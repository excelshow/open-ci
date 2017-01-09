<?php
/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:13
 */
require_once __DIR__ . '/../../Base.php';

class ProviderBase extends Base
{
	const SLEEP_S    = 10;
	const TIME_LIMIT = 30;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('qywx/provider_model');
		$this->load->model('qywx/weixin_model');
	}
}

