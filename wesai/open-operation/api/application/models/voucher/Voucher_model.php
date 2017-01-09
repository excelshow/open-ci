<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 代金券数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Voucher_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameVoucher            	   	= 't_voucher';
	protected $tableNameVoucherUserChangeLog    = 't_voucher_user_change_log';
	protected $tableNameVoucherStateLog        	= 't_voucher_state_log';
	protected $tableNameVoucherRule 			= 't_voucher_rule';
	public function __construct()
	{
		parent::__construct();
		$this->load->model('voucher/Rule_model');
	}

	public function get_db()
	{
		return OPERATION_DB_CONFIG;
	}


	/**
	 * 新增代金券
	 * @param $voucherData
	 * @param $ruleInfo
	 *
	 * @return array
	 * @throws Exception
	 */
	public function create($voucherData, $ruleInfo)
	{
		try {
			$this->beginTransaction();
			$okCount = 0;
			if(($ruleInfo['number'] - $ruleInfo['number_created']) < $voucherData['number']){
				$voucherData['number'] = $ruleInfo['number'] - $ruleInfo['number_created'];
			}
			for($i = 0; $i < $voucherData['number'];$i++){
				while (true){
					$code = OPERATION_VOUCHER_CODE . create_rand_number(10);
					$voucher = $this->get_by_code($code);
					if(empty($voucher)){
						break;
					}
				}
				$params = array(
					'code'	 		=> $code,
					'scope_type'     => $ruleInfo['scope_type'],
					'state' 		=> OPERATION_VOUCHER_STATE_NOTALLOT,
					'value'    		=> $ruleInfo['value'],
					'value_min'     => $ruleInfo['value_min'],
					'fk_voucher_rule'=> $voucherData['fk_voucher_rule'],
					'fk_corp'		=> $ruleInfo['fk_corp'],
					'stop_time'		=> $ruleInfo['date_stop']
				);
				$result = $this->setTable($this->tableNameVoucher)
					->addInsertColumns($params)
					->doInsert();
				if(!empty($result)){
					$okCount++;
				}
			}
			$number_created = $ruleInfo['number_created']+$okCount;
			if( $number_created >= $ruleInfo['number']){
				$state = OPERATION_VOUCHER_RULE_STATE_GENERATED;
			}else{
				$state = OPERATION_VOUCHER_RULE_STATE_GENERATING;
			}
			//修改代金券规则信息
			$this->Rule_model->updateNumber($ruleInfo['pk_voucher_rule'], $number_created, $state);

			$this->commit();
			return array(
				'number' => $okCount,
				'state' => $state
			);
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}


	}

	/**
	 * 获取代金券列表
	 * @param $voucherData array
	 *
	 * @return data
	 */
	public function lists($voucherData,$page,$size,$order,$sort){
		$param = array(
			array('fk_corp',$voucherData['fk_corp'],'='),
			array('fk_voucher_rule',$voucherData['fk_voucher_rule'],'=')
		);

		if(!empty($voucherData['state'])){
			$param[] = array('state',$voucherData['state'],'=');
		}
		if(!empty($voucherData['code'])){
			$param[] = array('code',$voucherData['code'],'=');
		}
		$queryFields = array(
			'pk_voucher as voucher_id',
			'fk_corp as corp_id',
			'fk_voucher_rule as voucher_rule_id',
			'fk_component_authorizer_app as component_authorizer_app_id',
			'code',
			'scope_type',
			'state',
			'value',
			'value_min',
			'used_time',
			'stop_time',
			'fk_user as user_id',
			'fk_order as order_id'
		);
		$orderParam = array(
			$order 		 => $sort,
			'pk_voucher' => $sort
		);

		$return['data'] = $this->setTable($this->tableNameVoucher)
								->addQueryFields($queryFields)
								->addQueryConditions($param)
								->addOrderBy($orderParam)
								->doSelect($page, $size);
		$total = $this->setTable($this->tableNameVoucher)
						->addQueryFieldsCount('pk_voucher')
						->addQueryConditions($param)
						->doSelect();
		$return['sum'] = $total[0]['pk_voucher'];
		return $return;
	}

	/**
	 * 根据代金券id等修改代金券所属用户
	 * @param $voucherData
	 *
	 * @return bool|int
	 * @throws Exception
	 */
	public function change_user($voucherData){
		//先修改代金券表中所属用户 再生成log
		$voucherInfo = $this->get_by_pk_voucher($voucherData['voucher_id']);
		if(empty($voucherInfo)){
			return false;
		}

		try {
			$this->beginTransaction();

			$params = array(
				array('fk_user', $voucherData['to_user_id'], '='),
				array('fk_component_authorizer_app', $voucherData['component_authorizer_app_id'], '='),
				array('state', OPERATION_VOUCHER_STATE_NOTUSE, '=')//状态从未分配改为已分配
			);

			//修改所属用户
			$result = $this->setTable($this->tableNameVoucher)
							->addUpdateColumns($params)
							->addQueryConditions('pk_voucher', $voucherData['voucher_id'])
							->doUpdate();
			if($voucherInfo['state'] == OPERATION_VOUCHER_STATE_NOTALLOT){
				$this->logVoustatechange($voucherData['voucher_id'],OPERATION_VOUCHER_STATE_NOTALLOT,OPERATION_VOUCHER_STATE_NOTUSE);
			}
			//生成log
			$this->logVouuserchange($voucherData['voucher_id'],$voucherInfo['fk_user'],$voucherData['to_user_id']);

			$this->commit();
			return $result;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}

	}

	/**
	 * 代金券所属用户修改 生成log
	 * @param 代金券id 原用户id 转让给用户id
	 */
	public function logVouuserchange($pk_voucher,$front_user_id,$to_user_id){
		$param = array(
			'fk_voucher'   => $pk_voucher,
			'from_fk_user' => $front_user_id,
			'to_fk_user'   => $to_user_id
		);
		$this->setTable($this->tableNameVoucherUserChangeLog)
					->addInsertColumns($param)
					->doInsert();
	}

	/**
	 * 代金券状态修改 生成log
	 * @param fk_voucher 代金券id 原始状态 需改为状态
	 */
	public function logVoustatechange($pk_voucher, $from_state ,$to_state){
		$param = array(
			'fk_voucher' => $pk_voucher,
			'from_state' => $from_state,
			'to_state'   => $to_state
		);
		$this->setTable($this->tableNameVoucherStateLog)
			 ->addInsertColumns($param)
			 ->doInsert();
	}

	/**
	 * 根据主键查询单条code信息
	 * @param  $pk_voucher
	 *
	 * @return data
	 */
	public function get_by_pk_voucher($pk_voucher){

		$result = $this->setTable($this->tableNameVoucher)
						->addQueryConditions('pk_voucher', $pk_voucher)->doSelect();
		if($result){
			return $result[0];
		}
	}
	/**
	 * 根据code 获取单条代金券信息
	 * @param code
	 * @return mixed
	 */
	public function get_by_code($code){
		$result = $this->setTable($this->tableNameVoucher)
						->addQueryConditions('code', $code)->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 根据规则id 获取未分配的code
	 *
	 * @param $rule_id
	 *
	 * @return data
	 */
	public function getCode($rule_id, $corp_id)
	{
		$param  = [
			['fk_voucher_rule', $rule_id, '='],
			['fk_corp', $corp_id, '='],
			['state', OPERATION_VOUCHER_STATE_NOTALLOT, '=']
		];
		$result = $this->setTable($this->tableNameVoucher)
			->addQueryConditions($param)->doSelect();
		if ($result) {
			return $result[0];
		}
	}

	/**
	 * 根据用户id获取以获取的代金券列表
	 *
	 * @param  user_id,component_authorizer_app_id,page,size
	 *
	 * @return list
	 */
	public function list_by_uid($voucherData,$page,$size,$order,$sort )
	{
		$param = array(
			array('fk_component_authorizer_app' ,$voucherData['component_authorizer_app_id'],'='),
			array('fk_user',$voucherData['user_id'],'=')
		);
		if($voucherData['state'] != ''){
			$param[] = array('state', $voucherData['state'],'=');
		}
		$queryFields = array(
			'pk_voucher as voucher_id',
			'fk_corp as corp_id',
			'fk_voucher_rule as voucher_rule_id',
			'fk_component_authorizer_app as component_authorizer_app_id',
			'code',
			'scope_type',
			'state',
			'value',
			'value_min',
			'used_time',
			'stop_time',
			'fk_user as user_id',
			'fk_order as order_id'
		);
		$orderParam = array(
			$order 		 => $sort,
			'pk_voucher' => $sort
		);
		$return['data'] = $this->setTable($this->tableNameVoucher)
								->addQueryFields($queryFields)
								->addQueryConditions($param)
								->addOrderBy($orderParam)
								->doSelect($page, $size);
		$total = $this->setTable($this->tableNameVoucher)
									->addQueryFieldsCount('pk_voucher')
									->addQueryConditions($param)
									->doSelect();
		$return['sum'] = $total[0]['pk_voucher'];
		return $return;
	}

	/**
	 * 消费代金券
	 *
	 * @param code
	 * @param order_id 订单id
	 * @param fk_corp 企业id
	 * @param component_authorizer_app_id 公众号id
	 * @param voucherInfo 代金券详细信息
	 *
	 * @return bool
	 */
	public function consume($user_id, $order_id, $voucherInfo){
		try {
			$this->beginTransaction();
			$params = array(
				array('state', OPERATION_VOUCHER_STATE_USE, '='),
				array('used_time', date('Y-m-d H:i:s'), '='),
				array('fk_order', $order_id, '='),
			);

			$result = $this->setTable($this->tableNameVoucher)
				->addUpdateColumns($params)
				->addQueryConditions('pk_voucher', $voucherInfo['pk_voucher'])
				->addQueryConditions('state', OPERATION_VOUCHER_STATE_NOTUSE)
				->doUpdate();

			$this->logVoustatechange(
				$voucherInfo['pk_voucher'],
				OPERATION_VOUCHER_STATE_NOTUSE,
				OPERATION_VOUCHER_STATE_USE
			);

			$this->Rule_model->logRuleusernumber($voucherInfo['fk_voucher_rule']);

			$this->commit();
			return $result;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据ids 获取多条代金券
	 * @param ids ,分割
	 * @return mixed
	 */
	public function get_by_ids($ids){

		$queryFields = array(
			'pk_voucher as voucher_id',
			'fk_corp as corp_id',
			'fk_voucher_rule as voucher_rule_id',
			'fk_component_authorizer_app as component_authorizer_app_id',
			'code',
			'scope_type',
			'state',
			'value',
			'value_min',
			'used_time',
			'stop_time',
			'fk_user as user_id',
			'fk_order as order_id'
		);
		$return['data'] = $this->setTable($this->tableNameVoucher)
								->addQueryFields($queryFields)
								->addQueryConditionIn('pk_voucher',$ids)
								->doSelect();
		$total = $this->setTable($this->tableNameVoucher)
						->addQueryFieldsCount('pk_voucher')
						->addQueryConditionIn('pk_voucher',$ids)
						->doSelect();
		$return['sum'] = $total[0]['pk_voucher'];
		return $return;
	}

	/**
	 * 根据ids销毁代金券
	 * @param $rule_id,$corp_id
	 * @rturn rows
	 */
	public function cancel($rule_id,$corp_id){
		try {
			$this->beginTransaction();
			$sql = "update t_voucher set state = " . OPERATION_VOUCHER_STATE_CANCEL .
				" WHERE fk_corp = ". $corp_id  . " and fk_voucher_rule = " . $rule_id .
				"  and state = " . OPERATION_VOUCHER_STATE_NOTALLOT;
			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			$upParam = array(
				array('pk_voucher_rule', $rule_id, '='),
			);
			//修改代金券状态为作废完成
			$this->setTable($this->tableNameVoucherRule)
				->addUpdateColumns('state', OPERATION_VOUCHER_RULE_STATE_CANCEL_FINISH)
				->addQueryConditions($upParam)
				->doUpdate();
			$this->commit();
			return $result;

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据id过期代金券
	 * @param $rule_id,$corp_id
	 * @rturn rows
	 */
	public function overdue($rule_id,$corp_id){
		try {
			$this->beginTransaction();
			$newTime = date("Y-m-d H:i:s");
			$sql = "update t_voucher set state = " . OPERATION_VOUCHER_STATE_OVERDUE .
				" WHERE fk_corp = ". $corp_id  . " AND fk_voucher_rule = " . $rule_id .
				" AND state in (" . OPERATION_VOUCHER_STATE_NOTALLOT . "," . OPERATION_VOUCHER_STATE_NOTUSE . ") " .
				" AND stop_time < '".$newTime ."'";

			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());
			$upParam = array(
				array('pk_voucher_rule', $rule_id, '='),
			);
			//修改代金券状态为过期
			$this->setTable($this->tableNameVoucherRule)
				->addUpdateColumns('state', OPERATION_VOUCHER_RULE_STATE_OVERDUE)
				->addQueryConditions($upParam)
				->doUpdate();
			$this->commit();
			return $result;

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}






} // END class Msg_model
