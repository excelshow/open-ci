<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';

class ServiceNotify_model extends ModelBase
{

    private $serviceApiHostConfig = [
        CARD_SERVICE_TYPE_VIP => [
            'host'     => 'api_host_open_user',
            'api_list' => [
                CARD_STATE_PASS     => 'vip/card/change_state_to_pass.json',
                CARD_STATE_NOT_PASS => 'vip/card/change_state_to_fail.json',
            ],
            'service_id_rename' => 'vip_card_id',
        ],
    ];

    private function getHost($service_type)
    {
        return $this->serviceApiHostConfig[$service_type]['host'];
    }

    private function getApi($service_type, $state)
    {
        return $this->serviceApiHostConfig[$service_type]['api_list'][$state];
    }

    private function getServiceIdName($service_type)
    {
        return $this->serviceApiHostConfig[$service_type]['service_id_rename'];
    }

    public function notify($corp_id, $service_type, $service_id, $state)
    {
        $params = compact('corp_id');
        $params[$this->getServiceIdName($service_type)] = $service_id;

        $requests[] = $this->request($this->getHost($service_type), $this->getApi($service_type, $state), $params, 'POST');

        $result = $this->result($requests);

        if (empty($result) || $result->error != 0) {
            log_message('error', 'notify service card state change failed . | ' . json_encode($requests));
            return false;
        }

        return $result;
    }

}
