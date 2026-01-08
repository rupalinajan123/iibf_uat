<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Examresult_test extends CI_Controller {

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
	
	//Results for BC/BF Examination 21st JAN 2017*
	public function bcbf(){
		try{
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
					
					
					 if($this->db->table_exists('memdtl_101_536')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_536');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>536
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');
						}
					 }
					 
					 if($this->db->table_exists('memdtl_101_535')){
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_535');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>535
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');
						}
					 }
					 
					 if($this->db->table_exists('memdtl_101_534')){  
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_534');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>534
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');
						}
					 }
					
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			//$this->db->where('type','CONS_MRK');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Results for Diploma/Certificate Examination Jan 2017*
	public function index(){
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
					$exam_code=explode('_',$this->input->post('exam'));
					if($exam_code[0]=='34' ||$exam_code[0]=='58')
					{ 
							//$exam_period='416';
							//$_POST['exam'] =$exam_code[0].'_'.$exam_period;
							$exam_period=array('416');
							foreach($exam_period as $exprrow)
							{
								 $_POST['exam'] =$exam_code[0].'_'.$exprrow;
								 if($this->db->table_exists('memdtl_'.$this->input->post('exam').''))
								 {  
									   $this->db->select('*');
										$this->db->from('memdtl_'.$this->input->post('exam').'');
										$exam_code=explode('_',$this->input->post('exam'));
										$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
										$record = $this->db->get();
										$user_info1 = $record->row();
										if(count($user_info1) > 0)
										{ 
											break;	
										}
									 }
									 
								 if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').''))
								 {  
								   $this->db->select('*');
									$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
									$exam_code=explode('_',$this->input->post('exam'));
									$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')));
									$record = $this->db->get();
									$user_info1 = $record->row();
									if(count($user_info1) > 0)
									{ 
										break;	
									}
								 }
							}
					}
					else
					{
							
							//$exam_period='216';
							//$_POST['exam'] =$exam_code[0].'_'.$exam_period;
							$exam_period=array('216','116');
							foreach($exam_period as $exprrow)
							{
								
								 $_POST['exam'] =$exam_code[0].'_'.$exprrow;
								 if($this->db->table_exists('memdtl_'.$this->input->post('exam').''))
								 {   
									   $this->db->select('*');
										$this->db->from('memdtl_'.$this->input->post('exam').'');
										$exam_code=explode('_',$this->input->post('exam'));
										$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
										$record = $this->db->get();
										$user_info1 = $record->row();
										
										if(count($user_info1) > 0)
										{ 
											break;	
										}
									 }
								
									 if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').''))
									 {   
									   $this->db->select('*');
										$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
										$exam_code=explode('_',$this->input->post('exam'));
										$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')));
										$record = $this->db->get();
										$user_info1 = $record->row();
										if(count($user_info1) > 0)
										{ 
											break;	
										}
									 }
								}
							}
					
					if($exam_code[0] != '58'){
						$exam_period=array('216','116','416');
					}
					if($exam_code[0] == '58'){
						$exam_period=array('416');
					}
					foreach($exam_period as $exprrow)
					{
					
					$_POST['exam'] =$exam_code[0].'_'.$exprrow;	
					if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){ 
						
						$this->db->select('*');
						$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
						$exam_code=explode('_',$this->input->post('exam'));
						$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')),'',false);
						$record = $this->db->get();
						$user_info = $record->row();
						//echo $this->db->last_query();
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');	
					 }
				}
				
				
					if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){ 
						
					   $this->db->select('*');
						$this->db->from('memdtl_'.$this->input->post('exam').'');
						$exam_code=explode('_',$this->input->post('exam'));
						//$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')),'',false);
						$this->db->where('exam_code' , $exam_code[0]);
						if(is_numeric($this->input->post('Username'))){
							$this->db->where('member_number' , $this->input->post('Username'),false);
						}else{
							$this->db->where('member_number' , $this->input->post('Username'),'',false);
						}
						$record = $this->db->get();
						$user_info1 = $record->row();
						if(count($user_info1) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info1->member_number,
												 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');
					}
				 }
					}
					 
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$exarr = array('11','32','33','47','51','52','8','18','19','20','31','34','35','48','49','53','58','59','74','75','76','77','78','79','81','148','149');
			$arr = array('216');
			
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			$this->db->where_in('display_result_setting.exam_code',$exarr);
			$this->db->where_in('display_result_setting.period ',$arr);
			//$this->db->where('display_result_setting.exam_code !=','101');
			//$this->db->where('type','CONS_MRK');
			$this->db->group_by('display_result_setting.exam_code');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('consolidatedlogin',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Results for JAIIB and DIPLOMA IN BANKING & FINANCE Examination*
	public function jaiib(){
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
					$exam_code=$this->input->post('exam');
					$period=$this->master_model->getRecords('display_result_setting',array('exam_code'=>$exam_code),'period,type');
					
					
					if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){ 
						$exam_code=explode('_',$this->input->post('exam')); 
						$this->db->select('*');
						$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
						$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')),'',false);
						$record = $this->db->get();
						$user_info = $record->row();
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');	
						}
					}
					
					
					if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');
						}
					}
					
					$data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$exarr = array('21','42','60','62','63','64','65','66','67','68','69','70','71','72');
			$arr = array('116');	
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
			$this->load->view('jaiiblogin',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	//Results for Diploma/Certificate Examination July 2016
	public function diplomaresult(){
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
					
					 if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){  
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');	
						 }
					}
					
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('11','32','33','47','51','52','8','18','19','20','31','34','35','48','49','53','58','59','74','75','76','77','78','79','81');
		$arr = array('116');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		//$this->db->where('type','CONS_MRK');
		//$this->db->group_by('display_result_setting.exam_code');
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('julylogin',$data);
	}
	
	//RESULT DATA FOR CAIIB & CAIIB ELECTIVES Examination*
	public function caiib(){
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
					$exam_code=$this->input->post('exam');
					$period=$this->master_model->getRecords('display_result_setting',array('exam_code'=>$exam_code),'period,type');
					
					
					if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').'')){ 
						$exam_code=explode('_',$this->input->post('exam')); 
						$this->db->select('*');
						$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
						$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')),'',false);
						$record = $this->db->get();
						$user_info = $record->row();
						if(count($user_info) > 0){  
							$mysqltime=date("H:i:s");
							$result__data=array('result_mem_no'=>$user_info->mem_no,
												 'result_name'=>$user_info->mem_name,
												 'result_examcode'=>$exam_code[0],
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');	
						}
					}
					
					
					if($this->db->table_exists('memdtl_'.$this->input->post('exam').'')){
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'examresult/dashboard');
						}
					}
					
					$data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$exarr = array('60','62','63','64','65','66','67','68','69','70','71','72');
			$arr = array('116');
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			$this->db->where_in('display_result_setting.exam_code',$exarr);
			$this->db->where_in('display_result_setting.period ',$arr);
			//$this->db->where('display_result_setting.exam_code !=','101');
			$this->db->group_by('display_result_setting.exam_code');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			
			$data['exam']=$exam_result;
			$this->load->view('caiiblogin',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function consolidatedresult(){
		try{
			
			$datearr=array();$exam_period='';
			if($this->session->userdata('result_mem_no')=='')
			{ 
			
				redirect(base_url().'Examresult');
			}
			
			
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			$exam_p = $this->session->userdata('result_period');
			 if($this->db->table_exists('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'')){ 
			
			$this->db->select('mem_no, mem_name, add_1, add_2, add_3, add_4, add_5, add_6, pin_code, state, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			$this->db->where(array('mem_no' => $member_id,"exam_code"=>$exam_code),'',false);
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
			$this->db->where(array('mem_no' => $member_id,"exam_code"=>$exam_code),'',false);
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
			
			$examtable = "exam_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->select('result_date');
			$this->db->from($examtable);
			$this->db->where(array("exam_code"=>$exam_code));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
			
		
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('consolidatedresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function advice(){
		try{
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
					
					$exam_code=explode('_',$this->input->post('exam'));
					if($exam_code[0]=='34' ||$exam_code[0]=='58')
					{
							//$exam_period='416';
							//$_POST['exam'] =$exam_code[0].'_'.$exam_period;
							$exam_period=array('416');
							foreach($exam_period as $exprrow)
							{
								 $_POST['exam'] =$exam_code[0].'_'.$exprrow;
								 if($this->db->table_exists('memdtl_'.$this->input->post('exam').''))
								 {  
									   $this->db->select('*');
										$this->db->from('memdtl_'.$this->input->post('exam').'');
										$exam_code=explode('_',$this->input->post('exam'));
										$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
										$record = $this->db->get();
										echo $this->db->last_query();exit;
										$user_info1 = $record->row();
										if(count($user_info1) > 0)
										{ 
											break;	
										}
									 }
							}
					}
					else
					{
							//$exam_period='216';
							//$_POST['exam'] =$exam_code[0].'_'.$exam_period;
							$exam_period=array('216','116');
							foreach($exam_period as $exprrow)
							{
								 $_POST['exam'] =$exam_code[0].'_'.$exprrow;
								 if($this->db->table_exists('memdtl_'.$this->input->post('exam').''))
								 {  
									   $this->db->select('*');
										$this->db->from('memdtl_'.$this->input->post('exam').'');
										$exam_code=explode('_',$this->input->post('exam'));
										$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
										$record = $this->db->get();
										$user_info1 = $record->row();
										if(count($user_info1) > 0)
										{ 
											break;	
										}
									 }
							}
					}
						
					$this->db->select('*');
					$this->db->from('memdtl_'.$this->input->post('exam').'');
					$exam_code=explode('_',$this->input->post('exam'));
					$this->db->where(array('exam_code' => $exam_code[0],"member_number"=>$this->input->post('Username')));
					$record = $this->db->get();
					$user_info = $record->row();
					//$this->db->last_query();
					//exit;
					if(count($user_info) > 0){ 
						$mysqltime=date("H:i:s");
						$result__data=array('result_mem_no'=>$user_info->member_number,
											 'result_name'=>$user_info->firstname." ".$user_info->middlename." ".$user_info->lastname,
											 'result_examcode'=>$exam_code[0],
											 'result_period'=>$exam_code[1]
										   );
						$this->session->set_userdata($result__data);
						//redirect(base_url().'examresult/adviceresult');	
						redirect(base_url().'examresult/dashboard');
					}elseif(count($user_info) == 0){
						 if($this->db->table_exists('CONS_MRK_'.$this->input->post('exam').''))
						 {
							$this->db->select('*');
							$this->db->from('CONS_MRK_'.$this->input->post('exam').'');
							$exam_code=explode('_',$this->input->post('exam'));
							$this->db->where(array('exam_code' => $exam_code[0],"mem_no"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							if(count($user_info1) > 0){
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->mem_no,
													 'result_name'=>$user_info1->mem_name,
													 'result_examcode'=>$exam_code[0],
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'examresult/dashboard');
						}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}
					
					elseif(count($user_info) == 0 && count($user_info1) == 0){
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			
			$this->db->distinct('exam_master.exam_code');
			$this->db->select('exam_master.description,exam_master.exam_code,period');
			$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
			$this->db->where('display_result_setting.exam_code !=','101');
			$this->db->group_by('display_result_setting.exam_code');
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('resultadvicelogin',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function adviceresult(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'Examresult/advice');
			}
			$member_id = $this->session->userdata('result_mem_no');
			$exam_code = $this->session->userdata('result_examcode');
			
			$this->db->select('firstname, middlename, lastname, address1, address2, address3, address4, address5, address6, pincode,no_of_attempt');
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
			
			
			$examjointable = "exam_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			$this->db->join($examjointable, $examjointable.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description'];
			$result_date = $result[0]['result_date'];
			$exam_conduct = $result[0]['exam_conduct'];
			
			$marktable = "marks_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			$subjtable = "subject_".$this->session->userdata('result_examcode')."_".$this->session->userdata('result_period');
			
			$this->db->join($subjtable, $marktable.'.subject_id = '.$subjtable.'.subject_code');
			
			if(is_numeric($member_id)){
				$this->db->where('regnumber',$member_id,false);
			}else{
				$this->db->where('regnumber',$member_id,'',false);
			}
			$this->db->where('exam_id',$exam_code);
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('adviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function dashboard(){
		$this->load->view('resultdashboard');	
	}
	
	
	public function logout(){
		try{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
				$this->session->unset_userdata($key);    
			}
			redirect(base_url().'examresult/index/');	
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	 
	
}
