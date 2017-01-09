<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Order_model extends MY_Model
{
	public function __construct() {
        parent::__construct();
    }

    public function checkPayMch($appid, $mch_id, $mch_secret, $out_trade_no) {
        $params = array();
        $params['appid'] = $appid;
        $params['mch_id'] = $mch_id;
        $params['mch_secret'] = $mch_secret;
        $params['out_trade_no'] = $out_trade_no;

        $requests = array();
        $requests[] = $this->request('api_host_open_pay', 'order/check_pay_mch.json', $params, 'GET');

        return $this->result($requests, true, 2 ,5000);
    }

}
