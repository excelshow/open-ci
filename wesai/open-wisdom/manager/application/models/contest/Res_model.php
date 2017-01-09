   <?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Res_model extends MY_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('mcurl');
	}

	public function getFileById($fileid)
	{
		$params     = compact('fileid');
		$requests[] = $this->request('api_host_open_res', 'res/get_by_fileid.json', $params, 'GET');

		return $this->result($requests);
	}

	public function preUpload($mime, $size, $fname, $fmd5, $utype, $uid)
	{
		$params     = compact('mime', 'size', 'fname', 'fmd5', 'utype', 'uid');
		$requests   = array();
		$requests[] = $this->request('api_host_open_res', 'res/pre_upload.json', $params, "GET");

		return $this->result($requests);
	}

	public function doUpload($fileid, $token, $mime, $filepath)
	{
		$data           = compact('fileid', 'token');
		$data['upfile'] = curl_file_create($filepath, $mime, 'file_upload_' . time());
		$config         = $this->config->item('api_host_open_res');
		$hostname       = array_keys($config)['0'];
		$url            = 'http://' . $config[$hostname][array_rand($config[$hostname])] . '/res/do_upload.json';
		try {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Host: ' . $hostname));
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 5);

			$response = curl_exec($ch);
			if (curl_errno($ch)) {
				//error
				log_message('error', __METHOD__ . "curl error :" . curl_error($ch) . PHP_EOL);
			}

			curl_close($ch);

			if ($response) {
				return json_decode($response);
			}

		} catch (Exception $e) {
			log_message('error', __METHOD__ . '|' . $e->getMessage());
		}
	}

}
