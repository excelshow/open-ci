<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Analysis_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function getTotal($fk_corp)
	{
		$params['fk_corp'] = $fk_corp;

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'analysis/get_total.json', $params, 'GET');

		return $this->result($requests);
	}

	public function getRecently($fk_corp, $days = null)
	{
		$params['fk_corp'] = $fk_corp;
		if (!empty($days)) {
			$params['days'] = $days;
		}

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'analysis/get_recently.json', $params, 'GET');

		return $this->result($requests);
	}

	public function listDaily($fk_corp, $pageNumber, $pageSize)
	{
		$params['fk_corp'] = $fk_corp;
		$params['page']    = $pageNumber;
		$params['size']    = $pageSize;

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'analysis/list_daily.json', $params, 'GET');

		return $this->result($requests);
	}

}
