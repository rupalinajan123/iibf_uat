<?php
/*
 	* Controller Name	:	Cpdcron
 	* Created By		:	Chaitali
 	* Created Date		:	18-06-2020
*/
//https://iibf.esdsconnect.com/admin/Cpdcron/cpd_data

defined('BASEPATH') OR exit('No direct script access allowed');

class Blended_mail_send extends CI_Controller {
			
public function __construct()
{
		parent::__construct();
		
		$this->load->model('UserModel');
		$this->load->model('Master_model');
		$this->load->library('email');
		$this->load->model('Emailsending');
		 $this->load->helper('custom_admitcard_helper');
		// $this->load->helper('custom_cpd_invoice_helper');
		
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
}

public function blended_mail_cron()
{
	$active_training_count = $this->Master_model->getRecordCount('blended_program_activation_master  ',array('program_activation_delete'=>'0'));
	if($active_training_count > 0)
	{
		$active_training = $this->Master_model->getRecords('blended_program_activation_master  ',array('program_activation_delete'=>'0'));
		if(!empty($active_training))
		{
			foreach($active_training as $res)
			{
			    $program_code  = $res['program_code'];
				if($program_code == 'CCP')
				{ exit;
					 $last_date  = $res['program_reg_to_date'];
			         $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
				     $current_date = date('Y-m-d');
					if($one_day_before == $current_date)
					{
						$this->db->where_in('program_code',$program_code);
						$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
						
						if(!empty($eligible_mem))
						{
							foreach($eligible_mem as $res)
							{
								$member_number = $res['member_number'];
								$member_name = $res['member_name'];
								$email = $res['email'];
								$program_code = $res['program_code'];
								$blended_id = $res['blended_id'];
								
								$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
								$final_str.= '<br/><br/>';
								$final_str.= 'Dear Candidate,';
								$final_str.= '<br/><br/>';
								$final_str.= 'Greetings from IIBF!!';
								$final_str.= '<br/><br/>';
								$final_str.= 'We have announced Virtual Mode Training for CERTIFIED CREDIT PROFESSIONAL Course.';
								$final_str.= '<br/><br/>';
								$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
								$final_str.= '<br/>';
								$final_str.= 'Training Fees :-1st Attempt - Free <br/><br/> 2nd Attempt – Rs 1000 + 18% GST';
								$final_str.= '<br/>';
								$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
								$final_str.= '<br/><br/>';
								$final_str.= 'Thanks and Regards';
								$final_str.= '<br/>';
								$final_str.= 'IIBF TEAM'; 
								
								$info_arr=array('to'=>$email,
									//'to'=>'chaitali.jadhav@esds.co.in',
							'from'=>'noreply@iibf.org.in',
							'subject'=>'IIBF: Blended CCP Training - '.$member_number,
							'message'=>$final_str); 
							
									if($this->Emailsending->mailsend_attch($info_arr,''))
									{
										$update_data = array('mail_flag' => '1');
										$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
										
									}
						
								}
							}
						}
					}else if($program_code == 'RFS')
					{
						 $last_date  = $res['program_reg_to_date'];
						 $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
						 $current_date = date('Y-m-d');
						if($one_day_before == $current_date)
						{
							$this->db->where_in('program_code',$program_code);
							$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
							
							if(!empty($eligible_mem))
							{
								foreach($eligible_mem as $res)
								{
									$member_number = $res['member_number'];
									$member_name = $res['member_name'];
									$email = $res['email'];
									$program_code = $res['program_code'];
									$blended_id = $res['blended_id'];
									
									$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
									$final_str.= '<br/><br/>';
									$final_str.= 'Dear Candidate,';
									$final_str.= '<br/><br/>';
									$final_str.= 'Greetings from IIBF!!';
									$final_str.= '<br/><br/>';
									$final_str.= 'We have announced Virtual Mode Training for RISK IN FINANCIAL SERVICES Course.';
									$final_str.= '<br/><br/>';
									$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
									$final_str.= '<br/>';
									$final_str.= 'Training Fees :-1st Attempt - Free <br/><br/> 2nd Attempt – Rs 1000 + 18% GST';
									$final_str.= '<br/>';
									$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
									$final_str.= '<br/><br/>';
									$final_str.= 'Thanks and Regards';
									$final_str.= '<br/>';
									$final_str.= 'IIBF TEAM'; 
									
									$info_arr=array('to'=>$email,
										//'to'=>'chaitali.jadhav@esds.co.in',
								'from'=>'noreply@iibf.org.in',
								'subject'=>'IIBF: Blended RFS Training - '.$member_number,
								'message'=>$final_str); 
								
										if($this->Emailsending->mailsend_attch($info_arr,''))
										{
											$update_data = array('mail_flag' => '1');
											$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
											
										}
							
									}
								}
							}
						}else if($program_code == 'CTP')
						{
							 $last_date  = $res['program_reg_to_date'];
							 $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
							 $current_date = date('Y-m-d');
							if($one_day_before == $current_date)
							{
								$this->db->where_in('program_code',$program_code);
								$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
								
								if(!empty($eligible_mem))
								{
									foreach($eligible_mem as $res)
									{
										$member_number = $res['member_number'];
										$member_name = $res['member_name'];
										$email = $res['email'];
										$program_code = $res['program_code'];
										$blended_id = $res['blended_id'];
										
										$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
										$final_str.= '<br/><br/>';
										$final_str.= 'Dear Candidate,';
										$final_str.= '<br/><br/>';
										$final_str.= 'Greetings from IIBF!!';
										$final_str.= '<br/><br/>';
										$final_str.= 'We have announced Virtual Mode Training for CERTIFIED TREASURY PROFESSIONAL Course.';
										$final_str.= '<br/><br/>';
										$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
										$final_str.= '<br/>';
										$final_str.= 'Training Fees :-1st Attempt - Free <br/><br/> 2nd Attempt – Rs 1000 + 18% GST';
										$final_str.= '<br/>';
										$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
										$final_str.= '<br/><br/>';
										$final_str.= 'Thanks and Regards';
										$final_str.= '<br/>';
										$final_str.= 'IIBF TEAM'; 
										
										$info_arr=array('to'=>$email,
											//'to'=>'chaitali.jadhav@esds.co.in',
									'from'=>'noreply@iibf.org.in',
									'subject'=>'IIBF: Blended CTP Training - '.$member_number,
									'message'=>$final_str); 
									
											if($this->Emailsending->mailsend_attch($info_arr,''))
											{
												$update_data = array('mail_flag' => '1');
												$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
												
											}
								
										}
									}
								}
							}else if($program_code == 'AAP')
							{
								 $last_date  = $res['program_reg_to_date'];
								 $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
								 $current_date = date('Y-m-d');
								if($one_day_before == $current_date)
								{
									$this->db->where_in('program_code',$program_code);
									$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
									
									if(!empty($eligible_mem))
									{
										foreach($eligible_mem as $res)
										{
											$member_number = $res['member_number'];
											$member_name = $res['member_name'];
											$email = $res['email'];
											$program_code = $res['program_code'];
											$blended_id = $res['blended_id'];
											
											$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
											$final_str.= '<br/><br/>';
											$final_str.= 'Dear Candidate,';
											$final_str.= '<br/><br/>';
											$final_str.= 'Greetings from IIBF!!';
											$final_str.= '<br/><br/>';
											$final_str.= 'We have announced Virtual Mode Training for CERTIFIED ACCOUNTING AND AUDIT PROFESSIONAL Course.';
											$final_str.= '<br/><br/>';
											$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
											$final_str.= '<br/>';
											$final_str.= 'Training Fees :-1st Attempt - Free <br/><br/> 2nd Attempt – Rs 1000 + 18% GST';
											$final_str.= '<br/>';
											$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
											$final_str.= '<br/><br/>';
											$final_str.= 'Thanks and Regards';
											$final_str.= '<br/>';
											$final_str.= 'IIBF TEAM'; 
											
											$info_arr=array('to'=>$email,
												//'to'=>'chaitali.jadhav@esds.co.in',
										'from'=>'noreply@iibf.org.in',
										'subject'=>'IIBF: Blended AAP Training - '.$member_number,
										'message'=>$final_str); 
										
												if($this->Emailsending->mailsend_attch($info_arr,''))
												{
													$update_data = array('mail_flag' => '1');
													$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
													
												}
									
											}
										}
									}
								}else if($program_code == 'CBC')
								{
									 $last_date  = $res['program_reg_to_date'];
									 $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
									 $current_date = date('Y-m-d');
									if($one_day_before == $current_date)
									{
										$this->db->where_in('program_code',$program_code);
										$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
										
										if(!empty($eligible_mem))
										{
											foreach($eligible_mem as $res)
											{
												$member_number = $res['member_number'];
												$member_name = $res['member_name'];
												$email = $res['email'];
												$program_code = $res['program_code'];
												$blended_id = $res['blended_id'];
												
												$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
												$final_str.= '<br/><br/>';
												$final_str.= 'Dear Candidate,';
												$final_str.= '<br/><br/>';
												$final_str.= 'Greetings from IIBF!!';
												$final_str.= '<br/><br/>';
												$final_str.= 'We have announced Virtual Mode Training for CERTIFIED BANKING COMPLIANCE PROFESSIONAL Course.';
												$final_str.= '<br/><br/>';
												$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
												$final_str.= '<br/>';
												$final_str.= 'Training Fees :-1st Attempt - Rs 4500 + 18% GST  <br/><br/> 2nd Attempt – Rs 2000 + 18% GST';
												$final_str.= '<br/>';
												$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
												$final_str.= '<br/><br/>';
												$final_str.= 'Thanks and Regards';
												$final_str.= '<br/>';
												$final_str.= 'IIBF TEAM'; 
												
												$info_arr=array('to'=>$email,
													//'to'=>'chaitali.jadhav@esds.co.in',
											'from'=>'noreply@iibf.org.in',
											'subject'=>'IIBF: Blended CBC Training - '.$member_number,
											'message'=>$final_str); 
											
													if($this->Emailsending->mailsend_attch($info_arr,''))
													{
														$update_data = array('mail_flag' => '1');
														$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
														
													}
										
												}
											}
										}
									}else if($program_code == 'CBT')
								{
									 $last_date  = $res['program_reg_to_date'];
									 $one_day_before = date('Y-m-d',strtotime("- 1 day",strtotime($last_date)));
									 $current_date = date('Y-m-d');
									if($one_day_before == $current_date)
									{
										$this->db->where_in('program_code',$program_code);
										$eligible_mem = $this->Master_model->getRecords('blended_eligible_master',array('mail_flag'=>'0'),'member_number,member_name,email,program_code,blended_id');
										
										if(!empty($eligible_mem))
										{
											foreach($eligible_mem as $res)
											{
												$member_number = $res['member_number'];
												$member_name = $res['member_name'];
												$email = $res['email'];
												$program_code = $res['program_code'];
												$blended_id = $res['blended_id'];
												
												$final_str = 'Hello&nbsp;'.$member_name.', <br/><br/>';
												$final_str.= '<br/><br/>';
												$final_str.= 'Dear Candidate,';
												$final_str.= '<br/><br/>';
												$final_str.= 'Greetings from IIBF!!';
												$final_str.= '<br/><br/>';
												$final_str.= 'We have announced Virtual Mode Training for CERTIFIED BANK TRAINER Course.';
												$final_str.= '<br/><br/>';
												$final_str.= 'Training Time :-10 AM to 5 PM (Tentative)';
												$final_str.= '<br/>';
												$final_str.= 'Training Fees :-1st Attempt - Rs 4500 + 18% GST  <br/><br/> 2nd Attempt – Rs 2000 + 18% GST';
												$final_str.= '<br/>';
												$final_str.= 'Last Date to Apply :- '. $last_date.',<br/><br/>';
												$final_str.= '<br/><br/>';
												$final_str.= 'Thanks and Regards';
												$final_str.= '<br/>';
												$final_str.= 'IIBF TEAM'; 
												
												$info_arr=array('to'=>$email,
													//'to'=>'chaitali.jadhav@esds.co.in',
											'from'=>'noreply@iibf.org.in',
											'subject'=>'IIBF: Blended CBT Training - '.$member_number,
											'message'=>$final_str); 
											
													if($this->Emailsending->mailsend_attch($info_arr,''))
													{
														$update_data = array('mail_flag' => '1');
														$this->master_model->updateRecord('blended_eligible_master',$update_data,array('blended_id'=>$blended_id));
														
													}
										
												}
											}
										}
									}
					
				}
			}
		}
		
	}
	

}