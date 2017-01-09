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
            $this->handler($msg);
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

    protected function getAuthorizerInfo($msg){
        $params = array(
            'user_name' => $msg->ToUserName
        );
		$componentAuthInfo = $this->callInnerApiDiy(
            API_HOST_OPEN_WEIXIN, 'component/authorizer/get_by_user_name.json', $params, METHOD_GET
        );
		if (empty($componentAuthInfo) || empty($componentAuthInfo->result)) {
            log_message('error', '授权公众号获取token信息失败');
		}
        $authorizer_info = $componentAuthInfo->result;
        return $authorizer_info;
    }

}
