<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-11-03 15:03:26 --> Severity: Error --> Call to undefined method Voucherrule_model::list() /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 72
ERROR - 2016-11-03 15:03:43 --> Severity: Notice --> Uninitialized string offset: 0 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 195
ERROR - 2016-11-03 15:03:43 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 196
ERROR - 2016-11-03 15:03:43 --> Severity: Notice --> Uninitialized string offset: 0 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 195
ERROR - 2016-11-03 15:03:43 --> Severity: Notice --> Uninitialized string offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 196
ERROR - 2016-11-03 15:06:56 --> Severity: Notice --> Undefined offset: 1 /home/liangkaixuan/code/open-base/ci/system/libraries/ORM_Model.php 196
ERROR - 2016-11-03 15:55:01 --> Severity: Error --> Call to undefined method Voucherrule_model::saveState() /home/liangkaixuan/code/open-operation/api/application/controllers/voucher/Rule.php 89
ERROR - 2016-11-03 15:55:36 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher_rule' in 'where clause',update t_voucher_rule set state = :state  where fk_corp = :fk_corp and fk_voucher_rule = :fk_voucher_rule
ERROR - 2016-11-03 15:55:36 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher_rule' in 'where clause',update t_voucher_rule set state = :state  where fk_corp = :fk_corp and fk_voucher_rule = :fk_voucher_rule
ERROR - 2016-11-03 16:04:57 --> Severity: Warning --> Missing argument 1 for Voucherrule_model::logRulechange(), called in /home/liangkaixuan/code/open-operation/api/application/models/Voucherrule_model.php on line 83 and defined /home/liangkaixuan/code/open-operation/api/application/models/Voucherrule_model.php 110
ERROR - 2016-11-03 16:04:57 --> Severity: Warning --> Missing argument 2 for Voucherrule_model::logRulechange(), called in /home/liangkaixuan/code/open-operation/api/application/models/Voucherrule_model.php on line 83 and defined /home/liangkaixuan/code/open-operation/api/application/models/Voucherrule_model.php 110
ERROR - 2016-11-03 16:04:57 --> Severity: Notice --> Undefined variable: fk_voucher_rule /home/liangkaixuan/code/open-operation/api/application/models/Voucherrule_model.php 113
ERROR - 2016-11-03 16:14:57 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher_rule' in 'field list',insert into t_voucher_rule (fk_voucher_rule,from_state,to_state,ctime) values (:fk_voucher_rule,:from_state,:to_state,:ctime)
ERROR - 2016-11-03 16:14:58 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'fk_voucher_rule' in 'field list',insert into t_voucher_rule (fk_voucher_rule,from_state,to_state,ctime) values (:fk_voucher_rule,:from_state,:to_state,:ctime)
ERROR - 2016-11-03 16:35:33 --> 404 Page Not Found: 
ERROR - 2016-11-03 17:31:20 --> 404 Page Not Found: voucher/Voucjer/add.json
ERROR - 2016-11-03 17:31:28 --> Severity: Notice --> Undefined index: fk_voucher_rule /home/liangkaixuan/code/open-operation/api/application/models/Vouchervoucher_model.php 43
ERROR - 2016-11-03 17:31:28 --> Severity: Notice --> Undefined index: stop_time /home/liangkaixuan/code/open-operation/api/application/models/Vouchervoucher_model.php 45
ERROR - 2016-11-03 17:31:28 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'scope_type' in 'field list',insert into t_voucher (code,scope_type,value,value_min,fk_corp,utime,ctime) values (:code,:scope_type,:value,:value_min,:fk_corp,:utime,:ctime)
ERROR - 2016-11-03 17:31:50 --> Severity: Notice --> Undefined index: stop_time /home/liangkaixuan/code/open-operation/api/application/models/Vouchervoucher_model.php 45
ERROR - 2016-11-03 17:31:50 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'scope_type' in 'field list',insert into t_voucher (code,scope_type,value,value_min,fk_voucher_rule,fk_corp,utime,ctime) values (:code,:scope_type,:value,:value_min,:fk_voucher_rule,:fk_corp,:utime,:ctime)
ERROR - 2016-11-03 17:33:49 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'scope_type' in 'field list',insert into t_voucher (code,scope_type,value,value_min,fk_voucher_rule,fk_corp,stop_time,utime,ctime) values (:code,:scope_type,:value,:value_min,:fk_voucher_rule,:fk_corp,:stop_time,:utime,:ctime)
ERROR - 2016-11-03 17:33:49 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'scope_type' in 'field list',insert into t_voucher (code,scope_type,value,value_min,fk_voucher_rule,fk_corp,stop_time,utime,ctime) values (:code,:scope_type,:value,:value_min,:fk_voucher_rule,:fk_corp,:stop_time,:utime,:ctime)
ERROR - 2016-11-03 17:58:56 --> 404 Page Not Found: 
