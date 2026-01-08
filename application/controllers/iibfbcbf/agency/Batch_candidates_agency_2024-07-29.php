<?php
  
  /********************************************************************************************************************
    ** Description: Controller for BCBF Centre Batches Candidates Master
    ** Created BY: Sagar Matale On 27-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Batch_candidates_agency extends CI_Controller
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
      
      $this->id_proof_file_path = 'uploads/iibfbcbf/id_proof';
      $this->qualification_certificate_file_path = 'uploads/iibfbcbf/qualification_certificate';
      $this->candidate_photo_path = 'uploads/iibfbcbf/photo';
      $this->candidate_sign_path = 'uploads/iibfbcbf/sign';
      
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
          $this->session->set_flashdata('error','You do not have permission to access All Candidates module');
          redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
    }
    
    public function index($enc_batch_id=0, $s_filter_type=0)
    {
      $data['enc_batch_id'] = $enc_batch_id;
      $data['s_filter_type'] = $s_filter_type;
      $chk_batch_start_date = $batch_candidate_count = '';
      
      if($enc_batch_id == '0') //FOR ALL CANDIDATE LISTING
      {
        $data['act_id'] = "All Candidates";
        $data['sub_act_id'] = "All Candidates";
        $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Training Batches : All Candidates List'; 
      }
      else  //FOR SPECIFIC BATCH CANDIDATE LISTING
      {
        $data['act_id'] = "Training Batches";
        $data['sub_act_id'] = "Training Batches";
        $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Training Batches : Batch Candidate List'; 
        
        $data['batch_id'] = $batch_id = url_decode($enc_batch_id);
        $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch', array('batch_id' => $batch_id, 'is_deleted' => '0'), 'batch_id, agency_id, centre_id, batch_code, batch_start_date, batch_end_date, total_candidates, batch_status');
        
        //$chk_batch_start_date = get_add_candidate_date($this->batches_id_edit_candidate_arr, $batch_id, $batch_data[0]['batch_start_date']);
        $chk_batch_start_date = get_add_candidate_date($this->Iibf_bcbf_model->extend_candidate_add_update_date_arr(), $batch_id, $batch_data[0]['batch_start_date']);//ADDED BY SAGAR TO ALLOW AGENCY TO ADD/EDIT CANDIDATE AFTER START DATE IS OVER  //iibfbcbf/iibf_bcbf_helper.php
        
        $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$batch_data[0]['agency_id'].'" AND centre_id = "'.$batch_data[0]['centre_id'].'" AND batch_id = "'.$batch_data[0]['batch_id'].'" AND is_deleted="0" ');
        $batch_candidate_count = $batch_candidate_qry->num_rows();
      }
      
      $data['agency_centre_data'] = array();
      if($this->login_user_type == 'agency') 
      {
        $agency_id = $this->login_agency_or_centre_id;
        
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');
      }
      
      $data['chk_batch_start_date'] = $chk_batch_start_date;
      $data['batch_candidate_count'] = $batch_candidate_count;
      
      $this->load->view('iibfbcbf/agency/candidate_list_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES DATA ********/
    public function get_batch_candidates_agency_data_ajax()
    {
      $table = 'iibfbcbf_batch_candidates bc';
      
      $column_order = array('bc.candidate_id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm2.city_name, ")") AS DispCentreName', 'acb.batch_code', 'bc.training_id', 'bc.regnumber', 'CONCAT(bc.salutation, " ", bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), "")) AS DispName', 'bc.dob', 'bc.mobile_no', 'bc.email_id', 'IF(bc.hold_release_status=1, "Auto Hold", IF(bc.hold_release_status=2, "Manual Hold", "Release")) AS HoldReleaseStatus', 'bc.batch_id', 'acb.batch_status', 'acb.batch_start_date', 'acb.batch_end_date', 'bc.hold_release_status'); //SET COLUMNS FOR SORT
      
      $column_search = array('acb.batch_code', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm2.city_name, ")")', 'bc.training_id', 'bc.regnumber', 'CONCAT(bc.salutation, " ", bc.first_name, IF(bc.middle_name != "", CONCAT(" ", bc.middle_name), ""), IF(bc.last_name != "", CONCAT(" ", bc.last_name), ""))', 'bc.dob', 'bc.mobile_no', 'bc.email_id', 'IF(bc.hold_release_status=1, "Auto Hold", IF(bc.hold_release_status=2, "Manual Hold", "Release"))'); //SET COLUMN FOR SEARCH
      $order = array('bc.candidate_id' => 'ASC', 'bc.batch_id' => 'DESC', ); // DEFAULT ORDER
      
      if($this->login_user_type == 'centre')
      {
        $WhereForTotal = "WHERE bc.centre_id = '".$this->login_agency_or_centre_id."' AND bc.is_deleted = '0' AND acb.is_deleted = '0' AND cm.is_deleted = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.centre_id = '".$this->login_agency_or_centre_id."' AND bc.is_deleted = '0' AND acb.is_deleted = '0' AND cm.is_deleted = '0' ";
      }
      else if($this->login_user_type == 'agency')
      {
        $WhereForTotal = "WHERE bc.agency_id = '".$this->login_agency_or_centre_id."' AND bc.is_deleted = '0' AND acb.is_deleted = '0' AND cm.is_deleted = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE bc.agency_id = '".$this->login_agency_or_centre_id."' AND bc.is_deleted = '0' AND acb.is_deleted = '0' AND cm.is_deleted = '0' ";
      }
      
      $enc_batch_id = trim($this->security->xss_clean($this->input->post('enc_batch_id')));
      if($enc_batch_id != "" && $enc_batch_id != "0") 
      {
        $WhereForTotal .= " AND acb.batch_id = '".url_decode($enc_batch_id)."'"; 
        $Where .= " AND acb.batch_id = '".url_decode($enc_batch_id)."'"; 
      }
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }
      
      //CUSTOM SEARCH
      if(isset($_POST['s_centre']))
      {
        $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
        if($s_centre != "") { $Where .= " AND bc.centre_id = '".$s_centre."'"; } 
      }
      
      if(isset($_POST['s_batch_code']))
      {
        $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
        if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".$s_batch_code."%'"; } 
      }
      
      $s_name = trim($this->security->xss_clean($this->input->post('s_name')));
      if($s_name != "") { $Where .= " AND CONCAT(bc.salutation, ' ', bc.first_name, IF(bc.middle_name != '', CONCAT(' ', bc.middle_name), ''), IF(bc.last_name != '', CONCAT(' ', bc.last_name), '')) LIKE '%".$s_name."%'"; } 
      
      $s_regnumber = trim($this->security->xss_clean($this->input->post('s_regnumber')));
      if($s_regnumber != "") { $Where .= " AND bc.regnumber LIKE '%".$s_regnumber."%'"; } 
      
      $s_status = trim($this->security->xss_clean($this->input->post('s_status')));
      if($s_status != "") 
      {
        if($s_status == '4') //Auto Hold + Manual Hold
        {
          $Where .= " AND bc.hold_release_status != '3'";
        }
        else
        {
          $Where .= " AND bc.hold_release_status = '".$s_status."'"; 
        }
      }
      
      $s_filter_type = trim($this->security->xss_clean($this->input->post('s_filter_type')));
      if($s_filter_type != "") 
      {
        if($s_filter_type == '1') //Total Training Completed Candidates
        {
          $Where .= " AND bc.hold_release_status = '3' AND acb.batch_end_date < '".date('Y-m-d')."' ";
        }
        else if($s_filter_type == '2') //Total Hold Candidates
        {
          $Where .= " AND (bc.hold_release_status = '1' OR bc.hold_release_status = '2') ";
        }
        else if($s_filter_type == '3') //Total Exam Applied Candidates
        {
          $Where .= " AND bc.re_attempt > 0 ";
        }
        else if($s_filter_type == '4') //Total Exam Not Applied Candidates
        {
          $Where .= " AND bc.re_attempt = '0' AND bc.hold_release_status = '3' ";
        }
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " INNER JOIN iibfbcbf_agency_centre_batch acb ON acb.batch_id = bc.batch_id";
      $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = bc.centre_id";
      $join_qry .= " LEFT JOIN city_master cm2 ON cm2.id = cm.centre_city";
      
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
        $row[] = $Res['training_id'];
        $row[] = $Res['regnumber'];
        $row[] = $Res['DispName'];
        $row[] = $Res['dob'];
        $row[] = $Res['mobile_no'];
        $row[] = $Res['email_id'];
        
        if($this->login_user_type == 'agency')
        {
          $disabled_txt = '';
          $hover_txt = 'Click to make it Release';
          if($Res['hold_release_status']==3) { $hover_txt = 'Click to make it Manual Hold'; }
          
          if(date('Y-m-d') < $Res['batch_start_date'])
          {
            $disabled_txt = 'disabled';
            $hover_txt = 'The Batch is not started yet';
          }
          else if(date('Y-m-d') > $Res['batch_end_date']) 
          {
            $disabled_txt = 'disabled';
            $hover_txt = 'The Batch End Date is Over';
          }
          
          if($Res['hold_release_status']==3) { $is_check = "checked"; } else { $is_check = ""; }        
          $hold_text = 'Manual Hold'; if($Res['hold_release_status']==1) { $hold_text = 'Auto Hold'; }
          $onchange_fun = "change_hold_release_status('".$Res['candidate_id']."', '".$Res['hold_release_status']."')";
          $row[] = '<div id="toggle_outer_'.$Res['candidate_id'].'" class="'.$disabled_txt.'" title="'.$hover_txt.'"><input '.$is_check.' value="'.$Res['candidate_id'].'" data-toggle="toggle" data-on="Release" data-off="'.$hold_text.'" data-onstyle="success" data-offstyle="danger" id="toogle_id_'.$Res['candidate_id'].'" onchange="'.$onchange_fun.'" type="checkbox" '.$disabled_txt.'></div>';
          }
        else if($this->login_user_type == 'centre')
        {
          $row[] = $Res['HoldReleaseStatus'];
        }
        
        $btn_str = ' <div class="text-center no_wrap"> ';
        
        $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/batch_candidates_agency/candidates_details_agency/'.$enc_batch_id.'/'.url_encode($Res['candidate_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        //$chk_candidate_edit_date = $this->Iibf_bcbf_model->calculate_batch_date_for_edit_candidate($this->batches_id_edit_candidate_arr,$Res['batch_id']);
        $chk_candidate_edit_date = $this->Iibf_bcbf_model->calculate_batch_date_for_edit_candidate($this->Iibf_bcbf_model->extend_candidate_add_update_date_arr(),$Res['batch_id']);
        
        if($Res['batch_status'] == '3' && $chk_candidate_edit_date >= date("Y-m-d") && $enc_batch_id != "" && $enc_batch_id != "0" && $this->login_user_type == 'centre')
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/agency/batch_candidates_agency/add_candidates_agency/'.url_encode($Res['batch_id']).'/'.url_encode($Res['candidate_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
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
      "Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES DATA ********/
    
    /******** START : ADD / UPDATE CANDIDATES DATA ********/
    public function add_candidates_agency($enc_batch_id=0, $enc_candidate_id=0)
    {
      if($this->login_user_type != 'centre') //only centre can add candidates
      {
        $this->session->set_flashdata('error','You do not have permission to access the Add Candidates module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
      
      $agency_id = '0';
      $centre_id = '0';
      if($this->login_user_type == 'agency') { $agency_id = $this->login_agency_or_centre_id; }
      else if($this->login_user_type == 'centre') //get centre data
      {
        $centre_id = $this->login_agency_or_centre_id;
        
        $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$centre_id), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode');
        
        if(count($centre_data) > 0) { $agency_id = $centre_data[0]['agency_id']; }
      }
      
      //START : CHECK IF BATCH EXIST OR NOT. ALSO CHECK BATCH STATUS AS GO AHEAD
      if($enc_batch_id == '0') { redirect(site_url('iibfbcbf/agency/training_batches_agency')); }
      else
      {
        $batch_id = url_decode($enc_batch_id);
        
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = acb.agency_id', 'LEFT');
        $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'LEFT');
        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.centre_id' => $this->login_agency_or_centre_id, 'acb.batch_id' => $batch_id, 'acb.batch_status'=>'3', 'acb.is_deleted' => '0', 'am.is_active' => '1', 'am.is_deleted' => '0', 'cm.status' => '1', 'cm.is_deleted' => '0'), "acb.*, am.agency_name, sm.state_name AS CentreState, cm.centre_district, cm1.city_name AS CentreCity");        
        if(count($batch_data) == 0) { redirect(site_url('iibfbcbf/agency/training_batches_agency')); }      
      }//END : CHECK IF BATCH EXIST OR NOT. ALSO CHECK BATCH STATUS AS GO AHEAD
      
      //START : FIND OUT THE CURRENT MODE AND GET THE CANDIDATES DATA
      if($enc_candidate_id == '0') 
      {
        $data['mode'] = $mode = "Add"; $candidate_id = $enc_candidate_id;
        
        //$dob_start_date = date('Y-m-d', strtotime("- 70 year", strtotime(date('Y-m-d'))));
        $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));
        
        //START : CAN NOT ADD MORE CANDIDATE THAN TOTAL BATCH CAPACITY CANDIDATES
        $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$agency_id.'" AND centre_id = "'.$centre_id.'" AND batch_id = "'.$batch_id.'" AND is_deleted="0" ');
        $batch_candidate_count = $batch_candidate_qry->num_rows();
        if($batch_candidate_count >= $batch_data[0]['total_candidates'])
        {
          $this->session->set_flashdata('error','You can not add more than '.$batch_data[0]['total_candidates'].' candidates in this batch');  
          redirect(site_url('iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id));
        }//END : CAN NOT ADD MORE CANDIDATE THAN TOTAL BATCH CAPACITY CANDIDATES
      }
      else
      {
        $candidate_id = url_decode($enc_candidate_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.centre_id' => $this->login_agency_or_centre_id, 'bc.candidate_id' => $candidate_id, 'bc.batch_id' => $batch_data[0]['batch_id'], 'bc.is_deleted' => '0'), "bc.*, IF(bc.gender=1,'Male','Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2,'Graduate','Post Graduate')) AS DispQualification");
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/batch_candidates_agency/add_candidates_agency')); }
        
        $data['mode'] = $mode = "Update";
        
        //$dob_start_date = date('Y-m-d', strtotime("- 70 year", strtotime(date('Y-m-d', strtotime($form_data[0]['created_on'])))));
        //$dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d', strtotime($form_data[0]['created_on'])))));
        $dob_end_date = date('Y-m-d', strtotime("- 18 year", strtotime(date('Y-m-d'))));
      }//END : FIND OUT THE CURRENT MODE AND GET THE CANDIDATES DATA
      
      //START : IF BATCH EDIT CANDIDATE DATE IS OVER, THEN REDIRECT THE PAGE TO CANDIDATE LISTING
      //$data['chk_candidate_edit_date'] = $chk_candidate_edit_date = $this->Iibf_bcbf_model->calculate_batch_date_for_edit_candidate($this->batches_id_edit_candidate_arr, $batch_id);
      $data['chk_candidate_edit_date'] = $chk_candidate_edit_date = $this->Iibf_bcbf_model->calculate_batch_date_for_edit_candidate($this->Iibf_bcbf_model->extend_candidate_add_update_date_arr(), $batch_id);
      
      if($chk_candidate_edit_date < date("Y-m-d"))
      {
        $this->session->set_flashdata('error','You can not add or update the candidate details after '.$chk_candidate_edit_date);  
        redirect(site_url('iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id));
      }//END : IF BATCH EDIT CANDIDATE DATE IS OVER, THEN REDIRECT THE PAGE TO CANDIDATE LISTING
      
      $error_flag = 0;
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";
      $data['enc_batch_id'] = $enc_batch_id;
      $data['enc_candidate_id'] = $enc_candidate_id;
      $data['id_proof_file_error'] = $data['qualification_certificate_file_error'] = $data['candidate_photo_error'] = $data['candidate_sign_error'] = '';
      $data['id_proof_file_path'] = $id_proof_file_path = $this->id_proof_file_path;
      $data['qualification_certificate_file_path'] = $qualification_certificate_file_path = $this->qualification_certificate_file_path;
      $data['candidate_photo_path'] = $candidate_photo_path = $this->candidate_photo_path;
      $data['candidate_sign_path'] = $candidate_sign_path = $this->candidate_sign_path;
      //$data['dob_start_date'] = $dob_start_date;
      $data['dob_end_date'] = $dob_end_date;
      
      //$data['chk_batch_start_date'] = $chk_batch_start_date = get_add_candidate_date($this->batches_id_edit_candidate_arr, $batch_id, $batch_data[0]['batch_start_date']);      
      $data['chk_batch_start_date'] = $chk_batch_start_date = get_add_candidate_date($this->Iibf_bcbf_model->extend_candidate_add_update_date_arr(), $batch_id, $batch_data[0]['batch_start_date']);//ADDED BY SAGAR TO ALLOW AGENCY TO ADD/EDIT CANDIDATE AFTER START DATE IS OVER  //iibfbcbf/iibf_bcbf_helper.php
      
      if(isset($_POST) && count($_POST) > 0)
      {
        /* _pa($_FILES); _pa($_POST); */
        $validate_form_type = 'full';
        if($mode == 'Add')
        {
          $form_action = $this->security->xss_clean($this->input->post('form_action'));
          if($form_action == '1') { $validate_form_type = 'basic'; }
        }
        
        $id_proof_file_req_flg = $qualification_certificate_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg = 'n';
        
        if($mode == 'Add') 
        {
          $id_proof_file_req_flg = $qualification_certificate_file_req_flg = $candidate_photo_req_flg = $candidate_sign_req_flg = 'y';
          if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "")
          {
            if (isset($_POST['id_proof_file_old']) && $_POST['id_proof_file_old'] != "")
            {
              $id_proof_file_req_flg = 'n';
            }
            if (isset($_POST['qualification_certificate_file_old']) && $_POST['qualification_certificate_file_old'] != "")
            {
              $qualification_certificate_file_req_flg = 'n';
            }
            if (isset($_POST['candidate_photo_old']) && $_POST['candidate_photo_old'] != "")
            {
              $candidate_photo_req_flg = 'n';
            }
            if (isset($_POST['candidate_sign_old']) && $_POST['candidate_sign_old'] != "")
            {
              $candidate_sign_req_flg = 'n';
            }
          }
        	else
          {
            if(isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "") { $id_proof_file_req_flg = 'n'; }
            if(isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "") { $qualification_certificate_file_req_flg = 'n'; }
            if(isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "") { $candidate_photo_req_flg = 'n'; }
            if(isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "") { $candidate_sign_req_flg = 'n'; }
          }
        }
        else
        {
          if ($form_data[0]['id_proof_file'] == "")
          {
            $id_proof_file_req_flg = 'y';
          }
          if ($form_data[0]['qualification_certificate_file'] == "")
          {
            $qualification_certificate_file_req_flg = 'y';
          }
          if ($form_data[0]['candidate_photo'] == "")
          {
            $candidate_photo_req_flg = 'y';
          }
          if ($form_data[0]['candidate_sign'] == "")
          {
            $candidate_sign_req_flg = 'y';
          }
        }
        
        if ($chk_batch_start_date >= date("Y-m-d")) //Centre can update the candidate's basic details till batch first day.
        {
          $this->form_validation->set_rules('salutation', 'candidate name (salutation)', 'trim|required|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('first_name', 'first name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('middle_name', 'middle name', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean');
          $this->form_validation->set_rules('last_name', 'last name', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[20]|xss_clean');
          //$this->form_validation->set_rules('dob', 'date of birth', 'trim|required|callback_fun_validate_dob['.$dob_start_date.'####'.$dob_end_date.']|xss_clean', array('required'=>"Please select the %s"));        
          $this->form_validation->set_rules('dob', 'date of birth', 'trim|required|callback_fun_validate_dob[' . $dob_end_date . ']|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('gender', 'gender', 'trim|required|callback_fun_validate_gender|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('mobile_no', 'mobile number', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_validation_check_mobile_exist[' . $enc_candidate_id . ']|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('alt_mobile_no', 'alternate mobile number', 'trim|callback_fun_restrict_input[allow_only_numbers]|min_length[10]|max_length[10]|callback_fun_restrict_input[first_zero_not_allowed]|xss_clean');
          $this->form_validation->set_rules('email_id', 'email id', 'trim|required|max_length[80]|valid_email|callback_validation_check_email_exist['.$enc_candidate_id.']|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('alt_email_id', 'alternate email id', 'trim|max_length[80]|valid_email|xss_clean');
          $this->form_validation->set_rules('qualification', 'qualification', 'trim|required|callback_validation_check_qualification_candidates['.$enc_batch_id.'###'.$enc_candidate_id.']|xss_clean', array('required'=>"Please select the %s"));  
        }
        
        if($validate_form_type == 'full') //THIS FIELDS VALIDATE ONLY IF USER CLICK ON SUBMIT II BUTTON
        {
          $this->form_validation->set_rules('address1', 'address line-1', 'trim|required|max_length[75]|xss_clean', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('address2', 'address line-2', 'trim|max_length[75]|xss_clean');
          $this->form_validation->set_rules('address3', 'address line-3', 'trim|max_length[75]|xss_clean');
          $this->form_validation->set_rules('address4', 'address line-4', 'trim|max_length[75]|xss_clean');
          $this->form_validation->set_rules('state', 'state', 'trim|required|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('city', 'city', 'trim|required|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('district', 'district', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers_and_space]|max_length[30]|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('pincode', 'pincode', 'trim|required|callback_fun_restrict_input[allow_only_numbers]|callback_validation_check_valid_pincode[' . $this->input->post('state') . ']|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('bank_associated', 'bank associated with', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('corporate_bc_associated', 'corporate bc associated with', 'trim|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required' => "Please enter the %s"));
          $this->form_validation->set_rules('id_proof_type', 'id proof type', 'trim|required|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('id_proof_number', 'id proof number', 'trim|callback_fun_validate_id_proof_number[' . $this->input->post('id_proof_type') . ']|callback_validation_check_id_proof_number_exist[' . $enc_candidate_id . ']|xss_clean');
          $this->form_validation->set_rules('id_proof_file', 'proof of identity', 'callback_fun_validate_file_upload[id_proof_file|' . $id_proof_file_req_flg . '|jpg,jpeg,png|100|proof of identity|75]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'
          $this->form_validation->set_rules('qualification_certificate_type', 'qualification certificate type', 'trim|required|callback_fun_validate_qualification_certificate_type[' . $this->input->post('qualification') . ']|xss_clean', array('required' => "Please select the %s"));
          $this->form_validation->set_rules('qualification_certificate_file', 'qualification certificate', 'callback_fun_validate_file_upload[qualification_certificate_file|' . $qualification_certificate_file_req_flg . '|jpg,jpeg,png|100|qualification certificate|75]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'  
          $this->form_validation->set_rules('candidate_photo', 'passport-size photo of the candidate', 'callback_fun_validate_file_upload[candidate_photo|' . $candidate_photo_req_flg . '|jpg,jpeg,png|20|passport photo of the candidate|14]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'  
          $this->form_validation->set_rules('candidate_sign', 'signature of the candidate', 'callback_fun_validate_file_upload[candidate_sign|' . $candidate_sign_req_flg . '|jpg,jpeg,png|20|signature of the candidate|14]'); //callback parameter separated by pipe 'input name|required|allowed extension|max size in kb|display name|min size in kb'        
          $this->form_validation->set_rules('aadhar_no', 'aadhar number', 'trim|callback_fun_restrict_input[allow_only_numbers]|callback_CheckAadharNumberWithIdProof|min_length[12]|max_length[12]|callback_validation_check_aadhar_no_exist[' . $enc_candidate_id . ']|xss_clean');
        }
        
        if ($this->form_validation->run())
        {
          $new_id_proof_file = $new_qualification_certificate_file = $new_candidate_photo = $new_candidate_sign = '';
          
          $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);
          /* if (isset($form_data) && count($form_data) > 0)
            {
            if (isset($form_data[0]['regnumber']) && $form_data[0]['regnumber'] != "")
            {
            $file_name_str = $form_data[0]['regnumber'];
            }
            }
            else if (isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['old_regnumber']) && $_POST['old_regnumber'] != "")
            {
            $file_name_str = $_POST['old_regnumber'];
          } */
          
          if ($validate_form_type == 'full') //THIS FIELDS VALIDATE ONLY IF USER CLICK ON SUBMIT II BUTTON
          {
            if ($_FILES['id_proof_file']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name1 = "id_proof_" . $file_name_str;
              /* $upload_data1 = $this->Iibf_bcbf_model->upload_file("id_proof_file", array('jpg', 'jpeg', 'png'), $new_file_name1, "./" . $id_proof_file_path, "jpg|jpeg|png", '', '', '', '', '100', '1000', '500', $new_file_name1); */
              $upload_data1 = $this->Iibf_bcbf_model->upload_file("id_proof_file", array('jpg', 'jpeg', 'png'), $new_file_name1, "./" . $id_proof_file_path, "jpg|jpeg|png", '', '', '', '', '100', '', '', $new_file_name1);
              
              if($upload_data1['response'] == 'error')
              {
                $data['id_proof_file_error'] = $upload_data1['message'];
                $error_flag = 1;
              }
              else if($upload_data1['response'] == 'success')
              {
                $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = $upload_data1['message'];
              }
            }
            else if(isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['id_proof_file_old']) && $_POST['id_proof_file_old'] != "")
            {
              $id_proof_file_old = $this->security->xss_clean($this->input->post('id_proof_file_old'));
              if(isset($_POST['parent_table']) && $_POST['parent_table'] == "iibfbcbf_batch_candidates")
              {
                $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($id_proof_file_old);
              }
              else
              {
                $new_file_name1 = "id_proof_".$file_name_str.'.'.strtolower(pathinfo($id_proof_file_old, PATHINFO_EXTENSION));
                if(copy($id_proof_file_old, $id_proof_file_path.'/'.$new_file_name1))
                {
                  $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);
                }
                else
                {
                  $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
                  $error_flag = 1;
                }
              }
            }
            else if(isset($_POST['id_proof_file_cropper']) && $_POST['id_proof_file_cropper'] != "")
            {
              $id_proof_file_cropper = $this->security->xss_clean($this->input->post('id_proof_file_cropper'));
              $new_file_name1 = "id_proof_".$file_name_str.'.'.strtolower(pathinfo($id_proof_file_cropper, PATHINFO_EXTENSION));
              if(copy($id_proof_file_cropper, $id_proof_file_path.'/'.$new_file_name1))
              {
                $add_data['id_proof_file'] = $id_proof_file = $new_id_proof_file = basename($new_file_name1);
              }
              else
              {
                $data['id_proof_file_error'] = 'Please upload valid Proof of Identity';
                $error_flag = 1;
              }
            }            
            
            if($_FILES['qualification_certificate_file']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name2 = "quali_cert_".$file_name_str;
              /* $upload_data2 = $this->Iibf_bcbf_model->upload_file("qualification_certificate_file", array('jpg','jpeg','png'), $new_file_name2, "./".$qualification_certificate_file_path, "jpg|jpeg|png", '', '', '', '', '100','1500','1000',$new_file_name2); */
              $upload_data2 = $this->Iibf_bcbf_model->upload_file("qualification_certificate_file", array('jpg','jpeg','png'), $new_file_name2, "./".$qualification_certificate_file_path, "jpg|jpeg|png", '', '', '', '', '100','','',$new_file_name2);
              if($upload_data2['response'] == 'error')
              {
                $data['qualification_certificate_file_error'] = $upload_data2['message'];
                $error_flag = 1;
              }
              else if($upload_data2['response'] == 'success')
              {
                $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = $upload_data2['message'];
              }
            }
            else if(isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['qualification_certificate_file_old']) && $_POST['qualification_certificate_file_old'] != "")
            {
              $qualification_certificate_file_old = $this->security->xss_clean($this->input->post('qualification_certificate_file_old'));
              if(isset($_POST['parent_table']) && $_POST['parent_table'] == "iibfbcbf_batch_candidates")
              {
                $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($qualification_certificate_file_old);
              }
              else
              {
                $new_file_name4 = "quali_cert_".$file_name_str.'.'.strtolower(pathinfo($qualification_certificate_file_old, PATHINFO_EXTENSION));
                if(copy($qualification_certificate_file_old, $qualification_certificate_file_path.'/'.$new_file_name4))
                {
                  $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($new_file_name4);
                }
                else
                {
                  $data['qualification_certificate_file_error'] = 'Please upload valid Qualification Certificate Photo';
                  $error_flag = 1;
                }
              }
            }
            else if(isset($_POST['qualification_certificate_file_cropper']) && $_POST['qualification_certificate_file_cropper'] != "")
            {
              $qualification_certificate_file_cropper = $this->security->xss_clean($this->input->post('qualification_certificate_file_cropper'));
              
              $new_file_name4 = "quali_cert_".$file_name_str.'.'.strtolower(pathinfo($qualification_certificate_file_cropper, PATHINFO_EXTENSION));
              if(copy($qualification_certificate_file_cropper, $qualification_certificate_file_path.'/'.$new_file_name4))
              {
                $add_data['qualification_certificate_file'] = $qualification_certificate_file = $new_qualification_certificate_file = basename($new_file_name4);
              }
              else
              {
                $data['qualification_certificate_file_error'] = 'Please upload valid Qualification Certificate Photo';
                $error_flag = 1;
              }              
            }
            
            if($_FILES['candidate_photo']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name3 = "photo_".$file_name_str;
              /* $upload_data3 = $this->Iibf_bcbf_model->upload_file("candidate_photo", array('jpg','jpeg','png'), $new_file_name3, "./".$candidate_photo_path, "jpg|jpeg|png", '', '', '', '', '20','1000','500',$new_file_name3); */
              $upload_data3 = $this->Iibf_bcbf_model->upload_file("candidate_photo", array('jpg','jpeg','png'), $new_file_name3, "./".$candidate_photo_path, "jpg|jpeg|png", '', '', '', '', '20','','',$new_file_name3);
              if($upload_data3['response'] == 'error')
              {
                $data['candidate_photo_error'] = $upload_data3['message'];
                $error_flag = 1;
              }
              else if($upload_data3['response'] == 'success')
              {
                $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = $upload_data3['message'];
              }
            }
            else if(isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['candidate_photo_old']) && $_POST['candidate_photo_old'] != "")
            {
              $candidate_photo_old = $this->security->xss_clean($this->input->post('candidate_photo_old'));
              if(isset($_POST['parent_table']) && $_POST['parent_table'] == "iibfbcbf_batch_candidates")
              {
                $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($candidate_photo_old);
              }
              else
              {
                $new_file_name3 = "photo_".$file_name_str.'.'.strtolower(pathinfo($candidate_photo_old, PATHINFO_EXTENSION));
                if(copy($candidate_photo_old, $candidate_photo_path.'/'.$new_file_name3))
                {
                  $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
                }
                else
                {
                  $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
                  $error_flag = 1;
                }
              }
            }
            else if(isset($_POST['candidate_photo_cropper']) && $_POST['candidate_photo_cropper'] != "")
            {
              $candidate_photo_cropper = $this->security->xss_clean($this->input->post('candidate_photo_cropper'));
              
              $new_file_name3 = "photo_".$file_name_str.'.'.strtolower(pathinfo($candidate_photo_cropper, PATHINFO_EXTENSION));
              if(copy($candidate_photo_cropper, $candidate_photo_path.'/'.$new_file_name3))
              {
                $add_data['candidate_photo'] = $candidate_photo = $new_candidate_photo = basename($new_file_name3);
              }
              else
              {
                $data['candidate_photo_error'] = 'Please upload valid Passport-size Photo';
                $error_flag = 1;
              }              
            }
            
            if($_FILES['candidate_sign']['name'] != "")
            {
              //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
              $new_file_name4 = "sign_".$file_name_str;
              /* $upload_data4 = $this->Iibf_bcbf_model->upload_file("candidate_sign", array('jpg','jpeg','png'), $new_file_name4, "./".$candidate_sign_path, "jpg|jpeg|png", '', '', '', '', '20','1000','500',$new_file_name4); */
              $upload_data4 = $this->Iibf_bcbf_model->upload_file("candidate_sign", array('jpg','jpeg','png'), $new_file_name4, "./".$candidate_sign_path, "jpg|jpeg|png", '', '', '', '', '20','','',$new_file_name4);
              if($upload_data4['response'] == 'error')
              {
                $data['candidate_sign_error'] = $upload_data4['message'];
                $error_flag = 1;
              }
              else if($upload_data4['response'] == 'success')
              {
                $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = $upload_data4['message'];
              }
            }
            else if(isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "" && isset($_POST['candidate_sign_old']) && $_POST['candidate_sign_old'] != "")
            {
              $candidate_sign_old = $this->security->xss_clean($this->input->post('candidate_sign_old'));
              if(isset($_POST['parent_table']) && $_POST['parent_table'] == "iibfbcbf_batch_candidates")
              {
                $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($candidate_sign_old);
              }
              else
              {
                $new_file_name4 = "sign_".$file_name_str.'.'.strtolower(pathinfo($candidate_sign_old, PATHINFO_EXTENSION));
                if(copy($candidate_sign_old, $candidate_sign_path.'/'.$new_file_name4))
                {
                  $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
                }
                else
                {
                  $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
                  $error_flag = 1;
                }
              }
            }
            else if(isset($_POST['candidate_sign_cropper']) && $_POST['candidate_sign_cropper'] != "")
            {
              $candidate_sign_cropper = $this->security->xss_clean($this->input->post('candidate_sign_cropper'));
              $new_file_name4 = "sign_".$file_name_str.'.'.strtolower(pathinfo($candidate_sign_cropper, PATHINFO_EXTENSION));
              if(copy($candidate_sign_cropper, $candidate_sign_path.'/'.$new_file_name4))
              {
                $add_data['candidate_sign'] = $candidate_sign = $new_candidate_sign = basename($new_file_name4);
              }
              else
              {
                $data['candidate_sign_error'] = 'Please upload valid Signature of the Candidate';
                $error_flag = 1;
              }
            }
          }
          
          if($error_flag == 1)
          {
            @unlink("./".$id_proof_file_path."/".$upload_data1['message']);
            @unlink("./".$qualification_certificate_file_path."/".$upload_data2['message']);
            @unlink("./".$candidate_photo_path."/".$upload_data3['message']);
            @unlink("./".$candidate_sign_path."/".$upload_data4['message']);
          }
          else if($error_flag == 0)
          {
            $posted_arr = json_encode($_POST).' >> '.json_encode($_FILES);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($centre_id, 'centre');
            
            //echo 'IN';exit;
            if ($chk_batch_start_date >= date("Y-m-d"))//Centre can update the candidate's basic details till batch first day.
            {
              $add_data['agency_id'] = $agency_id;
              $add_data['centre_id'] = $centre_id;
              $add_data['batch_id'] = $batch_id;
              $add_data['salutation'] = $this->input->post('salutation');
              $add_data['first_name'] = $this->input->post('first_name');
              $add_data['middle_name'] = $this->input->post('middle_name');
              $add_data['last_name'] = $this->input->post('last_name');
              $add_data['dob'] = $this->input->post('dob');
              $add_data['gender'] = $this->input->post('gender');
              $add_data['mobile_no'] = $this->input->post('mobile_no');
              $add_data['alt_mobile_no'] = $this->input->post('alt_mobile_no');
              $add_data['email_id'] = strtolower($this->input->post('email_id'));
              $add_data['alt_email_id'] = strtolower($this->input->post('alt_email_id'));
              $add_data['qualification'] = $this->input->post('qualification');
            }
            
            if($validate_form_type == 'full') //THIS FIELDS INSERT ONLY IF USER CLICK ON SUBMIT II BUTTON
            {
              $add_data['address1'] = $this->input->post('address1');
              $add_data['address2'] = $this->input->post('address2');
              $add_data['address3'] = $this->input->post('address3');
              $add_data['address4'] = $this->input->post('address4');
              $add_data['state'] = $this->input->post('state');
              $add_data['city'] = $this->input->post('city');
              $add_data['district'] = $this->input->post('district');
              $add_data['pincode'] = $this->input->post('pincode');
              $add_data['bank_associated'] = $this->input->post('bank_associated');
              $add_data['corporate_bc_associated'] = $this->input->post('corporate_bc_associated');
              $add_data['id_proof_type'] = $this->input->post('id_proof_type');
              $add_data['id_proof_number'] = $this->input->post('id_proof_number');
              $add_data['qualification_certificate_type'] = $this->input->post('qualification_certificate_type');
              $add_data['aadhar_no'] = $this->input->post('aadhar_no');
            }
            
            $add_data['hold_release_status'] = '3';
            $add_data['ip_address'] = get_ip_address(); //general_helper.php   
            
            if($validate_form_type == 'full') //THIS CONDITION CHECK ONLY IF USER CLICK ON SUBMIT II BUTTON
            {
              //START : IF FILE NOT EXIST WHILE ADDING / UPDATING THE RECORD, THEN REDIRECT & SHOW ERROR MESSAGE
              $chk_id_proof_file = $chk_qualification_certificate_file = $chk_candidate_photo = $chk_candidate_sign = '';
              if($mode == "Add")
              {
                $chk_id_proof_file = $id_proof_file;
                $chk_qualification_certificate_file = $qualification_certificate_file;
                $chk_candidate_photo = $candidate_photo;
                $chk_candidate_sign = $candidate_sign;
              }
              else if($mode == "Update")
                {
                if($new_id_proof_file == '') { $chk_id_proof_file = $form_data[0]['id_proof_file']; }
                else if($new_id_proof_file != '') { $chk_id_proof_file = $new_id_proof_file; }
                
                if($new_qualification_certificate_file == '') { $chk_qualification_certificate_file = $form_data[0]['qualification_certificate_file']; }
                else if($new_qualification_certificate_file != '') { $chk_qualification_certificate_file = $new_qualification_certificate_file; }
                
                if($new_candidate_photo == '') { $chk_candidate_photo = $form_data[0]['candidate_photo']; }
                else if($new_candidate_photo != '') { $chk_candidate_photo = $new_candidate_photo; }
                
                if($new_candidate_sign == '') { $chk_candidate_sign = $form_data[0]['candidate_sign']; }
                else if($new_candidate_sign != '') { $chk_candidate_sign = $new_candidate_sign; }
              }
              
              $this->Iibf_bcbf_model->check_file_exist($chk_id_proof_file, "./".$id_proof_file_path."/", 'iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id, 'Candidate record not added due to missing Proof of Identity');//IF FILE NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              
              $this->Iibf_bcbf_model->check_file_exist($chk_qualification_certificate_file, "./".$qualification_certificate_file_path."/", 'iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id, 'Candidate record not added due to missing Qualification Certificate');//IF FILE NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              
              $this->Iibf_bcbf_model->check_file_exist($chk_candidate_photo, "./".$candidate_photo_path."/", 'iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id, 'Candidate record not added due to missing Passport Photograph of the Candidate');//IF FILE NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              
              $this->Iibf_bcbf_model->check_file_exist($chk_candidate_sign, "./".$candidate_sign_path."/", 'iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id, 'Candidate record not added due to missing Signature of the Candidate');//IF FILE NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              //END : IF FILE NOT EXIST WHILE ADDING / UPDATING THE RECORD, THEN REDIRECT & SHOW ERROR MESSAGE
            }
            
            if($mode == "Add") 
            {
              if(isset($_POST['old_candidate_id']) && $_POST['old_candidate_id'] != "")
              {
                $add_data['parent_id'] = $this->security->xss_clean($this->input->post('old_candidate_id'));
                $add_data['parent_table_name'] = $this->security->xss_clean($this->input->post('parent_table'));
                
                if(isset($_POST['old_regnumber']) && $_POST['old_regnumber'] != "")
                {
                  $add_data['regnumber'] = $this->security->xss_clean($this->input->post('old_regnumber'));
                }
                
                if(isset($_POST['old_registration_type']) && $_POST['old_registration_type'] != "")
                {
                  $add_data['registration_type'] = $this->security->xss_clean($this->input->post('old_registration_type'));
                }
              }
              
              $add_data['created_on'] = date("Y-m-d H:i:s");
              $add_data['created_by'] = $this->login_agency_or_centre_id;
              
              /* _pa($add_data,1); */
              $this->master_model->insertRecord('iibfbcbf_batch_candidates ',$add_data);
              $candidate_id = $this->db->insert_id();
              
              if($candidate_id > 0)
              {
                $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate Added', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_id,'candidate_action','The candidate has successfully added by the centre '.$centreName['disp_name'], $posted_arr); 
                
                $this->session->set_flashdata('success','Candidate record added successfully');              
              }
              else
              {
                $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate Added Error', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_id,'candidate_action','The candidate has not added by the centre '.$centreName['disp_name'], $posted_arr); 
                
                $this->session->set_flashdata('error','Error occurred. Please try again.'); 
              }
            }
            else if ($mode == "Update")
            {
              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_batch_candidates', $add_data, array('candidate_id' => $candidate_id));
              
              //START : If new image upload, then unlink the old images for candidate whose regnumber is empty
              if (isset($form_data[0]['regnumber']) && $form_data[0]['regnumber'] == "")
              {
                if ($new_id_proof_file != '')
                {
                  @unlink("./" . $id_proof_file_path . "/" . $form_data[0]['id_proof_file']);
                }
                if ($new_qualification_certificate_file != '')
                {
                  @unlink("./" . $qualification_certificate_file_path . "/" . $form_data[0]['qualification_certificate_file']);
                }
                if ($new_candidate_photo != '')
                {
                  @unlink("./" . $candidate_photo_path . "/" . $form_data[0]['candidate_photo']);
                }
                if ($new_candidate_sign != '')
                {
                  @unlink("./" . $candidate_sign_path . "/" . $form_data[0]['candidate_sign']);
                }
              }//END : If new image upload, then unlink the old images for candidate whose regnumber is empty
              
              $this->Iibf_bcbf_model->insert_common_log('Centre : Candidate Updated', 'iibfbcbf_batch_candidates', $this->db->last_query(), $candidate_id, 'candidate_action', 'The candidate has successfully updated by the centre ' . $centreName['disp_name'], $posted_arr);
              
              $this->session->set_flashdata('success', 'Candidate record updated successfully');
            }
            
            // START : Rename the images and update into database table name
            if ($candidate_id > 0)
            {
              $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.regnumber !='=>''), "bc.candidate_id, bc.regnumber, bc.id_proof_file, bc.qualification_certificate_file, bc.candidate_photo, bc.candidate_sign");
              if(count($candidate_data) > 0)
              {
                $id_proof_file = $candidate_data[0]['id_proof_file'];
                $qualification_certificate_file = $candidate_data[0]['qualification_certificate_file'];
                $candidate_photo = $candidate_data[0]['candidate_photo'];
                $candidate_sign = $candidate_data[0]['candidate_sign'];
                
                $id_proof_file_new = 'id_proof_'.$candidate_data[0]['regnumber'].'.'.pathinfo($id_proof_file, PATHINFO_EXTENSION);
                $qualification_certificate_file_new = 'quali_cert_'.$candidate_data[0]['regnumber'].'.'.pathinfo($qualification_certificate_file, PATHINFO_EXTENSION);
                $candidate_photo_new = 'photo_'.$candidate_data[0]['regnumber'].'.'.pathinfo($candidate_photo, PATHINFO_EXTENSION);
                $candidate_sign_new = 'sign_'.$candidate_data[0]['regnumber'].'.'.pathinfo($candidate_sign, PATHINFO_EXTENSION);
                
                $up_img_data = array();
                if($id_proof_file != $id_proof_file_new)
                {
                  if(rename($id_proof_file_path."/".$id_proof_file, $id_proof_file_path."/".$id_proof_file_new))
                  {
                    $up_img_data['id_proof_file'] = $id_proof_file_new;
                  }
                }
                
                if($qualification_certificate_file != $qualification_certificate_file_new)
                {
                  if(rename($qualification_certificate_file_path."/".$qualification_certificate_file, $qualification_certificate_file_path."/".$qualification_certificate_file_new))
                  {
                    $up_img_data['qualification_certificate_file'] = $qualification_certificate_file_new;
                  }
                }
                
                if($candidate_photo != $candidate_photo_new)
                {
                  if(rename($candidate_photo_path."/".$candidate_photo, $candidate_photo_path."/".$candidate_photo_new))
                  {
                    $up_img_data['candidate_photo'] = $candidate_photo_new;
                  }
                }
                
                if($candidate_sign != $candidate_sign_new)
                {
                  if(rename($candidate_sign_path."/".$candidate_sign, $candidate_sign_path."/".$candidate_sign_new))
                  {
                    $up_img_data['candidate_sign'] = $candidate_sign_new;
                  }
                }
                
                if(count($up_img_data) > 0)
                {
                  $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_img_data, array('candidate_id' => $candidate_data[0]['candidate_id']));
                }
              }
            }
            // END : Rename the images and update into database table name
            
            //START : GENERATE TRAINING ID AND UPDATE IN DATABASE TABLE FOR BOTH ADD/UPDATE FORM. 
            //WE ARE USING QUALIFICATION TO GENERATE THE TRAINING ID. THEREFORE WE USED THIS CODE IN BOTH ADD & UPDATE MODE
            if($candidate_id > 0 && $mode == 'Add')
            {
              //$where_cond = '';
              //if($mode == 'Update') { $where_cond = 'AND candidate_id <= "'.$candidate_id.'"'; }
              $this->db->order_by('candidate_id','ASC');
              $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$agency_id.'" AND centre_id = "'.$centre_id.'" AND batch_id = "'.$batch_id.'" AND candidate_id <= "'.$candidate_id.'"');
              $batch_candidate_count = $batch_candidate_qry->num_rows();
              
              /* $type = '';
                if($this->input->post('qualification') == '1') { $type = 'UG'; }
                else if($this->input->post('qualification') == '2') { $type = 'G'; }
                else if($this->input->post('qualification') == '3') { $type = 'PG'; }
                
              $training_id = 'T'.sprintf('%02d',$batch_candidate_count).'-'.$type.'-'.$batch_data[0]['batch_code']; */
              $training_id = 'T'.sprintf('%02d',$batch_candidate_count).'-'.$batch_data[0]['batch_code'];
              $up_data = array();
              $up_data['training_id'] = $training_id;
              $this->master_model->updateRecord('iibfbcbf_batch_candidates', $up_data, array('candidate_id'=>$candidate_id));
              //END : GENERATE TRAINING ID AND UPDATE IN DATABASE TABLE FOR BOTH ADD/UPDATE FORM 
            }
            redirect(site_url('iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id));
          }
        }
      }
      
      $data['page_title'] = 'IIBF - BCBF Centre '.$mode.' Candidate'; 
      
      $data['state_master_data'] = $this->master_model->getRecords('state_master', array('state_delete' => '0', 'id !=' => '11'), 'id, state_code, state_no, exempt, state_name', array('state_name'=>'ASC'));
      $this->load->view('iibfbcbf/agency/add_candidates_agency', $data);
    }/******** END : ADD / UPDATE CANDIDATES DATA ********/  
    
    function get_city_ajax()
    {
			if(isset($_POST) && count($_POST) > 0)
      {
        $onchange_fun = "validate_file('city')";
				$html = '	<select class="form-control chosen-select ignore_required" name="city" id="city" required onchange="'.$onchange_fun.'">';
        $state_id = $this->security->xss_clean($this->input->post('state_id'));
        
        $city_data = $this->master_model->getRecords('city_master', array('state_code' => $state_id, 'city_delete' => '0'), 'id, city_name', array('city_name'=>'ASC'));
				if(count($city_data) > 0)
        {
          $html .= '	<option value="">Select City</option>';
					foreach($city_data as $city)
          {
						$html .= '	<option value="'.$city['id'].'">'.$city['city_name'].'</option>';
          }
        }
        else
        {
          $html .= '	<option value="">Select City</option>';
        }
        $html .= '</select>';
				$html .="<script>$('.chosen-select').chosen({width: '100%'});function validate_file(input_id) { $('#'+input_id).valid(); }</script>";
        
        $result['flag'] = "success";
        $result['response'] = $html;
      }
      else
      {
        $result['flag'] = "error";
      }
      echo json_encode($result);
    }
    
    function change_hold_release_status_agency()
    {
      if(isset($_POST['cand_id']) && $_POST['cand_id'] != "" && isset($_POST['status']) && $_POST['status'] != "")
      {
        echo $this->Iibf_bcbf_model->change_candidate_hold_release_status_common($_POST, 'agency');
      }
      else
      {
        echo "error";
      }
    }
    
    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
    public function validation_check_mobile_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['mobile_no'] != "")
      {
        if($type == '1') 
        {
          $mobile_no = $this->security->xss_clean($this->input->post('mobile_no'));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));
          
          if($enc_candidate_id != "" && $enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); }
          else { $candidate_id = $enc_candidate_id; }
        }
        else
        {
          $mobile_no = $str;
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }
        
        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'mobile', $mobile_no);
        if(count($candidate_data) == 0)
        {
          $return_val_ajax = 'true';
        }
      }
      
      if($type == '1') { echo $return_val_ajax; }
      else
      {
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['mobile_no'] != "")
        {
          $this->form_validation->set_message('validation_check_mobile_exist','The mobile number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE EMAIL ID EXIST OR NOT ********/
    public function validation_check_email_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['email_id'] != "")
      {
        if($type == '1') 
        {
          $email_id = strtolower($this->security->xss_clean($this->input->post('email_id')));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));
          
          if($enc_candidate_id != "" && $enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); }
          else { $candidate_id = $enc_candidate_id; }
        }
        else
        {
          $email_id = strtolower($str);
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }
        
        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'email', $email_id);
        if(count($candidate_data) == 0)
        {
          $return_val_ajax = 'true';
        }
      }
      
      if($type == '1') { echo $return_val_ajax; }
      else
      {
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['email_id'] != "")
        {
          $this->form_validation->set_message('validation_check_email_exist','The email id is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CANDIDATE MOBILE EXIST OR NOT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE ID PROOF NUMBER EXIST OR NOT ********/
    public function validation_check_id_proof_number_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['id_proof_number'] != "")
      {
        if($type == '1') 
        {
          $id_proof_number = strtolower($this->security->xss_clean($this->input->post('id_proof_number')));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));
          
          if($enc_candidate_id != "" && $enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); }
          else { $candidate_id = $enc_candidate_id; }
        }
        else
        {
          $id_proof_number = strtolower($str);
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }
        
        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'id_proof', $id_proof_number);
        if(count($candidate_data) == 0)
        {
          $return_val_ajax = 'true';
        }
      }
      
      if($type == '1') { echo $return_val_ajax; }
      else
      {
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['id_proof_number'] != "")
        {
          $this->form_validation->set_message('validation_check_id_proof_number_exist','The id proof number is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CANDIDATE ID PROOF NUMBER EXIST OR NOT ********/
    
    /******** START : VALIDATION TO CHECK THE TOTAL CANDIDATES CAN NOT BE MORE THAN DEFINE QUALIFICATION CANDIDATE COUNT ********/
    /******** WHILE CREATING BATCH, IF CENTER SELECTED 5 CANDIDATES AS GRADUATE, THEN THEY CAN NOT ADD MORE THAN 5 GRADUATE CANDIDATES AS GRADUATE QUALIFICTION CANDIDATES ********/
    public function validation_check_qualification_candidates($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $flag = "error";
      $response = '';
      $enc_batch_id = $enc_candidate_id = 0;
      
			if(isset($_POST) && $_POST['qualification'] != "")
      {
        if($type == '1') 
        {
          $selected_qualification = $this->security->xss_clean($this->input->post('qualification'));
          $enc_batch_id = $this->security->xss_clean($this->input->post('enc_batch_id'));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));
        }
        else
        {
          $selected_qualification = $str;
          
          $ids_str = $type;
          $ids_arr = explode("###",$ids_str);
          
          $enc_batch_id = $ids_arr[0];
          $enc_candidate_id = $ids_arr[1];
        }
        
        if($enc_batch_id != "" && $enc_batch_id != '0') { $batch_id = url_decode($enc_batch_id); } else { $batch_id = $enc_batch_id; }
        if($enc_candidate_id != "" && $enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); } else { $candidate_id = $enc_candidate_id; }
        
        //get batch data
        $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch btch', array('btch.is_deleted' => '0', 'btch.batch_id' => $batch_id), 'btch.batch_id, btch.agency_id, btch.centre_id, btch.under_graduate_candidates, btch.graduate_candidates, btch.post_graduate_candidates');
        
        //calculate total candidates data in this batch as per selected qualification
        $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted' => '0', 'cand.qualification' => $selected_qualification, 'cand.candidate_id !=' => $candidate_id, 'cand.batch_id' => $batch_id), 'cand.candidate_id, cand.agency_id, cand.centre_id, cand.batch_id, cand.qualification');
        
        $chk_batch_candidate_count = 0;
        if($selected_qualification == '1') { $chk_batch_candidate_count = $batch_data[0]['under_graduate_candidates']; }
        else if($selected_qualification == '2') { $chk_batch_candidate_count = $batch_data[0]['graduate_candidates']; }
        else if($selected_qualification == '3') { $chk_batch_candidate_count = $batch_data[0]['post_graduate_candidates']; }
        
        if(count($candidate_data) < $chk_batch_candidate_count) { $flag = 'success'; }
        else
        {
          $disp_qual = '';
          if($selected_qualification == '1') { $disp_qual = 'Under Graduate'; }
          else if($selected_qualification == '2') { $disp_qual = 'Graduate'; }
          else if($selected_qualification == '3') { $disp_qual = 'Post Graduate'; }
          
          if($chk_batch_candidate_count == '0')
          {
            $response = 'You can not add '.$disp_qual.' candidates for this batch';
          }
          else
          {
            $response = 'You can not add more than '.$chk_batch_candidate_count.' '.$disp_qual.' candidates for this batch';
          }
        }
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
        else if($_POST['qualification'] != "")
        {
          $this->form_validation->set_message('validation_check_qualification_candidates',$response);
          return false;
        }
      }
    }/******** END : VALIDATION TO CHECK THE TOTAL CANDIDATES CAN NOT BE MORE THAN DEFINE QUALIFICATION CANDIDATE COUNT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK CANDIDATE AADHAR NUMBER EXIST OR NOT ********/
    public function validation_check_aadhar_no_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['aadhar_no'] != "")
      {
        if($type == '1') 
        {
          $aadhar_no = $this->security->xss_clean($this->input->post('aadhar_no'));
          $enc_candidate_id = $this->security->xss_clean($this->input->post('enc_candidate_id'));
          
          if($enc_candidate_id != "" && $enc_candidate_id != '0') { $candidate_id = url_decode($enc_candidate_id); }
          else { $candidate_id = $enc_candidate_id; }
        }
        else
        {
          $aadhar_no = $str;
          $enc_candidate_id = $type;
          $candidate_id = url_decode($enc_candidate_id);
        }
        
        $candidate_data = $this->Iibf_bcbf_model->validation_check_candidate_eligibility_to_add_in_new_batch($candidate_id, 'aadhar', $aadhar_no);
        if(count($candidate_data) == 0)
        {
          $return_val_ajax = 'true';
        }
      }
      
      if($type == '1') { echo $return_val_ajax; }
      else
      {
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['aadhar_no'] != "")
        {
          $this->form_validation->set_message('validation_check_aadhar_no_exist','The aadhar no is already exist');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK CANDIDATE AADHAR NUMBER EXIST OR NOT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    public function validation_check_valid_pincode($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['pincode'] != "")
      {
        if($type == '1') 
        {
          $pincode = $this->security->xss_clean($this->input->post('pincode'));
          $selected_state_code = $this->security->xss_clean($this->input->post('selected_state_code'));
        }
        else
        {
          $pincode = $str;
          $selected_state_code = $type;
        }
        
        $this->db->where(" '".$pincode."' BETWEEN start_pin AND end_pin ");
        $result_data = $this->master_model->getRecords('state_master', array('state_code' => $selected_state_code), 'id, state_code, start_pin, end_pin');
        
        if(count($result_data) > 0)
        {
          $return_val_ajax = 'true';
        }
      }
      
      if($type == '1') { echo $return_val_ajax; }
      else
      {
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['pincode'] != "")
        {
          $pin_length = strlen($_POST['pincode']);
          
          $err_msg = 'Please enter valid pincode as per selected city';
          if($pin_length != 6) { $err_msg = 'Please enter only 6 numbers in pincode'; }
          
          $this->form_validation->set_message('validation_check_valid_pincode',$err_msg);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PINCODE IS VALID OR NOT ********/
    
    /******** START : VALIDATION FUNCTION TO CHECK VALID DATE OF BIRTH ********/
    function fun_validate_dob($str, $chk_dates_str='') // Custom callback function for check valid DATE OF BIRTH
    {
      if($str != '')
      {
        $current_val = $str;
        
        //$explode_input_arr = explode("####",$chk_dates_str);
        //$chk_dob_start_date = date('Y-m-d', strtotime($explode_input_arr[0]));
        //$chk_dob_end_date = date('Y-m-d', strtotime($explode_input_arr[1]));
        $chk_dob_end_date = $chk_dates_str;
        
        //if($chk_dob_start_date != "" && $chk_dob_end_date != "")
        if($chk_dob_end_date != "")
        {
          //if($current_val >= $chk_dob_start_date && $current_val <= $chk_dob_end_date) { return true; }
          if($current_val <= $chk_dob_end_date) { return true; }
          else
          {
            //$this->form_validation->set_message('fun_validate_dob', "Select date of birth between ".$chk_dob_start_date." to ".$chk_dob_end_date." date.");
            $this->form_validation->set_message('fun_validate_dob', "Select date of birth before ".date('Y-m-d', strtotime("+1days",strtotime($chk_dob_end_date)))." date.");
            return false;
          }
        }
        else { return true; }
      }
      else { return true; }     
    }/******** END : VALIDATION FUNCTION TO CHECK VALID DATE OF BIRTH ********/
    
    //START : VALIDATE Id Proof Number
    function fun_validate_id_proof_number($str, $selected_id_proof_type='')
    {
      //1=>Aadhar Card, 2=>Driving Licence, 3=>Employee's Card, 4=>Pan Card, 5=>Passport
      $selectedIdProofType = $selected_id_proof_type;
      
      if($str == "") 
      {
        $err_msg1 = 'Please enter the id proof number';
        
        if($selected_id_proof_type != "")
          {
          if($selectedIdProofType == '1') { $err_msg1 = 'Please enter the aadhar card number'; }
          else if($selectedIdProofType == '2') { $err_msg1 = 'Please enter the driving licence number'; }
          else if($selectedIdProofType == '3') { $err_msg1 = 'Please enter the employee id number'; }
          else if($selectedIdProofType == '4') { $err_msg1 = 'Please enter the pan card number'; }
          else if($selectedIdProofType == '5') { $err_msg1 = 'Please enter the passport number'; }
        }
        
        $this->form_validation->set_message('fun_validate_id_proof_number', $err_msg1);
        return false;
      }
      else
      {
        if($selectedIdProofType != "")
          {
          if($selectedIdProofType == '1')//Aadhar Card
            {
            if (preg_match('/^([0-9]{12})$/', $str)) { return true; } 
            else
            {
              $this->form_validation->set_message('fun_validate_id_proof_number', 'Please enter valid aadhar card number');
              return false;
            }
          }
          else if($selectedIdProofType == '2') //Driving Licence
            {
            if (preg_match('/^([A-Z]{2}[0-9]{13})$/', $str)) { return true; } 
            else
            {
              $this->form_validation->set_message('fun_validate_id_proof_number', 'Please enter valid driving licence number');
              return false;
            }
          }
          else if($selectedIdProofType == '3') //Employee's Card
          {
            return true;
          }
          else if($selectedIdProofType == '4') //Pan Card
            {
            if (preg_match('/^([A-Z]{5}[0-9]{4}[A-Z])$/', $str)) { return true; } 
            else
            {
              $this->form_validation->set_message('fun_validate_id_proof_number', 'Please enter valid pan card number');
              return false;
            }
          }
          else if($selectedIdProofType == '5') //Passport
            {
            if (preg_match('/^([A-Z]{1}[0-9]{7})$/', $str)) { return true; } 
            else
            {
              $this->form_validation->set_message('fun_validate_id_proof_number', 'Please enter valid passport number');
              return false;
            }
          }
          else
          {
            return true;
          }
        }
        else { return true; }
        }
    }//END : VALIDATE Id Proof Number
    
    //START : VALIDATE validate_qualification_certificate_type
    function fun_validate_qualification_certificate_type($str='', $selected_qualification='')
    {
      $selectedQualification = $selected_qualification;
      
      if($str == '') { return true; }
      else
      {
        if($selectedQualification != "")
        {
          $current_qualification_certificate_type = $str;
          if($selectedQualification == '1')//Under Graduate
            {
            if($current_qualification_certificate_type == 1 || $current_qualification_certificate_type == 2) { return true; }
            else
            {
              $this->form_validation->set_message('fun_validate_qualification_certificate_type', "Invalid qualification certificate type selected");
              return false;
            }
          }
          else if($selectedQualification == '2') //Graduate
            {
            if($current_qualification_certificate_type == 3) { return true; }
            else
            {
              $this->form_validation->set_message('fun_validate_qualification_certificate_type', "Invalid qualification certificate type selected");
              return false;
            }
          }
          else if($selectedQualification == '3') //post Graduate
            {
            if($current_qualification_certificate_type == 4) { return true; }
            else
            {
              $this->form_validation->set_message('fun_validate_qualification_certificate_type', "Invalid qualification certificate type selected");
              return false;
            }
          }
          else { return true; }
          }
        else { return true; }
        }
    }//END : VALIDATE validate_qualification_certificate_type
    
    //START : VALIDATE GENDER
    function fun_validate_gender($str='', $selected_salution='')
    {
      $selectedSalutation = $selected_salution;
      
      if($str == '') { return true; }
      else
      {
        if($selectedSalutation != "")
        {
          $current_gender = $str;
          if($selectedSalutation == 'Mr.')//Mr.
            {
            if($current_gender == 1) { return true; }
            else
            {
              $this->form_validation->set_message('fun_validate_gender', "Invalid gender selected");
              return false;
            }
          }
          else if($current_gender == 'Mrs.' || $current_gender == 'Ms.') //Mrs. or Ms.
            {
            if($current_gender == 2) { return true; }
            else
            {
              $this->form_validation->set_message('fun_validate_gender', "Invalid gender selected");
              return false;
            }
          }
          else { return true; }
        }
        else { return true; }
      }
    }//END : VALIDATE GENDER
    
    /******** START : CANDIDATE DETAILS PAGE ********/
    public function candidates_details_agency($enc_batch_id='0', $enc_candidate_id='0')
    {
      if($enc_batch_id == '0') //FOR ALL CANDIDATE LISTING
      {
        $data['act_id'] = "All Candidates";
        $data['sub_act_id'] = "All Candidates";
      }
      else  //FOR SPECIFIC BATCH CANDIDATE LISTING
      {
        $data['act_id'] = "Training Batches";
        $data['sub_act_id'] = "Training Batches";
      }
      
      $data['enc_batch_id'] = $enc_batch_id;
      $data['enc_candidate_id'] = $enc_candidate_id;
      $data['page_title'] = 'IIBF - BCBF '.ucfirst($this->login_user_type).' Candidate Details';
      
      $agency_id = '0';
      $centre_id = '0';
      if($this->login_user_type == 'agency') { $agency_id = $this->login_agency_or_centre_id; }
      else if($this->login_user_type == 'centre') //get centre data
      {
        $centre_id = $this->login_agency_or_centre_id;
        
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$centre_id), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, cm.centre_username');
        
        if(count($centre_data) > 0) { $agency_id = $centre_data[0]['agency_id']; }
      }
      
      //START : GET THE CANDIDATES DATA
      if($enc_candidate_id == '0') { redirect(site_url('iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id)); }
      else
      {
        $candidate_id = url_decode($enc_candidate_id);
        
        $this->db->join('state_master sm', 'sm.state_code = bc.state', 'LEFT');
        $this->db->join('city_master cm1', 'cm1.id = bc.city', 'LEFT');
        
        if($enc_batch_id != '0') { $this->db->where('bc.batch_id', url_decode($enc_batch_id)); }
        if($this->login_user_type == 'centre') { $this->db->where('bc.centre_id', $centre_id); }
        
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = bc.agency_id', 'LEFT');
        $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = bc.centre_id', 'INNER');
        $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_batch_candidates bc', array('bc.candidate_id' => $candidate_id, 'bc.is_deleted' => '0'), "bc.*, IF(bc.gender=1,'Male','Female') AS DispGender, IF(bc.qualification=1, 'Under Graduate', IF(bc.qualification=2,'Graduate','Post Graduate')) AS DispQualification, sm.state_name, cm1.city_name, IF(bc.id_proof_type=1, 'Aadhar Card', IF(bc.id_proof_type=2,'Driving Licence',IF(bc.id_proof_type=3,'Employee ID', IF(bc.id_proof_type=4,'Pan Card','Passport')))) AS DispIdProofType, IF(bc.qualification_certificate_type=1, '10th Pass', IF(bc.qualification_certificate_type=2,'12th Pass',IF(bc.qualification_certificate_type=3,'Graduation',IF(bc.qualification_certificate_type=4,'Post Graduation','')))) AS DispQualificationCertificateType, IF(bc.hold_release_status=1,'Auto Hold', IF(bc.hold_release_status=2,'Manual Hold','Release')) AS Disphold_release_status, am.agency_name, am.agency_code, am.allow_exam_types, cm.centre_name, cm.centre_username, cm2.city_name AS centre_city_name");
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/batch_candidates_agency/index/'.$enc_batch_id)); }
        
        //START : CHECK IF BATCH EXIST OR NOT. ALSO CHECK BATCH STATUS AS GO AHEAD
        $batch_id = $form_data[0]['batch_id'];
        
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = acb.agency_id', 'LEFT');
        $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = acb.centre_id', 'LEFT');
        $data['batch_data'] = $batch_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.centre_id' => $form_data[0]['centre_id'], 'acb.batch_id' => $batch_id, 'acb.is_deleted' => '0', 'am.is_active' => '1', 'am.is_deleted' => '0', 'cm.is_deleted' => '0'), "acb.*");
        
        if(count($batch_data) == 0) { redirect(site_url('iibfbcbf/agency/training_batches_agency')); } 
        //END : CHECK IF BATCH EXIST OR NOT. ALSO CHECK BATCH STATUS AS GO AHEAD
      }//END : GET THE CANDIDATES DATA
      
      $data['id_proof_file_path'] = $this->id_proof_file_path;
      $data['qualification_certificate_file_path'] = $this->qualification_certificate_file_path;
      $data['candidate_photo_path'] = $this->candidate_photo_path;
      $data['candidate_sign_path'] = $this->candidate_sign_path;
      
      $this->load->view('iibfbcbf/agency/candidates_details_agency', $data);
    }/******** END : CANDIDATE DETAILS PAGE ********/   
    
    /***** START : VALIDATION TO CHECK IF SELECTED 'ID Proof Type' IS 'AADHAR CARD' THEN 'AADHAR NUMBER' MUST BE SAME AS 'ID PROOF NUMBER' **/
    function CheckAadharNumberWithIdProof($str,$type) // Custom callback function for restrict input
    {
      if($str != '')
      {
        $id_proof_type_val = trim($this->security->xss_clean($this->input->post('id_proof_type')));
        $id_proof_number_val = trim($this->security->xss_clean($this->input->post('id_proof_number')));
        $aadhar_no_val = $str;
        
        if($id_proof_type_val == 1 &&  $id_proof_number_val != "" && $id_proof_number_val != $aadhar_no_val)
        {
          $this->form_validation->set_message('CheckAadharNumberWithIdProof', 'Aadhar Number value must be same as Id Proof Number.');
          return false;
        }
        else { return true; }
        }
      else { return true; }
    }/***** END : VALIDATION TO CHECK IF SELECTED 'ID Proof Type' IS 'AADHAR CARD' THEN 'AADHAR NUMBER' MUST BE SAME AS 'ID PROOF NUMBER' **/
    
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
    
    function get_candidate_details_ajax()
    {
      $result['flag'] = "error";
      $result['response'] = "Error occurred. Please try again.";
      
			if(isset($_POST) && count($_POST) > 0)
      {
        $training_id_regnumber = $this->security->xss_clean($this->input->post('training_id_regnumber'));
        $enc_batch_id = $this->security->xss_clean($this->input->post('enc_batch_id'));
        $batch_id = url_decode($enc_batch_id);
        
        $this->db->join('iibfbcbf_agency_centre_batch btch', 'btch.batch_id = cand.batch_id','INNER');
        $this->db->where(" (cand.training_id = '".$training_id_regnumber."' OR cand.regnumber = '".$training_id_regnumber."') ");
        $candidate_data = $this->master_model->getRecords('iibfbcbf_batch_candidates cand', array('cand.is_deleted' => '0'), "cand.candidate_id, cand.agency_id, cand.centre_id, cand.batch_id, cand.regnumber, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.dob, cand.gender, cand.mobile_no, cand.alt_mobile_no, cand.email_id, cand.alt_email_id, cand.qualification, cand.address1, cand.address2, cand.address3, cand.address4, cand.state, cand.city, cand.district, cand.pincode, cand.bank_associated, cand.corporate_bc_associated, cand.id_proof_type, cand.id_proof_number, cand.id_proof_file, cand.qualification_certificate_type, cand.qualification_certificate_file, cand.candidate_photo, cand.candidate_sign, cand.aadhar_no, cand.re_attempt, cand.registration_type, cand.hold_release_status, cand.created_on, btch.batch_end_date, btch.batch_status", array('cand.candidate_id'=>'DESC'));
        //_pq(1);
        
        if(count($candidate_data) > 0)
        {
          //3 attempt is over, then valid OR
          //candidate 270 days is over & batch 270 days is over, then valid OR
          //batch is cancelled, then valid
          $date_270_Days = date('Y-m-d', strtotime("-270days"));
          
          /* echo $candidate_data[0]['hold_release_status']." != '3' || ".$candidate_data[0]['re_attempt']." == '3' || ".(date("Y-m-d", strtotime($candidate_data[0]['created_on']))." < ".$date_270_Days." && ".date("Y-m-d", strtotime($candidate_data[0]['batch_end_date']))." < ".$date_270_Days)." || ".in_array($candidate_data[0]['batch_status'], array(5,7)); */
          
          if($candidate_data[0]['hold_release_status'] != '3' || $candidate_data[0]['re_attempt'] == '3' || (date("Y-m-d", strtotime($candidate_data[0]['created_on'])) < $date_270_Days && date("Y-m-d", strtotime($candidate_data[0]['batch_end_date'])) < $date_270_Days) || in_array($candidate_data[0]['batch_status'], array(5,7)))
          {
            $result['flag'] = "success";
            
            $tmp_arr = array();
            $tmp_arr['candidate_id'] = $candidate_data[0]['candidate_id'];
            //$tmp_arr['agency_id'] = $candidate_data[0]['agency_id'];
            //$tmp_arr['centre_id'] = $candidate_data[0]['centre_id'];
            //$tmp_arr['batch_id'] = $candidate_data[0]['batch_id'];
            $tmp_arr['regnumber'] = $candidate_data[0]['regnumber'];
            $tmp_arr['training_id'] = $candidate_data[0]['training_id'];
            $tmp_arr['salutation'] = $candidate_data[0]['salutation'];
            $tmp_arr['first_name'] = $candidate_data[0]['first_name'];
            $tmp_arr['middle_name'] = $candidate_data[0]['middle_name'];
            $tmp_arr['last_name'] = $candidate_data[0]['last_name'];
            $tmp_arr['dob'] = $candidate_data[0]['dob'];
            $tmp_arr['gender'] = $candidate_data[0]['gender'];
            $tmp_arr['mobile_no'] = $candidate_data[0]['mobile_no'];
            $tmp_arr['alt_mobile_no'] = $candidate_data[0]['alt_mobile_no'];
            $tmp_arr['email_id'] = $candidate_data[0]['email_id'];
            $tmp_arr['alt_email_id'] = $candidate_data[0]['alt_email_id'];
            $tmp_arr['qualification'] = $candidate_data[0]['qualification'];
            $tmp_arr['address1'] = $candidate_data[0]['address1'];
            $tmp_arr['address2'] = $candidate_data[0]['address2'];
            $tmp_arr['address3'] = $candidate_data[0]['address3'];
            $tmp_arr['address4'] = $candidate_data[0]['address4'];
            $tmp_arr['state'] = $candidate_data[0]['state'];
            $tmp_arr['city'] = $candidate_data[0]['city'];
            $tmp_arr['district'] = $candidate_data[0]['district'];
            $tmp_arr['pincode'] = $candidate_data[0]['pincode'];
            $tmp_arr['bank_associated'] = $candidate_data[0]['bank_associated'];
            $tmp_arr['corporate_bc_associated'] = $candidate_data[0]['corporate_bc_associated'];
            $tmp_arr['id_proof_type'] = $candidate_data[0]['id_proof_type'];
            $tmp_arr['id_proof_number'] = $candidate_data[0]['id_proof_number'];
            $tmp_arr['qualification_certificate_type'] = $candidate_data[0]['qualification_certificate_type'];
            $tmp_arr['aadhar_no'] = $candidate_data[0]['aadhar_no'];
            $tmp_arr['registration_type'] = $candidate_data[0]['registration_type'];
            //$tmp_arr['re_attempt'] = $candidate_data[0]['re_attempt']; 
            
            $id_proof_file = $this->Iibf_bcbf_model->check_file_exist_common("./".$this->id_proof_file_path."/", $candidate_data[0]['id_proof_file']);
            if($id_proof_file != "") { $id_proof_file = base_url($this->id_proof_file_path).'/'.$id_proof_file; }
            $tmp_arr['id_proof_file'] = $id_proof_file;
            
            $qualification_certificate_file = $this->Iibf_bcbf_model->check_file_exist_common("./".$this->qualification_certificate_file_path."/", $candidate_data[0]['qualification_certificate_file']);
            if($qualification_certificate_file != "") { $qualification_certificate_file = base_url($this->qualification_certificate_file_path).'/'.$qualification_certificate_file; }
            $tmp_arr['qualification_certificate_file'] = $qualification_certificate_file;
            
            $candidate_photo = $this->Iibf_bcbf_model->check_file_exist_common("./".$this->candidate_photo_path."/", $candidate_data[0]['candidate_photo']);
            if($candidate_photo != "") { $candidate_photo = base_url($this->candidate_photo_path).'/'.$candidate_photo; }
            $tmp_arr['candidate_photo'] = $candidate_photo;
            
            $candidate_sign = $this->Iibf_bcbf_model->check_file_exist_common("./".$this->candidate_sign_path."/", $candidate_data[0]['candidate_sign']);
            if($candidate_sign != "") { $candidate_sign = base_url($this->candidate_sign_path).'/'.$candidate_sign; }
            $tmp_arr['candidate_sign'] = $candidate_sign;
            
            $tmp_arr['parent_table'] = 'iibfbcbf_batch_candidates';
            
            $result['response'] = $tmp_arr;
          }
          else
          {
            if($candidate_data[0]['batch_id'] == $batch_id)
            {
              $result['response'] = 'The Candidates is already added in same batch';
            }
            else if($candidate_data[0]['re_attempt'] < '3')
            {
              $result['response'] = 'Candidates '.(3-$candidate_data[0]['re_attempt']).' attempt is pending';
            }
          }
        }
        else
        {
          $this->db->join('city_master cm', 'cm.city_name = mr.city', 'LEFT');
          $this->db->where_in('mr.registrationtype', array('NM','O'));
          $candidate_data = $this->master_model->getRecords('member_registration mr', array('mr.regnumber'=>$training_id_regnumber, 'mr.isactive' => '1', 'mr.isdeleted'=>'0'), "mr.regid, mr.regnumber, mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.dateofbirth, mr.gender, mr.mobile, mr.email, mr.qualification, mr.address1, mr.address2, mr.address3, mr.address4, mr.state, cm.id AS city_id, mr.district, mr.pincode, mr.idproof, mr.idNo, mr.aadhar_card, mr.registrationtype ", array('mr.regid'=>'DESC'));
          
          if(count($candidate_data) > 0)
          {
            $result['flag'] = "success";
            
            $tmp_arr = array();
            $tmp_arr['candidate_id'] = $candidate_data[0]['regid'];
            $tmp_arr['regnumber'] = $candidate_data[0]['regnumber'];
            $tmp_arr['training_id'] = '';
            
            $salutation = strtoupper($candidate_data[0]['namesub']);
            if(strpos($salutation, '.') === false) { $salutation .= '.'; }
            if($salutation == 'MS.') { $salutation = 'Ms.'; }
            $tmp_arr['salutation'] = $salutation;
            $tmp_arr['first_name'] = $candidate_data[0]['firstname'];
            $tmp_arr['middle_name'] = $candidate_data[0]['middlename'];
            $tmp_arr['last_name'] = $candidate_data[0]['lastname'];
            $tmp_arr['dob'] = $candidate_data[0]['dateofbirth'];
            
            $gender = '1'; if($candidate_data[0]['gender'] == 'female') { $gender = '2'; }
            $tmp_arr['gender'] = $gender;;
            $tmp_arr['mobile_no'] = $candidate_data[0]['mobile'];
            $tmp_arr['alt_mobile_no'] = '';
            $tmp_arr['email_id'] = $candidate_data[0]['email'];
            $tmp_arr['alt_email_id'] = '';
            
            $qualification = '';
            if($candidate_data[0]['qualification'] == 'U') { $qualification = '1'; }
            else if($candidate_data[0]['qualification'] == 'G') { $qualification = '2'; }
            else if($candidate_data[0]['qualification'] == 'P') { $qualification = '3'; }
            $tmp_arr['qualification'] = $qualification;;
            
            $tmp_arr['address1'] = $candidate_data[0]['address1'];
            $tmp_arr['address2'] = $candidate_data[0]['address2'];
            $tmp_arr['address3'] = $candidate_data[0]['address3'];
            $tmp_arr['address4'] = $candidate_data[0]['address4'];
            $tmp_arr['state'] = $candidate_data[0]['state'];
            $tmp_arr['city'] = $candidate_data[0]['city_id'];
            $tmp_arr['district'] = $candidate_data[0]['district'];
            $tmp_arr['pincode'] = $candidate_data[0]['pincode'];
            $tmp_arr['bank_associated'] = '';
            $tmp_arr['corporate_bc_associated'] = '';
            
            $id_proof_type = '';
            if($candidate_data[0]['idproof'] == '1') {  $id_proof_type = '1'; }
            else if($candidate_data[0]['idproof'] == '2') {  $id_proof_type = '2'; }
            else if($candidate_data[0]['idproof'] == '4') {  $id_proof_type = '3'; }
            else if($candidate_data[0]['idproof'] == '5') {  $id_proof_type = '4'; }
            else if($candidate_data[0]['idproof'] == '6') {  $id_proof_type = '5'; }
            $tmp_arr['id_proof_type'] = $id_proof_type;
            
            $id_proof_number = '';
            if($candidate_data[0]['idproof'] != '3') {  $id_proof_number = $candidate_data[0]['idNo']; } 
            $tmp_arr['id_proof_number'] = $id_proof_number;
            
            $tmp_arr['qualification_certificate_type'] = $qualification;
            $tmp_arr['aadhar_no'] = $candidate_data[0]['aadhar_card'];
            $tmp_arr['registration_type'] = $candidate_data[0]['registrationtype'];
            
            $id_proof_file = get_img_name($candidate_data[0]['regnumber'],'pr');
            if($id_proof_file != "") 
            {
              $id_proof_file = str_replace("./","",$id_proof_file);
              $id_proof_file = $this->Iibf_bcbf_model->check_file_exist_common("./", $id_proof_file);
              if($id_proof_file != "") { $id_proof_file = base_url().$id_proof_file; }
            }
            $tmp_arr['id_proof_file'] =  $id_proof_file;
            
            $tmp_arr['qualification_certificate_file'] = '';
            
            $candidate_photo = get_img_name($candidate_data[0]['regnumber'],'p');
            if($candidate_photo != "") 
            {
              $candidate_photo = str_replace("./","",$candidate_photo);
              $candidate_photo = $this->Iibf_bcbf_model->check_file_exist_common("./", $candidate_photo);
              if($candidate_photo != "") { $candidate_photo = base_url().$candidate_photo; }
            }
            $tmp_arr['candidate_photo'] =  $candidate_photo;
            
            $candidate_sign = get_img_name($candidate_data[0]['regnumber'],'s');
            if($candidate_sign != "") 
            {
              $candidate_sign = str_replace("./","",$candidate_sign);
              $candidate_sign = $this->Iibf_bcbf_model->check_file_exist_common("./", $candidate_sign);
              if($candidate_sign != "") { $candidate_sign = base_url().$candidate_sign; }
            }
            $tmp_arr['candidate_sign'] =  $candidate_sign;
            
            $tmp_arr['parent_table'] = 'member_registration';
            $result['response'] = $tmp_arr;
          }
          else
          {
            $result['response'] = "Please enter the valid Training ID or Registration Number";
          }
        }
      }
      
      echo json_encode($result);
    }
    
  } ?>  