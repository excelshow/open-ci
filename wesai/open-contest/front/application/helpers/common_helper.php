<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('create_rand_number')) {
	function create_rand_number($length)
	{
		$randstr = '';
		for ($i = 0; $i < $length; $i++) {
			$randstr .= chr(mt_rand(48, 57));
		}

		return $randstr;
	}
}

if ( ! function_exists('create_rand_str'))
{
    function create_rand_str($length)
    {  
        $randstr = '';  
        for ($i=0; $i<$length; $i++)  
        {  
            $randstr .= chr(mt_rand(97, 122));  
        }  
        return $randstr;  
    }
}

/*
* 获取ip地址
*/
if (!function_exists('getIP')) {

	function getIP($long = false)
	{
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = "unknown";

		if ($long) {
			$ip = sprintf("%u", ip2long($ip));
		}

		return $ip;
	}
}

if (!function_exists('industry_name')) {
    function industry_name($corp_id = 0, $contest_id = 0, $position = ''){
        global $INDUSTRY_NAME_LIST, $INDUSTRY_CORP_IDS, $INDUSTRY_CONTEST_IDS;
        // 命中
        $industry_hit = INDUSTRY_BASE;
        foreach($INDUSTRY_CORP_IDS as $industry => $corp_ids){
            if (in_array($corp_id, $corp_ids)) {
                $industry_hit = $industry;
                break;
            }
        }

        foreach($INDUSTRY_CONTEST_IDS as $industry => $contest_ids){
            if (in_array($contest_id, $contest_ids)) {
                $industry_hit = $industry;
                break;
            }
        }

        if (!empty($INDUSTRY_NAME_LIST[$industry_hit])) {
            if(!empty($position)){
                return $INDUSTRY_NAME_LIST[$industry_hit][$position];
            }
            return $INDUSTRY_NAME_LIST[$industry_hit];
        }

        return '';
     }
 }
