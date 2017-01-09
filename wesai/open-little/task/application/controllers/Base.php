<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . 'libraries/FRONT_Controller.php';

abstract class Base extends FRONT_Controller
{
    protected $dealed     = 0;
    protected $dealed_max = 100000;
    public function __construct()
    {
        parent::__construct();
    }

    protected function dealStart($dealed_max = 100000)
    {
        $this->dealed     = 0;
        $this->dealed_max = $dealed_max;
    }

    protected function dealing()
    {
        $this->dealed++;
    }

    protected function dealEnd()
    {
        if ($this->dealed > $this->dealed_max) {
            exit();
        }
    }

    protected function errorInfo($info, $code = -1)
    {
        return array('error' => $code, 'info' => $info);
    }


    protected function getHostName(){
        return $this->setHostName();
    }

    protected function getAllowedApiList(){
        return $this->setAllowedApiList();
    }

}
