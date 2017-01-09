<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/CardBase.php';

/**
 * User: zhaodc
 * Date: 28/12/2016
 * Time: 09:54
 */
class Card extends CardBase
{

    /**
     * Card constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function checkInsertParams($params, $rules)
    {
        if (empty($rules)) {
            return '';
        }

        foreach ($rules as $key => $rule) {
            if (!is_array($rule)) {
                if (!isset($params[$rule])) {
                    return '参数错误 ' . $rule;
                }
                continue;
            }

            if (!isset($params[$key])) {
                return '参数错误 ' . $key;
            }

            if (!empty($rule['child_required'])) {
                $result = $this->checkInsertParams($params[$key], $rule['child_required']);
                if (!empty($result)) {
                    return $result . '|' . $key;
                }
            }

            if (!empty($rule['sibiling_required_conditional'])) {
                foreach ($rule['sibiling_required_conditional'] as $sub_key => $sub_rule) {
                    if ($params[$key] == $sub_key) {
                        $result = $this->checkInsertParams($params, $sub_rule);
                        if (!empty($result)) {
                            return $result . '|' . $sub_key;
                        }
                        break;
                    }
                }
            }

            return '';
        }

    }

    public function add_post()
    {
        $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('service_type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('service_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('apppk', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('card_info', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_info = json_decode($params['card_info'], true);

        $param_rule = [
            'card_type' => [
                'sibiling_required_conditional' => [
                    CARD_TYPE_MEMBER_CARD => [
                        'member_card' => [
                            'child_required' => ['prerogative'],
                        ],
                    ],
                ],
            ],
            'base_info' => [
                'child_required' => [
                    'logo_url',
                    'brand_name',
                    'title',
                    'background_type',
                    'notice',
                    'description',
                    'date_type',
                ],
            ],
        ];

        $error_msg = $this->checkInsertParams($card_info, $param_rule);

        if (!empty($error_msg)) {
            return $this->response_error(Error_Code::ERROR_PARAM, $error_msg);
        }

        $result = $this->Card_model->create($params['corp_id'], $params['service_type'], $params['service_id'], $params['apppk'], $card_info);
        if ($result->error < 0) {
            return $this->response_error($result->error, $result->msg);
        }

        $this->Msg_model->sendMsgCardCreated($result->card_id);

        return $this->response_insert($result->card_id);
    }

    public function update_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('card_info', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_info = json_decode($params['card_info'], true);

        $support_params = [
            'base_info' => ['title', 'logo_url', 'code_type', 'background_type', 'color', 'notice', 'description', 'use_dynamic_code', 'service_phone', 'date_type', 'begin_timestamp', 'end_timestamp', 'center_title', 'center_sub_title', 'center_url', 'custom_url_name', 'custom_url_sub_title', 'custom_url', 'promotion_url_name', 'promotion_url_sub_title', 'promotion_url', 'get_limit', 'can_share', 'can_give_friend', 'abstract', 'icon_url_list', 'business_service', 'time_limit_type', 'time_limit_begin_hour', 'time_limit_begin_minute', 'time_limit_end_hour', 'time_limit_end_minute'],
            'member_card' => ['background_pic_url', 'prerogative', 'auto_activate', 'supply_balance', 'supply_bonus', 'discount', 'custom_fields'],
            'text_image_list' => ['image_url', 'text'],
        ];

        foreach ($card_info as $key => $value) {
            if (!in_array($key, array_keys($support_params))) {
                return $this->response_error(Error_Code::ERROR_PARAM, '参数非法 ' . $key);
            }
            if (is_array($value)) {
                foreach ($value as $sub_key => $sub_value) {
                    if (!in_array($sub_key, $support_params[$key])) {
                        return $this->response_error(Error_Code::ERROR_PARAM, '参数非法 ' . $sub_key . ' @ ' . $sub_value);
                    }
                }
            }
        }

        if (!empty($card_info['base_info']) && !empty($card_info['base_info']['date_type'])) {
            if (!in_array($card_info['base_info']['date_type'], [CARD_DATE_TYPE_PERMANENT, CARD_DATE_TYPE_FIX_TIME_RANGE])) {
                return $this->response_error(Error_Code::ERROR_PARAM, '参数错误 date_type');
            }
            if ($card_info['base_info']['date_type'] == CARD_DATE_TYPE_FIX_TIME_RANGE &&
                (empty($card_info['base_info']['begin_timestamp']) || empty($card_info['base_info']['end_timestamp']))) {
                return $this->response_error(Error_Code::ERROR_PARAM, '参数错误 begin_timestamp/end_timestamp');
            }
        }

        $card_id = $params['card_id'];
        $card = $this->checkCardExistsById($card_id);
        $card = $this->getCardExtInfo($card_id, 1, $card);

        $card_type_name = $this->getCardTypeName($card['card_type']);
        if (!empty($card_info[$card_type_name])) {
            if (isset($card_info[$card_type_name]['supply_balance']) && $card_info[$card_type_name]['supply_balance'] != CARD_SUPPLY_BALANCE_YES) {
                return $this->response_error(Error_Code::ERROR_PARAM, '参数错误 supply_balance');
            }
            if (isset($card_info[$card_type_name]['supply_bonus']) && $card_info[$card_type_name]['supply_bonus'] != CARD_SUPPLY_BONUS_YES) {
                return $this->response_error(Error_Code::ERROR_PARAM, '参数错误 supply_bonus');
            }
        }

        if ($card['state'] == CARD_STATE_DELETED) {
            return $this->response_error(Error_Code::ERROR_CARD_STATE_INVALID);
        }

        $affected_rows = $this->Card_model->modify($params['card_id'], $card['card_type'], $card_info, $card);
        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_UPDATE_FAILED);
        }

        $this->Msg_model->sendMsgCardUpdated($params['card_id']);

        return $this->response_update($affected_rows);
    }

    public function delete_post()
    {

    }

    /**
     * 更新卡券状态从已创建到审核中
     *
     * @param card_id 卡券自增ID
     *
     * @return Integer affect rows
     */
    public function change_state_from_created_to_checking_post()
    {
        $card_id  = $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY);

        $card_info = $this->checkCardExistsById($card_id);

        $this->checkCardState($card_info['state'], CARD_STATE_CREATED);

        $affected_rows = $this->Card_model->changeState($card_id, CARD_STATE_CREATED, CARD_STATE_CHECKING, __METHOD__);

        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CHANGE_STATE_FROM_CREATED_TO_CHECKING);
        }
        return $this->response_update($affected_rows);
    }


    /**
     * 更新卡券状态从审核中到审核未通过
     *
     * @param card_id 卡券自增ID
     *
     * @return Integer affect rows
     */
    public function change_state_from_checking_to_not_pass_post()
    {
        $card_id = $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY);
        $reason  = $this->post_check('reason', PARAM_NULL_NOT_EMPTY);

        $card_info = $this->checkCardExistsById($card_id);

        $this->checkCardState($card_info['state'], CARD_STATE_CHECKING);

        $remark = __METHOD__;
        empty($reason) or $remark = $reason;
        $affected_rows = $this->Card_model->changeState($card_id, CARD_STATE_CHECKING, CARD_STATE_NOT_PASS, $remark);

        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_NOT_PASS);
        }

        $this->ServiceNotify_model->notify($card_info['fk_corp'], $card_info['service_type'], $card_info['service_id'], CARD_STATE_NOT_PASS);

        return $this->response_update($affected_rows);
    }

    /**
     * 更新卡券状态从审核中到审核通过
     * @param card_id 卡券自增ID
     * @return Integer affect rows
     */
    public function change_state_from_checking_to_pass_post()
    {
        $card_id  = $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY);

        $card_info = $this->checkCardExistsById($card_id);

        $this->checkCardState($card_info['state'], CARD_STATE_CHECKING);

        $affected_rows = $this->Card_model->changeState($card_id, CARD_STATE_CHECKING, CARD_STATE_PASS, __METHOD__);

        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CHANGE_STATE_FROM_CHECKING_TO_PASS);
        }

        $this->ServiceNotify_model->notify($card_info['fk_corp'], $card_info['service_type'], $card_info['service_id'], CARD_STATE_PASS);

        return $this->response_update($affected_rows);
    }

    /**
     * 根据卡券自增id获取卡券详情
     * @param card_id 卡券自增ID
     * @param return_ext 是否返回扩展信息
     * @return object
     */
    public function get_by_id_get()
    {
        $card_id    = $this->get_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $return_ext = $this->get_check('return_ext', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $card_info = $this->checkCardExistsById($card_id);

        $card_info = $this->getCardExtInfo($card_id, $return_ext, $card_info);

        return $this->response_object($card_info);
    }


    /**
     * 根据微信卡券ID获取卡券详情
     * @param wx_card_id 微信卡券ID
     * @param return_ext 是否返回扩展信息
     * @return array
     */
    public function get_by_wx_card_id_get()
    {
        $wx_card_id = $this->get_check('wx_card_id', PARAM_NOT_NULL_NOT_EMPTY);
        $return_ext = $this->get_check('return_ext', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $card_info = $this->checkCardExistsByWxCardId($wx_card_id);

        $card_info = $this->getCardExtInfo($card_info['pk_card'], $return_ext, $card_info);

        return $this->response_object($card_info);
    }

    /**
     * 根据企业id获取卡券列表
     * @param corp_id 企业id
     * @return array
     */
    public function list_get()
    {
        $fk_corp    = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $state      = $this->get_check('state', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $pageSize   = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        // $return_ext = $this->get_check('return_ext', PARAM_NULL_EMPTY);

        $this->checkPageParams($pageNumber, $pageSize);

        $total = 0;
        $result = $this->Card_model->listByPage($fk_corp, $pageNumber, $pageSize, $state, $total);
        if (empty($result)) {
            return $this->response_list([], 0, $pageNumber, $pageSize);
        }
        return $this->response_list($result, $total, $pageNumber, $pageSize);
    }

    /**
     * @param $card_id
     * @param $return_ext
     * @param $card_info
     *
     * @return mixed
     */
    private function getCardExtInfo($card_id, $return_ext, $card_info)
    {
        if (empty($return_ext)) {
            return $card_info;
        }

        $card_type = $card_info['card_type'];
        $card_type_name = $this->getCardTypeName($card_type);
        $card_ext = $this->Card_model->getExtById($card_id, $card_type);
        $card_ext_custom_fields = $this->Card_model->listExtCustomFields($card_id, $card_type);
        $card_text_image_list = $this->Card_model->listTextImage($card_id);

        $card_info[$card_type_name] = $card_ext;
        $card_info['custom_fields'] = $card_ext_custom_fields;
        $card_info['text_image_list'] = $card_text_image_list;

        return $card_info;
    }

    public function save_wx_card_id_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('wx_card_id', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_id = $params['card_id'];
        $card_info = $this->checkCardExistsById($card_id);
        if (!empty($card_info['wx_card_id'])) {
            return $this->response_error(Error_Code::ERROR_CARD_WX_CARD_ID_ALREADY_EXISTS);
        }

        $affected_rows = $this->Card_model->saveWxCardId($card_id, $params['wx_card_id']);

        return $this->response_update($affected_rows);
    }

    public function save_wx_pic_url_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('text_image_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('logo_url', PARAM_NULL_NOT_EMPTY);
        $this->post_check('icon_url_list', PARAM_NULL_NOT_EMPTY);
        $this->post_check('image_url', PARAM_NULL_NOT_EMPTY);
        $this->post_check('background_pic_url', PARAM_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $this->checkCardExistsById($params['card_id']);

        $card_update_params = [];
        if (!empty($params['logo_url'])) {
            $card_update_params['wx_logo_url'] = $params['logo_url'];
        }
        if (!empty($params['icon_url_list'])) {
            $card_update_params['wx_icon_url_list'] = $params['icon_url_list'];
        }

        $member_card_update_params = [];
        if (!empty($params['background_pic_url'])) {
            $member_card_update_params['wx_background_pic_url'] = $params['background_pic_url'];
        }

        $text_image_update_params = [];
        if (!empty($params['image_url']) && !empty($params['text_image_id'])) {
            $text_image_update_params['wx_image_url'] = $params['image_url'];
        }

        $affeceted_rows = 0;
        if (!empty($card_update_params)) {
            $affeceted_rows += $this->Card_model->saveWxPicUrl($params['card_id'], $card_update_params);
        }

        if (!empty($member_card_update_params)) {
            $affeceted_rows += $this->MemberCard_model->saveWxPicUrl($params['card_id'], $member_card_update_params);
        }

        if (!empty($text_image_update_params)) {
            $affeceted_rows += $this->TextImageList_model->saveWxPicUrl($params['text_image_id'], $text_image_update_params);
        }

        return $this->response_update($affeceted_rows);
    }
}
