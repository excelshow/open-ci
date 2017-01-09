<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-11-08 15:46:11 --> Severity: Notice --> Undefined index: stete /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 201
ERROR - 2016-11-08 15:47:17 --> Severity: Notice --> Undefined index: stete /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 201
ERROR - 2016-11-08 15:48:12 --> Severity: Error --> Call to undefined method Voucher_model::saveState() /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 205
ERROR - 2016-11-08 16:02:58 --> 404 Page Not Found: voucher/Vouc/change_state_to_cancel.json
ERROR - 2016-11-08 16:35:57 --> Severity: Notice --> Undefined variable: number_created /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 51
ERROR - 2016-11-08 16:55:33 --> 404 Page Not Found: 
ERROR - 2016-11-08 17:54:15 --> Severity: Error --> Call to undefined method Activity_model::findOne() /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 75
ERROR - 2016-11-08 17:57:09 --> 404 Page Not Found: 
ERROR - 2016-11-08 17:59:06 --> Severity: Notice --> Array to string conversion /home/liangkaixuan/code/open-base/ci/system/libraries/Pdo_Mysql.php 171
ERROR - 2016-11-08 17:59:42 --> Severity: Notice --> Array to string conversion /home/liangkaixuan/code/open-base/ci/system/libraries/Pdo_Mysql.php 171
ERROR - 2016-11-08 18:01:37 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'state' in 'where clause',select * from t_activity_user_action   where state = :state and fk_activity = :fk_activity and rule = :rule and type = :type
ERROR - 2016-11-08 18:02:09 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'state' in 'where clause',select * from t_activity_user_action   where state = :state and fk_activity = :fk_activity and rule = :rule and type = :type
ERROR - 2016-11-08 18:02:39 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'state' in 'where clause',select * from t_activity_user_action   where state = :state and fk_activity = :fk_activity and rule = :rule and type = :type
ERROR - 2016-11-08 18:08:14 --> 404 Page Not Found: 
ERROR - 2016-11-08 18:08:27 --> 404 Page Not Found: 
ERROR - 2016-11-08 18:08:58 --> Severity: Notice --> Array to string conversion /home/liangkaixuan/code/open-base/ci/system/libraries/Pdo_Mysql.php 171
ERROR - 2016-11-08 18:19:08 --> Severity: Notice --> Undefined property: Activity::$tableNameVoucher /home/liangkaixuan/code/open-base/ci/system/core/Model.php 77
ERROR - 2016-11-08 18:19:08 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php:692) /home/liangkaixuan/code/open-base/ci/system/core/Common.php 573
ERROR - 2016-11-08 18:19:08 --> Severity: Error --> Call to undefined function log_message_v2() /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 692
ERROR - 2016-11-08 18:25:34 --> 404 Page Not Found: 
ERROR - 2016-11-08 19:02:38 --> [23000] SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '2-9527-9528' for key 'unq_activity_user_invite',insert into t_activity_user_invite (fk_activity,fk_user,invited_fk_user) values (:fk_activity,:fk_user,:invited_fk_user)
ERROR - 2016-11-08 19:03:39 --> [23000] SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry '2-9527-9528' for key 'unq_activity_user_invite',insert into t_activity_user_invite (fk_activity,fk_user,invited_fk_user) values (:fk_activity,:fk_user,:invited_fk_user)
ERROR - 2016-11-08 19:09:23 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'activity_id' in 'where clause',select * from t_activity_user_invite   where activity_id = :activity_id and user_id = :user_id and invited_fk_user = :invited_fk_user
ERROR - 2016-11-08 19:09:40 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'activity_id' in 'where clause',select * from t_activity_user_invite   where activity_id = :activity_id and user_id = :user_id and invited_fk_user = :invited_fk_user
ERROR - 2016-11-08 19:10:29 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'activity_id' in 'where clause',select * from t_activity_user_invite   where activity_id = :activity_id and user_id = :user_id and invited_fk_user = :invited_fk_user
ERROR - 2016-11-08 19:17:09 --> Severity: Notice --> Undefined index: pk_mapping_activity_user /home/liangkaixuan/code/open-operation/api/application/models/activity/Activity_model.php 257
ERROR - 2016-11-08 19:17:09 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'number_action' in 'field list',update t_mapping_activity_operation set number_action = number_action + 1 
ERROR - 2016-11-08 19:20:59 --> Severity: Notice --> Undefined index: pk_mapping_activity_user /home/liangkaixuan/code/open-operation/api/application/models/activity/Activity_model.php 257
ERROR - 2016-11-08 19:21:53 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_mapping_activity_user' in 'where clause',update t_mapping_activity_operation set number_action = number_action + 1  where pk_mapping_activity_user = :pk_mapping_activity_user
ERROR - 2016-11-08 19:28:17 --> 404 Page Not Found: 
