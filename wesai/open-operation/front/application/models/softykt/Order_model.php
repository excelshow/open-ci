<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 金飞鹰订单model
 */
class Order_model extends DIY_Model {

    /**
     * 核销
     * @param $orderid
     * @param $orderDetailid
     * @param $number
     * @return bool|object
     */
    public function orderTest($orderid, $orderDetailid, $number){
        //判断订单是否存在 且支付 判断是否核销 判断人数是否对应
        $param = array(
            'orderid' => $orderid,
            'orderDetailid' => $orderDetailid,
            'number' => $number
        );
        $requests[] = $this->request('api_host_open_operation', 'softykt/order/orderTest.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 更新数据库中的token
     */
    public function updToken($id,$token){
        $param = array(
            'token_id' => $id,
            'token' => $token
        );
        $requests[] = $this->request('api_host_open_operation', 'softykt/softykt/modify.json', $param, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }

	
}
