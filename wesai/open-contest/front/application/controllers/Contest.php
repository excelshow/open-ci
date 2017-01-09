<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . '/controllers/Base.php';

class Contest extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
				'contest/ajax_get' => 'contest/get.json'
			)
		);
	}

	public function setPageVars()
	{
		return array(
			'CDN_URL' => 'http://' . _UPLOAD_RES_CDN_DOMAIN_,
		);
	}

	public function ajax_list()
	{
		$userInfo = $this->verifyLogin();

		$page = $this->input->get('page', true);
		$size = $this->input->get('size', true);

		$corp_id = $userInfo['corp_id'];

		$params = compact('page', 'size', 'corp_id');
		if (!empty($_SESSION['template'])) {
			$params['template'] = $_SESSION['template'];
		}
		$contestList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/search.json', $params, METHOD_GET);

		$cids = array();
		if(!empty($contestList->data)){
		 	foreach ($contestList->data as $key => $value) {
				$contestList->data[$key]->locationData = $this->list_location($value->pk_contest);
			    $contestList->data[$key]->enrol_data_count = 0;
			    if ($value->show_enrol_data_count == 1) {
			    	$cids[] = $value->pk_contest;
			    }
			};
		};

		if (!empty($cids)) {
			$cids = implode(',', $cids);
			$enrolDataCountList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get_enrol_data_count_by_ids.json', compact('cids'), METHOD_GET);
			$enrolDataCountList = $this->obj2array($enrolDataCountList);
			if (!empty($enrolDataCountList['data'])) {
				$enrolDataCountList['data'] = array_column($enrolDataCountList['data'], null, 'fk_contest');
				foreach ($contestList->data as $key => $value) {
					if (array_key_exists($value->pk_contest, $enrolDataCountList['data'])) {
						$contestList->data[$key]->enrol_data_count = $enrolDataCountList['data'][$value->pk_contest]['enrol_data_count'];
					}
				}
			}
		}

		$contestList->page_vars = $this->setPageVars();

		$this->display($contestList);
	}

	public function index()
	{
		$userInfo = $this->verifyLogin();
		$template = $this->input->get('template', true);
		$_SESSION['template'] = $template;
		//分享朋友圈链接
		$page = 1;
		$size = 1;
		$corp_id = $userInfo['corp_id'];
		$params = compact('page', 'size', 'corp_id');
		$appId = $this->appId;
		if (!empty($_SESSION['template'])) {
			$params['template'] = $_SESSION['template'];
		}
		$contestData = null;
		$contestList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/search.json', $params, METHOD_GET);
		if(!empty($contestList->data)){
			$contestData=$contestList->data[0];
 			$contestData->page_vars = $this->setPageVars();
		}

		$title = "首页"; 
 		$data  = compact("title", 'contestData','corp_id','appId');
		$this->display($data);
	}

	public function detail()
	{
		$this->verifyLogin();
		$userInfo = $this->verifyLogin();
		$data=array();
		$cid = $this->input->get('cid', true);
		$intro = 1;

		$corp_id = $userInfo['corp_id'];
		$params = compact('cid','intro'); 
		$data = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		if($data->error==0){
			if($data->result->template == TEMPLATE_TICKET){
				$params['type'] = CONTEST_ITEM_TYPE_SINGLE;
			}
			$contestitemList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/list.json', $params, METHOD_GET);
			$data->contestitemList=$contestitemList->data;
			$data->result->enrol_data_count = 0;
			if (!empty($contestitemList->data) && $data->result->show_enrol_data_count == 1) {
				foreach ($contestitemList->data as $item) {
					$data->result->enrol_data_count += $item->enrol_data_count;
				}
			}
		}
		$data->locationData = $this->list_location($cid);
		$data->page_vars = $this->setPageVars();
    	global $TEMPLATE_LANG;
    	global $CONTEST_STATE_LIST;
    	$title = $TEMPLATE_LANG[$data->result->template]['detail_title'];
		$data->title = $title;
		$data->TEMPLATE_LANG      = $TEMPLATE_LANG;
		$data->CONTEST_STATE_LIST = $CONTEST_STATE_LIST;

		$shareInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'sharingsettings/get_by_cid.json',compact('cid'), METHOD_GET);
		if($shareInfo->error == 0){
			$shareInfo = $shareInfo->result;
		}else{
			$shareInfo = array();
		}
		$data->shareInfo = $shareInfo;
		$this->display($data);
	}
}
