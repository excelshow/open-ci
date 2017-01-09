<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/CardBase.php';

/**
 * User: zhaodc
 * Date: 28/12/2016
 * Time: 09:54
 */
class MemberCard extends CardBase
{

    /**
     * Card constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function save_wx_pic_url_post()
    {
        $this->post_check('card_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('background_pic_url', PARAM_NOT_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $card_id = $params['card_id'];

        $this->checkCardExistsById($card_id);

        $affected_rows = $this->MemberCard_model->saveWxPicUrl($card_id, $params['background_pic_url']);

        return $this->response_update($affected_rows);
    }

}
