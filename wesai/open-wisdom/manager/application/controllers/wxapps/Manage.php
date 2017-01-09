<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Applogin.php';

class Manage extends Applogin
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('error');
		$this->load->helper('log');
		$this->load->helper('weixin');
		$this->load->library('WeixinMenu');
	}

    public function getHostName() {
        return false;
    }

    public function getAllowedApiList() {
        return array();
    }

	private function obj2array($data)
	{
		return json_decode(json_encode($data), true);
    }

	/**管理首页*/
	public function index()
	{
		$this->verifyLogin();

		$this->load->model('contest/Analysis_model');
		$pk_corp = $this->userInfo->pk_corp;

		$data = array();

		$params    = array(
			'seller_corp_id' => $pk_corp,
		);
		$totalData = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_total.json', $params, METHOD_GET);
		$totalData = $this->obj2array($totalData);
		if (empty($totalData['result'])) {
			$data['total']['count'] = 0;
			$data['total']['amount'] = 0;
		} else {
			$data['total']['count'] = $totalData['result']['count'];
			$data['total']['amount'] = $totalData['result']['amount'];
		}

		$params['start_time'] = date('Y-m-d 00:00:00');
		$params['end_time']   = date('Y-m-d 23:59:59');
		$todayData         = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_total.json', $params, METHOD_GET);
		$todayData = $this->obj2array($todayData);
		if (empty($todayData['result'])) {
			$data['today']['count'] = 0;
			$data['today']['amount'] = 0;
		} else {
			$data['today']['count'] = $todayData['result']['count'];
			$data['today']['amount'] = $todayData['result']['amount'];
		}
		$this->display($data);

		// $data['analysisTotal'] = array(
		// 	'contest_count' => 0,
		// 	'item_count'    => 0,
		// 	'order_count'   => 0,
		// 	'amount_sum'    => 0,
		// );
		//
		// $data['analysisMonthly'] = array(
		// 	'contest_count' => 0,
		// 	'item_count'    => 0,
		// 	'order_count'   => 0,
		// 	'amount_sum'    => 0,
		// );
		//
		// $data['chatAmountList'] = '';
		// $data['chatDateStart']  = 0;
		// $data['dailyList']      = array();
		//
		// $analysisTotal = $this->Analysis_model->getTotal($pk_corp);
		// if (!empty($analysisTotal->result)) {
		// 	$data['analysisTotal'] = (array)$analysisTotal->result;
		// }
		//
		// $analysisMonthly = $this->Analysis_model->getRecently($pk_corp, $days = 30);
		// if (!empty($analysisMonthly->result)) {
		// 	$data['analysisMonthly'] = (array)$analysisMonthly->result;
		// }
		//
		// $chatAmountList        = array();
		// $data['dailyList']     = array();
		// $data['chatDateStart'] = time() * 1000;
		//
		// $dailyList = $this->Analysis_model->listDaily($pk_corp, 1, 30);
		// if (!empty($dailyList->data)) {
		// 	$preDate = '';
		// 	foreach ($dailyList->data as $analysisDaily) {
		// 		if (!empty($preDate)) {
		// 			$dateContinuityCheck = (strtotime($preDate) - strtotime($analysisDaily->date)) / (24 * 60 * 60);
		// 			if ($dateContinuityCheck > 1) {
		// 				for ($i = 0; $i < $dateContinuityCheck - 1; $i++) {
		// 					array_unshift($chatAmountList, 0.00);
		// 				}
		// 			}
		// 		}
		//
		// 		array_unshift($chatAmountList, sprintf('%.2f', $analysisDaily->amount_sum / 100));
		// 		$preDate = $analysisDaily->date;
		// 	}
		//
		// 	$data['chatDateStart'] = strtotime($dailyList->data[count($dailyList->data) - 1]->date) * 1000 + 24 * 60 * 60 * 1000;
		//
		// 	$data['dailyList'] = $dailyList->data;
		// }
		//
		// if (count($chatAmountList) < 30) {
		// 	$padNumber = 30 - count($chatAmountList);
		// 	for ($i = 0; $i < $padNumber; $i++) {
		// 		array_unshift($chatAmountList, 0.00);
		// 	}
		//
		// 	$data['chatDateStart'] -= $padNumber * 24 * 60 * 60 * 1000;
		// }
		//
		// $data['chatAmountList'] = implode(',', $chatAmountList);
		// $this->display($data);
	}

	/*菜单管理*/
	public function menu()
	{
		$this->load->library('Component');
		$authorizer_appid = $this->input->get('appid');

		// 获取公众号的基本信息等
        $authorizer_app = $this->getAuthorizerInfo($authorizer_appid);
        $authorizer_app_verifyed = $this->isAuthorizerAppVerifyed($authorizer_app);

		$menu = $this->weixinmenu->getSelfMenu($authorizer_appid);
		// 这里面要处理超时的问题
		if (empty($menu) || empty($menu->selfmenu_info)) {
			show_error_v2('获取公众号菜单信息错误', '-1');
		}
		$menu = json_encode($menu->selfmenu_info);
		// 获取公众号的基本信息等
		$mpinfo = $this->component->get_authorizer_info(WEIXIN_OPEN_APPID, $authorizer_appid);
		if (empty($mpinfo) || $mpinfo == null) {
			show_error_v2('公众号信息错误', '-1');
		}
        $menu_handle_check_ok = $this->menu_handle_check($menu);
		$link_contest = 'http://' . $authorizer_appid . CONTEST_DOMAIN_SUF;
		$link_ticket  = 'http://' . $authorizer_appid . TICKET_DOMAIN_SUF;
		$link_book  = 'http://' . $authorizer_appid . BOOK_DOMAIN_SUF;
		$links        = array(
			'contest' => array('name' => '活动报名', 'link' => $link_contest),
			//'ticket' => array('name' => '报名', 'link' => $link_ticket),
			'book' => array('name' => '场馆预定', 'link' => $link_book),
		);

		$data = array(
			'appid'  => $authorizer_appid,
			'menu'   => $menu,
			'menu_handle_check_ok'   => $menu_handle_check_ok,
			'mpinfo' => $mpinfo,
			'links'  => $links,
            'authorizer_app_verifyed' => $authorizer_app_verifyed,
		);
		$this->display($data);
	}

	/**
	 * 保存
	 */
	public function ajax_menu_save()
	{
		$authorizer_appid = $this->input->post('appid');
		$menu             = $this->input->post('menu');
		// menu 操作
		//$menu = '{"button":[{"type":"view","name":"微赛","url":"http://www.wesai.com"}]}';
		$this->load->library('WeixinMenu');
		$data = $this->weixinmenu->create($authorizer_appid, json_encode($menu, JSON_UNESCAPED_UNICODE));
		echo $data;
	}

    private function menu_handle_check($menu){
        $menu = json_decode($menu, true);
        global $MENU_API_HANDLES;
        foreach($menu['button'] as $m){
            if(isset($m['type']) && !in_array($m['type'], $MENU_API_HANDLES)){
                return false;
            }else{
                if(isset($m['sub_button'])){
                    foreach($m['sub_button']['list'] as $m_s){
                        if(!in_array($m_s['type'], $MENU_API_HANDLES)){
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

	/*支付信息管理*/
	public function pay_mch()
	{
		$this->load->model('Component_model');
		$authorizer_appid = $this->input->get('appid');
		// 获取公众号的基本信息等
        $authorizer_app = $this->getAuthorizerInfo($authorizer_appid);
        $authorizer_app_verifyed = $this->isAuthorizerAppVerifyed($authorizer_app);
        $mch_secret = $authorizer_app->result->mch_secret;
        $mch_secret = substr($mch_secret, 0, 3) . '***' . substr($mch_secret, -3);
        $authorizer_app->result->mch_secret = $mch_secret;
        
		$data = array(
            'appid' => $authorizer_appid,
            'authorizer_app' => $authorizer_app,
            'authorizer_app_verifyed' => $authorizer_app_verifyed,
            );
		$this->display($data);
	}

	public function set_pay_mch()
	{
		// 先校验是否可以下单
		$this->load->model('Component_model');
		$this->load->model('Order_model');
		$authorizer_appid = $this->input->post('appid');
		$mch_id           = $this->input->post('mch_id');
		$mch_secret       = $this->input->post('mch_secret');
		// 获取公众号的基本信息等
		$authorizer_app = $this->Component_model->getAuthorizerInfo($authorizer_appid);
		if (empty($authorizer_app) || empty($authorizer_app->result)) {
			$result = $this->errorInfo('授权公众号信息错误！');
			$this->display($result);
		}
		$authorizer_app = $authorizer_app->result;

		if ($authorizer_app->mch_id == $mch_id && $authorizer_app->mch_secret == $mch_secret) {
			$result = $this->errorInfo('支付信息配置没有变更，不需要重新设置！');
			$this->display($result);
		}

		$check = $this->Order_model->checkPayMch($authorizer_appid, $mch_id, $mch_secret, time());
		if (empty($check) || empty($check->result)) {
			$result = $this->errorInfo('支付信息配置错误，请确认是正确的商户ID和支付秘钥！');
			$this->display($result);
		}

		// 再修改
		$set = $this->Component_model->setAuthorizerPayMch($authorizer_app->authorizer_appid, $mch_id, $mch_secret);
		if (empty($set) || empty($set->affected_rows)) {
			$result = $this->errorInfo('支付信息配置失败，请重试！');
			$this->display($result);
		}

		$this->display($set);
	}

    private function getAuthorizerInfo($authorizer_appid){
		$authorizer_app = $this->Component_model->getAuthorizerInfo($authorizer_appid);
		if (empty($authorizer_app) || empty($authorizer_app->result)) {
			show_error_v2('公众号信息错误', '-1');
		}
        return $authorizer_app;
    }

    private function isAuthorizerAppVerifyed($authorizer_app){
        // 是否验证过
        $authorizer_app_verifyed = 1;
        $verify_type_info = $authorizer_app->result->verify_type_info;
        if(!empty($verify_type_info)){
            $verify_type_info = json_decode($verify_type_info);
            if(!empty($verify_type_info) && isset($verify_type_info->id) && $verify_type_info->id == -1){
                $authorizer_app_verifyed = 0;
            }
        }

        return $authorizer_app_verifyed;
    }

}

