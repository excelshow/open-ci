<?php

require_once __DIR__ . '/ModelBase.php';

/**
 * User: zhaodc
 * Date: 7/31/16
 * Time: 22:09
 */
class Api_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getByUnqKey($system, $path)
	{
		$sql    = 'select * from t_api where api_system = :system and api = :path';
		$params = compact('system', 'path');

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function getById($apiId)
	{
		$sql = 'select * from t_api where pk_api = :apiId';

		$params = compact('apiId');

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function getBySystemApi($api_system, $api, $method)
	{
		$sql = 'select * from t_api where api_system = :api_system and api = :api and method = :method';

		$params = compact('api_system', 'api', 'method');

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function addApi($params)
	{
		$params['utime'] = null;

		return $this->mixedInsertData('t_api', $params, ['utime']);
	}

	public function updateApi($apiId, $params)
	{
		$conditions = array(
			'pk_api' => $apiId,
		);

		return $this->mixedUpdateData('t_api', $params, $conditions);
	}

	public function deleteApi($apiId)
	{
		$params = array(
			'state' => OPEN_API_STATE_NG,
		);

		$conditions = array(
			'state'  => OPEN_API_STATE_OK,
			'pk_api' => $apiId,
		);

		return $this->mixedUpdateData('t_api', $params, $conditions);
	}

	public function listApiBySystem($system, $pageNumber, $pageSize, &$total, $state = null)
	{
		$params = array(
			'system' => $system,
		);

		$sqlSuffix = ' from t_api where api_system = :system ';
		if (!empty($state)) {
			$params['state'] = $state;
			$sqlSuffix .= ' and state = :state ';
		}

		$sql = 'select count(*) as cnt ' . $sqlSuffix;

		$count = $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
		if (empty($count)) {
			return false;
		}

		$total = $count['cnt'];

		$sql = 'select * ' . $sqlSuffix . ' order by ctime desc';

		return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params, $pageNumber, $pageSize);
	}

	public function listApiByIds($idList)
	{
		$params = array(
			'state' => OPEN_API_STATE_OK,
		);

		$ids = implode(',', $idList);
		$sql = 'select * from t_api where pk_api in (%s) and state = :state';

		$sql = sprintf($sql, $ids);

		return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params, 1, count($ids));
	}

	public function restoreApi($apiId)
	{
		$params = array(
			'state' => OPEN_API_STATE_OK,
		);

		$conditions = array(
			'state'  => OPEN_API_STATE_NG,
			'pk_api' => $apiId,
		);

		return $this->mixedUpdateData('t_api', $params, $conditions);
	}
}
