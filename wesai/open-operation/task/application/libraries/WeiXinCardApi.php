<?php

class WeiXinCardApi
{
    const UPLOAD_IMAGE_URL       = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='; //上传图片
    const CREATE_CARD_URL        = 'https://api.weixin.qq.com/card/create?access_token=';  //新增卡券
    const UPDATE_CARD_URL        = 'https://api.weixin.qq.com/card/update?access_token=';  //更新卡券
    const UPDATE_MEMBER_CARD_URL = 'https://api.weixin.qq.com/card/membercard/updateuser?access_token='; //更新会员信息

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
    public function uploadImage($access_token, $image)
    {
        $url = self::UPLOAD_IMAGE_URL . $access_token;

        $data['buffer'] = curl_file_create($image);

        $result = $this->httpsRequest($url, $data);
        if (empty($result->error)) {
            @unlink($image);
        }

        $result =  json_decode($result, true);
        if (empty($result)) {
            return false;
        }

        if (!empty($result['errcode'])) {
            log_message_v2('error', 'upload image to  failed | ' . json_encode($result));
            return false;
        }

        return $result['url'];
    }

    /**
     * 新增卡券信息
     *
     * @param $access_token
     * @param $data  注意转json串时需要添加第二个参数：json_encode($jsonData, JSON_UNESCAPED_UNICODE);
     *
     * @return mixed
     * ps.测试结果
     * {"errcode":0,"errmsg":"ok","card_id":"paffMwZ0jDlYKi4AxVEQcrFjdQxo"}
     */
    public function createCard($access_token, $data)
    {
        $url = self::CREATE_CARD_URL . $access_token;

        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        var_dump($json_data);

        $result = $this->httpsRequest($url, $json_data);

        $result = json_decode($result, true);
        if (empty($result)) {
            return false;
        }

        if (!empty($result['errcode'])) {
            log_message_v2('error', 'create weixin card failed | ' . json_encode($result));
            return false;
        }

        return $result['card_id'];
    }

    /**
     * 更新卡券信息
     *
     * @param $access_token
     * @param $data
     *
     * @return mixed
     */
    public function updateCard($access_token, $data)
    {
        $url = self::UPDATE_CARD_URL . $access_token;

        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        var_dump($json_data);
        $result = $this->httpsRequest($url, $json_data);
var_dump($result);die;
        $result =  json_decode($result, true);
        if (empty($result)) {
            return false;
        }

        if (!empty($result['errcode'])) {
            log_message_v2('error', 'update weixin card failed | ' . json_encode($result));
            return false;
        }

        return $result;
    }

    /**
     * 更新会员信息
     *
     * @param $access_token
     * @param $data
     *
     * @return mixed
     */
    public function updatedMember($access_token, $data)
    {
        $url = self::UPDATE_MEMBER_CARD_URL . $access_token;

        $result = $this->httpsRequest($url, json_encode($data, JSON_UNESCAPED_UNICODE));

        return json_decode($result, true);
    }

    private function httpsRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
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
