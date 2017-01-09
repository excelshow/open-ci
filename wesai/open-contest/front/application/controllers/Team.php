<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Team extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(),
		);
	}

	public function detail()
	{
		$userInfo = $this->verifyLogin();
		$data = array();
		$team_id = $this->input->get('team_id', true);

		$teamInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/get.json', compact('team_id'), METHOD_GET);
		$teamInfo = $this->obj2array($teamInfo);
		if (empty($teamInfo['result'])) {
			$this->displayError('团队不存在', -1);
		}
		if ($teamInfo['result']['fk_user'] != $userInfo['uid']) {
			$this->displayError('没有权限', -2);
		}
		$data['teamInfo'] = $teamInfo['result'];
		if (!in_array($teamInfo['result']['state'], [CONTEST_TEAM_STATE_INIT, CONTEST_TEAM_STATE_CANCEL])) {
			$orderInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_by_team.json', compact('team_id'), METHOD_GET);
			$orderInfo = $this->obj2array($orderInfo);
			if (!empty($orderInfo['result'])) {
				$data['orderInfo'] = $orderInfo['result'];
			}
		}

		$itemInfo = $this->get_item_info($teamInfo['result']['fk_contest_items']);
		$itemInfo = $this->obj2array($itemInfo);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目不存在', -4);
		}
		$data['itemInfo'] = $itemInfo['result'];

		$contestInfo = $this->get_contest_info($itemInfo['result']['fk_contest']);
		$contestInfo = $this->obj2array($contestInfo);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动不存在', -3);
		}
		$data['contestInfo'] = $contestInfo['result'];

		$enrolDataList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/list_by_team.json', compact('team_id'), METHOD_GET);
		$enrolDataList = $this->obj2array($enrolDataList);
		if (!empty($enrolDataList['data'])) {
			$data['enrolDataList'] = $enrolDataList['data'];
		}

		$data['locationData'] = $this->list_location($contestInfo['result']['pk_contest']);
		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['team_detail_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$this->display($data);
	}
	public function create()
	{
		$this->verifyLogin();
		$data = array();
		$cid = $this->input->get('cid', true);
		$item_id = $this->input->get('item_id', true);

		$contestInfo=$this->get_contest_info($cid);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动不存在', -1);
		}
		$data['contestInfo'] = $contestInfo['result'];

		$itemInfo=$this->get_item_info($item_id);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目不存在', -2);
		}
		$data['itemInfo'] = $itemInfo['result'];

		$data['locationData'] = $this->list_location($cid);
		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['team_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$this->display($data);
	}

	public function join()
	{
		$userInfo = $this->verifyLogin();
		$data = array();
		$uid      = $userInfo['uid'];
		$team_id = $this->input->get('team_id', true);
		if (empty($team_id)) {
			$this->displayError('参数不足', -1);
		}
		$teamInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/get.json', compact('team_id'), METHOD_GET);
		$teamInfo = $this->obj2array($teamInfo);
		if (empty($teamInfo['result'])) {
			$this->displayError('团队不存在', -2);
		}
		$data['teamInfo'] = $teamInfo['result'];

		$itemInfo=$this->get_item_info($teamInfo['result']['fk_contest_items']);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目不存在', -3);
		}
		$data['itemInfo'] = $itemInfo['result'];

		$contestInfo=$this->get_contest_info($itemInfo['result']['fk_contest']);
		if (empty($contestInfo['result'])) {
			$this->displayError('项目不存在', -3);
		}
		$data['contestInfo'] = $contestInfo['result'];

		if($teamInfo['result']['fk_user'] == $uid){
			$data['submitOrder'] = true;
		}
		$data['locationData'] = $this->list_location($itemInfo['result']['fk_contest']);
		global $TEMPLATE_LANG;
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['group_join_title'];
		$this->display($data);
	}

	
	public function ajax_create()
	{
		$userInfo = $this->verifyLogin();

		$uid            = $userInfo['uid'];
		$item_id        = $this->input->post('item_id', true);
		$name           = $this->input->post('name', true);
		$leader_name    = $this->input->post('leader_name', true);
		$leader_contact = $this->input->post('leader_contact', true);

		if (empty($uid) || empty($item_id) || empty($name) || empty($leader_name) || empty($leader_contact)) {
			$this->displayError('参数不足', -1);
		}

		$params = compact('uid', 'item_id', 'name', 'leader_name', 'leader_contact');
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/add.json', $params, METHOD_POST);

		$this->display($result);
	}

	

	public function ajax_list_my()
	{
		$userInfo = $this->verifyLogin();
		$uid = $userInfo['uid'];
		$item_id = $this->input->get('item_id', true);
		$type = $this->input->get('type', true);
		$state = $this->input->get('state', true);
		$page = $this->input->get('page', true);
		$size = $this->input->get('size', true);
		$contest_required = $this->input->get('contest_required');
		$params = compact('uid','item_id', 'type', 'page', 'size','state','contest_required');
		$data = $this->get_my_team_list($params);
		$data->type=$type;
		$this->display($data);
	}


	private function get_my_team_list($params)
	{
		$data = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/list_by_uid.json', $params, METHOD_GET);
		return $data;
	}
}
