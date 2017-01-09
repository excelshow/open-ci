<?php

require_once __DIR__ . '/ModelBase.php';

/**
 * User: zhaodc
 * Date: 7/31/16
 * Time: 22:10
 */
class ApiParams_model extends ModelBase
{
	private $tableName = 't_api_params';

	public function __construct()
	{
		parent::__construct();
	}

	public function addParam($params)
	{
		$params['utime'] = null;

		$exceptKeys = array('utime');

		return $this->mixedInsertData($this->tableName, $params, $exceptKeys);
	}

	public function getById($paramId)
	{
		$sql = 'select * from ' . $this->tableName . ' where pk_api_params = :paramId';

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, compact('paramId'));
	}

	public function updateParam($paramId, $params)
	{
		$conditions = array(
			'pk_api_params' => $paramId,
		);

		return $this->mixedUpdateData($this->tableName, $params, $conditions);
	}

	public function deleteParam($paramId)
	{
		$params = array(
			'state' => API_PARAMS_STATE_NG,
		);

		$conditions = array(
			'pk_api_params' => $paramId,
			'state'         => API_PARAMS_STATE_OK,
		);

		return $this->mixedUpdateData($this->tableName, $params, $conditions);
	}

	public function listByPkApi($apiId, $state)
	{
		$params = array(
			'apiId' => $apiId,
		);

		$sql = 'select pk_api_params,io_type,param_name,param_type,param_name_internal,parent_id,param_null,mark, state ';
		$sql .= 'from ' . $this->tableName . ' where fk_api = :apiId ';

		if (!empty($state)) {
			$params['state'] = $state;
			$sql .= ' and state = :state ';
		}

		$sql .= ' order by parent_id,param_order,ctime';

		return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function listParams($apiId, $ioType)
	{
		$params = array(
			'apiId'  => $apiId,
			'ioType' => $ioType,
			'state'  => API_PARAMS_STATE_OK,
		);

		$sql = 'select * from ' . $this->tableName . ' where fk_api = :apiId and io_type = :ioType and state = :state order by ctime asc';

		return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params, 1, 100);
	}

	public function verifyApiParamsDuplicated($apiId, $paramName, $ioType, $parentId)
	{
		$params = compact('apiId', 'paramName', 'ioType', 'parentId');

		$sql = 'select * from ' . $this->tableName . ' where fk_api = :apiId and param_name = :paramName and io_type = :ioType and parent_id = :parentId';

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}
	public function restoreParam($paramId)
	{
		$params = array(
			'state' => API_PARAMS_STATE_OK,
		);

		$conditions = array(
			'pk_api_params' => $paramId,
			'state'         => API_PARAMS_STATE_NG,
		);

		return $this->mixedUpdateData($this->tableName, $params, $conditions);
	}

}
