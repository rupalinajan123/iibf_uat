<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class DuplicateInvoiceEmail extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('custom_invoice_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->model('Emailsending');
	
	}
	
public function send_email()
{
	$member=array('510019105','510206571','500209629');
	foreach($member as $row)
	{
		$member_info = $this->master_model->getRecords('member_registration',array('regnumber'=>$row,'isactive'=>'1'));
		$invoice_info = $this->master_model->getRecords('exam_invoice',array('member_no'=>$row));
		
		$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
		 if(count($emailerstr) > 0 )
		{
			//Query to get user details
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>$member_info[0]['regnumber']),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
			$username=$user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
			$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
			$newstring2 = str_replace("#MEM_NO#", "".$member_info[0]['regnumber']."", $newstring1 );
			$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
			$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
			
			
			if(count($invoice_info) > 0)
			{ 
					$attachpath= custome_genarate_duplicateicard_invoice($invoice_info[0]['receipt_no']);
					if($attachpath!='')
					{
						if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
						{
							echo 'email send'; 
							echo '';
						}else
						{
							echo  'email not send';
						}
					}
	
			}
		}
	}
     
	

}
}