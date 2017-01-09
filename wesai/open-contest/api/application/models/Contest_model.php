<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once __DIR__ . '/ModelBase.php';

/**
 * 活动Model
 *
 * @package : default
 * @author  : zhaodechang@wesai.com
 **/
class Contest_model extends ModelBase
{

	/**
	 * 新增活动
	 *
	 * @param $params
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function create($params)
	{
		try {
			$this->beginTransaction();

			$cBindParams          = $params['contest_params'];
			$cBindParams['utime'] = null;

			$cid = $this->setTable($this->tableNameContest)
			            ->addInsertColumns($cBindParams, ['utime', 'intro'])
			            ->doInsert();

			switch ($cBindParams['gtype']) {
				case CONTEST_GTYPE_MALATHION:
					$mBindParams               = $params['malathion_params'];
					$mBindParams['fk_contest'] = $cid;

					$this->setTable($this->tableNameContestMalathion)
					     ->addInsertColumns($mBindParams)
					     ->doInsert();
					break;
			}

			$this->commit();

			return $cid;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据活动ID获取活动详情
	 *
	 * @param     $pk_contest
	 * @param int $returnIntro
	 *
	 * @return array|bool : array
	 * @internal param int $is_return_intro
	 *
	 * @author   : zhaodechang@wesai.com
	 */
	public function getById($pk_contest, $returnIntro = 0)
	{
		$columns = $this->getContestColumns($returnIntro);

		$result = $this->setTable($this->tableNameContest)
		               ->addQueryFields($columns)
		               ->addQueryConditions('pk_contest', $pk_contest)
		               ->doSelect();

		if (empty($result)) {
			return $result;
		}


		return $result[0];
	}

	private function getContestColumns($returnIntro = 0)
	{
		$columns = array(
			'pk_contest',
			'fk_corp',
			'fk_corp_user',
			'name',
			'ename',
			'gtype',
			'logo',
			'poster',
			'banner',
			'sdate_start',
			'sdate_end',
			'country_scope',
			'location',
			'publish_state',
			'source',
			'level',
			'lottery',
			'deliver_gear',
			'service_tel',
			'service_mail',
			'ctime',
			'utime',
			'template',
			'show_enrol_data_count',
            'partner'
		);

		if ($returnIntro == 1) {
			$columns[] = 'intro';
		}

		return $columns;
	}

	/**
	 * 根据活动ID获取活动详情
	 *
	 * @param     $ids
	 * @param int $returnIntro
	 *
	 * @return array|bool : array
	 * @throws \Exception
	 * @author: zhaodechang@wesai.com
	 */
	public function getByIds($ids, $returnIntro = 0)
	{
		$columns = $this->getContestColumns($returnIntro);

		return $this->setTable($this->tableNameContest)
		            ->addQueryFields($columns)
		            ->addQueryConditionIn('pk_contest', $ids)
		            ->doSelect(1, count($ids));
	}

	/**
	 * 根据活动ID获取马拉松活动资料
	 *
	 * @param $fk_contest
	 *
	 * @return array|bool : array
	 * @throws \Exception
	 * @author: zhaodechang@wesai.com
	 */
	public function getMalathionById($fk_contest)
	{
		$result = $this->setTable($this->tableNameMalathion)
		               ->addQueryConditions('fk_contest', $fk_contest)
		               ->doSelect(1, 1);
		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	/**
	 * 根据活动ID获取马拉松活动资料
	 *
	 * @param $ids
	 *
	 * @return array|bool : array
	 * @throws \Exception
	 * @author: zhaodechang@wesai.com
	 */
	public function getMalathionByIds($ids)
	{
		return $this->setTable($this->tableNameMalathion)
		            ->addQueryConditionIn('fk_contest', $ids)
		            ->doSelect(1, count($ids));
	}

	/**
	 * 更新活动资料
	 *
	 * @param $pk_contest
	 * @param $params
	 *
	 * @return bool|mixed
	 */
	public function modify($pk_contest, $params)
	{
		$updateColumns = array();
		foreach ($params['contest_params'] as $key => $val) {
			$updateColumns[] = array($key, $val);
		}

		$affectedRows = $this->setTable($this->tableNameContest)
		                     ->addUpdateColumns($updateColumns)
		                     ->addQueryConditions('pk_contest', $pk_contest)
		                     ->doUpdate();

		return $affectedRows;

	}

	// /**
	//  * 资格审查中
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToReviewing($pk_contest)
	// {
	// 	$lottery = MALATHION_LOTTERY_YES;
	//
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_DRAFT, MALATHION_STATE_REVIEWING, __METHOD__, compact('lottery'));
	// }

	// /**
	//  * 马拉松活动状态变更
	//  *
	//  * @param  integer $pk_contest    活动ID
	//  * @param  integer $from_state    起始状态
	//  * @param  integer $to_state      目标状态
	//  * @param  string  $remark        备注
	//  * @param  array   $extra_columns 额外参数
	//  *
	//  * @return int affect rows
	//  * @throws \Exception
	//  */
	// private function changeMalathionState($pk_contest, $from_state, $to_state, $remark, $extra_columns = null)
	// {
	// 	try {
	// 		$this->beginTransaction();
	//
	// 		// 更新活动表utime
	// 		$query_contest = 'update t_contest set utime = :utime where pk_contest = :pk_contest';
	//
	// 		$params               = array();
	// 		$params['utime']      = null;
	// 		$params['pk_contest'] = $pk_contest;
	// 		$this->update(Pdo_Mysql::DSN_TYPE_MASTER, $query_contest, $params);
	//
	// 		// 更新马拉松状态
	// 		$query_malathion = 'update t_contest_malathion set state = :to_state
	//                            where fk_contest = :fk_contest and state = :from_state';
	//
	// 		$params               = array();
	// 		$params['to_state']   = $to_state;
	// 		$params['from_state'] = $from_state;
	// 		$params['fk_contest'] = $pk_contest;
	//
	// 		$params_log = $params;
	// 		if (!empty($extra_columns) && is_array($extra_columns)) {
	// 			foreach ($extra_columns as $key => $value) {
	// 				if (empty($value)) {
	// 					continue;
	// 				}
	// 				$query_malathion .= ' and ' . $key . ' = :' . $key;
	// 				$params[$key] = $value;
	// 			}
	// 		}
	// 		$affected_rows = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $query_malathion, $params);
	//
	// 		// 写马拉松状态变更日志
	// 		$query_malathion_state_log = 'insert into t_contest_malathion_state_log
	//                                      (fk_contest, from_state, to_state, remark)
	//                                      values (:fk_contest, :from_state, :to_state, :remark)';
	// 		$params_log['remark']      = $remark;
	// 		$this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $query_malathion_state_log, $params_log);
	//
	// 		$this->commit();
	//
	//
	// 		return $affected_rows;
	// 	} catch (Exception $e) {
	// 		$this->rollBack();
	//
	//
	// 		$this->logException($e);
	// 		throw $e;
	// 	}
	// }

	// /**
	//  * 抽签中
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToBalloting($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_REVIEWING, MALATHION_STATE_BALLOTING, __METHOD__);
	// }

	// /**
	//  * 抽签结束
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToBallotCompleted($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_BALLOTING, MALATHION_STATE_BALLOT_COMPLETE, __METHOD__);
	// }

	// /**
	//  * 抽签结束到装备领取中
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateFromBallotCompletedToReceiving($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_BALLOT_COMPLETE, MALATHION_STATE_RECEIVING, __METHOD__);
	// }

	// /**
	//  * 装备领取完成
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToReceiveCompleted($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_RECEIVING, MALATHION_STATE_RECEIVE_COMPLETE, __METHOD__);
	// }

	// /**
	//  * 检录中
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToRollcall($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_RECEIVE_COMPLETE, MALATHION_STATE_ROLL_CALL_START, __METHOD__);
	// }
	//
	// /**
	//  * 检录完成
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToRollcallCompleted($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_ROLL_CALL_START, MALATHION_STATE_ROLL_CALL_END, __METHOD__);
	// }
	//
	// /**
	//  * 竞赛开始
	//  *
	//  **/
	// public function changeMalathionStateToContestStart($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_ROLL_CALL_END, MALATHION_STATE_CONTEST_START, __METHOD__);
	// }
	//
	// /**
	//  * 竞赛结束
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateToContestOver($pk_contest)
	// {
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_CONTEST_START, MALATHION_STATE_CONTEST_OVER, __METHOD__);
	// }
	//
	// /**
	//  * 暂存到装备领取中
	//  *
	//  * @param $pk_contest
	//  *
	//  * @return int
	//  * @throws \Exception
	//  */
	// public function changeMalathionStateFromDraftToReceiving($pk_contest)
	// {
	// 	$lottery = MALATHION_LOTTERY_NO;
	//
	// 	return $this->changeMalathionState($pk_contest, MALATHION_STATE_DRAFT, MALATHION_STATE_RECEIVING, __METHOD__, compact('lottery'));
	// }

	/**
	 * 筛选活动front
	 *
	 * @param          $fk_corp
	 * @param  string  $name      活动名称（中文＋英文）
	 * @param  array   $location  第三级地理位置行政区划tag ID
	 * @param  integer $gtype     竞赛类型
	 * @param  integer $sdate_min 竞赛时间范围起始
	 * @param  integer $sdate_max 竞赛时间范围终止
	 * @param  integer $page      页码
	 * @param  integer $size      页长
	 *
	 * @param null     $template
	 *
	 * @return \stdClass
	 */
	public function searchContestFront($fk_corp, $name = '', $location = array(), $gtype = 0, $sdate_min = 0, $sdate_max = 0, $page = 1, $size = 10, $template = null)
	{
		$config_name = SPHINX_INDEX_CONTEST;
		$sph_config  = $this->config->item('sphinx')[$config_name];
		$this->load->helper('sphinx');
		$sphinxClient = sphinx_init_helper($sph_config);
		$sphinxClient->SetSortMode(SPH_SORT_EXTENDED, 'ctime DESC');
		$sphinxClient->SetLimits(($page - 1) * $size, $size, $sph_config['max_matched']);
		$query = '';

		$sphinxClient->SetFilter('fk_corp', compact('fk_corp'));
		$sphinxClient->SetFilter('publish_state', [CONTEST_PUBLISH_STATE_SELLING, CONTEST_PUBLISH_STATE_ON]);

		if (!empty($name)) {
			$query = '@name ' . $name;
		}
		if (!empty($location)) {
			$sphinxClient->SetFilter('location', $location);
		}
		if (!empty($gtype)) {
			$sphinxClient->SetFilter('gtype', compact('gtype'));
		}
		if ((!empty($sdate_min) || !empty($sdate_max)) && $sdate_min >= 0 && $sdate_max >= 0 && ($sdate_min <= $sdate_max)) {
			$sphinxClient->SetFilterRange('ctime', $sdate_min, $sdate_max);
		}
		if (!empty($template)) {
			$sphinxClient->SetFilter('template', compact('template'));
		}

		$result = $sphinxClient->Query($query, $sph_config['index']);

		$ids    = array();
		if (!empty($result['matches'])) {
			foreach ($result['matches'] as $key => $value) {
				$ids[] = $value['id'];
			}
		}

		$std         = new stdClass();
		$std->total  = intval($result['total']);
		$std->result = $ids;

		return $std;
	}

	/**
	 * 筛选活动manage
	 *
	 * @param  string  $name  活动名称（中文＋英文）
	 * @param  array   $state 马拉松活动状态数组
	 * @param  integer $gtype 竞赛类型
	 * @param  integer $page  页码
	 * @param  integer $size  页长
	 *
	 * @return \stdClass
	 * @throws \Exception
	 */
	public function searchContestManage($fk_corp, $name = '', $state = array(), $gtype = 0, $page = 1, $size = 10, $minDate, $maxDate)
	{
		$config_name = SPHINX_INDEX_CONTEST;
		$sph_config  = $this->config->item('sphinx')[$config_name];
		$this->load->helper('sphinx');
		$sphinxClient = sphinx_init_helper($sph_config);
		$sphinxClient->SetSortMode(SPH_SORT_EXTENDED, 'ctime DESC');
		$sphinxClient->SetLimits(($page - 1) * $size, $size, $sph_config['max_matched']);
		$query = '';

		$sphinxClient->SetFilter('fk_corp', compact('fk_corp'));

		if (!empty($name)) {
			$query = '@name ' . $name;
		}
		if (!empty($state)) {
			$sphinxClient->SetFilter('publish_state', $state);
		}
		if (!empty($gtype)) {
			$sphinxClient->SetFilter('gtype', compact('gtype'));
		}
		if (!empty($minDate) || !empty($maxDate) && $minDate <= $maxDate) {
			$sphinxClient->SetFilterRange('ctime', $minDate, $maxDate);
		}
		$result = $sphinxClient->Query($query, $sph_config['index']);
		$ids    = array();
		if (!empty($result['matches'])) {
			foreach ($result['matches'] as $key => $value) {
				$ids[] = $value['id'];
			}
		}

		$std         = new stdClass();
		$std->total  = intval($result['total']);
		$std->result = $ids;

		return $std;
	}


	public function online($cid)
	{
		return $this->changeState($cid, CONTEST_PUBLISH_STATE_ON, CONTEST_PUBLISH_STATE_DRAFT, __METHOD__);
	}

	private function changeState($contestId, $toState, $fromState, $remark = null)
	{
		try {

			$this->beginTransaction();

			$affectedRows = $this->setTable($this->tableNameContest)
			                     ->addUpdateColumns('publish_state', $toState)
			                     ->addQueryConditions('publish_state', $fromState)
			                     ->addQueryConditions('pk_contest', $contestId)
			                     ->doUpdate();

			if (empty($affectedRows)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			$params = array(
				'fk_contest' => $contestId,
				'from_state' => $toState,
				'to_state'   => $fromState,
				'remark'     => $remark,
			);

			$logId = $this->setTable($this->tableNameContestStateLog)
			              ->addInsertColumns($params)
			              ->doInsert();

			if (empty($logId)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			$this->commit();

			$affectedRows++;

			return $affectedRows;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	public function reOnline($cid)
	{
		return $this->changeState($cid, CONTEST_PUBLISH_STATE_ON, CONTEST_PUBLISH_STATE_OFF, __METHOD__);
	}

	public function startSellingContest($cid)
	{
		return $this->changeState($cid, CONTEST_PUBLISH_STATE_SELLING, CONTEST_PUBLISH_STATE_ON, __METHOD__);
	}

	public function offline($cid, $fromState)
	{
		return $this->changeState($cid, CONTEST_PUBLISH_STATE_OFF, $fromState, __METHOD__);
	}

	public function listByPage($pageNumber = 1, $pageSize = 20, $visible = null, $fk_corp = null)
	{
		$columns = $this->getContestColumns();

		$model = $this->setTable($this->tableNameContest)
		              ->addQueryFields($columns);

		if (!empty($visible)) {
			$model = $model->addQueryConditionIn('publish_state', [CONTEST_PUBLISH_STATE_ON, CONTEST_PUBLISH_STATE_SELLING]);
		}

		if (!empty($fk_corp)) {
			$model = $model->addQueryConditions('fk_corp', $fk_corp);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect($pageNumber, $pageSize);
	}

} // END class

