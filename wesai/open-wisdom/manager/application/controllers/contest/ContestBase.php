<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../Applogin.php';

abstract class ContestBase extends Applogin
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getHostName() {
		return API_HOST_OPEN_CONTEST;
	}

	protected function errorInfo($info, $code = -1)
	{
		exit(json_encode(array('error' => $code, 'info' => $info)));
	}

	protected function verifyContestEdit($cid, $isItemEdit = false)
	{
		$std         = new stdClass();
		$std->error  = 0;
		$contestInfo = $this->Contest_model->getContestById($cid, 1);
		if (empty($contestInfo) || $contestInfo->error != 0 || empty($contestInfo->result)) {
			$std->error = -1;

			return $std;
		}

		if ($contestInfo->result->fk_corp != $this->userInfo->pk_corp) {
			$std->error = -2;

			return $std;
		}

		if ($isItemEdit && in_array($contestInfo->result->publish_state, [CONTEST_PUBLISH_STATE_SELLING])) {
			$std->error = -3;

			return $std;
		}

		$std->contestInfo = $contestInfo;

		return $std;
	}

	protected function verifyContestItemEdit($itemId, $isItemEdit = false)
	{
		$std        = new stdClass();
		$std->error = 0;

		$itemInfo = $this->Contest_model->getContestItemById($itemId);
		if (empty($itemInfo) || $itemInfo->error != 0 || empty($itemInfo->result)) {
			$std->error = -4;

			return $std;
		}

		if ($itemInfo->result->state != CONTEST_ITEM_STATE_OK) {
			$std->error = -5;

			return $std;
		}

		$contestEditVerify = $this->verifyContestEdit($itemInfo->result->fk_contest, $isItemEdit);
		if ($contestEditVerify->error < 0) {
			return $contestEditVerify;
		}

		$contestEditVerify->itemInfo = $itemInfo;

		return $contestEditVerify;
	}

	protected function verifyFormEdit($formId, $isItemEdit = false)
	{
		$std        = new stdClass();
		$std->error = 0;

		$formInfo = $this->Formorder_model->getFormById($formId);
		if (empty($formInfo) || $formInfo->error != 0 || empty($formInfo->result)) {
			$std->error = -5;

			return $std;
		}

		$contestItemEditVerify = $this->verifyContestItemEdit($formInfo->result->fk_contest_items, $isItemEdit);
		if ($contestItemEditVerify->error < 0) {
			return $contestItemEditVerify;
		}

		$contestItemEditVerify->formInfo = $formInfo;

		return $contestItemEditVerify;
	}

	protected function verifyFormItemEdit($formItemId)
	{
		$std        = new stdClass();
		$std->error = 0;

		$formItemInfo = $this->Formorder_model->getFormItemById($formItemId);
		if (empty($formItemId) || $formItemInfo->error != 0 || empty($formItemInfo->result)) {
			$std->error = -6;

			return $std;
		}

		if ($formItemInfo->result->state != ENROL_FORM_ITEM_STATE_OK) {
			$std->error = -7;

			return $std;
		}

		$formEditVerify = $this->verifyFormEdit($formItemInfo->result->fk_enrol_form, true);
		if ($formEditVerify->error < 0) {
			return $formEditVerify;
		}

		$formEditVerify->formItemInfo = $formItemInfo;

		return $formEditVerify;
	}

	protected function verifyOrder($oid)
	{
		$std        = new stdClass();
		$std->error = 0;

		$orderInfo = $this->Order_model->getOrderById($oid);
		if (empty($orderInfo) || $orderInfo->error != 0 || empty($orderInfo->result)) {
			$std->error = -8;

			return $std;
		}

		if ($orderInfo->result->fk_corp != $this->userInfo->pk_corp) {
			$std->error = -9;

			return $std;
		}

		$std->orderInfo = $orderInfo;

		return $std;
	}

	protected function obj2array($data)
	{
		return json_decode(json_encode($data), true);
	}
}
