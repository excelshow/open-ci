<?php

/**
 * User: zhaodc
 * Date: 16/6/26
 * Time: 下午3:20
 */
class WeixinModelBase extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	protected function checkInternalApiResult($result, $requests)
	{
		try {

			$backTrace  = debug_backtrace()[1];
			$methodName = !empty($backTrace['function']) ? $backTrace['function'] : '';
			$className  = !empty($backTrace['class']) ? $backTrace['class'] . '::' : '';
			$line       = !empty($backTrace['line']) ? $backTrace['line'] : 0;
			$file       = !empty($backTrace['file']) ? $backTrace['file'] : '';
			$__METHOD__ = $className . $methodName;

			$result = json_decode(json_encode($result), true);

			if (empty($result) || !is_array($result) || !isset($result['error'])) {
				$errMsg = array(
					'msg'      => $__METHOD__ . ' return null',
					'file'     => $file,
					'line'     => $line,
					'result'   => $result,
					'requests' => $requests,
				);

				log_message_v2('error', $errMsg);

				return false;
			}

			if ($result['error'] != 0) {
				$errMsg = array(
					'msg'      => $__METHOD__ . ' failed',
					'file'     => $file,
					'line'     => $line,
					'result'   => $result,
					'requests' => $requests,
				);

				log_message_v2('error', $errMsg);

				return false;
			}

			return $result;

		} catch (Exception $e) {
			$this->catchException($e);

			return false;
		}
	}

	protected function catchException(Exception $e)
	{
		$errMsg = array(
			'msg'     => 'Exception occurred',
			'e_file'  => $e->getFile(),
			'e_line'  => $e->getLine(),
			'e_msg'   => $e->getMessage(),
			'e_trace' => $e->getTrace()[0],
		);
		log_message_v2('error', $errMsg);
	}

	protected function checkWeixinApiResult($result, $requests)
	{
		try {

			$backTrace  = debug_backtrace()[1];
			$methodName = !empty($backTrace['function']) ? $backTrace['function'] : '';
			$className  = !empty($backTrace['class']) ? $backTrace['class'] . '::' : '';
			$line       = !empty($backTrace['line']) ? $backTrace['line'] : 0;
			$file       = !empty($backTrace['file']) ? $backTrace['file'] : '';
			$result     = json_decode($result, true);
			$__METHOD__ = $className . $methodName;

			if (empty($result)) {
				$errMsg = array(
					'msg'      => $__METHOD__ . ' return null',
					'file'     => $file,
					'line'     => $line,
					'result'   => $result,
					'requests' => $requests,
				);
				log_message_v2('error', $errMsg);

				return false;
			}

			if (!empty($result['errcode'])) {
				$errMsg = array(
					'msg'      => $__METHOD__ . ' failed',
					'file'     => $file,
					'line'     => $line,
					'result'   => $result,
					'requests' => $requests,
				);

				log_message_v2('error', $errMsg);

				return false;
			}

			return $result;
		} catch (Exception $e) {
			$this->catchException($e);

			return false;
		}
	}
}
