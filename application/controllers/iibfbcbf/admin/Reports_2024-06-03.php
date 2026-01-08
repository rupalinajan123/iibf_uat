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
      $column_order = array('pt.id', 'am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")") AS DispCentreName', 'bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), "")) AS DispName', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'pt.description', 'pt.transaction_details'); //SET COLUMNS FOR SORT

      $column_search = array('am1.agency_name', 'CONCAT(cm.centre_name," (",cm1.city_name,")")','bc_cand.training_id', 'CONCAT(bc_cand.salutation, " ", bc_cand.first_name, IF(bc_cand.middle_name != "", CONCAT(" ", bc_cand.middle_name), ""), IF(bc_cand.last_name != "", CONCAT(" ", bc_cand.last_name), ""))', 'acb.batch_code', 'bc_cand.mobile_no', 'bc_cand.email_id', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'pt.amount', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))'); //SET COLUMN FOR SEARCH
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
        $Where = substr_replace( $Where, "", -3 );
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
        $fields = array('Sr. No.', 'Agency Name', 'Centre Name', 'Training ID', 'Candidate Name', 'Training Batch', 'Mobile', 'Email', 'Transaction No.', 'Receipt No.', 'Amount', 'Date', 'Status', 'Description', 'Transaction Details'); // Column names  
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
        $row[] = $Res['DispName']; 
        $row[] = $Res['batch_code']; 
        $row[] = $Res['mobile_no']; 
        $row[] = $Res['email_id']; 
      }
      $row[] = $Res['DispTransactionNo'];
      $row[] = $Res['receipt_no'];
      $row[] = $Res['amount'];
      $row[] = $Res['PaymentDate'];

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

  /******** START : SERVER SIDE DATATABLE CALL FOR GET THE BATCH SUMMARY DATA ********/
  public function get_batch_summary_data_ajax()
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
      $explode_arr = explode("AS", $column_order[$_POST['order']['0']['column']]);
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
      $explode_arr = explode("AS", $column_order[$_POST['order']['0']['column']]);
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
}
