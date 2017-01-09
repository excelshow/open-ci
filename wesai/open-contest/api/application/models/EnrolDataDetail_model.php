<?php
/**
 * User: zhaodc
 * Date: 27/09/2016
 * Time: 10:31
 */
require_once __DIR__ . '/ModelBase.php';

class EnrolDataDetail_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create($params, $model = null)
	{
		return $this->getInstance($model)
		            ->setTable($this->tableNameEnrolDataDetail)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	public function getByEnrolDataIds($enrolDataIds)
	{
		return $this->setTable($this->tableNameEnrolDataDetail)
		            ->addQueryConditionIn('fk_enrol_data', $enrolDataIds)
		            ->addOrderBy('seq', 'asc')
		            ->addOrderBy('ctime', 'asc')
		            ->doSelect();
	}
}
