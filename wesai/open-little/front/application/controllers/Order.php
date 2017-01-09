<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

/**
 * 下单api类
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
        $this->load->model('Openapi_contest_model');
        $this->load->helper('diy');
    }

    /**
     * 去接口下单，并且存储起来
     * @access public
     * @return void
     */
    public function create(){
        $item_id   = $this->input->post('item_id', true);
        $mobile    = $this->input->post('mobile', true);
        $number    = $this->input->post('number', true);
        $form_info = $this->input->post('form_info', true);
        if(empty($item_id) || empty($mobile) || empty($number) || empty($form_info)){
            return $this->displayError(Error_Code::ERROR_PARAM);
        }
        $form_info = array_values(json_decode($form_info, true));
        $form_info = json_encode($form_info);

        $params = compact('item_id', 'mobile', 'number', 'form_info');
        $order = $this->Openapi_contest_model->create_order($params);
        if(empty($order)){
            return $this->displayError(Error_Code::ERROR_CONTEST_CREATE_ORDER);
        }
        if(!empty($order->error) || $order->error < 0){
            return $this->displayError($order->error, $order->info);
        }
        $order_no = $order->result->order_no;
        $params['order_no'] = $order_no;
        //存储订单信息
        $result = $this->Order_model->create($params);
        if(!empty($result->error) || $result->error < 0){
            return $this->displayError($result->error, $result->info);
        }
        return $this->display($result);
    }

    /**
     * 下单支付后调用成功接口
     */
    public function complete(){
        $order_no = $this->input->post('order_no', true);
        if(empty($order_no)){
            return $this->displayError(Error_Code::ERROR_PARAM);
        }
        $params   = compact('order_no');
        $result = $this->Openapi_contest_model->complete_order($params);
        if(empty($result)){
            return $this->displayError(Error_Code::ERROR_CONTEST_COMPLETE_ORDER);
        }
        if(!empty($result->error) || $result->error < 0){
            return $this->displayError($result->error, $result->info);
        }
        log_message_to_file('complete','order_no: '.$order_no . ' rl=' .json_encode($result->data));

        $params['complete_info'] = json_encode($result->data);
        //存储核销码信息
        $result = $this->Order_model->complete($params);
        if(!empty($result->error) || $result->error < 0){
            return $this->displayError($result->error, $result->info);
        }
        return $this->display($result);
    }




    }
