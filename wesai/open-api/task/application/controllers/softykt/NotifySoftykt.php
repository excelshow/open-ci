<?php
/**
 * User: liangkaixuan
 */
require_once __DIR__ . '/../Base.php';

class NotifySoftykt extends Base
{
	const TIME_SLEEP_S  = 10;
	const MQ_KEY		= MQ_TOPIC_SOFTYKT_EXT_CALLBACK;
	public function __construct()
	{
		parent::__construct();
		$redisConfig = $this->config->item('redismq');
		$this->load->library('Redis_List_Client', $redisConfig);
		$this->load->helper('log');
	}

	public function index()
	{
		while (true) {
			try {
				$notifyjson = $this->redis_list_client->RightPop(self::MQ_KEY);
				//log_message('error', var_dump($notifyjson, true));
				if (empty($notifyjson)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				$res = json_decode($notifyjson,true);

				switch($res['type']){
					//商品修改
					case SOF_TYPE_PRODUCT_SAVE:
						$proParam = $res;
						$this->productModify($proParam);
						break;
					//商品上下线
					case SOF_TYPE_PRODUCT_STATE_SAVE:
						$scenicid   = $res['scenicid'];
						$productid  = $res['productid'];
						$useflag    = $res['useflag'];
						$this->productStateSave($scenicid, $productid, $useflag, $res);
						break;
					//商品删除
					case SOF_TYPE_PRODUCT_DELETE:
						$scenicid   = $res['scenicid'];
						$productid  = $res['productid'];
						$this->productDelete($scenicid, $productid, $res);
						break;
					//验票
					case SOF_TYPE_ORDER_TEST:
						$orderid        = $res['orderid'];
						$orderdetailid  = $res['orderdetailid'];
						$number         = $res['number'];
						$this->orderTest($orderid, $orderdetailid, $number, $res);
						break;
					//退票
					case SOF_TYPE_RETURN_TICKET:
						$orderid        = $res['orderid'];
						$orderdetailid  = $res['orderdetailid'];
						$number         = $res['number'];
						$this->orderReturn($orderid, $orderdetailid, $number, $res);
						break;
					// 默认验证回调
					default:
						log_message_v2(
							'error',
							array(
								'msg'     => 'softykt callback failed',
								'file'    => __FILE__,
								'line'    => __LINE__,
								'info'    => $res,
							)
						);
						break;
				}

			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	/**
	 * 商品信息修改
	 */
	private function productModify($param){
		$storage = $param;
		unset($storage['type']);

		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'softykt/Contest/update.json', $storage, METHOD_POST,true,2,1000);
		if (empty($orderObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'modify_product failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $param,
				)
			);
			$this->setRedis($param, 'modify_product');
		}elseif($orderObj->error < 0){
			log_message('error', var_export($orderObj,true));
			return false;
		}
	}


	/**
	 * 商品上下架
	 * @param $scenicid 景区id
	 * @param $productid 产品id
	 * @param $useflag 上线状态（0:上线；1-下线）
	 */
	private function productStateSave($scenicid, $productid, $useflag, $param){

		$storage = array(
			'scenicid'  => $scenicid,
			'productid' => $productid,
			'useflag'   => $useflag
		);
		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'softykt/Contest/offline.json', $storage, METHOD_POST,true,2,1000);
		if (empty($orderObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'save_product_state failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $storage,
				)
			);
			$this->setRedis($param, 'save_product_state');
			return false;
		}elseif($orderObj->error < 0){
			log_message('error', var_export($orderObj,true));
			return false;
		}
	}

	/**
	 * 删除商品
	 * @param $scenicid 景区id
	 * @param $productid 产品id
	 */
	private function productDelete($scenicid, $productid, $param){
		$storage = array(
			'scenicid'  => $scenicid,
			'productid' => $productid
		);
		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'softykt/Contest/offline.json', $storage, METHOD_POST,true,2,1000);
		if (empty($orderObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'delete_product failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $storage,
				)
			);
			$this->setRedis($param, 'delete_product');
		}elseif($orderObj->error < 0){
			log_message('error', var_export($orderObj,true));
			return false;
		}
	}

	/**
	 * 退票
	 * @param $orderid
	 * @param $orderDetailid
	 * @param $number
	 */
	private function orderReturn($orderid, $orderDetailid, $number, $param){

		$storage = array(
			'order_number'    => $orderid,
			'order_detail_id' => $orderDetailid,
			'verify_number'   => $number
		);

		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'softykt/Order/refund.json', $storage, METHOD_POST,true,2,1000);
		if (empty($orderObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'back_order failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $storage,
				)
			);
			$this->setRedis($param, 'back_order');
		}elseif($orderObj->error < 0){
			log_message('error', var_export($orderObj,true));
			return false;
		}
	}

	/**
	 * 验票
	 * @param $orderid
	 * @param $orderDetailid
	 * @param $number
	 */
	private function orderTest($orderid, $orderDetailid, $number, $param){
		$storage = array(
			'order_number'    => $orderid,
			'order_detail_id' => $orderDetailid,
			'verify_number'   => $number
		);
		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'softykt/Order/consume.json', $storage, METHOD_POST,true,2,1000);
		if (empty($orderObj)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'test_order failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $storage,
				)
			);
			$this->setRedis($param, 'test_order');
		}elseif($orderObj->error < 0){
			log_message('error', var_export($orderObj,true));
			return false;
		}
	}

	/**
	 * 存储到redis
	 * @param $param
	 * @param $errormsg
	 */
	private function setRedis($param,$errormsg){
		$ret = $this->redis_list_client->LeftPush(self::MQ_KEY, json_encode($param));
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'set '.$errormsg.' to redis failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'param'   => $param,
				)
			);
		}
	}
}
