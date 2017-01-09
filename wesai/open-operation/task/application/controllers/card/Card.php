<?php

require_once __DIR__ . '/CardBase.php';

class Card extends CardBase
{
    const TIME_SLEEP_S = 5;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('WeiXinCardApi');
        $this->load->library('File');
    }

    public function create()
    {
        while (true) {
            try {
                $msg = $this->redis_list_client->RightPop(OPERATION_MQ_CARD_CREATED);
                $msg = json_decode($msg, true);
                if (empty($msg) || empty($msg['card_id'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $card_id = $msg['card_id'];

                $card_info = $this->getCardById($card_id, $return_ext = 1);
                if (empty($card_info)) {
                    continue;
                }
                $card_info = $card_info['result'];

                if ($card_info['state'] != CARD_STATE_CREATED) {
                    log_message_v2('error', 'invalid card state | ' . json_encode($card_info));
                    continue;
                }

                $access_token = $this->getAuthorizerAccessToken($card_info['fk_component_authorizer_app']);
                if (empty($access_token)) {
                    continue;
                }

                $data = $this->makeCardCreateData($card_info, $access_token);


                $params = compact('card_id');
                $state_changing_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/change_state_from_created_to_checking.json', $params, METHOD_POST);
                $state_changing_result = $this->checkInnerApiResult($state_changing_result, 'affected_rows');
                if (empty($state_changing_result) || empty($state_changing_result['affected_rows'])) {
                    log_message_v2('error', 'change_state_from_created_to_checking failed | ' . json_encode($params));
                    continue;
                }

                $wx_card_id = $this->weixincardapi->createCard($access_token, $data);

                if (empty($wx_card_id)) {
                    log_message_v2('error', 'create weixin card failed');
                    continue;
                }

                $params = compact('card_id');
                $params['wx_card_id'] = $wx_card_id;

                $result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/save_wx_card_id.json', $params, METHOD_POST);
                $result = $this->checkInnerApiResult($result, 'affected_rows');
                if (empty($result) || empty($result['affected_rows'])) {
                    log_message_v2('error', 'save wx_card_id failed');
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    private function makeCardCreateData($card_info, $access_token)
    {
        $card = [
            'card_type' => $this->getConstantName('card_type', $card_info['card_type']),
        ];

        $card_type_name = strtolower($card['card_type']);
        $card_ext = $card_info[$card_type_name];

        $base_info = [];
        $card_id = $card_info['pk_card'];
        $base_info['logo_url'] = $this->getWxPicUrl($card_id, $card_info, 'logo_url', $access_token);
        $base_info['brand_name'] = $card_info['brand_name'];
        $base_info['code_type'] = $this->getConstantName('code_type', $card_info['code_type']);
        $base_info['title'] = $card_info['title'];
        if ($card_info['background_type'] == CARD_BACKGROUND_TYPE_COLOR) {
            $base_info['color'] = $card_info['color'];
        } else {
            $card[$card_type_name]['background_pic_url'] = $this->getWxPicUrl($card_id, $card_ext, 'background_pic_url', $access_token);
            $base_info['color'] = 'Color010';
        }
        $base_info['notice'] = $card_info['notice'];
        $base_info['description'] = $card_info['description'];
        $base_info['sku']['quantity'] = intval($card_info['quantity']);
        $base_info['date_info'] = $this->getWxDateInfo($card_info);

        if (!empty($card_info['use_custom_code'])) {
            $base_info['use_custom_code'] = $this->getWxBoolValue($card_info['use_custom_code']);
        }

        if (!empty($card_info['bind_openid'])) {
            $base_info['bind_openid'] = $this->getWxBoolValue($card_info['bind_openid']);
        }
        if (!empty($card_info['service_phone'])) {
            $base_info['service_phone'] = $card_info['service_phone'];
        }
        if (!empty($card_info['location_id_list'])) {
            $base_info['location_id_list'] = $card_info['location_id_list'];
        }
        if (!empty($card_info['use_all_locations'])) {
            $base_info['use_all_locations'] = $card_info['use_all_locations'];
        }
        if (!empty($card_info['center_title'])) {
            $base_info['center_title'] = $card_info['center_title'];
        }
        if (!empty($card_info['center_sub_title'])) {
            $base_info['center_sub_title'] = $card_info['center_sub_title'];
        }
        if (!empty($card_info['center_url'])) {
            $base_info['center_url'] = $card_info['center_url'];
        }
        if (!empty($card_info['custom_url_name'])) {
            $base_info['custom_url_name'] = $card_info['custom_url_name'];
        }
        if (!empty($card_info['custom_url_sub_title'])) {
            $base_info['custom_url_sub_title'] = $card_info['custom_url_sub_title'];
        }
        if (!empty($card_info['custom_url'])) {
            $base_info['custom_url'] = $card_info['custom_url'];
        }
        if (!empty($card_info['promotion_url_name'])) {
            $base_info['promotion_url_name'] = $card_info['promotion_url_name'];
        }
        if (!empty($card_info['promotion_url_sub_title'])) {
            $base_info['promotion_url_sub_title'] = $card_info['promotion_url_sub_title'];
        }
        if (!empty($card_info['promotion_url'])) {
            $base_info['promotion_url'] = $card_info['promotion_url'];
        }
        if (!empty($card_info['get_limit'])) {
            $base_info['get_limit'] = intval($card_info['get_limit']);
        }
        if (!empty($card_info['can_share'])) {
            $base_info['can_share'] = $this->getWxBoolValue($card_info['can_share']);
        }
        if (!empty($card_info['can_give_friend'])) {
            $base_info['can_give_friend'] = $this->getWxBoolValue($card_info['can_give_friend']);
        }
        if (!empty($card_info['need_push_on_view'])) {
            $base_info['need_push_on_view'] = $this->getWxBoolValue($card_info['need_push_on_view']);
        }

        $card[$card_type_name]['base_info'] = $base_info;

        $advanced_info = [];

        $abstract = [];
        if (!empty($card_info['abstract'])) {
            $abstract['abstract'] = $card_info['abstract'];
        }
        if (!empty($card_info['icon_url_list'])) {
            $abstract['icon_url_list'] = [$this->getWxPicUrl($card_id, $card_info, 'icon_url_list', $access_token)];
        }

        if (!empty($abstract)) {
            $advanced_info['abstract'] = $abstract;
        }

        $text_image_list = [];
        if (!empty($card_info['text_image_list'])) {
            foreach ($card_info['text_image_list'] as $tm) {
                $wx_tm['text'] = $tm['text'];
                $wx_tm['image_url'] = $this->getWxPicUrl($card_id, $tm, 'image_url', $access_token);
                $text_image_list[] = $wx_tm;
            }
        }

        if (!empty($text_image_list)) {
            $advanced_info['text_image_list'] = $text_image_list;
        }

        $time_limits = [];
        if (!empty($card_info['time_limit_type'])) {
            $tlts = explode(',', $card_info['time_limit_type']);
            foreach ($tlts as $tlt) {
                $time_limit['type'] = $this->getConstantName('time_limit_type', $tlt);
                if (!empty($card_info['time_limit_begin_hour'])) {
                    $time_limit['begin_hour'] = intval($card_info['time_limit_begin_hour']);
                }
                if (!empty($card_info['time_limit_begin_minute'])) {
                    $time_limit['begin_minute'] = intval($card_info['time_limit_begin_minute']);
                }
                if (!empty($card_info['time_limit_end_hour'])) {
                    $time_limit['end_hour'] = intval($card_info['time_limit_end_hour']);
                }
                if (!empty($card_info['time_limit_end_minute'])) {
                    $time_limit['end_minute'] = intval($card_info['time_limit_end_minute']);
                }
                $time_limits[] = $time_limit;
            }
        }

        if (!empty($time_limits)) {
            $advanced_info['time_limit'] = $time_limits;
        }

        $business_service = [];
        if (!empty($card_info['business_service'])) {
            $services = explode(',', $card_info['business_service']);
            foreach ($services as $service) {
                $business_service[] = $this->getConstantName('business_service', $service);
            }
        }

        if (!empty($business_service)) {
            $advanced_info['business_service'] = $business_service;
        }

        if (!empty($advanced_info)) {
            $card[$card_type_name]['advanced_info'] = $advanced_info;
        }

        $custom_fields = [];
        foreach ($card_info['custom_fields'] as $field) {
            $cf = [];
            switch ($field['field_type']) {
                case 1:
                    $cf['name_type'] = $this->getConstantName('custom_name_type', $field['name_type']);
                    break;
                case 2:
                    $cf['name'] = $field['name'];
                    break;
            }
            if (!empty($field['url'])) {
                $cf['url'] = $field['url'];
            }

            $custom_fields[$field['wx_field_seq']] = $cf;
        }

        switch ($card_info['card_type']) {
            case CARD_TYPE_MEMBER_CARD:
                if (!empty($card_ext['prerogative'])) {
                    $card[$card_type_name]['prerogative'] = $card_ext['prerogative'];
                }
                if (!empty($card_ext['supply_balance'])) {
                    $card[$card_type_name]['supply_balance'] = $this->getWxBoolValue($card_ext['supply_balance']);
                }
                if (!empty($card_ext['supply_bonus'])) {
                    $card[$card_type_name]['supply_bonus'] = $this->getWxBoolValue($card_ext['supply_bonus']);
                }
                if (!empty($card_ext['auto_activate'])) {
                    $card[$card_type_name]['auto_activate'] = $this->getWxBoolValue($card_ext['auto_activate']);
                }
                if (!empty($card_ext['discount'])) {
                    $card[$card_type_name]['discount'] = intval($card_ext['discount']);
                }

                if (!empty($custom_fields)) {
                    foreach ($custom_fields as $seq => $field) {
                        $card[$card_type_name]['custom_field' . $seq] = $field;
                    }
                }
                break;
        }

        return compact('card');
	}

    private function getWxDateInfo($card_info)
    {
        $date_info = [];
        $date_info['type'] = $this->getConstantName('date_type', $card_info['date_type']);
        switch ($card_info['date_type']) {
            case DATE_TYPE_PERMANENT:
                break;
            case DATE_TYPE_FIX_TIME_RANGE:
                $date_info['begin_timestamp'] = strtotime($card_info['begin_timestamp']);
                $date_info['end_timestamp'] = strtotime($card_info['end_timestamp']);
                break;
            case DATE_TYPE_FIX_TERM;
                $date_info['fixed_term'] = intval($card_info['fixed_term']);
                $date_info['fixed_begin_term'] = intval($card_info['fixed_begin_term']);
                break;
        }

        return $date_info;
    }

    private function getWxPicUrl($card_id, $pic_data, $key, $access_token)
    {
        if (!empty($pic_data['wx_' . $key])) {
            return $pic_data['wx_' . $key];
        }
        $image = $this->file->download($pic_data[$key]);
        if (empty($image)) {
            return false;
        }
        $wx_logo_url = $this->weixincardapi->uploadImage($access_token, $image);
        if (empty($wx_logo_url)) {
            return false;
        }

        $sub_record_id_name = null;
        $sub_record_id_value = null;
        if ($key == 'image_url') {
            $sub_record_id_name = 'text_image_id';
            $sub_record_id_value = $pic_data['pk_card_text_image_list'];
        }

        $this->saveCardWxPicUrl($card_id, $key, $wx_logo_url, $sub_record_id_name, $sub_record_id_value);

        return $wx_logo_url;
	}

    private function saveCardWxPicUrl($card_id, $key, $value, $sub_record_id_name = null, $sub_record_id_value = null)
    {
        $params = [];
        $params['card_id'] = $card_id;
        $params[$key] = $value;
        if (!empty($sub_record_id_name) && !empty($sub_record_id_value)) {
            $params[$sub_record_id_name] = $sub_record_id_value;
        }

        $result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/save_wx_pic_url.json', $params, METHOD_POST);

        // log_message('error', json_encode($params) . '|' . json_encode($result));
	}


    public function update()
    {
        while (true) {
            try {
                $msg = $this->redis_list_client->RightPop(OPERATION_MQ_CARD_UPDATED);
                $msg = json_decode($msg, true);
                if (empty($msg) || empty($msg['card_id'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $card_id = $msg['card_id'];

                $card_info = $this->getCardById($card_id, $return_ext = 1);
                if (empty($card_info)) {
                    continue;
                }
                $card_info = $card_info['result'];

                if ($card_info['state'] != CARD_STATE_CHECKING) {
                    continue;
                }

                $access_token = $this->getAuthorizerAccessToken($card_info['fk_component_authorizer_app']);
                if (empty($access_token)) {
                    continue;
                }

                $data = $this->makeCardUpdateData($card_info, $access_token);

                $result = $this->weixincardapi->updateCard($access_token, $data);
                if (empty($result) || !empty($result['errcode'])) {
                    $msg = '';
                    empty($result['errmsg']) or $msg = $result['errmsg'];
                    log_message_v2('error', 'update weixin card failed | ' . $msg);
                    continue;
                }

                if (!empty($result['send_check'])) {
                    continue;
                }

                $params = compact('card_id');
                $result = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'card/card/change_state_from_checking_to_pass.json', $params, METHOD_POST);
                $result = $this->checkInnerApiResult($result, 'affected_rows');
                if (empty($result) || empty($result['affected_rows'])) {
                    log_message_v2('error', 'change_state_from_checking_to_pass failed');
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    private function makeCardUpdateData($card_info, $access_token)
    {
        $card_id = $card_info['pk_card'];

        $card = [];
        $card['card_id'] = $card_info['wx_card_id'];
        $card_type_name = strtolower($this->getConstantName('card_type', $card_info['card_type']));
        $card_ext = $card_info[$card_type_name];

        $base_info = [];
        if (!empty($card_info['title'])) {
            $base_info['title'] = $card_info['title'];
        }
        if (!empty($card_info['logo_url'])) {
            $base_info['logo_url'] = $this->getWxPicUrl($card_id, $card_info, 'logo_url', $access_token);
        }
        if ($card_info['background_type'] == CARD_BACKGROUND_TYPE_COLOR) {
            $base_info['color'] = $card_info['color'];
        } else {
            $card[$card_type_name]['background_pic_url'] = $this->getWxPicUrl($card_id, $card_ext, 'background_pic_url', $access_token);
        }
        if (!empty($card_info['description'])) {
            $base_info['description'] = $card_info['description'];
        }
        if (!empty($card_info['service_phone'])) {
            $base_info['service_phone'] = $card_info['service_phone'];
        }
        if (!empty($card_info['location_id_list '])) {
            $base_info['location_id_list '] = $card_info['location_id_list '];
        }
        if (!empty($card_info['use_all_locations'])) {
            $base_info['use_all_locations'] = $this->getWxBoolValue($card_info['use_all_locations']);
        }
        if (!empty($card_info['center_title'])) {
            $base_info['center_title'] = $card_info['center_title'];
        }
        if (!empty($card_info['center_sub_title'])) {
            $base_info['center_sub_title'] = $card_info['center_sub_title'];
        }
        if (!empty($card_info['center_url'])) {
            $base_info['center_url'] = $card_info['center_url'];
        }
        if (!empty($card_info['custom_url_name'])) {
            $base_info['custom_url_name'] = $card_info['custom_url_name'];
        }
        if (!empty($card_info['custom_url_sub_title'])) {
            $base_info['custom_url_sub_title'] = $card_info['custom_url_sub_title'];
        }
        if (!empty($card_info['custom_url'])) {
            $base_info['custom_url'] = $card_info['custom_url'];
        }
        if (!empty($card_info['promotion_url_name'])) {
            $base_info['promotion_url_name'] = $card_info['promotion_url_name'];
        }
        if (!empty($card_info['promotion_url_sub_title'])) {
            $base_info['promotion_url_sub_title'] = $card_info['promotion_url_sub_title'];
        }
        if (!empty($card_info['promotion_url'])) {
            $base_info['promotion_url'] = $card_info['promotion_url'];
        }
        if (!empty($card_info['promotion_url'])) {
            $base_info['code_type'] = $this->getConstantName('code_type', $card_info['code_type']);
        }
        if (!empty($card_info['get_limit'])) {
            $base_info['get_limit'] = intval($card_info['get_limit']);
        }
        if (!empty($card_info['can_share'])) {
            $base_info['can_share'] = $this->getWxBoolValue($card_info['can_share']);
        }
        if (!empty($card_info['date_type']) && $card_info['date_type'] == DATE_TYPE_FIX_TIME_RANGE) {
            $base_info['date_info'] = $this->getWxDateInfo($card_info);
        }

        $card[$card_type_name]['base_info'] = $base_info;

        $advanced_info = [];

        $abstract = [];
        if (!empty($card_info['abstract'])) {
            $abstract['abstract'] = $card_info['abstract'];
        }
        if (!empty($card_info['icon_url_list'])) {
            $abstract['icon_url_list'] = [$this->getWxPicUrl($card_id, $card_info, 'icon_url_list', $access_token)];
        }

        if (!empty($abstract)) {
            $advanced_info['abstract'] = $abstract;
        }

        $text_image_list = [];
        if (!empty($card_info['text_image_list'])) {
            foreach ($card_info['text_image_list'] as $tm) {
                $wx_tm['text'] = $tm['text'];
                $wx_tm['image_url'] = $this->getWxPicUrl($card_id, $tm, 'image_url', $access_token);
                $text_image_list[] = $wx_tm;
            }
        }

        if (!empty($text_image_list)) {
            $advanced_info['text_image_list'] = $text_image_list;
        }

        $time_limits = [];
        if (!empty($card_info['time_limit_type'])) {
            $tlts = explode(',', $card_info['time_limit_type']);
            foreach ($tlts as $tlt) {
                $time_limit['type'] = $this->getConstantName('time_limit_type', $tlt);
                if (!empty($card_info['time_limit_begin_hour'])) {
                    $time_limit['begin_hour'] = intval($card_info['time_limit_begin_hour']);
                }
                if (!empty($card_info['time_limit_begin_minute'])) {
                    $time_limit['begin_minute'] = intval($card_info['time_limit_begin_minute']);
                }
                if (!empty($card_info['time_limit_end_hour'])) {
                    $time_limit['end_hour'] = intval($card_info['time_limit_end_hour']);
                }
                if (!empty($card_info['time_limit_end_minute'])) {
                    $time_limit['end_minute'] = intval($card_info['time_limit_end_minute']);
                }
                $time_limits[] = $time_limit;
            }
        }

        if (!empty($time_limits)) {
            $advanced_info['time_limit'] = $time_limits;
        }

        $business_service = [];
        if (!empty($card_info['business_service'])) {
            $services = explode(',', $card_info['business_service']);
            foreach ($services as $service) {
                $business_service[] = $this->getConstantName('business_service', $service);
            }
        }

        if (!empty($business_service)) {
            $advanced_info['business_service'] = $business_service;
        }

        if (!empty($advanced_info)) {
            $card[$card_type_name]['advanced_info'] = $advanced_info;
        }

        $custom_fields = [];
        foreach ($card_info['custom_fields'] as $field) {
            $cf = [];
            switch ($field['field_type']) {
                case 1:
                    $cf['name_type'] = $this->getConstantName('custom_name_type', $field['name_type']);
                    break;
                case 2:
                    $cf['name'] = $field['name'];
                    break;
            }
            if (!empty($field['url'])) {
                $cf['url'] = $field['url'];
            }

            $custom_fields[$field['wx_field_seq']] = $cf;
        }

        switch ($card_info['card_type']) {
            case CARD_TYPE_MEMBER_CARD:
                if (!empty($card_ext['supply_bonus'])) {
                    $card[$card_type_name]['supply_bonus'] = $this->getWxBoolValue($card_ext['supply_bonus']);
                }
                if (!empty($card_ext['supply_balance'])) {
                    $card[$card_type_name]['supply_balance'] = $this->getWxBoolValue($card_ext['supply_balance']);
                }
                if (!empty($card_ext['prerogative'])) {
                    $card[$card_type_name]['prerogative'] = $card_ext['prerogative'];
                }
                if (!empty($card_ext['auto_activate'])) {
                    $card[$card_type_name]['auto_activate'] = $this->getWxBoolValue($card_ext['auto_activate']);
                }
                if (!empty($card_ext['discount'])) {
                    $card[$card_type_name]['discount'] = intval($card_ext['discount']);
                }

                if (!empty($custom_fields)) {
                    foreach ($custom_fields as $seq => $field) {
                        $card[$card_type_name]['custom_field' . $seq] = $field;
                    }
                }
                break;
        }

        return $card;
    }

    public function do_card_pass_check()
    {
        while (true) {
            try {
                $msg = $this->redis_list_client->RightPop(OPERATION_MQ_CARD_PASS_CHECK);
                $msg = json_decode($msg, true);
                if (empty($msg) || empty($msg['CardId'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $wx_card_id = $msg['CardId'];

                $card_info = $this->getCardByWxCardId($wx_card_id);
                if (empty($card_info)) {
                    continue;
                }
                $card_info = $card_info['result'];
                $card_id = $card_info['pk_card'];

                if ($card_info['state'] != CARD_STATE_CHECKING) {
                    log_message_v2('error', 'invalid card state | card_id = ' . $card_id . ' | state=' . $card_info['state']);
                    continue;
                }

                $params = compact('card_id');
                $state_changing_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/change_state_from_checking_to_pass.json', $params, METHOD_POST);
                $state_changing_result = $this->checkInnerApiResult($state_changing_result, 'affected_rows');
                if (empty($state_changing_result) || empty($state_changing_result['affected_rows'])) {
                    log_message_v2('error', 'change_state_from_checking_to_pass failed | ' . json_encode($params));
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }

    public function do_card_not_pass_check()
    {
        while (true) {
            try {
                $msg = $this->redis_list_client->RightPop(OPERATION_MQ_CARD_NOT_PASS_CHECK);
                $msg = json_decode($msg, true);
                if (empty($msg) || empty($msg['CardId'])) {
                    sleep(self::TIME_SLEEP_S);
                    continue;
                }

                $wx_card_id = $msg['CardId'];

                $card_info = $this->getCardByWxCardId($wx_card_id);
                if (empty($card_info)) {
                    continue;
                }
                $card_info = $card_info['result'];
                $card_id = $card_info['pk_card'];

                if ($card_info['state'] != CARD_STATE_CHECKING) {
                    log_message_v2('error', 'invalid card state | card_id = ' . $card_id . ' | state=' . $card_info['state']);
                    continue;
                }

                $params = compact('card_id');
                if (!empty($msg['RefuseReason'])) {
                    $params['reason'] = $msg['RefuseReason'];
                }
                $state_changing_result = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/change_state_from_checking_to_not_pass.json', $params, METHOD_POST);
                $state_changing_result = $this->checkInnerApiResult($state_changing_result, 'affected_rows');
                if (empty($state_changing_result) || empty($state_changing_result['affected_rows'])) {
                    log_message_v2('error', 'change_state_from_checking_to_not_pass failed | ' . json_encode($params));
                    continue;
                }
            } catch (Exception $e) {
                $this->catchException($e);
                sleep(self::TIME_SLEEP_S);
            }
        }
    }
}
