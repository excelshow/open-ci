<?php

require_once __DIR__ . '/CardBase.php';

class Wxmsg_user_card extends CardBase
{
	const TIME_SLEEP_S = 10;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper("diy");
	}
	public function get_card()
	{
		while (true) {
			try {
                $msg = $this->getRightPop(OPENERATION_MQ_WXMSG_EVENT_USER_GET_CARD);
                if (empty($msg) || empty($msg['CardId']) || empty($msg['UserCardCode']) || empty($msg['FromUserName'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $wx_card_id = $msg['CardId'];
                $code       = $msg['UserCardCode'];
                $openid     = $msg['FromUserName'];
                $is_restore = !empty($msg['IsRestoreMemberCard']) ? $msg['IsRestoreMemberCard'] : 0;

                $card_info = $this->getCardByWxCardId($wx_card_id);
                if (empty($card_info)) {
                    continue;
                }
                $params = compact('wx_card_id', 'code', 'openid', 'is_restore');
                if (!empty($msg['IsGiveByFriend']) && $msg['IsGiveByFriend'] == 1) {
                    $params['friend_openid'] = $msg['FriendUserName'];
                    $params['original_code'] = $msg['OldUserCardCode'];
                }

                $result = $this->receive($params);
                if (empty($result)) {
                    continue;
                }
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

    private function receive($params)
    {
        $resultApi = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/code/receive.json', $params, METHOD_POST);
        $result = $this->checkInnerApiResult($resultApi, 'affected_rows');
        if (empty($result) || empty($result['affected_rows'])) {
            $msg = "__METHOD__:" . __METHOD__ . ' get_user_card failed ! ';
            log_message('error', $msg . ' pm=' . json_encode($params) . 'rt='.json_encode($resultApi));
            return false;
        }
        return true;
	}


    public function del_card()
    {
        while (true) {
            try {
                $msg = $this->getRightPop(OPENERATION_MQ_WXMSG_EVENT_USER_DEL_CARD);
                if (empty($msg) || empty($msg['CardId']) || empty($msg['UserCardCode'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $wx_card_id = $msg['CardId'];
                $code       = $msg['UserCardCode'];

                $card_info = $this->getCardByWxCardId($wx_card_id);
                if (empty($card_info)) {
                    continue;
                }

                $params = compact('wx_card_id', 'code');

                $result = $this->delete_from_weixin($params);

                if (empty($result)) {
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    private function delete_from_weixin($params)
    {
        $resultApi = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/code/delete_from_weixin.json', $params, METHOD_POST);
        $result = $this->checkInnerApiResult($resultApi, 'affected_rows');
        if (empty($result) || empty($result['affected_rows'])) {
            $msg = "__METHOD__:" . __METHOD__ . ' del_user_card failed ! ';
            log_message('error', $msg . ' pm=' . json_encode($params) . 'rt='.json_encode($resultApi));
            return false;
        }
    }

    public function activate_card()
    {
        while (true) {
            try {
                $msg = $this->getRightPop(OPENERATION_MQ_WXMSG_EVENT_SUBMIT_MEMBERCARD_USER_INFO);
                if (empty($msg) || empty($msg['CardId']) || empty($msg['UserCardCode'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $wx_card_id = $msg['CardId'];
                $code       = $msg['UserCardCode'];

                $card_info = $this->getCardByWxCardId($wx_card_id);
                if (empty($card_info)) {
                    continue;
                }

                $params = compact('wx_card_id', 'code');

                $result = $this->activate($params);
                if (empty($result)) {
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    private function activate($params)
    {
        $resultApi = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/code/activate.json', $params, METHOD_POST);
        $result = $this->checkInnerApiResult($resultApi, 'affected_rows');
        if (empty($result) || empty($result['affected_rows'])) {
            $msg = "__METHOD__:" . __METHOD__ . ' failed ! ';
            log_message('error', $msg . ' pm=' . json_encode($params) . 'rt='.json_encode($resultApi));
            return false;
        }
    }

    private function getRightPop($mq_key)
    {
        $msg = $this->redis_list_client->RightPop($mq_key);
        $msg = json_decode($msg, true);
        log_message_to_file('Wxmsg_user_card', $msg);
        return $msg;
    }


}
