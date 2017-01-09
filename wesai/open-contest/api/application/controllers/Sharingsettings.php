<?php
/**
 * Created by PhpStorm.
 * User: 123
 * Date: 2016/11/16
 * Time: 11:51
 */

require_once __DIR__ . '/Base.php';

class Sharingsettings extends Base{

    public function __construct()
    {
        parent::__construct();
    }

    public function add_post(){

        $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('title', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('intro', PARAM_NOT_NULL_NOT_EMPTY);
        $this->post_check('image', PARAM_NULL_NOT_EMPTY);
        $this->post_check('timeline_title', PARAM_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $this->verifyContestExists($params['cid']);

        $SharingSettingsInfo = $this->SharingSettings_model->getByCid($params['cid']);

        if(!empty($SharingSettingsInfo)){
            return $this->response_error(Error_Code::ERROR_SHARING_SETTINGS_ALREADY_EXISTS);
        }

        $params['fk_contest'] = $params['cid'];
        unset($params['cid']);

        $SharingSettingsId = $this->SharingSettings_model->create($params);

        return $this->response_insert($SharingSettingsId);

    }

    public function get_by_cid_get()
    {
        $contestId = $this->get_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);

        $this->verifyContestExists($contestId);

        $SharingSettingsInfo = $this->SharingSettings_model->getByCid($contestId);

        if(empty($SharingSettingsInfo)){
            return $this->response_error(Error_Code::ERROR_SHARING_SETTINGS_NOT_EXISTS);
        }

        return $this->response_object($SharingSettingsInfo);
    }


    public function update_post(){

        $this->post_check('cid', PARAM_NOT_NULL_NOT_EMPTY, PARAM_TYPE_INT);
        $this->post_check('title', PARAM_NULL_NOT_EMPTY);
        $this->post_check('intro', PARAM_NULL_NOT_EMPTY);
        $this->post_check('image', PARAM_NULL_NOT_EMPTY);
        $this->post_check('timeline_title', PARAM_NULL_NOT_EMPTY);

        $params = $this->get_request_params();

        $cid = $params['cid'];
        unset($params['cid']);

        $this->verifyContestExists($cid);

        $SharingSettingsInfo = $this->SharingSettings_model->getByCid($cid);

        if(empty($SharingSettingsInfo)){
            return $this->response_error(Error_Code::ERROR_SHARING_SETTINGS_NOT_EXISTS);
        }

        $affectedRows = $this->SharingSettings_model->modify($cid, $params);

        return $this->response_update($affectedRows);

    }




}