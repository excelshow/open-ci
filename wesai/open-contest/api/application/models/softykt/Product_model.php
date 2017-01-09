<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 金飞鹰项目(产品)数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Product_model extends MY_Model
{
	protected $tableNameContestItems = 't_contest_items';
	protected $tableNameContest = 't_contest';
	protected $tableNameContestExtPartnerSoftykt = 't_contest_ext_partner_softykt';


	public function __construct()
	{
		parent::__construct();
		$this->load->model('softykt/Softykt_contest_item_model');
	}

	public function get_db()
	{
		return CONTEST_DB_CONFIG;
	}

	/**
	 * @param $contestParam 活动参数
	 * @param $contestItemParam 项目参数
	 * @param $param  ext关系参数
	 */
	public function create($contestParam, $contestItemParam, $extContestparam){
		try {
			$this->beginTransaction();

			//先创建活动
			$contestId = $this->createScenic($contestParam);

			//再创建商品.票
			$contestItemid = $this->createItem($contestItemParam,$contestId);

			//添加ext关系表
			$extId = $this->createExt($contestId, $extContestparam);

			$this->commit();
			return $extId;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 创建活动
	 * @param $param
	 * @return id
	 */
	public function createScenic($param){

		$result =  $this->setTable($this->tableNameContest)
						->addInsertColumns($param)
						->doInsert();
		return $result;
	}

	/**
	 * 创建项目
	 * @param $param
	 * @param $contestId
	 * @return id
	 */
	public function createItem($param, $contestId){

		$param['fk_contest'] = $contestId;
		$result =  $this->setTable($this->tableNameContestItems)
						->addInsertColumns($param)
						->doInsert();

		return $result;
	}

	/**
	 * 创建ext关系
	 * @param $contestId
	 * @param $productId
	 * @param $param
	 * @return bool
	 */
	public function createExt($contestId, $param){
		$param['fk_contest'] = $contestId;
		$result =  $this->setTable($this->tableNameContestExtPartnerSoftykt)
			->addInsertColumns($param)
			->doInsert();

		return $result;
	}


	/**
	 * 编辑产品
	 * @param $contestParam
	 * @param $contestItemParam
	 * @param $extContestparam
	 * @param $extInfo
	 */
	public function modify($contestParam, $contestItemParam, $extContestparam, $extInfo){
		try {
			$this->beginTransaction();
			//先修改活动
			$contestId = $this->modifyScenic($contestParam, $extInfo['fk_contest']);
			//再修改商品.票
			$contestItemid = $this->modifyItem($contestItemParam, $extInfo['fk_contest']);
			//修改ext关系表
			$extId = $this->modifyExt($extInfo['pk_ext_partner_softykt'], $extContestparam);
			$this->commit();
			if($contestId > 0){
				return $contestId;
			}
			return $contestItemid > $extId ? $contestItemid : $extId;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}


	/**
	 * 修改活动信息
	 * @param $param
	 * @param $fk_contest
	 * @return bool|int
	 */
	public function modifyScenic($param, $fk_contest){
		$condParam = array(
			array('pk_contest', $fk_contest, '=')
		);
		foreach($param as $k=>$v){
			$updateParam[] = array(
				$k, $v, '='
			);
		}
//		$updateParam = array(
//			array('service_tel', $param['service_tel'], '='),
//			array('name', $param['name'], '='),
//			array('gtype' , $param['gtype'], '='),
//			array('location' , $param['location'], '='),
//			array('publish_state', $param['publish_state'], '='),
//			array('gtype' , $param['gtype'], '='),
//			array('logo' , $param['logo'], '='),
//			array('poster', $param['poster'], '='),
//			array('banner' , $param['banner'], '='),
//			array('sdate_start', $param['sdate_start'], '='),
//			array('fk_corp', $param['fk_corp'], '='),
//			array('fk_corp_user', $param['fk_corp_user'], '='),
//			array('template', $param['template'], '='),
//			array('intro', $param['intro'], '='),
//		);
		$result = $this->setTable($this->tableNameContest)
			->addUpdateColumns($updateParam)
			->addQueryConditions($condParam)
			->doUpdate();
		return $result;
	}

	/**
	 * 修改项目信息
	 * @param $contestItemParam
	 * @param $fk_contest
	 * @return bool|int
	 */
	public function modifyItem($contestItemParam, $fk_contest){
		$condParam = array(
			array('fk_contest', $fk_contest, '=')
		);
		foreach($contestItemParam as $k=>$v){
			$updateParam[] = array(
				$k, $v, '='
			);
		}
		$result = $this->setTable($this->tableNameContestItems)
			->addUpdateColumns($updateParam)
			->addQueryConditions($condParam)
			->doUpdate();
		return $result;
	}

	/**
	 * 修改项目跟金飞鹰产品关系表
	 * @param $pk_ext_partner_softykt
	 * @param $extContestparam
	 * @return bool|int
	 */
	public function modifyExt($pk_ext_partner_softykt, $extContestparam){
		$condParam = array(
			array('pk_ext_partner_softykt', $pk_ext_partner_softykt, '=')
		);
		foreach($extContestparam as $k=>$v){
			$updateParam[] = array(
				$k, $v, '='
			);
		}
		$result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
			->addUpdateColumns($updateParam)
			->addQueryConditions($condParam)
			->doUpdate();
		return $result;
	}


	/**
	 * 获取项目信息
	 */
	public function get_by_fk_contest($fk_contest){

		$result = $this->setTable($this->tableNameContest)
			->addQueryConditions('pk_contest', $fk_contest)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 获取项目信息
	 */
	public function get_by_fk_contest_items($fk_contest_items){

		$result = $this->setTable($this->tableNameContestItems)
			->addQueryConditions('pk_contest_items', $fk_contest_items)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 修改商品状态
	 * @param $productid
	 * @param $useflag
	 * @param $fromState
	 * @return bool | object
	 */
	public function productStaticSve($fk_contest, $fromState){
		$condParam = array(
			array('pk_contest', $fk_contest, '='),
			array('publish_state', $fromState, '=')
		);
		$useflag = CONTEST_PUBLISH_STATE_OFF;//默认下架活动
		$result = $this->setTable($this->tableNameContest)
			->addUpdateColumns('publish_state', $useflag)
			->addQueryConditions($condParam)
			->doUpdate();
		return $result;
	}

	/**
	 * 查询对应的活动id
	 * @param $scenicid  景区id
	 * @param $productid 产品id
	 * @return mixed
	 */
	public function get_by_scenicid_productid($scenicid, $productid){

		$param = array(
			array('scenicid', $scenicid, '='),
			array('productid', $productid, '=')
		);
		$result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
			->addQueryConditions($param)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 查询对应的景区id 产品id
	 * @param $fk_content 活动id
	 * @return mixed
	 */
	public function get_by_partner_fk_contest($fk_contest){

		$param = array(
			array('fk_contest', $fk_contest, '=')
		);
		$result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
			->addQueryConditions($param)
			->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 查询对应的景区id 产品id
	 * @param $fk_contents 活动id组合 用,分割
	 * @return mixed
	 */
	public function get_by_partner_fk_contest_ids($fk_contests = 0){

		$result = $this->setTable($this->tableNameContestExtPartnerSoftykt)
			->addQueryConditionIn('fk_contest',$fk_contests)
			->doSelect();
		if($result){
			return $result;
		}
	}

} // END class Msg_model
