<?php

/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:53
 */

require_once __DIR__ . '/QywxModelBase.php';

class Auth_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function createAuthorization($suiteId, $jParams)
	{
		$params = array(
			'suite_id'  => $suiteId,
			'auth_info' => $jParams,
		);

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/create.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function cancelAuthorization($suiteId, $corpId)
	{
		$params = array(
			'suite_id' => $suiteId,
			'corp_id'  => $corpId,
		);

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/cancel.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getAuthorization($suiteId, $corpId)
	{
		$params = array(
			'suite_id' => $suiteId,
			'corp_id'  => $corpId,
		);

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/get.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listAuthNeedRefreshToken($state, $pageNumber = 1, $pageSize = 100)
	{
		$params = compact('state', 'pageNumber', 'pageSize');

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/list_auth_need_refresh_token.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateAuthorization($authId, $params)
	{
		$params['auth_id'] = $authId;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/update.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getByPk($authId)
	{
		$params['auth_id'] = $authId;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/get_by_pk.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listAuthByCorp($pkCorp, $pageNumber = 1, $pageSize = 10)
	{
		$params['corp_id'] = $pkCorp;
		$params['page']    = $pageNumber;
		$params['size']    = $pageSize;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/list_auth_by_corp.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
