<?php

require_once __DIR__ . '/Base.php';

/**
 * User: zhaodc
 * Date: 7/31/16
 * Time: 21:55
 */
class Api extends Base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Api_model');
		$this->load->model('MappingRoleApi_model');
	}

	public function add_post()
	{
		$system         = $this->post_check('system', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$name           = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
		$path           = $this->post_check('path', PARAM_NOT_NULL_NOT_EMPTY);
		$method         = $this->post_check('method', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$internalSystem = $this->post_check('internal_system', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$internalName   = $this->post_check('internal_name', PARAM_NOT_NULL_NOT_EMPTY);
		$internalPath   = $this->post_check('internal_path', PARAM_NOT_NULL_NOT_EMPTY);

		$this->verifyParamSystem($system);
		$this->verifyParamInternalSystem($internalSystem);
		$this->verifyParamMethod($method);

		$apiInfo = $this->Api_model->getByUnqKey($system, $path);
		if (!empty($apiInfo) && $apiInfo['state'] == OPEN_API_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_API_DUPLICATED);
		}

		$params = array(
			'api_system'          => $system,
			'api_name'            => $name,
			'api'                 => $path,
			'method'              => $method,
			'api_internal_system' => $internalSystem,
			'api_internal_name'   => $internalName,
			'api_internal'        => $internalPath,
		);

		if (!empty($apiInfo)) {
			$params['state'] = OPEN_API_STATE_OK;
			$affectedRows    = $this->Api_model->updateApi($apiInfo['pk_api'], $params);

			return $this->response_update($affectedRows);
		}

		$apiId = $this->Api_model->addApi($params);
		if (empty($apiId)) {
			return $this->response_error(Error_Code::ERROR_ADD_API_FAILED);
		}

		return $this->response_insert($apiId);
	}

	public function update_post()
	{
		$apiId          = $this->post_check('api_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$system         = $this->post_check('system', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$name           = $this->post_check('name', PARAM_NULL_NOT_EMPTY);
		$path           = $this->post_check('path', PARAM_NULL_NOT_EMPTY);
		$method         = $this->post_check('method', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$internalSystem = $this->post_check('internal_system', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$internalName   = $this->post_check('internal_name', PARAM_NULL_NOT_EMPTY);
		$internalPath   = $this->post_check('internal_path', PARAM_NULL_NOT_EMPTY);

		$this->verifyParamSystem($system);
		$this->verifyParamInternalSystem($internalSystem);
		$this->verifyParamMethod($method);

		$apiInfo = $this->verifyApiExists($apiId);

		$this->verifyApiStateOk($apiInfo['state']);

		$params = array(
			'api_system'          => $system,
			'api_name'            => $name,
			'api'                 => $path,
			'method'              => $method,
			'api_internal_system' => $internalSystem,
			'api_internal_name'   => $internalName,
			'api_internal'        => $internalPath,
		);

		$affectedRows = $this->Api_model->updateApi($apiId, $params);

		return $this->response_update($affectedRows);
	}

	public function delete_post()
	{
		$apiId = $this->post_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);

		$apiInfo = $this->verifyApiExists($apiId);

		if ($apiInfo['state'] == OPEN_API_STATE_NG) {
			return $this->response_update(0);
		}

		$affectedRows = $this->Api_model->deleteApi($apiId);

		return $this->response_update($affectedRows);
	}

	/**
	 *
	 */
	public function get_get()
	{
		$apiId = $this->get_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);

		$apiInfo = $this->verifyApiExists($apiId);

		$this->verifyApiStateOk($apiInfo['state']);

		return $this->response_object($apiInfo);
	}

    /**
     * get_by_system_api_get
     * 获取系统接口
     * @access public
     * @return void
     */
	public function get_by_system_api_get()
	{
		$api_system = $this->get_check('api_system', PARAM_NOT_NULL_NOT_EMPTY);
		$api        = $this->get_check('api', PARAM_NOT_NULL_NOT_EMPTY);
		$method     = $this->get_check('method', PARAM_NOT_NULL_NOT_EMPTY);
		$state      = $this->get_check('state', PARAM_NULL_NOT_EMPTY);

		$apiInfo   = $this->Api_model->getBySystemApi($api_system, $api, $method);
		$apiParams = array();
		if (!empty($apiInfo)) {
			$this->load->model('ApiParams_model');
			$apiParams = $this->ApiParams_model->listByPkApi($apiInfo['pk_api'], $state);
			// 格式化apiParams
			$apiParams = $this->formatApiParams($apiParams);
		}

		$data              = array();
		$data['api']       = $apiInfo;
		$data['apiParams'] = $apiParams;

		return $this->response_object($data);
	}

	public function list_by_system_get()
	{
		$system     = $this->get_check('system', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$state      = $this->get_check('state', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$pageNumber = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$pageSize   = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);

		$pageNumber > 0 or $pageNumber = 1;
		$pageSize > 0 or $pageSize = 20;
		$pageSize < 100 or $pageSize = 100;

		$this->verifyParamSystem($system);

		$apiList = $this->Api_model->listApiBySystem($system, $pageNumber, $pageSize, $total, $state);

		return $this->response_list($apiList, $total, $pageNumber, $pageSize);
	}

	public function list_by_role_get()
	{
		$role = $this->get_check('role', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		// $state      = $this->get_check('state', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$state      = ROLE_API_MAPPING_STATE_OK;
		$pageNumber = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);
		$pageSize   = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);

		$pageNumber > 0 or $pageNumber = 1;
		$pageSize > 0 or $pageSize = 20;
		$pageSize < 100 or $pageSize = 100;

		$this->verifyParamRole($role);

		$apiIds = $this->MappingRoleApi_model->listApiByRole($role, $pageNumber, $pageSize, $total, $state);
		if (empty($apiIds)) {
			return $this->response_list([], $total, $pageNumber, $pageSize);
		}

		$idList = array();
		foreach ($apiIds as $v) {
			$idList[] = $v['fk_api'];
		}

		$apiList = $this->Api_model->listApiByIds($idList);
		if (empty($apiIds)) {
			return $this->response_list([], $total, $pageNumber, $pageSize);
		}

		$apiListNotSorted = array();
		foreach ($apiList as $v) {
			$apiListNotSorted[$v['pk_api']] = $v;
		}
		unset($apiList);

		$apiListSorted = array();
		$skipCount     = 0;
		foreach ($apiIds as $v) {
			if (!array_key_exists($v['fk_api'], $apiListNotSorted)) {
				$skipCount++;
				continue;
			}
			$apiListSorted[] = $apiListNotSorted[$v['fk_api']];
		}

		$total -= $skipCount;

		return $this->response_list($apiListSorted, $total, $pageNumber, $pageSize);
	}


	public function bind_role_post()
	{
		$role  = $this->post_check('role', PARAM_NOT_NULL_NOT_EMPTY);
		$apiId = $this->post_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);

		$this->verifyParamRole($role);

		$apiInfo = $this->verifyApiExists($apiId);

		$this->verifyApiStateOk($apiInfo['state']);

		$verify = $this->MappingRoleApi_model->getMappingByUnqKey($role, $apiId);
		if (!empty($verify) && $verify['state'] == ROLE_API_MAPPING_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_ROLE_API_MAPPING_DUPLICATED);
		}

		if (!empty($verify)) {
			$affectedRows = $this->MappingRoleApi_model->reBindMapping($verify['pk_mapping_role_api']);

			return $this->response_update($affectedRows);
		}

		$mappingId = $this->MappingRoleApi_model->bindMapping($role, $apiId);
		if (empty($mappingId)) {
			return $this->response_error(Error_Code::ERROR_ADD_ROLE_API_MAPPING_FAILED);
		}

		return $this->response_insert($mappingId);
	}

	public function unbind_role_post()
	{
		$mappingId = $this->post_check('mapping_id', PARAM_NOT_NULL_NOT_EMPTY);

		$mappingInfo = $this->MappingRoleApi_model->getById($mappingId);
		if (empty($mappingInfo)) {
			return $this->response_error(Error_Code::ERROR_ROLE_API_MAPPING_NOT_EXISTS);
		}

		if ($mappingInfo['state'] == ROLE_API_MAPPING_STATE_NG) {
			return $this->response_update(0);
		}

		$affectedRows = $this->MappingRoleApi_model->unbindMapping($mappingId);

		return $this->response_update($affectedRows);
	}

	public function restore_post()
	{
		$apiId = $this->post_check('api_id', PARAM_NOT_NULL_NOT_EMPTY);

		$apiInfo = $this->verifyApiExists($apiId);

		if ($apiInfo['state'] == OPEN_API_STATE_OK) {
			return $this->response_update(0);
		}

		$affectedRows = $this->Api_model->restoreApi($apiId);

		return $this->response_update($affectedRows);

	}
}
