<?php
require_once __DIR__ . '/WexinModelBase.php';

/**
 * User: zhaodc
 * Date: 8/8/16
 * Time: 17:35
 */
class Oauth_model extends WeixinModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getLoginUrl($appId, $redirectUri)
	{
		$params = array(
			'appid'         => $appId,
			'redirect_uri'  => urlencode($redirectUri),
			'response_type' => 'code',
			'scope'         => 'snsapi_base',
			'state'         => 'state',
		);

		return WEIXIN_OAUTH2_LOGIN_URL . '?' . http_build_query($params) . '#wechat_redirect';

	}

	public function getUserInfo($access_token, $code)
	{
		$params = compact('access_token', 'code');
		$url    = WEIXIN_OAUTH2_GET_USER_INFO . '?' . http_build_query($params);
		$result = $this->curl->get($url);
		$this->curl->close();

		return $this->checkWeixinApiResult($result, compact('url', 'params'));
	}
}
