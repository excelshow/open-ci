<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取token信息 model
 */
class GetToken_model extends DIY_Model {


    /**
     * 根据token获取access_token
     * @param $token
     * *****$is_api  记得传递false
     * @return bool|object
     */
    public function curlToken($token){
        $headerParam = array(
            'Authorization: Basic '.$token
        );
        $requests[] = $this->request('api_host_open_wesai_zhty', '/access_token', array(), 'POST',$headerParam);
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }

	
}
