<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'libraries/FRONT_Controller.php';
abstract class Base extends FRONT_Controller
{
	// authorizer_app 主键
	protected $BASE_SITE_URL = '';

    public function __construct(){
        parent::__construct();
        $this->load->library('session');
		$this->load->model('User_model');

		// 变量
		$this->BASE_SITE_URL = 'http://' . $_SERVER["HTTP_HOST"];
		$appId = explode('.', $_SERVER['HTTP_HOST'])[0];
        $this->User_model->setCurrentAppId($appId);
    }

    protected function getHostName(){
        return $this->setHostName();
    }

    protected function getAllowedApiList(){
        return $this->setAllowedApiList();
    }

    abstract function setHostName();
    abstract function setAllowedApiList();

    protected function getUserExtInfo($user_id){
		$params = array(
			'uid' => $user_id,
			'ext_weixin' => 1,
		);
		$userInfo = $this->callInnerApiDiy(
            API_HOST_OPEN_USER, 'user/get_by_id.json', $params, METHOD_GET
        );
		if (empty($userInfo) || $userInfo->error != 0 || empty($userInfo->result)) {
            return array();
		}
        return $userInfo->result;
    }
}
