<?php

class JsapiTicket
{
    private static $_instance = null;

    public static function getInstance() {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }
        return new static;
    }
    
    public function call_weixin($appid) {
        $access_token = AccessToken::getInstance()->load_access_token($appid);

        $params = array(
            'access_token' => !empty($access_token->access_token) ? $access_token->access_token : 'false',
            'type'=>'jsapi'
        );

        $url = WEIXIN_GET_JSAPI_TICKET_URL;
        $url .= "?" . http_build_query($params);

        $ret = '';
        $CI =& get_instance();
        $CI->load->library('curl');
        $max = 3;
        $loop = 1;
        while(true){
            $ret = $CI->curl->get($url);
            if(!empty($ret) || $loop >= $max){
                break;
            }
            $loop ++;
        }

        return $ret;
    }

    public function get_jsapi_ticket($appid){
        $file = ACCESS_TOKEN_FILE_PATH . '/jsapi_ticket.'.$appid;
        $ticket = $this->call_weixin($appid);
        if (!empty($ticket)) {
            file_put_contents($file, $ticket);
        } else {
            echo 'Get JsapiTicket Error!' . PHP_EOL;
            return false;
        }

        return true;
    }

    public function load_jsapi_ticket($appid) {
        $file = ACCESS_TOKEN_FILE_PATH . '/jsapi_ticket.'.$appid;
        if (file_exists($file) && is_readable($file)) {
            $contents = file_get_contents($file);
            return json_decode($contents);
        }
        return false;
    }
}

