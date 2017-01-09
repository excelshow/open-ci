<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 活动类
 *
 * @package default
 * @author  : zhaodechang@wesai.com
 **/
class Contest extends Base
{
	/**
	 * 构造函数
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 新增活动
	 *
	 */
	public function add_post()
	{
		$this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('ename', PARAM_NULL_EMPTY);
		$this->post_check('intro', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_STRING, false);
		$this->post_check('logo', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('poster', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('banner', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('sdate_start', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('sdate_end', PARAM_NULL_NOT_EMPTY);
		$this->post_check('location', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('gtype', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('corp_uid', PARAM_NOT_NULL_NOT_EMPTY);
		$this->post_check('level', PARAM_NULL_EMPTY);
		$this->post_check('lottery', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('deliver_gear', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('country_scope', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('service_tel', PARAM_NULL_EMPTY);
		$this->post_check('service_mail', PARAM_NULL_EMPTY);
		$this->post_check('template', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('show_enrol_data_count', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$contest_params                 = $this->get_request_params();
		$contest_params['fk_corp']      = $contest_params['corp_id'];
		$contest_params['fk_corp_user'] = $contest_params['corp_uid'];
		unset($contest_params['corp_id'], $contest_params['corp_uid']);

		if(preg_match('/[^_\w\x{4e00}-\x{9fa5}]/u', $contest_params['name'])){
			return $this->response_error(Error_Code::ERROR_PARAM, '活动名中不能含有特殊字符');
		}
		
		if (empty($contest_params['service_tel']) && empty($contest_params['service_mail'])) {
			return $this->response_error(Error_Code::ERROR_PARAM, '客服电话和邮箱至少填写一项');
		}

		if (!empty($contest_params['sdate_end']) && $contest_params['sdate_end'] < $contest_params['sdate_start']) {
			return $this->response_error(Error_Code::ERROR_PARAM, '活动截止日期不可小于开始日期');
		}

		if (empty($contest_params['sdate_end'])) {
			$contest_params['sdate_end'] = $contest_params['sdate_start'];
		}

		if (empty($contest_params['intro'])) {
			$contest_params['intro'] = '';
		}

		$params = compact('contest_params');

		switch ($contest_params['gtype']) {
			case CONTEST_GTYPE_MALATHION:
				$malathion_params = array('state' => MALATHION_STATE_DRAFT);

				$params['malathion_params'] = $malathion_params;
				break;
		}

		$cid = $this->Contest_model->create($params);

		return $this->response_insert($cid);
	}

	/**
	 * 根据活动ID获取活动资料
	 *
	 */
	public function get_get()
	{
		// 活动ID
		$cid = $this->get_check('cid', PARAM_NOT_NULL_NOT_EMPTY);
		// 是否返回图文简介
		$returnIntro = $this->get_check('intro', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$contestInfo = $this->verifyContestExists($cid, $returnIntro);

		switch ($contestInfo['gtype']) {
			case CONTEST_GTYPE_MALATHION:
				$malathionInfo = $this->Contest_model->getMalathionById($cid);
				if (empty($malathionInfo)) {
					return $this->response_error(Error_Code::ERROR_MALATHION_NOT_EXISTS);
				}
				$contestInfo['malathion_info'] = $malathionInfo;
				break;

			default:
				break;
		}

		return $this->response_object($contestInfo);
	}

	/**
	 * 更新活动信息
	 *
	 * @return  Integer affect rows
	 **/
	public function update_post()
	{
		$this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('name', PARAM_NULL_NOT_EMPTY);
		$this->post_check('ename', PARAM_NULL_EMPTY);
		$this->post_check('intro', PARAM_NULL_EMPTY, PARAM_TYPE_STRING, false);
		$this->post_check('logo', PARAM_NULL_NOT_EMPTY);
		$this->post_check('poster', PARAM_NULL_NOT_EMPTY);
		$this->post_check('banner', PARAM_NULL_NOT_EMPTY);
		$this->post_check('sdate_start', PARAM_NULL_NOT_EMPTY);
		$this->post_check('sdate_end', PARAM_NULL_EMPTY);
		$this->post_check('location', PARAM_NULL_NOT_EMPTY);
		$this->post_check('level', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$this->post_check('lottery', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('deliver_gear', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('country_scope', PARAM_NULL_NOT_EMPTY);
		$this->post_check('service_tel', PARAM_NULL_EMPTY);
		$this->post_check('service_mail', PARAM_NULL_EMPTY);
		$this->post_check('template', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$this->post_check('show_enrol_data_count', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$contest_params = $this->get_request_params();

		$contest_params['pk_contest'] = $contest_params['cid'];
		unset($contest_params['cid'], $contest_params['gtype']);

		$cid = $contest_params['pk_contest'];

		$result = $this->contestEditVerify($cid, false);

		// 获取活动资料
		$contestInfo = $result->contestInfo;

		!isset($contest_params['sdate_start']) and $contest_params['sdate_start'] = $contestInfo['sdate_start'];
		isset($contest_params['sdate_end']) && empty($contest_params['sdate_end']) and $contest_params['sdate_end'] = $contest_params['sdate_start'];
		!isset($contest_params['service_tel']) and $contest_params['service_tel'] = $contestInfo['service_tel'];
		!isset($contest_params['service_mail']) and $contest_params['service_mail'] = $contestInfo['service_mail'];

		if (empty($contest_params['service_tel']) && empty($contest_params['service_mail'])) {
			return $this->response_error(Error_Code::ERROR_PARAM, '客服电话和邮箱至少填写一项');
		}

		if (!empty($contest_params['sdate_end']) && $contest_params['sdate_end'] < $contest_params['sdate_start']) {
			return $this->response_error(Error_Code::ERROR_PARAM, '活动截止日期不可小于开始日期');
		}

		$params = compact('contest_params');

		$affected_rows = $this->Contest_model->modify($cid, $params);

		// 国外活动，解除地理位置标签
		if ($contest_params['country_scope'] != CONTEST_COUNTRY_INTERNAL) {
			$this->load->model('Tag_model');
			@$this->Tag_model->removeLocations($contest_params['cid']);
		}

		return $this->response_update($affected_rows);
	}

	/**
	 * 筛选活动front
	 *
	 */
	public function search_get()
	{
		$location   = $this->get_check('location', PARAM_NULL_EMPTY);
		$gtype      = $this->get_check('gtype', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$minDate    = $this->get_check('min_date', PARAM_NULL_EMPTY);
		$maxDate    = $this->get_check('max_date', PARAM_NULL_EMPTY);
		$name       = $this->get_check('name', PARAM_NULL_EMPTY);
		$pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$fkCorp     = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$template   = $this->get_check('template', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		!empty($minDate) or $minDate = '1970-01-01';
		!empty($maxDate) or $maxDate = date('Y-m-d 23:59:59');

		$minTime = strtotime(date('Y-m-d 00:00:00', strtotime($minDate)));
		$maxTime = strtotime(date('Y-m-d 23:59:59', strtotime($maxDate)));

		if (!empty($location)) {
			$location = explode(',', $location);
		}

		$result     = array();
		$contestIds = $this->Contest_model->searchContestFront($fkCorp, $name, $location, $gtype, $minTime, $maxTime, $pageNumber, $pageSize, $template);
		$total      = $contestIds->total;
		if (empty($contestIds->result)) {
			return $this->response_list($result, $total, $pageNumber, $pageSize);
		}
		$contestList = $this->Contest_model->getByIds($contestIds->result);

		$this->load->helper('usort');
		usort($contestList, compare_array_str('ctime', 'DESC'));

		return $this->response_list($contestList, $total, $pageNumber, $pageSize);
	}

	/**
	 * 筛选活动manage
	 *
	 */
	public function search_manage_get()
	{
		$fk_corp = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
		$name    = $this->get_check('name', PARAM_NULL_EMPTY);
		$gtype   = $this->get_check('gtype', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$state   = $this->get_check('state', PARAM_NULL_EMPTY);
		$page    = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$size    = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$minDate = $this->get_check('min_date', PARAM_NULL_EMPTY);
		$maxDate = $this->get_check('max_date', PARAM_NULL_EMPTY);

		$this->checkPageParams($page, $size);

		!empty($minDate) or $minDate = '1970-01-01';
		!empty($maxDate) or $maxDate = date('Y-m-d 23:59:59');

		$minTime = strtotime(date('Y-m-d 00:00:00', strtotime($minDate)));
		$maxTime = strtotime(date('Y-m-d 23:59:59', strtotime($maxDate)));

		if (!empty($state)) {
			$state = explode(',', $state);
		}

		$result = array();
		$cids   = $this->Contest_model->searchContestManage($fk_corp, $name, $state, $gtype, $page, $size, $minTime, $maxTime);

		$total = $cids->total;
		if (empty($cids->result)) {
			return $this->response_list($result, $total, $page, $size);
		}

		$contestList = $this->Contest_model->getByIds($cids->result);

		$this->load->helper('usort');
		usort($contestList, compare_array_str('ctime', 'DESC'));

		return $this->response_list($contestList, $total, $page, $size);
	}

	public function list_get()
	{
		$fk_corp    = $this->get_check('corp_id', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$visible    = $this->get_check('visible', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
		$pageSize   = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$this->checkPageParams($pageNumber, $pageSize);

		$result = $this->Contest_model->listByPage($pageNumber, $pageSize, $visible, $fk_corp);

		return $this->response_list($result, count($result), $pageNumber, $pageSize);
	}

	public function online_post()
	{
		$cid = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY);

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($cid);

		$this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_DRAFT);

		$result = $this->Contest_model->online($cid);

		return $this->response_update($result);
	}

	public function start_selling_post()
	{
		$cid = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY);

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($cid);

		if (empty(trim($contestInfo['logo'])) || empty(trim($contestInfo['poster'])) || empty(trim($contestInfo['banner']))) {
            return $this->response_error(Error_Code::ERROR_CONTEST_INFO_NOT_ENOUGH, '请设置Logo、海报和横幅');
        }

        if (empty(trim($contestInfo['location']))) {
            return $this->response_error(Error_Code::ERROR_CONTEST_INFO_NOT_ENOUGH, '请设置具体的地理位置');
        }

        if ($contestInfo['sdate_start'] == '0000-00-00 00:00:00' || $contestInfo['sdate_end'] == '0000-00-00 00:00:00') {
            return $this->response_error(Error_Code::ERROR_CONTEST_INFO_NOT_ENOUGH, '请设置具体的开始截止时间');
        }

        if (empty(trim($contestInfo['service_tel'])) && empty(trim($contestInfo['service_mail']))) {
            return $this->response_error(Error_Code::ERROR_CONTEST_INFO_NOT_ENOUGH, '请设置联系电话或邮箱');
        }

		$this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_ON);

		$total    = 0;
		$itemList = $this->ContestItem_model->listByPage($contestInfo['fk_corp'], $cid, null, 0, 50, $total);
		if (empty($total) || empty($itemList)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
		}

		$itemIds = array_column($itemList, 'pk_contest_items');

		$enrolFormList = $this->Form_model->getByItemIds($itemIds);
		if (empty($enrolFormList) || count($enrolFormList) != count($itemIds)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_ENROL_FORM_NOT_ENOUGH);
		}

		$result = $this->Contest_model->startSellingContest($cid);

		return $this->response_update($result);
	}

	public function offline_post()
	{
		$cid = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY);

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($cid);

		$this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_SELLING);

		$result = $this->Contest_model->offline($cid, $contestInfo['publish_state']);

		return $this->response_update($result);
	}

	public function re_online_post()
	{
		$cid = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY);

		// 获取活动资料
		$contestInfo = $this->verifyContestExists($cid);

		$this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_OFF);

		$result = $this->Contest_model->reOnline($cid);

		return $this->response_update($result);

	}


	public function get_by_ids_get()
	{
		$contestIds = $this->get_check('cids', PARAM_NOT_NULL_NOT_EMPTY);
		// 是否返回图文简介
		$returnIntro = $this->get_check('intro', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

		$contestIds = explode(',', $contestIds);

		$contestList = $this->Contest_model->getByIds($contestIds, $returnIntro);

		$total = count($contestList);

		return $this->response_list($contestList, $total, 1, $total);
	}

	public function get_enrol_data_count_by_ids_get()
	{
		$cids = $this->get_check('cids', PARAM_NOT_NULL_NOT_EMPTY);
		$cids = $this->distinct_array_values(explode(',', $this->trim_string_ids($cids)));

		if (empty($cids)) {
			return $this->response_list(array(), 0);
		}

		$enrolDataCountList = $this->ContestItem_model->getEnrolDataCountByCids($cids);

		return $this->response_list($enrolDataCountList, count($enrolDataCountList), 1, count($enrolDataCountList));
	}
} // END class Contest
