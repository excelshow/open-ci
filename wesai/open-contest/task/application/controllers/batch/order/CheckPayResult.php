<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午10:34
 */
require_once __DIR__ . '/../BatchBase.php';

class CheckPayResult extends BatchBase
{
	private $log_file_name = '';

	public function __construct()
	{
		parent::__construct();
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));;
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
					log_message_v2('error', 'invalid msg content');
					continue;
				}

				$outTradeNo = $msg['out_trade_no'];
				$oid        = intval(substr($outTradeNo, -10));
				$paidTime   = $msg['paid_time'];
				$channelId  = $msg['channel_id'];

				$this->process($oid, $paidTime, $channelId);
			} catch (Exception $e) {
				$this->catchException($e);

				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function process($oid, $paidTime, $channelId)
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
		if (!in_array($orderInfo['state'], [ORDER_STATE_INIT, ORDER_STATE_PAYING])) {
			$errMsg               = array();
			$errMsg['msg']        = 'order has already processed';
			$errMsg['order_info'] = $orderInfo;
			//log_message_v2('error', $errMsg);

			return true;
		}

		$payParams      = array(
			'service'      => ORDER_PAY_SERVICE,
			'out_trade_no' => $orderInfo['out_trade_no'],
		);
		$orderPayResult = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_by_service.json', $payParams, METHOD_GET);
		$orderPayResult = $this->obj2array($orderPayResult);
		if (empty($orderPayResult['result'])) {
			$errMsg                 = array();
			$errMsg['msg']          = __METHOD__ . ' get order pay result faliled';
			$errMsg['service']      = ORDER_PAY_SERVICE;
			$errMsg['out_trade_no'] = $orderInfo['out_trade_no'];
			log_message_v2('error', $errMsg);

			return false;
		}
		$orderPayResult = $orderPayResult['result'];

		$orderParams = array(
			'oid'            => $oid,
			'paid_time'      => $paidTime,
			'channel_id'     => $channelId,
			'transaction_id' => $orderPayResult['channel_info']['channel_transaction_id'],
			'amount_pay'     => $orderPayResult['amount_pay'],
		);
		$result      = $this->callInnerApiDiy($this->getHostName(), 'order/change_state_to_completed.json', $orderParams, METHOD_POST);
		$result      = $this->obj2array($result);
		if (empty($result['affected_rows'])) {
			log_message_v2(
				'error',
				array(
					'msg'    => 'order/change_state_to_completed failed',
					'params' => $orderParams,
					'file'   => __FILE__ . ' ' . __LINE__,
				)
			);
		}

		return true;
	}
}
