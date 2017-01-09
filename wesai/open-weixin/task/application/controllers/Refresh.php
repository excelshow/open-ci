<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 获取需要刷新的数据丢入队列
 */
require_once APPPATH . 'controllers/Base.php';
class Refresh extends Base {

    public function __construct(){
        parent::__construct();

        $mq_config = $this->config->item('redismq');
        $this->load->library('Redis_List_Client', $mq_config);
        $this->load->model('Component_model');
    }

    /**
     * 获取需要处理的公众号
     */
    public function componentAuthorizerWillRefresh(){
        $apppk = 0;
        $refresh_time = '';
        $count = 100;
        while(1){
            $apps = $this->Component_model->list_will_refresh($count, $apppk, $refresh_time);
            if(empty($apps) || empty($apps->data)){
                // sleep 1 分钟
                sleep(60);
                $apppk = 0;
                $refresh_time = '';
                continue;
            }

            foreach($apps->data as $app){
                $apppk = $app->pk_component_authorizer_app;
                $refresh_time = $app->refresh_time;
                $result = $this->redis_list_client->LeftPush(MQ_TOPIC_AUTHORIZER_WILL_REFRESH, json_encode($app));
                if(empty($result)){
                    log_message('error', 'list_will_refresh LeftPush error');
                }
                $this->dealing();
            }
            $this->dealEnd();
        }
    }

    /**
     * 从队列里查询待刷新的授权app信息
     */
    public function componentAuthorizerRefreshToken(){
        $this->load->library('ComponentAccessToken');
        while(1){
            $app = $this->redis_list_client->RightPop(MQ_TOPIC_AUTHORIZER_WILL_REFRESH);
            if(empty($app)){
                sleep(1);
                continue;
            }
            $app = json_decode($app);
            // 判断是否刷新过
            $refreshed = $this->isAuthorizerRefreshed($app);
            if($refreshed){
                continue;
            }
            $ret = ComponentAccessToken::getInstance()->refresh_authorizer_access_token(COMPONENT_APPID, $app->authorizer_appid, $app->authorizer_refresh_token);
            if(empty($ret)){
                log_message('error', 'refresh_authorizer_access_token Error ' . json_encode($app));
            }
            // 查看
            $this->dealing();
            $this->dealEnd();
        }
    }

    private function isAuthorizerRefreshed($app){
        // 查看是否已经更新过了
        $result = $this->Component_model->getAuthorizerInfo($app->authorizer_appid);
        if(empty($result) || empty($result->result)){
            return true;
        }
        $authorizer_app = $result->result;
        $refresh_time = strtotime($authorizer_app->refresh_time);
        // 如果1个半小时前超过了 刷新时间
        if((time() - 90*60) >= $refresh_time){
            return false;
        }
        return true;
    }

}
