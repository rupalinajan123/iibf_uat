<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF CSC Exam Masters
  ** Created BY: Sagar Matale On 15-05-2024
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Masters_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      
      $this->login_agency_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      if($this->login_user_type != 'agency')
      {
        $this->session->set_flashdata('error','You do not have permission to access Master module');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
		}

    public function index() { redirect(site_url('iibfbcbf/agency/masters_agency/exam_centre_master_agency')); }
    
    /*############ START : EXAM MASTER ############*/

    /******** START : EXAM CENTRE MASTER DATA ********/
    public function exam_centre_master_agency()
    {   
      $data['act_id'] = "Exam Masters";
      $data['sub_act_id'] = "Exam Centre Master";
      $data['page_title'] = 'IIBF - BCBF Agency Exam Centre Master';
      
      $this->load->view('iibfbcbf/agency/exam_centre_master_agency', $data);
    }/******** END : EXAM CENTRE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM CENTRE MASTER DATA ********/
    public function get_exam_centre_master_data_ajax()
    {
      $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

      $table = 'iibfbcbf_exam_centre_master fm';
      
      $column_order = array('fm.id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'fm.exam_name', 'fm.exam_period', 'fm.centre_code', 'fm.centre_name', 'fm.state_code', 'fm.state_description', 'fm.exammode'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'fm.exam_name', 'fm.exam_period', 'fm.centre_code', 'fm.centre_name', 'fm.state_code', 'fm.state_description'); //SET COLUMN FOR SEARCH
      $order = array('fm.exam_name' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE fm.centre_delete = '0' AND fm.exam_name IN (1039,1040) AND em.exam_delete = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE fm.centre_delete = '0' AND fm.exam_name IN (1039,1040) AND em.exam_delete = '0' ";
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


    /******** START : EXAM VENUE MASTER DATA ********/
    public function exam_venue_master_agency()
    {   
      $data['act_id'] = "Exam Masters";
      $data['sub_act_id'] = "Exam Venue Master";
      $data['page_title'] = 'IIBF - BCBF Agency Exam Venue Master';
      
      $this->load->view('iibfbcbf/agency/exam_venue_master_agency', $data);
    }/******** END : EXAM VENUE MASTER DATA ********/
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM VENUE MASTER DATA ********/
    public function get_exam_venue_master_data_ajax()
    {
      $form_action = '';
      if(isset($_POST['form_action'])) { $form_action = trim($this->security->xss_clean($this->input->post('form_action'))); }

      $table = 'iibfbcbf_exam_venue_master fm';
      
      $column_order = array('fm.venue_master_id', 'em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", "")) AS DispExamType', 'em.exam_code', 'fm.centre_code', 'fm.session_capacity', 'fm.venue_code', 'fm.venue_name', 'fm.venue_addr1', 'fm.venue_addr2', 'fm.venue_addr3', 'fm.venue_addr4', 'fm.venue_addr5', 'fm.venue_pincode', 'fm.pwd_enabled', 'fm.vendor_code'); //SET COLUMNS FOR SORT
      
      $column_search = array('em.description', 'IF(em.exam_type=1,"Basic", IF(em.exam_type=2,"Advanced", ""))', 'em.exam_code', 'fm.centre_code', 'fm.session_capacity', 'fm.venue_code', 'fm.venue_name', 'fm.venue_addr1', 'fm.venue_addr2', 'fm.venue_addr3', 'fm.venue_addr4', 'fm.venue_addr5', 'fm.venue_pincode', 'fm.pwd_enabled', 'fm.vendor_code'); //SET COLUMN FOR SEARCH
      $order = array('em.exam_code' => 'ASC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE em.exam_code IN (1039,1040) AND em.exam_delete = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE em.exam_code IN (1039,1040) AND em.exam_delete = '0' ";
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

        $fields = array('Sr. No.', 'Exam Name', 'Exam Type', 'Exam Code', 'Centre Code', 'Session Capacity', 'Venue Code', 'Venue Name', 'Venue Address1', 'Venue Address2', 'Venue Address3', 'Venue Address4', 'Venue Address5', 'Venue Pincode', 'Password Enabled', 'Vendor Code'); // Column names 
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
        $row[] = $Res['centre_code'];
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
    public function csc_exam_date_master_agency()
    {   
      $data['act_id'] = "Exam Masters";
      $data['sub_act_id'] = "CSC Exam Date Master";
      $data['page_title'] = 'IIBF - BCBF Agency CSC Exam Date Master';
      
      $this->load->view('iibfbcbf/agency/csc_exam_date_master_agency', $data);
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
 } ?>  