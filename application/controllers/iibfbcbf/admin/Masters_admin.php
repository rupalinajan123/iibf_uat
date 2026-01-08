<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF All Exam Masters
  ** Created BY: Sagar Matale On 24-04-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Masters_admin extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'admin') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'admin')
      {
        $this->session->set_flashdata('error','You do not have permission to access Master module');
        redirect(site_url('iibfbcbf/admin/dashboard_agency'));
      }
		}

    public function index() { redirect(site_url('iibfbcbf/admin/masters_admin/exam_master_admin')); }
    
    /*############ START : EXAM MASTER ############*/

    /******** START : EXAM MASTER DATA ********/
    public function exam_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Master';
      
      $this->load->view('iibfbcbf/admin/exam_master_admin', $data);
    }/******** END : EXAM MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MASTER DATA ********/
    public function get_exam_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_master em';
      
      $column_order = array('em.id', 'em.description', 'em.exam_code', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'em.exam_code', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))'); //SET COLUMN FOR SEARCH
      $order = array('em.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE em.exam_delete = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE em.exam_delete = '0'	";
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
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Code', 'Exam Type'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['DispExamType'];
        
        //$btn_str = ' <div class="text-center no_wrap"> ';

        //$btn_str .= ' <a href="'.site_url('iibfbcbf/admin/masters_admin/exam_master_details/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="View Details"><i class="fa fa-eye" aria-hidden="true"></i></a> ';
        
        //$btn_str .= '<a href="'.site_url('iibfbcbf/admin/masters_admin/add_exam_master/'.url_encode($Res['id'])).'" class="btn btn-primary btn-xs" title="Edit"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> ';
        
        //$btn_str .= ' </div>';
        //$row[] = $btn_str;

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MASTER DATA ********/

    /******** START : UPDATE EXAM MASTER DATA ********/
    public function add_exam_master($enc_exam_id=0)
    { exit;
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Master";    
      
      //START : FIND OUT THE CURRENT MODE AND GET THE EXAM DATA
      if($enc_exam_id == '0') 
      { 
        redirect(site_url('iibfbcbf/admin/masters_admin/exam_master_admin'));
      }
      else
      {
        $exam_id = url_decode($enc_exam_id);
        
        $data['form_data'] = $form_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.id' => $exam_id), "em.*, IF(em.exam_type=1,'Basic', IF(em.exam_type=2,'Advanced', '')) AS DispExamType");        
        if(count($form_data) == 0) { redirect(site_url('iibfbcbf/admin/masters_admin/exam_master_admin')); }
        
        $data['mode'] = $mode = "Update";
      }//END : FIND OUT THE CURRENT MODE AND GET THE AGENCY DATA

      $data['page_title'] = 'IIBF - '.$mode.' Exam Master';
      $data['enc_exam_id'] = $enc_exam_id;

      if(isset($_POST) && count($_POST) > 0)
      { 
        $this->form_validation->set_rules('description', 'exam name', 'trim|required|callback_fun_restrict_input[validCustomInput]|max_length[90]|xss_clean', array('required'=>"Please enter the %s"));     
        //$this->form_validation->set_rules('xxx', 'xxx', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        
        if($this->form_validation->run())
        {
          $posted_arr = json_encode($_POST);
          $dispName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_admin_id, 'admin');

          $add_data['description'] = $this->input->post('description');
          
          /* if($mode == "Add") 
          {
            $add_data['is_active'] = '1';
            $add_data['agency_password'] = $this->Iibf_bcbf_model->password_encryption($this->input->post('agency_password'));
            $add_data['created_on'] = date("Y-m-d H:i:s");
            $add_data['created_by'] = $this->login_admin_id;
            
            $add_data['allow_exam_codes'] = '1037,1038';
            $this->master_model->insertRecord('iibfbcbf_agency_master  ',$add_data);
            $agency_id = $this->db->insert_id();

            $total_record_qry = $this->db->query('SELECT am.agency_id FROM iibfbcbf_agency_master am WHERE am.agency_id <= "'.$agency_id.'"');
            $get_total_record = $total_record_qry->num_rows();
            
            $up_data['agency_code'] = 1000 + $get_total_record;            
            $this->master_model->updateRecord('iibfbcbf_agency_master', $up_data, array('agency_id'=>$agency_id));
            
            $this->Iibf_bcbf_model->insert_common_log('Admin : Agency Added', 'iibfbcbf_agency_master', $this->db->last_query(), $agency_id,'agency_action','The agency has successfully added by the admin '.$dispName['disp_name'], $posted_arr); 
            
            $this->session->set_flashdata('success','Agency record added successfully');
          }
          else */ if($mode == "Update")
          {
            $add_data['modified_on'] = date("Y-m-d H:i:s");
            $add_data['modified_by'] = $this->login_admin_id;            
            
            if($this->master_model->updateRecord('iibfbcbf_exam_master', $add_data, array('id'=>$exam_id)))
            {                                      
              $this->Iibf_bcbf_model->insert_common_log('Admin : Exam Updated', 'iibfbcbf_exam_master', $this->db->last_query(), $exam_id,'exam_master_action','The exam has successfully updated by the admin '.$dispName['disp_name'], $posted_arr);
            
              $this->session->set_flashdata('success','Exam record updated successfully');              
            }
            else
            {
              $this->Iibf_bcbf_model->insert_common_log('Admin : Exam Updated', 'iibfbcbf_exam_master', $this->db->last_query(), $exam_id,'exam_master_action','Error occurred while updating the exam by the admin '.$dispName['disp_name'], $posted_arr);
            
              $this->session->set_flashdata('error','Error occurred. Please try again.');
            }
          }
          
          redirect(site_url('iibfbcbf/admin/masters_admin/exam_master_admin'));
        }
      }	
      
      $this->load->view('iibfbcbf/admin/add_exam_master', $data);
    }/******** END : UPDATE EXAM MASTER DATA ********/ 


    /******** START : EXAM ACTIVATION MASTER DATA ********/
    public function exam_activation_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Activation Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Activation Master';
      
      $this->load->view('iibfbcbf/admin/exam_activation_master_admin', $data);
    }/******** END : EXAM ACTIVATION MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM ACTIVATION MASTER DATA ********/
    public function get_exam_activation_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_activation_master eam';
      
      $column_order = array('eam.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'eam.exam_code', 'eam.exam_period', 'DATE_FORMAT(CONCAT(eam.exam_from_date, " ", eam.exam_from_time), "%Y-%m-%d %H:%i") AS ExamStart', 'DATE_FORMAT(CONCAT(eam.exam_to_date, " ", eam.exam_to_time), "%Y-%m-%d %H:%i") AS ExamEnd'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'eam.exam_code', 'eam.exam_period', 'DATE_FORMAT(CONCAT(eam.exam_from_date, " ", eam.exam_from_time), "%Y-%m-%d %H:%i")', 'DATE_FORMAT(CONCAT(eam.exam_to_date, " ", eam.exam_to_time), "%Y-%m-%d %H:%i")'); //SET COLUMN FOR SEARCH
      $order = array('eam.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE eam.exam_activation_delete = '0' AND em.exam_delete = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE eam.exam_activation_delete = '0' AND em.exam_delete = '0'  ";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND eam.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND eam.exam_period = '" . $s_exam_period . "'";
      }

      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

      if($s_from_date != "" && $s_to_date != "")
      { 
        $Where .= " AND (eam.exam_from_date >= '".$s_from_date."' OR eam.exam_to_date >= '".$s_from_date."') AND (eam.exam_from_date <= '".$s_to_date."' OR eam.exam_to_date <= '".$s_to_date."')"; 
      }
      else if($s_from_date != "") { $Where .= " AND (eam.exam_from_date >= '".$s_from_date."' OR eam.exam_to_date >= '".$s_from_date."')"; 
      }
      else if($s_to_date != "") { $Where .= " AND (eam.exam_from_date <= '".$s_to_date."' OR eam.exam_to_date <= '".$s_to_date."')"; } 
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = eam.exam_code";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_activation_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Start Date & Time', 'End Date & Time'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['ExamStart'];
        $row[] = $Res['ExamEnd'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM ACTIVATION MASTER DATA ********/
    

    /******** START : EXAM FEE MASTER DATA ********/
    public function exam_fee_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Fee Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Fee Master';
      
      $this->load->view('iibfbcbf/admin/exam_fee_master_admin', $data);
    }/******** END : EXAM FEE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM FEE MASTER DATA ********/
    public function get_exam_fee_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_fee_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_code', 'fm.exam_period', 'fm.part_no', 'fm.syllabus_code', 'fm.member_category', 'fm.group_code', 'fm.fee_amount', 'fm.sgst_amt', 'fm.cgst_amt', 'fm.igst_amt', 'fm.cs_tot', 'fm.igst_tot', 'fm.fr_date', 'fm.to_date', 'fm.exempt'); //SET COLUMNS FOR SORT
      
      $column_search = array('fm.exam_code', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_period', 'fm.part_no', 'fm.syllabus_code', 'fm.member_category', 'fm.group_code', 'fm.fee_amount', 'fm.sgst_amt', 'fm.cgst_amt', 'fm.igst_amt', 'fm.cs_tot', 'fm.igst_tot', 'fm.fr_date', 'fm.to_date', 'fm.exempt'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.fee_delete = '0' AND em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.fee_delete = '0' AND em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND fm.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = fm.exam_code";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_fee_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Part No', 'Syllabus Code', 'Member Category', 'Group Code', 'Fee Amount', 'SGST Amount', 'CGST Amount', 'IGST Amount', 'CS Total', 'IGST Total', 'From Date', 'To Date', 'Exempt'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['part_no'];
        $row[] = $Res['syllabus_code'];
        $row[] = $Res['member_category'];
        $row[] = $Res['group_code'];
        $row[] = $Res['fee_amount'];
        $row[] = $Res['sgst_amt'];
        $row[] = $Res['cgst_amt'];
        $row[] = $Res['igst_amt'];
        $row[] = $Res['cs_tot'];
        $row[] = $Res['igst_tot'];
        $row[] = $Res['fr_date'];
        $row[] = $Res['to_date'];
        $row[] = $Res['exempt'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM FEE MASTER DATA ********/
    

    /******** START : EXAM MISC MASTER DATA ********/
    public function exam_misc_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Misc Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Misc Master';
      
      $this->load->view('iibfbcbf/admin/exam_misc_master_admin', $data);
    }/******** END : EXAM MISC MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MISC MASTER DATA ********/
    public function get_exam_misc_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_misc_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_code', 'fm.exam_period', 'fm.exam_month', 'fm.trg_value'); //SET COLUMNS FOR SORT
      
      $column_search = array('fm.exam_code', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_period', 'fm.exam_month', 'fm.trg_value'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.misc_delete = '0' AND em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.misc_delete = '0' AND em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND fm.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = fm.exam_code";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_misc_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Exam Month', 'TRG Value'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['exam_month'];
        $row[] = $Res['trg_value'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MISC MASTER DATA ********/

    /******** START : EXAM CENTRE MASTER DATA ********/
    public function exam_centre_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Centre Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Centre Master';
      
      $this->load->view('iibfbcbf/admin/exam_centre_master_admin', $data);
    }/******** END : EXAM CENTRE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM CENTRE MASTER DATA ********/
    public function get_exam_centre_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_centre_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_name', 'fm.exam_period', 'fm.centre_code', 'fm.centre_name', 'fm.state_code', 'fm.state_description', 'fm.exammode'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_name', 'fm.exam_period', 'fm.centre_code', 'fm.centre_name', 'fm.state_code', 'fm.state_description'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_name' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.centre_delete = '0' AND em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.centre_delete = '0' AND em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND fm.exam_name = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = fm.exam_name";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_centre_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Centre Code', 'Centre Name', 'State Code', 'State Description', 'Mode'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_name'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['centre_code'];
        $row[] = $Res['centre_name'];
        $row[] = $Res['state_code'];
        $row[] = $Res['state_description'];
        $row[] = $Res['exammode'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM CENTRE MASTER DATA ********/


    /******** START : EXAM MEDIUM MASTER DATA ********/
    public function exam_medium_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Medium Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Medium Master';
      
      $this->load->view('iibfbcbf/admin/exam_medium_master_admin', $data);
    }/******** END : EXAM MEDIUM MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MEDIUM MASTER DATA ********/
    public function get_exam_medium_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_medium_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_code', 'fm.exam_period', 'fm.medium_code', 'fm.medium_description'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_code', 'fm.exam_period', 'fm.medium_code', 'fm.medium_description'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.medium_delete = '0' AND em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.medium_delete = '0' AND em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND fm.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = fm.exam_code";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_medium_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Medium Code', 'Medium Name'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['medium_code'];
        $row[] = $Res['medium_description'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM MEDIUM MASTER DATA ********/

    
    /******** START : EXAM SUBJECT MASTER DATA ********/
    public function exam_subject_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Subject Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Subject Master';
      
      $this->load->view('iibfbcbf/admin/exam_subject_master_admin', $data);
    }/******** END : EXAM SUBJECT MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM SUBJECT MASTER DATA ********/
    public function get_exam_subject_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_subject_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_code', 'fm.exam_period', 'fm.part_no', 'fm.syllabus_code', 'fm.subject_code', 'fm.subject_description', 'fm.group_code', 'fm.exam_date'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_code', 'fm.exam_period', 'fm.part_no', 'fm.syllabus_code', 'fm.subject_code', 'fm.subject_description', 'fm.group_code', 'fm.exam_date'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.subject_delete = '0' AND em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.subject_delete = '0' AND em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND fm.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON em.exam_code = fm.exam_code";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_subject_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Part No', 'Syllabus Code', 'Subject Code', 'Subject Description', 'Group Code', 'Exam Date'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['part_no'];
        $row[] = $Res['syllabus_code'];
        $row[] = $Res['subject_code'];
        $row[] = $Res['subject_description'];
        $row[] = $Res['group_code'];
        $row[] = $Res['exam_date'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM SUBJECT MASTER DATA ********/


    /******** START : EXAM VENUE MASTER DATA ********/
    public function exam_venue_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "Exam Venue Master";
      $data['page_title'] = 'IIBF - BCBF Admin Exam Venue Master';
      
      $this->load->view('iibfbcbf/admin/exam_venue_master_admin', $data);
    }/******** END : EXAM VENUE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM VENUE MASTER DATA ********/
    public function get_exam_venue_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_venue_master fm';
      
      $column_order = array('fm.venue_master_id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'em.exam_code', 'fm.exam_period', 'fm.exam_date', 'fm.centre_code', 'fm.session_time', 'fm.session_capacity', 'fm.venue_code', 'fm.venue_name', 'fm.venue_addr1', 'fm.venue_addr2', 'fm.venue_addr3', 'fm.venue_addr4', 'fm.venue_addr5', 'fm.venue_pincode', 'fm.pwd_enabled', 'fm.vendor_code'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'em.exam_code', 'fm.exam_period', 'fm.exam_date', 'fm.centre_code', 'fm.session_time', 'fm.session_capacity', 'fm.venue_code', 'fm.venue_name', 'fm.venue_addr1', 'fm.venue_addr2', 'fm.venue_addr3', 'fm.venue_addr4', 'fm.venue_addr5', 'fm.venue_pincode', 'fm.pwd_enabled', 'fm.vendor_code'); //SET COLUMN FOR SEARCH
      $order = array('em.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE  em.exam_delete = '0'  "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE em.exam_delete = '0' 	";
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
      $s_exam_type = trim($this->security->xss_clean($this->input->post('s_exam_type')));
      if ($s_exam_type != "")
      {
        $Where .= " AND em.exam_type = '" . $s_exam_type . "'";
      }

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if ($s_exam_code != "")
      {
        $Where .= " AND em.exam_code = '" . $s_exam_code . "'";
      }

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if ($s_exam_period != "")
      {
        $Where .= " AND fm.exam_period = '" . $s_exam_period . "'";
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " INNER JOIN iibfbcbf_exam_master em ON FIND_IN_SET (em.exam_code, fm.exam_codes)";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_exam_venue_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Exam Period', 'Exam Date', 'Centre Code', 'Session Time', 'Session Capacity', 'Venue Code', 'Venue Name', 'Venue Address1', 'Venue Address2', 'Venue Address3', 'Venue Address4', 'Venue Address5', 'Venue Pincode', 'Password Enabled', 'Vendor Code'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['description'];
        $row[] = $Res['DispExamType'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];
        $row[] = $Res['exam_date'];
        $row[] = $Res['centre_code'];
        $row[] = $Res['session_time'];
        $row[] = $Res['session_capacity'];
        $row[] = $Res['venue_code'];
        $row[] = $Res['venue_name'];
        $row[] = $Res['venue_addr1'];
        $row[] = $Res['venue_addr2'];
        $row[] = $Res['venue_addr3'];
        $row[] = $Res['venue_addr4'];
        $row[] = $Res['venue_addr5'];
        $row[] = $Res['venue_pincode'];
        $row[] = $Res['pwd_enabled'];
        $row[] = $Res['vendor_code'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM VENUE MASTER DATA ********/
    
    /******** START : EXAM CSC DATE MASTER DATA ********/
    public function csc_exam_date_master_admin()
    {   
      $data['act_id'] = "Masters";
      $data['sub_act_id'] = "CSC Exam Date Master";
      $data['page_title'] = 'IIBF - BCBF Admin CSC Exam Date Master';
      
      $this->load->view('iibfbcbf/admin/csc_exam_date_master_admin', $data);
    }/******** END : EXAM CSC DATE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM CSC DATE MASTER DATA ********/
    public function get_csc_exam_date_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_csc_exam_dates fm';
      
      $column_order = array('fm.id', 'fm.exam_date', 'fm.session_time'); //SET COLUMNS FOR SORT
      
      $column_search = array('fm.exam_date', 'fm.session_time'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_date' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.exam_date >= '".date('Y-m-d')."'"; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.exam_date >= '".date('Y-m-d')."'";
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
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	

      $join_qry = " ";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Master_csc_exam_date_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Exam Date', 'Time'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }

      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['exam_date'];
        $row[] = $Res['session_time'];

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
      //"Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM CSC DATE MASTER DATA ********/

    /*############ END : EXAM MASTER ############*/



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
 } ?>  