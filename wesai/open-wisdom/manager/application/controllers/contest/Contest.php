<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Contest extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('contest/Contest_model');
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
				'contest/ajax_online'        => 'contest/online.json', //上架
				'contest/ajax_start_selling' => 'contest/start_selling.json', //开始报名
				'contest/ajax_offline'       => 'contest/offline.json', //下架
				'contest/ajax_re_online'     => 'contest/re_online.json', //重新上架
				'contest/ajax_delete_items'  => 'contestitem/delete.json', //重新上架
			),

		);
	}

	//public function mobiledetail()
	//{
	//	$cid       = $this->input->get('cid', true);
	//	$intro     = "1";
	//	$data      = array();
	//	$web_title = "查看活动";
	//	// 获取活动
	//	$result = $this->Contest_model->getContestById($cid, $intro);
	//	if (!empty($result) && $result->error == 0) {
	//		$data = $result->result;
	//	}
	//	$page = 1;
	//	$size = 20;
	//	//竞赛项目+表单
	//	$itemdata   = array();
	//	$form       = "yes";
	//	$items_list = $this->Contest_model->get_items_list($cid, $page, $size, $form);
	//	if (!empty($items_list) && $items_list->error == 0) {
	//		$itemdata = $items_list->data;
	//	}
	//	global $NEWCONTEST_LEVEL_LIST;
	//	global $CONTEST_UNITS_LIST;
	//	global $CONTEST_STATE_LIST;
	//	$data = compact('web_title', 'data', 'itemdata', 'NEWCONTEST_LEVEL_LIST', 'CONTEST_STATE_LIST');
	//	$this->display($data);
	//}

	private function checkContestItemEditEnable($publishState)
	{
		if (in_array($publishState, [CONTEST_PUBLISH_STATE_SELLING])) {
			return false;
		}

		return true;
	}


	public function index()
	{
		$this->verifyLogin();//用户验证

		$name       = $this->input->get('name', true);//搜索
		$gtype      = $this->input->get('gtype', true);//类别
		$state      = $this->input->get('state', true);//状态
		$page       = $this->input->get('per_page', true);
		$dateType   = $this->input->get('date_type', true);
		$datePeriod = $this->input->get('date_period', true);
		$dateStart  = $this->input->get('date_start', true);
		$dateEnd    = $this->input->get('date_end', true);

		$min_date = 0;
		$max_date = 0;
		switch ($dateType) {
			case 1: // 选择固定期限
				switch ($datePeriod) {
					case 1: //当日
						$min_date = date('Y-m-d 00:00:00');
						$max_date = date('Y-m-d 23:59:59');
						break;
					case 2: //近一周
						$min_date = date('Y-m-d 00:00:00', strtotime('-1 weeks'));
						$max_date = date('Y-m-d 23:59:59');
						break;
					case 3:
						$min_date = date('Y-m-d 00:00:00', strtotime('-1 month'));
						$max_date = date('Y-m-d 23:59:59');
						break;
				}
				break;
			case 2:
				// 自定义日期区间
				$min_date = date('Y-m-d 00:00:00', strtotime($dateStart));
				$max_date = date('Y-m-d 23:59:59', strtotime($dateEnd));
				break;
		}
		$page > 0 or $page = 1;
		$size = 10;

		$corp_id = $this->userInfo->pk_corp;

		$params = compact('corp_id', 'name', 'gtype', 'state', 'page', 'size');
		if (!empty($min_date)) {
			$params['min_date'] = $min_date;
		}
		if (!empty($max_date)) {
			$params['max_date'] = $max_date;
		}
		// 获取活动列表
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/search_manage.json', $params, METHOD_GET);
		$data   = array();
		$total  = 0;
		if (!empty($result) && $result->error == 0) {
			$total = $result->total;
			$data  = $result->data;
		 	foreach ($data as $key => $value) {
				$data[$key]->locationData = $this->list_location($value->pk_contest);				
			};
		}
		$this->load->helper('pagination');
		$this->load->library('pagination');
		$pconfig = load_pagination_config($total, $size);
		$this->pagination->initialize($pconfig);
		$page_ctrl = $this->pagination->create_links();
		global $CONTEST_STATE_LIST;
		global $CONTEST_GTYPE_LIST;
		global $SOFTYKT_CORPS;
        $is_softykt_corp = in_array($corp_id, $SOFTYKT_CORPS)?1:0;
		$data = compact(
			'name', 'gtype', 'state', 'page', 'size', 'data', 'page_ctrl', 'CONTEST_STATE_LIST', 'CONTEST_GTYPE_LIST',
			'dateType', 'datePeriod', 'dateStart', 'dateEnd', 'is_softykt_corp'
		);
		$this->display($data);
	}

	public function edit()
	{
		$this->verifyLogin();//用户验证
		$cid   = $this->input->get('cid', true);
		$intro = 1;
		// 获取活动列表
		$params = compact('cid', 'intro');

		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		if (!empty($result) && $result->error == 0) {
			$data = $result->result;
		}
		global $CONTEST_GTYPE_LIST;
		$location = $this->list_location($cid);
		$data     = compact('data', 'CONTEST_GTYPE_LIST', 'location');
		$this->display($data);

	}


	protected function list_location($cid)
	{
		$params   = compact('cid');
		$newlocation = null;
		$location = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET);
		if($location->error == 0 and $location->result->country_scope == 1 ){
			$location = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'tag/list_location.json', $params, METHOD_GET);
			$location = $this->obj2array($location);
			if (empty($location['data'])) {
				return array();
			}
			$location = $location['data'];
			$tagIds   = array_column($location, 'fk_tag');
			$ids      = implode(',', $tagIds);
			$params   = compact('ids');
			$tag_list = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'tag/get_by_ids.json', $params, METHOD_GET);
			$tag_list = $this->obj2array($tag_list);
			if (empty($tag_list['data'])) {
				return array();
			}
			$tag_array   = array_reduce($tag_list['data'], create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
			$newlocation = array();
			foreach ($location as $key => $value) {
				$newlocation[$value['level']] = $tag_array[$value['fk_tag']];
			}
		}
		return $newlocation;
	}

	public function ajax_add_contest()
	{
		$this->needLoginJson();

		$name          = $this->input->post('name', true);
		$ename         = $this->input->post('ename', true);
		$intro         = $this->input->post('intro', false);
		$logo          = $this->input->post('logo', true);
		$poster        = $this->input->post('poster', true);
		$banner        = $this->input->post('banner', true);
		$sdate_start   = $this->input->post('sdate_start', true);
		$sdate_end     = $this->input->post('sdate_end', true);
		$country_scope = $this->input->post('country_scope', true);
		$location      = $this->input->post('location', true);
		$gtype         = $this->input->post('gtype', true);
		$source        = $this->input->post('source', true);
		$level         = $this->input->post('level', true);
		$lottery       = $this->input->post('lottery', true);
		$deliver_gear  = $this->input->post('deliver_gear', true);
		$service_tel   = $this->input->post('service_tel', true);
		$service_mail  = $this->input->post('service_mail', true);
		$template      = $this->input->post('template', true);
		$show_enrol_data_count      = $this->input->post('show_enrol_data_count', true);

		$corp_id  = $this->userInfo->pk_corp;
		$corp_uid = $this->userInfo->pk_corp_user;

		//新增活动
		$params = compact('name', 'ename', 'intro', 'logo', 'poster', 'banner', 'sdate_start', 'sdate_end', 'country_scope', 'location', 'gtype', 'source', 'level', 'lottery', 'deliver_gear', 'showtime', 'corp_id', 'corp_uid', 'service_tel', 'service_mail', 'template', 'show_enrol_data_count');
		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/add.json', $params, METHOD_POST);
		$this->display($result);
	}

	public function ajax_update_contest()
	{
		$this->needLoginJson();

		$cid           = $this->input->post('cid', true);
		$name          = $this->input->post('name', true);
		$ename         = $this->input->post('ename', true);
		$intro         = $this->input->post('intro', false);
		$logo          = $this->input->post('logo', true);
		$poster        = $this->input->post('poster', true);
		$banner        = $this->input->post('banner', true);
		$sdate_start   = $this->input->post('sdate_start', true);
		$sdate_end     = $this->input->post('sdate_end', true);
		
		$country_scope = $this->input->post('country_scope', true);
		$location      = $this->input->post('location', true);
		$gtype         = $this->input->post('gtype', true);
		$source        = $this->input->post('source', true);
		$level         = $this->input->post('level', true);
		$lottery       = $this->input->post('lottery', true);
		$deliver_gear  = $this->input->post('deliver_gear', true);
		$service_tel   = $this->input->post('service_tel', true);
		$service_mail  = $this->input->post('service_mail', true);
		$template      = $this->input->post('template', true);
		$show_enrol_data_count      = $this->input->post('show_enrol_data_count', true);

		$corp_id  = $this->userInfo->pk_corp;
		$corp_uid = $this->userInfo->pk_corp_user;

		//新增活动
		$params = compact('cid', 'name', 'ename', 'intro', 'logo', 'poster', 'banner', 'sdate_start', 'sdate_end', 'country_scope', 'location', 'gtype', 'source', 'level', 'lottery', 'deliver_gear', 'showtime', 'corp_id', 'corp_uid', 'service_tel', 'service_mail', 'template', 'show_enrol_data_count');


		$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/update.json', $params, METHOD_POST);
		$this->display($result);
	}


	public function ajax_add_location()
	{
		$this->needLoginJson();
		$cid      = $this->input->post('cid', true);
		$country  = $this->input->post('country', true);
		$province = $this->input->post('province', true);
		$city     = $this->input->post('city', true);
		$district = $this->input->post('district');

		$verify = $this->verifyContestEdit($cid);
		if ($verify->error < 0) {
			return $this->errorInfo('没有权限', $verify->error);
		}

		$tagsarray = array(
			array("name" => $country, "level" => 1),
			array("name" => $province, "level" => 2),
			array("name" => $city, "level" => 3),
		);
		if (!empty($district)) {
			$tagsarray[] = array("name" => $district, "level" => 4);
		}


		$names  = array("names" => json_encode($tagsarray));
		$result = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'location/get_by_names.json', $names, METHOD_GET, true, 2, 1000);
		if (!empty($result->data)) {
			$tags_location = array();
			foreach ($result->data as $key => &$value) {
				unset($value->name);
				unset($value->state);
				$tags_location[] = $value;
			}
			$tags   = json_encode($tags_location);
			$params = compact('cid', 'tags');
			$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'tag/add_location.json', $params, METHOD_POST, true, 2, 1000);
		}


		$this->display($result);
	}

	//活动详情
	public function detail_contest()
	{
		$this->verifyLogin();//用户验证
		$cid         = $this->input->get('cid', true);
		$intro       = "1";
		$data        = array();
		$web_title   = "查看活动";
		$server_name = BASE_DOMAIN;

		$params = compact('cid', 'intro');
		$verify = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', $params, METHOD_GET, true, 2, 1000);


		if ($verify->error < 0) {
			return $this->errorInfo('没有权限', $verify->error);
		}
		$data = $verify->result;

		$page = 1;
		$size = 20;
		//活动项目+表单
		$itemdata = array();

		$params     = compact('cid', 'page', 'size', 'type');
		$items_list = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/list.json', $params, METHOD_GET, true, 2, 1000);
		if (!empty($items_list) && $items_list->error == 0) {
			$itemdata = $items_list->data;

		}
		if (!empty($itemdata)) {

			$item_ids = array_column(json_decode(json_encode($itemdata), true), 'pk_contest_items');

			$item_ids = implode(',', $item_ids);

			$form_list = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'form/get_by_item_ids.json', compact('item_ids'), METHOD_GET);

			if (!empty($form_list->data)) {
				$form_list->data = array_column(json_decode(json_encode($form_list->data), true), null, 'fk_contest_items');
				foreach ($itemdata as $key => $value) {
					$value->forminfo = null;
					if (array_key_exists($value->pk_contest_items, $form_list->data)) {
						$value->forminfo = (object)$form_list->data[$value->pk_contest_items];
					}
				}
			}

		}
		// //组织单位
		// $udata      = array();
		// $units_list = $this->Contest_model->listTagUnits($cid, $page, $size);
		// if (!empty($units_list) && $units_list->error == 0) {
		// 	$udata = $units_list->data;
		// }
		//地理位置
		$localdata = array();

		$location = $this->list_location($cid);
		// if (!empty($location) && $location->error == 0) {
		// 	$localdata = $location;
		// }

		$itemEditEnable = $this->checkContestItemEditEnable($data->publish_state);


		global $CONTEST_GTYPE_LIST;
		global $CONTEST_UNITS_LIST;
		global $CONTEST_STATE_LIST;
		global $CONTEST_COUNTRY_LIST;
		global $CONTEST_SOURCE_LIST;
		global $CONTEST_ITEM_INVITE_REQUIRED_OPTIONS;
		global $CONTEST_LOTTERY_LIST;
		global $CONTEST_DELIVERGEAR_LIST;
		$data = compact(
			'web_title', 'data', 'itemdata', 'location', 'itemEditEnable',
			'CONTEST_UNITS_LIST', 'server_name', 'CONTEST_COUNTRY_LIST',
			'CONTEST_SOURCE_LIST', 'CONTEST_STATE_LIST', 'CONTEST_GTYPE_LIST',
			'CONTEST_ITEM_INVITE_REQUIRED_OPTIONS', 'CONTEST_LOTTERY_LIST',
			'CONTEST_DELIVERGEAR_LIST'
		);
		$this->display($data);
	}

	public function ajax_add_items()
	{
		$this->needLoginJson();

		$item_id         = $this->input->post('item_id', true);
		$cid             = $this->input->post('cid', true);
		$name            = $this->input->post('name', true);
		$fee             = $this->input->post('fee', true);
		$start_time      = $this->input->post('start_time', true);
		$end_time        = $this->input->post('end_time', true);
		$invite_required = $this->input->post('invite_required', true);
		$max_verify      = $this->input->post('max_verify', true);
		$type            = $this->input->post('type', true);
		$max_stock       = $this->input->post('max_stock', true);
		$team_max_stock  = $this->input->post('team_max_stock', true);
		$team_size       = $this->input->post('team_size', true);
		$multi_buy       = $this->input->post('multi_buy', true);

		//新增活动项目
		$params = compact(
			'cid', 'name', 'fee', 'start_time', 'end_time', 'invite_required', 'max_verify', 'type'
			, 'max_stock', 'team_max_stock', 'team_size', 'multi_buy'
		);


		if (!empty($item_id)) {
			// print_r($item_id);die;

			// $verify = $this->verifyContestItemEdit($item_id, true);
			// if ($verify->error < 0) {
			// 	return $this->errorInfo('没有权限', $verify->error);
			// }
			unset($params['cid']);
			$params['item_id'] = $item_id;
			// $result           = $this->Contest_model->updateContestItem($params);
			$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/update.json', $params, METHOD_POST, true, 2, 1000);
		} else {
			// $verify = $this->verifyContestEdit($cid, true);
			// if ($verify->error < 0) {
			// 	return $this->errorInfo('没有权限', $verify->error);
			// }
			$result = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/add.json', $params, METHOD_POST, true, 2, 1000);
		}
		$this->display($result);
	}


	public function create()
	{

	}

	public function detail()
	{
		// contest/get.json
	}
	//
	//创建活动
	public function add_contest()
	{
		$this->verifyLogin();//用户验证
		$web_title = "创建活动";
		global $CONTEST_LEVEL_LIST;
		global $CONTEST_COUNTRY_LIST;
		global $CONTEST_SOURCE_LIST;
		$data = compact('web_title', 'CONTEST_LEVEL_LIST', 'CONTEST_COUNTRY_LIST', 'CONTEST_SOURCE_LIST');
		$this->display($data);
	}
	//
	//添加项目
	public function add_item()
	{
		$this->verifyLogin();//用户验证
		$data = array();
		$this->display($data);
	}

	//编辑项目
	public function edit_item()
	{
		$this->verifyLogin();//用户验证
		$data = array();
		$this->display($data);
	}

	public function ajax_edit_item()
	{
		$this->verifyLogin();//用户验证
		$item_id  = $this->input->get('item_id', true);
		$params   = compact('item_id');
		$itemInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', $params, METHOD_GET, true, 2, 1000);
		if (empty($itemInfo->result)) {
			return $this->errorInfo('项目不存在', $itemInfo->error);
		}
		$data = $itemInfo;
		$this->display($data);
	}


	public function export_invite_code()
	{
		$this->needLoginJson();

		$item_id = $this->input->get('item_id', true);

		$itemInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contestitem/get.json', compact('item_id'), METHOD_GET);
		$itemInfo = $this->obj2array($itemInfo);
		if (empty($itemInfo['result'])) {
			$this->displayError('项目异常', -1);
		}

		if ($itemInfo['result']['state'] != CONTEST_ITEM_STATE_OK) {
			$this->displayError('项目状态异常', -2);
		}

		$contestInfo = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'contest/get.json', array('cid' => $itemInfo['result']['fk_contest']), METHOD_GET);
		$contestInfo = $this->obj2array($contestInfo);
		if (empty($contestInfo['result'])) {
			$this->displayError('活动异常', -3);
		}

		if ($contestInfo['result']['fk_corp'] != $this->userInfo->pk_corp) {
			$this->displayError('没有权限', -4);
		}

		$page           = 1;
		$size           = 1000;
		$params         = compact('item_id', 'size');
		$inviteCodeList = array();
		while (true) {
			$params['page'] = $page;
			$result         = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'invitecode/list.json', $params, METHOD_GET, false, 2);
			if (empty($result) || empty($result->data)) {
				break;
			}

			foreach ($result->data as $v) {
				$inviteCodeList[] = $v->invite_code;
			}
			$page++;
		}
		$this->outputFile('invite_code.csv', $inviteCodeList);
	}

	//
	// //public function mobiledetail()
	// //{
	// //	$cid       = $this->input->get('cid', true);
	// //	$intro     = "1";
	// //	$data      = array();
	// //	$web_title = "查看活动";
	// //	// 获取活动
	// //	$result = $this->Contest_model->getContestById($cid, $intro);
	// //	if (!empty($result) && $result->error == 0) {
	// //		$data = $result->result;
	// //	}
	// //	$page = 1;
	// //	$size = 20;
	// //	//竞赛项目+表单
	// //	$itemdata   = array();
	// //	$form       = "yes";
	// //	$items_list = $this->Contest_model->get_items_list($cid, $page, $size, $form);
	// //	if (!empty($items_list) && $items_list->error == 0) {
	// //		$itemdata = $items_list->data;
	// //	}
	// //	global $NEWCONTEST_LEVEL_LIST;
	// //	global $CONTEST_UNITS_LIST;
	// //	global $CONTEST_STATE_LIST;
	// //	$data = compact('web_title', 'data', 'itemdata', 'NEWCONTEST_LEVEL_LIST', 'CONTEST_STATE_LIST');
	// //	$this->display($data);
	// //}
	//
	// private function checkContestItemEditEnable($publishState)
	// {
	// 	if (in_array($publishState, [CONTEST_PUBLISH_STATE_SELLING])) {
	// 		return false;
	// 	}
	//
	// 	return true;
	// }


	public function ajax_change_contest_publish_state()
	{
		$this->needLoginJson();

		$cid = $this->input->post('cid', true);
		$act = $this->input->post('act', true);

		$verify = $this->verifyContestEdit($cid);
		if ($verify->error < 0) {
			return $this->errorInfo('没有权限', $verify->error);
		}

		$result = $this->Contest_model->changeContestPublishState($cid, $act);
		$this->display($result);
	}










	//
	//
	// public function ajax_add_units()
	// {
	// 	$this->needLoginJson();
	//
	// 	$cid  = $this->input->post('cid', true);
	// 	$tag  = $this->input->post('tag', true);
	// 	$role = $this->input->post('role', true);
	//
	// 	$verify = $this->verifyContestEdit($cid);
	// 	if ($verify->error < 0) {
	// 		return $this->errorInfo('没有权限', $verify->error);
	// 	}
	//
	// 	//新增组织单位
	// 	$postparams = compact('cid', 'tag', 'role');
	// 	$result     = $this->Contest_model->addContestUnits($postparams);
	//
	// 	if ($result->error != 0) {
	// 		log_message_v2('error', $result);
	// 		$result->info = '添加组织单位失败';
	// 	}
	// 	$this->display($result);
	// }
	//
	// public function ajax_add_items()
	// {
	// 	$this->needLoginJson();
	//
	// 	$itemId          = $this->input->post('itemid', true);
	// 	$cid             = $this->input->post('cid', true);
	// 	$name            = $this->input->post('name', true);
	// 	$max_stock       = $this->input->post('max_stock', true);
	// 	$fee             = $this->input->post('fee', true);
	// 	$start           = $this->input->post('start', true);
	// 	$max_verify      = $this->input->post('max_verify', true);
	// 	$end             = $this->input->post('end', true);
	// 	$invite_required = $this->input->post('invite_required', true);
	//
	// 	//新增活动项目
	// 	$params = compact('cid', 'name', 'max_stock', 'fee', 'start', 'end', 'invite_required', 'max_verify');
	// 	if (!empty($itemId)) {
	// 		$verify = $this->verifyContestItemEdit($itemId, true);
	// 		if ($verify->error < 0) {
	// 			return $this->errorInfo('没有权限', $verify->error);
	// 		}
	// 		unset($params['cid']);
	// 		$params['itemid'] = $itemId;
	// 		$result           = $this->Contest_model->updateContestItem($params);
	// 	} else {
	// 		$verify = $this->verifyContestEdit($cid, true);
	// 		if ($verify->error < 0) {
	// 			return $this->errorInfo('没有权限', $verify->error);
	// 		}
	// 		$result = $this->Contest_model->addContestItem($params);
	// 	}
	// 	$this->display($result);
	//
	// }
	//


	//

	private function outputFile($fileName, $inviteCodeList)
	{
		header("Content-type:text/csv");
		header("Content-Disposition:attachment;filename=" . $fileName);
		header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
		header('Expires:0');
		header('Pragma:public');

		foreach ($inviteCodeList as $k => $v) {
			if ($k > 0 && $k % 10 == 0) {
				echo PHP_EOL;
			}
			echo $v . ',';
		}
	}
}
