<?php
require_once __DIR__ . '/SuiteBase.php';
/**
 * User: zhaodc
 * Date: 8/6/16
 * Time: 11:03
 */
class LoadSuiteNeedRefreshToken extends SuiteBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$pageNumber = 1;
		$pageSize   = 100;

		while (true) {
			try {
				$suiteList = $this->suite_model->listSuiteNeedRefreshToken($pageNumber, $pageSize);
				if (empty($suiteList['data'])) {
					sleep(60);
					$pageNumber = 1;
					continue;
				}

				foreach ($suiteList['data'] as $v) {
					$ret = $this->redis_list_client->LeftPush(QYWX_MQ_SUITE_NEED_REFRESH_TOKEN, $v['pk_suite']);
					if (empty($ret)) {
						log_message_v2('error', __METHOD__ . '|set suite data to redis failed| ' . $v['pk_suite']);
					}
				}
				$pageNumber++;
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::SLEEP_S);
			}
		}
	}
}
