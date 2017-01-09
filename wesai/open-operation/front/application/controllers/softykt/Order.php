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
    public function placeOrder(){
        $scenicid   = $this->input->post('scenicid', true);
        $mobile     = $this->input->post('mobile', true);
        $productid  = $this->input->post('productid', true);
        $number     = $this->input->post('number', true);
        log_message('error',var_export($_REQUEST,true));
        if(empty($scenicid) || empty($mobile) || empty($productid) || empty($number)){
            echo json_encode($this->returnError(Error_Code::ERROR_PARAM));
            exit;
        }
        $onlycode = $this->onlycode();
        $param = array(
            'onlycode'  => $onlycode,
            'scenicid'  => $scenicid,
            'productid' => $productid,
            'mobile'    => $mobile,
            'number'    => $number
        );
        $info = $this->splitParam($param, SOF_DISTRIBUTORPUTORDERURL);
        log_message('error',var_export($info,true));
        if($info['rcode'] != '666'){
            echo json_encode($this->returnError(Error_Code::ERROR_PARAM));
            exit;
        }
        $rult = $info['result'];

        $order_id = $rult['ordernumber'];
        if($info['result']['onlycode'] != $onlycode){
            log_message('error','下单成功,但随机码错误,订单id为：'.$order_id);
        }

        //每日已卖 总量已卖
        //金飞鹰下单成功 需获取消费码 用于核销  TODO 智慧体育下单 同步订单or 走原来的订单流程
        print_r($info);
    }

    /**
     * 查询订单详情列表
     * @param order_id 订单号
     * @param Mobile 手机号
     * @param ValidBeginDate 开始时间
     * @param ValidEndDate 结束时间
     * @param page 分页
     * @return mixd
     */
    public function lists($order_id = null){
        if($order_id){
            $OrderNumber = $order_id;
        }else{
            $OrderNumber   = $this->input->post('order_id', true);
        }
        $Mobile     = $this->input->post('Mobile', true);
        $ValidBeginDate  = $this->input->post('ValidBeginDate', true);
        $ValidEndDate     = $this->input->post('ValidEndDate', true);
        $page = 1;

        $param = array(
            'OrderNumber' => $OrderNumber,
            'Mobile'      => $Mobile,
            'ValidBeginDate' => $ValidBeginDate,
            'ValidEndDate' => $ValidEndDate,
            'page'         => $page,
        );
        $info = $this->splitParam($param, SOF_DISTRIBUTORGETORDERURL);
        print_r($info);

    }

    /**
     * 重新发送消费码
     * @param order_id 订单id
     * @return mixd
     */
    public function orderSms(){
        $OrderNumber   = $this->input->post('order_id', true);
        if(empty($OrderNumber)){
            echo json_encode($this->returnError(Error_Code::ERROR_PARAM));
            exit;
        }

        $param = array(
            'ordernumber' => $OrderNumber
        );
        $info = $this->splitParam($param, SOF_DISTRIBUTORRESMSURL);
        if($info['rcode'] != 666){
            echo json_encode($this->returnError($info['rcode'], $info['result']));
            exit;
        }else{
            log_message('error',var_export($info,true));
        }
        //TODO

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
//        $info = $this->splitParam($param, SOF_DISTRIBUTORRETURNTICKETURL);//TODO
//    }







}