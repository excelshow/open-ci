<?php
/**
 * User: zhaodc
 * Date: 23/09/2016
 * Time: 14:30
 */
require_once __DIR__ . '/Base.php';

class Contestitem extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 新增活动项目
     *
     */
    public function add_post()
    {
        $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('fee', PARAM_NOT_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('start_time', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('end_time', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('invite_required', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('max_verify', PARAM_NOT_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('max_stock', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('team_max_stock', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('team_size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('multi_buy', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('consume_start_time', PARAM_NULL_EMPTY);
        $this->post_check('consume_end_time', PARAM_NULL_EMPTY);
        $this->post_check('refund_flag', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('fee_origin', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $params = $this->get_request_params();

        if (!in_array($params['type'], [CONTEST_ITEM_TYPE_SINGLE, CONTEST_ITEM_TYPE_TEAM])) {
            return $this->response_error(Error_Code::ERROR_PARAM, 'type 非法');
        }

        switch ($params['type']) {
            case CONTEST_ITEM_TYPE_SINGLE:
                isset($params['max_stock']) or $params['max_stock'] = 0;
                $params['max_stock'] >= 0 or $params['max_stock'] = 0;
                $params['max_stock'] <= CONTEST_ITEM_MAX_STOCK or $params['max_stock'] = CONTEST_ITEM_MAX_STOCK;
                $params['cur_stock'] = $params['max_stock'];
                unset($params['team_max_stock'], $params['team_size']);

                if ($params['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES &&
                    empty($params['max_stock'])
                ) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER, '必须设置参与人数');
                }

                break;
            case CONTEST_ITEM_TYPE_TEAM:
                isset($params['team_max_stock']) or $params['team_max_stock'] = 0;
                $params['team_max_stock'] >= 0 or $params['team_max_stock'] = 0;
                $params['team_max_stock'] <= CONTEST_TEAM_MAX_STOCK or $params['team_max_stock'] = CONTEST_TEAM_MAX_STOCK;
                $params['team_cur_stock'] = $params['team_max_stock'];
                unset($params['max_stock'], $params['multi_buy']);

                if (!isset($params['team_size']) || $params['team_size'] <= 0) {
                    return $this->response_error(Error_Code::ERROR_PARAM, 'team_size 必传非空, 且>0');
                }

                if ($params['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES &&
                    empty($params['team_max_stock'])
                ) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER, '必须设置团队个数');
                }

                break;
        }


        $verify = $this->contestEditVerify($params['cid'], true);
        if ($verify->error < 0) {
            return $this->response_error($verify->error);
        }

        !empty($params['end_time']) && $params['end_time'] = date('Y-m-d 23:59:59', strtotime($params['end_time']));

        if (!empty($params['start_time']) && strtotime($verify->contestInfo['sdate_start']) > strtotime($params['start_time'])) {
            return $this->response_error(Error_Code::ERROR_PARAM,
                '项目时间应在活动时间内' . $verify->contestInfo['sdate_start'] . '~' . $verify->contestInfo['sdate_end']);
        }

        if (!empty($params['end_time']) && (strtotime($verify->contestInfo['sdate_end']) + 86399) < strtotime($params['end_time'])) {
            return $this->response_error(Error_Code::ERROR_PARAM,
                '项目时间应在活动时间内' . $verify->contestInfo['sdate_start'] . '~' . $verify->contestInfo['sdate_end']);
        }

        if (!empty($params['end_time']) && strtotime($params['end_time']) < strtotime($params['start_time'])) {
            return $this->response_error(Error_Code::ERROR_PARAM, '项目截止日期不可小于开始日期');
        }

        $params['fk_corp'] = $verify->contestInfo['fk_corp'];
        $params['fk_contest'] = $params['cid'];
        unset($params['cid']);

        $itemId = $this->ContestItem_model->create($params);

        //生成报名邀请码
        if ($params['invite_required'] == CONTEST_ITEM_INVITE_REQUIRED_YES) {
            $this->Msg_model->sendMsgCreateContestItemInviteCode($itemId);
        }

        return $this->response_insert($itemId);
    }

    public function get_get()
    {
        $itemId = $this->get_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $itemInfo = $this->verifyContestItemExists($itemId);

        return $this->response_object($itemInfo);
    }

    public function get_by_ids_get()
    {
        $itemIds = $this->get_check('item_ids', PARAM_NOT_NULL_NOT_EMPTY);

        $itemIds = explode(',', $itemIds);

        $itemList = $this->ContestItem_model->getByIds($itemIds);

        return $this->response_list($itemList, count($itemList), 1, count($itemList));
    }

    /**
     * 删除项目
     *
     */
    public function delete_post()
    {
        $itemId = $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $itemInfo = $this->verifyContestItemExists($itemId);

        if ($itemInfo['state'] != CONTEST_ITEM_STATE_OK) {
            return $this->response_update(0);
        }

        $verify = $this->contestEditVerify($itemInfo['fk_contest'], true);
        if ($verify->error < 0) {
            return $this->response_error($verify->error);
        }

        $affected_rows = $this->ContestItem_model->remove($itemId);

        return $this->response_update($affected_rows);
    }

    /**
     * 更新活动项目
     *
     */
    public function update_post()
    {
        $this->post_check('item_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('name', PARAM_NULL_NOT_EMPTY);
        $this->post_check('fee', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('start_time', PARAM_NULL_NOT_EMPTY);
        $this->post_check('end_time', PARAM_NULL_NOT_EMPTY);
        $this->post_check('invite_required', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('max_verify', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('max_stock', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('team_max_stock', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('team_size', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('multi_buy', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('consume_start_time', PARAM_NULL_EMPTY);
        $this->post_check('consume_end_time', PARAM_NULL_EMPTY);
        $this->post_check('refund_flag', PARAM_NULL_EMPTY, PARAM_TYPE_INT);
        $this->post_check('fee_origin', PARAM_NULL_EMPTY, PARAM_TYPE_INT);

        $params = $this->get_request_params();
        $itemId = $params['item_id'];
        unset($params['item_id']);

        $itemInfo = $this->verifyContestItemExists($itemId);

        $inviteRequired = isset($params['invite_required']) ? $params['invite_required'] : $itemInfo['invite_required'];

        $this->verifyContestItemState($itemInfo['state'], CONTEST_ITEM_STATE_OK);

        if (!in_array($params['type'], [CONTEST_ITEM_TYPE_SINGLE, CONTEST_ITEM_TYPE_TEAM])) {
            return $this->response_error(Error_Code::ERROR_PARAM, 'type 非法');
        }

        switch ($params['type']) {
            case CONTEST_ITEM_TYPE_SINGLE:
                if (isset($params['max_stock'])) {
                    $params['max_stock'] >= 0 or $params['max_stock'] = 0;
                    $params['max_stock'] <= CONTEST_ITEM_MAX_STOCK or $params['max_stock'] = CONTEST_ITEM_MAX_STOCK;
                    $params['cur_stock'] = $params['max_stock'];
                }

                $params['team_max_stock'] = 0;
                $params['team_cur_stock'] = 0;
                $params['team_size'] = 0;

                if ($inviteRequired == CONTEST_ITEM_INVITE_REQUIRED_YES &&
                    empty($params['max_stock'])
                ) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER, '必须设置参与人数');
                }

                break;
            case CONTEST_ITEM_TYPE_TEAM:
                if (isset($params['team_max_stock'])) {
                    $params['team_max_stock'] >= 0 or $params['team_max_stock'] = 0;
                    $params['team_max_stock'] <= CONTEST_TEAM_MAX_STOCK or $params['team_max_stock'] = CONTEST_TEAM_MAX_STOCK;
                    $params['team_cur_stock'] = $params['team_max_stock'];
                } else {
                    $params['team_max_stock'] = $itemInfo['team_max_stock'];
                    $params['team_cur_stock'] = $itemInfo['team_cur_stock'];
                }

                $params['max_stock'] = 0;
                $params['cur_stock'] = 0;
                $params['multi_buy'] = 2;

                isset($params['team_size']) or $params['team_size'] = $itemInfo['team_size'];

                if (isset($params['team_size']) && $params['team_size'] <= 0) {
                    return $this->response_error(Error_Code::ERROR_PARAM, 'team_size 非空, 且>0');
                }

                if ($inviteRequired == CONTEST_ITEM_INVITE_REQUIRED_YES &&
                    empty($params['team_max_stock'])
                ) {
                    return $this->response_error(Error_Code::ERROR_CONTEST_ITEM_MUST_SET_MAX_PLAYER, '必须设置团队个数');
                }

                break;
        }

        $verify = $this->contestEditVerify($itemInfo['fk_contest'], true);
        if ($verify->error < 0) {
            return $this->response_error($verify->error);
        }

        !empty($params['end_time']) && $params['end_time'] = date('Y-m-d 23:59:59', strtotime($params['end_time']));

        if (!empty($params['end_time'])) {
            if (strtotime($params['end_time']) < strtotime($params['start_time']) || strtotime($params['end_time']) < strtotime($itemInfo['start_time'])) {
                return $this->response_error(Error_Code::ERROR_PARAM, '项目截止日期不可小于开始日期');
            }
        }

        if (!empty($params['start_time']) && strtotime($verify->contestInfo['sdate_start']) > strtotime($params['start_time'])) {
            return $this->response_error(Error_Code::ERROR_PARAM,
                '项目时间应在活动时间内' . $verify->contestInfo['sdate_start'] . '~' . $verify->contestInfo['sdate_end']);
        }

        if (!empty($params['end_time']) && (strtotime($verify->contestInfo['sdate_end']) + 86399) < strtotime($params['end_time'])) {
            return $this->response_error(Error_Code::ERROR_PARAM,
                '项目时间应在活动时间内' . $verify->contestInfo['sdate_start'] . '~' . $verify->contestInfo['sdate_end']);
        }

        !empty($params['consume_start_time']) or $params['consume_start_time'] = '0000-00-00 00:00:00';
        !empty($params['consume_end_time']) or $params['consume_end_time'] = '0000-00-00 00:00:00';

        $affected_rows = $this->ContestItem_model->modify($itemId, $params);

        //生成报名邀请码
        if ($inviteRequired == CONTEST_ITEM_INVITE_REQUIRED_YES) {
            $this->Msg_model->sendMsgCreateContestItemInviteCode($itemId);
        }

        return $this->response_update($affected_rows);
    }


    /**
     * 获取活动活动项目ID
     *
     */
    public function list_get()
    {
        $cid = $this->get_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $type = $this->get_check('type', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $page = $this->get_check('page', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $size = $this->get_check('size', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $this->checkPageParams($page, $size);

        $contestInfo = $this->verifyContestExists($cid);

        $total = 0;
        $itemList = $this->ContestItem_model->listByPage($contestInfo['fk_corp'], $cid, $type, $page, $size, $total);

        return $this->response_list($itemList, $total, $page, $size);
    }

    /**
     * 获取核销中的项目
     */
    public function list_verifying_items_get()
    {
        $fkCorp = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $date = $this->get_check('date', PARAM_NOT_NULL_NOT_EMPTY);
        $pageNumber = $this->get_check('page', PARAM_NULL_NOT_EMPTY);
        $pageSize = $this->get_check('size', PARAM_NULL_NOT_EMPTY);

        $this->checkPageParams($pageNumber, $pageSize);

        $total = 0;
        $itemList = $this->ContestItem_model->listVerifyingItems($fkCorp, $date, $pageNumber, $pageSize, $total);
        if (empty($itemList)) {
            return $this->response_list(array(), 0, $pageNumber, $pageSize);
        }

        $contestIds = array_column($itemList, 'fk_contest');

        $contestList = $this->Contest_model->getByIds($contestIds);
        if (empty($contestList)) {
            return $this->response_list(array(), 0, $pageNumber, $pageSize);
        }

        $contestList = array_column($contestList, null, 'pk_contest');

        $this->load->model('Order_model');
        $itemListFinal = array();
        foreach ($itemList as $item) {
            if (!array_key_exists($item['fk_contest'], $contestList)) {
                continue;
            }
            $item['cname'] = $contestList[$item['fk_contest']]['name'];
            $item['cename'] = $contestList[$item['fk_contest']]['ename'];
            $item['banner'] = $contestList[$item['fk_contest']]['banner'];
            // $item['order_count']          = $this->Order_model->getItemOrderCount($item['fk_corp'], $item['fk_contest'], $item['pk_contest_items'])['cnt'];
            // $item['order_count_verified'] = $this->Order_model->getItemOrderCount($item['fk_corp'], $item['fk_contest'], $item['pk_contest_items'], $isVerified = true)['cnt'];
            $item['order_count'] = 0;
            $item['order_count_verified'] = 0;

            $itemListFinal[] = $item;
        }
        unset($itemList);

        return $this->response_list($itemListFinal, $total, $pageNumber, $pageSize);
    }

    public function get_selling_count_by_cids_get()
    {
        $contestIds = $this->get_check('cids', PARAM_NOT_NULL_NOT_EMPTY);

        $contestIds = str_replace(' ', '', $contestIds);
        $contestIds = trim(trim($contestIds), ',');
        $contestIds = implode(',', array_unique(explode(',', $contestIds)));

        $pageNumber = 1;
        $pageSize = 100;

        $itemList = array();
        while (true) {
            $results = $this->ContestItem_model->getSellingItemByCids($contestIds, $pageNumber, $pageSize);
            if (empty($results)) {
                break;
            }

            $itemList = array_merge($itemList, $results);

            $pageNumber++;
        }

        $result = explode(',', $contestIds);

        $finalList = array();
        foreach ($result as $v) {
            $finalList[$v] = 0;
            foreach ($itemList as $key => $val) {
                if ($v == $val['fk_contest']) {
                    if ($val['max_stock'] > 0 && $val['cur_stock'] <= 0) {
                        unset($itemList[$key]);
                        continue;
                    }
                    $finalList[$v]++;
                    unset($itemList[$key]);
                }
            }
        }
        $total = count($finalList);

        return $this->response_list($finalList, $total, 1, $total);
    }


    public function get_fee_range_by_cids_get()
    {

        $contestIds = $this->get_check('cids', PARAM_NOT_NULL_NOT_EMPTY);

        $contestIds = str_replace(' ', '', $contestIds);
        $contestIds = trim(trim($contestIds), ',');
        $contestIds = implode(',', array_unique(explode(',', $contestIds)));

        $contestInfo = $this->ContestItem_model->getFeeRangeByCids($contestIds);

        $count = count($contestInfo);

        if (empty($contestInfo)) {
            return $this->response_list(array(), $count, 1, $count);
        }

        return $this->response_list($contestInfo, $count, 1, $count);
    }


}
