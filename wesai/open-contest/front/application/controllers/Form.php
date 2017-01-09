<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: zhaodc
 * Date: 11/10/2016
 * Time: 15:28
 */

require_once APPPATH . '/controllers/Base.php';

class Form extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array();
	}

	public function setPageVars()
	{
		return array(
			'CDN_URL' => 'http://' . _UPLOAD_RES_CDN_DOMAIN_,
		);
	}
	
	public function fillForm()
	{
		$this->verifyLogin();
		$data = array();
		$item_id = $this->input->get('item_id', true);
		$group_id = $this->input->get('group_id', true);
		$team_id = $this->input->get('team_id', true);

		$itemInfo = $this->get_item_info($item_id);
		if(empty($itemInfo['result'])){
			$this->displayError('项目不存在', -2);
		};
		$data['itemInfo'] = $itemInfo['result'];

		$intro = 1;
		$contestInfo=$this->get_contest_info($itemInfo['result']['fk_contest'],$intro);
		if(empty($contestInfo['result'])){
			$this->displayError('活动不存在', -1);
		}
		$data['contestInfo'] = $contestInfo['result'];

		$data['locationData'] = $this->list_location($contestInfo['result']['pk_contest']);

		global $TEMPLATE_LANG;
		$data['title'] = $TEMPLATE_LANG[$contestInfo['result']['template']]['form_title'];
		$data['TEMPLATE_LANG'] = $TEMPLATE_LANG;
		$data['group_id'] = $group_id;
		$data['team_id'] = $team_id;
		$this->display($data);
	}
	
	public function getByItemId()
	{
		$this->verifyLogin();
		$item_id = $this->input->get('item_id', true);

		$formInfo  = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'form/get_by_item_id.json', compact('item_id'), METHOD_GET);
		$formInfo = $this->obj2array($formInfo);
		if (empty($formInfo['result'])) {
			$this->displayError('表单不存在', -1);
		}
		$data['formInfo'] = $formInfo['result'];

		$itemInfo = $this->get_item_info($item_id);
		if(empty($itemInfo['result'])){
			$this->displayError('项目不存在', -2);
		};
		$data['itemInfo'] = $itemInfo['result'];
		$data['error'] = 0;
		$this->display($data);
	}

	public function listFormItems()
	{
		$userInfo = $this->verifyLogin();

		$uid     = $userInfo['uid'];
		$form_id = $this->input->get('form_id', true);
		$params  = compact('form_id');
		$result  = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'formitem/list_by_form.json', $params, METHOD_GET);
		$this->display($result);
	}
}
