<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class CloseOrder extends Base
{
	const TIME_SLEEP_S = 10;
	public function __construct()
	{
		parent::__construct();
		$redisConfig = $this->config->item('redis');
		$this->load->library('Redis_List_Client', $redisConfig);
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
				$order_id = $this->redis_list_client->RightPop(MQ_TOPIC_BOOK_ORDER_EXPIRED);
				if (empty($order_id)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				$params = compact('order_id');
				$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/get_state_by_id.json', $params, METHOD_GET);

				if (empty($orderObj)) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'order getById failed',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'orderId' => $orderId,
						)
					);
				}
				$order_json = json_encode($orderObj->result);
				$orderInfo = json_decode($order_json,true);
				if ($orderInfo[0]['state'] == ORDER_STATE_FAILED) {
					continue;
				}

				// 更新订单状态到 支付失败
				$result = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/change_state_failed.json', $params, METHOD_POST);
				if (empty($result)) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'changeStateToFailed failed',
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
