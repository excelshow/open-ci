<?php
/**
 * User: zhaodc
 * Date: 27/09/2016
 * Time: 10:19
 */

require_once __DIR__ . '/ModelBase.php';

class EnrolData_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	private function createEnrolData($enrolData)
	{
		// 创建报名资料
		$enrolData['utime'] = null;

		return $this->setTable($this->tableNameEnrolData)
		            ->addInsertColumns($enrolData, ['utime'])
		            ->doInsert();
	}

	private function createTeamUserMapping($teamId, $uid)
	{
		$this->load->model('Team_model');
		$mappingInfo = $this->Team_model->getMappingByUnqKey($teamId, $uid, $this);
		if (empty($mappingInfo)) {
			$mappingId = $this->Team_model->createMapping($teamId, $uid, $this);
		} else {
			if ($mappingInfo['state'] != MAPPING_STATE_OK) {
				$this->Team_model->updateMappingState($teamId, $uid, MAPPING_STATE_NG, MAPPING_STATE_OK, $this);
			}
			$mappingId = $mappingInfo['pk_mapping_team_user'];
		}

		return $mappingId;
	}

	private function createGroupUserMapping($groupId, $uid)
	{
		$this->load->model('Group_model');
		$mappingInfo = $this->Group_model->getMappingByUnqKey($groupId, $uid, $this);
		if (empty($mappingInfo)) {
			$mappingId = $this->Group_model->createMapping($groupId, $uid, $this);
		} else {
			if ($mappingInfo['state'] != MAPPING_STATE_OK) {
				$this->Group_model->updateMappingState($groupId, $uid, MAPPING_STATE_NG, MAPPING_STATE_OK, $this, $this);
			}
			$mappingId = $mappingInfo['pk_mapping_group_user'];
		}

		return $mappingId;
	}

	private function createEnrolDataDetail($enrolDataId, $enrolDataDetail)
	{
		// 写入报名详情
		$this->load->model('EnrolDataDetail_model');

		foreach ($enrolDataDetail as $value) {
			$value['fk_enrol_data'] = $enrolDataId;
			$detailId               = $this->EnrolDataDetail_model->create($value, $this);
			if (empty($detailId)) {
				return false;
			}
		}

		return true;
	}

	public function create($enrolData, $enrolDataDetail, $inviteCode = null)
	{
		try {
			$this->beginTransaction();

			$enrolDataId = $this->createEnrolData($enrolData);

			if (empty($enrolDataId)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			// 如果是小组／团队报名，关联用户对应小组／团队
			switch ($enrolData['type']) {
				case ENROL_DATA_TYPE_TEAM:
					$mappingId = $this->createTeamUserMapping($enrolData['fk_team'], $enrolData['fk_user']);
					if (empty($mappingId)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}

					$this->load->model('Team_model');
					$affectedRows = $this->Team_model->increaseCurMemberCount($enrolData['fk_team'], $this);

					if (empty($affectedRows)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}
					break;
				case ENROL_DATA_TYPE_GROUP:
					$mappingId = $this->createGroupUserMapping($enrolData['fk_group'], $enrolData['fk_user']);
					if (empty($mappingId)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}

					$this->load->model('Group_model');
					$affectedRows = $this->Group_model->increaseCurMemberCount($enrolData['fk_group'], $this);

					if (empty($affectedRows)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}
					break;
			}

			$result = $this->createEnrolDataDetail($enrolDataId, $enrolDataDetail);
			if (false === $result) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}


			// 如果有邀请码，消费邀请码
			if (!empty($inviteCode)) {
				$this->load->model('InviteCode_model');
				$affectedRows = $this->InviteCode_model->consume($enrolData['fk_contest_items'], $inviteCode, $enrolDataId, $this);
				if (empty($affectedRows)) {
					$this->rollBack();
					log_message_v2('error');

					return false;
				}
			}

			$this->commit();

			return $enrolDataId;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	public function getById($enrolDataId)
	{
		$result = $this->setTable($this->tableNameEnrolData)
		               ->addQueryConditions('pk_enrol_data', $enrolDataId)
		               ->doSelect(1, 1);
		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function getByIds($enrolDataIds)
	{
		return $this->setTable($this->tableNameEnrolData)
		            ->addQueryConditionIn('pk_enrol_data', $enrolDataIds)
		            ->addQueryConditions('state', ENROL_DATA_STATE_OK)
		            ->doSelect(1, count($enrolDataIds));
	}

	public function setVerifyCode($orderId, $enrolDataId, $maxVerify, $copies, $model = null)
	{
		$count = 0;
		for ($i = 0; $i < $copies; $i++) {
			$verifyCode = $this->generateVerifyCode();
			$params     = array(
				'fk_order'      => $orderId,
				'fk_enrol_data' => $enrolDataId,
				'code'          => $verifyCode,
				'max_verify'    => $maxVerify,
			);

			$codeId = $this->getInstance($model)
			               ->setTable($this->tableNameVerifyCode)
			               ->addInsertColumns($params)
			               ->doInsert();
			if (!empty($codeId)) {
				$count++;
			}
		}

		return $count;
	}

	public function getByCode($code)
	{
		$verifyCode = $this->setTable($this->tableNameVerifyCode)
		                   ->addQueryConditions('code', $code)
		                   ->doSelect(1, 1);
		if (empty($verifyCode)) {
			return false;
		}

		$enrolData = $this->getById($verifyCode[0]['fk_enrol_data']);

		if (empty($enrolData)) {
			return false;
		}

		$enrolData['verify_code'] = $verifyCode;

		return $enrolData;
	}

	public function remove($enrolDataId, $enrolDataOwner, $type, $groupId, $teamId)
	{
		try {
			$this->beginTransaction();

			$affectedRows = $this->setTable($this->tableNameEnrolData)
			                     ->addUpdateColumns('state', ENROL_DATA_STATE_NG)
			                     ->addQueryConditions('state', ENROL_DATA_STATE_OK)
			                     ->addQueryConditions('pk_enrol_data', $enrolDataId)
			                     ->doUpdate();

			if (empty($affectedRows)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			switch ($type) {
				case ENROL_DATA_TYPE_GROUP:
					$this->load->model('Group_model');
					$affectedRows = $this->Group_model->reduceCurMemberCount($groupId, $this);

					if (empty($affectedRows)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}

					$enrolDataList = $this->listByGroupUser($groupId, 1, 1, $enrolDataOwner, ENROL_DATA_STATE_OK);

					if (empty($enrolDataList)) {
						$this->Group_model->updateMappingState($groupId, $enrolDataOwner, MAPPING_STATE_OK, MAPPING_STATE_NG, $this);
					}
					break;
				case ENROL_DATA_TYPE_TEAM:
					$this->load->model('Team_model');
					$affectedRows = $this->Team_model->reduceCurMemberCount($teamId, $this);

					if (empty($affectedRows)) {
						$this->rollBack();
						log_message_v2('error');

						return false;
					}

					$enrolDataList = $this->listByTeamUser($teamId, 1, 1, $enrolDataOwner, ENROL_DATA_STATE_OK);

					if (empty($enrolDataList)) {
						$this->Team_model->updateMappingState($teamId, $enrolDataOwner, MAPPING_STATE_OK, MAPPING_STATE_NG, $this);
					}
					break;
			}

			$this->commit();

			return $affectedRows;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	public function listByTeamUser($teamId, $pageNumber, $pageSize, $uid = null, $state = null)
	{
		$model = $this->setTable($this->tableNameEnrolData)
		              ->addQueryConditions('fk_team', $teamId);

		if (!empty($uid)) {
			$model = $model->addQueryConditions('fk_user', $uid);
		}

		if (!empty($state)) {
			$model = $model->addQueryConditions('state', $state);
		}

		return $model->addOrderBy('ctime', 'desc')->doSelect($pageNumber, $pageSize);
	}


	public function listByTeamIds($teamIds, $state = null)
	{
		$model = $this->setTable($this->tableNameEnrolData)
		              ->addQueryConditionIn('fk_team', $teamIds);

		if (!empty($state)) {
			$model = $model->addQueryConditions('state', $state);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect();
	}


	public function listByGroupUser($groupId, $pageNumber, $pageSize, $uid = null, $state = null)
	{
		$model = $this->setTable($this->tableNameEnrolData)
		              ->addQueryConditions('fk_group', $groupId);


		if (!empty($uid)) {
			$model = $model->addQueryConditions('fk_user', $uid);
		}
		if (!empty($state)) {
			$model->addQueryConditions('state', $state);
		}

		return $model->addOrderBy('ctime', 'desc')
		             ->doSelect($pageNumber, $pageSize);
	}

	public function listByGroupIds($groupId, $state = null)
	{
		$model = $this->setTable($this->tableNameEnrolData)
			->addQueryConditionIn('fk_group', $groupId);

		if (!empty($state)) {
			$model->addQueryConditions('state', $state);
		}

		return $model->addOrderBy('ctime', 'desc')
			->doSelect(0, 0);
	}




	public function verify($verifyCodeInfo, $corpUid)
	{
		try {
			$this->beginTransaction();

			$affectedRows = $this->increaseVerifiedNumber($verifyCodeInfo['code']);
			if (empty($affectedRows)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			$logId = $this->writeVerifyLog($verifyCodeInfo['pk_verify_code'], $verifyCodeInfo['verify_number'], $verifyCodeInfo['verify_number'] + 1, $corpUid);
			if (empty($logId)) {
				$this->rollBack();
				log_message_v2('error');

				return false;
			}

			$this->commit();

			return $affectedRows;
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	private function increaseVerifiedNumber($code)
	{
		return $this->setTable($this->tableNameVerifyCode)
		            ->addUpdateColumns('verify_number', 1, '+')
		            ->addQueryConditions('code', $code)
		            ->doUpdate();
	}

	private function writeVerifyLog($code, $fromNumber, $toNumber, $corpUid)
	{
		$params = array(
			'fk_verify_code' => $code,
			'from_number'    => $fromNumber,
			'to_number'      => $toNumber,
			'fk_corp_user'   => $corpUid,
		);

		return $this->setTable($this->tableNameVerifyCodeVerifyLog)
		            ->addInsertColumns($params, ['from_number'])
		            ->doInsert();
	}

	public function listVerifyCodeByEnrolDataIds($enrolDataIds)
	{
		return $this->setTable($this->tableNameVerifyCode)
		            ->addQueryConditionIn('fk_enrol_data', $enrolDataIds)
		            ->doSelect();
	}

	public function listVerifyInfoByOrderIds($orderIds)
	{
		return $this->setTable($this->tableNameVerifyCode)
		            ->addQueryFields('fk_order')
		            ->addQueryFieldCalc('sum', 'max_verify')
		            ->addQueryFieldCalc('sum', 'verify_number')
		            ->addQueryConditionIn('fk_order', $orderIds)
		            ->addQueryConditions('verify_number', 'max_verify', '<', true)
		            ->addGroupBy('fk_order')
		            ->doSelect();
	}
}
