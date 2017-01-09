<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/../Base.php';

class Test extends Base
{
	const ASTOKEN = 'c3YF7vLZXa1pp4acpGCGfaNkZYsp-ibwfUkpcoPilzgdbL0vRNRpVeRxDgPDNYj_HHUoNmNLyU_K1kHye10W6weHD_9kZpFSw-dQ7qHmU_YPLOg6H4a5bRHwq2e6mQLgDVHiAKDYQC';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('WeChatApi');
		$this->load->library('DownloadImg');
		$this->load->helper("diy");
	}
	public function index()
	{
		$a = $this->wechatapi->uploadImg(
			self::ASTOKEN,
			'/home/liangkaixuan/code/open-operation/task/148306531938.jpg'
		);
		var_dump($a);die;
	}

	public function download_img()
	{
		$a = $this->downloadimg->uploadLocal('http://img.wesai.com/01020000582ecda900002e6fe28e9c76.jpg');
		print_r($a);die;
	}

	public function add_card()
	{
		$kqinfo = array("card" => array());
		$kqinfo['card']['card_type'] = 'GENERAL_COUPON';
		$kqinfo['card']['general_coupon'] = array('base_info' => array(), 'default_detail' => array());
		$kqinfo['card']['general_coupon']['base_info']['logo_url'] = 'URL';
		$kqinfo['card']['general_coupon']['base_info']['code_type'] = 'CODE_TYPE_QRCODE';
		$kqinfo['card']['general_coupon']['base_info']['brand_name'] = '测试brand';
		$kqinfo['card']['general_coupon']['base_info']['title'] = '测试卡券';
		$kqinfo['card']['general_coupon']['base_info']['color'] = 'Color030';
		$kqinfo['card']['general_coupon']['base_info']['notice'] = '测试测试测试';
		$kqinfo['card']['general_coupon']['base_info']['description'] = '这是一张优惠券';
		$kqinfo['card']['general_coupon']['base_info']['date_info']['type'] = 1;
		$kqinfo['card']['general_coupon']['base_info']['date_info']['begin_timestamp'] = time();
		$kqinfo['card']['general_coupon']['base_info']['date_info']['end_timestamp'] = time() + 100 * 24 * 3600;
		$kqinfo['card']['general_coupon']['base_info']['sku']['quantity'] = 100000;
		$kqinfo['card']['general_coupon']['default_detail'] = '测试数据\n测试数据\n测试数据';

		$kqinfo = json_encode($kqinfo, JSON_UNESCAPED_UNICODE);

		$resultData = $this->wechatapi->createdCard(self::ASTOKEN, $kqinfo);
		print_r($resultData);die;
	}


}
