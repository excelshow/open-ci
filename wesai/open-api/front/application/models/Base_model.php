<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once BASEPATH . '/libraries/DIY_Model.php';

class Base_model extends DIY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
    
    /**
     * __call
     * 
     * @param mixed $name 内部调用api
     * @param mixed $arguments 0-参数 1-host_config 2-GET/POST
     * @access public
     * @return void
     */
    public function __call($name, $arguments){
        $api = $name;
        $params = $arguments[0];
        $host_config = $arguments[1];
        $request_method = $arguments[2];

		$requests   = array();
		$requests[] = $this->request($host_config, $api, $params, $request_method);

		return $this->result($requests);
    }

}

