<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otherexam_month_count extends CI_Controller 
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

	

	public function exam_count_mail()
	{
		
		$data = '';
		$exam_code_arr = array();
		//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
		//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
		$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
	            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01";
			
		/*  $date = date('Y-m-d');
		 $this->db->where('exam_from_date <=' ,$date);
		 $this->db->where('exam_to_date >=' ,$date);
		 //$this->db->where_not_in('exam_period' ,'121');
		 $this->db->order_by('id','ASC');
		 $exam_data = $this->Master_model->getRecords('exam_activation_master',array('exam_activation_delete '=>'0'));
		// echo $this->db->last_query(); die;
		$exam_code = $exam_data[0]['exam_code'];
		$exam_prd = $exam_data[0]['exam_period'];
		$exam_code_arr = array();
		$exam_prd_arr = array();
		if(!empty($exam_data))
		{
			foreach($exam_data as $exam_res)
			{
				
				$exam_code_arr[$exam_res['exam_code']] = $exam_res['exam_code'];
				$exam_prd_arr[$exam_res['exam_period']] = $exam_res['exam_period'];
			// $exam_prd_arr = $exam_res['exam_period'];
				//$exam_code = $exam_arr[0]['exam_code'];
			}
		}
		 */
		//print_r($exam_code_arr); die;
		if($from_date!='' && $to_date!='')
		{
		//payment count
		$select = 'exam_code , count(id) AS PaymentCount';
		$this->db->where('transaction_no !=' , '');
		//$this->db->where_in('exam_code' ,$exam_code_arr);
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$this->db->where_in('pay_type', array('2','18'));
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('status' => '1'),$select);
		//,'DATE(date)'=>$yesterday
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//echo $this->db->last_query(); die;
		//Invoice count
		//$yesterday = date('Y-m-d', strtotime("- 1 day"));
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		//$this->db->where_in('exam_code' ,$exam_code_arr);
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		//$this->db->where_in('exam_period' ,$exam_prd);
		$this->db->where_in('app_type' ,array('O','L'));
		$this->db->group_by('exam_code,exam_period'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice','',$select);
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
		//$this->db->where_in('exam_code' ,$exam_code_arr);
		//$this->db->where_in('exam_period' ,$exam_prd);
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
				$exam_code_arr[$app_res['exam_code']] = $app_res['exam_code'];
				$prd_arr[$app_res['exam_period']] = $app_res['exam_period'];
			}
		}
		
		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		//$this->db->where_in('exm_cd' ,$exam_code_arr);
		$this->db->where('record_source !=' ,'Bulk'); 
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
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
								  
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1; //echo '<pre>'; print_r($exam_array); die;
								$csv = "Sr ,Exam Code ,	Exam Period ,Payment Count ,Invoice Count ,	Application Count ,	Admit Card Count \n";
								foreach($exam_code_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $exam_prd_arr = '0';
									
									//print_r($prd_arr[$app_res['exam_period']]); die;
									if(array_key_exists($res, $prd_arr)) { $exam_prd_arr = $prd_arr[$app_res['exam_period']]; }
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									/* if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; } */
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$exam_prd_arr.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											
										</tr>';
									$csv.= $sr_no.",".$res.",".$exam_prd_arr.",".$pay_count.",".$invoice_cnt.",".$application_cnt.",".$admitcard_cnt."\n";
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
					
					$csv .="\n\n\n\n";
				$csv .= "exam_code, exam_period, receipt_no, transaction_no, gstin_no, qty, institute_code, invoice_no, date_of_invoice, created_on \n";
				
				$query = $this->db->query("SELECT `exam_code`, `exam_period`, `receipt_no`, `transaction_no`, `gstin_no`, `qty`, `institute_code`, `invoice_no`, `date_of_invoice`, `created_on` FROM `exam_invoice` WHERE `transaction_no` != '' AND `invoice_no` != '' AND DATE(`date_of_invoice`) BETWEEN ('$from_date') AND ('$to_date')  AND `app_type` LIKE '%Z%'  ");
				$result = $query->result_array(); 
				//echo $this->db->last_query(); die;
				foreach($result as $record)
				{
					$csv.= $record['exam_code'].",".$record['exam_period'].",".$record['receipt_no'].",".$record['transaction_no'].",".$record['gstin_no'].",".$record['qty'].",".$record['institute_code'].",".$record['invoice_no'].",".$record['date_of_invoice'].",".$record['created_on']."\n";
				}
		//echo $final_str; exit;
		$filename = "monthly_count_".date("YmdHis").".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            $csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);		
				
			/* $info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 
			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>";  */
	}
	}										

	public function bulk_invoice()
	{
		$data = '';
		$exam_code_arr = array();
		//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
		//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
		$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01"; 
			
			$csv = "exam_code, exam_period, receipt_no, transaction_no, gstin_no, qty, institute_code, invoice_no, date_of_invoice, created_on";
				
				$query = $this->db->query("SELECT `exam_code`, `exam_period`, `receipt_no`, `transaction_no`, `gstin_no`, `qty`, `institute_code`, `invoice_no`, `date_of_invoice`, `created_on` FROM `exam_invoice` WHERE `transaction_no` != '' AND `invoice_no` != '' AND DATE(`date_of_invoice`) BETWEEN ('$from_date') AND ('$to_date')  AND `app_type` LIKE '%Z%'  ");
				$result = $query->result_array(); 
				//echo $this->db->last_query(); die;
				foreach($result as $record)
				{
					$csv.= $record['exam_code'].",".$record['exam_period'].",".$record['receipt_no'].",".$record['transaction_no'].",".$record['gstin_no'].",".$record['qty'].",".$record['institute_code'].",".$record['invoice_no'].",".$record['date_of_invoice'].",".$record['created_on']."\n";
				}
				$filename = "bulk_count_".date("YmdHis").".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            $csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);	
			
	}

	public function exam_invoice_no()
	{
		$data = '';
			$exam_code_arr = array();
		//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
		//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
		$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
	            $to_date =  $date->format('Y-m-d');
	            $from_date = date("Y-m-", strtotime($to_date))."01";
		$select = 'DISTINCT(exam_code) ,exam_period,invoice_no';
		$this->db->where('transaction_no !=' , '');
			$this->db->where('invoice_no !=' , '');
			$this->db->where('DATE(date_of_invoice) >=', $from_date);
			$this->db->where('DATE(date_of_invoice) <=', $to_date);
			$this->db->where_in('app_type' ,array('O','L'));
			$invoice_data = $this->Master_model->getRecords('exam_invoice','',$select);
		//echo $this->db->last_query(); die;
		$csv_headers = '';
		$csv_headers_arr = array();
		$csv_content ='';
		
		if(!empty($exam_code))
		{	//echo "csv is generating......";
			foreach($exam_code as $exam_code_prd)
			{
				//$csv_headers_arr[] = $exam_code_prd['exam_code'].'-'.$exam_code_prd['exam_period'];
				 //$csv = $csv_headers;
				$csv_headers .= $exam_code_prd['exam_code'].'-'.$exam_code_prd['exam_period'].',';
				
			}
		}
			
			$csv_headers = rtrim($csv_headers, ",")."\n";
			$filename = date("Ymd")."exam_invoice_no.csv";
					$path = "uploads/exam_invoice_no/".$filename ."";
					$csv_handler = fopen($path, 'w');
					fwrite ($csv_handler,$csv_headers);
					fclose ($csv_handler);					
				
			//echo "csv is downloaded";
	}	
public function invoice_csv()
{
	$data = '';
		$exam_code_arr = array();
	//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
	//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
	$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01";
		
		//Invoice count
		/* $select = 'DISTINCT(exam_code) ,exam_period';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		$this->db->where_in('app_type' ,array('O','L','EL'));
		$invoice_data = $this->Master_model->getRecords('exam_invoice','',$select); */
		
				$csv = "Exam code, Exam prd , Invoice no \n";
				
				$query = $this->db->query("SELECT exam_code, `exam_period`, invoice_no FROM `exam_invoice` WHERE `transaction_no` != '' AND `invoice_no` != '' AND `app_type` IN('O', 'L') AND DATE(date_of_invoice) BETWEEN ('$from_date') AND ('$to_date')  ORDER BY `exam_invoice`.`exam_code` ");
				$result = $query->result_array(); 
				//echo $this->db->last_query(); die;
				foreach($result as $record)
				{
				 $csv.= $record['exam_code'].",".$record['exam_period'].",".$record['invoice_no']."\n";
				}
				$filename = "invoice_".date("YmdHis").".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            $csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);		
		
		
	
		
			
}	
//by Pooja
public function monthly_count()
	{
		echo'<pre>';//die;
		$data = '';
		$exam_code_arr = array();
		//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
		//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
		$date = new DateTime('LAST DAY OF PREVIOUS MONTH');
	            $to_date =  $date->format('Y-m-d');
            	$from_date = date("Y-m-", strtotime($to_date))."01";
		//$from_date= '';
		//$to_date='';	
				
		
		if($from_date!='' && $to_date!='')
		{
		//PAYMENT TABLE COUNT
		$select = 'exam_code , count(id) AS PaymentCount';
		//$this->db->where('transaction_no !=' , '');
		$this->db->group_by('exam_code'); 
		$this->db->where('DATE(date) >=', $from_date);
		$this->db->where('DATE(date) <=', $to_date);
		$this->db->where_in('pay_type', array('2'));
		//$this->db->where_in('pay_type', array('18')); //for elearning
		$payment_data = $this->Master_model->getRecords('payment_transaction',array('status' => '1'),$select);
		//print_r($payment_data);//die;
		$payment_arr = array();
		if(count($payment_data) > 0)
		{
			foreach($payment_data as $payment_res)
			{
				$payment_arr[$payment_res['exam_code']] = $payment_res;
			}
		}		
		
		//echo '<br>';echo'payment>>';echo $this->db->last_query();echo '<br>'; //die;

		//INVOICE TABLE COUNT
		$select = 'exam_code ,exam_period, count(invoice_id) AS InvoiceCount';
		$this->db->where('transaction_no !=' , '');
		$this->db->where('invoice_no !=' , '');
		$this->db->where('DATE(date_of_invoice) >=', $from_date);
		$this->db->where('DATE(date_of_invoice) <=', $to_date);
		
		$this->db->where_in('app_type' ,array('O'));
		$this->db->group_by('exam_code'); 
		
		$invoice_data = $this->Master_model->getRecords('exam_invoice','',$select);
		$invoice_arr = array();
		if(count($invoice_data) > 0)
		{
			foreach($invoice_data as $invoice_res)
			{
				$invoice_arr[$invoice_res['exam_code']] = $invoice_res;
			}
		}
		//echo '<br>';echo'invoice>>';echo $this->db->last_query();echo '<br>'; //die;

		//MEMBER APPLICATION COUNT
		$select = 'exam_code ,exam_period, count(id) AS AppCount';
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exam_code'); 
		$app_data = $this->Master_model->getRecords('member_exam',array('pay_status'=>'1','institute_id '=>'0'),$select);
		$app_arr = array();
		if(count($app_data) > 0)
		{
			foreach($app_data as $app_res)
			{
				$app_arr[$app_res['exam_code']] = $app_res;
				$exam_code_arr[$app_res['exam_code']] = $app_res['exam_code'];
				$prd_arr[$app_res['exam_period']] = $app_res['exam_period'];
			}
		}
		//echo '<br>';echo'application>>';echo $this->db->last_query();echo '<br>'; //die;

		//Amdit Card count
		$select = 'exm_cd , exm_prd , count(distinct mem_exam_id) AS AdmitcardCount';
		//$this->db->distinct('mem_exam_id');
		//$this->db->where_in('exm_cd' ,$exam_code_arr);
		$this->db->where('record_source !=' ,'Bulk'); 
		$this->db->where('DATE(created_on) >=', $from_date);
		$this->db->where('DATE(created_on) <=', $to_date);
		$this->db->group_by('exm_cd'); 
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
		//echo '<br>';echo'admitcard>>';echo $this->db->last_query();echo '<br>'; //die;
		
		$final_str = '<html>
						<table style="border-collapse: collapse;margin: 0 auto;max-width: 900px; width: 100%;">
							<thead>
								<tr>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Sr</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Exam Code</th>
								  
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Payment Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Invoice Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Application Count</th>
								  <th style="border: 1px solid #ccc;background: #eee;text-align: center; padding: 8px 15px;">Admit Card Count</th>
								  
								</tr>
							</thead>
							<tbody>';
								$sr_no = 1; //echo '<pre>'; print_r($exam_array); die;
								$csv = "Sr ,Exam Code,Payment Count ,Invoice Count ,Application Count ,	Admit Card Count \n";
								foreach($exam_code_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $exam_prd_arr = '0';
									
									//print_r($prd_arr); die;
									//if(array_key_exists($res, $prd_arr)) { $exam_prd_arr = $prd_arr[$app_res['exam_period']]; }
									
									if(array_key_exists($res, $payment_arr)) { $pay_count = $payment_arr[$res]['PaymentCount']; }
									
									if(array_key_exists($res, $invoice_arr)) { $invoice_cnt = $invoice_arr[$res]['InvoiceCount']; }
									
									if(array_key_exists($res, $app_arr)) { $application_cnt = $app_arr[$res]['AppCount']; }
									
									if(array_key_exists($res, $admitcard_arr)) { $admitcard_cnt = $admitcard_arr[$res]['AdmitcardCount']; }
									
									/* if(array_key_exists($res, $app_arr_y)) { $application_elr_cnt = $app_arr_y[$res]['AppElerCount']; }
									
									if(array_key_exists($res, $app_arr_n)) { $application_nonelr_cnt = $app_arr_n[$res]['AppNonElerCount']; }
									
									if(array_key_exists($res, $bcases_arr_n)) { $boundarycases = $bcases_arr_n[$res]['BoundaryCasesCount']; } */
									
									$final_str .= '
										<tr>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$sr_no.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$res.'</td>
											
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$pay_count.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$invoice_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$application_cnt.'</td>
											<td style="border: 1px solid #ccc; padding: 5px 15px;">'.$admitcard_cnt.'</td>
											
										</tr>';
										
										//$csv.= $sr_no.",".$res.",".$exam_prd_arr.",".$pay_count.",".$invoice_cnt.",".$application_cnt.",".$admitcard_cnt."\n";
										$csv.= $sr_no.",".$res.",".$pay_count.",".$invoice_cnt.",".$application_cnt.",".$admitcard_cnt."\n";
									$sr_no++;
								}
							$final_str .= '	
							</tbody>
						</table>
					</html>';
					echo $final_str;die;
					$csv .="\n\n\n\n";
				$csv .= "exam_code, exam_period, receipt_no, transaction_no, gstin_no, qty, institute_code, invoice_no, date_of_invoice, created_on \n";
				
				$query = $this->db->query("SELECT `exam_code`, `exam_period`, `receipt_no`, `transaction_no`, `gstin_no`, `qty`, `institute_code`, `invoice_no`, `date_of_invoice`, `created_on` FROM `exam_invoice` WHERE `transaction_no` != '' AND `invoice_no` != '' AND DATE(`date_of_invoice`) BETWEEN ('$from_date') AND ('$to_date')  AND `app_type` LIKE '%Z%'  ");
				$result = $query->result_array(); 
				//echo'<lastqery> ';echo $this->db->last_query(); 
				//print_r($result);
				//die;
				foreach($result as $record)
				{
					$csv.= $record['exam_code'].",".$record['exam_period'].",".$record['receipt_no'].",".$record['transaction_no'].",".$record['gstin_no'].",".$record['qty'].",".$record['institute_code'].",".$record['invoice_no'].",".$record['date_of_invoice'].",".$record['created_on']."\n";
				}

			//echo $final_str; exit;
			$filename = "monthly_count_".date("YmdHis").".csv";
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename='.$filename);
            $csv_handler = fopen('php://output', 'w');
            fwrite ($csv_handler,$csv);
            fclose ($csv_handler);		
				
			/* $info_arr=array(//'to'=>$new_mem_reg['email'],
						'to'=>'sagar.matale@esds.co.in,chaitali.jadhav@esds.co.in,pallavi.panchal@esds.co.in',
						'from'=>'noreply@iibf.org.in',
				'subject'=>'IIBF:Blended Member Count',
				'message'=>$final_str
			); 

			$this->Emailsending->mailsend_attch($info_arr,'');
				echo "Mail send to => chaitali.jadhav@esds.co.in";
				echo "<br/>";  */
		}
	}

}



