<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 金飞鹰景区id、产品id对应活动id、项目id类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Softykt_contest_item_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameContestExtPartnerSoftykt = 't_contest_ext_partner_softykt';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return CONTEST_DB_CONFIG;
	}



	/**
	 * 根据景区id 活动id 添加对应关系
	 */
	public function create_scenicid($scenicid, $fk_contest){
		$param = array(
			'scenicid' => $scenicid,
			'fk_contest' => $fk_contest
		);
		$result = $this->setTable($this->tableNameSoftyktContest)
			->addInsertColumns($param)
			->doInsert();
		return $result;
	}


	/**
	 * 根据景区id 获取对应的数据
	 */
	public function get_by_item_scenicid($scenicid){

		$result = $this->setTable($this->tableNameSoftyktContestItem)
			->addQueryConditions('scenicid', $scenicid)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 根据活动id 获取对应的数据
	 */
	public function get_by_fk_contest($fk_contest){

		$result = $this->setTable($this->tableNameSoftyktContestItem)
			->addQueryConditions('fk_contest', $fk_contest)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 根据项目id 获取对应的数据
	 */
	public function get_by_fk_item($fk_item){

		$result = $this->setTable($this->tableNameSoftyktContestItem)
			->addQueryConditions('fk_item', $fk_item)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 根据产品id 获取对应的数据
	 */
	public function get_by_productid($productid){

		$result = $this->setTable($this->tableNameSoftyktContestItem)
			->addQueryConditions('productid', $productid)
			->doSelect();
		if($result){
			return $result[0];
		}
	}



} // END class Msg_model
