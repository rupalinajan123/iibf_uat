<?php
/*
 * Controller Name	:	Cron Exam Invoice Settlement
 * Created By		:	Padmashri Joshi
 * Created Date		:	24-09-2019
 * Last Update 		:   13-03-2020
*/
defined('BASEPATH') OR exit('No direct script access allowed');
class Cron_exam_invoice_settlement_cust  extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->model('Master_model');
		$this->load->model('log_model');
		error_reporting(E_ALL);
		ini_set("display_errors", 1);
		exit;
	}
	
	/*
	  To Settle the exam invoice automatically
	*/
	// https://iibf.esdsconnect.com/admin/Cron_exam_invoice_settlement/index
	// /usr/local/bin/php /home/supp0rttest/public_html/index.php admin Cron_exam_invoice_settlement index
	
	public function index()
	{
		 
		$start_point  = 0;
		$end_point    = 500;
		//$current_date =date('Y-m-d');	
		$current_date ="2020-03-11";	
		/*Cron LIMIT */
		$module_type = 'exam_invoice_cust';
		$this->db->where(" date(created_at) = '".$current_date."'");
		$this->db->where("module_type",$module_type);
		$is_cron_exists = $this->Master_model->getRecords('cron_limit'); 
	  	if(count($is_cron_exists)  > 0 && !empty($is_cron_exists))
		{
			$start_point = count($is_cron_exists)*$end_point;
		}
		$this->cron_add($start_point,$end_point,$current_date,$module_type);
		/*Cron LIMIT */


		/* Fetch the data from the exam table where paymnet status is 0 */
		$arr_referal  = array();
		$status       = 1;
		$pay_type     = 2;
 		$this->db->select('payment_transaction.member_regnumber,payment_transaction.date,payment_transaction.ref_id,payment_transaction.exam_code,payment_transaction.receipt_no,payment_transaction.pay_type,payment_transaction.status,payment_transaction.date,payment_transaction.transaction_no,payment_transaction.id as payment_auto_inc');
		$this->db->where("date(date) ='".$current_date."'");
	 	$this->db->where("status",$status);
	 	$this->db->where("pay_type",$pay_type);
	 	$this->db->limit($end_point,$start_point);
	 	//$this->db->where('id IN (2158453,2158452,2158451)');
	 	$result = $this->Master_model->getRecords('payment_transaction');	
	 	//print_r($result).'<br/><br/><br/>'; 
		//echo '<br/>sql 1 => '.$this->db->last_query().'<br/><br/><br/>'; 
		//exit;
		if(!empty($result))
 		{
 		 	$flag=0;
 		 	foreach($result as $res )
 			{	
 				$str_reason = '';
 				$arr_member_exam = $exam_invoice = array();
				$arr_member_exam = $this->check_member_exam($res['ref_id']);
				$arr_exam_invoice    = $this->check_exam_invoice($res['receipt_no']);
			//	print_r($arr_exam_invoice); echo "<br>";
				/*	echo "==>".$exam_invoice.'<br/><br/><br/>';*/
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
					$flag=1;
 					$val_is_new_record = $this->val_is_new_record($is_new_record);
 					$this->exam_settlement($res,$arr_member_exam,$val_is_new_record,$str_reason);
 				}
 			}
			if($flag==1)
			{
				$this->settle_exam_invoice();
				$this->generate_invoice_images();
			}

 		}
		else
		{
			$arr_update = array('created_at' => '0000-00-00');
			$this->master_model->updateRecord('cron_limit',$arr_update,array('created_at' => $current_date,'module_type'=>'exam_invoice_cust'));
			redirect(base_url().'Cron_exam_invoice_settlement');
		}
		echo "Done..!!";
	}
 	

 	/*settle the exam invoice table */
 	public function settle_exam_invoice()
 	{
		//////die();
		$this->db->select('receipt_no,exam_code,exam_period,ref_id,member_regnumber,id,transaction_no,payment_date');
		$this->db->where('record_type','exam_invoice');
 	 	$this->db->where('refund_case','0');
		$this->db->where('is_settle','no');
		$isExists = $this->master_model->getRecords('exam_invoice_settlement');
		//echo "First".$this->db->last_query().'<br/><br/><br/>';
 		if(!empty($isExists) && count($isExists) > 0)
 		{
 			foreach ($isExists as $key => $res)
 			{
 				$is_new_record = '0';
 				$is_new_image  = 'none';
 				$arr_update_settlement['is_settle'] ='yes';
 				/* check record exists in exam invoice */
 				$this->db->select('transaction_no,invoice_image,date_of_invoice,modified_on,invoice_no,invoice_id,receipt_no,exam_code,exam_period');
 				$this->db->where('receipt_no',$res['receipt_no']);
 				$this->db->where('exam_code',$res['exam_code']);
 				$this->db->where('exam_period',$res['exam_period']);
 				$is_exists_exam_invoice = $this->master_model->getRecords('exam_invoice');
 				//echo "Secoound ".$this->db->last_query().'<br/><br/><br/>'.print_r($is_exists_exam_invoice);  

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
											   $this->db->select('id,pay_status,regnumber');
											   $this->db->where('pay_status','0');
				     	$isExistsMemberExam =  $this->master_model->getRecords('member_exam',array('id'=>$res['ref_id'],'regnumber'=>$res['member_regnumber']));
				     	//echo "Three ".$this->db->last_query().'<br/><br/><br/>';
							
						if(count($isExistsMemberExam ) > 0 )
						{
							$this->updatePaymentStatus($res['ref_id'],$res['member_regnumber']);
							$this->update_exam_settlement($res['id'],$arr_update_settlement);
						}

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
							$arr_update_settlement['is_image_created'] = 'need_to_create';/* we need to create new image */
						}


						if(!empty($arr_update) && count($arr_update) >=1)
	 					{

	 						if($this->master_model->updateRecord('exam_invoice',$arr_update,array('invoice_id' => $invoice_id )))
	 						{

								$this->updatePaymentStatus($res['ref_id'],$res['member_regnumber']);

								/* update the status for why its here */
								
								$this->update_exam_settlement($res['id'],$arr_update_settlement);
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
 					$arr_update_settlement['is_image_created'] = 'need_to_create';
 					$this->update_exam_settlement($res['id'],$arr_update_settlement);
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
 			$this->db->select('exam_invoice_no,invoice_id');
			$this->db->where('invoice_id',$invoice_id);
			$is_exists_in_config = $this->master_model->getRecords('config_exam_invoice');
			//echo "four ".$this->db->last_query().'<br/><br/><br/>';
			
			if(!empty($is_exists_in_config) && count($is_exists_in_config)>0)
			{
				$config_auto = $is_exists_in_config[0]['exam_invoice_no'];
			}
			else
			{
				/*$config_auto =  $this->master_model->insertRecord('config_exam_invoice',array('invoice_id'=>$invoice_id),'true');*/
				$insert_info1 = array('invoice_id'=>$invoice_id);
				$config_auto = str_pad($this->master_model->insertRecord('config_exam_invoice',$insert_info1,true), 6, "0", STR_PAD_LEFT); 


			}
 		}

 		return $config_auto;
 	}

 	public function generate_invoice_no_image($config_auto_id,$type,$member_no='')
 	{
 		$cal_year = $str_return  =   '';
 		$cal_year = 20; 
		$next_year = 21; 
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
						'str_reason'       => $str_reason,
						'record_type'      => "exam_invoice",
						'payment_auto_inc' => $res['payment_auto_inc']
						/* Exam invoice  conditions */ 
					);
		/* rest of all from payment transaction */
		// print_r($insert_data);echo "<br/>";
		$this->db->select('exam_code,exam_period,member_regnumber,ref_id,receipt_no,record_type');
		$where = array(
						"exam_code"        => $exam_code,
						"exam_period"      => $exam_period,
						"member_regnumber" => $res['member_regnumber'],
						"ref_id"           => $res['ref_id'],
						"receipt_no"       => $res['receipt_no'],
						"record_type"	   => "exam_invoice"
					);
		//echo "five ".$this->db->last_query().'<br/><br/><br/>';
		$isExists = $this->master_model->getRecords('exam_invoice_settlement',$where);
		
		if(count($isExists) == 0)
		{
				/* INSERT into exam invoice settlement tbl */
				if($this->master_model->insertRecord('exam_invoice_settlement',$insert_data))
				{

					/* update the cancelation status */
					$isExistsCancelation = array();
					$this->db->select('mem_exam_id,mem_mem_no,remark');
					$this->db->where('mem_exam_id',$res['ref_id']);
					$this->db->where('mem_mem_no',$res['member_regnumber']);
					$this->db->where('remark','3');
					$isExistsCancelation = $this->master_model->getRecords('admit_card_details');
					//echo "six ".$this->db->last_query().'<br/><br/><br/>';		
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
						if($res['date']!='' && $res['date']!='0000-00-00 00:00:00')
						{

							$update_payment_status = array(
									  	'modified_on' => $res['date']
										);

							$this->master_model->updateRecord('member_exam',$update_payment_status,array('id'=>$res['ref_id'],'regnumber'=>$res['member_regnumber']));
						} 
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
 			/*$this->db->where('pay_status','0');*/

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
		$this->db->select('transaction_no,invoice_no,invoice_image,receipt_no,invoice_id,member_no,modified_on,date_of_invoice');
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


 	public function cron_add($start_point,$end_point,$current_date,$module_type)
 	{
 	 
	 		$insert_limit = array(
										'start_point' => $start_point,
										'end_point'   => $end_point,
										'created_at'  => $current_date,
										'module_type' => $module_type
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


 	public function update_exam_settlement($id,$arr_update)
 	{
 		if(is_numeric($id)&&!empty($arr_update) && count($arr_update) > 0)
 		{
 			$this->master_model->updateRecord('exam_invoice_settlement',$arr_update,array('id'=>$id));
 		}
 	}

 	/* function to generate invoice images which we have settled*/
 	public function generate_invoice_images()
 	{
 		$this->db->select('id,receipt_no,ref_id');
 		$this->db->where('is_image_created','need_to_create');
 		$this->db->where('record_type','exam_invoice');
 		$isExists = $this->master_model->getRecords('exam_invoice_settlement');
 		//echo "seven ".$this->db->last_query().'<br/><br/><br/>';		
 		if(!empty($isExists) && count($isExists) > 0)
 		{
 			//print_r($isExists);
 			foreach ($isExists as $key => $res)
 			{	
 							   $this->db->select('receipt_no,invoice_id');
 				$arr_invoice = $this->Master_model->getRecords('exam_invoice',array('receipt_no' => $res['receipt_no']));
 				//echo "eight ".$this->db->last_query().'<br/><br/><br/>';		

 				if(!empty($arr_invoice))
 				{
 					$invoice_id = isset($arr_invoice[0]['invoice_id'])&&$arr_invoice[0]['invoice_id']!=''?$arr_invoice[0]['invoice_id']:''; 		
 					if($invoice_id!='' && is_numeric($invoice_id))
 					{

 						echo $path = custom_genarate_exam_invoice_newdesign($invoice_id);
		 		 		echo "<br/>";
		 		 		if(isset($path) && $path!='')
		 		 		{
		 		 			$arr_update['is_image_created'] = 'created_by_us';
		 		 			$this->update_exam_settlement($res['id'],$arr_update);
		 		 		}
 					}
 				}
		 		
		 		 
		 	  
 			}
 		}
 	}
}