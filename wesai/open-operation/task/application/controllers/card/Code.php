<?php

require_once __DIR__ . '/CardBase.php';

class Code extends CardBase
{
    const TIME_SLEEP_S = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function update()
    {
        while (true) {
            try {
                $msg = $this->redis_list_client->RightPop(OPERATION_MQ_CARD_CODE_UPDATED);
                $msg = json_decode($msg, true);
                if (empty($msg) || empty($msg['card_id']) || empty($msg['code_id'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $card_id = $msg['card_id'];
                $code_id = $msg['code_id'];

                $card_info = $this->getCardById($card_id, $return_ext = 1);
                if (empty($card_info)) {
                    continue;
                }
                // $card_info = $card_info['result'];
                //
                // $data = $this->makeCardUpdateData($card_info);
                //
                // $access_token = $this->getAuthorizerAccessToken($card_info['fk_component_authorizer_app']);
                // if (empty($access_token)) {
                //     continue;
                // }
                //
                // $this->load->library('WeiXinCardApi');
                // $result = $this->WeiXinCardApi->updateCard($access_token, $data);
                // if (empty($result) || !empty($result['errcode'])) {
                //     $msg = '';
                //     empty($result['errmsg']) or $msg = $result['errmsg'];
                //     log_message_v2('error', 'update weixin card failed | ' . $msg);
                //     continue;
                // }
                //
                // if (!empty($result['send_check'])) {
                //     continue;
                // }
                //
                // $params = compact('card_id');
                // $result = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN,
                //     'card/card/change_state_from_checking_to_pass.json', $params, METHOD_POST);
                // $result = $this->checkInnerApiResult($result, 'affected_rows');
                // if (empty($result) || empty($result['affected_rows'])) {
                //     log_message_v2('error', 'change_state_from_checking_to_pass failed');
                //     continue;
                // }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    private function makeCardUpdateData($card_info)
    {

    }

    public function check_auth_result()
    {

    }
}
