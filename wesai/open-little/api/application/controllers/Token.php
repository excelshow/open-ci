<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 第三方测试 获取token类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Token extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Token_model');
        $this->load->model('GetToken_model');
    }

    /**
     * 获取数据库中已存在的token
     */
    public function get_token_get(){

        $token = $this->Token_model->getToken();
        if(empty($token)){
            return $this->response_error(Error_Code::ERROR_GET_TOKEN_FAILED);
        }

        return $this->response_object($token);
    }


    /**
     * 根据appid 获取新的token 存储在数据库中
     */
    public function update_token_post(){

        $token = $this->splic_signtoken();
        $result = $this->GetToken_model->curlToken($token);
        if(empty($result) || empty($result->access_token)){
            return $this->response_error(Error_Code::ERROR_CURL_TOKEN_FAILED);
        }
        $tokenStr = $result->access_token;
        $result = $this->Token_model->updateToken($tokenStr);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_UPDATE_TOKEN_FAILED);
        }

        return $this->response_update($result);
    }

    /**
     * 获取access_token 生成接口需要的token
     */
    private function splic_signtoken(){
        $sign = md5($this->timestamp . '-' . APP_SECRET);
        $str  = APP_ID . '-' . $this->timestamp . '-' . $sign;
        return base64_encode($str);
    }

    public function get_app_secret_get(){
        return $this->response_string(APP_SECRET);
    }
    public function get_sign_post(){
        $time = $this->post_check('time', PARAM_NOT_NULL_NOT_EMPTY);
        $sign = md5($time . '-' . APP_SECRET);
        return $this->response_string($sign);

    }
    public function get_token_post(){
        $time  = $this->post_check('time',  PARAM_NOT_NULL_NOT_EMPTY);
        $appid = $this->post_check('appid', PARAM_NOT_NULL_NOT_EMPTY);
        $sign  = $this->post_check('sign',  PARAM_NOT_NULL_NOT_EMPTY);

        $str = $appid . '-' . $time . '-' . $sign;
        return $this->response_string(base64_encode($str));

    }

}
