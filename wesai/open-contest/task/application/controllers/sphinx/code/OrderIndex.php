<?php

require_once dirname(dirname(__DIR__)) . '/Base.php';
require_once __DIR__ . '/Schema.php';

class OrderIndex extends Base
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getHostName()
	{
		return API_HOST_OPEN_CONTEST;
	}

	public function getAllowedApiList()
	{
		return array();
	}

	public function index()
	{
		echo Schema::getDocHeader();
		echo Schema::getDocSchemaOrder();

		$document = Schema::getDocFormatOrder();

		$contestListAll = array();
		$corp_list = array();//企业信息
		$app_list = array();//公众号信息
		$page        = 1;
		$size        = 100;
		while (true) {

			$orderList = $this->callInnerApiDiy($this->getHostName(), 'order/list.json', compact('page', 'size'), METHOD_GET);
			$orderList = $this->obj2array($orderList);
			if (empty($orderList['data'])) {
				break;
			}
			$orderList = $orderList['data'];

			$contestIds = array_column($orderList, 'fk_contest');
			$contestIds = array_flip($contestIds);
			$contestIds = array_diff_key($contestIds, $contestListAll);
			$contestIds = array_flip($contestIds);

			if (!empty($contestIds)) {
				$cids = implode(',', $contestIds);
				$contestList = $this->callInnerApiDiy($this->getHostName(), 'contest/get_by_ids.json', compact('cids'), METHOD_GET);
				$contestList = $this->obj2array($contestList);
				if (empty($contestList['data'])) {
					$page++;
					continue;
				}

				$contestList = array_column($contestList['data'], null, 'pk_contest');

				$contestListAll += $contestList;

				unset($contestList);
			}

			/* open-pay 不升级，先注释掉 @2016-12-18
            $outTradeNos = array_column($orderList, 'out_trade_no');
            $distList = array();
            if(!empty($outTradeNos) && is_array($outTradeNos)){
                $this->load->model('pay/Order_model');
                $distRtn = $this->Order_model->get_dist_by_out_trade_nos($outTradeNos);
                if(!empty($distRtn['data'])){
                    reset($distRtn['data']);
                    while(list(, $dist) = each($distRtn['data'])){
                        $distList[$dist['out_trade_no']] = $dist;
                    }
                }
            }
            */
			foreach ($orderList as $key => $value) {
				$is_dist = $value['seller_fk_corp'] != $value['owner_fk_corp'] ? 1 : 0;
				$dist_contest_plan_id = 0;
				$dist_contest_choose_id = 0;
				/*
                if(!empty($distList[ORDER_PAY_SERVICE . $value['out_trade_no']])){
                    $dist =  $distList[ORDER_PAY_SERVICE . $value['out_trade_no']];
                    $dist_contest_plan_id = !empty($dist['fk_contest_plan']) ? $dist['fk_contest_plan'] : '';
                    $dist_contest_choose_id = !empty($dist['fk_contest_choose']) ? $dist['fk_contest_choose'] : '';
                }
                */
				$extends = $this->get_cache_corp($value['owner_fk_corp'],$corp_list);
				$app_name = $this->get_cache_app($value['fk_component_authorizer_app'],$app_list);
				if(!isset($contestListAll[$value['fk_contest']])){
					continue;
				}
				echo sprintf(
						$document,
						$value['pk_order'],
						$value['seller_fk_corp'],
						$value['owner_fk_corp'],
						$is_dist,
						$dist_contest_plan_id,
						$dist_contest_choose_id,
						$contestListAll[$value['fk_contest']]['name'] . ' ' . $contestListAll[$value['fk_contest']]['ename'],
						empty($app_name)?'':$app_name,
						empty($extends['corp_name'])?'':$extends['corp_name'],//参与展示
						empty($extends['corp_full_name'])?'':$extends['corp_full_name'],
						$value['channel_transaction_id'],//参与展示
						empty($extends['corp_id'])?'':$extends['corp_id'],
						0,//corp_change_type
						$value['out_trade_no'],//参与展示
						$value['amount_pay'],
						$value['amount'],
						$value['type'],
						$value['state'],
						(strtotime($value['paid_time'])<0 || $value['paid_time']=="0000-00-00 00:00:00")?0:strtotime($value['paid_time']),
						strtotime($value['ctime'])
				);
			}
			$page++;
		}
		echo Schema::getDocFooter();
	}


	/**
	 * @param $pk_crop
	 * @return mixed
	 */
	private  function get_crop_info($pk_crop){
		$params = array("pk_corp"=>$pk_crop);
		$data = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'qywx/corp/get_by_pk.json', $params, METHOD_GET);
		return $data;
	}

	/**
	 * @param $wechat_authorizer_appid
	 * @return mixed
	 */
	private  function get_wechat_app_info($wechat_authorizer_appid){
		$params = array("apppk"=>$wechat_authorizer_appid);
		$data = $this->callInnerApiDiy(API_HOST_OPEN_WEIXIN, 'component/authorizer/get_by_pk.json', $params, METHOD_GET);
		return $data;
	}

	/**
	 * 获取企业信息cache
	 * @param $corp_id
	 * @param $corp_list
	 * @return ""
	 */
	private function get_cache_corp($corp_id,&$corp_list){
		$result = array('corp_name','corp_full_name','corp_id');
		if(!empty($corp_list[$corp_id])){
			return $corp_list[$corp_id];
		}else{
			$crop_info = $this->get_crop_info($corp_id);//获取企业信息
			if(empty($crop_info) || empty($crop_info->result)){
				log_message('error', '构建索引异常 get_crop_info');
				return $result;
			}else{
				$corp_list[$corp_id]['corp_name'] = $crop_info->result->corp_name;
				$corp_list[$corp_id]['corp_full_name'] = $crop_info->result->corp_full_name;
				$corp_list[$corp_id]['corp_id'] = $crop_info->result->corp_id;
			}
			return $corp_list[$corp_id];
		}
	}

	/**
	 * 获取公众号信息缓存
	 * @param $fk_component_authorizer_app
	 * @param $app_list
	 * @return string
	 */
	private  function get_cache_app($fk_component_authorizer_app,&$app_list){
		$result = "";
		if(!empty($app_list[$fk_component_authorizer_app])){
			return $app_list[$fk_component_authorizer_app];
		}else{
			$wechat_app_info = $this->get_wechat_app_info($fk_component_authorizer_app);//获取微信公众号信息
			if(empty($wechat_app_info) || empty($wechat_app_info->result)){
				log_message('error', '构建索引异常 get_wechat_app_info');
				return $result;
			}else{
				$app_list[$fk_component_authorizer_app] = $wechat_app_info->result->nick_name;
			}
			return $app_list[$fk_component_authorizer_app];
		}

	}


}
