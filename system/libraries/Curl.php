<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CI_Curl 
 *  默认启用timeout_ms
 *  GET POST 重新赋值timeout timeout_ms
 */
class CI_Curl {

	private $_url = false;
	private $_ch = false;
	private $_connecttimeout = 5;
	private $_timeout = 5;
	private $_timeoutms = 5000;
	private $_returntransfer = true;
	private $_followlocation = true;
	private $_maxredirs = 3;
	private $_ms = true;

	public function __construct($params = null) {

		if ($params['url']) {
			$this->_url = $params['url'];
			$this->_init();
		}
	}

	private function _init(){

		$this->_ch = curl_init();

		curl_setopt($this->_ch, CURLOPT_URL, $this->_url);
		curl_setopt($this->_ch, CURLOPT_CONNECTTIMEOUT, $this->_connecttimeout);
		curl_setopt($this->_ch, CURLOPT_TIMEOUT, $this->_timeout);
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, $this->_returntransfer);
		curl_setopt($this->_ch, CURLOPT_FOLLOWLOCATION, $this->_followlocation);
		curl_setopt($this->_ch, CURLOPT_MAXREDIRS, $this->_maxredirs);

		$curlInfo = curl_version();
		$version = explode('.', $curlInfo['version']);
		if($this->_ms && $version[0]>6 && $version[1]>15){
			curl_setopt($this->_ch, CURLOPT_TIMEOUT_MS, $this->_timeoutms);
		}
		return $this->_ch;
	}

	private function _errmsg($msg){
		return 'url:'.$this->_url.' errormsg:'.$msg."\r\n";
	}

	public function setHeaders($headers){
		curl_setopt($this->_ch, CURLOPT_HTTPHEADER, $headers);
	}

	public function setTimeoutMs($ms){
		$this->_timeoutms = $ms;
		curl_setopt($this->_ch, CURLOPT_TIMEOUT_MS, $this->_timeoutms);
	}

	public function __call($func, $arguments) {
		$func = 'curl_'.$func;
		if(function_exists($func)){
			 array_unshift($arguments, $this->_ch);
			return call_user_func_array($func, $arguments);
		}else{
			throw new Exception($func.'not exists!', 502);
			return false;
		}
	}

	public function __set($option, $value){
		return curl_setopt($this->_ch, $option, $value);
	}

	public function setOpts($options){
		return curl_setopt_array($this->_ch, $options);
	}

	public function exec(){
		$ret = curl_exec($this->_ch);
		if(curl_errno($this->_ch)){
			throw new Exception($this->_errmsg(curl_error($this->_ch)), 500);
		}else if(($net = curl_getinfo($this->_ch)) && $net['http_code'] != '200'){
			throw new Exception($this->_errmsg('httpCode is '.$net['http_code']), 500);
		}
		return $ret;
	}

	public function close(){
		if(is_resource($this->_ch)) {
			curl_close($this->_ch);
		}
	}

	public function get($url = '', $ms = true, $timeout = 5, $timeout_ms = 5000){
        $this->_ms = $ms;
        $this->_timeout = $timeout;
        $this->_timeoutms = $timeout_ms;
        if($url){
			$this->_url = $url;
			$this->_init();
        }
		curl_setopt($this->_ch, CURLOPT_HTTPGET, TRUE);
		return $this->exec();
	}

	//put your code here、
	public function post($data, $url='', $ms = true, $timeout = 5, $timeout_ms = 5000){
        $this->_ms = $ms;
        $this->_timeout = $timeout;
        $this->_timeoutms = $timeout_ms;
        if($url){
			$this->_url = $url;
			$this->_init();
        }
		curl_setopt($this->_ch, CURLOPT_POST, TRUE);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, $data);
		return $this->exec();
	}

	public function capture($hosts, $api, $method, $params, $headers = array(), $ms = true, $timeout = 5, $timeout_ms = 500){
		$this->_ms = $ms;
        $this->_timeout = $timeout;
        $this->_timeoutms = $timeout_ms;
		$hostname = array_keys($hosts)[0];
		$iplist   = $hosts[$hostname];

		if (!is_array($iplist) or empty($iplist)) {
			throw new Exception("api host config error .");
		}

		$this->_url = 'http://' . $iplist[array_rand($iplist)] . '/' . $api;

		if (strtoupper($method) == 'GET') {
			$this->_url .= "?" . http_build_query($params);
			$this->_init();
            $headers[] = 'Host: '.$hostname;
			$this->setHeaders($headers);
			$result = $this->get();
			$this->close();
		} else {
			$this->_init();
            $headers[] = 'Host: '.$hostname;
			$this->setHeaders($headers);
			$result = $this->post($params);
			$this->close();
		}

        $std = json_decode($result);

        if (is_null($std) || !is_object($std)) {
            throw new Exception ('Bad response:' . $result, -700);
        }

        return $std;
	}

	public function __destruct() {
		$this->close();
	}
}

?>
