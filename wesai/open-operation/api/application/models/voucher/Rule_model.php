<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 代金券数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Rule_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameVoucherRule 			= 't_voucher_rule';
	protected $tableNameVoucherRuleStateLog    	= 't_voucher_rule_state_log';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPERATION_DB_CONFIG;
	}


	/**
	 * 新增代金券规则
	 *
	 * @param $voucherData array
	 *
	 * @return mixed
	 *
	 */
	public function create($voucherData)
	{
		$params = $voucherData;
		$params['state'] = OPERATION_VOUCHER_RULE_STATE_NOSUBJECT;   //默认状态
		return $this->setTable($this->tableNameVoucherRule)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	/**
	 * 获取代金券规则列表
	 * @param $voucherData array
	 *
	 * @return data
	 */
	public function lists($voucherData,$page,$size,$order,$sort){

		$queryFields = array(
			'pk_voucher_rule as voucher_rule_id',
			'name',
			'fk_corp as corp_id',
			'scope_type',
			'state',
			'number',
			'number_created',
			'number_used',
			'value',
			'value_min',
			'date_stop as stop_time'
		);

		$param = array();
		if(!empty($voucherData['fk_corp']) && $voucherData['fk_corp'] != ''){
			$param[] = array('fk_corp',$voucherData['fk_corp'],'=');
		}
		if(!empty($voucherData['scope_type']) && $voucherData['scope_type'] != ''){
			$param[] = array('scope_type',$voucherData['scope_type'],'=');
		}
		if(!empty($voucherData['state'])  && $voucherData['state'] != ''){
			$param[] = array('state',$voucherData['state'],'=');
		}
		if(count($param)>0){
			$data = $this->setTable($this->tableNameVoucherRule)
				->addQueryFields($queryFields)
				->addQueryConditions($param)
				->addOrderBy($order, $sort)
				->doSelect($page, $size);
			$total = $this->setTable($this->tableNameVoucherRule)
				->addQueryFieldsCount('pk_voucher_rule')
				->addQueryConditions($param)
				->doSelect();
		}else{
			$data = $this->setTable($this->tableNameVoucherRule)
				->addQueryFields($queryFields)
				->addOrderBy($order, $sort)
				->doSelect($page, $size);
			$total = $this->setTable($this->tableNameVoucherRule)
				->addQueryFieldsCount('pk_voucher_rule')
				->doSelect();
		}

		$return['sum'] = $total[0]['pk_voucher_rule'];
		$return['data']= $data;
		return $return;
	}

	/**
	 * 根据代金券规则id 获取代金券信息
	 */
	public function details($rule_id){
		$queryFields = array(
			'pk_voucher_rule as voucher_rule_id',
			'name',
			'fk_corp as corp_id',
			'scope_type',
			'state',
			'number',
			'number_created',
			'number_used',
			'value',
			'value_min',
			'date_stop as stop_time'
		);
		$param = array(
			array('pk_voucher_rule', $rule_id, '=')
		);
		$result = $this->setTable($this->tableNameVoucherRule)
						->addQueryFields($queryFields)
						->addQueryConditions($param)
						->doSelect();
		if($result){
			return $result[0];
		}
	}


	/**
	 * 根据企业id 获取该企业创建的代金券规则（已完成、已过期）
	 */
	public function ruleList($corp_id){
		$param = array(
			array('fk_corp', $corp_id, '=')
			//array('state', OPERATION_VOUCHER_RULE_STATE_GENERATED, '=')
		);
		$inParam = OPERATION_VOUCHER_RULE_STATE_GENERATED . " , " . OPERATION_VOUCHER_RULE_STATE_OVERDUE;
		$queryFields = array(
			'pk_voucher_rule as voucher_rule_id',
			'name',
			'scope_type',
			'value',
	  		'value_min',
	  		'date_stop as stop_time',
			'number',
			'number_used',
			'number_allot'
		);
		$ruleData = $this->setTable($this->tableNameVoucherRule)
						->addQueryFields($queryFields)
						->addQueryConditions($param)
						->addQueryConditionIn('state',$inParam)
						->addOrderBy('ctime', 'desc')
						->doSelect();
		if($ruleData){
			$return = array();
			foreach($ruleData as $rule){
				$return[$rule['voucher_rule_id']] = $rule;
			}
			return $return;
		}

	}

	/**
	 * 根据代金券规则id 修改代金券信息
	 */
	public function modify($data){
		$condParam = array(
			array('pk_voucher_rule', $data['rule_id'], '=')
		);
		$updateParam = array(
			array('name',$data['name'],'='),
			array('scope_type',$data['scope_type'],'='),
			array('number',$data['number'],'='),
			array('value',$data['value'],'='),
			array('value_min',$data['value_min'],'='),
			array('date_stop',$data['date_stop'],'='),
		);
		$result = $this->setTable($this->tableNameVoucherRule)
			->addUpdateColumns($updateParam)
			->addQueryConditions($condParam)
			->doUpdate();
		return $result;
	}

	/**
	 * 根据代金券规则id 以及企业id修改状态
	 *
	 * @param  $voucherData array $type修改状态类型
	 *
	 * @return bool
	 */
	public function saveState($voucherData, $toState, $fromState)
	{
		try {
			$this->beginTransaction();
			$log = $this->logRulechange($voucherData['fk_voucher_rule'],$toState);
			if($log < 1){
				return false;
			}
			$condParam = array(
				array('fk_corp', $voucherData['fk_corp'], '='),
				array('pk_voucher_rule', $voucherData['fk_voucher_rule'], '='),
				array('state', $fromState, '=')
			);
			$result = $this->setTable($this->tableNameVoucherRule)
						->addUpdateColumns('state', $toState)
						->addQueryConditions($condParam)
						->doUpdate();
			$this->commit();
			return $result;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据规则主键更新主键状态以及更新条数
	 * @param rule_id
	 * @param 已生成数量
	 * @return lastid
	 */
	public function updateNumber($rule_id, $number, $state){
		$param = array(
			array('state', $state, '='),
			array('number_created', $number, '=')
		);
		return $this->setTable($this->tableNameVoucherRule)
			->addUpdateColumns($param)
			->addQueryConditions('pk_voucher_rule', $rule_id)
			->doUpdate();
	}

	/**
	 * 根据主键查询规则信息
	 * @param  $fk_voucher_rule
	 *
	 * @return data
	 */
	public function get_by_fk_voucher_rule($fk_voucher_rule){
		$result = $this->setTable($this->tableNameVoucherRule)
						->addQueryConditions('pk_voucher_rule', $fk_voucher_rule)
						->doSelect();
		if($result){
			return $result[0];
		}

	}

	/**
	 * 代金券规则状态变更记录表
	 *
	 * @param  $fk_voucher_rule,$toState
	 *
	 * @return mined
	 */
	public function logRulechange($fk_voucher_rule, $toState )
	{
		//查询原有数据
		$info = $this->get_by_fk_voucher_rule($fk_voucher_rule);
		if (empty($info)) {
			return false;
		}
		$params = array(
			'fk_voucher_rule'=> $fk_voucher_rule,
			'from_state'	 => $info['state'],
			'to_state'		 => $toState
		);
		return $this->setTable($this->tableNameVoucherRuleStateLog)
			->addInsertColumns($params)
			->doInsert();
	}

	/**
	 * 根据代金券主键 更新代金券 已使用 数量
	 * @param rule_id
	 */
	public function logRuleusernumber($rule_id){
		$condParam = array(
			array('pk_voucher_rule',$rule_id,'=')
		);
		$param = array(
			array('number_used',1,'+')
		);
		$this->setTable($this->tableNameVoucherRule)
			->addUpdateColumns($param)
			->addQueryConditions($condParam)
			->doUpdate();
	}

	/**
	 * 根据代金券id 更新代金券 已分配 数量
	 * @param rule_id
	 */
	public function logRulealltonumber($rule_id){
		$condParam = array(
			array('pk_voucher_rule',$rule_id,'=')
		);
		$param = array(
			array('number_allot',1,'+')
		);
		$this->setTable($this->tableNameVoucherRule)
			->addUpdateColumns($param)
			->addQueryConditions($condParam)
			->doUpdate();
	}



} // END class Msg_model
