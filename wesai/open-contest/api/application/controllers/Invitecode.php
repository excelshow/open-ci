<?php
/**
 * User: zhaodc
 * Date: 25/09/2016
 * Time: 13:45
 */
require __DIR__ . '/Base.php';

class Invitecode extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function add_post()
	{
		$itemId = $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY);
		$count  = $this->post_check('count', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

		$count < 100000 or $count = 100000;

		$itemInfo = $this->verifyContestItemExists($itemId);

		$this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

		$createdCount = 0;
		for ($j = 0; $j < $count; $j++) {
			for ($i = 0; $i < 3; $i++) {
				$code = $this->generateCode();
				try {
					$result = $this->InviteCode_model->create($itemId, $code);
					if (empty($result)) {
						continue;
					}
				} catch (Exception $e) {
					$this->catchException($e);
				}
				$createdCount++;
				break;
			}
		}

		$count = $createdCount;

		return $this->response_object(compact('count'));
	}

	private function generateCode($length = 6)
	{
		$str    = '0123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
		$slen   = strlen($str) - 1;
		$result = '';
		for ($i = 0; $i < $length; $i++) {
			mt_srand();

			$key = mt_rand(0, $slen);

			$result .= $str[$key];
		}

		return $result;
	}

	public function clear_post()
	{
		$itemId = $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY);

		$itemInfo = $this->verifyContestItemExists($itemId);

		if ($itemInfo['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_NO) {
			$affectedRows = $this->InviteCode_model->expireAll($itemId);

			return $this->response_update($affectedRows);
		}

		$maxInviteCode = 0;
		switch ($itemInfo['type']) {
			case CONTEST_ITEM_TYPE_SINGLE:
				$maxInviteCode = $itemInfo['max_stock'];
				break;
			case CONTEST_ITEM_TYPE_TEAM:
				$maxInviteCode = $itemInfo['team_size'] * $itemInfo['team_max_stock'];
				break;
		}

		// 取最大库存的后一条
		$total    = 0;
		$lastCode = $this->InviteCode_model->listByPage($itemId, $maxInviteCode, 1, $total);
		if (empty($lastCode)) {
			return $this->response_update(0);
		}

		$lastCodeId   = $lastCode[0]['pk_enrol_invite_code'];
		$affectedRows = $this->InviteCode_model->expireExt($itemId, $lastCodeId);

		return $this->response_update($affectedRows);
	}

	public function list_get()
	{
		$itemId     = $this->get_check('item_id', PARAM_NOT_NULL_NOT_EMPTY);
		$pageNumber = $this->get_check('page', PARAM_NULL_EMPTY);
		$pageSize   = $this->get_check('size', PARAM_NULL_EMPTY);

		$this->checkPageParams($pageNumber, $pageSize, $maxSize = 1000);

		$itemInfo = $this->verifyContestItemExists($itemId);

		$this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

		$total    = 0;
		$codeList = $this->InviteCode_model->listByPage($itemId, $pageNumber, $pageSize, $total);

		return $this->response_list($codeList, $total, $pageNumber, $pageSize);
	}
}
