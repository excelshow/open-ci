<?php

/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 上午11:02
 */
class ModelBase extends MY_Model
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


			if (empty($result) || !is_object($result)) {
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

			if ($result->error != 0) {
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

			$result = json_decode(json_encode($result), true);

			if (array_key_exists('result', $result)) {
				return $result['result'];
			}

			return $result;
		} catch (Exception $e) {
			$errMsg = array(
				'ExceptionMsg'  => $e->getMessage(),
				'ExceptionLine' => $e->getLine(),
				'ExceptionCode' => $e->getCode(),
				'result'        => $result,
				'requests'      => $requests,
			);

			log_message_v2('error', $errMsg);

			return false;
		}
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
			$errMsg = array(
				'ExceptionMsg'  => $e->getMessage(),
				'ExceptionLine' => $e->getLine(),
				'ExceptionCode' => $e->getCode(),
				'result'        => $result,
				'requests'      => $requests,
			);

			log_message_v2('error', $errMsg);

			return false;
		}
	}
}
