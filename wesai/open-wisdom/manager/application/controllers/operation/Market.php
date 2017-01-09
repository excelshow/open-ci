<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Market extends FrontBase
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
                'market/bind_operation' => 'activity/activity/bind_operation.json',
                'market/unbind_operation' => 'activity/activity/unbind_operation.json',
                'market/add_activity' => REQUEST_TPL,
            )
        );
    }

    /**
     * 根据企业id 获取企业下的活动信息
     */
    public function activity_list(){

        $this->verifyLogin();//用户验证

        $corp_id = $this->userInfo->pk_corp;
        $state   = $this->input->get('state', true);//状态
        $page    = $this->input->get('per_page', true);
        $size    = 20;
        $page > 0 or $page = 1;
        $params = compact('corp_id', 'state', 'page', 'size');

        // 获取活动列表
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/list.json', $params, METHOD_GET,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $data   = array();
        $total = $api_result->total;
        $size = $api_result->size;
        $this->load->helper('pagination');
        $this->load->library('pagination');
        $pconfig = load_pagination_config($total, $size);
        $this->pagination->initialize($pconfig);
        $page_ctrl = $this->pagination->create_links();
        $api_result->page_ctrl = $page_ctrl;
        global $OPERATION_ACTIVITY_STATE;
        $api_result->OPERATION_ACTIVITY_STATE=json_encode($OPERATION_ACTIVITY_STATE);
        $this->display($api_result);
    }

    /**
     * 创建新的活动
     */
    public function ajax_create_activity(){
        $this->verifyLogin();//用户验证

        $name           = $this->input->post('name', true);
        $time_start     = $this->input->post('time_start', true);
        $time_end       = $this->input->post('time_end', true);
        $need_follow    = $this->input->post('need_follow', true);
        $number         = $this->input->post('number', true);
        $number_max     = $this->input->post('number_max', true);
        $number_invite_one = $this->input->post('number_invite_one', true);
        $order          = $this->input->post('orderby', true);
        $banner         = $this->input->post('banner', true);
        $desc_rule         = $this->input->post('desc_rule', true);
        $corp_id = $this->userInfo->pk_corp;
        $time_end = date("Y-m-d",strtotime($time_end)) . " 23:59:59";//默认为当天的24点结束
        //$time_end = date('Y-m-d H:i:s' ,strtotime($time_end) + 24 * 60 * 60 -1); //默认为当天的24点结束
        $params = compact('corp_id', 'name', 'time_start', 'time_end', 'need_follow',
            'number', 'number_max', 'number_invite_one', 'order', 'banner', 'desc_rule');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/add.json', $params, METHOD_POST,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 修改活动信息  -- 获取活动详情
     */
    public function edit_activity(){
        $this->verifyLogin();//用户验证
        $activity_id = $this->input->get('activity_id', true);
        $corp_id     = $this->userInfo->pk_corp;
        $params = compact('corp_id', 'activity_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/details.json', $params, METHOD_GET,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 修改活动信息 tofrom
     */
    public function ajax_save_activity(){
        $this->verifyLogin();//用户验证
        $name           = $this->input->post('name', true);
        $time_start     = $this->input->post('time_start', true);
        $time_end       = $this->input->post('time_end', true);
        $need_follow    = $this->input->post('need_follow', true);
        $number         = $this->input->post('number', true);
        $number_max     = $this->input->post('number_max', true);
        $number_invite_one = $this->input->post('number_invite_one', true);
        $activity_id    = $this->input->post('activity_id', true);
        $order          = $this->input->post('orderby', true);
        $banner         = $this->input->post('banner', true);
        $desc_rule         = $this->input->post('desc_rule', true);
        $time_end = date("Y-m-d",strtotime($time_end)) . " 23:59:59";//默认为当天的24点结束

        $corp_id = $this->userInfo->pk_corp;
        $params = compact('corp_id', 'name', 'time_start', 'time_end', 'need_follow', 'number',
            'number_max', 'number_invite_one', 'activity_id', 'order', 'banner', 'desc_rule');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/modify.json', $params, METHOD_POST,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 修改活动状态
     */
    public function ajax_groupding_activity(){
        $this->verifyLogin();//用户验证
        $activity_id    = $this->input->post('activity_id', true);
        $state          = $this->input->post('state', true);
        $corp_id = $this->userInfo->pk_corp;
        $params = compact('corp_id','state','activity_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/modify_state.json', $params, METHOD_POST,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

    /**
     * 查询未关联的代金券规则
     */
    public function ajax_nobind_operation(){
        $this->verifyLogin();//用户验证
        $activity_id    = $this->input->get('activity_id', true);
        $corp_id        = $this->userInfo->pk_corp;

        $params = compact('corp_id','activity_id');
        $api_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/nobing_list.json', $params, METHOD_GET,true,2,1000);
        if (empty($api_result) || $api_result->error != 0) {
            $this->errorInfo($api_result->info);
        }
        $this->display($api_result);
    }

}
