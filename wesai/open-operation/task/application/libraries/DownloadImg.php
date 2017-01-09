<?php

/**
 * User: liangkaixuan
 * Date: 16/12/29
 */
class DownloadImg
{
	const UPLOAD = '/home/local/code/open-operation/task/';
	public function __construct()
	{

	}

	public function downloadLocal($image)
	{
		if (!file_exists(self::UPLOAD)) {
			return $this->displayError('路径不存在');
		}
		$img = file_get_contents($image);
		if (empty($img)) {
			return $this->displayError('文件不存在');
		}
		$name = $this->captureName($image);
		$is_down_ok = @file_put_contents(self::UPLOAD.$name,$img);
		if (!$is_down_ok) {
			return $this->displayError('保存失败');
		}
		return $name;
	}

	private function captureName($image)
	{
		$type = substr($image, strrpos($image, '.'));
		$name = time().rand(1,100);
		return $name.$type;
	}

	protected function displayError($info,$code = -1)
	{
		$result = array('error' => $code, 'info' => $info);
		return json_encode($result);
	}


}
