<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class CreateTimes extends Base
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
				$res_rule_list = $this->redis_list_client->RightPop(MQ_TOPIC_BOOK_VENUE_RES_RULE);
				if (empty($res_rule_list)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				$res = json_decode($res_rule_list,true);

				$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule/create_times_redis.json', $res, METHOD_POST,true,2,1000);

				if (empty($orderObj)) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'createTime failed',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'info' => $res_rule_list,
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
