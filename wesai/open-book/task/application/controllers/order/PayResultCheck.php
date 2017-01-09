<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class PayResultCheck extends Base
{
	const TIME_SLEEP_S = 10;
	private $log_file_name = '';
	public function __construct(){
		parent::__construct();
		$redisConfig = $this->config->item('redis');
		$this->load->library('Redis_List_Client', $redisConfig);
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));;
	}

	public function setHostName(){
        return API_HOST_OPEN_BOOK;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_BOOK => array(
            )
        );
    }
	public function index()
	{
		while (true) {
			try {
				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ORDER_PAY_RESULT);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				log_message_to_file($this->log_file_name, $msg);
				$msg = json_decode($msg, true);
				if (empty($msg)) {
					log_message_v2('error', 'invalid msg book');
					continue;
				}

				$outTradeNo = $msg['out_trade_no'];
				$oid        = intval(substr($outTradeNo, -10));
				$paid_time   = $msg['paid_time'];
				$channel_id  = $msg['channel_id'];

				$this->process($oid, $paid_time, $channel_id);
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function process($order_id, $paid_time, $channel_id)
	{
		// 获取订单详情
		$params = compact('order_id');
		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/get_state_by_id.json', $params, METHOD_GET);
		if (empty($orderObj)) {
			log_message_v2('error', 'order ' . $order_id . ' not exists');

			return false;
		}
		$order_json = json_encode($orderObj->result);
		$orderInfo = json_decode($order_json,true);

		// 判定订单状态
		if (!in_array($orderInfo[0]['state'], [ORDER_STATE_INIT, ORDER_STATE_PAYING])) {
			$errMsg               = array();
			$errMsg['msg']        = 'order has already processed';
			$errMsg['order_info'] = $orderInfo[0];
			log_message_v2('error', $errMsg);

			return true;
		}
		$service = ORDER_PAY_SERVICE;
		$out_trade_no = $orderInfo[0]['out_trade_no'];
		$pay_params = compact('service', 'out_trade_no');
		$payObj = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_by_service.json', $pay_params, METHOD_GET);
		if (empty($payObj)) {
			$errMsg                 = array();
			$errMsg['msg']          = __METHOD__ . ' get order pay result faliled';
			$errMsg['service']      = ORDER_PAY_SERVICE;
			$errMsg['out_trade_no'] = $out_trade_no;
			log_message_v2('error', $errMsg);

			return false;
		}
		$transaction_id = $payObj->result->channel_info->channel_transaction_id;
		$amount_pay = $payObj->result->amount_pay;
		$params = compact('order_id','paid_time','channel_id','transaction_id','amount_pay');


		$this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/change_state_completed.json', $params, METHOD_POST);
		return true;
	}
}
