<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('hasPermission'))
{
	function hasPermission($UserID,$Page,$Function){
		$CI =& get_instance();
		$CI->load->model('UserModel');
		$p=$CI->UserModel->getAccessPermissions($UserID,$Page,$Function);
		if($p)
			return true;
		else
			return false;			
	}
}