<?php

/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午5:10
 */
require_once __DIR__ . '/QywxModelBase.php';

class Weixin_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('curl');
	}

	public function getSuiteToken($suite_id, $suite_secret, $suite_ticket)
	{
		$url    = QYWX_GET_SUITE_TOKEN;
		$params = compact('suite_id', 'suite_secret', 'suite_ticket');
		$result = $this->curl->post(json_encode($params), $url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getPermanentCode($suite_access_token, $suite_id, $auth_code)
	{
		$url    = QYWX_GET_PERMANENT_CODE . $suite_access_token;
		$params = json_encode(compact('suite_id', 'auth_code'));
		$result = $this->curl->post($params, $url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getAuthInfo($suite_access_token, $permanent_code, $suite_id, $auth_corpid)
	{
		$url = QYWX_GET_AUTH_INFO . $suite_access_token;

		$params = json_encode(compact('suite_id', 'auth_corpid', 'permanent_code'));
		$result = $this->curl->post($params, $url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getCorpToken($suite_access_token, $suite_id, $auth_corpid, $permanent_code)
	{
		$url = QYWX_GET_CROP_TOKEN . $suite_access_token;

		$params = json_encode(compact('suite_id', 'auth_corpid', 'permanent_code'));
		$result = $this->curl->post($params, $url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getProviderAccessToken($corpid, $provider_secret)
	{
		$url    = QYWX_GET_PROVIDER_TOKEN;
		$params = json_encode(compact('corpid', 'provider_secret'));

		$result = $this->curl->post($params, $url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}

	public function getJsApiTicket($accessToken)
	{
		$url = QYWX_GET_JSAPI_TICKET . $accessToken;

		$result = $this->curl->get($url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url'));
	}
}
