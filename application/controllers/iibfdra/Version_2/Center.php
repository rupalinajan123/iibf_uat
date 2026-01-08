<?php

defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Center extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('dra_institute')) {
            redirect('iibfdra/Version_2/InstituteLogin');
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
        $this->load->helper('general_agency_helper');
        $this->load->model('Log_agency_model');
        $this->load->model('billdesk_pg_model');
        
        $this->agency_type = $this->session->userdata['dra_institute']['agency_type'];
    }

    public function get_client_ip_billdesk() {
            $ipaddress = '';
            if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
            else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
            else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
            else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
            else if(getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
            else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
            else
      $ipaddress = 'UNKNOWN';
            return $ipaddress;
    }

    /* Center Add Function */
    public function index()
    { 
        $var_errors=$upload_file1=$upload_file2=$cv1=$cv2=$cv3=$cv4=$cv5='';

        if (isset($_POST['btnSubmit'])) 
        {  
           // echo"<pre>"; print_r($_POST); print_r($_FILES);exit;
            //  Aayusha:Name of location is same as a City  
            if($this->input->post('city') != '' ){
                $_POST["location_name"] =  $this->input->post('city');
            }
            
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
            $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
           // $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
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
            $this->form_validation->set_rules('gstin_no', 'GST No', 'trim|xss_clean');
			
			//print_r($_FILES);
			//exit;
			
            $type = $_POST['center_type']; 
            if($type == 'T' && $type != "")
            { 
                $this->form_validation->set_rules('faculty_name1', 'Faculty Name 1', 'trim|required|xss_clean');
                $this->form_validation->set_rules('faculty_qualification1', 'Faculty Qualification 1', 'trim|required|xss_clean');
                $this->form_validation->set_rules('upload_file1','Letter','file_required|file_allowed_type[pdf,PDF,jpeg,JPEG,png,PNG,jpg,JPG]|callback_upload_file1');
				
                $this->form_validation->set_rules('cv1','CV','file_required|file_allowed_type[pdf,PDF]|callback_cv1');
            }
            if ($this->form_validation->run() == TRUE) {
            /*Store Center data in session*/
            if($_FILES['upload_file1']['name'] != '' || $_FILES['upload_file2']['name'] != '')
            {
                $upload_file1=$_FILES['upload_file1']['name'];
                $upload_file2=$_FILES['upload_file2']['name'];
                $tmp_nm = strtotime($date).rand(0,100);
                $new_filename = 'dra_'.$tmp_nm;
                $config=array('upload_path'=>'./uploads/iibfdra/Version_2/agency_center' ,
                'allowed_types'=>'pdf|PDF|jpeg|JPEG|png|PNG|jpg|JPG',
                'file_name'=>$new_filename);
                $this->upload->initialize($config);
                {
                if($this->upload->do_upload('upload_file1'))
                {
                    $dt1=$this->upload->data();
                    $upload_file1 = $dt1['file_name'];
                }
                else
                {
                    echo $error =  $this->upload->display_errors();
                }
                }   
                
                if($this->upload->do_upload('upload_file2'))
                {
                    $dt2=$this->upload->data();
                    $upload_file2 = $dt2['file_name'];
                }
                else
                {
                 $error =  $this->upload->display_errors();
                }
              }
            if($_FILES['cv1']['name'] != '' || $_FILES['cv2']['name'] != ''|| $_FILES['cv3']['name'] != ''|| $_FILES['cv4']['name'] != ''|| $_FILES['cv5']['name'] != '')
            {
                $cv1=$_FILES['cv1']['name'];
                $cv2=$_FILES['cv2']['name'];
                $cv3=$_FILES['cv3']['name'];
                $cv4=$_FILES['cv4']['name'];
                $cv5=$_FILES['cv5']['name'];
                $tmp_nm = strtotime($date).rand(0,100);
                $cv_filename = 'dra_cv_'.$tmp_nm;
                $config=array('upload_path'=>'./uploads/iibfdra/Version_2/agency_center/faculty_cv' ,
                'allowed_types'=>'pdf|PDF',
                'file_name'=>$cv_filename);
                $this->upload->initialize($config);
                {
                    if($this->upload->do_upload('cv1'))
                    {
                        $data1=$this->upload->data();
                        $cv1 = $data1['file_name'];
                    }
                    else
                    {
                        echo $error =  $this->upload->display_errors();
                    }
                }
                
                if($this->upload->do_upload('cv2'))
                {
                    $data2=$this->upload->data();
                    $cv2 = $data2['file_name'];
                }
                else
                {
                 $error =  $this->upload->display_errors();
                }
                if($this->upload->do_upload('cv3'))
                {
                    $data3=$this->upload->data();
                    $cv3 = $data3['file_name'];
                }
                else
                {
                 $error =  $this->upload->display_errors();
                }

                 if($this->upload->do_upload('cv4'))
                {
                    $data4=$this->upload->data();
                    $cv4 = $data4['file_name'];
                }
                else
                {
                 $error =  $this->upload->display_errors();
                }

                if($this->upload->do_upload('cv5'))
                {
                    $data5=$this->upload->data();
                    $cv5 = $data5['file_name'];
                }

                else
                {
                 $error =  $this->upload->display_errors();
                }
              }
                $agency_id         = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
                $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
				
                $data_arr = array(
                    'agency_id' => $agency_id ,
                    'location_name' => $_POST['location_name'],
                    'institute_code' => $this->session->userdata['dra_institute']['institute_code'],
                    'addressline1' => $_POST["addressline1"],
                    'addressline2' => $_POST["addressline2"],
                    'addressline3' => $_POST["addressline3"],
                    'addressline4' => $_POST["addressline4"],
                    'district'  => substr($_POST["district"], 0, 30),
                    'city'      => $_POST["city"],
                    'state'     => $_POST["state"],
                    'pincode'   => $_POST["pincode"],
					
					'stdcode' => $_POST['stdcode'],
                    'office_no' => $_POST['office_no'],
                    'contact_person_name' => $_POST['contact_person_name'],
                    'contact_person_mobile' => $_POST['contact_person_mobile'],
                    'email_id'  => $_POST['email_id'],
                    'center_type' => $_POST['center_type'],
                    'due_diligence' => @$_POST['due_diligence'],
                    'gstin_no'  => @$_POST['gstin_no'],
					'remarks' => $_POST['remarks'],					
                    'faculty_name1' => $_POST["faculty_name1"],
                    'faculty_qualification1' => $_POST["faculty_qualification1"],
                    'cv1' => $cv1,
                    'faculty_name2' => $_POST["faculty_name2"],
                    'faculty_qualification2' => $_POST["faculty_qualification2"],
                    'cv2' => $cv2,
                    'faculty_name3' => $_POST["faculty_name3"],
                    'faculty_qualification3' => $_POST["faculty_qualification3"],
                    'cv3' => $cv3,
                    'faculty_name4' => $_POST["faculty_name4"],
                    'faculty_qualification4' => $_POST["faculty_qualification4"],
                    'cv4' => $cv4,
                    'faculty_name5' => $_POST["faculty_name5"],
                    'faculty_qualification5' => $_POST["faculty_qualification5"],
                    'cv5' => $cv5,
                    'upload_file1' => $upload_file1,
                    'upload_file2' => $upload_file2,
                    'invoice_flag' => 'CS'
                    // 'invoice_flag' => $_POST['invoice_flag'] ,
                );
              //echo"<pre>"; print_r($data_arr); exit;
                $this->session->set_userdata('userinfo', $data_arr);
                $this->form_validation->set_message('error', "");

                /* User Log Activities  */
                $log_title ="Center Register Session Data.";
                $log_data = serialize($data_arr);
                $user_id = $institute_code;
                storedDraActivity($log_title, $log_data, $user_id);

                redirect(base_url() . 'iibfdra/Version_2/Center/preview');
            }

        }
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");

        $city_id = $this->session->userdata['dra_institute']['address6'];
        $this->db->where('city_master.city_delete', '0');
        $this->db->where('city_master.id', $city_id);
        $city_name = $this->master_model->getRecords('city_master');
      
        $data = array(
            'middle_content' => 'center_register',
            'states'         => $states,
            'cities'         => $cities,
            'active_exams'   => $res,
            'city_name'      => $city_name,
            'upload_data'    => $this->upload->data(),
            'var_errors'=>$var_errors
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }
    /* Added Center Preview Function */
    public function preview()
    {
        if (!$this->session->userdata('userinfo')) {
            redirect(base_url());
        }
        //echo"<pre>"; print_r($this->session->userdata());
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");
        $data = array(
            'middle_content' => 'center_preview',
            'states' => $states,
            'cities' => $cities,
            'active_exams' => $res
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }
    
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

    /*Stored Center Details Function */
    public function register()
    {
        if (!$this->session->userdata['userinfo']) 
        {
            redirect(base_url());
        }
        // Stored Data in agency center table
        $institute_code = $this->session->userdata['userinfo']['institute_code'];
       
        $this->db->join('state_master sm', 'sm.state_code = am.ste_code', 'LEFT');
        $dra_accerdited_data = $this->master_model->getRecords('dra_accerdited_master am',array('am.institute_code'=>$institute_code),'am.id,  am.institute_code, am.address3, am.address4, am.address6, am.ste_code, sm.state_name');

        $center_data_array = array(
            'agency_id'=> $this->session->userdata['userinfo']['agency_id'],
            'location_name' => $this->session->userdata['userinfo']['location_name'],
            'institute_code' => $this->session->userdata['userinfo']['institute_code'],
            'address1' => strtoupper($this->session->userdata['userinfo']['addressline1']),
            'address2' => strtoupper($this->session->userdata['userinfo']['addressline2']),
            'address3' => strtoupper($this->session->userdata['userinfo']['addressline3']),
            'address4' => strtoupper($this->session->userdata['userinfo']['addressline4']),
            'district' => strtoupper($this->session->userdata['userinfo']['district']),
            'city'     => strtoupper($this->session->userdata['userinfo']['city']),
            'state'    => $this->session->userdata['userinfo']['state'],
            'pincode'  => $this->session->userdata['userinfo']['pincode'],
			'stdcode'  => $this->session->userdata['userinfo']['stdcode'],
            'office_no'=> $this->session->userdata['userinfo']['office_no'],
            'contact_person_name' => $this->session->userdata['userinfo']['contact_person_name'],
            'contact_person_mobile' => $this->session->userdata['userinfo']['contact_person_mobile'],
            'email_id' => $this->session->userdata['userinfo']['email_id'],
            'center_type' => $this->session->userdata['userinfo']['center_type'],
            'due_diligence' => $this->session->userdata['userinfo']['due_diligence'],
            'gstin_no' => $this->session->userdata['userinfo']['gstin_no'], 
			'remarks' => $this->session->userdata['userinfo']['remarks'],           
            'center_add_status' => 'E',
            'created_on' => date('Y-m-d H:i:s'),
            'pay_status' => '2', 
            'faculty_name1' => $this->session->userdata['userinfo']['faculty_name1'],
            'faculty_qualification1' => $this->session->userdata['userinfo']['faculty_qualification1'],
            'cv1' => $this->session->userdata['userinfo']['cv1'],
            'faculty_name2' => $this->session->userdata['userinfo']['faculty_name2'],
            'faculty_qualification2' => $this->session->userdata['userinfo']['faculty_qualification2'],
            'cv2' => $this->session->userdata['userinfo']['cv2'],
            'faculty_name3' => $this->session->userdata['userinfo']['faculty_name3'],
            'faculty_qualification3' => $this->session->userdata['userinfo']['faculty_qualification3'],
            'cv3' => $this->session->userdata['userinfo']['cv3'],
            'faculty_name4' => $this->session->userdata['userinfo']['faculty_name4'],
            'faculty_qualification4' => $this->session->userdata['userinfo']['faculty_qualification4'],
            'cv4' => $this->session->userdata['userinfo']['cv4'],
            'faculty_name5' => $this->session->userdata['userinfo']['faculty_name5'],
            'faculty_qualification5' => $this->session->userdata['userinfo']['faculty_qualification5'],
            'cv5' => $this->session->userdata['userinfo']['cv5'],
            'upload_file1' => $this->session->userdata['userinfo']['upload_file1'],
            'upload_file2' => $this->session->userdata['userinfo']['upload_file2'],
            'invoice_flag' => $this->session->userdata['userinfo']['invoice_flag'],
            'check_city_state_for_active' => $dra_accerdited_data[0]['state_name'] 
        );
           //echo"<pre>"; print_r($center_data_array); exit;
        if($last_id = $this->master_model->insertRecord('agency_center', $center_data_array, true)) 
        {
            /* Agency Center Log Activities  */
            $log_title ="Insert log of agency_center table";
            $log_data = serialize($center_data_array);
            $user_id = $institute_code;
            storedDraActivity($log_title, $log_data, $user_id);

            $this->session->set_flashdata('success', 'Center Added Successfully..!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        } else {
            $this->session->set_flashdata('error', 'Error while during registration.please try again!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
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
            redirect(base_url().'iibfdra/Version_2/Center/listing');
        }
        $centerResult = array();
        //$last   = $this->uri->total_segments();
        if(is_numeric($id))
        {
            $centerResult = $this->master_model->getRecords('agency_center',array('center_id'=>$id));
            if(count($centerResult) <=0)
            {
                redirect(base_url().'iibfdra/Version_2/Center/listing');
            }
        }


        $login_agency   = $this->session->userdata('dra_institute');
        $agency_id      = $login_agency['id'];
        $institute_code = $login_agency['institute_code'];
        $this->db->select('agency_center.center_id,agency_center.agency_id,agency_center.institute_code,agency_center.location_name,agency_center.location_address,agency_center.email_id,agency_center.contact_person_mobile,agency_center.inst_type,agency_center.city,agency_center.center_status,agency_center.contact_person_name,agency_center.center_type,agency_center.pay_status,agency_center.center_add_status,agency_center.center_validity_to,agency_center.center_validity_from,city_master.city_name, state_master.state_name, agency_center.is_renew,agency_center.modified_on, agency_center.check_city_state_for_active,agency_center.payment_date');
        $this->db->join('dra_inst_registration','dra_inst_registration.id=agency_center.agency_id');
        $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');    
        $this->db->join('state_master','state_master.state_code = city_master.state_code','LEFT');       
        $center = $this->master_model->getRecords('agency_center', array('institute_code' => $institute_code,'dra_inst_registration.status'=>'1','agency_center.center_display_status'=>'1','agency_center.center_id'=>$id),'',array(
        'center_id' => 'DESC')); //(center_display_status : 1-Display,0-Hide)
        
        $this->db->join('state_master sm', 'sm.state_code = am.ste_code', 'LEFT');
        $dra_accerdited_data = $this->master_model->getRecords('dra_accerdited_master am',array('am.institute_code'=>$institute_code),'am.id,  am.institute_code, am.address3, am.address4, am.address6, am.ste_code, sm.state_name');

        $is_approve_status = '';
        $today_day = date('Y-m-d');   
        
        $to_date =  strtotime(date('Y-m-d',strtotime($center[0]['center_validity_to'])));        
        $from_date =  strtotime(date('Y-m-d',strtotime($center[0]['center_validity_from'])));
        $payment_date = $center[0]['payment_date'] != '' && $center[0]['payment_date'] != null ? strtotime(date('Y-m-d',strtotime($center[0]['payment_date']))) : '';

        $update_date = strtotime(date('Y-m-d',strtotime($center[0]['modified_on']))); 
        $exp_class = '';          
        if($to_date < $today_date){
            $expire_str = ' <span class="exp_font">(Expired)</span> ';
            $exp_class = 'redclass';
        }else{
            $expire_str = '';
            $exp_class = '';  
        }

        if($update_date > $to_date){
            $update_done = 1;
        } else {
            $update_done = 0;
        }

        if($center[0]['center_validity_to'] == ''){
            $expire_str = '';
            $exp_class = '';  
        }

        if($center[0]['center_status'] == 'IR' || $center[0]['center_status'] == 'AR'){ 
         
        } 
        elseif($center[0]['center_status'] == 'R') 
        {

        }
        else
        {             
            if($update_done == 1 )
            {
                $is_approve_status = 1;
            }
            else
            {
                if($expire_str != '')
                {
                }
                else
                {
                    $is_approve_status = 1;
                }
            }
        }   

        if(count($dra_accerdited_data) > 0)
        {
            if($is_approve_status == '1' &&
              (strtolower($dra_accerdited_data[0]['address3']) == strtolower($center[0]['location_name']) ||
              strtolower($dra_accerdited_data[0]['address3']) == strtolower($center[0]['city_name']) ||
              strtolower($dra_accerdited_data[0]['address3']) == strtolower($center[0]['state_name']) ||
              
              strtolower($dra_accerdited_data[0]['address4']) == strtolower($center[0]['location_name']) ||
              strtolower($dra_accerdited_data[0]['address4']) == strtolower($center[0]['city_name']) ||
              strtolower($dra_accerdited_data[0]['address4']) == strtolower($center[0]['state_name']) ||
              
              strtolower($dra_accerdited_data[0]['address6']) == strtolower($center[0]['location_name']) || 
              strtolower($dra_accerdited_data[0]['address6']) == strtolower($center[0]['city_name']) ||
              strtolower($dra_accerdited_data[0]['address6']) == strtolower($center[0]['state_name']) ||
              
              (strtolower($dra_accerdited_data[0]['state_name']) != "" && strtolower($dra_accerdited_data[0]['state_name']) == strtolower($center[0]['check_city_state_for_active'])))
            )
            { 
                $center_status = 'Active';
            }
            else
            {
                $center_status = 'Inactive';
            }    
        }

        if($center[0]['center_status'] == 'IR' || $center[0]['center_status'] == 'AR')
        { 
            
        }
        elseif($center[0]['center_status'] == 'R')
        { 
            
        } 
        else 
        {             
            if($update_done == 1 ) 
            {
                $is_approve_status = 1; 
            }
            else
            {
                if($expire_str != '')
                {
                    
                }
                else
                {
                    $is_approve_status = 1;    
                }
            }
        }   
        
        if ($center_status ==  'Active' || $is_approve_status == 1) 
        {
            redirect(base_url().'iibfdra/Version_2/Center/listing');
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
                            $config=array('upload_path'=>'./uploads/iibfdra/Version_2/agency_center' ,
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
                        $config=array('upload_path'=>'./uploads/iibfdra/Version_2/agency_center' ,
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
                    $config=array('upload_path'=>'./uploads/iibfdra/Version_2/agency_center/faculty_cv' ,
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
                @unlink('uploads/iibfdra/Version_2/agency_center/' . $centerResult[0]['upload_file1']);
                @unlink('uploads/iibfdra/Version_2/agency_center/' . $centerResult[0]['upload_file2']);
                @unlink('uploads/iibfdra/Version_2/agency_center/faculty_cv' . $centerResult[0]['cv1']);
                @unlink('uploads/iibfdra/Version_2/agency_center/faculty_cv' . $centerResult[0]['cv2']);
                @unlink('uploads/iibfdra/Version_2/agency_center/faculty_cv' . $centerResult[0]['cv3']);
                @unlink('uploads/iibfdra/Version_2/agency_center/faculty_cv' . $centerResult[0]['cv4']);
                @unlink('uploads/iibfdra/Version_2/agency_center/faculty_cv' . $centerResult[0]['cv5']);

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
							'remarks' => $_POST['remarks'],					
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
                    //redirect(base_url() .'iibfdra/Version_2/Center/edit/'.$id);
                    redirect(base_url() .'iibfdra/Version_2/Center/listing');
                    
                } else 
                {
                    $this->session->set_flashdata('error', 'Error while during .please try again!');
                    redirect(base_url() .'iibfdra/Version_2/Center/edit/'.$id);

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
            'middle_content' => 'center_edit',
            'states' => $states,
            'cities' => $cities,
            'active_exams' => $res,
            'city_name' => $city_name, 
            'centerResult' => $centerResult,
            'upload_data'  => $this->upload->data(),
            'edit_id' => $id
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }
    /* Center Listing Function */
    public function listing()
    {
        /*if($this->get_client_ip_billdesk() == '115.124.115.75'){
            
        }*/

        $login_agency   = $this->session->userdata('dra_institute');
        $agency_id      = $login_agency['id'];
        $institute_code = $login_agency['institute_code'];
        $this->db->select('agency_center.center_id,agency_center.agency_id,agency_center.institute_code,agency_center.location_name,agency_center.location_address,agency_center.email_id,agency_center.contact_person_mobile,agency_center.inst_type,agency_center.city,agency_center.center_status,agency_center.contact_person_name,agency_center.center_type,agency_center.pay_status,agency_center.center_add_status,agency_center.center_validity_to,agency_center.center_validity_from,city_master.city_name, state_master.state_name, agency_center.is_renew,agency_center.modified_on, agency_center.check_city_state_for_active,agency_center.payment_date');
        $this->db->join('dra_inst_registration','dra_inst_registration.id=agency_center.agency_id');
        $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');    
        $this->db->join('state_master','state_master.state_code = city_master.state_code','LEFT');       
        $center_listing = $this->master_model->getRecords('agency_center', array('institute_code' => $institute_code,'dra_inst_registration.status'=>'1','agency_center.center_display_status'=>'1'),'',array(
        'center_id' => 'DESC')); //(center_display_status : 1-Display,0-Hide)
        //echo $this->db->last_query();exit;
        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res  = $this->master_model->getRecords("dra_exam_master a");
     //  echo $this->db->last_query();exit;

        $this->db->join('state_master sm', 'sm.state_code = am.ste_code', 'LEFT');
        $dra_accerdited_data = $this->master_model->getRecords('dra_accerdited_master am',array('am.institute_code'=>$institute_code),'am.id,  am.institute_code, am.address3, am.address4, am.address6, am.ste_code, sm.state_name');

        $this->db->join('exam_invoice ei', 'ei.pay_txn_id = pt.id AND ei.institute_code = '.$institute_code);
        // $this->db->where("ei.institute_code", $institute_code);
        // $this->db->order_by("pt.id", $institute_code);
        $this->db->where('YEAR(pt.date) >=', 2025);
        $arr_transaction = $this->master_model->getRecords('payment_transaction pt',
            array('pt.pay_type'=>17),'pt.id,pt.proformo_invoice_no,pt.status,pt.transaction_no,pt.receipt_no,pt.transaction_details,pt.qty,pt.date,pt.amount,pt.tds_amount,ei.center_name,ei.invoice_no,ei.invoice_image,ei.invoice_id');
        
        $data = array(
            'middle_content' => 'center_list',
            'active_exams' => $res,
            'agency_type' => $this->agency_type,
            'center_listing' => $center_listing,
            'agency_id' => $agency_id,
            'dra_accerdited_data' => $dra_accerdited_data,
            'arr_transaction' => $arr_transaction
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }
    /* Added Center View Function */
    public function view($center_id='')
    {
    	$pay_amount ="";
        //$center_id   = $this->uri->segment(4);

        $this->db->select('agency_center_rejection.rejection'); 
        $this->db->order_by("agency_center_rejection.created_on", "DESC");
        $this->db->limit(1);
        $center_reject_text = $this->master_model->getRecords('agency_center_rejection', array('agency_center_rejection.center_id' => $center_id ));

        /* $this->db->select('agency_center.*,agency_center_rejection.rejection'); 
        $this->db->join('agency_center_rejection', 'agency_center.center_id = agency_center_rejection.center_id','left'); */
		
		$agency_center_renew = array();
		$agency_center_renew_val = array();
		$agency_id 	= $this->session->userdata['dra_institute']['dra_inst_registration_id'];
		$where = "FIND_IN_SET('".$center_id."', centers_id)"; 
                 $this->db->where($where);
				 $this->db->where('agency_id',$agency_id );
               //  $this->db->where_in('centers_id',$agency_center['center_id']);
                 $this->db->order_by('agency_renew_id',"desc");
                 $this->db->limit(1);
                $agency_center_renew = $this->master_model->getRecords('agency_center_renew');
               //  $agency_center_renew =$agency_center_renew->result_array();
        if(count($agency_center_renew) > 0){
             $agency_center_renew_val['pay_status'] = $agency_center_renew[0]['pay_status'];
             $agency_center_renew_val['renew_type'] = $agency_center_renew[0]['renew_type'];
        	}
        	else{
				//$agency_center_renew_val = array();
        		$agency_center_renew_val['pay_status'] = '';
        		$agency_center_renew_val['renew_type'] = "";
        	}
		
        $center_view = $this->master_model->getRecords('agency_center', array('agency_center.center_id' => $center_id ));
        $CenterPayDetails = [];
        if($center_view[0]['pay_status']=='1')
        {
           $this->db->select('payment_transaction.amount'); 
	       $this->db->where('payment_transaction.pay_type', '16');
	       $pay_amount = $this->master_model->getRecords('payment_transaction', array('payment_transaction.ref_id' => $center_id )); 

            $this->db->join('exam_invoice ei', 'pt.id = ei.pay_txn_id');
           $this->db->where('pt.pay_type', '12');
           $CenterPayDetails = $this->master_model->getRecords('payment_transaction pt', array('pt.ref_id' => $center_id )); 
        }

        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res = $this->master_model->getRecords("dra_exam_master a");
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');

        $login_agency   = $this->session->userdata('dra_institute');
        $institute_code = $login_agency['institute_code'];
        
        $this->db->join('exam_invoice ei', 'ei.pay_txn_id = pt.id AND ei.institute_code = '.$institute_code);
        $this->db->where_in('pt.pay_type',[12,16]);
        $arr_transaction = $this->master_model->getRecords('payment_transaction pt',
            array('pt.ref_id'=>$center_id),'pt.id,pt.proformo_invoice_no,pt.status,pt.transaction_no,pt.receipt_no,pt.transaction_details,pt.qty,pt.date,pt.amount,pt.tds_amount,ei.center_name,ei.invoice_no,ei.invoice_image,ei.invoice_id');
        
        $data   = array(
            'middle_content' => 'center_view',
            'active_exams' => $res,
            'arr_transaction' => $arr_transaction,
            'states' => $states,
            'cities' => $cities,
            'center_view' => $center_view,
			'renew_val'=> $agency_center_renew_val,
            'pay_amount' => $pay_amount,
            'center_reject_text' =>$center_reject_text,
            'CenterPayDetails' =>$CenterPayDetails
            
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }

    /* Added Center View Function */
    public function GSTEdit($center_id='')
    {
        $pay_amount = "";

        if (isset($_POST['edit_gst']) && $_POST['edit_gst'] == 'Update') 
        {     
            $date = date('Y-m-d H:i:s'); 
            
            // $this->form_validation->set_rules('gstin_no', 'GST No', 'trim|xss_clean|max_length[25]|required');
            // $this->form_validation->set_rules('invoice_flag', 'Invoice Flag', 'xss_clean|required'); 
            
            /*if($this->form_validation->run()==TRUE)
            {*/  
                if (isset($_POST['gstin_no']) && $_POST['gstin_no'] != '') {
                    $update_data = array(
                        'gstin_no' => @$_POST['gstin_no'],
                        // 'invoice_flag' => $_POST["invoice_flag"],            
                        'updated_on' => date('Y-m-d H:i:s')
                    );    
                } else {
                    $update_data = array(
                        'gstin_no' => '',
                        // 'invoice_flag' => $_POST["invoice_flag"],            
                        'updated_on' => date('Y-m-d H:i:s')
                    );    
                }
                
               
                $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
                $center_id = $_POST['center_id'];
                if ($this->master_model->updateRecord('agency_center',$update_data,array('center_id'=>$center_id), true)) 
                {
                    /* Edit Agency Center Log Activities  */
                    $log_title ="Update log of GST NO of agency_center table Institute Code: ".$institute_code;
                    $log_data = serialize($update_data);
                    $user_id = $institute_code;
                    storedDraActivity($log_title, $log_data, $user_id);

                    $this->session->set_flashdata('success', 'Center Updated Successfully..!');
                    //redirect(base_url() .'iibfdra/Version_2/Center/edit/'.$id);
                    redirect(base_url() .'iibfdra/Version_2/Center/listing');
                    
                } 
                else 
                {
                    $this->session->set_flashdata('error', 'Error while during .please try again!');
                    redirect(base_url() .'iibfdra/Version_2/Center/GSTEdit/'.$id);
                }
            /*}
            else 
            {
                 $data['validation_errors'] = validation_errors(); 
            }*/
        }

        $this->db->select('agency_center_rejection.rejection'); 
        $this->db->order_by("agency_center_rejection.created_on", "DESC");
        $this->db->limit(1);
        $center_reject_text = $this->master_model->getRecords('agency_center_rejection', array('agency_center_rejection.center_id' => $center_id ));

        $agency_center_renew = array();
        $agency_center_renew_val = array();
        $agency_id  = $this->session->userdata['dra_institute']['dra_inst_registration_id'];
        $where = "FIND_IN_SET('".$center_id."', centers_id)"; 
                 $this->db->where($where);
                 $this->db->where('agency_id',$agency_id );
               //  $this->db->where_in('centers_id',$agency_center['center_id']);
                 $this->db->order_by('agency_renew_id',"desc");
                 $this->db->limit(1);
                $agency_center_renew = $this->master_model->getRecords('agency_center_renew');
               //  $agency_center_renew =$agency_center_renew->result_array();
        if(count($agency_center_renew) > 0){
             $agency_center_renew_val['pay_status'] = $agency_center_renew[0]['pay_status'];
             $agency_center_renew_val['renew_type'] = $agency_center_renew[0]['renew_type'];
            }
            else{
                //$agency_center_renew_val = array();
                $agency_center_renew_val['pay_status'] = '';
                $agency_center_renew_val['renew_type'] = "";
            }
        
        $center_view = $this->master_model->getRecords('agency_center', array('agency_center.center_id' => $center_id ));

        if($center_view[0]['pay_status']=='1')
        {
           $this->db->select('payment_transaction.amount'); 
           $this->db->where('payment_transaction.pay_type', '16');
           $pay_amount = $this->master_model->getRecords('payment_transaction', array('payment_transaction.ref_id' => $center_id )); 
        }

        $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
        $res = $this->master_model->getRecords("dra_exam_master a");
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('city_master.city_delete', '0');
        $cities = $this->master_model->getRecords('city_master');
        $data   = array(
            'middle_content' => 'center_gst_edit',
            'active_exams' => $res,
            'states' => $states,
            'cities' => $cities,
            'center_view' => $center_view,
            'renew_val'=> $agency_center_renew_val,
            'pay_amount' => $pay_amount,
            'center_reject_text' =>$center_reject_text
            
        );
        $this->load->view('iibfdra/Version_2/common_view', $data);
    }

    /* Make Payment */
    public function make_payment()
    {
        $institute_name    = $this->session->userdata['dra_institute']['institute_name'];
        $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
        /* $center_id      = $this->uri->segment(4); */

        $center_id = $_POST['center_id'];

        if($center_id != "" && $center_id > 0 && $_POST['btnSubmit1'] == 'Proceed To Pay')
        {
            $cgst_rate = $sgst_rate  = $igst_rate = $tax_type = '';
            $cgst_amt  = $sgst_amt   = $igst_amt = '';
            $cs_total  = $igst_total = '';
            $getstate  = $getcenter  = $getfees = array();
            $flag      = 1;
            $state_code = $gstin_no = '';
            
            // Get Center Details
            $center_details = $this->master_model->getRecords('agency_center', array('center_id' => $center_id));
            
            $agencyAmount = $center_details[0]['required_amount'];

            if ($agencyAmount == null || $agencyAmount == '' || $agencyAmount == 0) 
            {
                $this->session->set_flashdata('error', 'Amount should be greater that zero.');
                redirect(base_url().'iibfdra/Version_2/Center/listing');
            }
            
            $center_id    = $center_details[0]['center_id']; 
            $center_type  = $center_details[0]['center_type'];
            $agency_id    = $center_details[0]['agency_id'];
            
            // $invoice_flag = $center_details[0]['invoice_flag'];
            $invoice_flag = "CS"; // Gaurav added static invoice flag

            if($invoice_flag == "AS") /* Get State of Agency */
            {
                if (!empty($agency_id)) 
                {   
                    $state_result = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),array('main_state','gstin_no'));
                    
                    $state_code = $state_result[0]['main_state'];
                    $gstin_no   = $state_result[0]['gstin_no'];
                }
            }
            elseif($invoice_flag == "CS") /* Get State of Center */
            {
                if (!empty($center_id)) 
                {   
                    $state_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('state','gstin_no'));
                    
                    $state_code = $state_result[0]['state'];
                    $gstin_no   = $state_result[0]['gstin_no'];
                }
            }
            
            // Payment Process
            /*if (isset($_POST['processPayment']) && $_POST['processPayment']) 
            {*/
                // TDS functionality 
                $totalAmountWithTds    = base64_decode($_POST['final_amount']);
                $tdsAmount             = base64_decode($_POST['tds_amount']);
                $totalAmountWithoutTds = $totalAmountWithTds+$tdsAmount;
                $tdsFlag               = $_POST['TDS'];
                $tdsType               = isset($_POST['tds_type']) && $_POST['tds_type'] != '' ? $_POST['tds_type'] : 0;

                if ($tdsFlag == 'No') {
                    $tdsType  = 0;                  
                } else {
                    $tdsType = $tdsType;
                }

                // $pg_name = $this->input->post('pg_name');
                $pg_name = 'billdesk';
			
                //$gstin_no       = $agency_center_state[0]['gstin_no'];
                $state    = $state_code;
                $gstin_no = $gstin_no;
                
                //get state code,state name,state number.
                if (!empty($state)) 
                {   
                    $getstate = $this->master_model->getRecords('state_master',array('state_code'=>$state,'state_delete'=> '0'));
                }

                $agencyAmount = $center_details[0]['required_amount'];

                if ($state == 'MAH') {
                    if ($center_type == 'R') {
                        //set a rate (e.g 9%,9% or 18%)
                        $cgst_rate = $this->config->item('DRA_inst_cgst_rate');
                        $sgst_rate = $this->config->item('DRA_inst_sgst_rate');
                        
                        if ($this->agency_type == 'BANK') {
                            //set an amount as per rate for banking institute
                            $cgst_amt  = ($agencyAmount * 9) / 100;
                            $sgst_amt  = ($agencyAmount * 9) / 100;
                        } else {    
                            //set an amount as per rate for non banking institute
                            $cgst_amt  = ($agencyAmount * 9) / 100;
                            $sgst_amt  = ($agencyAmount * 9) / 100;
                        }    
                        //set an total amount
                        $tax_type  = 'Intra';
                        $amount    = $totalAmountWithTds;
                        $cs_total  = $amount;
                    }
                    elseif($center_type == 'T')
                    {
                        //set a rate (e.g 9%,9% or 18%)
                        $cgst_rate=$this->config->item('DRA_inst_cgst_rate');
                        $sgst_rate=$this->config->item('DRA_inst_sgst_rate');
                        //set an amount as per rate
                        $cgst_amt=($agencyAmount * 9) / 100;
                        $sgst_amt=($agencyAmount * 9) / 100;
                        //set an total amount
                        $tax_type='Intra';    
                        $amount  = $totalAmountWithTds;
                        $cs_total=$amount;
                    }
                } 
                else 
                {
                    if ($center_type == 'R') {
                        $igst_rate  = $this->config->item('DRA_inst_igst_rate');
                        
                        if ($this->agency_type == 'BANK') {
                            // Set amount for banking institute                            
                            $igst_amt   = ($agencyAmount * 18) / 100;
                        } else {    
                            // Set amount for non banking institute 
                            $igst_amt =($agencyAmount * 18) / 100;
                        }
                            
                        $tax_type   = 'Inter';
                        $amount     = $totalAmountWithTds;
                        $igst_total = $amount;
                    }
                    elseif($center_type == 'T')
                    {
                        $igst_rate  = $this->config->item('DRA_inst_igst_rate');
                        $igst_amt   = ($agencyAmount * 18) / 100;
                        $tax_type   = 'Inter';
                        $amount     = $totalAmountWithTds;
                        $igst_total = $amount; 
                    }
                }

                // Add Details in Payment Transaction Table
				$pg_flag = 'IIBFDRAREG'; 
                $insert_data     = array(
                    'member_regnumber' => '',
                    'gateway' => "billdesk",
                    'amount' => $amount,
                    'isTDS' => $tdsFlag,
                    'tds_type' => $tdsType,
                    'tds_amount' => $tdsAmount,
                    'date' => date('Y-m-d H:i:s'),
                    'ref_id' => $center_id, // Primary Key of agency_center table
                    'description' => "DRA Agency Center Registration",
                    'pay_type' => 16,
                    'status' => 2,
                    'qty' => 1,
                    'pg_flag' => $pg_flag
                );
                
                $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
                
                $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
                /* Payment logs */
                $log_title ="Insert log of payment_transaction table Institute Code: ".$institute_code;
                $log_data = serialize($insert_data);
                $user_id = $institute_code;
                storedDraActivity($log_title, $log_data, $user_id);

                $MerchantOrderNo = sbi_exam_order_id($pt_id);
                $custom_field    = $pt_id."^iibfregn^".$pg_flag."^".$MerchantOrderNo."^".$center_id;
                $custom_field_billdesk    = $pt_id."-iibfregn-".$pg_flag."-".$center_id."-".$MerchantOrderNo;

                // update receipt no. in payment transaction -
                $update_data     = array(
                    'receipt_no' => $MerchantOrderNo,
                    'pg_other_details' => $custom_field
                );
               
                $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

                if ($center_type == 'R') {
                    if ($this->agency_type == 'BANK') 
                    {
                        // Fee amount for banking Institutes
                        $fee_amt = $agencyAmount;
                    } else {    
                        // Fee amount for non banking Institutes
                        $fee_amt = $agencyAmount;
                    }
                } elseif ($center_type == 'T') {
                    $fee_amt =$agencyAmount;
                }

                $invoice_insert_array = array(
                    'pay_txn_id' => $pt_id,
                    'receipt_no' => $MerchantOrderNo,
                    'exam_code' => '',
                    'state_of_center' => $state,
                    'gstin_no' => $gstin_no,
                    'app_type' => 'H', // A for accrediated institute 
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
                    'igst_total' => $igst_total,
                    'tds_amt' => $tdsAmount,
                    'exempt' => 'NE',
                    'institute_code' => $institute_code,
                    'institute_name' => $institute_name,
                    'created_on' => date('Y-m-d H:i:s')
                );
                $inser_id             = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
                $institute_code    = $this->session->userdata['dra_institute']['institute_code'];
                /* Exam Invoice logs */
                $log_title ="Insert log of exam_invoice table Institute Code:".$institute_code;
                $log_data = serialize($invoice_insert_array);
                $user_id = $institute_code;
                storedDraActivity($log_title, $log_data, $user_id);
                
                $MerchantCustomerID   = $center_id;
                if($pg_name == 'sbi')
                {
		            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
			        $key = $this->config->item('sbi_m_key');
			        $merchIdVal = $this->config->item('sbi_merchIdVal');
			        $AggregatorId = $this->config->item('sbi_AggregatorId');
			
			        $pg_success_url = base_url()."iibfdra/Version_2/Center/sbitranssuccess";
			        $pg_fail_url    = base_url()."iibfdra/Version_2/Center/sbitransfail";
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
                elseif ($pg_name == 'billdesk')
                {
                    // $update_payment_data = array('gateway' =>'billdesk');
    				// $this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
    				
                    $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $pt_id, $pt_id, '', 'iibfdra/Version_2/Center/center_add_handle_billdesk_response', '', '', '', $custom_field_billdesk);
                    
                    if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
                    {
                        $data['bdorderid']      = $billdesk_res['bdorderid'];
                        $data['token']          = $billdesk_res['token'];
						$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
						$data['returnUrl']      = $billdesk_res['returnUrl'];
                        $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                    }
                    else
                    {
                        $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                        redirect(base_url() . 'iibfdra/Version_2/Center/');
                    }
                }
            /*} 
            else 
            {   
                // echo "<pre>"; print_r($_POST); exit;
                $data['show_billdesk_option_flag'] = 1;
				$this->load->view('pg_sbi/make_payment_page', $data);
            }*/
        }
        else
        {
            $this->session->set_flashdata('error', 'Error during payment.please try again!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
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
                            $user_info = $this->master_model->getRecords('agency_center', array(
                                'center_id' => $get_user_regnum[0]['ref_id']
                            ), 'center_id,email_id,center_type');
                        }
                        $validate_upto = '';
                        if ($user_info[0]['center_type'] == 'T') {
                            $created_on    = date('Y-m-d H:i:s');
                            $validate_upto = date('Y-m-d H:i:s', strtotime('+3 months', strtotime($created_on)));
                        }
                        $update_data = array(
                            'pay_status' => '1'
                        );
                        
                        //,'validate_upto' => $validate_upto
                        
                        $this->master_model->updateRecord('agency_center', $update_data, array(
                            'center_id' => $get_user_regnum[0]['ref_id']
                        ));
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

                    /*    $update_data_status = array(
                            'center_display_status' => '1',
                        );
                        
                        $this->master_model->updateRecord('agency_center', $update_data_status, array('center_id' => $get_user_regnum[0]['ref_id']));*/ // added by aayusha


                        /* payment_transaction Update log*/
                        $log_title ="Update in payment_transaction table. Order Id: ".$MerchantOrderNo;
                        $log_data = serialize($update_data);
                        $user_id = 0;
                        storedDraActivity($log_title, $log_data, $user_id);

                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        
                        //$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'dra_institute'
                        ));
                        if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
                            $final_str         = $emailerstr[0]['emailer_text'];
                            $info_arr          = array(
                                'to' => $user_info[0]['email_id'],
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_str
                            );
                            //genertate invoice and email send with invoice attach 8-7-2017                    
                            //get invoice    
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                                'receipt_no' => $MerchantOrderNo,
                                'pay_txn_id' => $get_user_regnum[0]['id']
                            ));
                            if (count($getinvoice_number) > 0) {
                                $invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
                                }
                                $update_data = array(
                                    'invoice_no' => $invoiceNumber,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'cc' => 'rohini@iibf.org.in',
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
                                $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array(
                                    'receipt_no' => $MerchantOrderNo
                                ));
                                $attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
                            }
                            if ($attachpath != '') {
                                if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
                                    $this->session->set_flashdata('success', 'DRA agency center registration has been done successfully !!');
                                    redirect(base_url() . 'iibfdra/Version_2/Center/acknowledge/' . base64_encode($MerchantOrderNo));
                                } else {
                                    redirect(base_url('iibfdra/Version_2/Center/acknowledge/'));
                                }
                            } else {
                                redirect(base_url('iibfdra/Version_2/Center/acknowledge/'));
                            }
                        }
                    }
                }
            }
            ///End of SBI B2B callback 
            redirect(base_url() . 'iibfdra/Version_2/Center/acknowledge/');
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
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
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
    
    //if Billdesk transaction 
    public function handle_billdesk_response_old()
    {
        /* ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			error_reporting(E_ALL); */
		
			if (isset($_REQUEST['transaction_response']))
		{
			$response_encode = $_REQUEST['transaction_response'];
			$bd_response = $this->billdesk_pg_model->verify_res($response_encode);
			$attachpath=$invoiceNumber=$admitcard_pdf='';
				
			$responsedata = $bd_response['payload'];
				
			$MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
			$transaction_no  = $responsedata['transactionid'];
			$merchIdVal = $responsedata['mercid'];
		    $Bank_Code = $responsedata['bankid'];
			$encData = $_REQUEST['transaction_response'];
            $auth_status = $responsedata['auth_status'];
			
			$transaction_error_type = $responsedata['transaction_error_type'];	
			$qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			
            if($auth_status == '0300' && $qry_api_response['auth_status'] == '0300')
			{
					
		            $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
                    
                    if ($get_user_regnum[0]['status'] == 2) 
                    {
                        if (count($get_user_regnum) > 0) 
                        {
                            $user_info = $this->master_model->getRecords('agency_center', array(
                                'center_id' => $get_user_regnum[0]['ref_id']
                            ), 'center_id,email_id,center_type');
                        }
                        $validate_upto = '';
                        if ($user_info[0]['center_type'] == 'T') {
                            $created_on    = date('Y-m-d H:i:s');
                            $validate_upto = date('Y-m-d H:i:s', strtotime('+3 months', strtotime($created_on)));
                        }
                        $update_data = array(
                            'pay_status' => '1'
                        );
                        
                        //,'validate_upto' => $validate_upto
                        
                        $this->master_model->updateRecord('agency_center', $update_data, array(
                            'center_id' => $get_user_regnum[0]['ref_id']
                        ));
                        $update_data = array(
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata['bankid'],
                            'paymode' => $responsedata['txn_process_type'],
                            'callback' => 'B2B'
                        );
                        
                        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));


                        /* payment_transaction Update log*/
                        $log_title ="Update in payment_transaction table. Order Id: ".$MerchantOrderNo;
                        $log_data = serialize($update_data);
                        $user_id = 0;
                        storedDraActivity($log_title, $log_data, $user_id);

                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                        $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                        
                        //$pg_response = "encData=".$encData."&merchIdVal=".$merchIdVal."&Bank_Code=".$Bank_Code;
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'dra_institute'
                        ));
                        if (count($emailerstr) > 0 && (count($get_user_regnum) > 0)) {
                            $final_str         = $emailerstr[0]['emailer_text'];
                            $info_arr          = array(
                                'to' => $user_info[0]['email_id'],
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_str
                            );
                            //genertate invoice and email send with invoice attach 8-7-2017                    
                            //get invoice    
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                                'receipt_no' => $MerchantOrderNo,
                                'pay_txn_id' => $get_user_regnum[0]['id']
                            ));
                            if (count($getinvoice_number) > 0) {
                                $invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
                                }
                                $update_data = array(
                                    'invoice_no' => $invoiceNumber,
                                    'transaction_no' => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'cc' => 'rohini@iibf.org.in',
                                    'modified_on' => date('Y-m-d H:i:s')
                                );
                                $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array(
                                    'receipt_no' => $MerchantOrderNo
                                ));
                                $attachpath = genarate_dra_invoice($getinvoice_number[0]['invoice_id']);
                            }
                            if ($attachpath != '') {
                                if ($this->Emailsending->mailsend_attch_DRA($info_arr, $attachpath)) {
                                    $this->session->set_flashdata('success', 'DRA agency center registration has been done successfully !!');
                                    redirect(base_url() . 'iibfdra/Version_2/Center/acknowledge/' . base64_encode($MerchantOrderNo));
                                } else {
                                    redirect(base_url('iibfdra/Version_2/Center/acknowledge/'));
                                }
                            } else {
                                redirect(base_url('iibfdra/Version_2/Center/acknowledge/'));
                            }
                        }
                    }
            }
            ///End of SBI B2B callback 
            //billdesk fail
            else{
       
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => $MerchantOrderNo
            ), 'ref_id,status');
            if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {
                
                $update_data = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                    'auth_code' => '0399',
                    'bankcode' => $responsedata['bankid'],
                    'paymode' => $responsedata['txn_process_type'],
                    'callback' => 'B2B'
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo
                ));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
            }
            $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                
        }
            redirect(base_url() . 'iibfdra/Version_2/Center/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
    
    public function custom_invoice($invoice_id)
    {
        $invoiceNumber = generate_dra_invoice_number($invoice_id);
        if ($invoiceNumber) {
            $invoiceNumber = $this->config->item('DRA_invoice_ren_no_prefix') . $invoiceNumber;
        }

        $update_data = array(
            'invoice_no' => $invoiceNumber,
            'date_of_invoice' => date('Y-m-d H:i:s'),
            'modified_on' => date('Y-m-d H:i:s')
        );

        $this->master_model->updateRecord('exam_invoice', $update_data, array(
            'invoice_id' => $invoice_id
        ));

        $attachpath = genarate_dra_renew_invoice($invoice_id);
        echo $attachpath; exit; 
    }

    //if Billdesk transaction return URL for renew the center 
    public function handle_billdesk_response()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); 
        
        if (isset($_REQUEST['transaction_response']))
                    {
            $response_encode = $_REQUEST['transaction_response'];
            $bd_response = $this->billdesk_pg_model->verify_res($response_encode);
            $attachpath=$invoiceNumber=$admitcard_pdf='';
            $responsedata = $bd_response['payload'];
                
            $MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
            $transaction_no  = $responsedata['transactionid'];
            $merchIdVal = $responsedata['mercid'];
            $Bank_Code = $responsedata['bankid'];
            $encData = $_REQUEST['transaction_response'];
            $auth_status = $responsedata['auth_status'];
            $additional_info = $responsedata['additional_info'];
            $json_center_array = $additional_info['additional_info4'];
            
            if ($json_center_array != '') {
                $center_array = explode(',',$json_center_array);
                    }
                
            $transaction_error_type = $responsedata['transaction_error_type'];  
            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            
            if($auth_status == '0300' && $qry_api_response['auth_status'] == '0300')
            {    
                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo,'pay_type' => 17));
                
                if ($get_user_regnum[0]['status'] == 7 || $get_user_regnum_info[0]['status'] == 2) 
                {
                    if (count($get_user_regnum) > 0) 
                    {
                        foreach ($center_array as $key => $center_value) 
                        {
                            $user_info = $this->master_model->getRecords('agency_center',array(
                                'center_id' => $center_value
                            ), 'center_id,email_id,center_type');
                
                            $update_data = array(
                                'pay_status' => '1',
                                'payment_date' => date('Y-m-d H:i:s')
                            );
                    
                            $this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_value
                            ));   
                        }
                        }
                
                    $update_data = array(
                        'transaction_no' => $transaction_no,
                        'status' => 1,
                        'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                        'auth_code' => '0300',
                        'bankcode' => $responsedata['bankid'],
                        'paymode' => $responsedata['txn_process_type'],
                        'callback' => 'B2B'
                    );
                    
                    $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no' => $MerchantOrderNo));

                    /* payment_transaction Update log*/
                    $log_title ="Update in payment_transaction table. Order Id: ".$MerchantOrderNo;
                    $log_data = serialize($update_data);
                    $user_id = 0;
                    storedDraActivity($log_title, $log_data, $user_id);

                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                        
                    //genertate invoice and email send with invoice attach 8-7-2017                    
                    //get invoice    
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                        'receipt_no' => $MerchantOrderNo,
                        'pay_txn_id' => $get_user_regnum[0]['id']
                    ));
                
                    if (count($getinvoice_number) > 0) 
                    {
                        $invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('DRA_invoice_ren_no_prefix') . $invoiceNumber;
                            }
                        $update_data = array(
                            'invoice_no' => $invoiceNumber,
                            'transaction_no' => $transaction_no,
                            'date_of_invoice' => date('Y-m-d H:i:s'),
                            // 'cc' => 'rohini@iibf.org.in',
                            'modified_on' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                        $this->db->where('invoice_id', $getinvoice_number[0]['invoice_id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array(
                            'receipt_no' => $MerchantOrderNo
                        ));
                        
                        $attachpath = genarate_dra_renew_invoice($getinvoice_number[0]['invoice_id']);
                        
                        $this->session->set_flashdata('success', 'DRA agency renewal fee payment successful for '.count($center_array).' Center(s).');
                        redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                    } 
                
                    $this->session->set_flashdata('success', 'DRA invoice number not generated.');
                    redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                }
            }
            ///End of SBI B2B callback 
            //billdesk fail
            else
            {
                $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                    'receipt_no' => $MerchantOrderNo,'pay_type'=>17), 'ref_id,status');
                if ($get_user_regnum_info[0]['status'] != 0 && ($get_user_regnum_info[0]['status'] == 2 || $get_user_regnum_info[0]['status'] == 7)) 
                {
                    $update_data = array(
                        'transaction_no' => $transaction_no,
                        'status' => 0,
                        'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                        'auth_code' => $qry_api_response['auth_status'],
                        'bankcode' => $responsedata['bankid'],
                        'paymode' => $responsedata['txn_process_type'],
                        'callback' => 'B2B'
                    );
                    $this->master_model->updateRecord('payment_transaction', $update_data, array(
                        'receipt_no' => $MerchantOrderNo
                    ));
                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
        
                    $this->session->set_flashdata('error',$responsedata['transaction_error_desc']);
                    redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                }
                $this->session->set_flashdata('error', 'Transaction details could not be found.');
                redirect(base_url() . 'iibfdra/Version_2/Center/listing');
            }
            $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        } 
        else 
        {
            $this->session->set_flashdata('error', 'Transaction details could not be found.');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        }
    }
    
    //if Billdesk transaction return URL for renew the center 
    public function center_add_handle_billdesk_response()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); 
        
        if (isset($_REQUEST['transaction_response']))
                    {
            $response_encode = $_REQUEST['transaction_response'];
            $bd_response = $this->billdesk_pg_model->verify_res($response_encode);
            $attachpath=$invoiceNumber=$admitcard_pdf='';
            $responsedata = $bd_response['payload'];
                
            $MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
            $transaction_no  = $responsedata['transactionid'];
            $merchIdVal = $responsedata['mercid'];
            $Bank_Code = $responsedata['bankid'];
            $encData = $_REQUEST['transaction_response'];
            $auth_status = $responsedata['auth_status'];
            $additional_info = $responsedata['additional_info'];
            
            // $json_center_array = $additional_info['additional_info4'];
            $center_value = $additional_info['additional_info4'];    
            // if ($json_center_array != '') {
            //     $center_array = explode(',',$json_center_array);
            // }
            // echo $center_value; exit;    
            $transaction_error_type = $responsedata['transaction_error_type'];  
            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            
            if($auth_status == '0300' && $qry_api_response['auth_status'] == '0300')
            {    
                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo,'pay_type' => 16));
                
                if ($get_user_regnum[0]['status'] == 7 || $get_user_regnum[0]['status'] == 2) 
                {
                    if (count($get_user_regnum) > 0) 
                    {
                        // foreach ($center_array as $key => $center_value) 
                        // {
                            $user_info = $this->master_model->getRecords('agency_center',array(
                                'center_id' => $center_value
                            ), 'center_id,email_id,center_type');
                
                            $update_data = array(
                                'pay_status' => '1',
                                'payment_date' => date('Y-m-d H:i:s')
                            );
                    
                            $this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_value
                            ));   
                        // }
                    }
                
                    $update_data = array(
                        'transaction_no' => $transaction_no,
                        'status' => 1,
                        'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                        'auth_code' => '0300',
                        'bankcode' => $responsedata['bankid'],
                        'paymode' => $responsedata['txn_process_type'],
                        'callback' => 'B2B'
                    );
                    
                    $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no' => $MerchantOrderNo));

                    /* payment_transaction Update log*/
                    $log_title ="Update in payment_transaction table. Order Id: ".$MerchantOrderNo;
                    $log_data = serialize($update_data);
                    $user_id = 0;
                    storedDraActivity($log_title, $log_data, $user_id);

                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code . "&CALLBACK=B2B";
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
                        
                    //genertate invoice and email send with invoice attach 8-7-2017                    
                    //get invoice    
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                        'receipt_no' => $MerchantOrderNo,
                        'pay_txn_id' => $get_user_regnum[0]['id']
                    ));
                
                    if (count($getinvoice_number) > 0) 
                    {
                        $invoiceNumber = generate_dra_invoice_number($getinvoice_number[0]['invoice_id']);
                        
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('DRA_invoice_no_prefix') . $invoiceNumber;
                        }

                        $update_data = array(
                            'invoice_no' => $invoiceNumber,
                            'transaction_no' => $transaction_no,
                            'date_of_invoice' => date('Y-m-d H:i:s'),
                            // 'cc' => 'rohini@iibf.org.in',
                            'modified_on' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('pay_txn_id', $get_user_regnum[0]['id']);
                        $this->db->where('invoice_id', $getinvoice_number[0]['invoice_id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array(
                            'receipt_no' => $MerchantOrderNo
                        ));
                        
                        $attachpath = genarate_dra_center_invoice($getinvoice_number[0]['invoice_id']);
                        
                        $this->session->set_flashdata('success', 'DRA agency center registration fee payment successful');
                        redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                    } 
                
                    $this->session->set_flashdata('success', 'DRA invoice number not generated.');
                    redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                }
            }
            // End of SBI B2B callback 
            // Billdesk fail
            else
            {
                $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                    'receipt_no' => $MerchantOrderNo,'pay_type'=>16), 'ref_id,status');
                if ($get_user_regnum_info[0]['status'] != 0 && ($get_user_regnum_info[0]['status'] == 2 || $get_user_regnum_info[0]['status'] == 7)) 
                {
                    $update_data = array(
                        'transaction_no' => $transaction_no,
                        'status' => 0,
                        'transaction_details' => $responsedata['transaction_error_type']. " - " .$responsedata['transaction_error_desc'],
                        'auth_code' => $qry_api_response['auth_status'],
                        'bankcode' => $responsedata['bankid'],
                        'paymode' => $responsedata['txn_process_type'],
                        'callback' => 'B2B'
                    );
                    $this->master_model->updateRecord('payment_transaction', $update_data, array(
                        'receipt_no' => $MerchantOrderNo
                    ));
                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);
        
                    $this->session->set_flashdata('error',$responsedata['transaction_error_desc']);
                    redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                }
                $this->session->set_flashdata('error', 'Transaction details could not be found.');
                redirect(base_url() . 'iibfdra/Version_2/Center/listing');
            }
            $this->session->set_flashdata('error', 'Transaction has been fail, please try again!!');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        } 
        else 
        {
            $this->session->set_flashdata('error', 'Transaction details could not be found.');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
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
            $this->db->select('agency_center.*,city_master.city_name');
            $this->db->join('city_master','agency_center.location_name=city_master.id','LEFT');   
            $center_info = $this->master_model->getRecords('agency_center', array(
                'center_id' => $payment_info[0]['ref_id']
            ));
            $this->db->join('dra_exam_activation_master b', 'a.exam_code = b.exam_code', 'right');
            $res  = $this->master_model->getRecords("dra_exam_master a");
            
            $data = array(
            'middle_content' => 'center_ack',
            'application_number' => $payment_info[0]['ref_id'],
            'center_info' => $center_info,
            'payment_info' => $payment_info,
            'active_exams' => $res
            );
            $this->load->view('iibfdra/Version_2/common_view', $data);
            
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

 /*check pincode/zipcode alredy exist or not */

     public function checkpin()
    {

        $statecode = $_POST['state'];
        $pincode   = $_POST['pincode'];
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode
            ));
            //echo $this->db->last_query();
            //exit;
            if ($prev_count == 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            echo 'false';
        }
    }
/*GET VALUES OF CITY */
    public function getCity() 
    {
        if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
        {
            $state_code = $this->security->xss_clean($this->input->post('state_code'));
            $result = $this->master_model->getRecords('city_master', array('state_code' => $state_code,'city_delete' => 0));
            if ($result) 
            {
                echo '<option value="">- Select - </option>';
                foreach ($result AS $data) 
                {
                    if ($data) 
                    {
                        echo '<option value="' . $data['id'] . '">' . $data['city_name'] . '</option>';
                    }
                }
            } 
            else 
            {
                echo '<option value="">City Not Available, Please select other state</option>';
            }
            
        }
    }
/*GET VALUES OF CITY FOR EDIT */
    public function getCityedit() 
    {
        if (isset($_POST["state_code"]) && !empty($_POST["state_code"])) 
        {
            
            $state_code = $this->security->xss_clean($this->input->post('state_code'));
            $result = $this->master_model->getRecords('city_master', array('state_code' => $state_code, 'city_delete' => 0));
           
            if ($result) 
            {
                echo '<option value="">- Select - </option>';
                foreach ($result AS $data) 
                {
                    if ($data) 
                    {
                        echo '<option value="' . $data['id'] . '">' . $data['city_name'] . '</option>';
                    }
                }
            } 
            else 
            {
                echo '<option value="">City Not Available, Please select other state</option>';
            }
            
        }
    }
	
	
/*VALIDATE CITY  BY MANOJ */
    public function validateCity() 
    {
        if (isset($_POST["city_id"]) && !empty($_POST["city_id"]) && isset($_POST["center_type"]) && !empty($_POST["center_type"]) ) 
        {            
            $city_id 	= $_POST["city_id"];
			$curr_date = date('y-m-d');
			$agency_id 	= $this->session->userdata['dra_institute']['dra_inst_registration_id'];
			$this->db->select('agency_center.center_id,agency_center.center_type,agency_center.center_validity_to,agency_center.location_name,agency_center.is_renew');		
			$this->db->where('location_name',$city_id);
			$centerResult = $this->master_model->getRecords('agency_center',array('agency_id'=>$agency_id ));
			$query = $this->db->last_query();			
			//print_r($this->session);
			if(count($centerResult)>0){
				
				if($centerResult[0]['center_type'] ==  $_POST["center_type"]){
					echo 'EXIST';
					return true;
				}elseif($centerResult[0]['center_validity_to'] !=''){				
					if($centerResult[0]['center_validity_to'] <  $curr_date){
						if($centerResult[0]['is_renew'] = 1){
							echo 'EXIST';
							return true;
						}else{					
							echo 'OK'; 
							return true;
							//Only allow user to add center for same city if old center is expire and center type is diffrent and current center is not applyed for renew
						}
					}else{
						echo 'EXIST'; 
						return true;
					}
				}else{
					echo 'EXIST';
					return true;
				}
				
				
			}else{
				echo 'OK'; // city is not added	
				return true;
				
			}
        }
    }

/*VALIDATE CITY  BY MANOJ */
    public function validateEditCity() 
    {
		//print_r($_POST);
		
        if (isset($_POST["city_id"]) && !empty($_POST["city_id"]) && isset($_POST["center_type"]) && !empty($_POST["center_type"]) && isset($_POST["center_id"]) && !empty($_POST["center_id"]) ) 
        {            
            $city_id 	= $_POST["city_id"];
			$curr_date = date('y-m-d');
			$center_id = $_POST["center_id"];
			$center_type = $_POST["center_type"];
			
			$agency_id 	= $this->session->userdata['dra_institute']['dra_inst_registration_id'];
			$this->db->select('agency_center.center_id,agency_center.center_type,agency_center.center_validity_to,agency_center.location_name,agency_center.is_renew');	
			$this->db->where('location_name',$city_id);
			$this->db->where('agency_center.center_id !=',$center_id);
			$centerResult = $this->master_model->getRecords('agency_center',array('agency_id'=>$agency_id));
			//echo $query = $this->db->last_query();			
			//print_r($this->session);
			if(count($centerResult)>0){
				
				if($centerResult[0]['center_type'] ==  $_POST["center_type"]){
					echo 'EXIST';
					return true;
				}elseif($centerResult[0]['center_validity_to'] !=''){				
					if($centerResult[0]['center_validity_to'] <  $curr_date){
						if($centerResult[0]['is_renew'] = 1){
							echo 'EXIST';
							return true;
						}else{					
							echo 'OK'; 
							return true;
							//Only allow user to add center for same city if old center is expire and center type is diffrent and current center is not applyed for renew
						}
					}else{
						echo 'EXIST'; 
						return true;
					}
				}else{
					echo 'EXIST';
					return true;
				}
				
				
			}else{
				echo 'OK'; // city is not added	
				return true;
				
			}
        }
    }
	
    private function generateUniqueNumber() {
        // Get the current timestamp
        $timestamp = time();
        
        // Generate a random number
        $randomNumber = rand(10000, 99990);
        
        // Combine them to create a unique invoice number
        $invoiceNumber = $randomNumber.date('YmdHis',$timestamp);
        
        return $invoiceNumber;
    }

    public function generate_proforma() 
    {   
        if(isset($_POST['generate_proforma']) && $_POST['generate_proforma'])
        {
            $institute_name = $this->session->userdata['dra_institute']['institute_name'];
            $institute_code = $this->session->userdata['dra_institute']['institute_code'];
            $agency_id      = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

            if ($this->agency_type == 'BANK') {
                $this->session->set_flashdata('error', 'You are not allow the renew the center.');
                redirect(base_url() . 'iibfdra/Version_2/Center/listing');
            }

            $modifiedArray  = $this->input->post('chkmakepay');

            $centerarr = array_map(function($item) {
                return explode('|', $item)[0]; // Keep only the part before '|'
            }, $modifiedArray);

            if (isset($centerarr) && count($centerarr) == 1 ) {
                $center_id = $centerarr[0];
            } else {
                $this->session->set_flashdata('error', 'Please select only one center.');
                redirect(base_url() . 'iibfdra/Version_2/Center/listing');   
            }

            if (count($centerarr) == 1 && $centerarr != '') {
                                    $this->db->where('center_id',$center_id);
                $agencyCenterData = $this->master_model->getRecords('agency_center');   
            }            

            // $invoice_flag = $agencyCenterData[0]['invoice_flag'];
            $invoice_flag = 'CS'; // add gaurav static invoice flag 

            $state_code = '';
            $GsTIN_no   = '';
            if ($invoice_flag == 'AS') {
                $state_result = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),array('main_state','gstin_no'));
                $state_code = $state_result[0]['main_state'];
                $GsTIN_no   = $state_result[0]['gstin_no'];                
            } else if ($invoice_flag == 'CS') {
                if (!empty($center_id)) 
                {   
                    $state_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('state','gstin_no'));
                    $state_code = $state_result[0]['state'];
                    $GsTIN_no   = $state_result[0]['gstin_no'];
                }
            }
            // echo $agencyCenterData[0]['invoice_flag'].'--'.$center_id.'--'.$GsTIN_no; exit; 
            if (!empty($state_code)) 
            {
                //get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master',array('state_code' => $state_code,'state_delete' => '0'));
            }

            $center_name_arr = array_map(function($item) {
                return explode('|', $item)[1]; // Keep only the part before '|'
            }, $modifiedArray);

            $selectedCanCount = count($centerarr);

            $baseAmount  = 12000;
            $gstAmount   = 2160;
            $TotalAmount = 14160;

            $baseAmount  = $baseAmount * $selectedCanCount;
            $gstAmount   = $gstAmount * $selectedCanCount;
            $TotalAmount = $TotalAmount * $selectedCanCount; 

            $proformo_invoice_no = $this->generateUniqueNumber();

            // Create transaction
            $pg_flag = 'IIBFDRAREG';  // 'IIBF_DRA_REG';
            $insert_data     = array(
                'proformo_invoice_no' => $proformo_invoice_no,
                'member_regnumber' => '',
                'gateway' => "billdesk",
                'amount' => $TotalAmount,
                'date' => date('Y-m-d H:i:s'),
                'ref_id' => $center_id, // Primary Key of agency
                'description' => "DRA Center Renewal",
                'pay_type' => 17,
                'status' => 2,
                'qty' => $selectedCanCount,
                'pg_flag' => $pg_flag
            );
            
            $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            
            /* Payment logs */
            $log_title ="Insert log of payment_transaction table for center renew";
            $log_data  = serialize($insert_data);
            $user_id   = 0;
            storedDraActivity($log_title, $log_data, $user_id);
            
            $centerarr_json = implode(',',$centerarr);

            $MerchantOrderNo = sbi_exam_order_id($pt_id);
            $custom_field    = $pt_id."^iibfregn^".$pg_flag."^".$MerchantOrderNo."^".$centerarr_json;
            // $custom_field_billdesk    = $pt_id."-iibfregn-".$pg_flag."-".$MerchantOrderNo;
            $custom_field_billdesk    = $pt_id."-iibfregn-".$pg_flag."-".$centerarr_json;
                                
            // update receipt no. in payment transaction -
            $update_data     = array(
            'receipt_no' => $MerchantOrderNo,
            'pg_other_details' => $custom_field
            );
            
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
            
            $instdata   = $this->session->userdata('dra_institute');
            $inst_code  = $instdata['institute_code'];



            $this->db->where('id', $agency_id);
            $agencyData = $this->master_model->getRecords('dra_inst_registration');

            $cgst_rate = $cgst_amt = $sgst_rate = $sgst_amt = $igst_rate = $igst_amt = $cs_total = $igst_total = 0; 

            if($state_code == 'MAH')
            {
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                
                //set an amount as per rate
                $cgst_amt = 1080 * $selectedCanCount;
                $sgst_amt = 1080 * $selectedCanCount;
                $cs_total = 14160 * $selectedCanCount;
                $tax_type = 'Intra';    
            }
            else
            {
                $igst_rate  = $this->config->item('igst_rate');
                $igst_amt   = 2160 * $selectedCanCount;;
                $igst_total = 14160 * $selectedCanCount;;
                $tax_type   = 'Inter';
            }
            


            $invoice_insert_array = array(
            'center_name' => implode(',',$center_name_arr),
            'pay_txn_id' => $pt_id,
            'invoice_flag' => $invoice_flag,
            'receipt_no' => $MerchantOrderNo,
            'exam_code' => '',
            // 'center_code' => $agencyData[0]['main_city'],
            // 'state_of_center' => $agencyData[0]['main_state'],
            // 'gstin_no' => $agencyData[0]['gstin_no'],
            'center_code' => isset($center_id) ? $center_id:$agencyData[0]['main_city'],
            'state_of_center' => isset($state_code) ? $state_code : $agencyData[0]['main_state'],
            'gstin_no' => $GsTIN_no,
            'app_type' => 'W', // A for accrediated center renewal 
            'service_code' => $this->config->item('DRA_inst_service_code'),
            'qty' => $selectedCanCount,
            'state_code' => isset($getstate[0]['state_no']) ? $getstate[0]['state_no'] : $agencyData[0]['main_state'],
            'state_name' => isset($getstate[0]['state_name']) ? $getstate[0]['state_name'] : $agencyData[0]['main_state'],
            'tax_type' => $tax_type,
            'fee_amt' => $baseAmount,
            'cgst_rate' => $cgst_rate,
            'cgst_amt' => $cgst_amt,
            'sgst_rate' => $sgst_rate,
            'sgst_amt' => $sgst_amt,
            'igst_rate' => $igst_rate,
            'cs_total' => $cs_total,
            'igst_amt' => $igst_amt,
            'igst_total' => $igst_total,
            'exempt' => 'NE',
            'institute_code' => $institute_code,// by swati
            'institute_name' => $institute_name, // by swati
            'created_on' => date('Y-m-d H:i:s')
            );
            // print_r($invoice_insert_array); exit; 
            $inser_inv_id = $this->master_model->insertRecord('exam_invoice',$invoice_insert_array);
            

            foreach ($centerarr as $key => $center_value) 
            {
                $user_info = $this->master_model->getRecords('agency_center',array(
                    'center_id' => $center_value
                ), 'center_id,email_id,center_type');

                $update_data = array(
                    'pay_status' => '6',
                    'payment_date' => date('Y-m-d H:i:s')
                );
                
                $this->master_model->updateRecord('agency_center',$update_data,array('center_id' => $center_value
                ));   
            }

            /* Exam Invoice logs */
            $log_title ="Insert log of exam_invoice table";
            $log_data = serialize($invoice_insert_array);
            $user_id = 0;
            storedDraActivity($log_title, $log_data, $user_id);

            $this->session->set_flashdata('success', 'Proforma Invoice successfully generated.');  
        }
        else 
        {
            $this->session->set_flashdata('error', 'Error occurred while generating the Proforma Invoice. Please try again.');
        }   
        
        redirect(base_url() . 'iibfdra/Version_2/Center/listing');
    }  

    //performance invocie
    public function performance_invoice($qty=0,$enc_invoice_no=false)
    {
        $fresh_count = $rep_count = $wordamt ='';
        $login_agency=$this->session->userdata('dra_institute');
        $agency_id=$login_agency['dra_inst_registration_id'];
        
        $invoice_no = base64_decode($enc_invoice_no);

        $this->db->select('exam_invoice.*, agency_center.*,payment_transaction.*, exam_invoice.gstin_no AS invoice_gstin_no, exam_invoice.invoice_flag AS exam_invoice_flag, agency_center.invoice_flag AS center_invoice_flag, agency_center.gstin_no AS center_gstin_no,agency_center.state AS agency_state,payment_transaction.status AS paymentStatus');
        $this->db->join('payment_transaction','payment_transaction.receipt_no = exam_invoice.receipt_no AND payment_transaction.id = exam_invoice.pay_txn_id');
        $this->db->join('agency_center','agency_center.center_id = payment_transaction.ref_id');
        $record = $this->master_model->getRecords('exam_invoice',array('invoice_id'=>$invoice_no));
    
        /*$invoice_flag = $record[0]['center_invoice_flag'];        
        
        if ($record[0]['center_invoice_flag'] != $record[0]['exam_invoice_flag']) {
            if ($record[0]['invoice_no'] != '' && $record[0]['invoice_image'] != '' && $record[0]['transaction_no'] != '' && $record[0]['paymentStatus'] == 1) {
                $invoice_flag = $record[0]['exam_invoice_flag'];            
            }    
        }*/

        $invoice_flag = 'CS'; // add gaurav static invoice flag 

        if($invoice_flag == 'AS')
        {
            $this->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
            $ag_add = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),'inst_name,main_address1,main_address2,main_address3,main_address4,main_city,main_state,dra_inst_registration.gstin_no AS agency_gst');
            
            $name_of_center = $record[0]['city'];
            $name_of_agency = $ag_add[0]['inst_name'];
            $address = $ag_add[0]['main_address1']." ".$ag_add[0]['main_address2'];
            $address .= ' '. $ag_add[0]['main_address3']." ".$ag_add[0]['main_address4'];
            
            $getstate = $this->master_model->getRecords('state_master',array('state_code' => $ag_add[0]['main_state'],'state_delete' => '0'));

            $state      = $getstate[0]['state_name'];
            $state_code = $getstate[0]['state_no'];
            $gst_no     = $ag_add[0]['agency_gst'];

            /*$dra_inst_reg = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),'main_city');
            
            if(is_numeric($ag_add[0]['main_city'])){
                $city = $this->master_model->getRecords('city_master',array('id'=>$ag_add[0]['main_city']),'city_name');
                $city_name = $city[0]['city_name'];
            }
            else
            {
                if(is_numeric($record[0]['city'])) {
                    $city = $this->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
                    $city_name = $city[0]['city_name'];
                } else {
                    $city_name = $record[0]['city'];
                }   
            }*/
            }
        elseif($invoice_flag == 'CS')
        {
            $this->db->join('agency_center','agency_center.agency_id = dra_inst_registration.id');
            $ag_add = $this->master_model->getRecords('dra_inst_registration',array('id'=>$agency_id),'inst_name,main_address1,main_address2,main_address3,main_address4,main_city');
            
            $name_of_center = $record[0]['city'];
            $name_of_agency = $ag_add[0]['inst_name'];
            $address = $record[0]['location_address']." ".$record[0]['address1']." ".$record[0]['address2'];
            $address .= ' '. $record[0]['address3']." ".$record[0]['address4'];
            
            $getstate = $this->master_model->getRecords('state_master',array('state_code' => $record[0]['agency_state'],'state_delete' => '0'));

            $state      = $getstate[0]['state_name'];
            $state_code = $getstate[0]['state_no'];
            $gst_no     = $record[0]['center_gstin_no'];

            // $state      = $record[0]['state_name'];
            // $state_code = $record[0]['state_code'];
            
            
            /*if(is_numeric($record[0]['city'])){
                $city = $this->master_model->getRecords('city_master',array('id'=>$record[0]['city']),'city_name');
                $city_name = $city[0]['city_name'];
            }
            else{
                $city_name = $record[0]['city'];
            }*/
        }

        if (isset($record[0]['center_name']) && $record[0]['center_name'] != '') {
            $city_name = $record[0]['center_name'];
        }

        $MainbaseAmount  = 12000;
        $MaingstAmount   = 2160;
        $MainTotalAmount = 14160;

        $baseAmount  = $MainbaseAmount * $qty;
        $gstAmount   = $MaingstAmount * $qty;
        $TotalAmount = $MainTotalAmount * $qty; 
        
        $cgst_rate = '9.00';
        $sgst_rate = '9.00';
        $igst_rate = '18.00';
        $cgst = '0.09';
        $sgst = '0.09';
        $igst = '0.18';
        $cs_total = $igst_total =$final_total =$sgst_amt = $cs_amnt= '';
        
        if($state_code == 27) {
            $cgst = '0.09';
            $sgst = '0.09';
            $cs_amnt = ($baseAmount) * ('0.09');
            $sgst_amt = ($baseAmount) * ('0.09');
            $final_total = $cs_amnt + $baseAmount + $sgst_amt;
            $wordamt = $this->pb_amtinword(intval($final_total));
        }
        elseif($state_code != 27){
            $igst = '0.18';
            $igst_total = ($baseAmount) * ('0.18'); 
            $final_total = $igst_total + $baseAmount;
            $wordamt = $this->pb_amtinword(intval($final_total));
        }
        
        $date_of_invoice = date("d-m-Y"); 
        
        $data = array('wmt'=>$wordamt,'invoice_no'=>'TEMP_INVOICE_NO','date_of_invoice'=>$date_of_invoice,'transaction_no'=>'TEMP_TRN_NO','recepient_name'=>$name_of_agency,'address'=>$address,'institute_state'=>$state,'institute_state_code'=>$state_code,'institute_gstn'=>$gst_no,'city_name'=>$city_name,'MainbaseAmount'=>$MainbaseAmount,'baseAmount'=>$baseAmount,'discount_amt'=>'-','net_amt'=>'-','ste_code'=>$state_code,'cgst_rate'=>$cgst_rate,'cgst_amt'=>$cs_amnt,'sgst_rate'=>$sgst_rate,'sgst_amt'=>$sgst_amt,'final_total'=>$final_total,'igst_total'=>$igst_total,'invoice_number'=>'TEMP_INVOICE_NO','igst_rate'=>$igst_rate,'qty'=>$qty); 
       
        $this->load->view('iibfdra/Version_2/print_center_receipt_proforma',$data);
    }

    function pb_amtinword($amt){
        $number = $amt;
        $no = round($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        "." . $words[$point / 10] . " " . 
        $words[$point = $point % 10] : '';
        
        return $result;
    }

    public function goToPayment($payId = false,$encCenterName = false)
    {   
        $institute_name = $this->session->userdata['dra_institute']['institute_name'];
        $institute_code = $this->session->userdata['dra_institute']['institute_code'];
        $agency_id      = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

        $pt_id      = base64_decode($payId);
        $centerName = base64_decode($encCenterName);

        if ($pt_id > 0 && $payId != false) 
        {            
            $paymentTransInfo = $this->master_model->getRecords('payment_transaction',array('id'=>$pt_id));
            
            if (count($paymentTransInfo) < 1) {
               $this->session->set_flashdata('error', 'Proforma invoice details not found.');
               redirect(base_url() . 'iibfdra/Version_2/Center/listing'); 
            }

            $baseAmount  = 0;
            $totalAmount = $paymentTransInfo[0]['amount'];
            $receiptNo   = $paymentTransInfo[0]['receipt_no'];
            
            $examInvoiceInfo = $this->master_model->getRecords('exam_invoice',array('pay_txn_id' => $pt_id, 'receipt_no' => $receiptNo));

            if ( count($examInvoiceInfo) == 1 ) {
               $baseAmount = $examInvoiceInfo[0]['fee_amt'];                
            } else {
               $this->session->set_flashdata('error', 'Something went wrong.');
               redirect(base_url().'iibfdra/Version_2/Center/listing'); 
            }   
        } 

        $data['baseAmount']      = $baseAmount;
        $data['tot_fee']         = base64_encode($totalAmount);
        $data['centerName']      = $centerName;
        $data['instituteName']   = $institute_name;
        $data['payId']           = $payId;
        $data['middle_content']  = 'center_renew_online_payment_page'; 
               
        $this->load->view('iibfdra/Version_2/common_view',$data);
    }

    public function goToCenterPayment($enc_center_id=false,$encCenterName = false)
    {  
        if ($enc_center_id == false) {
            $this->session->set_flashdata('error', 'Center details not found.');
            redirect(base_url().'iibfdra/Version_2/Center/listing');
        }

        $institute_name = $this->session->userdata['dra_institute']['institute_name'];
        $center_id      = base64_decode($enc_center_id);
        
        $center_details = $this->master_model->getRecords('agency_center', array('center_id' => $center_id));
        
        $center_type = $center_details[0]['center_type'];

        $state_result = $this->master_model->getRecords('agency_center',array('center_id'=>$center_id),array('state','gstin_no'));
        $state_code = $state_result[0]['state'];
        
        $state = $state_code;
        
        $baseAmount  = $center_details[0]['required_amount'];

        if ($baseAmount == null || $baseAmount == '' || $baseAmount == 0) 
        {
            $this->session->set_flashdata('error', 'Amount should be greater that zero.');
            redirect(base_url().'iibfdra/Version_2/Center/listing');
        }

        if ($state == 'MAH') 
        {
            if ($center_type == 'R') 
            {
                if ($this->agency_type == 'BANK') {
                    // For Banking Institute fee
                    
                    // $baseAmount  = $this->config->item('DRA_regular_apply_fee');
                    // $totalAmount = $this->config->item('DRA_regular_cs_total');
                
                    $baseAmount  = $center_details[0]['required_amount'];
                    // Calculate GST amount
                    $gstAmount  = ($baseAmount * 18) / 100;
                    // Calculate total amount (base + GST)
                    $totalAmount = $baseAmount + $gstAmount;  
                } else {
                    // For Non Banking Institute fee
                    
                    // $baseAmount  = $this->config->item('DRA_regular_apply_with_dilligance_fee');
                    // $totalAmount = $this->config->item('DRA_regular_apply_with_dilligance_cs_total');
                    
                    $baseAmount = $center_details[0]['required_amount'];
                    // Calculate GST amount
                    $gstAmount  = ($baseAmount * 18) / 100;
                    // Calculate total amount (base + GST)
                    $totalAmount = $baseAmount + $gstAmount;
                }
            }
            elseif($center_type == 'T')
            {       
                $baseAmount  = $center_details[0]['required_amount'];
                // Calculate GST amount
                $gstAmount  = ($baseAmount * 18) / 100;
                // Calculate total amount (base + GST)
                $totalAmount = $baseAmount + $gstAmount;  
            }
        } 
        else 
        {
            if ($center_type == 'R') 
            {
                if ($this->agency_type == 'BANK') {
                    // For Banking Institute fee
                    // $baseAmount   = $this->config->item('DRA_regular_apply_fee');
                    // $totalAmount  = $this->config->item('DRA_regular_igst_tot');

                    $baseAmount  = $center_details[0]['required_amount'];
                    // Calculate GST amount
                    $gstAmount  = ($baseAmount * 18) / 100;
                    // Calculate total amount (base + GST)
                    $totalAmount = $baseAmount + $gstAmount;  
                } else {    
                    // For Non Banking Institute fee
                    // $baseAmount  = $this->config->item('DRA_regular_apply_with_dilligance_fee');
                    // $totalAmount = $this->config->item('DRA_regular_apply_with_dilligance_igst_tot');
                    
                    $baseAmount  = $center_details[0]['required_amount'];
                    // Calculate GST amount
                    $gstAmount  = ($baseAmount * 18) / 100;
                    // Calculate total amount (base + GST)
                    $totalAmount = $baseAmount + $gstAmount;
                }    
            }
            elseif($center_type == 'T')
            {
                $baseAmount  = $center_details[0]['required_amount'];
                // Calculate GST amount
                $gstAmount  = ($baseAmount * 18) / 100;
                // Calculate total amount (base + GST)
                $totalAmount = $baseAmount + $gstAmount;
            }
        } 

        $data['baseAmount']      = $baseAmount;
        $data['tot_fee']         = base64_encode($totalAmount);
        $data['centerName']      = base64_decode($encCenterName);
        $data['instituteName']   = $institute_name;
        $data['center_id']       = $center_id;
        // $data['payId']           = $payId;
        $data['middle_content']  = 'center_register_online_payment_page'; 

        $this->load->view('iibfdra/Version_2/common_view',$data);
    }

    public function renew()
    {        
        $institute_name = $this->session->userdata['dra_institute']['institute_name'];
        $institute_code = $this->session->userdata['dra_institute']['institute_code'];
        $agency_id      = $this->session->userdata['dra_institute']['dra_inst_registration_id'];

        // $selectedCanCount = count($centerarr);
        $payId = $_POST['payId'];
        $pt_id = base64_decode($payId);

        if ($pt_id > 0 && $payId != '') {
            $paymentTransInfo = $this->master_model->getRecords('payment_transaction',array('id'=>$pt_id));
            if (count($paymentTransInfo) < 1) {
               $this->session->set_flashdata('error', 'Transaction details not found.');
               redirect(base_url() . 'iibfdra/Version_2/Center/listing'); 
            }
        } else {
            $this->session->set_flashdata('error', 'Transaction details not found.');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        }

        if (isset($paymentTransInfo[0]['status']) && ($paymentTransInfo[0]['status'] == 1 || $paymentTransInfo[0]['status'] == 7)) {
            $this->session->set_flashdata('error', 'Payment has already been completed/inprocess for this transaction.');
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        }

        if( isset($paymentTransInfo) && count($paymentTransInfo)>0 ) 
        {
            if( $paymentTransInfo[0]['status'] == 0) 
            {
                $proformo_invoice_no = $paymentTransInfo[0]['proformo_invoice_no'];

                $old_receipt_no = $paymentTransInfo[0]['receipt_no'];
                $old_pay_id     = $paymentTransInfo[0]['id'];

                // get the last or previous inprocess transation so we cant process the current transaction
                $InprocessPayment = $this->master_model->getRecords('payment_transaction',array('proformo_invoice_no' => $proformo_invoice_no,'status' => 7));

                if (count($InprocessPayment) > 0) 
                {
                    $this->session->set_flashdata('error', 'Payment has already been inprocess for this transaction.');
                    redirect(base_url() . 'iibfdra/Version_2/Center/listing');
                }

                $arrPaymentTransInfo = array_shift($paymentTransInfo);
                
                $arr_insert_data = $arrPaymentTransInfo;

                unset($arr_insert_data['id']);

                $arr_insert_data['date']   = date('Y-m-d H:i:s');
                $arr_insert_data['status'] = 7;

                $pt_id = $this->master_model->insertRecord('payment_transaction',$arr_insert_data, true);

                $pg_flag               = 'IIBFDRAREG';
                $MerchantOrderNo       = sbi_exam_order_id($pt_id);
                $pg_other_details      = $arrPaymentTransInfo['pg_other_details'];
                $arr_pg_other_details  = explode('^', $pg_other_details);
                
                $custom_field    = $pt_id."^iibfregn^".$pg_flag."^".$MerchantOrderNo."^".$arr_pg_other_details[4];

                $custom_field_billdesk = $pt_id."-iibfregn-".$pg_flag."-".$arr_pg_other_details[4];

                // TDS functionality 
                $totalAmountWithTds    = base64_decode($_POST['final_amount']);
                $tdsAmount             = isset($_POST['tds_amount']) && $_POST['tds_amount'] != '' ? base64_decode($_POST['tds_amount']) : 0;
                $totalAmountWithoutTds = $totalAmountWithTds+$tdsAmount;
                $tdsFlag               = $_POST['TDS'];
                $tdsType               = isset($_POST['tds_type']) && $_POST['tds_type'] != '' ? $_POST['tds_type'] : 0;

                if ($tdsFlag == 'No') {
                    $tdsType  = 0;                  
                } else {
                    $tdsType = $tdsType;
                }
                $TotalAmount = $totalAmountWithTds;
                // update receipt no. in dra payment transaction -
                $update_trans_data = array('status' => 7,'amount' => $TotalAmount,'isTDS' => $tdsFlag,'tds_type' => $tdsType,'tds_amount' => $tdsAmount,'receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
                $this->master_model->updateRecord('payment_transaction',$update_trans_data,array('id'=>$pt_id));

                $arr_invoice_data = $this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$old_pay_id,'app_type'=>'W'));

                if($arr_invoice_data[0]['state_of_center'] == 'MAH')
                {
                    $update_invoice_data = array('tds_amt' => $tdsAmount,'cs_total' => $TotalAmount,'receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $pt_id);
                    $this->master_model->updateRecord('exam_invoice',$update_invoice_data,array('pay_txn_id'=>$old_pay_id,'app_type'=>'W'));
                }
                else
                {
                    $update_invoice_data = array('tds_amt' => $tdsAmount,'igst_total' => $TotalAmount,'receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $pt_id);
                    $this->master_model->updateRecord('exam_invoice',$update_invoice_data,array('pay_txn_id'=>$old_pay_id,'app_type'=>'W'));
                }
                
                $this->db->where('id',$old_pay_id);
                $this->db->delete('payment_transaction');

                $paymentTransInfo = $this->master_model->getRecords('payment_transaction',array('id' => $pt_id));
                
                $TotalAmount = $totalAmountWithTds;
                $center_id   = $paymentTransInfo[0]['ref_id'];    
            }
            else
            {
                // TDS functionality 
                $totalAmountWithTds    = base64_decode($_POST['final_amount']);
                $tdsAmount             = isset($_POST['tds_amount']) && $_POST['tds_amount'] != '' ? base64_decode($_POST['tds_amount']) : 0;
                $totalAmountWithoutTds = $totalAmountWithTds+$tdsAmount;
                $tdsFlag               = $_POST['TDS'];
                $tdsType               = isset($_POST['tds_type']) && $_POST['tds_type'] != '' ? $_POST['tds_type'] : 0;

                if ($tdsFlag == 'No') {
                    $tdsType  = 0;                  
                } else {
                    $tdsType = $tdsType;
                }

                $TotalAmount = $totalAmountWithTds;
                // update receipt no. and payment TDS details in dra payment transaction
                $update_trans_data = array('status' => 7,'amount' => $TotalAmount,'isTDS' => $tdsFlag,'tds_type' => $tdsType,'tds_amount' => $tdsAmount);
                $this->master_model->updateRecord('payment_transaction',$update_trans_data,array('id'=>$pt_id));

                $arr_invoice_data = $this->master_model->getRecords('exam_invoice',array('pay_txn_id'=>$pt_id,'app_type'=>'W'));

                if($arr_invoice_data[0]['state_of_center'] == 'MAH')
                {
                    $update_invoice_data = array('tds_amt' => $tdsAmount,'cs_total' => $TotalAmount);
                    $this->master_model->updateRecord('exam_invoice',$update_invoice_data,array('pay_txn_id'=>$pt_id,'app_type'=>'W'));
                }
                else
                {
                    $update_invoice_data = array('tds_amt' => $tdsAmount,'igst_total' => $TotalAmount);
                    $this->master_model->updateRecord('exam_invoice',$update_invoice_data,array('pay_txn_id'=>$pt_id,'app_type'=>'W'));
                }

                // Create transaction
                $pg_flag = 'IIBFDRAREG';  //'IIBF_DRA_REG';

                $center_id             = $paymentTransInfo[0]['ref_id'];
                $MerchantOrderNo       = $paymentTransInfo[0]['receipt_no'];
                // $TotalAmount           = $paymentTransInfo[0]['amount'];
                $pg_other_details      = $paymentTransInfo[0]['pg_other_details'];
                $arr_pg_other_details  = explode('^', $pg_other_details);
                
                $custom_field_billdesk = $pt_id."-iibfregn-".$pg_flag."-".$arr_pg_other_details[4];
            }   
        }
        
        $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $TotalAmount, $pt_id, $pt_id, '', 'iibfdra/Version_2/Center/handle_billdesk_response', '', '', '', $custom_field_billdesk);
        
        if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE')
        {
            $userarr = array('pt_id' => $pt_id, 'receipt_no' => $MerchantOrderNo);
            $this->session->set_userdata('SESSION_MEMBER_DATA', $userarr);

            $data['bdorderid']      = $billdesk_res['bdorderid'];
            $data['token']          = $billdesk_res['token'];
            $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
            $data['returnUrl']      = $billdesk_res['returnUrl'];
            $this->load->view('pg_billdesk/pg_billdesk_form', $data);
        }
        else
        {
            if (isset($billdesk_res['status']) && $billdesk_res['status'] == 409) {
                $this->session->set_flashdata('error', 'Payment has been cancelled.');  
            } else {
                $this->session->set_flashdata('error', 'Transaction failed...!');
            }            
            redirect(base_url() . 'iibfdra/Version_2/Center/listing');
        }
    }
}

?>
