<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/ModelBase.php';

/**
 * 活动活动项目订单类
 *
 * @author: zhaodechang@wesai.com
 **/
class Order_model extends ModelBase
{

    /**
     * init
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('EnrolData_model');
        $this->load->model('ContestItem_model');
        $this->load->model('Group_model');
        $this->load->model('Team_model');
    }

    private function createOrder($params)
    {
        $params['utime'] = null;

        return $this->setTable($this->tableNameOrder)
                    ->addInsertColumns($params, ['shipping_addr', 'utime'])
                    ->doInsert();
    }

    private function createSingleOrder($params)
    {
        $itemInfo = $params['itemInfo'];
        $orderId = $this->createOrder($params['order']);
        if (empty($orderId)) {
            log_message_v2('error');

            return false;
        }

        if ($itemInfo['max_stock'] > 0) {
            $affectedRows = $this->ContestItem_model->reduceCurStocks(array($itemInfo['pk_contest_items'] => $params['order']['copies']),
                $this);
            if (empty($affectedRows)) {
                log_message_v2('error');

                return false;
            }
        }

        return $orderId;
    }

    private function createGroupOrder($params)
    {
        $enrolDataCountGroupByItemId = $params['enrolDataCountGroupByItemId'];

        $orderId = $this->createOrder($params['order']);
        if (empty($orderId)) {
            log_message_v2('error');

            return false;
        }

        if (!empty($enrolDataCountGroupByItemId)) {
            $affectedRows = $this->ContestItem_model->reduceCurStocks($enrolDataCountGroupByItemId, $this);
            if ($affectedRows != count($enrolDataCountGroupByItemId)) {
                log_message_v2('error');

                return false;
            }
        }

        return $orderId;
    }

    private function createTeamOrder($params)
    {
        $itemInfo = $params['itemInfo'];

        $orderId = $this->createOrder($params['order']);
        if (empty($orderId)) {
            log_message_v2('error');

            return false;
        }

        if ($itemInfo['team_max_stock'] > 0) {
            $affectedRows = $this->ContestItem_model->reduceTeamCurStock($itemInfo['pk_contest_items'], $this);
            if (empty($affectedRows)) {
                log_message_v2('error');

                return false;
            }
        }

        return $orderId;
    }

    public function create($params)
    {
        try {
            $this->beginTransaction();

            switch ($params['type']) {
                case ORDER_TYPE_SINGLE:
                    $orderId = $this->createSingleOrder($params);
                    break;
                case ORDER_TYPE_GROUP:
                    $orderId = $this->createGroupOrder($params);
                    break;
                case ORDER_TYPE_TEAM:
                    $orderId = $this->createTeamOrder($params);
                    break;
                default:
                    $this->rollBack();
                    log_message_v2('error');

                    return false;
                    break;
            }

            if (empty($orderId)) {
                $this->rollBack();

                return false;
            }

            $this->commit();

            return $orderId;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function changeStateToPaying($orderInfo)
    {
        try {
            $this->beginTransaction();
            $out_trade_no = $this->createOutTradeNo($orderInfo['pk_order']);

            $affectedRows = $this->setTable($this->tableNameOrder)
                                 ->addUpdateColumns('state', ORDER_STATE_PAYING)
                                 ->addUpdateColumns('out_trade_no', $out_trade_no)
                                 ->addQueryConditions('pk_order', $orderInfo['pk_order'])
                                 ->addQueryConditions('state', ORDER_STATE_INIT)
                                 ->doUpdate();

            if ($affectedRows != 1) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            $params = array(
                'fk_order'   => $orderInfo['pk_order'],
                'from_state' => ORDER_STATE_INIT,
                'to_state'   => ORDER_STATE_PAYING,
                'remark'     => __METHOD__,
            );

            $orderLogId = $this->setTable($this->tableNameOrderStateLog)
                               ->addInsertColumns($params)
                               ->doInsert();

            if (empty($orderLogId)) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            switch ($orderInfo['type']) {
                case ORDER_TYPE_GROUP:
                    $affectedRows = $this->Group_model->updateState($orderInfo['fk_group'], CONTEST_GROUP_STATE_INIT,
                        CONTEST_GROUP_STATE_PAYING, __METHOD__, $this);

                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }
                    break;
                case ORDER_TYPE_TEAM:
                    $affectedRows = $this->Team_model->updateState($orderInfo['fk_team'], CONTEST_TEAM_STATE_INIT,
                        CONTEST_TEAM_STATE_PAYING, __METHOD__, $this);

                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }
                    break;
            }

            $this->commit();

            return $out_trade_no;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 生成外部订单号 2016030110000000001
     *
     * @param  integer $orderid 订单ID
     *
     * @return string
     */
    public function createOutTradeNo($orderid)
    {
        return date('Ymd') . '1' . sprintf('%010d', $orderid);
    }

    public function changeStateToFailed($orderInfo, $fromState)
    {
        try {
            $this->beginTransaction();

            $affectedRows = $this->setTable($this->tableNameOrder)
                                 ->addUpdateColumns('state', ORDER_STATE_FAILED)
                                 ->addQueryConditions('state', $fromState)
                                 ->addQueryConditions('pk_order', $orderInfo['pk_order'])
                                 ->doUpdate();

            if (empty($affectedRows)) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            $params = array(
                'fk_order'   => $orderInfo['pk_order'],
                'from_state' => $fromState,
                'to_state'   => ORDER_STATE_FAILED,
                'remark'     => __METHOD__,
            );

            $orderLogId = $this->setTable($this->tableNameOrderStateLog)
                               ->addInsertColumns($params)
                               ->doInsert();

            if (empty($orderLogId)) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            switch ($orderInfo['type']) {
                case ORDER_TYPE_SINGLE:
                    if ($orderInfo['itemInfo']['max_stock'] > 0) {
                        $affectedRows = $this->ContestItem_model->increaseCurStocks(array($orderInfo['itemInfo']['pk_contest_items'] => $orderInfo['copies']),
                            $this);
                        if (empty($affectedRows)) {
                            $this->rollBack();
                            log_message_v2('error');

                            return false;
                        }
                    }
                    break;
                case ORDER_TYPE_GROUP:
                    $affectedRows = $this->Group_model->updateState($orderInfo['fk_group'], CONTEST_GROUP_STATE_PAYING,
                        CONTEST_GROUP_STATE_FAILED, __METHOD__, $this);
                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }

                    if (!empty($orderInfo['enrolDataCountGroupByItemId'])) {
                        $affectedRows = $this->ContestItem_model->increaseCurStocks($orderInfo['enrolDataCountGroupByItemId'],
                            $this);

                        if ($affectedRows != count($orderInfo['enrolDataCountGroupByItemId'])) {
                            $this->rollBack();
                            log_message_v2('error');

                            return false;
                        }
                    }

                    break;
                case ORDER_TYPE_TEAM:
                    $affectedRows = $this->Team_model->updateState($orderInfo['fk_team'], CONTEST_TEAM_STATE_PAYING,
                        CONTEST_TEAM_STATE_FAILED, __METHOD__, $this);
                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }

                    if ($orderInfo['itemInfo']['team_max_stock'] > 0) {
                        $affectedRows = $this->ContestItem_model->increaseTeamCurStock($orderInfo['itemInfo']['pk_contest_items'],
                            $this);
                        if (empty($affectedRows)) {
                            $this->rollBack();
                            log_message_v2('error');

                            return false;
                        }
                    }
                    break;
            }

            $this->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }


    public function changeStateToCompleted(
        $orderInfo,
        $paidTime,
        $channelId,
        $transactionId,
        $amountPay,
        $verifyCodeNeedData
    ) {
        try {
            $this->beginTransaction();

            $orderId = $orderInfo['pk_order'];
            $affectedRows = $this->setTable($this->tableNameOrder)
                                 ->addUpdateColumns('state', ORDER_STATE_COMPLETED)
                                 ->addUpdateColumns('paid_time', empty($paidTime) ? date('Y-m-d H:i:s') : $paidTime)
                                 ->addUpdateColumns('lottery_state', ORDER_LOTTERY_STATE_START)
                                 ->addUpdateColumns('channel_id', $channelId)
                                 ->addUpdateColumns('channel_transaction_id', $transactionId)
                                 ->addUpdateColumns('amount_pay', $amountPay)
                                 ->addQueryConditions('pk_order', $orderId)
                                 ->addQueryConditions('state', ORDER_STATE_PAYING)
                                 ->doUpdate();

            if ($affectedRows != 1) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            $params = array(
                'fk_order'   => $orderId,
                'from_state' => ORDER_STATE_PAYING,
                'to_state'   => ORDER_STATE_COMPLETED,
                'remark'     => __METHOD__,
            );

            $orderLogId = $this->setTable($this->tableNameOrderStateLog)
                               ->addInsertColumns($params)
                               ->doInsert();

            if (empty($orderLogId)) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            switch ($orderInfo['type']) {
                case ORDER_TYPE_SINGLE:
                    $enrolDataId = $verifyCodeNeedData['enrolData']['pk_enrol_data'];
                    $maxVerify = $verifyCodeNeedData['contestItemInfo']['max_verify'];
                    $count = $this->EnrolData_model->setVerifyCode(
                        $orderId,
                        $enrolDataId,
                        $maxVerify,
                        $orderInfo['copies'],
                        $this
                    );

                    if ($count != $orderInfo['copies']) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }

                    break;
                case ORDER_TYPE_GROUP:
                    foreach ($verifyCodeNeedData['enrolData'] as $enrolData) {
                        $maxVerify = $verifyCodeNeedData['contestItemInfo'][$enrolData['fk_contest_items']]['max_verify'];
                        $count = $this->EnrolData_model->setVerifyCode(
                            $orderId,
                            $enrolData['pk_enrol_data'],
                            $maxVerify,
                            $orderInfo['copies'],
                            $this
                        );

                        if ($count != $orderInfo['copies']) {
                            $this->rollBack();
                            log_message_v2('error');

                            return false;
                        }
                    }

                    $affectedRows = $this->Group_model->updateState(
                        $orderInfo['fk_group'],
                        CONTEST_GROUP_STATE_PAYING,
                        CONTEST_GROUP_STATE_COMPLETED,
                        __METHOD__,
                        $this
                    );

                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }

                    break;
                case ORDER_TYPE_TEAM:
                    $enrolDataIds = array_column($verifyCodeNeedData['enrolData'], 'pk_enrol_data');
                    $maxVerify = $verifyCodeNeedData['contestItemInfo']['max_verify'];

                    foreach ($enrolDataIds as $enrolDataId) {
                        $count = $this->EnrolData_model->setVerifyCode(
                            $orderId,
                            $enrolDataId,
                            $maxVerify,
                            $orderInfo['copies'],
                            $this
                        );

                        if ($count != $orderInfo['copies']) {
                            $this->rollBack();
                            log_message_v2('error');

                            return false;
                        }
                    }

                    $affectedRows = $this->Team_model->updateState(
                        $orderInfo['fk_team'],
                        CONTEST_TEAM_STATE_PAYING,
                        CONTEST_TEAM_STATE_COMPLETED,
                        __METHOD__,
                        $this
                    );

                    if (empty($affectedRows)) {
                        $this->rollBack();
                        log_message_v2('error');

                        return false;
                    }
                    break;
            }

            @$this->initOrderExt($orderInfo['partner'], $orderId, $orderInfo['out_trade_no'], $this);

            $this->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function initOrderExt($parnter, $orderId, $outTradeNo, $model)
    {
        if (empty($parnter)) {
            return;
        }

        global $CONTEST_PARNTER_LIST;
        if (empty($CONTEST_PARNTER_LIST[$parnter])) {
            log_message_v2(
                'error',
                [
                    'msg'    => 'partner not found',
                    'params' => compact('partner', 'orderId', 'outTradeNo'),
                ]
            );

            return;
        }
        try {
            $parnterName = $CONTEST_PARNTER_LIST[$parnter];
            $modelName = "Order" . ucfirst($parnterName) . "_model";
            $this->load->model("{$parnterName}/{$modelName}");
            $lastId = $this->{$modelName}->initOrderExt($orderId, $outTradeNo, $model);
            if (empty($lastId)) {
                log_message_v2(
                    'error',
                    [
                        'msg'    => __METHOD__ . ' failed',
                        'params' => compact('partner', 'orderId', 'outTradeNo'),
                    ]
                );
            }
        } catch (Exception $e) {
            $this->logException($e);
        }
    }

    public function changeStateToClosed($orderId)
    {
        try {
            $this->beginTransaction();

            $affectedRows = $this->setTable($this->tableNameOrder)
                                 ->addUpdateColumns('state', ORDER_STATE_CLOSED)
                                 ->addUpdateColumns('lottery_state', ORDER_LOTTERY_STATE_SUCCESS)
                                 ->addQueryConditions('pk_order', $orderId)
                                 ->addQueryConditions('state', ORDER_STATE_COMPLETED)
                                 ->doUpdate();

            if ($affectedRows != 1) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            $params = array(
                'fk_order'   => $orderId,
                'from_state' => ORDER_STATE_COMPLETED,
                'to_state'   => ORDER_STATE_CLOSED,
                'remark'     => __METHOD__,
            );

            $orderLogId = $this->setTable($this->tableNameOrderStateLog)
                               ->addInsertColumns($params)
                               ->doInsert();

            if (empty($orderLogId)) {
                $this->rollBack();
                log_message_v2('error');

                return false;
            }

            $this->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 生成外部退款单号
     *
     * @param  integer $orderid 订单ID
     *
     * @return string
     */
    public function createOutRefundNo($orderid)
    {
        return date('Ymd') . '2' . sprintf('%010d', $orderid);
    }

    /**
     * 获取指定用户的订单列表
     *
     * @param $sellerCorpId
     * @param $sellerAppId
     * @param $uid
     * @param $state
     * @param $page
     * @param $size
     * @param $total
     *
     * @return array|bool
     */
    public function listByUid($sellerCorpId, $sellerAppId, $uid, $state, $page, $size, &$total)
    {
        $model = $this->setTable($this->tableNameOrder)
                      ->addQueryFieldsCount('pk_order', 'count')
                      ->addQueryConditions('seller_fk_corp', $sellerCorpId)
                      ->addQueryConditions('fk_component_authorizer_app', $sellerAppId)
                      ->addQueryConditions('fk_user', $uid);
        if (!empty($state)) {
            $model = $model->addQueryConditions('state', $state);
        }

        $countResult = $model->doSelect(1, 1);

        $total = $countResult[0]['count'];

        if (empty($total)) {
            return array();
        }

        $model = $this->setTable($this->tableNameOrder)
                      ->addQueryConditions('seller_fk_corp', $sellerCorpId)
                      ->addQueryConditions('fk_component_authorizer_app', $sellerAppId)
                      ->addQueryConditions('fk_user', $uid);
        if (!empty($state)) {
            $model = $model->addQueryConditions('state', $state);
        }

        return $model->addOrderBy('ctime', 'desc')
                     ->doSelect($page, $size);
    }

    public function searchManage($params)
    {
        try {
            $configName = SPHINX_INDEX_ORDER;
            $sphConfig = $this->config->item('sphinx')[$configName];
            $this->load->helper('sphinx');
            $sphinxClient = sphinx_init_helper($sphConfig);
            $sphinxClient->SetSortMode(SPH_SORT_EXTENDED, 'ctime DESC');
            $sphinxClient->SetLimits(($params['pageNumber'] - 1) * $params['pageSize'], $params['pageSize'],
                $sphConfig['max_matched']);

            $fk_corp = $params['sellerCorpId'];
            $sphinxClient->SetFilter('seller_fk_corp', compact('fk_corp'));

            $query = '';
            if (!empty($params['contestName'])) {
                $query .= '@cname ' . $params['contestName'] . ' ';
            }

            if (!empty($params['idNo'])) {
                $query .= '@id_no ' . $params['idNo'];
            }

            if (!empty($params['mobile'])) {
                $query .= '@mobile ' . $params['mobile'];
            }

            if (!empty($params['state'])) {
                $sphinxClient->SetFilter('state', array($params['state']));
            }

            if (!empty($params['start']) && !empty($params['end'])) {
                $sphinxClient->SetFilterRange('ctime', $params['start'], $params['end']);
            }

            $result = $sphinxClient->Query($query, $sphConfig['index']);
            $ids = array();
            if (!empty($result['matches'])) {
                foreach ($result['matches'] as $key => $value) {
                    $ids[] = $value['id'];
                }
            }

            $std = new stdClass();
            $std->total = intval($result['total']);
            $std->result = $ids;

            return $std;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * 根据订单ID获取订单详情
     *
     * @param  integer $pk_order 订单ID
     * @param string   $dsn_type 数据库连接方式
     *
     * @return array|bool
     * @throws \Exception
     */
    public function getById($pk_order, $dsn_type = Pdo_Mysql::DSN_TYPE_SLAVE)
    {
        $result = $this->setDsnType($dsn_type)
                       ->setTable($this->tableNameOrder)
                       ->addQueryConditions('pk_order', $pk_order)
                       ->doSelect(1, 1);

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function listEnrolInfoByOrderIds($orderIds)
    {
        $query = 'SELECT fk_order, title, value FROM t_enrol_info WHERE fk_order in (%s) ORDER BY fk_enrol_form_item asc';

        $query = sprintf($query, implode(',', $orderIds));

        return $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $query, array());
    }

    public function getByIds($orderIds)
    {
        return $this->setTable($this->tableNameOrder)
                    ->addQueryConditionIn('pk_order', $orderIds)
                    ->doSelect(1, count($orderIds));
    }

    public function getByTeam($teamId)
    {
        $result = $this->setTable($this->tableNameOrder)
                       ->addQueryConditions('fk_team', $teamId)
                       ->addOrderBy('ctime', 'desc')
                       ->doSelect(1, 1);

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }


    public function getByGroup($groupId)
    {
        $result = $this->setTable($this->tableNameOrder)
                       ->addQueryConditions('fk_group', $groupId)
                       ->addOrderBy('ctime', 'desc')
                       ->doSelect(1, 1);

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getByEnrolDataId($enrolDataId)
    {
        $result = $this->setTable($this->tableNameOrder)
                       ->addQueryConditions('fk_enrol_data', $enrolDataId)
                       ->doSelect(1, 1);

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function listExpired($pageNumber, $pageSize)
    {
        $time = date('Y-m-d H:i:s', strtotime("-" . ORDER_PAY_TIME_LIMIT . " minutes"));

        return $this->setTable($this->tableNameOrder)
                    ->addQueryFields('pk_order')
                    ->addQueryConditionIn('state', [ORDER_STATE_INIT, ORDER_STATE_PAYING])
                    ->addQueryConditions('ctime', $time, '<')
                    ->doSelect($pageNumber, $pageSize);
    }

    private function getModelWithConditions($funcName, $conditions)
    {
        switch ($funcName) {
            case 'listByPage':
                $model = $this->setTable($this->tableNameOrder);
                if (!empty($conditions['owner_corp_id'])) {
                    $model = $model->addQueryConditions('owner_fk_corp', $conditions['owner_corp_id']);
                }
                if (!empty($conditions['cid'])) {
                    $model = $model->addQueryConditions('fk_contest', $conditions['cid']);
                }
                if (!empty($conditions['seller_corp_id'])) {
                    $model = $model->addQueryConditions('seller_fk_corp', $conditions['seller_corp_id']);
                }
                if (!empty($conditions['oid'])) {
                    $model = $model->addQueryConditions('pk_order', $conditions['oid']);
                }
                if (!empty($conditions['transaction_id'])) {
                    $model = $model->addQueryConditions('channel_transaction_id', $conditions['transaction_id']);
                }
                if (!empty($conditions['out_trade_no'])) {
                    $model = $model->addQueryConditions('out_trade_no', $conditions['out_trade_no']);
                }
                if (!empty($conditions['uid'])) {
                    $model = $model->addQueryConditions('fk_user', $conditions['uid']);
                }
                if (!empty($conditions['type'])) {
                    $model = $model->addQueryConditions('type', $conditions['type']);
                }
                if (!empty($conditions['state'])) {
                    $model = $model->addQueryConditions('state', $conditions['state']);
                }
                if (!empty($conditions['date_start'])) {
                    $model = $model->addQueryConditions('ctime', $conditions['date_start'], '>=');
                }
                if (!empty($conditions['date_end'])) {
                    $model = $model->addQueryConditions('ctime', $conditions['date_end'], '<');
                }

                return $model;
                break;
        }

        return null;
    }

    public function listByPage($conditions, $pageNumber, $pageSize, &$total)
    {
        $countResult = $this->getModelWithConditions(__FUNCTION__, $conditions)
                            ->addQueryFieldsCount('pk_order', 'count')
                            ->doSelect(1, 1);

        $total = $countResult[0]['count'];

        if (empty($total)) {
            return array();
        }


        return $this->getModelWithConditions(__FUNCTION__, $conditions)
                    ->addOrderBy('ctime', 'desc')
                    ->doSelect($pageNumber, $pageSize);
    }

    public function getTotal($seller_corp_id = null, $start_time = null, $end_time = null)
    {
        $model = $this->setTable($this->tableNameOrder)
                      ->addQueryFieldsCount('*', 'count')
                      ->addQueryFieldsSum('amount', 'amount')
                      ->addQueryConditions('state', ORDER_STATE_CLOSED);

        if (!empty($seller_corp_id)) {
            $model = $model->addQueryConditions('seller_fk_corp', $seller_corp_id);
        }
        if (!empty($start_time)) {
            $model = $model->addQueryConditions('ctime', $start_time, '>=');
        }
        if (!empty($end_time)) {
            $model = $model->addQueryConditions('ctime', $end_time, '<');
        }

        $result = $model->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getVerifyCodeByOids($ids)
    {
        return $this->setTable($this->tableNameVerifyCode)
                    ->addQueryConditionIn('fk_order', $ids)
                    ->doSelect();
    }
}
// END class Msg_model
