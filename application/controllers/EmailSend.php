<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class EmailSend extends CI_Controller {
	public $UserID;
			
	public function __construct(){
		parent::__construct();
		if($this->session->id==""){
			redirect('admin/Login'); 
		}		
		
		$this->load->model('UserModel');
		$this->load->model('Master_model'); 
		$this->UserID=$this->session->id;
		
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('upload_helper');
		$this->load->helper('general_helper');
		$this->load->library('email');
		$this->load->model('Emailsending');
	}
	
	public function index()
	{
	
			//email to user
				$cnt=1;
			
				//echo $users['email'].'<br>';
				//$email_res = $this->master_model->getRecords('mem_reg_missing_img_16_3_17',array("regnumber"=>$reg_num),'email');
				$this->db->limit('1000');
				$email_res = $this->master_model->getRecords('jaiib_send_mail',array("mail_sent_flg"=>'0'),'','email,id');
				
				//echo $emailerstr[0]['emailer_text'];
				if(count($email_res)>0)
				{
					foreach($email_res as $row)
					{
						/*$info_arr = array('to'=>$email_res[0]['email'],
										  'from'=>$emailerstr[0]['from'],
										  'subject'=>$emailerstr[0]['subject'],
										  'message'=>$emailerstr[0]['emailer_text']
										);*/
										
										$info_arr = array('to'=>$row['email_id'],
										  'from'=>'noreply@iibf.org.in',
										  'subject'=>'JAIIB Admit Letter and Invoice',
										  'message'=>'<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Untitled Document</title>
	</head>
	
	<body>
	<p>Hello</p>
	<p>Candidates who have registered for <strong>JAIIB</strong> examination on or before <strong>4th of September 2019</strong> and have not received the admit card and invoice, the same will be emailed to you within 15 days.</p>
	<p>Regards,<br />
	IIBF </p>
	</body>
	</html>');
	
						/*echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
						$cnt++;*/
						
						if($this->Emailsending->mailsend($info_arr))
						{
						
							$update_data = array('mail_sent_flg' => 1);
							$this->master_model->updateRecord('jaiib_send_mail',$update_data,array('id'=>$row['id']));	
							//echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
							echo $cnt++;
							echo '<br>';
							//exit;
							}
						}			
				}
			
			//echo "<br>".$emailerstr[0]['emailer_text'];
			//echo "<br> mail sent";
		
	}
	
	
	public function profile()
	{
		//email to user
		//$cand_arr = array(510301029,510301030,510301031,510301032,510301034,510301035,510301037,510301039,510301040,510301041,510301042,510301043,510301044,510301046,510301045,510301047,510301048,510301049,510301050,510301051,510301052,510301053,510301054,510301055,510301057,510301059,510301060,510301061,510301062,510301063,510301064,510301065,510301066,510301067,510301068,510301069,510301070,510301071,510301072,510301073,510301074,510301075,510301076,510301078,510301077,510301080,510301079,510301081,510301083,510301082,510301084,510301085,510301086,510301087,510301088,510301089,510301090,510301092,510301093,510301095,510301097,510301098,510301099,510301100,510301101,510301103,510301105,510301106,510301107,510301108,510301109,510301112,510301110,510301111,510301113,510301115,510301114,510301117,510301116,510301118,510301120,510301119,510301122,510301121,510301124,510301125,510301126,510301127,510301128,510301129,510301131,510301130,510301132,510301133,510301134,510301135,510301136,510301137,510301138,510301139,510301141,510301143,510301144,510301145,510301146,510301147,510301153,510301148,510301149,510301150,510301151,510301152,510301154,510301156,510301160,510301162,510301163,510301164,510301165,510301166,510301167,510301168,510301169,510301170,510301171,510301172,510301173,510301175,510301174,510301176,510301177,510301179,510301178,510301180,510301181,510301182,510301183,510301184,510301185,510301186,510301187,510301189,510301191,510301192,510301193,510301194,510301195,510301196,510301198,510301200);
		
		//$cand_arr = array(510301153,510301148,510301149,510301150,510301151,510301152,510301154,510301156,510301160,510301162,510301163,510301164,510301165,510301166,510301167,510301168,510301169,510301170,510301171,510301172,510301173,510301175,510301174,510301176,510301177,510301179,510301178,510301180,510301181,510301182,510301183,510301184,510301185,510301186,510301187,510301189,510301191,510301192,510301193,510301194,510301195,510301196,510301198,510301200);
		
		$cand_arr = array(510299450,510299641,510299703,510299708,510299898,510300045,510300297,510300394,510300776,510300856,510300867,510300903,510301032,510301035,510301041,510301042,510301044,510301046,510301047,510301049,510301054,510301055,510301063,510301068,510301077,510301083,510301089,510301099,510301103,510301105,510301106,510301126,510301131,510301150,510301151,510301153,510301156,510301167,510301168,510301176,510301177,510301179,510301194,510301204,510301208,510301210,510301213,510301226,510301227,510301242,510301246,510301254,510301255,510301274,510301275,510301317,510301321,510301325,801148423);
		
		if(count($cand_arr) > 0)
		{
			$cnt=1;
			$emailerstr=$this->master_model->getRecords('refund_email',array('emailer_name'=>'profile'));
			
			foreach($cand_arr as $reg_num)
			{
				//echo $users['email'].'<br>';
				$email_res = $this->master_model->getRecords('member_registration_first_name_null_with_mobile_no',array("regnumber"=>$reg_num),'email');
				if(count($email_res)>0)
				{
					$info_arr = array('to'=>$email_res[0]['email'],
									  'from'=>$emailerstr[0]['from'],
									  'subject'=>$emailerstr[0]['subject'],
									  'message'=>$emailerstr[0]['emailer_text']
									);
									
					/*echo $cnt.'='.$email_res[0]['email'].' '.'Application Number='.$reg_num.'<br>';
					$cnt++;*/
					
					if($this->Emailsending->mailsend($info_arr))
					{
					
						echo $cnt.' = '.$email_res[0]['email'].'   '.'Application Number = '.$reg_num.'<br>';
						$cnt++;
						//exit;
					}
					
				}
				//exit;
				
				
			}
			echo "email sent Profile";
		}
	}
}