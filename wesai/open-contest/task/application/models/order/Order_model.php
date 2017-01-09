<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午11:25
 */

require_once __DIR__ . '/../ModelBase.php';

class Order_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getById($oid)
	{
		$params     = compact('oid');
		$requests[] = $this->request('api_host_open_contest', 'order/get.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function changeStateToPaying($oid)
	{
		$params     = compact('oid');
		$requests[] = $this->request('api_host_open_contest', 'order/change_state_paying.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function changeStateToFailed($oid)
	{
		$params     = compact('oid');
		$requests[] = $this->request('api_host_open_contest', 'order/change_state_failed.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function changeStateToCompleted($oid, $paid_time, $channel_id, $transaction_id)
	{
		$params     = compact('oid', 'paid_time', 'channel_id', 'transaction_id');
		$requests[] = $this->request('api_host_open_contest', 'order/change_state_completed.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function changeStateToRefunding($oid)
	{
		$params     = compact('oid');
		$requests[] = $this->request('api_host_open_contest', 'order/change_state_refunding.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);

	}

	public function getSpecifiedEnrolInfo($oid, $key)
	{
		$params     = compact('oid', 'key');
		$requests[] = $this->request('api_host_open_contest', 'order/get_specified_enrol_info.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateOrderStateTrack($oid, $state)
	{
		$params     = compact('oid', 'state');
		$requests[] = $this->request('api_host_open_contest', 'order/update_order_state_track.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listEnrolInfo($oid)
	{
		$params = compact('oid');

		$requests[] = $this->request('api_host_open_contest', 'order/list_enrol_info.json', $params, 'GET');

		$result = $this->result($requests, true, 1, 1000);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function updateEnrolInfo($pk_enrol_info, $value)
	{
		$params = compact('pk_enrol_info', 'value');

		$requests[] = $this->request('api_host_open_contest', 'order/update_enrol_info.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listRegSuccessOrder($cid, $page, $size, $state, $lottery_state)
	{
		$params = compact('cid', 'page', 'size');
		if (!empty($state)) {
			$params['state'] = $state;
		}

		if (!empty($lottery_state)) {
			$params['lottery_state'] = $lottery_state;
		}

		$requests[] = $this->request('api_host_open_contest', 'order/list_lottery_success_order.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listOrder($page, $size)
	{
		$params = compact('page', 'size');

		$requests[] = $this->request('api_host_open_contest', 'order/list.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listOrderExpired($page, $size)
	{
		$params = compact('page', 'size');

		$requests[] = $this->request('api_host_open_contest', 'order/list_order_expired.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function closeOrder($oid)
	{
		$params = compact('oid');

		$requests[] = $this->request('api_host_open_contest', 'order/change_state_closed.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getOrderPayResult($service, $out_trade_no)
	{
		$params = compact('service', 'out_trade_no');

		$requests[] = $this->request('api_host_open_pay', 'order/get_by_service.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
