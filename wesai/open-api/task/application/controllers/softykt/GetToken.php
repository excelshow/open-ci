<?php
/**
 * User: liangkx
 */
require_once __DIR__ . '/../Base.php';

class GetToken extends Base
{
	const TIME_SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}

	public function index()
	{
        $page = 1;
        $size = 100;
        while(1){
            $params = array();
            $params['page'] = $page;
            $params['size'] = $size;
            $result = $this->callInnerApiDiy(API_HOST_OPEN_API, '/softykt/softykt/list.json', $params, METHOD_GET);
            if (empty($result) || $result->error != 0) {
                log_message("error", '获取使用金飞鹰企业配置信息出错 result='.json_encode($result));
                sleep(10);
                continue;
            }

            if(empty($result->data)){
                break;
            }

            $corps = $result->data;
            foreach($corps as $corp){
                $this->updateCorpSoftyktToken($corp);
            }
            $page++;
        }
	}

    private function updateCorpSoftyktToken($info){
        $params = array('corp_id'=>$info->corp_id);
        $result = $this->callInnerApiDiy(API_HOST_OPEN_API, '/softykt/softykt/update_token.json', $params, METHOD_POST, true, 10, 10000);

        if (empty($result) || $result->error != 0) {
            log_message("error", '更新金飞鹰token出错 result='.json_encode($result));
            return false;
        }
    }
}
