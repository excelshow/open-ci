<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ModelBase.php';

/**
 * 活动项目表单数据处理类
 *
 * @author: zhaodechang@wesai.com
 **/
class Form_model extends ModelBase
{
	/**
	 * init
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	public function get_db()
	{
		return CONTEST_DB_CONFIG;
	}


	/**
	 * 新增报名表单
	 *
	 * @param $itemId
	 * @param $name
	 *
	 * @return mixed
	 *
	 */
	public function create($itemId, $name)
	{
		$params = array(
			'fk_contest_items' => $itemId,
			'name'             => $name,
			'utime'            => null,
		);

		return $this->setTable($this->tableNameForm)
		            ->addInsertColumns($params, ['utime'])
		            ->doInsert();
	}

	/**
	 * 根据表单ID
	 *
	 * @param  integer $pk_enrol_form 表单ID
	 * @param string   $dsn_type      数据库连接方式，默认从库
	 *
	 * @return array|bool
	 * @throws \Exception
	 */
	public function getById($pk_enrol_form, $dsn_type = Pdo_Mysql::DSN_TYPE_SLAVE)
	{
		$result = $this->setDsnType($dsn_type)
		               ->setTable($this->tableNameForm)
		               ->addQueryConditions('pk_enrol_form', $pk_enrol_form)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	/**
	 * 根据活动项目ID获取报名表单资料
	 *
	 * @param  integer $fk_contest_items 活动项目ID
	 * @param string   $dsn_type         数据库连接方式，默认从库
	 *
	 * @return array|bool
	 * @throws \Exception
	 */
	public function getByItemId($fk_contest_items, $dsn_type = Pdo_Mysql::DSN_TYPE_SLAVE)
	{
		$result = $this->setDsnType($dsn_type)
		               ->setTable($this->tableNameForm)
		               ->addQueryConditions('fk_contest_items', $fk_contest_items)
		               ->doSelect(1, 1);

		if (empty($result)) {
			return false;
		}

		return $result[0];
	}

	public function getByItemIds($itemIds)
	{
		return $this->setTable($this->tableNameForm)
		            ->addQueryConditionIn('fk_contest_items', $itemIds)
		            ->doSelect(1, count($itemIds));
	}

	/**
	 * 更新报名表单
	 *
	 * @param  integer $pk_enrol_form 表单ID
	 * @param  string  $name          表单名称
	 *
	 * @return mixed
	 */
	public function updateForm($pk_enrol_form, $name = null)
	{
		$params = array(
			'utime' => null,
		);
		if (!empty($name)) {
			$params['name'] = $name;
		}

		$conditions = compact('pk_enrol_form');

		return $this->mixedUpdateData('t_enrol_form', $params, $conditions);
	}
} // END class Msg_model
