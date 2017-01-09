<?php
/**
 * 微信公众号，消息处理
 */
class CI_Wx_Msg
{
    const MsgType_Text  = 'text';
    const MsgType_Image = 'image';
    const MsgType_Voice = 'voice';
    const MsgType_Video = 'video';
    const MsgType_News  = 'news';
    const MsgType_Template  = 'template';

    private $FromUserName = '';
    protected $CI = null;
    public function __construct(){
        $this->CI = & get_instance();
        $this->CI->load->library('curl');
    }

    public function news($data, $access_token = ''){
        $msg['touser']  = $data['ToUserName'];
        $msg['msgtype']  = self::MsgType_News;
        $msg['news']['articles'] = array();
        foreach($data['Articles'] as $a){
            $key = md5(json_encode($a));
            $msg['news']['articles'][] = array(
                'title'=>'_TITLE_'.$key, 
                'description'=>'_DESCRIPTION_'.$key,
                'url'=>'_URL_'.$key,
                'picurl'=>'_PICURL_'.$key
            );
        }

        $msg = json_encode($msg);
        foreach($data['Articles'] as $a){
            $key = md5(json_encode($a));
            $msg = str_replace('_TITLE_'.$key, $a['Title'], $msg);
            $msg = str_replace('_DESCRIPTION_'.$key, $a['Description'], $msg);
            $msg = str_replace('_URL_'.$key, $a['Url'], $msg);
            $msg = str_replace('_PICURL_'.$key, $a['PicUrl'], $msg);
        }

        $this->send($msg, $access_token);
    }

    public function text($data, $access_token = ''){
        $this->checkData($data);

        $msg = array();
        $msg['touser']  = $data['ToUserName'];
        $msg['msgtype']  = self::MsgType_Text;
        $msg['text'] = array('content' => '%s');
        $msg_format = sprintf(json_encode($msg), addslashes($data['Content']));

        $this->send($msg_format, $access_token);
    }

    private function checkData($data){
        if(empty($data['FromUserName'])){
            log_message('error', 'weixin msg checkdata error');
        }
        $this->FormUserName = $data['FromUserName'];
    }

    private function send($msg, $access_token = '', $msg_send_url = WEIXIN_CUSTOM_MSG_SEND_URL) {
        $params['access_token'] = $access_token;
        $url = $msg_send_url . "?" . http_build_query($params);

        $result = $this->CI->curl->post($msg, $url);
        if(!empty($result)){
            $ret = json_decode($result);
            if(empty($ret) || !is_object($ret)){
                log_message('error', 'weixin msg custom post error.'.$result);
            }
            if ($ret->errcode !== 0) {
                log_message('error', 'weixin msg custom post error , errcode : '. $ret->errcode . ', errmsg : '. $ret->errmsg);
            }
        }
    }
}

