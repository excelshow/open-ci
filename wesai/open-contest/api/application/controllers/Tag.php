<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 15:31
 */
require __DIR__ . '/Base.php';

class Tag extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 添加地理位置
	 *
	 * @param tags [{"level":1,"tag_id":2},{"level":2,"tag_id":3}]
	 */
	public function add_location_post()
	{
		$cid  = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$tags = $this->post_check('tags', PARAM_NOT_NULL_NOT_EMPTY);

		$tagList = json_decode($tags, true);
		if (empty($tagList) || !is_array($tagList)) {
			return $this->response_error(Error_Code::ERROR_PARAM);
		}

		$locationList = $this->Tag_model->listLocation($cid);
		if (empty($locationList)) {
			$result = $this->Tag_model->addLocations($cid, $tagList);

			return $this->response_update(count($result));
		}

		$locationList = array_column($locationList, null, 'level');

		$affectedRows = 0;
		foreach ($tagList as $tag) {
			if (!array_key_exists($tag['level'], $locationList)) {
				$this->Tag_model->addLocations($cid, array($tag));
				$affectedRows++;
				continue;
			}
			if ($locationList[$tag['level']]['fk_tag'] != $tag['tag_id']) {
				$affectedRows += $this->Tag_model->updateLocation($cid, $tag['level'], $tag['tag_id']);
			}
		}

		return $this->response_update($affectedRows);
	}

	/**
	 * 获取活动地理位置列表
	 *
	 */
	public function list_location_get()
	{
		$cid     = $this->get_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$tagList = $this->Tag_model->listLocation($cid);

		return $this->response_list($tagList, count($tagList), 1, count($tagList));
	}

	public function list_location_by_cids_get()
	{
		$contestIds = $this->get_check('cids', PARAM_NOT_NULL_NOT_EMPTY);

		$contestIds = explode(',', $contestIds);
		$tagList    = $this->Tag_model->listLocationByContestIds($contestIds);

		return $this->response_list($tagList, count($tagList), 1, count($tagList));
	}

	/**
	 * 添加组织单位
	 *
	 */
	public function add_unit_post()
	{
		$cid  = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$tag  = $this->post_check('tag_id', PARAM_NOT_NULL_NOT_EMPTY);
		$role = $this->post_check('role', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$tagInfo = $this->Tag_model->getUnitByUnqKey($cid, $tag, $role);
		if (!empty($tagInfo)) {
			if ($tagInfo['state'] == TAG_STATE_OK) {
				return $this->response_error(Error_Code::ERROR_CONTEST_UNITS_EXIST);
			}

			$this->Tag_model->updateUnitState($tagInfo['pk_mapping_contest_unit'], TAG_STATE_OK, TAG_STATE_NG);

			return $this->response_insert(1);
		}

		$lastId = $this->Tag_model->addUnit($cid, $tag, $role);
		if (false === $lastId) {
			return $this->response_error(Error_Code::ERROR_CONTEST_ADD_TAG_UNITS_FAIL);
		}

		return $this->response_insert($lastId);
	}

	public function delete_unit_post()
	{
		$mappingId = $this->post_check('mapping_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$tagInfo = $this->Tag_model->getUnitById($mappingId);
		if (empty($tagInfo)) {
			return $this->response_error(Error_Code::ERROR_CONTEST_UNITS_NOT_SET);
		}

		$affectedRows = $this->Tag_model->updateUnitState($mappingId, TAG_STATE_NG, TAG_STATE_OK);

		return $this->response_update($affectedRows);
	}

	/**
	 * 获取活动组织单位列表
	 *
	 */
	public function list_unit_get()
	{
		$cid     = $this->get_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
		$tagList = $this->Tag_model->listUnit($cid);

		return $this->response_list($tagList, count($tagList), 20);
	}

	public function delete_location_post()
	{
		$cid = $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$affectedRows = $this->Tag_model->removeLocations($cid);

		return $this->response_update($affectedRows);
	}
}
