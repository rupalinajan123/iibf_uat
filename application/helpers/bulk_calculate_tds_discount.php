<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pooja calculate tDS and Discount genarate code
 
function calculate_tds($amt_before_tds,$tds)
{
	
	$tds_amount =$amt_before_tds * $tds / 100;
    $amt_after_tds = $amt_before_tds - $tds_amount;
	
	  return  $amt_after_tds;
}
function calculate_discount($amt_before_discount,$discount)
{
	
	$discount_amount =$amt_before_discount * $tds / 100;
    $amt_after_discount = $amt_before_discount - $discount_amount;
	
	  return  $amt_after_discount;
}



	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */