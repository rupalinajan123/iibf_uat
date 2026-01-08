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
			
			if($examinfo['exam_code'] == 1017 || $examinfo['exam_code'] == 1018){
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') group by app_category,member_type,bulk_discount_flg,elearning_flag,base_fee");
				$exam_data = $query->result_array();
				
			}else{
				$query = $this->db->query("SELECT `app_category`,`base_fee`,count(`id`) AS total_cnt,(base_fee*count(`id`)) AS total_base,member_type,bulk_discount_flg,elearning_flag FROM `member_exam` WHERE `id` IN ('" . $regstr . "') AND bulk_isdelete = 0 group by base_fee,app_category,member_type,bulk_discount_flg,elearning_flag");
				$exam_data = $query->result_array();
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
				'no_unit_base'=>$no_unit_base
			);
			$this->load->view('bulk/bulk_common_view', $data);
		}
		else
		{
			$this->session->set_flashdata('error', 'Please select at least one candidate to pay');
			redirect(base_url() . 'bulk/BulkApply/exam_applicantlst/');
		}
	}
	
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
			);
			$this->load->view('bulk/bulk_common_view', $data);
		}//make nft payment
		elseif ($this->input->post('btnSubmit'))
		{	
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
					'date' => $this->input->post('payment_date') ,
					'pay_count' => count($bulk_mem_array) ,
					'status' => '3'
				);
				$pt_id = $this->master_model->insertRecord('bulk_payment_transaction', $insert_data, true);
				
				
				$log_title ="bulk payment transaction entry query";
				$log_message = serialize($insert_data);
				$rId = $pt_id;
				$regNo = $pt_id;
				$inst_id = $this->session->userdata('institute_id');
				//echo '<pre>',print_r($user_data),'</pre>';exit;
				bulk_storedUserActivity($log_title, $log_message,$inst_id ,$rId, $regNo);
		
				// update receipt_no
				$update_data = array(
					'receipt_no' => $pt_id
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
					
					//echo $this->db->last_query();
					//exit;
					
					
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
					$this->session->set_flashdata('success', 'Your payment has been successfully processed.');
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
