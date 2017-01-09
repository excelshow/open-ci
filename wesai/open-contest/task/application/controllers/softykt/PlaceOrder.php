<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/../Base.php';

class PlaceOrder extends Base
{
	const TIME_SLEEP_S 	= 10;
	const NUMBER 		= 100;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}
	public function getHostName()
	{
		return API_HOST_OPEN_CONTEST;
	}
	public function getAllowedApiList()
	{
		return array();
	}

	public function index()
	{
		while (true) {
			try {
				$list = $this->getOrderlist();
				if (empty($list) || empty($list['data']) || !is_array($list['data']) || count($list['data']) < 1) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				foreach($list['data'] as $order){
					if($order['partner_order_number'] != ''){
						continue;
					}
					$this->createSoftykt($order);
                    $this->dealing();
                    $this->dealEnd();
				}

			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function createSoftykt($order){
		$orderid = $order['fk_order'];

		$order = $this->getOrderdetail($orderid);
		if(empty($order)){
			log_message_v2(
				'error',
				array(
					'msg'     => 'get order detail failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'ordernumber' => $order['fk_order'],
				)
			);
		}
		$mobile = $this->getUser($order['fk_user']);
		if(empty($mobile)){
			return false;
		}
		$params = array(
			'scenicid' 			=> $order['scenicid'],
			'productid' 		=> $order['productid'],
			'number' 			=> $order['copies'],
			'trade_no'			=> $order['out_trade_no'],
			'corp_id'			=> $order['corp_id'],
			'mobile'			=> $mobile
		);
		$orderObj = $this->callInnerApiDiy(API_HOST_OPEN_API, 'softykt/order/place_order.json', $params, METHOD_POST, true, 3, 3000);
		if (empty($orderObj) || $orderObj->error != 0) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'softykt create order failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'ordernumber' => $order['out_trade_no'],
				)
			);
			return false;
		}

        // 金飞鹰返回的订单信息
        // http://test.softykt.com/help/skihelp/ 下单
        $orderInfo = $orderObj->result;
        if($orderInfo->rcode == 666 || $orderInfo->rcode == 200078){
            $order_number = $orderInfo->result->ordernumber;
            if($orderInfo->rcode == 666){
                $msg = "create 根据智慧体育订单号 " . $order['out_trade_no'] . "生成金飞鹰订单成功, 金飞鹰订单号为：".$order_number;
            }elseif($orderInfo->rcode == 200078){
                $msg = "create 根据智慧体育订单号 " . $order['out_trade_no'] . "生成金飞鹰订单失败, 金飞鹰订单已完成，订单号为：".$order_number;
            }
			log_message_to_file("placeOrder", $msg);

			//生成关联关系
			$parnter = $this->updateParnter($order_number, $orderid);
			if($parnter['error'] < 0){
				$msg = "create 根据智慧体育订单号 " . $order['out_trade_no'] . "生成金飞鹰订单成功，金飞鹰订单号为：".$order_number;
				$msg .= "创建关联关系出错！ ";
				log_message('error',$msg);
			}else{
				$msg = "生成订单关联关系成功";
				log_message_to_file("placeOrder", $msg);
			}
        }else{
			$msg = "create 根据智慧体育订单号 " . $order['out_trade_no'] . "生成金飞鹰订单出错，错误原因：".json_encode($orderInfo);
			log_message('error',$msg);
        }
	}


	/**
	 * 获取第三方金飞鹰未同步的订单列表
	 * @return mixed
	 */
	private function getOrderlist(){
		$params = array(
			'size' => self::NUMBER
		);
		$listObj = $this->callInnerApiDiy($this->getHostName(), 'softykt/Order/list_need_sync.json', $params, METHOD_GET);
		$listjson = json_encode($listObj);
		$list = json_decode($listjson,true);
		return $list;
	}

	/**
	 * 填充关联关系
	 * @param $ordernumber
	 * @param $trade_no
	 * @return mixed
	 */
	private function updateParnter($ordernumber, $fk_order){
		$params = array(
			'order_number' => $ordernumber,
			'oid' => $fk_order
		);
		$Obj  = $this->callInnerApiDiy($this->getHostName(), 'softykt/Order/update.json', $params, METHOD_POST);
		$json = json_encode($Obj);
		$arr  = json_decode($json,true);
		return $arr;
	}

	/**
	 * 根据订单id 查询订单详情
	 * @param $fk_order
	 * @return mixed
	 */
	private function getOrderdetail($fk_order){
		$params = array(
			'oid' => $fk_order
		);
		$Obj = $this->callInnerApiDiy($this->getHostName(), 'softykt/Contest/get_by_oid.json', $params, METHOD_GET);
		$json = json_encode($Obj);
		$arr = json_decode($json,true);

		if($arr['error'] < 0){
			$msg = "获取景区信息、订单信息失败 ,失败原因: ";
			log_message('error',$msg . $arr['info'] );
			return false;
		}
		$result = $arr['result'];
		$return = array(
			'scenicid' 			=> $result['scenicid'],
			'productid' 		=> $result['productid'],
			'fk_user' 			=> $result['order_info']['fk_user'],
			'copies' 			=> $result['order_info']['copies'],
			'out_trade_no'		=> $result['order_info']['out_trade_no'],
			'corp_id'			=> $result['order_info']['seller_fk_corp']
		);

		return $return;
	}

	private function getUser($fk_user){
		$params = array(
			'uid' => $fk_user,
			'ext_mobile' => GET_USER_EXT_MOBILE_YES
		);
		$Obj = $this->callInnerApiDiy(API_HOST_OPEN_USER, 'user/get_by_id.json', $params, METHOD_GET);
		if($Obj->error < 0){
			$msg = "下单时，获取用户信息失败";
			$array = array(
				'msg' => $msg,
				'param' => array(
					'fk_user' => $fk_user
				)
			);
			log_message('error',var_export($array,true));
			return false;
		}
		$user_json = json_encode($Obj);
		$userinfo = json_decode($user_json,true);
		$result = $userinfo['result'];
		if(empty($result['mobile'])){
			$msg = "下单时，获取用户信息成功，但手机号不存在";
			$array = array(
				'msg' => $msg,
				'param' => array(
					'fk_user' => $fk_user,
					'userinfo' => $result
				)
			);
			log_message('error',var_export($array,true));
			return false;
		}

		return $result['mobile'];

	}

}
