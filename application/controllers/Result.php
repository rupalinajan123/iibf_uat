<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Result extends CI_Controller {

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
	
	//Results for BC/BF Examination *
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
					
					
					 if($this->db->table_exists('memdtl_101_537')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_537');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>537
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfdashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf537',$data);
			
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
									array(
										'field' => 'code',
										'label' => 'code',
										'rules' => 'trim|required|callback_check_captcha_userreg'
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
							redirect(base_url().'result/dashboard');	
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
							redirect(base_url().'result/dashboard');
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
			
			##code added by chaitali on 2021-10-27
			$this->load->model('Captcha_model');		 
			$captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
			
			$data['image']=$captcha_image;
			
			$this->load->view('consolidatedlogin',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		/* $this->load->helper('captcha');
		$this->session->unset_userdata("regcaptcha");
		$this->session->set_userdata("regcaptcha", rand(1, 100000));
		$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data = $cap['image'];
		$_SESSION["regcaptcha"] = $cap['word'];
		echo $data; */		$this->load->model('Captcha_model');		echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
	}
	//call back for check captcha server side
	public function check_captcha_userreg($code) 
	{
		if(isset($_SESSION["regcaptcha"]))
		{
			if($code == '' || $_SESSION["regcaptcha"] != $code )
			{
				$this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.'); 
				//$this->session->set_userdata("regcaptcha", rand(1,100000));
				return false;
			}
			if($_SESSION["regcaptcha"] == $code)
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	//Results for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 116*
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
							redirect(base_url().'result/dashboard');	
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
							redirect(base_url().'result/dashboard');
						}
					}
					
					$data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$exarr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'),$this->config->item('examCodeCaiib'),'62',$this->config->item('examCodeCaiibElective63'),'64','65','66','67',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'),'72');
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
	
	//Results for Diploma/Certificate Examination July 2016*
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
								redirect(base_url().'result/dashboard');	
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
								redirect(base_url().'result/dashboard');
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
	
	//Results for AML/KYC Examination OCT 2016
	public function amlkyc(){
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
								redirect(base_url().'result/dashboard');	
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
								redirect(base_url().'result/dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58');
		$arr = array('316');	
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
		$this->load->view('octlogin',$data);
	}
	
	//Results for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 216
	public function jaiibdbf(){
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard');	
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard');
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
			$arr = array('216');	
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
			$this->load->view('jaiibdblogin',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	//Results for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 217
	public function jaiibdbf217(){
		try{
			
			$flag=0;
			$data=array();
			$data['error']='';
			
		    if(isset($_POST['submit'])){ 
			if($this->input->post('Username') == 510339620){
				$data['error']='<span style="">Invalid credential.</span>';
			}else{
			
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard217');	
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard217');
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
			
			}
			$exarr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'));
			$arr = array('217');	
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
			$this->load->view('jaiibdblogin217',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	// Result for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 118
	public function jaiibdbf118(){
		try{
			
			$flag=0;
			$data=array();
			$data['error']='';
			
			$block_mem = array(510172014,510136697,510157396,510312186,510326804,510337653,510232231,510221352,500206806,510326754,500172904,510204141,510188035,510346922,510369824,500116235,500120524,510096915,510030477,510362012,510379944,510377715,500139312,500189059,500207837,510054498,510204832,510214026,510228438,510264830,510285199,510289402,510310890,510324593,510337784,510340678);
			
		    if(isset($_POST['submit'])){ 
			if(in_array($this->input->post('Username'),$block_mem)){
				$data['error']='<span style="">Invalid credential.</span>';
			}else{
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard118');	
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdashboard118');
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
			
			}
			
			$exarr = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeDBF'));
			$arr = array('118');	
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
			$this->load->view('jaiibdblogin118',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	//Results for JAIIB and DIPLOMA IN BANKING & FINANCE Examination 117
	public function jaiibdbfresult(){
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdb_result_dashboard');	
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
												 'result_period'=>$exam_code[1]
											   );
							$this->session->set_userdata($result__data);
							redirect(base_url().'result/jaiibdb_result_dashboard');
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
			$arr = array('117');	
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
			$this->load->view('jaiibdb_result_login',$data);
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	// Results for AML/KYC Examination feb 2016 701 period
	public function amlkycfeb(){
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
								redirect(base_url().'result/dashboard');	
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
								redirect(base_url().'result/dashboardamlkycfeb');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','53');
		$arr = array('701');	
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
		$this->load->view('amlkycfeb',$data);
	}
	
	//Result For CAIIB_Result data for Feb/Mar 2017
	public function caiib(){
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiibdashboard');	
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiibdashboard');
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
		$arr = array('216');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('caiiblogin',$data);
	}
	
	// Results for AML/KYC Examination feb 2017 702 period
	public function amlkycrone(){
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
								redirect(base_url().'result/amlkycronedashboard');	
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
								redirect(base_url().'result/amlkycronedashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','53');
		$arr = array('702');	
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
		$this->load->view('amlkycmarone',$data);
	}
	
	// Results for AML/KYC Examination feb 2017 703 period
	public function amlkycrtwo(){
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
								redirect(base_url().'result/amlkycrtwodashboard');	
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
								redirect(base_url().'result/amlkycrtwodashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','53');
		$arr = array('703');	
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
		$this->load->view('amlkycmartwo',$data);
	}
	
	// Results for AML/KYC Examination 2017 704 period
	public function amlkycmay4(){
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
								redirect(base_url().'result/amlkycrtwodashboard');	
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
								redirect(base_url().'result/amlkycmay4dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','53');
		$arr = array('703');	
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
		$this->load->view('amlkycmay4',$data);
	}
	
	// Results for AML/KYC Examination 2017 705 period
	public function amlkycmay5(){
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
								redirect(base_url().'result/amlkycrtwodashboard');	
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
								redirect(base_url().'result/amlkycmay5dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','53');
		$arr = array('703');	
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
		$this->load->view('amlkycmay5',$data);
	}
	
	//Results for Diploma/Certificate Examination May 2017
	public function diplomacertification(){
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
								redirect(base_url().'result/dipcert_dashboard');	
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
								redirect(base_url().'result/dipcert_dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('11','32','33','47','52','151','153','156','158','8','18','19','20','34','51','58','59','74','78','79','81','165','148','149','160','161','162','175','177','135');
		$arr = array('117');	
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
		$this->load->view('dipcert117',$data);
	}
	
	//Result for pdc exam (706)
	public function pdcexam(){
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
								redirect(base_url().'result/pdcdashboard');	
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
								redirect(base_url().'result/pdcdashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('706');	
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
		$this->load->view('pdclogin',$data);
	}
	
	//Result for bcbf exam 538
	public function bcbfjune(){
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
					
					
					 if($this->db->table_exists('memdtl_101_538')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_538');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>538
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfjunedashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbflogin',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Result for pdc exam (707)
	public function pdcexamresult(){
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
								redirect(base_url().'result/pdcresultdashboard');	
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
								redirect(base_url().'result/pdcresultdashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('707');	
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
		$this->load->view('pdclogin707',$data);
	}
	
	//Result for pdc exam (708)
	public function pdc(){
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
								redirect(base_url().'result/p_dashboard');	
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
								redirect(base_url().'result/p_dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('708');	
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
		$this->load->view('p_login',$data);
	}
	
	//Result for pdc exam (709)
	public function pdc709(){
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
								redirect(base_url().'result/pdc_dashboard');	
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
								redirect(base_url().'result/pdc_dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('709');	
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
		$this->load->view('p_login709',$data);
	}
	
	//Result for bcbf exam 539
	public function bcbfresult(){
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
					
					
					 if($this->db->table_exists('memdtl_101_539')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_539');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>539
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfjulydashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf539',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function aml217(){
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
								redirect(base_url().'result/pdc_dashboard217');	
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
								redirect(base_url().'result/pdc_dashboard217');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','81');
		$arr = array('217');	
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
		$this->load->view('p_login217',$data);
	}
	
	
	//Result for pdc exam (710)
	public function pdc710(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard710');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('710');	
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
		$this->load->view('p_login710',$data);
	}
	
	//Result for pdc exam (711)
	public function pdc711(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard711');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('711');	
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
		$this->load->view('p_login711',$data);
	}
	
	//Result for pdc exam (712)
	public function pdc712(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard712');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('712');	
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
		$this->load->view('p_login712',$data);
	}
	
	//Result for pdc exam (713)
	public function pdc713(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard713');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('713');	
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
		$this->load->view('p_login713',$data);
	}
	
	//Result for pdc exam (714)
	public function pdc714(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard714');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('714');	
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
		$this->load->view('p_login714',$data);
	}
	
	//Result for pdc exam (715)
	public function pdc715(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard715');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('715');	
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
		$this->load->view('p_login715',$data);
	}
	
	//Result for oct exam (317)
	public function oct(){
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
								redirect(base_url().'result/oct_dashboard');	
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
								redirect(base_url().'result/oct_dashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','34','58','59','81');
		$arr = array('317','217');	
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
		$this->load->view('oct_login',$data);
	}
	
	//Result for pdc exam (716)
	public function pdc716(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard716');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('716');	
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
		$this->load->view('p_login716',$data);
	}
	
	//Result for pdc exam (717)
	public function pdc717(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard717');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('717');	
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
		$this->load->view('p_login717',$data);
	}
	
	//Result for pdc exam (718)
	public function pdc718(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard718');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('718');	
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
		$this->load->view('p_login718',$data);
	}
	
	//Result for pdc exam (719)
	public function pdc719(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard719');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160');
		$arr = array('719');	
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
		$this->load->view('p_login719',$data);
	}
	
	//Result for pdc exam (720)
	public function pdc720(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard720');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('720');	
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
		$this->load->view('p_login720',$data);
	}
	
	//Result for pdc exam (721)
	public function pdc721(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard721');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('721');	
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
		$this->load->view('p_login721',$data);
	}
	
	//Result for pdc exam (722)
	public function pdc722(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard722');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('722');	
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
		$this->load->view('p_login722',$data);
	}
	
	//Result for pdc exam (723)
	public function pdc723(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard723');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('723');	
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
		$this->load->view('p_login723',$data);
	}
	
	//Result for pdc exam (724)
	public function pdc724(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard724');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('724');	
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
		$this->load->view('p_login724',$data);
	}
	
	//Result for pdc exam (725)
	public function pdc725(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard725');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('725');	
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
		$this->load->view('p_login725',$data);
	}
	
	
	//Result for pdc exam (726)
	public function pdc726(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard726');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('726');	
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
		$this->load->view('p_login726',$data);
	}
	//Result for pdc exam (727)
	public function pdc727(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard727');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('727');	
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
		$this->load->view('p_login727',$data);
	}
	
	
	//Result for pdc exam (728)
	public function pdc728(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard728');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('728');	
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
		$this->load->view('p_login728',$data);
	}
	
	//Result for pdc exam (729)
	public function pdc729(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard729');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('729');	
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
		$this->load->view('p_login729',$data);
	}
	
	
	//Result for pdc exam (730)
	public function pdc730(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard730');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('730');	
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
		$this->load->view('p_login730',$data);
	}
	
	public function pdc731(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard731');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('731');	
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
		$this->load->view('p_login731',$data);  
	}
	
	public function pdc732(){
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
					
					//echo ">>". $this->input->post('exam');
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
													 'result_fucn' => 'pdc732'
												   );
								$this->session->set_userdata($result__data);
								//redirect(base_url().'result/pdc_dashboard731');
								redirect(base_url().'result/result_feedback');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('732');	
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
		$this->load->view('p_login732',$data);  
	}
	public function pdc733(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'pdc733'
												   );
								$this->session->set_userdata($result__data);
								//redirect(base_url().'result/pdc_dashboard731');
								redirect(base_url().'result/result_feedback');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('34','58','160','177');
		$arr = array('733');	
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
		$this->load->view('p_login733',$data);  
	}
	
	//Result for 817 period
	public function result817(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/dashboard817');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','34','58','59','81');
		$arr = array('817');	
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
		$this->load->view('p_login817',$data);
	}
	//Result for 917 period[Tejasvi]
	public function result917(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/dashboard917');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','34','58','59','81');
		$arr = array('917');	
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
		$this->load->view('p_login917',$data);
	}
	
	//Result for 801 period[Tejasvi]
	public function result801(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/dashboard801');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','34','58','59','74','81');
		$arr = array('801');	
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
		$this->load->view('p_login801',$data);
	}
	//Result amlkyc617
	public function amlkyc617(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/pdc_dashboard617');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','34','58');
		$arr = array('617');	
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
		$this->load->view('p_login617',$data);
	}
	
	public function exam417(){
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
								redirect(base_url().'result/pdc_dashboard710');	
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
								redirect(base_url().'result/exam_dashboard417');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('20','58','160',175);
		$arr = array('417');	
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
		$this->load->view('p_login417',$data);
	}
	
	
	public function certification(){
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
								redirect(base_url().'result/dashboard417');	
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
								redirect(base_url().'result/dashboard417');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('8','11','18','19','24','25','26','32','33','34','47','51','52','59','74','78','79','81','135','148','149','151','153','156','158','161','162','177');
		$arr = array('417');	
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
		$this->load->view('login417',$data);
	}
	
	public function dipcert802(){
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
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert802'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');	
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
													 'result_period'=>$exam_code[1],
													 'result_fucn' => 'dipcert802'
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/result_feedback');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array(8, 11, 18, 19, 20, 24, 25, 26, 32, 33, 34, 58, 59, 74, 78, 79, 81, 135, 148, 149, 151, 153, 156, 158, 161, 162, 163, 164, 175, 177);
		$arr = array('802');	
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
		$this->load->view('login802',$data);
	}
	
	//Result For CAIIB_Result data for July 2017
	public function caiibresult(){
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiib_result_dashboard');	
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiib_result_dashboard');
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
		$arr = array('117');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('caiib117',$data);
	}
	
	//Result for CAIIB 217
	public function caiib217(){
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiib217_result_dashboard');	
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
													 'result_period'=>$exam_code[1]
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/caiib217_result_dashboard');
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
		$arr = array('217');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('caiib217',$data);
	}
	
	//Result for CAIIB 118
	public function caiib118(){
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
													 'result_fucn'=>'caiib118'
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
													 'result_fucn'=>'caiib118'
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
		$arr = array('118');	
		$this->db->distinct('exam_master.exam_code');
		$this->db->select('exam_master.description,exam_master.exam_code,period');
		$this->db->join('exam_master','exam_master.exam_code=display_result_setting.exam_code');
		$this->db->where_in('display_result_setting.exam_code ',$exarr);
		$this->db->where_in('display_result_setting.period ',$arr);
		$this->db->from('display_result_setting');
		$exam = $this->db->get();
		$exam_result = $exam->result();
		
		$data['exam']=$exam_result;
		$this->load->view('caiib118',$data);
	}
	
	//Result for bcbf exam 540
	public function bcbfresultsep(){
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
					
					
					 if($this->db->table_exists('memdtl_101_540')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_540');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>540
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfsepdashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf540',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Result for bcbf exam 541
	public function bcbfresultnov(){
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
					
					
					 if($this->db->table_exists('memdtl_101_541')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_541');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>541
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfnovdashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf541',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Result for bcbf exam 548
	public function bcbfresultdec(){
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
					
					
					 if($this->db->table_exists('memdtl_101_548')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_548');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>548,
													 'result_fucn'=>'bcbfresultdec'
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
	
	
	//Result for bcbf exam 542
	public function bcbfresultjan(){
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
					
					
					 if($this->db->table_exists('memdtl_101_542')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_542');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>542
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfjandashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf542',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	//Result for bcbf exam 543
	public function bcbfresultmar(){
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
					
					
					 if($this->db->table_exists('memdtl_101_543')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_543');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>543
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfmardashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf543',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	//Result for bcbf exam 544
	public function bcbfresultmay(){
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
					
					
					 if($this->db->table_exists('memdtl_101_544')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_544');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>544
												   );
								$this->session->set_userdata($result__data);
								redirect(base_url().'result/bcbfmaydashboard');
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
			$this->db->from('display_result_setting');
			$exam = $this->db->get();
			$exam_result = $exam->result();
			$data['exam']=$exam_result;
			$this->load->view('bcbf544',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	//Result for bcbf exam 545
	public function bcbfresultjuly(){
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
					
					
					 if($this->db->table_exists('memdtl_101_545')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_545');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>545,
													 'result_fucn'=>'bcbfresultjuly'
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
	
	//Result for bcbf exam 546
	public function bcbfresultsept(){
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
					
					
					 if($this->db->table_exists('memdtl_101_545')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_546');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>546,
													 'result_fucn'=>'bcbfresultsept'
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
	
	//Result for bcbf exam 547
	public function bcbfresultnovember(){
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
					
					
					 if($this->db->table_exists('memdtl_101_547')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_547');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>547,
													 'result_fucn'=>'bcbfresultnov'
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
	
	//Result for bcbf exam 549
	public function bcbfresult549(){
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
					
					
					 if($this->db->table_exists('memdtl_101_549')){ 
						   $this->db->select('*');
							$this->db->from('memdtl_'.$this->input->post('exam').'_549');
							$exam_code=$this->input->post('exam');
							$this->db->where(array('exam_code' => $exam_code,"member_number"=>$this->input->post('Username')));
							$record = $this->db->get();
							$user_info1 = $record->row();
							
							if(count($user_info1) > 0){ 
								$mysqltime=date("H:i:s");
								$result__data=array('result_mem_no'=>$user_info1->member_number,
													 'result_name'=>$user_info1->firstname." ".$user_info1->middlename." ".$user_info1->lastname,
													 'result_examcode'=>$exam_code,
													 'result_period'=>549,
													 'result_fucn'=>'bcbfresult549'
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
	
	
	public function consolidatedresult(){
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
			
			
			$this->db->select('result_date');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
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
	
	public function adviceresult(){
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
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code),'description,result_date,exam_conduct');
			
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
			$this->load->view('adviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function adviceresultamlkycfeb(){
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
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code),'description,result_date,exam_conduct');
			
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
			$this->load->view('adviceresultamlkycfeb',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibconsolidatedresult(){
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
			
			$this->load->view('jaiibconsolidatedresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibconsolidatedresult217(){
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
			
			$this->load->view('jaiibconsolidatedresult217',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibconsolidatedresult118(){
		try{
			
			$datearr=array();$exam_period='';
			if($this->session->userdata('result_mem_no')=='')
			{ 
			
				redirect(base_url().'result/jaiibdbf118');
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
			
			$this->load->view('jaiibconsolidatedresult118',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibdb_consolidatedresult(){
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
			$user_info = $record->row(); // get user info
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
			$exam_name = $exam_result->description; // get exam name
			
			$this->db->select('subject_name, marks, result_flag, exam_hold_on, exam_result_date,exam_period');
			$this->db->from('CONS_MRK_'.$this->session->userdata('result_examcode').'_'.$this->session->userdata('result_period').'');
			
			if(is_numeric($member_id)){
				$this->db->where('mem_no',$member_id,false);
			}else{
				$this->db->where('mem_no',$member_id,'',false);
			}
			
			$this->db->where(array("exam_code"=>$exam_code));
			$sql = $this->db->get();
			$result = $sql->result(); // get mark and subject detail
			
			foreach($result as $dres){
				if($dres->exam_result_date == ''){
					$datearr[] = "17-July-2017";	
				}else{
					//$datearr[] = $dres->exam_result_date;
					$datearr[] = "17-July-2017";
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
			$exam_conduct = $result_date_info->exam_conduct; // get exam conduct date
	
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p,'exam_conduct'=>$exam_conduct);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('jaiibdb_consolidatedresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibadviceresult(){
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
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('jaiibadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibadviceresult217(){
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
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('jaiibadviceresult217',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibadviceresult118(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/jaiibdbf118');
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
			$this->load->view('jaiibadviceresult118',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function jaiibdb_adviceresult(){
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
			$this->db->where('exam_id',$exam_code);
			$this->db->where('result_subject.exam_period',$this->session->userdata('result_period'));
			
			$record=$this->master_model->getRecords($marktable,'','marks,status,subject_name');
			//echo $this->db->last_query();
			//exit;
			
			
			$data = array("user_info"=>$user_info, "exam_name" => $exam_name,"result_date"=>$result_date,"record"=>$record,"printrecord"=>$record,"exam_code"=>$exam_code,"exam_conduct"=>$exam_conduct);
			$this->load->view('jaiibdb_adviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiibconsolidatedresult(){
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
			
			$this->load->view('caiibconsolidatedresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiibadviceresult(){
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
			$this->load->view('caiibadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbfadviceresult(){
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
			$this->load->view('bcbfadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbfjuneadviceresult(){
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
			$this->load->view('bcbfjuneadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbfjulyadviceresult(){
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
			$this->load->view('bcbfjulyadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function bcbfsepadviceresult(){
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
			$this->load->view('bcbfsepadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function bcbfnovadviceresult(){
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
			$this->load->view('bcbfnovadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbfjanadviceresult(){
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
			$this->load->view('bcbfjanadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbfmaradviceresult(){
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
			$this->load->view('bcbfmaradviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function bcbfmayadviceresult(){
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
			$this->load->view('bcbfmayadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbf_advice_result(){
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
			$this->load->view('bcbf_advice_result',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbf_advicesept_result(){
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
			$this->load->view('bcbf_advicesept_result',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbf_advicenov_result(){
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
			$this->load->view('bcbf_advicenov_result',$data); 
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbf_advicedec_result(){
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
			$this->load->view('bcbf_advicedec_result',$data); 
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function bcbf_advice549_result(){
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
			$this->load->view('bcbf_advice549_result',$data); 
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function amlkycroneadviceresult(){
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
			$this->load->view('amlkycroneadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function amlkycrtwoadviceresult(){
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
			$this->load->view('amlkycrtwoadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function amlkycmay4adviceresult(){
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
			$this->load->view('amlkycmay4adviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function amlkycmay5adviceresult(){
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
			$this->load->view('amlkycmay5adviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function dipcertconsolidatedresult(){
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
			$this->db->where(array('mem_no' => $member_id,"exam_code"=>$exam_code),'',false);
			$sql = $this->db->get();
			$result = $sql->result();
		
			
			foreach($result as $dres){
				if($dres->exam_result_date == ''){
					$datearr[] = "13-June-2017";
				}else{
					$datearr[] = $dres->exam_result_date;
				}
			}
			
			
			$this->db->select('result_date');
			$this->db->from('result_exam');
			$this->db->where(array("exam_code"=>$exam_code,"exam_period"=>$exam_p));
			$result_date = $this->db->get();
			$result_date_info = $result_date->row();
			$exam_result_date =  $result_date_info->result_date;
		
			$data = array("user_info"=>$user_info,"exam_name" => $exam_name,"result"=>$result,"exam_hold_on"=>$exam_hold_on,"printresult"=>$result,"exam_result_date"=>$exam_result_date,"is_conso"=>$is_conso,"exam_code"=>$exam_code,"datearr"=>$datearr,"exam_period"=>$exam_period,"exam_p"=>$exam_p);
			
			 }else{ 
				 $is_conso = '';
				 $data = array("is_conso"=>$is_conso);
			 }
			
			$this->load->view('dipcertconsolidatedresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function dipcertadviceresult(){
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
			
			if($this->session->userdata('result_period') == ''){
				$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
				$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code),'description,result_date,exam_conduct');
			}else{
				$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
				$this->db->where('exam_period' , $this->session->userdata('result_period'));
				$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code),'description,result_date,exam_conduct');
			}
			
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
			$this->load->view('dipcertadviceresult',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdcadviceresult(){
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
			$this->load->view('pdcadviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdcresultadviceresult(){
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
			$this->load->view('pdcadviceresult707',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function p_adviceresult(){
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
			$this->load->view('p_adviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult(){
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
			$this->load->view('p_adviceresult709',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult217(){
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
			$this->load->view('p_adviceresult217',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult710(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult710',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function pdc_adviceresult711(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult711',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult712(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult712',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function pdc_adviceresult713(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult713',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult714(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult714',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult715(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult715',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult716(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult716',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult717(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult717',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult718(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult718',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult719(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult719',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult720(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult720',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult721(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult721',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult722(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult722',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult723(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult723',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult724(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult724',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult725(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult725',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function pdc_adviceresult726(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult726',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function pdc_adviceresult727(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult727',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function pdc_adviceresult728(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult728',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function pdc_adviceresult729(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult729',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult730(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult730',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function pdc_adviceresult731(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult731',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult732(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult732',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function pdc_adviceresult733(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult733',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function adviceresult817(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult817',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	//917 period [Tejasvi]
	public function adviceresult917(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult917',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	//801 period [Tejasvi]
	public function adviceresult801(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult801',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	public function pdc_adviceresult617(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('p_adviceresult617',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function exam_adviceresult417(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			
			//$exam_name = $result[0]['description']; // get exam name
			$exam_name = $result[0]['exam_name_full']; // get exam name
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
			$this->load->view('p_adviceresult417',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function adviceresult417(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			
			//$exam_name = $result[0]['description']; // get exam name
			$exam_name = $result[0]['exam_name_full']; // get exam name
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
			$this->load->view('adviceresult417',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function adviceresult802(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct,exam_name_full');
			
			//$exam_name = $result[0]['description']; // get exam name
			$exam_name = $result[0]['exam_name_full']; // get exam name
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
			$this->load->view('adviceresult802',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function oct_adviceresult(){
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
			$user_info = $record->row(); // get all member detail
			
			$this->db->join('result_exam', 'result_exam'.'.exam_code = exam_master.exam_code');
			$result=$this->master_model->getRecords('exam_master',array('exam_master.exam_code'=>$exam_code,'exam_period'=>$this->session->userdata('result_period')),'description,result_date,exam_conduct');
			
			$exam_name = $result[0]['description']; // get exam name
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
			$this->load->view('oct_adviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function pdc_adviceresultpdf(){
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
			
			$html=$this->load->view('p_adviceresult709pdf', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = $exam_code."_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$path = $pdf->Output($pdfFilePath, "D");
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function caiib_result_consolidated(){
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
			
			$this->load->view('caiibconsolidatedresult117',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiib217_result_consolidated(){
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
			
			$this->load->view('caiibconsolidatedresult217',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiib118_result_consolidated(){
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
			
			$this->load->view('caiibconsolidatedresult118',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function consolidated417(){
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
			
			$this->load->view('consolidated417',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function consolidated802(){
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
			
			$this->load->view('consolidated802',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiib_result_advice(){
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
			$this->load->view('caiibadviceresult117',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiib217_result_advice(){
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
			$this->load->view('caiibadviceresult217',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function caiib118_result_advice(){
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
			$this->load->view('caiibadviceresult118',$data);
			
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function dashboard(){
		$this->load->view('resultdashboardnew');	
	}
	
	public function dashboardamlkycfeb(){
		$this->load->view('resultdashboardamlkycfeb');	
	}
	
	public function jaiibdashboard(){
		$this->load->view('jaiibresultdashboard');	
	}
	
	public function jaiibdashboard217(){
		$this->load->view('jaiibresultdashboard217');	
	}
	
	public function jaiibdashboard118(){
		$this->load->view('jaiibresultdashboard118');	
	}
	
	public function jaiibdb_result_dashboard(){
		$this->load->view('jaiibdb_result_dashboard');	
	}
	
	public function caiibdashboard(){
		$this->load->view('caiibresultdashboard');	
	}
	
	public function bcbfdashboard(){
		$this->load->view('bcbfdashboard');	
	}
	
	public function bcbfjunedashboard(){
		$this->load->view('bcbfjunedashboard');	
	}
	
	public function bcbfsepdashboard(){
		$this->load->view('bcbfsepdashboard');	
	}
	
	public function bcbfnovdashboard(){
		$this->load->view('bcbfnovdashboard');	
	}
	
	public function bcbfjandashboard(){
		$this->load->view('bcbfjandashboard');	
	}
	
	public function bcbfmardashboard(){
		$this->load->view('bcbfmardashboard');	
	}
	public function bcbfmaydashboard(){
		$this->load->view('bcbfmaydashboard');	
	}
	
	public function bcbf_result_dashboard(){
		$this->load->view('bcbf_result_dashboard');	
	}
	
	public function bcbf_resultsept_dashboard(){
		$this->load->view('bcbf_resultsept_dashboard');	
	}
	
	public function bcbf_resultnov_dashboard(){
		$this->load->view('bcbf_resultnov_dashboard');	
	}
	
	public function bcbf_resultdec_dashboard(){
		$this->load->view('bcbf_resultdec_dashboard');	
	}
	
	public function bcbf_result549_dashboard(){
		$this->load->view('bcbf_result549_dashboard');	
	}
	
	public function amlkycronedashboard(){
		$this->load->view('amlkycronedashboard');	
	}
	
	public function amlkycrtwodashboard(){
		$this->load->view('amlkycrtwodashboard');	
	}
	
	public function amlkycmay4dashboard(){
		$this->load->view('amlkycmay4dashboard');	
	}
	
	public function amlkycmay5dashboard(){
		$this->load->view('amlkycmay5dashboard');	
	}
	
	public function dipcert_dashboard(){
		$this->load->view('dipcert_dashboard');	
	}
	
	public function pdcdashboard(){
		$this->load->view('pdcdashboard');	
	}
	
	public function pdcresultdashboard(){
		$this->load->view('pdcdashboard707');	
	}
	
	public function p_dashboard(){
		$this->load->view('p_dashboard');	
	}
	
	public function pdc_dashboard(){
		$this->load->view('p_dashboard709');	
	}
	
	public function pdc_dashboard217(){
		$this->load->view('p_dashboard217');	
	}
	
	public function pdc_dashboard710(){
		$this->load->view('p_dashboard710');	
	}
	
	public function pdc_dashboard711(){
		$this->load->view('p_dashboard711');	
	}
	
	public function pdc_dashboard712(){
		$this->load->view('p_dashboard712');	
	}
	
	public function pdc_dashboard713(){
		$this->load->view('p_dashboard713');	
	}
	
	public function pdc_dashboard714(){
		$this->load->view('p_dashboard714');	
	}
	
	public function pdc_dashboard715(){
		$this->load->view('p_dashboard715');	
	}
	
	public function pdc_dashboard716(){
		$this->load->view('p_dashboard716');	
	}
	
	public function pdc_dashboard717(){
		$this->load->view('p_dashboard717');	
	}
	
	public function pdc_dashboard718(){
		$this->load->view('p_dashboard718');	
	}
	
	public function pdc_dashboard719(){
		$this->load->view('p_dashboard719');	
	}
	
	public function pdc_dashboard720(){
		$this->load->view('p_dashboard720');	
	}
	
	public function pdc_dashboard721(){
		$this->load->view('p_dashboard721');	
	}
	
	public function pdc_dashboard722(){
		$this->load->view('p_dashboard722');	
	}
	
	public function pdc_dashboard723(){
		$this->load->view('p_dashboard723');	
	}
	
	public function pdc_dashboard724(){
		$this->load->view('p_dashboard724');	
	}
	
	public function pdc_dashboard725(){
		$this->load->view('p_dashboard725');	
	}
	
	public function pdc_dashboard726(){
		$this->load->view('p_dashboard726');	
	}
	
	public function pdc_dashboard728(){
		$this->load->view('p_dashboard728');	
	}
	
	public function pdc_dashboard727(){
		$this->load->view('p_dashboard727');	
	}
	
	public function pdc_dashboard729(){
		$this->load->view('p_dashboard729');	
	}
	
	public function pdc_dashboard730(){
		$this->load->view('p_dashboard730');	
	}
	
	public function pdc_dashboard731(){
		$this->load->view('p_dashboard731');	
	}
	
	public function pdc_dashboard732(){
		$this->load->view('p_dashboard732');	
	}
	
	public function pdc_dashboard733(){
		$this->load->view('p_dashboard733');	
	}
	
	public function dashboard817(){
		$this->load->view('p_dashboard817');	
	}
	
	public function dashboard917(){
		$this->load->view('p_dashboard917');	
	}
	
	public function pdc_dashboard617(){
		$this->load->view('p_dashboard617');	
	}
	
	public function exam_dashboard417(){
		$this->load->view('p_dashboard417');	
	}
	
	public function dashboard417(){
		$this->load->view('dashboard417');	
	}
	
	public function dashboard802(){
		$this->load->view('dashboard802');	
	}
	
	public function oct_dashboard(){
		$this->load->view('oct_dashboard');	
	}
	
	public function caiib_result_dashboard(){
		$this->load->view('caiibdashboard117');	
	}
	
	public function caiib217_result_dashboard(){
		$this->load->view('caiibdashboard217');	
	}
	
	public function caiib118_result_dashboard(){
		$this->load->view('caiibdashboard118');	
	}
	
	public function dashboard801(){
		$this->load->view('p_dashboard801');	
	}
	
	public function IT(){
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
								redirect(base_url().'Result/ITdashboard');	
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
								redirect(base_url().'Result/ITdashboard');
						}
					 }
					 //echo $this->db->last_query();
					 //echo "####";exit;
					 $data['error']='<span style="">Invalid credential.</span>';
				} else{
					$data['validation_errors'] = validation_errors();
				}
			}
		$exarr = array('24','25','26');
		$arr = array('117');	
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
		$this->load->view('IT',$data);
	}
	
	public function ITdashboard(){
		$this->load->view('ITdashboard');	
	}

	public function ITadviceresult(){
		try{
			
			if($this->session->userdata('result_mem_no')=='')
			{
				redirect(base_url().'result/IT');
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
			$this->load->view('ITadviceresult',$data);
			 
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	
	public function logout(){
		try{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
				$this->session->unset_userdata($key);    
			}
			redirect(base_url().'result/index/');	
		}catch(Exception $e){
			throw $e->getMessage();
		}	
	}
	
	public function result_feedback(){
		$this->load->view('result_feedback');		
	}
	
	 
	
}
