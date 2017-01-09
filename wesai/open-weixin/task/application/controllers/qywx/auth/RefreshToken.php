<?php
/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 上午10:50
 */
require_once __DIR__ . '/AuthBase.php';

class RefreshToken extends AuthBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {
				$authId = $this->redis_list_client->RightPop(QYWX_MQ_AUTH_NEED_REFRESH_TOKEN);
				if (empty($authId)) {
					sleep(self::SLEEP_S);
					continue;
				}

				log_message_to_file(
					'qywx_auth_' . __CLASS__,
					array(
						'msg'     => __METHOD__ . ' redis msg',
						'mqTopic' => QYWX_MQ_AUTH_NEED_REFRESH_TOKEN,
						'authId'  => $authId,
					)
				);

				$authInfo = $this->auth_model->getByPk($authId);
				if (empty($authInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'    => 'corp authorization not exists',
							'authId' => $authId,
						)
					);
					continue;
				}
				$authInfo = $authInfo['result'];

				if ($this->verifyTokenRefreshed($authInfo['token_expires_at'])) {
					log_message_v2(
						'error',
						array(
							'msg'      => 'token has already refreshed, exit',
							'file'     => __FILE__,
							'line'     => __LINE__,
							'authInfo' => $authInfo,
						)
					);
					continue;
				}

				$suiteInfo = $this->suite_model->getSuiteByPk($authInfo['fk_suite']);
				if (empty($suiteInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'     => 'suite not exists',
							'file'    => __FILE__,
							'line'    => __LINE__,
							'pkSuite' => $authInfo['fk_suite'],
						)
					);
					continue;
				}
				$suiteInfo = $suiteInfo['result'];

				$corpInfo = $this->corp_model->getCorpByPk($authInfo['fk_corp']);
				if (empty($corpInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'    => 'corp not exists',
							'file'   => __FILE__,
							'line'   => __LINE__,
							'pkCorp' => $authInfo['fk_corp'],
						)
					);
					continue;
				}
				$corpInfo = $corpInfo['result'];

				$accessToken = $this->weixin_model->getCorpToken(
					$suiteInfo['suite_access_token'],
					$suiteInfo['suite_id'],
					$corpInfo['corp_id'],
					$authInfo['permanent_code']
				);
				if (empty($accessToken)) {
					log_message_v2(
						'error',
						array(
							'msg'              => 'getCorpToken failed',
							'file'             => __FILE__,
							'line'             => __LINE__,
							'suiteAccessToken' => $suiteInfo['suite_access_token'],
							'suiteId'          => $suiteInfo['suite_id'],
							'corpId'           => $corpInfo['corp_id'],
							'permanentCode'    => $authInfo['permanent_code'],
						)
					);
				}

				$token_expires_at = date('Y-m-d H:i:s', time() + $accessToken['expires_in']);
				$access_token     = $accessToken['access_token'];

				$ret = $this->auth_model->updateAuthorization($authId, compact('token_expires_at', 'access_token'));
				if (empty($ret)) {
					log_message_v2(
						'error',
						array(
							'msg'              => 'updateAuthorization failed',
							'file'             => __FILE__,
							'line'             => __LINE__,
							'token_expires_at' => $token_expires_at,
							'access_token'     => $access_token,
							'authId'           => $authId,
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
