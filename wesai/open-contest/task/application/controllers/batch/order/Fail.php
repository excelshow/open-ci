<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午10:34
 */
require_once __DIR__ . '/../BatchBase.php';

class Fail extends BatchBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {
				$orderId = $this->redis_list_client->RightPop(MQ_TOPIC_CONTEST_ORDER_EXPIRED);
				if (empty($orderId)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				$orderInfo = $this->callInnerApiDiy($this->getHostName(), 'order/get.json', array('oid' => $orderId), METHOD_GET);
				$orderInfo = $this->obj2array($orderInfo);
				if (empty($orderInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'order getById failed',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'orderId' => $orderId,
						)
					);
					continue;
				}
				$orderInfo = $orderInfo['result'];

				if (!in_array($orderInfo['state'], [ORDER_STATE_INIT, ORDER_STATE_PAYING])) {
					continue;
				}

				// 查询支付系统订单状态
				$payParams      = array(
					'service'      => ORDER_PAY_SERVICE,
					'out_trade_no' => $orderInfo['out_trade_no'],
				);
				$orderPayResult = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_by_service.json', $payParams, METHOD_GET);
				$orderPayResult = $this->obj2array($orderPayResult);
				if (empty($orderPayResult['result'])) {
					// 更新订单状态到 支付失败
					$result = $this->callInnerApiDiy($this->getHostName(), 'order/change_state_to_failed.json', array('oid'=>$orderId), METHOD_POST);
					$result = $this->obj2array($result);
					if (empty($result['affected_rows'])) {
						log_message_v2(
							'error',
							array(
								'msg'     => 'change_state_to_failed failed',
								'file'    => __FILE__,
								'line'    => __LINE__,
								'orderId' => $orderId,
							)
						);
					}
					continue;
				}
				$orderPayResult = $orderPayResult['result'];

				if ($orderPayResult['state'] == ORDER_PAY_SYS_ORDER_STATE_SUCCESS) {
					$orderParams = array(
						'oid'            => $orderInfo['pk_order'],
						'paid_time'      => $orderPayResult['paid_time'],
						'channel_id'     => $orderPayResult['channel_id'],
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

					continue;
				}


				// 更新订单状态到 支付失败
				$result = $this->callInnerApiDiy($this->getHostName(), 'order/change_state_to_failed.json', array('oid'=>$orderId), METHOD_POST);
				$result = $this->obj2array($result);
				if (empty($result['affected_rows'])) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'change_state_to_failed failed',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'orderId' => $orderId,
						)
					);
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}
}
