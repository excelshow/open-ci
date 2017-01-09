<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 18:13
 */
require_once __DIR__ . '/Base.php';

class Team extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add_post()
	{
		$this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('leader_name', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('leader_contact', PARAM_NOT_NULL_NOT_EMPTY);

		$params = $this->get_request_params();

		$itemInfo = $this->verifyContestItemExists($params['item_id']);

		$this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

		if ($itemInfo['type'] != CONTEST_ITEM_TYPE_TEAM) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_TYPE_INVALID, '非团队项目，不能创建团队');
		}

		$params['fk_user']          = $params['uid'];
		$params['fk_contest_items'] = $params['item_id'];
		$params['max_member_count'] = $itemInfo['team_size'];
		unset($params['uid'], $params['item_id']);

		$lastId = $this->Team_model->create($params);

		return $this->response_insert($lastId);
	}

	public function update_post()
	{
		$this->post_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('name', PARAM_NULL_NOT_EMPTY);
		$this->post_check('leader_name', PARAM_NULL_NOT_EMPTY);
		$this->post_check('leader_contact', PARAM_NULL_NOT_EMPTY);

		$params = $this->get_request_params();

		$uid    = $params['uid'];
		$teamId = $params['team_id'];
		unset($params['team_id'], $params['uid']);

		$teamInfo = $this->verifyTeamExists($teamId);

		$this->verifyTeamState($teamInfo['state'], CONTEST_TEAM_STATE_INIT);

		if ($teamInfo['fk_user'] != $uid) {
			return $this->response_error(Error_Code::ERROR_TEAM_EDIT_NO_AUTH);
		}

		$affectedRows = $this->Team_model->modify($teamId, $params);

		return $this->response_update($affectedRows);
	}

	public function get_get()
	{
		$teamId = $this->get_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$teamInfo = $this->verifyTeamExists($teamId);

		return $this->response_object($teamInfo);
	}

	public function change_state_to_cancel_post()
	{
		$teamId = $this->post_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$uid    = $this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$teamInfo = $this->verifyTeamExists($teamId);

		$this->verifyTeamState($teamInfo['state'], CONTEST_TEAM_STATE_INIT);

		if ($teamInfo['fk_user'] != $uid) {
			return $this->response_error(Error_Code::ERROR_TEAM_EDIT_NO_AUTH);
		}

		$affectedRows = $this->Team_model->changeState($teamId, CONTEST_TEAM_STATE_INIT, CONTEST_TEAM_STATE_CANCEL);

		return $this->response_update($affectedRows);
	}

	public function list_members_get()
	{
		$teamId     = $this->get_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$total      = 0;
		$memberList = $this->Team_model->listMembersByPage($teamId, $pageNumber, $pageSize, $total);

		return $this->response_list($memberList, $total, $pageNumber, $pageSize);
	}

	/**
	 * @type int 1 - I created, 2 - I joined
	 */
	public function list_by_uid_get()
	{
		$uid             = $this->get_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$type            = $this->get_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$itemId          = $this->get_check('item_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$contestRequired = $this->get_check('contest_required', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber      = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageSize        = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$total    = 0;
		$teamList = array();

		switch ($type) {
			case 1: // I created
				$teamList = $this->Team_model->listByUid($uid, $itemId, $pageNumber, $pageSize, $total);

				$teamList = array_column($teamList, null, 'pk_team');
				break;
			case 2: // I joined
				$mappingTeamUser = $this->Team_model->listMappingUserTeamsByPage($uid, $pageNumber, $pageSize, $total);

				if (empty($mappingTeamUser)) {
					return $this->response_list(array(), 0, $pageNumber, $pageSize);
				}

				$teamIds = array_column($mappingTeamUser, 'fk_team');

				$teams = $this->Team_model->getByIds($teamIds);

				if (empty($teams)) {
					return $this->response_list(array(), 0, $pageNumber, $pageSize);
				}

				$teams = array_column($teams, null, 'pk_team');

				foreach ($mappingTeamUser as $val) {
					if (empty($teams[$val['fk_team']])) {
						continue;
					}
					$teamList[] = $teams[$val['fk_team']];
				}
				break;
		}

		if ($contestRequired) {
			$teamIds = array_column($teamList, 'fk_contest_items');
			$teamIds = array_flip(array_flip($teamIds));
			if (empty($teamIds)) {
				return $this->response_list($teamList, $total, $pageNumber, $pageSize);
			}
			$itemList = $this->ContestItem_model->getByIds($teamIds);
			if (empty($itemList)) {
				$teamList = array_merge(array(), $teamList);
				return $this->response_list($teamList, $total, $pageNumber, $pageSize);
			}
			$itemList = array_column($itemList, null, 'pk_contest_items');

			$cids = array_column($itemList, 'fk_contest');
			$cids = array_flip(array_flip($cids));
			if (empty($cids)) {
				return $this->response_list($teamList, $total, $pageNumber, $pageSize);
			}
			$contestList = $this->Contest_model->getByIds($cids);
			if (empty($contestList)) {
				$teamList = array_merge(array(), $teamList);
				return $this->response_list($teamList, $total, $pageNumber, $pageSize);
			}
			$contestList = array_column($contestList, null, 'pk_contest');

			foreach ($itemList as $key => $val) {
				$itemList[$key]['contest_info'] = $contestList[$val['fk_contest']];
			}

			foreach ($teamList as $key => $val) {
				$teamList[$key]['contest_info'] = $itemList[$val['fk_contest_items']]['contest_info'];
			}
		}

		$teamList = array_merge(array(), $teamList);

		return $this->response_list($teamList, $total, $pageNumber, $pageSize);
	}

}

