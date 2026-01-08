<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");

class Sme extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');

        ## Code added to close link on 15 Mar 2022
        $system_date = date("Y-m-d");
        if ($this->get_client_ip() != '115.124.115.75') {
            if ($this->router->fetch_method() == 'junior_executive' || $this->router->fetch_method() == 'assistant_director_academics' || $this->router->fetch_method() == 'assistant_director_it' || $this->router->fetch_method() == 'assistant_director_accounts' || $this->router->fetch_method() == 'deputy_director' || $this->router->fetch_method() == 'deputy_director_it' || $this->router->fetch_method() == 'research_associate' || $this->router->fetch_method() == 'post_of_director_operation') {
                if ($system_date > '2022-12-04') {
                    //   echo '<br><br><h1><center>Registration has been closed. <br><br>Thank you for your interest!<center></h1>';
                    //    exit;
                }
            } else {
                if ($system_date > '2023-05-05') {
                    //  echo '<br><br><h1><center>Registration has been closed. <br><br>Thank you for your interest!<center></h1>';
                    //   exit;
                }
            }
        }
    }



    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    /* Junior Executive */
    // Careers/junior_executive
    public function junior_executive()
    {

        // $current_date_time = date('Y-m-d H:i:s');
        // if($current_date_time > '2021-08-05 23:59:59')
        //  {
        //  echo '<br><br><h1><center>Registration for the Junior Executive has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        //  exit;
        // }

        // if ($this->get_client_ip()!='115.124.115.69') {
        //      echo '<br><br><h1><center>Registration for the Junior Executive has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        //       exit;
        // }

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) { //exit;
            //echo '<pre>'; print_r($_POST); echo '</pre>'; 
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|max_length[30]|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|required|max_length[30]|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
            $this->form_validation->set_rules('contact_number', 'Contact Number', 'trim|numeric|min_length[10]|xss_clean');

            /* PERMANENT ADDRESS */
            $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|required|max_length[30]|xss_clean');
            $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');
            $this->form_validation->set_rules('contact_number_pr', 'Contact Number', 'trim|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_center', 'Exam Center', 'trim|required|xss_clean');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_degree_completion_date', 'Date of completion of the Degree', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_percentage', 'Percentage', 'trim|required|max_length[20]|xss_clean');
            //$this->form_validation->set_rules('ess_grade_marks','Essential Grade or Percentage','trim|max_length[20]|required|xss_clean');
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // DESIRABLE 
            $this->form_validation->set_rules('course_code', 'Course Name', 'trim|xss_clean');
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }
            $this->form_validation->set_rules('from_date', 'From Date', 'trim|xss_clean');
            $this->form_validation->set_rules('to_date', 'To Date', 'trim|xss_clean');
            $this->form_validation->set_rules('degree_completion_date', 'Date of completion of the Degree', 'trim|xss_clean');
            $this->form_validation->set_rules('aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|max_length[20]|xss_clean');
            $this->form_validation->set_rules('aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|max_length[20]|xss_clean');
            $this->form_validation->set_rules('percentage', 'Percentage', 'trim|max_length[20]|xss_clean');
            /* if(isset($_POST['grade_marks']) && $_POST['grade_marks']!='')
                    {
          $this->form_validation->set_rules('grade_marks','Grade or Percentage','trim|max_length[20]|required|xss_clean');
                } */
            $this->form_validation->set_rules('class', 'Class', 'trim|xss_clean');

            /* EMPLOYMENT HISTORY */
            if (isset($_POST['organization']) && $_POST['organization'] != '' && count($_POST['organization']) < 0) {
                $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['designation']) && $_POST['designation'] != '' && count($_POST['designation']) < 0) {
                $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            }

            if (isset($_POST['responsibilities']) && $_POST['responsibilities'] != '' && count($_POST['responsibilities']) < 0) {
                $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');
            }

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }

            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }

            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }

            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }

            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|xss_clean');

            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            //$this->form_validation->set_rules('code', 'Security Code','trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            //$this->form_validation->set_rules('job_from_date[]','Job From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('job_to_date[]','Job To Date','trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                //echo 'in';exit;
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }

                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
            if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
            }
            else{
            $var_errors.=$this->upload->display_errors();
            
                        }   
                    }*/

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            'exam_center' => $_POST["exam_center"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'ess_degree_completion_date' => $_POST["ess_degree_completion_date"],
                            'ess_aggregate_marks_obtained' => $_POST["ess_aggregate_marks_obtained"],
                            'ess_aggregate_max_marks' => $_POST["ess_aggregate_max_marks"],
                            'ess_percentage' => $_POST["ess_percentage"],
                            'ess_class' => $_POST["ess_class"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'degree_completion_date' => $_POST["degree_completion_date"],
                            'aggregate_marks_obtained' => $_POST["aggregate_marks_obtained"],
                            'aggregate_max_marks' => $_POST["aggregate_max_marks"],
                            'percentage' => $_POST["percentage"],
                            'class' => $_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration_note' => $_POST["declaration_note"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'comment' => $_POST["comment"],
                            'declaration2' => $_POST["declaration2"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['dob1']) && $_POST['dob1'] != "") {
            $dob1 = $_POST["dob1"];
            $dob = str_replace('/', '-', $dob1);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'JE1');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {
            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '1',
                'title' => 'Junior_Executive',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/junior_executive', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Junior Executive */
    public function preview()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }

        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }

        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_junior_executive', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Junior Executive */
    public function ass_it_validate_age($age)
    {
        $dob = new DateTime($age);
        $max = new DateTime('01-11-2004');
        $min = new DateTime('01-11-1982');
        if ($dob < $min || $dob > $max) return false;
        else return true;
    }


    public function assistant_director_academics_validate_age($age)
    {
        $dob = new DateTime($age);
        $max = new DateTime('01-11-2022');
        $min = new DateTime('01-11-1987');
        if ($dob < $min || $dob > $max) return false;
        else return true;
    }
    /* Assistant Director academics */
    public function assistant_director_academics()
    {
        if ($this->get_client_ip() != '115.124.115.69') {
            //   exit;
        }
        /* echo '<br><br><h1><center>Registration for the Assistant Director academics has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
            exit; */

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean|callback_assistant_director_academics_validate_age');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            if (isset($_POST['addressline1']) && $_POST['addressline1'] != '') {
                $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            if (isset($_POST['addressline1_pr']) && $_POST['addressline1_pr'] != '') {
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential Institute Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_grade_marks', 'Essential Grade or Percentage', 'trim|max_length[20]|required|xss_clean');

            // CAIIB
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|xss_clean');
            $this->form_validation->set_rules('year_of_passing', 'Year of Passing', 'trim|xss_clean');
            $this->form_validation->set_rules('membership_number', 'Membership Number', 'trim|max_length[20]|xss_clean');


            // DESIRABLE
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'Institute Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['grade_marks']) && $_POST['grade_marks'] != '') {
                $this->form_validation->set_rules('grade_marks', 'Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            }

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Reference1 Organisation (If employed)', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Reference1 Designation', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Reference2 Organisation (If employed)', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Reference2 Designation', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }
            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration1', 'trim|required|xss_clean');
            $this->form_validation->set_rules('declaration3', 'Declaration3', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            $this->form_validation->set_message('assistant_director_academics_validate_age', 'Your Age should be between 18 and 35');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";
                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            //'exam_center'=>$_POST["exam_center"],
                            'refname_one' => $_POST["refname_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            //'ess_subject'=>$_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            //'ess_university'=>$_POST["ess_university"],
                            'ess_grade_marks' => $_POST["ess_grade_marks"],
                            //'ess_class'=>$_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            //'university'=>$_POST["university"],
                            'grade_marks' => $_POST["grade_marks"],
                            //'class'=>$_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration3' => $_POST["declaration3"],
                            'declaration_note' => $_POST["declaration_note"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            'ess_subject' => $_POST["ess_subject"],
                            'year_of_passing' => $_POST["year_of_passing"],
                            'membership_number' => $_POST["membership_number"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                            'exp_in_bank' => $_POST["exp_in_bank"],
                            'publication_of_books' => $_POST["publication_of_books"],
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_assistant_director_academics');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['dob1']) && $_POST['dob1'] != "") {
            $dob1 = $_POST["dob1"];
            $dob = str_replace('/', '-', $dob1);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'ADA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '3',
                'title' => 'Assistant_Director_Academics',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */


            $data = array('middle_content' => 'careers/assistant_director_academics', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Assistant Director academics */
    public function preview_assistant_director_academics()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'ADA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_assistant_director_academics', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Assistant Director Academics */


    /* Assistant Director IT */
    public function assistant_director_it()
    {
        if ($this->get_client_ip() != '115.124.115.69') {
            //  echo '<br><br><h1><center>Registration for the Assistant Director IT has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
            //  exit;
        }
        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('assistant_director_it_dob', 'Date of Birth', 'trim|required|xss_clean|callback_ass_it_validate_age');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');
            //$this->form_validation->set_rules('exam_center','Exam Center','trim|required|xss_clean');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_percentage', 'Percentage', 'trim|required|max_length[20]|xss_clean');
            /* $this->form_validation->set_rules('ess_grade_marks','Essential Grade or Percentage','trim|max_length[20]|required|xss_clean'); */
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // DESIRABLE
            //$this->form_validation->set_rules('course_code','Course Name','trim|required|xss_clean');
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }

            if (isset($_POST['aggregate_marks_obtained']) && $_POST['aggregate_marks_obtained'] != '') {
                $this->form_validation->set_rules('aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|max_length[20]|xss_clean');
            }

            if (isset($_POST['aggregate_max_marks']) && $_POST['aggregate_max_marks'] != '') {
                $this->form_validation->set_rules('aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|max_length[20]|xss_clean');
            }

            if (isset($_POST['percentage']) && $_POST['percentage'] != '') {
                $this->form_validation->set_rules('percentage', 'Percentage', 'trim|max_length[20]|required|xss_clean');
            }

            /* if(isset($_POST['grade_marks']) && $_POST['grade_marks']!=''){
                    $this->form_validation->set_rules('grade_marks','Grade or Percentage','trim|max_length[20]|required|xss_clean');
                } */
            //$this->form_validation->set_rules('class','Class','trim|required|xss_clean');

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration1', 'trim|required|xss_clean');
            $this->form_validation->set_rules('declaration3', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            $this->form_validation->set_message('ass_it_validate_age', 'Your Age should be between 35 to 40 years');
            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }


                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
                        if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
                        {
                        $img = "uploadcv";
                        $tmp_nm = strtotime($date).rand(0,100);
                        $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
                        $config=array('upload_path'=>'./uploads/uploadcv',
                        'allowed_types'=>'pdf|docx|',
                        'file_name'=>$new_filename,);
                        
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
                        
                        if($this->upload->do_upload($img)){
                        $dt=$this->upload->data();
                        $file=$dt['file_name'];
                        $uploadcv_file = $dt['file_name'];
                        $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
                        $var_errors.=$this->upload->display_errors();
                        
                        }   
                    }*/

                $assistant_director_it_dob = $_POST["assistant_director_it_dob"];
                $dob = str_replace('/', '-', $assistant_director_it_dob);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            //'exam_center'=>$_POST["exam_center"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_aggregate_marks_obtained' => $_POST["ess_aggregate_marks_obtained"],
                            'ess_aggregate_max_marks' => $_POST["ess_aggregate_max_marks"],
                            'ess_percentage' => $_POST["ess_percentage"],
                            /* 'ess_grade_marks'=>$_POST["ess_grade_marks"], */
                            'ess_class' => $_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'aggregate_marks_obtained' => $_POST["aggregate_marks_obtained"],
                            'aggregate_max_marks' => $_POST["aggregate_max_marks"],
                            'percentage' => $_POST["percentage"],
                            /* 'grade_marks'=>$_POST["grade_marks"], */
                            'class' => $_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration3' => $_POST["declaration3"],
                            'declaration_note' => $_POST["declaration_note"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_assistant_director_it');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['assistant_director_it_dob']) && $_POST['assistant_director_it_dob'] != "") {
            $assistant_director_it_dob = $_POST["assistant_director_it_dob"];
            $dob = str_replace('/', '-', $assistant_director_it_dob);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }


        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'ADIT');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '2',
                'title' => 'Assistant_Director_IT',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/assistant_director_it', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Assistant Director IT */
    public function preview_assistant_director_it()
    {

        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');

        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_assistant_director_it', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Assistant Director IT */

    /* Assistant Director Accounts */
    public function assistant_director_accounts()
    {
        echo '<br><br><h1><center>Registration for the Assistant Director Accounts has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        exit;

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|required|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            if (isset($_POST['addressline1']) && $_POST['addressline1'] != '') {
                $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            if (isset($_POST['addressline1_pr']) && $_POST['addressline1_pr'] != '') {
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential Institute Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_grade_marks', 'Essential Grade or Percentage', 'trim|max_length[20]|required|xss_clean');

            // DESIRABLE
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'Institute Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['grade_marks']) && $_POST['grade_marks'] != '') {
                $this->form_validation->set_rules('grade_marks', 'Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            }

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Reference1 Organisation (If employed)', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Reference1 Designation', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Reference2 Organisation (If employed)', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Reference2 Designation', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }
            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";
                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            //'exam_center'=>$_POST["exam_center"],
                            'refname_one' => $_POST["refname_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            //'ess_subject'=>$_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            //'ess_university'=>$_POST["ess_university"],
                            'ess_grade_marks' => $_POST["ess_grade_marks"],
                            //'ess_class'=>$_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            //'university'=>$_POST["university"],
                            'grade_marks' => $_POST["grade_marks"],
                            //'class'=>$_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration_note' => $_POST["declaration_note"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_assistant_director_accounts');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['dob1']) && $_POST['dob1'] != "") {
            $dob1 = $_POST["dob1"];
            $dob = str_replace('/', '-', $dob1);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'ADA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '3',
                'title' => 'Assistant_Director_Accounts',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */


            $data = array('middle_content' => 'careers/assistant_director_accounts', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Assistant Director Accounts */
    public function preview_assistant_director_accounts()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'ADA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_assistant_director_accounts', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Assistant Director Accounts */

    /* Director Training */
    public function director_training()
    {
        echo '<br><br><h1><center>Registration for the Director (Training) on Contract has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        exit;

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|required|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            if (isset($_POST['addressline1']) && $_POST['addressline1'] != '') {
                $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            if (isset($_POST['addressline1_pr']) && $_POST['addressline1_pr'] != '') {
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');


            //$this->form_validation->set_rules('exam_center','Exam Center','trim|required|xss_clean');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_grade_marks', 'Essential Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // CAIIB
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|required|xss_clean');
            $this->form_validation->set_rules('year_of_passing', 'Year of Passing', 'trim|required|xss_clean');
            $this->form_validation->set_rules('membership_number', 'Membership Number', 'trim|max_length[20]|required|xss_clean');

            // DESIRABLE
            //$this->form_validation->set_rules('course_code','Course Name','trim|required|xss_clean');
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }
            if (isset($_POST['grade_marks']) && $_POST['grade_marks'] != '') {
                $this->form_validation->set_rules('grade_marks', 'Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            }
            if (isset($_POST['class']) && $_POST['class'] != '') {
                $this->form_validation->set_rules('class', 'Class', 'trim|required|xss_clean');
            }


            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');

            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }

            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_grade_marks' => $_POST["ess_grade_marks"],
                            'ess_class' => $_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'year_of_passing' => $_POST["year_of_passing"],
                            'membership_number' => $_POST["membership_number"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'grade_marks' => $_POST["grade_marks"],
                            'class' => $_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration_note' => $_POST["declaration_note"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_director_training');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }
        $dob = '';
        if (isset($_POST['dob1']) && $_POST['dob1'] != "") {
            $dob1 = $_POST["dob1"];
            $dob = str_replace('/', '-', $dob1);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'DTC');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '4',
                'title' => 'Director_Training',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/director_training', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Director Training */
    public function preview_director_training()
    {

        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'DTC');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_director_training', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Director Training */

    /* Chief Executive Officer */
    public function ceo()
    {
        echo '<br><br><h1><center>Registration for the CEO has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        exit;

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|required|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* Languages */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }

            /* COMMUNICATION ADDRESS */
            if (isset($_POST['addressline1']) && $_POST['addressline1'] != '') {
                $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            if (isset($_POST['addressline1_pr']) && $_POST['addressline1_pr'] != '') {
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');

            if (isset($_POST['ph_d']) && $_POST['ph_d'] != '') {
                $this->form_validation->set_rules('phd_course', 'PHD Name of the course', 'trim|max_length[40]|required|xss_clean');
                $this->form_validation->set_rules('phd_university', 'PHD University', 'trim|max_length[40]|required|xss_clean');
            }
            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_grade_marks', 'Essential Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // DESIRABLE
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }
            if (isset($_POST['grade_marks']) && $_POST['grade_marks'] != '') {
                $this->form_validation->set_rules('grade_marks', 'Grade or Percentage', 'trim|max_length[20]|required|xss_clean');
            }
            if (isset($_POST['class']) && $_POST['class'] != '') {
                $this->form_validation->set_rules('class', 'Class', 'trim|required|xss_clean');
            }

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* UPLOADS */
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[2000]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');
            /* DATES */
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_grade_marks' => $_POST["ess_grade_marks"],
                            'ess_class' => $_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'year_of_passing' => $_POST["year_of_passing"],
                            'membership_number' => $_POST["membership_number"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'grade_marks' => $_POST["grade_marks"],
                            'class' => $_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'experience_as_principal' => $_POST["experience_as_principal"],
                            'experience_as_faculty' => $_POST["experience_as_faculty"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'declaration2' => $_POST["declaration2"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            'ph_d' => $_POST["ph_d"],
                            'phd_course' => $_POST["phd_course"],
                            'phd_university' => $_POST["phd_university"],
                            'publication_of_books' => $_POST["publication_of_books"],
                            'publication_of_articles' => $_POST["publication_of_articles"],
                            'experience_as_principal' => $_POST["experience_as_principal"],
                            'experience_as_faculty' => $_POST["experience_as_faculty"],
                            'area_of_specialization' => $_POST["area_of_specialization"],
                            'earliest_date_of_joining' => $_POST["earliest_date_of_joining"],
                            'suitable_of_the_post_of_CEO' => $_POST["suitable_of_the_post_of_CEO"]
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_ceo');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }
        $dob = '';
        if (isset($_POST['dob1']) && $_POST['dob1'] != "") {
            $dob1 = $_POST["dob1"];
            $dob = str_replace('/', '-', $dob1);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = $experience_as_principal = $experience_as_faculty = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
            $experience_as_principal = $_POST['experience_as_principal'];
            $experience_as_faculty = $_POST['experience_as_faculty'];
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');


        $this->db->where('job_position_code', 'CEO');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '5',
                'title' => 'Chief_Executive_Officer',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/ceo', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date, 'experience_as_principal' => $experience_as_principal, 'experience_as_faculty' => $experience_as_faculty);

            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview :Chief Executive Officer */
    public function preview_ceo()
    {

        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'CEO');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_ceo', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Chief Executive Officer */

    public function deputy_director_validate_age($age)
    {
        $dob = new DateTime($age);
        $max = new DateTime('01-11-2022');
        $min = new DateTime('01-11-1977');
        if ($dob < $min || $dob > $max) return false;
        else return true;
    }

    /* Deputy Director */
    public function deputy_director()
    {
        //   echo '<br><br><h1><center>Registration for the Deputy Director on Contract has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        //      exit;

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('deputy_director_dob', 'Date of Birth', 'trim|required|xss_clean|callback_deputy_director_validate_age');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            if (isset($_POST['addressline1_pr']) && $_POST['addressline1_pr'] != '') {
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');

            /* EDUCATION QUALIFICATION */
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            /* $this->form_validation->set_rules('ess_college_name','Essential College Name','trim|max_length[200]|required|alpha_numeric_spaces|xss_clean'); */
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            //$this->form_validation->set_rules('ess_aggregate_marks_obtained','Essential Aggregate Marks Obtained','trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            //$this->form_validation->set_rules('ess_aggregate_max_marks','Essential Aggregate Maximum Marks','trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_percentage', 'Essential Percentage', 'trim|max_length[20]|required|xss_clean');
            /* $this->form_validation->set_rules('ess_grade_marks','Essential Grade or Percentage','trim|max_length[20]|required|xss_clean'); */
            //$this->form_validation->set_rules('ess_class','Essential Class','trim|required|xss_clean');

            // CAIIB
            /* $this->form_validation->set_rules('ess_subject','Essential Subject','trim|required|xss_clean');
        $this->form_validation->set_rules('year_of_passing','Year of Passing','trim|required|xss_clean');
        $this->form_validation->set_rules('membership_number','Membership Number','trim|max_length[20]|required|xss_clean'); */

            // DESIRABLE  
            //$this->form_validation->set_rules('course_code','Course Name','trim|required|xss_clean');
            /* if(isset($_POST['college_name']) && $_POST['college_name']!=''){
          $this->form_validation->set_rules('college_name','College Name','trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
                } */

            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }
            /* if(isset($_POST['aggregate_marks_obtained']) && $_POST['aggregate_marks_obtained']!=''){
          $this->form_validation->set_rules('aggregate_marks_obtained','Aggregate Marks Obtained','trim|max_length[200]|alpha_numeric_spaces|xss_clean');
                } */
            /* if(isset($_POST['aggregate_max_marks']) && $_POST['aggregate_max_marks']!=''){
          $this->form_validation->set_rules('aggregate_max_marks','Aggregate Maximum Marks','trim|max_length[200]|alpha_numeric_spaces|xss_clean');
                } */
            if (isset($_POST['percentage']) && $_POST['percentage'] != '') {
                $this->form_validation->set_rules('percentage', 'percentage', 'trim|max_length[20]|xss_clean');
            }
            /* if(isset($_POST['grade_marks']) && $_POST['grade_marks']!=''){
          $this->form_validation->set_rules('grade_marks','Grade or Percentage','trim|max_length[20]|required|xss_clean');
                } */
            /* if(isset($_POST['class']) && $_POST['class']!=''){
          $this->form_validation->set_rules('class','Class','trim|required|xss_clean');
                } */

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');

            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }

            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            /* UPLOADS */
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('declaration3', 'Declaration3', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            $this->form_validation->set_message('deputy_director_validate_age', 'Your Age should be between 18 and 45');
            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $exp_in_functional_area = '';
                /* if(isset($_POST['exp_in_functional_area']) && $_POST["exp_in_functional_area"]!=""){
            $exp_in_functional_area = implode(",",$_POST["exp_in_functional_area"]);
                    } */

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $deputy_director_dob = $_POST["deputy_director_dob"];
                $dob = str_replace('/', '-', $deputy_director_dob);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            /* 'ess_subject'=>$_POST["ess_subject"], */
                            'ess_course_name' => $_POST["ess_course_name"],
                            /* 'ess_college_name'=>$_POST["ess_college_name"], */
                            'ess_university' => $_POST["ess_university"],
                            //'ess_aggregate_marks_obtained'=>$_POST["ess_aggregate_marks_obtained"],
                            //'ess_aggregate_max_marks'=>$_POST["ess_aggregate_max_marks"],
                            'ess_percentage' => $_POST["ess_percentage"],
                            /* 'ess_grade_marks'=>$_POST["ess_grade_marks"], */
                            //'ess_class'=>$_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            /* 'year_of_passing'=>$_POST["year_of_passing"],
            'membership_number'=>$_POST["membership_number"], */
                            'course_code' => $_POST["course_code"],
                            /* 'college_name'=>$_POST["college_name"], */
                            'university' => $_POST["university"],
                            //'aggregate_marks_obtained'=>$_POST["aggregate_marks_obtained"],
                            //'aggregate_max_marks'=>$_POST["aggregate_max_marks"],
                            'percentage' => $_POST["percentage"],
                            /* 'grade_marks'=>$_POST["grade_marks"], */
                            //'class'=>$_POST["class"],
                            /* 'exp_in_bank' =>$_POST["exp_in_bank"], */
                            /* 'exp_in_functional_area' =>$exp_in_functional_area, */
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration_note' => $_POST["declaration_note"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration3' => $_POST["declaration3"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            'deputy_subject' => $_POST['deputy_subject'],
                            'specialisation' => $_POST['specialisation'],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_deputy_director');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }
        $dob = '';
        if (isset($_POST['deputy_director_dob']) && $_POST['deputy_director_dob'] != "") {
            $deputy_director_dob = $_POST["deputy_director_dob"];
            $dob = str_replace('/', '-', $deputy_director_dob);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'DDA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '6',
                'title' => 'Deputy_Director',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/deputy_director', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Deputy Director */
    public function preview_deputy_director()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'DDA');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_deputy_director', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Deputy Director */

    /* Deputy Director IT */
    public function deputy_director_it()
    {
        echo '<br><br><h1><center>Registration for the Deputy Director IT has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!<center></h1>';
        exit;

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('deputy_director_it_dob', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');
            //$this->form_validation->set_rules('exam_center','Exam Center','trim|required|xss_clean');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_percentage', 'Percentage', 'trim|required|max_length[20]|xss_clean');
            /* $this->form_validation->set_rules('ess_grade_marks','Essential Grade or Percentage','trim|max_length[20]|required|xss_clean'); */
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // DESIRABLE
            //$this->form_validation->set_rules('course_code','Course Name','trim|required|xss_clean');
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }

            if (isset($_POST['aggregate_marks_obtained']) && $_POST['aggregate_marks_obtained'] != '') {
                $this->form_validation->set_rules('aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|max_length[20]|xss_clean');
            }

            if (isset($_POST['aggregate_max_marks']) && $_POST['aggregate_max_marks'] != '') {
                $this->form_validation->set_rules('aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|max_length[20]|xss_clean');
            }

            if (isset($_POST['percentage']) && $_POST['percentage'] != '') {
                $this->form_validation->set_rules('percentage', 'Percentage', 'trim|max_length[20]|required|xss_clean');
            }

            /* if(isset($_POST['grade_marks']) && $_POST['grade_marks']!=''){
                    $this->form_validation->set_rules('grade_marks','Grade or Percentage','trim|max_length[20]|required|xss_clean');
                } */
            //$this->form_validation->set_rules('class','Class','trim|required|xss_clean');

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Organisation (If employed)', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');
            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            /* UPLOADS */
            $this->form_validation->set_rules('declaration1', 'Declaration1', 'trim|required|xss_clean');
            if ($_POST["declaration_note"] != "") {
                $this->form_validation->set_rules('declaration_note', 'Declaration Note', 'trim|max_length[200]|required|xss_clean');
            }
            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }


                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
                        if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
                        {
                        $img = "uploadcv";
                        $tmp_nm = strtotime($date).rand(0,100);
                        $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
                        $config=array('upload_path'=>'./uploads/uploadcv',
                        'allowed_types'=>'pdf|docx|',
                        'file_name'=>$new_filename,);
                        
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
                        
                        if($this->upload->do_upload($img)){
                        $dt=$this->upload->data();
                        $file=$dt['file_name'];
                        $uploadcv_file = $dt['file_name'];
                        $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
                        $var_errors.=$this->upload->display_errors();
                        
                        }   
                    }*/

                $deputy_director_it_dob = $_POST["deputy_director_it_dob"];
                $dob = str_replace('/', '-', $deputy_director_it_dob);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            //'exam_center'=>$_POST["exam_center"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_aggregate_marks_obtained' => $_POST["ess_aggregate_marks_obtained"],
                            'ess_aggregate_max_marks' => $_POST["ess_aggregate_max_marks"],
                            'ess_percentage' => $_POST["ess_percentage"],
                            /* 'ess_grade_marks'=>$_POST["ess_grade_marks"], */
                            'ess_class' => $_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'course_code' => $_POST["course_code"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'aggregate_marks_obtained' => $_POST["aggregate_marks_obtained"],
                            'aggregate_max_marks' => $_POST["aggregate_max_marks"],
                            'percentage' => $_POST["percentage"],
                            /* 'grade_marks'=>$_POST["grade_marks"], */
                            'class' => $_POST["class"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            'declaration1' => $_POST["declaration1"],
                            'declaration2' => $_POST["declaration2"],
                            'declaration_note' => $_POST["declaration_note"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_deputy_director_it');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['deputy_director_it_dob']) && $_POST['deputy_director_it_dob'] != "") {
            $deputy_director_it_dob = $_POST["deputy_director_it_dob"];
            $dob = str_replace('/', '-', $deputy_director_it_dob);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }


        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('job_position_code', 'ADIT');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
            $this->load->view('common_view_fullwidth', $data);
        } else {

            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '2',
                'title' => 'Deputy_Director_IT',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/deputy_director_it', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
            $this->load->view('common_view_fullwidth', $data);
        }
    }

    /* Preview : Assistant Director IT */
    public function preview_deputy_director_it()
    {

        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');

        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_deputy_director_it', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Assistant Director IT */

    /* Faculty Member on Contract */
    public function faculty_member()
    {
        $current_date_time = date('Y-m-d H:i:s');
        //if($current_date_time > '2022-06-20 23:59:59')
        if ($this->get_client_ip() != '115.124.115.69') {
            //  echo '<br><br><h1>Registration for the Faculty Member on Contract has been closed because we have reached the maximum number of registrations for the post. <br><br>Thank you for your interest!</h1>';
            //  exit;
        }

        $flag = 1;
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST['btnSubmit'])) {
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

            /* BASIC DETAILS */
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('father_husband_name', 'Fathers/Husband Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('faculty_member_dob', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('marital_status', 'Marital Status', 'trim|required|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('alternate_mobile', 'Alternate Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('pan_no', 'PAN No', 'trim|alpha_numeric_spaces|min_length[10]|xss_clean');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            } else {
                if ($this->input->post('aadhar_card_no') != '') {
                    $this->form_validation->set_rules('aadhar_card_no', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean');
                }
            }

            /* COMMUNICATION ADDRESS */
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            if ($this->input->post('state') != '') {
                $state = $this->input->post('state');
            }
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            /* PERMANENT ADDRESS */
            $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }
            $this->form_validation->set_rules('district_pr', 'Permanent District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('city_pr', 'Permanent City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state_pr', 'Permanent State', 'trim|required|xss_clean');
            if ($this->input->post('state_pr') != '') {
                $state_pr = $this->input->post('state_pr');
            }
            $this->form_validation->set_rules('pincode_pr', 'Permanent Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_pr[' . $state_pr . ']');

            /* EDUCATION QUALIFICATION */
            // Essential
            $this->form_validation->set_rules('ess_course_name', 'Essential Course Name', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_pg_stream_subject', 'Post Graduation stream & subject', 'trim|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_college_name', 'Essential College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('ess_university', 'Essential University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|required|max_length[20]|xss_clean');
            $this->form_validation->set_rules('ess_percentage', 'Percentage', 'trim|required|max_length[20]|xss_clean');
            //$this->form_validation->set_rules('ess_grade_marks','Essential Grade or Percentage','trim|max_length[20]|required|xss_clean'); 
            $this->form_validation->set_rules('ess_class', 'Essential Class', 'trim|required|xss_clean');

            // CAIIB
            $this->form_validation->set_rules('ess_subject', 'Essential Subject', 'trim|required|xss_clean');
            $this->form_validation->set_rules('year_of_passing', 'Year of Passing', 'trim|required|xss_clean');
            $this->form_validation->set_rules('membership_number', 'Membership Number', 'trim|max_length[20]|required|xss_clean');

            // DESIRABLE
            //$this->form_validation->set_rules('course_code','Course Name','trim|required|xss_clean');
            if (isset($_POST['name_subject_of_course']) && $_POST['name_subject_of_course'] != '') {
                $this->form_validation->set_rules('name_subject_of_course', 'Name & Subject of the course', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['college_name']) && $_POST['college_name'] != '') {
                $this->form_validation->set_rules('college_name', 'College Name', 'trim|max_length[200]|required|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['university']) && $_POST['university'] != '') {
                $this->form_validation->set_rules('university', 'University', 'trim|max_length[200]|alpha_numeric_spaces|required|xss_clean');
            }

            $this->form_validation->set_rules('aggregate_marks_obtained', 'Aggregate Marks Obtained', 'trim|max_length[20]|xss_clean');
            $this->form_validation->set_rules('aggregate_max_marks', 'Aggregate Maximum Marks', 'trim|max_length[20]|xss_clean');
            $this->form_validation->set_rules('percentage', 'Percentage', 'trim|max_length[20]|xss_clean');

            /* if(isset($_POST['grade_marks']) && $_POST['grade_marks']!=''){
          $this->form_validation->set_rules('grade_marks','Grade or Percentage','trim|max_length[20]|required|xss_clean');
                } */
            if (isset($_POST['class']) && $_POST['class'] != '') {
                $this->form_validation->set_rules('class', 'Class', 'trim|required|xss_clean');
            }

            /* EMPLOYMENT HISTORY */
            $this->form_validation->set_rules('organization[]', 'Name of the Organization', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('designation[]', 'Designation', 'trim|max_length[40]|alpha_numeric_spaces|required|xss_clean');
            $this->form_validation->set_rules('responsibilities[]', 'Responsibilities', 'trim|max_length[300]|alpha_numeric_spaces|required|xss_clean');

            /* REFERENCE 1 */
            $this->form_validation->set_rules('refname_one', 'Reference1 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_one', 'Reference1 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_one', 'Reference1 Organisation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_one', 'Reference1 Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_one', 'Reference1 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_one', 'Reference1 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            /* REFERENCE 2 */
            $this->form_validation->set_rules('refname_two', 'Reference2 Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('refaddressline_two', 'Reference2 Address', 'trim|max_length[250]|required|xss_clean');
            $this->form_validation->set_rules('reforganisation_two', 'Reference2 Organisation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refdesignation_two', 'Reference2 Designation', 'trim|max_length[250]|xss_clean');
            $this->form_validation->set_rules('refemail_two', 'Reference2 Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('refmobile_two', 'Reference2 Mobile', 'trim|required|numeric|min_length[10]|xss_clean');

            $this->form_validation->set_rules('publication_of_books', 'Published Articles/Books', 'trim|xss_clean');

            /* Languages, Extracurricular, Achievements */
            $this->form_validation->set_rules('languages_known', 'Languages Known', 'trim|max_length[30]|required|xss_clean');
            $this->form_validation->set_rules('languages_option[]', 'Languages Option', 'trim|required|xss_clean');

            if (isset($_POST['languages_option1']) && $_POST['languages_option1'] != '') {
                $this->form_validation->set_rules('languages_known1', 'Languages Known 2', 'trim|max_length[30]|required|xss_clean');
            }
            if (isset($_POST['languages_option2']) && $_POST['languages_option2'] != '') {
                $this->form_validation->set_rules('languages_known2', 'Languages Known 3', 'trim|max_length[30]|required|xss_clean');
            }

            if (isset($_POST['extracurricular']) && $_POST['extracurricular'] != '') {
                $this->form_validation->set_rules('extracurricular', 'Extracurricular', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['hobbies']) && $_POST['hobbies'] != '') {
                $this->form_validation->set_rules('hobbies', 'Hobbies', 'trim|max_length[200]|required|xss_clean');
            }
            if (isset($_POST['achievements']) && $_POST['achievements'] != '') {
                $this->form_validation->set_rules('achievements', 'Achievements', 'trim|max_length[200]|required|xss_clean');
            }

            /* UPLOADS */
            /* $this->form_validation->set_rules('declaration1','Declaration1','trim|required|xss_clean');
        if($_POST["declaration_note"] != "")
                {
          $this->form_validation->set_rules('declaration_note','Declaration Note','trim|max_length[200]|required|xss_clean');
                } */

            $this->form_validation->set_rules('declaration2', 'Declaration2', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('submit_date', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('place', 'Place', 'trim|max_length[30]|required|xss_clean');
            if ($_POST["comment"] != "") {
                $this->form_validation->set_rules('comment', 'Comment', 'trim|max_length[300]|required|xss_clean');
            }
            $this->form_validation->set_rules('code', 'Security Code', 'trim|required|xss_clean|callback_check_captcha_userreg');

            /* DATES */
            //$this->form_validation->set_rules('from_date','Education From Date','trim|required|xss_clean');
            //$this->form_validation->set_rules('to_date','Education To Date Name','trim|required|xss_clean');
            $this->form_validation->set_rules('job_from_date[]', 'Job From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('job_to_date[]', 'Job To Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_from_date', 'Essential From Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('ess_to_date', 'Essential To Date', 'trim|required|xss_clean');

            if ($this->form_validation->run() == TRUE) {
                $outputphoto1 = $outputsign1 = $outputsign1 = $scannedphoto_file = $scannedsignaturephoto_file = '';
                // ajax response -
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = $uploadcv_file = $outputuploadcv_file = $languages_option = $languages_option1 = $languages_option2 = "";

                $this->session->unset_userdata('enduserinfo');

                if (isset($_POST['languages_option']) && $_POST["languages_option"] != "") {
                    $languages_option = implode(",", $_POST["languages_option"]);
                }
                if (isset($_POST['languages_option1']) && $_POST["languages_option1"] != "") {
                    $languages_option1 = implode(",", $_POST["languages_option1"]);
                }

                if (isset($_POST['languages_option2']) && $_POST["languages_option2"] != "") {
                    $languages_option2 = implode(",", $_POST["languages_option2"]);
                }

                $exp_in_functional_area = '';
                if (isset($_POST['exp_in_functional_area']) && $_POST["exp_in_functional_area"] != "") {
                    $exp_in_functional_area = implode(",", $_POST["exp_in_functional_area"]);
                }

                $date = date('Y-m-d h:i:s');
                //Generate dynamic photo
                $input = $_POST["hiddenphoto"];
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/photograph',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature
                $inputsignature = $_POST["hiddenscansignature"];
                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_signnm;
                    $config = array(
                        'upload_path' => './uploads/scansignature',
                        'allowed_types' => 'jpg|jpeg|',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /*$input = $_POST["hiddenuploadcv"];
            if(isset($_FILES['uploadcv']['name']) &&($_FILES['uploadcv']['name']!=''))
            {
            $img = "uploadcv";
            $tmp_nm = strtotime($date).rand(0,100);
            $new_filename = 'cv_'.$tmp_nm.'_'.$_POST["mobile"];
            $config=array('upload_path'=>'./uploads/uploadcv',
            'allowed_types'=>'pdf|docx|',
            'file_name'=>$new_filename,);
            
            $this->upload->initialize($config);
            $size = @getimagesize($_FILES['uploadcv']['tmp_name']);
            
                        if($this->upload->do_upload($img)){
            $dt=$this->upload->data();
            $file=$dt['file_name'];
            $uploadcv_file = $dt['file_name'];
            $outputuploadcv_file = base_url()."uploads/uploadcv/".$uploadcv_file;
                        }
                        else{
            $var_errors.=$this->upload->display_errors();
            
            }   
                    }*/

                $faculty_member_dob = $_POST["faculty_member_dob"];
                $dob = str_replace('/', '-', $faculty_member_dob);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                $from_date = $_POST["from_date"];
                $to_date = $_POST["to_date"];
                $job_from_date = $_POST["job_from_date"];
                $job_to_date = $_POST["job_to_date"];

                if ($scannedphoto_file != '' && $scannedsignaturephoto_file != '') {
                    $user_data =
                        array(
                            'sel_namesub' => $_POST["sel_namesub"],
                            'firstname' => $_POST["firstname"],
                            'middlename' => $_POST["middlename"],
                            'lastname' => $_POST["lastname"],
                            'father_husband_name' => $_POST["father_husband_name"],
                            'dateofbirth' => $dateofbirth,
                            'gender' => $_POST["gender"],
                            'email' => $_POST["email"],
                            'marital_status' => $_POST["marital_status"],
                            'mobile' => $_POST["mobile"],
                            'alternate_mobile' => $_POST["alternate_mobile"],
                            'pan_no' => $_POST["pan_no"],
                            'aadhar_card_no' => $_POST["aadhar_card_no"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'addressline3' => $_POST["addressline3"],
                            'addressline4' => $_POST["addressline4"],
                            'district' => $_POST["district"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'contact_number' => $_POST["contact_number"],
                            'addressline1_pr' => $_POST["addressline1_pr"],
                            'addressline2_pr' => $_POST["addressline2_pr"],
                            'addressline3_pr' => $_POST["addressline3_pr"],
                            'addressline4_pr' => $_POST["addressline4_pr"],
                            'district_pr' => $_POST["district_pr"],
                            'city_pr' => $_POST["city_pr"],
                            'state_pr' => $_POST["state_pr"],
                            'pincode_pr' => $_POST["pincode_pr"],
                            'contact_number_pr' => $_POST["contact_number_pr"],
                            'refname_one' => $_POST["refname_one"],
                            'refaddressline_one' => $_POST["refaddressline_one"],
                            'reforganisation_one' => $_POST["reforganisation_one"],
                            'refdesignation_one' => $_POST["refdesignation_one"],
                            'refemail_one' => $_POST["refemail_one"],
                            'refmobile_one' => $_POST["refmobile_one"],
                            'refname_two' => $_POST["refname_two"],
                            'refaddressline_two' => $_POST["refaddressline_two"],
                            'reforganisation_two' => $_POST["reforganisation_two"],
                            'refdesignation_two' => $_POST["refdesignation_two"],
                            'refemail_two' => $_POST["refemail_two"],
                            'refmobile_two' => $_POST["refmobile_two"],
                            'scannedphoto' => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'photoname' => $scannedphoto_file,
                            'signname' => $scannedsignaturephoto_file,
                            'ess_subject' => $_POST["ess_subject"],
                            'ess_course_name' => $_POST["ess_course_name"],
                            'ess_pg_stream_subject' => $_POST["ess_pg_stream_subject"],
                            'ess_college_name' => $_POST["ess_college_name"],
                            'ess_university' => $_POST["ess_university"],
                            'ess_aggregate_marks_obtained' => $_POST["ess_aggregate_marks_obtained"],
                            'ess_aggregate_max_marks' => $_POST["ess_aggregate_max_marks"],
                            'ess_percentage' => $_POST["ess_percentage"],
                            //'ess_grade_marks'=>$_POST["ess_grade_marks"],
                            'ess_class' => $_POST["ess_class"],
                            'ess_from_date' => $_POST["ess_from_date"],
                            'ess_to_date' => $_POST["ess_to_date"],
                            'year_of_passing' => $_POST["year_of_passing"],
                            'membership_number' => $_POST["membership_number"],
                            'course_code' => $_POST["course_code"],
                            'name_subject_of_course' => $_POST["name_subject_of_course"],
                            'college_name' => $_POST["college_name"],
                            'university' => $_POST["university"],
                            'aggregate_marks_obtained' => $_POST["aggregate_marks_obtained"],
                            'aggregate_max_marks' => $_POST["aggregate_max_marks"],
                            'percentage' => $_POST["percentage"],
                            //'grade_marks'=>$_POST["grade_marks"],
                            'class' => $_POST["class"],
                            'exp_in_bank' => $_POST["exp_in_bank"],
                            'exp_in_functional_area' => $exp_in_functional_area,
                            'publication_of_books' => $_POST["publication_of_books"],
                            'organization' => $_POST["organization"],
                            'designation' => $_POST["designation"],
                            'responsibilities' => $_POST["responsibilities"],
                            'from_date' => $from_date,
                            'to_date' => $to_date,
                            'job_from_date' => $job_from_date,
                            'job_to_date' => $job_to_date,
                            'languages_known' => $_POST["languages_known"],
                            'languages_option' => $languages_option,
                            'languages_known1' => $_POST["languages_known1"],
                            'languages_option1' => $languages_option1,
                            'languages_known2' => $_POST["languages_known2"],
                            'languages_option2' => $languages_option2,
                            'extracurricular' => $_POST["extracurricular"],
                            'hobbies' => $_POST["hobbies"],
                            'achievements' => $_POST["achievements"],
                            /* 'declaration1'=>$_POST["declaration1"],
            'declaration_note'=>$_POST["declaration_note"], */
                            'declaration2' => $_POST["declaration2"],
                            'comment' => $_POST["comment"],
                            'place' => $_POST["place"],
                            'submit_date' => $_POST["submit_date"],
                            'position_id' => $_POST["position_id"],
                            //'uploadcv'=>$uploadcv_file,
                            //'uploadcv_path'=>$outputuploadcv_file 
                        );
                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Careers/preview_faculty_member');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }
        $dob = '';
        if (isset($_POST['faculty_member_dob']) && $_POST['faculty_member_dob'] != "") {
            $faculty_member_dob = $_POST["faculty_member_dob"];
            $dob = str_replace('/', '-', $faculty_member_dob);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $organization =  $designation = $responsibilities = $job_from_date = $job_to_date = array();
        if (isset($_POST['organization']) && $_POST['organization'] != "") {
            $organization = $_POST['organization'];
            $designation = $_POST['designation'];
            $responsibilities = $_POST['responsibilities'];
            $job_from_date = $_POST['job_from_date'];
            $job_to_date = $_POST['job_to_date'];
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->order_by('course_code', 'ASC');
        $this->db->where('job_position_code', 'FMC');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        /* Captcha Code */
        $this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];

        if ($flag == 0) {
            $data = array('middle_content' => 'cookie_msg');
        } else {
            /* Page Track Counts */
            $insert_page_array = array();
            $obj = new OS_BR();
            $browser_details = implode('|', $obj->showInfo('all'));
            $insert_page_array = array(
                'position_id' => '7',
                'title' => 'Faculty_Member',
                'ip' => $this->input->ip_address(),
                'browser' => $browser_details,
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            if (count($insert_page_array) > 0) {
                $this->master_model->insertRecord('careers_pages_logs', $insert_page_array);
            }
            /* Page Track Counts END */

            $data = array('middle_content' => 'careers/faculty_member', 'states' => $states, 'careers_course_mst' => $careers_course_mst, 'image' => $cap['image'], 'var_errors' => $var_errors, 'dob' => $dob, 'organization' => $organization, 'designation' => $designation, 'responsibilities' => $responsibilities, 'job_from_date' => $job_from_date, 'job_to_date' => $job_to_date);
        }

        $this->load->view('common_view_fullwidth', $data);
    }

    /* Preview : Faculty Member on Contract */
    public function preview_faculty_member()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'FMC');
        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_faculty_member', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Faculty Member on Contract */

    /* Corporate_Development_Officer */

    /* Preview : Post of Director (Operations) on contract basis */
    public function preview_post_of_director_operation()
    {

        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }

        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers');
        }

        $states = $this->master_model->getRecords('state_master');

        $careers_course_mst = $this->master_model->getRecords('careers_course_mst');

        $data = array('middle_content' => 'careers/preview_post_of_director_operation', 'states' => $states, 'careers_course_mst' => $careers_course_mst);

        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Post of Director (Operations) on contract basis */

    /* Head PDC NZ */
    public function index()
    {
        // echo "<pre>";
        // print_r($_FILES['uploadcv']);
        // echo "<pre>";
        // print_r($_POST); exit;
        $current_date_time = date('Y-m-d H:i:s');

        $flag = 1;
        $var_errors = '';
        $data['validation_errors'] = '';

        if (isset($_POST) && count($_POST) > 0) {
            /* BASIC DETAILS */
            $this->form_validation->set_rules('name', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('head_pdc_nz_dobs', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('educational_qualification', 'Educational Qualification', 'trim|max_length[50]|required|xss_clean');
            $this->form_validation->set_rules('CAIIB_qualification', 'CAIIB Qualification', 'required|xss_clean');
            $this->form_validation->set_rules('addressline1', 'Address 1', 'trim|max_length[50]|required|xss_clean');
            $this->form_validation->set_rules('addressline2', 'Address 2', 'trim|max_length[50]|required|xss_clean');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[25]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[40]|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('bank_education', 'Bank/Education', 'required|xss_clean');
            $this->form_validation->set_rules('organisation_name', 'Organisation Name', 'trim|max_length[25]|required|xss_clean');
            $this->form_validation->set_rules('retired_working', 'Retired/Working', 'required|xss_clean');
            $this->form_validation->set_rules('year', 'Year', 'trim|required|numeric|min_length[1]|xss_clean');
            $this->form_validation->set_rules('mobile', 'Month', 'trim|required|numeric|min_length[1]|xss_clean');
            $this->form_validation->set_rules('designation', 'Designation', 'trim|max_length[25]|required|xss_clean');
            $this->form_validation->set_rules('uploadcv', 'Subject/(s) of Expertise/Interest PDF', 'file_required|file_allowed_type[pdf]|file_size_max[300]');

            if ($this->form_validation->run() == TRUE) {
                $resp = array('success' => 0, 'error' => 0, 'msg' => '');

                $this->session->unset_userdata('enduserinfo');

                $date = date('Y-m-d h:i:s');

                //Generate dynamic photo
                $input = $_POST["hiddenuploadcv"];
                if (isset($_FILES['uploadcv']['name']) && ($_FILES['uploadcv']['name'] != '')) {
                    $img = "uploadcv";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'cv_' . $tmp_nm . '_' . $_POST["mobile"];
                    $config = array(
                        'upload_path' => './uploads/uploadcv',
                        'allowed_types' => 'pdf|doc|docx|',
                        'file_name' => $new_filename,
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['uploadcv']['tmp_name']);

                    if ($this->upload->do_upload($img)) {
                        $dt   = $this->upload->data();
                        $file = $dt['file_name'];
                        $uploadcv_file = $dt['file_name'];
                        $outputcv = base_url() . "uploads/uploadcv/" . $uploadcv_file;
                    } else {
                        $var_errors .= $this->upload->display_errors();
                    }
                }

                $head_pdc_nz_dob = $_POST["head_pdc_nz_dobs"];
                $dob = str_replace('/', '-', $head_pdc_nz_dob);
                $dateofbirth = date('Y-m-d', strtotime($dob));

                if (isset($uploadcv_file) && $uploadcv_file != '') {
                    $user_data =
                        array(
                            'name' => $_POST["name"],
                            'position_id' => $_POST["position_id"],
                            'dateofbirth' => $dateofbirth,
                            'educational_qualification' => $_POST["educational_qualification"],
                            'CAIIB_qualification' => $_POST["CAIIB_qualification"],
                            'addressline1' => $_POST["addressline1"],
                            'addressline2' => $_POST["addressline2"],
                            'city' => $_POST["city"],
                            'state' => $_POST["state"],
                            'pincode' => $_POST["pincode"],
                            'mobile' => $_POST["mobile"],
                            'email' => $_POST["email"],
                            'bank_education' => $_POST["bank_education"],
                            'organisation_name' => $_POST["organisation_name"],
                            'retired_working' => $_POST["retired_working"],
                            'year' => $_POST["year"],
                            'month' => $_POST["month"],
                            'designation' => $_POST["designation"],
                            'uploadcv' => $uploadcv_file,
                            'outputcv' => $outputcv,
                            'general' => $_POST["general"],
                            'specialised' => $_POST["specialised"],
                            'it' => $_POST["it"],
                            'other' => $_POST["other"]
                        );

                    $this->session->set_userdata('enduserinfo', $user_data);

                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'sme/preview_head_pdc_nz');
                } else {
                    $var_errors = str_replace("<p>", "<span>", $var_errors);
                    $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                }
            }
        }

        $dob = '';
        if (isset($_POST['head_pdc_nz_dobs']) && $_POST['head_pdc_nz_dobs'] != "") {
            $head_pdc_nz_dob = $_POST["head_pdc_nz_dobs"];
            $dob = str_replace('/', '-', $head_pdc_nz_dob);
            $dateofbirth = date('Y-m-d', strtotime($dob));
            $dob = $dateofbirth;
        }

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');


        $data = array('middle_content' => 'sme/head_pdc_nz', 'states' => $states, 'var_errors' => $var_errors, 'dob' => $dob);

        $this->load->view('common_view_fullwidth', $data);
    }

    /* Preview : Head PDC NZ */
    public function preview_head_pdc_nz()
    {
        // echo "<pre>";
        // print_r($_SESSION); exit;
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $images_flag = 0;
        if (!file_exists("uploads/uploadcv/" . $this->session->userdata['enduserinfo']['uploadcv'])) {
            $images_flag = 1;
        }

        $states = $this->master_model->getRecords('state_master');
        $this->db->where('job_position_code', 'PDCNZ');
        $data = array('middle_content' => 'sme/preview_head_pdc_nz', 'states' => $states);
        $this->load->view('common_view_fullwidth', $data);
    }
    /* Close Head PDC NZ */

    /* Stored Data */
    public function addmember()
    {
        if (!$this->session->userdata['enduserinfo']) {
            //echo '<pre>'; print_r($_SESSION); exit;
            redirect('https://iibf.org.in/sme.asp');
        }

        // Variables Define 
        $position_id = $name = $dateofbirth = $educational_qualification = $CAIIB_qualification = $addressline1 = $addressline2 = $city = $state = $pincode = $mobile = $email = $bank_education = $organisation_name = $retired_working = $year = $month = $designation = $outputcv = $uploadcv = "";

        $general = $specialised = $it = $other = [];
        // echo "<pre>"; print_r($this->session->userdata['enduserinfo']); exit;
        $position_id = $this->session->userdata['enduserinfo']['position_id'];
        $name = $this->session->userdata['enduserinfo']['name'];
        $dateofbirth = $this->session->userdata['enduserinfo']['dateofbirth'];
        $educational_qualification = $this->session->userdata['enduserinfo']['educational_qualification'];
        $CAIIB_qualification = $this->session->userdata['enduserinfo']['CAIIB_qualification'];
        $addressline1 = $this->session->userdata['enduserinfo']['addressline1'];
        $addressline2 = $this->session->userdata['enduserinfo']['addressline2'];
        $city = $this->session->userdata['enduserinfo']['city'];
        $state = $this->session->userdata['enduserinfo']['state'];
        $pincode = $this->session->userdata['enduserinfo']['pincode'];
        $mobile = $this->session->userdata['enduserinfo']['mobile'];
        $email = $this->session->userdata['enduserinfo']['email'];
        $bank_education = $this->session->userdata['enduserinfo']['bank_education'];
        $organisation_name = $this->session->userdata['enduserinfo']['organisation_name'];
        $retired_working = $this->session->userdata['enduserinfo']['retired_working'];
        $year = $this->session->userdata['enduserinfo']['year'];
        $month = $this->session->userdata['enduserinfo']['month'];
        $designation = $this->session->userdata['enduserinfo']['designation'];
        $outputcv = $this->session->userdata['enduserinfo']['outputcv'];
        $uploadcv = $this->session->userdata['enduserinfo']['uploadcv'];
        $it          = $this->session->userdata['enduserinfo']['it'];
        $other       = $this->session->userdata['enduserinfo']['other'];
        $general     = $this->session->userdata['enduserinfo']['general'];
        $specialised = $this->session->userdata['enduserinfo']['specialised'];

        if ($general != '') {
            $general = implode(',', $general);
        }
        if ($it != '') {
            $it = implode(',', $it);
        }
        if ($specialised != '') {
            $specialised = implode(',', $specialised);
        }
        if ($other != '') {
            $other = implode(',', $other);
        }

        $createdon   = date('Y-m-d H:i:s');
        $submit_date = date('Y-m-d');
        $insert_info = array(
            'firstname' => $name,
            'dateofbirth' => $dateofbirth,
            'educational_qualification' => $educational_qualification,
            'CAIIB_qualification' => $CAIIB_qualification,
            'addressline1' => $addressline1,
            'addressline2' => $addressline2,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'mobile' => $mobile,
            'email' => $email,
            'bank_education' => $bank_education,
            'ess_college_name' => $organisation_name,
            'retired_working' => $retired_working,
            'exp_in_bank' => $year . "," . $month,
            'designation' => $designation,
            'uploadcv' => $uploadcv,
            'createdon' => $createdon,
            'submit_date' => $submit_date,
            'position_id' => $position_id,
            'it_subjects' => $it,
            'general_subjects' => $general,
            'specialisation' => $specialised,
            'other_subjects' => $other
        );
        // echo "<pre>"; print_r($insert_info); exit;
        // $this->master_model->insertRecord('careers_registration',$insert_info,true)
        // echo $this->db->last_query();                                    
        if ($last_id = $this->master_model->insertRecord('careers_registration', $insert_info, true)) {
            if ($last_id !== "" || $last_id != 0) {
                /* Gen.  Unique No */
                $unique_no = '';
                $unique_no = $this->generate_unique_no($last_id);
                $update_data = array('unique_no' => $unique_no, 'active_status' => '1');
                $this->master_model->updateRecord('careers_registration', $update_data, array('careers_id' => $last_id));

                /* Stored Logs */
                if (count($insert_info) > 0) {
                    $log_title   = "Application of " . $position_id . " - sme_registration";
                    $log_message = serialize($insert_info);
                    $unique_no   = $unique_no;
                    $position_id = $position_id;
                    // gaurabvcomment below line by live server testing purpose
                    careers_logactivity($log_title, $log_message, $unique_no, $position_id);
                }

                /* Email Sending */
                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'sme_email'));
                if (count($emailerstr) > 0) {
                    /*'to'=>$email,*/
                    $final_str = $emailerstr[0]['emailer_text'];

                    $info_arr = array(
                        'to' => $email,
                        'from' => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $final_str
                    );
                }
                // echo "<pre>"; print_r($info_arr); exit;
                $this->Emailsending->mailsend($info_arr);

                redirect(base_url() . 'sme/acknowledge/');
                //redirect(base_url()."Register/make_payment");
            }
        }
    }

    public function make_payment($career_id_encode)
    {
        $career_id = base64_decode($career_id_encode);

        if ($career_id > 0) {
            $this->db->where('pay_status IS NULL');
            $career_data = $this->master_model->getRecords('careers_registration', array('careers_id' => $career_id));

            if (count($career_data) > 0) {
                $amount = '826';
                $regno = $career_id;
                $custom_reg_id = 'JE' . date('Y') . sprintf("%04d", $career_id);
                // Create transaction
                $insert_data = array(
                    'gateway'     => 'billdesk',
                    'member_regnumber' => $custom_reg_id,
                    'amount'      =>  $amount,
                    'date'        => date('Y-m-d H:i:s'),
                    'ref_id'      => $regno,
                    'description' => "Career Registration",
                    'pay_type'    => 22,
                    'status'      => 2,
                    'pg_flag'     => 'iibf_career_reg',
                );

                $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

                $log_title   = "Career Registration  payment transaction insert:" . $pt_id;
                $log_message = serialize($insert_data);

                storedUserActivity($log_title, $log_message, $regno, $regno);

                $MerchantOrderNo = reg_sbi_order_id($pt_id);

                //Member registration
                //Ref1 = primary key of member registation table
                //Ref2 = iibfregn
                //Ref3 = primary key of member registation table
                //Ref4 = orderid  For below string
                $custom_field          = $regno . "^iibf_career_reg^" . $regno . "^" . $MerchantOrderNo;
                $custom_field_billdesk = $regno . "-iibf_career_reg-" . $regno . "-" . $MerchantOrderNo;

                // update receipt no. in payment transaction -
                $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
                $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));
                $state = $career_data[0]['state'];
                if (!empty($state)) {
                    $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
                }
                if ($state == 'MAH') {
                    //set a rate (e.g 9%,9% or 18%)
                    $cgst_rate = $this->config->item('cgst_rate');
                    $sgst_rate = $this->config->item('sgst_rate');
                    //set an amount as per rate
                    $cgst_amt = '63';
                    $sgst_amt = '63';
                    //set an total amount
                    $cs_total = $amount;
                    $tax_type = 'Intra';

                    $igst_rate = 0;

                    $igst_amt = 0;
                    $igst_total = 0;
                } else {
                    $cgst_rate = 0;
                    $sgst_rate = 0;
                    $cgst_amt = 0;
                    $sgst_amt = 0;
                    $cs_total = 0;
                    $igst_rate  = $this->config->item('igst_rate');
                    $igst_amt   = '126';
                    $igst_total = $amount;
                    $tax_type   = 'Inter';
                }


                $invoice_insert_array = array(
                    'pay_txn_id' => $pt_id,
                    'receipt_no'                               => $MerchantOrderNo,
                    'member_no'                                => $custom_reg_id,
                    'state_of_center'                          => $state,
                    'app_type'                                 => 'CR',
                    'service_code'                             => $this->config->item('reg_service_code'),
                    'qty'                                      => '1',
                    'state_code'                               => $getstate[0]['state_no'],
                    'state_name'                               => $getstate[0]['state_name'],
                    'tax_type'                                 => $tax_type,
                    'fee_amt'                                  => '700',
                    'cgst_rate'                                => $cgst_rate,
                    'cgst_amt'                                 => $cgst_amt,
                    'sgst_rate'                                => $sgst_rate,
                    'sgst_amt'                                 => $sgst_amt,
                    'igst_rate'                                => $igst_rate,
                    'igst_amt'                                 => $igst_amt,
                    'cs_total'                                 => $cs_total,
                    'igst_total'                               => $igst_total,
                    'gstin_no'                                 => '',
                    'exempt'                                   => $getstate[0]['exempt'],
                    'created_on'                               => date('Y-m-d H:i:s')
                );

                $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

                $log_title   = "Career Registration  invoice insert" . $regno;
                $log_message = serialize($invoice_insert_array);
                storedUserActivity($log_title, $log_message, $regno, $regno);
                //                         if ($this->get_client_ip()=='115.124.115.69') {
                //     $amount=1;
                // }

                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'careers/handle_billdesk_response', '', '', '', $custom_field_billdesk);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'careers/junior_executive/preview');
                    die;
                }
            } else {
                die('User not found');
            }
        }
    }

    public function handle_billdesk_response()
    {
        // ini_set('display_errors', 1);
        // ini_set('display_startup_errors', 1);
        // error_reporting(E_ALL);
        $this->load->helper('update_image_name_helper');
        delete_cookie('regid');
        $this->session->unset_userdata('enduserinfo');
        $this->session->unset_userdata('memberdata');

        if (isset($_REQUEST['transaction_response'])) {

            $response_encode        = $_REQUEST['transaction_response'];
            $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
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

            $user_payment_txn_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

            $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
            $this->log_model->logtransaction("billdesk-career-top", $pg_response, $transaction_error_type);

            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $user_payment_txn_info[0]['status'] == 2) {

                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 1,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'gateway'             => 'billdesk',
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));

                $log_title   = "Career payment paymentupdate";
                $log_message = serialize($update_data);
                $reg_id         = $user_payment_txn_info[0]['ref_id'];


                storedUserActivity($log_title, $log_message, $reg_id, $reg_id);

                if ($this->db->affected_rows()) {

                    if (count($user_payment_txn_info) > 0) {
                        $custom_reg_id = 'JE' . date('Y') . sprintf("%04d", $reg_id);

                        $update_mem_data = array('pay_status' => '1', 'reg_id' => $custom_reg_id);
                        $this->master_model->updateRecord('careers_registration', $update_mem_data, array('careers_id' => $reg_id));
                    }

                    //get invoice
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $user_payment_txn_info[0]['id']));

                    if (count($getinvoice_number) > 0) {

                        $invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = 'CAREER/2022-23/' . $invoiceNumber;
                        }

                        $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                        $this->db->where('pay_txn_id', $user_payment_txn_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));

                        $attachpath = genarate_career_invoice($getinvoice_number[0]['invoice_id'], $reg_id);
                        $log_title   = "Career Invoice log update  :" . $reg_id;
                        $log_message = serialize($this->db->last_query());
                        storedUserActivity($log_title, $log_message, $reg_id, $reg_id);
                    }

                    $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'careers_email'));
                    if (count($emailerstr) > 0) {
                        /*'to'=>$email,*/
                        $final_str = $emailerstr[0]['emailer_text'];

                        $career_data = $this->master_model->getRecords('careers_registration', array('careers_id' => $career_id));
                        if (count($career_data) > 0) {
                            $info_arr = array(
                                'to' => $career_data[0]['email'],
                                'from' => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_str
                            );

                            if ($attachpath != '') {
                                $this->Emailsending->mailsend_attch($info_arr, $attachpath);
                            }
                        }
                    }
                }

                //Manage Log
                $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                redirect(base_url() . 'careers/acknowledge_payment/' . base64_encode($MerchantOrderNo) . '/' . $custom_reg_id);
                exit();
            } elseif ($auth_status == '0002'  && $user_payment_txn_info[0]['status'] == 2) {

                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 2,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );

                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $this->session->set_flashdata('flsh_msg', 'Transaction under process...!');
                redirect(base_url() . 'careers/acknowledge_payment/' . base64_encode($MerchantOrderNo));
            } else /* if ($transaction_error_type == 'payment_authorization_error') */ {
                if ($user_payment_txn_info[0]['status'] != 0 && $user_payment_txn_info[0]['status'] == 2) {
                    $update_data = array(
                        'transaction_no'      => $transaction_no,
                        'status'              => 0,
                        'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                        'auth_code'           => '0399',
                        'bankcode'            => $bankid,
                        'paymode'             => $txn_process_type,
                        'callback'            => 'B2B',
                    );

                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    $pg_response = "encData=" . json_encode($responsedata) . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                }
                redirect(base_url() . 'careers/acknowledge_payment/' . base64_encode($MerchantOrderNo));
            }
        } else {
            die("Please try again...");
        }
    }

    public function test_mail($value = '')
    {
        $attachpath = 'uploads/reginvoice/user/JE20220034_CAREER_2022-23_041740.jpg';
        $info_arr = array(
            'to' => 'vishal.phadol@esds.co.in',
            'from' => 'noreply@iibf.org.in',
            'subject' => 'Job application of IIBF',
            'message' => 'Job application of IIBF'
        );


        $this->Emailsending->mailsend_attch($info_arr, $attachpath);
    }

    public function acknowledge_payment($receipt_no = '', $reg_id = '')
    {
        $receipt_no = base64_decode($receipt_no);
        if ($receipt_no > 0 && $receipt_no != '') {
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $receipt_no));

            $user_info = array();

            ## Clear session data
            $this->session->set_userdata('session_array', '');
            $this->session->unset_userdata('session_array');
            $status = 'pending';

            if ($payment_info[0]['status'] == 1) {
                $status = 'success';
            } elseif ($payment_info[0]['status'] == 0) {
                $status = 'failed';
            }
            $data['transaction_status'] = $status;
            $data['user_info'] = $user_info;
            $data['payment_info'] = $payment_info;
            $data['reg_id'] = $reg_id;
            $this->load->view('careers/payment_response', $data);
        }
    }

    /* Stored Data */
    public function addmember_old_21_02_2020()
    {
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        // Variables Define
        $position_id = $sel_namesub = $firstname = $middlename = $lastname = $father_husband_name = $dateofbirth = $gender = $email = $marital_status = $mobile = $alternate_mobile = $pan_no = $aadhar_card_no = $addressline1 = $addressline2 = $addressline3 = $addressline4 = $district = $city = $state = $pincode = $contact_number = $addressline1_pr = $addressline2_pr = $addressline3_pr = $addressline4_pr = $district_pr = $city_pr = $state_pr = $pincode_pr = $contact_number_pr = $exam_center = $refname_one = $refaddressline_one = $refemail_one = $refmobile_one = $refname_two = $refaddressline_two = $reforganisation_two = $refdesignation_two = $refemail_two = $refmobile_two = $languages_known = $languages_option = $languages_known1 = $languages_option1 = $languages_known2 = $languages_option2 = $extracurricular = $hobbies = $achievements = $declaration1 = $declaration_note = $declaration2 = $scannedphoto_file = $scannedsignaturephoto_file = $comment = $place = $submit_date = $ess_course_name = $ess_subject = $ess_college_name = $ess_university = $ess_grade_marks = $ess_class = $course_code = $college_name = $university = $grade_marks = $class = $organization = $designation = $responsibilities = $from_date = $to_date = $job_from_date = $experience_as_principal = $job_to_date = $ess_from_date = $ess_to_date = $createdon = $year_of_passing = $membership_number = $ph_d = $phd_course = $phd_university = $publication_of_books = $publication_of_articles = $experience_as_faculty = $area_of_specialization = $earliest_date_of_joining = $suitable_of_the_post_of_CEO = $experience_as_principal = $experience_as_faculty = "";

        $course_code = $ess_subject = $exam_center = "0";

        $position_id = $this->session->userdata['enduserinfo']['position_id'];
        /* BASIC DETAILS */
        $sel_namesub = $this->session->userdata['enduserinfo']['sel_namesub'];
        $firstname = $this->session->userdata['enduserinfo']['firstname'];
        $middlename = $this->session->userdata['enduserinfo']['middlename'];
        $lastname = $this->session->userdata['enduserinfo']['lastname'];
        $father_husband_name = $this->session->userdata['enduserinfo']['father_husband_name'];
        $dateofbirth = $this->session->userdata['enduserinfo']['dateofbirth'];
        $gender = $this->session->userdata['enduserinfo']['gender'];
        $email = $this->session->userdata['enduserinfo']['email'];
        $marital_status = $this->session->userdata['enduserinfo']['marital_status'];
        $mobile = $this->session->userdata['enduserinfo']['mobile'];
        $alternate_mobile = $this->session->userdata['enduserinfo']['alternate_mobile'];
        $pan_no = $this->session->userdata['enduserinfo']['pan_no'];
        $aadhar_card_no = $this->session->userdata['enduserinfo']['aadhar_card_no'];

        /* COMMUNICATION ADDRESS */
        $addressline1 = $this->session->userdata['enduserinfo']['addressline1'];
        $addressline2 = $this->session->userdata['enduserinfo']['addressline2'];
        $addressline3 = $this->session->userdata['enduserinfo']['addressline3'];
        $addressline4 = $this->session->userdata['enduserinfo']['addressline4'];
        $district = $this->session->userdata['enduserinfo']['district'];
        $city = $this->session->userdata['enduserinfo']['city'];
        $state = $this->session->userdata['enduserinfo']['state'];
        $pincode = $this->session->userdata['enduserinfo']['pincode'];
        $contact_number = $this->session->userdata['enduserinfo']['contact_number'];

        /* PERMANENT ADDRESS */
        $addressline1_pr = $this->session->userdata['enduserinfo']['addressline1_pr'];
        $addressline2_pr = $this->session->userdata['enduserinfo']['addressline2_pr'];
        $addressline3_pr = $this->session->userdata['enduserinfo']['addressline3_pr'];
        $addressline4_pr = $this->session->userdata['enduserinfo']['addressline4_pr'];
        $district_pr = $this->session->userdata['enduserinfo']['district_pr'];
        $city_pr = $this->session->userdata['enduserinfo']['city_pr'];
        $state_pr = $this->session->userdata['enduserinfo']['state_pr'];
        $pincode_pr = $this->session->userdata['enduserinfo']['pincode_pr'];
        $contact_number_pr = $this->session->userdata['enduserinfo']['contact_number_pr'];
        if ($position_id == 1) {
            $exam_center = $this->session->userdata['enduserinfo']['exam_center'];
        }
        /* REFRENCE DETAILS */
        $refname_one = $this->session->userdata['enduserinfo']['refname_one'];
        $refaddressline_one = $this->session->userdata['enduserinfo']['refaddressline_one'];
        $refemail_one = $this->session->userdata['enduserinfo']['refemail_one'];
        $refmobile_one = $this->session->userdata['enduserinfo']['refmobile_one'];
        $refname_two = $this->session->userdata['enduserinfo']['refname_two'];
        $refaddressline_two = $this->session->userdata['enduserinfo']['refaddressline_two'];
        $refemail_two = $this->session->userdata['enduserinfo']['refemail_two'];
        $refmobile_two = $this->session->userdata['enduserinfo']['refmobile_two'];

        /* Languages, Extracurricular, Achievements */
        $languages_known = $this->session->userdata['enduserinfo']['languages_known'];
        $languages_option = $this->session->userdata['enduserinfo']['languages_option'];

        $languages_known1 = $this->session->userdata['enduserinfo']['languages_known1'];
        $languages_option1 = $this->session->userdata['enduserinfo']['languages_option1'];

        $languages_known2 = $this->session->userdata['enduserinfo']['languages_known2'];
        $languages_option2 = $this->session->userdata['enduserinfo']['languages_option2'];
        if ($position_id != 5) {
            $extracurricular = $this->session->userdata['enduserinfo']['extracurricular'];
            $hobbies = $this->session->userdata['enduserinfo']['hobbies'];
            $achievements = $this->session->userdata['enduserinfo']['achievements'];
            $declaration1 = $this->session->userdata['enduserinfo']['declaration1'];
            $declaration_note = $this->session->userdata['enduserinfo']['declaration_note'];
        }
        /* UPLOAD */

        $declaration2 = $this->session->userdata['enduserinfo']['declaration2'];
        $scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];
        $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
        $comment = $this->session->userdata['enduserinfo']['comment'];
        $place = $this->session->userdata['enduserinfo']['place'];
        $submit_date = $this->session->userdata['enduserinfo']['submit_date'];
        //$uploadcv_file = $this->session->userdata['enduserinfo']['uploadcv'];
        //$uploadcv_file_path = $this->session->userdata['enduserinfo']['uploadcv_path'];

        /* EDUCATION QUALIFICATION */
        $ess_course_name = $this->session->userdata['enduserinfo']['ess_course_name'];

        if ($position_id == 1 || $position_id == 4 || $position_id == 2 || $position_id == 5) {
            $ess_subject = $this->session->userdata['enduserinfo']['ess_subject'];
        }
        if ($position_id != 3) {
            $ess_university = $this->session->userdata['enduserinfo']['ess_university'];
            $ess_class = $this->session->userdata['enduserinfo']['ess_class'];
            $university = $this->session->userdata['enduserinfo']['university'];
            $class = $this->session->userdata['enduserinfo']['class'];
        }
        $ess_college_name = $this->session->userdata['enduserinfo']['ess_college_name'];
        $ess_grade_marks = $this->session->userdata['enduserinfo']['ess_grade_marks'];

        // CAIIB
        if ($position_id == 4 || $position_id == 5) {
            $year_of_passing = $this->session->userdata['enduserinfo']['year_of_passing'];
            $membership_number = $this->session->userdata['enduserinfo']['membership_number'];
        }
        $course_code = $this->session->userdata['enduserinfo']['course_code'];
        $college_name = $this->session->userdata['enduserinfo']['college_name'];
        $grade_marks = $this->session->userdata['enduserinfo']['grade_marks'];


        /* EMPLOYMENT HISTORY */
        $organization = $this->session->userdata['enduserinfo']['organization'];
        $designation = $this->session->userdata['enduserinfo']['designation'];
        $responsibilities = $this->session->userdata['enduserinfo']['responsibilities'];

        if ($position_id == 5) {
            $experience_as_principal = $this->session->userdata['enduserinfo']['experience_as_principal'];
            $experience_as_faculty = $this->session->userdata['enduserinfo']['experience_as_faculty'];
        }
        /* DATES */
        $from_date = $this->session->userdata['enduserinfo']['from_date'];
        $to_date = $this->session->userdata['enduserinfo']['to_date'];
        $job_from_date = $this->session->userdata['enduserinfo']['job_from_date'];
        $job_to_date = $this->session->userdata['enduserinfo']['job_to_date'];
        $ess_from_date = $this->session->userdata['enduserinfo']['ess_from_date'];
        $ess_to_date = $this->session->userdata['enduserinfo']['ess_to_date'];

        if ($position_id == 5) {
            $ph_d = $this->session->userdata['enduserinfo']['ph_d'];
            $phd_course = $this->session->userdata['enduserinfo']['phd_course'];
            $phd_university = $this->session->userdata['enduserinfo']['phd_university'];
            $publication_of_books = $this->session->userdata['enduserinfo']['publication_of_books'];
            $publication_of_articles = $this->session->userdata['enduserinfo']['publication_of_articles'];
            $area_of_specialization = $this->session->userdata['enduserinfo']['area_of_specialization'];
            $earliest_date_of_joining = $this->session->userdata['enduserinfo']['earliest_date_of_joining'];
            $suitable_of_the_post_of_CEO = $this->session->userdata['enduserinfo']['suitable_of_the_post_of_CEO'];
        }

        $createdon = date('Y-m-d H:i:s');

        $insert_info = array(
            'sel_namesub' => $sel_namesub,
            'firstname' => $firstname,
            'middlename' => $middlename,
            'lastname' => $lastname,
            'father_husband_name' => $father_husband_name,
            'dateofbirth' => $dateofbirth,
            'gender' => $gender,
            'email' => $email,
            'marital_status' => $marital_status,
            'mobile' => $mobile,
            'alternate_mobile' => $alternate_mobile,
            'pan_no' => $pan_no,
            'aadhar_card_no' => $aadhar_card_no,
            'addressline1' => $addressline1,
            'addressline2' => $addressline2,
            'addressline3' => $addressline3,
            'addressline4' => $addressline4,
            'district' => $district,
            'city' => $city,
            'state' => $state,
            'pincode' => $pincode,
            'contact_number' => $contact_number,
            'addressline1_pr' => $addressline1_pr,
            'addressline2_pr' => $addressline2_pr,
            'addressline3_pr' => $addressline3_pr,
            'addressline4_pr' => $addressline4_pr,
            'district_pr' => $district_pr,
            'city_pr' => $city_pr,
            'state_pr' => $state_pr,
            'pincode_pr' => $pincode_pr,
            'contact_number_pr' => $contact_number_pr,
            'exam_center' => $exam_center,
            'ess_course_name' => $ess_course_name,
            'ess_subject' => $ess_subject,
            'ess_college_name' => $ess_college_name,
            'ess_university' => $ess_university,
            'ess_grade_marks' => $ess_grade_marks,
            'ess_class' => $ess_class,
            'ess_from_date' => $ess_from_date,
            'ess_to_date' => $ess_to_date,
            'year_of_passing' => $year_of_passing,
            'membership_number' => $membership_number,
            'languages_known' => $languages_known,
            'languages_option' => $languages_option,
            'languages_known1' => $languages_known1,
            'languages_option1' => $languages_option1,
            'languages_known2' => $languages_known2,
            'languages_option2' => $languages_option2,
            'extracurricular' => $extracurricular,
            'hobbies' => $hobbies,
            'achievements' => $achievements,
            'refname_one' => $refname_one,
            'refaddressline_one' => $refaddressline_one,
            'refemail_one' => $refemail_one,
            'refmobile_one' => $refmobile_one,
            'refname_two' => $refname_two,
            'refaddressline_two' => $refaddressline_two,
            'refemail_two' => $refemail_two,
            'refmobile_two' => $refmobile_two,
            'scannedphoto' => $scannedphoto_file,
            'scannedsignaturephoto' => $scannedsignaturephoto_file,
            'declaration1' => $declaration1,
            'declaration_note' => $declaration_note,
            'declaration2' => $declaration2,
            'comment' => $comment,
            'place' => $place,
            'submit_date' => $submit_date,
            'createdon' => $createdon,
            'position_id' => $position_id,
            'ph_d' => $ph_d,
            'phd_course' => $phd_course,
            'phd_university' => $phd_university,
            'publication_of_books' => $publication_of_books,
            'publication_of_articles' => $publication_of_articles,
            'area_of_specialization' => $area_of_specialization,
            'earliest_date_of_joining' => $earliest_date_of_joining,
            'suitable_of_the_post_of_CEO' => $suitable_of_the_post_of_CEO
        );



        //echo $this->db->last_query();
        //exit;
        /* BASIC DETAILS - COMMUNICATION ADDRESS */
        if ($last_id = $this->master_model->insertRecord('careers_registration', $insert_info, true)) {
            $insert_edu_qualification = array();

            $insert_edu_qualification = array(
                'course_code' => $course_code,
                'college_name' => $college_name,
                'university' => $university,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'grade_marks' => $grade_marks,
                'class' => $class,
                'createdon' => $createdon,
                'careers_id' => $last_id
            );
            if ($course_code != "") {
                if (count($insert_edu_qualification) > 0) {
                    $this->master_model->insertRecord('careers_edu_qualification', $insert_edu_qualification);
                }
            }

            /* EMPLOYMENT HISTORY */
            foreach ($organization as $job_key => $job_val) {
                $insert_prof_cert = array();

                $experience_as_principal_val = $experience_as_faculty_val = $job_to_date_val = $job_from_date_val = $responsibilities_val =  $designation_val = $organization_val = "";

                $organization_val = $job_val;
                $designation_val = $designation[$job_key];
                $responsibilities_val = $responsibilities[$job_key];
                $job_from_date_val = $job_from_date[$job_key];
                $job_to_date_val = $job_to_date[$job_key];

                if (isset($experience_as_principal[$job_key])) {
                    $experience_as_principal_val = $experience_as_principal[$job_key];
                }
                if (isset($experience_as_faculty[$job_key])) {
                    $experience_as_faculty_val = $experience_as_faculty[$job_key];
                }

                $insert_prof_cert = array(
                    'organization' => $organization_val,
                    'designation' => $designation_val,
                    'responsibilities' => $responsibilities_val,
                    'job_from_date' => $job_from_date_val,
                    'job_to_date' => $job_to_date_val,
                    'experience_as_principal' => $experience_as_principal_val,
                    'experience_as_faculty' => $experience_as_faculty_val,
                    'createdon' => $createdon,
                    'careers_id' => $last_id
                );
                if ($organization_val != "") {
                    if (count($insert_prof_cert) > 0) {
                        $this->master_model->insertRecord('careers_employment_hist', $insert_prof_cert);
                    }
                }
            }

            if ($last_id !== "" || $last_id != 0) {
                /* Gen.  Unique No */
                $unique_no = '';
                $unique_no = $this->generate_unique_no($last_id);
                $update_data = array('unique_no' => $unique_no, 'active_status' => '1');
                $this->master_model->updateRecord('careers_registration', $update_data, array('careers_id' => $last_id));

                /* Stored Logs */
                if (count($insert_info) > 0) {
                    $log_title = "Application of " . $position_id . " - careers_registration";
                    $log_message = serialize($insert_info);
                    $unique_no = $unique_no;
                    $position_id = $position_id;
                    careers_logactivity($log_title, $log_message, $unique_no, $position_id);
                }
                if (count($insert_edu_qualification) > 0) {
                    $log_title = "Application of " . $position_id . " - careers_edu_qualification";
                    $log_message = serialize($insert_edu_qualification);
                    $unique_no = $unique_no;
                    $position_id = $position_id;
                    careers_logactivity($log_title, $log_message, $unique_no, $position_id);
                }

                foreach ($organization as $job_key => $job_val) {
                    $insert_prof_cert = array();

                    $experience_as_principal_val = $experience_as_faculty_val = $job_to_date_val = $job_from_date_val = $responsibilities_val =  $designation_val = $organization_val = "";

                    $organization_val = $job_val;
                    $designation_val = $designation[$job_key];
                    $responsibilities_val = $responsibilities[$job_key];
                    $job_from_date_val = $job_from_date[$job_key];
                    $job_to_date_val = $job_to_date[$job_key];

                    if (isset($experience_as_principal[$job_key])) {
                        $experience_as_principal_val = $experience_as_principal[$job_key];
                    }
                    if (isset($experience_as_faculty[$job_key])) {
                        $experience_as_faculty_val = $experience_as_faculty[$job_key];
                    }

                    $insert_prof_cert = array(
                        'organization' => $organization_val,
                        'designation' => $designation_val,
                        'responsibilities' => $responsibilities_val,
                        'job_from_date' => $job_from_date_val,
                        'job_to_date' => $job_to_date_val,
                        'experience_as_principal' => $experience_as_principal_val,
                        'experience_as_faculty' => $experience_as_faculty_val,
                        'createdon' => $createdon,
                        'careers_id' => $last_id
                    );

                    if (count($insert_prof_cert) > 0) {
                        $log_title = "Application of " . $position_id . " - careers_employment_hist";
                        $log_message = serialize($insert_prof_cert);
                        $unique_no = $unique_no;
                        $position_id = $position_id;
                        careers_logactivity($log_title, $log_message, $unique_no, $position_id);
                    }
                }
            }

            /* Email Sending */
            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'careers_email'));
            if (count($emailerstr) > 0) {
                /*'to'=>$email,*/
                $final_str = $emailerstr[0]['emailer_text'];

                $info_arr = array(
                    'to' => $email,
                    'from' => $emailerstr[0]['from'],
                    'subject' => $emailerstr[0]['subject'],
                    'message' => $final_str
                );
            }
            $this->Emailsending->mailsend($info_arr);

            redirect(base_url() . 'Careers/acknowledge/');
            //redirect(base_url()."Register/make_payment");
        } else {
            $this->session->set_flashdata('error', 'Error while during registration.please try again!');
            redirect(base_url());
        }
    }

    public function generate_unique_no($last_id)
    {
        if ($last_id  != NULL) {
            $created_date = date('Y-m-d H:i:s');
            $insert = array('careers_id' => $last_id, 'created_date' => $created_date);
            $unique_no = str_pad($this->master_model->insertRecord('careers_unique_no', $insert, true), 5, "0", STR_PAD_LEFT);
        }
        return $unique_no;
    }

    // callback function to validate addressline 1
    public function address1($addressline1)
    {
        if (!preg_match('/^[a-z0-9 .,-]+$/i', $addressline1)) {
            $this->form_validation->set_message('address1', "Please enter valid addressline1");
            return false;
        } else {
            return true;
        }
    }

    //callback to validate photo
    function scannedphoto_upload()
    {
        if ($_FILES['scannedphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedphoto_upload', "No Scanned Photograph file selected");
            return false;
        }
    }

    //callback to validate scannedsignaturephoto
    function scannedsignaturephoto_upload()
    {
        if ($_FILES['scannedsignaturephoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedsignaturephoto_upload', "No  Scanned Signature file selected");
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

    public function date_view()
    {
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $this->db->where('institution_master.institution_delete', '0');
        $institution_master = $this->master_model->getRecords('institution_master');

        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        $this->db->where('designation_master.designation_delete', '0');
        $designation = $this->master_model->getRecords('designation_master');

        $this->db->not_like('name', 'college');
        $idtype_master = $this->master_model->getRecords('idtype_master');



        $this->load->helper('captcha');
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $data['image'] = $cap['image'];
        $data['code'] = $cap['word'];
        $this->session->set_userdata('regcaptcha', $cap['word']);


        $calendar = get_calendar_input();
        //print_r($calendar);
        //exit; 

        $data = array('middle_content' => 'register1', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'image' => $cap['image'], 'idtype_master' => $idtype_master, 'calendar' => $calendar);
        $this->load->view('common_view_fullwidth', $data);
    }

    //validate captcha
    ##---------check captcha userlogin (prafull)-----------##
    public function ajax_check_captcha()
    {
        echo 'true';
        exit;
        $code = $_POST['code'];
        // check if captcha is set -
        if ($code == '' || $_SESSION["regcaptcha"] != $code) {
            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');
            //$this->session->set_userdata("regcaptcha", rand(1, 100000));
            echo  'false';
        } else if ($_SESSION["regcaptcha"] == $code) {
            //$this->session->unset_userdata("regcaptcha");
            // $this->session->set_userdata("mycaptcha", rand(1,100000));
            echo 'true';
        }
    }

    // reload captcha functionality
    public function generatecaptchaajax()
    {
        $this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url' => base_url() . 'uploads/applications/',
        );
        $cap = create_captcha($vals);
        $data = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data;
    }

    //Thank you message to end user
    public function acknowledge()
    {
        if ($this->session->userdata('enduserinfo')) {
            $this->session->unset_userdata('enduserinfo');
        }
        $data = array('middle_content' => 'sme/sme_acknowledge');
        $this->load->view('common_view_fullwidth', $data);
    }

    //print user Register profile (Prafull)
    public function printUser()
    {


        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata['memberdata']['regno'], 'isactive' => '1'));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }

        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'UG'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'GR'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'PG'), 'name as qname', '', '', 1);
        }


        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');
        $data = array('middle_content' => 'print_member_profile', 'user_info' => $user_info, 'qualification' => $qualification, 'idtype_master' => $idtype_master);
        $this->load->view('common_view', $data);
    }

    //Download pdf(Prafull)
    public function pdf()
    {
        if (!$this->session->userdata('memberdata')) {
            redirect(base_url());
        }
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata['memberdata']['regno'], 'isactive' => '1'));

        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'UG'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'GR'), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array('type' => 'PG'), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');

        $username = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $userfinalstrname;
        if ($user_info[0]['gender'] == 'female') {
            $gender = 'Female';
        }
        if ($user_info[0]['gender'] == 'male') {
            $gender = 'Male';
        }
        if ($user_info[0]['qualification'] == 'U') {
            $memqualification =  'Under Graduate';
        }
        if ($user_info[0]['qualification'] == 'G') {
            $memqualification =  'Graduate';
        }
        if ($user_info[0]['qualification'] == 'P') {
            $memqualification =  'Post Graduate';
        }

        if ($user_info[0]['optnletter'] == 'Y') {
            $optnletter =  'Yes';
        }
        if ($user_info[0]['optnletter'] == 'N') {
            $optnletter =  'No';
        }

        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
        if ($user_info[0]['address2'] != '') {
            $user_info[0]['address2'] = $user_info[0]['address2'] . '<br>
        ';
        }
        if ($user_info[0]['address3'] != '') {
            $user_info[0]['address3'] = $user_info[0]['address3'] . '<br>
        ';
        }
        if ($user_info[0]['address4'] != '') {
            $user_info[0]['address4'] = $user_info[0]['address4'] . '<br>
        ';
        }
        $useradd = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'] . ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . ',' . $user_info[0]['state_name'] . $user_info[0]['pincode'];
        $html = '
      <table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
      border: 1px solid #000; padding:25px;
      ">
      <tbody>
      <tr>
      <td colspan="4" align="left">&nbsp;</td>
      </tr>
      <tr>
      <td colspan="4" align="center" height="25"><span id="1001a1" class="alert"> </span> </td>
      </tr>
      <tr style="border-bottom:solid 1px #000;">
      <td colspan="4" height="1"><img src="../AppData/Local/Temp/fz3temp-2/' . base_url() . 'assets/images/logo1.png"></td>
      </tr>
      <tr>
      <td colspan="4"></hr>
      <table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
      <tbody>
      <tr>
      <td class="tablecontent2" width="51%">Membership No : </td>
      <td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
      <td class="tablecontent" rowspan="4" valign="top"><img src="../AppData/Local/Temp/fz3temp-2/' . base_url() . 'uploads/photograph/' . $user_info[0]['scannedphoto'] . '" height="100" width="100" > </td>
      </tr>
      <tr>
      <td class="tablecontent2">Password :</td>
      <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $decpass . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Full Name :</td>
      <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Name as to appear on Card :</td>
      <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['displayname'] . '</td>
      </tr>
      <tr>
      <td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
      <td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap"> ' . $useradd . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Date of Birth :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . date('d-m-Y', strtotime($user_info[0]['dateofbirth'])) . '</td>
      </tr>
      <tr>
      <td class="tablecontent2">Gender :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $gender . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Qualification :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $memqualification . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Specify :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $qualification[0]['qname'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Bank/Institution working :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['name'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Branch/Office :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['office'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Designation :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['dname'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Date of Joining :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . date('d-m-Y', strtotime($user_info[0]['dateofjoin'])) . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Email :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['email'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">Mobile :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['mobile'] . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">ID Proof :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $idtype_master[0]['name'] . '</td>
      </tr>
      <tr>
      <td class="tablecontent2">Aadhar Card Number :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . '</td>
      </tr>
      <tr>
      <td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap"> ' . $optnletter . ' </td>
      </tr>
      <tr>
      <td class="tablecontent2">ID Proof :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="../AppData/Local/Temp/fz3temp-2/' . base_url() . 'uploads/idproof/' . $user_info[0]['idproofphoto'] . '"  height="180" width="100"></td>
      </tr>
      <tr>
      <td class="tablecontent2">Signature :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="../AppData/Local/Temp/fz3temp-2/' . base_url() . 'uploads/scansignature/' . $user_info[0]['scannedsignaturephoto'] . '" height="100" width="100"></td>
      </tr>
      <tr>
      <td class="tablecontent2">Date :</td>
      <td colspan="3" class="tablecontent2" nowrap="nowrap"> ' . date('d-m-Y h:i:s A', strtotime($user_info[0]['createdon'])) . ' </td>
      </tr>
      </tbody>
      </table></td>
      </tr>
      </tbody>
      </table>
      ';

        //this the the PDF filename that user will get to download
        $pdfFilePath = 'iibf' . '.pdf';
        //load mPDF library
        $this->load->library('m_pdf');
        //actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        //$pdf->SetHTMLHeader($header);
        $pdf->SetHTMLHeader('');
        $pdf->SetHTMLFooter('');
        $stylesheet = '/*Table with outline Classes*/
      .tablecontent2 {
      background-color: #ffffff;
      bottom: 5px;
      color: #000000;
      font-family: Tahoma;
      font-size: 11px;
      font-weight: normal;
      height: 10px;
      left: 5px;
      padding: 5px;
      right: 5px;
      top: 5px;
      }
      .img{ width:100%; height:auto; padding:15px;}';
        header('Content-Type: application/pdf');
        header('Content-Description: inline; filename.pdf');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output($pdfFilePath, 'D');
    }

    public function cookie_msg()
    {
        $data = array('middle_content' => 'cookie_msg');
        $this->load->view('common_view_fullwidth', $data);
    }

    //Genereate random password function
    public function generate_random_password($length = 8, $level = 2) // function to generate new password
    {
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));
        $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
        $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
        $password = "";
        $counter = 0;
        while ($counter < $length) {
            $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
            if (!strstr($password, $actChar)) {
                $password .= $actChar;
                $counter++;
            }
        }
        return $password;
    }

    //call back for e-mail duplication
    public function check_emailduplication($email)
    {
        if ($email != "") {
            $this->db->where(" '2024-01-01' < submit_date");
            $prev_count = $this->master_model->getRecordCount('careers_registration', array('email' => $email, 'active_status' => '1', 'position_id' => '13'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {

                $str = 'The entered email ID already exist';
                $this->form_validation->set_message('check_emailduplication', $str);
                return false;
            }
        } else {
            return false;
        }
    }

    //call back for mobile duplication
    public function check_mobileduplication($mobile)
    {
        if ($mobile != "") {
            $this->db->where("'2024-01-01' < submit_date");
            $prev_count = $this->master_model->getRecordCount('careers_registration', array('mobile' => $mobile, 'position_id' => '13', 'active_status' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {

                $str = 'The entered  mobile no already exist';
                $this->form_validation->set_message('check_mobileduplication', $str);
                return false;
            }
        } else {
            return false;
        }
    }

    //call back for checkpin
    public function check_checkpin($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
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

    //call back for checkpin
    public function check_checkpin_pr($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
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

    public function emailduplication()
    {
        $email = $_POST['email'];
        $position_id = $_POST['position_id'];
        $arr_response['status'] = true;
        if ($email != "") {
            if ($position_id == 1) {
                $this->db->where('pay_status', '1');
            }
            $this->db->where(" '2024-01-01' < submit_date"); // added condition by priyank D. on 22-nov-22 to skip old entries
            $prev_count = $this->master_model->getRecordCount('careers_registration', array('email' => $email, 'active_status' => '1', 'position_id' => $position_id));
            //  echo $this->db->last_query();
            if ($prev_count == 0) {
                // $data_arr=array('ans'=>'ok');        
                // echo json_encode($data_arr);}
                $arr_response['status'] = false;
            } else {

                // $str='The entered email id already exist';
                // $data_arr=array('ans'=>'exists','output'=>$str);       
                // echo json_encode($data_arr);
                $arr_response['status'] = true;
            }
        } else {
            $arr_response['status'] = false;
        }
        echo json_encode($arr_response);
    }

    public function mobileduplication()
    {
        // print_r($_POST); exit; 
        $mobile = $_POST['mobile'];
        $position_id = $_POST['position_id'];
        $arr_response['status'] = false;
        if ($mobile != "") {
            if ($position_id == 1) {
                $this->db->where('pay_status', 1);
            }
            $this->db->where("'2024-01-01' < submit_date"); // added condition by priyank D. on 22-nov-22 to skip old entries
            $prev_count = $this->master_model->getRecordCount('careers_registration', array('mobile' => $mobile, 'active_status' => '1', 'position_id' => $position_id));
            // echo $prev_count; exit;
            if ($prev_count == 0) {
                // $data_arr = array('ans'=>'ok');        
                // echo json_encode($data_arr);
                $arr_response['status'] = false;
            } else {
                $arr_response['status'] = true;
                // $str='The entered mobile no already exist';
                // $data_arr=array('ans'=>'exists','output'=>$str);       
                // echo json_encode($data_arr);
            }
        } else {
            $arr_response['status'] = false;
        }
        // echo "here";
        // print_r($arr_response); exit;
        echo json_encode($arr_response);
    }

    public function pannoduplication()
    {
        $pan_no = $_POST['pan_no'];
        $position_id = $_POST['position_id'];
        if ($pan_no != "") {
            $this->db->where(" '2023-01-01' < submit_date"); // added condition by priyank D. on 22-nov-22 to skip old entries
            $prev_count = $this->master_model->getRecordCount('careers_registration', array('pan_no' => $pan_no, 'active_status' => '1', 'position_id' => $position_id));
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok');
                echo json_encode($data_arr);
            } else {
                $str = 'The entered PAN No already exist';
                $data_arr = array('ans' => 'exists', 'output' => $str);
                echo json_encode($data_arr);
            }
        } else {
            //echo 'error';
            $data_arr = array('ans' => 'ok');
            echo json_encode($data_arr);
        }
    }

    public function checkpin()
    {
        $statecode = $_POST['statecode'];
        $pincode = $_POST['pincode'];
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
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

    public function checkpin_pr()
    {
        $statecode_pr = $_POST['statecode_pr'];
        $pincode_pr = $_POST['pincode_pr'];
        if ($statecode_pr != "") {
            $this->db->where("$pincode_pr BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode_pr));
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

    public function make_payment_old()
    {

        ////check temp file uploaded or not////
        $images_flag = 0;
        if (!file_exists("uploads/photograph/" . $this->session->userdata['enduserinfo']['photoname'])) {
            $images_flag = 1;
        }
        if (!file_exists("uploads/scansignature/" . $this->session->userdata['enduserinfo']['signname'])) {
            $images_flag = 1;
        }
        if ($images_flag) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Careers/');
        }

        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt = $sgst_amt = $igst_amt = '';
        $cs_total = $igst_total = '';
        $getstate = $getcenter = $getfees = array();
        $flag = 1;
        // TO do:
        // Validate reg no in DB
        //$_REQUEST['regno'] = "ODExODU5OTE1";
        //$regno = base64_decode($_REQUEST['regno']);
        $regno = $this->session->userdata['memberdata']['regno'];
        if (!empty($regno)) {
            $member_data = $this->Master_model->getRecords('member_registration', array('regid' => $regno, 'isactive' => '0'), array('state_pr', 'fee'));
        }

        $valcookie = register_get_cookie();

        if ($valcookie) {
            $regid = $valcookie;
            //$regid= '57';
            $checkuser = $this->master_model->getRecords('member_registration', array('regid' => $regno, 'regnumber !=' => '', 'isactive !=' => '0'));
            if (count($checkuser) > 0) {
                delete_cookie('regid');
                redirect('http://iibf.org.in');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array('ref_id' => $regno, 'status' => '2'));
                if (count($checkpayment) > 0) {
                    ///$datearr=explode(' ',$checkpayment[0]['date']);
                    $endTime = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
                    $current_time = date("Y-m-d H:i:s");
                    if (strtotime($current_time) <= strtotime($endTime)) {
                        $flag = 0;
                    } else {
                        delete_cookie('regid');
                        redirect('http://iibf.org.in');
                    }
                } else {
                    $flag = 1;
                    delete_cookie('regid');
                    redirect('http://iibf.org.in');
                }
            }
        }

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            //setting cookie for tracking multiple payment scenario
            register_set_cookie($regno);

            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $merchIdVal = $this->config->item('sbi_merchIdVal');
            $AggregatorId = $this->config->item('sbi_AggregatorId');

            $pg_success_url = base_url() . "Register/sbitranssuccess";
            $pg_fail_url    = base_url() . "Register/sbitransfail";

            //$amount = $this->config->item('member_reg_fee');
            //============addedd by tejasvi
            $state = $member_data[0]['state_pr'];
            $fee = $member_data[0]['fee'];
            if (!empty($state)) {
                if ($state == 'MAH') {
                    $amount = $this->config->item('cs_total');
                }
                /*else if($state == 'JAM')
            {
            $amount = $this->config->item('fee_amt');
                    }*/ else {
                    $amount = $this->config->item('igst_total');
                }
            }
            //$MerchantOrderNo = generate_order_id("reg_sbi_order_id");

            // Create transaction
            $insert_data = array(
                'gateway'     => "sbiepay",
                'amount'      => $amount,
                'date'        => date('Y-m-d H:i:s'),
                'ref_id'      =>  $regno,
                'description' => "Membership Registration",
                'pay_type'    => 1,
                'status'      => 2,
                //'receipt_no'  => $MerchantOrderNo,
                'pg_flag' => 'iibfregn',
                //'pg_other_details'=>$custom_field
            );

            $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

            $log_title = "Ordinory member payment transaction insert:" . $pt_id;
            $log_message = serialize($insert_data);
            $rId = $pt_id;
            $regNo = $regno;
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            $MerchantOrderNo = reg_sbi_order_id($pt_id);


            //Member registration
            //Ref1 = primary key of member registation table
            //Ref2 = iibfregn
            //Ref3 = primary key of member registation table
            //Ref4 = orderid  For below string
            $custom_field = $regno . "^iibfregn^" . $regno . "^" . $MerchantOrderNo;

            // update receipt no. in payment transaction -
            $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));


            //get value for invoice details [Tejasvi]
            if (!empty($state)) {
                //get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));
            }
            if ($state == 'MAH') {
                //set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                //set an amount as per rate
                $cgst_amt = $this->config->item('cgst_amt');
                $sgst_amt = $this->config->item('sgst_amt');
                //set an total amount
                $cs_total = $amount;
                $tax_type = 'Intra';
            }
            /*else if($state == 'JAM')
          {
                    //set a rate (e.g 9%,9% or 18%)
                    $cgst_rate=$sgst_rate=$igst_rate='';    
                    $cgst_amt=$sgst_amt=$igst_amt='';   
                    $igst_total=$amount; 
                    $tax_type='Inter';
                }*/ else {
                $igst_rate = $this->config->item('igst_rate');
                $igst_amt = $this->config->item('igst_amt');
                $igst_total = $amount;
                $tax_type = 'Inter';
            }

            /*if($getstate[0]['exempt']=='E')
          {
          $cgst_rate=$sgst_rate=$igst_rate='';  
          $cgst_amt=$sgst_amt=$igst_amt=''; 
                }*/

            $invoice_insert_array = array(
                'pay_txn_id' => $pt_id,
                'receipt_no' => $MerchantOrderNo,
                'member_no' => $regno,
                'state_of_center' => $state,
                'app_type' => 'R',
                'service_code' => $this->config->item('reg_service_code'),
                'qty' => '1',
                'state_code' => $getstate[0]['state_no'],
                'state_name' => $getstate[0]['state_name'],
                'tax_type' => $tax_type,
                'fee_amt' => $this->config->item('fee_amt'),
                'cgst_rate' => $cgst_rate,
                'cgst_amt' => $cgst_amt,
                'sgst_rate' => $sgst_rate,
                'sgst_amt' => $sgst_amt,
                'igst_rate' => $igst_rate,
                'igst_amt' => $igst_amt,
                'cs_total' => $cs_total,
                'igst_total' => $igst_total,
                'gstin_no' => '',
                'exempt' => $getstate[0]['exempt'],
                'created_on' => date('Y-m-d H:i:s')
            );

            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

            $log_title = "Ordinory membership exam invoice insert" . $regno;
            $log_message = serialize($invoice_insert_array);
            $rId = $regno;
            $regNo = $regno;
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            $MerchantCustomerID = $regno;

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
            $EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";

            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();

            $EncryptTrans = $aes->encrypt($EncryptTrans);

            $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
            $this->load->view('pg_sbi_form', $data);
        } else {
            //$data["regno"] = $_REQUEST['regno'];
            $this->load->view('pg_sbi/make_payment_page');
        }
    }

    public function sbitranssuccess()
    {
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData = $aes->decrypt($_REQUEST['encData']);
            $responsedata = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath = $invoiceNumber = '';
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
                if ($q_details[2] == "SUCCESS") {
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status,id');
                    //check user payment status is updated by s2s or not
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                        $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
                        if ($this->db->affected_rows()) {
                            $reg_id = $get_user_regnum_info[0]['ref_id'];
                            //$applicationNo = generate_mem_reg_num();
                            $applicationNo = generate_O_memreg($reg_id);
                            #####update member number in payment transaction####
                            $update_data = array('member_regnumber' => $applicationNo);
                            $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

                            if (count($get_user_regnum_info) > 0) {
                                $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
                                $this->master_model->updateRecord('member_registration', $update_mem_data, array('regid' => $reg_id));

                                $user_info = $this->master_model->getRecords('member_registration', array('regid' => $reg_id), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile');

                                ########get Old image Name############
                                $log_title = "Ordinory member OLD Image :" . $reg_id;
                                $log_message = serialize($user_info);
                                $rId = $reg_id;
                                $regNo = $reg_id;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);


                                $upd_files = array();
                                $photo_file = 'p_' . $applicationNo . '.jpg';
                                $sign_file = 's_' . $applicationNo . '.jpg';
                                $proof_file = 'pr_' . $applicationNo . '.jpg';

                                if (@rename("./uploads/photograph/" . $user_info[0]['scannedphoto'], "./uploads/photograph/" . $photo_file)) {
                                    $upd_files['scannedphoto'] = $photo_file;
                                }

                                if (@rename("./uploads/scansignature/" . $user_info[0]['scannedsignaturephoto'], "./uploads/scansignature/" . $sign_file)) {
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                }

                                if (@rename("./uploads/idproof/" . $user_info[0]['idproofphoto'], "./uploads/idproof/" . $proof_file)) {
                                    $upd_files['idproofphoto'] = $proof_file;
                                }

                                if (count($upd_files) > 0) {
                                    $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                                    $log_title = "Ordinory member PIC update :" . $reg_id;
                                    $log_message = serialize($upd_files);
                                    $rId = $reg_id;
                                    $regNo = $reg_id;
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                } else {
                                    $upd_files['scannedphoto'] = $photo_file;
                                    $upd_files['scannedsignaturephoto'] = $sign_file;
                                    $upd_files['idproofphoto'] = $proof_file;
                                    $this->master_model->updateRecord('member_registration', $upd_files, array('regid' => $reg_id));
                                    $log_title = "Member MANUAL PICS Update :" . $reg_id;
                                    $log_message = serialize($upd_files);
                                    $rId = $reg_id;
                                    $regNo = $reg_id;
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                }
                            }

                            //email to user
                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                            if (count($emailerstr) > 0) {
                                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                                $key = $this->config->item('pass_key');
                                $aes = new CryptAES();
                                $aes->set_key(base64_decode($key));
                                $aes->require_pkcs5();
                                //$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
                                $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
                                //$decpass = $aes->decrypt($user_info[0]['usrpassword']);
                                $newstring = str_replace("#application_num#", "" . $applicationNo . "",  $emailerstr[0]['emailer_text']);
                                $final_str = str_replace("#password#", "" . $decpass . "",  $newstring);
                                $info_arr = array(
                                    'to' => $user_info[0]['email'],
                                    //'to'=>'kumartupe@gmail.com',
                                    'from' => $emailerstr[0]['from'],
                                    'subject' => $emailerstr[0]['subject'],
                                    'message' => $final_str
                                );

                                //get invoice 
                                $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                //echo $this->db->last_query();exit;
                                if (count($getinvoice_number) > 0) {
                                    /*if($getinvoice_number[0]['state_of_center']=='JAM')
                      {
                      $invoiceNumber = generate_registration_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                      if($invoiceNumber)
                      {
                      $invoiceNumber=$this->config->item('mem_invoice_no_prefix_jammu').$invoiceNumber;
                      }
                                        }*/
                                    //else
                                    //{
                                    $invoiceNumber = generate_registration_invoice_number($getinvoice_number[0]['invoice_id']);
                                    if ($invoiceNumber) {
                                        $invoiceNumber = $this->config->item('mem_invoice_no_prefix') . $invoiceNumber;
                                    }
                                    //}

                                    $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                    $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                    $attachpath = genarate_reg_invoice($getinvoice_number[0]['invoice_id']);
                                }


                                if ($attachpath != '') {
                                    $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "",  $emailerstr[0]['sms_text']);
                                    $sms_final_str = str_replace("#password#", "" . $decpass . "",  $sms_newstring);
                                    //$this->master_model->send_sms($user_info[0]['mobile'],$sms_final_str);
                                    //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']),$sms_final_str,'DPDoOIwMR');
                                    $this->master_model->send_sms_common_all($user_info[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender']); // Added on 15 Sep 2023

                                    //if($this->Emailsending->mailsend($info_arr))
                                    if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                        redirect(base_url() . 'Register/acknowledge/');
                                    } else {
                                        redirect(base_url() . 'Register/acknowledge/');
                                    }
                                } else {
                                    redirect(base_url() . 'Register/acknowledge/');
                                }
                            }
                        }
                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                    }
                }
            }
            ///End of SBI B2B callback 
            redirect(base_url() . 'Register/acknowledge/');
        } else {
            die("Please try again...");
        }
    }

    public function sbitransfail()
    {
        delete_cookie('regid');
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData = $aes->decrypt($_REQUEST['encData']);
            $responsedata = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

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


                $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            //Sbi fail code without callback
            echo "Transaction failed";

            echo "
        <script>
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
                </script>
        ";

            exit;
            /*  $this->load->model('log_model');
          include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
          $key = $this->config->item('sbi_m_key');
          $aes = new CryptAES();
          $aes->set_key(base64_decode($key));
          $aes->require_pkcs5();
          $merchIdVal = $_REQUEST['merchIdVal'];
          $Bank_Code = $_REQUEST['Bank_Code'];
          $encData = $aes->decrypt($_REQUEST['encData']);
          $responsedata = explode("|",$encData);
          $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
                $transaction_no  = $responsedata[1];*/

            //SBI Callback Code
            /*$update_data = array('transaction_no' => $transaction_no,'status' => 0,'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0399', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5]);
                $this->master_model->updateRecord('payment_transaction',$update_data,array('receipt_no'=>$MerchantOrderNo));*/
            //END of SBI Callback code          

            //print_r($responsedata);  // Payment gateway response
            // TO DO : Redirect to user acknowledge page

        } else {
            die("Please try again...");
        }
    }

    public function check_img_valid()
    {
        $data['response'] = '';
        if (isset($_FILES) && count($_FILES) > 0) {
            if ($_FILES['chk_img']['name'] != "") {
                $chk_img = $this->upload_file("chk_img", array('jpg', 'jpeg'), "chk_img" . date("YmdHis"), "./uploads/rahultest", "jpeg|jpg", '20');
                //print_r($chk_img); exit;
                if ($chk_img['response'] == 'error') {
                    $data['response'] = $chk_img['message'];
                } else if ($chk_img['response'] == 'success') {
                    $data['response'] = '<p>valid image</p>';
                    @unlink("./uploads/rahultest/" . $chk_img['message']);
                }
            }
        }

        $this->load->view('careers/check_img_valid', $data);
    }

    function upload_file($input_name, $valid_arr, $new_file_name, $upload_path, $allowed_types, $max_size_in_kb)
    {
        $flag = 0;
        $path_img = $_FILES[$input_name]['name'];
        $ext_img = pathinfo($path_img, PATHINFO_EXTENSION);

        $valid_ext_arr = $valid_arr;

        if (count($valid_arr) > 0 && $valid_arr != "") {
            if (!in_array(strtolower($ext_img), $valid_ext_arr)) {
                $flag = 1;
            }
        }

        if ($flag == 0) {
            if (is_dir($upload_path)) {
            } else {
                $dir = mkdir($upload_path, 0755);

                $myfile = fopen($upload_path . "/index.php", "w") or die("Unable to open file!");
                $txt = "";
                fwrite($myfile, $txt);
                fclose($myfile);
            }

            $file = $_FILES;
            $_FILES['file_upload']['name'] = $file[$input_name]['name'];
            $filename = $new_file_name;
            $path = $_FILES['file_upload']['name'];
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $final_img = $filename . "." . $ext;

            $config['file_name']     = $filename;
            $config['upload_path']   = $upload_path;
            if ($allowed_types != "") {
                $config['allowed_types'] = $allowed_types;
            } else {
                $config['allowed_types'] = '*';
            }
            $config['max_size'] = $max_size_in_kb;
            /* $config['max_width'] = 1024;
                $config['max_height'] = 768; */

            $this->upload->initialize($config);

            $_FILES['file_upload']['type'] = $file[$input_name]['type'];
            $_FILES['file_upload']['tmp_name'] = $file[$input_name]['tmp_name'];
            $_FILES['file_upload']['error'] = $file[$input_name]['error'];
            $_FILES['file_upload']['size'] = $file[$input_name]['size'];

            if ($this->upload->do_upload('file_upload')) {
                $data = $this->upload->data();
                return array('response' => 'success', 'message' => $final_img);
            } else {
                return array('response' => 'error', 'message' => $this->upload->display_errors());
            }
        } else {
            return array('response' => 'error', 'message' => "Please upload valid " . $allowed_types . " extension image.");
        }
    }
}
