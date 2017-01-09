<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/wxhandler/Handler.php';

class Subscribe extends Handler {

    protected $topic_pre = 'event_';
    private $authorizer = null;
    public function handler($msg){
        // 暂停发送关注通知 @date:2016-12-01
        //$this->authorizer = $this->getAuthorizerInfo($msg);
        //$this->sendActivity($msg);
    }

    private function sendActivity($msg){
        $params = array(
            'corp_id' => $this->authorizer->fk_corp,
            'state' => OPERATION_ACTIVITY_STATE_START,
            'page' => 1,
            'size' => 5,
        );
        $activity = $this->callInnerApiDiy(
            API_HOST_OPEN_OPERATION, 'activity/activity/list.json', $params, METHOD_GET
        );
		if (empty($activity) || !isset($activity->data)) {
            log_message('error', '获取营销活动失败');
		}
        if(!empty($activity->data)){
            $activities = $this->getNewsDataFrontActivities($activity->data);
            $this->sendInfoToUserNews($msg, $activities);
        }
    }

    private function getNewsDataFrontActivities($activities){
        // 图文信息
        $Articles = array();
        foreach($activities as $a){
            if(strtotime($a->time_end) < time()){
                continue;
            }
            $Article = array();
            $Article['Title'] = $a->name;
            $Article['Description'] = '';
            $Article['Url'] = 'http://'.$this->authorizer->authorizer_appid.OPERATION_DOMAIN_SUF.'/activity/detail?aid='.$a->activity_id;
            $Article['PicUrl'] = 'http://'._UPLOAD_RES_CDN_DOMAIN_.'/'.$a->banner;
            $Articles[] = $Article; 
        }
        return $Articles;
    }

    private function sendInfoToUserNews($msg, $articles = array()){
        if(empty($articles)){
            return;
        }
        $data = array();
        $data['FromUserName'] = $msg->ToUserName;
        $data['ToUserName'] = $msg->FromUserName;
        $data['Articles'] = $articles;

        $this->load->library('Wx_Msg');
        $this->wx_msg->news($data, $this->authorizer->authorizer_access_token);
    }

    /**
     * sendInfoToUserText
     *  测试发送text
     * @param mixed $msg 
     * @param bool $activity 
     * @access private
     * @return void
     */
    private function sendInfoToUserText($msg, $activity = null){
        $data = array();
        $data['FromUserName'] = $msg->ToUserName;
        $data['ToUserName'] = $msg->FromUserName;
        $data['Content'] = 'test';

        $this->load->library('Wx_Msg');
        $this->wx_msg->text($data, $this->authorizer->authorizer_access_token);
    }

}
