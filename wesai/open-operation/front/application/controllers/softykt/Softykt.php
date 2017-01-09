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
        $this->load->model('softykt/Product_model');
        $this->load->model('softykt/Order_model');
    }


    /**
     * 回调函数
     * @param signature 签名
     * @param access_token token
     * @param timestamp 时间戳
     * @return str '666'
     */
    public function callback(){
        log_message('error',var_export($_REQUEST,true));

        $signature = $_REQUEST['signature'];      //签名
        $token     = $_REQUEST['access_token'];   //token
        $param = $_REQUEST;
        unset($param['signature']);
        unset($param['access_token']);
        $param['token'] = $token;
        if(!empty($_REQUEST['spare'])){
            $param['spare'] = $spare = $_REQUEST['spare'];//加密串，只有验证URL时发送
        }
        if(!empty($_REQUEST['type'])){
            $param['type'] = $type  = $_REQUEST['type'];//回调类型，验证URL时不发送
        }else{
            $type = '';
        }

        log_message('error',var_export($param,true));

        $sign = $this->get_signature($param);
        if($sign == $signature){
            switch($type){
                // 商品修改
                case self::$TypeProductSave:
                    $proParam = array(
                        'productid'         => $_REQUEST['productid'],
                        'scenicid'          => $_REQUEST['scenicid'],
                        'name'              => $_REQUEST['productname'],
                        'usepeoplenum'      => $_REQUEST['usepeoplenum'], //使用人数
                        'producttype'       => $_REQUEST['producttype'],  //产品类型id（1:票；2：教学；3-商品；4-酒店；5-餐饮；6-其他）
                        'validbegindate'    => $_REQUEST['validbegindate'],
                        'validenddate'      => $_REQUEST['validenddate'],
                        'price'             => $_REQUEST['price'],
                        'agentprice'        => $_REQUEST['agentprice'],
                        'returnflag'        => $_REQUEST['returnflag'],
                        'fee'               => $_REQUEST['sellprice'],
                        'numberflag'        => $_REQUEST['numberflag'], //每日数量限制（0代表无限制）
                        'number'            => $_REQUEST['number'],
                        'memo'              => $_REQUEST['memo'],
                        'webpic'            => $_REQUEST['webpic'],
                        'consumebegindate'  => $_REQUEST['consumebegindate'],
                        'consumeenddate'    => $_REQUEST['consumeenddate'],
                        'consumestate'      => $_REQUEST['consumestate'],
                        'hour'              => $_REQUEST['hour'],
                    );
                    $result = $this->productModify($proParam);
                    break;
                // 商品上下线
                case self::$TypeProductStateSave:
                    $scenicid   = $_REQUEST['scenicid'];
                    $productid  = $_REQUEST['productid'];
                    $useflag    = $_REQUEST['useflag'];
                    $result = $this->productStateSave($scenicid, $productid, $useflag);
                    //log_message('error',$result);

                    break;
                // 商品删除
                case self::$TypeProductDelete:
                    $scenicid   = $_REQUEST['scenicid'];
                    $productid  = $_REQUEST['productid'];
                    $result = $this->productDelete($scenicid, $productid);
                    break;
                // 验票
                case self::$TypeOrderTest: //post
                    $orderid        = $_REQUEST['ordernumber'];
                    $orderdetailid  = $_REQUEST['orderdetailid'];
                    $number         = $_REQUEST['number'];
                    $result = $this->orderTest($orderid, $orderdetailid, $number);
                    break;
                //退票
                case self::$TypeReturnTicket: //post
                    $orderid        = $_REQUEST['ordernumber'];
                    $orderdetailid  = $_REQUEST['orderdetailid'];
                    $number         = $_REQUEST['number'];
                    $result = $this->orderReturn($orderid, $orderdetailid, $number);
                    break;
                // 默认验证回调
                default:
                    $str = unserialize(base64_decode($spare));
                    echo $str[2];
                    log_message('error',$str[2]);
                    break;
            }
        }
        echo  666;exit;
    }

    /**
     * 商品信息修改
     */
    private function productModify($param){
        $result = $this->Product_model->productModify($param);
        log_message('error',var_export($result,true));

        if(empty($result)){
            log_message('error','商品信息修改失败,参数：');
            log_message('error',var_export($param,true));
            echo json_encode($this->returnError(Error_Code::ERROR_PRODUCT_STATE_SAVE_FAILED));
            exit;
        }
        return $result;
    }


    /**
     * 商品上下架
     * @param $scenicid 景区id
     * @param $productid 产品id
     * @param $useflag 上线状态（0:上线；1-下线）
     */
    private function productStateSave($scenicid, $productid, $useflag){
        $result = $this->Product_model->productStaticSve($scenicid, $productid);
        if(empty($result)){
            log_message('error','商品上下架失败，参数：');
            log_message('error','scenicid:'.$scenicid . '; productid:' .$productid. '; useflag:' .$useflag);
            echo json_encode($this->returnError(Error_Code::ERROR_PRODUCT_STATE_SAVE_FAILED));
            exit;
        }
        return $result;
    }

    /**
     * 删除商品
     * @param $scenicid 景区id
     * @param $productid 产品id
     */
    private function productDelete($scenicid, $productid){
        $result = $this->Product_model->productStaticSve($scenicid, $productid);
        if(empty($result)){
            log_message('error','商品上删除失败，参数：');
            log_message('error','scenicid:'.$scenicid . '; productid:' .$productid);
            echo json_encode($this->returnError(Error_Code::ERROR_PRODUCT_DELETE_FAILED));
            exit;
        }
        return $result;
    }

    /**
     * 退票
     * @param $orderid
     * @param $orderDetailid
     * @param $number
     */
    private function orderReturn($orderid, $orderDetailid, $number){
        $result = $this->Order_model->orderTest($orderid, $orderDetailid, $number);
        if(empty($result)){
            echo json_encode($this->returnError(Error_Code::ERROR_ORDER_TEST_FAILED));
            exit;
        }
        return $result;
    }

    /**
     * 验票
     * @param $orderid
     * @param $orderDetailid
     * @param $number
     */
    private function orderTest($orderid, $orderDetailid, $number){
        $result = $this->Order_model->orderTest($orderid, $orderDetailid, $number);
        if(empty($result)){
            echo json_encode($this->returnError(Error_Code::ERROR_ORDER_TEST_FAILED));
            exit;
        }
        return $result;
    }







}