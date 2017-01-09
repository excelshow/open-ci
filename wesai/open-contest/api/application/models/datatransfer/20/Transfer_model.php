<?php
/**
 * User: zhaodc
 * Date: 28/10/2016
 * Time: 11:57
 */
require_once __DIR__ . '/../../ModelBase.php';

class Transfer_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
		set_time_limit(0);
	}

	public function upgradeDb()
	{
		$sqls[] = 'ALTER TABLE t_contest CHANGE sdate sdate_start DATE NOT NULL DEFAULT \'0000-00-00\';';
		$sqls[] = 'ALTER TABLE t_contest ADD sdate_end DATE DEFAULT \'0000-00-00\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest ADD service_mail VARCHAR(30) DEFAULT \'\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest ADD template TINYINT(4) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest_items ADD `type` TINYINT(4) DEFAULT \'1\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest_items ADD team_max_stock INT(11) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest_items ADD team_cur_stock INT(11) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest_items ADD team_size INT(11) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_contest_items ADD multi_buy TINYINT(4) DEFAULT \'2\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_enrol_invite_code CHANGE fk_order fk_enrol_data INT(11) NOT NULL DEFAULT \'0\';';
		$sqls[] = 'ALTER TABLE t_mapping_contest_location RENAME TO `t_mapping_contest_location_bak`;';
		$sqls[] = 'ALTER TABLE t_order ADD seller_fk_corp INT(11) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_order CHANGE fk_corp owner_fk_corp INT(11) DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_order CHANGE ip ip INT(11) unsigned DEFAULT \'0\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_order ADD type TINYINT(4) DEFAULT \'1\' NOT NULL;';
		$sqls[] = 'ALTER TABLE t_order ADD fk_group INT(11);';
		$sqls[] = 'ALTER TABLE t_order ADD fk_team INT(11);';
		$sqls[] = 'ALTER TABLE t_order ADD fk_enrol_data INT(11);';
		$sqls[] = 'ALTER TABLE t_order ADD copies INT(11) DEFAULT \'1\' NOT NULL;';
		$sqls[] = 'CREATE INDEX idx_trans_id ON open_contest.t_order (channel_transaction_id);';
		$sqls[] = 'ALTER TABLE open_contest.t_order MODIFY channel_transaction_id VARCHAR(40) DEFAULT NULL;';
		$sqls[] = 'DROP INDEX idx_corp_app_user_time ON t_order;';
		$sqls[] = 'DROP INDEX idx_corp_contest_item_time ON t_order;';
		$sqls[] = 'ALTER TABLE t_order ADD KEY `idx_owner_contest_item_time` (`owner_fk_corp`, `fk_contest`, `ctime`);';
		$sqls[] = 'ALTER TABLE t_order ADD KEY `idx_seller_app_user_time` (`seller_fk_corp`, `fk_component_authorizer_app`, `fk_user`, `ctime`);';
		$sqls[] = 'ALTER TABLE t_order ADD KEY `idx_group_time` (`fk_group`, `ctime`);';
		$sqls[] = 'ALTER TABLE t_order ADD KEY `idx_team_time` (`fk_team`, `ctime`);';
		$sqls[] = 'CREATE TABLE `t_enrol_data` (
  `pk_enrol_data` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user` int(11) NOT NULL,
  `fk_enrol_form` int(11) NOT NULL,
  `fk_contest_items` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `fk_team` int(11) DEFAULT NULL,
  `fk_group` int(11) DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_enrol_data`),
  KEY `idx_group_time_user` (`fk_group`,`ctime`,`fk_user`),
  KEY `idx_team_time_user` (`fk_team`,`ctime`,`fk_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_enrol_data_detail` (
  `pk_enrol_data_detail` int(11) NOT NULL AUTO_INCREMENT,
  `fk_enrol_data` int(11) NOT NULL,
  `fk_enrol_form_item` int(11) NOT NULL,
  `title` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `value` varchar(255) NOT NULL DEFAULT \'\',
  `seq` int(11) NOT NULL DEFAULT \'0\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_enrol_data_detail`),
  KEY `idx_enrol_data_seq` (`fk_enrol_data`,`seq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_group` (
  `pk_group` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user` int(11) NOT NULL,
  `fk_contest` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `cur_member_count` int(11) NOT NULL DEFAULT \'0\',
  `leader_name` varchar(20) NOT NULL,
  `leader_contact` varchar(20) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_group`),
  KEY `idx_user_time` (`fk_user`,`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_group_state_log` (
  `pk_group_state_log` int(11) NOT NULL AUTO_INCREMENT,
  `fk_group` int(11) NOT NULL,
  `from_state` tinyint(4) NOT NULL,
  `to_state` int(11) NOT NULL,
  `remark` varchar(50) NOT NULL DEFAULT \'\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_group_state_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_mapping_contest_location` (
  `pk_mapping_contest_location` int(11) NOT NULL AUTO_INCREMENT,
  `fk_contest` int(11) NOT NULL,
  `fk_tag` int(11) NOT NULL,
  `level` tinyint(4) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_mapping_contest_location`),
  UNIQUE KEY `unq_contest_level_tag` (`fk_contest`,`level`,`fk_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$sqls[] = 'CREATE TABLE `t_mapping_contest_unit` (
  `pk_mapping_contest_unit` int(11) NOT NULL AUTO_INCREMENT,
  `fk_contest` int(11) NOT NULL,
  `fk_tag` int(11) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_mapping_contest_unit`),
  UNIQUE KEY `unq_contest_role_tag` (`fk_contest`,`role`,`fk_tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8';
		$sqls[] = 'CREATE TABLE `t_mapping_group_user` (
  `pk_mapping_group_user` int(11) NOT NULL AUTO_INCREMENT,
  `fk_group` int(11) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_mapping_group_user`),
  UNIQUE KEY `unq_group_user` (`fk_group`,`fk_user`),
  KEY `idx_user_group_time` (`fk_user`,`fk_group`,`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_mapping_team_user` (
  `pk_mapping_team_user` int(11) NOT NULL AUTO_INCREMENT,
  `fk_team` int(11) NOT NULL,
  `fk_user` int(11) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_mapping_team_user`),
  UNIQUE KEY `unq_team_user` (`fk_team`,`fk_user`),
  KEY `idx_user_team_time` (`fk_user`,`fk_team`,`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_team` (
  `pk_team` int(11) NOT NULL AUTO_INCREMENT,
  `fk_user` int(11) NOT NULL,
  `fk_contest_items` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `max_member_count` int(11) NOT NULL DEFAULT \'0\',
  `cur_member_count` int(11) NOT NULL DEFAULT \'0\',
  `leader_name` varchar(20) NOT NULL,
  `leader_contact` varchar(20) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT \'1\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_team`),
  KEY `idx_user_time` (`fk_user`,`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_team_state_log` (
  `pk_team_state_log` int(11) NOT NULL AUTO_INCREMENT,
  `fk_team` int(11) NOT NULL,
  `from_state` tinyint(4) NOT NULL,
  `to_state` int(11) NOT NULL,
  `remark` varchar(50) NOT NULL DEFAULT \'\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_team_state_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_verify_code` (
  `pk_verify_code` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(20) NOT NULL,
  `fk_order` int(11) NOT NULL,
  `fk_enrol_data` int(11) NOT NULL,
  `max_verify` int(11) NOT NULL DEFAULT \'0\',
  `verify_number` int(11) NOT NULL DEFAULT \'0\',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `utime` timestamp NOT NULL DEFAULT \'0000-00-00 00:00:00\' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_verify_code`),
  UNIQUE KEY `unq_code` (`code`),
  KEY `idx_order_enrol_data_time` (`fk_order`,`fk_enrol_data`,`ctime`),
  KEY `idx_enrol_data_time` (`fk_enrol_data`,`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';
		$sqls[] = 'CREATE TABLE `t_verify_code_verify_log` (
  `pk_verify_code_verify_log` int(11) NOT NULL AUTO_INCREMENT,
  `fk_verify_code` int(11) NOT NULL,
  `from_number` int(11) NOT NULL,
  `to_number` int(11) NOT NULL,
  `fk_corp_user` int(11) NOT NULL,
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pk_verify_code_verify_log`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

		try {

			$this->beginTransaction();

			foreach ($sqls as $sql) {
				$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

				var_dump($sql, $result);
			}

			$this->commit();

		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
	}

	public function upgradeTagLocation()
	{
		echo __METHOD__ . ' start' . PHP_EOL;

		$tags = '{"error":0,"cost":0.06492805480957,"total":22,"page":1,"size":10,"data":[{"tag_id":"8","name":"\u4e2d\u56fd","state":"1","level":"1"},{"tag_id":"10","name":"\u5317\u4eac\u5e02","state":"1","level":"2"},{"tag_id":"12","name":"\u5317\u4eac\u5e02\u5e02\u8f96\u533a","state":1,"level":"3"},{"tag_id":"13","name":"\u6d77\u6dc0\u533a","state":1,"level":"4"},{"tag_id":"14","name":"\u671d\u9633\u533a","state":1,"level":"4"},{"tag_id":"15","name":"\u5929\u6d25\u5e02","state":1,"level":"2"},{"tag_id":"16","name":"\u5929\u6d25\u5e02\u5e02\u8f96\u533a","state":1,"level":"3"},{"tag_id":"17","name":"\u6cb3\u4e1c\u533a","state":1,"level":"4"},{"tag_id":"18","name":"\u6cb3\u5317\u7701","state":1,"level":"2"},{"tag_id":"19","name":"\u77f3\u5bb6\u5e84\u5e02","state":1,"level":"3"},{"tag_id":"20","name":"\u65b0\u534e\u533a","state":1,"level":"4"},{"tag_id":"21","name":"\u77f3\u666f\u5c71\u533a","state":1,"level":"4"},{"tag_id":"11","name":"\u4e1c\u57ce\u533a","state":"1","level":"4"},{"tag_id":"22","name":"\u4e0a\u6d77\u5e02","state":1,"level":"2"},{"tag_id":"23","name":"\u4e0a\u6d77\u5e02\u5e02\u8f96\u533a","state":1,"level":"3"},{"tag_id":"24","name":"\u6d66\u4e1c\u65b0\u533a","state":1,"level":"4"},{"tag_id":"25","name":"\u5bc6\u4e91\u533a","state":1,"level":"4"},{"tag_id":"26","name":"\u897f\u57ce\u533a","state":1,"level":"4"},{"tag_id":"27","name":"\u65b0\u7586\u7ef4\u543e\u5c14\u81ea\u6cbb\u533a","state":1,"level":"2"},{"tag_id":"28","name":"\u4e4c\u9c81\u6728\u9f50\u5e02","state":1,"level":"3"},{"tag_id":"29","name":"\u4e4c\u9c81\u6728\u9f50\u53bf","state":1,"level":"4"},{"tag_id":"30","name":"\u5929\u6d25\u5e02\u90ca\u53bf","state":1,"level":"3"}]}';
		$tags = json_decode($tags, true);
		$tags = $tags['data'];
		$tags = array_column($tags, null, 'name');

		try {
			$this->beginTransaction();

			$sql = 'select * from t_tag_location';

			$old_tags = $this->getAll(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array(), 0, 0);

			$old_tags = array_column($old_tags, null, 'pk_tag_location');

			$sql = 'select * from t_mapping_contest_location_bak';

			$old_mappings = $this->getAll(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array(), 0, 0);

			foreach ($old_mappings as $mapping) {
				$old_tag = $old_tags[$mapping['fk_tag_location']];

				$sql = 'insert into t_mapping_contest_location (fk_contest, fk_tag, `level`) values (?,?,?)';

				if (empty($tags[$old_tag['name']])) {
					continue;
				}
				$new_tag_id = $tags[$old_tag['name']]['tag_id'];

				$params = array($mapping['fk_contest'], $new_tag_id, $mapping['rd_level']);

				$this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sql, $params);
			}

			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}

		echo __METHOD__ . ' end' . PHP_EOL;
	}

	public function upgradeContest()
	{
		echo __METHOD__ . ' start' . PHP_EOL;
		try {
			$this->beginTransaction();

			$sql = 'update t_contest set sdate_end = sdate_start, template = 1;';

			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			$sql = 'update t_contest set sdate_end = sdate_start, template = 2 WHERE fk_corp = 9;';

			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			$sql = 'update t_contest_items set multi_buy = 1 WHERE fk_contest in (22,23,24,25,26,27,28,30,31,29,32,33,34,36,37,42)';

			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}

		echo __METHOD__ . ' end' . PHP_EOL;
	}

	public function upgradeOrder()
	{
		echo __METHOD__ . ' start' . PHP_EOL;

		try {
			$this->beginTransaction();

			$sql    = 'update t_order set seller_fk_corp = owner_fk_corp';
			$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			// var_dump($sql, $result);


			$pageNumber = 1;
			$pageSize   = 100;
			$sql        = 'select * from t_order order BY pk_order asc';
			while (true) {
				$orderList = $this->getAll(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array(), $pageNumber, $pageSize);
				if (empty($orderList)) {
					break;
				}

				echo 'pageNumebr: ' . $pageNumber . PHP_EOL, 'orderCount: ' . count($orderList) . PHP_EOL;
				// echo 'order count: ' . count($orderList) . PHP_EOL;

				foreach ($orderList as $order) {
					echo $order['pk_order'] . ' start ...' . PHP_EOL;
					$sql_enrol_info = 'select * from t_enrol_info WHERE fk_order = ' . $order['pk_order'];
					$enrolInfoList  = $this->getAll(Pdo_Mysql::DSN_TYPE_MASTER, $sql_enrol_info, array(), 0, 0);

					// var_dump($sql_enrol_info);

					// echo 'enrol info count: ' . count($enrolInfoList) . PHP_EOL;
					//
					// echo 'get enrol form info' . PHP_EOL;

					$sql_enrol_form = 'select * from t_enrol_form WHERE fk_contest_items = ' . $order['fk_contest_items'];
					$enrolForm      = $this->getSingle(Pdo_Mysql::DSN_TYPE_MASTER, $sql_enrol_form, array());

					// var_dump($sql_enrol_form);

					// echo 'create enrol data ...' . PHP_EOL;

					$sql_enrol_data = 'insert into t_enrol_data (fk_user, fk_enrol_form, fk_contest_items, `type`) values (?, ?, ?, ?)';
					$params         = array($order['fk_user'], $enrolForm['pk_enrol_form'], $order['fk_contest_items'], ENROL_DATA_TYPE_SINGLE);

					$result_enrol_data = $this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sql_enrol_data, $params);
					// var_dump($sql_enrol_data);

					// echo 'create enrol data done' . PHP_EOL;
					//
					// echo 'create enrol data detail ...' . PHP_EOL;

					$seq = 0;
					foreach ($enrolInfoList as $enrolInfo) {
						$sql_enrol_data_detail = 'insert into t_enrol_data_detail (fk_enrol_data, fk_enrol_form_item, title, `type`, `value`, seq) values (?, ?, ?, ?, ?, ?)';
						$params                = array($result_enrol_data, $enrolInfo['fk_enrol_form_item'], $enrolInfo['title'], $enrolInfo['type'], $enrolInfo['value'], $seq);
						$seq++;

						$result_enrol_data_detail = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql_enrol_data_detail, $params);
						// var_dump($sql_enrol_data_detail, $result_enrol_data_detail);
					}

					// echo 'create enrol data detail done' . PHP_EOL;

					if ($order['state'] == ORDER_STATE_CLOSED) {
						// echo 'create verify code...' . PHP_EOL;

						$verify_number = $order['verify_number'];
						for ($i = 0; $i < $order['max_verify']; $i++) {
							$sql_verify_code = 'insert into t_verify_code (code, fk_order, fk_enrol_data, max_verify, verify_number) values (?, ?, ?, ?, ?)';
							for ($j = 0; $j < 3; $j++) {
								try {

									$code = $this->generateVerifyCode();

									$new_veriyf_number = $verify_number > 0 ? 1 : 0;
									$verify_number--;
									$params = array($code, $order['pk_order'], $result_enrol_data, 1, $new_veriyf_number);

									$result = $this->insert(Pdo_Mysql::DSN_TYPE_MASTER, $sql_verify_code, $params);
									if (!empty($result)) {
										break;
									}
								} catch (Exception $e) {
									echo $e->getMessage() . PHP_EOL;
									continue;
								}
							}
							// var_dump($sql_verify_code, $result);
						}

						// echo 'create verify code done' . PHP_EOL;

					}

					$sql_order = 'update t_order set fk_enrol_data = ' . $result_enrol_data . ' where pk_order = ' . $order['pk_order'];

					$result = $this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql_order, array());

					// echo 'update order fk_enrol_data ' . $result;

					echo $order['pk_order'] . ' end ...' . PHP_EOL;
				}

				$pageNumber++;
			}


			$sql = 'ALTER TABLE t_order DROP fk_contest_items;';

			$this->update(Pdo_Mysql::DSN_TYPE_MASTER, $sql, array());

			$this->commit();
		} catch (Exception $e) {
			$this->rollBack();
			throw $e;
		}
		echo __METHOD__ . ' end' . PHP_EOL;
	}
}
