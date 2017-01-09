<?php

require_once BASEPATH . 'libraries/FRONT_Controller.php';

/**
 * Created by PhpStorm.
 * User: zhaodc
 * Date: 16/6/16
 * Time: 下午3:49
 */
abstract class Applogin extends FRONT_Controller
{
	protected $userInfo = null;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('error');
		$this->load->helper('log');
		$this->load->library('session');
		$this->load->model('User_model');
		$this->load->model('qywx/provider_model');
		$this->load->model('qywx/weixin_model');
		$this->load->model('qywx/auth_model');

		// 获取用户信息
		$this->userInfo = $this->User_model->getUserInfo();
		if (empty($this->userInfo)) {
			$this->loginFromQy();
			$this->userInfo = $this->User_model->getUserInfo();
		}

		if (empty($this->userInfo)) {
			show_error_v2('用户信息异常！', '-1');
		}
	}

	protected function getHostName() {}

	/**
	 * @todo 错误页面
	 *
	 * stdClass Object
	 * (
	 * [corp_id] => wx47ec89b75275d679
	 * [user_type] => 1
	 * [user_id] => assetmgr@wesai.com
	 * [user_name] =>
	 * [user_avatar] =>
	 * [redirect_login_info] => stdClass Object
	 * (
	 * [login_ticket] => 1326397de378cd7549dfcb428cb1d4e1
	 * [expires_in] => 36000
	 * [expires_at] => 2016-07-05 23:44:38
	 * )
	 *
	 * [pk_corp] => 1
	 * [pk_corp_user] => 3
	 * )
	 */
	public function loginFromQy()
	{
		$auth_code = $this->input->get('auth_code', true);

		if (empty($this->userInfo) && empty($auth_code)) {
			$loginUrl    = QYWX_LOGIN_AUTH_URL . '?url=%s&state=%s&usertype=%s';
			$schema      = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
			$requestPath = explode('?', $_SERVER['REQUEST_URI'])[0];
			$getParams   = $this->input->get(null, true);
			unset($getParams['auth_code'], $getParams['expires_in']);
			if (!empty($getParams)) {
				$requestPath .= '?' . http_build_query($getParams);
			}
			$redirectUrl = rawurlencode($schema . $_SERVER['HTTP_HOST'] . $requestPath);
			$state       = 'qywx_user_login';
			$loginUrl    = sprintf($loginUrl, $redirectUrl, $state, 'all');

			header('Location: ' . $loginUrl);
			exit;
		}

		$providerInfo = $this->provider_model->getProvider();
		if (empty($providerInfo['result'])) {
			log_message('error', 'get providerInfo failed');
			show_error_v2('provider！', '-1');
		}
		$providerInfo = $providerInfo['result'];

		$weixinLoginUserInfo = $this->weixin_model->getLoginUserInfo($providerInfo['provider_access_token'], $auth_code);
		if (empty($weixinLoginUserInfo)) {
			log_message('error', 'get weixin login user info failed');
			show_error_v2('weixin login userinfo！', '-1');
		}


		$userInfo              = array();
		$userInfo['corp_id']   = $weixinLoginUserInfo['corp_info']['corpid'];
		$userInfo['user_type'] = $weixinLoginUserInfo['usertype'];
		if ($weixinLoginUserInfo['usertype'] == 1) {
			$userInfo['user_id']     = $weixinLoginUserInfo['user_info']['email'];
			$userInfo['user_name']   = '';
			$userInfo['user_avatar'] = '';
		} else {
			$userInfo['user_id']     = $weixinLoginUserInfo['user_info']['userid'];
			$userInfo['user_name']   = $weixinLoginUserInfo['user_info']['name'];
			$userInfo['user_avatar'] = $weixinLoginUserInfo['user_info']['avatar'];
		}

		if (!empty($weixinLoginUserInfo['redirect_login_info'])) {
			$weixinLoginUserInfo['redirect_login_info']['expires_at'] = date('Y-m-d H:i:s', time() + $weixinLoginUserInfo['redirect_login_info']['expires_in']);
			$userInfo['redirect_login_info']                          = $weixinLoginUserInfo['redirect_login_info'];
		} else {
			$userInfo['redirect_login_info'] = null;
		}


		// 获取pk_corp，获取pk_corp、user_id，如果不存在则创建一个用户
		$result = $this->auth_model->getCorpById($userInfo['corp_id']);
		if (empty($result) || empty($result->result)) {
			log_message('error', 'get corp by id failed');
			show_error_v2('get corp by id！', '-1');
		}
		$pk_corp             = $result->result->pk_corp;
		$userInfo['pk_corp'] = $pk_corp;

		// 校验企业号是否已经授权
		$corpAuthInfo = $this->auth_model->listAuthByCorp($pk_corp);

		if (empty($corpAuthInfo) || $corpAuthInfo->error != 0 || empty($corpAuthInfo->data)) {
			show_error_v2('请您授权套件后继续使用,谢谢!', -1);
		}

		$this->load->model('Corp_user_model');
		$result = $this->Corp_user_model->get($pk_corp, $userInfo['user_id']);
		if (empty($result) || empty($result->result)) {
			$result = $this->Corp_user_model->create($pk_corp, $userInfo['user_id'], $userInfo['user_type'], $userInfo['user_name'], $userInfo['user_avatar']);

			$pk_corp_user = $result->lastid;
		} else {
			$pk_corp_user = $result->result->pk_corp_user;
		}

		$userInfo['pk_corp_user'] = $pk_corp_user;

		// 获取用户信息
		$this->User_model->setUserInfo($userInfo);

		$getParams = $this->input->get(null, true);
		if (isset($getParams['auth_code']) || isset($getParams['expires_in'])) {
			unset($getParams['auth_code'], $getParams['expires_in']);

			$redirectUrl = explode('?', $_SERVER['REQUEST_URI'])[0];
			$queryString = '';
			if (!empty($getParams)) {
				$queryString = '?' . http_build_query($getParams);
			}

			header('Location:' . $redirectUrl . $queryString);
		}
	}

	protected function needLoginJson()
	{
		if (empty($this->verifyLogin())) {
			echo json_encode($this->errorInfo('您还没有登陆'));
			exit;
		}
	}

	protected function verifyLogin()
	{
		if (!empty($this->userInfo)) {
			return true;
		}

		return false;
	}
}
