<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

/**
 * 获取表单类
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
        $this->load->model('ContestItem_model');
        $this->load->model('ContestItemForm_model');
    }

    /**
     * 获取接口中的表单类
     * @access public
     * @return void
     */
    public function save_item_form_list(){
        $page = 1;
        $size = 10;
        $createNumber = 0;

        while(true){
            $list = $this->ContestItem_model->list_contest_item($page, $size);

            if(empty($list) || $list->error < 0){
                return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEM_LIST_FAILED);
            }
            if(count($list->data) < 1){
                break;
            }
            foreach($list->data as $item){
                $info = $this->get_item_form($item->contest_item_id);
                $createNumber = $createNumber+$info;
            }

            $page++;
        }
        $result = array(
            'createNumber'  => $createNumber,
        );
        return $this->displayResult($result);
    }

    private function get_item_form($contest_item_id){
        $createNumber = 0;
        $param = array(
            'item_id' => $contest_item_id
        );
        $headers = $this->getHeaders($param);
        $fromList = $this->ContestItemForm_model->get_item_form_list($param, $headers);
        if(empty($fromList)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEM_LIST_FAILED);
        }
        if($fromList->error < 0){
            log_message('error', '新增表单出错  rl='.json_encode($fromList));
            return $createNumber;
        }
        if(empty($fromList->data) || count($fromList->data) < 1){
            return $createNumber;
        }
        foreach($fromList->data as $from){
            $insertId = $this->ContestItemForm_model->create_item_form($from, $contest_item_id);
            if($insertId->error < 0){
                if($insertId->error != -208){ //忽略因为数据已存在而记录错误log信息
                    log_message('error', '新增表单出错 pm=' . json_encode($from) . ' rl='.json_encode($insertId));
                }
                continue;
            }
            $createNumber++;
        }

        return $createNumber;


    }

    /**
     * 获取 数据库中表单列表
     * @return bool
     */
    public function list_item_form_data(){
        $item_id = $this->input->get('item_id', true);

        $list = $this->ContestItemForm_model->list_item_form($item_id);
        if(empty($list)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEM_FROM_LIST_FAILED);
        }
        if(!empty($list->error) || $list->error < 0){
            return $this->displayError($list->error, $list->info);
        }
        return $this->displayDate($list);

    }



}
