<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once __DIR__ . '/../ModelBase.php';

class Card_model extends ModelBase
{
    public function __construct()
    {
        parent::__construct();
    }

    private function returnError($code, $param = null)
    {
        $result = new stdClass();
        $result->error = $code;
        $result->msg = Error_Code::desc($result->error);
        if (!empty($param)) {
            $result->msg = sprintf($result->msg, $param);
        }

        return $result;
    }

    public function create($corp_id, $service_type, $service_id, $apppk, $card_info)
    {

        $card = $card_info['base_info'];
        $card['fk_corp'] = $corp_id;
        $card['service_type'] = $service_type;
        $card['service_id'] = $service_id;
        $card['fk_component_authorizer_app'] = $apppk;
        $card['card_type'] = $card_info['card_type'];
        !empty($card['code_type']) or $card['code_type'] = CODE_TYPE_QRCODE;

        $card_type_name = $this->getCardTypeName($card_info['card_type']);
        if (empty($card_type_name)) {
            return $this->returnError(Error_Code::ERROR_CARD_PARAM_INVALID, 'card_type');
        }

        $card_ext = $card_info[$card_type_name];

        $card_ext_custom_fields = isset($card_ext['custom_fields']) ? $card_ext['custom_fields'] : [];
        unset($card_ext['custom_fields']);

        // 会员卡自定义权益最多3个、包含余额、积分
        $custom_field_number = 3;
        if (!empty($card_ext['supply_balance'])) {
            $custom_field_number--;
        }
        if (!empty($card_ext['supply_bonus'])) {
            $custom_field_number--;
        }

        if (count($card_ext_custom_fields) > $custom_field_number) {
            return $this->returnError(Error_Code::ERROR_CARD_CUSTOM_FIELD_OVERFLOW);
        }

        $card_text_image_list = isset($card_info['text_image_list']) ? $card_info['text_image_list'] : [];

        try {
            $this->beginTransaction();

            $verify_unq = $this->getByUnqKey($corp_id, $apppk, $service_type, $service_id);
            if (!empty($verify_unq)) {
                $this->rollBack();

                return $this->returnError(Error_Code::ERROR_CARD_CORP_SERVICE_TYPE_ID_DUPLICATED);
            }

            $card_id = $this->createCard($card);
            if (empty($card_id)) {
                $this->rollBack();

                return $this->returnError(Error_Code::ERROR_CARD_CREATE_FAILED);
            }

            $ext_id = $this->createCardExt($card_id, $card_info['card_type'], $card_ext);
            if (empty($ext_id)) {
                $this->rollBack();

                return $this->returnError(Error_Code::ERROR_CARD_EXT_CREATE_FAILED);
            }

            $ext_custom_id = $this->createCardExtCustomFields($card_id, $card_info['card_type'],
                $card_ext_custom_fields);
            if (empty($ext_custom_id)) {
                $this->rollBack();

                return $this->returnError(Error_Code::ERROR_CARD_EXT_CUSTOM_CREATE_FAILED);
            }

            $text_image_id = $this->createCardTextImageList($card_id, $card_text_image_list);
            if (empty($text_image_id)) {
                $this->rollBack();

                return $this->returnError(Error_Code::ERROR_CARD_TEXT_IMAGE_CREATE_FAILED);
            }

            $this->commit();

            $result = new stdClass();
            $result->error = 0;
            $result->card_id = $card_id;

            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function createCard($card)
    {
        return $this->setTable($this->tableNameCard)
                    ->addInsertColumns($card)
                    ->doInsert();
    }

    private function createCardExt($card_id, $card_type, $card_ext)
    {
        if (empty($card_ext)) {
            return true;
        }

        $card_ext['fk_card'] = $card_id;

        return $this->setTable($this->getTableNameCardExt($card_type))
                    ->addInsertColumns($card_ext)
                    ->doInsert();
    }

    private function createCardExtCustomFields($card_id, $card_type, $card_ext_custom_fields)
    {
        if (empty($card_ext_custom_fields)) {
            return true;
        }

        foreach ($card_ext_custom_fields as $key => $field) {
            $field['fk_card'] = $card_id;
            $field['wx_field_seq'] = $key + 1;

            $id = $this->createCardExtCustomField($card_type, $field);
            if (empty($id)) {
                return false;
            }
        }

        return count($card_ext_custom_fields);
    }

    private function createCardExtCustomField($card_type, $card_ext_custom_field)
    {
        return $this->setTable($this->getTableNameCustomField($card_type))
                    ->addInsertColumns($card_ext_custom_field)
                    ->doInsert();
    }

    private function createCardTextImageList($card_id, $text_image_list)
    {
        if (empty($text_image_list)) {
            return true;
        }

        foreach ($text_image_list as $tm) {
            $tm['fk_card'] = $card_id;
            $id = $this->createCardTextImage($tm);
            if (empty($id)) {
                return false;
            }
        }

        return count($text_image_list);
    }

    private function createCardTextImage($text_image)
    {
        return $this->setTable($this->getTableNameTextImageList())
                    ->addInsertColumns($text_image)
                    ->doInsert();
    }

    private function getCardTypeName($card_type)
    {
        global $CARD_TYPE_NAME_LIST;
        if (!array_key_exists($card_type, $CARD_TYPE_NAME_LIST)) {
            return false;
        }

        return $CARD_TYPE_NAME_LIST[$card_type];
    }

    public function getByUnqKey($corp_id, $apppk, $service_type, $service_id)
    {
        $result = $this->setTable($this->tableNameCard)
                       ->addQueryConditions('fk_corp', $corp_id)
                       ->addQueryConditions('fk_component_authorizer_app', $apppk)
                       ->addQueryConditions('service_type', $service_type)
                       ->addQueryConditions('service_id', $service_id)
                       ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getById($card_id)
    {
        $result = $this->setTable($this->tableNameCard)
                       ->addQueryConditions('pk_card', $card_id)
                       ->doSelect();
        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function modify($card_id, $card_type, $card_info, $old_card)
    {
        $card_type_name = $this->getCardTypeName($card_type);
        if (empty($card_type_name)) {
            return $this->returnError(Error_Code::ERROR_CARD_PARAM_INVALID, 'card_type');
        }

        !empty($card_info['base_info']) or $card_info['base_info'] = [];
        !empty($card_info[$card_type_name]) or $card_info[$card_type_name] = [];
        !empty($card_info[$card_type_name]['custom_fields']) or $card_info[$card_type_name]['custom_fields'] = [];
        !empty($card_info['text_image_list']) or $card_info['text_image_list'] = [];

        $card = $card_info['base_info'];
        $card['state'] = CARD_STATE_CHECKING;
        if (!empty($card['logo_url']) && $card['logo_url'] != $old_card['logo_url']) {
            $card['wx_logo_url'] = '';
        }
        if (!empty($card['icon_url_list']) && $card['icon_url_list'] != $old_card['icon_url_list']) {
            $card['wx_icon_url_list'] = '';
        }

        $card_ext = $card_info[$card_type_name];
        unset($card_ext['custom_fields']);
        if (!empty($card_ext['background_pic_url']) && $card_ext['background_pic_url'] != $old_card[$card_type_name]['background_pic_url']) {
            $card_ext['wx_background_pic_url'] = '';
        }

        $card_ext_custom_fields = $card_info[$card_type_name]['custom_fields'];
        $text_image_list = $card_info['text_image_list'];

        try {
            $this->beginTransaction();

            $affected_rows = 0;
            $affected_rows += $this->updateCard($card_id, $card);
            $affected_rows += $this->updateCardExt($card_id, $card_type, $card_ext);
            $affected_rows += $this->updateCardExtCustomFields($card_id, $card_type, $card_ext_custom_fields);
            $affected_rows += $this->updateCardTextImageList($card_id, $text_image_list);

            $from_state = $old_card['state'];
            $to_state = CARD_STATE_CHECKING;
            if ($from_state != $to_state) {
                $fk_card = $card_id;
                $remark = __METHOD__;
                $log_params = compact('fk_card', 'from_state', 'to_state', 'remark');
                $log_id = $this->changeStateLog($log_params);
                if (empty($log_id)) {
                    $this->rollBack();

                    return false;
                }
            }

            $this->commit();

            return $affected_rows;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function updateCard($card_id, $card)
    {
        if (empty($card)) {
            return 0;
        }

        $card = $this->makeORMUpdateColumns($card);

        return $this->setTable($this->tableNameCard)
                    ->addUpdateColumns($card)
                    ->addQueryConditions('pk_card', $card_id)
                    ->doUpdate();
    }

    private function updateCardExt($card_id, $card_type, $card_ext)
    {
        if (empty($card_ext)) {
            return 0;
        }

        $card_ext = $this->makeORMUpdateColumns($card_ext);

        return $this->setTable($this->getTableNameCardExt($card_type))
                    ->addUpdateColumns($card_ext)
                    ->addQueryConditions('fk_card', $card_id)
                    ->doUpdate();
    }

    private function updateCardExtCustomFields($card_id, $card_type, $card_ext_custom_fields)
    {
        if (empty($card_ext_custom_fields)) {
            return 0;
        }

        $affected_rows = $this->deleteAllCustomFields($card_id, $card_type);
        $custom_field_list = $this->listCustomFields($card_id, $card_type);

        $card_type_name = $this->getCardTypeName($card_type);

        foreach ($card_ext_custom_fields as $key => $field) {
            if (empty($custom_field_list[$key])) {
                $field['fk_card'] = $card_id;
                $id = $this->createCardExtCustomField($card_type, $field);
                if (empty($id)) {
                    return false;
                }
            } else {
                $old_field = $custom_field_list[$key];
                $field['wx_field_seq'] = $old_field['wx_field_seq'];
                $field['state'] = CARD_CUSTOM_FIELD_STATE_NORMAL;
                $field_id = $old_field['pk_card_' . $card_type_name . '_custom_field'];
                $affected_rows += $this->updateCardExtCustomField($field_id, $card_type, $field, $card_type_name);
            }
        }

        return $affected_rows;
    }

    private function deleteAllCustomFields($card_id, $card_type)
    {
        return $this->setTable($this->getTableNameCustomField($card_type))
                    ->addUpdateColumns('state', CARD_CUSTOM_FIELD_STATE_DELETED)
                    ->addQueryConditions('fk_card', $card_id)
                    ->doUpdate();
    }

    private function listCustomFields($card_id, $card_type)
    {
        return $this->setTable($this->getTableNameCustomField($card_type))
                    ->addQueryConditions('fk_card', $card_id)
                    ->doSelect();
    }

    private function updateCardExtCustomField($field_id, $card_type, $field, $card_type_name)
    {
        $field = $this->makeORMUpdateColumns($field);

        return $this->setTable($this->getTableNameCustomField($card_type))
                    ->addUpdateColumns($field)
                    ->addQueryConditions('pk_card_' . $card_type_name . '_custom_field', $field_id)
                    ->doUpdate();
    }

    private function updateCardTextImageList($card_id, $text_image_list)
    {
        $affected_rows = $this->deleteAllTextImageList($card_id);
        $old_text_image_list = $this->listTextImageList($card_id);

        foreach ($text_image_list as $key => $tm) {
            if (empty($old_text_image_list[$key])) {
                $tm['fk_card'] = $card_id;
                $id = $this->createCardTextImage($tm);
                if (empty($id)) {
                    return false;
                }
            } else {
                $old_tm = $old_text_image_list[$key];
                $tm['state'] = CARD_TEXT_IMAGE_STATE_NORMAL;
                if (!empty($tm['image_url']) && $tm['image_url'] != $old_tm['image_url']) {
                    $tm['wx_image_url'] = '';
                }
                $affected_rows += $this->updateCardTextImage($old_tm['pk_card_text_image_list'], $tm);
            }
        }

        return $affected_rows;
    }

    private function deleteAllTextImageList($card_id)
    {
        return $this->setTable($this->getTableNameTextImageList())
                    ->addUpdateColumns('state', CARD_TEXT_IMAGE_STATE_DELETED)
                    ->addQueryConditions('fk_card', $card_id)
                    ->doUpdate();
    }

    private function listTextImageList($card_id)
    {
        return $this->setTable($this->getTableNameTextImageList())
                    ->addQueryConditions('fk_card', $card_id)
                    ->doSelect();
    }

    private function updateCardTextImage($tm_id, $text_image)
    {
        return $this->setTable($this->getTableNameTextImageList())
                    ->addUpdateColumns($text_image)
                    ->addQueryConditions('pk_card_text_image_list', $tm_id)
                    ->doUpdate();
    }

    private function getTableNameCustomField($card_type)
    {
        $card_type_name = $this->getCardTypeName($card_type);
        if (empty($card_type_name)) {
            return false;
        }

        return $this->tableNameCard . '_' . $card_type_name . '_custom_field';
    }

    private function getTableNameCardExt($card_type)
    {
        $card_type_name = $this->getCardTypeName($card_type);
        if (empty($card_type_name)) {
            return false;
        }
        return $this->tableNameCard . '_' . $card_type_name;
    }

    private function getTableNameTextImageList()
    {
        return $this->tableNameCard . '_text_image_list';
    }

    public function getByWxCardId($wx_card_id)
    {
        $result = $this->setTable($this->tableNameCard)
            ->addQueryConditions('wx_card_id', $wx_card_id)
            ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function getExtById($card_id, $card_type)
    {
        $result = $this->setTable($this->getTableNameCardExt($card_type))
            ->addQueryConditions('fk_card', $card_id)
            ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result[0];
    }

    public function listExtCustomFields($card_id, $card_type)
    {
        $result = $this->setTable($this->getTableNameCustomField($card_type))
                       ->addQueryConditions('fk_card', $card_id)
                       ->addQueryConditions('state', 1)
                       ->doSelect();

        if (empty($result)) {
            return false;
        }

        return $result;
    }

    public function listTextImage($card_id)
    {
        return $this->setTable($this->getTableNameTextImageList())
            ->addQueryConditions('fk_card', $card_id)
            ->addQueryConditions('state', 1)
            ->addOrderBy('ctime', 'desc')
            ->doSelect();
    }


    /**
     * 根据企业id获取列表
     *
     * @param      $fk_corp
     * @param int  $pageNumber
     * @param int  $pageSize
     * @param null $state
     *
     * @param      $total
     *
     * @return array|bool
     */
    public function listByPage($fk_corp, $pageNumber, $pageSize, $state, &$total)
    {
        $conditions = [
            ['fk_corp', $fk_corp, '='],
        ];
        if (!empty($state)) {
            $conditions[] = ['state', $state, '='];
        }

        $count_result = $this->setTable($this->tableNameCard)
                      ->addQueryFieldCalc('count', '*', 'count')
                      ->addQueryConditions($conditions)
                      ->doSelect();

        $total = $count_result[0]['total'];
        if (empty($total)) {
            return [];
        }

        return $this->setTable($this->tableNameCard)
                     ->addQueryConditions($conditions)
                     ->addOrderBy('ctime', 'desc')
                     ->doSelect($pageNumber, $pageSize);
    }

    /**
     * 状态变更
     *
     * @param        $card_id
     * @param        $from_state
     * @param        $to_state
     * @param string $remark 事件说明
     *
     * @return bool|int
     * @throws Exception
     */
    public function changeState($card_id, $from_state, $to_state, $remark)
    {
        try {
            $this->beginTransaction();
            $condParam = array(
                array('pk_card', $card_id, '='),
                array('state', $from_state, '='),
            );
            $result = $this->setTable($this->tableNameCard)
                           ->addUpdateColumns('state', $to_state)
                           ->addQueryConditions($condParam)
                           ->doUpdate();
            $param = compact('from_state', 'to_state', 'remark');
            $param['fk_card'] = $card_id;
            if (empty($result)) {
                $this->rollBack();
                log_message('error', $remark . ' update state failed. pm:' . json_encode($param));

                return false;
            }
            $logId = $this->changeStateLog($param);
            if (empty($logId)) {
                $this->rollBack();
                log_message('error', $remark . ' insert log failed. pm:' . json_encode($param));

                return false;
            }

            $this->commit();

            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    private function changeStateLog($param)
    {
        return $this->setTable($this->tableNameCardStateLog)
                    ->addInsertColumns($param)
                    ->doInsert();
    }

    public function saveWxCardId($card_id, $wx_card_id)
    {
        return $this->setTable($this->tableNameCard)
                    ->addUpdateColumns('wx_card_id', $wx_card_id)
                    ->addQueryConditions('pk_card', $card_id)
                    ->doUpdate();
    }

    public function saveWxPicUrl($card_id, $params)
    {

        $params = $this->makeORMUpdateColumns($params);

        return $this->setTable($this->tableNameCard)
                    ->addUpdateColumns($params)
                    ->addQueryConditions('pk_card', $card_id)
                    ->doUpdate();
    }
}
