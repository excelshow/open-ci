<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-11-04 10:21:23 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 652
ERROR - 2016-11-04 10:21:23 --> [0] SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens,update t_voucher_rule set  = :, = :_14782260837312,1 = :1,2 = :2  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:22:53 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 652
ERROR - 2016-11-04 10:22:53 --> [0] SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens,update t_voucher_rule set  = :, = :_14782261734389,1 = :1,2 = :2  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:22:56 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 652
ERROR - 2016-11-04 10:22:56 --> [0] SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens,update t_voucher_rule set  = :, = :_14782261761736,1 = :1,2 = :2  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:23:29 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 652
ERROR - 2016-11-04 10:23:29 --> [0] SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens,update t_voucher_rule set  = :, = :_14782262090682,1 = :1,2 = :2  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:23:35 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 652
ERROR - 2016-11-04 10:23:35 --> [0] SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens,update t_voucher_rule set  = :, = :_14782262158002,1 = :1,2 = :2  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:29:09 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_voucher' in 'where clause',update t_voucher_rule set fk_user = :fk_user,fk_component_authorizer_app = :fk_component_authorizer_app,state = :state,utime = :utime  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:29:09 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_voucher' in 'where clause',update t_voucher_rule set fk_user = :fk_user,fk_component_authorizer_app = :fk_component_authorizer_app,state = :state,utime = :utime  where pk_voucher = :pk_voucher
ERROR - 2016-11-04 10:34:54 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher' in 'field list',insert into t_voucher_user_change_log (fk_voucher,to_user_id) values (:fk_voucher,:to_user_id)
ERROR - 2016-11-04 10:34:54 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher' in 'field list',insert into t_voucher_user_change_log (fk_voucher,to_user_id) values (:fk_voucher,:to_user_id)
ERROR - 2016-11-04 10:38:27 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'to_user_id' in 'field list',insert into t_voucher_user_change_log (fk_voucher,to_user_id) values (:fk_voucher,:to_user_id)
ERROR - 2016-11-04 10:38:27 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'to_user_id' in 'field list',insert into t_voucher_user_change_log (fk_voucher,to_user_id) values (:fk_voucher,:to_user_id)
ERROR - 2016-11-04 10:40:59 --> [0] SQLSTATE[HY000]: General error: 1364 Field 'from_fk_user' doesn't have a default value,insert into t_voucher_user_change_log (fk_voucher,to_fk_user) values (:fk_voucher,:to_fk_user)
ERROR - 2016-11-04 10:40:59 --> [0] SQLSTATE[HY000]: General error: 1364 Field 'from_fk_user' doesn't have a default value,insert into t_voucher_user_change_log (fk_voucher,to_fk_user) values (:fk_voucher,:to_fk_user)
ERROR - 2016-11-04 11:34:41 --> [0] SQLSTATE[HY000]: General error: 1364 Field 'from_state' doesn't have a default value,insert into t_voucher_state_log (fk_voucher,to_state) values (:fk_voucher,:to_state)
ERROR - 2016-11-04 11:58:37 --> 404 Page Not Found: 
ERROR - 2016-11-04 12:04:26 --> 404 Page Not Found: Faviconico/index
ERROR - 2016-11-04 12:04:26 --> 404 Page Not Found: Faviconico/index
ERROR - 2016-11-04 15:03:29 --> Severity: Error --> Call to undefined method Voucherrule_model::consume() /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Voucher.php 182
ERROR - 2016-11-04 17:14:32 --> Severity: Error --> Call to undefined method Activity::newDatetime() /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 39
ERROR - 2016-11-04 17:15:06 --> Severity: Notice --> Undefined property: Activity::$Activaty_model /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 39
ERROR - 2016-11-04 17:15:06 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php:39) /home/liangkaixuan/code/open-base/ci/system/core/Common.php 573
ERROR - 2016-11-04 17:15:06 --> Severity: Error --> Call to a member function newDatetime() on null /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 39
ERROR - 2016-11-04 17:15:57 --> Severity: Notice --> Undefined property: Activity::$Activaty_model /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 46
ERROR - 2016-11-04 17:15:57 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php:46) /home/liangkaixuan/code/open-base/ci/system/core/Common.php 573
ERROR - 2016-11-04 17:15:57 --> Severity: Error --> Call to a member function create() on null /home/liangkaixuan/code/open-operation/api/application/controllers/activity/Activity.php 46
