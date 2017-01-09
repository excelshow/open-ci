<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Order_Pay_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function getOrderPayResult($service, $out_trade_no)
	{
		$params     = compact('service', 'out_trade_no');
		$requests[] = $this->request('api_host_open_pay', 'order/get_by_service.json', $params, 'GET');

		return $this->result($requests);
	}
}
