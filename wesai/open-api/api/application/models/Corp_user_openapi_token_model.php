<?php
require_once __DIR__ . '/ModelBase.php';
/**
 * Corp_user_token_model 
 * @user like
 * @date 2016-08-04
 * 
 * @uses MY
 * @uses _Model
 */
class Corp_user_openapi_token_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function get_access_token($access_token)
	{
		$sql = 'select fk_corp,fk_corp_user,fk_corp_user_openapi,access_token, access_time, expires_in from t_corp_user_openapi_token where access_token = ?';
		$params = array($access_token);
		return $this->getSingle(Pdo_Mysql::DSN_TYPE_SLAVE, $sql, $params);
	}

	public function set_openapi_access_token($fk_corp, $fk_corp_user, $fk_corp_user_openapi, $access_token, $access_time, $expires_in)
	{
        $sql = 'insert into t_corp_user_openapi_token (fk_corp, fk_corp_user, fk_corp_user_openapi, access_token, access_time, expires_in) 
                    values (:fk_corp, :fk_corp_user, :fk_corp_user_openapi, :access_token, :access_time, :expires_in) 
                    on duplicate key update access_token = :access_token, access_time = :access_time, expires_in = :expires_in';
        $params = compact('fk_corp', 'fk_corp_user', 'fk_corp_user_openapi', 'access_token', 'access_time', 'expires_in');
        $lastid = $this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sql, $params);
        return $lastid;
	}

}
