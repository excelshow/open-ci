<?php
require_once __DIR__ . '/CorpBase.php';

/**
 * User: zhaodc
 * Date: 8/6/16
 * Time: 11:03
 */
class LoadCorpNeedRefreshTicket extends CorpBase
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
				$corpList = $this->corp_model->listCorpNeedRefreshTicket($pageNumber, $pageSize);
				if (empty($corpList['data'])) {
					sleep(60);
					$pageNumber = 1;
					continue;
				}

				foreach ($corpList['data'] as $v) {
					$ret = $this->redis_list_client->LeftPush(QYWX_MQ_CORP_NEED_REFRESH_TICKET, $v['pk_corp']);
					if (empty($ret)) {
						log_message_v2('error', __METHOD__ . '|set corp data to redis failed| ' . $v['pk_corp']);
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
