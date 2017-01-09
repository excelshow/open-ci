<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Component_model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function set($appid, $verify_ticket)
	{
		$params                  = array();
		$params['appid']         = $appid;
		$params['verify_ticket'] = $verify_ticket;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/app/set.json', $params, 'POST');

		return $this->result($requests);
	}

	public function get($appid)
	{
		$params          = array();
		$params['appid'] = $appid;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/app/get.json', $params, 'GET');

		return $this->result($requests);
	}

	public function setAuthorizerToken($appid, $access_token, $refresh_token, $func_info, $fk_component_app, $fk_corp)
	{
		$params                             = array();
		$params['authorizer_appid']         = $appid;
		$params['authorizer_access_token']  = $access_token;
		$params['authorizer_refresh_token'] = $refresh_token;
		$params['func_info']                = $func_info;
		$params['fk_component_app']         = $fk_component_app;
		$params['fk_corp']                  = $fk_corp;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/set.json', $params, 'POST');

		return $this->result($requests);
	}

	public function getAuthorizerInfo($authorizer_appid)
	{
		$params                     = array();
		$params['authorizer_appid'] = $authorizer_appid;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/get.json', $params, 'GET');

		return $this->result($requests);
	}

	public function setAuthorizerInfo($authorizer_appid, $info)
	{
		$params                      = array();
		$params['authorizer_appid']  = $authorizer_appid;
		$params['nick_name']         = $info->authorizer_info->nick_name;
		$params['service_type_info'] = json_encode($info->authorizer_info->service_type_info);
		$params['verify_type_info']  = json_encode($info->authorizer_info->verify_type_info);
		$params['user_name']         = $info->authorizer_info->user_name;
		$params['alias']             = $info->authorizer_info->alias;
		$params['qrcode_url']        = $info->authorizer_info->qrcode_url;
		$params['business_info']     = json_encode($info->authorizer_info->business_info);

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/set.json', $params, 'POST');

		return $this->result($requests);
	}

	public function checkPayMch($appid, $mch_id, $mch_secret, $out_trade_no)
	{
		$params                 = array();
		$params['appid']        = $appid;
		$params['mch_id']       = $mch_id;
		$params['mch_secret']   = $mch_secret;
		$params['out_trade_no'] = $out_trade_no;

		$requests   = array();
		$requests[] = $this->request('api_host_open_pay', 'order/check_pay_mch.json', $params, 'GET');

		return $this->result($requests);
	}

	public function setAuthorizerPayMch($authorizer_appid, $mch_id, $mch_secret)
	{
		$params                     = array();
		$params['authorizer_appid'] = $authorizer_appid;
		$params['mch_id']           = $mch_id;
		$params['mch_secret']       = $mch_secret;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/set_pay_mch.json', $params, 'POST');

		return $this->result($requests);
	}

	public function listByCorp($fk_corp, $page, $size)
	{
		$params            = array();
		$params['fk_corp'] = $fk_corp;
		$params['page']    = $page;
		$params['size']    = $size;

		$requests   = array();
		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/list_by_corp.json', $params, 'GET');

		return $this->result($requests);
	}


}
