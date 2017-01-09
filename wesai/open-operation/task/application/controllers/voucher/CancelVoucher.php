<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/../Base.php';

class CancelVoucher extends Base
{
	const TIME_SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}
	public function index()
	{
		while (true) {
			try {

				$ruleList = $this->getRulelist();
				if (empty($ruleList) || empty($ruleList['data'])) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				foreach($ruleList['data'] as $rule){
					$this->cancelVoucher($rule);
				}

			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function cancelVoucher($rule){
		$params = array(
			'rule_id'=>$rule['voucher_rule_id'],
			'corp_id'=>$rule['corp_id']
		);
		$ruleObj = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/voucher/cancel.json', $params, METHOD_POST);

		if (empty($ruleObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'rule cancel voucher failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'ruleId' => $rule['voucher_rule_id'],
				)
			);
			return false;
		}
		$rule_json = json_encode($ruleObj);
		$ruleInfo = json_decode($rule_json,true);
		$this->dealing();
		$this->dealEnd();
		if($ruleInfo['error'] < 0){
			$msg = " cancel 根据代金券规则id " . $rule['voucher_rule_id'] . "销毁代金券出错，错误原因：" . $ruleInfo['info'];
			log_message('error',$msg);
		}else{
			$msg = " 根据代金券规则id " . $rule['voucher_rule_id'] . " ,销毁代金券数量 ".$ruleInfo['affected_rows'];
			log_message_to_file("cancelVoucher", $msg);
		}


	}

	/**
	 * 获取状态为 ”作废“ 的代金券规则列表
	 * @return mixed
	 */
	private function getRulelist(){
		$params = array('state'=> OPERATION_VOUCHER_RULE_STATE_CANCEL);
		$ruleObj = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/list.json', $params, METHOD_GET);
		$rule_json = json_encode($ruleObj);
		$ruleList = json_decode($rule_json,true);
		return $ruleList;
	}
}
