<?php
require_once dirname(__DIR__) . '/BatchBase.php';
/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:37
 */
class AnalysisContestItemPerDayCalc extends BatchBase
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

				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ANALYSIS_CONTEST_ITEM_CALC);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				log_message_to_file($this->log_file_name, $msg);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					continue;
				}

				$cursorName = 'item_calc_' . $msg['fk_corp'] . '_' . $msg['date'];

				$analysisCursor = $this->Analysis_model->getAnalysisCursor($cursorName);
				if (!empty($analysisCursor)) {
					continue;
				}

				unset($analysisCursor);

				$itemCalc = $this->Analysis_model->getContestItemCalcPerDay($msg['fk_corp'], $msg['date']);
				if (empty($itemCalc)) {
					continue;
				}


				$this->updateItemCalc($msg['fk_corp'], $msg['date'], $itemCalc['item_count']);

				$this->setCursor($cursorName, 1);
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function updateItemCalc($fkCorp, $date, $itemCount)
	{
		$params = array(
			'item_count' => $itemCount,
		);
		$result = $this->Analysis_model->setCalcData($fkCorp, $date, $params);
		if (empty($result)) {
			log_message_v2(
				'error',
				array(
					'msg'    => 'updateCalcData failed',
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
