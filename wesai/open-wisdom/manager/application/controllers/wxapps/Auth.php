<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Applogin.php';
class Auth extends Applogin {
    public function __construct() {
        parent::__construct();
        $this->load->helper('weixin');
        $this->load->helper('error');
    }

    public function getHostName() {
        return false;
    }

    public function getAllowedApiList() {
        return array();
    }

    public function index() {
	    $this->load->model('Component_model');
	    $corp_pk = $this->userInfo->pk_corp;
	    $page    = 1;
	    $size    = 10;
	    $result  = $this->Component_model->listByCorp($corp_pk, $page, $size);
	    $apps    = array();
	    if (!empty($result) && !empty($result->data)) {
		    foreach ($result->data as $data) {
			    $app['pk_component_authorizer_app'] = $data->pk_component_authorizer_app;
			    $app['authorizer_appid']            = $data->authorizer_appid;
			    $app['nick_name']                   = $data->nick_name;
			    $app['qrcode_url']                  = $data->qrcode_url;
			    $app['ctime']                       = $data->ctime;
			    $app['state_auth']                  = $data->state_auth;
			    $apps[]                             = $app;
		    }
	    }

	    $data         = array();
	    $data['apps'] = $apps;

	    $this->display($data);
    }

    public function addwxapp() {
        $data = array('tip'=>'新增公众号');
        $this->display($data);
    }

    public function toAuth(){
        $this->load->library('Component');
        $pre_auth_code = $this->component->get_pre_auth_code(WEIXIN_OPEN_APPID);
        if(empty($pre_auth_code)){
            show_error_v2('pre_auth_code 服务异常', '-1');
        }
        $redirect_uri = 'http://' . BASE_DOMAIN . '/wxapps/auth/redirect';
        $url = WEIXIN_OPEN_COMPONENTLOGINPAGE_URL . '?';
        $url .= 'component_appid='.WEIXIN_OPEN_APPID.'&';
        $url .= 'pre_auth_code='.$pre_auth_code.'&';
        $url .= 'redirect_uri='.urlencode($redirect_uri);
        header('Location: '.$url);
    }

    /**
     * 授权回调
     */
    public function redirect(){
        $auth_code = $this->input->get('auth_code');
        $expires_in = $this->input->get('expires_in');
        $this->load->library('Component');
        $api_query_auth = $this->component->get_api_query_auth(WEIXIN_OPEN_APPID, $auth_code);
        if(empty($api_query_auth->authorization_info)){
            show_error_v2('redirect 服务异常', '-1');
        }
        $authorization_info = $api_query_auth->authorization_info;

        $authorizer_appid = $authorization_info->authorizer_appid;
        $authorizer_access_token = $authorization_info->authorizer_access_token;
        $authorizer_refresh_token= $authorization_info->authorizer_refresh_token;
        $func_info = json_encode($authorization_info->func_info);

        $this->load->model('Component_model');
        $result = $this->Component_model->get(WEIXIN_OPEN_APPID);
        if(empty($result) || empty($result->result)){
            show_error_v2('component get 服务异常', '-1');
        }
        $component_app = $result->result;
        $fk_component_app = $component_app->pk_component_app;

        $corp_pk = $this->userInfo->pk_corp;
        $result = $this->Component_model->setAuthorizerToken($authorizer_appid, $authorizer_access_token, $authorizer_refresh_token, $func_info, $fk_component_app, $corp_pk);
        if(empty($result) || $result->error != 0){
            if($result->error = -202){
                show_error_v2($result->info, '-1');
            }else{
                show_error_v2('授权公众号设置异常', '-1');
            }
        }
        // 获取公众号的基本信息等
        $info = $this->component->get_authorizer_info(WEIXIN_OPEN_APPID, $authorizer_appid);
        $result = $this->Component_model->setAuthorizerInfo($authorizer_appid, $info);

        $url = 'http://' . BASE_DOMAIN . '/wxapps/auth';
        header('Location: '.$url);
    }

}

