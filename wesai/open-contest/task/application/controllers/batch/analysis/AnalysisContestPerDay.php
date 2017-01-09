<?php
require_once dirname(__DIR__) . '/BatchBase.php';
/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:37
 */
class AnalysisContestPerDay extends BatchBase
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

				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_ANALYSIS_CONTEST);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}

				log_message_to_file($this->log_file_name, $msg);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					continue;
				}

				$analysisData = $this->Analysis_model->getAnalysisContestByUnqKey($msg['fk_corp'], $msg['date']);
				if (!empty($analysisData)) {
					continue;
				}

				unset($analysisData);

				$count = $this->Analysis_model->getContestCountPerDay($msg['fk_corp'], $msg['date']);
				if (empty($count)) {
					continue;
				}
				$contest_count = $count['count'];

				$result = $this->Analysis_model->setCalcData($msg['fk_corp'], $msg['date'], compact('contest_count'));
				if (empty($result)) {
					log_message_v2(
						'error',
						array(
							'msg'  => 'setCalcData failed',
							'file' => __FILE__,
							'line' => __LINE__,
						)
					);
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}
}
