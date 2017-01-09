<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午10:34
 */
require_once __DIR__ . '/../BatchBase.php';

class LoadExpired extends BatchBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$page = 1;
		$size   = 100;
		while (true) {
			try {
				$orderList = $this->callInnerApiDiy($this->getHostName(), 'order/list_expired.json', compact('page', 'size'), METHOD_GET);
				$orderList = $this->obj2array($orderList);
				if (empty($orderList['data'])) {
					$page = 1;
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				foreach ($orderList['data'] as $v) {
					$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_CONTEST_ORDER_EXPIRED, $v['pk_order']);
					if (empty($ret)) {
						log_message_v2(
							'error',
							array(
								'msg'     => 'set order to redis failed',
								'file'    => __FILE__,
								'line'    => __LINE__,
								'orderId' => $v['pk_order'],
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
