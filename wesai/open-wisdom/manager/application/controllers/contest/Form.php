<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Form extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
				'form/ajax_create'  => '', //创建表单项
				'form/ajax_update'  => '', //更新表单项
				'form/ajax_delete'  => 'formitem/delete.json', //删除表单项
				'form/ajax_list'    => 'formitem/list_by_form.json', //表单项列表
				'form/ajax_set_seq' => 'formitem/set_seqs.json', //调整表单项顺序
			),
		);
	}


	public function detail_form()
	{
		$this->verifyLogin();//用户验证
		$form_id = $this->input->get('form_id', true);
		$data    = array();
		$paramslastid = compact('form_id');
		$data         = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'formitem/list_by_form.json', $paramslastid, METHOD_GET, true, 2, 1000);
		$this->display($data);
	}

	public function index()
	{
		$this->verifyLogin();//用户验证

		$item_id = $this->input->get('item_id', true);
		if (empty($item_id)) {
			$this->displayError('参数错误', -1);
		}

		$itemInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', compact('item_id'), METHOD_GET);
		$itemInfo = $this->obj2array($itemInfo);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目异常', -2);
		}

		$form_id  = null;
		$formInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'form/get_by_item_id.json', compact('item_id'), METHOD_GET);
		$formInfo = $this->obj2array($formInfo);
		if (empty($formInfo['result'])) {
			$params = array(
				'item_id' => $item_id,
				'name'    => $itemInfo['result']['name'],
			);
			$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'form/add.json', $params, METHOD_POST, true, 2, 1000);
			$result = $this->obj2array($result);
			if (empty($result['lastid'])) {
				$this->displayError('创建报名表单失败', -3);
			}
			$form_id = $result['lastid'];
		} else {
			$form_id = $formInfo['result']['pk_enrol_form'];
		}

		$data            = array();
		$data['form_id'] = $form_id;
		$this->display($data);
	}

	public function edit_form()
	{

	}

	public function ajax_add_form_item()
	{
		$this->needLoginJson();

		$form_id       = $this->input->post('form_id');
		$type          = $this->input->post('type', true);
		$title         = $this->input->post('title', true);
		$is_required   = $this->input->post('is_required', true);
		$option_values = $this->input->post('option_values', true);
		$option_values = json_encode($option_values);
		$seq           = $this->input->post('seq', true);

		$params = compact('form_id', 'type', 'title', 'is_required', 'option_values', 'seq');

		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'formitem/add.json', $params, METHOD_POST, true, 2, 1000);

		$paramslastid = compact('form_id');
		$data         = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'formitem/list_by_form.json', $paramslastid, METHOD_GET, true, 2, 1000);
		$this->display($data);
	}

	// public function ajax_update_form_item()
	// {
	// 	$this->needLoginJson();

	// 	$pk_enrol_form_item = $this->input->post('form_itemid', true);
	// 	if (isset($_POST['title'])) {
	// 		$title      = $this->input->post('title', true);
	// 		$postparams = compact('pk_enrol_form_item', 'title');
	// 	}
	// 	if (isset($_POST['is_required'])) {
	// 		$is_required = $this->input->post('is_required', true);
	// 		$postparams  = compact('pk_enrol_form_item', 'is_required');
	// 	}
	// 	if (isset($_POST['option_values'])) {
	// 		$option_values = $this->input->post('option_values', true);
	// 		$option_values = json_encode($option_values);
	// 		$postparams    = compact('pk_enrol_form_item', 'option_values');
	// 	}

	// 	$verify = $this->verifyFormItemEdit($pk_enrol_form_item);
	// 	if ($verify->error < 0) {
	// 		return $this->errorInfo('没有权限', $verify->error);
	// 	}

	// 	//添加form问题
	// 	$result = $this->Formorder_model->updateFormItem($postparams);
	// 	$this->display($result);
	// }

	// public function ajax_delete_form_item()
	// {
	// 	$this->needLoginJson();

	// 	$pk_enrol_form_item = $this->input->post('qid', true);

	// 	$verify = $this->verifyFormItemEdit($pk_enrol_form_item);
	// 	if ($verify->error < 0) {
	// 		return $this->errorInfo('没有权限', $verify->error);
	// 	}

	// 	//添加form问题
	// 	$postparams = compact('pk_enrol_form_item');
	// 	$result     = $this->Formorder_model->deleteFormItem($postparams);
	// 	$this->display($result);
	// }


}
