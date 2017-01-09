<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('create_rand_number'))
{
    function create_rand_number($length)
    {  
        $randstr = '';  
        for ($i=0; $i<$length; $i++)  
        {  
            $randstr .= chr(mt_rand(48, 57));  
        }  
        return $randstr;  
    }
}

if ( ! function_exists('create_rand_str'))
{
    function create_rand_str($length)
    {  
        $randstr = '';  
        for ($i=0; $i<$length; $i++)  
        {  
            $randstr .= chr(mt_rand(97, 122));  
        }  
        return $randstr;  
    }
}

if ( ! function_exists('is_weixin_client'))
{
    function is_weixin_client()
    {
        $useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
        if (strpos($useragent, 'MicroMessenger') === false && 
            strpos($useragent, 'Windows Phone') === false) {

            return false;
        }

        return true;
    }
}
