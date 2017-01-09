<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 代金券数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Activity_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameActivity 				 = 't_activity';
	protected $tableNameActivityUserAction    	 = 't_activity_user_action';
	protected $tableNameMappingActivityOperation = 't_mapping_activity_operation';
	protected $tableNameActivityUserInvite       = 't_activity_user_invite';
	protected $tableNameVoucher            	   	 = 't_voucher';
	protected $tableNameMappingActivityUser      = 't_mapping_activity_user';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('voucher/Voucher_model');
		$this->load->model('voucher/Rule_model');
	}

	public function get_db()
	{
		return OPERATION_DB_CONFIG;
	}


	/**
	 * 新增活动规则
	 *
	 * @param $data array
	 *
	 * @return mixed
	 *
	 */
	public function create($data)
	{
		$params = array(
			'fk_corp' 	=> $data['fk_corp'],
			'name'		=> $data['name'],
			'time_start'=> $data['time_start'],
			'time_end'	=> $data['time_end'],
			'need_follow'=>$data['need_follow'],
			'number' 	=> $data['number'],
			'number_max' => $data['number_max'],
			'number_invite_one' => $data['number_invite_one'],
			'orderby' 	=> $data['orderby'],
			'banner' 	=> $data['banner'],
			'desc_rule' =>	$data['desc_rule'],
			//'desc' 		=>	$data['desc'],
			'state' 	=> OPERATION_ACTIVITY_STATE_STOP//默认状态 暂存
		);

		return $this->setTable($this->tableNameActivity)
		            ->addInsertColumns($params)
		            ->doInsert();
	}

	/**
	 * 修改活动信息
	 * @param $data array
	 * @return mixed
	 */
	public function saveActivity($data,$fk_activity){

		$params = array(
			array('fk_corp',	$data['fk_corp'],'='),
			array('name', 		$data['name'],'='),
			array('time_start', $data['time_start'],'='),
			array('time_end', 	$data['time_end'],'='),
			array('need_follow',$data['need_follow'],'='),
			array('number', 	$data['number'],'='),
			array('number_max', $data['number_max'],'='),
			array('orderby' ,		$data['orderby'],'='),
			array('banner' ,	$data['banner'],'='),
			array('number_invite_one', $data['number_invite_one'],'='),
			array('desc_rule',	$data['desc_rule'],'='),
			//array('desc', $data['desc'],'=')
		);

		return $this->setTable($this->tableNameActivity)
						->addUpdateColumns($params)
						->addQueryConditions('pk_activity', $fk_activity)
						->doUpdate();
	}

	/**
	 * 修改活动状态
	 */
	public function saveStateActivity($state,$fk_activity){

		$params = array(
			array('state',$state,'=')
		);

		return $this->setTable($this->tableNameActivity)
					->addUpdateColumns($params)
					->addQueryConditions('pk_activity', $fk_activity)
					->doUpdate();
	}


	/**
	 * 获取企业下活动列表
	 * @param $data array
	 *
	 * @return data
	 */
	public function lists($data,$page,$size,$order,$sort){
		$param = array(
			array('fk_corp',$data['fk_corp'],'=')
		);
		if(!empty($data['state'])){
			$param[] = array('state',$data['state'],'=');
		}

		$queryFields = array(
			'pk_activity as activity_id',
			'fk_corp as corp_id',
			'name',
			'time_start',
			'time_end',
			'need_follow',
			'number',
			'number_max',
			'number_invite_one',
			'state',
			'desc_rule',
			'desc',
			'banner',
			'orderby'
		);
		$orderParam = array(
			$order => $sort,
			'pk_activity' => 'desc'
		);
		$result = $this->setTable($this->tableNameActivity)
								->addQueryFields($queryFields)
								->addQueryConditions($param)
								->addOrderBy($orderParam)
								->doSelect($page, $size);
		$ruleName = $this->Rule_model->ruleList($data['fk_corp']);
		foreach($result as &$activity){
			//判断是否过期
			if($activity['state'] != OPERATION_ACTIVITY_STATE_DOWN && strtotime($activity['time_end']) < time()){
				$activity['state'] = OPERATION_ACTIVITY_STATE_END;
			}
			$activity['operation'] = array();
			$bingList = $this->bind_operation_list($activity['activity_id']);
			foreach($bingList as $ruleid){
				$ruleArr = array();
				if(!empty($ruleName[$ruleid['rule']])){
					$ruleArr = $ruleName[$ruleid['rule']];
					$ruleArr['type'] = $ruleid['type'];
					$activity['operation'][] = $ruleArr;
				}
			}

		}
        $total = $this->setTable($this->tableNameActivity)
                        ->addQueryFieldsCount('pk_activity')
                        ->addQueryConditions($param)
                        ->doSelect();
        $return['sum'] 	= $total[0]['pk_activity'];
		$return['data'] = $result;
		return $return;
	}

	/**
	 * 根据活动id 查询单条数据信息
	 * @param activity_id 活动id
	 * @param user_id 用户id
	 * @return mixed
	 */
	public function details($activity_id, $user_id = 0){
		$queryFields = array(
			'pk_activity as activity_id',
			'fk_corp as corp_id',
			'name',
			'time_start',
			'time_end',
			'need_follow',
			'number',
			'number_max',
			'number_invite_one',
			'state',
			'desc_rule',
			'desc',
			'banner',
			'orderby'
		);
		$result = $this->setTable($this->tableNameActivity)
						->addQueryFields($queryFields)
						->addQueryConditions('pk_activity', $activity_id)
						->doSelect();
		if($result){
			$activityInfo = $result[0];
			$ruleName = $this->Rule_model->ruleList($activityInfo['corp_id']);
			$bingList = $this->bind_operation_list($activity_id);
			$activityInfo['operation'] = array();
			foreach($bingList as $ruleid){
				$ruleArr = array();
				if(!empty($ruleName[$ruleid['rule']])){
					$ruleArr = $ruleName[$ruleid['rule']];
					$ruleArr['type'] = $ruleid['type'];
					$activityInfo['operation'][] = $ruleArr;
				}
			}

			//根据用户id获取用户的参与次数以及总可参与次数
			if($user_id > 0){
				$activityInfo['numberData'] = $this->user_action_number($activityInfo['activity_id'], $user_id, $activityInfo);
			}

			return $activityInfo;
		}
	}


	/**
	 * 根据活动id 获取绑定的代金券规则
	 */
	public function bind_operation_list($activity_id){
		$param = array(
			array('fk_activity', $activity_id, '='),
			array('state', OPERATION_ACTIVITY_STATE_BIND_OK, '=')
		);
		$queryFields = array(
			'rule','type'
		);
		$ruleList = $this->setTable($this->tableNameMappingActivityOperation)
					->addQueryFields($queryFields)
					->addQueryConditions($param)
					->doSelect();
		return $ruleList;
	}
	/**
	 * 根据活动id/企业id 获取该活动下未绑定的代金券规则
	 */
	public function nobind_operation_list($activity_id, $corp_id){
		$ruleName = $this->Rule_model->ruleList($corp_id);

		$bingList = $this->bind_operation_list($activity_id);
		foreach($bingList as $ruleid){
			unset($ruleName[$ruleid['rule']]);
		}
		if(count($ruleName)>0){
			return array_values($ruleName);
		}else{
			return array();
		}


	}



	/**
	 * 活动绑定营销规则
	 * @param  $data
	 * @return lastid
	 */
	public function bind_operation($data){
		try {
			$success = 0;
			$this->beginTransaction();
			$ids = explode(',',trim($data['rule'],','));
			foreach($ids as $rule){

				$params = array(
					'fk_activity'	=> $data['fk_activity'],
					'type' 			=> $data['type'],
					'rule' 			=> $rule,
					'state' 		=> OPERATION_ACTIVITY_STATE_BIND_OK
				);

				$resule =  $this->setTable($this->tableNameMappingActivityOperation)
								->addInsertColumns($params)
								->doInsert();
				if($resule){
					$success ++;
				}
			}
			$this->commit();
			return $success;

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 是否存在绑定关系
	 * @param $data
	 * @return mixd
	 */
	public function get_by_bingactivity($data){
		$param = array(
			array('state', OPERATION_ACTIVITY_STATE_BIND_OK,'='),
			array('fk_activity', $data['fk_activity'], '='),
			array('rule', $data['rule'], '='),
			array('type', $data['type'], '=')
		);
		return $this->setTable($this->tableNameMappingActivityOperation)
					->addQueryConditions($param)
					->doSelect();
	}

	/**
	 * 取消活动绑定规则
	 * @param $data
	 * @return bool
	 */
	public function unbind_operation($data){
		$params = array(
			array('state', OPERATION_ACTIVITY_STATE_BIND_NO, '=')
		);
		$queryArr = array(
			array('fk_activity', $data['fk_activity'], '='),
			array('type', $data['type'], '='),
			array('rule', $data['rule'], '=')
		);

		return $this->setTable($this->tableNameMappingActivityOperation)
					->addUpdateColumns($params)
					->addQueryConditions($queryArr)
					->doUpdate();
	}


	/**
	 * 用户参与活动信息记录
	 * @param 活动id 用户id 工具类型 规则id
	 * @param code 代金券信息array
	 * @return newid
	 */
	public function user_action($component_authorizer_app_id,$activity_id,$user_id,$type,$rule,$code){

		$actionParam = array(
			'fk_activity' 	=> $activity_id,
			'fk_user' 		=> $user_id,
			'type' 			=> $type,
			'rule' 			=> $rule,
			'rule_id' 		=> $code['pk_voucher']
		);
		try {
			$this->beginTransaction();

			$result = $this->setTable($this->tableNameActivityUserAction)
							->addInsertColumns($actionParam)
							->doInsert();
			//更新代金券信息
			$params = array(
				array('fk_user', $user_id, '='),
				array('fk_component_authorizer_app', $component_authorizer_app_id, '='),
				array('state', OPERATION_VOUCHER_STATE_NOTUSE, '=')//状态从未分配改为已分配
			);
			$this->setTable($this->tableNameVoucher)
						->addUpdateColumns($params)
						->addQueryConditions('pk_voucher', $code['pk_voucher'])
						->doUpdate();
			$this->Voucher_model->logVoustatechange($code['pk_voucher'], OPERATION_VOUCHER_STATE_NOTALLOT, OPERATION_VOUCHER_STATE_NOTUSE);

			//记录用户参与活动次数
			$this->logMappingActvityUser($activity_id ,$user_id,'number_action');

			//记录代金券抢夺记录-代金券规则表 被抢夺数量+1
			$this->Rule_model->logRulealltonumber($code['fk_voucher_rule']);

			$this->commit();
			return $result;

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据活动信息 用户id 存储用户参与与邀请次数
	 * @param fk_activity fk_user
	 * @param type 判断更新哪个字段
	 */
	public function logMappingActvityUser($fk_activity, $fk_user, $type, $number = 1){
		//先确认信息是否已存在
		$param = array(
			array('fk_activity', $fk_activity,'='),
			array('fk_user', $fk_user, '=')
		);
		$mappingInfo = $this->setTable($this->tableNameMappingActivityUser)
							->addQueryConditions($param)
							->doSelect();
		if(empty($mappingInfo)){
			$insertParam = array(
				'fk_activity' 		=> $fk_activity,
				'fk_user' 			=> $fk_user,
				$type 				=> $number
			);
			$this->setTable($this->tableNameMappingActivityUser)
				 ->addInsertColumns($insertParam)
				 ->doInsert();
		}else{
			$updateParam = array(
				array($type,$number,'+')
			);
			$this->setTable($this->tableNameMappingActivityUser)
				->addUpdateColumns($updateParam)
				->addQueryConditions('pk_mapping_activity_user',$mappingInfo[0]['pk_mapping_activity_user'])
				->doUpdate();
		}
	}

	/**
	 * 根据活动id 以及用户id获取用户参加的列表
	 * @param activity_id
	 * @param user_id
	 * @return lists
	 */
	public function list_user_action($activity_id,$user_id,$page,$size,$order,$sort){
		$param = array(
			array('fk_activity',$activity_id,'=')
		);
		if($user_id){
			$param[] = array('fk_user',$user_id,'=');
		}

		$queryFields = array(
			'pk_activity_user_action as activity_user_action_id',
			'fk_activity as activity_id',
			'fk_user as user_id',
			'type',
			'rule',
			'rule_id',
			'ctime'
		);

		$return['data'] = $this->setTable($this->tableNameActivityUserAction)
			                    ->addQueryFields($queryFields)
			                    ->addQueryConditions($param)
			                    ->addOrderBy($order, $sort)
			                    ->doSelect($page, $size);
        $total = $this->setTable($this->tableNameActivityUserAction)
                        ->addQueryFieldsCount('pk_activity_user_action')
                        ->addQueryConditions($param)
                        ->doSelect();
        $return['sum'] = $total[0]['pk_activity_user_action'];
		return $return;
	}



	/**
	 * 根据主键查询单条数据
	 * @param  pk_activity
	 *
	 * @return data
	 */
	public function get_by_pk_activity($pk_activity){
		$result = $this->setTable($this->tableNameActivity)
						->addQueryConditions('pk_activity', $pk_activity)
						->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * 根据规则id及活动id 判断是否存在关联关系
	 * @param pk_activity 活动id
	 * @param rule_id 规则id
	 * @return data
	 */
	public function get_by_activity_operation($pk_activity,$rule_id,$type){
		$param = array(
			array('fk_activity',$pk_activity,'='),
			array('type',$type,'='),
			array('rule',$rule_id,'='),
			array('state',OPERATION_ACTIVITY_STATE_BIND_OK,'=')
		);
		$result = $this->setTable($this->tableNameMappingActivityOperation)
						->addQueryConditions($param)
						->doSelect();
		if($result){
			return $result[0];
		}
	}

	/**
	 * @param $activity_id
	 * @param $user_id
	 * @param $invited_fk_user
	 * @return date
	 */
	public function get_by_invite($activity_id,$user_id,$invited_fk_user){
		$param = array(
			array('fk_activity',$activity_id,'='),
			array('fk_user',$user_id,'='),
			array('invited_fk_user',$invited_fk_user,'=')
		);
		return $this->setTable($this->tableNameActivityUserInvite)
					->addQueryConditions($param)
					->doSelect();

	}

	/**
	 * 添加参与活动邀请人信息
	 * @param
	 * @return lastid
	 */
	public function user_invite($component_authorizer_app_id,$activity_id,$user_id,$invited_fk_user){
		$param = array(
			'fk_activity'	=> $activity_id,
			'fk_user'    	=> $user_id,
			'invited_fk_user'=>$invited_fk_user
		);
		try {
			$this->beginTransaction();
			$result = $this->setTable($this->tableNameActivityUserInvite)
						->addInsertColumns($param)
						->doInsert();
			$this->logMappingActvityUser($activity_id ,$user_id,'number_user_invite');
			$this->commit();
			return $result;

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	/**
	 * 根据用户id，查询用户可参与次数，已参与次数
	 * @param activity_id 活动id
	 * @param user_id 用户id
	 * @param $activityInfo 活动信息
	 *** number_invite_one 邀请一人可参与次数
	 *** number_max 最多可参与次数
	 * @return mixd
	 */
	public function user_action_number($activity_id, $user_id, $activityInfo){
		$return = array(
			'numberAll' 	=> 0,  //可参与总次数
			'numberAction' 	=> 0   //已参与次数
		);
		$return['numberAction'] = $this->partake($activity_id, $user_id);

		//获取用户邀请人折算可参与次数
		$intviteNumber = $this->intviteTake($activity_id, $user_id, $activityInfo['number_invite_one']);
		if(!empty($intviteNumber)){
			if(($intviteNumber + $activityInfo['number']) > $activityInfo['number_max']){
				$return['numberAll'] = $activityInfo['number_max'];
			}else{
				$return['numberAll'] = $intviteNumber + $activityInfo['number'];
			}
		}else{
			$return['numberAll'] = $activityInfo['number'];
		}
		return $return;
	}


	/**
	 * 获取用户的参与次数
	 * @param activity_id user_id
	 * @return int
	 */
	public function partake($activity_id, $user_id){
        $param = array(
            array('fk_activity',$activity_id,'='),
            array('fk_user',$user_id,'=')
        );
        $result = $this->setTable($this->tableNameActivityUserAction)
                        ->addQueryFieldsCount('pk_activity_user_action')
                        ->addQueryConditions($param)
                        ->doSelect();
        $total = $result[0]['pk_activity_user_action'];
        return $total;
	}
    /**
     * 根据用户邀请信息 获取用户的新次数
     * @param activity_id user_id
     * @param $number_invite_one 邀请一个人可参与次数
     * @return int
     */
    public function intviteTake($activity_id, $user_id,$number_invite_one){
        $param = array(
            array('fk_activity',$activity_id,'='),
            array('fk_user',$user_id,'=')
        );
        $result = $this->setTable($this->tableNameActivityUserInvite)
                        ->addQueryFieldsCount('pk_activity_user_invite')
                        ->addQueryConditions($param)
                        ->doSelect();
        $total = $result[0]['pk_activity_user_invite'];
        if($total>0){
            return $total * $number_invite_one;
        }
    }





} // END class Msg_model
