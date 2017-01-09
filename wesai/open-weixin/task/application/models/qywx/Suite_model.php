<?php

/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:43
 */
require_once __DIR__ . '/QywxModelBase.php';

class Suite_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function createSuite($suiteId, $timeStamp, $ticket)
	{
		$params = array(
			'suite_id'     => $suiteId,
			'timestamp'    => date('Y-m-d H:i:s', $timeStamp),
			'suite_ticket' => $ticket,
		);

		$requests[] = $this->request('api_host_open_weixin', 'qywx/suite/create.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateBySuiteId($params)
	{
		$requests[] = $this->request('api_host_open_weixin', 'qywx/suite/update.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getSuite($suiteId)
	{
		$params     = array('suite_id' => $suiteId);
		$requests[] = $this->request('api_host_open_weixin', 'qywx/suite/get.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getSuiteByPk($pk_suite)
	{
		$params     = array('pk_suite' => $pk_suite);
		$requests[] = $this->request('api_host_open_weixin', 'qywx/suite/get_by_pk.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listSuiteNeedRefreshToken($pageNumber = 1, $pageSize = 100)
	{
		$params = compact('pageNumber', 'pageSize');

		$requests[] = $this->request('api_host_open_weixin', 'qywx/suite/list_suite_need_refresh_token.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
