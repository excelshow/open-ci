<?php
/**
 * User: zhaodc
 * Date: 26/09/2016
 * Time: 18:21
 */
require __DIR__ . '/Base.php';

class Enroldata extends Base
{
	public function __construct()
	{
		parent::__construct();

	}

	/**
	 *
	 * info type json
	 * {
	 *      qid : xxx,
	 *      title : xxx,
	 *      type : xxx,
	 *      value : xxx,
	 * }
	 */
	public function add_post()
	{
		$this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('info', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('team_id', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$this->post_check('group_id', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$this->post_check('invite_code', PARAM_NULL_EMPTY);

		$params = $this->get_request_params();

		if (!in_array($params['type'], [ENROL_DATA_TYPE_SINGLE, ENROL_DATA_TYPE_GROUP, ENROL_DATA_TYPE_TEAM])) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'type 非法');
		}

		// 获取项目资料
		$itemInfo = $this->verifyContestItemExists($params['item_id']);

		// 验证项目状态
		$this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

		// 检查项目已关门,停售
		if (strtotime($itemInfo['end_time']) < time()) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_CLOSED);
		}

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($itemInfo['fk_contest']);

		// 验证活动状态
		$this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_SELLING);


		switch ($params['type']) {
			case ENROL_DATA_TYPE_SINGLE:
				// 检查项目类型
				if ($itemInfo['type'] != CONTEST_ITEM_TYPE_SINGLE) {
					return $this->response_error(Error_Code::ERROR_PARAM, '报名资料类型与活动项目类型不符');
				}
				unset($params['team_id'], $params['group_id']);
				break;
			case ENROL_DATA_TYPE_GROUP:
				// 检查项目类型
				if ($itemInfo['type'] != CONTEST_ITEM_TYPE_SINGLE) {
					return $this->response_error(Error_Code::ERROR_PARAM, '报名资料类型与活动项目类型不符');
				}

				if (empty($params['group_id'])) {
					return $this->response_error(Error_Code::ERROR_PARAM, 'group_id 必填非空');
				}
				$params['fk_group'] = $params['group_id'];
				unset($params['group_id']);

				$groupInfo = $this->verifyGroupExists($params['fk_group']);

				$this->verifyGroupState($groupInfo['state'], CONTEST_GROUP_STATE_INIT);
				break;
			case ENROL_DATA_TYPE_TEAM:
				// 检查项目类型
				if ($itemInfo['type'] != CONTEST_ITEM_TYPE_TEAM) {
					return $this->response_error(Error_Code::ERROR_PARAM, '报名资料类型与活动项目类型不符');
				}
				if (empty($params['team_id'])) {
					return $this->response_error(Error_Code::ERROR_PARAM, 'team_id 必填非空');
				}
				$params['fk_team'] = $params['team_id'];
				unset($params['team_id']);

				$teamInfo = $this->verifyTeamExists($params['fk_team']);

				$this->verifyTeamState($teamInfo['state'], CONTEST_TEAM_STATE_INIT);

				if ($teamInfo['max_member_count'] <= $teamInfo['cur_member_count']) {
					return $this->response_error(Error_Code::ERROR_TEAM_FULL);
				}
				break;
		}

		// 邀请报名
		if ($itemInfo['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES) {
			if (empty($params['invite_code'])) {
				return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_INVITE_CODE_NECESSARY);
			}

			// 验证邀请码
			$inviteCodeVerifyResult = $this->verifyInviteCode($params['item_id'], $params['invite_code']);
			if ($inviteCodeVerifyResult < 0) {
				return $this->response_error($inviteCodeVerifyResult);
			}
		}

		$inviteCode = !empty($params['invite_code']) ? $params['invite_code'] : '';
		unset($params['invite_code']);

		// 获取表单资料
		$formInfo = $this->Form_model->getByItemId($params['item_id']);
		if (empty($formInfo)) {
			return $this->response_error(Error_Code::ERROR_FORM_NOT_EXISTS);
		}

		//获取报名表问题列表
		$formItemList = $this->FormItem_model->listByForm($formInfo['pk_enrol_form'], 1, MAX_ENROL_FORM_ITEMS);
		if (empty($formItemList)) {
			return $this->response_error(Error_Code::ERROR_ENROL_FORM_ITEM_NOT_EXISTS);
		}
		$formItemList = array_column($formItemList, null, 'pk_enrol_form_item');

		$info = json_decode($params['info'], true);
		unset($params['info']);
		$info = array_column($info, null, 'qid');

		foreach ($formItemList as $k => $v) {
			// 检查必填项
			if ($v['is_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES && !array_key_exists($v['pk_enrol_form_item'], $info)) {
				return $this->response_error(Error_Code::ERROR_FORM_INPUT_INVALID);
			}

			if (array_key_exists($v['pk_enrol_form_item'], $info)) {
				$info[$v['pk_enrol_form_item']]['seq']                = $v['seq'];
				$info[$v['pk_enrol_form_item']]['fk_enrol_form_item'] = $v['pk_enrol_form_item'];
				unset($info[$v['pk_enrol_form_item']]['qid']);
			}
		}

		$info = array_intersect_key($info, $formItemList);

		$params['fk_user']          = $params['uid'];
		$params['fk_enrol_form']    = $formInfo['pk_enrol_form'];
		$params['fk_contest_items'] = $params['item_id'];

		unset($params['uid'], $params['form_id'], $params['item_id'], $params['team_id'], $params['group_id']);

		$enrolDataId = $this->EnrolData_model->create($params, $info, $inviteCode);

		return $this->response_insert($enrolDataId);
	}


	/**
	 * 校验报名邀请码是否合法
	 *
	 * @param $itemId
	 * @param $code
	 *
	 * @return int
	 */
	private function verifyInviteCode($itemId, $code)
	{
		$code     = strtoupper($code);
		$codeInfo = $this->InviteCode_model->getByCode($itemId, $code);

		if (empty($codeInfo)) {
			return Error_Code::ERROR_CONTEST_ITEM_INVITE_CODE_NOT_EXISTS;
		}

		if ($codeInfo['state'] == CONTEST_ITEM_INVITE_CODE_STATE_USED) {
			return Error_Code::ERROR_CONTEST_ITEM_INVITE_CODE_USED;
		}

		if ($codeInfo['state'] == CONTEST_ITEM_INVITE_CODE_STATE_EXPIRED) {
			return Error_Code::ERROR_CONTEST_ITEM_INVITE_CODE_EXPIRED;
		}

		return 0;
	}

	public function get_by_code_get()
	{
		$verifyCode = $this->get_check('code', PARAM_NOT_NULL_NOT_EMPTY);

		$enrolData = $this->EnrolData_model->getByCode($verifyCode);
		if (empty($enrolData)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
		}

		$this->load->model('Order_model');
		$orderInfo = null;
		switch ($enrolData['type']) {
			case ENROL_DATA_TYPE_SINGLE:
				$orderInfo = $this->Order_model->getByEnrolDataId($enrolData['pk_enrol_data']);
				if (empty($orderInfo)) {
					return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
				}
				break;
			case ENROL_DATA_TYPE_GROUP:
				$orderInfo = $this->Order_model->getByGroup($enrolData['fk_group']);
				if (empty($orderInfo)) {
					return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
				}
				break;
			case ENROL_DATA_TYPE_TEAM:
				$orderInfo = $this->Order_model->getByTeam($enrolData['fk_team']);
				if (empty($orderInfo)) {
					return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
				}
				break;
		}

		$enrolData['order_info'] = $orderInfo;

		$enrolDataDetail = $this->EnrolDataDetail_model->getByEnrolDataIds(array($enrolData['pk_enrol_data']));
		if (empty($enrolDataDetail)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_DETAIL_NOT_EXISTS);
		}

		$enrolData['enrol_data_detail'] = $enrolDataDetail;

		return $this->response_object($enrolData);
	}

	public function delete_post()
	{
		$enrolDataId = $this->post_check('enrol_data_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$uid         = $this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$enrolData = $this->EnrolData_model->getById($enrolDataId);
		if (empty($enrolData)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
		}

		if ($enrolData['state'] == ENROL_DATA_STATE_NG) {
			return $this->response_update(0);
		}

		switch ($enrolData['type']) {
			case ENROL_DATA_TYPE_GROUP:
				$groupInfo = $this->verifyGroupExists($enrolData['fk_group']);
				if ($groupInfo['fk_user'] != $uid && $enrolData['fk_user'] != $uid) {
					return $this->response_error(Error_Code::ERROR_GROUP_EDIT_NO_AUTH);
				}
				$orderInfo = $this->Order_model->getByGroup($enrolData['fk_group']);
				if (!empty($orderInfo) && $orderInfo['state'] != ORDER_STATE_FAILED) {
					return $this->response_error(Error_Code::ERROR_GROUP_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA);
				}
				break;
			case ENROL_DATA_TYPE_TEAM:
				$teamInfo = $this->verifyTeamExists($enrolData['fk_team']);
				if ($teamInfo['fk_user'] != $uid && $enrolData['fk_user'] != $uid) {
					return $this->response_error(Error_Code::ERROR_TEAM_EDIT_NO_AUTH);
				}
				$orderInfo = $this->Order_model->getByTeam($enrolData['fk_team']);
				if (!empty($orderInfo) && $orderInfo['state'] != ORDER_STATE_FAILED) {
					return $this->response_error(Error_Code::ERROR_TEAM_IN_PAYING_CAN_NOT_REMOVE_ENROL_DATA);
				}
				break;
			default:
				return $this->response_error(Error_Code::ERROR_PARAM, 'enrol_data_id 非法');
				break;
		}

		$affectedRows = $this->EnrolData_model->remove($enrolDataId, $enrolData['fk_user'], $enrolData['type'], $enrolData['fk_group'], $enrolData['fk_team']);
		if (empty($affectedRows)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_REMOVE_FAILED);
		}

		return $this->response_update($affectedRows);
	}

	public function verify_post()
	{
		$verifyCode  = $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$corpUid     = $this->post_check('corp_uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$ownerCorpId = $this->post_check('owner_corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$itemId      = $this->post_check('item_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$enrolData = $this->EnrolData_model->getByCode($verifyCode);
		if (empty($enrolData)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
		}

		$verifyCodeInfo = $enrolData['verify_code'][0];
		$orderId = $verifyCodeInfo['fk_order'];

		$orderInfo = $this->verifyOrderExists($orderId);

		$this->verifyOrderState($orderInfo['state'], ORDER_STATE_CLOSED);

		if ($orderInfo['owner_fk_corp'] != $ownerCorpId) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_VERIFY_CORP_NOT_MATCH);
		}

		if ($verifyCodeInfo['max_verify'] > 0 && $verifyCodeInfo['verify_number'] >= $verifyCodeInfo['max_verify']) {
			return $this->response_error(Error_Code::ERROR_ORDER_VERIFY_OVERFLOW);
		}
		if (!empty($itemId)) {
			$this->verifyContestItemExists($itemId);

			if ($enrolData['fk_contest_items'] != $itemId) {
				return $this->response_error(Error_Code::ERROR_ENROL_DATA_VERIFY_ITEM_NOT_MATCH);
			}
		}

		$affectedRows = $this->EnrolData_model->verify($verifyCodeInfo, $corpUid);
		if (empty($affectedRows)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_VERIFY_FAILED);
		}

		return $this->response_update($affectedRows);
	}

	public function list_by_group_get()
	{
		$groupId    = $this->get_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$uid        = $this->get_check('uid', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$enrolDataList = $this->EnrolData_model->listByGroupUser($groupId, $pageNumber, $pageSize, $uid, ENROL_DATA_STATE_OK);
		if (!empty($enrolDataList)) {
			$enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

			$enrolDataDetailList = $this->EnrolDataDetail_model->getByEnrolDataIds($enrolDataIds);

			if (!empty($enrolDataDetailList)) {
				foreach ($enrolDataList as $k => $v) {
					foreach ($enrolDataDetailList as $key => $val) {
						if ($v['pk_enrol_data'] == $val['fk_enrol_data']) {
							$enrolDataList[$k]['enrol_data_detail'][] = $val;
							unset($enrolDataDetailList[$key]);
						}
					}
				}
			}
		}

		return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
	}

	public function list_by_team_get()
	{
		$groupId    = $this->get_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$uid        = $this->get_check('uid', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$enrolDataList = $this->EnrolData_model->listByTeamUser($groupId, $pageNumber, $pageSize, $uid, ENROL_DATA_STATE_OK);
		if (!empty($enrolDataList)) {
			$enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

			$enrolDataDetailList = $this->EnrolDataDetail_model->getByEnrolDataIds($enrolDataIds);

			if (!empty($enrolDataDetailList)) {
				foreach ($enrolDataList as $k => $v) {
					foreach ($enrolDataDetailList as $key => $val) {
						if ($v['pk_enrol_data'] == $val['fk_enrol_data']) {
							$enrolDataList[$k]['enrol_data_detail'][] = $val;
							unset($enrolDataDetailList[$key]);
						}
					}
				}
			}
		}

		return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
	}

	public function get_get()
	{
		$enrolDataId = $this->get_check('enrol_data_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$enrolData = $this->EnrolData_model->getById($enrolDataId);
		if (empty($enrolData)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
		}
		
		$verifyCode = $this->EnrolData_model->listVerifyCodeByEnrolDataIds($enrolDataId);
		
		$enrolData['verify_code'] = $verifyCode;

		$enrolDataDetail = $this->EnrolDataDetail_model->getByEnrolDataIds(array($enrolData['pk_enrol_data']));
		if (empty($enrolDataDetail)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_DETAIL_NOT_EXISTS);
		}

		$enrolData['enrol_data_detail'] = $enrolDataDetail;

		return $this->response_object($enrolData);
	}

	private function getEnrolDataDetailByEnrolDataIds(Array $enrolDataIds, Array $enrolDataList)
	{
		$enrolDataDetail = $this->EnrolDataDetail_model->getByEnrolDataIds($enrolDataIds);
		if (empty($enrolDataDetail)) {
			return $enrolDataList;
		}

		$enrolDataList = array_column($enrolDataList, null, 'pk_enrol_data');
		foreach ($enrolDataDetail as $v) {
			$enrolDataList[$v['fk_enrol_data']]['enrol_data_detail'][] = $v;
		}

		return array_merge(array(), $enrolDataList);
	}

	public function get_by_ids_get(){
		$enrolDataIds = $this->get_check('enrol_data_ids', PARAM_NOT_NULL_NOT_EMPTY);

		$enrolDataIds = explode(',', $enrolDataIds);

		$enrolDataList = $this->EnrolData_model->getByIds($enrolDataIds);
		if (empty($enrolDataList)) {
			return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
		}

		$enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

		$enrolDataList = $this->getEnrolDataDetailByEnrolDataIds($enrolDataIds, $enrolDataList);

		return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));

	}

	public function get_by_team_ids_get()
	{
		$teamIds    = $this->get_check('team_ids', PARAM_NOT_NULL_NOT_EMPTY);

		$teamIds = explode(',', $teamIds);

		$enrolDataList = $this->EnrolData_model->listByTeamIds($teamIds, ENROL_DATA_STATE_OK);
		if (empty($enrolDataList)) {
			return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
		}

		$enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

		$enrolDataList = $this->getEnrolDataDetailByEnrolDataIds($enrolDataIds, $enrolDataList);

		return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
	}


	public function get_by_group_ids_get()
	{
		$groupId = $this->get_check('group_ids', PARAM_NOT_NULL_NOT_EMPTY);

		$enrolDataList = $this->EnrolData_model->listByGroupIds($groupId, ENROL_DATA_STATE_OK);
		if (empty($enrolDataList)) {
			return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
		}

		$enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

		$enrolDataList = $this->getEnrolDataDetailByEnrolDataIds($enrolDataIds, $enrolDataList);

		return $this->response_list($enrolDataList, count($enrolDataList), 1, count($enrolDataList));
	}


}
