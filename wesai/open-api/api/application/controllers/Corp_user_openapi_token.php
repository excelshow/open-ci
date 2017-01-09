<?php
/**
 * User: like
 * Date: 16/8/04
 */
require_once __DIR__ . '/Base.php';

class Corp_user_openapi_token extends Base
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('corp_user_openapi_token_model');
	}

	public function set_access_token_post()
	{
		$fk_corp = $this->post_check('fk_corp', PARAM_NOT_NULL_NOT_EMPTY);
		$fk_corp_user = $this->post_check('fk_corp_user', PARAM_NOT_NULL_NOT_EMPTY);
		$fk_corp_user_openapi = $this->post_check('fk_corp_user_openapi', PARAM_NOT_NULL_NOT_EMPTY);
		$access_token = $this->post_check('access_token', PARAM_NOT_NULL_EMPTY);
		$access_time = $this->post_check('access_time', PARAM_NOT_NULL_EMPTY);
		$expires_in = $this->post_check('expires_in', PARAM_NOT_NULL_EMPTY);

		$affect_rows = $this->corp_user_openapi_token_model->set_openapi_access_token($fk_corp, $fk_corp_user, $fk_corp_user_openapi, $access_token, $access_time, $expires_in);

		return $this->response_update($affect_rows);
	}

	public function get_access_token_get()
	{
		$access_token = $this->get_check('access_token', PARAM_NOT_NULL_NOT_EMPTY);

		$result = $this->corp_user_openapi_token_model->get_access_token($access_token);

		return $this->response_object($result);
	}

}
