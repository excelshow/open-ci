<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


if ( ! function_exists('check_sign'))
{
	function check_sign(array $params, $secret_key)
	{
		if(empty($params['signature'])) return false;

		$signature = $params['signature'];
		unset($params['signature']);

		$params[] = $secret_key;

		sort($params, SORT_STRING);
		$tmp_str = implode($params);

		return $signature == sha1($tmp_str);
	}
}

if ( ! function_exists('jsapi_sign'))
{
    function jsapi_sign($nonceStr, $jsapiTicket, $timestamp, $url)
    {
        $params['noncestr'] = $nonceStr;
        $params['jsapi_ticket'] = $jsapiTicket;
        $params['timestamp'] = $timestamp;
        $params['url'] = $url;

        ksort($params, SORT_STRING);
        $tmp_str = urldecode(http_build_query($params));

        return $signature = sha1($tmp_str);
    }
}

if ( ! function_exists('get_jsapi_ticket'))
{
    function get_jsapi_ticket($filepath)
    {
        if (!file_exists($filepath) || !is_readable($filepath)) {
            return false;
        }

        $contents = file_get_contents($filepath);
        $ticketObj = json_decode($contents);

        return isset($ticketObj->ticket) ? $ticketObj->ticket : false;
    }
}

if (!function_exists('controller_autoloader')) {
    function controller_autoloader($classname) {
        $pos = strpos($classname, '_');
        if (false === $pos) {
            return false;
        }

        $filename = substr($classname, strrpos($classname, '_') + 1);
        $filepath = substr($classname, 0, strrpos($classname, '_') + 1);
        $filepath = strtolower(str_replace('_', '/', $filepath));

        $file = APPPATH . 'controllers/' . $filepath . $filename . '.php';

        if (!isset($file) || !file_exists($file)) {
            return false;
        }

        require_once $file;

        return true;
    }
}

if (!function_exists('wx_jsapi_config')) {
    function wx_jsapi_config($url, $apis, $jsapi_ticket = '')
    {
        $api_list = explode(',', $apis);
        $api_format = array();
        foreach ($api_list as $api) {
            $api_format[] = "'".trim($api)."'";
        }

        $debug = WEIXIN_JSAPI_DEBUG ? 'true' : 'false';

        $timestamp = time();
        $noncestr  = create_rand_str(10);

        $config = new stdClass();
        $config->appid = WEIXIN_APPID;
        $config->debug = $debug;
        $config->noncestr = $noncestr;
        $config->timestamp = $timestamp;
        $config->signature = jsapi_sign($noncestr, $jsapi_ticket, $timestamp, $url);
        $config->jsapilist = implode(',', $api_format);

        return $config;
    }
}
