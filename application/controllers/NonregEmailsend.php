<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class NonregEmailsend extends CI_Controller {

	public function __construct()
	{
		
		parent::__construct();
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('master_model');		
		$this->load->library('email');
		$this->load->helper('date');
		$this->load->library('email');
		$this->load->model('Emailsending');
		$this->load->model('log_model');
		$this->load->model('chk_session');
		$this->chk_session->Check_mult_session();
		//$this->load->model('chk_session');
	    //$this->chk_session->chk_member_session();
	}

	
	public function send_elearning_mail()
    { 
		
		$this->db->where('email_send','0');
		$this->db->limit(100);
		$chk_eligible = $this->master_model->getRecords('exam_invoice_without_gst');
		
		if(count($chk_eligible) > 0)
		{
			
			$html='
			<P><strong>FINAL REMINDER !!!</strong></p>
			<p>Dear Candidate,</p>
<p>Due to technical error, your payment for Remote Proctored exam scheduled on 5th July (AML/KYC, MSME, Prevention of Cyber Crimes and Fraud Management) has not been deducted completely. <br>
  You are requested to make balance payment immediately through following link given below or through "RPE GST Payment" link available in your Edit profile option by maximum7 PM today.
<a href="https://iibf.esdsconnect.com/RPEGstRecovery">click here</a>
</p>
<p>
Kindly complete your balance payment or you wont be allowed to appear for RPE examination tomorrow</P>
<p>Regards,</p>
Indian Institute of  Banking &amp; Finance';	
            
		//$files=array('https://iibf.esdsconnect.com/uploads/Rules_and_regulation_of_RP_exam.pdf','https://iibf.esdsconnect.com/uploads/website_schd_remote_proct_exm.pdf');	
		
		foreach($chk_eligible as $row)
		{
		$this->db->select('email');	
		$this->db->where('regnumber',$row['member_no']);
		$this->db->where('isactive','1');
		$this->db->order_by('regid','desc');	
		$this->db->limit(1);
		$chk_reg = $this->master_model->getRecords('member_registration');
		//echo $this->db->last_query();exit;
			if(count($chk_reg))
		{
		
			$info_arr=array('to'=>$chk_reg[0]['email'],
										'from'=>'noreply@iibf.org.in',
										'subject'=>'Remote Procedure Examination',
										'message'=>$html
									);
									
				$check_mail=	$this->Emailsending->mailsend($info_arr);
				if($check_mail)
				{
					$update_data=array('email_send'=>'1');
					$this->master_model->updateRecord('exam_invoice_without_gst',$update_data,array('member_no'=>$row['member_no']));
					//echo $this->db->last_query();exit;
				}
			
		}
			else{echo 'number not found in DB 123';}
	}	
}
		
    }
   

	
	public function send_sms()
    { 
		
		
		$this->db->where('sms_send','0');
		$this->db->limit(100);
		$chk_eligible = $this->master_model->getRecords('exam_invoice_without_gst');
		
		if(count($chk_eligible) > 0)
		{
			
			
	
							$text='FINAL REMINDER !!! Dear Candidate,
Due to technical error, your payment for Remote Proctored exam scheduled on 5th July (AML/KYC, MSME, Prevention of Cyber Crimes and Fraud Management) has not been deducted completely.
You are requested to make balance payment immediately through following link given below or through "RPE GST Payment" link available in your Edit profile option by maximum7 PM today:
https://iibf.esdsconnect.com/RPEGstRecovery
Kindly complete your balance payment or you wont be allowed to appear for RPE examination tomorrow.
Regards,
Indian Institute of Banking & Finance';

						
		
		foreach($chk_eligible as $row)
		{
			$mobile=$row['mobile'];
			//$mobile='9096241879';
		
				$url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$mobile."&text=".urlencode($text)."&senderid=OTPSMS&route_id=2&Unicode=0";
							
							$string = preg_replace('/\s+/','', $url);

							$x = curl_init($string);

							curl_setopt($x, CURLOPT_HEADER, 0);	

							curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);

							curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);			

							$reply = curl_exec($x);

							curl_close($x);
	
							$sms_array=array('respond'=>htmlspecialchars_decode($reply),'mobile'=>$mobile,'status'=>'success');
			
							if($sms_array['status']=='success')
							{			
					$update_data=array('sms_send'=>'1');
					$this->master_model->updateRecord('exam_invoice_without_gst',$update_data,array('member_no'=>$row['member_no']));
					//echo $this->db->last_query();exit;
				}
			
		
			
	}	
}
		
    }
	
	
	public function check_sms()
	{
			$text='Regarding your Exam Application please refer following links:
1.Rules & Regulation URL http://www.iibf.org.in/documents/pdf/Rules_and_regulation_of_RP_exam_20200525.pdf
2.Exam Application registration URL https://iibf.esdsconnect.com/RELApplyexam/exapplylogin

Joint Director (Examinations)
Indian Institute of Banking & Finance (Mumbai)
';
			$mobile='';
							echo $url ="http://www.hindit.co.in/API/pushsms.aspx?loginID=T1IIBF&password=supp0rt123&mobile=".$mobile."&text=".urlencode($text)."&senderid=IIBFNM&route_id=2&Unicode=0";
							exit;
							
						 

		
	}
	




public function send_candidate_mail()
    { 
		
	
if (isset($_POST['btnSubmit']))
        
            {
        		

        		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');	

        		 if ($this->form_validation->run() == TRUE)
        		 {

        		 $email=$this->input->post('email');	
            	$html='<p>Dear Candidate,</p>
<p>Due to technical error, your payment for Remote Proctored exam (AML/KYC, MSME, Prevention of Cyber Crimes and Fraud Management) has not been deducted completely.  <br>
  You are requested to make balance payment through following link given below or through “RPE GST Payment” link available in your Edit profile option :<br>
  <a href="https://iibf.esdsconnect.com/RPEGstRecovery">click here</a></p>
<p>Regards,</p>
Indian Institute of  Banking &amp; Finance';	

$info_arr=array('to'=>$email,
										'from'=>'noreply@iibf.org.in',
										'subject'=>'Remote Procedure Examination',
										'message'=>$html
									);
									
				$check_mail=	$this->Emailsending->mailsend($info_arr);
				if($check_mail)
				{

					 $this->session->set_flashdata('success', 'Mail send successfuly!');
                     redirect(base_url() . 'NonregEmailsend/send_candidate_mail');
				}
            
            
				}
				else
				{
					echo 'Enter valida email address';
				}
            }

$this->load->view('emailform');		

		
    }
   


}
