<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base.php';
class Menu extends Base {

    public function create(){
        $this->load->library('WeixinMenu');
        $ret = $this->weixinmenu->create(WEIXIN_APPID);

        if (!empty($ret)) {
            var_dump(json_decode($ret));
        } else {
            echo 'Create Menu Failed!' . PHP_EOL;
        }
    }

}
