<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Order extends Base
{
	public function __construct()
	{
		parent::__construct();
	}


	public function getAllowedApiList()
	{
		return array();
	}

	private function encryptOrderCodeData($verify_code)
	{
		//当前时间戳
		$cur_timestamp = time();
		//带签名数据
		$toBeSignData = array($cur_timestamp, $verify_code, ORDER_ENCRYPT_KEY);
		$toBeSignData = implode('', $toBeSignData);
		//数据签名
		$sign = md5($toBeSignData);
		//二维码数据
		$qrcodeData = base64_encode($cur_timestamp . '|' . $verify_code . '|' . $sign);

		return $qrcodeData;
	}


	public function setPageVars()
	{
		return array(
			'CDN_URL' => 'http://' . _UPLOAD_RES_CDN_DOMAIN_,
		);
	}

	public function index()
	{
		$this->verifyLogin();
		$data  =  array();
		$title = "订单详情";
		$data['title'] = $title;
		$this->display($data);

	}


	/**
	 *  订单详情
	 */
	public function detail()
	{
		$userInfo = $this->verifyLogin();
		$oid          = $this->input->get('oid');
		$contest_info = 1;
		$params       = compact('oid', 'contest_info');
		$orderInfo  = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get.json', $params, METHOD_GET);
		if (empty($orderInfo->result)) {
			exit('获取订单失败');
		}
		if ($userInfo['uid'] != $orderInfo->result->fk_user) {
			exit('对不起！这笔订单不属于您，不能查看该订单。');
		}

		$orderInfo->result->amount_discount = 0;
		if (!empty($orderInfo->result->contest_item_list)) {
			foreach ($orderInfo->result->contest_item_list as $key => $value) {
				foreach ($value->enrol_data as $key1 => $v) {
					foreach ($v->verify_code as $key2 => $w) {
						$w->verify_code_sigin = $this->encryptOrderCodeData($w->code);
					}
				}
			}
		}

		$operation_info = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_operation_by_out_trade_nos.json', array('out_trade_nos' => $orderInfo->result->out_trade_no, 'service' => ORDER_PAY_SERVICE), METHOD_GET);
		if (!empty($operation_info->data)) {
			foreach ($operation_info->data as $operation) {
				$orderInfo->result->amount_discount += $operation->value;
			}
		}
		$title = "订单详情";
		$orderInfo->title  = $title;
		$orderInfo->locationData = $this->list_location($orderInfo->result->fk_contest);
		$this->display($orderInfo);
	}

	public function list_by_uid()
	{
		$userInfo       = $this->verifyLogin();
		$uid            = $userInfo['uid'];

		$seller_corp_id = $userInfo['corp_id'];
		$seller_app_id  = $userInfo['apppk'];
		$state          = $this->input->get('state', true);
		$contest_info   = $this->input->get('contest_info', true);
		$page           = $this->input->get('page', true);
		$size           = $this->input->get('size', true);


		$params         = compact('seller_corp_id', 'seller_app_id', 'uid', 'state', 'contest_info', 'page', 'size');
		$data           = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/list_by_uid.json', $params, METHOD_GET);

		if(!empty($data->data)){
		 	foreach ($data->data as $key => $value) {
				$value->amount_pay = $value->amount;
				$data->data[$key]->contest_info->locationData = $this->list_location($value->fk_contest);
			};

			$out_trade_nos = array_column($this->obj2array($data->data), 'out_trade_no');
			$operations = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_operation_by_out_trade_nos.json', array('out_trade_nos' => implode(',', $out_trade_nos), 'service' => ORDER_PAY_SERVICE), METHOD_GET);
			if (!empty($operations->data)) {
				foreach ($data->data as $order) {
					$order->amount_discount = 0;
					foreach ($operations->data as $operation) {
						if ($operation->out_trade_no == ORDER_PAY_SERVICE . $order->out_trade_no) {
							$order->amount_discount += $operation->value;
						}
					}
					$order->amount_pay = $order->amount - $order->amount_discount;
				}
			}
		};
		$data->page_vars = $this->setPageVars();

		$this->display($data);

	}



	public function ajax_create()
	{
		$userInfo = $this->verifyLogin();

		$uid            = $userInfo['uid'];
		$seller_corp_id = $userInfo['corp_id'];
		$seller_app_id  = $userInfo['apppk'];
		$openid         = $userInfo['openid'];
		$team_id       = $this->input->post('team_id', true);
		$group_id      = $this->input->post('group_id', true);
		$enrol_data_id = $this->input->post('enrol_data_id', true);
		$copies = $this->input->post('copies');
		if(empty($team_id) and empty($group_id) and empty($enrol_data_id)){
			$this->displayError('参数错误', -1);
		}

		$params = compact('seller_corp_id', 'seller_app_id', 'uid','openid','copies');
		$type = null;
		if (!empty($enrol_data_id)) {
			$type                    = ORDER_TYPE_SINGLE;
			$params['enrol_data_id'] = $enrol_data_id;
		} else if (!empty($group_id)) {
			$type               = ORDER_TYPE_GROUP;
			$params['group_id'] = $group_id;
		} else if (!empty($team_id)) {
			$type              = ORDER_TYPE_TEAM;
			$params['team_id'] = $team_id;
		}
		$params['type'] = $type;
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/add.json', $params, METHOD_POST);
		$result = $this->obj2array($result);
		if (empty($result['result'])) {
			$this->display($result);
		}

		$result['order_pay_url'] = $this->getOrderPayUrl($result['result']['out_trade_no']);

		$this->display($result);
	}

 	private function getOrderPayUrl($out_trade_no) {
 		$this->load->helper('common');
		$this->load->helper('signature');
		$payParams = array(
			'service'  => 101,
			'id'       => $out_trade_no,
			'source'   => 1,
			'nonce'    => create_rand_str(16),
			'redirect' => urlencode('http://' . $_SERVER['HTTP_HOST'] . '/order/pay_result?out_trade_no=' . $out_trade_no),
			'time'     => time(),
		);
		$payParams['sign'] = create_signature($payParams, 'md5', PAY_SITE_SIGN_TOKEN);

		return 'http://' . PAY_SITE_DOMAIN . '/order?' . http_build_query($payParams);
 	}

	public function ajax_get_order_pay_url()
	{
		$std        = new stdClass();
		$std->error = 0;
		$oid = intval($this->input->post('oid', true));
		if (empty($oid)) {
			$std->error = -1;
			$std->msg   = '参数错误';
			$this->display($std);
		}
		$orderInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get.json', compact('oid'), METHOD_GET);

		$orderInfo = $this->obj2array($orderInfo);
		if (empty($orderInfo['result'])) {
			$std->error = -2;
			$std->msg   = '订单有误';
			$this->display($std);
		}

		if (time() - strtotime($orderInfo['result']['ctime']) > ORDER_TIME_EXPIRE * 60) {
			$std->error = -3;
			$this->display($std);
		}

		$std->order_pay_url = $this->getOrderPayUrl($orderInfo['result']['out_trade_no']);
		$this->display($std);
	}
	public function ajax_get_order_by_id()
	{
		$userInfo = $this->verifyLogin();

		$std        = new stdClass();
		$std->error = 0;

		$oid = $this->input->get('oid', true);
		$uid = $userInfo['uid'];

		$contest_info = 0;
		$params       = compact('oid', 'contest_info');
		$order_info  = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get.json', $params, METHOD_GET);
		if (empty($order_info) || $order_info->error != 0 || empty($order_info->result)) {
			$std->error = -1;
			$this->display($std);
		}
		if ($order_info->result->fk_user != $uid) {
			$std->error = -2;
			$this->display($std);
		}

		$std->orderState = $order_info->result->state;
		$this->display($std);
	}

	public function pay_result()
	{
		$outTradeNo = $this->input->get('out_trade_no', true);
		if (empty($outTradeNo)) {
			$this->displayError('error params');
		}

		$oid = intval(substr($outTradeNo, -10));

		$contest_info = 0;
		$params       = compact('oid', 'contest_info');
		$orderInfo  = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get.json', $params, METHOD_GET);

		if (empty($orderInfo) || empty($orderInfo->result)) {
			$this->displayError('order not exists');
		}

		if (!in_array($orderInfo->result->state, [ORDER_STATE_INIT, ORDER_STATE_PAYING])) {
			header('Location: /order/detail?oid=' . $oid);
			exit();
		}

		$service = ORDER_PAY_SERVICE;
		$out_trade_no = $outTradeNo;
		$params     = compact('service', 'out_trade_no');
		$orderPayResult = $this->callInnerApiDiy(API_HOST_OPEN_PAY, 'order/get_by_service.json', $params, METHOD_GET);

		if (!empty($orderPayResult) && !empty($orderPayResult->result) && $orderPayResult->result->state == ORDER_PAY_SYS_ORDER_STATE_SUCCESS) {
			$paid_time = $orderPayResult->result->paid_time;
			$channel_id = $orderPayResult->result->channel_id;
			$transaction_id = $orderPayResult->result->channel_info->channel_transaction_id;
			$amount_pay = $orderPayResult->result->amount_pay;
			$paramsState  = compact('oid', 'paid_time', 'channel_id', 'transaction_id', 'amount_pay');
			$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/change_state_to_completed.json', $paramsState, METHOD_POST);
			if (!empty($result) && $result->error == 0) {
				header('Location: /order/detail?oid=' . $oid);
				exit();
			}
		}
		$data = compact('oid', 'outTradeNo');

		$this->display($data);
	}

}
