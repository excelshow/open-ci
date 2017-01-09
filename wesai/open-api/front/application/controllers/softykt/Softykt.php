<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Softykt_Base.php';

/**
 * 金飞鹰对接回调类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Softykt extends Softykt_Base
{
    static $TypeProductSave        = "118000"; //修改商品信息
    static $TypeProductStateSave   = "118001"; //修改商品状态
    static $TypeProductDelete      = "118002"; //删除商品
    static $TypeOrderTest          = "118100"; //验票
    static $TypeReturnTicket       = "118200"; //退票
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $redisConfig = $this->config->item('redismq');
        $this->load->library('Redis_List_Client', $redisConfig);
    }

    /**
     * 回调函数
     * @param signature 签名
     * @param access_token token
     * @param timestamp 时间戳
     * @return str '666'
     */
    //notify
    public function callback(){
        if(empty($_REQUEST['signature'])){
            echo json_encode($this->returnError(Error_Code::ERROR_PARAM));
            exit;
        }
        log_message_to_file('softykt_callback', json_encode($_REQUEST));
        $signature = $_REQUEST['signature'];      //签名

        $param = $this->checkParam();
        $spare = !empty($param['spare']) ? $param['spare'] : '';
        $type  = !empty($param['type']) ? $param['type'] : '';
        $sign = $this->get_signature($param);
        if($sign == $signature){
            switch($type){
                // 商品修改
                case self::$TypeProductSave:
                    $proParam = $this->checkProductParam($param);
                    $this->productModify($proParam, SOF_TYPE_PRODUCT_SAVE);
                    break;
                //商品上下线
                case self::$TypeProductStateSave:
                    $scenicid   = $param['scenicid'];
                    $productid  = $param['productid'];
                    $useflag    = $param['useflag'];
                    $this->productStateSave($scenicid, $productid, $useflag, SOF_TYPE_PRODUCT_STATE_SAVE);
                    break;
                //商品删除
                case self::$TypeProductDelete:
                    $scenicid   = $param['scenicid'];
                    $productid  = $param['productid'];
                    $this->productDelete($scenicid, $productid, SOF_TYPE_PRODUCT_DELETE);
                    break;
                //验票
                case self::$TypeOrderTest: //post
                    $orderid        = $param['ordernumber'];
                    $orderdetailid  = $param['orderdetailid'];
                    $number         = $param['number'];
                    $this->orderTest($orderid, $orderdetailid, $number, SOF_TYPE_ORDER_TEST);
                    break;
                //退票
                case self::$TypeReturnTicket: //post
                    $orderid        = $param['ordernumber'];
                    $orderdetailid  = $param['orderdetailid'];
                    $number         = $param['number'];
                    $this->orderReturn($orderid, $orderdetailid, $number, SOF_TYPE_RETURN_TICKET);
                    break;
                //默认验证回调
                default:
                    $str = unserialize(base64_decode($spare));
                    echo $str[2];
                    break;
            }
            echo SOF_API_CODE_SUCCESS;exit;
        }else{
            echo json_encode($this->returnError(Error_Code::ERROR_SOFTYKT_TEST_SIGN_FAILED));
            exit;
        }
    }

    //获取验证sign的参数
    private function checkParam(){
        $param          = $_REQUEST;
        $param['token'] = $param['access_token'];
        unset($param['signature']);
        unset($param['access_token']);
        if(!empty($_REQUEST['spare'])){
            $param['spare'] = $_REQUEST['spare'];//加密串，只有验证URL时发送
        }
        if(!empty($_REQUEST['type'])){
            $param['type'] = $_REQUEST['type'];//回调类型，验证URL时不发送
        }
        return $param;
    }

    //整理商品修改的参数
    private function checkProductParam($param){
        return array(
            'productid'         => $param['productid'],
            'scenicid'          => $param['scenicid'],
            'name'              => $param['productname'],
            'usepeoplenum'      => $param['usepeoplenum'], //使用人数
            'producttype'       => $param['producttype'],  //产品类型id（1:票；2：教学；3-商品；4-酒店；5-餐饮；6-其他）
            'validbegindate'    => $param['validbegindate'],
            'validenddate'      => $param['validenddate'],
            'price'             => $param['price'],
            'agentprice'        => $param['agentprice'],
            'returnflag'        => $param['returnflag'],
            'fee'               => $param['sellprice'],
            'numberflag'        => $param['numberflag'], //每日数量限制（0代表无限制）
            'number'            => $param['number'],
            'memo'              => $param['memo'],
            'webpic'            => $param['webpic'],
            'consumebegindate'  => $param['consumebegindate'],
            'consumeenddate'    => $param['consumeenddate'],
            'consumestate'      => $param['consumestate'],
            'hour'              => $param['hour']
        );
    }

    /**
     * 商品信息修改
     */
    private function productModify($param, $type){
        $storage = $param;
        $storage['type'] = $type;
        $this->setRedis($storage, $msg = "modify_product");
    }


    /**
     * 商品上下架
     * @param $scenicid 景区id
     * @param $productid 产品id
     * @param $useflag 上线状态（0:上线；1-下线）
     */
    private function productStateSave($scenicid, $productid, $useflag, $type){

        $storage = array(
            'type'      => $type,
            'scenicid'  => $scenicid,
            'productid' => $productid,
            'useflag'   => $useflag
        );
        $this->setRedis($storage, $msg = "save_product_state");
    }

    /**
     * 删除商品
     * @param $scenicid 景区id
     * @param $productid 产品id
     */
    private function productDelete($scenicid, $productid, $type){
        $storage = array(
            'type'      => $type,
            'scenicid'  => $scenicid,
            'productid' => $productid
        );
        $this->setRedis($storage, $msg = "delete_product");
    }

    /**
     * 退票
     * @param $orderid
     * @param $orderDetailid
     * @param $number
     */
    private function orderReturn($orderid, $orderDetailid, $number, $type){

        $storage = array(
            'type'          => $type,
            'orderid'       => $orderid,
            'orderdetailid' => $orderDetailid,
            'number'        => $number
        );
        $this->setRedis($storage, $msg = "back_order");
    }

    /**
     * 验票
     * @param $orderid
     * @param $orderDetailid
     * @param $number
     */
    private function orderTest($orderid, $orderDetailid, $number, $type){
        $storage = array(
            'type'          => $type,
            'orderid'       => $orderid,
            'orderdetailid' => $orderDetailid,
            'number'        => $number
        );
        $this->setRedis($storage, $msg = "test_order");
    }

    /**
     * 存储到redis
     * @param $param
     * @param $errormsg
     */
    private function setRedis($param,$errormsg){
        $ret = $this->redis_list_client->LeftPush(MQ_TOPIC_SOFTYKT_EXT_CALLBACK, json_encode($param));
        log_message('error', var_export($param, true));

        log_message('error', $ret);
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
