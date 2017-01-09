<?php
require_once dirname(__DIR__) . '/ModelBase.php';
/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:53
 */
class Corp_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function listCorp($page, $size)
	{
		$params = compact('page', 'size');

		$requests[] = $this->request('api_host_open_weixin', 'qywx/corp/list_corp.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
