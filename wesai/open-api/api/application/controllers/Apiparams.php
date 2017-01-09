<?php

require_once __DIR__ . '/Base.php';

/**
 * User: zhaodc
 * Date: 7/31/16
 * Time: 22:00
 */
class Apiparams extends Base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('ApiParams_model');
	}

	public function add_post()
	{
		$apiId             = $this->post_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);
		$ioType            = $this->post_check('io_type', PARAM_NOT_NULL_NOT_EMPTY);
		$paramName         = $this->post_check('param_name', PARAM_NOT_NULL_NOT_EMPTY);
		$paramType         = $this->post_check('param_type', PARAM_NOT_NULL_NOT_EMPTY);
		$paramNameInternal = $this->post_check('param_name_internal', PARAM_NOT_NULL_NOT_EMPTY);
		$parentId          = $this->post_check('parent_id', PARAM_NULL_EMPTY);
		$paramNull         = $this->post_check('param_null', PARAM_NULL_NOT_EMPTY);
		$mark              = $this->post_check('mark', PARAM_NULL_NOT_EMPTY);

		$this->verifyParamIoType($ioType);
		$this->verifyParamApiParamType($paramType);
		if (!empty($paramNull)) {
			$this->verifyParamNull($paramNull);
		}

		$verify = $this->ApiParams_model->verifyApiParamsDuplicated($apiId, $paramName, $ioType, $parentId);
		if (!empty($verify)) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_DUPLICATED);
		}

		$apiParamData = array(
			'fk_api'              => $apiId,
			'io_type'             => $ioType,
			'param_name'          => $paramName,
			'param_type'          => $paramType,
			'param_name_internal' => $paramNameInternal,
			'parent_id'           => $parentId,
			'param_null'          => $paramNull,
			'mark'                => $mark,
		);

		$apiParamId = $this->ApiParams_model->addParam($apiParamData);
		if (empty($apiParamId)) {
			return $this->response_error(Error_Code::ERROR_ADD_API_PARAM_FAILED);
		}

		return $this->response_insert($apiParamId);
	}

	public function update_post()
	{
		$paramId           = $this->post_check('api_param_id', PARAM_NOT_NULL_NOT_EMPTY);
		$ioType            = $this->post_check('io_type', PARAM_NULL_NOT_EMPTY);
		$paramName         = $this->post_check('param_name', PARAM_NULL_NOT_EMPTY);
		$paramType         = $this->post_check('param_type', PARAM_NULL_NOT_EMPTY);
		$paramNameInternal = $this->post_check('param_name_internal', PARAM_NULL_NOT_EMPTY);
		$parentId          = $this->post_check('parent_id', PARAM_NULL_EMPTY);
		$paramNull         = $this->post_check('param_null', PARAM_NULL_NOT_EMPTY);
		$mark              = $this->post_check('mark', PARAM_NULL_NOT_EMPTY);

		$this->verifyParamIoType($ioType);
		$this->verifyParamApiParamType($paramType);
		if (!empty($paramNull)) {
			$this->verifyParamNull($paramNull);
		}

		$paramInfo = $this->ApiParams_model->getById($paramId);
		if (empty($paramInfo)) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_NOT_EXISTS);
		}

		if ($paramInfo['state'] != API_PARAMS_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_STATE_INVALID);
		}

		$paramData = array(
			'pk_api_params'       => $paramId,
			'io_type'             => $ioType,
			'param_name'          => $paramName,
			'param_type'          => $paramType,
			'param_name_internal' => $paramNameInternal,
			'parent_id'           => $parentId,
			'param_null'          => $paramNull,
			'mark'                => $mark,
		);

		$affectedRows = $this->ApiParams_model->updateParam($paramId, $paramData);

		return $this->response_update($affectedRows);
	}

	public function delete_post()
	{
		$paramId = $this->post_check('api_param_id', PARAM_NOT_NULL_NOT_EMPTY);

		$paramInfo = $this->ApiParams_model->getById($paramId);
		if (empty($paramInfo)) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_NOT_EXISTS);
		}

		if ($paramInfo['state'] == API_PARAMS_STATE_NG) {
			return $this->response_update(0);
		}

		$affectedRows = $this->ApiParams_model->deleteParam($paramId);

		return $this->response_update($affectedRows);
	}

	public function get_get()
	{
		$paramId = $this->get_check('api_param_id', PARAM_NOT_NULL_NOT_EMPTY);

		$paramInfo = $this->ApiParams_model->getById($paramId);
		if (empty($paramInfo)) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_NOT_EXISTS);
		}

		if ($paramInfo['state'] != API_PARAMS_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_STATE_INVALID);
		}

		return $this->response_object($paramInfo);
	}

	public function list_get()
	{
		$apiId  = $this->get_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);
		$ioType = $this->get_check('io_type', PARAM_NOT_NULL_NOT_EMPTY);

		$this->verifyParamIoType($ioType);

		$paramList = $this->ApiParams_model->listParams($apiId, $ioType);

		return $this->response_list($paramList, count($paramList), 1, count($paramList));
	}

	public function restore_post()
	{
		$paramId = $this->post_check('api_param_id', PARAM_NOT_NULL_NOT_EMPTY);

		$paramInfo = $this->ApiParams_model->getById($paramId);
		if (empty($paramInfo)) {
			return $this->response_error(Error_Code::ERROR_API_PARAM_NOT_EXISTS);
		}

		if ($paramInfo['state'] == API_PARAMS_STATE_OK) {
			return $this->response_update(0);
		}

		$affectedRows = $this->ApiParams_model->restoreParam($paramId);

		return $this->response_update($affectedRows);
	}
}
