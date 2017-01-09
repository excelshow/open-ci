<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 项目数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class ContestItem_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameContest = 't_contest';
	protected $tableNameContestItem = 't_contest_item';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_LITTLE_DB_CONFIG;
	}

    /**
     * 根据out_contest_id 获取数据
     * @param $out_contest_id
     * @param $out_contest_items_id
     * @return array
     */
	public function get_by_out_contest_item($out_contest_id,$out_contest_items_id){
        $param = array(
            array('out_contest_id', $out_contest_id, '='),
            array('out_contest_item_id', $out_contest_items_id, '=')
        );
        $result = $this->setTable($this->tableNameContestItem)
            ->addQueryConditions($param)
            ->doSelect();
        return empty($result)?array():$result[0];
	}
    public function get_by_out_item($out_contest_items_id){
        $param = array(
            array('out_contest_item_id', $out_contest_items_id, '=')
        );
        $result = $this->setTable($this->tableNameContestItem)
            ->addQueryConditions($param)
            ->doSelect();
        return empty($result)?array():$result[0];
    }
    /**
     * 新增项目信息
     * @param $param
     * @return array
     */
    public function create($param){

        $result = $this->setTable($this->tableNameContestItem)
            ->addInsertColumns($param)
            ->doInsert();

        return empty($result) ? false : $result;
    }

    /**
     * 修改项目信息
     * @param $param
     * @param $out_contest_id
     * @param $$out_contest_item_id
     * @return updateId
     */
    public function modify($param, $out_contest_id, $out_contest_item_id){
        $condParam = array(
            array('out_contest_id', $out_contest_id, '='),
            array('out_contest_item_id', $out_contest_item_id, '=')
        );
        $updateParam = array(
            array('name', $param['name'], '='),
            array('max_stock', $param['max_stock'], '='),
            array('cur_stock', $param['cur_stock'], '='),
            array('max_verify', $param['max_verify'], '='),
            array('price',$param['price'], '='),
            array('end_time',$param['end_time'], '='),
            array('state', $param['state'], '='),
            array('sell_number', $param['sell_number'], '='),
            array('consume_start_time', $param['consume_start_time'], '='),
            array('consume_end_time', $param['consume_end_time'], '='),
            array('refund_flag', $param['refund_flag'], '=')
        );
        $result = $this->setTable($this->tableNameContestItem)
            ->addUpdateColumns($updateParam)
            ->addQueryConditions($condParam)
            ->doUpdate();
        return $result;
    }

    /**
     * 获取活动的项目列表
     * @param $page
     * @param $size
     * @param $out_contest_id
     * @return array
     */
    public function lists($page, $size, $out_contest_id = null){
        $queryFields = array(
            'out_contest_id as contest_id',
            'out_contest_item_id as contest_item_id',
            'name',
            'max_stock',
            'cur_stock',
            'max_verify',
            'price',
            'end_time',
            'state',
            'sell_number',
            'consume_start_time',
            'consume_end_time',
            'refund_flag'
        );

        $orderParam = array(
            'ctime' => 'ASC'
        );
        if(empty($out_contest_id)){
            $result = $this->setTable($this->tableNameContestItem)
                ->addQueryFields($queryFields)
                ->addOrderBy($orderParam)
                ->doSelect($page, $size);
        }else{
            $queryParam = array(
                array('out_contest_id', $out_contest_id, '=')
            );
            $result = $this->setTable($this->tableNameContestItem)
                ->addQueryFields($queryFields)
                ->addQueryConditions($queryParam)
                ->addOrderBy($orderParam)
                ->doSelect($page, $size);
        }

        return empty($result) ? array() : $result;
    }



} // END class Msg_model
