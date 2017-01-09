<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 15:37
 */
require_once __DIR__ . '/ModelBase.php';

class Tag_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取活动地理位置列表
	 *
	 * @param  integer $fk_contest 活动ID
	 *
	 * @return array|bool
	 * @throws \Exception
	 */
	public function listLocation($fk_contest)
	{
		return $this->setTable($this->tableNameMappingContestLocation)
		            ->addQueryFields('fk_contest,fk_tag,level,state')
		            ->addQueryConditions('fk_contest', $fk_contest)
		            ->addOrderBy('level', 'asc')
		            ->doSelect();
	}

	public function listLocationByContestIds($contestIds)
	{
		return $this->setTable($this->tableNameMappingContestLocation)
		            ->addQueryConditionIn('fk_contest', $contestIds)
		            ->addOrderBy('level', 'asc')
		            ->doSelect(1, count($contestIds) * 3);
	}

	public function addLocations($fk_contest, $tagList)
	{
		$lastIds = array();
		foreach ($tagList as $tag) {
			$lastIds[] = $this->addLocation($fk_contest, $tag['tag_id'], $tag['level']);
		}

		return $lastIds;
	}

	public function addLocation($fk_contest, $fk_tag, $level)
	{
		return $this->setTable($this->tableNameMappingContestLocation)
		            ->addInsertColumns(compact('fk_contest', 'fk_tag', 'level'))
		            ->doInsert();
	}

	public function updateLocation($fk_contest, $level, $fk_tag)
	{
		return $this->setTable($this->tableNameMappingContestLocation)
		            ->addUpdateColumns('fk_tag', $fk_tag)
		            ->addQueryConditions('fk_contest', $fk_contest)
		            ->addQueryConditions('level', $level)
		            ->doUpdate();
	}

	public function removeLocations($fk_contest)
	{
		return $this->setTable($this->tableNameMappingContestLocation)
		            ->addUpdateColumns('state', MAPPING_STATE_NG)
		            ->addQueryConditions('fk_contest', $fk_contest)
		            ->doUpdate();
	}

	/**
	 * 为活动添加组织单位标签
	 *
	 * @param integer $fk_contest 活动ID
	 * @param         $fk_tag
	 * @param integer $role       组织单位角色
	 *
	 * @return bool|mixed
	 * @internal param string $tag 组织单位标签名称
	 */
	public function addUnit($fk_contest, $fk_tag, $role)
	{
		return $this->setTable($this->tableNameMappingContestUnit)
		            ->addInsertColumns(compact('fk_contest', 'fk_tag', 'role'))
		            ->doInsert();
	}

	public function updateUnitState($mappingId, $toState, $fromState)
	{
		return $this->setTable($this->tableNameMappingContestUnit)
		            ->addUpdateColumns('state', $toState)
		            ->addQueryConditions('pk_mapping_contest_unit', $mappingId)
		            ->addQueryConditions('state', $fromState)
		            ->doUpdate();
	}

	public function getUnitByUnqKey($fk_contest, $fk_tag, $role)
	{
		$result = $this->setTable($this->tableNameMappingContestUnit)
		               ->addQueryConditions('fk_contest', $fk_contest)
		               ->addQueryConditions('fk_tag', $fk_tag)
		               ->addQueryConditions('role', $role)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function getUnitById($mappingId)
	{
		$result = $this->setTable($this->tableNameMappingContestUnit)
		               ->addQueryConditions('pk_mapping_contest_unit', $mappingId)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function listUnit($fk_contest)
	{
		return $this->setTable($this->tableNameMappingContestUnit)
		            ->addQueryConditions('fk_contest', $fk_contest)
		            ->addQueryConditions('state', TAG_STATE_OK)
		            ->doSelect();
	}
}
