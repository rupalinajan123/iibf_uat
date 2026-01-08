<?php 
  /********************************************************************************************************************
  ** Description: Controller for Macroresearch Candidate REGISTRATION
  ** Created BY: Priyanka Dhikale On 04-Oct-2024

  ********************************************************************************************************************/
  //ini_set('display_errors', 1);ini_set('display_startup_errors', 1);error_reporting(E_ALL);

  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Application extends CI_Controller 
  {
    public function __construct()
    {

      
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('macroresearch_model');
      $this->load->helper('macroresearch_helper'); 
      $this->load->helper('file');
      $this->load->library('upload');
      $this->load->helper('upload_helper');
      $this->load->helper('master_helper');
      $this->load->library('email');
      $this->load->model('Emailsending'); 
		}
    // File upload handling function
    private function do_file_upload() {

        $date = date('Y-m-d');$proposal_file=$var_errors='';
        $config['upload_path'] = './uploads/macroresearch'; // Directory to upload files
        $config['allowed_types'] = 'jpg|png|pdf|docx'; // Allowed file types
        $config['max_size'] = 5000; // Allowed file types

        //$this->load->library('upload', $config);

        $uploaded_files = array();
        $error = false;
        $error_message = '';

        // Loop through forwarding_letter[]
        foreach ($_FILES['forwarding_letter']['name'] as $key => $file_name) {
            $new_filename     = $_POST['candidate_name'][$key].'_FL_'.strtotime($date) . rand(0, 100);;
            $config['file_name'] = $new_filename;
            $this->upload->initialize($config); 
            $_FILES['curr_forwarding_letter']['name'] = $_FILES['forwarding_letter']['name'][$key];
            $_FILES['curr_forwarding_letter']['type'] = $_FILES['forwarding_letter']['type'][$key];
            $_FILES['curr_forwarding_letter']['tmp_name'] = $_FILES['forwarding_letter']['tmp_name'][$key];
            $_FILES['curr_forwarding_letter']['error'] = $_FILES['forwarding_letter']['error'][$key];
            $_FILES['curr_forwarding_letter']['size'] = $_FILES['forwarding_letter']['size'][$key];

            if (!$this->upload->do_upload('curr_forwarding_letter')) {
                // If any file upload fails, store the error message
                $error = true;
                $error_message = 'Forwarding Letter : '.$this->upload->display_errors();
                //break;
            } else {
                $uploaded_files['forwarding_letter'][$key] = $this->upload->data(); // File uploaded successfully
            }
        }

        foreach ($_FILES['resume']['name'] as $key => $file_name) {

          $new_filename     = $_POST['candidate_name'][$key].'_CV_'.strtotime($date) . rand(0, 100);
          $config['file_name'] = $new_filename; 

          $this->upload->initialize($config);
          $_FILES['curr_resume']['name'] = $_FILES['resume']['name'][$key];
          $_FILES['curr_resume']['type'] = $_FILES['resume']['type'][$key];
          $_FILES['curr_resume']['tmp_name'] = $_FILES['resume']['tmp_name'][$key];
          $_FILES['curr_resume']['error'] = $_FILES['resume']['error'][$key];
          $_FILES['curr_resume']['size'] = $_FILES['resume']['size'][$key];

          if (!$this->upload->do_upload('curr_resume')) {
              // If any file upload fails, store the error message
              $error = true;
              $error_message = 'Resume : '.$this->upload->display_errors();
             // break;
          } else {
              $uploaded_files['resume'][$key] = $this->upload->data(); // File uploaded successfully
          }
        }
        if (isset($_FILES['proposal']['name']) && ($_FILES['proposal']['name'] != '')) 
          {
            $config['upload_path'] = './uploads/macroresearch'; // Directory to upload files
              $config['allowed_types'] = 'docx'; // Allowed file types
              $config['max_size'] = 5000; // Allowed file types
              $new_filename     = $_POST['candidate_name'][0].'_PP_'.strtotime($date) . rand(0, 100);;
              
              $config['file_name'] = $new_filename; 
              $this->upload->initialize($config);
              
              if ($this->upload->do_upload('proposal')) {
                  $dt             = $this->upload->data();
                  $proposal_file   = $dt['file_name'];
                  $uploaded_files['proposal'][0] = $this->upload->data();
              } else {
                $error = true;
                $error_message = 'Proposal : '.$this->upload->display_errors();
                //break;
              }
              

          }

        if ($error) {
            return array('success' => false, 'error' => $error_message);
        } else {
            return array('success' => true, 'uploaded_files' => $uploaded_files);
        }
    }

    public function submit() {
      if(isset($_POST) && count($_POST) > 0)
      {
        // echo '<pre>';print_r($_POST);exit;
        $this->form_validation->set_rules('application_type', 'Application Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('dob[]', 'Date of birth', 'trim|required|xss_clean');
        $this->form_validation->set_rules('candidate_name[]', 'name', 'trim|required|callback_check_eligibility|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[160]|xss_clean', array('required'=>"Please enter the %s"));     
        $this->form_validation->set_rules('candidate_salutation[]', 'Salutation', 'trim|required|max_length[160]|xss_clean', array('required'=>"Please select the %s"));     
        foreach($_POST['email'] as $key=>$email) {
          $this->form_validation->set_rules('email['.$key.']', 'Email id: '.$email, 'trim|required|max_length[75]|valid_email|callback_validation_check_email_exist[]|callback_check_duplicate_email_id[]|xss_clean', array('required'=>"Please enter the %s"));
        }
        foreach($_POST['mobile'] as $key=>$email) {
          $this->form_validation->set_rules('mobile['.$key.']', 'Mobile number: '.$mobile, 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[]|callback_check_duplicate_mobile_number[]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required'=>"Please enter the %s"));
        }
        $this->form_validation->set_rules('nature_of_job[]', 'pdc zone', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  
       
        $this->form_validation->set_rules('designation[]', 'designation', 'trim|required|xss_clean');
        
        $this->form_validation->set_rules('employer[]', 'employer', 'trim|required|max_length[100]|xss_clean', array('required'=>"Please enter the %s"));
        
        $this->form_validation->set_rules('address[]', 'address', 'trim|required|xss_clean');
        
        if($_POST['application_type']=='Individual' || $_POST['application_type']=='Joint') {
          //$this->form_validation->set_rules('forwarding_letter[]', 'Forwarding Letter', 'callback_forwarding_letter');
        }
        if($_POST['application_type']=='Institute' ) {
          $this->form_validation->set_rules('institute_name', 'Institute Name', 'trim|required|xss_clean');
          //$this->form_validation->set_rules('project_coordinator', 'Project Co-ordinator Name', 'trim|required|xss_clean');
        }
        
       
        //$this->form_validation->set_rules('resume[]', 'Resume', 'file_required|file_allowed_type[jpg,jpeg,png,pdf]|file_size_max[300]');
        
        $this->form_validation->set_rules('title_research_proposal', 'Title Of Research Proposal', 'trim|required|max_length[255]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('objectives', ' Major Objectives of Research', 'trim|required|max_length[255]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('theme', 'Theme', 'trim|required|xss_clean');
        $this->form_validation->set_rules('proposal', 'Proposal', 'file_required');
       
        
        if($this->form_validation->run())
        {          
          
          foreach($_POST['nature_of_job'] as $nature_of_job) {
            if($nature_of_job!='Regular') {
              return redirect()->back()->withInput()->with('error', 'Wrong Selected Nature of Job');
            } 
          }
          
          $date = date('Y-m-d');$proposal_file='';
          $file_data = $this->do_file_upload();

          $posted_arr = json_encode($_POST);

          $this->macroresearch_model->insert_common_log('macroresearch posted Application', 'macroresearch_applications', '', $id,'application_action','', $posted_arr.json_encode($_FILES)); 

          if (!$file_data['success']) {
            // File upload error
            echo json_encode(array('status' => 'error', 'message' => $file_data['error']));
          } 
          else 
          {
              $uploaded_files = $file_data['uploaded_files'];
              //echo'<pre>';print_r($uploaded_files);exit;
              $mainadd_data['title_research_proposal'] = ucfirst(trim($candidate_name = $_POST['title_research_proposal']));
              $mainadd_data['objectives'] = ucfirst(trim($candidate_name = $_POST['objectives']));
              $mainadd_data['theme'] = ucfirst(trim($candidate_name = $_POST['theme']));
              $mainadd_data['proposal'] = $uploaded_files['proposal'][0]['file_name'];
              $mainadd_data['ip_address'] = get_ip_address(); //general_helper.php   
              $mainadd_data['application_type'] = $this->input->post('application_type');
              $mainadd_data['institute_name'] = $this->input->post('institute_name');
              //$mainadd_data['project_coordinator'] = $this->input->post('project_coordinator');
              $mainadd_data['is_active'] = '1';
              $mainadd_data['created_on'] = date("Y-m-d H:i:s");
              
              $this->master_model->insertRecord('macroresearch_applications',$mainadd_data);
              $id = $this->db->insert_id();

              //
              $total_record_qry = $this->db->query('SELECT am.id FROM macroresearch_applications am WHERE am.id <= "'.$id.'"');
              $get_total_record = $total_record_qry->num_rows();
              
              $up_data['application_code'] = 1000 + $get_total_record;            
              $this->master_model->updateRecord('macroresearch_applications', $up_data, array('id'=>$id));
              
              $this->macroresearch_model->insert_common_log('macroresearch Application', 'macroresearch_applications', $this->db->last_query(), $id,'application_action','The application has successfully registered', $posted_arr); 

              
              foreach($_POST['candidate_name'] as $key=>$value) {

                $add_data['macro_id'] = ($id);  
                $add_data['salutation'] = trim($_POST['candidate_salutation'][$key]);          
                $add_data['candidate_name'] = ucfirst(trim($candidate_name = $_POST['candidate_name'][$key]));
                $add_data['dob'] = date('Y-m-d',strtotime($_POST['dob'][$key]));
                $add_data['email'] = trim($_POST['email'][$key]);
                $add_data['acknowledged'] = isset($_POST['receive_acknowledge'][$key]) ? 1 : '0';
                $add_data['mobile'] = trim($_POST['mobile'][$key]);
                $add_data['nature_of_job'] = ucfirst(trim($_POST['nature_of_job'][$key]));
                $add_data['employer'] = ucfirst(trim($_POST['employer'][$key]));
                $add_data['designation'] = ucfirst(trim($_POST['designation'][$key]));
                $add_data['address'] = ucfirst(trim($_POST['address'][$key]));
                $add_data['forwarding_letter'] = $uploaded_files['forwarding_letter'][$key]['file_name'];
                $add_data['resume'] = $uploaded_files['resume'][$key]['file_name'];
                
                $insertStatus = $this->master_model->insertRecord('macroresearch_app_records',$add_data);
                $macro_app_id = $this->db->insert_id();


                $application_type = $_POST['application_type'];
                
                if ( ($macro_app_id > 0) && (($application_type == 'Individual') || (($application_type == 'Joint' || $application_type == 'Institute') && isset($_POST['receive_acknowledge'][$key])) ) ) 
                {
                  /* Email Sending */
                  $emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'macro_research_email'));

                  $emailContent = $emailerstr[0]['emailer_text'];;

                  $info_arr = array('to'=>trim($_POST['email'][$key]),
                                    'from'=>$emailerstr[0]['from'],
                                    'cc'=>'iibfdev@esds.co.in',
                                    'subject'=>$emailerstr[0]['subject'],
                                    'message'=>$emailContent);

                  $this->Emailsending->mailsend($info_arr);
                }  
              }
             
              echo json_encode(array('status' => 'success', 'message' => 'Application submitted successfully!'));
          }
            
        }
        else {
       
          echo json_encode(array('status' => 'error', 'message' => validation_errors()));
        }
      }	
    }
    public function not_eligible_candidates() {
      return array('Ajaya Kumar Panda','Prakash Singh','Swati Raju','Vighneswara Swamy','Tejinderpal Singha','B K Swain','N S Shetty','N K Thingalaya','M S Moodithaya','M Jayadev','Meena Sharma','Nikita Ramrakhiani','Pankaj Kumar Agarwal','Leena S','Pirzada Mohammad Athar','Peerzadah Oveis','Bijoyata Yonzon','Dinesh Mishra','Amrendra Pandey');
    }
    public function index() 
    { 
      $data['page_title'] = 'Macroresearch Application';  

      
      $date = date('Y-m-d');
      $this->db->where('to_date >= ',$date);
      $this->db->where('from_date <= ',$date);
      $application = $this->master_model->getRecords('macroresearch_application_activation', array('is_deleted' => '0'));
      if(count($application)<=0)
      {
        $message = '<div style="color:#F00">Applications are closed. Thanks you for your intereset</div>';
        $data    = array(
            'middle_content'    => 'not_eligible',
            'check_eligibility' => $message,
            'var_errors'        => $var_errors

        );
        return $this->load->view('common_view', $data);
      }
      
      
      $data['not_eligible_candidates'] = $this->not_eligible_candidates();
      $this->load->helper('captcha');
      $data['captcha_img'] = generate_captcha('IIBF_MACRORESEARCH_APPLICATION_CAPTCHA',6); //macroresearch/macroresearch_helper.php
      $this->load->view('macroresearch/application', $data);
    }

    public function refresh_captcha() /******** START : REFRESH CAPTCHA ********/
    { 
      $this->load->helper('captcha');
      echo generate_captcha('IIBF_MACRORESEARCH_APPLICATION_CAPTCHA',6); //macroresearch/macroresearch_helper.php
    }  /******** END : REFRESH CAPTCHA ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK Eligiblity ********/
    public function check_eligibility($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['candidate_name'] != "")
      {
        if($type == '1') { $candidate_name = $this->security->xss_clean($this->input->post('candidate_name')); }
        else if($type == '0') { $candidate_name = $str; }
        
        if(in_array(ucwords($candidate_name),$this->not_eligible_candidates()))
          $return_val_ajax ='false' ;
        else
        $return_val_ajax ='true' ;
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        foreach($_POST['candidate_name'] as $candidate_name) {
          if(in_array(ucwords($candidate_name),$this->not_eligible_candidates()))
            {
              $this->form_validation->set_message('check_eligibility','We regret to inform you that you are not eligible to participate in this scheme');
              return false;
            }
        }
        return true;
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/


    /******** START : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/
    public function validation_check_captcha($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
      if(isset($_POST) && $_POST['iibf_macroresearch_application_captcha'] != "")
      {
        if($type == '1') { $captcha = $this->security->xss_clean($this->input->post('iibf_macroresearch_application_captcha')); }
        else if($type == '0') { $captcha = $str; }
        
        $session_captcha = $this->session->userdata('IIBF_MACRORESEARCH_APPLICATION_CAPTCHA');
        
        if($captcha == $session_captcha)
        {
          $return_val_ajax = 'true';
        }
      }   
      
      if($type == '1') { echo $return_val_ajax; }
      else if($type == '0') 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['iibf_macroresearch_application_captcha'] != "")
        {
          $this->form_validation->set_message('validation_check_captcha','Please enter the valid code');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK THE CORRECT CAPTCHA ********/

     
    /******** START : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/
    public function check_duplicate_mobile_number($str=array()) {
      return TRUE; 
      if(isset($_POST) && $_POST['mobile'] != "")
			{
        return  true;
          if (count($_POST['mobile']) !== count(array_unique($_POST['mobile']))) {
            // There are duplicate values in the array
            $this->form_validation->set_message('check_duplicate_mobile_number', 'Duplicate Mobile numbers are not allowed.');
            return FALSE;
        }
      }
      return TRUE;
    }

    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      return TRUE; 
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['mobile'] != "")
			{
        if($type == '1') 
        { 
          $mobile = $this->security->xss_clean($this->input->post('mobile')); 
        }
        else 
        { 
          $mobile = $str;
        }
        //foreach($mobile as $mob) {
            //check if candidate mobile exist or not
            $result_data = $this->master_model->getRecords('macroresearch_app_records am', array('am.is_deleted' => '0', 'am.mobile' => $mobile), 'am.id, am.mobile, am.email');
          
            if(count($result_data) == 0)
            {
              $return_val_ajax = 'true';
            }
          

          if($type == '1') { echo $return_val_ajax; }
          else 
          { 
            if($return_val_ajax == 'true') { return TRUE; } 
            else if($_POST['mobile'] != "")
            {
              $this->form_validation->set_message('validation_check_mobile_exist','Mobile number : '.$mobile.' is already exist');
              return false;
            }
          }
        //}
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

    /******** START : VALIDATION FUNCTION TO CHECK AGENCY EMAIL ID EXIST OR NOT ********/

    public function check_duplicate_email_id($str=array()) {
      return TRUE; 
      if(isset($_POST) && $_POST['email'] != "")
			{
        if (count($_POST['email']) !== count(array_unique($_POST['email']))) {
          // There are duplicate values in the array
          $this->form_validation->set_message('check_duplicate_email_id', 'Duplicate Email Id are not allowed.');
          return FALSE;
        }
      }
      return TRUE;
    }

    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      return TRUE; 
      $return_val_ajax = 'false';
			if(isset($_POST) && !empty($_POST['email']))
			{
          if($type == '1') 
          { 
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
          }
          else 
          { 
            $email = strtolower($str);
          }
            //foreach($email as $em) {
            //check if candidate mobile exist or not
            $result_data = $this->master_model->getRecords('macroresearch_app_records am', array('am.is_deleted' => '0', 'am.email' => $email), 'am.id, am.mobile, am.email');
          
            if(count($result_data) == 0)
            {
              $return_val_ajax = 'true';
            }
          

          if($type == '1') { echo $return_val_ajax; }
          else 
          { 
            if($return_val_ajax == 'true') { return TRUE; } 
            else if($_POST['email'] != "")
            {
              $this->form_validation->set_message('validation_check_email_exist','Email id : '.$email.' is already exist');
              return false;
            }
          }
        //}
      }
    }/******** END : VALIDATION FUNCTION TO CHECK AGENCY MOBILE EXIST OR NOT ********/

   
    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->macroresearch_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

  }