<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Formorder_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function addForm($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'form/add.json', $params, "POST");

		return $this->result($requests);
	}

	public function getFormById($formid)
	{
		$params     = compact('formid');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'form/get.json', $params);

		return $this->result($requests);
	}

	public function addFormItem($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'formitem/add.json', $params, "POST");

		return $this->result($requests);
	}

	public function deleteFormItem($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'formitem/delete.json', $params, "POST");

		return $this->result($requests);
	}

	public function updateFormItem($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'form/update_form_item.json', $params, "POST");

		return $this->result($requests);
	}

	public function listFormItem($formid)
	{
		$fk_enrol_form = $formid;
		$params        = compact('fk_enrol_form');
		$requests      = array();
		$requests[]    = $this->request('api_host_open_contest', 'form/list_form_item.json', $params);

		return $this->result($requests);
	}

	public function updateFormItemSeqs($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'formitem/set_seqs.json', $params, "POST");

		return $this->result($requests);
	}

	public function getFormItemById($formItemId)
	{
		$params     = array(
			'form_item_id' => $formItemId,
		);
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'form/get_form_item.json', $params, "GET");

		return $this->result($requests);
	}
}
