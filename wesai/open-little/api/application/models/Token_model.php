<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * token基础数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Token_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameToken = 't_token';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_LITTLE_DB_CONFIG;
	}

	/**
	 * 获取存储的token
	 */
	public function getToken(){
        $queryFields = array(
            'pk_token as token_id',
            'token'
        );
		$result = $this->setTable($this->tableNameToken)
            ->addQueryFields($queryFields)
            ->doSelect();
        return empty($result)?array():$result[0];
	}


	/**
	 * 更新数据库中的token
	 */
	public function updateToken($token){

        $tokenInfo = $this->getToken();
        if(empty($tokenInfo)){
            //添加
            $addParam = array(
                'token' => $token
            );
            $result = $this->setTable($this->tableNameToken)
                ->addInsertColumns($addParam)
                ->doInsert();
        }else{
            $params = array(
                array('token', $token, '=')
            );
            $result = $this->setTable($this->tableNameToken)
                ->addUpdateColumns($params)
                ->addQueryConditions('pk_token', $tokenInfo['token_id'])
                ->doUpdate();
        }
        return $result;
	}


} // END class Msg_model
