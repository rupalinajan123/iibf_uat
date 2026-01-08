<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan invoice image genarate code
 
function amtinwordagnrew($amt){
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

function genarate_agnecy_renewal_invoice($invoice_no){ 
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
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
	
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

//EXM/DUP-CERT/FY/001 Sample: - EXM/DUP-CERT/2017-18/001. 
function generate_agnecy_renewal_invoice_number($invoice_id= NULL){
	$last_id='';
	$CI = & get_instance();
	//$CI->load->model('my_model');
	if($invoice_id  !=NULL)
	{
		$insert_info = array('invoice_id'=>$invoice_id);
		//$last_id = str_pad($CI->master_model->insertRecord('config_dup_cert_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		$last_id = str_pad($CI->master_model->insertRecord('config_agency_renewal_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
	}
	return $last_id;
}
	
/* End of file checkactiveexam_helper.php */
/* Location: ./application/helpers/invice_helper.php */
?>