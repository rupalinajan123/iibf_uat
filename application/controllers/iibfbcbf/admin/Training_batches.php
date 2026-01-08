<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Centre Batches Master
  ** Created BY: Sagar Matale On 17-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Training_batches extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      
      $this->login_admin_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Training Batches module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      }
       
      $this->no_of_hours_basic = '28'; //28 Hours 
      $this->no_of_hours_advance = '42'; //42 Hours 
      $this->chk_gross_training_days_basic = '15'; //15 Days
      $this->chk_gross_training_days_advance = '28'; //28 Days 
      $this->chk_gross_training_time_per_day = '8'; //8 Hours 
      $this->chk_total_break_time = '90'; //90 Minutes 
      $this->chk_net_training_time_per_day = '7'; //7 Hours 
      $this->chk_total_net_training_time_of_duration_basic = '28'; //28 Hours 
      $this->chk_total_net_training_time_of_duration_advance = '42'; //42 Hours 
      $this->chk_total_batch_candidates = '35'; //35 Candidates

      $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';
      $this->inspection_report_by_admin_file_path = 'uploads/iibfbcbf/inspection_report_by_admin';
		}
    
    public function index()
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";
      $data['page_title'] = 'IIBF - BCBF Training Batches';

      $data['agency_data'] = $data['agency_centre_data'] = array();
      
      $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, am.is_active', array('am.agency_name'=>'ASC'));

      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username', array('cm.centre_name'=>'ASC'));

      $this->load->view('iibfbcbf/admin/training_batches_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRAINING BATCHES DATA ********/
    public function get_training_batches_data_ajax()
    {
      $table = 'iibfbcbf_agency_centre_batch acb';
      
      $column_order = array('acb.batch_id', 'CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")") AS agency_name', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", "")) AS DispBatchType', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date) AS BatchDate', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted")))))))) AS DispBatchStatus', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag','acb.agency_id', 'acb.batch_start_date', 'acb.batch_end_date'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")")', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")")', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", ""))', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date)', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))'); //SET COLUMN FOR SEARCH
      $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER
      
       
      $WhereForTotal = "WHERE acb.is_deleted = 0 AND acb.batch_status != '0' AND acb.batch_status != '8' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE acb.is_deleted = 0 AND acb.batch_status != '0' AND acb.batch_status != '8' ";
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
      if($s_agency != "") { $Where .= " AND acb.agency_id = '".$s_agency."'"; } 

      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND acb.centre_id = '".$s_centre."'"; } 
      
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."') AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 

      $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
      if($s_batch_code != "") { $Where .= " AND acb.batch_code = '".custom_safe_string($s_batch_code)."'"; } //iibfbcbf/iibf_bcbf_helper.php
      
      $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
      if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['agency_name'];
        $row[] = $Res['DispCentreName'];
        $row[] = $Res['batch_code'];
        $row[] = $Res['centre_batch_id'];
        $row[] = $Res['DispBatchType'];
        $row[] = $Res['BatchDate'];
        $row[] = $Res['BatchTime'];
        $row[] = $Res['total_candidates'];
        $row[] = $Res['created_on'];
        
        $row[] = '<span class="badge '.show_batch_status($Res['batch_status']).'" style="min-width:90px;">'.$Res['DispBatchStatus'].'</span>';

        $batch_candidate_count = '';
        $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$Res['agency_id'].'" AND centre_id = "'.$Res['centre_id'].'" AND batch_id = "'.$Res['batch_id'].'" AND is_deleted="0" ');
        $batch_candidate_count = $batch_candidate_qry->num_rows();
        //echo $this->db->last_query();
        
        $btn_str = ' <div class="text-left no_wrap"> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/training_batches/training_batch_details/'.url_encode($Res['batch_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';

        if($Res['batch_status'] == '3' && date('Y-m-d') <= $Res['batch_end_date'] && $batch_candidate_count < $Res['total_candidates'])
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/admin/batch_candidates/add_candidates/'.url_encode($Res['batch_id'])).'" class="btn btn-warning btn-xs" title="Add Candidate"><i class="fa fa-user-circle-o" aria-hidden="true"></i></a> ';
        }
        
        //show candidate list button only when there is any candidate available in the batch
        if($batch_candidate_count > 0)
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($Res['batch_id'])).'" class="btn btn-danger btn-xs" title="Candidate List"><i class="fa fa-users" aria-hidden="true"></i></a> ';
        }        
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        /* if(in_array($Res['batch_id'],$delete_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
        $row[] = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['batch_id'].'" id="checkboxlist_new_'.$Res['batch_id'].'" onclick="update_delete_str('.$Res['batch_id'].')" '.$check_val.'><span class="checkmark"></span></label>'; */
        
        $data[] = $row; 
      }			
      
      $output = array(
      "draw" => $_POST['draw'],
      "recordsTotal" => $TotalResult, //All result count
      "recordsFiltered" => $FilteredResult, //Disp result count
      /* "Query" => $print_query, */
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRAINING BATCHES DATA ********/  	     

    /******** START : FACULTY DETAILS PAGE ********/
    public function training_batch_details($enc_batch_id=0)
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";     
      $data['page_title'] = 'IIBF - BCBF Agency Training Batches Details';

      $data['inspection_report_by_admin_error'] = '';
      $data['inspection_report_by_admin_file_path'] = $inspection_report_by_admin_file_path = $this->inspection_report_by_admin_file_path;

      $data['enc_batch_id'] = $enc_batch_id;
      $batch_id = url_decode($enc_batch_id); 
      
      $this->db->join('iibfbcbf_faculty_master fm1', 'fm1.faculty_id = acb.first_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm2', 'fm2.faculty_id = acb.second_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm3', 'fm3.faculty_id = acb.third_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm4', 'fm4.faculty_id = acb.fourth_faculty', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = acb.agency_id', 'LEFT'); 
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = acb.inspector_id', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.is_deleted' => '0', 'acb.batch_status !='=> '0', 'acb.batch_status !='=> '8'), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', IF(acb.batch_status = 7, 'Cancelled', 'Drafted')))))))) AS DispBatchStatus,am1.agency_name,am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_city, cm.centre_username, cm2.city_name, im.inspector_name");
      
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/training_batches')); }
      
      $data['user_data'] = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'login_id, password', array('created_on'=>'ASC'));  

      $data['bank_cand_data'] = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'bank_id, bank_name, cand_src', array('created_on'=>'ASC'));
      
      $data['training_schedule_file_path'] = $this->training_schedule_file_path;

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT'); 
      $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$form_data[0]['centre_id']), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name');
      
      /*if(isset($_POST) && count($_POST) > 0){
        _pa($_POST,1);
      }*/
      //START : SUBMIT BATCH COMMUNICATION
      if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_action']) && $_POST['form_action'] == 'batch_communication_action' && $this->login_user_type == 'admin')
      {
        //_pa($_POST,1);
        $this->form_validation->set_rules('batch_communication', 'batch communication', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
        
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin'); 

          $this->Iibf_bcbf_model->insert_common_log('Admin : Batch Communication', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch communication added by the admin '.$centreName['disp_name']." : ".$this->input->post('batch_communication'), $posted_arr);
              
          $this->session->set_flashdata('success','Batch communication added successfully');

          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
        } 
      } //END : SUBMIT BATCH COMMUNICATION
      else if(isset($_POST) && count($_POST) > 0 && isset($_POST["form_action"]) && $_POST['form_action'] == 'batch_status_action' && $this->login_user_type == 'admin') //START : SUBMIT BATCH STATUS
      {
        if($form_data[0]['batch_end_date'] < date('Y-m-d'))
        {
          $this->session->set_flashdata('error','You can not change batch status after batch end date ('.$form_data[0]['batch_end_date'].')');
          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id)); exit;
        }

        $admin_batch_status_new = '';
        $admin_batch_status = $this->input->post('admin_batch_status');
        $admin_batch_status_new = $this->input->post('admin_batch_status_new');
        
        //_pa($_POST,1);
        $batch_status_reason_label = '';
        $batch_status_action = '';
        if($admin_batch_status == '3' && $admin_batch_status_new == ''){
          $batch_status_reason_label = 'Describe Approval Reason here';
          $batch_status_action = 'Go Ahead';
        }else if($admin_batch_status == '2'){
          $batch_status_reason_label = 'Describe Error here';
          $batch_status_action = 'Error';
        }else if($admin_batch_status == '5'){
          $batch_status_reason_label = 'Describe rejection reason here';
          $batch_status_action = 'Rejected';
        }else if($admin_batch_status == '4'){
          $batch_status_reason_label = 'Describe Hold batch reason here';
          $batch_status_action = 'Hold';
        }else if($admin_batch_status == '7'){
          $batch_status_reason_label = 'Describe cancel batch reason here';
          $batch_status_action = 'Cancelled';
        }else if($admin_batch_status_new == 'UnHold'){
          $batch_status_reason_label = 'Describe UnHold batch reason here';
          $batch_status_action = 'UnHold';
        }

        $this->form_validation->set_rules('batch_status_reason', $batch_status_reason_label, 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
        
        if($this->form_validation->run())
        {    
          if($form_data[0]['batch_status'] == $admin_batch_status)
          {
            $this->session->set_flashdata('error','The status was already updated.');
            redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
            exit;
          }

          $posted_arr = json_encode($_POST);
          $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');  
           
          // Insert status action record in "iibfbcbf_agency_batch_status_action" table
          $insert_batch_status_action_data['agency_id'] = $form_data[0]['agency_id']; 
          $insert_batch_status_action_data['centre_id'] = $form_data[0]['centre_id']; 
          $insert_batch_status_action_data['batch_id'] = $batch_id; 
          $insert_batch_status_action_data['batch_status'] = $admin_batch_status; 
          $insert_batch_status_action_data['batch_status_reason'] = $this->input->post('batch_status_reason'); 
          $insert_batch_status_action_data['action_by'] = $this->login_admin_id; 
          $insert_batch_status_action_data['created_on'] = date("Y-m-d H:i:s"); 
          
          if($this->master_model->insertRecord('iibfbcbf_agency_batch_status_action',$insert_batch_status_action_data))
          {
            //Update status in "iibfbcbf_agency_centre_batch" table
            $update_agency_centre_batch_data = array('batch_status'  => $admin_batch_status);
            $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$update_agency_centre_batch_data,array('batch_id' => $batch_id));

            //START : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL
            if(in_array($admin_batch_status, array(3,5,7))) // 3=>GO AHEAD/APPROVED, 5=> REJECTED, 7=> CANCELLED
            {
              $this->Iibf_bcbf_model->send_batch_action_email_sms($batch_id, $admin_batch_status, 'Admin');
            }//END : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL
            
            //Insert Log into "iibfbcbf_logs" table
            $this->Iibf_bcbf_model->insert_common_log('Admin : Batch Status Action', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The Batch '.$batch_status_action.' by the admin '.$dispName['disp_name']." : ".$this->input->post('batch_status_reason'), $posted_arr);
          }

          $this->session->set_flashdata('success','Batch '.$batch_status_action.' successfully');
          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
        }
      }//END : SUBMIT BATCH STATUS
      else if(isset($_POST) && count($_POST) > 0 && isset($_POST["form_action"]) && $_POST['form_action'] == 'assign_inspector_action' && $this->login_user_type == 'admin') //START : ASSIGN BATCH INSPECTOR
      {
        if(date('Y-m-d') <= $form_data[0]['batch_end_date']) 
        {
          $this->form_validation->set_rules('inspector_id', 'inspector', 'trim|required|callback_validation_check_assign_inspector['.$form_data[0]['inspector_id'].']|xss_clean', array('required'=>"Please select the %s"));
          if($this->form_validation->run())
          {          
            $posted_arr = json_encode($_POST);
            $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');  
            
            // Insert status action record in "iibfbcbf_agency_batch_status_action" table
            $add_inspector['inspector_id'] = $inspector_id = $this->input->post('inspector_id');           
            $add_inspector['ip_address'] = get_ip_address(); //general_helper.php  
            $add_inspector['updated_on'] = date("Y-m-d H:i:s"); 
            $add_inspector['updated_by'] = $this->login_admin_id; 
            $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$add_inspector,array('batch_id' => $batch_id));
            
            $get_inspector_name = $this->master_model->getRecords("iibfbcbf_inspector_master im", array('im.inspector_id'=>$inspector_id), 'im.inspector_name');
            $this->Iibf_bcbf_model->insert_common_log('Admin : Batch Assign Inspector', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The inspector '.$get_inspector_name[0]['inspector_name'].' has successfully assigned to batch by the admin '.$dispName['disp_name'].'.', $posted_arr);

            //SEND MAIL TO INSPECTOR
            $this->Iibf_bcbf_model->send_notification_to_inspector($inspector_id,$batch_id,'Regular');

            $this->session->set_flashdata('success','Inspector successfully assigned to batch');
            redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
          }
        }
        else
        {
          $this->session->set_flashdata('error','You can not assign Inspector to the batch after batch end date');
          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
        }
      }//END : ASSIGN BATCH INSPECTOR
      else if(isset($_POST) && count($_POST) > 0 && isset($_POST["form_action"]) && $_POST['form_action'] == 'inspection_report_action' && $this->login_user_type == 'admin') //START : ADD / UPDATE INSPECTION REPORT
      {
        /* _pa($_POST);
        _pa($_FILES,1); */
        if(in_array($form_data[0]['batch_status'], array(3,7)) && $form_data[0]['inspector_id'] > 0)
        {
          $this->form_validation->set_rules('inspection_report_by_admin', 'inspection report', 'trim|required|callback_fun_validate_file_upload[inspection_report_by_admin|y|pdf,doc,docx,jpg,png,jpeg|5000|inspection report]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb|display name'  

          if($this->form_validation->run())
          {
            $error_flag = 0;
            $add_inspection_report = array();
            if($_FILES['inspection_report_by_admin']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name = "inspection_report_by_admin_".date("YmdHis").'_'.rand(1000,9999);
              $upload_data1 = $this->Iibf_bcbf_model->upload_file("inspection_report_by_admin", array('pdf','doc','docx','jpg','png','jpeg'), $new_file_name, "./".$inspection_report_by_admin_file_path, "pdf|doc|docx|jpg|png|jpeg", '', '', '', '', '5000');
              if($upload_data1['response'] == 'error')
              {
                $data['inspection_report_by_admin_error'] = $upload_data1['message'];
                $error_flag = 1;
              }
              else if($upload_data1['response'] == 'success')
              {
                $add_inspection_report['inspection_report_by_admin'] = $inspection_report_by_admin_file = $new_inspection_report_by_admin_file = $upload_data1['message'];
              }
            } 
            
            if($error_flag == 1)
            {
              @unlink("./".$inspection_report_by_admin_file_path."/".$upload_data1['message']);
            }
            else if($error_flag == 0)
            {
              $posted_arr = json_encode($_POST);
              $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');  

              if(count($add_inspection_report) > 0)
              {
                $this->Iibf_bcbf_model->check_file_exist($new_inspection_report_by_admin_file, "./".$inspection_report_by_admin_file_path."/", 'iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id, 'Inspection report not added/updated due to missing file');//IF inspection_report_by_admin_file NOT EXIST WHILE UPDATING THE RECORD, THEN SHOW ERROR MESSAGE                
                
                $add_inspection_report['ip_address'] = get_ip_address(); //general_helper.php  
                $add_inspection_report['updated_on'] = date("Y-m-d H:i:s"); 
                $add_inspection_report['updated_by'] = $this->login_admin_id;
                $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$add_inspection_report,array('batch_id' => $batch_id));
                
                $log_text_mode = 'added';
                if($this->input->post('inspection_report_mode') == 'Update')
                {
                  @unlink("./".$inspection_report_by_admin_file_path."/".$form_data[0]['inspection_report_by_admin']);
                  $log_text_mode = 'updated';
                }
                
                $this->Iibf_bcbf_model->insert_common_log('Admin : Inspection Report by Admin', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The inspection report has successfully '.$log_text_mode.' by the admin '.$dispName['disp_name'].'.', $posted_arr);

                $this->session->set_flashdata('success','Inspecton report successfully '.$log_text_mode.' to batch');
              }
              else
              {
                $this->session->set_flashdata('error','Error occurred. Please try again later.');
              }
              redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
            }            
          }
        }
        else
        {
          $this->session->set_flashdata('error','You can not add inspection report to this batch');
          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
        }
      }//END : ADD / UPDATE INSPECTION REPORT
      else if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_action']) && $_POST['form_action'] == 'extend_date_action' && $this->login_user_type == 'admin' && date('Y-m-d') <= $form_data[0]['batch_end_date'] && $form_data[0]['batch_status'] == '3')//START : EXTEND DATE FOR ADD/UPDATE CANDIDATES
      {
        $this->form_validation->set_rules('batch_extend_type', 'type', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_extend_date', 'date', 'trim|required|callback_fun_check_extend_date['.$batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin'); 

          $up_data = array();
          $up_data['batch_extend_date'] = $batch_extend_date = $this->input->post('batch_extend_date');
          $up_data['batch_extend_type'] = $batch_extend_type = $this->input->post('batch_extend_type');
          $up_data['ip_address'] = get_ip_address(); //general_helper.php  
          $up_data['updated_on'] = date("Y-m-d H:i:s"); 
          $up_data['updated_by'] = $this->login_admin_id;
          $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$up_data,array('batch_id' => $batch_id));
          
          $log_text = 'Add/Update candidate';
          if($batch_extend_type == 2) { $log_text = 'Only Update candidate'; }

          $this->Iibf_bcbf_model->insert_common_log('Admin : Extend date for add/update candidate', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','Batch '.$log_text.' date successfully extended to '.$batch_extend_date.' by the admin '.$centreName['disp_name'], $posted_arr);
              
          $this->session->set_flashdata('success','Batch Add/Update candidate date successfully extended');

          redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
        } 
      } //END : EXTEND DATE FOR ADD/UPDATE CANDIDATES

      $inspector_data = array();
      if($form_data[0]['batch_online_offline_flag'] == '1') //offline
      {
        $this->db->join('iibfbcbf_inspector_centres ic','ic.inspector_id = im.inspector_id','INNER');      
        $this->db->where('ic.city',$form_data[0]['centre_city']);           
      }      
      $inspector_data = $this->master_model->getRecords("iibfbcbf_inspector_master im", array('im.is_deleted'=>'0', 'im.is_active'=>'1', 'im.batch_online_offline_flag'=>$form_data[0]['batch_online_offline_flag']), 'im.inspector_id, im.inspector_name');      
      
      $data['inspector_data'] = $inspector_data;

      $this->load->view('iibfbcbf/admin/training_batch_details_admin', $data);
    }/******** END : FACULTY DETAILS PAGE ********/

    function clear_extended_date($enc_batch_id=0)
    {
      $batch_id = url_decode($enc_batch_id);
      
      $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.is_deleted' => '0', 'acb.batch_status !='=> '0', 'acb.batch_status !='=> '8', 'acb.batch_extend_type !='=>'0'), "acb.*");
            
      if(count($form_data) == 0) 
      { 
        $this->session->set_flashdata('error','Error occurred. Please try again.'); 
        redirect(site_url('iibfbcbf/admin/training_batches')); 
      }

      $posted_arr = json_encode($_POST);
      $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin'); 

      $up_data = array();
      $up_data['batch_extend_date'] = NULL;
      $up_data['batch_extend_type'] = '0';
      $up_data['ip_address'] = get_ip_address(); //general_helper.php  
      $up_data['updated_on'] = date("Y-m-d H:i:s"); 
      $up_data['updated_by'] = $this->login_admin_id;
      $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$up_data,array('batch_id' => $batch_id));
      
      $this->Iibf_bcbf_model->insert_common_log('Admin : Cleared extended date for add/update candidate', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','Batch Add/Update candidate date successfully cleared by the admin '.$centreName['disp_name'], $posted_arr);
          
      $this->session->set_flashdata('success','Batch Add/Update candidate date successfully cleared');

      redirect(site_url('iibfbcbf/admin/training_batches/training_batch_details/'.$enc_batch_id));
    }

    function validation_check_assign_inspector($str='',$previous_inspector_id=0)
    {  
      $inspector_id = $str; 
      if($inspector_id != $previous_inspector_id)
      {
        return TRUE;
      }
      else
      {
        $this->form_validation->set_message('validation_check_assign_inspector','The selected inspector is already assigned to this batch. Please select different inspector');
        return false;
      }
    }

    function fun_check_extend_date($str, $batch_id='') // Custom callback function for check extend date
    {
      if($str != '')
      {
        $current_val = $str;
        
        $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $batch_id, 'is_deleted' => '0'), 'batch_start_date, batch_end_date');
        
        if(count($batch_data) > 0) 
        { 
          $extend_date = $current_val;
          $batch_chk_date_start = $batch_data[0]['batch_start_date'];
          $batch_chk_date_end = $batch_data[0]['batch_end_date'];
        
          if($extend_date < $batch_chk_date_start || $extend_date > $batch_chk_date_end)
          {
            $this->form_validation->set_message('fun_check_extend_date', "Please Select the Date between ".$batch_chk_date_start." and ".$batch_chk_date_end.".");
            return false;
          }
          else { return true; }  
        }
        else { return true; }               
      }
      else { return true; }       
    }

    /******** START : LOAD CENTERS ********/ 
    public function load_centre_data() 
    {      
      $flag = "success";
      $response = ''; 
      $html = '<option value="">Select Centre</option>';
      if(isset($_POST) && isset($_POST['s_agency']))
      { 
        $s_agency = $this->security->xss_clean($this->input->post('s_agency'));       
        if($s_agency != "") { $this->db->where('cm.agency_id',$s_agency); }
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username', array('cm.centre_name'=>'ASC'));

        if(count($agency_centre_data) > 0)
        {
          foreach($agency_centre_data as $res){
            $html .= '<option value="'.$res['centre_id'].'">'.$res['centre_name']." (".$res['centre_username']." - ".$res['city_name'].")".'</option>';
          }  
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $html; 
      echo json_encode($result);  
    } /******** END : LOAD CENTERS ********/
 
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

    function test_notification_to_inspoector()
    {
      $this->Iibf_bcbf_model->send_notification_to_inspector(3,53,'Reminder');
    }
  } ?>  