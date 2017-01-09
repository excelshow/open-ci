<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 获取活动信息 model
 */
class Contest_model extends DIY_Model {


    /**
     * 获取活动列表
     * @param $param 接口参数
     * @param $headers header参数
     * @return bool|object
     */
    public function get_contest_list($param, $headers){

        $requests[] = $this->request('api_host_open_wesai_zhty', 'contest/list.json', $param, 'GET', $headers);
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }


    /**
     * 获取活动详情
     * @param $param
     * @param $headers
     * @return bool|object
     */
    public function get_contest_detail($param, $headers){
        $requests[] = $this->request('api_host_open_wesai_zhty', 'contest/get_by_id.json', $param, 'GET', $headers);
        if(!empty($requests)){
            return $this->result($requests, $ms = true, $timeout = 2, $timeout_ms = 500, $is_api = false);
        }
    }


    /**
     * 存储活动信息
     * @param $paramObj object
     * @return bool|object
     */
    public function create_contest($paramObj){
        $param = array(
            'name'          => $paramObj->name,
            'contest_id'    => $paramObj->contest_id,
            'banner'        => $paramObj->banner,
            'poster'        => $paramObj->poster,
            'sdate_start'   => $paramObj->sdate_start,
            'sdate_end'     => $paramObj->sdate_end,
            'logo'          => $paramObj->logo,
            'location'      => $paramObj->location,
            'publish_state' => $paramObj->publish_state,
            'service_tel'   => $paramObj->service_tel,
            'intro'         => $paramObj->intro,
            'locations'     => serialize($paramObj->locations)
        );
        $requests[] = $this->request('api_host_open_little', '/contest/create.json', $param, 'POST');
        if(!empty($requests)){
            return $this->result($requests);
        }
    }

    /**
     * 数据库中获取活动列表
     * @param $param
     * @return bool|object
     */
    public function list_contest($page, $size, $intro = 0){
        $param = array(
            'page' => $page,
            'size' => $size,
            'intro'=> $intro
        );
        $requests[] = $this->request('api_host_open_little', '/contest/list_contest.json', $param, 'GET');
        if(!empty($requests)){
            return $this->result($requests);
        }

    }





}
