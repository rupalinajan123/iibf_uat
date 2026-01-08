<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Centre Batches Master
  ** Created BY: Sagar Matale On 17-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Training_batches_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file'); 
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout
       
      $this->no_of_hours_basic = '28'; //28 Hours 
      $this->no_of_hours_advance = '42'; //42 Hours 
      $this->chk_gross_training_days_basic = '15'; //15 Days
      $this->chk_gross_training_days_advance = '28'; //28 Days 
      $this->chk_gross_training_time_per_day = '8'; //8 Hours 
      $this->chk_total_break_time = '90'; //90 Minutes 
      $this->chk_min_net_training_time_per_day = '2'; //2 Hours 
      $this->chk_net_training_time_per_day = '7'; //7 Hours 
      $this->chk_total_net_training_time_of_duration_basic = '28'; //28 Hours 
      $this->chk_total_net_training_time_of_duration_advance = '42'; //42 Hours 
      $this->chk_total_batch_candidates = '35'; //35 Candidates

      $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';
      $this->inspection_report_by_admin_file_path = 'uploads/iibfbcbf/inspection_report_by_admin';

      //$this->batches_id_edit_candidate_arr = array('00'=>'2023-12-01'); //array('batch id'=>'batch add/edit date'); array('1'=>'2023-11-30', '2'=>'2023-12-01');

      $this->agency_id = 0;
      if($this->login_user_type == 'agency') 
      { 
        $this->agency_id = $this->login_agency_or_centre_id; 
      }
      else if($this->login_user_type == 'centre')
      {
        $agency_id_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $this->login_agency_or_centre_id), "agency_id");
        if(count($agency_id_data) > 0)
        {
          $this->agency_id = $agency_id_data[0]['agency_id'];
        }
      }

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->agency_id), "agency_id, allow_exam_codes, allow_exam_types");
      
      if(count($agency_data) > 0)
      {
        if($agency_data[0]['allow_exam_types'] == 'CSC')
        {
          $this->session->set_flashdata('error','You do not have permission to access Training Batches module');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
    }
    
    public function index($search_batch_current_state='')
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";
      $data['search_batch_current_state'] = $search_batch_current_state;

      $data['agency_centre_data'] = array();
      if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id; 

        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');
      }
      
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Training Batches';
      $this->load->view('iibfbcbf/agency/training_batches_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRAINING BATCHES DATA ********/
    public function get_training_batches_agency_data_ajax()
    {
      $table = 'iibfbcbf_agency_centre_batch acb';
      
      $column_order = array('acb.batch_id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", "")) AS DispBatchType', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date) AS BatchDate', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted")))))))) AS DispBatchStatus', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag, acb.agency_id, acb.batch_start_date, acb.batch_end_date'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")")', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", ""))', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date)', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))'); //SET COLUMN FOR SEARCH
      $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER
      
      if($this->login_user_type == 'centre')
      {
        $WhereForTotal = "WHERE acb.centre_id = '".$this->login_agency_or_centre_id."' AND acb.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE acb.centre_id = '".$this->login_agency_or_centre_id."' AND acb.is_deleted = 0 	";
      }
      else if($this->login_user_type == 'agency')
      {
        $WhereForTotal = "WHERE acb.agency_id = '".$this->login_agency_or_centre_id."' AND acb.is_deleted = 0 AND cm.is_deleted = 0 AND acb.batch_status != '0' AND acb.batch_status != '8' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE acb.agency_id = '".$this->login_agency_or_centre_id."' AND acb.is_deleted = 0 AND cm.is_deleted = 0 AND acb.batch_status != '0' AND acb.batch_status != '8' ";
      }
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND acb.centre_id = '".$s_centre."'"; } 

      $s_batch_current_state = trim($this->security->xss_clean($this->input->post('s_batch_current_state')));
      if($s_batch_current_state != "") 
      { 
        if($s_batch_current_state == '1') //Completed
        {
          $Where .= " AND acb.batch_end_date < '".date('Y-m-d')."'";
        }
        else if($s_batch_current_state == '2') //Ongoing
        {
          $Where .= " AND acb.batch_start_date <= '".date('Y-m-d')."' AND acb.batch_end_date >= '".date('Y-m-d')."'";          
        }
        else if($s_batch_current_state == '3') //Upcoming
        {
          $Where .= " AND acb.batch_start_date > '".date('Y-m-d')."'";
        }
      } 

      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."') AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; 
      }
      else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; 
      }
      else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 
      
      $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
      if($s_batch_code != "") { $Where .= " AND acb.batch_code = '".custom_safe_string($s_batch_code)."'"; } //iibfbcbf/iibf_bcbf_helper.php
      
      $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
      if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
            
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
        $row[] = $Res['DispCentreName'];
        $row[] = $Res['batch_code'];
        $row[] = $Res['centre_batch_id'];
        $row[] = $Res['DispBatchType'];
        $row[] = $Res['BatchDate'];
        $row[] = $Res['BatchTime'];
        $row[] = $Res['total_candidates'];
        $row[] = $Res['created_on'];
        
        $row[] = '<span class="badge '.show_batch_status($Res['batch_status']).'" style="min-width:90px;">'.$Res['DispBatchStatus'].'</span>';

        $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$Res['agency_id'].'" AND centre_id = "'.$Res['centre_id'].'" AND batch_id = "'.$Res['batch_id'].'" AND is_deleted="0" ');
        $batch_candidate_count = $batch_candidate_qry->num_rows();
        
        $btn_str = ' <div class="text-left no_wrap"> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/'.url_encode($Res['batch_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        if($this->login_user_type == 'centre') //only centre can edit batches
        {
          if($Res['batch_status'] == 0 && $this->calculate_batch_start_date() <= $Res['batch_start_date'])
          {
            $onclick_send_for_approval = "sweet_alert_send_for_approval_confirm('".url_encode($Res['batch_id'])."')";

            $btn_str .= '<a href="javascript:void(0)" onclick="'.$onclick_send_for_approval.'" class="btn btn-info btn-xs" title="Submit Batch to Agency for Final Approval"><i class="fa fa-paper-plane" aria-hidden="true"></i></a> ';
          }

          if($Res['batch_status'] == 0 || $Res['batch_status'] == 1 || $Res['batch_status'] == 2 || $Res['batch_status'] == 6 || $Res['batch_status'] == 8)
          {
            $btn_str .= '<a href="'.site_url('iibfbcbf/agency/training_batches_agency/add_training_batch_agency/'.url_encode($Res['batch_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
          }

          if($Res['batch_status'] == '3')
          {
            //$chk_batch_start_date = get_add_candidate_date($this->batches_id_edit_candidate_arr, $Res['batch_id'], $Res['batch_start_date']);
            
            $chk_batch_start_date = get_add_candidate_date($this->Iibf_bcbf_model->extend_candidate_add_update_date_arr(), $Res['batch_id'], $Res['batch_start_date']);//ADDED BY SAGAR TO ALLOW AGENCY TO ADD/EDIT CANDIDATE AFTER START DATE IS OVER  //iibfbcbf/iibf_bcbf_helper.php           

            //batch_start_date >= current_date && 'total candidate added against batch' < total_candidates
            if($chk_batch_start_date >= date('Y-m-d') && $batch_candidate_count < $Res['total_candidates'])
            {
              $btn_str .= '<a href="'.site_url('iibfbcbf/agency/batch_candidates_agency/add_candidates_agency/'.url_encode($Res['batch_id'])).'" class="btn btn-warning btn-xs" title="Add Candidate "><i class="fa fa-user-circle-o" aria-hidden="true"></i></a> ';
            }            
          }
        }
        
        //show candidate list button only when there is any candidate available in the batch
        if($batch_candidate_count > 0)
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/agency/batch_candidates_agency/index/'.url_encode($Res['batch_id'])).'" class="btn btn-danger btn-xs" title="Candidate List"><i class="fa fa-users" aria-hidden="true"></i></a> ';
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
  	    
    /******** START : ADD / UPDATE TRAINING BATCHES DATA ********/
    public function add_training_batch_agency($enc_batch_id=0)
    {   
      if($this->login_user_type != 'centre') //only centre can add batches
      {
        $this->session->set_flashdata('error','You do not have permission to access the Add Training Batch module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }

      $agency_id = '0';
      $centre_id = '0';
      if($this->login_user_type == 'agency') { $agency_id = $this->login_agency_or_centre_id; }
      else if($this->login_user_type == 'centre')
      {
        $centre_id = $this->login_agency_or_centre_id;

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$centre_id), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name, cm.centre_name, am.agency_name');
        
        if(count($centre_data) > 0) { $agency_id = $centre_data[0]['agency_id']; }
      }

      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";

      $data['no_of_hours_basic'] = $this->no_of_hours_basic; 
      $data['no_of_hours_advance'] = $this->no_of_hours_advance; 
      $data['chk_gross_training_days_basic'] = $this->chk_gross_training_days_basic;
      $data['chk_gross_training_days_advance'] = $this->chk_gross_training_days_advance ;
      $data['chk_gross_training_time_per_day'] = $this->chk_gross_training_time_per_day;
      $data['chk_total_break_time'] = $this->chk_total_break_time;
      $data['chk_min_net_training_time_per_day'] = $this->chk_min_net_training_time_per_day;
      $data['chk_net_training_time_per_day'] = $this->chk_net_training_time_per_day;
      $data['chk_total_net_training_time_of_duration_basic'] = $this->chk_total_net_training_time_of_duration_basic;
      $data['chk_total_net_training_time_of_duration_advance'] = $this->chk_total_net_training_time_of_duration_advance;           
      $data['chk_total_batch_candidates'] = $this->chk_total_batch_candidates;           

      $data['enc_batch_id'] = $enc_batch_id;
      $data['training_schedule_file_error'] = '';
      $data['training_schedule_file_path'] = $training_schedule_file_path = $this->training_schedule_file_path;
      $new_training_schedule_file = '';
      
      if($enc_batch_id == '0') 
      { 
        $data['mode'] = $mode = "Add"; $batch_id = $enc_batch_id; 
        $data['chk_batch_start_date'] = $this->calculate_batch_start_date();
      }
      else
      {
        $batch_id = url_decode($enc_batch_id);
        
        $this->db->where(" (batch_status = '0' OR batch_status = '1' OR batch_status = '2' OR batch_status = '6' OR batch_status = '8') ");
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.centre_id' => $this->login_agency_or_centre_id, 'acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.*, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', IF(acb.batch_status = 7, 'Cancelled', 'Drafted')))))))) AS DispBatchStatus");        
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/training_batches_agency/add_training_batch_agency')); }
        
        $data['mode'] = $mode = "Update";

        //$data['chk_batch_start_date'] = $this->calculate_batch_start_date(date("Y-m-d",strtotime($form_data[0]['created_on'])));
        $data['chk_batch_start_date'] = $this->calculate_batch_start_date();

        $bank_field_id_arr = $bank_name_arr = $cand_src_arr = array();
        $bank_field_arr = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted' => '0'));
        if(count($bank_field_arr) > 0)
        {
          foreach($bank_field_arr as $res)
          {
            $bank_field_id_arr[] = $res['bank_id'];
            $bank_name_arr[] = $res['bank_name'];
            $cand_src_arr[] = $res['cand_src'];
          }
        }
        $data['form_bank_field_id_arr'] = $form_bank_field_id_arr = $bank_field_id_arr; 
        $data['form_bank_name_arr'] = $bank_name_arr;
        $data['form_cand_src_arr'] = $cand_src_arr;


        $field_id_arr = $login_id_arr = $password_arr = array();
        $field_arr = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted' => '0'));
        if(count($field_arr) > 0)
        {
          foreach($field_arr as $res)
          {
            $field_id_arr[] = $res['user_id'];
            $login_id_arr[] = $res['login_id'];
            $password_arr[] = $res['password'];
          }
        }
        $data['form_field_id_arr'] = $form_field_id_arr = $field_id_arr; 
        $data['form_login_id_arr'] = $login_id_arr;
        $data['form_password_arr'] = $password_arr;        
      }
      
      if(isset($_POST) && count($_POST) > 0)
      {
        $training_schedule_file_flg = 'n';
        if($mode == 'Add') { $training_schedule_file_flg = 'y'; }
        else { if($form_data[0]['training_schedule_file'] == "") { $training_schedule_file_flg = 'y'; } }

        $this->form_validation->set_rules('batch_type', 'batch type', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_hours', 'no. of hours', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|xss_clean', array('required'=>"Please enter the %s"));        
        $this->form_validation->set_rules('batch_start_date', 'batch training from date', 'trim|required|callback_fun_check_calculated_readonly_values[batch_start_date####'.$batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_end_date', 'batch training to date', 'trim|required|callback_fun_check_calculated_readonly_values[batch_end_date####'.$batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_gross_days', 'gross training days', 'trim|callback_fun_restrict_input[allow_only_numbers]|callback_fun_check_calculated_readonly_values[batch_gross_days]|xss_clean');
        $this->form_validation->set_rules('batch_holidays', 'holidays', 'trim|xss_clean');
        $this->form_validation->set_rules('batch_net_days', 'net training days', 'trim|callback_fun_check_calculated_readonly_values[batch_net_days]|xss_clean');
        $this->form_validation->set_rules('batch_daily_start_time', 'daily training start time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_daily_end_time', 'daily training end time', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('batch_daily_gross_time', 'gross training time per day', 'trim|callback_fun_check_calculated_readonly_values[batch_daily_gross_time]|xss_clean');
        $this->form_validation->set_rules('break_start_time1', 'break start time1', 'trim|required|callback_fun_check_valid_break_time_between[break_start_time1]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('break_end_time1', 'break end time1', 'trim|required|callback_fun_check_valid_break_end_time[break_end_time1]|callback_fun_check_valid_break_time_between[break_end_time1]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('break_start_time2', 'break start time2', 'trim|required|callback_fun_check_valid_break_time_between[break_start_time2]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('break_end_time2', 'break end time2', 'trim|required|callback_fun_check_valid_break_end_time[break_end_time2]|callback_fun_check_valid_break_time_between[break_end_time2]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('break_start_time3', 'break start time3', 'trim|required|callback_fun_check_valid_break_time_between[break_start_time3]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('break_end_time3', 'break end time3', 'trim|required|callback_fun_check_valid_break_end_time[break_end_time3]|callback_fun_check_valid_break_time_between[break_end_time3]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('total_daily_break_time', 'total break time', 'trim|callback_fun_restrict_input[allow_only_numbers]|callback_fun_check_calculated_readonly_values[total_daily_break_time]|xss_clean');
        $this->form_validation->set_rules('batch_daily_net_time', 'net training time per day', 'trim|callback_fun_check_calculated_readonly_values[batch_daily_net_time]|xss_clean');
        $this->form_validation->set_rules('batch_total_net_time', 'total net training time of duration', 'trim|callback_fun_check_calculated_readonly_values[batch_total_net_time]|xss_clean');
        $this->form_validation->set_rules('training_language', 'training language', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('under_graduate_candidates', 'under graduate candidates', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('graduate_candidates', 'graduate candidates', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('post_graduate_candidates', 'post graduate candidates', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('total_candidates', 'total candidates', 'trim|callback_fun_restrict_input[allow_only_numbers]|callback_fun_check_calculated_readonly_values[total_candidates]|xss_clean');
        $this->form_validation->set_rules('first_faculty', 'faculty1', 'trim|required|callback_validation_faculty_availability['.$enc_batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('second_faculty', 'faculty2', 'trim|required|callback_validation_faculty_availability['.$enc_batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('third_faculty', 'faculty3', 'trim|callback_validation_faculty_availability['.$enc_batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('fourth_faculty', 'faculty4', 'trim|callback_validation_faculty_availability['.$enc_batch_id.']|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('training_schedule_file', 'training schedule', 'callback_fun_validate_file_upload[training_schedule_file|'.$training_schedule_file_flg.'|txt,doc,docx,pdf|5000|training schedule]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb|display name'  
        $this->form_validation->set_rules('contact_person_name', 'batch coordinator name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('contact_person_mobile', 'batch coordinator mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_fun_restrict_input[first_zero_not_allowed]|min_length[10]|max_length[10]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('alt_contact_person_name', '', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean');
        $this->form_validation->set_rules('alt_contact_person_mobile', '', 'trim|callback_fun_restrict_input[allow_only_numbers]|callback_fun_restrict_input[first_zero_not_allowed]|min_length[10]|max_length[10]|xss_clean');        
        $this->form_validation->set_rules('bank_name_arr[]', 'source of candidates (Bank/Agency)', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[30]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('cand_src_arr[]', 'number of candidates', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|xss_clean', array('required'=>"Please enter the %s"));/* callback_fun_restrict_input[first_zero_not_allowed]|max_length[3]| */
        $this->form_validation->set_rules('remarks', 'remarks', 'trim|max_length[1000]|xss_clean');
        $this->form_validation->set_rules('batch_online_offline_flag', 'batch infrastructure', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        $batch_online_offline_flag = $this->security->xss_clean($this->input->post('batch_online_offline_flag'));
        if($batch_online_offline_flag == '2')
        {
          $this->form_validation->set_rules('online_training_platform', 'name of the online training platform used', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[50]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('platform_link', 'link', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('login_id_arr[]', 'login id', 'trim|required|max_length[50]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('password_arr[]', 'password', 'trim|required|max_length[50]|xss_clean', array('required'=>"Please enter the %s")); 
        }

        //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        {
          /* echo 'in'; 
          _pa($_FILES);           
          _pa($_POST,1); 
          exit; */
          $error_flag = 0;
          if($_FILES['training_schedule_file']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_file_name = "training_batch_schedule_".date("YmdHis").'_'.rand(1000,9999);
            $upload_data1 = $this->Iibf_bcbf_model->upload_file("training_schedule_file", array('txt','doc','docx','pdf'), $new_file_name, "./".$training_schedule_file_path, "txt|doc|docx|pdf", '', '', '', '', '5000');
            if($upload_data1['response'] == 'error')
            {
              $data['training_schedule_file_error'] = $upload_data1['message'];
              $error_flag = 1;
            }
            else if($upload_data1['response'] == 'success')
            {
              $add_data['training_schedule_file'] = $training_schedule_file = $new_training_schedule_file = $upload_data1['message'];
            }
          } 
          
          if($error_flag == 1)
          {
            @unlink("./".$training_schedule_file_path."/".$upload_data1['message']);
          }
          else if($error_flag == 0)
          {
            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($centre_id, 'centre');

            $batch_type = $this->input->post('batch_type');
            if($mode == 'Update' && $form_data[0]['batch_status'] != '8') { $batch_type = $form_data[0]['batch_type']; }
            
            //echo 'IN';exit;
            $add_data['agency_id'] = $agency_id;
            $add_data['centre_id'] = $centre_id;
            $add_data['batch_type'] = $batch_type;
            $add_data['batch_hours'] = $this->input->post('batch_hours');
            $add_data['batch_start_date'] = $batch_start_date = $this->input->post('batch_start_date');
            $add_data['batch_end_date'] = $this->input->post('batch_end_date');
            $add_data['batch_gross_days'] = $this->input->post('batch_gross_days');
            $add_data['batch_holidays'] = $this->sort_holidays_dates($this->input->post('batch_holidays'));
            $add_data['batch_net_days'] = $this->input->post('batch_net_days');
            $add_data['batch_daily_start_time'] = date("H:i",strtotime($this->input->post('batch_daily_start_time')));
            $add_data['batch_daily_end_time'] = date("H:i",strtotime($this->input->post('batch_daily_end_time')));
            $add_data['batch_daily_gross_time'] = $this->input->post('batch_daily_gross_time');
            $add_data['break_start_time1'] = date("H:i",strtotime($this->input->post('break_start_time1')));
            $add_data['break_end_time1'] = date("H:i",strtotime($this->input->post('break_end_time1')));
            $add_data['break_start_time2'] = date("H:i",strtotime($this->input->post('break_start_time2')));
            $add_data['break_end_time2'] = date("H:i",strtotime($this->input->post('break_end_time2')));
            $add_data['break_start_time3'] = date("H:i",strtotime($this->input->post('break_start_time3')));
            $add_data['break_end_time3'] = date("H:i",strtotime($this->input->post('break_end_time3')));
            $add_data['total_daily_break_time'] = $this->input->post('total_daily_break_time');
            $add_data['batch_daily_net_time'] = $this->input->post('batch_daily_net_time');
            $add_data['batch_total_net_time'] = $this->input->post('batch_total_net_time');
            $add_data['training_language'] = $this->input->post('training_language');
            $add_data['under_graduate_candidates'] = $this->input->post('under_graduate_candidates');
            $add_data['graduate_candidates'] = $this->input->post('graduate_candidates');
            $add_data['post_graduate_candidates'] = $this->input->post('post_graduate_candidates');
            $add_data['total_candidates'] = $this->input->post('total_candidates');
            $add_data['first_faculty'] = $this->input->post('first_faculty');
            $add_data['second_faculty'] = $this->input->post('second_faculty');
            $add_data['third_faculty'] = $this->input->post('third_faculty');
            $add_data['fourth_faculty'] = $this->input->post('fourth_faculty');
            $add_data['contact_person_name'] = $this->input->post('contact_person_name');
            $add_data['contact_person_mobile'] = $this->input->post('contact_person_mobile');
            $add_data['alt_contact_person_name'] = $this->input->post('alt_contact_person_name');
            $add_data['alt_contact_person_mobile'] = $this->input->post('alt_contact_person_mobile');
            $add_data['remarks'] = $this->input->post('remarks');
            $add_data['batch_online_offline_flag'] = $batch_online_offline_flag = $this->input->post('batch_online_offline_flag');

            if($batch_online_offline_flag == 2)
            {
              $add_data['online_training_platform'] = $this->input->post('online_training_platform');
              $add_data['platform_link'] = $this->input->post('platform_link');
            }
            else
            {
              $add_data['online_training_platform'] = '';
              $add_data['platform_link'] = '';
            }

            $add_data['ip_address'] = get_ip_address(); //general_helper.php            
            if($mode == "Add") 
            {
              $add_data['batch_status'] = '0';
              $add_data['created_on'] = date("Y-m-d H:i:s");
              $add_data['created_by'] = $this->login_agency_or_centre_id;

              $this->Iibf_bcbf_model->check_file_exist($training_schedule_file, "./".$training_schedule_file_path."/", 'iibfbcbf/agency/training_batches_agency', 'Batch record not added due to missing training schedule file');//IF training_schedule_file NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              
              $this->master_model->insertRecord('iibfbcbf_agency_centre_batch ',$add_data);
              $batch_id = $this->db->insert_id();

              if($batch_id > 0)
              {
                $this->Iibf_bcbf_model->insert_common_log('Centre : Batch Added', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch has successfully added by the centre '.$centreName['disp_name'], $posted_arr);
                
                $this->session->set_flashdata('success',"Batch record added successfully.<br>Process of <span style='font-weight: bold;'>Review Batch and Submit to Agency</span> must be done by end of <span style='font-weight: bold;'>".date('d M Y', strtotime($this->calculate_review_batch_and_submit_to_iibf_date($batch_start_date)))."</span>.");
              }
              else
              {
                $this->Iibf_bcbf_model->insert_common_log('Centre : Batch Added', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','Error occurred while adding the batch by the centre '.$centreName['disp_name'], $posted_arr);
                
                $this->session->set_flashdata('error',"Error occurred. Please try again.");
                redirect(site_url('iibfbcbf/agency/training_batches_agency'));
              }
            }
            else if($mode == "Update")
            {
              $chk_training_schedule_file = '';
              if($new_training_schedule_file == '') { $chk_training_schedule_file = $form_data[0]['training_schedule_file']; }
              else if($new_training_schedule_file != '') { $chk_training_schedule_file = $new_training_schedule_file; }

              $this->Iibf_bcbf_model->check_file_exist($chk_training_schedule_file, "./".$training_schedule_file_path."/", 'iibfbcbf/agency/training_batches_agency', 'Batch record not added due to missing training schedule file');//IF training_schedule_file NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE

              if($form_data[0]['batch_status'] == '2') //IF BATCH STATUS IS Batch Error AND CENTER AGAIN UPDATE THE RECORD THEN MAKE BATCH STATUS AS RE-SUBMITTED
              {
                $add_data['batch_status'] = '6';
              }
              else if($form_data[0]['batch_status'] == '8') //IF BATCH STATUS IS DRAFTED AND CENTER UPDATE THE RECORD THEN MAKE BATCH STATUS AS 0
              {
                $add_data['batch_status'] = '0';
              }

              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_agency_or_centre_id;            
              $this->master_model->updateRecord('iibfbcbf_agency_centre_batch', $add_data, array('batch_id'=>$batch_id));
                            
              if($new_training_schedule_file != '') { @unlink("./".$training_schedule_file_path."/".$form_data[0]['training_schedule_file']); }

              $this->Iibf_bcbf_model->insert_common_log('Centre : Batch Updated', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch has successfully updated by the centre '.$centreName['disp_name'], $posted_arr);
							
              if($form_data[0]['batch_status'] == '8')
              {
                $this->session->set_flashdata('success',"Batch record added successfully.<br>Process of <span style='font-weight: bold;'>Review Batch and Submit to Agency</span> must be done by end of <span style='font-weight: bold;'>".date('d M Y', strtotime($this->calculate_review_batch_and_submit_to_iibf_date($batch_start_date)))."</span>.");
              }
              else
              {
                $this->session->set_flashdata('success','Batch record updated successfully.');
              }
            }
            
            if($batch_id > 0)
            {
              if($mode == 'Add' || $form_data[0]['batch_status'] == '8')
              {
                $this->generate_batch_code_batch_id($batch_type, $centre_data,$batch_id);
              }

              $bank_field_id_arr = $this->input->post('bank_field_id_arr');              
              $bank_name_arr = $this->input->post('bank_name_arr');
              $cand_src_arr = $this->input->post('cand_src_arr');
              
              if(count($bank_field_id_arr) > 0)
              {
                $i = 0;
                foreach($bank_field_id_arr as $res)
                {
                  $add_field = array();
                  $add_field['agency_id'] = $agency_id;
                  $add_field['batch_id'] = $batch_id;
                  $add_field['bank_name'] = $bank_name_arr[$i];
                  $add_field['cand_src'] = $cand_src_arr[$i];                  
                  $add_field['ip_address'] = get_ip_address(); //general_helper.php           

                  if($res == 0)
                  {
                    $add_field['is_active'] = '1';                    
                    $add_field['created_on'] = date("Y-m-d H:i:s");
                    $add_field['created_by'] = $this->login_agency_or_centre_id;
                    $this->master_model->insertRecord('iibfbcbf_batch_bak_name_cand_src_details',$add_field);
                  }
                  else
                  {                    
                    $add_field['updated_on'] = date("Y-m-d H:i:s");
                    $add_field['updated_by'] = $this->login_agency_or_centre_id;
                    $this->master_model->updateRecord('iibfbcbf_batch_bak_name_cand_src_details',$add_field, array('bank_id' => $res));
                  }
                  $i++;
                }
              }

              if($batch_online_offline_flag == 2)
              {
                $field_id_arr = $this->input->post('field_id_arr');              
                $login_id_arr = $this->input->post('login_id_arr');
                $password_arr = $this->input->post('password_arr');
                
                if(count($field_id_arr) > 0)
                {
                  $i = 0;
                  foreach($field_id_arr as $res)
                  {
                    $add_field = array();
                    $add_field['agency_id'] = $agency_id;
                    $add_field['batch_id'] = $batch_id;
                    $add_field['login_id'] = $login_id_arr[$i];
                    $add_field['password'] = $password_arr[$i];                  
                    $add_field['ip_address'] = get_ip_address(); //general_helper.php           

                    if($res == 0)
                    {
                      $add_field['is_active'] = '1';                    
                      $add_field['created_on'] = date("Y-m-d H:i:s");
                      $add_field['created_by'] = $this->login_agency_or_centre_id;
                      $this->master_model->insertRecord(' iibfbcbf_online_batch_user_details ',$add_field);
                    }
                    else
                    {                    
                      $add_field['updated_on'] = date("Y-m-d H:i:s");
                      $add_field['updated_by'] = $this->login_agency_or_centre_id;
                      $this->master_model->updateRecord(' iibfbcbf_online_batch_user_details ',$add_field, array('user_id' => $res));
                    }
                    $i++;
                  }
                }
              }

              if($mode == 'Update')//DELETE PREVIOUS user details
              {
                $old_bank_name_arr = $form_bank_field_id_arr;
                $current_bank_name_arr = $this->input->post('bank_field_id_arr');
                $delete_bank_arr = array_diff($old_bank_name_arr, $current_bank_name_arr);
                if(count($delete_bank_arr) > 0)
                {
                  foreach($delete_bank_arr as $del)
                  {
                    $del_data = array();
                    $del_data['is_deleted'] = '1';
                    $del_data['ip_address'] = get_ip_address(); //general_helper.php
                    $del_data['deleted_on'] = date("Y-m-d H:i:s");
                    $del_data['deleted_by'] = $this->login_agency_or_centre_id;
                    $this->master_model->updateRecord('iibfbcbf_batch_bak_name_cand_src_details',$del_data, array('bank_id' => $del));
                  }
                }


                if($batch_online_offline_flag == 2)
                {
                  $old_arr = $form_field_id_arr;
                  $current_arr = $this->input->post('field_id_arr');
                  $delete_arr = array_diff($old_arr, $current_arr);
                  if(count($delete_arr) > 0)
                  {
                    foreach($delete_arr as $del)
                    {
                      $del_data = array();
                      $del_data['is_deleted'] = '1';
                      $del_data['ip_address'] = get_ip_address(); //general_helper.php
                      $del_data['deleted_on'] = date("Y-m-d H:i:s");
                      $del_data['deleted_by'] = $this->login_agency_or_centre_id;
                      $this->master_model->updateRecord('iibfbcbf_online_batch_user_details',$del_data, array('user_id' => $del));
                    }
                  }
                }
              }

              if($mode == 'Update' && $batch_online_offline_flag == 1)
              {
                $delete_arr = $form_field_id_arr;
                if(count($delete_arr) > 0)
                {
                  foreach($delete_arr as $del)
                  {
                    $del_data = array();
                    $del_data['is_deleted'] = '1';
                    $del_data['ip_address'] = get_ip_address(); //general_helper.php
                    $del_data['deleted_on'] = date("Y-m-d H:i:s");
                    $del_data['deleted_by'] = $this->login_agency_or_centre_id;
                    $this->master_model->updateRecord('iibfbcbf_online_batch_user_details',$del_data, array('user_id' => $del));
                  }
                }
              }
            }
            redirect(site_url('iibfbcbf/agency/training_batches_agency'));
          }          
        }
      }	
      
      $data['page_title'] = 'IIBF - BCBF Centre '.$mode.' Batch'; 

      /* $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['centre_master_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id' => $this->login_agency_or_centre_id, 'cm.status'=>'1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.centre_city, cm1.city_name'); */

      $data['medium_master'] = $this->master_model->getRecords('iibfbcbf_exam_medium_master', array());
      $this->db->where(" (centre_id = '".$this->login_agency_or_centre_id."' OR centre_id = '0') ");
      $data['faculty_master'] = $this->master_model->getRecords('iibfbcbf_faculty_master', array('is_deleted'=>'0', 'status'=>'1', 'agency_id'=>$agency_id),'',array('faculty_name'=>'ASC'));
      
      $this->load->view('iibfbcbf/agency/add_training_batch_agency', $data);
    }/******** END : ADD / UPDATE TRAINING BATCHES DATA ********/

    function generate_batch_code_batch_id($batch_type='',$centre_data=array(),$batch_id=0)
    {
      //START : GENERATE BATCH CODE
      //Batch CODE should be automatically generated (Ex: BB1, BB2, BA1, BA2) 
      //[BB= Batch Basic, BA= Batch Advanced, Start from 1 with incremental value.]
      $total_batch_qry = $this->db->query('SELECT batch_id FROM iibfbcbf_agency_centre_batch WHERE batch_type = "'.$batch_type.'" AND batch_status != "8" ');
      $total_batch_count = $total_batch_qry->num_rows();

      //$batch_code = "BC".sprintf('%04d', $batch_id);
      $get_batch_type = 'BB'; if($batch_type == '2') { $get_batch_type = 'BA'; } 
      $batch_code = $get_batch_type.$total_batch_count; 
      //END : GENERATE BATCH CODE 

      //START : GENERATE BATCH ID
      $centre_batch_id = '';
      if(count($centre_data) > 0)
      {
        $total_batch_qry2 = $this->db->query('SELECT batch_id FROM iibfbcbf_agency_centre_batch WHERE agency_id = "'.$centre_data[0]['agency_id'].'" AND centre_id = "'.$centre_data[0]['centre_id'].'" AND batch_status != "8" ');
        $total_batch_count2 = $total_batch_qry2->num_rows();

        $centre_batch_id = strtoupper(substr(preg_replace('/\s+/', '', $centre_data[0]['agency_name']), 0, 3)). '/' .strtoupper(substr(preg_replace('/\s+/', '', $centre_data[0]['centre_name']), 0, 3)).'/'.$total_batch_count2;
      }
      //END : GENERATE BATCH ID

      $chk_record_exist = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_code'=>$batch_code, 'centre_batch_id'=>$centre_batch_id));
      if(count($chk_record_exist) > 0)
      {
        $this->generate_batch_code_batch_id($batch_type,$centre_data,$batch_id);
      }
      else
      {
        $this->master_model->updateRecord('iibfbcbf_agency_centre_batch', array('batch_code'=>$batch_code, 'centre_batch_id'=>$centre_batch_id), array('batch_id'=>$batch_id));
      }
    }
    
    function save_as_draft()
    {
      $result['flag'] = "error";
      $result['response'] = '';
      $new_training_schedule_file = '';

      if($this->login_user_type != 'centre') //only centre can add batches
      {
        $result['response'] = 'You do not have permission to access the Add Training Batch module';
        echo json_encode($result); exit;
      }
      
      $agency_id = '0';
      $centre_id = '0';
      if($this->login_user_type == 'agency') { $agency_id = $this->login_agency_or_centre_id; }
      else if($this->login_user_type == 'centre')
      {
        $centre_id = $this->login_agency_or_centre_id;

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$centre_id), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name, cm.centre_name, am.agency_name');
        
        if(count($centre_data) > 0) { $agency_id = $centre_data[0]['agency_id']; }
      }

      $mode = $this->security->xss_clean($this->input->post('mode'));
      $enc_batch_id = $this->security->xss_clean($this->input->post('form_enc_batch_id'));
    
      if($mode == 'Add') 
      { 
        $batch_id = $enc_batch_id;
      }
      else
      {
        $batch_id = url_decode($enc_batch_id);
      
        $this->db->where(" (batch_status = '0' OR batch_status = '1' OR batch_status = '2' OR batch_status = '6' OR batch_status = '8') ");
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.centre_id' => $this->login_agency_or_centre_id, 'acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.*, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', 'Cancelled'))))))) AS DispBatchStatus");        

        if(count($form_data) == 0) 
        { 
          $result['response'] = 'Invalid form submission';
          echo json_encode($result); exit;
        } 
        else
        {
          if($form_data[0]['batch_status'] != '8')
          {
            $result['response'] = 'Invalid form submission';
            echo json_encode($result); exit;
          }
        }   
        
        
        $bank_field_id_arr = $bank_name_arr = $cand_src_arr = array();
        $bank_field_arr = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted' => '0'));
        if(count($bank_field_arr) > 0)
        {
          foreach($bank_field_arr as $res)
          {
            $bank_field_id_arr[] = $res['bank_id'];
            $bank_name_arr[] = $res['bank_name'];
            $cand_src_arr[] = $res['cand_src'];
          }
        }
        $form_bank_field_id_arr = $bank_field_id_arr;         

        $field_id_arr = $login_id_arr = $password_arr = array();
        $field_arr = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted' => '0'));
        if(count($field_arr) > 0)
        {
          foreach($field_arr as $res)
          {
            $field_id_arr[] = $res['user_id'];
            $login_id_arr[] = $res['login_id'];
            $password_arr[] = $res['password'];
          }
        }
        $form_field_id_arr = $field_id_arr; 
      }
      
      $training_schedule_file_path = $this->training_schedule_file_path;
      if(isset($_POST) && count($_POST) > 0)
      {
        if($_FILES['training_schedule_file']['name'] != "")
        {
          //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
          $new_file_name = "training_batch_schedule_".date("YmdHis").'_'.rand(1000,9999);
          $upload_data1 = $this->Iibf_bcbf_model->upload_file("training_schedule_file", array('txt','doc','docx','pdf'), $new_file_name, "./".$training_schedule_file_path, "txt|doc|docx|pdf", '', '', '', '', '5000');
          if($upload_data1['response'] == 'error')
          {
            $data['training_schedule_file_error'] = $upload_data1['message'];
          }
          else if($upload_data1['response'] == 'success')
          {
            $add_data['training_schedule_file'] = $training_schedule_file = $new_training_schedule_file = $upload_data1['message'];
          }
        }
        
        $posted_arr = json_encode($_POST);
        $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($centre_id, 'centre');

        $batch_type = $this->input->post('batch_type');        
            
        //echo 'IN';exit;
        $add_data['agency_id'] = $agency_id;
        $add_data['centre_id'] = $centre_id;
        $add_data['batch_type'] = $batch_type;
        $add_data['batch_hours'] = $this->input->post('batch_hours');
        $add_data['batch_start_date'] = $batch_start_date = $this->input->post('batch_start_date');
        $add_data['batch_end_date'] = $this->input->post('batch_end_date');
        $add_data['batch_gross_days'] = $this->input->post('batch_gross_days');
        $add_data['batch_holidays'] = $this->sort_holidays_dates($this->input->post('batch_holidays'));
        $add_data['batch_net_days'] = $this->input->post('batch_net_days');

        if($this->input->post('batch_daily_start_time') != "")
        {
          $add_data['batch_daily_start_time'] = date("H:i",strtotime($this->input->post('batch_daily_start_time')));
        }

        if($this->input->post('batch_daily_end_time') != "")
        {
          $add_data['batch_daily_end_time'] = date("H:i",strtotime($this->input->post('batch_daily_end_time')));
        }

        $add_data['batch_daily_gross_time'] = $this->input->post('batch_daily_gross_time');
        
        if($this->input->post('break_start_time1') != "")
        {
          $add_data['break_start_time1'] = date("H:i",strtotime($this->input->post('break_start_time1')));
        }

        if($this->input->post('break_end_time1') != "")
        {
          $add_data['break_end_time1'] = date("H:i",strtotime($this->input->post('break_end_time1')));
        }

        if($this->input->post('break_start_time2') != "")
        {
          $add_data['break_start_time2'] = date("H:i",strtotime($this->input->post('break_start_time2')));
        }
        
        if($this->input->post('break_end_time2') != "")
        {
          $add_data['break_end_time2'] = date("H:i",strtotime($this->input->post('break_end_time2')));
        }

        if($this->input->post('break_start_time3') != "")
        {
          $add_data['break_start_time3'] = date("H:i",strtotime($this->input->post('break_start_time3')));
        }

        if($this->input->post('break_end_time3') != "")
        {
          $add_data['break_end_time3'] = date("H:i",strtotime($this->input->post('break_end_time3')));
        }
        
        $add_data['total_daily_break_time'] = $this->input->post('total_daily_break_time');
        $add_data['batch_daily_net_time'] = $this->input->post('batch_daily_net_time');
        $add_data['batch_total_net_time'] = $this->input->post('batch_total_net_time');
        $add_data['training_language'] = $this->input->post('training_language');
        $add_data['under_graduate_candidates'] = $this->input->post('under_graduate_candidates');
        $add_data['graduate_candidates'] = $this->input->post('graduate_candidates');
        $add_data['post_graduate_candidates'] = $this->input->post('post_graduate_candidates');
        $add_data['total_candidates'] = $this->input->post('total_candidates');
        $add_data['first_faculty'] = $this->input->post('first_faculty');
        $add_data['second_faculty'] = $this->input->post('second_faculty');
        $add_data['third_faculty'] = $this->input->post('third_faculty');
        $add_data['fourth_faculty'] = $this->input->post('fourth_faculty');
        $add_data['contact_person_name'] = $this->input->post('contact_person_name');
        $add_data['contact_person_mobile'] = $this->input->post('contact_person_mobile');
        $add_data['alt_contact_person_name'] = $this->input->post('alt_contact_person_name');
        $add_data['alt_contact_person_mobile'] = $this->input->post('alt_contact_person_mobile');
        $add_data['remarks'] = $this->input->post('remarks');
        $add_data['batch_online_offline_flag'] = $batch_online_offline_flag = $this->input->post('batch_online_offline_flag');

        if($batch_online_offline_flag == 2)
        {
          $add_data['online_training_platform'] = $this->input->post('online_training_platform');
          $add_data['platform_link'] = $this->input->post('platform_link');
        }
        else
        {
          $add_data['online_training_platform'] = '';
          $add_data['platform_link'] = '';
        }

        $add_data['ip_address'] = get_ip_address(); //general_helper.php            
        if($mode == "Add") 
        {
          $add_data['batch_status'] = '8';
          $add_data['created_on'] = date("Y-m-d H:i:s");
          $add_data['created_by'] = $this->login_agency_or_centre_id;
          
          $this->master_model->insertRecord('iibfbcbf_agency_centre_batch ',$add_data);
          $batch_id = $this->db->insert_id();

          if($batch_id > 0)
          {
            $this->Iibf_bcbf_model->insert_common_log('Centre : Batch drafted', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch has successfully drafted by the centre '.$centreName['disp_name'], $posted_arr);
          }
          else
          {
            $this->Iibf_bcbf_model->insert_common_log('Centre : Batch drafted error', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The error occurred for batch drafted by the centre '.$centreName['disp_name'], $posted_arr);

            $result['response'] = 'Error occurred';
            echo json_encode($result); exit;
          }
        }
        else if($mode == "Update")
        {
          $chk_training_schedule_file = '';
          if($new_training_schedule_file == '') { $chk_training_schedule_file = $form_data[0]['training_schedule_file']; }
          else if($new_training_schedule_file != '') { $chk_training_schedule_file = $new_training_schedule_file; }
          
          $add_data['updated_on'] = date("Y-m-d H:i:s");
          $add_data['updated_by'] = $this->login_agency_or_centre_id;            
          $this->master_model->updateRecord('iibfbcbf_agency_centre_batch', $add_data, array('batch_id'=>$batch_id));
                            
          if($new_training_schedule_file != '') { @unlink("./".$training_schedule_file_path."/".$form_data[0]['training_schedule_file']); }

          $this->Iibf_bcbf_model->insert_common_log('Centre : Batch draft Updated', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The draft batch has successfully updated by the centre '.$centreName['disp_name'], $posted_arr);
				}

        if($batch_id > 0)
        {
          $bank_field_id_arr = $this->input->post('bank_field_id_arr');              
          $bank_name_arr = $this->input->post('bank_name_arr');
          $cand_src_arr = $this->input->post('cand_src_arr');
              
          if(count($bank_field_id_arr) > 0)
          {
            $i = 0;
            foreach($bank_field_id_arr as $res)
            {
              $add_field = array();
              $add_field['agency_id'] = $agency_id;
              $add_field['batch_id'] = $batch_id;
              $add_field['bank_name'] = $bank_name_arr[$i];
              $add_field['cand_src'] = $cand_src_arr[$i];                  
              $add_field['ip_address'] = get_ip_address(); //general_helper.php           

              if($res == 0)
              {
                $add_field['is_active'] = '1';                    
                $add_field['created_on'] = date("Y-m-d H:i:s");
                $add_field['created_by'] = $this->login_agency_or_centre_id;
                $this->master_model->insertRecord('iibfbcbf_batch_bak_name_cand_src_details',$add_field);
              }
              else
              {                    
                $add_field['updated_on'] = date("Y-m-d H:i:s");
                $add_field['updated_by'] = $this->login_agency_or_centre_id;
                $this->master_model->updateRecord('iibfbcbf_batch_bak_name_cand_src_details',$add_field, array('bank_id' => $res));
              }
              $i++;
            }
          }

          if($batch_online_offline_flag == 2)
          {
            $field_id_arr = $this->input->post('field_id_arr');              
            $login_id_arr = $this->input->post('login_id_arr');
            $password_arr = $this->input->post('password_arr');
            
            if(count($field_id_arr) > 0)
            {
              $i = 0;
              foreach($field_id_arr as $res)
              {
                $add_field = array();
                $add_field['agency_id'] = $agency_id;
                $add_field['batch_id'] = $batch_id;
                $add_field['login_id'] = $login_id_arr[$i];
                $add_field['password'] = $password_arr[$i];                  
                $add_field['ip_address'] = get_ip_address(); //general_helper.php           

                if($res == 0)
                {
                  $add_field['is_active'] = '1';                    
                  $add_field['created_on'] = date("Y-m-d H:i:s");
                  $add_field['created_by'] = $this->login_agency_or_centre_id;
                  $this->master_model->insertRecord(' iibfbcbf_online_batch_user_details ',$add_field);
                }
                else
                {                    
                  $add_field['updated_on'] = date("Y-m-d H:i:s");
                  $add_field['updated_by'] = $this->login_agency_or_centre_id;
                  $this->master_model->updateRecord(' iibfbcbf_online_batch_user_details ',$add_field, array('user_id' => $res));
                }
                $i++;
              }
            }
          }
        }
            
        if($mode == 'Update')//DELETE PREVIOUS user details
        {
          $old_bank_name_arr = $form_bank_field_id_arr;
          $current_bank_name_arr = $this->input->post('bank_field_id_arr');
          $delete_bank_arr = array_diff($old_bank_name_arr, $current_bank_name_arr);
          if(count($delete_bank_arr) > 0)
          {
            foreach($delete_bank_arr as $del)
            {
              $del_data = array();
              $del_data['is_deleted'] = '1';
              $del_data['ip_address'] = get_ip_address(); //general_helper.php
              $del_data['deleted_on'] = date("Y-m-d H:i:s");
              $del_data['deleted_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_batch_bak_name_cand_src_details',$del_data, array('bank_id' => $del));
            }
          }

          if($batch_online_offline_flag == 2)
          {
            $old_arr = $form_field_id_arr;
            $current_arr = $this->input->post('field_id_arr');
            $delete_arr = array_diff($old_arr, $current_arr);
            if(count($delete_arr) > 0)
            {
              foreach($delete_arr as $del)
              {
                $del_data = array();
                $del_data['is_deleted'] = '1';
                $del_data['ip_address'] = get_ip_address(); //general_helper.php
                $del_data['deleted_on'] = date("Y-m-d H:i:s");
                $del_data['deleted_by'] = $this->login_agency_or_centre_id;
                $this->master_model->updateRecord('iibfbcbf_online_batch_user_details',$del_data, array('user_id' => $del));
              }
            }
          }
        }

        if($mode == 'Update' && $batch_online_offline_flag == 1)
        {
          $delete_arr = $form_field_id_arr;
          if(count($delete_arr) > 0)
          {
            foreach($delete_arr as $del)
            {
              $del_data = array();
              $del_data['is_deleted'] = '1';
              $del_data['ip_address'] = get_ip_address(); //general_helper.php
              $del_data['deleted_on'] = date("Y-m-d H:i:s");
              $del_data['deleted_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_online_batch_user_details',$del_data, array('user_id' => $del));
            }
          }
        }

        $this->session->set_flashdata('success','Batch data successfully drafted');
        $result['flag'] = 'success';
        $result['response'] = url_encode($batch_id);          
      }

      echo json_encode($result);
    }

    //START : IT IS USED TO TO CALCULATE BATCH START DATE EXCLUDING ALL SATURDAYS, SUNDAYS, 15 AUG, 16 JAN
    //BATCH START DATE = T+2
    function calculate_batch_start_date($current_date='',$numDays=0)
    {
      if($current_date == '') { $current_date = date('Y-m-d'); }
      if($numDays == 0) { $numDays = 2; }

      $holiday_arr = array('01-26', '08-15');
      $finalDate = $current_date;
      for($i=1;$i<=$numDays;$i)
      {
        $finalDate = date('Y-m-d', strtotime("+1day", strtotime($finalDate)));
        $dayOfWeek = date('N', strtotime($finalDate)); // Get day of the week (1 = Monday, 7 = Sunday)
        //if ($dayOfWeek != 6 && !in_array(date('m-d', strtotime($finalDate)),$holiday_arr))
        if ($dayOfWeek != 6 && $dayOfWeek != 7 && !in_array(date('m-d', strtotime($finalDate)),$holiday_arr))
        {
          $i++;
        }
      }
      return date('Y-m-d', strtotime("+1day", strtotime($finalDate)));
    }//END : IT IS USED TO TO CALCULATE BATCH START DATE EXCLUDING ALL SATURDAYS, SUNDAYS, 15 AUG, 16 JAN

    //START : ADDED BY SAGAR ON 2024-01-09    
    //IT IS USED TO TO CALCULATE "Review Batch and Submit to IIBF" DATE EXCLUDING ALL SATURDAYS, SUNDAYS, 15 AUG, 16 JAN
    function calculate_review_batch_and_submit_to_iibf_date($batch_start_date='',$numDays=0)
    {
      if($batch_start_date == '') { $batch_start_date = date('Y-m-d'); }
      if($numDays == 0) { $numDays = 2; }

      $holiday_arr = array('01-26', '08-15');
      $finalDate = $batch_start_date;
      for($i=$numDays;$i>=0;$i)
      {
        $finalDate = date('Y-m-d', strtotime("-1day", strtotime($finalDate)));
        $dayOfWeek = date('N', strtotime($finalDate)); // Get day of the week (1 = Monday, 7 = Sunday)
        if ($dayOfWeek != 6 && $dayOfWeek != 7 && !in_array(date('m-d', strtotime($finalDate)),$holiday_arr))
        {
          $i--;
        }
      }
      return date('Y-m-d', strtotime($finalDate));
    }//END : ADDED BY SAGAR ON 2024-01-05

    /******** START : VALIDATION FUNCTION TO CHECK FACULTY IS NOT ALLOCATED MORE THAN 2 BATCHES IN SAME TIME ********/
    public function validation_faculty_availability($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {      
      $flag = "error";
      $response = '';

      $chk_faculty_id = '';
      if(isset($_POST['faculty_id']) && $_POST['faculty_id'] != "") { $chk_faculty_id = $this->security->xss_clean($this->input->post('faculty_id')); }
      else if(isset($_POST['faculty_id1']) && $_POST['faculty_id1'] != "") { $chk_faculty_id = $this->security->xss_clean($this->input->post('faculty_id1')); }
      else if(isset($_POST['faculty_id2']) && $_POST['faculty_id2'] != "") { $chk_faculty_id = $this->security->xss_clean($this->input->post('faculty_id2')); }
      else if(isset($_POST['faculty_id3']) && $_POST['faculty_id3'] != "") { $chk_faculty_id = $this->security->xss_clean($this->input->post('faculty_id3')); }
      else if(isset($_POST['faculty_id4']) && $_POST['faculty_id4'] != "") { $chk_faculty_id = $this->security->xss_clean($this->input->post('faculty_id4')); }

      if($chk_faculty_id != "")
      {
        if($type == '1') 
        { 
          $faculty_id = $chk_faculty_id; 
          $enc_batch_id = $str;
          
          if($enc_batch_id != "" && $enc_batch_id != '0') { $batch_id = url_decode($enc_batch_id); }
          else { $batch_id = $enc_batch_id; }
        }
        else 
        { 
          $faculty_id = $str; 
          $enc_batch_id = $type;
          $batch_id = url_decode($enc_batch_id);
        }

        $batch_start_date = $this->security->xss_clean($this->input->post('batch_start_date')); 
        $batch_end_date = $this->security->xss_clean($this->input->post('batch_end_date')); 

        $get_faculty_ids = $this->master_model->getRecords('iibfbcbf_faculty_master fm1', array('fm1.faculty_id' => $faculty_id), 'fm1.faculty_id, fm1.pan_no, (SELECT GROUP_CONCAT(faculty_id) FROM iibfbcbf_faculty_master WHERE pan_no = fm1.pan_no) AS CheckFacultyIds');
                
        if(count($get_faculty_ids) > 0)
        {
          $this->db->where(" (first_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].") OR second_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].") OR third_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].")  OR fourth_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].")) ");
          $this->db->where(" ( ('".$batch_start_date."' BETWEEN batch_start_date AND batch_end_date) OR ('".$batch_end_date."' BETWEEN batch_start_date AND batch_end_date)) ");
          $faculty_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('is_deleted' => '0', 'batch_id !=' => $batch_id, 'batch_status !=' => '5', 'batch_status !=' => '7'), 'batch_id, agency_id, batch_code, first_faculty, second_faculty, third_faculty, fourth_faculty');
          
          if(count($faculty_data) < 2)
          {
            $flag = 'success';
          }
          else
          {
            $batch_str = '';
            foreach($faculty_data as $res)
            {
              $batch_str .= $res['batch_code'].", ";
            }
            $response = 'This faculty is mapped with '.rtrim($batch_str,", ");
          }        
          
          if($type == '1') 
          {
            $result['flag'] = $flag;
            $result['response'] = $response;
            echo json_encode($result); 
          }
          else 
          { 
            if($flag == 'success') { return TRUE; } 
            else
            {
              $this->form_validation->set_message('validation_faculty_availability', $response);
              return false;
            }
          }
        }
        else 
        { 
          if($type == '1') 
          {
            $result['flag'] = 'success';
            echo json_encode($result); 
          }
          else 
          { 
            return TRUE;
          }
        }                
      }
      else 
      { 
        if($type == '1') 
        {
          $result['flag'] = 'success';
          echo json_encode($result); 
        }
        else 
        { 
          return TRUE;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK FACULTY IS NOT ALLOCATED MORE THAN 2 BATCHES IN SAME TIME ********/

    /******** START : BATCH DETAILS PAGE ********/
    public function training_batch_details_agency($enc_batch_id=0)
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";     
      $data['page_title'] = 'IIBF - BCBF Agency Training Batches Details';

      $data['enc_batch_id'] = $enc_batch_id;
      $batch_id = url_decode($enc_batch_id);      

      if($this->login_user_type == 'centre') { $this->db->where('acb.centre_id', $this->login_agency_or_centre_id); }
      else if($this->login_user_type == 'agency') 
      { 
        $this->db->where('acb.agency_id', $this->login_agency_or_centre_id);
        $this->db->where('cm.is_deleted', '0');        
        $this->db->where('acb.batch_status !=', '0');        
        $this->db->where('acb.batch_status !=', '8');        
      }
      
      $this->db->join('iibfbcbf_faculty_master fm1', 'fm1.faculty_id = acb.first_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm2', 'fm2.faculty_id = acb.second_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm3', 'fm3.faculty_id = acb.third_faculty', 'LEFT');
      $this->db->join('iibfbcbf_faculty_master fm4', 'fm4.faculty_id = acb.fourth_faculty', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = acb.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_inspector_master im', 'im.inspector_id = acb.inspector_id', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.is_deleted' => '0'), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', IF(acb.batch_status = 7, 'Cancelled', 'Drafted')))))))) AS DispBatchStatus, am.agency_name, am.agency_code, am.allow_exam_types, cm.centre_name, cm.centre_username, cm2.city_name, im.inspector_name");
      
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/training_batches_agency')); }
      
      $data['user_data'] = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'login_id, password', array('created_on'=>'ASC'));  
      
      $data['bank_cand_data'] = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'bank_id, bank_name, cand_src', array('created_on'=>'ASC'));  
      
      $data['training_schedule_file_path'] = $this->training_schedule_file_path;

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$form_data[0]['centre_id']), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name');

      //START : SUBMIT BATCH COMMUNICATION
      if(isset($_POST) && count($_POST) > 0 && isset($_POST['form_action']) && $_POST['form_action'] == 'batch_communication_action')
      {
        $this->form_validation->set_rules('batch_communication', 'batch communication', 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
        
        if($this->form_validation->run())
        {          
          $posted_arr = json_encode($_POST);
          $logName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, $this->login_user_type);
          
          $this->Iibf_bcbf_model->insert_common_log('Centre : Batch Communication', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch communication added by the '.$this->login_user_type.' '.$logName['disp_name']." : ".$this->input->post('batch_communication'), $posted_arr);
							
          $this->session->set_flashdata('success','Batch communication added successfully');

          redirect(site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/'.$enc_batch_id));
        }
      }//END : SUBMIT BATCH COMMUNICATION
      else if(isset($_POST) && count($_POST) > 0 && isset($_POST["form_action"]) && $_POST['form_action'] == 'batch_status_action' && $this->login_user_type == 'agency') //START : SUBMIT BATCH STATUS
      {
        $agency_batch_status_new = '';
        $agency_batch_status = $this->input->post('agency_batch_status');
        $agency_batch_status_new = $this->input->post('agency_batch_status_new');
        
        //_pa($_POST,1);
        $batch_status_reason_label = '';
        $batch_status_action = '';
        if($agency_batch_status == '3' && $agency_batch_status_new == '')
        {
          $batch_status_reason_label = 'Describe Approval Reason here';
          $batch_status_action = 'Go Ahead';
        }
        else if($agency_batch_status == '2')
        {
          $batch_status_reason_label = 'Describe Error here';
          $batch_status_action = 'Error';
        }
        else if($agency_batch_status == '5')
        {
          $batch_status_reason_label = 'Describe rejection reason here';
          $batch_status_action = 'Rejected';
        }
        else if($agency_batch_status == '4'){
          $batch_status_reason_label = 'Describe Hold batch reason here';
          $batch_status_action = 'Hold';
        }
        else if($agency_batch_status == '7')
        {
          $batch_status_reason_label = 'Describe cancel batch reason here';
          $batch_status_action = 'Cancelled';
        }
        else if($agency_batch_status_new == 'UnHold')
        {
          $batch_status_reason_label = 'Describe UnHold batch reason here';
          $batch_status_action = 'UnHold';
        }

        $this->form_validation->set_rules('batch_status_reason', $batch_status_reason_label, 'trim|required|max_length[500]|xss_clean', array('required'=>"Please enter the %s"));
        
        if($this->form_validation->run())
        {          
          if($form_data[0]['batch_status'] == $agency_batch_status)
          {
            $this->session->set_flashdata('error','The status was already updated.');
            redirect(site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/'.$enc_batch_id));
            exit;
          }

          $posted_arr = json_encode($_POST);
          $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, $this->login_user_type);  
           
          // Insert status action record in "iibfbcbf_agency_batch_status_action" table
          $insert_batch_status_action_data['agency_id'] = $form_data[0]['agency_id']; 
          $insert_batch_status_action_data['centre_id'] = $form_data[0]['centre_id']; 
          $insert_batch_status_action_data['batch_id'] = $batch_id; 
          $insert_batch_status_action_data['batch_status'] = $agency_batch_status; 
          $insert_batch_status_action_data['batch_status_reason'] = $this->input->post('batch_status_reason'); 
          $insert_batch_status_action_data['action_by'] = $this->login_agency_or_centre_id; 
          $insert_batch_status_action_data['created_on'] = date("Y-m-d H:i:s"); 
          
          if($this->master_model->insertRecord('iibfbcbf_agency_batch_status_action',$insert_batch_status_action_data))
          {            
            //Update status in "iibfbcbf_agency_centre_batch" table
            $update_agency_centre_batch_data = array('batch_status'  => $agency_batch_status);
            $this->master_model->updateRecord('iibfbcbf_agency_centre_batch',$update_agency_centre_batch_data,array('batch_id' => $batch_id));

            //START : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL
            if(in_array($agency_batch_status, array(3,5,7))) // 3=>GO AHEAD/APPROVED, 5=> REJECTED, 7=> CANCELLED
            {
              $this->Iibf_bcbf_model->send_batch_action_email_sms($batch_id, $agency_batch_status, 'Agency');
            }//END : SEND BATCH GO AHEAD/APPROVED, REJECTED, CANCELLED EMAIL
          
            //Insert Log into "iibfbcbf_logs" table
            $this->Iibf_bcbf_model->insert_common_log('Agency : Batch Status Action', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The Batch '.$batch_status_action.' by the agency '.$dispName['disp_name']." : ".$this->input->post('batch_status_reason'), $posted_arr);
            
            $this->session->set_flashdata('success','Batch '.$batch_status_action.' successfully');
          }
          else
          {
            $this->session->set_flashdata('error','Error occurred. Please try again');

          }
          redirect(site_url('iibfbcbf/agency/training_batches_agency/training_batch_details_agency/'.$enc_batch_id));
        }
      }//END : SUBMIT BATCH STATUS

      $this->load->view('iibfbcbf/agency/training_batch_details_agency', $data);
    }/******** END : BATCH DETAILS PAGE ********/    

    /******** START : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/
    function fun_restrict_input($str,$type) // Custom callback function for restrict input
    { 
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_restrict_input($str, $type); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_restrict_input', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO RESTRICT INPUT VALUES ********/

    /******** START : CALCULATE BATCH GROSS DAYS & BATCH NET DAYS ********/
    function calculate_days($type='', $start_date='', $end_date='', $holidays='')
    {
      $return_val = 0;
      $holiday_cnt = 0;
      
      if($start_date != "" && $end_date != "")
      {
        $return_val = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24) + 1;

        if($type == 'batch_net_days')
        {
          if($holidays != "")
          {
            $explode_holiday = explode(",",$holidays);
            if(count($explode_holiday) > 0) { $holiday_cnt = count($explode_holiday); }
          }
        }
      }
      $return_val  = $return_val - $holiday_cnt;
      
      return $return_val;      
    }/******** END : CALCULATE BATCH GROSS DAYS & BATCH NET DAYS ********/

    /******** START : CALCULATE BATCH DAILY GROSS TIME ********/
    function calculate_time_in_min($type='', $start_time='', $end_time='')
    {
      if($type == 'batch_daily_gross_time')
      {
        $start_time_24_hr = date("H:i",strtotime($start_time));
        $end_time_24_hr = date("H:i",strtotime($end_time));

        $chk_start_date = '2023-01-01 '.$start_time_24_hr;
        $chk_end_date = '2023-01-01 '.$end_time_24_hr;

        if(str_replace(":","",$end_time_24_hr) < str_replace(":","",$start_time_24_hr))
        {
          $chk_end_date = '2023-01-02 '.$end_time_24_hr;
        }
        
        $startTime = new DateTime($chk_start_date);
        $endTime = new DateTime($chk_end_date);
        $interval = $startTime->diff($endTime);
        $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;
        
        return $totalMinutes;
      }
    }/******** END : CALCULATE BATCH DAILY GROSS TIME ********/

    function convert_hour_into_min($hour='')
    {
      if(strpos($hour, ':') !== false) { }
      else if(strpos($hour, '.') !== false) { }
      else { $hour = $hour.":00"; }

      if(strpos(strtolower($hour), 'am') !== false || strpos(strtolower($hour), 'pm') !== false) 
      { 
        $hour = date("H:i",strtotime($hour));
      }

      $explode_hour_arr = explode(":",$hour);
      if(count($explode_hour_arr) < 2)
      {
        $explode_hour_arr = explode(".",$hour);
      }

      if(count($explode_hour_arr) == 2)
      {
        return (($explode_hour_arr[0]*60) + $explode_hour_arr[1]);
      }
      else
      {
        return ($hour[0]*60);
      }             
    }

    /******** START : CALCULATE TOTAL BREAK TIME PER DAY ********/
    function calculate_total_break_time()
    {
      $total_daily_break_time = 0;
      for($i=1; $i<=3; $i++)
      {
        $break_start_time = $this->security->xss_clean($this->input->post('break_start_time'.$i));
        $break_end_time = $this->security->xss_clean($this->input->post('break_end_time'.$i));

        if($break_start_time != "" && $break_end_time != "") 
        {
          $break_start_time = $this->convert_hour_into_min($break_start_time);
          $break_end_time = $this->convert_hour_into_min($break_end_time);
          
          if($break_end_time > $break_start_time)
          {
            $break_time_in_min = $break_end_time - $break_start_time;
            $total_daily_break_time = $total_daily_break_time + $break_time_in_min;
          }
        }
      }
      
      return $total_daily_break_time;
    }/******** END : CALCULATE TOTAL BREAK TIME PER DAY ********/

    /******** START : VALIDATION FUNCTION TO CHECK VALID PAN NUMBER ********/
    function fun_check_calculated_readonly_values($str, $input_id='') // Custom callback function for check valid pan number
    {
      if($str != '')
      {
        $current_val = $str;

        $explode_input_arr = explode("####",$input_id);
        $input_id = $explode_input_arr[0];

        if($input_id == 'batch_start_date')
        {
          $batch_id = $explode_input_arr[1];
          $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $batch_id, 'is_deleted' => '0'), 'created_on');

          if(count($batch_data) > 0) 
          { 
            //$batch_chk_date = $this->calculate_batch_start_date(date("Y-m-d",strtotime($batch_data[0]['created_on']))); 
            $batch_chk_date = $this->calculate_batch_start_date(); 
          }
          else
          {
            $batch_chk_date = $this->calculate_batch_start_date();
          }
          $batch_chk_date_end = date('Y-m-d', strtotime("+4day", strtotime($batch_chk_date)));

          $batch_start_date = $current_val;
          
          if($batch_start_date < $batch_chk_date || $batch_start_date > $batch_chk_date_end)
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Please Select the Date between ".$batch_chk_date." and ".$batch_chk_date_end.".");
            return false;
          }
          else { return true; }
        } 
        else if($input_id == 'batch_end_date')
        {
          $selected_batch_type = $this->security->xss_clean($this->input->post('batch_type'));
          $chk_gap_days = '5';
          if($selected_batch_type == '1') { $chk_gap_days = '3'; }

          $batch_id = $explode_input_arr[1];
          $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $batch_id, 'is_deleted' => '0'), 'created_on');

          if(count($batch_data) > 0) 
          { 
            //$batch_chk_date = date('Y-m-d', strtotime("+5day", strtotime($this->calculate_batch_start_date(date("Y-m-d",strtotime($batch_data[0]['created_on']))))));
            $batch_chk_date = date('Y-m-d', strtotime("+".$chk_gap_days."day", strtotime($this->calculate_batch_start_date())));
          }
          else
          {
            $batch_chk_date = date('Y-m-d', strtotime("+".$chk_gap_days."day", strtotime($this->calculate_batch_start_date())));
          }

          $batch_end_date = $current_val;          
          if($batch_end_date < $batch_chk_date)
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Please Select From Date greater than ".date('Y-m-d', strtotime("+".$chk_gap_days."day", strtotime($this->calculate_batch_start_date()))));
            return false;
          }
          else { return true; }
        }
        else if($input_id == 'batch_gross_days')
        {
          $batch_start_date =  $this->security->xss_clean($this->input->post('batch_start_date'));
          $batch_end_date =  $this->security->xss_clean($this->input->post('batch_end_date'));

          if($batch_start_date != "" && $batch_end_date != "")
          {
            $batch_gross_days = $this->calculate_days('batch_gross_days', $batch_start_date, $batch_end_date);

            if($batch_gross_days == $current_val)
            {                        
              $batch_type_value =  $this->security->xss_clean($this->input->post('batch_type'));
              $check_gross_days = $this->chk_gross_training_days_basic;
          
              if($batch_type_value == '1') { $check_gross_days = $this->chk_gross_training_days_basic; }
              else if($batch_type_value == '2') { $check_gross_days = $this->chk_gross_training_days_advance; }
              
              if($batch_gross_days > $check_gross_days)
              {
                $this->form_validation->set_message('fun_check_calculated_readonly_values', "Gross Days should be less than or equal to ".$check_gross_days);
                return false;
              }
              else { return true; }
            }
            else
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid gross training days value");
              return false;
            }
          }
          else { return true; }
        }    
        else if($input_id == 'batch_net_days')
        {          
          $batch_start_date =  $this->security->xss_clean($this->input->post('batch_start_date'));
          $batch_end_date =  $this->security->xss_clean($this->input->post('batch_end_date'));
          $batch_holidays =  $this->security->xss_clean($this->input->post('batch_holidays'));

          if($batch_start_date != "" && $batch_end_date != "")
          {
            $batch_net_days = $this->calculate_days('batch_net_days', $batch_start_date, $batch_end_date, $batch_holidays);

            if($batch_net_days != $current_val)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid net training days value");
              return false;
            }
            else { return true; }
          }
          else { return true; }
        }    
        else if($input_id == 'batch_daily_gross_time')
        {
          $batch_daily_start_time =  $this->security->xss_clean($this->input->post('batch_daily_start_time'));
          $batch_daily_end_time =  $this->security->xss_clean($this->input->post('batch_daily_end_time'));
          
          $batch_daily_gross_time_in_min = $this->calculate_time_in_min('batch_daily_gross_time', $batch_daily_start_time, $batch_daily_end_time);
          
          /* $explode_current_val_arr = explode(":",$current_val);
          $current_val_in_min = ($explode_current_val_arr[0]*60) + $explode_current_val_arr[1]; */
          $current_val_in_min = $this->convert_hour_into_min($current_val);

          if($batch_daily_gross_time_in_min == $current_val_in_min)
          {
            if($batch_daily_gross_time_in_min > ($this->chk_gross_training_time_per_day*60))
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Gross Time should be less than or equal to ".$this->chk_gross_training_time_per_day." Hours");
              return false;
            }
            else { return true; }
          }
          else
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid gross training time per day value");
            return false;
          }
        }
        else if($input_id == 'total_daily_break_time')
        {         
          $chk_total_break_time = $this->chk_total_break_time;
          $total_daily_break_time = $this->calculate_total_break_time(); 
          
          if($total_daily_break_time == $current_val)
          {
            if($total_daily_break_time > $chk_total_break_time)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Total Break Time should be less than or equal to ".$chk_total_break_time." minutes.");
              return false;
            }
            else { return true; }
          }
          else
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid total break time value");
            return false;
          }
        }
        else if($input_id == 'batch_daily_net_time')
        {         
          $chk_min_net_training_time_per_day_in_min = $this->convert_hour_into_min($this->chk_min_net_training_time_per_day);
          $chk_net_training_time_per_day_in_min = $this->convert_hour_into_min($this->chk_net_training_time_per_day);
          
          $batch_daily_start_time =  $this->security->xss_clean($this->input->post('batch_daily_start_time'));
          $batch_daily_end_time =  $this->security->xss_clean($this->input->post('batch_daily_end_time'));
          
          $batch_daily_gross_time_in_min = $this->calculate_time_in_min('batch_daily_gross_time', $batch_daily_start_time, $batch_daily_end_time);

          $batch_daily_net_time_in_min = $batch_daily_gross_time_in_min - $this->calculate_total_break_time(); 
          $current_val_in_min = $this->convert_hour_into_min($current_val);

          if($batch_daily_net_time_in_min == $current_val_in_min)
          {  
            if($batch_daily_net_time_in_min > $chk_net_training_time_per_day_in_min)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Net Time should be less than or equal to ".$this->chk_net_training_time_per_day." Hours.");
              return false;
            }
            else if($batch_daily_net_time_in_min < $chk_min_net_training_time_per_day_in_min)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Net Time should be greater than or equal to ".$this->chk_min_net_training_time_per_day." Hours.");
              return false;
            }
            else { return true; }
          }
          else
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid net training time per day value");
            return false;
          }
        }
        else if($input_id == 'batch_total_net_time')
        { 
          //CALCULATE NET TRAINING DAYS
          $batch_start_date =  $this->security->xss_clean($this->input->post('batch_start_date'));
          $batch_end_date =  $this->security->xss_clean($this->input->post('batch_end_date'));
          $batch_holidays =  $this->security->xss_clean($this->input->post('batch_holidays'));

          if($batch_start_date != "" && $batch_end_date != "")
          {
            $batch_net_days = $this->calculate_days('batch_net_days', $batch_start_date, $batch_end_date, $batch_holidays);

            if($batch_net_days != "" && $batch_net_days > 0)
            {
              //CALCULATE GROSS TRAINING TIME PER DAY
              $batch_daily_start_time =  $this->security->xss_clean($this->input->post('batch_daily_start_time'));
              $batch_daily_end_time =  $this->security->xss_clean($this->input->post('batch_daily_end_time'));
              $batch_daily_gross_time_in_min = $this->calculate_time_in_min('batch_daily_gross_time', $batch_daily_start_time, $batch_daily_end_time);

              $batch_daily_net_time_in_min = $batch_daily_gross_time_in_min - $this->calculate_total_break_time();
              
              $batch_total_net_time_in_min = $batch_net_days * $batch_daily_net_time_in_min;
              $current_val_in_min = $this->convert_hour_into_min($current_val);
              
              if($batch_total_net_time_in_min == $current_val_in_min)
              {
                $batch_type_value =  $this->security->xss_clean($this->input->post('batch_type'));
                $chk_total_net_training_time_of_duration = $this->chk_total_net_training_time_of_duration_basic;
      
                if($batch_type_value == '1') { $chk_total_net_training_time_of_duration = $this->chk_total_net_training_time_of_duration_basic; }
                else if($batch_type_value == '2') { $chk_total_net_training_time_of_duration = $this->chk_total_net_training_time_of_duration_advance; }

                $chk_total_net_training_time_of_duration_in_min = $this->convert_hour_into_min($chk_total_net_training_time_of_duration);

                if($batch_total_net_time_in_min < $chk_total_net_training_time_of_duration_in_min)
                {
                  $this->form_validation->set_message('fun_check_calculated_readonly_values', "Total Net Training Time of Duration should be greater than or equal to ".$chk_total_net_training_time_of_duration." Hours.");
                  return false;
                }
                else { return true; }
              }
              else
              {
                $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid total net training time of duration value");
                return false;
              }
            } else { return true; }
          }
          else { return true; }
        }
        else if($input_id == 'total_candidates')
        { 
          //CALCULATE TOTAL CANDIDATE COUNT
          $under_graduate_candidates = $this->security->xss_clean($this->input->post('under_graduate_candidates'));
          $graduate_candidates = $this->security->xss_clean($this->input->post('graduate_candidates'));
          $post_graduate_candidates = $this->security->xss_clean($this->input->post('post_graduate_candidates'));
          $total_candidate = 0;

          if($under_graduate_candidates != "") { $total_candidate = $total_candidate + $under_graduate_candidates; }
          if($graduate_candidates != "") { $total_candidate = $total_candidate + $graduate_candidates; }
          if($post_graduate_candidates != "") { $total_candidate = $total_candidate + $post_graduate_candidates; }

          if($total_candidate == $current_val)
          {
            $chk_total_batch_candidates = $this->chk_total_batch_candidates;
            if($total_candidate == 0)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Total Candidates should be more than or equal to 1");
              return false;
            }
            else if($total_candidate > $chk_total_batch_candidates)
            {
              $this->form_validation->set_message('fun_check_calculated_readonly_values', "Total Candidates should be less than or equal to ".$chk_total_batch_candidates);
              return false;
            }
            else { return true; }
          }
          else
          {
            $this->form_validation->set_message('fun_check_calculated_readonly_values', "Invalid total candidates value");
            return false;
          }
        }
      }      
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PAN NUMBER ********/

    //START : BREAK START & END TIME MUST BE IN BETWEEN 'DAILY TRAINING START TIME' & 'DAILY TRAINING END TIME'
    function fun_check_valid_break_time_between($str, $input_id='')
    {
      $break_time = $str;
      $batch_daily_start_time =  $this->security->xss_clean($this->input->post('batch_daily_start_time'));
      $batch_daily_end_time =  $this->security->xss_clean($this->input->post('batch_daily_end_time'));

      $check_time_type = 'start';   
      if(strpos($input_id, 'end') !== false) { $check_time_type = 'end'; }

      $check_time_msg = '1';
      if(strpos($input_id, '2') !== false) { $check_time_msg = '2'; }
      else if(strpos($input_id, '3') !== false) { $check_time_msg = '3'; }

      if($break_time != "" && $batch_daily_start_time != "" && $batch_daily_end_time != "")
      {
        $break_time_in_min = $this->convert_hour_into_min($break_time);
        $batch_daily_start_time_in_min = $this->convert_hour_into_min($batch_daily_start_time);
        $batch_daily_end_time_in_min = $this->convert_hour_into_min($batch_daily_end_time);
      
        if($break_time_in_min < $batch_daily_start_time_in_min || $break_time_in_min > $batch_daily_end_time_in_min) 
        { 
          $this->form_validation->set_message('fun_check_valid_break_time_between', "Break ".$check_time_type." time".$check_time_msg." must be in between <b>".$batch_daily_start_time."</b> & <b>".$batch_daily_end_time."</b>");
          return false;
        }
        else
        { 
          //START : BREAK START & END TIME2 MUST BE GREATER THAN BREAK START & END TIME1. ALSO BREAK START & END TIME3 MUST BE GREATER THAN BREAK START & END TIME2
          $current_val_in_min = $this->convert_hour_into_min($break_time);
          if(strpos($input_id, '2') !== false) 
          { 
            $break_end_time1 = $this->security->xss_clean($this->input->post('break_end_time1'));

            if($break_end_time1 != "")
            {
              $break_end_time1_in_min = $this->convert_hour_into_min($break_end_time1);
              if($current_val_in_min < $break_end_time1_in_min)
              {
                $this->form_validation->set_message('fun_check_valid_break_time_between', "Break ".$check_time_type." time2 must be greater than <b>".$break_end_time1."</b>");
                return false;
              }                
            }
          }
          
          if(strpos($input_id, '3') !== false)
          { 
            $break_end_time2 = $this->security->xss_clean($this->input->post('break_end_time2'));
            $break_end_time1 = $this->security->xss_clean($this->input->post('break_end_time1'));

            if($break_end_time2 != "")
            {
              $break_end_time2_in_min = $this->convert_hour_into_min($break_end_time2);
              if($current_val_in_min < $break_end_time2_in_min)
              {
                $this->form_validation->set_message('fun_check_valid_break_time_between', "Break ".$check_time_type." time3 must be greater than <b>".$break_end_time2."</b>");
                return false;
              }                
            }

            if($break_end_time1 != "")
            {
              $break_end_time1_in_min = $this->convert_hour_into_min($break_end_time1);
              if($current_val_in_min < $break_end_time1_in_min)
              {
                $this->form_validation->set_message('fun_check_valid_break_time_between', "Break ".$check_time_type." time3 must be greater than <b>".$break_end_time1."</b>");
                return false;
              }                
            }
          }
          
          return true;
          //END : BREAK START & END TIME2 MUST BE GREATER THAN BREAK START & END TIME1. ALSO BREAK START & END TIME3 MUST BE GREATER THAN BREAK START & END TIME2
        }
      }
      else { return true; }
    }//END : BREAK START & END TIME MUST BE IN BETWEEN 'DAILY TRAINING START TIME' & 'DAILY TRAINING END TIME'

    //START : BREAK END TIME MUST BE GREATER THAN BREAK START TIME
    function fun_check_valid_break_end_time($str, $input_id='')
    {
      //var return_flag = validation_for_break_end_time($.trim($("#"+element.id.replace("end", "start")).val()), $.trim(value));

      $break_end_time = $str;
      $break_start_time =  $this->security->xss_clean($this->input->post(str_replace("end","start",$input_id)));

      if($break_end_time == "") { return true; }
      else
      {
        if($break_start_time != "" && $break_end_time != "")
        {
          $break_start_time_in_min = $this->convert_hour_into_min($break_start_time);
          $break_end_time_in_min = $this->convert_hour_into_min($break_end_time);
        
          if($break_start_time_in_min >= $break_end_time_in_min)
          {              
            $err_msg = '1';
            if(strpos($input_id, '2') !== false) { $err_msg = '2'; }
            else if(strpos($input_id, '3') !== false) { $err_msg = '3'; }            

            $err_msg_text = $break_start_time;
            
            $this->form_validation->set_message('fun_check_valid_break_end_time', "Break end time".$err_msg." must be greater than <b>".$err_msg_text."</b>");
            return false;
          }
          else { return true; }
        }
        else { return true; }
      }
    }//END : BREAK END TIME MUST BE GREATER THAN BREAK START TIME   

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

    //START : SEND BATCH TO IIBF FOR APPROVAL
    function send_batch_for_approval_ajax()
		{
      $result['flag'] = "error";
      $result['response'] = '';
			if(isset($_POST) && $_POST['enc_batch_id'] != "")
			{
        $batch_id = url_decode($this->security->xss_clean($this->input->post('enc_batch_id')));  
        $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $batch_id, 'is_deleted' => '0', 'batch_status'=>'0', 'batch_start_date >=' => $this->calculate_batch_start_date()), 'batch_id, batch_start_date, batch_end_date, first_faculty, second_faculty, third_faculty, fourth_faculty');
        
        if(count($batch_data) > 0)
        {   
          //START : CHECK THE FACULTY MAPPED CONDITION
          $chk_faculty_flag = '1';
          $chk_faculty_msg = '';
          if($batch_data[0]['first_faculty'] > 0)
          {
            $chk_first_faculty = $this->validation_faculty_availability_for_approval($batch_data[0]['first_faculty'],$batch_data);
            if($chk_first_faculty['flag'] == 'error')
            {
              $chk_faculty_flag = '0';
              $chk_faculty_msg .= 'This first faculty is mapped with '.$chk_first_faculty['response'].'. ';
            }
          }

          if($batch_data[0]['second_faculty'] > 0)
          {
            $chk_second_faculty = $this->validation_faculty_availability_for_approval($batch_data[0]['second_faculty'],$batch_data);
            if($chk_second_faculty['flag'] == 'error')
            {
              $chk_faculty_flag = '0';
              $chk_faculty_msg .= 'This second faculty is mapped with '.$chk_second_faculty['response'].'. ';
            }
          }

          if($batch_data[0]['third_faculty'] > 0)
          {
            $chk_third_faculty = $this->validation_faculty_availability_for_approval($batch_data[0]['third_faculty'],$batch_data);
            if($chk_third_faculty['flag'] == 'error')
            {
              $chk_faculty_flag = '0';
              $chk_faculty_msg .= 'This third faculty is mapped with '.$chk_third_faculty['response'].'. ';
            }
          }

          if($batch_data[0]['fourth_faculty'] > 0)
          {
            $chk_fourth_faculty = $this->validation_faculty_availability_for_approval($batch_data[0]['fourth_faculty'],$batch_data);
            if($chk_fourth_faculty['flag'] == 'error')
            {
              $chk_faculty_flag = '0';
              $chk_faculty_msg .= 'This fourth faculty is mapped with '.$chk_fourth_faculty['response'].'. ';
            }
          }          
          //END : CHECK THE FACULTY MAPPED CONDITION

          /* echo 'chk_faculty_flag : '.$chk_faculty_flag;
          echo '<br>chk_faculty_msg : '.$chk_faculty_msg; exit; */
          
          if($chk_faculty_flag == '1')
          {
            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');

            $this->master_model->updateRecord('iibfbcbf_agency_centre_batch', array('batch_status'=>'1', 'updated_on'=>date('Y-m-d H:i:s'), 'updated_by'=>$this->login_agency_or_centre_id), array('batch_id'=>$batch_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Centre : Batch Updated', 'iibfbcbf_agency_centre_batch', $this->db->last_query(), $batch_id,'batch_action','The batch has successfully submitted to IIBF for approval by the centre '.$centreName['disp_name'], $posted_arr);

            $result['flag'] = "success";
          }
          else
          {
            $result['response'] = $chk_faculty_msg." So can not send this batch for approval. Please edit the batch and update the faculties.";
          }
        }
			} 
			
      echo json_encode($result);
		}//END : SEND BATCH TO IIBF FOR APPROVAL

    /******** START : FUNCTION TO CHECK FACULTY IS NOT ALLOCATED MORE THAN 2 BATCHES IN SAME TIME ********/
    public function validation_faculty_availability_for_approval($chk_faculty_id='',$batch_data=array())
    {      
      $flag = "success";
      $response = '';

      if($chk_faculty_id != "")
      {
        $faculty_id = $chk_faculty_id; 
        $batch_id = $batch_data[0]['batch_id'];
        $batch_start_date = $batch_data[0]['batch_start_date'];
        $batch_end_date = $batch_data[0]['batch_end_date'];
        
        $get_faculty_ids = $this->master_model->getRecords('iibfbcbf_faculty_master fm1', array('fm1.faculty_id' => $faculty_id), 'fm1.faculty_id, fm1.pan_no, (SELECT GROUP_CONCAT(faculty_id) FROM iibfbcbf_faculty_master WHERE pan_no = fm1.pan_no) AS CheckFacultyIds');
        
        if(count($get_faculty_ids) > 0)
        {
          $this->db->where(" (first_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].") OR second_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].") OR third_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].")  OR fourth_faculty IN (".$get_faculty_ids[0]['CheckFacultyIds'].")) ");
          $this->db->where(" ( ('".$batch_start_date."' BETWEEN batch_start_date AND batch_end_date) OR ('".$batch_end_date."' BETWEEN batch_start_date AND batch_end_date)) ");
          $faculty_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('is_deleted' => '0', 'batch_id !=' => $batch_id, 'batch_status !=' => '5', 'batch_status !=' => '7'), 'batch_id, agency_id, batch_code, first_faculty, second_faculty, third_faculty, fourth_faculty');
          
          if(count($faculty_data) >= 2)
          {
            $batch_str = '';
            foreach($faculty_data as $res)
            {
              $batch_str .= $res['batch_code'].", ";
            }
            $response = rtrim($batch_str,", ");
            $flag = 'error';            
          }
        }                           
      }
      
      $result = array();
      $result['flag'] = $flag;
      $result['response'] = $response;
      return $result; 
    }/******** END : FUNCTION TO CHECK FACULTY IS NOT ALLOCATED MORE THAN 2 BATCHES IN SAME TIME ********/

    //START: CODE TO DISPLAY THE SELECTED HOLIDAYS IN ASCENDING ORDER 
    function sort_holidays_dates($selected_holidays_str='')
    {
      $holidaysStr = ''; 
      if ($selected_holidays_str != '') 
      {  
          $holidaysArr = explode(",",$selected_holidays_str);
          if(count($holidaysArr) > 0)
          {     
            // Custom function to convert d-m-Y date to Y-m-d format
            function convertToYmd($date) { return date("Y-m-d", strtotime($date)); }
      
            // Convert the date array using the custom function
            $holidaysArrYmd = array_map('convertToYmd', $holidaysArr);
            //echo '<br>'; print_r($holidaysArrYmd);

            sort($holidaysArrYmd);
            //echo '<br>'; print_r($holidaysArrYmd);

            // Custom function to convert Y-m-d date to d-m-Y format
            //function convertTodmY($date) { return date("d-m-Y", strtotime($date)); }

            $holidaysArrdmY = array_map('convertToYmd', $holidaysArrYmd);
            //echo '<br>'; print_r($holidaysArrdmY);

            $holidaysStr = implode(',', $holidaysArrdmY);
          } 
      } 
      return $holidaysStr; 
    }

  } ?>  