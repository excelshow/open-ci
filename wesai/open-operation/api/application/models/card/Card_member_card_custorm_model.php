<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Card_member_card_custorm_model extends MY_Model
{

	protected $tableNameCardMemberCardCustomField = 't_card_member_card_custom_field';
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * 获取单条数据
	 * @param $custom_field_id
	 * @return array
	 */
	public function getById($custom_field_id)
	{
		$columns = $this->getMemberCardCustomFidldColumns();

		$result = $this->setTable($this->tableNameCardMemberCardCustomField)
			->addQueryFields($columns)
			->addQueryConditions('pk_card_member_card_custom_field', $custom_field_id)
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
	private function getMemberCardCustomFidldColumns()
	{
		$columns = array(
			'pk_card_member_card_custom_field as card_member_card_custom_field_id',
			'fk_card as card_id',
			'field_type',
			'name_type',
			'wx_field_seq',
			'name',
			'url',
			'ctime',
			'utime'
		);
		return $columns;
	}


	/**
	 * 根据卡券获取列表
	 * @param $card_id
	 * @return array|bool
	 */
	public function listByCardId($card_id)
	{
		$columns = $this->getMemberCardCustomFidldColumns();

		$result = $this->setTable($this->tableNameCardMemberCardCustomField)
			->addQueryFields($columns)
			->addQueryConditions('fk_card', $card_id)
			->addQueryConditions('wx_field_seq', 0, '>')
			->addQueryConditions('state', CARD_MEMBER_CARD_CUSTOM_FAILED_STATE_OK)
			->addOrderBy('wx_field_seq', 'asc')
			->doSelect();
		if (empty($result)) {
			return array();
		}
		return $result;
	}


}
