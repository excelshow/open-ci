<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Product extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('contest/Contest_model');
	}

	public function getHostName() {
		return API_HOST_OPEN_API;
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_API => array(
            )
		);
	}

	public function index()
	{
		$this->needLoginJson();//用户验证

		$corp_id = $this->userInfo->pk_corp;
		$user_id = $this->userInfo->pk_corp_user;

		$params = compact('corp_id', 'user_id');
		// 获取活动列表
		$result = $this->callInnerApiDiy(
            API_HOST_OPEN_API, 
            'softykt/product/import_product.json', 
            $params, 
            METHOD_POST,
            true,
            30,
            3000
        );
        if(empty($result)){
            $result = new stdClass();
            $result->error = -1;
        }

        if($result->error != 0){
			$result->info = "金飞鹰产品信息同步失败（".$result->error."）";
            log_message('error', $result->info);
		}else{
			//$result->info = "金飞鹰产品信息同步成功，需要您确认后才能上架。";
        }
		$this->display($result);
	}

}
