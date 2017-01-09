<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'controllers/FrontBase.php';


class Times extends FrontBase
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setHostName() {
        return API_HOST_OPEN_BOOK;
    }

    public function setAllowedApiList() {
        return array(
            API_HOST_OPEN_BOOK => array(
                'times/ajax_get_by_area_res_id' => 'venue_area_res_times/get_times_by_venue_area_res_id.json',
            )
        );
    }

     public function ajax_get_by_venue_id(){ 
          $params['venue_id'] = intval($this->input->get('venue_id', true));
          $params['day'] = $this->input->get('day');
          $get_times = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_res_times/get_times_by_venue_id.json', $params, METHOD_GET,true,2,500);
          $this->display($get_times);
     }
     public function ajax_post_stop_times(){ 
          $params['venue_area_res_times_ids'] = intval($this->input->post('venue_area_res_times_ids', true));
          $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_res_times/stop_times.json', $params, METHOD_POST);
          $this->display($data);
     }
     public function ajxa_post_start_times(){ 
          $params['venue_area_res_times_ids'] = intval($this->input->post('venue_area_res_times_ids', true));
          $data = $this->callInnerApiDiy(API_HOST_OPEN_BOOK, 'venue_area_res_times/start_times.json', $params, METHOD_POST);
          $this->display($data);
     }

    /**
     * 获取场馆的场次列表根据
     */
    // public function ajax_get_by_area_res_id() {
    //     $this->needLoginJson();
    //     $venue_area_res_id = intval($this->input->post('venue_area_res_id', true));
    //     if (empty($venue_area_res_id)) {
    //         return show_error('区域资源ID不能为空');
    //     }

    //     $request_params = array(
    //         'venue_area_res_id' => $venue_area_res_id
    //     );

    //     $venue_area_res_times = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res_times/get_times_by_venue_area_res_id.json', $request_params, METHOD_GET);
    //     if (!$venue_area_res_times->error) {
    //         return show_error('场次不存在');
    //     }

    //     if (!$venue_area_res_times->data) {
    //         return show_error('场次不存在');
    //     }

    //     $data = array();
    //     foreach ($venue_area_res_times->data as $key => $value) {
    //         if ($value) {
    //             foreach ($value as $times_key => $times) {
    //                 $times_start_key = date('H', strtotime($times['times_start']));
    //                 $data[$key][$times_key][$times_start_key] = $times;
    //             }
    //         } else {
    //             $data[$key] = array();
    //         }
    //     }

    //     $venue_area_res_times->data = $data;
    //     return $this->display($venue_area_res_times);
    // }

    /**
     * 获取场馆的场次列表根据
     */
    // public function ajax_get_by_venue_id() {
    //     $this->needLoginJson();
    //     $venue_id = intval($this->input->post('venue_id', true));
    //     $day = trim($this->input->post('day', true));
    //     if (empty($venue_id)) {
    //         return show_error('场馆ID不能为空');
    //     }

    //     // 验证day
    //     $rebuild_day = "";
    //     if (!empty($day)) {
    //         $rebuild_day = date('Y-m-d', strtotime($day));
    //     }

    //     $request_params = array(
    //         'venue_id' => $venue_id,
    //         'day' => $rebuild_day
    //     );

    //     $venue_area_res_times = $this->callInnerApiDiy(API_HOST_OPEN_VENUE, 'venue_area_res_times/get_times_by_venue_id.json', $request_params, METHOD_GET);
    //     if (!$venue_area_res_times->error) {
    //         show_error('场次不存在');
    //     }

    //     if (!$venue_area_res_times->data) {
    //         return show_error('场次不存在');
    //     }

    //     $data = array();
    //     foreach ($venue_area_res_times->data as $key => $value) {
    //         if ($value) {
    //             $data[$key] = $value;
    //             $times = array();
    //             foreach ($value['times'] as $times_key => $times) {
    //                 $times_start_key = date('H', strtotime($times['times_start']));
    //                 $times[$times_start_key] = $times;
    //             }

    //             $data[$key]['times'] = $times;
    //         } else {
    //             $data[$key] = array();
    //         }
    //     }

    //     $venue_area_res_times->data = $data;

    //     return $this->display($venue_area_res_times);
    // }
}