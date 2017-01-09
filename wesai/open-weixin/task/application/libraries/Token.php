<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * Token 
 */
class Token 
{
    public function get_access_token($appid) {
        $params = array();
        $params['appid'] = $appid;

        $CI = & get_instance();
        $CI->load->library('curl');
        $ret = $CI->curl->capture($CI->config->item('api_host_token'),'token/get.json', 'GET', $params);

        if(!empty($ret)){
            $ret_json = $ret;
            if($ret_json && isset($ret_json->result)){
                return $ret_json->result;
            }
        }
        return false;
    }

    public function get_jsapi_ticket($appid) {
        $params = array();
        $params['appid'] = $appid;

        $CI = & get_instance();
        $CI->load->library('curl');
        $ret = $CI->curl->capture($CI->config->item('api_host_token'),'ticket/get.json', 'GET', $params);

        if(!empty($ret)){
            $ret_json = $ret;
            if($ret_json && isset($ret_json->result)){
                return $ret_json->result; 
            }
        }
        return false;
    }


}
