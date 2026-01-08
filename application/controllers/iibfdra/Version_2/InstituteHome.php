<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class InstituteHome extends CI_Controller {
	public $InstData;
	public function __construct() {
		parent::__construct();
		if(!$this->session->userdata('dra_institute')) {
			redirect('iibfdra/Version_2/InstituteLogin');
		}	
		$this->InstData = $this->session->userdata('dra_institute');
		$this->load->helper('master_helper');
		$this->load->model('master_model');	
		$this->load->model('UserModel');
		$this->load->helper('pagination_helper');
		$this->load->library('pagination');
		$this->load->helper('general_helper');
	}
	public function index() {
		$this->dashboard();
	}
	
	public function dashboard()
	{
		
		$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
		$res = $this->master_model->getRecords("dra_exam_master a");
		$data = array( 'active_exams' => $res, 'middle_content'=>'dashboard' );
		$this->load->view('iibfdra/Version_2/common_view',$data);
	}

	public function dashboard2()
	{
		$data = array('middle_content'=>'dashboard2' );
		$this->load->view('iibfdra/Version_2/common_view',$data);
	}

	public function draexamapplicants() {
		if( isset( $_GET['exCd'] ) ) {
			$examcode = trim( $_GET['exCd'] );
			$decdexamcode = base64_decode($examcode);
			if(!intval($decdexamcode))
			{
				$this->session->set_flashdata('error','This exam does not exists');
				redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');
			}
			$decdexamcode = intval($decdexamcode);
			//check if exam exists or not
			$examcount = $this->master_model->getRecordCount('dra_exam_master', array('exam_code' => $decdexamcode));
			if( $examcount > 0 ) {
				//check if exam is active or not
				$examact = $this->master_model->getRecords('dra_exam_activation_master', array('exam_code' => $decdexamcode),'exam_from_date, exam_to_date, exam_from_time, exam_to_time');
				if( count($examact) > 0 ) {
					//$comp_currdate = date('Y-m-d H:i:s');
					//$comp_frmdate = $examact[0]['exam_from_date'].' '.$examact[0]['exam_from_time'];
					//$comp_todate = $examact[0]['exam_to_date'].' '.$examact[0]['exam_to_time'];
					$comp_currdate = date('Y-m-d');
					$comp_frmdate = $examact[0]['exam_from_date'];
					$comp_todate = $examact[0]['exam_to_date'];
					if( strtotime($comp_currdate) >= strtotime($comp_frmdate) && strtotime($comp_currdate) <= strtotime($comp_todate) ) 
					{
						$data['result'] = array();
						$per_page = 50;
						$last = $this->uri->total_segments();
						$start = 0;
						$page = ($this->uri->segment($last)) ? $this->uri->segment($last) : 0;
						$searchText = '';
						$searchBy = '';
						$field = $value = $sortkey = $sortval = '';
						if($page!=0) {	
							$start = $page-1;	
						}
						$instdata = $this->session->userdata('dra_institute');
						$instcode = $instdata['institute_code'];
						//added
						$this->db->select('dra_members.firstname, dra_members.middlename, dra_members.lastname, dra_members.dateofbirth, dra_members.email, dra_member_exam.exam_center_code, dra_member_exam.exam_fee, dra_member_exam.pay_status, dra_member_exam.id as mem_examid, dra_member_exam.id');
						$this->db->where('dra_members.isdeleted',0);
						$this->db->join('dra_members','dra_member_exam.regid = dra_members.regid');
						
						//added to have listing of only those who are registered by logged in institute
						$this->db->where('dra_members.inst_code',$instcode);
						
						$this->db->where('dra_member_exam.exam_code',$decdexamcode);
						$this->db->where('dra_member_exam.dra_memberexam_delete',0);
						//do not include applicants in listing whoes payment is successful
						$this->db->where('dra_member_exam.pay_status !=',1);
						//do not show records which are added before date 2018-01-09 /*added this condition on 2018-01-10*/
						//$this->db->where('date(dra_member_exam.created_on) > ','2018-01-09');
						//$this->db->where('date(dra_member_exam.created_on) > ','2018-05-20');
					    //$this->db->where('date(dra_member_exam.created_on) > ','2018-07-15');
					    //$this->db->where('date(dra_member_exam.created_on) > ','2018-11-18');
					    //$this->db->where('date(dra_member_exam.created_on) > ','2019-01-13');
						//$this->db->where('date(dra_member_exam.created_on) >= ','2019-03-01');
						$this->db->where('date(dra_member_exam.created_on) >= ','2019-04-03');
						
						//show latest record first
						$this->db->order_by("dra_member_exam.id","desc");
							$res = $this->master_model->getRecords("dra_member_exam");
							//print_r($this->db->last_query()); exit();
						$resultarr = array();
						if( $res ) {
							foreach( $res as $result ) {
								//if( $result['pay_status'] != 1 ) { //do not include applicants in listing whoes payment is successful
									if( $result['pay_status'] == 3 ) { //if payment mode is NEFT and pending for approval by iibf
										$memexamid = $result['id'];
										//added for neft case
										$this->db->order_by("ptid", "desc");
										
										$transid = $this->master_model->getValue('dra_member_payment_transaction',array('memexamid'=>$memexamid), 'ptid');
										if( $transid ) {
											$utrno = $this->master_model->getValue('dra_payment_transaction',array('id'=>$transid), 'UTR_no');	
											$result['utr_no'] = $utrno;
											$resultarr[] = $result; 
										}
									} else { // pay_status is fail-0 or pending-2
										$result['utr_no'] = '';
										$resultarr[] = $result; 
									}
								//} 	
							}	
						}
						//print_r($resultarr);
						$data['startidx'] = 1;
						/* Removed pagination on 21-01-2017 */ 
						$data['info'] = $data['links'] = '';
						/*if( count($resultarr) > 0 ) {
							//new paginaion logic 
							$chunks = array();
							// How many applicants to show per page
							$per_page = 50; 
							// an index you can use to find offsets for pagination
							$index = 0;
							$total_row = count($resultarr);
							// loop through the applicants array in chunks, and create
							// an index or offset you can query from 
							foreach(array_chunk($resultarr, $per_page) as $result){
								$index++;
								$chunks[$index] = $result; // build your array 
							}
							$offset = 1;
							if( isset( $_GET['per_page'] ) ) {
								$offset = trim( $_GET['per_page'] );	
							}
							//if offset is not integer then redirect to first page
							if(!intval($offset)) {
								redirect(base_url().'iibfdra/Version_2/InstituteHome/draexamapplicants/?exCd='.base64_encode($decdexamcode));
							}
							$offset = intval($offset);
							//if page parameter is edited in URL and entered which does not exists the redirect to first page
							if( !array_key_exists($offset,$chunks)) {
								redirect(base_url().'iibfdra/Version_2/InstituteHome/draexamapplicants/?exCd='.base64_encode($decdexamcode));	
							}
							$data['result'] = $chunks[$offset];
							$config = array(
								'base_url' => base_url()."iibfdra/Version_2/InstituteHome/draexamapplicants/?exCd=".base64_encode($decdexamcode),
								'total_rows' => $total_row,
								'per_page' => $per_page,
								'page_query_string' => TRUE,
								'use_page_numbers' => TRUE,
								'full_tag_open' => '<ul class="pagination">',
								'full_tag_close' => '</ul><!--pagination-->',
							
								'first_link' => 'First',
								'first_tag_open' => '<li class="prev page paginate_button" >',
								'first_tag_close' => '</li>',
							
								'last_link' => 'Last',
								'last_tag_open' => '<li class="next page paginate_button" >',
								'last_tag_close' => '</li>',
							
								'next_link' => 'Next',
								'next_tag_open' => '<li class="next page paginate_button" >',
								'next_tag_close' => '</li>',
							
								'prev_link' => ' Previous',
								'prev_tag_open' => '<li class="prev page paginate_button" >',
								'prev_tag_close' => '</li>',
							
								'cur_tag_open' => '<li class="page active paginate_button" style="font-weight:bold;"><a href="javascript:void(0);" id="currLink">',
								'cur_tag_close' => '</a></li>',
							
								'num_tag_open' => '<li class="page paginate_button" >',
								'num_tag_close' => '</li>'
							);
							$this->pagination->initialize($config);
							$str_links = $this->pagination->create_links();
							$start = 1;
							if( $offset != 1 ) 
								$start = ($per_page*($offset-1))+1;
							//if( $start == 1 ) {
								//$end_of_total = $per_page;
							//} else {
								if( $per_page*$offset > $total_row )
									$end_of_total = $total_row;
								else {
									$end_of_total = $per_page*$offset;
								}
							//}
							$data['startidx'] = $start;
							$data['info'] = 'Showing '.($start).' to '.$end_of_total.' of '.$total_row.' entries';
							$data["links"] = $str_links;
						}*/
						$data['result'] = $resultarr;
						$data['middle_content']	= 'draexam_applicantlst';
						//$data['examcode']	= $examcode;
						$data['examcode']	= $decdexamcode;
						/* send active exams for display in sidebar */
						$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
						$res = $this->master_model->getRecords("dra_exam_master a");
						$data['active_exams'] = $res;
						$this->load->view('iibfdra/Version_2/common_view',$data);
					} else {//if exam is not active
						$this->session->set_flashdata('error','This exam is not active');
						redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');	
					}
				} else { //if exam not found in exam activation master then redirect to home
					$this->session->set_flashdata('error','This exam is not active');
					redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');	
				}
			} else { // if exam does not exists redirect to dashboard
				$this->session->set_flashdata('error','This exam does not exists');
				redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');
			}
			
		} else {
			$this->session->set_flashdata('error','URL is edited. Please try again');
			redirect(base_url().'iibfdra/Version_2/InstituteHome/dashboard');
		}
	}
	public function editprofile_backup() {
		if( $this->InstData ) {
			$institutedata = $this->InstData;
			$instid = $institutedata['id'];
			$instRes = $this->master_model->getRecords('dra_accerdited_master',array('id'=>$instid));
			$instdata = array();
			if(count($instRes))
			{
				$instdata = $instRes[0];
			}
			if(isset($_POST['btnSubmit']))
			{
				$this->form_validation->set_rules('instname','Institute Name','trim|required');
				$this->form_validation->set_rules('addressline1','Address line1','trim|required');
				$this->form_validation->set_rules('addressline2','Address line 2','trim|required');
				$this->form_validation->set_rules('pincode','Pin Code','trim|required|min_length[6]|max_length[6]');
				$this->form_validation->set_rules('mobile','Mobile No','trim|required|min_length[10]|max_length[10]');
				$this->form_validation->set_rules('email','Email ID','trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('contact_person','Contact Person','trim|required');
				$this->form_validation->set_rules('contactp_designation','Contact Person Designation','trim|required');
							
				if($this->form_validation->run()==TRUE)
				{
					$update_data = array(
						'institute_name'		=> $this->input->post('instname'),
						'address1'				=> $this->input->post('addressline1'),
						'address2'				=> $this->input->post('addressline2'),
						'address3'				=> $this->input->post('addressline3'),
						'address4'				=> $this->input->post('addressline4'),
						'pin_code'				=> $this->input->post('pincode'),
						'mobile'				=> $this->input->post('mobile'),
						'email'					=> $this->input->post('email'),
						'coord_name'			=> $this->input->post('contact_person'),
						'designation'			=> $this->input->post('contactp_designation')
					);
					
					if($this->master_model->updateRecord('dra_accerdited_master',$update_data, array('id'=>$instid)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $institutedata;
						log_dra_user($log_title = "DRA Institute Edit Profile Successful", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Profile updated successfully');
						redirect(base_url().'iibfdra/Version_2/InstituteHome/editprofile');
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $institutedata;
						log_dra_user($log_title = "DRA Institute Edit Profile Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating the profile');
						redirect(base_url().'iibfdra/Version_2/InstituteHome/editprofile');
					}
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
			/* send active exams for display in sidebar */
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");
			$data = array('middle_content' => 'institute_editprofile', 'instdata' => $instdata, 'active_exams' => $res );
			$this->load->view('iibfdra/Version_2/common_view',$data);	
		} else {
			redirect('iibfdra/Version_2/InstituteLogin');	
		}
	}
	
	public function editprofile() {

		if( $this->InstData ) {
			$institutedata = $this->InstData;
			$instid = $institutedata['id'];

			$this->db->join('dra_inst_registration','dra_accerdited_master.dra_inst_registration_id = dra_inst_registration.id');
			$instRes = $this->master_model->getRecords('dra_accerdited_master',array('dra_inst_registration_id'=>$instid));
			
			$instdata = array();
			if(count($instRes))
			{
				$instdata = $instRes[0];
			}
			if(isset($_POST['btnSubmit']))
			{
				$this->form_validation->set_rules('instname','Institute Name','trim|required');
				$this->form_validation->set_rules('addressline1','Address line1','trim|required');
				$this->form_validation->set_rules('addressline2','Address line 2','trim|required');
				$this->form_validation->set_rules('pincode','Pin Code','trim|required|min_length[6]|max_length[6]');
				$this->form_validation->set_rules('mobile','Mobile No','trim|required|min_length[10]|max_length[10]');
				$this->form_validation->set_rules('email','Email ID','trim|required|valid_email|xss_clean');
				$this->form_validation->set_rules('contact_person','Contact Person','trim|required');
				$this->form_validation->set_rules('contactp_designation','Contact Person Designation','trim|required');
							
				if($this->form_validation->run()==TRUE)
				{
					$update_data = array(
						'institute_name'		=> $this->input->post('instname'),
						'address1'				=> $this->input->post('addressline1'),
						'address2'				=> $this->input->post('addressline2'),
						'address3'				=> $this->input->post('addressline3'),
						'address4'				=> $this->input->post('addressline4'),
						'pin_code'				=> $this->input->post('pincode'),
						'mobile'				=> $this->input->post('mobile'),
						'email'					=> $this->input->post('email'),
						'coord_name'			=> $this->input->post('contact_person'),
						'designation'			=> $this->input->post('contactp_designation')
					);
					
					if($this->master_model->updateRecord('dra_accerdited_master',$update_data, array('id'=>$instid)))
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $institutedata;
						log_dra_user($log_title = "DRA Institute Edit Profile Successful", $log_message = serialize($desc));
						$this->session->set_flashdata('success','Profile updated successfully');
						redirect(base_url().'iibfdra/Version_2/InstituteHome/editprofile');
					}
					else
					{
						$desc['updated_data'] = $update_data;
						$desc['old_data'] = $institutedata;
						log_dra_user($log_title = "DRA Institute Edit Profile Unsuccessful", $log_message = serialize($desc));
						$this->session->set_flashdata('error','Error occured while updating the profile');
						redirect(base_url().'iibfdra/Version_2/InstituteHome/editprofile');
					}
				}
			}
			else
			{
				$data['validation_errors'] = validation_errors(); 
			}
			/* send active exams for display in sidebar */
	        $this->db->where('state_master.state_delete', '0');
	        $states = $this->master_model->getRecords('state_master');

	        $city_name ="";
	        $city_id = $instdata['address6'];
	        if(is_numeric($city_id))
            {
	            $this->db->where('city_master.city_delete', '0');
		        $this->db->where('city_master.id', $city_id);
		        $city_name_sql = $this->master_model->getRecords('city_master');
            	$city_name = strtoupper($city_name_sql[0]['city_name']);
            }
            else
            {
            	$city_name = strtoupper($instdata['address6']);
            }
	      	
			$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
			$res = $this->master_model->getRecords("dra_exam_master a");

			$data = array('middle_content' => 'institute_editprofile',  'states' => $states,
            'city_name' => $city_name,'instdata' => $instdata, 'active_exams' => $res );
             
			$this->load->view('iibfdra/Version_2/common_view',$data);	
		} else {
			redirect('iibfdra/Version_2/InstituteLogin');	
		}
	}

	##------------------------Transaction Details done by (PRAFULL)----------------##
	public function transaction()
	{
		
	}
	/* Change Password */
	public function changepass()
	{
		$data['error']='';
		if(isset($_POST['btn_password']))
		{
			$this->form_validation->set_rules('current_pass','Current Password','required|xss_clean');
			$this->form_validation->set_rules('txtnpwd','New Password','required|xss_clean');
			$this->form_validation->set_rules('txtrpwd','Re-type new password','required|xss_clean|matches[txtnpwd]');
			if($this->form_validation->run())
			{
				$current_pass=$this->input->post('current_pass');
				$new_pass=$this->input->post('txtnpwd');
				if( $current_pass == $new_pass ) {
					$this->session->set_flashdata('error','Current password and new password cannot be same.'); 
					redirect(base_url().'iibfdra/Version_2/InstituteHome/changepass/');
				}
 				$instdata = $this->session->userdata('dra_institute');
 				//print_r($instdata);
				$instcode = $instdata['institute_code'];
				
				$row=$this->master_model->getRecordCount('dra_accerdited_master',array('password'=>md5(trim($current_pass)),'institute_code'=>$instcode));
				if($row==0)
				{
					$this->session->set_flashdata('error','Current Password is Wrong.'); 
					redirect(base_url().'iibfdra/Version_2/InstituteHome/changepass/');
				}
				else
				{
					$input_array=array('password'=>md5(trim($new_pass)));
					$this->master_model->updateRecord('dra_accerdited_master',$input_array,array('institute_code'=>$instcode,'dra_inst_registration_id'=>$instdata['dra_inst_registration_id']));

					//echo $this->db->last_query(); die;
					//$this->session->unset_userdata('password');
					//$this->session->set_userdata("password",base64_encode($new_pass));
					$instsessdata = $this->session->userdata('dra_institute');
					$instsessdata['password'] = md5(trim($new_pass));
					$this->session->set_userdata("dra_institute",$instsessdata);
					
					log_dra_user($log_title = "DRA Institute Password Changed Successful", $log_message = serialize($input_array));
					
					$this->session->set_flashdata('success','Password Changed successfully.'); 
					redirect(base_url().'iibfdra/Version_2/InstituteHome/changepass/');
				}
		  } else {
				$data['validation_errors'] = validation_errors(); 
			}
		}
		/* send active exams for display in sidebar */
		$this->db->join('dra_exam_activation_master b','a.exam_code = b.exam_code', 'right');
		$res = $this->master_model->getRecords("dra_exam_master a");
		$data=array('middle_content'=>'change_pass','active_exams' => $res);
		$this->load->view('iibfdra/Version_2/common_view',$data);
	}
}
?>