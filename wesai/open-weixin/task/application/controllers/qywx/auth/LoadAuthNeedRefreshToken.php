<?php
require_once __DIR__ . '/AuthBase.php';
/**
 * User: zhaodc
 * Date: 8/6/16
 * Time: 11:03
 */
class LoadAuthNeedRefreshToken extends AuthBase
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
				$authList = $this->auth_model->listAuthNeedRefreshToken(T_CORP_AUTHORIZATION_STATE_ON, $pageNumber, $pageSize);
				if (empty($authList) || empty($authList['data'])) {
					sleep(60);
					$pageNumber = 1;
					continue;
				}

				foreach ($authList['data'] as $v) {
					$ret = $this->redis_list_client->LeftPush(QYWX_MQ_AUTH_NEED_REFRESH_TOKEN, $v['pk_corp_authorization']);
					if (empty($ret)) {
						log_message_v2('error', __METHOD__ . '|set auth data to redis failed| ' . $v['pk_corp_authorization']);
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
