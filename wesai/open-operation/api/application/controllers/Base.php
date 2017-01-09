<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Error_Code.php';

class Base extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function checkPageParams(&$pageNumber, &$pageSize, $maxSize = 100)
    {
        $pageNumber = intval($pageNumber);
        $pageSize   = intval($pageSize);
        $pageNumber >= 0 or $pageNumber = 1;
        $pageSize > 0 or $pageSize = 20;
        $pageSize < $maxSize or $pageSize = $maxSize;
    }
}
