<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class Base extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('smarty');
        $this->load->helper('common');
        $this->load->helper('weixin');
    }

    protected function errorInfo($info, $code = -1)
    {
        return array('error' => $code, 'info' => $info);
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
