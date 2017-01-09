<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once 'Error_Code.php';

require_once BASEPATH . 'libraries/FRONT_Controller.php';

abstract class Base extends FRONT_Controller
{
	protected $dealed     = 0;
	protected $dealed_max = 100000;

	public function __construct()
	{
		parent::__construct();
		if (!is_cli()) {
			show_404();
		}
	}

	protected function dealStart($dealed_max = 100000)
	{
		$this->dealed     = 0;
		$this->dealed_max = $dealed_max;
	}

	protected function dealing()
	{
		$this->dealed++;
	}

	protected function dealEnd()
	{
		if ($this->dealed > $this->dealed_max) {
			exit();
		}
	}

	protected function mail_error($msg)
	{
		$this->load->library('email');
		$mail_error_set = $this->config->item('mail_error_set');
		$this->email->initialize($mail_error_set['config']);
		$this->email->from($mail_error_set['from']);
		$this->email->to($mail_error_set['to']);

		$this->email->subject($msg . ' ' . date('Y-m-d H:i:s'));
		$this->email->message($msg);

		$this->email->send();
		echo $this->email->print_debugger();
	}

	protected function catchException(Exception $e)
	{
		$errMsg = array(
			'msg'     => 'Exception occurred',
			'e_file'  => $e->getFile(),
			'e_line'  => $e->getLine(),
			'e_msg'   => $e->getMessage(),
			'e_trace' => $e->getTrace()[0],
		);
		log_message_v2('error', $errMsg);
	}

	public function obj2array($data)
	{
		return json_decode(json_encode($data), true);
	}

}
