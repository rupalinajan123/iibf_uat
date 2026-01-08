<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Otherexam_day_count extends CI_Controller 
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
			 $lastdate = date('Y-m-d');
			$exarr = explode("-",$lastdate);
			$from_date = $exarr[0]."-".$exarr[1]."-01 00:00:01";
			$to_date = $lastdate." 23:59:59"; 
			//$from_date = '2021-06-01';
			//$to_date  = '2021-06-30';//date('Y-m-d');
		    //$yesterday = $from_date.'/'.$to_date;
			/* $date = new DateTime('LAST DAY OF PREVIOUS MONTH');
            $to_date =  $date->format('Y-m-d');
            $from_date = date("Y-m-", strtotime($to_date))."01"; */
		}
		
		 $date = date('Y-m-d');
		 $this->db->where('exam_from_date <=' ,$date);
		 $this->db->where('exam_to_date >=' ,$date);
		 $this->db->where_not_in('exam_period' ,'121');
		 $this->db->order_by('id','ASC');
		 $exam_data = $this->Master_model->getRecords('exam_activation_master',array('exam_activation_delete '=>'0'));
		echo $this->db->last_query(); die;
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
		
		//print_r($exam_code_arr); die;
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
		//echo $this->db->last_query(); die;
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
		//$this->db->where_in('exm_cd' ,$exam_code_arr);
		//$this->db->where_in('exm_prd' ,$exam_prd);
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
		
		//Member App Count with Elearning
		$select = 'exam_code ,exam_period, count(id) AS AppElerCount';
		//$this->db->where_in('exam_code' ,$exam_code_arr);
		//$this->db->where_in('exam_period' ,$exam_prd);
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
		//$this->db->where_in('exam_code' ,$exam_code_arr);
		//$this->db->where_in('exam_period' ,$exam_prd);
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
		//$this->db->where_in('a.exam_code' ,$exam_code_arr);
		//$this->db->where('a.exam_period' ,$exam_prd);
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
		
		//echo $this->db->last_query(); exit;
		 /* SELECT id, exam_code, exam_period, exam_from_date, exam_to_date FROM exam_activation_master WHERE '2020-10-11' between exam_from_date and exam_to_date AND exam_activation_delete = 0 ORDER BY id ASC; */
		
		
		//$exam_arr = array('21', '42', '992');
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
								foreach($exam_code_arr as $res)
								{
									$pay_count = $invoice_cnt = $application_cnt = $admitcard_cnt = $application_elr_cnt = $application_nonelr_cnt = $boundarycases = $exam_prd_arr = '0';
									//print_r($payment_arr[$res]['PaymentCount']); die;
									//if(array_key_exists($res, $prd_arr)) { $exam_prd_arr = $prd_arr[$res]['exam_prd_arr']; }
									
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
											
}



