<?php

class WeixinMenu
{
	private static $_instance = null;
	protected      $CI        = null;

	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('Component_model');
		$this->CI->load->library('curl');
	}

	public static function getInstance()
	{
		if (self::$_instance instanceof static) {
			return self::$_instance;
		}

		return new static;
	}

	/**
	 * do create menu action
	 *
	 * @return boolen
	 */
	public function create($appid, $menu_data)
	{
		$access_token = $this->getAccessToken($appid);
		$url          = WEIXIN_CREATE_MENU_URL;
		$params       = array(
			'access_token' => $access_token,
		);
		$url .= "?" . http_build_query($params);
		$ret  = '';
		$max  = 3;
		$loop = 1;
		while (true) {
			$ret = $this->CI->curl->post($menu_data, $url);
			//var_dump($ret);
			if (!empty($ret) || $loop >= $max) {
				break;
			}
			$loop++;
		}

		return $ret;
	}

	private function getAccessToken($appid)
	{
		$result = $this->CI->Component_model->getAuthorizerInfo($appid);
		if (empty($result) || empty($result->result)) {
			log_message('error', 'Component_model getAuthorizerInfo error = ' . json_encode($result->result));

			return false;
		}
		$result = $result->result;

		return $result->authorizer_access_token;
	}

	public function getSelfMenu($appid)
	{
		$access_token = $this->getAccessToken($appid);
		$params       = array(
			'access_token' => $access_token,
		);

		$url = WEIXIN_GET_SELFMENU_URL;
		$url .= "?" . http_build_query($params);

		$ret = $this->CI->curl->get($url);

		if (!empty($ret)) {
			$result = json_decode($ret);

			return $result;
		} else {
			log_message('error', 'getSelfMenu error');

			return false;
		}
	}

	/**
	 * do create menu action
	 *
	 * @return boolen
	 */
	public function get()
	{
		$url          = WEIXIN_GET_MENU_URL;
		$access_token = Weixin_Class_AccessToken::getInstance()->load_access_token();
		$params       = array(
			'access_token' => !empty($access_token->access_token) ? $access_token->access_token : 'false',
		);

		$url .= "?" . http_build_query($params);

		$xcurl = new Lib_Curl($url);
		$ret   = $xcurl->get();

		if (!empty($ret)) {
			$result = json_decode($ret);
			var_dump($result);
		} else {
			echo 'Get Menu Failed!' . PHP_EOL;
		}
	}


	/**
	 * do delete menu action
	 *
	 * @return boolen
	 */
	public function delete()
	{
		$url          = WEIXIN_DEL_MENU_URL;
		$access_token = Weixin_Class_AccessToken::getInstance()->load_access_token();
		$params       = array(
			'access_token' => !empty($access_token->access_token) ? $access_token->access_token : 'false',
		);

		$url .= "?" . http_build_query($params);

		$xcurl = new Lib_Curl($url);
		$ret   = $xcurl->get();

		if (!empty($ret)) {
			$result = json_decode($ret);
			var_dump($result);
		} else {
			echo 'Delete Menu Failed!' . PHP_EOL;
		}
	}

	/**
	 * load menu data
	 *
	 * @return json
	 */
	private function load_menu_data($appid)
	{
		$file = WEIXIN_MENU_DIR . '/menu_' . $appid . '.txt';
		if (file_exists($file) && is_readable($file)) {
			return file_get_contents($file);
		}

		return false;
	}
}
