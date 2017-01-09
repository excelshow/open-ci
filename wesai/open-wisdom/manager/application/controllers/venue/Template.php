<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Venue_template extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    public function setHostName(){
        return API_HOST_OPEN_VENUE;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_VENUE => array(
                'venue_template/add' => 'venue_template/add.json'
            )
        );
    }

    /**
     * 添加场馆模板
     */
    public function  add(){

    }

    /**
     * 获取场馆模板列表
     */
    public function get_list(){

    }
}