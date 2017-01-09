<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mcurl');
    }

    public function wxSubscribe($to_user_name, $from_user_name) {
        $params = compact('to_user_name', 'from_user_name');
        $requests = array();
        $requests[] = $this->request('api_host_open_user', 'user/wx_subscribe.json', $params, 'POST');

        return $this->result($requests);
    }

    public function wxUnsubscribe($to_user_name, $from_user_name) {
        $params = compact('to_user_name', 'from_user_name');
        $requests = array();
        $requests[] = $this->request('api_host_open_user', 'user/wx_unsubscribe.json', $params, 'POST');

        return $this->result($requests);
    }

}
