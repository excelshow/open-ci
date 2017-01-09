<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mcurl');
    }

    public function getuserinfo_by_uid($uid, $ext_mobile = 1)
    {
	    $params     = compact('uid', 'ext_mobile');
	    $requests   = array();
	    $requests[] = $this->request('api_host_open_user', 'user/get_by_id.json', $params);

	    return $this->result($requests);
    }

    public function list_addresss($uid, $page = 1, $size = 10)
    {
        $params     = compact('uid', 'page', 'size');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/list.json', $params);

        return $this->result($requests);
    }

    public function get_addresss($uid, $addrid)
    {
        $params     = compact("uid", "addrid");
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/get.json', $params);

        return $this->result($requests);
    }

    public function del_addresss($uid, $addrid)
    {
        $params     = compact("uid", "addrid");
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/delete.json', $params, "POST");

        return $this->result($requests);
    }

    public function setdefault_addresss($uid, $addrid)
    {
        $isdefault  = 1;
        $params     = compact("uid", "addrid", "isdefault");
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/update.json', $params, "POST");

        return $this->result($requests);
    }

    public function add_addresss($uid, $name, $addr, $mobile, $zipcode)
    {
        $params     = compact("uid", "name", "addr", "mobile", "zipcode");
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/add.json', $params, "POST");

        return $this->result($requests);
    }

    public function update_addresss($uid, $addrid, $name, $addr, $mobile, $zipcode)
    {
        $params     = compact("uid", "addrid", "name", "addr", "mobile", "zipcode");
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/shipping_address/update.json', $params, "POST");

        return $this->result($requests);
    }

    public function bind_mobile($uid, $mobile, $verified = 2)
    {
        $params     = compact("uid", "mobile", 'verified');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/bind_mobile.json', $params, "POST");

        return $this->result($requests);
    }

    public function user_login($unionid, $openid, $web_access_token, $source = USER_SOURCE_CONTEST)
    {
        $params     = compact('unionid', 'openid', 'web_access_token', 'source');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/login.json', $params, 'POST');

        return $this->result($requests);
    }

    /**
     * 依赖snsapi判断是否取用户信息
     */
    public function get_by_wxcode($apppk, $code, $snsapi)
    {
        $params     = compact('apppk', 'code', 'snsapi');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/get_by_wxcode.json', $params, 'GET');

        return $this->result($requests, false);
    }

    public function get_weixin_openid_by_wxcode($apppk, $code)
    {
        $params     = compact('apppk', 'code');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/get_weixin_openid_by_wxcode.json', $params, 'GET');

        return $this->result($requests, false);
    }

    public function bind_weixin($uid, $openid, $apppk)
    {
        $params     = compact('uid', 'openid', 'apppk');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/bind_weixin.json', $params, 'POST');

        return $this->result($requests);
    }

    public function get_by_mobile($mobile)
    {
        $params     = compact('mobile');
        $requests   = array();
        $requests[] = $this->request('api_host_open_user', 'user/get_by_mobile.json', $params, 'GET');

        return $this->result($requests, false);
    }
}
