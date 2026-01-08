<?php 
  /********************************************************************************************************************
  ** Description: Controller for BCBF Agency Transaction Details
  ** Created BY: Sagar Matale On 11-12-2023
  ********************************************************************************************************************/
  defined('BASEPATH') OR exit('No direct script access allowed');
  
  class Transaction_details_agency extends CI_Controller 
  {
    public function __construct()
    {
      parent::__construct();
      
      $this->load->model('master_model');
      $this->load->model('iibfbcbf/Iibf_bcbf_model');
      $this->load->helper('iibfbcbf/iibf_bcbf_helper'); 
      $this->load->helper('file');
      $this->load->helper('directory');
      
      $this->login_agency_or_centre_id = $this->session->userdata('IIBF_BCBF_LOGIN_ID');
      $this->login_user_type = $this->session->userdata('IIBF_BCBF_USER_TYPE');

      if($this->login_user_type != 'agency' && $this->login_user_type != 'centre') { $this->login_user_type = 'invalid'; }
			$this->Iibf_bcbf_model->check_admin_session_all_pages($this->login_user_type); // If admin session is not started then redirect to logout

      //genarate_iibf_bcbf_exam_invoice(11);
      $this->utr_slip_path = 'uploads/iibfbcbf/utr_slip';

      
      $this->login_agency_id = $this->login_centre_id = $this->allow_exam_types = '';
      if($this->login_user_type == 'agency')
      {
        $this->login_agency_id = $this->login_agency_or_centre_id;
      }
      else if($this->login_user_type == 'centre')
      {
        $this->login_centre_id = $this->login_agency_or_centre_id;

        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master',array('centre_id'=>$this->login_agency_or_centre_id), 'centre_id, agency_id');
        if(count($centre_data) > 0)
        {
          $this->login_agency_id = $centre_data[0]['agency_id'];
        }
      }

      $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id' => $this->login_agency_id), "agency_id, allow_exam_codes, allow_exam_types");
      if(count($agency_data) > 0)
      {
        $this->allow_exam_types = $agency_data[0]['allow_exam_types'];
      }

      $this->venue_master_eligible_exam_codes_arr = array(1041,1042,1057);//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM

      $this->eligible_exam_code_for_admit_card_arr = array(1039,1040,1041,1042,1057); //ADMITCARD IS AVAILABLE ONLY FOR 1039,1040,1041,1042,1057 EXAM CODES. 1039 & 1040 ARE CSC MODE EXAMS. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM
    }
    
    public function index()
    {   
      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Transaction Details';
      $data['allow_exam_types'] = $this->allow_exam_types;

      $data['utr_slip_path'] = $utr_slip_path = $this->utr_slip_path;
      $data['utr_slip_error'] = '';
      $error_flag = 0;

      $data['agency_centre_data'] = array();
      $agency_id = '0';
      if($this->login_user_type == 'agency') 
      { 
        $agency_id = $this->login_agency_or_centre_id; 

        $this->db->join('city_master cm1', 'cm1.id = cm.centre_city', 'LEFT');
        $data['agency_centre_data'] = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.agency_id'=>$agency_id, 'cm.status' => '1', 'cm.is_deleted'=>'0'), 'cm.centre_id, cm.agency_id, cm.centre_state, cm.centre_city, cm.centre_name, cm.centre_username, cm1.city_name');
      }
      else if($this->login_user_type == 'centre') 
      { 
        $centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, cm.agency_id, cm.centre_username');
        if(count($centre_data) > 0)
        {
          $agency_id = $centre_data[0]['agency_id'];
        }
      }

      /******** START : CODE TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/
      if(isset($_POST) && count($_POST) > 0 && $this->login_user_type == 'centre')
			{
        $enc_payment_id = $this->security->xss_clean($this->input->post('enc_payment_id'));
        $payment_id = url_decode($enc_payment_id);
        
        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id' => $payment_id, 'pt.UTR_no' => 'IIBFBCBF-TEMP-UTR-NO', 'status'=>'3'), 'pt.id, pt.exam_ids, pt.amount, pt.exam_code, pt.exam_period', array('pt.id'=>'DESC'));
        if(count($payment_data) == 0)
        {
          $this->session->set_flashdata('error','Invalid request');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        
        //START : GET ACTIVE EXAM DETAILS
        $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details(url_encode($payment_data[0]['exam_code']), $agency_id,'bulk'); 
        if(count($active_exam_data) == 0)
        {
          $this->session->set_flashdata('error','This exam is not active');
          redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
        }
        else
        {
          if($active_exam_data[0]['exam_period'] != $payment_data[0]['exam_period'])
          {
            $this->session->set_flashdata('error','This exam is not active');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }//END : GET ACTIVE EXAM DETAILS
        
        $selcted_member_exam_ids_str = $payment_data[0]['exam_ids'];
        
        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $this->db->where_in('me.pay_status', '3', FALSE);
        $this->db->where_in('me.member_exam_id', $selcted_member_exam_ids_str, FALSE);
        $data['form_data'] = $candidate_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.is_deleted'=>'0', 'cand.is_deleted'=>'0'), 'me.member_exam_id, me.candidate_id, me.batch_id, me.exam_code, me.exam_mode, me.exam_medium, me.exam_period, me.exam_centre_code, me.exam_venue_code, me.exam_date, me.exam_time, me.exam_fee, me.pay_status, me.fee_paid_flag, cand.training_id, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.registration_type');

        $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
        $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
        $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
        $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name, cm.gst_no AS CentreGST, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, cm.invoice_address, city.city_name, am.agency_name, am.agency_code, am.gst_no AS AgencyGST');
        
        //START : SERVER SIDE VALIDATION
        $this->form_validation->set_rules('utr_no', 'NEFT / RTGS (UTR) Number', 'trim|required|callback_fun_restrict_input[allow_only_alphabets_and_numbers]|max_length[30]|callback_validation_check_utr_exist[]|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required|xss_clean', array('required'=>"Please select the %s"));
        $this->form_validation->set_rules('utr_slip', 'payment (UTR) slip', 'trim|required|callback_fun_validate_file_upload[utr_slip|y|jpg,jpeg,png|100|payment (UTR) slip]'); //callback parameter separated by pipe 'input name|required|allowed extension|size in kb' 
        /* $this->form_validation->set_rules('gst_centre_id', 'GST No to be displayed on Invoice', 'trim|required|xss_clean', array('required'=>"Please select the %s"));  */        
        //END : SERVER SIDE VALIDATION 

        $up_payment_data = array();
        if($this->form_validation->run() == TRUE)
        { 
          if(in_array($active_exam_data[0]['exam_code'],$this->venue_master_eligible_exam_codes_arr))//VENUE MASTER IS ONLY APPLICABLE FOR EXAM CODE 1041, 1042, 1057. HENCE THE CAPACITY CHECK IS APPLY FOR ONLY 1041, 1042, 1057. 1041 & 1042 ARE HYBRID MODE EXAMS. 1057 IS BCBF NAR HYBRID MODE EXAM
          {
            foreach ($candidate_data as $candidate_res)
            {
              //echo '<br>'.$active_exam_data[0]['exam_code']." - ".$active_exam_data[0]['exam_period']." - ".$candidate_res['exam_centre_code']." - ".$candidate_res['exam_venue_code']." - ".$candidate_res['exam_date']." - ".$candidate_res['exam_time'];
              $chk_capacity = $this->Iibf_bcbf_model->get_capacity_bulk($active_exam_data[0]['exam_code'], $active_exam_data[0]['exam_period'], $candidate_res['exam_centre_code'], $candidate_res['exam_venue_code'], $candidate_res['exam_date'], $candidate_res['exam_time'], $candidate_res['member_exam_id'], 'make_payment');

              if($chk_capacity <= 0)
              {
                $this->session->set_flashdata('error','The capaciy is full');
                redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
              }
            }
          }          

          if($_FILES['utr_slip']['name'] != "")
          {
            //$input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $is_multiple=0, $cnt='', $height=0, $width=0, $size=0,$resize_width=0,$resize_height=0,$resize_file_name
            $new_img_name = "utr_slip_".date("YmdHis").'_'.rand(1000,9999);
            $upload_data1 = $this->Iibf_bcbf_model->upload_file("utr_slip", array('png','jpg','jpeg'), $new_img_name, "./".$utr_slip_path, "png|jpeg|jpg",0,'','','','100','1000','500',$new_img_name);
            if($upload_data1['response'] == 'error')
            {
              $data['utr_slip_error'] = $upload_data1['message'];
              $error_flag = 1;
            }
            else if($upload_data1['response'] == 'success')
            {
              $up_payment_data['UTR_slip_file'] = $upload_data1['message'];
            }
          }

          if($error_flag == 1)
          {
            @unlink("./".$utr_slip_path."/".$upload_data1['message']);
          }
          else if($error_flag == 0)
          {            
            $utr_no = $this->security->xss_clean($this->input->post('utr_no')); 
            $payment_date = $this->security->xss_clean($this->input->post('payment_date')); 
            /* $gst_centre_id = $this->security->xss_clean($this->input->post('gst_centre_id')); */
            
            $gstin_no = '-';
            //if($gst_centre_id > 0) { $gstin_no = $agency_centre_data[0]['gst_no']; /* logged in centre GST number */ }
            if($agency_centre_data[0]['invoice_address'] == '1') { $gstin_no = $agency_centre_data[0]['AgencyGST']; }
            else if($agency_centre_data[0]['invoice_address'] == '2') { $gstin_no = $agency_centre_data[0]['CentreGST']; }
            
            //START : UPDATE PAYMENT TABLE ENTRY
            $up_payment_data['UTR_no'] = $utr_no;
            $up_payment_data['date'] = $payment_date;
            $up_payment_data['ip_address'] = get_ip_address(); //general_helper.php 
            $up_payment_data['updated_on'] = date('Y-m-d H:i:s');
            $up_payment_data['updated_by'] = $this->login_agency_or_centre_id;
            $this->master_model->updateRecord('iibfbcbf_payment_transaction', $up_payment_data, array('id'=>$payment_id));
            
            $posted_arr = json_encode($_POST);
            $centreName = $this->Iibf_bcbf_model->getLoggedInUserDetails($this->login_agency_or_centre_id, 'centre');
                  
            $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_payment_transaction', $this->db->last_query(), $payment_id,'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', $posted_arr);
            //UPDATE : INSERT PAYMENT TABLE ENTRY
            
            foreach ($candidate_data as $candidate_res)
            {
              //START : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
              $up_exam_arr = array();
              $up_exam_arr['ref_utr_no'] = $utr_no;
              $up_exam_arr['updated_on'] = date('Y-m-d H:i:s');
              $up_exam_arr['updated_by'] = $this->login_agency_or_centre_id;
              $this->master_model->updateRecord('iibfbcbf_member_exam',$up_exam_arr,array('member_exam_id'=>$candidate_res['member_exam_id']));
              //END : UPDATE DETAILS IN MEMBER EXAM TABLE FOR SELECTED CANDDATES
                    
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'iibfbcbf_member_exam', $this->db->last_query(), $candidate_res['member_exam_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated the payment details of the candidate', $posted_arr);

              $this->Iibf_bcbf_model->insert_common_log('Candidate : Update payment details', 'iibfbcbf_batch_candidates', '', $candidate_res['candidate_id'],'candidate_action','The '.$centreName['disp_name'].' successfully updated the payment details for the exam ('.$active_exam_data[0]['exam_code'].' & '.$active_exam_data[0]['exam_period'].').', '');
            }
              
            //START : UPDATE EXAM INVOICE TABLE ENTRY
            $pg_flag = 'BC';
            $exam_invoice_data = $this->master_model->getRecords('exam_invoice', array('pay_txn_id'=>$payment_id, 'receipt_no'=>$payment_id, 'exam_code'=>$payment_data[0]['exam_code'], 'exam_period'=>$payment_data[0]['exam_period'], 'center_code'=> $agency_centre_data[0]['centre_id'], 'center_name' => $agency_centre_data[0]['centre_name'], 'institute_code' => $agency_centre_data[0]['agency_code'], 'institute_name' => $agency_centre_data[0]['agency_name'], 'app_type'=>$pg_flag), 'invoice_id');            
            if(count($exam_invoice_data) > 0) 
            {
              $up_invoice = array();
              $up_invoice['gstin_no'] = $gstin_no;
              $up_invoice['transaction_no'] = $utr_no;
              $up_invoice['modified_on'] = date('Y-m-d H:i:s');
              $this->master_model->updateRecord('exam_invoice',$up_invoice,array('invoice_id'=>$exam_invoice_data[0]['invoice_id']));
              
              $this->Iibf_bcbf_model->insert_common_log('Centre : Update payment details', 'exam_invoice', $this->db->last_query(), $exam_invoice_data[0]['invoice_id'],'make_payment','The '.$centreName['disp_name'].' successfully updated record in exam invoice table', $posted_arr);
              //die;
            }//END : UPDATE EXAM INVOICE TABLE ENTRY

            $this->session->set_flashdata('success','NEFT/RTGS Payment details is added and sent for approval');
            redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
          }
        }
      }/******** END : FUNCTION TO UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/

      $this->load->view('iibfbcbf/agency/transaction_details_agency', $data);
    }
    
    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/
    public function get_transaction_data_ajax()
    {
      $form_action = '';
      if(isset($_POST['form_action'])) { $form_action = trim($this->security->xss_clean($this->input->post('form_action'))); }

      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")") AS DispCentre', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no) AS DispTransactionNo', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")") AS description', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i")) AS PaymentDate', 'pt.exam_code', 'pt.exam_period', '"" AS DispMemberNumbers', '"" AS DispMemberTrainingIds', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), ""))))) AS DispPayStatus', 'pt.status', 'ei.invoice_image', 'ei.invoice_id', 'me.regnumber', 'cand.training_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cm.centre_name," (", cm.centre_username, " - ", cm1.city_name, ")")', 'IF(pt.payment_mode="Bulk", pt.UTR_no, pt.transaction_no)', 'pt.receipt_no', 'CONCAT(em.description," (", em.exam_code, ")")', 'pt.amount', 'pt.pay_count', 'IF(pt.payment_mode="Bulk", DATE_FORMAT(pt.date, "%Y-%m-%d"), DATE_FORMAT(pt.date, "%Y-%m-%d %H:%i"))', 'pt.exam_code', 'pt.exam_period', 'pt.payment_mode', 'IF(pt.status=0, "Fail", IF(pt.status=1, "Success", IF(pt.status=2, "Pending", IF(pt.status=4, "Cancelled", IF(pt.status=3, IF(pt.UTR_no = "IIBFBCBF-TEMP-UTR-NO", "Proforma Invoice Generated", "Payment Pending for Approval by IIBF"), "")))))', 'me.regnumber', 'cand.training_id'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC'); // DEFAULT ORDER 

      if($this->allow_exam_types == 'CSC')
      {
        $WhereForTotal = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where = "WHERE pt.payment_done_by_agency_id = '".$this->login_agency_id."' ";

        if($this->login_user_type == 'centre')
        {
          $WhereForTotal .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where .= " AND pt.payment_done_by_centre_id = '".$this->login_centre_id."' ";
        }
      }
      else
      {
        if($this->login_user_type == 'centre')
        {
          $WhereForTotal = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.centre_id = '".$this->login_agency_or_centre_id."' ";
        }
        else if($this->login_user_type == 'agency')
        {
          $WhereForTotal = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
          $Where = "WHERE pt.agency_id = '".$this->login_agency_or_centre_id."' ";
        }      
      }
      
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
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND pt.centre_id = '".$s_centre."'"; } 
     
      $s_member_no = trim($this->security->xss_clean($this->input->post('s_member_no')));
      /* if($s_member_no != "") { $Where .= " AND ((SELECT GROUP_CONCAT(bc.regnumber SEPARATOR ', ') from iibfbcbf_batch_candidates bc WHERE FIND_IN_SET(bc.candidate_id, (SELECT GROUP_CONCAT(me.candidate_id) from iibfbcbf_member_exam me WHERE FIND_IN_SET(me.member_exam_id,pt.exam_ids)))) LIKE '%".$s_member_no."%' )"; }  */
      if($s_member_no != "") { $Where .= " AND me.regnumber = '".$s_member_no."'"; }
      
      $s_utr_no = trim($this->security->xss_clean($this->input->post('s_utr_no')));
      if($s_utr_no != "") { $Where .= " AND (pt.UTR_no LIKE '%".$s_utr_no."%' OR pt.transaction_no LIKE '%".$s_utr_no."%')"; } 

      $s_exam_code = trim($this->security->xss_clean($this->input->post('s_exam_code')));
      if($s_exam_code != "") { $Where .= " AND pt.exam_code = '".$s_exam_code."'"; } 

      $s_exam_period = trim($this->security->xss_clean($this->input->post('s_exam_period')));
      if($s_exam_period != "") { $Where .= " AND pt.exam_period = '".$s_exam_period."'"; } 
      
      $s_from_date = trim($this->security->xss_clean($this->input->post('s_from_date')));
      if($s_from_date != "") { $Where .= " AND DATE(pt.date) >= '".$s_from_date."'"; } 
      
      $s_to_date = trim($this->security->xss_clean($this->input->post('s_to_date')));
      if($s_to_date != "") { $Where .= " AND DATE(pt.date) <= '".$s_to_date."'"; } 
      
      $s_payment_mode = trim($this->security->xss_clean($this->input->post('s_payment_mode')));
      if($s_payment_mode != "") { $Where .= " AND pt.payment_mode = '".$s_payment_mode."'"; } 

      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") 
      {
        if($s_payment_status == '3') 
        { 
          $Where .= " AND pt.UTR_no = 'IIBFBCBF-TEMP-UTR-NO'"; 
        }
        else if($s_payment_status == '5') 
        { 
          $s_payment_status = '3'; 
          $Where .= " AND pt.UTR_no != 'IIBFBCBF-TEMP-UTR-NO'";
        }

        $Where .= " AND pt.status = '".$s_payment_status."'"; 
      }
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1'  && $form_action != 'export') { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $WhereForTotal .= " GROUP BY pt.id";
      $Where .= " GROUP BY pt.id";
      
      $join_qry = " LEFT JOIN iibfbcbf_exam_master em ON em.exam_code = pt.exam_code"; 
      $join_qry .= " INNER JOIN iibfbcbf_centre_master cm ON cm.centre_id = pt.centre_id";
      $join_qry .= " LEFT JOIN city_master cm1 ON cm1.id = cm.centre_city";
      $join_qry .= " INNER JOIN iibfbcbf_agency_master am ON am.agency_id = pt.agency_id";
      $join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      $join_qry .= " LEFT JOIN iibfbcbf_member_exam me ON me.pt_id = pt.id";
      $join_qry .= " LEFT JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id";
            
      $print_query = "SELECT ".str_replace(" , ", " ", implode(", ", $column_order))." FROM $table $join_qry $Where $Order $Limit "; //ACTUAL QUERY
      $Result = $this->db->query($print_query);  
      $Rows = $Result->result_array();
      
      $TotalResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table,$WhereForTotal);
      $FilteredResult = $this->Iibf_bcbf_model->datatable_record_cnt($column_order[0],$table." ".$join_qry,$Where);

      if ($form_action == 'export')
      {
        // Excel file name for download 
        $fileName = "Agency_transaction_details_" . date('Y-m-d_H_i_s') . ".xls";

        $fields = array('Sr. No.', 'Centre Name', 'NEFT / RTGS (UTR) or Transaction Number', 'Receipt No.', 'Application', 'Amount', 'No. of Candidates', 'Payment Date', 'Exam Code', 'Exam Period', 'Registration No.', 'Training Ids', 'Payment Mode', 'Status'); // Column names 
        $excelData = implode("\t", array_values($fields)) . "\n"; // Display column names as first row
      }
      
      $data = array();
      $no = $_POST['start'];    
      
      $this->db->having(' (CURRENT_TIMESTAMP BETWEEN ChkExamStart AND ChkExamEnd) ');
      $this->db->join('iibfbcbf_exam_activation_master eam', 'eam.exam_code = em.exam_code', 'INNER');
      $get_active_exam_data = $this->master_model->getRecords('iibfbcbf_exam_master em', array('em.exam_delete'=>'0', 'eam.exam_activation_delete' => '0'), "em.exam_code, em.description, em.exam_type, IF(em.exam_type = 1,'Basic', IF(em.exam_type = 2, 'Advanced','')) AS DispExamType, eam.exam_period, CONCAT(eam.exam_from_date,' ', eam.exam_from_time) AS ChkExamStart, CONCAT(eam.exam_to_date,' ', eam.exam_to_time) AS ChkExamEnd, eam.exam_from_date, eam.exam_from_time, eam.exam_to_date, eam.exam_to_time");
      $active_exam_code_arr = array();
      if(count($get_active_exam_data) > 0)
      {
        foreach($get_active_exam_data as $get_active_exam_res)
        {
          $active_exam_code_arr[]=$get_active_exam_res['exam_code'];
        }
      }
      
      foreach ($Rows as $Res) 
      {
        $no++;
        $row = array();
        
        $row[] = $no;
        $row[] = $Res['DispCentre'];
        $row[] = $Res['DispTransactionNo'];
        $row[] = $Res['receipt_no'];
        $row[] = $Res['description'];
        $row[] = $Res['amount'];
        $row[] = $Res['pay_count'];
        $row[] = $Res['PaymentDate'];
        $row[] = $Res['exam_code'];
        $row[] = $Res['exam_period'];

        $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
        $cand_data = $this->master_model->getRecords('iibfbcbf_member_exam me', array('me.pt_id' => $Res['id']), 'me.candidate_id, cand.regnumber, cand.training_id');
        $DispMemberNumbers = $DispMemberTrainingIds = '';
        if(count($cand_data) > 0)
        {
          foreach($cand_data as $cand_res)
          {
            if($cand_res['regnumber'] != '') { $DispMemberNumbers .= $cand_res['regnumber'].', '; }
            $DispMemberTrainingIds .= $cand_res['training_id'].', ';
          }
        }

        $row[] = rtrim($DispMemberNumbers,", ");        
        $row[] = rtrim($DispMemberTrainingIds,", ");        
        $row[] = $Res['payment_mode'];

        if ($form_action != 'export')
        {
          $row[] = '<span class="badge '.show_payment_status($Res['status']).'" style="width: 120px;white-space: normal;line-height: 15px; word-break: break-word;">'.$Res['DispPayStatus'].'</span>'; //iibf_bcbf_helper.php              
          
          $text_centre_cls = '';
          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
          {
            $text_centre_cls = 'text-center';
          }
          $btn_str = ' <div class="'.$text_centre_cls.' no_wrap" style="width: 90px; margin: 0 auto;"> ';

          if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { }
          else
          {
            $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_agency/'.url_encode($Res['id'])).'" class="btn btn-success btn-xs" title="Payment Receipt"><i class="fa fa-money"></i></a> ';
          }
          
          if($Res['DispTransactionNo'] == "IIBFBCBF-TEMP-UTR-NO" && in_array($Res['exam_code'],$active_exam_code_arr))
          {
            if($this->login_user_type == 'centre')
            {
              $onclick_fun = "update_payment_details_modal('".url_encode($Res['id'])."')";
              $btn_str .= ' <button class="btn btn-warning btn-xs" title="Update Payment Details" onclick="'.$onclick_fun.'"><i class="fa fa-inr"></i></button> ';
            }
            
            $btn_str .= ' <a href="'.site_url('iibfbcbf/agency/transaction_details_agency/proforma_invoice/'.url_encode($Res['id'])).'" target="_blank" class="btn btn-info btn-xs" title="Proforma Invoice"><i class="fa fa-list-alt" aria-hidden="true"></i></a> ';
          }        

          if($Res['invoice_image'] != "")
          {
            $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/download_file_common/index/'.url_encode($Res['invoice_id']).'/invoice_image').'" class="btn btn-primary btn-xs" title="View Invoice"><i class="fa fa-file-text"></i></a> ';
          }

          if($Res['status'] == "1" && in_array($Res['exam_code'], $this->eligible_exam_code_for_admit_card_arr))
          {
            if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC') { } 
            else
            {
              $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/admit_cards/'.url_encode($Res['id'])).'" class="btn btn-warning btn-xs" title="View Admit Cards"><i class="fa fa-file-text"></i></a> ';
            }
          }
          $btn_str .= ' </div>';
          $row[] = $btn_str; 
        }
        else if ($form_action == 'export')
        {
          $row[] = $Res['DispPayStatus'];
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
      "Query" => $print_query,
      "data" => $data,
      );
      //output to json format
      echo json_encode($output);
    }/******** END : SERVER SIDE DATATABLE CALL FOR GET THE TRANSACTION DATA ********/  
    
    public function payment_receipt_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = pt.agency_id', 'INNER');
      $this->db->join('iibfbcbf_centre_master cm', 'cm.centre_id = pt.centre_id', 'INNER');
      $this->db->join('city_master cm2', 'cm2.id = cm.centre_city', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, CONCAT(am.agency_name, " (", am.agency_code, " - ", IF(am.allow_exam_types="Bulk/Individual", "Regular", am.allow_exam_types),")") AS agency_name, am.agency_code, cm.centre_name, cm.centre_username, cm2.city_name');

      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency')); }

      $this->load->view('iibfbcbf/agency/payment_receipt_agency',$data);
    }

    function payment_receipt_candidate_listing_agency($enc_pt_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Payment Receipt : Candidate List';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id), 'pt.id, pt.agency_id, pt.centre_id, pt.amount');

      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency')); }

      $this->load->view('iibfbcbf/agency/payment_receipt_candidate_listing_agency',$data);
    }

    function get_payment_receipt_candidate_listing_data_ajax()
    {
      $table = 'iibfbcbf_payment_transaction pt';
      
      $column_order = array('pt.id', 'CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""), " (", cand.training_id, ")") AS DispCandidateName', 'cand.training_id', 'me.exam_fee', 'me.candidate_id'); //SET COLUMNS FOR SORT
      
      $column_search = array('CONCAT(cand.salutation, " ", cand.first_name, IF(cand.middle_name != "", CONCAT(" ", cand.middle_name), ""), IF(cand.last_name != "", CONCAT(" ", cand.last_name), ""), " (", cand.training_id, ")")', 'cand.training_id', 'me.exam_fee'); //SET COLUMN FOR SEARCH
      $order = array('pt.id' => 'DESC', 'me.candidate_id' => 'DESC'); // DEFAULT ORDER

      $enc_pt_id = trim($this->security->xss_clean($this->input->post('enc_pt_id')));
      $pt_id = url_decode($enc_pt_id);

      $WhereForTotal = "WHERE pt.id = '".$pt_id."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE pt.id = '".$pt_id."' ";        
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }    
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT	
      
      $join_qry = " INNER JOIN iibfbcbf_member_exam me ON FIND_IN_SET(me.member_exam_id, pt.exam_ids)";
      $join_qry .= " INNER JOIN iibfbcbf_batch_candidates cand ON cand.candidate_id = me.candidate_id";
      
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
        $row[] = $Res['DispCandidateName'];
        $row[] = $Res['training_id'];
        $row[] = $Res['exam_fee'];
        
        $btn_str = ' <div class="text-center no_wrap"> ';
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_candidate_agency/'.url_encode($Res['id']).'/'.url_encode($Res['candidate_id'])).'" class="btn btn-success btn-xs" title="Candidate Payment Receipt"><i class="fa fa-money"></i></a> ';
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
    }

    function payment_receipt_candidate_agency($enc_pt_id=0, $enc_candidate_id=0)
    {
      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);
      
      $data['enc_candidate_id'] = $enc_candidate_id;
      $candidate_id = url_decode($enc_candidate_id);

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Candidate Payment Receipt';

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access payment receipt module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $this->db->join('iibfbcbf_member_exam me', 'FIND_IN_SET(me.member_exam_id, pt.exam_ids)', 'INNER');
      $this->db->join('iibfbcbf_batch_candidates cand', 'cand.candidate_id = me.candidate_id', 'INNER');
      $this->db->join('iibfbcbf_exam_medium_master mm', 'mm.medium_code = me.exam_medium AND mm.exam_code = me.exam_code', 'LEFT');
      $this->db->join('iibfbcbf_exam_centre_master ecm', 'ecm.centre_code = me.exam_centre_code AND ecm.exam_name = me.exam_code', 'LEFT');
      $data['payment_data'] = $payment_data = $this->master_model->getRecords("iibfbcbf_payment_transaction pt", array('pt.id'=>$pt_id, 'cand.candidate_id'=>$candidate_id), 'pt.id, pt.agency_id, pt.centre_id, pt.exam_ids, pt.receipt_no, pt.gateway, pt.transaction_no, pt.UTR_no, pt.amount, pt.date, pt.exam_period, pt.status, me.member_exam_id, me.candidate_id, me.exam_fee, me.exam_centre_code, cand.salutation, cand.first_name, cand.middle_name, cand.last_name, cand.regnumber, cand.training_id, cand.email_id, mm.medium_description, ecm.centre_name');
      
      if(count($payment_data) == 0) { redirect(site_url('iibfbcbf/agency/transaction_details_agency/payment_receipt_candidate_listing_agency/'.$enc_pt_id)); }

      $this->load->view('iibfbcbf/agency/payment_receipt_candidate_agency',$data);
    }

    /******** START : FUNCTION TO GET THE POP UP FOR UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/
    function get_payment_details_modal_ajax()
    {
      $flag = "error";
      $response = "";

			if(isset($_POST) && count($_POST) > 0 && $this->login_user_type == 'centre')
			{
        $enc_payment_id = $this->security->xss_clean($this->input->post('enc_payment_id'));
        $payment_id = url_decode($enc_payment_id);
        
        $data['form_utr_no'] = $this->security->xss_clean($this->input->post('form_utr_no'));
        $data['form_payment_date'] = $this->security->xss_clean($this->input->post('form_payment_date'));
        //$data['form_gst_centre_id'] = $this->security->xss_clean($this->input->post('form_gst_centre_id'));
        $data['utr_slip_error'] = $this->security->xss_clean($this->input->post('utr_slip_error'));
        $data['form_utr_no_error'] = $this->security->xss_clean($this->input->post('form_utr_no_error'));
        $data['form_payment_date_error'] = $this->security->xss_clean($this->input->post('form_payment_date_error'));
        $data['form_utr_slip_error'] = $this->security->xss_clean($this->input->post('form_utr_slip_error'));
        //$data['form_gst_centre_id_error'] = $this->security->xss_clean($this->input->post('form_gst_centre_id_error'));
        
        $data['payment_data'] = $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id' => $payment_id, 'pt.UTR_no' => 'IIBFBCBF-TEMP-UTR-NO'), 'pt.id, pt.exam_ids, pt.amount, pt.exam_code, pt.exam_period', array('pt.id'=>'DESC'));
        if(count($payment_data) > 0)
				{
					$this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
          $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
          $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
          $data['agency_centre_data'] = $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array('cm.centre_id'=>$this->login_agency_or_centre_id), 'cm.centre_id, am.agency_id, cm.centre_name, cm.gst_no AS CentreGST, cm.centre_state, sm.state_no, sm.state_name, sm.exempt, cm.centre_city, cm.invoice_address, city.city_name, am.agency_name, am.agency_code, am.gst_no AS AgencyGST');
          
          $agency_id = '0';
          if(count($agency_centre_data) > 0) { $agency_id = $agency_centre_data[0]['agency_id']; }
          $data['active_exam_data'] = $active_exam_data = $this->Iibf_bcbf_model->get_exam_activation_details(url_encode($payment_data[0]['exam_code']), $agency_id,'bulk'); 
          
          if(count($active_exam_data) > 0)
          { 
            if($active_exam_data[0]['exam_period'] == $payment_data[0]['exam_period'])
            {
              $flag = "success";
              $response = $this->load->view('iibfbcbf/agency/inc_update_payment_details_modal',$data, true);
            }
          }
        }				
      }      

      $result = array();
      $result['flag'] = $flag;
      $result['response'] = $response;
      echo json_encode($result);
    }/******** END : FUNCTION TO GET THE POP UP FOR UPDATE THE PAYMENT DETAILS FOR GENERATED Proforma INVOICE ********/

    /******** START : VALIDATION FUNCTION TO CHECK UTR NUMBER EXIST OR NOT ********/
    public function validation_check_utr_exist($str='',$type=0)//$type : 0=>Server side, 1=>Ajax
    {  
      $return_val_ajax = 'false';
			if(isset($_POST) && $_POST['utr_no'] != "")
			{
        if($type == '1') 
        { 
          $utr_no = $this->security->xss_clean($this->input->post('utr_no'));          
        }
        else 
        { 
          $utr_no = $str;
        }

        $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction', array('utr_no' => $utr_no), 'utr_no');
      
        if(count($payment_data) == 0)
        {
          $return_val_ajax = 'true';
        }
			}

      if($type == '1') { echo $return_val_ajax; }
      else 
      { 
        if($return_val_ajax == 'true') { return TRUE; } 
        else if($_POST['utr_no'] != "")
        {
          $this->form_validation->set_message('validation_check_utr_exist','This UTR No is already present. Please enter unique utr no.');
          return false;
        }
      }
    }/******** END : VALIDATION FUNCTION TO CHECK UTR NUMBER EXIST OR NOT ********/

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

    /******** START : FUNCTION TO DISPLAY THE Proforma INVOICE ********/
    public function proforma_invoice($enc_pt_id=0)
    {
      $this->db->join('state_master sm', 'sm.state_code = cm.centre_state', 'LEFT');
      $this->db->join('city_master city', 'city.id = cm.centre_city', 'LEFT');
      $this->db->join('iibfbcbf_agency_master am', 'am.agency_id = cm.agency_id', 'LEFT');
      if($this->login_user_type == 'agency') { $this->db->where('cm.agency_id', $this->login_agency_or_centre_id); }
      else if($this->login_user_type == 'centre') { $this->db->where('cm.centre_id', $this->login_agency_or_centre_id); }
      $agency_centre_data = $this->master_model->getRecords('iibfbcbf_centre_master cm', array(), 'cm.centre_id, cm.agency_id, cm.centre_name, cm.gst_no, cm.centre_state, cm.centre_city, cm.centre_address1, cm.centre_address2, cm.centre_address3, cm.centre_address4, cm.centre_district, sm.state_name AS CentreState, sm.state_no, am.agency_name, am.agency_code, city.city_name');

      $pt_id = url_decode($enc_pt_id);
      $this->db->join('exam_invoice ei', 'ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag', 'INNER');
      if($this->login_user_type == 'agency') { $this->db->where('pt.agency_id', $this->login_agency_or_centre_id); }
      else if($this->login_user_type == 'centre') { $this->db->where('pt.centre_id', $this->login_agency_or_centre_id); }
      $payment_data = $this->master_model->getRecords('iibfbcbf_payment_transaction pt', array('pt.id'=>$pt_id, 'pt.status'=>'3', 'pt.payment_mode'=>'Bulk'), 'pt.id, pt.exam_ids, pt.exam_code, pt.exam_period, pt.gateway, pt.amount, pt.UTR_no, pt.UTR_slip_file, pt.pay_count, ei.fresh_fee, ei.rep_fee, ei.fresh_count, ei.rep_count, ei.fee_amt, ei.cgst_rate, ei.cgst_amt, ei.sgst_rate, ei.sgst_amt, ei.cs_total, ei.igst_rate, ei.igst_amt, ei.igst_total, ei.transaction_no');
      
      if(count($payment_data) == 0)
      {
        $this->session->set_flashdata('error','Record not found');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }
      //$join_qry .= " LEFT JOIN exam_invoice ei ON ei.receipt_no = pt.receipt_no AND ei.exam_code = pt.exam_code AND ei.exam_period = pt.exam_period AND ei.app_type = pt.pg_flag";
      //_pa($payment_data,1);
      /* echo $pt_id; exit; */
      
      $fresh_cnt = $payment_data[0]['fresh_count'];
      $repeater_cnt = $payment_data[0]['rep_count'];
      $wordamt = convert_amount_into_words(intval($payment_data[0]['amount']));
      $final_total = $payment_data[0]['amount'];	
      $total_fresh_fee = $fresh_cnt * $payment_data[0]['fresh_fee'];
			$total_rep_fee = $repeater_cnt * $payment_data[0]['rep_fee'];			
			
			$igst_total = $sgst_amt = $cs_amnt = '';			
			if($agency_centre_data[0]['centre_state'] == 'MAH')
      {	
				$cs_amnt = $payment_data[0]['cgst_amt'];
				$sgst_amt = $payment_data[0]['sgst_amt'];
      }
      elseif($agency_centre_data[0]['centre_state'] != 'MAH')
      {
        $igst_total = $payment_data[0]['igst_amt'];
      }
			
			$date_of_invoice = date("d-m-Y"); 
			$address = rtrim($agency_centre_data[0]['centre_address1'],",");
      if($agency_centre_data[0]['centre_address2'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address2']),","); }
      if($agency_centre_data[0]['centre_address3'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address3']),","); }
      if($agency_centre_data[0]['centre_address4'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_address4']),","); }
      if($agency_centre_data[0]['centre_district'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['centre_district']),","); }
      if($agency_centre_data[0]['city_name'] != "") { $address .= ", ".rtrim(trim($agency_centre_data[0]['city_name']),","); }
			
      $data['amount_in_word'] = $wordamt;
      $data['invoice_no'] = 'TEMP_INVOICE_NO';
      $data['date_of_invoice'] = $date_of_invoice;
      $data['transaction_no'] = $payment_data[0]['transaction_no'];
      $data['recepient_name'] = $agency_centre_data[0]['centre_name']." (".$agency_centre_data[0]['agency_name']." - ".$agency_centre_data[0]['agency_code'].")";
      $data['address'] = $address;
      $data['centre_state'] = $agency_centre_data[0]['CentreState'];
      $data['centre_state_code'] = $agency_centre_data[0]['state_no'];
      $data['centre_gstn'] = $agency_centre_data[0]['gst_no'];
      $data['fresh_fee_amount'] = number_format_upto2($payment_data[0]['fresh_fee']);
      $data['rep_fee_amount'] = number_format_upto2($payment_data[0]['rep_fee']);
      $data['discount_amt'] = '-';
      $data['net_amt'] = '-';
      $data['ste_code'] = $agency_centre_data[0]['centre_state'];
      $data['cgst_rate'] = number_format_upto2($payment_data[0]['cgst_rate']);
      $data['cgst_amt'] = $cs_amnt;
      $data['sgst_rate'] = number_format_upto2($payment_data[0]['sgst_rate']);
      $data['sgst_amt'] = $sgst_amt;
      $data['final_total'] = $final_total;
      $data['igst_total'] = $igst_total;
      $data['invoice_number'] ='TEMP_INVOICE_NO';
      $data['igst_rate'] = number_format_upto2($payment_data[0]['igst_rate']);
      $data['total_fresh_fee'] = $total_fresh_fee;
      $data['total_rep_fee'] = $total_rep_fee;
      $data['fresh_count'] = $fresh_cnt;
      $data['rep_count'] = $repeater_cnt;
      
			$this->load->view('iibfbcbf/agency/proforma_invoice_agency',$data);			
		}/******** END : FUNCTION TO DISPLAY THE Proforma INVOICE ********/

    /******** START : FUNCTION TO DISPLAY THE Candidates Admit Card ********/
    public function admit_cards($enc_pt_id=0)
    { 

      $data['enc_pt_id'] = $enc_pt_id;
      $pt_id = url_decode($enc_pt_id);

      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access Admit Card module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $data['act_id'] = "Transaction Details";
      $data['sub_act_id'] = "Transaction Details";
      $data['page_title'] = 'IIBF - BCBF Agency Candidate Admitcard : Candidate Candidate Admitcard List';
      
      $this->load->view('iibfbcbf/agency/admit_cards',$data);     
    }/******** END : FUNCTION TO DISPLAY THE Candidates Admit Card ********/

    /******** START : SERVER SIDE DATATABLE CALL FOR GET THE ADMITCARD DATA ********/
    public function get_admitcard_data_ajax()
    {
      $table = 'iibfbcbf_admit_card_details bc'; 
      $GroupBy = "";

      $pt_id_enc = trim($this->security->xss_clean($this->input->post('pt_id_enc'))); 
      $pt_id = url_decode($pt_id_enc);
       
      $column_order = array('bc.admitcard_id', 'bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no AS no_of_candidates', 'ca.training_id', 'bc.mam_nam_1', 'ca.mobile_no', 'ca.email_id', 'bc.inscd', 'bc.centre_code', 'bc.exam_date'); //SET COLUMNS FOR SORT
      $column_search = array('bc.centre_name', 'bc.exm_cd', 'bc.exm_prd', 'bc.mem_mem_no'); //SET COLUMN FOR SEARCH 
      $order = array('bc.admitcard_id' => 'DESC'); // DEFAULT ORDER
      
      $WhereForTotal = "WHERE bc.pt_id = '".$pt_id."'";  //DEFAULT WHERE CONDITION FOR ALL RECORDS 
      $Where = "WHERE bc.pt_id = '".$pt_id."'";   

      /* if($this->login_user_type == 'agency')
      {
        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$this->login_agency_or_centre_id), 'agency_code');

        $WhereForTotal .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' "; // AND bc.exm_cd IN('1037','1038') //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' "; //  AND bc.exm_cd IN('1037','1038')
      }
      else if($this->login_user_type == 'centre')
      {
        $center_data = $this->master_model->getRecords('iibfbcbf_centre_master', array('centre_id'=>$this->login_agency_or_centre_id), 'centre_username,agency_id');

        $agency_data = $this->master_model->getRecords('iibfbcbf_agency_master', array('agency_id'=>$center_data[0]["agency_id"]), 'agency_code');

        $WhereForTotal .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; //DEFAULT WHERE CONDITION FOR ALL RECORDS 
        $Where .= " AND bc.inscd = '".$agency_data[0]["agency_code"]."' AND bc.centre_code = '".$center_data[0]["centre_username"]."' "; // AND bm.exam_code = '1038'
      } */      
      
      if($_POST['search']['value']) // DATATABLE SEARCH
      {
        $Where .= " AND (";
        for($i=0; $i<count($column_search); $i++) { $Where .= $column_search[$i]." LIKE '%".( custom_safe_string($_POST['search']['value']) )."%' ESCAPE '!' OR "; }
        $Where = substr_replace( $Where, "", -3 );
        $Where .= ')';
      }
 
      
      //CUSTOM SEARCH
      $s_centre = trim($this->security->xss_clean($this->input->post('s_centre')));
      if($s_centre != "") { $Where .= " AND bc.centre_code = '".$s_centre."'"; } 
      $s_term = trim($this->security->xss_clean($this->input->post('s_term')));
      if($s_term != "") { $Where .= " AND (bc.centre_name LIKE '%".$s_term."%' OR bc.mem_mem_no LIKE '%".$s_term."%' OR bc.exm_cd LIKE '%".$s_term."%' OR bc.exm_prd LIKE '%".$s_term."%' OR ca.training_id LIKE '%".$s_term."%' OR bc.mam_nam_1 LIKE '%".$s_term."%' OR ca.mobile_no LIKE '%".$s_term."%' OR ca.email_id LIKE '%".$s_term."%' OR bc.inscd LIKE '%".$s_term."%')"; } 
      /* 
      $s_payment_status = trim($this->security->xss_clean($this->input->post('s_payment_status')));
      if($s_payment_status != "") { $Where .= " AND bc.status = '".$s_payment_status."'"; } */
      
      
      $Order = ""; //DATATABLE SORT
      if(isset($_POST['order'])) { $explode_arr = explode("AS",$column_order[$_POST['order']['0']['column']]);  $Order = "ORDER BY ".$explode_arr[0]." ".$_POST['order']['0']['dir']; }
      else if(isset($order)) { $Order = "ORDER BY "; foreach($order as $o_key=>$o_val) { $Order .= $o_key." ".$o_val.", "; } $Order = rtrim($Order,", "); }
      
      $Limit = ""; if ($_POST['length'] != '-1' ) { $Limit = "LIMIT ".intval( $_POST['start'] ).", ".intval( $_POST['length'] ); } // DATATABLE LIMIT 
      
      $join_qry = " LEFT JOIN iibfbcbf_batch_candidates ca ON ca.regnumber = bc.mem_mem_no"; 

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

        $btn_str = ' <div class="text-centerx no_wrap" style="width: 60px; margin: 0 auto;"> '; 
        $btn_str .= ' <a target="_blank" href="'.site_url('iibfbcbf/agency/transaction_details_agency/download_admitcard_pdf/'.url_encode($Res['admitcard_id'])).'" class="btn btn-success btn-xs" title="Download"><i class="fa fa-download"></i></a> ';

        $row[] = $Res['centre_name'];
        $row[] = $Res['exm_cd'];
        $row[] = $Res['exm_prd'];
        $row[] = $Res['no_of_candidates'];      
        $row[] = $Res['training_id'];      
        $row[] = $Res['mam_nam_1'];      
        $row[] = $Res['mobile_no'];      
        $row[] = $Res['email_id'];      
        $row[] = $Res['inscd'];      
        
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

    public function download_admitcard_pdf($enc_admitcard_id = '0')
    {
      if($this->login_user_type == 'centre' && $this->allow_exam_types == 'CSC')
      {
        $this->session->set_flashdata('error','You do not have permission to access Admit Card module');
        redirect(site_url('iibfbcbf/agency/transaction_details_agency'));
      }

      $this->Iibf_bcbf_model->download_admit_card_pdf_single($enc_admitcard_id, 'download');
    }

    /* GET ALL FOLDER LISTING FROM REQUIRED FOLDER  */
    function get_directory_list($dir_name)
    {
      return $this->array_sort_ascending(directory_map('./'.$dir_name, 1)); // This is use to get all folders and files from current directory excluding subfolders
    }
    
    /* SORT ARRAY IN ASCENDING ORDER USING VALUES NOT KEY */
    function array_sort_ascending($array)
    {
      if($array != "") { sort($array); /* sort() - sort arrays in ascending order. rsort() - sort arrays in descending order. */ }
      return $array;
    }
    
    /* RECURSIVE FUNCTION TO DELETE ALL SUB FILES AND FOLDER FROM REQUIRED FOLDER */
    function rmdir_recursive($dir) 
    {
      foreach(scandir($dir) as $file) 
      {
        if ('.' === $file || '..' === $file) continue;
        if (is_dir("$dir/$file")) 
        {
          $this->rmdir_recursive("$dir/$file");
        }
        else unlink("$dir/$file");
      }
      rmdir($dir);
    }


 } ?>  