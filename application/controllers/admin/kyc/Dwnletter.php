<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dwnletter extends CI_Controller {

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
	
	
	public function index(){
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
	
}
