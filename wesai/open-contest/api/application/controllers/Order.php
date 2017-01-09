<?php defined('BASEPATH') or exit('No direct script access allowed');

require_once __DIR__ . '/Base.php';

/**
 * 活动活动项目报名订单类
 *
 * @package default
 * @author  : zhaodechang@wesai.com
 **/
class Order extends Base
{
    /**
     * construct
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function doSingleOrderRequest($params)
    {
        if (empty($params['enrol_data_id'])) {
            return $this->response_error(Error_Code::ERROR_PARAM, '单人报名，enrol_data_id 必传非空');
        }
        $params['order']['fk_enrol_data'] = $params['enrol_data_id'];

        $enrolData = $this->EnrolData_model->getById($params['enrol_data_id']);
        if (empty($enrolData)) {
            return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
        }

        if ($enrolData['type'] != ENROL_DATA_TYPE_SINGLE) {
            return $this->response_error(Error_Code::ERROR_ENROL_DATA_TYPE_INVALID);
        }

        $itemInfo = $this->verifyContestItemExists($enrolData['fk_contest_items']);

        $this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

        if (strtotime($itemInfo['end_time']) < time()) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_CLOSED);
        }

        // 检查库存状态
        if ($itemInfo['max_stock'] > 0 && $itemInfo['cur_stock'] < $params['order']['copies']) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_QUOTA_FULFIL);
        }

        $contestInfo = $this->verifyContestExists($itemInfo['fk_contest']);

        $this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_SELLING);

        $params['order']['amount'] = $itemInfo['fee'] * $params['order']['copies'];
        $params['order']['owner_fk_corp'] = $contestInfo['fk_corp'];
        $params['order']['fk_contest'] = $contestInfo['pk_contest'];
        $params['order']['partner'] = $contestInfo['partner'];

        $params['itemInfo'] = $itemInfo;

        $orderId = $this->Order_model->create($params);

        if (empty($orderId)) {
            return $this->response_error(Error_Code::ERROR_ORDER_CREATE_FAILED);
        }

        $std = new stdClass();
        $std->orderId = $orderId;
        $std->amount = $params['order']['amount'];

        return $std;
    }

    private function doGroupOrderRequest($params)
    {
        if (empty($params['group_id'])) {
            return $this->response_error(Error_Code::ERROR_PARAM, '多人报名，group_id 必传非空');
        }
        $params['order']['fk_group'] = $params['group_id'];

        $groupInfo = $this->verifyGroupExists($params['order']['fk_group']);

        $this->verifyGroupState($groupInfo['state'], CONTEST_GROUP_STATE_INIT);

        $contestInfo = $this->verifyContestExists($groupInfo['fk_contest']);

        $this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_SELLING);

        // 获取所有报名资料
        $pageNumber = 1;
        $pageSize = 100;
        $enrolDataList = array();
        while (true) {
            $result = $this->EnrolData_model->listByGroupUser($params['order']['fk_group'], $pageNumber, $pageSize,
                null, ENROL_DATA_STATE_OK);
            if (empty($result)) {
                break;
            }
            $enrolDataList = array_merge($enrolDataList, $result);

            $pageNumber++;
        }

        if (empty($enrolDataList)) {
            return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
        }

        // $enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

        // 获取所有项目ID，排重
        $itemIds = array_keys(array_flip(array_column($enrolDataList, 'fk_contest_items')));

        $itemList = $this->ContestItem_model->getByIds($itemIds);
        if (empty($itemList)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
        }

        // 计算每个项目的名额数
        $enrolDataCountGroupByItemId = array_count_values(array_column($enrolDataList, 'fk_contest_items'));

        // 库存检验，截止时间检验
        $stockRestrictItemIds = array();
        foreach ($itemList as $item) {
            if ($item['max_stock'] > 0) {
                if ($item['cur_stock'] < $enrolDataCountGroupByItemId[$item['pk_contest_items']]) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_QUOTA_FULFIL, $item['name'] . ' 名额不足');
                }

                $stockRestrictItemIds[] = $item['pk_contest_items'];
            }

            if (strtotime($item['end_time']) < time()) {
                return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_QUOTA_FULFIL, $item['name'] . ' 已停止报名');
            }
        }

        $itemList = array_column($itemList, null, 'pk_contest_items');
        $amount = 0;
        foreach ($enrolDataCountGroupByItemId as $itemId => $stockCount) {
            $amount += $itemList[$itemId]['fee'] * $stockCount;
        }

        $stockRestrictItemIds = array_flip($stockRestrictItemIds);
        $enrolDataCountGroupByItemId = array_intersect_key($enrolDataCountGroupByItemId, $stockRestrictItemIds);

        $params['order']['amount'] = $amount;
        $params['order']['owner_fk_corp'] = $contestInfo['fk_corp'];
        $params['order']['fk_contest'] = $contestInfo['pk_contest'];
        $params['order']['partner'] = $contestInfo['partner'];

        $params['enrolDataCountGroupByItemId'] = $enrolDataCountGroupByItemId;
        $params['enrolDataIds'] = array();
        foreach ($enrolDataList as $enrolData) {
            $params['enrolDataIds'][$enrolData['pk_enrol_data']] = $itemList[$enrolData['fk_contest_items']]['max_verify'];
        }

        $orderId = $this->Order_model->create($params);

        if (empty($orderId)) {
            return $this->response_error(Error_Code::ERROR_ORDER_CREATE_FAILED);
        }

        $std = new stdClass();
        $std->orderId = $orderId;
        $std->amount = $params['order']['amount'];

        return $std;
    }

    private function doTeamOrderRequest($params)
    {
        if (empty($params['team_id'])) {
            return $this->response_error(Error_Code::ERROR_PARAM, '团队报名，team_id 必传非空');
        }
        $params['order']['fk_team'] = $params['team_id'];

        $teamInfo = $this->verifyTeamExists($params['order']['fk_team']);

        $this->verifyTeamState($teamInfo['state'], CONTEST_TEAM_STATE_INIT);

        if ($teamInfo['max_member_count'] != $teamInfo['cur_member_count']) {
            return $this->response_error(Error_Code::ERROR_TEAM_ENROL_DATA_NOT_ENOUGH);
        }

        $itemInfo = $this->verifyContestItemExists($teamInfo['fk_contest_items']);

        $this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

        if (strtotime($itemInfo['end_time']) < time()) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_QUOTA_FULFIL, $itemInfo['name'] . ' 已停止报名');
        }

        if ($itemInfo['team_max_stock'] > 0 && $itemInfo['team_cur_stock'] < 1) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_QUOTA_FULFIL, $itemInfo['name'] . ' 团队名额不足');
        }

        $contestInfo = $this->verifyContestExists($itemInfo['fk_contest']);

        $this->verifyContestState($contestInfo['publish_state'], CONTEST_PUBLISH_STATE_SELLING);

        // 获取所有报名资料
        $pageNumber = 1;
        $pageSize = 100;
        $enrolDataList = array();
        while (true) {
            $result = $this->EnrolData_model->listByTeamUser($params['order']['fk_team'], $pageNumber, $pageSize, null,
                ENROL_DATA_STATE_OK);
            if (empty($result)) {
                break;
            }
            $enrolDataList = array_merge($enrolDataList, $result);

            $pageNumber++;
        }

        if (empty($enrolDataList)) {
            return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
        }

        $enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');


        $params['order']['amount'] = $itemInfo['fee'];
        $params['order']['owner_fk_corp'] = $contestInfo['fk_corp'];
        $params['order']['fk_contest'] = $contestInfo['pk_contest'];
        $params['order']['partner'] = $contestInfo['partner'];

        $params['enrolDataIds'] = $enrolDataIds;
        $params['itemInfo'] = $itemInfo;

        $orderId = $this->Order_model->create($params);

        if (empty($orderId)) {
            return $this->response_error(Error_Code::ERROR_ORDER_CREATE_FAILED);
        }

        $std = new stdClass();
        $std->orderId = $orderId;
        $std->amount = $params['order']['amount'];

        return $std;
    }

    /**
     * 新增订单
     */
    public function add_post()
    {
        $this->post_check('seller_corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('seller_app_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('uid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('openid', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('team_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('group_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('enrol_data_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('ip', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('shipping_addr', PARAM_NULL_NOT_EMPTY);
        $this->post_check('copies', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $params = $this->get_request_params();

        $params['order']['seller_fk_corp'] = $params['seller_corp_id'];
        $params['order']['fk_component_authorizer_app'] = $params['seller_app_id'];
        $params['order']['fk_user'] = $params['uid'];
        $params['order']['type'] = $params['type'];
        $params['order']['shipping_addr'] = !empty($params['shipping_addr']) ? $params['shipping_addr'] : '';
        $params['order']['copies'] = 1;
        $params['order']['channel_account'] = $params['openid'];

        switch ($params['type']) {
            case ORDER_TYPE_SINGLE:
                $params['order']['copies'] = !empty($params['copies']) ? $params['copies'] : 1;
                $params['order']['copies'] > 0 or $params['order']['copies'] = 1;

                $result = $this->doSingleOrderRequest($params);
                break;
            case ORDER_TYPE_GROUP:
                $result = $this->doGroupOrderRequest($params);
                break;
            case ORDER_TYPE_TEAM:
                $result = $this->doTeamOrderRequest($params);
                break;
            default:
                return $this->response_error(Error_Code::ERROR_PARAM, 'type 非法');
                break;
        }

        $std = new stdClass();
        $std->orderid = $result->orderId;

        $this->load->model('Msg_model');

        //更新订单状态到支付中
        $outTradeNo = $this->updateStateToPaying($std->orderid);
        if ($outTradeNo < 0) {
            return $this->response_error(Error_Code::ERROR_ORDER_PREPAY_FAILED);
        }

        // 0元报名
        if ($result->amount == 0) {
            $this->updateStateToCompleted($std->orderid);
        }

        $std->out_trade_no = $outTradeNo;
        $std->amount = $result->amount;

        return $this->response_object($std);
    }


    private function updateStateToPaying($oid)
    {
        // 获取订单资料
        $orderInfo = $this->verifyOrderExists($oid);

        $this->verifyOrderState($orderInfo['state'], ORDER_STATE_INIT);

        return $this->Order_model->changeStateToPaying($orderInfo);
    }

    /**
     * 更新订单状态到“支付失败”
     */
    public function change_state_to_failed_post()
    {
        $oid = $this->post_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        // 获取订单资料
        $orderInfo = $this->verifyOrderExists($oid);

        $this->verifyOrderState($orderInfo['state'], [ORDER_STATE_INIT, ORDER_STATE_PAYING]);

        switch ($orderInfo['type']) {
            case ORDER_TYPE_SINGLE:
                $enrolData = $this->EnrolData_model->getById($orderInfo['fk_enrol_data']);
                if (empty($enrolData)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }
                $itemInfo = $this->verifyContestItemExists($enrolData['fk_contest_items']);

                $orderInfo['itemInfo'] = $itemInfo;
                break;
            case ORDER_TYPE_GROUP:
                // 获取所有报名资料
                $pageNumber = 1;
                $pageSize = 100;
                $enrolDataList = array();
                while (true) {
                    $result = $this->EnrolData_model->listByGroupUser($orderInfo['fk_group'], $pageNumber, $pageSize,
                        null, ENROL_DATA_STATE_OK);
                    if (empty($result)) {
                        break;
                    }
                    $enrolDataList = array_merge($enrolDataList, $result);

                    $pageNumber++;
                }

                if (empty($enrolDataList)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }

                // 计算每个项目的名额数
                $enrolDataCountGroupByItemId = array_count_values(array_column($enrolDataList, 'fk_contest_items'));

                // 获取所有项目ID，排重
                $itemIds = array_keys(array_flip(array_column($enrolDataList, 'fk_contest_items')));

                $itemList = $this->ContestItem_model->getByIds($itemIds);
                if (empty($itemList)) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
                }

                // 库存检验
                $stockRestrictItemIds = array();
                foreach ($itemList as $item) {
                    if ($item['max_stock'] > 0) {
                        $stockRestrictItemIds[] = $item['pk_contest_items'];
                    }
                }

                $stockRestrictItemIds = array_flip($stockRestrictItemIds);
                $enrolDataCountGroupByItemId = array_intersect_key($enrolDataCountGroupByItemId, $stockRestrictItemIds);

                $orderInfo['enrolDataCountGroupByItemId'] = $enrolDataCountGroupByItemId;

                break;
            case ORDER_TYPE_TEAM:
                $teamInfo = $this->verifyTeamExists($orderInfo['fk_team']);

                $itemInfo = $this->verifyContestItemExists($teamInfo['fk_contest_items']);

                $orderInfo['itemInfo'] = $itemInfo;
                break;
        }

        $affectedRows = $this->Order_model->changeStateToFailed($orderInfo, $orderInfo['state']);

        return $this->response_update($affectedRows);
    }

    /**
     * 更新订单状态到“支付完成”
     */
    public function change_state_to_completed_post()
    {
        $oid = $this->post_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $paidTime = $this->post_check('paid_time', PARAM_NOT_NULL_NOT_EMPTY);
        $channelId = $this->post_check('channel_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $transactionId = $this->post_check('transaction_id', PARAM_NOT_NULL_NOT_EMPTY);
        $amountPay = $this->post_check('amount_pay', PARAM_NOT_NULL_EMPTY);

        $result = $this->updateStateToCompleted($oid, $paidTime, $channelId, $transactionId, $amountPay);

        if ($result <= 0) {
            return $this->response_error($result);
        }

        return $this->response_update($result);
    }

    /**
     * 更新订单状态为“支付完成”， 同时更新完成支付的用户账户和支付通道交易ID
     *
     * @param  integer $oid 订单ID
     * @param string   $paidTime
     *
     * @param int      $channelId
     *
     * @param string   $transactionId
     *
     * @param int      $amountPay
     *
     * @return int
     * @internal param string $channel_account 完成支付的用户账户
     * @internal param string $channel_transaction_id 支付通道交易ID
     * @internal param string $channel_id
     */
    private function updateStateToCompleted($oid, $paidTime = '', $channelId = 0, $transactionId = '', $amountPay = 0)
    {
        $orderInfo = $this->verifyOrderExists($oid);

        $verifyCodeNeedData = $this->getVerifyCodeNeedData($orderInfo);

        $this->verifyOrderState($orderInfo['state'], ORDER_STATE_PAYING);

        $result = $this->Order_model->changeStateToCompleted($orderInfo, $paidTime, $channelId, $transactionId,
            $amountPay, $verifyCodeNeedData);

        $this->load->model('Msg_model');

        $this->Msg_model->sendMsgOrderCompleted($oid);

        switch ($orderInfo['type']) {
            case ORDER_TYPE_SINGLE:
                $enrolDataInfo = $this->EnrolData_model->getById($orderInfo['fk_enrol_data']);
                if (empty($enrolDataInfo)) {
                    return $result;
                }
                $contestItemInfo = $this->ContestItem_model->getById($enrolDataInfo['fk_contest_items']);
                if (empty($contestItemInfo)) {
                    return $result;
                }
                $itemCount = array(
                    $contestItemInfo['pk_contest_items'] => $orderInfo['copies'],
                );
                $rs = $this->ContestItem_model->increaseEnrolDataCount($itemCount);
                if (empty($rs)) {
                    log_message_v2(
                        'error',
                        array(
                            'msg'    => 'increaseEnrolDataCount',
                            'params' => $itemCount,
                        )
                    );
                }
                break;
            case ORDER_TYPE_GROUP:
                $groupInfo = $this->Group_model->getById($orderInfo['fk_group']);
                if (empty($groupInfo)) {
                    return $result;
                }

                $enrolDataList = $this->EnrolData_model->listByGroupUser($groupInfo['pk_group'], 0, 0, null,
                    ENROL_DATA_STATE_OK);
                if (empty($enrolDataList)) {
                    return $result;
                }
                $itemIds = array_column($enrolDataList, 'fk_contest_items');
                $itemCount = array_count_values($itemIds);

                $rs = $this->ContestItem_model->increaseEnrolDataCount($itemCount);
                break;
            case ORDER_TYPE_TEAM:
                $teamInfo = $this->Team_model->getById($orderInfo['fk_team']);
                if (empty($teamInfo)) {
                    return $result;
                }

                $itemCount = array(
                    $teamInfo['fk_contest_items'] => $teamInfo['max_member_count'],
                );

                $rs = $this->ContestItem_model->increaseEnrolDataCount($itemCount);
                if (empty($rs)) {
                    log_message_v2(
                        'error',
                        array(
                            'msg'    => 'increaseEnrolDataCount',
                            'params' => $itemCount,
                        )
                    );
                }
                break;
        }

        return $result;
    }

    /**
     * 更新订单状态到“订单关闭”
     */
    public function change_state_to_closed_post()
    {
        $oid = $this->post_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        // 获取订单资料
        $orderInfo = $this->verifyOrderExists($oid);

        $this->verifyOrderState($orderInfo['state'], ORDER_STATE_COMPLETED);

        $affected_rows = $this->Order_model->changeStateToClosed($oid);

        return $this->response_update($affected_rows);
    }


    /**
     * 我的订单列表
     */
    public function list_by_uid_get()
    {
        $sellerCorpId = $this->get_check('seller_corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $sellerAppId = $this->get_check('seller_app_id', PARAM_NOT_NULL_NOT_EMPTY);
        $uid = $this->get_check('uid', PARAM_NOT_NULL_NOT_EMPTY);
        $state = $this->get_check('state', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $page = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $size = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $rtnContestInfo = $this->get_check('contest_info', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $this->checkPageParams($page, $size);

        $total = 0;
        $orderList = $this->Order_model->listByUid($sellerCorpId, $sellerAppId, $uid, $state, $page, $size, $total);
        if (empty($orderList)) {
            return $this->response_list(array(), $total);
        }

        $verifiableOrderIds = array();
        foreach ($orderList as $key => $order) {
            $orderList[$key]['verifiable_number'] = 0;
            if ($order['state'] != ORDER_STATE_CLOSED) {
                continue;
            }
            $verifiableOrderIds[] = $order['pk_order'];
        }
        if (!empty($verifiableOrderIds)) {
            $this->load->model('EnrolData_model');
            $orderVerifyInfo = $this->EnrolData_model->listVerifyInfoByOrderIds($verifiableOrderIds);
            if (!empty($orderVerifyInfo)) {
                $orderVerifyInfo = array_column($orderVerifyInfo, null, 'fk_order');
                foreach ($orderList as $key => $order) {
                    if (!array_key_exists($order['pk_order'], $orderVerifyInfo)) {
                        $orderList[$key]['verifiable_number'] = 0;
                        continue;
                    }

                    $orderList[$key]['verifiable_number'] = $orderVerifyInfo[$order['pk_order']]['max_verify'] - $orderVerifyInfo[$order['pk_order']]['verify_number'];
                }
            }
        }

        // 如果不需要返回活动详情
        if ($rtnContestInfo != 1) {
            return $this->response_list($orderList, $total, $page, $size);
        }

        $contestIds = array_flip(array_flip(array_column($orderList, 'fk_contest')));

        $contestList = $this->Contest_model->getByIds($contestIds);

        if (empty($contestList) || count($contestIds) != count($contestList)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_NOT_EXISTS);
        }

        $contestList = array_column($contestList, null, 'pk_contest');

        foreach ($orderList as $key => $order) {
            $orderList[$key]['contest_info'] = $contestList[$order['fk_contest']];
        }

        return $this->response_list($orderList, $total, $page, $size);
    }

    public function search_manage_get()
    {
        $sellerCorpId = $this->get_check('seller_corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $contestName = $this->get_check('contest_name', PARAM_NULL_EMPTY);
        $state = $this->get_check('state', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $orderId = $this->get_check('oid', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $start = $this->get_check('min_date', PARAM_NULL_EMPTY);
        $end = $this->get_check('max_date', PARAM_NULL_EMPTY);
        $pageNumber = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $pageSize = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $this->checkPageParams($pageNumber, $pageSize);

        !empty($start) or $start = '1970-01-01';
        !empty($end) or $end = date('Y-m-d 23:59:59');

        $start = strtotime(date('Y-m-d 00:00:00', strtotime($start)));
        $end = strtotime(date('Y-m-d 23:59:59', strtotime($end)));

        $rs = array();
        $total = 0;
        if (!empty($orderId)) {
            $orderInfo = $this->verifyOrderExists($orderId);

            $contestInfo = $this->verifyContestExists($orderInfo['fk_contest']);

            $orderInfo['contest_info'] = $contestInfo;
            $orderInfo['contest_items_info'] = null;

            if (in_array($orderInfo['type'], [ORDER_TYPE_SINGLE, ORDER_TYPE_TEAM])) {
                $itemInfo = $this->verifyContestItemExists($orderInfo['fk_contest_items']);

                $orderInfo['contest_items_info'] = $itemInfo;
            }
            $rs[] = $orderInfo;
            $total++;

            return $this->response_list($rs, $total, $pageNumber, $pageSize);
        }

        $params = compact(
            'sellerCorpId',
            'contestName',
            'state',
            'orderId',
            'pageNumber',
            'pageSize',
            'start',
            'end'
        );


        $result = $this->Order_model->searchManage($params);
        $total = $result->total;
        if (empty($result->result)) {
            return $this->response_list(array(), 0, $pageNumber, $pageSize);
        }

        $orderIds = array();
        foreach ($result->result as $key => $value) {
            $orderIds[] = $value;
        }

        $orderList = $this->Order_model->getByIds($orderIds);
        if (empty($orderList)) {
            return $this->response_list(array(), 0, $pageNumber, $pageSize);
        }

        $contestIds = array_flip(array_flip(array_column($orderList, 'fk_contest')));

        $contestList = $this->Contest_model->getByIds($contestIds);

        if (empty($contestList) || count($contestList) != count($contestIds)) {
            return $this->response_list(array(), 0, $pageNumber, $pageSize);
        }

        $contestList = array_column($contestList, null, 'pk_contest');

        foreach ($orderList as $key => $order) {
            $orderList[$key]['contest_info'] = $contestList[$order['fk_contest']];
        }

        return $this->response_list($orderList, $total, $pageNumber, $pageSize);
    }

    /**
     * 获取订单详情
     */
    public function get_get()
    {
        $oid = $this->get_check('oid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $rtnContestInfo = $this->get_check('contest_info', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        // 获取订单资料
        $orderInfo = $this->verifyOrderExists($oid);

        $enrolDataList = array();
        switch ($orderInfo['type']) {
            case ORDER_TYPE_SINGLE:
                $enrolDataList = $this->EnrolData_model->getByIds($orderInfo['fk_enrol_data']);
                if (empty($enrolDataList)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }
                break;
            case ORDER_TYPE_GROUP:
                $this->verifyGroupExists($orderInfo['fk_group']);
                // 获取报名资料列表
                $enrolDataList = $this->EnrolData_model->listByGroupUser($orderInfo['fk_group'], 0, 0, null,
                    ENROL_DATA_STATE_OK);
                if (empty($enrolDataList)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }
                break;
            case ORDER_TYPE_TEAM:
                $this->verifyTeamExists($orderInfo['fk_team']);
                // 获取报名资料列表
                $enrolDataList = $this->EnrolData_model->listByTeamUser($orderInfo['fk_team'], 0, 0, null,
                    ENROL_DATA_STATE_OK);
                if (empty($enrolDataList)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }
                break;
        }

        $enrolDataIds = array_column($enrolDataList, 'pk_enrol_data');

        $contestItemIds = array_column($enrolDataList, 'fk_contest_items');

        $contestItemIds = array_flip(array_flip($contestItemIds));

        $contestItemList = $this->ContestItem_model->getByIds($contestItemIds);
        if (empty($contestItemList) || count($contestItemList) != count($contestItemIds)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
        }

        $contestItemList = array_column($contestItemList, null, 'pk_contest_items');

        // 获取报名详情
        $enrolDataDetailList = $this->EnrolDataDetail_model->getByEnrolDataIds($enrolDataIds);
        // if (empty($enrolDataDetailList)) {
        // 	return $this->response_error(Error_Code::ERROR_ENROL_DATA_DETAIL_NOT_EXISTS);
        // }

        // 获取核销码列表
        $verifyCodeList = $this->EnrolData_model->listVerifyCodeByEnrolDataIds($enrolDataIds);
        // if (empty($verifyCodeList)) {
        // return $this->response_error(Error_Code::ERROR_ENROL_DATA_VERIFY_CODE_NOT_EXISTS);
        // $verifyCodeList = array();
        // }

        foreach ($enrolDataList as $k => $enrolData) {
            if (empty($enrolDataDetailList)) {
                $enrolDataList[$k]['enrol_data_detail'] = array();
            } else {
                // 合并报名资料详情到报名资料中
                foreach ($enrolDataDetailList as $key => $enrolDataDetail) {
                    if ($enrolData['pk_enrol_data'] == $enrolDataDetail['fk_enrol_data']) {
                        $enrolDataList[$k]['enrol_data_detail'][] = $enrolDataDetail;
                        unset($enrolDataDetailList[$key]);
                    }
                }
            }

            if (empty($verifyCodeList)) {
                $enrolDataList[$k]['verify_code'] = array();
            } else {
                // 获取核销码列表
                foreach ($verifyCodeList as $vkey => $verifyCode) {
                    if ($verifyCode['fk_enrol_data'] == $enrolData['pk_enrol_data'] &&
                        $verifyCode['fk_order'] == $oid
                    ) {
                        $enrolDataList[$k]['verify_code'][] = $verifyCode;
                        unset($verifyCodeList[$vkey]);
                    }
                }
            }
        }

        foreach ($contestItemList as $k => $v) {
            foreach ($enrolDataList as $key => $val) {
                if ($v['pk_contest_items'] == $val['fk_contest_items']) {
                    $contestItemList[$k]['enrol_data'][] = $val;
                    unset($enrolDataList[$key]);
                }
            }
            $contestItemList[$k]['enrol_form_titles'] = array_column($contestItemList[$k]['enrol_data'][0]['enrol_data_detail'],
                'title');
            $contestItemList[$k]['copies'] = count($contestItemList[$k]['enrol_data']);
        }


        $orderInfo['contest_item_list'] = array_merge(array(), $contestItemList);

        // 不获取活动资料，直接返回订单数据
        if ($rtnContestInfo != 1) {
            return $this->response_object($orderInfo);
        }

        $contestInfo = $this->verifyContestExists($orderInfo['fk_contest']);

        $orderInfo['contest_info'] = $contestInfo;

        return $this->response_object($orderInfo);
    }

    public function get_by_team_get()
    {
        $teamId = $this->get_check('team_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $contestInfo = $this->get_check('contest_info', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $orderInfo = $this->Order_model->getByTeam($teamId);
        if (empty($orderInfo)) {
            return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
        }

        if ($contestInfo == 1) {
            $orderInfo['contest_info'] = $this->verifyContestExists($orderInfo['fk_contest']);
        }

        return $this->response_object($orderInfo);
    }

    public function get_by_group_get()
    {
        $groupId = $this->get_check('group_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $contestInfo = $this->get_check('contest_info', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $orderInfo = $this->Order_model->getByGroup($groupId);
        if (empty($orderInfo)) {
            return $this->response_error(Error_Code::ERROR_ORDER_NOT_EXISTS);
        }

        if ($contestInfo == 1) {
            $orderInfo['contest_info'] = $this->verifyContestExists($orderInfo['fk_contest']);
        }

        return $this->response_object($orderInfo);
    }


    public function list_expired_get()
    {
        $pageNumber = $this->get_check('page', PARAM_NOT_NULL_NOT_EMPTY);
        $pageSize = $this->get_check('size', PARAM_NOT_NULL_NOT_EMPTY);

        $this->checkPageParams($pageNumber, $pageSize);

        $result = $this->Order_model->listExpired($pageNumber, $pageSize);

        return $this->response_list($result, count($result), $pageNumber, $pageSize);
    }

    public function list_get()
    {
        $this->get_check('owner_corp_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('seller_corp_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('uid', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('cid', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('type', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('state', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->get_check('date_start', PARAM_NULL_NOT_EMPTY);
        $this->get_check('date_end', PARAM_NULL_NOT_EMPTY);
        $this->get_check('oid', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->get_check('transaction_id', PARAM_NULL_EMPTY);
        $this->get_check('page', PARAM_NOT_NULL_NOT_EMPTY);
        $this->get_check('size', PARAM_NOT_NULL_NOT_EMPTY);
        $this->get_check('contest_info', PARAM_NULL_EMPTY);
        $this->get_check('out_trade_no', PARAM_NULL_EMPTY);

        $params = $this->get_request_params();

        $page = $params['page'];
        $size = $params['size'];
        $this->checkPageParams($page, $size);

        $total = 0;
        $orderList = $this->Order_model->listByPage($params, $page, $size, $total);

        if (empty($params['contest_info'])) {
            return $this->response_list($orderList, $total, $page, $size);
        }

        $contestIds = array_column($orderList, 'fk_contest');
        $contestIds = array_flip(array_flip($contestIds));
        if (empty($contestIds)) {
            return $this->response_list($orderList, $total, $page, $size);
        }

        $contestList = $this->Contest_model->getByIds($contestIds);
        if (empty($contestList)) {
            return $this->response_list($orderList, $total, $page, $size);
        }

        $contestList = array_column($contestList, null, 'pk_contest');

        foreach ($orderList as $key => $order) {
            $orderList[$key]['contest_info'] = $contestList[$order['fk_contest']];
        }

        return $this->response_list($orderList, $total, $page, $size);
    }

    public function get_total_get()
    {
        $seller_corp_id = $this->get_check('seller_corp_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $start_time = $this->get_check('start_time', PARAM_NULL_NOT_EMPTY);
        $end_time = $this->get_check('end_time', PARAM_NULL_NOT_EMPTY);

        $result = $this->Order_model->getTotal($seller_corp_id, $start_time, $end_time);

        return $this->response_object($result);
    }

    public function get_payinfo_by_out_trade_no_get()
    {
        $outTradeNo = $this->get_check('out_trade_no', PARAM_NOT_NULL_NOT_EMPTY);
        $oid = intval(substr($outTradeNo, -10));

        $orderInfo = $this->verifyOrderExists($oid);

        $this->verifyOrderState($orderInfo['state'], ORDER_STATE_PAYING);

        $contestInfo = $this->verifyContestExists($orderInfo['fk_contest']);

        $itemList = array();
        switch ($orderInfo['type']) {
            case ORDER_TYPE_SINGLE:
                $enrolDataInfo = $this->verifyEnrolDataExists($orderInfo['fk_enrol_data']);

                $contestItemInfo = $this->verifyContestItemExists($enrolDataInfo['fk_contest_items']);
                $itemList[] = array(
                    'name'   => $contestItemInfo['name'],
                    'fee'    => $contestItemInfo['fee'],
                    'copies' => $orderInfo['copies'],
                );
                break;
            case ORDER_TYPE_GROUP:
                $enrolDataList = $this->EnrolData_model->listByGroupUser($orderInfo['fk_group'], 0, 0, null,
                    ENROL_DATA_STATE_OK);
                if (empty($enrolDataList)) {
                    return $this->response_error(Error_Code::ERROR_ENROL_DATA_NOT_EXISTS);
                }

                $itemIds = array_column($enrolDataList, 'fk_contest_items');
                $itemEnrolDataCount = array_count_values($itemIds);

                $items = $this->ContestItem_model->getByIds(array_keys($itemEnrolDataCount));
                if (empty($items)) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
                }

                $items = array_column($items, null, 'pk_contest_items');

                foreach ($itemEnrolDataCount as $itemId => $count) {
                    $itemList[] = array(
                        'name'   => $items[$itemId]['name'],
                        'fee'    => $items[$itemId]['fee'],
                        'copies' => $count,
                    );
                }

                break;
            case ORDER_TYPE_TEAM:
                $teamInfo = $this->verifyTeamExists($orderInfo['fk_team']);

                $contestItemInfo = $this->verifyContestItemExists($teamInfo['fk_contest_items']);

                $itemList[] = array(
                    'name'   => $contestItemInfo['name'],
                    'fee'    => $contestItemInfo['fee'],
                    'copies' => 1,
                );
                break;
        }

        $expiresIn = ORDER_PAY_TIME_LIMIT * 60 - (time() - strtotime($orderInfo['ctime']));
        if ($expiresIn < 0) {
            return $this->response_error(Error_Code::ERROR_ORDER_ALREADY_EXPIRED);
        }

        $payInfo = array();
        $payInfo['contest_info']['name'] = $contestInfo['name'];
        $payInfo['contest_info']['location'] = $contestInfo['location'];
        $payInfo['contest_info']['sdate_start'] = $contestInfo['sdate_start'];
        $payInfo['contest_info']['sdate_end'] = $contestInfo['sdate_end'];

        $payInfo['item_list'] = $itemList;

        $result = array();
        $result['authorizer_apppk'] = $orderInfo['fk_component_authorizer_app'];
        $result['openid'] = $orderInfo['channel_account'];
        $result['out_trade_no'] = $outTradeNo;
        $result['amount'] = $orderInfo['amount'];
        $result['ctime'] = $orderInfo['ctime'];
        $result['expire_seconds'] = $expiresIn;
        $result['user_id'] = $orderInfo['fk_user'];
        $result['remark'] = $payInfo['contest_info']['name'];
        $result['seller_corp_id'] = $orderInfo['seller_fk_corp'];
        $result['owner_corp_id'] = $orderInfo['owner_fk_corp'];

        $result['payinfo'] = $payInfo;

        return $this->response_object($result);
    }

    private function getVerifyCodeNeedData($orderInfo)
    {
        $contestItemInfo = array();
        $enrolData = array();
        switch ($orderInfo['type']) {
            case ORDER_TYPE_SINGLE:
                $enrolData = $this->EnrolData_model->getById($orderInfo['fk_enrol_data']);
                $contestItemInfo = $this->verifyContestItemExists($enrolData['fk_contest_items']);
                break;
            case ORDER_TYPE_GROUP:
                $enrolData = $this->EnrolData_model->listByGroupUser($orderInfo['fk_group'], 0, 0, 0,
                    ENROL_DATA_STATE_OK);
                $contestItemIds = array_keys(array_flip(array_column($enrolData, 'fk_contest_items')));
                $contestItemInfo = $this->ContestItem_model->getByIds($contestItemIds);
                if (count($contestItemInfo) != count($contestItemIds)) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
                }

                break;
            case ORDER_TYPE_TEAM:
                $enrolData = $this->EnrolData_model->listByTeamUser($orderInfo['fk_team'], 0, 0, 0,
                    ENROL_DATA_STATE_OK);
                $contestItemInfo = $this->verifyContestItemExists($enrolData[0]['fk_contest_items']);
                break;
        }

        if (empty($contestItemInfo)) {
            return $this->response_error(Error_Code::ERROR_CONTEST_ITEMS_NOT_EXISTS);
        }

        return array(
            'contestItemInfo' => $contestItemInfo,
            'enrolData'       => $enrolData,
        );
    }
} // END class
