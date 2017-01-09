<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class Update_member_card extends Handler {

    protected $topic_pre = 'event_';
    public function handler($msg){
    }

}

