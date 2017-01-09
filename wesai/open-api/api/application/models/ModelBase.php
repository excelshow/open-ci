<?php

/**
 * User: zhaodc
 * Date: 7/31/16
 * Time: 22:09
 */
class ModelBase extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_API_DB_CONFIG;
	}

	protected function mixedInsertData($tableName, $params, $exceptKeys = array())
	{
		$sqlData = $this->makeInsertSqlData($tableName, $params, $exceptKeys);
		if (false === $sqlData) {
			return false;
		}

		return $this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sqlData['sql'], $sqlData['bindParams']);
	}

	protected function makeInsertSqlData($tableName, $params, $exceptKeys = array(), $bindParamsIndex = 0)
	{
		if (empty($tableName) || empty($params)) {
			$errMsg              = array();
			$errMsg['msg']       = 'params empty';
			$errMsg['tableName'] = $tableName;
			$errMsg['params']    = $params;
			log_message_v2('error', $errMsg);

			return false;
		}

		$columns    = array();
		$values     = array();
		$bindParams = array();

		foreach ($params as $k => $v) {
			if (empty($v) && !in_array($k, $exceptKeys)) {
				continue;
			}

			$bindValueKey              = $k . '_' . $bindParamsIndex;
			$columns[]                 = $k;
			$values[]                  = ':' . $bindValueKey;
			$bindParams[$bindValueKey] = $v;
		}

		if (empty($columns) || empty($values) || empty($bindParams) ||
		    (count($columns) != count($bindParams)) ||
		    (count($columns) != count($values))
		) {
			$errMsg               = array();
			$errMsg['msg']        = 'params empty';
			$errMsg['tableName']  = $tableName;
			$errMsg['params']     = $params;
			$errMsg['columns']    = $columns;
			$errMsg['values']     = $values;
			$errMsg['bindParams'] = $bindParams;
			log_message_v2('error', $errMsg);

			return false;
		}

		$strColumns = implode(',', $columns);
		$strValues  = implode(', ', $values);

		$sql = 'insert into %s (%s) values (%s) ';
		$sql = sprintf($sql, $tableName, $strColumns, $strValues);

		return compact('sql', 'bindParams');
	}

	protected function mixedUpdateData($tableName, $params, $conditions, $exceptKeys = array())
	{
		$sqlData = $this->makeUpdateSqlData($tableName, $params, $conditions, $exceptKeys);
		if (false === $sqlData) {
			return false;
		}

		return $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sqlData['sql'], $sqlData['bindParams']);
	}

	protected function makeUpdateSqlData($tableName, $params, $conditions, $exceptKeys = array(), $bindParamsIndex = 0)
	{
		$sql = 'update %s set %s where %s';

		if (empty($tableName) || empty($params) || empty($conditions)) {
			$errMsg               = array();
			$errMsg['msg']        = 'params empty';
			$errMsg['tableName']  = $tableName;
			$errMsg['params']     = $params;
			$errMsg['conditions'] = $conditions;
			log_message_v2('error', $errMsg);
		}

		$columns    = array();
		$bindParams = array();

		foreach ($params as $k => $v) {
			if (empty($v) && !in_array($k, $exceptKeys)) {
				continue;
			}

			$bindValueKey              = $k . '_' . $bindParamsIndex;
			$columns[]                 = $k . '= :' . $bindValueKey;
			$bindParams[$bindValueKey] = $v;
		}

		$wheres = array();
		foreach ($conditions as $k => $v) {
			$bindValueKey = $k . '_' . $bindParamsIndex;
			if (array_key_exists($bindValueKey, $bindParams)) {
				$t = microtime(true) * 10000;
				$bindValueKey .= '_' . $t;
				$wheres[]                  = $k . '=:' . $bindValueKey;
				$bindParams[$bindValueKey] = $v;
				continue;
			}
			$wheres[]                  = $k . '=:' . $bindValueKey;
			$bindParams[$bindValueKey] = $v;
		}

		if (empty($columns) || empty($wheres) || empty($bindParams) ||
		    ((count($columns) + count($wheres)) != count($bindParams))
		) {
			$errMsg               = array();
			$errMsg['msg']        = 'params empty';
			$errMsg['tableName']  = $tableName;
			$errMsg['params']     = $params;
			$errMsg['columns']    = $columns;
			$errMsg['wheres']     = $wheres;
			$errMsg['bindParams'] = $bindParams;
			log_message_v2('error', $errMsg);

			return false;
		}

		$strColumns = implode(',', $columns);
		$strWheres  = implode(' and ', $wheres);
		$sql        = sprintf($sql, $tableName, $strColumns, $strWheres);

		return compact('sql', 'bindParams');
	}

	protected function logException(Exception $e)
	{
		$errMsg = array(
			'msg'     => 'Exception occurred',
			'e_code'  => $e->getCode(),
			'e_line'  => $e->getLine(),
			'e_file'  => $e->getFile(),
			'e_msg'   => $e->getMessage(),
			'e_trace' => $e->getTrace(),
		);

		log_message_v2('error', $errMsg);
	}
}
