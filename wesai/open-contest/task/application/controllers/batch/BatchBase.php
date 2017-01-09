<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午10:40
 */
require_once __DIR__ . '/../Base.php';

class BatchBase extends Base
{
	const TIME_SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$redisConfig = $this->config->item('redis');
		$this->load->library('Redis_List_Client', $redisConfig);
		$this->load->model('res/Res_model');
	}

	public function getHostName()
	{
		return API_HOST_OPEN_CONTEST;
	}

	public function getAllowedApiList()
	{
		return array();
	}
}
