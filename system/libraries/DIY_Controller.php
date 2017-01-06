<?php
require_once __DIR__ . '/REST_Controller.php';

define('PARAM_NULL_EMPTY', 1);//非必选可空
define('PARAM_NOT_NULL_EMPTY', 2);//必选可空
define('PARAM_NULL_NOT_EMPTY', 3);//非必选不可空
define('PARAM_NOT_NULL_NOT_EMPTY', 4);//必选不可空
define('PARAM_TYPE_NUMBER', 1);//参数类型-数字
define('PARAM_TYPE_STRING', 2);//参数类型-字符串
define('PARAM_TYPE_INT', 3);//参数类型-整数

abstract class DIY_Controller extends Rest_Controller
{
	private $param_list = array();
	/**
	 * 调用处理
	 */
	protected function _fire_method($method, $args)
	{
		try {
			call_user_func_array($method, $args);
		} catch (Exception $e) {
			// 默认是数据库异常
			$info = $e->getMessage();
			$code = $e->getCode();
			if (empty($code) || !is_numeric($code)) {
				$code = Error_Code::ERROR_THROW;
				$info = '[' . $code . '] ' . $info;
			}

			$data = $this->api_spec->gen_error($code, $info);
			echo $this->response($data);
			exit;
		}
	}

	protected function get_check($key, $check, $type = PARAM_TYPE_STRING, $xss_clean = true)
	{
		$val = $this->get($key, $xss_clean);

		return $this->params_check($key, $check, $type, $val, 'get');
	}

	private function params_type_check($key, $type, $val)
	{
		switch ($type) {
			case PARAM_TYPE_NUMBER:
				if (!is_numeric($val)) {
					throw new Exception($key . ' 类型错误, 需要数字类型', Error_Code::ERROR_PARAM);
				}
				$val = floatval($val);
				break;
			case PARAM_TYPE_INT:
				if (!is_numeric($val)) {
					throw new Exception($key . ' 类型错误, 需要数字类型', Error_Code::ERROR_PARAM);
				}
				$val = intval($val);
				break;
			default:
				break;
		}
		return $val;
	}
	private function params_check($key, $check, $type, $val, $method)
	{
        if(false !== $val && !is_numeric($val) && !is_string($val)){
            throw new Exception($key . ' 不是数字或字符串', Error_Code::ERROR_PARAM);
        }
		$val_origin = $val;
		$val        = trim($val);

		switch ($check) {
			case PARAM_NOT_NULL_NOT_EMPTY:
				if (empty($val)) {
					throw new Exception($key . ' 必传非空', Error_Code::ERROR_PARAM);
				}
				$val = $this->params_type_check($key, $type, $val);
				break;
			case PARAM_NOT_NULL_EMPTY:
				if (false === $val_origin) {
					throw new Exception($key . ' 必传', Error_Code::ERROR_PARAM);
				}
				$val = $this->params_type_check($key, $type, $val);
				break;
			case PARAM_NULL_NOT_EMPTY:
				if (false !== $val_origin) {
					if (empty($val)) {
						throw new Exception($key . ' 若传非空', Error_Code::ERROR_PARAM);
					}
					$val = $this->params_type_check($key, $type, $val);
				}
				break;
			case PARAM_NULL_EMPTY:
				if (false !== $val_origin && !empty($val)) {
					$val = $this->params_type_check($key, $type, $val);
				}
				break;
		}

		$this->param_list[$method][$key] = array($val_origin, $val);

		return $val;
	}

	protected function post_check($key, $check, $type = PARAM_TYPE_STRING, $xss_clean = true)
	{
		$val = $this->post($key, $xss_clean);

		return $this->params_check($key, $check, $type, $val, 'post');
	}

	protected function response_error($code, $info = null)
	{
		if (empty($info) && class_exists('Error_Code')) {
			$info = Error_Code::desc($code);
		}
		$data = $this->api_spec->gen_error($code, $info);

		return $this->response($data);
	}

	protected function response_object($object)
	{
		$data = $this->api_spec->gen_object($object);

		return $this->response($data);
	}

	protected function response_insert($lastid)
	{
		$data = $this->api_spec->gen_insert($lastid);

		return $this->response($data);
	}

	protected function response_list($list, $total = 0, $page = 1, $size = 10)
	{
		$data = $this->api_spec->gen_list($list, $total, $page, $size);

		return $this->response($data);
	}

	protected function response_update($affected_rows)
	{
		$data = $this->api_spec->gen_update($affected_rows);

		return $this->response($data);
	}

    protected function response_string($data){
        $data = $this->api_spec->gen_string($data);
        return $this->response($data);
    }

    protected function response_empty(){
        $data = $this->api_spec->gen_empty();
        return $this->response($data);
    }

	protected function get_request_params($method = null)
	{
		if (empty($method)) {
			$method = $_SERVER['REQUEST_METHOD'];
		}

		$method = strtolower($method);
		$params = $this->param_list[$method];

		if (empty($params)) {
			return array();
		}
		foreach (array_keys($params) as $key) {
			if (false === $params[$key][0]) {
				unset($params[$key]);
			} else {
				$params[$key] = $params[$key][1];
			}
		}

		return $params;
    }
}
