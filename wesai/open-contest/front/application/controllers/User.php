<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class User extends Base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Contest_model');
		$this->load->model('Component_model');
		$this->load->library('SmsService');
	}
	


	public function getAllowedApiList()
	{
		return array();
	}

	
	public function index()
	{
		$userInfo = $this->verifyLogin();
		$data  =  array();
		$title = "我的"; 
		$data=compact('title');
		$this->display($data);
	}
	public function groupList()
	{
		$userInfo = $this->verifyLogin();
		$data  =  array();
		$title = "我的小组"; 
		$data=compact('title');
		$this->display($data);
	}
	public function teamList()
	{
		$userInfo = $this->verifyLogin();
		$data  =  array();
		$title = "我的团队"; 
		$data=compact('title');
		$this->display($data);
	}

	public function captcha()
	{
		//创建图片，定义颜色值
		header("Content-type: image/PNG");
		$options = array(
			"width"     => 100,
			"height"    => 42,
			"content"   => 1,
			"lineWidth" => 1,
		);
		$this->load->library('SimpleCaptcha'); //图片验证生成
		$simpleCaptcha = new SimpleCaptcha($options);
		$simpleCaptcha->ShowImage();
		$captchaCode               = $simpleCaptcha->GetCaptchaText();
		$_SESSION['_captchaCode_'] = $captchaCode;
		exit();
	}
	//
	// public function profile()
	// {
	// 	$userInfo = $this->verifyLogin();
	//
	// 	$webtitle = "个人中心";
	// 	$data     = array();
	//
	// 	$uid    = $userInfo['uid'];
	// 	$result = $this->User_model->getuserinfo_by_uid($uid);
	// 	if (empty($result) || $result->error != 0 || empty($result->result)) {
	// 		$this->_displayError('用户资料有误');
	// 	}
	// 	$userinfo    = $result->result;
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "userinfo", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function profilemanage()
	// {
	// 	$userInfo = $this->verifyLogin();
	//
	// 	$webtitle = "个人中心";
	//
	// 	$uid    = $userInfo['uid'];
	// 	$result = $this->User_model->getuserinfo_by_uid($uid);
	// 	if (empty($result) || $result->error != 0 || empty($result->result)) {
	// 		$this->_displayError('用户资料有误');
	// 	}
	// 	$userinfo    = $result->result;
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "userinfo", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function checkmobile()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$webtitle = "验证手机号";
	// 	$itemid   = $this->input->get('itemid', true);
	// 	$uid      = $userInfo['uid'];
	// 	$result   = $this->User_model->getuserinfo_by_uid($uid);
	// 	if (empty($result) || $result->error != 0 || empty($result->result)) {
	// 		$this->_displayError('用户资料有误');
	// 	}
	// 	$userinfo    = $result->result;
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "userinfo", "itemid", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	public function ajax_send_sms_verify_code()
	{
		$userInfo = $this->verifyLogin();
	
		$mobile             = $this->input->post('mobile');
		$tucode             = $this->input->post('tucode');
		$_SESSION['vphone'] = $mobile;
		if ($tucode != $_SESSION['_captchaCode_']) {
			exit(json_encode(array('error' => -3, 'info' => '图形验证码有误')));
		}
		try {
			$result = $this->smsservice->sendCode(SMS_CLIENT_ID_CONTEST, $mobile, $userInfo['apppk']);
			$data   = array();
			if ($result && $result->error == 0) {
				$data['error'] = 0;
			} else {
				$data['error'] = $result->error;
			}
		} catch (Exception $e) {
			$data          = array();
			$data['error'] = -1;
		}
		$this->display($data);
	}
	
	public function ajax_verify_sms_code()
	{
		$userInfo = $this->verifyLogin();
	
		$mobile = $this->input->post('mobile');
		$code   = $this->input->post('vcode');
		$tucode = $this->input->post('tucode');
		if ($mobile != $_SESSION['vphone']) {
			exit(json_encode(array('error' => -1, 'info' => '手机号有误')));
		}
		if ($tucode != $_SESSION['_captchaCode_']) {
			exit(json_encode(array('error' => -3, 'info' => '图形验证码有误')));
		}
		$result = $this->smsservice->verifyCode(SMS_CLIENT_ID_CONTEST, $mobile, $code);
		if (empty($result)) {
			exit(json_encode(['error' => -1, 'info' => '服务器错误']));
		}
	
		if ($result->error != 0) {
			exit(json_encode(['error' => -2, 'info' => '短信验证码有误']));
		}
	
		$uid    = $userInfo['uid'];
		$params = compact('mobile', 'code','tucode','uid');
	    $result = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/bind_mobile.json', $params, METHOD_POST);
		$this->display($result);
	}
	
    public function getuserinfo_by_uid()
    {	
    	$userInfo = $this->verifyLogin();
    	$uid        = $userInfo['uid'];
    	$ext_mobile = 1;
	    $params     = compact('uid', 'ext_mobile');
	    $result= $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_id.json', $params,METHOD_GET);
	    $this->display($result);
    }



	//
	// public function contestorderlist()
	// {
	// 	$userInfo = $this->verifyLogin();
	//
	// 	$webtitle = "报名订单";
	//
	// 	$uid = $userInfo['uid'];
	//
	// 	$this->load->model('Contest_model');
	// 	$result = $this->Contest_model->get_myorderlist($userInfo['fk_corp'], $userInfo['apppk'], $uid, $page = 1, $size = 10, $contest_info = 1);
	//
	// 	if (empty($result) || $result->error != 0) {
	// 		$this->_displayError('订单有误');
	// 	}
	//
	// 	$orderlist = $result->data;
	//
	// 	global $ORDER_PAY_SATATELIST;
	// 	$pagetotal   = $result->total;
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "orderlist", "ORDER_PAY_SATATELIST", "pagetotal", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function ajax_orderist()
	// {
	// 	$userInfo = $this->verifyLogin();
	//
	// 	$uid  = $userInfo['uid'];
	// 	$page = $this->input->get("page", true);
	// 	$this->load->model('Contest_model');
	// 	$result = $this->Contest_model->get_myorderlist($uid, $page, $size = 10);
	// 	if (empty($result) || $result->error != 0) {
	// 		$this->_displayError('订单有误');
	// 	}
	// 	$total        = $result->total;
	// 	$tmporderlist = $result->data;
	// 	$orderlist    = array();
	// 	foreach ($tmporderlist as $key) {
	// 		$cid    = $key->fk_contest;
	// 		$itemid = $key->fk_contest_items;
	// 		//活动信息
	// 		$contestinfo      = $this->Contest_model->get_contest($cid, $intro = 0);
	// 		$key->contestinfo = $contestinfo->result;
	// 		//竞赛信息
	// 		$iteminfo      = $this->Contest_model->get_iteminfo($itemid);
	// 		$key->iteminfo = $iteminfo->result;
	// 		$orderlist[]   = $key;
	// 	}
	// 	global $ORDER_PAY_SATATELIST;
	// 	$upload_res_url = 'http://' . _UPLOAD_RES_CDN_DOMAIN_;
	// 	$static_res_url = 'http://' . _STATIC_RES_CDN_DOMAIN_;
	// 	$data           = compact("page", "total", "orderlist", "ORDER_PAY_SATATELIST", "upload_res_url", "static_res_url");
	// 	$this->display($data);
	// }
	//
	// public function addressmaster()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$webtitle = "管理收货地址";
	// 	$page     = 1;
	// 	$size     = 10;
	// 	$uid      = $userInfo['uid'];
	// 	$listdata = array();
	// 	$result   = $this->User_model->list_addresss($uid, $page, $size);
	// 	if (!empty($result) && $result->error == 0) {
	// 		$listdata = $result->data;
	// 	}
	// 	$data        = array();
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "listdata", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function address()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$webtitle = "选择收货地址";
	// 	$page     = 1;
	// 	$size     = 10;
	// 	$uid      = $userInfo['uid'];
	// 	$listdata = array();
	// 	$result   = $this->User_model->list_addresss($uid, $page, $size);
	// 	if (!empty($result) && $result->error == 0) {
	// 		$listdata = $result->data;
	// 	}
	// 	$data        = array();
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "listdata", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function addressadd()
	// {
	// 	$userInfo    = $this->verifyLogin();
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function addressedit()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$webtitle = "编辑地址";
	// 	$addrid   = $this->input->get("addrid", true);
	// 	$uid      = $userInfo['uid'];
	// 	$addrdata = array();
	// 	$result   = $this->User_model->get_addresss($uid, $addrid);
	// 	if (!empty($result) && $result->error == 0) {
	// 		$addrdata = $result->result;
	// 	}
	// 	$data        = array();
	// 	$jssdk_sdata = $this->get_jssdk_sign();
	// 	$data        = compact("webtitle", "addrdata", "jssdk_sdata");
	// 	$this->display($data);
	// }
	//
	// public function ajax_deladdress()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$addrid   = $this->input->post("addrid", true);
	// 	$uid      = $userInfo['uid'];
	// 	$result   = $this->User_model->del_addresss($uid, $addrid);
	// 	$this->display($result);
	// }
	//
	// public function ajax_addaddress()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$name     = $this->input->post("name", true);
	// 	$addr     = $this->input->post("addr", true);
	// 	$mobile   = $this->input->post("mobile", true);
	// 	$zipcode  = $this->input->post("zipcode", true);
	// 	$uid      = $userInfo['uid'];
	// 	$result   = $this->User_model->add_addresss($uid, $name, $addr, $mobile, $zipcode);
	// 	$this->display($result);
	// }
	//
	// public function ajax_setdefaultaddress()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$addrid   = $this->input->post("addrid", true);
	// 	$uid      = $userInfo['uid'];
	// 	$result   = $this->User_model->setdefault_addresss($uid, $addrid);
	// 	$this->display($result);
	// }
	//
	// public function ajax_updateaddress()
	// {
	// 	$userInfo = $this->verifyLogin();
	// 	$addrid   = $this->input->post("addrid", true);
	// 	$name     = $this->input->post("name", true);
	// 	$addr     = $this->input->post("addr", true);
	// 	$mobile   = $this->input->post("mobile", true);
	// 	$zipcode  = $this->input->post("zipcode", true);
	// 	$uid      = $userInfo['uid'];
	// 	$result   = $this->User_model->update_addresss($uid, $addrid, $name, $addr, $mobile, $zipcode);
	// 	$this->display($result);
	// }

	public function login()
	{
		$toUrl       = $this->input->get('url', true);
		$wxAuthCode  = $this->input->get('code', true);
		$wxAuthState = $this->input->get('state', true);

		if (empty($toUrl) || empty($wxAuthCode) || empty($wxAuthState)) {
			log_message_v2('error');

			return $this->displayError('登录失败（-1）', -1);
		}

		$authorizer_appid = $this->User_model->getAppId();

		$componentAuthInfo = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'component/authorizer/get.json', compact('authorizer_appid'), METHOD_GET);
		$componentAuthInfo = $this->obj2array($componentAuthInfo);
		if (empty($componentAuthInfo['result'])) {
			log_message_v2('error');
			$this->displayError('登录失败（-2）');
		}
		$componentAuthInfo = $componentAuthInfo['result'];

		$params = array(
			'apppk' => $componentAuthInfo['pk_component_authorizer_app'],
			'code'  => $wxAuthCode,
			'snsapi' => $wxAuthState,
		);

		$userInfo = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_wxcode.json', $params, METHOD_GET, false);
		$userInfo = $this->obj2array($userInfo);

		if (empty($userInfo['result'])) {
			log_message_v2('error');
			$this->displayError('登录失败（-3）');
		}
		$userInfo = $userInfo['result'];

		$uid     = $userInfo['pk_user'];
		$openid  = $userInfo['ext_weixin']['openid'];
		$apppk   = $componentAuthInfo['pk_component_authorizer_app'];
		$corp_id = $componentAuthInfo['fk_corp'];

		$this->User_model->setLoginUserInfo($uid, $openid, $apppk, $corp_id);

		header('Location: ' . $toUrl);
		exit();
	}
}
