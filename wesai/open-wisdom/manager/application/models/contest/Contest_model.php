<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Contest_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function addContest($params)
	{

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contest/add.json', $params, "POST");

		return $this->result($requests);
	}

	public function getContestById($cid, $intro = 0)
	{
		$params     = compact('cid', 'intro');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contest/get.json', $params);

		return $this->result($requests);
	}

	public function updateContest($params)
	{

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contest/update.json', $params, "POST");

		return $this->result($requests);
	}

	public function listContest($fk_corp, $name, $gtype, $state, $page = 1, $size = 10, $min_date = 0, $max_date = 0)
	{
		$params     = compact('fk_corp', 'name', 'gtype', 'state', 'page', 'size', 'min_date', 'max_date');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contest/search_manage.json', $params);

		return $this->result($requests);
	}

	//change state
	public function changeContestPublishState($cid, $act)
	{
		$params = compact('cid');
		global $CONTEST_CHANGESTATE_LIST;
		$change_state_url = $CONTEST_CHANGESTATE_LIST;
		$apiurl           = $change_state_url[$act]['apiurl'];
		$requests         = array();
		$requests[]       = $this->request('api_host_open_contest', $apiurl, $params, "POST");

		return $this->result($requests);
	}

	public function addContestUnits($params)
	{

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'tag/add_unit.json', $params, "POST");

		return $this->result($requests);
	}

	public function listTagUnits($cid, $page = 1, $size = 10)
	{
		$params     = compact('cid', 'page', 'size');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'tag/list_unit.json', $params);

		return $this->result($requests);
	}

	public function addContestItem($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/add.json', $params, "POST");

		return $this->result($requests);
	}

	public function deleteContestItem($params)
	{
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/delete.json', $params, "POST");

		return $this->result($requests);
	}

	public function updateContestItem($params)
	{

		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/update.json', $params, "POST");

		return $this->result($requests);
	}

	public function getContestItemById($item_id)
	{
		$params     = compact("item_id");
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/get.json', $params, "GET");

		return $this->result($requests);
	}

	public function listContestItems($cid, $page = 1, $size = 10, $form = 'no')
	{
		$params     = compact('cid', 'page', 'size');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'contestitem/list.json', $params);
		//同时返回报名表单
		if ($form == "yes") {
			$result  = $this->result($requests);
			$newdata = array();
			if (!empty($result->data)) {
				foreach ($result->data as $key => $val) {
					$item_id = $val->pk_contest_items;
					//获取表单
					$requests2   = array();
					$requests2[] = $this->request('api_host_open_contest', 'form/get_by_itemid.json', compact("item_id"));
					$result2     = $this->result($requests2);
					$itemform    = array();

					if ($result2->error == 0) {
						$itemform = $result2->result;
					}
					$val->forminfo = $itemform;
					$newdata[]     = $val;
				}
			}
			$result->data = $newdata;

			return $result;
		} else {
			return $this->result($requests);
		}
	}

	public function addContestLocation($params)
	{

		$requests[] = $this->request('api_host_open_contest', 'tag/add_location.json', $params, "POST");

		return $this->result($requests);
	}

	public function listContestLocations($cid)
	{
		$params     = compact('cid');
		$requests   = array();
		$requests[] = $this->request('api_host_open_contest', 'tag/list_location.json', $params);

		return $this->result($requests);
	}

	public function listInviteCode($itemId, $pageNumber, $pageSize)
	{
		$params = array(
			'item_id' => $itemId,
			'page'    => $pageNumber,
			'size'    => $pageSize,
		);

		$requests[] = $this->request('api_host_open_contest', 'invitecode/list.json', $params, "GET");

		return $this->result($requests, false);
	}

	public function listVerifyingItems($fkCorp, $date, $pageNumber, $pageSize)
	{
		$params = array(
			'fk_corp' => $fkCorp,
			'date'    => $date,
			'page'    => $pageNumber,
			'size'    => $pageSize,
		);

		$requests[] = $this->request('api_host_open_contest', 'contest/list_verifying_items.json', $params, "GET");

		return $this->result($requests);
	}
}
