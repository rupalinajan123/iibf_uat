<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jaiib_day_count extends CI_Controller 
{
			
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		 $this->load->model('Emailsending');
		$this->load->library('Excel');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}

	

	public function jaiib_count_mail_feb()
	{
		
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$get_date= $this->uri->segment(3);
		if(!empty($get_date))
		{
			 //$yesterday = $get_date;
			 $from_date = $to_date = $get_date;
			//$to_date  = date('Y-m-d');
		}
		else
		{
			/* $lastdate = date('Y-m-d');
			$exarr = explode("-",$lastdate);
			$first_date_month = $exarr[0]."-".$exarr[1]."-01 00:00:01";
			$last_date_month = $lastdate." 23:59:59"; */
			$from_date = '2021-02-01';
			$to_date  = '2021-02-28';//date('Y-m-d');
		    //$yesterday = $from_date.'/'.$to_date;
		}
		
		//payment count
		$select = 'exam_code , count(id) AS PaymentCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>'2','status' => '1'),$select);
		//,'DATE(date)'=>$yesterday
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//Invoice count
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		$this->db->where_in('exam_period' ,'121');
		$this->db->group_by('exam_code,exam_period'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice',array('app_type'=>'O'),$select);
		//,'DATE(date_of_invoice)'=>$yesterday
		$invoice_arr = array();
		if(count($invoice_data) > 0)
		{
			foreach($invoice_data as $invoice_res)
			{
				$invoice_arr[$invoice_res['exam_code']] = $invoice_res;
			}
		}
		
		//Member App Count
		$select = 'exam_code ,exam_period, count(id) AS AppCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr = array();
		if(count($app_data) > 0)
		{
			foreach($app_data as $app_res)
			{
				$app_arr[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		$this->db->where_in('exm_cd' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exm_prd' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->where('record_source !=', 'Bulk');
		$this->db->group_by('exm_cd,exm_prd'); 
		$admitcard_data = $this->Master_model->getRecords('admit_card_details',array('remark'=>'1'),$select);
		//,'DATE(created_on)'=>$yesterday
		$admitcard_arr = array();
		if(count($admitcard_data) > 0)
		{
			foreach($admitcard_data as $admitcard_res)
			{
				$admitcard_arr[$admitcard_res['exm_cd']] = $admitcard_res;
			}
		}
		
		//Member App Count with Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_y = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'Y'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_y = array();
		if(count($app_data_y) > 0)
		{
			foreach($app_data_y as $app_res)
			{
				$app_arr_y[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Member App Count with Non Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppNonElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_n = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'N'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_n = array();
		if(count($app_data_n) > 0)
		{
			foreach($app_data_n as $app_res)
			{
				$app_arr_n[$app_res['exam_code']] = $app_res;
			}
		}
		
		// Boundary cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS BoundaryCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$boundary_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$bcases_arr_n = array();
		if(count($boundary_cases) > 0)
		{
			foreach($boundary_cases as $boundary_cases_res)
			{
				$bcases_arr_n[$boundary_cases_res['exam_code']] = $boundary_cases_res;
			}
		}		
		
		// Refund cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS RefundCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('a.transaction_no' ,'');
		$this->db->where('b.status' ,'3');
		$this->db->where('DATE(b.date) >=', $from_date);
		$this->db->where('DATE(b.date) <=', $to_date);
		//$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$refund_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$refund_arr_n = array();
		if(count($refund_cases) > 0)
		{
			foreach($refund_cases as $refund_cases_res)
			{
				$refund_arr_n[$refund_cases_res['exam_code']] = $refund_cases_res;
			}
		}	
		
		//echo $this->db->last_query(); exit;
		$exam_arr = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB'));
		$final_str = '<html>
						<table style="border-collapse: collapse;margin: 0 auto;max-width: 900px; width: 100%;">
							<thead>
								<tr>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Sr</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Code</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Period</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Payment Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Invoice Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Admit Card Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Non Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Boundary Cases</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Refund Cases</th>
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1;
								foreach($exam_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $refundcases='0';
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; }
									
									if(array_key_exists($res, $refund_arr_n)) { $refundcases = $refund_arr_n[$res]['RefundCasesCount']; }
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">121</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_elr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_nonelr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$boundarycases.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$refundcases.'</td>
										</tr>';
									
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
				
		echo $final_str; exit;	
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
	}
							

							
	public function jaiib_count_mail_march()
	{
		
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$get_date= $this->uri->segment(3);
		if(!empty($get_date))
		{
			 //$yesterday = $get_date;
			 $from_date = $to_date = $get_date;
			//$to_date  = date('Y-m-d');
		}
		else
		{
			/* $lastdate = date('Y-m-d');
			$exarr = explode("-",$lastdate);
			$first_date_month = $exarr[0]."-".$exarr[1]."-01 00:00:01";
			$last_date_month = $lastdate." 23:59:59"; */
			$from_date = '2021-03-01';
			$to_date  = '2021-03-31';//date('Y-m-d');
		    //$yesterday = $from_date.'/'.$to_date;
		}
		
		//payment count
		$select = 'exam_code , count(id) AS PaymentCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>'2','status' => '1'),$select);
		//,'DATE(date)'=>$yesterday
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//Invoice count
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		$this->db->where_in('exam_period' ,'121');
		$this->db->group_by('exam_code,exam_period'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice',array('app_type'=>'O'),$select);
		//,'DATE(date_of_invoice)'=>$yesterday
		$invoice_arr = array();
		if(count($invoice_data) > 0)
		{
			foreach($invoice_data as $invoice_res)
			{
				$invoice_arr[$invoice_res['exam_code']] = $invoice_res;
			}
		}
		
		//Member App Count
		$select = 'exam_code ,exam_period, count(id) AS AppCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr = array();
		if(count($app_data) > 0)
		{
			foreach($app_data as $app_res)
			{
				$app_arr[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		$this->db->where_in('exm_cd' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exm_prd' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->where('record_source !=', 'Bulk');
		$this->db->group_by('exm_cd,exm_prd'); 
		$admitcard_data = $this->Master_model->getRecords('admit_card_details',array('remark'=>'1'),$select);
		//,'DATE(created_on)'=>$yesterday
		$admitcard_arr = array();
		if(count($admitcard_data) > 0)
		{
			foreach($admitcard_data as $admitcard_res)
			{
				$admitcard_arr[$admitcard_res['exm_cd']] = $admitcard_res;
			}
		}
		
		//Member App Count with Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_y = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'Y'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_y = array();
		if(count($app_data_y) > 0)
		{
			foreach($app_data_y as $app_res)
			{
				$app_arr_y[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Member App Count with Non Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppNonElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_n = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'N'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_n = array();
		if(count($app_data_n) > 0)
		{
			foreach($app_data_n as $app_res)
			{
				$app_arr_n[$app_res['exam_code']] = $app_res;
			}
		}
		
		// Boundary cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS BoundaryCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$boundary_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$bcases_arr_n = array();
		if(count($boundary_cases) > 0)
		{
			foreach($boundary_cases as $boundary_cases_res)
			{
				$bcases_arr_n[$boundary_cases_res['exam_code']] = $boundary_cases_res;
			}
		}		
		
		// Refund cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS RefundCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('a.transaction_no' ,'');
		$this->db->where('b.status' ,'3');
		$this->db->where('DATE(b.date) >=', $from_date);
		$this->db->where('DATE(b.date) <=', $to_date);
		//$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$refund_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$refund_arr_n = array();
		if(count($refund_cases) > 0)
		{
			foreach($refund_cases as $refund_cases_res)
			{
				$refund_arr_n[$refund_cases_res['exam_code']] = $refund_cases_res;
			}
		}	
		
		//echo $this->db->last_query(); exit;
		$exam_arr = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB'));
		$final_str = '<html>
						<table style="border-collapse: collapse;margin: 0 auto;max-width: 900px; width: 100%;">
							<thead>
								<tr>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Sr</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Code</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Period</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Payment Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Invoice Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Admit Card Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Non Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Boundary Cases</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Refund Cases</th>
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1;
								foreach($exam_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $refundcases='0';
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; }
									
									if(array_key_exists($res, $refund_arr_n)) { $refundcases = $refund_arr_n[$res]['RefundCasesCount']; }
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">121</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_elr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_nonelr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$boundarycases.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$refundcases.'</td>
										</tr>';
									
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
				
		echo $final_str; exit;	
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
	}
							

public function jaiib_count_mail_april()
	{
		
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$get_date= $this->uri->segment(3);
		if(!empty($get_date))
		{
			 //$yesterday = $get_date;
			 $from_date = $to_date = $get_date;
			//$to_date  = date('Y-m-d');
		}
		else
		{
			/* $lastdate = date('Y-m-d');
			$exarr = explode("-",$lastdate);
			$first_date_month = $exarr[0]."-".$exarr[1]."-01 00:00:01";
			$last_date_month = $lastdate." 23:59:59"; */
			$from_date = '2021-04-01';
			$to_date  = '2021-04-28';//date('Y-m-d');
		    //$yesterday = $from_date.'/'.$to_date;
		}
		
		//payment count
		$select = 'exam_code , count(id) AS PaymentCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>'2','status' => '1'),$select);
		//,'DATE(date)'=>$yesterday
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//Invoice count
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		$this->db->where_in('exam_period' ,'121');
		$this->db->group_by('exam_code,exam_period'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice',array('app_type'=>'O'),$select);
		//,'DATE(date_of_invoice)'=>$yesterday
		$invoice_arr = array();
		if(count($invoice_data) > 0)
		{
			foreach($invoice_data as $invoice_res)
			{
				$invoice_arr[$invoice_res['exam_code']] = $invoice_res;
			}
		}
		
		//Member App Count
		$select = 'exam_code ,exam_period, count(id) AS AppCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr = array();
		if(count($app_data) > 0)
		{
			foreach($app_data as $app_res)
			{
				$app_arr[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		$this->db->where_in('exm_cd' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exm_prd' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->where('record_source !=', 'Bulk');
		$this->db->group_by('exm_cd,exm_prd'); 
		$admitcard_data = $this->Master_model->getRecords('admit_card_details',array('remark'=>'1'),$select);
		//,'DATE(created_on)'=>$yesterday
		$admitcard_arr = array();
		if(count($admitcard_data) > 0)
		{
			foreach($admitcard_data as $admitcard_res)
			{
				$admitcard_arr[$admitcard_res['exm_cd']] = $admitcard_res;
			}
		}
		
		//Member App Count with Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_y = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'Y'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_y = array();
		if(count($app_data_y) > 0)
		{
			foreach($app_data_y as $app_res)
			{
				$app_arr_y[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Member App Count with Non Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppNonElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'121');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_n = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'N'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_n = array();
		if(count($app_data_n) > 0)
		{
			foreach($app_data_n as $app_res)
			{
				$app_arr_n[$app_res['exam_code']] = $app_res;
			}
		}
		
		// Boundary cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS BoundaryCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$boundary_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$bcases_arr_n = array();
		if(count($boundary_cases) > 0)
		{
			foreach($boundary_cases as $boundary_cases_res)
			{
				$bcases_arr_n[$boundary_cases_res['exam_code']] = $boundary_cases_res;
			}
		}		
		
		// Refund cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS RefundCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'121');
		$this->db->where('a.transaction_no' ,'');
		$this->db->where('b.status' ,'3');
		$this->db->where('DATE(b.date) >=', $from_date);
		$this->db->where('DATE(b.date) <=', $to_date);
		//$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$refund_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$refund_arr_n = array();
		if(count($refund_cases) > 0)
		{
			foreach($refund_cases as $refund_cases_res)
			{
				$refund_arr_n[$refund_cases_res['exam_code']] = $refund_cases_res;
			}
		}	
		
		//echo $this->db->last_query(); exit;
		$exam_arr = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB'));
		$final_str = '<html>
						<table style="border-collapse: collapse;margin: 0 auto;max-width: 900px; width: 100%;">
							<thead>
								<tr>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Sr</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Code</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Period</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Payment Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Invoice Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Admit Card Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Non Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Boundary Cases</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Refund Cases</th>
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1;
								foreach($exam_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $refundcases='0';
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; }
									
									if(array_key_exists($res, $refund_arr_n)) { $refundcases = $refund_arr_n[$res]['RefundCasesCount']; }
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">121</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_elr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_nonelr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$boundarycases.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$refundcases.'</td>
										</tr>';
									
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
				
		echo $final_str; exit;	
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
	}
							

public function jaiib_count_mail_total()
	{
		
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$get_date= $this->uri->segment(3);
		if(!empty($get_date))
		{
			 //$yesterday = $get_date;
			 $from_date = $to_date = $get_date;
			//$to_date  = date('Y-m-d');
		}
		else
		{
			/* $lastdate = date('Y-m-d');
			$exarr = explode("-",$lastdate);
			$first_date_month = $exarr[0]."-".$exarr[1]."-01 00:00:01";
			$last_date_month = $lastdate." 23:59:59"; */
			$from_date = '2023-03-01';
			$to_date  = date('Y-m-d');
		    //$yesterday = $from_date.'/'.$to_date;
		}
		
		//payment count
		$select = 'exam_code , count(id) AS PaymentCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('pay_type'=>'2','status' => '1'),$select);
		//echo $this->db->last_query();
		//,'DATE(date)'=>$yesterday
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//Invoice count
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		$this->db->where_in('exam_period' ,'123');
		$this->db->group_by('exam_code,exam_period'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice',array('app_type'=>'O'),$select);
		//,'DATE(date_of_invoice)'=>$yesterday
		$invoice_arr = array();
		if(count($invoice_data) > 0)
		{
			foreach($invoice_data as $invoice_res)
			{
				$invoice_arr[$invoice_res['exam_code']] = $invoice_res;
			}
		}
		
		//Member App Count
		$select = 'exam_code ,exam_period, count(id) AS AppCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'123');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr = array();
		if(count($app_data) > 0)
		{
			foreach($app_data as $app_res)
			{
				$app_arr[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		$this->db->where_in('exm_cd' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exm_prd' ,'123');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->where('record_source !=', 'Bulk');
		$this->db->group_by('exm_cd,exm_prd'); 
		$admitcard_data = $this->Master_model->getRecords('admit_card_details',array('remark'=>'1'),$select);
		//,'DATE(created_on)'=>$yesterday
		$admitcard_arr = array();
		if(count($admitcard_data) > 0)
		{
			foreach($admitcard_data as $admitcard_res)
			{
				$admitcard_arr[$admitcard_res['exm_cd']] = $admitcard_res;
			}
		}
		
		//Member App Count with Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'123');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_y = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'Y'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_y = array();
		if(count($app_data_y) > 0)
		{
			foreach($app_data_y as $app_res)
			{
				$app_arr_y[$app_res['exam_code']] = $app_res;
			}
		}
		
		//Member App Count with Non Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppNonElerCount';
		$this->db->where_in('exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where_in('exam_period' ,'123');
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code,exam_period'); 
		$app_data_n = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0','elearning_flag' =>'N'),$select);
		//,'DATE(created_on)'=>$yesterday
		$app_arr_n = array();
		if(count($app_data_n) > 0)
		{
			foreach($app_data_n as $app_res)
			{
				$app_arr_n[$app_res['exam_code']] = $app_res;
			}
		}
		
		// Boundary cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS BoundaryCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'123');
		$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$boundary_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$bcases_arr_n = array();
		if(count($boundary_cases) > 0)
		{
			foreach($boundary_cases as $boundary_cases_res)
			{
				$bcases_arr_n[$boundary_cases_res['exam_code']] = $boundary_cases_res;
			}
		}		
		
		// Refund cases
		$select = 'a.exam_code ,a.exam_period, count(a.invoice_id) AS RefundCasesCount';
		$this->db->where_in('a.exam_code' ,array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
		$this->db->where('a.exam_period' ,'123');
		$this->db->where('a.transaction_no' ,'');
		$this->db->where('b.status' ,'3');
		$this->db->where('DATE(b.date) >=', $from_date);
		$this->db->where('DATE(b.date) <=', $to_date);
		//$this->db->where('DATE(b.date) < DATE(a.date_of_invoice)');
		$this->db->join('payment_transaction b','b.receipt_no  = a.receipt_no ','LEFT');
		$refund_cases = $this->Master_model->getRecords('exam_invoice a',array('pay_type '=>'2','invoice_no !='=>''),$select);
			//,'date'=>$yesterday
		$refund_arr_n = array();
		if(count($refund_cases) > 0)
		{
			foreach($refund_cases as $refund_cases_res)
			{
				$refund_arr_n[$refund_cases_res['exam_code']] = $refund_cases_res;
			}
		}	
		
		//echo $this->db->last_query(); exit;
		$exam_arr = array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB'));
		$final_str = '<html>
						<table style="border-collapse: collapse;margin: 0 auto;max-width: 900px; width: 100%;">
							<thead>
								<tr>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Sr</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Code</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Period</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Payment Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Invoice Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Admit Card Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count Non Elearning</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Boundary Cases</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Refund Cases</th>
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1;
								foreach($exam_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $refundcases='0';
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; }
									
									if(array_key_exists($res, $refund_arr_n)) { $refundcases = $refund_arr_n[$res]['RefundCasesCount']; }
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">123</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_elr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_nonelr_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$boundarycases.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$refundcases.'</td>
										</tr>';
									
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
				
		echo $final_str; exit;	
				
			$info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>"; 
	}
							
	public function change_member_exam_opt_flag123() {
		
		$memberss = $this->db->query("SELECT e.member_no,count(e.id) as numrows,m.exam_fee,m.created_on FROM `eligible_master` e,member_exam m WHERE e.member_no=m.regnumber and e.optForCandidate='Y' and m.optFlg='N' and m.exam_code=210 and m.pay_status='1' group by e.member_no"); 
        $members = $memberss->result_array();
		$i=0;
		foreach ($members as $mem) {
			//if($mem['numrows'] == 4)
			{
				$admitcardss = $this->db->query("SELECT count(admitcard_id) as admitcardrows FROM admit_card_details where mem_mem_no='".$mem['member_no']."' and exm_cd=210 and remark=1");
		//	echo $this->db->last_query();  
        	$admitcards = $admitcardss->row_array();
			if($mem['numrows']==$admitcards['admitcardrows']) {
				echo $mem['member_no'].'<br>';
				echo $i++.'=<pre>'.$mem['member_no'].'====='.$mem['created_on'].'====='.$mem['exam_fee'].'====='.$mem['numrows'].'=====';print_r($admitcards);
			}
			}
			
			 
		}
	}
	public function change_member_exam_opt_flag() {
		
		$memberss = $this->db->query("SELECT m.regnumber,m.created_on FROM member_exam m WHERE    m.exam_code=210 and m.pay_status='1' and m.created_on < '2023-03-02 15:00:00'"); 
        $members = $memberss->result_array();
		$i=0;
			
$existarr=array(500040545,500045380,500080223,500093485,500107984,500124680,500158146,500177860,500180127,500206119,500206707,500207938,510036228,510079415,510085049,510087480,510094645,510119198,510129937,510134261,510171261,510182909,510206234,510211424,510213636,510214514,510216014,510226189,510237161,510246376,510259691,510261732,510279580,510282731,510287677,510290460,510300784,510304920,510306387,510307714,510319129,510322315,510322352,510328410,510331590,510344192,510345881,510346010,510346230,510348081,510348405,510362874,510367571,510372848,510376793,510378631,510381324,510384009,510386456,510395776,510396222,510397204,510399915,510400541,510409887,510411769,510412924,510413774,510416891,510418749,510419350,510420663,510421614,510421704,510423576,510428766,510431617,510431681,510433787,510433860,510434976,510435367,510437911,510440033,510442869,510443025,510444780,510448233,510449740,510451067,510454954,510458855,510460512,510461790,510465774,510470088,510473583,510479454,510485162,510486114,510487507,510502010,510502464,510507092,510508011,510508364,510509066,510509545,510512282,510512809,510513820,510514723,510514769,510515314,510515496,510521073,510522504,510524404,510524598,510528950,510530188,510531333,510532229,510532989,510534130,510537686,510538884,510538987,510539432,510541035,510542754,510545014,510548911,510551582,510552833,510553072,510553304,510553394,510553818,510554050,510555549,510556099,510556169,510559107,510559366,510559944,510560178,510560464,510562088,510563683,510563946,510564200,510566449,510566598,510566799,510567314,510569360,510569802,510569868,510570933,500011264,500043622,500090818,500101127,500101192,500111419,500137550,500139617,500159244,500163553,500168802,510014991,510037074,510038295,510048650,510057962,510064622,510064679,510073856,510090437,510097387,510098878,510099578,510111504,510116397,510133194,510145554,510158738,510171155,510181001,510218767,510224233,510235235,510236748,510255291,510257499,510257650,510269787,510270011,510277535,510292669,510296815,510300616,510302535,510304917,510322060,510328206,510330091,510336709,510338176,510355924,510360676,510363638,510364088,510364126,510370147,510373261,510385265,510388709,510390381,510390440,510397329,510405469,510407002,510408781,510410074,510410717,510411832,510412106,510412904,510413583,510414246,510414515,510417174,510417512,510420357,510421378,510423558,510424058,510424069,510424108,510425276,510426927,510433949,510437357,510437371,510437707,510440648,510443987,510450484,510452005,510452024,510452049,510454599,510462114,510462842,510464395,510464981,510465197,510467239,510470852,510471207,510472354,510473252,510473657,510476852,510477349,510479296,510479718,510498816,510500212,510502514,510504589,510506415,510506822,510507298,510508826,510509336,510510503,510511689,510512845,510513002,510513647,510513685,510515270,510515290,510515552,510516118,510517229,510517286,510517488,510518101,510520721,510520736,510520866,510524088,510525514,510526244,510526528,510527966,510528632,510529309,510529424,510531210,510531338,510531369,510531518,510531807,510532222,510533093,510533371,510533398,510533595,510533888,510533894,510534036,510534152,510534191,510534497,510535760,510536917,510537453,510538017,510538247,510539036,510539518,510539675,510540999,510541113,510541520,510541923,510542373,510543660,510543862,510544306,510544649,510544992,510545510,510546028,510546089,510546752,510546958,510547032,510548063,510551789,510553339,510553771,510554020,510554561,510554943,510555263,510555617,510556321,510556860,510556969,510556985,510557038,510557729,510557789,510559589,510559691,510560067,510560755,510561843,510561940,510562670,510562951,510563492,510563654,510563722,510564901,510565082,510565201,510565268,510565660,510565859,510566454,510567094,510567727,510567767,510568320,510568798,510569539,510569682,510570374,510570583,510570591,510570641,510570787,510570833,510571297,510571903,'500151543','510325583','510361373','510411417','510510635','510567579');
		foreach ($members as $mem) {
			//if($mem['numrows'] == 4)
			{
				$associatedinstitutes = $this->db->query("SELECT associatedinstitute  FROM member_registration where regnumber='".$mem['regnumber']."' and associatedinstitute!=''");
		//	echo $this->db->last_query();  
        	$associatedinstitute = $associatedinstitutes->row_array();
			if(!empty($associatedinstitutes) && $associatedinstitute['associatedinstitute']!='' && !in_array($mem['regnumber'],$existarr)) {
				echo $mem['regnumber'].'<br>';
				//echo '<br>newline='.$i++.'=<pre>'.$mem['regnumber'].'====='.$mem['created_on'].'====='.$associatedinstitute['associatedinstitute'].'<br>';
			}
				
				//echo $i++.'<br>';//.'=<pre>'.$mem['regnumber'].'====='.$mem['created_on'].'====='.$associatedinstitute['associatedinstitute'].'<br>';
			
			}
			
			 
		}
	}			 				
public function change_member_exam_opt_flagold() {
		/*$memberss = $this->db->query("SELECT DISTINCT e.member_no FROM `eligible_master` e,member_exam m WHERE e.member_no=m.regnumber and e.optForCandidate='Y' and m.optFlg='N' and m.exam_code=210 and m.pay_status='1' limit 0,4"); 
        $members = $memberss->result_array();*/
		$members=array(500124680,510424069,510563492,500159244,510246376,510410074,510414515,510569802,510119198,500158146,510553771,510417512,510534152,510546752,510502010,510539432,510544649,510507092,500111419,500080223,500101127,510479296,510542754,500011264,510512282,510514723,510563683,510555617,510257499,510513685,510420357,510562951,510528950,510559589,510443987,510531518,510560178,510515270,510435367,510440033,510506822,510440648,510399915,510307714,510479454,510423558,510561843,510546089,510554020,510513647,510541113,510407002,510378631,510487507,510559107,510545014,510425276,510424058,510533595,500180127,510079415,510416891,510547032,510563722,500101192,510539518,510535760,510508364,510512809,510548063,510546028,510485162,510551582,510418749,510534036,510556860,510226189,510330091,510556321,510214514,510559944,510513002,510521073,510419350,510452049,510510503,510552833,510451067,510470852,510450484,510559366,510409887,510473252,510566449,510085049,510290460,510434976,510390381,510544992,500040545,510087480,510540999,510563946,500177860,510569360,510460512,510539036,510390440,510532989,510517229,510571903,510413774,510426927,510408781,500107984,510057962,510037074,510145554,510437371,500043622,510073856,510532229,510328410,510099578,510433860,510319129,510471207,510567727,510556099,510448233,510498816,510464981,510477349,510570583,510461790,510515496,510473657,510529424,510470088,510541923,510129937,510363638,510502464,510531333,510544306,510531807,510560067,500168802,510048650,510397204,510545510,510557729,510449740,510442869,510292669,510570591,510553394,510462842,510567094,500139617,510542373,510570374,510528632,510181001,510381324,510559691,510526244,510287677,510566799,510452024,510554561,500206707,510213636,510546958,510556169,510508826,510431681,510462114,510554050,510300784,510527966,510322315,510171261,510397329,510570933,510443025,510526528,510279580,510384009,510537453,510411832,510536917,510541520,510531369,510562088,510538987,510553304,510396222,510367571,510507298,510520721,510428766,510520866,510543660,510437707,510551789,510306387,510531210,510424108,510237161,510565082,510116397,510508011,510568798,500045380,510372848,510569539,510571297,510433787,510566598,510098878,510097387,510561940,510569868,510423576,510411769,510373261,510431617,510296815,510454954,510533894,510348081,510355924,500207938,510534130,510538884,510548911,510364126,510533398,500163553,510452005,510568320,510518101,510302535,510565859,510395776,510331590,510304917,510522504,510421614,510512845,510336709,510269787,510525514,510036228,510529309,510533093,510515552,510417174,510255291,510171155,510537686,510111504,510567767,510524404,510465774,510322060,510235235,510515290,510338176,500137550,510014991,510360676,510570641,510410717,510509066,510517286,510304920,510554943,510400541,510322352,510454599,510370147,510433949,510486114,510414246,510344192,510346230,510509545,510133194,510530188,510517488,510553818,510158738,510282731,510567314,510524598,510413583,510502514,510064622,510531338,510506415,510259691,510412106,510412904,510565660,510328206,510539675,510562670,510565268,510420663,510458855,510541035,510515314,510218767,510386456,510553339,510444780,510364088,510345881,510261732,510556985,510570833,510346010,510557038,510236748,510270011,510538247,510388709,510437911,510565201,510376793,510257650,510532222,510277535,510500212,510476852,510514769,500206119,510385265,510533371,510224233,510570787,510560464,510513820,510421378,510405469,510563654,510564200,510437357,510090437,510569682,510511689,510524088,510479718,510533888,510412924,510300616,510556969,510538017,510534497,510555263,510553072,510094645,510543862,510520736,510464395,510362874,510182909,510206234,510555549,510467239,510472354,510557789,510211424,510509336,510473583,500090818,500093485,510560755,510566454,510421704,510504589,510348405,510564901,510516118,510465197,510216014,510064679,510534191,510134261,510038295);
		$members=array(510064679,510534191,510134261,510038295);
		foreach ($members as $mem) {
			echo '=====================<br>';
			$userlogss = $this->db->query("SELECT description FROM `userlogs` where regnumber='".$mem."' and title='Member exam apply details' "); 
        	$userlogs = $userlogss->result_array();
			echo $this->db->last_query().'<br>';
			foreach($userlogs as $userlog) {
				$description=json_decode($userlog['description']);
				echo'<pre>';print_r($description);
			}
		}
}
							
}



