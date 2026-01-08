<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Blended_Feedback extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('blended_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
    }
	
	
	/* Check if the member belongs to the batch or not*/
	public function chk_memno()
    {
			$batch_details=array();
			$this->db->where('is_active', 1);
		 	$batch_details = $this->master_model->getRecords('blended_feedback_dates');
			if(!empty($batch_details))
			{
				$traning_info=$traning_activation=$traning_name=$all_question=array();
				$this->db->where('batch_code', $batch_details[0]['batch_code']);
				$this->db->where('member_no', $_GET['mem_no']);
				$this->db->where('pay_status', 1);
				$member_info = $this->master_model->getRecords('blended_registration');
			
				if(empty($member_info))
				{	
						echo 'false';
						
				}else
				{
						echo 'true';
						
				}
			}else
			{
				echo 'false';
			}
	}
	
	/* Showing Blended comman view */
	public function index()
    { 
	
			$batch_details=array();
			$this->db->where('is_active',1);
		 	$batch_details = $this->master_model->getRecords('blended_feedback_dates');
			
		if(!empty($batch_details))
		{
			//echo $batch_details[0]['link_activation_date'];exit;
			if($batch_details[0]['link_activation_date']>=date('Y-m-d H:i:s'))
			{
				if(!empty($batch_details))
				{
					
					$data['batch_code']=$batch_details[0]['batch_code'];
					$data['title']=$batch_details[0]['title'];
					$data['start_date']=$batch_details[0]['start_date'];
					$data['end_date']=$batch_details[0]['end_date'];
				}else
				{
					$data['batch_code']=$data['title']=$data['start_date']=$data['end_date']='';
				
				}
				
				$data['middle_content'] =  'blended/comman_page';
				$this->load->view('blended/blended_common_view', $data);
				}
		}
		
	}
	/* Showing Blended Form */
	public function index1()
    { 
				$traning_array =$member_info=$member_emailid =$traning_name=	$already_present =array();
				//SELECT * FROM `blended_dates` WHERE `end_date` < '2018-06-04' 
				$this->db->where('isdelete', 0);
				$this->db->where('end_date <', date('Y-m-d'));
          		$traning_array = $this->master_model->getRecords('blended_dates');
				 
				foreach($traning_array as $val)
				{
					//get all member of the traning 
					$this->db->where('pay_status', 1);
					$this->db->where('batch_code', $val['batch_code']);
          		 	$member_info = $this->master_model->getRecords('blended_registration');
					
					
					//get all member of the traning 
					$this->db->where('email_send', 1);
					$this->db->where('batch_code', $val['batch_code']);
          		 	$already_present = $this->master_model->getRecords('blended_feedback_dates');
					
					if(empty($already_present))
					{
						
					//insert into traning table for which the email are send
					// add 3 days to date
					$NewDate=date('Y-m-d', strtotime("+3 days"));
					    $insert_data = array(
								'program_code ' => $val['program_code'],
								'batch_code ' => $val['batch_code'],
							     'training_type' => $val['training_type'],
								'start_date' => $val['start_date'],
								'end_date ' => $val['end_date'],
								'center_name' =>$val['center_name'],
								'email_send' =>1,
								'date_of_email_sending' => date('Y-m-d H:i:s'),
								'link_activation_date '=>$NewDate
								);
			
            			$pt_id=$this->master_model->insertRecord('blended_feedback_dates', $insert_data, true);
						
					}
					
					foreach($member_info as $mem)
					{
						
						//get member email id from member registration table 
						$this->db->where('isactive','1');
						$this->db->where('regnumber', $mem['member_no']);
						$member_emailid = $this->master_model->getRecords('member_registration','','email');
						
						if(!empty($member_emailid))
						{
							//send email to member 
							/* Set Email sending options */
							//get traning name
							
							$this->db->where('program_code', $mem['program_code']);
							$traning_name = $this->master_model->getRecords('blended_program_master','','program_name');
							
							$infrastructure_feedback ='http://iibf.teamgrowth.net/blended_feedback/infrastructure_feedback/'.$val['dates_id'].'/';
							$emailerstr[0]['subject']='Blended traning feed back form for '.$traning_name[0]['program_name'];
							$newstring5='<!DOCTYPE html>
												<html>
												<head>
												<title>Title of the document</title>
												</head>
												
												<body>
												<h1>Infrastructure_feedback : </h1>'.$infrastructure_feedback.'
												
												</body>
												
												</html>
';
							$info_arr          = array(
							//'to' =>'',
							'to' => 'kyciibf@gmail.com',
							//'to' => 'bhushan.amrutkar09@gmail.com',
							'from' =>'noreply@iibf.org.in',
							'subject' => $emailerstr[0]['subject'],
							'message' => $newstring5
							);	
							$attachpath = '';
							/* SMS Sending Code */
							//$sms_newstring = str_replace("#program_name#", "" . $program_name . "", $emailerstr[0]['sms_text']);
							//$this->master_model->send_sms($user_info[0]['mobile'], $sms_newstring);
							if($this->Emailsending->mailsend_attch($info_arr, $attachpath))
							{
								echo 'Email send';
								echo '<br>';
								echo 'Blended traning feed back form for '.$traning_name[0]['program_name'];
									echo '<br>';
								echo 'program code : '.$mem['program_code'];
									echo '<br>';
								echo 'Batch code : '.$mem['batch_code'];
								
								
							}else
							{
								echo 'error for email send';
							}
								break;
						}

					}
			break;
				}
			  
    }
/* call infrastructure feedback */
public function infrastructure_feedback()
	{
		if($this->input->post('btnSubmit')!='')
		{
				
			//echo '**';
			//print_r($_POST);exit;
			//$last_date_feedback=array();
			//$current_date  = date("Y-m-d H:i:s");
			//$this->db->where('dates_id', $id);
			//$last_date_feedback = $this->master_model->getRecords('blended_dates');
			//if(!empty($last_date_feedback))
			//{
				//if(	$last_date_feedback[0]['feedback_email_lastdate']<$current_date)
				//{	
				
				//echo 'Data has been save';
					//$data['middle_content'] =  'blended/blended_feedback_acknowledge';
					//$data['msg']='Sorry the feedback link has been closed for the training..';
					//print_r($data);exit;
					//$this->load->view('blended/blended_common_view', $data);
					
				//}else{
					
				$traning_info=$traning_activation=$traning_name=$all_question=array();
				$this->db->where('batch_code', $this->input->post('batch_code'));
				$traning_info = $this->master_model->getRecords('blended_dates');
			//	echo $this->db->last_query();exit;
				if(!empty($traning_info))
				{
					$this->db->where('batch_code',$this->input->post('batch_code'));
					//$this->db->where('email_send',1);
					$traning_activation = $this->master_model->getRecords('blended_feedback_dates');
					//insert data 
					if(empty($traning_activation))
					{
						
							$NewDate=date('Y-m-d', strtotime("+3 days"));
							$insert_data = array(
									'program_code ' => $traning_info[0]['program_code'],
									'batch_code ' => $traning_info[0]['batch_code'],
									 'training_type' => $traning_info[0]['training_type'],
									'start_date' => $traning_info[0]['start_date'],
									'end_date ' => $traning_info[0]['end_date'],
									'center_name' =>$traning_info[0]['center_name'],
									//'email_send' =>1,
									'date_of_email_sending' => date('Y-m-d H:i:s'),
									'link_activation_date '=>$NewDate
									);
				
							$pt_id=$this->master_model->insertRecord('blended_feedback_dates', $insert_data, true);
					}
					//get traning details 
					
					$this->db->where('batch_code',$traning_info[0]['batch_code']);
					//$this->db->where('email_send',1);
					$traning_activation = $this->master_model->getRecords('blended_feedback_dates');
					
					if(!empty($traning_activation))
					{
						//get traning name
						$this->db->where('program_code',$traning_info[0]['program_code']);
						$traning_name = $this->master_model->getRecords('blended_program_master');
						
						//get question and answer for the infrastructure feedback 
						
						$this->db->where('feedback_type','I');
						//$this->db->where('batch_code', $this->input->post('batch_code'));
						$this->db->where('isactive', 1);
						$this->db->order_by("topic_code", "asc");
						$all_question = $this->master_model->getRecords('infrastructure_feedback_question');
						
						//echo $this->db->last_query();exit;
						$data['traning_name']=$traning_name[0]['program_name'];
						$data['center_name']=$traning_activation[0]['center_name'];
						$data['start_traning_date']=$traning_activation[0]['start_date'];
						$data['end_traning_date']=$traning_activation[0]['end_date'];
						$data['traning_type']=$traning_activation[0]['training_type'];
						$data['batch_code']=$traning_info[0]['batch_code'];
						$data['feedback_question']=$all_question;
					}
					
					$data['middle_content'] =  'blended/infrastructure_feedback_form';
					//print_r($data);exit;
					$this->load->view('blended/blended_common_view', $data);
				}

			}
		else
		{
				
				redirect('/blended_feedback/index/');
		}
}


/*facilitators feedback*/	
public function facilitators_feedback($batch_code=NULL,$infraId=NULL)
{
	if($batch_code!='' && $infraId!='' && !is_nan($infraId))
	{
			
		//echo '**';exit;
		//$last_date_feedback=array();
		//$current_date  = date("Y-m-d H:i:s");
		//$this->db->where('dates_id', $id);
		//$last_date_feedback = $this->master_model->getRecords('blended_dates');
		//if(!empty($last_date_feedback))
		//{
			//if(	$last_date_feedback[0]['feedback_email_lastdate']<$current_date)
			//{	
			
			//echo 'Data has been save';
				//$data['middle_content'] =  'blended/blended_feedback_acknowledge';
				//$data['msg']='Sorry the feedback link has been closed for the training..';
				//print_r($data);exit;
				//$this->load->view('blended/blended_common_view', $data);
				
			//}else{
				
			$traning_info=$traning_activation=$traning_name=$all_question=array();
			$this->db->where('batch_code', $batch_code);
			$traning_info = $this->master_model->getRecords('blended_dates');
			
			if(!empty($traning_info))
			{
					
				$this->db->where('batch_code',$batch_code);
				//$this->db->where('email_send',1);
				$traning_activation = $this->master_model->getRecords('blended_feedback_dates');
				//insert data 
				if(empty($traning_activation))
				{
					
				$NewDate=date('Y-m-d', strtotime("+3 days"));
					    $insert_data = array(
								'program_code ' => $traning_info[0]['program_code'],
								'batch_code ' => $traning_info[0]['batch_code'],
							     'training_type' => $traning_info[0]['training_type'],
								'start_date' => $traning_info[0]['start_date'],
								'end_date ' => $traning_info[0]['end_date'],
								'center_name' =>$traning_info[0]['center_name'],
								//'email_send' =>1,
								'date_of_email_sending' => date('Y-m-d H:i:s'),
								'link_activation_date '=>$NewDate
								);
			
            			$pt_id=$this->master_model->insertRecord('blended_feedback_dates', $insert_data, true);
				}
				//get traning details 
				
				$this->db->where('batch_code',$traning_info[0]['batch_code']);
				//$this->db->where('email_send',1);
				$traning_activation = $this->master_model->getRecords('blended_feedback_dates');
				
				if(!empty($traning_activation))
				{
					
					//get traning name
					$this->db->where('program_code',$traning_info[0]['program_code']);
					$traning_name = $this->master_model->getRecords('blended_program_master');
					
					//get question and answer for the infrastructure feedback 
					
					$this->db->where('feedback_type','F');	
					$this->db->where('batch_code', $batch_code);
					$this->db->where('isactive', 1);
					$all_question = $this->master_model->getRecords('faculty_feedback_question');
					$data['traning_name']=$traning_name[0]['program_name'];
					$data['center_name']=$traning_activation[0]['center_name'];
					$data['start_traning_date']=$traning_activation[0]['start_date'];
					$data['end_traning_date']=$traning_activation[0]['end_date'];
					$data['traning_type']=$traning_activation[0]['training_type'];
					$data['batch_code']=$traning_info[0]['batch_code'];
					$data['feedback_question']=$all_question;
						
				}
				
				$data['middle_content'] =  'blended/facilitators_feedback_form';
				//print_r($data);exit;
				$this->load->view('blended/blended_common_view', $data);
			}
	
		}else
		{
			
				redirect('/blended_feedback/index/');
		}
	}	
/*Save the both feedback form */	
public function save_feedback($batch_code=NULL)
{
	//print_r($_POST);exit;
	//echo count($_POST);exit;
	//Array ( [1] => relevant [2] => helpful [3] => very helpful [4] => average [5] => good [btnSubmit] => Submit ) 
	if($this->input->post('Continue')!='')
	{
		//print_r($_POST);exit;
		
		
	$data['msg']="";
	
	//check member is valid or not 

		$this->session->set_userdata('mem_no', $_POST['mem_no']);
	    $this->session->set_userdata('mem_name', $_POST['mem_name']);
		
	
		//	print_r($_POST);exit;
		// save  the feed back 
			$traning_info=$traning_activation=$traning_name=$all_question=array();
			$this->db->where('batch_code', $batch_code);
			$traning_info = $this->master_model->getRecords('blended_dates');
			
			if(!empty($traning_info))
			{ 
				if(count($_POST)>6)
				{
					if(!isset($_POST['cq1']) && !isset($_POST['Q1']))
					{
						$_POST['cq1']=$_POST['Q1']=0;
					}
					if(!isset($_POST['cq2']) && !isset($_POST['Q2']))
					{
						$_POST['cq2']=$_POST['Q2']=0;
					}
				if(!isset($_POST['cq3']) && !isset($_POST['Q3']))
					{
						$_POST['cq3']=$_POST['Q3']=0;
					}
				if(!isset($_POST['cq4']) && !isset($_POST['Q4']))
					{
						$_POST['cq4']=$_POST['Q4']=0;
					}if(!isset($_POST['cq5']) && !isset($_POST['Q5']))
					{
						$_POST['cq5']=$_POST['Q5']=0;
					}
				if(!isset($_POST['cq6']) && !isset($_POST['Q6']))
					{
						$_POST['cq6']=$_POST['Q6']=0;
					}
					
						$insert_info        = array(  
						'member_no' => $_POST['mem_no'],
						'mem_name' => $_POST['mem_name'],
						'batch_code' => $traning_info[0]['batch_code'],
						'program_code' => $traning_info[0]['program_code'],
						'traning_type' => $traning_info[0]['training_type'],
						'center' => $traning_info[0]['center_name'],
						'topic1_code' =>$_POST['cq1'],
						'q1_ans' =>$_POST['Q1'],
						'topic2_code' =>$_POST['cq2'],
						'q2_ans' => $_POST['Q2'],
						'topic3_code' =>$_POST['cq3'],
						'q3_ans' => $_POST['Q3'],
						'topic4_code' =>$_POST['cq4'],
						'q4_ans' =>$_POST['Q4'],
						'topic5_code' =>$_POST['cq5'],
						'q5_ans' =>$_POST['Q5'],
						'topic6_code' =>$_POST['cq6'],
						'q6_ans' =>$_POST['Q6'],
						'comment' =>$_POST['comment'],
						'feedback_type' => 'I',
						'creation_date' => date('Y-m-d H:i:s')
					  );
				}else
				{
						$this->session->flashdata('Please the proper question');
						redirect('/blended_feedback/index/');
				}
		//echo "<pre>"; print_r($insert_info); echo "</pre>";exit;
		
		/* Stored user details and selected field details in the database table */
        if ($last_id = $this->master_model->insertRecord('blended_feedback_infrastructure', $insert_info, true)) 
		{
		
			redirect('/blended_feedback/facilitators_feedback/'.$batch_code.'/'.$last_id);	

		}
      
	}
	}elseif($this->input->post('submit_f')!='')
	{
			//print_r($_POST);exit;
			$data['msg']="";
			// save  the feed back 
			$traning_info=$traning_activation=$traning_name=$all_question=array();
			$this->db->where('batch_code', $batch_code);
			$traning_info = $this->master_model->getRecords('blended_dates');
			
			if(!empty($traning_info))
			{
				if(count($_POST)>8)
				{
					if(!isset($_POST['cq1']) && !isset($_POST['Q1']))
					{
						$_POST['cq1']=$_POST['Q1']=0;
					}
					if(!isset($_POST['cq2']) && !isset($_POST['Q2']))
					{
						$_POST['cq2']=$_POST['Q2']=0;
					}
					if(!isset($_POST['cq3']) && !isset($_POST['Q3']))
					{
						$_POST['cq3']=$_POST['Q3']=0;
					}
					if(!isset($_POST['cq4']) && !isset($_POST['Q4']))
					{
						$_POST['cq4']=$_POST['Q4']=0;
					}if(!isset($_POST['cq5']) && !isset($_POST['Q5']))
					{
						$_POST['cq5']=$_POST['Q5']=0;
					}
					if(!isset($_POST['cq6']) && !isset($_POST['Q6']))
					{
						$_POST['cq6']=$_POST['Q6']=0;
					}
					if(!isset($_POST['cq7']) && !isset($_POST['Q7']))
					{
						$_POST['cq7']=$_POST['Q7']=0;
					}
					if(!isset($_POST['cq8']) && !isset($_POST['Q8']))
					{
						$_POST['cq8']=$_POST['Q8']=0;
					}
					if(!isset($_POST['cq9']) && !isset($_POST['Q69']))
					{
						$_POST['cq9']=$_POST['Q9']=0;
					}
					if(!isset($_POST['cq10']) && !isset($_POST['Q10']))
					{
						$_POST['cq10']=$_POST['Q10']=0;
					}
					
					$insert_info        = array(  
					'member_no' => $_POST['mem_no'],
					'mem_name' => $_POST['mem_name'],
					'batch_code' => $traning_info[0]['batch_code'],
					'program_code' => $traning_info[0]['program_code'],
					'traning_type' => $traning_info[0]['training_type'],
					'center' => $traning_info[0]['center_name'],
					'topic1_code' =>$_POST['cq1'],
					'q1_ans' =>$_POST['Q1'],
					'topic2_code' =>$_POST['cq2'],
					'q2_ans' => $_POST['Q2'],
					'topic3_code' =>$_POST['cq3'],
					'q3_ans' => $_POST['Q3'],
					'topic4_code' =>$_POST['cq4'],
					'q4_ans' =>$_POST['Q4'],
					'topic5_code' =>$_POST['cq5'],
					'q5_ans' =>$_POST['Q5'],
					'topic6_code' =>$_POST['cq6'],
					'q6_ans' =>$_POST['Q6'],
					'topic7_code' =>$_POST['cq7'],
					'q7_ans' =>$_POST['Q7'],
					'topic8_code' =>$_POST['cq8'],
					'q8_ans' =>$_POST['Q8'],
					'topic9_code' =>$_POST['cq9'],
					'q9_ans' =>$_POST['Q9'],
					'topic10_code' =>$_POST['cq10'],
					'q10_ans' =>$_POST['Q10'],
					'device_used' =>$_POST['Q11'],
					'connectivity' =>$_POST['Q12'],
					'comment' =>$_POST['comment'],
					'feedback_type' => 'F',
					'creation_date' => date('Y-m-d H:i:s')
       				 );
		}else
				{
						$this->session->flashdata('Please the proper question');
						redirect('/blended_feedback/index/');
				}
	//echo "<pre>"; print_r($insert_info); echo "</pre>";exit;
		
			/* Stored user details and selected field details in the database table */
			if ($last_id = $this->master_model->insertRecord('blended_feedback_Faculty', $insert_info, true)) 
			{
				
				$data['middle_content'] =  'blended/blended_feedback_acknowledge';
					//print_r($data);exit;
				$this->load->view('blended/blended_common_view', $data);
				
			}
		}
		else
		{
		
			redirect('/blended_feedback/index');
		}
	}else
	{
	
		redirect('/blended_feedback/index');
	}
}

}