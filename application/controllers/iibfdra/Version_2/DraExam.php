<?php
defined('BASEPATH') OR exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class DraExam extends CI_Controller {
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('dra_institute')) {
			redirect('iibfdra/InstituteLogin');
		}
		$this->load->library('upload');	
		$this->load->helper('upload_helper');
		$this->load->helper('master_helper');
		$this->load->model('UserModel');
		$this->load->model('master_model');	
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');	
		$this->load->model('log_model');
		$this->load->helper('general_helper');
	}
	public function getApplicantList() {
		$data['result'] = array();
		$data['action'] = array();
		$data['links'] = '';
		$data['success'] = '';
		$field = '';
		$value = '';
		$sortkey = '';
		$sortval = '';
		$per_page = '';
		$limit = 10;
		$start = 0;
		$session_arr = check_session();
		if($session_arr)
		{
			$field = $session_arr['field'];
			$value = $session_arr['value'];
			$sortkey = $session_arr['sortkey'];
			$sortval = $session_arr['sortval'];
			$per_page = $session_arr['per_page'];
			$start = $session_arr['start'];
		}
		$this->db->where('dra_member_exam.dra_memberexam_delete',0);
		$total_row = $this->UserModel->getRecordCount("dra_member_exam",$field,$value);
		$url = base_url()."iibfdra/DraExam/getApplicantList/";
		$config = pagination_init($url,$total_row, $per_page, 2);
		$this->pagination->initialize($config);
		
		$this->db->where('dra_members.isdeleted',0);
		$this->db->join('dra_members','dra_member_exam.regid = dra_members.regid');
		$this->db->where('dra_member_exam.dra_memberexam_delete',0);
		$res = $this->UserModel->getRecords("dra_member_exam", '', $field, $value, $sortkey, $sortval, $per_page, $start);
		//$data['query'] = $this->db->last_query();
		if($res)
		{
			$result = $res->result_array();
			$data['result'] = $result;
			foreach($result as $row)
			{
				$confirm = "return confirm('Are you sure to delete this record?');";
				$action = '<a href="'.base_url().'iibfdra/DraExam/edit/'.$row['id'].'">Edit |</a><a href="'.base_url().'iibfdra/DraExam/delete/'.$row['id'].'" onclick="'.$confirm.'">Delete </a>';
				$data['action'][] = $action;
			}
			if(count($result))
				$data['success'] = 'Success';
			else
				$data['success'] = '';
			
			$str_links = $this->pagination->create_links();
			$data["links"] = $str_links;
			if(($start+$per_page)>$total_row)
				$end_of_total = $total_row;
			else
				$end_of_total = $start+$per_page;
			
			$data['info'] = 'Showing '.($start+1).' to '.$end_of_total.' of '.$total_row.' entries';
			$data['index'] = $start+1;
		}
		
		$json_res = json_encode($data);
		echo $json_res;
	}
	public function emailduplication()
	{
		$email = $_POST['email'];
		if( $email != "") {
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->where('dra_member_exam.dra_memberexam_delete',0);
			$results = $this->master_model->getRecords('dra_members',array('email'=>$email,'isactive'=>'1','isdeleted'=>'0'));
			if( $results ) { //exists
				$username = $results[0]['firstname'].' '.$results[0]['middlename'].' '.$results[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered email ID already exist for ';
				if( !empty( $results[0]['regnumber'] ) ) {
					$str .='membership / registration number '.$results[0]['regnumber'].' , '.$userfinalstrname;
				} else {
					$str .= $userfinalstrname;
				}
				$data_arr = array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			} else {
				$data_arr = array('ans'=>'ok');		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	
	##---------check mobile nnnumber alredy exist or not -----------##
	 public function mobileduplication()
	{
		$mobile = $_POST['mobile'];
		if($mobile!="")
		{
			$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
			$this->db->where('dra_member_exam.dra_memberexam_delete',0);
			$results = $this->master_model->getRecords('dra_members',array('mobile'=>$mobile,'isactive'=>'1','isdeleted'=>'0'));
			if( $results ) { //exists
				$username=$results[0]['firstname'].' '.$results[0]['middlename'].' '.$results[0]['lastname'];
				$userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
				$str='The entered mobile no already exist for ';
				if( !empty( $results[0]['regnumber'] ) ) {
					$str .='membership / registration number '.$results[0]['regnumber'].' , '.$userfinalstrname;
				} else {
					$str .= $userfinalstrname;
				}
				$data_arr = array('ans'=>'exists','output'=>$str);		
				echo json_encode($data_arr);
			} else {
				$data_arr=array('ans'=>'ok');		
				echo json_encode($data_arr);
			}
		}
		else
		{
			echo 'error';
		}
	}
	/* Ajax callback function for geting exam mode from center code ( from center master ) */
	public function getexam_mode() {
		$center_code = ( isset($_POST['center_code']) ) ? $_POST['center_code'] : '';
		$exam_mode = '';
		if( !empty( $center_code ) ) {
			$exam_mode = $this->master_model->getValue('dra_center_master',array('center_code'=>$center_code),'exammode');
		}	
		echo $exam_mode;
		exit;
	}
	/* Check pin number according to state */
	public function checkpin()
	{
		$statecode=$_POST['statecode'];
		$pincode=$_POST['pincode'];
		if($statecode!="")
		{
			$this->db->where("$pincode BETWEEN start_pin AND end_pin");
		 	$prev_count=$this->master_model->getRecordCount('state_master',array('state_code'=>$statecode));
			//echo $this->db->last_query();
			if($prev_count==0)
			{echo 'false';}
			else
			{echo 'true';}
		}
		else
		{
			echo 'false';
		}
	}
	
	public function check_captcha_draexamapply($code) 
	{
		if(!isset($this->session->draexamcaptcha) && empty($this->session->draexamcaptcha))
		{
			redirect(base_url().'iibfdra/');
		}
		
		if($code == '' || $this->session->draexamcaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_draexamapply', 'Invalid %s.'); 
			$this->session->set_userdata("draexamcaptcha", rand(1,100000));
			return false;
		}
		if($this->session->draexamcaptcha == $code)
		{
			$this->session->set_userdata('draexamcaptcha','');
			$this->session->unset_userdata("draexamcaptcha");
			return true;
		}
	}
	public function check_captcha_draexamapplyedt($code) 
	{
		if(!isset($this->session->draexamedtcaptcha) && empty($this->session->draexamedtcaptcha))
		{
			redirect(base_url().'iibfdra/');
		}
		
		if($code == '' || $this->session->draexamedtcaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_draexamapplyedt', 'Invalid %s.'); 
			$this->session->set_userdata("draexamedtcaptcha", rand(1,100000));
			return false;
		}
		if($this->session->draexamedtcaptcha == $code)
		{
			$this->session->set_userdata('draexamedtcaptcha','');
			$this->session->unset_userdata("draexamedtcaptcha");
			return true;
		}
	}
	// reload captcha functionality
	public function generatecaptchaajax()
	{
		$captchaname = (isset($_POST['captchaname'])) ? $_POST['captchaname'] : '';
		if( !empty( $captchaname ) ) {
			$this->load->helper('captcha');
			$this->session->unset_userdata($captchaname);
			$this->session->set_userdata($captchaname, rand(1, 100000));
			$vals = array('img_path' => './uploads/applications/','img_url' => '../../../uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION[$captchaname] = $cap['word'];
			echo $data;
		} 
	}
	
	/* Ajax callback function for get details */
	public function get_memdetails() {
		$regno = (isset($_POST['regno'])) ? $_POST['regno'] : '';
		if( !empty( $regno ) ) {
			$memRes = $this->master_model->getRecords('member_registration',array('regnumber'=>$regno));
			$data = array();
			if( count( $memRes ) > 0 ) {
				$memRes = $memRes[0];
				$data['membertype'] = 'normal_member';
				$data['sel_namesub'] = $memRes['namesub']; 
				$data['firstname'] = $memRes['firstname']; 
				$data['middlename'] = $memRes['middlename']; 
				$data['lastname'] = $memRes['lastname']; 
				$data['addressline1'] = $memRes['address1'];
				$data['addressline2'] = $memRes['address2'];
				$data['city'] = $memRes['city'];
				$data['district'] = $memRes['district'];
				$data['state'] = $memRes['state'];
				$data['pincode'] = $memRes['pincode'];
				$data['dateofbirth'] = $memRes['dateofbirth'];
				$data['gender'] = $memRes['gender']; //check it
				$data['stdcode'] = $memRes['stdcode'];
				$data['phone'] = $memRes['office_phone'];
				$data['mobile'] = $memRes['mobile'];
				$data['email'] = $memRes['email'];
				$data['idproof'] = $memRes['idproof'];
				$data['memtype'] = $memRes['registrationtype'];
				$data['error'] = 0;	
			} else {
				$memRes = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
				$data = array();
				if( count( $memRes ) > 0 ) {
					//echo 'djkhkj'; die();
					$this->db->select('dra_member_exam.*, dra_members.*, dra_eligible_master.training_from as trainingfrm, dra_eligible_master.training_to as trainingto');
					$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
					$this->db->join('dra_eligible_master','dra_members.regnumber=dra_eligible_master.member_no');
					$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
					//echo $this->db->last_query(); die();
					if( count( $memRes ) > 0 ) {
				
						$memRes = $memRes[0];
						
						$data['membertype'] = 'dra_member';
						$data['sel_namesub'] = $memRes['namesub']; 
						$data['firstname'] = $memRes['firstname']; 
						$data['middlename'] = $memRes['middlename']; 
						$data['lastname'] = $memRes['lastname']; 
						$data['addressline1'] = $memRes['address1'];
						$data['addressline2'] = $memRes['address2'];
						$data['city'] = $memRes['city'];
						$data['district'] = $memRes['district'];
						$data['state'] = $memRes['state'];
						$data['pincode'] = $memRes['pincode'];
						$data['dateofbirth'] = $memRes['dateofbirth'];
						$data['gender'] = $memRes['gender']; //check it
						$data['stdcode'] = $memRes['stdcode'];
						$data['phone'] = $memRes['phone'];
						$data['mobile'] = $memRes['mobile'];
						$data['email'] = $memRes['email'];
						$data['idproof'] = $memRes['idproof'];
						$data['memtype'] = $memRes['registrationtype'];
						$data['exam_center'] = $memRes['exam_center_code'];
						$data['center_code'] = $memRes['exam_center_code'];
						$data['exam_medium'] = $memRes['exam_medium'];
						$data['training_from'] = $memRes['trainingfrm'];
						$data['training_to'] = $memRes['trainingto'];
						$data['exam_mode'] = $memRes['exam_mode'];
						$data['edu_quali'] = $memRes['qualification'];
						$data['aadhar_no'] = $memRes['aadhar_no'];	// added by Bhagwan Sahane, on 06-05-2017
						$data['error'] = 0;	
						
						$scannedphoto = '';
						$scannedsignaturephoto = '';
						$idproofphoto = '';
						$quali_certificate = '';
						$training_certificate = '';
						
						// get images -
						$old_image_path = 'uploads'.$memRes['image_path'];
							
						$new_image_path = 'uploads/iibfdra/';
						
						if($memRes['scannedphoto'] == '')
						{
							if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
							{
								$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['scannedphoto']))
							{
								$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
							}
						}
						
						if($memRes['scannedsignaturephoto'] == '')
						{
							if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
							{
								$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
							{
								$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
							}
						}
						
						if($memRes['idproofphoto'] == '')
						{
							if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
							{
								$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['idproofphoto']))
							{
								$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
							}
						}
						
						if($memRes['quali_certificate'] == '')
						{
							if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
							{
								$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['quali_certificate']))
							{
								$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
							}
						}
						
						if($memRes['training_certificate'] == '')
						{
							if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
							{
								$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
							}
						}
						else
						{
							if(file_exists($new_image_path . $memRes['training_certificate']))
							{
								$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
							}
						}
						
						/*if($memRes['scannedphoto'] == '')
						{
							$image_path = $memRes['image_path'];
							
							$scannedphoto = $image_path . "p_" . $memRes['registration_no'];
							$scannedsignaturephoto = $image_path . "s_" . $memRes['registration_no'];
							$idproofphoto = $image_path . "pr_" . $memRes['registration_no'];
							$quali_certificate = $image_path . "degre_" . $memRes['registration_no'];
						}
						else
						{
							$image_path = base_url().'uploads/iibfdra/';
							
							$scannedphoto = $image_path . $memRes['scannedphoto'];
							$scannedsignaturephoto = $image_path . $memRes['scannedsignaturephoto'];
							$idproofphoto = $image_path . $memRes['idproofphoto'];
							$quali_certificate = $image_path . $memRes['quali_certificate'];	
						}*/
						
						$data['scannedphoto'] = $scannedphoto;
						$data['scannedsignaturephoto'] = $scannedsignaturephoto;
						$data['idproofphoto'] = $idproofphoto;
						$data['quali_certificate'] = $quali_certificate;
						$data['training_certificate'] = $training_certificate;
						
					} else { // if not found in dra eligible master
						//$data['error'] = 1;	
						$this->db->select('dra_member_exam.*, dra_members.*, dra_member_exam.training_from as trainingfrm, dra_member_exam.training_to as trainingto');
						$this->db->join('dra_member_exam','dra_members.regid=dra_member_exam.regid');
						$memRes = $this->master_model->getRecords('dra_members',array('dra_members.regnumber'=>$regno));
						//echo $this->db->last_query();
						if( count( $memRes ) > 0 ) {
							
							$memRes = $memRes[0];
							
							$data['membertype'] = 'dra_member';
							$data['sel_namesub'] = $memRes['namesub']; 
							$data['firstname'] = $memRes['firstname']; 
							$data['middlename'] = $memRes['middlename']; 
							$data['lastname'] = $memRes['lastname']; 
							$data['addressline1'] = $memRes['address1'];
							$data['addressline2'] = $memRes['address2'];
							$data['city'] = $memRes['city'];
							$data['district'] = $memRes['district'];
							$data['state'] = $memRes['state'];
							$data['pincode'] = $memRes['pincode'];
							$data['dateofbirth'] = $memRes['dateofbirth'];
							$data['gender'] = $memRes['gender']; //check it
							$data['stdcode'] = $memRes['stdcode'];
							$data['phone'] = $memRes['phone'];
							$data['mobile'] = $memRes['mobile'];
							$data['email'] = $memRes['email'];
							$data['idproof'] = $memRes['idproof'];
							$data['memtype'] = $memRes['registrationtype'];
							$data['exam_center'] = $memRes['exam_center_code'];
							$data['center_code'] = $memRes['exam_center_code'];
							$data['exam_medium'] = $memRes['exam_medium'];
							$data['training_from'] = $memRes['trainingfrm'];
							$data['training_to'] = $memRes['trainingto'];
							$data['exam_mode'] = $memRes['exam_mode'];
							$data['edu_quali'] = $memRes['qualification'];
							$data['aadhar_no'] = $memRes['aadhar_no'];	// added by Bhagwan Sahane, on 06-05-2017
							$data['error'] = 0;	
							
							$scannedphoto = '';
							$scannedsignaturephoto = '';
							$idproofphoto = '';
							$quali_certificate = '';
							$training_certificate = '';
							
							// get images -
							$old_image_path = 'uploads'.$memRes['image_path'];
							
							$new_image_path = 'uploads/iibfdra/';
							
							if($memRes['scannedphoto'] == '')
							{
								if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
								{
									$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['scannedphoto']))
								{
									$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
								}
							}
							
							if($memRes['scannedsignaturephoto'] == '')
							{
								if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
								{
									$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
								{
									$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
								}
							}
							
							if($memRes['idproofphoto'] == '')
							{
								if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
								{
									$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['idproofphoto']))
								{
									$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
								}
							}
							
							if($memRes['quali_certificate'] == '')
							{
								if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
								{
									$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['quali_certificate']))
								{
									$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
								}
							}
							
							if($memRes['training_certificate'] == '')
							{
								if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
								{
									$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
								}
							}
							else
							{
								if(file_exists($new_image_path . $memRes['training_certificate']))
								{
									$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
								}
							}
							
							$data['scannedphoto'] = $scannedphoto;
							$data['scannedsignaturephoto'] = $scannedsignaturephoto;
							$data['idproofphoto'] = $idproofphoto;
							$data['quali_certificate'] = $quali_certificate;
							$data['training_certificate'] = $training_certificate;
						}
					}
				}
			}	
			echo json_encode($data);
		}
	}
	
	/*Added*/
	public function examapplied($regnumber=NULL,$exam_code=NULL)
	{
		//check where exam alredy apply or not
		$today_date=date('Y-m-d');
		$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_member_exam.exam_code AND dra_exam_activation_master.exam_period=dra_member_exam.exam_period');
		$this->db->join('dra_exam_master','dra_exam_master.exam_code=dra_member_exam.exam_code');
		$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid');
		$this->db->where('dra_members.regnumber',$regnumber);
		//$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_member_exam.exam_code');
		$this->db->where("'$today_date' BETWEEN dra_exam_activation_master.exam_from_date AND dra_exam_activation_master.exam_to_date");
		//$this->db->where('dra_member_exam.pay_status','1');
		//for preventing dual application
		$this->db->where('dra_member_exam.dra_memberexam_delete','0');
		$applied_exam_info=$this->master_model->getRecords('dra_member_exam',array('dra_member_exam.exam_code'=>$exam_code));
		//print_r($this->db->last_query());die("test");
		//echo $this->db->last_query();exit;
		return count($applied_exam_info);
	}
	
	//check whether applied exam date fall in same date of other exam date(Prafull)
	public function examdate($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$today_date=date('Y-m-d');
		$applied_exam_date=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_member_exam.exam_code AND dra_exam_activation_master.exam_period=dra_member_exam.exam_period');
			$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid');
			$this->db->where('dra_members.regnumber',$regnumber);
			$getapplied_exam_code=$this->master_model->getRecords('dra_member_exam',array('pay_status'=>'1'),'dra_member_exam.exam_code');
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
									break;
								}
							}
						}
					}
				}
		}
		return $flag;
	}
	
	//get applied exam name which is fall on same date(Prafull)
	public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
	{
		$flag=0;
		$msg='';
		$today_date=date('Y-m-d');
		
		$this->db->select('dra_subject_master.*,dra_exam_master.description');
		$this->db->join('dra_exam_master','dra_exam_master.exam_code=dra_subject_master.exam_code');
		$applied_exam_date=$this->master_model->getRecords('dra_subject_master',array('dra_subject_master.exam_code'=>$exam_code,'exam_date >='=>$today_date,'subject_delete'=>'0'));
		if(count($applied_exam_date) > 0)
		{
			$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_member_exam.exam_code AND dra_exam_activation_master.exam_period=dra_member_exam.exam_period');
			$this->db->join('dra_exam_master','dra_exam_master.exam_code=dra_member_exam.exam_code');
			$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid');
			$this->db->where('dra_members.regnumber',$regnumber);
			$getapplied_exam_code=$this->master_model->getRecords('dra_member_exam',array('pay_status'=>'1'),'dra_member_exam.exam_code,dra_exam_master.description');
			if(count($getapplied_exam_code) >0)
			{
				foreach($getapplied_exam_code as $exist_ex_code)
				{	
					$getapplied_exam_date=$this->master_model->getRecords('dra_subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
					if(count($getapplied_exam_date) > 0)
					{
						foreach($getapplied_exam_date as $exist_ex_date)
						{
							foreach($applied_exam_date as $sel_ex_date)
							{
									if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
									{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong>";
										$flag=1;
										break;
									}
								}
								if($flag==1)
								{
										$msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong>";
									break;
								}
							}
						}
					}
				}
			}
		return $msg;
	}
	
	public function add() {
		
		echo "<strong>Applications should not be entered from here.</strong>";
		exit;
		if( isset( $_GET['exCd'] ) ) {
			$enexamcode = trim($_GET['exCd']);
			$examcode = base64_decode($enexamcode);
			if(!intval($examcode)) {
				$this->session->set_flashdata('error','This exam does not exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
			$examcode = intval($examcode);
			//echo 'exam code: '.$examcode;
			//check if exam exists or not
			$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $examcode));
			if( $examcount > 0 ) {
				//check if exam is active or not
				$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $examcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time');
				if( count($examact) > 0 ) {
					//$comp_currdate = date('Y-m-d H:i:s');
					//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
					//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
					$comp_currdate = date('Y-m-d');
					$comp_frmdate = $examact[0]['exam_from_date'];
					$comp_todate = $examact[0]['exam_to_date'];
					if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) 
					{
						/* form submit logic */
						if(isset($_POST['btnSubmit'])) {
							$regno = $this->input->post('reg_no');
							$flag = 1; $message = '';
							//if( empty( $regno ) ) { 
							//Keep files required in case of re-attempt also - 23-01-2017
							
							$is_images_exists = TRUE;
							
							// validate images for new member registartion, Added by Bhagwan Sahane on 26-04-2017 -
							if( empty( $regno ) )
							{
								// check if images uploaded by candidate, Added by Bhagwan Sahane on 25-01-2017 -
								/*$this->form_validation->set_rules('draidproofphoto','Proof of Identity','required');
								$this->form_validation->set_rules('qualicertificate','Qualification Certificate','required');
								$this->form_validation->set_rules('drascannedphoto','Photograph of Candidate','required');
								$this->form_validation->set_rules('drascannedsignature','Signature of Candidate','required');
								$this->form_validation->set_rules('trainingcertificate','Training Certificate','required');*/
								
								$this->form_validation->set_rules('drascannedphoto','Photograph of Candidate','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_drascannedphoto_upload');
								
								$this->form_validation->set_rules('drascannedsignature','Signature of Candidate','file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_drascannedsignature_upload');
								
								$this->form_validation->set_rules('draidproofphoto','Proof of Identity','file_required|file_allowed_type[jpg,jpeg]|file_size_max[25]|callback_draidproofphoto_upload');
								
								$this->form_validation->set_rules('qualicertificate','Qualification Certificate','file_required|file_allowed_type[jpg,jpeg]|file_size_min[50]|file_size_max[100]|callback_qualicertificate_upload');
								
								$this->form_validation->set_rules('trainingcertificate','Training Certificate','file_required|file_allowed_type[jpg,jpeg]|file_size_min[50]|file_size_max[100]|callback_trainingcertificate_upload');
							}
							else	//check if images exists in case of re-attempt, Added by Bhagwan Sahane on 26-04-2017
							{
								// get dra members image path -
								$memRes = $this->master_model->getRecords('dra_members',array('regnumber'=>$regno));
								$memRes = $memRes[0];
								
								// check for state, qualification & idproof in re-attempt (get details) case -
								$_POST['state'] = $memRes['state'];
								$_POST['edu_quali'] = $memRes['qualification'];
								$_POST['idproof'] = $memRes['idproof'];
								// eof code
								
								$scannedphoto = '';
								$scannedsignaturephoto = '';
								$idproofphoto = '';
								$quali_certificate = '';
								$training_certificate = '';
								
								$old_image_path = 'uploads'.$memRes['image_path'];
									
								$new_image_path = 'uploads/iibfdra/';
								
								if($memRes['scannedphoto'] == '')
								{
									if(file_exists($old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg'))
									{
										$scannedphoto = base_url().$old_image_path . "photo/p_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['scannedphoto']))
									{
										$scannedphoto = base_url().$new_image_path . $memRes['scannedphoto'];
									}
								}
								
								if($memRes['scannedsignaturephoto'] == '')
								{
									if(file_exists($old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg'))
									{
										$scannedsignaturephoto = base_url().$old_image_path . "signature/s_" . $memRes['registration_no'].'.jpg';	
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['scannedsignaturephoto']))
									{
										$scannedsignaturephoto = base_url().$new_image_path . $memRes['scannedsignaturephoto'];
									}
								}
								
								if($memRes['idproofphoto'] == '')
								{
									if(file_exists($old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg'))
									{
										$idproofphoto = base_url().$old_image_path . "idproof/pr_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['idproofphoto']))
									{
										$idproofphoto = base_url().$new_image_path . $memRes['idproofphoto'];	
									}
								}
								
								if($memRes['quali_certificate'] == '')
								{
									if(file_exists($old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg'))
									{
										$quali_certificate = base_url().$old_image_path . "degree_cert/degre_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['quali_certificate']))
									{
										$quali_certificate = base_url().$new_image_path . $memRes['quali_certificate'];
									}
								}
								
								if($memRes['training_certificate'] == '')
								{
									if(file_exists($old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg'))
									{
										$training_certificate = base_url().$old_image_path . "training_cert/traing_" . $memRes['registration_no'].'.jpg';
									}
								}
								else
								{
									if(file_exists($new_image_path . $memRes['training_certificate']))
									{
										$training_certificate = base_url().$new_image_path . $memRes['training_certificate'];
									}
								}
								
								// check if images missing
								if($scannedphoto == '' || $scannedsignaturephoto == '' || $idproofphoto == '' || $quali_certificate == '' || $training_certificate == '')
								{
									// image(s) not available
									$is_images_exists = FALSE;
									
									$this->session->set_flashdata('error','Images are missing in your profile, kindly apply again with new application.');	
								}
							}
							//eof code
							
							//}
							$this->form_validation->set_rules('exam_medium','Medium of Examination','required');
							$this->form_validation->set_rules('firstname','First Name','trim|required');
							$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
							$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
							//do not keep last name field compulsory - 21-01-2017
							//$this->form_validation->set_rules('lastname','Last Name','trim|required');
							$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[30]');
							$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[30]');
							$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
							$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
							
							$this->form_validation->set_rules('state','State','trim|required');
							
							$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
							$this->form_validation->set_rules('dob1','Date of Birth','trim|required');
							$this->form_validation->set_rules('gender','Gender','required');
							$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
							$this->form_validation->set_rules('email','Email','valid_email|required|trim|max_length[45]');
							$this->form_validation->set_rules('exam_center','Exam Center','required|trim');
							$this->form_validation->set_rules('center_code','Center Code','required|trim');
							$this->form_validation->set_rules('inst_name','Institute Name','required');
							$this->form_validation->set_rules('training_from','Training From','required');
							$this->form_validation->set_rules('training_to','Training to','required|callback_comparefromtoDates');
							
							$this->form_validation->set_rules('edu_quali','Qualification','required');
							
							$this->form_validation->set_rules('idproof','Id Proof','required');
							
							$this->form_validation->set_rules('declaration1','Declaration','required');
							$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapply');
							$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
							$this->form_validation->set_rules('phone','Phone No','max_length[8]');
							
							$outputphoto1 = $outputsign1 = $outputidproof1 = $outputtcertificate1 = $outputqualicertificate1 = '';
							$photofnm = $signfnm = $idfnm = $trgfnm = $qualifnm = '';
							$photo_flg = $signature_flg = $id_flg = $tcertificate_flg = $qualicertificate_flg = 'N';
							
							if($this->form_validation->run() == TRUE && $is_images_exists) {
								$examcd = $this->input->post('examcd');
								if( !empty( $examcd ) ) { // check if exam code is not empty
								
									$date = date('Y-m-d h:i:s');
									
									$image_size_error = 0;
									$image_size_error_message = array();
									
									// generate dynamic photo
									
									//if( !empty($input) ) {
									if($this->input->post('hiddenphoto') != '')
									{
										$size = @getimagesize($_FILES['drascannedphoto']['tmp_name']);
										if($size)
										{
											$input = $this->input->post('hiddenphoto');
											
											$tmp_nm = strtotime($date).rand(0,100);
											$outputphoto = getcwd()."/uploads/iibfdra/p_".$tmp_nm.".jpg";
											$outputphoto1 = base_url()."uploads/iibfdra/p_".$tmp_nm.".jpg";
											file_put_contents($outputphoto, file_get_contents($input));
											$photofnm = "p_".$tmp_nm.".jpg";
											$photo_flg = 'Y';
										}
										else
										{
											$image_size_error = 1;
											$image_size_error_message[] = 'Invalid photo file uploaded.';	
										}
									}
									
									// generate dynamic scan signature
									
									//if( !empty($inputsignature) ) {
									if($this->input->post('hiddenscansignature') != '')
									{
										$size = @getimagesize($_FILES['drascannedsignature']['tmp_name']);
										if($size)
										{
											$inputsignature = $_POST["hiddenscansignature"];
											
											$tmp_signnm = strtotime($date).rand(0,100);
											$outputsign = getcwd()."/uploads/iibfdra/s_".$tmp_signnm.".jpg";
											$outputsign1 = base_url()."uploads/iibfdra/s_".$tmp_signnm.".jpg";
											file_put_contents($outputsign, file_get_contents($inputsignature));
											$signfnm = "s_".$tmp_signnm.".jpg";
											$signature_flg = 'Y';
										}
										else
										{
											$image_size_error = 1;
											$image_size_error_message[] = 'Invalid signature file uploaded.';
										}
									}
									
									// generate dynamic id proof
									
									//if( !empty($inputidproofphoto) ) {
									if($this->input->post('hiddenidproofphoto') != '')
									{
										$size = @getimagesize($_FILES['draidproofphoto']['tmp_name']);
										if($size)
										{
											$inputidproofphoto = $_POST["hiddenidproofphoto"];
											
											$tmp_inputidproof = strtotime($date).rand(0,100);
											$outputidproof = getcwd()."/uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
											$outputidproof1 = base_url()."uploads/iibfdra/pr_".$tmp_inputidproof.".jpg";
											file_put_contents($outputidproof, file_get_contents($inputidproofphoto));
											$idfnm = "pr_".$tmp_inputidproof.".jpg";
											$id_flg = 'Y';
										}
										else
										{
											$image_size_error = 1;
											$image_size_error_message[] = 'Invalid id proof file uploaded.';
										}
									}
									
									// generate dynamic training certificate 
									
									//if( !empty($input_tcertificatephoto) ) {
									if($this->input->post('hiddentrainingcertificate') != '')
									{
										$size = @getimagesize($_FILES['trainingcertificate']['tmp_name']);
										if($size)
										{
											$input_tcertificatephoto = $_POST["hiddentrainingcertificate"];
											
											$tmp_tcertificate = strtotime($date).rand(0,100);
											$outputtcertificate = getcwd()."/uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
											$outputtcertificate1 = base_url()."uploads/iibfdra/traing_".$tmp_tcertificate.".jpg";
											file_put_contents($outputtcertificate, file_get_contents($input_tcertificatephoto));
											$trgfnm = "traing_".$tmp_tcertificate.".jpg";
											$tcertificate_flg = 'Y';
										}
										else
										{
											$image_size_error = 1;	
											$image_size_error_message[] = 'Invalid training certificate file uploaded.';
										}
									}
									
									// generate dynamic qualification certificate
									
									//if( !empty($input_qualicertificate) ) {
									if($this->input->post('hiddenqualicertificate') != '')
									{
										$size = @getimagesize($_FILES['qualicertificate']['tmp_name']);
										if($size)
										{
											$input_qualicertificate = $_POST["hiddenqualicertificate"];
											
											$tmp_qualicertificate = strtotime($date).rand(0,100);
											$outputqualicertificate = getcwd()."/uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
											$outputqualicertificate1 = base_url()."uploads/iibfdra/degre_".$tmp_qualicertificate.".jpg";
											file_put_contents($outputqualicertificate, file_get_contents($input_qualicertificate));
											$qualifnm = "degre_".$tmp_qualicertificate.".jpg";
											$qualicertificate_flg = 'Y';
										}
										else
										{
											$image_size_error = 1;
											$image_size_error_message[] = 'Invalid qualification certificate file uploaded.';	
										}
									}
									// eof file upload code
									
									// check if invalid image error
									if($image_size_error == 1)
									{
										$this->session->set_flashdata('error',implode('<br>', $image_size_error_message));
										redirect(base_url().'iibfdra/DraExam/add/?exCd='.base64_encode($examcd));
									}
									//eof code
									
									// ====
									
									$exam_fees = 0;
									$exam_period = $exam_date = $exam_time = '';
									
									$instdata = $this->session->userdata('dra_institute');
									
									//new candidate
									$regno = $this->input->post('reg_no');
									if( empty( $regno ) ) {
										$registrationtype = 'NM';
										$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
										$this->db->where('dra_exam_master.exam_delete','0');
										$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
										$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
										$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
										$this->db->where("dra_misc_master.misc_delete",'0');
										$this->db->where("dra_subject_master.subject_delete",'0');
										$this->db->where("dra_fee_master.fee_delete",'0');
										$this->db->where('dra_fee_master.member_category','NM');
										$this->db->where('dra_fee_master.group_code','B1');
										$this->db->where('dra_fee_master.exempt','NE'); // get only NE states fees, added by Bhagwan Sahane, on 05-07-2017
										$examRes = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcd));
										if( count($examRes) > 0 ) {
											$result = $examRes[0];
											
											// code added for GST, By Bhagwan Sahane, on 03-07-2017
											//$exam_fees = $result['fee_amount'];
											if($instdata['ste_code'] == 'MAH')
											{
												$exam_fees = $result['cs_tot'];
												$loop = 1;
											}
											else
											{
												$exam_fees = $result['igst_tot'];
												$loop = 2;
											}
											// eof code added for GST
											
											//echo $loop.' - Inst. State : '.$instdata['ste_code'].' | Exam Fees : '.$exam_fees; die();
											
											$exam_period = $result['miscex_period'];
											$exam_date = $result['exam_date'];
											$exam_time = $result['exam_time'];
										}
									}  else {
										/* registered member / non-member but no dra member */
										$membertype = $this->input->post('membertype');
										if( $membertype == 'normal_member' ) {
											$registrationtype = $this->input->post('memtype');
											$this->db->select('dra_fee_master.*,dra_exam_master.*,dra_misc_master.*,dra_misc_master.exam_period as miscex_period, dra_subject_master.*');
											$this->db->where('dra_exam_master.exam_delete','0');
											$this->db->join('dra_fee_master','dra_fee_master.exam_code=dra_exam_master.exam_code');
											$this->db->join('dra_misc_master','dra_misc_master.exam_code=dra_exam_master.exam_code');
											$this->db->join('dra_subject_master','dra_subject_master.exam_code=dra_exam_master.exam_code');
											$this->db->where("dra_misc_master.misc_delete",'0');
											$this->db->where("dra_subject_master.subject_delete",'0');
											$this->db->where("dra_fee_master.fee_delete",'0');
											$this->db->where('dra_fee_master.member_category',$registrationtype);
											$this->db->where('dra_fee_master.group_code','B1');
											$this->db->where('dra_fee_master.exempt','NE'); // get only NE states fees, added by Bhagwan Sahane, on 05-07-2017
											$examRes = $this->master_model->getRecords('dra_exam_master',array('dra_exam_master.exam_code'=>$examcd));
											if( count($examRes) > 0 ) {
												$result = $examRes[0];
												
												// code added for GST, By Bhagwan Sahane, on 03-07-2017
												//$exam_fees = $result['fee_amount'];
												if($instdata['ste_code'] == 'MAH')
												{
													$exam_fees = $result['cs_tot'];
													$loop = 1;
												}
												else
												{
													$exam_fees = $result['igst_tot'];
													$loop = 2;
												}
												// eof code added for GST
												
												//echo $loop.' - Inst. State : '.$instdata['ste_code'].' | Exam Fees : '.$exam_fees; die();
												
												$exam_period = $result['miscex_period'];
												$exam_date = $result['exam_date'];
												$exam_time = $result['exam_time'];
											}
										} else { //DRA user (Re-Attempt Case)
											$registrationtype = $this->input->post('memtype');
											//$registrationtype = 'NM';
											$this->db->join('dra_exam_activation_master','dra_exam_activation_master.exam_code=dra_eligible_master.exam_code AND dra_exam_activation_master.exam_period=dra_eligible_master.eligible_period');
											$this->db->where("dra_eligible_master.eligible_delete",'0');
											$this->db->where("dra_exam_activation_master.exam_activation_delete",'0');
											$check_eligibility_for_applied_exam = $this->master_model->getRecords('dra_eligible_master',array('dra_eligible_master.exam_code'=>$examcd,'member_type'=>$registrationtype,'member_no'=>$regno));
											//print_r($this->db->last_query()); exit;
											if(count($check_eligibility_for_applied_exam) > 0)
											{ //found in eligible master
												/*if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v') { //pass/debard/valid
													$flag=0;
													$message=$check_eligibility_for_applied_exam[0]['remark'];
												
												}*/ 
												if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v'|| 
												($check_eligibility_for_applied_exam[0]['app_category']=='' && $check_eligibility_for_applied_exam[0]['exam_status']=='F')) { //pass/debard/valid
													

													if($check_eligibility_for_applied_exam[0]['app_category']==''){
													$flag=0;
													$message='Fee is not defined for this candidate, Please contact to IIBF.';
													}else{
													$flag=0;
													$message=$check_eligibility_for_applied_exam[0]['remark'];
													}

													
												
												}
												else { //status - fail and irrespective of app category whether it is R or subsequent attempt let candidate apply
													$check=$this->examapplied($regno,$examcd);
													if(!$check)
													{
														$check_date=$this->examdate($regno,$examcd);
														
														if(!$check_date) {
															$flag=1;
															//apply exam
															$period = $check_eligibility_for_applied_exam[0]['eligible_period'];
															$mode = $this->input->post('exam_mode');
															$medium = $this->input->post('exam_medium');
															$center = $this->input->post('exam_center');
															$trainingfrm = $this->input->post('training_from');
															$trainingto	= $this->input->post('training_to');
														
															/* Get Group Code start - Bhushan */
															$this->db->where('member_no', $regno);
															$group_code_sql = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
															$group_code = $group_code_sql[0]['app_category'];
															if(count($group_code_sql) > 0)
															{
																$group_code = $group_code_sql[0]['app_category'];
																if($group_code == "R")
																{
																	$group_code = "B1";
																}
															}
															else
															{
																	$group_code = "B1";														
															}
															/* Get Group Code Close - Bhushan */
															$this->db->join("dra_members","dra_members.regnumber=".$regno);
															$this->db->join("dra_fee_master","dra_fee_master.exam_period=dra_subject_master.exam_period AND dra_fee_master.exam_code = dra_subject_master.exam_code AND dra_fee_master.syllabus_code = dra_fee_master.syllabus_code AND dra_fee_master.part_no = dra_subject_master.part_no");
															$this->db->join("dra_eligible_master","dra_eligible_master.exam_code=dra_subject_master.exam_code AND dra_eligible_master.eligible_period=dra_subject_master.exam_period AND dra_eligible_master.part_no = dra_subject_master.part_no");
															$this->db->where("dra_fee_master.member_category",$registrationtype);
															/* Group Code Added - Bhushan */
															$this->db->where("dra_fee_master.group_code",$group_code);
															/* Group Code Added Close - Bhushan */
															$this->db->where("dra_eligible_master.member_no",$regno);
															$this->db->where("dra_subject_master.subject_delete",'0');
															$this->db->where("dra_fee_master.fee_delete",'0');
															$this->db->where("dra_members.isdeleted",'0');
															$this->db->where("dra_eligible_master.eligible_delete",'0');
															$this->db->where('dra_fee_master.exempt','NE'); // get only NE states fees, added by Bhagwan Sahane, on 05-07-2017
															$examinfo = $this->master_model->getRecords('dra_subject_master');
															//die($this->db->last_query());
															$exdate = $extime = '';
															$regid = 0;$fees = 0;
															if( count( $examinfo ) > 0 ) {
																
																// code added for GST, By Bhagwan Sahane, on 03-07-2017
																//$fees = $examinfo[0]['fee_amount']; //take fee from dra_fee_master
																if($instdata['ste_code'] == 'MAH')
																{
																	$fees = $examinfo[0]['cs_tot'];
																	$loop = 1;
																}
																else
																{
																	$fees = $examinfo[0]['igst_tot'];
																	$loop = 2;
																}
																// eof code added for GST
																
																//echo $loop.' - Inst. State : '.$instdata['ste_code'].' | Exam Fees : '.$fees; die();
																
																$exdate = $examinfo[0]['exam_date'];
																$extime = $examinfo[0]['exam_time'];
																$regid = $examinfo[0]['regid'];	
															}
															
															//update user info changed in dra_members table -> added on 23-01-2017
															/*$pfile = $signfile = $prooffile = $trgfile = $qualifile = '';
															if( !empty( $photofnm ) ) {
																$pfile = 'p_'.$regno.'.jpg';
																@rename("./uploads/iibfdra/".$photofnm,"./uploads/iibfdra/".$pfile);
															}
															if( !empty( $signfnm ) ) {
																$signfile = 's_'.$regno.'.jpg';
																@rename("./uploads/iibfdra/".$signfnm,"./uploads/iibfdra/".$signfile);
															}
															if( !empty( $idfnm ) ) {
																$prooffile = 'pr_'.$regno.'.jpg';
																@rename("./uploads/iibfdra/".$idfnm,"./uploads/iibfdra/".$prooffile);
															}
															if( !empty( $trgfnm ) ) {
																$trgfile = 'traing_'.$regno.'.jpg';
																@rename("./uploads/iibfdra/".$trgfnm,"./uploads/iibfdra/".$trgfile);
															}
															if( !empty( $qualifnm ) ) {
																$qualifile = 'degre_'.$regno.'.jpg';
																@rename("./uploads/iibfdra/".$qualifnm,"./uploads/iibfdra/".$qualifile);
															}*/
															
															$updatecandinfoarr = array(
																/*'namesub' => $this->input->post('sel_namesub'),
																'firstname' => $this->input->post('firstname'),
																'middlename' => $this->input->post('middlename'),
																'lastname' => $this->input->post('lastname'),
																'address1' => $this->input->post('addressline1'),
																'address2' => $this->input->post('addressline2'),
																'city' => $this->input->post('city'),
																'state' => $this->input->post('state'),
																'district' => $this->input->post('district'),
																'pincode' => $this->input->post('pincode'),
																'dateofbirth' => $this->input->post('dob1'),
																'gender' => $this->input->post('gender'),
																'stdcode' => $this->input->post('stdcode'),
																'phone' => $this->input->post('phone'),
																'mobile' => $this->input->post('mobile'),
																'associatedinstitute' => $this->input->post('inst_name'),
																'inst_code' => $this->input->post('institute_code'),
																'email' => $this->input->post('email'),
																'qualification' => $this->input->post('edu_quali'),
																'idproof' => $this->input->post('idproof'),
																'excode' => $examcd,*/
																'editedon' => date('Y-m-d H:i:s'),
																're_attempt' => 1,
															);
															
															/*if( !empty( $pfile ) ) {
																$updatecandinfoarr['scannedphoto'] = $pfile;
																$updatecandinfoarr['photo_flg'] = 'Y';
															}
															if( !empty( $signfile ) ) {
																$updatecandinfoarr['scannedsignaturephoto'] = $signfile;
																$updatecandinfoarr['signature_flg'] = 'Y';
															}
															if( !empty( $prooffile ) ) {
																$updatecandinfoarr['idproofphoto'] = $prooffile;
																$updatecandinfoarr['id_flg'] = 'Y';
															}
															if( !empty( $trgfile ) ) {
																$updatecandinfoarr['training_certificate'] = $trgfile;
																$updatecandinfoarr['tcertificate_flg'] = 'Y';
															}
															if( !empty( $qualifile ) ) {
																$updatecandinfoarr['quali_certificate'] = $qualifile;
																$updatecandinfoarr['qualicertificate_flg'] = 'Y';
															}*/
															
															$this->master_model->updateRecord('dra_members',$updatecandinfoarr,  array('regnumber'=>$regno));
															//apply for exam
															$this->applyexam($regid, $examcd, $mode, $medium, $period, $center, $fees, $exdate, $extime, $trainingfrm, $trainingto);
															
														}else {
															$message = $this->get_alredy_applied_examname($regno,$examcd);
															$flag = 0;
														}
													} else {
														$message = 'You have already applied for the examination';
														$flag = 0;
													}
												} 
											} else { // not found in eligible master
												$this->session->set_flashdata('error','You have already applied for this examination');
											}
											/*else {
												$check=$this->examapplied($regno,$examcd);
												if(!$check)
												{
													$check_date=$this->examdate($regno,$examcd);
													if(!$check_date)
													{
														//apply exam
															$fees = $check_eligibility_for_applied_exam[0]['fees'];
															$period = $check_eligibility_for_applied_exam[0]['eligible_period'];
															$mode = $this->input->post('exam_mode');
															$medium = $this->input->post('exam_medium');
															$center = $this->input->post('exam_center');
															$trainingfrm = $this->input->post('training_from');
															$trainingto	= $this->input->post('training_to');
															$this->db->join("dra_eligible_master","dra_eligible_master.exam_code=dra_subject_master.exam_code AND dra_eligible_master.eligible_period=dra_subject_master.exam_period");
															$this->db->where("dra_eligible_master.member_no",$regno);
															$examinfo = $this->master_model->getRecords('dra_subject_master');
															$exdate = $extime = '';
															if( count( $examinfo ) > 0 ) {
																$exdate = $examinfo[0]['exam_date'];
																$extime = $examinfo[0]['exam_time'];	
															}
															$date = $examinfo[];																														   											$this->applyexam($regid, $examcd, $mode, $medium, $period, $center, $fees, $exdate, $extime, $trainingfrm, $trainingto);
													}
													else
													{
														$message=$this->get_alredy_applied_examname($regno,$examcd);
														//$message='Exam fall in same date';
														$flag=0;
													}
												}
												else
												{
													$message='You have already applied for the examination';
													$flag=0;
												}
											}*/	
										}//dra user
									}//if reg_no provided
									
									$membertype = $this->input->post('membertype');
									if( empty($regno) || ( !empty( $regno ) && $membertype == 'normal_member' ) ) { //insert record for ordinary member / non-member and new candidate in dra_members
										$insert_data = array(	
											'namesub'		=> $this->input->post('sel_namesub'),
											'firstname'		=> $this->input->post('firstname'),
											'middlename'	=> $this->input->post('middlename'),
											'lastname'		=> $this->input->post('lastname'),
											'address1'		=> $this->input->post('addressline1'),
											'address2'		=> $this->input->post('addressline2'),
											'city'			=> $this->input->post('city'),
											'state'			=> $this->input->post('state'),
											'district'		=> $this->input->post('district'),
											'pincode'		=> $this->input->post('pincode'),
											'dateofbirth'	=> $this->input->post('dob1'),
											'gender'		=> $this->input->post('gender'),
											'stdcode'		=> $this->input->post('stdcode'),
											'phone'			=> $this->input->post('phone'),
											'mobile'		=> $this->input->post('mobile'),
											'associatedinstitute' => $this->input->post('inst_name'),
											'inst_code'		=> $this->input->post('institute_code'),
											'email' 		=> $this->input->post('email'),
											'qualification'	=> $this->input->post('edu_quali'),
											'idproof'		=> $this->input->post('idproof'),
											'excode' 		=> $examcd,
											'registrationtype' => $registrationtype,
											'aadhar_no' 	=> $this->input->post('aadhar_no'), // added by Bhagwan Sahane, on 06-05-2017
											'createdon' 	=> date('Y-m-d H:i:s'),
											'scannedphoto' 	=> $photofnm,
											'scannedsignaturephoto' => $signfnm,
											'idproofphoto' 	=> $idfnm,
											'training_certificate' => $trgfnm,
											'quali_certificate' => $qualifnm,
											'photo_flg' 	=> $photo_flg,
											'signature_flg' => $signature_flg,
											'id_flg' 		=> $id_flg,
											'tcertificate_flg' => $tcertificate_flg,
											'qualicertificate_flg' => $qualicertificate_flg 
										);	
										if($this->master_model->insertRecord('dra_members',$insert_data)) {
											
											$regid = $this->db->insert_id();
											
											log_dra_user($log_title = "Add DRA Member Successful", $log_message = serialize($insert_data));
											$insert_exam = array(
												'regid' => $regid,
												'exam_code' => $examcd,
												'exam_mode' => $this->input->post('exam_mode'),
												'exam_medium' => $this->input->post('exam_medium'),
												'exam_period' => $exam_period,
												'exam_center_code' => $this->input->post('exam_center'),
												'exam_fee' => $exam_fees,
												'exam_date' => $exam_date,
												'exam_time' => $exam_time,
												'training_from'	=> $this->input->post('training_from'),
												'training_to'	=> $this->input->post('training_to'),
												'created_on' => date('Y-m-d H:i:s'),
												'pay_status' => 2
											);
											if( $this->master_model->insertRecord('dra_member_exam',$insert_exam) ) {
												log_dra_user($log_title = "Add DRA Exam Applicant Successful", $log_message = serialize($insert_exam));
												$this->session->set_flashdata('success','Record added successfully');
												redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcd));
											} else {
												log_dra_user($log_title = "Add DRA Exam Applicant Unsuccessful", $log_message = serialize($insert_exam));
												$this->session->set_flashdata('error','Error occured while adding record');
												redirect(base_url().'iibfdra/DraExam/add/?exCd='.base64_encode($examcd));
											}
										}
										else {
											log_dra_user($log_title = "Add DRA Exam Applicant Unsuccessful", $log_message = serialize($insert_data));
											$this->session->set_flashdata('error','Error occured while adding record');
											redirect(base_url().'iibfdra/DraExam/add/?exCd='.base64_encode($examcd));
										}
									} //for member/NM/new candidate
									else { //re-attempt
										if( $flag == 0 ) {
											$this->session->set_flashdata('error',$message);
											//die($message);
										}
									}
								} // if not empty exam code
							} else {
								$data['validation_errors'] = validation_errors();
							}
						}
						
						$data['breadcrumb'] = '<ol class="breadcrumb"><li><a href="'.base_url().'iibfdra/InstituteHome"><i class="fa fa-dashboard"></i> Home</a></li>
							<li><a href="'.base_url().'iibfdra/'.$this->router->fetch_class().'">Manage DRA Exam</a></li>
							<li class="active">Add</li>
						</ol>';
						/* Get required data for DRA exam */
						$states = $this->master_model->getRecords('state_master');
						
						//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
						$this->db->not_like('name','Election Voters card');
						$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
						
						$medium_master = $this->master_model->getRecords('dra_medium_master',array('exam_code'=>$examcode));
						$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$examcode),'',array('center_name'=>'ASC'));
						$this->load->helper('captcha');
						$this->session->unset_userdata("draexamcaptcha");
						$this->session->set_userdata("draexamcaptcha", rand(1, 100000));
						$vals = array(
							'img_path' => './uploads/applications/',
							'img_url' => '../../../uploads/applications/'
						);
						$cap = create_captcha($vals);
						$_SESSION["draexamcaptcha"] = $cap['word']; 
						$data['states'] = $states;
						$data['image'] = $cap['image'];
						$data['idtype_master'] = $idtype_master;
						$data['medium_master'] = $medium_master;
						$data['center_master'] = $center_master;
						$data['data_examcode'] = $examcode;
						$data["middle_content"] = 'draexam_add';
						//get exam date and training period limit from subject master and misc master
						$examdt = $this->master_model->getValue('dra_subject_master',array('exam_code' => $examcode), 'exam_date');
						$traininglimit = $this->master_model->getValue('dra_misc_master',array('exam_code' => $examcode), 'trg_value');
						$data["examdt"] = $examdt;
						$data["traininglimit"] = $traininglimit;
						/* send active exams for display in sidebar */
						$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
						$res = $this->master_model->getRecords("dra_exam_master a");
						$data['active_exams'] = $res;
						$this->load->view('iibfdra/common_view',$data);
					} else {//if exam is not active
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/InstituteHome/dashboard');	
					}
				} else { //if exam not found in exam activation master then redirect to home
					$this->session->set_flashdata('error','This exam is not active');
					redirect(base_url().'iibfdra/InstituteHome/dashboard');	
				}
			} else { // if exam does not exists redirect to dashboard
				$this->session->set_flashdata('error','This exam does not exists');
				redirect(base_url().'iibfdra/InstituteHome/dashboard');
			}
		} else { 
			redirect(base_url().'iibfdra/');	
		}
	}
	
	// callback to validate drascannedphoto, Added By Bhagwan Sahane, on 27-04-2017
	function drascannedphoto_upload(){
	      if($_FILES['drascannedphoto']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('drascannedphoto_upload', "No Photograph of Candidate selected");
	        return false;
	    }
	}
	
	// callback to validate drascannedsignature, Added By Bhagwan Sahane, on 27-04-2017
	function drascannedsignature_upload(){
	      if($_FILES['drascannedsignature']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('drascannedsignature_upload', "No Signature of Candidate selected");
	        return false;
	    }
	}
	
	// callback to validate draidproofphoto, Added By Bhagwan Sahane, on 27-04-2017
	function draidproofphoto_upload(){
	      if($_FILES['draidproofphoto']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('draidproofphoto_upload', "No Proof of Identity selected");
	        return false;
	    }
	}
	
	// callback to validate qualicertificate, Added By Bhagwan Sahane, on 27-04-2017
	function qualicertificate_upload(){
	      if($_FILES['qualicertificate']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('qualicertificate_upload', "No Qualification Certificate selected");
	        return false;
	    }
	}
	
	// callback to validate trainingcertificate, Added By Bhagwan Sahane, on 27-04-2017
	function trainingcertificate_upload(){
	      if($_FILES['trainingcertificate']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('trainingcertificate_upload', "No Training Certificate selected");
	        return false;
	    }
	}
	// eof code
	
	/* Added so that training from date should not be greater than training to date - 18-02-2017 */
	public function comparefromtoDates() {
		$fromdt = strtotime($this->input->post('training_from'));
		$todt = strtotime($this->input->post('training_to'));
		$traininglimit = $this->input->post('traininglimit');
		$examdate = $this->input->post('examdate');
		$currdate = date('Y-m-d');
		$currdatetotime = strtotime($currdate);
		if($fromdt > $todt)
		{
			$this->form_validation->set_message('comparefromtoDates','Training from date must be less than training to date');
			return false;
		}
		if( $todt > $currdatetotime ) {
			$this->form_validation->set_message('comparefromtoDates','Training from date must be less than today');
			return false;
		}
		if( $fromdt > $currdatetotime ) {
			$this->form_validation->set_message('comparefromtoDates','Training to date must be less than today');
			return false;
		}
		$traininglimit = (int)$traininglimit;
		$datetillvalid = strtotime("+".$traininglimit." days", $todt);
		$datetillvalid = date("Y-m-d", $datetillvalid);
		//die($examdate." ".$datetillvalid);
		if( $examdate > $datetillvalid ) {
			$this->form_validation->set_message('comparefromtoDates','Your training date is expired, kindly apply again with new application.');
			return false;
		}
	}
	public function edit()
	{
		$data = array();
		$data['examRes'] = array();
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		//check if id is integer in url if not regdirect to home
		if(!intval($id)) {
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/InstituteHome/dashboard');
		}
		$id = intval($id);
		$this->db->join('dra_members','dra_members.regid=dra_member_exam.regid');
		$this->db->where('dra_member_exam.pay_status != ', 1);
		$this->db->where('dra_member_exam.pay_status != ', 3);
		$examRes = $this->master_model->getRecords('dra_member_exam',array('id'=>$id,'dra_memberexam_delete' => 0));
		//print_r( $this->db->last_query() ); die();
		if(count($examRes))
		{
			$data['examRes'] = $examRes[0];
		} else { //check entered id details are present in db if not redirect to home
			$this->session->set_flashdata('error','No such applicant exists');
			redirect(base_url().'iibfdra/InstituteHome/dashboard');
		}
		if(isset($_POST['btnSubmit']))
		{
			$this->form_validation->set_rules('exam_medium','Medium of Examination','required');
			$this->form_validation->set_rules('firstname','First Name','trim|required|max_length[30]');
			$this->form_validation->set_rules('middlename','Middle Name','trim|max_length[30]');
			$this->form_validation->set_rules('lastname','Last Name','trim|max_length[30]');
			$this->form_validation->set_rules('addressline1','Address Line1','trim|required|max_length[30]');
			$this->form_validation->set_rules('addressline2','Address Line2','trim|max_length[30]');
			$this->form_validation->set_rules('city','City','trim|required|max_length[30]');
			$this->form_validation->set_rules('district','District','trim|required|max_length[30]');
			$this->form_validation->set_rules('state','State','trim|required');
			$this->form_validation->set_rules('pincode','Pin Code','trim|required|max_length[6]');
			$this->form_validation->set_rules('dob1','Date of Birth','trim|required');
			$this->form_validation->set_rules('gender','Gender','required');
			$this->form_validation->set_rules('mobile','Mobile No.','required|max_length[10]|min_length[10]');
			$this->form_validation->set_rules('email','Email','valid_email|required|trim');
			$this->form_validation->set_rules('exam_center','Exam Center','required|trim');
			$this->form_validation->set_rules('exam_mode','Exam Mode','required');
			$this->form_validation->set_rules('center_code','Center Code','required|trim');
			$this->form_validation->set_rules('inst_name','Institute Name','required');
			$this->form_validation->set_rules('training_from','Training From','required');
			$this->form_validation->set_rules('training_to','Training to','required|callback_comparefromtoDates');
			$this->form_validation->set_rules('edu_quali','Qualification','required');
			$this->form_validation->set_rules('idproof','Id Proof','required');
			$this->form_validation->set_rules('declaration1','Declaration','required');
			$this->form_validation->set_rules('code','Security Code','required|callback_check_captcha_draexamapplyedt');
			$this->form_validation->set_rules('stdcode','STD Code','max_length[5]');
			$this->form_validation->set_rules('phone','Phone No','max_length[8]');
							
			if($this->form_validation->run()==TRUE)
			{
				$dmemexam_id = $this->input->post('dmemexam_id');
				$update_data = array(	
					'namesub' => $this->input->post('sel_namesub'),
					'firstname'		=>$this->input->post('firstname'),
					'middlename'		=>$this->input->post('middlename'),
					'lastname'		=>$this->input->post('lastname'),
					'address1'		=>$this->input->post('addressline1'),
					'address2'		=>$this->input->post('addressline2'),
					'city'				=>$this->input->post('city'),
					'state'				=>$this->input->post('state'),
					'district'			=>$this->input->post('district'),
					'pincode'				=>$this->input->post('pincode'),
					'dateofbirth'				=>$this->input->post('dob1'),
					'gender'				=>$this->input->post('gender'),
					'stdcode'			=>$this->input->post('stdcode'),
					'phone'			=>$this->input->post('phone'),
					'mobile'	=>$this->input->post('mobile'),
					'aadhar_no'	=> $this->input->post('aadhar_no'),	// added by Bhagwan Sahane, on 06-05-2017
					'associatedinstitute'	=>$this->input->post('inst_name'),
					'inst_code'	=>$this->input->post('institute_code'),
					'email' => $this->input->post('email'),
					'qualification'	=>$this->input->post('edu_quali'),
					'idproof'	=>$this->input->post('idproof'),
				);
				//print_r($update_data);
				$regid = $examRes[0]['regid'];
				if($this->master_model->updateRecord('dra_members',$update_data,  array('regid'=>$regid)))
				{
					$update_exam = array(
						'exam_mode' => $this->input->post('exam_mode'),
						'exam_medium' => $this->input->post('exam_medium'),
						'exam_center_code' => $this->input->post('exam_center'),
						'training_from'	=> $this->input->post('training_from'),
						'training_to'	=> $this->input->post('training_to'),
					);
					if( $this->master_model->updateRecord('dra_member_exam',$update_exam, array('id'=>$dmemexam_id)) ) {
						$updatedarr = array_merge($update_data,$update_exam);
						$desc['updated_data'] = $updatedarr;
						$desc['old_data'] = $examRes[0];
						log_dra_user($log_title = "Edit DRA Exam Application Successful", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Record updated successfully');
						//redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
						
						$examcode = $examRes[0]['exam_code'];
						
						redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcode));
					} else {
						$updatedarr = array_merge($update_data,$update_exam);
						$desc['updated_data'] = $updatedarr;
						$desc['old_data'] = $examRes[0];
						log_dra_user($log_title = "Edit DRA Exam Application Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating record');
						redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
					}
				}
				else
				{
					$desc['updated_data'] = $update_data;
					$desc['old_data'] = $examRes[0];
					log_dra_user($log_title = "Edit DRA Exam Application Unsuccessful", $log_message = serialize($desc));
					$this->session->set_flashdata('error','Error occured while updating record');
					redirect(base_url().'iibfdra/DraExam/edit/'.$dmemexam_id);
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
		}
		$examcode = $examRes[0]['exam_code'];
		$states = $this->master_model->getRecords('state_master');
		
		//$this->db->not_like('name','Aadhaar id'); // activated again by Bhagwan Sahane, on 13-07-2017
		$this->db->not_like('name','Election Voters card');
		$idtype_master = $this->master_model->getRecords('dra_idtype_master','','',array('id' => 'ASC'));
		
		$medium_master = $this->master_model->getRecords('dra_medium_master',array('exam_code'=>$examcode));
		$center_master = $this->master_model->getRecords('dra_center_master',array('exam_name'=>$examcode),'',array('center_name'=>'ASC'));
		$data['states'] = $states;
		$data['idtype_master'] = $idtype_master;
		$data['medium_master'] = $medium_master;
		$data['center_master'] = $center_master;
		$data['dramemexam_id'] = $id;
		$data["middle_content"] = 'draexam_edit';
		$this->load->helper('captcha');
		$this->session->unset_userdata("draexamedtcaptcha");
		$this->session->set_userdata("draexamedtcaptcha", rand(1, 100000));
		$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => '../../../uploads/applications/',
		);
		$cap = create_captcha($vals);
		$_SESSION["draexamedtcaptcha"] = $cap['word']; 
		$data['image'] = $cap['image'];
		//get exam date and training period limit from subject master and misc master
		$examdt = $this->master_model->getValue('dra_subject_master',array('exam_code' => $examcode), 'exam_date');
		$traininglimit = $this->master_model->getValue('dra_misc_master',array('exam_code' => $examcode), 'trg_value');
		$data["examdt"] = $examdt;
		$data["traininglimit"] = $traininglimit;
		/* send active exams for display in sidebar */
		$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
		$res = $this->master_model->getRecords("dra_exam_master a");
		$data['active_exams'] = $res;
		$this->load->view('iibfdra/common_view',$data);
	}
	public function delete() {
		$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);$last = $this->uri->total_segments();
		$id = $this->uri->segment($last);
		$examcode = $this->master_model->getValue('dra_member_exam',array('id'=>$id),'exam_code');
		if(is_numeric($id)) {
			$update_data = array('dra_memberexam_delete' => 1);
			if($this->master_model->updateRecord('dra_member_exam', $update_data, array('id'=>$id))) {
				log_dra_user($log_title = "Delete DRA Exam Applicant Successful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('success','Record deleted successfully');
				redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcode));
			}
			else {
				log_dra_user($log_title = "Delete DRA Exam Applicant Unsuccessful", $log_message = serialize(array('id'=>$id)));
				$this->session->set_flashdata('error','Error occured while deleting record');
				redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcode));
			}
		}
	}
	public function applyexam( $regid, $examcode, $mode, $medium, $period, $center, $fees, $date, $time, $trainingfrm, $trainingto ) {
		$insert_exam = array(
			'regid' => $regid,
			'exam_code' => $examcode,
			'exam_mode' => $mode,
			'exam_medium' => $medium,
			'exam_period' => $period,
			'exam_center_code' => $center,
			'exam_fee' => $fees,
			'exam_date' => $date,
			'exam_time' => $time,
			'training_from'	=> $trainingfrm,
			'training_to'	=> $trainingto,
			'pay_status' => 2,
			'created_on' => date('Y-m-d H:i:s')
		);
		if( $this->master_model->insertRecord('dra_member_exam',$insert_exam) ) {
			log_dra_user($log_title = "Add DRA Exam Applicant Successful", $log_message = serialize($insert_exam));
			$this->session->set_flashdata('success','Record added successfully');
			redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcode));
		} else {
			log_dra_user($log_title = "Add DRA Exam Applicant Unsuccessful", $log_message = serialize($insert_exam));
			$this->session->set_flashdata('error','Error occured while adding record');
			redirect(base_url().'iibfdra/DraExam/add/?exCd='.base64_encode($examcode));
		}
	}	
	public function payment() {
		$last = $this->uri->total_segments();
		$enexamcode = $this->uri->segment($last);
		$examcode = base64_decode($enexamcode);
		if( !empty( $enexamcode ) && is_numeric( $examcode ) ) {
			$regnoarr = $this->input->post('chkmakepay');
			$data['result'] = array();
			if( is_array($regnoarr) && count( $regnoarr ) > 0 ) {
				foreach( $regnoarr as $id ) {
					//print_r("hiiii");
					$this->db->where('dra_members.isdeleted',0);
					$this->db->join('dra_members','dra_member_exam.regid = dra_members.regid');
					$this->db->where('dra_member_exam.dra_memberexam_delete',0);
					$this->db->where('dra_member_exam.id',$id);
					$res = $this->master_model->getRecords("dra_member_exam",array('dra_member_exam.exam_code'=>$examcode));
					//print_r($this->db->last_query());
					if($res) {
						$data['result'][] = $res['0'];
					}
				}
				$regnostr = implode('|',$regnoarr);
				$regnostrencd = base64_encode($regnostr);
				$data['regnostrencd'] = $regnostrencd;	
				$data['examcode'] = $examcode;
				// TO do: Candidate list
				$data["middle_content"] = 'payment_page';
				/* send active exams for display in sidebar */
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$data['active_exams'] = $res;
				$this->load->view('iibfdra/common_view',$data);
			} else {
				$this->session->set_flashdata('error','Please select at least one candidate to pay');
				redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.$enexamcode);
			}
		} else {
				redirect(base_url().'iibfdra/');
		}
	}
	
	public function make_neft() {
		
		// TO do:
		
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			$this->form_validation->set_rules('utr_no','NEFT / RTGS (UTR) Number','required|trim');
			$this->form_validation->set_rules('payment_date','Payment Date','required');
			$this->form_validation->set_rules('utr_slip','UTR Slip','required');
			if($this->form_validation->run()==TRUE) {
				// upload UTR slip
				$outpututrslip1 = '';
				if( isset( $_POST["hiddenutrslip"] ) && !empty($_POST["hiddenutrslip"]) ) {
					$date = date('Y-m-d h:i:s');
					$inpututrslip = $_POST["hiddenutrslip"];
					$tmp_utrslip = strtotime($date).rand(0,100);
					$outpututrslip = getcwd()."/uploads/iibfdra/utrslip_".$tmp_utrslip.".jpg";
					$outpututrslip1 = base_url()."uploads/iibfdra/utrslip_".$tmp_utrslip.".jpg";
					file_put_contents($outpututrslip, file_get_contents($inpututrslip));
				}
				//$dra_mem_list = "441021@447980@446046";
				//$dra_mem_array = explode("@", "441021@447980@446046");
				$amount =  base64_decode($_POST['tot_fee']);
				$dra_mem_list = base64_decode($_POST['regNosToPay']);
				$dra_mem_array = explode("|", $dra_mem_list);
				$examcode = base64_decode($_POST['exam_code']);
				$examperiod = base64_decode($_POST['exam_period']);
				$instdata = $this->session->userdata('dra_institute');
				$inst_code = $instdata['institute_code']; 
	
				// Create transaction
				$insert_data = array(
					'amount'           => $amount,
					'gateway'          => 1,  // 1= NEFT / RTGS
					'UTR_no'		   => $this->input->post('utr_no'),
					'UTR_slip_file'    => $outpututrslip1,
					'inst_code'		   => $inst_code,
					'date'             => $this->input->post('payment_date'),
					'pay_count'        => count($dra_mem_array),
					'exam_code'        => $examcode,  // TO DO:
					'exam_period'      => $examperiod,  // TO DO:
					'status'           => '3' //applied for approval by dra admin
				);
				$pt_id = $this->master_model->insertRecord('dra_payment_transaction', $insert_data, true);
				//$pt_id = "DP9878280".$pt_id;
				$update_data = array(
					'receipt_no' => $pt_id
				);
				//print_r($update_data); exit;
				$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('id'=>$pt_id));
				/* Start Dyanamic Fees allocation - Bhushan Added code on 28 Feb 2019 */
				// get logged in institute details from session
				$instdata = $this->session->userdata('dra_institute');
				$instStateCode = $instdata['ste_code'];
				
				//get state name, state_no from state master by state code
				$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
				$totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = 0; 
				$cgst_rate = 0;
				$cgst_amt = 0;
				$sgst_rate = 0;
				$sgst_amt = 0;
				$igst_rate = 0;
				$igst_amt = 0;
				$cs_total = 0;
				$igst_total = 0;
				$cess = 0;
				
				foreach ($dra_mem_array as $dra_mem_id)
				{
						$insert_mpt_data = array(
							'memexamid' => $dra_mem_id,
							'ptid'  => $pt_id
						);
						// insert the dra member id in 'dra_member_payment_transaction' table	
						$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
						//update status in dra_member_exam table
						$updtmemexam_data = array('pay_status'=>3);
						$this->master_model->updateRecord('dra_member_exam',$updtmemexam_data,array('id'=>$dra_mem_id));
					
					/* Get reg id   */
					$this->db->where('id', $dra_mem_id);
					$getRegId = $this->master_model->getRecords('dra_member_exam', '', 'regid');
					$RegId = $getRegId[0]['regid'];
					
					/* Get Member Number  */
					$registrationtype = "NM";
					$this->db->where('regid', $RegId);
					$getMemberNo = $this->master_model->getRecords('dra_members', '', 'regnumber,registrationtype');
					$member_no = $getMemberNo[0]['regnumber']; 
					$registrationtype = $getMemberNo[0]['registrationtype']; // NM,O
					
					/* Get Grp Code */
					$grp_cd = "B1";
					if($member_no != 0 && $member_no != "")
					{
						$this->db->where('member_no', $member_no);
						$getGrpCd = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
						if(count($getGrpCd)>0)
						{
							$grp_cd = $getGrpCd[0]['app_category']; 
							if($grp_cd != ""){
								if($grp_cd == "R"){
									$grp_cd = "B1";
								}
							}
						}else{
								$grp_cd = "B1";
							}
					}
					
					//get fees details from fee master
					$this->db->select('dra_fee_master.*');
					$this->db->where('dra_fee_master.member_category',$registrationtype);
					$this->db->where('dra_fee_master.group_code',$grp_cd);
					$this->db->where('dra_fee_master.exempt','NE'); 
					$this->db->where('dra_fee_master.exam_code',$examcode);
					$this->db->where('dra_fee_master.exam_period',$examperiod);
					$dra_fee_master=$this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
					
					$totol_amt = $totol_amt + $dra_fee_master[0]['fee_amount'];
					
					if($instdata['ste_code'] == 'MAH'){
						$total_cgst_amt = $total_cgst_amt + $dra_fee_master[0]['cgst_amt'];
						$total_sgst_amt = $total_sgst_amt + $dra_fee_master[0]['sgst_amt'];
					}
					else{
						$total_igst_amt = $total_igst_amt + $dra_fee_master[0]['igst_amt'];
					}
				}
				// Total amount without any GST
				$fee_amt = $totol_amt; 
				
				$tax_type = '';
				if($instdata['ste_code'] == 'MAH')
				{
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					//set an amount as per rate
					$cgst_amt = $total_cgst_amt;
					$sgst_amt = $total_sgst_amt;
					$cs_total = $amount;
					$tax_type = 'Intra';
				}
				else
				{
					//set a rate (e.g 9%,9% or 18%)
					$igst_rate = $this->config->item('igst_rate');
					$igst_amt = $total_igst_amt;
					$igst_total = $amount;
					$tax_type = 'Inter';
				}	
				$no_of_members_payment = count($dra_mem_array);
			
				/* Dyanamic Fees allocation End - Bhushan Added code on 28 Feb 2019 */
				
				// insert the dra member id in 'dra_member_payment_transaction' table
			/*	foreach ($dra_mem_array as $dra_mem_id)
				{
					$insert_mpt_data = array(
						'memexamid' => $dra_mem_id,
						'ptid'  => $pt_id
					);
					$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
					//update status in dra_member_exam table
					$updtmemexam_data = array('pay_status'=>3);
					$this->master_model->updateRecord('dra_member_exam',$updtmemexam_data,array('id'=>$dra_mem_id));
				}*/
				
				/******************* code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
			
				/*$no_of_members_payment = count($dra_mem_array);
			
				$cgst_rate = 0;
				$cgst_amt = 0;
				$sgst_rate = 0;
				$sgst_amt = 0;
				$igst_rate = 0;
				$igst_amt = 0;
				$cs_total = 0;
				$igst_total = 0;
				$cess = 0;
				
				//get fees details from fee master
				$this->db->select('dra_fee_master.*');
				$this->db->where('dra_fee_master.member_category','NM');
				$this->db->where('dra_fee_master.group_code','B1');
				$this->db->where('dra_fee_master.exempt','NE'); // get only NE states fees, added by Bhagwan Sahane, on 05-07-2017
				$this->db->where('dra_fee_master.exam_code',$examcode);
				$this->db->where('dra_fee_master.exam_period',$examperiod);
				$dra_fee_master = $this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
				
				$fee_amt = $dra_fee_master[0]['fee_amount'];
				
				// get logged in institute details from session
				$instdata = $this->session->userdata('dra_institute');
				$instStateCode = $instdata['ste_code'];
				
				//get state name, state_no from state master by state code
				$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
				
				// get fees details from fee master
				$tax_type = '';
				if($instdata['ste_code'] == 'MAH')
				{
					//set a rate (e.g 9%,9% or 18%)
					$cgst_rate = $this->config->item('cgst_rate');
					$sgst_rate = $this->config->item('sgst_rate');
					
					//set an amount as per rate
					//$cgst_amt = $fee_amt * ($cgst_rate / 100);
					//$sgst_amt = $fee_amt * ($sgst_rate / 100);
					$cgst_amt = $dra_fee_master[0]['cgst_amt'] * $no_of_members_payment;
					$sgst_amt = $dra_fee_master[0]['sgst_amt'] * $no_of_members_payment;
					
					//set an total amount
					//$cs_total = $fee_amt + $cgst_amt + $sgst_amt;
					$cs_total = $amount;
					
					$tax_type = 'Intra';
				}
				else
				{
					//set a rate (e.g 9%,9% or 18%)
					$igst_rate = $this->config->item('igst_rate');
					
					//set an amount as per rate
					//$igst_amt = $fee_amt * ($igst_rate / 100);
					$igst_amt = $dra_fee_master[0]['igst_amt'] * $no_of_members_payment;
					
					//set an total amount
					//$igst_total = $fee_amt + $igst_amt;
					$igst_total = $amount;
					
					$tax_type = 'Inter';
				}*/
				
				$invoice_insert_array = array(
											'pay_txn_id' => $pt_id,
											'receipt_no' => $pt_id,
											'exam_code' => $examcode,
											'exam_period' => $examperiod,
											//'center_code' => '',
											//'center_name' => '',
											'state_of_center' => $instStateCode,
											//'member_no' => $this->session->userdata('regnumber'),
											'institute_code' => $instdata['institute_code'],
											'institute_name' => $instdata['institute_name'],
											'app_type' => 'I', // I for DRA Exam Invoice
											'tax_type' => $tax_type, // I for DRA Exam Invoice
											'service_code' => $this->config->item('exam_service_code'),
											'gstin_no' => $instdata['gstin_no'],
											'qty' => $no_of_members_payment,
											'state_code' => $draInstState[0]['state_no'],
											'state_name' => $draInstState[0]['state_name'],
											//'invoice_no' => '',	// before payment it will be blank
											//'invoice_image' => '', // before payment it will be blank
											//'date_of_invoice' => '', // before payment it will be blank
											//'transaction_no' => '', // before payment it will be blank
											'fee_amt' => $fee_amt,
											'cgst_rate' => $cgst_rate,
											'cgst_amt' => $cgst_amt,
											'sgst_rate' => $sgst_rate,
											'sgst_amt' => $sgst_amt,
											'igst_rate' => $igst_rate,
											'igst_amt' => $igst_amt,
											'cs_total' => $cs_total,
											'igst_total' => $igst_total,
											'cess' => $cess,
											'exempt' => $draInstState[0]['exempt'],
											'created_on' => date('Y-m-d H:i:s')
										);
										
				$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
				
				log_dra_user($log_title = "Add DRA Exam Invoice Successful", $log_message = serialize($invoice_insert_array));
				
				/******************* eof code added for GST Changes, by Bhagwan Sahane, on 05-07-2017 ***************/
				
				// manage NEFT/RTGS transaction
				//$this->load->view('pg_sbi_form',$data);
				if( $pt_id ) {
					$this->session->set_flashdata('success','NEFT/RTGS Payment is added and sent for approval');
					redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.base64_encode($examcode));
				}
			} else {
				$data['validation_errors'] = validation_errors(); 
			}
			
		}
		else
		{
			if( isset( $_POST['regNosToPay'] ) && isset( $_POST['tot_fee'] ) && isset( $_POST['exam_code'] ) && isset( $_POST['exam_period'] ) ) {
				$data["regNosToPay"] = $this->input->post('regNosToPay');
				$data["tot_fee"] = $this->input->post('tot_fee');
				$data["exam_code"] = $this->input->post('exam_code');
				$data["exam_period"] = $this->input->post('exam_period');
				$data["middle_content"] = 'make_neftpayment_page';
				/* send active exams for display in sidebar */
				$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
				$res = $this->master_model->getRecords("dra_exam_master a");
				$data['active_exams'] = $res;
				$this->load->view('iibfdra/common_view',$data);
			} else {
				redirect(base_url().'iibfdra/');
			}
		}
		
	}
	
	public function make_payment() {
		
		// TO do:
		//print_r($_POST);
		if(isset($_POST['processPayment']) && $_POST['processPayment'])
		{
			include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			
			$key          = $this->config->item('sbi_m_key');
			$merchIdVal   = $this->config->item('sbi_merchIdVal');
			$AggregatorId = $this->config->item('sbi_AggregatorId');
			
			$pg_success_url = base_url()."iibfdra/DraExam/sbitranssuccess";
			$pg_fail_url    = base_url()."iibfdra/DraExam/sbitransfail";
			
			$amount =  base64_decode($_POST['tot_fee']);
			//$dra_mem_list = "441021@447980@446046";
			$dra_mem_list = base64_decode($_POST['regNosToPay']);
			//$dra_mem_array = explode("@", "441021@447980@446046");
			$dra_mem_array = explode("|", $dra_mem_list);
			$examcode = base64_decode($_POST['exam_code']);
			$examperiod = base64_decode($_POST['exam_period']);
			$instdata = $this->session->userdata('dra_institute');
			$inst_code = $instdata['institute_code']; 
			
			//$MerchantOrderNo = generate_dra_order_id();
			
			//Apply DRA exam
			//Ref1 = orderid
			//Ref2 = iibfexam
			//Ref3 = iibfdra
			//Ref4 = exam_code + examyear + exammonth ex (101201602)
			$exam_month_year = $this->master_model->getRecords('dra_misc_master',array('exam_code'=>$examcode,'exam_period'=>$examperiod));
			
			if( count($exam_month_year) > 0 ) {				
				$ref4 = $examcode.$exam_month_year[0]['exam_month'];
			}
			else
			{
				$ref4 = "";
			}
			
			// Create transaction
			$insert_data = array(
				'amount'           => $amount,
				'gateway'          => 2,  // 2 = SBI ePay
				'inst_code'		   => $inst_code,
				'date'             => date('Y-m-d h:i:s'),
				'pay_count'        => count($dra_mem_array),
				'exam_code'        => $examcode,  // TO DO:
				'exam_period'      => $examperiod,  // TO DO:
				'status'           => '2',
				//'receipt_no'       => $MerchantOrderNo,
				'pg_flag'=>		'iibfdra',
				//'pg_other_details'=>$custom_field
				
			);
			//print_r($insert_data); die("test");
			
			$pt_id = $this->master_model->insertRecord('dra_payment_transaction', $insert_data, true);
			
			$MerchantOrderNo = dra_sbi_order_id($pt_id);
			
			$custom_field = $MerchantOrderNo."^iibfexam^iibfdra^".$ref4;
			
			// update receipt no. in dra payment transaction -
			$update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
			$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('id'=>$pt_id));
			/* Start Dyanamic Fees allocation - Bhushan Added code on 28 Feb 2019 */
			
			// get logged in institute details from session
			$instdata = $this->session->userdata('dra_institute');
			$instStateCode = $instdata['ste_code'];
			
			//get state name, state_no from state master by state code
			$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
			$totol_amt = $total_igst_amt = $total_cgst_amt = $total_sgst_amt = 0;
			$cgst_rate = 0;
			$cgst_amt = 0;
			$sgst_rate = 0;
			$sgst_amt = 0;
			$igst_rate = 0;
			$igst_amt = 0;
			$cs_total = 0;
			$igst_total = 0;
			$cess = 0;
			
			foreach ($dra_mem_array as $dra_mem_id)
			{
				$insert_mpt_data = array(
					'memexamid' => $dra_mem_id,
					'ptid'  => $pt_id
				);
				
				// insert the dra member id in 'dra_member_payment_transaction' table	
				$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
				
				/* Get reg id   */
				$this->db->where('id', $dra_mem_id);
				$getRegId = $this->master_model->getRecords('dra_member_exam', '', 'regid');
				$RegId = $getRegId[0]['regid'];
				
				/* Get Member Number  */
				$registrationtype = "NM";
				$this->db->where('regid', $RegId);
				$getMemberNo = $this->master_model->getRecords('dra_members', '', 'regnumber,registrationtype');
				$member_no = $getMemberNo[0]['regnumber']; 
				$registrationtype = $getMemberNo[0]['registrationtype']; // NM,O
				
				/* Get Grp Code */
				$grp_cd = "B1";
				if($member_no != 0 && $member_no != "")
				{
					$this->db->where('member_no', $member_no);
					$getGrpCd = $this->master_model->getRecords('dra_eligible_master', '', 'app_category');
					if(count($getGrpCd)>0)
					{
						$grp_cd = $getGrpCd[0]['app_category']; 
						if($grp_cd != ""){
							if($grp_cd == "R"){
								$grp_cd = "B1";
							}
						}
					}else{
							$grp_cd = "B1";
						}
				}
				
				//get fees details from fee master
				$this->db->select('dra_fee_master.*');
				$this->db->where('dra_fee_master.member_category',$registrationtype);
				$this->db->where('dra_fee_master.group_code',$grp_cd);
				$this->db->where('dra_fee_master.exempt','NE'); 
				$this->db->where('dra_fee_master.exam_code',$examcode);
				$this->db->where('dra_fee_master.exam_period',$examperiod);
				$dra_fee_master=$this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
				
				$totol_amt = $totol_amt + $dra_fee_master[0]['fee_amount'];
				
				if($instdata['ste_code'] == 'MAH')
				{
					$total_cgst_amt = $total_cgst_amt + $dra_fee_master[0]['cgst_amt'];
					$total_sgst_amt = $total_sgst_amt + $dra_fee_master[0]['sgst_amt'];
				}
				else
				{
					$total_igst_amt = $total_igst_amt + $dra_fee_master[0]['igst_amt'];
				}
				
			}
			$fee_amt = $totol_amt; // Total amount without any GST
			
			$tax_type = '';
			if($instdata['ste_code'] == 'MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate = $this->config->item('cgst_rate');
				$sgst_rate = $this->config->item('sgst_rate');
				//set an amount as per rate
				$cgst_amt = $total_cgst_amt;
				$sgst_amt = $total_sgst_amt;
				$cs_total = $amount;
				$tax_type = 'Intra';
			}
			else
			{
				//set a rate (e.g 9%,9% or 18%)
				$igst_rate = $this->config->item('igst_rate');
				$igst_amt = $total_igst_amt;
				$igst_total = $amount;
				$tax_type = 'Inter';
			}	
			$no_of_members_payment = count($dra_mem_array);
			
			/* Dyanamic Fees allocation End - Bhushan Added code on 28 Feb 2019 */
			/*// insert the dra member id in 'dra_member_payment_transaction' table
			foreach ($dra_mem_array as $dra_mem_id)
			{
				$insert_mpt_data = array(
					'memexamid' => $dra_mem_id,
					'ptid'  => $pt_id
				);
					
				$this->master_model->insertRecord('dra_member_payment_transaction', $insert_mpt_data);
			}*/
			
			/******************* code added for GST Changes, by Bhagwan Sahane, on 03-07-2017 ***************/
			
			/*$no_of_members_payment = count($dra_mem_array);
			
			$cgst_rate = 0;
			$cgst_amt = 0;
			$sgst_rate = 0;
			$sgst_amt = 0;
			$igst_rate = 0;
			$igst_amt = 0;
			$cs_total = 0;
			$igst_total = 0;
			$cess = 0;
			
			//get fees details from fee master
			$this->db->select('dra_fee_master.*');
			$this->db->where('dra_fee_master.member_category','NM');
			$this->db->where('dra_fee_master.group_code','B1');
			$this->db->where('dra_fee_master.exempt','NE'); // get only NE states fees, added by Bhagwan Sahane, on 05-07-2017
			$this->db->where('dra_fee_master.exam_code',$examcode);
			$this->db->where('dra_fee_master.exam_period',$examperiod);
			$dra_fee_master = $this->master_model->getRecords('dra_fee_master',array('dra_fee_master.fee_delete'=>'0'));
			
			$fee_amt = $dra_fee_master[0]['fee_amount'];
			
			// get logged in institute details from session
			$instdata = $this->session->userdata('dra_institute');
			$instStateCode = $instdata['ste_code'];
			
			//get state name, state_no from state master by state code
			$draInstState = $this->master_model->getRecords('state_master',array('state_code'=>$instStateCode,'state_delete'=>'0'));
			
			// get fees details from fee master
			$tax_type = '';
			if($instdata['ste_code'] == 'MAH')
			{
				//set a rate (e.g 9%,9% or 18%)
				$cgst_rate = $this->config->item('cgst_rate');
				$sgst_rate = $this->config->item('sgst_rate');
				
				//set an amount as per rate
				//$cgst_amt = $fee_amt * ($cgst_rate / 100);
				//$sgst_amt = $fee_amt * ($sgst_rate / 100);
				$cgst_amt = $dra_fee_master[0]['cgst_amt'] * $no_of_members_payment;
				$sgst_amt = $dra_fee_master[0]['sgst_amt'] * $no_of_members_payment;
				
				//set an total amount
				//$cs_total = $fee_amt + $cgst_amt + $sgst_amt;
				$cs_total = $amount;
				
				$tax_type = 'Intra';
			}
			else
			{
				//set a rate (e.g 9%,9% or 18%)
				$igst_rate = $this->config->item('igst_rate');
				
				//set an amount as per rate
				//$igst_amt = $fee_amt * ($igst_rate / 100);
				$igst_amt = $dra_fee_master[0]['igst_amt'] * $no_of_members_payment;
				
				//set an total amount
				//$igst_total = $fee_amt + $igst_amt;
				$igst_total = $amount;
				
				$tax_type = 'Inter';
			}*/
			
			$invoice_insert_array = array(
										'pay_txn_id' => $pt_id,
										'receipt_no' => $MerchantOrderNo,
										'exam_code' => $examcode,
										'exam_period' => $examperiod,
										//'center_code' => '',
										//'center_name' => '',
										'state_of_center' => $instStateCode,
										//'member_no' => $this->session->userdata('regnumber'),
										'institute_code' => $instdata['institute_code'],
										'institute_name' => $instdata['institute_name'],
										'app_type' => 'I', // I for DRA Exam Invoice
										'tax_type' => $tax_type, // I for DRA Exam Invoice
										'service_code' => $this->config->item('exam_service_code'),
										'gstin_no' => $instdata['gstin_no'],
										'qty' => $no_of_members_payment,
										'state_code' => $draInstState[0]['state_no'],
										'state_name' => $draInstState[0]['state_name'],
										//'invoice_no' => '',	// before payment it will be blank
										//'invoice_image' => '', // before payment it will be blank
										//'date_of_invoice' => '', // before payment it will be blank
										//'transaction_no' => '', // before payment it will be blank
										'fee_amt' => $fee_amt,
										'cgst_rate' => $cgst_rate,
										'cgst_amt' => $cgst_amt,
										'sgst_rate' => $sgst_rate,
										'sgst_amt' => $sgst_amt,
										'igst_rate' => $igst_rate,
										'igst_amt' => $igst_amt,
										'cs_total' => $cs_total,
										'igst_total' => $igst_total,
										'cess' => $cess,
										'exempt' => $draInstState[0]['exempt'],
										'created_on' => date('Y-m-d H:i:s')
									);
									
			$this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
			
			log_dra_user($log_title = "Add DRA Exam Invoice Successful", $log_message = serialize($invoice_insert_array));
			
			/******************* eof code added for GST Changes, by Bhagwan Sahane, on 03-07-2017 ***************/

			$MerchantCustomerID = "12345";  // exam code
			
			$data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
			$data["merchIdVal"]  = $merchIdVal;
			
			/* 
			requestparameter=
			MerchantId | OperatingMode | MerchantCountry | MerchantCurrency |
PostingAmount | OtherDetails | SuccessURL | FailURL | AggregatorId | MerchantOrderNo |
MerchantCustomerID | Paymode | Accesmedium | TransactionSource

			Ex.
			requestparameter
=1000003|DOM|IN|INR|2|Other|https://test.sbiepay.coom/secure/fail.jsp|SBIEPAY|2|2|NB|ONLINE|ONLINE
			*/

			$EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			
			$EncryptTrans = $aes->encrypt($EncryptTrans);
			
			$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
	//exit;//added for testing
			$this->load->view('pg_sbi_form',$data);
		}
		else
		{
			//$data["regNosToPay"] = '441021@447980@446046@';
			//$data["tot_fee"] = 'Mw==';
			//$data["t"] = 't';
			//die('test '.);
			if( isset( $_POST['regNosToPay'] ) && isset( $_POST['tot_fee'] ) && isset( $_POST['exam_code'] ) && isset( $_POST['exam_period'] ) ) {
				$data["regNosToPay"] = $this->input->post('regNosToPay');
				$data["tot_fee"] = $this->input->post('tot_fee');
				$data["exam_code"] = $this->input->post('exam_code');
				$data["exam_period"] = $this->input->post('exam_period');
				$this->load->view('iibfdra/make_payment_page',$data);
			} else {
				redirect(base_url().'iibfdra/');
			}
		}
	}
	
	public function sbitranssuccess()
	{
		//error_reporting(E_ALL);
		//print_r($_REQUEST['encData']); exit;
		//$_REQUEST['encData'] = "W6lmL7Io4x76YD/xrO84HDrkIteeOajJuxgcUC2TbRl08hnZkq2g4JJDPVjm3Hfrmb+GGtxF69f9Yy1LfrpYjNaVEZdqTsl/voX7GRkI4lqo+a97oX/BAYlwJ70Uu1N6J5o+bvX6XuSuBKyLFY4CdgXDZzEofhz0VmJPz0txT7s=";
	//	echo "<BR>";

		if (isset($_REQUEST['encData']))
		{
			$this->load->model('log_model');

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			$key = $this->config->item('sbi_m_key');
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();
			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			$MerchantOrderNo = $responsedata[0];
			$transaction_no  = $responsedata[1];
			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				$encData = $_REQUEST['pushRespData'];
			}
			
			$payment_status = 2;
			
			switch ($responsedata[2])
			{
				case "SUCCESS":
					$payment_status = 1;
					break;
				case "FAIL":
					$payment_status = 0;
					break;
				case "PENDING":
					$payment_status = 2;
					break;
			}

			if($payment_status==1)
			{
				//SBI CALLBACK B2B
				// Handle transaction sucess case 
				//get payment transaction id
				$q_details = sbiqueryapi($MerchantOrderNo);
				if ($q_details)
				{
					if ($q_details[2] == "SUCCESS")
					{
						//added on 18-01-2017 - For payment callback changes
						$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status,id,date');
						if($transdetail_det[0]['status']==2)
						{  
						$updated_date = date('Y-m-d H:i:s');					
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'B2B');	
						$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
                        // print_r($update_data);exit;				
						if($this->db->affected_rows())					
						{
							$transid = 0;
							if( count($transdetail_det) > 0 ) {
							$transdetail = $transdetail_det[0];
							$transid = $transdetail['id'];
							//get dra_member_exam_unique ids from dra_member_payment_transaction table
							$transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
							//print_r($transmemdetails);
							if( count( $transmemdetails ) > 0 ) {
								foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
									$uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
									$regidformemref = $this->master_model->getValue('dra_member_exam',array('id'=>$uniqueid),'regid');
									//echo "<BR>regidformemref = ".$regidformemref."  --  ".$uniqueid;
									$regnum = $this->master_model->getValue('dra_members',array('regid'=>$regidformemref),'regnumber');
									//echo "<BR>regnum = ".$regnum;
									if( empty( $regnum ) ) {
										//$regnumber = generate_dra_reg_num();
										//$regnumber = generate_nm_reg_num();
										$regnumber = generate_NM_memreg($regidformemref);
										$update_data = array('regnumber' => $regnumber);
										$this->master_model->updateRecord('dra_members',$update_data,array('regid'=>$regidformemref));
										//update uploaded file names which will include generated registration number
										//get cuurent saved file names from DB
										$currentpics = $this->master_model->getRecords('dra_members', array('regid'=>$regidformemref), 'scannedphoto, scannedsignaturephoto, idproofphoto, training_certificate, quali_certificate'); 									$scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $trainingphoto_file = $qualiphoto_file = '';
											
										if( count($currentpics) > 0 ) {
											$currentphotos = $currentpics[0];
											$scannedphoto_file = $currentphotos['scannedphoto'];
											$scannedsignaturephoto_file = $currentphotos['scannedsignaturephoto'];
											$idproofphoto_file = $currentphotos['idproofphoto'];
											$trainingphoto_file = $currentphotos['training_certificate'];
											$qualiphoto_file = $currentphotos['quali_certificate'];
										}
										$upd_files = array();
										$photo_file = 'p_'.$regnumber.'.jpg';
										$sign_file = 's_'.$regnumber.'.jpg';
										$proof_file = 'pr_'.$regnumber.'.jpg';
										$quali_file = 'degre_'.$regnumber.'.jpg';
										$training_file = 'traing_'.$regnumber.'.jpg';
										if( !empty( $scannedphoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$scannedphoto_file,"./uploads/iibfdra/".$photo_file))
											{	
												$upd_files['scannedphoto'] = $photo_file;	
											}
										}
										if( !empty( $scannedsignaturephoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$scannedsignaturephoto_file,"./uploads/iibfdra/".$sign_file))
											{	
												$upd_files['scannedsignaturephoto'] = $sign_file;	
											}
										}
										if( !empty( $idproofphoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$idproofphoto_file,"./uploads/iibfdra/".$proof_file))
											{	
												$upd_files['idproofphoto'] = $proof_file;	
											}
										}
										if( !empty( $qualiphoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$qualiphoto_file,"./uploads/iibfdra/".$quali_file))
											{	
												$upd_files['quali_certificate'] = $quali_file;	
											}
										}
										if( !empty( $trainingphoto_file ) ) {
											if(@ rename("./uploads/iibfdra/".$trainingphoto_file,"./uploads/iibfdra/".$training_file))
											{	
												$upd_files['training_certificate'] = $training_file;	
											}
										}
										if(count($upd_files)>0)
										{
											$this->master_model->updateRecord('dra_members',$upd_files,array('regid'=>$regidformemref));
										}							
									}
									
									$update_data = array('pay_status' => 1);
									$this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
									
									//echo "<BR>dra_member_exam id = ".$uniqueid;
								}
							}
						
						/*$updated_date = date('Y-m-d H:i:s');
						$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7], 'updated_date' => $updated_date, 'callback'=>'B2B');
						//print_r($update_data);
						//echo "<BR>receipt_no = ".$MerchantOrderNo;
				$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
						
						//Manage Log
						$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
						$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);
						
						/******************* code added for GST Changes, by Bhagwan Sahane, on 04-07-2017 ***************/
			
						// get invoice
						$exam_invoice = $this->master_model->getRecords('exam_invoice',array('receipt_no' => $MerchantOrderNo,'pay_txn_id' => $transdetail_det[0]['id']),'invoice_id');
						
						if(count($exam_invoice) > 0)
						{
							// generate exam invoice no
							$invoice_no = generate_exam_invoice_number($exam_invoice[0]['invoice_id']);
							if($invoice_no)
							{
								$invoice_no = $this->config->item('exam_invoice_no_prefix').$invoice_no; // e.g. EXM/2017-18/000001
							}
							
							// update invoice details
							$invoice_update_data = array('invoice_no' => $invoice_no,'transaction_no' => $transaction_no,'date_of_invoice' => $transdetail_det[0]['date'],'modified_on' => $updated_date);
							//'date_of_invoice' => $updated_date 
							$this->db->where('pay_txn_id',$transdetail_det[0]['id']);
							$this->master_model->updateRecord('exam_invoice',$invoice_update_data,array('invoice_id' => $exam_invoice[0]['invoice_id']));
							
							log_dra_user($log_title = "Update DRA Exam Invoice Successful", $log_message = serialize($invoice_update_data));
							
							// generate invoice image
							$invoice_img_path = genarate_draexam_invoice($exam_invoice[0]['invoice_id']);
						}
						}
						}
						/******************* eof code added for GST Changes, by Bhagwan Sahane, on 04-07-2017 ***************/
						
						}
					}
				}
				//End Of SBI CALLBACK B2B
				$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
				if( count($transdetail_det) > 0 ) {
				
					$enexcode = base64_encode($transdetail_det[0]['exam_code']);
					$this->session->set_flashdata('success','Your payment has been successfully processed.');
					redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.$enexcode);					
				}
				else
				{
					redirect(base_url().'iibfdra');					
				}
			}
			else if($payment_status==0)
			{
				$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
				if( count($transdetail_det) > 0 ) {
				
					$enexcode = base64_encode($transdetail_det[0]['exam_code']);
					$this->session->set_flashdata('error','Your transaction has been declined.');
					redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.$enexcode);					
				}
				else
				{
					redirect(base_url().'iibfdra');		
				}
			}
		}
		else
		{
			die("Please try again...");
		}

		exit;
	}
	
	public function sbitransfail()
	{
		if (isset($_REQUEST['encData']))
		{
			$this->load->model('log_model');

			include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

			$key = $this->config->item('sbi_m_key');
			
			$aes = new CryptAES();
			$aes->set_key(base64_decode($key));
			$aes->require_pkcs5();

			$encData = $aes->decrypt($_REQUEST['encData']);
			$responsedata = explode("|",$encData);
			//print_r($responsedata);

			$MerchantOrderNo = $responsedata[0];
			$transaction_no  = $responsedata[1];
			
			if (isset($_REQUEST['merchIdVal']))
			{
				$merchIdVal = $_REQUEST['merchIdVal'];
			}
			if (isset($_REQUEST['Bank_Code']))
			{
				$Bank_Code = $_REQUEST['Bank_Code'];
			}
			if (isset($_REQUEST['pushRespData']))
			{
				$encData = $_REQUEST['pushRespData'];
			}
			
			$payment_status = 2;
			
			switch ($responsedata[2])
			{
				case "SUCCESS":
					$payment_status = 1;
					break;
				case "FAIL":
					$payment_status = 0;
					break;
				case "PENDING":
					$payment_status = 2;
					break;
			}

			if($payment_status==1)
			{
				
				$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
				if( count($transdetail_det) > 0 ) {
				
					$enexcode = base64_encode($transdetail_det[0]['exam_code']);
					$this->session->set_flashdata('success','Your payment has been successfully processed.');
					redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.$enexcode);					
				}
				else
				{
					redirect(base_url().'iibfdra');					
				}
			}
			else if($payment_status==0)
			{
					$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo),'status');
					if($transdetail_det[0]['status']!=0 && $transdetail_det[0]['status']==2)
					{
						$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2], 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'description' => $responsedata[7],'callback'=>'B2B');
						$this->master_model->updateRecord('dra_payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));
						// Handle transaction fail case 
						
						$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
						$transid = 0;
						if( count($transdetail_det) > 0 ) {
							$transdetail = $transdetail_det[0];
							$transid = $transdetail['id'];
							//echo "<BR>transid = ".$transid; 
							//get dra_member_exam_unique ids from dra_member_payment_transaction table
							$transmemdetails = $this->master_model->getRecords('dra_member_payment_transaction',array('ptid'=>$transid));
							//echo $this->db->last_query();
							//print_r($transmemdetails);
							if( count( $transmemdetails ) > 0 ) {
								foreach( $transmemdetails as $transmemdetail ) { //print_r($transmemdetail);
									$uniqueid = $transmemdetail['memexamid']; //unique id of dra_member_exam table
									$update_data = array('pay_status' => 0); //0 for fail
									$this->master_model->updateRecord('dra_member_exam',$update_data,array('id'=>$uniqueid));
									//echo "<BR>dra_member_exam id = ".$uniqueid;
								}
							}
						}
					//Manage Log
					$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
					$this->log_model->logdratransaction("sbiepay", $pg_response, $responsedata[2]);
				}
				$transdetail_det = $this->master_model->getRecords('dra_payment_transaction',array('receipt_no'=>$MerchantOrderNo));
				if( count($transdetail_det) > 0 ) {
				
					$enexcode = base64_encode($transdetail_det[0]['exam_code']);
					$this->session->set_flashdata('error','Your transaction has been declined.');
					redirect(base_url().'iibfdra/InstituteHome/draexamapplicants/?exCd='.$enexcode);					
				}
				else
				{
					redirect(base_url().'iibfdra');		
				}
			}
		}
		else
		{
			die("Please try again...");
		}

		exit;
	}
}
?>