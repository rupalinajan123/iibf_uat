<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Refundemail extends CI_Controller {
	public function __construct()

	{
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
	}
	
	public function index()
	{
		 $refunduesr=$this->master_model->getRecords('refund_mail_users');
		 
		 if(count($refunduesr) > 0)
		 {
			 $cnt=1;
			 $emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'refund'));
			 if(count($emailerstr) > 0)
			{
				foreach($refunduesr as $users)
				{
					//echo $users['email'].'<br>';
					
					$final_str = str_replace("#application_num#", "".$users['register_num']."",  $emailerstr[0]['emailer_text']);
					
					$info_arr=array('to'=>$users['email'],
											  'from'=>$emailerstr[0]['from'],
											  'subject'=>$emailerstr[0]['subject'],
											  'message'=>$final_str
											);
						if($this->Emailsending->mailsend($info_arr))
						{
							
								$info_arr=array('to'=>$users['email'],
											  'from'=>$emailerstr[0]['from'],
											  'subject'=>$emailerstr[0]['subject'],
											  'message'=>$final_str,
											  'createdon'=>date('Y-m-d H:i:s')
											);
							logactivity($log_title ="Refund email", $log_message = serialize($info_arr));
							echo $cnt.'='.$users['email'].' '.'Application Number='.$users['register_num'].'<br>';
							$cnt++;
						}
			
				}
			}
		 }
	
	}
}

