<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/ContestBase.php';

class Res extends ContestBase
{
	const  UPLOAD_ERR_OK         = 0;
	const  UPLOAD_ERR_INI_SIZE   = 1;
	const  UPLOAD_ERR_FORM_SIZE  = 2;
	const  UPLOAD_ERR_PARTIAL    = 3;
	const  UPLOAD_ERR_NO_FILE    = 4;
	const  UPLOAD_ERR_NO_TMP_DIR = 6;
	const  UPLOAD_ERR_CANT_WRITE = 7;

	public function getAllowedApiList()
	{
		return array(
			

		);
	}
	private $_FILE_UPLOAD_ERROR = array(
		self::UPLOAD_ERR_OK         => '上传成功',
		self::UPLOAD_ERR_INI_SIZE   => '文件太大,超过限定制4M',
		self::UPLOAD_ERR_FORM_SIZE  => '文件太大,超过限定制4M',
		self::UPLOAD_ERR_PARTIAL    => '文件上传未完成',
		self::UPLOAD_ERR_NO_FILE    => '文件没有被上传',
		self::UPLOAD_ERR_NO_TMP_DIR => '文件上传失败',
		self::UPLOAD_ERR_CANT_WRITE => '文件上传失败',
	);

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contest/Res_model');
	}

	public function map()
	{
		$this->verifyLogin();//用户验证
		$key         = "608d75903d29ad471362f8c58c550daf";
		$data['key'] = $key;
		$this->display($data);
	}

	public function form()
	{
		$file = $_FILES;
		$this->display($file);
	}

	//上传图片（到云服务器）
	public function pre_upload()
	{
		$std        = new stdClass();
		$std->error = 0;

		if (empty($_FILES)) {
			$std->error = -1;
			$std->msg   = 'empty $_FILES';
			exit(json_encode($std));
		}

		$filename = array_keys($_FILES)[0];

		$fileUploadErrorNo = $_FILES[$filename]['error'];
		if (!empty($fileUploadErrorNo)) {
			$std->error = -2;
			$std->msg   = 'error: ' . !empty($this->_FILE_UPLOAD_ERROR[$fileUploadErrorNo]) ? $this->_FILE_UPLOAD_ERROR[$fileUploadErrorNo] : '';
			exit(json_encode($std));
		}

		$file   = $_FILES[$filename]['tmp_name'];
		$mime   = mime_content_type($file);
		$size   = filesize($file);
		$fname  = '';
		$fmd5   = md5_file($file);
		$utype  = 1;
		$uid    = 1;
		$result = $this->Res_model->preUpload($mime, $size, $fname, $fmd5, $utype, $uid);
		if (empty($result) || $result->error != 0 || empty($result->result)) {
			$std->error = -3;
			$std->msg   = 'pre_upload failed';
			if (isset($result->error)) {
				$std->errorno = $result->error;
			}
			exit(json_encode($std));
		}

		if (empty($result->result->token)) {
			$std->fileid = $result->result->fileid;
			exit(json_encode($std));
		}

		$token  = $result->result->token;
		$fileid = $result->result->fileid;
		$rs     = $this->Res_model->doUpload($fileid, $token, $mime, $file);
		if (empty($rs) || $rs->error != 0) {
			$std->error = -4;
			$std->msg   = 'do_upload failed';
			if (isset($rs->error)) {
				$std->errorno = $rs->error;
			}
			exit(json_encode($std));
		}

		unlink($file);
		$std->fileid = $result->result->fileid;
		exit(json_encode($std));
	}

	//编辑器上传图片
	public function editorupload()
	{
		$std        = new stdClass();
		$std->error = 0;

		if (empty($_FILES)) {
			$std->error = -1;
			$std->msg   = 'empty $_FILES';
			exit(json_encode($std));
		}

		$filename = array_keys($_FILES)[0];

		$fileUploadErrorNo = $_FILES[$filename]['error'];
		if (!empty($fileUploadErrorNo)) {
			$std->error = -2;
			$std->msg   = 'error: ' . !empty($this->_FILE_UPLOAD_ERROR[$fileUploadErrorNo]) ? $this->_FILE_UPLOAD_ERROR[$fileUploadErrorNo] : '';
			exit(json_encode($std));
		}

		$file   = $_FILES[$filename]['tmp_name'];
		$mime   = mime_content_type($file);
		$size   = filesize($file);
		$fname  = '';
		$fmd5   = md5_file($file);
		$utype  = 1;
		$uid    = 1;
		$result = $this->Res_model->preUpload($mime, $size, $fname, $fmd5, $utype, $uid);
		if (empty($result) || $result->error != 0 || empty($result->result)) {
			$std->error = -3;
			$std->msg   = 'pre_upload failed';
			if (isset($result->error)) {
				$std->errorno = $result->error;
			}
			exit(json_encode($std));
		}

		if (empty($result->result->token)) {
			$std->fileid = $result->result->fileid;
			$fitleInfo   = array(
				"originalName" => $file,
				"name"         => $fname,
				"url"          => $std->fileid,
				"size"         => $size,
				"type"         => $mime,
				"state"        => "SUCCESS",
			);
			exit(json_encode($fitleInfo));
		}

		$token  = $result->result->token;
		$fileid = $result->result->fileid;
		$rs     = $this->Res_model->doUpload($fileid, $token, $mime, $file);
		if (empty($rs) || $rs->error != 0) {
			$std->error = -4;
			$std->msg   = 'do_upload failed';
			if (isset($rs->error)) {
				$std->errorno = $rs->error;
			}
			exit(json_encode($std));
		}
		unlink($file);
		$std->fileid = $result->result->fileid;
		// exit(json_encode($std));
		$fitleInfo = array(
			"originalName" => $file,
			"name"         => $fname,
			"url"          => $std->fileid,
			"size"         => $size,
			"type"         => $mime,
			"state"        => "SUCCESS",
		);
		exit(json_encode($fitleInfo));
	}

	public function check_file_state()
	{
		$std        = new stdClass();
		$std->error = 0;
		$fileid     = $this->input->get('fileid', true);

		if (empty($fileid)) {
			$std->error = -1;
			$std->msg   = 'illegal parameters';
			exit(json_encode($std));
		}

		$rs = $this->Res_model->getFileById($fileid);

		if (empty($rs) || $rs->error != 0 || empty($rs->result)) {
			$std->error = -2;
			$std->ecode = !empty($rs->error) ? $rs->error : 0;
			$std->msg   = 'get file by id failed';
			exit(json_encode($std));
		}

		$std->state = $rs->result->state;
		exit(json_encode($std));
	}

	/**
	 * 倒入excel文件
	 */
	public function excel_reader_codelist()
	{
		set_time_limit(0);
		error_reporting(E_ALL);
		date_default_timezone_set('Asia/ShangHai');
		$this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
		//读取excel
		//$uploadfile= APP_PATH.'/assets/excel/example1.xls';//获取上传成功的Excel
		$std           = new stdClass();
		$std->error    = 0;
		$std->data     = array();
		$allowed_types = array('xls', 'xlsx', 'xl');
		if (empty($_FILES)) {
			$std->error = -1;
			$std->msg   = '请上传文件';
			exit(json_encode($std));
		}
		$filename = $_FILES["codefile"]["name"];
		$x        = explode('.', $filename);
		$ext      = strtolower(end($x));
		if (!in_array($ext, $allowed_types, true)) {
			$std->error = -2;
			$std->msg   = '文件格式不对';
			exit(json_encode($std));
		}
		$uploadfile    = $_FILES["codefile"]["tmp_name"];
		$objReader     = IOFactory::createReader('Excel5');//use excel2007 for 2007 format
		$objPHPExcel   = $objReader->load($uploadfile);//加载目标Excel
		$sheet         = $objPHPExcel->getSheet(0);//读取第一个sheet
		$highestRow    = $sheet->getHighestRow(); // 取得总行数
		$highestColumn = $sheet->getHighestColumn(); // 取得总列数
		$succ_result   = $error_result = 0;//设置导入成功和失败的总数为0
		$strExcel      = array();
		for ($j = 1; $j <= $highestRow; $j++) {
			$k = 'A';//1列
			//读取单元格
			$cellValue = trim($objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue());
			if ($cellValue != "") {
				array_push($strExcel, $cellValue);
			}
		}
		array_unique($strExcel);
		$std->error = 0;
		$std->msg   = '上传成功';
		$std->data  = array("count" => count($strExcel), "data" => implode(",", $strExcel));
		exit(json_encode($std));
	}
}
