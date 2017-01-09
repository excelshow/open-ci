<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 通过微赛 openapi 即可获取活动信息 model
 */
class Openapi_contest_model extends DIY_Model {


    /**
     * 获取活动列表
     * @param $param 接口参数
     * @return bool|object
     */
    public function list_contest($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/list.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function complete_order($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/order/complete.json', $params, 'POST', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function create_order($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/order/create.json', $params, 'POST', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function list_form_items($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/item/list_form.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function list_items($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/item/list.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function get_by_id($params){
        $headers = $this->getOpenapiHeaders();
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/get_by_id.json', $params, 'GET', $headers);

        return $this->result($requests, true, 2, 2000, false);
    }

    private function getOpenapiHeaders(){
        $this->load->model('Token_model');
        $result = $this->Token_model->getToken();
        if(empty($result) || $result->error != 0 || empty($result->result)){
            return false;
        }
        $token = $result->result->token;
        $headers = array(
            'Token: ' . $token,
        );
        return $headers;
    }


}
