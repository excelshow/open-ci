<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/7/29
 * Time: 17:45
 */
class Analysis extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('contest/Analysis_model');

	}

	public function getAllowedApiList()
	{
		return array();
	}

	public function index()
	{
		$this->verifyLogin();//用户验证
		$pk_corp = $this->userInfo->pk_corp;
		
		$data = array();

		$params    = array(
			'seller_corp_id' => $pk_corp,
		);
		$totalData = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_total.json', $params, METHOD_GET);
		$totalData = $this->obj2array($totalData);
		if (empty($totalData['result'])) {
			$data['total']['count'] = 0;
			$data['total']['amount'] = 0;
		} else {
			$data['total']['count'] = $totalData['result']['count'];
			$data['total']['amount'] = $totalData['result']['amount'];
		}

		$params['start_time'] = date('Y-m-d 00:00:00');
		$params['end_time']   = date('Y-m-d 23:59:59');
		$todayData         = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get_total.json', $params, METHOD_GET);
		$todayData = $this->obj2array($todayData);
		if (empty($todayData['result'])) {
			$data['today']['count'] = 0;
			$data['today']['amount'] = 0;
		} else {
			$data['today']['count'] = $todayData['result']['count'];
			$data['today']['amount'] = $todayData['result']['amount'];
		}
		$this->display($data);

		// $data['analysisTotal'] = array(
		// 	'contest_count' => 0,
		// 	'item_count'    => 0,
		// 	'order_count'   => 0,
		// 	'amount_sum'    => 0,
		// );
		//
		// $data['analysisMonthly'] = array(
		// 	'contest_count' => 0,
		// 	'item_count'    => 0,
		// 	'order_count'   => 0,
		// 	'amount_sum'    => 0,
		// );
		//
		// $data['chatAmountList'] = '';
		// $data['chatDateStart']  = 0;
		// $data['dailyList']      = array();
		//
		// $analysisTotal = $this->Analysis_model->getTotal($pk_corp);
		// if (!empty($analysisTotal->result)) {
		// 	$data['analysisTotal'] = (array)$analysisTotal->result;
		// }
		//
		// $analysisMonthly = $this->Analysis_model->getRecently($pk_corp, $days = 30);
		// if (!empty($analysisMonthly->result)) {
		// 	$data['analysisMonthly'] = (array)$analysisMonthly->result;
		// }
		//
		// $chatAmountList        = array();
		// $data['dailyList']     = array();
		// $data['chatDateStart'] = time() * 1000;
		//
		// $dailyList = $this->Analysis_model->listDaily($pk_corp, 1, 30);
		// if (!empty($dailyList->data)) {
		// 	$preDate = '';
		// 	foreach ($dailyList->data as $analysisDaily) {
		// 		if (!empty($preDate)) {
		// 			$dateContinuityCheck = (strtotime($preDate) - strtotime($analysisDaily->date)) / (24 * 60 * 60);
		// 			if ($dateContinuityCheck > 1) {
		// 				for ($i = 0; $i < $dateContinuityCheck - 1; $i++) {
		// 					array_unshift($chatAmountList, 0.00);
		// 				}
		// 			}
		// 		}
		//
		// 		array_unshift($chatAmountList, sprintf('%.2f', $analysisDaily->amount_sum / 100));
		// 		$preDate = $analysisDaily->date;
		// 	}
		//
		// 	$data['chatDateStart'] = strtotime($dailyList->data[count($dailyList->data) - 1]->date) * 1000 + 24 * 60 * 60 * 1000;
		//
		// 	$data['dailyList'] = $dailyList->data;
		// }
		//
		// if (count($chatAmountList) < 30) {
		// 	$padNumber = 30 - count($chatAmountList);
		// 	for ($i = 0; $i < $padNumber; $i++) {
		// 		array_unshift($chatAmountList, 0.00);
		// 	}
		//
		// 	$data['chatDateStart'] -= $padNumber * 24 * 60 * 60 * 1000;
		// }
		//
		// $data['chatAmountList'] = implode(',', $chatAmountList);
		// $this->display($data);
	}
}
