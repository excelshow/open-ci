<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Contestitem extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
				'contestitem/ajax_list' => 'contestitem/list.json',
				'contestitem/ajax_get'  => 'contestitem/get.json',
			),
		);
	}


	public function detail()
	{

	}
}
