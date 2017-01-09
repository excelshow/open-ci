<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Base.php';
/**
 * 代金券接口类
 *
 * @package default
 * @author  : liangkaixuan@wesai.com
 **/
class Activity extends Base
{
	/**
	 * construct
	 **/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('activity/Activity_model');
        $this->load->model('voucher/Rule_model');
        $this->load->model('voucher/Voucher_model');
	}

	/**
	 * 活动添加
     * @param corp_id name ...
     * @return json
	 */
	public function add_post()
    {
        $param = $this->checkParam();
        if($param['number'] >$param['number_max']){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NUMBER_MAX_ERROR);
        }
        //存储数据
        $result = $this->Activity_model->create($param);
        if (empty($result)) {
            return $this->response_error(Error_Code::ERROR_ADD_ACTIVITY_FAILED);
        }
        return $this->response_insert($result);
	}

    /**
     * @param post过来的请求数据
     * @return mixed
     */
    private function checkParam(){
        $fk_corp    = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);        //企业id
        $name       = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);                           //代金券规则名称
        $time_start = $this->post_check('time_start', PARAM_NOT_NULL_NOT_EMPTY);                     //活动开始时间
        $time_end   = $this->post_check('time_end', PARAM_NOT_NULL_NOT_EMPTY);                       //活动开始时间
        $need_follow= $this->post_check('need_follow', PARAM_NOT_NULL_NOT_EMPTY);                    //是否需要关注公众号
        $number_max = $this->post_check('number_max', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);  //最多参与次数
        $number     = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);      //默认参与次数
        $order      = (int)$this->post_check('order', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);          //排序
        $banner     = $this->post_check('banner', PARAM_NOT_NULL_NOT_EMPTY);                         //图片
        $desc_rule  = $this->post_check('desc_rule', PARAM_NULL_EMPTY);                              //规则介绍
        //$desc       = $this->post_check('desc', PARAM_NULL_EMPTY);                                 //活动描述
        $number_invite_one  = $this->post_check(
            'number_invite_one',
            PARAM_NOT_NULL_NOT_EMPTY,
            PARAM_TYPE_NUMBER);   //邀请一个人可参与次数

        return array(
            'fk_corp'       => $fk_corp,
            'name'          => $name,
            'time_start'    => $time_start,
            'time_end'      => $time_end,
            'need_follow'   => $need_follow,
            'number_max'    => $number_max,
            'number'        => $number,
            'orderby'       => $order,
            'banner'        => $banner,
            'number_invite_one' => $number_invite_one,
            'desc_rule'     => $desc_rule,
            //'desc'     => $desc
        );
    }

    /**
     * 修改活动信息
     * @param post过来的请求数据
     * @return json
     */
    public function modify_post(){

        $param = $this->checkParam();
        $fk_activity = $this->post_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT); //活动id

        $activityInfo = $this->Activity_model->get_by_pk_activity($fk_activity);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }
        if($param['number'] >$param['number_max']){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NUMBER_MAX_ERROR);
        }

        $result = $this->Activity_model->saveActivity($param, $fk_activity);
        return $this->response_update($result);
    }

    /**
     * 修改活动状态
     * @param corp_id activity_id
     * $return json
     */
    public function modify_state_post(){
        $fk_corp    = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);      //企业id
        $fk_activity= $this->post_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT); //活动id
        $state      = $this->post_check('state', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);          //状态
        $activityInfo = $this->Activity_model->get_by_pk_activity($fk_activity);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }elseif($activityInfo['fk_corp'] != $fk_corp){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_CORP_ERROR);
        }elseif($state == OPERATION_ACTIVITY_STATE_START  && strtotime($activityInfo['time_end']) < time()){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_TIME_END_ERROR);
        }

        $result = $this->Activity_model->saveStateActivity($state, $fk_activity);
        return $this->response_update($result);
    }
    
    /**
     * 活动列表
     * @param corp_id  企业id
     * @param state    状态
     * @return json
     */
    public function list_get(){

        $fk_corp    = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//企业id
        $state      = $this->get_check('state', PARAM_NULL_EMPTY, PARAM_TYPE_INT);          //状态
        $page       = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //页数
        $size       = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //每页条数

        $param = array(
            'fk_corp'    => $fk_corp,
            'state'      => $state
        );

        if(empty($size)) {
            $size = OPERATION_ACTIVITY_LIST_SIZE;
        }
        if(empty($page)) {
            $page = OPERATION_LIST_PAGE;
        }
        if(empty($order)) {
            $order = 'orderby';
        }
        if(empty($sort)) {
            $sort = 'asc';
        }

        $result = $this->Activity_model->lists($param,$page,$size,$order,$sort);
        return $this->response_list($result['data'], $result['sum'], $page, $size);
    }
    /**
     * 根据活动id 获取活动信息
     * @param activity_id 活动id
     * @return json
     */
    public function details_get(){
        $activity_id= $this->get_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //活动主键
        $user_id= $this->get_check('user_id', PARAM_NULL_EMPTY, PARAM_TYPE_INT);                    //用户id
        $result = $this->Activity_model->details($activity_id, $user_id);
        return $this->response_object($result);
    }
    /**
     * 根据 活动id 企业id 获取未绑定的代金券规则信息
     * @param corp_id  企业id
     * @param activity_id 活动id
     * @return json
     */
    public function nobing_list_get(){
        $fk_corp = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//企业id
        $fk_activity = $this->get_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//活动id
        $result = $this->Activity_model->nobind_operation_list($fk_activity, $fk_corp);
        return $this->response_list($result);
    }

	/**
	 * 营销活动绑定营销工具
	 *
	 * @param  fk_corp 企业主键id fk_activity活动id
     * @param type 营销活动类型
     * @param rule 规则id（如：代金券规则id）
     *
	 * @return json/lastid
	 */
	public function bind_operation_post()
	{
        $param = $this->checkBindparam();
        //活动是否存在
        $activityInfo = $this->Activity_model->get_by_pk_activity($param['fk_activity']);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }elseif($activityInfo['fk_corp'] != $param['fk_corp']){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_CORP_ERROR);
        }
        $ids = explode(',',trim($param['rule'],','));
        foreach($ids as $k=>$rule){
            //规则是否存在
            $ruleInfo = $this->Rule_model->get_by_fk_voucher_rule($rule);
            if(empty($ruleInfo)){
                return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
            }
            //查询是否已经绑定
            $bindParam = $param;
            $bindParam['rule'] = $rule;
            $bingInfo = $this->Activity_model->get_by_bingactivity($bindParam);
            if($bingInfo){
                return $this->response_error(Error_Code::ERROR_ACTIVITY_BING_HAVE);
            }
        }

        $result = $this->Activity_model->bind_operation($param);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_BING_RULE_FAILED);
        }
        return $this->response_Object($result);
	}
    /**
     * 验证绑定/取消 参数
     * @return mixed
     */
    private function checkBindparam(){
        $fk_corp     = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);      //企业id
        $fk_activity = $this->post_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);  //活动id
        $type        = $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);         //类型
        $rule        = $this->post_check('rule', PARAM_NOT_NULL_NOT_EMPTY);         //规则id
        return array(
            'fk_corp'     => $fk_corp,
            'fk_activity' => $fk_activity,
            'type'        => $type,
            'rule'        => $rule
        );
    }
    /**
     * 取消 营销活动绑定营销工具
     *
     * @param  fk_corp 企业主键id fk_activity活动id
     * @param type 营销活动类型
     * @param rule 规则id（如：代金券规则id）
     *
     * @return json/bool
     *
     */
    public function unbind_operation_post(){

        $param = $this->checkBindparam();
        //活动是否存在
        $activityInfo = $this->Activity_model->get_by_pk_activity($param['fk_activity']);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }elseif($activityInfo['fk_corp'] != $param['fk_corp']){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_CORP_ERROR);
        }
        $result = $this->Activity_model->unbind_operation($param);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_BING_RULE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 用户参与一次活动
     * @param component_authorizer_app_id 公众号id  user_id activity_id
     *
     * @return lastid
     */
    public function user_action_post(){
        $component_authorizer_app_id = $this->post_check(
            'component_authorizer_app_id',
            PARAM_NOT_NULL_NOT_EMPTY,
            PARAM_TYPE_INT
        );      //公众号id
        $activity_id = $this->post_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//活动id
        $user_id     = $this->post_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //用户id
        $type        = $this->post_check('type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);       //类型
        $rule        = $this->post_check('rule', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);       //规则

        //判断活动是否存在且未结束
        $activityInfo = $this->Activity_model->get_by_pk_activity($activity_id);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }elseif(time()<strtotime($activityInfo['time_start'])){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTSTART);
        }elseif(time()>strtotime($activityInfo['time_end'])){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_END);
        }
        //规则是否到截止日期 且规则是否是已完成状态
        $ruleInfo = $this->Rule_model->get_by_fk_voucher_rule($rule);
        if(empty($ruleInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif(time()>strtotime($ruleInfo['date_stop'])){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_OVERDUE);
        }elseif($ruleInfo['state'] != OPERATION_VOUCHER_RULE_STATE_GENERATED){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }

        //判断活动 跟规则是否匹配
        $mappingInfo = $this->Activity_model->get_by_activity_operation($activity_id,$rule,$type);
        if(empty($mappingInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_RULE_NOBIND);
        }

        //判断用户已经参与次数是否超过默认次数
        $partake = $this->Activity_model->partake($activity_id, $user_id);
        if($partake >= $activityInfo['number']){
            //判断是否超过可参与总数
            if($partake >= $activityInfo['number_max']){
                return $this->response_error(Error_Code::ERROR_ACTIVITY_PARTAKE_MIX);
            }
            //查询是否邀请用户 折算 邀请人可参与次数
            $intviteTake = $this->Activity_model->intviteTake($activity_id, $user_id,$activityInfo['number_invite_one']);
            if(empty($intviteTake)){
                return $this->response_error(Error_Code::ERROR_ACTIVITY_PARTAKE_USEUP);
            }else{
                //判断是否将邀请用户参与次数用完
                if(($partake - $activityInfo['number']) >= $intviteTake){
                    return $this->response_error(Error_Code::ERROR_ACTIVITY_PARTAKE_USEUP);
                }
            }
        }

        //判断代金券是否使用完
        $code = $this->Voucher_model->getCode($rule,$activityInfo['fk_corp']);
        if(empty($code)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CODE_NOHAVE);
        }
        //添加参与活动信息
        $result = $this->Activity_model->user_action($component_authorizer_app_id,$activity_id,$user_id,$type,$rule,$code);
        if (empty($result)) {
            return $this->response_error(Error_Code::ERROR_ADD_ACTIVITY_FAILED);
        }
        //获取可参与次数与已参与次数
        $actionNumber = $this->Activity_model->user_action_number($activity_id, $user_id, $activityInfo);
        return $this->response_object($actionNumber);
    }

    /**
     * 查询用户参与活动列表
     * @param activity_id 活动id
     * @param user_id 用户id
     * @param 分页
     * @return json
     */
    public function list_user_action_get(){
        $activity_id = $this->get_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);      //活动id
        $user_id     = $this->get_check('user_id', PARAM_NULL_NOT_EMPTY, PARAM_TYPE_INT);      //用户id
        $page        = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //页数
        $size        = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //每页条数

        if(empty($size)) {
            $size = OPERATION_ACTIVITY_LIST_SIZE;
        }
        if(empty($page)) {
            $page = OPERATION_LIST_PAGE;
        }
        if(empty($order)) {
            $order = 'ctime';
        }
        if(empty($sort)) {
            $sort = 'desc';
        }
        $activityInfo = $this->Activity_model->get_by_pk_activity($activity_id);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }
        //获取列表
        $result = $this->Activity_model->list_user_action($activity_id,$user_id,$page,$size,$order,$sort);
        return $this->response_list($result['data'], $result['sum'], $page, $size);

    }

    /**
     * 用户参与活动邀请被邀请记录
     * @param component_authorizer_app_id
     * @param user_id
     * @param activity_id
     * @param invited_fk_user
     * @return json
     */
    public function user_invite_post(){
        $activity_id                = $this->post_check('activity_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);        //活动id
        $user_id                    = $this->post_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);            //用户id
        $component_authorizer_app_id= $this->post_check('component_authorizer_app_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);      //公众号id
        $invited_fk_user            = $this->post_check('invited_fk_user', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //被邀请用户id

        //判断活动是否存在且未结束
        $activityInfo = $this->Activity_model->get_by_pk_activity($activity_id);
        if(empty($activityInfo)){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTEXIST);
        }elseif(time()<strtotime($activityInfo['time_start'])){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_NOTSTART);
        }elseif(time()>strtotime($activityInfo['time_end'])){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_END);
        }

        //判断是否邀请过
        $inviteInfo = $this->Activity_model->get_by_invite($activity_id,$user_id,$invited_fk_user);
        if($inviteInfo){
            return $this->response_error(Error_Code::ERROR_ACTIVITY_INVITE_HAVE);
        }

        //添加邀请人信息
        $result = $this->Activity_model->user_invite($component_authorizer_app_id,$activity_id,$user_id,$invited_fk_user);
        if(empty($result)) {
            return $this->response_error(Error_Code::ERROR_ACTIVITY_ADD_INVITE_FAILED);
        }
        return $this->response_insert($result);


    }







} // END class
