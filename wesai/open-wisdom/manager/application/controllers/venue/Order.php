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
        return API_HOST_OPEN_BOOK;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_BOOK => array(
                'venut_order/get_list' => 'order/getList.json',
                'venut_order/get' => 'order/get.json'
            )
        );
    }
    public function detail_order() {
        $this->needLoginJson();
        $data['allow_venue_types'] = $this->config->item('allow_venue_types');
        global  $PAY_CHANNEL_LIST;
        $data['order_pay_method'] = $PAY_CHANNEL_LIST;
        $this->display($data);
    }
    /**
     * 订单详情
     */
    public function ajax_detail_order() {
        $this->needLoginJson();
        $pk_corp = $this->userInfo->pk_corp;
        $params['corp_id']   = $this->userInfo->pk_corp;
        $params['order_id']    = $this->input->get('corp_id', true);
        $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/get_info.json', $params, METHOD_GET,true,2,1000);
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

        
        $params['owner_corp_id']  = $this->userInfo->pk_corp;
        $params['types']     = $this->input->get('types', true);
        $params['venue_name']  = $this->input->get('name', true);
        $params['is_use']    = $this->input->get('is_use');
        $params['state']    = $this->input->get('state', true);
        $params['page']     = $this->input->get('page', true);
        $params['size']     = $this->input->get('size', true);
        $params['start']    = $minDate;
        $params['end']      = $maxDate;

        $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/search.json', $params, METHOD_GET,true,2,2000);
        $this->display($data);
    }

   
    

    /**
     * 获取筛选条件
     */
    public function order_list() {
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
        $owner_corp_id  = $this->userInfo->pk_corp;
        $types     = $this->input->get('types', true);
        $venue_name  = $this->input->get('name', true);
        $is_use    = $this->input->get('is_use');
        $state    = $this->input->get('state', true);
        $page     = $this->input->get('per_page', true);
        $size     = $this->input->get('size', true);
        $start    = $minDate;
        $end      = $maxDate;
        $page > 0 or $page = 1;
        $size > 0 or $size = 20;

        $params = compact('owner_corp_id','types','venue_name','is_use','state','page','size','start','end');
        $allow_venue_types = $this->config->item('allow_venue_types');
        $types = array_reduce($allow_venue_types, create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
        $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'order/search.json', $params, METHOD_GET,true,2,2000);
        foreach ($data->data as $key => $value) {
            $data->data[$key]->type_name = $types[$value->type];
            switch ($value->state) {
                case ORDER_STATE_FAILED:
                    $state_class = 'order-state-failed';
                    $state_name = '支付失败';
                    break;
                case ORDER_STATE_COMPLETED:
                    if($value->is_use == ORDER_IS_USE){
                        $state_class = 'order-state-used';
                        $state_name = '已使用';
                    }else{
                        $state_class = 'order-state-unused';
                        $state_name = '未使用';
                    }
                    break;
                default:
                        $state_class = 'order-state-paying';
                        $state_name = '待付款';
                    break;
            }
            $data->data[$key]->state_class = $state_class;
            $data->data[$key]->state_name = $state_name;
        }
        $data->allow_venue_types = $this->config->item('allow_venue_types');
        $data->venue_order_pay_satatelist = $this->config->item('venue_order_pay_satatelist');
        
        $total = $data->total;
        $size = $data->size;
        $this->load->helper('pagination');
        $this->load->library('pagination');
        $pconfig = load_pagination_config($total, $size);
        $this->pagination->initialize($pconfig);
        $page_ctrl = $this->pagination->create_links();
        $data->page_ctrl = $page_ctrl;

        $this->display($data);
    }
}