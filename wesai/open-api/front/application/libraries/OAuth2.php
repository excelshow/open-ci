<?php
/**
 * Oauth2 
 *  
 */
class OAuth2 {
    public function getSecretSign($time, $secret){
        $time_secret = $time . '-' . $secret;
        $time_secret_md5 = md5($time_secret);
        return $time_secret_md5;
    }

    public function encodeBasicToken($client_id, $time, $secret){
        $time_secret_md5 = $this->getSecretSign($time, $secret);
        $token = $client_id . '-' . $time . '-' . $time_secret_md5;
        return base64_encode($token);
    }

    public function decodeBasicToken($authorization){
        $token_array = explode(' ', $authorization);
        if(isset($token_array[1])){
            $token = $token_array[1];
        }else{
            return false;
        }
        $token = base64_decode($token);
        $token_array = explode('-', $token);
        $auth = array(
            'client_id' => '',
            'time' => '',
            'sign' => ''
        );
        if(isset($token_array[0]) && !empty($token_array[0])){
            $auth['client_id'] = $token_array[0];
        }else{
            return false;
        }
        if(isset($token_array[1]) && !empty($token_array[1])){
            $auth['time'] = $token_array[1];
        }else{
            return false;
        }
        if(isset($token_array[2]) && !empty($token_array[2])){
            $auth['sign'] = $token_array[2];
        }else{
            return false;
        }

        return $auth;
    }

    public function makeBasicToken($client_id, $secret){
        return $this->encodeBasicToken($client_id, time(), $secret);
    }

    /**
     * makeAccessToken
     * 
     * @param mixed $client_id 
     * @param mixed $secret 
     * @access public
     * @return void
     */
    public function makeAccessToken($client_id, $secret){
        $time = time();
        $time_secret_md5 = $this->getSecretSign($time, $secret);
        $token = $time . '-' . $time_secret_md5;
        return base64_encode($token);
    }
}
