<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class User extends Base {
	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
	}

    public function setHostName(){
        return API_HOST_OPEN_USER;
    }

    public function setAllowedApiList(){
        return array(

        );
    }

    public function index(){
		$this->needLogin();
        $this->display();
    }
}
