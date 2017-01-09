<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base.php';
class Gen extends Base {

    public function token(){
        $this->load->library('AccessToken');
        $apps = $this->config->item('weixin_apps');
        foreach($apps as $app){
            $ret = AccessToken::getInstance()->get_access_token($app['appID'], $app['appsecret']);
            if(empty($ret)){
                $this->mail_error('Get Weixin AccessToken Error');
            }
        }

        // 因为有严格的先后顺序
        $this->ticket();
    }

    private function ticket(){
        $this->load->library('AccessToken');
        $this->load->library('JsapiTicket');
        $apps = $this->config->item('weixin_apps');
        foreach($apps as $app){
            $ret = JsapiTicket::getInstance()->get_jsapi_ticket($app['appID']);
            if(empty($ret)){
                $this->mail_error('Get Weixin JsapiTicket Error');
            }
        }
    }

    public function componentToken(){
        $this->load->library('ComponentAccessToken');
        $apps = $this->config->item('weixin_components');
        foreach($apps as $appID => $app){
            ComponentAccessToken::getInstance()->get_access_token($appID, $app['appsecret'], $app['token'], $app['aeskey'], $app['name']);
        }
    }



}
