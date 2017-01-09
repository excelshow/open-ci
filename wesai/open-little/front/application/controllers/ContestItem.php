<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

/**
 * 获取项目api类
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
    }

    /**
     * 获取数据库中已存在的活动列表
     * @access public
     * @return void
     */
    public function save_contest_item_list(){
        $page = 1;
        $size = 10;
        $intro  = 0;
        $createNumber = 0;

        while(true){
            $list = $this->Contest_model->list_contest($page, $size, $intro);

            if(empty($list) || $list->error < 0){
                return $this->displayError(Error_Code::ERROR_GET_CONTEST_LIST_FAILED);
            }
            if(count($list->data) < 1){
                break;
            }
            foreach($list->data as $contest){
                $info = $this->get_contest_item($contest->contest_id);
                $createNumber = $createNumber+$info;
            }

            $page++;
        }
        $result = array(
            'createNumber'  => $createNumber,
        );
        return $this->displayResult($result);
    }

    private function get_contest_item($contest_id){
        $page = 1;
        $size = 10;
        $createNumber = 0;
        while(true) {
            $param = array(
                'contest_id' => $contest_id,
                'page'       => $page,
                'size'       => $size
            );
            $headers = $this->getHeaders($param);
            $itemList = $this->ContestItem_model->get_contest_item_list($param, $headers);
            if(empty($itemList)){
                return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEM_LIST_FAILED);
            }
            if($itemList->error < 0){
                return $this->displayError($itemList->error, $itemList->info);
            }
            if(empty($itemList->data) || count($itemList->data) < 1){
                break;
            }
            foreach($itemList->data as $contestItem){
                $insertId = $this->ContestItem_model->create_contest_item($contestItem, $contest_id);
                if($insertId->error < 0){
                    if($insertId->error != -204){ //忽略因为数据已存在而记录错误log信息
                        log_message('error', '新增项目出错 pm=' . json_encode($contestItem) . ' rl='.json_encode($insertId));
                    }
                    continue;
                }
                $createNumber++;
            }
            $page++;
        }
        return $createNumber;


    }

    /**
     * 获取 数据库中项目列表
     * @return bool
     */
    public function list_item_data(){
        $page           = $this->input->get('page', true);
        $size           = $this->input->get('size', true);
        $out_contest_id = $this->input->get('contest_id', true);

        $list = $this->ContestItem_model->list_contest_item($page, $size, $out_contest_id);
        if(empty($list)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEM_LIST_FAILED);
        }
        if(!empty($list->error) || $list->error < 0){
            return $this->displayError($list->error, $list->info);
        }
        return $this->displayDate($list);

    }



}
