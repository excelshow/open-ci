<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 13:48
 */
require_once __DIR__ . '/ModelBase.php';

class InviteCode_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create($itemId, $code)
	{
		$params = array(
			'fk_contest_items' => $itemId,
			'invite_code'      => $code,
			'utime'            => null,
		);

		$exceptKeys = array('utime');

		return $this->setTable($this->tableNameInviteCode)
		            ->addInsertColumns($params, $exceptKeys)
		            ->doInsert();
	}

	public function expireAll($itemId)
	{
		return $this->setTable($this->tableNameInviteCode)
		            ->addUpdateColumns('state', CONTEST_ITEM_INVITE_CODE_STATE_EXPIRED)
		            ->addQueryConditions('fk_contest_items', $itemId)
		            ->addQueryConditions('state', CONTEST_ITEM_INVITE_CODE_STATE_UNUSED)
		            ->doUpdate();
	}

	public function expireExt($itemId, $lastCodeId)
	{
		return $this->setTable($this->tableNameInviteCode)
		            ->addUpdateColumns('state', CONTEST_ITEM_INVITE_CODE_STATE_EXPIRED)
		            ->addQueryConditions('fk_contest_items', $itemId)
		            ->addQueryConditions('state', CONTEST_ITEM_INVITE_CODE_STATE_UNUSED)
		            ->addQueryConditions('pk_enrol_invite_code', $lastCodeId, '>')
		            ->doUpdate();

	}

	public function listByPage($itemId, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameInviteCode)
		                    ->addQueryFieldsCount('pk_enrol_invite_code', 'count')
		                    ->addQueryConditions('fk_contest_items', $itemId)
		                    ->addQueryConditions('state', CONTEST_ITEM_INVITE_CODE_STATE_UNUSED)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameInviteCode)
		            ->addQueryConditions('fk_contest_items', $itemId)
		            ->addQueryConditions('state', CONTEST_ITEM_INVITE_CODE_STATE_UNUSED)
		            ->addOrderBy('pk_enrol_invite_code', 'asc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function getByCode($itemId, $code)
	{
		$result = $this->setTable($this->tableNameInviteCode)
		               ->addQueryConditions('fk_contest_items', $itemId)
		               ->addQueryConditions('invite_code', $code)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}


	public function consume($itemId, $code, $enrolDataId, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameInviteCode)
		            ->addUpdateColumns('state', CONTEST_ITEM_INVITE_CODE_STATE_USED)
		            ->addUpdateColumns('fk_enrol_data', $enrolDataId)
		            ->addQueryConditions('state', CONTEST_ITEM_INVITE_CODE_STATE_UNUSED)
		            ->addQueryConditions('invite_code', $code)
		            ->addQueryConditions('fk_contest_items', $itemId)
		            ->doUpdate();
	}
}
