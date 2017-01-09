<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model {
    
    public function getUserInfo(){
        if(!empty($_SESSION['userInfo'])){
            return json_decode($_SESSION['userInfo']);
        }
        return false;
    }

    public function setUserInfo($userInfo){
		$_SESSION['userInfo'] = json_encode($userInfo);
    }

}
