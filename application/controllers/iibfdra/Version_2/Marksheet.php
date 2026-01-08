<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marksheet extends CI_Controller {

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
	 
	public function __construct()
	{
		 parent::__construct(); 
	} 
	
	public function pdc734(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login734',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc735(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login735',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc736(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login736',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc737(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login737',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc738(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login738',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc739(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}


				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login739',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc740(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){ 
							   $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1]));
								$record = $this->db->get();
								$user_info1 = $record->row();
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login740',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc741(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
										array(
												'field' => 'Password',
												'label' => 'Password',
												'rules' => 'trim|required',
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$encpass = $aes->encrypt($this->input->post('Password'));
					
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
						
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){
							  
							    $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->join('member_registration', 'member_registration'.'.regnumber = pdc_member.member_number');
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1],'usrpassword'=>$encpass));
								$record = $this->db->get();
								$user_info1 = $record->row();
								
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login741',$data);
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc742(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
										array(
												'field' => 'Password',
												'label' => 'Password',
												'rules' => 'trim|required',
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$encpass = $aes->encrypt($this->input->post('Password'));
					
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
						
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){
							  
							    $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->join('member_registration', 'member_registration'.'.regnumber = pdc_member.member_number');
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1],'usrpassword'=>$encpass));
								$record = $this->db->get();
								$user_info1 = $record->row();
								
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login742',$data); 
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	public function pdc743(){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			 if(isset($_POST['submit'])){ 
					$config = array(
									array(
											'field' => 'exam',
											'label' => 'Exam',
											'rules' => 'trim|required'
										),
										array(
												'field' => 'Password',
												'label' => 'Password',
												'rules' => 'trim|required',
										),
									array(
											'field' => 'Username',
											'label' => 'Registration/Membership No.',
											'rules' => 'trim|required'
										),
									);
					$this->form_validation->set_rules($config);
					
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$encpass = $aes->encrypt($this->input->post('Password'));
					
					$dataarr=array(
						'regnumber'=> $this->input->post('Username'),
						
					);
					if ($this->form_validation->run() == TRUE){
						 if($this->db->table_exists('pdc_member')){
							  
							    $this->db->select('*');
								$this->db->from('pdc_member');
								$exam_code=explode('_',$this->input->post('exam'));
								$this->db->join('member_registration', 'member_registration'.'.regnumber = pdc_member.member_number');
								$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'period'=>$exam_code[1],'usrpassword'=>$encpass));
								$record = $this->db->get();
								$user_info1 = $record->row();
								
								if(count($user_info1) > 0){ 
									$mysqltime=date("H:i:s");
									$result__data=array('result_mem_no'=>$user_info1->member_number,
														 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
														 'result_examcode'=>$exam_code[0],
														 'result_period'=>$exam_code[1],
														 'result_fucn' => 'pdc'
													   );
									$this->session->set_userdata($result__data);
									redirect(base_url().'marksheet/result_feedback');
							}
						 }
						 //echo $this->db->last_query();
						 $data['error']='<span style="">Invalid credential.</span>';
					} else{
						$data['validation_errors'] = validation_errors();
					}
				}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$this->load->view('p_login743',$data); 
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	// common login function for PDC result
	public function pdcresult($exam_period=''){
	try{
		/* $exam_period = base64_decode($this->uri->segment(3)); */
		$exam_code = $this->input->post('exam');
		
		$this->db->select('exam_conduct');
		$this->db->from('result_exam');
		$this->db->where(array('exam_period' => $exam_period));
		$result = $this->db->get();
		$exam_conduct = $result->row(); 
		$mntyr = explode("/",$exam_conduct->exam_conduct);
		
		$month = $mntyr[0];
		$month_num = date('m', strtotime($month));
		$month_name = date("F", mktime(0, 0, 0, $month_num, 10));
		$year = $mntyr[1];
		
		$flag=0;
		$data=array();
		$data['error']='';
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
											'field' => 'Password',
											'label' => 'Password',
											'rules' => 'trim|required',
									),
								array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
								);
				$this->form_validation->set_rules($config);
				
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					
				);
				if ($this->form_validation->run() == TRUE){
					 if($this->db->table_exists('pdc_member')){
						  
							$this->db->select('*');
							$this->db->from('pdc_member');
							
							//$exam_code=explode('_',$this->input->post('exam'));
							
							$this->db->join('member_registration', 'member_registration'.'.regnumber = pdc_member.member_number');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username'),'period'=>$exam_period,'usrpassword'=>$encpass));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>$exam_period,
													 'result_fucn' => 'pdc'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'marksheet/result_feedback');
						}
					 }
					 //echo $this->db->last_query();
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$exam_result = '';
			$data['exam']=$exam_result;
			$data['month']=$month_name;
			$data['year']=$year;
			$this->load->view('pdc_login',$data); 
		}catch(Exception $e){
			echo 'Message: ' .$e->getMessage();
		}	
	}
	// common function
	public function pdc_dashboard(){
		$this->load->view('pdc_dashboard');	
	}
	// common function
	public function pdc_adviceresult(){
		try{ 
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'marksheet/pdc'.$this->session->userdata('result_period'));
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_period = $this->session->userdata('result_period');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('pdc_member');
			$this->db->where(array("exam_code"=>$exam_code,"period"=>$exam_period),'',false);
			
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			$record = $this->db->get();
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			
			$exam_name = $result[0]['description']; // get exam name
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			
			$marktable = "pdc_marks";
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('pdc_marks.exam_period',$exam_period);
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('pdc_adviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	
	public function dipcert804(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					 if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 }
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(20,59,81);
		$arr = array('804');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login804',$data);
	}
	public function dashboard804(){
		$this->load->view('dashboard804');	
	}
	public function adviceresult804(){
		try{ 
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/advice');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('memdtl_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			
			
			$exam_name = $result[0]['exam_name_full']; // get exam name
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "marks_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('adviceresult804',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function dipcert803(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					 if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 }
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(20,59,81);
		$arr = array('803');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login803',$data);
	}
	public function dashboard803(){
		$this->load->view('dashboard803');	
	}
	public function adviceresult803(){
		try{ 
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/advice');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('memdtl_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			
			
			$exam_name = $result[0]['exam_name_full']; // get exam name
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "marks_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('adviceresult803',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function dipcert805(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					
						   $this->db->select('*');
							$this->db->from('dipcert_member');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(20,34,58,59,74,81,164);
		$arr = array('805');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login805',$data);
	}
	public function dipcert806(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
										),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					
						   $this->db->select('*');
							$this->db->from('dipcert_member');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->join('member_registration', 'member_registration'.'.regnumber = dipcert_member.member_number');
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'usrpassword'=>$encpass));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(20,34,58,59,81); 
		$arr = array('806');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login806',$data);
	}
	public function dipcert807(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
										),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					
						   $this->db->select('*');
							$this->db->from('dipcert_member');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->join('member_registration', 'member_registration'.'.regnumber = dipcert_member.member_number');
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'usrpassword'=>$encpass));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(20,34,58,59,81); 
		$arr = array('807');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login807',$data);
	}
	
	public function dipcert808(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
										),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					
						   $this->db->select('*');
							$this->db->from('dipcert_member');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->join('member_registration', 'member_registration'.'.regnumber = dipcert_member.member_number');
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username'),'usrpassword'=>$encpass));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(8,11,18,19,20,24,25,26,34,58,59,74,78,79,81,135,148,149,151,153,156,158,160,162,163,164,165,166,175,177); 
		$arr = array('808');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('login808',$data);
	}
	
	// common login function for diploma and certification result
	public function dipcertresult($exam_period=''){
		
		/* $exam_period = base64_decode($this->uri->segment(3)); */
		$exam_code = $this->input->post('exam');
		$excode=$this->master_model->getRecords('result_exam',array('exam_period'=>$exam_period),'exam_conduct,exam_code,exam_period,exam_name_full');
		$mntyr = explode("/",$excode[0]['exam_conduct']);
		$month = $mntyr[0];
		$month_num = date('m', strtotime($month));
		$month_name = date("F", mktime(0, 0, 0, $month_num, 10));
		$year = $mntyr[1];
		
		
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
		 	if($exam_period != 809){
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
										),*/
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			 }
			if($exam_period == 809){
				 $config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
										),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			 }
			if($exam_period == 809){
					
					include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
					$key = $this->config->item('pass_key');
					$aes = new CryptAES();
					$aes->set_key(base64_decode($key));
					$aes->require_pkcs5();
					$encpass = $aes->encrypt($this->input->post('Password'));
			}
				
				$this->form_validation->set_rules($config);
				
				if ($this->form_validation->run() == TRUE){
					
					
						   $this->db->select('*');
							$this->db->from('dipcert_member');
							//$exam_code=explode('_',$this->input->post('exam'));
							$this->db->join('member_registration', 'member_registration'.'.regnumber = dipcert_member.member_number');
							$this->db->where('period',$exam_period);
							if($exam_period == 809){
								$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username'),'usrpassword'=>$encpass));
							}
							if($exam_period != 809){
								$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							}
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>$exam_period,
													 'result_fucn' => 'dipcert'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'marksheet/result_feedback');
						}
					 
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			} 
		$exarr = array(20,34,58,59,81); 
		$arr = array('807');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$data['month']=$month_name;
		$data['year']=$year;
		$data['excode']=$excode;
		$data['experiod']=$exam_period;
			
		$this->load->view('dipcertlogin',$data);
	}
	// common function
	public function dipcert_dashboard(){
		$this->load->view('dipcert_dashboard_new');	
	}
	// common function
	public function dipcert_adviceresult(){
		try{ 
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/advice');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_period = $this->session->userdata('result_period');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('dipcert_member');
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			$this->db->where('period' , $exam_period, false);
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			$exam_name = $result[0]['exam_name_full']; // get exam name
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "dipcert_mark";
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('dipcert_mark.exam_period',$exam_period);
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			
			/*echo $this->db->last_query();
			echo "<pre>";
			print_r($record);
			exit;*/
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct,"exam_period"=>$exam_period);
			$this->load->view('dipcert_adviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	// common function
	public function dipcert_consolidated(){
	try{
		$datearr=array();$exam_period='';
		if($this->session->userdata('result_mem_no')=='')
		{ 
			redirect(base_url().'marksheet/dipcertresult/'.base64_encode($this->session->userdata('result_period')));
		}
		$member_id = $this->session->userdata('result_mem_no');
		$exam_code = $this->session->userdata('result_examcode');
		$exam_p = $this->session->userdata('result_period');
		
		 if($this->db->table_exists('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'')){ 
		
		$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
		$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
		
		if(is_numeric($member_id)){
			$this->db->where('mem_no',$member_id,false);
		}else{
			$this->db->where('mem_no',$member_id,'',false);
		}
		
		$this->db->where(array("exam_code"=>$exam_code));
		$record = $this->db->get();
		$user_info = $record->row();
		if(count($user_info) > 0)
		{
			$exam_period=$user_info->exam_period;
		}
		if(isset($user_info)){
			$exam_hold_on = $user_info->exam_hold_on;
			$is_conso = $user_info->mem_no;
		}else{
			$exam_hold_on = '';
			$is_conso = '';
		}
		$this->db->select('description');
		$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
		$exam_result = $exam->row();
		$exam_name = $exam_result->description;
		
		$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
		$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
		
		if(is_numeric($member_id)){
			$this->db->where('mem_no',$member_id,false);
		}else{
			$this->db->where('mem_no',$member_id,'',false);
		}
		
		$this->db->where(array("exam_code"=>$exam_code));
		$sql = $this->db->get();
		$result = $sql->result();
		
		foreach($result as $dres){
			if($dres->exam_result_date == ''){
				if($user_info->exam_period == 116){
					$datearr[] = "24-Aug-2016";	
				}
				if($user_info->exam_period == 215){
					$datearr[] = "10-feb-2016";	
				}	
			}else{
				$datearr[] = $dres->exam_result_date;
			}
		}
		
		
		$this->db->select('result_date,exam_conduct');
		$this->db->from('result_exam');
		$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
		$result_date = $this->db->get();
		$result_date_info = $result_date->row();
		$exam_result_date =  $result_date_info->result_date;
		$exam_conduct = $result_date_info->exam_conduct;

		$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
		
		 }else{ 
			 $is_conso = '';
			 $data = array("is_conso"=>$is_conso);
		 }
		
		$this->load->view('dipcert_consolidated',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	// Result for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 218
	public function jaiibdbf218(){
		try{
			
			$flag=0;
			$data=array();
			$data['error']='';
			
		    if(isset($_POST['submit'])){ 
			
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),
							);
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				
				
				if ($this->form_validation->run() == TRUE){
					
					$exam_code=$this->input->post('exam');
					$period=$this->master_model->getRecords('display_result_setting',array('exam_code'=>$exam_code),'period,type');
					$meminfo=$this->master_model->getRecords('member_registration',$dataarr);
					
					if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){ 
					if(count($meminfo) > 0){ 
						$exam_code=explode('_',$this->input->post('exam')); 
						$this->db->select('*');
						$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
						if(is_numeric($this->input->post('Username'))){
							$this->db->where('mem_no',$this->input->post('Username'),false);
						}else{
							$this->db->where('mem_no',$this->input->post('Username'),'',false);
						}
						$this->db->where(array('exam_code' => $exam_code[0]));
						$record = $this->db->get();
						$user_info = $record->row();
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1],
												 'result_fucn' => 'jaiib218'
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');	
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
					}
					
					
					if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){
						if(count($meminfo) > 0){ 
						$exam_code=explode('_',$this->input->post('exam')); 
						$this->db->select('*');
						$this->db->from('memdtl_'.$this->input->post('exam').'');
						$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')),'',false);
						$record = $this->db->get();
						$user_info1 = $record->row();
						if(count($user_info1) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info1->member_number,
												 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1],
												  'result_fucn' => 'jaiib218'
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');
						}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}
					
					$data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			
			
			}
			$exarr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'));
			$arr = array('218');	
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			$this->db->where_in('display_result_setting.exam_code',$exarr);
			$this->db->where_in('display_result_setting.period ',$arr);
			//$this->db->where('type','CONS_MRK');
			//$this->db->group_by('display_result_setting.exam_code');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('jaiibdblogin218',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function jaiibdashboard218(){
		$this->load->view('jaiibresultdashboard218');	
	}
	public function jaiibconsolidatedresult218(){
		try{
			
			$datearr=array();$exam_period='';
			if($this->session->userdata('result_mem_no')=='')
			{ 
			
				redirect(base_url().'marksheet/jaiibdbf218');
			}
			
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			 if($this->db->table_exists('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'')){ 
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			
			$this->db->where(array("exam_code"=>$exam_code));
			$record = $this->db->get();
			$user_info = $record->row();
			if(count($user_info) > 0)
			{
				$exam_period=$user_info->exam_period;
			}
			
			
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_conso = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_conso = '';
			}
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			$exam_name = $exam_result->description;
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			
			$this->db->where(array("exam_code"=>$exam_code));
			$sql = $this->db->get();
			$result = $sql->result();
			
			foreach($result as $dres){
				if($dres->exam_result_date == ''){
					if($user_info->exam_period == 116){
						$datearr[] = "24-Aug-2016";	
					}
					if($user_info->exam_period == 215){
						$datearr[] = "10-feb-2016";	
					}	
				}else{
					$datearr[] = $dres->exam_result_date;
				}
			}
			
			
			$this->db->select('result_date,exam_conduct');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			//$this->db->where(array("exam_code"=>$exam_code));
			//$this->db->order_by('exam_id','ASC');
			//$this->db->limit(1);
			$result_date = $this->db->get();
			//echo $this->db->last_query();exit;
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			$exam_conduct = $result_date_info->exam_conduct;
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('jaiibconsolidatedresult218',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function jaiibadviceresult218(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'marksheet/jaiibdbf218');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('memdtl_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			//$this->db->where(array('member_number' => $member_id,"exam_code"=>$exam_code),'',false);
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row();
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "marks_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			//$subjtable = "subject_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('jaiibadviceresult218',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}

	// common function for JAIIB result
	public function jaiibresult($exam_period=''){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			/* $exam_period = base64_decode($this->uri->segment(3)); */
			
		    if(isset($_POST['submit'])){ 
			
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),
							);
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				
				
				if ($this->form_validation->run() == TRUE){
					
					$exam_code=$this->input->post('exam');
					$meminfo=$this->master_model->getRecords('member_registration',$dataarr);
					
					
					if(count($meminfo) > 0){ 
						$exam_code=$this->input->post('exam'); 
						$this->db->select('*');
						$this->db->from('jaiib_cons_mrk');
						if(is_numeric($this->input->post('Username'))){
							$this->db->where('mem_no',$this->input->post('Username'),false);
						}else{
							$this->db->where('mem_no',$this->input->post('Username'),'',false);
						}
						$this->db->where('period' , $exam_period);
						$this->db->where('exam_code' , $exam_code);
						$record = $this->db->get();
						$user_info = $record->row();
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code,
												 'result_period'=>$exam_period,
												 'result_fucn' => 'jaiib'
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');	
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
					
					if(count($meminfo) > 0){ 
						$exam_code=$this->input->post('exam'); 
						$this->db->select('*');
						$this->db->from('jaiib_member');
						$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username'),'exam_period'=>$exam_period),'',false);
						$record = $this->db->get();
						$user_info1 = $record->row();
						if(count($user_info1) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info1->member_number,
											 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
											 'result_examcode'=>$exam_code,
											 'result_period'=>$exam_period,
											  'result_fucn' => 'jaiib'
										   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
					$data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}

			$this->load->view('jaiibresultlogin',$data);
		}catch(Exception $e){
			echo "Message : ". $e->getMessage();
		}
	}

	public function jaiibdashboard(){
		$this->load->view('jaiibdashboard');
	}

	public function jaiibconsolidate(){
		try{
			$datearr=array();
			$exam_period='';
			if($this->session->userdata('result_mem_no')==''){ 
				redirect(base_url().'marksheet/jaiibresult');
			}
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('jaiib_cons_mrk');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			$this->db->where(array("period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$record = $this->db->get();
			$user_info = $record->row();
			if(count($user_info) > 0){
				$exam_period=$user_info->exam_period;
			}
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_conso = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_conso = '';
			}
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			$exam_name = $exam_result->description;
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('jaiib_cons_mrk');
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			$this->db->where(array("period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$sql = $this->db->get();
			$result = $sql->result();

			if(sizeof($result)!=0){
				$is_conso = 'yes';
			}else{
				$is_conso = '';
			}
			
			foreach($result as $dres){
				$datearr[] = $dres->exam_result_date;
			}
			
			
			$this->db->select('result_date,exam_conduct');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			$exam_conduct = $result_date_info->exam_conduct;
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			$this->load->view('jaiibconsolidated',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}

	public function jaiibadvice(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'marksheet/jaiibresult');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_period = $this->session->userdata('result_period');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('jaiib_member');
			$this->db->where('exam_period',$exam_period);
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row();
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$exam_period),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = 'jaiib_marks';
			
			//$subjtable = "subject_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('jaiib_marks.exam_period',$exam_period);
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct,'exam_period' => $exam_period);
			$this->load->view('jaiibadvice',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}
	}

	
	//Result for CAIIB 218
	public function caiib218(){
		$flag=0;
		$data=array();
		$data['error']='';
		
		 if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),
							);
			
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				if ($this->form_validation->run() == TRUE){
					$meminfo=$this->master_model->getRecords('member_registration',$dataarr);
					//echo $this->db->last_query();
					 if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){
						 
						 if(count($meminfo) > 0){    
						 
							$this->db->select('*');
							$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info = $record->row();
							if(count($user_info) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info->mem_no,
													 'result_name'=>$user_info->mem_name,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn'=>'caiib218'
												   );
								$this->session->set_userdata($result__data);
								//redirect(base_url().'result/caiib118_result_dashboard');	
								redirect(base_url().'result/result_feedback');
						 }
						 }else{
							$data['error']='<span style="">Invalid credential.</span>'; 
						 }
					}
					
					 if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){ 
					 	if(count($meminfo) > 0){   
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1],
													 'result_fucn'=>'caiib218'
												   );
								$this->session->set_userdata($result__data);
								//redirect(base_url().'result/caiib118_result_dashboard');
								redirect(base_url().'result/result_feedback');
						}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					 }
					 
					 //exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array($this->config->item('examCodeCaiib'),'62',$this->config->item('examCodeCaiibElective63'),'64','65','66','67',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'72');
		$arr = array('218');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('caiib218',$data);
	}
	public function caiib218_result_dashboard(){
		$this->load->view('caiibdashboard218');	
	}
	public function caiib218_result_consolidated(){
		try{
			
			$datearr=array();$exam_period='';
			if($this->session->userdata('result_mem_no')=='')
			{ 
			
				redirect(base_url().'result');
			}
			
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			 if($this->db->table_exists('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'')){ 
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			
			$this->db->where(array("exam_code"=>$exam_code));
			$record = $this->db->get();
			$user_info = $record->row();
			if(count($user_info) > 0)
			{
				$exam_period=$user_info->exam_period;
			}
			
			
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_conso = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_conso = '';
			}
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			$exam_name = $exam_result->description;
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			
			$this->db->where(array("exam_code"=>$exam_code));
			$sql = $this->db->get();
			$result = $sql->result();
			
			foreach($result as $dres){
				if($dres->exam_result_date == ''){
					if($user_info->exam_period == 116){
						$datearr[] = "24-Aug-2016";	
					}
					if($user_info->exam_period == 215){
						$datearr[] = "10-feb-2016";	
					}	
				}else{
					$datearr[] = $dres->exam_result_date;
				}
			}
			
			
			$this->db->select('result_date,exam_conduct');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			$exam_conduct = $result_date_info->exam_conduct;
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('caiibconsolidatedresult218',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function caiib218_result_advice(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/advice');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('memdtl_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			//$this->db->where(array('member_number' => $member_id,"exam_code"=>$exam_code),'',false);
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row();
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "marks_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			//$subjtable = "subject_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('caiibadviceresult218',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	// common function for BCBF result
	public function bcbf($exam_period=''){
		try{
			
			/* $exam_period = base64_decode($this->uri->segment(3)); */
			$exam_code = $this->input->post('exam');
			
			$data=array();
			$data['error']='';
			
			if(isset($_POST['submit'])){ 
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
				);
				if ($this->form_validation->run() == TRUE){
					
					
					 if($this->db->table_exists('bcbf_member')){ 
						   $this->db->select('*');
							$this->db->from('bcbf_member');
							$this->db->where(array('exam_code' => $exam_code,'exam_period'=>$exam_period,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>$exam_period,
													 'result_fucn'=>'bcbf'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'marksheet/result_feedback');
						} 
					 } 
					 $data['error']='<span style="">Invalid credential123.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf_result_login',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	public function bcbf_result_dashboard(){
		$this->load->view('bcbf_res_dashboard');	
	}
	public function bcbf_advice_result(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'marksheet/bcbf');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_period = $this->session->userdata('result_period');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('bcbf_member');
			$this->db->where(array('member_number' => $member_id,"exam_period"=>$exam_period),'',false);
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			$record = $this->db->get();
			$user_info = $record->row();
			
			
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "bcbf_marks";
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false); 
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('bcbf_marks.exam_period',$exam_period);
			$this->db->where('result_subject.exam_code',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('bcbf_result',$data); 
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function result_feedback(){
		$this->load->view('result_feedback'); 		
	}
	
	 // common function for JAIIB result
	public function caiibresult($exam_period=''){
		try{
			$flag=0;
			$data=array();
			$data['error']='';
			/* $exam_period = base64_decode($this->uri->segment(3)); */
			
		    if(isset($_POST['submit'])){ 
			
				$config = array(
								array(
										'field' => 'exam',
										'label' => 'Exam',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Username',
										'label' => 'Registration/Membership No.',
										'rules' => 'trim|required'
									),
									array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),
							);
				$this->form_validation->set_rules($config);
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$encpass = $aes->encrypt($this->input->post('Password'));
				
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'usrpassword'=>$encpass,
				);
				
				
				if ($this->form_validation->run() == TRUE){
					
					$exam_code=$this->input->post('exam');
					$meminfo=$this->master_model->getRecords('member_registration',$dataarr);
					
					if(count($meminfo) > 0){ 
						$exam_code=$this->input->post('exam'); 
						$this->db->select('*');
						$this->db->from('caiib_cons_mrk');
						if(is_numeric($this->input->post('Username'))){
							$this->db->where('mem_no',$this->input->post('Username'),false);
						}else{
							$this->db->where('mem_no',$this->input->post('Username'),'',false);
						}
						$this->db->where('period' , $exam_period);
						$this->db->where('exam_code' , $exam_code);
						$record = $this->db->get();
						$user_info = $record->row();
						
						//print_r($user_info); die;
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code,
												 'result_period'=>$exam_period,
												 'result_fucn' => 'caiib'
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');	
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
					
					if(count($meminfo) > 0){ 
						$exam_code=$this->input->post('exam'); 
						$this->db->select('*');
						$this->db->from('caiib_member');
						$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username'),'exam_period'=>$exam_period),'',false);
						$record = $this->db->get();
						$user_info1 = $record->row();
						//echo '>>'. $this->db->last_query(); die;
						if(count($user_info1) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info1->member_number,
											 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
											 'result_examcode'=>$exam_code,
											 'result_period'=>$exam_period,
											  'result_fucn' => 'caiib'
										   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'marksheet/result_feedback');
						}
					}else{
						$data['error']='<span style="">Invalid credential123.</span>';
					}
					$data['error']='<span style="">Invalid credential3434.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}

			$this->load->view('caiibresultlogin',$data);
		}catch(Exception $e){
			echo "Message : ". $e->getMessage();
		}
	}

	public function caiibdashboard(){
		$this->load->view('caiibdashboard');
	}

	public function caiibconsolidate(){
		try{
			$datearr=array();
			$exam_period='';
			if($this->session->userdata('result_mem_no')==''){ 
				redirect(base_url().'marksheet/caiibresult');
			}
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('caiib_cons_mrk');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			$this->db->where(array("period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$record = $this->db->get();
			$user_info = $record->row();
			if(count($user_info) > 0){
				$exam_period=$user_info->exam_period;
			}
			
			if(isset($user_info)){
				$exam_hold_on = $user_info->exam_hold_on;
				$is_conso = $user_info->mem_no;
			}else{
				$exam_hold_on = '';
				$is_conso = '';
			}
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			$exam_name = $exam_result->description;
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('caiib_cons_mrk');
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			$this->db->where(array("period"=>$exam_p));
			$this->db->where(array("exam_code"=>$exam_code));
			$sql = $this->db->get();
			$result = $sql->result();

			if(sizeof($result)!=0){
				$is_conso = 'yes';
			}else{
				$is_conso = '';
			}
			
			foreach($result as $dres){
				$datearr[] = $dres->exam_result_date;
			}
			
			
			$this->db->select('result_date,exam_conduct');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			$exam_conduct = $result_date_info->exam_conduct;
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			$this->load->view('caiibconsolidated',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}

	public function caiibadvice(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'marksheet/caiibresult');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_period = $this->session->userdata('result_period');
			
			$this->db->select('member_number, firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
			$this->db->from('caiib_member');
			$this->db->where('exam_period',$exam_period);
			if(is_numeric($member_id)){
				$this->db->where('member_number' , $member_id, false);
			}else{
				$this->db->where('member_number' , $member_id,'', false);
			}
			
			
			$this->db->where('exam_code' , $exam_code);
			$record = $this->db->get();
			$user_info = $record->row();
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$exam_period),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = 'caiib_marks';
			
			//$subjtable = "subject_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->join('result_subject', $marktable.'.subject_id = result_subject.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('caiib_marks.exam_period',$exam_period);
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct,'exam_period' => $exam_period);
			$this->load->view('caiibadvice',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}
	}

	
}
