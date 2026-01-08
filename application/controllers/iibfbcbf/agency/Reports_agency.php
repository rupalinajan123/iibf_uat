<?php

/********************************************************************************************************************
 ** Description: Controller for BCBF Reports Master
 ** Created BY: Sagar Matale On 25-10-2024
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Reports_agency extends CI_Controller
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

    if ($this->login_user_type != 'agency')
    {
      $this->login_user_type = 'invalid';
    }
    $this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

    if ($this->login_user_type != 'agency')
    {
      $this->session->set_flashdata('error', 'You do not have permission to access the reports');
      redirect(site_url('iibfbcbf/agency/dashboard_agency'));
    }
    else if ($this->login_user_type == 'agency')
    {
      $logged_in_agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_admin_id), "agency_id, allow_exam_codes, allow_exam_types");
      if($logged_in_agency_data[0]['allow_exam_types'] == 'CSC')
      {
        $this->session->set_flashdata('error', 'You do not have permission to access the reports');
        redirect(site_url('iibfbcbf/agency/dashboard_agency'));
      }
    }

    $this->training_schedule_file_path = 'uploads/iibfbcbf/training_schedule';

    $this->buffer_days_after_training_end_date = '0';
    $this->buffer_days_after_candidate_add_date = '290';

  }

  public function index($report_type = '')
  {
    $sub_act_id = $tbl_gateway = '';
    if ($report_type == 'billdesk')
    {
      $sub_act_id = "Billdesk Transaction Report";
      $tbl_gateway = '2';
    }
    else if ($report_type == 'csc')
    {
      $sub_act_id = "CSC Transaction Report";
      $tbl_gateway = '3';
    }
    else if ($report_type == 'neft')
    {
      $sub_act_id = "NEFT Transaction Report";
      $tbl_gateway = '1';
    }

    $data['act_id'] = "Reports";
    $data['sub_act_id'] = $sub_act_id;
    $data['page_title'] = 'IIBF - BCBF ' . $sub_act_id;
    $data['agency_data'] = $data['agency_centre_data'] = array();
    $data['report_type'] = $report_type;
    $data['tbl_gateway'] = $tbl_gateway;

    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of previous month")); 
    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of April last year"));
    //$data['s_to_date'] = date("Y-m-d", strtotime("last day of previous month"));

    if(date('Y-m-d') > date("Y").'-04-01') { $data['s_from_date'] = date("Y").'-04-01'; }
    else { $data['s_from_date'] = date("Y", strtotime("-1 Year")).'-04-01'; }
    
    $data['s_to_date'] = date("Y-m-d");

    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');
    $this->load->view('iibfbcbf/admin/transaction_report_admin', $data);
  }

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE SUCCESS BILLDESK DATA ********/
  public function get_success_failure_billdesk_or_neft_data_ajax()
  {
    $tbl_gateway = trim($this->security->xss_clean($this->input->post('tbl_gateway')));
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $chk_status_str = '1';
    if ($tbl_gateway == '2' || $tbl_gateway == '3')
    {
      $chk_status_str = '0,1,2,5';
    } //BILLDESK  OR CSC  
    if ($tbl_gateway == '1')
    {
      $chk_status_str = '1,4,3';
    } //NEFT RTGS   

    $table = 'iibfbcbf_payment_transaction pt';     

    $column_order = array('pt.id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'pt.description', 'pt.transaction_details', 'DATE_FORMAT(pt.updated_on, "%Y-%m-%d") AS ApproveRejectDate'); //SET COLUMNS FOR SORT

    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))'); //SET COLUMN FOR SEARCH

    if ($tbl_gateway == '2' || $tbl_gateway == '3')
    {  
      $column_order = array('pt.id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'bc_cand.training_id', 'bc_cand.regnumber', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), "")) AS DispName', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'mem_ex.exam_date', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'pt.description', 'pt.transaction_details'); //SET COLUMNS FOR SORT

      $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'bc_cand.training_id', 'bc_cand.regnumber', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), ""))', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'mem_ex.exam_date', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))'); //SET COLUMN FOR SEARCH
    }
    
    $order = array('pt.id' => 'DESC'); // DEFAULT ORDER

    $WhereForTotal = "WHERE pt.gateway = '" . $tbl_gateway . "' AND pt.status IN (" . $chk_status_str . ") "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE pt.gateway = '" . $tbl_gateway . "' AND pt.status IN (" . $chk_status_str . ") ";

    if ($_POST['search']['value']) // DATATABLE SEARCH
    {
      $Where .= " AND (";
      for ($i = 0; $i < count($column_search); $i++)
      {
        $Where .= $column_search[$i] . " LIKE '%" . (custom_safe_string($_POST['search']['value'])) . "%' ESCAPE '!' OR ";
      }
      $Where = substr_replace($Where, "", -3);
      $Where .= ')';
    }

    if ($form_action == 'export' || $form_action == 'export_neft_transaction')
    {
      if(isset($_POST['tbl_search_value']) && $_POST['tbl_search_value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['tbl_search_value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace($Where, "", -3);
        $Where .= ')';
      }
    }

    //CUSTOM SEARCH
    $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
    if ($s_agency != "")
    {
      $Where .= " AND pt.payment_done_by_agency_id = '" . $s_agency . "'";
    }

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if ($s_centre != "")
    {
      $Where .= " AND pt.payment_done_by_centre_id = '" . $s_centre . "'";
    }

    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "" && ($tbl_gateway == '2' || $tbl_gateway == '3'))
    {
      $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
    }

    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

    if ($s_from_date != "" && $s_to_date != "")
    {
      $Where .= " AND (DATE(pt.date) >= '" . $s_from_date . "' AND DATE(pt.date) <= '" . $s_to_date . "')";
    }
    else if ($s_from_date != "")
    {
      $Where .= " AND DATE(pt.date) >= '" . $s_from_date . "'";
    }
    else if ($s_to_date != "")
    {
      $Where .= " AND DATE(pt.date) <= '" . $s_to_date . "'";
    }

    $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
    if ($s_payment_status != "")
    {
      $Where .= " AND pt.status = '" . $s_payment_status . "'";
    }

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode("AS", $column_order[$_POST['order']['0']['column']]);
      $Order = "ORDER BY " . $explode_arr[0] . " " . $_POST['order']['0']['dir'];
    }
    else if (isset($order))
    {
      $Order = "ORDER BY " . key($order) . " " . $order[key($order)];
    }

    $Limit = "";
    if ($_POST['length'] != '-1' && $form_action != 'export' && $form_action != 'export_neft_transaction')
    {
      $Limit = "LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
    } // DATATABLE LIMIT	

    $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = pt.agency_id";
    if ($tbl_gateway == '2' || $tbl_gateway == '3')
    {
      $join_qry .= " LEFT JOIN iibfbcbf_member_exam mem_ex ON mem_ex.member_exam_id = pt.exam_ids AND pt.gateway != '1'";
      $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc_cand ON bc_cand.candidate_id = mem_ex.candidate_id";
      $join_qry .= " LEFT JOIN iibfbcbf_agency_centre_batch acb ON acb.batch_id = bc_cand.batch_id";
    }
    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $WhereForTotal);
    $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $Where);

    if ($form_action == 'export' || $form_action == 'export_neft_transaction')
    {
      // Excel file name for download 
      if ($tbl_gateway == "1") //NEFT/RTGS
      {
        $fileName = "neft_" . date('Y-m-d') . ".xls";

        if ($s_payment_status == '1')
        {
          $fileName = "neft_success_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '4')
        {
          $fileName = "neft_cancelled_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '3')
        {
          $fileName = "neft_applied_" . date('Y-m-d') . ".xls";
        }
      }
      else if ($tbl_gateway == "2") //BILLDESK
      {
        $fileName = "billdesk_" . date('Y-m-d') . ".xls";

        if ($s_payment_status == '1')
        {
          $fileName = "billdesk_success_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '0')
        {
          $fileName = "billdesk_fail_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '2')
        {
          $fileName = "billdesk_pending_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '5')
        {
          $fileName = "billdesk_refund_" . date('Y-m-d') . ".xls";
        }
      }
      else if ($tbl_gateway == "3") //CSC
      {
        $fileName = "csc_" . date('Y-m-d') . ".xls";

        if ($s_payment_status == '1')
        {
          $fileName = "csc_success_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '0')
        {
          $fileName = "csc_fail_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '2')
        {
          $fileName = "csc_pending_" . date('Y-m-d') . ".xls";
        }
        else if ($s_payment_status == '5')
        {
          $fileName = "csc_refund_" . date('Y-m-d') . ".xls";
        }
      }

      $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Transaction No.', 'Receipt No.', 'Amount', 'Date', 'Status', 'Description', 'Transaction Details'); // Column names 
      if ($tbl_gateway == '2' || $tbl_gateway == '3')
      {
        $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Training ID', 'Registration Number', 'Candidate Name', 'Training Batch', 'Mobile', 'Email', 'Transaction No.', 'Receipt No.', 'Amount', 'Date', 'Exam Date', 'Status', 'Description', 'Transaction Details'); // Column names  
      }

      $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row 

      if($form_action == 'export_neft_transaction')
      {
          //$fields = array('','','','','','','','','','','','','','','','','',''); // Column names
          $excelData = ""; // Display column names as first row 
      }  
       
      
    }

    $data = array();
    $no = $_POST['start'];
    $neft_sr_no = 1000;

    foreach ($Rows as $Res)
    {
      $no++;
      $neft_sr_no++;
      $row = $row_neft = array();

      $row[] = $no;
      $row[] = $Res['agency_name'];
      $row[] = $Res['DispCentreName'];
      if($tbl_gateway == '2' || $tbl_gateway == '3')
      { 
        $row[] = $Res['training_id']; 
        $row[] = $Res['regnumber']; 
        $row[] = $Res['DispName']; 
        $row[] = $Res['batch_code']; 
        $row[] = $Res['mobile_no']; 
        $row[] = $Res['email_id']; 
      }
      $row[] = $Res['DispTransactionNo'];
      $row[] = $Res['receipt_no'];
      $row[] = $Res['amount'];
      $row[] = $Res['PaymentDate'];

      if($tbl_gateway == '2' || $tbl_gateway == '3')
      {
        $row[] = $Res['exam_date']; 
      }

      if ($form_action == 'export')
      {
        $row[] = $Res['DispPayStatus'];
        $row[] = $Res['description'];
        $row[] = $Res['transaction_details'];
      }
      else if($form_action == 'export_neft_transaction'){
        $row_neft[] = 'IIBF';
        $row_neft[] = 'Net Banking';
        $row_neft[] = 'NA';
        $row_neft[] = 'NEFT';
        $row_neft[] = 'SBI';
        $row_neft[] = (string)$Res['DispTransactionNo'];
        $row_neft[] = (string)'9624';
        $row_neft[] = (string)'9624';
        $row_neft[] = 'iibfexam';
        $row_neft[] = 'iibfexm';
        $row_neft[] = $neft_sr_no;
        $row_neft[] = date("d/m/Y", strtotime($Res['ApproveRejectDate']));
        $row_neft[] = (string)$Res['amount'];
        $row_neft[] = (string)'0';
        $row_neft[] = (string)'0';
        $row_neft[] = (string)'0';
        $row_neft[] = (string)'0';
        $row_neft[] = (string)$Res['amount'];
      }
      else
      {
        $row[] = '<span class="badge ' . show_payment_status($Res['status']) . '" style="width: 100px;white-space: normal;line-height: 15px; word-break: break-word;">' . $Res['DispPayStatus'] . '</span>'; //iibf_bcbf_helper.php
      }
      
      if ($form_action == 'export')
      {
        array_walk($row, 'filterData');
        $excelData .= implode("\t", array_values($row)) . "\n";
      }
      else if ($form_action == 'export_neft_transaction')
      {
        array_walk($row_neft, 'filterData');
        $excelData .= implode("\t", array_values($row_neft)) . "\n";
      }

      $data[] = $row;
    }

    if ($form_action == 'export' || $form_action == 'export_neft_transaction')
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
  }
  /******** END : SERVER SIDE DATATABLE CALL FOR GET THE SUCCESS BILLDESK DATA ********/

  /******** START : LOAD CENTERS ********/
  public function load_centre_data()
  {
    //$flag = "error";
    $flag = "success";
    $response = '';
    $html = '<option value="">Select Centre</option>';
    if (isset($_POST) && $_POST['s_agency'] != "")
    {
      $s_agency = $this->security->xss_clean($this->input->post('s_agency'));
      //$id = url_decode($enc_id);        
      $id = $s_agency;
      $this->db->where("cm.agency_id", $id);
      $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
      $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');
      //$agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('is_deleted' => '0'), 'centre_id,agency_id,status,centre_district,centre_city,centre_username');

      if (count($agency_centre_data) > 0)
      {
        foreach ($agency_centre_data as $res)
        {
          $html .= '<option value="' . $res['centre_id'] . '">' . $res['centre_name'] . " (" . $res['city_name'] . ")" . '</option>';;
        }
      }
    }
    $result['flag'] = $flag;
    $result['response'] = $html;
    echo json_encode($result);
  }
  /******** END : LOAD CENTERS ********/

    

  /******** START : BATCH COMMUNICATION ********/
  public function batch_communication()
  {   
    $data['act_id'] = "Reports Agency";
    $data['sub_act_id'] = "Batch Communication";
    $data['page_title'] = 'IIBF - BCBF Batch Communication';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    //$data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/agency/batch_communication_agency', $data);
  }
  /******** END : BATCH COMMUNICATION ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Batch MIS DATA ********/
  public function get_batch_communication_data_ajax()
  {
    echo $this->Iibf_bcbf_model->get_batch_communication_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE Batch Communication DATA ********/
 
   
  /******** START : EXAM DETAILS ********/
  public function exam_details()
  {   
    $data['act_id'] = "Reports Agency";
    $data['sub_act_id'] = "Exam Details Report";
    $data['page_title'] = 'IIBF - Exam Details Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    //$data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/agency/exam_details_report_agency', $data);
  }
  /******** END : EXAM DETAILS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE EXAM DETAILS DATA ********/
  public function get_exam_details_data_ajax()
  {  
    echo $this->Iibf_bcbf_model->get_exam_details_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE EXAM DETAILS DATA ********/

  /******** START : CANDIDATES ELIGIBLE FOR EXAMINATION DETAILS ********/
  public function eligible_candidate_for_examination()
  {   
    $data['act_id'] = "Reports Agency";
    $data['sub_act_id'] = "Candidate Eligible For Examination";
    $data['page_title'] = 'IIBF - Candidate Eligible For Examination';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    //$data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/agency/eligible_candidate_report_agency', $data);
  }
  /******** END : CANDIDATES ELIGIBLE FOR EXAMINATION DETAILS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES ELIGIBLE FOR EXAMINATION DATA ********/
  public function get_eligible_candidate_for_examination_data_ajax()
  {
     echo $this->Iibf_bcbf_model->get_eligible_candidate_for_examination_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES ELIGIBLE FOR EXAMINATION DATA ********/
 

  /*FOR QA Environment API Call*/
  function get_iibfbcbf_result_related_api_curl_qa($type = '', $exam_code = '', $exam_period = '', $part_no = '1', $member_no = '')
  {
    $res = $this->Iibf_bcbf_model->get_iibfbcbf_result_related_api($type, $exam_code, $exam_period, $part_no, $member_no);
    echo json_encode($res);
  }
  /*FOR QA Environment API Call*/

  /******** START : TRAINING BATCH DETAILS REPORT ********/
  public function training_details_batch()
  {   
    $data['act_id'] = "Reports Agency";
    $data['sub_act_id'] = "Training Batch Details Report";
    $data['page_title'] = 'IIBF - BCBF Training Batch Details Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    //$data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/agency/training_details_batch_report_agency', $data);
  }
  /******** END : TRAINING BATCH DETAILS REPORT ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Training BATCH Details Report DATA ********/
  public function get_training_batch_details_data_ajax()
  {
      echo $this->Iibf_bcbf_model->get_training_batch_details_common_data();
  } /******** END : SERVER SIDE DATATABLE CALL FOR GET THE Training BATCH Details Report DATA ********/

  /******** START : CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DETAILS ********/
  public function candidates_required_re_enroll()
  {   
    $data['act_id'] = "Reports Agency";
    $data['sub_act_id'] = "Candidates Required to re-enroll for Training";
    $data['page_title'] = 'IIBF - Candidates Required to re-enroll for Training';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/agency/candidates_required_re_enroll_report_agency', $data);
  }
  /******** END : CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DETAILS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DATA ********/
  public function get_candidates_to_re_enroll_for_training_data_ajax()
  {
    echo $this->Iibf_bcbf_model->get_candidates_to_re_enroll_for_training_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DATA ********/

   
}
