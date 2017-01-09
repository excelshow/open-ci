<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';

class Verifypc extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    public function setHostName(){
        return API_HOST_OPEN_BOOK;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_BOOK => array(
                'order/verify' => 'order/verify.json'
            )
        );
    }

    /**
     * 订单详情
     */
    public function ajax_detail_order() {
        $this->needLoginJson();
        $pk_corp = $this->userInfo->pk_corp;
        $this->display($data);
    }

    /**
     * 订单搜索
     */
    public function index(){
        $data = array(); 
        $this->needLoginJson();
        $corp_id = $this->userInfo->pk_corp;
        $page = $this->input->get('per_page', true);
        $size = $this->input->get('size', true);
        $page > 0 or $page = 1;
        $size > 0 or $size = 20;
        $params = compact('corp_id','page','size');
        $get_times = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'verify/get_list.json', $params, METHOD_GET,true,2,1500);
        if ($get_times->error) {
            return show_error('获取核销记录失败');
        }
        $allow_venue_types = $this->config->item('allow_venue_types');
        $types = array_reduce($allow_venue_types, create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
        foreach ($get_times->data as $key => $value) {
            $get_times->data[$key]->venue_area_type= $types[$value->venue_area_type];
        }

        $allow_venue_types = $this->config->item('allow_venue_types');
        $total = $get_times->total;
        $size = $get_times->size;
        $this->load->helper('pagination');
        $this->load->library('pagination');
        $pconfig = load_pagination_config($total, $size);
        $this->pagination->initialize($pconfig);
        $page_ctrl = $this->pagination->create_links();
        $get_times->page_ctrl = $page_ctrl;

        $this->display($get_times);
    }
    public function order_verify(){ 
        $this->needLoginJson();
        $pk_corp = $this->userInfo->pk_corp;
        $params['code'] = $this->input->post('code', true);
        $params['corp_id'] = $pk_corp;
        $params['user_id'] = $this->userInfo->user_id;
        $get_times = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/verify.json', $params, METHOD_POST,true,2,1000);
        $this->display($get_times);
    }
    
}
