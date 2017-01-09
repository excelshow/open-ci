<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Error_Code.php';

class Base extends MY_Controller
{
    protected $timestamp;
    public function __construct(){
        parent::__construct();
        $this->timestamp = time();
    }






}
