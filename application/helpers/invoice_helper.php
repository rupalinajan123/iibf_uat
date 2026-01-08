<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function amtinword_bk($amt){
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
    '30' => 'Thirty', '40' => 'Fourty', '50' => 'Fifty',
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

function amtinword($number)
{
   $no = floor($number);
   $point = round($number - $no, 2) * 100;
   $hundred = null;
   $digits_1 = strlen($no);
   $i = 0;
   $str = array();
   $words = array('0' => '', '1' => 'one', '2' => 'two',
    '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
    '7' => 'seven', '8' => 'eight', '9' => 'nine',
    '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
    '13' => 'thirteen', '14' => 'fourteen',
    '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
    '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
    '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
    '60' => 'sixty', '70' => 'seventy',
    '80' => 'eighty', '90' => 'ninety');
   $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
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
    " And " . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  return str_replace("  "," ",$result . "Rupees" . $points);
}

//garp invoice
function genarate_cfp_exam_invoice($invoice_id){ 
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
		$invoice_exname = 'CFP Exam';
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
	
	$savepath = base_url()."uploads/cfpinvoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/cfpinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/cfpinvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/cfpinvoice/user/'.$imagename);
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
	imagestring($im, 3, 118,  600, "Registration for CFP-Fast Track Program", $black);
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
	
	$savepath = base_url()."uploads/cfpinvoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/cfpinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/cfpinvoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/cfpinvoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/cfpinvoice/user/".$imagename;
	
}
//garp invoice
function genarate_garp_exam_invoice($invoice_id){ 
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
 	function generate_GARP_invoice_number($invoice_id= NULL)
	{
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
	###-----generate GARP invoice number---### 
	function generate_CFP_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_CFP_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}

//chartered invoice
function genarate_chartered_exam_invoice($invoice_id){ 
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

function genarate_exam_invoice($invoice_id){ 
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


function genarate_elearning_exam_invoice($invoice_id){ 
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
	
	return $attachpath = "uploads/examinvoice/user/".$imagename;
	
}

function genarate_reg_invoice($invoice_no){
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
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

function genarate_draexam_invoice($invoice_no){ 
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
	
	if (isset($invoice_info[0]['tds_amt']) && $invoice_info[0]['tds_amt'] != '' && $invoice_info[0]['tds_amt'] > 0) {
		$tdsAmount = $invoice_info[0]['tds_amt'];	
	} else {
		$tdsAmount = 0;
	}

	// convert invoice amount in words
	$amt_in_words = '';
	if($invoice_info[0]['state_of_center'] == 'MAH') {
		$tamount = $invoice_info[0]['cs_total'] + $tdsAmount;
		$amt_in_words = trim(amtinword($tamount));
	} elseif($invoice_info[0]['state_of_center'] != 'MAH') {
		$tamount = $invoice_info[0]['igst_total'] + $tdsAmount;
		$amt_in_words = trim(amtinword($tamount));
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

	/*if (isset($invoice_info[0]['tds_amt']) && $invoice_info[0]['tds_amt'] != '' && $invoice_info[0]['tds_amt'] > 0) {
		$tdsAmount = $invoice_info[0]['tds_amt'];	
	} else {
		$tdsAmount = 0;
	}*/

	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total']+$tdsAmount, $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total']+$tdsAmount, $black);
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id' => $invoice_no));
	
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
		imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total']+$tdsAmount, $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total']+$tdsAmount, $black);
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

function genarate_draexam_invoice_1_Nov_2019($invoice_no){
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id' => $invoice_no));
	
	
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

function genarate_draexam_invoice_old15oct19($invoice_no){
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id' => $invoice_no));
	
	
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

function genarate_exam_invoice_jk123($invoice_no){
	
		$CI = & get_instance();
		$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
		$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
		$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
		$wordamt = amtinword($invoice_info[0]['igst_total']);
		$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
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
		imagestring($im, 3, 22,  224, "Date Of Invoice :".$date_of_invoice, $black);
		imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
		
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black);
		imagestring($im, 3, 22,  262, "Member No: ".$invoice_info[0]['member_no'], $black);
		imagestring($im, 3, 22,  274, "Member Name: ".$member_name, $black);
		imagestring($im, 3, 22,  286, "Center Code : ".$invoice_info[0]['center_code'], $black);
		imagestring($im, 3, 22,  298, "Center Name : ".$invoice_info[0]['center_name'], $black);
		imagestring($im, 3, 22,  310, "State of Center : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service ", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
        
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, "1", $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt."only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "%", $black);
		imagestring($im, 3, 350,  932, "Rs.", $black);
		
		$savepath = base_url()."uploads/examinvoice/user/";
		$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
		$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
		
		$update_data = array('invoice_image' => $imagename);
		$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
		
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
		imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER", $black);
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black);
		imagestring($im, 3, 22,  262, "Member No: ".$invoice_info[0]['member_no'], $black);
		imagestring($im, 3, 22,  274, "Member Name: ".$member_name, $black);
		imagestring($im, 3, 22,  286, "Center Code : ".$invoice_info[0]['center_code'], $black);
		imagestring($im, 3, 22,  298, "Center Name : ".$invoice_info[0]['center_name'], $black);
		imagestring($im, 3, 22,  310, "State of Center : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service ", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
        
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Conduction of Exam", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, "1", $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words :  ".$wordamt."only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "%", $black);
		imagestring($im, 3, 350,  932, "Rs.", $black);
		
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

function genarate_reg_invoice_jk123($invoice_no){
	
		$CI = & get_instance();
		$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
		$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr');
		$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
		$wordamt = amtinword($invoice_info[0]['igst_total']);
		$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
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
		imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black);
		imagestring($im, 3, 22,  262, "Name of service Recipient: ".$member_name, $black);
		imagestring($im, 3, 22,  274, "Address:".$mem_info[0]['address1_pr'], $black);
		imagestring($im, 3, 22,  286, $mem_info[0]['address2_pr'], $black);
		imagestring($im, 3, 22,  298, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
		imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service ", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
        
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Membership Registration", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, "1", $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "%", $black);
		imagestring($im, 3, 350,  932, "Rs.", $black);
		
		$savepath = base_url()."uploads/reginvoice/user/";
		$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
		$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
		
		$update_data = array('invoice_image' => $imagename);
		$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
		
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
		imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info[0]['transaction_no'], $black);
		imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER", $black);
		
		imagestring($im, 3, 22,  250, "Details of service recipient", $black);
		imagestring($im, 3, 22,  262, "Name of service Recipient: ".$member_name, $black);
		imagestring($im, 3, 22,  274, "Address: ".$mem_info[0]['address1_pr'], $black);
		imagestring($im, 3, 22,  286, $mem_info[0]['address2_pr'], $black);
		imagestring($im, 3, 22,  298, $mem_info[0]['city_pr']."-".$mem_info[0]['pincode_pr'], $black);
		imagestring($im, 3, 22,  310, "State : ".$invoice_info[0]['state_name'], $black);
		imagestring($im, 3, 22,  322, "State Code : ".$invoice_info[0]['state_code'], $black);
		imagestring($im, 3, 22,  334, "GSTIN / Unique Id : NA", $black);
		
		imagestring($im, 3, 22,  530, "Sr.No", $black);
		imagestring($im, 3, 100,  530, "Description of goods & Service ", $black);
		imagestring($im, 3, 570,  508, "Accounting ", $black);
		imagestring($im, 3, 570,  520, "code", $black);
		imagestring($im, 3, 570,  532, "of Service", $black);
		imagestring($im, 3, 665,  530, "Rate per unit", $black);
		imagestring($im, 3, 780,  530, "Unit", $black);
		imagestring($im, 3, 900,  530, "Total", $black);
        
		imagestring($im, 3, 45,  560, "1", $black);
		imagestring($im, 3, 100,  560, "Membership Registration", $black);
		imagestring($im, 3, 590,  560, $invoice_info[0]['service_code'], $black);
		imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black);
		imagestring($im, 3, 780,  560, "1", $black);
		imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black);
		
		imagestring($im, 3, 500,  780, "Total", $black);
		imagestring($im, 3, 900,  780, $invoice_info[0]['igst_total'], $black);
		
		imagestring($im, 3, 22,  820, "Amount in words : ".$wordamt. " only", $black);
		imagestring($im, 3, 720,  910, "Authorised Signatory", $black);
		imagestring($im, 3, 22,  900, "Reverse charge applicable :", $black);
		imagestring($im, 3, 300,  900, "Y/N", $black);
		imagestring($im, 3, 350,  900, "NO", $black);
		imagestring($im, 3, 22,  920, "% of Tax payable under Reverse", $black);
		imagestring($im, 3, 22,  932, "Charge by recepient :", $black);
		imagestring($im, 3, 300,  932, "%", $black);
		imagestring($im, 3, 350,  932, "Rs.", $black);
		
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
	
function genarate_DISA_invoice($invoice_no){
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
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

function genarate_PB_invoice($invoice_no) //FOR PROFESSIONAL BANKER
{
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword($invoice_info[0]['cs_total']);
	}
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	$disp_exam_name = '';
	if(count($invoice_info) > 0)
	{
		$CI->db->limit(1);
		$exam_name_info = $CI->master_model->getRecords('exam_master',array('exam_code'=>$invoice_info[0]['exam_code']),'description');
		if(count($exam_name_info) > 0)
		{
			$disp_exam_name = $exam_name_info[0]['description'];
		}
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
	imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
	imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
	imagestring($im, 3, 40,  300, "Exam code : ".$invoice_info[0]['exam_code'], $black);
	imagestring($im, 3, 40,  320, "Exam Name : ".$disp_exam_name, $black);
	imagestring($im, 3, 40,  340, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  360, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  380, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  400, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  420, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  440, "GST No: -", $black);
	imagestring($im, 3, 40,  460, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
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
	imagestring($im, 3, 40,  320, "Exam Name : ".$disp_exam_name, $black);
	imagestring($im, 3, 40,  340, "Exam period :".$invoice_info[0]['exam_period'], $black);
	imagestring($im, 3, 40,  360, "Center code : ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  380, "Center name :".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  400, "State of center : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  420, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  440, "GST No: -", $black);
	imagestring($im, 3, 40,  460, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
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

function genarate_CISI_invoice($invoice_no){
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
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


function generate_credit_note($transaction_no,$transaction_type='0') //transaction_type : 0=>Regular, 1=>Bulk, 2=>DRA, 3=>JBIMS , 4=> AMP
{ 
	//echo $transaction_no; exit;
	
	$CI = & get_instance(); 
	$payment_txn = array();
	if($transaction_type == 0)
	{
		$payment_txn = $CI->master_model->getRecords('payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no, id, pay_type'); 
	}
	else if($transaction_type == 1)
	{
		$payment_txn = $CI->master_model->getRecords('bulk_payment_transaction',array('UTR_no'=>$transaction_no),'receipt_no, id, 0 as pay_type'); 
	}
	else if($transaction_type == 2)
	{
		$payment_txn = $CI->master_model->getRecords('dra_payment_transaction',array('UTR_no'=>$transaction_no),'receipt_no, id, 4 as pay_type');    
	}
	else if($transaction_type == 3)
	{
		$payment_txn = $CI->master_model->getRecords('JBIMS_payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no, id, 21 as pay_type');   
	}
	else if($transaction_type == 4)
	{
		$payment_txn = $CI->master_model->getRecords('amp_payment_transaction',array('transaction_no'=>$transaction_no),'receipt_no, id, 1 as pay_type');   
	}
	//echo "<br> Payment Qry : ".$CI->db->last_query(); //exit; 	
	
	/* if($transaction_type == 3)  
	{
		$CI->db->where('transaction_no',$transaction_no);  
	} */
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$payment_txn[0]['id'],'receipt_no'=>$payment_txn[0]['receipt_no'])); 
	//echo "<br> Invoice Qry : ".$CI->db->last_query();  exit;   
	//echo '<pre>';	
	//print_r($invoice_info); exit;   
	
	$mem_info = array();
	if($invoice_info[0]['member_no'] != "")
	{
		if($transaction_type == 2 && $payment_txn[0]['pay_type'] == 4) //FOR DRA MEMBER : CODE ADDED BY SAGAR ON 05-04-2021 TO GENERATE CREDIT NOTE
		{
			$mem_info = $CI->master_model->getRecords('dra_members',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
		}
		else if($transaction_type == 3 && $payment_txn[0]['pay_type'] == '21') //FOR JBIMS MEMBER : CODE ADDED BY SAGAR ON 04-04-2022 TO GENERATE CREDIT NOTE
		{ 
			$mem_info = $CI->master_model->getRecords('JBIMS_candidates',array('regnumber'=>$invoice_info[0]['member_no']),'name as firstname, "" as middlename, "" as lastname, address1,address2,address3,address4, "" AS district, city,pincode_address as pincode');
		}
		else if($transaction_type == 4 && $payment_txn[0]['pay_type'] == '1') //FOR AMP MEMBER : CODE ADDED BY SAGAR ON 04-04-2022 TO GENERATE CREDIT NOTE
		{ 
			$mem_info = $CI->master_model->getRecords('amp_candidates',array('regnumber'=>$invoice_info[0]['member_no']),'name as firstname, "" as middlename, "" as lastname, address1,address2,address3,address4, "" AS district, city,pincode_address as pincode');
		}  		
		else
		{
			$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,pincode');
		}
	}
	
	//echo "<br> Member Info Qry : ".$CI->db->last_query(); exit; 
	
	if(count($mem_info) > 0)
	{
		$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
		$address1 = $mem_info[0]['address1']." ".$mem_info[0]['address2']." ".$mem_info[0]['address3']." ".$mem_info[0]['address4'];
	
		$address2 = $mem_info[0]['district']." ".$mem_info[0]['city']." ".$mem_info[0]['pincode'];
	}
	else { $member_name = $address1 = $address2 = ""; }
	
	// echo "<br> member_name : ".$member_name; exit;
	//echo "<br> address1 : ".$address1; //exit;
	//echo "<br> address2 : ".$address2; //exit;
	
	if($invoice_info[0]['center_name'] !='' ) { $city = $invoice_info[0]['center_name']; }
	else { $city = ''; }
	
	if($transaction_type == 2)
	{
		$wordamt = amtinword($invoice_info[0]['igst_total']);
	}
	else
	{
		if($invoice_info[0]['state_of_center'] == 'MAH')
		{
			$wordamt = amtinword($invoice_info[0]['cs_total']);
		}
		elseif($invoice_info[0]['state_of_center'] != 'MAH')
		{
			$wordamt = amtinword($invoice_info[0]['igst_total']);
		}
	} 
	
	//echo "<br> wordamt : ".$wordamt; exit;
	//echo "<br>".$wordamt; exit;  
	
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	
	$exp = explode("/",$invoice_info[0]['invoice_no']);	
	
	$chk_config = $CI->master_model->getRecords('config_credit_note',array('invoice_id'=>$invoice_info[0]['invoice_id']));
	
	if(count($chk_config) == 0)
	{ 
		$config_inset_arr = array(
			'invoice_id' => $invoice_info[0]['invoice_id'],
			'created_date' => date('Y-m-d H:i:s')
		);
		$config_last_id = str_pad($CI->master_model->insertRecord('config_credit_note',$config_inset_arr,true), 6, "0", STR_PAD_LEFT); 
		//$config_last_id = $CI->master_model->insertRecord('config_credit_note_31_03_2023',$config_inset_arr,true);
	}
	else
	{ 
		$config_last_id = $chk_config[0]['creditnote_no'];
	}	
	
	$y = date('y');
	$ny = date('y')+1; 
	//$cr_imagename = "CN_".$exp[0]."_".$exp[1]."_".$exp[2].".jpg";
	//$credit_note_no = 'CDN/'.$exp[1].'/'.$config_last_id;

  $todays_date = date('Y-m-d');
  $chk_date = date("Y")."-03-31";
  
  $financial_year = date('y').'-'.date('y', strtotime("+1 year"));
  if($todays_date <= $chk_date)
  {
    $financial_year = date('y', strtotime("-1 year")).'-'.date('y');
  }

  $cr_imagename = "CN_".$exp[0]."_".$financial_year."_".$exp[2].".jpg";
	$credit_note_no = 'CDN/'.$financial_year.'/'.$config_last_id; 
	
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
  imagestring($im, 3, 100,  100, "www.iibf.org.in", $black);
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
	//imagestring($im, 3, 600,  340, "www.iibf.org.in", $black);
	imagestring($im, 3, 600,  340, "Credit Note no: ".$maker_rec[0]['credit_note_number'], $black);
	//imagestring($im, 3, 600,  380, "Date : ", $black);
	
	imagestring($im, 3, 600,  360, "Refund Date : ".date("d-m-Y", strtotime($maker_rec[0]['sbi_refund_date'])), $black);
	imagestring($im, 3, 600,  380, "GSTIN No: 27AAATT3309D1ZS", $black);
	
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
	
	imagestring($im, 3, 40,  860, "Amount in words : Rs. ".$wordamt, $black);
	imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
	imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
	
	$savepath = base_url()."uploads/CreditNote/";
	
	$ex = explode("/",$invoice_info[0]['invoice_no']);
	$imagename = "CN_".$ex[0]."_".$financial_year."_".$ex[2].".jpg";
	
	
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


	###-----generate exam invoice number---### 
 	function generate_exam_invoice_number($invoice_id= NULL)
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
	
	function generate_elearning_exam_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_elearning_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	###-----generate dra exam invoice number for NEFT Payment---### [Tejasvi]
 	function generate_draexam_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_draexam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	###-----generate exam invoice number jammu---###
 	function generate_exam_invoice_number_jammu($invoice_id= NULL)
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
	
	###-----generate exam invoice number---###
 	function generate_registration_invoice_number_new($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		$check_id = $CI->master_model->getRecords('config_reg_invoice',array('invoice_id'=>$invoice_id));
		if(!empty($check_id))
		{
			if(!empty($invoice_id))
			{
				$insert_info = array('invoice_id'=>$invoice_id);
				$last_id = str_pad($CI->master_model->insertRecord('config_reg_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			}
		}
		else
		{
			$last_id = $check_id['reg_invoice_no'];
		}
		return $last_id;
	}

	function genarate_career_invoice($invoice_no,$careers_id){
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
	
	$mem_info = $CI->master_model->getRecords('careers_registration',array('careers_id'=>$careers_id),'firstname,middlename,lastname,addressline1,addressline2,city,state,pincode');
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
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['addressline1']." ".$mem_info[0]['addressline2'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	
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
	imagestring($im, 3, 118,  600, "Application for the post of Junior Executive", $black);
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
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	
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
	imagestring($im, 3, 40,  280, "Address: ".$mem_info[0]['addressline1']." ".$mem_info[0]['addressline2'], $black);
	imagestring($im, 3, 40,  300, $mem_info[0]['city']."-".$mem_info[0]['pincode'], $black);
	
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
	
function generate_registration_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		
			if($invoice_id != NULL)
			{
				$insert_info = array('invoice_id'=>$invoice_id);
				$last_id = str_pad($CI->master_model->insertRecord('config_reg_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			}
		
		return $last_id;
	}
	function generate_dbftojaiibcr_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		
			if($invoice_id != NULL)
			{
				$insert_info = array('invoice_id'=>$invoice_id);
				$last_id = str_pad($CI->master_model->insertRecord('config_dbftojaiibcr_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
			}
		
		return $last_id;
	}
	###-----generate exam invoice number for jammu---###
 	function generate_registration_invoice_number_jammu($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_reg_invoice_jammu',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	function genarate_CITAP_invoice($invoice_no)
	{
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
		imagestring($im, 3, 118,  600, "Charges for CITAP Registration", $black);
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
		
		$update_data = array('invoice_image' => $imagename);
		$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
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
		imagestring($im, 3, 118,  600, "Charges for CITAP Registration", $black);
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

	function generate_DISA_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_DISA_invoice',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}

	function generate_CITAP_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_CITAP_invoice',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	function generate_CISI_invoice_number($invoice_id= NULL)
	{
		$last_id='';
		$CI = & get_instance();
		//$CI->load->model('my_model');
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('config_CISI_invoice',$insert_info,true), 5, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	
	
	## functions added for E-learning separate Module
	## Added by : Pratibha Purkar
	## Date: 25 Jun 2021
    function generate_el_invoice_number($invoice_id= NULL) 
	{
		$last_id='';
		$CI = & get_instance();
		if($invoice_id  !=NULL)
		{
			$insert_info = array('invoice_id'=>$invoice_id);
			$last_id = str_pad($CI->master_model->insertRecord('spm_elearning_config_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		}
		return $last_id;
	}
	
	function genarate_el_invoice($invoice_id){ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	$mem_info = $CI->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,state');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	$member_state = $mem_info[0]['state'];
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
	// $exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	
	// if(count($exam) > 0 &&  $exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
	// {
	// 	$ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
	// 	if(count($ex_period))
	// 	{
	// 		$exam_period = $ex_period[0]['period'];	
	// 	}
	// }else{
	// 	$exam_period = $exam[0]['exam_period'];
	// }
	
	
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
	imagestring($im, 3, 40,  300, "State: ".$member_state, $black);
	imagestring($im, 3, 40,  320, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  340, "GSTIN / Unique ID: ".$gstno, $black);
	/*imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);*/
	
	
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
	imagestring($im, 3, 118,  600, "E-learning", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, number_format($invoice_info[0]['fee_amt'] / $invoice_info[0]['qty'],2), $black);
	imagestring($im, 3, 820,  600, $invoice_info[0]['qty'], $black);
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
	
	$savepath = base_url()."uploads/Elearning_invoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/Elearning_invoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/Elearning_invoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/Elearning_invoice/user/'.$imagename);
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
	imagestring($im, 3, 40,  300, "State: ".$member_state, $black);
	imagestring($im, 3, 40,  320, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  340, "GSTIN / Unique ID: ".$gstno, $black);
	/*imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
	imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
	imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
	imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
	imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
	imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
	imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);*/
	
	
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
	imagestring($im, 3, 118,  600, "E-learning", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, number_format($invoice_info[0]['fee_amt'] / $invoice_info[0]['qty'],2), $black);
	imagestring($im, 3, 820,  600, $invoice_info[0]['qty'], $black);
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
	
	$savepath = base_url()."uploads/Elearning_invoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/Elearning_invoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/Elearning_invoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/Elearning_invoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/Elearning_invoice/user/".$imagename;
	
}


	
function genarate_el_recovery_invoice($invoice_id){ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
	if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
	{$gstno=$invoice_info[0]['gstin_no'];}
	else
	{$gstno='NA';}
	
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1_pr,address2_pr,address3_pr,address4_pr,city_pr,state_pr,pincode_pr');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	
	$member_state = $invoice_info[0]['state_name'];
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
		$invoice_exname = '-';
	}
	
	$exam_period = '';
	
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
	imagestring($im, 3, 40,  300, "State: ".$member_state, $black);
	imagestring($im, 3, 40,  320, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  340, "GSTIN / Unique ID: ".$gstno, $black);
	
	
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
	imagestring($im, 3, 118,  600, "E-learning", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, number_format($invoice_info[0]['fee_amt'] / $invoice_info[0]['qty'],2), $black);
	imagestring($im, 3, 820,  600, $invoice_info[0]['qty'], $black);
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
	
	$savepath = base_url()."uploads/Elearning_invoice/user/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
	
	imagepng($im,"uploads/Elearning_invoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/Elearning_invoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/Elearning_invoice/user/'.$imagename);
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
	imagestring($im, 3, 40,  300, "State: ".$member_state, $black);
	imagestring($im, 3, 40,  320, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	imagestring($im, 3, 40,  340, "GSTIN / Unique ID: ".$gstno, $black);
	
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
	imagestring($im, 3, 118,  600, "E-learning", $black);
	imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
	imagestring($im, 3, 690,  600, number_format($invoice_info[0]['fee_amt'] / $invoice_info[0]['qty'],2), $black);
	imagestring($im, 3, 820,  600, $invoice_info[0]['qty'], $black);
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
	
	$savepath = base_url()."uploads/Elearning_invoice/supplier/";
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	imagepng($im,"uploads/Elearning_invoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/Elearning_invoice/supplier/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/Elearning_invoice/supplier/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/Elearning_invoice/user/".$imagename;
	
}


/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */