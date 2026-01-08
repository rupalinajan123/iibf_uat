<?php
defined('BASEPATH')||exit('No Direct Allowed Here');
/**
 * Function to create log.
 * @access public 
 * @param String
 * @return String
 */
 
// by pawan Admitcard genarate code

function genarate_admitcard_dra_nameupdate($member_id='',$exam_code='',$exam_period='')
{
	try{
		$CI = & get_instance();	
		$pdfFilePath = $exam_code."_".$exam_period."_".$member_id.".pdf";
		$dra_admit_card_details = $CI->db->get_where('dra_admit_card_details', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));
		$update_data = array('admitcard_image' => $pdfFilePath);
		//foreach($dra_admit_card_details as $admit_card_update){
		foreach($dra_admit_card_details->result_array() as $admit_card_update){
			$CI->db->where('admitcard_id', $admit_card_update['admitcard_id']);
			$CI->db->where('remark', 1);
			$CI->db->where('admitcard_image', '');
			$CI->db->where('record_source','bulk');
			$CI->db->update('dra_admit_card_details',$update_data);	
		}
	}catch(Exception $e){
		echo $e->getMessage();
	}
}

function genarate_admitcard_dra($member_id='',$exam_code='',$exam_period='')
{
	try{
		
		$CI = & get_instance();	
		$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card start", "description"=>"member_id = ".$member_id.' >> exam_code = '.$exam_code.' >> exam_period = '.$exam_period));		
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code,center_code');
		$CI->db->from('dra_admit_card_details');
		$CI->db->where(array('dra_admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exm_prd'=>$exam_period,'remark'=>1,'record_source'=>'bulk'));/* ,'admitcard_image'=>'' */
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		$last_qry = $CI->db->last_query();
		
		if(sizeof($member_result) == 0)
		{
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card error", "description"=>$last_qry));
			return '';
			exit;
		}
		else
		{		
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
			$lstQry2 = $CI->db->last_query();
			
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 2", "description"=>$lstQry2));
		
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
		
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 3", "description"=>serialize($data)));
			
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
		 
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card End", "description"=>$pdfFilePath));		
		
		return 'uploads/dra_admitcardpdf/'.$pdfFilePath;
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}

//Normal DRA

function genarate_admitcardinfo_dra($member_id='',$exam_code='',$exam_period='')
{  
	try{
		
		$CI = & get_instance();	
		$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card start", "description"=>"member_id = ".$member_id.' >> exam_code = '.$exam_code.' >> exam_period = '.$exam_period));		
		
		$CI->db->select('mem_mem_no, mam_nam_1, mem_adr_1, mem_adr_2, mem_adr_3, mem_adr_4, mem_adr_5,mem_adr_6, mem_pin_cd, mode, pwd, center_code, m_1,vendor_code');
		$CI->db->from('dra_admitcard_info');
		$CI->db->where(array('dra_admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exam_period'=>$exam_period));/* ,'admitcard_image'=>'' */
		$CI->db->order_by("admitcard_id", "desc");
		$member_record = $CI->db->get();
		$member_result = $member_record->row();
		$last_qry = $CI->db->last_query();
		
		if(sizeof($member_result) == 0)
		{
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card error", "description"=>$last_qry));
			return '';
			exit;
		}
		else
		{		
		if(isset($member_result->vendor_code) || $member_result->vendor_code!='' ){
			$vcenter = $member_result->vendor_code;
		}elseif(!isset($member_result->vendor_code) || $member_result->vendor_code=='' ){
			$vcenter = '0';
		}
		
		
		$medium_code = $member_result->m_1;
		
		$CI->db->select('venueid, venueadd1, venueadd2, venueadd3, venueadd4, venueadd5, venpin,insname,venue_name,seat_identification');
		$CI->db->from('dra_admitcard_info');
		$CI->db->where(array('dra_admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'exam_period'=>$exam_period));
		$CI->db->group_by('venueid');
		$CI->db->order_by("date", "asc");
		$venue_record = $CI->db->get();
		$venue_result = $venue_record->result();
		
		$CI->db->select('description');
		$exam = $CI->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		
		$CI->db->select('subject_description,dra_admitcard_info.date,time,venueid,seat_identification');
		$CI->db->from('dra_admitcard_info');
		$CI->db->join('admit_subject_master', 'admit_subject_master.subject_code = LEFT(dra_admitcard_info.sub_cd,3)');
		$CI->db->where(array('admit_subject_master.exam_code' => $exam_code,'dra_admitcard_info.mem_mem_no'=>$member_id,'subject_delete'=>0,'dra_admitcard_info.exam_period'=>$exam_period));
		$CI->db->where('pwd!=','');
		// $CI->db->where('seat_identification!=','');
		//$CI->db->where('remark',1);
		$CI->db->order_by("dra_admitcard_info.date", "asc");
		$subject = $CI->db->get();
		$subject_result = $subject->result();

		// echo $CI->db->last_query();
		// print_r($subject_result); exit;
		
			$lstQry2 = $CI->db->last_query();
			
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 2", "description"=>$lstQry2));
		
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
		$medium = $CI->db->get_where('dra_medium_master', array('medium_code' => $medium_code_lng));
		$medium_result = $medium->row();
		
		$data = array("exam_code"=>$exam_code,"examdate"=>$printdate,"member_result"=>$member_result,"subject"=>$subject_result,"venue_result"=>$venue_result,"vcenter"=>$vcenter,'member_id'=>$member_id,"exam_name"=>$exam_result->description,"medium"=>$medium_result->medium_description,'idate'=>$exdate,'exam_period'=>$exam_period);
		
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card in 3", "description"=>serialize($data)));
			
		/*echo '<pre>';
		print_r($data);
		exit;*/
		
		$html=$CI->load->view('iibfdra/dra_admitcardpdf_attach', $data, true);
		
		// echo $html; exit;
		
		$dra_admit_card_details = $CI->db->get_where('dra_admitcard_info', array('mem_mem_no'=>$member_id,'exm_cd'=>$exam_code,'exam_period'=>$exam_period));
		 
			$CI->master_model->insertRecord('dra_adminlogs',array("title"=>"DRA Generate admit card End", "description"=>$pdfFilePath));		
		
		return $html;
		}
		
	}catch(Exception $e){
		echo $e->getMessage();
	}
	
}


function dra_img_p($member_id=NULL){
	
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
	
	
function dra_img_s($member_id=NULL){
		
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