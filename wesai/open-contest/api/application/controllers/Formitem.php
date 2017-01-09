<?php
/**
 * User: zhaodc
 * Date: 30/09/2016
 * Time: 11:55
 */
require_once __DIR__ . '/Base.php';

class Formitem extends Base
{
	public function __construct()
	{
		parent::__construct();
    }

	public function add_post()
	{
		$formId  = $this->post_check('form_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$type         = $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY);
		$title        = $this->post_check('title', PARAM_NOT_NULL_NOT_EMPTY);
		$isRequired   = $this->post_check('is_required', PARAM_NULL_NOT_EMPTY);
		$optionValues = $this->post_check('option_values', PARAM_NULL_EMPTY);

		//获取最大序号
		$seq = $this->FormItem_model->getMaxSeq($formId);

		$params          = array(
			'fk_enrol_form' => $formId,
			'type'          => $type,
			'title'         => $title,
			'is_required'   => $isRequired,
			'seq'           => $seq + 1,
			'option_values' => $optionValues,
		);
		$formItemId = $this->FormItem_model->create($params);
		if (empty($formItemId)) {
			return $this->response_error(Error_Code::ERROR_ADD_ENROL_FORM_ITEM_FAILED);
		}

		return $this->response_insert($formItemId);
	}

	public function delete_post()
	{
		$formItemId = $this->post_check('form_item_id', PARAM_NOT_NULL_NOT_EMPTY);

		$formItemInfo = $this->FormItem_model->getById($formItemId);
		if (empty($formItemInfo)) {
			return $this->response_error(Error_Code::ERROR_ENROL_FORM_ITEM_NOT_EXISTS);
		}

		if ($formItemInfo['state'] != ENROL_FORM_ITEM_STATE_OK) {
			return $this->response_update(0);
		}

		$affectedRows = $this->FormItem_model->remove($formItemId);

		return $this->response_update($affectedRows);
	}

	public function get_get()
	{
		$formItemId = $this->get_check('form_item_id', PARAM_NOT_NULL_NOT_EMPTY);

		$result = $this->FormItem_model->getById($formItemId);
		if (empty($result)) {
			return $this->response_error(Error_Code::ERROR_ENROL_FORM_ITEM_NOT_EXISTS);
		}

		return $this->response_object($result);
	}

	public function list_by_form_get()
	{
		$formId = $this->get_check('form_id', PARAM_NOT_NULL_NOT_EMPTY);
		$pageNumber = 1;
		$pageSize   = 100;
		$result     = $this->FormItem_model->listByForm($formId, $pageNumber, $pageSize);

		return $this->response_list($result, count($result), $pageNumber, $pageSize);
	}

	public function update_post()
	{
		$this->post_check('form_item_id', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('type', PARAM_NULL_NOT_EMPTY);
		$this->post_check('title', PARAM_NULL_NOT_EMPTY);
		$this->post_check('is_required', PARAM_NULL_NOT_EMPTY);
		$this->post_check('option_values', PARAM_NULL_NOT_EMPTY);
		//$this->post_check('seq', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$params = $this->get_request_params();

		$formItemId = $params['form_item_id'];
		unset($params['form_item_id']);

		$formItemInfo = $this->FormItem_model->getById($formItemId);
		if (empty($formItemInfo)) {
			return $this->response_error(Error_Code::ERROR_ENROL_FORM_ITEM_NOT_EXISTS);
		}

		if ($formItemInfo['state'] != ENROL_FORM_ITEM_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_ENROL_FORM_ITEM_STATE_INVALID);
		}

		$affectedRows = $this->FormItem_model->modify($formItemId, $params);

		return $this->response_update($affectedRows);
	}

	/**
	 * @param json [{"qid":7,"seq":1},{"qid":8,"seq":2}]
	 */
	public function set_seqs_post()
	{
		$params = $this->post_check('params', PARAM_NOT_NULL_NOT_EMPTY);

		$params = json_decode($params, true);

		$affectedRows = $this->FormItem_model->setSeqs($params);

		return $this->response_update($affectedRows);
	}

	public function list_by_form_ids_get(){

		$formIds = $this->get_check('form_ids', PARAM_NOT_NULL_NOT_EMPTY);

		$formIds = explode(',', $formIds);

		$formItemList = $this->FormItem_model->listByFormIds($formIds);

		return $this->response_list($formItemList, count($formItemList), 1, count($formItemList));

	}

}
