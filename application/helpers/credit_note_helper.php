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


// chaitali added 2021-04-05
function generate_credit_note_chaitali($transaction_no){
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
	
	$chk_config = $CI->master_model->getRecords('config_credit_note',array('invoice_id'=>$invoice_info[0]['invoice_id']));
	
	if(count($chk_config) == 0){ 
		$config_inset_arr = array(
			'invoice_id' => $invoice_info[0]['invoice_id'],
			'created_date' => date('Y-m-d H:i:s')
		);
		$config_last_id = str_pad($CI->master_model->insertRecord('config_credit_note',$config_inset_arr,true), 5, "0", STR_PAD_LEFT);
	}else{ 
		$config_last_id = $chk_config[0]['creditnote_no'];
	}
	
	
	$y = date('y');
	$ny = date('y')+1;   
	
	$credit_note_no = 'CDN/'.$exp[1].'/'.$config_last_id;
	
	$update_arr = array('credit_note_image'=>$cr_imagename,'credit_note_gen_date'=>date('Y-m-d'),'credit_note_number'=>$credit_note_no);
	//$update_arr = array('credit_note_image'=>$cr_imagename,'credit_note_number'=>$credit_note_no);
	$CI->master_model->updateRecord('maker_checker',$update_arr,array('transaction_no'=>$transaction_no));
	
	
	$CI->db->where('transaction_no',$transaction_no);
	$CI->db->where('req_status',5);
	$maker_rec = $CI->master_model->getRecords('maker_checker','','refund_date,credit_note_number,req_module,sbi_refund_date');
	
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
	
	imagestring($im, 3, 600,  380, "Refund Date : ".date("d-m-Y", strtotime($maker_rec[0]['sbi_refund_date'])), $black);
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
	imagestring($im, 3, 118,  600, $credit_title['title'], $black);
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
	
	return $attachpath = "uploads/CreditNote/".$imagename;
	//exit;
}




function genarate_credit_note($member_no)
{
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('caiib_refunds',array('MEM_NO'=>$member_no));
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_no),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr,address1,address2,address3,address4,pincode');
	
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($mem_info[0]['address1_pr'] != ''){
		$addflg = 'permanent';
	}elseif($mem_info[0]['address1_pr'] == ''){
		$addflg = 'current';
	}
	//echo $mem_info[0]['address2'];exit; 
	
	if($invoice_info[0]['STATE_OF_CENTER'] == 'MAH'){
		$wordamt = examamtinword($invoice_info[0]['CS_TOT']);
	}elseif($invoice_info[0]['STATE_OF_CENTER'] != 'MAH'){
		$wordamt = examamtinword($invoice_info[0]['IGST_TOT']);
	}
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['DATE_OF_INVOICE']));
	
	$UPD_DT = date("d-m-Y", strtotime($invoice_info[0]['UPD_DT']));
	
	
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
	
	imagestring($im, 5, 455,  30, "Credit Note", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Credit Note No : ".$invoice_info[0]['CREDIT_NOTE'], $black);
	imagestring($im, 3, 22,  224, "Date : ".$UPD_DT, $black);
	imagestring($im, 3, 22,  236, "", $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	if($invoice_info[0]['EXM_CD'] == 340 || $invoice_info[0]['EXM_CD'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['EXM_CD'] == 580 || $invoice_info[0]['EXM_CD'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['EXM_CD'] == 1600){
		$exam_code = 160;
	}elseif($invoice_info[0]['EXM_CD'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['EXM_CD'] == 1770){
		$exam_code = 177;
	}elseif($invoice_info[0]['EXM_CD'] == 590){
		$exam_code = 59;
	}
	elseif($invoice_info[0]['EXM_CD'] == 810){
		$exam_code = 81;
	}
	else{
		$exam_code = $invoice_info[0]['EXM_CD'];
	}
	//imagestring($im, 3, 22,  248, "Exam code: ".$exam_code, $black);
	//imagestring($im, 3, 22,  260, "Exam period: ".$invoice_info[0]['EXM_PRD'], $black);
	
	imagestring($im, 3, 22,  274, "Details of Buyer (Billed to)", $black);
	imagestring($im, 3, 22,  298, "Name of the Buyer: ".$member_name, $black);
	
	if($addflg == 'permanent'){
	
		imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1_pr'], $black);
		imagestring($im, 3, 22,  322, $mem_info[0]['address2_pr'], $black);
		imagestring($im, 3, 22,  334, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	}
	
	if($addflg == 'current'){
	
		imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1'], $black);
		imagestring($im, 3, 22,  322, $mem_info[0]['address2'], $black);
		imagestring($im, 3, 22,  334, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	
	}
	
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['STATE_NAME'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['STATE_CODE'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	imagestring($im, 3, 22,  382, "Reference No. of original invoice: ".$invoice_info[0]['INVOICE_NO'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Refund of exam fees", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['SERVICE_CODE'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['FEE_AMT'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['FEE_AMT'], $black); // Total

	
	if($invoice_info[0]['STATE_OF_CENTER'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['CGST_RATE']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['CGST_AMT'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['SGST_RATE']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['SGST_AMT'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['STATE_OF_CENTER'] != 'MAH'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['IGST_RATE']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['IGST_AMT'], $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['STATE_OF_CENTER'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['CS_TOT'], $black);
	}elseif($invoice_info[0]['STATE_OF_CENTER'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['IGST_TOT'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	//imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	//imagestring($im, 3, 300,  900, "Y/N", $black);
	//imagestring($im, 3, 350,  900, "NO", $black);
	//imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	//imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	//imagestring($im, 3, 300,  932, "% ---", $black);
	//imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/CreditNote/user/";
	$ino = str_replace("/","_",$invoice_info[0]['CREDIT_NOTE']);
	$imagename = $invoice_info[0]['MEM_NO']."_".$ino.".jpg";
	
	$update_data = array('CREDIT_NOTE_IMAGE' => $imagename);
	$CI->master_model->updateRecord('caiib_refunds',$update_data,array('MEM_NO'=>$member_no));
	
	imagepng($im,"uploads/CreditNote/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/CreditNote/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/CreditNote/user/'.$imagename);
	
	imagedestroy($im);
	
	/****************************** image for supplier ***********************************/
	
	
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
	
	imagestring($im, 5, 455,  30, "Credit Note", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Credit Note No : ".$invoice_info[0]['CREDIT_NOTE'], $black);
	imagestring($im, 3, 22,  224, "Date : ".$UPD_DT, $black);
	imagestring($im, 3, 22,  236, "", $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	if($invoice_info[0]['EXM_CD'] == 340 || $invoice_info[0]['EXM_CD'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['EXM_CD'] == 580 || $invoice_info[0]['EXM_CD'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['EXM_CD'] == 1600){
		$exam_code = 160;
	}elseif($invoice_info[0]['EXM_CD'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['EXM_CD'] == 1770){
		$exam_code = 177;
	}elseif($invoice_info[0]['EXM_CD'] == 590){
		$exam_code = 59;
	}
	elseif($invoice_info[0]['EXM_CD'] == 810){
		$exam_code = 81;
	}
	else{
		$exam_code = $invoice_info[0]['EXM_CD'];
	}
	//imagestring($im, 3, 22,  248, "Exam code: ".$exam_code, $black);
	//imagestring($im, 3, 22,  260, "Exam period: ".$invoice_info[0]['EXM_PRD'], $black);
	
	imagestring($im, 3, 22,  274, "Details of Buyer (Billed to)", $black);
	imagestring($im, 3, 22,  298, "Name of the Buyer: ".$member_name, $black);
	
	/*imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1_pr'], $black);
	imagestring($im, 3, 22,  322, $mem_info[0]['address2_pr'], $black, $black);
	imagestring($im, 3, 22,  334, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);*/
	
	
	if($addflg == 'permanent'){
	
		imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1_pr'], $black);
		imagestring($im, 3, 22,  322, $mem_info[0]['address2_pr'], $black);
		imagestring($im, 3, 22,  334, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
	
	}
	
	if($addflg == 'current'){
	
		imagestring($im, 3, 22,  310, "Address: ".$mem_info[0]['address1'], $black);
		imagestring($im, 3, 22,  322, $mem_info[0]['address2'], $black);
		imagestring($im, 3, 22,  334, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	
	}
	
	
	imagestring($im, 3, 22,  346, "State: ".$invoice_info[0]['STATE_NAME'], $black);
	imagestring($im, 3, 22,  358, "State code: ".$invoice_info[0]['STATE_CODE'], $black);
	imagestring($im, 3, 22,  370, "GSTIN / Unique Id : NA", $black);
	imagestring($im, 3, 22,  382, "Reference No. of original invoice: ".$invoice_info[0]['INVOICE_NO'], $black);
	
	imagestring($im, 3, 22,  530, "Sr.No", $black);
	imagestring($im, 3, 100,  530, "Description of Service", $black);
	imagestring($im, 3, 570,  508, "Accounting ", $black);
	imagestring($im, 3, 570,  520, "code", $black);
	imagestring($im, 3, 570,  532, "of Service", $black);
	imagestring($im, 3, 665,  530, "Rate", $black);
	imagestring($im, 3, 780,  530, "Unit", $black);
	imagestring($im, 3, 900,  530, "Total", $black);
	
	imagestring($im, 3, 45,  560, "1", $black);
	imagestring($im, 3, 100,  560, "Refund of exam fees", $black);
	imagestring($im, 3, 590,  560, $invoice_info[0]['SERVICE_CODE'], $black);//Accounting
	imagestring($im, 3, 690,  560, $invoice_info[0]['FEE_AMT'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['FEE_AMT'], $black); // Total

	
	if($invoice_info[0]['STATE_OF_CENTER'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['CGST_RATE']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['CGST_AMT'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['SGST_RATE']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['SGST_AMT'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['STATE_OF_CENTER'] != 'MAH'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['IGST_RATE']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['IGST_AMT'], $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['STATE_OF_CENTER'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['CS_TOT'], $black);
	}elseif($invoice_info[0]['STATE_OF_CENTER'] != 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['IGST_TOT'], $black);
	}
	
	imagestring($im, 3, 22,  820, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	//imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	//imagestring($im, 3, 300,  900, "Y/N", $black);
	//imagestring($im, 3, 350,  900, "NO", $black);
	//imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	//imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	//imagestring($im, 3, 300,  932, "% ---", $black);
	//imagestring($im, 3, 350,  932, "Rs.---", $black);
	
	$savepath = base_url()."uploads/CreditNote/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['CREDIT_NOTE']);
	$imagename = $invoice_info[0]['MEM_NO']."_".$ino.".jpg";
	
	$update_data = array('CREDIT_NOTE_IMAGE' => $imagename);
	$CI->master_model->updateRecord('caiib_refunds',$update_data,array('MEM_NO'=>$member_no));
	
	imagepng($im,"uploads/CreditNote/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/CreditNote/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	
	imagepng($im, 'uploads/CreditNote/supplier/'.$imagename);
	
	imagedestroy($im);
	return $attachpath = "uploads/CreditNote/user/".$imagename;
}
