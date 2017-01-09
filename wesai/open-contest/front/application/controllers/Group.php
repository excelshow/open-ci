<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Group extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
			),
		);
	}

	public function detail()
	{
		$userInfo = $this->verifyLogin();
		$data = array();
		$group_id = $this->input->get('group_id', true);

		$groupInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', compact('group_id'), METHOD_GET);
		$groupInfo = $this->obj2array($groupInfo);
		if (empty($groupInfo['result'])) {
			$this->displayError('小组不存在', -1);
		}
		if ($groupInfo['result']['fk_user'] != $userInfo['uid']) {
			$this->displayError('没有权限', -2);
		}
		$data['groupInfo'] = $groupInfo['result'];

		if (!in_array($groupInfo['result']['state'], [CONTEST_GROUP_STATE_INIT, CONTEST_GROUP_STATE_CANCEL])) {
			$orderInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_by_group.json', compact('group_id'), METHOD_GET);
			$orderInfo = $this->obj2array($orderInfo);
			if (empty($orderInfo['result'])) {
				$this->displayError('订单获取失败', -3);
			}
			$data['orderInfo'] = $orderInfo['result'];
		}

		$cid = $groupInfo['result']['fk_contest'];
		//获取活动信息
		$contestInfo =$this->get_contest_info($cid, 0);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动不存在', -4);
		}
		$data['contestInfo'] = $contestInfo['result'];
		$params = array(
			'cid' => $cid,
			'type' => CONTEST_ITEM_TYPE_SINGLE,
		);

		$itemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/list.json', $params, METHOD_GET);
		$itemList = $this->obj2array($itemList);
		if (empty($itemList['data'])) {
			$this->displayError('项目不存在', -5);
		}
		$data['itemList'] = $itemList['data'];
		$data['itemList'] = array_column($data['itemList'], null, 'pk_contest_items');

		$params = array(
			'group_id' => $group_id,
			'uid'      => $userInfo['uid'],
		);
		$enrolDataList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/list_by_group.json', $params, METHOD_GET);
		$enrolDataList = $this->obj2array($enrolDataList);
		if (!empty($enrolDataList['data'])) {
			$data['enrolDataList'] = $enrolDataList['data'];
		}
		$data['locationData'] = $this->list_location($cid);
		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['group_detail_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$this->display($data);
	}

	public function create()
	{
		$this->verifyLogin();
		$data = array();
		$cid = $this->input->get('cid', true);
		$contestInfo=$this->get_contest_info($cid);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动不存在', -1);
		}
		$data['contestInfo'] = $contestInfo['result'];

		$type = CONTEST_ITEM_TYPE_SINGLE;
		$itemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/list.json', compact('cid','type'), METHOD_GET);
		$itemList = $this->obj2array($itemList);
		if (empty($itemList['data'])) {
			$this->displayError('项目不存在', -2);
		}
		$data['itemList'] = $itemList['data'];

		$data['locationData'] = $this->list_location($cid);

		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['group_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$this->display($data);
	}


	
	public function join()
	{
		$userInfo = $this->verifyLogin();
		$uid      = $userInfo['uid'];
		$data = array();
		$group_id = $this->input->get('group_id', true);
		if (empty($group_id)) {
			$this->displayError('参数不足', -1);
		}

		$groupInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', compact('group_id'), METHOD_GET);
		$groupInfo = $this->obj2array($groupInfo);
		if (empty($groupInfo['result'])) {
			$this->displayError('小组不存在', -2);
		}
		$data['groupInfo']=$groupInfo['result'];

		//获取项目信息
		$cid = $groupInfo['result']['fk_contest'];
		$contestInfo=$this->get_contest_info($cid);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动不存在', -4);
		}
		$data['contestInfo'] = $contestInfo['result'];

		$type = CONTEST_ITEM_TYPE_SINGLE;
		$itemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/list.json', compact('cid','type'), METHOD_GET);
		$itemList = $this->obj2array($itemList);
		if (empty($itemList['data'])) {
			$this->displayError('项目不存在', -3);
		}
		$data['itemList'] = $itemList['data'];

		$data['locationData'] = $this->list_location($cid);

		if($groupInfo['result']['fk_user'] == $uid){
			$data['submitOrder'] = true;
		}
		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['team_join_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$this->display($data);
	}

	public function ajax_create()
	{
		$userInfo = $this->verifyLogin();

		$uid            = $userInfo['uid'];
		$cid            = $this->input->post('cid', true);
		$name           = $this->input->post('name', true);
		$leader_name    = $this->input->post('leader_name', true);
		$leader_contact = $this->input->post('leader_contact', true);

		if (empty($uid) || empty($cid) || empty($name) || empty($leader_name) || empty($leader_contact)) {
			$std        = new stdClass();
			$std->error = -1;
			$std->info  = '参数不足';
			$this->display($std);
		}
		$params = compact('uid', 'cid', 'name', 'leader_name', 'leader_contact');
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/add.json', $params, METHOD_POST);
		$this->display($result);
	}

	public function ajax_get()
	{
		$userInfo = $this->verifyLogin();

		$group_id = $this->input->get('group_id', true);

		if (empty($group_id)) {
			$this->displayError('参数不足', -1);
		}

		$groupInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', compact('group_id'), METHOD_GET);
		$groupInfo = $this->obj2array($groupInfo);
		if (empty($groupInfo['result'])) {
			$this->displayError('团队信息异常', -2);
		}

		$groupInfo['is_owner'] = 0;
		if ($groupInfo['result']['fk_user'] == $userInfo['uid']) {
			$groupInfo['is_owner'] = 1;
		} else {
			$groupInfo['result']['leader_contact'] = '***********';
		}


		$this->display($groupInfo);
	}
	public function ajax_list_my()
	{	
		$this->verifyLogin();
		$userInfo = $this->verifyLogin();
		$data = array();
		$uid      = $userInfo['uid'];
		$cid = $this->input->get('cid', true);
		$item_id = $this->input->get('item_id', true);
		$type = $this->input->get('type', true);
		$page = $this->input->get('page', true);
		$size = $this->input->get('size', true);
		$state = $this->input->get('state', true);
		$contest_required = $this->input->get('contest_required');
		$params=compact('uid','cid','type','page','size','state','contest_required');
		$data = $this->get_my_group_list($params);
		$data->type = $type;
		$this->display($data);

	}
	private function get_my_group_list($params)
	{
		$data = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/list_by_uid.json',$params, METHOD_GET);
		return $data;
	}
}
