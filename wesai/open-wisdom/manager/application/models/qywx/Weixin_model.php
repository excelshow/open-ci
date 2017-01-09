<?php

/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 下午3:57
 */
require_once __DIR__ . '/QywxModelBase.php';
class Weixin_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
	}

	public function getPreAuthCode($suite_access_token, $suite_id)
	{
		$params = json_encode(compact('suite_id'));

		$result = $this->curl->post($params, QYWX_GET_PRE_AUTH_CODE . $suite_access_token);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getLoginUserInfo($provider_access_token, $auth_code)
	{
		$url = QYWX_GET_LOGIN_USER_INFO . 	$provider_access_token;
		
		$params = json_encode(compact('auth_code'));
		
		$result = $this->curl->post($params, $url);
		$this->curl->close();
		
		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}
}
