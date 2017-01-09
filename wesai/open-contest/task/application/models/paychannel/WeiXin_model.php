<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 下午1:45
 */
require_once __DIR__ . '/../ModelBase.php';

class WeiXin_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('signature');
	}

	public function doWeixinPay($outTradeNo, $amount, $ip, $tradeType = 'JSAPI', $openid = '', $psName = '', $timeExpire = null)
	{
		$std        = new stdClass();
		$std->error = 0;
		$this->load->config('pay_channel');
		$payChannel = $this->config->item('pay_channel')['weixin_' . strtolower($tradeType)];

		!empty($timeExpire) or $timeExpire = date('YmdHis', time() + (ORDER_PAY_TIME_LIMIT * 60));

		$params = array(
			'appid'            => $payChannel['appid'],
			'mch_id'           => $payChannel['mch_id'],
			'nonce_str'        => get_nonce_str(),
			'body'             => !empty($psName) ? $psName : (sprintf('%.2f', ($amount / 100)) . '元商品'),
			'attach'           => '微赛体育',
			'out_trade_no'     => $outTradeNo,
			'total_fee'        => $amount,
			'spbill_create_ip' => long2ip($ip),
			'notify_url'       => $payChannel['notify_url'],
			'trade_type'       => $tradeType,
			'time_expire'      => $timeExpire,
		);

		switch ($tradeType) {
			case 'JSAPI':
				$params['openid'] = $openid;
				break;
			default:
				break;
		}

		if (strlen($params['body']) > 128) {
			$params['body'] = mb_substr($params['body'], -42, null, 'UTF-8');
		}

		$params['sign'] = create_signature($params, 'md5', '&key=' . $payChannel['api_secret']);

		$xml = '<xml>';
		foreach ($params as $key => $value) {
			$xml .= "<$key>$value</$key>";
		}
		$xml .= '</xml>';

		$this->load->library('curl');
		$result = $this->curl->post($xml, $payChannel['pay_url']);
		$this->curl->close();
		if (empty($result)) {
			$std->error = -1;

			return $std;
		}

		$errMsg           = array();
		$errMsg['msg']    = 'channel log weixin prepay result';
		$errMsg['result'] = $result;
		log_message_v2('error', $errMsg);

		$returnData = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);

		$returnData = $this->xml2array($returnData);
		$verify     = verify_signature($returnData, 'md5', '&key=' . $payChannel['api_secret']);
		if (false === $verify) {
			$std->error = -2;

			return $std;
		}
		if (!is_array($returnData)) {
			$std->error = -3;

			return $std;
		}
		if ($returnData['return_code'] != 'SUCCESS') {
			$std->error   = -4;
			$std->err_msg = !empty($returnData['return_msg']) ? $returnData['return_msg'] : '';

			return $std;
		}
		if ($returnData['result_code'] != 'SUCCESS') {
			$std->error               = -5;
			$std->result_err_code     = !empty($returnData['err_code']) ? $returnData['err_code'] : '';
			$std->result_err_code_des = !empty($returnData['err_code_des']) ? $returnData['err_code_des'] : '';

			return $std;
		}

		$params   = array();
		$prepayId = strval($returnData['prepay_id']);

		switch ($tradeType) {
			case 'JSAPI':
				$params['appId']     = $payChannel['appid'];
				$params['timeStamp'] = time();
				$params['nonceStr']  = get_nonce_str();
				$params['package']   = 'prepay_id=' . $prepayId;
				$params['signType']  = 'MD5';
				$params['paySign']   = create_signature($params, 'md5', '&key=' . $payChannel['api_secret']);
				unset($params['appId']);
				$std->prepay_data = $params;
				break;
			case 'APP':
				$params['appid']     = $payChannel['appid'];
				$params['partnerid'] = $payChannel['mch_id'];
				$params['prepayid']  = $prepayId;
				$params['package']   = 'Sign=WXPay';
				$params['noncestr']  = get_nonce_str();
				$params['timestamp'] = time();
				$params['sign']      = create_signature($params, 'md5', '&key=' . $payChannel['api_secret']);
				$std->prepay_data    = $params;
				break;
			case 'NATIVE':
				$params['code_url'] = strval($returnData['code_url']);
				$std->prepay_data   = $params;
				break;
		}

		return $std;
	}

	public function getAccessToken($apppk)
	{
		$params = compact('apppk');

		$requests[] = $this->request('api_host_open_weixin', 'component/authorizer/get_by_pk.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function downloadMedia($access_token, $media_id)
	{
		try {
			$url = WEIXIN_FILE_DOWNLOAD_API . '?' . http_build_query(compact('access_token', 'media_id'));
			$this->load->library('curl');

			$result = $this->curl->get($url);
			$this->curl->close();

			$file_path = '/tmp/' . $media_id . '_' . time();
			file_put_contents($file_path, $result);

			return $file_path;
		} catch (Exception $e) {
			$errMsg           = array();
			$errMsg['msg']    = 'Exception occurred';
			$errMsg['e_msg']  = $e->getMessage();
			$errMsg['e_code'] = $e->getCode();
			$errMsg['e_line'] = $e->getLine();
			$errMsg['e_file'] = $e->getFile();
			log_message_v2('error', $errMsg);

			return false;
		}
	}

	public function doWeixinRefundApply($out_trade_no, $amount, $trade_type = 'JSAPI')
	{
		$std        = new stdClass();
		$std->error = 0;
		$this->load->config('pay_channel');
		$payChannel = $this->config->item('pay_channel')['weixin_' . strtolower($trade_type)];

		$params = array(
			'appid'         => $payChannel['appid'],
			'mch_id'        => $payChannel['mch_id'],
			'nonce_str'     => get_nonce_str(),
			'out_trade_no'  => $out_trade_no,
			'out_refund_no' => $out_trade_no,
			'total_fee'     => $amount,
			'refund_fee'    => $amount,
			'op_user_id'    => $payChannel['mch_id'],
		);

		$params['sign'] = create_signature($params, 'md5', '&key=' . $payChannel['api_secret']);

		$xml = '<xml>';
		foreach ($params as $key => $value) {
			$xml .= "<$key>$value</$key>";
		}
		$xml .= '</xml>';

		if (!file_exists($payChannel['apiclient_cert']) || !file_exists($payChannel['apiclient_key'])) {
			$std->error = -1;

			return $std; //证书文件不存在
		}

		$this->load->library('curl', array('url' => $payChannel['refund_url']));
		$curlOpts = array(
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_SSLCERTTYPE    => 'PEM',
			CURLOPT_SSLCERT        => $payChannel['apiclient_cert'],
			CURLOPT_SSLKEYTYPE     => 'PEM',
			CURLOPT_SSLKEY         => $payChannel['apiclient_key'],
		);
		$this->curl->setOpts($curlOpts);
		$result = $this->curl->post($xml);
		if (empty($result)) {
			$std->error = -2;

			return $std;
		}

		$returnData = $this->xml2array($result);
		$verify     = verify_signature($returnData, 'md5', '&key=' . $payChannel['api_secret']);
		if (false === $verify) {
			$std->error = -3;

			return $std;
		}
		if (!is_array($returnData)) {
			$std->error = -4;

			return $std;
		}
		if ($returnData['return_code'] != 'SUCCESS') {
			$std->error   = -5;
			$std->err_msg = !empty($returnData['return_msg']) ? $returnData['return_msg'] : '';

			return $std;
		}
		if ($returnData['result_code'] != 'SUCCESS') {
			$std->error               = -5;
			$std->result_err_code     = !empty($returnData['err_code']) ? $returnData['err_code'] : '';
			$std->result_err_code_des = !empty($returnData['err_code_des']) ? $returnData['err_code_des'] : '';

			return $std;
		}

		return $std;
	}

	private function xml2array($xml)
	{
		$array    = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
		$newArray = array();
		$array    = ( array )$array;
		foreach ($array as $key => $value) {
			$value          = ( array )$value;
			$newArray[$key] = count($value) == 0 ? null : $value [0];
		}
		$newArray = array_map("trim", $newArray);

		return $newArray;
	}
}
