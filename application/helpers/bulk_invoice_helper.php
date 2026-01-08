<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function bulk_amtinword($amt){
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
    " and " . $words[$point / 10] . " " . 
          $words[$point = $point % 10] : '';
  //echo $result . "Rupees  " . $points . " Paise";
  //return $result;
	if($points != "") { $points = $points." paise"; }
	return str_replace("  "," ",$result . "rupees" . $points);
}

function generate_bulk_examinvoice($id){
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
	
	imagestring($im, 3, 40,  280, "Name of the Recipient: ".$institute_info[0]['institute_name'], $black);
	imagestring($im, 3, 40,  300, "Address: ".$institute_info[0]['address1']." ".$institute_info[0]['address2'], $black);
	imagestring($im, 3, 40,  320, $institute_info[0]['address3']." ".$institute_info[0]['address4'], $black);
	imagestring($im, 3, 40,  340, $institute_info[0]['address5']." ".$institute_info[0]['address6'], $black);
	
	imagestring($im, 3, 40,  360, "State: ".$state_info[0]['state_name'], $black);
	imagestring($im, 3, 40,  380, "State Code: ".$state_info[0]['state_no'], $black);
	imagestring($im, 3, 40,  400, "GST No: ".$institute_info[0]['gstin_no'], $black);
	imagestring($im, 3, 40,  420, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
	
	$CI->db->where('ptid',$invoice_info[0]['pay_txn_id']);
	$query1 = $CI->master_model->getRecords('bulk_member_payment_transaction','','memexamid');
	
	$CI->db->where('exam_code',1015);
	$CI->db->where('id',$query1[0]['memexamid']);
	$query2 = $CI->master_model->getRecords('member_exam','','exam_center_code');
	
	$CI->db->where('exam_name',1015);
	$CI->db->where('center_code',$query2[0]['exam_center_code']);
	$center_name = $CI->master_model->getRecords('center_master','','center_name');
	
	if($invoice_info[0]['exam_code'] == 1015){
		imagestring($im, 3, 40,  440, "Center Code : ".$center_name[0]['center_name'], $black);
	}
	
	
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
	
	
	
	$savepath = base_url()."uploads/bulkexaminvoice/user/";
	
	$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$imagename = "bulk_".$invoice_info[0]['institute_code']."_".$ino.".jpg";
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('pay_txn_id'=>$id,'app_type'=>'Z'));
	
	imagepng($im,"uploads/bulkexaminvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bulkexaminvoice/user/".$imagename);
	
	//imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
	@imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
	@imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
	imagepng($im, 'uploads/bulkexaminvoice/user/'.$imagename);
	imagedestroy($im);
	
	
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
	
	$CI->db->where('ptid',$invoice_info[0]['pay_txn_id']);
	$query1 = $CI->master_model->getRecords('bulk_member_payment_transaction','','memexamid');
	
	$CI->db->where('exam_code',1015);
	$CI->db->where('id',$query1[0]['memexamid']);
	$query2 = $CI->master_model->getRecords('member_exam','','exam_center_code');
	
	$CI->db->where('exam_name',1015);
	$CI->db->where('center_code',$query2[0]['exam_center_code']);
	$center_name = $CI->master_model->getRecords('center_master','','center_name');
	
	if($invoice_info[0]['exam_code'] == 1015){
		imagestring($im, 3, 40,  440, "Center Code : ".$center_name[0]['center_name'], $black);
	} 
	
	
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
	
	return $attachpath = "uploads/bulkexaminvoice/user/".$imagename;
}

function bulk_generate_exam_invoice_number($invoice_id= NULL){
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

function bulk_exam_invoice_dynamic($transaction_no){
	
	$invoice_no = $transaction_no;
	
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$invoice_no));
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = "demo user";
	
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
	imageline ($im,   20,  495, 980, 495, $black); // line-10
	imageline ($im,   20,  845, 980, 845, $black); // line-11
	imageline ($im,   490,  100, 490, 400, $black); // line-12
	imageline ($im,   80,  450, 80, 845, $black); // line-13
	imageline ($im,   560,  450, 560, 845, $black); // line-14
	imageline ($im,   660,  450, 660, 845, $black); // line-15
	imageline ($im,   760,  450, 760, 845, $black); // line-16
	imageline ($im,   860,  450, 860, 845, $black); // line-17
	imageline ($im,   20,  875, 490, 875, $black); // line-18
	imageline ($im,   860,  810, 980, 810, $black); // line-19
	
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
	}elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}
	elseif($invoice_info[0]['exam_code'] == 590){
		$exam_code = 59;
	}
	elseif($invoice_info[0]['exam_code'] == 810){
		$exam_code = 81;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	
	$exam_period = '007';
	
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
	
	imagestring($im, 3, 22,  460, "Sr.No", $black);
	imagestring($im, 3, 100,  460, "Description of Service", $black);
	imagestring($im, 3, 570,  460, "Accounting ", $black);
	imagestring($im, 3, 570,  470, "code", $black);
	imagestring($im, 3, 570,  480, "of Service", $black);
	imagestring($im, 3, 665,  460, "Rate per unit", $black);
	imagestring($im, 3, 780,  460, "Unit", $black);
	imagestring($im, 3, 900,  460, "Total", $black);
	
	$i = 500;
	$j = 1;
	$total_sum = array();
	foreach($invoice_info as $invoice_info_rec){
	
	imagestring($im, 3, 45,  $i, $j, $black);
	imagestring($im, 3, 95,  $i, "CERTIFICATE EXAMINATION FOR BUSINESS CORRESPONDENTS / FACILITATORS", $black);
	imagestring($im, 3, 590,  $i, $invoice_info[0]['service_code'], $black);//Accounting
	imagestring($im, 3, 690,  $i, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  $i, "1", $black); // Quantity 
	imagestring($im, 3, 900,  $i, $invoice_info[0]['fee_amt'], $black); // Total
	
	$i = $i + 20;
	$j++;
	$total_sum[] = $invoice_info[0]['fee_amt'];
	}
	
	
	
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		
		imagestring($im, 3, 95,  727, "Total Discount -", $black);
		imagestring($im, 3, 690,  727, "9.00%", $black);
		imagestring($im, 3, 900,  727, "500.00", $black);
		imagestring($im, 3, 95,  747, "Total TDS -", $black);
		imagestring($im, 3, 690,  747, "9.00%", $black);
		imagestring($im, 3, 900,  747, "500.00", $black);
		
		$total_sum[] = "500.00";
		$total_sum[] = "500.00";
		
		imagestring($im, 3, 95,  777, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  777, "Central Tax:", $black);
		imagestring($im, 3, 690,  777, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  777, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  790, "State Tax:", $black);
		imagestring($im, 3, 690,  790, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  790, $invoice_info[0]['sgst_amt'], $black);
		
		$total_sum[] = $invoice_info[0]['cgst_amt'];
		$total_sum[] = $invoice_info[0]['sgst_amt'];
		
		/*imagestring($im, 3, 95,  777, "For inter-state supply -", $black);
		imagestring($im, 3, 300,  787, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  787, "-", $black);
		imagestring($im, 3, 900,  787, "-", $black);*/
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH'){
		/*imagestring($im, 3, 95,  727, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  727, "Central Tax:", $black);
		imagestring($im, 3, 690,  727, "-", $black);
		imagestring($im, 3, 900,  727, "-", $black);
		imagestring($im, 3, 300,  740, "State Tax:", $black);
		imagestring($im, 3, 690,  740, "-", $black);
		imagestring($im, 3, 900,  740, "-", $black);*/
		imagestring($im, 3, 95,  727, "Total Discount -", $black);
		imagestring($im, 3, 690,  727, "9.00%", $black);
		imagestring($im, 3, 900,  727, "500.00", $black);
		imagestring($im, 3, 95,  747, "Total TDS -", $black);
		imagestring($im, 3, 690,  747, "9.00%", $black);
		imagestring($im, 3, 900,  747, "500.00", $black);
		
		$total_sum[] = "500.00";
		$total_sum[] = "500.00";
		
		
		imagestring($im, 3, 95,  777, "For inter-state supply -", $black);
		imagestring($im, 3, 300,  787, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  787, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  787, $invoice_info[0]['igst_amt'], $black);
		
		$total_sum[] = $invoice_info[0]['igst_amt'];
	}
	
	
	
	imagestring($im, 3, 500,  820, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  820, array_sum($total_sum), $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		imagestring($im, 3, 900,  820, array_sum($total_sum), $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = amtinword(array_sum($total_sum));
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = amtinword(array_sum($total_sum));
	}
	
	imagestring($im, 3, 22,  855, "Amount in words :".$wordamt."only", $black);
	imagestring($im, 3, 720,  935, "Authorised Signatory", $black);
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
	
	$update_data = array('invoice_image' => $imagename);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
	imagepng($im,"uploads/examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 875, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/examinvoice/user/'.$imagename);
	imagedestroy($im);
	
	return $attachpath = "uploads/examinvoice/user/".$imagename;
}


	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */