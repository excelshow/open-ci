<?php
/**
 * User: zhaodc
 * Date: 16/6/22
 * Time: 下午4:13
 */
require_once __DIR__ . '/ProviderBase.php';

class RefreshToken extends ProviderBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {
				$providerInfo = $this->provider_model->getProvider();
				if (empty($providerInfo['result'])) {
					sleep(self::SLEEP_S);
					continue;
				}
				$providerInfo = $providerInfo['result'];

				$i_token_expires_at = strtotime($providerInfo['token_expires_at']);
				if (time() + self::TIME_LIMIT * 60 < $i_token_expires_at) {
					$sleepSec = $i_token_expires_at - time() - self::TIME_LIMIT * 60;
					log_message_to_file('qywx_provider_' . __CLASS__, __METHOD__ . 'sleep ' . $sleepSec . 's');
					sleep($sleepSec);
					continue;
				}

				$corpId              = $providerInfo['provider_id'];
				$secret              = $providerInfo['provider_secret'];
				$providerAccessToken = $this->weixin_model->getProviderAccessToken($corpId, $secret);
				if (empty($providerAccessToken)) {
					sleep(self::SLEEP_S);
					continue;
				}

				$accessToken = $providerAccessToken['provider_access_token'];
				$expiresAt   = date('Y-m-d H:i:s', time() + $providerAccessToken['expires_in']);
				$ret         = $this->provider_model->updateProvider($corpId, $accessToken, $expiresAt);
				if (empty($ret)) {
					sleep(self::SLEEP_S);
					continue;
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::SLEEP_S);
			}
		}
	}
}

