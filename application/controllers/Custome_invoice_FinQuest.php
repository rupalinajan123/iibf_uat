<?php defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Custome_invoice_FinQuest extends CI_Controller {
    public function __construct() {
     exit; 
	    parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
		$this->load->helper('upload_helper');
        $this->load->helper('renewal_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        //accedd denied due to GST
        //$this->master_model->warning();
        
    }

 
	public function index()
	{
 $MerchantOrderNo=811977129;
 $session_id=6;
				$mem_info=$this->master_model->getRecords('fin_quest',array('mem_no'=>'500165877'));	  
					   
					   $start_date = $mem_info[0]['subscription_from_date'];
						$end_date = $mem_info[0]['subscription_to_date'];
						$subscription_range = $start_date." to ".$end_date;
						$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'finquest'));
						$newstring1 = str_replace("#NO#", "". 100001 ."",  $emailerstr[0]['emailer_text']);
						$final_str= str_replace("#DATE#", "". $subscription_range ."", $newstring1);
								$info_arr=array(//'to'=>$this->session->userdata['enduserinfo']['email'],
											'to'=>'abhid1487@gmail.com 	',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
											);
									//to client	
											$client_arr=array(//'to'=>$this->session->userdata['enduserinfo']['email'],
											'to'=>'kavan@iibf.org.in',
											'from'=>$emailerstr[0]['from'],
											'subject'=>$emailerstr[0]['subject'],
											'message'=>$final_str
											);	
											
							// genarate invoice
							$getinvoice_number=$this->master_model->getRecords('exam_invoice',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>2108511));
							if(count($getinvoice_number) > 0)
							{
							
									if($getinvoice_number[0]['state_of_center']=='JAM')
									{
										
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('FinQuest_invoice_no_prefix_jammu').$invoiceNumber;
										}
								}
								else
								{
										$invoiceNumber =00001;
										if($invoiceNumber)
										{
											$invoiceNumber=$this->config->item('FinQuest_invoice_no_prefix').$invoiceNumber;
										}
								}
								
							
								if($getinvoice_number[0]['state_of_center']=='JAM')
								{
									$attachpath=genarate_finquest_invoice_jk($getinvoice_number[0]['invoice_id'],$session_id);
								}else
								{
									$attachpath=genarate_finquest_invoice($getinvoice_number[0]['invoice_id'],$session_id);
								}
			}
				
							if($attachpath!='')
							{
						    	if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
								{
									$this->Emailsending->mailsend_attch($client_arr,$attachpath);
								echo 'email send';
								exit;
									redirect(base_url().'FinQuest/acknowledge/');
								}
								
								else{
									echo 'email  not send';
										redirect(base_url().'FinQuest/acknowledge/');
								}
							}else{
								redirect(base_url().'FinQuest/acknowledge/');
							}
							
							
						
	}
	
				
			
			

	
 }
	
