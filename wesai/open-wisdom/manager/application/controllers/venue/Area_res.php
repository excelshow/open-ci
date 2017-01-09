<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Area_res extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setHostName(){
        return API_HOST_OPEN_VENUE;
    }

    public function setAllowedApiList(){
        return array(
            API_HOST_OPEN_VENUE => array(
                'venue/get' => 'venue/get.json',
                'venue_area_res/get' => 'venue_area_res/get.json',
                'venue_area_res/add' => 'venue_area_res/add.json',
                'venue_area_res/update' => 'venue_area_res/update.json',
                'venue_area_res/get_list_by_venue' => 'venue_area_res/get_list_by_venue.json',
                'venue_template/get_list_by_venue' => 'venue_template/get_list_by_venue.json',
                'venue_area/add' => 'venue_area/add.json',
            ),
            API_HOST_OPEN_BOOK => array(
                'venue_area_rule/add' => 'venue_area_rule/add.json',
                'venue_area_rule_detail/add' => 'venue_area_rule_detail/add.json',
                'resRule/get_detail_by_res_id' => 'mappingResRule/get_detail_by_res_id.json',
            ),
        );
    }

    /**
     * 获取添加场地模板
     */
    public function  get_add_page() {
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_id = intval($this->input->get('venue_id', true));
        if (!$venue_id) {
            return show_error('请选择场馆 ID');
        }

        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return show_error('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return show_error('场馆不存在');
        }

        $types_obj = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get_types_by_venues.json', array('venue_ids' => json_encode(array($venue_id), true)), METHOD_GET);
        if($types_obj->error){
            return show_error('场馆类型异常');
        }
        $type_date = json_decode(json_encode($types_obj->data),true);
        if(count($type_date) > 0){
            $tag_ids = array_column($type_date, 'tag_id');
        }
        $item = $this->config->item('allow_venue_types');
        $types = array_reduce($item, create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
        $allow_venue_types = array();
        foreach ($tag_ids as $key => $value) {
            if(isset($types[$value])){
                $allow_venue_types[$key]['tag_id'] = $value;
                $allow_venue_types[$key]['name'] = $types[$value];
            }
        }
        $data['allow_venue_types'] =$allow_venue_types;
        $data['venue_id'] = $venue_id;

        // 获取渲染的时间
        $data['select_start_date'] = get_select_start_date();
        $data['select_end_date'] = get_select_end_date();

        return $this->display($data);
    }

    /**
     * 获取添加场地模板
     */
    public function  get_edit_page(){
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->get('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return show_error('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        if ($get_res->error) {
            return show_error('场地不存在');
        }

        if ($get_res->result->state != VENUE_AREA_RES_STATE_UNSHELVE) {
            return show_error('无效的场地选项');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);

        if ($get_venue->error) {
            return show_error('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return show_error('场馆不存在');
        }

        $rules = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'mapping_res_rule/get_detail_by_res_id.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        // 整理规则信息
        foreach ($rules->data as $rule) {
            foreach ($rule as $value) {
                $value->time_start_format = date('G', strtotime($value->time_start));
                $value->time_end_format = date('G', strtotime($value->time_end));
                $value->price = $this->__format_price($value->price);
            }
        }

        $types_obj = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get_types_by_venues.json', array('venue_ids' => json_encode(array($get_res->result->venue_id), true)), METHOD_GET);
        if($types_obj->error){
            return show_error('场馆类型异常');
        }
        $type_date = json_decode(json_encode($types_obj->data),true);
        if(count($type_date) > 0){
            $tag_ids = array_column($type_date, 'tag_id');
        }

        $item = $this->config->item('allow_venue_types');
        $types = array_reduce($item, create_function('$v,$w', '$v[$w["tag_id"]]=$w["name"];return $v;'));
        $allow_venue_types = array();
        foreach ($tag_ids as $key => $value) {
            if(isset($types[$value])){
                $allow_venue_types[$key]['tag_id'] = $value;
                $allow_venue_types[$key]['name'] = $types[$value];
            }
        }
        $get_res->allow_venue_types =$allow_venue_types;

        $get_res->rules = $rules->data;

        $get_res->select_start_date = get_select_start_date();
        $get_res->select_end_date = get_select_end_date();

        return $this->display($get_res);
    }

    /**
     * 添加场地
     */
    public function ajax_add() {
        $this->needLoginJson();
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_id = intval($this->input->post('venue_id', true));

        // 验证场馆状态
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json',  array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->state != VENUE_STATE_DOWN) {
            return $this->errorInfo('无效的场馆状态');
        }

        $params = $this->__format_params($get_venue->result->types);
        if (array_key_exists('error', $params)) {
            return $params;
        }

        // 获取模板
        $list_template = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_template/get_list_by_venue.json',  array('venue_id' => $venue_id), METHOD_GET);
        if ($list_template->error) {
            return $this->errorInfo('无效的场馆模板');
        }

        if (!$list_template->data) {
            return $this->errorInfo('无效的场馆模板');
        }

        $template_id = $list_template->data[0]->venue_template_id;

        // 先验证是否有区域ID 如果有默认先第一个 以后去掉
        $area_params =  array(
            'venue_id'          => $venue_id,
            'function'          => $params['type'],
        );

        $add_area_obj = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area/get_id_by_venue_function.json', $area_params, METHOD_GET);
        $add_area_id = $add_area_obj->result;

        if(!$add_area_id){
            // 添加区域
            $area_params['venue_template_id'] = $template_id;
            $area_params['number']            = 'default';
            $add_area = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area/add.json', $area_params, METHOD_POST);
            if ($add_area->error) {
                return $this->errorInfo('添加区域失败');
            }
            $add_area_id = $add_area->lastid;
        }
        $res_params = array(
            'venue_area_id' => $add_area_id,
            'name' => $params['name'],
            'type' => $params['type'],
        );

        // 添加场地
        $add_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/add.json', $res_params, METHOD_POST);
        if ($add_res->error) {
            return $this->errorInfo('添加场地失败');
        }

        // 添加场地规则
        $add_rule = $this->__add_rules($venue_id, $add_area_id, $add_res->lastid, $params['mini'], $params['detail']);
        if ($add_rule['error']) {
            return $add_rule;
        }

        // 触发场次生成
        $create_times_params = array(
            'venue_id' => $venue_id,
            'venue_area_id' => $add_area_id,
            'venue_area_res_id' => $add_res->lastid,
        );

        $add_times = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule/create_times.json', $create_times_params, METHOD_POST);
        if ($add_times->error) {
            return $this->errorInfo('添加场地失败');
        }

        return $this->display($add_res);
    }

    private function __format_params($venue_types) {
        $venue_id = intval($this->input->post('venue_id', true));
        $name     = trim($this->input->post('name', true));
        $type     = intval($this->input->post('type', true));
        $mini     = intval($this->input->post('rule_mini', true));
        $detail     = $this->input->post('rule_detail', true);

        $result = array();
        if (!$name || strlen($name) > VENUE_NAME_MAX_LENGTH) {
            return $this->errorInfo('场地名称长度超出限制');
        }

        // 获取 venue_type_tag_id
        $tag_ids = array();
        foreach ($venue_types as $key => $value) {
            $tag_ids[] = $value->tag_id;
        }

        if (!$type || !$tag_ids) {
            return $this->errorInfo('错误的类型选择');
        }

        if (!$tag_ids || !in_array($type, $tag_ids)) {
            return $this->errorInfo('错误的类型选择');
        }

        $result['name'] = $name;
        $result['type'] = $type;

        // TODO 最小售卖单元目前产品设计默认为1小时，所以设置 mini = 1
        $result['mini'] = 1;

        if (!$detail || !is_array($detail)) {
            return $this->errorInfo('价格设置必填');
        }

        if (!$detail['working_days'] || !is_array($detail['working_days']) || count($detail['working_days']) > 24) {
            return $this->errorInfo('无效的工作日设置');
        }

        if (!$detail['weekend'] || !is_array($detail['weekend']) || count($detail['weekend']) > 24) {
            return $this->errorInfo('无效的周末设置');
        }

        if (!$detail['holidays'] || !is_array($detail['holidays']) || count($detail['holidays']) > 24) {
            return $this->errorInfo('无效的假期设置');
        }

        // 将价格乘100
        foreach ($detail as $key => $rule) {
            foreach ($rule as $index => $value) {
                $detail[$key][$index]['price'] = floatval($value['price']) * 100;
            }
        }

        $result['detail'] = $detail;
        return $result;
    }

    private function __add_rules($venue_id, $venue_area_id, $venue_area_res_id, $mini, $detail) {

        // 添加规则
        $add_rule = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule/add.json',  array('venue_id' => $venue_id, 'venue_area_id' => $venue_area_id, 'venue_area_res_id' => $venue_area_res_id, 'mini' => $mini), METHOD_POST);
        if ($add_rule->error) {
            return $this->errorInfo('添加规则失败');
        }

        // 添加规则详情
        $add_rule_weekday = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule_detail/add.json',  array('venue_area_rule_id' => $add_rule->lastid, 'type' => DAY_TYPE_WEEKDAY, 'detail' => json_encode($detail['working_days'], true)), METHOD_POST);
        if ($add_rule_weekday->error) {
            return $this->errorInfo('添加工作日规则详情失败');
        }

        $add_rule_weekend = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule_detail/add.json',  array('venue_area_rule_id' => $add_rule->lastid, 'type' => DAY_TYPE_WEEKEND, 'detail' => json_encode($detail['weekend'], true)), METHOD_POST);
        if ($add_rule_weekend->error) {
            return $this->errorInfo('添加周末规则详情失败');
        }

        $add_rule_holiday = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule_detail/add.json',  array('venue_area_rule_id' => $add_rule->lastid, 'type' => DAY_TYPE_HOLIDAY, 'detail' => json_encode($detail['holidays'], true)), METHOD_POST);
        if ($add_rule_holiday->error) {
            return $this->errorInfo('添加假日规则详情失败');
        }

        return true;
    }

    /**
     * 编辑场地
     */
    public function ajax_update() {
        $this->needLoginJson();
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->post('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return $this->errorInfo('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);
        if ($get_res->error) {
            return $this->errorInfo('场地不存在');
        }

        if ($get_res->result->state != VENUE_AREA_RES_STATE_UNSHELVE) {
            return $this->errorInfo('无效的场地选项');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        $params = $this->__format_params($get_venue->result->types);
        if (array_key_exists('error', $params)) {
            return $params;
        }

        $res_params['venue_area_res_id'] = $venue_area_res_id;
        $res_params['name'] = $params['name'];
        $res_params['type'] = $params['type'];

        $update_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update.json', $res_params, METHOD_POST);
        if ($update_res->error) {
            return $this->errorInfo('编辑场地失败');
        }

        // 添加场地规则
        $this->__add_rules($get_res->result->venue_id, $get_res->result->venue_area_id, $venue_area_res_id, $params['mini'], $params['detail']);

        // 触发场次生成
        $create_times_params = array(
            'venue_id' => $get_res->result->venue_id,
            'venue_area_id' => $get_res->result->venue_area_id,
            'venue_area_res_id' => $venue_area_res_id,
        );

        $add_times = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_rule/create_times.json', $create_times_params, METHOD_POST);
        if ($add_times->error) {
            return $this->errorInfo('编辑场地失败');
        }

        return $this->display($update_res);
    }

    /**
     * 场地上架
     */
    public function ajax_shelve() {
        $this->needLoginJson();
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->get('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return $this->errorInfo('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        if ($get_res->error) {
            return $this->errorInfo('场地不存在');
        }

        if ($get_res->result->state != VENUE_AREA_RES_STATE_UNSHELVE) {
            return $this->errorInfo('无效的场地选项');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);
        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        $update_shelve_params = array(
            'venue_area_res_id' => $venue_area_res_id,
            'from_state' => VENUE_AREA_RES_STATE_SHELEVING,
            'to_state' => VENUE_AREA_RES_STATE_SHELVE,
        );

        $update_shelving_params = array(
            'venue_area_res_id' => $venue_area_res_id,
            'from_state' => VENUE_AREA_RES_STATE_UNSHELVE,
            'to_state' => VENUE_AREA_RES_STATE_SHELEVING,
        );
        switch ($get_res->result->state) {
            case VENUE_AREA_RES_STATE_SHELVE:
                return $this->errorInfo('场地已上架');
                break;

            case VENUE_AREA_RES_STATE_SHELEVING:
                $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update_state.json', $update_shelve_params, METHOD_POST);
                if ($result->error) {
                    return $this->errorInfo('上架失败');
                }

                break;

            case VENUE_AREA_RES_STATE_UNSHELVE:
                // TODO 目前产品层面没有上架申请的概念，所以在此隐藏上架申请的过程
                $apply_result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update_state.json', $update_shelving_params, METHOD_POST);
                if ($apply_result->error) {
                    return $this->errorInfo('申请上架失败');
                }

                $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update_state.json', $update_shelve_params, METHOD_POST);
                if ($result->error) {
                    return $this->errorInfo('上架失败');
                }

                break;

            default:
                $this->errorInfo('错误的场地状态');
                break;
        }

        return $this->display($result);
    }

    /**
     * 场地下架
     */
    public function ajax_unshelve() {
        $this->needLoginJson();
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->get('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return $this->errorInfo('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        if ($get_res->error) {
            return $this->errorInfo('场地不存在');
        }

        if (!in_array($get_res->result->state, array(VENUE_AREA_RES_STATE_SHELVE,VENUE_AREA_RES_STATE_CAN_SELL))) {
            return $this->errorInfo('无效的场地选项');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        $update_shelve_params = array(
            'venue_area_res_id' => $venue_area_res_id,
            'from_state' => $get_res->result->state,
            'to_state' => VENUE_AREA_RES_STATE_UNSHELVE,
        );
        $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update_state.json', $update_shelve_params, METHOD_POST);
        if ($result->error) {
            return $this->errorInfo('下架失败');
        }

        return $this->display($result);
    }

    /**
     * 场地变为可售
     */
    public function ajax_to_sell() {
        $this->needLoginJson();
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->get('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return $this->errorInfo('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        if ($get_res->error) {
            return $this->errorInfo('场地不存在');
        }

        if ($get_res->result->state != VENUE_AREA_RES_STATE_SHELVE) {
            return $this->errorInfo('无效的场地选项');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        $update_shelve_params = array(
            'venue_area_res_id' => $venue_area_res_id,
            'from_state' => VENUE_AREA_RES_STATE_SHELVE,
            'to_state' => VENUE_AREA_RES_STATE_CAN_SELL,
        );
        $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/update_state.json', $update_shelve_params, METHOD_POST);
        if ($result->error) {
            return $this->errorInfo('变更失败');
        }

        return $this->display($result);
    }

    /**
     * 场地详情
     */
    public function get() {
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_area_res_id = intval($this->input->get('venue_area_res_id', true));

        if (!$venue_area_res_id) {
            return show_error('请选择场地 ID');
        }

        $get_res = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get.json', array('venue_area_res_id' => $venue_area_res_id), METHOD_GET);

        if ($get_res->error) {
            return show_error('场地不存在');
        }

        // 验证场馆
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $get_res->result->venue_id), METHOD_GET);

        if ($get_venue->error) {
            return show_error('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return show_error('场馆不存在');
        }

        return $this->display($get_res);
    }

    /**
     * 获取场地列表
     */
    public function get_list_by_venue() {
        $pk_corp  = $this->userInfo->pk_corp;
        $venue_id = intval($this->input->get('venue_id', true));
        $page = intval($this->input->get('per_page', true));
        $size = intval($this->input->get('size', true));
        $page > 0 or $page = 1;
        $size > 0 or $size = 9;
        $size < 12 or $size = 12;
        $params = compact('venue_id','page','size');
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return show_error('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return show_error('场馆不存在');
        }

        $get_ress = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res/get_list_by_venue.json', $params, METHOD_GET);
        $params['page'] = $this->input->get('per_page');
        $params['pk_corp'] = $pk_corp;
        $total = $get_ress->total;
        $size = $get_ress->size;
        $this->load->helper('pagination');
        $this->load->library('pagination');
        $pconfig = load_pagination_config($total, $size);
        $this->pagination->initialize($pconfig);
        $page_ctrl = $this->pagination->create_links();
      
        if ($get_ress->error) {
            return show_error('获取场地失败');
        }
        $get_ress->page_ctrl = $page_ctrl;
        $allow_venue_types = $this->config->item('allow_venue_types');
        $tag_ids = array_column($this->config->item('allow_venue_types'), 'tag_id');
        $allow_venue_types = array_combine($tag_ids, $allow_venue_types);
        $get_ress->venue_id = $venue_id;

        if (!$get_ress->data) {
            return $this->display($get_ress);
        }

        $venue_area_res_ids = array();
        foreach ($get_ress->data as $key => $value) {
            $venue_area_res_ids[] = $value->venue_area_res_id;
        }

        $rules = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'mapping_res_rule/get_detail_by_res_ids.json', array('venue_area_res_ids' => implode(',', $venue_area_res_ids)), METHOD_GET);

        foreach ($get_ress->data as $key => $value) {
            $venue_area_res_id = $value->venue_area_res_id;
            if (empty($rules->data->$venue_area_res_id)) {
                $get_ress->data[$key]->rules = array();
            } else {
                // 处理金额信息
                foreach ($rules->data->$venue_area_res_id as $rule) {
                    foreach ($rule as $rule_value) {
                        $rule_value->price = $this->__format_price($rule_value->price);
                    }
                }

                $get_ress->data[$key]->rules = $rules->data->$venue_area_res_id;
            }

            if (array_key_exists($value->type, $allow_venue_types)) {
                $value->type_name = $allow_venue_types[$value->type]['name'];
            }
        }

        return $this->display($get_ress);
    }

    /**
     * 格式化金额
     */
    private function __format_price($price) {
        $price = intval($price);
        if ($price <= 0) {
            return $price;
        }

        // TODO 这里暂时处理金额的信息 这块可以封装成smarty的plugins进行使用
        return number_format($price/100, 2, '.', '');
    }
}
