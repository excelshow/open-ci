<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 金飞鹰
 */
class Softykt_model extends DIY_Model {

    /**
     * 获取存储的token
     */
    public function getToken(){
        //获取数据库中已有的token
        $requests[] = $this->request('api_host_open_operation', 'softykt/softykt/get_token.json', $param = array(), 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 更新数据库中的token
     */
    public function updToken($id,$token){
        $param = array(
            'token_id' => $id,
            'token' => $token
        );
        $requests[] = $this->request('api_host_open_operation', 'softykt/softykt/modify.json', $param, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }

	
}
