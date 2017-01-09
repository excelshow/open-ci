<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 18:13
 */
require_once __DIR__ . '/Base.php';

class Group extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add_post()
	{
		$this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('leader_name', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('leader_contact', PARAM_NOT_NULL_NOT_EMPTY);

		$params               = $this->get_request_params();
		$params['fk_user']    = $params['uid'];
		$params['fk_contest'] = $params['cid'];
		unset($params['uid'], $params['cid']);

		$lastId = $this->Group_model->create($params);

		return $this->response_insert($lastId);
	}

	public function update_post()
	{
		$this->post_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('name', PARAM_NULL_NOT_EMPTY);
		$this->post_check('leader_name', PARAM_NULL_NOT_EMPTY);
		$this->post_check('leader_contact', PARAM_NULL_NOT_EMPTY);

		$params  = $this->get_request_params();
		$uid     = $params['uid'];
		$groupId = $params['group_id'];
		unset($params['group_id'], $params['uid']);

		$groupInfo = $this->verifyGroupExists($groupId);

		$this->verifyGroupState($groupInfo['state'], CONTEST_GROUP_STATE_INIT);

		if ($groupInfo['fk_user'] != $uid) {
			return $this->response_error(Error_Code::ERROR_GROUP_EDIT_NO_AUTH);
		}

		$affectedRows = $this->Group_model->modify($groupId, $params);

		return $this->response_update($affectedRows);
	}

	public function get_get()
	{
		$groupId = $this->get_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$groupInfo = $this->verifyGroupExists($groupId);

		return $this->response_object($groupInfo);
	}

	public function change_state_to_cancel_post()
	{
		$groupId = $this->post_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$uid     = $this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$groupInfo = $this->verifyGroupExists($groupId);

		$this->verifyGroupState($groupInfo['state'], CONTEST_GROUP_STATE_INIT);

		if ($groupInfo['fk_user'] != $uid) {
			return $this->response_error(Error_Code::ERROR_GROUP_EDIT_NO_AUTH);
		}

		$affectedRows = $this->Group_model->changeState($groupId, CONTEST_GROUP_STATE_INIT, CONTEST_GROUP_STATE_CANCEL);

		return $this->response_update($affectedRows);
	}

	public function list_members_get()
	{
		$groupId    = $this->get_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber = $this->get_check('page', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$total      = 0;
		$memberList = $this->Group_model->listMemberByPage($groupId, $pageNumber, $pageSize, $total);

		return $this->response_list($memberList, $total, $pageNumber, $pageSize);
	}

	public function list_by_uid_get()
	{
		$uid             = $this->get_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$type            = $this->get_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$cid             = $this->get_check('cid', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$contestRequired = $this->get_check('contest_required', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber      = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageSize        = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$total     = 0;
		$groupList = array();

		switch ($type) {
			case 1: // I created
				$groupList = $this->Group_model->listByUid($uid, $cid, $pageNumber, $pageSize, $total);

				$groupList = array_column($groupList, null, 'pk_group');
				break;
			case 2: // I joined
				$mappingGroupUser = $this->Group_model->listMappingUserGroupsByPage($uid, $pageNumber, $pageSize, $total);

				if (empty($mappingGroupUser)) {
					return $this->response_list(array(), 0, $pageNumber, $pageSize);
				}

				$groupIds = array_column($mappingGroupUser, 'fk_group');

				$groups = $this->Group_model->getByIds($groupIds);

				if (empty($groups)) {
					return $this->response_list(array(), 0, $pageNumber, $pageSize);
				}

				$groups = array_column($groups, null, 'pk_group');

				$groupList = array();
				foreach ($mappingGroupUser as $val) {
					if (empty($groups[$val['fk_group']])) {
						continue;
					}
					$groupList[] = $groups[$val['fk_group']];
				}
				break;
		}

		if ($contestRequired) {
			$cids = array_column($groupList, 'fk_contest');
			$cids = array_flip(array_flip($cids));
			if (empty($cids)) {
				$groupList = array_merge(array(), $groupList);
				return $this->response_list($groupList, $total, $pageNumber, $pageSize);
			}
			$contestList = $this->Contest_model->getByIds($cids);
			if (empty($contestList)) {
				$groupList = array_merge(array(), $groupList);
				return $this->response_list($groupList, $total, $pageNumber, $pageSize);
			}
			$contestList = array_column($contestList, null, 'pk_contest');

			foreach ($groupList as $key => $val) {
				$groupList[$key]['contest_info'] = $contestList[$val['fk_contest']];
			}
		}

		$groupList = array_merge(array(), $groupList);

		return $this->response_list($groupList, $total, $pageNumber, $pageSize);
	}

}

