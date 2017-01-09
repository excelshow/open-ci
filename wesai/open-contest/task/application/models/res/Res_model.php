<?php
/**
 * User: zhaodc
 * Date: 16/6/28
 * Time: 下午6:50
 */
require_once __DIR__ . '/../ModelBase.php';

class Res_model extends ModelBase
{
	public function __construct()
	{
		parent::__construct();
	}

	public function preUpload($file_path, $utype, $uid)
	{
		$mime = mime_content_type($file_path);
		$size = filesize($file_path);
		$fmd5 = md5_file($file_path);

		$params = compact('mime', 'size', 'fmd5', 'utype', 'uid');

		$requests[] = $this->request('api_host_open_res', 'res/pre_upload.json', $params, 'GET');

		$result = $this->result($requests);

		return $this->checkInternalApiResult($result, $requests);
	}

	public function doUpload($token, $fileid, $file, $mime)
	{
		$upfile = curl_file_create($file, $mime, 'file_upload_' . time());

		$params = compact('token', 'fileid', 'upfile');

		$this->load->library('curl');

		$url = 'http://' . array_keys($this->config->item('api_host_open_res'))[0] . '/res/do_upload.json';

		$result = json_decode($this->curl->post($params, $url));

		return $this->checkInternalApiResult($result, compact('url', 'params'));
	}
}
