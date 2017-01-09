<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'controllers/Base.php';
/**
 * 运维服务监控接口
 *
 * @package default
 * @author: zhaodechang@wesai.com
 **/
class Ops extends Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function check_ops()
    {
        $startTime  = microtime(true);
        $std        = new stdClass();
        $std->error = 0;
        $std->cost  = microtime(true) - $startTime;

        exit(json_encode($std));
    }
}
