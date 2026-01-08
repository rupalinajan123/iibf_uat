<?php
	/*
		* Controller Name	:	Cron File Generation
		* Created By		:	Bhushan
		* Created Date		:	12-09-2019
	*/
	// https://iibf.esdsconnect.com/admin/Memcron/update_Member_images
	
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Memcron_chaitali extends CI_Controller {
		
		public function __construct(){
			parent::__construct();
			
			$this->load->model('UserModel');
			$this->load->model('Master_model');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->helper('custom_contact_classes_invoice_helper');
			$this->load->helper('custom_admitcard_helper');
			
			$this->load->helper('bulk_invoice_helper');
			$this->load->helper('bulk_admitcard_helper');
			$this->load->helper('custom_invoice_helper');
			$this->load->helper('blended_invoice_custom_helper');
			
			error_reporting(E_ALL);
			ini_set('display_errors', 1);
		}
		
		
		
		/* Member Registration Cron */
		public function update_Member_images()
		{
			ini_set("memory_limit", "-1");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber");
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));	
			
			if(count($new_mem_reg))
			{
				foreach($new_mem_reg as $reg_data)
				{
					$extn = '.jpg';
					$member_no = $reg_data['regnumber'];
					
					/* Code for Photo */
					$photo_name = $reg_data['scannedphoto'];
					$photo = strpos($photo_name,'photo');
					if($photo == 8)
					{
						$photo_replace = str_replace($photo_name,'p_',$photo_name);
						$updated_photo = $photo_replace.$member_no.$extn;
						
						$update_data = array('scannedphoto' => $updated_photo);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Photo",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}
					
					/* Code for Sign */
					$sign_name = $reg_data['scannedsignaturephoto'];
					$sign = strpos($sign_name,'sign');
					if($sign == 8)
					{
						$sign_replace = str_replace($sign_name,'s_',$sign_name);
						$updated_sign = $sign_replace.$member_no.$extn;
						
						$update_data = array('scannedsignaturephoto' => $updated_sign);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Sign",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
						
						
					}
					
					/* Code for Idproof */
					$idproof_name = $reg_data['idproofphoto'];
					$idproof = strpos($idproof_name,'idproof');
					if($idproof == 8)
					{
						$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
						$updated_idproof = $idproof_replace.$member_no.$extn;
						
						$update_data = array('idproofphoto' => $updated_idproof);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Idproof",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
				}	
			}
		}
		
		/* Member Registration Cron */
		public function update_member_images_by_folder()
		{
			ini_set("memory_limit", "-1");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day")); 
			
			//$yesterday = '2020-08-16';
			$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'excode'=>991,'exam_period'=> 998));
			
			
			
			///$regnumber array(801441494,801441497,801441536,801441569,801441576,801441606,801441716,801441759,80144231);
			//$this->db->where_in('regnumber', $regnumber);  
			//$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			//  $new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0,'excode'=>991,'exam_period'=> 998));
			
			//print_r($new_mem_reg);die;
			
			if(count($new_mem_reg) > 0) 
			{
				foreach($new_mem_reg as $reg_data)
				{
					$regid = $reg_data['regid'];
					
					$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,' DATE(date)'=>$yesterday));
					
					if(COUNT($user_log) > 0)
					{ 
						
						$description = unserialize($user_log[0]['description']);
						$p_photo =  $description['scannedphoto'];
						$s_photo =  $description['scannedsignaturephoto'];
						$i_photo =  $description['idproofphoto'];
						
						if($p_photo != '' && $p_photo != 'p_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/photograph/".$p_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/photograph/".$p_photo,"./uploads/photograph/p_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder Photo rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
									$upd_files  = array(
									'scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
									'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
									'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg"
									);
									$log_title ="CSC PICS Update thro cron :".$regid;
									$log_message = serialize($upd_files);
									$rId = $regid;
									$regNo = $reg_data['regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								
							}
							
						}
						
						if($s_photo != '' && $s_photo != 's_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/scansignature/".$s_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/scansignature/".$s_photo,"./uploads/scansignature/s_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder scansignature name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
							}
							
						}
						
						if($i_photo != '' && $i_photo != 'pr_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/idproof/".$i_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/idproof/".$i_photo,"./uploads/idproof/pr_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder idproof name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
							}
							
						}
						
						
					}
					
					
					$extn = '.jpg';
					$member_no = $reg_data['regnumber'];
					
					/* Code for Photo */
					$photo_name = $reg_data['scannedphoto'];
					$photo = strpos($photo_name,'photo');
					if($photo == 8)
					{
						$photo_replace = str_replace($photo_name,'p_',$photo_name);
						$updated_photo = $photo_replace.$member_no.$extn;
						
						$update_data = array('scannedphoto' => $updated_photo);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Photo",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}
					
					/* Code for Sign */
					$sign_name = $reg_data['scannedsignaturephoto'];
					$sign = strpos($sign_name,'sign');
					if($sign == 8)
					{
						$sign_replace = str_replace($sign_name,'s_',$sign_name);
						$updated_sign = $sign_replace.$member_no.$extn;
						
						$update_data = array('scannedsignaturephoto' => $updated_sign);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Sign",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					/* Code for Idproof */
					$idproof_name = $reg_data['idproofphoto'];
					$idproof = strpos($idproof_name,'idproof');
					if($idproof == 8)
					{
						$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
						$updated_idproof = $idproof_replace.$member_no.$extn;
						
						$update_data = array('idproofphoto' => $updated_idproof);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Idproof",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					$this->db->distinct('member_no');
					//$this->db->select('member_no');
					$this->db->where('member_no', $member_no); 
					$query = $this->master_model->getRecords('member_images_update',array(' DATE(update_date)'=>$yesterday));
					
					//echo $this->db->last_query(); die;
					if(COUNT($query) > 0 ){
						$exam_code = 991;     
						$exam_period = 998;    
						
						
						
						genarate_admitcard_custom_new($member_no,$exam_code,$exam_period);  
						
						
						$this->db->distinct('mem_mem_no');   
						$this->db->where('remark',1);
						$this->db->where('exm_prd',$exam_period);
						$this->db->where('admitcard_image !=','');
						$this->db->where_in('mem_mem_no',$member_no);
						$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
						
						foreach($sql as $rec){ 
							
							$this->db->where('exam_code',$rec['exm_cd']);
							$exam_name = $this->master_model->getRecords('exam_master','','description');
							
							$final_str = 'Hello Sir/Madam <br/><br/>';
							
							$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
							$final_str.= '<br/><br/>';
							$final_str.= 'Regards,';
							$final_str.= '<br/>';
							$final_str.= 'IIBF TEAM'; 
							
							$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
							
							$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
							$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'Swati.Watpade@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
							); 
							$files=array($attachpath);
							if(file_exists($attachpath)){
								$this->Emailsending->mailsend_attch($info_arr,$files);
								}else{
								
							}
						}
					}
					
					
				}	
			}
		}
		
		
		/* Member Registration Cron */
		public function update_member_images_by_folder_csc()
		{
			ini_set("memory_limit", "-1");
			
			$current_date = date("Y-m-d h:i:s");
			
			$date = date('Y-m-d h:i:s', strtotime("-30 minutes", strtotime ($current_date)));
			
			$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			$this->db->where('a.createdon >=', $date);
			$this->db->where('a.createdon <=', $current_date);
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0,'excode'=>991));
			
			if(count($new_mem_reg) > 0) 
			{
				foreach($new_mem_reg as $reg_data)
				{
					$regid = $reg_data['regid'];
					
					$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid,'DATE(date)'=>$current_date));
					
					if(COUNT($user_log) > 0)
					{ 
						
						$description = unserialize($user_log[0]['description']);
						$p_photo =  $description['scannedphoto'];
						$s_photo =  $description['scannedsignaturephoto'];
						$i_photo =  $description['idproofphoto'];
						
						if($p_photo != '' && $p_photo != 'p_'.$reg_data['regnumber'].'.jpg')
						{
							$attachpath = "uploads/photograph/".$p_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/photograph/".$p_photo,"./uploads/photograph/p_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder Photo rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
									$upd_files  = array(
									'scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
									'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
									'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg"
									);
									$log_title ="CSC PICS Update thro cron :".$regid;
									$log_message = serialize($upd_files);
									$rId = $regid;
									$regNo = $reg_data['regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								
							}
							
						}
						
						if($s_photo != '' && $s_photo != 's_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/scansignature/".$s_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/scansignature/".$s_photo,"./uploads/scansignature/s_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder scansignature name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
							}
							
						}
						
						if($i_photo != '' && $i_photo != 'pr_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/idproof/".$i_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/idproof/".$i_photo,"./uploads/idproof/pr_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder idproof name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
							}
							
						}
						
						
					}
					
					
					$extn = '.jpg';
					$member_no = $reg_data['regnumber'];
					
					/* Code for Photo */
					$photo_name = $reg_data['scannedphoto'];
					$photo = strpos($photo_name,'photo');
					if($photo == 8)
					{
						$photo_replace = str_replace($photo_name,'p_',$photo_name);
						$updated_photo = $photo_replace.$member_no.$extn;
						
						$update_data = array('scannedphoto' => $updated_photo);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Photo",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}
					
					/* Code for Sign */
					$sign_name = $reg_data['scannedsignaturephoto'];
					$sign = strpos($sign_name,'sign');
					if($sign == 8)
					{
						$sign_replace = str_replace($sign_name,'s_',$sign_name);
						$updated_sign = $sign_replace.$member_no.$extn;
						
						$update_data = array('scannedsignaturephoto' => $updated_sign);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Sign",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					/* Code for Idproof */
					$idproof_name = $reg_data['idproofphoto'];
					$idproof = strpos($idproof_name,'idproof');
					if($idproof == 8)
					{
						$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
						$updated_idproof = $idproof_replace.$member_no.$extn;
						
						$update_data = array('idproofphoto' => $updated_idproof);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Idproof",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					$this->db->distinct('member_no');
					$this->db->where('member_no', $member_no); 
					$this->db->where('DATE(update_date) >=', $date);
					$this->db->where('DATE(update_date) <=', $current_date);
					$query = $this->master_model->getRecords('member_images_update');
					
					// ,array(' DATE(update_date)'=>$yesterday)
					
					//echo $this->db->last_query(); die;
					
					if(COUNT($query) > 0 ){
						$exam_code = 991;     
						$exam_period = 998;    
						
						
						
						genarate_admitcard_custom_new($member_no,$exam_code,$exam_period);  
						
						
						$this->db->distinct('mem_mem_no');   
						$this->db->where('remark',1);
						$this->db->where('exm_prd',$exam_period);
						$this->db->where('admitcard_image !=','');
						$this->db->where_in('mem_mem_no',$member_no);
						$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
						
						foreach($sql as $rec){ 
							
							$this->db->where('exam_code',$rec['exm_cd']);
							$exam_name = $this->master_model->getRecords('exam_master','','description');
							
							$final_str = 'Hello Sir/Madam <br/><br/>';
							
							$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
							$final_str.= '<br/><br/>';
							$final_str.= 'Regards,';
							$final_str.= '<br/>';
							$final_str.= 'IIBF TEAM'; 
							
							$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
							
							$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
							$info_arr=array('to'=>$email[0]['email'],
							//'to'=>'Swati.Watpade@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'Revised Admit Letter',
							'message'=>$final_str
							); 
							$files=array($attachpath);
							if(file_exists($attachpath)){
								$this->Emailsending->mailsend_attch($info_arr,$files);
								}else{
								
							}
						}
					}
					
					
				}	
			}
		}
		
		
		/* Member Registration Cron */
		public function update_member_images_by_folder_registration()
		{
			ini_set("memory_limit", "-1");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			  //$yesterday = '2021-02-26';
			
			//$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			//$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' //DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0));
			
			//print_r($new_mem_reg);die;
			
			$regnumber =array(801641483,801252725,801567822,801641537,801641282,801557759,801426905,801631187,801641061,801641281,801641327,801469587,801641320,801536451,801640936,801641166,801629370,801629060,801628988,801641551,801641538,801641554,801640883,801459402,801641546,801641540,801641548,801463403,801641348,801641323,801223330,801641347,801641536,801013994,801641322,801641438,801641342);
			$this->db->where_in('regnumber', $regnumber);  
			$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
			
			if(count($new_mem_reg) > 0) 
			{
				foreach($new_mem_reg as $reg_data)
				{
					$regid = $reg_data['regid']; 
					
					$user_log = $this->Master_model->getRecords('userlogs a',array('regid'=>$regid));
					//echo $this->db->last_query(); die;
					if(COUNT($user_log) > 0)
					{ 
						//echo $this->db->last_query(); die;
						//echo $regid = $reg_data['regid']; die;
						$description = unserialize($user_log[0]['description']);
						// if (array_key_exists("scannedphoto", $description)) {
						$p_photo =  $description['scannedphoto'];
						$s_photo =  $description['scannedsignaturephoto'];
						$i_photo =  $description['idproofphoto'];
						// echo $reg_data['regnumber'] ; die;
						if($p_photo != '' && $p_photo != 'p_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/photograph/".$p_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/photograph/".$p_photo,"./uploads/photograph/p_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder Photo name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
									
									$update_data = array('scannedphoto' => "p_".$reg_data['regnumber'].".jpg");
									$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
									
									$upd_files  = array(
									'scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
									'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
									'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg"
									);
									$log_title =" PICS rename thro cron :".$regid;
									$log_message = serialize($upd_files);
									$rId = $regid;
									$regNo = $reg_data['regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								
							}
							
						}
						
						if($s_photo != '' && $s_photo != 's_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/scansignature/".$s_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/scansignature/".$s_photo,"./uploads/scansignature/s_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder scansignature name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
								$update_data = array('scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg");
								$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
							}
							
						}
						
						if($i_photo != '' && $i_photo != 'pr_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/idproof/".$i_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/idproof/".$i_photo,"./uploads/idproof/pr_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder idproof name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								$update_data = array('idproofphoto' => "pr_".$reg_data['regnumber'].".jpg");
								$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
							}
							
						}
						
						
					}
					//}
					$update_data = array('scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
					'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
					'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg");
					$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
					
					
					$extn = '.jpg';
					$member_no = $reg_data['regnumber'];
					
					/* Code for Photo */
					$photo_name = $reg_data['scannedphoto'];
					$photo = strpos($photo_name,'photo');
					if($photo == 8)
					{
						$photo_replace = str_replace($photo_name,'p_',$photo_name);
						$updated_photo = $photo_replace.$member_no.$extn;
						
						$update_data = array('scannedphoto' => $updated_photo);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Photo",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}
					
					/* Code for Sign */
					$sign_name = $reg_data['scannedsignaturephoto'];
					$sign = strpos($sign_name,'sign');
					if($sign == 8)
					{
						$sign_replace = str_replace($sign_name,'s_',$sign_name);
						$updated_sign = $sign_replace.$member_no.$extn;
						
						$update_data = array('scannedsignaturephoto' => $updated_sign);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Sign",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					/* Code for Idproof */
					$idproof_name = $reg_data['idproofphoto'];
					$idproof = strpos($idproof_name,'idproof');
					if($idproof == 8)
					{
						$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
						$updated_idproof = $idproof_replace.$member_no.$extn;
						
						$update_data = array('idproofphoto' => $updated_idproof);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Idproof",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					$this->db->distinct('member_no');
					//$this->db->select('member_no');
					$this->db->where('member_no', $member_no); 
					$query = $this->master_model->getRecords('member_images_update',array(' DATE(update_date)'=>$yesterday));
					
					//echo $this->db->last_query(); die;
					if(COUNT($query) > 0 ){
						
						$this->db->order_by("id", "desc");
						$this->db->limit(1); 
						$exam_count = $this->master_model->getRecords('member_exam',array('regnumber'=>$member_no));
						if(COUNT($exam_count) > 0 ){
							$exam_code = $exam_count[0]['exam_code'];     
							$exam_period = $exam_count[0]['exam_period'];    
							
							
							
							genarate_admitcard_custom_new($member_no,$exam_code,$exam_period);  
							
							
							$this->db->distinct('mem_mem_no');   
							$this->db->where('remark',1);
							$this->db->where('exm_prd',$exam_period);
							$this->db->where('admitcard_image !=','');
							$this->db->where_in('mem_mem_no',$member_no);
							$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
							
							foreach($sql as $rec){ 
								
								$this->db->where('exam_code',$rec['exm_cd']);
								$exam_name = $this->master_model->getRecords('exam_master','','description');
								
								$final_str = 'Hello Sir/Madam <br/><br/>';
								
								$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
								$final_str.= '<br/><br/>';
								$final_str.= 'Regards,';
								$final_str.= '<br/>';
								$final_str.= 'IIBF TEAM'; 
								
								$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
								
								$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
								$info_arr=array('to'=>$email[0]['email'],
								//'to'=>'Swati.Watpade@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Revised Admit Letter',
								'message'=>$final_str
								); 
								$files=array($attachpath);
								if(file_exists($attachpath)){
									$this->Emailsending->mailsend_attch($info_arr,$files);
									}else{
									
								}
							}
						}
					}
					
					
				}	
			}
		}
		
		public function send_admitcrads(){
			
			ini_set("memory_limit", "-1");
			
			$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$this->db->select("a.regnumber,a.regid,a.email,a.mobile");
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array(' DATE(createdon)'=>$yesterday,'isactive'=>'1','isdeleted'=>0,'excode'=>991,'exam_period'=> 998));
			if(COUNT($new_mem_reg) > 0){
				foreach ($new_mem_reg as $new_mem_reg) {
					
					$this->db->distinct('mem_mem_no');   
					$this->db->where('remark',1);
					$this->db->where('exm_prd',998);
					$this->db->where('admitcard_image !=','');
					$this->db->where_in('mem_mem_no',$new_mem_reg['regnumber']);
					$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
					
					foreach($sql as $rec){ 
						
						$this->db->where('exam_code',$rec['exm_cd']);
						$exam_name = $this->master_model->getRecords('exam_master','','description');
						
						$final_str = 'Hello Sir/Madam <br/><br/>';
						
						$final_str.= 'Please check your new attached admit card letter for '.$exam_name[0]['description'].' examination';   
						$final_str.= '<br/><br/>';
						$final_str.= 'Regards,';
						$final_str.= '<br/>';
						$final_str.= 'IIBF TEAM'; 
						
						$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
						
						
						$info_arr=array('to'=>$new_mem_reg['email'],
						//'to'=>'Swati.Watpade@esds.co.in',
						'from'=>'noreply@iibf.org.in',
						'subject'=>'Exam Admit Card Letter',
						'message'=>$final_str
						); 
						$files=array($attachpath);
						if(file_exists($attachpath)){
							
							//$this->Emailsending->mailsend_attch($info_arr,$files);
							if($this->Emailsending->mailsend_attch($info_arr,$files)){
								$insert_data  = array(
								'member_no' => $new_mem_reg['regnumber'],
								'email' => '1',
								'update_date' => date('Y-m-d H:i:s')
								);
								$this->master_model->insertRecord('daily_csc_admitcard', $insert_data);
								
							}
							
							}else{
							
						}
					}
					
					
				}
			}    
		}
		
		public function update_member_images_uploads_folder()
		{
			ini_set("memory_limit", "-1");
			
			//$yesterday = date('Y-m-d', strtotime("- 1 day"));
			
			$yesterday = '2020-03-01';
			
			
			
			$regnumber =array(510460642);
			$this->db->where_in('regnumber', $regnumber);  
			$this->db->select("a.scannedphoto,a.scannedsignaturephoto,a.idproofphoto,a.regnumber,a.regid");
			$new_mem_reg = $this->Master_model->getRecords('member_registration a',array('isactive'=>'1','isdeleted'=>0));
			if(count($new_mem_reg) > 0) 
			{
				foreach($new_mem_reg as $reg_data)
				{
					$regid = $reg_data['regid']; 
					
					$user_log = $this->Master_model->getRecords('userlogs a',array('id'=>5435310));
					
					if(COUNT($user_log) > 0)
					{ 
						//echo $this->db->last_query(); die;
						//echo $regid = $reg_data['regid']; die;
						$description = unserialize($user_log[0]['description']);
						// if (array_key_exists("scannedphoto", $description)) {
						$p_photo =  $description['scannedphoto'];
						$s_photo =  $description['scannedsignaturephoto'];
						$i_photo =  $description['idproofphoto'];
						// echo $reg_data['regnumber'] ; die;
						if($p_photo != '' && $p_photo != 'p_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/photograph/".$p_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/photograph/".$p_photo,"./uploads/photograph/p_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder Photo name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
									
									$update_data = array('scannedphoto' => "p_".$reg_data['regnumber'].".jpg");
									$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
									
									$upd_files  = array(
									'scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
									'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
									'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg"
									);
									$log_title =" PICS rename thro cron :".$regid;
									$log_message = serialize($upd_files);
									$rId = $regid;
									$regNo = $reg_data['regnumber'];
									storedUserActivity($log_title, $log_message, $rId, $regNo);	
								}
								
							}
							
						}
						
						if($s_photo != '' && $s_photo != 's_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/scansignature/".$s_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/scansignature/".$s_photo,"./uploads/scansignature/s_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder scansignature name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								
								$update_data = array('scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg");
								$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
							}
							
						}
						
						if($i_photo != '' && $i_photo != 'pr_'.$reg_data['regnumber'].'.jpg'){
							
							$attachpath = "uploads/idproof/".$i_photo;
							if(file_exists($attachpath)){
								if(@ rename("./uploads/idproof/".$i_photo,"./uploads/idproof/pr_".$reg_data['regnumber'].".jpg")){
									$insert_data  = array(
									'member_no' => $reg_data['regnumber'],
									'update_value' => "uploads folder idproof name rename",
									'update_date' => date('Y-m-d H:i:s')
									);
									$this->master_model->insertRecord('member_images_update', $insert_data);
								}
								$update_data = array('idproofphoto' => "pr_".$reg_data['regnumber'].".jpg");
								$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
							}
							
						}
						
						
					}
					//}
					$update_data = array('scannedphoto' => "p_".$reg_data['regnumber'].".jpg",
					'scannedsignaturephoto' => "s_".$reg_data['regnumber'].".jpg",
					'idproofphoto' => "pr_".$reg_data['regnumber'].".jpg");
					$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $reg_data['regnumber']));
					
					
					$extn = '.jpg';
					$member_no = $reg_data['regnumber'];
					
					/* Code for Photo */
					$photo_name = $reg_data['scannedphoto'];
					$photo = strpos($photo_name,'photo');
					if($photo == 8)
					{
						$photo_replace = str_replace($photo_name,'p_',$photo_name);
						$updated_photo = $photo_replace.$member_no.$extn;
						
						$update_data = array('scannedphoto' => $updated_photo);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Photo",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);
					}
					
					/* Code for Sign */
					$sign_name = $reg_data['scannedsignaturephoto'];
					$sign = strpos($sign_name,'sign');
					if($sign == 8)
					{
						$sign_replace = str_replace($sign_name,'s_',$sign_name);
						$updated_sign = $sign_replace.$member_no.$extn;
						
						$update_data = array('scannedsignaturephoto' => $updated_sign);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Sign",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					/* Code for Idproof */
					$idproof_name = $reg_data['idproofphoto'];
					$idproof = strpos($idproof_name,'idproof');
					if($idproof == 8)
					{
						$idproof_replace = str_replace($idproof_name,'pr_',$idproof_name);
						$updated_idproof = $idproof_replace.$member_no.$extn;
						
						$update_data = array('idproofphoto' => $updated_idproof);
						$this->master_model->updateRecord('member_registration', $update_data, array('regnumber' => $member_no));
						
						$insert_data  = array(
						'member_no' => $member_no,
						'update_value' => "Idproof",
						'update_date' => date('Y-m-d H:i:s')
						);
						$this->master_model->insertRecord('member_images_update', $insert_data);	
					}
					
					$this->db->distinct('member_no');
					//$this->db->select('member_no');
					$this->db->where('member_no', $member_no); 
					$query = $this->master_model->getRecords('member_images_update',array(' DATE(update_date)'=>$yesterday));
					
					//echo $this->db->last_query(); die;
					if(COUNT($query) > 0 ){
						
						$this->db->order_by("id", "desc");
						$this->db->limit(1); 
						$exam_count = $this->master_model->getRecords('member_exam',array('regnumber'=>$member_no));
						if(COUNT($exam_count) > 0 ){
							$exam_code = $exam_count[0]['exam_code'];     
							$exam_period = $exam_count[0]['exam_period'];    
							
							
							
							genarate_admitcard_custom_new($member_no,$exam_code,$exam_period);  
							
							
							$this->db->distinct('mem_mem_no');   
							$this->db->where('remark',1);
							$this->db->where('exm_prd',$exam_period);
							$this->db->where('admitcard_image !=','');
							$this->db->where_in('mem_mem_no',$member_no);
							$sql = $this->master_model->getRecords('admit_card_details','','mem_mem_no,admitcard_image,exm_cd'); 
							
							foreach($sql as $rec){ 
								
								$this->db->where('exam_code',$rec['exm_cd']);
								$exam_name = $this->master_model->getRecords('exam_master','','description');
								
								$final_str = 'Hello Sir/Madam <br/><br/>';
								
								$final_str.= 'Please check your new attached revised admit card letter for '.$exam_name[0]['description'].' examination';   
								$final_str.= '<br/><br/>';
								$final_str.= 'Regards,';
								$final_str.= '<br/>';
								$final_str.= 'IIBF TEAM'; 
								
								$attachpath = "uploads/admitcardpdf/".$rec['admitcard_image'];  
								
								$email = $this->master_model->getRecords('member_registration',array('regnumber'=>$rec['mem_mem_no'],'isactive'=>'1'),'email,mobile');   
								$info_arr=array('to'=>$email[0]['email'],
								//'to'=>'Swati.Watpade@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Revised Admit Letter',
								'message'=>$final_str
								); 
								$files=array($attachpath);
								if(file_exists($attachpath)){
									$this->Emailsending->mailsend_attch($info_arr,$files);
									}else{
									
								}
							}
						}
					}
					
					
				}	
			}
		}
		
	}	