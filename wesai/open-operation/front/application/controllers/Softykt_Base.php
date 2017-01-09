<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . 'libraries/Curl.php';
require_once APPPATH . '/controllers/Error_Code.php';

abstract class Softykt_Base extends CI_Controller
{
    public $curl;

    public function __construct(){
        parent::__construct();
        $this->curl = new CI_Curl;
        //$this->load->helper('error');
        $this->load->model('softykt/Softykt_model');
    }



    public function getToken(){
        //获取数据库中一存储的token
        $result = $this->Softykt_model->getToken();
        if(!empty($result)){
            $rule_json = json_encode($result);
            $ruleList = json_decode($rule_json,true);
            $result = $ruleList['result'];
            if((time() - strtotime($result['utime'])) > 7200){
                //超时 重新获取 更新数据库
                $token = $this->curlToken();
                $uprow = $this->Softykt_model->updToken($result['pk_token'],$token);
                if($uprow){
                    return $token;
                }
            }else{
                return $result['token'];
            }
        }else{
            //首次获取数据库
            $token = $this->curlToken();
            $uprow = $this->Softykt_model->updToken(0,$token);
            if($uprow){
                return $token;
            }
        }
    }

    /**
     * 根据appid 获取access_token
     * @return str
     */
    public function curlToken(){
        $param = array(
            'appid'          => SOF_APPID,
            'softyktsecrect' => SOF_SOFTYKTSECRECT,
            'yoursex'        => 2,
        );

        $result = $this->curl->post($param, SOF_ACCESSTOKENURL);
        $info = json_decode($result,true);
        return $info['access_token'];
    }

    /**
     * 组成param
     */
    public function splitParam($param = array(), $url){
        $param['nonce'] = time();
        $param['timestamp'] = time();
        $param['access_token'] = $this->getToken();

        $sign = $this->get_signature($param);
        $param['signature'] = $sign;
        $result = $this->curl->post($param, $url);
        log_message('error',var_export($result,true));
        $info = json_decode($result,true);
        return $info;
    }


    /**
     * 签名生成算法
     * @param array API调用的请求参数集合的关联数组，不包含signature参数
     * @access_token string 通过密钥获取
     * @return sign
     */
    public function get_signature($param = array()){
        sort($param, 2);
        $str = implode($param);
        return strtoupper(md5($str));
    }

    public function onlycode(){
        return strtoupper(md5(time()));
    }

    public function returnError($error_code,$message = null){
        if($message == null){
            $message = Error_Code::desc($error_code);
        }
        $error = array(
            'error' => $error_code,
            'info' => $message,
        );
        return $error;
    }

}
