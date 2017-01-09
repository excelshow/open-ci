<?php

class WeixinMenu
{
    private static $_instance = null;

    public static function getInstance() {
        if (self::$_instance instanceof static) {
            return self::$_instance;
        }
        return new static;
    }

    /**
     * do create menu action
     * @return boolen
     */
    public function create($appid) {
        $CI =& get_instance();
        $CI->load->library('token');
        $access_token = $CI->token->get_access_token($appid);
        $access_token = $access_token->access_token;

        $url = WEIXIN_CREATE_MENU_URL;
        $params = array(
            'access_token' => $access_token
        );
        $url .= "?" . http_build_query($params);

        $menu_data = $this->load_menu_data($appid);

        $ret = '';
        $CI->load->library('curl');
        $max = 3;
        $loop = 1;
        while(true){
            $ret = $CI->curl->post($menu_data, $url);
            if(!empty($ret) || $loop >= $max){
                break;
            }
            $loop ++;
        }

        return $ret;
   }

    
    /**
     * do create menu action
     * @return boolen
     */
    public function get() {
        $url          = WEIXIN_GET_MENU_URL;
        $access_token = Weixin_Class_AccessToken::getInstance()->load_access_token();
        $params       = array(
            'access_token' => !empty($access_token->access_token) ? $access_token->access_token : 'false',
        );

        $url .= "?" . http_build_query($params);

        $xcurl = new Lib_Curl($url);
        $ret   = $xcurl->get();

        if (!empty($ret)) {
            $result = json_decode($ret);
            var_dump($result);
        } else {
            echo 'Get Menu Failed!' . PHP_EOL;
        }
    }

    
    /**
     * do delete menu action
     * @return boolen
     */
    public function delete() {
        $url          = WEIXIN_DEL_MENU_URL;
        $access_token = Weixin_Class_AccessToken::getInstance()->load_access_token();
        $params       = array(
            'access_token' => !empty($access_token->access_token) ? $access_token->access_token : 'false',
        );

        $url .= "?" . http_build_query($params);

        $xcurl = new Lib_Curl($url);
        $ret   = $xcurl->get();

        if (!empty($ret)) {
            $result = json_decode($ret);
            var_dump($result);
        } else {
            echo 'Delete Menu Failed!' . PHP_EOL;
        }
    }

    /**
     * load menu data
     * @return json
     */
    private function load_menu_data ($appid) {
        $file = WEIXIN_MENU_DIR . '/menu_'.$appid.'.txt';
        if (file_exists($file) && is_readable($file)) {
            return file_get_contents($file);
        }
        return false;
    }
}
