<?php defined('BASEPATH')||exit('No Direct Allowed Here');

/* Get Total Attempts Counts */
function getTotalAttemptsCounts($regnumber,$program_code)
{
	$CI = & get_instance();
	$TotalAttemptsCounts = $CI->master_model->getRecords('blended_registration', array('member_no'=>$regnumber, 'program_code'=>$program_code, 'pay_status'=>'1'),'COUNT(blended_id) as TotalAttemptsCounts');
	$TotalAttemptsCounts = $TotalAttemptsCounts[0]['TotalAttemptsCounts'];
	return $TotalAttemptsCounts;
}

/* Get Attempts Count */
function getAttemptsCounts($regnumber,$program_code,$batch_code)
{//'batch_code'=>$batch_code,
	$CI = & get_instance();
	$AttemptsCounts = $CI->master_model->getRecords('blended_registration', array('member_no'=>$regnumber,'program_code'=>$program_code,'pay_status'=>'1','batch_code'=>$batch_code), 'COUNT(blended_id) as AttemptsCounts');
	$AttemptsCounts = $AttemptsCounts[0]['AttemptsCounts'];
	//echo "SQL=>".$CI->db->last_query();exit;
	return $AttemptsCounts;
}

/* Check Count of Vitual Attempts */
function getVitualAttemptsCounts($regnumber,$program_code,$batch_code)
{
	//'batch_code'=>$batch_code / ,'attempt'=>'1'
	$CI = & get_instance();
	$VitualAttemptsCounts = $CI->master_model->getRecords('blended_registration', array('member_no'=>$regnumber,'program_code'=>$program_code,'training_type'=>'VC','pay_status'=>'1'), 'COUNT(blended_id) as VitualAttemptsCounts');
	//echo "SQL=>".$CI->db->last_query();exit;
	$VitualAttemptsCounts = $VitualAttemptsCounts[0]['VitualAttemptsCounts'];
	return $VitualAttemptsCounts;
}

/* Check Registration Capacity Count */
function blendedRegistrationCapacity($program_code,$center_code,$batch_code,$training_type,$venue_code,$sDate)
{
	$CI = & get_instance();
	$RegCount = $CI->master_model->getRecords('blended_registration', array('program_code'=>$program_code,'center_code'=>$center_code,'batch_code'=>$batch_code,'training_type'=>$training_type,'venue_code'=>$venue_code, 'start_date'=>$sDate, 'pay_status'=>'1'), 'COUNT(blended_id) as regCount');
	$RegCount = $RegCount[0]['regCount'];
	return $RegCount;
}

/* Get Venue Capacity */
function getVenueCapacity($program_code,$center_code,$batch_code,$training_type,$venue_code,$sDate)
{
	$CI = & get_instance();
	$capacity = $CI->master_model->getRecords('blended_venue_master', array('program_code'=>$program_code,'center_code'=>$center_code,'batch_code'=>$batch_code,'training_type'=>$training_type,'venue_code'=>$venue_code,'start_date'=>$sDate,'isdeleted'=>'0'), 'capacity');
	$capacity = $capacity[0]['capacity'];
	//echo "SQL=>".$CI->db->last_query();exit;
	return $capacity;
}

/* Get Batch Code */
function getBatchCode($program_code)
{
	$CI = & get_instance();
	$batch = $CI->master_model->getRecords('blended_program_activation_master',array('program_code'=>$program_code,'program_activation_delete'=>'0'),'batch_code');
	return $batch_code = $batch[0]['batch_code'];
}

function amtinword_blended($amt){
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

function genarate_blended_invoice($invoice_no,$zone_code,$program_name)
{	$CI = & get_instance();
	
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	/* Get Member Details */
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	

	//address
	$address_1=$address_2='';
	if(isset($mem_info[0]['address1']) )
	{
		$address_1=$mem_info[0]['address1'].$mem_info[0]['address2'];
	}if(isset($mem_info[0]['address2']) )
	{
		$address_2=$mem_info[0]['address3'].$mem_info[0]['address4'];
	}
	
	
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
		$wordamt = amtinword_blended($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		$wordamt = amtinword_blended($invoice_info[0]['igst_total']);
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
	imagestring($im, 3, 40,  260, "Name of service Recipient:".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 40,  400, "Course name : ".$program_name, $black);
	imagestring($im, 3, 40,  420, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  440, "Center name : ".$invoice_info[0]['center_name'], $black);
	
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
	
	imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
	imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black); 
	imagestring($im, 3, 535,  820, "Total(Rs.)", $black); 
	
	
	imagestring($im, 3, 45,  595, "1", $black);
	imagestring($im, 3, 118,  626,"" , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	
	
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
	
	
	
		imagestring($im, 3, 700,  626, $cgst_rate, $black);
		imagestring($im, 3, 700,  646, $sgst_rate, $black);
		imagestring($im, 3, 700,  666, $igst_rate , $black);
		
		imagestring($im, 3, 900,  626, $cgst_amt, $black);
		imagestring($im, 3, 900,  646, $sgst_amt, $black);
		imagestring($im, 3, 900,  666, $igst_amt, $black);
	
	if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
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
	
	$savepath = base_url()."uploads/blended_invoice/user/";
	//$imagename = 'new_dra.jpg';
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
	
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
	
	imagepng($im,"uploads/blended_invoice/user/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice/user/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/blended_invoice/user/'.$zone_code.'/'.$imagename);
	imagedestroy($im);
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	
	
	
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
	imagestring($im, 3, 40,  260, "Name of service Recipient:".$member_name, $black);
	imagestring($im, 3, 40,  280, "Address: ".$address_1, $black);
	imagestring($im, 3, 40,  300, $address_2, $black);
	imagestring($im, 3, 40,  320, "State: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  340, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  360, "GST No: ".$invoice_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  380, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	imagestring($im, 3, 40,  400, "Course name : ".$program_name, $black);
	imagestring($im, 3, 40,  420, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  440, "Center name : ".$invoice_info[0]['center_name'], $black);
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
	
	imagestring($im, 3, 118,  596, "Charges Towards Training Program", $black);
	imagestring($im, 3, 535,  596, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 700,  596, $invoice_info[0]['fee_amt'], $black);
	imagestring($im, 3, 815,  596, $invoice_info[0]['qty'], $black);
	imagestring($im, 3, 900,  596, $invoice_info[0]['fee_amt'], $black); 
	imagestring($im, 3, 535,  820, "Total(Rs.)", $black); 
	
	
	imagestring($im, 3, 45,  595, "1", $black);
	imagestring($im, 3, 118,  626,"" , $black);
	imagestring($im, 3, 260,  626, "CGST ", $black);
	imagestring($im, 3, 260,  646, "SGST ", $black);
	imagestring($im, 3, 260,  666, "IGST ", $black);
	
	
	
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
	
	
	
		imagestring($im, 3, 700,  626, $cgst_rate, $black);
		imagestring($im, 3, 700,  646, $sgst_rate, $black);
		imagestring($im, 3, 700,  666, $igst_rate , $black);
		
		imagestring($im, 3, 900,  626, $cgst_amt, $black);
		imagestring($im, 3, 900,  646, $sgst_amt, $black);
		imagestring($im, 3, 900,  666, $igst_amt, $black);
	
	if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  820, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
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
	
	$savepath = base_url()."uploads/blended_invoice/supplier/";
	//$imagename = 'new_dra.jpg';
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
	
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
	
	imagepng($im,"uploads/blended_invoice/supplier/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice/supplier/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/blended_invoice/supplier/'.$zone_code.'/'.$imagename);
	imagedestroy($im);
	return $attachpath = 'uploads/blended_invoice/supplier/'.$zone_code.'/'.$imagename;
	
	
	}
function generate_blended_invoice_number($invoice_id= NULL,$zone_code= NULL){
	$last_id='';
	$CI = & get_instance();
	if($invoice_id  !=NULL){
	$insert_info = array('invoice_id'=>$invoice_id);
	$last_id = str_pad($CI->master_model->insertRecord('config_blended_'.$zone_code.'_invoice',$insert_info,true),5,"0",STR_PAD_LEFT);
	}
	return $last_id;
}