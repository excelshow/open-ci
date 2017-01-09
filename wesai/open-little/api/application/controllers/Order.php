<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 第三方测试 订单类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Order extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Order_model');
        $this->load->model('ContestItem_model');
        $this->load->helper('diy');
    }

    /**
     * 创建订单
     */
    public function create_post(){
        $param = $this->checkParam();
        //验证是否存在
        $orderInfo = $this->Order_model->get_by_out_trade_no($param['out_trade_no']);
        if(!empty($orderInfo)){
            return $this->response_error(Error_Code::ERROR_ORDER_ALREADY_EXISTS);
        }
        //查询最大核销次数
        $itemInfo = $this->ContestItem_model->get_by_out_item($param['out_contest_item_id']);
        if(empty($itemInfo)){
            log_message_to_file("create_order", ' pm=' . json_encode($param));
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_NOT_EXISTS);
        }
        $param['max_verify'] = $itemInfo['max_verify'];
        $insertId = $this->Order_model->create($param);
        if(empty($insertId)){
            return $this->response_error(Error_Code::ERROR_CREATE_ORDER_FAILED);
        }

        return $this->response_insert($insertId);
    }
    private function checkParam(){
        $out_trade_no       = $this->post_check('order_no', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_item_id= $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY);
        $from_info          = $this->post_check('form_info', PARAM_NOT_NULL_NOT_EMPTY);
        $number             = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY);
        $mobile             = $this->post_check('mobile', PARAM_NOT_NULL_NOT_EMPTY);
        $state              = CONTEST_ORDER_STATE_OK;

        $param = compact(
            'out_trade_no', 'out_contest_item_id', 'from_info', 'number', 'mobile', 'state'
        );
        return $param;
    }

    /**
     * 存储核销信息
     */
    public function complete_post(){
        $out_trade_no  = $this->post_check('order_no', PARAM_NOT_NULL_NOT_EMPTY);
        $complete_info = $this->post_check('complete_info', PARAM_NOT_NULL_NOT_EMPTY);
        //验证是否存在
        $orderInfo = $this->Order_model->get_by_out_trade_no($out_trade_no);
        if(empty($orderInfo)){
            return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
        }
        if($orderInfo['state'] != CONTEST_ORDER_STATE_OK){
            return $this->response_error(Error_Code::ERROR_CONTEST_ORDER_STATE);
        }
        $complete_info = json_decode($complete_info);
        $insertId = $this->Order_model->order_complete($complete_info, $out_trade_no, $orderInfo['pk_order']);
        if(empty($insertId)){
            return $this->response_error(Error_Code::ERROR_CREATE_ORDER_COMPLETE_FAILED);
        }
        return $this->response_insert($insertId);
    }

    /**
     * 核销订单信息ing
     */
    public function complete_order_post(){
        $out_trade_no  = $this->post_check('out_trade_no', PARAM_NOT_NULL_NOT_EMPTY);
        $code          = $this->post_check('verify_code', PARAM_NOT_NULL_NOT_EMPTY);
        $verify_number = $this->post_check('verify_number', PARAM_NOT_NULL_NOT_EMPTY);
        $max_verify    = $this->post_check('max_verify', PARAM_NOT_NULL_NOT_EMPTY);
        //验证订单号是否存在
        $orderInfo = $this->Order_model->get_by_out_trade_no($out_trade_no);
        if(empty($orderInfo)){
            return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
        }
        //验证核销信息是否存在
        $completeInfo = $this->Order_model->get_by_verify_code($code);
        if(empty($completeInfo)){
            return $this->response_error(Error_Code::ERROR_ORDER_COMPLETE_NOT_EXISTS);
        }elseif($completeInfo['fk_order'] != $orderInfo['pk_order']){
            return $this->response_error(Error_Code::ERROR_ORDER_COMPLETE_ORDER_ID);
        }elseif($completeInfo['verify_number'] >= $verify_number){
            return $this->response_error(Error_Code::ERROR_ORDER_COMPLETE_VERIFY_NUMBER);
        }elseif($completeInfo['max_verify'] < $verify_number){
            return $this->response_error(Error_Code::ERROR_ORDER_COMPLETE_VERIFY_NUMBER);
        }

        $state = $max_verify == $verify_number?CONTEST_ORDER_COMPLETE_STATE_END:CONTEST_ORDER_COMPLETE_STATE_OK;
        $param = compact('verify_number', 'state');
        $update = $this->Order_model->modify_complete($param, $code, $orderInfo);

        return $this->response_update($update);
    }









}
