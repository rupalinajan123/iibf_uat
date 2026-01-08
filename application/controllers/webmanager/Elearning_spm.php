<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	class Elearning_spm extends CI_Controller 
	{
		public function __construct()
  	{ 		
   		parent::__construct();
			//$this->load->helper(array('form', 'url'));
			//this->load->helper('page');
			/* Load form validation library */ 
			//$this->load->library('upload');
			//$this->load->library('email');
			//$this->load->library('pagination');
			//$this->load->library('table');	
			
			$this->load->library('form_validation');
			$this->load->model('Master_model'); 
			$this->load->library('session');    
			
			error_reporting(E_ALL);// Report all errors
			ini_set("error_reporting", E_ALL);// Same as error_reporting(E_ALL);
		}
		
		public function index()
		{
			if(!$this->session->userdata('username')) { redirect(site_url('webmanager/login/logout')); }			
			$count = $from_date = $to_date = $el_subject = $type = '';
			
			if(isset($_POST) && count($_POST) > 0)
			{
				//echo '<pre>'; print_r($_POST); exit;
				
				$this->form_validation->set_rules('from_date', 'From Date', 'trim|required', array('required'=>"Please select the %s"));
				$this->form_validation->set_rules('to_date', 'To Date', 'trim|required', array('required'=>"Please select the %s"));
				$this->form_validation->set_rules('el_subject', 'Subject', 'trim');
				$this->form_validation->set_rules('type', 'Type', 'trim');
				
				if ($this->form_validation->run() == TRUE)
				{
					if(isset($_POST['from_date']) && $_POST['from_date'] != "") { $from_date = date("Y-m-d",strtotime($_POST['from_date'])); } else { $from_date = ''; }
					if(isset($_POST['to_date']) && $_POST['to_date'] != "") { $to_date = date("Y-m-d",strtotime($_POST['to_date'])); } else { $to_date = ''; }
					
					if(isset($_POST['el_subject']) && $_POST['el_subject'] != "") { $el_subject = $_POST['el_subject']; }
					
					if(isset($_POST['type']) && $_POST['type'] != "") { $type = $_POST['type']; }
					
					$count = $total_exam_count = $total_el_count = 0;
					
					//echo $type; exit;
					
					$total_exam_data = array();
					if($type == "" || $type == "exam")
					{
						//START : ALL RPE DATA
						$this->db->where('ac.remark','1');
						$this->db->where('me.elearning_flag','Y');	
						$this->db->where('me.free_paid_flg','P');						
						$this->db->where('me.pay_status','1');						
						$this->db->where('me.institute_id','0');						
						$this->db->where('me.exam_period','777');						
										
						if($from_date != "") { $this->db->where('DATE(me.created_on) >=', $from_date); }
						if($to_date != "") { $this->db->where('DATE(me.created_on) <=', $to_date); }
						if($el_subject != "") 
						{ 
							$el_subject_arr = explode("##", $el_subject);
							$this->db->where('ac.exm_cd', $el_subject_arr[0]); 
							$this->db->where('ac.sub_cd', $el_subject_arr[1]); 
						}
						$this->db->group_by('ac.admitcard_id');
						$this->db->order_by('me.created_on ASC');
						$this->db->join('member_registration mr', 'mr.regnumber = me.regnumber', 'LEFT', FALSE);
						$this->db->join('admit_card_details ac', 'ac.mem_exam_id = me.id', 'INNER', FALSE);
						$total_exam_data1 = $this->master_model->getRecords('member_exam me', array(), 'mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.mobile, mr.state, mr.regnumber, ac.exm_cd, ac.sub_cd, ac.sub_dsc, me.created_on, ac.admitcard_id, me.exam_period');
						//END : ALL RPE DATA
						
						//START : ALL DATA EXCLUDING RPE
						$this->db->where('ac.remark','1');
						$this->db->where('me.elearning_flag','Y');	
						$this->db->where('ac.sub_el_flg','Y');	
						$this->db->where('me.free_paid_flg','P');						
						$this->db->where('me.pay_status','1');						
						$this->db->where('me.institute_id','0');
						$this->db->where('me.exam_period !=','777');
										
						if($from_date != "") { $this->db->where('DATE(me.created_on) >=', $from_date); }
						if($to_date != "") { $this->db->where('DATE(me.created_on) <=', $to_date); }
						if($el_subject != "") 
						{ 
							$el_subject_arr = explode("##", $el_subject);
							$this->db->where('ac.exm_cd', $el_subject_arr[0]); 
							$this->db->where('ac.sub_cd', $el_subject_arr[1]); 
						}
						$this->db->group_by('ac.admitcard_id');
						$this->db->order_by('me.created_on ASC');
						$this->db->join('member_registration mr', 'mr.regnumber = me.regnumber', 'LEFT', FALSE);
						$this->db->join('admit_card_details ac', 'ac.mem_exam_id = me.id', 'INNER', FALSE);
						$total_exam_data2 = $this->master_model->getRecords('member_exam me', array(), 'mr.namesub, mr.firstname, mr.middlename, mr.lastname, mr.email, mr.mobile, mr.state, mr.regnumber, ac.exm_cd, ac.sub_cd, ac.sub_dsc, me.created_on, ac.admitcard_id, me.exam_period');						
						//END : ALL DATA EXCLUDING RPE
						
						$total_exam_count = count($total_exam_data1) + count($total_exam_data2);
						$total_exam_data = array_merge($total_exam_data1, $total_exam_data2);
						$data['chk_qry'] = $this->db->last_query();
					}
					
					$total_el_data = array();
					if($type == "" || $type == "spm_el")
					{
						$this->db->where('ms.status','1'); 
						$this->db->where('pt.status','1'); 
						$this->db->where('pt.pay_type','20'); 
											
						if($from_date != "") { $this->db->where('ms.created_on >=', $from_date." 00:00:00"); }
						if($to_date != "") { $this->db->where('ms.created_on <=', $to_date." 23:59:59"); }
						if($el_subject != "") 
						{ 
							$el_subject_arr = explode("##", $el_subject);
							$this->db->where('ms.exam_code', $el_subject_arr[0]); 
							$this->db->where('ms.subject_code', $el_subject_arr[1]); 
						}
						
						$this->db->join('payment_transaction pt', 'pt.id = ms.pt_id', 'LEFT', FALSE);
						$this->db->join('spm_elearning_registration er', 'er.regid = ms.regid', 'LEFT', FALSE);
						$this->db->order_by('ms.created_on ASC'); 
						//$total_el_count = $this->master_model->getRecordCount('spm_elearning_member_subjects ms'); 
						$total_el_data = $this->master_model->getRecords('spm_elearning_member_subjects ms', array(), 'er.namesub, er.firstname, er.middlename, er.lastname, er.email, er.mobile, er.state, ms.regnumber, ms.exam_code, ms.subject_code, ms.subject_description, ms.created_on'); 
						$total_el_count = count($total_el_data);
						//echo $this->db->last_query();
					}
					
					if($type == "")
					{
						$count = $total_exam_count + $total_el_count;
					}
					else if($type == "exam")
					{
						$count = $total_exam_count;
					}
					else if($type == "spm_el")
					{
						$count = $total_el_count;
					}
									
					if(isset($_POST['download_csv']) && $_POST['download_csv'] != "")
					{
						$final_arr = array();						
						
						if(count($total_exam_data) > 0)
						{
							foreach($total_exam_data as $total_exam_res)
							{
								$temp_arr = array();
								$temp_arr['namesub'] = $total_exam_res['namesub'];
								$temp_arr['firstname'] = $total_exam_res['firstname'];
								$temp_arr['middlename'] = $total_exam_res['middlename'];
								$temp_arr['lastname'] = $total_exam_res['lastname'];
								$temp_arr['email'] = $total_exam_res['email'];
								$temp_arr['mobile'] = $total_exam_res['mobile'];
								$temp_arr['state'] = $total_exam_res['state'];
								$temp_arr['regnumber'] = $total_exam_res['regnumber'];
								$temp_arr['exam_code'] = $total_exam_res['exm_cd'];
								$temp_arr['subject_code'] = $total_exam_res['sub_cd'];
								$temp_arr['subject_description'] = $total_exam_res['sub_dsc'];
								$temp_arr['created_on'] = $total_exam_res['created_on'];
								$temp_arr['type'] = 'Exam';
								
								$final_arr[] = $temp_arr;
							}
						}
						
						if(count($total_el_data) > 0)
						{
							foreach($total_el_data as $total_el_res)
							{
								$temp_arr = array();
								$temp_arr['namesub'] = $total_el_res['namesub'];
								$temp_arr['firstname'] = $total_el_res['firstname'];
								$temp_arr['middlename'] = $total_el_res['middlename'];
								$temp_arr['lastname'] = $total_el_res['lastname'];
								$temp_arr['email'] = $total_el_res['email'];
								$temp_arr['mobile'] = $total_el_res['mobile'];
								$temp_arr['state'] = $total_el_res['state'];
								$temp_arr['regnumber'] = $total_el_res['regnumber'];
								$temp_arr['exam_code'] = $total_el_res['exam_code'];
								$temp_arr['subject_code'] = $total_el_res['subject_code'];
								$temp_arr['subject_description'] = $total_el_res['subject_description'];
								$temp_arr['created_on'] = $total_el_res['created_on'];
								$temp_arr['type'] = 'Separate E-learning';
								
								$final_arr[] = $temp_arr;
							}
						}
						
						array_multisort(array_column($final_arr, 'created_on'), SORT_ASC, $final_arr);

						//echo '<pre>'; print_r($final_arr); echo '</pre>'; exit;
						
						$csv = "Sr. No, Name Sub, First Name, Middle Name, Last Name, Email, Mobile, State, Regnumber, Exam Code, Subject Code, Subject Name, Type, Date \n";
						
						if(count($final_arr) > 0)
						{	
							$sr_no = 1;
							foreach($final_arr as $row)
							{	
								 $csv.= $sr_no.",".$row['namesub'].",".$row['firstname'].",".$row['middlename'].",".$row['lastname'].",".$row['email'].",".$row['mobile'].",".$row['state'].",".$row['regnumber'].",".$row['exam_code'].",".$row['subject_code'].",".$row['subject_description'].",".$row['type'].",".date("Y-m-d h:ia", strtotime($row['created_on']))."\n"; 
								 $sr_no++;
							}
						}
						
						$filename = "elearning_spm_data_".date("YmdHis").".csv";
						header('Content-type: application/csv');
						header('Content-Disposition: attachment; filename='.$filename);
						$csv_handler = fopen('php://output', 'w');
						fwrite ($csv_handler,$csv);
						fclose ($csv_handler);
						exit;
					}
				}
			}
			
			$data['count'] = $count;			
			$data['from_date'] = $from_date;
			$data['to_date'] = $to_date;
			$data['el_subject'] = $el_subject;
			$data['type'] = $type;
			$data['exam_data'] = $this->master_model->getRecords('spm_elearning_subject_master', array('subject_delete'=>'0'), 'id, exam_code, subject_code, subject_description', array('subject_description'=>'ASC'));
			$this->load->view('webmanager/elearning_spm',$data);
		}

		 function test_csv(){
    		$csv = "Sr. No, Exam code, Center code, Center name, Exam period, Registration count\n";
    	  $filename = "elearning_spm_data_".date("YmdHis").".csv";
				header('Content-type: application/csv');
				header('Content-Disposition: attachment; filename='.$filename);
				$csv_handler = fopen('php://output', 'w');
				fwrite ($csv_handler,$csv);
				fclose ($csv_handler);
				exit;
    }
	}		