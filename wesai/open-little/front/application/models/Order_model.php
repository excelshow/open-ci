<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取活动信息 model
 */
class Order_model extends DIY_Model {


    /**
     * 存储订单信息
     * @param $param 接口参数
     * @return bool|object
     */
    public function create($param){

        $requests[] = $this->request('api_host_open_little', 'order/create.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }
    /**
     * 存储核销信息
     * @param $param 接口参数
     * @return bool|object
     */
    public function complete($param){

        $requests[] = $this->request('api_host_open_little', 'order/complete.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }







}
