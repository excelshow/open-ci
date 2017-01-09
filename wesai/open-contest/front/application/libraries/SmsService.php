<?php
/**
 * User: zhaodc
 * Date: 16/7/20
 * Time: 上午10:36
 */
class SmsService
{
	public function __construct()
	{
		$this->CI = &get_instance();
		$this->CI->load->library('mcurl');
	}

	private function request($host, $api, $params, $method = 'GET'){
		$host    = $this->CI->config->item($host);
		$request = array(
			'host'   => $host,
			'api'    => $api,
			'method' => $method,
			'params' => $params,
		);

		return $request;
	}

	private function result($requests, $ms = true, $timeout = 2, $timeout_ms = 300){
		try {
			$result = $this->CI->mcurl->capture_multi($requests, $ms, $timeout, $timeout_ms);
			if(isset($result[0])){
				return $result[0];
			}
			return (object)array();
		} catch (Exception $e) {
			$msg = $e->getMessage().json_encode($requests);
			log_message('error', $msg);
			throw new Exception($msg, $e->getCode());
		}
	}
	public function sendCode($client_id, $phone, $apppk = 0, $mark = '')
	{
		$params = compact('client_id', 'phone', 'apppk', 'mark');

		$requests[] = $this->request('api_host_open_sms', 'sms/code_send.json', $params, "POST");

		return $this->result($requests);
	}

	public function verifyCode($client_id, $phone, $code)
	{
		$params     = compact('client_id', 'phone', 'code');
		$requests[] = $this->request('api_host_open_sms', 'sms/code_verify.json', $params, "POST");

		return $this->result($requests);

	}

	public function sendMsg($client_id, $biz, $phone, $params, $apppk = 0)
	{
		$params = json_encode($params);
		$params = compact('client_id', 'biz', 'phone', 'params', 'apppk');

		$requests[] = $this->request('api_host_open_sms', 'sms/msg_send.json', $params, "POST");

		return $this->result($requests);
	}
}
