<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Faculty Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Faculty extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'admin' && $this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Faculty module');
        redirect(site_url('iibfbcbf/admin/dashboard_agency'));
      }

      $this->faculty_photo_path = 'uploads/iibfbcbf/faculty_photo';
      $this->pan_photo_path = 'uploads/iibfbcbf/pan_photo';
		}
    
    public function index()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Faculty Master";
      $data['page_title'] = 'IIBF - BCBF Agency Faculty Master';

      $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, am.is_active', array('am.agency_name'=>'ASC'));

      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username', array('cm.centre_name'=>'ASC'));

      $this->load->view('iibfbcbf/admin/faculty_master_admin', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE FACULTY DATA ********/
    public function get_faculty_data_ajax()
    {
      $table = 'iibfbcbf_faculty_master fm';
      
      $column_order = array('fm.faculty_id', 'CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")") AS agency_name', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")") AS centre_name', 'fm.faculty_number', 'CONCAT(fm.salutation, " ", fm.faculty_name) AS FacultyName', 'fm.dob', 'fm.base_location', 'fm.pan_no', 'fm.language_known', '(SELECT GROUP_CONCAT(batch_code, " (", DATE_FORMAT(batch_start_date, "%d %b %Y"), " to ", DATE_FORMAT(batch_end_date, "%d %b %Y"), ")" SEPARATOR ", ") FROM iibfbcbf_agency_centre_batch WHERE batch_status != "5" AND batch_status != "7" AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS CurrentBatches', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted"))) AS DispStatus', 'IF(fm.created_by_type=1, "Centre", "Agency") AS CreatedByType', 'fm.status'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(am1.agency_name, " (", am1.agency_code, " - ", IF(am1.allow_exam_types="Bulk/Individual", "Regular", am1.allow_exam_types),")")', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")")', 'fm.faculty_number', 'CONCAT(fm.salutation, " ", fm.faculty_name)', 'fm.dob', 'fm.base_location', 'fm.pan_no', 'fm.language_known', '(SELECT GROUP_CONCAT(batch_code, " (", DATE_FORMAT(batch_start_date, "%d %b %Y"), " to ", DATE_FORMAT(batch_end_date, "%d %b %Y"), ")" SEPARATOR ", ") FROM iibfbcbf_agency_centre_batch WHERE batch_status != "5" AND batch_status != "7" AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id))', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted")))', 'IF(fm.created_by_type=1, "Centre", "Agency")'); //SET COLUMN FOR SEARCH
      $order = array('fm.faculty_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.is_deleted = 0 	";
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
      if($s_agency != "") { $Where .= " AND fm.agency_id = '".$s_agency."'"; } 

      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND fm.centre_id = '".$s_centre."'"; } 

      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."' AND DATE(fm.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(fm.created_on) <= '".$s_to_date."')"; } 

      $s_added_by = trim($this->security->xss_clean($this->input->post('s_added_by')));
      if($s_added_by != "") { $Where .= " AND fm.created_by_type = '".$s_added_by."'"; }
      
      $s_faculty_status = trim($this->security->xss_clean($this->input->post('s_faculty_status')));
      if($s_faculty_status != "") { $Where .= " AND fm.status = '".$s_faculty_status."'"; }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " ";
      $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = fm.agency_id";
      $join_qry .= " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = fm.centre_id";
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
        $row[] = $Res['agency_name'];
        $row[] = $Res['centre_name'];
        $row[] = $Res['faculty_number'];
        $row[] = $Res['FacultyName'];
        $row[] = $Res['dob'];
        $row[] = $Res['base_location'];
        $row[] = $Res['pan_no'];
        $row[] = $Res['language_known'];
        $row[] = $Res['CurrentBatches'];
        $row[] = $Res['CreatedByType'];
        
        $row[] = '<span class="badge '.show_faculty_status($Res['status']).'" style="min-width:90px;">'.$Res['DispStatus'].'</span>';

        
        $btn_str = ' <div class="text-center no_wrap"> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/admin/faculty/faculty_details/'.url_encode($Res['faculty_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        //$btn_str .= '<a href="'.site_url('iibfbcbf/admin/faculty/add_faculty_agency/'.url_encode($Res['faculty_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
        /* if(in_array($Res['faculty_id'],$delete_ids_str_arr)) { $check_val = "checked"; } else { $check_val = ""; }
        $row[] = '<label class="css_checkbox_radio"><input type="checkbox" name="checkboxlist_new" class="checkboxlist_new" value="'.$Res['faculty_id'].'" id="checkboxlist_new_'.$Res['faculty_id'].'" onclick="update_delete_str('.$Res['faculty_id'].')" '.$check_val.'><span class="checkmark"></span></label>'; */
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE FACULTY DATA ********/
  	 

    /******** START : FACULTY DETAILS PAGE ********/
    public function faculty_details($enc_faculty_id=0)
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Faculty Master";      
      $data['page_title'] = 'IIBF - BCBF Agency Faculty Details';

      $data['enc_faculty_id'] = $enc_faculty_id;
      $faculty_id = url_decode($enc_faculty_id);
      
      $this->db->join('iibfbcbf_faculty_intrested_session_master sm', 'sm.session_interested_id = fm.session_interested_id', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am1', 'am1.agency_id = fm.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = fm.centre_id', 'LEFT');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_faculty_master fm', array('fm.faculty_id' => $faculty_id, 'fm.is_deleted' => '0'), "fm.*, IF(fm.status=0, 'Inactive', IF(fm.status=1, 'Active', IF(fm.status=2, 'In Review', 'Re-submitted'))) AS DispStatus, sm.intrested_session_name, (SELECT GROUP_CONCAT(batch_code, ' (', DATE_FORMAT(batch_start_date, '%d %b %Y'), ' to ', DATE_FORMAT(batch_end_date, '%d %b %Y'), ')' SEPARATOR ', ') FROM iibfbcbf_agency_centre_batch WHERE batch_status != '5' AND batch_status != '7' AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS CurrentBatches, (SELECT GROUP_CONCAT(batch_code, ' (', DATE_FORMAT(batch_start_date, '%d %b %Y'), ' to ', DATE_FORMAT(batch_end_date, '%d %b %Y'), ')' SEPARATOR ', ') FROM iibfbcbf_agency_centre_batch WHERE batch_status != '5' AND batch_status != '7' AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS AllBatches, am1.agency_name, am1.agency_code, am1.allow_exam_types, cm.centre_name, cm.centre_username, cm2.city_name AS centre_city_name"); 
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/faculty')); }  
      
      //$data['log_data'] = $this->master_model->getRecords('iibfbcbf_logs', array('module_slug' => 'faculty_action', 'pk_id' => $faculty_id), 'log_id, module_slug, description, created_on', array('created_on'=>'ASC')); 
      
      $data['work_exp_data'] = $this->master_model->getRecords('iibfbcbf_faculty_work_experience', array('faculty_id' => $faculty_id, 'is_active' => '1', 'is_deleted'=>'0'), 'work_experience_id, bank_name, last_position_employee_id, experience_year, experience_month', array('created_on'=>'ASC'));  
      
      $data['faculty_photo_path'] = $this->faculty_photo_path;
      $data['pan_photo_path'] = $this->pan_photo_path;

      $this->load->view('iibfbcbf/admin/faculty_details_admin', $data);
    }/******** END : FACULTY DETAILS PAGE ********/

    /******** START : FACULTY STATUS CHANGE ********/ 
    public function change_faculty_status() 
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['enc_id'] != "")
      { 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        $status = $this->security->xss_clean($this->input->post('status')); 
        $status_num_val = $this->security->xss_clean($this->input->post('status_num_val')); 
        $return_status = '';
        $id = url_decode($enc_id);        
        $this->db->where("faculty_id",$id);
        $faculty_data = $this->master_model->getRecords('iibfbcbf_faculty_master', array('is_deleted' => '0'), 'faculty_id,status');
        
        if(count($faculty_data) > 0)
        {
          if($faculty_data[0]['status'] == $status_num_val)
          {
            $return_status = $faculty_data[0]['status'];
            if($status == 'Deactivate')
            { 
              $this->form_validation->set_rules('reason_for_deactivate', 'reason for deactivating the faculty', 'trim|required|xss_clean', array('required'=>"Please enter the %s"));

              
              if($this->form_validation->run())
              {
                $reason_for_deactivate = $this->security->xss_clean($this->input->post('reason_for_deactivate'));
                $return_status = 0;
                $update_data["status"] = 0;
                $update_data["reason_for_deactivate"] = $reason_for_deactivate;
                $response = 'The faculty has been successfully Deactivated.'; 
                $this->session->set_flashdata('faculty_status_success',$response); 
                $this->Iibf_bcbf_model->insert_common_log('Faculty : Deactivated', 'iibfbcbf_faculty_master', $this->db->last_query(), $id,'faculty_action','The faculty has been successfully deactivated by the admin Reason : '.$reason_for_deactivate, json_encode($update_data));
              }else{
                $this->session->set_flashdata('faculty_status_error',form_error('reason_for_deactivate'));
              }
                            
            }
            else if($status == 'Activate')
            { 
              $return_status = 1;
              $update_data["status"] = 1;
              $response = 'The faculty has been successfully Activated.';
              $this->session->set_flashdata('faculty_status_success',$response);
              $this->Iibf_bcbf_model->insert_common_log('Faculty : Activated', 'iibfbcbf_faculty_master', $this->db->last_query(), $id,'faculty_action','The faculty has been successfully activated by the admin.', json_encode($update_data));
              
            } 
            $this->db->where("faculty_id",$id);
            $this->db->update("iibfbcbf_faculty_master",$update_data); 
            $flag = "success";
          } 
          else
          {
            $this->session->set_flashdata('faculty_status_error',"The faculty status was already changed.");
          }
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $response;
      $result['status'] = $return_status;
      echo json_encode($result);  
    } 
    /******** START : FACULTY STATUS CHANGE ********/

    /******** START : LOAD CENTERS ********/ 
    public function load_centre_data() 
    {      
      $flag = "success";
      $html = '<option value="">Select Centre</option>';
      if(isset($_POST) && isset($_POST['s_agency']))
      { 
        $s_agency = $this->security->xss_clean($this->input->post('s_agency'));       
        if($s_agency != "") { $this->db->where('cm.agency_id',$s_agency); }
        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username', array('cm.centre_name'=>'ASC'));
        
        $html .= '<option value="0">Blank Centre</option>';
        if(count($agency_centre_data) > 0)
        {
          foreach($agency_centre_data as $res)
          {
            $html .= '<option value="'.$res['centre_id'].'">'.$res['centre_name']." (".$res['centre_username']." - ".$res['city_name'].")".'</option>';;
          }  
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $html; 
      echo json_encode($result);  
    } /******** END : LOAD CENTERS ********/
 
 } ?>  