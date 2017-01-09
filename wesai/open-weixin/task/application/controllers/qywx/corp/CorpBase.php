<?php
/**
 * User: zhaodc
 * Date: 16/6/23
 * Time: 上午11:49
 */
require_once __DIR__ . '/../../Base.php';

class CorpBase extends Base
{
	const SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$mqConfig = $this->config->item('redismq');
		$this->load->library('Redis_List_Client', $mqConfig);
		$this->load->model('qywx/corp_model');
		$this->load->model('qywx/auth_model');
		$this->load->model('qywx/suite_model');
		$this->load->model('qywx/weixin_model');
	}
}
