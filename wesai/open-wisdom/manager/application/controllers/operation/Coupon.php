<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Coupon extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    public function setHostName(){
        return API_HOST_OPEN_OPERATION;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_OPERATION => array(
                'coupon/coupon_state_cancel' => 'voucher/rule/change_state_to_cancel.json',
                'coupon/coupon_state_generated' => 'voucher/rule/change_state_to_generated.json',
                'coupon/coupon_state_generating' => 'voucher/rule/change_state_to_generating.json',
                'coupon/nosubject_to_wait_post' => 'voucher/rule/change_state_fromnosubject_to_wait.json',
                'coupon/notpass_to_wait_post' => 'voucher/rule/change_state_fromnotpass_to_wait.json',
                'coupon/coupon_state_notpassed' => 'voucher/rule/change_state_to_notpassed.json',
                'coupon/coupon_state_passed' => 'voucher/rule/change_state_to_passed.json',
                'coupon/coupon_state_wait' => 'voucher/rule/change_state_to_wait.json',
                'coupon/add_coupon' => REQUEST_TPL, 
                'coupon/modify_coupon' => 'voucher/rule/modify.json',
                'coupon/get_rule_list' => 'voucher/rule/list.json',
            )
        );
    }

    /**
     * 获取代金券规则列表
     */
    public function rule_list(){
        $this->verifyLogin();//用户验证

        $corp_id    = $this->userInfo->pk_corp;
        $scope_type = $this->input->get('scope_type', true);//状态
        $state      = $this->input->get('state', true);//状态
        $page       = $this->input->get('per_page', true);
        $size       = 20;
        $params = compact('corp_id', 'scope_type', 'state', 'page', 'size');

        // 获取规则列表
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/list.json', $params, METHOD_GET);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $total = $api_result->total;
        $size = $api_result->size;
        $this->load->helper('pagination');
        $this->load->library('pagination');
        $pconfig = load_pagination_config($total, $size);
        $this->pagination->initialize($pconfig);
        $page_ctrl = $this->pagination->create_links();
        $api_result->page_ctrl = $page_ctrl;
        global $OPERATION_VOUCHER_RULE_STATE;
        $api_result->OPERATION_VOUCHER_RULE_STATE=json_encode($OPERATION_VOUCHER_RULE_STATE);  
        $this->display($api_result);
    }

    /**
     * 编辑代金券信息 - 获取代金券单条信息
     */
    public function edit_coupon(){
        $this->verifyLogin();//用户验证
        $corp_id = $this->userInfo->pk_corp;
        $rule_id = $this->input->get('voucher_rule_id', true);//状态
        $params  = compact('corp_id', 'rule_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/details.json', $params, METHOD_GET);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $api_result->result->value = $api_result->result->value /100;
        $api_result->result->value_min = $api_result->result->value_min /100;
        $this->display($api_result);
    }

    
     /**
     * 添加代金券信息 - 
     */
    public function add_coupon_rule(){
        $this->verifyLogin();//用户验证
        $corp_id = $this->userInfo->pk_corp;
        $name = $this->input->post('name', true);
        $scope_type = $this->input->post('scope_type', true);
        $number = $this->input->post('number', true);
        $value = $this->input->post('value', true);
        $value_min = $this->input->post('value_min', true);
        $stop_time = $this->input->post('stop_time', true);

        $stop_time = date("Y-m-d",strtotime($stop_time)) . " 23:59:59";//默认为当天的24点结束
        $params  = compact('corp_id', 'name' ,'scope_type', 'number', 'value', 'value_min', 'stop_time');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/add.json', $params, METHOD_POST);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 修改代金券信息 - 提交
     */
    public function modify_coupon(){
        $this->verifyLogin();//用户验证
        $corp_id = $this->userInfo->pk_corp;
        $name = $this->input->post('name', true);
        $scope_type = $this->input->post('scope_type', true);
        $number = $this->input->post('number', true);
        $value = $this->input->post('value', true);
        $value_min = $this->input->post('value_min', true);
        $stop_time = $this->input->post('stop_time', true);

        $stop_time = date("Y-m-d",strtotime($stop_time)) . " 23:59:59";//默认为当天的24点结束

        $rule_id = $this->input->post('rule_id', true);
        $params  = compact('corp_id', 'name' ,'scope_type', 'number', 'value', 'value_min', 'stop_time', 'rule_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/modify.json', $params, METHOD_POST);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 查询卡密 -  列表
     */
    public function query_list(){
        $this->verifyLogin();//用户验证
        $corp_id = $this->userInfo->pk_corp;
        $voucher_rule_id = $this->input->get('voucher_rule_id', true);
        $code = $this->input->get('code', true);
        $state = $this->input->get('state', true);
        $page = $this->input->get('per_page', true);
        $size = 20;

        $params  = compact('corp_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/rule/list.json', $params, METHOD_GET);

        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }

        global $OPERATION_VOUCHER_STATE;
        if (!empty($voucher_rule_id)) {
            $params_list  = compact('corp_id','voucher_rule_id','code','state','page','size');
            $list = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/voucher/list.json', $params_list, METHOD_GET,true,2,2000);
            if (empty($list) || $list->error != 0) {
                $this->errorInfo($list->info);
            }

            $total = $list->total;
            $size = $list->size;
            $this->load->helper('pagination');
            $this->load->library('pagination');
            $pconfig = load_pagination_config($total, $size);
            $this->pagination->initialize($pconfig);
            $page_ctrl = $this->pagination->create_links();
            $list->page_ctrl = $page_ctrl;
            $api_result->OPERATION_VOUCHER_STATE=json_encode($OPERATION_VOUCHER_STATE);
            $api_result->listdata=$list;
        }
        $api_result->OPERATION_VOUCHER_STATE_LIST=$OPERATION_VOUCHER_STATE;
        $this->display($api_result);
    }   


}
