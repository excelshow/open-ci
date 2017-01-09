<?php

/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 下午4:03
 */
class Auth_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function createOpenapiCorp($corpId, $corpName='')
	{
		$params = array();
        $params['corp_id'] = $corpId;
        $params['corp_name'] = $corpName;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/create_openapi_corp.json', $params, 'POST');

		return $this->result($requests);
	}

	public function getCorpById($corpId = '')
	{
		$params            = array();
		$params['corp_id'] = $corpId;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/get_by_id.json', $params, 'GET');

		return $this->result($requests);
	}

	public function listAuthByCorp($pkCorp, $pageNumber = 1, $pageSize = 100)
	{
		$params            = array();
		$params['corp_id'] = $pkCorp;
		$params['page']    = $pageNumber;
		$params['size']    = $pageSize;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/list_auth_by_corp.json', $params, 'GET');

		return $this->result($requests);
	}

	public function getAuthByUnqKey($pkSuite, $pkCorp)
	{
		$params             = array();
		$params['pk_suite'] = $pkSuite;
		$params['pk_corp']  = $pkCorp;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/auth/get_auth_by_unq.json', $params, 'GET');

		return $this->result($requests);
	}
}
