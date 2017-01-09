<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class Voucher extends Base{
    public function setHostName(){
        return API_HOST_OPEN_OPERATION;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_OPERATION => array(
            )
        );
    }
    public function index(){
        $this->needLogin();
        $data = array();
        $appId = explode('.', $_SERVER['HTTP_HOST'])[0];
        $_SESSION['appId'];
        $jssdk_sdata = $this->getJssdkSign();
        $data = compact('jssdk_sdata');
        $data['title'] = "我的优惠券";
        $this->display($data);
    }




    public function get_voucher_list(){
        $this->needLogin();
        $user_id = $this->userInfo->uid;
        $component_authorizer_app_id = $this->userInfo->apppk;
        $state  = $this->input->get('state');
        $page  = $this->input->get('page');
        $size  = $this->input->get('size');
        $params = compact('user_id','component_authorizer_app_id','state','page','size');
        $voucherList = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'voucher/voucher/list_by_uid.json', $params, METHOD_GET);
        $jssdk_sdata = $this->getJssdkSign();
              $data = compact('jssdk_sdata','voucherList');
        $this->display($data);
    }

}
