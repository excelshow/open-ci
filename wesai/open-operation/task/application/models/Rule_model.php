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
	protected $tableNameVoucherRule 	= 't_voucher_rule';
	protected $VoucherRuleStatePass 	= '3,5'; //3审核通过  5生成中
	protected $VoucherRuleStateCancel	= '7'; //3审核通过  5生成中
	protected $tableNameVoucher         = 't_voucher';
	protected $VoucherStateNotallot		= '0';
	protected $VoucherListPage			= '1';
	protected $VoucherListSize			= '50';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPERATION_DB_CONFIG;
	}

	/**
	 * 获取代金券规则列表
	 * @param $type
	 *
	 * @return data
	 */
	public function lists($type = 'pass'){
		if($type == 'cancel'){
			$param = $this->VoucherRuleStateCancel;
		}else{
			$param = $this->VoucherRuleStatePass;
		}


		$queryFields = array(
			'pk_voucher_rule as voucher_rule_id',
			'name',
			'fk_corp as corp_id',
			'scope_type',
			'state',
			'number',
			'number_created',
			'value',
			'value_min',
			'date_stop as stop_time'
		);

		return $this->setTable($this->tableNameVoucherRule)
								->addQueryFields($queryFields)
								->addQueryConditionIn('state',$param)
								->doSelect();
	}



	/**
	 * 根据规则id 获取多条未分配的代金券
	 * @param rule_id
	 * @return mixd
	 */
	public function listVoucher($rule_id){
		$result =  $this->setTable($this->tableNameVoucher)
					->addQueryFields('pk_voucher')
					->addQueryConditions('state',$this->VoucherStateNotallot)
					->addQueryConditions('fk_voucher_rule',$rule_id)
					->doSelect();
		if($result){
			$ids = '';
			foreach($result as $voucher){
				$ids .= $voucher['pk_voucher'] . ',';
			}
			return trim($ids, ',');
		}
	}




} // END class Msg_model
