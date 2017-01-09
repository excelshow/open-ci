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

    /**
     * import_product_post
     *  导入金飞鹰产品
     *  但，导入逻辑不应该写在接口里面
     * @access public
     * @return void
     */
    public function import_product_post(){
        $corp_id = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $user_id = $this->post_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $info = $this->getCorpInfo($corp_id);

        $page = 1;
        $all_number = 0;
        $exits_number = 0;
        $syn_number = 0;
        while(true){
            // 获取产品列表
            $products = $this->getProductsFromSoftykt($info, $page);
            if(empty($products)){
                break;
            }
            // 导入产品
            $this->importProducts($corp_id, $user_id, $products, $all_number, $exits_number, $syn_number);

            $page++;
        }

        $result = array();
        $result['all_number'] = $all_number;
        $result['syn_number'] = $syn_number;
        $result['exits_number'] = $exits_number;

        return $this->response_object($result);
    }

    private function getProductsFromSoftykt($corp_info, $page){
        $param = array(
            'page' => $page,
            'access_token' => $corp_info['token']
        );

        $products = $this->callSoftyktProductApi($param);
        if(empty($products)){
            return false;
        }
        return $products->result->product;
    }

    private function importProducts($corp_id, $user_id, $products, &$all_number, &$exits_number, &$syn_number){
        $all_number += count($products);
        foreach($products as $k => $info) {
            //需根据景区查询对应的活动id
            $contest = $this->Product_model->get_by_scenicid_productid($info->scenicid, $info->productid);
            if(empty($contest)){
                log_message('error', '导入产品出错 Product_model->get_by_scenicid_productid result='.json_encode($contest));
                continue;
            }
            if(empty($contest->result)){
                $result = $this->Product_model->create($info, $corp_id, $user_id);
                if(empty($result) || $result->error != 0){
                    log_message('error', '导入产品出错 Product_model->create result='.json_encode($result));
                }else{
                    $syn_number++;
                }
            }else{
                $exits_number++;
            }
        }
    }

}
