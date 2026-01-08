<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan Admitcard genarate code


function genarate_admitcard($member_id,$exam_code,$exam_period)
{		
	try{
		
		//$member_id = 700001459;
		//$exam_code = 42;
		//$exam_period = 217;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code,  mem_exam_id,insname, DATE_FORMAT(created_on, "%d%m%Y%H%i%s") as created_on,center_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		if($exam_code=='997' || $exam_code=='991' || $exam_code=='1052' || $exam_code=='1054' || $exam_code=='1053') // priyanka d - 20-july-23 >> to solve issue of showing old entries in admit card
		{
			$CI->db->where('admit_card_details.exam_date >=',date('Y-m-d'));
		}
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		$center = $member_result->center_name;

		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if($exam_code == $CI->config->item('examCodeJaiib')){
			if(isset($member_result->center_code) && $member_result->center_code==306){
				$vcenter = 3;
			}
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		if($exam_code=='997' || $exam_code=='991' || $exam_code=='1052' || $exam_code=='1054' || $exam_code=='1053') // priyanka d - 20-july-23 >> to solve issue of showing old entries in admit card
		{
			$CI->db->where('admit_card_details.exam_date >=',date('Y-m-d'));
		}
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) and admit_card_details.exm_cd=admit_subject_master.exam_code');// priyanka d - 02-feb-23 >> added exam_condition for join to avoid duplicate subjects in admitcard pdf
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		if($exam_code=='997' || $exam_code=='991' || $exam_code=='1052' || $exam_code=='1054' || $exam_code=='1053') // priyanka d - 20-july-23 >> to solve issue of showing old entries in admit card
		{
			$CI->db->where('admit_card_details.exam_date >=',date('Y-m-d'));
		}
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 
		$examdate = explode("-",$exdate);
		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
		
		
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';

		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$CI->db->select('medium_description');
		$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();

		$memberDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','associatedinstitute')); //priyanka d -feb-23
		$instituteDetails = $CI->master_model->getRecords('institution_master',array('institude_id' => $memberDetails[0]['associatedinstitute']),array('name')); //priyanka d -feb-23
		if(!empty($instituteDetails)) {
			$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
		}
		
		$CI->db->join('institution_master', 'member_registration.associatedinstitute = institution_master.institude_id'); 
		$instituteDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','institution_master.name as associatedinstitute')); //priyanka d -feb-23
			

			$CI->db->select('optFlg,selected_vendor');
			$CI->db->from('member_exam');
			$CI->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));		
			$CI->db->order_by("id", "desc");
			$optFlgRecordRow = $CI->db->get();
			$optFlgRecord=$selected_vendor='';
				if(!empty($optFlgRecordRow)) {
					$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
					$selected_vendor = $optFlgRecordRow->row()->selected_vendor;
				}
		
		
		$CI->db->select('transaction_no');
		$payment = $CI->db->get_where('payment_transaction', array('member_regnumber' => $member_result->mem_mem_no, 'ref_id' => $member_result->mem_exam_id));
		$payment_result = $payment->row();

		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord,'selected_vendor'=>$selected_vendor,'transaction_no'=>$payment_result->transaction_no,'center'=>$center); //priyanka d -feb-23 added instituteDetails,memberDetails
		
		// echo "<pre>"; 
		// print_r($member_result);
		// echo "<pre>"; 
		// print_r($memberDetails); exit;

		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020 || $exam_code == 1031 || $exam_code == 1032 || $exam_code == 1058 || $exam_code == 1062 || $exam_code == 1063 || $exam_code == 1064 || $exam_code == 1065 || $exam_code == 1066 || $exam_code == 1067 || $exam_code == 1068 || $exam_code == 1069){ 
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('admitcardpdf_attach', $data, true);
		}
		
		//echo $optFlgRecord;
		// 
		//echo $html;exit;
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$skipped_admitcard_examcodes =$CI->config->item('skippedAdmitCardForExams'); //priyanka d >> 16-jan-25 to skip admitcard
		if(in_array($exam_code,$skipped_admitcard_examcodes))  {

			$path = $pdf->Output('uploads/skippedAdmitCardForExams/'.$pdfFilePath, "F"); 
		}
		else 
			$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		//echo $path;exit;
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($admit_card_details as $admit_card_update){
		foreach($admit_card_details->result_array() as $admit_card_update){
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));
			
			//$CI->db->where('remark', 1);
			//$CI->db->where('admitcard_image', '');
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->update('admit_card_details',$update_data);	
			
			//$last_update_query_error = $CI->db->_error_message();
			
			$last_update_query = $CI->db->last_query();
			
			$log_title ="Admitcard filename updated in for loop. id:".$admit_card_update['admitcard_id'];
			//$log_message = serialize($admit_card_details);
			//$log_message = $last_update_query."|".$last_update_query_error;
			$log_message = $last_update_query;
			$rId = $admit_card_update['admitcard_id'];
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
			
		}
		
		// code to check if admi card file name updated
		//$admit_card_details_update = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
		
		$admit_card_details_update = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
		
		
		if(count($admit_card_details_update->result_array()) > 0)
		{
			//$CI->db->where('exm_cd', $exam_code);
			//$CI->db->where('exm_prd', $exam_period);
			//$CI->db->where('remark', 1);
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$member_id));
			
			
			$CI->db->where('mem_mem_no', $member_id);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('exm_cd', $exam_code);
			$CI->db->where('exm_prd', $exam_period);
			$CI->db->update('admit_card_details',$update_data);	
			
			
			$log_title ="Admitcard filename updated in 2nd update. Member id:".$member_id;
			$log_message = serialize($admit_card_details_update->result_array());
			$rId = $member_id;
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
		}
		// eof code to check if admi card file name updated
	//	echo 'uploads/admitcardpdf/'.$pdfFilePath;;
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function remote_genarate_admitcard($member_id,$exam_code,$exam_period)

{
	try{

		

		//$member_id = 700001459;

		//$exam_code = 42;

		//$exam_period = 217;

		$CI = & get_instance();	

		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));

		

		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');

		$CI->db->from('admit_card_details');

		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

		$CI->db->order_by("admitcard_id", "desc");

		$member_record = $CI->db->get();

		$member_result = $member_record->row();

		

		if(sizeof($member_result) == 0){

			return '';

			exit;

		}else{

		

		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){

			$vcenter = $member_result->vendor_code;

		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){

			$vcenter = '0';

		}

		if($exam_code == $CI->config->item('examCodeJaiib')){

			if(isset($member_result->center_code) && $member_result->center_code==306){

				$vcenter = 3;

			}

		}

		

		$medium_code = $member_result->m_1;

		

		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');

		$CI->db->from('admit_card_details');

		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

		$CI->db->group_by('venueid');

		$CI->db->order_by("exam_date", "asc");

		$venue_record = $CI->db->get();

		$venue_result = $venue_record->result();

		

		$CI->db->select('description');

		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));

		$exam_result = $exam->row();

		

		$CI->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');

		$CI->db->from('admit_card_details');

		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');

		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'exm_cd'=>$exam_code));

		$CI->db->where('pwd!=','');

		$CI->db->where('seat_identification!=','');

		$CI->db->where('remark',1);

		$CI->db->order_by("admit_card_details.exam_date", "asc");

		$subject = $CI->db->get();

		$subject_result = $subject->result();

		

		$pdate = $subject->result();

		foreach($pdate as $pdate){

			$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;

			$examdate = explode("-",$exdate);

			$examdatearr[] = $examdate[1];

		}

		

		$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 

		$examdate = explode("-",$exdate);

		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];

		

		

		

		if($medium_code == 'ENGLISH' || $medium_code == 'E'){

			$medium_code_lng = 'E';

		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){

			$medium_code_lng = 'H';

		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){

			$medium_code_lng = 'A';

		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){

			$medium_code_lng = 'G';

		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){

			$medium_code_lng = 'K';

		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){

			$medium_code_lng = 'L';

		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){

			$medium_code_lng = 'M';

		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){

			$medium_code_lng = 'N';



		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){

			$medium_code_lng = 'O';

		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){

			$medium_code_lng = 'S';

		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){

			$medium_code_lng = 'T';

		}

		

		$CI->db->select('medium_description');

		$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));

		$medium_result = $medium->row();

		

		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);

		

		$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);

		$CI->load->library('m_pdf');

		$pdf = $CI->m_pdf->load();

		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";

		$pdf->WriteHTML($html);

		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 

		

		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

		

		

		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));

		

		$update_data = array('admitcard_image' => $pdfFilePath);

		//foreach($admit_card_details as $admit_card_update){

		foreach($admit_card_details->result_array() as $admit_card_update){

			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));

			

			/*$CI->db->where('remark', 1);

			$CI->db->where('admitcard_image', '');

			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));*/

			

			

			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);

			$CI->db->where('remark', 1);

			$CI->db->where('admitcard_image', '');

			$CI->db->update('admit_card_details',$update_data);	

			

			//$last_update_query_error = $CI->db->_error_message();

			

			$last_update_query = $CI->db->last_query();

			

			$log_title ="Admitcard filename updated in for loop. id:".$admit_card_update['admitcard_id'];

			//$log_message = serialize($admit_card_details);

			//$log_message = $last_update_query."|".$last_update_query_error;

			$log_message = $last_update_query;

			$rId = $admit_card_update['admitcard_id'];

			$regNo = $member_id;

			//storedUserActivity($log_title, $log_message, $rId, $regNo);

			$log_data['title'] = $log_title;

			$log_data['description'] = $log_message;

			$log_data['regid'] = $rId;

			$log_data['regnumber'] = $regNo;

			$CI->db->insert('userlogs', $log_data);

			

		}

		

		// code to check if admi card file name updated

		//$admit_card_details_update = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));

		

		$admit_card_details_update = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));

		

		

		if(count($admit_card_details_update->result_array()) > 0)

		{

			/*$CI->db->where('exm_cd', $exam_code);

			$CI->db->where('exm_prd', $exam_period);

			$CI->db->where('remark', 1);

			$CI->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$member_id));*/

			

			

			$CI->db->where('mem_mem_no', $member_id);

			$CI->db->where('remark', 1);

			$CI->db->where('admitcard_image', '');

			$CI->db->where('exm_cd', $exam_code);

			$CI->db->where('exm_prd', $exam_period);

			$CI->db->update('admit_card_details',$update_data);	

			

			

			$log_title ="Admitcard filename updated in 2nd update. Member id:".$member_id;

			$log_message = serialize($admit_card_details_update->result_array());

			$rId = $member_id;

			$regNo = $member_id;

			//storedUserActivity($log_title, $log_message, $rId, $regNo);

			$log_data['title'] = $log_title;

			$log_data['description'] = $log_message;

			$log_data['regid'] = $rId;

			$log_data['regnumber'] = $regNo;

			$CI->db->insert('userlogs', $log_data);

		}

		// eof code to check if admi card file name updated

		

		return 'uploads/admitcardpdf/'.$pdfFilePath;

		

		}

		

	}catch(Exception $e){

		echo $e->getMessage();

	}

	

}



   function genarate_admitcard_test($member_no) 
   {
	try{
		 
		$member_id = $member_no;  
		
		$exam_period = 217;
		$CI = & get_instance();	
		$exam_code = $CI->config->item('examCodeJaiib');
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		 
		//get photo and signature of member
		$mem_data = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('regnumber','scannedphoto','scannedsignaturephoto'));
		$scannedphoto = @$mem_data[0]['scannedphoto'];
		$scannedsignaturephoto = @$mem_data[0]['scannedsignaturephoto'];
		
		if(!empty($scannedphoto) && ($scannedsignaturephoto))
		{
		    $scannedphoto = 'https://iibf.esdsconnect.com/uploads/photograph/'.$scannedphoto;
		    $scannedsignaturephoto = 'https://iibf.esdsconnect.com/uploads/scansignature/'.$scannedsignaturephoto;
		}
		else
		{
			$scannedphoto = get_img_name($member_id,'p'); 
			$scannedphoto = 'https://iibf.esdsconnect.com/'.$scannedphoto;
			$scannedsignaturephoto = get_img_name($member_id,'s');
			$scannedsignaturephoto = 'https://iibf.esdsconnect.com/'.$scannedsignaturephoto;
		}
		
		
		
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if(isset($member_result->center_code) && $member_result->center_code==306){
			$vcenter = 2;
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 
		$examdate = explode("-",$exdate);
		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
		
		
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';

		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$CI->db->select('medium_description');
		$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'scannedphoto'=>$scannedphoto,'scannedsignaturephoto'=>$scannedsignaturephoto);
		
		
		$html=$CI->load->view('custom_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/custom_admitcardpdf/'.$pdfFilePath, "F");
		
		print_r('https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$pdfFilePath);
		echo '</br>';
		echo '<pre>',print_r($scannedphoto.'</br>'.$scannedsignaturephoto),'</pre>';
		//echo '<pre>path:',print_r('uploads/admitcardpdf/'.$pdfFilePath),'</pre></br>';
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		foreach($admit_card_details as $admit_card_update){
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));
			
			/*$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));*/
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->update('admit_card_details',$update_data);	
			
			
			
			//$last_update_query_error = $CI->db->_error_message();
			
			$last_update_query = $CI->db->last_query();
			
			$log_title ="Admitcard filename updated in for loop. id:".$admit_card_update['admitcard_id'];
			//$log_message = serialize($admit_card_details);
			//$log_message = $last_update_query."|".$last_update_query_error;
			$log_message = $last_update_query;
			$rId = $admit_card_update['admitcard_id'];
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
			
		}
		
		// code to check if admi card file name updated
		//$admit_card_details_update = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
		
		$admit_card_details_update = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		if(count($admit_card_details_update) > 0)
		{
			/*$CI->db->where('exm_cd', $exam_code);
			$CI->db->where('exm_prd', $exam_period);
			$CI->db->where('remark', 1);
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$member_id));*/
			
			
			$CI->db->where('mem_mem_no', $member_id);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('exm_cd', $exam_code);
			$CI->db->where('exm_prd', $exam_period);
			$CI->db->update('admit_card_details',$update_data);	
			
			
			$log_title ="Admitcard filename updated in 2nd update. Member id:".$member_id;
			$log_message = serialize($admit_card_details_update);
			$rId = $member_id;
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
		}
		// eof code to check if admi card file name updated
		echo '<pre>path:',print_r($pdfFilePath),'</pre></br>';
		echo 'generated';
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		return 'uploads/custom_admitcardpdf/'.$pdfFilePath;
		//return 'uploads/custom_admitcardpdf/';
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}



function genarate_admitcard_old()
{
	try{
		$member_id = 3320237;
		
		
		$CI = & get_instance();	
		$exam_code = $CI->config->item('examCodeJaiib');
		$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('center_code'); 
		$CI->db->from('sify_center');
		$scenter = $CI->db->get();
		$sifyresult = $scenter->result();
		foreach($sifyresult as $sifyresult){
			$sifycenter[] = $sifyresult->center_code;
		}
		
		$CI->db->select('center_code'); 
		$CI->db->from('nseit_center');
		$ncenter = $CI->db->get();
		$nseitresult = $ncenter->result();
		foreach($nseitresult as $nseitresult){
			$nseitcenter[] = $nseitresult->center_code;
		}
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		
		if(in_array($member_result->center_code, $nseitcenter)){
			$vcenter = 'NSEIT';
		}
		if(in_array($member_result->center_code, $sifycenter)){
			$vcenter = 'SIFY';
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
		$CI->db->group_by('venueid');
		$CI->db->order_by("date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0));
		$CI->db->order_by("STR_TO_DATE(date, '%e-%b-%y')ASC");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = $pdate->date;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = $subject_result[0]->date;
		$examdate = explode("-",$exdate);
		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';
		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$CI->db->select('medium_description');
		$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description);
		
		$html=$CI->load->view('admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F");
		
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}
 
 function genarate_admitcard_centerchange_custom($member_id,$exam_code,$exam_period,$admitcard_id_arr)
{
	try{
		
		//$member_id = 700001459;
		//$exam_code = 42;
		//$exam_period = 217;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		if(isset($admitcard_id_arr) && count($admitcard_id_arr) > 0 )
		{
			$CI->db->where_in('admit_card_details.admitcard_id',$admitcard_id_arr);
		}
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		//echo $CI->db->last_query(); exit;
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if($exam_code == $CI->config->item('examCodeJaiib')){
			if(isset($member_result->center_code) && $member_result->center_code==306){
				$vcenter = 3;
			}
		}
		
		$medium_code = $member_result->m_1;
		if(isset($admitcard_id_arr) && count($admitcard_id_arr) > 0 )
		{
			$CI->db->where_in('admit_card_details.admitcard_id',$admitcard_id_arr);
		}
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		if(isset($admitcard_id_arr) && count($admitcard_id_arr) > 0 )
		{
			$CI->db->where_in('admit_card_details.admitcard_id',$admitcard_id_arr);
		}
		$CI->db->select('subject_description,admit_card_details.exam_date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); ;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = date("d-M-y", strtotime($subject_result[0]->exam_date)); 
		$examdate = explode("-",$exdate);
		$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
		
		
		
		if($medium_code == 'ENGLISH' || $medium_code == 'E'){
			$medium_code_lng = 'E';
		}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
			$medium_code_lng = 'H';
		}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
			$medium_code_lng = 'A';
		}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
			$medium_code_lng = 'G';
		}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
			$medium_code_lng = 'K';
		}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
			$medium_code_lng = 'L';
		}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
			$medium_code_lng = 'M';
		}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
			$medium_code_lng = 'N';

		}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
			$medium_code_lng = 'O';
		}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
			$medium_code_lng = 'S';
		}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
			$medium_code_lng = 'T';
		}
		
		$CI->db->select('medium_description');
		$medium = $CI->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
		
		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('admitcardpdf_attach', $data, true);
		}
		
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		if(isset($admitcard_id_arr) && count($admitcard_id_arr) > 0 )
		{
			$CI->db->where_in('admit_card_details.admitcard_id',$admitcard_id_arr);
		}
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($admit_card_details as $admit_card_update){
		foreach($admit_card_details->result_array() as $admit_card_update){
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));
			
			/*$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));*/
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->update('admit_card_details',$update_data);	
			
			//$last_update_query_error = $CI->db->_error_message();
			
			$last_update_query = $CI->db->last_query();
			
			$log_title ="Admitcard filename updated in for loop. id:".$admit_card_update['admitcard_id'];
			//$log_message = serialize($admit_card_details);
			//$log_message = $last_update_query."|".$last_update_query_error;
			$log_message = $last_update_query;
			$rId = $admit_card_update['admitcard_id'];
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
			
		}
		
		// code to check if admi card file name updated
		//$admit_card_details_update = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
		
		if(isset($admitcard_id_arr) && count($admitcard_id_arr) > 0 )
		{
			$CI->db->where_in('admit_card_details.admitcard_id',$admitcard_id_arr);
		}
		$admit_card_details_update = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image' => ''));
		
		
		if(count($admit_card_details_update->result_array()) > 0)
		{
			/*$CI->db->where('exm_cd', $exam_code);
			$CI->db->where('exm_prd', $exam_period);
			$CI->db->where('remark', 1);
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('mem_mem_no'=>$member_id));*/
			
			
			$CI->db->where('mem_mem_no', $member_id);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('exm_cd', $exam_code);
			$CI->db->where('exm_prd', $exam_period);
			$CI->db->update('admit_card_details',$update_data);	
			
			
			$log_title ="Admitcard filename updated in 2nd update. Member id:".$member_id;
			$log_message = serialize($admit_card_details_update->result_array());
			$rId = $member_id;
			$regNo = $member_id;
			//storedUserActivity($log_title, $log_message, $rId, $regNo);
			$log_data['title'] = $log_title;
			$log_data['description'] = $log_message;
			$log_data['regid'] = $rId;
			$log_data['regnumber'] = $regNo;
			$CI->db->insert('userlogs', $log_data);
		} 
		// eof code to check if admi card file name updated
		
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


 

/* End of file admitcard_helper.php */
/* Location: ./application/helpers/invice_helper.php */