<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/Base.php';

class GetToken extends Base
{
	const TIME_SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}

	public function index()
	{
        $result = $this->callInnerApiDiy(API_HOST_OPEN_LITTLE, '/token/update_token.json', array(), METHOD_POST, true, 10, 10000);
        if (empty($result) || $result->error != 0 || empty($result->affected_rows)) {
            log_message("error", '更新token信息错误 result='.json_encode($result));
            sleep(10);
        }
        $msg = '更新token信息成功';
        log_message_to_file("token", $msg);

	}

}
