<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/../Base.php';

class CreateVoucher extends Base
{
	const TIME_SLEEP_S = 10;
	const ONE_GENERATE = 100; //每次数据库添加量
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}
	public function index()
	{
		while (true) {
			try {
				$ruleList = $this->getRulelist_pass();
				if (empty($ruleList) || empty($ruleList['data'])) {
					$ruleList = $this->getRulelist_generating();
					if(empty($ruleList) || empty($ruleList['data'])){
						sleep(self::TIME_SLEEP_S);
						continue;
					}
				}
				foreach($ruleList['data'] as $rule){
					$this->createVoucher($rule);
				}

			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function createVoucher($rule){

		while (true) {
			$params = array(
				'corp_id' 			=> $rule['corp_id'],
				'voucher_rule_id' 	=> $rule['voucher_rule_id'],
				'number' 			=> self::ONE_GENERATE
			);
			$ruleObj = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/voucher/generate.json', $params, METHOD_POST);
			if (empty($ruleObj)) {
				log_message_v2(
					'error',
					array(
						'msg'     => 'rule create voucher failed',
						'file'    => __FILE__,
						'line'    => __LINE__,
						'ruleId' => $rule['voucher_rule_id'],
					)
				);
				break;
			}
			$rule_json = json_encode($ruleObj);
			$ruleInfo = json_decode($rule_json,true);
			$this->dealing();
			$this->dealEnd();
			if($ruleInfo['error'] < 0){
				$msg = " create 根据代金券规则id " . $rule['voucher_rule_id'] . "生成代金券出错，错误原因：" . $ruleInfo['info'];
				log_message('error',$msg);
				break;
			}else{
				$msg = " 根据代金券规则id " . $rule['voucher_rule_id'] . " ,生成代金券数量 ".$ruleInfo['result']['number'];
				log_message_to_file("createVoucher", $msg);
				if($ruleInfo['result']['state'] == OPERATION_VOUCHER_RULE_STATE_GENERATED){
					break;
				}
			}
		}
	}


	/**
	 * 获取状态为 ”通过“ 的代金券规则列表
	 * @return mixed
	 */
	private function getRulelist_pass(){
		$params = array('state'=> OPERATION_VOUCHER_RULE_STATE_PASSED );
		$ruleObj = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/list.json', $params, METHOD_GET);
		$rule_json = json_encode($ruleObj);
		$ruleList = json_decode($rule_json,true);
		return $ruleList;
	}

	/**
	 * 获取状态为 ”生成中“ 的代金券规则列表
	 * @return mixed
	 */
	private function getRulelist_generating(){
		$params = array('state'=> OPERATION_VOUCHER_RULE_STATE_GENERATING );
		$ruleObj = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/list.json', $params, METHOD_GET);
		$rule_json = json_encode($ruleObj);
		$ruleList = json_decode($rule_json,true);
		return $ruleList;
	}
}
