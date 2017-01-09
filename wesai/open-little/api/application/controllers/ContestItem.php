<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 第三方测试 项目类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class ContestItem extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contest_model');
        $this->load->model('ContestItem_model');
        $this->load->model('Openapi_model');
    }

    /**
     * 创建项目
     */
    public function create_post(){

        $param = $this->checkParam();
        //验证是否存在
        $contestitemInfo = $this->ContestItem_model->get_by_out_contest_item($param['out_contest_id'], $param['out_contest_item_id']);
        if(!empty($contestitemInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_ALREADY_EXISTS);
        }
        $insertId = $this->ContestItem_model->create($param);
        if(empty($insertId)){
            return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_ITEM_FAILED);
        }

        return $this->response_insert($insertId);
    }

    private function checkParam(){
        $out_contest_id     = $this->post_check('out_contest_id', PARAM_NOT_NULL_NOT_EMPTY);
        $name               = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_item_id= $this->post_check('contest_items_id', PARAM_NOT_NULL_NOT_EMPTY);
        $max_stock          = $this->post_check('max_stock', PARAM_NOT_NULL_EMPTY);
        $cur_stock          = $this->post_check('cur_stock', PARAM_NOT_NULL_EMPTY);
        $max_verify         = $this->post_check('max_verify', PARAM_NOT_NULL_EMPTY);
        $price              = $this->post_check('price', PARAM_NOT_NULL_NOT_EMPTY);
        $end_time           = $this->post_check('end_time', PARAM_NOT_NULL_EMPTY);
        $state              = $this->post_check('state', PARAM_NOT_NULL_NOT_EMPTY);
        $sell_number        = $this->post_check('sell_number', PARAM_NOT_NULL_EMPTY);
        $consume_start_time = $this->post_check('consume_start_time', PARAM_NOT_NULL_NOT_EMPTY);
        $consume_end_time   = $this->post_check('consume_end_time', PARAM_NOT_NULL_NOT_EMPTY);
        $refund_flag        = $this->post_check('refund_flag', PARAM_NOT_NULL_EMPTY);

        $contestInfo = $this->Contest_model->get_by_out_contest_id($out_contest_id);
        if(empty($contestInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }
        $max_stock = empty($max_stock)?0:$max_stock;
        $cur_stock = empty($cur_stock)?0:$cur_stock;
        $max_verify = empty($max_verify)?0:$max_verify;
        $refund_flag = empty($refund_flag)?0:$refund_flag;

        $param = compact(
            'name', 'out_contest_id', 'out_contest_item_id', 'max_stock', 'cur_stock', 'max_verify',
            'price', 'end_time', 'state', 'sell_number', 'consume_start_time', 'consume_end_time', 'refund_flag'
        );
        return $param;
    }

    public function modify_post(){
        $out_contest_id      = $this->post_check('contest_id', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_item_id = $this->post_check('contest_items_id', PARAM_NOT_NULL_NOT_EMPTY);
        $detail = $this->Openapi_model->item_detail(array('item_id' => $out_contest_item_id));
        if(empty($detail) || $detail->error != 0){
            return $this->response_error(Error_Code::ERROR_CURL_CONTEST_ITEM_DETAIL_FAILED);
        }
        $result = $detail->result;
        $param = array(
            'out_contest_id'      => $out_contest_id,
            'name'                => $result->name,
            'out_contest_item_id' => $out_contest_item_id,
            'max_stock'           => $result->max_stock,
            'cur_stock'           => $result->cur_stock,
            'max_verify'          => $result->max_verify,
            'price'               => $result->price,
            'end_time'            => $result->end_time,
            'state'               => $result->state,
            'sell_number'         => $result->sell_number,
            'consume_start_time'  => $result->consume_start_time,
            'consume_end_time'    => $result->consume_end_time,
            'refund_flag'         => $result->refund_flag,
        );

        //验证是否存在
        $itemInfo = $this->ContestItem_model->get_by_out_contest_item($out_contest_id, $out_contest_item_id);
        if(empty($itemInfo)){
            $insertId = $this->ContestItem_model->create($param);
            if(empty($insertId)){
                return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_ITEM_FAILED);
            }
            return $this->response_insert($insertId);
        }
        $updateId = $this->ContestItem_model->modify($param, $out_contest_id, $out_contest_item_id);
        if(empty($updateId) && $updateId != 0){
            return $this->response_error(Error_Code::ERROR_MODIFY_CONTEST_ITEM_FAILED);
        }

        return $this->response_update($updateId);
    }

    /**
     * 获取活动的项目列表
     */
    public function list_contest_item_get(){
        $page           = $this->get_check('page', PARAM_NOT_NULL_NOT_EMPTY);
        $size           = $this->get_check('size', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_id = $this->get_check('out_contest_id', PARAM_NULL_EMPTY);
        if(!empty($out_contest_id)){
            $contestInfo = $this->Contest_model->get_by_out_contest_id($out_contest_id);
            if(empty($contestInfo)){
                return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
            }
        }

        $list = $this->ContestItem_model->lists($page, $size, $out_contest_id);
        return $this->response_list($list);
    }



}
