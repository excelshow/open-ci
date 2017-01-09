<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Contest_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function list_contest($fk_corp, $name = '', $gtype = 0, $range = 0, $page = 1, $size = 10)
	{
		$params     = compact('fk_corp', 'name', 'gtype', 'state', 'page', 'size');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contest/search.json', $params);

		return $this->result($requests);
	}

	public function get_contest($cid, $intro = 0)
	{
		$params     = compact('cid', 'intro');
		$requests[] = $this->request('api_host_open_contest', 'contest/get.json', $params);

		return $this->result($requests);
	}

	public function get_contestitems($cid, $page = 1, $size = 10)
	{
		$params     = compact('cid', 'page', "size");
		$requests[] = $this->request('api_host_open_contest', 'contestitem/list.json', $params);

		return $this->result($requests);
	}

	public function get_iteminfo($item_id)
	{
		$params     = compact('item_id');
		$requests[] = $this->request('api_host_open_contest', 'contestitem/get.json', $params);

		return $this->result($requests);
	}

	public function get_itemformbyitemid($item_id)
	{
		$params     = compact('item_id');
		$requests[] = $this->request('api_host_open_contest', 'form/get_by_itemid.json', $params);

		return $this->result($requests);
	}

	public function create_order(
		$fk_corp, $fk_comp_auth_app, $uid, $cid, $item_id, $amount, $ip, $info, $openid, $shipping_addr,$invite_code,
		$order_channel = ORDER_CHANNEL_WESAI, $order_source = ORDER_SOURCE_WEIXIN
	) {
		$params = compact('fk_corp', 'fk_comp_auth_app', 'uid', 'cid', 'item_id', 'amount', 'ip', 'info', 'shipping_addr', 'order_channel', 'order_source','invite_code');
		if ($order_source == ORDER_SOURCE_WEIXIN) {
			$params['channel_account'] = $openid;
		}
		$requests[] = $this->request('api_host_open_contest', 'order/add.json', $params, 'POST');

		return $this->result($requests);
	}

	public function get_order_by_id($oid, $contest_info = 0)
	{
		$requests[] = $this->request('api_host_open_contest', 'order/get.json', compact('oid', 'contest_info'), 'GET');

		return $this->result($requests);
	}

	public function get_myorderlist_by_cid($fk_corp, $fk_comp_auth_app, $cid, $uid, $state = null)
	{
		$params = compact("cid", "uid", 'fk_corp', 'fk_comp_auth_app');
		if (!empty($state)) {
			$params['state'] = $state;
		}
		$requests[] = $this->request('api_host_open_contest', 'order/list_by_uid.json', $params, 'GET');

		return $this->result($requests);
	}

	public function get_myorderlist($fk_corp, $fk_comp_auth_app, $uid, $page = 1, $size = 10, $contest_info = 0)
	{
		$params     = compact("fk_corp", "fk_comp_auth_app", "uid", "page", "size", 'contest_info');
		$requests[] = $this->request('api_host_open_contest', 'order/list_by_uid.json', $params, 'GET');

		return $this->result($requests);
	}

	public function getAlipayWapPaymentParams($oid, $channel_id)
	{
		$params     = compact("oid", "channel_id");
		$requests[] = $this->request('api_host_open_contest', 'order/get_payment_params.json', $params, 'GET');

		return $this->result($requests);
	}

	public function sendAlipayResult($params, $channel_id)
	{
		$params     = compact("params", "channel_id");
		$requests[] = $this->request('api_host_open_contest', 'order/pay_result.json', $params, 'POST');

		return $this->result($requests, false);
	}

	public function updateOrderStateToCompleted($oid, $paid_time, $channel_id, $transaction_id)
	{
		$params     = compact('oid', 'paid_time', 'channel_id', 'transaction_id');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'order/change_state_completed.json', $params, 'POST');

		return $this->result($requests);
	}
	public function list_form_item($formid){
		$fk_enrol_form = $formid;
		$params     = compact('fk_enrol_form');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'form/list_form_item.json', $params);
		return $this->result($requests);
	}
	public function getSellingItemCountByIds($cids){
		$params     = compact('cids');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/get_selling_count_by_cids.json', $params);
		return $this->result($requests);
	}

}
