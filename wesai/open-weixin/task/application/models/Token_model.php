<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Token model
 *
 * @author like@wesai.com.cn
 * @date 2016-01-14
 */
class Token_model extends My_Model
{
    public function get_db(){
    }

    public function get($appid){
        $this->load->library('AccessToken');
        $token = AccessToken::getInstance()->load_access_token($appid);

        return $token;
    }
}
