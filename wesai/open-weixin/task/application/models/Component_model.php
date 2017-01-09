<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Component_model extends MY_Model
{
	public function __construct() {
        parent::__construct();
    }

    public function get($appid) {
        $params = array();
        $params['appid'] = $appid;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/app/get.json', $params, 'GET');

        return $this->result($requests);
    }

    public function setAccessToken($appid, $appsecret, $access_token, $name) {
        $params = array();
        $params['appid'] = $appid;
        $params['appsecret'] = $appsecret;
        $params['name'] = $name;
        $params['access_token'] = $access_token;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/app/set.json', $params, 'POST');

        return $this->result($requests);
    }

    public function refreshAuthorizerToken($appid, $access_token, $refresh_token) {
        $params = array();
        $params['authorizer_appid'] = $appid;
        $params['authorizer_access_token'] = $access_token;
        $params['authorizer_refresh_token'] = $refresh_token;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/refresh_token.json', $params, 'POST');

        return $this->result($requests);
    }

    public function refreshAuthorizerJsapiTicket($appid, $jsapi_ticket) {
        $params = array();
        $params['authorizer_appid'] = $appid;
        $params['authorizer_jsapi_ticket'] = $jsapi_ticket;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/refresh_jsapi_ticket.json', $params, 'POST');

        return $this->result($requests);
    }

    public function list_will_refresh($count, $apppk = 0, $refresh_time) {
        $params = array();
        $params['count'] = $count;
        $params['apppk'] = $apppk;
        $params['refresh_time'] = $refresh_time;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/list_will_refresh.json', $params, 'GET');

        return $this->result($requests);
    }

    public function getAuthorizerInfo($authorizer_appid) {
        $params = array();
        $params['authorizer_appid'] = $authorizer_appid;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/get.json', $params, 'GET');

        return $this->result($requests);
    }




}
