<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Order_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	// public function listOrder($fk_corp, $cname, $state, $page, $size, $minDate, $maxDate)
	// {

	// 	$params = compact(
	// 		'fk_corp',
	// 		'cname',
	// 		'state',
	// 		'page',
	// 		'size'
	// 	);

	// 	if (!empty($minDate)) {
	// 		$params['start'] = $minDate;
	// 	}

	// 	if (!empty($maxDate)) {
	// 		$params['end'] = $maxDate;
	// 	}

	// 	$requests[] = $this->request('api_host_open_contest', 'order/search.json', $params, 'GET');

	// 	return $this->result($requests);
	// }

	public function getOrderByCode($code,$corp_id)
	{
		$params = compact('code','corp_id');
		
		$requests[] = $this->request('api_host_open_book', 'order/get_order_by_code.json', $params, 'GET');

		return $this->result($requests);
	}

	// public function ExportOrder($cname, $state)
	// {
	// 	$params     = compact('cname', 'state');
	// 	$requests[] = $this->request('api_host_open_contest', 'order/contest_order_export.json', $params, 'POST');

	// 	return $this->result($requests);
	// }

	// public function verifyRestrict($orderId, $itemId, $pkCorp, $userId)
	// {
	// 	$params = array(
	// 		'oid'     => $orderId,
	// 		'item_id' => $itemId,
	// 		'pk_corp' => $pkCorp,
	// 		'user_id' => $userId,
	// 	);

	// 	$requests[] = $this->request('api_host_open_contest', 'order/verify_restrict.json', $params, 'POST');

	// 	return $this->result($requests);
	// }

	public function verifyLoose($code, $corp_id, $user_id)
	{
		$params = array(
			'code'     => $code,
			'corp_id' => $corp_id,
			'user_id' => $user_id,

		);

		$requests[] = $this->request('api_host_open_book', 'order/verify.json', $params, 'POST');

		return $this->result($requests);
	}
}
