<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Venue_area extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setHostName(){
        return API_HOST_OPEN_PAY;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_PAY => array(
                'test/get' => 'order/get.json'
            )
        );
    }

    /**
     * 添加场馆区域
     */
    public function  add(){

    }

    /**
     * 获取场馆区域列表
     */
    public function get_list(){

    }
}