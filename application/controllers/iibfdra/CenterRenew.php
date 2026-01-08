<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class CenterRenew extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('dra_institute')) {
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
		$this->load->model('Emailsending');
        $this->load->helper('general_helper');
		$this->load->helper('agency_renewal_invoice_helper');        
    }
	
	public function index()
    { 
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
		
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");
		
		$login_agency   = $this->session->userdata('dra_institute');
        $agency_id      = $login_agency['dra_inst_registration_id'];
		$institute_code	= $login_agency['institute_code'];
		
		$todays_date = date('Y-m-d');
        $this->db->select('agency_center.*, city_master.city_name');
        $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');  
		$this->db->where('center_add_status','E');
		$this->db->where('center_type','T');
		$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
		$this->db->where('center_validity_to <',$todays_date);	
		$this->db->where('institute_code',$institute_code);
		//$this->db->where('is_renew!=1');		
		
		$this->db->where('agency_id',$agency_id);
		$res_arr = $this->master_model->getRecords("agency_center");
		$res_value = array();			
		
		$this->db->where('agency_id',$agency_id);
		$this->db->where('agency_center_renew.center_type','T');
		$this->db->order_by("agency_center_renew.created_on", "DESC");
    	$this->db->limit(1);
		$res_arr_renwal = $this->master_model->getRecords("agency_center_renew");
		$res_renw_chk = array();
		//echo $this->db->last_query();
		//print_r($this->session);		
		if(count($res_arr))
		{	
			foreach($res_arr as $row_val)
			{
				$res_value[] = $row_val;				
			}			
		}
		
		$data['center_result'] = $res_value;		
		$var_errors = '';
		
        $data = array(
            'middle_content' => 'renew_center_list',
            'states'         => $states,
			'center_listing' => $res_value,
            'active_exams'   => $res,
            'upload_data'    => $this->upload->data(),
			'var_errors'	 => $var_errors
        );
        $this->load->view('iibfdra/common_view', $data);
    }
	
	// List regular center to make payment for renew 	
	public function regular()
    { 
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');

        $res  = $this->master_model->getRecords("dra_exam_master a");
		
		$login_agency   = $this->session->userdata('dra_institute');
        $agency_id      = $login_agency['dra_inst_registration_id'];
		$institute_code	= $login_agency['institute_code'];
		
		$todays_date = date('Y-m-d');
        $this->db->select('agency_center.*, city_master.city_name');
        $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');  
		$this->db->where('center_type','R');
		$this->db->where('center_status','A');
		$this->db->where('agency_center.center_display_status','1');// added by Manoj on 19 mar 2019 to hide centers related batch from list	
	
		$this->db->where('center_validity_to !=','');	
		$this->db->where('institute_code',$institute_code);
		//$this->db->where('is_renew=1');
		$this->db->where('agency_id',$agency_id);
		$res_arr = $this->master_model->getRecords("agency_center");
		
		$res_value = array();	
		$renew_result = array();
		
		if(count($res_arr))
		{
			foreach($res_arr as $row_val)
			{
				$res_value[] = $row_val;
			}			
		}else{
			redirect(base_url() . 'iibfdra/InstituteHome/dashboard');
		}	
			
		$this->db->where('agency_center_renew.center_type','R');
		$this->db->where('agency_center_renew.agency_id',$agency_id);
		$this->db->order_by("agency_center_renew.agency_renew_id", "DESC");
    	$this->db->limit(1);
		$renew_result_val = $this->master_model->getRecords("agency_center_renew");		
		//echo $this->db->last_query();
		//exit;
		if(count($renew_result_val)){
			$renew_result = $renew_result_val[0];		
		}
		
		$var_errors = '';		
        $data = array(
            'middle_content' => 'renew_regular_center_list',
            'states'         => $states,
			'center_listing' => $res_value,
            'active_exams'   => $res,
			'renew_result'	 => $renew_result,
            'upload_data'    => $this->upload->data(),
			'var_errors'	 => $var_errors
        );
        $this->load->view('iibfdra/common_view', $data);
    }	
	
	//callback to validate scannedsignaturephoto
    //callback to validate scannedsignaturephoto
    function upload_file1(){
          if($_FILES['upload_file1']['size'] != 0)
          {
           return true;
        }  
        else{
            $this->form_validation->set_message('upload_file1', "Please select valid file.");
            return false;
        }
    }
    
    function cv1(){
          if($_FILES['cv1']['size'] != 0){
           return true;
        }  
        else{
            $this->form_validation->set_message('cv1', "Please select valid file.");
            return false;
        }
    }
	
	function upload_file2(){
	      if($_FILES['upload_file2']['size'] != 0){
	       return true;
	    }  
	    else{
	        $this->form_validation->set_message('upload_file2', "Please select valid file.");
	        return false;
	    }
	}

    /* Edit Center Details Function */	
    public function edit($id=NULL)
    {
        if (!$this->session->userdata('dra_institute')) 
        {
            redirect(base_url());
        }
		if(!is_numeric($id) || $id=='')
		{
			redirect(base_url().'iibfdra/Center/listing');
		}
        $centerResult = array();
        //$last   = $this->uri->total_segments();
        if(is_numeric($id))
        {
            $centerResult = $this->master_model->getRecords('agency_center',array('center_id'=>$id));
        	if(count($centerResult) <=0)
			{
				redirect(base_url().'iibfdra/Center/listing');
			}
		}
        if (isset($_POST['btnSubmit'])) 
        { 
			$date = date('Y-m-d H:i:s'); 
            //$this->form_validation->set_rules('location_name', 'Name of Location', 'trim|required|xss_clean');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|xss_clean');
           // $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
			
			//stdcode code added by manoj on 4 apr 2019
			$this->form_validation->set_rules('stdcode', 'STD code Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('office_no', 'Office Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('contact_person_name', 'Contact Person Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('contact_person_mobile', 'Mobile Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email_id', 'Email id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('center_type', 'Center Type', 'trim|required|xss_clean');
            $this->form_validation->set_rules('due_diligence', 'Due diligence ', 'trim|xss_clean');
            $this->form_validation->set_rules('gstin_no', 'GSTIN No', 'trim|xss_clean'); 
            $type = $_POST['center_type'];           
           
			if($type == 'T' && $type != "")
            { 
            $this->form_validation->set_rules('faculty_name1', 'Faculty Name 1', 'trim|required|xss_clean');
            $this->form_validation->set_rules('faculty_qualification1', 'Faculty Qualification 1', 'trim|required|xss_clean');
			
			 if ($_FILES['upload_file1']['name'] != '')
			 {
				 $this->form_validation->set_rules('upload_file1','Letter','file_required|file_allowed_type[pdf,PDF,jpeg,JPEG,png,PNG,jpg,JPG]|callback_upload_file1');
			 }
			  if ($_FILES['upload_file2']['name'] != '')
			  {
				//$this->form_validation->set_rules('upload_file2','Upload File2','file_required|file_allowed_type[pdf|PDF|jpeg|JPEG|png|PNG|jpg|JPG]|callback_upload_file2');
			  }
			  
		   if ($_FILES['cv1']['name'] != '')
			{
			$this->form_validation->set_rules('cv1','CV1','file_required|file_allowed_type[pdf,PDF]|callback_cv1');
			}
			  
			  
			  
            }
			
            if($this->form_validation->run()==TRUE)
            {  
				$tmp_nm = strtotime($date).rand(0,100);
				$new_filename = 'dra_'.$tmp_nm;
                $config=array('upload_path'=>'./uploads/iibfdra/agency_center' ,
						'allowed_types'=>'pdf|PDF|jpeg|JPEG|png|PNG|jpg|JPG',
						'file_name'=>$new_filename);
						$this->upload->initialize($config);
                /*File Uploadation of center and institute letter*/
                    if($_FILES['upload_file1']['name'] != '')
                    {
					  if($this->upload->do_upload('upload_file1'))
                        {
                            $dt1=$this->upload->data();
							$upload_file1 = $dt1['file_name'];
                        }
                        else
                        {
							$upload_file1 = $this->input->post('upload_file1_hidden');
                            $error =  $this->upload->display_errors();
                        }
                    }
					 else
                    {
                      $upload_file1 = $this->input->post('upload_file1_hidden');
                    }
					
					  /*File Uploadation of center and institute letter*/
					 if($_FILES['upload_file2']['name'] != '')
                    {
				        if($this->upload->do_upload('upload_file2'))
                        {
                            $dt2=$this->upload->data();
							$upload_file2 = $dt2['file_name'];
                        }
                        else
                        {
							$upload_file2 = $this->input->post('upload_file2_hidden');
                            $error =  $this->upload->display_errors();
                        }
                    }
					 else
                    {
                       $upload_file2 = $this->input->post('upload_file2_hidden');
                    }
					
					//=======new code for cv uplaod =================//					
					$temp_name = strtotime($date).rand(0,100);
                    $new_cv_filename = 'dra_cv_'.$temp_name;
                    $config=array('upload_path'=>'./uploads/iibfdra/agency_center/faculty_cv' ,
                            'allowed_types'=>'pdf|PDF',
                            'file_name'=>$new_cv_filename);
                    $this->upload->initialize($config);
					
                    if($_FILES['cv1']['name'] != '' )
                    {
                       
                        if($this->upload->do_upload('cv1'))
                        {
                            $data1=$this->upload->data();
                            $cv1 = $data1['file_name'];
                        }
                        else
                        {
                            $cv1 = $this->input->post('hiddencv1');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv1 = $this->input->post('hiddencv1');
                     
                    }
                    if($_FILES['cv2']['name'] != '' )
                    {
                        if($this->upload->do_upload('cv2'))
                        {
                            $data2=$this->upload->data();
                            $cv2 = $data2['file_name'];
                        }
                        else
                        {
                            $cv2 = $this->input->post('hiddencv2');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv2 = $this->input->post('hiddencv2');
                     
                    }
                    if($_FILES['cv3']['name'] != '' )
                    {

                        if($this->upload->do_upload('cv3'))
                        {
                            $data3=$this->upload->data();
                            $cv3 = $data3['file_name'];
                        }
                        else
                        {
                            $cv3 = $this->input->post('hiddencv3');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv3 = $this->input->post('hiddencv3');
                     
                    }
                    if($_FILES['cv4']['name'] != '' )
                    {
                        if($this->upload->do_upload('cv4'))
                        {
                            $data4=$this->upload->data();
                            $cv24= $data4['file_name'];
                        }
                        else
                        {
                            $cv4 = $this->input->post('hiddencv4');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv4 = $this->input->post('hiddencv4');
                     
                    }
                    if($_FILES['cv5']['name'] != '' )
                    {

                        if($this->upload->do_upload('cv5'))
                        {
                            $data5=$this->upload->data();
                            $cv5 = $data5['file_name'];
                        }
                        else
                        {
                            $cv5 = $this->input->post('hiddencv5');
                            $error =  $this->upload->display_errors();
                        }

                    }
                    else
                    {
                      $cv5 = $this->input->post('hiddencv5');
                     
                    }
					
					//====================end======
					
			if($this->input->post('center_type')=='R')
			{
				@unlink('uploads/iibfdra/agency_center/' . $centerResult[0]['upload_file1']);
				@unlink('uploads/iibfdra/agency_center/' . $centerResult[0]['upload_file2']);
				@unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv1']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv2']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv3']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv4']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv5']);
				
				$faculty_name1 = '';
				$faculty_qualification1 = '';
				$faculty_name2 = '';
				$faculty_qualification2 = '';
				$faculty_name3 = '';
				$faculty_qualification3 = '';
				$faculty_name4 = '';
				$faculty_qualification4 = '';
				$faculty_name5 = '';
				$faculty_qualification5 = '';
				$upload_file1 = '';
				$upload_file2 = '';
				$cv1 = '';
                $cv2 = '';
                $cv3 = '';
                $cv4 = '';
                $cv5 = '';
			}
			else
			{
				$faculty_name1 = $_POST['faculty_name1'];
				$faculty_qualification1 = $_POST['faculty_qualification1'];
				$faculty_name2 = $_POST["faculty_name2"];
				$faculty_qualification2 = $_POST['faculty_qualification2'];
				$faculty_name3 = $_POST["faculty_name3"];
				$faculty_qualification3 = $_POST['faculty_qualification3'];
				$faculty_name4 = $_POST["faculty_name4"];
				$faculty_qualification4 = $_POST['faculty_qualification4'];
				$faculty_name5 = $_POST["faculty_name5"];
				$faculty_qualification5 = $_POST['faculty_qualification5'];
				$upload_file1 = $upload_file1;
				$upload_file2 = $upload_file2;
				$cv1 = $cv1;
                $cv2 = $cv2;
                $cv3 = $cv3;
                $cv4 = $cv4;
                $cv5 = $cv5;
			}
		
				// 'is_renew' => 1 when user apply for renew				
				//  'location_name' => $_POST['location_name'],
				//	'city' => $_POST["city"],
				//  'state' => $_POST["state"],
				$update_data = array(                           
                            'address1' => $_POST["addressline1"],
                            'address2' => $_POST["addressline2"],
                            'address3' => $_POST["addressline3"],
                            'address4' => $_POST["addressline4"],
                            'district' => substr($_POST["district"], 0, 30),
                            'pincode' => $_POST["pincode"],
							
							'stdcode' => $_POST['stdcode'],
                            'office_no' => $_POST['office_no'],
                            'contact_person_name' => $_POST['contact_person_name'],
                            'contact_person_mobile' => $_POST['contact_person_mobile'],
                            'email_id' => $_POST['email_id'],
                            'center_type' => $_POST['center_type'],
                            'due_diligence' => @$_POST['due_diligence'],
                            'gstin_no' => @$_POST['gstin_no'],
							'remarks'  => $_POST['remarks'],
                            'faculty_name1' => $faculty_name1,
                            'faculty_qualification1' => $faculty_qualification1,
                            'faculty_name2' => $faculty_name2,
                            'faculty_qualification2' => $faculty_qualification2,
                            'faculty_name3' => $faculty_name3,
                            'faculty_qualification3' => $faculty_qualification3,
                            'faculty_name4' => $faculty_name4,
                            'faculty_qualification4' => $faculty_qualification4,
                            'faculty_name5' => $faculty_name5,
                            'faculty_qualification5' => $faculty_qualification5,
                            'upload_file1' => $upload_file1,
                            'upload_file2' => $upload_file2,
							'cv1' => $cv1,
                            'cv2' => $cv2,
                            'cv3' => $cv3,
                            'cv4' => $cv4,
                            'cv5' => $cv5,
                            'invoice_flag' => $_POST["invoice_flag"],
							'is_renew' 	   => 1,
							'center_status'=> 'IR',
							'pay_status'   => '2'
							
                );
              
				$center_id = $_POST['center_id'];
				$this->session->set_userdata('center_id', '0');	
				if ($this->master_model->updateRecord('agency_center',$update_data,array('center_id'=>$center_id), true)) 
                {
					
					$renewal_type 	= 'pay';
					$login_agency   = $this->session->userdata('dra_institute');
       				$agency_id      = $login_agency['dra_inst_registration_id'];
					$insert_data = array(
							'agency_id'				=> $agency_id,						
							'centers_id'			=> $center_id,								
							'renew_type'			=> $renewal_type,
							'pay_status'			=> '2',
							'center_type'			=> 'T',
							'created_on'			=> date('Y-m-d H:i:s'),
							);	
					
							
					if($this->master_model->insertRecord('agency_center_renew',$insert_data)){	
						//log_dra_admin($log_title = "DRA Admin Renew regular Agency", $log_message = serialize($insert_data));	
					}
										
					$this->session->set_flashdata('success', 'Center Applied for Renew Successfully..!');
					//redirect(base_url() .'iibfdra/CenterRenew/');	
					redirect(base_url() .'iibfdra/Center/listing');	
									
				} else {
					$this->session->set_flashdata('error', 'Error while during registration.please try again!');
					redirect(base_url() .'iibfdra/CenterRenew/edit/'.$id);
				}
            }
            else 
            {
                 $data['validation_errors'] = validation_errors(); 
            }
        }
       
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('city_master.state_code',$centerResult[0]['state']);
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');

        $city_id = $this->session->userdata['dra_institute']['address6'];
        $this->db->where('city_master.city_delete', '0');
        $this->db->where('city_master.id', $city_id);
        $city_name = $this->master_model->getRecords('city_master');
        //echo $this->db->last_query();
		$this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");
        
		 $data = array(
            'middle_content' => 'center_edit_renew',
            'states' => $states,
            'cities' => $cities,
            'active_exams' => $res,
            'city_name' => $city_name, 
            'centerResult' => $centerResult,
            'upload_data'  => $this->upload->data(),
            'edit_id' => $id
        );
		
        $this->load->view('iibfdra/common_view', $data);
    }
	
	 public function renew_edit($id=NULL)
    {
        if (!$this->session->userdata('dra_institute')) 
        {
            redirect(base_url());
        }
        if(!is_numeric($id) || $id=='')
        {
            redirect(base_url().'iibfdra/Center/listing');
        }
        $centerResult = array();
        //$last   = $this->uri->total_segments();
        if(is_numeric($id))
        {
            $centerResult = $this->master_model->getRecords('agency_center',array('center_id'=>$id));
            if(count($centerResult) <=0)
            {
                redirect(base_url().'iibfdra/Center/listing');
            }
        }
        if (isset($_POST['btnSubmit'])) 
        { 
            
            $date = date('Y-m-d H:i:s'); 
			//  Name of location is same as a City  
            if($this->input->post('city') != '' ){
                $_POST["location_name"] =  $this->input->post('city');
            }
           // $this->form_validation->set_rules('location_name', 'Name of Location', 'trim|required|xss_clean');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|xss_clean');
           // $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
			
			$this->form_validation->set_rules('stdcode', 'STD code Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('office_no', 'Office Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('contact_person_name', 'Contact Person Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('contact_person_mobile', 'Mobile Number', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email_id', 'Email id', 'trim|required|xss_clean');
            $this->form_validation->set_rules('center_type', 'Center Type', 'trim|required|xss_clean');
            $this->form_validation->set_rules('due_diligence', 'Due diligence ', 'trim|xss_clean');
            $this->form_validation->set_rules('gstin_no', 'GST No', 'trim|xss_clean'); 
            $type = $_POST['center_type']; 
            if($type == 'T' && $type != "")
            { 

            $this->form_validation->set_rules('faculty_name1', 'Faculty Name 1', 'trim|required|xss_clean');
            $this->form_validation->set_rules('faculty_qualification1', 'Faculty Qualification 1', 'trim|required|xss_clean');
            
            if ($_FILES['upload_file1']['name'] != '')
            {
                $this->form_validation->set_rules('upload_file1','Letter','file_required|file_allowed_type[pdf,PDF,jpeg,JPEG,png,PNG,jpg,JPG]|callback_upload_file1');
            }
            if ($_FILES['cv1']['name'] != '')
            {
                $this->form_validation->set_rules('cv1','CV1','file_required|file_allowed_type[pdf,PDF]|callback_cv1');
            }
			
            }
            if($this->form_validation->run()==TRUE)
            {  
               
                /*File Uploadation of center and institute letter*/
                    if($_FILES['upload_file1']['name'] != '')
                    {
                            $tmp_nm = strtotime($date).rand(0,100);
                            $new_filename = 'dra_'.$tmp_nm;
                            $config=array('upload_path'=>'./uploads/iibfdra/agency_center' ,
                                    'allowed_types'=>'pdf|PDF|jpeg|JPEG|png|PNG|jpg|JPG',
                                    'file_name'=>$new_filename);
                                    $this->upload->initialize($config);
                        if($this->upload->do_upload('upload_file1'))
                        {
                            $dt1=$this->upload->data();
                            $upload_file1 = $dt1['file_name'];
                        }
                        else
                        {
                            $upload_file1 = $this->input->post('upload_file1_hidden');
                            $error =  $this->upload->display_errors();
                        }
                    }
                     else
                    {
                      $upload_file1 = $this->input->post('upload_file1_hidden');
                    }
                    
                      /*File Uploadation of center and institute letter*/
                     if($_FILES['upload_file2']['name'] != '')
                    {   
                        $tmp_nm = strtotime($date).rand(0,100);
                        $new_filename = 'dra_'.$tmp_nm;
                        $config=array('upload_path'=>'./uploads/iibfdra/agency_center' ,
                        'allowed_types'=>'pdf|PDF|jpeg|JPEG|png|PNG|jpg|JPG',
                        'file_name'=>$new_filename);
                        $this->upload->initialize($config);
                        
                        if($this->upload->do_upload('upload_file2'))
                        {
                            $dt2=$this->upload->data();
                            $upload_file2 = $dt2['file_name'];
                        }
                        else
                        {
                            $upload_file2 = $this->input->post('upload_file2_hidden');
                            $error =  $this->upload->display_errors();
                        }
                    }
                     else
                    {
                       $upload_file2 = $this->input->post('upload_file2_hidden');
                    }

                    $temp_name = strtotime($date).rand(0,100);
                    $new_cv_filename = 'dra_cv_'.$temp_name;
                    $config=array('upload_path'=>'./uploads/iibfdra/agency_center/faculty_cv' ,
                            'allowed_types'=>'pdf|PDF',
                            'file_name'=>$new_cv_filename);
                    $this->upload->initialize($config);
					
                    if($_FILES['cv1']['name'] != '' )
                    {
                       
                        if($this->upload->do_upload('cv1'))
                        {
                            $data1=$this->upload->data();
                            $cv1 = $data1['file_name'];
                        }
                        else
                        {
                            $cv1 = $this->input->post('hiddencv1');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv1 = $this->input->post('hiddencv1');
                     
                    }
                    if($_FILES['cv2']['name'] != '' )
                    {
                        if($this->upload->do_upload('cv2'))
                        {
                            $data2=$this->upload->data();
                            $cv2 = $data2['file_name'];
                        }
                        else
                        {
                            $cv2 = $this->input->post('hiddencv2');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv2 = $this->input->post('hiddencv2');
                     
                    }
                    if($_FILES['cv3']['name'] != '' )
                    {

                        if($this->upload->do_upload('cv3'))
                        {
                            $data3=$this->upload->data();
                            $cv3 = $data3['file_name'];
                        }
                        else
                        {
                            $cv3 = $this->input->post('hiddencv3');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv3 = $this->input->post('hiddencv3');
                     
                    }
                    if($_FILES['cv4']['name'] != '' )
                    {
                        if($this->upload->do_upload('cv4'))
                        {
                            $data4=$this->upload->data();
                            $cv24= $data4['file_name'];
                        }
                        else
                        {
                            $cv4 = $this->input->post('hiddencv4');
                            $error =  $this->upload->display_errors();
                        }
                    }
                    else
                    {
                      $cv4 = $this->input->post('hiddencv4');
                     
                    }
                    if($_FILES['cv5']['name'] != '' )
                    {

                        if($this->upload->do_upload('cv5'))
                        {
                            $data5=$this->upload->data();
                            $cv5 = $data5['file_name'];
                        }
                        else
                        {
                            $cv5 = $this->input->post('hiddencv5');
                            $error =  $this->upload->display_errors();
                        }

                    }
                    else
                    {
                      $cv5 = $this->input->post('hiddencv5');
                     
                    }

                   
            if($this->input->post('center_type')=='R')
            {
                @unlink('uploads/iibfdra/agency_center/' . $centerResult[0]['upload_file1']);
                @unlink('uploads/iibfdra/agency_center/' . $centerResult[0]['upload_file2']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv1']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv2']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv3']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv4']);
                @unlink('uploads/iibfdra/agency_center/faculty_cv' . $centerResult[0]['cv5']);

                $faculty_name1 = '';
                $faculty_qualification1 = '';
                $faculty_name2 = '';
                $faculty_qualification2 = '';
                $faculty_name3 = '';
                $faculty_qualification3 = '';
                $faculty_name4 = '';
                $faculty_qualification4 = '';
                $faculty_name5 = '';
                $faculty_qualification5 = '';
                $upload_file1 = '';
                $upload_file2 = '';
                $cv1 = '';
                $cv2 = '';
                $cv3 = '';
                $cv4 = '';
                $cv5 = '';
            }
            else
            {
                $faculty_name1 = $_POST['faculty_name1'];
                $faculty_qualification1 = $_POST['faculty_qualification1'];
                $faculty_name2 = $_POST["faculty_name2"];
                $faculty_qualification2 = $_POST['faculty_qualification2'];
                $faculty_name3 = $_POST["faculty_name3"];
                $faculty_qualification3 = $_POST['faculty_qualification3'];
                $faculty_name4 = $_POST["faculty_name4"];
                $faculty_qualification4 = $_POST['faculty_qualification4'];
                $faculty_name5 = $_POST["faculty_name5"];
                $faculty_qualification5 = $_POST['faculty_qualification5'];
                $upload_file1 = $upload_file1;
                $upload_file2 = $upload_file2;
                $cv1 = $cv1;
                $cv2 = $cv2;
                $cv3 = $cv3;
                $cv4 = $cv4;
                $cv5 = $cv5;
            }
              if($centerResult[0]['center_status']=='R')
              {
                    $center_status = 'IR';
              }
              else
              {
                    $center_status = $centerResult[0]['center_status'];
              }
                $update_data = array(
                            'location_name' => $_POST["city"],
                            'address1' => $_POST["addressline1"],
                            'address2' => $_POST["addressline2"],
                            'address3' => $_POST["addressline3"],
                            'address4' => $_POST["addressline4"],
                            'district' => substr($_POST["district"], 0, 30),
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
							'stdcode' => $_POST['stdcode'],
                            'office_no' => $_POST['office_no'],
                            'contact_person_name' => $_POST['contact_person_name'],
                            'contact_person_mobile' => $_POST['contact_person_mobile'],
                            'email_id' => $_POST['email_id'],
                            'center_type' => $_POST['center_type'],
                            'center_status' => $center_status,
                            'due_diligence' => @$_POST['due_diligence'],
                            'gstin_no' => @$_POST['gstin_no'],
							'remarks'  => $_POST['remarks'],
                            'faculty_name1' => $faculty_name1,
                            'faculty_qualification1' => $faculty_qualification1,
                            'faculty_name2' => $faculty_name2,
                            'faculty_qualification2' => $faculty_qualification2,
                            'faculty_name3' => $faculty_name3,
                            'faculty_qualification3' => $faculty_qualification3,
                            'faculty_name4' => $faculty_name4,
                            'faculty_qualification4' => $faculty_qualification4,
                            'faculty_name5' => $faculty_name5,
                            'faculty_qualification5' => $faculty_qualification5,
                            'upload_file1' => $upload_file1,
                            'upload_file2' => $upload_file2,
                            'cv1' => $cv1,
                            'cv2' => $cv2,
                            'cv3' => $cv3,
                            'cv4' => $cv4,
                            'cv5' => $cv5,
                            'invoice_flag' => $_POST["invoice_flag"],            
                            'updated_on' => date('Y-m-d H:i:s')
                );
               // echo"<pre>"; print_r($update_data); exit;
                $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
                $center_id = $_POST['center_id'];
                if ($this->master_model->updateRecord('agency_center',$update_data,array('center_id'=>$center_id), true)) 
                {
                    /* Edit Agency Center Log Activities  */
                    $log_title ="Update log of agency_center table Institute Code: ".$institute_code;
                    $log_data = serialize($update_data);
                    $user_id = $institute_code;
                    storedDraActivity($log_title, $log_data, $user_id);

                    $this->session->set_flashdata('success', 'Center Updated Successfully..!');
                    //redirect(base_url() .'iibfdra/Center/edit/'.$id);
                    redirect(base_url() .'iibfdra/Center/listing');
                    
                } else 
                {
                    $this->session->set_flashdata('error', 'Error while during .please try again!');
                    redirect(base_url() .'iibfdra/CenterRenew/renew_edit/'.$id);

                }
            }
            else 
            {
                 $data['validation_errors'] = validation_errors(); 
            }
        }
       
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('city_master.state_code',$centerResult[0]['state']);
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');

        $city_id = $this->session->userdata['dra_institute']['address6'];
        $this->db->where('city_master.city_delete', '0');
        $this->db->where('city_master.id', $city_id);
        $city_name = $this->master_model->getRecords('city_master');
      
        //echo $this->db->last_query(); exit;
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");
        
        $data = array(
            'middle_content' => 'center_edit_renew',
            'states' => $states,
            'cities' => $cities,
            'active_exams' => $res,
            'city_name' => $city_name, 
            'centerResult' => $centerResult,
            'upload_data'  => $this->upload->data(),
            'edit_id' => $id
        );
        $this->load->view('iibfdra/common_view', $data);
    }
		
	/* Added Center View Function */
    public function view()
    {
        $center_id   = $this->uri->segment(4);        
		
        $this->db->select('agency_center_rejection.rejection'); 
		$this->db->order_by("agency_center_rejection.created_on", "DESC");
		$this->db->limit(1);
        $center_reject_text = $this->master_model->getRecords('agency_center_rejection', array('agency_center_rejection.center_id' => $center_id ));		
		
		$center_view = $this->master_model->getRecords('agency_center', array(
            'center_id' => $center_id
        ));		
		
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res = $this->master_model->getRecords("dra_exam_master a");
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
		$city_id = $center_view[0]['location_name'];
		
        $this->db->where('city_master.id',$center_view[0]['location_name']);
		$this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');
        $data   = array(
            'middle_content' => 'renew_center_view',
            'active_exams' => $res,
			'center_reject_text'=> $center_reject_text,
            'states' => $states,
            'cities' => $cities,
            'center_view' => $center_view
        );
        $this->load->view('iibfdra/common_view', $data);
    }
	
	
	/* Make Payment BY MANOJ To Renew Regular and Temporary Center*/
	public function renew_temporary_center()
    {
		$this->session->unset_userdata('center_id');
		$center_id   = $this->uri->segment(4);		
		$this->session->set_userdata('center_id', $center_id);		
		$this->session->set_userdata('center_type', 'T');
		$_POST['center_id'] = $center_id;
		$this->make_payment();		
	}
	
	
	/* Make Payment BY MANOJ To Renew Regular Center*/
	/* Make Payment BY MANOJ To Renew Regular and Temporary Center*/
	public function make_payment()
    {		
		$center_id = '';
		$agency_data = $this->session->userdata('dra_institute');
		$agency_id 	 = $agency_data['dra_inst_registration_id'];
		
		if($this->session->userdata('center_id') != ''){
			$center_id   = $this->session->userdata('center_id');	
		}				
		//$center_id   = $this->session->userdata('center_id');	 
		$institute_name    = $this->session->userdata['dra_institute']['institute_name'];
    	$institute_code    = $this->session->userdata['dra_institute']['institute_code'];
		
		if($agency_id  != "")
		{
			$cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
			$cgst_amt  = $sgst_amt = $igst_amt = '';
			$cs_total  = $igst_total = '';
			$getstate  = $getcenter = $getfees = array();
			$flag      = 1;
			
			// Varify and fetch Get Center Details
			if($center_id != ''){
				
				$this->db->where('agency_id',$agency_id);
				$this->db->where('centers_id',$center_id);
				$this->db->where('pay_status !=','1');
				$this->db->order_by("agency_center_renew.agency_renew_id", "DESC");
				$this->db->limit(1);
				$agency_renew_res = $this->master_model->getRecords('agency_center_renew');				
					
			}elseif($center_id ==''){
								
				$this->db->where('agency_id',$agency_id);
				$this->db->where('pay_status !=','1');
				$this->db->order_by("agency_center_renew.agency_renew_id", "DESC");
				$this->db->limit(1);
				$agency_renew_res = $this->master_model->getRecords('agency_center_renew');				
			}
			
			$center_id 			= $agency_renew_res[0]['centers_id']; // multiple center Id's
			$agency_renew_id 	= $agency_renew_res[0]['agency_renew_id']; // PK of agency_center_renew table
			$center_type 		= $agency_renew_res[0]['center_type'];			
			//$center_type = 'R'; //removed to do code for both R,T	
			
			//echo  '<br> center_id => '.$center_id;
			//echo  '<br> agency_renew_id => '.$agency_renew_id;
			//echo  '<br> center_type => '.$center_type;
			
			// Getting State of Center
			if (!empty($center_id)) {				
				// agency details from session
				$state = $agency_data['ste_code'];
				$gstin_no = $agency_data['gstin_no'];				
			}
			
			// center address code for Addres for Temporary cetner
			if($center_type == 'T'){
				
				 // Get Center Details only for type T
				$center_details = $this->master_model->getRecords('agency_center', array('center_id' => $center_id));
				$center_id = $center_details[0]['center_id']; 
				$center_type = $center_details[0]['center_type'];
				$invoice_flag = $center_details[0]['invoice_flag'];
				$agency_id = $center_details[0]['agency_id'];
			
				if($invoice_flag == "AS") /* Get State of Agency*/
				{
					if (!empty($agency_id)) 
					{   
						$state_result = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),array('main_state','gstin_no'));
						$state_code = $state_result[0]['main_state'];
						$gstin_no = $state_result[0]['gstin_no'];
					}
				}
				elseif($invoice_flag == "CS") /* Get State of Center*/
				{
					if (!empty($center_id)) 
					{   
						$state_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('state','gstin_no'));
						$state_code = $state_result[0]['state'];
						$gstin_no = $state_result[0]['gstin_no'];
					}
				}
			}
			
			// Payment Process
			if (isset($_POST['processPayment']) && $_POST['processPayment']) 
			{
				include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
				$key            = $this->config->item('sbi_m_key');
				$merchIdVal     = $this->config->item('sbi_merchIdVal');
				$AggregatorId   = $this->config->item('sbi_AggregatorId');
				$pg_success_url = base_url() . "iibfdra/CenterRenew/sbitranssuccess";
				$pg_fail_url    = base_url() . "iibfdra/CenterRenew/sbitransfail";
				$state          = $state;
				$gstin_no       = $gstin_no;
								
				//get state code,state name,state number.
				if (!empty($state)) {	
					$getstate=$this->master_model->getRecords('state_master',array('state_code'=>$state,'state_delete'=> '0'));
				}
				
				$this->session->unset_userdata('center_id');
				
				// added by Manoj for renew regular fee				
				if ($state == 'MAH') {
					if ($center_type == 'R') {
						//set a rate (e.g 9%,9% or 18%)
						$fee_amt 	= $this->config->item('DRA_regular_renew_fee');
						$cgst_rate = $this->config->item('DRA_inst_cgst_rate');
						$sgst_rate = $this->config->item('DRA_inst_sgst_rate');
						//set an amount as per rate
						$cgst_amt  = $this->config->item('DRA_regular_renew_cgst_amt');
						$sgst_amt  = $this->config->item('DRA_regular_renew_sgst_amt');
						//set an total amount
						$tax_type  = 'Intra';
						$amount    = $this->config->item('DRA_regular_renew_cs_total');
						$cs_total  = $amount;
					}elseif($center_type == 'T')
                    {
                        //set a rate (e.g 9%,9% or 18%)
						$fee_amt =  $this->config->item('DRA_mobile_apply_fee'); 
                        $cgst_rate=$this->config->item('DRA_inst_cgst_rate');
                        $sgst_rate=$this->config->item('DRA_inst_sgst_rate');
                        //set an amount as per rate
                        $cgst_amt=$this->config->item('DRA_mobile_cgst_amt');
                        $sgst_amt=$this->config->item('DRA_mobile_sgst_amt');
                        //set an total amount
                        $tax_type='Intra';    
                        $amount = $this->config->item('DRA_mobile_cs_total');
                        $cs_total=$amount;
                    }					
				} else {
					if ($center_type == 'R') {
						$fee_amt 	= $this->config->item('DRA_regular_renew_fee');
						$igst_rate  = $this->config->item('DRA_inst_igst_rate');
						$igst_amt   = $this->config->item('DRA_regular_renew_igst_amt');
						$tax_type   = 'Inter';
						$amount     = $this->config->item('DRA_regular_renew_igst_tot');
						$igst_total = $amount;
					}elseif($center_type == 'T')
                    {
                        $fee_amt =  $this->config->item('DRA_mobile_apply_fee'); 
						$igst_rate=$this->config->item('DRA_inst_igst_rate');
                        $igst_amt=$this->config->item('DRA_mobile_igst_amt');
                        $tax_type='Inter';
                        $amount = $this->config->item('DRA_mobile_igst_tot');
                        $igst_total=$amount; 
                    }					
				}

				//$pg_flag = 'IIBF_DRA_REN'; // pg flag set for Agency Center Renew //DRA //IIBF_DRA_REN
				$pg_flag = 'IIBFDRAREN'; // pg flag set for Agency Center Renew //DRA //IIBF_DRA_REN				
				// Add Details in Payment Transaction Table
				$insert_data     = array(
					'member_regnumber' => '',
					'gateway' => "sbiepay",
					'amount' => $amount,
					'date' => date('Y-m-d H:i:s'),
					'ref_id' => $agency_renew_id, // Primary Key of agency_center_renew table
					'description' => "DRA Agency Center Renew",
					'pay_type' => 17, // Agency Center Renew
					'status' => 2,
					'pg_flag' => $pg_flag
				);
				
				$pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
				$MerchantOrderNo = sbi_exam_order_id($pt_id);
				
				// manoj added custom_field for Agency Center Renew 
				$custom_field    = $agency_renew_id."^iibfregn^".$pg_flag."^".$MerchantOrderNo;
				
				// update receipt no. in payment transaction -
				$update_data     = array(
					'receipt_no' => $MerchantOrderNo,
					'pg_other_details' => $custom_field
				);
				
				$this->master_model->updateRecord('payment_transaction', $update_data, array(
					'id' => $pt_id
				));
				
				//$fee_amt = $amount;
				$invoice_insert_array = array(
					'pay_txn_id' => $pt_id,
					'receipt_no' => $MerchantOrderNo,
					'exam_code' => '',
					'state_of_center' => $state,
					'gstin_no' => $gstin_no,
					'app_type' => 'W', // W for Agency Center Renew
					'service_code' => $this->config->item('DRA_inst_service_code'),
					'qty' => '1',
					'state_code' => $getstate[0]['state_no'],
					'state_name' => $getstate[0]['state_name'],
					'tax_type' => $tax_type,
					'fee_amt' => $fee_amt,
					'cgst_rate' => $cgst_rate,
					'cgst_amt' => $cgst_amt,
					'sgst_rate' => $sgst_rate,
					'sgst_amt' => $sgst_amt,
					'igst_rate' => $igst_rate,
					'igst_amt' => $igst_amt,
					'cs_total' => $cs_total,
					'institute_code' => $institute_code,
                    'institute_name' => $institute_name,
					'igst_total' => $igst_total,
					'exempt' => $getstate[0]['exempt'],
					'created_on' => date('Y-m-d H:i:s')
				);
				
				$inser_id             = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);				
				$MerchantCustomerID   = $center_id;
				$data["pg_form_url"]  = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
				$data["merchIdVal"]   = $merchIdVal;
				$EncryptTrans         = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
				$aes                  = new CryptAES();
				$aes->set_key(base64_decode($key));
				$aes->require_pkcs5();
				$EncryptTrans         = $aes->encrypt($EncryptTrans);
				$data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
				$this->load->view('pg_sbi_form', $data);	
			} 
			else 
			{
				//$data["regno"] = $_REQUEST['regno'];
				$this->load->view('pg_sbi/make_payment_page');
        	}
		}
		else
		{
			$this->session->set_flashdata('error', 'Error during payment.please try again!');
			
			if($center_type == 'R'){
            	redirect(base_url() . 'iibfdra/Center/listing');
			}else{
			 	redirect(base_url() . 'iibfdra/Center');	
			}
		}
    }
	
	
	//if sbi transaction success
    public function sbitranssuccess()
    {       	   
	    //delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber = '';
			$email_id 		 = '';
			
            if (isset($_REQUEST['merchIdVal'])) {
                $merchIdVal = $_REQUEST['merchIdVal'];
            }
            if (isset($_REQUEST['Bank_Code'])) {
                $Bank_Code = $_REQUEST['Bank_Code'];
            }
            if (isset($_REQUEST['pushRespData'])) {
                $encData = $_REQUEST['pushRespData'];
            }
            //Sbi B2B callback
            //check sbi payment status with MerchantOrderNo 
            $q_details = sbiqueryapi($MerchantOrderNo);
			
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") 
				{
                    $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
					
                    if ($get_user_regnum[0]['status'] == 2) 
					{
                        if (count($get_user_regnum) > 0) 
						{
                            $renew_info = $this->master_model->getRecords('agency_center_renew', array(
                                'agency_renew_id' => $get_user_regnum[0]['ref_id']
                            ), 'agency_id');
                        }
						
						if(count($renew_info) > 0){
							 $user_info = $this->master_model->getRecords('dra_inst_registration', array(
                                'id' => $renew_info[0]['agency_id']
                            ), 'inst_head_email');
							
							$email_id = $user_info[0]['inst_head_email'];
						}
						
                        $update_data = array(
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata[8],
                            'paymode' => $responsedata[5],
                            'callback' => 'B2B'
                        );
					
					 $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));	
                    if ($this->db->affected_rows())
                    {
                       // $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));   
						// Get email of institute by its id                        
                        $update_data = array('pay_status' => '1');						
						
						$this->master_model->updateRecord('agency_center_renew', $update_data, array(
                            'agency_renew_id' => $get_user_regnum[0]['ref_id']
                        ));
						
						//================== NEW CODE ADDED TO update PAY status  BY Manoj============
						$agency_info = $this->master_model->getRecords('agency_center_renew', array(
                            'agency_renew_id' => $get_user_regnum[0]['ref_id']
                        ));	
								
						$agency_id 	= $agency_info[0]['agency_id']; 					
						$center_ids = $agency_info[0]['centers_id']; 
						$center_arr = explode(',',$center_ids);
						
						// ADD CODE TO SET PAY STATUS 1: SUCCESS  
						$update_data = array('center_status' => 'A','pay_status'  => '1');					
						
							foreach($center_arr as $center_id){
								$this->master_model->updateRecord('agency_center', $update_data, array(
									'center_id' => $center_id
								));								
							}
						//===============================================================================
						
						//Manage Log
                        $pg_response = "encData=" . $encData ."&merchIdVal=". $merchIdVal ."&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        
                        //$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'dra_agency_renew'
                        ));
						
                        if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
                            
							$final_str = $emailerstr[0]['emailer_text'];							
							$info_arr  = array(
								'to' => $email_id,
								'from' => $emailerstr[0]['from'],
								'subject' => $emailerstr[0]['subject'],
								'cc' => 'rohini@iibf.org.in',
								'message' => $final_str
							);
                            //genertate invoice and email send with invoice attach 8-7-2017                    
                            //get invoice    
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                                'receipt_no' => $MerchantOrderNo,
                                'pay_txn_id' => $get_user_regnum[0]['id']
                            ));
							
                            if (count($getinvoice_number) > 0){
								//generate_dra_invoice_number to generate_agnecy_renewal_invoice_number
                                $invoiceNumber = generate_agnecy_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('DRA_agency_renew_invoice_no_prefix') . $invoiceNumber;
                                }
                                $update_data = array(
                                    'invoice_no' => $invoiceNumber,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
																
                                $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                $attachpath = genarate_agnecy_renewal_invoice($getinvoice_number[0]['invoice_id']);
                            }
                            if ($attachpath != '') {
                                if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)){
									
                                    $this->session->set_flashdata('success', 'DRA agency center renewal has been done successfully !!');
                                    redirect(base_url() . 'iibfdra/CenterRenew/acknowledge/'.base64_encode($MerchantOrderNo));
                                } else {
                                    redirect(base_url('iibfdra/CenterRenew/acknowledge/'));
                                }
                            } else {
                                redirect(base_url('iibfdra/CenterRenew/acknowledge/'));
                            }
						}
                      }
                    }
                }
            }
            ///End of SBI B2B callback 
            redirect(base_url() . 'iibfdra/CenterRenew/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
	
	//if sbi transaction success
    public function sbitranssuccess_old_code()
    {
       $this->load->helper('general_agency_helper');
	   	   
	    //delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber = '';
			$email_id 		 = '';
            if (isset($_REQUEST['merchIdVal'])) {
                $merchIdVal = $_REQUEST['merchIdVal'];
            }
            if (isset($_REQUEST['Bank_Code'])) {
                $Bank_Code = $_REQUEST['Bank_Code'];
            }
            if (isset($_REQUEST['pushRespData'])) {
                $encData = $_REQUEST['pushRespData'];
            }
            //Sbi B2B callback
            //check sbi payment status with MerchantOrderNo 
            $q_details = sbiqueryapi($MerchantOrderNo);
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") 
				{
                    $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
					
                    if ($get_user_regnum[0]['status'] == 2) 
					{
                        if (count($get_user_regnum) > 0) 
						{
                            $renew_info = $this->master_model->getRecords('agency_center_renew', array(
                                'agency_renew_id' => $get_user_regnum[0]['ref_id']
                            ), 'agency_id');
                        }
						
						if(count($renew_info) > 0){
							 $user_info = $this->master_model->getRecords('dra_inst_registration', array(
                                'id' => $renew_info[0]['agency_id']
                            ), 'inst_head_email');
							
							$email_id = $user_info[0]['inst_head_email'];
						}
						
						// Get email of institute by its id                        
                        $update_data = array('pay_status' => '1');						
						
						$this->master_model->updateRecord('agency_center_renew', $update_data, array(
                            'agency_renew_id' => $get_user_regnum[0]['ref_id']
                        ));
						
						//================== NEW CODE ADDED TO update PAY status  BY Manoj============
						$agency_info = $this->master_model->getRecords('agency_center_renew', array(
                            'agency_renew_id' => $get_user_regnum[0]['ref_id']
                        ));	
								
						$agency_id 	= $agency_info[0]['agency_id']; 					
						$center_ids = $agency_info[0]['centers_id']; 
						$center_arr = explode(',',$center_ids);
						
						// ADD CODE TO SET PAY STATUS 1: SUCCESS  
						$update_data = array('center_status' => 'A','pay_status'  => '1');					
						
						foreach($center_arr as $center_id){
							$this->master_model->updateRecord('agency_center', $update_data, array(
								'center_id' => $center_id
							));
							
						log_dra_agency_center_detail($log_title = "Agecny Center Renew Payment",$center_id,serialize($update_data));	
							
						}
						//===============================================================================
						
						
                        $update_data = array(
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata[8],
                            'paymode' => $responsedata[5],
                            'callback' => 'B2B'
                        );
						
                        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                        //Manage Log
                        $pg_response = "encData=" . $encData ."&merchIdVal=". $merchIdVal ."&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        
                        //$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'dra_agency_renew'
                        ));
						
                        if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
                            $final_str         = $emailerstr[0]['emailer_text'];							
                            $info_arr          = array(
                                'to' => $email_id,
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'cc' => 'rohini@iibf.org.in',
                                'message' => $final_str
                            );
                            //genertate invoice and email send with invoice attach 8-7-2017                    
                            //get invoice    
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                                'receipt_no' => $MerchantOrderNo,
                                'pay_txn_id' => $get_user_regnum[0]['id']
                            ));
							
                            if (count($getinvoice_number) > 0){
								//generate_dra_invoice_number to generate_agnecy_renewal_invoice_number
                                $invoiceNumber = generate_agnecy_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('DRA_agency_renew_invoice_no_prefix') . $invoiceNumber;
                                }
                                $update_data = array(
                                    'invoice_no' => $invoiceNumber,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
								
                                $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                $attachpath = genarate_agnecy_renewal_invoice($getinvoice_number[0]['invoice_id']);
                            }
                            if ($attachpath != '') {
                                if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)){
                                    $this->session->set_flashdata('success', 'DRA agency center renewal has been done successfully !!');
                                    redirect(base_url() . 'iibfdra/CenterRenew/acknowledge/'.base64_encode($MerchantOrderNo));
                                } else {
                                    redirect(base_url('iibfdra/CenterRenew/acknowledge/'));
                                }
                            } else {
                                redirect(base_url('iibfdra/CenterRenew/acknowledge/'));
                            }
                        }
                    }
                }
            }
            ///End of SBI B2B callback 
            redirect(base_url() . 'iibfdra/CenterRenew/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
	
    //if sbi payment fail
    public function sbitransfail()
    {
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData              = $aes->decrypt($_REQUEST['encData']);
            $responsedata         = explode("|", $encData);
            $MerchantOrderNo      = $responsedata[0];
            $transaction_no       = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => $MerchantOrderNo
            ), 'ref_id,status');
            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                if (isset($_REQUEST['merchIdVal'])) {
                    $merchIdVal = $_REQUEST['merchIdVal'];
                }
                if (isset($_REQUEST['Bank_Code'])) {
                    $Bank_Code = $_REQUEST['Bank_Code'];
                }
                if (isset($_REQUEST['pushRespData'])) {
                    $encData = $_REQUEST['pushRespData'];
                }
                $update_data = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                    'auth_code' => '0399',
                    'bankcode' => $responsedata[8],
                    'paymode' => $responsedata[5],
                    'callback' => 'B2B'
                );
				
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo
                ));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
			
            $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
            redirect(base_url() . 'iibfdra/Center/listing');
            //Sbi fail code without callback
            echo "Transaction failed";
            echo "<script>
                (function (global) {
                
                    if(typeof (global) === 'undefined')
                    {
                        throw new Error('window is undefined');
                    }
                
                    var _hash = '!';
                    var noBackPlease = function () {
                        global.location.href += '#';
                
                        // making sure we have the fruit available for juice....
                        // 50 milliseconds for just once do not cost much (^__^)
                        global.setTimeout(function () {
                            global.location.href += '!';
                        }, 50);
                    };
                    
                    // Earlier we had setInerval here....
                    global.onhashchange = function () {
                        if (global.location.hash !== _hash) {
                            global.location.hash = _hash;
                        }
                    };
                
                    global.onload = function () {
                        
                        noBackPlease();                
                        // disables backspace on page except on input fields and textarea..
                        document.body.onkeydown = function (e) {
                            var elm = e.target.nodeName.toLowerCase();
                            if (e.which === 8 && (elm !== 'input' && elm  !== 'textarea')) {
                                e.preventDefault();
                            }
                            // stopping event bubbling up the DOM tree..
                            e.stopPropagation();
                        };                        
                    };
                
                })(window);
                </script>";
            exit;
        } else {
            die("Please try again...");
        }
    }
	
	public function acknowledge($MerchantOrderNo = NULL)
    {
        if (!empty($MerchantOrderNo)) {
            $payment_info = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => base64_decode($MerchantOrderNo)
            ), 'ref_id,transaction_no,date,amount,status');
        }
        if (count(@$payment_info) <= 0) 
		{
            redirect(base_url());
        }
		else
		{
			$data = array();		
			//print_r($payment_info);						
			$agency_info = $this->master_model->getRecords('agency_center_renew', array(
                            'agency_renew_id' => $payment_info[0]['ref_id']
                        ));
			
			$agency_id = $agency_info[0]['agency_id']; 
			$agency_details = $this->master_model->getRecords('dra_inst_registration',array(
                            'id' => $agency_id
                        ));
			
			$center_ids = $agency_info[0]['centers_id']; 
			$center_arr = explode(',',$center_ids);
			
			$this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        	$res  = $this->master_model->getRecords("dra_exam_master a");
		
			foreach($center_arr as $center_id){
			
			$this->db->select('agency_center.*,city_master.city_name');
            $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');   
            $center_info = $this->master_model->getRecords('agency_center', array(
                'center_id' => $center_id
            ));
			
				$center_details_array[] = $center_info[0]; 
			}		
			
			$data = array(
            'middle_content' => 'agency_renew_ack',
           	'application_number' => $payment_info[0]['ref_id'],
			'agency_info' => $agency_info,
			'active_exams' => $res,
			'center_arr'  => $center_details_array,
			'agency_details' => $agency_details,
			'payment_info' => $payment_info           
        	);
        	$this->load->view('iibfdra/common_view', $data);
			
		}
    }
	
	/* Check Captcha Function */
    public function ajax_check_captcha()
    {
        $code = $_POST['code'];
        // check if captcha is set -
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
            echo 'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
            echo 'true';
        }
    }
	/* Generate Captcha Function */
    public function generatecaptchaajax()
    {
        $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data;
    }
	/* Check Checkpin Function */
    public function check_checkpin($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin', $str);
                return false;
            } else
                $this->form_validation->set_message('error', ""); {
                return true;
            }
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin', $str);
            return false;
        }
    }


}
?>
