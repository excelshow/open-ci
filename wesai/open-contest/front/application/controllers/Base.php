<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'libraries/FRONT_Controller.php';

abstract class Base extends FRONT_Controller
{

	// 当前登录用户
	protected $apppk = 0;
	// 跳转地址,分享地址
	protected $BASE_SITE_URL = '';
	protected $debug         = 0;
	protected $redirect_url  = '';

	// 连续两个流程一块校验

	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('smarty');
		$this->load->helper('common');
		$this->load->model('User_model');

		// 变量
		$this->BASE_SITE_URL = 'http://' . $_SERVER["HTTP_HOST"];

		$appId = explode('.', $_SERVER['HTTP_HOST'])[0];
		$this->appId = $appId;
		$this->User_model->setAppId($appId);
	}

	protected function getHostName()
	{
		return API_HOST_OPEN_CONTEST;
	}

	protected function verifyLogin()
	{
		$userInfo = $this->User_model->getLoginUserInfo();
		if (empty($userInfo['uid'])) {
			$this->redirectToGetWeixinOpenId();
		}

		return $userInfo;
	}

	protected function redirectToGetWeixinOpenId()
	{
		$url = 'http://' . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
		$url = $this->BASE_SITE_URL . '/user/login?' . http_build_query(compact('url'));

		$params = array(
			'redirect_uri'    => WEIXIN_REDIRECT_PROXY_URL . '?' . http_build_query(compact('url')),
			'appid'           => $_SESSION['appId'],
			'response_type'   => 'code',
			'scope'           => WEIXIN_SNSAPI_SCOPE_BASE,
			'state'           => WEIXIN_SNSAPI_SCOPE_BASE,
			'component_appid' => COMPONENT_APPID,
		);

		$url = WEIXIN_SNSAPI_AUTHORIZE_URL . '?' . http_build_query($params) . '#wechat_redirect';

		header('Location: ' . $url);
		exit;
	}

	protected function get_jssdk_sign()
	{
		$userInfo = $this->User_model->getLoginUserInfo();

		$apppk = $userInfo['apppk'];

		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

		$url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$jsApiConfig = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'component/authorizer/get_jsapi_config', compact('apppk', 'url'), METHOD_GET);
		$jsApiConfig = $this->obj2array($jsApiConfig);

		if (empty($jsApiConfig['result'])) {
			return $this->errorInfo('系统错误，请稍后再试！');
		}

		return $jsApiConfig['result'];
	}

	protected function obj2array($data)
	{
		return json_decode(json_encode($data), true);
	}

	protected function array2obj($data)
	{
		return json_decode(json_encode($data));
	}

	protected function list_location($cid)
	{
		$params   = compact('cid');
		$newlocation = null;
		$location = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		if($location->error == 0 and $location->result->country_scope == 1 ){
			$location = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'tag/list_location.json', $params, METHOD_GET);
			$location = $this->obj2array($location);
			if (empty($location['data'])) {
				return array();
			}
			$location = $location['data'];
			$tagIds   = array_column($location, 'fk_tag');
			$ids      = implode(',', $tagIds);
			$params   = compact('ids');
			$tag_list = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'tag/get_by_ids.json', $params, METHOD_GET);
			$tag_list = $this->obj2array($tag_list);
			if (empty($tag_list['data'])) {
				return array();
			}
			$tag_array   = array_reduce($tag_list['data'], create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
			$newlocation = array();
			foreach ($location as $key => $value) {
				$newlocation[$value['level']] = $tag_array[$value['fk_tag']];
			}
		}
		return $newlocation;
	}

    public function display($data = array()){
    	global $NAVIGATOR_LIST;
    	!empty($data) or $data = array();
		$jssdk_sdata = $this->get_jssdk_sign();
		if(is_array($data)){
        	$data['jssdk_sdata'] = $jssdk_sdata;
			$data['NAVIGATOR_LIST'] = $NAVIGATOR_LIST;
		}else{
        	$data->jssdk_sdata = $jssdk_sdata;
			$data->NAVIGATOR_LIST = $NAVIGATOR_LIST;
		}

		if (empty($_SESSION['template'])) {
			$_SESSION['template'] = '';
		}

        parent::display($data);
    }
	protected function get_contest_info($cid, $intro = 1)
	{
		$params = compact('cid', 'intro');
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		$result = $this->obj2array($result);

		return $result;
	}

	protected function get_item_info($item_id)
	{
		$params = compact('item_id');
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', $params, METHOD_GET);
		$result = $this->obj2array($result);

		return $result;
	}
}
