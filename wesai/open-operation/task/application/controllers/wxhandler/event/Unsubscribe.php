<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class Unsubscribe extends Handler {

    protected $topic_pre = 'event_';
    public function handler($msg){
        // 消费掉
        //var_dump($msg);
    }

}
