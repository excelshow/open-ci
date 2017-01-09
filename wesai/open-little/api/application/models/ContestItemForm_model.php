<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 表单数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class ContestItemForm_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameContestItem     = 't_contest_item';
	protected $tableNameContestItemForm = 't_contest_item_form';
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return OPEN_LITTLE_DB_CONFIG;
	}

    /**
     * 根据$out_contest_items_id 获取表单数据
     * @param $out_form_item_id
     * @param $out_contest_items_id
     * @return array
     */
	public function get_by_out_contest_item($out_form_item_id,$out_contest_items_id){
        $param = array(
            array('out_form_item_id', $out_form_item_id, '='),
            array('out_contest_item_id', $out_contest_items_id, '=')
        );
        $result = $this->setTable($this->tableNameContestItemForm)
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

        $result = $this->setTable($this->tableNameContestItemForm)
            ->addInsertColumns($param)
            ->doInsert();

        return empty($result) ? false : $result;
    }

    public function modify($param, $item_id){
        try {
            $create = 0;
            $this->beginTransaction();

            //删除现有表单 修改状态
            $this->saveState($item_id);
            //新增新的表单信息
            foreach($param as $from){
                $ontParam = array(
                    'out_form_item_id'    => $from->form_item_id,
                    'type'                => $from->type,
                    'out_contest_item_id' => $item_id,
                    'title'               => $from->title,
                    'option_values'       => $from->option_values,
                    'is_required'         => $from->is_required,
                    'seq'                 => $from->seq,
                    'state'               => CONTEST_LIST_FORM_STATE_OK,
                );
                $result = $this->create($ontParam);
                if(!empty($result)){
                    $create++;
                }else{
                    log_message('error','create from failed'.' pm='.json_encode($ontParam));
                }
            }

            $this->commit();
            return $create;
        } catch (Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }
    private function saveState($item_id){
        $condParam = array(
            array('out_contest_item_id', $item_id, '='),
            array('state', CONTEST_LIST_FORM_STATE_OK, '=')
        );
        $updateParam = array(
            array('state', CONTEST_LIST_FORM_STATE_DEL, '=')
        );
        $result = $this->setTable($this->tableNameContestItemForm)
            ->addUpdateColumns($updateParam)
            ->addQueryConditions($condParam)
            ->doUpdate();
        return $result;
    }

    /**
     * 获取表单列表
     * @param $out_contest_id
     * @return array
     */
    public function lists($out_contest_item_id){
        $queryFields = array(
            'out_form_item_id as form_item_id',
            'out_contest_item_id as contest_item_id',
            'type',
            'title',
            'option_values',
            'is_required',
            'seq'
        );
        $orderParam = array(
            'ctime' => 'ASC'
        );
        $queryParam = array(
            array('out_contest_item_id', $out_contest_item_id, '='),
            array('state', CONTEST_LIST_FORM_STATE_OK, '=')
        );
        $result = $this->setTable($this->tableNameContestItemForm)
            ->addQueryFields($queryFields)
            ->addQueryConditions($queryParam)
            ->addOrderBy($orderParam)
            ->doSelect();

        return empty($result) ? array() : $result;
    }



} // END class Msg_model
