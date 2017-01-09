<?php
/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:13
 */
require_once __DIR__ . '/../../Base.php';

class SuiteBase extends Base
{
	const SLEEP_S              = 10;
	const TIME_DELAY_LONG_MIN  = 31;
	const TIME_DELAY_SHORT_MIN = 1;

	public function __construct()
	{
		parent::__construct();
		$mqConfig = $this->config->item('redismq');
		$this->load->library('Redis_List_Client', $mqConfig);
		$this->load->model('qywx/suite_model');
		$this->load->model('qywx/auth_model');
		$this->load->model('qywx/weixin_model');
	}
}

