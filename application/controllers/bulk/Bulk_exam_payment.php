<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class Bulk_exam_payment extends CI_Controller
{
	public
	function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
		$this->load->helper('bulk_calculate_tds_discount_helper');
		$this->load->helper('bulk_proforma_invoice_helper');
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('UserModel');
		$this->load->model('master_model');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->model('log_model');
		$this->load->helper('general_helper');
		
	}
	public function make_payment()
	{
		
		if($this->input->post('chkmakepay'))
		{
			$examinfo = $this->session->userdata('exmCrdPrd');
			$this->session->set_userdata('mem_id', $this->input->post('chkmakepay'));
			if (count($this->input->post('chkmakepay')) > 0)
			{
				foreach($this->input->post('chkmakepay') as $row)
				{
					$new_arrayid[] = $row;
				}
			}
			if (count($new_arrayid) > 0)
			{
				$regstr = implode("','", $new_arrayid);
			}

			$this->session->set_userdata('selected_rows',$regstr); //Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
			
			if($examinfo['exam_code'] == 1017 || $examinfo['exam_code'] == 1018){
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') group by app_category,member_type,bulk_discount_flg,elearning_flag,base_fee");
				$exam_data = $query->result_array();
				
			}else{
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') AND bulk_isdelete = 0 group by base_fee,app_category,member_type,bulk_discount_flg,elearning_flag");
				$exam_data = $query->result_array();
				// echo $this->db->last_query();
			}
			
	
		  $unit_base_amt = array_column($exam_data, 'total_cnt'); // quantity
          $no_unit_base= array_sum($unit_base_amt);
			// get exam name
			$exam_name = $this->master_model->getRecords('exam_master', array(
				'exam_code' => $examinfo['exam_code']
			) , 'description');
			// get discount rate
			$discount_rate = $this->master_model->getRecords('bulk_exam_activation_master', array(
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'institute_code' => $this->session->userdata('institute_id')
			) , 'discount');
			// sum of all base amount
	       $base_amts = array_column($exam_data, 'total_base'); // base_fee*count(`id`) = total_base
		   $base_total = array_sum($base_amts); // primary total mount
		   
		   //calculate discount
			//$after_discount = calculate_discount($base_total, intval($discount_rate[0]['discount']));
			// GST
			$institude_state = $this->master_model->getRecords('bulk_accerdited_master', array(
				'institute_code' => $this->session->userdata('institute_id') ,
				'accerdited_delete' => 0
			) , 'ste_code');
			// apply discount on base amount
			//$base_amt_after_dsct = $base_total - $after_discount;
		
			
			if (!empty($institude_state))
			{
				if ($institude_state[0]['ste_code'] == 'MAH')
				{
					// set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $sgst_rate = $gst_amt = 0;
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					$gst_amount_rate = calculate_gst_rate($base_total, $cgst_rate);
					$gst_amount_rate1 = $gst_amount_rate * 2;
					$amt_after_gst = calculate_gst($base_total, $gst_amount_rate1);
					$this->session->set_userdata('total_after_gst',$amt_after_gst);
					$tax_type = 'Intra';
				}
				else
				{
					$igst_rate = $gst_amt = 0;
					$igst_rate = $this->config->item('igst_rate');
					$gst_amount_rate = calculate_gst_rate($base_total, $igst_rate);
					$amt_after_gst = calculate_gst($base_total, $gst_amount_rate);
					$this->session->set_userdata('total_after_gst',$amt_after_gst);
					$tax_type = 'Inter';
				}
			}

			if($this->session->userdata('institute_id') == "17171"){
				$gst_amount_rate = 0;
				$amt_after_gst = calculate_gst($base_total, $gst_amount_rate);
			} 
                                                                   
			$data = array(
				'middle_content' => 'bulk/bulk_payment_page',
				'exam_data' => $exam_data,
				'exam_name' => $exam_name,
				'discount_rate' => $discount_rate,
				'after_discount' => 0,
				'tax_type' => $tax_type,
				'gst_amt' =>$gst_amount_rate,
				'amount_after_gst' =>$amt_after_gst,
				'base_amt_after_dsct' => 0,
				'base_amt_total' => $base_total,
				'no_unit_base'=>$no_unit_base,
				'examinfo'=>$examinfo,//Priyanka D >> start DBFMOUPAYMENTCHANGE >> 10-july-25
			);
			$this->load->view('bulk/bulk_common_view', $data);
		}
		else
		{
			$this->session->set_flashdata('error', 'Please select at least one candidate to pay');
			redirect(base_url() . 'bulk/BulkApply/exam_applicantlst/');
		}
	}
	public function view_inst_pr_invoice(){ //Priyanka d >>DBFMOUPAYMENTCHANGE >> 10-july
			
		
		$regstr = $this->session->userdata('selected_rows');
		$examinfo = $this->session->userdata('exmCrdPrd');
		$preview_proforma = $this->session->userdata('preview_proforma');

		
		//echo'<pre>';print_r($preview_proforma);exit;
		if($examinfo['exam_code'] == 1017 || $examinfo['exam_code'] == 1018){
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') group by app_category,member_type,bulk_discount_flg,elearning_flag,base_fee");
				$exam_data = $query->result_array();
				
			}else{
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') AND bulk_isdelete = 0 group by base_fee,app_category,member_type,bulk_discount_flg,elearning_flag");
				$exam_data = $query->result_array();
				// echo $this->db->last_query();
			}
			
	
		  $unit_base_amt = array_column($exam_data, 'total_cnt'); // quantity
          $no_unit_base= array_sum($unit_base_amt);
			// get exam name
			$exam_name = $this->master_model->getRecords('exam_master', array(
				'exam_code' => $examinfo['exam_code']
			) , 'description');
			// get discount rate
			$discount_rate = $this->master_model->getRecords('bulk_exam_activation_master', array(
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'institute_code' => $this->session->userdata('institute_id')
			) , 'discount');
			// sum of all base amount
	       $base_amts = array_column($exam_data, 'total_base'); // base_fee*count(`id`) = total_base
		   $base_total = array_sum($base_amts); // primary total mount
		   
		   //calculate discount
			//$after_discount = calculate_discount($base_total, intval($discount_rate[0]['discount']));
			// GST
			$institude_state = $this->master_model->getRecords('bulk_accerdited_master', array(
				'institute_code' => $this->session->userdata('institute_id') ,
				'accerdited_delete' => 0
			) , 'ste_code');
		// apply discount on base amount
			//$base_amt_after_dsct = $base_total - $after_discount;
		

		
		$dsct_rate_amt=$preview_proforma['base_amt_after_dsct'];				 
		$tds_amt =  $preview_proforma['tds_amt'];
		$subtotal_after_tds = $preview_proforma['final_subtotal_after_tds'];
		$gst_rate_amt =  $preview_proforma['gst_rate_amt'];
		$base_fee_amt = $preview_proforma['base_fee_amt'];
		$tax_type = $preview_proforma['tax_type'];
		$tot_fee =$preview_proforma['total_fee'];;

		$UTRno='TEMP-UTR-IIBF';
		$txn_id = 'TEMP_INVOICE_NO';
		$bulk_mem_array = $this->session->userdata('mem_id');
		$inst_code = $this->session->userdata('institute_id');
		// get discount rate
		$discount_rate = $this->master_model->getRecords('bulk_exam_activation_master', array(
			'exam_code' => $examinfo['exam_code'],
			'exam_period' => $examinfo['exam_prd'],
			'institute_code' => $this->session->userdata('institute_id')
		) , 'discount');
			// Create transaction
			$bulk_payment_transaction = array(
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'amount' => $amount,
				'gateway' => 1, // 1= NEFT / RTGS
				'UTR_no' => $UTRno,
				//'UTR_slip_file' => $outpututrslip1,
				'inst_code' => $inst_code,
				'date' => $payment_date ,
				'pay_count' => count($bulk_mem_array) ,
				'status' => '3'
			);
			

			
			$all_discount = 0;
			$this->db->select('SUM(taken_discount) as tsum');
			$this->db->where_in('id',$bulk_mem_array);
			$taken_discount_sum = $this->master_model->getRecords('member_exam');
			if(isset($taken_discount_sum[0]['tsum'])){
			$all_discount =  $taken_discount_sum[0]['tsum'];
			}
			
		
			/******************* code added for GST ***************/
			$no_of_members_payment = count($bulk_mem_array);
			$cgst_rate = 0;
			$cgst_amt = 0;
			$sgst_rate = 0;
			$sgst_amt = 0;
			$igst_rate = 0;
			$igst_amt = 0;
			$cs_total = 0;
			$igst_total = 0;
			$cess = 0;
			$institude_state = $this->master_model->getRecords('bulk_accerdited_master', array(
				'institute_code' => $this->session->userdata('institute_id') ,
				'accerdited_delete' => 0
			) , 'ste_code,gstin_no');
			// get state name, state_no from state master by state code
			$bulkInstState = $this->master_model->getRecords('state_master', array(
				'state_code' => $institude_state[0]['ste_code'],
				'state_delete' => '0'
			));
			
				if ($tax_type == 'Intra')
				{
					if ($institude_state[0]['ste_code'] == 'MAH')
					{
						// set a rate (e.g 9%,9% or 18%)
						$cgst_rate = $this->config->item('cgst_rate');
						$sgst_rate = $this->config->item('sgst_rate');
						$cgst_amt = $gst_rate_amt;
						$sgst_amt = $gst_rate_amt;
						$cs_total = $this->session->userdata('total_after_gst');
					}
				}
				else
				{
					if ($institude_state[0]['ste_code'] !== 'MAH')
					{
						// set a rate (e.g 9%,9% or 18%)
						$igst_rate = $this->config->item('igst_rate');
						// set an amount as per rate
						// $igst_amt = $fee_amt * ($igst_rate / 100);
						$igst_amt = $gst_rate_amt;
						// set an total amount
						// $igst_total = $fee_amt + $igst_amt;
						$igst_total = $this->session->userdata('total_after_gst');
					}
				}
				$invoice_info = array(
					'pay_txn_id' => $pt_id,
					'receipt_no' => $pt_id,
					'exam_code' => $examinfo['exam_code'],
					'exam_period' => $examinfo['exam_prd'],
					'disc_rate'=>$discount_rate [0]['discount'],
					'disc_amt'=>$dsct_rate_amt,
					'tds_amt'=>$tds_amt,
					'institute_code' => $this->session->userdata('institute_id'),
					'institute_name' => $this->session->userdata('institute_name'),
					'app_type' => 'Z', // I for DRA Exam Invoice
					'tax_type' => $tax_type, // I for DRA Exam Invoice
					'service_code' => $this->config->item('exam_service_code'),
					'gstin_no' => $institude_state[0]['gstin_no'],
					'qty' => $no_of_members_payment,
					'state_code' => $bulkInstState[0]['state_no'],
					'state_name' => $bulkInstState[0]['state_name'],
					// 'invoice_no' => '',	// before payment it will be blank
					// 'invoice_image' => $invoice_name, // before payment it will be blank
					// 'date_of_invoice' => '', // before payment it will be blank
						'transaction_no' =>$UTRno, // before payment it will be blank
					
					'fee_amt' => $base_fee_amt,
					'cgst_rate' => $cgst_rate,
					'cgst_amt' => $cgst_amt,
					'sgst_rate' => $sgst_rate,
					'sgst_amt' => $sgst_amt,
					'igst_rate' => $igst_rate,
					'igst_amt' => $igst_amt,
					'cs_total' => $cs_total,
					'igst_total' => $igst_total,
					'disc_amt' => $all_discount,
					'cess' => $cess,
					'exempt' => $bulkInstState[0]['exempt'],
					'created_on' => date('Y-m-d H:i:s')
				);
				

		
		$data = array();
		
		$institute_info = $this->master_model->getRecords('bulk_accerdited_master',array('institute_code'=>$invoice_info['institute_code']),'institute_name,address1,address2,address3,address4,address5,address6,ste_code,gstin_no');
				
		$state_info = $this->master_model->getRecords('state_master',array('state_code'=>$institute_info[0]['ste_code']),'state_name,state_no');
		
		//$net_amt = $invoice_info[0]['fee_amt'] - $invoice_info[0]['disc_amt'];
		
		$net_amt = $invoice_info['fee_amt'];
		
		//$invoice_info['cs_total'] = ($invoice_info['cs_total']-($invoice_info['disc_amt']));
		//$invoice_info['igst_total'] = ($invoice_info['igst_total']-($invoice_info['disc_amt']));
		if($institute_info[0]['ste_code'] == 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info['cs_total']));
		}elseif($institute_info[0]['ste_code'] != 'MAH'){
			$wordamt = $this->pb_amtinword(intval($invoice_info['igst_total']));
		}
		
		$date_of_invoice = date("d-m-Y");//, strtotime($invoice_info[0]['created_on']));

		$address = $institute_info[0]['address1']." ".$institute_info[0]['address2']." ".$institute_info[0]['address3']." ".$institute_info[0]['address4']." ".$institute_info[0]['address5']." ".$institute_info[0]['address6'];
		
		$this->db->where('ptid',$invoice_info['pay_txn_id']);
		$query1 = $this->master_model->getRecords('bulk_member_payment_transaction','','memexamid');
		
		$this->db->where('exam_code',1015);
		$this->db->where('id',$query1[0]['memexamid']);
		$query2 = $this->master_model->getRecords('member_exam','','exam_center_code');
		
		$this->db->where('exam_name',1015);
		$this->db->where('center_code',$query2[0]['exam_center_code']);
		$center_name = $this->master_model->getRecords('center_master','','center_name');
		
		$data = array('wmt'=>$wordamt,'invoice_no'=>$invoice_info['invoice_no'],'date_of_invoice'=>$date_of_invoice,'transaction_no'=>'TEMP_TRN_NO','recepient_name'=>$institute_info[0]['institute_name'],'address'=>$address,'institute_state'=>$state_info[0]['state_name'],'institute_state_code'=>$state_info[0]['state_no'],'institute_gstn'=>$institute_info[0]['gstin_no'],'fee_amount'=>$invoice_info['fee_amt'],'discount_amt'=>$invoice_info['disc_amt'],'net_amt'=>number_format($net_amt, 2, '.', ''),'ste_code'=>$institute_info[0]['ste_code'],'cgst_rate'=>$invoice_info['cgst_rate'],'cgst_amt'=>$invoice_info['cgst_amt'],'sgst_rate'=>$invoice_info['sgst_rate'],'sgst_amt'=>$invoice_info['sgst_amt'],'cs_total'=>$invoice_info['cs_total'],'igst_total'=>$invoice_info['igst_total'],'invoice_number'=>$txn_id,'igst_rate'=>$invoice_info['igst_rate'],'igst_amt'=>$invoice_info['igst_amt'],'center_name'=>$center_name[0]['center_name'],'exam_code'=>$invoice_info['exam_code']); 
		//print_r($data);		
		$this->load->view('bulk/transaction/print_inst_receipt_proforma',$data);
		
	}

	 function pb_amtinword($amt){ //Priyanka D >> start DBFMOUPAYMENTCHANGE >> 10-july-25
	   $number = $amt;
	   $no = round($number);
	   $point = round($number - $no, 2) * 100;
	   $hundred = null;
	   $digits_1 = strlen($no);
	   $i = 0;
	   $str = array();
	   $words = array('0' => '', '1' => 'One', '2' => 'Two',
		'3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
		'7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
		'10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
		'13' => 'Thirteen', '14' => 'Fourteen',
		'15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
		'18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
		'30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
		'60' => 'Sixty', '70' => 'Seventy',
		'80' => 'Eighty', '90' => 'Ninety');
	   $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
	   while ($i < $digits_1) {
		 $divider = ($i == 2) ? 10 : 100;
		 $number = floor($no % $divider);
		 $no = floor($no / $divider);
		 $i += ($divider == 10) ? 1 : 2;
		 if ($number) {
			$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
			$hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
			$str [] = ($number < 21) ? $words[$number] .
				" " . $digits[$counter] . $plural . " " . $hundred
				:
				$words[floor($number / 10) * 10]
				. " " . $words[$number % 10] . " "
				. $digits[$counter] . $plural . " " . $hundred;
		 } else $str[] = null;
	  }
	  $str = array_reverse($str);
	  $result = implode('', $str);
	  $points = ($point) ?
		"." . $words[$point / 10] . " " . 
			  $words[$point = $point % 10] : '';
			
	 return $result; //Priyanka D >> end DBFMOUPAYMENTCHANGE >> 10-july-25
	}
	public function before_preview_profoma() { // Priyanka D >> start DBFMOUPAYMENTCHANGE >> 10-july-25
		$dsct_rate_amt=$this->input->post('dsct_rate_amt');
		$tds_amt = $this->input->post('tds_amt');
		$subtotal_after_tds =$this->input->post('final_subtotal_after_tds');
		$gst_rate_amt = $this->input->post('gst_rate_amt');
		$base_fee_amt =$this->input->post('base_fee_amt');
		$tax_type =$this->input->post('tax_type');

		$total_fee = $subtotal_after_tds;
		
		$examinfo = $this->session->userdata('exmCrdPrd');
		if($tds_amt=='' && $subtotal_after_tds=='')
		{
			$subtotal_after_tds=0;
			$tds_amt=0;
			$total_fee=$final_amt;
		}
		$preview_proforma = array(
				
				'total_fee' => $total_fee,
				'tds_amt' => $tds_amt,
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'subtotal_after_tds' =>  $subtotal_after_tds,
				'gst_rate_amt' => $gst_rate_amt,
				'base_fee_amt' => $base_fee_amt,
				'tax_type' => $tax_type,
				'base_amt_after_dsct' => $dsct_rate_amt,
			);
			
			$this->session->set_userdata('preview_proforma',$preview_proforma);
		echo 'ok';
		exit;

	} // Priyanka D >> end DBFMOUPAYMENTCHANGE >> 10-july-25
	public function make_neft()
	{
		
        $dsct_rate_amt=$this->input->post('base_amt_after_dsct');
		$tds_amt = $this->input->post('tds_amt');
		$subtotal_after_tds =$this->input->post('final_subtotal_after_tds');
		$final_amt=$this->input->post('final_amt');
		$gst_rate_amt = $this->input->post('gst_rate_amt');
		$base_fee_amt =$this->input->post('base_amt_tot');
		$tax_type =$this->input->post('tax_type');
		
		$total_fee = $subtotal_after_tds;
		$var_errors = '';
		$examinfo = $this->session->userdata('exmCrdPrd');
		if($tds_amt=='' && $subtotal_after_tds=='')
		{
			$subtotal_after_tds=0;
			$tds_amt=0;
			$total_fee=$final_amt;
		}
		
		//view summary 
		if ($this->input->post('Submit'))
		{ 
			
				$data = array(
				'middle_content' => 'bulk/bulk_make_neftpayment_page',
				'total_fee' => $total_fee,
				'tds_amt' => $tds_amt,
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'var_errors' => $var_errors,
				'tds_amt' => $tds_amt,
				'subtotal_after_tds' =>  $subtotal_after_tds,
				'gst_rate_amt' => $gst_rate_amt,
				'base_fee_amt' => $base_fee_amt,
				'tax_type' => $tax_type,
				'base_amt_after_dsct' => $dsct_rate_amt,
				'mou_flg'=>$this->session->userdata('mou_flg'), // Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
				'current_exam_code'=>$examinfo['exam_code'], // Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
			);
			$this->load->view('bulk/bulk_common_view', $data);
		}//make nft payment
		elseif ($this->input->post('btnSubmit'))
		{	
			//echo'<pre>';print_r($_POST);exit; 
		   $dsct_rate_amt=$this->input->post('base_amt_after_dsct');				 
			$tds_amt =  $this->input->post('tds_amt');
			$subtotal_after_tds = $this->input->post('final_subtotal_after_tds');
			$gst_rate_amt =  $this->input->post('gst_rate_amt');
			$base_fee_amt = $this->input->post('base_amt_tot');
			$tax_type = $this->input->post('tax_type');
			
			
			//$this->form_validation->set_rules('utr_no', 'NEFT / RTGS (UTR) Number', 'required|trim');
			$this->form_validation->set_rules('payment_date', 'Payment Date', 'required');
			
			//$this->form_validation->set_rules('utr_slip', 'UTR Slip', 'required');
			$UTRno='TEMP-UTR-IIBF';
			if ($this->form_validation->run() == TRUE)
			{
				
				//Priyanka D >> start DBFMOUPAYMENTCHANGE >> 10-july-25
					$payment_date = $this->input->post('payment_date');
					$examinfo = $this->session->userdata('exmCrdPrd'); 
					//echo'<pre>';print_r($_POST);exit;
					if($this->session->userdata('mou_flg')==1 && $examinfo['exam_code']==420) { 
						$payment_date = date('Y-m-d');
					}
				//Priyanka D >> end DBFMOUPAYMENTCHANGE >> 10-july-25
				
				$resp = array(
					'success' => 0,
					'error' => 0,
					'msg' => ''
				);
				if ($UTRno == '')
				{
					$var_errors = str_replace("<p>", "<span>", $var_errors);
					$var_errors = str_replace("</p>", "</span><br />", $var_errors);
				}
				
				// upload UTR slip
			/*	$outpututrslip1 = '';
				if ($this->input->post("hiddenutrslip") && $this->input->post("hiddenutrslip"))
				{
					$date = date('Y-m-d h:i:s');
					$inpututrslip = $this->input->post("hiddenutrslip");
					$tmp_utrslip = strtotime($date) . rand(0, 100);
					$outpututrslip = getcwd() . "/uploads/bulk_utr_slip/utrslip_" . $tmp_utrslip . ".jpg";
					$outpututrslip1 = base_url() . "uploads/bulk_utr_slip/utrslip_" . $tmp_utrslip . ".jpg";
					file_put_contents($outpututrslip, file_get_contents($inpututrslip));
				}*/
				$amount =$this->input->post('tot_fee');
				$bulk_mem_array = $this->session->userdata('mem_id');
				$inst_code = $this->session->userdata('institute_id');
			// get discount rate
			$discount_rate = $this->master_model->getRecords('bulk_exam_activation_master', array(
				'exam_code' => $examinfo['exam_code'],
				'exam_period' => $examinfo['exam_prd'],
				'institute_code' => $this->session->userdata('institute_id')
			) , 'discount');

			
				// Create transaction
				$insert_data = array(
				   'exam_code' => $examinfo['exam_code'],
				   'exam_period' => $examinfo['exam_prd'],
					'amount' => $amount,
					'gateway' => 1, // 1= NEFT / RTGS
					'UTR_no' => $UTRno,
					//'UTR_slip_file' => $outpututrslip1,
					'inst_code' => $inst_code,
					'date' => $payment_date , //Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
					'pay_count' => count($bulk_mem_array) ,
					'status' => '3',
					
				);

				$pt_id = $this->master_model->insertRecord('bulk_payment_transaction', $insert_data, true);
				//echo $this->db->last_query();exit;
				
				$log_title ="bulk payment transaction entry query";
				$log_message = serialize($insert_data);
				$rId = $pt_id;
				$regNo = $pt_id;
				$inst_id = $this->session->userdata('institute_id');
				//echo '<pre>',print_r($user_data),'</pre>';exit;
				bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
		
				// update receipt_no
				$update_data = array(
					'receipt_no' => $pt_id,
					'proformo_invoice_no'=>date('ymd').$pt_id//Priyanka D >>  DBFMOUPAYMENTCHANGE >> 10-july-25
				);
				$this->master_model->updateRecord('bulk_payment_transaction', $update_data, array(
					'id' => $pt_id
				));
				// insert the bulk member id in 'bulk_member_payment_transaction' table
				foreach($bulk_mem_array as $bulk_mem_id)
				{
					$insert_mpt_data = array(
						'memexamid' => $bulk_mem_id,
						'ptid' => $pt_id
					);
					$this->master_model->insertRecord('bulk_member_payment_transaction', $insert_mpt_data);
					// update status in bulk_member_exam table
					$updtmemexam_data = array(
						'pay_status' => 3
					);
					$this->master_model->updateRecord('member_exam', $updtmemexam_data, array(
						'id' => $bulk_mem_id
					));
					
				// update admit card table 
		    	$update_data = array(
					'remark' => 2
				);
				$this->master_model->updateRecord('admit_card_details', $update_data, array(
					'mem_exam_id' =>$bulk_mem_id,
					'remark' =>4));
				}
				
				$all_discount = 0;
				$this->db->select('SUM(taken_discount) as tsum');
				$this->db->where_in('id',$bulk_mem_array);
				$taken_discount_sum = $this->master_model->getRecords('member_exam');
				if(isset($taken_discount_sum[0]['tsum'])){
				$all_discount =  $taken_discount_sum[0]['tsum'];
				}
				
			
				/******************* code added for GST ***************/
				$no_of_members_payment = count($bulk_mem_array);
				$cgst_rate = 0;
				$cgst_amt = 0;
				$sgst_rate = 0;
				$sgst_amt = 0;
				$igst_rate = 0;
				$igst_amt = 0;
				$cs_total = 0;
				$igst_total = 0;
				$cess = 0;
				$institude_state = $this->master_model->getRecords('bulk_accerdited_master', array(
					'institute_code' => $this->session->userdata('institute_id') ,
					'accerdited_delete' => 0
				) , 'ste_code,gstin_no');
				// get state name, state_no from state master by state code
				$bulkInstState = $this->master_model->getRecords('state_master', array(
					'state_code' => $institude_state[0]['ste_code'],
					'state_delete' => '0'
				));
				if (!empty($institude_state))
				{ //echo 'Inn';
					if ($tax_type == 'Intra')
					{
						if ($institude_state[0]['ste_code'] == 'MAH')
						{
							// set a rate (e.g 9%,9% or 18%)
							$cgst_rate = $this->config->item('cgst_rate');
							$sgst_rate = $this->config->item('sgst_rate');
							$cgst_amt = $gst_rate_amt;
							$sgst_amt = $gst_rate_amt;
							$cs_total = $this->session->userdata('total_after_gst');
						}
					}
					else
					{
						if ($institude_state[0]['ste_code'] !== 'MAH')
						{
							// set a rate (e.g 9%,9% or 18%)
							$igst_rate = $this->config->item('igst_rate');
							// set an amount as per rate
							// $igst_amt = $fee_amt * ($igst_rate / 100);
							$igst_amt = $gst_rate_amt;
							// set an total amount
							// $igst_total = $fee_amt + $igst_amt;
							$igst_total = $this->session->userdata('total_after_gst');
						}
					}
					$invoice_insert_array = array(
						'pay_txn_id' => $pt_id,
						'receipt_no' => $pt_id,
						'exam_code' => $examinfo['exam_code'],
						'exam_period' => $examinfo['exam_prd'],
					  'disc_rate'=>$discount_rate [0]['discount'],
					  'disc_amt'=>$dsct_rate_amt,
					   'tds_amt'=>$tds_amt,
						'institute_code' => $this->session->userdata('institute_id'),
						'institute_name' => $this->session->userdata('institute_name'),
						'app_type' => 'Z', // I for DRA Exam Invoice
						'tax_type' => $tax_type, // I for DRA Exam Invoice
						'service_code' => $this->config->item('exam_service_code'),
						'gstin_no' => $institude_state[0]['gstin_no'],
						'qty' => $no_of_members_payment,
						'state_code' => $bulkInstState[0]['state_no'],
						'state_name' => $bulkInstState[0]['state_name'],
						// 'invoice_no' => '',	// before payment it will be blank
						// 'invoice_image' => $invoice_name, // before payment it will be blank
						// 'date_of_invoice' => '', // before payment it will be blank
						 'transaction_no' =>$UTRno, // before payment it will be blank
						
						'fee_amt' => $base_fee_amt,
						'cgst_rate' => $cgst_rate,
						'cgst_amt' => $cgst_amt,
						'sgst_rate' => $sgst_rate,
						'sgst_amt' => $sgst_amt,
						'igst_rate' => $igst_rate,
						'igst_amt' => $igst_amt,
						'cs_total' => $cs_total,
						'igst_total' => $igst_total,
						'disc_amt' => $all_discount,
						'cess' => $cess,
						'exempt' => $bulkInstState[0]['exempt'],
						'created_on' => date('Y-m-d H:i:s')
					);
					
					$this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
					
					/*echo $this->db->last_query();
					exit;*/
					
					
					$log_title ="bulk exam invoice entry query";
					$log_message = serialize($invoice_insert_array);
					$rId = $pt_id;
					$regNo = $pt_id;
					$inst_id = $this->session->userdata('institute_id');
					//echo '<pre>',print_r($user_data),'</pre>';exit;
					bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
					
					// function to get proforma invoice  -
					$attchpath_examinvoice =generate_bulk_proforma_examinvoice($pt_id);
			
					
			
					//	log_dra_user($log_title = "Add Bulk Exam Invoice Successful", $log_message = serialize($invoice_insert_array));
					/******************* eof code added for GST  ***************/
					$this->session->set_flashdata('success', 'Proforma Invoice Generated Successfully');
					redirect(base_url() . 'bulk/BulkApply/exam_applicantlst/?exCd=' . $examinfo['exam_code']);
				}
				else
				{
					$last_query = $this->db->last_query();
					
					$log_title ="bulk exam invoice entry query fail";
					$log_message = $last_query;
					$rId = $pt_id;
					$regNo = $pt_id;
					$inst_id = $this->session->userdata('institute_id');
					//echo '<pre>',print_r($user_data),'</pre>';exit;
					bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
					
					redirect(base_url() . 'bulk/BulkApply/examlist');
				}
			}
		}
	}
}
