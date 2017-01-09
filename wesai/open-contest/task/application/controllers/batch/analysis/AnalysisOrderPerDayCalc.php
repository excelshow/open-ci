<?php
require_once dirname(__DIR__) . '/BatchBase.php';
/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:37
 */
class AnalysisOrderPerDayCalc extends BatchBase
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

				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ANALYSIS_ORDER_CALC);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				log_message_to_file($this->log_file_name, $msg);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					continue;
				}

				$cursorName = 'order_calc_' . $msg['fk_corp'] . '_' . $msg['date'];

				$analysisCursor = $this->Analysis_model->getAnalysisCursor($cursorName);
				if (!empty($analysisCursor)) {
					continue;
				}

				unset($analysisCursor);

				$orderCalc = $this->Analysis_model->getOrderCalcPerDay($msg['fk_corp'], $msg['date']);
				if (empty($orderCalc)) {
					continue;
				}


				$this->updateOrderCalc($msg['fk_corp'], $msg['date'], $orderCalc['order_count'], $orderCalc['amount_sum']);

				$this->setCursor($cursorName, 1);
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function updateOrderCalc($fkCorp, $date, $orderCount, $amountSum)
	{
		$params = array(
			'order_count' => $orderCount,
			'amount_sum'  => $amountSum,
		);
		$result = $this->Analysis_model->setCalcData($fkCorp, $date, $params);
		if (empty($result)) {
			log_message_v2(
				'error',
				array(
					'msg'    => 'setCalcData failed',
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
