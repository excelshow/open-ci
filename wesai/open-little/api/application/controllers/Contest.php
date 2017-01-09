<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 第三方测试 活动类
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
        $this->load->model('Openapi_model');
        //$this->load->model('GetToken_model');
    }

    /**
     * 创建活动
     */
    public function create_post(){
        $param = $this->checkParam();
        //验证是否存在
        $contestInfo = $this->Contest_model->get_by_out_contest_id($param['out_contest_id']);
        if(!empty($contestInfo)){
            return $this->response_error(Error_Code::ERROR_CONTEST_ALREADY_EXISTS);
        }
        $insertId = $this->Contest_model->create($param);
        if(empty($insertId)){
            return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_FAILED);
        }

        return $this->response_insert($insertId);
    }
    private function checkParam(){
        $name           = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
        $out_contest_id = $this->post_check('contest_id', PARAM_NOT_NULL_NOT_EMPTY);
        $banner         = $this->post_check('banner', PARAM_NOT_NULL_EMPTY);
        $sdate_start    = $this->post_check('sdate_start', PARAM_NOT_NULL_NOT_EMPTY);
        $sdate_end      = $this->post_check('sdate_end', PARAM_NOT_NULL_NOT_EMPTY);
        $logo           = $this->post_check('logo', PARAM_NOT_NULL_EMPTY);
        $poster         = $this->post_check('poster', PARAM_NOT_NULL_EMPTY);
        $location       = $this->post_check('location', PARAM_NOT_NULL_EMPTY);
        $publish_state  = $this->post_check('publish_state', PARAM_NOT_NULL_NOT_EMPTY);
        $service_tel    = $this->post_check('service_tel', PARAM_NOT_NULL_EMPTY);
        $intro          = $this->post_check('intro', PARAM_NOT_NULL_EMPTY);
        $locations      = $this->post_check('locations', PARAM_NOT_NULL_EMPTY);

        $param = compact(
            'name', 'out_contest_id', 'banner', 'sdate_start', 'sdate_end', 'logo',
            'location', 'publish_state', 'service_tel', 'intro','poster','locations'
        );
        return $param;
    }

    /**
     * 修改编辑活动
     */
    public function modify_post(){
        $out_contest_id = $this->post_check('contest_id', PARAM_NOT_NULL_NOT_EMPTY);
        $detail = $this->Openapi_model->detail(array('contest_id' => $out_contest_id, 'intro'=>1, 'location'=>1));
        if(empty($detail) || $detail->error != 0){
            return $this->response_error(Error_Code::ERROR_CURL_CONTEST_DETAIL_FAILED);
        }
        $result = $detail->result;
        $param = array(
            'name'          => $result->name,
            'logo'          => $result->logo,
            'poster'        => $result->poster,
            'banner'        => $result->banner,
            'sdate_start'   => $result->sdate_start,
            'sdate_end'     => $result->sdate_end,
            'location'      => $result->location,
            'locations'     => serialize($result->location),
            'publish_state' => $result->publish_state,
            'service_tel'   => $result->service_tel,
            'intro'         => $result->intro,
            'out_contest_id'=> $out_contest_id
        );
        //验证是否存在
        $contestInfo = $this->Contest_model->get_by_out_contest_id($out_contest_id);
        if(empty($contestInfo)){
            $insertId = $this->Contest_model->create($param);
            if(empty($insertId)){
                return $this->response_error(Error_Code::ERROR_CREATE_CONTEST_FAILED);
            }
            return $this->response_insert($insertId);
        }
        $updateId = $this->Contest_model->modify($param, $out_contest_id);
        if(empty($updateId) && $updateId != 0){
            return $this->response_error(Error_Code::ERROR_MODIFY_CONTEST_FAILED);
        }

        return $this->response_update($updateId);
    }

    /**
     * 获取活动列表
     */
    public function list_contest_get(){
        $page   = $this->get_check('page', PARAM_NOT_NULL_NOT_EMPTY);
        $size   = $this->get_check('size', PARAM_NOT_NULL_NOT_EMPTY);
        $intro  = $this->get_check('intro', PARAM_NOT_NULL_EMPTY);  //是否获取详情  1获取  0不获取 默认不获取

        $list = $this->Contest_model->lists($page, $size, $intro);
        return $this->response_list($list);
    }



}
