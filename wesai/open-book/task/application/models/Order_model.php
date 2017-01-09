<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Order_model 
 *  订单信息
 */
class Order_model
{
	public function getOrder(){
		return empty($_SESSION[$_SESSION['appId']]) ? array() : $_SESSION[$_SESSION['appId']];
	}

}
