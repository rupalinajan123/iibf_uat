<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Refund_details extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		/* if($this->session->id==""){
			redirect('admin/Login'); 
		}	 */	
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('KYC_Log_model'); 
	}

public function seatfull_cases()
{
	$records = array();
	$data['reg_num_res'] = array();
	//$date = date('Y-m-d');
	//$this->db->where('DATE(res_date) >=',$date );
	$get_seat_full = $this->master_model->getRecords('S2S_direcrt_refund',array());
	
	if(isset($get_seat_full))
	{
		foreach($get_seat_full as $res)
		{
			$receipt_no = $res['receipt_no'];
			$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , b.namesub,b.firstname, b.lastname,a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date';
			$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
			$this->db->order_by('a.date', "desc");
			$reg_num_res =  $this->master_model->getRecords("payment_transaction a",array('a.receipt_no'=>$receipt_no),$select);
			//echo $this->db->last_query(); exit;
			
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								
							
							$i++;
						}
						
					}
					 $data['reg_num_res'][] = $records; 
					//print_r($records);
		}
		
	}
	 
	$this->load->view('refund_details/seat_full_details',$data);
}


public function seatfull_cases_old()
{
	$SearchVal = '';
	$searchBy = '';
	$searchOn = '';
	$where = " `b.isactive` = '1' AND `b.isdeleted` = 0 ";
	$trnWhr = '';
	$recWhr = " `a.status` = '1'";
	$data = '';
	$records = array();
	 $data['reg_num_res'] = array();
	if(isset($_POST['btnSearch']))
	{ //print_r($_POST); 
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{ 
				 $searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{	 
				 $SearchVal = trim($_POST['SearchVal']);
			}
			if($searchBy !='' && $SearchVal != '')
			{
					switch($searchBy)
				{
					case 'regnumber' 		: 	$where .= ' AND b.regnumber = "'.$SearchVal.'"';
												
												break;
												
					//case 'mobile' 			: 	$where .= ' AND b.mobile = "'.$SearchVal.'"';
												
												break;
					case 'transaction_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.transaction_no = "'.$SearchVal.'"';
												break;
					//case 'email' 	: 			$where .= ' AND b.email = "'.$SearchVal.'"';
												break;
					case 'receipt_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.receipt_no = "'.$SearchVal.'"';
												break;							
				}
				
				if($searchBy == 'transaction_no')
				{
					$select = 'DISTINCT (`c`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
					$this->db->join('S2S_direcrt_refund c','c.receipt_no=a.receipt_no','LEFT');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.transaction_no'=>$SearchVal),$select);
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='<html> Success </html>';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				else if($searchBy == 'receipt_no')
				{	
					$select = 'DISTINCT (`c`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
					$this->db->join('S2S_direcrt_refund c','c.receipt_no=a.receipt_no','LEFT');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.receipt_no'=>$SearchVal),$select);
					
					//echo $this->db->last_query();
					
					if(!empty($reg_num_res))
					{	//print_r($reg_num_res);
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								//print_r($reg_num_res['receipt_no']); echo '<br>';
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				else if($searchBy == 'regnumber') 
				{ 
					$select = 'DISTINCT (`c`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
					$this->db->join('S2S_direcrt_refund c','c.receipt_no=a.receipt_no','LEFT');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.member_regnumber'=>$SearchVal),$select);
					
					if(!empty($reg_num_res))
					{
							$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];

								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
				}
								
			  
			}
	}
	
	$this->load->view('refund_details/seatfullcases_search',$data);
}

public function admitcard()
{
	$SearchVal = '';
	$searchBy = '';
	//$searchOn = '';
	$where = " `isactive` = '1' AND `isdeleted` = 0 ";
	$trnWhr = '';
	$recWhr = '';
	$data = '';
	//$records = array();
	$invoice_records = array();
	
	if(isset($_POST['btnSearch']))
	{ //print_r($_POST); 
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{ 
				 $searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{	 
				 $SearchVal = trim($_POST['SearchVal']);
			}
			if($searchBy !='' && $SearchVal != '')
			{
					switch($searchBy)
				{
					case 'regnumber' 		: 	$where .= ' AND a.member_regnumber = "'.$SearchVal.'"';
												
												break;
					case 'transaction_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$payWhr = ' b.transaction_no = "'.$SearchVal.'" AND a.isdeleted = 0';
												break;
					case 'receipt_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr = ' b.receipt_no = "'.$SearchVal.'" AND a.isdeleted = 0';
												break;							
				}
				if($searchBy == 'regnumber')
				{
					$this->db->DISTINCT('a.mem_exam_id');
					$select = 'a.mem_exam_id , a.exm_cd , a.exm_prd , a.created_on , a.admitcard_image ,a.mem_mem_no , b.receipt_no , b.transaction_no , b.description';
					$this->db->join('payment_transaction b','b.ref_id=a.mem_exam_id','LEFT');
					$this->db->order_by('b.date', "desc");
					$data['admit_card_data'] = $admit_card_data =  $this->master_model->getRecords("admit_card_details a", array('a.mem_mem_no'=>$SearchVal, 'b.pay_type'=>'2'),$select);
				}
				else if($searchBy == 'receipt_no')
				{
					$this->db->DISTINCT('a.mem_exam_id');
					$select = 'a.mem_exam_id , a.exm_cd , a.exm_prd , a.created_on , a.admitcard_image ,a.mem_mem_no , b.receipt_no , b.transaction_no , b.description';
					$this->db->join('admit_card_details a','a.mem_exam_id=b.ref_id','LEFT');
					$this->db->order_by('b.date', "desc");
					$data['admit_card_data'] = $admit_card_data =  $this->master_model->getRecords("payment_transaction b", array('b.receipt_no'=>$SearchVal, 'b.pay_type'=>'2'));
				}
				else if($searchBy == 'transaction_no')
				{
					/* $payment_details = $this->master_model->getRecords("payment_transaction b", array('b.transaction_no'=>$SearchVal));
					if(!empty($payment_details))
					{
						foreach($payment_details as $payment_res)
						{
							$ref_id = $payment_res['ref_id'];
							$exam_code = $payment_res['ref_id'];
						}
					} */
					$this->db->DISTINCT('a.mem_exam_id');
					$select = 'a.mem_exam_id , a.exm_cd , a.exm_prd , a.created_on , a.admitcard_image ,a.mem_mem_no , b.receipt_no , b.transaction_no , b.description';
					$this->db->join('admit_card_details a','a.mem_exam_id=b.ref_id','LEFT');
					$this->db->order_by('b.date', "desc");
					$data['admit_card_data'] = $admit_card_data =  $this->master_model->getRecords("payment_transaction b", array('b.transaction_no'=>$SearchVal , 'b.pay_type'=>'2'));
				}
			}
	}
     $this->load->view('refund_details/admitcard_search',$data);
}	
	
public function refund()
{	
	$SearchVal = '';
	$searchBy = '';
	$searchOn = '';
	$where = " `b.isactive` = '1' AND `b.isdeleted` = 0 ";
	$trnWhr = '';
	$recWhr = " `a.status` = '1'";
	$data = '';
	$records = array();
	 $data['reg_num_res'] = array();
	if(isset($_POST['btnSearch']))
	{ //print_r($_POST); 
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{ 
				 $searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{	 
				 $SearchVal = trim($_POST['SearchVal']);
			}
			if($searchBy !='' && $SearchVal != '')
			{
					switch($searchBy)
				{
					case 'regnumber' 		: 	$where .= ' AND b.regnumber = "'.$SearchVal.'"';
												
												break;
												
					case 'mobile' 			: 	$where .= ' AND b.mobile = "'.$SearchVal.'"';
												
												break;
					case 'transaction_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.transaction_no = "'.$SearchVal.'"';
												break;
					case 'email' 	: 			$where .= ' AND b.email = "'.$SearchVal.'"';
												break;
					case 'receipt_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.receipt_no = "'.$SearchVal.'"';
												break;							
				}
				
				if($searchBy == 'transaction_no')
				{
					$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.transaction_no'=>$SearchVal));
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='<html> Success </html>';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				else if($searchBy == 'receipt_no')
				{	
					//$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.receipt_no'=>$SearchVal));
					if(!empty($reg_num_res))
					{	//print_r($reg_num_res);
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								//print_r($reg_num_res['receipt_no']); echo '<br>';
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				else if($searchBy == 'regnumber') 
				{
					$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.member_regnumber'=>$SearchVal),$select);
					//echo $this->db->last_query();
					if(!empty($reg_num_res))
					{
							$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];

								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
				}
				else if($searchBy == 'email')
				{ 
					$reg_num_data = $this->master_model->getRecords("member_registration b", array('email'=>$SearchVal));
					//echo $this->db->last_query(); die;
					if(!empty($reg_num_data))
					{ 
						foreach($reg_num_data as $reg_res)
						{
							 $regnumber = $reg_res['regnumber'];
							 $email = $reg_res['email'];
							if($regnumber == '')
							{ 
								
									$this->db->where('b.email',$email);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email , b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								//echo $this->db->last_query(); die;
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
									
								}
								
								//echo $this->db->last_query();
							 
								
							}
							else{ 
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
								
								//echo $this->db->last_query(); 
							}
						}
					}	
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				} /*else if($searchBy == 'email')
				{
					//$this->db->join('payment_transaction b','a.regnumber=b.member_regnumber','INNER');
					//$this->db->order_by('b.date', "desc");
					$reg_num_data = $this->master_model->getRecords("member_registration a", array('a.email'=>$SearchVal));
					
					if(!empty($reg_num_data))
					{
						//$select = 'a.regnumber , b.receipt_no , b.transaction_no , b.amount , b.status , b.description ,a.email , a.mobile , b.date ';
						//$this->db->join('payment_transaction b','b.member_regnumber = a.regnumber','INNER',FALSE);
						//$this->db->order_by('b.date', "desc");
						$this->db->where('a.email',$SearchVal);
						$this->db->having('MKID > ', 0);
						$reg_num_res =  $this->master_model->getRecords("member_registration a" , '' , 'a.regnumber,a.email , a.mobile, (SELECT receipt_no FROM payment_transaction `b` WHERE member_regnumber = a.regnumber Order BY date desc LIMIT 1 ) AS MKID', '');
						//echo '<pre>'; print_r($reg_num_res); 
						//echo $this->db->last_query();  
						$receipt_no = $reg_num_res[0]['MKID'];
						$reg_num_res_pay =  $this->master_model->getRecords("payment_transaction a", array('a.receipt_no'=>$receipt_no));
						echo $this->db->last_query(); 
					}
					else{
						$select = 'a.regnumber , b.receipt_no , b.transaction_no , b.amount , b.status , b.description ,a.email , a.mobile , b.date ';
						$this->db->join('payment_transaction b','b.ref_id=a.regid','LEFT');
						$this->db->order_by('b.date', "desc");
						$reg_num_res =  $this->master_model->getRecords("member_registration a", array('a.email'=>$SearchVal),$select);
					}
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['MKID'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				*/
				else if($searchBy == 'mobile')
				{
						$reg_num_data =  $this->master_model->getRecords("member_registration b", array('b.mobile'=>$SearchVal));
					
					
					if(!empty($reg_num_data))
					{ 
						foreach($reg_num_data as $reg_res)
						{
							 $regnumber = $reg_res['regnumber'];
							if($regnumber == '')
							{
								
									$this->db->where('b.mobile',$SearchVal);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email , b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
									
								}
								
							}
							else{ 
							
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','INNER');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
								
								
							}
						}
					}
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['amount']=$reg_num_res['amount'];
							$records[$i]['pay_type']=$reg_num_res['pay_type'];
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							//$records[$i]['refund_type']=$refund_info['refund_type'];
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
			  
			}
	}
	
	$this->load->view('refund_details/refund_search',$data);	
}

public function invoice()
{
	$SearchVal = '';
	$searchBy = '';
	//$searchOn = '';
	$where = " `isactive` = '1' AND `isdeleted` = 0 ";
	$trnWhr = '';
	$recWhr = '';
	$data = '';
	//$records = array();
	$invoice_records = array();
	
	if(isset($_POST['btnSearch']))
	{ //print_r($_POST); 
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{ 
				 $searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{	 
				 $SearchVal = trim($_POST['SearchVal']);
			}
			if($searchBy !='' && $SearchVal != '')
			{
					switch($searchBy)
				{
					case 'regnumber' 		: 	$where .= ' AND a.member_regnumber = "'.$SearchVal.'"';
												
												break;
					case 'transaction_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$payWhr = ' b.transaction_no = "'.$SearchVal.'" AND a.isdeleted = 0';
												break;
					case 'receipt_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr = ' b.receipt_no = "'.$SearchVal.'" AND a.isdeleted = 0';
												break;							
				}
				if($searchBy == 'transaction_no')
				{
					$this->db->join('payment_transaction b','b.receipt_no=a.receipt_no','LEFT');
					$data['invoice_records'] = $invoice_records =  $this->master_model->getRecords("exam_invoice a", array('b.transaction_no'=>$SearchVal));
					//echo $this->db->last_query();
					
				}
				else if($searchBy == 'receipt_no')
				{
					$this->db->join('payment_transaction b','b.receipt_no=a.receipt_no','LEFT');
					$data['invoice_records'] = $invoice_records =  $this->master_model->getRecords("exam_invoice a", array('a.receipt_no'=>$SearchVal));
					//echo $this->db->last_query();
					
				}
				else if($searchBy == 'regnumber')
				{
					$this->db->join('payment_transaction b','b.receipt_no=a.receipt_no','LEFT');
					$data['invoice_records'] = $invoice_records =  $this->master_model->getRecords("exam_invoice a", array('a.member_no'=>$SearchVal));
					//echo $this->db->last_query(); 
					
				}
			}	
	}
	
	$this->load->view('refund_details/invoice_search',$data);
}

public function member()
{
	$SearchVal = '';
	$searchBy = '';
	$searchOn = '';
	$where = " `b.isactive` = '1' AND `b.isdeleted` = 0 ";
	$trnWhr = '';
	$recWhr = " `a.status` = '1'";
	$data = '';
	$records = array();
	 $data['reg_num_res'] = array();
	if(isset($_POST['btnSearch']))
	{ //print_r($_POST); 
			if(isset($_POST['searchBy']) && $_POST['searchBy']!='')
			{ 
				 $searchBy = trim($_POST['searchBy']);
			}
			
			if(isset($_POST['SearchVal']) && $_POST['SearchVal']!='')
			{	 
				 $SearchVal = trim($_POST['SearchVal']);
			}
			if($searchBy !='' && $SearchVal != '')
			{
					switch($searchBy)
				{
					case 'regnumber' 		: 	$where .= ' AND b.regnumber = "'.$SearchVal.'"';
												
												break;
												
					case 'mobile' 			: 	$where .= ' AND b.mobile = "'.$SearchVal.'"';
												
												break;
					case 'transaction_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.transaction_no = "'.$SearchVal.'"';
												break;
					case 'email' 	: 			$where .= ' AND b.email = "'.$SearchVal.'"';
												break;
					case 'receipt_no' 	: 	/*$where .= ' AND b.transaction_no = "'.$SearchVal.'"';*/
												$recWhr .= ' a.receipt_no = "'.$SearchVal.'"';
												break;							
				}
				if($searchBy == 'transaction_no')
				{
					$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.transaction_no'=>$SearchVal));
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='<html> Success </html>';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}else if($searchBy == 'receipt_no')
				{	
					//$this->db->DISTINCT('a. receipt_no');
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.receipt_no'=>$SearchVal));
					if(!empty($reg_num_res))
					{	//print_r($reg_num_res);
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								//print_r($reg_num_res['receipt_no']); echo '<br>';
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}else if($searchBy == 'regnumber') 
				{
					$select = '`a`.`receipt_no`, a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile, a.date, b.namesub, b.firstname, b.lastname';
					$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
					$this->db->group_by('a.receipt_no');
					$this->db->order_by('a.date', "desc");
					$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('a.member_regnumber'=>$SearchVal),$select);
					//echo $this->db->last_query();
					if(!empty($reg_num_res))
					{
							$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];

								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
				}else if($searchBy == 'email')
				{ 
					$reg_num_data = $this->master_model->getRecords("member_registration b", array('email'=>$SearchVal));
					//echo $this->db->last_query(); die;
					if(!empty($reg_num_data))
					{ 
						foreach($reg_num_data as $reg_res)
						{
							 $regnumber = $reg_res['regnumber'];
							 $email = $reg_res['email'];
							 $reg_type = $reg_res['registrationtype'];
							if($reg_type != 'NM' && $reg_type != 'DB'&& $reg_type != 'A' && $reg_type != 'F')
							{ 
								if($regnumber == '')
								{ 
									
									$this->db->where('b.email',$email);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								//echo '<br>1'.$this->db->last_query(); //die;
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
									echo '<br>2'.$this->db->last_query();
								}
								
								//echo $this->db->last_query();
							 
								
							}
							else{ 
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
								
								//echo $this->db->last_query(); 
							}
						  }	
						else
						{ 
							if($regnumber == '')
							{ 
								
									$this->db->where('b.email',$email);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								//echo $this->db->last_query(); //die;
								if($reg_num_res == '')
								{  
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
									
								}
								
								//echo $this->db->last_query();
							 
								
							}
							else{ 
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.email'=>$SearchVal),$select);
								
								//echo $this->db->last_query(); 
							}
							
						}
						}
					}	
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}else if($searchBy == 'mobile')
				{ 
					$reg_num_data = $this->master_model->getRecords("member_registration b", array('mobile'=>$SearchVal));
					//echo $this->db->last_query(); die;
					if(!empty($reg_num_data))
					{ 
						foreach($reg_num_data as $reg_res)
						{
							 $regnumber = $reg_res['regnumber'];
							 $mobile = $reg_res['mobile'];
							 $reg_type = $reg_res['registrationtype'];
							if($reg_type != 'NM')
							{
							if($regnumber == '')
							{ 
								
									$this->db->where('b.mobile',$mobile);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								//echo $this->db->last_query(); die;
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
									
								}
								
								//echo $this->db->last_query();
							 
								
							}
							else{ 
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
								
								//echo $this->db->last_query(); 
							}
						  }	
						else if($reg_type == 'NM')
						{
							if($regnumber == '')
							{ 
								
									$this->db->where('b.mobile',$mobile);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber,b.email, b.namesub, b.firstname, b.lastname, b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE member_regnumber = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								//echo $this->db->last_query(); die;
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
									
								}
								
								//echo $this->db->last_query();
							 
								
							}
							else{ 
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber ,  b.namesub, b.firstname, b.lastname, a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','LEFT');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
								
								//echo $this->db->last_query(); 
							}
							
						}
						}
					}	
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}
				/*else if($searchBy == 'mobile')
				{
						$reg_num_data =  $this->master_model->getRecords("member_registration b", array('b.mobile'=>$SearchVal));
					
					
					if(!empty($reg_num_data))
					{ 
						foreach($reg_num_data as $reg_res)
						{
							 $regnumber = $reg_res['regnumber'];
							if($regnumber == '')
							{
								
									$this->db->where('b.mobile',$SearchVal);
									$reg_num_res =  $this->master_model->getRecords("member_registration b" , '' , 'b.regnumber, b.namesub, b.firstname, b.lastname, b.email , b.mobile, (SELECT receipt_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS receipt_no,(SELECT transaction_no FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS transaction_no ,(SELECT status FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS status , (SELECT amount FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS amount,(SELECT date FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS date ,(SELECT description FROM payment_transaction `a` WHERE ref_id = b.regid Order BY date desc LIMIT 1 ) AS description', '');  
								
								if($reg_num_res == '')
								{ 
									$select = 'DISTINCT (`a`.`receipt_no`), b.regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile ,  b.namesub, b.firstname, b.lastname, a.date ';
									$this->db->join('member_registration b','b.regid=a.member_regnumber','INNER');
									$this->db->order_by('a.date', "desc");
									$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
									
								}
								
							}
							else{ 
							
								$select = 'DISTINCT (`a`.`receipt_no`), a.member_regnumber , a.transaction_no , a.amount , a.status , a.description ,b.email , b.mobile , b.namesub, b.firstname, b.lastname, a.date ';
								$this->db->join('member_registration b','b.regnumber = a.member_regnumber','INNER');
								$this->db->order_by('a.date', "desc");
								$reg_num_res =  $this->master_model->getRecords("payment_transaction a", array('b.mobile'=>$SearchVal),$select);
								
								
							}
						}
					}
					if(!empty($reg_num_res))
					{
						$i=0;
						foreach($reg_num_res as $reg_num_res)
						{
							$mem_pass = $this->getpassword($reg_num_res['member_regnumber']);  // get password
							
							$records[$i]['member_regnumber'] = $reg_num_res['member_regnumber'];
							$records[$i]['receipt_no']=$reg_num_res['receipt_no'];
							$records[$i]['transaction_no']=$reg_num_res['transaction_no'];
							$records[$i]['namesub']=$reg_num_res['namesub']. $reg_num_res['firstname']. $reg_num_res['lastname'];
							$records[$i]['password']=$mem_pass; 
							$records[$i]['description']=$reg_num_res['description'];
							$records[$i]['status']=$reg_num_res['status'];
							$records[$i]['email']=$reg_num_res['email'];
							$records[$i]['mobile']=$reg_num_res['mobile'];
							$records[$i]['date']=$reg_num_res['date'];
							
						    $status = $reg_num_res['status'];
							$trn = $reg_num_res['transaction_no'];
								// Call to SBI for status 
								
								$responsedata = sbiqueryapi($reg_num_res['receipt_no']);
								if($responsedata[2] == 'SUCCESS')
								{
									$records[$i]['refund_type']='Success';
								}
								else if($responsedata[2] == 'FAIL')
								{
									$records[$i]['refund_type']='FAIL';
								}
								else if($responsedata[2] == 'ABORT')
								{
									$records[$i]['refund_type']='ABORT';
								}
								else if($responsedata[2] == 'REFUND')
								{
									 $records[$i]['refund_type']='REFUND';
									
									$refund_info =  $this->master_model->getRecords("maker_checker", array('transaction_no'=>$responsedata[1]));
									//echo $this->db->last_query(); die;
									if(!empty($refund_info))
									{
										$records[$i]['refund_type']='Credit Note';
									}
									else{
										
										$records[$i]['refund_type']='Manual Refund';
									}
										
								}
								else if($responsedata[1] == 'NA')
								{
									$records[$i]['refund_type']='No Records Found at SBI END.';
								}
							
							$i++;
						}
						
					}
					 $data['reg_num_res'] = $records;
					
				}*/
			}
	}	
		$this->load->view('refund_details/member_details',$data);			
}
	
public function getpassword($member){ 
		
		include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
		$number = $member;
		$res = $this->master_model->getRecords('member_registration',array('regnumber'=>$number),'usrpassword');
		$key = $this->config->item('pass_key');
		$aes = new CryptAES();
		$aes->set_key(base64_decode($key));
		$aes->require_pkcs5();
		return $decpass = $aes->decrypt(trim($res[0]['usrpassword']));
		//iibf.teamgrowth.net/dwnletter/getpassword/511000086
	}

}