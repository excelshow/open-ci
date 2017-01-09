<?php
/**
 * User: liangkaixuan
 */
require_once __DIR__ . '/Base.php';

class Notify extends Base
{
	const TIME_SLEEP_S  		= 10;
	const MQ_KEY				= MQ_TOPIC_EXT_NOTIFY;
	const type_contest_change 	= 'contest.update';
	const type_item_change 		= 'contest.item.update';
	const type_from_change 		= 'contest.item.form.update';
	const type_order_change 	= 'order.verify_code.update';
	public function __construct()
	{
		parent::__construct();
		$redisConfig = $this->config->item('redismq');
		$this->load->library('Redis_List_Client', $redisConfig);
		$this->load->helper('log');
		$this->load->model('Contest_model');
	}

	public function index()
	{
		while (true) {
			try {
				$notifyjson = $this->redis_list_client->RightPop(self::MQ_KEY);
				log_message_to_file("notify", ' pm=' . $notifyjson);
				if (empty($notifyjson)) {
					sleep(self::TIME_SLEEP_S);
					continue;
				}
				$res = json_decode($notifyjson,true);

				switch($res['action']){
					//活动修改
					case self::type_contest_change:
						$this->contestModify($res);
						break;
					//项目修改
					case self::type_item_change:
						$this->itemModify($res);
						break;
					//表单修改
					case self::type_from_change:
						$this->formModify($res);
						break;
					//验票核销
					case self::type_order_change:
						$this->orderComplete($res);
						break;
					// 默认验证回调
					default:
						log_message_v2(
							'error',
							array(
								'msg'     => 'softykt callback failed',
								'file'    => __FILE__,
								'line'    => __LINE__,
								'info'    => $res,
							)
						);
						break;
				}
			} catch (Exception $e) {
				$this->catchException($e);
				sleep(self::TIME_SLEEP_S);
			}
		}
	}

	/**
	 * 活动修改
	 */
	private function contestModify($param){
		$update = $this->Contest_model->modify_contest($param['contest_id']);
		if (empty($update)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'modify_contest failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $param,
				)
			);
			$this->setRedis($param, 'modify_contest');
		}elseif($update->error < 0){
			log_message('error', var_export($update,true));
			return false;
		}
	}


	/**
	 * 项目信息修改
	 */
	private function itemModify($param){
		$update = $this->Contest_model->modify_item($param['contest_id'], $param['item_id']);
		if (empty($update)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'modify_item failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $param,
				)
			);
			$this->setRedis($param, 'modify_item');
			return false;
		}elseif($update->error < 0){
			log_message('error', var_export($update,true));
			return false;
		}
	}

	/**
	 * 表单信息修改
	 */
	private function formModify($param){
		$row = $this->Contest_model->modify_form($param['contest_id'], $param['item_id']);
		if (empty($row)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'modify_from failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $param,
				)
			);
			$this->setRedis($param, 'modify_from');
		}elseif($row->error < 0){
			log_message('error', var_export($row,true));
			return false;
		}
	}


	/**
	 * 验票
	 */
	private function orderComplete($param){
		$storage = array(
			'verify_code'	=> $param['verify_code'],
			'max_verify' 	=> $param['max_verify'],
			'verify_number' => $param['verify_number'],
			'out_trade_no'	=> $param['out_trade_no']
		);
		$row = $this->Contest_model->order_complete($storage);
		if (empty($row)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'conplete_order failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'info'    => $storage,
				)
			);
			$this->setRedis($param, 'test_order');
		}elseif($row->error < 0){
			log_message('error', var_export($row,true));
			return false;
		}
	}

	/**
	 * 存储到redis
	 * @param $param
	 * @param $errormsg
	 */
	private function setRedis($param,$errormsg){
		$ret = $this->redis_list_client->LeftPush(self::MQ_KEY, json_encode($param));
		if (empty($ret)) {
			log_message_v2(
				'error',
				array(
					'msg'     => 'set '.$errormsg.' to redis failed',
					'file'    => __FILE__,
					'line'    => __LINE__,
					'param'   => $param,
				)
			);
		}
	}
}
