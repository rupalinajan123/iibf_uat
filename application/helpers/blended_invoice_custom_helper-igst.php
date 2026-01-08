<?php defined('BASEPATH')||exit('No Direct Allowed Here');



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

function genarate_blended_invoice_custom($invoice_no,$zone_code,$program_name,$mem_gstin_no)
{ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	//echo "<pre>"; print_r($invoice_info); echo "</pre>";
 	/* Get Member Details */
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	//echo "<pre>mem_info=>"; print_r($mem_info); echo "</pre>";
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
	/*if($invoice_info[0]['cs_total'] != 0.00){
		$wordamt = amtinword_blended($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		$wordamt = amtinword_blended($invoice_info[0]['igst_total']);
	}*/
	$igst="10620";
	$wordamt = amtinword_blended($igst);
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
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, "-", $black);
	imagestring($im, 3, 300,  672, "State Tax:", $black);
	imagestring($im, 3, 690,  672, "-", $black);
	imagestring($im, 3, 900,  672, "-", $black);
	imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
	imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
	imagestring($im, 3, 690,  710, "18%", $black);
	imagestring($im, 3, 900,  710, "1620", $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	/*if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
	imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);		
	}*/
	imagestring($im, 3, 900,  780, "10620", $black);	
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);
	$savepath = base_url()."uploads/blended_invoice_custom/user/".$zone_code."/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/blended_invoice_custom/user/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice_custom/user/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/blended_invoice_custom/user/'.$zone_code.'/'.$imagename);
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
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, "-", $black);
	imagestring($im, 3, 300,  672, "State Tax:", $black);
	imagestring($im, 3, 690,  672, "-", $black);
	imagestring($im, 3, 900,  672, "-", $black);
	imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
	imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
	imagestring($im, 3, 690,  710, "18%", $black);
	imagestring($im, 3, 900,  710, "1620", $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	/*if($invoice_info[0]['cs_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['igst_total'] != 0.00){
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);	
	}*/
	
	imagestring($im, 3, 900,  780, "10620", $black);	
	
	imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
	imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
	imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
	imagestring($im, 3, 300,  900, "Y/N", $black);
	imagestring($im, 3, 350,  900, "NO", $black);
	imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
	imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
	imagestring($im, 3, 300,  932, "% ---", $black);
	imagestring($im, 3, 350,  932, "Rs. ---", $black);

	$savepath = base_url()."uploads/blended_invoice_custom/supplier/".$zone_code."/";
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/blended_invoice_custom/supplier/".$zone_code."/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/blended_invoice_custom/supplier/".$zone_code."/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/blended_invoice_custom/supplier/'.$zone_code.'/'.$imagename);
	imagedestroy($im);
	return $attachpath = "uploads/blended_invoice_custom/user/".$zone_code."/".$imagename;
}
