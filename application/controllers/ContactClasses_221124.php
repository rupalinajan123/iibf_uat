<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
/*
    last modified by : Pooja Mane
    last modified on : 2024-07-04
*/
class ContactClasses extends CI_Controller
{
    public function __construct()
    {//exit;
        parent::__construct();
        
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('upload_helper');
        $this->load->helper('contact_classes_invoice_helper');
        //$this->load->helper('renewal_invoice_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
		
		$this->load->model('billdesk_pg_model');
		
        //accedd denied due to GST
        //$this->master_model->warning();
        
    }
    
    public function index()
    {
		$request_cnt = 0;
        $var_errors = '';
        //member no submit
        if (isset($_POST['getdata'])) {
            $member_no          = $_POST['mem_no'];
            $_SESSION['mem_no'] = $member_no;
				
					if(!empty($member_no))
					{
								$config = array(
						array(
								'field' => 'mem_no',
								'label' => 'Registration/Membership No.',
								'rules' => 'trim|required'
						),
						array(
								'field' => 'code2',
								'label' => 'Code',
								'rules' => 'trim|required|callback_check_captcha_userreg',
						),
					);
					$this->form_validation->set_rules($config);
						$dataarr=array(
							'regnumber'=> mysqli_real_escape_string($this->db->conn_id,$this->security->xss_clean($this->input->post('mem_no'))),
							'isactive'=>'1',
							'isdeleted'=>'0'
						);
						$request_cnt = $_POST['request_cnt'] + 1;
					    if($this->form_validation->run()==TRUE)
						{
									$member_array       = array();
								$member_array       = $this->master_model->getRecords('member_registration', array(
									'regnumber' => $member_no,
									'isactive' => '1'
								));
						}
						else{
							$this->session->set_flashdata('error', 'Invalid Membership Number or captcha.');
							redirect(base_url() . 'ContactClasses');
						}
						
					
                
                //get cource name
                $cource = array();
                $this->db->where('isactive', '1');
                $cource = $this->master_model->getRecords('contact_classes_cource_master');
                
                
                $this->db->where('state_master.state_delete', '0');
                $states = $this->master_model->getRecords('state_master');
                
                
                //echo $this->db->last_query();
                //exit;
                /* $this->load->helper('captcha');
                //$this->session->unset_userdata("regcaptcha");
                $this->session->set_userdata("regcaptcha", rand(1, 100000));
                $vals                   = array(
                    'img_path' => './uploads/applications/',
                    'img_url' => base_url() . 'uploads/applications/'
                );
                $cap                    = create_captcha($vals);
                $_SESSION["regcaptcha"] = $cap['word']; */
				$this->load->model('Captcha_model');
				$captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha'); 
                $data                   = array(
                    'middle_content' => 'contact_classes/contact_classes',
                    'states' => $states,
                    'image' => $captcha_image,
                    'var_errors' => $var_errors,
                    'member_data' => $member_array,
                    'cource' => $cource
                );
                $this->load->view('common_view_fullwidth', $data);
                
                
            }
            
        } else {
            
            
            
            $this->db->where('state_master.state_delete', '0');
            $states = $this->master_model->getRecords('state_master');
            
            
            //echo $this->db->last_query();
            //exit;
            /* $this->load->helper('captcha');
            //$this->session->unset_userdata("regcaptcha");
            $this->session->set_userdata("regcaptcha", rand(1, 100000));
            $vals                   = array(
                'img_path' => './uploads/applications/',
                'img_url' => base_url() . 'uploads/applications/'
            );
            $cap                    = create_captcha($vals);
            $_SESSION["regcaptcha"] = $cap['word']; */
			
			$this->load->model('Captcha_model');
			$captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha'); 
				
            $data                   = array(
                'middle_content' => 'contact_classes/contact_classes',
                'states' => $states,
                'image' => $captcha_image,
                'var_errors' => $var_errors
            );
            $this->load->view('common_view_fullwidth', $data);
        }
		
    }

    //New function For KYC,AML & CFT Virtual Training Program form display created by Pooja Mane : 03-07-2024
    public function VirtualTraining()
    {
        $request_cnt = 0;
        $var_errors = '';
        $todays_date = date('d-M-Y');
            //get cource name
            $cource = array();
            $this->db->where('isactive', '1');
            $this->db->where('course_code', '1002');//Link is made for this exam only
            $cource = $this->master_model->getRecords('contact_classes_cource_master');

            //get active courses
            $this->db->where('course_code', $cource[0]['course_code']);
            $course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');

            //get centers
            $this->db->where('course_code', $cource[0]['course_code']);
            $this->db->where('course_end_date>=',   $todays_date );
            $center = $this->master_model->getRecords('contact_classes_center_master');

            //get states
            $this->db->where('state_master.state_delete', '0');
            $states = $this->master_model->getRecords('state_master');

            //get institutions
            $this->db->where('institution_delete', '0');
            $institution = $this->master_model->getRecords('institution_master');

            //get institutions
            $this->db->where('designation_delete', '0');
            $designation = $this->master_model->getRecords('designation_master');

            $this->load->model('Captcha_model');
            $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha'); 
                
            $data                   = array(
                'middle_content' => 'contact_classes/virtual_training',
                'course' => $cource,
                'center' => $center,
                'states' => $states,
                'institution' => $institution,
                'designation' => $designation,
                'image' => $captcha_image,
                'var_errors' => $var_errors
            );
            // print_r($data);die;
            $this->load->view('common_view_fullwidth', $data);
    }

    public function getcenter()
    {
	
	 	$i      = 0;
        $data   = array();
		$course_prd=array();
         $todays_date  =  date("Y-m-d H:i:s");
        $courcecode = mysqli_real_escape_string($this->db->conn_id,$_POST['courcecode']);
		$memno=$_POST['memno'];
		 $output['redirect_url'] = 'No';
		//For DB&F 
		if($courcecode=='42')
		{
		$this->db->where('regnumber', $memno);
        $this->db->where('isactive', '1');
        $memtype = $this->master_model->getRecords('member_registration');
		if(!empty($memtype))
		{
		
			if($memtype[0]['registrationtype']=='O' || $memtype[0]['registrationtype']=='NM' || $memtype[0]['registrationtype']=='A' || $memtype[0]['registrationtype']=='F'){
			
			  
			   $output['redirect_url'] = 'Yes';
			   $output['Code'] = '42';
               // redirect(base_url() . 'ContactClasses');
				//exit;
		}else
		{
			
		
		$this->db->where('course_code', $courcecode);
		$course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
		if(!empty($course_prd))
		{
			$_SESSION['period']= $course_prd [0]['exam_prd'];
		}
		
		
        $this->db->where('course_code', $courcecode);
		 $this->db->where('course_end_date>=',   $todays_date );
        $center = $this->master_model->getRecords('contact_classes_center_master');
       
        foreach ($center as $result) {
            $data[$i]['center_code'] = $result['center_code'];
            $data[$i]['center_name'] = $result['center_name'];
            $i++;
        }
       
        //$data_arr = array('record'=>$data);
        $output['cource'] = $data;
		
		}
		}
		}elseif($courcecode=='210')
		{
		$this->db->where('regnumber', $memno);
        $this->db->where('isactive', '1');
        $memtype = $this->master_model->getRecords('member_registration');
		if(!empty($memtype))
		{
			if($memtype[0]['registrationtype']=='DB' || $memtype[0]['registrationtype']=='NM' ){
			
			  
			   $output['redirect_url'] = 'Yes';
			    $output['Code'] = '210';
               // redirect(base_url() . 'ContactClasses');
			}else
			{
			
				
		
		$this->db->where('course_code', $courcecode);
		$course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
		if(!empty($course_prd))
		{
			$_SESSION['period']= $course_prd [0]['exam_prd'];
		}
		
		
        $this->db->where('course_code', $courcecode);
		 $this->db->where('course_end_date>=',   $todays_date );
        $center = $this->master_model->getRecords('contact_classes_center_master');
       
        foreach ($center as $result) {
            $data[$i]['center_code'] = $result['center_code'];
            $data[$i]['center_name'] = $result['center_name'];
            $i++;
        }
       
        //$data_arr = array('record'=>$data);
        $output['cource'] = $data;
		
			
			}
		}
		}else{
		
		$this->db->where('course_code', $courcecode);
		$course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
		if(!empty($course_prd))
		{
			$_SESSION['period']= $course_prd [0]['exam_prd'];
		}
		
		
        $this->db->where('course_code', $courcecode);
		 $this->db->where('course_end_date>=',   $todays_date );
        $center = $this->master_model->getRecords('contact_classes_center_master');
       
        foreach ($center as $result) {
            $data[$i]['center_code'] = $result['center_code'];
            $data[$i]['center_name'] = $result['center_name'];
            $i++;
        }
       
        //$data_arr = array('record'=>$data);
        $output['cource'] = $data;
		}
		
        echo $putput = json_encode($output);
        
    }
  
    public function getsub()
    {
        
        $centercode = mysqli_real_escape_string($this->db->conn_id,$_POST['centercode']);
        $course_id  = mysqli_real_escape_string($this->db->conn_id,$_POST['course_id']);
 		$todays_date  =  date("Y-m-d H:i:s");
        $sub = array();
		
		$this->db->where('course_code', $course_id);
		$course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
		if(!empty($course_prd))
		{
			$_SESSION['period']= $course_prd [0]['exam_prd'];
		}
		
		$select='*';
        $this->db->where('course_code', $course_id);
        $this->db->where('center_code', $centercode);
		$this->db->where('exam_prd', $course_prd[0]['exam_prd']);
		$this->db->where('last_date_reg>=',   $todays_date );
        $sub = $this->master_model->getRecords('contact_classes_subject_master','',$select,array('sub_code'=>'DESC'));
	    // echo $this->db->last_query();exit;
	
        $i = 0;
        $data = array();
		
            foreach ($sub as $result) 
    		{
		      $sub_cap_full = $this->master_model->getRecordCount('contact_classes_Subject_registration', array(
                  'program_code'=> $course_id,
				    'sub_code' =>$result['sub_code'],
                    'center_code' =>$centercode,
                    'remark' =>'1',
					'program_prd' =>$_SESSION['period']
				 ));
				 // echo $this->db->last_query();exit;
    			$capacity='no';
    			if($sub_cap_full>=$result['capacity'])
    			{
    				$capacity='yes';
    			}
				$data[$i]['Scode']        = $result['sub_code'];
				$data[$i]['Sname']        = $result['sub_name'];
				$data[$i]['cource_date1'] = $result['cource_date1'];
				$data[$i]['cource_date2'] = $result['cource_date2'];
				$data[$i]['cource_date3'] = $result['cource_date3'];
				$data[$i]['cource_date4'] = $result['cource_date4'];
				$data[$i]['cource_date5'] = $result['cource_date5'];
				$data[$i]['cource_date6'] = $result['cource_date6'];
				$data[$i]['cource_date7'] = $result['cource_date7'];
				$data[$i]['capcity_full'] = $capacity;
				$i++;
            }
        
        #--subject 1--#
        if (isset($data[0]['cource_date1'])) {
            if ($data[0]['cource_date1'] != '0000-00-00') {
                $S1date1   = '';
                $timestamp = strtotime($data[0]['cource_date1']);
                $S1date1   = date("d-M-Y", $timestamp);
            } else {
                echo $S1date1 = '';
            }
            
            
            if ($data[0]['cource_date2'] != '0000-00-00') {
                $S1date2   = '';
                $timestamp = strtotime($data[0]['cource_date2']);
                $S1date2   = date("d-M-Y", $timestamp);
            } else {
                $S1date2 = '';
            }
            
            if ($data[0]['cource_date3'] != '0000-00-00') {
                $S1date3   = '';
                $timestamp = strtotime($data[0]['cource_date3']);
                $S1date3   = date("d-M-Y", $timestamp);
            } else {
                $S1date3 = '';
            }
			
			if ($data[0]['cource_date4'] != '0000-00-00') {
                $S1date4   = '';
                $timestamp = strtotime($data[0]['cource_date4']);
                $S1date4   = date("d-M-Y", $timestamp);
            } else {
                $S1date4 = '';
            }
			
			
					if ($data[0]['cource_date5'] != '0000-00-00') {
                $S1date5   = '';
                $timestamp = strtotime($data[0]['cource_date5']);
                $S1date5   = date("d-M-Y", $timestamp);
            } else {
                $S1date5 = '';
            }
			
			if ($data[0]['cource_date6'] != '0000-00-00') {
                $S1date6   = '';
                $timestamp = strtotime($data[0]['cource_date6']);
                $S1date6   = date("d-M-Y", $timestamp);
            } else {
                $S1date6 = '';
            }
			
				if ($data[0]['cource_date7'] != '0000-00-00') {
                $S1date7   = '';
                $timestamp = strtotime($data[0]['cource_date7']);
                $S1date7  = date("d-M-Y", $timestamp);
            } else {
                $S1date7 = '';
            }
        }
        #--subject 1--#
        
        #--subject 2--#
        if (isset($data[1]['cource_date1'])) {
            if ($data[1]['cource_date1'] != '0000-00-00') {
                $S2date1   = '';
                $timestamp = strtotime($data[1]['cource_date1']);
                $S2date1   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date1 = '';
            }
            
            
            if ($data[1]['cource_date2'] != '0000-00-00') {
                $S2date2   = '';
                $timestamp = strtotime($data[1]['cource_date2']);
                $S2date2   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date2 = '';
            }
            
            if ($data[1]['cource_date3'] != '0000-00-00') {
                $S2date3   = '';
                $timestamp = strtotime($data[1]['cource_date3']);
                $S2date3   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date3 = '';
            }
			
			  if ($data[1]['cource_date4'] != '0000-00-00') {
                $S2date4   = '';
                $timestamp = strtotime($data[1]['cource_date4']);
                $S2date4   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date4 = '';
            }
			
			  if ($data[1]['cource_date5'] != '0000-00-00') {
                $S2date5   = '';
                $timestamp = strtotime($data[1]['cource_date5']);
                $S2date5   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date5 = '';
            }
			
			  if ($data[1]['cource_date6'] != '0000-00-00') {
                $S2date6   = '';
                $timestamp = strtotime($data[1]['cource_date6']);
                $S2date6   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date6 = '';
            }
			
				  if ($data[1]['cource_date7'] != '0000-00-00') {
                $S2date7   = '';
                $timestamp = strtotime($data[1]['cource_date7']);
                $S2date7   = date("d-M-Y", $timestamp);
            } else {
                echo $S2date7 = '';
            }
			
        }
        #--subject 2--#
        
        #--subject 2--#
        if (isset($data[2]['cource_date1'])) {
            if ($data[2]['cource_date1'] != '0000-00-00') {
                $S3date1   = '';
                $timestamp = strtotime($data[2]['cource_date1']);
                $S3date1   = date("d-M-Y", $timestamp);
            } else {
                $S3date1 = '';
            }
            
            
            if ($data[2]['cource_date2'] != '0000-00-00') {
                $S3date2   = '';
                $timestamp = strtotime($data[2]['cource_date2']);
                $S3date2   = date("d-M-Y", $timestamp);
            } else {
                $S3date2 = '';
            }
            
            
            if ($data[2]['cource_date3'] != '0000-00-00') {
                $S3date3   = '';
                $timestamp = strtotime($data[2]['cource_date3']);
                $S3date3   = date("d-M-Y", $timestamp);
                
            } else {
                $S3date3 = '';
            }
			
			 if ($data[2]['cource_date4'] != '0000-00-00') {
                $S3date4   = '';
                $timestamp = strtotime($data[2]['cource_date4']);
                $S3date4   = date("d-M-Y", $timestamp);
                
            } else {
                $S3date4 = '';
            }
			
				 if ($data[2]['cource_date5'] != '0000-00-00') {
                $S3date5   = '';
                $timestamp = strtotime($data[2]['cource_date5']);
                $S3date5   = date("d-M-Y", $timestamp);
                
            } else {
                $S3date5 = '';
            }
			
			
				 if ($data[2]['cource_date6'] != '0000-00-00') {
                $S3date6   = '';
                $timestamp = strtotime($data[2]['cource_date6']);
                $S3date6   = date("d-M-Y", $timestamp);
                
            } else {
                $S3date6 = '';
            }
			
				 if ($data[2]['cource_date7'] != '0000-00-00') {
                $S3date7   = '';
                $timestamp = strtotime($data[2]['cource_date7']);
                $S3date7   = date("d-M-Y", $timestamp);
                
            } else {
                $S3date7 = '';
            }
        }
        #--subject 3--#

        #--subject 4--# Added by : Pooja Mane On: 2023-03-22
        if (isset($data[3]['cource_date1'])) {
            if ($data[3]['cource_date1'] != '0000-00-00') {
                $S4date1      = '';
                $timestamp    = strtotime($data[3]['cource_date1']);
                $S4date1 = date("d-M-Y", $timestamp);
            } else {
                echo $S4date1 = '';
            }

            if ($data[3]['cource_date2'] != '0000-00-00') {
                $S4date2      = '';
                $timestamp    = strtotime($data[3]['cource_date2']);
                $S4date2 = date("d-M-Y", $timestamp);
            } else {
                echo $S4date2 = '';
            }

            if ($data[3]['cource_date3'] != '0000-00-00') {
                $S4date3   = '';
                $timestamp = strtotime($data[3]['cource_date3']);
                $S4date3   = date("d-M-Y", $timestamp);
            } else {
                echo $S4date3 = '';
            }

            if ($data[3]['cource_date4'] != '0000-00-00') {
                $S4date4   = '';
                $timestamp = strtotime($data[3]['cource_date4']);
                $S4date4   = date("d-M-Y", $timestamp);
            } else {
                echo $S4date4 = '';
            }

            if ($data[3]['cource_date5'] != '0000-00-00') {
                $S4date5   = '';
                $timestamp = strtotime($data[3]['cource_date5']);
                $S4date5   = date("d-M-Y", $timestamp);
            } else {
                echo $S4date5 = '';
            }

            if ($data[3]['cource_date6'] != '0000-00-00') {
                $S4date6   = '';
                $timestamp = strtotime($data[3]['cource_date6']);
                $S4date6   = date("d-M-Y", $timestamp);
            } else {
                echo $S4date6 = '';
            }

            if ($data[3]['cource_date7'] != '0000-00-00') {
                $S4date7   = '';
                $timestamp = strtotime($data[3]['cource_date7']);
                $S4date7   = date("d-M-Y", $timestamp);
            } else {
                echo $S4date7 = '';
            }

        }
        #--subject 4--# Added code end : Pooja Mane On: 2023-03-22
        
        
        $subject = '';
        //$data_arr = array('record'=>$data);
        
        //venue
        $venue = array();
        $this->db->distinct('venue_name');
        $this->db->where('center_code', $centercode);
        $venue      = $this->master_model->getRecords('contact_classes_venue_master');
        $venue_data = array();
        
        foreach ($venue as $result) {
            $venue_data['venue_code'] = $result['venue_code'];
            $venue_data['venue_name'] = $result['venue_name'];
            
        }
        $_SESSION['venue_name'] = $venue_data['venue_name'];
        
        if (isset($data[2]['Scode']) && isset($data[2]['Sname'])) {
            $sub_code = $data[2]['Scode'];
            $sub_name = $data[2]['Sname'];
            
        } else {
            $sub_code = $sub_name = $venue_data['venue_code'] = $venue_data['venue_name'] = '';
            
        }
        if (isset($data[0]['Scode']) && !isset($data[1]['Scode']) && !isset($data[2]['Scode']) && !isset($data[3]['Scode'])) {
            $subject .= "   <label for='roleid'  class='col-sm-1 control-label'>Subject<span style='color:#F00'>*</span></label>

            <span style='color:#F00'> Select the subject </span>
            <table id='listitems' class='table table-bordered table-striped dataTables-example'>
             <thead>
                <tr>
                 <td   nowrap='nowrap'><b> Sr No.</b> </td>
                 <td  nowrap='nowrap' ><b> Subject Name</b> </td>
                  <td nowrap='nowrap'><b> Venue Name</b> </td>
                  <td width='20%'><b> Course Date    </b> </td>
                  <td   nowrap='nowrap'><b> Select the Subject</b> </td>
                </tr>
              </thead>
                    <tbody>
                      <tr>
                         <td>
                          1.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[0]['Scode'] . ">
                         " . $data[0]['Sname'] . " </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                         " . $S1date1 . "<br>
                             " . $S1date2 . "<br>
                             " . $S1date3 . "<br>
                             " . $S1date4 . "<br>
                             " . $S1date5 . "<br>
                             " . $S1date6 . "<br>
                             " . $S1date7 . "
                          </td>";

            if ($data[0]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs1' value=" . $data[0]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>

                    </tbody>
                  </table>";
        } elseif (isset($data[0]['Scode']) && isset($data[1]['Scode']) && !isset($data[2]['Scode']) && !isset($data[3]['Scode'])) {

            $subject .= "   <label for='roleid'  class='col-sm-1 control-label'>Subject<span style='color:#F00'>*</span></label>
           <span style='color:#F00'> Select the subject </span>

            <table id='listitems' class='table table-bordered table-striped dataTables-example'>
                <thead>
                <tr>
                 <td   nowrap='nowrap'><b> Sr No.</b> </td>
                 <td  nowrap='nowrap' ><b> Subject Name</b> </td>
                  <td nowrap='nowrap'><b> Venue Name</b> </td>
                  <td  width='20%'><b> Course Date</b> </td>
                  <td   nowrap='nowrap'><b> Select the Subject</b> </td>
                </tr>
              </thead>
                    <tbody>
                      <tr>
                         <td>
                          1.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[0]['Scode'] . ">
                         " . $data[0]['Sname'] . " </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                         " . $S1date1 . "<br>
                             " . $S1date2 . "<br>
                              " . $S1date3 . "<br>
                               " . $S1date4 . "<br>
                                " . $S1date5 . "<br>
                                 " . $S1date6 . "<br>
                                " . $S1date7 . "
                         </td>";

            if ($data[0]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs1' value=" . $data[0]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                          <td>
                         2.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[1]['Scode'] . ">

                        " . $data[1]['Sname'] . "
                           </td>
                        <td>
                        " . $_SESSION['venue_name'] . "
                          </td>
                        <td >
                        " . $S2date1 . "<br>
                         " . $S2date2 . "<br>
                          " . $S2date3 . "<br>
                           " . $S2date4 . "<br>
                            " . $S2date5 . "<br>
                             " . $S2date6 . "<br>
                            " . $S2date7 . "
                           </td>";

            if ($data[1]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs2' value=" . $data[1]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>

                        </tr>
                    </tbody>
                  </table>";
        } elseif (isset($data[0]['Scode']) && isset($data[1]['Scode']) && isset($data[2]['Scode']) && !isset($data[3]['Scode'])) {
            $subject .= "   <label for='roleid'  class='col-sm-1 control-label'>Subject<span style='color:#F00'>*</span></label>


            <table id='listitems' class='table table-bordered table-striped dataTables-example'>
                <thead>
                <tr>
                 <td   nowrap='nowrap'><b> Sr No.</b> </td>
                 <td  nowrap='nowrap' ><b> Subject Name</b> </td>
                  <td nowrap='nowrap'><b> Venue Name</b> </td>
                  <td width='20%'><b> Course Date</b> </td>
                  <td   nowrap='nowrap'><b> Select the Subject</b> </td>
                </tr>
              </thead>
                    <tbody>
                      <tr>
                         <td>
                          1.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[0]['Scode'] . ">
                         " . $data[0]['Sname'] . " </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                         " . $S1date1 . "<br>
                             " . $S1date2 . "<br>
                                 " . $S1date3 . "<br>
                                     " . $S1date4 . "<br>
                                         " . $S1date5 . "<br>
                                             " . $S1date6 . "<br>
                                                    " . $S1date7 . "
                         </td>";

            if ($data[0]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs1' value=" . $data[0]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                          <td>
                         2.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[1]['Scode'] . ">

                        " . $data[1]['Sname'] . "
                           </td>
                        <td>
                        " . $_SESSION['venue_name'] . "
                          </td>
                        <td >
                        " . $S2date1 . "<br>
                         " . $S2date2 . "<br>
                          " . $S2date3 . "<br>
                           " . $S2date4 . "<br>
                            " . $S2date5 . "<br>
                             " . $S2date6 . "<br>
                                " . $S2date7 . "
                         </td>";

            if ($data[1]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs2' value=" . $data[1]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                    <td>
                         3.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[2]['Scode'] . ">

                        " . $data[2]['Sname'] . "
                           </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                " . $S3date1 . "<br>
                        " . $S3date2 . "<br>
                        " . $S3date3 . "<br>
                        " . $S3date4 . "<br>"
                . $S3date5 . "<br>
                         " . $S3date6 . "<br>
                                 " . $S3date7 . "
                            </td>";

            if ($data[2]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs3' value=" . $data[2]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                    </tbody>
                  </table>";
        } elseif (isset($data[0]['Scode']) && isset($data[1]['Scode']) && isset($data[2]['Scode']) && isset($data[3]['Scode'])) {
            $subject .= "   <label for='roleid'  class='col-sm-1 control-label'>Subject<span style='color:#F00'>*</span></label>


            <table id='listitems' class='table table-bordered table-striped dataTables-example'>
                <thead>
                <tr>
                 <td   nowrap='nowrap'><b> Sr No.</b> </td>
                 <td  nowrap='nowrap' ><b> Subject Name</b> </td>
                  <td nowrap='nowrap'><b> Venue Name</b> </td>
                  <td width='20%'><b> Course Date</b> </td>
                  <td   nowrap='nowrap'><b> Select the Subject</b> </td>
                </tr>
              </thead>
                    <tbody>
                      <tr>
                         <td>
                          1.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[0]['Scode'] . ">
                         " . $data[0]['Sname'] . " </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                         " . $S1date1 . "<br>
                             " . $S1date2 . "<br>
                                 " . $S1date3 . "<br>
                                     " . $S1date4 . "<br>
                                         " . $S1date5 . "<br>
                                             " . $S1date6 . "<br>
                                                    " . $S1date7 . "
                         </td>";

            if ($data[0]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs1' value=" . $data[0]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                          <td>
                         2.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[1]['Scode'] . ">

                        " . $data[1]['Sname'] . "
                           </td>
                        <td>
                        " . $_SESSION['venue_name'] . "
                          </td>
                        <td >
                        " . $S2date1 . "<br>
                         " . $S2date2 . "<br>
                          " . $S2date3 . "<br>
                           " . $S2date4 . "<br>
                            " . $S2date5 . "<br>
                             " . $S2date6 . "<br>
                                " . $S2date7 . "
                         </td>";

            if ($data[1]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs2' value=" . $data[1]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                    <td>
                         3.
                          </td>
                        <td >
                <input type='hidden' id='s1' value=" . $data[2]['Scode'] . ">

                        " . $data[2]['Sname'] . "
                           </td>
                        <td>
                         " . $_SESSION['venue_name'] . "
                          </td>
                        <td>
                        " . $S3date1 . "<br>
                        " . $S3date2 . "<br>
                        " . $S3date3 . "<br>
                        " . $S3date4 . "<br>
                        " . $S3date5 . "<br>
                        " . $S3date6 . "<br>
                        " . $S3date7 . "
                            </td>";

            if ($data[2]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                         </td>
                        </tr>";

            } else {
                $subject .= "
                        <td> <input type='checkbox' name='cs[]' id='cs3' value=" . $data[2]['Scode'] . ">
                         </td>
                        </tr>";
            }
            $subject .= "<tr>
                        <td>
                        4.
                         </td>
                       <td >
               <input type='hidden' id='s1' value=" . $data[3]['Scode'] . ">

                       " . $data[3]['Sname'] . "
                          </td>
                       <td>
                        " . $_SESSION['venue_name'] . "
                         </td>
                       <td>
                       " . $S4date1 . "<br>
                       " . $S4date2 . "<br>
                       " . $S4date3 . "<br>
                       " . $S4date4 . "<br>
                       " . $S4date5 . "<br>
                       " . $S4date6 . "<br>
                       " . $S4date7 . "
                           </td>";

            if ($data[3]['capcity_full'] == 'yes') {

                $subject .= " <td style='color:red'> Capacity full
                        </td>
                       </tr>";

            } else {
                $subject .= "
                       <td> <input type='checkbox' name='cs[]' id='cs3' value=" . $data[3]['Scode'] . ">
                        </td>
                       </tr>";
            }
            $subject .= "<tr>
                    </tbody>
                  </table>";
        }
        echo $subject;
        //echo $putput = json_encode($subject);
        
    }

    public function check_member()
    {
        $var_errors = '';
        //final data submit
        if (isset($_POST['btnSubmit'])) 
        {
            
            $state = '';
            // $this->form_validation->set_rules('center', 'Center ', 'trim|required|xss_clean');
            // $this->form_validation->set_rules('course', 'Course', 'trim|required|xss_clean');
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('fname', 'Name', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            
            if (!isset($_POST['course'])) {
                $this->session->set_flashdata('error', 'Select the course');
                redirect(base_url() . 'ContactClasses');  
            }
            
            if (!isset($_POST['center'])) {
                $this->session->set_flashdata('error', 'Select the center');
                redirect(base_url() . 'ContactClasses');
            }

            if (empty($_POST["cs"])) {
                $this->session->set_flashdata('error', 'Select the subjects');
                redirect(base_url() . 'ContactClasses');
            }
            
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin');
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['stdcode']) && $_POST['stdcode'] != '') {
                $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
            }
            
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
            $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
            $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
            $this->form_validation->set_rules('course', 'Course', 'trim|required|xss_clean');
            $this->form_validation->set_rules('center', 'Center', 'trim|required|xss_clean');
         
            if ($this->form_validation->run() == TRUE) 
            {
                $user_data = array(
                //'mem_no'=>$_POST['mem'],
                'sel_namesub' => $_POST["sel_namesub"],
                'fname' => $_POST["fname"],
                'mname' => $_POST["mname"],
                'lname' => $_POST["lname"],
                'addressline1' => $_POST["addressline1"],
                'addressline2' => $_POST["addressline2"],
                'addressline3' => $_POST["addressline3"],
                'addressline4' => $_POST["addressline4"],
                'district' => substr($_POST["district"], 0, 30),
                'city' => $_POST["city"],
                'code' => trim($_POST["code"]),
                'email' => $_POST["email"],
                'mobile' => $_POST["mobile"],
                'pincode' => $_POST["pincode"],
                'state' => $_POST["state"],
                'cource_code' => $_POST["course"],
                'center_code' => $_POST["center"],
                'subjects' =>$_POST["cs"]
                );
                
                $this->session->set_userdata('enduserinfo', $user_data);
                $this->form_validation->set_message('error', "");

    		    $log_message = serialize($user_data);
    		    $logs = array(
				'title' =>'Contact Class registration',
				'description' =>$log_message);
				
				$this->master_model->insertRecord('contactclass_logs', $logs,true);

                redirect(base_url() . 'ContactClasses/preview');
            }      
        }
        
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
    
    
        //echo $this->db->last_query();
        //exit;
        $this->load->helper('captcha');
        //$this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/'
        );

        $cap                    = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];
        $data                   = array(
            'middle_content' => 'contact_classes/contact_classes',
            'states' => $states,
            'image' => $cap['image'],
            'var_errors' => $var_errors
        );

        $this->load->view('common_view_fullwidth', $data);
    }

    //New function For KYC,AML program to validate data created by Pooja Mane : 03-07-2024
    public function check_vtmember()
    {
        $var_errors = '';
        //final data submit
        if (isset($_POST['btnSubmit'])) {
            $state = '';

            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('fname', 'Name', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            
            if (!isset($_POST['course'])) {
                $this->session->set_flashdata('error', 'Select the course');
                redirect(base_url() . 'ContactClasses');
            }
            
            if (!isset($_POST['center'])) {
                $this->session->set_flashdata('error', 'Select the center');
                redirect(base_url() . 'ContactClasses');
            }
            if (empty($_POST["cs"])) {
                $this->session->set_flashdata('error', 'Select the subjects');
                redirect(base_url() . 'ContactClasses');
            }
            
            
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin');
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean');
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|xss_clean');
            }
            
            if (isset($_POST['stdcode']) && $_POST['stdcode'] != '') {
                $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
            }
            
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
            $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');
            $this->form_validation->set_rules('course', 'Course', 'trim|required|xss_clean');
            $this->form_validation->set_rules('center', 'Center', 'trim|required|xss_clean');
         
        
           if ($this->form_validation->run() == TRUE) {
                $user_data = array(
                    //'mem_no'=>$_POST['mem'],
                    'sel_namesub' => $_POST["sel_namesub"],
                    'fname' => $_POST["fname"],
                    'mname' => $_POST["mname"],
                    'lname' => $_POST["lname"],
                    'addressline1' => $_POST["addressline1"],
                    'addressline2' => $_POST["addressline2"],
                    'addressline3' => $_POST["addressline3"],
                    'addressline4' => $_POST["addressline4"],
                    'district' => substr($_POST["district"], 0, 30),
                    'city' => $_POST["city"],
                    'code' => trim($_POST["code"]),
                    'email' => $_POST["email"],
                    'mobile' => $_POST["mobile"],
                    'institution' => $_POST["institution"],
                    'designation' => $_POST["designation"],
                    'pincode' => $_POST["pincode"],
                    'state' => $_POST["state"],
                    'cource_code' => $_POST["course"],
                    'center_code' => $_POST["center"],
                    'subjects' =>$_POST["cs"]
                );
                
                // print_r($user_data);die;
                $this->session->set_userdata('enduserinfo', $user_data);
                $this->form_validation->set_message('error', "");

            $log_message = serialize($user_data);
            $logs = array(
                    'title' =>'Contact Class registration',
                    'description' =>$log_message);
                    
                    $this->master_model->insertRecord('contactclass_logs', $logs,true);

                    redirect(base_url() . 'ContactClasses/vtpreview');
                }
        }
        
        
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        
        $this->load->helper('captcha');

        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];
        $data                   = array(
            'middle_content' => 'contact_classes/virtual_training',
            'states' => $states,
            'image' => $cap['image'],
            'var_errors' => $var_errors
        );
        $this->load->view('common_view_fullwidth', $data);
    }
    
    public function preview()
    {
			//get period
			//echo '<pre>'; print_r($this->session->userdata['enduserinfo']);DIE;
            $course_prd=array();
			$this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
			$course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
			if(!empty($course_prd))
			{
				$_SESSION['period']= $course_prd [0]['exam_prd'];
			}
			$todays_date  =  date("Y-m-d H:i:s");
			$sub =	$selected_sub=$data = array();
			$selected_sub=$this->session->userdata['enduserinfo']['subjects'];
			foreach($selected_sub as $subj)
			{
					$select='*';
					$this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
    			    $this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
					$this->db->where('sub_code', $subj);
					$this->db->where('last_date_reg>=',$todays_date );
      				 $sub = $this->master_model->getRecords('contact_classes_subject_master','',$select,array('sub_code'=>'DESC'));
       				$i    = 0;
					$sub_cap_full = $this->master_model->getRecordCount('contact_classes_Subject_registration', array(
					'program_code' => $this->session->userdata['enduserinfo']['cource_code'],
					'sub_code' =>$subj,
					'remark' =>'1',
					'center_code' =>$this->session->userdata['enduserinfo']['center_code'],
					'program_prd' => $_SESSION['period']
					));


					 if($sub_cap_full>=$sub[0]['capacity'])
					 {
						 
						$sub_name=array(); 
						$this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
						$this->db->where('sub_code', $sub[0]['sub_code']);
						$sub_name = $this->master_model->getRecords('contact_classes_subject_master');
						
						$this->session->set_flashdata('error', 'The subject '.$sub_name[0]['sub_name']. ' capacity has been full !');
						redirect(base_url() . 'ContactClasses');
					 }
				
		}
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $data = array(
            'middle_content' => 'contact_classes/preview_contact_classes'
        );
        $this->load->view('common_view_fullwidth', $data);
        
    }

    //New function For KYC,AML & CFT Virtual Training Program Preview page created by Pooja Mane : 03-07-2024
    public function vtpreview()
    {
            //get period
            // echo '<pre>'; print_r($this->session->userdata['enduserinfo']);DIE;
            $course_prd=array();
            $this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
            $course_prd = $this->master_model->getRecords('contact_classes_cource_activation_master');
            if(!empty($course_prd))
            {
                $_SESSION['period']= $course_prd [0]['exam_prd'];
            }
            $todays_date  =  date("Y-m-d H:i:s");
            $sub =  $selected_sub=$data = array();
            $selected_sub=$this->session->userdata['enduserinfo']['subjects'];
            foreach($selected_sub as $subj)
            {
                    $select='*';
                    $this->db->where('course_code', $this->session->userdata['enduserinfo']['cource_code']);
                    $this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
                    $this->db->where('sub_code', $subj);
                    $this->db->where('last_date_reg>=',$todays_date );
                     $sub = $this->master_model->getRecords('contact_classes_subject_master','',$select,array('sub_code'=>'DESC'));
                    $i    = 0;
                    $sub_cap_full = $this->master_model->getRecordCount('contact_classes_Subject_registration', array(
                    'program_code' => $this->session->userdata['enduserinfo']['cource_code'],
                    'sub_code' =>$subj,
                    'remark' =>'1',
                    'center_code' =>$this->session->userdata['enduserinfo']['center_code'],
                    'program_prd' => $_SESSION['period']
                    ));


                     if($sub_cap_full>=$sub[0]['capacity'])
                     {
                         
                        $sub_name=array(); 
                        $this->db->where('center_code', $this->session->userdata['enduserinfo']['center_code']);
                        $this->db->where('sub_code', $sub[0]['sub_code']);
                        $sub_name = $this->master_model->getRecords('contact_classes_subject_master');
                        
                        $this->session->set_flashdata('error', 'The subject '.$sub_name[0]['sub_name']. ' capacity has been full !');
                        redirect(base_url() . 'ContactClasses');
                     }
                
        }
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $data = array(
            'middle_content' => 'contact_classes/preview_virtual_training'
        );
        $this->load->view('common_view_fullwidth', $data);
        
    }
    
    public function addrecord()
    {
        if (empty($this->session->userdata['enduserinfo'])) {
            redirect(base_url());
        }
        // print_r($this->session->userdata['enduserinfo']);DIE;
        //get member type
        $memtype          = array();
        $memberno         = '';
        $registrationtype = '';
        $this->db->where('regnumber', $this->session->userdata['mem_no']);
        $this->db->where('isactive', '1');
        $memtype = $this->master_model->getRecords('member_registration');

        if (!empty($memtype)) {
            $memberno         = $memtype[0]['regnumber'];
            $registrationtype = $memtype[0]['registrationtype'];
        }
        
        $insert_info = array(
            'member_no  ' => $memberno,
            //	'registrationtype ' =>$registrationtype,
            'namesub' => $memtype[0]['namesub'],
            'firstname' => $memtype[0]['firstname'],
            'middlename' => $memtype[0]['middlename'],
            'lastname' => $memtype[0]['lastname'],
            'email' => $memtype[0]['email'],
            'mobile' => $memtype[0]['mobile'],
            'address1' => $memtype[0]['address1'],
            'address2' => $memtype[0]['address2'],
            'address3' => $memtype[0]['address3'],
            'address4' => $memtype[0]['address4'],
            'district' => $memtype[0]['district'],
            'city' => $memtype[0]['city'],
            'state' => $memtype[0]['state'],
            'pincode' => $memtype[0]['pincode'],
            'program_code' => $this->session->userdata['enduserinfo']['cource_code'],
            'program_prd' => $_SESSION['period'],
            'center_code' => $this->session->userdata['enduserinfo']['center_code'],
            'createdon' => date("Y-m-d H:i:s"),
            'modify_date' => date("Y-m-d H:i:s"),
            'pay_status' => 0
        );
        
        
        
        if ($last_id = $this->master_model->insertRecord('contact_classes_registration', $insert_info, true)) {
            $upd_files = array();
            $pt_array  = array(
                'id' => $last_id
            );//echo $this->db->last_query();die;
            $this->session->set_userdata('contact_classes_memberdata', $pt_array);
            redirect(base_url() . "ContactClasses/make_payment");
        } else {
            $this->session->set_flashdata('error', 'Error while during registration for contact classes.please try again!');
            redirect(base_url() . 'ContactClasses');
        }
    }

    //New function For KYC,AML & CFT Virtual Training Program data submit and proceed to payment by Pooja Mane : 03-07-2024
    public function addvtrecord()
    {
        if (empty($this->session->userdata['enduserinfo'])) {
            redirect(base_url());
        }
        // echo'<pre>'; print_r($this->session->userdata['enduserinfo']);DIE;
        //get member type
        $memtype          = array();
        $memberno         = '';
        $registrationtype = '';
        $this->db->where('regnumber', $this->session->userdata['mem_no']);
        $this->db->where('isactive', '1');
        $memtype = $this->master_model->getRecords('member_registration');

        if (!empty($memtype)) {
            $memberno         = $memtype[0]['regnumber'];
            $registrationtype = $memtype[0]['registrationtype'];
        }
        
            $insert_info = array(
            'member_no  ' => $memberno,
            'namesub' => $this->session->userdata['enduserinfo']['sel_namesub'],
            'firstname' => $this->session->userdata['enduserinfo']['fname'],
            'middlename' => $this->session->userdata['enduserinfo']['mname'],
            'lastname' => $this->session->userdata['enduserinfo']['lname'],
            'email' => $this->session->userdata['enduserinfo']['email'],
            'mobile' => $this->session->userdata['enduserinfo']['mobile'],
            'institution' => $this->session->userdata['enduserinfo']['institution'],
            'designation' => $this->session->userdata['enduserinfo']['designation'],
            'address1' => $this->session->userdata['enduserinfo']['addressline1'],
            'address2' => $this->session->userdata['enduserinfo']['addressline2'],
            'address3' => $this->session->userdata['enduserinfo']['addressline3'],
            'address4' => $this->session->userdata['enduserinfo']['addressline4'],
            'district' => $this->session->userdata['enduserinfo']['district'],
            'city' => $this->session->userdata['enduserinfo']['city'],
            'state' => $this->session->userdata['enduserinfo']['state'],
            'pincode' => $this->session->userdata['enduserinfo']['pincode'],
            'program_code' => $this->session->userdata['enduserinfo']['cource_code'],
            'program_prd' => $_SESSION['period'],
            'center_code' => $this->session->userdata['enduserinfo']['center_code'],
            'createdon' => date("Y-m-d H:i:s"),
            'modify_date' => date("Y-m-d H:i:s"),
            'pay_status' => 0
            );

        
        // echo'<pre>';print_r($insert_info);die;
        if ($last_id = $this->master_model->insertRecord('contact_classes_registration', $insert_info, true)) {
            $upd_files = array();
            $pt_array  = array(
                'id' => $last_id
            );
            //added to update id as a member no for aml KYC training
                if($memberno == ''){
                    $update_data = array('member_no' => $last_id);
                     $this->master_model->updateRecord('contact_classes_registration', $update_data, array(
                        'contact_classes_id' => $last_id
                    ));
                    $this->session->set_userdata('mem_no', $last_id);
                }
            
            // echo $this->db->last_query();die;
            $this->session->set_userdata('contact_classes_memberdata', $pt_array);
            redirect(base_url() . "ContactClasses/make_payment");
        } else {
            $this->session->set_flashdata('error', 'Error while during registration for contact classes.please try again!');
            redirect(base_url() . 'ContactClasses/VirtualTraining');
        }
    }
    
    public function make_payment()
    {

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
			
			$pg_name = $this->input->post('pg_name');
            
            $cgst_rate      = $sgst_rate = $igst_rate = $tax_type = '';
            $cgst_amt       = $sgst_amt = $igst_amt = '';
            $cs_total       = $igst_total = '';
            $incenter_array = $new_center_array = array();
            //get regno  
            $this->db->where('regnumber', $this->session->userdata['mem_no']);
            $mem_regid = $this->master_model->getRecords('member_registration', '', 'regid');
            
            $regno        = $_SESSION['contact_classes_memberdata']['id'];
            $cource_state = $this->session->userdata['enduserinfo']['state'];
            
            if(count($mem_regid) == '0'){
                $mem_regid = $_SESSION['contact_classes_memberdata']['id'];//AS THERE IS NO MEMBER NUMBER TAKEN for AML KYC
            }
            
            //get all center 
            $center_query = $this->db->query(" SELECT DISTINCT (`center_code`) FROM `contact_classes_subject_master` WHERE `course_code` = '" . $this->session->userdata['enduserinfo']['cource_code'] . "'");
            // echo '<br>1.'.$this->db->last_query();
            $center_array = $center_query->result_array();
            $i            = 1;
            foreach ($center_array as $val) {
                
                //echo '**'; 
                $new_center_array[$i] = $val['center_code'];
                $i++;
            }
            
            $incenter_array = implode("','", $new_center_array);
            
            //get state accouding to the center
            $state_query = $this->db->query(" SELECT DISTINCT(`state_code`)  FROM `contact_classes_center_master` WHERE `center_code` IN ('" . $incenter_array . "')");
            // echo '<br>2.'.$this->db->last_query();
            $state_array = $state_query->result_array();
            
            foreach ($state_array as $val) {
                
                //echo '**'; 
                $new_state_array[$i] = $val['state_code'];
                $i++;
            }
            $instate_array = implode("','", $new_state_array);
            
            //get zone as per state
            $zone_query = $this->db->query(" SELECT `state_code`,`zone_code` FROM `zone_state_master` WHERE `state_code` IN ('" . $instate_array . "')");
            // echo '<br>3.'.$this->db->last_query();
            $zone_array = $zone_query->result_array();
            
            foreach ($zone_array as $val) {
                
                //echo '**'; 
                $compare_state_array[$i] = $val['state_code'];
                $i++;
            }
            
            //get cource state
            $center_state_query = $this->db->query(" SELECT `state_code` FROM `contact_classes_center_master` WHERE `center_code` IN ('" . $this->session->userdata['enduserinfo']['center_code'] . "')");
            // echo '<br>4.'.$this->db->last_query();
            $center_state_array = $center_state_query->result_array();
            
            
            if (in_array($center_state_array[0]['state_code'], $compare_state_array)) {
                $get_zone = $this->db->query(" SELECT `zone_code`,`state_code` FROM `zone_state_master` WHERE `state_code` IN ('" . $center_state_array[0]['state_code'] . "')");
                // echo '<br>5.'.$this->db->last_query();die;
                $get_zone_array = $get_zone->result_array();
                
                
                if (!empty($get_zone_array)) {
                    if ($get_zone_array[0]['zone_code'] == 'CO') {
                        //set zone code to session
                        $_SESSION['zone_code'] = $get_zone_array[0]['zone_code'];
                        
                        //intra condition
                        if ($center_state_array[0]['state_code'] == 'MAH' && $this->session->userdata['enduserinfo']['state'] == 'MAH') {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];

                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "')  ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            
                            if (!empty($fee_query)) {
                                //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $cgst_rate  = $this->config->item('contact_classes_cgst_rate');

                                    $sgst_rate  = $this->config->item('contact_classes_sgst_rate');
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[0]['cgst_amt'];
                                    $sgst_amt   = $fee_array[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total   = $fee_array[0]['cs_tot'];
                                    $tax_type   = 'Intra';
                                    
                                    $igst_amt = $igst_total = '';
                                    $amount   = $cs_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[1]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[1]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[1]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[2]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[2]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[2]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {
                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }
                            }
                        }
                        else {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            
                            if (!empty($fee_array)) {
                                //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $igst_rate = $this->config->item('contact_classes_igst_rate');
                                    $cgst      = $sgst = '';
                                    
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[0]['igst_amt'];
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[0]['igst_tot'];
                                    $tax_type   = 'Inter';
                                    
                                    $cs_total = '';
                                    $amount   = $igst_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[1]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[1]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                    
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[2]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[2]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                        }
                    } elseif ($get_zone_array[0]['zone_code'] == 'NZ') 
                    {
                        //set zone code to session
                        $_SESSION['zone_code'] = $get_zone_array[0]['zone_code'];
                        //intra condition
                        // echo $this->session->userdata['enduserinfo']['state'];die;
                        if ($center_state_array[0]['state_code'] == 'DEL' && $this->session->userdata['enduserinfo']['state'] == 'DEL') {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            // print_r($sub_array);die;
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC ");
            						
            					$fee_array = $fee_query->result_array();

                            if (!empty($fee_array)) 
                            {
                                //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];

                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $cgst_rate  = $this->config->item('contact_classes_cgst_rate');
                                    $sgst_rate  = $this->config->item('contact_classes_sgst_rate');
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[0]['cgst_amt'];
                                    $sgst_amt   = $fee_array[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total   = $fee_array[0]['cs_tot'];
                                    $tax_type   = 'Intra';
                                    
                                    $igst_amt = $igst_total = '';
                                    $amount   = $cs_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[1]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[1]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[1]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[2]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[2]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[2]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                        }
                        else {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            // print_r($sub_array);die;
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            // echo '<br>fee6.'.$this->db->last_query();die;
                            if (!empty($fee_array)) { //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                //subject1
                                $igst_rate          = $this->config->item('contact_classes_igst_rate');
                                if (isset($fee_array[0]['sub_code'])) {
                                    
                                    $cgst = $sgst = '';
                                    
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[0]['igst_amt'];
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[0]['igst_tot'];
                                    $tax_type   = 'Inter';
                                    
                                    $cs_total = '';
                                    $amount   = $igst_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[1]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[1]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[2]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[2]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                        }
                    } elseif ($get_zone_array[0]['zone_code'] == 'EZ') {
                        //set zone code to session
                        $_SESSION['zone_code'] = $get_zone_array[0]['zone_code'];
                        //intra condition
                        if ($center_state_array[0]['state_code'] == 'WES' && $this->session->userdata['enduserinfo']['state'] == 'WES') {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            
                            if (!empty($fee_array)) { //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                
                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $cgst_rate  = $this->config->item('contact_classes_cgst_rate');
                                    $sgst_rate  = $this->config->item('contact_classes_sgst_rate');
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[0]['cgst_amt'];
                                    $sgst_amt   = $fee_array[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total   = $fee_array[0]['cs_tot'];
                                    $tax_type   = 'Intra';
                                    
                                    $igst_amt = $igst_total = '';
                                    $amount   = $cs_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[1]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[1]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[1]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[2]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[2]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[2]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                    
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                        }//inter condition
                        else {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            
                            ///set the subject wise fees 
                            
                            if (!empty($fee_array)) {
                                //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt']; //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $igst_rate = $this->config->item('contact_classes_igst_rate');
                                    $cgst      = $sgst = '';
                                    
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[0]['igst_amt'];
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[0]['igst_tot'];
                                    $tax_type   = 'Inter';
                                    
                                    $cs_total = '';
                                    $amount   = $igst_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[1]['igst_amt'] + $igst_amt;
                                    
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[1]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[2]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[2]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                            
                        }
                        
                    } elseif ($get_zone_array[0]['zone_code'] == 'SZ') {
                        //set zone code to session
                        $_SESSION['zone_code'] = $get_zone_array[0]['zone_code'];
                        //intra condition
                        if ($center_state_array[0]['state_code'] == 'TAM' && $this->session->userdata['enduserinfo']['state'] == 'TAM') {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "') ORDER BY `sub_code` DESC");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            
                            if (!empty($fee_array)) {
                                //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $cgst_rate  = $this->config->item('contact_classes_cgst_rate');
                                    $sgst_rate  = $this->config->item('contact_classes_sgst_rate');
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[0]['cgst_amt'];
                                    $sgst_amt   = $fee_array[0]['sgst_amt'];
                                    //set an total amount
                                    $cs_total   = $fee_array[0]['cs_tot'];
                                    $tax_type   = 'Intra';
                                    
                                    $igst_amt = $igst_total = '';
                                    $amount   = $cs_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[1]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[1]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[1]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $cgst_amt   = $fee_array[2]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt   = $fee_array[2]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total   = $fee_array[2]['cs_tot'] + $cs_total;
                                    $amount     = $cs_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                        }
                        else {
                            //set state  code to session
                            $_SESSION['center_state_code'] = $get_zone_array[0]['state_code'];
                            
                            foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                                $new_sub_array[$i] = $val;
                                $i++;
                            }
                            $sub_array = implode("','", $new_sub_array);
                            
                            //get fee
                            $fee_query = $this->db->query(" SELECT * FROM `contact_classes_fee_master` WHERE `course_code` = " . $this->session->userdata['enduserinfo']['cource_code'] . " AND `exempt`='NE' AND `sub_code` IN ('" . $sub_array . "')  ORDER BY `sub_code` DESC ");
                            $fee_array = $fee_query->result_array();
                            ///set the subject wise fees 
                            
                            if (!empty($fee_array)) { //set state  code to session
                                $_SESSION['exempt'] = $fee_array[0]['exempt'];
                                //subject1
                                if (isset($fee_array[0]['sub_code'])) {
                                    $igst_rate = $this->config->item('contact_classes_igst_rate');
                                    $cgst      = $sgst = '';
                                    
                                    $fee1       = 0;
                                    $fee1       = $fee_array[0]['fee'];
                                    $fee_amount = $fee1;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[0]['igst_amt'];
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[0]['igst_tot'];
                                    $tax_type   = 'Inter';
                                    
                                    $cs_total = '';
                                    $amount   = $igst_total;
                                }
                                
                                //subject2
                                if (isset($fee_array[1]['sub_code'])) {
                                    
                                    $fee2       = 0;
                                    $fee2       = $fee_array[1]['fee'];
                                    $fee_amount = $fee1 + $fee2;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[1]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[1]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }
                                
                                //subject3
                                if (isset($fee_array[2]['sub_code'])) {
                                    
                                    $fee3       = 0;
                                    $fee3       = $fee_array[2]['fee'];
                                    $fee_amount = $fee1 + $fee2 + $fee3;
                                    //set an amount as per rate
                                    $igst_amt   = $fee_array[2]['igst_amt'] + $igst_amt;
                                    $cgst_amt   = '';
                                    $sgst_amt   = '';
                                    //set an total amount
                                    $igst_total = $fee_array[2]['igst_tot'] + $igst_total;
                                    $amount     = $igst_total;
                                }

                                //subject4
                                if (isset($fee_array[3]['sub_code'])) {

                                    $fee4       = 0;
                                    $fee4       = $fee_array[3]['fee'];
                                    $fee_amount = $fee4 + $fee3 + $fee2 + $fee1;
                                    //set an amount as per rate
                                    $cgst_amt = $fee_array[3]['cgst_amt'] + $cgst_amt;
                                    $sgst_amt = $fee_array[3]['sgst_amt'] + $sgst_amt;
                                    //set an total amount
                                    $cs_total = $fee_array[3]['cs_tot'] + $cs_total;
                                    $amount   = $cs_total;
                                }
                            }
                            
                        }
                    }
                }
                #-------Set invoice data to session----------#		
                //get state accouding to the center
                $state_query = $this->db->query(" SELECT `state_code` FROM `contact_classes_center_master` WHERE `center_code`=" . $this->session->userdata['enduserinfo']['center_code'] . "");
                $state_array = $state_query->result_array();
                
                $get_zone       = $this->db->query(" SELECT `zone_code`,`state_code` FROM `zone_state_master` WHERE `state_code`= '" . $state_array[0]['state_code'] . "'");
                $get_zone_array = $get_zone->result_array();
                
                
                $get_zone_add   = $this->db->query(" SELECT `zone_address1`,`zone_address2`,`zone_address3`,`zone_address4`,`gstin_no`,`state_code`  FROM `zone_master` WHERE `zone_code`= '" . $get_zone_array[0]['zone_code'] . "'");
                $zone_add_array = $get_zone_add->result_array();
                
                
                //get zone state 
                $zone_state_query = $this->db->query(" SELECT `state_code`,`state_name`,`state_no` FROM `zone_state_master` WHERE `state_no`= '" . $zone_add_array[0]['state_code'] . "'");
                $zone_state_array = $zone_state_query->result_array();
                
                //set a fee array to SESSion
                
                
                if (isset($fee1)) {
                    $fee1 = $fee1;
                } else {
                    $fee1 = 0;
                }
                if (isset($fee2)) {
                    $fee2 = $fee2;
                } else {
                    $fee2 = 0;
                }
                if (isset($fee3)) {
                    $fee3 = $fee3;
                } else {
                    $fee3 = 0;
                }
                $user_Cource_data = array(
                    'subject' => $this->session->userdata['enduserinfo']['subjects'],
                    'zone_code' => $get_zone_array[0]['zone_code'],
                    'zone_address1' => $zone_add_array[0]['zone_address1'],
                    'zone_address2' => $zone_add_array[0]['zone_address2'],
                    'zone_address3' => $zone_add_array[0]['zone_address3'],
                    'zone_address4' => $zone_add_array[0]['zone_address4'],
                    'zone_gstin_no' => $zone_add_array[0]['gstin_no'],
                    'zone_state_code' => $zone_state_array[0]['state_no'],
                    'zone_state_name' => $zone_state_array[0]['state_name'],
                    'zone_state' => $zone_state_array[0]['state_code'],
                    'cource_code' => $this->session->userdata['enduserinfo']['cource_code'],
                    'center_code' => $this->session->userdata['enduserinfo']['center_code'],
                    
                    'sub_fee1' => $fee1,
                    'sub_fee2' => $fee2,
                    'sub_fee3' => $fee3,
                    
                    'cgst_rate' => $cgst_rate,


                    'sgst_rate' => $sgst_rate,
                    
                    'cgst_amt' => $cgst_amt,
                    'sgst_amt' => $sgst_amt,
                    
                    'cs_total' => $cs_total,
                    'igst_total' => $igst_total
                );
                
				// echo '<pre>'; print_r($user_Cource_data);
				// exit;
                $this->session->set_userdata('invoice_info', $user_Cource_data);

                //new array to set contact class id as member no as we are not generating any member no for aml kyc by pooja mane:2024-0704
                if(empty($this->session->userdata['mem_no']) OR $this->session->userdata['mem_no'] == '')
                {
                    $this->session->userdata['mem_no'] = $_SESSION['contact_classes_memberdata']['id'];
                }
				
                #-----------end Set invoice data to session----------#		
                $insert_data = array(
                    'exam_code' => $this->session->userdata['enduserinfo']['cource_code'],//exam code added for identification
                    'member_regnumber' => $this->session->userdata['mem_no'],
                    'gateway' => "sbiepay",
                    'amount' => $amount,
                    'date' => date('Y-m-d H:i:s'),
                    'ref_id' => $_SESSION['contact_classes_memberdata']['id'],
                    'description' => "Contact Classes",
                    'pay_type' => 11,
                    'status' => 2,
                    'pg_flag' => 'iibfcc'
                );
                
                // echo '<pre>'; print_r($insert_data);die;
                $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
                $MerchantOrderNo = sbi_exam_order_id($pt_id);
                $custom_field    = $_SESSION['contact_classes_memberdata']['id'] . "^iibfexam^iibfcc^" . $MerchantOrderNo;
                $custom_field_billdesk    = $_SESSION['contact_classes_memberdata']['id'] . "-iibfexam-iibfcc-" . $MerchantOrderNo;
                
                $update_data = array(
                    'receipt_no' => $MerchantOrderNo,
                    'pg_other_details' => $custom_field
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'id' => $pt_id
                ));
                
                //get exam_prd
                
                $prd = $this->master_model->getRecords('contact_classes_cource_activation_master', array(
                    'course_code' => $this->session->userdata['enduserinfo']['cource_code'],
                    'to_date >=' => date('Y-m-d')
                ));
                
                if (!empty($prd)) {
                    $course_prd = $prd[0]['exam_prd'];
                }
                //get center name
                $center_name = $this->master_model->getRecords('contact_classes_center_master', array(
                    'center_code' => $this->session->userdata['enduserinfo']['center_code']
                ));
                if (!empty($center_name)) {
                    $center_name = $center_name[0]['center_name'];
                }
                //insert invoice details
                $getstate             = $this->master_model->getRecords('state_master', array(
                    'state_code' => $this->session->userdata['enduserinfo']['state'],
                    'state_delete' => '0'
                ));
                $invoice_insert_array = array(
                    'pay_txn_id' => $pt_id,
                    'exam_period' => $_SESSION['period'],
                    'center_name' => $center_name,
                    'receipt_no' => $MerchantOrderNo,
                    //  'gstin_no'=> $this->session->userdata['invoice_info']['zone_gstin_no'],
                    'exam_code' => $this->session->userdata['enduserinfo']['cource_code'],
                    'center_code' => $this->session->userdata['enduserinfo']['center_code'],
                    'member_no' => $this->session->userdata['mem_no'],
                    'state_of_center' => $_SESSION['center_state_code'],
                    'app_type' => 'E',
                    'service_code' => $this->config->item('contact_classes_service_code'),
                    'qty' => '1',
                    'state_code' => $getstate[0]['state_no'],
                    'state_name' => $getstate[0]['state_name'],
                    'tax_type' => $tax_type,
                    'fee_amt' => $fee_amount,
                    'cgst_rate' => $cgst_rate,
                    'cgst_amt' => $cgst_amt,
                    'sgst_rate' => $sgst_rate,
                    'sgst_amt' => $sgst_amt,
                    'igst_rate' => $igst_rate,
                    'igst_amt' => $igst_amt,
                    'cs_total' => $cs_total,
                    'igst_total' => $igst_total,
                    'gstin_no' => '',
                    'exempt' => $_SESSION['exempt'],
                    'created_on' => date('Y-m-d H:i:s')
                );
                // echo '<pre>'; print_r($invoice_insert_array);die;
                
                $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
				$invloice_insert_query=$this->db->last_query();
				$log_message = serialize($invloice_insert_query);
						$titlt = "Contact Class invoice inserton".$this->session->userdata['mem_no'];
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);

			             //get program name - 2021-03-16 - chaitali
                        $get_cource_query = $this->db->query(" SELECT `course_name`  FROM `contact_classes_cource_master` WHERE `course_code`= " . $this->session->userdata['invoice_info']['cource_code'] . "");
                        $get_cource       = $get_cource_query->result_array();
                        
                        
                        //get venue code
                        $get_venue_query = $this->db->query(" SELECT `venue_code`,`venue_name`  FROM `contact_classes_venue_master` WHERE `center_code`= " . $this->session->userdata['invoice_info']['center_code'] . "");
                        $get_venue       = $get_venue_query->result_array();
                        
                        //get center name
                        
                        $get_center_query = $this->db->query(" SELECT `center_name`,`state_code` FROM `contact_classes_center_master` WHERE `center_code`= " . $this->session->userdata['invoice_info']['center_code'] . "");
                        $get_center       = $get_center_query->result_array();
                        
                        
                        //insert subjects data in table.
						 if(count($this->session->userdata['enduserinfo']['subjects'] ) > 0)
						{
                        foreach ($this->session->userdata['enduserinfo']['subjects'] as $val) {
                            //get subject name
                            
                            $get_sub_query = $this->db->query(" SELECT `sub_code`,`sub_name`  FROM `contact_classes_subject_master` WHERE `sub_code`= " . $val . "");
                            $get_sub       = $get_sub_query->result_array();
                            
                            $invoice_insert_array = array(
                                
                                'contact_classes_regid' => $this->session->userdata['contact_classes_memberdata']['id'],
                                'member_no' => $this->session->userdata['mem_no'],
                                'program_code' => $this->session->userdata['enduserinfo']['cource_code'],
                                'program_prd' =>$_SESSION['period'],
                                'program_name' => $get_cource[0]['course_name'],
                                'sub_code' => $get_sub[0]['sub_code'],
                                'sub_name' => $get_sub[0]['sub_name'],
                                'zone_code' => $_SESSION['zone_code'],
                                'state_code' => $get_center[0]['state_code'],
                                'center_code' => $this->session->userdata['enduserinfo']['center_code'],
                                'center_name' => $get_center[0]['center_name'],
                                'venue_code' => $get_venue[0]['venue_code'],
                                'venue_name' => $get_venue[0]['venue_name'],
                                'createdon' => date('Y-m-d H:i:s'),
                                'modify_date' => date('Y-m-d H:i:s')
                            );
                            $inser_id = $this->master_model->insertRecord('contact_classes_Subject_registration', $invoice_insert_array);
							// echo '<pre>'; print_r($invoice_insert_array);die;
							
						$log_message = serialize($invoice_insert_array);
						$titlt = "Contact Class successful registration".$this->session->userdata['mem_no'];
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);
                        }

						}else
						{
						$log_message = serialize($this->session->userdata['enduserinfo']['subjects']);
						$titlt = "Contact Class else condition".$this->session->userdata['mem_no'];
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);
						}
						
                if ($pg_name == 'sbi') 
                { exit;
                        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        				$key = $this->config->item('sbi_m_key');
        				$merchIdVal = $this->config->item('sbi_merchIdVal');
        				$AggregatorId = $this->config->item('sbi_AggregatorId');
        				
        				$pg_success_url = base_url()."ContactClasses/sbitranssuccess";
        				$pg_fail_url    = base_url()."ContactClasses/sbitransfail";
                        //exit;
                        $MerchantCustomerID  = $inser_id;
                        $data["pg_form_url"] = $this->config->item('sbi_pg_form_url');
                        $data["merchIdVal"]  = $merchIdVal;
                        $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                        $aes                 = new CryptAES();
                        $aes->set_key(base64_decode($key));
                        $aes->require_pkcs5();
                        $EncryptTrans         = $aes->encrypt($EncryptTrans);
                        $data["EncryptTrans"] = $EncryptTrans;
                        $this->load->view('pg_sbi_form', $data);
                } 
    			elseif ($pg_name == 'billdesk') 
    			{
					$update_payment_data = array('gateway' =>'billdesk');
					$this->master_model->updateRecord('payment_transaction',$update_payment_data,array('id'=>$pt_id));
				
                    $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'ContactClasses/handle_billdesk_response', '', '', '', $custom_field_billdesk);
            
				   //echo '<pre>'; print_r($billdesk_res); echo '</pre>'; //exit;						
				
                    if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                        $data['bdorderid'] = $billdesk_res['bdorderid'];
                        $data['token']     = $billdesk_res['token'];
    					$data['responseXHRUrl'] = $billdesk_res['responseXHRUrl']; 
    					$data['returnUrl'] = $billdesk_res['returnUrl']; 
                        $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                    }else
                    {
                        $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                        redirect(base_url() . 'ContactClasses');
                    }
            }               
			   
        }
            
        } else {
            $data['show_billdesk_option_flag'] = 1;
						$this->load->view('pg_sbi/make_payment_page', $data);
        }
    }
   
    public function handle_billdesk_response()
    { 
        /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); */
        $selected_invoice_id = $attachpath = $invoiceNumber = '';
        //$selected_invoice_id = $this->session->userdata['memberdata']['regno']; // Seleted Invoice Id
      
        
        if (isset($_REQUEST['transaction_response'])) 
				{            
            $response_encode = $_REQUEST['transaction_response'];
            $bd_response     = $this->billdesk_pg_model->verify_res($response_encode);
       
            $responsedata           = $bd_response['payload'];
            $attachpath             = $invoiceNumber             = '';
            $MerchantOrderNo        = $responsedata['orderid'];
            $transaction_no         = $responsedata['transactionid'];
            $transaction_error_type = $responsedata['transaction_error_type'];
            $transaction_error_desc = $responsedata['transaction_error_desc'];
            $bankid                 = $responsedata['bankid'];
            $txn_process_type       = $responsedata['txn_process_type'];
            $merchIdVal             = $responsedata['mercid'];
            $Bank_Code              = $responsedata['bankid'];
            $encData                = $_REQUEST['transaction_response'];
            $auth_status            = $responsedata['auth_status'];
        
            $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id,transaction_no,date,amount', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'ContactClasses');
            }
            $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
            $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
            $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
            
            
            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
			
            if($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2)
            {
            	
                //Query to get Payment details
                $update_data  = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             =>'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                /* Transaction Log */
                $payment_update_query=$this->db->last_query();
                            $log_message = serialize($payment_update_query);
                            $titlt = "Contact Class payment update1".$member_regnumber;
                            $logs = array(
                            'title' =>$titlt,
                            'description' =>$log_message);
                            $this->master_model->insertRecord('contactclass_logs', $logs,true);

                        $update_bank_data = array(
                            'pay_status' => 1,
                            'modify_date' => date("Y-m-d H:i:s")
                        );
                        
                        $this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array(
                            'contact_classes_id ' =>$new_invoice_id
                        ));
						
						$contact_classes_registration_query=$this->db->last_query();
                        $log_message = serialize($contact_classes_registration_query);
						$titlt = "contact_classes_registration".$member_regnumber;
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);
						
						
						$update_sub_data = array(
                            'remark' => 1,
                            'modify_date' => date("Y-m-d H:i:s")
                        );
                        
                        $this->master_model->updateRecord('contact_classes_Subject_registration', $update_sub_data, array(
                            'contact_classes_regid ' =>$new_invoice_id
                        ));
						$payment_info = $this->master_model->getRecords('payment_transaction', array(
                            'receipt_no' => $MerchantOrderNo,
                            'status' => 1
                        ));

                        $member       = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE contact_classes_id IN (
																SELECT MAX(contact_classes_id)
																FROM contact_classes_registration
																GROUP BY member_no
															) and pay_status = 1 AND member_no=" . $member_regnumber);
                        $memtype      = $member->result_array();
                        //get center name
                        
                        $this->db->where('center_code', $memtype[0]['center_code']);
                        $center_info = $this->master_model->getRecords('contact_classes_center_master');
                        
                          $user_info  = $this->master_model->getRecords('contact_classes_Subject_registration', array(
                            'member_no' =>$member_regnumber,'center_code'=>$memtype[0]['center_code'],'contact_classes_regid'=>$memtype[0]['contact_classes_id']
                        ));
						
						$sub_array = array();
									foreach($user_info as $user_info_rec)
									{
										array_push($sub_array ,$user_info_rec['sub_code']);
									}

						//email
						 $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'contactclasses'
                        ));
                        
                        
                        $selfstr1  = str_replace("#regnumber#", "" . $member_regnumber . "", $emailerstr[0]['emailer_text']);
                        $selfstr2  = str_replace("#program_name#", "" . $user_info[0]['program_name'] . "", $selfstr1);
                        $selfstr3  = str_replace("#center_name#", "" . $center_info[0]['center_name'] . "", $selfstr2);
                        $selfstr4  = str_replace("#venue_name#", "" . $user_info[0]['venue_name'] . "", $selfstr3);
                        //$selfstr5 = str_replace("#start_date#", "".$reg_info[0]['start_date']."",  $selfstr4);
                        //$selfstr6 = str_replace("#end_date#", "".$reg_info[0]['end_date']."",  $selfstr5);
                        $selfstr7  = str_replace("#name#", "" . $memtype[0]['namesub'] . " " . $memtype[0]['firstname'] . " " . $memtype[0]['middlename'] . " " . $memtype[0]['lastname'], $selfstr4);
                        $selfstr8  = str_replace("#address1#", "" . $memtype[0]['address1'] . "", $selfstr7);
                        $selfstr9  = str_replace("#address2#", "" . $memtype[0]['address2'] . "", $selfstr8);
                        $selfstr10 = str_replace("#address3#", "" . $memtype[0]['address3'] . "", $selfstr9);
                        $selfstr11 = str_replace("#address4#", "" . $memtype[0]['address4'] . "", $selfstr10);
                        
                        $selfstr12 = str_replace("#district#", "" . $memtype[0]['district'] . "", $selfstr11);
                        $selfstr13 = str_replace("#city#", "" . $memtype[0]['city'] . "", $selfstr12);
                        $selfstr14 = str_replace("#state#", "" . $memtype[0]['state'] . "", $selfstr13);
                        $selfstr15 = str_replace("#pincode#", "" . $memtype[0]['pincode'] . "", $selfstr14);
                        $selfstr19 = str_replace("#email#", "" . $memtype[0]['email'] . "", $selfstr15);
                        $selfstr20 = str_replace("#mobile#", "" . $memtype[0]['mobile'] . "", $selfstr19);
                        
                        $selfstr29     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $selfstr20);
                        $selfstr30     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $selfstr29);
                        $selfstr31     = str_replace("#STATUS#", "Transaction Successful", $selfstr30);
                        $final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $selfstr31);
                        
                        
                        //	$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
                        //	$final_str= str_replace("#DATE#",  $emailerstr[0]['emailer_text']);
                          $info_arr = array('to'=>$memtype[0]['email'],
							'from' => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'],
                            'message' => $final_selfstr
                        );
                        if ($this->session->userdata['zone_code'] == 'NZ') {
                            $client_arr = array(
                            'to'=>'sanjay@iibf.org.in,se.pdcnz1@iibf.org.in,mkbhatia@iibf.org.in,iibfdevp@esds.co.in,je.pdcnz2@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        } elseif ($this->session->userdata['zone_code'] == 'EZ') {
                            $client_arr = array(
                                'to'=>'iibfez@iibf.org.in',
                             //  'to' => 'kyciibf@gmail.com',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr 
                            );
                        }elseif ($this->session->userdata['zone_code'] == 'SZ') {
                            $client_arr = array(
                             //	'to'=>'kyciibf@gmail.com',
                            //  'to'=>'vratesh@iibf.org.in,sriram@iibf.org.in',
                            	 'to'=>'sriram@iibf.org.in,priya@iibf.org.in,govindarajanr@iibf.org.in,iibfsz@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        }
						elseif ($this->session->userdata['zone_code'] == 'CO') {
                            $client_arr = array(
                             //	'to'=>'kyciibf@gmail.com', 
                             'to'=>'training@iibf.org.in,vratesh@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        }
                        // genarate invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $get_user_regnum_info[0]['id']
                        ));
                        $session_id        = $new_invoice_id;
                        if (count($getinvoice_number) > 0) {
							
                              
                            if ($this->session->userdata['invoice_info']['zone_code'] == 'CO') {
                                
                               $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_CO') . $invoiceNumber;
                                }
                                
                            }elseif ($this->session->userdata['invoice_info']['zone_code'] == 'NZ') {
                                
                               $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_NZ') . $invoiceNumber;
                                }
                               
                            }elseif ($this->session->userdata['invoice_info']['zone_code'] == 'SZ') {
                                
                               $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_SZ') . $invoiceNumber;
                                }
                                
                            }elseif ($this->session->userdata['invoice_info']['zone_code'] == 'EZ') {
                                
                               $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_EZ') . $invoiceNumber;
                                }
                                
                            } 
                            $update_data22 = array(
                                'invoice_no' => $invoiceNumber,
                                'transaction_no' => $transaction_no,
                                'date_of_invoice' => date('Y-m-d H:i:s'),
                                'modified_on' => date('Y-m-d H:i:s')
                            );
                            $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                            $this->master_model->updateRecord('exam_invoice', $update_data22, array(
                                'receipt_no' => $MerchantOrderNo
                            ));
							$invloice_update_query=$this->db->last_query();
							$log_message = serialize($invloice_update_query);
							$titlt = "Contact Class invoice update".$this->session->userdata['mem_no'];
							$logs = array(
							'title' =>$titlt,
							'description' =>$log_message);
							$this->master_model->insertRecord('contactclass_logs', $logs,true);
							
                            
                            //$attachpath = genarate_contact_classes_invoice($getinvoice_number[0]['invoice_id'], $session_id);
                           
						   $attachpath = genarate_contact_classes_invoice_cs2s($getinvoice_number[0]['invoice_id'], $session_id , $user_info[0]['program_code'],$sub_array,$user_info[0]['zone_code']);

                        }
						 //Manage Log
                        /* $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]); */
                        // echo $attachpath;
                        // print_r($attachpath);die;
                        if ($attachpath != '') {
                            
                            //to user
                            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                //to client
                                $this->Emailsending->mailsend_attch($client_arr, $attachpath);
                                /*log activity for genarate subscription number*/
                                $log_title   = "Email send sucessfully for id  :" . $this->session->userdata['contact_classes_memberdata']['id'] . "";
                                $log_message = serialize($info_arr);
                                storedUserActivity($log_title, $log_message, '', '');
                                /* Close User Log Actitives */
                                
                                redirect(base_url() . 'ContactClasses/acknowledge/');
                            } else {
                                redirect(base_url() . 'ContactClasses/acknowledge/');
                            }
                        } else {
                            redirect(base_url() . 'ContactClasses/acknowledge/');
                        }
				
				
				
				
				
				
			} 
            elseif ($auth_status == "0002")
            {
                $update_data33 = array(
                    'transaction_no' => $transaction_no,
                    'status' => 2,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code' => '0002',
                    'bankcode' => $bankid,
                    'paymode' => $txn_process_type,
                    'callback' => 'B2B'
                );
                $this->master_model->updateRecord('payment_transaction', $update_data33, array(
                    'receipt_no' => $MerchantOrderNo
                ));
                
                
                $update_bank_data = array(
                    'pay_status' => 0,
                    'modify_date' => date("Y-m-d H:i:s")
                );
                
                
                $this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array(
                    'contact_classes_id' => $new_invoice_id));
                
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response,$transaction_error_type); 
				
                $this->session->set_flashdata('flsh_msg', 'Transaction inprocess...!');
                redirect(base_url() . 'ContactClasses');

            }
			else //if ($transaction_error_type == 'payment_authorization_error') 
			{
				 $update_data33 = array(
                    'transaction_no' => $transaction_no,
                    'status' => 0,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code' => '0300',
                    'bankcode' => $bankid,
                    'paymode' => $txn_process_type,
                    'callback' => 'B2B'
                );
                $this->master_model->updateRecord('payment_transaction', $update_data33, array(
                    'receipt_no' => $MerchantOrderNo
                ));
                
                
                $update_bank_data = array(
                    'pay_status' => 0,
                    'modify_date' => date("Y-m-d H:i:s")
                );
                
                
                $this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array(
                    'contact_classes_id' => $new_invoice_id));
                
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response,$transaction_error_type); 
				
                $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                redirect(base_url() . 'ContactClasses');
            } 
        } else {
            die("Please try again...");
        }
    }
   
    public function sbitranssuccess()
    {
        
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData      = $aes->decrypt($_REQUEST['encData']);
            $responsedata = explode("|", $encData);
            
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
            //Sbi B2B callback check sbi payment status with MerchantOrderNo 
            $q_details = sbiqueryapi($MerchantOrderNo);
            
            
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") {
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no' => $MerchantOrderNo
                    ), 'ref_id,status,id');
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $reg_id = $get_user_regnum_info[0]['ref_id'];
                        $this->db->trans_start();
                        $update_data = array(
                            'transaction_no' => $transaction_no,
                            'status' => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code' => '0300',
                            'bankcode' => $responsedata[8],
                            'paymode' => $responsedata[5],
                            'callback' => 'B2B'
                        );
                        
                        
                        $this->master_model->updateRecord('payment_transaction', $update_data, array(
                            'receipt_no' => $MerchantOrderNo
                        ));
						$payment_update_query=$this->db->last_query();
                        $log_message = serialize($payment_update_query);
						$titlt = "Contact Class payment update1".$this->session->userdata['mem_no'];
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);
					    
                        $update_bank_data = array(
                            'pay_status' => 1,
                            'modify_date' => date("Y-m-d H:i:s")
                        );
                        
                        $this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array(
                            'contact_classes_id ' => $this->session->userdata['contact_classes_memberdata']['id']
                        ));
						
						$contact_classes_registration_query=$this->db->last_query();
                        $log_message = serialize($contact_classes_registration_query);
						$titlt = "contact_classes_registration".$this->session->userdata['mem_no'];
						$logs = array(
						'title' =>$titlt,
						'description' =>$log_message);
						$this->master_model->insertRecord('contactclass_logs', $logs,true);
						
						
						$update_sub_data = array(
                            'remark' => 1,
                            'modify_date' => date("Y-m-d H:i:s")
                        );
                        
                        $this->master_model->updateRecord('contact_classes_Subject_registration', $update_sub_data, array(
                            'contact_classes_regid ' => $this->session->userdata['contact_classes_memberdata']['id']
                        ));
						
						
                        $payment_info = $this->master_model->getRecords('payment_transaction', array(
                            'receipt_no' => $MerchantOrderNo,
                            'status' => 1
                        ));
                        $member       = $this->db->query("SELECT *
															FROM contact_classes_registration
															WHERE contact_classes_id IN (
																SELECT MAX(contact_classes_id)
																FROM contact_classes_registration
																GROUP BY member_no
															) and pay_status = 1 AND member_no=" . $this->session->userdata['mem_no']);
                        $memtype      = $member->result_array();
                        //get center name
                        
                        $this->db->where('center_code', $memtype[0]['center_code']);
                        $center_info = $this->master_model->getRecords('contact_classes_center_master');
                        
                          $user_info  = $this->master_model->getRecords('contact_classes_Subject_registration', array(
                            'member_no' => $this->session->userdata['mem_no'],'center_code'=>$memtype[0]['center_code'],'contact_classes_regid'=>$memtype[0]['contact_classes_id']
                        ));
                        // email to user
                        /*$emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'recommender_recommend_email'));
                        $final_str = str_replace("#MSG#", "".$msg."",  $emailerstr[0]['emailer_text']);
                        //$final_str= str_replace("#password#", "".$decpass."",  $newstring);
                        */
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'contactclasses'
                        ));
                        
                        
                        $selfstr1  = str_replace("#regnumber#", "" . $this->session->userdata['mem_no'] . "", $emailerstr[0]['emailer_text']);
                        $selfstr2  = str_replace("#program_name#", "" . $user_info[0]['program_name'] . "", $selfstr1);
                        $selfstr3  = str_replace("#center_name#", "" . $center_info[0]['center_name'] . "", $selfstr2);
                        $selfstr4  = str_replace("#venue_name#", "" . $user_info[0]['venue_name'] . "", $selfstr3);
                        //$selfstr5 = str_replace("#start_date#", "".$reg_info[0]['start_date']."",  $selfstr4);
                        //$selfstr6 = str_replace("#end_date#", "".$reg_info[0]['end_date']."",  $selfstr5);
                        $selfstr7  = str_replace("#name#", "" . $memtype[0]['namesub'] . " " . $memtype[0]['firstname'] . " " . $memtype[0]['middlename'] . " " . $memtype[0]['lastname'], $selfstr4);
                        $selfstr8  = str_replace("#address1#", "" . $memtype[0]['address1'] . "", $selfstr7);
                        $selfstr9  = str_replace("#address2#", "" . $memtype[0]['address2'] . "", $selfstr8);
                        $selfstr10 = str_replace("#address3#", "" . $memtype[0]['address3'] . "", $selfstr9);
                        $selfstr11 = str_replace("#address4#", "" . $memtype[0]['address4'] . "", $selfstr10);
                        
                        $selfstr12 = str_replace("#district#", "" . $memtype[0]['district'] . "", $selfstr11);
                        $selfstr13 = str_replace("#city#", "" . $memtype[0]['city'] . "", $selfstr12);
                        $selfstr14 = str_replace("#state#", "" . $memtype[0]['state'] . "", $selfstr13);
                        $selfstr15 = str_replace("#pincode#", "" . $memtype[0]['pincode'] . "", $selfstr14);
                        $selfstr19 = str_replace("#email#", "" . $memtype[0]['email'] . "", $selfstr15);
                        $selfstr20 = str_replace("#mobile#", "" . $memtype[0]['mobile'] . "", $selfstr19);
                        
                        $selfstr29     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $selfstr20);
                        $selfstr30     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $selfstr29);
                        $selfstr31     = str_replace("#STATUS#", "Transaction Successful", $selfstr30);
                        $final_selfstr = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $selfstr31);
                        
                        
                            //	$newstring1 = str_replace("#NO#", "". $subscription_number."",  $emailerstr[0]['emailer_text']);
                            //	$final_str= str_replace("#DATE#",  $emailerstr[0]['emailer_text']);
                          $info_arr = array('to'=>$memtype[0]['email'],
                            //  'to' => 'kyciibf@gmail.com',
                            'from' => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'],
                            'message' => $final_selfstr
                        );
                        if ($this->session->userdata['zone_code'] == 'NZ') {
                            $client_arr = array(
                            // 'to'=>'kyciibf@gmail.com',
                               'to'=>'sanjay@iibf.org.in,mkbhatia@iibf.org.in,iibfdevp@esds.co.in,se.pdcnz1@iibf.org.in,head-pdcnz@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        } elseif ($this->session->userdata['zone_code'] == 'EZ') {
                            $client_arr = array(
                                'to'=>'iibfez@iibf.org.in',
                             //  'to' => 'kyciibf@gmail.com',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr 
                            );
                        }elseif ($this->session->userdata['zone_code'] == 'SZ') {
                            $client_arr = array(
                             //	'to'=>'kyciibf@gmail.com',
                            //  'to'=>'vratesh@iibf.org.in,sriram@iibf.org.in',
                            	 'to'=>'sriram@iibf.org.in,priya@iibf.org.in,govindarajanr@iibf.org.in,iibfsz@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        }
						elseif ($this->session->userdata['zone_code'] == 'CO') {
                            $client_arr = array(
                             //	'to'=>'kyciibf@gmail.com', 
                             'to'=>'training@iibf.org.in,vratesh@iibf.org.in',
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_selfstr
                            );
                        }
                        // genarate invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $get_user_regnum_info[0]['id']
                        ));
                        $session_id        = $this->session->userdata['contact_classes_memberdata']['id'];
                        if (count($getinvoice_number) > 0) {
                            if ($this->session->userdata['invoice_info']['zone_code'] == 'CO') {
                                /*if($this->session->userdata['invoice_info']['zone_code']=='JAM')
                                {
                                $invoiceNumber = generate_contact_classes_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if($invoiceNumber)
                                {
                                $invoiceNumber=$this->config->item('Contact_classes_invoice_no_prefix_jammu_CO').$invoiceNumber;
                                }
                                }
                                else
                                {*/
                                $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_CO') . $invoiceNumber;
                                }
                                /*}*/
                            } elseif ($this->session->userdata['invoice_info']['zone_code'] == 'NZ') {
                                /*if($this->session->userdata['invoice_info']['zone_code']=='JAM')
                                {
                                $invoiceNumber = generate_contact_classes_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if($invoiceNumber)
                                {
                                $invoiceNumber=$this->config->item('Contact_classes_invoice_no_prefix_jammu_NZ').$invoiceNumber;
                                }
                                }
                                else
                                {*/
                                $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_NZ') . $invoiceNumber;
                                }
                                /*}*/
                            } elseif ($this->session->userdata['invoice_info']['zone_code'] == 'SZ') {
                                /*if($this->session->userdata['invoice_info']['zone_code']=='JAM')
                                {
                                $invoiceNumber = generate_contact_classes_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if($invoiceNumber)
                                {
                                $invoiceNumber=$this->config->item('Contact_classes_invoice_no_prefix_jammu_SZ').$invoiceNumber;
                                }
                                }
                                else
                                {*/
                                $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_SZ') . $invoiceNumber;
                                }
                                /*}*/
                            } elseif ($this->session->userdata['invoice_info']['zone_code'] == 'EZ') {
                                /*if($this->session->userdata['invoice_info']['zone_code']=='JAM')
                                {
                                $invoiceNumber = generate_contact_classes_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if($invoiceNumber)
                                {
                                $invoiceNumber=$this->config->item('Contact_classes_invoice_no_prefix_jammu_EZ').$invoiceNumber;
                                }
                                }
                                else
                                {*/
                                $invoiceNumber = generate_contact_classes_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('Contact_classes_invoice_no_prefix_EZ') . $invoiceNumber;
                                }
                                /*}*/
                            }
                            $update_data = array(
                                'invoice_no' => $invoiceNumber,
                                'transaction_no' => $transaction_no,
                                'date_of_invoice' => date('Y-m-d H:i:s'),
                                'modified_on' => date('Y-m-d H:i:s')
                            );
                            $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                            $this->master_model->updateRecord('exam_invoice', $update_data, array(
                                'receipt_no' => $MerchantOrderNo
                            ));
							$invloice_update_query=$this->db->last_query();
							$log_message = serialize($invloice_update_query);
							$titlt = "Contact Class invoice update".$this->session->userdata['mem_no'];
							$logs = array(
							'title' =>$titlt,
							'description' =>$log_message);
							$this->master_model->insertRecord('contactclass_logs', $logs,true);
							
                            /*if($getinvoice_number[0]['state_of_center']=='JAM')
                            {
                            $attachpath=genarate_contact_classes_invoice_jk($getinvoice_number[0]['invoice_id'],$session_id);
                            }
                            else
                            {*/
                            $attachpath = genarate_contact_classes_invoice($getinvoice_number[0]['invoice_id'], $session_id);
                            //	}
                        }
                        /*if(count($getinvoice_number) > 0){
                        $invoiceNumber = generate_bankquest_invoice_number($getinvoice_number[0]['invoice_id']);
                        if($invoiceNumber){
                        $invoiceNumber=$this->config->item('bankquest_no_prefix').$invoiceNumber;
                        }
                        
                        
                        }*/
                        
                        
                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        
                        if ($attachpath != '') {
                            
                            //to user
                            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                //to client
                                $this->Emailsending->mailsend_attch($client_arr, $attachpath);
                                /*log activity for genarate subscription number*/
                                $log_title   = "Email send sucessfully for id  :" . $this->session->userdata['contact_classes_memberdata']['id'] . "";
                                $log_message = serialize($info_arr);
                                storedUserActivity($log_title, $log_message, '', '');
                                /* Close User Log Actitives */
                                
                                redirect(base_url() . 'ContactClasses/acknowledge/');
                            } else {
                                redirect(base_url() . 'ContactClasses/acknowledge/');
                            }
                        } else {
                            redirect(base_url() . 'ContactClasses/acknowledge/');
                        }
                        
                        
                        
                    }
                }
            }
            
            redirect(base_url() . 'ContactClasses/acknowledge/');
        } else {
            die("Please try again...");
        }
    }
    
    public function sbitransfail()
    {
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
                
                
                $update_bank_data = array(
                    'pay_status' => 0,
                    'modify_date' => date("Y-m-d H:i:s")
                );
                
                
                $this->master_model->updateRecord('contact_classes_registration', $update_bank_data, array(
                    'contact_classes_id' => $this->session->userdata['contact_classes_memberdata']['id']
                ));
                
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                
            }
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
    public function acknowledge()
    {
        
        if ($this->session->userdata('contact_classes_memberdata') == '') {
            redirect(base_url());
        }
        if ($this->session->userdata('enduserinfo')) {
            
            //unset session data		
            $this->session->unset_userdata('enduserinfo');
        }
        
        $data = array(
            'middle_content' => 'contact_classes/contact_classes_thankyou'
        );
        $this->load->view('common_view_fullwidth', $data);
    }
    
    public function ajax_check_captcha()
    {
        $code = $_POST['code'];
        // check if captcha is set -
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
            //$this->session->set_userdata("regcaptcha", rand(1, 100000));
            echo 'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
            //$this->session->unset_userdata("regcaptcha");
            // $this->session->set_userdata("mycaptcha", rand(1,100000));
            echo 'true';
        }
    }
    
    public function generatecaptchaajax() 
    {
        /* $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data; */
		$this->load->model('Captcha_model');
		echo $captcha_image = $this->Captcha_model->generate_captcha_img('regcaptcha');
    }
    
    //call back for checkpin
    public function check_checkpin()
    {
        $pincode   = mysqli_real_escape_string($this->db->conn_id,$_POST['pincode']);
        $statecode = mysqli_real_escape_string($this->db->conn_id,$_POST['state']);
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
    
    //call back for mobile duplication
    public function check_mobileduplication($mobile)
    {
        if ($mobile != "") {
            
            $prev_count = $this->master_model->getRecordCount('contact_classes_registration', array(
                'mobile' => $mobile,
                'pay_status' => '1'
            ));
            //echo $this->db->last_query();
            if ($prev_count == 1) {
                
                $prev_count1 = $this->master_model->getRecordCount('contact_classes_registration', array(
                    'mobile' => $mobile,
                    'pay_status' => '1'
                ));
                if ($prev_count1 == 1) {
                    return true;
                } else {
                    $str = 'The entered  mobile no already exist ';
                    $this->form_validation->set_message('check_mobileduplication', $str);
                    return false;
                }
            } else {
                
                return true;
            }
        } else {
            return false;
        }
    }
    
    //call back for e-mail duplication
    public function check_emailduplication($email)
    {
        if ($email != "") {
            $this->db->where('email', $email);
            $this->db->where('pay_status', '1');
            $prev_count = $this->master_model->getRecordCount('contact_classes_registration');
            
            //echo $this->db->last_query();
            if ($prev_count == 1) {
                $this->db->where('email', $email);
                $this->db->where('pay_status', '1');
                $prev_count1 = $this->master_model->getRecordCount('contact_classes_registration');
                if ($prev_count1 == 1) {
                    
                    return true;
                } else {
                    $str = 'The entered email ID already exist ';
                    $this->form_validation->set_message('check_emailduplication', $str);
                    return false;
                }
                
            } else {
                return true;
            }
            
        } else {
            return false;
        }
    }
    
    
    //call back for check captcha server side
    public function check_captcha_userreg($code)
    {
        if (isset($_SESSION["regcaptcha"])) {
            if ($code == '' || $_SESSION["regcaptcha"] != $code) {
                $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
                //$this->session->set_userdata("regcaptcha", rand(1,100000));
                return false;
            }
            if ($_SESSION["regcaptcha"] == $code) {
                return true;
            }
        } else {
            return false;
        }
    }
    
    ##---------check mobile nnnumber alredy exist or not (prafull)-----------##
    public function mobileduplication()
    {
        $mobile = $_POST['mobile'];
        if ($mobile != "") {
            
            $this->db->where('mobile', $mobile);
            $prev_count = $this->master_model->getRecordCount('contact_classes_registration', array(
                'pay_status' => '1'
            ));
            
            if ($prev_count == 1) {
                
                
                $this->db->where('mobile', $mobile);
                $prev_count1 = $this->master_model->getRecordCount('contact_classes_registration', array(
                    'pay_status' => '1'
                ));
                if ($prev_count1 == 1) {
                    $data_arr = array(
                        'ans' => 'ok'
                    );
                    echo json_encode($data_arr);
                    
                } else {
                    $str      = 'The entered email ID and mobile no already exist for membership / registration number ';
                    $data_arr = array(
                        'ans' => 'exists',
                        'output' => $str
                    );
                    echo json_encode($data_arr);
                }
            } else {
                
                $data_arr = array(
                    'ans' => 'ok'
                );
                echo json_encode($data_arr);
                
            }
        } else {
            echo 'error';
        }
    }
    
    ##---------check mail alredy exist or not (prafull)-----------##
    public function emailduplication()
    {
        $email = $_POST['email'];
        if ($email != "") {
            
            $this->db->where('email ', $email);
            $prev_count = $this->master_model->getRecordCount('contact_classes_registration', array(
                'pay_status' => '1'
            ));
            
            if ($prev_count == 1) {
                $this->db->where('email ', $email);
                $this->db->where('pay_status', '1');
                $prev_count2 = $this->master_model->getRecordCount('contact_classes_registration', array(
                    'pay_status ' => '1'
                ));
                if ($prev_count2 == 1) {
                    $data_arr = array(
                        'ans' => 'ok'
                    );
                    echo json_encode($data_arr);
                    
                } else {
                    $str      = 'The entered email ID and mobile no already exist for membership / registration number ';
                    $data_arr = array(
                        'ans' => 'exists',
                        'output' => $str
                    );
                    echo json_encode($data_arr);
                    
                }
            } else {
                
                $data_arr = array(
                    'ans' => 'ok'
                );
                echo json_encode($data_arr);
                
            }
        } else {
            echo 'error';
        }
    }
    
    
}
