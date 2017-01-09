<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

/**
 * 获取活动api类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Contest extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Contest_model');
    }

    /**
     * 获取活动列表 存储在数据库中
     * @access public
     * @return void
     */
    public function save_list(){
        $page = 1;
        $size = 10;
        $location = 0; //是否返回行政区  在详情接口中获取
        $allNumber = 0;
        $extNumber = 0;
        $createNumber = 0;
        $failedNumber = 0;

        while(true){
            $param = compact('page', 'size', 'location');
            $headers = $this->getHeaders($param);
            $list = $this->Contest_model->get_contest_list($param, $headers);
            if(empty($list)){
                return $this->displayError(Error_Code::ERROR_GET_CONTEST_LIST_FAILED);
            }
            if(!empty($list->error)){
                return $this->displayError($list->error, $list->info);
            }
            if(empty($list->data) || count($list->data) < 1){
                break;
            }
            $allNumber = $allNumber + count($list->data);
            foreach($list->data as $contest){
                $detail = $this->get_contest_detail($contest->contest_id);
                if(empty($detail)){
                    continue;
                }
                $insertInfo = $this->Contest_model->create_contest($detail);

                if($insertInfo->error == -202){
                    $extNumber++;
                }elseif($insertInfo->error < 0){
                    log_message('error', var_export($insertInfo, true));
                    $failedNumber++;
                }else{
                    $createNumber++;
                }
            }
            $page++;
        }
        $result = array(
            'allNumber'     => $allNumber,
            'extNumber'     => $extNumber,
            'createNumber'  => $createNumber,
            'failedNumber'  => $failedNumber
        );
        return $this->displayResult($result);
    }

    private function get_contest_detail($contest_id){
        $param = array(
            'contest_id' => $contest_id,
            'intro'      => 1,          //获取图文详情
            'location'   => 1,          //获取行政区划
        );
        $headers = $this->getHeaders($param);
        $detail = $this->Contest_model->get_contest_detail($param, $headers);
        if(empty($detail) || ($detail->error<0)){
            log_message('error', '获取活动详情出错，rl='.json_encode($detail));
            return false;
        }
        return $detail->result;
    }

    /**
     * 获取 数据库中活动列表
     * @return bool
     */
    public function list_data(){
        $page   = $this->input->get('page', true);
        $size   = $this->input->get('size', true);
        $intro  = $this->input->get('intro', true);
        if(empty($page) || empty($size)){
            return $this->displayError(Error_Code::ERROR_PARAM);
        }

        $list = $this->Contest_model->list_contest($page, $size, $intro);
        if(empty($list)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_LIST_FAILED);
        }
        if(!empty($list->error) || $list->error < 0){
            return $this->displayError($list->error, $list->info);
        }

        return $this->displayResult($list->data);

    }

}
