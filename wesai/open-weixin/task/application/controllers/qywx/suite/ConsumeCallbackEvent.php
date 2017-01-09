<?php
/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:13
 */
require_once __DIR__ . '/SuiteBase.php';

class ConsumeCallbackEvent extends SuiteBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {

				$msg = $this->redis_list_client->RightPop(QYWX_MQ_CORP_SUITE_EVENT);
				if (empty($msg)) {
					sleep(self::SLEEP_S);
					continue;
				}

				log_message_to_file('qywx_suite_' . __CLASS__, 'suite wxmsg: ' . $msg);

				$msg = json_decode($msg, true);
				if (empty($msg) || empty($msg['InfoType'])) {
					$errMsg = array(
						'msg'   => 'suite wxmsg not in json type',
						'wxmsg' => $msg,
					);
					log_message_v2('error', $errMsg);
					continue;
				}

				$ret = null;
				switch (strtolower($msg['InfoType'])) {
					case 'suite_ticket':
						$ret = $this->suite_model->createSuite($msg['SuiteId'], $msg['TimeStamp'], $msg['SuiteTicket']);
						if (empty($ret)) {
							continue;
						}
						break;
					case 'change_auth':
						$suiteInfo = $this->suite_model->getSuite($msg['SuiteId']);
						if (empty($suiteInfo['result'])) {
							log_message_v2('error', $msg['InfoType'] . ' failed | ' . json_encode($suiteInfo));
							continue;
						}
						$suiteInfo = $suiteInfo['result'];

						if (empty($suiteInfo['suite_access_token'])) {
							log_message_v2('error', 'suite_access_token empty');
							continue;
						}

						$authInfo = $this->auth_model->getAuthorization($msg['SuiteId'], $msg['AuthCorpId']);
						if (empty($authInfo['result'])) {
							log_message_v2('error', 'get authorization failed');
							continue;
						}
						$authInfo = $authInfo['result'];

						$newAuthInfo = $this->weixin_model->getAuthInfo(
							$suiteInfo['suite_access_token'],
							$authInfo['permanent_code'],
							$msg['SuiteId'],
							$msg['AuthCorpId']
						);

						$ret = $this->auth_model->createAuthorization($msg['SuiteId'], $newAuthInfo);
						if (empty($ret)) {
							continue;
						}
						break;
					case
					'cancel_auth':
						$ret = $this->auth_model->cancelAuthorization($msg['SuiteId'], $msg['AuthCorpId']);
						if (empty($ret)) {
							continue;
						}
						break;
					case 'create_auth':
						$suiteInfo = $this->suite_model->getSuite($msg['SuiteId']);
						if (empty($suiteInfo['result'])) {
							$errMsg = array(
								'msg'       => 'get suite info failed',
								'suiteInfo' => $suiteInfo,
							);
							log_message_v2('error', $errMsg);
							continue;
						}
						$suiteInfo = $suiteInfo['result'];

						if (empty($suiteInfo['suite_access_token'])) {
							$errMsg = array(
								'msg'       => 'suite access token empty',
								'suiteInfo' => $suiteInfo,
							);
							log_message_v2('error', $errMsg);
							continue;
						}

						$authInfo = $this->weixin_model->getPermanentCode($suiteInfo['suite_access_token'], $msg['SuiteId'], $msg['AuthCode']);
						if (empty($authInfo) || !empty($authInfo["errcode"])) {
							$errMsg = array(
								'msg'      => 'get permanent code failed',
								'authInfo' => $authInfo,
							);
							log_message_v2('error', $errMsg);
							continue;
						}

						$ret = $this->auth_model->createAuthorization($msg['SuiteId'], json_encode($authInfo));
						if (empty($ret)) {
							continue;
						}
						break;
				}

			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::SLEEP_S);
			}
		}
	}
}

