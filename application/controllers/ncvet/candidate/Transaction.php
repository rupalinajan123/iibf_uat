<?php 
  /********************************************************************************************************************
    ** Description: Controller for NCVET Candidate Transactions
    ** Created BY: Gaurav Shewale On 29-08-2025
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Transaction extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('ncvet/Ncvet_model');
      $this->load->helper('ncvet/ncvet_helper');  
      $this->load->helper('file'); 
      $this->load->helper('getregnumber_helper'); 
      
      $this->login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('NCVET_USER_TYPE');
      
      if($this->login_user_type != 'candidate') { $this->login_user_type = 'invalid'; }
			$this->Ncvet_model->check_candidate_session_all_pages($this->login_user_type); // If candidate session is not started then redirect to logout
    }
    
    
    public function index($enc_id=0)
    {      
      $data['act_id']        = "Transaction Details";
      $data['sub_act_id']    = "Transaction Details";
      $data['page_title'] = 'NCVET '.ucfirst($this->login_user_type).' Transactions : List'; 
      
      $this->load->view('ncvet/candidate/transactions_list', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_transaction_data_ajax()
    {
      $table = 'ncvet_payment_transaction pt';
      
      $column_order = array('pt.id',  'pt.pg_flag', 'pt.transaction_no AS DispTransactionNo', 'pt.receipt_no', 'pt.amount', 'DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i") AS PaymentDate', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", ""))))) AS DispPayStatus', 'pt.status', 'IF(pt.pay_type=1, "Membership Enrollment", "") AS TransactionType'); //SET COLUMNS FOR SORT
      
      $column_search = array('pt.transaction_no', 'pt.receipt_no', 'pt.description', 'pt.amount', 'pt.pay_count', 'DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=3, "Applied", IF(pt.status=4, "Cancelled", "")))))'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC'); // DEFAULT ORDER
      
      //$WhereForTotal = " WHERE pt.gateway = '1' ";
      $WhereForTotal = " WHERE pt.ref_id = ".$this->login_candidate_id." AND pt.date != '' AND pt.transaction_no != '' ";
      //$Where = " WHERE pt.gateway = '1' ";
      $Where = " WHERE pt.ref_id = ".$this->login_candidate_id." AND pt.date != '' AND pt.transaction_no != '' ";
      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }  
            
      $s_utr_no = trim($this->security->xss_clean($this->input->post('s_utr_no')));
      if($s_utr_no != "") { $Where .= " AND (pt.UTR_no LIKE '%".$s_utr_no."%' OR pt.transaction_no LIKE '%".$s_utr_no."%' )"; }
       
      $s_receipt_no = trim($this->security->xss_clean($this->input->post('s_receipt_no')));
      if($s_receipt_no != "") { $Where .= " AND pt.receipt_no = '".$s_receipt_no."'"; } 

      // $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      // if($s_exam_code != "") { $Where .= " AND pt.exam_code = '".$s_exam_code."'"; } 

      // $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      // if($s_exam_period != "") { $Where .= " AND pt.exam_period = '".$s_exam_period."'"; } 
      
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      if($s_from_date != "") { $Where .= " AND DATE(pt.date) >= '".$s_from_date."'"; } 
      
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      if($s_to_date != "") { $Where .= " AND DATE(pt.date) <= '".$s_to_date."'"; } 
      
      // $s_payment_mode = trim($this->security->xss_clean($this->input->post('s_payment_mode')));
      // if($s_payment_mode != "") { $Where .= " AND pt.payment_mode = '".$s_payment_mode."'"; } 
      
      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND pt.status = '".$s_payment_status."'"; }      
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY ".key($order)." ".$order[key($order)]; }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $WhereForTotal .= " GROUP BY pt.id";
      $Where .= " GROUP BY pt.id";

      // $join_qry = " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = pt.exam_code";       
      // $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
      // $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table,$WhereForTotal);
      $FilteredResult = $this->Ncvet_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);
      
      $data = array();
      $no = $_POST['start'];    
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        // $row[] = $Res['agency_name'];
        // $row[] = $Res['DispCentre'];
        $row[] = $Res['TransactionType'];
        $row[] = $Res['DispTransactionNo'];
        $row[] = $Res['receipt_no'];
        // $row[] = $Res['description'];
        $row[] = $Res['amount'];
        // $row[] = $Res['pay_count'];
        $row[] = $Res['PaymentDate'];
        // $row[] = $Res['exam_code'];
        // $row[] = $Res['exam_period'];

        $row[] = '<span class="badge '.show_payment_status($Res['status']).'" style="width: 100px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //ncvet_helper.php
        
        $btn_str = ' <div class="text-right"> ';
      
        $this->db->limit(1);
        $invoice_data = $this->master_model->getRecords('ncvet_exam_invoice ei', array('ei.receipt_no' => $Res['receipt_no'], 'ei.pay_txn_id'=>$Res['id'], 'ei.invoice_image !='=>''), "invoice_id,gstin_no,invoice_no,created_on");
        
        if(count($invoice_data) > 0 && !empty($invoice_data[0]['invoice_id']) && $invoice_data[0]['invoice_id'] != "")
        {
          // <i class="fa fa-file-text"></i>
          $btn_str .= ' <a target="_blank" href="'.site_url('ncvet/download_file_common/index/'.url_encode($invoice_data[0]['invoice_id']).'/invoice_image').'" class="btn btn-danger" title="View Invoice">Download Invoice</a> ';
        }

        /*if(count($invoice_data) > 0 && !empty($invoice_data[0]['gstin_no']) && $invoice_data[0]['gstin_no'] != "" && $invoice_data[0]['created_on'] >= '2025-06-01 00:01:01')
        {
          $str_invoice_no = str_replace("/","_",$invoice_data[0]['invoice_no']);
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/transaction/get_e_invoice/'.url_encode($str_invoice_no)).'" class="btn btn-info btn-xs" title="View E-Invoice"><i class="fa fa-file-text-o"></i></a> ';
        }*/
        
        /*if($Res['status'] == "1" && in_array($Res['exam_code'], $this->eligible_exam_code_for_admit_card_arr))
        {
          $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/admin/transaction/admit_cards/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="Admit Card"><i class="fa fa-id-card" aria-hidden="true"></i></a> ';
        }*/
        
        $btn_str .= ' </div>';
        $row[] = $btn_str;
        
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
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    
    // function to get transaction details by id -
    public function getTransactionDetails()
    { 
      $flag = "error"; 
      $result = array();
      if(isset($_POST) && $_POST['enc_id'] != "")
      {  
        $enc_id = $this->security->xss_clean($this->input->post('enc_id'));   
        //$id = url_decode($enc_id);        
        $id = ($enc_id);        
        $this->db->where("pt.id",$id);
        $this->db->join('iibfbcbf_exam_master as em','em.exam_code = pt.exam_code','LEFT');
        $payment_transaction_data = $this->master_model->getRecords('ncvet_payment_transaction as pt', array('pt.gateway' => '1'), 'pt.id, pt.UTR_no AS transaction_no, pt.UTR_slip_file, em.description, pt.pay_count AS member_count, pt.amount, DATE_FORMAT(pt.date,"%Y-%m-%d") As date, pt.exam_period, pt.status'); 
        if(count($payment_transaction_data) > 0)
        {
          $payment_transaction_data[0]['enc_payment_id'] = url_encode($payment_transaction_data[0]['id']);
          $result = $payment_transaction_data[0]; 
          $flag = "success"; 
        } 
      } 
      $result['flag'] = $flag;
      $result['response'] = $result; 
      echo json_encode($result); 
    }

  } ?>    