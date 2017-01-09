<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Card_member_card_model extends MY_Model
{

	protected $tableNameCardMemberCard = 't_card_member_card';
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取单条数据
	 * @param $card_id
	 * @return array
	 */
	public function getByCardId($card_id)
	{
		$columns = $this->getMemberCardColumns();

		$result = $this->setTable($this->tableNameCardMemberCard)
			->addQueryFields($columns)
			->addQueryConditions('fk_card', $card_id)
			->doSelect();
		if (empty($result)) {
			return array();
		}
		return $result[0];

	}

	/**
	 * 获取单条、列表数据时的字段值
	 * @return array
	 */
	private function getMemberCardColumns()
	{
		$columns = array(
			'pk_card_member_card as card_member_card_id',
			'fk_card as card_id',
			'background_pic_url',
			'prerogative',
			'auto_activate',
			'wx_activate',
			'supply_balance',
			'balance_url',
			'supply_bonus',
			'bonus_url',
			'discount',
			'ctime',
			'utime'
		);
		return $columns;
	}


}
