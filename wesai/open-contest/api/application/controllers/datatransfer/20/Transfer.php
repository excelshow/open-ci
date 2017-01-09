<?php
/**
 * User: zhaodc
 * Date: 28/10/2016
 * Time: 11:10
 */
require_once __DIR__ . '/../../Base.php';

class Transfer extends Base
{
	public function __construct()
	{
		parent::__construct();

		if (time() > strtotime('2016-11-8')) {
			exit('数据迁移已过期');
		}

		$this->load->model('datatransfer/20/Transfer_model');
    }

	public function go_get()
	{
		echo 'Start ...' . PHP_EOL;
		$this->Transfer_model->upgradeDB();
		$this->Transfer_model->upgradeContest();
		$this->Transfer_model->upgradeOrder();
		$this->Transfer_model->upgradeTagLocation();

		echo 'Done .' . PHP_EOL;
	}


}
