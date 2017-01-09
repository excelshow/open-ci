<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('show_error_info')) {
	function show_error_info($error_pre, $error_code, $message, $write = true)
	{
        if($write){
            $msg = $error_pre . '*' . $error_code . '*' . $message . '*' . json_encode($_SERVER['REQUEST_URI']) . '*' . json_encode($_POST);
            log_message('error', $msg);
        }
        $error_code = error_code_format($error_pre, $error_code);
        $error = array(
            'error' => $error_code,
            'info' => $message,
        );

        return $error;
	}
}

if (!function_exists('error_code_format')) {
	function error_code_format($error_pre, $error_code)
	{
        return $error_pre . str_pad(abs($error_code), 4, "0", STR_PAD_LEFT);
	}
}

