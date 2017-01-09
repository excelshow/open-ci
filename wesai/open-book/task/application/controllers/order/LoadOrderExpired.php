<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class LoadOrderExpired extends Base
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
		$page = 1;
		$size  = 100;
		while (true) {
			$params = compact($page,$size);
			try {
				$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/list_order_expired.json', $params, METHOD_GET);
				if (empty($orderObj->data)) {
					$page = 1;
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				foreach ($orderObj->data as $v) {
					$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_BOOK_ORDER_EXPIRED, $v->pk_order);
					if (empty($ret)) {
						log_message_v2(
							'error',
							array(
								'msg'     => 'set order to redis failed',
								'file'    => __FILE__,
								'line'    => __LINE__,
								'orderId' => $v->pk_order,
							)
						);
					}
				}

				$page++;
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}
}
