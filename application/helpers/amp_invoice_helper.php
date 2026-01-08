<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function amtinwordamp($amt){
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

 function genarate_amp_invoice($invoice_no){
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$member_ref_id = $CI->master_model->getRecords('amp_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));
	
	$mem_info = $CI->master_model->getRecords('amp_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,name,address1,address2,address3,address4,city,state,pincode_address,sponsor,sponsor_bank_name,bank_address1,bank_address2,bank_address3,bank_address4,bank_city,bank_state,bank_pincode');
	
	//echo 'payment_info',print_r($member_ref_id);
	//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);
	
	$fee_amount1 = '';
	$fee_amount2 = '';
	//get fee amount 
	if($member_ref_id[0]['payment_option'] == '4') //full fee
	{
	    $instalment='Full';
		$fee_amount1 = $CI->config->item('amp_full_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_full_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
	{
	    $instalment='First';
		$fee_amount1 = $CI->config->item('amp_first_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_first_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
	{
	    $instalment='Second';
		$fee_amount1 = $CI->config->item('amp_second_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
	{
	    $instalment='Third';
		$fee_amount1 = $CI->config->item('amp_third_fee').'.00';
	}
	elseif($member_ref_id[0]['payment_option'] == '5')//third fee
	{
	    $instalment='Recovery Amount';
		$fee_amount1 = '10000.00';
	}
	//echo 'fee_amount1',print_r($fee_amount1);
	//echo 'fee_amount2',print_r($fee_amount2);
	
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_cpd_invoice_jk($invoice_no);
		exit;
	}*/
	//address
	$address_1=$address_2='';
	if(isset($mem_info[0]['address1']) )
	{
		$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
	}if(isset($mem_info[0]['address2']) )
	{
		$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'].$mem_info[0]['city']."-".$mem_info[0]['pincode_address'];
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	// create image for recipeint
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
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	if($mem_info[0]['sponsor']='self')
	{
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		//$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		imagestring($im, 3, 40,  400, "Payment Instalment Balance : ".$instalment,$black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	}
	elseif($mem_info[0]['sponsor']='bank')
	{
			
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
		imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['bank_address1'], $black);
		imagestring($im, 3, 40,  300, $mem_info[0]['bank_address2'].$mem_info[0]['bank_city']."-".$mem_info[0]['bank_pincode'], $black);
		imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		//$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		

	}
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "Advanced Management Programme course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 

  imagestring($im, 3, 118,  600, "(AMP XIII - 2024-25)", $black);
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  615, "2", $black);
		imagestring($im, 3, 118,  615, "Travel Expenses", $black);
		imagestring($im, 3, 535,  615, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  615, $fee_amount2, $black);
		imagestring($im, 3, 815,  615, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  615, $fee_amount2, $black);
	}
	
	imagestring($im, 3, 118,  640,"" , $black);
	imagestring($im, 3, 118,  640, "CGST ", $black);
	imagestring($im, 3, 118,  660, "SGST ", $black);
	imagestring($im, 3, 118,  680, "IGST ", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
	
	imagestring($im, 3, 700,  640, "9% ", $black);
		imagestring($im, 3, 700,  660, "9% ", $black);
		imagestring($im, 3, 700,  680, "- ", $black);
		
		imagestring($im, 3, 900,  640, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  680, "- ", $black);
		
		
	}
	// && $invoice_info[0]['state_of_center'] != 'JAM'
	if($invoice_info[0]['state_of_center'] != 'MAH'){
	imagestring($im, 3, 700,  640, "- ", $black);
		imagestring($im, 3, 700,  660, "- ", $black);
		imagestring($im, 3, 700,  680, "18% ", $black);
		
		imagestring($im, 3, 900,  640, "- ", $black);
		imagestring($im, 3, 900,  660, "- ", $black);
		imagestring($im, 3, 900,  680, $invoice_info[0]['igst_amt'], $black);
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words Rupees:".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ampinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/ampinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ampinvoice/user/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ampinvoice/user/'.$imagename);
	imagedestroy($im);
	

	
	/****************************** image for supplier ***********************************/
	// create image for recipeint
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER ", $black);
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	if($mem_info[0]['sponsor']='self')
	{
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		imagestring($im, 3, 40,  400, "Payment Instalment Balance : ".$instalment,$black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	}
	elseif($mem_info[0]['sponsor']='bank')
	{
			
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
		imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['bank_address1'], $black);
		imagestring($im, 3, 40,  300, $mem_info[0]['bank_address2'].$mem_info[0]['bank_city']."-".$mem_info[0]['bank_pincode'], $black);
		imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		

	}
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "Advanced Management Programme course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words Rupees:".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ampinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/ampinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ampinvoice/supplier/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ampinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/ampinvoice/user/".$imagename;
}
//generate custom invoice using invocie id
function custom_genarate_amp_invoice_new($invoice_no)
{
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$member_ref_id = $CI->master_model->getRecords('amp_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));
	
	$mem_info = $CI->master_model->getRecords('amp_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,name,address1,address2,address3,address4,city,state,pincode_address,sponsor,sponsor_bank_name,bank_address1,bank_address2,bank_address3,bank_address4,bank_city,bank_state,bank_pincode');
	
	//echo 'payment_info',print_r($member_ref_id);
	//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);
	
	$fee_amount1 = '';
	$fee_amount2 = '';
	//get fee amount 
	if($member_ref_id[0]['payment_option'] == '4') //full fee
	{
	    $instalment='Full';
		$fee_amount1 = $CI->config->item('amp_full_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_full_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
	{
	    $instalment='First';
		$fee_amount1 = $CI->config->item('amp_first_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_first_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
	{
	    $instalment='Second';
		$fee_amount1 = $CI->config->item('amp_second_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
	{
	    $instalment='Third';
		$fee_amount1 = $CI->config->item('amp_third_fee').'.00';
	}
	elseif($member_ref_id[0]['payment_option'] == '5')//third fee
	{
	    $instalment='Recovery Amount';
		$fee_amount1 = '10000.00';
	}
	//echo 'fee_amount1',print_r($fee_amount1);
	//echo 'fee_amount2',print_r($fee_amount2);
	 $instalment='Recovery Amount';
		$fee_amount1 = '10000.00';
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_cpd_invoice_jk($invoice_no);
		exit;
	}*/
	//address
	$address_1=$address_2='';
	if(isset($mem_info[0]['address1']) )
	{
		$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
	}if(isset($mem_info[0]['address2']) )
	{
		$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'].$mem_info[0]['city']."-".$mem_info[0]['pincode_address'];
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	// create image for recipeint
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
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	if($mem_info[0]['sponsor']='self')
	{
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		//$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		imagestring($im, 3, 40,  400, "Payment Instalment Balance : ".$instalment,$black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	}
	elseif($mem_info[0]['sponsor']='bank')
	{
			
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
		imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['bank_address1'], $black);
		imagestring($im, 3, 40,  300, $mem_info[0]['bank_address2'].$mem_info[0]['bank_city']."-".$mem_info[0]['bank_pincode'], $black);
		imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		//$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		

	}
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "Advanced Management Programme course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words Rupees:".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ampinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/ampinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ampinvoice/user/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ampinvoice/user/'.$imagename);
	imagedestroy($im);
	

	
	/****************************** image for supplier ***********************************/
	// create image for recipeint
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER ", $black);
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	if($mem_info[0]['sponsor']='self')
	{
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		imagestring($im, 3, 40,  400, "Payment Instalment Balance : ".$instalment,$black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	}
	elseif($mem_info[0]['sponsor']='bank')
	{
			
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
		imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['bank_address1'], $black);
		imagestring($im, 3, 40,  300, $mem_info[0]['bank_address2'].$mem_info[0]['bank_city']."-".$mem_info[0]['bank_pincode'], $black);
		imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		

	}
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "Advanced Management Programme course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words Rupees:".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/ampinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/ampinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/ampinvoice/supplier/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/ampinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/ampinvoice/user/".$imagename;
}

   //EXM/DUP-CERT/FY/001 Sample: - EXM/DUP-CERT/2017-18/001. 
	function generate_amp_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			//$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			$last_id = str_pad($CI->master_model->insertRecord('config_amp_invoice',$insert_info,true), 3, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	//JBIMS invocie image
function genarate_JBIMS_invoice($invoice_no){
//$invoice_no =   ;
//echo 'in invoice_no';
//echo $invoice_no;
$CI = & get_instance();
$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));

$member_ref_id = $CI->master_model->getRecords('JBIMS_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));

$mem_info = $CI->master_model->getRecords('JBIMS_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,regnumber,name,address1,address2,address3,address4,city,state,pincode_address,gst_bank_name,gst_no');

//echo 'payment_info',print_r($member_ref_id);
//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);

$fee_amount1 = '';
$fee_amount2 = '';
//get fee amount 
if($member_ref_id[0]['payment_option'] == '4') //full fee
{
$fee_amount1 = $CI->config->item('JBIMS_full_fee').'.00';
$fee_amount2 = $CI->config->item('JBIMS_full_travel_fee').'.00';
}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
{
$fee_amount1 = $CI->config->item('JBIMS_first_fee').'.00';
$fee_amount2 = $CI->config->item('JBIMS_first_travel_fee').'.00';
}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
{
$fee_amount1 = $CI->config->item('JBIMS_second_fee').'.00';
}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
{
$fee_amount1 = $CI->config->item('JBIMS_third_fee').'.00';
}
//echo 'fee_amount1',print_r($fee_amount1);
//echo 'fee_amount2',print_r($fee_amount2);

//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];

/*if($invoice_info[0]['state_of_center'] == 'JAM'){
return genarate_cpd_invoice_jk($invoice_no);
exit;
}*/
//address
$address_1=$address_2='';
if(isset($mem_info[0]['address1']) )
{
$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
}if(isset($mem_info[0]['address2']) )
{
$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'].$mem_info[0]['city']."-".$mem_info[0]['pincode_address'];
}

if($invoice_info[0]['state_of_center'] == 'MAH'){
$wordamt = amtinwordamp($invoice_info[0]['cs_total']);
}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
$wordamt = amtinwordamp($invoice_info[0]['igst_total']);
}

$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));

/****************************** image for user ***********************************/
// create image for recipeint
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

imagestring($im, 5, 40,  220, "DETAILS OF SERVICE RECIPIENT", $black);
imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
;
imagestring($im, 3, 40,  360, "TRANSACTION NUMBER : ".$invoice_info[0]['transaction_no'], $black);




imagestring($im, 3, 40,  260, "NAME OF THE RECIPIENT: ".$mem_info[0]['gst_bank_name'], $black);

imagestring($im, 3, 40,  280, "ADDRESS: ".$address_1, $black);
imagestring($im, 3, 40,  300, $address_2, $black);
imagestring($im, 3, 40,  320, "STATE : ".$invoice_info[0]['state_name'], $black);
imagestring($im, 3, 40,  340, "STATE CODE : ".$invoice_info[0]['state_code'], $black);
if($mem_info[0]['gst_no']!=''){$gstn_no = $mem_info[0]['gst_no'];}else{$gstn_no = '-';}

imagestring($im, 3, 40,  380, "BANK GSTIN / UNIQUE ID: ".$gstn_no, $black);
imagestring($im, 3, 40,  400, "NAME OF THE CANDIDATE : ".$mem_info[0]['name'],$black);


imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice'])), $black);
if($invoice_info[0]['gstin_no'] != '' && $invoice_info[0]['gstin_no'] != 0){
$gstn = $invoice_info[0]['gstin_no'];
}else{
$gstn = "-";
}
imagestring($im, 3, 670,  300, "GSTIN - 27AAATT3309D1ZS ", $black);



imagestring($im, 3, 40,  530, "Sr.No", $black);
imagestring($im, 3, 118,  530, "Description of Services - February 2022 batch", $black);
imagestring($im, 3, 535,  530, "Accounting ", $black);
imagestring($im, 3, 535,  542, "code", $black);
imagestring($im, 3, 535,  554, "of Service", $black);
imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
imagestring($im, 3, 808,  530, "Unit", $black);
imagestring($im, 3, 900,  530, "Total(Rs.)", $black);

imagestring($im, 3, 45,  585, "1", $black);
imagestring($im, 3, 118,  585, "JBIMS course fee", $black);
imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
imagestring($im, 3, 700,  585, $fee_amount1, $black);
imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
imagestring($im, 3, 900,  585, $fee_amount1, $black); 
imagestring($im, 3, 535,  820, "Total", $black); 

if($fee_amount2 > 0)
{
imagestring($im, 3, 45,  600, "2", $black);
imagestring($im, 3, 118,  600, "Travel Expenses", $black);
imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
imagestring($im, 3, 700,  600, $fee_amount2, $black);
imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
imagestring($im, 3, 900,  600, $fee_amount2, $black);
}

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
// && $invoice_info[0]['state_of_center'] != 'JAM'
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

//imagestring($im, 3, 500,  780, "Total", $black);

if($invoice_info[0]['state_of_center'] == 'MAH'){
imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
}




imagestring($im, 3, 40,  860, "Amount in words :Rupees ".$wordamt." Only", $black);
imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
imagestring($im, 3, 260,  900, "Y/N", $black);
imagestring($im, 3, 300,  900, "NO", $black);
imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
imagestring($im, 3, 280,  930, "% ---", $black);
imagestring($im, 3, 350,  930, "Rs.---", $black);

imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
imagestring($im, 3, 720,  950, "Authorised Signatory", $black);

$savepath = base_url()."uploads/JBIMSinvoice/user/";
$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
$imagename = $mem_info[0]['regnumber']."_".$ino.".jpg";

$update_data = array('invoice_image' => $imagename);
$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));

imagepng($im,"uploads/JBIMSinvoice/user/".$imagename);
$png = @imagecreatefromjpeg('assets/images/sign.jpg');
$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
$jpeg = @imagecreatefromjpeg("uploads/JBIMSinvoice/user/".$imagename);

@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
imagepng($im, 'uploads/JBIMSinvoice/user/'.$imagename);
imagedestroy($im);



/****************************** image for supplier ***********************************/
// create image for recipeint
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

imagestring($im, 5, 40,  220, "DETAILS OF SERVICE RECIPIENT", $black);
imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER ", $black);
;
imagestring($im, 3, 40,  360, "TRANSACTION NUMBER : ".$invoice_info[0]['transaction_no'], $black);



imagestring($im, 3, 40,  260, "NAME OF THE RECIPIENT: ".$mem_info[0]['gst_bank_name'], $black);

imagestring($im, 3, 40,  280, "ADDRESS: ".$address_1, $black);
imagestring($im, 3, 40,  300, $address_2, $black);
imagestring($im, 3, 40,  320, "STATE : ".$invoice_info[0]['state_name'], $black);
imagestring($im, 3, 40,  340, "STATE CODE : ".$invoice_info[0]['state_code'], $black);
if($mem_info[0]['gst_no']!=''){$gstn_no = $mem_info[0]['gst_no'];}else{$gstn_no = '-';}

imagestring($im, 3, 40,  380, "BANK GSTIN / UNIQUE ID: ".$gstn_no, $black);
imagestring($im, 3, 40,  400, "NAME OF THE CANDIDATE : ".$mem_info[0]['name'],$black);


imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice'])), $black);
if($invoice_info[0]['gstin_no'] != '' && $invoice_info[0]['gstin_no'] != 0){
$gstn = $invoice_info[0]['gstin_no'];
}else{
$gstn = "-";
}
imagestring($im, 3, 670,  300, "GSTIN - 27AAATT3309D1ZS ", $black);



imagestring($im, 3, 40,  530, "Sr.No", $black);
imagestring($im, 3, 118,  530, "Description of Services - February 2022 batch", $black);
imagestring($im, 3, 535,  530, "Accounting ", $black);
imagestring($im, 3, 535,  542, "code", $black);
imagestring($im, 3, 535,  554, "of Service", $black);
imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
imagestring($im, 3, 808,  530, "Unit", $black);
imagestring($im, 3, 900,  530, "Total(Rs.)", $black);

imagestring($im, 3, 45,  585, "1", $black);
imagestring($im, 3, 118,  585, "JBIMS course fee", $black);
imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
imagestring($im, 3, 700,  585, $fee_amount1, $black);
imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
imagestring($im, 3, 900,  585, $fee_amount1, $black); 
imagestring($im, 3, 535,  820, "Total", $black); 

if($fee_amount2 > 0)
{
imagestring($im, 3, 45,  600, "2", $black);
imagestring($im, 3, 118,  600, "Travel Expenses", $black);
imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
imagestring($im, 3, 700,  600, $fee_amount2, $black);
imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
imagestring($im, 3, 900,  600, $fee_amount2, $black);
}

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
// && $invoice_info[0]['state_of_center'] != 'JAM'
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

//imagestring($im, 3, 500,  780, "Total", $black);

if($invoice_info[0]['state_of_center'] == 'MAH'){
imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
imagestring($im, 3, 900,  820, $invoice_info[0]['igst_total'], $black);
}




imagestring($im, 3, 40,  860, "Amount in words :Rupees ".$wordamt." Only", $black);
imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
imagestring($im, 3, 260,  900, "Y/N", $black);
imagestring($im, 3, 300,  900, "NO", $black);
imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
imagestring($im, 3, 280,  930, "% ---", $black);
imagestring($im, 3, 350,  930, "Rs.---", $black);

imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
imagestring($im, 3, 720,  950, "Authorised Signatory", $black);

$savepath = base_url()."uploads/JBIMSinvoice/supplier/";
$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
$imagename = $mem_info[0]['regnumber']."_".$ino.".jpg";

//$update_data = array('invoice_image' => $imagename);
//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));

imagepng($im,"uploads/JBIMSinvoice/supplier/".$imagename);
$png = @imagecreatefromjpeg('assets/images/sign.jpg');
$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
$jpeg = @imagecreatefromjpeg("uploads/JBIMSinvoice/supplier/".$imagename);

@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
imagepng($im, 'uploads/JBIMSinvoice/supplier/'.$imagename);
imagedestroy($im);


return $attachpath = "uploads/JBIMSinvoice/user/".$imagename;
}

	//jbims invoice no
	function generate_JBIMS_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			//$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			$last_id = str_pad($CI->master_model->insertRecord('config_JBIMS_invoice',$insert_info,true), 3, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
		####----generate JBIMS member regnumber--###
	function generate_JBIMS_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('JBIMS_id '=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_JBIMS_memreg',$insert_info,true);
		}
		return $last_id;
	}
	
	####----generate XLRI member regnumber--###
	function generate_XLRI_memreg($reg_id = NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($reg_id  !=NULL)
		{
			$insert_info = array('xlri_id '=>$reg_id);
			$last_id = $CI->master_model->insertRecord('config_XLRI_memreg',$insert_info,true);
		}
		return $last_id;
	}
	
	//xlri invoice no
	function generate_XLRI_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			//$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			$last_id = str_pad($CI->master_model->insertRecord('config_XLRI_invoice',$insert_info,true), 3, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
//xlri invocie image
 function genarate_XLRI_invoice($invoice_no){
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$member_ref_id = $CI->master_model->getRecords('XLRI_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));
	
	$mem_info = $CI->master_model->getRecords('XLRI_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,regnumber,name,address1,address2,address3,address4,city,state,pincode_address,');
	
	//echo 'payment_info',print_r($member_ref_id);
	//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);
	
	$fee_amount1 = '';
	$fee_amount2 = '';
	//get fee amount 
	if($member_ref_id[0]['payment_option'] == '4') //full fee
	{
		$fee_amount1 = $CI->config->item('XLRI_full_fee').'.00';
		$fee_amount2 = $CI->config->item('XLRI_full_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
	{
		$fee_amount1 = $CI->config->item('XLRI_first_fee').'.00';
		$fee_amount2 = $CI->config->item('XLRI_first_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
	{
		$fee_amount1 = $CI->config->item('XLRI_second_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
	{
		$fee_amount1 = $CI->config->item('XLRI_third_fee').'.00';
	}
	//echo 'fee_amount1',print_r($fee_amount1);
	//echo 'fee_amount2',print_r($fee_amount2);
	
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_cpd_invoice_jk($invoice_no);
		exit;
	}*/
	//address
	$address_1=$address_2='';
	if(isset($mem_info[0]['address1']) )
	{
		$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
	}if(isset($mem_info[0]['address2']) )
	{
		$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'].$mem_info[0]['city']."-".$mem_info[0]['pincode_address'];
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	// create image for recipeint
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
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "XLRI course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
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
	
	$savepath = base_url()."uploads/XLRIinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['regnumber']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/XLRIinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/XLRIinvoice/user/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/XLRIinvoice/user/'.$imagename);
	imagedestroy($im);
	

	
	/****************************** image for supplier ***********************************/
	// create image for recipeint
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER ", $black);
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "XLRI course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
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
	
	$savepath = base_url()."uploads/XLRIinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['regnumber']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/XLRIinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/XLRIinvoice/supplier/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/XLRIinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/XLRIinvoice/user/".$imagename;
}

//xlri invocie image custome
 function custom_genarate_XLRI_invoice_new($invoice_no){
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$member_ref_id = $CI->master_model->getRecords('XLRI_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));
	
	$mem_info = $CI->master_model->getRecords('XLRI_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,name,address1,address2,address3,address4,city,state,pincode_address,');
	
	//echo 'payment_info',print_r($member_ref_id);
	//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);
	
	$fee_amount1 = '';
	$fee_amount2 = '';
	//get fee amount 
	if($member_ref_id[0]['payment_option'] == '4') //full fee
	{
		$fee_amount1 = $CI->config->item('XLRI_full_fee').'.00';
		$fee_amount2 = $CI->config->item('XLRI_full_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
	{
		$fee_amount1 = $CI->config->item('XLRI_first_fee').'.00';
		$fee_amount2 = $CI->config->item('XLRI_first_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
	{
		$fee_amount1 = $CI->config->item('XLRI_second_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
	{
		$fee_amount1 = $CI->config->item('XLRI_third_fee').'.00';
	}
	//echo 'fee_amount1',print_r($fee_amount1);
	//echo 'fee_amount2',print_r($fee_amount2);
	
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return genarate_cpd_invoice_jk($invoice_no);
		exit;
	}*/
	//address
	$address_1=$address_2='';
	if(isset($mem_info[0]['address1']) )
	{
		$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
	}if(isset($mem_info[0]['address2']) )
	{
		$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'].$mem_info[0]['city']."-".$mem_info[0]['pincode_address'];
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinwordamp($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	// create image for recipeint
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
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
	
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "XLRI course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
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
	
	$savepath = base_url()."uploads/XLRIinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/XLRIinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/XLRIinvoice/user/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/XLRIinvoice/user/'.$imagename);
	imagedestroy($im);
	

	
	/****************************** image for supplier ***********************************/
	// create image for recipeint
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER ", $black);
;
	imagestring($im, 3, 40,  360, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);

	
		
		imagestring($im, 3, 40,  260, "Name of the Recipient: ".$mem_info[0]['name'], $black);
		
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 40,  340, "State Code : ".$invoice_info[0]['state_code'], $black);
		if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '-';}
		$gstn_no = '-';
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
		//imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
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
	
	imagestring($im, 3, 45,  585, "1", $black);
	imagestring($im, 3, 118,  585, "XLRI course fee", $black);
	imagestring($im, 3, 535,  585, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  585, $fee_amount1, $black);
	imagestring($im, 3, 815,  585, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  585, $fee_amount1, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	if($fee_amount2 > 0)
	{
		imagestring($im, 3, 45,  600, "2", $black);
		imagestring($im, 3, 118,  600, "Travel Expenses", $black);
		imagestring($im, 3, 535,  600, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 700,  600, $fee_amount2, $black);
		imagestring($im, 3, 815,  600, $invoice_info[0]['qty'], $black);
		imagestring($im, 3, 900,  600, $fee_amount2, $black);
	}
	
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
	// && $invoice_info[0]['state_of_center'] != 'JAM'
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
	
	//imagestring($im, 3, 500,  780, "Total", $black);
		
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
	
	$savepath = base_url()."uploads/XLRIinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/XLRIinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/XLRIinvoice/supplier/".$imagename);
	
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/XLRIinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/XLRIinvoice/user/".$imagename;
}
	/*function generate_cpd_invoice_number_jammu($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance(); 
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id); 
			$last_id = str_pad($CI->master_model->insertRecord('config_cpd_invoice_jammu',$insert_info,true), 4, "0", STR_PAD_LEFT);;
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