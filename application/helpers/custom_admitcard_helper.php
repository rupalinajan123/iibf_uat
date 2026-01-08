<?php
 //ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL); 
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan Admitcard genarate code


function genarate_admitcard_custom($member_id,$exam_code,$exam_period,$id)
{
	try{ 
		//echo 'here';exit;
		 
		$member_id = 510451306;
		$exam_code = $this->config->item('examCodeCaiib');
	    $exam_period = 222; 
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		 
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code mem_exm_id, DATE_FORMAT(created_on, "%d%m%Y%H%i%s") as created_on');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		//echo $CI->db->last_query();
		//exit; 
		
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if($exam_code == $this->config->item('examCodeJaiib')){
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		/*echo $CI->db->last_query();
		echo '<pre>';
		print_r($subject_result);
		exit;*/
		
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
		
		$update_data_arr = array('find_status' => 2);
		$CI->master_model->updateRecord('jaiib_pass_sub_copy',$update_data_arr,array('id'=>$id));
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
		
		$html=$CI->load->view('custom_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf"; 
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		return 'uploads/admitcardpdf/'.$pdfFilePath; 
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function genarate_admitcard_ippb($member_id,$exam_code,$exam_period)
{
	
	try{
	
		$CI = & get_instance();	
		
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		
		if(sizeof($member_result) == 0){
			return 'not found';
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
		//$CI->db->where('admit_card_details.exam_date >=',date('Y-m-d'));
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
	//	$CI->db->where('admit_card_details.exam_date >=',date('Y-m-d'));
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
	

			$CI->db->select('dateofbirth,associatedinstitute');
			$memberDetailss = $CI->db->get_where('member_registration', array('regnumber' => $member_id));
			$memberDetails = $memberDetailss->result_array();

			$CI->db->select('name');
		//	$CI->db->join('institution_master', 'member_registration.associatedinstitute = institution_master.institude_id'); 
			$instituteDetailss = $CI->db->get_where('institution_master', array('institude_id' => $memberDetails[0]['associatedinstitute']));
			$instituteDetails = $instituteDetailss->result_array();

			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
		
		$CI->db->select('optFlg');
		$CI->db->from('member_exam');
		$CI->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));		
		$CI->db->order_by("id", "desc");
		$optFlgRecordRow = $CI->db->get();
		$optFlgRecord='';
			if(!empty($optFlgRecordRow))
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
				
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_periodm,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord); //priyanka d -feb-23 added instituteDetails,memberDetails
		


		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('admitcardpdf_attach', $data, true);
		}
		//echo $html;
		//
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdf->showImageErrors = true;
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
	
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
				$update_data = array('admitcard_image' => $pdfFilePath);
				//foreach($admit_card_details as $admit_card_update){
				foreach($admit_card_details->result_array() as $admit_card_update){
		
					$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
					$CI->db->where('remark', 1);
					$CI->db->where('admitcard_image', '');
					$CI->db->update('admit_card_details',$update_data);	
					
				
					
				}
			

		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}
function remote_genarate_admitcard_custom_new($member_id,$exam_code,$exam_period)

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

		if($exam_code == $this->config->item('examCodeJaiib')){

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


function naar_genarate_admitcard_bulk($member_id='',$exam_code='',$exam_period='')
{
	try{
		
		//$member_id = 510332837;
		//$exam_code = 34;
		//$exam_period = 714;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
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
		
		
		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1015 || $exam_code == 1017 || $exam_code == 1018){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('bulk_admitcardpdf_attach', $data, true);
		}
		
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($admit_card_details as $admit_card_update){
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function naar_institute_admitcard_bulk($member_id='',$exam_code='',$exam_period='')
{
	try{
		
		//$member_id = 510332837;
		//$exam_code = 34;
		//$exam_period = 714;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
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
		
		
		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 1015 || $exam_code == 1017 || $exam_code == 1018){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('bulk_admitcardpdf_attach', $data, true);
		}
		
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		echo $pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output($pdfFilePath, "D"); 
		
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function remotedra_genarate_admitcard_custom_new($member_id='',$exam_code='',$exam_period='')
{
	try{ 
		
		$CI = & get_instance();	
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('dra_admit_card_details');
		$CI->db->where(array('dra_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('dra_admit_card_details');
		$CI->db->where(array('dra_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,dra_admit_card_details.exam_date,time,venueid,seat_identification');
		$CI->db->from('dra_admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(dra_admit_card_details.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'dra_admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("dra_admit_card_details.exam_date", "asc");
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
		$medium = $CI->db->get_where('dra_medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
		
		/*echo '<pre>';
		print_r($data);
		exit;*/
		
		$html=$CI->load->view('dra_remote_admitcardpdf_attach', $data, true);
		
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/dra_admitcardpdf/'.$pdfFilePath, "F"); 
		
		$dra_admit_card_details = $CI->db->get_where('dra_admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		 
		
		
		
		
		return 'uploads/dra_admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function genarate_admitcard210($member_id,$exam_code,$exam_period)
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
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) and admit_card_details.exm_cd=admit_subject_master.exam_code');// priyanka d - 02-feb-23 >> added exam_condition for join to avoid duplicate subjects in admitcard pdf
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
		
		/*$CI->db->join('institution_master', 'member_registration.associatedinstitute = institution_master.institude_id'); 
		$instituteDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','institution_master.name as associatedinstitute')); //priyanka d -feb-23
			$memberDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth')); //priyanka d -feb-23
*/
			

			$CI->db->select('dateofbirth,associatedinstitute');
			$memberDetailss = $CI->db->get_where('member_registration', array('regnumber' => $member_id));
			$memberDetails = $memberDetailss->result_array();

			$CI->db->select('name');
		//	$CI->db->join('institution_master', 'member_registration.associatedinstitute = institution_master.institude_id'); 
			$instituteDetailss = $CI->db->get_where('institution_master', array('institude_id' => $memberDetails[0]['associatedinstitute']));
			$instituteDetails = $instituteDetailss->result_array();

			if(!empty($instituteDetails)) {
				$instituteDetails[0]['associatedinstitute']=$instituteDetails[0]['name'];
			}
		
		$CI->db->select('optFlg');
		$CI->db->from('member_exam');
		$CI->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));		
		$CI->db->order_by("id", "desc");
		$optFlgRecordRow = $CI->db->get();
		$optFlgRecord='';
			if(!empty($optFlgRecordRow))
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
				
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_periodm,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails,'optFlgRecord'=>$optFlgRecord); //priyanka d -feb-23 added instituteDetails,memberDetails
		


		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014 || $exam_code == 2027 || $exam_code == 1019 || $exam_code == 1020){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('admitcardpdf_attach', $data, true);
		}
		//echo $html;
		//
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdf->showImageErrors = true;
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
	
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
				$update_data = array('admitcard_image' => $pdfFilePath);
				//foreach($admit_card_details as $admit_card_update){
				foreach($admit_card_details->result_array() as $admit_card_update){
		
					$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
					$CI->db->where('remark', 1);
					$CI->db->where('admitcard_image', '');
					$CI->db->update('admit_card_details',$update_data);	
					
				
					
				}
			

		// eof code to check if admi card file name updated
		echo 'uploads/admitcardpdf/'.$pdfFilePath;;
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function genarate_admitcard_custom_new($member_id,$exam_code,$exam_period)
{
	try{ 
		//echo $member_id;exit; 
		 
		//$member_id = 700001459;
		//$exam_code = 42;
	   // $exam_period = 809; 
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		 
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		echo $CI->db->last_query();
		//exit; 
		
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if($exam_code == $this->config->item('examCodeJaiib')){
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		//echo $CI->db->last_query();
		//echo '<pre>';
		//print_r($subject_result);
		/*exit; */ 
		
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
		
 
		$CI->db->join('institution_master', 'member_registration.associatedinstitute = institution_master.institude_id'); 
		$instituteDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth','institution_master.name as associatedinstitute')); //priyanka d -feb-23
			$memberDetails = $CI->master_model->getRecords('member_registration',array('regnumber'=>$member_id),array('dateofbirth')); //priyanka d -feb-23

		$CI->db->select('optFlg');
		$CI->db->from('member_exam');
		$CI->db->where(array('member_exam.regnumber' => $member_id,'exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));		
		$CI->db->order_by("id", "desc");
		$optFlgRecordRow = $CI->db->get();
		$optFlgRecord='';
			if(!empty($optFlgRecordRow))
				$optFlgRecord = $optFlgRecordRow->row()->optFlg; //priyanka d- 27-feb-23 >> to get option selected by canddate for jaiib
		
		
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period,'optFlgRecord'=>$optFlgRecord,'memberDetails'=>$memberDetails,'instituteDetails'=>$instituteDetails);
		
		if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004 || $exam_code == 1005|| $exam_code == 1006|| $exam_code == 1007|| $exam_code == 1008|| $exam_code == 1009|| $exam_code == 1010|| $exam_code == 1011|| $exam_code == 1012|| $exam_code == 1013|| $exam_code == 1014|| $exam_code == 1015){
			$html=$CI->load->view('remote_admitcardpdf_attach', $data, true);
		}else{
			$html=$CI->load->view('admitcardpdf_attach', $data, true);
		}
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf"; 
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf210/'.$pdfFilePath, "F"); 
		
		//echo $html;
		echo 'uploads/admitcardpdf210/'.$pdfFilePath; 
		return 'uploads/admitcardpdf210/'.$pdfFilePath; 
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function genarate_admitcard_custom_new_996($member_id,$exam_code,$exam_period)
{
	try{ 
		//echo 'here';exit;
		 
		//$member_id = 700001459;
		//$exam_code = 42;
	   // $exam_period = 809; 
		$CI = & get_instance();	
		//$admit_card_details_996 = $CI->master_model->getRecords('admit_card_details_996',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		 
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details_996');
		$CI->db->where(array('admit_card_details_996.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		
		//echo $CI->db->last_query();
		//exit; 
		
		if(sizeof($member_result) == 0){
			return '';
			exit;
		}else{
		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		if($exam_code == $this->config->item('examCodeJaiib')){
			if(isset($member_result->center_code) && $member_result->center_code==306){
				$vcenter = 3;
			}	
		}
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details_996');
		$CI->db->where(array('admit_card_details_996.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,admit_card_details_996.exam_date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details_996');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details_996.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details_996.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("admit_card_details_996.exam_date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		/*echo $CI->db->last_query();
		echo '<pre>';
		print_r($subject_result);
		exit;*/
		
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
		
		$html=$CI->load->view('custom_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf"; 
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		return 'uploads/admitcardpdf/'.$pdfFilePath; 
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function custome_genarate_admitcard_bulk($member_id='',$exam_code='',$exam_period='')
{
	try{
		
		//$member_id = 510332837;
		//$exam_code = 34;
		//$exam_period = 714;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk','admitcard_image'=>''));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
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
		
		$html=$CI->load->view('bulk_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($admit_card_details as $admit_card_update){
		foreach($admit_card_details->result_array() as $admit_card_update){
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('record_source','bulk');
			$CI->db->update('admit_card_details',$update_data);	
			
		}
		
		return 'uploads/admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function custome_genarate_admitcard_bulk802($member_id,$exam_code,$exam_period)
{
	try{ 
		
		//$member_id = 510332837;
		//$exam_code = 34;
		//$exam_period = 714;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
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
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
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
		
		$html=$CI->load->view('bulk_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/custom_admitcardpdf/'.$pdfFilePath, "F"); 
		
		return 'uploads/custom_admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function genarate_admitcard_custom_old22may2018($member_no,$exam_code) 
   {
	try{
		 
		$member_id = $member_no;  
		$exam_code = $exam_code;
		$exam_period = 728; 
		$CI = & get_instance();	
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
		echo '-----------------------------------------------';
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		return 'uploads/custom_admitcardpdf/'.$pdfFilePath;
		//return 'uploads/custom_admitcardpdf/';
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}
function genarate_admitcard_custom_118($member_no,$r_id,$mem_exm_id) 
   {
	try{
		 
		$member_id = $member_no;  
		$exam_code = $this->config->item('examCodeDBF');
		$exam_period = 118;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'mem_exam_id'=>$mem_exm_id));
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
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'mem_exam_id'=>$mem_exm_id));
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
		//$path = $pdf->Output('uploads/custom_admitcardpdf/'.$pdfFilePath, "F");
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F");
		
		//print_r('https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$pdfFilePath);
		//echo '</br>';
		//echo '<pre>',print_r($scannedphoto.'</br>'.$scannedsignaturephoto),'</pre>';
		
		//echo '<pre>path:',print_r('uploads/admitcardpdf/'.$pdfFilePath),'</pre></br>';
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'mem_exam_id'=>$mem_exm_id));
		$admit_card_details = $admit_card_details->result_array();
		//echo $CI->db->last_query();
		
		
		if($pdfFilePath)
		{
		//echo 'in';
		$update_data = array('admitcard_image' => $pdfFilePath);
		//print_r($update_data);exit;
		$modified_on = $admit_card_details[0]['modified_on'];
		foreach($admit_card_details as $admit_card_update){
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('pwd !=','');
			$CI->db->where('seat_identification !=','');
			$CI->db->where('admitcard_image','');
			$response = $CI->db->update('admit_card_details',$update_data);	
		
			
			//echo  $CI->db->last_query();
		  /*  $last_update_query_error = $CI->db->_error_message();
			print_r($last_update_query_error);*/
			
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
		echo 'response',print_r($response);
			echo '</br>';
		
		//update custom table
			$update_data1 = array('admitcard_image' => $pdfFilePath,'is_done'=>1);
			
			$CI->db->where('id',$r_id);
			$CI->db->where('admitcard_image','');
			$CI->db->update('jaiib_admit_card_generation_10_04_2018',$update_data1);	
			
			
			//update member_exam
			$update_data_mem_exm = array('pay_status'=>1,'modified_on'=>$modified_on);
			
			$CI->db->where('id',$mem_exm_id);
			$CI->db->where('regnumber',$member_no);
			$CI->db->update('member_exam',$update_data_mem_exm);
			
			echo $CI->db->last_query();
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
		//echo '<pre>path:',print_r($pdfFilePath),'</pre></br>';
		//return 'uploads/admitcardpdf/'.$pdfFilePath;
		return 'https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$pdfFilePath;
		//return 'uploads/custom_admitcardpdf/';
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function genarate_custom_admitcard($member_no,$exam_code,$exam_period) 
   {
	try{ 
		 
		$member_id = $member_no;  
		$exam_code = $exam_code;
		$exam_period = $exam_period;
		$CI = & get_instance();	
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
		//$path = $pdf->Output('uploads/custom_admitcardpdf/'.$pdfFilePath, "F");
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F");
		
		print_r('https://iibf.esdsconnect.com/uploads/admitcardpdf/'.$pdfFilePath);
		echo '</br>';
		echo '<pre>',print_r($scannedphoto.'</br>'.$scannedsignaturephoto),'</pre>';
		//echo '<pre>path:',print_r('uploads/admitcardpdf/'.$pdfFilePath),'</pre></br>';
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		
		
		$admit_card_details = $CI->db->get_where('admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1));
		$admit_card_details = $admit_card_details->result_array();
		
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		foreach($admit_card_details as $admit_card_update){
			//$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id'],'remark'=>1));
			
			/*$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));*/
			
			
			$CI->db->where('admitcard_id',$admit_card_update['admitcard_id']);
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


function custom_genarate_admitcard_test()
{
	try{
		
		$member_id = 510192591;
		$exam_code = $this->config->item('examCodeJaiib');
		$exam_period = 217;
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
		if(isset($member_result->center_code) && $member_result->center_code==306){
			$vcenter = 3;
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
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate);
		
		$html=$CI->load->view('custom_admitcardpdf_attach', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/custom_admitcardpdf/'.$pdfFilePath, "F");
		
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
		
		return 'uploads/custom_admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function custome_genarate_admitcard_old()
{
	try{
		$member_id = 3320237;
		$exam_code = $this->config->item('examCodeJaiib');
		
		$CI = & get_instance();	
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

 
function custom_genarate_admitcard_offline($member_id,$exam_code,$exam_period)
{
	try{
		
		//$member_id = 700001459;
		//$exam_code = 42;
		//$exam_period = 217;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline','admitcard_image'=>''));
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
		if(isset($member_result->center_code) && $member_result->center_code==306){
			$vcenter = 3;
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline','admitcard_image'=>''));
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
		$CI->db->where('record_source','Offline');
		$CI->db->where('admitcard_image', '');
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
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate);
		
		$html=$CI->load->view('admitcardpdf_attach_offline', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		//$path = $pdf->Output('uploads/admitcardpdf_offline/'.$pdfFilePath, "F");
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'Offline','admitcard_image'=>''));
		
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		
		foreach($admit_card_details as $admit_card_update){
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('record_source','Offline');
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));
		}
		
		//return 'uploads/admitcardpdf_offline/'.$pdfFilePath;
		return 'uploads/admitcardpdf/'.$pdfFilePath;
	}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function custom_genarate_admitcard_close_venue($member_id,$exam_code,$exam_period)
{
	try{
		
		//$member_id = 700001459;
		//$exam_code = 42;
		//$exam_period = 217;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details');
		$CI->db->where('record_source !=','Offline');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image'=>''));
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
		if(isset($member_result->center_code) && $member_result->center_code==306){
			$vcenter = 3;
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details');
		$CI->db->where('record_source !=','Offline');
		$CI->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image'=>''));
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
		$CI->db->where('record_source !=','Offline');
		$CI->db->where('admitcard_image', '');
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
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate);
		
		$html=$CI->load->view('admitcardpdf_attach_offline', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		//$path = $pdf->Output('uploads/admitcardpdf_offline/'.$pdfFilePath, "F");
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		$CI->db->where('record_source !=','Offline');
		$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'admitcard_image'=>''));
		
		
		$update_data = array('admitcard_image' => $pdfFilePath);
		
		foreach($admit_card_details as $admit_card_update){
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('record_source !=','Offline');
			$CI->master_model->updateRecord('admit_card_details',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));
		}
		
		//return 'uploads/admitcardpdf_offline/'.$pdfFilePath;
		return 'uploads/admitcardpdf/'.$pdfFilePath;
	}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function caiib_custom_genarate_admitcard_offline($member_id,$exam_code,$exam_period)
{
	try{
		
		//$member_id = 700001459;
		//$exam_code = 42;
		//$exam_period = 217;
		$CI = & get_instance();	
		//$admit_card_details = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code));
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admitcard_caiib_217_mailsend');
		$CI->db->where(array('admitcard_caiib_217_mailsend.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'mailsend'=>'no'));
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1');
		$CI->db->from('admitcard_caiib_217_mailsend');
		$CI->db->where(array('admitcard_caiib_217_mailsend.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'mailsend'=>'no'));
		$CI->db->group_by('venueid');
		$CI->db->order_by("date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,admitcard_caiib_217_mailsend.date,time,venueid,seat_identification');
		$CI->db->from('admitcard_caiib_217_mailsend');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admitcard_caiib_217_mailsend.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admitcard_caiib_217_mailsend.mem_mem_no'=>$member_id,'subject_delete'=>0));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('mailsend', 'no');
		$CI->db->order_by("admitcard_caiib_217_mailsend.date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();
		
		$pdate = $subject->result();
		foreach($pdate as $pdate){
			$exdate = date("d-M-y", strtotime($subject_result[0]->date)); ;
			$examdate = explode("-",$exdate);
			$examdatearr[] = $examdate[1];
		}
		
		$exdate = date("d-M-y", strtotime($subject_result[0]->date)); 
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
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate);
		
		$html=$CI->load->view('caiib_admitcardpdf_attach_offline', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		//$path = $pdf->Output('uploads/admitcardpdf_offline/'.$pdfFilePath, "F");
		$path = $pdf->Output('uploads/admitcardpdf/'.$pdfFilePath, "F"); 
		
		$admit_card_details = $CI->master_model->getRecords('admitcard_caiib_217_mailsend',array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'mailsend'=>'no'));
		
		
		$update_data = array('mailsend' => 'yes');
		
		foreach($admit_card_details as $admit_card_update){
			$CI->db->where('mailsend', 'no');
			$CI->master_model->updateRecord('admitcard_caiib_217_mailsend',$update_data,array('admitcard_id'=>$admit_card_update['admitcard_id']));
		}
		
		//return 'uploads/admitcardpdf_offline/'.$pdfFilePath;
		return 'uploads/admitcardpdf/'.$pdfFilePath;
	}
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function genarate_admitcard_instruction_ch($member_no,$exam_code)
{
	try{
		
		$member_id = $member_no;  echo "<br/>"; 
		$exam_code = $exam_code; echo "<br/>"; 
		$exam_period = 517;
		
		
		$CI = & get_instance();	
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('admit_card_details_instruc_ch');
		$CI->db->where(array('admit_card_details_instruc_ch.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'is_mail_send'=>0));
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
			$vcenter = 3;
		}
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('admit_card_details_instruc_ch');
		$CI->db->where(array('admit_card_details_instruc_ch.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'is_mail_send'=>0));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,admit_card_details_instruc_ch.exam_date,time,venueid,seat_identification');
		$CI->db->from('admit_card_details_instruc_ch');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(admit_card_details_instruc_ch.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'admit_card_details_instruc_ch.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->where('is_mail_send',0);
		$CI->db->order_by("admit_card_details_instruc_ch.exam_date", "asc");
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
		//,'scannedphoto'=>$scannedphoto,'scannedsignaturephoto'=>$scannedsignaturephoto
		
		
		//$html=$CI->load->view('custom_admitcardpdf_attach', $data, true);
		$html=$CI->load->view('custom_admitcardpdf_attach_ch', $data, true);
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/custom_admitcardpdf_459/'.$pdfFilePath, "F");
		
		
		return 'uploads/custom_admitcardpdf_459/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}
function custom_amtinword_118($amt){
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

function custom_genarate_exam_invoice_118($receipt_no){ 
	$CI = & get_instance();
	
	$invoice_info = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	echo 'receipt_no:',print_r($receipt_no),'</br>';
	echo '<pre>invoice_info:',print_r($invoice_info[0]['member_no']),'</pre>';
	if($invoice_info)
	{
	
	$mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
	$member_name = $mem_info[0]['firstname']." ".@$mem_info[0]['middlename']." ".@$mem_info[0]['lastname'];
	
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		$wordamt = custom_amtinword_118($invoice_info[0]['cs_total']);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
		$wordamt = custom_amtinword_118($invoice_info[0]['igst_total']);
	}
	
	$admit_info = $CI->master_model->getRecords('admit_card_details',array('mem_mem_no'=>$invoice_info[0]['member_no'],'exm_cd'=>$invoice_info[0]['exam_code'],'exm_prd'=>$invoice_info[0]['exam_period'],'remark'=>'1'),'modified_on');
	
	//get modified on date from member_exam
	//$admit_info = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exm_cd'=>$invoice_info[0]['exam_code'],'exm_prd'=>$invoice_info[0]['exam_period'],'pay_status'=>'1'),'modified_on');
	//echo $CI->db->last_query();
	//echo '<pre>',print_r($invoice_info),'</pre>';exit;
	//echo '<pre>',print_r($admit_info),'</pre>';exit;
	
	$payment_info = $CI->master_model->getRecords('payment_transaction',array('receipt_no'=>$invoice_info[0]['receipt_no']),'transaction_no');
	echo '<pre>invoice_id:',print_r($invoice_info[0]['invoice_id']),'</pre>';
	$invoiceNumber = generate_exam_invoice_number_118(@$invoice_info[0]['invoice_id']);
	$img_name =  $invoice_info[0]['member_no']."_EX_18-19_".$invoiceNumber.".jpg";
		
		if($invoiceNumber)
		{
			$invoiceNumber=$CI->config->item('exam_invoice_no_prefix').$invoiceNumber;
		}
	 
	
	$update_data = array('date_of_invoice' => $admit_info[0]['modified_on'],'modified_on'=>$admit_info[0]['modified_on'],'transaction_no'=>$payment_info[0]['transaction_no'],'invoice_no'=>$invoiceNumber,'invoice_image'=>$img_name);
	$CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_info[0]['invoice_id'],'receipt_no'=>$receipt_no));
	$invoice_info_new = $CI->master_model->getRecords('exam_invoice',array('receipt_no'=>$receipt_no));
	
	
	// invoice image generation code start
	
	$date_of_invoice = date("d-m-Y", strtotime($admit_info[0]['modified_on']));
	
	
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
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info_new[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info_new[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "ORIGINAL FOR RECIPIENT ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
	
	if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
		$exam_code = 34;
	}elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
		$exam_code = 58;
	}elseif($invoice_info[0]['exam_code'] == 1600){
		$exam_code = 160;
	}
	elseif($invoice_info[0]['exam_code'] == 200){
		$exam_code = 20;
	}elseif($invoice_info[0]['exam_code'] == 1770){
		$exam_code =177;
	}
	else{
		$exam_code = $invoice_info[0]['exam_code'];
	}
	
	$exam_period = '';
	$exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
	//echo 'exam',$CI->db->last_query();
	
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
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	//imagestring($im, 3, 22,  630, "Less :", $black);
	//imagestring($im, 3, 100,  630, "Discount :", $black);
	//imagestring($im, 3, 100,  642, "Abatement :", $black);
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
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
	
	$savepath = base_url()."uploads/custom_examinvoice/user/";
	//$ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
	$ino = str_replace("/","_",$invoiceNumber);
	$imagename = $img_name;
	//$imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
	//print_r('jb,cj',$invoiceNumber);echo '</br>';
	//print_r($imagename);exit;
	imagepng($im,"uploads/custom_examinvoice/user/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_examinvoice/user/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_examinvoice/user/'.$imagename);
	
	
	
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
	imagestring($im, 3, 22,  112, "GSTIN: 27AAATT3309D1ZS", $black);
	imagestring($im, 3, 22,  124, "Address: ", $black);
	imagestring($im, 3, 22,  136, "Kohinoor City Commercial - II Tower - I, ", $black);
	imagestring($im, 3, 22,  148, "2nd & 3rd Floor, Kirol Road, Off-L.B.S Marg", $black);
	imagestring($im, 3, 22,  160, "Kurla- West Mumbai - 400 070", $black);
	imagestring($im, 3, 22,  172, "State : Maharashtra", $black);
	imagestring($im, 3, 22,  184, "State Code : 27", $black);
	imagestring($im, 3, 22,  212, "Invoice No : ".$invoice_info_new[0]['invoice_no'], $black);
	imagestring($im, 3, 22,  224, "Date Of Invoice : ".$date_of_invoice, $black);
	imagestring($im, 3, 22,  236, "Transaction no : ".$invoice_info_new[0]['transaction_no'], $black);
	imagestring($im, 3, 800,  100, "DUPLICATE FOR SUPPLIER ", $black);
	//imagestring($im, 3, 22,  250, "Details of service recipient", $black);
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
	imagestring($im, 3, 690,  560, $invoice_info[0]['fee_amt'], $black); // Rate
	imagestring($im, 3, 780,  560, "1", $black); // Quantity 
	imagestring($im, 3, 900,  560, $invoice_info[0]['fee_amt'], $black); // Total
	
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 100,  660, "For intra-state supply -", $black);
		imagestring($im, 3, 300,  660, "Central Tax:", $black);
		imagestring($im, 3, 690,  660, $invoice_info[0]['cgst_rate']."%", $black);
		imagestring($im, 3, 900,  660, $invoice_info[0]['cgst_amt'], $black);
		imagestring($im, 3, 300,  672, "State Tax:", $black);
		imagestring($im, 3, 690,  672, $invoice_info[0]['sgst_rate']."%", $black);
		imagestring($im, 3, 900,  672, $invoice_info[0]['sgst_amt'], $black);
		
		imagestring($im, 3, 100,  700, "For inter-state supply -", $black);
		//imagestring($im, 3, 100,  710, "Supply", $black);
		imagestring($im, 3, 300,  710, "Integrated Tax:", $black);
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	if($invoice_info[0]['state_of_center'] != 'MAH' && $invoice_info[0]['state_of_center'] != 'JAM'){
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
		imagestring($im, 3, 690,  710, $invoice_info[0]['igst_rate']."%", $black);
		imagestring($im, 3, 900,  710, $invoice_info[0]['igst_amt'], $black);
	}
	
	if($invoice_info[0]['state_of_center'] == 'JAM'){
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
		imagestring($im, 3, 690,  710, "-", $black);
		imagestring($im, 3, 900,  710, "-", $black);
	}
	
	imagestring($im, 3, 500,  780, "Total", $black);
	if($invoice_info[0]['state_of_center'] == 'MAH'){
		imagestring($im, 3, 900,  780, $invoice_info[0]['cs_total'], $black);
	}elseif($invoice_info[0]['state_of_center'] != 'MAH'){
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
	
	$savepath = base_url()."uploads/custom_examinvoice/supplier/";
	$imagename = $img_name;
	
	imagepng($im,"uploads/custom_examinvoice/supplier/".$imagename);
	$png = @imagecreatefromjpeg('assets/images/sign.jpg');
	$jpeg = @imagecreatefromjpeg("uploads/custom_examinvoice/supplier/".$imagename);
	@imagecopyresampled($im, $png, 760, 850, 0, 0, 50, 50, 170, 124);
	imagepng($im, 'uploads/custom_examinvoice/supplier/'.$imagename);
	
	imagedestroy($im);
	return $attachpath = "uploads/custom_examinvoice/user/".$img_name;
	}
}

function generate_exam_invoice_number_118($invoice_id= NULL)
{
	$last_id='';
	$CI = & get_instance();
	//$CI->load->model('my_model');
	if($invoice_id  !=NULL)
	{ 
		
		$number_info = $CI->master_model->getRecords('config_exam_invoice',array('invoice_id'=>$invoice_id),'exam_invoice_no');
		
		if(@$number_info[0]['exam_invoice_no'] == ''){ 
		
		$insert_info = array('invoice_id'=>$invoice_id);
		$last_id = str_pad($CI->master_model->insertRecord('config_exam_invoice',$insert_info,true), 6, "0", STR_PAD_LEFT);;
		
		}
		else
		{
			$last_id = $number_info[0]['exam_invoice_no'];
		}
	}
	//print_r($last_id);exit;
	return $last_id;
}

function genarate_admitcard_dra_custom($member_id='',$exam_code='',$exam_period='')
{ 
	try{
		
		$CI = & get_instance();	
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('dra_admit_card_details');
		$CI->db->where(array('dra_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));//,'admitcard_image'=>''
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
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name');
		$CI->db->from('dra_admit_card_details');
		$CI->db->where(array('dra_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		$CI->db->group_by('venueid');
		$CI->db->order_by("exam_date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,dra_admit_card_details.exam_date,time,venueid,seat_identification');
		$CI->db->from('dra_admit_card_details');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(dra_admit_card_details.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'dra_admit_card_details.mem_mem_no'=>$member_id,'subject_delete'=>0,'exm_prd'=>$exam_period,'record_source'=>'bulk'));
		$CI->db->where('pwd!=','');
		$CI->db->where('seat_identification!=','');
		$CI->db->where('remark',1);
		$CI->db->order_by("dra_admit_card_details.exam_date", "asc");
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
		$medium = $CI->db->get_where('dra_medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
		
		/*echo '<pre>';
		print_r($data);
		exit;*/
		
		$html=$CI->load->view('dra_remote_admitcardpdf_attach_custom', $data, true);
		
		$CI->load->library('m_pdf');
		$pdf = $CI->m_pdf->load();
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$pdf->WriteHTML($html);
		$path = $pdf->Output('uploads/dra_admitcardpdf/'.$pdfFilePath, "F"); 
		
		$dra_admit_card_details = $CI->db->get_where('dra_admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		 
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($dra_admit_card_details as $admit_card_update){
		/* foreach($dra_admit_card_details->result_array() as $admit_card_update){
			
			
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('record_source','bulk');
			$CI->db->update('dra_admit_card_details',$update_data);	
			
		} */
		
		return 'uploads/dra_admitcardpdf/'.$pdfFilePath;
		
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

function dra_img_p_custom($member_id=NULL){
	
	$CI = & get_instance();	
	$CI->db->where('regnumber',$member_id);
	$mem_info = $CI->master_model->getRecords('dra_members','','registration_no,image_path,scannedphoto');
	
	$old_image_path = 'uploads'.$mem_info[0]['image_path'];
	$new_image_path  = 'uploads/iibfdra/';
	
	
	if($mem_info[0]['scannedphoto'] == ''){
		if(file_exists($old_image_path . "photo/p_" . $mem_info[0]['registration_no'].'.jpg')){
			 $scannedphoto = base_url().$old_image_path . "photo/p_" . $mem_info[0]['registration_no'].'.jpg';
		 }
	}else{
		 if(file_exists($new_image_path . $mem_info[0]['scannedphoto'])){
			 $scannedphoto = base_url().$new_image_path . $mem_info[0]['scannedphoto'];
		 }
	}
	return $scannedphoto;
}
	
	
function dra_img_s_custom($member_id=NULL){
		
	$CI = & get_instance();	
	
	$CI->db->where('regnumber',$member_id);
	$mem_info = $CI->master_model->getRecords('dra_members','','registration_no,image_path,scannedsignaturephoto');
	
	$old_image_path = 'uploads'.$mem_info[0]['image_path'];
	$new_image_path  = 'uploads/iibfdra/';
	
	if($mem_info[0]['scannedsignaturephoto'] == ''){
		 if(file_exists($old_image_path . "signature/s_" . $mem_info[0]['registration_no'].'.jpg')){
			  $scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $mem_info[0]['registration_no'].'.jpg';       
		 }
	}else{
		 if(file_exists($new_image_path . $mem_info[0]['scannedsignaturephoto'])){
			   $scannedsignaturephoto = base_url().$new_image_path . $mem_info[0]['scannedsignaturephoto'];
		 }
	}
	return $scannedsignaturephoto;
} 
 
 
 
 

/* End of file admitcard_helper.php */
/* Location: ./application/helpers/invice_helper.php */