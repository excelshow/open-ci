<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Corp_user_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function createOpenapi($fk_corp, $user_id, $user_type, $user_name, $user_avatar='')
	{
		$params                = array();
		$params['fk_corp']     = $fk_corp;
		$params['user_id']     = $user_id;
		$params['user_type']   = $user_type;
		$params['user_name']   = $user_name;
		$params['user_avatar'] = $user_avatar;

		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'corp_user/create_openapi.json', $params, 'POST');

		return $this->result($requests);
	}

	public function create($fk_corp, $user_id, $user_type, $user_name, $user_avatar='')
	{
		$params                = array();
		$params['fk_corp']     = $fk_corp;
		$params['user_id']     = $user_id;
		$params['user_type']   = $user_type;
		$params['user_name']   = $user_name;
		$params['user_avatar'] = $user_avatar;

		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'corp_user/create.json', $params, 'POST');

		return $this->result($requests);
	}

	public function get($fk_corp, $user_id)
	{
		$params            = array();
		$params['fk_corp'] = $fk_corp;
		$params['user_id'] = $user_id;

		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'corp_user/get_by_unq_key.json', $params, 'GET');

		return $this->result($requests);
	}

}
