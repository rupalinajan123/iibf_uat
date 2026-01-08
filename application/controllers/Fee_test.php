<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Fee_test extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->model('master_model');		
		$this->load->model('log_model');
		$this->load->helper('fee_helper');

	}
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	//get fee as per the cenrer selection (Prafull)	
	public function getFee()
	{
		
		$centerCode= $_POST['centerCode'];
		$eprid=$_POST['eprid'];
		$excd=$_POST['excd'];
		$grp_code=$_POST['grp_code'];
		$memcategory=$_POST['mtype'];
		//$memcategory=$this->session->userdata('memtype');
		
		//Prameter should be in following format
		//1) Center Code 2)Exam period 3)exam code 4)Group ccode 5) member type (eg, '495','117','8','B1','O')
		echo getExamFee_test($centerCode,$eprid,$excd,$grp_code,$memcategory);
		/*if($centerCode!="" && $eprid!="" && $excd!="" && $grp_code!="")
		{
			$getstate=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
			if(count($getstate) > 0)
			{
				if($grp_code=='')
				{
					$grp_code='B1';
				}
				 $today_date=date('Y-m-d');
				// $today_date='2017-08-15';
				// $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
				$this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
				$getfees=$this->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$this->session->userdata('memtype'),'exam_period'=>$eprid,'group_code'=>$grp_code));
				//echo $this->db->last_query();exit;
				if(count($getfees) > 0)
				{
					if($getstate[0]['state_code']=='MAH')
					{
						echo $getfees[0]['cs_tot'];
					}
					else
					{
						echo $getfees[0]['igst_tot'];
					}
				}
			}
		}*/
		exit;
	}

	function genarate_exam_invoice_test_pratibha($invoice_id){ 
		// exit;
        $invoice_id = '3683497';
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
		// print_r($invoice_exname); exit;
        
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

}

