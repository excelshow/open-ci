<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 通过微赛 openapi 即可获取活动信息 model
 */
class Openapi_model extends DIY_Model {

    public function create_order($params){
        $headers = $this->getOpenapiHeaders($params);
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/order/create.json', $params, 'POST', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function list_form_items($params){
        $headers = $this->getOpenapiHeaders($params);
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/item/list_form.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function item_detail($params){
        $headers = $this->getOpenapiHeaders($params);
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/item/get_by_id.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    public function detail($params){
        $headers = $this->getOpenapiHeaders($params);
        $requests[] = $this->request(API_HOST_OPEN_WESAI_ZHTY, 'contest/get_by_id.json', $params, 'GET', $headers);
        return $this->result($requests, true, 2, 2000, false);
    }

    private function getOpenapiHeaders(&$params = array()){
        $this->getSignature($params);
        $this->load->model('Token_model');
        $result = $this->Token_model->getToken();
        if(empty($result)){
            return false;
        }
        $token = $result['token'];
        $headers = array(
            'Token: ' . $token,
        );
        return $headers;
    }

    /**
     * 签名生成算法
     * @param array
     * md5('a=av&b=bv&c=cv&'.$secret);
     * @return sign
     */
    protected function getSignature(&$params = array()){
        $app_secret = APP_SECRET;
        ksort($params);
        $str = '';
        foreach($params as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str .= $app_secret;

        $params['sign'] = md5($str);
        return $params;
    }


}
