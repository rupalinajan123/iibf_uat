<?php	
  /********************************************************************
  * Description: Controller for Case Writing Competition 
  * Created BY: Anil S
  * Created On: 09-09-2025
  * Update By: Anil S
  * Updated on: 09-09-2025
	********************************************************************/
  
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Registration extends CI_Controller 
	{	
		public function __construct()
		{
			parent::__construct();
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->helper('general_helper');
			$this->load->model('master_model');		
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			$this->load->model('chk_session');
			$this->load->helper('cookie');  
			$this->chk_session->Check_mult_session();
			/*echo "<h4>Sorry for the inconvenience, we performing some maintenance at the moment</h4>";
			exit; */  
			header("Access-Control-Allow-Origin: *");
		} 
		
		public function demo() 
		{
			$data['error']='';
			$this->load->view('case_writing_competition/registration_demo',$data);
		}
		public function index() 
		{
      	$data['error']='';
      	
      	$this->remove_old_dir();
			  
			  $data['case_study_user_info'] = $case_study_user_info = $this->session->userdata['case_study_user_info'];
			  
        if(isset($_POST) && count($_POST) > 0)
        {  
          //$this->form_validation->set_rules('aadhar_no','Aadhar Card No','trim|xss_clean|numeric|min_length[12]|max_length[12]');
          /* $this->form_validation->set_rules('val1', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val2', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val3', 'Value', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s')); */

          //$this->form_validation->set_rules('captcha_code', 'Security Code', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s'));
          
          $this->form_validation->set_rules('case_study_title','Name','trim|required|max_length[200]');
          $this->form_validation->set_rules('case_study_area','Name','trim|required|max_length[200]');
          $this->form_validation->set_rules('case_study_level_id','Name','trim|required');
          $this->form_validation->set_rules('case_study_level_desc_id','Name','trim|required');
          $this->form_validation->set_rules('name_of_author','Name','trim|required|max_length[200]');
          $this->form_validation->set_rules('designation','Designation','trim|required|max_length[200]');
          $this->form_validation->set_rules('employer','Employer','trim|required|max_length[200]');
          $this->form_validation->set_rules('mobile_no','Mobile No.','required|max_length[10]|min_length[10]');
					$this->form_validation->set_rules('email_id','Email ID','valid_email|required|trim');
          $this->form_validation->set_rules('qualifications','Qualifications','trim|required|max_length[200]');
					$this->form_validation->set_rules('other_info','other_info','trim|max_length[300]');  

					//$this->form_validation->set_rules('place_name','Place','trim|required|max_length[200]');

					if(isset($_POST["upload_case_study_doc_file_exist"]) && $_POST["upload_case_study_doc_file_exist"] != ""){

					}else{
						$this->form_validation->set_rules('upload_case_study_doc','Upload Pan No.','required');
					}

					if(isset($_POST["upload_signature_file_exist"]) && $_POST["upload_signature_file_exist"] != ""){

					}else{
						$this->form_validation->set_rules('upload_signature','Upload Pan No.','required');
					}
					
          
					
					//$this->form_validation->set_rules('declaration','Declaration','required');
					//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
					//$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
					//$this->form_validation->set_rules('phone','Phone No','max_length[8]');
				
				if($this->form_validation->run()==TRUE)
				{
					/*echo "<pre>";
					print_r($_POST);die;*/
					/*$email_id = strtoupper($this->input->post('email_id')); 
					$user_info = $this->master_model->getRecords('case_study_comp_registration',array('email_id'=>strtoupper($this->input->post('email_id')), 'is_active'=>1),'id'); 
          if(count($user_info) > 0)
          {
            $this->session->set_flashdata('error',"Your Email Id already register!..");
						redirect(base_url().'case_writing_competition/registration');
          }*/ 
					
					$img_unique_name = date("YmdHis") . "_" . rand(0, 999);  

					$image_size_error = 0;
					$image_size_error_message = array();
					$errorUploadType = '';
					$upload_case_study_doc = $upload_signature = '';

					if(isset($_POST["upload_case_study_doc_file_exist"]) && $_POST["upload_case_study_doc_file_exist"] != "" && empty($_FILES['upload_case_study_doc']['name'])){
          	
          	//$upload_case_study_doc      = $_POST["upload_case_study_doc_file_exist"];

          	$case_study_doc_path = FCPATH . 'uploads/case_study_comp_registration/'.date("Ymd").'/' . $_POST["upload_case_study_doc_file_exist"];
          	if(file_exists($case_study_doc_path)){
          		$upload_case_study_doc      = $_POST["upload_case_study_doc_file_exist"];
          	} 

          }
          else if (isset($_FILES['upload_case_study_doc']['name']) && !empty($_FILES['upload_case_study_doc']['name']))
          {
          	//echo 'if'; 

               $arr_extension = array('pdf','doc','docx');  
               $ext1 = explode('.',$_FILES['upload_case_study_doc']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .pdf, .doc, .docx extension files.');
                redirect(base_url().'case_writing_competition/registration');
              } 
              //$file_name_to_dispaly =  'case_study_'.rand(000,999).round(microtime(true)).'.'.$ext;
              $file_name_to_dispaly =  'case_study_'.$img_unique_name.'.'.$ext;
 							
 							// Generate folder name based on current date
							$created_folder = date('Ymd');
							// Full path to the folder
							$upload_path = FCPATH . 'uploads/case_study_comp_registration/' . $created_folder;

							// Check if folder exists, if not create it
							if (!is_dir($upload_path)) {
							    mkdir($upload_path, 0755, true); // recursive mkdir with proper permissions
							}

							// Now use this folder in CI upload config
							$config['upload_path']   = 'uploads/case_study_comp_registration/' . $created_folder;

              //$config['upload_path'] = 'uploads/case_study_comp_registration/'; 
              /*$config['allowed_types'] = 'pdf|jpg|jpeg|png';
              $config['max_size']      = '200';*/
              $config['allowed_types'] = 'pdf|doc|docx';
							$config['max_size']      = 2048; // size in KB (2 MB)
              $config['file_name']     =  $file_name_to_dispaly;

              $this->load->library('upload', $config); 
              $this->upload->initialize($config); 

              if($this->upload->do_upload('upload_case_study_doc')){ 
                  // Uploaded file data 
                  $fileData = $this->upload->data(); 
                  //$uploadData['file_name'] = $fileData['file_name']; 
                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                  $uploaded_id = 1;
                  $upload_case_study_doc      = $file_name_to_dispaly;
               
              }else{ 
                  $this->session->set_flashdata('error',$this->upload->display_errors());
									redirect(base_url().'case_writing_competition/registration');
                  $errorUploadType .= $_FILES['upload_case_study_doc']['name'].' | ';  
                  $uploaded_id = 0;
              } 
          }

          if(isset($_POST["upload_signature_file_exist"]) && $_POST["upload_signature_file_exist"] != "" && empty($_FILES['upload_signature']['name'])){

          	$signature_path = FCPATH . 'uploads/case_study_comp_registration/'.date("Ymd").'/' . $_POST["upload_signature_file_exist"];
          	if(file_exists($signature_path)){
          		$upload_signature      = $_POST["upload_signature_file_exist"];
          	} 
          	
          }
          else if (isset($_FILES['upload_signature']['name']))
          { 
          	  if (!empty($_FILES['upload_signature']['name'])){ 
	             
	             $arr_extension = array('pdf','jpg','jpeg','png');  
               $ext1 = explode('.',$_FILES['upload_signature']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .jpg, .jpeg, .png extension files.');
                redirect(base_url().'case_writing_competition/registration');
              } 
              $file_name_to_dispaly =  'sign_'.$img_unique_name.'.'.$ext;
 							
 							// Generate folder name based on current date
							$created_folder = date('Ymd');
							// Full path to the folder
							$upload_path = FCPATH . 'uploads/case_study_comp_registration/' . $created_folder;

							// Check if folder exists, if not create it
							if (!is_dir($upload_path)) {
							    mkdir($upload_path, 0755, true); // recursive mkdir with proper permissions
							}

							// Now use this folder in CI upload config
							$config['upload_path']   = 'uploads/case_study_comp_registration/' . $created_folder;

              //$config['upload_path'] = 'uploads/case_study_comp_registration/'; 
              $config['allowed_types'] = 'jpg|jpeg|png';
              $config['max_size']      = '200';
              $config['file_name']     =  $file_name_to_dispaly;

	              $this->load->library('upload', $config); 
	              $this->upload->initialize($config); 

	              if($this->upload->do_upload('upload_signature')){ 
	                  // Uploaded file data 
	                  $fileData = $this->upload->data(); 
	                  //$uploadData['file_name'] = $fileData['file_name']; 
	                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
	                  $uploaded_id = 1;
	                  $upload_signature      = $file_name_to_dispaly;
	               
	              }else{ 
	                  $this->session->set_flashdata('error',$this->upload->display_errors());
										//redirect(base_url().'case_writing_competition/registration');
	                  $errorUploadType .= $_FILES['upload_signature']['name'].' | ';  
	                  $uploaded_id = 0;
	              }
              } 
          }
 
  					  
					$case_study_registration_data = array(	
					'case_study_title' => ($this->input->post('case_study_title')),
					'case_study_area'		=>($this->input->post('case_study_area')),
					'case_study_level_id'		=>($this->input->post('case_study_level_id')),
					'case_study_level_desc_id'		=>($this->input->post('case_study_level_desc_id')),
					'name_of_author'		=>$this->input->post('name_of_author'),
					'designation'		=> ($this->input->post('designation')),
					'employer'		=> ($this->input->post('employer')),
					'mobile_no'				=>$this->input->post('mobile_no'),
					'email_id'				=>($this->input->post('email_id')),
					'qualifications'				=>$this->input->post('qualifications'),
					'other_info'				=>$this->input->post('other_info'),
					//'place_name'				=>($this->input->post('place_name')),
					'submit_date'			=> date("Y-m-d"),
					'upload_case_study_doc'				=> $upload_case_study_doc,
					'upload_signature'				=> $upload_signature
					);
					//print_r($case_study_registration_data);die;
					
					 
					$this->session->set_userdata('case_study_user_info', $case_study_registration_data);
          redirect(base_url().'case_writing_competition/registration/preview');

          /*$insert_id = $this->master_model->insertRecord('case_study_comp_registration', $case_study_registration_data, true); 

					if($insert_id)
					{ 
						//log_dra_user($module='Case Study Writing Competition Registration', $insert_id, $activity = 'Add',$log_title = "Case Study Writing Competition Registration Successful", $log_message = serialize($case_study_registration_data), $flag='Success');

						$data_log['Module'] = 'Case Study Writing Competition Registration';
						$data_log['prim_key'] = $insert_id;
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Case Study Writing Competition Registration';
						$data_log['description'] = serialize($case_study_registration_data);
						$data_log['flag'] = 'Success';  
						$data_log['userid'] = $insert_id; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('case_study_comp_registration_logs', $data_log);

						$this->session->set_flashdata('success','You are Data Submitted Successfully!..');
						redirect(base_url().'case_writing_competition/registration');
						//redirect(base_url().$_SESSION['reffer']);
					}
					else
					{ 
						$data_log['Module'] = 'Case Study Writing Competition Registration';
						$data_log['prim_key'] = '';
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Case Study Writing Competition Registration';
						$data_log['description'] = serialize($case_study_registration_data);
						$data_log['flag'] = 'Error';  
						$data_log['userid'] = ''; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('case_study_comp_registration_logs', $data_log);

						$this->session->set_flashdata('error','Error occured during Registration');
						redirect(base_url().'case_writing_competition/registration');
					}*/
					
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}

           
        }
        
				$this->load->model('Captcha_model');
				$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('CASE_WRITING_COMPETITION');
				$data['case_study_comp_level'] = $this->master_model->getRecords('case_study_comp_level', array('is_active' => '1'));
        $this->load->view('case_writing_competition/registration',$data);
      
		}

		public function preview()
		{

				$data['error']='';
				$data['case_study_user_info'] = $case_study_user_info = $this->session->userdata['case_study_user_info'];

				if(isset($_POST) && count($_POST) > 0)
        {   
          $this->form_validation->set_rules('place_name','Place','trim|required|max_length[200]'); 
					$this->form_validation->set_rules('declaration','Declaration','required');
					//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt'); 
				
				if($this->form_validation->run()==TRUE)
				{
					/*echo "<pre>";
					print_r($_POST);die;*/ 

					$case_study_registration_data = array(	
					'case_study_title' => ($case_study_user_info['case_study_title']),
					'case_study_area'		=>($case_study_user_info['case_study_area']),
					'case_study_level_id'		=>($case_study_user_info['case_study_level_id']),
					'case_study_level_desc_id'		=>($case_study_user_info['case_study_level_desc_id']),
					'name_of_author'		=>$case_study_user_info['name_of_author'],
					'designation'		=> ($case_study_user_info['designation']),
					'employer'		=> ($case_study_user_info['employer']),
					'mobile_no'				=>$case_study_user_info['mobile_no'],
					'email_id'				=>($case_study_user_info['email_id']),
					'qualifications'				=>$case_study_user_info['qualifications'],
					'other_info'				=>$case_study_user_info['other_info'],
					'place_name'				=>($this->input->post('place_name')),
					'submit_date'			=> date("Y-m-d"),
					'upload_case_study_doc'				=> $case_study_user_info['upload_case_study_doc'],
					'upload_signature'				=> $case_study_user_info['upload_signature']
					);
					//print_r($case_study_registration_data);die;
					
					 
					//$this->session->set_userdata('case_study_user_info', $case_study_registration_data);
          //redirect(base_url().'case_writing_competition/preview');

          $insert_id = $this->master_model->insertRecord('case_study_comp_registration', $case_study_registration_data, true); 

					if($insert_id)
					{  
						$application_no = date("Y")."-".$insert_id;
						$update_data['application_no'] = $application_no;
						$this->master_model->updateRecord('case_study_comp_registration', $update_data, array('id' => $insert_id));

						$created_folder = date('Ymd');
						$source_path = FCPATH . 'uploads/case_study_comp_registration/' . $created_folder . '/';
						$main_path   = FCPATH . 'uploads/case_study_comp_registration/';

						if($case_study_user_info['upload_case_study_doc'] != ""){
							// File name (assume already uploaded)
							$file_name = $case_study_user_info['upload_case_study_doc']; 
							// Full paths
							$source_file = $source_path . $file_name;
							$dest_file   = $main_path . $file_name; 
							// Check if file exists in source folder
							if (file_exists($source_file)) {
							    // Copy file to main folder
							    if (copy($source_file, $dest_file)) {
							        // Remove file from created folder
							        unlink($source_file); 
							    } else {
							        //echo "Failed to copy file.";
							    }
							} else {
							    //echo "Source file does not exist.";
							}
						}

						if($case_study_user_info['upload_signature'] != ""){
							// File name (assume already uploaded)
							$file_name = $case_study_user_info['upload_signature']; 
							// Full paths
							$source_file = $source_path . $file_name;
							$dest_file   = $main_path . $file_name; 
							// Check if file exists in source folder
							if (file_exists($source_file)) {
							    // Copy file to main folder
							    if (copy($source_file, $dest_file)) {
							        // Remove file from created folder
							        unlink($source_file); 
							    } else {
							        //echo "Failed to copy file.";
							    }
							} else {
							    //echo "Source file does not exist.";
							}
						}
						

						$data_log['Module'] = 'Case Study Writing Competition Registration';
						$data_log['prim_key'] = $insert_id;
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Case Study Writing Competition Registration';
						$data_log['description'] = serialize($case_study_registration_data);
						$data_log['flag'] = 'Success';  
						$data_log['userid'] = $insert_id; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('case_study_comp_registration_logs', $data_log);


				$attachpath = base_url('uploads/case_study_comp_registration/'.$case_study_user_info['upload_case_study_doc']);

				$scheme_level = $scheme_name = '';
				if($case_study_user_info['case_study_level_id'] != ""){
          $case_study_comp_level = $this->master_model->getRecords('case_study_comp_level',array('id'=>$case_study_user_info['case_study_level_id'], 'is_active'=>1),'level'); 
          if(count($case_study_comp_level) > 0)
          {
             $scheme_level = $case_study_comp_level[0]['level'];
          }
        }
        if($case_study_user_info['case_study_level_desc_id'] != ""){
          $case_study_comp_level_desc = $this->master_model->getRecords('case_study_comp_level_desc',array('id'=>$case_study_user_info['case_study_level_desc_id'], 'is_active'=>1),'desc'); 
          if(count($case_study_comp_level_desc) > 0)
          {
             $scheme_name = $case_study_comp_level_desc[0]['desc'];
          }
        } 
				// HTML body

				$mail_content_common = '<tr>
					      <td colspan="2" style="border: none;padding:0;">
					        <table cellspacing="0" cellpadding="0" border="0" style="border-collapse: collapse;">
					          <tbody>
					            <tr><td><strong>Application Number</strong></td><td>'.$application_no.'</td></tr>
					            <tr><td><strong>Title of the Case Study</strong></td><td>'.$case_study_user_info['case_study_title'].'</td></tr>
					            <tr><td><strong>Area of the Case Study</strong></td><td>'.$case_study_user_info['case_study_area'].'</td></tr>
					            <tr><td><strong>Case Study entered in the scheme (I – V)</strong></td><td>'.$scheme_level.'</td></tr>
					            <tr><td><strong>Scheme Name</strong></td><td>'.$scheme_name.'</td></tr>
					            <tr><td><strong>Name of the Author</strong></td><td>'.$case_study_user_info['name_of_author'].'</td></tr>
					            <tr><td><strong>Designation</strong></td><td>'.$case_study_user_info['designation'].'</td></tr>
					            <tr><td><strong>Employer</strong></td><td>'.$case_study_user_info['employer'].'</td></tr>
					            <tr><td><strong>Mobile No.</strong></td><td>'.$case_study_user_info['mobile_no'].'</td></tr>
					            <tr><td><strong>Email ID</strong></td><td>'.$case_study_user_info['email_id'].'</td></tr>
					            <tr><td><strong>Qualifications</strong></td><td>'.$case_study_user_info['qualifications'].'</td></tr>
					            <tr><td><strong>Any other information</strong></td><td>'.$case_study_user_info['other_info'].'</td></tr>
					            <tr><td><strong>Place</strong></td><td>'.$this->input->post('place_name').'</td></tr>
					            <tr><td><strong>Date</strong></td><td>'.date("d-M-Y").'</td></tr>            
					          </tbody>
					        </table>
					      </td>
					    </tr>';

        $mail_content_admin = '<!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="background-color: #00a7f6;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600;">(AN ISO 21001:2018 Certified)</span></td>
                    </tr>
                    <tr>
                      <td style="padding:35px 40px 30px"> 
	                      <table class="inner_tbl" cellspacing="0" cellpadding="0" width="100%" border="0">
												  <tbody>
												    <tr>
												      <td colspan="2" style="border: none; padding:0;">
												        <p style="text-align: center; font-size: 20px; margin: 0 0 20px 0; border-bottom: 1px dashed #bbb; padding: 0 0 5px 0; ">IIBF’s Case Study Writing Competition Form - 2025 Application Details</p> 
												      </td>
												    </tr>
												    <tr><td colspan="2" style="border: none;"></td></tr>
												    <tr><td colspan="2" style="border: none;"></td></tr>
												    
												    '.$mail_content_common.'
												    
												    <tr><td colspan="2" style="border: none;"></td></tr>
												     
												  </tbody>
												</table> 
											</td>
                    </tr>
                    <tr>
                      <td style="background-color: #00a7f6; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';

        $mail_content_candidate = '<!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="background-color: #00a7f6;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600;">(AN ISO 21001:2018 Certified)</span></td>
                    </tr>
                    <tr>
                      <td style="padding:35px 40px 30px"> 
                      	<table class="inner_tbl" cellspacing="0" cellpadding="0" width="100%" border="0">
												  <tbody>
												    <tr>
												      <td colspan="2" style="border: none; padding:0;">
												        <p style="text-align: center; font-size: 20px; margin: 0 0 20px 0; border-bottom: 1px dashed #bbb; padding: 0 0 5px 0; ">IIBF’s Case Study Writing Competition Form - 2025 Application Details</p>

												        <p>Dear '.$case_study_user_info['name_of_author'].',</p>

												        <p style="text-align: justify;margin: 8px 0 0 0;line-height: 20px;">You have <strong>successfully registered</strong> for the Case Study Writing Competition – 2025.</p>
												        <p style="text-align: justify;margin: 8px 0 0 0;line-height: 20px;">Please note down your application details for future correspondence.</p>
												      </td>
												    </tr>
												    <tr><td colspan="2" style="border: none;"></td></tr>
												    <tr><td colspan="2" style="border: none;"></td></tr>
												    
												    '.$mail_content_common.'
												    
												    <tr><td colspan="2" style="border: none;"></td></tr>
												    <tr>
												      <td colspan="2" style="border: none; padding:0;">
												        <p class="footer_regards">Regards,<br>IIBF</p>
												      </td>
												    </tr>
												  </tbody>
												</table> 
											</td>
                    </tr>
                    <tr>
                      <td style="background-color: #00a7f6; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
 
				/*$mail_content = '
				<html>
				<head>
				  <style>
				    body { font-family: Arial, sans-serif; font-size: 14px; color: #333; }
				    h2 { color: #0056b3; }
				    p { margin: 5px 0; }
				    .section-title { font-weight: bold; margin-top: 10px; }
				  </style>
				</head>
				<body>
				  <h2>IIBF’s Case Study Writing Competition Form - 2025</h2>
				  <p><strong>Title of the Case Study:</strong><br>'.$case_study_user_info['case_study_title'].'</p>				  
				  <p><strong>Area of the Case Study:</strong><br>'.$case_study_user_info['case_study_area'].'</p>				  
				  <p><strong>Case Study entered in the scheme (I – V):</strong><br>'.$scheme_level.'</p>				  
				  <p><strong>Scheme Name:</strong><br>'.$scheme_name.'</p>				  
				  <p><strong>Name of the Author:</strong><br>'.$case_study_user_info['name_of_author'].'</p>				  
				  <p><strong>Designation:</strong><br>'.$case_study_user_info['designation'].'</p>				  
				  <p><strong>Employer:</strong><br>'.$case_study_user_info['employer'].'</p>				  
				  <p><strong>Mobile No.:</strong><br>'.$case_study_user_info['mobile_no'].'</p>				  
				  <p><strong>Email ID:</strong><br>'.$case_study_user_info['email_id'].'</p>				  
				  <p><strong>Qualifications:</strong><br>'.$case_study_user_info['qualifications'].'</p>				  
				  <p><strong>Any other information:</strong><br>'.$case_study_user_info['other_info'].'</p>
				  <h3>DECLARATION & COPYRIGHT TRANSFER FORM</h3>
				  <p>
				    To be signed by all authors, I/We, the undersigned author(s) of the case study titled 
				    <em><strong><u>'.$case_study_user_info['case_study_title'].'</u></strong></em>, hereby declare that:
				  </p>
				  <ul>
				    <li>The above Case Study and Teaching Notes submitted to IIBF, Mumbai, are not under consideration elsewhere.</li>
				    <li>The Case Study and Teaching Notes have not been published already in part or whole in any journal or magazine for private or public circulation.</li>
				    <li>I/We give consent for publication in any media and assign copyright to IIBF in the event of its publication.</li>
				    <li>The Case Study & Teaching Notes may be used by IIBF for educational purposes after modifications, without mentioning the author(s).</li>
				    <li>I/We affirm the case does not violate the intellectual rights of any third party and indemnify IIBF against any claims.</li>
				    <li>I/We do not have any conflict of interest (financial or otherwise).</li>
				    <li>I/We have read the final version and take responsibility for the contents.</li>
				    <li>The work described is my/our own.</li>
				    <li>All contributors have been acknowledged; no one has been denied authorship.</li>
				    <li>If authorship is contested, responsibility lies with the author(s); IIBF will be indemnified.</li>
				    <li>All authors are required to sign this form.</li>
				  </ul>

				  <p><strong>Date:</strong> '.date("d-M-Y").'</p>
				  <p><strong>Signature:</strong> <img src="'.base_url('uploads/case_study_comp_registration/' . $case_study_user_info['upload_signature']).'" alt="Uploaded Signature" width="100" height="100" style="border:1px solid #ccc;" /></p>
				  <p><strong>Place:</strong> '.$this->input->post('place_name').'</p>
				  <p>I/We agree to the above conditions.</p>
				</body>
				</html>
				';*/ 

        $mail_arg_admin = $mail_arg_candidate = array();

        //Admin Email Header 
        $mail_arg_admin['subject'] = 'IIBF Case Study Writing Competition - 2025';
        $mail_arg_admin['to_email'] = 'ad.trg1@iibf.org.in'; //'casestudies2025@iibf.org.in'; 
        $mail_arg_admin['to_name'] = 'Case Study Writing Competition'; //'sagar'; //
        $mail_arg_admin['cc_email'] = 'anil.s@esds.co.in,Shweta.Pingale@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in 
        $mail_arg_admin['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
        $mail_arg_admin['is_header_footer_required'] = '0';
        $mail_arg_admin['view_flag'] = '0';
        $mail_arg_admin['attachment'] = $attachpath; 
        $mail_arg_admin['mail_content'] = $mail_content_admin;
		    //echo "<pre>".print_r($mail_arg_admin)."<pre>"; //die;
		    $logmail = $this->case_study_send_mail_common($mail_arg_admin); //Email Send to Admin
 				
 				//Candidate Email Header 
        $mail_arg_candidate['subject'] = 'Your Application Details for IIBF Case Study Writing Competition - 2025';
        $mail_arg_candidate['to_email'] = 'anil.s@esds.co.in'; //$case_study_user_info['email_id']; 
        $mail_arg_candidate['to_name'] = 'Case Study Writing Competition'; //'sagar'; //
        $mail_arg_candidate['cc_email'] = 'Shweta.Pingale@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in 
        $mail_arg_candidate['bcc_email'] = 'iibfteam@esds.co.in';//sagar.matale@esds.co.in,anil.s@esds.co.in
        $mail_arg_candidate['is_header_footer_required'] = '0';
        $mail_arg_candidate['view_flag'] = '0'; 
        $mail_arg_candidate['mail_content'] = $mail_content_candidate;
		    //echo "<pre>".print_r($mail_arg_candidate)."<pre>"; //die;
		    $logmail = $this->case_study_send_mail_common($mail_arg_candidate); //Email Send to Candidate

		        //print_r($logmail);die; 

						$this->session->set_flashdata('success','You are Data Submitted Successfully!..');
						$this->session->unset_userdata("case_study_user_info");

						redirect(base_url().'case_writing_competition/registration');
						//redirect(base_url().$_SESSION['reffer']);
					}
					else
					{ 
						$data_log['Module'] = 'Case Study Writing Competition Registration';
						$data_log['prim_key'] = '';
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Case Study Writing Competition Registration';
						$data_log['description'] = serialize($case_study_registration_data);
						$data_log['flag'] = 'Error';  
						$data_log['userid'] = ''; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('case_study_comp_registration_logs', $data_log);

						$this->session->set_flashdata('error','Error occured during Registration');
						redirect(base_url().'case_writing_competition/registration');
					}
					
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}

           
        }

				$this->load->model('Captcha_model');
				$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('CASE_WRITING_COMPETITION');
				$data['case_study_comp_level'] = $this->master_model->getRecords('case_study_comp_level', array('is_active' => '1'));
        $this->load->view('case_writing_competition/preview',$data);
		}
  
    function case_study_send_mail_common($mail_arg=array())
    {
      $subject = $mail_content = $to_email = $to_name = $cc_email = $bcc_email = $attachment = '';
      $from_email='logs@iibf.esdsconnect.com'; 
      $from_name='IIBF';
      $reply_to_email='noreply@iibf.org.in';
      $reply_to_name='IIBF';
      $view_flag = '0'; 
      $is_header_footer_required='0';
      $is_smtp='1';
      
      if(count($mail_arg) > 0)
      {
        if(isset($mail_arg['subject']) && $mail_arg['subject'] != '') { $subject = $mail_arg['subject']; }
        if(isset($mail_arg['mail_content']) && $mail_arg['mail_content'] != '') { $mail_content = $mail_arg['mail_content']; }
        if(isset($mail_arg['to_email']) && $mail_arg['to_email'] != '') { $to_email = $mail_arg['to_email']; }
        if(isset($mail_arg['to_name']) && $mail_arg['to_name'] != '') { $to_name = $mail_arg['to_name']; }
        if(isset($mail_arg['cc_email']) && $mail_arg['cc_email'] != '') { $cc_email = $mail_arg['cc_email']; }
        if(isset($mail_arg['bcc_email']) && $mail_arg['bcc_email'] != '') { $bcc_email = $mail_arg['bcc_email']; }
        if(isset($mail_arg['attachment']) && $mail_arg['attachment'] != '') { $attachment = $mail_arg['attachment']; }
        
        if(isset($mail_arg['from_email']) && $mail_arg['from_email'] != '') { $from_email = $mail_arg['from_email']; }
        if(isset($mail_arg['from_name']) && $mail_arg['from_name'] != '') { $from_name = $mail_arg['from_name']; }
        if(isset($mail_arg['reply_to_email']) && $mail_arg['reply_to_email'] != '') { $reply_to_email = $mail_arg['reply_to_email']; }
        if(isset($mail_arg['reply_to_name']) && $mail_arg['reply_to_name'] != '') { $reply_to_name = $mail_arg['reply_to_name']; }
        if(isset($mail_arg['view_flag']) && $mail_arg['view_flag'] != '') { $view_flag = $mail_arg['view_flag']; }
        if(isset($mail_arg['is_header_footer_required']) && $mail_arg['is_header_footer_required'] != '') { $is_header_footer_required = $mail_arg['is_header_footer_required']; }
        if(isset($mail_arg['is_smtp']) && $mail_arg['is_smtp'] != '') { $is_smtp = $mail_arg['is_smtp']; }
      }

      if($subject != '' && $from_email != '' && $to_email != '' && $mail_content != '')
      {
        if($is_header_footer_required == '1')
        {
          $mail_body ='
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="600px" border="1" style="width: 100%; max-width:800px; border-collapse: collapse; font-size: 14px; line-height: 20px; border: 1px solid #041f38; margin: 0 auto; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="background-color: #00a7f6;color: #fff; font-size: 20px; font-weight: bold; text-align: center; padding: 20px 5px 15px; border-bottom: 1px solid #000; line-height: 25px;">INDIAN INSTITUTE OF BANKING & FINANCE<br><span style="font-size: 16px; font-weight: 600;">(AN ISO 21001:2018 Certified)</span></td>
                    </tr>
                    <tr>
                      <td style="padding:35px 40px 30px">                      
                        '.$mail_content.'                      
                      </td>
                    </tr>
                    <tr>
                      <td style="background-color: #00a7f6; color: #fff; font-weight: bold; text-align: center; padding: 0 8px; height: 38px;">&copy; '.date('Y').' IIBF. All rights reserved.</td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
        }
        else if($is_header_footer_required == '0')
        {
          $mail_body ='
            <!DOCTYPE html>
            <html>
              <head>
                <meta charset="UTF-8">
                <title>Email</title>
                <style type="text/css">
                  body { font-family: Times New Roman; font-size: 14px; color: #000; margin: 0; padding: 0; }                            
                </style>
              </head>
              <body>
                <br>
                <table cellspacing="0" cellpadding="0" width="100%" style="width: 100%; border-collapse: collapse; font-size: 14px; line-height: 20px; color:#000;">
                  <tbody>                    
                    <tr>
                      <td style="padding:10px">                      
                        '.$mail_content.'                      
                      </td>
                    </tr>
                  </tbody>
                </table>
                <br>
              </body>
            </html>';
        }

        $mail_body .='
                  ';
          
        if($view_flag=='1')
        {
          echo "<br>From = ".$from_email." (".$from_name.")";
          echo "<br>To = ".$to_email." (".$to_name.")";
          echo "<br>CC = ".$cc_email;					
          echo "<br>BCC = ".$bcc_email;					
          echo "<br>Reply to = ".$reply_to_email." (".$reply_to_name.")";			
          echo "<br>Subject = ".$subject;
          if(is_array($attachment)) { echo "<br>Attachment = "; print_r($attachment); } else { echo "<br>Attachment = ".$attachment; }
          echo "<br>Message = ".$mail_body; 
          exit;
        }
        
        $this->load->library('email');
        if($is_smtp == "0")
        {						
          //$config['protocol'] = 'sendmail';
          //$config['mailpath'] = '/usr/sbin/sendmail';
          $config['charset'] = 'iso-8859-1';
          $config['charset'] = 'UTF-8';
          $config['wordwrap'] = TRUE;
          $config['mailtype'] = 'html';
          $this->email->initialize($config);
          //$this->email->subject($subject." php mail");
        }
        else
        {
          $this->Emailsending->setting_smtp();
        }
        
        $this->email->clear(TRUE);
        $this->email->set_newline("\r\n");

        $this->email->from($from_email, $from_name);
        $this->email->to($to_email, $to_name); 
        $this->email->subject($subject);
        $this->email->message($mail_body);
        
        if($reply_to_email != "") { $this->email->reply_to($reply_to_email, $reply_to_name); }
        if($cc_email != "") { $this->email->cc($cc_email); }
        if($bcc_email != '') { $this->email->bcc($bcc_email); }
        
        if(is_array($attachment))
        {
          foreach($attachment as $row)
          {
            $this->email->attach($row);
          }
        }
        else
        {
          if($attachment!=NULL || $attachment!='')
          {
            $this->email->attach($attachment);
          }
        }
        
        if($this->email->send())
        {
          $final_msg = 'success';
        }
        else
        {
          $final_msg = 'error. Email not send<br>';
          $final_msg .= $this->email->print_debugger();
        }
        
        return $final_msg;
        $this->email->clear();				
      }
      else
      {
        return 'error - invalid form fields';
      }
    } 

		/*GET VALUES OF CITY */
    	public function getCasestudylevel() 
    	{
				if (isset($_POST["case_study_level_id"]) && !empty($_POST["case_study_level_id"])) 
				{
					$case_study_level_id = $this->security->xss_clean($this->input->post('case_study_level_id'));
					$case_study_level_desc_id = $this->security->xss_clean($this->input->post('case_study_level_desc_id'));
					$result = $this->master_model->getRecords('case_study_comp_level_desc', array('case_study_level_id' => $case_study_level_id));
					if ($result) 
					{
						echo '<option value="">- Select - </option>';
						foreach ($result AS $data) 
						{
							if ($data) 
							{ 
								echo '<option value="'.$data['id'].'" '.($case_study_level_desc_id != "" && $case_study_level_desc_id == $data['id'] ? 'selected' : '').'>' . $data['desc'] . '</option>';
							}
						}
					} 
					else 
					{
						echo '<option value="">Not Available, Please select other</option>';
					}
					
				}
		}

		/* Check pin number according to state */
		public function checkpin()
		{
			$statecode=$_POST['statecode'];
			$pincode=$_POST['pincode'];
			if($statecode!="")
			{
				$this->db->where("$pincode BETWEEN start_pin AND end_pin");
				$prev_count = $this->master_model->getRecords('state_master',array('state_code'=>$statecode),'id'); 
				if(count($prev_count) > 0) 
				{
					echo "true";
				}
				else { 
					echo "false"; 
				}

				/*$this->db->where("$pincode BETWEEN start_pin AND end_pin");
			 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
				//echo $this->db->last_query();
				if($prev_count==0)
				{echo 'false';}
				else
				{echo 'true';}*/
			}
			else
			{
				echo 'false';
			}
		}
    
		function generate_captcha_ajax()
		{
			$session_name = 'CASE_WRITING_COMPETITION_REGISTRATION';
			if(isset($_POST['session_name']) && $_POST['session_name'] != "") 
			{ 
				$session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
			}
			
			$this->load->model('Captcha_model');
			echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
		}
		
		public function check_captcha_code_ajax()
		{
			if(isset($_POST) && count($_POST) > 0)
			{
				$session_name = 'CASE_WRITING_COMPETITION_REGISTRATION';
				$session_captcha = '';
				
				if(isset($_POST['session_name']) && $_POST['session_name'] != "") 
				{ 
					$session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
				}
				
				if(isset($_SESSION[$session_name])) { $session_captcha = $_SESSION[$session_name]; }
				
				$captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
        
				if($captcha_code == $session_captcha) { echo 'true'; } else { echo "false"; }
        
			} else { echo "false"; }
		}		
    
    public function check_member_no_ajax()
		{
			$result['flag'] = 'error';
			$result['response'] = 'Please enter valid Membership/Registration No';
			
			if(isset($_POST) && $_POST['member_no'] != "" && $_POST['member_type'] != "")
			{
				$member_no = $this->security->xss_clean(trim($this->input->post('member_no')));
				$member_type = $this->security->xss_clean(trim($this->input->post('member_type')));
        
        $member_info = array();
        if($member_type == 'ordinary') 
				{
					$member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$member_no, 'isactive'=>'1'),'regid');
				}
        else if($member_type == 'non_ordinary')
        {
          $member_info = $this->master_model->getRecords('spm_elearning_registration',array('regnumber'=>$member_no, 'isactive'=>'1', 'registrationtype'=>'1'),'regid');
        }
				
				if(!empty($member_info) && count($member_info) > 0) 
				{ 
					$result['flag'] = 'success';
				}
			} 
			
			echo json_encode($result);
		}

    function validation_pan_no_exist($str='',$type=0, $enc_month_id=0)//$type : 0=>Server side, 1=>Ajax
		{
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['pan_no'] != "")
			{
        
        if($type == '1') { $pan_no = $this->input->post('pan_no'); }
        else if($type == '0') { $pan_no = $str; }
        else
        {
          $explode_arr = explode("###", $type);
          $type = $explode_arr[0];          
        }
        
        /* echo '<br>str : '.$str;
        echo '<br>type : '.$type;
        echo '<br>enc_month_id : '.$enc_month_id;exit; */
        
        $vendor_info = $this->master_model->getRecords('case_study_comp_registration',array('pan_no'=>$pan_no, 'is_active'=>1),'id');
        if(count($vendor_info) == 0) { $return_val_ajax = 'true'; }
			}
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else
        {
          $this->form_validation->set_message('validation_pan_no_exist','Pan no already exist');
          return false;
        }
      }
		}

		public function check_pan_no_ajax()
		{
			if(isset($_POST) && $_POST['pan_no'] != "")
			{
				//$email = strtolower($this->input->post('email'));
				$pan_no = strtoupper($this->input->post('pan_no')); 
				$vendor_info = $this->master_model->getRecords('case_study_comp_registration',array('pan_no'=>$pan_no, 'is_active'=>1),'id'); 
				if(count($vendor_info) > 0) 
				{
					echo "false";
				}
				else { echo "true"; }
			} 
			else { echo "false"; }
		}
		
		public function check_email_exist_ajax()
		{
			if(isset($_POST) && $_POST['email'] != "")
			{
				$email = strtolower($this->input->post('email'));
				$member_no = $this->input->post('member_no');
				
				if($member_no != "" && $member_no != "0")
				{
					$this->db->where('regnumber != ', base64_decode($member_no));
				}
				
				$member_info = $this->master_model->getRecords('spm_elearning_registration',array('email'=>$email, 'isactive'=>'1'),'regid'); 
				if(count($member_info) > 0) 
				{
					echo "false";
				}
				else { echo "true"; }
			} 
			else { echo "false"; }
		}
		
    public function check_mobile_exist_ajax()
		{
			if(isset($_POST) && $_POST['mobile'] != "")
			{
				$mobile = $this->input->post('mobile');
				$member_no = $this->input->post('member_no');
				
				if($member_no != "" && $member_no != "0")
				{
					$this->db->where('regnumber != ', base64_decode($member_no));
				}
				
				$member_info = $this->master_model->getRecords('spm_elearning_registration',array('mobile'=>$mobile, 'isactive'=>'1'),'regid'); 
				if(count($member_info) > 0) 
				{
					echo "false";
				}
				else { echo "true"; }
			} 
			else { echo "false"; }
		}
		 
		  
    
		
		public function check_login_captcha() 
		{
			/* $val1 = $this->input->post('val1');		  
			$val2 = $this->input->post('val2');		  
			$val3 = $this->input->post('val3');
			$add_val = ($val1+$val2);
			
			if($val1 == "" || $val2 == "" || $val3 == "" || $add_val != $val3)
			{
				$this->form_validation->set_message('check_login_captcha', 'Please enter correct answer');	
				return FALSE;
			}
			else
			{
				return TRUE;								
			} */
			
			$session_name = 'CASE_WRITING_COMPETITION_REGISTRATION';
			$session_captcha = '';
			if(isset($_SESSION[$session_name])) { $session_captcha = $_SESSION[$session_name]; }
			
			$captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
			
			if($captcha_code == $session_captcha) { return TRUE; } else { $this->form_validation->set_message('check_login_captcha', 'Please enter correct code');	 return FALSE; }
		}
     
    public function remove_old_dir(){
    		$base_path = FCPATH . 'uploads/case_study_comp_registration/';
				$today     = date('Ymd');

				// Scan all folders in directory
				$folders = glob($base_path . '*', GLOB_ONLYDIR);

				if($folders){
						foreach ($folders as $folder) 
						{
						    $folder_name = basename($folder);

						    // Check if folder name is Ymd format and not today's folder
						    if ($folder_name !== $today && preg_match('/^\d{8}$/', $folder_name)) {
						        // Remove all files inside the folder
						        $files = glob($folder . '/*');
						        foreach ($files as $file) {
						            if (is_file($file)) {
						                unlink($file);
						            }
						        }
						        // Remove folder itself
						        rmdir($folder);
						    }
						}
				}
				
    }  
		 
	} 			