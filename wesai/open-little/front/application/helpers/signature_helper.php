<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('create_signature')) {
    function create_signature(Array $params, $algo = 'md5', $secretKey = '') {
        $signPar = '';
        ksort($params);
        reset($params);
        foreach ($params as $key => $value) {
            if ('' === $value || $key == 'sign_type') {
                continue;
            }
            $signPar .= $key . '=' . $value . '&';
        }

        switch (strtolower($algo)) {
            case 'md5':
                $sign = md5($signPar . $secretKey);
                break;
            case 'sha1':
                $sign = sha1($signPar . $secretKey);
                break;
        }

        return $sign;
    }
}


if (!function_exists('verify_signature')) {
    function verify_signature(Array $params, $algo = 'md5', $secretKey = '') {
        if (!array_key_exists('sign', $params)) {
            return false;
        }
        $sign = $params['sign'];
        unset($params['sign']);

        $verify_sign = create_signature($params, $algo, $secretKey);

        return (boolean) ($verify_sign === $sign);
    }
}

