<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2016/11/16
 * Time: 12:03
 */

require_once __DIR__ . '/ModelBase.php';

class SharingSettings_model extends ModelBase{

    public function __construct()
    {
        parent::__construct();
    }


    public function create($SharingSettingsData){

        return $this->setTable($this->tableNameSharingSettings)
                    ->addInsertColumns($SharingSettingsData, ['utime'])
                    ->doInsert();

    }

    public function getByCid($fk_contest){

        $result = $this->setTable($this->tableNameSharingSettings)
                       ->addQueryConditions('fk_contest', $fk_contest)
                       ->doSelect(1, 1);

        if (empty($result)) {
            return false;
        }

        return $result[0];

    }


    public function modify($cid, $params)
    {
        $columns = array();
        foreach ($params as $key => $val) {
            $columns[] = array($key, $val, '=');
        }

        $affectedRows = $this->setTable($this->tableNameSharingSettings)
                             ->addUpdateColumns($columns)
                             ->addQueryConditions('fk_contest', $cid)
                             ->doUpdate();

        return $affectedRows;

    }

}