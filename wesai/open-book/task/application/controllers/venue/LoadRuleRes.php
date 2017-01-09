<?php
/**
 * User: wangbo
 */
require_once __DIR__ . '/../Base.php';

class LoadRuleRes extends Base
{
	const TIME_SLEEP_S = 10;
	public function __construct()
	{
		parent::__construct();
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
		$params = array();
		try {
			$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'mapping_res_rule/get_res_rule.json', $params, METHOD_GET);
			if (empty($orderObj->data)) {
				sleep(self::TIME_SLEEP_S);
			}
			log_message_to_file($this->log_file_name, $orderObj);
			foreach ($orderObj->data as $v) {
				$msg['venue_area_res_id'] = $v->res;
				$msg['day']	=  date("Y-m-d",strtotime('6day'));
				$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_BOOK_VENUE_RES_RULE, json_encode($msg));
				if (empty($ret)) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'set rule to redis failed',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'resRule' => $v,
						)
					);
				}
			}

		} catch (Exception $e) {
			$this->catchException($e);
			sleep(self::TIME_SLEEP_S);
		}
	}
}
