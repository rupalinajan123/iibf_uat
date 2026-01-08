<?php	
  /********************************************************************
  * Description: Controller for E-learning separate module 
  * Created BY: Sagar Matale
  * Created On: 10-02-2021
  * Update By: Sagar Matale
  * Updated on: 05-07-2021
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
		
		public function index() 
		{
      	$data['error']='';
			  
        if(isset($_POST) && count($_POST) > 0)
        {  
          $this->form_validation->set_rules('pan_no', 'Pan No.', 'trim|required|xss_clean|callback_validation_pan_no_exist[0###0]',array('required' => 'Please enter the %s'));
          //$this->form_validation->set_rules('aadhar_no','Aadhar Card No','trim|xss_clean|numeric|min_length[12]|max_length[12]');
          /* $this->form_validation->set_rules('val1', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val2', 'Value', 'trim|xss_clean',array('required' => 'Please enter the %s'));
          $this->form_validation->set_rules('val3', 'Value', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s')); */

          //$this->form_validation->set_rules('captcha_code', 'Security Code', 'trim|callback_check_login_captcha|xss_clean',array('required' => 'Please enter the %s'));
          
          $this->form_validation->set_rules('full_name','Name','trim|required|max_length[100]');
					$this->form_validation->set_rules('address','Address','trim|max_length[200]'); 
					//$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[200]'); 
					$this->form_validation->set_rules('city','City','trim|required|max_length[50]'); 
					$this->form_validation->set_rules('state','State','trim|required');
					$this->form_validation->set_rules('pin_code','Pin Code','trim|required|max_length[6]'); 
					//$this->form_validation->set_rules('mobile_no','Mobile No.','required|max_length[10]|min_length[10]');
					$this->form_validation->set_rules('email_id','Email ID','valid_email|required|trim'); 
					$this->form_validation->set_rules('upload_pan_no','Upload Pan No.','required');
					$this->form_validation->set_rules('declaration','Declaration','required');
					//$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
					//$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
					//$this->form_validation->set_rules('phone','Phone No','max_length[8]');
				
				if($this->form_validation->run()==TRUE)
				{

					$pan_no = strtoupper($this->input->post('pan_no'));

					$user_info = $this->master_model->getRecords('vendor_registration',array('pan_no'=>strtoupper($this->input->post('pan_no')), 'is_active'=>1),'id,full_name');
            
          if(count($user_info) > 0)
          {
            $this->session->set_flashdata('error',"Your Pan No. already register!..");
						redirect(base_url().'vendors/registration');
          }

					 
					$date = date('Y-m-d h:i:s');
					
					$image_size_error = 0;
					$image_size_error_message = array();
					$errorUploadType = '';
					$upload_pan_no = $upload_gst_no = '';
					if (!empty($_FILES['upload_pan_no']['name']))
          {
          	//echo 'if'; 

               $arr_extension = array('pdf','jpg','jpeg','png');  
               $ext1 = explode('.',$_FILES['upload_pan_no']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .pdf, .jpg, .jpeg, .png extension files.');
                redirect(base_url().'vendors/registration');
              } 
              //$file_name_to_dispaly =  'pan_no_'.rand(000,999).round(microtime(true)).'.'.$ext;
              $file_name_to_dispaly =  'pan_no_'.$pan_no.'.'.$ext;
 
              $config['upload_path'] = 'uploads/vendor_registration/'; 
              $config['allowed_types'] = 'pdf|jpg|jpeg|png';
              $config['max_size']      = '200';
              $config['file_name']     =  $file_name_to_dispaly;

              $this->load->library('upload', $config); 
              $this->upload->initialize($config); 

              if($this->upload->do_upload('upload_pan_no')){ 
                  // Uploaded file data 
                  $fileData = $this->upload->data(); 
                  //$uploadData['file_name'] = $fileData['file_name']; 
                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                  $uploaded_id = 1;
                  $upload_pan_no      = $file_name_to_dispaly;
               
              }else{ 
                  $this->session->set_flashdata('error',$this->upload->display_errors());
									redirect(base_url().'vendors/registration');
                  $errorUploadType .= $_FILES['upload_pan_no']['name'].' | ';  
                  $uploaded_id = 0;
              } 
          }
          if (isset($_FILES['upload_gst_no']['name']))
          { 
          	  if (!empty($_FILES['upload_gst_no']['name'])){ 
	             
	             $arr_extension = array('pdf','jpg','jpeg','png');  
               $ext1 = explode('.',$_FILES['upload_gst_no']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .pdf, .jpg, .jpeg, .png extension files.');
                redirect(base_url().'vendors/registration');
              } 
              $file_name_to_dispaly =  'gst_no_'.$pan_no.'.'.$ext;
 
              $config['upload_path'] = 'uploads/vendor_registration/'; 
              $config['allowed_types'] = 'pdf|jpg|jpeg|png';
              $config['max_size']      = '200';
              $config['file_name']     =  $file_name_to_dispaly;

	              $this->load->library('upload', $config); 
	              $this->upload->initialize($config); 

	              if($this->upload->do_upload('upload_gst_no')){ 
	                  // Uploaded file data 
	                  $fileData = $this->upload->data(); 
	                  //$uploadData['file_name'] = $fileData['file_name']; 
	                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
	                  $uploaded_id = 1;
	                  $upload_gst_no      = $file_name_to_dispaly;
	               
	              }else{ 
	                  $this->session->set_flashdata('error',$this->upload->display_errors());
										//redirect(base_url().'vendors/registration');
	                  $errorUploadType .= $_FILES['upload_gst_no']['name'].' | ';  
	                  $uploaded_id = 0;
	              }
              } 
          }
          if (isset($_FILES['upload_msmed_reg_no']['name']))
          { 
          	if (!empty($_FILES['upload_msmed_reg_no']['name'])){ 	
              
               $arr_extension = array('pdf','jpg','jpeg','png');  
               $ext1 = explode('.',$_FILES['upload_msmed_reg_no']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .pdf, .jpg, .jpeg, .png extension files.');
                redirect(base_url().'vendors/registration');
              } 
              $file_name_to_dispaly =  'msmed_reg_no_'.$pan_no.'.'.$ext;
 
              $config['upload_path'] = 'uploads/vendor_registration/'; 
              $config['allowed_types'] = 'pdf|jpg|jpeg|png';
              $config['max_size']      = '200';
              $config['file_name']     =  $file_name_to_dispaly;

              $this->load->library('upload', $config); 
              $this->upload->initialize($config); 

              if($this->upload->do_upload('upload_msmed_reg_no')){ 
                  // Uploaded file data 
                  $fileData = $this->upload->data(); 
                  //$uploadData['file_name'] = $fileData['file_name']; 
                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                  $uploaded_id = 1;
                  $upload_msmed_reg_no      = $file_name_to_dispaly;
               
              }else{ 
                  $this->session->set_flashdata('error',$this->upload->display_errors());
									//redirect(base_url().'vendors/registration');
                  $errorUploadType .= $_FILES['upload_msmed_reg_no']['name'].' | ';  
                  $uploaded_id = 0;
              } 
            }
          }
          if (isset($_FILES['upload_canceled_cheque']['name']))
          { 
          	if (!empty($_FILES['upload_canceled_cheque']['name'])){ 
              
              $arr_extension = array('pdf','jpg','jpeg','png');  
               $ext1 = explode('.',$_FILES['upload_canceled_cheque']['name']); 
               $ext = end($ext1);  
               $ext = strtolower($ext);

              if(!in_array($ext,$arr_extension))
              { 
                $this->session->set_flashdata('error','Please upload .pdf, .jpg, .jpeg, .png extension files.');
                redirect(base_url().'vendors/registration');
              } 
              $file_name_to_dispaly =  'canceled_cheque_'.$pan_no.'.'.$ext;
 
              $config['upload_path'] = 'uploads/vendor_registration/'; 
              $config['allowed_types'] = 'pdf|jpg|jpeg|png';
              $config['max_size']      = '200';
              $config['file_name']     =  $file_name_to_dispaly;
               
              $this->load->library('upload', $config); 
              $this->upload->initialize($config); 

              if($this->upload->do_upload('upload_canceled_cheque')){ 
                  // Uploaded file data 
                  $fileData = $this->upload->data(); 
                  $uploadData['file_name'] = $fileData['file_name']; 
                  $uploadData['uploaded_on'] = date("Y-m-d H:i:s"); 
                  $uploaded_id = 1;
                  $upload_canceled_cheque      = $file_name_to_dispaly;
               
              }else{ 
                  $this->session->set_flashdata('error',$this->upload->display_errors());
									//redirect(base_url().'vendors/registration');
                  $errorUploadType .= $_FILES['upload_canceled_cheque']['name'].' | ';  
                  $uploaded_id = 0;
              } 
            }
          }

          $company_cin = '';
          if($this->input->post('company_cin')){
          		$company_cin = strtoupper($this->input->post('company_cin'));
          }
					  
					$vendor_data = array(	
					'full_name' => strtoupper($this->input->post('full_name')),
					'address'		=>strtoupper($this->input->post('address')),
					'state'		=>strtoupper($this->input->post('state')),
					'city'		=>strtoupper($this->input->post('city')),
					'pin_code'		=>$this->input->post('pin_code'),
					'type_of_person'		=>strtoupper($this->input->post('type_of_person')),
					'company_cin'		=>	$company_cin,
					'contact_person_name'		=> strtoupper($this->input->post('contact_person_name')),
					'designation'		=> strtoupper($this->input->post('designation')),
					'email_id'				=>strtoupper($this->input->post('email_id')),
					'mobile_no'				=>$this->input->post('mobile_no'),
					'website'				=>$this->input->post('website'),
					'telephone_no'				=>$this->input->post('telephone_no'),
					'nature_of_goods_services'				=>strtoupper($this->input->post('nature_of_goods_services')),
					'pan_no'			=> strtoupper($this->input->post('pan_no')),
					'upload_pan_no'				=>$upload_pan_no,
					'gst_no'				=>strtoupper($this->input->post('gst_no')),
					'upload_gst_no'				=>$upload_gst_no,
					'msmed_reg_no'			=>strtoupper($this->input->post('msmed_reg_no')),
					'upload_msmed_reg_no'			=>$upload_msmed_reg_no,
					'epfo_reg_no'			=>strtoupper($this->input->post('epfo_reg_no')),
					'vendor_name_in_bank'	=>strtoupper($this->input->post('vendor_name_in_bank')),
					'bank_name'	=> strtoupper($this->input->post('bank_name')),	
					'bank_branch_address' => strtoupper($this->input->post('bank_branch_address')),
					//'bank_location_address'	=>strtoupper($this->input->post('bank_location_address')),
					'account_type'	=>strtoupper($this->input->post('account_type')),
					'bank_account_no'	=>$this->input->post('bank_account_no'),
					'ifsc_code'	=>strtoupper($this->input->post('ifsc_code')),
					'micr_code'	=>strtoupper($this->input->post('micr_code')),
					'upload_canceled_cheque'	=>$upload_canceled_cheque,
					'authorized_person_name'	=>strtoupper($this->input->post('authorized_person_name')),
					'authorized_person_designation'	=>strtoupper($this->input->post('authorized_person_designation')), 
					'is_active'	=> 1 
					);
					//print_r($vendor_data);die;
					
					$insert_id = $this->master_model->insertRecord('vendor_registration', $vendor_data, true); 
					if($insert_id)
					{ 
						//log_dra_user($module='Vendor Registration', $insert_id, $activity = 'Add',$log_title = "Vendor Registration Successful", $log_message = serialize($vendor_data), $flag='Success');

						$data_log['Module'] = 'Vendor Registration';
						$data_log['prim_key'] = $insert_id;
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Vendor Registration';
						$data_log['description'] = serialize($vendor_data);
						$data_log['flag'] = 'Success';  
						$data_log['userid'] = $insert_id; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('vendor_registration_logs', $data_log);

						$this->session->set_flashdata('success','You are Registered Successfully!..');
						redirect(base_url().'vendors/registration');
						//redirect(base_url().$_SESSION['reffer']);
					}
					else
					{ 
						$data_log['Module'] = 'Vendor Registration';
						$data_log['prim_key'] = '';
						$data_log['activity'] = 'Add';
						$data_log['title'] = 'Vendor Registration';
						$data_log['description'] = serialize($vendor_data);
						$data_log['flag'] = 'Error';  
						$data_log['userid'] = ''; 
						$data_log['ip'] = $this->input->ip_address();
						$this->db->insert('vendor_registration_logs', $data_log);

						$this->session->set_flashdata('error','Error occured during Registration');
						redirect(base_url().'vendors/registration');
					}
					
				}
				else
				{
					$data['validation_errors'] = validation_errors(); 
				}

           
        }
        
				$this->load->model('Captcha_model');
				$data['captcha_img'] = $this->Captcha_model->generate_captcha_img('VENDOR');
				$data['states'] = $this->master_model->getRecords('state_master');
        $this->load->view('vendors/registration',$data);
      
		}

		/*GET VALUES OF CITY */
    	public function getCity() 
    	{
			if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
			{
				$state_code = $this->security->xss_clean($this->input->post('state_code'));
				$result = $this->master_model->getRecords('city_master', array('state_code' => $state_code));
				if ($result) 
				{
					echo '<option value="">- Select - </option>';
					foreach ($result AS $data) 
					{
						if ($data) 
						{
							echo '<option value="' . $data['city_name'] . '">' . $data['city_name'] . '</option>';
						}
					}
				} 
				else 
				{
					echo '<option value="">City Not Available, Please select other state</option>';
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
			$session_name = 'VENDOR_REGISTRATION';
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
				$session_name = 'VENDOR_REGISTRATION';
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
        
        $vendor_info = $this->master_model->getRecords('vendor_registration',array('pan_no'=>$pan_no, 'is_active'=>1),'id');
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
				$vendor_info = $this->master_model->getRecords('vendor_registration',array('pan_no'=>$pan_no, 'is_active'=>1),'id'); 
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
			
			$session_name = 'VENDOR_REGISTRATION';
			$session_captcha = '';
			if(isset($_SESSION[$session_name])) { $session_captcha = $_SESSION[$session_name]; }
			
			$captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
			
			if($captcha_code == $session_captcha) { return TRUE; } else { $this->form_validation->set_message('check_login_captcha', 'Please enter correct code');	 return FALSE; }
		}
      
		 
	} 			