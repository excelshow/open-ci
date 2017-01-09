<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base.php';

abstract class Handler extends Base {

    protected $topic_pre = '';

    public function __construct($config = 'redismq'){
        parent::__construct();

        $this->load->library('Redis_List_Client', $this->config->item($config));
    }

    public function getMsg($topic) {
        try {
            return $this->redis_list_client->RightPop($topic);
        } catch (Exception $e) {
            log_message('error', 'redis|list|' . $e->getMessage());
        }
    }

    /**
     * 从event获取数据并处理
     */
    public function index(){
        $topic = 'wxmsg_' . $this->topic_pre . strtolower(get_class($this));
        while(1){
            $msg = $this->getMsg($topic);
            if(empty($msg)){
                sleep(1);
                continue;
            }
            $msg = json_decode($msg);
            // 微赛的消息都转发
            if($msg->ToUserName == 'gh_495075d1cefe'){
                $this->handlerWesaiB2c($msg);
            }else{
                $this->handler($msg);
            }
            // 查看
            $this->dealing();
            $this->dealEnd();
        }
    }

    
    /**
     * handler
     *  消息处理逻辑
     * @param mixed $msg 
     * @abstract
     * @access public
     * @return void
     */
    abstract function handler($msg);

    /**
     * handlerWesaiB2c
     *  微赛b2c逻辑处理
     * @param mixed $msg 
     * @access protected
     * @return void
     */
    protected function handlerWesaiB2c($msg){
        $this->load->model('Wesai_model');
        $result = $this->Wesai_model->push_event_msg($msg);
        
        // 记录
        log_message_to_file('wesaib2c', get_class($this).' '.json_encode($result).' '.json_encode($msg));
    }
}
