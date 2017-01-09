<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午10:34
 */
require_once __DIR__ . '/../BatchBase.php';

class Close extends BatchBase
{
	private $log_file_name = '';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('SmsService');
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));;
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

	private function process($oid)
	{
		// 获取订单详情
		$orderInfo = $this->callInnerApiDiy($this->getHostName(), 'order/get.json', compact('oid'), METHOD_GET);
		$orderInfo = $this->obj2array($orderInfo);
		if (empty($orderInfo['result'])) {
			log_message_v2('error', 'order ' . $oid . ' not exists');

			return false;
		}
		$orderInfo = $orderInfo['result'];

		// 判定订单状态
		if ($orderInfo['state'] != ORDER_STATE_COMPLETED) {
			$errMsg               = array();
			$errMsg['msg']        = 'order state invalid';
			$errMsg['order_info'] = $orderInfo;
			log_message_v2('error', $errMsg);

			return false;
		}

		// 获取活动资料
		$contestInfo = $this->callInnerApiDiy($this->getHostName(), 'contest/get.json', array('cid' => $orderInfo['fk_contest']), METHOD_GET);
		$contestInfo = $this->obj2array($contestInfo);
		if (empty($contestInfo['result'])) {
			$errMsg               = array();
			$errMsg['msg']        = 'contest not exists';
			$errMsg['contest_id'] = $orderInfo['fk_contest'];
			log_message_v2('error', $errMsg);

			return false;
		}
		$contestInfo = $contestInfo['result'];

		// 如果需要抽签, 则直接退出, 不错处理
		if ($contestInfo['lottery'] == MALATHION_LOTTERY_YES) {
			$errMsg               = array();
			$errMsg['msg']        = 'malathion need lottry, exit';
			$errMsg['order_id']   = $oid;
			$errMsg['contest_id'] = $orderInfo['fk_contest'];
			log_message_v2('error', $errMsg);

			return false;
		}

		// 不需要抽签, 关闭订单, 发送报名成功短信
		// 关闭订单
		$result = $this->callInnerApiDiy($this->getHostName(), 'order/change_state_to_closed.json', compact('oid'), METHOD_POST);
		$result = $this->obj2array($result);
		if (empty($result['affected_rows'])) {
			$errMsg             = array();
			$errMsg['msg']      = 'change_state_to_closed failed';
			$errMsg['order_id'] = $oid;
			log_message_v2('error', $errMsg);

			return false;
		}

		// 通知下单用户报名成功
		$orderUserMobile = '';
		$orderUserInfo   = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_id.json', array('uid' => $orderInfo['fk_user'], 'ext_mobile' => 1), METHOD_GET);
		$orderUserInfo   = $this->obj2array($orderUserInfo);
		if (empty($orderUserInfo['result'])) {
			$errMsg        = array();
			$errMsg['msg'] = 'user/get_by_id failed';
			$errMsg['uid'] = $orderInfo['fk_user'];
			log_message_v2('error', $errMsg);
		} else {
			$orderUserMobile = $orderUserInfo['result']['mobile'];
		}

		//短信原始内容
		$content = array(
			'{1}' => '或购买' . $contestInfo['name'],
		);

		if (!empty($contestInfo['service_tel'])) {
			$content['{2}'] = '，服务热线 ' . $contestInfo['service_tel'];
		}
		if (empty($content['{2}']) && !empty($contestInfo['service_mail'])) {
			$content['{2}'] = '，客服邮箱 ' . $contestInfo['service_mail'];
		}

		$cnt = 0;
		if (!empty($orderUserMobile)) {
			// 通知报名者
			$result = $this->smsservice->sendMsg(SMS_CLIENT_ID_CONTEST, SMS_BIZ_CONTEST_SUCCESS, $orderUserMobile, $content, $orderInfo['fk_component_authorizer_app']);

			if (empty($result) || $result->error != 0 || empty($result->lastid)) {
				$errMsg            = array();
				$errMsg['msg']     = 'send notify msg to orderUserMobile failed';
				$errMsg['mobile']  = $orderUserMobile;
				$errMsg['content'] = $content;
				$errMsg['result']  = $result;
				log_message_v2('error', $errMsg);
			} else {
				$cnt++;
			}
		}

		$errMsg             = array();
		$errMsg['msg']      = 'send sms notice done';
		$errMsg['order_id'] = $oid;
		$errMsg['count']    = $cnt;
		log_message_to_file($this->log_file_name, $errMsg);

		return true;
	}
}
