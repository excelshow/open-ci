<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 金飞鹰商品model
 */
class Product_model extends DIY_Model {

    /**
     * 修改商品上下架  、删除  默认下架活动
     * @param $scenicid
     * @param $productid
     * @param $useflag
     * @return bool|object
     */
    public function productStaticSve($scenicid, $productid){
        $param = array(
            'scenicid'  => $scenicid,
            'productid' => $productid,
        );
        $requests[] = $this->request('api_host_open_operation', 'softykt/product/productStaticSave.json', $param, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }


    /**
     * 修改商品信息
     * @param $param
     * @return bool|object
     */
    public function productModify($param){
        $requests[] = $this->request('api_host_open_operation', 'softykt/product/modify.json', $param, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }



    /**
     * 新建商品信息
     * @param $proInfo 商品的字段信息
     * @return bool|object
     */
    public function create($proInfo, $corp_id, $user_id){
        $paramPro = array(
            'corp_id'           => $corp_id,
            'user_id'           => $user_id,
            'productid'         => $proInfo['productid'],
            'scenicid'          => $proInfo['scenicid'],
            'name'              => $proInfo['productname'],
            'usepeoplenum'      => $proInfo['usepeoplenum'], //使用人数
            'producttype'       => $proInfo['producttype'],  //产品类型id（1:票；2：教学；3-商品；4-酒店；5-餐饮；6-其他）
            'validbegindate'    => $proInfo['validbegindate'],
            'validenddate'      => $proInfo['validenddate'],
            'price'             => $proInfo['price'],
            'agentprice'        => $proInfo['agentprice'],
            'returnflag'        => $proInfo['returnflag'],
            'fee'               => $proInfo['sellprice'],
            'numberflag'        => $proInfo['numberflag'], //每日数量限制（0代表无限制）
            'number'            => $proInfo['number'],
            //'state'           => $proInfo['useflag'] +1,  //上线状态（0上线、1下线）  默认为下架
            'memo'              => $proInfo['memo'],
            'webpic'            => $proInfo['webpic'],
            'consumebegindate'  => $proInfo['consumebegindate'],
            'consumeenddate'    => $proInfo['consumeenddate'],
            'consumestate'      => $proInfo['consumestate'],
            'hour'              => $proInfo['hour'],

        );

        $requests[] = $this->request('api_host_open_operation', 'softykt/product/create.json', $paramPro, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }


    /**
     * 根据景区id 查询活动id
     * @param $scenicid
     * @return bool|object
     */
    public function get_by_scenicid_productid($scenicid, $productid){
        $param = array(
            'scenicid' => $scenicid,
            'productid'=> $productid
        );
        $requests[] = $this->request('api_host_open_operation', 'softykt/product/get_by_scenicid_productid.json', $param, 'post');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }








}
