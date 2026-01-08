<?php

/********************************************************************************************************************
 ** Description: Controller for BCBF Reports Master
 ** Created BY: Sagar Matale On 01-04-2024
 ********************************************************************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
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

    if ($this->login_user_type != 'admin')
    {
      $this->login_user_type = 'invalid';
    }
    $this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

    if ($this->login_user_type != 'admin')
    {
      $this->session->set_flashdata('error', 'You do not have permission to access Batch MIS module');
      redirect(site_url('iibfbcbf/admin/dashboard_admin'));
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
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
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

  /******** START : BATCH SUMMARY OLD ********/
  public function batch_summary_old()
  {
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Batch Summary";
    $data['page_title'] = 'IIBF - BCBF Batch Summary';
    $data['agency_data'] = $data['agency_centre_data'] = array();

    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of previous month")); 
    $data['s_from_date'] = date("Y-m-d", strtotime("first day of April last year"));
    //$data['s_to_date'] = date("Y-m-d", strtotime("last day of previous month"));
    $data['s_to_date'] = date("Y-m-d");

    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');

    $data['inspector_data'] = $this->master_model->getRecords('iibfbcbf_inspector_master', array('is_deleted' => '0'), "inspector_id,inspector_name", array('inspector_name' => 'ASC'));

    $this->load->view('iibfbcbf/admin/batch_summary_old_admin', $data);
  }
  /******** END : BATCH SUMMARY OLD ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE BATCH SUMMARY DATA ********/
  public function get_batch_summary_old_data_ajax()
  {

    $table = 'iibfbcbf_agency_centre_batch acb';
    $GroupBy = " Group By acb.batch_id";

    $column_order = array('acb.batch_id', 'acb.batch_code', 'am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.created_on as acb_created_on', 'acb.updated_on as acb_updated_on', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.contact_person_name', 'CONCAT_WS(", ", fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name) AS faculty_names', 'COUNT(bi.inspection_id) AS inspection_count', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(bi.created_on, "%Y-%m-%d")) AS inspection_dates', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", SUM(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)),"0") AS total_inspection_time_minutes', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", AVG(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)), "0") AS average_inspection_time', 'GROUP_CONCAT(DISTINCT im.inspector_name) AS inspected_by', 'acb.total_candidates', 'COUNT(DISTINCT bc.candidate_id) AS total_registered_candidate', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END) AS total_hold_candidate', '(COUNT(DISTINCT bc.candidate_id) - (COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END))) AS total_eligible_candidate', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted")))))))) AS DispBatchStatus', '(
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
    ) AS total_excellent_counts'); //SET COLUMNS FOR SORT

    $column_search = array('acb.batch_code', 'am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.created_on', 'acb.updated_on', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.contact_person_name', 'CONCAT_WS(", ", fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name)', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))'); //SET COLUMN FOR SEARCH

    $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER


    $WhereForTotal = "WHERE acb.is_deleted = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE acb.is_deleted = '0' ";

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

    //CUSTOM SEARCH
    $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
    if ($s_agency != "")
    {
      $Where .= " AND acb.agency_id = '" . $s_agency . "'";
    }

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if ($s_centre != "")
    {
      $Where .= " AND acb.centre_id = '" . $s_centre . "'";
    }

    $s_inspector = trim($this->security->xss_clean($this->input->post('s_inspector')));
    if ($s_inspector != "")
    {
      $Where .= " AND acb.inspector_id = '" . $s_inspector . "'";
    }

    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

    if ($s_from_date != "")
    {
      $Where .= " AND (acb.batch_start_date >= '" . $s_from_date . "' OR acb.batch_end_date >= '" . $s_from_date . "')";
    }
    if ($s_to_date != "")
    {
      $Where .= " AND (acb.batch_start_date <= '" . $s_to_date . "' OR acb.batch_end_date <= '" . $s_to_date . "')";
    }

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
      $Order = "ORDER BY " . $explode_arr[0] . " " . $_POST['order']['0']['dir'];
    }
    else if (isset($order))
    {
      $Order = "ORDER BY " . key($order) . " " . $order[key($order)];
    }

    $Limit = "";
    if ($_POST['length'] != '-1')
    {
      $Limit = "LIMIT " . intval($_POST['start']) . ", " . intval($_POST['length']);
    } // DATATABLE LIMIT 

    $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm1 ON fm1.faculty_id = acb.first_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm2 ON fm2.faculty_id = acb.second_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm3 ON fm3.faculty_id = acb.third_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm4 ON fm4.faculty_id = acb.fourth_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bi ON bi.batch_id = acb.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_inspector_master im ON im.inspector_id = acb.inspector_id";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc ON bc.batch_id = acb.batch_id";

    $Where .= $GroupBy;
    $WhereForTotal .= $GroupBy;

    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $WhereForTotal);
    $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $Where);

    $data = array();
    $no = $_POST['start'];

    foreach ($Rows as $Res)
    {
      $no++;
      $row = array();

      $row[] = $no;
      $row[] = $Res['batch_code'];
      $row[] = $Res['agency_code'];
      $row[] = $Res['agency_name'];
      $row[] = $Res['DispCentreName'];
      $row[] = $Res['acb_created_on'];
      $row[] = $Res['acb_updated_on'];
      $row[] = $Res['batch_start_date'];
      $row[] = $Res['batch_end_date'];
      $row[] = $Res['contact_person_name'];
      $row[] = $Res['faculty_names'];
      $row[] = $Res['inspection_count'];
      $row[] = $Res['inspection_dates'];
      $row[] = $Res['total_inspection_time_minutes'];
      $row[] = $Res['average_inspection_time'];
      $row[] = $Res['inspected_by'];
      $row[] = $Res['total_candidates'];
      $row[] = $Res['total_registered_candidate'];
      $row[] = $Res['total_hold_candidate'];
      $row[] = $Res['total_eligible_candidate'];
      $row[] = $Res['DispBatchStatus'];
      $row[] = $Res['total_poor_counts'];
      $row[] = $Res['total_average_counts'];
      $row[] = $Res['total_good_counts'];
      $row[] = $Res['total_excellent_counts'];

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
  }
  /******** END : SERVER SIDE DATATABLE CALL FOR GET THE BATCH SUMMARY DATA ********/


  /******** START : EXPORT TO EXCEL FOR BATCH SUMMARY ********/
  public function export_to_excel_batch_summary()
  {
    $table = 'iibfbcbf_agency_centre_batch acb';
    $GroupBy = " Group By acb.batch_id";

    $column_order = array('acb.batch_id', 'acb.batch_code', 'am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.created_on as acb_created_on', 'acb.updated_on as acb_updated_on', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.contact_person_name', 'CONCAT_WS(", ", fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name) AS faculty_names', 'COUNT(bi.inspection_id) AS inspection_count', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(bi.created_on, "%Y-%m-%d")) AS inspection_dates', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", SUM(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)),"0") AS total_inspection_time_minutes', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", AVG(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)), "0") AS average_inspection_time', 'GROUP_CONCAT(DISTINCT im.inspector_name) AS inspected_by', 'acb.total_candidates', 'COUNT(DISTINCT bc.candidate_id) AS total_registered_candidate', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END) AS total_hold_candidate', '(COUNT(DISTINCT bc.candidate_id) - (COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END))) AS total_eligible_candidate', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted")))))))) AS DispBatchStatus', '(
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
    ) AS total_excellent_counts'); //SET COLUMNS FOR SORT

    $column_search = array('acb.batch_code', 'am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.created_on', 'acb.updated_on', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.contact_person_name', 'CONCAT_WS(", ", fm1.faculty_name, fm2.faculty_name, fm3.faculty_name, fm4.faculty_name)', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))'); //SET COLUMN FOR SEARCH

    $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER


    $WhereForTotal = "WHERE acb.is_deleted = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE acb.is_deleted = '0' ";

    if ($_POST['tbl_search_value']) // DATATABLE SEARCH
    {
      $Where .= " AND (";
      for ($i = 0; $i < count($column_search); $i++)
      {
        $Where .= $column_search[$i] . " LIKE '%" . (custom_safe_string($_POST['tbl_search_value'])) . "%' ESCAPE '!' OR ";
      }
      $Where = substr_replace($Where, "", -3);
      $Where .= ')';
    }

    //CUSTOM SEARCH
    $s_agency = trim($this->security->xss_clean($this->input->post('s_agency')));
    if ($s_agency != "")
    {
      $Where .= " AND acb.agency_id = '" . $s_agency . "'";
    }

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if ($s_centre != "")
    {
      $Where .= " AND acb.centre_id = '" . $s_centre . "'";
    }

    $s_inspector = trim($this->security->xss_clean($this->input->post('s_inspector')));
    if ($s_inspector != "")
    {
      $Where .= " AND acb.inspector_id = '" . $s_inspector . "'";
    }

    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

    if ($s_from_date != "")
    {
      $Where .= " AND (acb.batch_start_date >= '" . $s_from_date . "' OR acb.batch_end_date >= '" . $s_from_date . "')";
    }
    if ($s_to_date != "")
    {
      $Where .= " AND (acb.batch_start_date <= '" . $s_to_date . "' OR acb.batch_end_date <= '" . $s_to_date . "')";
    }

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
      $Order = "ORDER BY " . $explode_arr[0] . " " . $_POST['order']['0']['dir'];
    }
    else if (isset($order))
    {
      $Order = "ORDER BY " . key($order) . " " . $order[key($order)];
    }

    $Limit = "";
    //if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 

    $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm1 ON fm1.faculty_id = acb.first_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm2 ON fm2.faculty_id = acb.second_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm3 ON fm3.faculty_id = acb.third_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm4 ON fm4.faculty_id = acb.fourth_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bi ON bi.batch_id = acb.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_inspector_master im ON im.inspector_id = acb.inspector_id";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc ON bc.batch_id = acb.batch_id";

    $Where .= $GroupBy;
    $WhereForTotal .= $GroupBy;

    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();
    //echo $this->db->last_query();die;
    $i = 1;

    // Excel file name for download 
    $fileName = "batch_summary_" . date('Y-m-d') . ".xls";

    // Column names 
    $fields = array('Sr. No.', 'Batch Code', 'Agency Code', 'Agency Name', 'Centre Name', 'Batch Submit Date and Time', 'Batch Approve Date and Time', 'Batch From Date', 'Batch To Date', 'Batch Co-ordinator', 'Faculties', 'Total Number of Inspections', 'Dates of inspections', 'Total Inspection Time(Minutes)', 'Average Inspection Time(Minutes)', 'Inspected By', 'Total Candidates', 'Total Registered Candidates', 'Total Hold Candidates', 'Total Eligible Candidates', 'Batch Status', 'Assessment/Rating(Poor)', 'Assessment/Rating(Average)', 'Assessment/Rating(Good)', 'Assessment/Rating(Excellent)');

    // Display column names as first row 
    $excelData = implode("\t", array_values($fields)) . "\n";

    if ($Rows)
    {
      foreach ($Rows as $Res)
      {
        $row = array();
        $row[] = $i;
        $row[] = $Res['batch_code'];
        $row[] = $Res['agency_code'];
        $row[] = $Res['agency_name'];
        $row[] = $Res['DispCentreName'];
        $row[] = $Res['acb_created_on'];
        $row[] = $Res['acb_updated_on'];
        $row[] = $Res['batch_start_date'];
        $row[] = $Res['batch_end_date'];
        $row[] = $Res['contact_person_name'];
        $row[] = $Res['faculty_names'];
        $row[] = $Res['inspection_count'];
        $row[] = $Res['inspection_dates'];
        $row[] = $Res['total_inspection_time_minutes'];
        $row[] = $Res['average_inspection_time'];
        $row[] = $Res['inspected_by'];
        $row[] = $Res['total_candidates'];
        $row[] = $Res['total_registered_candidate'];
        $row[] = $Res['total_hold_candidate'];
        $row[] = $Res['total_eligible_candidate'];
        $row[] = $Res['DispBatchStatus'];
        $row[] = $Res['total_poor_counts'];
        $row[] = $Res['total_average_counts'];
        $row[] = $Res['total_good_counts'];
        $row[] = $Res['total_excellent_counts'];

        array_walk($row, 'filterData');
        $excelData .= implode("\t", array_values($row)) . "\n";

        $i++;
      }
    }
    else
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
  /******** END : EXPORT TO EXCEL FOR BATCH SUMMARY ********/

  /******** START : BATCH MIS ********/
  public function batch_mis()
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
  /******** END : BATCH MIS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Batch MIS DATA ********/
  public function get_batch_mis_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $table = 'iibfbcbf_agency_centre_batch acb';
    $GroupBy = " Group By acb.batch_id";
    
    $column_order = array('acb.batch_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", "")) AS DispBatchType', 'acb.batch_start_date', 'acb.batch_end_date',  'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled"))))))) AS DispBatchStatus', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "3" THEN bc.candidate_id END) AS total_release_candidate', 
    
    'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END) AS total_hold_candidate', 'im.inspector_name', 'COUNT(bi.inspection_id) AS inspection_count', 'IF(bi.inspection_start_time != "0000-00-00 00:00:00", SUM(TIMESTAMPDIFF(MINUTE, bi.inspection_start_time, bi.created_on)),"0") AS total_inspection_time_minutes', '(
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
  ) AS total_excellent_counts', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates',  'GROUP_CONCAT( DISTINCT im.inspector_name SEPARATOR ", ") AS all_assign_inspectors', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag','acb.agency_id'); //SET COLUMNS FOR SORT
    
    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", ""))', 'acb.batch_start_date', 'acb.batch_end_date', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled")))))))','im.inspector_name'); //SET COLUMN FOR SEARCH
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
    
    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "")
    {
      $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
    }

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
    if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
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
      $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Batch Type', 'Batch From Date', 'Batch To Date', 'Batch Status','No. of Release Candidates','No. of Hold Candidates', 'Inspector Name', 'Total Number of Inspections', 'Total Inspection Time(Minutes)', 'Inspection/Rating(Poor)', 'Inspection/Rating(Average)', 'Inspection/Rating(Good)', 'Inspection/Rating(Excellent)');  
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

  /******** START : BATCH COMMUNICATION ********/
  public function batch_communication()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Batch Communication";
    $data['page_title'] = 'IIBF - BCBF Batch Communication';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/batch_communication_admin', $data);
  }
  /******** END : BATCH COMMUNICATION ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Batch MIS DATA ********/
  public function get_batch_communication_data_ajax()
  {
    echo $this->Iibf_bcbf_model->get_batch_communication_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE Batch Communication DATA ********/

  /******** START : BATCH ACTION ********/
  public function batch_action()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Batch Action Report";
    $data['page_title'] = 'IIBF - BCBF Batch Action Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/batch_action_admin', $data);
  }
  /******** END : BATCH ACTION ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Batch ACTION DATA ********/
  public function get_batch_action_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $table = 'iibfbcbf_agency_batch_status_action bsa'; //
     
    $column_order = array('bsa.status_action_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'acb.batch_start_date', 'acb.batch_end_date', 'im.inspector_name', 'IF(bsa.action_by!="1", bsa.batch_status_reason, "") AS remark_by_agency', 'IF(bsa.batch_status=4, "Y", "N") AS Hold_lifted', 'IF(bsa.batch_status=7, "Y", "N") AS batch_cancelled', 'IF(bsa.action_by="1", bsa.batch_status_reason, "") AS remark_by_admin', 'DATE_FORMAT(bsa.created_on,"%Y-%m-%d %h:%i %p") AS action_date_time', 'IF(bsa.batch_status = 0, "In Review", IF(bsa.batch_status = 1, "Final Review", IF(bsa.batch_status = 2, "Batch Error", IF(bsa.batch_status = 3, "Go Ahead", IF(bsa.batch_status = 4, "Hold", IF(bsa.batch_status = 5, "Rejected", IF(bsa.batch_status = 6, "Re-Submitted", "Cancelled"))))))) AS DispBatchStatus'); //SET COLUMNS FOR SORT
    
    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advance", ""))', 'acb.batch_start_date', 'acb.batch_end_date'); //SET COLUMN FOR SEARCH
    $order = array('bsa.status_action_id' => 'DESC'); // DEFAULT ORDER    
     
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

    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "")
    {
      $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
    }
    
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
    if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
    else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
    
    $Limit = ""; 
    if ($_POST['length'] != '-1' && $form_action != 'export') 
    { 
      $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
    } // DATATABLE LIMIT  
    
    $join_qry = " LEFT JOIN iibfbcbf_agency_centre_batch acb ON acb.batch_id = bsa.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id"; 
    //$join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bi ON bi.batch_id = acb.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_inspector_master im ON im.inspector_id = acb.inspector_id";

    /*$Where .= $GroupBy;
    $WhereForTotal .= $GroupBy;*/
          
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
      $fileName = "Batch_Action_".date('Y-m-d').".xls";  
      // Column names 
      $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'From Date', 'To Date', 'Inspector Assigned', 'Reason For Putting On Hold / Cancellation From Agency', 'Hold Lifted? (Y/N)', 'Batch Cancelled? (Y/N)', 'Reason For Putting On Hold / Cancellation from  Admin', 'Action Date and Time', 'Batch Status');  
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
      $row[] = $Res['batch_start_date'];
      $row[] = $Res['batch_end_date'];
      $row[] = $Res['inspector_name'];
      //$row[] = $Res['batch_status_reason'];
      $row[] = $Res['remark_by_agency'];
      $row[] = $Res['Hold_lifted'];
      $row[] = $Res['batch_cancelled'];
      $row[] = $Res['remark_by_admin'];
      $row[] = $Res['action_date_time'];
      $row[] = $Res['DispBatchStatus'];
 
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
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE Batch ACTION DATA ********/

  /******** START : INSPECTOR REPORT ********/
  public function inspector_report()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Inspector Report";
    $data['page_title'] = 'IIBF - BCBF Inspector Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $data['inspector_data'] = $this->master_model->getRecords('iibfbcbf_inspector_master im', array('im.is_deleted'=>'0'), 'im.inspector_id, im.inspector_name, im.inspector_designation');

    $this->load->view('iibfbcbf/admin/inspector_report_admin', $data);
  }
  /******** END : INSPECTOR REPORT ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE INSPECTOR REPORT DATA ********/
  public function get_inspector_report_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $table = 'iibfbcbf_inspector_master im';
    $GroupBy = ' Group By im.inspector_id';
     
    $column_order = array('im.inspector_id', 'im.inspector_name', 'im.inspector_designation', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'COUNT(DISTINCT a1.batch_id) AS BasicBatchAllotedCnt', '(SELECT COUNT(bc1.batch_id) FROM iibfbcbf_batch_inspection bc1 INNER JOIN iibfbcbf_agency_centre_batch acb1 ON acb1.batch_id = bc1.batch_id AND acb1.batch_type = "1" WHERE bc1.inspector_id = im.inspector_id) AS BasicReportSubmittedCnt', 'COUNT(DISTINCT a3.batch_id) AS BasicBatchNotSubmittedCnt', 'COUNT(DISTINCT a5.batch_id) AS BasicBatchAwaitedCnt', 'COUNT(DISTINCT a2.batch_id) AS AdvancedBatchAllotedCnt', '(SELECT COUNT(bc2.batch_id) FROM iibfbcbf_batch_inspection bc2 INNER JOIN iibfbcbf_agency_centre_batch acb2 ON acb2.batch_id = bc2.batch_id AND acb2.batch_type = "2" WHERE bc2.inspector_id = im.inspector_id) AS AdvancedReportSubmittedCnt', 'COUNT(DISTINCT a4.batch_id) AS AdvancedBatchNotSubmittedCnt', 'COUNT(DISTINCT a6.batch_id) AS AdvancedBatchAwaitedCnt'); //SET COLUMNS FOR SORT
    
    $column_search = array('im.inspector_name', 'im.inspector_designation', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'COUNT(DISTINCT a1.batch_id)', '(SELECT COUNT(bc1.batch_id) FROM iibfbcbf_batch_inspection bc1 INNER JOIN iibfbcbf_agency_centre_batch acb1 ON acb1.batch_id = bc1.batch_id AND acb1.batch_type = "1" WHERE bc1.inspector_id = im.inspector_id)', 'COUNT(DISTINCT a3.batch_id)', 'COUNT(DISTINCT a5.batch_id)', 'COUNT(DISTINCT a2.batch_id)', '(SELECT COUNT(bc2.batch_id) FROM iibfbcbf_batch_inspection bc2 INNER JOIN iibfbcbf_agency_centre_batch acb2 ON acb2.batch_id = bc2.batch_id AND acb2.batch_type = "2" WHERE bc2.inspector_id = im.inspector_id)', 'COUNT(DISTINCT a4.batch_id)', 'COUNT(DISTINCT a6.batch_id)'); //SET COLUMN FOR SEARCH

    $order = array('im.inspector_id' => 'DESC'); // DEFAULT ORDER    
     
    $WhereForTotal = "WHERE im.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE im.is_deleted = 0 ";
    
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
    if($s_agency != "") { $Where .= " AND acb_main.agency_id = '".$s_agency."'"; } 

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if($s_centre != "") { $Where .= " AND acb_main.centre_id = '".$s_centre."'"; }

    $s_inspector = trim($this->security->xss_clean($this->input->post('s_inspector')));
    if($s_inspector != "") { $Where .= " AND acb_main.inspector_id = '".$s_inspector."'"; } 
    
    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
    
    if($s_from_date != "" && $s_to_date != "")
    { 
      $Where .= " AND (acb_main.batch_start_date >= '".$s_from_date."' OR acb_main.batch_end_date >= '".$s_from_date."') AND (acb_main.batch_start_date <= '".$s_to_date."' OR acb_main.batch_end_date <= '".$s_to_date."')"; 
    }else if($s_from_date != "") { $Where .= " AND (acb_main.batch_start_date >= '".$s_from_date."' OR acb_main.batch_end_date >= '".$s_from_date."')"; 
    }else if($s_to_date != "") { $Where .= " AND (acb_main.batch_start_date <= '".$s_to_date."' OR acb_main.batch_end_date <= '".$s_to_date."')"; 
  } 

    $s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
    if($s_batch_code != "") { $Where .= " AND acb_main.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; 
    } //iibfbcbf/iibf_bcbf_helper.php
    
    $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
    if($s_batch_status != "") { $Where .= " AND acb_main.batch_status = '".$s_batch_status."'"; 
  }      

    $Order = ""; //DATATABLE SORT
    if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
    else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
    
    $Limit = ""; 
    if ($_POST['length'] != '-1' && $form_action != 'export') 
    { 
      $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
    } // DATATABLE LIMIT  
    
    $join_qry = " LEFT JOIN (

      SELECT batch_id, inspector_id FROM iibfbcbf_agency_centre_batch WHERE is_deleted = '0' AND batch_type = '1' 

      UNION 

      SELECT in_bc1.batch_id, in_bc1.inspector_id FROM iibfbcbf_batch_inspection in_bc1 INNER JOIN iibfbcbf_agency_centre_batch in_acb1 ON in_acb1.batch_id = in_bc1.batch_id AND in_acb1.batch_type = '1'

    ) AS a1 ON a1.inspector_id = im.inspector_id

  LEFT JOIN 

    (

      SELECT batch_id, inspector_id FROM iibfbcbf_agency_centre_batch WHERE is_deleted = '0' AND batch_type = '2' 

      UNION 

      SELECT in_bc2.batch_id, in_bc2.inspector_id FROM iibfbcbf_batch_inspection in_bc2 INNER JOIN iibfbcbf_agency_centre_batch in_acb2 ON in_acb2.batch_id = in_bc2.batch_id AND in_acb2.batch_type = '2'

    ) AS a2 ON a2.inspector_id = im.inspector_id

  LEFT JOIN (

    SELECT acb11.centre_id, acb11.batch_id, acb11.batch_end_date, acb11.inspector_id, (SELECT COUNT(batch_id) FROM iibfbcbf_batch_inspection WHERE batch_id = acb11.batch_id AND inspector_id = acb11.inspector_id) AS InspectionCnt

    FROM iibfbcbf_agency_centre_batch acb11 WHERE acb11.batch_type = '1' AND acb11.batch_end_date < CURDATE() HAVING InspectionCnt = 0

  ) AS a3 ON a3.inspector_id = im.inspector_id

  LEFT JOIN (

    SELECT acb12.batch_id, acb12.batch_end_date, acb12.inspector_id, (SELECT COUNT(batch_id) FROM iibfbcbf_batch_inspection WHERE batch_id = acb12.batch_id AND inspector_id = acb12.inspector_id) AS InspectionCnt

    FROM iibfbcbf_agency_centre_batch acb12 WHERE acb12.batch_type = '2' AND acb12.batch_end_date < CURDATE() HAVING InspectionCnt = 0

  ) AS a4 ON a4.inspector_id = im.inspector_id

  LEFT JOIN (

    SELECT acb13.batch_id, acb13.batch_end_date, acb13.inspector_id, (SELECT COUNT(batch_id) FROM iibfbcbf_batch_inspection WHERE batch_id = acb13.batch_id AND inspector_id = acb13.inspector_id) AS InspectionCnt

    FROM iibfbcbf_agency_centre_batch acb13 WHERE acb13.batch_type = '1' AND acb13.batch_end_date >= CURDATE() HAVING InspectionCnt = 0

  ) AS a5 ON a5.inspector_id = im.inspector_id

  LEFT JOIN (

    SELECT acb14.batch_id, acb14.batch_end_date, acb14.inspector_id, (SELECT COUNT(batch_id) FROM iibfbcbf_batch_inspection WHERE batch_id = acb14.batch_id AND inspector_id = acb14.inspector_id) AS InspectionCnt

    FROM iibfbcbf_agency_centre_batch acb14 WHERE acb14.batch_type = '2' AND acb14.batch_end_date >= CURDATE() HAVING InspectionCnt = 0

  ) AS a6 ON a6.inspector_id = im.inspector_id";


    $join_qry .= " LEFT JOIN iibfbcbf_agency_centre_batch acb_main ON acb_main.inspector_id = im.inspector_id";
    $join_qry .= " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb_main.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";  

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
      $fileName = "Inspector_Report_".date('Y-m-d').".xls";  
      // Column names 
      $fields = array('Sr. No.', 'Name of Inspector', 'Designation', 'Assigned Centre(s)', 'No. of Basic Batches Allotted', 'No. of Reports Submitted', 'No. of Reports not submitted', 'No. of Reports Awaited', 'No. of Advanced Batches Allotted', 'No. of Reports Submitted', 'No. of Reports not Submitted', 'No. of Reports Awaited');  
      // Display column names as first row 
      $excelData = implode("\t", array_values($fields)) . "\n";  
    }

    foreach ($Rows as $Res) 
    {
      $no++;
      $row = array();
       
      $row[] = $no;
      $row[] = $Res['inspector_name'];
      $row[] = $Res['inspector_designation'];
      $row[] = $Res['DispCentreName'];
      $row[] = $Res['BasicBatchAllotedCnt'];
      $row[] = $Res['BasicReportSubmittedCnt'];
      $row[] = $Res['BasicBatchNotSubmittedCnt']; 
      $row[] = $Res['BasicBatchAwaitedCnt'];
      $row[] = $Res['AdvancedBatchAllotedCnt'];
      $row[] = $Res['AdvancedReportSubmittedCnt'];
      $row[] = $Res['AdvancedBatchNotSubmittedCnt'];
      $row[] = $Res['AdvancedBatchAwaitedCnt'];
 
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
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE INSPECTOR REPORT DATA ********/

  /******** START : INSTITUTION REPORT ********/
  public function institution_report()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Institution Report";
    $data['page_title'] = 'IIBF - BCBF Institution Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/institution_report_admin', $data);
  }
  /******** END : INSTITUTION REPORT ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE INSTITUTION REPORT DATA ********/
  public function get_institution_report_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $table = 'iibfbcbf_centre_master cm';
    
    //$GroupBy = ' Group By acb_main.centre_id';
     
    $column_order = array('cm.centre_id', 'CONCAT(cm.centre_name, " - ", cm.centre_username) AS DispCentreName', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1") AS BasicBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "3" AND batch_end_date < CURDATE()) AS BasicCompletedBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "5") AS BasicRejectedBatchCount', '(Select count(DISTINCT absa.batch_id) FROM iibfbcbf_agency_centre_batch as acb_sub INNER JOIN iibfbcbf_agency_batch_status_action as absa ON absa.batch_id = acb_sub.batch_id WHERE acb_sub.centre_id = cm.centre_id AND acb_sub.batch_type = "1" AND absa.batch_status IN("4") AND acb_sub.batch_status != "4" GROUP BY acb_sub.batch_id) AS BasicPutOnHoldToUnholdBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "4") AS BasicStillOnHoldBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "7") AS BasicCancelledBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2") AS AdvancedBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "3" AND batch_end_date < CURDATE()) AS AdvancedCompletedBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "5") AS AdvancedRejectedBatchCount', '(Select count(DISTINCT absa.batch_id) FROM iibfbcbf_agency_centre_batch as acb_sub INNER JOIN iibfbcbf_agency_batch_status_action as absa ON absa.batch_id = acb_sub.batch_id WHERE acb_sub.centre_id = cm.centre_id AND acb_sub.batch_type = "2" AND absa.batch_status IN("4") AND acb_sub.batch_status != "4" GROUP BY acb_sub.batch_id) AS AdvancedPutOnHoldToUnholdBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "4") AS AdvancedStillOnHoldBatchCount', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "7") AS AdvancedCancelledBatchCount'); //SET COLUMNS FOR SORT
    
    $column_search = array('CONCAT(cm.centre_name, " - ", cm.centre_username)', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1")', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "3" AND batch_end_date < CURDATE())', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "5")', '(Select count(DISTINCT absa.batch_id) FROM iibfbcbf_agency_centre_batch as acb_sub INNER JOIN iibfbcbf_agency_batch_status_action as absa ON absa.batch_id = acb_sub.batch_id WHERE acb_sub.centre_id = cm.centre_id AND acb_sub.batch_type = "1" AND absa.batch_status IN("4") AND acb_sub.batch_status != "4" GROUP BY acb_sub.batch_id)', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "4")', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "1" AND batch_status = "7")', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2")', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "3" AND batch_end_date < CURDATE())', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "5")', '(Select count(DISTINCT absa.batch_id) FROM iibfbcbf_agency_centre_batch as acb_sub INNER JOIN iibfbcbf_agency_batch_status_action as absa ON absa.batch_id = acb_sub.batch_id WHERE acb_sub.centre_id = cm.centre_id AND acb_sub.batch_type = "2" AND absa.batch_status IN("4") AND acb_sub.batch_status != "4" GROUP BY acb_sub.batch_id)', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "4")', '(Select COUNT(batch_id) FROM iibfbcbf_agency_centre_batch WHERE centre_id = cm.centre_id AND batch_type = "2" AND batch_status = "7")'); //SET COLUMN FOR SEARCH

    $order = array('cm.centre_id' => 'DESC'); // DEFAULT ORDER    
     
    $WhereForTotal = "WHERE cm.is_deleted = 0 "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE cm.is_deleted = 0 ";
    
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
    if($s_agency != "") { $Where .= " AND cm.agency_id = '".$s_agency."'"; } 

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if($s_centre != "") { $Where .= " AND cm.centre_id = '".$s_centre."'"; } 
    
    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
    
    /*if($s_from_date != "" && $s_to_date != "")
    { 
      $Where .= " AND (acb_main.batch_start_date >= '".$s_from_date."' OR acb_main.batch_end_date >= '".$s_from_date."') AND (acb_main.batch_start_date <= '".$s_to_date."' OR acb_main.batch_end_date <= '".$s_to_date."')"; 
    }else if($s_from_date != "") { $Where .= " AND (acb_main.batch_start_date >= '".$s_from_date."' OR acb_main.batch_end_date >= '".$s_from_date."')"; 
    }else if($s_to_date != "") { $Where .= " AND (acb_main.batch_start_date <= '".$s_to_date."' OR acb_main.batch_end_date <= '".$s_to_date."')"; 
    }*/ 

    /*$s_batch_code = trim($this->security->xss_clean($this->input->post('s_batch_code')));
    if($s_batch_code != "") { $Where .= " AND acb_main.batch_code LIKE '%".custom_safe_string($s_batch_code)."%'"; 
    } //iibfbcbf/iibf_bcbf_helper.php 
    $s_batch_status = trim($this->security->xss_clean($this->input->post('s_batch_status')));
    if($s_batch_status != "") { $Where .= " AND acb_main.batch_status = '".$s_batch_status."'"; 
    }*/      

    $Order = ""; //DATATABLE SORT
    if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
    else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
    
    $Limit = ""; 
    if ($_POST['length'] != '-1' && $form_action != 'export') 
    { 
      $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
    } // DATATABLE LIMIT  
    
    $join_qry = "";
    //$join_qry = " LEFT JOIN iibfbcbf_agency_centre_batch acb_main ON acb_main.centre_id = cm.centre_id";
    //$join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";  

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
      $fileName = "Institution_Report_".date('Y-m-d').".xls";  
      // Column names 
      $fields = array('Sr. No.', 'Centre Name - Code', 'No. of Basic Batches Created', 'No. of Basic Batches Completed', 'No. of Basic Batches Rejected', 'No. of Basic Batches put on Hold (led to Unhold)', 'No. of Basic Batches still on Hold', 'No. of Basic Batches Cancelled', 'No. of Advanced Batches Created', 'No. of Advanced Batches Completed', 'No. of Advanced Batches Rejected', 'No. of Advanced Batches put on Hold (led to Unhold)', 'No. of Advanced Batches still on Hold', 'No. of Advanced Batches Cancelled');  
      // Display column names as first row 
      $excelData = implode("\t", array_values($fields)) . "\n";  
    }

    foreach ($Rows as $Res) 
    {
      $no++;
      $row = array();
       
      $row[] = $no;
      $row[] = $Res['DispCentreName'];
      $row[] = $Res['BasicBatchCount'];
      $row[] = $Res['BasicCompletedBatchCount'];
      $row[] = $Res['BasicRejectedBatchCount'];
      $row[] = ($Res['BasicPutOnHoldToUnholdBatchCount'] > 0 ? $Res['BasicPutOnHoldToUnholdBatchCount'] : 0);
      $row[] = $Res['BasicStillOnHoldBatchCount']; 
      $row[] = $Res['BasicCancelledBatchCount'];
      $row[] = $Res['AdvancedBatchCount'];
      $row[] = $Res['AdvancedCompletedBatchCount'];
      $row[] = $Res['AdvancedRejectedBatchCount'];
      $row[] = ($Res['AdvancedPutOnHoldToUnholdBatchCount'] > 0 ? $Res['AdvancedPutOnHoldToUnholdBatchCount'] : 0);
      $row[] = $Res['AdvancedStillOnHoldBatchCount'];
      $row[] = $Res['AdvancedCancelledBatchCount'];
 
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
    "Query" => $print_query, 
    "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE INSTITUTION REPORT DATA ********/

  /************ START : INDIVIDUAL REGISTRATION REPORT *************/
  public function individual_registration($report_type = '')
  { 
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Individual Registration Report";
    $data['page_title'] = 'IIBF - BCBF Individual Registration Report';
    $data['agency_data'] = $data['agency_centre_data'] = array();
    $data['report_type'] = $report_type;
    $data['tbl_gateway'] = 1;

    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of previous month")); 
    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of April last year"));
    //$data['s_to_date'] = date("Y-m-d", strtotime("last day of previous month"));

    if(date('Y-m-d') > date("Y").'-04-01') { $data['s_from_date'] = date("Y").'-04-01'; }
    else { $data['s_from_date'] = date("Y", strtotime("-1 Year")).'-04-01'; }
    
    $data['s_to_date'] = date("Y-m-d");

    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');
    $this->load->view('iibfbcbf/admin/individual_registration_report_admin', $data);
  }
  /************ END : INDIVIDUAL REGISTRATION REPORT *************/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE SUCCESS INDIVIDUAL REGISTRATION DATA ********/
  public function get_success_individual_registration_data_ajax()
  {
    $tbl_gateway = trim($this->security->xss_clean($this->input->post('tbl_gateway')));
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $chk_status_str = '1';
    /*if ($tbl_gateway == '2' || $tbl_gateway == '3')
    {
      $chk_status_str = '0,1,2,5';
    } //BILLDESK  OR CSC  
    if ($tbl_gateway == '1')
    {
      $chk_status_str = '1,4,3';
    } //NEFT RTGS*/   

    $table = 'iibfbcbf_payment_transaction pt';     
 
    $column_order = array('pt.id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), "")) AS DispName', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.description, pt.transaction_details) AS transaction_details', 'pt.exam_code', 'pt.exam_period', 'pt.bankcode', 'CONCAT(pt.bankcode," (",pt.paymode,")") AS BankName', '"1" AS pay_count', 'pt.amount', 'ei.tds_amt', 'ei.disc_amt', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i %p")) AS PaymentDate', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'pt.description'); //SET COLUMNS FOR SORT

    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), ""))', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))'); //SET COLUMN FOR SEARCH
   
    
    $order = array('pt.id' => 'DESC'); // DEFAULT ORDER

    $WhereForTotal = "WHERE pt.status IN (" . $chk_status_str . ") "; //pt.gateway = '" . $tbl_gateway . "' AND  //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE pt.status IN (" . $chk_status_str . ") "; //pt.gateway = '" . $tbl_gateway . "' AND 

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

    if ($form_action == 'export')
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
      $Where .= " AND pt.agency_id = '" . $s_agency . "'";
    }

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if ($s_centre != "")
    {
      $Where .= " AND pt.centre_id = '" . $s_centre . "'";
    }

    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "")
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

    $s_payment_mode = trim($this->security->xss_clean($this->input->post('s_payment_mode')));
    if($s_payment_mode != "") { $Where .= " AND pt.payment_mode = '".$s_payment_mode."'"; } 

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
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
    
    $join_qry .= " LEFT JOIN iibfbcbf_member_exam mem_ex ON FIND_IN_SET(mem_ex.member_exam_id, pt.exam_ids)";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc_cand ON bc_cand.candidate_id = mem_ex.candidate_id";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_centre_batch acb ON acb.batch_id = bc_cand.batch_id";
    $join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.app_type = 'BC'";
     
    
    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $WhereForTotal);
    $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $Where);

    if ($form_action == 'export')
    {
      $fileName = "individual_registration_" . date('Y-m-d') . ".xls"; 

      $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Training ID', 'Candidate Name', 'Training Batch', 'Mobile', 'Email', 'Transaction Details', 'Exam Code', 'Exam Period', 'Bank ID', 'Bank Name', 'Pay Count', 'Amount', 'Discount Amount', 'TDS Amount', 'Paid Date', 'NEFT / UTR No. / Transaction No.'); // Column names  
       
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
      
      $row[] = $Res['training_id']; 
      $row[] = $Res['DispName']; 
      $row[] = $Res['batch_code']; 
      $row[] = $Res['mobile_no']; 
      $row[] = $Res['email_id']; 
       
      $row[] = $Res['transaction_details'];
      $row[] = $Res['exam_code'];
      $row[] = $Res['exam_period'];
      $row[] = $Res['bankcode'];
      $row[] = $Res['BankName'];
      $row[] = $Res['pay_count'];
      $row[] = $Res['amount'];
      $row[] = $Res['disc_amt'];
      $row[] = $Res['tds_amt'];
      $row[] = $Res['PaymentDate'];
      $row[] = $Res['DispTransactionNo'];

      if ($form_action == 'export')
      {
        //$row[] = $Res['DispPayStatus'];
        //$row[] = $Res['description'];
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
  }
  /******** END : SERVER SIDE DATATABLE CALL FOR GET THE SUCCESS INDIVIDUAL REGISTRATION DATA ********/

  /******** START : EXAM DETAILS ********/
  public function exam_details()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Exam Details Report";
    $data['page_title'] = 'IIBF - Exam Details Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/exam_details_report_admin', $data);
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
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Candidate Eligible For Examination";
    $data['page_title'] = 'IIBF - Candidate Eligible For Examination';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/eligible_candidate_report_admin', $data);
  }
  /******** END : CANDIDATES ELIGIBLE FOR EXAMINATION DETAILS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES ELIGIBLE FOR EXAMINATION DATA ********/
  public function get_eligible_candidate_for_examination_data_ajax()
  {
     echo $this->Iibf_bcbf_model->get_eligible_candidate_for_examination_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES ELIGIBLE FOR EXAMINATION DATA ********/


  /******** START : INSTITUTION WISE BATCH REPORT ********/
  public function institution_wise_batch()
  {   
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Institution Wise Batch Report";
    $data['page_title'] = 'IIBF - BCBF Institution Wise Batch Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/institution_wise_batch_report_admin', $data);
  }
  /******** END : INSTITUTION WISE BATCH REPORT ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE Institution Wise Batch Report DATA ********/
  public function get_institution_wise_batch_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));
    $selected_batch_ids = trim($this->security->xss_clean($this->input->post('selected_batch_ids')));

    $table = 'iibfbcbf_agency_centre_batch acb';
    $GroupBy = " Group By acb.batch_id";
    
    $column_order = array('""', 'acb.batch_id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", "")) AS DispBatchType', 'acb.batch_start_date', 'acb.batch_end_date', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p")) AS BatchTime', 'acb.total_candidates', 'DATE_FORMAT(acb.created_on, "%Y-%m-%d %H:%i %p") AS batch_created_on', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled"))))))) AS DispBatchStatus', 'COUNT(DISTINCT CASE WHEN bc.re_attempt > "0" THEN bc.candidate_id END) AS no_of_candidates_applied_for_exam', 'acb.batch_status', 'acb.centre_id', 'acb.batch_type', 'acb.batch_online_offline_flag','acb.agency_id'); //SET COLUMNS FOR SORT
    
    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'acb.centre_batch_id', 'IF(acb.batch_type=1, "Basic", IF(acb.batch_type=2, "Advanced", ""))', 'acb.batch_start_date', 'acb.batch_end_date', 'CONCAT(TIME_FORMAT(acb.batch_daily_start_time, "%h:%i %p"), " To ", TIME_FORMAT(acb.batch_daily_end_time, "%h:%i %p"))', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", "Cancelled")))))))'); //SET COLUMN FOR SEARCH
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

    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "")
    {
      $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
    }

    if($selected_batch_ids != "" && $form_action == 'export') { $Where .= " AND acb.batch_id IN (".$selected_batch_ids.")"; }
    
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
    if(isset($_POST['order'])) { $explode_arr = explode(" AS ",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
    else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
    
    $Limit = ""; 
    if ($_POST['length'] != '-1' && $form_action != 'export') 
    { 
      $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
    } // DATATABLE LIMIT  
    
    $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id"; 
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
      $fileName = "Institution_Wise_Batch_".date('Y-m-d').".xls";  
      // Column names 
        $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Batch ID', 'Batch Type', 'Batch From Date', 'Batch To Date', 'Batch Time', 'Candidate Capacity', 'Batch Created', 'Batch Status','No. of Candidates Applied for Exam');  

      if($selected_batch_ids != ""){
        $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Batch Code', 'Batch ID', 'Batch Type', 'Batch From Date', 'Batch To Date', 'Batch Time', 'Candidate Capacity', 'Batch Created', 'Batch Status','No. of Candidates Applied for Exam', 'No. of Candidates Passed', 'No. of Candidates Failed', 'No. of Candidates Absent');  
      }
      // Display column names as first row 
      $excelData = implode("\t", array_values($fields)) . "\n";  
    }

    foreach ($Rows as $Res) 
    {
      $no++;
      $row = array();

      $checked = '';
      if($selected_batch_ids != "" && in_array($Res['batch_id'],explode(",",$selected_batch_ids))){
        $checked = 'checked';
      }

      $checkbox_str = '<label class="css_checkbox_radio"><input '.$checked.' onclick="set_all_batch_id_list(); only_checked_id('.$Res['batch_id'].');" type="checkbox" name="checkboxlist_batch_id" class="checkbox_training_details_btch_common" value="'.$Res['batch_id'].'" id="checkboxlist_batch_id_'.$Res['batch_id'].'"><span class="checkmark"></span></label>';

      if($form_action != 'export'){
        $row[] = $checkbox_str; 
      }
      
      $row[] = $no;
      $row[] = $Res['agency_name'];
      $row[] = $Res['DispCentreName'];
      $row[] = $Res['batch_code'];
      $row[] = $Res['centre_batch_id'];
      $row[] = $Res['DispBatchType'];
      $row[] = $Res['batch_start_date'];
      $row[] = $Res['batch_end_date'];
      $row[] = $Res['BatchTime'];
      $row[] = $Res['total_candidates'];
      $row[] = $Res['batch_created_on'];

      if ($form_action == 'export')
      {
        $row[] = $Res['DispBatchStatus']; 
      }
      else
      {
        $row[] = '<span class="badge '.show_batch_status($Res['batch_status']).'" style="min-width:90px;">'.$Res['DispBatchStatus'].'</span>';
      }
      
      $row[] = $Res['no_of_candidates_applied_for_exam'];  
      
      if ($form_action == 'export')
      {
        if($selected_batch_ids != "" && in_array($Res['batch_id'],explode(",",$selected_batch_ids))){
          //$chk_api_data = $this->Iibf_bcbf_model->calculate_no_of_pass_fail_absent_candidates($Res['batch_id']);
          $chk_api_data = $this->Iibf_bcbf_model->get_iibfbcbf_no_of_pass_fail_absent_cnt_api($Res['batch_code']);
          //echo $this->db->last_query();die;
          //print_r($chk_api_data);die;
          //echo $chk_api_data["pass_cnt"];die;
          $row[] = $chk_api_data["pass_cnt"];  
          $row[] = $chk_api_data["fail_cnt"];  
          $row[] = $chk_api_data["absent_cnt"];  
        }
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
  } /******** END : SERVER SIDE DATATABLE CALL FOR GET THE Institution Wise Batch Report DATA ********/

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
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Training Batch Details Report";
    $data['page_title'] = 'IIBF - BCBF Training Batch Details Report';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/training_details_batch_report_admin', $data);
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
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Candidates Required to re-enroll for Training";
    $data['page_title'] = 'IIBF - Candidates Required to re-enroll for Training';

    $data['agency_data'] = $data['agency_centre_data'] = array();
    
    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted'=>'0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');  

    $this->load->view('iibfbcbf/admin/candidates_required_re_enroll_report_admin', $data);
  }
  /******** END : CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DETAILS ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DATA ********/
  public function get_candidates_to_re_enroll_for_training_data_ajax()
  {
    echo $this->Iibf_bcbf_model->get_candidates_to_re_enroll_for_training_common_data();
  }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE CANDIDATES REQUIRED TO RE_ENROLL FOR TRAINING DATA ********/

  /******** START : BATCH SUMMARY ********/
  public function batch_summary()
  {
    $data['act_id'] = "Reports";
    $data['sub_act_id'] = "Batch Summary";
    $data['page_title'] = 'IIBF - BCBF Batch Summary';
    $data['agency_data'] = $data['agency_centre_data'] = array();

    //$data['s_from_date'] = date("Y-m-d", strtotime("first day of previous month")); 
    $data['s_from_date'] = date("Y-m-d", strtotime("first day of April last year"));
    //$data['s_to_date'] = date("Y-m-d", strtotime("last day of previous month"));
    $data['s_to_date'] = date("Y-m-d");

    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');

    $data['inspector_data'] = $this->master_model->getRecords('iibfbcbf_inspector_master', array('is_deleted' => '0'), "inspector_id,inspector_name", array('inspector_name' => 'ASC'));

    $this->load->view('iibfbcbf/admin/batch_summary_admin', $data);
  }
  /******** END : BATCH SUMMARY OLD ********/

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE BATCH SUMMARY DATA ********/
  public function get_batch_summary_data_ajax()
  {
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $table = 'iibfbcbf_agency_centre_batch acb';
    $GroupBy = " Group By acb.batch_id";

    $column_order = array('acb.batch_id', 'am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'acb.batch_code', 'IF(acb.batch_online_offline_flag = "1", "Offline", "Online") AS batch_mode', 'IF(acb.batch_type = "1", "Basic", "Advanced") AS batch_details', 'acb.training_language', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted")))))))) AS DispBatchStatus', 'GROUP_CONCAT(DISTINCT CONCAT("","",bcbflog.posted_data,"")) AS BatchCommunicationRemarks', 'GROUP_CONCAT(DISTINCT im.inspector_name) AS inspected_by', 'COUNT(bi.inspection_id) AS inspection_count', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(bi.created_on, "%Y-%m-%d")) AS inspection_dates', 'GROUP_CONCAT(DISTINCT CONCAT(""," Remark: ",bi.overall_observation," ")) AS InspectorRemarks', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END) AS total_hold_candidate', 'bcbflog.login_type', 'IF(bcbflog.login_type="centre", bcbflog.posted_data, "") AS remark_by_centre', 'IF(bcbflog.login_type="agency", bcbflog.posted_data, "") AS remark_by_agency', 'IF(bcbflog.login_type="admin", bcbflog.posted_data, "") AS remark_by_admin'); //SET COLUMNS FOR SORT

    $column_search = array('am1.agency_code', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'acb.batch_code', 'IF(acb.batch_online_offline_flag = "1", "Offline", "Online")', 'IF(acb.batch_type = "1", "Basic", "Advanced")', 'acb.training_language', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))'); //SET COLUMN FOR SEARCH

    //, 'acb.batch_code', 'IF(acb.batch_online_offline_flag = "1", "Offline", "Online")', 'IF(acb.batch_type = "1", "Basic", "Advanced")', 'acb.training_language', 'acb.batch_start_date', 'acb.batch_end_date', 'acb.total_candidates', 'IF(acb.batch_status = 0, "In Review", IF(acb.batch_status = 1, "Final Review", IF(acb.batch_status = 2, "Batch Error", IF(acb.batch_status = 3, "Go Ahead", IF(acb.batch_status = 4, "Hold", IF(acb.batch_status = 5, "Rejected", IF(acb.batch_status = 6, "Re-Submitted", IF(acb.batch_status = 7, "Cancelled", "Drafted"))))))))', 'GROUP_CONCAT(DISTINCT CONCAT("","",bcbflog.posted_data,""))', 'GROUP_CONCAT(DISTINCT im.inspector_name)', 'COUNT(bi.inspection_id)', 'GROUP_CONCAT(DISTINCT DATE_FORMAT(bi.created_on, "%Y-%m-%d"))', 'GROUP_CONCAT(DISTINCT CONCAT(""," Remark: ",bi.overall_observation," "))', 'COUNT(DISTINCT CASE WHEN bc.hold_release_status = "2" OR bc.hold_release_status = "1" THEN bc.candidate_id END)'

    $order = array('acb.batch_id' => 'DESC'); // DEFAULT ORDER


    $WhereForTotal = "WHERE acb.is_deleted = '0' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE acb.is_deleted = '0' ";

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
    if ($s_agency != "")
    {
      $Where .= " AND acb.agency_id = '" . $s_agency . "'";
    }

    $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
    if ($s_centre != "")
    {
      $Where .= " AND acb.centre_id = '" . $s_centre . "'";
    }

    $s_batch_type = trim($this->security->xss_clean($this->input->post('s_batch_type')));
    if ($s_batch_type != "")
    {
      $Where .= " AND acb.batch_type = '" . $s_batch_type . "'";
    }

    $s_inspector = trim($this->security->xss_clean($this->input->post('s_inspector')));
    if ($s_inspector != "")
    {
      $Where .= " AND acb.inspector_id = '" . $s_inspector . "'";
    }

    $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
    $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));

    if ($s_from_date != "")
    {
      $Where .= " AND (acb.batch_start_date >= '" . $s_from_date . "' OR acb.batch_end_date >= '" . $s_from_date . "')";
    }
    if ($s_to_date != "")
    {
      $Where .= " AND (acb.batch_start_date <= '" . $s_to_date . "' OR acb.batch_end_date <= '" . $s_to_date . "')";
    }

    $Order = ""; //DATATABLE SORT
    if (isset($_POST['order']))
    {
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
      $Order = "ORDER BY " . $explode_arr[0] . " " . $_POST['order']['0']['dir'];
    }
    else if (isset($order))
    {
      $Order = "ORDER BY " . key($order) . " " . $order[key($order)];
    }
 
    $Limit = ""; 
    if ($_POST['length'] != '-1' && $form_action != 'export') 
    { 
      $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); 
    } // DATATABLE LIMIT  

    $join_qry = " LEFT JOIN iibfbcbf_centre_master cm ON cm.centre_id = acb.centre_id";
    $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_master am1 ON am1.agency_id = acb.agency_id";
    /*$join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm1 ON fm1.faculty_id = acb.first_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm2 ON fm2.faculty_id = acb.second_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm3 ON fm3.faculty_id = acb.third_faculty";
    $join_qry .= " LEFT JOIN iibfbcbf_faculty_master fm4 ON fm4.faculty_id = acb.fourth_faculty";*/
    $join_qry .= " LEFT JOIN iibfbcbf_batch_inspection bi ON bi.batch_id = acb.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_inspector_master im ON im.inspector_id = acb.inspector_id";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc ON bc.batch_id = acb.batch_id";
    $join_qry .= " LEFT JOIN iibfbcbf_logs bcbflog ON bcbflog.pk_id = acb.batch_id AND bcbflog.module_slug = 'batch_action' AND (bcbflog.title LIKE '%Centre : Batch Communication%' OR bcbflog.title LIKE '%Admin : Batch Communication%') ";

    $Where .= $GroupBy;
    $WhereForTotal .= $GroupBy;

    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $WhereForTotal);
    $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $Where);

    $data = array();
    $no = $_POST['start'];

    if ($form_action == 'export')
    {
      // Excel file name for download 
      $fileName = "Batch_Summary_".date('Y-m-d').".xls";  
      // Column names 
      $fields = array('Sr. No.', 'Agency Code', 'Agency Name', 'Centre Name', 'Training Batch Code', 'Mode of Training', 'Course Details', 'Training Medium', 'Batch From Date', 'Batch To Date', 'Total Candidates', 'Batch Status', 'Batch Communication Remarks', 'Inspector Name', 'No. of times Inspected', 'Final Inspection Report Submission Date', 'Inspector Remark', 'No. of Candidates on hold');  
      // Display column names as first row 
      $excelData = implode("\t", array_values($fields)) . "\n";  
    }

    foreach ($Rows as $Res)
    {
      $no++;
      $row = array();

      $BatchCommunicationRemarks = '';
      /*if($Res['login_type'] == "centre" && $Res['posted_data'] != ""){
        $data_json_centre = json_decode($Res['posted_data'], true);  
        $BatchCommunicationRemarks .= "Remarks By Centre: ".$data_json_centre['batch_communication'];
      }else if($Res['login_type'] == "agency" && $Res['posted_data'] != ""){
        $data_json_agency = json_decode($Res['posted_data'], true);  
        $BatchCommunicationRemarks .= "Remarks By Agency: ".$data_json_agency['batch_communication'];
      }else if($Res['login_type'] == "admin" && $Res['posted_data'] != ""){
        $data_json_admin = json_decode($Res['posted_data'], true);  
        $BatchCommunicationRemarks .= "Remarks By Admin: ".$data_json_admin['batch_communication'];
      }*/

       
      if($Res['login_type'] == "centre" && $Res['BatchCommunicationRemarks'] != ""){
        $data_json_centre = json_decode($Res['BatchCommunicationRemarks'], true);  
        $BatchCommunicationRemarks .= "Remarks By Centre: ".$data_json_centre['batch_communication'];
      }else if($Res['login_type'] == "agency" && $Res['BatchCommunicationRemarks'] != ""){
        $data_json_agency = json_decode($Res['BatchCommunicationRemarks'], true);  
        $BatchCommunicationRemarks .= "Remarks By Agency: ".$data_json_agency['batch_communication'];
      }else if($Res['login_type'] == "admin" && $Res['BatchCommunicationRemarks'] != ""){
        $data_json_admin = json_decode($Res['BatchCommunicationRemarks'], true);  
        $BatchCommunicationRemarks .= "Remarks By Admin: ".$data_json_admin['batch_communication'];
      }

      /*$remark_by_centre = $remark_by_agency = $remark_by_admin = ''; 
      $data_json_centre_arr = json_decode($Res['remark_by_centre'], true);  
      $remark_by_centre = $BatchCommunicationRemarks .= "Remarks By Centre: ".$data_json_centre_arr['batch_communication'];
      $data_json_agency_arr = json_decode($Res['remark_by_agency'], true);  
      $remark_by_agency = $BatchCommunicationRemarks .= "Remarks By Agency: ".$data_json_agency_arr['batch_communication'];
      $data_json_admin_arr = json_decode($Res['remark_by_admin'], true);  
      $remark_by_admin = $BatchCommunicationRemarks .= "Remarks By Admin: ".$data_json_admin_arr['batch_communication'];*/
       
      $row[] = $no;
      $row[] = $Res['agency_code'];
      $row[] = $Res['agency_name'];
      $row[] = $Res['DispCentreName'];
      $row[] = $Res['batch_code'];
      $row[] = $Res['batch_mode'];
      $row[] = $Res['batch_details'];
      $row[] = $Res['training_language'];
      $row[] = $Res['batch_start_date'];
      $row[] = $Res['batch_end_date'];
      $row[] = $Res['total_candidates'];
      $row[] = $Res['DispBatchStatus'];
      $row[] = $BatchCommunicationRemarks;
      $row[] = $Res['inspected_by'];
      $row[] = $Res['inspection_count'];
      $row[] = $Res['inspection_dates'];
      $row[] = $Res['InspectorRemarks'];
      $row[] = $Res['total_hold_candidate'];

      /*$row[] = $Res['acb_created_on'];
      $row[] = $Res['acb_updated_on'];      
      $row[] = $Res['contact_person_name'];
      $row[] = $Res['faculty_names'];  
      $row[] = $Res['total_inspection_time_minutes'];
      $row[] = $Res['average_inspection_time']; 
      $row[] = $Res['total_registered_candidate'];
      $row[] = $Res['total_eligible_candidate'];
      $row[] = $Res['total_poor_counts'];
      $row[] = $Res['total_average_counts'];
      $row[] = $Res['total_good_counts'];
      $row[] = $Res['total_excellent_counts'];*/

        
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
      "Query" => $print_query,
      "data" => $data,
    );
    //output to json format
    echo json_encode($output);
  }
  /******** END : SERVER SIDE DATATABLE CALL FOR GET THE BATCH SUMMARY DATA ********/

  /******** START : BULK APPLICATION REPORT ********/
  public function bulk_application_report($report_type = '')
  {
    $sub_act_id = $tbl_gateway = '';
    $sub_act_id = "Bulk Application Report";
    $tbl_gateway = '1';

    $data['act_id'] = "Reports";
    $data['sub_act_id'] = $sub_act_id;
    $data['page_title'] = 'IIBF - BCBF ' . $sub_act_id;
    $data['agency_data'] = $data['agency_centre_data'] = array();
    $data['report_type'] = $report_type;
    $data['tbl_gateway'] = $tbl_gateway;
  
    if(date('Y-m-d') > date("Y").'-04-01') { $data['s_from_date'] = date("Y").'-04-01'; }
    else { $data['s_from_date'] = date("Y", strtotime("-1 Year")).'-04-01'; }
    
    $data['s_to_date'] = date("Y-m-d");

    $data['agency_data'] = $this->master_model->getRecords('iibfbcbf_agency_master am', array('am.is_deleted' => '0'), 'am.agency_id, am.agency_name, am.agency_code, am.is_active');

    $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
    $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.is_deleted' => '0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm1.city_name, cm.centre_name');
    $this->load->view('iibfbcbf/admin/bulk_application_report_admin', $data);
  }
  /******** END : BULK APPLICATION REPORT ********/


  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE BULK APPLICATION REPORT DATA ********/
  public function get_bulk_application_data_ajax()
  {
    $tbl_gateway = trim($this->security->xss_clean($this->input->post('tbl_gateway')));
    $form_action = trim($this->security->xss_clean($this->input->post('form_action')));

    $chk_status_str = '1'; 
    if ($tbl_gateway == '1')
    {
      $chk_status_str = '1,4,3';
    } //NEFT RTGS   

    $table = 'iibfbcbf_payment_transaction pt';     

    $column_order = array('pt.id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), "")) AS DispName', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'pt.description', 'mem_ex.exam_code', 'mem_ex.exam_period', 'pt.bankcode', 'CONCAT(pt.bankcode," (",pt.paymode,")") AS BankName', 'pt.pay_count', 'pt.amount', 'ei.disc_amt', 'ei.tds_amt', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i %p")) AS PaymentDate', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'DATE_FORMAT(pt.created_on, "%Y-%m-%d") AS AddedDate', 'DATE_FORMAT(pt.updated_on, "%Y-%m-%d") AS ApproveRejectDate', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.transaction_details', 'pt.status'); //SET COLUMNS FOR SORT

    $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")', 'bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), ""))', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'pt.description', 'mem_ex.exam_code', 'mem_ex.exam_period', 'pt.bankcode', 'CONCAT(pt.bankcode," (",pt.paymode,")")', 'pt.pay_count', 'pt.amount', 'ei.disc_amt', 'ei.tds_amt', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i %p"))', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'DATE_FORMAT(pt.created_on, "%Y-%m-%d")', 'DATE_FORMAT(pt.updated_on, "%Y-%m-%d")'); //SET COLUMN FOR SEARCH
 
    
    $order = array('pt.id' => 'DESC'); // DEFAULT ORDER

    $WhereForTotal = "WHERE pt.gateway = '".$tbl_gateway."' AND pt.status IN (" . $chk_status_str . ") "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
    $Where = "WHERE pt.gateway = '".$tbl_gateway."' AND pt.status IN (" . $chk_status_str . ") ";

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

    if ($form_action == 'export')
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
      $explode_arr = explode(" AS ", $column_order[$_POST['order']['0']['column']]);
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
    $join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
    $join_qry .= " LEFT JOIN iibfbcbf_member_exam mem_ex ON mem_ex.pt_id = pt.id";
    $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates bc_cand ON bc_cand.candidate_id = mem_ex.candidate_id";
    $join_qry .= " LEFT JOIN iibfbcbf_agency_centre_batch acb ON acb.batch_id = bc_cand.batch_id";
 
    $print_query = "SELECT " . str_replace(" , ", " ", implode(", ", $column_order)) . " FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
    $Result = $this->db->query($print_query);
    $Rows = $Result->result_array();

    $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $WhereForTotal);
    $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0], $table . " " . $join_qry, $Where);

    if ($form_action == 'export')
    {
      // Excel file name for download 
      $fileName = "bulk_application_" . date('Y-m-d') . ".xls"; 
      
      $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Training ID', 'Candidate Name', 'Training Batch', 'Mobile', 'Email', 'Transaction Details', 'Exam Code', 'Exam Period', 'Bank ID', 'Bank Name', 'Pay Count', 'Amount', 'Discount Amount', 'TDS Amount', 'Paid Date', 'NEFT/UTR No.', 'Added Date', 'Approve Date', 'Status'); // Column names 

      $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row             
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
      $row[] = $Res['training_id']; 
      $row[] = $Res['DispName']; 
      $row[] = $Res['batch_code']; 
      $row[] = $Res['mobile_no']; 
      $row[] = $Res['email_id']; 
      
      $row[] = $Res['description'];
      $row[] = $Res['exam_code'];
      $row[] = $Res['exam_period'];
      $row[] = $Res['bankcode'];
      $row[] = $Res['BankName'];
      $row[] = $Res['pay_count'];
      $row[] = $Res['amount'];
      $row[] = $Res['disc_amt'];
      $row[] = $Res['tds_amt'];
      $row[] = $Res['PaymentDate'];
      $row[] = $Res['DispTransactionNo'];
      $row[] = $Res['AddedDate'];
      $row[] = $Res['ApproveRejectDate'];

      if ($form_action == 'export')
      {
        $row[] = $Res['DispPayStatus']; 
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
  }
  /******** END : SERVER SIDE DATATABLE CALL FOR GET THE BULK APPLICATION REPORT DATA ********/

}
