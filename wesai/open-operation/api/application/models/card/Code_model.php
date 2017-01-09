<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';


class Code_model extends ModelBase
{

    public function __construct()
    {
        parent::__construct();
    }

    public function create($card_code)
    {
        try {
            $this->beginTransaction();

            $code_id = $this->setTable($this->tableNameCode)
                            ->addInsertColumns($card_code)
                            ->doInsert();
            if (empty($code_id)) {
                return false;
            }

            $cardQueryParam = [
                ['pk_card', $card_code['fk_card'], '='],
                ['current_quantity', 1, '>=']
            ];

            $affected_rows = $this->setTable($this->tableNameCard)
                                  ->addUpdateColumns('current_quantity', 1, '-')
                                  ->addQueryConditions('current_quantity', 1, '>=')
                                  ->addQueryConditions('pk_card', $card_code['fk_card'], '=')
                                  ->doUpdate();
            //print_r($affected_rows);die;
            if (empty($affected_rows)) {
                return false;
            }

            $this->commit();

            return $code_id;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function getByCode($code)
    {
        $result = $this->setTable($this->tableNameCode)
                       ->addQueryConditions('code', $code)
                       ->doSelect();
        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function modify($code_id, $params)
    {
        $updated_result = ['error' => 0];
        try {
            $this->beginTransaction();

            $code_info = $this->getByCode($params['code']);

            if (empty($code_info)) {
                $updated_result['error'] = Error_Code::ERROR_CARD_CODE_NOT_EXISTS;

                return $updated_result;
            }

            $affected_rows = 0;

            if (!empty($params['add_balance'])) {
                !empty($params['record_balance']) or $params['record_balance'] = ($params['add_balance'] > 0 ? "充值" : "消费") . '余额 ' . abs($params['add_balance']);
                $affected_rows += $this->increaseBalance($code_id, $params['add_balance'], $params['record_balance'],
                    $code_info['balance']);
                if (empty($affected_rows)) {
                    $this->rollBack();

                    $updated_result['error'] = Error_Code::ERROR_CARD_CODE_INCREASE_BALANCE_FAILED;

                    return $updated_result;
                }

                $updated_result['balance']['add_balance'] = $params['add_balance'];
                $updated_result['balance']['record_balance'] = $params['record_balance'];
            }

            if (!empty($params['add_bonus'])) {
                !empty($params['record_bonus']) or $params['record_bonus'] = ($params['add_bonus'] > 0 ? "充值" : "消费") . '余额 ' . abs($params['add_bonus']);
                $affected_rows += $this->increaseBonus($code_id, $params['add_bonus'], $params['record_bonus'],
                    $code_info['bonus']);
                if (empty($affected_rows)) {
                    $this->rollBack();

                    $updated_result['error'] = Error_Code::ERROR_CARD_CODE_INCREASE_BONUS_FAILED;

                    return $updated_result;
                }

                $updated_result['bonus']['add_bonus'] = $params['add_bonus'];
                $updated_result['bonus']['record_bonus'] = $params['record_bonus'];
            }

            if (!empty($params['custom_field_value1'])) {
                $affected_rows += $this->setCustomValue($code_id, $params['card_id'], 1,
                    $params['custom_field_value1']);

                $updated_result['custom_field_value1'] = $params['custom_field_value1'];
            }

            if (!empty($params['custom_field_value2'])) {
                $affected_rows += $this->setCustomValue($code_id, $params['card_id'], 2,
                    $params['custom_field_value2']);
                $updated_result['custom_field_value2'] = $params['custom_field_value2'];
            }

            if (!empty($params['custom_field_value3'])) {
                $affected_rows += $this->setCustomValue($code_id, $params['card_id'], 3,
                    $params['custom_field_value3']);
                $updated_result['custom_field_value3'] = $params['custom_field_value3'];
            }


            $this->commit();

            $updated_result['affected_rows'] = $affected_rows;

            return $updated_result;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function increaseBalance($code_id, $add_balance, $remark, $from_balance)
    {
        $affected_rows = $this->setTable($this->tableNameCode)
                              ->addUpdateColumns('balance', $add_balance, '+')
                              ->addQueryConditions('pk_card_code', $code_id)
                              ->doUpdate();
        if (empty($affected_rows)) {
            return false;
        }

        $params = [
            'fk_card_code' => $code_id,
            'from_balance' => $from_balance,
            'add_balance'  => $add_balance,
            'to_balance'   => $from_balance + $add_balance,
            'remark'       => $remark,
        ];

        $log_id = $this->setTable($this->tableNameCardCodeBalanceLog)
                       ->addInsertColumns($params)
                       ->doInsert();
        if (empty($log_id)) {
            return false;
        }

        return $affected_rows;
    }

    private function increaseBonus($code_id, $add_bonus, $remark, $from_bonus)
    {
        $affected_rows = $this->setTable($this->tableNameCode)
                              ->addUpdateColumns('bonus', $add_bonus, '+')
                              ->addQueryConditions('pk_card_code', $code_id)
                              ->doUpdate();
        if (empty($affected_rows)) {
            return false;
        }

        $params = [
            'fk_card_code' => $code_id,
            'from_bonus'   => $from_bonus,
            'add_bonus'    => $add_bonus,
            'to_bonus'     => $from_bonus + $add_bonus,
            'remark'       => $remark,
        ];

        $log_id = $this->setTable($this->tableNameCardCodeBonusLog)
                       ->addInsertColumns($params)
                       ->doInsert();
        if (empty($log_id)) {
            return false;
        }

        return $affected_rows;
    }

    private function setCustomValue($code_id, $card_id, $seq, $value)
    {
        $custom_field = $this->getCustomField($card_id, $seq);
        if (empty($custom_field)) {
            return false;
        }

        return $this->setTable($this->tableNameCardCodeCustomValue)
                    ->addUpdateColumns('value', $value)
                    ->addQueryConditions('fk_card_code', $code_id)
                    ->addQueryConditions('fk_card_member_card_custom_field',
                        $custom_field['pk_card_member_card_custom_field'])
                    ->doUpdate();
    }

    public function getCustomField($card_id, $seq)
    {
        $result = $this->setTable($this->tableNameCardMemberCardCustomField)
                       ->addQueryConditions('fk_card', $card_id)
                       ->addQueryConditions('wx_field_seq', $seq)
                       ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getById($code_id)
    {
        $result = $this->setTable($this->tableNameCode)
                       ->addQueryConditions('pk_card_code', $code_id)
                       ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function receive($code_id, $params)
    {
        try {
            $this->beginTransaction();

            $conditions = [
                'pk_card_code' => $code_id,
            ];

            $update_params = [
                'code'   => $params['code'],
                'openid' => $params['openid'],
            ];

            empty($params['original_code']) or $update_params['original_code'] = $params['original_code'];
            empty($params['original_openid']) or $update_params['original_openid'] = $params['original_openid'];

            if (empty($params['is_restore'])) {
                $conditions['state'] = CARD_CODE_STATE_CREATED;
                $update_params['state'] = CARD_CODE_STATE_RECEIVED;
            }

            $update_params = $this->makeORMUpdateColumns($update_params);

            $affected_rows = $this->setTable($this->tableNameCode)
                                  ->addUpdateColumns($update_params)
                                  ->addQueryConditions($conditions)
                                  ->doUpdate();

            if (empty($affected_rows)) {
                $this->rollBack();

                return Error_Code::ERROR_CARD_CODE_RECEIVE_FALIED;
            }

            if (empty($params['is_restore'])) {
                $log_id = $this->writeStateLog($code_id, CARD_CODE_STATE_CREATED, CARD_CODE_STATE_RECEIVED, __METHOD__);
                if (empty($log_id)) {
                    $this->rollBack();

                    return Error_Code::ERROR_CARD_CODE_RECEIVE_FALIED;
                }

                $this->commit();

                return $affected_rows;
            }

            $log_id = $this->writeWxStateLog($code_id, CARD_CODE_WX_STATE_DELETED, CARD_CODE_WX_STATE_NORMAL, __METHOD__);
            if (empty($log_id)) {
                $this->rollBack();

                return Error_Code::ERROR_CARD_CODE_RECEIVE_FALIED;
            }

            $this->commit();

            return $affected_rows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function writeStateLog($code_id, $from_state, $to_state, $remark)
    {
        $params = compact('from_state', 'to_state');
        $params['fk_card_code'] = $code_id;
        $params['remark'] = $remark;

        return $this->setTable($this->tableNameCodeStateLog)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    private function writeWxStateLog($code_id, $from_state, $to_state, $remark)
    {
        $params = compact('from_state', 'to_state');
        $params['fk_card_code'] = $code_id;
        $params['remark'] = $remark;

        return $this->setTable($this->tableNameCodeWxStateLog)
                    ->addInsertColumns($params)
                    ->doInsert();
    }

    public function deleteFromWeixin($code_id)
    {
        try {
            $this->beginTransaction();

            $from_state = CARD_CODE_WX_STATE_NORMAL;
            $to_state = CARD_CODE_WX_STATE_DELETED;

            $affected_rows = $this->setTable($this->tableNameCode)
                ->addUpdateColumns('wx_state', $to_state)
                ->addQueryConditions('pk_card_code', $code_id)
                ->addQueryConditions('wx_state', $from_state)
                ->doUpdate();

            if (empty($affected_rows)) {
                $this->rollBack();

                return false;
            }

            $log_id = $this->writeWxStateLog($code_id, $from_state, $to_state, __METHOD__);
            if (empty($log_id)) {
                $this->rollBack();

                return false;
            }

            $this->commit();

            return $affected_rows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function activate($code_id)
    {
        try {
            $this->beginTransaction();

            $from_state = CARD_CODE_STATE_RECEIVED;
            $to_state = CARD_CODE_STATE_ACTIVATED;

            $affected_rows = $this->setTable($this->tableNameCode)
                                  ->addUpdateColumns('state', $to_state)
                                  ->addQueryConditions('pk_card_code', $code_id)
                                  ->addQueryConditions('state', $from_state)
                                  ->doUpdate();

            if (empty($affected_rows)) {
                $this->rollBack();

                return false;
            }

            $log_id = $this->writeStateLog($code_id, $from_state, $to_state, __METHOD__);
            if (empty($log_id)) {
                $this->rollBack();

                return false;
            }

            $this->commit();

            return $affected_rows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function listCustomValues($code_id)
    {
        return $this->setTable($this->tableNameCardCodeCustomValue)
            ->addQueryConditions('fk_card_code', $code_id)
            ->doSelect();
    }

    public function listByPage($card_id, $page, $size, &$total)
    {
        $model = $this->setTable($this->tableNameCode)
            ->addQueryFieldCalc('count', '*', 'cnt');
        if (!empty($card_id)) {
            $model = $model->addQueryConditions('fk_card', $card_id);
        }

        $count_result = $model->doSelect(1, 1);
        $total = $count_result[0]['cnt'];

        if (empty($total)) {
            return [];
        }

        $model = $this->setTable($this->tableNameCode);
        if (!empty($card_id)) {
            $model = $model->addQueryConditions('fk_card', $card_id);
        }

        return $model->doSelect($page, $size);
    }
}
