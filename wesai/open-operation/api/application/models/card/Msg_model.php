<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Msg_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('config');
        $this->load->library('Redis_List_Client', $this->config->config['redis']);
    }

    private function _send_msg($topic, $msg)
    {
        try {
            return $this->redis_list_client->LeftPush($topic, json_encode($msg));
        } catch (Exception $e) {
            log_message('error', 'redis|list|' . $e->getMessage());
            throw new Exception($e->getMessage(), Error_Code::ERROR_REDIS);

        }
    }

    public function sendMsgCardCreated($card_id)
    {
        $msg = compact('card_id');
        return $this->_send_msg(OPERATION_MQ_CARD_CREATED, $msg);
    }

    public function sendMsgCardUpdated($card_id)
    {
        $msg = compact('card_id');
        return $this->_send_msg(OPERATION_MQ_CARD_UPDATED, $msg);
    }

    public function sendMsgCardDeleted()
    {

    }

    public function sendMsgCardCodeUpdated($card_id, $code, $update_info)
    {
        $msg = compact('card_id', 'code', 'update_info');

        return $this->_send_msg(OPERATION_MQ_CARD_CODE_UPDATED, $msg);
    }

}
