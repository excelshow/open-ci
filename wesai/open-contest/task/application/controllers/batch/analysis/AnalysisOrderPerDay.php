<?php
require_once dirname(__DIR__) . '/BatchBase.php';
/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:37
 */
class AnalysisOrderPerDay extends BatchBase
{
	private $log_file_name = '';
	public function __construct()
	{
		parent::__construct();
		$this->load->model('analysis/Analysis_model');
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));;
	}

	public function index()
	{
		while (true) {
			try {

				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ANALYSIS_ORDER);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				log_message_to_file($this->log_file_name, $msg);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					continue;
				}

				$cursorName = 'order_' . $msg['fk_corp'] . '_' . $msg['date'];

				$analysisCursor = $this->Analysis_model->getAnalysisCursor($cursorName);
				if (!empty($analysisCursor)) {
					continue;
				}

				unset($analysisCursor);

				$pageNumber = 1;
				$pageSize   = 100;

				$result = array();
				while (true) {
					$orderList = $this->Analysis_model->listOrderPerDay($msg['fk_corp'], $msg['date'], $pageNumber, $pageSize);
					if (empty($orderList['data'])) {
						break;
					}

					foreach ($orderList['data'] as $value) {
						if (!array_key_exists($value['fk_contest'], $result) ||
						    !array_key_exists($value['fk_contest_items'], $result[$value['fk_contest']])
						) {
							$result[$value['fk_contest']][$value['fk_contest_items']]['order_count'] = 1;
							$result[$value['fk_contest']][$value['fk_contest_items']]['amount_sum']  = $value['amount'];
							continue;
						}

						$result[$value['fk_contest']][$value['fk_contest_items']]['order_count'] += 1;
						$result[$value['fk_contest']][$value['fk_contest_items']]['amount_sum'] += $value['amount'];
					}

					unset($orderList);
					$pageNumber++;
				}

				if (empty($result)) {
					$this->setCursor($cursorName, 1);
					continue;
				}

				foreach ($result as $fkContest => $value) {
					foreach ($value as $fkContestItems => $v) {
						$this->setOrderCount($msg['fk_corp'], $msg['date'], $fkContest, $fkContestItems, $v['order_count'], $v['amount_sum']);
					}
				}

				unset($result);

				$this->setCursor($cursorName, 1);

				$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_ANALYSIS_ORDER_CALC, json_encode($msg));
				if (empty($ret)) {
					log_message_v2(
						'error',
						array(
							'msg'  => 'set data to MQ_TOPIC_ANALYSIS_ORDER_CALC failed',
							'file' => __FILE__,
							'line' => __LINE__,
							'data' => $msg,
						)
					);
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function setOrderCount($fkCorp, $date, $fkContest, $fkContestItems, $orderCount, $amountSum)
	{
		$result = $this->Analysis_model->setOrderCount($fkCorp, $date, $fkContest, $fkContestItems, $orderCount, $amountSum);
		if (empty($result)) {
			log_message_v2(
				'error',
				array(
					'msg'    => 'setOrderCount failed',
					'file'   => __FILE__,
					'line'   => __LINE__,
					'result' => $result,
				)
			);
		}
	}

	private function setCursor($name, $value)
	{
		$ret = $this->Analysis_model->setAnalysisCursor($name, $value);
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'    => 'setAnalysisCursor failed',
					'file'   => __FILE__,
					'line'   => __LINE__,
					'result' => $ret,
				)
			);
		}
	}
}
