<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 活动数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Contest_model extends MY_Model
{
	/**
	 * init
	 *
	 */
	protected $tableNameContest = 't_contest';
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
     * @return array
     */
	public function get_by_out_contest_id($out_contest_id){
        $result = $this->setTable($this->tableNameContest)
            ->addQueryConditions('out_contest_id', $out_contest_id)
            ->doSelect();
        return empty($result)?array():$result[0];
	}

    /**
     * 新增活动信息
     * @param $param
     * @return array
     */
    public function create($param){

        $result = $this->setTable($this->tableNameContest)
            ->addInsertColumns($param)
            ->doInsert();

        return empty($result) ? false : $result;
    }

    /**
     * 修改活动信息
     * @param $param
     * @param $out_contest_id
     * @return updateId
     */
    public function modify($param, $out_contest_id){
        $condParam = array(
            array('out_contest_id', $out_contest_id, '=')
        );
        $updateParam = array(
            array('name', $param['name'], '='),
            array('logo', $param['logo'], '='),
            array('poster', $param['poster'], '='),
            array('banner', $param['banner'], '='),
            array('sdate_start',$param['sdate_start'], '='),
            array('sdate_end',$param['sdate_end'], '='),
            array('location', $param['location'], '='),
            array('locations', $param['locations'], '='),
            array('publish_state', $param['publish_state'], '='),
            array('service_tel', $param['service_tel'], '='),
            array('intro', $param['intro'], '=')
        );
        $result = $this->setTable($this->tableNameContest)
            ->addUpdateColumns($updateParam)
            ->addQueryConditions($condParam)
            ->doUpdate();
        return $result;
    }

    /**
     * 获取活动列表
     * @param $page
     * @param $size
     * @param $intro 是否获取详情  1获取  0不获取 默认不获取
     * @return array
     */
    public function lists($page, $size, $intro){

        if($intro == CONTEST_LIST_INTRO_YES){
            $queryFields = $this->query_detail_fields();
        }else{
            $queryFields = $this->query_list_fields();
        }
        $orderParam = array(
            'ctime' => 'ASC'
        );
        $result = $this->setTable($this->tableNameContest)
            ->addQueryFields($queryFields)
            //->addQueryConditions($param)
            ->addOrderBy($orderParam)
            ->doSelect($page, $size);
        foreach($result as &$val){
            if(!empty($val['locations'])){
                $val['locations'] = unserialize($val['locations']);
            }
        }

        return empty($result) ? array() : $result;
    }
    //活动列表（详情）所需字段
    private function query_detail_fields(){
        return array(
            'out_contest_id as contest_id',
            'name',
            'banner',
            'sdate_start',
            'sdate_end',
            'logo',
            'poster',
            'location',
            'locations',
            'publish_state',
            'service_tel',
            'intro',
            'ctime'
        );
    }
    //活动列表 （简洁）所需字段
    private function query_list_fields(){
        return array(
            'out_contest_id as contest_id',
            'name',
            'banner',
            'sdate_start',
            'sdate_end',
            'logo'
        );
    }




} // END class Msg_model
