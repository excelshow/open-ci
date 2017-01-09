<?php
/**
 * Created by PhpStorm.
 * User: weizheng
 * Date: 2016/12/7
 * Time: 11:27
 */
require_once __DIR__ . '/../ModelBase.php';

class Order_model extends ModelBase{
    /**
     * @param $out_trade_nos
     * @return mixed
     */
    public function get_dist_by_out_trade_nos($out_trade_nos){
        $params = array(
            'service' => ORDER_PAY_SERVICE,
            'out_trade_nos' => implode(',', $out_trade_nos),
        );

        $requests[] = $this->request(API_HOST_OPEN_PAY, 'order/get_dist_by_out_trade_nos.json', $params, METHOD_GET);

        $result = $this->result($requests);

        return $this->checkInternalApiResult($result, $requests);
    }
}