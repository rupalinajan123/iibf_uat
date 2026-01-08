<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Getphotosig extends CI_Controller {

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
		 $this->load->library('email');
		 $this->load->model('Emailsending');
	     $this->load->model('Emailsending_123');
		 $this->load->helper('admitcard_helper');
	     $this->load->helper('custom_invoice_helper');
	} 
	
	
	public function index(){
	
	    echo 'in';
		exit;
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
				//echo $decData = $aes->decrypt("0vXcgvTUG5yi2YG1AMSlnQ==");
				$dataarr=array(
					'regnumber'=> $this->input->post('Username'),
					//'usrpassword'=>$encpass,
				);
				if ($this->form_validation->run() == TRUE){
					$user_info=$this->master_model->getRecords('member_registration',$dataarr);
					if(count($user_info) > 0){ 
						$mysqltime=date("H:i:s");
						$seprate_user_data=array('regid'=>$user_info[0]['regid'],
													'spregnumber'=>$user_info[0]['regnumber'],
													'spfirstname'=>$user_info[0]['firstname'],
													'spmiddlename'=>$user_info[0]['middlename'],
													'splastname'=>$user_info[0]['lastname']
												);
						$this->session->set_userdata($seprate_user_data);
						redirect(base_url().'dwnletter/getadmitdashboard');	
					}else{
						$data['error']='<span style="">Membership No. is not valid.</span>';
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
			
			$this->load->view('admitcardloginjaiib',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	 
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
			
			$record=$this->db->query("SELECT admitcard_info_190.exm_cd, admitcard_info_190.m_1, admitcard_info_190.sub_cd, exam_master.description, admitcard_info_190.mode, admitcard_info_190.date as exam_date, admitcard_info_190.admitcard_id
FROM exam_master
JOIN admitcard_info_190 ON admitcard_info_190.exm_cd = exam_master.exam_code
WHERE admitcard_info_190.mem_mem_no = '".$member_id."'
ORDER BY admitcard_info_190.admitcard_id DESC
LIMIT 1 ");
			$result = $record->result();
			
			$data = array("exam_name"=>$result,'name'=>$name,'frm'=>'dwn');
			$this->load->view('admitdashboard',$data);
			
		}catch(Exception $e){
			echo "Message : ".$e->getMessage();
		}	
	}
	
	public function getadmitcardsp(){
		//To Do-- validate as per admin admit card setting(Need to Do)
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
				redirect(base_url().'dwnletter/index');	
			}
			
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
			
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			$exam_code = base64_decode($this->uri->segment(3));
			
			
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			if(in_array($result->center_code, $nseitcenter)){
				$vcenter = 'NSEIT';
			}
			if(in_array($result->center_code, $sifycenter)){
				$vcenter = 'SIFY';
			}
			
			
			$medium_code = $result->m_1;
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM subject_master JOIN admitcard_info_190 ON subject_master.subject_code = RIGHT(admitcard_info_190.sub_cd,3) WHERE subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			
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
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"subjectprint"=>$subject_result,"examdate"=>$printdate,"exam_code"=>$exam_code,"vcenter"=>$vcenter,"records"=>$results,"recordsp"=>$results,'frm'=>'dwn');
			//load the view and saved it into $html variable
			$this->load->view('admitcardsp', $data);
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	public function getadmitcardpdfsp(){
		//To Do-- validate as per admin admit card setting(Need to Do)
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
			
			$exam_code = base64_decode($this->uri->segment(3));
			$img_path = base_url()."uploads/photograph/";
			$sig_path =  base_url()."uploads/scansignature/";
			
			$this->db->select('admitcard_info_190.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
			$this->db->from('admitcard_info_190');
			$this->db->join('member_registration', 'admitcard_info_190.mem_mem_no = member_registration.regnumber');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$record = $this->db->get();
			$result = $record->row();
			
			$this->db->select('*');
			$this->db->from('admitcard_info_190');
			$this->db->where(array('admitcard_info_190.mem_mem_no' => $member_id,'exm_cd'=>$exam_code));
			$this->db->group_by('venueid');
			$this->db->order_by("date", "asc");
			$nrecord = $this->db->get();
			$results = $nrecord->result();
			
			if(in_array($result->center_code, $nseitcenter)){
				$vcenter = 'NSEIT';
			}
			if(in_array($result->center_code, $sifycenter)){
				$vcenter = 'SIFY';
			}
			
			//$exam_code = $result->exm_cd;
			
			$medium_code = $result->m_1;
			
			$this->db->select('description');
			$exam = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
			$exam_result = $exam->row();
			
			
			$subject=$this->db->query("SELECT subject_description,date,time,venueid,seat_identification FROM subject_master JOIN admitcard_info_190 ON subject_master.subject_code = RIGHT(admitcard_info_190.sub_cd,3) WHERE subject_master.exam_code = ".$exam_code." AND admitcard_info_190.mem_mem_no = '".$member_id."' AND subject_delete = '0' ORDER BY STR_TO_DATE(date, '%e-%b-%y') ASC ");
			
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
			
			
			$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description, "img_path"=>$img_path , "sig_path"=>$sig_path,"examdate"=>$printdate,'member_id'=>$member_id,"vcenter"=>$vcenter,"exam_code"=>$exam_code,"records"=>$results,"recordsp"=>$results);
			//load the view and saved it into $html variable
			$html=$this->load->view('admitcardpdf', $data, true);
			//this the the PDF filename that user will get to download
			$this->load->library('m_pdf');
			$pdf = $this->m_pdf->load();
			$pdfFilePath = "IIBF_ADMIT_CARD_".$member_id.".pdf";
			//generate the PDF from the given html
			$pdf->WriteHTML($html);
			//download it.
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
			$vals = array('img_path' => './uploads/applications/','img_url' => base_url().'uploads/applications/',
						);
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
	
	public function centerstat(){
		
		$exam_code = 34;
		$exam_period = '714';
		
		$result=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
		
		foreach($result as $record){
			$reg = $this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1, "examination_date"=>2017-10-14));
			
			$insert_array = array(
								'exam_code' =>$exam_code,
								'center_code'=>$record['center_code'],
								'center_name'=>$record['center_name'],
								'exam_period'=>$exam_period,
								'register_count'=>sizeof($reg)
							);
							
			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);
							
		}
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "filename_you_wish.csv";
		$query = "SELECT * FROM center_stat ";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
		
	}
	public function venuestat(){
		
		$exam_code = 101;
		$exam_period = '540';
		
		$result=$this->master_model->getRecords('center_master',array('exam_name'=>$exam_code,'exam_period'=>$exam_period),'center_code,center_name,exam_period');
		
		foreach($result as $record){
			$reg = $this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'exam_center_code'=>$record['center_code'],"pay_status"=>1));
			
			$insert_array = array(
								'exam_code' =>$exam_code,
								'center_code'=>$record['center_code'],
								'center_name'=>$record['center_name'],
								'exam_period'=>$exam_period,
								'register_count'=>sizeof($reg)
							);
							
			$last_id = $this->master_model->insertRecord('center_stat',$insert_array,true);
							
		}
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "filename_you_wish.csv";
		$query = "SELECT * FROM center_stat ";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		//$this->db->empty_table('center_stat'); 
		force_download($filename, $data);
		
	}
	
	
	public function jaiib_datewise(){
		
		/*$this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "filename_you_wish.csv";
        $query = "SELECT * FROM dra_admin ";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
		
		exit;*/
		
		$exam_code = $this->config->item('examCodeJaiib');
		$exam_period = '117';
		
		//SELECT DATE(created_on),count(*)  FROM `member_exam` WHERE `exam_code` = 21 AND  `pay_status` = 1  GROUP BY DATE(created_on)
		$this->db->select('DATE(created_on),count(*)');
		$this->db->group_by('DATE(created_on)');
		$result=$this->master_model->getRecords('member_exam',array('exam_code'=>$exam_code,'exam_period'=>$exam_period,'pay_status'=>1));
		echo $this->db->last_query();
		foreach($result as $record){
			$insert_array = array(
								'date' =>$record['DATE(created_on)'],
								'count'=>$record['count(*)'],
							);
			$last_id = $this->master_model->insertRecord('date_wise_count',$insert_array,true);
							
		}
		$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "date_wise_count.csv";
		$query = "SELECT * FROM date_wise_count";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		$this->db->empty_table('date_wise_count'); 
		force_download($filename, $data);
	}
	
	
	// to check genaration of invoice
	public function chkinvoice(){
		genarate_reg_invoice(121);	
	}
	
	// to check genaration of custom invoice
	public function custom_chkinvoice(){
		
		$arr = array('900216087','900216171','900216352','900216517','900216519','900216581','900217866');
		
		for($i=0;$i<=6;$i++){
			
			custom_genarate_exam_invoice_jk_supplier($arr[$i]);	
		}
	}
	
	// to check email sending
	public function chkmail(){
		$info_arr=array('to'=>'pwn.prdshi@rediffmail.com',
									'from'=>'IIBF',
									'subject'=>'test mail',
									'message'=>'this is testing mail'
								);
		//$this->Emailsending_123->mailsend($info_arr);
	}
	
	// to check genaration of admitcard 
	public function admitcard(){
	
	    echo 'in';exit;
	    $member_id = '510341214';
	    //get invoice image path
		$query = $this->db->query("SELECT member_no,receipt_no,transaction_no,invoice_no,invoice_image FROM `exam_invoice` WHERE `member_no` LIKE ".$member_id." AND `transaction_no` != '' AND invoice_no != '' AND invoice_image != '' AND exam_invoice.exam_code !='' ");
		$invoice_data = $query->result_array();
		echo $this->db->last_query();
		
		if(!empty($invoice_data))
		{
			//echo '<pre>',print_r($invoice_data),'</pre>';
			$invoice_path = $invoice_data[0]['invoice_image'];
			if(!empty($invoice_path))
			{
				$invoice_path = 'https://iibf.esdsconnect.com/uploads/examinvoice/user/'.$invoice_path;
			}
		}
		echo  $invoice_data[0]['receipt_no'] ,'</br>'; //'receipt_no = ',
	    echo  $invoice_path ,'</br>'; //'Invoive Path = ',
	    
		$path = genarate_admitcard_test($member_id);
		if(!empty($path))
		{
			$pdf_path = 'https://iibf.esdsconnect.com/'.$path;
		}
		echo $pdf_path; //'Admit_card pdf = ',
	}  
	// get old member photo and signature
	public function getphotosig(){
		
		echo $p = get_img_name(700018080,'p');  
		echo "<br/>"; 
		echo $s = get_img_name(700018080,'s'); 
	}
	 
	// to check genaration of duplicate certificate invoice
	public function dupinvoice(){
		
		custome_genarate_duplicatecert_invoice(14185);
	}
	
	//to check query
	public function deleterecord(){
		try{ 
		error_reporting(E_ALL);
		$regnum = 	'100001314';
		//$this->master_model->deleteRecord('admitcard_info_22_feb_2017','mem_mem_no',$regnum);	
		//echo $this->db->last_query();
		$sql = mysql_query("DELETE FROM `admitcard_info_22_feb_2017` WHERE `admitcard_info_22_feb_2017`.`mem_mem_no` = '".$regnum."'");
		echo "DELETE FROM `admitcard_info_22_feb_2017` WHERE `admitcard_info_22_feb_2017`.`mem_mem_no` = '".$regnum."'";
		echo ">>".$sql."<<";
		}catch(Exception $e){echo "##". $e->getMessage();}
		
	}
	
	// to check genaration of custom duplicate idcard invoice
	public function custom_dupidcardinvoice_mailsent(){
		
		//custome_genarate_duplicateicard_invoice(22029);	
		
		$MerchantOrderNo = 22029;
		$pay_txn_id = 1930043;
		
		$getinvoice_number=$this->master_model->getRecords('exam_invoice_test',array('receipt_no'=>$MerchantOrderNo,'pay_txn_id'=>$pay_txn_id));
		
		if(count($getinvoice_number) > 0)
		{ 
				
			$attachpath=custome_genarate_duplicateicard_invoice($getinvoice_number[0]['invoice_id']);
			
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'duplicate_id'));
			
			$user_info = $this->master_model->getRecords('member_registration',array('regnumber'=>5944633),'namesub,firstname,middlename,lastname,email,usrpassword,mobile');
			$username = $user_info[0]['namesub'].' '.$user_info[0]['firstname'].' '.$user_info[0]['middlename'].' '.$user_info[0]['lastname'];
			$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
			$newstring1 = str_replace("#DATE#", "".date('Y-m-d h:s:i')."",  $emailerstr[0]['emailer_text']);
			$newstring2 = str_replace("#MEM_NO#", "5944633", $newstring1 );
			$final_str= str_replace("#USERNAME#", "".$userfinalstrname."",  $newstring2);
			
			$info_arr = array('to'=>$user_info[0]['email'],'from'=>$emailerstr[0]['from'],'subject'=>$emailerstr[0]['subject'],'message'=>$final_str);
			
			
			
			if($attachpath!='')
			{	
				//if($this->Emailsending->mailsend($info_arr))
				if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
				{
					echo "Invoice sent";
				}
				else
				{
					echo "Invoice not sent";
				}
			}
		}
		
		
		
	} 
	
	public function custom_dupidcardinvoice(){
		
		$array = array(51156,51136,50641,50495,50431,50395,50379,49985,49555,49207,48532,48487,48398,48317,48060,47641,47625,47153,46171,45766,45577,45332,45063,44626,44461,44422,43397,43181,42725,42331,42330,41445);
		
		for($i=0;$i<=31;$i++){
		 	$path = custome_genarate_duplicateicard_invoice($array[$i]);
		 	echo $path;
		 	echo "<br/>";
		}
	}
	
	// to check custom bankquest invoice
	public function custom_bankquest_invoice(){
		custom_genarate_bankquest_invoice(740);
	}
	
	// to genarate custom registration invoice
	public function custom_registration_invoice(){
		$receipt_no = 811950334;
		$attach = custom_genarate_reg_invoice($receipt_no);
		
		if($attach!=''){
			
			$user_info=$this->master_model->getRecords('member_registration',array('regnumber'=>510336577),'usrpassword,email');
			$applicationNo = 510342058;
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'user_register_email'));
			
			if(count($emailerstr) > 0)
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key = $this->config->item('pass_key');
				$aes = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
				$newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['emailer_text']);
				$final_str= str_replace("#password#", "".$decpass."",  $newstring);
				$info_arr=array('to'=>$user_info[0]['email'],
								//'to'=>'raajpardeshi@gmail.com',
								'from'=>$emailerstr[0]['from'],
								'subject'=>$emailerstr[0]['subject'],
								'message'=>$final_str
								); 
								
				if($this->Emailsending->mailsend_attch($info_arr,$attach)){
					echo "Email sent";
				}else{
					echo "Email not sent";
				}
			}
		}else{
			echo "attach path is blank";	
		}
	}
	
	public function genarate_csv(){
		$result=$this->master_model->getRecords('config_bankquest_invoice');
		//echo "<pre>";
		//print_r($result);
		/*$this->load->dbutil();
		$this->load->helper('file');
		$this->load->helper('download');
		$delimiter = ",";
		$newline = "\r\n";
		$filename = "uploads/date_wise_count.csv";
		$query = "SELECT * FROM date_wise_count";
		$result1 = $this->db->query($query);
		$data = $this->dbutil->csv_from_result($result1, $delimiter, $newline);
		$this->db->empty_table('date_wise_count'); 
		force_download($filename, $data);*/
		
		$data_array = array (
            array ('1','2'),
            array ('2','2'),
            array ('3','6'),
            array ('4','2'),
            array ('6','5')
            );

		$csv = "col1,col2 \n";//Column headers
		foreach ($result as $record){
			$csv.= $record['sub_invoice_no'].','.$record['invoice_id']."\n"; //Append data to csv
			}
		
		$csv_handler = fopen ('uploads/csvfile.csv','w');
		fwrite ($csv_handler,$csv);
		fclose ($csv_handler);
		
		echo 'Data saved to csvfile.csv';
		
		
	}
	//tejasvi
	public function download_PDC_CSV()
	{
	    //exit; 
		 $csv = "exam_code,center_code,center_name,exam_period,register_count \n";//Column headers
		 
		$query = $this->db->query("SELECT center_master.exam_name,center_master.center_code,center_master.center_name,center_master.exam_period,COUNT(member_exam.id) AS registerd
		FROM center_master
		LEFT JOIN member_exam ON member_exam.exam_code = center_master.exam_name AND member_exam.exam_period = center_master.exam_period AND member_exam.exam_center_code= center_master.center_code
		WHERE center_master.exam_name IN (34,58,160) AND center_master.exam_period IN(714) AND member_exam.pay_status = 1 AND member_exam.examination_date = '2017-10-14'
		GROUP by center_master.exam_name,center_master.center_code,center_master.center_name,center_master.exam_period");
		
		
		$result = $query->result_array();
		foreach($result as $record)
		{
			
			// print_r($record);exit;
			 $csv.= $record['exam_name'].','.$record['center_code'].','.$record['center_name'].','.$record['exam_period'].','.$record['registerd']."\n";
		}
		
        $filename = "pdc_exam_stats.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		$csv_handler = fopen('php://output', 'w');
 		fwrite ($csv_handler,$csv);
 		fclose ($csv_handler);
	}
	
	
	public function  contact_class(){
		echo $path = 	genarate_contact_classes_invoice();	
	}
	
	// genarate admitcard for JAIIB prevoius valid member
	public function jaiib_previous(){
		try{
			
			$this->db->limit(0,20);
			$jaiib_member = $this->master_model->getRecords('jaiib_previous');
			
			$this->db->where('center_code',306);
			$venue = $this->master_model->getRecords('venue_master_j');
			
			$this->db->where('exam_code',$this->config->item('examCodeJaiib'));
			$this->db->where('exam_period',217);
			$this->db->order_by("id", "asc");
			$subject = $this->master_model->getRecords('subject_master','','exam_date');
			foreach($subject as $subject_res){
				$exam_date_arr[] = $subject_res['exam_date'];
			}
			
			$time_array = array("2:00 PM","2.00 PM","11:15 AM","11.15 AM","8:30 AM","8.30 AM");
			
			foreach($jaiib_member as $jaiib_member_res){
				
				foreach($venue as $venue_res){
					
					
					$capacity=check_capacity($venue_res['venue_code'],$exam_date_arr[0],$time_array[1],306);
					if($capacity == 0){
						echo "Capacity is full";
					}elseif($capacity != 0){
						
						// insert in admit card dettail table
						$admitcard_insert_array=array(
											'mem_exam_id'=>1,
											'center_code'=>$jaiib_member_res['center_code'],
											'center_name'=>'test',
											'mem_type'=>$jaiib_member_res['member_type'],
											'mem_mem_no'=>$jaiib_member_res['member_number'],
											'g_1'=>$jaiib_member_res['gender'],
											'mam_nam_1'=>$jaiib_member_res['member_name'],
											'mem_adr_1'=>$jaiib_member_res['address_1'],
											'mem_adr_2'=>$jaiib_member_res['address_2'],
											'mem_adr_3'=>$jaiib_member_res['address_3'],
											'mem_adr_4'=>$jaiib_member_res['address_4'],
											'mem_adr_5'=>$jaiib_member_res['district'],
											'mem_adr_6'=>$jaiib_member_res['city'],
											'mem_pin_cd'=>$jaiib_member_res['pincode'],
											'state'=>$jaiib_member_res['state'],
											'exm_cd'=>$jaiib_member_res['exam_code'],
											'exm_prd'=>217,
											'sub_cd '=>$jaiib_member_res['subject_code'],
											'sub_dsc'=>'subject name',
											'm_1'=>$jaiib_member_res['medium'],
											'inscd'=>$jaiib_member_res['institute_code'],
											'insname'=>$jaiib_member_res['institute_name'],
											'venueid'=>$venue_res['venue_code'],
											'venue_name'=>$venue_res['venue_name'],
											'venueadd1'=>$venue_res['venue_addr1'],
											'venueadd2'=>$venue_res['venue_addr2'],
											'venueadd3'=>$venue_res['venue_addr3'],
											'venueadd4'=>$venue_res['venue_addr4'],
											'venueadd5'=>$venue_res['venue_addr5'],
											'venpin'=>$venue_res['venue_pincode'],
											'exam_date'=>$exam_date_arr[0],
											'time'=>$time_array[1],
											'mode'=>'Online',
											'scribe_flag'=>'',
											'vendor_code'=>$venue_res['vendor_code'],
											'remark'=>2,
											'created_on'=>date('Y-m-d H:i:s'));
						
						$inser_id=$this->master_model->insertRecord('admit_card_details',$admitcard_insert_array);
						
						
						
						$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$venue_res['venue_code'],'exam_date'=>$exam_date_arr[0],'session_time'=>$time_array[1]));
						
						
						$admit_card_details=$this->master_model->getRecords('admit_card_details',array('venueid'=>$venue_res['venue_code'],'exam_date'=>$exam_date_arr[0],'time'=>$time_array[1],'sub_cd'=>$jaiib_member_res['subject_code'],'mem_mem_no'=>$jaiib_member_res['member_number']));
						
						// insert in seat allocation table
						$seat_allocation = getseat($this->config->item('examCodeJaiib'), 306, $venue_res['venue_code'], $exam_date_arr[0], $time_array[1] , 217 , $jaiib_member_res['subject_code'],$get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
					}
					
					$i++;
				}
			}
			
		}catch(Exception $e){echo $e->getMessage();}
	}
	
	public function jaiib_previous_test(){
		try{
			$member_array = array("100010289","100054194","200059157","300014603","3894274");
			$this->db->where_in('mem_mem_no', $member_array);
			$jaiib_member = $this->master_model->getRecords('admit_card_details_j');
			
			$this->db->where('center_code',306);
			$venue = $this->master_model->getRecords('venue_master');
			
			
			$this->db->where('exam_code',$this->config->item('examCodeJaiib'));
			$this->db->where('exam_period',217);
			$this->db->order_by("id", "asc");
			$subject = $this->master_model->getRecords('subject_master','','exam_date');
			foreach($subject as $subject_res){
				$exam_date_arr[] = $subject_res['exam_date'];
			}
			
			$time_array = array("2:00 PM","2.00 PM","11:15 AM","11.15 AM","8:30 AM","8.30 AM");
			
			
			foreach($venue as $venue_res){
				$venue_arr[] = $venue_res['venue_code'];
			}
			
			$i = 0;
			foreach($jaiib_member as $jaiib_member_res){
				$password=random_password();
				$capacity=check_capacity($venue_arr[$i],$exam_date_arr[0],$time_array[1],306);
				
				if($capacity == 0){
					echo "Capacity full";
				}elseif($capacity != 0){
					
					$get_subject_details=$this->master_model->getRecords('venue_master',array('venue_code'=>$venue_arr[$i],'exam_date'=>$exam_date_arr[0],'session_time'=>$time_array[1]));
					
					
					$admit_card_details=$this->master_model->getRecords('admit_card_details_j',array('sub_cd'=>$jaiib_member_res['sub_cd'],'mem_mem_no'=>$jaiib_member_res['mem_mem_no'],'exm_cd'=>$jaiib_member_res['exm_cd']));
					
					$seat_allocation = getseat_j($this->config->item('examCodeJaiib'), 306, $venue_arr[$i], $exam_date_arr[0], $time_array[1] , 217 , $jaiib_member_res['sub_cd'],$get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
					
					$this->db->where('center_code',306);
					$this->db->where('venue_code',$venue_arr[$i]);
					$this->db->where('exam_date',$exam_date_arr[0]);
					$this->db->where('session_time',$time_array[1]);
					$venue_rec = $this->master_model->getRecords('venue_master');
					
					if($seat_allocation!='')
					{
						$final_seat_number =$seat_allocation;
						$update_data = array(
										'pwd' => $password,
										'seat_identification' => $final_seat_number,
										'remark'=>1,
										'modified_on'=>date('Y-m-d H:i:s'),
										'exam_date' =>$exam_date_arr[0],
										'time' => $time_array[1],
										'venpin' => $venue_rec[0]['venue_pincode'],
										'venueadd1' =>$venue_rec[0]['venue_addr1'],
										'venueadd2' =>$venue_rec[0]['venue_addr2'],
										'venueadd3' =>$venue_rec[0]['venue_addr3'],
										'venueadd4' =>$venue_rec[0]['venue_addr4'],
										'venueadd5' =>$venue_rec[0]['venue_addr5'],
										'venue_name' =>$venue_rec[0]['venue_name'],
										'venueid' =>$venue_arr[$i]
										);
						$this->master_model->updateRecord('admit_card_details_j',$update_data,array('admitcard_id'=>$admit_card_details[0]['admitcard_id']));
					}
					
					
					
				}
				$i++;
			}
			
			exit;
		}catch(Exception $e){echo $e->getMessage();}
	}
	
	public function insert_admitcard_jaiib(){
		$member_array = array("100010289","100054194","200059157","300014603","3894274");
		$this->db->where_in('member_number', $member_array);
		$jaiib_member = $this->master_model->getRecords('jaiib_previous');
		
		foreach($jaiib_member as $jaiib_member_res){
			
			$this->db->where('subject_code',$jaiib_member_res['subject_code']);
			$subject = $this->master_model->getRecords('subject_master','','subject_description');
			
			$admitcard_insert_array = array(
											'mem_exam_id'=>'',
											'center_code'=>$jaiib_member_res['center_code'],
											'center_name'=>'test',
											'mem_type'=>$jaiib_member_res['member_type'],
											'mem_mem_no'=>$jaiib_member_res['member_number'],
											'g_1'=>$jaiib_member_res['gender'],
											'mam_nam_1'=>$jaiib_member_res['member_name'],
											'mem_adr_1'=>$jaiib_member_res['address_1'],
											'mem_adr_2'=>$jaiib_member_res['address_2'],
											'mem_adr_3'=>$jaiib_member_res['address_3'],
											'mem_adr_4'=>$jaiib_member_res['address_4'],
											'mem_adr_5'=>$jaiib_member_res['district'],
											'mem_adr_6'=>$jaiib_member_res['city'],
											'mem_pin_cd'=>$jaiib_member_res['pincode'],
											'state'=>$jaiib_member_res['state'],
											'exm_cd'=>$jaiib_member_res['exam_code'],
											'exm_prd'=>217,
											'sub_cd '=>$jaiib_member_res['subject_code'],
											'sub_dsc'=>$subject[0]['subject_description'],
											'm_1'=>$jaiib_member_res['medium'],
											'inscd'=>$jaiib_member_res['institute_code'],
											'insname'=>$jaiib_member_res['institute_name'],
											'venueid'=>'',
											'venue_name'=>'',
											'venueadd1'=>'',
											'venueadd2'=>'',
											'venueadd3'=>'',
											'venueadd4'=>'',
											'venueadd5'=>'',
											'venpin'=>'',
											'exam_date'=>'',
											'time'=>'',
											'mode'=>'Online',
											'scribe_flag'=>'',
											'vendor_code'=>'',
											'remark'=>2,
											'created_on'=>date('Y-m-d H:i:s')
											);
			$inser_id=$this->master_model->insertRecord('admit_card_details_j',$admitcard_insert_array);
		}
	}
	public function send_custom_invoice_mail()
	{
		
			$info_arr = array('to'=>'21bhavsartejasvi@gmail.com','from'=>'21bhavsartejasvi@gmail.com','subject'=>'Custom Invoice','message'=>'Null');

		
	    $attachpath = custom_genarate_exam_invoice('900320555');
		if($attachpath!='')
		{
			if($this->Emailsending->mailsend_attch($info_arr,$attachpath)) 
			{
                 echo "mail send successfully";
		    }
			else
			{
				echo "Error while mail sending";
			}
		}	
	    
	}
	public  function get_admitcard_mismatch_data()
	{
	    echo 'in';
		$query = $this->db->query("SELECT * FROM `admit_card_details` WHERE 1 ");
		$admit_data = query->result_array();
	    //$admit_data = $this->master_model->getRecords('admit_card_details');//array('admitcard_id'=>'251043')
		echo $this->db->last_query();
		print_r($admit_data[0]);exit;
	    foreach($admit_data as $key_data)
		{
		  
		    $seat_allocation_data = $this->master_model->getRecords('seat_allocation',array('admit_card_id'=>$key_data['admitcard_id']));
			 
			if(!empty($seat_allocation_data))
			{
				if(count($seat_allocation_data) > 1)
				{
					echo count($seat_allocation_data),' ',$seat_allocation_data[0]['admit_card_id'],'</br>';
				}
				//print_r($key_data['seat_identification']);exit;
				$cnt_seat_mismatch_count = 0;
				if($key_data['seat_identification'] != $seat_allocation_data[0]['seat_no'])
				{
					$seat_identification[] = $key_data['admitcard_id'];
					$cnt_seat_mismatch_count++;
				}
				
				$cnt_subcd_mismatch_count = 0;
				if($key_data['exm_cd'] != $seat_allocation_data[0]['exam_code'])
				{
					$exam_code[] = $key_data['admitcard_id'];
					$cnt_subcd_mismatch_count++;
				}
				
				$cnt_subpr_mismatch_count = 0;
				if($key_data['exm_prd'] != $seat_allocation_data[0]['exam_period'])
				{
					$exam_period[] = $key_data['admitcard_id'];
					$cnt_subpr_mismatch_count++;
				}
				
				$cnt_exmcd_mismatch_count = 0;
				if($key_data['sub_cd'] != $seat_allocation_data[0]['subject_code'])
				{
					$subject_code[] = $key_data['admitcard_id'];
					$cnt_exmcd_mismatch_count++;
				}
				
				
				echo '<pre> wrong seat_allocation :',print_r(@$seat_identification),'mismatch_count :',print_r($cnt_seat_mismatch_count),'</pre>';
				echo '</br>';
				echo '--------------------------------------------------------------';
				echo '<pre> wrong exam code :',print_r(@$exam_code),'mismatch_count :',print_r($cnt_subcd_mismatch_count),'</pre>';
				echo '</br>';
				echo '--------------------------------------------------------------';
				echo '<pre> wrong subject period :',print_r(@$exam_period),'mismatch_count :',print_r($cnt_subpr_mismatch_count),'</pre>';
				echo '</br>';
				echo '--------------------------------------------------------------';
				echo '<pre> wrong subject code :',print_r(@$subject_code),'mismatch_count :',print_r($cnt_exmcd_mismatch_count),'</pre>';
			}
		}
	}
 
}
