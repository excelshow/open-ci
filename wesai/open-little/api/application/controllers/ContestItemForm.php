<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 第三方测试 表单类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class ContestItemForm extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ContestItemForm_model');
        $this->load->model('Openapi_model');
    }

    /**
     * 新增表单
     */
    public function create_post(){

        $param = $this->checkParam();
        //验证是否存在
        $fromInfo = $this->ContestItemForm_model->get_by_out_contest_item($param['out_form_item_id'], $param['out_contest_item_id']);
        if(!empty($fromInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_FROM_ALREADY_EXISTS);
        }
        $insertId = $this->ContestItemForm_model->create($param);
        if(empty($insertId)){
            return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_ITEM_FROM_FAILED);
        }

        return $this->response_insert($insertId);
    }

    private function checkParam(){
        $out_form_item_id    = $this->post_check('form_item_id', PARAM_NOT_NULL_NOT_EMPTY);
        $type                = $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_item_id = $this->post_check('contest_item_id', PARAM_NOT_NULL_NOT_EMPTY);
        $title               = $this->post_check('title', PARAM_NOT_NULL_EMPTY);
        $option_values       = $this->post_check('option_values', PARAM_NOT_NULL_EMPTY);
        $is_required         = $this->post_check('is_required', PARAM_NOT_NULL_EMPTY);
        $seq                 = $this->post_check('seq', PARAM_NOT_NULL_EMPTY);
        $state               = CONTEST_LIST_FORM_STATE_OK;

        $param = compact(
            'out_form_item_id', 'type', 'out_contest_item_id', 'title', 'option_values', 'is_required', 'seq', 'state'
        );
        return $param;
    }

    /**
     * 删除就得表单、新增新的表单
     */
    public function modify_post(){
        $out_contest_id      = $this->post_check('contest_id', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_item_id = $this->post_check('contest_items_id', PARAM_NOT_NULL_NOT_EMPTY);
        $list = $this->Openapi_model->list_form_items(array('item_id' => $out_contest_item_id));
        if(empty($list) || $list->error != 0){
            return $this->response_error(Error_Code::ERROR_CURL_ITEM_FROM_DETAIL_FAILED);
        }
        $result = $list->data;
        $insertCount = $this->ContestItemForm_model->modify($result, $out_contest_item_id);
        if(empty($insertCount)){
            return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_ITEM_FAILED);
        }

        return $this->response_insert($insertCount);

    }

    /**
     * 获取项目表单列表
     */
    public function list_contest_item_from_get(){
        $out_contest_item_id = $this->get_check('out_contest_item_id', PARAM_NOT_NULL_NOT_EMPTY);

        $list = $this->ContestItemForm_model->lists($out_contest_item_id);
        return $this->response_list($list);
    }



}
