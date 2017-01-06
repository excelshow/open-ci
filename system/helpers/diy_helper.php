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

if (!function_exists('log_message_v2')) {
	function log_message_v2($level, $msg)
	{
		if (is_array($msg) || is_object($msg)) {
			$msg = json_encode($msg);
		}
		log_message($level, $msg);
	}
}

if (!function_exists('log_message_to_file')) {
	function log_message_to_file($filename, $msg) {
		if (is_array($msg) || is_object($msg)) {
			$msg = json_encode($msg);
		}

		$config = &get_config();

		$file_path = $config['log_path'] . 'log_' . date('Y-m-d') . '_' . $filename;

		$msg = date('Y-m-d H:i:s') . ' ------- ' . $msg . PHP_EOL;
		file_put_contents($file_path, $msg, FILE_APPEND);
	}
}

if (!function_exists('obj2array')) {
    function obj2array($obj) {
        return json_decode(json_encode($obj), true);
    }
}

if (!function_exists('array2obj')) {
    function array2obj($arr) {
        return json_decode(json_encode($arr));
    }
}
