<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2016-12-02 11:14:50 --> array (
  'corp_id' => '1',
  'user_id' => '10',
  'scenicid' => '53',
  'productid' => '160',
  'name' => '全天滑雪票',
  'producttype' => '1',
  'usepeoplenum' => '1',
  'validbegindate' => '0000-00-00 00:00:00',
  'validenddate' => '0000-00-00 00:00:00',
  'price' => '150.00',
  'agentprice' => '100.00',
  'fee' => '130.00',
  'returnflag' => '0',
  'numberflag' => '0',
  'number' => '0',
  'memo' => '全天滑雪票详情',
  'webpic' => '',
  'consumebegindate' => '2016-07-06 12:12:12',
  'consumeenddate' => '2017-07-06 12:12:12',
  'consumestate' => '1',
  'hour' => '0',
)
ERROR - 2016-12-02 11:14:50 --> array (
  'corp_id' => '1',
  'user_id' => '10',
  'scenicid' => '53',
  'productid' => '161',
  'name' => '滑雪教练',
  'producttype' => '2',
  'usepeoplenum' => '0',
  'validbegindate' => '2016-08-02 00:00:00',
  'validenddate' => '2016-12-02 00:00:00',
  'price' => '200.00',
  'agentprice' => '120.00',
  'fee' => '150.00',
  'returnflag' => '0',
  'numberflag' => '0',
  'number' => '0',
  'memo' => '滑雪教练详情',
  'webpic' => '',
  'consumebegindate' => '2016-08-02 00:00:00',
  'consumeenddate' => '2016-12-02 00:00:00',
  'consumestate' => '1',
  'hour' => '0',
)
ERROR - 2016-12-02 11:14:50 --> array (
  'corp_id' => '1',
  'user_id' => '10',
  'scenicid' => '53',
  'productid' => '162',
  'name' => '滑雪板',
  'producttype' => '3',
  'usepeoplenum' => '0',
  'validbegindate' => '2016-08-02 00:00:00',
  'validenddate' => '2016-12-02 00:00:00',
  'price' => '300.00',
  'agentprice' => '200.00',
  'fee' => '280.00',
  'returnflag' => '0',
  'numberflag' => '0',
  'number' => '0',
  'memo' => '滑雪板详情',
  'webpic' => '',
  'consumebegindate' => '0000-00-00 00:00:00',
  'consumeenddate' => '0000-00-00 00:00:00',
  'consumestate' => '2',
  'hour' => '2',
)
ERROR - 2016-12-02 11:18:55 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_contest' in 'where clause',update t_contest_items set state = :state  where pk_contest = :pk_contest and state = :state_14806487355477
ERROR - 2016-12-02 11:20:20 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_contest' in 'where clause',update t_contest_items set publish_state = :publish_state  where pk_contest = :pk_contest and publish_state = :publish_state_14806488204824
ERROR - 2016-12-02 11:20:56 --> stdClass::__set_state(array(
   'error' => 0,
   'msg' => '',
   'sql' => 'update t_contest_items set publish_state = :publish_state  where pk_contest = :pk_contest and publish_state = :publish_state_14806488562074',
   'bindParams' => 
  array (
    ':publish_state' => '4',
    ':pk_contest' => '43',
    ':publish_state_14806488562074' => '1',
  ),
))
ERROR - 2016-12-02 11:20:56 --> [42] SQLSTATE[42S22]: Column not found: 1054 Unknown column 'pk_contest' in 'where clause',update t_contest_items set publish_state = :publish_state  where pk_contest = :pk_contest and publish_state = :publish_state_14806488562074
ERROR - 2016-12-02 11:22:31 --> stdClass::__set_state(array(
   'error' => 0,
   'msg' => '',
   'sql' => 'update t_contest set publish_state = :publish_state  where pk_contest = :pk_contest and publish_state = :publish_state_14806489514653',
   'bindParams' => 
  array (
    ':publish_state' => '4',
    ':pk_contest' => '43',
    ':publish_state_14806489514653' => '1',
  ),
))
