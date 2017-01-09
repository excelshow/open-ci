<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Contestitem extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			'contestitem/ajax_list' => '', //项目列表
			'contestitem/ajax_delete' => '', //删除项目

		);
	}

	public function create()
	{	
		
	}

	public function edit()
	{

	}

	public function ajax_download_code()
	{
		
	}
}
