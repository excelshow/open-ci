<?php

require_once __DIR__ . '/../Base.php';

/**
 * User: zhaodc
 * Date: 29/12/2016
 * Time: 14:49
 */
class CardBase extends Base
{


    /**
     * CardBase constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('card/Card_model');
        $this->load->model('card/MemberCard_model');
        $this->load->model('card/TextImageList_model');
        $this->load->model('card/Msg_model');
        $this->load->model('card/ServiceNotify_model');
    }

    protected function getCardTypeName($card_type)
    {
        global $CARD_TYPE_NAME_LIST;
        if (!array_key_exists($card_type, $CARD_TYPE_NAME_LIST)) {
            return false;
        }
        return $CARD_TYPE_NAME_LIST[$card_type];
    }

    protected function checkPageParams(&$pageNumber, &$pageSize, $maxSize = 100)
    {
        $pageNumber = intval($pageNumber);
        $pageSize   = intval($pageSize);
        $pageNumber >= 0 or $pageNumber = 1;
        $pageSize > 0 or $pageSize = 20;
        $pageSize < $maxSize or $pageSize = $maxSize;
    }

    public function checkCardExistsById($card_id)
    {
        $card_info = $this->Card_model->getById($card_id);
        if (empty($card_info)) {
            return $this->response_error(Error_Code::ERROR_CARD_NOT_EXISTS);
        }

        return $card_info;
    }

    public function checkCardExistsByWxCardId($wx_card_id)
    {
        $card_info = $this->Card_model->getByWxCardId($wx_card_id);
        if (empty($card_info)) {
            return $this->response_error(Error_Code::ERROR_CARD_NOT_EXISTS);
        }

        return $card_info;
    }

    public function checkCardState($current_state, $target_state_array, $in_target_state = true)
    {
        if (!$this->checkState($current_state, $target_state_array, $in_target_state)) {
            return $this->response_error(Error_Code::ERROR_CARD_STATE_INVALID);
        }
    }

    private function checkState($current_state, $target_state_array, $in_target_state)
    {
        if (!is_array($target_state_array)) {
            $target_state_array = [$target_state_array];
        }
        $check = in_array($current_state, $target_state_array);

        if ($check xor $in_target_state) {
            return false;
        }

        return true;
    }

    public function checkCardCodeState($current_state, $target_state_array, $in_target_state = true)
    {
        if (!$this->checkState($current_state, $target_state_array, $in_target_state)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_STATE_INVALID);
        }
    }

    public function checkCardCodeExistsByCode($code)
    {
        $code_info = $this->Code_model->getByCode($code);
        if (empty($code_info)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_NOT_EXISTS);
        }

        return $code_info;
    }

    public function checkCardCodeExistsById($code_id)
    {
        $code_info = $this->Code_model->getById($code_id);
        if (empty($code_info)) {
            return $this->response_error(Error_Code::ERROR_CARD_CODE_NOT_EXISTS);
        }

        return $code_info;
    }
}
