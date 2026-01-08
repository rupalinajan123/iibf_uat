<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF INSPECTION REPORT
  ** Created BY: Sagar Matale On 09-01-2024
  ********************************************************************************************************************/
	defined('BASEPATH') OR exit('No direct script access allowed');
	
	class Inspection_report_inspector extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			
			$this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      
      $this->login_inspector_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');
      
      if($this->login_user_type != 'inspector') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';
      $this->inspection_report_by_admin_file_path = 'uploads/iibfbcbf/inspection_report_by_admin';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->inspection_attachment_path = 'uploads/iibfbcbf/inspection_attachment';
    }

    public function index($enc_batch_id=0)
    {
      $data['act_id'] = "Inspection Report";
			$data['sub_act_id'] = "Inspection Report";
			$data['candidate_photo_path'] = $this->candidate_photo_path;
			$data['inspection_attachment_path'] = $inspection_attachment_path = $this->inspection_attachment_path;
      
      $this->db->group_by('bi.batch_id');
      $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = bi.batch_id', 'INNER');
      $data['batch_dropdown_data'] = $this->master_model->getRecords('iibfbcbf_batch_inspection bi', array('bi.inspector_id'=>$this->login_inspector_id, 'btch.is_deleted'=>'0'), 'btch.batch_id, btch.batch_code, btch.batch_hours, btch.batch_start_date, btch.batch_end_date, btch.inspector_id');
      
      $batch_id = '0';
      if($enc_batch_id != '0')
      {
        $batch_id = url_decode($enc_batch_id);

        $inspection_all_data = $this->Iibf_bcbf_model->get_inspection_data($batch_id, $this->login_inspector_id);

        $data['batch_data'] = $batch_data = $inspection_all_data['batch_data'];
        $data['user_data'] = $inspection_all_data['user_data'];
        $data['bank_cand_data'] = $inspection_all_data['bank_cand_data'];
        $data['centre_data'] = $inspection_all_data['centre_data'];
        $data['inspection_data'] = $inspection_data = $inspection_all_data['inspection_data'];
        $data['batch_candidate_data'] = $inspection_all_data['batch_candidate_data'];
        $data['candidate_inspection_data_arr'] = $inspection_all_data['candidate_inspection_data_arr'];

        if(count($batch_data) == 0 || count($inspection_data) == 0) { redirect(site_url('iibfbcbf/inspector/inspection_report_inspector')); }
      }
      
      $data['batch_id'] = $batch_id;
      $data['enc_batch_id'] = $enc_batch_id;
      $data['page_title'] = 'IIBF - BCBF Inspection Report';
      $this->load->view('iibfbcbf/inspector/inspection_report_inspector', $data);
    }
        
    public function add_inspection_report_inspector($enc_batch_id=0)
		{   
			$data['act_id'] = "Add Inspection Report";
			$data['sub_act_id'] = "Add Inspection Report";
			$data['candidate_photo_path'] = $this->candidate_photo_path;
			$data['inspection_attachment_path'] = $inspection_attachment_path = $this->inspection_attachment_path;
      $data['attachment_error'] = '';
      $error_flag = 0;
      
      $this->db->where_in('btch.batch_status', array(3,4));
      $data['batch_dropdown_data'] = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted'=>'0', 'btch.inspector_id'=>$this->login_inspector_id, 'btch.batch_start_date <='=>date("Y-m-d"), 'btch.batch_end_date >='=>date("Y-m-d")), 'btch.batch_id, btch.batch_code, btch.batch_hours, btch.batch_start_date, btch.batch_end_date');
      //_pq(1);

      $batch_id = '0';
      if($enc_batch_id != '0')
      {
        $batch_id = url_decode($enc_batch_id);
        
        $this->db->where_in('acb.batch_status', array(3,4));
        $this->db->join('iibfbcbf_faculty_master fm1', 'fm1.faculty_id = acb.first_faculty', 'LEFT');
        $this->db->join('iibfbcbf_faculty_master fm2', 'fm2.faculty_id = acb.second_faculty', 'LEFT');
        $this->db->join('iibfbcbf_faculty_master fm3', 'fm3.faculty_id = acb.third_faculty', 'LEFT');
        $this->db->join('iibfbcbf_faculty_master fm4', 'fm4.faculty_id = acb.fourth_faculty', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = acb.agency_id', 'LEFT'); 
        $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'INNER');
        $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = acb.inspector_id', 'LEFT');
        $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.inspector_id' => $this->login_inspector_id, 'acb.is_deleted' => '0', 'acb.batch_start_date <='=>date("Y-m-d"), 'acb.batch_end_date >='=>date("Y-m-d")), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', 'Cancelled'))))))) AS DispBatchStatus,am1.agency_name,am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_city, cm.centre_username, cm2.city_name, im.inspector_name");
        
        if(count($batch_data) == 0) { redirect(site_url('iibfbcbf/inspector/inspection_report_inspector/add_inspection_report_inspector')); }
        
        $data['user_data'] = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'login_id, password', array('created_on'=>'ASC'));  

        $data['bank_cand_data'] = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'bank_id, bank_name, cand_src', array('created_on'=>'ASC'));

        $data['training_schedule_file_path'] = $this->training_schedule_file_path;

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT'); 
        $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$batch_data[0]['centre_id']), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name');

        $data['batch_candidate_data'] = $batch_candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.agency_id'=>$batch_data[0]['agency_id'], 'bc.centre_id'=>$batch_data[0]['centre_id'], 'bc.batch_id'=>$batch_data[0]['batch_id'], 'bc.is_deleted'=>'0'), 'bc.candidate_id, bc.training_id, bc.regnumber, bc.salutation, bc.first_name, bc.middle_name, bc.last_name, bc.dob, bc.mobile_no, bc.email_id, bc.candidate_photo', array('bc.candidate_id'=>'ASC'));

        $inspection_data = $this->master_model->getRecords('iibfbcbf_batch_inspection', array('agency_id'=>$batch_data[0]['agency_id'], 'centre_id'=>$batch_data[0]['centre_id'], 'batch_id'=>$batch_data[0]['batch_id'],  'is_deleted'=>'0'), 'inspection_id');

        $inspection_no = 1;
        if(count($inspection_data) > 0) { $inspection_no = count($inspection_data) + 1; }
        $data['inspection_no'] = $inspection_no;
        
        //START : GET AUTO SAVE DATA TO DISPLAY AUTO FILLED IN THE FORM
        $data['inspection_auto_save_data'] = $inspection_auto_save_data = $this->master_model->getRecords('iibfbcbf_batch_inspection_auto_save', array('agency_id'=>$batch_data[0]['agency_id'], 'centre_id'=>$batch_data[0]['centre_id'], 'batch_id'=>$batch_data[0]['batch_id'], 'inspector_id'=>$this->login_inspector_id), '*', array('inspection_id'=>'DESC'));
        
        if(count($inspection_auto_save_data) > 0)
        {
          $inspection_candidate_auto_save_data = $this->master_model->getRecords('iibfbcbf_candidate_inspection_auto_save', array('batch_id'=>$batch_data[0]['batch_id'], 'batch_inspection_id'=>$inspection_auto_save_data[0]['inspection_id'], 'inspector_id'=>$this->login_inspector_id), '*', array('id'=>'DESC'));

          $inspection_candidate_auto_save_arr = array();
          if(count($inspection_candidate_auto_save_data) > 0)
          {
            foreach($inspection_candidate_auto_save_data as $inspection_candidate_auto_save_res)
            {
              $inspection_candidate_auto_save_arr[$inspection_candidate_auto_save_res['candidate_id']] = $inspection_candidate_auto_save_res;
            }
          }
          $data['inspection_candidate_auto_save_arr'] = $inspection_candidate_auto_save_arr;
        }//END : GET AUTO SAVE DATA TO DISPLAY AUTO FILLED IN THE FORM

        if(isset($_POST) && count($_POST) > 0)
        {     
          $this->form_validation->set_rules('inspection_started_on', '', 'trim|required|xss_clean', array('required'=>"Please enter the value"));
          $this->form_validation->set_rules('candidates_loggedin', '', 'trim|required|xss_clean', array('required'=>"Please enter the value"));
          $this->form_validation->set_rules('platform_name', '', 'trim|xss_clean');
          $this->form_validation->set_rules('multiple_login_same_name', '', 'trim|xss_clean');
          $this->form_validation->set_rules('instrument_name', '', 'trim|xss_clean');
          $this->form_validation->set_rules('issues', '', 'trim|xss_clean');
          $this->form_validation->set_rules('training_session', '', 'trim|xss_clean');
          $this->form_validation->set_rules('session_candidates', '', 'trim|xss_clean');
          $this->form_validation->set_rules('training_session_plan', '', 'trim|xss_clean');
          $this->form_validation->set_rules('attendance_sheet_updated', '', 'trim|xss_clean');
          $this->form_validation->set_rules('attendance_mode', '', 'trim|xss_clean');
          $this->form_validation->set_rules('attendance_shown', '', 'trim|xss_clean');
          $this->form_validation->set_rules('candidate_count_device', '', 'trim|xss_clean');
          $this->form_validation->set_rules('actual_faculty', '', 'trim|xss_clean');
          $this->form_validation->set_rules('faculty_taking_session', '', 'trim|xss_clean');
          $this->form_validation->set_rules('name_qualification', '', 'trim|xss_clean');
          $this->form_validation->set_rules('no_of_days', '', 'trim|xss_clean');
          $this->form_validation->set_rules('reason_of_change_in_faculty', '', 'trim|xss_clean');
          $this->form_validation->set_rules('experience_teaching_training_BFSI_sector', '', 'trim|xss_clean');
          $this->form_validation->set_rules('faculty_language', '', 'trim|xss_clean');
          $this->form_validation->set_rules('faculty_session_time', '', 'trim|xss_clean');
          $this->form_validation->set_rules('two_faculty_taking_session', '', 'trim|xss_clean');
          $this->form_validation->set_rules('faculty_language_understandable', '', 'trim|xss_clean');
          $this->form_validation->set_rules('whiteboard_ppt_pdf_used', '', 'trim|xss_clean');
          $this->form_validation->set_rules('session_on_etiquettes', '', 'trim|xss_clean');
          $this->form_validation->set_rules('faculty_trainees_conversant', '', 'trim|xss_clean');
          $this->form_validation->set_rules('candidates_recognise', '', 'trim|xss_clean');
          $this->form_validation->set_rules('handbook_on_debt_recovery', '', 'trim|xss_clean');
          $this->form_validation->set_rules('other_study_materials', '', 'trim|xss_clean');
          $this->form_validation->set_rules('training_conduction', '', 'trim|xss_clean');
          $this->form_validation->set_rules('batch_coordinator_available', '', 'trim|xss_clean');
          $this->form_validation->set_rules('coordinator_available_name', '', 'trim|xss_clean');
          $this->form_validation->set_rules('current_coordinator_available_name', '', 'trim|xss_clean');
          $this->form_validation->set_rules('any_irregularity', '', 'trim|xss_clean');
          $this->form_validation->set_rules('teaching_quality_interaction_with_candidates', '', 'trim|xss_clean');
          $this->form_validation->set_rules('teaching_quality_softskill_session', '', 'trim|xss_clean');
          $this->form_validation->set_rules('teaching_quality_softskill_session', '', 'trim|xss_clean');
          $this->form_validation->set_rules('attitude_behaviour', '', 'trim|xss_clean');
          $this->form_validation->set_rules('learning_quality_interaction_with_faculty', '', 'trim|xss_clean');
          $this->form_validation->set_rules('learning_quality_response_to_queries', '', 'trim|xss_clean');
          $this->form_validation->set_rules('teaching_effectiveness', '', 'trim|xss_clean');
          $this->form_validation->set_rules('curriculum_covered', '', 'trim|xss_clean');
          $this->form_validation->set_rules('overall_compliance_training_delivery', '', 'trim|xss_clean');
          $this->form_validation->set_rules('overall_compliance_training_coordination', '', 'trim|xss_clean');
          $this->form_validation->set_rules('other_observations', '', 'trim|xss_clean');
          $this->form_validation->set_rules('overall_observation', '', 'trim|xss_clean');
          $this->form_validation->set_rules('overall_compliance', '', 'trim|xss_clean');
          $this->form_validation->set_rules('attachment', 'attachment', 'trim|callback_fun_validate_file_upload[attachment|n|txt,doc,docx,pdf,jpg,png,jpeg|5000|attachment]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb|display name'  
          
          if($this->form_validation->run())
          {
            if($_FILES['attachment']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name = "inspection_attachment_".$batch_data[0]['batch_code'].'_'.date("YmdHis").'_'.rand(1000,9999);
              $upload_data = $this->Iibf_bcbf_model->upload_file("attachment", array('txt','doc','docx','pdf','jpg','png','jpeg'), $new_file_name, "./".$inspection_attachment_path, "txt|doc|docx|pdf|jpg|png|jpeg", '', '', '', '', '5000');
              if($upload_data['response'] == 'error')
              {
                $data['attachment_error'] = $upload_data['message'];
                $error_flag = 1;
              }
              else if($upload_data['response'] == 'success')
              {
                $add_data['attachment'] = $attachment = $new_attachment = $upload_data['message'];
              }
            }

            if($error_flag == 1)
            {
              @unlink("./".$inspection_attachment_path."/".$upload_data['message']);
            }
            else if($error_flag == 0)
            {
              $last_inspection_record = $this->master_model->getRecords('iibfbcbf_batch_inspection', array('agency_id' => $batch_data[0]['agency_id'], 'centre_id' => $batch_data[0]['centre_id'], 'batch_id' => $batch_data[0]['batch_id'], 'inspector_id' => $this->login_inspector_id), 'inspection_id, agency_id, centre_id, batch_id, inspector_id, inspection_no, inspection_start_time, created_on', array('created_on' => 'DESC'));

              $submission_time_error_msg = '';
              if (count($last_inspection_record) > 0)
              {
                $dateTimeObject1 = date_create($last_inspection_record[0]['created_on']);
                $dateTimeObject2 = date_create(date('Y-m-d H:i:s'));

                // Calculating the difference between DateTime Objects 
                $interval = date_diff($dateTimeObject1, $dateTimeObject2);
                $min = $interval->days * 24 * 60;
                $min += $interval->h * 60;
                $min += $interval->i;

                if ($last_inspection_record[0]['inspection_start_time'] == $this->input->post('inspection_started_on'))
                {
                  $submission_time_error_msg  = 'Duplicate form submission';
                }
                else if ($min < 1)
                {
                  $submission_time_error_msg  = 'Wait for 1 min and submit the form again';
                }
              }
              
              if($submission_time_error_msg == "")
              {
                $posted_arr = json_encode($_POST);
                $inspectorName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_inspector_id, 'inspector');

                $add_data['agency_id'] = $batch_data[0]['agency_id'];
                $add_data['centre_id'] = $batch_data[0]['centre_id'];
                $add_data['batch_id'] = $batch_data[0]['batch_id'];
                $add_data['inspector_id'] = $this->login_inspector_id;
                $add_data['inspection_no'] = $inspection_no;
                $add_data['inspection_start_time'] = $this->input->post('inspection_started_on');
                $add_data['candidates_loggedin'] = $this->input->post('candidates_loggedin');
                $add_data['platform_name'] = $this->input->post('platform_name');
                $add_data['multiple_login_same_name'] = $this->input->post('multiple_login_same_name');
                $add_data['instrument_name'] = $this->input->post('instrument_name');
                $add_data['issues'] = $this->input->post('issues');
                $add_data['training_session'] = $this->input->post('training_session');
                $add_data['session_candidates'] = $this->input->post('session_candidates');
                $add_data['training_session_plan'] = $this->input->post('training_session_plan');
                $add_data['attendance_sheet_updated'] = $this->input->post('attendance_sheet_updated');
                $add_data['attendance_mode'] = $this->input->post('attendance_mode');
                $add_data['attendance_shown'] = $this->input->post('attendance_shown');
                $add_data['candidate_count_device'] = $this->input->post('candidate_count_device');
                $add_data['actual_faculty'] = $this->input->post('actual_faculty');
                $add_data['faculty_taking_session'] = $this->input->post('faculty_taking_session');
                $add_data['name_qualification'] = $this->input->post('name_qualification');
                $add_data['no_of_days'] = $this->input->post('no_of_days');
                $add_data['reason_of_change_in_faculty'] = $this->input->post('reason_of_change_in_faculty');
                $add_data['experience_teaching_training_BFSI_sector'] = $this->input->post('experience_teaching_training_BFSI_sector');
                $add_data['faculty_language'] = $this->input->post('faculty_language');
                $add_data['faculty_session_time'] = $this->input->post('faculty_session_time');
                $add_data['two_faculty_taking_session'] = $this->input->post('two_faculty_taking_session');
                $add_data['faculty_language_understandable'] = $this->input->post('faculty_language_understandable');
                $add_data['whiteboard_ppt_pdf_used'] = $this->input->post('whiteboard_ppt_pdf_used');
                $add_data['session_on_etiquettes'] = $this->input->post('session_on_etiquettes');
                $add_data['faculty_trainees_conversant'] = $this->input->post('faculty_trainees_conversant');
                $add_data['candidates_recognise'] = $this->input->post('candidates_recognise');
                $add_data['handbook_on_debt_recovery'] = $this->input->post('handbook_on_debt_recovery');
                $add_data['other_study_materials'] = $this->input->post('other_study_materials');
                $add_data['training_conduction'] = $this->input->post('training_conduction');
                $add_data['batch_coordinator_available'] = $this->input->post('batch_coordinator_available');
                $add_data['coordinator_available_name'] = $this->input->post('coordinator_available_name');
                $add_data['current_coordinator_available_name'] = $this->input->post('current_coordinator_available_name');
                $add_data['any_irregularity'] = $this->input->post('any_irregularity');
              
                $teaching_quality_interaction_with_candidates = '';
                if(isset($_POST['teaching_quality_interaction_with_candidates'])) { $teaching_quality_interaction_with_candidates = $this->input->post('teaching_quality_interaction_with_candidates'); }
                $add_data['teaching_quality_interaction_with_candidates'] = $teaching_quality_interaction_with_candidates;

                $teaching_quality_softskill_session = '';
                if(isset($_POST['teaching_quality_softskill_session'])) { $teaching_quality_softskill_session = $this->input->post('teaching_quality_softskill_session'); }
                $add_data['teaching_quality_softskill_session'] = $teaching_quality_softskill_session;

                $candidates_attentiveness = '';
                if(isset($_POST['candidates_attentiveness'])) { $candidates_attentiveness = $this->input->post('candidates_attentiveness'); }
                $add_data['candidates_attentiveness'] = $candidates_attentiveness;

                $attitude_behaviour = '';
                if(isset($_POST['attitude_behaviour'])) { $attitude_behaviour = $this->input->post('attitude_behaviour'); }
                $add_data['attitude_behaviour'] = $attitude_behaviour;

                $learning_quality_interaction_with_faculty = '';
                if(isset($_POST['learning_quality_interaction_with_faculty'])) { $learning_quality_interaction_with_faculty = $this->input->post('learning_quality_interaction_with_faculty'); }
                $add_data['learning_quality_interaction_with_faculty'] = $learning_quality_interaction_with_faculty;

                $learning_quality_response_to_queries = '';
                if(isset($_POST['learning_quality_response_to_queries'])) { $learning_quality_response_to_queries = $this->input->post('learning_quality_response_to_queries'); }
                $add_data['learning_quality_response_to_queries'] = $learning_quality_response_to_queries;

                $teaching_effectiveness = '';
                if(isset($_POST['teaching_effectiveness'])) { $teaching_effectiveness = $this->input->post('teaching_effectiveness'); }
                $add_data['teaching_effectiveness'] = $teaching_effectiveness;

                $curriculum_covered = '';
                if(isset($_POST['curriculum_covered'])) { $curriculum_covered = $this->input->post('curriculum_covered'); }
                $add_data['curriculum_covered'] = $curriculum_covered;

                $overall_compliance_training_delivery = '';
                if(isset($_POST['overall_compliance_training_delivery'])) { $overall_compliance_training_delivery = $this->input->post('overall_compliance_training_delivery'); }
                $add_data['overall_compliance_training_delivery'] = $overall_compliance_training_delivery;

                $overall_compliance_training_coordination = '';
                if(isset($_POST['overall_compliance_training_coordination'])) { $overall_compliance_training_coordination = $this->input->post('overall_compliance_training_coordination'); }
                $add_data['overall_compliance_training_coordination'] = $overall_compliance_training_coordination;
                
                $add_data['other_observations'] = $this->input->post('other_observations');
                $add_data['overall_observation'] = $this->input->post('overall_observation');

                $overall_compliance = '';
                if(isset($_POST['overall_compliance'])) { $overall_compliance = $this->input->post('overall_compliance'); }
                $add_data['overall_compliance'] = $overall_compliance;

                $add_data['ip_address'] = get_ip_address(); //general_helper.php
                $add_data['is_deleted'] = '0';
                $add_data['created_on'] = date("Y-m-d H:i:s");
                $add_data['created_by'] = $this->login_inspector_id;
                
                $this->master_model->insertRecord('iibfbcbf_batch_inspection ',$add_data);
                $inspection_id = $this->db->insert_id();
                
                if($inspection_id > 0)
                {
                  //START : DELETE EXISTING AUTO SAVE ENTRIES
                  $auto_save_data = $this->master_model->getRecords('iibfbcbf_batch_inspection_auto_save ins', array('ins.batch_id' => $batch_data[0]['batch_id'], 'ins.inspector_id' => $this->login_inspector_id), "ins.inspection_id", array('ins.inspection_id' => 'DESC'));
                  
                  $this->db->where('agency_id', $batch_data[0]['agency_id']);
                  $this->db->where('centre_id', $batch_data[0]['centre_id']);
                  $this->db->where('batch_id', $batch_data[0]['batch_id']);
                  $this->db->where('inspector_id', $this->login_inspector_id);
                  $this->db->delete('iibfbcbf_batch_inspection_auto_save');
                  
                  if(count($auto_save_data) > 0)
                  {
                    $this->db->where('batch_id', $batch_data[0]['batch_id']);
                    $this->db->where('batch_inspection_id', $auto_save_data[0]['inspection_id']);
                    $this->db->where('inspector_id', $this->login_inspector_id);
                    $this->db->delete('iibfbcbf_candidate_inspection_auto_save');
                  }
                  //END : DELETE EXISTING AUTO SAVE ENTRIES

                  $this->Iibf_bcbf_model->insert_common_log('Inspector : Batch Inspection', 'iibfbcbf_batch_inspection', $this->db->last_query(), $inspection_id,'inspection_action','The inspection has successfully added by the inspector '.$inspectorName['disp_name'], $posted_arr); 

                  //START : CANDIDATE INSERT REMARK & ATTENDANCE
                  $remark_arr = $this->input->post('remark');
                  $attendance_arr = $this->input->post('attendance');
                  if (count($remark_arr) > 0)
                  {
                    foreach ($remark_arr as $key_remark => $val_remark)
                    {
                      if ($val_remark[0] != '' || array_key_exists($key_remark, $attendance_arr))
                      {
                        $insert_data = array();
                        $log_msg = '';
                        $insert_data['batch_id'] = $batch_data[0]['batch_id'];
                        $insert_data['batch_inspection_id'] = $inspection_id;
                        $insert_data['candidate_id'] = $key_remark;
                        $insert_data['inspector_id'] = $this->login_inspector_id;
                        $insert_data['remark'] = $val_remark[0];
                        $insert_data['created_by'] = $this->login_inspector_id;
                        if(array_key_exists($key_remark, $attendance_arr)) 
                        { 
                          $insert_data['attendance'] = $attendance_arr[$key_remark][0]; 
                          $log_msg .= ' & attendance';
                        }
                        $insert_data['created_on'] = date('Y-m-d H:i:s');
                        $inserted_remark_id = $this->master_model->insertRecord('iibfbcbf_candidate_inspection', $insert_data);
                        
                        if($inserted_remark_id > 0)
                        {
                          $this->Iibf_bcbf_model->insert_common_log('Inspector : Batch Inspection', 'iibfbcbf_candidate_inspection', $this->db->last_query(), $inserted_remark_id,'inspection_action','The candidate remark '.$log_msg.' has successfully added by the inspector '.$inspectorName['disp_name'], array()); 
                        }
                        else
                        {
                          $this->Iibf_bcbf_model->insert_common_log('Inspector : Batch Inspection', 'iibfbcbf_candidate_inspection', $this->db->last_query(), $inserted_remark_id,'inspection_action','Error occurred during the submission of candidate remark '.$log_msg.' by the inspector '.$inspectorName['disp_name'], array());
                        }
                      }
                    }
                  }//END : CANDIDATE INSERT REMARK & ATTENDANCE

                  $this->db->group_by('ci.candidate_id');
                  $this->db->join('iibfbcbf_agency_centre_batch acb', 'acb.batch_id = ci.batch_id', 'INNER');
                  $candidate_attendance_data = $this->master_model->getRecords('iibfbcbf_candidate_inspection ci', array('ci.batch_id' => $batch_data[0]['batch_id'], 'ci.attendance' => 'Absent'), 'ci.batch_id, ci.candidate_id, count(ci.candidate_id) AS absent_cnt, ci.attendance, acb.batch_hours');
                  
                  if(count($candidate_attendance_data) > 0)
                  {
                    foreach ($candidate_attendance_data as $candidate_attendance_res) 
                    {
                      if (($candidate_attendance_res['batch_hours'] == '28' && $candidate_attendance_res['absent_cnt'] >= 3) || ($candidate_attendance_res['batch_hours'] == '42' && $candidate_attendance_res['absent_cnt'] >= 5))
                      {
                        $up_attendance = array();
                        $up_attendance['hold_release_status'] = '1';
                        $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_attendance,  array('candidate_id' => $candidate_attendance_res['candidate_id']));

                        $this->Iibf_bcbf_model->insert_common_log('Inspector : Batch Inspection', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_attendance_res['candidate_id'],'inspection_action','The candidate status changed as Auto hold. Candidate id is '.$candidate_attendance_res['candidate_id'], array()); 
                      }
                    }
                  }

                  $this->session->set_flashdata('success','Inspection Report Saved Successfully.');
                  redirect(site_url('iibfbcbf/inspector/inspection_report_inspector/index/'.$enc_batch_id));
                }
                else
                {
                  $this->Iibf_bcbf_model->insert_common_log('Inspector : Batch Inspection', 'iibfbcbf_batch_inspection', $this->db->last_query(), $inspection_id,'inspection_action','Error occurred during the submission of the inspection by the inspector '.$inspectorName['disp_name'], $posted_arr); 

                  @unlink("./".$inspection_attachment_path."/".$upload_data['message']);

                  $data['error'] = 'Error occurred during the submission of the inspection.';
                }
              }
              else
              {
                $data['submission_time_error_msg'] = $submission_time_error_msg;
              }
            }
          }
        }
      }
      
      $data['batch_id'] = $batch_id;
      $data['enc_batch_id'] = $enc_batch_id;
      $data['page_title'] = 'IIBF - BCBF Add Inspection Report';
      $this->load->view('iibfbcbf/inspector/add_inspection_report_inspector', $data);
    }

    function auto_save_form_ajax()
    {
      $result['flag'] = "error";
      $result['response'] = "";
      if(isset($_POST) && count($_POST) > 0)
      {
        $enc_batch_id = trim($this->security->xss_clean($this->input->post('enc_batch_id')));
        if($enc_batch_id != '0')
        {
          $batch_id = url_decode($enc_batch_id);
        
          $this->db->where_in('acb.batch_status', array(3,4));
          $this->db->join('iibfbcbf_faculty_master fm1', 'fm1.faculty_id = acb.first_faculty', 'LEFT');
          $this->db->join('iibfbcbf_faculty_master fm2', 'fm2.faculty_id = acb.second_faculty', 'LEFT');
          $this->db->join('iibfbcbf_faculty_master fm3', 'fm3.faculty_id = acb.third_faculty', 'LEFT');
          $this->db->join('iibfbcbf_faculty_master fm4', 'fm4.faculty_id = acb.fourth_faculty', 'LEFT');
          $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = acb.agency_id', 'LEFT'); 
          $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'INNER');
          $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
          $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = acb.inspector_id', 'LEFT');
          $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.inspector_id' => $this->login_inspector_id, 'acb.is_deleted' => '0', 'acb.batch_start_date <='=>date("Y-m-d"), 'acb.batch_end_date >='=>date("Y-m-d")), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', 'Cancelled'))))))) AS DispBatchStatus,am1.agency_name,am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_city, cm.centre_username, cm2.city_name, im.inspector_name");
        
          if(count($batch_data) > 0)
          {
            $add_data['agency_id'] = $batch_data[0]['agency_id'];
            $add_data['centre_id'] = $batch_data[0]['centre_id'];
            $add_data['batch_id'] = $batch_data[0]['batch_id'];
            $add_data['inspector_id'] = $this->login_inspector_id;
            $add_data['inspection_no'] = $add_data['inspection_start_time'] = '';
            $add_data['candidates_loggedin'] = trim($this->security->xss_clean($this->input->post('candidates_loggedin')));
            $add_data['platform_name'] = trim($this->security->xss_clean($this->input->post('platform_name')));
            $add_data['multiple_login_same_name'] = trim($this->security->xss_clean($this->input->post('multiple_login_same_name')));
            $add_data['instrument_name'] = trim($this->security->xss_clean($this->input->post('instrument_name')));
            $add_data['issues'] = trim($this->security->xss_clean($this->input->post('issues')));
            $add_data['training_session'] = trim($this->security->xss_clean($this->input->post('training_session')));
            $add_data['session_candidates'] = trim($this->security->xss_clean($this->input->post('session_candidates')));
            $add_data['training_session_plan'] = trim($this->security->xss_clean($this->input->post('training_session_plan')));
            $add_data['attendance_sheet_updated'] = trim($this->security->xss_clean($this->input->post('attendance_sheet_updated')));
            $add_data['attendance_mode'] = trim($this->security->xss_clean($this->input->post('attendance_mode')));
            $add_data['attendance_shown'] = trim($this->security->xss_clean($this->input->post('attendance_shown')));
            $add_data['candidate_count_device'] = trim($this->security->xss_clean($this->input->post('candidate_count_device')));
            $add_data['actual_faculty'] = trim($this->security->xss_clean($this->input->post('actual_faculty')));
            $add_data['faculty_taking_session'] = trim($this->security->xss_clean($this->input->post('faculty_taking_session')));
            $add_data['name_qualification'] = trim($this->security->xss_clean($this->input->post('name_qualification')));
            $add_data['no_of_days'] = trim($this->security->xss_clean($this->input->post('no_of_days')));
            $add_data['reason_of_change_in_faculty'] = trim($this->security->xss_clean($this->input->post('reason_of_change_in_faculty')));
            $add_data['experience_teaching_training_BFSI_sector'] = trim($this->security->xss_clean($this->input->post('experience_teaching_training_BFSI_sector')));
            $add_data['faculty_language'] = trim($this->security->xss_clean($this->input->post('faculty_language')));
            $add_data['faculty_session_time'] = trim($this->security->xss_clean($this->input->post('faculty_session_time')));
            $add_data['two_faculty_taking_session'] = trim($this->security->xss_clean($this->input->post('two_faculty_taking_session')));
            $add_data['faculty_language_understandable'] = trim($this->security->xss_clean($this->input->post('faculty_language_understandable')));
            $add_data['whiteboard_ppt_pdf_used'] = trim($this->security->xss_clean($this->input->post('whiteboard_ppt_pdf_used')));
            $add_data['session_on_etiquettes'] = trim($this->security->xss_clean($this->input->post('session_on_etiquettes')));
            $add_data['faculty_trainees_conversant'] = trim($this->security->xss_clean($this->input->post('faculty_trainees_conversant')));
            $add_data['candidates_recognise'] = trim($this->security->xss_clean($this->input->post('candidates_recognise')));
            $add_data['handbook_on_debt_recovery'] = trim($this->security->xss_clean($this->input->post('handbook_on_debt_recovery')));
            $add_data['other_study_materials'] = trim($this->security->xss_clean($this->input->post('other_study_materials')));
            $add_data['training_conduction'] = trim($this->security->xss_clean($this->input->post('training_conduction')));
            $add_data['batch_coordinator_available'] = trim($this->security->xss_clean($this->input->post('batch_coordinator_available')));
            $add_data['coordinator_available_name'] = trim($this->security->xss_clean($this->input->post('coordinator_available_name')));
            $add_data['current_coordinator_available_name'] = trim($this->security->xss_clean($this->input->post('current_coordinator_available_name')));
            $add_data['any_irregularity'] = trim($this->security->xss_clean($this->input->post('any_irregularity')));
            
            $teaching_quality_interaction_with_candidates = '';
            if(isset($_POST['teaching_quality_interaction_with_candidates'])) { $teaching_quality_interaction_with_candidates = trim($this->security->xss_clean($this->input->post('teaching_quality_interaction_with_candidates'))); }
            $add_data['teaching_quality_interaction_with_candidates'] = $teaching_quality_interaction_with_candidates;

            $teaching_quality_softskill_session = '';
            if(isset($_POST['teaching_quality_softskill_session'])) { $teaching_quality_softskill_session = trim($this->security->xss_clean($this->input->post('teaching_quality_softskill_session'))); }
            $add_data['teaching_quality_softskill_session'] = $teaching_quality_softskill_session;

            $candidates_attentiveness = '';
            if(isset($_POST['candidates_attentiveness'])) { $candidates_attentiveness = trim($this->security->xss_clean($this->input->post('candidates_attentiveness'))); }
            $add_data['candidates_attentiveness'] = $candidates_attentiveness;

            $attitude_behaviour = '';
            if(isset($_POST['attitude_behaviour'])) { $attitude_behaviour = trim($this->security->xss_clean($this->input->post('attitude_behaviour'))); }
            $add_data['attitude_behaviour'] = $attitude_behaviour;

            $learning_quality_interaction_with_faculty = '';
            if(isset($_POST['learning_quality_interaction_with_faculty'])) { $learning_quality_interaction_with_faculty = trim($this->security->xss_clean($this->input->post('learning_quality_interaction_with_faculty'))); }
            $add_data['learning_quality_interaction_with_faculty'] = $learning_quality_interaction_with_faculty;

            $learning_quality_response_to_queries = '';
            if(isset($_POST['learning_quality_response_to_queries'])) { $learning_quality_response_to_queries = trim($this->security->xss_clean($this->input->post('learning_quality_response_to_queries'))); }
            $add_data['learning_quality_response_to_queries'] = $learning_quality_response_to_queries;

            $teaching_effectiveness = '';
            if(isset($_POST['teaching_effectiveness'])) { $teaching_effectiveness = trim($this->security->xss_clean($this->input->post('teaching_effectiveness'))); }
            $add_data['teaching_effectiveness'] = $teaching_effectiveness;

            $curriculum_covered = '';
            if(isset($_POST['curriculum_covered'])) { $curriculum_covered = trim($this->security->xss_clean($this->input->post('curriculum_covered'))); }
            $add_data['curriculum_covered'] = $curriculum_covered;

            $overall_compliance_training_delivery = '';
            if(isset($_POST['overall_compliance_training_delivery'])) { $overall_compliance_training_delivery = trim($this->security->xss_clean($this->input->post('overall_compliance_training_delivery'))); }
            $add_data['overall_compliance_training_delivery'] = $overall_compliance_training_delivery;

            $overall_compliance_training_coordination = '';
            if(isset($_POST['overall_compliance_training_coordination'])) { $overall_compliance_training_coordination = trim($this->security->xss_clean($this->input->post('overall_compliance_training_coordination'))); }
            $add_data['overall_compliance_training_coordination'] = $overall_compliance_training_coordination;
              
            $add_data['other_observations'] = trim($this->security->xss_clean($this->input->post('other_observations')));
            $add_data['overall_observation'] = trim($this->security->xss_clean($this->input->post('overall_observation')));

            $overall_compliance = '';
            if(isset($_POST['overall_compliance'])) { $overall_compliance = trim($this->security->xss_clean($this->input->post('overall_compliance'))); }
            $add_data['overall_compliance'] = $overall_compliance;

            $auto_save_data = $this->master_model->getRecords('iibfbcbf_batch_inspection_auto_save ins', array('ins.batch_id' => $batch_id, 'ins.inspector_id' => $this->login_inspector_id), "ins.inspection_id", array('ins.inspection_id' => 'DESC'));
            if(count($auto_save_data) == 0)
            {
              $add_data['ip_address'] = get_ip_address(); //general_helper.php
              $add_data['is_deleted'] = '0';
              $add_data['created_on'] = date("Y-m-d H:i:s");
              $add_data['created_by'] = $this->login_inspector_id;
              $this->master_model->insertRecord('iibfbcbf_batch_inspection_auto_save  ',$add_data);
              $inspection_id = $this->db->insert_id();
            }
            else
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_inspector_id;
              $this->master_model->updateRecord('iibfbcbf_batch_inspection_auto_save', $add_data, array('inspection_id' => $auto_save_data[0]['inspection_id']));
              $inspection_id = $auto_save_data[0]['inspection_id'];
            }
            
            if($inspection_id > 0)
            {
              //START : DELETE EXISTING AUTO SAVE ENTRIES
              $this->db->where('batch_id', $batch_data[0]['batch_id']);
              $this->db->where('batch_inspection_id', $inspection_id);
              $this->db->where('inspector_id', $this->login_inspector_id);
              $this->db->delete('iibfbcbf_candidate_inspection_auto_save');
              //END : DELETE EXISTING AUTO SAVE ENTRIES

              //START : CANDIDATE INSERT REMARK & ATTENDANCE
              $remark_arr = $this->input->post('remark');
              $attendance_arr = $this->input->post('attendance');
              if (count($remark_arr) > 0)
              {
                foreach ($remark_arr as $key_remark => $val_remark)
                {
                  if ($val_remark[0] != '' || array_key_exists($key_remark, $attendance_arr))
                  {
                    $insert_data = array();
                    $insert_data['batch_id'] = $batch_data[0]['batch_id'];
                    $insert_data['batch_inspection_id'] = $inspection_id;
                    $insert_data['candidate_id'] = $key_remark;
                    $insert_data['inspector_id'] = $this->login_inspector_id;
                    $insert_data['remark'] = $val_remark[0];
                    $insert_data['created_by'] = $this->login_inspector_id;
                    if(array_key_exists($key_remark, $attendance_arr)) 
                    { 
                      $insert_data['attendance'] = $attendance_arr[$key_remark][0];                     
                    }
                    $insert_data['created_on'] = date('Y-m-d H:i:s');
                    $this->master_model->insertRecord('iibfbcbf_candidate_inspection_auto_save', $insert_data);
                  }
                }
              }//END : CANDIDATE INSERT REMARK & ATTENDANCE
              
              $result['flag'] = "success";
            }
            else
            {
              $result['response'] = 'Error occurred during the submission of the inspection.';
            }
          }
          else
          {
            $result['response'] = "Batch data not found";
          }
        }
        else
        {
          $result['response'] = "Invalid posted data";
        }          
      }
      else
      {
        $result['response'] = "Invalid Request";
      }

      echo json_encode($result);
    }

    function apply_search()
    {      
      $enc_batch_id = 0;
      if(isset($_POST) && count($_POST) > 0)
      {
        $enc_batch_id = url_encode(trim($this->security->xss_clean($this->input->post('s_batch_id'))));
      }
      redirect(site_url('iibfbcbf/inspector/inspection_report_inspector/add_inspection_report_inspector/'.$enc_batch_id));
    }

    function apply_search_report()
    {
      if(isset($_POST) && count($_POST) > 0 && isset($_POST['export_to_pdf']) && $_POST['export_to_pdf'] != "")
      {
        $batch_id = trim($this->security->xss_clean($this->input->post('s_batch_id')));
        $inspection_all_data = $this->Iibf_bcbf_model->get_inspection_data($batch_id, $this->login_inspector_id);

        $data['batch_data'] = $batch_data = $inspection_all_data['batch_data'];
        $data['user_data'] = $inspection_all_data['user_data'];
        $data['bank_cand_data'] = $inspection_all_data['bank_cand_data'];
        $data['centre_data'] = $inspection_all_data['centre_data'];
        $data['inspection_data'] = $inspection_data = $inspection_all_data['inspection_data'];
        $data['batch_candidate_data'] = $inspection_all_data['batch_candidate_data'];
        $data['candidate_inspection_data_arr'] = $inspection_all_data['candidate_inspection_data_arr'];
        
        $data['candidate_photo_path'] = $this->candidate_photo_path;
			  $data['inspection_attachment_path'] = $inspection_attachment_path = $this->inspection_attachment_path;
        
        $data['inspection_report_pdf_flag'] = '1';
        $data['inspection_report_by_admin_file_path'] = $this->inspection_report_by_admin_file_path;
        $data['inspection_attachment_path'] = $this->inspection_attachment_path;

        if(count($batch_data) == 0 || count($inspection_data) == 0) { redirect(site_url('iibfbcbf/inspector/inspection_report_inspector')); }
        
        $html = $this->load->view('iibfbcbf/common/inc_inspection_report_content_pdf', $data, true);
        //echo $html; die;
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdfFilePath = 'batch_inspection_report_pdf_'.$batch_data[0]['batch_code'].".pdf";
        //generate the PDF from the given html
        $pdf->WriteHTML($html);
        //download it.
        $pdf->Output($pdfFilePath, "D");
        exit;
      }
      
      $enc_batch_id = 0;
      if(isset($_POST) && count($_POST) > 0)
      {
        $enc_batch_id = url_encode(trim($this->security->xss_clean($this->input->post('s_batch_id'))));
      }
      redirect(site_url('iibfbcbf/inspector/inspection_report_inspector/index/'.$enc_batch_id));
    }

    /******** START : VALIDATION FUNCTION TO CHECK VALID FILE ********/
    function fun_validate_file_upload($str,$parameter) // Custom callback function for check valid file
    {
      $result = $this->Iibf_bcbf_model->fun_validate_file_upload($parameter); 
      if($result['flag'] == 'success') { return true; }
      else
      {
        $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
        return false;
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID FILE ********/
  }