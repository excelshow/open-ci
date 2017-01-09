<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . 'libraries/Curl.php';
include_once 'Error_Code.php';

class Softykt_Base extends MY_Controller
{
    public $curl;

    public function __construct(){
        parent::__construct();
        $this->curl = new CI_Curl;
        $this->load->model('softykt/Softykt_model');
        $this->load->helper('diy');
    }

    protected function getCorpInfo($corp_id){
        $corp = $this->Softykt_model->getCorpInfo($corp_id);
        if (empty($corp)) {
            return $this->response_error(Error_Code::ERROR_CORP_SOFTYKT_INFO_NOT_FOUND);
        }

        return $corp;
    }

    /**
     * 根据appid 获取access_token
     * @return str
     */
    public function getSoftyToken($appid, $softyktsecrect){
        $param = array(
            'appid'          => $appid,
            'softyktsecrect' => $softyktsecrect,
            'yoursex'        => 2,
        );

        $result = $this->curl->post($param, SOF_ACCESSTOKEN_URL);
        $info = json_decode($result);
        if(empty($info) || $info->rcode != SOF_API_CODE_SUCCESS || empty($info->access_token)){
            log_message('error', '获取金飞鹰token信息错误  '.json_encode($param).' rt='.json_encode($info));
            return false;
        }
        return $info->access_token;
    }

    public function callSoftyktProductApi($param = array()){
        $param = $this->assemParam($param);
        $result = $this->curl->post($param, SOF_SCENIC_GET_PRODUCT_URL);
        $info = json_decode($result);
        if(empty($info) || $info->rcode != SOF_API_CODE_SUCCESS){
            log_message('error', '获取金飞鹰商品信息错误  '.json_encode($param).' rt='.json_encode($info));
            return false;
        }
        log_message_to_file('softykt_products',var_export($result,true));

        return $info;
    }

    public function callSoftyktPlaceOrderApi($param = array()){
        $param = $this->assemParam($param);
        $result = $this->curl->post($param, SOF_SCENIC_PUT_ORDER_URL);

        log_message_to_file('softykt_place_order',json_encode($result));

        $info = json_decode($result);
        if(empty($info)){
            log_message('error', '金飞鹰下单返回信息错误  '.json_encode($param).' rt='.json_encode($info));
            return false;
        }
        if($info->rcode != SOF_API_CODE_SUCCESS){
            log_message('error', '金飞鹰下单返回信息状态错误  '.json_encode($param).' rt='.json_encode($info));
            return $info;
        }

        return $info;
    }

    public function callSoftyktOrderSmsApi($param = array()){
        $param = $this->assemParam($param);
        $result = $this->curl->post($param, SOF_DISTRIBUTOR_RESMS_URL);

        $info = json_decode($result);
        if(empty($info)){
            log_message('error', '金飞鹰重发消费码返回信息错误  '.json_encode($param).' rt='.json_encode($info));
            return false;
        }
        if($info->rcode != SOF_API_CODE_SUCCESS){
            log_message('error', '金飞鹰重发消费码返回信息状态错误  '.json_encode($param).' rt='.json_encode($info));
            return $info;
        }
        log_message_to_file('softykt_order_sms',var_export($result,true));

        return $info;
    }
    private function assemParam($param){
        $param['nonce'] = time();
        $param['timestamp'] = time();

        $sign = $this->get_signature($param);
        $param['signature'] = $sign;
        return $param;
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

    public function onlycode($no){
        return strtoupper(md5($no));
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

    protected function checkPageParams(&$pageNumber, &$pageSize, $maxSize = 100)
    {
        $pageNumber = intval($pageNumber);
        $pageSize   = intval($pageSize);
        $pageNumber >= 0 or $pageNumber = 1;
        $pageSize >= 0 or $pageSize = 20;
        $pageSize < $maxSize or $pageSize = $maxSize;
    }

}
