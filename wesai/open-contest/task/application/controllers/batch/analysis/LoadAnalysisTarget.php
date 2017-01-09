<?php
require_once dirname(__DIR__) . '/BatchBase.php';

/**
 * User: zhaodc
 * Date: 8/10/16
 * Time: 11:37
 */
class LoadAnalysisTarget extends BatchBase
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('qywx/Corp_model');
	}

	public function index($debug = null)
	{
		$pageNumber = 1;
		$pageSize   = 100;
		while (true) {
			try {
				$corpList = $this->Corp_model->listCorp($pageNumber, $pageSize);
				if (empty($corpList) || empty($corpList['data'])) {
					exit();
				}

				$date = date('Y-m-d', strtotime('-1 days'));

				if ($debug) {
					while ($date > '2016-06-01') {
						$date = date('Y-m-d', strtotime($date) - 24 * 60 * 60);
						var_dump($date);
						foreach ($corpList['data'] as $value) {
							$fk_corp = $value['pk_corp'];
							$this->sendDataToRedis(compact('fk_corp', 'date'));
						}
					}
				} else {

					foreach ($corpList['data'] as $value) {
						$fk_corp = $value['pk_corp'];
						$this->sendDataToRedis(compact('fk_corp', 'date'));
					}
				}

				$pageNumber++;
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function sendDataToRedis($data)
	{
		$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_ANALYSIS_CONTEST, json_encode($data));
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'  => __METHOD__ . ' set data to MQ_TOPIC_ANALYSIS_CONTEST failed',
					'file' => __FILE__,
					'line' => __LINE__,
					'data' => compact('fk_corp', 'date'),
				)
			);
		}

		$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_ANALYSIS_ORDER, json_encode($data));
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'  => __METHOD__ . ' set data to MQ_TOPIC_ANALYSIS_ORDER failed',
					'file' => __FILE__,
					'line' => __LINE__,
					'data' => compact('fk_corp', 'date'),
				)
			);
		}

		$ret = $this->redis_list_client->LeftPush(MQ_TOPIC_ANALYSIS_CONTEST_ITEM, json_encode($data));
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'  => __METHOD__ . ' set data to MQ_TOPIC_ANALYSIS_CONTEST_ITEM failed',
					'file' => __FILE__,
					'line' => __LINE__,
					'data' => compact('fk_corp', 'date'),
				)
			);
		}
	}
}
