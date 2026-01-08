<?php defined('BASEPATH')||exit('No Direct Allowed Here');
	/*dra_agency_center_mail_helper
		* @copyright    Copyright (c) 2018 ESDS Software Solution Private.
		* @author       Aayusha Kapadni
		* @package      Helper
		* @updated      2019-04-11
	*/
	/*Agency Center Approve with */
	function agency_center_approve_mail($center_id,$user_type_flag)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'agency_center_approve'));    
		$CI->db->select('agency_center.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id', 'left'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT'); 
		$user_info = $CI->master_model->getRecords('agency_center',array('agency_center.center_id'=>$center_id));
		
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['center_type'].' '.$user_info[0]['state_name'].' '.$user_info[0]['center_status'];
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		if($user_info[0]['center_type']=='R')
		{
			$user_info[0]['center_type']='Regular';
		}
		if($user_info[0]['center_type']=='T')
		{
			$user_info[0]['center_type']='Temporary';
		}
		$newstring4 = str_replace("#CENTER_TYPE#", "".$user_info[0]['center_type']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		if($user_info[0]['center_status']=="A")
		{
			$user_info[0]['center_status']="Approved";
		}
		
		$final_str = str_replace("#CENTER_STATUS#", "".$user_info[0]['center_status']."", $newstring5);
		
		
		
		if($user_type_flag == '1'){
			
			$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','subject'=>$user_info[0]['city_name'].' Centre is Approved.','message'=>$final_str);
			$CI->Emailsending->mailsend($info_arr);
			}else{
			$dra_admin = $CI->master_model->getRecords('dra_admin',array('roleid'=>'1'));
			foreach ($dra_admin as $key) {
				$to_mail = $key['emailid'];
				// if(!empty($to_mail)){
				//      $info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','subject'=>$user_info[0]['city_name'].' Centre is Approved.','message'=>$final_str);
				//      $CI->Emailsending->mailsend($info_arr);
				// }
				
			}
			
			$info_arr = array('to'=>'smuralidaran@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>$user_info[0]['city_name'].' Centre is Approved.','message'=>$final_str);
			$CI->Emailsending->mailsend($info_arr);
			
			$info_arr1 = array('to'=>'kalpanashetty@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>$user_info[0]['city_name'].' Centre is Approved.','message'=>$final_str);
			$CI->Emailsending->mailsend($info_arr1);
		}
		
		// $info_arr2 = array('to'=>'roopal.agrawal@esds.co.in','from'=>'logs@iibf.esdsconnect.com','subject'=>$user_info[0]['city_name'].' Centre is Approved.','message'=>$final_str);
		// $CI->Emailsending->mailsend($info_arr2);
		
	}
	/*Agency Center Rejection wṭith Reason*/
	function agency_center_reject_mail($center_id,$user_type_flag)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'agency_center_reject'));
		
		$CI->db->select('agency_center.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name,agency_center_rejection.rejection');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id', 'left');
		$CI->db->order_by('agency_center_rejection.created_on','DESC'); 
		$CI->db->LIMIT('1'); 
		$CI->db->where('agency_center_rejection.center_id',$center_id); 
		$CI->db->join('agency_center_rejection','agency_center_rejection.center_id=agency_center.center_id','LEFT'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT'); 
		$user_info = $CI->master_model->getRecords('agency_center',array('agency_center.center_id'=>$center_id));             
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['center_type'].' '.$user_info[0]['state_name'].' '.$user_info[0]['center_status'].' '.$user_info[0]['rejection'];
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		if($user_info[0]['center_type']=='R')
		{
			$user_info[0]['center_type']='Regular';
		}
		if($user_info[0]['center_type']=='T')
		{
			$user_info[0]['center_type']='Temporary';
		}
		$newstring4 = str_replace("#CENTER_TYPE#", "".$user_info[0]['center_type']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		if($user_info[0]['center_status']=="R")
		{
			$user_info[0]['center_status']="Rejected";
		}
		$newstring6 = str_replace("#CENTER_STATUS#", "".$user_info[0]['center_status']."", $newstring5); 
		$final_str  = str_replace("#REJECTION_REASON#", "".$user_info[0]['rejection']."", $newstring6);
		
		$bcc = array('roopal.agrawal@esds.co.in','aayusha.kapadni@esds.co.in');
		
		if($user_type_flag == '2'){
			$dra_admin = $CI->master_model->getRecords('dra_admin',array('roleid'=>'1'));
			foreach ($dra_admin as $key) {
				$to_mail = $key['emailid'];
				
				// if(!empty($to_mail)){
				//      $info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>$user_info[0]['city_name'].' Centre is Rejected.','message'=>$final_str);
				//      $CI->Emailsending->mailsend($info_arr);
				//   }  
				
				
			}
			
			$info_arr = array('to'=>'smuralidaran@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>$user_info[0]['city_name'].' Centre is Rejected.','message'=>$final_str);
			$CI->Emailsending->mailsend($info_arr);
			
			$info_arr1 = array('to'=>'kalpanashetty@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>$user_info[0]['city_name'].' Centre is Rejected.','message'=>$final_str);
			$CI->Emailsending->mailsend($info_arr1);
		}
		//   $info_arr2 = array('to'=>'roopal.agrawal@esds.co.in','from'=>'logs@iibf.esdsconnect.com','subject'=>$user_info[0]['city_name'].' Centre is Rejected.','message'=>$final_str);
		// $CI->Emailsending->mailsend($info_arr2);
		
		
		
	}
	/*Agency Center Accradation Period */
	function center_accradation_period_mail($center_id)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'center_accradation_period'));
		
		$CI->db->select('agency_center.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_center.agency_id', 'left'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT'); 
		$user_info = $CI->master_model->getRecords('agency_center',array('agency_center.center_id'=>$center_id));
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['center_type'].' '.$user_info[0]['state_name'].' '.$user_info[0]['center_status'].' '.$user_info[0]['center_validity_from'].' '.$user_info[0]['center_validity_to'];
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		if($user_info[0]['center_type']=='R')
		{
			$user_info[0]['center_type']='Regular';
		}
		if($user_info[0]['center_type']=='T')
		{
			$user_info[0]['center_type']='Temporary';
		}
		$newstring4 = str_replace("#CENTER_TYPE#", "".$user_info[0]['center_type']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		$newstring6 = str_replace("#CENTER_VALIDITY_FROM#", "".date('d-M-Y',strtotime($user_info[0]['center_validity_from']))."", $newstring5);
		$newstring7 = str_replace("#CENTER_VALIDITY_TO#", "".date('d-M-Y',strtotime($user_info[0]['center_validity_to']))."", $newstring6);
		if($user_info[0]['center_status']=="A")
		{
			$user_info[0]['center_status']="Approved";
		}
		
		$final_str  = str_replace("#CENTER_STATUS#", "".$user_info[0]['center_status']."", $newstring7);
		$bcc = array('roopal.agrawal@esds.co.in','aayusha.kapadni@esds.co.in');
		$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Accreditation Period for '.$user_info[0]['city_name'].' Center is Assigned.','message'=>$final_str);
		$CI->Emailsending->mailsend($info_arr);
		
		$info_arr1 = array('to'=>'kalpanashetty@iibf.org.in','from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Accreditation Period for '.$user_info[0]['city_name'].' Center is Assigned.','message'=>$final_str);
		$CI->Emailsending->mailsend($info_arr1);
		
	}
	
	/*Batch Approve  Mail */
	function batch_approve_mail($batch_id,$user_type_flag)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_approve'));
		
		$CI->db->select('agency_batch.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','left'); 
		$CI->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT'); 
		$user_info = $CI->master_model->getRecords('agency_batch',array('agency_batch.id'=>$batch_id));
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['state_name'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_code'].' '.$user_info[0]['batch_status'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'];
		
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		$newstring4 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		if($user_info[0]['batch_type']=='C')
		{
			$user_info[0]['batch_type']= ' Combine Batch ';
		}
		else
		{
			$user_info[0]['batch_type']= 'Separate Batch';
		}
		$newstring6 = str_replace("#BATCH_TYPE#", "".$user_info[0]['batch_type']."", $newstring5);
		$newstring7 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring6);
		$newstring8 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring7);
		if($user_info[0]['batch_status']=='A')
		{
			$user_info[0]['batch_status']= 'Approved';
		}
		
		$newstring9 = str_replace("#BATCH_STATUS#", "".$user_info[0]['batch_status']."", $newstring8);
		$final_str  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring9);
		
		$bcc = array('iibfdevp@esds.co.in');
		// $bcc = array('roopal.agrawal@esds.co.in','aayusha.kapadni@esds.co.in');
		// if($user_type_flag == '2'){
		//     $dra_admin = $CI->master_model->getRecords('dra_admin',array('roleid'=>'1'));
		//     foreach ($dra_admin as $key) {
		//        $to_mail = $key['emailid'];
		//     if(!empty($to_mail)){
		//           $info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Approved.','message'=>$final_str);
		
		//           $CI->Emailsending->mailsend($info_arr);
		
		//     }
		
		//     }
		//    }
		$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Approved.','message'=>$final_str);

		$CI->Emailsending->sendmail($info_arr);
		//$CI->Emailsending->mailsend($info_arr);
	}	
	
	/*Batch Rejection Mail with reason */
	function batch_reject_mail($batch_id,$user_type_flag)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_reject'));
		$CI->db->select('agency_batch.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name,agency_batch_rejection.rejection');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','left'); 
		$CI->db->order_by('agency_batch_rejection.created_on','DESC');
		$CI->db->limit('1'); 
		$CI->db->join('agency_batch_rejection','agency_batch_rejection.batch_id='.$batch_id,'LEFT'); 
		$CI->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT');
		
		$user_info = $CI->master_model->getRecords('agency_batch',array('agency_batch.id'=>$batch_id));
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['state_name'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_code'].' '.$user_info[0]['batch_status'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['rejection'];
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		$newstring4 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		$newstring6 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring5);
		$newstring7 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring6);
		if($user_info[0]['batch_status']=='R')
		{
			$user_info[0]['batch_status']= 'Rejected';
		}
		$newstring8 = str_replace("#BATCH_STATUS#", "".$user_info[0]['batch_status']."", $newstring7);
		$newstring9 = str_replace("#REJECTION_REASON#", "".$user_info[0]['rejection']."", $newstring8);   
		if($user_info[0]['batch_type']=='C')
		{
			$user_info[0]['batch_type']= ' Combine Batch';
		}
		else
		{
			$user_info[0]['batch_type']= 'Separate Batch';
		}
		$newstring10 = str_replace("#BATCH_TYPE#", "".$user_info[0]['batch_type']."", $newstring9);
		$final_str  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring10);
		$bcc = array('roopal.agrawal@esds.co.in','aayusha.kapadni@esds.co.in');
		
		// if($user_type_flag == '2'){
		//     $dra_admin = $CI->master_model->getRecords('dra_admin',array('roleid'=>'1'));
		//     foreach ($dra_admin as $key) {
		//        $to_mail = $key['emailid'];
		//     if(!empty($to_mail)){
		//          $info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Rejected.','message'=>$final_str);
		
		//          $CI->Emailsending->mailsend($info_arr);
		
		//     }
		
		//     }
		//    }
		$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Rejected.','message'=>$final_str);
		
		$CI->Emailsending->mailsend($info_arr);
		
	}
	
	/*Batch Cancelled Mail */
	function batch_cancel_mail($batch_id,$user_type_flag)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_cancel'));
		$CI->db->select('agency_batch.*,dra_inst_registration.inst_name,dra_inst_registration.inst_head_email,city_master.city_name,state_master.state_name,agency_batch_rejection.rejection');
		$CI->db->join('dra_inst_registration', 'dra_inst_registration.id = agency_batch.agency_id','left'); 
		$CI->db->join('agency_batch_rejection','agency_batch_rejection.batch_id='.$batch_id,'LEFT'); 
		$CI->db->join('agency_center', 'agency_center.center_id = agency_batch.center_id'); 
		$CI->db->join('city_master','agency_center.location_name=city_master.id','LEFT'); 
		$CI->db->join('state_master','agency_center.state=state_master.state_code','LEFT'); 
		$user_info =$CI->master_model->getRecords('agency_batch',array('agency_batch.id'=>$batch_id));
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['state_name'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_code'].' '.$user_info[0]['batch_status'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['rejection'];
		$to_mail = $user_info[0]['inst_head_email'];
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['city_name']."", $newstring2 );
		$newstring4 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring3 );
		$newstring5 = str_replace("#STATE#", "".$user_info[0]['state_name']."", $newstring4);
		$newstring6 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring5);
		$newstring7 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring6);
		if($user_info[0]['batch_status']=='C')
		{
			$user_info[0]['batch_status']= 'Cancelled';
		}
		$newstring8 = str_replace("#BATCH_STATUS#", "".$user_info[0]['batch_status']."", $newstring7);
		$newstring9 = str_replace("#REJECTION_REASON#", "".$user_info[0]['rejection']."", $newstring8);
		if($user_info[0]['batch_type']=='C')
		{
			$user_info[0]['batch_type']= ' Combine Batch';
		}
		else
		{
			$user_info[0]['batch_type']= 'Separate Batch';
		}
		$newstring10 = str_replace("#BATCH_TYPE#", "".$user_info[0]['batch_type']."", $newstring9);
		$final_str  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring10);
		$bcc = array('roopal.agrawal@esds.co.in','aayusha.kapadni@esds.co.in');
		
		// if($user_type_flag == '2'){
		//     $dra_admin = $CI->master_model->getRecords('dra_admin',array('roleid'=>'1'));
		//     foreach ($dra_admin as $key) {
		//        $to_mail = $key['emailid'];
		//     if(!empty($to_mail)){
		//         $info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Cancelled.','message'=>$final_str);
		//        $CI->Emailsending->mailsend($info_arr);  
		
		//     }
		
		//     }
		//    }
		$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'Your Batch '.$user_info[0]['batch_name'].' is Cancelled.','message'=>$final_str);
		$CI->Emailsending->mailsend($info_arr);  
		
	}
	
	function batch_inspection_mail($batch_id,$inspector_email, $inspector_id)
	{ 
		//Added 3rd parameter $inspector_id by Priyanka Wadnere for getting inspector's credentials from table
		$attachpath = "";
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_inspection'));
		$CI->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname");    
		$CI->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$CI->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$CI->db->join('city_master','agency_batch.city=city_master.id','LEFT');       
		$CI->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$CI->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$CI->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');       
		$CI->db->where('agency_batch.id = '.$batch_id);
		$CI->db->where('agency_center.center_display_status','1'); // to hide centers and batches.        
		
		$user_info = $CI->master_model->getRecords("agency_batch");
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['cityname'].' '.$user_info[0]['inst_head_name'].' '.$user_info[0]['inst_head_contact_no'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['timing_from'].' '.$user_info[0]['timing_to'].' '.$user_info[0]['total_candidates'].' '.$user_info[0]['batch_code'];
		
		/* Code added By Manoj: 15 May 2019 */
		$batch_address = "";
	 	$batch_address = $user_info[0]['addressline1'].' '.$user_info[0]['addressline2'].' '.$user_info[0]['addressline3'].' '.$user_info[0]['addressline4'].' '.$user_info[0]['district'].' '.$user_info[0]['state_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['pincode'];
	 	/* Close Code added By Manoj: 15 May 2019 */
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['cityname']."", $newstring2 );
		$newstring4 = str_replace("#CONTACT_PERSON#", "".$user_info[0]['contact_person_name']."", $newstring3 );
		
		/*if($user_info[0]['inst_head_contact_no']=='')
			{
			$user_info[0]['inst_head_contact_no']= '-';
			}
			else
			{
			$user_info[0]['inst_head_contact_no'];
		}*/
		
		if($user_info[0]['contact_person_phone']=='')
		{
			$user_info[0]['contact_person_phone']= '-';
		}
		else
		{
			$user_info[0]['contact_person_phone'];
		}
		
		// name_of_bank and remarks
		if($user_info[0]['name_of_bank']=='')
		{
			$name_of_bank =  '-';
		}
		else
		{
			$name_of_bank = $user_info[0]['name_of_bank'];
		}
		
		if($user_info[0]['remarks']=='')
		{
			$remarks =  '-';
		}
		else
		{
			$remarks = $user_info[0]['remarks'];
		}
		
		if($user_info[0]['faculty_name']=='')
		{
			$faculty_name =  '-';
		}
		elseif($user_info[0]['faculty_qualification']=='')
		{
			$faculty_name = $user_info[0]['faculty_name'];
			}else{
			$faculty_name = $user_info[0]['faculty_name'].' , '.$user_info[0]['faculty_qualification'];
		}
		
		if($user_info[0]['faculty_name2']=='')
		{
			$faculty_name2 =  '-';
		}
		elseif($user_info[0]['faculty_qualification2']=='')
		{
			$faculty_name2 = $user_info[0]['faculty_name2'];
			}else{
			$faculty_name2 = $user_info[0]['faculty_name2'].' , '.$user_info[0]['faculty_qualification2'];
		}
		
		$newstring5 = str_replace("#CONTACT_NUMBER#", "".$user_info[0]['contact_person_phone']."", $newstring4);
		$newstring6 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring5);
		$newstring7 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring6);
		$newstring8 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring7);
		$newstring9 = str_replace("#TIMING_FROM#", "".$user_info[0]['timing_from']."", $newstring8);
		$newstring10 = str_replace("#TIMING_TO#", "".$user_info[0]['timing_to']."", $newstring9);
		$newstring11 = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring10);		
		$newstring12 = str_replace("#BANK_NAME#", "".$name_of_bank."", $newstring11);
		$newstring13 = str_replace("#REMARK#", "".$remarks."", $newstring12);
		$newstring14 = str_replace("#ADDRESS#", "".$batch_address."", $newstring13);
		$newstring15 = str_replace("#FACULTY_DETAILS_1#", "".$faculty_name."", $newstring14);
		$newstring16 = str_replace("#FACULTY_DETAILS_2#", "".$faculty_name2."", $newstring15);  	
		$newstring17  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring16);
		
		$final_str  = str_replace("#CANDIDATES#", "".$user_info[0]['total_candidates']."", $newstring17);
		########## START : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		$online_user_details = '';
		if(isset($user_info[0]['batch_online_offline_flag']) && $user_info[0]['batch_online_offline_flag'] == 1) //IF BATCH IS ONLINE THEN SEND USER IDS AND PASSWORD WITH URL
		{
			$CI->db->where('agency_id = '.$user_info[0]['agency_id']);
			$CI->db->where('batch_id = '.$batch_id);
			$user_id_password_data = $CI->master_model->getRecords("agency_online_batch_user_details");
			if(count($user_id_password_data) > 0)
			{
				$online_user_details .='<p>Please check below login details and On-line training platform details</p>
				<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
				<thead>
				<tr>
				<th style="padding:5px 10px;">Login Id</th>
				<th style="padding:5px 10px;">Password</th>
				</tr>
				</thead>
				<tbody>';
				foreach($user_id_password_data as $Res)
				{
					$online_user_details .='
					<tr>
					<td style="padding:5px 10px;">'.$Res['login_id'].'</td>
					<td style="padding:5px 10px;">'.base64_decode($Res['password']).'</td>
					</tr>
					';
				}						
				$online_user_details .='
				</tbody>
				</table>';
				
				$online_user_details .='<p><strong>On-line training platform used : </strong>'.$user_info[0]['online_training_platform'].'</p>';
			}		 	
		}
		$final_str  = str_replace("#ONLINE_USER_DETAILS#", "".$online_user_details."", $final_str);

		//Added by Priyanka Wadnere to send Inspector Credentials on mail while assigning inspector
		$CI->db->where('agency_inspector_master.id',$inspector_id);
		$inspectorRes = $CI->master_model->getRecords("agency_inspector_master");	

		$inspectorStr = '';
		if(count($inspectorRes) > 0)
		{
			$inspectorStr.='<p>Please use below login details while login</p>
			<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
			<thead>
			<tr>
			<th style="padding:5px 10px;">Login Id</th>
			<th style="padding:5px 10px;">Password</th>
			</tr>
			</thead>
			<tbody>';
			foreach($inspectorRes as $Res)
			{
				$inspectorStr .='
				<tr>
				<td style="padding:5px 10px;">'.$Res['username'].'</td>
				<td style="padding:5px 10px;">'.$Res['plain_password'].'</td>
				</tr>
				';
			}						
			$inspectorStr .='
			</tbody>
			</table>';
		}		

		$final_str  = str_replace("#ISPECTOR_CREDENTIALS#", "".$inspectorStr."", $final_str);
		//Added by Priyanka Wadnere to send Inspector Credentials on mail while assigning inspector

		//$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.pdf';
		$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';
		
		$inspector_email_send = $inspector_email;	
		
		/*$info_arr = array('to'=>'roopal.agrawal@esds.co.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr,$attachpath);  */
		
		// $bcc = array('sonal.chavan@esds.co.in','roopal.agrawal@esds.co.in','soumya@iibf.org.in','aayusha.kapadni@esds.co.in');
		
		/* $info_arr1 = array('to'=>$inspector_email_send,'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '.$user_info[0]['cityname'],'message'=>$final_str);      
		$CI->Emailsending->mailsend_attch($info_arr1,$attachpath); */
		
		//Remove soumya@iibf.org.in   and include rohini@iibf.org.in and lathasekhar@iibf.org.in 
		/* $info_arr2 = array('to'=>'dharmvirm@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr2,$attachpath); */  
		
		// $info_arr3 = array('to'=>'lathasekhar@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		// $CI->Emailsending->mailsend_attch($info_arr3,$attachpath); 
		
		/* $info_arr3 = array('to'=>'kavan@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr3,$attachpath); */  
		
		
		/* $info_arr4 = array('to'=>'balasalian@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr4,$attachpath); */  
		
		########## START : CODE ADDED BY SAGAR ON 24-08-2020 ###################
		$info_arr1 = array('to'=>$inspector_email_send,'cc'=>'iibfteam@esds.co.in,logs@iibf.esdsconnect.com,prakash@iibf.org.in,Je.exm7@iibf.org.in','subject'=>'DRA Inspection ('.$user_info[0]['cityname'].') ('.$user_info[0]['batch_code'].') ('.date('d-M-Y',strtotime($user_info[0]['batch_from_date'])).' TO '.date('d-M-Y',strtotime($user_info[0]['batch_to_date'])).')','message'=>$final_str);

		//print_r($info_arr1);die;      
		$response = $CI->Emailsending->mailsend_attch_cc($info_arr1,$attachpath);
		
		$CI->master_model->insertRecord('dra_batch_approve_mail_log',array('inspector_name'=>$response,'tmp_id'=>'3','inspector_email'=>$inspector_email_send,'batch_id'=>$batch_id,'mail_content'=>json_encode($info_arr1),'created_on'=>date('Y-m-d H:i:s')));
		########## END : CODE ADDED BY SAGAR ON 24-08-2020 ###################  
	}

	function batch_inspection_mail_V2($batch_id,$inspector_email,$inspector_id)
	{ 

		$attachpath = "";
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_inspection'));

		$CI->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname");    
		$CI->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$CI->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$CI->db->join('city_master','agency_batch.city=city_master.id','LEFT');       
		$CI->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$CI->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$CI->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');       
		$CI->db->where('agency_batch.id = '.$batch_id);
		$CI->db->where('agency_center.center_display_status','1'); // to hide centers and batches.        
		
		$user_info = $CI->master_model->getRecords("agency_batch");
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['cityname'].' '.$user_info[0]['inst_head_name'].' '.$user_info[0]['inst_head_contact_no'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['timing_from'].' '.$user_info[0]['timing_to'].' '.$user_info[0]['total_candidates'].' '.$user_info[0]['batch_code'];
		
		/* Code added By Manoj: 15 May 2019 */
		$batch_address = "";
	 	$batch_address = $user_info[0]['addressline1'].' '.$user_info[0]['addressline2'].' '.$user_info[0]['addressline3'].' '.$user_info[0]['addressline4'].' '.$user_info[0]['district'].' '.$user_info[0]['state_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['pincode'];
	 	/* Close Code added By Manoj: 15 May 2019 */
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);

		$newstring = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);

		$newstring1 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring);

		if($user_info[0]['contact_person_phone']=='')
		{
			$user_info[0]['contact_person_phone']= '-';
		}
		else
		{
			$user_info[0]['contact_person_phone'];
		}
		
		if($user_info[0]['remarks']=='')
		{
			$remarks =  '-';
		}
		else
		{
			$remarks = $user_info[0]['remarks'];
		}

		if($user_info[0]['batch_online_offline_flag'] == 1){
			$training_mode = 'Online';
		}
		else{
			$training_mode = 'Offline';
		}

		$CI->db->where('agency_inspector_master.id',$inspector_id);
		$inspectorRes = $CI->master_model->getRecords("agency_inspector_master");	

		$newstring2 = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring1);	
		$newstring3 = str_replace("#TRAINING_MODE#", "".$training_mode."", $newstring2);
		$newstring4 = str_replace("#BATCH_TYPE#", "".$user_info[0]['hours']." Hours", $newstring3);
		$newstring5 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring4);
		$newstring6 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring5);
		$newstring7 = str_replace("#TIMING_FROM#", "".$user_info[0]['timing_from']."", $newstring6);
		$newstring8 = str_replace("#TIMING_TO#", "".$user_info[0]['timing_to']."", $newstring7);
		$newstring9 = str_replace("#CONTACT_PERSON#", "".$user_info[0]['contact_person_name']."", $newstring8);
		$newstring10 = str_replace("#CONTACT_NUMBER#", "".$user_info[0]['contact_person_phone']."", $newstring9);
		$newstring11 = str_replace("#ADDITIONAL_CONTACT_PERSON#", "".$user_info[0]['alt_contact_person_name']."", $newstring10);
		$newstring12 = str_replace("#ADDITIONAL_CONTACT_NUMBER#", "".$user_info[0]['alt_contact_person_phone']."", $newstring11);
		$newstring13 = str_replace("#ADDRESS#", "".$batch_address."", $newstring12);
		$newstring14 = str_replace("#TRAINING_LANGUAGE#", "".$user_info[0]['training_medium']."", $newstring13);
		$newstring15 = str_replace("#INSPECTOR_NAME#", "".$inspectorRes[0]['inspector_name']."", $newstring14);
	
		$final_str  = str_replace("#REMARK#", "".$remarks."", $newstring15);
		//echo $final_str; die;

		########## START : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		$online_user_details = '';
		if(isset($user_info[0]['batch_online_offline_flag']) && $user_info[0]['batch_online_offline_flag'] == 1) //IF BATCH IS ONLINE THEN SEND USER IDS AND PASSWORD WITH URL
		{
			$CI->db->where('agency_id = '.$user_info[0]['agency_id']);
			$CI->db->where('batch_id = '.$batch_id);
			$user_id_password_data = $CI->master_model->getRecords("agency_online_batch_user_details");
			if(count($user_id_password_data) > 0)
			{
				$online_user_details .='<p>Please find below login details for the On-line training platform: </p> 
				<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
				<thead>
				<tr>
				<th style="padding:5px 10px;">DRA Training Batch No.</th>
				<th style="padding:5px 10px;">Name of Online Platform</th>
				</tr>
				</thead>
				<tbody>
					<tr>
					<td style="padding:5px 10px;">'.$user_info[0]['batch_code'].'</td>
					<td style="padding:5px 10px;">'.$user_info[0]['online_training_platform'].'</td>
					</tr>
				</tbody>
				</table>	
				</br>
				<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
				<thead>
				<tr>
				<th style="padding:5px 10px;">Login Id</th>
				<th style="padding:5px 10px;">Password</th>
				</tr>
				</thead>
				<tbody>';
				foreach($user_id_password_data as $Res)
				{
					$online_user_details .='
					<tr>
					<td style="padding:5px 10px;">'.$Res['login_id'].'</td>
					<td style="padding:5px 10px;">'.base64_decode($Res['password']).'</td>
					</tr>
					';
				}						
				$online_user_details .='
				</tbody>
				</table>';
				
				$online_user_details .='<p><strong>Online Platform Link : </strong>'.$user_info[0]['platform_link'].'</p>';
			}		 	
		}
		$final_str  = str_replace("#ONLINE_USER_DETAILS#", "".$online_user_details."", $final_str);

		//echo $final_str; die;
		########## END : CODE ADDED BY SAGAR ON 19-08-2020 ###################

		//Added by Priyanka Wadnere to send Inspector Credentials on mail while assigning inspector
		/*$CI->db->where('agency_inspector_master.id',$inspector_id);
		$inspectorRes = $CI->master_model->getRecords("agency_inspector_master");	

		$inspectorStr = '';
		if(count($inspectorRes) > 0)
		{
			$inspectorStr.='<p>Please use below Inspector login details while login</p>
			<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
			<thead>
			<tr>
			<th style="padding:5px 10px;">Login Id</th>
			<th style="padding:5px 10px;">Password</th>
			</tr>
			</thead>
			<tbody>';
			foreach($inspectorRes as $Res)
			{
				$inspectorStr .='
				<tr>
				<td style="padding:5px 10px;">'.$Res['username'].'</td>
				<td style="padding:5px 10px;">'.$Res['plain_password'].'</td>
				</tr>
				';
			}						
			$inspectorStr .='
			</tbody>
			</table>';
		}		

		$final_str  = str_replace("#ISPECTOR_CREDENTIALS#", "".$inspectorStr."", $final_str);*/
		//Added by Priyanka Wadnere to send Inspector Credentials on mail while assigning inspector
		
		//$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.pdf';
		//$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';
		$attachpath = base_url().'/uploads/training_schedule/'.$user_info[0]['training_schedule'];
		
		$inspector_email_send = $inspector_email;	


		$bcc = array('iibfdevp@esds.co.in','sagar.matale@esds.co.in','priyanka.wadnere@esds.co.in');
		
		########## START : CODE ADDED BY SAGAR ON 24-08-2020 ###################
		/*$info_arr1 = array('to'=>$inspector_email_send,'cc'=>'iibfdevp@esds.co.in,logs@iibf.esdsconnect.com,prakash@iibf.org.in,Je.exm7@iibf.org.in','bcc'=>$bcc,'subject'=>'DRA Inspection ('.$user_info[0]['cityname'].') ('.$user_info[0]['batch_code'].') ('.date('d-M-Y',strtotime($user_info[0]['batch_from_date'])).' TO '.date('d-M-Y',strtotime($user_info[0]['batch_to_date'])).')','message'=>$final_str); */

		$info_arr1 = array('to'=>'iibfteam@esds.co.in','cc'=>'iibfdevp@esds.co.in,logs@iibf.esdsconnect.com,sagar.matale@esds.co.in,priyanka.wadnere@esds.co.in','subject'=>'DRA Inspection ('.$user_info[0]['cityname'].') ('.$user_info[0]['batch_code'].') ('.date('d-M-Y',strtotime($user_info[0]['batch_from_date'])).' TO '.date('d-M-Y',strtotime($user_info[0]['batch_to_date'])).')','message'=>$final_str);

		$response = $CI->Emailsending->mailsend_attch_cc($info_arr1,$attachpath);
		
		$CI->master_model->insertRecord('dra_batch_approve_mail_log',array('inspector_name'=>$response,'tmp_id'=>'3','inspector_email'=>$inspector_email_send,'batch_id'=>$batch_id,'mail_content'=>json_encode($info_arr1),'created_on'=>date('Y-m-d H:i:s')));
		########## END : CODE ADDED BY SAGAR ON 24-08-2020 ###################  
	}
	
	/*Batcj reminder mail*/
	function batch_inspection_mail_reminder($batch_id,$inspector_email)
	{ 
		$attachpath = "";
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_inspection_reminder'));
		$CI->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname");    
		$CI->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$CI->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$CI->db->join('city_master','agency_batch.city=city_master.id','LEFT');       
		$CI->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$CI->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$CI->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');       
		$CI->db->where('agency_batch.id = '.$batch_id);
		$CI->db->where('agency_center.center_display_status','1'); // to hide centers and batches.        
		
		$user_info = $CI->master_model->getRecords("agency_batch");
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['cityname'].' '.$user_info[0]['inst_head_name'].' '.$user_info[0]['inst_head_contact_no'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['timing_from'].' '.$user_info[0]['timing_to'].' '.$user_info[0]['total_candidates'].' '.$user_info[0]['batch_code'];
		
		/* Code added By Manoj: 15 May 2019 */
		$batch_address = "";
	 	$batch_address = $user_info[0]['addressline1'].' '.$user_info[0]['addressline2'].' '.$user_info[0]['addressline3'].' '.$user_info[0]['addressline4'].' '.$user_info[0]['district'].' '.$user_info[0]['state_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['pincode'];
	 	/* Close Code added By Manoj: 15 May 2019 */
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['cityname']."", $newstring2 );
		$newstring4 = str_replace("#CONTACT_PERSON#", "".$user_info[0]['contact_person_name']."", $newstring3 );
		
		/*if($user_info[0]['inst_head_contact_no']=='')
			{
			$user_info[0]['inst_head_contact_no']= '-';
			}
			else
			{
			$user_info[0]['inst_head_contact_no'];
		}*/
		
		if($user_info[0]['contact_person_phone']=='')
		{
			$user_info[0]['contact_person_phone']= '-';
		}
		else
		{
			$user_info[0]['contact_person_phone'];
		}
		
		// name_of_bank and remarks
		if($user_info[0]['name_of_bank']=='')
		{
			$name_of_bank =  '-';
		}
		else
		{
			$name_of_bank = $user_info[0]['name_of_bank'];
		}
		
		if($user_info[0]['remarks']=='')
		{
			$remarks =  '-';
		}
		else
		{
			$remarks = $user_info[0]['remarks'];
		}
		
		if($user_info[0]['faculty_name']=='')
		{
			$faculty_name =  '-';
		}
		elseif($user_info[0]['faculty_qualification']=='')
		{
			$faculty_name = $user_info[0]['faculty_name'];
			}else{
			$faculty_name = $user_info[0]['faculty_name'].' , '.$user_info[0]['faculty_qualification'];
		}
		
		if($user_info[0]['faculty_name2']=='')
		{
			$faculty_name2 =  '-';
		}
		elseif($user_info[0]['faculty_qualification2']=='')
		{
			$faculty_name2 = $user_info[0]['faculty_name2'];
			}else{
			$faculty_name2 = $user_info[0]['faculty_name2'].' , '.$user_info[0]['faculty_qualification2'];
		}
		
		$newstring5 = str_replace("#CONTACT_NUMBER#", "".$user_info[0]['contact_person_phone']."", $newstring4);
		$newstring6 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring5);
		$newstring7 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring6);
		$newstring8 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring7);
		$newstring9 = str_replace("#TIMING_FROM#", "".$user_info[0]['timing_from']."", $newstring8);
		$newstring10 = str_replace("#TIMING_TO#", "".$user_info[0]['timing_to']."", $newstring9);
		$newstring11 = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring10);		
		$newstring12 = str_replace("#BANK_NAME#", "".$name_of_bank."", $newstring11);
		$newstring13 = str_replace("#REMARK#", "".$remarks."", $newstring12);
		$newstring14 = str_replace("#ADDRESS#", "".$batch_address."", $newstring13);
		$newstring15 = str_replace("#FACULTY_DETAILS_1#", "".$faculty_name."", $newstring14);
		$newstring16 = str_replace("#FACULTY_DETAILS_2#", "".$faculty_name2."", $newstring15);  	
		$newstring17  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring16);
		
		$final_str  = str_replace("#CANDIDATES#", "".$user_info[0]['total_candidates']."", $newstring17);
		########## START : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		$online_user_details = '';
		if(isset($user_info[0]['batch_online_offline_flag']) && $user_info[0]['batch_online_offline_flag'] == 1) //IF BATCH IS ONLINE THEN SEND USER IDS AND PASSWORD WITH URL
		{
			$CI->db->where('agency_id = '.$user_info[0]['agency_id']);
			$CI->db->where('batch_id = '.$batch_id);
			$user_id_password_data = $CI->master_model->getRecords("agency_online_batch_user_details");
			if(count($user_id_password_data) > 0)
			{
				$online_user_details .='<p>Please check below login details and On-line training platform details</p>
				<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
				<thead>
				<tr>
				<th style="padding:5px 10px;">Login Id</th>
				<th style="padding:5px 10px;">Password</th>
				</tr>
				</thead>
				<tbody>';
				foreach($user_id_password_data as $Res)
				{
					$online_user_details .='
					<tr>
					<td style="padding:5px 10px;">'.$Res['login_id'].'</td>
					<td style="padding:5px 10px;">'.base64_decode($Res['password']).'</td>
					</tr>
					';
				}						
				$online_user_details .='
				</tbody>
				</table>';
				
				$online_user_details .='<p><strong>On-line training platform used : </strong>'.$user_info[0]['online_training_platform'].'</p>';
			}		 	
		}
		$final_str  = str_replace("#ONLINE_USER_DETAILS#", "".$online_user_details."", $final_str);
		########## END : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		
		//$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.pdf';
		$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';
		
		$inspector_email_send = $inspector_email ;	
		
		/*$info_arr = array('to'=>'roopal.agrawal@esds.co.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr,$attachpath);  */
		
		// $bcc = array('sonal.chavan@esds.co.in','roopal.agrawal@esds.co.in','soumya@iibf.org.in','aayusha.kapadni@esds.co.in');
		
		/* $info_arr1 = array('to'=>$inspector_email_send,'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '.$user_info[0]['cityname'],'message'=>$final_str);      
		$CI->Emailsending->mailsend_attch($info_arr1,$attachpath); */
		
		//Remove soumya@iibf.org.in   and include rohini@iibf.org.in and lathasekhar@iibf.org.in 
		/* $info_arr2 = array('to'=>'dharmvirm@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr2,$attachpath); */  
		
		// $info_arr3 = array('to'=>'lathasekhar@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		// $CI->Emailsending->mailsend_attch($info_arr3,$attachpath); 
		
		/* $info_arr3 = array('to'=>'kavan@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr3,$attachpath); */  
		
		
		/* $info_arr4 = array('to'=>'balasalian@iibf.org.in', 'from'=>'logs@iibf.esdsconnect.com','subject'=>'DRA Inspection '. $user_info[0]['cityname'],'message'=>$final_str);
		$CI->Emailsending->mailsend_attch($info_arr4,$attachpath); */  
		
		########## START : CODE ADDED BY SAGAR ON 24-08-2020 ###################
		$info_arr1 = array('to'=>$inspector_email_send,'from'=>'logs@iibf.esdsconnect.com', 'cc'=>'prakash@iibf.org.in,Je.exm7@iibf.org.in,iibfteam@esds.co.in','subject'=>'REMINDER DRA Inspection ('.$user_info[0]['cityname'].') ('.$user_info[0]['batch_code'].') ('.date('d-M-Y',strtotime($user_info[0]['batch_from_date'])).' TO '.date('d-M-Y',strtotime($user_info[0]['batch_to_date'])).')','message'=>$final_str);      
		$CI->Emailsending->mailsend_attch_cc($info_arr1,$attachpath);
		########## END : CODE ADDED BY SAGAR ON 24-08-2020 ################### 
	}
	
	function batch_inspection_mail_test($batch_id,$inspector_email)
	{ 
		$attachpath = "";
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'batch_inspection'));
		$CI->db->select("dra_inst_registration.*,dra_inst_registration.id as institute_id,agency_center.location_name,dra_medium_master.medium_description,agency_batch.*,state_master.state_name,city_master.city_name,cs.city_name as cityname");    
		$CI->db->join('agency_center','agency_batch.center_id=agency_center.center_id','LEFT');
		$CI->db->join('city_master as cs','agency_center.location_name=cs.id','LEFT');
		$CI->db->join('city_master','agency_batch.city=city_master.id','LEFT');       
		$CI->db->join('state_master','state_master.state_code=agency_batch.state_code','LEFT');
		$CI->db->join('dra_inst_registration','agency_batch.agency_id=dra_inst_registration.id','LEFT');
		$CI->db->join('dra_medium_master','dra_medium_master.medium_code=agency_batch.training_medium','LEFT');       
		$CI->db->where('agency_batch.id = '.$batch_id);
		$CI->db->where('agency_center.center_display_status','1'); // to hide centers and batches.        
		
		$user_info = $CI->master_model->getRecords("agency_batch");
		
		$institute_name = $user_info[0]['inst_name'].' '.$user_info[0]['cityname'].' '.$user_info[0]['inst_head_name'].' '.$user_info[0]['inst_head_contact_no'].' '.$user_info[0]['batch_name'].' '.$user_info[0]['batch_from_date'].' '.$user_info[0]['batch_to_date'].' '.$user_info[0]['timing_from'].' '.$user_info[0]['timing_to'].' '.$user_info[0]['total_candidates'].' '.$user_info[0]['batch_code'];
		
		/* Code added By Manoj: 15 May 2019 */
		$batch_address = "";
	 	$batch_address = $user_info[0]['addressline1'].' '.$user_info[0]['addressline2'].' '.$user_info[0]['addressline3'].' '.$user_info[0]['addressline4'].' '.$user_info[0]['district'].' '.$user_info[0]['state_name'].' '.$user_info[0]['city_name'].' '.$user_info[0]['pincode'];
	 	/* Close Code added By Manoj: 15 May 2019 */
		
		$userfinalstrname = preg_replace('#[\s]+#', ' ', $institute_name);
		$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#INSITUTE_NAME#", "".$user_info[0]['inst_name']."", $newstring1 );
		$newstring3 = str_replace("#LOCATION_NAME#", "".$user_info[0]['cityname']."", $newstring2 );
		$newstring4 = str_replace("#CONTACT_PERSON#", "".$user_info[0]['contact_person_name']."", $newstring3 );
		
		/*if($user_info[0]['inst_head_contact_no']=='')
			{
			$user_info[0]['inst_head_contact_no']= '-';
			}
			else
			{
			$user_info[0]['inst_head_contact_no'];
		}*/
		
		if($user_info[0]['contact_person_phone']=='')
		{
			$user_info[0]['contact_person_phone']= '-';
		}
		else
		{
			$user_info[0]['contact_person_phone'];
		}
		
		// name_of_bank and remarks
		if($user_info[0]['name_of_bank']=='')
		{
			$name_of_bank =  '-';
		}
		else
		{
			$name_of_bank = $user_info[0]['name_of_bank'];
		}
		
		if($user_info[0]['remarks']=='')
		{
			$remarks =  '-';
		}
		else
		{
			$remarks = $user_info[0]['remarks'];
		}
		
		if($user_info[0]['faculty_name']=='')
		{
			$faculty_name =  '-';
		}
		elseif($user_info[0]['faculty_qualification']=='')
		{
			$faculty_name = $user_info[0]['faculty_name'];
			}else{
			$faculty_name = $user_info[0]['faculty_name'].' , '.$user_info[0]['faculty_qualification'];
		}
		
		if($user_info[0]['faculty_name2']=='')
		{
			$faculty_name2 =  '-';
		}
		elseif($user_info[0]['faculty_qualification2']=='')
		{
			$faculty_name2 = $user_info[0]['faculty_name2'];
			}else{
			$faculty_name2 = $user_info[0]['faculty_name2'].' , '.$user_info[0]['faculty_qualification2'];
		}
		
		$newstring5 = str_replace("#CONTACT_NUMBER#", "".$user_info[0]['contact_person_phone']."", $newstring4);
		$newstring6 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring5);
		$newstring7 = str_replace("#FROM_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_from_date']))."", $newstring6);
		$newstring8 = str_replace("#TO_DATE#", "".date('d-M-Y',strtotime($user_info[0]['batch_to_date']))."", $newstring7);
		$newstring9 = str_replace("#TIMING_FROM#", "".$user_info[0]['timing_from']."", $newstring8);
		$newstring10 = str_replace("#TIMING_TO#", "".$user_info[0]['timing_to']."", $newstring9);
		$newstring11 = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring10);		
		$newstring12 = str_replace("#BANK_NAME#", "".$name_of_bank."", $newstring11);
		$newstring13 = str_replace("#REMARK#", "".$remarks."", $newstring12);
		$newstring14 = str_replace("#ADDRESS#", "".$batch_address."", $newstring13);
		$newstring15 = str_replace("#FACULTY_DETAILS_1#", "".$faculty_name."", $newstring14);
		$newstring16 = str_replace("#FACULTY_DETAILS_2#", "".$faculty_name2."", $newstring15);  	
		$newstring17  = str_replace("#BATCH_CODE#", "".$user_info[0]['batch_code']."", $newstring16);
		
		$final_str  = str_replace("#CANDIDATES#", "".$user_info[0]['total_candidates']."", $newstring17);
		########## START : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		$online_user_details = '';
		if(isset($user_info[0]['batch_online_offline_flag']) && $user_info[0]['batch_online_offline_flag'] == 1) //IF BATCH IS ONLINE THEN SEND USER IDS AND PASSWORD WITH URL
		{
			$CI->db->where('agency_id = '.$user_info[0]['agency_id']);
			$CI->db->where('batch_id = '.$batch_id);
			$user_id_password_data = $CI->master_model->getRecords("agency_online_batch_user_details");
			if(count($user_id_password_data) > 0)
			{
				$online_user_details .='<p>Please check below login details and On-line training platform details</p>
				<table style=" font-family:Arial, Helvetica, sans-serif; font-size:14px;" width="50%" cellspacing="" cellpadding="" border="1">
				<thead>
				<tr>
				<th style="padding:5px 10px;">Login Id by sagar</th>
				<th style="padding:5px 10px;">Password</th>
				</tr>
				</thead>
				<tbody>';
				foreach($user_id_password_data as $Res)
				{
					$online_user_details .='
					<tr>
					<td style="padding:5px 10px;">'.$Res['login_id'].'</td>
					<td style="padding:5px 10px;">'.base64_decode($Res['password']).'</td>
					</tr>
					';
				}						
				$online_user_details .='
				</tbody>
				</table>';
				
				$online_user_details .='<p><strong>On-line training platform used : </strong>'.$user_info[0]['online_training_platform'].'</p>';
			}		 	
		}
		$final_str  = str_replace("#ONLINE_USER_DETAILS#", "".$online_user_details."", $final_str);
		########## END : CODE ADDED BY SAGAR ON 19-08-2020 ###################
		
		//$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.pdf';
		$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';
		
		$inspector_email_send = $inspector_email ;	
		
		//$info_arr1 = array('to'=>'sagar.matale@esds.co.in','from'=>'logs@iibf.esdsconnect.com', 'cc'=>' chaitali.jadhav@esds.co.in, logs@iibf.esdsconnect.com, bhushan.amrutkar@esds.co.in','subject'=>'DRA Inspection '.$user_info[0]['cityname'],'message'=>$final_str);      
		$info_arr1 = array('to'=>'sagar.matale@esds.co.in','subject'=>'DRA Inspection ('.$user_info[0]['cityname'].') ('.$user_info[0]['batch_code'].') ('.date('d-M-Y',strtotime($user_info[0]['batch_from_date'])).' TO '.date('d-M-Y',strtotime($user_info[0]['batch_to_date'])).')','message'=>$final_str);      
		print_r($info_arr1); 
		$attachpath = 'https://iibf.esdsconnect.com/uploads/iibfdra_inspection_report/INSPECTION_FORMAT.xlsx';
		$CI->Emailsending->mailsend_attch_cc($info_arr1,$attachpath);	
	}

	/*Applicant Add Mail */
	function applicant_save_mail($regid)
	{
		$CI = & get_instance();
		$emailerstr=$CI->master_model->getRecords('emailer',array('emailer_name'=>'applicant_save'));
	
		$query = "SELECT m.regid, m.training_id, concat(m.namesub, ' ', m.firstname, ' ', m.middlename, ' ', m.lastname) as name, m.dateofbirth, m.mobile_no, c.batch_code, c.batch_name, c.batch_from_date, c.batch_to_date, c.timing_from, a.institute_name FROM dra_members m LEFT JOIN agency_batch c ON m.batch_id = c.id LEFT JOIN dra_accerdited_master a ON a.dra_inst_registration_id = c.agency_id
			WHERE m.regid =".$regid;
			
		$result = $CI->db->query($query);  
		$user_info = $result->result_array();
		
		$to_mail = $user_info[0]['email'];
		
		$newstring1 = str_replace("#TRAINING_ID#", "".$user_info[0]['training_id']."",  $emailerstr[0]['emailer_text']);
		$newstring2 = str_replace("#BATCH_NAME#", "".$user_info[0]['batch_name']."", $newstring1 );
		$newstring3 = str_replace("#TRAINING_BATCH_NO#", "".$user_info[0]['batch_code']."", $newstring2 );
		$newstring4 = str_replace("#TRAINING_FROM_DATE#", "".$user_info[0]['batch_from_date']."", $newstring3 );
		$newstring5 = str_replace("#TRAINING_TO_DATE#", "".$user_info[0]['batch_to_date']."", $newstring4 );
		$newstring6 = str_replace("#TRAINING_TIME_FROM#", "".$user_info[0]['timing_from']."", $newstring5);
		$newstring7 = str_replace("#TRAINING_TIME_TO#", "".$user_info[0]['timing_to']."", $newstring6);
		$newstring8 = str_replace("#TRAINING_ID_NAME#", "".$user_info[0]['training_id']."", $newstring6);
		$final_str = str_replace("#TRAINING_INSTITUTE#", "".$user_info[0]['institute_name']."", $newstring8);
		
		$bcc = array('iibfdevp@esds.co.in');
		
		$info_arr = array('to'=>$to_mail,'from'=>'logs@iibf.esdsconnect.com','bcc'=>$bcc,'subject'=>'IIBF DRA/DRA-TC Training Batch Information.','message'=>$final_str);

		$CI->Emailsending->sendmail($info_arr);
		//$CI->Emailsending->mailsend($info_arr);
	}	
	
