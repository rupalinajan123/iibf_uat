<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Batch_MIS
  ** Created BY: Sagar Matale On 17-11-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Batch_MIS extends CI_Controller 
  {
    public function __construct()
    {
      exit;
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
        $this->session->set_flashdata('error','You do not have permission to access Batch MIS module');
        redirect(site_url('iibfbcbf/admin/dashboard_admin'));
      } 

      $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';
		}
    
    public function index()
    {   
      $data['act_id'] = "Reports";
      $data['sub_act_id'] = "Batch MIS";
      $data['page_title'] = 'IIBF - BCBF Batch MIS';

      $data['agency_data'] = $data['agency_centre_data'] = array();
      
      $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

      $this->load->view('iibfbcbf/admin/batch_mis_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Batch MIS DATA ********/
    public function get_batch_mis_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_agency_centre_batch acb';
      $GroupBy = " Group By acb.batch_id";
      
      $column_order = array('acb.batch_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advance", "")) AS DispBatchType', 'acb.batch_start_date', 'acb.batch_end_date', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled"))))))) AS DispBatchStatus', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "3" THEN bc.candidate_id END) AS total_release_candidate', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END) AS total_hold_candidate', 'GROUP_CONCAT( DISTINCT im.inspector_name ) AS all_assign_inspectors', 'COUNT(bi.inspection_id) AS inspection_count', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", SUM(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)),"0") AS total_inspection_time_minutes', '(
        SUM(CASE WHEN bi.teaching_quality_interaction_with_candidates = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_quality_softskill_session = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.candidates_attentiveness = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.attitude_behaviour = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_interaction_with_faculty = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_response_to_queries = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_effectiveness = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.curriculum_covered = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_delivery = "Poor" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_coordination = "Poor" THEN 1 ELSE 0 END)
    ) AS total_poor_counts', '(
        SUM(CASE WHEN bi.teaching_quality_interaction_with_candidates = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_quality_softskill_session = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.candidates_attentiveness = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.attitude_behaviour = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_interaction_with_faculty = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_response_to_queries = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_effectiveness = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.curriculum_covered = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_delivery = "Average" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_coordination = "Average" THEN 1 ELSE 0 END)
    ) AS total_average_counts', '(
        SUM(CASE WHEN bi.teaching_quality_interaction_with_candidates = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_quality_softskill_session = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.candidates_attentiveness = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.attitude_behaviour = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_interaction_with_faculty = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_response_to_queries = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_effectiveness = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.curriculum_covered = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_delivery = "Good" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_coordination = "Good" THEN 1 ELSE 0 END)
    ) AS total_good_counts', '(
        SUM(CASE WHEN bi.teaching_quality_interaction_with_candidates = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_quality_softskill_session = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.candidates_attentiveness = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.attitude_behaviour = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_interaction_with_faculty = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.learning_quality_response_to_queries = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.teaching_effectiveness = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.curriculum_covered = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_delivery = "Excellent" THEN 1 ELSE 0 END) +
        SUM(CASE WHEN bi.overall_compliance_training_coordination = "Excellent" THEN 1 ELSE 0 END)
    ) AS total_excellent_counts', 

    'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag','acb.agency_id','im.inspector_name'); //SET COLUMNS FOR SORT
      
      $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advance", ""))', 'acb.batch_start_date', 'acb.batch_end_date', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled")))))))','im.inspector_name'); //SET COLUMN FOR SEARCH
      $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER
      
       
      $WhereForTotal = "WHERE acb.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE acb.is_deleted = 0  ";
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  

      if ($form_action == 'export')
      {
        if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
        {
          $Where .= " AND (";
          for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
          $Where = substr_replace( $Where, "", -3 );
          $Where .= ')';
        }
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
      if($s_batch_code != "") { $Where .= " AND acb.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; } //iibfbcbf/iibf_bcbf_helper.php
      
      $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
      if($s_batch_status != "") { $Where .= " AND acb.batch_status = '".$s_batch_status."'"; }      

      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; 
      if ($_POST['length'] != '-1' && $form_action != 'export') 
      { 
        $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
      } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id";
      $join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bi ON bi.batch_id = acb.batch_id";
      $join_qry .= " LEFT JOIN iibfbcbf_inspector_master im ON im.inspector_id = bi.inspector_id";
      //$join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bim ON bim.inspector_id = im.inspector_id";
      $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc ON bc.batch_id = acb.batch_id";

      $Where .= $GroupBy;
      $WhereForTotal .= $GroupBy;
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Batch_MIS_".date('Y-m-d').".xls";  
        // Column names 
        $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Batch From Date', 'Batch To Date', 'Total Candidates','Inspector Name','Status');  
        // Display column names as first row 
        $excelData = implode("\t", array_values($fields)) . "\n";  
      }

      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['agency_name'];
        $row[] = $Res['DispCentreName'];
        $row[] = $Res['batch_code'];
        $row[] = $Res['DispBatchType'];
        $row[] = $Res['batch_start_date'];
        $row[] = $Res['batch_end_date'];

        if ($form_action == 'export')
        {
          $row[] = $Res['DispBatchStatus']; 
        }
        else
        {
          $row[] = '<span class="badge '.show_batch_status($Res['batch_status']).'" style="min-width:90px;">'.$Res['DispBatchStatus'].'</span>';
        }

        //$row[] = $Res['BatchTime'];
        $row[] = $Res['total_release_candidate'];
        $row[] = $Res['total_hold_candidate'];
        $row[] = $Res['all_assign_inspectors'];
        //$row[] = $Res['total_candidates'];
        $row[] = $Res['inspection_count'];        
        $row[] = $Res['total_inspection_time_minutes'];        
        $row[] = $Res['total_poor_counts'];        
        $row[] = $Res['total_average_counts'];        
        $row[] = $Res['total_good_counts'];        
        $row[] = $Res['total_excellent_counts'];        
        
        

        

        /*$batch_candidate_count = '';
        $batch_candidate_qry = $this->db->query('SELECT candidate_id FROM iibfbcbf_batch_candidates WHERE agency_id = "'.$Res['agency_id'].'" AND centre_id = "'.$Res['centre_id'].'" AND batch_id = "'.$Res['batch_id'].'" AND is_deleted="0" ');
        $batch_candidate_count = $batch_candidate_qry->num_rows();
        //echo $this->db->last_query(); 
        $btn_str = ' <div class="text-left no_wrap"> '; 
        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/Batch_MIS/training_batch_details/'.url_encode($Res['batch_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> '; 
        //show candidate list button only when there is any candidate available in the batch
        if($batch_candidate_count > 0)
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/admin/batch_candidates/index/'.url_encode($Res['batch_id'])).'" class="btn btn-danger btn-xs" title="Candidate List"><i class="fa fa-users" aria-hidden="true"></i></a> ';
        }  
        $btn_str .= ' </div>';
        $row[] = $btn_str;*/
        
        /* if(in_array($Res['batch_id'],$delete_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
        $row[] = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['batch_id'].'" id="checkboxlist_new_'.$Res['batch_id'].'" onclick="update_delete_str('.$Res['batch_id'].')" '.$check_val.'><span class="checkmark"></span></label>'; */
        
        if ($form_action == 'export')
        {
          array_walk($row, 'filterData');
          $excelData .= implode("\t", array_values($row)) . "\n";
        }

        $data[] = $row; 
      }			
      
      if ($form_action == 'export')
      {
        if (count($Rows) == '0')
        {
          $excelData .= 'No records found...' . "\n";
        }

        // Headers for download 
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        // Render excel data 
        echo $excelData;
        exit;
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE Batch MIS DATA ********/  	     
 
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
        $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name'); 
        //$agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0'), 'centre_id,agency_id,status,centre_district,centre_city,centre_username');
        
        if(count($agency_centre_data) > 0)
        {
          foreach($agency_centre_data as $res){
            $html .= '<option value="'.$res['centre_id'].'">'.$res['centre_name']." (".$res['city_name'].")".'</option>';;
          }  
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $html; 
      echo json_encode($result);  
    } /******** END : LOAD CENTERS ********/
 
  } ?>  