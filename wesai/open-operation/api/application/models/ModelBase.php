<?php

/**
 * User: liangkaixuan
 */
class ModelBase extends MY_Model
{
	protected $tableNameVoucherRule 			= 't_voucher_rule';
	protected $tableNameVoucherRuleStateLog    	= 't_voucher_rule_state_log';
	protected $voucherRuleStatic           	   	= 1;

	protected $tableNameVoucher            	   	= 't_voucher';
	protected $tableNameVoucherStateLog        	= 't_voucher_state_log';
	protected $voucherStatic           	   	   	= 0;

	protected $tableNameVoucherUserChangeLog    = 't_voucher_user_change_log';
	protected $tableNameVoucherConsumeLog    	= 't_voucher_consume_log';

	protected $tableNameActivity    			= 't_activity';

	protected $tableNameMappingActivityOperation= 't_mapping_activity_operation';


    protected $tableNameCard                      = 't_card';
    protected $tableNameCardStateLog              = 't_card_state_log';
    protected $tableNameCardMemberCard            = 't_card_member_card';
    protected $tableNameCardMemberCardCustomField = 't_card_member_card_custom_field';
    protected $tableNameCardTextImageList         = 't_card_text_image_list';
    protected $tableNameCode                      = 't_card_code';
    protected $tableNameCodeStateLog              = 't_card_code_state_log';
    protected $tableNameCardCodeBalanceLog        = 't_card_code_balance_log';
    protected $tableNameCardCodeBonusLog          = 't_card_code_bonus_log';
    protected $tableNameCodeWxStateLog            = 't_card_code_wx_state_log';
    protected $tableNameCardCodeCustomValue       = 't_card_member_card_code_custom_value';


	public function __construct()
	{
		parent::__construct();
	}

	protected function getInstance($model = null)
	{
		if (!empty($model)) {
			return $model;
		}

		return $this;
	}

	protected function newTimestamp(){
		return time();
	}
	protected function newDatetime(){
		return date('Y-m-d H:i:s',time());
	}

	protected function mixedInsertData($tableName, $params, $exceptKeys = array())
	{
		$sqlData = $this->makeInsertSqlData($tableName, $params, $exceptKeys);

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
			'e_trace' => $e->getTrace()[0],
		);

		log_message_v2('error', $errMsg);
	}

	protected function generateVerifyCode()
	{
		return VERIFY_CODE_PREFIX . mt_rand(pow(10, VERIFY_CODE_LENGTH - 1), pow(10, VERIFY_CODE_LENGTH) - 1);
	}

    protected function makeORMUpdateColumns($params)
    {
        $return_value = [];
        foreach ($params as $key => $val) {
            $return_value[] = [$key, $val, '='];
        }

        return $return_value;
    }
}
