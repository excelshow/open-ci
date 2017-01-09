<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class Text extends Handler {

    public function handler($msg){
        $this->load->model('User_model');
    }

}
