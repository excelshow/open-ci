<?php

require_once BASEPATH . 'libraries/ORM_Model.php';

class MY_Model extends ORM_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->setDBConfig(CONTEST_DB_CONFIG);
    }
}
