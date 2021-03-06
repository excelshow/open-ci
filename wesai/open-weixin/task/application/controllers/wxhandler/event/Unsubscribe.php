<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class Unsubscribe extends Handler {

    protected $topic_pre = 'event_';
    public function handler($msg){
        $this->load->model('User_model');
        $result = $this->User_model->wxUnsubscribe($msg->ToUserName, $msg->FromUserName);
        if(empty($result) || !isset($result->affected_rows)){
            log_message('error', 'User_model->wxUnsubscribe error');
        }
    }

}
