<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DipcertDRAExam extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->library('upload');
    $this->load->model('Master_model');
    $this->load->model('log_model');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set("memory_limit", "-1");
    $this->load->model('master_model');
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
  }

  public function get_client_ip_email()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    return $ipaddress;
  }

  public function DRATransactions()
  {
    // Base query structure (without ORDER BY, LIMIT, or OFFSET)
    $base_payment_query = 'SELECT dra_payment_transaction.id,dra_payment_transaction.proformo_invoice_no,dra_payment_transaction.transaction_no,dra_payment_transaction.receipt_no,dra_payment_transaction.exam_period,dra_payment_transaction.amount,dra_payment_transaction.date,dra_payment_transaction.status,dra_payment_transaction.transaction_details,dra_accerdited_master.institute_name FROM dra_payment_transaction
                           JOIN dra_accerdited_master ON dra_payment_transaction.inst_code = dra_accerdited_master.institute_code 
                           WHERE exam_code = 1036 
                           AND exam_period = 20   
                           AND status != 1';

    // 1. Modify the Query with LIMIT and OFFSET
    $payment_query = $base_payment_query . ' ORDER BY id DESC';

    // 2. Execute Query and Process Data
    $payment_result   = $this->db->query($payment_query);
    $arr_payment_data = $payment_result->result_array();  
   
    // 3. Pass Data and Pagination Links to the view
    $data['arr_payment_data'] = $arr_payment_data;

    $this->load->view('dra_payment_list', $data);
  }

  public function validateMemberFee()
  {
    $this->load->view('member_payment_list');
  }

  public function getPaymentDataAjax()
  {
      // DataTables parameters from POST request
      $start = $this->input->post('start', TRUE);  // Offset
      $length = $this->input->post('length', TRUE); // Limit (Per page)
      $draw = $this->input->post('draw', TRUE);    // DataTables draw counter

      // Initialize an empty array for the response
      $response = [
          "draw" => intval($draw),
          "recordsTotal" => 0,
          "recordsFiltered" => 0,
          "data" => []
      ];

      // --- 1. Base Query for ALL records (for filtering/total count) ---
      $base_payment_query = 'SELECT * FROM payment_transaction 
                             WHERE exam_code IN (11,19,78,79,119,151,153,154,156,157,163,165,166,220) 
                             AND date >= "2025-10-01" 
                             AND pay_type = 2 
                             AND status = 1';

      // --- 2. Count Total Records (unfiltered) ---
      $total_count_query = "SELECT COUNT(*) as total FROM ({$base_payment_query}) AS subquery";
      $total_records = $this->db->query($total_count_query)->row()->total;
      $response["recordsTotal"] = $total_records;
      
      // --- 3. Filtering/Searching ---
      $search_value = $this->input->post('search')['value'];
      $where_clause = '';
      
      if (!empty($search_value)) {
          // You MUST list all searchable columns here
          $searchable_columns = [
              'member_regnumber', 'amount', 'exam_code', 'date' // Only use columns present in payment_transaction
          ];
          
          $search_terms = [];
          foreach ($searchable_columns as $col) {
              $search_terms[] = "CAST({$col} AS CHAR) LIKE '%" . $this->db->escape_like_str($search_value) . "%'";
          }
          $where_clause = ' AND (' . implode(' OR ', $search_terms) . ')';
      }

      // --- 4. Final Query with Filtering, Ordering, Limit/Offset ---
      
      // Build the query to get filtered count
      $filtered_count_query = "SELECT COUNT(*) as total FROM ({$base_payment_query} {$where_clause}) AS subquery";
      $filtered_records = $this->db->query($filtered_count_query)->row()->total;
      $response["recordsFiltered"] = $filtered_records;


      // DataTables ordering (Default to ordering by 'id' if not specified)
      $order_column_index = $this->input->post('order')[0]['column'];
      $order_direction = $this->input->post('order')[0]['dir'];
      
      // NOTE: These columns refer to the visual order in the table, not necessarily DB columns.
      // We use actual DB column names for ordering the query.
      $db_columns = [
          'date', 'member_regnumber', 'member_regnumber', 'member_regnumber', 
          'member_regnumber', 'member_regnumber', 'member_regnumber', 'member_regnumber', 'member_regnumber', 'amount', 'amount', 'exam_code', 'date'
      ];

      $order_column = $db_columns[$order_column_index];
      
      $data_query = $base_payment_query . $where_clause . " ORDER BY {$order_column} {$order_direction} LIMIT {$length} OFFSET {$start}";
      
      $payment_result = $this->db->query($data_query);
      $arr_payment_data = $payment_result->result_array();
      
      // --- 5. Apply Lookups and Calculations (Your existing logic) ---
      if( count($arr_payment_data) > 0 )  
      {
          foreach ($arr_payment_data as $payment_data)  // Note: key is not strictly needed here
          {
              $final_paid_amount = $payment_data['amount'];
              $exam_code         = $payment_data['exam_code'];
              $member_number     = $payment_data['member_regnumber'];
              $payment_date      = date("Y-m-d", strtotime($payment_data['date']));

              // --- Initialize variables for the final output array (THE FIX) ---
              $elearning_flag    = '';
              $sub_el_count      = 0;
              $app_category      = 'B1_1';
              $member_category   = '';
              $total_exam_fee    = 0;
              

              // MEMBER_EXAM LOOKUP
              $member_exam_query = 'SELECT * FROM member_exam WHERE exam_code = '.$exam_code.' AND exam_period = 926 AND pay_status = 1 AND regnumber = "'.$member_number.'"'; 
              $arr_member_exam   = $this->db->query($member_exam_query)->result_array();
              
              if (count($arr_member_exam) > 0) {
                  $elearning_flag = $arr_member_exam[0]['elearning_flag'];
                  $sub_el_count   = $arr_member_exam[0]['sub_el_count']; 
              }

              // ELIGIBLE_MASTER LOOKUP
              $eligible_member_query = 'SELECT * FROM eligible_master WHERE exam_code = '.$exam_code.' AND eligible_period = 926 AND member_no = "'.$member_number.'"';
              $arr_eligible_member   = $this->db->query($eligible_member_query)->result_array();
              
              if ( isset($arr_eligible_member[0]['app_category']) && $arr_eligible_member[0]['app_category'] != '' ) {
                  $app_category = $arr_eligible_member[0]['app_category']; 
              }

              if ( $app_category == 'R' ) {
                  $app_category = 'B1_1';
              }

              // MEMBER_REGISTRATION LOOKUP
              $member_query = 'SELECT * FROM member_registration WHERE regnumber = "'.$member_number.'" ORDER BY regid DESC'; 
              $arr_member   = $this->db->query($member_query)->result_array();
              
              if (count($arr_member) > 0 ) {
                  $member_category = $arr_member[0]['registrationtype'];
              }

              // FEE_MASTER LOOKUP
              $fee_query = 'SELECT * FROM fee_master WHERE exam_code = '.$exam_code.' AND exam_period = 926 AND group_code = "'.$app_category.'" AND member_category = "'.$member_category.'" AND "'.$payment_date.'" BETWEEN fr_date AND to_date'; 
              
              $arr_fee   = $this->db->query($fee_query)->result_array();

              $exam_fee             = 0;
              $elearning_fee        = 0;
              $base_exam_fee        = 0;
              $base_elearning_fee   = 0;

              $base_exam_fee_gst      = 0;
              $base_elearning_fee_gst = 0;

              $total_elearning_fee  = 0;

              if( count($arr_fee) > 0 )  
              {
                $base_exam_fee        = $arr_fee[0]['fee_amount'];
                $base_elearning_fee   = $arr_fee[0]['elearning_fee_amt'] != '' ? $arr_fee[0]['elearning_fee_amt'] : 0;

                $base_exam_fee_gst      = $arr_fee[0]['igst_amt'];
                $base_elearning_fee_gst = $arr_fee[0]['elearning_igst_amt'] != '' ? $arr_fee[0]['elearning_igst_amt'] : 0;

                $exam_fee             = $arr_fee[0]['cs_tot'];
                $elearning_fee        = $arr_fee[0]['elearning_cs_amt_total'];
              }

              if ($elearning_flag == 'Y') {
                if ($elearning_fee > 0 && $sub_el_count > 0)  {
                    $total_elearning_fee = $elearning_fee * $sub_el_count;
                }
              }

              $total_exam_fee = $exam_fee + $total_elearning_fee;
              
              // --- 6. Format Data for DataTables ---
              $rowCSS = ($final_paid_amount != $total_exam_fee) ? 'style="background-color: red;"' : '';

              if ( ($exam_code == 19 || $exam_code == 153 || $exam_code == 156) && $app_category == 'B1_1' ) {
                $rowCSS = 'style="background-color: red;"';
              }

              if ( $exam_code == 119 ) 
              {
                $new_eligible_member_query = 'SELECT * FROM eligible_master WHERE exam_status = "P" AND exam_code = 19 AND eligible_period = 926 AND member_no = "'.$member_number.'"';                
                $arr_new_eligible_member = $this->db->query($new_eligible_member_query)->result_array();
                
                if ( count($arr_new_eligible_member) > 0 ) {
                   $rowCSS = 'style="background-color: red;"';
                }
              }
              
              // Create the final output array for the DataTables row
              $response['data'][] = [
                  '<span '.$rowCSS.'>' . $member_number . '</span>',
                  '<span '.$rowCSS.'>' . $member_category . '</span>', // member_category is now defined
                  '<span '.$rowCSS.'>' . $app_category . '</span>',     // app_category is now defined
                  '<span '.$rowCSS.'>' . $elearning_flag . '</span>',  // elearning_flag is now defined
                  '<span '.$rowCSS.'>' . $sub_el_count . '</span>',    // sub_el_count is now defined
                  '<span '.$rowCSS.'>' . $base_exam_fee . '</span>',
                  '<span '.$rowCSS.'>' . $base_elearning_fee . '</span>',
                  '<span '.$rowCSS.'>' . $base_exam_fee_gst . '</span>',
                  '<span '.$rowCSS.'>' . $base_elearning_fee_gst . '</span>',
                  '<span '.$rowCSS.'>' . $final_paid_amount . '</span>',
                  '<span '.$rowCSS.'>' . $total_exam_fee . '</span>',  // total_exam_fee is now defined
                  '<span '.$rowCSS.'>' . $exam_code . '</span>',
                  '<span '.$rowCSS.'>' . $payment_data['date'] . '</span>',
              ];
          }  
      }

      // --- 7. Output JSON Response ---
      $this->output
          ->set_content_type('application/json')
          ->set_output(json_encode($response));
  }


  public function genarate_exam_invoice($invoice_id)
  { 
    $CI = & get_instance();
    $invoice_info = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_id));
    
    if($invoice_info[0]['gstin_no']!='' && $invoice_info[0]['gstin_no']!=0)
    {
      $gstno=$invoice_info[0]['gstin_no'];
    }
    else
    {
      $gstno='NA';
    }
    
    $mem_info = $CI->master_model->getRecords('member_registration',array('regnumber'=>$invoice_info[0]['member_no']),'firstname,middlename,lastname');
    $member_name = $mem_info[0]['firstname']." ".$mem_info[0]['middlename']." ".$mem_info[0]['lastname'];
    
    if($invoice_info[0]['state_of_center'] == 'MAH'){
      $wordamt = amtinword($invoice_info[0]['cs_total']);
    }elseif($invoice_info[0]['state_of_center'] != 'MAH'){
      $wordamt = amtinword($invoice_info[0]['igst_total']);
    }
    
    $date_of_invoice = date("d-m-Y", strtotime($invoice_info[0]['date_of_invoice']));
    
    
    if($invoice_info[0]['exam_code'] == 340 || $invoice_info[0]['exam_code'] == 3400){
      $exam_code = 34;
    }elseif($invoice_info[0]['exam_code'] == 580 || $invoice_info[0]['exam_code'] == 5800){
      $exam_code = 58;
    }elseif($invoice_info[0]['exam_code'] == 1600 || $invoice_info[0]['exam_code'] == 16000){
      $exam_code = 160;
    }
    elseif($invoice_info[0]['exam_code'] == 200){
      $exam_code = 20;
    }elseif($invoice_info[0]['exam_code'] == 1770 || $invoice_info[0]['exam_code'] == 17700){
      $exam_code =177;
    }
    elseif($invoice_info[0]['exam_code'] == 1750){
      $exam_code =175;
    }
    elseif($invoice_info[0]['exam_code'] == 590){
      $exam_code =59;
    }
    elseif($invoice_info[0]['exam_code'] == 810){
      $exam_code =81;
    }elseif($invoice_info[0]['exam_code'] == 2027){
      $exam_code =1017;
    }
    else{
      $exam_code = $invoice_info[0]['exam_code'];
    }
    
    if($exam_code > 0){
      $exam_name_code = $exam_code;
    }else{
      $exam_name_code = $invoice_info[0]['exam_code'];
    }
    $exam_name = $CI->master_model->getRecords('exam_invoice_name',array('exam_code'=>$exam_name_code),'exam_name');
    if($exam_name[0]['exam_name'] != ''){
      $invoice_exname = $exam_name[0]['exam_name'];
    }else{
      $invoice_exname = '-';
    }
    
    $exam_period = '';
    $exam = $CI->master_model->getRecords('member_exam',array('regnumber'=>$invoice_info[0]['member_no'],'exam_code'=>$invoice_info[0]['exam_code'],'exam_period'=>$invoice_info[0]['exam_period']));
    
    if($exam[0]['examination_date'] != '' && $exam[0]['examination_date'] != "0000-00-00")
    {
      $ex_period = $CI->master_model->getRecords('special_exam_dates',array('examination_date'=>$exam[0]['examination_date']));
      if(count($ex_period))
      {
        $exam_period = $ex_period[0]['period']; 
      }
    }else{
      $exam_period = $exam[0]['exam_period'];
    }
    
    
    $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black
    
    
    //imageline ($im,   x1,  y1, x2, y2, color); 
    imageline ($im,   20,  20, 980, 20, $black); // line-1
    imageline ($im,   20,  980, 980, 980, $black); // line-2
    imageline ($im,   20,  20, 20, 980, $black); // line-3
    imageline ($im,   980, 20, 980, 980, $black); // line-4
    imageline ($im,   20,  160, 980, 160, $black); // line-5
    imageline ($im,   20,  200, 980, 200, $black); // line-6
    imageline ($im,   20,  480, 980, 480, $black); // line-7
    imageline ($im,   20,  520, 980, 520, $black); // line-8
    imageline ($im,   20,  580, 980, 580, $black); // line-9
    imageline ($im,   20,  850, 980, 850, $black); // line-10
    imageline ($im,   650,  200, 650, 480, $black); // line-11
    imageline ($im,   85,  520, 85, 850, $black); // line-12
    imageline ($im,   500,  520, 500, 850, $black); // line-13
    imageline ($im,   650,  520, 650, 850, $black); // line-14
    imageline ($im,   785,  520, 785, 850, $black); // line-15
    imageline ($im,   860,  520, 860, 850, $black); // line-16
    imageline ($im,   40,  880, 625, 880, $black); // line-17
    
    
    
    //imagestring(image,font,x,y,string,color);
    imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
    imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
    imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
    imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
    
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "ORIGINAL FOR RECIPIENT", $black);
    imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
    imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
    imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
    imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
    imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
    imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
    imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
    imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
    imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
    imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
    imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
    
    
    imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
    imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
    imagestring($im, 3, 670,  300, "GSTIN No: 27AAATT3309D1ZS", $black);
    
    
    imagestring($im, 3, 40,  530, "Sr.No.", $black);
    imagestring($im, 3, 118,  530, "Description of Service", $black);
    imagestring($im, 3, 535,  530, "Accounting ", $black);
    imagestring($im, 3, 535,  542, "code", $black);
    imagestring($im, 3, 535,  554, "of Service", $black);
    imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
    imagestring($im, 3, 808,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
    
    imagestring($im, 3, 40,  600, "1", $black);
    imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
    imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
    imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
    imagestring($im, 3, 820,  600, 1, $black);
    imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
    
    if($invoice_info[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 118,  700, "CGST", $black);
      imagestring($im, 3, 118,  720, "SGST", $black);
      imagestring($im, 3, 118,  740, "IGST", $black);
      
      imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
      imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
      imagestring($im, 3, 690,  740, "-", $black);
      
      imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
      imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
      imagestring($im, 3, 900,  740, "-", $black);
    }
    
    if($invoice_info[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 118,  700, "CGST", $black);
      imagestring($im, 3, 118,  720, "SGST", $black);
      imagestring($im, 3, 118,  740, "IGST", $black);
      
      imagestring($im, 3, 690,  700, "-", $black);
      imagestring($im, 3, 690,  720, "-", $black);
      imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
      
      imagestring($im, 3, 900,  700, "-", $black);
      imagestring($im, 3, 900,  720, "-", $black);
      imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
    }
    
    if($invoice_info[0]['total_el_amount'] > 0){
      imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
      imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
    }
    
    imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
    if($invoice_info[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
    }elseif($invoice_info[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
    }
    
    imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
    imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260,  900, "Y/N", $black);
    imagestring($im, 3, 300,  900, "NO", $black);
    imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
    imagestring($im, 3, 280,  930, "% ---", $black);
    imagestring($im, 3, 350,  930, "Rs.---", $black);
    
    imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
    imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
    
    $savepath = base_url()."uploads/examinvoice/user/";
    $ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
    $imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
    $update_data = array('invoice_image' => $imagename);
    $CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_id));
    
    imagepng($im,"uploads/examinvoice/user/".$imagename);
    $png = @imagecreatefromjpeg('assets/images/sign.jpg');
    $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
    $jpeg = @imagecreatefromjpeg("uploads/examinvoice/user/".$imagename);
    
    //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
    @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
    @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
    imagepng($im, 'uploads/examinvoice/user/'.$imagename);
    imagedestroy($im);
    
    
    
    /*********************** Image for supplier *************************************/
    
    $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black
    
    
    //imageline ($im,   x1,  y1, x2, y2, color); 
    imageline ($im,   20,  20, 980, 20, $black); // line-1
    imageline ($im,   20,  980, 980, 980, $black); // line-2
    imageline ($im,   20,  20, 20, 980, $black); // line-3
    imageline ($im,   980, 20, 980, 980, $black); // line-4
    imageline ($im,   20,  160, 980, 160, $black); // line-5
    imageline ($im,   20,  200, 980, 200, $black); // line-6
    imageline ($im,   20,  480, 980, 480, $black); // line-7
    imageline ($im,   20,  520, 980, 520, $black); // line-8
    imageline ($im,   20,  580, 980, 580, $black); // line-9
    imageline ($im,   20,  850, 980, 850, $black); // line-10
    imageline ($im,   650,  200, 650, 480, $black); // line-11
    imageline ($im,   85,  520, 85, 850, $black); // line-12
    imageline ($im,   500,  520, 500, 850, $black); // line-13
    imageline ($im,   650,  520, 650, 850, $black); // line-14
    imageline ($im,   785,  520, 785, 850, $black); // line-15
    imageline ($im,   860,  520, 860, 850, $black); // line-16
    imageline ($im,   40,  880, 625, 880, $black); // line-17
    
    
    
    //imagestring(image,font,x,y,string,color);
    imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
    imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
    imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
    imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400,  170, "TAX INVOICE CUM RECEIPT", $black);
    
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
    imagestring($im, 5, 670, 220, "DUPLICATE FOR SUPPLIER", $black);
    imagestring($im, 3, 40,  260, "Member no: ".$invoice_info[0]['member_no'], $black);
    imagestring($im, 3, 40,  280, "Member name: ".$member_name, $black);
    imagestring($im, 3, 40,  300, "Center code: ".$invoice_info[0]['center_code'], $black);
    imagestring($im, 3, 40,  320, "Center name: ".$invoice_info[0]['center_name'], $black);
    imagestring($im, 3, 40,  340, "State of center: ".$invoice_info[0]['state_name'], $black);
    imagestring($im, 3, 40,  360, "State Code: ".$invoice_info[0]['state_code'], $black);
    imagestring($im, 3, 40,  380, "Exam code: ".$exam_code, $black);
    imagestring($im, 3, 40,  400, "Exam name: ".$invoice_exname, $black);
    imagestring($im, 3, 40,  420, "Exam Period: ".$exam_period, $black);
    imagestring($im, 3, 40,  440, "Transaction Number : ".$invoice_info[0]['transaction_no'], $black);
    imagestring($im, 3, 40,  460, "GSTIN / Unique ID: ".$gstno, $black);
    
    
    imagestring($im, 3, 670,  260, "Invoice Number: ".$invoice_info[0]['invoice_no'], $black);
    imagestring($im, 3, 670,  280, "Date: ".$date_of_invoice, $black);
    imagestring($im, 3, 670,  300, "GSTN No: 27AAATT3309D1ZS", $black);
    
    

    imagestring($im, 3, 40,  530, "Sr.No.", $black);
    imagestring($im, 3, 118,  530, "Description of Service", $black);
    imagestring($im, 3, 535,  530, "Accounting ", $black);
    imagestring($im, 3, 535,  542, "code", $black);
    imagestring($im, 3, 535,  554, "of Service", $black);
    imagestring($im, 3, 660,  530, "Rate per unit(Rs.)", $black);
    imagestring($im, 3, 808,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total(Rs.)", $black);
    
    imagestring($im, 3, 40,  600, "1", $black);
    imagestring($im, 3, 118,  600, "Conduction of Exam", $black);
    imagestring($im, 3, 550,  600, $invoice_info[0]['service_code'], $black);
    imagestring($im, 3, 690,  600, $invoice_info[0]['fee_amt'], $black);
    imagestring($im, 3, 820,  600, 1, $black);
    imagestring($im, 3, 900,  600, $invoice_info[0]['fee_amt'], $black);
    
    if($invoice_info[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 118,  700, "CGST", $black);
      imagestring($im, 3, 118,  720, "SGST", $black);
      imagestring($im, 3, 118,  740, "IGST", $black);
      
      imagestring($im, 3, 690,  700, $invoice_info[0]['cgst_rate']."%", $black);
      imagestring($im, 3, 690,  720, $invoice_info[0]['sgst_rate']."%", $black);
      imagestring($im, 3, 690,  740, "-", $black);
      
      imagestring($im, 3, 900,  700, $invoice_info[0]['cgst_amt'], $black);
      imagestring($im, 3, 900,  720, $invoice_info[0]['sgst_amt'], $black);
      imagestring($im, 3, 900,  740, "-", $black);
    }
    
    if($invoice_info[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 118,  700, "CGST", $black);
      imagestring($im, 3, 118,  720, "SGST", $black);
      imagestring($im, 3, 118,  740, "IGST", $black);
      
      imagestring($im, 3, 690,  700, "-", $black);
      imagestring($im, 3, 690,  720, "-", $black);
      imagestring($im, 3, 690,  740, $invoice_info[0]['igst_rate']."%", $black);
      
      imagestring($im, 3, 900,  700, "-", $black);
      imagestring($im, 3, 900,  720, "-", $black);
      imagestring($im, 3, 900,  740, $invoice_info[0]['igst_amt'], $black);
      
      
    }
    
    if($invoice_info[0]['total_el_amount'] > 0){
        imagestring($im, 3, 118,  760, "Total Elearning amount", $black);
        imagestring($im, 3, 900,  760, $invoice_info[0]['total_el_amount'], $black);
      }
    
    imagestring($im, 3, 535,  830, "Total(Rs.) ", $black);
    if($invoice_info[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 900,  830, $invoice_info[0]['cs_total'], $black);
    }elseif($invoice_info[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 900,  830, $invoice_info[0]['igst_total'], $black);
    }
    
    imagestring($im, 3, 40,  860, "Amount in words : ".$wordamt." Only", $black);
    imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260,  900, "Y/N", $black);
    imagestring($im, 3, 300,  900, "NO", $black);
    imagestring($im, 3, 40,  930, "% of Tax payable under", $black);
    imagestring($im, 3, 280,  930, "% ---", $black);
    imagestring($im, 3, 350,  930, "Rs.---", $black);
    
    imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
    imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
    
    $savepath = base_url()."uploads/examinvoice/supplier/";
    $ino = str_replace("/","_",$invoice_info[0]['invoice_no']);
    $imagename = $invoice_info[0]['member_no']."_".$ino.".jpg";
    imagepng($im,"uploads/examinvoice/supplier/".$imagename);
    $png = @imagecreatefromjpeg('assets/images/sign.jpg');
    $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
    $jpeg = @imagecreatefromjpeg("uploads/examinvoice/supplier/".$imagename);
    
    //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
    @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
    @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
    imagepng($im, 'uploads/examinvoice/supplier/'.$imagename);
    imagedestroy($im);
    
    return $attachpath = "uploads/examinvoice/user/".$imagename;
  }

  // Below Function generate DRA agency registration invoice
  public function genarate_dra_invoice($invoice_no){ 
    $CI = & get_instance();
    
    $CI->db->select('exam_invoice.*,payment_transaction.*, agency_center.*, exam_invoice.gstin_no AS invoice_gstin_no, agency_center.gstin_no AS center_gstin_no,agency_center.invoice_flag AS center_invoice_flag');
    $CI->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no');
    $CI->db->join('agency_center','agency_center.center_id = payment_transaction.ref_id');
    $record = $CI->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
    
    
    
    /*$CI->db->join('agency_center','agency_center.center_id = agency_center_payment.center_id');
    $CI->db->join('exam_invoice','exam_invoice.invoice_id = agency_center_payment.invoice_id');
    $record = $CI->master_model->getRecords('agency_center_payment',array('agency_center_payment.receipt_no'=>$receipt_no)); */
    
    if (isset($record[0]['tds_amt']) && $record[0]['tds_amt'] != '' && $record[0]['tds_amt'] > 0) {
      $tdsAmount = $record[0]['tds_amt']; 
    } else {
      $tdsAmount = 0;
    }
    
    if($record[0]['state_of_center'] == 'MAH'){
      $total = $record[0]['cs_total']+$tdsAmount;
      $wordamt = custom_amtinword($total);
      $fee_amt =  $record[0]['fee_amt'];
    }
    if($record[0]['state_of_center'] != 'MAH'){
      $total = $record[0]['igst_total']+$tdsAmount;
      $wordamt = custom_amtinword($total);
      $fee_amt =  $record[0]['fee_amt'];
    }
    
    $city_name = "";
    $name_of_center = "";
    $name_of_agency = "";
    if($record[0]['center_invoice_flag'] == 'AS')
    {
      $CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
      $ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
      $name_of_center = $record[0]['city'];
      $name_of_agency = $ag_add[0]['inst_name'];
      $address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
      $address1 = $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
      $state = $record[0]['state_name'];
      $state_code = $record[0]['state_code'];
      
      $dra_inst_reg = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'main_city');
      // || $dra_inst_reg[0]['main_city'] > 0
      if(is_numeric($record[0]['city'])){
        $city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
        $city_name = $city[0]['city_name'];
      }
      else{
        $city = $CI->master_model->getRecords('city_master',array('id'=>$dra_inst_reg[0]['main_city']),'city_name');

        $city_name = $city[0]['main_city'];
      }

    }elseif($record[0]['center_invoice_flag'] == 'CS')
    {
      $CI->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
      $ag_add = $CI->master_model->getRecords('dra_inst_registration',array('id'=>$record[0]['agency_id']),'inst_name,main_address1,main_address2,main_address3,main_address4');
      $name_of_center = $record[0]['city'];
      $name_of_agency = $ag_add[0]['inst_name'];
      $address = $record[0]['location_address']." ".$record[0]['address1']." ".$record[0]['address2'];
      $address1 = $record[0]['address3']." ".$record[0]['address4'];
      $state = $record[0]['state_name'];
      $state_code = $record[0]['state_code'];
      
      // || $record[0]['city'] > 0
      if(is_numeric($record[0]['city'])){
        $city = $CI->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
        $city_name = $city[0]['city_name'];
      }
      else{
        $city_name = $record[0]['city'];
      }
    }
    
    $accreditation_fee = 0;
    $dilligance_fee    = 0;

    if ($fee_amt == 22000) 
    {
      $accreditation_fee = 12000;
      $dilligance_fee    = 10000;
    } 
    elseif ($fee_amt == 12000) 
    {
      $accreditation_fee = 12000;
      $dilligance_fee    = 0;
    } 
    elseif ($fee_amt == 10000) 
    {
      $accreditation_fee = 0;
      $dilligance_fee    = 10000;
    }

    // if ($accreditation_fee == 22000) {
    //   $accreditation_fee = 12000;
    //   $dilligance_fee    = 10000;
    // }


    // create image for recipeint
    //imagecreate(width, height);
    $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black
    
    
    //imageline ($im,   x1,  y1, x2, y2, color); 
    imageline ($im,   20,  20, 980, 20, $black); // line-1
    imageline ($im,   20,  980, 980, 980, $black); // line-2
    imageline ($im,   20,  20, 20, 980, $black); // line-3
    imageline ($im,   980, 20, 980, 980, $black); // line-4
    imageline ($im,   20,  160, 980, 160, $black); // line-5
    imageline ($im,   20,  200, 980, 200, $black); // line-6
    imageline ($im,   20,  480, 980, 480, $black); // line-7
    imageline ($im,   20,  520, 980, 520, $black); // line-8
    imageline ($im,   20,  580, 980, 580, $black); // line-9
    imageline ($im,   20,  850, 980, 850, $black); // line-10
    imageline ($im,   650,  200, 650, 480, $black); // line-11
    imageline ($im,   85,  520, 85, 850, $black); // line-12
    imageline ($im,   500,  520, 500, 850, $black); // line-13
    imageline ($im,   650,  520, 650, 850, $black); // line-14
    imageline ($im,   785,  520, 785, 850, $black); // line-15
    imageline ($im,   860,  520, 860, 850, $black); // line-16
    imageline ($im,   40,  880, 625, 880, $black); // line-17
    
    
    
    //imagestring(image,font,x,y,string,color); 
    imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
    imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
    imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
    imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
    
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
    imagestring($im, 5, 670,  220, "ORIGINAL FOR RECIPIENT", $black);
    imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
    imagestring($im, 3, 40,  280, "Address: ".$address, $black);
    imagestring($im, 3, 40,  300, $address1, $black);
    imagestring($im, 3, 40,  320, "State: ".$state, $black);
    imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
    imagestring($im, 3, 40,  360, "GST No: ".$record[0]['invoice_gstin_no'], $black);
    imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
    
    
    imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
    imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
    if($record[0]['gstin_no'] != '' && $record[0]['gstin_no'] != 0){
      $gstn = $record[0]['gstin_no'];
    }else{
      $gstn = "-";
    }
    imagestring($im, 3, 670,  300, "GSTIN - 27AAATT3309D1ZS ", $black);
    
    
    imagestring($im, 3, 40,  530, "Sr.No", $black);
    imagestring($im, 3, 118,  530, "Description of Service", $black);
    imagestring($im, 3, 535,  530, "Accounting ", $black);
    imagestring($im, 3, 535,  542, "code", $black);
    imagestring($im, 3, 535,  554, "of Service", $black);
    imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total", $black);
    
    // imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration(Diligence)", $black);
    // imagestring($im, 3, 535,  596, "999799", $black);
    // imagestring($im, 3, 700,  596, $fee_amt, $black);
    // imagestring($im, 3, 815,  596, "1", $black);
    // imagestring($im, 3, 900,  596, $fee_amt, $black); 
    // imagestring($im, 3, 535,  820, "Total", $black); 
    
    imagestring($im, 3, 118,  596, "Charges for DRA Agency accreditation", $black);
    imagestring($im, 3, 535,  596, "999799", $black);
    imagestring($im, 3, 700,  596, $accreditation_fee, $black);
    imagestring($im, 3, 815,  596, "1", $black);
    imagestring($im, 3, 900,  596, $accreditation_fee, $black); 
    imagestring($im, 3, 535,  820, "Total", $black); 
    
    /*------------------------------------------------*/

    imagestring($im, 3, 118,  616, "Charges for DRA Center Diligence", $black);
    imagestring($im, 3, 535,  616, "999799", $black);
    imagestring($im, 3, 700,  616, $dilligance_fee, $black);
    imagestring($im, 3, 815,  616, '1', $black);
    imagestring($im, 3, 900,  616, $dilligance_fee, $black); 
    imagestring($im, 3, 535,  850, "Total", $black);

    /*------------------------------------------------*/

    
    imagestring($im, 3, 60,  630, "1", $black);
    imagestring($im, 3, 118,  630,$city_name , $black);
    imagestring($im, 3, 260,  630, "CGST ", $black);
    imagestring($im, 3, 260,  650, "SGST ", $black);
    imagestring($im, 3, 260,  670, "IGST ", $black);
    
    if($record[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 700,  630, "9% ", $black);
      imagestring($im, 3, 700,  650, "9% ", $black);
      imagestring($im, 3, 700,  666, "- ", $black);
      
      imagestring($im, 3, 900,  630, $record[0]['cgst_amt'], $black);
      imagestring($im, 3, 900,  650, $record[0]['sgst_amt'], $black);
      imagestring($im, 3, 900,  670, "- ", $black);
    }
    if($record[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 700,  630, "- ", $black);
      imagestring($im, 3, 700,  650, "- ", $black);
      imagestring($im, 3, 700,  670, "18% ", $black);
      
      imagestring($im, 3, 900,  630, "- ", $black);
      imagestring($im, 3, 900,  650, "- ", $black);
      imagestring($im, 3, 900,  670, $record[0]['igst_amt'], $black);
    }
    
    
    
    imagestring($im, 3, 900,  820, $total, $black); 
    
    imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
    imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260,  900, "Y/N", $black);
    imagestring($im, 3, 300,  900, "NO", $black);
    imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
    imagestring($im, 3, 280,  930, "% ---", $black);
    imagestring($im, 3, 350,  930, "Rs.---", $black);
    
    imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
    imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
    
    $savepath = base_url()."uploads/drainvoice/user/";
    //$imagename = 'new_dra.jpg';
    $ino = str_replace("/","_",$record[0]['invoice_no']);
    // $imagename = $record[0]['center_id']."_".$ino.".jpg";
    $imagename = $ino.".jpg";
    $update_data = array('invoice_image' => $imagename);
    $CI->master_model->updateRecord('exam_invoice',$update_data,array('invoice_id'=>$invoice_no));
    
    imagepng($im,"uploads/drainvoice/user/".$imagename);
    $png = @imagecreatefromjpeg('assets/images/sign.jpg');
    $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
    $jpeg = @imagecreatefromjpeg("uploads/drainvoice/user/".$imagename);
    
    //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
    @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
    @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
    imagepng($im, 'uploads/drainvoice/user/'.$imagename);
    imagedestroy($im);
    
    // create image for supplier
    //imagecreate(width, height);
    $im = @imagecreate(1000, 1000) or die("Cannot Initialize new GD image stream");
    $background_color = imagecolorallocate($im, 255, 255, 255); // white
    $black = imagecolorallocate($im, 0, 0, 0); // black
    
    //imageline ($im,   x1,  y1, x2, y2, color); 
    imageline ($im,   20,  20, 980, 20, $black); // line-1
    imageline ($im,   20,  980, 980, 980, $black); // line-2
    imageline ($im,   20,  20, 20, 980, $black); // line-3
    imageline ($im,   980, 20, 980, 980, $black); // line-4
    imageline ($im,   20,  160, 980, 160, $black); // line-5
    imageline ($im,   20,  200, 980, 200, $black); // line-6
    imageline ($im,   20,  480, 980, 480, $black); // line-7
    imageline ($im,   20,  520, 980, 520, $black); // line-8
    imageline ($im,   20,  580, 980, 580, $black); // line-9
    imageline ($im,   20,  850, 980, 850, $black); // line-10
    imageline ($im,   650,  200, 650, 480, $black); // line-11
    imageline ($im,   85,  520, 85, 850, $black); // line-12
    imageline ($im,   500,  520, 500, 850, $black); // line-13
    imageline ($im,   650,  520, 650, 850, $black); // line-14
    imageline ($im,   785,  520, 785, 850, $black); // line-15
    imageline ($im,   860,  520, 860, 850, $black); // line-16
    imageline ($im,   40,  880, 625, 880, $black); // line-17
    
    
    //imagestring(image,font,x,y,string,color);
    imagestring($im, 5, 100,  40, "INDIAN INSTITUTE OF BANKING & FINANCE", $black);
    imagestring($im, 3, 100,  60, "ISO 21001:2018 Certified", $black);
    imagestring($im, 3, 100,  80, "(CINU9111OMH1928GAP1391)", $black);
    imagestring($im, 3, 100,  100, "Registered office Kohinoor City, Commercial - II,  Tower 1, 2nd Floor, Kirole Road,", $black);
    imagestring($im, 3, 100,  120, "Off LBS Marg, Kurla(West), Mumbai - 400 070 , Maharashtra", $black);
    imagestring($im, 3, 100,  140, "www.iibf.org.in", $black);
    imagestring($im, 5, 400,  170, "Tax Invoice cum receipt", $black);
    
    imagestring($im, 5, 40,  220, "Details of service recipient", $black);
    imagestring($im, 5, 670,  220, "DUPLICATE FOR SUPPLIER", $black);
    imagestring($im, 3, 40,  260, "Name of Agency:".$name_of_agency, $black);
    imagestring($im, 3, 40,  280, "Address: ".$address, $black);
    imagestring($im, 3, 40,  300, $address1, $black);
    imagestring($im, 3, 40,  320, "State: ".$state, $black);
    imagestring($im, 3, 40,  340, "State Code: ".$state_code, $black);
    imagestring($im, 3, 40,  360, "GST No: ".$record[0]['invoice_gstin_no'], $black);
    imagestring($im, 3, 40,  380, "Transaction Number : ".$record[0]['transaction_no'], $black);
    
    
    imagestring($im, 3, 670,  260, "Invoice Number: ".$record[0]['invoice_no'], $black);
    imagestring($im, 3, 670,  280, "Date: ".date("d-m-Y", strtotime($record[0]['date_of_invoice'])), $black);
    imagestring($im, 3, 670,  300, "GSTIN : 27AAATT3309D1ZS", $black);
    
    
    imagestring($im, 3, 40,  530, "Sr.No", $black);
    imagestring($im, 3, 118,  530, "Description of Service", $black);
    imagestring($im, 3, 535,  530, "Accounting ", $black);
    imagestring($im, 3, 535,  542, "code", $black);
    imagestring($im, 3, 535,  554, "of Service", $black);
    imagestring($im, 3, 660,  530, "Rate per unit(Rs)", $black);
    imagestring($im, 3, 808,  530, "Unit", $black);
    imagestring($im, 3, 900,  530, "Total", $black);
    
    // imagestring($im, 3, 118,  596, "Charges for DRA accreditation registration(Diligence)", $black);
    // imagestring($im, 3, 535,  596, "999799", $black);
    // imagestring($im, 3, 700,  596, $fee_amt, $black);
    // imagestring($im, 3, 815,  596, "1", $black);
    // imagestring($im, 3, 900,  596, $fee_amt, $black); 
    // imagestring($im, 3, 535,  820, "Total", $black); 

    imagestring($im, 3, 118,  596, "Charges for DRA Agency accreditation", $black);
    imagestring($im, 3, 535,  596, "999799", $black);
    imagestring($im, 3, 700,  596, $accreditation_fee, $black);
    imagestring($im, 3, 815,  596, "1", $black);
    imagestring($im, 3, 900,  596, $accreditation_fee, $black); 
    imagestring($im, 3, 535,  820, "Total", $black); 
    
    /*------------------------------------------------*/

    imagestring($im, 3, 118,  616, "Charges for DRA Center Diligence", $black);
    imagestring($im, 3, 535,  616, "999799", $black);
    imagestring($im, 3, 700,  616, $dilligance_fee, $black);
    imagestring($im, 3, 815,  616, '1', $black);
    imagestring($im, 3, 900,  616, $dilligance_fee, $black); 
    imagestring($im, 3, 535,  850, "Total", $black);

    /*------------------------------------------------*/
    
    
    imagestring($im, 3, 60,  630, "1", $black);
    imagestring($im, 3, 118,  630,$city_name, $black); 
    imagestring($im, 3, 260,  630, "CGST ", $black);
    imagestring($im, 3, 260,  650, "SGST ", $black);
    imagestring($im, 3, 260,  670, "IGST ", $black);
    
    if($record[0]['state_of_center'] == 'MAH'){
      imagestring($im, 3, 700,  630, "9% ", $black);
      imagestring($im, 3, 700,  650, "9% ", $black);
      imagestring($im, 3, 700,  670, "- ", $black);
      
      imagestring($im, 3, 900,  630, $record[0]['cgst_amt'], $black);
      imagestring($im, 3, 900,  650, $record[0]['sgst_amt'], $black);
      imagestring($im, 3, 900,  670, "- ", $black);
    }
    if($record[0]['state_of_center'] != 'MAH'){
      imagestring($im, 3, 700,  630, "- ", $black);
      imagestring($im, 3, 700,  650, "- ", $black);
      imagestring($im, 3, 700,  670, "18% ", $black);
      
      imagestring($im, 3, 900,  630, "- ", $black);
      imagestring($im, 3, 900,  650, "- ", $black);
      imagestring($im, 3, 900,  670, $record[0]['igst_amt'], $black);
    }
    
    
    
    imagestring($im, 3, 900,  820, $total, $black); 
    
    imagestring($im, 3, 40,  860, "Amount in words :".$wordamt." Only", $black);
    imagestring($im, 3, 40,  900, "Reverse charge applicable :", $black);
    imagestring($im, 3, 260,  900, "Y/N", $black);
    imagestring($im, 3, 300,  900, "NO", $black);
    imagestring($im, 3, 40,  930, "% of Tax payable under Reverse", $black);
    imagestring($im, 3, 280,  930, "% ---", $black);
    imagestring($im, 3, 350,  930, "Rs.---", $black);
    
    imagestring($im, 3, 650,  880, "For Indian Institute of Banking & Finance", $black);
    imagestring($im, 3, 720,  950, "Authorised Signatory", $black);
    
    
    
    $savepath = base_url()."uploads/drainvoice/supplier/";
    //$imagename = 'new_dra.jpg';
    imagepng($im,"uploads/drainvoice/supplier/".$imagename);
    $png = @imagecreatefromjpeg('assets/images/sign.jpg');
    $png2 = @imagecreatefromjpeg('assets/images/iibf_logo_short.jpg');
    $jpeg = @imagecreatefromjpeg("uploads/drainvoice/supplier/".$imagename);
    
    //imagecopyresampled(dst_image,src_image,dst_x,dst_y ,src_x,src_y,dst_w,dst_h,src_w,src_h);
    @imagecopyresampled($im, $png, 760, 900, 0, 0, 50, 50, 170, 124);
    @imagecopyresampled($im, $png2, 40, 40, 0, 0, 38, 65, 38, 65);
    imagepng($im, 'uploads/drainvoice/supplier/'.$imagename);
    imagedestroy($im);
    
    return $attachpath = "uploads/drainvoice/user/".$imagename; 
  } 

  public function update_payment_status($payment_id = false) 
  {
    if($payment_id > 0 && $payment_id != '' && $payment_id != false) 
    {
      $update_data = array('status' => 0);
      $payment_updated_status = $this->master_model->updateRecord('dra_payment_transaction',$update_data,array('id'=>$payment_id,'status'=>5));
      
      if ($payment_updated_status) 
      {
        $this->session->set_flashdata('success', 'Payment status successfully updated to Failed.');
        redirect(base_url() . 'DipcertDRAExam/DRATransactions');
      }
      else
      {
        $this->session->set_flashdata('error', 'Failed to update payment status. The record may not exist or the current status is not eligible for change.');
        redirect(base_url().'DipcertDRAExam/DRATransactions');
      }
    }
    else
    {
      $this->session->set_flashdata('error', 'Invalid payment details provided.');
      redirect(base_url().'DipcertDRAExam/DRATransactions');
    }
  }

}