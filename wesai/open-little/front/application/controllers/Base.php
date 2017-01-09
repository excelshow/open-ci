<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include_once 'Error_Code.php';

class Base extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('smarty');
        $this->load->helper('common');
        $this->load->helper('weixin');
        $this->load->helper('diy');
        $this->load->model('Token_model');
    }



    protected function getHeaders(&$param = array()){
        $this->getSignature($param);
        $token  = $this->getToken();
        $header  = array(
            'Token: ' . $token
        );
        return $header;
    }

    protected function getToken(){
        $result = $this->Token_model->getToken();
        if(empty($result) || $result->error != 0 || empty($result->result)){
            return false;
        }
        $tokenInfo = $result->result;
        return $tokenInfo->token;
    }

    /**
     * 签名生成算法
     * @param array
     * md5('a=av&b=bv&c=cv&'.$secret);
     * @return sign
     */
    protected function getSignature(&$param = array()){
        $secretInfo = $this->Token_model->get_secret();
        $app_secret = $secretInfo->result;
        ksort($param);
        $str = '';
        foreach($param as $k=>$v){
            $str .= $k . '=' . $v . '&';
        }
        $str .= $app_secret;

        $param['sign'] = md5($str);
        return $param;
    }

    protected function displayError($code, $info = null)
    {
        if(empty($info)){
            $info = Error_Code::desc($code);
        }
        $result = array('error' => $code, 'info' => $info);
        echo json_encode($result);
        exit;
    }

    protected function displayResult($info = null)
    {
        $code = '0';
        $result = array('error' => $code, 'result' => $info);
        echo json_encode($result);
        exit;
    }
    protected function displayDate($info = null)
    {
        echo json_encode($info);
        exit;
    }


    protected function display($data)
    {
        $template_dir = $this->smarty->template_dir[0];
        $tpl = $this->router->directory . $this->router->class . '/' . $this->router->method . '.tpl';

        if (!file_exists($template_dir . $tpl) || $this->input->get('debugo')) {
            echo json_encode($data);
            exit;
        }

        if (defined('SMARTY_DEBUG') && SMARTY_DEBUG) {
            $this->smarty->assign('CI', '为方便debug，把该变量清空');
            $this->smarty->debugging = true;
        }

        if(!empty($data)){
            foreach($data as $k=>$v){
                $this->smarty->assign($k, $v);
            }
        }

		$this->smarty->display($tpl);
    }

}
