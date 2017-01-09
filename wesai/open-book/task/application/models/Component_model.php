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

    public function getAuthorizerInfo($authorizer_appid) {
        $params = array();
        $params['authorizer_appid'] = $authorizer_appid;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/get.json', $params, 'GET');

        return $this->result($requests);
    }

    public function getJsapiConfig($apppk, $url) {
        $params = array();
        $params['apppk'] = $apppk;
        $params['url'] = $url;

        $requests = array();
        $requests[] = $this->request('api_host_open_weixin', 'component/authorizer/get_jsapi_config.json', $params, 'GET');

        return $this->result($requests);
    }




}
