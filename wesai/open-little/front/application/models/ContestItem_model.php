<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取项目信息 model
 */
class ContestItem_model extends DIY_Model {


    /**
     * 获取活动列表
     * @param $param 接口参数
     * @param $headers header参数
     * @return bool|object
     */
    public function get_contest_item_list($param, $headers){

        $requests[] = $this->request('api_host_open_wesai_zhty', 'contest/item/list.json', $param, 'GET', $headers);
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }


    /**
     * 存储活动信息
     * @param $paramObj object
     * @return bool|object
     */
    public function create_contest_item($paramObj, $out_contest_id){
        $param = array(
            'out_contest_id'    => $out_contest_id,
            'name'               => $paramObj->name,
            'contest_items_id'   => $paramObj->contest_items_id,
            'max_stock'          => $paramObj->max_stock,
            'cur_stock'          => $paramObj->cur_stock,
            'max_verify'         => $paramObj->max_verify,
            'price'              => $paramObj->price,
            'end_time'           => $paramObj->end_time,
            'state'              => $paramObj->state,
            'sell_number'        => $paramObj->sell_number,
            'consume_start_time' => $paramObj->consume_start_time,
            'consume_end_time'   => $paramObj->consume_end_time,
            'refund_flag'        => $paramObj->refund_flag
        );
        $requests[] = $this->request('api_host_open_little', '/contestItem/create.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 数据库中获取活动的项目列表
     * @param $page
     * @param $size
     * @param $out_contest_id
     * @return bool|object
     */
    public function list_contest_item($page, $size, $out_contest_id = null){
        $param = array(
            'page' => $page,
            'size' => $size,
            'out_contest_id'=> $out_contest_id
        );
        $requests[] = $this->request('api_host_open_little', '/contestItem/list_contest_item.json', $param, 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }





}
