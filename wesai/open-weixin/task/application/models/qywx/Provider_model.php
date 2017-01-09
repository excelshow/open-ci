<?php

/**
 * User: zhaodc
 * Date: 16/6/26
 * Time: 下午2:35
 */
require_once __DIR__ . '/QywxModelBase.php';

class Provider_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getProvider($corpId = null)
	{
		$params = array();
		if (!empty($corpId)) {
			$params = compact('corpId');
		}

		$requests[] = $this->request('api_host_open_weixin_provider', 'qywx/provider/get.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateProvider($corp_id, $provider_access_token, $token_expires_at)
	{
		$params = compact('corp_id', 'provider_access_token', 'token_expires_at');

		$requests[] = $this->request('api_host_open_weixin_provider', 'qywx/provider/update.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
