<?php
/**
 * User: zhaodc
 * Date: 16/6/25
 * Time: 上午11:56
 */

if (!function_exists('log_message_v2')) {
	function log_message_v2($level, $msg = null, $debug_backtrace_depth = 2)
	{
		if (is_array($msg) || is_object($msg)) {
			$msg = json_encode($msg);
		}

		if (empty($msg)) {
			$msg = json_encode(array_slice(debug_backtrace(), 0, $debug_backtrace_depth));
		}
		log_message($level, $msg);
	}
}
