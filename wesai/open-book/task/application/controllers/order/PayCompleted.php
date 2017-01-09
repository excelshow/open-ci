<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class PayCompleted extends Base
{
	const TIME_SLEEP_S = 10;
	private $log_file_name = '';
	public function __construct()
	{
		parent::__construct();
		$this->load->library('SmsService');
		$redisConfig = $this->config->item('redis');
		$this->load->library('Redis_List_Client', $redisConfig);
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));
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
				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ORDER_PAY_COMPLETED);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				log_message_to_file($this->log_file_name, $msg);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					log_message_v2('error', 'invalid msg content');
					continue;
				}

				$oid = $msg['oid'];

				$this->process($oid);
			} catch (Exception $e) {
				$this->catchException($e);

				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function process($order_id)
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
		if ($orderInfo[0]['state'] != ORDER_STATE_COMPLETED) {
			$errMsg               = array();
			$errMsg['msg']        = 'order state invalid';
			$errMsg['order_info'] = $orderInfo[0];
			log_message_v2('error', $errMsg);

			return false;
		}

		// 获取场次详情
		$user_id = $orderInfo[0]['fk_user'];
		$my_params = compact('order_id','user_id');
		$orderInfoObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/get_my_info.json', $my_params, METHOD_GET);
		if (empty($orderInfoObj)) {
			$errMsg               = array();
			$errMsg['msg']        = 'contest not exists';
			$errMsg['contest_id'] = $orderInfo[0]['fk_contest'];
			log_message_v2('error', $errMsg);

			return false;
		}


		$phone = $orderInfo[0]['mobile'];

		foreach($orderInfoObj->result->times as $v){
			$times[] = $v->day.' '.substr($v->time_start,0,5).'-'.substr($v->time_end,0,5).' '.$v->name.'场';
			$code[] = $v->code;
		}
		$times = implode(',', $times);
		$code = implode(',', $code);
		//短信原始内容
		$params = array(
			'{1}' => $orderInfoObj->result->venue_name.' '.$times."。核销码为：".$code,
			'{2}' => '客服电话：'.$orderInfoObj->result->venue_phone,
		);

		$params = json_encode($params);

		$client_id = SMS_CLIENT_ID_BOOK;
		$biz = SMS_BIZ_BOOK_SUCCESS;
		$apppk = $orderInfo[0]['fk_component_authorizer_app'];

		$sms_params = compact('client_id', 'biz', 'phone', 'params', 'apppk');

		if (!empty($phone)) {

			$result = $this->callInnerApiDiy(API_HOST_OPEN_SMS, 'sms/msg_send.json', $sms_params, METHOD_POST);

			if (empty($result) || $result->error != 0 || empty($result->lastid)) {
				$errMsg            = array();
				$errMsg['msg']     = 'send notify msg to phone failed';
				$errMsg['mobile']  = $phone;
				$errMsg['content'] = $params;
				$errMsg['result']  = $result;
				log_message_v2('error', $errMsg);
			}

		}

		$errMsg             = array();
		$errMsg['msg']      = 'send sms notice done';
		$errMsg['order_id'] = $order_id;
		log_message_to_file($this->log_file_name, $errMsg);

		return true;
	}
}
