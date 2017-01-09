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
