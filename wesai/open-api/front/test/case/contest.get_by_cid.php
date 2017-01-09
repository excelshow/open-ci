<?php
define('TEST_DIR', dirname(dirname(__FILE__)));

include_once(TEST_DIR . '/config.php');
include_once(TEST_DIR . '/common.php');

$api = 'contest/get_by_cid.json';
$params = array();
$params['ccid'] = '2';

call_api($api, $params, 'get');

