<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . '/libraries/DIY_Model.php';
/**
 * 通知open-user vip model
 */
class Notify_user_model extends DIY_Model {

    /**
     * 会员卡种状态从微信审核中到微信审核失败
     * @param $vip_card_id
     * @param $corp_id
     * @return bool|void
     */
    public function changeStateToFail($vip_card_id, $corp_id)
    {
        $param = array(
            'vip_card_id' => $vip_card_id,
            'corp_id'     => $corp_id
        );
        $requests[] = $this->request('api_host_open_user', 'vip/card/change_state_to_fail.json', $param, 'POST');
        if (!empty($requests)) {
            return $this->result($requests);
        } else {
            return false;
        }
    }

    /**
     * 会员卡种状态从微信审核中到微信审核通过
     * @param $vip_card_id
     * @param $corp_id
     * @return bool|void
     */
    public function changeStateToPass($vip_card_id, $corp_id)
    {
        $param = array(
            'vip_card_id' => $vip_card_id,
            'corp_id'     => $corp_id
        );
        $requests[] = $this->request('api_host_open_user', 'vip/card/change_state_to_pass.json', $param, 'POST');
        if (!empty($requests)) {
            return $this->result($requests);
        } else {
            return false;
        }
    }








}
