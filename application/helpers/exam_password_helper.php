<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */

	function random_password($length = 6)
	{
		//$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789";
		$chars = "ABCDEFGHJKMNPQRTUVWXYZ2346789";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}
	
	
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/checkactiveexam_helper.php */