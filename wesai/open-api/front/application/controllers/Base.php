<?php defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/controllers/Error_Code.php';
require_once APPPATH . '/helpers/error_helper.php';
/**
 * Base 
 * 错误码前缀规范：
 *      外部系统编号(没有调用外部的时候)
 *      外部系统编号-内部系统编号(调用内部错误的时候)
 *      非业务错误错误码都未 ERROR_SYSTEM，但是内部需要记录具体错误码，参考上面
 * 参数校验和获取逻辑有些复杂
 *      注意getInParams/getOutParams的递归调用
 *      开发逻辑梳理流程：1 只有一层参数 2 第二层是对象的调用 3 第二层是数组的调用
 * 系统对外接口规范
 *      系统名称/api.json 【系统名称是系统内部的标识】
 * @uses CI
 * @uses _Controller
 */
class Base extends CI_Controller {
    private static $class = '';
    private static $class_api = '';

    // 错误码前缀
    private $error_pre_openapi = 0;
    private $error_pre_innserapi = 0;
    // 是否开始业务流程
    private $biz_start = 0;

    // 企业ID
    private $pk_corp = 0;
    // 用户ID
    private $pk_corp_user = 0;
    // client secret
    private $secret = '';

    // children的数据
    private $data_in_childrens = null;
    private $data_out_childrens = null;
    
    public function __call($name, $arguments){
        $this->checkAccessToken();
        $this->checkRequestSign();
        $api = $this->getBySystemApi();
        // 请求方法校验
        $this->checkRequestMethod($api);
        // client 调用权限校验
        $this->checkPermission($api);
        // 入参校验
        $params_in = $this->getParamsIn($api);
        //print_r($params_in);
        $this->biz_start = 1;
        // 请求内部接口
        $result = $this->callInternalApi($api, $params_in);
        //var_dump($result);
        // 处理、输出
        $params_out = $this->getParamsOut($api, $result);

        echo json_encode($params_out);
        exit;
    }

    public function checkAccessToken(){
        // 查找access token对应的用户信息
        $this->pk_corp_user = 0;
        $token = isset($_SERVER['HTTP_TOKEN'])?$_SERVER['HTTP_TOKEN']:'';
        if(empty($token)){
            $error = show_error_info(
                        $this->getErrorPre(), 
                        Error_Code::ERROR_TOKEN_NOT_EXISTS, 
                        Error_Code::desc(Error_Code::ERROR_TOKEN_NOT_EXISTS), 
                        false);
            echo json_encode($error);
            exit;
        }
        $this->load->model('Corp_user_openapi_model');
        $corp_user_openapi = $this->Corp_user_openapi_model->getOpenapiAccessToken($token);
        if(empty($corp_user_openapi) || empty($corp_user_openapi->result) || empty($corp_user_openapi->result->access_token)){
            $error = show_error_info(
                        $this->getErrorPre(), 
                        Error_Code::ERROR_TOKEN_ERROR, 
                        Error_Code::desc(Error_Code::ERROR_TOKEN_ERROR));
            echo json_encode($error);
            exit;
        }
        $access_token = $corp_user_openapi->result;

        $this->pk_corp_user = $corp_user_openapi->result->fk_corp_user;
        $this->pk_corp = $corp_user_openapi->result->fk_corp;
    }

    public function checkRequestSign(){
    }

    private function checkRequestMethod($api){
        $method = $api->api->method;
        $http_method = $this->getRequestMethod();
        if($http_method != $method){
            $error = show_error_info(
                        $this->getErrorPre(), 
                        Error_Code::ERROR_HTTP_METHOD, 
                        Error_Code::desc(Error_Code::ERROR_HTTP_METHOD), 
                        false);
            echo json_encode($error);
            exit;
        }
    }

    private function checkPermission($api){
        //
    }

    private function getParamsOut($api, $result){
        $this->data_out_childrens = null;
        $params_out = array();
        $io_type = API_PARAMS_IO_TYPE_OUT;
        if(isset($api->apiParams->$io_type)){
            $api_params_out = $api->apiParams->$io_type;
        }
        if(!empty($api_params_out)){
            // 输出参数处理 按着新的逻辑梳理一遍
            $this->getOutParams($api_params_out, $params_out, $result);
        }
        // 出参没有必要清理null

        return $params_out;
    }

    /**
     * getOutParams
     * 
     * 获取出参(跟getInParams类似)
     *  . 逻辑很复杂，总体来说，整理好数据和参数配置，进行获取
     *  . 要用递归逻辑去理解
     * @param mixed $api_params_out 
     * @param mixed $params_out 
     * @param bool $data 
     * @access private
     * @return void
     */
    private function getOutParams($api_params_out, &$params_out, $data){
        //print_r($api_params_out);
        foreach($api_params_out as $api_param){
            $param_type = $api_param->param_type;
            if(isset($api_param->children)){
                // 这里的val暂时用不到
                $val = $this->getOutParamVal($api_param, $data);
                //print_r($this->data_out_childrens[$api_param->pk_api_params]);
                $this->getOutParams($api_param->children, $params_out[$api_param->param_name], $this->data_out_childrens[$api_param->pk_api_params]);
            }else{
                if($api_param->parent_id && is_array($this->data_out_childrens[$api_param->parent_id])){
                    $i = 0;
                    foreach($data as $d){
                        $params_out[$i][$api_param->param_name] = $this->getOutParamVal($api_param, $d);
                        $i++;
                    }
                }else{
                    $val = $this->getOutParamVal($api_param, $data);
                    $params_out[$api_param->param_name] = $val;
                }
            }
        }
    }

    private function getOutParamVal($api_param, $data){
        $param_name_internal = $api_param->param_name_internal;
        $param_null = $api_param->param_null;
        $param_type = $api_param->param_type;

        // 参数校验
        // 输入参数才做校验

        // 参数值获取
        $val = null;
        if($api_param->parent_id){
            $data_out_children = $this->data_out_childrens[$api_param->parent_id];
            if(isset($data_out_children) && isset($data_out_children->$param_name_internal)){
                $val = $data_out_children->$param_name_internal;
            }
            if(is_array($data_out_children)){
                // 这里需要再处理单个值
                $val = $data->$param_name_internal;
                //var_dump($api_param, $data);
            }
        }else{
            if(isset($data->$param_name_internal)){
                $val = $data->$param_name_internal;
            }
            // 如果没有父节点，但是是object/array，则null
            if($param_type == API_PARAMS_TYPE_OBJECT || $param_type == API_PARAMS_TYPE_ARRAY){
                $val = null;
            }
        }

        // 所有数据放入data_out_childrens
        if(isset($data->$param_name_internal)){
            $this->data_out_childrens[$api_param->pk_api_params] = $data->$param_name_internal;
        }

        // 值校验
        // 输入参数才做校验

        return $val;
    }

    /*{{{ 第一版逻辑，暂时保留*/
    /*
    private function getOutParams($api_params_out, &$params_out, $result){
        // 注意 param_name_internal 到 param_name 的转换
        foreach($api_params_out as $api_param){
            if(isset($api_param->children)){
                $val = $this->getOutParamVal($api_param, $result);
                // 下面两行代码不可换
                $this->data_out_childrens[$api_param->pk_api_params] = $val;
                $this->getOutParams($api_param->children, $params_out[$api_param->param_name], $val);
                // 清理null数据
                if($params_out[$api_param->param_name] === null){
                    unset($params_out[$api_param->param_name]);
                }
            }else{
                // 字段是数组的时候，输出转换逻辑
                if($api_param->parent_id && is_array($this->data_out_childrens[$api_param->parent_id])){
                // 下面的输出不要删除，字段是数组的时候的测试关键点
                //print_r($api_param);print_r($result);var_dump($params_out);
                    $i = 0;
                    foreach($result as $r){
                        $params_out[$i][$api_param->param_name] = $this->getOutParamVal($api_param, $r);
                        $i++;
                    }
                //var_dump($params_out);
                }else{
                    $val = $this->getOutParamVal($api_param, $result);
                    $params_out[$api_param->param_name] = $val;
                }
            }
        }
    }

    private function getOutParamVal($api_param, $result){
        $param_name_internal = $api_param->param_name_internal;
        if($api_param->parent_id){
            $param_out_children = $this->data_out_childrens[$api_param->parent_id];
        }

        // 忽略param_type,param_null 判断
        // 参数校验-- 不需要
        // 参数值获取
        $val = null;
        if(isset($param_out_children) && isset($param_out_children->$param_name_internal)){
            $val = $param_out_children->$param_name_internal;
        }else{
            // 参数异常
            if(!isset($result->$param_name_internal)){
                $error = show_error_info(
                            $this->getErrorPre(), 
                            Error_Code::ERROR_PARAM, 
                            Error_Code::desc(Error_Code::ERROR_PARAM).' [ '. $api_param->param_name.' ]');
                echo json_encode($error);
                exit;
            }else{
                $val = $result->$param_name_internal;
            }
        }
        // 值校验-- 不需要

        return $val;
    }
    */
    /*}}}*/

    private function getParamsIn($api){
        $this->data_in_childrens = null;
        $params_in = array();
        $io_type = API_PARAMS_IO_TYPE_IN;
        if(isset($api->apiParams->$io_type)){
            $api_params_in = $api->apiParams->$io_type;
        }
        if(!empty($api_params_in)){
            // 提取入参参数
            $data = $this->getRequestData($api_params_in);
            $this->getInParams($api_params_in, $params_in, $data);
        }

        // 构建请求参数
        foreach($params_in as $k=>&$pa){
            if($pa === null){
                unset($params_in[$k]);
            }else{
                if(is_array($pa) || is_object($pa)){
                    $pa = json_encode($pa);
                }
            }
        }

        return $params_in;
    }

    /**
     * getInParams
     * 
     * 获取入参
     *  . 逻辑很复杂，总体来说，整理好数据和参数配置，进行获取
     *  . 要用递归逻辑去理解
     * @param mixed $api_params_in 
     * @param mixed $params_in 
     * @param bool $data 
     * @access private
     * @return void
     */
    private function getInParams($api_params_in, &$params_in, $data){
        //print_r($api_params_in);
        foreach($api_params_in as $api_param){
            if($api_param->param_name == 'corp_id'){
                $params_in[$api_param->param_name_internal] = $this->pk_corp;
                continue;
            }
            $param_type = $api_param->param_type;
            if(isset($api_param->children)){
                // 这里的val暂时用不到
                $val = $this->getInParamVal($api_param, $data);
                //print_r($this->data_in_childrens);
                $this->getInParams($api_param->children, $params_in[$api_param->param_name_internal], $this->data_in_childrens[$api_param->pk_api_params]);
            }else{
                if($api_param->parent_id && is_array($this->data_in_childrens[$api_param->parent_id])){
                    $i = 0;
                    foreach($data as $d){
                        $params_in[$i][$api_param->param_name_internal] = $this->getInParamVal($api_param, $d);
                        $i++;
                    }
                }else{
                    $val = $this->getInParamVal($api_param, $data);
                    $params_in[$api_param->param_name_internal] = $val;
                }
            }
        }
    }

    private function getInParamVal($api_param, $data){
        $io_type = $api_param->io_type;
        $param_name = $api_param->param_name;
        $param_null = $api_param->param_null;
        $param_type = $api_param->param_type;
        $method = $this->getRequestMethod();

        // 参数校验
        $param_null_error = 0;
        // 输入参数才做校验
        if($io_type == API_PARAMS_IO_TYPE_IN){
            if($param_null == API_PARAMS_NULL_NOT){
                // 如果有父字段，则一定不直接从$_GET/$_POST中获取
                if($api_param->parent_id){
                    // 如果有children则是json，需校验json字符串
                    $data_in_children = $this->data_in_childrens[$api_param->parent_id];
                    if(isset($param_in_children) && !isset($param_in_children->$param_name)){
                        $param_null_error = 1;
                    }
                }else{
                    if(!isset($data->$param_name)){
                        $param_null_error = 1;
                    }
                }
            }
        }

        // 参数值获取
        $val = null;
        if($api_param->parent_id){
            $data_in_children = $this->data_in_childrens[$api_param->parent_id];
            if(isset($data_in_children) && isset($data_in_children->$param_name)){
                $val = $data_in_children->$param_name;
            }
            if(is_array($data_in_children)){
                // 这里需要再处理单个值
                $val = $data->$param_name;
                //var_dump($api_param, $data);
            }
        }else{
            if(isset($data->$param_name)){
                $val = $data->$param_name;
            }
            // 如果没有父节点，但是是object/array，则null
            if($param_type == API_PARAMS_TYPE_OBJECT || $param_type == API_PARAMS_TYPE_ARRAY){
                $val = null;
            }
        }

        // 所有数据放入data_in_childrens
        if(isset($data->$param_name)){
            $this->data_in_childrens[$api_param->pk_api_params] = $data->$param_name;
            //print_r($this->data_in_childrens);
        }

        // 值校验
        // 输入参数才做校验
        if($io_type == API_PARAMS_IO_TYPE_IN){
            if( $param_null_error || 
                ($val !== null && $param_type == API_PARAMS_TYPE_NUMBER && !is_numeric($val)) ){
                $error = show_error_info(
                            $this->getErrorPre(), 
                            Error_Code::ERROR_PARAM, 
                            Error_Code::desc(Error_Code::ERROR_PARAM).' [ '. $api_param->param_name . ' ]', 
                            false);
                echo json_encode($error);
                exit;
            }
        }

        return $val;
    }

    private function callInternalApi($api, $params_in){
        $this->load->model('Base_model');
        $api_internal_system = $api->api->api_internal_system;
        $api_internal = $api->api->api_internal;

        $internal_host_config = $this->getInternalHostConfig($api_internal_system);
        if(empty($internal_host_config)){
            $error = show_error_info(
                        $this->getErrorPre(), 
                        Error_Code::ERROR_INTERNAL_HOST_CONFIG, 
                        Error_Code::desc(Error_Code::ERROR_INTERNAL_HOST_CONFIG).' [ '. $api_internal_system. ' ]');
            echo json_encode($error);
            exit;
        }
        $request_method = strtoupper($_SERVER['REQUEST_METHOD']);

        // 构建model
        $model_name = 'Internal_model';
        eval("class $model_name extends Base_model {}");
        $this->load->model($model_name);
        $result = $this->$model_name->$api_internal($params_in, $internal_host_config, $request_method);
        $this->checkInnerApiResult($result);

        return $result;
    }

    private function getBySystemApi(){
        $this->load->model('Open_api_model');
        // 查看缓存
        // 没有缓存，再查询接口（参数调整，要同步更新缓存）
        $api_system = $this->getApiSystemByClass(self::$class);
        $api = self::$class_api;
        $method = $this->getRequestMethod();
        if(empty($api_system) || empty($api) || empty($method)){
            $error = show_error_info($this->getErrorPre(), Error_Code::ERROR_PARAM, Error_Code::desc(Error_Code::ERROR_PARAM), false);
            echo json_encode($error);
            exit;
        }

        // 确认错误码前置标示
        $this->error_pre_openapi = $api_system;

        //var_dump($api_system, $api, $method);
        $system_api = $this->Open_api_model->getBySystemApi($api_system, $api, $method);
        $this->checkInnerApiResult($system_api);
        //print_r($system_api);
        // 接口状态判断
        if(empty($system_api->result) || empty($system_api->result->api) || 
            (isset($system_api->result->api->state) && $system_api->result->api->state != OPEN_API_STATE_OK) ){

            $error = show_error_info($this->getErrorPre(), Error_Code::ERROR_CONTROLLER_NOT_EXISTS, Error_Code::desc(Error_Code::ERROR_CONTROLLER_NOT_EXISTS));
            echo json_encode($error);
            exit;
        }
        $system_api = $system_api->result;

        return $system_api;
    }

    /**
     * checkInnerApiResult
     * 
     * @param mixed $result 
     * @access private
     * @return void
     */
    private function checkInnerApiResult($result){
        if(empty($result) || !isset($result->error)){
            $error = show_error_info($this->getErrorPre(), Error_Code::ERROR_SYSTEM, Error_Code::desc(Error_Code::ERROR_SYSTEM));
            echo json_encode($error);
            exit;
        }
        // 错误了
        if($result->error < 0){
            // 调用业务的话，error <= -100 都是业务错误，其他都是 系统异常
            if($this->biz_start && ($result->error <= -100 || $result->error == -1)){
                $error = show_error_info($this->getErrorPre($result->error), $result->error, $result->info);
                echo json_encode($error);
                exit;
            }else{
                $error = show_error_info($this->getErrorPre(), Error_Code::ERROR_SYSTEM, Error_Code::desc(Error_Code::ERROR_SYSTEM));
                echo json_encode($error);
                exit;
            }
        }
    }

    /**
     * getErrorPre
     * 
     * 用于构建对外的标准的错误码
     * @access private
     * @return void
     */
    private function getErrorPre($error = 0){
        //if($this->biz_start && $error < -100){
        // 不加error 可以看出是不是内部api返回的错误吗
        if($this->biz_start){
            return $this->error_pre_openapi.$this->error_pre_innserapi;
        }else{
            return '00';
        }
    }

    /**
     * getInternalHostConfig
     *  根据内部系统id获取其host配置
     * @param mixed $api_internal_system 
     * @access private
     * @return void
     */
    private function getInternalHostConfig($api_internal_system){
        global $INTERNAL_API_SYSTEM_HOST_CONFIG_LIST;
        if(isset($INTERNAL_API_SYSTEM_HOST_CONFIG_LIST[$api_internal_system])){
            return $INTERNAL_API_SYSTEM_HOST_CONFIG_LIST[$api_internal_system];
        }
        return '';
    }

    private function getApiSystemByClass($class){
        global $OPEN_API_SYSTEM_NAME_LIST;
        $class = strtolower($class);
        if(isset($OPEN_API_SYSTEM_NAME_LIST[$class])){
            return $OPEN_API_SYSTEM_NAME_LIST[$class];
        }
        return 0;
    }
  
    private function getRequestData($api_params_in){
        $data = array();
        $method = $this->getRequestMethod();
        foreach($api_params_in as $api_param){
            if($method == API_METHOD_GET){
                $val = $this->input->get($api_param->param_name, true);
            }else{
                $val = $this->input->get_post($api_param->param_name, true);
            }
            if($api_param->param_type == API_PARAMS_TYPE_OBJECT || $api_param->param_type == API_PARAMS_TYPE_ARRAY){
                $val = json_decode($val);
            }
            $data[$api_param->param_name] = $val;
        }
        return (object)$data;
    }

    private function getRequestMethod(){
        global $OPEN_API_METHOD_LIST;
        $request_method = strtolower($_SERVER['REQUEST_METHOD']);
        if(isset($OPEN_API_METHOD_LIST[$request_method])){
            return $OPEN_API_METHOD_LIST[$request_method];
        }
        return 0;
    }

    public static function handler($RTR){
        // 业务调用
        $class = ucfirst($RTR->class);
        $uri_string = $RTR->uri->uri_string;
        // 截取api出来
        $class_api = substr($uri_string, strlen($class)+1);

        self::$class = $class;
        self::$class_api = $class_api;

        $object = new $class();
        call_user_func(array($object, $class_api));

        exit;
    }

}
