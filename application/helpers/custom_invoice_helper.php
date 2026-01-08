<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
 function genarate_amp_invoice_custom($invoice_no){
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$member_ref_id = $CI->master_model->getRecords('amp_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option','member_regnumber'));
	
	$mem_info = $CI->master_model->getRecords('amp_candidates',array('regnumber'=>$member_ref_id[0]['member_regnumber']),'id,name,address1,address2,address3,address4,city,state,pincode_address,sponsor,sponsor_bank_name,bank_address1,bank_address2,bank_address3,bank_address4,bank_city,bank_state,bank_pincode');
	
	echo 'payment_info',print_r($member_ref_id);
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

 
function custom_amtinword($amt){
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

function custom_generate_credit_note($transaction_no){ 
	//echo $transaction_no;
	
	$CI = & get_instance();  
	
	$payment_txn = $CI->master_model->getRecords('payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no,id');
	
	
	
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment_txn[0]['id']));
	/*echo $CI->db->last_query();
	echo '<pre>';
	print_r($invoice_info);*/
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$address1 = $mem_info[0]['address1']." ".$mem_info[0]['address2']." ".$mem_info[0]['address3']." ".$mem_info[0]['address4'];
	
	$address2 = $mem_info[0]['district']." ".$mem_info[0]['city']." ".$mem_info[0]['pincode'];
	
	if($invoice_info[0]['center_name'] !='' ){
		$city = $invoice_info[0]['center_name'];
	}else{
		$city = '';
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	$exp = explode("/",$invoice_info[0]['invoice_no']);
	$cr_imagename = "CN_".$exp[0]."_".$exp[1]."_".$exp[2].".jpg";
	
	
	$y = date('y');
	$ny = date('y')+1;   
	
	$credit_note_no = 'CDN/'.$exp[0].''.$exp[1].'/'.$config_last_id;
	
	
	$CI->db->where('transaction_no',$transaction_no);
	$CI->db->where('req_status',5);
	$maker_rec = $CI->master_model->getRecords('maker_checker','','refund_date,credit_note_number,req_module');
	
	$credit_title = $CI->master_model->getRecords('credit_note_title',array('pay_type'=>$maker_rec[0]['req_module']),'title,service_code');
	
	
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
	imageline ($im,   580,  200, 580, 480, $black); // line-11
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
	imagestring($im, 5, 400,  170, "Credit Note", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 600, 220, "Details of Assessee", $black);
	imagestring($im, 3, 40,  260, "Membership number: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Address: ".$address1, $black);
	imagestring($im, 3, 40,  320, $address2, $black);
	
	imagestring($im, 3, 40,  340, "City: ".$city, $black);
	imagestring($im, 3, 40,  360, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  400, "GST No: NA", $black);
	imagestring($im, 3, 40,  420, "Reference no of Original Invoice: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 40,  440, "Date of Original Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 40,  460, "Transaction no : ".$transaction_no, $black);
	
	
	imagestring($im, 3, 600,  260, "Address: Registered office Kohinoor City,", $black);
	imagestring($im, 3, 600,  280, "Commercial - II,  Tower 1, 2nd Floor, Kirole Road", $black);
	
	imagestring($im, 3, 600,  300, "Off LBS Marg, Kurla(West), Mumbai - 400 070,", $black);
	imagestring($im, 3, 600,  320, "Maharashtra", $black);
	imagestring($im, 3, 600,  340, "www.iibf.org.in", $black);
	imagestring($im, 3, 600,  360, "Credit Note no: ".$maker_rec[0]['credit_note_number'], $black);
	//imagestring($im, 3, 600,  380, "Date : ", $black);
	imagestring($im, 3, 600,  380, "Refund Date : ".date("d-m-Y", strtotime($maker_rec[0]['refund_date'])), $black);
	imagestring($im, 3, 600,  400, "GSTIN No: 27AAATT3309D1ZS", $black);
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service/HSN", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs)", $black);
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, $credit_title[0]['title'], $black);
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
	
	imagestring($im, 3, 40,  860, "Amount in words : Rs. ".$wordamt, $black);
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/CreditNote/";
	
	$ex = explode("/",$invoice_info[0]['invoice_no']);
	$imagename = "CN_".$ex[0]."_".$ex[1]."_".$ex[2].".jpg";
	
	
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/CreditNote/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/CreditNote/'.$imagename);
	imagedestroy($im); 	
	$imagename1 = "CN_".$ex[0]."_".$ex[1]."_".$ex[2].".jpg";
	
	return $attachpath = "uploads/CreditNote/".$imagename1; 
	//return 'pawan';
	//exit;
}

function custom_generate_credit_note_img($transaction_no){ 
	//echo $transaction_no;
	
	$CI = & get_instance();  
	
	$payment_txn = $CI->master_model->getRecords('payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no,id');
	
	
	
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment_txn[0]['id']));
	/*echo $CI->db->last_query();
	echo '<pre>';
	print_r($invoice_info);*/
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$address1 = $mem_info[0]['address1']." ".$mem_info[0]['address2']." ".$mem_info[0]['address3']." ".$mem_info[0]['address4'];
	
	$address2 = $mem_info[0]['district']." ".$mem_info[0]['city']." ".$mem_info[0]['pincode'];
	
	if($invoice_info[0]['center_name'] !='' ){
		$city = $invoice_info[0]['center_name'];
	}else{
		$city = '';
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	$exp = explode("/",$invoice_info[0]['invoice_no']);
	$cr_imagename = "CN_".$exp[0]."_".$exp[1]."_".$exp[2].".jpg";
	
	
	$y = date('y');
	$ny = date('y')+1;   
	
	//$credit_note_no = 'CDN/'.$exp[0].''.$exp[1].'/'.$config_last_id;
	
	$credit_note_no = 'CDN/19-20/9998';
	
	
	$CI->db->where('transaction_no',$transaction_no);
	$CI->db->where('req_status',5);
	$maker_rec = $CI->master_model->getRecords('maker_checker','','refund_date,credit_note_number,req_module');
	
	$credit_title = $CI->master_model->getRecords('credit_note_title',array('pay_type'=>$maker_rec[0]['req_module']),'title,service_code');
	
	
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
	imageline ($im,   580,  200, 580, 480, $black); // line-11
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
	imagestring($im, 5, 400,  170, "Credit Note", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 600, 220, "Details of Assessee", $black);
	imagestring($im, 3, 40,  260, "Membership number: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Address: ".$address1, $black);
	imagestring($im, 3, 40,  320, $address2, $black);
	
	imagestring($im, 3, 40,  340, "City: ".$city, $black);
	imagestring($im, 3, 40,  360, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  400, "GST No: NA", $black);
	imagestring($im, 3, 40,  420, "Reference no of Original Invoice: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 40,  440, "Date of Original Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 40,  460, "Transaction no : ".$transaction_no, $black);
	
	
	imagestring($im, 3, 600,  260, "Address: Registered office Kohinoor City,", $black);
	imagestring($im, 3, 600,  280, "Commercial - II,  Tower 1, 2nd Floor, Kirole Road", $black);
	
	imagestring($im, 3, 600,  300, "Off LBS Marg, Kurla(West), Mumbai - 400 070,", $black);
	imagestring($im, 3, 600,  320, "Maharashtra", $black);
	imagestring($im, 3, 600,  340, "www.iibf.org.in", $black);
	imagestring($im, 3, 600,  360, "Credit Note no: ".$maker_rec[0]['credit_note_number'], $black);
	//imagestring($im, 3, 600,  380, "Date : ", $black);
	imagestring($im, 3, 600,  380, "Refund Date : ".date("d-m-Y", strtotime($maker_rec[0]['refund_date'])), $black);
	imagestring($im, 3, 600,  400, "GSTIN No: 27AAATT3309D1ZS", $black);
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service/HSN", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs)", $black);
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, $credit_title[0]['title'], $black);
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
	
	imagestring($im, 3, 40,  860, "Amount in words : Rs. ".$wordamt, $black);
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/CreditNote/";
	
	$ex = explode("/",$invoice_info[0]['invoice_no']);
	$imagename = "CN_".$ex[0]."_".$ex[1]."_".$ex[2].".jpg";
	
	
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/CreditNote/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/CreditNote/'.$imagename);
	imagedestroy($im); 	
	$imagename1 = "CN_".$ex[0]."_".$ex[1]."_".$ex[2].".jpg";
	
	return $attachpath = "uploads/CreditNote/".$imagename1; 
	//return 'pawan';
	//exit;
}

function custom_genarate_exam_invoice($receipt_no){ 
	//$receipt_no = '900490642';
	//echo "here";exit;
	$CI = & get_instance();
	$CI->db->where('invoice_no !=', '');
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	//echo $CI->db->last_query();
	/*if($invoice_info[0]['state_of_center'] == 'JAM'){
		return custom_genarate_exam_invoice_jk($invoice_no);
		exit;
	}*/
	/*echo "<pre>";
	print_r($mem_info);
	exit;*/
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// image for user
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
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
	}elseif($invoice_info[0]['exam_code'] == 590){
		$exam_code =59;
	}
	elseif($invoice_info[0]['exam_code'] == 810){
		$exam_code =81;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period'],'pay_status'=>1));
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	/*echo $CI->db->last_query();
	echo ">>".$exam_period;
	exit;*/
	
	imagestring($im, 3, 22,  248, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 22,  260, "Exam period: ".$exam_period, $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name: ".$member_name, $black);
	imagestring($im, 3, 22,  322, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	
	
	
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/examinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  248, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 22,  260, "Exam period: ".$exam_period, $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name: ".$member_name, $black);
	imagestring($im, 3, 22,  322, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/examinvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	return $attachpath = "uploads/examinvoice/user/".$imagename;
	
}

function custom_genarate_exam_invoice_newdesign_temp($invoice_id){  //echo 'herer ';exit;
	//$receipt_no = '900490642';
	if($invoice_id!=''){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600 || $invoice_info[0]['exam_code'] == 16000){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770 || $invoice_info[0]['exam_code'] == 17700){
		$exam_code =177;
	}
	elseif($invoice_info[0]['exam_code'] == 1750){
		$exam_code =175;
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
	
	if($exam_code > 0){
		$exam_name_code = $exam_code;
	}else{
		$exam_name_code = $invoice_info[0]['exam_code'];
	}
	$exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
	if($exam_name[0]['exam_name'] != ''){
		$invoice_exname = $exam_name[0]['exam_name'];
	}else{
		$invoice_exname = '-';
	}
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	//echo '>>>'. $CI->db->last_query();
	//exit;
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	
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
	//echo $imagename;
	//echo "<br/>";
	imagepng($im,"uploads/examinvoice/user/".$imagename);
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
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
	//echo $imagename;
	imagepng($im,"uploads/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	$u_arr = array('gen_flag'=>1);
	$CI->master_model->updateRecord('aug_invoice_gen',$u_arr,array('invoice_id'=>$invoice_id));
	
		return $attachpath = "uploads/examinvoice/user/".$imagename;
	}
	
}
// added by chaitali
function custom_genarate_exam_invoice_newdesign_el($invoice_id)
{
	
	//$receipt_no = '900490642';
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600 || $invoice_info[0]['exam_code'] == 16000){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770 || $invoice_info[0]['exam_code'] == 17700){
		$exam_code =177;
	}
	elseif($invoice_info[0]['exam_code'] == 1750){
		$exam_code =175;
	}
	elseif($invoice_info[0]['exam_code'] == 590){
		$exam_code =59;
	}
	elseif($invoice_info[0]['exam_code'] == 810){
		$exam_code =81;
	}elseif($invoice_info[0]['exam_code'] == 2027){
		$exam_code =1017;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	if($exam_code > 0){
		$exam_name_code = $exam_code;
	}else{
		$exam_name_code = $invoice_info[0]['exam_code'];
	}
	$exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
	if($exam_name[0]['exam_name'] != ''){
		$invoice_exname = $exam_name[0]['exam_name'];
	}else{
		$invoice_exname = '-';
	}
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
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
	
	if($invoice_info[0]['total_el_amount'] > 0){
		imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
		imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
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
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
	

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
	
	if($invoice_info[0]['total_el_amount'] > 0){
			imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
			imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
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
function custom_genarate_exam_invoice_newdesign($invoice_id){  
	//$receipt_no = '900490642';
	if($invoice_id!=''){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	//echo '>>>'. $CI->db->last_query();
	//echo '<br/>';
	//exit;
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	

	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600 || $invoice_info[0]['exam_code'] == 16000){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770 || $invoice_info[0]['exam_code'] == 17700){
		$exam_code =177;
	}
	elseif($invoice_info[0]['exam_code'] == 1750){
		$exam_code =175;
	}
	elseif($invoice_info[0]['exam_code'] == 590){
		$exam_code =59;
	}
	elseif($invoice_info[0]['exam_code'] == 810){ 
		$exam_code =81;
	}elseif($invoice_info[0]['exam_code'] == 2027){
		$exam_code =1017;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	if($exam_code > 0){
		$exam_name_code = $exam_code;
	}else{
		$exam_name_code = $invoice_info[0]['exam_code'];
	}
	$exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
	
	if($exam_name[0]['exam_name'] != ''){
		$invoice_exname = $exam_name[0]['exam_name'];
	}else{
		$invoice_exname = '-';
	}
	
	//$exam_period = '912';
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	//echo '>>>'. $CI->db->last_query();
	//echo '<br/>';
	//exit;
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{ 
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		//echo '##'. $CI->db->last_query();
		//echo '<br/>';
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	//echo '>>'. $exam_period;
	
	//exit;
	
	
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
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
	if($invoice_info[0]['total_el_amount'] > 0){
		imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
		imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
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
	//echo $imagename;
	//echo "<br/>";
	imagepng($im,"uploads/examinvoice/user/".$imagename);
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
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
	if($invoice_info[0]['total_el_amount'] > 0){
			imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
			imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
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
	//echo $imagename;
	imagepng($im,"uploads/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/examinvoice/supplier/".$imagename;
	}
	
}
function custom_genarate_draexam_invoice($receipt_no){  
	
	$CI = & get_instance();
	
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no' => $receipt_no));
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(custom_amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(custom_amtinword($invoice_info[0]['igst_total']));
	}
	
	// image for DRA Institute
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black); // Quantity
	 
	$base_total = $invoice_info[0]['fee_amt'] * $invoice_info[0]['qty'];
	$base_total = number_format($base_total, 2, '.', '');
	imagestring($im, 3, 900,  560, $base_total, $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black); // Quantity
	 
	$base_total = $invoice_info[0]['fee_amt'] * $invoice_info[0]['qty'];
	$base_total = number_format($base_total, 2, '.', '');
	imagestring($im, 3, 900,  560, $base_total, $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}

function custom_genarate_reg_invoice($receipt_no){ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// image for user
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of service Recipient: ".$member_name, $black);
	imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1_pr'], $black);
	imagestring($im, 3, 22,  322, $mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 22,  334, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges paid towards ordinary membership registration", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);	
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/reginvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/reginvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/reginvoice/user/'.$imagename);
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of service Recipient: ".$member_name, $black);
	imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1_pr'], $black);
	imagestring($im, 3, 22,  322, $mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 22,  334, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges paid towards ordinary membership registration", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		// imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/reginvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/reginvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/reginvoice/supplier/'.$imagename);
	
	
	imagedestroy($im);
	return $attachpath = "uploads/reginvoice/user/".$imagename;
	
}

/* New Invoice funcation */
function genarate_DISA_invoice_custom($invoice_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Exam code : ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 40,  320, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  340, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  360, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  380, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  400, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  420, "GST No: -", $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges for certificate", $black);
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
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
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	imagedestroy($im);
	
	/****************************** Image of supplier *********************************/
	
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Exam code : ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 40,  320, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  340, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  360, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  380, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  400, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  420, "GST No: -", $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges for certificate", $black);
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
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


function custom_genarate_disa_invoice($receipt_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// image for user
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  248, "Exam code: ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 22,  260, "Exam period: ".$invoice_info[0]['exam_period'], $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name: ".$member_name, $black);
	imagestring($im, 3, 22,  322, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges for certificate", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/examinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	
	imagedestroy($im);
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  248, "Exam code: ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 22,  260, "Exam period: ".$invoice_info[0]['exam_period'], $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name: ".$member_name, $black);
	imagestring($im, 3, 22,  322, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges for certificate", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/examinvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	return $attachpath = "uploads/examinvoice/user/".$imagename;
}

function custome_genarate_duplicateicard_invoice($invoice_id){
	//$invoice_no =   ;
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice_test',array('invoice_id'=>$invoice_id));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1 ,address2, address3, address4, city, state, pincode ');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		return custome_genarate_duplicateicard_invoice_jk($receipt_no);
		exit;
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/****************************** image for user ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : 14-08-2017 ", $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	
	imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  262, "Name of service Recipient: ".$member_name, $black);
	imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 22,  286, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  298, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
	imagestring($im, 3, 22,  346, "Transaction No : ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges of Duplicate I Card", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	
	$savepath = base_url()."uploads/custom_dupicardinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/custom_dupicardinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_dupicardinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_dupicardinvoice/user/'.$imagename);
	
	imagedestroy($im);
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : 14-08-2017", $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	
	imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  262, "Name of service Recipient: ".$member_name, $black);
	imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 22,  286, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  298, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
	imagestring($im, 3, 22,  346, "Transaction No : ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges of Duplicate I Card", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/custom_dupicardinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/custom_dupicardinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_dupicardinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_dupicardinvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	
	return $attachpath = "uploads/custom_dupicardinvoice/user/".$imagename;
}

function custome_genarate_duplicatecert_invoice($invoice_no){
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1 ,address2, address3, address4, city, state, pincode ');
	
	if(empty($mem_info))
	{
		$mem_info = $CI->master_model->getRecords('dra_members',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	}
	
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	/***************************** Invoice for user ********************************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
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
	imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
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
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/custom_dupcertinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/custom_dupcertinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_dupcertinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_dupcertinvoice/user/'.$imagename);
	
	imagedestroy($im);
	
	return $attachpath = "uploads/custom_dupcertinvoice/user/".$imagename;
	
	
    /***************************** Invoice for supplier ********************************************/
	
	
	/*$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice", $black);
	
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
	imagestring($im, 3, 100,  530, "Description of Service", $black);
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
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "inter-state", $black);
		imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/custom_dupcertinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	imagepng($im,"uploads/custom_dupcertinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_dupcertinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_dupcertinvoice/supplier/'.$imagename);
	
	imagedestroy($im);*/
	
}

function custom_genarate_bankquest_invoice_old($invoice_no){
	
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname, middlename, lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$wordamt = bnqamtinword($invoice_info[0]['igst_total']);
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
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
	
	imagestring($im, 5, 415,  30, "Bill of Supply - Services", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	//imagestring($im, 3, 22,  236, "Transaction no : 1234567", $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	
	imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	imagestring($im, 3, 22,  262, "Address of Buyer (Billed to)", $black);
	imagestring($im, 3, 22,  274, "Name of the buyer: ".$member_name, $black);
	imagestring($im, 3, 22,  286, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  298, "State Code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  310, "GSTIN / Unique Id : NA", $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of goods & Service ", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Journals - IIBF BANK QUEST", $black);
	imagestring($im, 3, 630,  560, $invoice_info[0]['service_code'], $black); // 590
	imagestring($im, 3, 720,  560, $invoice_info[0]['fee_amt'], $black); // 690
	imagestring($im, 3, 850,  560, "1", $black); // 780
	imagestring($im, 3, 940,  560, $invoice_info[0]['fee_amt'], $black); // 900
	
	imagestring($im, 3, 500,  780, "Total", $black);
	imagestring($im, 3, 940,  780, $invoice_info[0]['igst_total'], $black);
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt." Only", $black);
	imagestring($im, 3, 720,  900, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs ---", $black);
	
	
	$savepath = base_url()."uploads/bnqinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $ino.".jpg";
	
	imagepng($im,"uploads/bnqinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bnqinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/bnqinvoice/user/'.$imagename);
	
	imagedestroy($im);
}

function custom_genarate_bankquest_invoice($invoice_id,$bv_id){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	$mem_info = $CI->master_model->getRecords('bank_vision',array('bv_id'=>$bv_id),'fname,mname,lname,address_1,address_2,address_3,address_4');
	$member_name = $mem_info[0]['fname']." ".$mem_info[0]['mname']." ".$mem_info[0]['lname'];
	
	$wordamt = bnqamtinword($invoice_info[0]['igst_total']);
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
	imagestring($im, 3, 100,  600, "Journals - IIBF BANK QUEST", $black);
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
	
	
	
	$savepath = base_url()."uploads/bnqinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $ino.".jpg";
	
	
	imagepng($im,"uploads/bnqinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bnqinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/bnqinvoice/user/'.$imagename);
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
	imagestring($im, 3, 100,  600, "Journals - IIBF BANK QUEST", $black);
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
	
	
	
	$savepath = base_url()."uploads/bnqinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $ino.".jpg";
	imagepng($im,"uploads/bnqinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bnqinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/bnqinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/bnqinvoice/user/".$imagename;
	
}

function custom_genarate_vision_invoice($invoice_id,$vision_id){
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	$mem_info = $CI->master_model->getRecords('iibf_vision',array('vision_id'=>$vision_id),'fname,mname,lname,address_1,address_2,address_3,address_4');
	$member_name = $mem_info[0]['fname']." ".$mem_info[0]['mname']." ".$mem_info[0]['lname'];
	
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

function custome_generate_bulk_examinvoice($id){ 
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$id,'app_type'=>'Z'));
	//$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$institute_info = $CI->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
	
	$state_info = $CI->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
	
	$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
	
	
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		return custom_genarate_exam_invoice_jk($invoice_no);
		exit;
	}
	
	
	if($institute_info[0]['ste_code'] == 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// image for user
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of the Recipient: ".$institute_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Address: ".$institute_info[0]['address1']." ".$institute_info[0]['address2'], $black);
	imagestring($im, 3, 22,  322, $institute_info[0]['address3']." ".$institute_info[0]['address4'], $black);
	imagestring($im, 3, 22,  334, $institute_info[0]['address5']." ".$institute_info[0]['address6'], $black);
	imagestring($im, 3, 22,  346, "State: ".$state_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$state_info[0]['state_no'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : ".$institute_info[0]['gstin_no'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, "-", $black); // Rate
	imagestring($im, 3, 780,  560, "-", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	imagestring($im, 3, 45,  660, "Less", $black);
	imagestring($im, 3, 100,  660, "Discount -", $black);
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, $invoice_info[0]['disc_amt'], $black);
	
	/*imagestring($im, 3, 100,  680, "NET-", $black);
	imagestring($im, 3, 690,  680, "-", $black);
	imagestring($im, 3, 900,  680, number_format($net_amt, 2, '.', '') , $black);*/
	
	
	if($institute_info[0]['ste_code'] == 'MAH'){
		imagestring($im, 3, 500,  700, "CGST", $black);
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 500,  715, "SGST", $black);
		imagestring($im, 3, 690,  715, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  715, $invoice_info[0]['sgst_amt'], $black);
	}
	
	if($institute_info[0]['ste_code'] != 'MAH'){
		imagestring($im, 3, 500,  740, "IGST", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}
	
	
	
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($institute_info[0]['ste_code'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/bulkexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "bulk_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/bulkexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bulkexaminvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/bulkexaminvoice/user/'.$imagename);
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
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
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of the Recipient: ".$institute_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Address: ".$institute_info[0]['address1']." ".$institute_info[0]['address2'], $black);
	imagestring($im, 3, 22,  322, $institute_info[0]['address3']." ".$institute_info[0]['address4'], $black);
	imagestring($im, 3, 22,  334, $institute_info[0]['address5']." ".$institute_info[0]['address6'], $black);
	imagestring($im, 3, 22,  346, "State: ".$state_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$state_info[0]['state_no'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : ".$institute_info[0]['gstin_no'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, "-", $black); // Rate
	imagestring($im, 3, 780,  560, "-", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	imagestring($im, 3, 45,  660, "Less", $black);
	imagestring($im, 3, 100,  660, "Discount -", $black);
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, $invoice_info[0]['disc_amt'], $black);
	
	
	
	/*imagestring($im, 3, 100,  680, "NET-", $black);
	imagestring($im, 3, 690,  680, "-", $black);
	imagestring($im, 3, 900,  680, number_format($net_amt, 2, '.', ''), $black);*/
	
	
	if($institute_info[0]['ste_code'] == 'MAH'){
		imagestring($im, 3, 500,  700, "CGST", $black);
		imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 500,  715, "SGST", $black);
		imagestring($im, 3, 690,  715, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  715, $invoice_info[0]['sgst_amt'], $black);
	}
	
	if($institute_info[0]['ste_code'] != 'MAH'){
		imagestring($im, 3, 500,  740, "IGST", $black);
		imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
	}

	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($institute_info[0]['ste_code'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/bulkexaminvoice/supplier/";
	$imagename = "bulk_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/bulkexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bulkexaminvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/bulkexaminvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	return $attachpath = "uploads/bulkexaminvoice/user/".$imagename;
	
	}
	
function genarate_draexam_invoice_custom($invoice_no){
	
	$CI = & get_instance();
	
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
	}
	
	// image for DRA Institute
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black); // Quantity
	 
	$base_total = $invoice_info[0]['fee_amt'] * $invoice_info[0]['qty'];
	$base_total = number_format($base_total, 2, '.', '');
	imagestring($im, 3, 900,  560, $base_total, $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id' => $invoice_no));
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black); // Quantity
	 
	$base_total = $invoice_info[0]['fee_amt'] * $invoice_info[0]['qty'];
	$base_total = number_format($base_total, 2, '.', '');
	imagestring($im, 3, 900,  560, $base_total, $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}

function custom_genarate_blended_invoice($receipt_no,$zone_code,$program_name,$mem_gstin_no){
	//echo "here";
	//exit; 
	 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	/* Get Member Details */
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	/* Get Zone Details */
	$zone_info=$CI->master_model->getRecords('zone_master',array('zone_code'=>$zone_code,'isdeleted'=>0));
	$zone_address1 = $zone_info[0]['zone_address1'];
	$zone_address2 = $zone_info[0]['zone_address2'];
	$zone_address3 = $zone_info[0]['zone_address3'];
	$zone_address4 = $zone_info[0]['zone_address4'];
	$gstin_no      = $zone_info[0]['gstin_no'];
	$state_code    = $zone_info[0]['state_code'];
	$state_name    = $zone_info[0]['state_name'];
	$stateArr = $CI->master_model->getRecords('zone_state_master',array('state_code'=>$mem_info[0]['state'],'state_delete'=>0),'state_no,state_name','','1');
	if($invoice_info[0]['cs_total'] != 0.00){
		$wordamt = custom_amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		$wordamt = custom_amtinword($invoice_info[0]['igst_total']);
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	// image for user
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	imagestring($im, 5, 155,  70, "", $black);
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN : ".$gstin_no, $black);
	imagestring($im, 3, 22,  124, "Address : ", $black);
	imagestring($im, 3, 22,  136, $zone_address1 , $black);
	imagestring($im, 3, 22,  148, $zone_address2." ".$zone_address3, $black);
	imagestring($im, 3, 22,  160, $zone_address4, $black);
	imagestring($im, 3, 22,  172, "State : ".$state_name ,$black);
	imagestring($im, 3, 22,  184, "State Code : ".$state_code, $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 22,  248, "Course name : ".$program_name, $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);	
	
	/*imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of service Recipient : ".$member_name, $black);
	imagestring($im, 3, 22,  310, "Address : ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  334, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  346, "State : ".$stateArr[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$stateArr[0]['state_no'], $black);*/
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no. : ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name : ".$member_name, $black);
	imagestring($im, 3, 22,  322, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name : ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : ".$mem_gstin_no, $black);
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges Towards Training Program", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	if($invoice_info[0]['cgst_rate'] <=  0.00){
		$cgst_rate = "-";
	}else{
		$cgst_rate = $invoice_info[0]['cgst_rate']."%";
	}
	if($invoice_info[0]['cgst_amt'] <=  0.00){
		$cgst_amt = "-";
	}else{
		$cgst_amt = $invoice_info[0]['cgst_amt'];
	}
	if($invoice_info[0]['sgst_rate'] <=  0.00){
		$sgst_rate = "-";
	}else{
		$sgst_rate = $invoice_info[0]['sgst_rate']."%";
	}
	if($invoice_info[0]['sgst_amt'] <=  0.00){
		$sgst_amt = "-";
	}else{
		$sgst_amt = $invoice_info[0]['sgst_amt'];
	}
	if($invoice_info[0]['igst_rate'] <=  0.00){
		$igst_rate = "-";
	}else{
		$igst_rate = $invoice_info[0]['igst_rate']."%";
	}
	if($invoice_info[0]['igst_amt'] <=  0.00){
		$igst_amt = "-";
	}else{
		$igst_amt = $invoice_info[0]['igst_amt'];
	}
	imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
	imagestring($im, 3, 300,  660, "Central Tax:", $black);
	imagestring($im, 3, 690,  660, $cgst_rate, $black);
	imagestring($im, 3, 900,  660, $cgst_amt, $black);
	imagestring($im, 3, 300,  672, "State Tax:", $black);
	imagestring($im, 3, 690,  672, $sgst_rate, $black);
	imagestring($im, 3, 900,  672, $sgst_amt, $black);
	imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
	imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
	imagestring($im, 3, 690,  710, $igst_rate, $black);
	imagestring($im, 3, 900,  710, $igst_amt, $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);	
	}
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	$savepath = base_url()."uploads/blended_invoice/user/".$zone_code."/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/blended_invoice/user/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice/user/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/blended_invoice/user/'.$zone_code.'/'.$imagename);
	imagedestroy($im);
	/****************************** image for supplier ***********************************/
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate 
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	imagestring($im, 5, 155,  70, "", $black);
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN : ".$gstin_no, $black);
	imagestring($im, 3, 22,  124, "Address : ", $black);
	imagestring($im, 3, 22,  136, $zone_address1 , $black);
	imagestring($im, 3, 22,  148, $zone_address2." ".$zone_address3, $black);
	imagestring($im, 3, 22,  160, $zone_address4, $black);
	imagestring($im, 3, 22,  172, "State : ".$state_name ,$black);
	imagestring($im, 3, 22,  184, "State Code : ".$state_code, $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 22,  248, "Course name : ".$program_name, $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	/*imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of service Recipient : ".$member_name, $black);
	imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  334, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  346, "State : ".$stateArr[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code : ".$stateArr[0]['state_no'], $black);*/
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Member no. : ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 22,  310, "Member name : ".$member_name, $black);
	
	imagestring($im, 3, 22,  322, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 22,  334, "Center name : ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 22,  346, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State code : ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : ".$mem_gstin_no, $black);
	
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Charges Towards Training Program", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
	imagestring($im, 3, 300,  660, "Central Tax:", $black);
	imagestring($im, 3, 690,  660, $cgst_rate, $black);
	imagestring($im, 3, 900,  660, $cgst_amt, $black);
	imagestring($im, 3, 300,  672, "State Tax:", $black);
	imagestring($im, 3, 690,  672, $sgst_rate, $black);
	imagestring($im, 3, 900,  672, $sgst_amt, $black);
	imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
	imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
	imagestring($im, 3, 690,  710, $igst_rate, $black);
	imagestring($im, 3, 900,  710, $igst_amt, $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);	
	}
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);

	$savepath = base_url()."uploads/blended_invoice/supplier/".$zone_code."/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/blended_invoice/supplier/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice/supplier/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/blended_invoice/supplier/'.$zone_code.'/'.$imagename);
	imagedestroy($im);
	return $attachpath = "uploads/blended_invoice/user/".$zone_code."/".$imagename;
}



/******************Offline seat allocation****************************/

function getseat_j($exam_code = NULL, $sel_center = NULL, $sel_venue = NULL, $sel_date = NULL, $sel_time = NULL , $ex_prd = NULL , $sel_subject = NULL,$capacity = NULL, $admit_card_id =NULL)
{
		$flag=$seat_count=0;
		$CI = & get_instance();
		$seat_number='';
		//$CI->load->model('my_model');
		if($exam_code !=NULL && $sel_center !=NULL && $sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $ex_prd !=NULL && $sel_subject != NULL && $capacity != NULL && $admit_card_id!='NULL')
		{
			$CI->db->trans_start();	
			/*$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
		SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt , '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
		FROM seat_allocation
		WHERE exam_code = '.$exam_code.' AND exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'" HAVING new_seat_cnt <= '.$capacity.'' ;*/
		##### check if seat number alredy exist in seat allocation table for admit card id######
		$seat_count=$CI->master_model->getRecords('seat_allocation',array('admit_card_id'=>$admit_card_id));
		if(count($seat_count) <=0)
		{
			$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
			SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt, '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
			FROM seat_allocation
			WHERE  exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'" HAVING new_seat_cnt <= '.$capacity.'' ;
				
				//	echo $CI->db->last_query();exit;
					$CI->db->query($sql);
					if($last_id=$CI->db->insert_id())
					{
						$seat_count=$CI->master_model->getRecords('seat_allocation',array('id'=>$last_id),'seat_no');
						if(count($seat_count) <=0)
						{
							$seat_number='';
							$log_title ="Seat Allocation log 4";
							$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
							$rId = $admit_card_id ;
							$regNo = $admit_card_id;
							$log_data['title'] = $log_title;
							$log_data['description'] = $log_message.'|'.$CI->db->last_query();
							$log_data['regid'] = $rId;
							$log_data['regnumber'] = $regNo;
							$CI->db->insert('userlogs', $log_data);
						}
						else
						{
							$seat_number=$seat_count[0]['seat_no'];
						}
					}
					else
					{
						$log_title ="Seat Allocation log 3";
						$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
						$rId = $admit_card_id ;
						$regNo = $admit_card_id;
						$log_data['title'] = $log_title;
						$log_data['description'] = $log_message.'|'.$CI->db->last_query();;
						$log_data['regid'] = $rId;
						$log_data['regnumber'] = $regNo;
						$CI->db->insert('userlogs', $log_data);
					}
					
					
					if($seat_number=='')
					{
							$sql='INSERT INTO seat_allocation (seat_no, exam_code, exam_period, center_code, subject_code, venue_code, session, admit_card_id,date)
										SELECT 1 + IFNULL(max(seat_no), 0) as new_seat_cnt, '.$exam_code.', '.$ex_prd.', '.$sel_center.', '.$sel_subject.', "'.$sel_venue.'", "'.$sel_time.'", '.$admit_card_id.',"'.$sel_date.'"
										FROM seat_allocation
										WHERE  exam_period = '.$ex_prd.' AND center_code = '.$sel_center.' AND venue_code = "'.$sel_venue.'" AND date= "'.$sel_date.'" AND session = "'.$sel_time.'" HAVING new_seat_cnt <= '.$capacity.'' ;
							$CI->db->query($sql);	
							if($last_id=$CI->db->insert_id())
							{
								$seat_count=$CI->master_model->getRecords('seat_allocation',array('id'=>$last_id),'seat_no');
								if(count($seat_count) >0)
								{		
									$seat_number=$seat_count[0]['seat_no'];
								}
							}
							$log_title ="Seat Allocation log 5";
							$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
							$rId = $admit_card_id ;
							$regNo = $admit_card_id;
							$log_data['title'] = $log_title;
							$log_data['description'] = $log_message.'|'.$CI->db->last_query();;
							$log_data['regid'] = $rId;
							$log_data['regnumber'] = $regNo;
							$CI->db->insert('userlogs', $log_data);
							//	echo $CI->db->last_query();exit;
					}
				
		}
		else
		{
			$log_title ="Seat Allocation log 2";
			$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
			$rId = $admit_card_id ;
			$regNo = $admit_card_id;
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message.'|'.$CI->db->last_query();;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
		}
		
		
		$CI->db->trans_complete();
		return $seat_number;
		}
		else
		{
			$log_title ="Seat Allocation log 1";
			$log_message = $exam_code.'|'.$sel_center.'|'.$sel_venue.'|'.$sel_date.'|'.$sel_time.'|'.$ex_prd.'|'.$sel_subject.'|'.$capacity.'|'.$admit_card_id ;
			$rId = $admit_card_id ;
			$regNo = $admit_card_id;
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
								
			return $seat_number;
		} 
	}
	
function check_capacity_j($sel_venue = NULL, $sel_date = NULL, $sel_time = NULL,$sel_center = NULL)
{
		$seat_flag=1;
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($sel_venue !=NULL && $sel_date !=NULL && $sel_time !=NULL && $sel_center !=NULL)
		{
			//$CI->db->join('admit_card_details');
			//$sql='select (SELECT session_capacity FROM `venue_master` WHERE `exam_date` = "'.$sel_date.'" AND `venue_code` = "'.$sel_venue.'" AND `session_time` = "'.$sel_time.'") as session_capacity, (SELECT COUNT(*) FROM admit_card_details WHERE `exam_date` = "'.$sel_date.'" AND `venueid` = "'.$sel_venue.'" AND `time` = "'.$sel_time.'") as admit_card_Count where session_capacity';
		   //$CI->db->query($sql);
		   $CI->db->trans_start();
			$seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');
			if(count($seat_count) > 0)
			{
				$CI->db->where('pwd !=','');
				$CI->db->where('seat_identification !=','');
				$actual_admit_card_Count=$CI->master_model->getRecords('admit_card_details',array('exam_date'=>$sel_date,'venueid'=>$sel_venue,'time'=>$sel_time,'center_code'=>$sel_center));		
				
				$admit_card_Count=$CI->master_model->getRecords('seat_allocation',array('date'=>$sel_date,'venue_code'=>$sel_venue,'session'=>$sel_time,'center_code'=>$sel_center));	
					
				if($seat_count[0]['session_capacity'] <=(count($admit_card_Count))|| $seat_count[0]['session_capacity'] <=(count($actual_admit_card_Count)))
				{
					$seat_flag=0;
				}
				else if((count($admit_card_Count) > $seat_count[0]['session_capacity']) || (count($actual_admit_card_Count))>$seat_count[0]['session_capacity'])
				{
					$seat_flag=0;
				}
				/*if(!(count($admit_card_Count) < $seat_count[0]['session_capacity']))
				{
					$seat_flag=0;
				}*/
			}
			$CI->db->trans_complete();   
			//echo $CI->db->last_query().'<br>';
			//return $seat_number;
		}
		
		return $seat_flag;  
		
	}
	
// Invoice with group by fee filteration [dynamic fee structure]
function custom_genarate_draexam_invoice_new($invoice_no){ 
	$CI = & get_instance();
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	//echo $CI->db->last_query();exit;
	if(!$invoice_info){ 
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
		return '';
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	//echo $CI->db->last_query();exit;
	if(!$inst_details){ 
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
		return '';
	}
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
	}
	
	// image for DRA Institute
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	imagestring($im, 5, 155,  70, "", $black);
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	
	
	$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}
	
	$regnumber_arr = array();
	$member_type_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber,registrationtype');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}
	
	$app_category = array();
	$base_total_R=0;
	$base_total_S1=0;
	$base_total_B1=0;
	
	foreach($regnumber_arr as $regnumber_arr){
		
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category,member_type');
		if(isset($dra_eligible_master[0]['app_category']))
		{
			$app_category[$dra_eligible_master[0]['member_type']][] = $dra_eligible_master[0]['app_category'];
		}else
		{
			$mtype = $CI->master_model->getRecords('dra_members',array('regnumber'=>$regnumber_arr),'registrationtype');
			$app_category[$mtype[0]['registrationtype']][] = 'B1';
		}
	}
	$unique_app_category = $app_category;
	$y = 560;
	$i=1;
	
	//echo "<pre>";
	//print_r($unique_app_category);
	
	foreach($unique_app_category as $key => $val){ 
		foreach( $val as $keyItem => $valKey){
		  $cat = 'B1';
		  if($key=='NM' && ($valKey == 'R' || $valKey == 'B1')){ 
			$cat = 'B1';
		  }
		  if($key=='NM' && $valKey == 'S1'){ 
			$cat = 'S1';
		  }
		  if($key=='O' && ($valKey == 'R' || $valKey == 'B1')){ 
			$cat = 'B1';
		  }
		  if($key=='O' && $valKey == 'S1'){  
			$cat = 'S1';
		  }
		   $dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>$key),'fee_amount');
		   $category_arr[$key][$cat][]=$dra_fee_master[0]['fee_amount'];
		}
	}
	
		
	$member_fee_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($category_arr)), 0);
	
	$category_array=array_count_values($member_fee_array);
	
	foreach($category_array as $keyfee => $valcount){
		imagestring($im, 3, 690,  $y, $keyfee, $black); // Rate
		imagestring($im, 3, 780,  $y, $valcount, $black); // Quantity
		$base_total_R = $keyfee * $valcount;
		$base_total_R = number_format($base_total_R, 2, '.', '');
		imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
		$y = $y+20;
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	//$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	$imagename = 'user.jpg';
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	
	
	imagedestroy($im);
	
	
	/****************************** image for supplier ***********************************/
	
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255);   // white
	
	$black = imagecolorallocate($im, 0, 0, 0);                  // black
	
	//imageline ($im,   x1,  y1, x2, y2, $black);
	// x1 and y1 => point one co-ordinate
	// x2 and y2 => point two co-ordinate
	
	imageline ($im,   20,  20, 980, 20, $black); // line-1
	imageline ($im,   20,  980, 980, 980, $black); // line-2
	imageline ($im,   20,  20, 20, 980, $black); // line-3
	imageline ($im,   980, 20, 980, 980, $black); // line-4
	imageline ($im,   20,  60, 980, 60, $black); // line-5
	imageline ($im,   20,  100, 980, 100, $black); // line-6
	//imageline ($im,   20,  250, 980, 250, $black); // line-7
	imageline ($im,   20,  273, 980, 273, $black); // line-7
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
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	
	imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  212, "Invoice No.: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice: ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction No.: ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 22,  334, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  358, "State Code: ".$invoice_info[0]['state_code'], $black);
	if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id: ".$gstn_no, $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate per unit", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);//Accounting
	
	/*$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}*/
	
	/*$regnumber_arr = array();
	$member_type_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber,registrationtype');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}*/
	
	$app_category = array();
	$base_total_R=0;
	$base_total_S1=0;
	$base_total_B1=0;
	
	/*foreach($regnumber_arr as $regnumber_arr){
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category,member_type');
		if(isset($dra_eligible_master[0]['app_category'])){
			$app_category[$dra_eligible_master[0]['member_type']][] = $dra_eligible_master[0]['app_category'];
		}else{
			$mtype = $CI->master_model->getRecords('dra_members',array('regnumber'=>$regnumber_arr),'registrationtype');
			$app_category[$mtype[0]['registrationtype']][] = 'B1';
		}
	}*/
	
	$unique_app_category = $app_category;
	$y = 560;
	$i=1;
	
	/*foreach($unique_app_category as $key => $val){
		foreach( $val as $keyItem => $valKey){
		  $cat = 'B1';
		  if($key=='NM' && ($valKey == 'R' || $valKey == 'B1')){
			$cat = 'B1';
		  }
		  if($key=='NM' && $valKey == 'S1'){
			$cat = 'S1';
		  }
		  if($key=='O' && ($valKey == 'R' || $valKey == 'B1')){
			$cat = 'B1';
		  }
		  if($key=='O' && $valKey == 'S1'){
			$cat = 'S1';
		  }
			
			
		   $dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>$key),'fee_amount');
		   $category_arr[$key][$cat][]=$dra_fee_master[0]['fee_amount'];
		}
	}*/
	
/*	$size_category_arr = sizeof($category_arr);
	if($size_category_arr > 0 && is_array($category_arr)){*/
		//$member_fee_array = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($category_arr)), 0);
		if(sizeof($member_fee_array) > 0 && is_array($member_fee_array)){
			$category_array=array_count_values($member_fee_array);
			foreach($category_array as $keyfee => $valcount){
				imagestring($im, 3, 690,  $y, $keyfee, $black); // Rate
				imagestring($im, 3, 780,  $y, $valcount, $black); // Quantity
				$base_total_R = $keyfee * $valcount;
				$base_total_R = number_format($base_total_R, 2, '.', '');
				imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
				$y = $y+20;
			}
		}
	//}
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " Only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	//$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	$imagename = 'supplier.jpg';
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}

function custome_generate_bulk_examinvoice_new_design($id){ 
	$CI = & get_instance();
	
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$id,'app_type'=>'Z'));
	$institute_info = $CI->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
	
	$state_info = $CI->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
	$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		return custom_genarate_exam_invoice_jk($invoice_no);
		exit;
	} 
	
	if($institute_info[0]['ste_code'] == 'MAH'){
		$wordamt = bulk_amtinword($invoice_info[0]['cs_total']);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
		$wordamt = bulk_amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	
	
	
	
	
	/******************************* Image for supplier *****************************/
	
	
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	
	imagestring($im, 3, 40,  280, "Name of the Recipient: ".$institute_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  300, "Address: ".$institute_info[0]['address1']." ".$institute_info[0]['address2'], $black);
	imagestring($im, 3, 40,  320, $institute_info[0]['address3']." ".$institute_info[0]['address4'], $black);
	imagestring($im, 3, 40,  340, $institute_info[0]['address5']." ".$institute_info[0]['address6'], $black);
	
	imagestring($im, 3, 40,  360, "State: ".$state_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_info[0]['state_no'], $black);
	imagestring($im, 3, 40,  400, "GST No: ".$institute_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	echo '>>'.$institute_info[0]['gstin_no'];
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
	
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	
	imagestring($im, 3, 45,  660, "Less", $black);
	imagestring($im, 3, 118,  660, "Discount -", $black);
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, $invoice_info[0]['disc_amt'], $black);
	
	imagestring($im, 3, 118,  680, "NET-", $black);
	imagestring($im, 3, 690,  680, "-", $black);
	imagestring($im, 3, 900,  680, number_format($net_amt, 2, '.', '') , $black);
	
	
	if($institute_info[0]['ste_code'] == 'MAH'){
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
	
	if($institute_info[0]['ste_code'] != 'MAH'){
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
	if($institute_info[0]['ste_code'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
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
	
	
	
	$savepath = base_url()."uploads/bulkexaminvoice/supplier/";
	
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "bulk_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	
	
	imagepng($im,"uploads/bulkexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bulkexaminvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/bulkexaminvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/bulkexaminvoice/supplier/".$imagename; 
}

function custom_genarate_draexam_invoice_new_design($invoice_no){ 
	$CI = & get_instance();
	
// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	if(!$invoice_info){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
		return '';
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	if(!$inst_details){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
		return '';
	}
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
	}
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	if($invoice_info[0]['center_code'] != 0){
		
		$center = $CI->master_model->getRecords('agency_center',array('center_id'=>$invoice_info[0]['center_code']));
		
		$state_desc = $CI->master_model->getRecords('state_master',array('state_code'=>$center[0]['state']));
		$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$center[0]['city']));
		
		
		
		$address1 = $center[0]['address1'];
		$address2 = $center[0]['address2'];
		$pincode = $center[0]['pincode'];
		
		$state = $state_desc[0]['state_name'];
		$state_code = $state_desc[0]['state_no'];
		$city  = $city_desc[0]['city_name'];
	}else{
		$address1 = $inst_details[0]['address1'];
		$address2 = $inst_details[0]['address2'];
		$pincode = $inst_details[0]['pin_code'];
		$state = $invoice_info[0]['state_name'];
		$state_code = $invoice_info[0]['state_code'];
		
		$agency_city = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']));
		
		if($agency_city[0]['address6']!=''){
			if(is_int($agency_city[0]['address6'])){
				$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$agency_city[0]['address6']));
				$city = $city_desc[0]['city_name'];
			}else{
				$city = $agency_city[0]['address6'];
			}
		}else{
			$city  = '-';
		}
	}
	
		
	
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
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$address1, $black);
	imagestring($im, 3, 40,  300, $address2, $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$pincode, $black);
	imagestring($im, 3, 40,  360, "State: ".$state, $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  400, "City: ".$city, $black);
	imagestring($im, 3, 40,  420, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	
	
	$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}
	
	$regnumber_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}
	
	$app_category = array();
	
	$unit_R=$base_total_R=0;
	$unit_S1=$base_total_S1=0;
	$unit_B1=$base_total_B1=0;
	foreach($regnumber_arr as $regnumber_arr){
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category');
		
		if(count($dra_eligible_master) > 0){
			if($dra_eligible_master[0]['app_category'] == 'R' || $dra_eligible_master[0]['app_category'] == ''){
				$unit_R=$unit_R+1;
			}
			if($dra_eligible_master[0]['app_category'] == 'S1'){
				$unit_S1=$unit_S1+1	;
			}
			if($dra_eligible_master[0]['app_category'] == 'B1'){
				$unit_B1=$unit_B1+1;
			}
		}
		else{
			$unit_R=$unit_R+1;
		}
	
		if(isset($dra_eligible_master[0]['app_category'])){
			if($dra_eligible_master[0]['app_category'] == 'R'){
				$app_category[] = 'B1'; 
			}else{
				$app_category[] = $dra_eligible_master[0]['app_category'];
			}
		}else{
			$app_category[] = 'B1';
		}
	}
	$unique_app_category = array_values(array_unique($app_category));
	$loop =  sizeof($unique_app_category);
	echo '<pre>';
	print_r($unique_app_category);
	echo '<br/>';
	$y = 600;
	for($i=0;$i<$loop;$i++){
		$cat = 'B1';
		if($unique_app_category[$i] == 'R' || $unique_app_category[$i] == 'B1'){
			$cat = 'B1';
		}
		if($unique_app_category[$i] == 'S1'){
			$cat = 'S1';
		}
		$dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>'NM'),'fee_amount');
		
		echo $CI->db->last_query();
		echo '<br/>';
		echo '<br/>';
		
		imagestring($im, 3, 690,  $y, $dra_fee_master[0]['fee_amount'], $black); // Rate
		
		if($unit_R!=0 && ($cat == 'R' || $cat == 'B1')){
			imagestring($im, 3, 820,  $y, $unit_R, $black); // Quantity
			$base_total_R = $dra_fee_master[0]['fee_amount'] * $unit_R;
			$base_total_R = number_format($base_total_R, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
			$y = $y+20;
			$unit_R = 0;
		}elseif($unit_S1!=0  && $cat == 'S1'){
			imagestring($im, 3, 820,  $y, $unit_S1, $black); // Quantity
			$base_total_S1 = $dra_fee_master[0]['fee_amount'] * $unit_S1;
			$base_total_S1 = number_format($base_total_S1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_S1, $black); // Total
			$y = $y+20;
			$unit_S1 = 0;
			
		}elseif($unit_B1!=0  && $cat == 'B1'){
			imagestring($im, 3, 820,  $y, $unit_B1, $black); // Quantity
			$base_total_B1 = $dra_fee_master[0]['fee_amount'] * $unit_B1;
			$base_total_B1 = number_format($base_total_B1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_B1, $black); // Total
			$y = $y+20;
			$unit_B1 = 0;
			
		}
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
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
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$address1, $black);
	imagestring($im, 3, 40,  300, $address2, $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$pincode, $black);
	imagestring($im, 3, 40,  360, "State: ".$state, $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  400, "City: ".$city, $black);
	imagestring($im, 3, 40,  420, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	
	
	$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}
	
	$regnumber_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}
	
	$app_category = array();
	
	$unit_R=$base_total_R=0;
	$unit_S1=$base_total_S1=0;
	$unit_B1=$base_total_B1=0;
	foreach($regnumber_arr as $regnumber_arr){
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category');
		
		if(count($dra_eligible_master) > 0){
			if($dra_eligible_master[0]['app_category'] == 'R'){
				$unit_R=$unit_R+1;
			}
			if($dra_eligible_master[0]['app_category'] == 'S1'){
				$unit_S1=$unit_S1+1	;
			}
			if($dra_eligible_master[0]['app_category'] == 'B1'){
				$unit_B1=$unit_B1+1;
			}
		}
		else{
			$unit_B1=$unit_B1+1;	
		}
	
		if(isset($dra_eligible_master[0]['app_category'])){
			$app_category[] = $dra_eligible_master[0]['app_category'];
		}else{
			$app_category[] = 'B1';
		}
	}
	$unique_app_category = array_values(array_unique($app_category));
	$loop =  sizeof($unique_app_category);
	$y = 600;
	for($i=0;$i<$loop;$i++){
		$cat = 'B1';
		if($unique_app_category[$i] == 'R' || $unique_app_category[$i] == 'B1'){
			$cat = 'B1';
		}
		if($unique_app_category[$i] == 'S1'){
			$cat = 'S1';
		}
		$dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>'NM'),'fee_amount');
		imagestring($im, 3, 690,  $y, $dra_fee_master[0]['fee_amount'], $black); // Rate
		
		if($unit_R!=0 && ($cat == 'R' || $cat == 'B1')){
			imagestring($im, 3, 820,  $y, $unit_R, $black); // Quantity
			$base_total_R = $dra_fee_master[0]['fee_amount'] * $unit_R;
			$base_total_R = number_format($base_total_R, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
			$y = $y+20;
			$unit_R = 0;
		}elseif($unit_S1!=0  && $cat == 'S1'){
			imagestring($im, 3, 820,  $y, $unit_S1, $black); // Quantity
			$base_total_S1 = $dra_fee_master[0]['fee_amount'] * $unit_S1;
			$base_total_S1 = number_format($base_total_S1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_S1, $black); // Total
			$y = $y+20;
			$unit_S1 = 0;
			
		}elseif($unit_B1!=0  && $cat == 'B1'){
			imagestring($im, 3, 820,  $y, $unit_B1, $black); // Quantity
			$base_total_B1 = $dra_fee_master[0]['fee_amount'] * $unit_B1;
			$base_total_B1 = number_format($base_total_B1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_B1, $black); // Total
			$y = $y+20;
			$unit_B1 = 0;
			
		}
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}

function custom_genarate_draexam_invoice_new_design_swati($invoice_no){ 
	$CI = & get_instance();
	
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	if(!$invoice_info){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
		return '';
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	if(!$inst_details){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
		return '';
	}
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
		//$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	if($invoice_info[0]['center_code'] != 0){
		
		$center = $CI->master_model->getRecords('agency_center',array('center_id'=>$invoice_info[0]['center_code']));
		
		$state_desc = $CI->master_model->getRecords('state_master',array('state_code'=>$center[0]['state']));
		$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$center[0]['city']));
		
		
		
		$address1 = $center[0]['address1'];
		$address2 = $center[0]['address2'];
		$pincode = $center[0]['pincode'];
		
		$state = $state_desc[0]['state_name'];
		$state_code = $state_desc[0]['state_no'];
		$city  = $city_desc[0]['city_name'];
	}else{
		$ins_name = 'IL&FS Skills Development Corporation Limited';
		$address1 = 'Trade Star Building, 4th Floor, B  Wing, Andheri - Kurla Road';
		$address2 = 'J B Nagar, Andheri (East), Mumbai ';
		$pincode = '400059 ';
		$state = 'MAHARASHTRA';
		$state_code = 27;
		
		$agency_city = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']));
		
		if($agency_city[0]['address6']!=''){
			if(is_int($agency_city[0]['address6'])){
				$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$agency_city[0]['address6']));
				$city = $city_desc[0]['city_name'];
			}else{
				$city = $agency_city[0]['address6'];
			}
		}else{
			$city  = '-';
		}
	}
	$city = 'MUMBAI';
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
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
    imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	
	
	//imagestring(image,font,x,y,string,color);
	$year = date('Y');
    imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
    
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
    imagestring($im, 3, 22,  262, "Name of the Institute: ".$mem_info[0]['inst_name'], $black);
    imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['main_address1'], $black);
    imagestring($im, 3, 22,  286, $mem_info[0]['main_address2'], $black);
    imagestring($im, 3, 22,  298, $mem_info[0]['main_city']."-".$mem_info[0]['main_pincode'], $black);
    imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
    imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
    if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
    imagestring($im, 3, 22,  334, "GSTIN / Unique Id: ".$gstn_no, $black);
    //imagestring($im, 3, 22,  334, "GSTIN / Unique Id ", $black);//: ZZAAAA0000A1ZS
    //imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
    imagestring($im, 3, 100,  530, "Description of Service", $black);
    imagestring($im, 3, 570,  508, "Accounting ", $black);
    imagestring($im, 3, 570,  520, "code", $black);
    imagestring($im, 3, 570,  532, "of Service", $black);
    imagestring($im, 3, 665,  530, "Rate per unit", $black);
    imagestring($im, 3, 780,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total", $black);
	
	
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
	
	imagestring($im, 3, 690,  560, $invoice_info[0]['fresh_fee'], $black); // Rate
	imagestring($im, 3, 780,  560, $invoice_info[0]['qty'], $black); // Quantity [unit]
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
        imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
        imagestring($im, 3, 300,  660, "Central Tax:", $black);
        imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
        imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
        imagestring($im, 3, 300,  672, "State Tax:", $black);
        imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
        imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
        
        imagestring($im, 3, 100,  700, "For inter-state", $black);
        imagestring($im, 3, 100,  710, "Supply", $black);
        imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
        imagestring($im, 3, 690,  710, "-", $black);
        imagestring($im, 3, 900,  710, "-", $black);
    }
    // && $invoice_info[0]['state_of_center'] != 'JAM'
    if($invoice_info[0]['state_of_center'] != 'MAH'){
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
        imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
        imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
    }
    
    
    imagestring($im, 3, 400,  600, "Total", $black);
    if($invoice_info[0]['state_of_center'] == 'MAH'){
        imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
    }elseif($invoice_info[0]['state_of_center'] != 'MAH'){
        imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
    }
		
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " only", $black);
    imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
    imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 300,  900, "Y/N", $black);
    imagestring($im, 3, 350,  900, "NO", $black);
    imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
    imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
    imagestring($im, 3, 300,  932, "% ---", $black);
    imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/drainvoice/user/";
    $ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
    $imagename = $mem_info[0]['id']."_".$ino.".jpg";
    
    $update_data = array('invoice_image' => $imagename);
    $CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
    
    imagepng($im,"uploads/drainvoice/user/".$imagename);
    $png = @imagecreatefromjpeg('assets/images/sign.jpg');
    $jpeg = @imagecreatefromjpeg("uploads/drainvoice/user/".$imagename);
    @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
    imagepng($im, 'uploads/drainvoice/user/'.$imagename);
    
    imagedestroy($im);  
	
	
	/*********************** Image for supplier *************************************/
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	//imageline ($im,   x1,  y1, x2, y2, color); 
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
    imageline ($im,   860,  770, 980, 770, $black); // line-19
	
	
	
	//imagestring(image,font,x,y,string,color);
	$year = date('Y');
    imagestring($im, 5, 455,  30, "Tax Invoice cum receipt", $black);
    
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
    imagestring($im, 3, 22,  262, "Name of the Institute: ".$mem_info[0]['inst_name'], $black);
    imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['main_address1'], $black);
    imagestring($im, 3, 22,  286, $mem_info[0]['main_address2'], $black);
    imagestring($im, 3, 22,  298, $mem_info[0]['main_city']."-".$mem_info[0]['main_pincode'], $black);
    imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
    imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
    if($invoice_info[0]['gstin_no']!=''){$gstn_no = $invoice_info[0]['gstin_no'];}else{$gstn_no = '---';}
    imagestring($im, 3, 22,  334, "GSTIN / Unique Id: ".$gstn_no, $black);
    //imagestring($im, 3, 22,  334, "GSTIN / Unique Id ", $black);//: ZZAAAA0000A1ZS
    //imagestring($im, 3, 22,  346, "Exam code : ".$invoice_info[0]['exam_code'],$black);
	
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
    imagestring($im, 3, 100,  530, "Description of Service", $black);
    imagestring($im, 3, 570,  508, "Accounting ", $black);
    imagestring($im, 3, 570,  520, "code", $black);
    imagestring($im, 3, 570,  532, "of Service", $black);
    imagestring($im, 3, 665,  530, "Rate per unit", $black);
    imagestring($im, 3, 780,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total", $black);
	
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
	imagestring($im, 3, 590,  600, $invoice_info[0]['service_code'], $black);
	
	
	imagestring($im, 3, 690,  600, $invoice_info[0]['fresh_fee'], $black); // Rate
	imagestring($im, 3, 820,  600, $invoice_info[0]['qty'], $black); // Quantity [unit]
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black); // Total
	
	
	
	
	
		
	if($invoice_info[0]['state_of_center'] == 'MAH'){
        imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
        imagestring($im, 3, 300,  660, "Central Tax:", $black);
        imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
        imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
        imagestring($im, 3, 300,  672, "State Tax:", $black);
        imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
        imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
        
        imagestring($im, 3, 100,  700, "For inter-state", $black);
        imagestring($im, 3, 100,  710, "Supply", $black);
        imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
        imagestring($im, 3, 690,  710, "-", $black);
        imagestring($im, 3, 900,  710, "-", $black);
    }
    // && $invoice_info[0]['state_of_center'] != 'JAM'
    if($invoice_info[0]['state_of_center'] != 'MAH'){
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
        imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
        imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
    }
    
    
    imagestring($im, 3, 500,  780, "Total", $black);
    if($invoice_info[0]['state_of_center'] == 'MAH'){
        imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
    }elseif($invoice_info[0]['state_of_center'] != 'MAH'){
        imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
    }
	
	
	
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$amt_in_words. " only", $black);
    imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
    imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 300,  900, "Y/N", $black);
    imagestring($im, 3, 350,  900, "NO", $black);
    imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
    imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
    imagestring($im, 3, 300,  932, "% ---", $black);
    imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	
    @imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
    imagepng($im, 'uploads/drainvoice/supplier/'.$imagename);
    
    imagedestroy($im);
    
    return $attachpath = "uploads/drainvoice/user/".$imagename;
}



function custom_genarate_draexam_invoice_new_design_196($invoice_no){
	$CI = & get_instance();
	
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	if(!$invoice_info){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
		return '';
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	if(!$inst_details){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
		return '';
	}
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
	}
	
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
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 40,  300, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 40,  360, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  400, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	
	
	$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}
	
	$regnumber_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}
	
	$app_category = array();
	
	$unit_R=$base_total_R=0;
	$unit_S1=$base_total_S1=0;
	$unit_B1=$base_total_B1=0;
	foreach($regnumber_arr as $regnumber_arr){
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category');
		
		if(count($dra_eligible_master) > 0){
			if($dra_eligible_master[0]['app_category'] == 'R'){
				$unit_R=$unit_R+1;
			}
			if($dra_eligible_master[0]['app_category'] == 'S1'){
				$unit_S1=$unit_S1+1	;
			}
			if($dra_eligible_master[0]['app_category'] == 'B1'){
				$unit_B1=$unit_B1+1;
			}
		}
		else{
			$unit_B1=$unit_B1+1;	
		}
	
		if(isset($dra_eligible_master[0]['app_category'])){
			$app_category[] = $dra_eligible_master[0]['app_category'];
		}else{
			$app_category[] = 'B1';
		}
	}
	$unique_app_category = array_values(array_unique($app_category));
	$loop =  sizeof($unique_app_category);
	$y = 600;
	for($i=0;$i<$loop;$i++){
		$cat = 'B1';
		if($unique_app_category[$i] == 'R' || $unique_app_category[$i] == 'B1'){
			$cat = 'B1';
		}
		if($unique_app_category[$i] == 'S1'){
			$cat = 'S1';
		}
		$dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>'NM'),'fee_amount');
		imagestring($im, 3, 690,  $y, $dra_fee_master[0]['fee_amount'], $black); // Rate
		
		if($unit_R!=0 && ($cat == 'R' || $cat == 'B1')){
			imagestring($im, 3, 820,  $y, $unit_R, $black); // Quantity
			$base_total_R = $dra_fee_master[0]['fee_amount'] * $unit_R;
			$base_total_R = number_format($base_total_R, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
			$y = $y+20;
			$unit_R = 0;
		}elseif($unit_S1!=0  && $cat == 'S1'){
			imagestring($im, 3, 820,  $y, $unit_S1, $black); // Quantity
			$base_total_S1 = $dra_fee_master[0]['fee_amount'] * $unit_S1;
			$base_total_S1 = number_format($base_total_S1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_S1, $black); // Total
			$y = $y+20;
			$unit_S1 = 0;
			
		}elseif($unit_B1!=0  && $cat == 'B1'){
			imagestring($im, 3, 820,  $y, $unit_B1, $black); // Quantity
			$base_total_B1 = $dra_fee_master[0]['fee_amount'] * $unit_B1;
			$base_total_B1 = number_format($base_total_B1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_B1, $black); // Total
			$y = $y+20;
			$unit_B1 = 0;
			
		}
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
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
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$inst_details[0]['address1'], $black);
	imagestring($im, 3, 40,  300, $inst_details[0]['address2'], $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$inst_details[0]['pin_code'], $black);
	imagestring($im, 3, 40,  360, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  400, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	
	
	$dra_payment_transaction = $CI->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'id');
	
	$dra_member_payment_transaction = $CI->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$dra_payment_transaction[0]['id']));
	
	$memexamid = array();
	foreach($dra_member_payment_transaction as $dra_member_payment_transaction){
		$memexamid[] = $dra_member_payment_transaction['memexamid'];
	}
	
	$regnumber_arr = array();
	foreach($memexamid as $memexamid){
		$CI->db->join('dra_member_exam','dra_member_exam.regid = dra_members.regid');
		$dra_member_exam = $CI->master_model->getRecords('dra_members',array('dra_member_exam.id'=>$memexamid),'regnumber');
		$regnumber_arr[] = $dra_member_exam[0]['regnumber'];
	}
	
	$app_category = array();
	
	$unit_R=$base_total_R=0;
	$unit_S1=$base_total_S1=0;
	$unit_B1=$base_total_B1=0;
	foreach($regnumber_arr as $regnumber_arr){
		$dra_eligible_master = $CI->master_model->getRecords('dra_eligible_master',array('member_no'=>$regnumber_arr,'exam_code'=>$invoice_info[0]['exam_code'],'eligible_period'=>$invoice_info[0]['exam_period']),'app_category');
		
		if(count($dra_eligible_master) > 0){
			if($dra_eligible_master[0]['app_category'] == 'R'){
				$unit_R=$unit_R+1;
			}
			if($dra_eligible_master[0]['app_category'] == 'S1'){
				$unit_S1=$unit_S1+1	;
			}
			if($dra_eligible_master[0]['app_category'] == 'B1'){
				$unit_B1=$unit_B1+1;
			}
		}
		else{
			$unit_B1=$unit_B1+1;	
		}
	
		if(isset($dra_eligible_master[0]['app_category'])){
			$app_category[] = $dra_eligible_master[0]['app_category'];
		}else{
			$app_category[] = 'B1';
		}
	}
	$unique_app_category = array_values(array_unique($app_category));
	$loop =  sizeof($unique_app_category);
	$y = 600;
	for($i=0;$i<$loop;$i++){
		$cat = 'B1';
		if($unique_app_category[$i] == 'R' || $unique_app_category[$i] == 'B1'){
			$cat = 'B1';
		}
		if($unique_app_category[$i] == 'S1'){
			$cat = 'S1';
		}
		$dra_fee_master = $CI->master_model->getRecords('dra_fee_master',array('group_code'=>$cat,'member_category'=>'NM'),'fee_amount');
		imagestring($im, 3, 690,  $y, $dra_fee_master[0]['fee_amount'], $black); // Rate
		
		if($unit_R!=0 && ($cat == 'R' || $cat == 'B1')){
			imagestring($im, 3, 820,  $y, $unit_R, $black); // Quantity
			$base_total_R = $dra_fee_master[0]['fee_amount'] * $unit_R;
			$base_total_R = number_format($base_total_R, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_R, $black); // Total
			$y = $y+20;
			$unit_R = 0;
		}elseif($unit_S1!=0  && $cat == 'S1'){
			imagestring($im, 3, 820,  $y, $unit_S1, $black); // Quantity
			$base_total_S1 = $dra_fee_master[0]['fee_amount'] * $unit_S1;
			$base_total_S1 = number_format($base_total_S1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_S1, $black); // Total
			$y = $y+20;
			$unit_S1 = 0;
			
		}elseif($unit_B1!=0  && $cat == 'B1'){
			imagestring($im, 3, 820,  $y, $unit_B1, $black); // Quantity
			$base_total_B1 = $dra_fee_master[0]['fee_amount'] * $unit_B1;
			$base_total_B1 = number_format($base_total_B1, 2, '.', '');
			imagestring($im, 3, 900,  $y, $base_total_B1, $black); // Total
			$y = $y+20;
			$unit_B1 = 0;
			
		}
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}

function custom_genarate_dra_acc_invoice_newdesign($invoice_no){ 
	$CI = & get_instance();
	
	
	$CI->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no');
	$CI->db->join('agency_center','agency_center.center_id = payment_transaction.ref_id');
	$record = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	
	
	/*$CI->db->join('agency_center','agency_center.center_id = agency_center_payment.center_id');
	$CI->db->join('exam_invoice','exam_invoice.invoice_id = agency_center_payment.invoice_id');
	$record = $CI->master_model->getRecords('agency_center_payment',array('agency_center_payment.receipt_no'=>$receipt_no)); */
	
	//echo "<pre>";
	//print_r($record);
	//exit;
	
	if($record[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($record[0]['cs_total']);
		$total = $record[0]['cs_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	if($record[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($record[0]['igst_total']);
		$total = $record[0]['igst_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	
	

	
	$city_name = "";
	if($record[0]['invoice_flag'] == 'AS')
	{
		$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4,');
		$name_of_center = $ag_add[0]['inst_name'];
		$address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
		$address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		$dra_inst_reg = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'main_city');
		// || $dra_inst_reg[0]['main_city'] > 0
		if(is_numeric($dra_inst_reg[0]['main_city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$dra_inst_reg[0]['main_city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $dra_inst_reg[0]['main_city'];
		}

	}elseif($record[0]['invoice_flag'] == 'CS'){
		
		$name_of_center = $record[0]['city'];
		$address = $record[0]['location_address']." ".$record[0]['address1']." ".$record[0]['address2'];
		$address1 = $record[0]['address3']." ".$record[0]['address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		// || $record[0]['city'] > 0
		if(is_numeric($record[0]['city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $record[0]['city'];
		}
	}
	echo "city_name>>".$city_name;
	//exit;
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Name of Center:".$city_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	if($record[0]['gstin_no'] != '' && $record[0]['gstin_no'] != 0){
		$gstn = $record[0]['gstin_no'];
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
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626, $city_name, $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/drainvoice/user/";
	//$imagename = 'new_dra.jpg';
	$ino = str_replace("/","_",$record[0]['invoice_no']);
	$imagename = $record[0]['center_id']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/drainvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	// create image for supplier
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of Center:".$city_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	imagestring($im, 3, 670,  300, "GSTIN : 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$city_name , $black); 
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/drainvoice/supplier/";
	//$imagename = 'new_dra.jpg';
	imagepng($im,"uploads/drainvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/drainvoice/user/".$imagename;
	
	
	
	
}	




function custom_genarate_agnecy_renewal_invoice($invoice_no){ 
	$CI = & get_instance();
	
	
	$CI->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no');
	$CI->db->join('agency_center_renew','agency_center_renew.agency_renew_id = payment_transaction.ref_id');
	$record = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	/*$CI->db->join('agency_center','agency_center.center_id = agency_center_payment.center_id');
	$CI->db->join('exam_invoice','exam_invoice.invoice_id = agency_center_payment.invoice_id');
	$record = $CI->master_model->getRecords('agency_center_payment',array('agency_center_payment.receipt_no'=>$receipt_no)); */
	
	/*echo $CI->db->last_query();*/
	//echo "<pre>";
	//print_r($record);
	//exit;
	
	if($record[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinwordagnrew($record[0]['cs_total']);
		$total = $record[0]['cs_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	if($record[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinwordagnrew($record[0]['igst_total']);
		$total = $record[0]['igst_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	
	if($record[0]['center_type'] == 'T'){
		
		$center_details = $CI->master_model->getRecords('agency_center', array('center_id' => $record[0]['centers_id']));
		$center_id = $center_details[0]['center_id']; 
		$center_type = $center_details[0]['center_type'];
		$invoice_flag = $center_details[0]['invoice_flag'];
		$agency_id = $center_details[0]['agency_id'];
		
		if($invoice_flag == 'AS'){
			$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
			$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
			
			$dra_inst_reg = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'main_city');
			
			$city = $CI->master_model->getRecords('city_master',array('id'=>$dra_inst_reg[0]['main_city']),'city_name');
			
			$name_of_agency =$ag_add[0]['inst_name'];
			
			$center_result = $CI->master_model->getRecords('agency_center',array('center_id'=>$center_id),'state,gstin_no,city,address1,address2,address3,address4,location_name');
			
						
			//$name_of_center =$city;
			
			$city_name = $CI->master_model->getRecords('city_master',array('id'=>$center_result[0]['location_name'],'city_delete'=>0),'city_name');				
			$name_of_center = $city_name[0]['city_name'];	
			
			$address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
			$address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
			$state = $record[0]['state_name'];
			$state_code = $record[0]['state_code'];
			
			
			
			
			
		}elseif($invoice_flag == 'CS'){
			
			$center_details = $CI->master_model->getRecords('agency_center', array('center_id' => $record[0]['centers_id']));
			$center_id = $center_details[0]['center_id']; 
			$center_type = $center_details[0]['center_type'];
			$invoice_flag = $center_details[0]['invoice_flag'];
			$agency_id = $center_details[0]['agency_id'];
			
			
			$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
			$name_of_agency =$ag_add[0]['inst_name'];
			
			
			$center_result = $CI->master_model->getRecords('agency_center',array('center_id'=>$center_id),'state,gstin_no,city,address1,address2,address3,address4,location_name');
			
			
			$city_name = $CI->master_model->getRecords('city_master',array('id'=>$center_result[0]['location_name'],'city_delete'=>0),'city_name');
			
			
			$name_of_center = $city_name[0]['city_name'];
			$address = $center_result[0]['address1']." ".$center_result[0]['address2'];
			$address1 = $center_result[0]['address3']." ".$center_result[0]['address4'];
			
			$state_info = $CI->master_model->getRecords('state_master',array('state_code'=>$center_result[0]['state'],'state_delete'=> '0'));
			$state = $state_info[0]['state_name'];
			$state_code = $state_info[0]['state_code'];
			
			$city = $CI->master_model->getRecords('city_master',array('id'=>$center_result[0]['city']),'city_name');
			
		}
	}else{
		$CI->db->join('agency_center_renew','agency_center_renew.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
		
		$name_of_center = $ag_add[0]['inst_name'];
		$name_of_agency = $ag_add[0]['inst_name'];
		$address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
		$address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
	}
	
		
	
		
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	//imagestring($im, 3, 40,  260, "Name of Center:".$name_of_center, $black);
	imagestring($im, 3, 40,  260, "Name of Agency: ".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code : ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	if($record[0]['gstin_no'] != '' && $record[0]['gstin_no'] != 0){
		$gstn = $record[0]['gstin_no'];
	}else{
		$gstn = "-";
	}
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS ", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "charges paid towards renewal agency registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$name_of_center , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/agency_renewal_invoice/user/";
	//$imagename = 'new_dra.jpg';
	//DRN/19-20/000001
	$ino = str_replace("/","_",$record[0]['invoice_no']);
	$imagename = $record[0]['agency_id']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/agency_renewal_invoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/agency_renewal_invoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/agency_renewal_invoice/user/'.$imagename);
	imagedestroy($im);
	
	
	// create image for supplier
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	//imagestring($im, 3, 40,  260, "Name of Center:".$name_of_center, $black);
	imagestring($im, 3, 40,  260, "Name of Agency: ".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "charges paid towards renewal agency registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$name_of_center , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/agency_renewal_invoice/supplier/";
	//$imagename = 'new_dra.jpg';
	imagepng($im,"uploads/agency_renewal_invoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/agency_renewal_invoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/agency_renewal_invoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/agency_renewal_invoice/user/".$imagename;
	
}

function custom_genarate_reg_invoice_new($invoice_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}
	
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
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges paid towards ordinary membership registration", $black);
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/reginvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	echo '>>u>>'.$invoice_info[0]['invoice_no'];
	echo '<br/>';
	
	$update_data = array('invoice_image' => $imagename);
	
	
	
	imagepng($im,"uploads/reginvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/reginvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges paid towards ordinary membership registration", $black);
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/reginvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	echo '>>s>>'.$imagename;
	echo '<br/>';
	
	imagepng($im,"uploads/reginvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/reginvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/reginvoice/supplier/".$imagename;
}


function custom_genarate_reg_invoice_new123($invoice_no){ 
	$CI = & get_instance();
	
	
	$CI->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no');
	$CI->db->join('agency_center','agency_center.center_id = payment_transaction.ref_id');
	$record = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	
	
	/*$CI->db->join('agency_center','agency_center.center_id = agency_center_payment.center_id');
	$CI->db->join('exam_invoice','exam_invoice.invoice_id = agency_center_payment.invoice_id');
	$record = $CI->master_model->getRecords('agency_center_payment',array('agency_center_payment.receipt_no'=>$receipt_no)); */
	
	//echo "<pre>";
	//print_r($record);
	//exit;
	
	if($record[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($record[0]['cs_total']);
		$total = $record[0]['cs_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	if($record[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($record[0]['igst_total']);
		$total = $record[0]['igst_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	
	

	
	$city_name = "";
	$name_of_center = "";
	$name_of_agency = "";
	if($record[0]['invoice_flag'] == 'AS')
	{
		$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
		$name_of_center = $record[0]['city'];
		$name_of_agency = $ag_add[0]['inst_name'];
		$address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
		$address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		$dra_inst_reg = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'main_city');
		// || $dra_inst_reg[0]['main_city'] > 0
		if(is_numeric($record[0]['city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $dra_inst_reg[0]['main_city'];
		}

	}elseif($record[0]['invoice_flag'] == 'CS'){
		$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
		$name_of_center = $record[0]['city'];
		$name_of_agency = $ag_add[0]['inst_name'];
		$address = $record[0]['location_address']." ".$record[0]['address1']." ".$record[0]['address2'];
		$address1 = $record[0]['address3']." ".$record[0]['address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		// || $record[0]['city'] > 0
		if(is_numeric($record[0]['city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $record[0]['city'];
		}
	}
	
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	if($record[0]['gstin_no'] != '' && $record[0]['gstin_no'] != 0){
		$gstn = $record[0]['gstin_no'];
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
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$city_name , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/drainvoice/user/";
	//$imagename = 'new_dra.jpg';
	$ino = str_replace("/","_",$record[0]['invoice_no']);
	$imagename = $record[0]['center_id']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/drainvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	// create image for supplier
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	imagestring($im, 3, 670,  300, "GSTIN : 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$city_name, $black); 
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/drainvoice/supplier/";
	//$imagename = 'new_dra.jpg';
	imagepng($im,"uploads/drainvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/drainvoice/user/".$imagename;
	
	
	
	
}


function custom_genarate_reg_invoice_new_old($invoice_no){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	//	echo "<pre>";
	//print_r($invoice_info);
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	//echo "<pre>";
//	print_r($mem_info);exit;
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}
	
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
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges paid towards ordinary membership registration", $black);
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/reginvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
	imagepng($im,"uploads/reginvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/reginvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of the Recipient: ".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['address1_pr']." ".$mem_info[0]['address2_pr'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: -", $black);
	imagestring($im, 3, 40,  380, "Transaction Number: ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Charges paid towards ordinary membership registration", $black);
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
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/reginvoice/supplier/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/reginvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/reginvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/reginvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/reginvoice/user/".$imagename;
		
}

 function genarate_amp_invoice_custom1($invoice_no){
     //echo 'here';
	//$invoice_no =   ;
	//echo 'in invoice_no';
	//echo $invoice_no;
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	/*echo '<pre>';
	print_r($invoice_info);
	exit;*/
	
	$member_ref_id = $CI->master_model->getRecords('amp_payment_transaction',array('id'=>$invoice_info[0]['pay_txn_id'],'status'=>'1'),array('ref_id','payment_option'));
	
	$mem_info = $CI->master_model->getRecords('amp_candidates',array('id'=>$member_ref_id[0]['ref_id']),'id,name,address1,address2,address3,address4,city,state,pincode_address,sponsor,sponsor_bank_name,bank_address1,bank_address2,bank_address3,bank_address4,bank_city,bank_state,bank_pincode');
	
	//echo 'payment_info',print_r($member_ref_id);
	//echo 'payment_option',print_r($member_ref_id[0]['payment_option']);
	
	$fee_amount1 = '';
	$fee_amount2 = '';
	//get fee amount 
	if($member_ref_id[0]['payment_option'] == '4') //full fee
	{
		$fee_amount1 = $CI->config->item('amp_full_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_full_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '1')//first fee
	{
		$fee_amount1 = $CI->config->item('amp_first_fee').'.00';
		$fee_amount2 = $CI->config->item('amp_first_travel_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '2')//SECOND fee
	{
		$fee_amount1 = $CI->config->item('amp_second_fee').'.00';
	}elseif($member_ref_id[0]['payment_option'] == '3')//third fee
	{
		$fee_amount1 = $CI->config->item('amp_third_fee').'.00';
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
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
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
	
	if($fee_amount2 != '')
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
	
	$savepath = base_url()."uploads/ampinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $mem_info[0]['id']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
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
		imagestring($im, 3, 40,  380, "GSTIN / Unique Id: ".$gstn_no, $black);
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
	
	if($fee_amount2 != '')
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


function custom_genarate_elearning_exam_invoice($invoice_id){ 
	//$receipt_no = '900490642';
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600 || $invoice_info[0]['exam_code'] == 16000){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770 || $invoice_info[0]['exam_code'] == 17700){
		$exam_code =177;
	}
	elseif($invoice_info[0]['exam_code'] == 1750){
		$exam_code =175;
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
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	
	/*$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	//imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	//imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	//imagestring($im, 3, 40,  300, "State of center: ".$invoice_info[0]['state_name'], $black);
	//imagestring($im, 3, 40,  320, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  300, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  320, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  340, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt."Rs Only", $black);
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
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	imagedestroy($im);*/
	
	
	
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	//imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	//imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	//imagestring($im, 3, 40,  300, "State of center: ".$invoice_info[0]['state_name'], $black);
	//imagestring($im, 3, 40,  320, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  300, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  320, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  340, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
	

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
	
	imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt."Rs Only", $black);
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
	
	return $attachpath = "uploads/examinvoice/supplier/".$imagename;
	
}

function custom_genarate_dra_invoice($invoice_no){ 
	//echo 'here';
	//exit;

	$CI = & get_instance();
	
	
	$CI->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no');
	$CI->db->join('agency_center','agency_center.center_id = payment_transaction.ref_id');
	$record = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	//echo $CI->db->last_query();
	//exit;
	
	
	
	/*$CI->db->join('agency_center','agency_center.center_id = agency_center_payment.center_id');
	$CI->db->join('exam_invoice','exam_invoice.invoice_id = agency_center_payment.invoice_id');
	$record = $CI->master_model->getRecords('agency_center_payment',array('agency_center_payment.receipt_no'=>$receipt_no)); */
	
	//echo "<pre>";
	//print_r($record);
	//exit;
	
	if($record[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword($record[0]['cs_total']);
		$total = $record[0]['cs_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	if($record[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword($record[0]['igst_total']);
		$total = $record[0]['igst_total'];
		$fee_amt =  $record[0]['fee_amt'];
	}
	
	

	
	$city_name = "";
	$name_of_center = "";
	$name_of_agency = "";
	if($record[0]['invoice_flag'] == 'AS')
	{
		$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
		$name_of_center = $record[0]['city'];
		$name_of_agency = $ag_add[0]['inst_name'];
		$address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
		$address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		$dra_inst_reg = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'main_city');
		// || $dra_inst_reg[0]['main_city'] > 0
		if(is_numeric($record[0]['city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $dra_inst_reg[0]['main_city'];
		}

	}elseif($record[0]['invoice_flag'] == 'CS'){
		$CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
		$ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
		$name_of_center = $record[0]['city'];
		$name_of_agency = $ag_add[0]['inst_name'];
		$address = $record[0]['location_address']." ".$record[0]['address1']." ".$record[0]['address2'];
		$address1 = $record[0]['address3']." ".$record[0]['address4'];
		$state = $record[0]['state_name'];
		$state_code = $record[0]['state_code'];
		
		// || $record[0]['city'] > 0
		if(is_numeric($record[0]['city'])){
			$city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
			$city_name = $city[0]['city_name'];
		}
		else{
			$city_name = $record[0]['city'];
		}
	}
	
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
	imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	if($record[0]['gstin_no'] != '' && $record[0]['gstin_no'] != 0){
		$gstn = $record[0]['gstin_no'];
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
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$city_name , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/drainvoice/user/";
	//$imagename = 'new_dra.jpg';
	$ino = str_replace("/","_",$record[0]['invoice_no']);
	$imagename = $record[0]['center_id']."_".$ino.".jpg";
	
	
	imagepng($im,"uploads/drainvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/user/'.$imagename);
	imagedestroy($im);
	//echo $imagename;
	//exit;
	
	
	// create image for supplier 
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
	imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
	
	imagestring($im, 5, 40,  220, "Details of service recipient", $black);
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address, $black);
	imagestring($im, 3, 40,  300, $address1, $black);
	imagestring($im, 3, 40,  320, "State: ".$state, $black);
	imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  360, "GST No: ".$record[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
	imagestring($im, 3, 670,  300, "GSTIN : 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration", $black);
	imagestring($im, 3, 535,  596, "999799", $black);
	imagestring($im, 3, 700,  596, $fee_amt, $black);
	imagestring($im, 3, 815,  596, "1", $black);
	imagestring($im, 3, 900,  596, $fee_amt, $black); 
	imagestring($im, 3, 535,  820, "Total", $black); 
	
	
	imagestring($im, 3, 60,  626, "1", $black);
	imagestring($im, 3, 118,  626,$city_name, $black); 
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	if($record[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 700,  626, "9% ", $black);
		imagestring($im, 3, 700,  646, "9% ", $black);
		imagestring($im, 3, 700,  666, "- ", $black);
		
		imagestring($im, 3, 900,  626, $record[0]['cgst_amt'], $black);
		imagestring($im, 3, 900,  646, $record[0]['sgst_amt'], $black);
		imagestring($im, 3, 900,  666, "- ", $black);
	}
	if($record[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 700,  626, "- ", $black);
		imagestring($im, 3, 700,  646, "- ", $black);
		imagestring($im, 3, 700,  666, "18% ", $black);
		
		imagestring($im, 3, 900,  626, "- ", $black);
		imagestring($im, 3, 900,  646, "- ", $black);
		imagestring($im, 3, 900,  666, $record[0]['igst_amt'], $black);
	}
	
	
	
	imagestring($im, 3, 900,  820, $total, $black); 
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/drainvoice/supplier/";
	//$imagename = 'new_dra.jpg';
	imagepng($im,"uploads/drainvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/drainvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/drainvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/drainvoice/user/".$imagename;
	
	
	
	
}

//garp invoice
function genarate_garp_invoice_custom($invoice_id){ 
	//$receipt_no = '900490642';
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_name'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_name'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	} 
		
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
		$exam_code = $invoice_info[0]['exam_code'];
	
	
	if($exam_code > 0){
		$exam_name_code = $exam_code;
	}else{
		$exam_name_code = $invoice_info[0]['exam_code'];
	}
	$exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
	if($exam_name[0]['exam_name'] != ''){
		$invoice_exname = $exam_name[0]['exam_name'];
	}else{
		$invoice_exname = 'GARP-FRR Exam';
	}
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	// imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	// imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
//	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
//	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
    imagestring($im, 3, 40,  360, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
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
	imagestring($im, 3, 118,  600, "Registration for GARP-FRR exam", $black);
	imagestring($im, 3, 550,  600, "999294", $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_name'] == 'MAH'){
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
	
	if($invoice_info[0]['state_name'] != 'MAH'){
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
	
	if($invoice_info[0]['total_el_amount'] > 0){
		imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
		imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
	}
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_name'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_name'] != 'MAH'){
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
	
	$savepath = base_url()."uploads/garpinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/garpinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/garpinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/garpinvoice/user/'.$imagename);
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	// imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	// imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
//	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
	

	imagestring($im, 3, 40,  530, "Sr.No.", $black);
	imagestring($im, 3, 118,  530, "Description of Service", $black);
	imagestring($im, 3, 535,  530, "Accounting ", $black);
	imagestring($im, 3, 535,  542, "code", $black);
	imagestring($im, 3, 535,  554, "of Service", $black);
	imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
	imagestring($im, 3, 808,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
	
	imagestring($im, 3, 40,  600, "1", $black);
	imagestring($im, 3, 118,  600, "Registration for GARP-FRR exam", $black);
	imagestring($im, 3, 550,  600, "999294", $black);
	imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 820,  600, 1, $black);
	imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
	
	if($invoice_info[0]['state_name'] == 'MAH'){
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
	
	if($invoice_info[0]['state_name'] != 'MAH'){
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
	if($invoice_info[0]['state_name'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_name'] != 'MAH'){
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
	
	$savepath = base_url()."uploads/garpinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/garpinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/garpinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/garpinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/garpinvoice/user/".$imagename;
	
}

	###-----generate GARP invoice number---### 
 	function custom_generate_GARP_invoice_number($invoice_id= NULL)
	{ //echo 'swa';
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_GARP_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	

function genarate_chartered_invoice_custom($invoice_id){ 
	//$receipt_no = '900490642';
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	} 
		
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
		$exam_code = $invoice_info[0]['exam_code'];
	
	
	if($exam_code > 0){
		$exam_name_code = $exam_code;
	}else{
		$exam_name_code = $invoice_info[0]['exam_code'];
	}
	$exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
	if($exam_name[0]['exam_name'] != ''){
		$invoice_exname = $exam_name[0]['exam_name'];
	}else{
		$invoice_exname = 'Chartered Accountant';
	}
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	
	if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	{
		$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
		if(count($ex_period))
		{
			$exam_period = $ex_period[0]['period'];	
		}
	}else{
		$exam_period = $exam[0]['exam_period'];
	}
	
	
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	// imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	// imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
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
	
	if($invoice_info[0]['total_el_amount'] > 0){
		imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
		imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
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
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
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
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	// imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	// imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
	
	

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


function dynamic_invoice_generation($receipt_no_arr){ 
		$CI = & get_instance();
		$complete_arr = array(); 
		$r_cat = array();
		$aug_insert = array();
		/*$receipt_no_arr = array(901836357);   */ 
		/*print_r($receipt_no_arr);*/
		$sizearr = sizeof($receipt_no_arr);
		/*echo $sizearr; die();*/
		for($i=0;$i<$sizearr;$i++){
		
			$payment = $CI->master_model->getRecords('payment_transaction',array('receipt_no'=>$receipt_no_arr[$i]),'member_regnumber,transaction_no,id,amount,ref_id,date,receipt_no');
			
			$member = $CI->master_model->getRecords('member_exam',array('id'=>$payment[0]['ref_id']),'id,exam_code,exam_period,exam_center_code,created_on,modified_on');
			
			$registration = $CI->master_model->getRecords('member_registration',array('regnumber'=>$payment[0]['member_regnumber']),'registrationtype');
			
			$CI->db->where('exam_code',$member[0]['exam_code']);
			$CI->db->where('eligible_period',$member[0]['exam_period']);
			$CI->db->where('member_no',$payment[0]['member_regnumber']);
			$eligible = $CI->master_model->getRecords('eligible_master','','app_category');
		
			if($eligible){
				if($eligible[0]['app_category'] == 'R'){
					$CI->db->where('group_code','B1_1');
				}else{
					$CI->db->where('group_code',$eligible[0]['app_category']);
				}
			}else{
				$CI->db->where('group_code','B1_1');
			}
			$CI->db->where('exam_code',$member[0]['exam_code']);
			$CI->db->where('exam_period',$member[0]['exam_period']);
			$CI->db->where('member_category',$registration[0]['registrationtype']);
			$ex = explode(" ",$payment[0]['date']);
			$pay_date=$ex[0];
			$CI->db->where("'$pay_date' BETWEEN fr_date AND to_date");
			$fee = $CI->master_model->getRecords('fee_master','','fee_amount,sgst_amt,cgst_amt,igst_amt,cs_tot,igst_tot');
			
			
			$CI->db->where('exam_name',$member[0]['exam_code']);
			$CI->db->where('exam_period',$member[0]['exam_period']);
			$CI->db->where('center_code',$member[0]['exam_center_code']);
			$center = $CI->master_model->getRecords('center_master','','center_name,state_code,state_description');
			
			
			
			$state = $CI->master_model->getRecords('state_master',array('state_code'=>$center[0]['state_code']),'state_no,exempt');
			
			
			
			if($state[0]['state_no'] == 27){
				$cgst_rate = 9.00;
				$cgst_amt = $fee[0]['cgst_amt'];
				$sgst_rate = 9.00;
				$sgst_amt = $fee[0]['sgst_amt'];
				$cs_total = $fee[0]['cs_tot'];
				$igst_rate = 0.00;
				$igst_amt = 0.00;
				$igst_total = 0.00;
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Intra';
			}else{
				$cgst_rate = 0.00;
				$cgst_amt = 0.00;
				$sgst_rate = 0.00;
				$sgst_amt = 0.00;
				$cs_total = 0.00;
				$igst_rate = 18.00;
				$igst_amt = isset($fee[0]['igst_amt'])&&$fee[0]['igst_amt']!=''?$fee[0]['igst_amt']:0;
				$igst_total = isset($fee[0]['igst_tot'])&&$fee[0]['igst_tot']!=''?$fee[0]['igst_tot']:0;
				$disc_rate = 0.00;
				$disc_amt = 0.00;
				$tds_amt = 0.00;
				$tax_type = 'Inter';
			}
			
			$insert_arr = array(
								'exam_code' => $member[0]['exam_code'],
								'exam_period' => $member[0]['exam_period'],
								'center_code' => $member[0]['exam_center_code'],
								'center_name' => $center[0]['center_name'],
								'state_of_center' => $center[0]['state_code'],
								'member_no' => $payment[0]['member_regnumber'],
								'pay_txn_id' => $payment[0]['id'],
								'receipt_no' => $payment[0]['receipt_no'],
								'transaction_no' => $payment[0]['transaction_no'],
								'gstin_no' => '',
								'service_code' => 999294,
								'qty' => 1,
								'fresh_fee' => 0.00,
								'rep_fee' => 0.00,
								'fresh_count' => 0,
								'rep_count' => 0,
								'cess' => 0.00,
								'institute_code' => 0,
								'institute_name' => '',
								'state_code' => $state[0]['state_no'],
								'state_name' => $center[0]['state_description'],
								'invoice_no' => '',
								'invoice_image' => '',
								'fee_amt' => isset($fee[0]['fee_amount'])&&$fee[0]['fee_amount']!=''?$fee[0]['fee_amount']:0,
								'cgst_rate' => $cgst_rate,
								'cgst_amt' => $cgst_amt,
								'sgst_rate' => $sgst_rate,
								'sgst_amt' => $sgst_amt,
								'cs_total' => $cs_total,
								'igst_rate' => $igst_rate,
								'igst_amt' => $igst_amt,
								'igst_total' => $igst_total,
								'disc_rate' => $disc_rate,
								'disc_amt' => $disc_amt,
								'tds_amt' => $tds_amt,
								'date_of_invoice' => $member[0]['modified_on'],
								'created_on' => $member[0]['created_on'],
								'modified_on' => $member[0]['modified_on'],
								'tax_type' => $tax_type,
								'app_type' => 'O',
								'exempt' => $state[0]['exempt']
								);
		
			$exam_invoice = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no_arr[$i]),'invoice_id');
			
			//echo 'here'.$eligible[0]['app_category'].print_r($exam_invoice);
			//exit;
			
			
			if(count($eligible) > 0 && isset($eligible[0]['app_category']) ){ /* updated by padmashri */

			
			if($eligible[0]['app_category']!=''){
				
				if(empty($exam_invoice)){ /* updated by padmashri*/
					$last_id = $CI->master_model->insertRecord('exam_invoice',$insert_arr,true);
					//echo "ldfksdlkfjdflgkj".print_r($last_id);
					if($last_id > 0){
						$config_inset_arr = array(
													'invoice_id' => $last_id,
													'created_date' => $member[0]['modified_on']
												);

					
						$config_last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true), 6, "0", STR_PAD_LEFT); 
 						/*	$config_last_id = $CI->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);*/
						//$invoice_no = 'EX/19-20/'.$config_last_id;
						//$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';
						$update_arr = array(
											'invoice_no' => $invoice_no,
											'invoice_image' => $invoice_image
										);
						$CI->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));
					}
				}else{
				
					echo $payment[0]['receipt_no'].'Dupicate entry';
					echo '<br/>'; 
				}
			}else{
					$r_cat[] = $payment[0]['receipt_no']; 
					$aug_insert = array(
										'receipt_no'=>$receipt_no_arr[$i],
										'exm_cd'=>$member[0]['exam_code']
										);
				
				//	$CI->master_model->insertRecord('aug_invoice',$aug_insert,true);
					
			}
			}else{
			
				if( empty($exam_invoice) ){ /* updated by padmashri */
					$last_id = $CI->master_model->insertRecord('exam_invoice',$insert_arr,true);
					if($last_id > 0){
						$config_inset_arr = array(
													'invoice_id' => $last_id,
													'created_date' => $member[0]['modified_on']
												);
						$config_last_id = $CI->master_model->insertRecord('config_exam_invoice',$config_inset_arr,true);
						//$invoice_no = 'EX/19-20/'.$config_last_id;
						//$invoice_image = $payment[0]['member_regnumber'].'_EX_21-22_'.$config_last_id.'.jpg';
						$update_arr = array(
											'invoice_no' => $invoice_no,
											'invoice_image' => $invoice_image
										);
						$CI->master_model->updateRecord('exam_invoice',$update_arr,array('invoice_id'=>$last_id));
					}
				}else{
					echo $payment[0]['receipt_no'].'Dupicate entry';
					echo '<br/>'; 
				}
			}
			$complete_arr[] = $payment[0]['receipt_no'];
		} 
		

	}


/* Added by padmashri to insert the record in exam invoice in member registration  on dtd 10th Oct 2019 */
function dynamic_member_invoice_generation($member_regnumber,$ref_id,$arr_settlement)
{
		$CI = & get_instance();
		$cgst_rate=$sgst_rate=$igst_rate=$tax_type='';
		$cgst_amt=$sgst_amt=$igst_amt='';
		$cs_total=$igst_total='';
		$getstate=$getcenter=$getfees=array();
		$flag=1;
		$regno  = $member_regnumber;
		if(!empty($regno) && !empty($arr_settlement)   && $member_regnumber!='' && $member_regnumber > 0 &&  $ref_id > 0 && $ref_id!='')
		{
			/* neeed to asked in registration its */

		 	$member_data = $CI->Master_model->getRecords('member_registration',array('regid'=>$ref_id,'isactive'=>'1'),array('state','fee'));
		 
		 	$state = $member_data[0]['state']; /* updated by padmashri previously it was state_pre*/
			//$fee   = $member_data[0]['fee'];
			if(!empty($state))
			{
				if($state == 'MAH')
				{
				   $amount = $CI->config->item('cs_total');
				}
				/*else if($state == 'JAM')
				{
				   $amount = $CI->config->item('fee_amt');
				}*/
				else
				{
					$amount = $CI->config->item('igst_total');
				}
			}
			//$MerchantOrderNo = generate_order_id("reg_sbi_order_id");
		 	//get value for invoice details [Tejasvi]
			if(!empty($state))
			{ 
				//get state code,state name,state number.
				$getstate = $CI->master_model->getRecords('state_master',array('state_code'=>$state,'state_delete'=>'0'));
			}
			if($state == 'MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$CI->config->item('cgst_rate');
				$sgst_rate=$CI->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt=$CI->config->item('cgst_amt');
				$sgst_amt=$CI->config->item('sgst_amt');
				 //set an total amount
				$cs_total=$amount;
				$tax_type='Intra';     
			
			}
			/*else if($state == 'JAM')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate=$sgst_rate=$igst_rate='';	
				$cgst_amt=$sgst_amt=$igst_amt='';	
				$igst_total=$amount; 
				$tax_type='Inter';
			}*/
			else
			{
				$igst_rate=$CI->config->item('igst_rate');
				$igst_amt=$CI->config->item('igst_amt');
				$igst_total=$amount; 
				$tax_type='Inter';
			}
			            
			/*if($getstate[0]['exempt']=='E')
			{
				 $cgst_rate=$sgst_rate=$igst_rate='';	
				 $cgst_amt=$sgst_amt=$igst_amt='';	
			}*/
				
			$invoice_insert_array = array(
											'member_no'       => $arr_settlement['member_regnumber'],
											'pay_txn_id'      => $arr_settlement['payment_auto_inc'],
											'receipt_no'      => $arr_settlement['receipt_no'],
											'transaction_no'  => $arr_settlement['transaction_no'],
											'member_no'       => $regno,
											'state_of_center' =>$state,
											'app_type'        =>'R',
											'service_code'    =>$CI->config->item('reg_service_code'),
											'qty'             =>'1',
											'state_code'      =>$getstate[0]['state_no'],
											'state_name'      =>$getstate[0]['state_name'],
											'tax_type'        =>$tax_type,
											'fee_amt'         =>$CI->config->item('fee_amt'),
											'cgst_rate'       =>$cgst_rate,
											'cgst_amt'        =>$cgst_amt,
											'sgst_rate'       =>$sgst_rate,
											'sgst_amt'        =>$sgst_amt,
											'igst_rate'       =>$igst_rate,
											'igst_amt'        =>$igst_amt,
											'cs_total'        =>$cs_total,
											'igst_total'      =>$igst_total,
											'gstin_no'        =>'',
											'exempt'          =>$getstate[0]['exempt'],
											'date_of_invoice' => $arr_settlement['payment_date'],
											'modified_on'     => $arr_settlement['payment_date'],
											'created_on'      =>date('Y-m-d H:i:s')
										);
			echo "Exam invoice array <br/>".print_r($invoice_insert_array); 
		    $inser_id=$CI->master_model->insertRecord('exam_invoice',$invoice_insert_array,'true');
		    echo "INserted id ".$inser_id;
			if($inser_id)
		    {
		    	$CI->db->where('invoice_id',$inser_id);
				$is_exists_in_config = $CI->master_model->getRecords('config_reg_invoice');
				if(!empty($is_exists_in_config) && count($is_exists_in_config)>0)
				{
					$config_auto = $is_exists_in_config[0]['reg_invoice_no'];
				}
				else
				{
					//$config_auto =  $CI->master_model->insertRecord('config_reg_invoice',array('invoice_id'=>$inser_id),'true');
					$insert_info1 = array('invoice_id'=>$inser_id);
					$config_auto = str_pad($CI->master_model->insertRecord('config_reg_invoice',$insert_info1,true), 6, "0", STR_PAD_LEFT);

				}

				/* update the invoice no and invoice images as per generate  */

				$invoice_no                  = generate_invoice_no_image_for_member_registration($config_auto,'no');		
				$invoice_image               = generate_invoice_no_image_for_member_registration($config_auto,'image',$member_regnumber);
				$arr_update['invoice_no']    = $invoice_no;
				$arr_update['invoice_image'] = $invoice_image;
				$CI->master_model->updateRecord('exam_invoice',$arr_update,array('invoice_id' => $inser_id ));
				return "settle";
				 
		    }
		    return "error";
		     
		}
		
	

}
function generate_invoice_no_image_for_member_registration($config_auto_id,$type,$member_no='')
{	
		$CI = & get_instance();
		$cal_year = $str_return  =   '';
		$cal_year = date('y'); 
		$next_year = date('y')+1; 
		$str_year = $cal_year.'-'.$next_year; 
		if($type == 'image' && $member_no!='')
		{
			$str_return = $member_no."_M_".$str_year.'_'.$config_auto_id.'.jpg';
		}
		else if($type == 'no')
		{
			$str_return =$CI->config->item('mem_invoice_no_prefix').$config_auto_id;
		}
		return $str_return;
}


function custom_genarate_CISI_invoice($invoice_no){ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Exam code : ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 40,  320, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  340, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  360, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  380, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  400, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  420, "GST No: -", $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
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
	
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	imagedestroy($im);
	
	/****************************** Image of supplier *********************************/
	
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Exam code : ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 40,  320, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  340, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  360, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  380, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  400, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  420, "GST No: -", $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
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
 	function custom_generate_exam_invoice_number($invoice_id= NULL)
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


	
/** FUNCTION ADDED BY SAGAR ON 09-12-2020 TO GENERATE DRA EXAM INVOICE ***/
function genarate_dra_exam_invoice_custom($invoice_no)
{ 
		$CI = & get_instance();
	
	// get invoice details
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id' => $invoice_no));
	if(!$invoice_info){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Invoice ID ot found');
		return '';
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	// get DRA institute details
	$inst_details = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code' => $invoice_info[0]['institute_code']),'address1,address2,address3,address4,address5,address6,pin_code');
	if(!$inst_details){
		log_dra_user($log_title = "DRA exam invoice generation", $log_message = 'Institute code not found in dra_accerdited_master');
		return '';
	}
	
	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['cs_total']));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$amt_in_words = trim(amtinword($invoice_info[0]['igst_total']));
	}
	
	// create image
	//imagecreate(width, height);
	$im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
	$background_color = imagecolorallocate($im, 255, 255, 255); // white
	$black = imagecolorallocate($im, 0, 0, 0); // black
	
	
	if($invoice_info[0]['center_code'] != 0){
		
		$center = $CI->master_model->getRecords('agency_center',array('center_id'=>$invoice_info[0]['center_code']));
		
		$state_desc = $CI->master_model->getRecords('state_master',array('state_code'=>$center[0]['state']));
		$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$center[0]['city']));
		
		
		
		$address1 = $center[0]['address1'];
		$address2 = $center[0]['address2'];
		$pincode = $center[0]['pincode'];
		
		$state = $state_desc[0]['state_name'];
		$state_code = $state_desc[0]['state_no'];
		$city  = $city_desc[0]['city_name'];
	}else{
		$address1 = $inst_details[0]['address1'];
		$address2 = $inst_details[0]['address2'];
		$pincode = $inst_details[0]['pin_code'];
		$state = $invoice_info[0]['state_name'];
		$state_code = $invoice_info[0]['state_code'];
		
		$agency_city = $CI->master_model->getRecords('dra_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']));
		
		if($agency_city[0]['address6']!=''){
			if(is_int($agency_city[0]['address6'])){
				$city_desc = $CI->master_model->getRecords('city_master',array('id'=>$agency_city[0]['address6']));
				$city = $city_desc[0]['city_name'];
			}else{
				$city = $agency_city[0]['address6'];
			}
		}else{
			$city  = '-';
		}
	}
	
	
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
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$address1, $black);
	imagestring($im, 3, 40,  300, $address2, $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$pincode, $black);
	imagestring($im, 3, 40,  360, "State: ".$state, $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  400, "City: ".$city, $black);
	imagestring($im, 3, 40,  420, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	if($invoice_info[0]['fresh_count'] != 0){
		$base_total_R =  $invoice_info[0]['fresh_fee'] * $invoice_info[0]['fresh_count'];
		imagestring($im, 3, 690,  600, $invoice_info[0]['fresh_fee'], $black); // Rate
		imagestring($im, 3, 820,  600, $invoice_info[0]['fresh_count'], $black); // Quantity [unit]
		imagestring($im, 3, 900,  600, $base_total_R, $black); // Total
	}
	
	if($invoice_info[0]['rep_count'] != 0){ 
		$base_total_R =  $invoice_info[0]['rep_fee'] * $invoice_info[0]['rep_count'];
		imagestring($im, 3, 690,  620, $invoice_info[0]['rep_fee'], $black); // Rate
		imagestring($im, 3, 820,  620, $invoice_info[0]['rep_count'], $black); // Quantity [unit]
		imagestring($im, 3, 900,  620, $base_total_R, $black); // Total
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/draexaminvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/user/'.$imagename);
	imagedestroy($im);
	
	
	/*********************** Image for supplier *************************************/
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
	imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
	imagestring($im, 3, 40,  260, "Name of Institute: ".$invoice_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  280, "Institute Address: ".$address1, $black);
	imagestring($im, 3, 40,  300, $address2, $black);
	imagestring($im, 3, 40,  340, "Pincode: ".$pincode, $black);
	imagestring($im, 3, 40,  360, "State: ".$state, $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_code, $black);
	imagestring($im, 3, 40,  400, "City: ".$city, $black);
	imagestring($im, 3, 40,  420, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
	imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
	imagestring($im, 3, 670,  300, "GSTIN: 27AAATT3309D1ZS", $black);
	
	
	imagestring($im, 3, 40,  530, "Sr.No", $black);
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
	
	
	if($invoice_info[0]['fresh_count'] != 0){
		$base_total_R =  $invoice_info[0]['fresh_fee'] * $invoice_info[0]['fresh_count'];
		imagestring($im, 3, 690,  600, $invoice_info[0]['fresh_fee'], $black); // Rate
		imagestring($im, 3, 820,  600, $invoice_info[0]['fresh_count'], $black); // Quantity [unit]
		imagestring($im, 3, 900,  600, $base_total_R, $black); // Total
	}
	
	if($invoice_info[0]['rep_count'] != 0){
		$base_total_R =  $invoice_info[0]['rep_fee'] * $invoice_info[0]['rep_count'];
		imagestring($im, 3, 690,  620, $invoice_info[0]['rep_fee'], $black); // Rate
		imagestring($im, 3, 820,  620, $invoice_info[0]['rep_count'], $black); // Quantity [unit]
		imagestring($im, 3, 900,  620, $base_total_R, $black); // Total
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	} 
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		
		imagestring($im, 3, 118,  660, "CGST", $black);
		imagestring($im, 3, 690,  660, "-", $black);
		imagestring($im, 3, 900,  660, "-", $black);
		imagestring($im, 3, 118,  672, "SGST", $black);
		imagestring($im, 3, 690,  672, "-", $black);
		imagestring($im, 3, 900,  672, "-", $black);
		
		
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 118,  710, "IGST", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
	}
	
	
	
	
	imagestring($im, 3, 40,  860, "Amount in words :".$amt_in_words. " Only", $black);
	imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 260,  900, "Y/N", $black);
	imagestring($im, 3, 300,  900, "NO", $black);
	imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
	imagestring($im, 3, 280,  930, "% ---", $black);
	imagestring($im, 3, 350,  930, "Rs.---", $black);
	
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	
	
	$savepath = base_url()."uploads/draexaminvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "dra_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	imagepng($im,"uploads/draexaminvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/draexaminvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/draexaminvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/draexaminvoice/user/".$imagename;
}


/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/custom_invice_helper.php */



