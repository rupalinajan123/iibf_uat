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
	
	$discount_amount =$amt_before_discount * $discount / 100;
    $amt_after_discount = $amt_before_discount - $discount_amount;
	
	  return  $amt_after_discount;
}
function calculate_gst_rate($amt_after_discount,$gst_rate)
{
	
/*	Formula 
    GST Amount = (Original Cost x GST%)/100
      Net Price = Original Cost + GST Amount*/
	$gst_amount =($amt_after_discount * $gst_rate) / 100;
 
	  return  $gst_amount ;
}
function calculate_gst($base_amt_after_dsct,$amt_after_discount)
{
	
/*	Formula 
    GST Amount = (Original Cost x GST%)/100
      Net Price = Original Cost + GST Amount*/

  //$amt_after_gst = $base_amt_after_dsct + $gst_amount_rate ;
  $amt_after_gst = $base_amt_after_dsct + $amt_after_discount ;
	  return $amt_after_gst ;
}



	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */