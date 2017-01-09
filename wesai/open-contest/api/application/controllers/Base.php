<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Error_Code.php';

class Base extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Contest_model');
		$this->load->model('ContestItem_model');
		$this->load->model('Team_model');
		$this->load->model('Group_model');
		$this->load->model('Form_model');
		$this->load->model('Msg_model');
		$this->load->model('EnrolData_model');
		$this->load->model('EnrolDataDetail_model');
		$this->load->model('InviteCode_model');
		$this->load->model('FormItem_model');
		$this->load->model('Order_model');
		$this->load->model('Tag_model');
		$this->load->model('SharingSettings_model');

	}

	/**
	 * 校验活动是否可编辑
	 * publish_state == CONTEST_PUBLISH_STATE_DRAFT
	 * 可修改所有资料,列表不可见,直接访问不可见
	 * publish_state == CONTEST_PUBLISH_STATE_ON
	 * 可修改所有资料,列表可见,直接访问可见
	 * publish_state == CONTEST_PUBLISH_STATE_SELLING
	 * 不可修改项目资料,列表可见,直接访问可见
	 * publish_state == CONTEST_PUBLISH_STATE_OFF
	 * 不可修改项目资料,列表不可见,直接访问可见(订单列表可查看详情)
	 *
	 * @param  integer $cid 活动ID
	 *
	 * @param bool     $editItem
	 *
	 * @return \stdClass
	 */
	protected function contestEditVerify($cid, $editItem = false)
	{
		$std        = new stdClass();
		$std->error = 0;

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($cid);

		$std->contestInfo = $contestInfo;

		if ($editItem) {
			$this->verifyContestState($contestInfo['publish_state'], [CONTEST_PUBLISH_STATE_DRAFT, CONTEST_PUBLISH_STATE_ON, CONTEST_PUBLISH_STATE_OFF]);
		}

		return $std;
	}

	protected function catchException(Exception $e)
	{
		$errMsg = array(
			'msg'     => 'Exception occurred',
			'e_file'  => $e->getFile(),
			'e_line'  => $e->getLine(),
			'e_msg'   => $e->getMessage(),
			'e_trace' => $e->getTrace()[0],
		);
		log_message_v2('error', $errMsg);
	}

	protected function checkPageParams(&$pageNumber, &$pageSize, $maxSize = 100)
	{
		$pageNumber = intval($pageNumber);
		$pageSize   = intval($pageSize);
		$pageNumber >= 0 or $pageNumber = 1;
		$pageSize > 0 or $pageSize = 20;
		$pageSize < $maxSize or $pageSize = $maxSize;
	}

	protected function verifyContestExists($cid, $returnIntro = false)
	{
		$this->load->model('Contest_model');

		$contestInfo = $this->Contest_model->getById($cid, $returnIntro);
		if (empty($contestInfo)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
		}

		return $contestInfo;
	}

	protected function verifyContestState($curState, $targetState)
	{
		if (false === $this->verifyState($curState, $targetState)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_PUBLISH_STATE_INVALID);
		}
	}

	protected function verifyContestItemExists($itemId)
	{
		$this->load->model('ContestItem_model');

		$itemInfo = $this->ContestItem_model->getById($itemId);
		if (empty($itemInfo)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
		}

		return $itemInfo;
	}

	protected function verifyContestItemState($curState, $targetState)
	{
		if (false === $this->verifyState($curState, $targetState)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_STATE_INVALID);
		}
	}

	protected function verifyState($curState, $targetState)
	{
		$type = gettype($targetState);
		switch ($type) {
			case 'integer':
				if ($curState != $targetState) {
					return false;
				}
				break;
			case 'array':
				if (!in_array($curState, $targetState)) {
					return false;
				}
				break;
		}

		return true;
	}

	protected function verifyTeamExists($teamId)
	{
		$this->load->model('Team_model');

		$teamInfo = $this->Team_model->getById($teamId);
		if (empty($teamInfo)) {
			return $this->response_error(Error_Code::ERROR_TEAM_NOT_EXISTS);
		}

		return $teamInfo;
	}

	protected function verifyTeamState($curState, $targetState)
	{
		if (false === $this->verifyState($curState, $targetState)) {
			return $this->response_error(Error_Code::ERROR_TEAM_STATE_INVALID);
		}
	}

	protected function verifyGroupExists($groupId)
	{
		$this->load->model('Group_model');

		$groupInfo = $this->Group_model->getById($groupId);
		if (empty($groupInfo)) {
			return $this->response_error(Error_Code::ERROR_GROUP_NOT_EXISTS);
		}

		return $groupInfo;
	}

	protected function verifyGroupState($curState, $targetState)
	{
		if (false === $this->verifyState($curState, $targetState)) {
			return $this->response_error(Error_Code::ERROR_GROUP_STATE_INVALID);
		}
	}

	protected function verifyFormExists($formId)
	{
		$this->load->model('Form_model');

		$formInfo = $this->Form_model->getById($formId);
		if (empty($formInfo)) {
			return $this->response_error(Error_Code::ERROR_FORM_NOT_EXISTS);
		}

		return $formInfo;
	}

	protected function verifyOrderExists($orderId)
	{
		$this->load->model('Order_model');

		$orderInfo = $this->Order_model->getById($orderId);
		if (empty($orderInfo)) {
			return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
		}

		return $orderInfo;
	}

	protected function verifyOrderState($curState, $targetState)
	{
		if (false === $this->verifyState($curState, $targetState)) {
			return $this->response_error(Error_Code::ERROR_ORDER_INVALID_STATE);
		}
	}

	protected function verifyEnrolDataExists($enrolDataId)
	{
		$this->load->model('EnrolData_model');

		$enrolDataInfo = $this->EnrolData_model->getById($enrolDataId);
		if (empty($enrolDataInfo)) {
			return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
		}

		return $enrolDataInfo;
	}

	public function trim_string_ids($ids)
	{
		if (!is_string($ids)) {
			return $ids;
		}
		return trim(str_replace(' ', '', $ids), ',');
	}

	public function distinct_array_values($arr)
	{
		if (!is_array($arr)) {
			return $arr;
		}

		try {
			return array_flip(array_flip($arr));
		} catch (Exception $e) {
			return $arr;
		}
	}
}
