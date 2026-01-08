<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function examamtinword($amt){
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

function genarate_exam_recovery_invoice($invoice_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = examamtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = examamtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770){
		$exam_code =177;
	}
	elseif($invoice_info[0]['exam_code'] == 590){
		$exam_code =59;
	}
	elseif($invoice_info[0]['exam_code'] == 810){
		$exam_code =81;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	$exam_period = $invoice_info[0]['exam_period'];
	
	
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
	imagestring($im, 5, 670, 220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Mmeber no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  440, "GSTIN / Unique ID: NA", $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN No: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 690,  740, "-", $black);
		
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  740, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, "-", $black);
		imagestring($im, 3, 690,  720, "-", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		
		imagestring($im, 3, 900,  700, "-", $black);
		imagestring($im, 3, 900,  720, "-", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/examinvoice/user/";
	
	
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	
	/*********************** Image for supplier *************************************/
	
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
	imagestring($im, 5, 670, 220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Mmeber name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  440, "GSTIN / Unique ID: NA", $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN No: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 690,  740, "-", $black);
		
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  740, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 118,  700, "CGST", $black);
		imagestring($im, 3, 118,  720, "SGST", $black);
		imagestring($im, 3, 118,  740, "IGST", $black);
		
		imagestring($im, 3, 690,  700, "-", $black);
		imagestring($im, 3, 690,  720, "-", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		
		imagestring($im, 3, 900,  700, "-", $black);
		imagestring($im, 3, 900,  720, "-", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/examinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/examinvoice/user/".$imagename;
}
###-----generate exam invoice number---### 
function generate_elearning_recovery_invoice_number($invoice_id= NULL)
{
	$last_id='';
	$CI = & get_instance();
	//$CI->load->model('my_model');
	if($invoice_id  !=NULL)
	{
		$insert_info = array('invoice_id'=>$invoice_id);
		$last_id = str_pad($CI->master_model->insertRecord('config_dbf_elearning_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
	}
	return $last_id;
}
 
	###-----generate exam invoice number---### 
 	function generate_exam_recovery_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	
	
	###-----generate exam invoice number jammu---###
 	function generate_exam_recovery_invoice_number_jammu($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice_jammu',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	###-----generate exam invoice number---###
	