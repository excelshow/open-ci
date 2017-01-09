<?php
require_once dirname(__DIR__) . '/ModelBase.php';

/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:53
 */
class Analysis_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getContestCountPerDay($fk_corp, $date)
	{
		$params = compact('fk_corp', 'date');

		$requests[] = $this->request('api_host_open_contest', 'analysis/get_contest_count_per_day.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function setOrderCount($fk_corp, $date, $cid, $item_id, $order_count, $amount_sum)
	{
		$params = compact('fk_corp', 'date', 'cid', 'item_id', 'order_count', 'amount_sum');

		$requests[] = $this->request('api_host_open_contest', 'analysis/set_order_count.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getAnalysisContestByUnqKey($fk_corp, $date)
	{
		$params = compact('fk_corp', 'date');

		$requests[] = $this->request('api_host_open_contest', 'analysis/get_analysis_contest_by_unq_key.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getAnalysisCursor($name)
	{
		$params = compact('name');

		$requests[] = $this->request('api_host_open_contest', 'analysis/get_analysis_cursor.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function setAnalysisCursor($name, $value)
	{
		$params = compact('name', 'value');

		$requests[] = $this->request('api_host_open_contest', 'analysis/set_analysis_cursor.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listOrderPerDay($fk_corp, $date, $page, $size)
	{
		$params = compact('fk_corp', 'date', 'page', 'size');

		$requests[] = $this->request('api_host_open_contest', 'analysis/list_order_per_day.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function listContestItemPerDay($fk_corp, $date, $page, $size)
	{
		$params = compact('fk_corp', 'date', 'page', 'size');

		$requests[] = $this->request('api_host_open_contest', 'analysis/list_contest_item_per_day.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function setContestItemCount($fk_corp, $date, $cid, $item_count)
	{
		$params = compact('fk_corp', 'date', 'cid', 'item_count');

		$requests[] = $this->request('api_host_open_contest', 'analysis/set_contest_item_count.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getOrderCalcPerDay($fk_corp, $date)
	{
		$params = compact('fk_corp', 'date');

		$requests[] = $this->request('api_host_open_contest', 'analysis/get_order_calc_per_day.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function getContestItemCalcPerDay($fk_corp, $date)
	{
		$params = compact('fk_corp', 'date');

		$requests[] = $this->request('api_host_open_contest', 'analysis/get_contest_item_calc_per_day.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function setCalcData($fk_corp, $date, $params)
	{
		$params['fk_corp'] = $fk_corp;
		$params['date']    = $date;

		$requests[] = $this->request('api_host_open_contest', 'analysis/set_calc_data.json', $params, 'POST');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}
}
