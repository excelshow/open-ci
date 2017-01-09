<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 18:13
 */
require_once __DIR__ . '/ModelBase.php';

class Team_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create($params)
	{
		return $this->setTable($this->tableNameTeam)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	public function getById($teamId)
	{
		$result = $this->setTable($this->tableNameTeam)
		               ->addQueryConditions('pk_team', $teamId)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function getByIds($teamIds)
	{
		return $this->setTable($this->tableNameTeam)
		            ->addQueryConditionIn('pk_team', $teamIds)
		            ->doSelect(1, count($teamIds));
	}

	public function modify($teamId, $params)
	{
		$columns = array();
		foreach ($params as $key => $val) {
			$columns[] = array($key, $val);
		}

		return $this->setTable($this->tableNameTeam)
		            ->addUpdateColumns($columns)
		            ->addQueryConditions('pk_team', $teamId)
		            ->doUpdate();
	}

	public function changeState($teamId, $fromState, $toState)
	{
		try {
			$this->beginTransaction();

			$affectedRows = $this->updateState($teamId, $fromState, $toState, __METHOD__);

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

	public function updateState($teamId, $fromState, $toState, $remark, $model = null)
	{
		$this->writeStateLog($teamId, $fromState, $toState, $remark, $model);

		return $this->getInstance($model)
		            ->setTable($this->tableNameTeam)
		            ->addUpdateColumns('state', $toState)
		            ->addQueryConditions('pk_team', $teamId)
		            ->addQueryConditions('state', $fromState)
		            ->doUpdate();
	}

	private function writeStateLog($teamId, $fromState, $toState, $remark, $model = null)
	{
		$params = array(
			'fk_team'    => $teamId,
			'from_state' => $fromState,
			'to_state'   => $toState,
			'remark'     => $remark . '_' . $fromState . '_' . $toState,
		);

		return $this->getInstance($model)
		            ->setTable($this->tableNameTeamStateLog)
		            ->addInsertColumns($params)
		            ->doInsert();

	}

	public function listMembersByPage($teamId, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameMappingTeamUser)
		                    ->addQueryFieldsCount('*', 'count')
		                    ->addQueryConditions('fk_team', $teamId)
		                    ->addQueryConditions('state', MAPPING_STATE_OK)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameMappingTeamUser)
		            ->addQueryConditions('fk_team', $teamId)
		            ->addQueryConditions('state', MAPPING_STATE_OK)
		            ->addOrderBy('ctime', 'desc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function listMappingUserTeamsByPage($uid, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameMappingTeamUser)
		                    ->addQueryFieldsCount('*', 'count')
		                    ->addQueryConditions('fk_user', $uid)
		                    ->addQueryConditions('state', MAPPING_STATE_OK)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameMappingTeamUser)
		            ->addQueryConditions('fk_user', $uid)
		            ->addQueryConditions('state', MAPPING_STATE_OK)
		            ->addOrderBy('ctime', 'desc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function updateMappingState($teamId, $uid, $fromState, $toState, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameMappingTeamUser)
		            ->addUpdateColumns('state', $toState)
		            ->addQueryConditions('fk_team', $teamId)
		            ->addQueryConditions('fk_user', $uid)
		            ->addQueryConditions('state', $fromState)
		            ->doUpdate();
	}

	public function reduceCurMemberCount($teamId, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameTeam)
		            ->addUpdateColumns('cur_member_count', 1, '-')
		            ->addQueryConditions('pk_team', $teamId)
		            ->addQueryConditions('cur_member_count', 0, '>')
		            ->doUpdate();
	}

	public function increaseCurMemberCount($teamId, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameTeam)
		            ->addUpdateColumns('cur_member_count', 1, '+')
		            ->addQueryConditions('pk_team', $teamId)
		            ->addQueryConditions('cur_member_count', 'max_member_count', '<', true)
		            ->doUpdate();
	}

	public function createMapping($fk_team, $fk_user, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameMappingTeamUser)
		            ->addInsertColumns(compact('fk_team', 'fk_user'))
		            ->doInsert();
	}

	public function getMappingByUnqKey($teamId, $uid, $model = null)
	{
		$result = $this->getInstance($model)
		               ->setTable($this->tableNameMappingTeamUser)
		               ->addQueryConditions('fk_team', $teamId)
		               ->addQueryConditions('fk_user', $uid)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function listByUid($uid, $itemId, $pageNumber, $pageSize, &$total)
	{

		$model = $this->setTable($this->tableNameTeam)
		              ->addQueryFieldsCount('*', 'count')
		              ->addQueryConditions('fk_user', $uid);

		if (!empty($itemId)) {
			$model = $model->addQueryConditions('fk_contest_items', $itemId);
		}

		$countResult = $model->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		$model = $this->setTable($this->tableNameTeam)
		              ->addQueryConditions('fk_user', $uid);

		if (!empty($itemId)) {
			$model = $model->addQueryConditions('fk_contest_items', $itemId);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect($pageNumber, $pageSize);
	}
}
