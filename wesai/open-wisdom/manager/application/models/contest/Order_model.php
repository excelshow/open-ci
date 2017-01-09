<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Order_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function listOrder($fk_corp, $cname, $state, $page, $size, $minDate, $maxDate, $orderid = null)
	{

		$params = compact(
			'fk_corp',
			'state',
			'page',
			'size'
		);

		if (!empty($minDate)) {
			$params['start'] = $minDate;
		}

		if (!empty($maxDate)) {
			$params['end'] = $maxDate;
		}

		if (!empty($cname)) {
			$params['cname'] = $cname;
		}

		if (!empty($orderid)) {
			$params['orderid'] = $orderid;
		}

		$requests[] = $this->request('api_host_open_contest', 'order/search.json', $params, 'GET');

		return $this->result($requests);
	}

	public function get_by_code($code)
	{
		$params = compact('code');
		$requests[] = $this->request('api_host_open_contest', 'enroldata/get_by_code.json', $params, 'GET');

		return $this->result($requests);
	}

	public function ExportOrder($cname, $state)
	{
		$params     = compact('cname', 'state');
		$requests[] = $this->request('api_host_open_contest', 'order/contest_order_export.json', $params, 'POST');

		return $this->result($requests);
	}

	public function verifyRestrict($orderId, $itemId, $pkCorp, $userId)
	{
		$params = array(
			'oid'     => $orderId,
			'item_id' => $itemId,
			'pk_corp' => $pkCorp,
			'user_id' => $userId,
		);

		$requests[] = $this->request('api_host_open_contest', 'enroldata/verify.json', $params, 'POST');

		return $this->result($requests);
	}

	public function verifyLoose($code, $corp_uid, $owner_corp_id)
	{
		$params = array(
			'code'     => $code,
			'corp_uid' => $corp_uid,
			'owner_corp_id' => $owner_corp_id,

		);
		$requests[] = $this->request('api_host_open_contest', 'enroldata/verify.json', $params, 'POST');
		return $this->result($requests);
	}
	public function getContestInfo($cid)
	{
		$params = array(
			'cid'     => $cid,
		);
		$requests[] = $this->request('api_host_open_contest', 'contest/get.json', $params, 'get');
		return $this->result($requests);
	}
	public function getItemInfo($item_id)
	{
		$params = array(
			'item_id'     => $item_id,
		);
		$requests[] = $this->request('api_host_open_contest', 'contestitem/get.json', $params, 'get');
		return $this->result($requests);
	}
}
