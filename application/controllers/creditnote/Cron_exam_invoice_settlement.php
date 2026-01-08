<?php
/*
 * Controller Name	:	Cron Exam Invoice Settlement
 * Created By		:	Padmashri Joshi
 * Created Date		:	24-09-2019
 * Last Update 		:   24-09-2019
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_exam_invoice_settlement  extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		$this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
	}
	
	/*
	  To Settle the exam invoice automatically
	*/
	public function index()
	{

		$start_point  = 0;
		$end_point    = 100;
		//$current_date =date('Y-m-d');	
		$current_date ="2019-09-27";	
		/*Cron LIMIT */
						 $this->db->where(" date(created_at) = '".$current_date."'");
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists))
		{
			$start_point = count($is_cron_exists)*$end_point;
			 
		}
		$this->cron_add($start_point,$end_point);
		/*Cron LIMIT */


		/* Fetch the data from the exam table where paymnet status is 0 */
		$arr_referal  = array();
		$status       = 1;
		$pay_type     = 2;
 		$this->db->select('payment_transaction.member_regnumber,payment_transaction.date,payment_transaction.ref_id,payment_transaction.exam_code,payment_transaction.receipt_no,payment_transaction.pay_type,payment_transaction.status,payment_transaction.date,payment_transaction.transaction_no');
		$this->db->where("date >='".$current_date."'");
	 	$this->db->where("status",$status);
	 	$this->db->where("pay_type",$pay_type);
	 	$this->db->limit($end_point,$start_point);
	 	$result = $this->Master_model->getRecords('payment_transaction');	
		//echo $this->db->last_query();exit;
		if(!empty($result))
 		{
 		 
 		 	foreach($result as $res )
 			{	
 				$str_reason = '';
 				$arr_member_exam = $exam_invoice = array();
				$arr_member_exam = $this->check_member_exam($res['ref_id']);
				$arr_exam_invoice    = $this->check_exam_invoice($res['receipt_no']);
				print_r($arr_exam_invoice); echo "<br>";
			/*	echo "==>".$exam_invoice.'<br/>';*/
				$is_insert_in_settlement = 0;
 				if(!empty($arr_exam_invoice) && count($arr_exam_invoice)>0)
 				{
 					$is_new_record = isset($arr_exam_invoice['is_new_record'])?$arr_exam_invoice['is_new_record']:'';
 					$str_reason .= isset($arr_exam_invoice['str_reason'])&&$arr_exam_invoice['str_reason']!=''?$arr_exam_invoice['str_reason']:'';
					
					if( $is_new_record == 1  ||  $is_new_record == 0 )
	 				{
	 					$is_insert_in_settlement = 1; 	
	 				}
 				}
 						 
 				if(!empty($arr_member_exam) && $arr_member_exam['pay_status'] == 0)
 				{
 					$is_insert_in_settlement = 1; 
 					$str_reason .= " pay_status = 0 from member_exam tbl";
 				}

 				

 				/*echo $is_new_record."<br/>"; die();*/
 				if($is_insert_in_settlement == 1 )
 				{
 					echo $is_new_record;echo"<br/>";

 					$val_is_new_record = $this->val_is_new_record($is_new_record);
 					$this->exam_settlement($res,$arr_member_exam,$val_is_new_record,$str_reason);
 				}
 			}
 			$this->settle_exam_invoice();
 		}
		echo "Done..!!";
	}
 	

 	/*settle the exam invoice table */
 	public function settle_exam_invoice()
 	{
		//////die();
 	 	$this->db->where('refund_case','0');
 		$isExists = $this->master_model->getRecords('exam_invoice_settlement');

 		if(!empty($isExists) && count($isExists) > 0)
 		{
 			foreach ($isExists as $key => $res)
 			{
 				$is_new_record = '0';
 				/* check record exists in exam invoice */
 				$this->db->where('receipt_no',$res['receipt_no']);
 				$this->db->where('exam_code',$res['exam_code']);
 				$this->db->where('exam_period',$res['exam_period']);
 				$is_exists_exam_invoice = $this->master_model->getRecords('exam_invoice');
 				if(count($is_exists_exam_invoice) > 0 && !empty($is_exists_exam_invoice) )
 				{
 					$arr_update = array();
 					$invoice_id = $is_exists_exam_invoice[0]['invoice_id'];


 					if(($is_exists_exam_invoice[0]['transaction_no'] != '' ) && 
 						/*($is_exists_exam_invoice[0]['member_no']  !='' ) && 
 						($is_exists_exam_invoice[0]['date_of_invoice'] !='' && $is_exists_exam_invoice[0]['date_of_invoice'] != '0000-00-00 00:00:00' ) &&
						($is_exists_exam_invoice[0]['modified_on'] !='' && $is_exists_exam_invoice[0]['modified_on'] != '0000-00-00 00:00:00' ) &&  */						
						($is_exists_exam_invoice[0]['invoice_no'] != '') && 
						($is_exists_exam_invoice[0]['invoice_image'] != '')	
 						)
 					{
 						$is_new_record = '2';
 					}
 					else
 					{

 						$str_falut =  'Updated columns are : ';
 						/* Update the exam invoice code*/
 						if(($is_exists_exam_invoice[0]['transaction_no']  =='' ))
	 					{
	 						$arr_update['transaction_no'] = trim($res['transaction_no']);
	 						$str_falut .="transaction_no,";
	 					}

	 					$arr_update['member_no'] = trim($res['member_regnumber']);
	 					/*if(($is_exists_exam_invoice[0]['member_no']  =='' ))
	 					{
	 						$str_falut .="member_no,";
	 					}*/

	 					if(($is_exists_exam_invoice[0]['date_of_invoice'] !='' && $is_exists_exam_invoice[0]['date_of_invoice'] == '0000-00-00 00:00:00' ))
	 					{
	 						$arr_update['date_of_invoice'] = trim($res['payment_date']);
	 						$str_falut .="date_of_invoice,";
	 					}

	 					if(($is_exists_exam_invoice[0]['modified_on'] !='' && $is_exists_exam_invoice[0]['modified_on'] == '0000-00-00 00:00:00' ))
	 					{
	 						$arr_update['modified_on'] = trim($res['payment_date']);
	 						$str_falut .="modified_on,";
	 					}

	 					
	 					/* check invoice id in config table  */
						$config_auto_id =  $this->check_config($invoice_id); 	
						
						if( $is_exists_exam_invoice[0]['invoice_no'] == '')
						{
							$invoice_no = $this->generate_invoice_no_image($config_auto_id,'no');			
							$arr_update['invoice_no'] = $invoice_no;
							$str_falut .="invoice_no,";
						}
						if( $is_exists_exam_invoice[0]['invoice_image'] == '')
						{
							$invoice_image = $this->generate_invoice_no_image($config_auto_id,'image',$res['member_regnumber']);			
							$arr_update['invoice_image'] = $invoice_image;
							$str_falut .="invoice_image,";
						}


						if(!empty($arr_update) && count($arr_update) >=1)
	 					{

	 						if($this->master_model->updateRecord('exam_invoice',$arr_update,array('invoice_id' => $invoice_id )))
	 						{

								$this->updatePaymentStatus($res['ref_id'],$res['member_regnumber']);

								/* update the status for why its here */
							/*	$this->master_model->updateRecord('exam_invoice_settlement',array('str_reason'=>$str_falut),array('id' => $res['id']));*/
								/* update the status for why its here */
							};
	 					}
	 					else
	 					{
	 						$is_new_record = '2'; /* for safer side */
	 					}

 					}

 				}
 				else
 				{
 					/*call the insert into invoice*/
 					$is_new_record = '1';
 					$new_receipt_no = array();
 					array_push($new_receipt_no,$res['receipt_no']);
 					dynamic_invoice_generation($new_receipt_no);
 					$this->updatePaymentStatus($res['ref_id'],$res['member_regnumber']);
 					
 					/* update the status for why its here */
					/*$this->master_model->updateRecord('exam_invoice_settlement',array('str_reason'=>'New entry added by us'),array('id' => $res['id']));*/
					/* update the status for why its here */

 					/*call the insert into invoice*/
 				}
 				/* check record exists in exam invoice */

 				/* update the flag from exam_settlement as the invoice is created by us or its there etc
					is_new_record = 1  its added by us
					is_new_record = 0  its there and updated by us
					is_new_record = 2  its there and already settled
				*/
				/*echo "<br/>".$is_new_record.'==> '.$res['id'];*/
				/*$this->master_model->updateRecord('exam_invoice_settlement',
					array('is_new_record' => $is_new_record),array('id' =>$res['id']));*/



 			}

 		}

 	}
 	/*settle the exam invoice table */


 	public function check_config($invoice_id)
 	{
 		if(isset($invoice_id) && $invoice_id!='')
 		{
			$this->db->where('invoice_id',$invoice_id);
			$is_exists_in_config = $this->master_model->getRecords('config_exam_invoice');
			if(!empty($is_exists_in_config) && count($is_exists_in_config)>0)
			{
				$config_auto = $is_exists_in_config[0]['exam_invoice_no'];
			}
			else
			{
				$config_auto =  $this->master_model->insertRecord('config_exam_invoice',array('invoice_id'=>$invoice_id),'true');

			}
 		}

 		return $config_auto;
 	}

 	public function generate_invoice_no_image($config_auto_id,$type,$member_no='')
 	{
 		$cal_year = $str_return  =   '';
 		$cal_year = date('y'); 
		$next_year = date('y')+1; 
		$str_year = $cal_year.'-'.$next_year; 
 		if($type == 'image' && $member_no!='')
 		{
 			$str_return = $member_no."_EX_".$str_year.'_'.$config_auto_id.'.jpg';
 		}
 		else if($type == 'no')
 		{
 			$str_return =$this->config->item('exam_invoice_no_prefix').$config_auto_id;
 		}
 		return $str_return;
 	}


 	public function updatePaymentStatus($ref_id,$member_regnumber)
 	{
 		if($ref_id!='' && $member_regnumber!='')
 		{
 			$update_payment_status = array('pay_status'  => '1');
			$this->master_model->updateRecord('member_exam',$update_payment_status,array('id'=>$ref_id,'regnumber'=>$member_regnumber));
 		}
 	}


 	public function exam_settlement($res,$arr_member_exam,$is_new_record,$str_reason='')
 	{

	 

 		$exam_period = isset($arr_member_exam['exam_period'])&&$arr_member_exam['exam_period']!=''?$arr_member_exam['exam_period']:0;
 		$exam_code = isset($arr_member_exam['exam_code'])&&$arr_member_exam['exam_code']!=''?$arr_member_exam['exam_code']:0;

 		$insert_data = array();
		$insert_data = array(
						"exam_code"        => $exam_code, /* from member exam */
						"exam_period"      => $exam_period,  /* from member exam */
						"member_regnumber" => $res['member_regnumber'],
						"ref_id"           => $res['ref_id'],
						"receipt_no"       => $res['receipt_no'],
						"pay_type"         => $res['pay_type'],
						"status"           => $res['status'],
						"pay_status"       => isset($arr_member_exam['pay_status'])&&$arr_member_exam['pay_status']!=''?$arr_member_exam['pay_status']:0,  /* from member exam */
						'transaction_no'   => $res['transaction_no'],
						'payment_date' 	   => $res['date'],
						'is_new_record'    => $is_new_record,
						'str_reason'       => $str_reason
						/* Exam invoice  conditions */ 
					);
		/* rest of all from payment transaction */
		 print_r($insert_data);echo "<br/>";
		$where = array(
						"exam_code"        => $exam_code,
						"exam_period"      => $exam_period,
						"member_regnumber" => $res['member_regnumber'],
						"ref_id"           => $res['ref_id'],
						"receipt_no"       => $res['receipt_no']
					);
		$isExists = $this->master_model->getRecords('exam_invoice_settlement',$where);
		
		if(count($isExists) == 0)
		{
				/* INSERT into exam invoice settlement tbl */
				if($this->master_model->insertRecord('exam_invoice_settlement',$insert_data))
				{

					/* update the cancelation status */
					$isExistsCancelation = array();
					$this->db->where('mem_exam_id',$res['ref_id']);
					$this->db->where('mem_mem_no',$res['member_regnumber']);
					$this->db->where('remark','3');
					$isExistsCancelation = $this->master_model->getRecords('admit_card_details');	
					if(!empty($isExistsCancelation) && count($isExistsCancelation) > 0)
					{
						$update_where = array(
											"member_regnumber" => $res['member_regnumber'],
											"ref_id"           => $res['ref_id'],
											"receipt_no"       => $res['receipt_no']
										);
						$arr_update = array(
										'refund_case' => '1',
										'str_reason'  => 'Refund Case'
									);
						
						$this->master_model->updateRecord('exam_invoice_settlement',$arr_update,$update_where);
						/* update the cancelation status */
				 
					}
					else
					{

						/* update the payment status if its not refund case */	
					/*	if($res['date']!='' && $res['date']!='0000-00-00 00:00:00')
						{

							$update_payment_status = array(
									  	'modified_on' => $res['date']
										);

							$this->master_model->updateRecord('member_exam',$update_payment_status,array('id'=>$res['ref_id'],'regnumber'=>$res['member_regnumber']));
						} */
					}
				}	
		}
 	}


 	public function check_member_exam($ref_id)
 	{
 		if($ref_id != '')
 		{
 			$this->db->select('pay_status,exam_period,exam_code');
 			$this->db->where('id',$ref_id);
 			$exam_invoice_result = $this->master_model->getRecords('member_exam');
 			if(!empty($exam_invoice_result) && count($exam_invoice_result) > 0)
 			{
				return $exam_invoice_result[0];
 			}
 			return '';
 		}
 	}

 	public function check_exam_invoice($receipt_no)
 	{
 		/* update the flag from exam_settlement as the invoice is created by us or its there etc
					is_new_record = 1  its added by us
					is_new_record = 0  its there and updated by us
					is_new_record = 2  its there and already settled
		*/
 		$str_reason = $is_new_record = '';
		/* check record exists in exam invoice */
		$this->db->where('receipt_no',$receipt_no);
		$is_exists_exam_invoice = $this->master_model->getRecords('exam_invoice');
		if(count($is_exists_exam_invoice) > 0 && !empty($is_exists_exam_invoice) )
		{
			$arr_update = array();
			$invoice_id = $is_exists_exam_invoice[0]['invoice_id'];


			if(($is_exists_exam_invoice[0]['transaction_no'] != '' ) && 
				/*($is_exists_exam_invoice[0]['member_no']  !='' ) && */
				/*($is_exists_exam_invoice[0]['date_of_invoice'] !='' && $is_exists_exam_invoice[0]['date_of_invoice'] != '0000-00-00 00:00:00' ) &&*/
			/*($is_exists_exam_invoice[0]['modified_on'] !='' && $is_exists_exam_invoice[0]['modified_on'] != '0000-00-00 00:00:00' ) &&  						*/
			($is_exists_exam_invoice[0]['invoice_no'] != '') && 
			($is_exists_exam_invoice[0]['invoice_image'] != '')	
				)
			{
				$is_new_record = 2;
				$str_reason .= "Already exists and settled";
			}
			else
			{
 				
 				$str_reason .=  'Updated columns are : ';
				/* Update the exam invoice code*/
				if(($is_exists_exam_invoice[0]['transaction_no']  == '' ))
				{
					$str_reason .="transaction_no,";
					$is_new_record = 0;
				}

				if( $is_exists_exam_invoice[0]['invoice_no'] == '')
				{
				 	$str_reason .="invoice_no,";
				 	$is_new_record = 0;
				}

				if( $is_exists_exam_invoice[0]['invoice_image'] == '')
				{
					$str_reason .="invoice_image,";
					$is_new_record = 0;
				}
 			 }

		}
		else
		{
			/*call the insert into invoice*/
			$is_new_record = 1;
			$str_reason .= "New record added by us";
			 
		}
		$arr_return['is_new_record'] = $is_new_record;
		$arr_return['str_reason'] = $str_reason;
		return $arr_return;
 	}


 	public function cron_add($start_point,$end_point)
 	{
 	 
	 		$insert_limit = array(
										'start_point' => $start_point,
										'end_point'   => $end_point
									);
			$this->Master_model->insertRecord('cron_limit',$insert_limit);
 		 
 	}

 	public function val_is_new_record($data)
 	{		$retun_status = '';
 			switch ($data) {
 				case 0:
 					$retun_status = 'already_exists_need_to_settle';
 					break;

				case 1:
 					$retun_status = 'new_record_added_by_us';
 					break;

				case 2:
 					$retun_status = 'exists_and_settled';
 					break;

 			}
 			return $retun_status;
 	}
}