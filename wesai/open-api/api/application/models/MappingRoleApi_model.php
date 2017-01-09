<?php

require_once __DIR__ . '/ModelBase.php';

/**
 * User: zhaodc
 * Date: 8/1/16
 * Time: 14:40
 */
class MappingRoleApi_model extends ModelBase
{
	private $tableName = 't_mapping_role_api';

	public function __construct()
	{
		parent::__construct();
	}

	public function listApiByRole($role, $pageNumber, $pageSize, &$total, $state = null)
	{
		$params = array(
			'role' => $role,
		);

		$sqlSuffix = ' from ' . $this->tableName . ' where fk_role = :role ';

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

		$sql = 'select fk_api ' . $sqlSuffix . ' order by ctime desc';

		return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params, $pageNumber, $pageSize);
	}

	public function getMappingByUnqKey($role, $apiId)
	{
		$params = array(
			'role'  => $role,
			'apiId' => $apiId,
		);

		$sql = 'select * from ' . $this->tableName . ' where fk_role = :role and fk_api = :apiId';

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function bindMapping($role, $apiId)
	{
		$params = array(
			'fk_role' => $role,
			'fk_api'  => $apiId,
			'utime'   => null,
		);

		$exceptKeys = array('utime');

		return $this->mixedInsertData($this->tableName, $params, $exceptKeys);
	}

	public function getById($mappingId)
	{
		$sql = 'select * from ' . $this->tableName . ' where pk_mapping_role_api = :mappingId';

		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, compact('mappingId'));
	}

	public function unbindMapping($mappingId)
	{
		$params = array(
			'state' => ROLE_API_MAPPING_STATE_NG,
		);

		$conditions = array(
			'pk_mapping_role_api' => $mappingId,
			'state'               => ROLE_API_MAPPING_STATE_OK,
		);

		return $this->mixedUpdateData($this->tableName, $params, $conditions);
	}

	public function reBindMapping($mappingId)
	{
		$params = array(
			'state' => ROLE_API_MAPPING_STATE_OK,
		);

		$conditions = array(
			'pk_mapping_role_api' => $mappingId,
			'state'               => ROLE_API_MAPPING_STATE_NG,
		);

		return $this->mixedUpdateData($this->tableName, $params, $conditions);
	}
}
