<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/CardBase.php';
/**
 * User: zhaodc
 * Date: 28/12/2016
 * Time: 09:55
 */
class Code extends CardBase
{

    /**
     * Code constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->model('card/Code_model');
        $this->load->model('card/Card_model');
    }

    public function add_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $params = $this->get_request_params();

        $card_info = $this->checkCardExistsById($params['card_id']);

        $code_info = $this->checkCardCodeExistsByCode($params['code']);
        if (!empty($code_info)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_ALREADY_BEEN_RECEIVED);
        }

        $card_code = [];
        $card_code['fk_card'] = $params['card_id'];
        $card_code['code'] = $params['code'];
        $card_code['original_code'] = $params['code'];
        $card_code['fk_user'] = $params['uid'];

        $code_id = $this->Code_model->create($card_code);
        if (empty($code_id)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_CREATE_FAILED);
        }

        return $this->response_insert($code_id);
    }

    public function update_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);
        // $this->post_check('balance', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('add_balance', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('record_balance', PARAM_NULL_EMPTY);
        // $this->post_check('bonus', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('add_bonus', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('record_bonus', PARAM_NULL_EMPTY);
        $this->post_check('custom_field_value1', PARAM_NULL_EMPTY);
        $this->post_check('custom_field_value2', PARAM_NULL_EMPTY);
        $this->post_check('custom_field_value3', PARAM_NULL_EMPTY);

        $params = $this->get_request_params();

        $card_info = $this->checkCardExistsById($params['card_id']);

        $code_info = $this->checkCardCodeExistsByCode($params['code']);

        $this->checkCardCodeState($code_info['state'], [CARD_CODE_STATE_CONSUMED, CARD_CODE_STATE_DISABLED], false);

        if ($code_info['fk_card'] != $card_info['pk_card']) {
            return $this->response_error(Error_Code::ERROR_CARD_VS_CODE_NOT_MATCH);
        }

        $result = $this->Code_model->modify($code_info['pk_card_code'], $params);
        if ($result['error'] < 0) {
            return $this->response_error($result['error']);
        }

        if (empty($result['affected_rows'])) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_UPDATE_FAILED);
        }

        $affected_rows = $result['affected_rows'];

        unset($result['error'], $result['affected_rows']);
        $this->Msg_model->sendMsgCardCodeUpdated($params['card_id'], $params['code'], $result);

        return $this->response_update($affected_rows);
    }

    public function receive_post()
    {
        $this->post_check('wx_card_id', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('original_code', PARAM_NULL_NOT_EMPTY);
        $this->post_check('openid', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('friend_openid', PARAM_NULL_NOT_EMPTY);
        $this->post_check('is_restore', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $params = $this->get_request_params();

        $card_info = $this->checkCardExistsByWxCardId($params['wx_card_id']);

        $code = $params['code'];
        empty($params['original_code']) or $code = $params['original_code'];

        $code_info = $this->checkCardCodeExistsByCode($code);

        if ($code_info['fk_card'] != $card_info['pk_card']) {
            return $this->response_error(Error_Code::ERROR_CARD_VS_CODE_NOT_MATCH);
        }

        if (empty($params['is_resotre']) && $code_info['state'] != CARD_CODE_STATE_CREATED) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_STATE_INVALID);
        }

        if (!empty($params['is_resotre']) && $code_info['wx_state'] != CARD_CODE_WX_STATE_DELETED) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_STATE_INVALID);
        }

        $affected_rows = $this->Code_model->receive($code_info['pk_card_code'], $params);
        if ($affected_rows < 0) {
            return $this->response_error($affected_rows);
        }
        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_RECEIVE_FALIED);
        }

        return $this->response_update($affected_rows);
    }

    public function delete_from_weixin_post()
    {
        $this->post_check('wx_card_id', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_info = $this->checkCardExistsByWxCardId($params['wx_card_id']);

        $code_info = $this->checkCardCodeExistsByCode($params['code']);

        if ($code_info['fk_card'] != $card_info['pk_card']) {
            return $this->response_error(Error_Code::ERROR_CARD_VS_CODE_NOT_MATCH);
        }

        if ($code_info['wx_state'] != CARD_CODE_WX_STATE_NORMAL) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_STATE_INVALID);
        }

        $affected_rows = $this->Code_model->deleteFromWeixin($code_info['pk_card_code']);
        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_UPDATE_FAILED);
        }

        return $this->response_update($affected_rows);
    }

    public function get_by_id_get()
    {
        $code_id = $this->get_check('card_code_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $return_ext = $this->get_check('return_ext', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $code_info = $this->checkCardCodeExistsById($code_id);

        $card_id = $code_info['fk_card'];
        $card_info = $this->checkCardExistsById($card_id);

        $code_info['card_info'] = $card_info;

        if (empty($return_ext)) {
            return $this->response_object($code_info);
        }

        $card_type = $card_info['card_type'];
        $card_ext = $this->Card_model->getExtById($card_id, $card_type);

        $card_type_name = $this->getCardTypeName($card_type);

        $card_ext_custom_value = $this->Code_model->listCustomValues($card_id);

        $code_info[$card_type_name] = $card_ext;
        $code_info['custom_value'] = $card_ext_custom_value;

        return $this->response_object($code_info);
    }

    public function get_by_code_get()
    {
        $code = $this->get_check('code', PARAM_NOT_NULL_NOT_EMPTY);
        $return_ext = $this->get_check('return_ext', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $code_info = $this->checkCardCodeExistsByCode($code);

        $card_id = $code_info['fk_card'];
        $card_info = $this->checkCardExistsById($card_id);

        $code_info['card_info'] = $card_info;

        if (empty($return_ext)) {
            return $this->response_object($code_info);
        }
        $card_type = $card_info['card_type'];
        $card_ext = $this->Card_model->getExtById($card_id, $card_type);

        $card_type_name = $this->getCardTypeName($card_type);

        $card_ext_custom_value = $this->Code_model->listCustomValues($card_id);

        $code_info[$card_type_name] = $card_ext;
        $code_info['custom_value'] = $card_ext_custom_value;

        return $this->response_object($code_info);
    }

    public function list_get()
    {
        $card_id = $this->get_check('card_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $page = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $size = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $this->checkPageParams($page, $size);

        $total = 0;
        $result = $this->Code_model->listByPage($card_id, $page, $size, $total);

        return $this->response_list($result, $total, $page, $size);
    }

    public function activate_post()
    {
        $this->post_check('wx_card_id', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_info = $this->checkCardExistsByWxCardId($params['wx_card_id']);

        $code_info = $this->checkCardCodeExistsByCode($params['code']);

        if ($code_info['fk_card'] != $card_info['pk_card']) {
            return $this->response_error(Error_Code::ERROR_CARD_VS_CODE_NOT_MATCH);
        }

        if ($code_info['state'] != CARD_CODE_STATE_RECEIVED) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_STATE_INVALID);
        }

        $affected_rows = $this->Code_model->activate($code_info['pk_card_code']);
        if (empty($affected_rows)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_UPDATE_FAILED);
        }

        return $this->response_update($affected_rows);
    }
}
