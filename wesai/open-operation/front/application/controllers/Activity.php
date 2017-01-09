<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class Activity extends Base{
    public function setHostName(){
        return API_HOST_OPEN_OPERATION;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_OPERATION => array(
                'activity/list_by_corp' => 'activity/activity/list.json',
                'venue/select' => REQUEST_TPL,
            )
        );
    }
    
    public function jsonp_activity_list(){
        $corp_id = $this->input->get('corp_id');
        $appId = $this->input->get('appId');
        $state = OPERATION_ACTIVITY_STATE_START;
        $params = compact('corp_id','state');

        $data = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/list.json', $params, METHOD_GET);
        $lists = array();
        if($data->error == 0 and !empty($data->data)){
            foreach ($data->data as $key => &$value) {
                $value->banner = 'http://'._UPLOAD_RES_CDN_DOMAIN_.'/'.$value->banner;
                $value->url = 'http://'.$appId.OPERATION_DOMAIN_SUF.'/activity/detail?aid='.$value->activity_id;

            }
            $lists = $data->data;
        }
        $callback = $_GET["callback"];
        echo $callback . "(" . json_encode($lists) . ")";
    }
    public function index(){
        $this->needLogin();
        $userExtInfo = $this->getUserExtInfo($this->userInfo->uid);
        // userExtInfo->ext_weixin->state == 1 // 关注
        $jssdk_sdata = $this->getJssdkSign();

        $data = compact('userExtInfo','jssdk_sdata');
        $this->display($data);
    }
    public function detail(){
        $this->needLogin();
        $corp_id = $this->userInfo->corp_id;
        $user_id = $this->userInfo->uid;
        $uuid = $this->userInfo->uuid;
        $activity_id = $this->input->get('aid');
        $params = array(
            'activity_id' => $activity_id,
            'user_id' => $user_id,
        );
        $userExtInfo = $this->getUserExtInfo($this->userInfo->uid);
        $activityInfo = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/details.json', $params, METHOD_GET);
        if(empty($activityInfo) || empty($activityInfo->result)){
            show_error('活动不存在', '500', '系统升级中...');
        }
        $activityInfo->result->banner = 'http://'._UPLOAD_RES_CDN_DOMAIN_.'/'.$activityInfo->result->banner;

        $jssdk_sdata = $this->getJssdkSign();
        global $OPERATION_ACTIVITY_STATE_LIST;
        $title = "活动首页" ;

        $data = compact('activityInfo','userExtInfo','jssdk_sdata','OPERATION_ACTIVITY_STATE_LIST','uuid','title');
        $this->display($data);
    }
    public function user_action(){
        $this->needLogin();
        $user_id = $this->userInfo->uid;
        $component_authorizer_app_id = $this->userInfo->apppk;
        $activity_id = $this->input->post('activity_id');
        $type = $this->input->post('type');

        $rule = $this->input->post('voucher_rule_id');

        $params = compact('user_id','component_authorizer_app_id','activity_id','type','rule');
        $data = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/user_action.json', $params, METHOD_POST);
        $this->display($data);
    }
    public function user_invite(){
        $this->needLogin();
        $invited_fk_user = $this->userInfo->uid;
        $uuid = $this->input->post('uuid');
        $paramsUuid = compact('uuid');
        $uuidInfo = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_uuid', $paramsUuid, METHOD_GET);
        if($uuidInfo->error == 0){
            $user_id = $uuidInfo->result->pk_user;
        }
        $component_authorizer_app_id = $this->input->post('component_authorizer_app_id');
        $activity_id = $this->input->post('activity_id');
        $rule = $this->input->post('voucher_rule_id');

        $params = compact('user_id','invited_fk_user','component_authorizer_app_id','activity_id');

        $data = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'activity/activity/user_invite.json', $params, METHOD_POST);
        $this->display($data);
    }
    /**
     * qrcode_url
     *  显示图片控件，前端根据定义修改样式
     * @access public
     * @return void
     */
    public function qrcode_url(){
        $this->needLogin();
        $data = base64_encode(file_get_contents($this->userInfo->qrcode_url));
        $this->display($data);

    }

    public function list_by_corp(){
        $this->needLoginJson();
        $pk_corp = $this->userInfo->corp_id;
        $corp_id = $pk_corp;
        $page = (int)$this->input->get('page');
        $size = (int)$this->input->get('size');

        $params = array(
            'corp_id' => $corp_id,
            'state' => OPERATION_ACTIVITY_STATE_START,
            'page' => $page,
            'size' => $size,
        );
        $data = $this->callInnerApi($params);

        $this->display($data);
    }
    public function voucher_list(){
        $this->needLoginJson();
        $pk_corp = $this->userInfo->corp_id;
        $corp_id = $pk_corp;
        $page = (int)$this->input->get('page');
        $page = (int)$this->input->get('page');
        $page = (int)$this->input->get('page');
        $size = (int)$this->input->get('size');

        $params = array(
            'corp_id' => $corp_id,
            'state' => OPERATION_ACTIVITY_STATE_START,
            'page' => $page,
            'size' => $size,
        );
        $data = $this->callInnerApi($params);
        $this->display($data);
    }
}
