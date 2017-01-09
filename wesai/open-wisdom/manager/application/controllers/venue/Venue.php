<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Venue extends FrontBase
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
                'venue/add'           => 'venue/add.json',
                'venue/ajax_update'   => 'venue/update.json',
                'venue/ajax_shelve'   => 'venue/apply_online.json',
                'venue/ajax_unshelve' => 'venue/offline.json',
                'venue/get'           => 'venue/get.json',
                'venue/get_list'      => 'venue/search_manage.json',
                'venue/add_image'     => 'venue_template/add.json',
                'venue/ajxa_search_manage' => 'venue/search_manage.json',
                'venue/ajxa_list_by_venue' => 'venue_area_res/get_list_by_venue.json',
                'venue/ajax_get_images'   => 'venue/get_image_list.json',
                'venue/view_venue_status' => REQUEST_TPL
            ),
            API_HOST_OPEN_COMMON => array(
                'tag/get_by_ids'       => 'tag/get_by_ids.json',
                'location/get_by_name' => 'location/get_by_name.json',
            )
        );
    }

    /**
     * 获取添加场馆模板
     */
    public function  get_add_page(){
        $data['allow_venue_types'] = $this->config->item('allow_venue_types');
        $data['select_start_date'] = get_select_start_date();
        $data['select_end_date'] = get_select_end_date();

        return $this->display($data);
    }

    /**
     * 获取编辑场馆模板
     */
    public function  get_edit_page(){
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

        if ($get_venue->result->state != VENUE_STATE_DOWN) {
            return show_error('无效的场馆选项');
        }

        $get_venue->allow_venue_types = $this->config->item('allow_venue_types');

        $get_venue->result->open_time = json_decode($get_venue->result->open_time, true);
        $get_venue->result->locations = $this->__format_locations($get_venue->result->locations);


        $get_venue->result->types = $this->__format_types($get_venue->result->types);
        $get_venue->select_start_date = get_select_start_date();
        $get_venue->select_end_date   = get_select_end_date();

        return $this->display($get_venue);
    }

    /**
     * 添加场馆
     */
    public function ajax_add(){
        $this->needLoginJson();

        // 添加场馆
        $venue_params = $this->__format_params();
        if (array_key_exists('error', $venue_params)) {
            return $venue_params;
        }

        $images = $venue_params['images'];
        unset($venue_params['images']);

        $add_result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/add.json', $venue_params, METHOD_POST);

        if ($add_result->error) {
            return $this->errorInfo('场馆添加失败');
        }

        // 添加场馆模板
        $template_params = array(
            'venue_id' => $add_result->lastid,
            'name' => 'default',
            'area' => 200,
        );
        if ($images) {
            $template_params['disgram_fileid'] = $images[0];
        }

        $template = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_template/add.json', $template_params, METHOD_POST);

        // 添加场馆图片
        if ($images) {
            foreach ($images as $image_id) {
                $image_params = array(
                    'venue_id' => $add_result->lastid,
                    'file_id' => $image_id,
                );

                try {
                    $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/add_image.json', $image_params, METHOD_POST);

                } catch (Exception $e) {
                    // 暂不处理
                    continue;
                }
            }
        }

        return $this->display($add_result);
    }

    private function __format_params() {
        $pk_corp      = $this->userInfo->pk_corp;
        $pk_corp_user = $this->userInfo->pk_corp_user;

        $name      = trim($this->input->post('name', true));
        $phone     = trim($this->input->post('phone', true));
        $address   = trim($this->input->post('address', true));
        $longitude = trim($this->input->post('longitude', true));
        $latitude  = trim($this->input->post('latitude', true));
        $intro     = trim($this->input->post('intro', true));
        $open_time = $this->input->post('open_time', true);
        $images    = $this->input->post('images', true);
        $types     = $this->input->post('types', true);
        $locations = $this->input->post('locations', true);

        $result = array();
        if (!$name || strlen($name) > VENUE_NAME_MAX_LENGTH) {
            return $this->errorInfo('场馆名称长度超出限制');
        }

        if (!$phone || preg_match("/^(((0\d{2,3}-)?\d{7,8}(-\d{1,5})?))$/", $phone)) {
            return $this->errorInfo('客服电话格式不正确');
        }

        if (!$address || strlen($address) > VENUE_ADDRESS_MAX_LENGTH) {
            return $this->errorInfo('场馆详细地址长度超出限制');
        }

        if (!$intro || strlen($intro) > VENUE_INTRO_MAX_LENGTH) {
            return $this->errorInfo('场馆图文简介长度超出限制');
        }

        $result['corp_id'] = $pk_corp;
        $result['corp_user_id'] = $pk_corp_user;
        $result['name'] = $name;
        $result['phone'] = $phone;
        $result['address'] = $address;
        $result['longitude'] = $longitude;
        $result['latitude'] = $latitude;
        $result['intro'] = $intro;

        if (!$open_time || !is_array($open_time)) {
            return $this->errorInfo('场馆开放时间必填');
        }

        if (!array_key_exists('working_days', $open_time) || !is_array($open_time['working_days'])) {
            return $this->errorInfo('场馆开放时间必填');
        }

        if (!array_key_exists('weekend', $open_time) || !is_array($open_time['weekend'])) {
            return $this->errorInfo('场馆开放时间必填');
        }

        if (!array_key_exists('holidays', $open_time) || !is_array($open_time['holidays'])) {
            return $this->errorInfo('场馆开放时间必填');
        }

        $open_time = array(
            'working_days' => array(
                'start' => date('H:i', strtotime($open_time['working_days']['start'])),
                'end' => date('H:i', strtotime($open_time['working_days']['end'])),
            ),
            'weekend' => array(
                'start' => date('H:i', strtotime($open_time['weekend']['start'])),
                'end' => date('H:i', strtotime($open_time['weekend']['end'])),
            ),
            'holidays' => array(
                'start' => date('H:i', strtotime($open_time['holidays']['start'])),
                'end' => date('H:i', strtotime($open_time['holidays']['end'])),
            )
        );
        $result['open_time'] = json_encode($open_time, true);

        // types
        if (!$types || !is_array($types)) {
            return $this->errorInfo('错误的类型选项');
        }

        $types = array_unique(array_filter($types));
        $params_types = implode(',', $types);
        $get_type_tags = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'tag/get_by_ids.json', array('ids' => $params_types), METHOD_GET);

        if ($get_type_tags->error) {
            return $this->errorInfo('错误的类型选项');
        }
        if (count($get_type_tags->data) != count($types)) {
            return $this->errorInfo('错误的类型选项');
        }

        $result['types'] = $params_types;

        // localtions
        if (!$locations || !is_array($locations)) {
            return $this->errorInfo('错误的地区选项');
        }
        $country = trim($locations['country']);
        $province = trim($locations['province']);
        $city     = trim($locations['city']);
        $district   = trim($locations['district']);

        if($province == $city){
            $province = mb_substr($province,0,-1,"utf8");
        }
        if (!$province || !$city) {
            return $this->errorInfo('错误的地区选项');
        }

        $result_locations = array();
        $location_names = array(array('name' => $country, 'level' => 1),array('name' => $province, 'level' => 2),array('name' => $city, 'level' => 3),array('name' => $district, 'level' => 4));
        $names = json_encode($location_names);
        $location_names = compact('names');
        $get_level = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'location/get_by_names.json', $location_names, METHOD_GET);
        if ($get_level->error) {
            return $this->errorInfo('错误的地区选项');
        }
        if(count($get_level->data)<=0){
            return $this->errorInfo('错误的地区选项');
        }
        $locations = array();
        foreach ($get_level->data as $key => $value) {
            $locations[$value->tag_id] = $value->level;
        }

        $result['locations'] = json_encode($locations, true);
        // images
        $result['images'] = array();
        if ($images && is_array($images)) {
            foreach ($images as $key => $value) {
                $image_id = trim($value);
                if (!$image_id || strlen($image_id) > 80) {
                    continue;
                }

                $result['images'][] = $image_id;
            }
        }

        return $result;
    }

    /**
     * 编辑场馆
     */
    public function ajax_update() {
        $this->needLoginJson();

        $pk_corp      = $this->userInfo->pk_corp;
        $venue_id = intval($this->input->post('venue_id', true));

        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->state != VENUE_STATE_DOWN) {
            return $this->errorInfo('无效的场馆状态');
        }

        $venue_params = $this->__format_params();
        if (array_key_exists('error', $venue_params)) {
            return $venue_params;
        }
        $venue_params['venue_id'] = $venue_id;

        $venue_params['venue_id'] = $venue_id;

        $types = $venue_params['types'];
        $images = implode(',', $venue_params['images']);
        unset($venue_params['types']);
        unset($venue_params['images']);

        $update_result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update.json', $venue_params, METHOD_POST);
        if ($update_result->error) {
            return $this->errorInfo('场馆更新失败');
        }

        // TODO 请求时间过长临时将超时时间调整为1000毫秒
        $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_types.json', array('venue_id' => $venue_id, 'types' => $types), METHOD_POST, true, 2, 1000);

        if ($images) {
            $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_images.json', array('venue_id' => $venue_id, 'images' => $images), METHOD_POST);
        }

        return $this->display($update_result);
    }

    /**
     * 场馆上架
     */
    public function ajax_shelve() {
        $this->needLoginJson();
        $pk_corp   = $this->userInfo->pk_corp;

        $venue_id  = intval($this->input->post('venue_id', true));
        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');
        }

        $up_params = array(
            'venue_id' => $venue_id,
            'from_state' => VENUE_STATE_UPING,
            'to_state' => VENUE_STATE_UP,
        );

        $uping_params = array(
            'venue_id' => $venue_id,
            'from_state' => VENUE_STATE_DOWN,
            'to_state' => VENUE_STATE_UPING,
        );
        switch ($get_venue->result->state) {
            case VENUE_STATE_UP:
                return $this->errorInfo('场馆已上架');
                break;

            case VENUE_STATE_UPING:
                $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_state.json', $up_params, METHOD_POST);
                if ($result->error) {
                    return $this->errorInfo('上架失败');
                }

                break;

            case VENUE_STATE_DOWN:
                // TODO 目前产品层面没有上架申请的概念，所以在此隐藏上架申请的过程
                $apply_result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_state.json', $uping_params, METHOD_POST);
                if ($apply_result->error) {
                    return $this->errorInfo('申请上架失败');
                }

                $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_state.json', $up_params, METHOD_POST);
                if ($result->error) {
                    return $this->errorInfo('上架失败');
                }

                break;

            default:
                return show_error('错误的场馆状态');
                break;
        }

        return $this->display($result);
    }

    /**
     * 场馆下架
     */
    public function ajax_unshelve() {
        $this->needLoginJson();
        $pk_corp      = $this->userInfo->pk_corp;
        $venue_id = intval($this->input->post('venue_id', true));

        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return $this->errorInfo('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return $this->errorInfo('场馆不存在');

        }

        $params = array(
            'venue_id' => $venue_id,
            'from_state' => VENUE_STATE_UP,
            'to_state' => VENUE_STATE_DOWN,
        );
        $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/update_state.json', $params, METHOD_POST);

        if ($result->error) {
            return $this->errorInfo('下架失败');
        }

        return $this->display($result);
    }

    /**
     * 场馆详情
     */
    public function get() {
        $pk_corp      = $this->userInfo->pk_corp;
        $venue_id  = intval($this->input->post('venue_id', true));

        $get_venue = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get.json', array('venue_id' => $venue_id), METHOD_GET);

        if ($get_venue->error) {
            return show_error('场馆不存在');
        }

        if ($get_venue->result->corp_id != $pk_corp) {
            return show_error('场馆不存在');
        }

        $get_venue->result->locations = $this->__format_locations($get_venue->result->locations);

        return $this->display($get_venue);
    }

    private function __format_locations($locations) {
        if (!$locations || !count($locations)) {
            return array();
        }

        $tag_ids = array();
        foreach ($locations as $key => $value) {
            $tag_ids[] = $value->tag_id;
        }

        $ids = implode(',', array_unique(array_filter($tag_ids)));
        $get_tags = $this->callInnerApiDiy(API_HOST_OPEN_COMMON, 'tag/get_by_ids.json', array('ids' => $ids), METHOD_GET);
        if ($get_tags->error) {
            return show_error('场馆地址信息错误');
        }

        $names = array();
        foreach ($get_tags->data as $key => $value) {
            $names[$value->tag_id] = $value->name;
        }

        $rebuild_locations = array(
            'country'   => '',
            'province'  => '',
            'city'      => '',
            'district'  => ''
            );
        foreach ($locations as $key => $value) {
            $name = '';
            switch ($value->level) {
                case 1:
                    $name = 'country';
                    break;
                case 2:
                    $name = 'province';
                    break;
                case 3:
                    $name = 'city';
                    break;
                default:
                    $name = 'district';
                    break;
            }
            $rebuild_locations[$name] = $names[$value->tag_id];
        }

        return $rebuild_locations;
    }

    private function __format_types($types) {
        if (!$types || !count($types)) {
            return array();
        }

        $result = array();
        foreach ($types as $key => $value) {
            $result[] = $value->tag_id;
        }

        return $result;
    }

    /**
     * 获取场馆列表
     */
    public function get_list() {

        $pk_corp = $this->userInfo->pk_corp;
        $page    = $this->input->get('page', true);

        $page > 0 or $page = 1;
        $size = 20;

        $params = array(
            'corp_id' => $pk_corp,
            'page'    => $page,
            'size'    => $size,
        );
        $result = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/search_manage.json', $params, METHOD_GET);

        if (!empty($result->data)) {
            $venue_ids = array();
            foreach ($result->data as $value) {
                $value->open_time = $this->__format_open_time($value->open_time);
                $venue_ids[] = $value->venue_id;
            }

            // todo 这里会根据场馆id获取types但是一个list页面有多个types造成不确定查询数据的数量(底层控制是默认是十条)，最好让list可以进行分页这里能直接控制查询数据的数量
            $types = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue/get_types_by_venues.json', array('venue_ids' => json_encode($venue_ids, true)), METHOD_GET);
            if ($types->error) {
                return show_error('获取场馆类型失败');
            }

            $types = $this->__rebuild_types($types->data);

            $rebuild_types = array();
            $allow_venue_types = $this->config->item('allow_venue_types');
            foreach ($result->data as $key => $value) {
                if (!$types || !array_key_exists($value->venue_id, $types)) {
                    continue;
                }

                $rebuild_types = $types[$value->venue_id];

                $allow_venue_type_names = array();
                foreach ($allow_venue_types as $vallow_venue_type) {
                    if (in_array($vallow_venue_type['tag_id'], $rebuild_types)) {
                        $allow_venue_type_names[] = $vallow_venue_type['name'];
                    }
                }
                $value->type_count = count($allow_venue_type_names);
                $value->type_name = implode('、', $allow_venue_type_names);
            }
        }

        return $this->display($result);
    }

    /**
     * __rebuild_types
     */
    private function __rebuild_types($types) {
        if (empty($types)) {
            return array();
        }

        $result = array();
        foreach ($types as $key => $value) {
            $result[$value->venue_id][] = $value->tag_id;
        }

        return $result;
    }

    /**
     * 格式化open_time
     */
    private function __format_open_time($open_time) {
        if (!is_string($open_time)) {
            return $open_time;
        }

        return json_decode($open_time, true);
    }
}
