<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Error_Code.php';

class Base extends MY_Controller
{
	protected function verifyParamSystem($system)
	{
		global $OPEN_API_SYSTEM_LIST;
		if (!in_array($system, $OPEN_API_SYSTEM_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: system');
		}

		return true;
	}

	protected function verifyParamInternalSystem($internalSystem)
	{
		global $INTERNAL_API_SYSTEM_LIST;

		if (!in_array($internalSystem, $INTERNAL_API_SYSTEM_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: internal_system');
		}

		return true;
	}

	protected function verifyParamMethod($method)
	{
		global $OPEN_API_METHOD_LIST;

		if (!in_array($method, $OPEN_API_METHOD_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: method');
		}

		return true;
	}

	protected function verifyParamRole($role)
	{
		global $OPEN_API_ROLE_LIST;

		if (!in_array($role, $OPEN_API_ROLE_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: role');
		}

		return true;
	}

	protected function verifyParamNull($paramNull)
	{
		$paramNulls = array(API_PARAMS_NULL_ALLOW, API_PARAMS_NULL_NOT);
		if (!in_array($paramNull, $paramNulls)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: param_null');
		}

		return true;
	}

	protected function verifyParamIoType($ioType)
	{
		global $API_IO_TYPE_LIST;
		if (!in_array($ioType, $API_IO_TYPE_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: io_type');
		}

		return true;
	}

	protected function verifyParamApiParamType($paramType)
	{
		global $API_PARAMS_VALUE_TYPE_LIST;
		if (!in_array($paramType, $API_PARAMS_VALUE_TYPE_LIST)) {
			return $this->response_error(Error_Code::ERROR_PARAM, 'invalid param: param_type');
		}

		return true;
	}

	protected function verifyApiExists($apiId)
	{
		$this->load->model('Api_model');
		$apiInfo = $this->Api_model->getById($apiId);
		if (empty($apiInfo)) {
			return $this->response_error(Error_Code::ERROR_API_NOT_EXISTS);
		}
		return $apiInfo;
	}

	protected function verifyApiStateOk($state)
	{
		if ($state != OPEN_API_STATE_OK) {
			return $this->response_error(Error_Code::ERROR_API_STATE_INVALID);
		}

		return true;
	}

    /**
     * formatApiParams
     *  格式化参数，组成参数树结构（需按parent_id排序）
     * 
     * @access protected
     * @return void
     */
    protected function formatApiParams($params){
        if(empty($params)){
            return $params;
        }

        $params_in = array();
        $params_out = array();

        foreach($params as $p){
            if($p['io_type'] == API_PARAMS_IO_TYPE_IN){
                $this->findParent($p, $params_in);
            }else{
                $this->findParent($p, $params_out);
            }
        }

        $params_result = array(
            API_PARAMS_IO_TYPE_IN => $params_in,
            API_PARAMS_IO_TYPE_OUT => $params_out
        );

        return $params_result;
    }

    private function findParent($p, &$params_new){
        $pk_api_params = $p['pk_api_params'];
        $parent_id = $p['parent_id'];
        if(empty($parent_id)){
            $params_new[] = $p;
        }else{
            foreach($params_new as &$pn){
                if($pn['pk_api_params'] == $parent_id){
                    $pn['children'][] = $p;
                }else{
                    if(isset($pn['children'])){
                        $this->findParent($p, $pn['children']);
                    }
                }
            }
        }
    }

}
