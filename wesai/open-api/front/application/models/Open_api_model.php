<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . '/libraries/DIY_Model.php';

class Open_api_model extends DIY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getBySystemApi($api_system, $api, $method)
	{
		$params = array();
		$params['api_system'] = $api_system;
		$params['api'] = $api;
		$params['method'] = $method;

		$requests   = array();
		$requests[] = $this->request('api_host_open_api', 'api/get_by_system_api.json', $params, 'GET');

		return $this->result($requests);
	}

}
