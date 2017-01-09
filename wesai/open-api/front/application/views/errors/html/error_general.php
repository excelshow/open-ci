<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/Error_Code.php';
require_once APPPATH . '/helpers/error_helper.php';

$error = show_error_info(ERROR_PRE_COMMON, Error_Code::ERROR_SYSTEM, Error_Code::desc(Error_Code::ERROR_SYSTEM));
echo json_encode($error);
exit;

