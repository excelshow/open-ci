<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Softykt_Base.php';

/**
 * 金飞鹰对接api产品类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Product extends Softykt_Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/Product_model');
    }

//    /**
//     * 获取景区列表
//     */
//    public function get_scenic(){
//        $page = 1;
//        $scenic = 0;
//        while(true){
//            $param = array(
//                'page' => $page,
//            );
//            $scenic = $this->splitParam($param, SOF_DISTRIBUTORGETSCENICINFOURL);
//            if($scenic['rcode'] != '666'){
//                echo json_encode($this->returnError($scenic['rcode'], $scenic['result']));
//                exit;
//            }
//            log_message('error', var_export($scenic['result']['scenic'], true));
//
//            foreach($scenic['result']['scenic'] as $k => $info) {
//                $result = $this->Product_model->createScenic($info);
//                $json = json_encode($result);
//                $resultArr = json_decode($json,true);
//                if($resultArr['error'] < 0){
//                    $msg = " 金飞鹰景区添加失败";
//                }else{
//                    $msg = "金飞鹰景区添加成功";
//                    $scenic ++;
//                }
//                log_message('error',$msg);
//            }
//
//            $pageInfo = $scenic['result']['paging'];
//            if($pageInfo['total_pages'] < 2){
//                break;
//            }else{
//                $page ++ ;
//            }
//        }
//        return $scenic;
//    }



    /**
     * 获取产品列表
     * @return json
     */
    public function get_product(){
        //TODO corp_id user_id
        $corp_id = 1;
        $user_id = 10;
        $page = 1;
        $allNumber = 0;
        $synNumber = 0;
        while(true){
            $param = array(
                'page' => $page
            );
            $product = $this->splitParam($param, SOF_DISTRIBUTORGETPRODUCTURL);
            if($product['rcode'] != '666'){
                echo json_encode($this->returnError($product['rcode'], $product['result']));
                exit;
            }
            $allNumber += count($product['result']['product']);
            foreach($product['result']['product'] as $k => $info) {

                //需根据景区查询对应的活动id
                $contest = $this->Product_model->get_by_scenicid_productid($info['scenicid'], $info['productid']);
                $json = json_encode($contest);
                $contestArr = json_decode($json,true);
                if(!empty($contest) && $contestArr['error'] > -1){ //存在跳过
                    //存在
                    $synNumber ++;
                }else{
                    if($contestArr['error'] < 0){
                        log_message('error','同步产品时，确认是否已存在出错，错误原因：'.$contest['info']);
                    }
                    $result = $this->Product_model->create($info, $corp_id, $user_id);
                    $json = json_encode($result);
                    $resultArr = json_decode($json,true);
                    if($resultArr['error'] < 0){
                        log_message('error','同步产品时，确认是否已存在出错');
                    }
                    $synNumber ++;
                }
            }

            $pageInfo = $product['result']['paging'];
            if($pageInfo < 2){
                break;
            }else{
                $page ++ ;
            }
        }
        echo "总数量：".$allNumber."   同步成功数量：".$synNumber;
        //print_r($product);die;
    }

    /**
     * 根据商品id 获取商品的已售数量
     * @param $productID
     * @return mixed
     */
    public function get_product_num($productID){
        $param = array(
            'ProductID' => $productID
        );
        $product = $this->splitParam($param, SOF_DISTRIBUTORGETPRODUCTNUMURL);
        $json = json_encode($product);
        $productArr = json_decode($json,true);
        if($productArr['rcode'] != 666){
            $msg = " 金飞鹰获取产品已卖数量失败，失败原因：".$productArr['result'];
            log_message('error',$msg);exit;
        }
        return $productArr['result'];
    }






}