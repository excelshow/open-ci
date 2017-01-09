<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Order extends ContestBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getAllowedApiList()
	{
		return array(
			API_HOST_OPEN_CONTEST => array(
				'order/ajax_team' => 'team/get.json', //上架
			),
		);
	}

	public function list_order()
	{
		$this->verifyLogin();//用户验证
		$keyword = $this->input->get('keyword', true);
		$state        = $this->input->get('state', true);
		$page         = $this->input->get('per_page', true);
		$dateType     = $this->input->get('date_type', true);
		$datePeriod   = $this->input->get('date_period', true);
		$dateStart   = $this->input->get('dateStart', true);
		$dateEnd     = $this->input->get('dateEnd', true);
		$size         = 20;
		switch ($dateType) {
			case 1: // 选择固定期限
				switch ($datePeriod) {
					case 1: //当日
						$date_start = date('Y-m-d 00:00:00');
						$date_end   = date('Y-m-d 23:59:59');
						break;
					case 2: //近一周
						$date_start = date('Y-m-d 00:00:00', strtotime('-1 weeks'));
						$date_end   = date('Y-m-d 23:59:59');
						break;
					case 3:
						$date_start = date('Y-m-d 00:00:00', strtotime('-1 month'));
						$date_end   = date('Y-m-d 23:59:59');
						break;
				}
				break;
			case 2:
				// 自定义日期区间
				$date_start = date('Y-m-d 00:00:00', strtotime($dateStart));
				$date_end   = date('Y-m-d 23:59:59', strtotime($dateEnd));
				break;
		}

		$seller_corp_id = $this->userInfo->pk_corp;

		$page > 0 or $page = 1;

		$transaction_id = null;
		$oid = null;
		if (strlen($keyword) > 20) {
			$transaction_id = strval($keyword);
		} else {
			$oid = intval($keyword);
		}

		$contest_info = 1;

		$params = compact('seller_corp_id', 'oid', 'transaction_id', 'state', 'page', 'size', 'date_start', 'date_end', 'contest_info');
		// 获取活动列表

		$orderList = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/list.json', $params, METHOD_GET);
		$total = $orderList->total;
		$orderList = $orderList->data;

		$this->load->helper('pagination');
		$this->load->library('pagination');
		$pconfig = load_pagination_config($total, $size);
		$this->pagination->initialize($pconfig);
		$page_ctrl = $this->pagination->create_links();

		global $ORDER_PAY_SATATELIST,
		       $CONTEST_DELIVER_GEAR_OPTIONS,
		       $ORDER_LOTTERY_STATELIST,
		       $ORDER_CHANNEL_LIST;

		$data = compact(
			'keyword', 'state', 'page', 'size', 'dateStart', 'dateEnd', 'total', 'dateType', 'datePeriod',
			'data', 'page_ctrl', 'ORDER_PAY_SATATELIST', 'orderList',
			'CONTEST_DELIVER_GEAR_OPTIONS', 'ORDER_LOTTERY_STATELIST', 'ORDER_CHANNEL_LIST'
		);
		$this->display($data);
	}

	public function detail_order()
	{
		$this->verifyLogin();//用户验证
		//活动名称
		$params['oid'] = $this->input->get('oid', true);
		$contest_name  = $this->input->get('keyword', true);
		$group_idInfo  =  null;
		$orderInfo     = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'order/get.json', $params, METHOD_GET); 

		if($orderInfo->result->type == 2){
			$group_id =$orderInfo->result->fk_group;
			$group_idInfo     = $this->callInnerApiDiy(API_HOST_OPEN_CONTEST, 'group/get.json', compact('group_id'), METHOD_GET);
			$group_idInfo     = $group_idInfo->result;
		}
		global $ORDER_PAY_SATATELIST;
		global $ORDER_LOTTERY_STATELIST;
		$data = compact('orderInfo', "userInfo", 'group_idInfo','contestInfo',"orderInfo", "ORDER_PAY_SATATELIST", "contest_name", "ORDER_LOTTERY_STATELIST");
		$this->display($data);
	}

	public function contest_order_export()
	{
		$this->verifyLogin();//用户验证

		$state = $this->input->post('state', true);
		$cname = $this->input->post("cname", true);

		$result = $this->Order_model->ExportOrder($cname, $state);

		$this->display($result);
	}
}
