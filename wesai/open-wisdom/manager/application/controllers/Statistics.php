<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/FrontBase.php';

class Statistics extends FrontBase{

    public function setHostName(){
        return API_HOST_OPEN_COMMON;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_COMMON => array(
                'statistics/list_data' => 'statistics/list_data.json'
            )
        );
    }

    /**
     * list_data
     *  前端不需要传corp_id
     * @access public
     * @return void
     */
    public function list_data(){
        $params = $this->input->get();
        $params['seller_corp_id'] = $this->userInfo->pk_corp;
        $data = $this->callInnerApi($params);
        $this->display($data);
    }
}
