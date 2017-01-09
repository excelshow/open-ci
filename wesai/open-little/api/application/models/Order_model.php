<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 订单数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Order_model extends MY_Model
{
	/**
	 * init
	 */
	protected $tableNameOrder           = 't_order';
    protected $tableNameOrderComplete   = 't_order_complete';

    public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_LITTLE_DB_CONFIG;
	}

    /**
     * 根据out_trade_no 获取数据
     * @param out_trade_no
     * @return array
     */
	public function get_by_out_trade_no($out_trade_no){
        $result = $this->setTable($this->tableNameOrder)
            ->addQueryConditions('out_trade_no', $out_trade_no)
            ->doSelect();
        return empty($result)?array():$result[0];
	}

    /**
     * 核销码code 查询核销信息
     */
    public function get_by_verify_code($verify_code){
        $result = $this->setTable($this->tableNameOrderComplete)
            ->addQueryConditions('code', $verify_code)
            ->doSelect();
        return empty($result)?array():$result[0];
    }

    /**
     * 新增订单信息
     * @param $param
     * @return array
     */
    public function create($param){

        $result = $this->setTable($this->tableNameOrder)
            ->addInsertColumns($param)
            ->doInsert();

        return empty($result) ? false : $result;
    }
    /**
     * 新增核销信息
     * @param $param
     * @return array
     */
    public function create_complete($param){

        $result = $this->setTable($this->tableNameOrderComplete)
            ->addInsertColumns($param)
            ->doInsert();

        return empty($result) ? false : $result;
    }

    /**
     * 修改订单状态,新增核销信息
     * @param $complete_info 核销信息(object)
     * @param $out_trade_no 订单号
     * @param $order_id 订单信息主键
     * @return int
     * @throws Exception
     */
    public function order_complete($complete_info, $out_trade_no, $order_id){
        try {
            $create = 0;
            $this->beginTransaction();
            //修改订单状态
            $this->modify_order_state($out_trade_no, CONTEST_ORDER_STATE_PAID, CONTEST_ORDER_STATE_OK);
            //新增核销信息
            foreach ($complete_info as $complete) {
                $param = array(
                    'fk_order'      => $order_id,
                    'max_verify'    => $complete->max_verify,
                    'verify_number' => $complete->verify_number,
                    'code'          => $complete->code,
                    'qrcode_data'   => $complete->qrcode_data,
                    'state'         => CONTEST_ORDER_COMPLETE_STATE_OK
                );
                $result = $this->create_complete($param);
                if(!empty($result)){
                    $create++;
                }
            }
            $this->commit();
            return $create;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    /**
     * 修改订单状态
     * @param $out_trade_no 订单号
     * @param $toState
     * @param $fromState
     * @return bool|int
     */
    public function modify_order_state($out_trade_no, $toState, $fromState){
        $condParam = array(
            array('out_trade_no', $out_trade_no, '='),
            array('state', $fromState, '=')
        );
        $result = $this->setTable($this->tableNameOrder)
            ->addUpdateColumns('state', $toState)
            ->addQueryConditions($condParam)
            ->doUpdate();
        return $result;
    }

    /**
     * 修改核销信息/修改订单状态
     * @param $param
     * @param $code
     * @param $orderInfo
     * @return mixed
     * @throws Exception
     */
    public function modify_complete($param, $code, $orderInfo){
        try {
            $this->beginTransaction();
            $upParam = array(
                array('verify_number',$param['verify_number'], '='),
                array('state',$param['state'], '=')
            );
            $result = $this->setTable($this->tableNameOrderComplete)
                ->addUpdateColumns($upParam)
                ->addQueryConditions('code', $code)
                ->doUpdate();

            $this->check_order_complete($orderInfo);
            $this->commit();
            return $result;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }
    private function check_order_complete($orderInfo){
        $param = array(
            array('fk_order', $orderInfo['pk_order'], '='),
            array('state', CONTEST_ORDER_COMPLETE_STATE_OK, '=')
        );
        $result = $this->setTable($this->tableNameOrderComplete)
            ->addQueryConditions($param)
            ->doSelect();
        if(empty($result)){
            $this->modify_order_state($orderInfo['out_trade_no'], CONTEST_ORDER_STATE_VERIFY_END, $orderInfo['state']);
        }else{
            if($orderInfo['state'] != CONTEST_ORDER_STATE_VERIFY_ING){
                $this->modify_order_state($orderInfo['out_trade_no'], CONTEST_ORDER_STATE_VERIFY_ING, $orderInfo['state']);
            }
        }
    }


} // END class Msg_model
