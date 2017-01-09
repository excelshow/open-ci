<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: zhaodc
 * Date: 16/6/16
 * Time: 下午3:49
 */
require_once APPPATH . '/controllers/Applogin.php';

class Index extends Applogin
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getHostname() {
		return false;
	}

	public function getAllowedApiList()
	{
		return array();
	}

	public function index()
	{
		header('Location:wxapps/manage');
		exit();
	}
	public function loginquit()
	{
		unset($_SESSION['userInfo']);
        session_unset();
        session_destroy();
        header("Location:/");
        exit;
	}

}
