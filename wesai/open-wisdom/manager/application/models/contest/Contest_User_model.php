<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contest_User_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function getCorpUserByPk($pk_corp_user)
	{
		$params     = compact('pk_corp_user');
		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'corp_user/get_by_pk.json', $params);

		return $this->result($requests);
	}

	public function getUserByUids($uids)
	{
		$params     = compact('uids');
		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'user/get_by_ids.json', $params);

		return $this->result($requests);
	}

	public function getUserById($uid)
	{
		$params     = compact('uid');
		$requests   = array();
		$requests[] = $this->request('api_host_open_user', 'user/get_by_id.json', $params);

		return $this->result($requests);
	}
}
