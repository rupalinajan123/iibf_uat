<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admitcard_test extends CI_Controller {

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
		
		 //load mPDF library
		 //$this->load->library('m_pdf');
		 $this->load->model('Master_model');
	} 
	
	// Common login function for BCBF admitcard link
	public function bcbf(){ 
		try{ 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
					
						$exmarr = array(
										'exm_cd'=> $this->input->post('examcode'),
										'mem_mem_no'=> $this->input->post('Username')
										
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
														'spregnumber'=>$user_info[0]['regnumber'],
														'spfirstname'=>$user_info[0]['firstname'],
														'spmiddlename'=>$user_info[0]['middlename'],
														'splastname'=>$user_info[0]['lastname'],
														'feedback_exam_name' => $feedback_exam_name
													);
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential..</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			
			$this->load->view('bcbfjuly',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function bcbf506(){ 
		try{ 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
					
						$exmarr = array(
										'exm_cd'=> 996,
										'mem_mem_no'=> $this->input->post('Username')
										
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
														'spregnumber'=>$user_info[0]['regnumber'],
														'spfirstname'=>$user_info[0]['firstname'],
														'spmiddlename'=>$user_info[0]['middlename'],
														'splastname'=>$user_info[0]['lastname'],
														'feedback_exam_name' => $feedback_exam_name
													);
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential..</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			
			$this->load->view('bcbfjuly',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function kotak_bcbf(){ 
		try{ 
			$data=array();
			$data['error']='';
			$feedback_exam_name = 'bcbf';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
					
						$exmarr = array(
										'exm_cd'=> $this->input->post('examcode'),
										'mem_mem_no'=> $this->input->post('Username'),
										'date' => '2019-02-25'
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr);
						if(count($chkexam) > 0){
							$mysqltime=date("H:i:s");
							$seprate_user_data=array('regid'=>$user_info[0]['regid'],
														'spregnumber'=>$user_info[0]['regnumber'],
														'spfirstname'=>$user_info[0]['firstname'],
														'spmiddlename'=>$user_info[0]['middlename'],
														'splastname'=>$user_info[0]['lastname'],
														'feedback_exam_name' => $feedback_exam_name
													);
							$this->session->set_userdata($seprate_user_data);
							redirect(base_url().'admitcard/feedback');
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential..</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			
			$this->load->view('bcbfjuly',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	// dashboard function for user portal
	public function getadmitdashboard(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
			}
			if($this->session->userdata('nmregnumber')!=''){
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
			}
			if($this->session->userdata('dbregnumber')!=''){
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
			}
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					$tbl = 'old';
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
				}
			}
			
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' ");
			$exm_arr_1 = $query_1->result();
			//echo $this->db->last_query();
			if(sizeof($exm_arr_1) > 0){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1 AND admit_card_details.admitcard_image != '' AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND exm_prd NOT IN(808,901,905,906,907,908,219,909,910)  AND exam_date != '2019-03-10' AND admit_card_details.exm_cd NOT IN (".$this->config->item('examCodeCaiib').",62,63,64,65,66,67,68,69,70,71,72) 
				GROUP BY admit_card_details.exm_cd,admit_card_details.exam_date
				ORDER BY admit_card_details.admitcard_id DESC 
				 ");
				 $result_new = $record->result();
				 $tbl_new = 'new';
				 //echo $this->db->last_query();
				 /*echo '<pre>';
				 print_r($result_new);*/
			}elseif(count($exm_arr) == 1){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id,admit_card_details.mem_exam_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1 AND admit_card_details.admitcard_image != '' AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND exm_prd NOT IN(808,901,905,906,907,908,219,909,910)  AND exam_date != '2019-03-10' AND admit_card_details.exm_cd NOT IN (".$this->config->item('examCodeCaiib').",62,63,64,65,66,67,68,69,70,71,72) 
				ORDER BY admit_card_details.admitcard_id DESC 
				LIMIT 1;
				");
				$result_new = $record->result();
				$tbl_new = 'new';
			}else{
				$tbl = 'none';
				$result = array();
			}
			//echo $tbl_new;
			//echo '<pre>';
			//print_r($result_new);
			$data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function getadmitdashboardtest(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
				$name      = $this->session->userdata('firstname')." ".$this->session->userdata('middlename')." ".$this->session->userdata('lastname');	
			}
			if($this->session->userdata('nmregnumber')!=''){
				$member_id = $this->session->userdata('nmregnumber');
				$name      = $this->session->userdata('nmfirstname')." ".$this->session->userdata('nmmiddlename')." ".$this->session->userdata('nmlastname');
			}
			if($this->session->userdata('dbregnumber')!=''){
				$member_id = $this->session->userdata('dbregnumber');
				$name      = $this->session->userdata('dbfirstname')." ".$this->session->userdata('dbmiddlename')." ".$this->session->userdata('dblastname');
			}
			
			if(!isset($member_id)){
				redirect(base_url());
			}
			
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			$result_new = array();
			$result = array();
			$tbl_new = '';
			$tbl = '';
			
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					$tbl = 'old';
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					$tbl = 'old';
				}
			}
			
			$query_1 = $this->db->query("select exm_cd from admit_card_details where mem_mem_no = '".$member_id."' ");
			$exm_arr_1 = $query_1->result();
			//echo $this->db->last_query();
			if(sizeof($exm_arr_1) > 0){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1 AND admit_card_details.admitcard_image != '' AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND exm_prd NOT IN(808,901,905,906,907,908,219,909,910)  AND exam_date != '2019-03-10' AND admit_card_details.exm_cd NOT IN (".$this->config->item('examCodeCaiib').",62,63,64,65,66,67,68,69,70,71,72) 
				GROUP BY admit_card_details.exm_cd,admit_card_details.exam_date
				ORDER BY admit_card_details.admitcard_id DESC 
				 ");
				 $result_new = $record->result();
				 $tbl_new = 'new';
				// echo $this->db->last_query();
				 /*echo '<pre>';
				 print_r($result_new);*/
			}elseif(count($exm_arr) == 1){ 
				$record=$this->db->query("SELECT admit_card_details.exm_cd, admit_card_details.m_1, admit_card_details.sub_cd, admit_exam_master.description, admit_card_details.mode, admit_card_details.exam_date as exam_date, admit_card_details.admitcard_id
				FROM admit_exam_master
				JOIN admit_card_details ON admit_card_details.exm_cd = admit_exam_master.exam_code
				WHERE admit_card_details.mem_mem_no = '".$member_id."' AND admit_card_details.remark = 1 AND admit_card_details.admitcard_image != '' AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND exm_prd NOT IN(808,901,905,906,907,908,219,909,910)  AND exam_date != '2019-03-10' AND admit_card_details.exm_cd NOT IN (".$this->config->item('examCodeCaiib')."0,62,63,64,65,66,67,68,69,70,71,72) 
				ORDER BY admit_card_details.admitcard_id DESC 
				LIMIT 1;
				");
				$result_new = $record->result();
				$tbl_new = 'new';
				//echo $this->db->last_query();
			}else{
				$tbl = 'none';
				$result = array();
			}
			//echo $tbl_new;
			//echo '<pre>';
			//print_r($result_new);
			$data = array("exam_name"=>$result,"exam_name_new"=>$result_new,'name'=>$name,'frm'=>'dwn','mid'=>$member_id,'tbl'=>$tbl,'tbl_new'=>$tbl_new);
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	// function run for old table for exam code 101
	public function getadmitcardsp(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3));
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($exam_code != 101){
				if($result->vendor_code == 3){
					$vcenter = '3';
				}elseif($result->vendor_code == 1){
					$vcenter = '1';
				}
			}elseif($exam_code == 101){
				$this->db->select('center_code'); 
				$this->db->from('sify_center');
				$scenter = $this->db->get();
				$sifyresult = $scenter->result();
				foreach($sifyresult as $sifyresult){
					$sifycenter[] = $sifyresult->center_code;
				}
				
				$this->db->select('center_code'); 
				$this->db->from('nseit_center');
				$ncenter = $this->db->get();
				$nseitresult = $ncenter->result();
				foreach($nseitresult as $nseitresult){
					$nseitcenter[] = $nseitresult->center_code;
				}
				
				if(in_array($result->center_code, $nseitcenter)){
					$vcenter = '3';
				}
				if(in_array($result->center_code, $sifycenter)){
					$vcenter = '1';
				}
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row(); 
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardsp', $data);
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	// function run for old table for exam code 101
	public function getadmitcardpdfsp(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($exam_code != 101){
				if($result->vendor_code == 3){
					$vcenter = '3';
				}elseif($result->vendor_code == 1){
					$vcenter = '1';
				}
			}elseif($exam_code == 101){
				$this->db->select('center_code'); 
				$this->db->from('sify_center');
				$scenter = $this->db->get();
				$sifyresult = $scenter->result();
				foreach($sifyresult as $sifyresult){
					$sifycenter[] = $sifyresult->center_code;
				}
				
				$this->db->select('center_code'); 
				$this->db->from('nseit_center');
				$ncenter = $this->db->get();
				$nseitresult = $ncenter->result();
				foreach($nseitresult as $nseitresult){
					$nseitcenter[] = $nseitresult->center_code;
				}
				
				if(in_array($result->center_code, $nseitcenter)){
					$vcenter = '3';
				}
				if(in_array($result->center_code, $sifycenter)){
					$vcenter = '1';
				}
			}
			
			$medium_code = $result->m_1; 
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdf', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	public function check_captcha_userlogin($code){
		try{
			if(!isset($this->session->useradmitcardlogincaptcha) && empty($this->session->useradmitcardlogincaptcha)){
				redirect(base_url().'index/');
			}
			if($code == '' || $this->session->useradmitcardlogincaptcha != $code ){
				$this->form_validation->set_message('check_captcha_userlogin', 'Invalid %s.'); 
				$this->session->set_userdata("userlogincaptcha", rand(1,100000));
				return false;
			}
			if($this->session->useradmitcardlogincaptcha == $code){
				$this->session->set_userdata('useradmitcardlogincaptcha','');
				$this->session->unset_userdata("useradmitcardlogincaptcha");
				return true;
			}
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();	
		}
	}
	public function generatecaptchaajax(){
		try{
			$this->load->helper('captcha');
			$this->session->unset_userdata("useradmitcardlogincaptcha");
			$this->session->set_userdata("useradmitcardlogincaptcha", rand(1, 100000));
			$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION["useradmitcardlogincaptcha"] = $cap['word'];
			echo $data;
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();	
		}
	}
	public function Logout(){
		try{
			$sessionData = $this->session->all_userdata();
			foreach($sessionData as $key =>$val){
				$this->session->unset_userdata($key);    
			}
			redirect('http://iibf.org.in/');
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	
	// function run for old table  from website link without exam codde 101
	public function getadmitcardjd(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			//echo $this->db->last_query();
			//exit;
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardjd', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	// function run for old table  from website link without exam codde 101
	public function getadmitcardpdfjd(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info');
			$this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info');
			$this->db->where(array('admitcard_info.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info ON admit_subject_master.subject_code = LEFT(admitcard_info.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	
	// function run for new table  from user portal
	public function getadmitcardsp_new(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3));
			$mem_exam_id = $this->uri->segment(4);
			
			$this->db->select('admit_card_details.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admit_card_details');
			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}
			if($result->vendor_code != 3){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admit_card_details');
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			$this->db->group_by('venueid');
			$this->db->order_by("exam_date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND admit_card_details.mem_exam_id = ".$mem_exam_id."   group by admit_card_details.exam_date ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = date('d-M-y',strtotime($pdate->exam_date));
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->exam_date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." ".$examdate[0];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate,'exam_period'=>$result->exm_prd,'mem_exam_id'=>$mem_exam_id);
			
			if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004  || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014){
				$this->load->view('rel_jdadmitcardsp', $data);
			}else{
				$this->load->view('jdadmitcardsp', $data);
			}
			
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	// function run for new table  from user portal
	public function getadmitcardpdfsp_new(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$mem_exam_id = $this->uri->segment(4);
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admit_card_details.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admit_card_details');
			$this->db->join('member_registration', 'admit_card_details.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}
			if($result->vendor_code != 3){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admit_card_details');
			$this->db->where(array('admit_card_details.mem_mem_no' => $member_id,'exm_cd'=>$exam_code,'remark'=>1,'exam_date >= '=> date("Y-m-d"),'mem_exam_id'=>$mem_exam_id));
			$this->db->group_by('venueid');
			$this->db->order_by("exam_date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,admit_card_details.exam_date,time,venueid,seat_identification FROM admit_subject_master JOIN admit_card_details ON admit_subject_master.subject_code = LEFT(admit_card_details.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admit_card_details.mem_mem_no = '".$member_id."' AND subject_delete = '0' and remark = 1 AND admit_card_details.exam_date >= '".date("Y-m-d")."' AND admit_card_details.mem_exam_id = ".$mem_exam_id."   group by admit_card_details.exam_date  ORDER BY STR_TO_DATE(admit_card_details.exam_date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				date('d-M-y',strtotime($pdate->exam_date));
				$exdate = date('d-M-y',strtotime($pdate->exam_date));
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->exam_date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." ".$examdate[0];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'exam_period'=>$result->exm_prd);
			
			if($exam_code == 1002 || $exam_code == 1003 || $exam_code == 1004  || $exam_code == 1005 || $exam_code == 1006 || $exam_code == 1007 || $exam_code == 1008 || $exam_code == 1009 || $exam_code == 1010 || $exam_code == 1011 || $exam_code == 1012 || $exam_code == 1013 || $exam_code == 1014){
				$html=$this->load->view('rel_jdadmitcardpdf', $data, true);
			}else{
				$html=$this->load->view('jdadmitcardpdf', $data, true);
			}
			
			
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = $exam_code."_".$result->exm_prd."_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");   
			
		}catch(Exception $e){
			echo $e->getMessage(); 
		}
	}
	
	
	public function caiib219(){
		try{
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiib219';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
									/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
									),*/
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function caiib219dashboard(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/caiib119/'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function caiib_rescheduled(){
		try{
			$data=array();
			$data['error']='';
			
			$feedback_type = 'new';
			$feedback_exam_name = 'caiibres';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
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
					//'usrpassword'=>$encpass,
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_caiib_rescheduled',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential.</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential.</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			$this->load->view('loginjd_caiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function caiibdashboardres(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/caiib_rescheduled/'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_caiib_rescheduled where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_caiib_rescheduled.exm_cd, admitcard_caiib_rescheduled.m_1, admitcard_caiib_rescheduled.sub_cd, admit_exam_master.description, admitcard_caiib_rescheduled.mode, admitcard_caiib_rescheduled.date as exam_date, admitcard_caiib_rescheduled.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_caiib_rescheduled ON admitcard_caiib_rescheduled.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_caiib_rescheduled.exm_cd
					ORDER BY admitcard_caiib_rescheduled.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_caiib_rescheduled.exm_cd, admitcard_caiib_rescheduled.m_1, admitcard_caiib_rescheduled.sub_cd, admit_exam_master.description, admitcard_caiib_rescheduled.mode, admitcard_caiib_rescheduled.date as exam_date, admitcard_caiib_rescheduled.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_caiib_rescheduled ON admitcard_caiib_rescheduled.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."'
					ORDER BY admitcard_caiib_rescheduled.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardcdres',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function getadmitcardcdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_caiib_rescheduled.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->join('member_registration', 'admitcard_caiib_rescheduled.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_caiib_rescheduled ON admit_subject_master.subject_code = LEFT(admitcard_caiib_rescheduled.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardcdres', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	public function getadmitcardpdfcdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_caiib_rescheduled.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->join('member_registration', 'admitcard_caiib_rescheduled.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_caiib_rescheduled');
			$this->db->where(array('admitcard_caiib_rescheduled.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_caiib_rescheduled ON admit_subject_master.subject_code = LEFT(admitcard_caiib_rescheduled.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_caiib_rescheduled.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	public function jaiib(){ 
	try{
		$data=array();
		$data['error']='';
		
		if(isset($_POST['submit'])){
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Username',
									'rules' => 'trim|required'
								),
								/*array(
										'field' => 'Password',
										'label' => 'Password',
										'rules' => 'trim|required',
								),*/
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userlogin',
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
				'isactive'=>'1',
				//'usrpassword'=>$encpass
			);
			if ($this->form_validation->run() == TRUE){
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info) > 0){ 
					$mysqltime=date("H:i:s");
					$seprate_user_data=array('regid'=>$user_info[0]['regid'],
												'spregnumber'=>$user_info[0]['regnumber'],
												'spfirstname'=>$user_info[0]['firstname'],
												'spmiddlename'=>$user_info[0]['middlename'],
												'splastname'=>$user_info[0]['lastname'],
												'feedback_exam_name' => 'jaiib'
											);
					$this->session->set_userdata($seprate_user_data);
					redirect(base_url().'admitcard/feedback');	
				}else{
					$data['error']='<span style="">Invalid credential.</span>';
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
		}
		$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '/uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
		//admitcardlogin
		$this->load->view('loginjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function jaiib_reschedule(){ 
	try{
		$data=array();
		$data['error']='';
		
		if(isset($_POST['submit'])){
			$config = array(
							array(
									'field' => 'Username',
									'label' => 'Username',
									'rules' => 'trim|required'
								),
							array(
									'field' => 'code',
									'label' => 'Code',
									'rules' => 'trim|required|callback_check_captcha_userlogin',
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
				'isactive'=>'1'
				//'usrpassword'=>$encpass
			);
			
			if ($this->form_validation->run() == TRUE){
				$user_info=$this->master_model->getRecords('member_registration',$dataarr);
				if(count($user_info) > 0){ 
					$mysqltime=date("H:i:s");
					$seprate_user_data=array('regid'=>$user_info[0]['regid'],
												'spregnumber'=>$user_info[0]['regnumber'],
												'spfirstname'=>$user_info[0]['firstname'],
												'spmiddlename'=>$user_info[0]['middlename'],
												'splastname'=>$user_info[0]['lastname'],
												'feedback_exam_name' => 'jaiibres'
											);
					$this->session->set_userdata($seprate_user_data);
					redirect(base_url().'admitcard/feedback');	
				}else{
					$data['error']='<span style="">Invalid credential.</span>';
				}
			}else{
				$data['validation_errors'] = validation_errors();
			}
			
		}
		$this->load->helper('captcha');
		$vals = array(
						'img_path' => './uploads/applications/',
						'img_url' => '/uploads/applications/',
					);
		$cap = create_captcha($vals);
		$data['image'] = $cap['image'];
		$data['code']=$cap['word'];
		$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
		//admitcardlogin
		$this->load->view('loginjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function jaiibdashboard(){
		try{
			$result = array();
			$tbl = '';
			
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/jaiib/'));
			}
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
			
			if(sizeof($exm_arr) > 0 && count($exm_arr) > 1){
				$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
FROM admit_exam_master
JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info.mem_mem_no = '".$member_id."' 
GROUP BY admitcard_info.exm_cd
ORDER BY admitcard_info.admitcard_id DESC 
");
				$result = $record->result();
			}elseif(count($exm_arr) == 1){
				$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
FROM admit_exam_master
JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info.mem_mem_no = '".$member_id."'
ORDER BY admitcard_info.admitcard_id DESC 
LIMIT 1;
");
				$result = $record->result();
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
		}
	}
	
	public function jaiibdashboardres(){
		try{ 
			$result = array();
			$tbl = '';
			
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/jaiib/'));
			}
			$query = $this->db->query("select exm_cd from admitcard_info_190 where mem_mem_no = '".$member_id."' ");
			
			$exm_arr = $query->result();
			
			if(sizeof($exm_arr) > 0 && count($exm_arr) > 1){
				$record=$this->db->query("SELECT admitcard_info_190.exm_cd, admitcard_info_190.m_1, admitcard_info_190.sub_cd, admit_exam_master.description, admitcard_info_190.mode, admitcard_info_190.date as exam_date, admitcard_info_190.admitcard_id
FROM admit_exam_master
JOIN admitcard_info_190 ON admitcard_info_190.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info_190.mem_mem_no = '".$member_id."' 
GROUP BY admitcard_info_190.exm_cd
ORDER BY admitcard_info_190.admitcard_id DESC 
");
				$result = $record->result();
			}elseif(count($exm_arr) == 1){
				$record=$this->db->query("SELECT admitcard_info_190.exm_cd, admitcard_info_190.m_1, admitcard_info_190.sub_cd, admit_exam_master.description, admitcard_info_190.mode, admitcard_info_190.date as exam_date, admitcard_info_190.admitcard_id
FROM admit_exam_master
JOIN admitcard_info_190 ON admitcard_info_190.exm_cd = admit_exam_master.exam_code
WHERE admitcard_info_190.mem_mem_no = '".$member_id."'
ORDER BY admitcard_info_190.admitcard_id DESC 
LIMIT 1;
");
				$result = $record->result();
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjdres',$data);
			
		}catch(Exception $e){
		}
	}
	// function run for old table  from website link without exam codde 101
	public function getadmitcardjdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			if($this->session->userdata('spregnumber') == '' && $this->session->userdata('regnumber') == '' && $this->session->userdata('nmregnumber') == '' && $this->session->userdata('dbregnumber') == '' ){
				redirect(base_url());	
			}
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3)); 
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row(); 
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info_190 ON admit_subject_master.subject_code = LEFT(admitcard_info_190.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn','mid'=>$member_id,'idate'=>$exdate);
			
			$this->load->view('admitcardjdres', $data);
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	// function run for old table  from website link without exam codde 101
	public function getadmitcardpdfjdres(){
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
			}
			if($this->session->userdata('regnumber') != ''){
				$member_id = $this->session->userdata('regnumber');
			}
			if($this->session->userdata('nmregnumber') != ''){
				$member_id = $this->session->userdata('nmregnumber');
			}
			if($this->session->userdata('dbregnumber') != ''){
				$member_id = $this->session->userdata('dbregnumber');
			}
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			if($result->vendor_code == 3){
				$vcenter = '3';
			}elseif($result->vendor_code == 1){
				$vcenter = '1';
			}
			$medium_code = $result->m_1;
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			$this->db->select('description');
			$exam = $this->db->get_where('admit_exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM admit_subject_master JOIN admitcard_info_190 ON admit_subject_master.subject_code = LEFT(admitcard_info_190.sub_cd,3) WHERE admit_subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			$subject_result = $subject->result();
			$pdate = $subject->result();
			
			foreach($pdate as $pdate){
				$exdate = $pdate->date;
				$examdate = explode("-",$exdate);
				$examdatearr[] = $examdate[1];
			}
			
			$exdate = $subject_result[0]->date;
			$examdate = explode("-",$exdate);
			$printdate = implode("/",array_unique($examdatearr))." 20".$examdate[2];
			
			if($medium_code == 'ENGLISH' || $medium_code == 'E'){
				$medium_code_lng = 'E';
			}elseif($medium_code == 'HINDI' || $medium_code == 'H'){
				$medium_code_lng = 'H';
			}elseif($medium_code == 'MALAYALAM' || $medium_code == 'A'){
				$medium_code_lng = 'A';
			}elseif($medium_code == 'GUJRATHI' || $medium_code == 'G' || $medium_code == 'GUJARATI'){
				$medium_code_lng = 'G';
			}elseif($medium_code == 'KANADDA' || $medium_code == 'K' || $medium_code == 'KANNADA'){
				$medium_code_lng = 'K';
			}elseif($medium_code == 'TELEGU' || $medium_code == 'L' || $medium_code == 'TELUGU' ){
				$medium_code_lng = 'L';
			}elseif($medium_code == 'MARATHI' || $medium_code == 'M'){
				$medium_code_lng = 'M';
			}elseif($medium_code == 'BENGALI' || $medium_code == 'N'){
				$medium_code_lng = 'N';
			}elseif($medium_code == 'ORIYA' || $medium_code == 'O'){
				$medium_code_lng = 'O';
			}elseif($medium_code == 'ASSAMESE' || $medium_code == 'S'){
				$medium_code_lng = 'S';
			}elseif($medium_code == 'TAMIL' || $medium_code == 'T'){
				$medium_code_lng = 'T';
			}
			
			$this->db->select('medium_description');
			$medium = $this->db->get_where('medium_master', array('medium_code' => $medium_code_lng));
			$medium_result = $medium->row();
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results,'idate'=>$exdate,'mid'=>$member_id,'idate'=>$exdate);
			
			$html=$this->load->view('admitcardpdfjd', $data, true);
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			$pdf->WriteHTML($html);
			$pdf->Output($pdfFilePath, "D");  
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function dipcert808(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert808';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function dipcertdashboard808(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code

					WHERE admitcard_info.mem_mem_no = '".$member_id."'
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function dipcert901(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert901';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function dipcertdashboard901(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date = '27-Apr-19' 
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  AND date = '27-Apr-19' 
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
				}
			}else{
				$result = array();
			}
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	
	
	public function dipcert(){
		try{
			$data=array();
			$data['error']='';
			$feedback_type = 'new';
			$feedback_exam_name = 'dipcert';
			
		    if(isset($_POST['submit'])){
				$config = array(
								array(
										'field' => 'Username',
										'label' => 'Username',
										'rules' => 'trim|required'
									),
								array(
										'field' => 'code',
										'label' => 'Code',
										'rules' => 'trim|required|callback_check_captcha_userlogin',
									),
							);
			
				$this->form_validation->set_rules($config);
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					'isactive'=>'1'
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$exmarr = array(
										'mem_mem_no'=> $this->input->post('Username')
										);
						$this->db->order_by('admitcard_id','desc');
						$chkexam = $this->master_model->getRecords('admitcard_info',$exmarr,'exm_cd');
						//echo $this->db->last_query();
						//exit;
						if(count($chkexam) > 0){
							$ex_arr = explode(",",$this->input->post('examcode'));
							if(in_array($chkexam[0]['exm_cd'],$ex_arr)){
								$mysqltime=date("H:i:s");
								$seprate_user_data=array('regid'=>$user_info[0]['regid'],
															'spregnumber'=>$user_info[0]['regnumber'],
															'spfirstname'=>$user_info[0]['firstname'],
															'spmiddlename'=>$user_info[0]['middlename'],
															'splastname'=>$user_info[0]['lastname'],
															'feedback_type' => $feedback_type,
															'feedback_exam_name' => $feedback_exam_name
														);
								$this->session->set_userdata($seprate_user_data);
								redirect(base_url().'admitcard/feedback');
							}else{
								$data['error']='<span style="">Invalid credential.</span>';
							}
						}else{
							$data['error']='<span style="">Invalid credential..</span>';
						}
					}else{
						$data['error']='<span style="">Invalid credential...</span>';
					}
				}else{
					$data['validation_errors'] = validation_errors();
				}
			}
			$this->load->helper('captcha');
			$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '/uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data['image'] = $cap['image'];
			$data['code']=$cap['word'];
			$this->session->set_userdata('useradmitcardlogincaptcha', $cap['word']);
			$this->load->view('loginjd_808',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	public function dipcertdashboard(){
		$result = array();
		$tbl = '';
		try{
			if($this->session->userdata('spregnumber') != ''){
				$member_id = $this->session->userdata('spregnumber');
				$name      = $this->session->userdata('spfirstname')." ".$this->session->userdata('spmiddlename')." ".$this->session->userdata('splastname');
			}
			if(!isset($member_id)){
				redirect(base_url('admitcard/dipcert808'));
			}
		
			$query = $this->db->query("select exm_cd from admitcard_info where mem_mem_no = '".$member_id."' ");
			$exm_arr = $query->result();
		
			if(sizeof($exm_arr) > 0){ 
				if(count($exm_arr) > 1){
					 $record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."' AND date IN ('05-Jan-20','12-Jan-20','19-Jan-20')
					GROUP BY admitcard_info.exm_cd
					ORDER BY admitcard_info.admitcard_id DESC 
					 ");
					$result = $record->result();
					
				}elseif(count($exm_arr) == 1){
					$record=$this->db->query("SELECT admitcard_info.exm_cd, admitcard_info.m_1, admitcard_info.sub_cd, admit_exam_master.description, admitcard_info.mode, admitcard_info.date as exam_date, admitcard_info.admitcard_id
					FROM admit_exam_master
					JOIN admitcard_info ON admitcard_info.exm_cd = admit_exam_master.exam_code
					WHERE admitcard_info.mem_mem_no = '".$member_id."'  AND date IN ('05-Jan-20','12-Jan-20','19-Jan-20')
					ORDER BY admitcard_info.admitcard_id DESC 
					LIMIT 1;
					");
					$result = $record->result();
					
				}
			}else{
				$result = array();
			}
			
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn','mid'=>$member_id);
			$this->load->view('admitdashboardjd',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}
	}
	
	public function feedback(){
		$this->load->view('feedback');
	}
	
}
