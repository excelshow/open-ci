<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 金飞鹰订单数据处理类
 *
 * @author: liangkaixuan@wesai.com
 **/
class Orders_model extends MY_Model
{
	protected $tableNameOrderPartnerSoftykt = 't_order_partner_softykt';

	public function __construct()
	{
		parent::__construct();
		$this->load->model('softykt/Product_model');
		$this->load->model('Order_model');
	}

	public function get_db()
	{
		return CONTEST_DB_CONFIG;
	}

	/**
	 * @param ordernumber 金飞鹰订单号
	 * @param trade_no 智慧体育订单号
	 */
	public function create($ordernumber, $out_refund_no){

		$param = array(
			'ordernumber' 	=> $ordernumber,
			'trade_no' 		=> $out_refund_no,
			'state'			=> CONTEST_ORDER_PARTNER_SOFTYKT_STATE_OK
		);
		$result = $this->setTable($this->tableNameOrderPartnerSoftykt)
			->addInsertColumns($param)
			->doInsert();

		return $result;
	}

	/**
	 * 修改对应关系且存储返回值
	 * @param $ordernumber 金飞鹰订单号
	 * @param $state 状态
	 * @param $orderdeatild 金飞鹰订单详情id
	 * @param $number  对应数量
	 * @return id
	 */
	public function modify($ordernumber, $orderdeatild, $number, $state){
		$whereParam = array(
			array('ordernumber', $ordernumber,'=')
		);
		$updateparam = array(
			array('state', $state, '='),
			array('orderdeatild', $orderdeatild, '='),
			array('number', $number, '='),
		);

		$result = $this->setTable($this->tableNameOrderPartnerSoftykt)
			->addUpdateColumns($updateparam)
			->addQueryConditions($whereParam)
			->doUpdate();
		return $result;
	}

	/**
	 * 根据金飞鹰订单号查询订单信息
	 * @param ordernumber  金飞鹰订单号
	 * @return mixed
	 */
	public function get_by_ordernumber($ordernumber){

		$param = array(
			array('ordernumber', $ordernumber, '=')
		);
		$result = $this->setTable($this->tableNameOrderPartnerSoftykt)
			->addQueryConditions($param)
			->doSelect();
		if($result){
			return $result[0];
		}
	}


	/**
	 * 获取金飞鹰未同步订单列表
	 * @param $page
	 * @param $size
	 */
	public function orderlists($page = 1, $size = 100){

		$query = "SELECT fk_order,out_trade_no
				  FROM t_order_partner_softykt
				  WHERE parnter_order_number IS NULL ";

		$pagenum = ($page-1) * $size;
		$limit = " limit ". $pagenum . ", ".$size;

		$orderlist =  $this->getAll(Pdo_Mysql::DSN_TYPE_SLAVE, $query.$limit, compact('pk_contest'));

		if($orderlist){
			//补充关联的景区、产品id
			$idarr = '';
			$fk_contest_ids = '';
			$fk_order_ids = array();
			$order_detail_key_list = array();

			foreach($orderlist as $v){
				$fk_order_ids[] = $v['fk_order'];
			}
			//查询订单信息
			$order_detail_list = $this->Order_model->getByIds($fk_order_ids);
			foreach($order_detail_list as $order){
				$idarr[$order['fk_contest']] = $order['fk_contest'];
				$order_detail_key_list[$order['pk_order']] = $order;
			}
			foreach($idarr as $v){
				$fk_contest_ids .= $v.',';
			}

			$contestParnter = $this->Product_model->get_by_partner_fk_contest_ids(trim($fk_contest_ids, ','));
			foreach($contestParnter as $k=>$v){
				$contestParnterinfo[$v['fk_contest']] = $v;
			}
			foreach($orderlist as &$order){
				$orderdetail = array();
				$orderdetail = $order_detail_key_list[$order['fk_order']];
				$order['fk_user']	= $orderdetail['fk_user'];
				$order['copies']	= $orderdetail['copies'];
				$contestSoftykt = array();
				$contestSoftykt = $contestParnterinfo[$orderdetail['fk_contest']] = $v;
				$order['scenicid'] 	= $contestSoftykt['scenicid'];
				$order['productid'] = $contestSoftykt['productid'];
			}

		}
		return $orderlist;

	}

	//根据智慧体育订单号查询金飞鹰订单号
	public function get_by_trade_no($order){
		$param = array(
			array('out_trade_no', $order, '=')
		);
		$result = $this->setTable($this->tableNameOrderPartnerSoftykt)
			->addQueryConditions($param)
			->doSelect();
		if($result){
			return $result[0];
		}

	}


} // END class Msg_model
