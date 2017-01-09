<?php

require_once dirname(__DIR__) . '/Wxlogin.php';

/**
 * User: zhaodc
 * Date: 8/4/16
 * Time: 23:51
 */
class Verify extends Wxlogin
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('qywx/auth_model');
		$this->load->model('qywx/corp_model');
		$this->load->model('contest/Order_model');
		$this->load->model('contest/Contest_model');
	}

	private function getJsApiConfig()
	{
		$url = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

		$jsApiConfig = $this->corp_model->getJsApiConfig($_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'], $url);
		if (empty($jsApiConfig['result'])) {
			log_message('error', 'getJsApiConfig failed');
			show_error_v2('load jsapi config failed', '-1');
		}

		return $jsApiConfig;
	}

	public function loose($corpId)
	{
		$this->needLogin($corpId);
		$data           = array();
		$data['corpId'] = $corpId;



		$jsApiConfig = $this->getJsApiConfig();

		$data['jsApiConfig'] = $jsApiConfig['result'];
		$this->display($data);
	}
	//拿到base64切分成核销码；
	public function ajax_check_qrcode_data()
	{
		$this->needLoginJson($_SESSION['corp_id']);

		$std        = new stdClass();
		$std->error = 0;
		$std->msg   = '';

		$code = $this->input->post('data', true);
		if (empty($code)) {
			$std->error = -1;
			$std->msg   = '参数错误';
			$this->display($std);
		}

		$code = $this->decryptOrderData($code);

		if ($code->error < 0) {
			$std->error = -2;
			$std->msg   = '二维码无效|' . $code->error;
			$this->display($std);

		}
		$std->code = $code->orderId;
		$this->display($std);
	}
	//base64切分成核销码；
	private function decryptOrderData($orderData)
	{
		$std        = new stdClass();
		$std->error = 0;

		$orderData = base64_decode($orderData);
		if (empty($orderData)) {
			$std->error = -1;

			return $std;
		}

		$orderData = explode('|', $orderData);
		if (count($orderData) != 3) {
			$std->error = -2;

			return $std;
		}

		$originSign   = $orderData[2];
		$orderData[2] = ORDER_ENCRYPT_KEY;

		$sign = md5(implode('', $orderData));

		if ($sign != $originSign) {
			$std->error = -3;

			return $std;
		}

		$std->orderId = $orderData[1];

		return $std;
	}

	public function ajax_getOrderById()
	{
		$this->needLoginJson($_SESSION['corp_id']);
		$pkCorp  = $_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'];
		$code = $this->input->post('code', true);

		$data    = new stdClass();
		if (empty($code)) {
			$data->error = -1;
			$data->msg   = '订单无效';
			$this->display($data);
		}
		$data = $this->Order_model->get_by_code($code);
		$data->pkCorp = $pkCorp;
		$this->display($data);
	}
	public function ajax_verifyLoose()
	{
		$this->needLoginJson($_SESSION['corp_id']);
		$code = $this->input->post('code', true);
		$corp_uid  = $_SESSION[$_SESSION['corp_id']]['userInfo']['corpUserId'];
		$owner_corp_id  = $_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'];
		$data    = new stdClass();
		if (empty($code) && empty($code) && empty($code)) {
			$data->error = -1;
			$data->msg   = '参数错误,请重新登录';
			$this->display($data);
		}
		$data = $this->Order_model->verifyLoose($code,  $corp_uid, $owner_corp_id);
		$this->display($data);
	}
	public function getItemInfo()
	{
		$this->needLoginJson($_SESSION['corp_id']);
		$item_id = $this->input->get('item_id', true);
		$corp_uid  = $_SESSION[$_SESSION['corp_id']]['userInfo']['userId'];
		$owner_corp_id  = $_SESSION[$_SESSION['corp_id']]['userInfo']['pkCorp'];
		$data   = new stdClass();
		$itemInfo = $this->Order_model->getItemInfo($item_id);
		if($itemInfo->error == 0){
			$cid=$itemInfo->result->fk_contest;
			$contestInfo = $this->Order_model->getContestInfo($cid);
		}
		$this->display($data);
	}

}
