<?php defined('BASEPATH')||exit('No Direct Allowed Here');

function generate_gst_recovery_invoice_number($invoice_id= NULL){  
	$last_id='';
	$CI = & get_instance();
	if($invoice_id  !=NULL)
	{
		$insert_info = array('gst_recovery_details_fk'=>$invoice_id,'created_date' => date('Y-m-d H:i:s'));
		$last_id = str_pad($CI->master_model->insertRecord('config_gst_recovery_doc',$insert_info,true),4,"0",STR_PAD_LEFT);
		//echo $CI->db->last_query(); 
	}
	return $last_id;
}

function genarate_gst_recovery_invoice($gst_recovery_details_pk)
{ 
//$gst_recovery_details_pk = '1';
	$CI = & get_instance();
	$invoice_info = $CI->master_model->getRecords('gst_recovery_details',array('gst_recovery_details_pk'=>$gst_recovery_details_pk));
	
	/* Get Member Details */
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname,address1,address2,address3,address4,city,state,pincode');
	$member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
	
	$wordamt = amtinword_gst_recovery($invoice_info[0]['igst_amt']);
	$date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
	$date_of_doc = date("d-m-Y", strtotime($invoice_info[0]['date_of_doc']));
	
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
	imageline ($im,   20,  280, 980, 280, $black); // line-7
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
	//imageline ($im,   20,  855, 490, 855, $black); // line-18
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Debit Note", $black);
	imagestring($im, 5, 155,  70, "", $black);
	imagestring($im, 3, 22,  100, "Name of the Supplier : INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  114, "GSTIN : 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  126, "Address : ", $black);
	imagestring($im, 3, 22,  138, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  150, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  162, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  174, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  186, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  214, "Nature Of Document : Debit Note", $black);
	imagestring($im, 3, 22,  226, "Document No. : ".$invoice_info[0]['doc_no'], $black);
	imagestring($im, 3, 22,  238, "Date Of Document : ".$date_of_doc, $black);
	imagestring($im, 3, 22,  250, "Original Invoice No. : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  262, "Original Invoice Date : ".$date_of_invoice, $black);
	
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	
	imagestring($im, 3, 22,  290, "Details of Buyer (Billed to)", $black);
	imagestring($im, 3, 22,  308, "Name of the Buyer : ".$member_name, $black);
	imagestring($im, 3, 22,  323, "Address : ".$mem_info[0]['address1'].' '.$mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  338, " ".$mem_info[0]['address3'].' '.$mem_info[0]['address4']." ".$mem_info[0]['city'].' '.$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  353, "GSTIN/UIN : NA", $black);
	imagestring($im, 3, 22,  368, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  383, "State Code : ".$invoice_info[0]['state_code'], $black);
	
	$igst_rate = "18.00%";
	$igst_amt = $invoice_info[0]['igst_amt'];
	
	imagestring($im, 3, 30,   510, "Sr.No", $black);
	imagestring($im, 3, 100,  510, "Description of Service", $black);
	imagestring($im, 3, 590,  510, "SAC Code", $black);
	imagestring($im, 3, 665,  510, "Rate per unit", $black);
	imagestring($im, 3, 780,  510, "Unit", $black);
	imagestring($im, 3, 900,  510, "Total", $black);
	
	imagestring($im, 3, 45,   560, "1", $black);
	imagestring($im, 3, 100,  560, "Recovery of Taxes", $black);
	imagestring($im, 3, 590,  560, '', $black);
	//imagestring($im, 3, 690,  560, $igst_amt, $black);
	//imagestring($im, 3, 780,  560, "1", $black); 
	//imagestring($im, 3, 900,  560, $igst_amt, $black);
	imagestring($im, 3, 100,  660, "For Inter State Supply -", $black);
	imagestring($im, 3, 900,  678, $igst_amt, $black);
	imagestring($im, 3, 100,  672, "IGST : ", $black);
	imagestring($im, 3, 690,  678, $igst_rate, $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	imagestring($im, 3, 900,  780, $igst_amt, $black);
	
	//imagestring($im, 3, 22,  820, "Total Taxable Value In Words : ".''."only", $black);
	imagestring($im, 3, 22,  820, "Total Taxes In Words : ".$wordamt."only", $black);
	imagestring($im, 3, 710,  910, "Signature or Digital signature of", $black);
	imagestring($im, 3, 720,  930, "Authorised Signatory", $black);

	$savepath = base_url()."uploads/gst_recovery_invoice/user/";
	
	$ino = str_replace("/","_",$invoice_info[0]['doc_no']);
	
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	//$update_data = array('doc_image' => $imagename);
	//$CI->master_model->updateRecord('gst_recovery_details',$update_data,array('gst_recovery_details_pk'=>$gst_recovery_details_pk));
	
	imagepng($im,"uploads/gst_recovery_invoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/gst_recovery_invoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/gst_recovery_invoice/user/'.$imagename);
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
	imageline ($im,   20,  280, 980, 280, $black); // line-7
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
	//imageline ($im,   20,  855, 490, 855, $black); // line-18
	imageline ($im,   860,  770, 980, 770, $black); // line-19
	//imagestring ($im, size, X,  Y, text, $red);
	$year = date('Y');
	imagestring($im, 5, 455,  30, "Debit Note", $black);
	imagestring($im, 5, 155,  70, "", $black);
	imagestring($im, 3, 22,  100, "Name of the Supplier : INDIAN INSTITUTE OF BANKING & FINANCE", $black);
	imagestring($im, 3, 22,  114, "GSTIN : 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  126, "Address : ", $black);
	imagestring($im, 3, 22,  138, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  150, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  162, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  174, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  186, "State Code : 27", $black);
	
	imagestring($im, 3, 22,  214, "Nature Of Document : Debit Note", $black);
	imagestring($im, 3, 22,  226, "Document No. : ".$invoice_info[0]['doc_no'], $black);
	imagestring($im, 3, 22,  238, "Date Of Document : ".$date_of_doc, $black);
	imagestring($im, 3, 22,  250, "Original Invoice No. : ".$invoice_info[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  262, "Original Invoice Date : ".$date_of_invoice, $black);
	
	imagestring($im, 3, 800,  100, "ORIGINAL FOR SUPPLIER ", $black);
	
	imagestring($im, 3, 22,  290, "Details of Buyer (Billed to)", $black);
	imagestring($im, 3, 22,  308, "Name of the Buyer : ".$member_name, $black);
	imagestring($im, 3, 22,  323, "Address : ".$mem_info[0]['address1'].' '.$mem_info[0]['address2'], $black);
	imagestring($im, 3, 22,  338, " ".$mem_info[0]['address3'].' '.$mem_info[0]['address4']." ".$mem_info[0]['city'].' '.$mem_info[0]['pincode'], $black);
	imagestring($im, 3, 22,  353, "GSTIN/UIN : NA", $black);
	imagestring($im, 3, 22,  368, "State : ".$invoice_info[0]['state_name'], $black);
	imagestring($im, 3, 22,  383, "State Code : ".$invoice_info[0]['state_code'], $black);
	
	$igst_rate = "18.00%";
	$igst_amt = $invoice_info[0]['igst_amt'];
	
	imagestring($im, 3, 30,   510, "Sr.No", $black);
	imagestring($im, 3, 100,  510, "Description of Service", $black);
	imagestring($im, 3, 590,  510, "SAC Code", $black);
	imagestring($im, 3, 665,  510, "Rate per unit", $black);
	imagestring($im, 3, 780,  510, "Unit", $black);
	imagestring($im, 3, 900,  510, "Total", $black);
	
	imagestring($im, 3, 45,   560, "1", $black);
	imagestring($im, 3, 100,  560, "Recovery of Taxes", $black);
	imagestring($im, 3, 590,  560, '', $black);
	//imagestring($im, 3, 690,  560, $igst_amt, $black);
	//imagestring($im, 3, 780,  560, "1", $black); 
	//imagestring($im, 3, 900,  560, $igst_amt, $black);
	imagestring($im, 3, 100,  660, "For Inter State Supply -", $black);
	imagestring($im, 3, 900,  678, $igst_amt, $black);
	imagestring($im, 3, 100,  672, "IGST : ", $black);
	imagestring($im, 3, 690,  678, $igst_rate, $black);
	imagestring($im, 3, 500,  780, "Total", $black);
	imagestring($im, 3, 900,  780, $igst_amt, $black);
	
	//imagestring($im, 3, 22,  820, "Total Taxable Value In Words : ".''."only", $black);
	imagestring($im, 3, 22,  820, "Total Taxes In Words : ".$wordamt."only", $black);
	imagestring($im, 3, 710,  910, "Signature or Digital signature of", $black);
	imagestring($im, 3, 720,  930, "Authorised Signatory", $black);

	$savepath = base_url()."uploads/gst_recovery_invoice/supplier/";
	
	$ino = str_replace("/","_",$invoice_info[0]['doc_no']);
	
	$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	
	//$update_data = array('doc_image' => $imagename);
	//$CI->master_model->updateRecord('gst_recovery_details',$update_data,array('gst_recovery_details_pk'=>$gst_recovery_details_pk));
	
	imagepng($im,"uploads/gst_recovery_invoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/gst_recovery_invoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/gst_recovery_invoice/supplier/'.$imagename);
	imagedestroy($im);
	
	
	return $attachpath = "uploads/gst_recovery_invoice/user/".$imagename;
}

function amtinword_gst_recovery($amt)
{
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