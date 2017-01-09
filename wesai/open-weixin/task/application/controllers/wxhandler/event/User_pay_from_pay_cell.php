<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class User_pay_from_pay_cell extends Handler {

    protected $topic_pre = 'event_';
    public function handler($msg){
    }

}

