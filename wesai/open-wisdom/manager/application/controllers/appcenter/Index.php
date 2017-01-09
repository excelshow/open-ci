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

	public function getHostName() {
		return false;
	}

	public function index()
	{
		$data = array();
        $this->display($data);
	}

}
