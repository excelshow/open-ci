<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class User_enter_session_from_card extends Handler {

    protected $topic_pre = 'event_';
    public function handler($msg){
    }

}

