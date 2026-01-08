<?php
/*
 * Controller Name	:	venue capacity alert Alert
 * Created By		:	Prafull Tupe
 * Created Date		:	24-09-2019
 * Last Update 		:   24-09-2019
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Capacity_alert  extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->helper('capcity_helper');
		$this->load->model('Master_model');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	
	/*
	  To Settle the exam invoice automatically
	*/
	public function index()
	{
		$str='';
		$start_point  = 0;
		$end_point    = 500;
		//$current_date =date('Y-m-d',strtotime("-1 days"));	
		//$current_date =date('2019-10-14');	
	 	$current_date =date('Y-m-d');	

		//$current_date ="2019-09-27";	
		/*Cron LIMIT */
		/*$module_type = 'member_invoice';
		$this->db->where(" date(created_at) = '".$current_date."'");
		$this->db->where("module_type","member_invoice");
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists))
		{
			$start_point = count($is_cron_exists)*$end_point;
		}
		$this->cron_add($start_point,$end_point,$current_date,$module_type);*/
		/*Cron LIMIT */


		/* Fetch the data from the exam table where paymnet status is 0 */
		$arr_referal  = $date_arr=array();
		$status       = 0;
		//$pay_type     = 1; /* member  */
 		//$this->db->select('payment_transaction.member_regnumber,payment_transaction.date,payment_transaction.ref_id,payment_transaction.exam_code,payment_transaction.receipt_no,payment_transaction.pay_type,payment_transaction.status,payment_transaction.date,payment_transaction.transaction_no,payment_transaction.id as payment_auto_inc');
		//$this->db->where("date >='".$current_date."'");
	 	//$this->db->where("status",$status);
	 	//$this->db->where("pay_type",$pay_type);
	 	//$this->db->limit($end_point,$start_point);
		$this->db->where("'$current_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $get_live_exams = $this->master_model->getRecords('exam_activation_master');
		if(count($get_live_exams) > 0)
		{
			foreach($get_live_exams as $exams)
			{
					$this->db->select('exam_date');
					$this->db->distinct('exam_date');
					$this->db->where('exam_code',$exams['exam_code']);
					$this->db->where('exam_period',$exams['exam_period']);
					$get_dates = $this->master_model->getRecords('subject_master');
					
					if(count($get_dates) > 0)
					{
						foreach($get_dates as $row)
						{
							$date_arr[]=	$row['exam_date'];	
						}		
					}
			}
		}
		if(count($date_arr) > 0)
		{
		$check_date_arr=array_unique($date_arr);
		$this->db->where_in("exam_date",$check_date_arr);
		$seat_arr = $this->Master_model->getRecords('venue_master');	
		if(count($seat_arr) > 0)
		{
			$mailsend_str='';
			$str.='<table id="listitems" border="1">
                <thead>
                  <tr>
                    <th nowrap="nowrap">Center Code</th>
				    <th nowrap="nowrap">Center Name</th>
                    <th nowrap="nowrap">Venue Code</th>
                    <th nowrap="nowrap">Venue Name</th>
                    <th nowrap="nowrap">Exam Date</th>
                    <th nowrap="nowrap">Exam Time</th>
                    <th nowrap="nowrap">Total <br />
                      Capacity </th>
                    <th nowrap="nowrap">Registered <br />
                      Count </th>
                    <th nowrap="nowrap">Balance <br />
                      Capacity </th>
                    
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">';
				
			foreach($seat_arr as $result)
			{
					$mailsend_str=capacity($result['center_code'],$result['venue_code'],$result['exam_date'],$result['session_time']);
					if($mailsend_str!='')
					{
						$status=1;
						$str.=$mailsend_str;
					}
		}
		$str.='</tbody>
              </table>';
		//echo $str; 
		
		$email_arr = array('sgbhatia@iibf.org.in','shrayan@iibf.org.in','amit@iibf.org.in','suhas@iibf.org.in','pawansing.pardeshi@esds.co.in'); 
			
		//	$email_arr = array('pawansing.pardeshi@esds.co.in','bhushan.amrutkar@esds.co.in');
				if($status)
				{
					$info_arr=array(
												'to'=>$email_arr, 
												'from'=>'noreply@iibf.org.in',
												'subject'=>'Venue Alert',
												'message'=>$str
											);
											
					if($this->Emailsending->mailsend($info_arr))
					{
						//echo 'Mail sent';
					}
				}	
		//echo "Done..!!";
	}
 	
	}
}
}