<?php

/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:53
 */

require_once __DIR__ . '/QywxModelBase.php';

class Corp_model extends QywxModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getCorpByPk($pk_corp)
	{
		$params['pk_corp'] = $pk_corp;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/get_by_pk.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listCorpNeedRefreshTicket($page, $size)
	{
		$params['page'] = $page;
		$params['size'] = $size;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/list_corp_need_refresh_ticket.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateCorp($pkCorp, $params)
	{
		$params['pk_corp'] = $pkCorp;

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/update.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
