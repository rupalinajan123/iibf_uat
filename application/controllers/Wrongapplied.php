<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wrongapplied extends CI_Controller {
	
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
		//$this->load->model('chk_session');
	  //	$this->chk_session->chk_member_session();
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	 ##---------default userlogin (prafull)-----------##
	public function index()
	{
		echo 'Remove Exit to run next code!!!';
		exit;
		
		$sendsmsemail=$this->master_model->getRecords('member_wrongly_applied_examination',array('date'=>date('Y-m-d')));
		
		if(count($sendsmsemail) > 0)
		{
			$emailformat=$this->master_model->getRecords('emailer',array('emailer_name'=>'Wrongly_Applied_Examination'));
			$cnt=1;
			foreach($sendsmsemail as $row)
			{
				if($row['wrong_examname']!='' && $row['correct_examname']!='')
				{
					$member_details=$this->master_model->getRecords('member_registration',array('regnumber'=>$row['member_number']),'email,mobile');
					$newstring1= str_replace("#WRONG#", "<strong>".strtoupper($row['wrong_examname'])."</strong>",$emailformat[0]['emailer_text']);
					$final_str = str_replace("#CORRECT#", "<strong>".strtoupper($row['correct_examname'])."</strong>",$newstring1);
					
					
					$info_arr=array('to'=>$member_details[0]['email'],
											'from'=>$emailformat[0]['from'],
											'subject'=>$emailformat[0]['subject'],
											'message'=>$final_str);
					
						
					//send email
					if($this->Emailsending->mailsend($info_arr))
					{
						//send sms
						//$this->master_model->send_sms($member_details[0]['mobile'],$emailformat[0]['sms_text']);
						$this->master_model->send_sms_trustsignal(intval($member_details[0]['mobile']),$emailformat[0]['sms_text'],'r29rOSwMR');
						$insert_data = array(
														'member_number' 	 => $row['member_number'],
														'email_text'           =>$final_str,
														'sms_text'        		 => $emailformat[0]['sms_text'],
														'date'            		 => date('Y-m-d H:i:s'),
														);
							//insert into log
							$this->master_model->insertRecord('wrongly_applied_examination_log', $insert_data);				
							echo 'Mail and SMS Send!!';
					}
				}
			}
		}
	}
	
	/*public function index()
	{
		$cnt=1;
		echo $final_str = '<p>Dear Candidate,</p>
<p>Your exam application for the <strong>CERTIFICATE EXAMINATION IN MICROFINANCE</strong> examination was not successfull. You are requested to apply again before  22nd March, 2017. </p>
<p><span style="color:#F00">Plz note no extension will be given for the same under any circumstances.</span> </p>';
$sms_text='Dear Candidate,
Plz check your email for the un-successful applied examination and revert at the earliest. If any issues, send email to onlineservices@iibf.org.in';
		$member_details=$this->master_model->getRecords('member_registration',array('regnumber'=>'510206377'),'email,mobile');
		$info_arr=array('to'=>$member_details[0]['email'],
								'from'=>'noreply@iibf.org.in',
								'subject'=>'Regarding Exam Application',
								'message'=>$final_str);
								
		//send email
		if($this->Emailsending->mailsend($info_arr))
		{
			//send sms
			$this->master_model->send_sms($member_details[0]['mobile'],$sms_text);	
			//$this->master_model->send_sms_trustsignal(intval($member_details[0]['mobile']),$sms_text,'template_id');
			$insert_data = array(
											'member_number' 	 => '510206377',
											'email_text'           =>$final_str,
											'sms_text'        		 => $sms_text,
											'date'            		 => date('Y-m-d H:i:s'),
											);
				//insert into log
				$this->master_model->insertRecord('wrongly_applied_examination_log', $insert_data);				
				echo 'Mail and SMS Send!!';
		}
	}*/
}
