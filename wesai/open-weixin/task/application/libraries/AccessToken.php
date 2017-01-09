<?php

class AccessToken
{
    private static $_instance = null;

    public static function getInstance() {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }
        return new static;
    }

    public function call_weixin($appid, $secret){
        $url = WEIXIN_GET_ACCESS_TOKEN_URL;
        $params = array(
            'grant_type' => 'client_credential', 
            'appid'      => $appid,
            'secret'     => $secret,
        );

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

    public function get_access_token($appid, $secret) {
        $file = ACCESS_TOKEN_FILE_PATH . '/access_token.'.$appid;
        $token = $this->call_weixin($appid, $secret);
        if (!empty($token)) {
            file_put_contents($file, $token);
        } else {
            echo 'Get AccessToken Error!' . PHP_EOL;
            return false;
        }

        return true;
    }

    public function load_access_token($appid) {
        $file = ACCESS_TOKEN_FILE_PATH . '/access_token.'.$appid;
        if (file_exists($file) && is_readable($file)) {
            $contents = file_get_contents($file);
            return json_decode($contents);
        }
        return false;
    }
}

