<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ .'/Base.php';
/**
 * 赛项目报名表单类
 *
 * @package default
 * @author  : zhaodechang@wesai.com
 **/
class Form extends Base
{
	/**
	 * construct
	 *
	 * @return void
	 **/
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 新增报名表单
	 *
	 */
	public function add_post()
	{
		$itemId = $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$name   = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);

		$verify = $this->editVerify($itemId);
		if ($verify < 0) {
			return $this->response_error($verify);
		}

		$formInfo = $this->getByItemId($itemId);
		if (!empty($formInfo)) {
			return $this->response_error(Error_Code::ERROR_FORM_ALREADY_EXISTS);
		}

		$formId = $this->Form_model->create($itemId, $name);

		return $this->response_insert($formId);
	}

	/**
	 * 报名表单编辑校验
	 *
	 * @param $itemId
	 *
	 * @return int
	 *
	 */
	private function editVerify($itemId)
	{
		// 获取活动项目资料
		$itemInfo = $this->verifyContestItemExists($itemId);

		// 活动活动项目状态异常
		if ($itemInfo['state'] != CONTEST_ITEM_STATE_OK) {
			return Error_Code::ERROR_CONTEST_ITEMS_STATE_INVALID;
		}

		$verifyContestEdit = $this->contestEditVerify($itemInfo['fk_contest'], true);
		if ($verifyContestEdit->error < 0) {
			return $verifyContestEdit->error;
		}

		return 0;
	}

	private function getByItemId($item_id)
	{
		return $this->Form_model->getByItemId($item_id);
	}

	public function get_by_item_id_get()
	{
		$itemId = $this->get_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$formInfo = $this->getByItemId($itemId);
		if (empty($formInfo)) {
			return $this->response_error(Error_Code::ERROR_FORM_NOT_EXISTS);
		}

		return $this->response_object($formInfo);
	}

	public function get_by_item_ids_get()
	{
		$itemIds = $this->get_check('item_ids', PARAM_NOT_NULL_NOT_EMPTY);
		$itemIds = explode(',', $itemIds);
		$formInfoList = $this->Form_model->getByItemIds($itemIds);
		if (empty($formInfoList)) {
			return $this->response_error(Error_Code::ERROR_FORM_NOT_EXISTS);
		}

		return $this->response_list($formInfoList, count($formInfoList), 1, count($formInfoList));
	}

	/**
	 * 根据表单ID获取详情
	 *
	 */
	public function get_get()
	{
		$formId = $this->get_check('form_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$formInfo = $this->verifyFormExists($formId);

		return $this->response_object($formInfo);
	}

} // END class
