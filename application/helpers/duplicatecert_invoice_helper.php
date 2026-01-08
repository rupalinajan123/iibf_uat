<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function amtinworddupcert($amt){
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

function genarate_duplicatecert_invoice($invoice_no)
{
	//$invoice_no =   ;
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1 ,address2, address3, address4, city, state, pincode ');
	//if member is DRA
	if(empty($mem_info))
	{
		$mem_info = $CI->master_model->getRecords('dra_members',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	}
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	/* if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_duplicatecert_invoice_jk($invoice_no);
		exit;
	}*/
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinworddupcert($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinworddupcert($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	
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
	
	$year = date('Y');
	//imagestring($im, 5, 455,  30, "TAX INVOICE CUM RECEIPT", $black);

	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
	
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  240, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  260, "Name of service Recipient:".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  400, "Exam code : ".$invoice_info[0]['exam_code'],$black);

	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice'])), $black);
	if($invoice_info[0]['gstin_no'] != '' && $invoice_info[0]['gstin_no'] != 0){
		$gstn = $invoice_info[0]['gstin_no'];
	}else{
		$gstn = "-";
	}
	imagestring($im, 3, 670,  300, "GSTIN - 27AAATT3309D1ZS ", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	imagestring($im, 3, 118,  596, "Charges for Duplicate Certificate", $black);
	imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black); 
	imagestring($im, 3, 535,  820, "Total (Rs.)", $black);

	imagestring($im, 3, 40,  596, "1", $black);
	imagestring($im, 3, 118,  626,"" , $black);
	imagestring($im, 3, 118,  626, "CGST ", $black);
	imagestring($im, 3, 118,  646, "SGST ", $black);
	imagestring($im, 3, 118,  666, "IGST ", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $invoice_info[0]['igst_amt'], $black);
	}

	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}*/
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/dupcertinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/dupcertinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/dupcertinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/dupcertinvoice/user/'.$imagename);
	imagedestroy($im);
	
	/****************************** image for supplier ***********************************/
	
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
	
	$year = date('Y');
	//imagestring($im, 5, 455,  30, "TAX INVOICE CUM RECEIPT", $black);

	imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
	imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
	imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
	imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
	imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
	imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
	
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  240, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  260, "Name of service Recipient:".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  400, "Exam code : ".$invoice_info[0]['exam_code'],$black);

	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice'])), $black);
	if($invoice_info[0]['gstin_no'] != '' && $invoice_info[0]['gstin_no'] != 0){
		$gstn = $invoice_info[0]['gstin_no'];
	}else{
		$gstn = "-";
	}
	imagestring($im, 3, 670,  300, "GSTIN - 27AAATT3309D1ZS ", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	imagestring($im, 3, 118,  596, "Charges for Duplicate Certificate", $black);
	imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black); 
	imagestring($im, 3, 535,  820, "Total (Rs.)", $black);

	imagestring($im, 3, 40,  596, "1", $black);
	imagestring($im, 3, 118,  626,"" , $black);
	imagestring($im, 3, 118,  626, "CGST ", $black);
	imagestring($im, 3, 118,  646, "SGST ", $black);
	imagestring($im, 3, 118,  666, "IGST ", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $invoice_info[0]['igst_amt'], $black);
	}

	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}*/
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/dupcertinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/dupcertinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/dupcertinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/dupcertinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	
	return $attachpath = "uploads/dupcertinvoice/supplier/".$imagename;
}
/*	function genarate_duplicatecert_invoice_jk($invoice_no){
		
		$CI = & get_instance();
		$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
		$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
		//if member is DRA
		if(empty($mem_info))
		{
			$mem_info = $CI->master_model->getRecords('dra_members',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
		}
		$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
		$wordamt = amtinworddupcert($invoice_info[0]['igst_total']);
		$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
		
		// image for user /
		
		$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
		$background_color = imagecolorallocate($im, 255, 255, 255);   // white
		
		$black = imagecolorallocate($im, 0, 0, 0);                  // black
		
		imageline ($im,   20,  20, 980, 20, $black); // line-1
		imageline ($im,   20,  980, 980, 980, $black); // line-2
		imageline ($im,   20,  20, 20, 980, $black); // line-3
		imageline ($im,   980, 20, 980, 980, $black); // line-4
		imageline ($im,   20,  60, 980, 60, $black); // line-5
		imageline ($im,   20,  100, 980, 100, $black); // line-6
		imageline ($im,   20,  250, 980, 250, $black); // line-7
		imageline ($im,   20,  400, 980, 400, $black); // line-8
		imageline ($im,   20,  450, 980, 450, $black); // line-9
		imageline ($im,   20,  550, 980, 550, $black); // line-10
		imageline ($im,   20,  800, 980, 800, $black); // line-11
		imageline ($im,   490,  100, 490, 400, $black); // line-12 
		imageline ($im,   80,  450, 80, 800, $black); // line-13
		imageline ($im,   560,  450, 560, 800, $black); // line-14
		imageline ($im,   660,  450, 660, 800, $black); // line-15
		imageline ($im,   760,  450, 760, 800, $black); // line-16
		imageline ($im,   860,  450, 860, 800, $black); // line-17
		imageline ($im,   20,  835, 490, 835, $black); // line-18
		imageline ($im,   20,  770, 980, 770, $black); // line-19
		
		//imagestring ($im, size, X,  Y, text, $red);
		$year = date('Y');
		
		imagestring($im, 5, 455,  30, "Bill Of Supply - Services", $black);
		
		
		imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
		imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
		imagestring($im, 3, 22,  124, "Address: ", $black);
		imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
		imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
		imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
		imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
		imagestring($im, 3, 22,  184, "State Code : 27", $black);
		imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
		imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
		imagestring($im, 3, 22,  236, "Transaction No : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black); 
		imagestring($im, 3, 22,  262, "Name of the Recipient: ".$member_name, $black);
		imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['address1'], $black);
		imagestring($im, 3, 22,  286, $mem_info[0]['address2'], $black);
		imagestring($im, 3, 22,  298, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
		imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		imagestring($im, 3, 22,  346, "Exam code: ".$invoice_info[0]['exam_code'],$black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
		
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Charges for Duplicate Certificate", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		//imagestring($im, 3, 700,  900,   "echo<img src=Face.png>", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "% ---", $black);
		imagestring($im, 3, 350,  932, "Rs.---", $black);
		
		$savepath = base_url()."uploads/dupcertinvoice/user/";
		$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
		$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
		
		$update_data = array('invoice_image' => $imagename);
		$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
		
		imagepng($im,"uploads/dupcertinvoice/user/".$imagename);
		$png = @imagecreatefromjpeg('assets/images/sign.jpg');
		$jpeg = @imagecreatefromjpeg("uploads/dupcertinvoice/user/".$imagename);
		@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
		imagepng($im, 'uploads/dupcertinvoice/user/'.$imagename);
		
		imagedestroy($im);
		
		//image for supplier /
		
		$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
		$background_color = imagecolorallocate($im, 255, 255, 255);   // white
		
		$black = imagecolorallocate($im, 0, 0, 0);                  // black
		
		imageline ($im,   20,  20, 980, 20, $black); // line-1
		imageline ($im,   20,  980, 980, 980, $black); // line-2
		imageline ($im,   20,  20, 20, 980, $black); // line-3
		imageline ($im,   980, 20, 980, 980, $black); // line-4
		imageline ($im,   20,  60, 980, 60, $black); // line-5
		imageline ($im,   20,  100, 980, 100, $black); // line-6
		imageline ($im,   20,  250, 980, 250, $black); // line-7
		imageline ($im,   20,  400, 980, 400, $black); // line-8
		imageline ($im,   20,  450, 980, 450, $black); // line-9
		imageline ($im,   20,  550, 980, 550, $black); // line-10
		imageline ($im,   20,  800, 980, 800, $black); // line-11
		imageline ($im,   490,  100, 490, 400, $black); // line-12
		imageline ($im,   80,  450, 80, 800, $black); // line-13
		imageline ($im,   560,  450, 560, 800, $black); // line-14
		imageline ($im,   660,  450, 660, 800, $black); // line-15
		imageline ($im,   760,  450, 760, 800, $black); // line-16
		imageline ($im,   860,  450, 860, 800, $black); // line-17
		imageline ($im,   20,  835, 490, 835, $black); // line-18
		imageline ($im,   20,  770, 980, 770, $black); // line-19
		
		//imagestring ($im, size, X,  Y, text, $red);
		$year = date('Y');
		
		imagestring($im, 5, 455,  30, "Bill Of Supply - Services", $black);
		
		
		imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
		imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
		imagestring($im, 3, 22,  124, "Address: ", $black);
		imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
		imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
		imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
		imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
		imagestring($im, 3, 22,  184, "State Code : 27", $black);
		imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
		imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
		imagestring($im, 3, 22,  236, "Transaction No : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black);
		imagestring($im, 3, 22,  262, "Name of the Recipient: ".$member_name, $black);
		imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['address1'], $black);
		imagestring($im, 3, 22,  286, $mem_info[0]['address2'], $black);
		imagestring($im, 3, 22,  298, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
		imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
		
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Charges for Duplicate Certificate", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		//imagestring($im, 3, 700,  900,   "echo<img src=Face.png>", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "% ---", $black);
		imagestring($im, 3, 350,  932, "Rs.---", $black);
		
		$savepath = base_url()."uploads/dupcertinvoice/supplier/";
		$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
		$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
		
		imagepng($im,"uploads/dupcertinvoice/supplier/".$imagename);
		$png = @imagecreatefromjpeg('assets/images/sign.jpg');
		$jpeg = @imagecreatefromjpeg("uploads/dupcertinvoice/supplier/".$imagename);
		@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
		imagepng($im, 'uploads/dupcertinvoice/supplier/'.$imagename);
		
		imagedestroy($im);
		
		return $attachpath = "uploads/dupcertinvoice/user/".$imagename;
	}*/
    //EXM/DUP-CERT/FY/001 Sample: - EXM/DUP-CERT/2017-18/001. 
	function generate_duplicate_cert_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			//$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			$last_id = str_pad($CI->master_model->insertRecord('config_DISA_invoice',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	/*function generate_duplicate_cert_invoice_number_jammu($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance(); 
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice_jammu',$insert_info,true), 4, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}*/
	/*function generate_duplicate_id_invoice_number_jammu($invoice_id= NULL)
		{
			$last_id='';
			$CI = & get_instance();
			//$CI->load->model('my_model');
			if($invoice_id  !=NULL)
			{
				$insert_info = array('invoice_id'=>$invoice_id);
				$last_id = str_pad($CI->master_model->insertRecord('config_dup_icard_invoice_jammu',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			}
			return $last_id;
		}
	*/

/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */
?>