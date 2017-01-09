<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH . 'libraries/FRONT_Controller.php';
abstract class Base extends FRONT_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->helper('common');
    }

    protected function getHostName(){
        return $this->setHostName();
    }

    protected function getAllowedApiList(){
        return $this->setAllowedApiList();
    }

    abstract function setHostName();
    abstract function setAllowedApiList();

    protected function xmlpipe2_doc_header(){
		return '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL .
		       '<sphinx:docset>' . PHP_EOL;
	}

	protected function xmlpipe2_doc_footer(){
		return '</sphinx:docset>' . PHP_EOL;
	}

}
