<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Wesai_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mcurl');
    }

    public function push_event_msg($msg) {
        $data = json_encode($msg);
        $params = compact('data');
        $requests = array();
        $requests[] = $this->request(API_HOST_WESAI_B2C, 'wxnotice/info', $params, 'POST');

        return $this->result($requests);
    }

}
