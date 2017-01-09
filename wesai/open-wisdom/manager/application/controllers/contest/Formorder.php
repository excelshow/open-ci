<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Formorder extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
				API_HOST_OPEN_CONTEST => array(
				
				)
			);
	}

	// public function ajax_add_form()
	// {
	// 	$this->needLoginJson();
	//
	// 	$itemid = $this->input->post('itemid', true);
	// 	$name   = $this->input->post('name', true);
	//
	// 	$verify = $this->verifyContestItemEdit($itemid, true);
	// 	if ($verify->error < 0) {
	// 		return $this->errorInfo('没有权限', $verify->error);
	// 	}
	//
	// 	$formstr = json_encode($this->input->post('formstr', true));
	// 	//新增表单
	// 	$postparams = compact('itemid', 'name', 'formstr');
	// 	$result     = $this->Formorder_model->addForm($postparams);
	// 	$this->display($result);
	// }


	//

	//
	public function ajax_update_form_item()
	{
		$form_item_id  = $this->input->post('form_item_id', true);
		$type          = $this->input->post('type', true);

		$title         = $this->input->post('title', true);
		$option_values = json_encode($this->input->post('option_values', true));
		$is_required   = $this->input->post('is_required', true);

		$params = compact('form_item_id','option_values');
		if(!empty($title)){
			$params['title'] = $title;
		}
		if(!empty($is_required)){
			$params['is_required'] = $is_required;
		}
		if(!empty($type)){
			$params['type'] = $type;
		}
		$result   = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'formitem/update.json', $params, METHOD_POST);
		$this->display($result);
	}
}
