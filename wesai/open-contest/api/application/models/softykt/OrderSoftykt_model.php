<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';

class OrderSoftykt_model extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initOrderExt($orderId, $outTradeNo)
    {
        $params = [
            'fk_order'     => $orderId,
            'out_trade_no' => $outTradeNo,
        ];

        return $this->setTable($this->tableNameOrderPartnerSoftykt)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    public function listNeedSync($page, $size)
    {
        return $this->setTable($this->tableNameOrderPartnerSoftykt)
                    ->addQueryConditions('partner_order_number', 'null', 'is', true)
                    ->addQueryConditions('state', PARTNER_SOFTYKT_ORDER_STATE_OK)
                    ->doSelect($page, $size);
    }


    private function getUpdateColumns($params)
    {
        $returnVal = [];
        foreach ($params as $key => $val) {
            $returnVal[] = [$key, $val, '='];
        }

        return $returnVal;
    }

    public function modify($oid, $params)
    {
        $params = $this->getUpdateColumns($params);
        $result = $this->setTable($this->tableNameOrderPartnerSoftykt)
                       ->addUpdateColumns($params)
                       ->addQueryConditions('fk_order', $oid)
                       ->doUpdate();

        return $result;
    }

    public function consume($oid, $partner_order_detail_id, $partner_verify_number)
    {
        $toState = PARTNER_SOFTYKT_ORDER_STATE_CONSUMED;
        $fromState = PARTNER_SOFTYKT_ORDER_STATE_OK;

        return $this->setTable($this->tableNameOrderPartnerSoftykt)
                    ->addUpdateColumn('partner_order_detail_id', $partner_order_detail_id)
                    ->addUpdateColumn('partner_verify_number', $partner_verify_number)
                    ->addUpdateColumn('state', $toState)
                    ->addQueryConditions('fk_order', $oid)
                    ->addQueryConditions('state', $fromState)
                    ->doUpdate();
    }

    public function refund($oid, $partner_order_detail_id, $partner_verify_number)
    {
        $toState = PARTNER_SOFTYKT_ORDER_STATE_REFUNDED;
        $fromState = PARTNER_SOFTYKT_ORDER_STATE_OK;

        return $this->setTable($this->tableNameOrderPartnerSoftykt)
                    ->addUpdateColumn('partner_order_detail_id', $partner_order_detail_id)
                    ->addUpdateColumn('partner_verify_number', $partner_verify_number)
                    ->addUpdateColumn('state', $toState)
                    ->addQueryConditions('fk_order', $oid)
                    ->addQueryConditions('state', $fromState)
                    ->doUpdate();
    }

    public function getByOrderNumber($order_number)
    {
        $result = $this->setTable($this->tableNameOrderPartnerSoftykt)
                       ->addQueryConditions('partner_order_number', $order_number)
                       ->doSelect();
        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getByOid($oid)
    {
        $result = $this->setTable($this->tableNameOrderPartnerSoftykt)
                       ->addQueryConditions('fk_order', $oid)
                       ->doSelect();
        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

} // END class Msg_model
