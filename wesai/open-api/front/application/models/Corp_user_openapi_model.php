<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . '/libraries/DIY_Model.php';

class Corp_user_openapi_model extends DIY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function setOpenapiAccessToken($fk_corp, $fk_corp_user, $fk_corp_user_openapi, $access_token, $access_time, $expires_in)
	{
		$params = array();
		$params['fk_corp'] = $fk_corp;
		$params['fk_corp_user'] = $fk_corp_user;
		$params['fk_corp_user_openapi'] = $fk_corp_user_openapi;
		$params['access_token'] = $access_token;
		$params['access_time'] = $access_time;
		$params['expires_in'] = $expires_in;

		$requests   = array();
		$requests[] = $this->request('api_host_open_api', 'corp_user_openapi_token/set_access_token.json', $params, 'POST');

		return $this->result($requests);
	}

	public function getOpenapiAccessToken($access_token)
	{
		$params = array();
		$params['access_token'] = $access_token;

		$requests   = array();
		$requests[] = $this->request('api_host_open_api', 'corp_user_openapi_token/get_access_token.json', $params, 'GET');

		return $this->result($requests);
	}


}
