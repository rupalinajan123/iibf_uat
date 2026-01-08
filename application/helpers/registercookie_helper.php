<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * AdminCMS
 * @package   AdminCMS
 * @author    Yunus Shaikh {contributed}
 * @since     Version 1.0
 */
/**
 * Files upload helper functions.
 *
 * Includes additional file upload functions
 *
 */
 $obj =& get_instance();
 $obj->load->helper('cookie');
 function register_set_cookie($reg_id=NULL)
 {
	 if($reg_id!=NULL)
	 {
		$cookie = array(
        'name'   => 'regid',
        'value'  => $reg_id,
        'expire' => time()+86500,
        );
		set_cookie($cookie,true);
	 }
	

 }


 function register_get_cookie()
 {
		$val=get_cookie('regid');
	
		if($val)
		{
			return $val;
		}	
		else
		{
			return false;
		}
	}



 function duplicateid_set_cookie($did=NULL)
 {
	 if($did!=NULL)
	 {
		$cookie = array(
        'name'   => 'did',
        'value'  => $did,
        'expire' => time()+86500,
        );
		set_cookie($cookie,true);
	 }
	

 }


 function duplicateid_get_cookie()
 {
		$val=get_cookie('did');
		if($val)
		{
			return $val;
		}	
		else
		{
			return false;
		}
	}
	
	
 function applyexam_set_cookie($examid=NULL)
 {
	 if($examid!=NULL)
	 {
		$cookie = array(
        'name'   => 'examid',
        'value'  => $examid,
        'expire' => time()+300,
        );
		set_cookie($cookie,true);
	 }
	

 }


 function  applyexam_get_cookie()
 {
		$val=get_cookie('examid');
		if($val)
		{
			return $val;
		}	
		else
		{
			return false;
		}
	}

