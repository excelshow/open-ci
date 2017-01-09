<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Softykt_Base.php';

/**
 * 金飞鹰对接api 获取token类
 *
 * @package default
 * @author  : liangkaixuan
 **/
class Softykt extends Softykt_Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/Softykt_model');
    }

    public function list_get()
    {
        $page = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $size = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $this->checkPageParams($page, $size);

        $result = $this->Softykt_model->listCorp($page, $size);

        return $this->response_list($result, count($result), $page, $size);
    }

    public function get_token_get(){
        $corp_id = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $this->getCorpInfo($corp_id);

        $token = $this->Softykt_model->getCorpToken($corp_id);
        if(empty($token)){
            return $this->response_error(Error_Code::ERROR_GET_SOFTYKT_TOKEN_FAILED);
        }

        return $this->response_object($token);
    }

    /**
     * 更新token
     */
    public function update_token_post(){
        $corp_id = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $info = $this->getCorpInfo($corp_id);

        $token = $this->getSoftyToken($info['appid'], $info['softyktsecrect']);
        if(empty($token)){
            return $this->response_error(Error_Code::ERROR_API_SOFTYKT_GET_TOKEN_FAILED);
        }

        $result = $this->Softykt_model->updateCorpToken($corp_id, $token);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_UPDATE_SOFTYKT_TOKEN_FAILED);
        }

        return $this->response_update($result);
    }

}
