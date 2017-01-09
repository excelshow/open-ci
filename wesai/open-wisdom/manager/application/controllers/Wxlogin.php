<?php

require_once __DIR__ . '/Base.php';

/**
 * Created by PhpStorm.
 * User: zhaodc
 * Date: 16/6/16
 * Time: 下午3:49
 */
class Wxlogin extends Base
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
		$this->load->model('weixin/oauth_model');
		$this->load->model('Corp_user_model');

	}

	protected function needLogin($corpId)
	{
		// var_dump($_SESSION);
		// session_destroy();die();
		if ($this->verifyLogin($corpId)) {
			return true;
		}

		$corpInfo = $this->auth_model->getCorpById($corpId);
		if (empty($corpInfo->result)) {
			log_message('error', 'get corp info by corp_id failed');
			show_error_v2('get corp by id failed！', '-1');
		}

		$authCode  = $this->input->get('code', true);
		$authState = $this->input->get('state', true);
		if (!empty($authCode) && $authState == 'state') {
			$corpAuthInfo = $this->auth_model->listAuthByCorp($corpInfo->result->pk_corp, 1, 1);
			if (empty($corpAuthInfo->data)) {
				log_message('error', 'get auth info by pk_corp failed');
				show_error_v2('get auth info by pk_corp！', '-1');
			}

			$corpAccessToken = $corpAuthInfo->data[0]->access_token;

			$userInfo = $this->oauth_model->getUserInfo($corpAccessToken, $authCode);
			if (!empty($userInfo)) {
				if (!empty($userInfo['UserId'])) {
					$corpUserInfo = $this->Corp_user_model->get($corpInfo->result->pk_corp, $userInfo['UserId']);
					if (empty($corpUserInfo->result)) {
						$corpUserId = $this->Corp_user_model->create($corpInfo->result->pk_corp, $userInfo['UserId'], 5, $userInfo['UserId'], '');
						if (empty($corpUserId->lastid)) {
							log_message('error', 'create corp user failed');
							show_error_v2('获取身份信息失败！', '-1');
						}
						$corpUserId = $corpUserId->lastid;
					} else {
						$corpUserId = $corpUserInfo->result->pk_corp_user;
					}
					$_SESSION[$corpId]['userInfo'] = array(
						'userId'     => $userInfo['UserId'],
						'pkCorp'     => $corpInfo->result->pk_corp,
						'corpUserId' => $corpUserId,
					);
					$_SESSION['corp_id']           = $corpId;
					return true;
				}

				log_message('error', 'no authorization');
				show_error_v2('no authorization', '-1');
			}
		}


		if (!$this->verifyLogin($corpId)) {
			$redirectUrl = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
			$redirectUrl .= $_SERVER['HTTP_HOST'];
			$requestPath = explode('?', $_SERVER['REQUEST_URI']);
			$redirectUrl .= $requestPath[0];

			$getParams = $this->input->get(null, true);
			unset($getParams['code'], $getParams['state']);

			if (!empty($getParams)) {
				$redirectUrl .= '?' . http_build_query($getParams);
			}

			$loginUrl = $this->oauth_model->getLoginUrl($corpId, $redirectUrl);
			header('Location:' . $loginUrl);
			exit();
		}
	}

	protected function verifyLogin($corpId)
	{
		if (!empty($_SESSION[$corpId]['userInfo'])) {
			return true;
		}

		return false;
	}

	protected function needLoginJson($corpId)
	{
		if (empty($this->verifyLogin($corpId))) {
			echo json_encode($this->errorInfo('您还没有登陆'));
			exit;
		}
	}

	protected function errorInfo($info, $code = -1)
	{
		return array('error' => $code, 'info' => $info);
	}
}
