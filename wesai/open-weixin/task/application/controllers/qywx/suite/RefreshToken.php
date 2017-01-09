<?php
/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:13
 */
require_once __DIR__ . '/SuiteBase.php';

class RefreshToken extends SuiteBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {
				$pkSuite = $this->redis_list_client->RightPop(QYWX_MQ_SUITE_NEED_REFRESH_TOKEN);
				if (empty($pkSuite)) {
					sleep(self::SLEEP_S);
					continue;
				}

				log_message_to_file(
					'qywx_suite_' . __CLASS__,
					array(
						'msg'     => __METHOD__ . ' redis msg',
						'mqTopic' => QYWX_MQ_SUITE_NEED_REFRESH_TOKEN,
						'pkSuite' => $pkSuite,
					)
				);

				$suiteInfo = $this->suite_model->getSuiteByPk($pkSuite);
				if (empty($suiteInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'suite not exists',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'pkSuite' => $pkSuite,
						)
					);
					continue;
				}
				$suiteInfo = $suiteInfo['result'];

				if ($this->verifyTokenRefreshed($suiteInfo['token_expires_at'])) {
					log_message_v2(
						'error',
						array(
							'msg'       => 'token has already refreshed, exit',
							'file'      => __FILE__,
							'line'      => __LINE__,
							'suiteInfo' => $suiteInfo,
						)
					);
					continue;
				}

				if (empty($suiteInfo['suite_secret'])) {
					log_message_v2(
						'error',
						array(
							'msg'       => 'suite secret not set yet',
							'file'      => __FILE__,
							'line'      => __LINE__,
							'suiteInfo' => $suiteInfo,
						)
					);
					continue;
				}

				$suiteToken = $this->weixin_model->getSuiteToken(
					$suiteInfo['suite_id'],
					$suiteInfo['suite_secret'],
					$suiteInfo['suite_ticket']
				);
				if (empty($suiteToken) || !empty($suiteToken['errcode'])) {
					log_message_v2(
						'error',
						array(
							'msg'           => 'get suite_access_token failed',
							'file'          => __FILE__,
							'line'          => __LINE__,
							'suiteInfo'     => $suiteInfo,
							'weixin_result' => $suiteToken,
						)
					);
					continue;
				}

				$suite_access_token = $suiteToken['suite_access_token'];
				$token_expires_at   = date('Y-m-d H:i:s', time() + $suiteToken['expires_in']);
				$suite_id           = $suiteInfo['suite_id'];

				$ret = $this->suite_model->updateBySuiteId(compact('suite_id', 'suite_access_token', 'token_expires_at'));
				if (empty($ret)) {
					log_message_v2(
						'error',
						array(
							'msg'        => 'updateBySuiteId failed',
							'file'       => __FILE__,
							'line'       => __LINE__,
							'api_result' => $ret,
						)
					);
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::SLEEP_S);
			}
		}
	}

	private function verifyTokenRefreshed($tokenExpiresAt)
	{
		if (strtotime($tokenExpiresAt) - time() > 30 * 60) {
			return true;
		}

		return false;
	}
}

