<?php
/**
 * User: zhaodc
 * Date: 16/6/24
 * Time: 上午10:50
 */
require_once __DIR__ . '/CorpBase.php';

class RefreshTicket extends CorpBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		while (true) {
			try {
				$pkCorp = $this->redis_list_client->RightPop(QYWX_MQ_CORP_NEED_REFRESH_TICKET);
				if (empty($pkCorp)) {
					sleep(self::SLEEP_S);
					continue;
				}

				$corpInfo = $this->corp_model->getCorpByPk($pkCorp);
				if (empty($corpInfo['result'])) {
					log_message_v2(
						'error',
						array(
							'msg'    => 'corp not exists',
							'file'   => __FILE__,
							'line'   => __LINE__,
							'pkCorp' => $pkCorp,
						)
					);
					continue;
				}
				$corpInfo = $corpInfo['result'];

				if ($this->verifyTicketRefreshed($corpInfo['ticket_expires_at'])) {
					log_message_v2(
						'error',
						array(
							'msg'      => 'ticket has already refreshed, exit',
							'file'     => __FILE__,
							'line'     => __LINE__,
							'corpInfo' => $corpInfo,
						)
					);
					continue;
				}

				$authList = $this->auth_model->listAuthByCorp($pkCorp, 1, 1);
				if (empty($authList['data'])) {
					log_message_v2(
						'error',
						array(
							'msg'    => 'listAuthByCorp failed',
							'file'   => __FILE__,
							'line'   => __LINE__,
							'pkCorp' => $pkCorp,
						)
					);
					continue;
				}

				$authInfo = $authList['data'][0];
				if (empty($authInfo['access_token']) || strtotime($authInfo['token_expires_at']) < time()) {
					log_message_v2(
						'error',
						array(
							'msg'    => 'invalid access_token',
							'file'   => __FILE__,
							'line'   => __LINE__,
							'pkCorp' => $pkCorp,
						)
					);
					continue;
				}

				$jsApiTicket = $this->weixin_model->getJsApiTicket($authInfo['access_token']);

				if (empty($jsApiTicket)) {
					log_message_v2(
						'error',
						array(
							'msg'         => 'getJsApiTicket failed',
							'file'        => __FILE__,
							'line'        => __LINE__,
							'jsApiTicket' => $corpInfo['jsapi_ticket'],
						)
					);
				}


				$jsapi_ticket      = $jsApiTicket['ticket'];
				$ticket_expires_at = date('Y-m-d H:i:s', time() + $jsApiTicket['expires_in']);

				$ret = $this->corp_model->updateCorp($pkCorp, compact('jsapi_ticket', 'ticket_expires_at'));
				if (empty($ret)) {
					log_message_v2(
						'error',
						array(
							'msg'               => 'updateAuthorization failed',
							'file'              => __FILE__,
							'line'              => __LINE__,
							'pkCorp'            => $pkCorp,
							'jsapi_ticket'      => $jsapi_ticket,
							'ticket_expires_at' => $ticket_expires_at,
						)
					);
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::SLEEP_S);
			}
		}
	}

	private function verifyTicketRefreshed($ticketExpiresAt)
	{
		if (strtotime($ticketExpiresAt) - time() > 2) {
			return true;
		}

		return false;
	}
}
