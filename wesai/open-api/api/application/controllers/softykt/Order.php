<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Softykt_Base.php';

/**
 * 金飞鹰对接订单类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Order extends Softykt_Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/Softykt_model');
        $this->load->model('softykt/Order_model');
    }


    /**
     * 下单
     * @param onlycode 唯一随机码
     * @param scenicid 景区id
     * @param mobile   电话号
     * @param productid 产品id：格式（id1，id2，id3…）
     * @param number   验证数量：格式（数量1，数量2，数量3…）
     * @return mixd
     */
    public function place_order_post(){
        $scenicid   = $this->post_check('scenicid', PARAM_NOT_NULL_NOT_EMPTY);
        $productid  = $this->post_check('productid', PARAM_NOT_NULL_NOT_EMPTY);
        $number     = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY);
        $trade_no   = $this->post_check('trade_no', PARAM_NOT_NULL_NOT_EMPTY);
        $mobile     = $this->post_check('mobile', PARAM_NOT_NULL_NOT_EMPTY);
        $corp_id    = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $info = $this->getCorpInfo($corp_id);

        $onlycode = $this->onlycode($trade_no);
        $param = array(
            'onlycode'      => $onlycode,
            'scenicid'      => $scenicid,
            'productid'     => $productid,
            'number'        => $number,
            'mobile'        => $mobile,
            'access_token'  => $info['token']
            );

        $info = $this->callSoftyktPlaceOrderApi($param);
        if(empty($info)){
            return $this->response_error(Error_Code::ERROR_SOFTYKT_ORDER_PLACE_FAILED);
        }

        return $this->response_object($info);
    }


    /**
     * 重新发送消费码
     * @param order_id 订单id
     * @return mixd
     */
    public function order_sms_post(){

        $order_id = $this->post_check('order_id', PARAM_NOT_NULL_NOT_EMPTY);
        $corp_id  = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $info = $this->getCorpInfo($corp_id);

        $param = array(
            'ordernumber'  => $order_id,
            'access_token' => $info['token']
        );
        $info = $this->callSoftyktOrderSmsApi($param);
        if(empty($info)){
            return $this->response_error(Error_Code::ERROR_SOFTYKT_ORDER_SMS_FAILED);
        }
        return $this->response_object($info);
    }

    /**
     * 退票
     * @param orderid 订单id
     * @param orderdeatild 订单详情id 格式（id1，id2，id3…）
     * @param ordernum 退票的数量 格式（数量1，数量2，数量3…）
     * @return mixd
     */
//    public function returnOrder(){
//        $orderid        = $this->input->post('orderid', true);
//        $orderdeatild   = $this->input->post('orderdeatild', true);
//        $ordernum       = $this->input->post('ordernum', true);
//        if(empty($orderid) || empty($orderdeatild) || empty($ordernum)){
//            echo json_encode($this->returnError(Error_Code::ERROR_PARAM));
//            exit;
//        }
//        $param = array(
//            'orderid' => $orderid,
//            'orderdeatild' => $orderdeatild,
//            'ordernum' => $ordernum
//        );
//        $info = $this->callSoftyktProductApi($param, SOF_SCENIC_RETURNTICKET_URL);
//    }







}
