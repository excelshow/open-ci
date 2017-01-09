<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APP_PATH . '/controllers/Base.php';

class User extends Base {
	public function __construct() {
		parent::__construct();
		$this->load->model('qywx/Auth_model');
		$this->load->model('Corp_user_model');
	}

	//reg
	public function reg() {
		$data = array();
		$this->display($data);
	}

	public function ajax_add_openapi() {
		$userid        = $this->input->post('userid', true);
		$corpname      = $this->input->post('corpname', false);

        $pk_corp = $this->getPkCorp($corpname);
        // usertype=100 为openapi的corp超级管理员
        // usertype=101 为openapi的corp普通用户（也既接口用户）
        // 查询userid，如果已存在不新增
        $result = $this->Corp_user_model->get($pk_corp, $userid);
        if (empty($result) || $result->error != 0) {
            $this->errorInfo('用户信息异常');
        }
        $user = $result->result;
        if(empty($user)){
            $username = $userid;
            $result = $this->Corp_user_model->createOpenapi($pk_corp, $userid, 101, $username);
            if (empty($result) || $result->error != 0) {
                $this->errorInfo('创建用户失败');
            }
        }

		$this->display($result);
	}

    private function getPkCorp($corpname){
        $corpid        = $corpname;
        // 查询corpid，如果已存在不新增
        $corp = $this->Auth_model->getCorpById($corpname);
        if (empty($corp) || $corp->error != 0) {
            $this->errorInfo('企业信息异常');
        }
        $corp = $corp->result;
        if(empty($corp)){
            $result = $this->Auth_model->createOpenapiCorp($corpid, $corpname);
            if (empty($result) || $result->error != 0) {
                $this->errorInfo('创建企业信息异常');
            }
            $pk_corp = $result->lastid;
        }else{
            $pk_corp = $corp->pk_corp;
        }

        return $pk_corp;
    }
}
