<?php
/**
 * User: zhaodc
 * Date: 30/09/2016
 * Time: 11:19
 */
require_once __DIR__ . '/ModelBase.php';

class FormItem_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getMaxSeq($formId)
	{
		$result = $this->setTable($this->tableNameFormItem)
		               ->addQueryFieldCalc('max', 'seq', 'seq')
		               ->addQueryConditions('fk_enrol_form', $formId)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return 0;
		}

		return intval($result[0]['seq']);
	}

	public function create($params)
	{
		return $this->setTable($this->tableNameFormItem)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	public function getById($formItemId)
	{
		$result = $this->setTable($this->tableNameFormItem)
		               ->addQueryConditions('pk_enrol_form_item', $formItemId)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function remove($formItemId)
	{
		return $this->setTable($this->tableNameFormItem)
		            ->addUpdateColumns('state', ENROL_FORM_ITEM_STATE_NG)
		            ->addQueryConditions('state', ENROL_FORM_ITEM_STATE_OK)
		            ->addQueryConditions('pk_enrol_form_item', $formItemId)
		            ->doUpdate();
	}

	public function listByForm($formId, $pageNumber, $pageSize)
	{
		return $this->setTable($this->tableNameFormItem)
		            ->addQueryConditions('fk_enrol_form', $formId)
		            ->addQueryConditions('state', ENROL_FORM_ITEM_STATE_OK)
		            ->addOrderBy('seq', 'asc')
		            ->doSelect($pageNumber, $pageSize);
	}

	public function modify($formItemId, $params)
	{
		$columns = array();
		foreach ($params as $key => $val) {
			$columns[] = array($key, $val, '=');
		}

		return $this->setTable($this->tableNameFormItem)
		            ->addUpdateColumns($columns)
		            ->addQueryConditions('pk_enrol_form_item', $formItemId)
		            ->doUpdate();
	}

	public function setSeqs($params)
	{
		if (empty($params)) {
			return false;
		}

		$arrSql = array();
		foreach ($params as $k => $v) {
			$arrSql[] = 'update t_enrol_form_item set seq = ' . intval($v['seq']) .
			            ' where pk_enrol_form_item = ' . intval($v['qid']) . ';';
		}

		$strSql = implode(' ', $arrSql);

		return $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $strSql, array());
	}

	public function listByFormIds($formIds)
	{
		return $this->setTable($this->tableNameFormItem)
			->addQueryConditionIn('fk_enrol_form', $formIds)
			->addQueryConditions('state', ENROL_FORM_ITEM_STATE_OK)
			->addOrderBy('seq', 'asc')
			->doSelect();
	}

}
