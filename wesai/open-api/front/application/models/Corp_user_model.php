<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . '/libraries/DIY_Model.php';

class Corp_user_model extends DIY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getOpenapiByAppid($appid)
	{
		$params            = array();
		$params['appid'] = $appid;

		$requests   = array();
		$requests[] = $this->request(API_HOST_OPEN_USER, 'corp_user/get_openapi_by_appid.json', $params, 'GET');

		return $this->result($requests);
	}

}
