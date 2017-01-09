<?php
/**
 * 微信公众号，消息处理
 */
class CI_Weixin_Msg
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
		$this->CI->load->model('Component_model');
        $this->CI->load->library('curl');
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
        if(empty($access_token)){
            $result = $this->CI->Component_model->getAuthorizerInfoByUserName($this->FormUserName);
            if(empty($result) || empty($result->result)){
                log_message('error', 'Component_model getAuthorizerInfoByUserName error.'.$result);
                return false;
            }
            $result = $result->result;
            $access_token = $result->authorizer_access_token;
        }

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

