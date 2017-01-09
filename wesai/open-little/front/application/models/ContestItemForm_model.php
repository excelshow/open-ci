<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取表单信息 model
 */
class ContestItemForm_model extends DIY_Model {


    /**
     * 获取表单列表
     * @param $param 接口参数
     * @param $headers header参数
     * @return bool|object
     */
    public function get_item_form_list($param, $headers){

        $requests[] = $this->request('api_host_open_wesai_zhty', 'contest/item/list_form.json', $param, 'GET', $headers);
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }


    /**
     * 存储活动信息
     * @param $paramObj object
     * @return bool|object
     */
    public function create_item_form($paramObj, $out_contest_id){
        $param = array(
            'form_item_id'    => $paramObj->form_item_id,
            'type'            => $paramObj->type,
            'contest_item_id' => $out_contest_id,
            'title'           => $paramObj->title,
            'option_values'   => $paramObj->option_values,
            'is_required'     => $paramObj->is_required,
            'seq'             => $paramObj->seq,
        );
        $requests[] = $this->request('api_host_open_little', '/ContestItemForm/create.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 数据库中获取项目的表单列表
     * @param $out_contest_id
     * @return bool|object
     */
    public function list_item_form($item_id){
        $param = array(
            'out_contest_item_id' => $item_id
        );
        $requests[] = $this->request('api_host_open_little', '/ContestItemForm/list_contest_item_from.json', $param, 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }





}
