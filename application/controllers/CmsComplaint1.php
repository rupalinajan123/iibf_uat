<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); header("Access-Control-Allow-Origin: *");
class CmsComplaint extends CI_Controller {
	public function __construct() {
		 parent::__construct();
		 $this->load->model('master_model');
		 $this->load->model('log_model');	
		 $this->load->library('email');
		 $this->load->model('Emailsending'); 
		 $this->load->library('upload');	
		 $this->load->helper('upload_helper');
		 $this->load->helper('general_helper'); 
	}
	
	/*public function cmsmail()
	{
		//$cmsmaster = $this->db->query("SELECT * FROM `cms_master_live` WHERE `sub_cat_cd` != '' AND DATE(`complain_date`) >= '2016-12-26' LIMIT 5")->result_array();
		
		$cmsmaster = $this->db->query("SELECT * FROM `cms_master_live2` WHERE `sub_cat_cd` IN('WB19916','WB19920','WB19898','WB19894','WB19881','WB19866','WB19860','WB19854','WB19814','WB19809','WB19810','WB19806','WB19794','WB19792','WB19788','WB19786','WB19782','WB19777','WB19771','WB19737','WB19703','WB19683','WB19682','WB19678','WB19633','WB19608','WB19607','WB19605','WB19584','WB19585','WB19561','WB19557','WB19535','WB19531','WB19529','WB19528','WB19492','WB19487','WB19467','WB19465','WB19440')")->result_array();
		
		foreach($cmsmaster as $row)
		{
			$querycat = $row['category_code'];
			$querysubcat = $row['subcatcode'];
			
			$excode = $row['exam_code'];
			if( $excode == NULL ) $excode = 0;
			
			$country_code = $row['country_code'];
			if( $country_code == NULL ) $country_code = 0;
			
			$complaintdate = $row['complain_date'];
			
			$attachment = $row['attachment'];
			if($attachment != "")
			{
				$new_filename = $row['attachment'];
				$rnmfile = $row['attachment'];
			}
			else
			{
				$new_filename = '';
				$rnmfile = '';	
			}
			
			$qnumber = $row['sub_cat_cd'];
			
			// Send Email
			$emailerstr = $this->master_model->getRecords('emailer',array('emailer_name'=>'complaint_email'));
			if(count($emailerstr) > 0) {
				$emailtext = $emailerstr[0]['emailer_text'];
				
				$newstring = str_replace("*category_code*", $querycat, $emailtext);
				//$newstring = str_replace("#category_code#", $this->input->post('querycat'), $emailtext);
				$newstring = str_replace("*subcatcode*", $querysubcat, $newstring);
				if( $excode == 0 ) {
					$examcode = '';
				} else {
					$examcode = $excode;
				}
				$memno = $row['regnumber'];
				$mem_no_flg = 2; //registration number not added
				if( !empty( $memno ) ) 
					$mem_no_flg = 1; //registration number added
				$ath_flg = 'N'; //attachment not added
				if( !empty( $new_filename ) )
					$ath_flg = 'Y'; //attachment added
				$newstring = str_replace("*exam_code*", $examcode, $newstring);
				$newstring = str_replace("*regnumber*", $row['regnumber'], $newstring);
				$newstring = str_replace("*mem_no_flg*", $mem_no_flg, $newstring);
				$newstring = str_replace("*emp_name*", $row['emp_name'], $newstring);
				$newstring = str_replace("*dob*", date("d/m/Y", strtotime($row['dob'])), $newstring);
				$newstring = str_replace("*mem_name*", $row['mem_name'], $newstring);
				$newstring = str_replace("*email*", $row['email'], $newstring);
				if( $country_code == 0 ) $cntrycd = '';
				else $cntrycd = $country_code;
				$newstring = str_replace("*country_code*", $cntrycd, $newstring);
				$newstring = str_replace("*mobile*", $row['mobile'], $newstring);
				$newstring = str_replace("*complain_details*", $row['complain_details'], $newstring);
				$newstring = str_replace("*complain_date*", date("d/m/Y h:i:s A", strtotime($complaintdate)), $newstring);
				$newstring = str_replace("*ath_flg*", $ath_flg, $newstring);
				
				echo "<br>--------------------------------------------------<br>";
				echo "comp_id#".$qnumber."#";
				echo "<br>--------------------------------------------------<br>";
				echo $newstring;
				echo "<br>";
				echo "Attachment File Name : ".$new_filename;
				echo "<br>";
				
				$info_arr = array(
					//'to' => 'webcare@iibf.org.in',
					'to' => 'bvsahane89@gmail.com',
					'from' => $emailerstr[0]['from'],
					'subject' => "comp_id#".$qnumber."#",
					'message' => $newstring
				);
					
				if( !empty( $rnmfile ) ) {
					$path = base_url()."/uploads/cms/".$rnmfile;					
				}
				else {
					$path = '';
				}
				if($this->Emailsending->mailsend_attch($info_arr,$path)){
					//die;
				}
				else {
					$this->session->set_flashdata('error','Error while sending email');
				}
			}
		}
	}*/
	
	public function index() {
		
		
		$valerrors = '';
		//print_r($_POST);    
		if(isset($_POST['querycat']))
		{
			//print_r($_POST);    die("fgfhbgh");
			$this->form_validation->set_rules('querycat','Select Query Category','required');
			$this->form_validation->set_rules('querysubcat','Select Query Sub-Category','required');
			$this->form_validation->set_rules('qurytxtarea','Query In Details','required|trim|xss_clean');
			$this->form_validation->set_rules('code','Security Code','required|trim|callback_check_captcha_cms');
			$this->form_validation->set_rules('countrycd','Country Code','trim|max_length[3]');
			$querycat = $this->input->post('querycat');
			if( isset( $querycat ) && $querycat == 'EXM' ) {
				$this->form_validation->set_rules('examname','Exam Name','required');
			}
			
			// images validations
			if($this->input->post('hiddenqueryfile') != '')
			{
				$this->form_validation->set_rules('queryfile','Upload File','file_allowed_type[jpg,jpeg,png,gif,pdf,doc,docx,txt]|file_size_min[10]|file_size_max[90]');
			}
			
			if($this->form_validation->run()==TRUE) {
				$new_filename = $rnmfile = '';
				$extension = array();
				$date = date('Y-m-d h:i:s');
				//Generate dynamic query file
				//echo $input = $this->input->post('hiddenqueryfile');
			/*	if( !empty($input) ) {
					$tmp_nm = strtotime($date).rand(0,100);
					$outputphoto = getcwd()."/uploads/cms/query_".$tmp_nm.'.'.$extension[1];
					file_put_contents($outputphoto, file_get_contents($input));
					$photofnm = "query_".$tmp_nm.'.'.$extension[1];;
				}*/
				
				
					if(isset($_FILES['queryfile']['name']) && $_FILES['queryfile']['name']!='')
					{
						$size = @getimagesize($_FILES['queryfile']['tmp_name']);
					if($size)
					{
						$input = $_FILES['queryfile']['name'];
						$extension = explode('.',$input);
						$tmp_nm = strtotime($date).rand(0,100);
						$path = "./uploads/cms";
						//$new_filename = 'photo_'.strtotime($date).rand(1,99999);
						$new_filename = "query_".$tmp_nm.'.'.$extension[1];
						$uploadData = upload_file('queryfile', $path, $new_filename,'','',TRUE);
						if(!$uploadData)
						{
							$this->session->set_flashdata('error',$this->upload->display_errors());
							redirect(base_url().'CmsComplaint');
						}
						else
						{
							$flag=0;
							$scannedphoto_file=$this->input->post('scannedphoto1_hidd');
						}
					}
					}
					
				$excode = $this->input->post('examname');
				if( $excode == NULL ) $excode = 0;
				$country_code = $this->input->post('countrycd');
				if( $country_code == NULL ) $country_code = 0;
				$insert_data = array(	
					'category_code'	=> $this->input->post('querycat'),
					'subcatcode'	=> $this->input->post('querysubcat'),
					'exam_code'		=> $excode,
					'regnumber'		=> $this->input->post('memno'),
					'emp_name'		=> $this->input->post('employer_name'),
					'dob'			=> $this->input->post('dateofbirth'),
					'mem_name'		=> $this->input->post('name'),
					'email'			=> $this->input->post('email'),
					'mobile'		=> $this->input->post('mobileno'),
					'complain_details'	=> $this->input->post('qurytxtarea'),
					'member_type'	=> $this->input->post('stacode'),
					'added_by'		=> 'candidate',
					'attachment'	=> $new_filename,
					'country_code' => $country_code
				);
				if($this->master_model->insertRecord('cms_master',$insert_data)) {
					/*$insertid = $this->db->insert_id();
					//$this->db->order_by("compid", "desc");
					$this->db->order_by("sub_cat_cd","desc");
					$this->db->limit(1);
					//$uniqueid = $this->master_model->getValue("cms_master",array(),"compid");
					$uniqueid = $this->master_model->getValue("cms_master",array('compid >' =>81124),"sub_cat_cd");
					//array('compid >' => 81124)*/
					$insertid = $this->db->insert_id();
					$this->db->order_by("compid", "desc");
					$this->db->limit(1);
					//$uniqueid = $this->master_model->getValue("cms_master",array(),"compid");
					$uniqueid = $this->master_model->getValue("cms_master",array('sub_cat_cd !=' =>''),"sub_cat_cd");
					//echo $this->db->last_query(); exit;
					if($uniqueid) {
						//$last_count = $uniqueid;
						$max_no = (int) (str_replace("WB", "", trim($uniqueid)));
						$last_count = ++$max_no; 
					} else {
						//$last_count = 1;
						$last_count = "19000";
					}

					//$last_count = str_pad($last_count, 7, '0', STR_PAD_LEFT);
					//$randomNumber = mt_rand(0,9999);
					//$qnumber = "WBES".$randomNumber.$last_count;
					$qnumber = "WB".$last_count;
					$this->master_model->updateRecord("cms_master", array('sub_cat_cd' => $qnumber), array('compid' => $insertid ));
					//rename file
					if( !empty( $new_filename ) && !empty( $qnumber ) && count( $extension ) > 0 ) {
						$ext = $extension[1];
						$rnmfile = $qnumber.'.'.$ext;
						if(@ rename("./uploads/cms/".$new_filename,"./uploads/cms/".$rnmfile)) {
							$this->master_model->updateRecord("cms_master", array('attachment' => $rnmfile), array('compid' => $insertid ));
						}
					}
					/*get complain date to send in email*/
					$complaintdate = '';
					$complaintdate = $this->master_model->getValue("cms_master", array('compid'=>$insertid), "complain_date");
					/* Send Email */
					sleep(2); // Added delay time for sync of attachement between 2 web servers
					$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'complaint_email'));
				 	if(count($emailerstr) > 0) {
						$emailtext = $emailerstr[0]['emailer_text'];
						
						$newstring = str_replace("*category_code*", $this->input->post('querycat'), $emailtext);
						//$newstring = str_replace("#category_code#", $this->input->post('querycat'), $emailtext);
						$newstring = str_replace("*subcatcode*", $this->input->post('querysubcat'), $newstring);
						if( $excode == 0 ) {
							$examcode = '';
						} else {
							$examcode = $excode;
						}
						$memno = $this->input->post('memno');
						$mem_no_flg = 2; //registration number not added
						if( !empty( $memno ) ) 
							$mem_no_flg = 1; //registration number added
						$ath_flg = 'N'; //attachment not added
						if( !empty( $new_filename ) )
							$ath_flg = 'Y'; //attachment added
						$newstring = str_replace("*exam_code*", $examcode, $newstring);
						$newstring = str_replace("*regnumber*", $this->input->post('memno'), $newstring);
						$newstring = str_replace("*mem_no_flg*", $mem_no_flg, $newstring);
						$newstring = str_replace("*emp_name*", $this->input->post('employer_name'), $newstring);
						$newstring = str_replace("*dob*", date("d/m/Y", strtotime($this->input->post('dateofbirth'))), $newstring);
						$newstring = str_replace("*mem_name*", $this->input->post('name'), $newstring);
						$newstring = str_replace("*email*", $this->input->post('email'), $newstring);
						if( $country_code == 0 ) $cntrycd = '';
						else $cntrycd = $country_code;
						$newstring = str_replace("*country_code*", $cntrycd, $newstring);
						$newstring = str_replace("*mobile*", $this->input->post('mobileno'), $newstring);
						$newstring = str_replace("*complain_details*", $this->input->post('qurytxtarea'), $newstring);
						$newstring = str_replace("*complain_date*", date("d/m/Y h:i:s A", strtotime($complaintdate)), $newstring);
						$newstring = str_replace("*ath_flg*", $ath_flg, $newstring);
						
						$info_arr = array(
							'to' => 'webcare@iibf.org.in',
							//'to' => 'starsagar123@gmail.com',
							'from' => $emailerstr[0]['from'],
						 	'subject' => "comp_id#".$qnumber."#",
						 	'message' => $newstring
						);
							//die('email sent to '.$this->input->post('email'));
						if( !empty( $rnmfile ) ) {
							//$path = base_url()."/uploads/cms/".$rnmfile;
							
							$path = "./uploads/cms/".$rnmfile;		
						}
						else {
							$path ='';
						}
						if($this->Emailsending->mailsend_attch($info_arr,$path)){
							//die('email sent to '.$this->input->post('email'));
						}
						else {
							$this->session->set_flashdata('error','Error while sending email');
						}
					}
					
					//$this->session->set_flashdata('success','Query Submitted Successfully with ID '.$qnumber.' Quote this ID for any communication');
					redirect(base_url().'CmsComplaint/cms_complaint_sucess?CMP_ID='.$insertid);
				}
				else {
					$this->session->set_flashdata('error','Error occured while adding complaint');
					redirect(base_url().'CmsComplaint');
				}
			} else {
				$valerrors = validation_errors();
			}
		}
		$query_cats = $this->master_model->getRecords("cms_query_category");
		$this->load->helper('captcha');
		$this->session->unset_userdata("cmscaptcha");
		$this->session->set_userdata("cmscaptcha", rand(1, 100000));
		$vals = array(
			'img_path' => './uploads/applications/',
			'img_url' => './uploads/applications/',
		);
		$cap = create_captcha($vals);
		$_SESSION["cmscaptcha"] = $cap['word']; 
		$data = array("middle_content"=>'cms_complaint', 'query_cats'=> $query_cats, 'image' => $cap['image'], 'validation_errors' => $valerrors );
		$this->load->view('common_view_fullwidth',$data);	
	}
	//validate captcha
	public function check_captcha_cms($code) 
	{
		if(!isset($this->session->cmscaptcha) && empty($this->session->cmscaptcha))
		{
			redirect(base_url().'CmsComplaint/');
		}
		
		if($code == '' || $this->session->cmscaptcha != $code )
		{
			$this->form_validation->set_message('check_captcha_cms', 'Invalid %s.'); 
			$this->session->set_userdata("cmscaptcha", rand(1,100000));
			return false;
		}
		if($this->session->cmscaptcha == $code)
		{
			$this->session->set_userdata('cmscaptcha','');
			$this->session->unset_userdata("cmscaptcha");
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
			$vals = array('img_path' => './uploads/applications/','img_url' => './uploads/applications/',
						);
			$cap = create_captcha($vals);
			$data = $cap['image'];
			$_SESSION[$captchaname] = $cap['word'];
			echo $data;
		} 
	}
	//check captcha ajax function on submit of form
	public function ajax_check_captcha() {
		$code = (isset( $_POST['code'] )) ? $_POST['code'] : '';
		if( !empty( $code ) ) {
			if ( $_SESSION["cmscaptcha"] != $code ) {
				//$this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
				//$this->session->set_userdata("regcaptcha", rand(1, 100000));
				echo 'false'; exit;
			}
			else if ($_SESSION["cmscaptcha"] == $code)
			{
				//$this->session->unset_userdata("regcaptcha");
				// $this->session->set_userdata("mycaptcha", rand(1,100000));
				echo 'true'; exit;
			}	
		}
	}
	//get sub-categories ajax callback function
		public function get_query_subcat() {
		$catcode = (isset($_POST['catcode'])) ? $_POST['catcode'] : '';
		$subcatcode = (isset($_POST['subcat'])) ? $_POST['subcat'] : '';
		$html = ''; $is_membershipno = 0; $is_examselect = 0; $exhtml = '<option value="">--Select--</option>'; //
		if( !empty( $catcode ) ) {
			$catdetails = $this->master_model->getRecords("cms_query_category", array('category_code'=>$catcode));
			if( count( $catdetails ) > 0 ) {
				$catdetail = $catdetails[0];
				$is_membershipno = $catdetail['is_membershipno'];
				$is_examselect = $catdetail['is_examselect'];
			}
			$subcats = $this->master_model->getRecords("cms_query_subcategory", array('category_code'=>$catcode,'subcatcode!='=>21));	
			if( count( $subcats ) > 0 ) {
				$html = '<option value="">--Select--</option>';
				foreach( $subcats as $subcat ) {
					$html .= '<option value="'.$subcat["subcatcode"].'">'.$subcat["name"].'</option>';	
				}
			}
			/* get exams if query category is examination */
			if( $catcode == 'EXM' ) {
				$exams = array();
				if( empty( $subcatcode ) ) {
					$exams = $this->master_model->getRecords("cms_exams");
				} else {
					$exams = $this->db->query("select * from cms_exams where belong_subcat LIKE '%,".$subcatcode.",%' OR belong_subcat LIKE '".$subcatcode.",%' OR belong_subcat LIKE '%,".$subcatcode."' OR  belong_subcat = '".$subcatcode."' ");
					
					$exams = $exams->result_array(); //
				}
				if( count( $exams ) > 0 ) {
					//$exhtml .= '<option value="">--Select--</option>'; //
					foreach( $exams as $exam ) {
						$exhtml .= '<option value="'.$exam["exam_code"].'">'.$exam["name"].'</option>';	
					}
				}
			}
		}
		$data = array(
			'subcathtml' => $html,
			'is_membershipno' => $is_membershipno,
			'is_examselect' => $is_examselect,
			'examhtml' => $exhtml
		);
		echo json_encode($data); 
		exit;
	}
	public function get_exams() {
		$subcatcode = (isset($_POST['subcat'])) ? $_POST['subcat'] : '';
		$exhtml = '';
		if( !empty( $subcatcode ) )	{
			$exams = $this->db->query("select * from cms_exams where belong_subcat LIKE '%,".$subcatcode.",%' OR belong_subcat LIKE '".$subcatcode.",%' OR belong_subcat LIKE '%,".$subcatcode."' OR  belong_subcat = '".$subcatcode."' ");
			if( count( $exams ) > 0 ) {
				$exams = $exams->result_array();
				$exhtml .= '<option value="">--Select--</option>';
				foreach( $exams as $exam ) {
					$exhtml .= '<option value="'.$exam["exam_code"].'">'.$exam["name"].'</option>';	
				}
			}	
		}
		echo $exhtml; 
		exit;
	}
	public function validate_memregno() {
		$memno = (isset($_POST['memno'])) ? $_POST['memno'] : '';
		$name = $dateofbirth = $emplyername = $email = $mobno = $stacode = $errormsg = '';
		$erroflg = 0; $errorprofileflg = 0;
		if( !empty( $memno ) ) {
			$cnt = 0; $memtype = ''; 
			$cnt = $this->master_model->getRecordCount('member_registration',array('regnumber'=>$memno, 'isactive' => '1', 'isdeleted' => 0));
			//print_r( $this->db->last_query() ); exit;
			if( $cnt == 0 ) {
				$cnt = $this->master_model->getRecordCount('dra_members',array('regnumber'=>$memno, 'isdeleted' => 0));
				$memtype = 'dra_member';
			} else {
				$memtype = 'member';	
			}
			if( $cnt == 0 ) {
				$erroflg = 1;
				$errormsg = "Invalid Membership/Registration no";	
			} else {
				if( !empty( $memtype ) ) {
					if( $memtype == 'member' ) {
						$this->db->join('institution_master', 'institution_master.institude_id = member_registration.associatedinstitute','left');
						$results = $this->master_model->getRecords('member_registration', array('regnumber'=>$memno, 'isactive' => '1', 'isdeleted' => 0) );	
						if( count( $results ) > 0 ) {
							$result = $results[0];
							$name = $result['firstname'];
							if( !empty( $result['middlename'] ) ) {
								$name.= ' '.$result['middlename'];	
							}
							if( !empty( $result['lastname'] ) ) {
								$name.= ' '.$result['lastname'];	
							}
							$dateofbirth = $result['dateofbirth'];
							$emplyername = $result['name']; // institute name
							$email = $result['email'];
							$mobno = $result['mobile'];
							$stacode = $result['registrationtype'];
						}
					} else if($memtype == 'dra_member') {
						$results = $this->master_model->getRecords('dra_members',array('regnumber'=>$memno, 'isdeleted' => 0));
						if( count( $results ) > 0 ) {
							$result = $results[0];
							$name = $result['firstname'];
							if( !empty( $result['middlename'] ) ) {
								$name.= ' '.$result['middlename'];	
							}
							if( !empty( $result['lastname'] ) ) {
								$name.= ' '.$result['lastname'];	
							}
							$dateofbirth = $result['dateofbirth'];
							$emplyername = ''; // institute name
							$email = $result['email'];
							$mobno = $result['mobile'];
							$stacode = 'dra';
						}
					}
				}	
			}
			if( empty($email) || empty( $mobno ) ) {
				$errorprofileflg = 1;
				$errormsg = "Please update email ID/ Mobile No., Please <a href='".base_url()."Home/profile/'>click here</a> to edit your profile.";
			}
		}
		$data = array(
			'erroflg' => $erroflg,
			'errorprofileflg' => $errorprofileflg,
			'name' => $name,
			'employer_name' => $emplyername,
			'email' => $email,
			'mobileno' => $mobno,
			'stacode' => $stacode,
			'dateofbirth' => $dateofbirth,
			'errormsg' => $errormsg
		);
		echo json_encode($data);
		exit;
	}
	public function cms_complaint_sucess() {
		$cmpid = ( isset( $_GET['CMP_ID'] ) ) ? $_GET['CMP_ID'] : '';	
		if( !empty( $cmpid ) ) {
			$compcd = $this->master_model->getValue('cms_master', array('compid'=>$cmpid), 'sub_cat_cd');
			if( $compcd ) {
				$data = array("middle_content"=>'cms_complaint_sucess', 'compcd'=> $compcd );
				$this->load->view('common_view_fullwidth',$data);	
			} else {
				redirect(base_url().'CmsComplaint');
			}
		} else {
			redirect(base_url().'CmsComplaint');
		}
	}
	public function update_wrong_code_record()
	{
		exit;
		$update_id=$this->master_model->getRecords('cms_master',array('compid>'=>81788),'compid');
		//echo $this->db->last_query();
		
		$sub_cat_cd = 100663;
		foreach($update_id as $id_row)
		{
			//print_r($id_row['compid']);exit;
			echo 'in',$id_row['compid'],' ',$sub_cat_cd,'</br>';
			//$last_count = ++$sub_cat_cd;
			 $qnumber = "WB".$sub_cat_cd;
			//print_r($qnumber);exit;
			$update_data = array('sub_cat_cd'=>$qnumber);
			$where_arr = array('compid'=>$id_row['compid']);
			//$where_arr = array('compid'=>'81126');
			//$this>db->limit(5)
			$this->master_model->updateRecord('cms_master',$update_data,$where_arr);
			//exit;
			echo $this->db->last_query();
			echo '</br>';
			$sub_cat_cd++;
			
		}
		
	}
	public function send_mail_to_updated_record()
	{
		exit;
		$comp_id = array('81125');
		foreach($comp_id as $id_row)
		{
			$result_data = $this->master_model->getRecords('cms_master',array('compid'=>$id_row));
			print_r($result_data[0]);exit;
			$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'complaint_email'));
		if(count($emailerstr) > 0) {
			$emailtext = $emailerstr[0]['emailer_text'];
						
						$newstring = str_replace("*category_code*", $result_data[0]['querycat'],$emailtext);
						
						$newstring = str_replace("*subcatcode*", $this->input->post('subcatcode'),$newstring);
						if( $excode == 0 ) {
							$examcode = '';
						} else {
							$examcode = $excode;
						}
						$memno = $this->input->post('memno');
						$mem_no_flg = 2; //registration number not added
						if( !empty( $memno ) ) 
							$mem_no_flg = 1; //registration number added
						$ath_flg = 'N'; //attachment not added
						if( !empty( $new_filename ) )
							$ath_flg = 'Y'; //attachment added
						$newstring = str_replace("*exam_code*", $examcode, $newstring);
						$newstring = str_replace("*regnumber*", $this->input->post('memno'), $newstring);
						$newstring = str_replace("*mem_no_flg*", $mem_no_flg, $newstring);
						$newstring = str_replace("*emp_name*", $this->input->post('employer_name'), $newstring);
						$newstring = str_replace("*dob*", date("d/m/Y", strtotime($this->input->post('dateofbirth'))), $newstring);
						$newstring = str_replace("*mem_name*", $this->input->post('name'), $newstring);
						$newstring = str_replace("*email*", $this->input->post('email'), $newstring);
						if( $country_code == 0 ) $cntrycd = '';
						else $cntrycd = $country_code;
						$newstring = str_replace("*country_code*", $cntrycd, $newstring);
						$newstring = str_replace("*mobile*", $this->input->post('mobileno'), $newstring);
						$newstring = str_replace("*complain_details*", $this->input->post('qurytxtarea'), $newstring);
						$newstring = str_replace("*complain_date*", date("d/m/Y h:i:s A", strtotime($complaintdate)), $newstring);
						$newstring = str_replace("*ath_flg*", $ath_flg, $newstring);
						
						$info_arr = array(
							//'to' => 'webcare@iibf.org.in',
							'to' => 'starsagar123@gmail.com',
							'from' => $emailerstr[0]['from'],
						 	'subject' => "comp_id#".$qnumber."#",
						 	'message' => $newstring
						);
							//die('email sent to '.$this->input->post('email'));
						if( !empty( $rnmfile ) ) {
							//$path = base_url()."/uploads/cms/".$rnmfile;
							
							$path = "./uploads/cms/".$rnmfile;		
						}
						else {
							$path ='';
						}
						if($this->Emailsending->mailsend_attch($info_arr,$path)){
							//die('email sent to '.$this->input->post('email'));
						}
						else {
							$this->session->set_flashdata('error','Error while sending email');
						}
		    }
		}
		
		
	}
	
}