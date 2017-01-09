<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Home extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    public function setHostName(){
        return API_HOST_OPEN_PAY;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_PAY => array(
                'venut_order/get_list' => 'order/getList.json',
                'venut_order/get' => 'order/get.json'
            )
        );
    }

    /**
     * 订单详情
     */
    public function index() {
        $this->needLoginJson();
        $data = array(); 
        $pk_corp = $this->userInfo->pk_corp;
        $this->display($data);
    }

    /**
     * 订单搜索
     */
   
    public function ajax_search(){ 
        $this->needLoginJson();
        $pk_corp = $this->userInfo->pk_corp;
        $dateType   = $this->input->get('date_type', true);
        $datePeriod = $this->input->get('date_period', true);
        $start      = $this->input->get('date_start', true);
        $end        = $this->input->get('date_end', true);

        $minDate = 0;
        $maxDate = 0;
        switch ($dateType) {
            case 1: // 选择固定期限
                switch ($datePeriod) {
                    case 1: //当日
                        $minDate = date('Y-m-d');
                        $maxDate = date('Y-m-d');
                        break;
                    case 2: //近一周
                        $minDate = date('Y-m-d', strtotime('-1 weeks'));
                        $maxDate = date('Y-m-d');
                        break;
                    case 3: //近一月
                        $minDate = date('Y-m-d', strtotime('-1 month'));
                        $maxDate = date('Y-m-d');
                        break;
                }
                break;
            case 2:
                // 自定义日期区间
                $minDate = date('Y-m-d', strtotime($start));
                $maxDate = date('Y-m-d', strtotime($end));
                break;
        }

        
        $params['corp_id']  = $this->userInfo->pk_corp;
        $params['type']     = $this->input->get('type', true);
        $params['name']     = $this->input->get('name', true);
        $params['state']    = $this->input->get('state', true);
        $params['page']     = $this->input->get('page', true);
        $params['size']     = $this->input->get('size', true);
        $params['start']    = $minDate;
        $params['end']      = $maxDate;
        $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/search.json', $params, METHOD_GET,true,2,500);
         $this->display($data);
    }

   
    

    /**
     * 获取筛选条件
     */
    public function order_list() {
        $this->needLoginJson();
        $pk_corp = $this->userInfo->pk_corp;
        $data['allow_venue_types'] = $this->config->item('allow_venue_types');
        $data['venue_order_pay_satatelist'] = $this->config->item('venue_order_pay_satatelist');
        
        return $this->display($data);
    }
}