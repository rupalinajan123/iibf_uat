<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class FeedbackForm extends CI_Controller
	{
		public $UserID;
		
		public function __construct()
		{
			parent::__construct();
			$this->load->library('upload');	
			$this->load->helper('upload_helper');
			$this->load->helper('master_helper');
			$this->load->library('email');
			$this->load->model('Emailsending');
			$this->load->model('log_model');
			//exit;
			
		}
		public function index()
		{
			if(isset($_POST['btnSubmitmem']))  	
			{
				
				$regnumber = trim($this->input->post('regnumber'));
				$this->db->where('regnumber',$regnumber);
				$chk_reg= $this->master_model->getRecordCount('feedback_form');
				if($chk_reg > 0)
				{
					$this->session->set_flashdata('success','Your feedback is already registered with us. Thank you!');
					redirect(base_url().'FeedbackForm');
				}
				
				$this->db->where('regnumber',$regnumber);
				$this->db->where('exam_period',121);
				$this->db->where_in('exam_code',array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeSOB')));
				$this->db->where('pay_status',1);
				$chk_jaiib = $this->master_model->getRecordCount('member_exam');
				//echo $this->db->last_query(); die;
				if($chk_jaiib == 0)
				{
					$this->session->set_flashdata('error','You are not eligible member to give the feedback.');
					redirect(base_url().'FeedbackForm');
				}
				else{
					
					$this->form_validation->set_rules('regnumber','Member no','trim|required|xss_clean');
					$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
					if($this->form_validation->run()==TRUE)
					{
						$sess_array = array(
						'regnumber'=>$_POST["regnumber"],
						'email'=>$_POST["email"],
						);
						
						/* Stored User Details In The Session */
							$this->session->set_userdata('enduserinfo', $sess_array);
							$this->form_validation->set_message('error', "");
							
					}
					redirect(base_url().'FeedbackForm/feedback_questions');
				}
				
			}
			$this->load->view('feedback_form');
		}
		public function feedback_questions()
		{
			if(!$this->session->userdata('enduserinfo'))
			{
				redirect(base_url().'FeedbackForm'); 
			}
			else{
			
			$regnumber = $this->session->userdata['enduserinfo']['regnumber'];
			$email = $this->session->userdata['enduserinfo']['email'];
			if(!empty($regnumber))
			{
				
			if(isset($_POST['btnSubmit']))  	
			{
				
				//print_r($_POST); die;
				//$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');
				$this->form_validation->set_rules('streams','Streams','trim|required|xss_clean');
				$this->form_validation->set_rules('years','work experience','trim|required|xss_clean');
				$this->form_validation->set_rules('macmillan','IBF books published by Macmillan','trim|required|xss_clean');
				$this->form_validation->set_rules('e-learning','e-learning provided by IIBF','trim|required|xss_clean');
				$this->form_validation->set_rules('mock','IBF mock test','trim|required|xss_clean');
				$this->form_validation->set_rules('subject','subject updates provided on IIBF website','trim|required|xss_clean');
				$this->form_validation->set_rules('time','time spent by you in preparing for the exam','trim|required|xss_clean');
				$this->form_validation->set_rules('exam','examination','trim|required|xss_clean');
				$this->form_validation->set_rules('subject_difficult[]','subject difficult','trim|required|xss_clean');
				$this->form_validation->set_rules('questions[]','questions difficult','trim|required|xss_clean');
				$this->form_validation->set_rules('support[]','additional pedagogical support','trim|required|xss_clean');
				$this->form_validation->set_rules('examtime','time allotted for the exam','trim|required|xss_clean');
				$this->form_validation->set_rules('suggestions','suggestions','trim|required|xss_clean');
				if(isset($_POST['streams']) && $_POST['streams'] =='others' )
				{
					$this->form_validation->set_rules('otherstreams','OtherStream','trim|required|xss_clean');
				}					
				
				if($this->form_validation->run()==TRUE)
				{
				  
					$subject_difficult = implode(",",$this->input->post('subject_difficult'));
					$questions = implode(",",$this->input->post('questions'));
					$support = implode(",",$this->input->post('support'));
					
					$insert_array = array(
					'regnumber'=>$regnumber,
					'email'=>$email,
					'streams'=>$this->input->post('streams'),
					'otherstreams'=>$this->input->post('otherstreams'),
					'years'=>$this->input->post('years'),
					'macmillan'=>$this->input->post('macmillan'),
					'e-learning'=>$this->input->post('e-learning'),
					'mock'=>$this->input->post('mock'),
					'subject'=>$this->input->post('subject'),
					'time'=>$this->input->post('time'),
					'subject_difficult'=>$subject_difficult,
					'questions'=>$questions,
					'support'=>$support,
					'examtime'=>$this->input->post('examtime'),
					'suggestions'=>$this->input->post('suggestions')
					
					);
					
					$this->master_model->insertRecord('feedback_form',$insert_array,true);
					$this->session->set_flashdata('success','Thank you for giving feedback.');
					redirect(base_url().'FeedbackForm');
				}
			}
			$this->load->view('feedback_form_questions');
			}
					
			else{
				$this->session->set_flashdata('error','You are not eligible to give the feedback.');
				redirect(base_url().'FeedbackForm');
			}
			}
		}
		
		public function feedback_details()
		{
			$data = '';
			//$exam_code_arr = array();
			//$from_date = '2020-05-01'; //date('Y-m-d', strtotime("- 1 week"));  
			//$to_date = '2020-05-31'; //date('Y-m-d', strtotime("- 1 day")) ; 
			/* $date = new DateTime('LAST DAY OF PREVIOUS MONTH');
				$to_date =  $date->format('Y-m-d');
				$from_date = date("Y-m-", strtotime($to_date))."01"; 
			*/		
			$csv = "Member no, Email,Which one of the following streams have you studied in graduate/post graduate level?, Please indicate your work experience, Had you studied the IIBF books published by Macmillan for the examination?, Did you register and went through the e-learning provided by IIBF?, Did you register and went through the IIBF mock test?, Did you go through the subject updates provided on IIBF website?, Please indicate the time spent by you in preparing for the exam., How did you find the examination?,Which subject did you find difficult? ,In which type of questions did you face difficulty?,What type of additional pedagogical support you require from IIBF?,Do you feel that the time allotted for the exam is sufficient?,Your suggestions:,\n";
			
			$query = $this->db->query("SELECT * FROM `feedback_form`");
			$result = $query->result_array(); 
			//echo $this->db->last_query(); die;
			
			$replace_char = array(",", "\n", "\r");
			foreach($result as $record)
			{
				$csv.= str_replace($replace_char, ' ', $record['regnumber']).",".str_replace($replace_char, ' ', $record['email']).",".$record['streams'].",".$record['years'].",".$record['macmillan'].",".$record['e-learning'].",".$record['mock'].",".$record['subject'].",".$record['time'].",".$record['exam'].",".str_replace($replace_char, ' | ', $record['subject_difficult']).",".str_replace($replace_char, ' | ', $record['questions']).",".str_replace($replace_char, ' | ', $record['support']).",".$record['examtime'].",".str_replace($replace_char, ' ', trim($record['suggestions']))."\n";
			} 
			$filename = "feedback_details_".date("YmdHis").".csv";
			header('Content-type: application/csv');
			header('Content-Disposition: attachment; filename='.$filename);
			$csv_handler = fopen('php://output', 'w');
			//fputcsv($csv_handler, $csv, '|');
			fwrite ($csv_handler,$csv);
			fclose ($csv_handler);	
			
		}
		
		//call back for e-mail duplication
		public function check_emailduplication($email)
		{
			if($email!="")
			{
				//$where="( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
				//$this->db->where($where);
				$prev_count=$this->master_model->getRecordCount('feedback_form',array('email'=>$email));
				//echo $this->db->last_query();
				if($prev_count==0)
				{	
					return true;	
				}
				else
				{
					
					$str='The entered email ID already exist for membership / registration number';
					
					$this->form_validation->set_message('check_emailduplication', $str); 
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		//call back for e-mail duplication
		public function check_regnumber($regnumber)
		{
			if($regnumber!="")
			{
				
				$prev_count=$this->master_model->getRecordCount('feedback_form',array('regnumber'=>$regnumber));
				//echo $this->db->last_query();
				if($prev_count==0)
				{	
					return true;	
				}
				else
				{
					
					$str='Your feedback is already registered with us. Thank you!';
					
					$this->form_validation->set_message('check_emailduplication', $str); 
					$this->form_validation->set_message('success', "");
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		
	}	