<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once __DIR__ . '/../Base.php';
/**
 * 代金券接口类
 *
 * @package default
 * @author  : liangkaixuan@wesai.com
 **/
class Rule extends Base
{
	/**
	 * construct
	 **/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('voucher/Rule_model');
        $this->load->model('voucher/Voucher_model');
	}

	/**
	 * 代金券规则添加
     * @param corp_id name ...
     * @return json
	 */
	public function add_post()
    {
        $fk_corp    = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);        //企业id
        $name       = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);                           //代金券规则名称
        $scope_type = $this->post_check('scope_type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);     //权限类型（1:通券 2:定向券）
        $number     = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);      //代金券数量
        $value      = $this->post_check('value', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);       //面值金额
        $value_min  = $this->post_check('value_min', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);   //最低使用金额
        $date_stop  = $this->post_check('stop_time', PARAM_NOT_NULL_NOT_EMPTY);                      //截止时间

        if($value  < OPERATION_VOUCHER_RULE_VALUE_MIN ){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_VALUE_ERROR);
        }
        if($value_min  < OPERATION_VOUCHER_RULE_VALUE_MIN ){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_VALUE_MIX_ERROR);
        }elseif($value_min <= $value){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_VALUE_MIX_BIG);
        }
        $param = array(
            'fk_corp'       => $fk_corp,
            'name'          => $name,
            'scope_type'    => $scope_type,
            'number'        => $number,
            'value'         => $value,
            'value_min'     => $value_min,
            'date_stop'     => $date_stop,
        );

        //存储数据
        $result = $this->Rule_model->create($param);
        if (empty($result)) {
            return $this->response_error(Error_Code::ERROR_ADD_VOUCHER_RULE_FAILED);
        }
        return $this->response_insert($result);
	}
    
    /**
     * 代金券规则列表
     * @param corp_id  scope_type state 分页等
     * @return json
     */
    public function list_get(){

        $scope_type = $this->get_check('scope_type', PARAM_NULL_EMPTY, PARAM_TYPE_INT);     //权限类型（1:通券 2:定向券）
        $fk_corp    = $this->get_check('corp_id', PARAM_NULL_EMPTY, PARAM_TYPE_INT);        //企业id
        $state      = $this->get_check('state', PARAM_NULL_EMPTY);                          //状态
        $page       = $this->get_check('page', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //页数
        $size       = $this->get_check('size', PARAM_NULL_EMPTY, PARAM_TYPE_NUMBER);        //每页条数
        $param = array(
            'scope_type' => $scope_type,
            'fk_corp'    => $fk_corp,
            'state'      => $state
        );
        if(empty($size)) {$size = OPERATION_VOUCHER_RULE_LIST_SIZE;}
        if(empty($page)) {$page = OPERATION_LIST_PAGE;}
        if(empty($order)){$order = 'ctime';}
        if(empty($sort)) {$sort = 'desc';}

        $result = $this->Rule_model->lists($param,$page,$size,$order,$sort);
        return $this->response_list($result['data'], $result['sum'], $page, $size);

    }

    /**
     * 根据代金券规则id  获取代金券规则详情
     * @param rule_id
     * @return json
     */
    public function details_get(){
        $rule_id    = $this->get_check('rule_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //规则主键
        $result     = $this->Rule_model->details($rule_id);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }
        return $this->response_object($result);
    }

    /**
     * 根据代金券id 企业id 修改代金券信息 且必须状态为 未通过、未审核
     * @param rule_id corp_id
     * @return json
     */
    public function modify_post(){
        $rule_id    = $this->post_check('rule_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //规则主键
        $fk_corp    = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);    //企业id
        $name       = $this->post_check('name', PARAM_NOT_NULL_NOT_EMPTY);                           //代金券规则名称
        $scope_type = $this->post_check('scope_type', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);     //权限类型（1:通券 2:定向券）
        $number     = $this->post_check('number', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);      //代金券数量
        $value      = $this->post_check('value', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);       //面值金额
        $value_min  = $this->post_check('value_min', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_NUMBER);   //最低使用金额
        $date_stop  = $this->post_check('stop_time', PARAM_NOT_NULL_NOT_EMPTY);                      //截止时间

        $result     = $this->Rule_model->get_by_fk_voucher_rule($rule_id);
        if(empty($result)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($result['fk_corp'] != $fk_corp){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }elseif($result['state'] != OPERATION_VOUCHER_RULE_STATE_NOSUBJECT && $result['state'] != OPERATION_VOUCHER_RULE_STATE_NOTPASSED){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }
        $param = compact('fk_corp','rule_id','name','scope_type','number','value','value_min','date_stop');
        $last = $this->Rule_model->modify($param);
        return $this->response_update($last);

    }


	/**
	 * 修改企业下规则状态 审核通过 to 生成中
	 *
	 * @param  corp_id 企业主键id voucher_rule_id代金券id
	 * @return json/bool
	 */
	public function change_state_to_generating_post()
	{
        $param = $this->checkParam();
        $this->checkRule($param);

        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_GENERATING, OPERATION_VOUCHER_RULE_STATE_PASSED);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
	}
    /**
     * 修改企业下规则状态 生成中 to 已生成
     *
     * @param  fk_corp 企业主键id voucher_rule_id代金券id
     * @return json/bool
     */
    public function change_state_to_generated_post()
    {
        $param = $this->checkParam();
        $this->checkRule($param);

        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_GENERATED, OPERATION_VOUCHER_RULE_STATE_GENERATING);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 修改企业下规则状态 待审批 to 不通过
     *
     * @param  fk_corp 企业主键id voucher_rule_id代金券id
     * @return json
     */
    public function change_state_to_notpassed_post()
    {
        $param = $this->checkParam();
        $this->checkRule($param);
        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_NOTPASSED, OPERATION_VOUCHER_RULE_STATE_WAIT);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 修改企业下规则状态 修改为通过
     *
     * @param  fk_corp 企业主键id voucher_rule_id 代金券id
     * @return json
     */
    public function change_state_to_passed_post()
    {
        $param = $this->checkParam();

        //查询代金券规则是否过期
        $ruleInfo = $this->Rule_model->get_by_fk_voucher_rule($param['fk_voucher_rule']);
        if(empty($ruleInfo)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($ruleInfo['fk_corp'] != $param['fk_corp']){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }elseif(strtotime($ruleInfo['date_stop']) < time() ){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_OVERDUE);
        }

        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_PASSED, OPERATION_VOUCHER_RULE_STATE_WAIT);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 修改企业下规则状态 修改为待审批
     *
     * @param  fk_corp 企业主键id voucher_rule_id代金券id
     * @return json
     */
    public function change_state_fromnosubject_to_wait_post()
    {
        $param = $this->checkParam();
        $this->checkRule($param);

        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_WAIT,OPERATION_VOUCHER_RULE_STATE_NOSUBJECT);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 修改企业下规则状态 不通过修改为待审批
     *
     * @param  fk_corp 企业主键id voucher_rule_id代金券id
     * @return json
     */
    public function change_state_fromnotpass_to_wait_post()
    {
        $param = $this->checkParam();
        $this->checkRule($param);

        $result = $this->Rule_model->saveState($param,OPERATION_VOUCHER_RULE_STATE_WAIT,OPERATION_VOUCHER_RULE_STATE_NOTPASSED);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * 修改企业下规则状态 未使用的代金券修改为作废
     *
     * @param  fk_corp 企业主键id voucher_rule_id代金券id
     * @return json
     */
    public function change_state_to_cancel_post()
    {
        $param = $this->checkParam();

        //查询规则是否为 已生成
        $rule = $this->Rule_model->get_by_fk_voucher_rule($param['fk_voucher_rule']);
        if(empty($rule)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($rule['fk_corp'] != $param['fk_corp']){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }elseif($rule['state'] != OPERATION_VOUCHER_RULE_STATE_GENERATED){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_STATE_ERROR);
        }

        $result = $this->Rule_model->saveState($param, OPERATION_VOUCHER_RULE_STATE_CANCEL, OPERATION_VOUCHER_RULE_STATE_GENERATED);
        if($result < 1){
            return $this->response_error(Error_Code::ERROR_SAVE_VOUCHER_RULE_STATE_FAILED);
        }
        return $this->response_update($result);
    }

    /**
     * @param post过来的请求数据
     * @return mixed
     */
    private function checkParam(){

        $fk_corp         = $this->post_check('corp_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);         //企业id
        $fk_voucher_rule = $this->post_check('voucher_rule_id', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT); //代金券规则id

        return array(
            'fk_corp'         => $fk_corp,
            'fk_voucher_rule' => $fk_voucher_rule
        );
    }

    /**
     * 根据参数验证代金券规则
     * @param $param
     */
    private function checkRule($param){
        $rule = $this->Rule_model->get_by_fk_voucher_rule($param['fk_voucher_rule']);
        if(empty($rule)){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_NOTEXIST);
        }elseif($rule['fk_corp'] != $param['fk_corp']){
            return $this->response_error(Error_Code::ERROR_VOUCHER_RULE_CORP_NOTBELONG);
        }
    }



} // END class
