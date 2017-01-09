<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 下午1:49
 */

if (!function_exists('create_signature')) {

	function create_signature(Array $params, $algo = 'md5', $secretKey = '', $paychannel = '')
	{
		$signPar = '';
		switch ($paychannel) {
			default:
				ksort($params);
				reset($params);
				foreach ($params as $key => $value) {
					if ('' === $value || $key == 'sign_type') {
						continue;
					}
					$signPar .= $key . '=' . $value . '&';
				}

				$signPar = substr($signPar, 0, strlen($signPar) - 1);
				break;
		}
		$sign = '';
		switch (strtolower($algo)) {
			case 'md5':
				$sign = md5($signPar . $secretKey);
				break;
			case 'sha1':
				$sign = sha1($signPar . $secretKey);
				break;
		}


		switch ($paychannel) {
			case 'weibo':
				return $sign;
				break;
			default:
				return strtoupper($sign);
				break;
		}
	}
}

if (!function_exists('verify_signature')) {

	function verify_signature(Array $params, $algo = 'md5', $secretKey = '', $paychannel = '')
	{
		if (!array_key_exists('sign', $params)) {
			log_message('error', __FUNCTION__ . ': sign not in weixin return data');

			return false;
		}
		$sign = $params['sign'];
		unset($params['sign']);

		$verify_sign = self::create_signature($params, $algo, $secretKey, $paychannel);

		return (boolean)($verify_sign === $sign);
	}
}
if (!function_exists('get_nonce_str')) {

	function get_nonce_str($length = 16)
	{
		$str = 'abcdefghigklmnopqrstuvwxyzABCDEFGHIGKLMNOPQRSTUVWXYZ';

		$noncestr = '';
		for ($i = 0; $i < 16; $i++) {
			mt_srand();
			$k = mt_rand(0, 51);
			$noncestr .= $str[$k];
		}

		return $noncestr;
	}
}


/**
 * RSA签名
 *
 * @param $data             待签名数据
 * @param $private_key_path 商户私钥文件路径
 *                          return 签名结果
 *
 * @return string
 */
if (!function_exists('rsa_sign')) {

	function rsa_sign($data, $private_key_path)
	{
		$priKey = file_get_contents($private_key_path);
		$res    = openssl_get_privatekey($priKey);
		openssl_sign($data, $sign, $res);
		openssl_free_key($res);
		//base64编码
		$sign = base64_encode($sign);

		return $sign;
	}
}

/**
 * RSA验签
 *
 * @param $data                待签名数据
 * @param $ali_public_key_path 支付宝的公钥文件路径
 * @param $sign                要校对的的签名结果
 *                             return 验证结果
 *
 * @return bool
 */
if (!function_exists('rsa_verify')) {

	function rsa_verify($data, $ali_public_key_path, $sign)
	{
		$pubKey = file_get_contents($ali_public_key_path);
		$res    = openssl_get_publickey($pubKey);
		$result = (bool)openssl_verify($data, base64_decode($sign), $res);
		openssl_free_key($res);

		return $result;
	}
}

/**
 * RSA解密
 *
 * @param $content          需要解密的内容，密文
 * @param $private_key_path 商户私钥文件路径
 *                          return 解密后内容，明文
 *
 * @return string
 */
if (!function_exists('rsa_decrypt')) {

	function rsa_decrypt($content, $private_key_path)
	{
		$priKey = file_get_contents($private_key_path);
		$res    = openssl_get_privatekey($priKey);
		//用base64将内容还原成二进制
		$content = base64_decode($content);
		//把需要解密的内容，按128位拆开解密
		$result = '';
		for ($i = 0; $i < strlen($content) / 128; $i++) {
			$data = substr($content, $i * 128, 128);
			openssl_private_decrypt($data, $decrypt, $res);
			$result .= $decrypt;
		}
		openssl_free_key($res);

		return $result;
	}
}
