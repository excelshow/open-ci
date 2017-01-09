<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取活动信息 model
 */
class Contest_model extends DIY_Model {

    /**
     * 编辑活动信息
     */
    public function modify_contest($contest_id){
        $param = array(
            'contest_id' => $contest_id
        );
        $requests[] = $this->request('api_host_open_little', 'contest/modify', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 编辑项目信息
     * @param $contest_id
     * @param $item_id
     */
    public function modify_item($contest_id, $item_id){
        $param = array(
            'contest_id'        => $contest_id,
            'contest_items_id'  => $item_id
        );
        $requests[] = $this->request('api_host_open_little', 'contestItem/modify', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 重新存储表单
     * @param $contest_id
     * @param $item_id
     */
    public function modify_form($contest_id, $item_id){
        $param = array(
            'contest_id'        => $contest_id,
            'contest_items_id'  => $item_id
        );
        $requests[] = $this->request('api_host_open_little', 'contestItemForm/modify', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 核销信息记录
     * @param $param
     */
    public function order_complete($param){
        $requests[] = $this->request('api_host_open_little', 'order/complete_order', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }







}
