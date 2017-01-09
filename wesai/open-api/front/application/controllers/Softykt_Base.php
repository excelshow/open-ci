<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'Error_Code.php';

abstract class Softykt_Base extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->helper('diy');
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
