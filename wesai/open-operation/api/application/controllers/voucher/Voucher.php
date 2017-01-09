<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Base.php';
/**
 * 代金券接口类
 *
 * @package default
 * @author  : liangkaixuan@wesai.com
 **/
class Voucher extends Base
{
	/**
	 * construct
	 **/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('voucher/Voucher_model');
		$this->load->model('voucher/Rule_model');
        $this->load->helper("diy");
	}

	/**
	 * 代金券规则添加
     * @param corp_id name ...
     * @return json
	 */
	public function generate_post()
    {
        $fk_corp         = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);        //企业id
        $fk_voucher_rule = $this->post_check('voucher_rule_id', PARAM_NOT_NULL_NOT_EMPTY);                //代金券规则id
        $number          = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);      //代金券数量

        $param = array(
            'fk_corp'         => $fk_corp,
            'fk_voucher_rule' => $fk_voucher_rule,
            'number'          => $number
        );
        //查询规则信息
        $voucherruleInfo = $this->Rule_model->get_by_fk_voucher_rule($fk_voucher_rule);
        if(empty($voucherruleInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($voucherruleInfo['state'] != OPERATION_VOUCHER_RULE_STATE_PASSED && $voucherruleInfo['state'] != OPERATION_VOUCHER_RULE_STATE_GENERATING){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }
        $result = $this->Voucher_model->create($param,$voucherruleInfo);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_GENERATE_FAILED);
        }else{
            return $this->response_object($result);
        }

	}

    
    /**
     * 代金券列表
     * @param corp_id  scope_type state 分页等
     * @return json
     */
    public function list_get(){

        $fk_voucher_rule = $this->get_check('voucher_rule_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//规则id
        $fk_corp         = $this->get_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//企业id
        $state           = $this->get_check('state', PARAM_NULL_EMPTY, PARAM_TYPE_INT);          //状态
        $code            = $this->get_check('code', PARAM_NULL_EMPTY, PARAM_TYPE_STRING);        //卡密
        $page            = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //页数
        $size            = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //每页条数

        $param = array(
            'fk_voucher_rule' => $fk_voucher_rule,
            'fk_corp' => $fk_corp,
            'state'   => $state,
            'code'    => $code
        );

        if(empty($size)) {
            $size = OPERATION_VOUCHER_LIST_SIZE;
        }
        if(empty($page)) {
            $page = OPERATION_LIST_PAGE;
        }
        if(empty($order)) {
            $order = 'ctime';
        }
        if(empty($sort)) {
            $sort = 'asc';
        }

        $result = $this->Voucher_model->lists($param,$page,$size,$order,$sort);
        return $this->response_list($result['data'], $result['sum'], $page, $size);

    }

    /**
     * 代金券转让、初授
     *
     * @param corp_id
     * @param component_authorizer_app_id
     * @param voucher_id
     * @param front_user_id -> to_user_id
     *
     * @return json
     */
    public function change_user_post(){
        $voucher_id  = $this->post_check('voucher_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT); //代金券主键
        $to_user_id  = $this->post_check('to_user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT); //转让给 用户id
        $from_user_id = $this->post_check('from_user_id', PARAM_NOT_NULL_EMPTY, PARAM_TYPE_INT);  //从哪里来 用户id
        $fk_corp      = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);   //企业id
        $component_authorizer_app_id = $this->post_check(
            'component_authorizer_app_id',
            PARAM_NOT_NULL_NOT_EMPTY,
            PARAM_TYPE_INT
        );//来源公众号

        //查询代金券主键是否存在且是否属于该用户
        $voucherInfo = $this->Voucher_model->get_by_pk_voucher($voucher_id);
        if(empty($voucherInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_NOTEXIST);
        }elseif($voucherInfo['fk_user'] != $from_user_id){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CHANGE_USER_NOTBELONG);
        }

        $param = array(
            'voucher_id'   => $voucher_id,
            'to_user_id'   => $to_user_id,
            'from_user_id' => $from_user_id,
            'fk_corp'      => $fk_corp,
            'component_authorizer_app_id' => $component_authorizer_app_id
        );

        $result = $this->Voucher_model->change_user($param);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CHANGE_FAILED);
        }
        return $this->response_update($result);

    }

    /**
     * 根据用户id获取用户名下未过期的代金券信息
     * @param user_id component_authorizer_app_id 企业号id
     * @return json
     */
    public function list_by_uid_get(){
        $user_id                     = $this->get_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);//用户id
        $component_authorizer_app_id = $this->get_check(
            'component_authorizer_app_id',
            PARAM_NOT_NULL_NOT_EMPTY,
            PARAM_TYPE_INT
        );//公众号id
        $state                       = $this->get_check('state', PARAM_NULL_EMPTY, PARAM_TYPE_INT);          //状态
        $page                        = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //页数
        $size                        = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //每页条数

        $param = array(
            'user_id'   => $user_id,
            'state'     => $state,
            'component_authorizer_app_id' => $component_authorizer_app_id
        );

        if(empty($size)) {
            $size = OPERATION_VOUCHER_USER_SIZE;
        }
        if(empty($page)) {
            $page = OPERATION_LIST_PAGE;
        }
        if(empty($order)) {
            $order = 'ctime';
        }
        if(empty($sort)) {
            $sort = 'asc';
        }
        $result = $this->Voucher_model->list_by_uid($param,$page,$size,$order,$sort);
        return $this->response_list($result['data'], $result['sum'], $page, $size);

    }

	/**
	 * 消费代金券
	 *
	 * @param  corp_id 企业主键id
     * @param  component_authorizer_app_id 公众号主键
     * @param  code 卡密
     * @param  order_id 订单主键
     * @param  user_id 用户主键
	 * @return json/bool
	 */
	public function consume_post()
	{
        $user_id                     = $this->post_check('user_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $component_authorizer_app_id = $this->post_check('component_authorizer_app_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $code                        = $this->post_check('code', PARAM_NOT_NULL_NOT_EMPTY);
        $order_id                    = $this->post_check('order_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $amount                      = $this->post_check('amount', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        //确认是否存在且未使用
        $voucherInfo = $this->Voucher_model->get_by_code($code);
        if(empty($voucherInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_NOTEXIST);
        }

        if($voucherInfo['state'] == OPERATION_VOUCHER_STATE_USE){
            return $this->response_error(Error_Code::ERROR_VOUCHER_USE_ALREADY);
        }

        if(time()>strtotime($voucherInfo['stop_time'])){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CODE_OVERDUE);
        }

        if($voucherInfo['fk_component_authorizer_app'] != $component_authorizer_app_id){
            return $this->response_error(Error_Code::ERROR_VOUCHER_AUTHORIZER_APP_ID_NOT_MATCH);
        }

        if($voucherInfo['fk_user'] != $user_id){
            return $this->response_error(Error_Code::ERROR_VOUCHER_USER_NOT_MATCH);
        }

        if($amount < $voucherInfo['value_min']){
            return $this->response_error(Error_Code::ERROR_VOUCHER_VALUE_MIN_NOT_MATCH);
        }

        $result = $this->Voucher_model->consume($user_id, $order_id, $voucherInfo);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CONSUME_FAILED);
        }

        $data = array();
        $data['voucher_rule_id'] = $voucherInfo['fk_voucher_rule'];
        $data['voucher_id'] = $voucherInfo['pk_voucher'];
        $data['value'] = $voucherInfo['value'];
        $data['value_min'] = $voucherInfo['value_min'];

        return $this->response_object($data);
	}

    /**
     * 根据ids 获取多条代金券
     * @param ids 代金券自增IDS
     * @return json
     */
    public function get_by_ids_get(){
        $ids = $this->get_check('ids', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_STRING);
        $result = $this->Voucher_model->get_by_ids(trim($ids,','));
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_NOTEXIST);
        }
        return $this->response_list($result['data'], $result['sum']);
    }

    /**
     * 根据代金券规则id 作废代金券规则下的代金券
     */
    public function cancel_post(){

        $rule_id = $this->post_check('rule_id', PARAM_NOT_NULL_NOT_EMPTY);
        $corp_id = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $voucherruleInfo = $this->Rule_model->get_by_fk_voucher_rule($rule_id);
        if(empty($voucherruleInfo)) {
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($voucherruleInfo['state'] != OPERATION_VOUCHER_RULE_STATE_CANCEL){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }elseif($voucherruleInfo['fk_corp'] != $corp_id){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }
        $result = $this->Voucher_model->cancel($rule_id,$corp_id);
        return $this->response_update($result);
    }

    /**
     * 根据代金券规则id 过期状态为"已生成、未使用"的代金券
     */
    public function overdue_post(){
        $rule_id = $this->post_check('rule_id', PARAM_NOT_NULL_NOT_EMPTY);
        $corp_id = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY);
        $voucherruleInfo = $this->Rule_model->get_by_fk_voucher_rule($rule_id);
        if(empty($voucherruleInfo)) {
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($voucherruleInfo['state'] != OPERATION_VOUCHER_RULE_STATE_GENERATED){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }elseif($voucherruleInfo['fk_corp'] != $corp_id){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }
        $result = $this->Voucher_model->overdue($rule_id,$corp_id);
        return $this->response_update($result);
    }

    /**
     * 根据用户手机号 查询用户id 绑定代金券
     * @param mobile
     * @param voucher_id  voucher_rule_id
     */
    //TODO
    public function bind_manual(){
        $mobile = $this->get_check('mobile', PARAM_NOT_NULL_NOT_EMPTY);
        $voucher_id = $this->get_check('voucher_id', PARAM_NOT_NULL_NOT_EMPTY);

        //确认是否存在且未使用
        $voucherInfo = $this->Voucher_model->get_by_pk_voucher($voucher_id);
        if(empty($voucherInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_NOTEXIST);
        }

        if($voucherInfo['state'] != OPERATION_VOUCHER_STATE_NOTALLOT){
            return $this->response_error(Error_Code::ERROR_VOUCHER_CODE_USEED);
        }




    }






} // END class
