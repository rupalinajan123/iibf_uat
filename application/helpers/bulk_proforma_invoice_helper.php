<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function bulk_proforma_amtinword($amt){
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

function generate_bulk_proforma_examinvoice($id){ 
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$id,'app_type'=>'Z'));
	//$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	//$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$institute_info = $CI->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info[0]['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
		
		
		$pay_info = $CI->master_model->getRecords('bulk_payment_transaction',array('id'=>$id),'id,created_date');
	
	$state_info = $CI->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
	
	$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
	
	
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
		return custom_genarate_exam_invoice_jk($invoice_no);
		exit;
	}
	
	
	if($institute_info[0]['ste_code'] == 'MAH'){
		$wordamt = bulk_proforma_amtinword(intval($invoice_info[0]['cs_total']));
		//$wordamt = bulk_proforma_amtinword(7516);
	}elseif($institute_info[0]['ste_code'] != 'MAH'){
	    $wordamt = bulk_proforma_amtinword(intval($invoice_info[0]['igst_total']));
		//$wordamt = bulk_proforma_amtinword(7516); 
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
	
	imagestring($im, 5, 455,  30, "Proforma Invoice", $black);
	
	imagestring($im, 5, 155,  70, "", $black);
	
	imagestring($im, 3, 22,  100, "Name of the Assessee: INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".	$pay_info[0]['id'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : ".date('d-m-Y',strtotime($pay_info[0]['created_date'])), $black);
	//imagestring($im, 3, 22,  236, "Transaction no : ".'', $black);
//	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	
	
	imagestring($im, 3, 22,  274, "Details of service recipient", $black);
	imagestring($im, 3, 22,  298, "Name of the Recipient: ".$institute_info[0]['institute_name'], $black);
	imagestring($im, 3, 22,  310, "Address: ".$institute_info[0]['address1'], $black);
	imagestring($im, 3, 22,  322, $institute_info[0]['address2']." ".$institute_info[0]['address3'], $black);
	imagestring($im, 3, 22,  334, $institute_info[0]['address4']." ".$institute_info[0]['address5'], $black);
	imagestring($im, 3, 22,  346, $institute_info[0]['address6'], $black);
	imagestring($im, 3, 22,  358, "State: ".$state_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  370, "State code: ".$state_info[0]['state_no'], $black);
	imagestring($im, 3, 22,  380, "GSTIN / Unique Id : ".$institute_info[0]['gstin_no'], $black);
	
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
	imagestring($im, 3, 900,  560, date($invoice_info[0]['fee_amt']), $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	imagestring($im, 3, 45,  660, "Less", $black);
	imagestring($im, 3, 100,  660, "Discount -", $black);
	imagestring($im, 3, 690,  660, "-", $black);
	imagestring($im, 3, 900,  660, $invoice_info[0]['disc_amt'], $black);
	
	imagestring($im, 3, 100,  680, "NET-", $black);
	imagestring($im, 3, 690,  680, "-", $black);
	imagestring($im, 3, 900,  680, number_format($net_amt, 2, '.', '') , $black);
	
	
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
	
	$savepath = base_url()."uploads/bulk_proforma_examinvoice/";
	$ino = str_replace("/","_",$invoice_info[0]['exam_code']);
	$prd = str_replace("/","_",$invoice_info[0]['exam_period']);
	$imagename = "bulk_".$invoice_info[0]['pay_txn_id'].".jpg";
	
	//$update_data = array('invoice_image' => $imagename);
	//$CI->master_model->updateRecord('exam_invoice',$update_data,array('pay_txn_id'=>$id));
	
	imagepng($im,"uploads/bulk_proforma_examinvoice/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/bulk_proforma_examinvoice/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/bulk_proforma_examinvoice/'.$imagename);
	
	
	
	imagedestroy($im);
	
	
	return $attachpath = "uploads/bulk_proforma_examinvoice/".$imagename;
	
}





	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */