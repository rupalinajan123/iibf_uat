<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Faculty Master
  ** Created BY: Sagar Matale On 11-10-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Faculty_master_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      $this->faculty_photo_path = 'uploads/iibfbcbf/faculty_photo';
      $this->pan_photo_path = 'uploads/iibfbcbf/pan_photo';

      $this->centre_id = 0;
      if($this->login_user_type == 'agency') 
      { 
        $this->agency_id = $this->login_agency_centre_id; 
      }
      else if($this->login_user_type == 'centre')
      {
        $this->centre_id = $this->login_agency_centre_id;

        $agency_id_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id' => $this->login_agency_centre_id), "agency_id");
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
          //$this->session->set_flashdata('error','You do not have permission to access Faculty Master module');
          //redirect(site_url('iibfbcbf/agency/dashboard_agency'));
        }
      }
		}
    
    public function index($search_faculty_status='')
    {   
      $data['act_id'] = "Faculty Master";
      $data['sub_act_id'] = "Faculty Master";
      $data['page_title'] = 'IIBF - BCBF Agency Faculty Master';
      $data['search_faculty_status'] = $search_faculty_status;

      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0', 'cm.agency_id'=>$this->agency_id), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name, cm.centre_username', array('cm.centre_name'=>'ASC'));

      $this->load->view('iibfbcbf/agency/faculty_master_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE FACULTY DATA ********/
    public function get_faculty_data_ajax()
    {
      $table = 'iibfbcbf_faculty_master fm';
      
      $column_order = array('fm.faculty_id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")") AS centre_name', 'fm.faculty_number', 'CONCAT(fm.salutation, " ", fm.faculty_name) AS FacultyName', 'fm.dob', 'fm.base_location', 'fm.pan_no', 'fm.language_known', '(SELECT GROUP_CONCAT(batch_code, " (", DATE_FORMAT(batch_start_date, "%d %b %Y"), " to ", DATE_FORMAT(batch_end_date, "%d %b %Y"), ")" SEPARATOR ", ") FROM iibfbcbf_agency_centre_batch WHERE batch_status != "5" AND batch_status != "7" AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS CurrentBatches', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted"))) AS DispStatus', 'IF(fm.created_by_type=1, "Centre", "Agency") AS CreatedByType', 'fm.status', 'fm.agency_id', 'fm.centre_id', 'fm.created_by_type'); //SET COLUMNS FOR SORT
      
      $column_search = array('fm.faculty_number', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name,")")', 'fm.salutation', 'fm.faculty_name', 'CONCAT(fm.salutation, " ", fm.faculty_name)', 'fm.dob',  'fm.base_location', 'fm.pan_no', 'fm.language_known', '(SELECT GROUP_CONCAT(batch_code, " (", DATE_FORMAT(batch_start_date, "%d %b %Y"), " to ", DATE_FORMAT(batch_end_date, "%d %b %Y"), ")" SEPARATOR ", ") FROM iibfbcbf_agency_centre_batch WHERE batch_status != "5" AND batch_status != "7" AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id))', 'IF(fm.status=0, "Inactive", IF(fm.status=1, "Active", IF(fm.status=2, "In Review", "Re-submitted")))', 'IF(fm.created_by_type=1, "Centre", "Agency")'); //SET COLUMN FOR SEARCH
      $order = array('fm.faculty_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.agency_id = '".$this->agency_id."' AND fm.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.agency_id = '".$this->agency_id."' AND fm.is_deleted = 0 	";
      
      if($this->login_user_type == 'centre') 
      {
        $WhereForTotal .= " AND fm.centre_id = '".$this->login_agency_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where .= " AND fm.centre_id = '".$this->login_agency_centre_id."' ";
      }
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
      
      //CUSTOM SEARCH
      if($this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency') 
      { 
        $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
        if($s_centre != "") { $Where .= " AND fm.centre_id = '".$s_centre."'"; } 
        
        $s_added_by = trim($this->security->xss_clean($this->input->post('s_added_by')));
        if($s_added_by != "") { $Where .= " AND fm.created_by_type = '".$s_added_by."'"; } 
      }

      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      
      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."' AND DATE(fm.created_on) <= '".$s_to_date."')"; 
      }else if($s_from_date != "") { $Where .= " AND (DATE(fm.created_on) >= '".$s_from_date."')"; 
      }else if($s_to_date != "") { $Where .= " AND (DATE(fm.created_on) <= '".$s_to_date."')"; } 

      $s_faculty_status = trim($this->security->xss_clean($this->input->post('s_faculty_status')));
      if($s_faculty_status != "") { $Where .= " AND fm.status = '".$s_faculty_status."'"; }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = fm.centre_id";
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

        
        $btn_str = ' <div class="no_wrap" style="width: 60px; margin: 0 auto;"> ';

        $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/faculty_master_agency/faculty_details_agency/'.url_encode($Res['faculty_id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        $show_edit_btn_flag = 0;
        if($Res['created_by_type'] == '1')//FACULTY ADDED BY CENTRE
        {
          if($Res['centre_id'] == $this->centre_id) { $show_edit_btn_flag = '1'; }
        }
        else if($Res['created_by_type'] == '2')//FACULTY ADDED BY AGENCY
        {
          if($Res['agency_id'] == $this->agency_id) { $show_edit_btn_flag = '1'; }
        }

        if($show_edit_btn_flag == '1')
        {
          $btn_str .= '<a href="'.site_url('iibfbcbf/agency/faculty_master_agency/add_faculty_agency/'.url_encode($Res['faculty_id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
        }

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
  	
    /******** START : ADD / UPDATE FACULTY DATA ********/
    public function add_faculty_agency($enc_faculty_id=0)
    {   
      /* if($this->login_user_type != 'centre')
      {
        $this->session->set_flashdata('error','You do not have permission to Add/Update the Faculty');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      } */

      $data['act_id'] = "Faculty Master";
      $data['sub_act_id'] = "Faculty Master";  

      $agency_id = $this->agency_id;
      $centre_id = $this->centre_id;
      
      $data['faculty_photo_path'] = $faculty_photo_path = $this->faculty_photo_path;
      $data['pan_photo_path'] = $pan_photo_path = $this->pan_photo_path;

      $data['enc_faculty_id'] = $enc_faculty_id;
      $data['dob_end_date'] = date('Y-m-d', strtotime('-22years'));  //Changed -25 to -22 years as per client requirement at 15 April 2025  

      $data['faculty_photo_error'] = $data['pan_photo_error'] = '';
      $error_flag = 0;
      
      if($enc_faculty_id == '0') { $data['mode'] = $mode = "Add"; $faculty_id = $enc_faculty_id; }
      else
      {
        $faculty_id = url_decode($enc_faculty_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_faculty_master', array('agency_id' => $agency_id, 'centre_id' => $centre_id, 'faculty_id' => $faculty_id, 'is_deleted' => '0'), "*, IF(status=0, 'Inactive', IF(status=1, 'Active', IF(status=2, 'In Review', 'Re-submitted'))) AS DispStatus");
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/faculty_master_agency/add_faculty_agency')); }
        
        $data['mode'] = $mode = "Update";  
        
        $field_id_arr = $bank_fi_name_arr = $last_position_id_arr = $gross_duration_year_arr = $gross_duration_month_arr = array();
        $field_arr = $this->master_model->getRecords('iibfbcbf_faculty_work_experience', array('faculty_id' => $faculty_id, 'is_deleted' => '0'));
        if(count($field_arr) > 0)
        {
          foreach($field_arr as $res)
          {
            $field_id_arr[] = $res['work_experience_id'];
            $bank_fi_name_arr[] = $res['bank_name'];
            $last_position_id_arr[] = $res['last_position_employee_id'];
            $gross_duration_year_arr[] = $res['experience_year'];
            $gross_duration_month_arr[] = $res['experience_month'];
          }
        }
        $data['form_field_id_arr'] = $form_field_id_arr = $field_id_arr; 
        $data['form_bank_fi_name_arr'] = $bank_fi_name_arr;
        $data['form_last_position_id_arr'] = $last_position_id_arr;
        $data['form_gross_duration_year_arr'] = $gross_duration_year_arr;
        $data['form_gross_duration_month_arr'] = $gross_duration_month_arr;        
      }
      
      if(isset($_POST) && count($_POST) > 0)
      { 
        $faculty_photo_required_flg = $pan_photo_required_flg = 'n';
        $faculty_photo_req_validation = $pan_photo_req_validation = '';
        if($mode == 'Add') 
        { 
          $faculty_photo_required_flg = $pan_photo_required_flg = 'y'; 
          $faculty_photo_req_validation = $pan_photo_req_validation = 'required|';
        }
        else
        {
          if($form_data[0]['faculty_photo'] == "") 
          { 
            $faculty_photo_required_flg = 'y'; 
            $faculty_photo_req_validation = 'required|';
          }
          if($form_data[0]['pan_photo'] == "") 
          { 
            $pan_photo_required_flg = 'y'; 
            $pan_photo_req_validation = 'required|';
          }
        }

        $this->form_validation->set_rules('salutation', 'Faculty Name (Salutation)', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('faculty_name', 'Faculty Full Name', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('faculty_photo', 'faculty photo', 'trim|'.$faculty_photo_req_validation.'callback_fun_validate_file_upload[faculty_photo|'.$faculty_photo_required_flg.'|jpg,jpeg,png|20|faculty photo]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb|input display name'
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('pan_no', 'PAN No.', 'trim|required|max_length[10]|callback_fun_validate_pan_no|callback_validation_check_pan_exist['.$enc_faculty_id.']|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('pan_photo', 'PAN photo', 'trim|'.$pan_photo_req_validation.'callback_fun_validate_file_upload[pan_photo|'.$pan_photo_required_flg.'|jpg,jpeg,png|20|PAN photo]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb'        
        $this->form_validation->set_rules('base_location', '', 'trim|xss_clean|callback_fun_restrict_input[allow_only_alphabets_and_space]|max_length[50]');
        $this->form_validation->set_rules('academic_qualification', 'Academic Qualification(s) with year of passing', 'trim|required|max_length[50]|callback_fun_validation_check_year|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('professional_qualification', '', 'trim|max_length[50]|callback_fun_validation_check_year|xss_clean');
        $this->form_validation->set_rules('language_known[]', 'language', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('bank_fi_name_arr[]', 'Bank/ FI Name', 'trim|required|max_length[100]|xss_clean', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('last_position_id_arr[]', 'Last Position held, Employee Id', 'trim|required|max_length[50]|xss_clean', array('required'=>"Please enter the %s"));        
        $this->form_validation->set_rules('gross_duration_year_arr[]', 'Gross Duration Year', 'trim|required|xss_clean|max_length[2]', array('required'=>"Please enter the %s"));
        $this->form_validation->set_rules('gross_duration_month_arr[]', 'Gross Duration Month', 'trim|required|xss_clean|max_length[2]', array('required'=>"Please enter the %s"));        
        $this->form_validation->set_rules('work_exp_iibf', '', 'trim|xss_clean|callback_fun_restrict_input[allow_only_alphabets_and_floats_and_space]|max_length[100]');
        
        $this->form_validation->set_rules('training_faculty_exp', '', 'trim|xss_clean|callback_fun_restrict_input[allow_only_alphabets_and_floats_and_space]|max_length[100]');
        /* if($this->input->post('training_faculty_exp') != "")
        {
          $this->form_validation->set_rules('training_faculty_exp_year', 'Year', 'trim|required|xss_clean|callback_fun_restrict_input[allow_only_numbers]|callback_fun_validation_check_year|max_length[4]', array('required'=>"Please enter the %s"));
          $this->form_validation->set_rules('training_faculty_exp_month', 'Month', 'trim|required|xss_clean|callback_fun_restrict_input[allow_only_numbers]|max_length[2]|callback_validate_max_number[12]', array('required'=>"Please enter the %s"));
        } */
        
        $this->form_validation->set_rules('session_interested_id', 'Interested to take sessions on', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('softskills_banking_exp', '', 'trim|xss_clean|max_length[100]');
        /* $this->form_validation->set_rules('training_activities_exp', '', 'trim|xss_clean|max_length[100]'); */

        ///$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        if($this->form_validation->run())
        { 
          $new_faculty_photo = $new_pan_photo = '';

          $pan_no = $this->input->post('pan_no');
          //echo '<pre>'; print_r($_POST); exit;
          if($_FILES['faculty_photo']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_img_name = "faculty_photo_".$pan_no.'_'.rand(1000,9999);
            $upload_data1 = $this->Iibf_bcbf_model->upload_file("faculty_photo", array('png','jpg','jpeg'), $new_img_name, "./".$faculty_photo_path, "png|jpeg|jpg",0,'','','','20','1000','500',$new_img_name);
            if($upload_data1['response'] == 'error')
            {
              $data['faculty_photo_error'] = $upload_data1['message'];
              $error_flag = 1;
            }
            else if($upload_data1['response'] == 'success')
            {
              $add_data['faculty_photo'] = $faculty_photo = $new_faculty_photo = $upload_data1['message'];
            }
          } 

          if($_FILES['pan_photo']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_img_name = "pan_photo_".$pan_no.'_'.rand(1000,9999);
            $upload_data2 = $this->Iibf_bcbf_model->upload_file("pan_photo", array('png','jpg','jpeg'), $new_img_name, "./".$pan_photo_path, "png|jpeg|jpg",0,'','','','20','1000','500',$new_img_name);
            if($upload_data2['response'] == 'error')
            {
              $data['pan_photo_error'] = $upload_data2['message'];
              $error_flag = 1;
            }
            else if($upload_data2['response'] == 'success')
            {
              $add_data['pan_photo'] = $pan_photo = $new_pan_photo = $upload_data2['message'];
            }
          } 
          
          if($error_flag == 1)
          {
            @unlink("./".$faculty_photo_path."/".$upload_data1['message']);
            @unlink("./".$pan_photo_path."/".$upload_data2['message']);
          }
          else if($error_flag == 0)
          {
            $posted_arr = json_encode($_POST);
            $LogName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_centre_id, $this->login_user_type);

            //echo 'IN';exit;
            $add_data['salutation'] = $this->input->post('salutation');
            $add_data['agency_id'] = $agency_id;
            $add_data['centre_id'] = $centre_id;
            $add_data['faculty_name'] = $this->input->post('faculty_name');
            $add_data['dob'] = date("Y-m-d", strtotime($this->input->post('dob')));
            $add_data['pan_no'] = $this->input->post('pan_no');
            $add_data['base_location'] = $this->input->post('base_location');
            $add_data['academic_qualification'] = $this->input->post('academic_qualification');
            $add_data['professional_qualification'] = $this->input->post('professional_qualification');
            $add_data['language_known'] = implode(", ",$this->input->post('language_known'));
            $add_data['work_exp_iibf'] = $this->input->post('work_exp_iibf');

            $add_data['training_faculty_exp'] = $training_faculty_exp = $this->input->post('training_faculty_exp');
            /* if($training_faculty_exp != "")
            {
              $add_data['training_faculty_exp_year'] = $this->input->post('training_faculty_exp_year');
              $add_data['training_faculty_exp_month'] = $this->input->post('training_faculty_exp_month');
            }
            else
            {
              $add_data['training_faculty_exp_year'] = '';
              $add_data['training_faculty_exp_month'] = '';
            } */

            $add_data['session_interested_id'] = $this->input->post('session_interested_id');
            $add_data['softskills_banking_exp'] = $this->input->post('softskills_banking_exp');
            /* $add_data['training_activities_exp'] = $this->input->post('training_activities_exp'); */
            $add_data['ip_address'] = get_ip_address(); //general_helper.php
            
            if($mode == "Add") 
            {
              $add_data['status'] = '2';
              $add_data['created_on'] = date("Y-m-d H:i:s");
              $add_data['created_by'] = $this->login_agency_centre_id;   
              
              if($this->login_user_type == 'centre') { $add_data['created_by_type'] = '1'; }
              else if($this->login_user_type == 'agency') { $add_data['created_by_type'] = '2'; }
              
              $this->Iibf_bcbf_model->check_file_exist($faculty_photo, "./".$faculty_photo_path."/", 'iibfbcbf/agency/faculty_master_agency', 'Faculty record not added due to missing faculty photo');//IF faculty_photo NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE
              
              $this->Iibf_bcbf_model->check_file_exist($pan_photo, "./".$pan_photo_path."/", 'iibfbcbf/agency/faculty_master_agency', 'Faculty record not added due to missing pan photo');//IF pan_photo NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE

              $this->master_model->insertRecord('iibfbcbf_faculty_master',$add_data);
              $faculty_id = $this->db->insert_id();

              $this->Iibf_bcbf_model->insert_common_log($this->login_user_type.' : Faculty Added', 'iibfbcbf_faculty_master', $this->db->last_query(), $faculty_id,'faculty_action','The faculty has successfully added by the '.$this->login_user_type.' '.$LogName['disp_name'], $posted_arr);

              $tot_rec_qry = $this->db->query('SELECT fm.faculty_id FROM iibfbcbf_faculty_master fm WHERE fm.faculty_id <= "'.$faculty_id.'"');
              $get_total_record = $tot_rec_qry->num_rows();
              $faculty_code = "F".sprintf('%04d', $get_total_record); 
              $this->master_model->updateRecord('iibfbcbf_faculty_master', array('faculty_number'=>$faculty_code), array('faculty_id'=>$faculty_id));               
              
              $this->session->set_flashdata('success','Faculty record added successfully');              
            }
            else if($mode == "Update")
            {
              $chk_faculty_photo = '';
              if($new_faculty_photo == '') { $chk_faculty_photo = $form_data[0]['faculty_photo']; }
              else if($new_faculty_photo != '') { $chk_faculty_photo = $new_faculty_photo; }

              $this->Iibf_bcbf_model->check_file_exist($chk_faculty_photo, "./".$faculty_photo_path."/", 'iibfbcbf/agency/faculty_master_agency', 'Faculty record not updated due to missing faculty photo');//IF faculty_photo NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE              
              
              $chk_pan_photo = '';
              if($new_pan_photo == '') { $chk_pan_photo = $form_data[0]['pan_photo']; }
              else if($new_pan_photo != '') { $chk_pan_photo = $new_pan_photo; }

              $this->Iibf_bcbf_model->check_file_exist($chk_pan_photo, "./".$pan_photo_path."/", 'iibfbcbf/agency/faculty_master_agency', 'Faculty record not updated due to missing pan photo');//IF pan_photo NOT EXIST WHILE ADDING THE RECORD, THEN SHOW ERROR MESSAGE

              if($form_data[0]['status'] == '1') //IF FACULTY STATUS IS ACTIVE AND AGENCY AGAIN UPDATE THE RECORD THEN MAKE FACULTY STATUS AS RE-SUBMITTED
              {
                $add_data['status'] = '3';
              }

              $add_data['updated_on'] = date("Y-m-d H:i:s");
              $add_data['updated_by'] = $this->login_agency_centre_id;            
              $this->master_model->updateRecord('iibfbcbf_faculty_master', $add_data, array('faculty_id'=>$faculty_id));

              if($new_faculty_photo != '') { @unlink("./".$faculty_photo_path."/".$form_data[0]['faculty_photo']); }
              if($new_pan_photo != '') { @unlink("./".$pan_photo_path."/".$form_data[0]['pan_photo']); }
              
              $this->Iibf_bcbf_model->insert_common_log($this->login_user_type.' : Faculty Updated', 'iibfbcbf_faculty_master', $this->db->last_query(), $faculty_id,'faculty_action','The faculty has successfully updated by the '.$this->login_user_type.' '.$LogName['disp_name'], $posted_arr);
							
              $this->session->set_flashdata('success','Faculty record updated successfully');              
            }

            if($faculty_id > 0)
            {
              $field_id_arr = $this->input->post('field_id_arr');              
              $bank_fi_name_arr = $this->input->post('bank_fi_name_arr');
              $last_position_id_arr = $this->input->post('last_position_id_arr');
              $gross_duration_year_arr = $this->input->post('gross_duration_year_arr');
              $gross_duration_month_arr = $this->input->post('gross_duration_month_arr');

              if(count($field_id_arr) > 0)
              {
                $i = 0;
                foreach($field_id_arr as $res)
                {
                  $add_field = array();
                  $add_field['faculty_id'] = $faculty_id;
                  $add_field['bank_name'] = $bank_fi_name_arr[$i];
                  $add_field['last_position_employee_id'] = $last_position_id_arr[$i];
                  $add_field['experience_year'] = $gross_duration_year_arr[$i];
                  $add_field['experience_month'] = $gross_duration_month_arr[$i];
                  $add_field['ip_address'] = get_ip_address(); //general_helper.php          

                  if($res == 0)
                  {
                    $add_field['is_active'] = '1';                    
                    $add_field['created_on'] = date("Y-m-d H:i:s");
                    $add_field['created_by'] = $this->login_agency_centre_id;
                    $this->master_model->insertRecord('iibfbcbf_faculty_work_experience',$add_field);
                  }
                  else
                  {                    
                    $add_field['updated_on'] = date("Y-m-d H:i:s");
                    $add_field['updated_by'] = $this->login_agency_centre_id;
                    $this->master_model->updateRecord('iibfbcbf_faculty_work_experience',$add_field, array('work_experience_id' => $res));
                  }
                  $i++;
                }
              }
            }
            
            if($mode == 'Update')//DELETE PREVIOUS Work Experience
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
                  $del_data['deleted_by'] = $this->login_agency_centre_id;
                  $this->master_model->updateRecord('iibfbcbf_faculty_work_experience',$del_data, array('work_experience_id' => $del));
                }
              }
            }

            redirect(site_url('iibfbcbf/agency/faculty_master_agency'));
          }          
        }
      }	
      
      $data['page_title'] = 'IIBF - BCBF Agency '.$mode.' Faculty';

      $data['faculty_intrested_session_data'] = $this->master_model->getRecords('iibfbcbf_faculty_intrested_session_master', array('is_active' => '1', 'is_deleted' => '0'), 'session_interested_id, intrested_session_name, sort_no', array('sort_no'=>'ASC'));
      $this->load->view('iibfbcbf/agency/add_faculty_agency', $data);
    }/******** END : ADD / UPDATE FACULTY DATA ********/

    /******** START : FACULTY DETAILS PAGE ********/
    public function faculty_details_agency($enc_faculty_id=0)
    {   
      $data['act_id'] = "Faculty Master";
      $data['sub_act_id'] = "Faculty Master";      
      $data['page_title'] = 'IIBF - BCBF Agency Faculty Details';

      $data['faculty_photo_path'] = $this->faculty_photo_path;
      $data['pan_photo_path'] = $this->pan_photo_path;

      $data['enc_faculty_id'] = $enc_faculty_id;
      $faculty_id = url_decode($enc_faculty_id);      
      
      $this->db->join('iibfbcbf_faculty_intrested_session_master sm', 'sm.session_interested_id = fm.session_interested_id', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = fm.agency_id', 'LEFT');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = fm.centre_id', 'LEFT');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      if($this->login_user_type == 'centre') { $this->db->where('fm.centre_id',$this->login_agency_centre_id); }
      $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_faculty_master fm', array('fm.agency_id' => $this->agency_id, 'fm.faculty_id' => $faculty_id, 'fm.is_deleted' => '0'), "fm.*, IF(fm.status=0, 'Inactive', IF(fm.status=1, 'Active', IF(fm.status=2, 'In Review', 'Re-submitted'))) AS DispStatus, sm.intrested_session_name, (SELECT GROUP_CONCAT(batch_code, ' (', DATE_FORMAT(batch_start_date, '%d %b %Y'), ' to ', DATE_FORMAT(batch_end_date, '%d %b %Y'), ')' SEPARATOR ', ') FROM iibfbcbf_agency_centre_batch WHERE batch_status != '5' AND batch_status != '7' AND CURRENT_DATE() BETWEEN batch_start_date AND batch_end_date AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS CurrentBatches, (SELECT GROUP_CONCAT(batch_code, ' (', DATE_FORMAT(batch_start_date, '%d %b %Y'), ' to ', DATE_FORMAT(batch_end_date, '%d %b %Y'), ')' SEPARATOR ', ') FROM iibfbcbf_agency_centre_batch WHERE batch_status != '5' AND batch_status != '7' AND (first_faculty = fm.faculty_id OR second_faculty = fm.faculty_id OR third_faculty = fm.faculty_id OR fourth_faculty = fm.faculty_id)) AS AllBatches, am.agency_name, am.agency_code, am.allow_exam_types, cm.centre_name, cm.centre_username, cm2.city_name AS centre_city_name");
      if(count($form_data) == 0) { redirect(site_url('iibfbcbf/agency/faculty_master_agency')); }
      
      $data['work_exp_data'] = $this->master_model->getRecords('iibfbcbf_faculty_work_experience', array('faculty_id' => $faculty_id, 'is_active' => '1', 'is_deleted'=>'0'), 'work_experience_id, bank_name, last_position_employee_id, experience_year, experience_month', array('created_on'=>'ASC'));  
      
      $this->load->view('iibfbcbf/agency/faculty_details_agency', $data);
    }/******** END : FACULTY DETAILS PAGE ********/

    /******** START : VALIDATION FUNCTION TO CHECK PAN NUMBER EXIST OR NOT ********/
    //CHECK IF PAN NUMBER EXIST IN SAME AGENCY
    //CHECK IF PAN NUMBER IS EXIST AND ACTIVE IN ANY OTHER AGENCY
    public function validation_check_pan_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['pan_no'] != "")
      {
        if($type == '1') 
        { 
          $pan_no = $this->security->xss_clean($this->input->post('pan_no')); 
          $enc_faculty_id = $str;

          if($str != "" && $str != '0') { $faculty_id = url_decode($enc_faculty_id); }
          else { $faculty_id = $enc_faculty_id; }
        }
        else 
        { 
          $pan_no = $str; 
          $enc_faculty_id = $type;
          $faculty_id = url_decode($enc_faculty_id);
        }

        //CHECK IF PAN NUMBER IS EXIST IN SAME CENTRE OR EXIST IN PRENT AGENCY
        $faculty_data = $this->master_model->getRecords('iibfbcbf_faculty_master', array('is_deleted' => '0', 'pan_no' => $pan_no, 'faculty_id !=' => $faculty_id, 'agency_id'=>$this->agency_id), 'faculty_id, centre_id, agency_id, pan_no, status, created_by_type');
                
        if(count($faculty_data) == 0)
        {
          $flag = 'success';
        }
        else
        {
          if($faculty_data[0]['created_by_type'] == '1') //FACULTY ADDED BY CENTRE
          {
            if($this->login_user_type == 'centre')
            {
              if($faculty_data[0]['centre_id'] == $this->centre_id)  //CHECK PAN NUMBER EXIST IN SAME CENTRE
              { 
                $response = 'The Pan no is already exist in your centre'; 
              }
              else
              {
                $flag = 'success';
              }
            }
            else if($this->login_user_type == 'agency')
            {
              $response = 'The Pan no is already exist in your center';
            }
          }
          else if($faculty_data[0]['created_by_type'] == '2') //FACULTY ADDED BY AGENCY
          {
            $response = 'The Pan no is already exist in your agency'; 
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
        else if($_POST['pan_no'] != "")
        {
          $this->form_validation->set_message('validation_check_pan_exist', $response);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK PAN NUMBER EXIST OR NOT ********/

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

    /******** START : VALIDATION FUNCTION TO CHECK VALID PAN NUMBER ********/
    function fun_validate_pan_no($str) // Custom callback function for check valid pan number
    {
      if($str != '')
      {
        $result = $this->Iibf_bcbf_model->fun_validate_pan_no($str); 
        if($result['flag'] == 'success') { return true; }
        else
        {
          $this->form_validation->set_message('fun_validate_pan_no', $result['response']);
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK VALID PAN NUMBER ********/

    /******** START : VALIDATION FUNCTION TO CHECK MAXIMUM NUMBER VALUE ********/
    public function validate_max_number($str,$max_val) 
    {
      if($str != "")
      {
        if ($str <= $max_val) { return true; /* // Valid range */ } 
        else 
        { 
          $this->form_validation->set_message('validate_max_number', 'The {field} must be a number less than 12'); 
          return false; // Invalid range 
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK MAXIMUM NUMBER VALUE ********/

    /******** START : VALIDATION FUNCTION TO CHECK IF ENTERED STRING HAS YEAR VALUE MORE THAN CURRENT YEAR ********/
    function fun_validation_check_year($str)
    {
      if(trim($str) == "") { return true; /* echo 'blank'; */ }
      else
      {
        // Regular expression to match the pattern and extract the number            
        $pattern = '!\d+!'; 
        preg_match_all($pattern, $str, $match); 
        
        $validate_flag = 1;
        if ($match) 
        {
          $current_year = date("Y");              
          $chkArr = $match[0];
              
          if(count($chkArr) > 0)
          {
            foreach($chkArr as $res)
            {
              if(strlen($res) >= 4)
              {
                if($res > $current_year)
                {
                  $validate_flag = 0;
                }
              }
            }
          }

          if($validate_flag == '0')
          {
            $this->form_validation->set_message('fun_validation_check_year', "Please enter the year less than or equal to ".$current_year); 
            return false; // Invalid range 
          }
          else { /* echo 'valid'; */ return true; }
        }
        else 
        {
          /* echo 'empty result'; */ return true;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK IF ENTERED STRING HAS YEAR VALUE MORE THAN CURRENT YEAR ********/

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

    /******** START : FACULTY STATUS CHANGE ********/ 
    public function change_faculty_status() 
    {      
      $flag = "error";
      $response = '';
      if(isset($_POST) && $_POST['enc_id'] != "" && $this->login_user_type == 'agency')
      { 
        $enc_id = $this->security->xss_clean($this->input->post('enc_id')); 
        $status = $this->security->xss_clean($this->input->post('status')); 
        $status_num_val = $this->security->xss_clean($this->input->post('status_num_val')); 
        $return_status = '';
        $id = url_decode($enc_id);        
        $this->db->where("faculty_id",$id);
        $faculty_data = $this->master_model->getRecords('iibfbcbf_faculty_master', array('is_deleted' => '0'), 'faculty_id,status, created_by_type');
        
        if(count($faculty_data) > 0 && $this->session->userdata('IIBF_BCBF_USER_TYPE') == 'agency' && $faculty_data[0]['created_by_type'] == '1')
        {
          if($faculty_data[0]['status'] == $status_num_val)
          {
            $agencyName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_centre_id, 'agency');

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
                $this->Iibf_bcbf_model->insert_common_log('Faculty : Deactivated', 'iibfbcbf_faculty_master', $this->db->last_query(), $id,'faculty_action','The faculty has been successfully deactivated by the agency '.$agencyName['disp_name'].' Reason : '.$reason_for_deactivate, json_encode($update_data));
              }
              else
              {
                //$this->session->set_flashdata('faculty_status_error',"Please enter the reason for deactivating the faculty.");
                $this->session->set_flashdata('faculty_status_error',form_error('reason_for_deactivate'));
              }
                           
            }
            else if($status == 'Activate')
            { 
              $return_status = 1;
              $update_data["status"] = 1;
              $response = 'The faculty has been successfully Activated.';
              $this->session->set_flashdata('faculty_status_success',$response);
              $this->Iibf_bcbf_model->insert_common_log('Faculty : Activated', 'iibfbcbf_faculty_master', $this->db->last_query(), $id,'faculty_action','The faculty has been successfully activated by the agency '.$agencyName['disp_name'], json_encode($update_data));             
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
        else
        {
          $this->session->set_flashdata('faculty_status_error',"You do not have permission to change the Faculty status");
        } 
      }
      else
      {
        $this->session->set_flashdata('faculty_status_error',"Invalid Request");
      } 

      $result['flag'] = $flag;
      $result['response'] = $response;
      $result['status'] = $return_status;
      echo json_encode($result);  
    } 
    /******** START : FACULTY STATUS CHANGE ********/
 } ?>  