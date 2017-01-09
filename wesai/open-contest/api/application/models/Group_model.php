<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 18:13
 */
require_once __DIR__ . '/ModelBase.php';

class Group_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create($params)
	{
		return $this->setTable($this->tableNameGroup)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	public function getById($groupId)
	{
		$result = $this->setTable($this->tableNameGroup)
		               ->addQueryConditions('pk_group', $groupId)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function getByIds($groupIds)
	{
		return $this->setTable($this->tableNameGroup)
		            ->addQueryConditionIn('pk_group', $groupIds)
		            ->doSelect(1, count($groupIds));
	}

	public function modify($groupId, $params)
	{
		$columns = array();
		foreach ($params as $key => $val) {
			$columns[] = array($key, $val);
		}

		return $this->setTable($this->tableNameGroup)
		            ->addUpdateColumns($columns)
		            ->addQueryConditions('pk_group', $groupId)
		            ->doUpdate();
	}

	public function changeState($groupId, $fromState, $toState)
	{
		try {
			$this->beginTransaction();

			$affectedRows = $this->updateState($groupId, $fromState, $toState, __METHOD__);

			if (empty($affectedRows)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			$this->commit();

			return $affectedRows;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	public function updateState($groupId, $fromState, $toState, $remark, $model = null)
	{
		$this->writeStateLog($groupId, $fromState, $toState, $remark, $model);

		return $this->getInstance($model)
		            ->setTable($this->tableNameGroup)
		            ->addUpdateColumns('state', $toState)
		            ->addQueryConditions('pk_group', $groupId)
		            ->addQueryConditions('state', $fromState)
		            ->doUpdate();
	}

	private function writeStateLog($groupId, $fromState, $toState, $remark, $model = null)
	{
		$params = array(
			'fk_group'   => $groupId,
			'from_state' => $fromState,
			'to_state'   => $toState,
			'remark'     => $remark . '_' . $fromState . '_' . $toState,
		);

		return $this->getInstance($model)
		            ->setTable($this->tableNameGroupStateLog)
		            ->addInsertColumns($params)
		            ->doInsert();

	}

	public function listMemberByPage($groupId, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameMappingGroupUser)
		                    ->addQueryFieldsCount('*', 'count')
		                    ->addQueryConditions('fk_group', $groupId)
		                    ->addQueryConditions('state', MAPPING_STATE_OK)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameMappingGroupUser)
		            ->addQueryConditions('fk_group', $groupId)
		            ->addQueryConditions('state', MAPPING_STATE_OK)
		            ->addOrderBy('ctime', 'desc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function listMappingUserGroupsByPage($uid, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameMappingGroupUser)
		                    ->addQueryFieldsCount('*', 'count')
		                    ->addQueryConditions('fk_user', $uid)
		                    ->addQueryConditions('state', MAPPING_STATE_OK)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameMappingGroupUser)
		            ->addQueryConditions('fk_user', $uid)
		            ->addQueryConditions('state', MAPPING_STATE_OK)
		            ->addOrderBy('ctime', 'desc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function updateMappingState($groupId, $uid, $fromState, $toState, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameMappingGroupUser)
		            ->addUpdateColumns('state', $toState)
		            ->addQueryConditions('fk_group', $groupId)
		            ->addQueryConditions('fk_user', $uid)
		            ->addQueryConditions('state', $fromState)
		            ->doUpdate();
	}

	public function reduceCurMemberCount($groupId, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameGroup)
		            ->addUpdateColumns('cur_member_count', 1, '-')
		            ->addQueryConditions('pk_group', $groupId)
		            ->addQueryConditions('cur_member_count', 0, '>')
		            ->doUpdate();
	}

	public function increaseCurMemberCount($groupId)
	{
		return $this->setTable($this->tableNameGroup)
		            ->addUpdateColumns('cur_member_count', 1, '+')
		            ->addQueryConditions('pk_group', $groupId)
		            ->doUpdate();
	}

	public function createMapping($fk_group, $fk_user, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameMappingGroupUser)
		            ->addInsertColumns(compact('fk_group', 'fk_user'))
		            ->doInsert();
	}

	public function getMappingByUnqKey($groupId, $uid, $model = null)
	{
		$result = $this->getInstance($model)
		               ->setTable($this->tableNameMappingGroupUser)
		               ->addQueryConditions('fk_group', $groupId)
		               ->addQueryConditions('fk_user', $uid)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function listByUid($uid, $cid, $pageNumber, $pageSize, &$total)
	{
		$model = $this->setTable($this->tableNameGroup)
		              ->addQueryFieldsCount('*', 'count')
		              ->addQueryConditions('fk_user', $uid);

		if (!empty($cid)) {
			$model = $model->addQueryConditions('fk_contest', $cid);
		}

		$countResult = $model->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		$model = $this->setTable($this->tableNameGroup)
		              ->addQueryConditions('fk_user', $uid);

		if (!empty($cid)) {
			$model = $model->addQueryConditions('fk_contest', $cid);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect($pageNumber, $pageSize);
	}
}
