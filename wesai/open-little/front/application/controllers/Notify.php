<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

/**
 * 通知类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Notify extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Token_model');
        $redisConfig = $this->config->item('redismq');
        $this->load->library('Redis_List_Client', $redisConfig);
    }

    public function notify(){
        $raw_post_data = $this->check();

        $this->setRedis($raw_post_data);

        echo 'ok';exit;
    }

    private function check(){
        $token = isset($_SERVER['HTTP_AUTHORIZATION'])?$_SERVER['HTTP_AUTHORIZATION']:'';
        if(empty($token)){
            return $this->displayError(Error_Code::ERROR_TOKEN_NOT_EMPTY);
        }
        $raw_post_data = file_get_contents('php://input', 'r');
        log_message('error', $raw_post_data);

        //$raw_post_data = '{"verify_code":"013907636738","max_verify":"1","verify_number":"1","created_at":"2016-12-13 12:00:26","client_id":"460355fed32a08953f82fb43d6064a27","time":1482050053,"sign":"30a7b0f946750af1eae69bb50f7f0758","action":"order.verify_code.update"}';
        if(empty($raw_post_data)){
            return $this->displayError(Error_Code::ERROR_PARAM);
        }
        $dataInfo = json_decode($raw_post_data,true);
        if(empty($dataInfo['sign'])){
            return $this->displayError(Error_Code::ERROR_SIGN_NOT_EMPTY);
        }
        $this->check_sign($dataInfo['sign'], $dataInfo['time']);
        $this->check_token($dataInfo['sign'], $dataInfo['time'], $dataInfo['client_id'], $token);
        unset($dataInfo['sign']);
        unset($dataInfo['time']);
        unset($dataInfo['client_id']);
        return $dataInfo;

    }

    //md5($TIME-系统分配的客户秘钥)
    private function check_sign($sign, $time){
        $signInfo = $this->Token_model->get_sign($time);
        if($signInfo->error < 0){
            return $this->displayError(Error_Code::ERROR_CHECK_SIGN_FAILED);
        }
        $actualSign = $signInfo->result;
        if($actualSign != $sign){
            return $this->displayError(Error_Code::ERROR_CHECK_SIGN_FAILED);
        }
    }

    //base64_encode($CLIENT_ID-$TIME-$SIGN)
    private function check_token($sign, $time, $appid, $token){
        $tokenInfo = $this->Token_model->get_token($time, $appid, $sign);
        if($tokenInfo->error < 0){
            return $this->displayError(Error_Code::ERROR_CHECK_TOKEN_FAILED);
        }
        $actualToken = $tokenInfo->result;
        $token = substr($token,6);
        if($actualToken != $token){
            return $this->displayError(Error_Code::ERROR_CHECK_TOKEN_FAILED);
        }
    }

    /**
     * 存储到redis
     * @param $param
     * @param $errormsg
     */
    private function setRedis($param){
        $ret = $this->redis_list_client->LeftPush(MQ_TOPIC_EXT_NOTIFY, json_encode($param));
        log_message_to_file("notify", $ret . ' pm=' . json_encode($param));
        if (empty($ret)) {
            log_message_v2(
                'error',
                array(
                    'msg'     => 'set open-little to redis failed',
                    'file'    => __FILE__,
                    'line'    => __LINE__,
                    'param'   => $param,
                )
            );
        }
    }



}
