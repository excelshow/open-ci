<?php

require_once __DIR__ . '/QywxModelBase.php';

/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 下午4:03
 */
class Corp_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getJsApiConfig($pkCorp, $url)
	{
		$params            = array();
		$params['pk_corp'] = $pkCorp;
		$params['url']     = $url;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/get_jsapi_config.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
