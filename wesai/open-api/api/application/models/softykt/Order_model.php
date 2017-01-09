<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 金飞鹰订单model
 */
class Order_model extends DIY_Model {


    /**
     * 根据智慧体育订单号查询金飞鹰订单号
     * @param $orderNumber
     * @return bool|object
     */
    public function get_by_trade_no($order_id){
        $param = array(
            'orderid' => $order_id
        );
        $requests[] = $this->request('api_host_open_contest', 'softykt/order/get_by_trade_no.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 根据智慧体育订单号查询是否已经在金飞鹰下单
     * @param $trade_no
     * @return bool|object
     */
    public function checkorder($trade_no){
        $param = array(
            'trade_no' => $trade_no
        );
        $requests[] = $this->request('api_host_open_contest', 'softykt/order/checkout_trade_no.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }


	
}
