<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once __DIR__ . '/Base.php';

class Openapi extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Openapi_contest_model');
        $this->load->helper('signature');
    }

    /**
     * 获取活动列表
     * @return data
     */
    public function list_data(){
        $page   = $this->input->get('page', true);
        $size   = $this->input->get('size', true);
        $location = 0;
        if(empty($page) || empty($size)){
            return $this->displayError(Error_Code::ERROR_PARAM);
        }

        $params = compact('page', 'size', 'location');
        $this->addSign($params);
        $list = $this->Openapi_contest_model->list_contest($params);
        if(empty($list)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_LIST_FAILED);
        }
        if(!empty($list->error) || $list->error < 0){
            return $this->displayError($list->error, $list->info);
        }

        return $this->display($list);
    }

    public function detail(){
        $contest_id = $this->input->get('id', true);
        $intro = 1;
        $location = 1;

        $params = compact('contest_id', 'intro', 'location');
        $this->addSign($params);
        $contest = $this->Openapi_contest_model->get_by_id($params);
        if(empty($contest)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_DETAIL);
        }
        if(!empty($contest->error) || $contest->error < 0){
            return $this->displayError($contest->error, $contest->info);
        }

        return $this->display($contest);
    }

    public function items(){
        $contest_id = $this->input->get('id', true);
        $page = $this->input->get('page', true);
        $size = $this->input->get('size', true);

        $params = compact('contest_id', 'page', 'size');
        $this->addSign($params);
        $items = $this->Openapi_contest_model->list_items($params);
        if(empty($items)){
            return $this->displayError(Error_Code::ERROR_GET_CONTEST_ITEMS);
        }
        if(!empty($items->error) || $items->error < 0){
            return $this->displayError($items->error, $items->info);
        }

        return $this->display($items);
    }

    public function list_form_info(){
        $item_id = $this->input->get('id', true);

        $params = compact('item_id');
        $this->addSign($params);
        $items = $this->Openapi_contest_model->list_items($params);
        $items = $this->Openapi_contest_model->list_form_items($params);
        if(empty($items)){
            return $this->displayError(Error_Code::ERROR_LIST_ITEM_FORM);
        }
        if(!empty($items->error) || $items->error < 0){
            return $this->displayError($items->error, $items->info);
        }

        foreach($items->data as &$item){
            $value = array();
            $value['form_item_id'] = $item->form_item_id;
            $value['title'] = $item->title;
            $value['type'] = $item->type;
            if($item->title == "姓名"){
                $value['value'] = "rico";
            }elseif($item->title == "手机号"){
                $value['value'] = "18513831860";
            }
            $item->formatValue = json_encode($value);
        }

        return $this->display($items);
    }

    // post 有问题
    // 推测是 https 的原因
    public function create_order(){
        //$item_id = $this->input->post('id', true);
        //$phone = $this->input->post('phone', true);
        //$number = $this->input->post('number', true);
        //$form_info = $this->input->post('form_info', true);
        $item_id = $this->input->get('id', true);
        $mobile = $this->input->get('phone', true);
        $number = $this->input->get('number', true);
        $form_info = $this->input->get('form_info', true);
        $form_info = json_decode($form_info);
        $infos = array();
        foreach($form_info as $info){
            $infos[] = (array)json_decode($info);
        }
        $form_info = json_encode($infos);
        //print_r($infos);

        $params = compact('item_id', 'mobile', 'number', 'form_info');
        $this->addSign($params);
        $result = $this->Openapi_contest_model->create_order($params);
        if(empty($result)){
            return $this->displayError(Error_Code::ERROR_CONTEST_CREATE_ORDER);
        }
        if(!empty($result->error) || $result->error < 0){
            return $this->displayError($result->error, $result->info);
        }

        return $this->display($result);
    }

    public function complete_order(){
        $order_no = $this->input->get('order_no', true);

        $params = compact('order_no');
        $this->addSign($params);
        $result = $this->Openapi_contest_model->complete_order($params);
        if(empty($result)){
            return $this->displayError(Error_Code::ERROR_CONTEST_COMPLETE_ORDER);
        }
        if(!empty($result->error) || $result->error < 0){
            return $this->displayError($result->error, $result->info);
        }

        return $this->display($result);
    }

    private function addSign(&$params){
        $params['sign'] = create_signature($params, 'md5', 'e5e62175a68ebba5906714222d816514');
    }

}
