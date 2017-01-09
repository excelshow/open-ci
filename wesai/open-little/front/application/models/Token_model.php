<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取token信息 model
 */
class Token_model extends DIY_Model {


    /**
     * 根据token获取access_token
     * @return bool|object
     */
    public function getToken(){

        $requests[] = $this->request('api_host_open_little', 'token/get_token.json', array(), 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 获取密钥
     */
    public function get_secret(){
        $requests[] = $this->request('api_host_open_little', 'token/get_app_secret.json', array(), 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 获取sign
     */
    public function get_sign($time){
        $param = array(
            'time' => $time
        );
        $requests[] = $this->request('api_host_open_little', 'token/get_sign.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 获取token
     */
    public function get_token($time, $appid, $sign){
        $param = array(
            'time' => $time,
            'appid'=> $appid,
            'sign' => $sign
        );
        $requests[] = $this->request('api_host_open_little', 'token/get_token.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }


	
}
