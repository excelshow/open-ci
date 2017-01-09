<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Oauth2.0 的Client Credentials 模式
 *  不下发REFRESH_TOKEN
 */
require_once APPPATH . '/controllers/Base.php';
require_once APPPATH . '/controllers/Error_Code.php';

class Access_token extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('error');
        $this->load->library('OAuth2');
        $this->load->model('Corp_user_model');
        $this->load->model('Corp_user_openapi_model');
    }

    public function index(){
        $authorization = isset($_SERVER['HTTP_AUTHORIZATION'])?$_SERVER['HTTP_AUTHORIZATION']:'';
        // 参数错误
        if(empty($authorization)){
            $error = show_error_info('00', Error_Code::ERROR_AUTHORIZATION_NOT_EXISTS, Error_Code::desc(Error_Code::ERROR_AUTHORIZATION_NOT_EXISTS), false);
            echo json_encode($error);
            exit;
        }

        // 参数错误
        $auth_info = $this->oauth2->decodeBasicToken($authorization);
        if(empty($auth_info)){
            $error = show_error_info('00', Error_Code::ERROR_AUTHORIZATION, Error_Code::desc(Error_Code::ERROR_AUTHORIZATION), false);
            echo json_encode($error);
            exit;
        }
        
        // 时间校验 超过5分钟不认
        if($auth_info['time'] < time()-300){
            $error = show_error_info('00', Error_Code::ERROR_AUTHORIZATION_TIMEOUT, Error_Code::desc(Error_Code::ERROR_AUTHORIZATION_TIMEOUT), false);
            echo json_encode($error);
            exit;
        }

        // 校验失败
        $check = $this->checkBasicToken($auth_info, $openapi_info);
        if(empty($check)){
            //
        }

        $access_token = $this->generateAccessToken($openapi_info);

        // access_token 入库
        $fk_corp = $openapi_info->fk_corp;
        $fk_corp_user = $openapi_info->fk_corp_user;
        $fk_corp_user_openapi = $openapi_info->pk_corp_user_openapi;
        $access_time = time();
        $result = $this->Corp_user_openapi_model->setOpenapiAccessToken($fk_corp, $fk_corp_user, $fk_corp_user_openapi, $access_token, $access_time, EXPIRES_IN);
        if(empty($result) || !isset($result->error) || $result->error != '0'){
            log_message('error', 'Corp_user_openapi_model setOpenapiAccessToken error');
            $error = show_error_info('00', Error_Code::ERROR_TOKEN_SET_ERROR, Error_Code::desc(Error_Code::ERROR_TOKEN_NOT_EXISTS), false);
            echo json_encode($error);
            exit;
        }

        $data = array(
            'access_token' => $access_token,
            'expires_in' => EXPIRES_IN
        );

        $this->displayAccessToken($data);
    }

    private function displayAccessToken($data){
        // 设置header头信息
        header('Pragma: no-cache');
        header('Cache-Control: no-store');
        header('Server: open-api');
        header('X-Powered-By: open-api');
        echo json_encode($data);
        exit;
    }

    private function generateAccessToken($openapi_info){
        $client_id = $openapi_info->appid;
        $secret = $openapi_info->appsecret;
        $access_token = $this->oauth2->makeAccessToken($client_id, $secret);

        return $access_token;
    }

    private function checkBasicToken($auth_info, &$openapi_info){
        $client_id = $auth_info['client_id'];
        $time = $auth_info['time'];
        $sign = $auth_info['sign'];

        $corp_user = $this->Corp_user_model->getOpenapiByAppid($client_id);
        if(empty($corp_user) || empty($corp_user->result) || empty($corp_user->result->appsecret)){
            log_message('error', 'corp_user getOpenapiByAppid error');
            return false;
        }

        $openapi_info = $corp_user->result;
        $secret = $corp_user->result->appsecret;
        $sign_check = $this->oauth2->getSecretSign($time, $secret);
        if($sign == $sign_check){
            return true;
        }
        return false;
    }

    /**
     * get_basic_token
     *  测试用
     * 
     * @access public
     * @return void
     */
    public function get_basic_token(){
        $client_id = $this->input->get_post('client_id', true);
        $secret = $this->input->get_post('secret', true);
        $token = $this->oauth2->makeBasicToken($client_id, $secret);
        echo $token;
    }
}

