<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 金飞鹰基础数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Softykt_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameSoftyktToken = 't_softykt_token';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_API_DB_CONFIG;
	}

	/**
	 * 获取存储的token
	 */
	public function getCorpToken($corp_id){
		$result = $this->setTable($this->tableNameSoftyktToken)
            ->addQueryFields('fk_corp as corp_id, token')
            ->addQueryConditions('fk_corp', $corp_id)
            ->doSelect();
        return empty($result)?array():$result[0];
	}

	public function getCorpInfo($corp_id){
		$result = $this->setTable($this->tableNameSoftyktToken)
            ->addQueryConditions('fk_corp', $corp_id)
            ->doSelect();
        return empty($result)?array():$result[0];
	}

	/**
	 * 更新数据库中的token
	 */
	public function updateCorpToken($corp_id, $token){
        $params = array(
            array('token', $token, '=')
        );

        $result = $this->setTable($this->tableNameSoftyktToken)
            ->addUpdateColumns($params)
            ->addQueryConditions('fk_corp', $corp_id)
            ->doUpdate();
        return $result;
	}

    public function listCorp($page = 1, $size = 20)
    {
        $model = $this->setTable($this->tableNameSoftyktToken)
              ->addQueryFields('fk_corp as corp_id');

        return $model->addOrderBy('ctime', 'desc')
                     ->doSelect($page, $size);
    }

} // END class Msg_model
