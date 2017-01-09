<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Order extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    public function setHostName(){
        return API_HOST_OPEN_COMMON;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_COMMON => array(
                'order/index' => REQUEST_TPL, // 只有tpl的也需要配置一下
            )
        );
    }


    
   
    

}
