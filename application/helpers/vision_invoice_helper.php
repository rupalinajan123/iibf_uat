<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function visamtinword($amt){
   $number = $amt;
   $no = round($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'One', '2' => 'Two',
    '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
    '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
    '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
    '13' => 'Thirteen', '14' => 'Fourteen',
    '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
    '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
    '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
    '60' => 'Sixty', '70' => 'Seventy',
    '80' => 'Eighty', '90' => 'Ninety');
   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
   while ($i < $digits_1) {
     $divider = ($i == 2) ? 10 : 100;
     $number = floor($no % $divider);
     $no = floor($no / $divider);
     $i += ($divider == 10) ? 1 : 2;
     if ($number) {
        $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
        $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
        $str [] = ($number < 21) ? $words[$number] .
            " " . $digits[$counter] . $plural . " " . $hundred
            :
            $words[floor($number / 10) * 10]
            . " " . $words[$number % 10] . " "
            . $digits[$counter] . $plural . " " . $hundred;
     } else $str[] = null;
  }
  $str = array_reverse($str);
  $result = implode('', $str);
  $points = ($point) ?
    "." . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  //echo $result . "Rupees  " . $points . " Paise";
  return $result;
}


function log_vision_invoice($log_title, $log_message = "",$invoice_id='',$vision_id='')
{
    $CI = & get_instance();
	$rId = $invoice_id;
	$regNo = $vision_id;
    $CI->load->model('Log_model');
    $CI->Log_model->userActivity_log($log_title, $log_message, $rId, $regNo);
}

function genarate_vision_invoice($invoice_id,$vision_id){
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	$mem_info = $CI->master_model->getRecords('iibf_vision',array('vision_id'=>$vision_id),'fname,mname,lname,address_1,address_2,address_3,address_4');
	$member_name = $mem_info[0]['fname']." ".$mem_info[0]['mname']." ".$mem_info[0]['lname'];
	
	log_vision_invoice($log_title = "genarate_vision_invoice function call.", $log_message = serialize($invoice_id.'|'.$vision_id),$invoice_id = $invoice_id, $vision_id = $vision_id);
	
	$wordamt = visamtinword($invoice_info[0]['igst_total']);
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Address of Buyer (Billed to): ". $mem_info[0]['address_1'], $black);
	imagestring($im, 3, 40,  272, $mem_info[0]['address_2'], $black);
	imagestring($im, 3, 40,  283, $mem_info[0]['address_3'], $black);
	imagestring($im, 3, 40,  296, $mem_info[0]['address_4'], $black);
	
	
	imagestring($im, 3, 40,  316, "Name of the buyer: ".$member_name, $black);
	imagestring($im, 3, 40,  336, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  356, "State Code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  376, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 45,  600, "1", $black);
	imagestring($im, 3, 100,  600, "Journals - IIBF Vision", $black);
	imagestring($im, 3, 560,  600, $invoice_info[0]['service_code'], $black); // 590
	imagestring($im, 3, 700,  600, $invoice_info[0]['fee_amt'], $black); // 690
	imagestring($im, 3, 820,  600, "1", $black); // 780
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black); // 900
	imagestring($im, 3, 560,  830, "Total(Rs.)", $black);
	imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/vision_invoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $ino.".jpg";
	
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	imagepng($im,"uploads/vision_invoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/vision_invoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/vision_invoice/user/'.$imagename);
	imagedestroy($im);
	
	
	
	
	/****************** Image for supplier ************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  160, 980, 160, $black); // line-5
	imageline ($im,   20,  200, 980, 200, $black); // line-6
	imageline ($im,   20,  480, 980, 480, $black); // line-7
	imageline ($im,   20,  520, 980, 520, $black); // line-8
	imageline ($im,   20,  580, 980, 580, $black); // line-9
	imageline ($im,   20,  850, 980, 850, $black); // line-10
	imageline ($im,   650,  200, 650, 480, $black); // line-11
	imageline ($im,   85,  520, 85, 850, $black); // line-12
	imageline ($im,   500,  520, 500, 850, $black); // line-13
	imageline ($im,   650,  520, 650, 850, $black); // line-14
	imageline ($im,   785,  520, 785, 850, $black); // line-15
	imageline ($im,   860,  520, 860, 850, $black); // line-16
	imageline ($im,   40,  880, 625, 880, $black); // line-17
	
	
	
	//imagestring(image,font,x,y,string,color);
	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Address of Buyer (Billed to): ". $mem_info[0]['address_1'], $black);
	imagestring($im, 3, 40,  272, $mem_info[0]['address_2'], $black);
	imagestring($im, 3, 40,  283, $mem_info[0]['address_3'], $black);
	imagestring($im, 3, 40,  296, $mem_info[0]['address_4'], $black);
	
	imagestring($im, 3, 40,  316, "Name of the buyer: ".$member_name, $black);
	imagestring($im, 3, 40,  336, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  356, "State Code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  376, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	
	imagestring($im, 3, 45,  600, "1", $black);
	imagestring($im, 3, 100,  600, "Journals - IIBF Vision", $black);
	imagestring($im, 3, 560,  600, $invoice_info[0]['service_code'], $black); // 590
	imagestring($im, 3, 700,  600, $invoice_info[0]['fee_amt'], $black); // 690
	imagestring($im, 3, 820,  600, "1", $black); // 780
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black); // 900
	imagestring($im, 3, 560,  830, "Total(Rs.)", $black);
	imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/vision_invoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $ino.".jpg";
	imagepng($im,"uploads/vision_invoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/vision_invoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/vision_invoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/vision_invoice/user/".$imagename;
	
}

function generate_vision_invoice_number($invoice_id){
	$last_id='';
	$CI = & get_instance();
	//$CI->load->model('my_model');
	if($invoice_id  !=NULL)
	{
		$insert_info = array('invoice_id'=>$invoice_id);
		$last_id = str_pad($CI->master_model->insertRecord('config_vision_invoice',$insert_info,true), 5, "0", STR_PAD_LEFT);;
	}
	return $last_id;
}



	
/* Location: ./application/helpers/bankquest_invice_helper.php */