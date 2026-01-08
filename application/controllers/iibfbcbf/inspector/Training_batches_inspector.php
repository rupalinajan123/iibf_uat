<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF INSPECTOR Batches LIST
  ** Created BY: Sagar Matale On 09-01-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Training_batches_inspector extends CI_Controller 
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
		}
    
    public function index($search_start_date='')
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";
      $data['search_start_date'] = $search_start_date;
      
      $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, am.is_active');

      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username');

      $data['page_title'] = 'IIBF - BCBF Inspector Batch List';
      $this->load->view('iibfbcbf/inspector/training_batches_inspector', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRAINING BATCHES DATA ********/
    public function get_training_batches_data_ajax()
    {
      $table = 'iibfbcbf_agency_centre_batch acb';
      
      $column_order = array('acb.batch_id', 'CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")") AS agency_name', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", "")) AS DispBatchType', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date) AS BatchDate', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled"))))))) AS DispBatchStatus', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag','acb.agency_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")")', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", ""))', 'CONCAT(acb.batch_start_date, " To ", acb.batch_end_date)', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'acb.created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled")))))))'); //SET COLUMN FOR SEARCH
      $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER      
       
      $WhereForTotal = "WHERE acb.inspector_id = '".$this->login_inspector_id."' AND acb.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE acb.inspector_id = '".$this->login_inspector_id."' AND acb.is_deleted = 0  ";
      
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
      }
      else if($s_from_date != "") { $Where .= " AND (acb.batch_start_date >= '".$s_from_date."' OR acb.batch_end_date >= '".$s_from_date."')"; }
      else if($s_to_date != "") { $Where .= " AND (acb.batch_start_date <= '".$s_to_date."' OR acb.batch_end_date <= '".$s_to_date."')"; } 

      $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
      if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; } //iibfbcbf/iibf_bcbf_helper.php
      
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
        $row[] = $Res['DispBatchType'];
        $row[] = $Res['BatchDate'];
        $row[] = $Res['BatchTime'];
        $row[] = $Res['total_candidates'];
        $row[] = $Res['created_on'];
        
        $row[] = '<span class="badge '.show_batch_status($Res['batch_status']).'" style="min-width:90px;">'.$Res['DispBatchStatus'].'</span>';

        $btn_str = ' <div class="text-left no_wrap"> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/inspector/training_batches_inspector/training_batch_details/'.url_encode($Res['batch_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
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
    
    /******** START : LOAD CENTERS ********/ 
    public function load_centre_data() 
    {      
      //$flag = "error";
      $flag = "success";
      $response = ''; 
      $html = '<option value="">Select Centre</option>';
      if(isset($_POST) && $_POST['s_agency'] != "")
      { 
        $s_agency = $this->security->xss_clean($this->input->post('s_agency'));   
        //$id = url_decode($enc_id);        
        $id = $s_agency;        
        $this->db->where("cm.agency_id",$id);
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username'); 
        //$agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0'), 'centre_id,agency_id,status,centre_district,centre_city,centre_username');
        
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

    /******** START : BATCH DETAILS PAGE ********/
    public function training_batch_details($enc_batch_id=0)
    {   
      $data['act_id'] = "Training Batches";
      $data['sub_act_id'] = "Training Batches";     
      $data['page_title'] = 'IIBF - BCBF Inspector Training Batches Details';

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
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_agency_centre_batch acb', array('acb.batch_id' => $batch_id, 'acb.inspector_id' => $this->login_inspector_id, 'acb.is_deleted' => '0'), "acb.*, IF(acb.batch_type=1, 'Basic', 'Advanced') AS DispBatchType, CONCAT(fm1.salutation, ' ', fm1.faculty_name, ' (', fm1.faculty_number,')') AS FirstFaculty, CONCAT(fm2.salutation, ' ', fm2.faculty_name, ' (', fm2.faculty_number,')') AS SecondFaculty, CONCAT(fm3.salutation, ' ', fm3.faculty_name, ' (', fm3.faculty_number,')') AS ThirdFaculty, CONCAT(fm4.salutation, ' ', fm4.faculty_name, ' (', fm4.faculty_number,')') AS FourthFaculty, IF(acb.batch_online_offline_flag=1, 'Offline', 'Online') AS DispBatchInfrastructure, IF(acb.batch_status = 0, 'In Review', IF(acb.batch_status = 1, 'Final Review', IF(acb.batch_status = 2, 'Batch Error', IF(acb.batch_status = 3, 'Go Ahead', IF(acb.batch_status = 4, 'Hold', IF(acb.batch_status = 5, 'Rejected', IF(acb.batch_status = 6, 'Re-Submitted', 'Cancelled'))))))) AS DispBatchStatus,am1.agency_name,am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_city, cm.centre_username, cm2.city_name, im.inspector_name");
      
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/inspector/training_batches_inspector')); }
      
      $data['user_data'] = $this->master_model->getRecords('iibfbcbf_online_batch_user_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'login_id, password', array('created_on'=>'ASC'));  

      $data['bank_cand_data'] = $this->master_model->getRecords('iibfbcbf_batch_bak_name_cand_src_details', array('batch_id' => $batch_id, 'is_active' => '1', 'is_deleted'=>'0'), 'bank_id, bank_name, cand_src', array('created_on'=>'ASC'));

      $data['training_schedule_file_path'] = $this->training_schedule_file_path;

      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT'); 
      $data['centre_data'] = $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$form_data[0]['centre_id']), 'cm.centre_id, cm.agency_id, cm.centre_address1, cm.centre_state, cm.centre_city, cm.centre_district, cm.centre_pincode, sm.state_name, cm1.city_name');

      $this->load->view('iibfbcbf/inspector/training_batch_details_inspector', $data);
    }/******** END : BATCH DETAILS PAGE ********/

  } ?>  