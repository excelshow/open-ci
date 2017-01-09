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

if (! function_exists('admin_is_signed_in')) {
    function admin_is_signed_in()
    {
        $CI =& get_instance();
        return $CI->session->userdata('userid') ? true : false;
    }
}


if ( ! function_exists('admin_sign_in'))
{       
        function admin_sign_in($userid, $openid)
        {
                $userdata = array();
                $userdata['userid'] = $userid;
                $userdata['openid'] = $openid;
                $userdata['sign_in_time'] = time();
                                                                                
                $CI =& get_instance();                                          
                $CI->session->set_userdata($userdata);                          
        
                return true;
        }   
}

if ( ! function_exists('admin_sign_out'))
{
        function admin_sign_out()
        {
                $userdata = array();
                $userdata['userid'] = '';
                $userdata['openid'] = '';
                $userdata['sign_out_time'] = 0;

                $CI =& get_instance();
                $CI->session->unset_userdata($userdata);

                return true;
        }
}


/**
 * 获取添加页面和修改页面选择时间格式
 */
if ( ! function_exists('get_select_start_date'))
{
    function get_select_start_date() {
        $select_date = array();
        for ($i = 0; $i < 24; $i++) {
            $date = $i . ':00';
            if ($i < 10) {
                $date = 0 . $i . ':00';
            }

            $select_date[] = array(
                'name' => $date,
                'value' => $date
            );
        }

        return $select_date;
    }
}

if ( ! function_exists('get_select_end_date'))
{
    function get_select_end_date() {
        $select_date = array();
        for ($i = 1; $i < 25; $i++) {
            $date = $i . ':00';
            if ($i < 10) {
                $date = 0 . $i . ':00';
            }
            if ($i == 24) {
                $date = ($i-1) . ':59';
            }

            $select_date[] = array(
                'name' => $date,
                'value' => $date
            );
        }

        return $select_date;
    }
}



