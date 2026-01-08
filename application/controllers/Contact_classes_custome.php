<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contact_classes_custome extends CI_Controller {

			
	public function __construct(){
		parent::__construct();
		exit;
	/*	if($this->session->userdata('kyc_id') == ""){
			redirect('admin/kyc/Login');
		}		*/
		
	      $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
		$this->load->helper('upload_helper');
		$this->load->helper('custom_contact_classes_invoice_helper');
        $this->load->helper('renewal_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');

	}
public function index()
{exit;
$regnumber ='510141289';
$MerchantOrderNo='900400680';	
	
	if (isset($regnumber))
	 {
		 	$payment_info=$this->master_model->getRecords('payment_transaction',array('receipt_no'=>$MerchantOrderNo,'status'=>1));
				$member = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE contact_classes_id IN (
																SELECT MAX(contact_classes_id)
																FROM contact_classes_registration
																GROUP BY member_no
															) and pay_status = 1 AND member_no=".$regnumber);
				$memtype= $member->result_array();
				$user_info=$this->master_model->getRecords('contact_classes_Subject_registration',array('member_no'=>$regnumber));		
						// email to user
						/*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
							$final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
							//$final_str= str_replace("#password#", "".$decpass."",  $newstring);
			*/			
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'contactclasses'));
						
						
				                 	$selfstr1 = str_replace("#regnumber#", "".$regnumber."", $emailerstr[0]['emailer_text']);
										$selfstr2 = str_replace("#program_name#", "".$user_info[0]['program_name']."",  $selfstr1);
										$selfstr3 = str_replace("#center_name#", "".$user_info[0]['center_name']."",  $selfstr2);
										$selfstr4 = str_replace("#venue_name#", "".$user_info[0]['venue_name']."",  $selfstr3);
										//$selfstr5 = str_replace("#start_date#", "".$reg_info[0]['start_date']."",  $selfstr4);
										//$selfstr6 = str_replace("#end_date#", "".$reg_info[0]['end_date']."",  $selfstr5);
										$selfstr7 = str_replace("#name#", "". $memtype[0]['namesub']  ." ". $memtype[0]['firstname'] ." ". $memtype[0]['middlename']  ." ". $memtype[0]['lastname'],  $selfstr4);
										$selfstr8 = str_replace("#address1#", "".$memtype[0]['address1']."",  $selfstr7);
										$selfstr9 = str_replace("#address2#", "".$memtype[0]['address2']."",  $selfstr8);
										$selfstr10 = str_replace("#address3#", "".$memtype[0]['address3']."",  $selfstr9);
										$selfstr11 = str_replace("#address4#", "".$memtype[0]['address4']."",  $selfstr10);
										
										$selfstr12 = str_replace("#district#", "".$memtype[0]['district']."",  $selfstr11);
										$selfstr13 = str_replace("#city#", "".$memtype[0]['city']."",  $selfstr12);
										$selfstr14 = str_replace("#state#", "".$memtype[0]['state']."",  $selfstr13);
										$selfstr15 = str_replace("#pincode#", "".$memtype[0]['pincode']."",  $selfstr14);
										$selfstr19 = str_replace("#email#", "".$memtype[0]['email']."",  $selfstr15);
										$selfstr20 = str_replace("#mobile#", "".$memtype[0]['mobile']."",  $selfstr19);
									
										$selfstr29 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $selfstr20);
										$selfstr30 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $selfstr29);
										$selfstr31 = str_replace("#STATUS#", "Transaction Successful",  $selfstr30);
										$final_selfstr = str_replace("#TRANSACTION_DATE#", "".date('Y-m-d H:i:sA',strtotime($payment_info[0]['date']))."",  $selfstr31);
                 
				 
					//	$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
					//	$final_str= str_replace("#DATE#",  $emailerstr[0]['emailer_text']);
								$info_arr=array(//'to'=>$memtype[0]['email'],
								'to'=>'kyciibf@gmail.com',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_selfstr
											);
										
									$client_arr=array(
								//	'to'=>'kyciibf@gmail.com',
			//	 'to'=>'kyciibf@gmail.com,shailly@iibf.org.in,jagdishr@iibf.org.in',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_selfstr
											);	

							
				
							
					
								$attachpath=custom_genarate_custom_contact_classes_invoice(242914,177);
							if($attachpath!=''){
						
						//to user
								if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
								{
								//	//to client
								$this->Emailsending->mailsend_attch($client_arr,$attachpath);
								echo 'emailsend to test 177  ';
								
								}else
								{
									echo 'email not send';
								}
							}
	 }
}
}




