<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/Error_Code.php';
require_once APPPATH . '/helpers/error_helper.php';

$error = show_error_info(ERROR_PRE_COMMON, Error_Code::ERROR_CONTROLLER_NOT_EXISTS, Error_Code::desc(Error_Code::ERROR_CONTROLLER_NOT_EXISTS));
echo json_encode($error);
exit;

