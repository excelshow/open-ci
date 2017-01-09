<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('show_error_v2')) {
	function show_error_v2($message, $status_code, $heading = '系统升级中，请稍后...')
	{
        show_error($message, $status_code, $heading);
	}
}
