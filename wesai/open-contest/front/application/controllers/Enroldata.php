<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Enroldata extends Base
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

	public function delete()
	{
		$userInfo = $this->verifyLogin();
		$data = array();
		$uid         = $userInfo['uid'];
		$enrol_data_id     = $this->input->post('enrol_data_id', true);
		$params = compact('uid', 'enrol_data_id');
		$data = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/delete.json', $params, METHOD_POST);
		$this->display($data);
	}
	public function detail()
	{
		$this->verifyLogin();

		$data = array();
		$this->display($data);
	}

	public function create()
	{
		$this->verifyLogin();
		$data = array();
		$this->display($data);
	}

	public function ajax_create()
	{
		$userInfo = $this->verifyLogin();

		$uid         = $userInfo['uid'];
		$item_id     = $this->input->post('item_id', true);
		$team_id     = $this->input->post('team_id', true);
		$group_id    = $this->input->post('group_id', true);
		$invite_code = $this->input->post('invite_code', true);
		$info        = json_encode($this->input->post('info', true));

		if (empty($item_id)) {
			$this->displayError('请选择项目', -1);
		}

		$itemInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', compact('item_id'), METHOD_GET);
		$itemInfo = $this->obj2array($itemInfo);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目异常，报名失败', -2);
		}

		$params = compact('uid', 'item_id', 'info');

		if ($itemInfo['result']['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES) {
			if (empty($invite_code)) {
				$this->displayError('请填写邀请码', -3);
			}
			$params['invite_code'] = $invite_code;
		}

		$type = ENROL_DATA_TYPE_SINGLE;
		switch ($itemInfo['result']['type']) {
			case CONTEST_ITEM_TYPE_SINGLE:
				if (!empty($group_id)) {
					$type               = ENROL_DATA_TYPE_GROUP;
					$params['group_id'] = $group_id;
				}
				break;
			case CONTEST_ITEM_TYPE_TEAM:
				if (empty($team_id)) {
					$this->displayError('参数错误', -4);
				}
				$type              = ENROL_DATA_TYPE_TEAM;
				$params['team_id'] = $team_id;
				break;
		}

		$params['type'] = $type;

		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/add.json', $params, METHOD_POST);
		$result = $this->obj2array($result);
		$result['type'] = $type;
		$this->display($result);
	}

	public function ajax_list_by_group()
	{
		$std      = new stdClass();
		$userInfo = $this->verifyLogin();

		$groupId = $this->input->get('group_id', true);
		$page    = $this->input->get('page', true);
		$size    = $this->input->get('size', true);

		if (empty($groupId)) {
			$std->error = 0;
			$std->code  = -1;
			$std->data  = array();
			$this->display($std);
		}

		$params    = array('group_id' => $groupId);
		$groupInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', $params, METHOD_GET);
		$groupInfo = $this->obj2array($groupInfo);
		if (empty($groupInfo['result'])) {
			$std->error = 0;
			$std->code  = -2;
			$std->data  = array();
			$this->display($std);
		}
		$groupInfo = $groupInfo['result'];

		if ($groupInfo['fk_user'] != $userInfo['uid']) {
			$params['uid'] = $userInfo['uid'];
		}

		$params['page'] = $page;
		$params['size'] = $size;

		$enrolDataList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/list_by_group.json', $params, METHOD_GET);
		$enrolDataList = $this->obj2array($enrolDataList);
		if (empty($enrolDataList['data'])) {
			$std->error = 0;
			$std->code  = -3;
			$std->data  = array();

			$this->display($std);
		}

		$item_ids = array_column($enrolDataList['data'], 'fk_contest_items');
		$item_ids = array_flip(array_flip($item_ids));
		$item_ids = implode(',', $item_ids);

		$contestItemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get_by_ids.json', compact('item_ids'), METHOD_GET);
		$contestItemList = $this->obj2array($contestItemList);
		$item_ids = explode(',', $item_ids);
		if (empty($contestItemList['data']) || count($contestItemList['data']) != count($item_ids)) {
			$std->error = 0;
			$std->code  = -4;
			$std->data  = array();
			$this->display($std);
		}

		$contestItemList = $contestItemList['data'];

		$contestItemList = array_column($contestItemList, null, 'pk_contest_items');

		foreach ($enrolDataList['data'] as $k => $enrolData) {
			$enrolDataList['data'][$k]['contest_item_name'] = $contestItemList[$enrolData['fk_contest_items']]['name'];
			$enrolDataList['data'][$k]['enrol_data_detail'] = array_slice($enrolDataList['data'][$k]['enrol_data_detail'], 0, 2);
		}

		$this->display($enrolDataList);
	}

	public function ajax_list_by_team()
	{
		$std      = new stdClass();
		$userInfo = $this->verifyLogin();

		$teamId = $this->input->get('team_id', true);
		$page   = $this->input->get('page', true);
		$size   = $this->input->get('size', true);

		if (empty($teamId)) {
			$std->error = 0;
			$std->code  = -1;
			$std->data  = array();
			$this->display($std);
		}

		$params   = array('team_id' => $teamId);
		$teamInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/get.json', $params, METHOD_GET);
		$teamInfo = $this->obj2array($teamInfo);
		if (empty($teamInfo['result'])) {
			$std->error = 0;
			$std->code  = -2;
			$std->data  = array();
			$this->display($std);
		}
		$teamInfo = $teamInfo['result'];

		if ($teamInfo['fk_user'] != $userInfo['uid']) {
			$params['uid'] = $userInfo['uid'];
		}

		$params['page'] = $page;
		$params['size'] = $size;

		$enrolDataList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/list_by_team.json', $params, METHOD_GET);
		$enrolDataList = $this->obj2array($enrolDataList);
		if (empty($enrolDataList['data'])) {
			$std->error = 0;
			$std->code  = -3;
			$std->data  = array();

			$this->display($std);
		}

		$item_ids = array_column($enrolDataList['data'], 'fk_contest_items');
		$item_ids = array_flip(array_flip($item_ids));
		$item_ids = implode(',', $item_ids);

		$contestItemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get_by_ids.json', compact('item_ids'), METHOD_GET);
		$contestItemList = $this->obj2array($contestItemList);
		$item_ids = explode(',', $item_ids);
		if (empty($contestItemList['data']) || count($contestItemList['data']) != count($item_ids)) {
			$std->error = 0;
			$std->code  = -4;
			$std->data  = array();
			$this->display($std);
		}

		$contestItemList = $contestItemList['data'];

		$contestItemList = array_column($contestItemList, null, 'pk_contest_items');

		foreach ($enrolDataList['data'] as $k => $enrolData) {
			$enrolDataList['data'][$k]['contest_item_name'] = $contestItemList[$enrolData['fk_contest_items']]['name'];
			$enrolDataList['data'][$k]['enrol_data_detail'] = array_slice($enrolDataList['data'][$k]['enrol_data_detail'], 0, 2);
		}

		$this->display($enrolDataList);
	}

	public function ajax_get()
	{
		$std = new stdClass();

		$userInfo = $this->verifyLogin();

		$enrol_data_id = $this->input->get('enrol_data_id', true);
		if (empty($enrol_data_id)) {
			$std->error = -1;
			$std->info  = '参数错误';
			$this->display($std);
		}

		$params = compact('enrol_data_id');

		$enrolDataInfoObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'enroldata/get.json', $params, METHOD_GET);
		$enrolDataInfo    = $this->obj2array($enrolDataInfoObj);
		if (empty($enrolDataInfo['result'])) {
			$std->error = -2;
			$std->info  = '报名资料不存在';
			$this->display($std);
		}
		$enrolDataInfo = $enrolDataInfo['result'];

		switch ($enrolDataInfo['type']) {
			case ENROL_DATA_TYPE_SINGLE:
				if ($enrolDataInfo['fk_user'] != $userInfo['uid']) {
					$std->error = -3;
					$std->info  = '无权查看';
					$this->display($std);
				}
				break;
			case ENROL_DATA_TYPE_GROUP:
				$params    = array(
					'group_id' => $enrolDataInfo['fk_group'],
				);
				$groupInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', $params, METHOD_GET);
				$groupInfo = $this->obj2array($groupInfo);
				if (empty($groupInfo['result'])) {
					$std->error = -4;
					$std->info  = '小组信息异常';
					$this->display($std);
				}
				$groupInfo = $groupInfo['result'];

				if ($groupInfo['fk_user'] != $userInfo['uid'] &&
				    $enrolDataInfo['fk_user'] != $userInfo['uid']
				) {
					$std->error = -5;
					$std->info  = '无权查看';
					$this->display($std);
				}
				break;
			case ENROL_DATA_TYPE_TEAM:
				$params   = array(
					'team_id' => $enrolDataInfo['fk_team'],
				);
				$teamInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'team/get.json', $params, METHOD_GET);
				$teamInfo = $this->obj2array($teamInfo);
				if (empty($teamInfo['result'])) {
					$std->error = -6;
					$std->info  = '团队信息异常';
					$this->display($std);
				}
				$teamInfo = $teamInfo['result'];

				if ($teamInfo['fk_user'] != $userInfo['uid'] &&
				    $enrolDataInfo['fk_user'] != $userInfo['uid']
				) {
					$std->error = -7;
					$std->info  = '无权查看';
					$this->display($std);
				}
				break;
			default:
				$std->error = -99;
				$std->info  = '无权查看';
				$this->display($std);
				break;
		}

		$params          = array(
			'item_id' => $enrolDataInfo['fk_contest_items'],
		);
		$contestItemInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', $params, METHOD_GET);
		$contestItemInfo = $this->obj2array($contestItemInfo);
		if (empty($contestItemInfo['result'])) {
			$std->error = -8;
			$std->info  = '信息异常';
			$this->display($std);
		}
		$enrolDataInfoObj->result->contest_item_info = $contestItemInfo['result'];

		$params      = array(
			'cid' => $contestItemInfo['result']['fk_contest'],
		);
		$contestInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		$contestInfo = $this->obj2array($contestInfo);
		if (empty($contestInfo['result'])) {
			$std->error = -9;
			$std->info  = '信息异常';
			$this->display($std);
		}
		$enrolDataInfoObj->result->contest_info = $contestInfo['result'];

		$this->display($enrolDataInfoObj);
	}
}
