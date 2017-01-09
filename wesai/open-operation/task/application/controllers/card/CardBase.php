<?php
/**
 * User: zhaodc
 * Date: 30/12/2016
 * Time: 10:29
 */
require_once __DIR__ . '/../Base.php';

class CardBase extends Base
{

    /**
     * CardBase constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $redisConfig = $this->config->item('redismq');
        $this->load->library('Redis_List_Client', $redisConfig);
    }

    private function obj2array($data)
    {
        return json_decode(json_encode($data), true);
    }

    protected function checkInnerApiResult($result, $keys)
    {
        $result = $this->obj2array($result);
        if (empty($result)) {
            return false;
        }

        if (!empty($keys)) {

            is_array($keys) or $keys = [$keys];

            foreach ($keys as $key) {
                if (!isset($result[$key])) {
                    log_message_v2('error', 'api result key not exists : ' . $key);
                    return false;
                }
            }
        }

        return $result;
    }

    protected function getCardById($card_id , $return_ext = 0)
    {
        $params = compact('card_id', 'return_ext');
        $card_info = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/get_by_id.json', $params, METHOD_GET);

        return $this->checkInnerApiResult($card_info, 'result');
    }

    protected function getCardByWxCardId($wx_card_id)
    {
        $params = compact('wx_card_id');
        $card_info = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/get_by_wx_card_id.json', $params, METHOD_GET);

        return $this->checkInnerApiResult($card_info, 'result');
    }

    protected function getCardByWxCardId($wx_card_id , $return_ext = 0)
    {
        $params = compact('wx_card_id', 'return_ext');
        $card_info = $this->callInnerApiDiy(API_HOST_OPEN_OPERATION, 'card/card/get_by_wx_card_id.json', $params, METHOD_GET);

        return $this->checkInnerApiResult($card_info, 'result');
    }

    protected function getAuthorizerApp($apppk)
    {
        $params = compact('apppk');
        $apppk_info = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'component/authorizer/get_by_pk.json', $params, METHOD_GET);

        return $this->checkInnerApiResult($apppk_info, 'result');
    }

    protected function getAuthorizerAccessToken($apppk)
    {
        $authorizer_info = $this->getAuthorizerApp($apppk);
        if (empty($authorizer_info)) {
            return false;
        }

        return $authorizer_info['result']['authorizer_access_token'];
    }

    protected function getWxCardCodeType($code_type)
    {
        global $CODE_TYPE_NAME_LIST;
        if (empty($CODE_TYPE_NAME_LIST[$code_type])) {
            return '';
        }

        return $CODE_TYPE_NAME_LIST[$code_type];
    }

    protected function getWxBoolValue($value)
    {
        return $value ? true : false;
    }

    protected function getConstantName($key, $value)
    {
        $key = strtoupper($key);
        global ${$key . '_NAME_LIST'};
        if (empty(${$key . '_NAME_LIST'}[$value])) {
            return '';
        }

        return ${$key . '_NAME_LIST'}[$value];
    }
}
