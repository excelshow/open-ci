<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Component
 * 获取开放平台授权相关信息
 */
class Component
{
	protected $CI = null;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('Component_model');
		$this->CI->load->library('curl');
	}

	/**
	 * 获取component pre auth code
	 */
	public function get_pre_auth_code($appid)
	{
		$result = $this->CI->Component_model->get($appid);
		if (empty($result) || empty($result->result)) {
			return false;
		}
		$result = $result->result;

		$access_token = $result->component_access_token;
		$url          = WEIXIN_OPEN_PRE_AUTHCODE_URL . '?component_access_token=' . $access_token;

		$postData = array('component_appid' => $appid);
		$data     = json_encode($postData);

		$ret = $this->CI->curl->post($data, $url);
		if (!empty($ret)) {
			$ret = json_decode($ret);
			if (!empty($ret->pre_auth_code)) {
				return $ret->pre_auth_code;
			}
		}
	}

	/**
	 * 获取component api_query_auth
	 */
	public function get_api_query_auth($appid, $auth_code)
	{
		$result = $this->CI->Component_model->get($appid);
		if (empty($result) || empty($result->result)) {
			return false;
		}
		$result = $result->result;

		$access_token = $result->component_access_token;
		$url          = WEIXIN_OPEN_API_QUERYAUTH_URL . '?component_access_token=' . $access_token;

		$postData = array('component_appid' => $appid, 'authorization_code' => $auth_code);
		$data     = json_encode($postData);

		$ret = $this->CI->curl->post($data, $url);
		if (!empty($ret)) {
			$ret = json_decode($ret);

			return $ret;
		}
	}

	/**
	 * 获取authorizer_info
	 */
	public function get_authorizer_info($component_appid, $authorizer_appid)
	{
		$result = $this->CI->Component_model->get($component_appid);
		if (empty($result) || empty($result->result)) {
			return false;
		}
		$result = $result->result;

		$access_token = $result->component_access_token;
		$url          = WEIXIN_OPEN_API_AUTHORIZER_INFO_URL . '?component_access_token=' . $access_token;

		$postData = array('component_appid' => $component_appid, 'authorizer_appid' => $authorizer_appid);
		$data     = json_encode($postData);

		$ret = $this->CI->curl->post($data, $url);
		if (!empty($ret)) {
			$ret = json_decode($ret);

			return $ret;
		}
	}


}
