<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 //EXM/DUP-CERT/FY/001 Sample: - EXM/DUP-CERT/2017-18/001. 

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

 function genarate_XLRI_invoice($invoice_no){
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

/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */
?>