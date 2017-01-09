<?php

/**
 * User: liangkaixuan
 * Date: 16/12/29
 */
class WeChatApi
{
	const UPLOADIMG_URL     = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='; //上传图片
	const CREATE_URL        = 'https://api.weixin.qq.com/card/create?access_token=';  //新增卡券
	const UPDATE_URL        = 'https://api.weixin.qq.com/card/update?access_token=';  //更新卡券
	const UPDATE_MEMBER_URL = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token='; //更新会员信息
	/**
	 * 上传图片
	 *
	 * @param        $access_token
	 * @param string $image
	 *
	 * @return mixed
	 * ps.测试结果
	 * {"url": "http://mmbiz.qpic.cn/mmbiz_jpg/URbw3wcC1A4t0sRicnjnBGicrz3bAiatn1LUtdEQNI0Fz345MJhblClZwryem4t9EtYX7wUUUibflRa57EsIXLxMiag/0"}
	 */
	public function uploadImg($access_token, $image)
	{
		$url = self::UPLOADIMG_URL . $access_token;
		//微信给出的方法是@+文件的路径来赋予数组来上传 测试后 "errcode":41005,"errmsg":"media data missing"
		//$data['buffer'] = "@" . $image;
		$data['buffer'] = curl_file_create($image);
		$result = $this->httpsRequest($url, $data);
		if(empty($result->error)){
			@unlink ($image);
		}
		return $result;
	}

	/**
	 * 新增卡券信息
	 *
	 * @param $access_token
	 * @param $jsonData  注意转json串时需要添加第二个参数：json_encode($jsonData, JSON_UNESCAPED_UNICODE);
	 *
	 * @return mixed
	 * ps.测试结果
	 * {"errcode":0,"errmsg":"ok","card_id":"paffMwZ0jDlYKi4AxVEQcrFjdQxo"}
	 */
	public function createdCard($access_token, $jsonData)
	{
		$url    = self::CREATE_URL . $access_token;
		$result = $this->httpsRequest($url,$jsonData);
		return $result;
	}

	/**
	 * 更新卡券信息
	 * @param $access_token
	 * @param $jsonData
	 * @return mixed
	 */
	public function updatedCard($access_token, $jsonData)
	{
		$url    = self::UPDATE_URL . $access_token;
		$result = $this->httpsRequest($url,$jsonData);
		return $result;
	}

	/**
	 * 更新会员信息
	 * @param $access_token
	 * @param $jsonData
	 * @return mixed
	 */
	public function updatedMember($access_token, $jsonData)
	{
		$url    = self::UPDATE_MEMBER_URL . $access_token;
		$result = $this->httpsRequest($url,$jsonData);
		return $result;
	}

	private function httpsRequest($url,$data = null){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		if (!empty($data)) {
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

}
