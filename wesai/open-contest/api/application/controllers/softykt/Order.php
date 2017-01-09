<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Base.php';

class Order extends Base
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('softykt/OrderSoftykt_model');
    }

    public function list_need_sync_get()
    {
        $page = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $size = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        !empty($page) or $page = 1;

        $this->checkPageParams($page, $size);

        $partnerOrders = $this->OrderSoftykt_model->listNeedSync($page, $size);

        return $this->response_list($partnerOrders, count($partnerOrders), $page, $size);
    }

    public function update_post()
    {
        $oid = $this->post_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $partner_order_number = $this->post_check('order_number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $params = compact('partner_order_number');
        $affectedRows = $this->OrderSoftykt_model->modify($oid, $params);

        return $this->response_update($affectedRows);
    }

    public function consume_post()
    {
        $partner_order_number = $this->post_check('order_number', PARAM_NOT_NULL_NOT_EMPTY);
        $partner_order_detail_id = $this->post_check('order_detail_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $partner_verify_number = $this->post_check('verify_number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $partnerOrderInfo = $this->OrderSoftykt_model->getByOrderNumber($partner_order_number);
        if (empty($partnerOrderInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_ORDER_NOT_EXISTS);
        }

        if ($partnerOrderInfo['state'] != PARTNER_SOFTYKT_ORDER_STATE_OK) {
            return $this->response_error(Error_Code::ERROR_PARTNER_ORDER_STATE_INVALID);
        }

        $oid = $partnerOrderInfo['fk_order'];
        $affectedRows = $this->OrderSoftykt_model->consume($oid, $partner_order_detail_id, $partner_verify_number);

        return $this->response_update($affectedRows);
    }

    public function refund_post()
    {
        $partner_order_number = $this->post_check('order_number', PARAM_NOT_NULL_NOT_EMPTY);
        $partner_order_detail_id = $this->post_check('order_detail_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $partner_verify_number = $this->post_check('verify_number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $partnerOrderInfo = $this->OrderSoftykt_model->getByOrderNumber($partner_order_number);
        if (empty($partnerOrderInfo)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_ORDER_NOT_EXISTS);
        }

        if ($partnerOrderInfo['state'] != PARTNER_SOFTYKT_ORDER_STATE_OK) {
            return $this->response_error(Error_Code::ERROR_PARTNER_ORDER_STATE_INVALID);
        }

        $oid = $partnerOrderInfo['fk_order'];
        $affectedRows = $this->OrderSoftykt_model->refund($oid, $partner_order_detail_id, $partner_verify_number);

        return $this->response_update($affectedRows);
    }

    public function get_by_oid_get()
    {
        $oid = $this->get_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $result = $this->OrderSoftykt_model->getByOid($oid);
        if (empty($result)) {
            return $this->response_error(Error_Code::ERROR_PARTNER_ORDER_NOT_EXISTS);
        }

        return $this->response_object($result);
    }


}
