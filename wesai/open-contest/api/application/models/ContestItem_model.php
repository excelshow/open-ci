<?php
/**
 * User: zhaodc
 * Date: 23/09/2016
 * Time: 14:54
 */
require_once __DIR__ . '/ModelBase.php';

class ContestItem_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 新增活动项目
	 *
	 * @param array $params 参数列表
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create($params)
	{
		$params['utime'] = null;

		return $this->setTable($this->tableNameContestItem)
		            ->addInsertColumns($params, ['utime'])
		            ->doInsert();
	}

	/**
	 * 根据项目ID获取活动项目资料
	 *
	 * @param  integer $pk_contest_items 活动项目ID
	 * @param string   $dsn_type
	 *
	 * @return array|bool
	 * @throws \Exception
	 */
	public function getById($pk_contest_items, $dsn_type = Pdo_Mysql::DSN_TYPE_SLAVE)
	{
		$result = $this->setDsnType($dsn_type)
		               ->setTable($this->tableNameContestItem)
		               ->addQueryConditions('pk_contest_items', $pk_contest_items)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function remove($itemId)
	{
		return $this->setTable($this->tableNameContestItem)
		            ->addUpdateColumns('state', CONTEST_ITEM_STATE_NG)
		            ->addQueryConditions('pk_contest_items', $itemId)
		            ->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
		            ->doUpdate();
	}

	/**
	 * 更新活动项目
	 *
	 * @param  integer $pk_contest_items 活动项目ID
	 * @param  array   $params           参数列表
	 *
	 * @return bool|mixed
	 * @throws \Exception
	 */
	public function modify($pk_contest_items, $params)
	{
		$columns = array();
		foreach ($params as $key => $val) {
			$columns[] = array($key, $val);
		}

		return $this->setTable($this->tableNameContestItem)
		            ->addUpdateColumns($columns)
		            ->addQueryConditions('pk_contest_items', $pk_contest_items)
		            ->doUpdate();
	}

	/**
	 * 获取活动活动项目列表
	 *
	 * @param          $fk_corp
	 * @param  integer $fk_contest 活动ID
	 * @param          $type
	 * @param  integer $page       页码
	 * @param  integer $size       页长
	 *
	 * @param          $total
	 *
	 * @return array|bool
	 */
	public function listByPage($fk_corp, $fk_contest, $type, $page, $size, &$total)
	{
		$model = $this->setTable($this->tableNameContestItem)
		              ->addQueryFieldsCount('pk_contest_items', 'count')
		              ->addQueryConditions('fk_corp', $fk_corp)
		              ->addQueryConditions('fk_contest', $fk_contest)
		              ->addQueryConditions('state', CONTEST_ITEM_STATE_OK);
		if (!empty($type)) {
			$model = $model->addQueryConditions('type', $type);
		}

		$countResult = $model->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}


		$model = $this->setTable($this->tableNameContestItem)
		              ->addQueryConditions('fk_corp', $fk_corp)
		              ->addQueryConditions('fk_contest', $fk_contest)
		              ->addQueryConditions('state', CONTEST_ITEM_STATE_OK);
		if (!empty($type)) {
			$model = $model->addQueryConditions('type', $type);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect($page, $size);

	}

	public function listVerifyingItems($fkCorp, $date, $pageNumber, $pageSize, &$total)
	{
		$countResult = $this->setTable($this->tableNameContestItem)
		                    ->addQueryFieldsCount('pk_contest_items', 'count')
		                    ->addQueryConditions('fk_corp', $fkCorp)
		                    ->addQueryConditions('start_time', date('Y-m-d 00:00:00', strtotime($date)), '>=')
		                    ->addQueryConditions('start_time', date('Y-m-d 23:59:59', strtotime($date)), '<=')
		                    ->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
		                    ->doSelect(1, 1);

		$total = $countResult[0]['count'];

		if (empty($total)) {
			return array();
		}

		return $this->setTable($this->tableNameContestItem)
		            ->addQueryConditions('fk_corp', $fkCorp)
		            ->addQueryConditions('start_time', date('Y-m-d 00:00:00', strtotime($date)), '>=')
		            ->addQueryConditions('start_time', date('Y-m-d 23:59:59', strtotime($date)), '<=')
		            ->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
		            ->doSelect($pageNumber, $pageSize);
	}

	public function getSellingItemByCids($ids, $pageNumber, $pageSize)
	{
		return $this->setTable($this->tableNameContestItem)
		            ->addQueryFields('fk_contest,max_stock,cur_stock')
		            ->addQueryConditionIn('fk_contest', $ids)
		            ->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
		            ->addQueryConditions('end_time', date('Y-m-d H:i:s'), '>')
		            ->addOrderBy('pk_contest_items', 'asc')
		            ->doSelect($pageNumber, $pageSize);
	}

	/**
	 * 根据项目ID获取活动项目资料
	 *
	 * @param        $itemIds
	 * @param string $dsn_type
	 *
	 * @return array|bool
	 * @throws \Exception
	 * @internal param int $pk_contest_items 活动项目ID
	 */
	public function getByIds($itemIds, $dsn_type = Pdo_Mysql::DSN_TYPE_SLAVE)
	{
		return $this->setDsnType($dsn_type)
		            ->setTable($this->tableNameContestItem)
		            ->addQueryConditionIn('pk_contest_items', $itemIds)
		            ->doSelect(1, count($itemIds));
	}

	private function lockItems($itemIds, $model = null)
	{
		return $this->getInstance($model)
					->setTable($this->tableNameContestItem)
		            ->addQueryConditionIn('pk_contest_items', $itemIds)
		            ->lockRow()
		            ->doSelect();
	}

	private function reduceCurStock($itemId, $stockCount, $model = null)
	{
		return $this->getInstance($model)
					->setTable($this->tableNameContestItem)
		            ->addUpdateColumns('cur_stock', $stockCount, '-')
		            ->addQueryConditions('pk_contest_items', $itemId)
		            ->addQueryConditions('cur_stock', $stockCount, '>=')
		            ->doUpdate();
	}

	public function reduceCurStocks($itemList, $model = null)
	{
		$itemIds = array_keys($itemList);
		$this->lockItems($itemIds, $model);

		$affectedRows = 0;
		foreach ($itemList as $itemId => $stockCount) {
			$affectedRows += $this->reduceCurStock($itemId, $stockCount, $model);
		}

		return $affectedRows;
	}

	public function increaseCurStocks($itemList, $model = null)
	{
		$itemIds = array_keys($itemList);
		$this->lockItems($itemIds, $model);

		$affectedRows = 0;
		foreach ($itemList as $itemId => $stockCount) {
			$affectedRows += $this->increaseCurStock($itemId, $stockCount, $model);
		}

		return $affectedRows;
	}

	private function increaseCurStock($itemId, $stockCount, $model = null)
	{
		return $this->getInstance($model)
					->setTable($this->tableNameContestItem)
		            ->addUpdateColumns('cur_stock', $stockCount, '+')
		            ->addQueryConditions('pk_contest_items', $itemId)
		            ->doUpdate();
	}

	public function reduceTeamCurStock($itemId, $model = null)
	{
		$this->lockItems(array($itemId), $model);

		return $this->getInstance($model)
		            ->setTable($this->tableNameContestItem)
		            ->addUpdateColumns('team_cur_stock', 1, '-')
		            ->addQueryConditions('team_cur_stock', 1, '>=')
		            ->addQueryConditions('pk_contest_items', $itemId)
		            ->doUpdate();
	}

	public function increaseTeamCurStock($itemId)
	{
		$this->lockItems(array($itemId));

		return $this->setTable($this->tableNameContestItem)
		            ->addUpdateColumns('team_cur_stock', 1, '+')
		            ->addQueryConditions('pk_contest_items', $itemId)
		            ->doUpdate();
	}


	public function getFeeRangeByCids($contestIds){

		$columns = array('fk_contest');

		$contestInfo = $this->setTable($this->tableNameContestItem)
							->addQueryFields($columns)
							->addQueryFieldCalc('min', 'fee', 'minfee')
							->addQueryFieldCalc('max', 'fee', 'maxfee')
							->addQueryConditionIn('fk_contest', $contestIds)
							->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
							->addGroupBy('fk_contest')
							->doSelect(0, 0);

		return $contestInfo;

	}

	public function getEnrolDataCountByCids($cids)
	{
		return $this->setTable($this->tableNameContestItem)
		            ->addQueryFields('fk_contest')
		            ->addQueryFieldCalc('sum', 'enrol_data_count')
		            ->addQueryConditionIn('fk_contest', $cids)
		            ->addQueryConditions('state', CONTEST_ITEM_STATE_OK)
		            ->addGroupBy('fk_contest')
		            ->doSelect();
	}

	/**
	 * @param $itemCount array(itemId => offset)
	 *
	 * @return bool|int
	 */
	public function increaseEnrolDataCount($itemCount)
	{
		$affectedRows = 0;
		if (empty($itemCount)) {
			return $affectedRows;
		}

		foreach ($itemCount as $itemId => $offset) {
			$affectedRows += $this->setTable($this->tableNameContestItem)
			                      ->addUpdateColumns('enrol_data_count', $offset, '+')
			                      ->addQueryConditions('pk_contest_items', $itemId)
			                      ->doUpdate();
		}

		return $affectedRows;
	}
}
