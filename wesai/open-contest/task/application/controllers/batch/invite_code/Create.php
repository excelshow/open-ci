<?php
/**
 * User: zhaodc
 * Date: 16/7/14
 * Time: 下午16:48
 */
require_once __DIR__ . '/../BatchBase.php';

class Create extends BatchBase
{
	private $log_file_name = '';

	public function __construct()
	{
		parent::__construct();
		$this->log_file_name = implode('_', array_slice(explode('/', __FILE__), -3));;
	}

	public function index()
	{
		while (true) {
			try {
				$msg = $this->redis_list_client->RightPop(MQ_TOPIC_CREATE_CONTEST_ITEM_INVITE_CODE);
				if (empty($msg)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}


				log_message_to_file($this->log_file_name, ['topic' => MQ_TOPIC_CREATE_CONTEST_ITEM_INVITE_CODE, 'msg' => $msg]);

				$msg = json_decode($msg, true);
				if (empty($msg)) {
					log_message_v2('error', 'invalid msg content');
					continue;
				}

				$itemId = $msg['itemId'];

				$this->process($itemId);
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	private function process($item_id)
	{
		$itemInfo = $this->callInnerApiDiy($this->getHostName(), 'contestitem/get.json', compact('item_id'), METHOD_GET);
		$itemInfo = $this->obj2array($itemInfo);

		if (empty($itemInfo['result'])) {
			return false;
		}
		$itemInfo = $itemInfo['result'];

		if ($itemInfo['state'] != CONTEST_ITEM_STATE_OK) {
			$errMsg['msg']           = 'contest state invalid';
			$errMsg['cid']           = $item_id;
			$errMsg['publish_state'] = $itemInfo['publish_state'];
			log_message_v2('error', $errMsg);

			return false;
		}

		$codeList = $this->callInnerApiDiy($this->getHostName(), 'invitecode/list.json', array('item_id' => $item_id, 'page' => 1, 'size' => 2), METHOD_GET);

		$codeList = $this->obj2array($codeList);

		if (!empty($codeList['error'])) {
			return false;
		}

		$total = 0;
		if (!empty($codeList['total'])) {
			$total = $codeList['total'];
		}

		$needClear  = false;
		$needCreate = false;
		$count      = 0;
		switch ($itemInfo['type']) {
			case CONTEST_ITEM_TYPE_SINGLE:
				if ($total > $itemInfo['max_stock']) {
					$needClear = true;
				} elseif ($total < $itemInfo['max_stock']) {
					$needCreate = true;
					$count      = $itemInfo['max_stock'] - $total;
				}
				break;
			case CONTEST_ITEM_TYPE_TEAM:
				if ($total > $itemInfo['team_max_stock'] * $itemInfo['team_size']) {
					$needClear = true;
				} elseif ($total < $itemInfo['team_max_stock'] * $itemInfo['team_size']) {
					$needCreate = true;
					$count      = $itemInfo['team_max_stock'] * $itemInfo['team_size'] - $total;
				}
				break;
		}

		if ($needClear) {
			$result = $this->callInnerApiDiy($this->getHostName(), 'invitecode/clear.json', compact('item_id'), METHOD_POST);
			$result = $this->obj2array($result);
			if (empty($result['affected_rows'])) {
				$errMsg = array(
					'msg'    => 'invitecode/clear failed.',
					'itemId' => $item_id,
				);
				log_message_v2('error', $errMsg);

				return false;
			}

			return true;
		}


		if ($needCreate && $count) {
			$result = $this->callInnerApiDiy($this->getHostName(), 'invitecode/add.json', compact('item_id', 'count'), METHOD_POST, false, 60);
			$result = $this->obj2array($result);

			if (empty($result['result']['count'])) {
				$errMsg = array(
					'msg'    => 'invitecode/add failed.',
					'params' => compact('item_id', 'count'),
				);
				log_message_v2('error', $errMsg);

				return false;
			}
			log_message_to_file($this->log_file_name, __METHOD__ . ' staring create invite code..., target: ' . $count);

			return true;
		}
	}
}
