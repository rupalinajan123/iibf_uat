<?php
//ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);  
defined('BASEPATH') or exit('No direct script access allowed');
class Dbf extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->model('master_model');
        $this->load->library('email');
        $this->load->model('chk_session');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('KYC_Log_model');
		$this->load->model('billdesk_pg_model');
        $this->load->model('refund_after_capacity_full');
        $this->chk_session->chk_dbf_member_session();
        $this->chk_session->Check_mult_session();
        /*if($this->router->fetch_method()!='comApplication' && $this->router->fetch_method()!='preview' && $this->router->fetch_method()!='Msuccess' && $this->router->fetch_method()!='details' && $this->router->fetch_method()!='editmobile' && $this->router->fetch_method()!='editemailduplication')
        {*/
        if ($this->router->fetch_method() != 'comApplication' && $this->router->fetch_method() != 'preview' && $this->router->fetch_method() != 'Msuccess' && $this->router->fetch_method() != 'editmobile' && $this->router->fetch_method() != 'editemailduplication' && $this->router->fetch_method() != 'setExamSession' && $this->router->fetch_method() != 'details' && $this->router->fetch_method() != 'applydetails' && $this->router->fetch_method() != 'saveexam' && $this->router->fetch_method() != 'exampdf' && $this->router->fetch_method() != 'printexamdetails' && $this->router->fetch_method() != 'sbi_make_payment' && $this->router->fetch_method() != 'sbitranssuccess' && $this->router->fetch_method() != 'sbitransfail' && $this->router->fetch_method() != 'check_emailduplication' && $this->router->fetch_method() != 'check_mobileduplication' && $this->router->fetch_method() != 'check_checkpin' && $this->router->fetch_method() != 'refund' && $this->router->fetch_method() != 'getsub_menue' && $this->router->fetch_method() != 'set_dbf_elsub_cnt' &&  $this->router->fetch_method() != 'handle_billdesk_response' && $this->router->fetch_method() != 'getsetAsFresherOrOld') // priyanka d- 10-feb-23 >> added handle_billdesk_response 
        {
            if ($this->session->userdata('examinfo')) {
                $this->session->unset_userdata('examinfo');
            }
            if ($this->session->userdata('examcode')) {
                $this->session->unset_userdata('examcode');
            }
        }
         /* if ($this->get_client_ip() != '115.124.115.69') {
				  		echo "We are experiencing technical issues.Service will be resumed shortly.";
							die();
				  } */
    }
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        redirect(base_url() . 'Dbf/dashboard');
    }
    public function applicationForm()
    {
        $this->load->view('application_block');
    }
    ##------------------ Member dashboard (PRAFULL)---------------##
    public function dashboard()
    {

        /*    $examcode=$this->input->get('ExId');
        $flag=1;
        $checkqualifyflag=0;
        if($examcode!='')
        {
        $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>base64_decode($examcode)));
        if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
        {
        $qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam1'],$examcode,$check_qualify_exam[0]['qualifying_part1']);
        $flag=$qaulifyarry['flag'];
        $message=$qaulifyarry['message'];
        if($flag==0)
        {
        $checkqualifyflag=1;
        }
        }
        if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
        {
        $qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam2'],$examcode,$check_qualify_exam[0]['qualifying_part2']);
        $flag=$qaulifyarry['flag'];
        $message=$qaulifyarry['message'];
        if($flag==0)
        {
        $checkqualifyflag=1;
        }
        }
        if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
        {
        $qaulifyarry=$this->checkqualify($check_qualify_exam[0]['qualifying_exam3'],$examcode,$check_qualify_exam[0]['qualifying_part3']);
        $flag=$qaulifyarry['flag'];
        $message=$qaulifyarry['message'];
        if($flag==0)
        {
        $checkqualifyflag=1;
        }
        }
        if($checkqualifyflag==0)
        {
        $check=$this->examapplied($this->session->userdata('nmregnumber'),$examcode);
        if($check)
        {
        $message='You have already applied for the examination';
        $flag=0;
        }else
        {
        $check_date=$this->examdate($this->session->userdata('nmregnumber'),$examcode);
        if($check_date)
        {
        $message=$this->get_alredy_applied_examname($this->session->userdata('nmregnumber'),$examcode);
        $flag=0;
        }
        }
        }
        }
        if($flag==1 && $checkqualifyflag==0)
        {
        $data=array('middle_content'=>'Dbf/dashboard');
        $this->load->view('nonmember/nm_common_view',$data);
        }
        else
        {
        $data=array('middle_content'=>'nonmember/not_eligible','check_eligibility'=>$message);
        $this->load->view('nonmember/nm_common_view',$data);
        }*/
        $data = array('middle_content' => 'dbf/dashboard');
        $this->load->view('dbf/dbf_common_view', $data);
    }

    ##------------------ Member dashboard (PRAFULL)---------------##
    public function showexam()
    {
        //accedd denied due to GST
        //$this->master_model->warning();

        $examcode         = $this->input->get('ExId');
        $flag             = 1;
        $checkqualifyflag = 0;
        if ($examcode != '') {
            $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => base64_decode($examcode)));
            if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $examcode, $check_qualify_exam[0]['qualifying_part1']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $examcode, $check_qualify_exam[0]['qualifying_part2']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $examcode, $check_qualify_exam[0]['qualifying_part3']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            if ($checkqualifyflag == 0) {
                $check = $this->examapplied($this->session->userdata('dbregnumber'), $examcode);
                if ($check) {
                    /*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($examcode),'misc_master.misc_delete'=>'0'),'exam_month');
                    $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
                    $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
                    $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/

                    $message = 'You have already applied for the examination';
                    $flag    = 0;
                } else {
                    $check_date = $this->examdate($this->session->userdata('dbregnumber'), $examcode);
                    if ($check_date) {
                        $message = $this->get_alredy_applied_examname($this->session->userdata('dbregnumber'), $examcode);
                        $flag    = 0;
                    }
                }
            }
        }
        if ($flag == 1 && $checkqualifyflag == 0) {
            redirect(base_url() . 'Dbf/examdetails/?excode2=' . $examcode);
        } else {
            $data = array('middle_content' => 'dbf/not_eligible', 'check_eligibility' => $message);
            $this->load->view('dbf/dbf_common_view', $data);
        }
    }

    // ##---------End user profile (prafull)-----------##
    public function profile()
    {
        $kycflag   = 0;
        $prevData  = array();
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')));
        if (count($user_info)) {
            $prevData = $user_info[0];
        }

        #---images ---#
        $kyc_update_data = array();
        $kyc_edit_flag   = 0;
        $flag            = 1;
        $member_info     = $this->master_model->getRecords('member_registration', array(
            'regid' => $this->session->userdata('dbregid'),
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto');
        $applicationNo = $this->session->userdata('dbregnumber');
        #---images end ---#

        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = '';

        $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

        if (isset($_POST['btnSubmit'])) {
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required');
            //$this->form_validation->set_rules('nameoncard','Name as to appear on Card','trim|max_length[35]|required');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required');
            $this->form_validation->set_rules('idNo', 'ID No.', 'trim|required|max_length[25]|xss_clean');

            /* Benchmark Disability Code - Bhushan */
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';

            $this->form_validation->set_rules('benchmark_disability', 'Person with Benchmark Disability', 'trim|required');

            /*if(isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y'){
            $this->form_validation->set_rules('scanned_vis_imp_cert','Visually impaired Attach scan copy of PWD certificate','required');
            }
            if(isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y'){
            $this->form_validation->set_rules('scanned_orth_han_cert','Orthopedically handicapped Attach scan copy of PWD certificate','required');
            }
            if(isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y'){
            $this->form_validation->set_rules('scanned_cer_palsy_cert','Cerebral palsy Attach scan copy of PWD certificate','required');
            }*/
            /* Close Benchmark Disability Code - Bhushan */

            if ($this->input->post("aadhar_card") != '') {
                //$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('dbregid').'.registrationtype.'.$this->session->userdata('memtype').']');
                $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $this->session->userdata('dbregid') . '.registrationtype.' . $this->session->userdata('memtype') . ']');

            }
            //$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required|max_length[25]|xss_clean');
            //$this->form_validation->set_rules('idproof','Id Proof','trim|required');
            //$this->form_validation->set_rules('dob','Date of Birth','trim|required');
            //$this->form_validation->set_rules('gender','Gender','trim|required');
            //$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[member_registration.email.regid.'.$this->session->userdata('nmregid').']|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_validunique[member_registration.email.regid.' . $this->session->userdata('dbregid') . '.isactive.1.registrationtype.' . $this->session->userdata('memtype') . ']|xss_clean');

            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
            $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required');
            if ($_POST['optedu'] == 'U') {
                $this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required');
            } else if ($_POST['optedu'] == 'G') {
                $this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required');
            } else if ($_POST['optedu'] == 'P') {
                $this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required');
            }

            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]');
            }

            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]');
            }

            // -----images-----#
            $image_info = array();
            $image_info = $this->master_model->getRecords('member_registration', array(
                'regid' => $this->session->userdata('dbregid'),
            ));
            //  print_r($image_info);exit;
            // get photo path
            $oldfilepath_photo = get_img_name($this->session->userdata('dbregnumber'), 'p');
            if ($oldfilepath_photo != '') {
                $p = '';
                $p = strpos($oldfilepath_photo, $this->session->userdata('dbregnumber'));
                if ($p == '') {
                    $oldfilepath_photo = '';

                }
                if ($oldfilepath_photo == '') {
                    if (!empty($image_info)) {

                        $oldfilepath_photo = 'uploads/' . $image_info[0]['image_path'] . 'photo/p_' . $image_info[0]['reg_no'] . '.jpg';
                        //echo $oldfilepath_photo;exit;
                        if (!file_exists($oldfilepath_photo)) {

                            $oldfilepath_photo = '';
                        }
                    }
                }
            }
            // get sign path
            $oldfilepath_s = get_img_name($this->session->userdata('dbregnumber'), 's');
            //echo $oldfilepath_s;exit;
            if ($oldfilepath_s != '') {

                $s = '';
                $s = strpos($oldfilepath_s, $this->session->userdata('dbregnumber'));
                if ($s == '') {
                    $oldfilepath_s = '';

                }
                if ($oldfilepath_s == '') {
                    if (!empty($image_info)) {

                        $oldfilepath_s = 'uploads/' . $image_info[0]['image_path'] . 'signature/s_' . $image_info[0]['reg_no'] . '.jpg';
                        //echo $oldfilepath_photo;exit;
                        if (!file_exists($oldfilepath_s)) {

                            $oldfilepath_s = '';
                        }
                    }
                }
            }

            // get idproof path
            $oldfilepath_pr = get_img_name($this->session->userdata('dbregnumber'), 'pr');

            if ($oldfilepath_pr != '') {
                $pr = '';
                $pr = strpos($oldfilepath_pr, $this->session->userdata('dbregnumber'));
                if ($pr == '') {
                    $oldfilepath_pr = '';

                }
                //        echo $oldfilepath_pr;exit;
                if ($oldfilepath_pr == '') {
                    if (!empty($image_info)) {
                        $oldfilepath_pr = 'uploads/' . $image_info[0]['image_path'] . 'idproof/s_' . $image_info[0]['reg_no'] . '.jpg';
                        //echo $oldfilepath_photo;exit;
                        if (!file_exists($oldfilepath_pr)) {

                            $oldfilepath_pr = '';
                        }
                    }

                }
            }
            //exit;
            if (!file_exists($oldfilepath_photo)) {
                if (isset($_FILES['scannedphoto']['name'])) {
                    if ($_FILES['scannedphoto']['name'] == '') {
                        $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
                    }
                }
            }
            if (!file_exists($oldfilepath_s)) {
                if (isset($_FILES['scannedsignaturephoto']['name'])) {
                    //echo $_FILES['scannedsignaturephoto']['name'];exit;
                    if ($_FILES['scannedsignaturephoto']['name'] == '') {
                        $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
                    }
                }
            }
            if (!file_exists($oldfilepath_pr)) {
                if (isset($_FILES['idproofphoto']['name'])) {
                    if ($_FILES['idproofphoto']['name'] == '') {
                        $this->form_validation->set_rules('idproofphoto', 'id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
                    }
                }
            }
            // -----image----#

            //$this->form_validation->set_rules('scannedphoto1_hidd','Uploaded Photo','trim|required');
            //$this->form_validation->set_rules('scannedsignaturephoto1_hidd','uploaded Signature','trim|required');
            //$this->form_validation->set_rules('idproofphoto1_hidd','Uploaded ID Proof','trim|required');

            if ($this->form_validation->run() == true) {
                $addressline1 = strtoupper($this->input->post('addressline1'));
                $addressline2 = strtoupper($this->input->post('addressline2'));
                $addressline3 = strtoupper($this->input->post('addressline3'));
                $addressline4 = strtoupper($this->input->post('addressline4'));
                $district     = strtoupper($this->input->post('district'));
                $city         = strtoupper($this->input->post('city'));
                $state        = $this->input->post('state');
                $pincode      = $this->input->post('pincode');
                $gender       = $this->input->post('gender');
                $dob          = $this->input->post('dob1');
                //$gender =$this->input->post('gender');
                $optedu = $this->input->post('optedu');
                if ($optedu == 'U') {
                    $specify_qualification = $this->input->post('eduqual1');
                } elseif ($optedu == 'G') {
                    $specify_qualification = $this->input->post('eduqual2');
                } else if ($optedu == 'P') {
                    $specify_qualification = $this->input->post('eduqual3');
                }

                /*$dob1 = $this->input->post('dob');
                $dob = str_replace('/','-',$dob1);*/

                $email        = $this->input->post('email');
                $stdcode      = $this->input->post('stdcode');
                $phone        = $this->input->post('phone');
                $mobile       = $this->input->post('mobile');
                $idproof      = $this->input->post('idproof');
                $optnletter   = 'N';
                $firstname    = $this->input->post("firstname");
                $nameoncard   = $this->input->post("nameoncard");
                $declaration1 = $this->input->post("declaration1");
                $idNo         = $this->input->post("idNo");
                $aadhar_card  = $this->input->post("aadhar_card");
                $sel_namesub  = $this->input->post("sel_namesub");
                $middlename   = $this->input->post("middlename");
                $lastname     = $this->input->post("lastname");

                /* Benchmark Disability Code - Bhushan */

                $date = date('Y-m-d h:i:s');

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array('upload_path' => './uploads/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/disability/" . $scanned_vis_imp_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /* Orthopedically handicapped certificate */
                $input_orth_han_cert = $_POST["hidden_orth_han_cert"];
                if (isset($_FILES['scanned_orth_han_cert']['name']) && ($_FILES['scanned_orth_han_cert']['name'] != '')) {
                    $img          = "scanned_orth_han_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'orth_han_cert_' . $tmp_nm;
                    $config       = array('upload_path' => './uploads/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/disability/" . $scanned_orth_han_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                /* Cerebral palsy certificate */
                $input_cer_palsy_cert = $_POST["hidden_cer_palsy_cert"];
                if (isset($_FILES['scanned_cer_palsy_cert']['name']) && ($_FILES['scanned_cer_palsy_cert']['name'] != '')) {
                    $img          = "scanned_cer_palsy_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'cer_palsy_cert_' . $tmp_nm;
                    $config       = array('upload_path' => './uploads/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                $benchmark_disability       = $this->input->post("benchmark_disability");
                $scanned_vis_imp_cert       = $output_vis_imp_cert1;
                $vis_imp_cert_name          = $scanned_vis_imp_cert_file;
                $scanned_orth_han_cert      = $output_orth_han_cert1;
                $orth_han_cert_name         = $scanned_orth_han_cert_file;
                $scanned_cer_palsy_cert     = $output_cer_palsy_cert1;
                $cer_palsy_cert_name        = $scanned_cer_palsy_cert_file;
                $visually_impaired          = $this->input->post("visually_impaired");
                $orthopedically_handicapped = $this->input->post("orthopedically_handicapped");
                $cerebral_palsy             = $this->input->post("cerebral_palsy");
                /* Close Benchmark Disability Code - Bhushan */

                // ----images ---#
                $prev_edited_on     = '';
                $prev_photo_flg     = "N";
                $prev_signature_flg = "N";
                $prev_id_flg        = "N";
                $prev_edited_on_qry = $this->master_model->getRecords('member_registration', array(
                    'regid' => $this->session->userdata('dbregid'),
                ), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
                if (count($prev_edited_on_qry)) {
                    $prev_edited_on     = $prev_edited_on_qry[0]['images_editedon'];
                    $prev_photo_flg     = $prev_edited_on_qry[0]['photo_flg'];
                    $prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
                    $prev_id_flg        = $prev_edited_on_qry[0]['id_flg'];
                    if ($prev_edited_on != date('Y-m-d')) {
                        $this->master_model->updateRecord('member_registration', array(
                            'photo_flg'     => 'N',
                            'signature_flg' => 'N',
                            'id_flg'        => 'N',
                        ), array(
                            'regid' => $this->session->userdata('dbregid'),
                        ));
                    }
                }
                $date              = date('Y-m-d h:i:s');
                $scannedphoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $photo_flg = 'N';
                } else {
                    $photo_flg = $prev_photo_flg;
                }
                $edited = '';
                if (!file_exists($oldfilepath_photo)) {
                    if (isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name'] != '') {
                        @unlink('uploads/photograph/' . $member_info[0]['scannedphoto']);
                        $path = "./uploads/photograph";
                        // $new_filename = 'photo_'.strtotime($date).rand(1,99999);
                        $new_filename = 'p_' . $applicationNo;
                        $uploadData   = upload_file('scannedphoto', $path, $new_filename, '', '', true);
                        if ($uploadData) {
                            $kyc_edit_flag                       = 1;
                            $kyc_update_data['edited_mem_photo'] = 1;
                            // Overwrites file so no need to unlink
                            // @unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
                            $scannedphoto_file = $uploadData['file_name'];
                            $photo_flg         = 'Y';
                            $edited .= 'PHOTO || ';
                        } else {
                            $flag              = 0;
                            $scannedphoto_file = $this->input->post('scannedphoto1_hidd');
                        }
                    } else {
                        $scannedphoto_file = $this->input->post('scannedphoto1_hidd');
                    }
                } else {
                    $scannedphoto_file = $this->input->post('scannedphoto1_hidd');
                }
                // Upload DOB Proof
                $scannedsignaturephoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $signature_flg = 'N';
                } else {
                    $signature_flg = $prev_signature_flg;
                }
                if (!file_exists($oldfilepath_s)) {
                    if (isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name'] != '') {
                        @unlink('uploads/photograph/' . $member_info[0]['scannedsignaturephoto']);
                        $path = "./uploads/scansignature";
                        // $new_filename = 'sign_'.strtotime($date).rand(1,99999);
                        $new_filename = 's_' . $applicationNo;
                        $uploadData   = upload_file('scannedsignaturephoto', $path, $new_filename, '', '', true);
                        if ($uploadData) {
                            $kyc_edit_flag                      = 1;
                            $kyc_update_data['edited_mem_sign'] = 1;
                            $scannedsignaturephoto_file         = $uploadData['file_name'];
                            $signature_flg                      = 'Y';
                            $edited .= 'SIGNATURE || ';
                        } else {
                            $flag                       = 0;
                            $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
                        }
                    } else {
                        $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
                    }
                } else {
                    $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
                }
                // Upload Education Certificate
                $idproofphoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $id_flg = 'N';
                } else {
                    $id_flg = $prev_id_flg;
                }

//echo $oldfilepath_pr;exit;
                if (!file_exists($oldfilepath_pr)) {
// echo '***';
                    //echo $_FILES['idproofphoto']['name'];exit;
                    if (isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name'] != '') {
                        @unlink('uploads/photograph/' . $member_info[0]['idproofphoto']);
                        $path = "./uploads/idproof";
                        // $new_filename = 'idproof_'.strtotime($date).rand(1,99999);
                        $new_filename = 'pr_' . $applicationNo;
                        $uploadData   = upload_file('idproofphoto', $path, $new_filename, '', '', true);
                        if ($uploadData) {
                            $kyc_edit_flag                       = 1;
                            $kyc_update_data['edited_mem_proof'] = 1;
                            $idproofphoto_file                   = $uploadData['file_name'];
                            $id_flg                              = 'Y';
                            $edited .= 'PROOF || ';
                        } else {
                            $flag              = 0;
                            $idproofphoto_file = $this->input->post('idproofphoto1_hidd');
                        }
                    } else {
                        $idproofphoto_file = $this->input->post('idproofphoto1_hidd');
                    }
                } else {
                    $idproofphoto_file = $this->input->post('idproofphoto1_hidd');
                }
                if ($flag == 1) {
                    /*$update_info = array(
                    'scannedphoto'=>$scannedphoto_file,
                    'scannedsignaturephoto'=>$scannedsignaturephoto_file,
                    'idproofphoto'=>$idproofphoto_file,
                    'editedon'=>date('Y-m-d H:i:s'),
                    'photo_flg'=>$photo_flg,
                    'signature_flg'=>$signature_flg,
                    'id_flg'=>$id_flg,
                    'editedon'=>date('Y-m-d H:i:s'),
                    'editedby'=>'Candidate',
                    );*/
                    $update_data_image['scannedphoto']          = $scannedphoto_file;
                    $update_data_image['scannedsignaturephoto'] = $scannedsignaturephoto_file;
                    $update_data_image['idproofphoto']          = $idproofphoto_file;
                    $update_data_image['images_editedon']       = date('Y-m-d H:i:s');
                    $update_data_image['images_editedby']       = 'Candidate';
                    $update_data_image['photo_flg']             = $photo_flg;
                    $update_data_image['signature_flg']         = $signature_flg;
                    $update_data_image['id_flg']                = $id_flg;
                    $update_data_image['kyc_edit']              = $kyc_edit_flag;
                    $update_data_image['kyc_status']            = '0';
                    if (count($update_data_image)) {
                        if ($kyc_edit_flag == 1) {

                            if ($this->master_model->updateRecord('member_registration', $update_data_image, array(
                                'regid'     => $this->session->userdata('dbregid'),
                                'regnumber' => $this->session->userdata('dbregnumber'),
                            ))) {
                                $desc['updated_data'] = $update_data_image;
                                $desc['old_data']     = $member_info[0];
                                // logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));
                                /* User Log Activities : Bhushan */
                                $log_title   = "Member Edit Images";
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('dbregid');
                                $regNo       = $this->session->userdata('dbregnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */
                                $finalStr = '';
                                if ($edited != '') {
                                    $edit_data = trim($edited);
                                    $finalStr  = rtrim($edit_data, "||");
                                }
                                log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('dbregid'), $this->session->userdata('dbregnumber'));
                                if ($kyc_edit_flag == 1) {

                                    $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                        'regnumber' => $this->session->userdata('dbregnumber'),
                                    ), '', array(
                                        'kyc_id' => 'DESC',
                                    ), '0', '1');
                                    if (count($kycmemdetails) > 0) {
                                        $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                        $kyc_update_data['kyc_state']        = '2';
                                        $kyc_update_data['kyc_status']       = '0';
                                        $this->db->like('allotted_member_id', $this->session->userdata('dbregnumber'));
                                        $this->db->or_like('original_allotted_member_id', $this->session->userdata('dbregnumber'));
                                        $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                            'list_type' => 'New',
                                        ));
                                        if (count($check_duplicate_entry) > 0) {
                                            foreach ($check_duplicate_entry as $row) {
                                                $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('dbregnumber'));
                                                $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('dbregnumber'));
                                                $admin_update_data           = array(
                                                    'allotted_member_id'          => $allotted_member_id,
                                                    'original_allotted_member_id' => $original_allotted_member_id,
                                                );
                                                $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array(
                                                    'kyc_user_id' => $row['kyc_user_id'],
                                                ));
                                            }
                                        }
                                        // $kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
                                        if ($kycmemdetails[0]['kyc_status'] == '0') {
                                            $this->master_model->updateRecord('member_kyc', $kyc_update_data, array(
                                                'kyc_id' => $kycmemdetails[0]['kyc_id'],
                                            ));
                                            $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('dbregnumber'), serialize($desc));
                                        }
                                        // check membership count
                                        $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                            'member_number' => $this->session->userdata('dbregnumber'),
                                        ));
                                        if (count($check_membership_cnt) > 0) {
                                            // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                            /* update dowanload count 8-8-2017 */
                                            $this->master_model->updateRecord('member_idcard_cnt', array(
                                                'card_cnt' => '0',
                                            ), array(
                                                'member_number' => $this->session->userdata('dbregnumber'),
                                            ));
                                            /* Close update dowanload count */
                                            /* User Log Activities : Pooja */
                                            $uerlog = $this->master_model->getRecords('member_registration', array(
                                                'regnumber' => $this->session->userdata('dbregnumber'),
                                            ), 'regid,regnumber');
                                            $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                                'member_number' => $this->session->userdata('dbregnumber'),
                                            ));
                                            $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                            $log_message = serialize($user_info);
                                            $rId         = $uerlog[0]['regid'];
                                            $regNo       = $this->session->userdata('dbregnumber');
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                            /* Close User Log Actitives */
                                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                                'emailer_name' => 'user_register_email',
                                            ));
                                            if (count($emailerstr) > 0) {
                                                $member_info = $this->master_model->getRecords('member_registration', array(
                                                    'regid' => $this->session->userdata('dbregid'),
                                                ), 'email');
                                                $newstring = str_replace("#application_num#", "" . $this->session->userdata('dbregnumber') . "", $emailerstr[0]['emailer_text']);
                                                $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                                                $info_arr  = array(
                                                    'to'      => $member_info[0]['email'],
                                                    'from'    => $emailerstr[0]['from'],
                                                    'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('dbregnumber'),
                                                    'message' => $final_str,
                                                );
                                                if ($this->Emailsending->mailsend($info_arr)) {
//echo '***';
                                                    // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                                    redirect(base_url('Dbf/acknowledge/'));
                                                } else {
                                                    $this->session->set_flashdata('error', 'Error while sending email !!');
                                                    redirect(base_url('Dbf/profile/'));
                                                }
                                            } else {
                                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                                redirect(base_url('Dbf/profile/'));
                                            }
                                        }
                                    }
                                    // echo $this->db->last_query();exit;
                                    // change by pooja godse for  memebersgip id card  dowanload count reset
                                    // check membership count
                                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('dbregnumber'),
                                    ));
                                    if (count($check_membership_cnt) > 0) {
                                        // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                        /* update dowanload count 8-8-2017 */
                                        $this->master_model->updateRecord('member_idcard_cnt', array(
                                            'card_cnt' => '0',
                                        ), array(
                                            'member_number' => $this->session->userdata('dbregnumber'),
                                        ));
                                        /* User Log Activities : Pooja */
                                        $uerlog = $this->master_model->getRecords('member_registration', array(
                                            'regnumber' => $this->session->userdata('dbregnumber'),
                                        ), 'regid,regnumber');
                                        $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                            'member_number' => $this->session->userdata('dbregnumber'),
                                        ));
                                        $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                        $log_message = serialize($user_info);
                                        $rId         = $uerlog[0]['regid'];
                                        $regNo       = $this->session->userdata('dbregnumber');
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        /* Close User Log Actitives */
                                        /* Close update dowanload count */
                                    }
                                    // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                                    /* User Log Activities : Bhushan */
                                    $log_title   = "kyc member edited images id : " . $this->session->userdata('dbregid');
                                    $log_message = serialize($desc);
                                    $rId         = $this->session->userdata('dbregid');
                                    $regNo       = $this->session->userdata('dbregnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                }

                            }
                        }

                    } // print_r($update_data);
                    // $personalInfo = filter($personal_info);
                    // ---end images ----#
                    // Check if value is edited
                    $update_data = $kyc_update_data = array();
                    if (count($prevData)) {
                        if ($prevData['address1'] != $addressline1) {$update_data['address1'] = $addressline1;}

                        if ($prevData['address2'] != $addressline2) {$update_data['address2'] = $addressline2;}

                        if ($prevData['address3'] != $addressline3) {$update_data['address3'] = $addressline3;}

                        if ($prevData['address4'] != $addressline4) {$update_data['address4'] = $addressline4;}

                        if ($prevData['district'] != $district) {$update_data['district'] = $district;}

                        if ($prevData['city'] != $city) {$update_data['city'] = $city;}

                        if ($prevData['state'] != $state) {$update_data['state'] = $state;}

                        if ($prevData['pincode'] != $pincode) {$update_data['pincode'] = $pincode;}

                        if (date('Y-m-d', strtotime($prevData['dateofbirth'])) != date('Y-m-d', strtotime($dob)) && $dob != '') {
                            $update_data['dateofbirth']        = date('Y-m-d', strtotime($dob));
                            $update_data['kyc_edit']           = '1';
                            $update_data['kyc_status']         = '0';
                            $kycflag                           = 1;
                            $kyc_update_data['edited_mem_dob'] = 1;
                        }

                        if ($prevData['gender'] != $gender) {$update_data['gender'] = $gender;}

                        if ($prevData['qualification'] != $optedu) {$update_data['qualification'] = $optedu;}

                        if ($prevData['specify_qualification'] != $specify_qualification) {$update_data['specify_qualification'] = $specify_qualification;}

                        if ($prevData['email'] != $email) {$update_data['email'] = $email;}

                        if ($prevData['stdcode'] != $stdcode) {$update_data['stdcode'] = $stdcode;}

                        if ($prevData['office_phone'] != $phone) {$update_data['office_phone'] = $phone;}

                        if ($prevData['mobile'] != $mobile) {$update_data['mobile'] = $mobile;}

                        if ($prevData['idproof'] != $idproof) {$update_data['idproof'] = $idproof;}

                        if ($prevData['idNo'] != $idNo) {$update_data['idNo'] = $idNo;}

                        if ($prevData['aadhar_card'] != $aadhar_card) {$update_data['aadhar_card'] = $aadhar_card;}

                        if ($prevData['optnletter'] != $optnletter) {$update_data['optnletter'] = 'N';}

                        if ($prevData['namesub'] != $sel_namesub) {
                            $update_data['namesub']             = $sel_namesub;
                            $update_data['kyc_edit']            = '1';
                            $update_data['kyc_status']          = '0';
                            $kycflag                            = 1;
                            $kyc_update_data['edited_mem_name'] = 1;
                        }

                        if ($prevData['firstname'] != $firstname) {
                            $update_data['firstname']           = $firstname;
                            $update_data['kyc_edit']            = '1';
                            $update_data['kyc_status']          = '0';
                            $kycflag                            = 1;
                            $kyc_update_data['edited_mem_name'] = 1;
                        }

                        if ($prevData['middlename'] != $middlename) {
                            $update_data['middlename']          = $middlename;
                            $update_data['kyc_edit']            = '1';
                            $update_data['kyc_status']          = '0';
                            $kycflag                            = 1;
                            $kyc_update_data['edited_mem_name'] = 1;
                        }

                        if ($prevData['lastname'] != $lastname) {
                            $update_data['lastname']            = $lastname;
                            $update_data['kyc_edit']            = '1';
                            $update_data['kyc_status']          = '0';
                            $kycflag                            = 1;
                            $kyc_update_data['edited_mem_name'] = 1;
                        }

                        if ($prevData['displayname'] != $nameoncard) {$update_data['displayname'] = $nameoncard;}

                        /* Benchmark Disability Code - Bhushan */
                        if ($prevData['benchmark_disability'] != $benchmark_disability) {
                            $update_data['benchmark_disability'] = $benchmark_disability;
                        }
                        if ($prevData['visually_impaired'] != $visually_impaired) {
                            $update_data['visually_impaired'] = $visually_impaired;
                        }
                        if ($prevData['orthopedically_handicapped'] != $orthopedically_handicapped) {
                            $update_data['orthopedically_handicapped'] = $orthopedically_handicapped;
                        }
                        if ($prevData['cerebral_palsy'] != $cerebral_palsy) {
                            $update_data['cerebral_palsy'] = $cerebral_palsy;
                        }
                        /* Close Benchmark Disability Code - Bhushan */
                    }

                    $edited = array();
                    $edited = '';
                    //echo $kyc_edit_flag ;exit;
                    if (empty($update_data) && $kyc_edit_flag == 0) {
//echo '**';exit;
                        $this->session->set_flashdata('error', 'Change atleast one field');
                        redirect(base_url('Dbf/profile/'));
                    }
                    if (count($update_data)) {
                        foreach ($update_data as $key => $val) {
                            $edited .= strtoupper($key) . " = " . strtoupper($val) . " && ";
                        }

                        $update_data['editedon'] = date('Y-m-d H:i:s');
                        $update_data['editedby'] = 'candidate';
                        //$update_data['editedbyadmin'] = $this->UserID;

                        /* Benchmark Disability Code - Bhushan */
                        $applicationNo = $this->session->userdata('dbregnumber');

                        $visually_file       = 'v_' . $applicationNo . '.jpg';
                        $orthopedically_file = 'o_' . $applicationNo . '.jpg';
                        $cerebral_file       = 'c_' . $applicationNo . '.jpg';

                        if (@rename("./uploads/disability/" . $vis_imp_cert_name, "./uploads/disability/" . $visually_file)) {$update_data['vis_imp_cert_img'] = $visually_file;}

                        if (@rename("./uploads/disability/" . $orth_han_cert_name, "./uploads/disability/" . $orthopedically_file)) {$update_data['orth_han_cert_img'] = $orthopedically_file;}

                        if (@rename("./uploads/disability/" . $cer_palsy_cert_name, "./uploads/disability/" . $cerebral_file)) {$update_data['cer_palsy_cert_img'] = $cerebral_file;}

                        $update_data['benchmark_disability']       = $benchmark_disability;
                        $update_data['visually_impaired']          = $visually_impaired;
                        $update_data['orthopedically_handicapped'] = $orthopedically_handicapped;
                        $update_data['cerebral_palsy']             = $cerebral_palsy;

                        if ($benchmark_disability == 'N') {
                            $update_data['vis_imp_cert_img']   = '';
                            $update_data['orth_han_cert_img']  = '';
                            $update_data['cer_palsy_cert_img'] = '';
                        }
                        if ($visually_impaired == 'N') {
                            $update_data['vis_imp_cert_img'] = '';
                        }
                        if ($orthopedically_handicapped == 'N') {
                            $update_data['orth_han_cert_img'] = '';
                        }
                        if ($cerebral_palsy == 'N') {
                            $update_data['cer_palsy_cert_img'] = '';
                        }

                        if ($benchmark_disability == 'Y') {
                            if ($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N') {
                                $update_data['benchmark_disability'] = 'N';
                            }
                        }

                        $check_benchmark = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'), 'benchmark_disability');
                        if ($benchmark_disability != '') {
                            $update_data['benchmark_edit_flg']  = 'Y';
                            $update_data['benchmark_edit_date'] = date('Y-m-d H:i:s');
                        } elseif ($check_benchmark[0]['benchmark_disability'] != $benchmark_disability) {
                            $update_data['benchmark_edit_flg']  = 'Y';
                            $update_data['benchmark_edit_date'] = date('Y-m-d H:i:s');
                        }

                        /* Close Benchmark Disability Code - Bhushan */

                        //$personalInfo = filter($personal_info);
                        if ($this->master_model->updateRecord('member_registration', $update_data, array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')))) {
                            // -------image------#
                            $desc['updated_data'] = $update_data;
                            $desc['old_data']     = $member_info[0];

                            $finalStr = '';
                            if ($edited != '') {
                                $edit_data = trim($edited);
                                $finalStr  = rtrim($edit_data, "||");
                            }
                            //   log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('dbregid') , $this->session->userdata('dbregnumber'));
                            if ($kyc_edit_flag == 1) {
                                $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                    'regnumber' => $this->session->userdata('dbregnumber'),
                                ), '', array(
                                    'kyc_id' => 'DESC',
                                ), '0', '1');
                                if (count($kycmemdetails) > 0) {
                                    $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                    $kyc_update_data['kyc_state']        = '2';
                                    $kyc_update_data['kyc_status']       = '0';
                                    $this->db->like('allotted_member_id', $this->session->userdata('dbregnumber'));
                                    $this->db->or_like('original_allotted_member_id', $this->session->userdata('dbregnumber'));
                                    $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                        'list_type' => 'New',
                                    ));
                                    if (count($check_duplicate_entry) > 0) {
                                        foreach ($check_duplicate_entry as $row) {
                                            $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('dbregnumber'));
                                            $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('dbregnumber'));
                                            $admin_update_data           = array(
                                                'allotted_member_id'          => $allotted_member_id,
                                                'original_allotted_member_id' => $original_allotted_member_id,
                                            );
                                            $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array(
                                                'kyc_user_id' => $row['kyc_user_id'],
                                            ));
                                        }
                                    }
                                    // $kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
                                    if ($kycmemdetails[0]['kyc_status'] == '0') {
                                        $this->master_model->updateRecord('member_kyc', $kyc_update_data, array(
                                            'kyc_id' => $kycmemdetails[0]['kyc_id'],
                                        ));
                                        $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('dbregnumber'), serialize($desc));
                                    }
                                    // check membership count
                                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('dbregnumber'),
                                    ));
                                    if (count($check_membership_cnt) > 0) {
                                        // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                        /* update dowanload count 8-8-2017 */
                                        $this->master_model->updateRecord('member_idcard_cnt', array(
                                            'card_cnt' => '0',
                                        ), array(
                                            'member_number' => $this->session->userdata('dbregnumber'),
                                        ));
                                        /* Close update dowanload count */
                                        /* User Log Activities : Pooja */
                                        $uerlog = $this->master_model->getRecords('member_registration', array(
                                            'regnumber' => $this->session->userdata('dbregnumber'),
                                        ), 'regid,regnumber');
                                        $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                            'member_number' => $this->session->userdata('dbregnumber'),
                                        ));
                                        $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                        $log_message = serialize($user_info);
                                        $rId         = $uerlog[0]['regid'];
                                        $regNo       = $this->session->userdata('dbregnumber');
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        /* Close User Log Actitives */
                                    }
                                }
                                // echo $this->db->last_query();exit;
                                // change by pooja godse for  memebersgip id card  dowanload count reset
                                // check membership count
                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('dbregnumber'),
                                ));
                                if (count($check_membership_cnt) > 0) {
                                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                    /* update dowanload count 8-8-2017 */
                                    $this->master_model->updateRecord('member_idcard_cnt', array(
                                        'card_cnt' => '0',
                                    ), array(
                                        'member_number' => $this->session->userdata('dbregnumber'),
                                    ));
                                    /* User Log Activities : Pooja */
                                    $uerlog = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $this->session->userdata('dbregnumber'),
                                    ), 'regid,regnumber');
                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('dbregnumber'),
                                    ));
                                    $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                    $log_message = serialize($user_info);
                                    $rId         = $uerlog[0]['regid'];
                                    $regNo       = $this->session->userdata('dbregnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                    /* Close update dowanload count */
                                }
                                // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                                // if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))
                                $emailerstr = $this->master_model->getRecords('emailer', array(
                                    'emailer_name' => 'user_register_email',
                                ));
                                if (count($emailerstr) > 0) {
                                    $member_info = $this->master_model->getRecords('member_registration', array(
                                        'regid' => $this->session->userdata('dbregid'),
                                    ), 'email');
                                    $newstring = str_replace("#application_num#", "" . $this->session->userdata('dbregnumber') . "", $emailerstr[0]['emailer_text']);
                                    $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                                    $info_arr  = array(
                                        'to'      => $member_info[0]['email'],
                                        'from'    => $emailerstr[0]['from'],
                                        'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('dbregnumber'),
                                        'message' => $final_str,
                                    );
                                    if ($this->Emailsending->mailsend($info_arr)) {
//echo '***';
                                        // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                        redirect(base_url('Dbf/acknowledge/'));
                                    } else {
                                        $this->session->set_flashdata('error', 'Error while sending email !!');
                                        redirect(base_url('Dbf/profile/'));
                                    }
                                } else {
                                    $this->session->set_flashdata('error', 'Error while sending email !!');
                                    redirect(base_url('Dbf/profile/'));
                                }
                                /* User Log Activities : Bhushan */
                                $log_title   = "kyc member edited images id : " . $this->session->userdata('dbregid');
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('dbregid');
                                $regNo       = $this->session->userdata('dbregnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                //redirect(base_url('dbf/acknowledge/'));
                                /* Close User Log Actitives */
                            }
                            // ----------end image---------#

                            $desc['updated_data'] = $update_data;
                            $desc['old_data']     = $user_info[0];
                            //profile update logs
                            log_profile_user($log_title = "Profile updated successfully", $edited, 'data', $this->session->userdata('dbregid'), $this->session->userdata('dbregnumber'));

                            //log_dbf_activity($log_title = "Profile updated id:".$this->session->userdata('dbregid'), $description = serialize($desc));

                            /* User Log Activities : Bhushan */
                            $log_title   = "Profile updated id:" . $this->session->userdata('dbregid');
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('dbregid');
                            $regNo       = $this->session->userdata('dbregnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */

                            if ($kycflag == 1) {
                                $kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('dbregnumber')), '', array('kyc_id' => 'DESC'), '0', '1');
                                if (count($kycmemdetails) > 0) {
                                    $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                    $kyc_update_data['kyc_state']        = '2';
                                    $kyc_update_data['kyc_status']       = '0';

                                    $this->db->like('allotted_member_id', $this->session->userdata('dbregnumber'));
                                    $this->db->or_like('original_allotted_member_id', $this->session->userdata('dbregnumber'));
                                    $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array('list_type' => 'New'));
                                    if (count($check_duplicate_entry) > 0) {
                                        foreach ($check_duplicate_entry as $row) {
                                            $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('dbregnumber'));
                                            $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('dbregnumber'));
                                            $admin_update_data           = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);
                                            $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
                                        }
                                    }

                                    //$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
                                    if ($kycmemdetails[0]['kyc_status'] == '0') {
                                        $this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
                                        $this->KYC_Log_model->create_log('kyc member profile edit', '', '', $this->session->userdata('dbregnumber'), serialize($desc));
                                    }
                                    //check membership count
                                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('dbregnumber')));
                                    if (count($check_membership_cnt) > 0) {
                                        //$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('dbregnumber'));
                                        /* update dowanload count 8-8-2017 : pooja*/
                                        $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('dbregnumber')));
                                        /* Close update dowanload count */
                                    }

                                }
                                //echo $this->db->last_query();exit;
                                //log_dbf_activity($log_title = "KYC Profile updated id : ".$this->session->userdata('dbregid'), $description = serialize($desc));

                                /* User Log Activities : Bhushan */
                                $log_title   = "KYC Profile updated id:" . $this->session->userdata('dbregid');
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('dbregid');
                                $regNo       = $this->session->userdata('dbregnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */

                            }

                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                            if (count($emailerstr) > 0) {
                                $newstring = str_replace("#application_num#", "" . $this->session->userdata('dbregnumber') . "", $emailerstr[0]['emailer_text']);
                                $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('nmpassword')) . "", $newstring);
                                $info_arr  = array(
                                    'to'      => $email,
                                    'from'    => $emailerstr[0]['from'],
                                    'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('dbregnumber'),
                                    'message' => $final_str,
                                );

                                if ($this->Emailsending->mailsend($info_arr)) {
                                    //$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                    redirect(base_url('Dbf/acknowledge/'));
                                } else {
                                    $this->session->set_flashdata('error', 'Error while sending email !!');
                                    redirect(base_url('Dbf/profile/'));
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                redirect(base_url('Dbf/profile/'));
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                            $last = $this->uri->total_segments();
                            $post = $this->uri->segment($last);
                            redirect(base_url() . $post);
                        }
                    }

                } else {
                    $data['validation_errors'] = validation_errors();
                }
            } else {
                $data['validation_errors'] = validation_errors();
            }
        }
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        $states        = $this->master_model->getRecords('state_master');

        $this->db->not_like('name', 'Declaration Form');
        $this->db->not_like('name', 'college');
        $this->db->not_like('name', 'Aadhaar id');
        $this->db->not_like('name', 'Election Voters card');
        $idtype_master = $this->master_model->getRecords('idtype_master');

        $data = array('middle_content' => 'dbf/userprofile', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'user_info' => $user_info, 'idtype_master' => $idtype_master);
        $this->load->view('dbf/dbf_common_view', $data);
    }
    // ##---------Thank you page for user (prafull)-----------##
    public function acknowledge()
    {

        $data = array('middle_content' => 'dbf/edif_profile_thankyou', 'application_number' => $this->session->userdata('dbregnumber'), 'password' => base64_decode($this->session->userdata('dbpassword')));
        $this->load->view('dbf/dbf_common_view', $data);

    }

    // ##---------Edit Images(Prafull)-----------##
    public function editimages()
    {
        $kyc_update_data = array();
        $kyc_edit_flag   = 0;
        $flag            = 1;
        $member_info     = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid')), 'scannedphoto,scannedsignaturephoto,idproofphoto');
        $applicationNo   = $this->session->userdata('dbregnumber');
        if (isset($_POST['btnSubmit'])) {

            if ($_FILES['scannedphoto']['name'] == '' && $_FILES['scannedsignaturephoto']['name'] == '' && $_FILES['idproofphoto']['name'] == '') {
                $this->form_validation->set_rules('scannedphoto', 'Please Change atleast One Value', 'file_required');
            }
            if ($_FILES['scannedphoto']['name'] != '') {
                $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
            }
            if ($_FILES['scannedsignaturephoto']['name'] != '') {
                $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]');
            }
            if ($_FILES['idproofphoto']['name'] != '') {
                $this->form_validation->set_rules('idproofphoto', 'id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
            }
            if ($this->form_validation->run() == true) {
                $prev_edited_on     = '';
                $prev_photo_flg     = "N";
                $prev_signature_flg = "N";
                $prev_id_flg        = "N";
                $prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid')), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');
                if (count($prev_edited_on_qry)) {
                    $prev_edited_on     = $prev_edited_on_qry[0]['images_editedon'];
                    $prev_photo_flg     = $prev_edited_on_qry[0]['photo_flg'];
                    $prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
                    $prev_id_flg        = $prev_edited_on_qry[0]['id_flg'];
                    if ($prev_edited_on != date('Y-m-d')) {
                        $this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N'), array('regid' => $this->session->userdata('dbregid')));
                    }
                }

                $scannedphoto_file = '';
                $date              = date('Y-m-d h:i:s');
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$photo_flg = 'N';} else { $photo_flg = $prev_photo_flg;}

                $edited = '';
                if (isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name'] != '') {
                    @unlink('uploads/photograph/' . $member_info[0]['scannedphoto']);
                    $path = "./uploads/photograph";
                    //$new_filename = 'photo_'.strtotime($date).rand(1,99999);
                    $new_filename = 'p_' . $applicationNo;
                    $uploadData   = upload_file('scannedphoto', $path, $new_filename, '', '', true);
                    if ($uploadData) {
                        $kyc_edit_flag                       = 1;
                        $kyc_update_data['edited_mem_photo'] = 1;
                        //Overwrites file so no need to unlink
                        //@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);
                        $scannedphoto_file = $uploadData['file_name'];
                        $photo_flg         = 'Y';
                        $edited .= 'PHOTO || ';
                    } else {
                        $flag              = 0;
                        $scannedphoto_file = $this->input->post('scannedphoto1_hidd');
                    }
                } else {
                    $scannedphoto_file = $this->input->post('scannedphoto1_hidd');
                }

                // Upload signature
                $scannedsignaturephoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$signature_flg = 'N';} else { $signature_flg = $prev_signature_flg;}
                if (isset($_FILES['scannedsignaturephoto']['name']) && $_FILES['scannedsignaturephoto']['name'] != '') {
                    @unlink('uploads/photograph/' . $member_info[0]['scannedsignaturephoto']);
                    $path = "./uploads/scansignature";
                    //$new_filename = 'sign_'.strtotime($date).rand(1,99999);
                    $new_filename = 's_' . $applicationNo;
                    $uploadData   = upload_file('scannedsignaturephoto', $path, $new_filename, '', '', true);
                    if ($uploadData) {
                        $kyc_edit_flag                      = 1;
                        $kyc_update_data['edited_mem_sign'] = 1;
                        $scannedsignaturephoto_file         = $uploadData['file_name'];
                        $signature_flg                      = 'Y';
                        $edited .= 'SIGNATURE || ';
                    } else {
                        $flag                       = 0;
                        $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
                    }
                } else {
                    $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');
                }

                // Upload ID proof
                $idproofphoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$id_flg = 'N';} else { $id_flg = $prev_id_flg;}
                if (isset($_FILES['idproofphoto']['name']) && $_FILES['idproofphoto']['name'] != '') {
                    @unlink('uploads/photograph/' . $member_info[0]['idproofphoto']);
                    $path = "./uploads/idproof";
                    //$new_filename = 'idproof_'.strtotime($date).rand(1,99999);
                    $new_filename = 'pr_' . $applicationNo;
                    $uploadData   = upload_file('idproofphoto', $path, $new_filename, '', '', true);
                    if ($uploadData) {
                        $kyc_edit_flag                       = 1;
                        $kyc_update_data['edited_mem_proof'] = 1;
                        $idproofphoto_file                   = $uploadData['file_name'];
                        $id_flg                              = 'Y';
                        $edited .= 'PROOF || ';
                    } else {
                        $flag              = 0;
                        $idproofphoto_file = $this->input->post('idproofphoto1_hidd');
                    }
                } else {
                    $idproofphoto_file = $this->input->post('idproofphoto1_hidd');
                }

                if ($flag == 1) {
                   
                    /*$update_info = array(
                        'scannedphoto'          => $scannedphoto_file,
                        'scannedsignaturephoto' => $scannedsignaturephoto_file,
                        'idproofphoto'          => $idproofphoto_file,
                        'images_editedon'       => date('Y-m-d H:i:s'),
                        'images_editedby'       => 'candidate',
                        'photo_flg'             => $photo_flg,
                        'signature_flg'         => $signature_flg,
                        'id_flg'                => $id_flg,
                        'kyc_edit'              => $kyc_edit_flag,
                        'kyc_status'            => '0',
                    );*/

                     $update_info['scannedphoto'] = $scannedphoto_file; 
                     $update_info['scannedsignaturephoto'] = $scannedsignaturephoto_file; 
                     $update_info['idproofphoto'] = $idproofphoto_file; 
                     $update_info['images_editedon'] = date('Y-m-d H:i:s'); 

                     /*START: CHECK & UPDATE CREATED ON DATE IF CREATED ON IS 0000-00-00 00:00:00 in member_registration table for KYC ADDED this functionality on 01 July 2025 BY ANIL & Priyanka*/  
                      $this->db->where('(createdon = "0000-00-00 00:00:00" OR DATE(createdon) < "2017-06-01")');
                      $this->db->where('regnumber', $this->session->userdata('dbregnumber'));
                      $ex_mem_info = $this->master_model->getRecords('member_registration', '', 'createdon');                      
                      if (isset($ex_mem_info) && count($ex_mem_info) > 0){
                        $update_info['createdon'] = '2017-06-01 10:10:10';
                        $update_info['developer_comment'] = 'created on updated as 2017-06-01 10:10:10 from '.$ex_mem_info[0]['createdon'];
                      }
                      /*END: CHECK & UPDATE CREATED ON DATE IF CREATED ON IS 0000-00-00 00:00:00 in member_registration table for KYC ADDED this functionality on 01 July 2025 BY ANIL & Priyanka*/

                     $update_info['images_editedby'] = 'candidate'; 
                     $update_info['photo_flg'] = $photo_flg; 
                     $update_info['signature_flg'] = $signature_flg; 
                     $update_info['id_flg'] = $id_flg; 
                     $update_info['kyc_edit'] = $kyc_edit_flag; 
                     $update_info['kyc_status'] = '0'; 
 
                       

                    //$personalInfo = filter($personal_info);
                    if ($this->master_model->updateRecord('member_registration', $update_info, array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')))) {
                        $desc['updated_data'] = $update_info;
                        $desc['old_data']     = $member_info[0];
                        //log_nm_activity($log_title ="Member Edit Images", $log_message = serialize($desc));
                        //log_dbf_activity($log_title ="Member Edit Images", $log_message = serialize($desc));

                        /* User Log Activities : Bhushan */
                        $log_title   = "Member Edit Images";
                        $log_message = serialize($desc);
                        $rId         = $this->session->userdata('dbregid');
                        $regNo       = $this->session->userdata('dbregnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        /* Close User Log Actitives */

                        $finalStr = '';
                        if ($edited != '') {
                            $edit_data = trim($edited);
                            $finalStr  = rtrim($edit_data, "||");
                        }
                        log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('dbregid'), $this->session->userdata('dbregnumber'));

                        if ($kyc_edit_flag == 1) {
                            $kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('dbregnumber')), '', array('kyc_id' => 'DESC'), '0', '1');
                            if (count($kycmemdetails) > 0) {
                                $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                $kyc_update_data['kyc_state']        = '2';
                                $kyc_update_data['kyc_status']       = '0';

                                $this->db->like('allotted_member_id', $this->session->userdata('dbregnumber'));
                                $this->db->or_like('original_allotted_member_id', $this->session->userdata('dbregnumber'));
                                $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array('list_type' => 'New'));
                                if (count($check_duplicate_entry) > 0) {
                                    foreach ($check_duplicate_entry as $row) {
                                        $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('dbregnumber'));
                                        $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('dbregnumber'));
                                        $admin_update_data           = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);
                                        $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
                                    }
                                }

                                //$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
                                if ($kycmemdetails[0]['kyc_status'] == '0') {
                                    $this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
                                    $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('dbregnumber'), serialize($desc));
                                }
                            }
                            //echo $this->db->last_query();exit;
                            //check membership count
                            $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('dbregnumber')));
                            if (count($check_membership_cnt) > 0) {
                                //    $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('dbregnumber'));
                                /* update dowanload count 8-8-2017 : pooja*/
                                $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('dbregnumber')));
                                /* Close update dowanload count */
                            }

                            //log_dbf_activity($log_title = "kyc member edited images id : ".$this->session->userdata('dbregid'), $description = serialize($desc));

                            /* User Log Activities : Bhushan */
                            $log_title   = "kyc member edited images id : " . $this->session->userdata('dbregid');
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('dbregid');
                            $regNo       = $this->session->userdata('dbregnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */

                        }

                        if (!is_file(get_img_name($this->session->userdata('dbregnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('dbregnumber'), 's')) || !is_file(get_img_name($this->session->userdata('dbregnumber'), 'p')) || validate_edit_dbfmemdata($this->session->userdata('dbregnumber'))) {
                            $this->session->set_flashdata('error', 'Please update your profile!!');
                            redirect(base_url() . 'Dbf/profile/');
                        }

                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                        if (count($emailerstr) > 0) {
                            $member_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid')), 'email');
                            $newstring   = str_replace("#application_num#", "" . $this->session->userdata('dbregnumber') . "", $emailerstr[0]['emailer_text']);
                            $final_str   = str_replace("#password#", "" . base64_decode($this->session->userdata('nmpassword')) . "", $newstring);
                            $info_arr    = array(
                                'to'      => $member_info[0]['email'],
                                'from'    => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('dbregnumber'),
                                'message' => $final_str,
                            );

                            if ($this->Emailsending->mailsend($info_arr)) {
                                //$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                redirect(base_url('Dbf/acknowledge/'));
                            } else {
                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                redirect(base_url('Dbf/editimages/'));
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Error while sending email !!');
                            redirect(base_url('Dbf/editimages/'));
                        }
                    } else {
                        $desc['updated_data'] = $update_info;
                        $desc['old_data']     = $member_info[0];

                        //log_nm_activity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
                        //log_dbf_activity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
                        /* User Log Activities : Bhushan */
                        $log_title   = "Error While Member Images Edit";
                        $log_message = serialize($desc);
                        $rId         = $this->session->userdata('dbregid');
                        $regNo       = $this->session->userdata('dbregnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        /* Close User Log Actitives */

                        $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                        $last = $this->uri->total_segments();
                        $post = $this->uri->segment($last);
                        //redirect(base_url().$post);
                        redirect(base_url() . 'Dbf/profile');
                    }

                } else {
                    $this->session->set_flashdata('error', 'Please follow the instruction while uploading image(s)!!');
                    redirect(base_url('Dbf/editimages/'));
                }
            } else {
                $data['validation_errors'] = validation_errors();
            }
        }
        $data = array('middle_content' => 'dbf/edit_images', 'member_info' => $member_info);
        $this->load->view('dbf/dbf_common_view', $data);
    }

    //##---------check mobile number alredy exist or not on edit page(Prafull)-----------##
    public function editmobile()
    {
        $mobile = $_POST['mobile'];
        $regid  = $_POST['regid'];
        if ($mobile != "" && $regid != "") {
            $where = "( registrationtype='DB')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $regid, 'isactive' => '1'));
            if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}
        } else {
            echo 'error';
        }
    }

    //##---------check mail alredy exist or not on edit page(Prafull)-----------##
    public function editemailduplication()
    {
        $email = $_POST['email'];
        $regid = $_POST['regid'];
        if ($email != "" && $regid != "") {
            $where = "( registrationtype='DB')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $regid, 'isactive' => '1'));
            if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}
        } else {
            echo 'error';
        }
    }

    ##------------------ chenge password (PRAFULL)---------------##
    public function changepass()
    {
        $data['error'] = '';
        if (isset($_POST['btn_password'])) {
            $this->form_validation->set_rules('current_pass', 'Current Password', 'required|xss_clean');
            $this->form_validation->set_rules('txtnpwd', 'New Password', 'required|xss_clean');
            $this->form_validation->set_rules('txtrpwd', 'Re-type new password', 'required|xss_clean|matches[txtnpwd]');
            if ($this->form_validation->run()) {
                $current_pass = $this->input->post('current_pass');
                $new_pass     = $this->input->post('txtnpwd');
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key = $this->config->item('pass_key');
                $aes = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $encPass        = $aes->encrypt($new_pass);
                $curr_encrypass = $aes->encrypt(trim($current_pass));

                $row = $this->master_model->getRecordCount('member_registration', array('usrpassword' => $curr_encrypass, 'regid' => $this->session->userdata('dbregid')));
                if ($row == 0) {
                    $this->session->set_flashdata('error', 'Current Password is Wrong.');
                    redirect(base_url() . 'Dbf/changepass/');
                } else {
                    if ($current_pass != $new_pass) {
                        $input_array = array('usrpassword' => $encPass);
                        $this->master_model->updateRecord('member_registration', $input_array, array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')));
                        $this->session->unset_userdata('nmpassword');
                        $this->session->set_userdata("nmpassword", base64_encode($new_pass));
                        $this->session->set_flashdata('success', 'Password Changed successfully.');
                        redirect(base_url() . 'Dbf/changepass/');
                    } else {
                        $this->session->set_flashdata('error', 'Current password and New password cannot be same.');
                        redirect(base_url() . 'Dbf/changepass/');
                    }
                }
            }
        }
        $data = array('middle_content' => 'dbf/change_pass', $data);
        $this->load->view('dbf/dbf_common_view', $data);
    }

    ##----------- Show error msg for non-eligible
    public function GST()
    {
        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data    = array('middle_content' => 'dbf/not_eligible', 'check_eligibility' => $message);
        $this->load->view('dbf/dbf_common_view', $data);
    }
    public function benchmark_disability_check()
    {
        $msg      = '';
        $flag     = 1;
        $data_arr = $this->master_model->getRecords('member_registration', array(
            'regnumber' => $this->session->userdata('dbregnumber'),
            'isactive'  => '1',
        ), 'benchmark_disability,registrationtype');

        if ($data_arr[0]['benchmark_disability'] == '') {
            $message = '<div style="color:#F00">Kindly go to Edit Profile and .
	<a href="' . base_url() . 'Dbf/profile" target="new">click here</a> to update the, "Benchmark Disability" and then apply for exam. For any queries contact zonal office.</div>';
            $data = array('middle_content' => 'dbf/not_eligible', 'check_eligibility' => $message);
            $this->load->view('dbf/dbf_common_view', $data);
        }
    }
    ##------------------ Exam list for logged in user(Vrushali)---------------##
    public function examlist()
    {
        /* Benchmark Disability Check */
        $user_benchmark_disability = $this->master_model->getRecords('member_registration', array(
            'regnumber' => $this->session->userdata('dbregnumber'),
            'isactive'  => '1',
        ), 'benchmark_disability');
        if ($user_benchmark_disability[0]['benchmark_disability'] == '') {
            redirect(base_url() . 'Dbf/benchmark_disability_check');
        }
        /* Benchmark Disability Check Close */

        ####check GST paid or not.
        $GST_val = check_GST($this->session->userdata('dbregnumber'));
        if ($GST_val == 2) {
            redirect(base_url() . 'Dbf/GST');
        }
        //accedd denied due to GST
        //$this->master_model->warning();
        $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        if (!is_file(get_img_name($this->session->userdata('dbregnumber'), 's')) || !is_file(get_img_name($this->session->userdata('dbregnumber'), 'p'))) {
            redirect(base_url() . 'Dbf/notification');
        }

        $today_date = date('Y-m-d');
        $flag       = 1;
        $exam_list  = array();

        /* $this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');
        $this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
        $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');

        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $this->db->where("exam_activation_master.exam_activation_delete","0");
        $this->db->where("misc_master.misc_delete",'0');
        $this->db->where('medium_delete','0');
        $this->db->group_by('medium_master.exam_code');
        $exam_list=$this->master_model->getRecords('exam_master',array('elg_mem_db'=>'Y'));*/
        $examcodes = array('528', '529', '530', '531', '534', '991');
        $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
        $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
        $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.	exam_period');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
        $this->db->where('medium_delete', '0');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $this->db->where("exam_activation_master.exam_activation_delete", "0");
        $this->db->where_not_in('exam_master.exam_code', $examcodes);
        $this->db->group_by('medium_master.exam_code');
        $exam_list = $this->master_model->getRecords('exam_master', array('elg_mem_db' => 'Y'));

      //  echo $this->db->last_query();exit;
        $data = array('middle_content' => 'dbf/dbf_exam_list', 'exam_list' => $exam_list);
        $this->load->view('dbf/dbf_common_view', $data);

    }

    ##------------------ Exam list for logged in user(Vrushali)---------------##
    

    ##------------------ Specific Exam Details for logged in user(Vrushali)---------------##
    /*public function examdetails()
    {
    $applied_exam_info=array();
    $flag=1;
    $examcode=base64_decode($this->input->get('excode2'));
    //Query to check selected exam details
    $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
    if(count($check_qualify_exam) > 0)
    {
    //Condition to check the qualifying id exist
    if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0')
    {
    //Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
    $check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$check_qualify_exam[0]['qualifying_exam1'],'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')),'exam_status,remark');
    if(count($check_qualify_exam_eligibility) > 0)
    {
    if($check_qualify_exam_eligibility[0]['exam_status']=='P')
    {
    //check eligibility for applied exam(This are the exam who  have pre qualifying exam)
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
    $check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')));
    if(count($check_eligibility_for_applied_exam) > 0)
    {
    if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
    {
    $flag=0;
    $message=$check_eligibility_for_applied_exam[0]['remark'];
    }
    else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
    {
    $flag=1;
    }
    }
    else
    {
    //CAIIB apply directly
    $flag=1;
    }
    }
    else
    {
    $flag=0;
    $message=$check_qualify_exam_eligibility[0]['remark'];
    }
    }
    else
    {
    //show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
    $flag=0;
    if(isset($check_qualify_exam[0]['qualifying_exam1']))
    {
    $get_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$check_qualify_exam[0]['qualifying_exam1']),'description');
    if(count($get_exam) > 0)
    {
    $message='You must apply/clear <strong>'.$get_exam[0]['description'].'</strong> exam before applying <strong> '.$check_qualify_exam[0]['description'].'</strong> exam.';
    }
    }
    }
    }
    else
    {

    //check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
    $check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('nmregnumber')));
    if(count($check_eligibility_for_applied_exam) > 0)
    {
    if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
    {
    $flag=0;
    $message=$check_eligibility_for_applied_exam[0]['remark'];

    }
    else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
    {
    $check=$this->examapplied($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
    if(!$check)
    {
    $check_date=$this->examdate($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
    if(!$check_date)
    {
    //CAIIB apply directly
    $flag=1;
    }
    else
    {
    $message='Exam fall in same date';
    $flag=0;
    }
    }
    else
    {
    $message='You have already applied for the examination';
    $flag=0;
    }
    }
    }
    else
    {
    $check=$this->examapplied($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
    if(!$check)
    {
    $check_date=$this->examdate($this->session->userdata('nmregnumber'),$this->input->get('excode2'));
    if(!$check_date)
    {
    //CAIIB apply directly
    $flag=1;
    }
    else
    {
    $message='Exam fall in same date';
    $flag=0;
    }
    }
    else
    {
    $message='You have already applied for the examination';
    $flag=0;
    }

    }
    }
    }
    else
    {
    $flag=1;
    }

    //Query to check where exam applied successfully or not with transaction
    $is_transaction_doone=$this->master_model->getRecordCount('payment_transaction',array('exam_code'=>$examcode,'member_regnumber'=>$this->session->userdata('nmregnumber')));

    if($is_transaction_doone >0)
    {
    $today_date=date('Y-m-d');
    $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.exam_status,exam_master.description');
    $this->db->where('exam_master.elg_mem_db','Y');
    //$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
    $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
    //$this->db->where('payment_transaction.status','1');
    $applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>$examcode,'regnumber'=>$this->session->userdata('nmregnumber')));
    }

    if($flag==0)
    {
    $data=array('middle_content'=>'nonmember/not_eligible','check_eligibility'=>$message);
    $this->load->view('nonmember/nm_common_view',$data);
    }
    else if(count($applied_exam_info) > 0)
    {
    $data=array('middle_content'=>'nonmember/already_apply','check_eligibility'=>'You have already applied for the examination.');
    $this->load->view('nonmember/nm_common_view',$data);
    }
    else
    {

    $exam_info=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
    $data=array('middle_content'=>'nonmember/cms_page','exam_info'=>$exam_info);
    $this->load->view('nonmember/nm_common_view',$data);
    }
    }*/

    ##----------- Show error msg for non-eligible
    public function accessdenied()
    {
        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data    = array('middle_content' => 'dbf/not_eligible', 'check_eligibility' => $message);
        $this->load->view('dbf/dbf_common_view', $data);
    }

    function get_client_ip1() {
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
    ##------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
    public function examdetails()
    {

        /* Benchmark Disability Check */
        $user_benchmark_disability = $this->master_model->getRecords('member_registration', array(
            'regnumber' => $this->session->userdata('dbregnumber'),
            'isactive'  => '1',
        ), 'benchmark_disability');
        if ($user_benchmark_disability[0]['benchmark_disability'] == '') {
            redirect(base_url() . 'Dbf/benchmark_disability_check');
        }
        /* Benchmark Disability Check Close */

        ####check GST paid or not.
        $GST_val = check_GST($this->session->userdata('dbregnumber'));
        if ($GST_val == 2) {
            redirect(base_url() . 'Dbf/GST');
        }

        $flag = $this->checkusers(base64_decode($this->input->get('excode2')));
        if ($flag == 0) {
            redirect(base_url() . 'Dbf/accessdenied/');
        }
        //check exam acivation
        $check_exam_activation = check_exam_activate(base64_decode($this->input->get('excode2')));
        if ($check_exam_activation == 0) {
            redirect(base_url() . 'Dbf/accessdenied/');
        }
        $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        if (!is_file(get_img_name($this->session->userdata('dbregnumber'), 's')) || !is_file(get_img_name($this->session->userdata('dbregnumber'), 'p'))) {
            redirect(base_url() . 'Dbf/notification');
        }
        $message           = '';
        $applied_exam_info = array();
        $flag              = 1;
        $checkqualifyflag  = 0;
        $exam_status       = 1;
        $examcode          = base64_decode($this->input->get('excode2'));

        if($examcode==210 || $examcode==420)
        {
            if($this->get_client_ip1()!='115.124.115.75'  && $this->get_client_ip1()!='182.73.101.70' && $this->get_client_ip1()!='115.124.115.69' && $this->get_client_ip1()!='106.194.206.125') {
               //echo'JAIIB/DBF will be start soon';exit; 
            }
        }

        //$check=$this->examapplied($this->session->userdata('regnumber'),$this->input->get('excode2'));
        //if(!$check)
        //{

        //Query to check selected exam details
        $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
        if (count($check_qualify_exam) > 0) {
            //Condition to check the qualifying id exist
            if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $examcode, $check_qualify_exam[0]['qualifying_part1']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $examcode, $check_qualify_exam[0]['qualifying_part2']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0' && $checkqualifyflag == 0) {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $examcode, $check_qualify_exam[0]['qualifying_part3']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            } else if ($flag == 1 && $checkqualifyflag == 0) {
                //check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('dbregnumber')));
                if (count($check_eligibility_for_applied_exam) > 0) {
                    foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
                        if ($check_exam_status['exam_status'] == 'F') {
                            $exam_status = 0;
                        }
                    }

                    //if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
                    if ($exam_status == 1) {
                        $flag    = 0;
                        $message = $check_eligibility_for_applied_exam[0]['remark'];
                    }
                    //else if($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
                    else if ($exam_status == 0) {
                        $check = $this->examapplied($this->session->userdata('dbregnumber'), $this->input->get('excode2'));
                        if (!$check) {
                            $check_date = $this->examdate($this->session->userdata('dbregnumber'), $this->input->get('excode2'));
                            if (!$check_date) {
                                //CAIIB apply directly
                                $flag = 1;
                            } else {
                                $message = $this->get_alredy_applied_examname($this->session->userdata('dbregnumber'), $this->input->get('excode2'));
                                //$message='Exam fall in same date';
                                $flag = 0;
                            }
                        } else {
                            /*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
                            $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
                            $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
                            $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
                            $message = 'You have already applied for the examination';
                            $flag    = 0;
                        }
                    }
                } else {

                    $check = $this->examapplied($this->session->userdata('dbregnumber'), $this->input->get('excode2'));

                    if (!$check) {

                        $check_date = $this->examdate($this->session->userdata('dbregnumber'), $this->input->get('excode2'));

                        if (!$check_date) {
                            //CAIIB apply directly
                            $flag = 1;
                        } else {
                            $message = $this->get_alredy_applied_examname($this->session->userdata('dbregnumber'), $this->input->get('excode2'));
                            //$message='Exam fall in same date';
                            $flag = 0;
                        }
                    } else {
                        /*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->input->get('excode2')),'misc_master.misc_delete'=>'0'),'exam_month');
                        $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
                        $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
                        $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
                        $message = 'You have already applied for the examination';
                        $flag    = 0;
                    }

                }
            }
        } else {
            $flag = 1;
        }

        //Query to check where exam applied successfully or not with transaction
        $is_transaction_doone = $this->master_model->getRecordCount('payment_transaction', array('exam_code' => $examcode, 'member_regnumber' => $this->session->userdata('dbregnumber'), 'status' => '1'));

        if ($is_transaction_doone > 0) {
            $today_date = date('Y-m-d');
            $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');
            $this->db->where('exam_master.elg_mem_db', 'Y');
            //$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('member_exam.pay_status', '1');
            $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('dbregnumber')));
        }

        if ($flag == 0) {
            $data = array('middle_content' => 'dbf/not_eligible', 'check_eligibility' => $message);
            $this->load->view('dbf/dbf_common_view', $data);
        } else if (count($applied_exam_info) > 0) {
            /*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>$examcode,'misc_master.misc_delete'=>'0'),'exam_month');
            $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
            $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
            $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
            $message = 'You have already applied for the examination';
            $data    = array('middle_content' => 'dbf/already_apply', 'check_eligibility' => $message);
            $this->load->view('dbf/dbf_common_view', $data);
        } else {
            $exam_info = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
            $data      = array('middle_content' => 'dbf/cms_page', 'exam_info' => $exam_info);
            $this->load->view('dbf/dbf_common_view', $data);
        }

        //}
        /*else
    {
    $data=array('middle_content'=>'already_apply','check_eligibility'=>'You have already applied for the examination.');
    $this->load->view('common_view',$data);
    }*/

    }

    ##-------------- check qualify exam pass/fail
    public function checkqualify($qualify_id = null, $examcode = null, $part_no = null)
    {
        $flag               = 0;
        $exam_status        = 1;
        $check_qualify      = array();
        $message            = 'Pre qualifying exam not found';
        $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));
        //Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
        $check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $qualify_id, 'part_no' => $part_no, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('dbregnumber')), 'exam_status,remark');
        if (count($check_qualify_exam_eligibility) > 0) {
            foreach ($check_qualify_exam_eligibility as $check_exam_status) {
                if ($check_exam_status['exam_status'] == 'F' || $check_exam_status['exam_status'] == 'V' || $check_exam_status['exam_status'] == 'D') {
                    $exam_status = 0;
                }
            }

            //if($check_qualify_exam_eligibility[0]['exam_status']=='P')
            if ($exam_status == 1) {
                //check eligibility for applied exam(This are the exam who  have pre qualifying exam)
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('dbregnumber')));
                if (count($check_eligibility_for_applied_exam) > 0) {
                    foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
                        if ($check_exam_status['exam_status'] == 'F') {
                            $exam_status = 0;
                        }
                    }
                    //if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
                    if ($exam_status == 1) {
                        $flag          = 0;
                        $message       = $check_eligibility_for_applied_exam[0]['remark'];
                        $check_qualify = array('flag' => $flag, 'message' => $message);
                        return $check_qualify;
                    }
                    /*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
                    {
                    $flag=0;
                    $message=$check_eligibility_for_applied_exam[0]['remark'];
                    $check_qualify=array('flag'=>$flag,'message'=>$message);
                    return $check_qualify;
                    }*/
                    //else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
                    else if ($exam_status == 0) {
                        $flag    = 0;
                        $message = $check_eligibility_for_applied_exam[0]['remark'];
                    } else if ($exam_status == 0)
                    //else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
                    {
                        $flag          = 1;
                        $check_qualify = array('flag' => $flag, 'message' => $message);
                        return $check_qualify;
                    }
                } else {
                    //CAIIB apply directly
                    $flag          = 1;
                    $check_qualify = array('flag' => $flag, 'message' => $message);
                    return $check_qualify;
                }
            } else {
                $flag          = 0;
                $message       = $check_qualify_exam_eligibility[0]['remark'];
                $check_qualify = array('flag' => $flag, 'message' => $message);
                return $check_qualify;
            }
        } else {
            //show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
            $flag = 0;
            if ($qualify_id) {
                $get_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $qualify_id), 'description');
                if (count($get_exam) > 0) {
                    $message = 'You must apply/clear <strong>' . $get_exam[0]['description'] . '</strong> exam before applying <strong> ' . $check_qualify_exam[0]['description'] . '</strong> exam.';
                }
            }

            $check_qualify = array('flag' => $flag, 'message' => $message);
            return $check_qualify;
        }

    }

    ##------------------ CMS Page for logged in user(PRAFULL)---------------##
    /*public function comApplication()
    {
    //Considering B1 as group code in query (By Prafull)
    if($this->session->userdata('examcode')=='')
    {
    redirect(base_url().'Home/examlist/');
    }
    $where="CASE WHEN eligible_master.app_category ='' THEN  fee_master.group_code='B1' ELSE fee_master.group_code=eligible_master.app_category END";
    //$this->db->select("fee_master.*,eligible_master.*,exam_master.*");
    $this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
    $this->db->join("eligible_master","eligible_master.exam_code=fee_master.exam_code");
    $this->db->where("eligible_master.member_no",$this->session->userdata('nmregnumber'));
    $this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
    $this->db->where($where,'',false);
    //$this->db->where('fee_master.group_code','B1');
    $this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
    $examinfo=$this->master_model->getRecords('exam_master');
    //echo $this->db->last_query();
    if(count($examinfo)==0)
    {
    $this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code");
    $this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
    $this->db->where('fee_master.group_code','B1');
    $this->db->where('exam_master.exam_code',$this->session->userdata('examcode'));
    $examinfo = $this->master_model->getRecords('exam_master');
    }

    $undergraduate=$this->master_model->getRecords('qualification',array('type'=>'UG'));
    $graduate=$this->master_model->getRecords('qualification',array('type'=>'GR'));
    $postgraduate=$this->master_model->getRecords('qualification',array('type'=>'PG'));
    $institution_master=$this->master_model->getRecords('institution_master');
    $states=$this->master_model->getRecords('state_master');
    $designation=$this->master_model->getRecords('designation_master');
    $idtype_master=$this->master_model->getRecords('idtype_master');
    //To-do use exam-code wirh medium master
    $medium=$this->master_model->getRecords('medium_master');
    //get center as per exam
    $this->db->where('exam_name',$this->session->userdata('examcode'));
    $center=$this->master_model->getRecords('center_master');
    //user information
    $user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')));
    $data=array('middle_content'=>'nonmember/comApplication','states'=>$states,'undergraduate'=>$undergraduate,'graduate'=>$graduate,'postgraduate'=>$postgraduate,'institution_master'=>$institution_master,'designation'=>$designation,'user_info'=>$user_info,'idtype_master'=>$idtype_master,'examinfo'=>$examinfo,'medium'=>$medium,'center'=>$center);
    $this->load->view('nonmember/nm_common_view',$data);

    }*/

    ##------------------ CMS Page for logged in user(PRAFULL)---------------##
    public function comApplication()
    {
        //accedd denied due to GST
        //$this->master_model->warning();

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // priyanka d-01-feb-23 >> avoid back button error
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: no-store, no-cache, must-revalidate"); 
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");

        if($this->get_client_ip1()!='115.124.115.72'    && $this->get_client_ip1()!='115.124.115.75'    && $this->get_client_ip1()!='182.73.101.70'&& $this->get_client_ip1()!='106.194.206.125') 
        {
          
            $excodes = array(210,420);
            
            if(in_array($this->session->userdata('examcode'),$excodes)) {
                 //echo'JAIIB/DBF exam will be live soon';exit; 
            }
		}

        if (isset($_POST['btnPreviewSubmit'])) {
            //exit;
            $scribe_flag = 'N';
            $venue       = $this->input->post('venue');
            $date        = $this->input->post('date');
            $time        = $this->input->post('time');

            $outputphoto1 = $outputsign1 = $photo_name = $sign_name = $var_errors = $state = '';
            if ($this->session->userdata('examinfo')) {
                $this->session->unset_userdata('examinfo');
            }

            if(in_array(base64_decode($_POST['excd']),$this->config->item('skippedAdmitCardForExams'))) {
                if(!isset($_POST['venue']) || !isset($_POST['date'])  || !isset($_POST['time']) || empty($_POST['venue']) || empty($_POST['date']) || empty($_POST['time'])) {
                    $this->session->set_flashdata('error','Error while during registration.please try again!');
                    redirect(base_url() . 'Dbf/comApplication');
                }
            }
            $this->form_validation->set_rules('scribe_flag', 'Scribe Services', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
            $this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');
            $this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');
            $this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');
            $this->form_validation->set_rules('medium', 'Medium', 'required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');
            $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');

            $this->form_validation->set_rules('collage_institute', 'College/Educational Institute ', 'required|xss_clean'); //priyanka d - 27-july-23
            if ($this->input->post('collage_institute') == 'other') { //priyanka d - 27-july-23
                $this->form_validation->set_rules('other_collage_institute', 'College/Educational Institute', 'trim|required|xss_clean');
            }
            if ($this->input->post('elearning_flag') == 'Y') {
                $this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
            }

            if ($this->session->userdata('examcode') == $this->config->item('examCodeCaiib')) {
                $this->form_validation->set_rules('selSubName', 'Elective Subject Name', 'required|xss_clean');
            }
            if ($this->session->userdata('examcode') == $this->config->item('examCodeJaiib')) {
                $this->form_validation->set_rules('placeofwork', 'Place of Work', 'trim|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('state_place_of_work', 'State', 'trim|required|xss_clean');
                if ($this->input->post('state_place_of_work') != '') {
                    $state = $this->input->post('state_place_of_work');
                }
                $this->form_validation->set_rules('pincode_place_of_work', 'Pin Code', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
            }

            /*if($this->input->post('gstin_no'))
            {
            $this->form_validation->set_rules('gstin_no', 'Bank GSTIN Number', 'trim|alpha_numeric|min_length[15]|xss_clean');
            }*/
            if ($this->form_validation->run() == true) {
                $subject_arr = array();
                $venue       = $this->input->post('venue');
                $date        = $this->input->post('date');
                $time        = $this->input->post('time');

                if (count($venue) > 0 && count($date) > 0 && count($time) > 0) {
                    foreach ($venue as $k => $v) {
                        $this->db->group_by('subject_code');
                        $compulsory_subjects_name = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($_POST['excd']), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $_POST['eprid'], 'subject_code' => $k), 'subject_description');
                        $subject_arr[$k]          = array('venue' => $v, 'date' => $date[$k], 'session_time' => $time[$k], 'subject_name' => $compulsory_subjects_name[0]['subject_description']);
                    }
                    #########check duplication of venue,date,time##########
                    if (count($subject_arr) > 0) {
                        $msg          = '';
                        $sub_flag     = 1;
                        $sub_capacity = 1;
                        foreach ($subject_arr as $k => $v) {
                            foreach ($subject_arr as $j => $val) {
                                if ($k != $j) {
                                    //if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                                    if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                                        $sub_flag = 0;
                                    }
                                }
                            }
                            $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
							if ($capacity <= 1) 
							{
								//get latest/ in between 2hr record from admit-card.
								$total_admit_count=getLastseat(base64_decode($_POST['excd']),$_POST['selCenterName'],$v['venue'],$v['date'],$v['session_time']);
								if($total_admit_count > 0)
								{
									$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
									$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
								}
							}
                            if ($msg != '') {
                                $this->session->set_flashdata('error', $msg);
                                redirect(base_url() . 'Dbf/comApplication');
                            }
                        }
                    }
                    if ($sub_flag == 0) {
                        $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                        redirect(base_url() . 'Dbf/comApplication');
                    }
                }

                #----Scrib Flag - changes by POOJA GODSE---#
                $scribe_flag          = $scribe_flag_d          = 'N';
                $Sub_menue_disability = $disability_value = '';
                if (isset($_POST['scribe_flag'])) {
                    $scribe_flag = $_POST['scribe_flag'];
                }
                if (isset($_POST['scribe_flag_d'])) {
                    $scribe_flag_d = $_POST['scribe_flag_d'];
                }
                if (isset($_POST['Sub_menue'])) {
                    $Sub_menue_disability = $_POST['Sub_menue'];
                }
                if (isset($_POST['disability_value'])) {
                    $disability_value = $_POST['disability_value'];
                }

                $elearning_flag_new = 'N';
                if (isset($_POST['el_subject'])) {
                    $el_subject         = $_POST['el_subject'];
                    $elearning_flag_new = 'Y';
                } else {
                    $el_subject = 'N';
                }

                if (in_array(base64_decode($_POST['excd']), array($this->config->item('examCodeJaiib'), $this->config->item('examCodeDBF'), $this->config->item('examCodeSOB')))) {
                    $elearning_flag_new = $elearning_flag_new;
                } else if (in_array(base64_decode($_POST['excd']), array($this->config->item('examCodeCaiib'), 65))) {
                    $elearning_flag_new = $elearning_flag_new;
                } else {
                    $elearning_flag_new = $_POST['elearning_flag'];
                }
                #----Scrib Flag end - changes by POOJA GODSE---#

                //priyanka d
					$optFlg='N';
					/*if(isset($_POST['optval']) && $_POST['optval']==1)
					$optFlg='F';
				else if(isset($_POST['optval']) && $_POST['optval']==2)
				$optFlg='R';*/
                $selectedoptval=$this->session->userdata('selectedoptVal');
                if($selectedoptval==1)
                    $optFlg='F';
                else if($selectedoptval==2)
                    $optFlg='R';
                $user_data = array('email' => $_POST["email"],
                    'mobile'                   => $_POST["mobile"],
                    'photo'                    => '',
                    'signname'                 => '',
                    'medium'                   => $_POST['medium'],
                    'selCenterName'            => $_POST['selCenterName'],
                    'optmode'                  => $_POST['optmode'],
                    'extype'                   => $_POST['extype'],
                    'exname'                   => $_POST['exname'],
                    'excd'                     => $_POST['excd'],
                    'eprid'                    => $_POST['eprid'],
                    'fee'                      => $_POST['fee'],
                    'txtCenterCode'            => $_POST['txtCenterCode'],
                    'insert_id'                => '',
                    'selected_elect_subcode'   => $_POST['selSubcode'],
                    'selected_elect_subname'   => $_POST['selSubName1'],
                    'placeofwork'              => $_POST['placeofwork'],
                    'state_place_of_work'      => $_POST['state_place_of_work'],
                    'pincode_place_of_work'    => $_POST['pincode_place_of_work'],
                    'elected_exam_mode'        => $_POST['elected_exam_mode'],
                    'grp_code'                 => $_POST['grp_code'],
                    'subject_arr'              => $subject_arr,
                    'el_subject'               => $el_subject,
                    'scribe_flag'              => $scribe_flag,
                    'scribe_flag_d'            => $scribe_flag_d,
                    'disability_value'         => $disability_value,
                    'Sub_menue_disability'     => $Sub_menue_disability,
                    'elearning_flag'           => $elearning_flag_new,
                    /* 'elearning_flag'=>$_POST['elearning_flag'] */
                    'optval'                    => $selectedoptval, // priyanka d - 24-o1-23
                    'optFlg'                    => $optFlg,
                    'collage_institute'         =>  $_POST['collage_institute'],  // priyanka d - 26-july-23
                    'other_collage_institute'   =>$_POST['other_collage_institute'], // priyanka d - 26-july-23
                );
                $this->session->set_userdata('examinfo', $user_data);
                //logactivity($log_title ="Dbf Member exam apply details", $log_message = serialize($user_data));
                //log_dbf_activity($log_title ="Dbf Member exam apply details", $log_message = serialize($user_data));

                /* User Log Activities : Bhushan */
                $log_title   = "Dbf Member exam apply details";
                $log_message = serialize($user_data);
                $rId         = $this->session->userdata('dbregid');
                $regNo       = $this->session->userdata('dbregnumber');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */

                redirect(base_url() . 'Dbf/preview');
            } else {
                $var_errors = str_replace("<p>", "<span>", $var_errors);
                $var_errors = str_replace("</p>", "</span><br>", $var_errors);
            }
        }

        //Considering B1 as group code in query (By Prafull)
        if ($this->session->userdata('examcode') == '') {
            redirect(base_url() . 'Dbf/examlist/');
        }
        //check exam acivation
        $check_exam_activation = check_exam_activate($this->session->userdata('examcode'));
        if ($check_exam_activation == 0) {
            redirect(base_url() . 'Dbf/accessdenied/');
        }

        $cookieflag = 1;
        // $this->chk_session->checkphoto();
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $this->session->userdata('dbregnumber');
        if ($valcookie) {
            $regnumber    = $valcookie;
            $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                'member_regnumber' => $regnumber,
                'status'           => '2',
                'pay_type'         => '2',
            ), '', array(
                'id' => 'DESC',
            ));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $cookieflag = 0;
                } else {
                    delete_cookie('examid');
                }
            } else {
                delete_cookie('examid');
            }
        } else {
            delete_cookie('examid');
        }
        
        // End Of ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)

        // priyanka d - for jaiib -23-01-23

        if(!$this->session->userdata('selectedoptVal_examcode') || $this->session->userdata('selectedoptVal_examcode')!=$this->session->userdata('examcode') ) {
            //echo $this->session->userdata('selectedoptVal');exit;
                            $selectedoptVal = array('selectedoptVal' => 0);
                            $this->session->set_userdata($selectedoptVal);
            
                        }

		 $continueAsOld=1;
		 if($this->session->userdata('selectedoptVal')==1)
		 //if(isset($_GET['optval']) && $_GET['optval']==1)
		 {
			 $continueAsOld=0;
		 }

        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
        $this->db->join("eligible_master", 'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period', 'left');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->where("eligible_master.member_no", $this->session->userdata('dbregnumber'));
        $this->db->where("eligible_master.app_category !=", 'R');
        $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
		$this->db->order_by("eligible_master.subject_code", "asc");
        $examinfo = $this->master_model->getRecords('exam_master');

        if (count($examinfo) > 0 && $continueAsOld==1) // priyanka d -17-feb-23
        {
            foreach ($examinfo as $rowdata) {
                if ($rowdata['exam_status'] != 'P') {
                    $this->db->group_by('subject_code');
                    $compulsory_subjects[] = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $rowdata['exam_period'], 'subject_code' => $rowdata['subject_code']));
                }
            }
            //$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));
            $compulsory_subjects = array_map('current', $compulsory_subjects);
           
        }

        //center for eligible member
        //$this->db->join("eligible_master","eligible_master.exam_code=center_master.exam_name AND eligible_master.eligible_period=center_master.exam_period");
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
        $this->db->where('center_master.exam_name', $this->session->userdata('examcode'));
        //$this->db->where("eligible_master.member_no",$this->session->userdata('dbregnumber'));
        $this->db->where("center_delete", '0');
        $this->db->order_by('center_master.center_name asc');
        $center = $this->master_model->getRecords('center_master');

        //Below code, if member is new member
        if (count($examinfo) <= 0  || $continueAsOld==0) // priyanka d- 24-01-23
        {
            //$this->db->select('fee_master.*,exam_master.*,misc_master.*,fee_master.fee_amount as fees');
            $this->db->select('exam_master.*,misc_master.*');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period'); //added on 5/6/2017
            $this->db->where("misc_master.misc_delete", '0');
            //$this->db->join("fee_master","fee_master.exam_code=exam_master.exam_code AND fee_master.exam_period=exam_activation_master.exam_period");//added on 5/6/2017
            //$this->db->where("fee_master.member_category",$this->session->userdata('memtype'));
            //$this->db->where('fee_master.group_code','B1');
            $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
            $examinfo = $this->master_model->getRecords('exam_master');
            //echo $this->db->last_query();exit;
            //get center
            /*$this->db->join('misc_master','misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
            $this->db->where("center_delete",'0');
            $this->db->where('exam_name',$this->session->userdata('examcode'));
            $center=$this->master_model->getRecords('center_master');*/
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
            //$this->db->join('misc_master','misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
            $this->db->where("center_delete", '0');
            $this->db->where('exam_name', $this->session->userdata('examcode'));
            $this->db->group_by('center_master.center_name');
            $this->db->order_by('center_master.center_name asc');
            $center = $this->master_model->getRecords('center_master');
            ####### get compulsory subject list##########
            $this->db->group_by('subject_code');
            $compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $examinfo[0]['exam_period']), '', array('subject_code' => 'ASC'));
        }

        if (count($examinfo) <= 0) {
            redirect(base_url() . 'Dbf/examlist');
        }

        $undergraduate      = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate           = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate       = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        $institution_master = $this->master_model->getRecords('institution_master');
        $states             = $this->master_model->getRecords('state_master');
        $designation        = $this->master_model->getRecords('designation_master');
        $idtype_master      = $this->master_model->getRecords('idtype_master');
        //To-do use exam-code wirh medium master
        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', $this->session->userdata('examcode'));
        $medium = $this->master_model->getRecords('medium_master');
        //get center as per exam

        //user information
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')));
        if (count($user_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard');
        }
        $scribe_disability = $this->master_model->getRecords('scribe_disability', array('is_delete' => '0'));
        //subject information
        $caiib_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'E', 'exam_period' => $examinfo[0]['exam_period']));

        /* benchmark disability */
        $benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');

            // priyanka d - 23-01-23 for jaiib flag
		$order_by=array('id'=>'desc');
		$checkJaiibFlag = $this->master_model->getRecords('eligible_master', array('exam_code' => $this->session->userdata('examcode'),  'eligible_period' => $examinfo[0]['exam_period'],'member_no' => $this->session->userdata('dbregnumber')),'',$order_by,0,1);
		$showOptForJaiib=0;
	   // echo $this->db->last_query();
		//echo'<pre>';print_r($checkJaiibFlag);exit;
		if(count($checkJaiibFlag)>0) {
			foreach($checkJaiibFlag as $j) {
				if($j['optForCandidate']=='Y')
					$showOptForJaiib=1;
			}
		}
	   // echo $showOptForJaiib;
		if(isset($_GET['optval']) && $_GET['optval']!='' && $showOptForJaiib==0)  {
			redirect(base_url().'/Dbf/comApplication/');
		}

        if(!isset($_GET['optval']) && $this->session->userdata('selectedoptVal')!=0) {
            redirect(base_url().'/Dbf/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
        }
        if($_GET['optval']!= $this->session->userdata('selectedoptVal')) {
            redirect(base_url().'/Dbf/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
        }

        $data = array('benchmark_disability_info' => $benchmark_disability_info, 'scribe_disability' => $scribe_disability, 'middle_content' => 'dbf/comApplication', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'user_info' => $user_info, 'idtype_master' => $idtype_master, 'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'caiib_subjects' => $caiib_subjects, 'compulsory_subjects' => $compulsory_subjects,'showOptForJaiib'=>$showOptForJaiib,'selectedoptVal'=>$this->session->userdata('selectedoptVal'));//priyanka d- added ,'showOptForJaiib'=>$showOptForJaiib -17-feb-23
        $this->load->view('dbf/dbf_common_view', $data);

    }

    ##------------------ Set applied exam value in session for logged in user(PRAFULL)---------------##
    //public function setExamSession()
    //{
    /*$outputphoto1=$outputsign1=$photo_name=$sign_name='';
    if($this->session->userdata('examinfo'))
    {
    $this->session->unset_userdata('examinfo');
    }
    //Generate dynamic photo
    $date=date('Y-m-d h:i:s');
    $applicationNo = $this->session->userdata('dbregnumber');
    if($_POST["hiddenphoto"]!='')
    {
    $input = $_POST["hiddenphoto"];
    $tmp_nm = 'p_'.$this->session->userdata('dbregnumber').'.jpg';
    $outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
    $outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
    file_put_contents($outputphoto, file_get_contents($input));
    $photo_name = $tmp_nm;*/

    /*$path = "./uploads/photograph";
    //$new_filename = 'photo_'.strtotime($date).rand(1,99999);
    $new_filename = 'p_'.$applicationNo;
    $uploadData = upload_file('hiddenphoto', $path, $new_filename,'100','120',TRUE);
    if($uploadData)
    {
    $photo_name = $uploadData['file_name'];
    }*/
    //}

    // generate dynamic Signature
    /*if($_POST["hiddenscansignature"]!='')
    {
    $inputsignature = $_POST["hiddenscansignature"];
    $tmp_signnm = 's_'.$this->session->userdata('dbregnumber').'.jpg';
    $outputsign = getcwd()."/uploads/scansignature/".$tmp_signnm;
    $outputsign1 = base_url()."uploads/scansignature/".$tmp_signnm;
    file_put_contents($outputsign, file_get_contents($inputsignature));
    $sign_name = $tmp_signnm;*/

    /*$path = "./uploads/scansignature";
    //$new_filename = 'sign_'.strtotime($date).rand(1,99999);
    $new_filename = 's_'.$applicationNo;
    $uploadData = upload_file('hiddenscansignature', $path, $new_filename,'100','120',TRUE);
    if($uploadData)
    {
    $sign_name = $uploadData['file_name'];
    }*/
    /*}

    $user_data=array(    'email'=>$_POST["email"],
    'mobile'=>$_POST["mobile"],
    'photo'=>$photo_name,
    'signname'=>$sign_name,
    'medium'=>$_POST['medium'],
    'selCenterName'=>$_POST['selCenterName'],
    'optmode'=>$_POST['optmode'],
    'extype'=>$_POST['extype'],
    'exname'=>$_POST['exname'],
    'excd'=>$_POST['excd'],
    'eprid'=>$_POST['eprid'],
    'fee'=>$_POST['fee'],
    'txtCenterCode'=>$_POST['txtCenterCode'],
    'insert_id'=>'',
    'selected_elect_subcode'=>$_POST['selSubcode'],
    'selected_elect_subname'=>$_POST['selSubName1'],
    'placeofwork'=>$_POST['placeofwork'],
    'state_place_of_work'=>$_POST['state_place_of_work'],
    'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
    'elected_exam_mode'=>$_POST['elected_exam_mode']
    );
    $this->session->set_userdata('examinfo',$user_data);
    logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
    return 'true';*/
    //}

    ##------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
    public function preview()
    {
        $valcookie = applyexam_get_cookie();
        if ($valcookie) {
            delete_cookie('examid');
        }
        if (!$this->session->userdata('examinfo')) {
            redirect(base_url());
        }
        //check exam acivation
        $check_exam_activation = check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
        if ($check_exam_activation == 0) {
            redirect(base_url() . 'Dbf/accessdenied/');
        }
        ############check capacity is full or not ##########
        $subject_arr = $this->session->userdata['examinfo']['subject_arr'];
        if (count($subject_arr) > 0) {
            $msg          = '';
            $sub_flag     = 1;
            $sub_capacity = 1;
            foreach ($subject_arr as $k => $v) {
                foreach ($subject_arr as $j => $val) {
                    if ($k != $j) {
                        //if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                        if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                            $sub_flag = 0;
                        }
                    }
                }
                $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
				if ($capacity <= 1) 
				{
					//get latest/ in between 2hr record from admit-card.
					$total_admit_count=getLastseat(base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['selCenterName'],$v['venue'],$v['date'],$v['session_time']);
						if($total_admit_count > 0)
						{
							$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
							$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
						}
					}
                if ($msg != '') {
                    $this->session->set_flashdata('error', $msg);
                    redirect(base_url() . 'Dbf/comApplication');
                }
            }
        }
        if ($sub_flag == 0) {
            $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
            redirect(base_url() . 'Dbf/comApplication');
        }

        //check for valid fee
        if ($this->session->userdata['examinfo']['fee'] == 0 || $this->session->userdata['examinfo']['fee'] == '') {
            $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
            redirect(base_url() . 'Dbf/comApplication/');
        }

        $check = $this->examapplied($this->session->userdata('dbregnumber'), $this->session->userdata['examinfo']['excd']);
        if (!$check) {

            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
            $this->db->where('medium_master.exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
            $medium = $this->master_model->getRecords('medium_master');

            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
            $this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
            $this->db->where('center_code', $this->session->userdata['examinfo']['selCenterName']);
            $center = $this->master_model->getRecords('center_master', '', 'center_name');

            //echo $this->db->last_query();exit;
            $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')));

            $this->db->where('state_delete', '0');
            $states = $this->master_model->getRecords('state_master');

            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
            $misc                  = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_delete' => '0'));
            $disability_value      = $this->master_model->getRecords('scribe_disability', array('is_delete' => 0));
            $scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' => 0));

            /* Benchmark Disability */
            $benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('dbregnumber')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
            /* Benchmark Disability Close */

            $data = array('disability_value' => $disability_value,
                'scribe_sub_disability'          => $scribe_sub_disability, 'middle_content' => 'dbf/exam_preview', 'user_info' => $user_info, 'medium' => $medium, 'center' => $center, 'misc' => $misc, 'states' => $states, 'compulsory_subjects' => $this->session->userdata['examinfo']['subject_arr'], 'benchmark_disability_info' => $benchmark_disability_info);
            $this->load->view('dbf/dbf_common_view', $data);
        } else {
            /*$get_period_info=$this->master_model->getRecords('misc_master',array('misc_master.exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),'misc_master.misc_delete'=>'0'),'exam_month');
            $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
            $exam_period_date=date('F',strtotime($month))."-".substr($get_period_info[0]['exam_month'],0,-2);
            $message='Application for this examination is already registered by you and is valid for <strong>'.$exam_period_date.'</strong>.... period. Hence you need not apply for the same.';*/
            $message = 'You have already applied for the examination';
            $data    = array('middle_content' => 'dbf/already_apply', 'check_eligibility' => $message);
            $this->load->view('dbf/dbf_common_view', $data);
        }
    }

    ##------------------Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
    /*public function Msuccess()
    {
    $photoname=$singname='';
    if(($this->session->userdata('examinfo')==''))
    {
    redirect(base_url().'NonMember/dashboard/');
    }
    if(isset($_POST['btnPreview']))
    {
    $inser_array=array(    'regnumber'=>$this->session->userdata('nmregnumber'),
    'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
    'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
    'exam_medium'=>$this->session->userdata['examinfo']['medium'],
    'exam_period'=>$this->session->userdata['examinfo']['eprid'],
    'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
    'exam_fee'=>$this->session->userdata['examinfo']['fee'],
    'pay_status'=>'1',
    'created_on'=>date('y-m-d h:i:sa')
    );
    if($last_exam_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
    {
    $update_array=array();

    //update an array for images
    if($this->session->userdata['examinfo']['photo']!='')
    {
    $update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
    $photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'scannedphoto');
    $photoname=$photo_name[0]['scannedphoto'];
    }
    if($this->session->userdata['examinfo']['signname']!='')
    {
    $update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));
    $sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'scannedsignaturephoto');
    $singname=$sing_name[0]['scannedphoto'];
    }

    //check if email is unique
    $check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email']));
    if($check_email==0)
    {
    $update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));
    }
    // check if mobile is unique
    $check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile']));
    if($check_mobile==0)
    {
    $update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));
    }
    if(count($update_array) > 0)
    {
    $this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')));
    if($photoname!='')
    @unlink('uploads/photograph/'.$photoname);
    if($singname!='')
    @unlink('uploads/scansignature/'.$singname);
    log_nm_activity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
    }

    $this->db->join('state_master','state_master.state_code=member_registration.state');
    $result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('nmregid'),'regnumber'=>$this->session->userdata('nmregnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');

    $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
    $exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$last_exam_id,'member_exam.regnumber'=>$this->session->userdata('nmregnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
    if($exam_info[0]['exam_mode']=='ON')
    {$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
    {$mode='Offline';}
    else{$mode='';}
    $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
    $exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
    //Get Medium
    $this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
    $this->db->where('exam_period',$exam_info[0]['exam_period']);
    $this->db->where('medium_code',$exam_info[0]['exam_medium']);
    $this->db->where('medium_delete','0');
    $medium=$this->master_model->getRecords('medium_master','','medium_description');

    $username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
    $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
    $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'nonmember_exam_enrollment_nofee '));
    $newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
    $newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('nmregnumber')."",$newstring1);
    $newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
    $newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
    $newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
    $newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
    $newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
    $newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
    $newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
    $newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
    $newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
    $newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
    $newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
    $newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
    $newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
    $newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
    $newstring17 = str_replace("#AMOUNT#", "".'-'."",$newstring16);
    $final_str = str_replace("#MODE#", "".$mode."",$newstring17);
    $info_arr=array(    'to'=>$result[0]['email'],
    'from'=>$emailerstr[0]['from'],
    'subject'=>$emailerstr[0]['subject'],
    'message'=>$final_str
    );
    $this->Emailsending->mailsend($info_arr);
    redirect(base_url().'NonMember/applydetails/');
    }
    }
    else
    {
    redirect(base_url().'NonMember/dashboard/');
    }
    }*/

    public function details($order_no = null, $excd = null)
    {
        //payment detail
        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('dbregnumber')));

        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,exam_master.ebook_flag');
        $this->db->where('elg_mem_db', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($excd), 'regnumber' => $this->session->userdata('dbregnumber'), 'member_exam.pay_status' => '1'));

        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard');
        }

        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', base64_decode($excd));
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', base64_decode($excd));
        $this->db->where("center_delete", '0');
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $center = $this->master_model->getRecords('center_master');
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard/');
        }

        /*
        $user_data=array('email'=>'',
        'mobile'=>'',
        'photo'=>'',
        'signname'=>'',
        'medium'=>'',
        'selCenterName'=>'',
        'optmode'=>'',
        'extype'=>'',
        'exname'=>'',
        'excd'=>'',
        'eprid'=>'',
        'fee'=>'',
        'txtCenterCode'=>'',
        'insdet_id'=>'',
        'selected_elect_subcode'=>'',
        'selected_elect_subname'=>'',
        'placeofwork'=>'',
        'state_place_of_work'=>'',
        'pincode_place_of_work'=>'',
        'elected_exam_mode'=>''
        );
        $this->session->unset_userdata('examinfo',$user_data);
         */

        $data = array('middle_content' => 'dbf/exam_applied_success', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info, 'payment_info' => $payment_info);
        $this->load->view('dbf/dbf_common_view', $data);

    }

    //Detail page for non member without pay
    public function applydetails()
    {
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
        $this->db->where('elg_mem_db', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'regnumber' => $this->session->userdata('dbregnumber')));

        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->join('misc_master', 'misc_master.exam_code=center_master.exam_name AND misc_master.exam_period=center_master.exam_period');
        $this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master');

        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $data = array('middle_content' => 'dbf/exam_applied_success_withoutpay', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info);
        $this->load->view('dbf/dbf_common_view', $data);

    }

    /*public function examapplied($regnumber=NULL,$exam_code=NULL)
    {
    //check where exam alredy apply or not
    $today_date=date('Y-m-d');
    $this->db->like('exam_master.elg_mem_db','Y');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
    $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
    //$this->db->where('payment_transaction.status','1');
    $applied_exam_info=$this->master_model->getRecords('member_exam',array('member_exam.exam_code'=>base64_decode($exam_code),'regnumber'=>$regnumber));
    return count($applied_exam_info);
    }*/
    ######### if seat allocation full show message#######
    public function refund($order_no = null)
    {
        //payment detail
        //$this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
        //$this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no)));

        if (count($payment_info) <= 0) {
            redirect(base_url());
        }
        $this->db->where('remark', '2');
        $admit_card_refund = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $payment_info[0]['ref_id']));
        if (count($admit_card_refund) > 0) {
            $update_data = array('remark' => 3);
            $this->master_model->updateRecord('admit_card_details', $update_data, array('mem_exam_id' => $payment_info[0]['ref_id']));
        }
        $exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $payment_info[0]['exam_code']));
        $data      = array('middle_content' => 'dbf/dbf_refund', 'payment_info' => $payment_info, 'exam_name' => $exam_name);
        $this->load->view('dbf/dbf_common_view', $data);
        //$this->load->view('common_view',$data);

    }

    public function examapplied($nmregnumber = null, $exam_code = null)
    {
        //check where exam alredy apply or not
        $today_date = date('Y-m-d');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $this->db->where('exam_master.elg_mem_db', 'Y');
        $this->db->where('pay_status', '1');
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $nmregnumber));
        ####check if number applied through the bulk registration (Prafull)###
        if (count($applied_exam_info) <= 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('exam_master.elg_mem_db', 'Y');
            $this->db->where('bulk_isdelete', '0');
            $this->db->where('institute_id!=', '');
            $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($exam_code), 'regnumber' => $nmregnumber));
        }
        ###### End of check  number applied through the bulk registration###

        //echo $this->db->last_query();exit;
        return count($applied_exam_info);
    }

    //check whether applied exam date fall in same date of other exam date(Prafull)
    public function examdate($nmregnumber = null, $exam_code = null)
    {
        $flag              = 0;
        $today_date        = date('Y-m-d');
        $applied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($exam_code), 'exam_date >=' => $today_date, 'subject_delete' => '0'));
        if (count($applied_exam_date) > 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $nmregnumber, 'pay_status' => '1'), 'member_exam.exam_code');
            ### checking bulk applied ######
            if (count($getapplied_exam_code) <= 0) {
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->where('bulk_isdelete', '0');
                $this->db->where('institute_id!=', '');
                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $nmregnumber, 'pay_status' => '2'), 'member_exam.exam_code');
            }

            if (count($getapplied_exam_code) > 0) {
                foreach ($getapplied_exam_code as $exist_ex_code) {
                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));
                    if (count($getapplied_exam_date) > 0) {
                        foreach ($getapplied_exam_date as $exist_ex_date) {
                            foreach ($applied_exam_date as $sel_ex_date) {
                                if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {
                                    $flag = 1;
                                    break;
                                }
                            }
                            if ($flag == 1) {
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
    public function get_alredy_applied_examname($nmregnumber = null, $exam_code = null)
    {
        $flag       = 0;
        $msg        = '';
        $today_date = date('Y-m-d');

        $this->db->select('subject_master.*,exam_master.description');
        $this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');
        $applied_exam_date = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => base64_decode($exam_code), 'exam_date >=' => $today_date, 'subject_delete' => '0'));
        if (count($applied_exam_date) > 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $nmregnumber, 'pay_status' => '1'), 'member_exam.exam_code,exam_master.description');

            ### checking bulk applied ######
            if (count($getapplied_exam_code) <= 0) {
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->where('bulk_isdelete', '0');
                $this->db->where('institute_id!=', '');
                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '2'), 'member_exam.exam_code,exam_master.description');
            }

            if (count($getapplied_exam_code) > 0) {
                foreach ($getapplied_exam_code as $exist_ex_code) {
                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));
                    if (count($getapplied_exam_date) > 0) {
                        foreach ($getapplied_exam_date as $exist_ex_date) {
                            foreach ($applied_exam_date as $sel_ex_date) {
                                if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {
                                    $msg  = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
                                    $flag = 1;
                                    break;
                                }
                            }
                            if ($flag == 1) {
                                $msg = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $msg;
    }

    ##------------------Insert data in member_exam table for applied exam,for logged in user Without Payment(PRAFULL)---------------##
    /*    public function saveexam()
    {
    $photoname=$singname='';
    if(($this->session->userdata('examinfo')==''))
    {
    redirect(base_url().'Dbf/dashboard/');
    }
    if(isset($_POST['btnPreview']))
    {

    $inser_array=array(    'regnumber'=>$this->session->userdata('dbregnumber'),
    'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
    'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
    'exam_medium'=>$this->session->userdata['examinfo']['medium'],
    'exam_period'=>$this->session->userdata['examinfo']['eprid'],
    'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
    'exam_fee'=>$this->session->userdata['examinfo']['fee'],
    'pay_status'=>'1',
    'created_on'=>date('y-m-d H:i:s')
    );
    if($last_exam_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
    {
    $update_array=array();
    $this->session->userdata['examinfo']['insert_id']=$last_exam_id;
    //update an array for images
    if($this->session->userdata['examinfo']['photo']!='')
    {
    $update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
    $photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedphoto');
    $photoname=$photo_name[0]['scannedphoto'];
    }
    if($this->session->userdata['examinfo']['signname']!='')
    {
    $update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));
    $sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'scannedsignaturephoto');
    $singname=$sing_name[0]['scannedsignaturephoto'];
    }

    //check if email is unique
    $check_email=$this->master_model->getRecordCount('member_registration',array('email'=>$this->session->userdata['examinfo']['email']));
    if($check_email==0)
    {
    $update_array=array_merge($update_array, array("email"=>$this->session->userdata['examinfo']['email']));
    }
    // check if mobile is unique
    $check_mobile=$this->master_model->getRecordCount('member_registration',array('mobile'=>$this->session->userdata['examinfo']['mobile']));
    if($check_mobile==0)
    {
    $update_array=array_merge($update_array, array("mobile"=>$this->session->userdata['examinfo']['mobile']));
    }
    if(count($update_array) > 0)
    {
    $this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')));

    //log_nm_activity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
    //log_dbf_activity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));

    $log_title = "Member update profile during exam apply";
    $log_message = serialize($update_array);
    $rId = $this->session->userdata('dbregid');
    $regNo = $this->session->userdata('dbregnumber');
    storedUserActivity($log_title, $log_message, $rId, $regNo);

    }

    $this->db->join('state_master','state_master.state_code=member_registration.state');
    $result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('dbregid'),'regnumber'=>$this->session->userdata('dbregnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');

    $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
    $exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$last_exam_id,'member_exam.regnumber'=>$this->session->userdata('dbregnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

    if($exam_info[0]['exam_mode']=='ON')
    {$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
    {$mode='Offline';}
    else{$mode='';}
    $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
    $exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
    //Get Medium
    $this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
    $this->db->where('exam_period',$exam_info[0]['exam_period']);
    $this->db->where('medium_code',$exam_info[0]['exam_medium']);
    $this->db->where('medium_delete','0');
    $medium=$this->master_model->getRecords('medium_master','','medium_description');

    $username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
    $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
    $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'nonmember_exam_enrollment_nofee '));
    $newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
    $newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('dbregnumber')."",$newstring1);
    $newstring3 = str_replace("#EXAM_NAME#", "".$exam_info[0]['description']."",$newstring2);
    $newstring4 = str_replace("#EXAM_DATE#", "".$exam_period_date."",$newstring3);
    $newstring5 = str_replace("#ADD1#", "".$result[0]['address1']."",$newstring4);
    $newstring6 = str_replace("#ADD2#", "".$result[0]['address2']."",$newstring5);
    $newstring7 = str_replace("#ADD3#", "".$result[0]['address3']."",$newstring6);
    $newstring8 = str_replace("#ADD4#", "".$result[0]['address4']."",$newstring7);
    $newstring9 = str_replace("#DISTRICT#", "".$result[0]['district']."",$newstring8);
    $newstring10 = str_replace("#CITY#", "".$result[0]['city']."",$newstring9);
    $newstring11 = str_replace("#STATE#", "".$result[0]['state_name']."",$newstring10);
    $newstring12 = str_replace("#PINCODE#", "".$result[0]['pincode']."",$newstring11);
    $newstring13 = str_replace("#EMAIL#", "".$result[0]['email']."",$newstring12);
    $newstring14 = str_replace("#MEDIUM#", "".$medium[0]['medium_description']."",$newstring13);
    $newstring15 = str_replace("#CENTER#", "".$exam_info[0]['center_name']."",$newstring14);
    $newstring16 = str_replace("#CENTER_CODE#", "".$exam_info[0]['exam_center_code']."",$newstring15);
    $newstring17 = str_replace("#AMOUNT#", "".'-'."",$newstring16);
    $final_str = str_replace("#MODE#", "".$mode."",$newstring17);
    $info_arr=array(    'to'=>$result[0]['email'],
    'from'=>$emailerstr[0]['from'],
    'subject'=>$emailerstr[0]['subject'],
    'message'=>$final_str
    );

    $this->Emailsending->mailsend($info_arr);
    redirect(base_url().'Dbf/applydetails/');
    }
    }
    else
    {
    redirect(base_url().'Dbf/dashboard/');
    }
    }*/

    ##------------------NON MEMBER Insert data in member_exam table for applied exam,for logged in user With Payment(PRAFULL)---------------##
    public function Msuccess()
    {
        $photoname = $singname = '';
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Dbf/dashboard/');
        }

        if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {
            if (isset($this->session->userdata['examinfo']['el_subject'][0]) && $this->session->userdata['examinfo']['el_subject'][0] == 'N' && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeJaiib') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeDBF') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeSOB')) {
                unset($this->session->userdata['examinfo']['el_subject'][0]);
            }
            if ($this->session->userdata['examinfo']['el_subject'] == 'N') {
                $el_subject_cnt = 0;
            } else {
                $el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
            }
        } else {
            $el_subject_cnt = 0;
        }

        if (isset($_POST['btnPreview'])) {
            $amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

            if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {

                $el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                $total_elearning_amt = $el_amount * $el_subject_cnt;
               // $amount              = $amount + $total_elearning_amt;

            }
            

            //priyanka d =22-feb-23
				$optFlg	=	'N';
				if(isset($this->session->userdata['examinfo']['optFlg']))
					$optFlg	=	$this->session->userdata['examinfo']['optFlg'];

            $inser_array = array('regnumber' => $this->session->userdata('dbregnumber'),
                'exam_code'                      => base64_decode($this->session->userdata['examinfo']['excd']),
                'exam_mode'                      => $this->session->userdata['examinfo']['optmode'],
                'exam_medium'                    => $this->session->userdata['examinfo']['medium'],
                'exam_period'                    => $this->session->userdata['examinfo']['eprid'],
                'exam_center_code'               => $this->session->userdata['examinfo']['txtCenterCode'],
                'exam_fee'                       => $amount,
                'scribe_flag'                    => $this->session->userdata['examinfo']['scribe_flag'],
                'scribe_flag_PwBD'               => $this->session->userdata['examinfo']['scribe_flag_d'],
                'disability'                     => $this->session->userdata['examinfo']['disability_value'],
                'sub_disability'                 => $this->session->userdata['examinfo']['Sub_menue_disability'],
                'created_on'                     => date('y-m-d H:i:s'),
                'elearning_flag'                 => $this->session->userdata['examinfo']['elearning_flag'],
                'sub_el_count'                   => $el_subject_cnt,
                'optFlg'                         => $optFlg,
            );
            if ($insert_id = $this->master_model->insertRecord('member_exam', $inser_array, true)) {

                //priyanka d >> 26-july-23
            $inser_array = array('regnumber' => $this->session->userdata('dbregnumber'),
            'mem_exam_id'					=> $insert_id,
            'exam_code'                      => base64_decode($this->session->userdata['examinfo']['excd']),
                'exam_period'                    => $this->session->userdata['examinfo']['eprid'],
                
                'collage_institute'                    => $this->session->userdata['examinfo']['collage_institute'],
                'other_collage_institute'               => $this->session->userdata['examinfo']['other_collage_institute'],
            
            );
            $this->master_model->insertRecord('member_institute', $inser_array, true);

                /* User Log Activities : Bhushan */
                $log_title   = "Member exam apply details - Insert Dbf.php";
                $log_message = serialize($inser_array);
                $rId         = $this->session->userdata('regid');
                $regNo       = $this->session->userdata('mregnumber_applyexam');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */

                //echo $this->session->userdata['examinfo']['fee'];
                $this->session->userdata['examinfo']['insert_id'] = $insert_id;
                $update_array                                     = array();
                //update an array for images
                if ($this->session->userdata['examinfo']['photo'] != '') {
                    $update_array = array_merge($update_array, array("scannedphoto" => $this->session->userdata['examinfo']['photo']));
                    $photo_name   = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')), 'scannedphoto');
                    $photoname    = $photo_name[0]['scannedphoto'];
                }
                if ($this->session->userdata['examinfo']['signname'] != '') {
                    $update_array = array_merge($update_array, array("scannedsignaturephoto" => $this->session->userdata['examinfo']['signname']));
                    $sing_name    = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')), 'scannedsignaturephoto');
                    $singname     = $sing_name[0]['scannedsignaturephoto'];
                }

                //check if email is unique
                $where = "( registrationtype='DB')";
                $this->db->where($where);
                $check_email = $this->master_model->getRecordCount('member_registration', array('email' => $this->session->userdata['examinfo']['email'], 'isactive' => '1'));
                if ($check_email == 0) {
                    $update_array = array_merge($update_array, array("email" => $this->session->userdata['examinfo']['email']));
                }
                // check if mobile is unique
                $where = "( registrationtype='DB')";
                $this->db->where($where);
                $check_mobile = $this->master_model->getRecordCount('member_registration', array('mobile' => $this->session->userdata['examinfo']['mobile'], 'isactive' => '1'));
                if ($check_mobile == 0) {
                    $update_array = array_merge($update_array, array("mobile" => $this->session->userdata['examinfo']['mobile']));
                }
                if (count($update_array) > 0) {
                    $this->master_model->updateRecord('member_registration', $update_array, array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber')));
                    //@unlink('uploads/photograph/'.$photoname);
                    //@unlink('uploads/scansignature/'.$singname);

                    /* logactivity($log_title ="Dbf update profile during exam apply", $log_message = serialize($update_array));
                    log_dbf_activity($log_title ="Dbf update profile during exam apply", $log_message = serialize($update_array)); */

                    /* User Log Activities : Bhushan */
                    $log_title   = "Dbf update profile during exam apply";
                    $log_message = serialize($update_array);
                    $rId         = $this->session->userdata('dbregid');
                    $regNo       = $this->session->userdata('dbregnumber');
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    /* Close User Log Actitives */

                }
                if ($this->config->item('exam_apply_gateway') == 'sbi') {
                    redirect(base_url() . 'Dbf/sbi_make_payment/');
                } else {
                    redirect(base_url() . 'Dbfpayment/make_payment/');
                }
            }
        } else {
            redirect(base_url() . 'Dbf/dashboard/');
        }
    }

    ##------------------Exam apply with SBI Payment Gate-way(PRAFULL)---------------##
    public function sbi_make_payment()
    {
        $cgst_rate           = $sgst_rate           = $igst_rate           = $tax_type           = '';
        $cgst_amt            = $sgst_amt            = $igst_amt            = '';
        $cs_total            = $igst_total            = '';
        $total_el_amount     = 0;
        $el_subject_cnt      = 0;
        $total_elearning_amt = 0;
        ## New elarning columns code
        $total_el_base_amount = 0;
        $total_el_gst_amount  = 0;
        $total_el_cgst_amount = 0;
        $total_el_sgst_amount = 0;
        $total_el_igst_amount = 0;
        $getstate             = $getcenter             = $getfees             = array();

        if (!$this->session->userdata('examinfo')) {
            redirect(base_url() . 'Dbfpayment/dashboard/');
        }
        $valcookie = applyexam_get_cookie();
        if ($valcookie) {
            delete_cookie('examid');
            redirect('http://iibf.org.in/');
        }

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
            $checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $this->session->userdata('dbregnumber'), 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $this->session->set_flashdata('error', 'Wait your transaction is under process!.');
                    redirect(base_url() . 'Dbf/comApplication');
                }
            }

            ############check capacity is full or not
            $subject_arr = $this->session->userdata['examinfo']['subject_arr'];
            if (count($subject_arr) > 0) {
                $msg          = '';
                $sub_flag     = 1;
                $sub_capacity = 1;
                foreach ($subject_arr as $k => $v) {
                    foreach ($subject_arr as $j => $val) {
                        if ($k != $j) {
                            //if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                            if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                                $sub_flag = 0;
                            }
                        }
                    }
                     $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
					if ($capacity <= 1) 
					{
					//get latest/ in between 2hr record from admit-card.
					$total_admit_count=getLastseat(base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['txtCenterCode'],$v['venue'],$v['date'],$v['session_time']);
						if($total_admit_count > 0)
						{
							$msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
							$msg =$msg .' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
						}
					}
                    if ($msg != '') {
                        $this->session->set_flashdata('error', $msg);
                        redirect(base_url() . 'Dbf/comApplication');
                    }
                }
            }
            if ($sub_flag == 0) {
                $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                redirect(base_url() . 'Dbf/comApplication');
            }

            $regno = $this->session->userdata('dbregnumber'); //$this->session->userdata('regnumber');
            include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key          = $this->config->item('sbi_m_key');
            $merchIdVal   = $this->config->item('sbi_merchIdVal');
            $AggregatorId = $this->config->item('sbi_AggregatorId');

            $pg_success_url = base_url() . "Dbf/sbitranssuccess";
            $pg_fail_url    = base_url() . "Dbf/sbitransfail";

            if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {
                if ($this->session->userdata['examinfo']['el_subject'] == 'N') {
                    $el_subject_cnt = 0;
                } else {
                    $el_subject_cnt = count($this->session->userdata['examinfo']['el_subject']);
                }
            } else {
                $el_subject_cnt = 0;
            }

            if ($this->config->item('sb_test_mode')) {
                $amount = $this->config->item('exam_apply_fee');
            } else {
                //$amount=$this->session->userdata['examinfo']['fee'];
                $amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {

                    $el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                    $total_elearning_amt = $el_amount * $el_subject_cnt;
                    //$amount              = $amount + $total_elearning_amt;
                    ## New elarning columns code
                    $total_el_base_amount = $el_subject_cnt;
                    $total_el_cgst_amount = $el_subject_cnt;
                    $total_el_sgst_amount = $el_subject_cnt;
                    $total_el_igst_amount = $el_subject_cnt;
                }

            }

            //$MerchantOrderNo = generate_order_id("sbi_exam_order_id");
            // With Login DBF
            // Non memeber / DBF Apply exam
            // Ref1 = orderid
            // Ref2 = iibfexam
            // Ref3 = member_regno
            // Ref4 = exam_code + exam year + exam month ex (101201602)

            $yearmonth = $this->master_model->getRecords('misc_master', array('exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'exam_period' => $this->session->userdata['examinfo']['eprid']), 'exam_month');

            if (base64_decode($this->session->userdata['examinfo']['excd']) == 340 || base64_decode($this->session->userdata['examinfo']['excd']) == 3400) {
                $exam_code = 34;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 580 || base64_decode($this->session->userdata['examinfo']['excd']) == 5800) {
                $exam_code = 58;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1600 || base64_decode($this->session->userdata['examinfo']['excd']) == 16000) {
                $exam_code = 160;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 200) {
                $exam_code = 20;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1770 || base64_decode($this->session->userdata['examinfo']['excd']) == 17700) {
                $exam_code = 177;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 1750) {
                $exam_code = 175;
            } else {
                $exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
            }

            $ref4 = ($exam_code) . $yearmonth[0]['exam_month'];

            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regno,
                'amount'           => $amount,
                'gateway'          => "billdesk",
                'date'             => date('Y-m-d H:i:s'),
                'pay_type'         => '2',
                'ref_id'           => $this->session->userdata['examinfo']['insert_id'],
                'description'      => $this->session->userdata['examinfo']['exname'],
                'status'           => '2',
                'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
                //'receipt_no'       => $MerchantOrderNo,
                'pg_flag'          => 'IIBF_EXAM_DB_EXAM',
                //'pg_other_details'=>$custom_field
            );

            $pt_id = $this->master_model->insertRecord('payment_transaction', $insert_data, true);

            $MerchantOrderNo = sbi_exam_order_id($pt_id);

            // payment gateway custom fields -
            $custom_field          = $MerchantOrderNo . "^iibfexam^" . $this->session->userdata('dbregnumber') . "^" . $ref4;
            $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . "IIBF_EXAM_DB_EXAM" . "-" . $ref4;

            // update receipt no. in payment transaction -
            $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('payment_transaction', $update_data, array('id' => $pt_id));

            //set invoice details(pawan)
            $getcenter = $this->master_model->getRecords('center_master', array('exam_name' => base64_decode($this->session->userdata['examinfo']['excd']), 'center_code' => $this->session->userdata['examinfo']['txtCenterCode'], 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'center_delete' => '0'));
            if (count($getcenter) > 0) {
                //get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array('state_code' => $getcenter[0]['state_code'], 'state_delete' => '0'));

                //call to helper (fee_helper)
                $getfees = getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
            }
            if ($getcenter[0]['state_code'] == 'MAH') {
                //set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {

                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {
                        $cs_total        = $amount;
                        $total_el_amount = $total_elearning_amt;
                        $amount_base     = $getfees[0]['fee_amount'];
                        $cgst_amt        = $getfees[0]['cgst_amt'];
                        $sgst_amt        = $getfees[0]['sgst_amt'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                        $total_el_cgst_amount = $total_el_cgst_amount * $getfees[0]['elearning_cgst_amt'];
                        $total_el_sgst_amount = $total_el_sgst_amount * $getfees[0]['elearning_sgst_amt'];
                        $total_el_gst_amount  = $total_el_cgst_amount + $total_el_sgst_amount;
                    } else {
                        $cs_total        = $getfees[0]['elearning_cs_amt_total'];
                        $total_el_amount = 0;
                        $amount_base     = $getfees[0]['elearning_fee_amt'];

                        $cgst_amt             = $getfees[0]['elearning_cgst_amt'];
                        $sgst_amt             = $getfees[0]['elearning_sgst_amt'];
                        $total_el_base_amount = 0;
                        $total_el_gst_amount  = 0;
                    }
                } else {
                    //set an amount as per rate
                    $cgst_amt = $getfees[0]['cgst_amt'];
                    $sgst_amt = $getfees[0]['sgst_amt'];
                    //set an total amount
                    $cs_total             = $getfees[0]['cs_tot'];
                    $amount_base          = $getfees[0]['fee_amount'];
                    $total_el_base_amount = 0;
                    $total_el_gst_amount  = 0;
                }

                $tax_type = 'Intra';

            }
            /*else if($getcenter[0]['state_code']=='JAM')
            {
            //set a rate (e.g 9%,9% or 18%)
            $cgst_rate=$sgst_rate=$igst_rate='';
            $cgst_amt=$sgst_amt=$igst_amt='';
            $igst_total=$getfees[0]['fee_amount'];
            $tax_type='Inter';
            }*/
            else {
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {

                    $igst_rate = $this->config->item('igst_rate');

                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {
                        $igst_total      = $amount;
                        $total_el_amount = $total_elearning_amt;
                        $amount_base     = $getfees[0]['fee_amount'];
                        $igst_amt        = $getfees[0]['igst_amt'];
                        ## New elarning columns code
                        $total_el_base_amount = $total_el_base_amount * $getfees[0]['elearning_fee_amt'];
                        $total_el_igst_amount = $total_el_igst_amount * $getfees[0]['elearning_igst_amt'];
                        $total_el_gst_amount  = $total_el_igst_amount;
                    } else {
                        $igst_total           = $getfees[0]['elearning_igst_amt_total'];
                        $total_el_amount      = 0;
                        $amount_base          = $getfees[0]['elearning_fee_amt'];
                        $igst_amt             = $getfees[0]['elearning_igst_amt'];
                        $total_el_base_amount = 0;
                        $total_el_gst_amount  = 0;
                    }
                } else {
                    $igst_rate            = $this->config->item('igst_rate');
                    $igst_amt             = $getfees[0]['igst_amt'];
                    $igst_total           = $getfees[0]['igst_tot'];
                    $amount_base          = $getfees[0]['fee_amount'];
                    $total_el_base_amount = 0;
                    $total_el_gst_amount  = 0;
                }
                $tax_type = 'Inter';
            }

            if ($getstate[0]['exempt'] == 'E') {
                $cgst_rate = $sgst_rate = $igst_rate = '';
                $cgst_amt  = $sgst_amt  = $igst_amt  = '';
            }

            $invoice_insert_array = array('pay_txn_id' => $pt_id,
                'receipt_no'                               => $MerchantOrderNo,
                'exam_code'                                => base64_decode($this->session->userdata['examinfo']['excd']),
                'center_code'                              => $getcenter[0]['center_code'],
                'center_name'                              => $getcenter[0]['center_name'],
                'state_of_center'                          => $getcenter[0]['state_code'],
                'member_no'                                => $this->session->userdata('dbregnumber'),
                'app_type'                                 => 'O',
                'exam_period'                              => $this->session->userdata['examinfo']['eprid'],
                'service_code'                             => $this->config->item('exam_service_code'),
                'qty'                                      => '1',
                'state_code'                               => $getstate[0]['state_no'],
                'state_name'                               => $getstate[0]['state_name'],
                'tax_type'                                 => $tax_type,
                'fee_amt'                                  => $amount_base,
                'total_el_amount'                          => $total_el_amount,
                'total_el_base_amount'                     => $total_el_base_amount,
                'total_el_gst_amount'                      => $total_el_gst_amount,
                'cgst_rate'                                => $cgst_rate,
                'cgst_amt'                                 => $cgst_amt,
                'sgst_rate'                                => $sgst_rate,
                'sgst_amt'                                 => $sgst_amt,
                'igst_rate'                                => $igst_rate,
                'igst_amt'                                 => $igst_amt,
                'cs_total'                                 => $cs_total,
                'igst_total'                               => $igst_total,
                'exempt'                                   => $getstate[0]['exempt'],
                'created_on'                               => date('Y-m-d H:i:s'));
            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);

            //if exam invocie entry skip
            if ($inser_id == '') {
                $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);
            }

            $log_title   = "Exam invoice data from dbf cntrlr inser_id = '" . $inser_id . "'";
            $log_message = serialize($invoice_insert_array);
            $rId         = $this->session->userdata('dbregnumber');
            $regNo       = $this->session->userdata('dbregnumber');
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            //insert into admit card table
            //################get userdata###########
            $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'));
            //get associate institute details
            $institute_id     = '';
            $institution_name = '';
            if ($user_info[0]['associatedinstitute'] != '') {
                $institution_master = $this->master_model->getRecords('institution_master', array('institude_id' => $user_info[0]['associatedinstitute']));
                if (count($institution_master) > 0) {
                    $institute_id     = $institution_master[0]['institude_id'];
                    $institution_name = $institution_master[0]['name'];
                }
            }
            //############check Gender########
            if ($user_info[0]['gender'] == 'male') {$gender = 'M';} else { $gender = 'F';}
            //########prepare user name########
            $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

            //###########get State##########
            $states     = $this->master_model->getRecords('state_master', array('state_code' => $user_info[0]['state'], 'state_delete' => '0'));
            $state_name = '';
            if (count($states) > 0) {
                $state_name = $states[0]['state_name'];
            }
            //##############Examination Mode###########
            if ($this->session->userdata['examinfo']['optmode'] == 'ON') {
                $mode = 'Online';
            } else {
                $mode = 'Offline';
            }
            $sub_el_flg = 'N';
            if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                    $this->db->group_by('subject_code');
                    $compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'subject_code' => $k), 'subject_description');
                    $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v['venue'], 'exam_date' => $v['date'], 'session_time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));

                    if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB'))) {

                        if ($this->session->userdata['examinfo']['el_subject'] != 'N') {
                            if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
                                $sub_el_flg = 'Y';
                            } else {
                                $sub_el_flg = 'N';
                            }
                        }

                    }
                    $check_last_seat_available=preventUser(base64_decode($this->session->userdata['examinfo']['excd']),$this->session->userdata['examinfo']['txtCenterCode'],$v['venue'],$v['date'],$v['session_time'],$this->session->userdata['examinfo']['eprid']);
							
							if($check_last_seat_available <= 0)
							{
							$msg ='There is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
							$this->session->set_flashdata('error', $msg);
							redirect(base_url().'Dbf/comApplication');
							}

                    $admitcard_insert_array = array('mem_exam_id' => $this->session->userdata['examinfo']['insert_id'],
                        'center_code'                                 => $getcenter[0]['center_code'],
                        'center_name'                                 => $getcenter[0]['center_name'],
                        'mem_type'                                    => $this->session->userdata('memtype'),
                        'mem_mem_no'                                  => $this->session->userdata('dbregnumber'),
                        'g_1'                                         => $gender,
                        'mam_nam_1'                                   => $userfinalstrname,
                        'mem_adr_1'                                   => $user_info[0]['address1'],
                        'mem_adr_2'                                   => $user_info[0]['address2'],
                        'mem_adr_3'                                   => $user_info[0]['address3'],
                        'mem_adr_4'                                   => $user_info[0]['address4'],
                        'mem_adr_5'                                   => $user_info[0]['district'],
                        'mem_adr_6'                                   => $user_info[0]['city'],
                        'mem_pin_cd'                                  => $user_info[0]['pincode'],
                        'state'                                       => $state_name,
                        'exm_cd'                                      => base64_decode($this->session->userdata['examinfo']['excd']),
                        'exm_prd'                                     => $this->session->userdata['examinfo']['eprid'],
                        'sub_cd '                                     => $k,
                        'sub_dsc'                                     => $compulsory_subjects[0]['subject_description'],
                        'sub_el_flg'                                  => $sub_el_flg,
                        'm_1'                                         => $this->session->userdata['examinfo']['medium'],
                        'inscd'                                       => $institute_id,
                        'insname'                                     => $institution_name,
                        'venueid'                                     => $get_subject_details[0]['venue_code'],
                        'venue_name'                                  => $get_subject_details[0]['venue_name'],
                        'venueadd1'                                   => $get_subject_details[0]['venue_addr1'],
                        'venueadd2'                                   => $get_subject_details[0]['venue_addr2'],
                        'venueadd3'                                   => $get_subject_details[0]['venue_addr3'],
                        'venueadd4'                                   => $get_subject_details[0]['venue_addr4'],
                        'venueadd5'                                   => $get_subject_details[0]['venue_addr5'],
                        'venpin'                                      => $get_subject_details[0]['venue_pincode'],
                        'exam_date'                                   => $get_subject_details[0]['exam_date'],
                        'time'                                        => $get_subject_details[0]['session_time'],
                        'mode'                                        => $mode,
                        'scribe_flag'                                 => $this->session->userdata['examinfo']['scribe_flag'],
                        'scribe_flag_PwBD'                            => $this->session->userdata['examinfo']['scribe_flag_d'],
                        'disability'                                  => $this->session->userdata['examinfo']['disability_value'],
                        'sub_disability'                              => $this->session->userdata['examinfo']['Sub_menue_disability'],
                        'vendor_code'                                 => $get_subject_details[0]['vendor_code'],
                        'remark'                                      => 2,
                        'created_on'                                  => date('Y-m-d H:i:s'));

                    //echo '<pre>';
                    //print_r($admitcard_insert_array);
                    $inser_id = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);
                }
            } else {
                $this->session->set_flashdata('Error', 'Something went wrong!!');
                redirect(base_url() . 'Dbf/comApplication');
            }

            $MerchantCustomerID = $regno;
            //$custom_field = "^iibfexam^^";

            $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'Dbf/handle_billdesk_response', '', '', '', $custom_field_billdesk);

            if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                $data['bdorderid']      = $billdesk_res['bdorderid'];
                $data['token']          = $billdesk_res['token'];
                $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                $data['returnUrl']      = $billdesk_res['returnUrl'];
                $this->load->view('pg_billdesk/pg_billdesk_form', $data);
            } else {
                $this->session->set_flashdata('Error', 'Transaction failed...!');
                redirect(base_url() . 'Dbfuser/preview/');
            }

            /*
        $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
        $data["merchIdVal"]  = $merchIdVal;
        $EncryptTrans = $merchIdVal."|DOM|IN|INR|".$amount."|".$custom_field."|".$pg_success_url."|".$pg_fail_url."|".$AggregatorId."|".$MerchantOrderNo."|".$MerchantCustomerID."|NB|ONLINE|ONLINE";
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();

        $EncryptTrans = $aes->encrypt($EncryptTrans);

        $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
        $this->load->view('pg_sbi_form',$data);*/
        } else {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }

    public function handle_billdesk_response()
    {
       // exit;
        $valcookie = applyexam_get_cookie();
        if ($valcookie) {
            delete_cookie('examid');
        }

        if (isset($_REQUEST['transaction_response'])) {

            $attachpath = $invoiceNumber = $admitcard_pdf = '';

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
            $get_user_regnum   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum)) {
                redirect(base_url() . 'ExamRecovery');
            }
            $new_invoice_id   = $get_user_regnum[0]['ref_id'];
            $member_regnumber = $get_user_regnum[0]['member_regnumber'];
            //Query to get Payment details
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300' && $get_user_regnum[0]['status'] == 2) {

                ######### payment Transaction ############
                //$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');

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
                if ($this->db->affected_rows()) {
                    $get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');
                    if ($get_payment_status[0]['status'] == 1) {
                        if (count($get_user_regnum) > 0) {
                            $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
                        }

                        ######### payment Transaction ############
                        $this->db->trans_start();
                        //$update_data = array('transaction_no' => $transaction_no,'status' => 1, 'transaction_details' => $responsedata[2]." - ".$responsedata[7],'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5],'callback'=>'B2B');

                        $update_data = array(
                            'transaction_no'      => $transaction_no,
                            'status'              => 1,
                            'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                            'auth_code'           => '0300',
                            'bankcode'            => $bankid,
                            'paymode'             => $txn_process_type,
                            'callback'            => 'B2B',
                        );
                        $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                        $this->db->trans_complete();

                        //Query to get user details
                        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                        $result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');

                        //Query to get exam details
                        $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                        $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                        ########## Generate Admit card and allocate Seat #############
                        $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $get_user_regnum[0]['ref_id']));
                        if (count($exam_admicard_details) > 0) {
                            ############check capacity is full or not ##########
                            //$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
                            if (count($exam_admicard_details) > 0) {
                                $msg          = '';
                                $sub_flag     = 1;
                                $sub_capacity = 1;
                                foreach ($exam_admicard_details as $row) {
                                    $capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
                                    if ($capacity == 0) {
                                        #########get message if capacity is full##########
                                        $log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
                                        $log_message = serialize($exam_admicard_details);
                                        $rId         = $get_user_regnum[0]['ref_id'];
                                        $regNo       = $get_user_regnum[0]['member_regnumber'];
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        
                                        $refund_insert_array=array('receipt_no'=>$MerchantOrderNo,'response'=>$encData);
                                        $inser_id = $this->master_model->insertRecord('S2S_direcrt_refund',$refund_insert_array);

                                        $this->refund_after_capacity_full->make_refund($MerchantOrderNo);

                                        redirect(base_url() . 'Dbf/refund/' . base64_encode($MerchantOrderNo));
                                    }
                                }
                            }

                            $password = random_password();
                            foreach ($exam_admicard_details as $row) {
                                $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid'], 'exam_date' => $row['exam_date'], 'session_time' => $row['time'], 'center_code' => $row['center_code']));

                                $admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $get_user_regnum[0]['ref_id'], 'sub_cd' => $row['sub_cd']));

                                //echo $this->db->last_query().'<br>';
                                $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);

                                if ($seat_number != '') {
                                    $final_seat_number = $seat_number;
                                    $update_data       = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));
                                } else {
                                    $admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $admit_card_details[0]['admitcard_id'], 'remark' => 1));
                                    if (count($admit_card_details) > 0) {
                                        $log_title   = "Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
                                        $log_message = serialize($exam_admicard_details);
                                        $rId         = $admit_card_details[0]['admitcard_id'];
                                        $regNo       = $get_user_regnum[0]['member_regnumber'];
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    } else {
                                        $log_title   = "Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
                                        $log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
                                        $rId         = $get_user_regnum[0]['member_regnumber'];
                                        $regNo       = $get_user_regnum[0]['member_regnumber'];
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        //redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
                                    }
                                }
                            }
                            ######update member_exam transaction######

                            if ($get_payment_status[0]['status'] == 1) {
                                $update_data = array('pay_status' => '1');
                                $this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));

                                $log_title   = "DBF controller member exam update:" . $get_user_regnum[0]['ref_id'];
                                $log_message = '';
                                $rId         = $MerchantOrderNo;
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {

                                $log_title   = "DBF controller member exam update fail:" . $get_user_regnum[0]['ref_id'];
                                $log_message = $get_user_regnum[0]['ref_id'];
                                $rId         = $MerchantOrderNo;
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            }
                        } else {
                            redirect(base_url() . 'Dbf/refund/' . base64_encode($MerchantOrderNo));
                        }

                        if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
                        //$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                        $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                        $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                        //Query to get Medium
                        $this->db->where('exam_code', $exam_info[0]['exam_code']);
                        $this->db->where('exam_period', $exam_info[0]['exam_period']);
                        $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                        $this->db->where('medium_delete', '0');
                        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                        //Query to get Payment details
                        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');

                        $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                        $key = $this->config->item('pass_key');
                        $aes = new CryptAES();
                        $aes->set_key(base64_decode($key));
                        $aes->require_pkcs5();
                        $decpass = $aes->decrypt(trim($result[0]['usrpassword']));

                        $emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'non_member_apply_exam_transaction_success'));
                        $newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                        $newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                        $newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                        $newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                        $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                        $newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
                        $newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
                        $newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
                        $newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
                        $newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
                        $newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
                        $newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
                        $newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
                        $newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
                        $newstring15      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                        $newstring16      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                        $newstring17      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                        $newstring18      = str_replace("#MODE#", "" . $mode . "", $newstring17);
                        $newstring19      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring18);
                        $newstring20      = str_replace("#PASS#", "" . $decpass . "", $newstring19);
                        $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                        if (count($elern_msg_string) > 0) {
                            foreach ($elern_msg_string as $row) {
                                $arr_elern_msg_string[] = $row['exam_code'];
                            }
                            if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                $newstring21 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring20);
                            } else {
                                $newstring21 = str_replace("#E-MSG#", '', $newstring20);
                            }
                        } else {
                            $newstring21 = str_replace("#E-MSG#", '', $newstring20);
                        }
                        $final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring21);

                        $info_arr = array('to' => $result[0]['email'],
                            //'to'=>'raajpardeshi@gmail.com',
                            'from'                 => $emailerstr[0]['from'],
                            'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                            'message'              => $final_str,
                        );

                        //get invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));

                        if (count($getinvoice_number) > 0) {
                            /*if($getinvoice_number[0]['state_of_center']=='JAM')
                            {
                            $invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                            if($invoiceNumber)
                            {
                            $invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
                            }
                            }
                            else
                            {*/
                            $invoiceNumber = '';
                            if ($get_payment_status[0]['status'] == 1) {
                                $invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                                $log_title     = "DBF controller member exam update:" . $getinvoice_number[0]['invoice_id'];
                                $log_message   = '';
                                $rId           = $MerchantOrderNo;
                                $regNo         = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            } else {

                                $log_title   = "DBF controller member exam update fail:" . $getinvoice_number[0]['invoice_id'];
                                $log_message = $getinvoice_number[0]['invoice_id'];
                                $rId         = $MerchantOrderNo;
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            }
                            if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                            }
                            //}

                            if ($get_payment_status[0]['status'] == 1) {
                                $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

                                $log_title   = "DBF controller exam invoice update:" . $payment_info[0]['id'];
                                $log_message = '';
                                $rId         = $MerchantOrderNo;
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {

                                $log_title   = "DBF controller exam invoice update fail:" . $payment_info[0]['id'];
                                $log_message = $payment_info[0]['id'];
                                $rId         = $MerchantOrderNo;
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            }
                            ##############Get Admit card#############
                            $admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                        }
                        if ($attachpath != '') {
                            $files          = array($attachpath, $admitcard_pdf);

                            $skipped_admitcard_examcodes =$this->config->item('skippedAdmitCardForExams');
                            //priyanka d >>27-dec-24 >> by default selecting venue for jaiib/caiiib as we don't have to create admitcard from filled form now >> exam_cd
                            if($exam_info[0]['exam_code']!=null && in_array($exam_info[0]['exam_code'],$skipped_admitcard_examcodes)) {
                                $files = array(
                                    $attachpath,
                                   
                                );
                            }
                            
                            $sms_newstring  = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
                            $sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                            $sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
                            $sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
                            //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
                            //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'mUr3FSwGR', $exam_info[0]['exam_code']);
                            $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 15 Sep 2023

                            $this->Emailsending->mailsend_attch($info_arr, $files);
                        }
                    }
                } else {
                    $log_title   = "B2B Update fail:" . $get_user_regnum[0]['member_regnumber'];
                    $log_message = serialize($update_data);
                    $rId         = $MerchantOrderNo;
                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                }
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);

                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
                redirect(base_url() . 'Dbf/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));

            } 
            elseif ($auth_status == '0002') {
                $update_data = array('transaction_no' => $transaction_no, 'status' => 2, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0002', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');

                                    $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

                    redirect(base_url() . 'Dbf/pending/' . base64_encode($MerchantOrderNo));
            }
            else {

                if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

									// $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => 0399, 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');

									$update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0399', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');

									$this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

									$result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,email,mobile');

									//Query to get Payment details
									$payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');

									// Handle transaction
									$get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
									//Query to get exam details
									$this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
									$this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
									$this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
									$exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');

									//$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
									$month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
									$exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

									$username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
									$userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
									$emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
									$newstring1       = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
									$newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
									$newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
									$final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);

									$info_arr = array('to' => $result[0]['email'],
									'from'                 => $emailerstr[0]['from'],
									'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
									'message'              => $final_str,
									);

									$sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
									$sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
									//$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
									//$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
                                    $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 15 Sep 2023

									$this->Emailsending->mailsend($info_arr);
									//Manage Log
									$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
									// $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
								 	$this->log_model->logtransaction("billdesk", $pg_response, $responsedata['transaction_error_type']);

                }
                redirect(base_url() . 'Dbf/fail/' . base64_encode($MerchantOrderNo));
            }
        } else {
            die("Please try again...");
        }
    }

    //SBI Success
    public function sbitranssuccess()
    {
        exit();
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('sbi_m_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encData         = $aes->decrypt($_REQUEST['encData']);
        $responsedata    = explode("|", $encData);
        $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
        $transaction_no  = $responsedata[1];
        $attachpath      = $invoiceNumber      = $admitcard_pdf      = '';
        if (isset($_REQUEST['merchIdVal'])) {
            $merchIdVal = $_REQUEST['merchIdVal'];
        }
        if (isset($_REQUEST['Bank_Code'])) {
            $Bank_Code = $_REQUEST['Bank_Code'];
        }
        if (isset($_REQUEST['pushRespData'])) {
            $encData = $_REQUEST['pushRespData'];
        }
        // Handle transaction success case
        $q_details = sbiqueryapi($MerchantOrderNo);
        if ($q_details) {
            if ($q_details[2] == "SUCCESS") {
                // Handle transaction success case
                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');
                if ($get_user_regnum[0]['status'] == 2) {
                    ######### payment Transaction ############
                    $update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                    $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
                    if ($this->db->affected_rows()) {
                        $get_payment_status = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');
                        if ($get_payment_status[0]['status'] == 1) {
                            if (count($get_user_regnum) > 0) {
                                $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'regnumber,usrpassword,email');
                            }

                            ######### payment Transaction ############
                            $this->db->trans_start();
                            $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                            $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                            $this->db->trans_complete();

                            //Query to get user details
                            $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                            $result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword');

                            //Query to get exam details
                            $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                            $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                            $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');

                            ########## Generate Admit card and allocate Seat #############
                            $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array('mem_exam_id' => $get_user_regnum[0]['ref_id']));
                            if (count($exam_admicard_details) > 0) {
                                ############check capacity is full or not ##########
                                //$subject_arr=$this->session->userdata['examinfo']['subject_arr'];
                                if (count($exam_admicard_details) > 0) {
                                    $msg          = '';
                                    $sub_flag     = 1;
                                    $sub_capacity = 1;
                                    foreach ($exam_admicard_details as $row) {
                                        $capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
                                        if ($capacity == 0) {
                                            #########get message if capacity is full##########
                                            $log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $get_user_regnum[0]['ref_id'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                            redirect(base_url() . 'Dbf/refund/' . base64_encode($MerchantOrderNo));
                                        }
                                    }
                                }

                                $password = random_password();
                                foreach ($exam_admicard_details as $row) {
                                    $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $row['venueid'], 'exam_date' => $row['exam_date'], 'session_time' => $row['time'], 'center_code' => $row['center_code']));

                                    $admit_card_details = $this->master_model->getRecords('admit_card_details', array('venueid' => $row['venueid'], 'exam_date' => $row['exam_date'], 'time' => $row['time'], 'mem_exam_id' => $get_user_regnum[0]['ref_id'], 'sub_cd' => $row['sub_cd']));

                                    //echo $this->db->last_query().'<br>';
                                    $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);

                                    if ($seat_number != '') {
                                        $final_seat_number = $seat_number;
                                        $update_data       = array('pwd' => $password, 'seat_identification' => $final_seat_number, 'remark' => 1, 'modified_on' => date('Y-m-d H:i:s'));
                                        $this->master_model->updateRecord('admit_card_details', $update_data, array('admitcard_id' => $admit_card_details[0]['admitcard_id']));
                                    } else {
                                        $admit_card_details = $this->master_model->getRecords('admit_card_details', array('admitcard_id' => $admit_card_details[0]['admitcard_id'], 'remark' => 1));
                                        if (count($admit_card_details) > 0) {
                                            $log_title   = "Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $admit_card_details[0]['admitcard_id'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        } else {
                                            $log_title   = "Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
                                            $rId         = $get_user_regnum[0]['member_regnumber'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                            //redirect(base_url().'Dbf/refund/'.base64_encode($MerchantOrderNo));
                                        }
                                    }
                                }
                                ######update member_exam transaction######

                                if ($get_payment_status[0]['status'] == 1) {
                                    $update_data = array('pay_status' => '1');
                                    $this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));

                                    $log_title   = "DBF controller member exam update:" . $get_user_regnum[0]['ref_id'];
                                    $log_message = '';
                                    $rId         = $MerchantOrderNo;
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                } else {

                                    $log_title   = "DBF controller member exam update fail:" . $get_user_regnum[0]['ref_id'];
                                    $log_message = $get_user_regnum[0]['ref_id'];
                                    $rId         = $MerchantOrderNo;
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                }
                            } else {
                                redirect(base_url() . 'Dbf/refund/' . base64_encode($MerchantOrderNo));
                            }

                            if ($exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} elseif ($exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';} else { $mode = '';}
                            //$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                            $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                            $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                            //Query to get Medium
                            $this->db->where('exam_code', $exam_info[0]['exam_code']);
                            $this->db->where('exam_period', $exam_info[0]['exam_period']);
                            $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                            $this->db->where('medium_delete', '0');
                            $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

                            //Query to get Payment details
                            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');

                            $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $decpass = $aes->decrypt(trim($result[0]['usrpassword']));

                            $emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'non_member_apply_exam_transaction_success'));
                            $newstring1       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2       = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                            $newstring3       = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4       = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                            $newstring6       = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
                            $newstring7       = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
                            $newstring8       = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
                            $newstring9       = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
                            $newstring10      = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
                            $newstring11      = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
                            $newstring12      = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
                            $newstring13      = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
                            $newstring14      = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
                            $newstring15      = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring14);
                            $newstring16      = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring15);
                            $newstring17      = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring16);
                            $newstring18      = str_replace("#MODE#", "" . $mode . "", $newstring17);
                            $newstring19      = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring18);
                            $newstring20      = str_replace("#PASS#", "" . $decpass . "", $newstring19);
                            $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                            if (count($elern_msg_string) > 0) {
                                foreach ($elern_msg_string as $row) {
                                    $arr_elern_msg_string[] = $row['exam_code'];
                                }
                                if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                    $newstring21 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring20);
                                } else {
                                    $newstring21 = str_replace("#E-MSG#", '', $newstring20);
                                }
                            } else {
                                $newstring21 = str_replace("#E-MSG#", '', $newstring20);
                            }
                            $final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring21);

                            $info_arr = array('to' => $result[0]['email'],
                                //'to'=>'raajpardeshi@gmail.com',
                                'from'                 => $emailerstr[0]['from'],
                                'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                                'message'              => $final_str,
                            );

                            //get invoice
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));

                            if (count($getinvoice_number) > 0) {
                                /*if($getinvoice_number[0]['state_of_center']=='JAM')
                                {
                                $invoiceNumber = generate_exam_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if($invoiceNumber)
                                {
                                $invoiceNumber=$this->config->item('exam_invoice_no_prefix_jammu').$invoiceNumber;
                                }
                                }
                                else
                                {*/
                                $invoiceNumber = '';
                                if ($get_payment_status[0]['status'] == 1) {
                                    $invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                                    $log_title     = "DBF controller member exam update:" . $getinvoice_number[0]['invoice_id'];
                                    $log_message   = '';
                                    $rId           = $MerchantOrderNo;
                                    $regNo         = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                } else {

                                    $log_title   = "DBF controller member exam update fail:" . $getinvoice_number[0]['invoice_id'];
                                    $log_message = $getinvoice_number[0]['invoice_id'];
                                    $rId         = $MerchantOrderNo;
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                }
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                                }
                                //}

                                if ($get_payment_status[0]['status'] == 1) {
                                    $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                    $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                    $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

                                    $log_title   = "DBF controller exam invoice update:" . $payment_info[0]['id'];
                                    $log_message = '';
                                    $rId         = $MerchantOrderNo;
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                } else {

                                    $log_title   = "DBF controller exam invoice update fail:" . $payment_info[0]['id'];
                                    $log_message = $payment_info[0]['id'];
                                    $rId         = $MerchantOrderNo;
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                }
                                ##############Get Admit card#############
                                $admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                            }
                            if ($attachpath != '') {
                                $files          = array($attachpath, $admitcard_pdf);
                                $sms_newstring  = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
                                $sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                                $sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
                                $sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
                                //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
                                //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'mUr3FSwGR', $exam_info[0]['exam_code']);
                                $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 15 Sep 2023

                                $this->Emailsending->mailsend_attch($info_arr, $files);
                            }
                        }
                    } else {
                        $log_title   = "B2B Update fail:" . $get_user_regnum[0]['member_regnumber'];
                        $log_message = serialize($update_data);
                        $rId         = $MerchantOrderNo;
                        $regNo       = $get_user_regnum[0]['member_regnumber'];
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                    }
                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                }
            }
        }
        //MAIN CODE
        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
        $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
        redirect(base_url() . 'Dbf/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));

    }

    //SBI Failure
    public function sbitransfail()
    {
        exit();
        if (isset($_REQUEST['encData'])) {
            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key = $this->config->item('sbi_m_key');
            $aes = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0]; // To DO: temp testing changes please remove it and use valid recipt id
            $transaction_no  = $responsedata[1];

            // SBI CALLBACK B2B
            // Handle transaction fail case
            $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status');
            //if($get_user_regnum[0]['status']==2)
            //{
            if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {
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

                $result = $this->master_model->getRecords('member_registration', array('regnumber' => $get_user_regnum[0]['member_regnumber']), 'firstname,middlename,lastname,email,mobile');

                //Query to get Payment details
                $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount');

                // Handle transaction
                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id');
                //Query to get exam details
                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $get_user_regnum[0]['member_regnumber'], 'member_exam.id' => $get_user_regnum[0]['ref_id']), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');

                //$month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);

                $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'transaction_fail'));
                $newstring1       = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
                $newstring2       = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
                $newstring3       = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
                $final_str        = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);

                $info_arr = array('to' => $result[0]['email'],
                    'from'                 => $emailerstr[0]['from'],
                    'subject'              => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                    'message'              => $final_str,
                );

                $sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
                $sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                //$this->master_model->send_sms($result[0]['mobile'],$sms_final_str);
                //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
                $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 15 Sep 2023

                $this->Emailsending->mailsend($info_arr);
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            //End Of SBI CallBack

            redirect(base_url() . 'Dbf/fail/' . base64_encode($MerchantOrderNo));
            //echo 'transaction fail';exit;

            //echo 'transaction fail';exit;
        } else {
            die("Please try again...");
        }
    }

    //Show acknowlodgement to to user after transaction Failure
    public function fail($order_no = null)
    {
        //payment detail
        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('dbregnumber')));
        if (count($payment_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $data = array('middle_content' => 'dbf/exam_applied_fail', 'payment_info' => $payment_info);
        $this->load->view('dbf/dbf_common_view', $data);

    }

      public function pending($order_no = null)
    {
        //payment detail
        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('dbregnumber')));
        if (count($payment_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $data = array('middle_content' => 'dbf/exam_applied_pending', 'payment_info' => $payment_info);
        $this->load->view('dbf/dbf_common_view', $data);

    }

    
    ##---------Forcefully Update profile mesage to user(prafull)-----------##
    public function notification()
    {
        $msg         = '';
        $flag        = 1;
        $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        if (!file_exists('./uploads/photograph/' . $user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/' . $user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/' . $user_images[0]['idproofphoto']) || $user_images[0]['scannedphoto'] == '' || $user_images[0]['scannedsignaturephoto'] == '' || $user_images[0]['idproofphoto'] == '') {
            $flag = 0;
            $msg .= '<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="' . base_url() . 'Dbf/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';
        }
        if ($user_images[0]['mobile'] == '' || $user_images[0]['email'] == '') {
            $flag = 0;
            $msg .= '<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="' . base_url() . 'Dbf/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
        }

/*        if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']) && ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))
{
$flag=0;
$msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>
<li>
Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'NonMember/profile/">click here</a> to update the, then email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
}*/

        if ($flag) {
            redirect(base_url() . 'Dbf/profile/');
        }
        $data = array('middle_content' => 'dbf/dbfmember_notification', 'msg' => $msg);
        $this->load->view('dbf/dbf_common_view', $data);
    }

    //print user edit profile (Prafull)
    public function printUser()
    {
        $qualification = array();
        $this->db->select('member_registration.*,state_master.state_name');
        //$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        //$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
        //$this->db->where('institution_master.institution_delete','0');
        $this->db->where('state_master.state_delete', '0');
        //$this->db->where('designation_master.designation_delete','0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'));

        if (count($user_info) <= 0) {
            redirect(base_url() . 'Dbf/dashboard');
        }
        if (count($user_info) < 0) {
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
        $data          = array('middle_content' => 'dbf/print_non_member_profile', 'user_info' => $user_info, 'qualification' => $qualification, 'idtype_master' => $idtype_master);
        $this->load->view('dbf/dbf_common_view', $data);
    }

    // ##-------download pdf (Prafull)
    public function downloadeditprofile()
    {
        $qualification = array();
        $this->db->select('member_registration.*,state_master.state_name');
        //$this->db->join('institution_master','institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        //$this->db->join('designation_master','designation_master.dcode=member_registration.designation');
        //$this->db->where('institution_master.institution_delete','0');
        $this->db->where('state_master.state_delete', '0');
        //$this->db->where('designation_master.designation_delete','0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'));
        if (count($user_info) < 0) {
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

        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $userfinalstrname;
        if ($user_info[0]['gender'] == 'female') {$gender = 'Female';}
        if ($user_info[0]['gender'] == 'male') {$gender = 'Male';}
        if ($user_info[0]['qualification'] == 'U') {$memqualification = 'Under Graduate';}
        if ($user_info[0]['qualification'] == 'G') {$memqualification = 'Graduate';}
        if ($user_info[0]['qualification'] == 'P') {$memqualification = 'Post Graduate';}

        if ($user_info[0]['optnletter'] == 'Y') {$optnletter = 'Yes';}
        if ($user_info[0]['optnletter'] == 'N') {$optnletter = 'No';}

        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
        if ($user_info[0]['address2'] != '') {
            $user_info[0]['address2'] = ',' . $user_info[0]['address2'] . '*';
        }
        if ($user_info[0]['address3'] != '') {
            $user_info[0]['address3'] = ',' . $user_info[0]['address3'];
        }
        if ($user_info[0]['address4'] != '') {
            $user_info[0]['address4'] = ',' . $user_info[0]['address4'];
        }
        $string1   = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'];
        $finalstr1 = str_replace("*", "<br>", $string1);
        $string2   = ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . '*' . $user_info[0]['state_name'] . ',' . $user_info[0]['pincode'];
        $finalstr2 = str_replace("*", ",<br>", $string2);
        $useradd   = $finalstr1 . $finalstr2;
        $html      = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
    border: 1px solid #000; padding:25px;
  ">
<tbody><tr> <td colspan="4" align="left">&nbsp;</td> </tr>
<tr>
	<td colspan="4" align="center" height="25">
		<span id="1001a1" class="alert">
		</span>
	</td>
</tr>
<tr style="border-bottom:solid 1px #000;">
	<td colspan="4" height="1"><img src="' . base_url() . 'assets/images/logo1.png"></td>
</tr>

<tr>
	<td colspan="4">
	</hr>
	<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
					<tbody><tr>
			<td class="tablecontent2" width="51%">Membership No : </td>
			<td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
			<td class="tablecontent" rowspan="4" valign="top">
			<img src="' . base_url() . 'uploads/photograph/' . $user_info[0]['scannedphoto'] . '" height="100" width="100" >
			</td>
		</tr>
				<tr>
			<td class="tablecontent2">Password :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">' . $decpass . ' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Full Name :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
			</td>
		</tr>
				<tr>
				<td class="tablecontent2">Name as to appear on Card :</td>
					<td colspan="2" class="tablecontent2" nowrap="nowrap">
					' . $user_info[0]['displayname'] . '
				</td>
		</tr>
		<tr>
			<td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
			<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
				' . $useradd . '			</td>
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
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['email'] . ' </td>
		</tr>


		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['mobile'] . ' </td>
		</tr>

			<tr>
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . '</td>
		</tr>
		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $idtype_master[0]['name'] . '</td>
		</tr>
		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['idNo'] . '</td>
		</tr>

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="' . base_url() . 'uploads/idproof/' . $user_info[0]['idproofphoto'] . '"  height="180" width="100"></td>
		</tr>
		<tr>
			<td class="tablecontent2">Signature :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . 'uploads/scansignature/' . $user_info[0]['scannedsignaturephoto'] . '" height="100" width="100"></td>
		</tr>
		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				' . date('d-m-Y h:i:s A', strtotime($user_info[0]['createdon'])) . '		</td>
		</tr>
		</tbody></table>
	</td>
</tr>

</tbody></table>';

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

    // ##---------Download pdf(Prafull)-----------##
    public function pdf()
    {
        $html = '<div class="content-wrapper">
		<section class="content-header">
	   <center>    <h3>
		 INDIAN INSTITUTE OF BANKING & FINANCE
		  </h3>
		 <p><span class="box-header with-border"> (AN ISO 9001:2008 Certified ) </span></p></center>
		</section>
		<section class="content">
		  <div class="row">
			<div class="col-md-12">
			  <div class="box box-info">
				<div class="box-header with-border">
				</div>
				<div class="box-body">
				   <div class="form-group">
					<label for="roleid" class="col-sm-3 control-label"></label>
						<center>
						<div class="col-sm-2">
						Your application saved successfully.<br><br><strong>Your Membership No is</strong> ' . $this->session->userdata('dbregnumber') . ' <strong>and Your password is </strong>' . base64_decode($this->session->userdata('nmpassword')) . '<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence
						</div>
						</center>
					</div>
				 </div>
			  </div>
			</div>
		  </div>
		</section>
	 </div>';

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
								table.tbl-2 { outline: none; width: 100%; border-right:1px solid #cccaca; border-top: 1px solid #cccaca;}
								table.tbl-2 th { background: #222D3A; border-bottom: 1px solid #cccaca; border-left:1px solid #dbdada; color: #fff; padding: 5px; text-align: center;}
								table.tbl-2 th.head { background: #CECECE; text-align:left;}
								table.tbl-2 td.tda2 { background: #f7f7f7; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tdb2 { background: #ebeaea; color: #4c4c4c; border-bottom: 1px solid #cccaca; border-left:1px solid #cccaca; line-height: 18px; padding: 5px; text-align: left;}
								table.tbl-2 td.tda2 a { color: #0d64a0;}
								table.tbl-2 td.tda2 a:hover{ color: #0d64a0; text-decoration:none;}
								table.tbl-2 td.tdb2 a { color: #0d64a0;}
								table.tbl-2 td.tdb2 a:hover{ color: #0d64a0; text-decoration:none;}
								.align_class_table{text-align:center !important;}
								.align_class_table_right{text-align:right !important;}';
        header('Content-Type: application/pdf');
        header('Content-Description: inline; filename.pdf');
        $pdf->WriteHTML($stylesheet, 1);
        $pdf->WriteHTML($html, 2);
        $pdf->Output($pdfFilePath, 'D');
    }

    // ##---------Download exam applied pdf(Prafull)-----------##
    public function exampdf()
    {
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $qualification = array();
        $this->db->select('member_registration.*,state_master.state_name');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->where('state_master.state_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'));
        if (count($user_info) < 0) {
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

        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $userfinalstrname;
        if ($user_info[0]['gender'] == 'female') {$gender = 'Female';}
        if ($user_info[0]['gender'] == 'male') {$gender = 'Male';}
        if ($user_info[0]['qualification'] == 'U') {$memqualification = 'Under Graduate';}
        if ($user_info[0]['qualification'] == 'G') {$memqualification = 'Graduate';}
        if ($user_info[0]['qualification'] == 'P') {$memqualification = 'Post Graduate';}

        if ($user_info[0]['optnletter'] == 'Y') {$optnletter = 'Yes';}
        if ($user_info[0]['optnletter'] == 'N') {$optnletter = 'No';}

        if ($user_info[0]['address2'] != '') {
            $user_info[0]['address2'] = ',' . $user_info[0]['address2'];
        }
        if ($user_info[0]['address3'] != '') {
            $user_info[0]['address3'] = ',' . $user_info[0]['address3'] . '*';
        }
        if ($user_info[0]['address4'] != '') {
            $user_info[0]['address4'] = ',' . $user_info[0]['address4'];
        }
        $string1   = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'];
        $finalstr1 = str_replace("*", "<br>", $string1);
        $string2   = ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . '*' . $user_info[0]['state_name'] . ',' . $user_info[0]['pincode'];
        $finalstr2 = str_replace("*", ",<br>", $string2);
        $useradd   = $finalstr1 . $finalstr2;

        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
        $this->db->where('elg_mem_db', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'regnumber' => $this->session->userdata('dbregnumber')));

        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master');

        //$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
        $month       = date('Y') . "-" . substr($applied_exam_info['0']['exam_month'], 4);
        $exam_period = date('F', strtotime($month)) . "-" . substr($applied_exam_info['0']['exam_month'], 0, -2);
        if ($applied_exam_info[0]['exam_mode'] == 'ON') {$mode = 'Online';} else if ($applied_exam_info[0]['exam_mode'] == 'OF') {$mode = 'Offline';}

        $html = '
			<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;border: 1px solid #000; padding:25px;">
				<tbody>
				<tr> <td colspan="4" align="left">&nbsp;</td> </tr>
				<tr>
					<td colspan="4" align="center" height="25"><span id="1001a1" class="alert"></span></td>
				</tr>
				<tr style="border-bottom:solid 1px #000;">
					<td colspan="4" height="1"><img src="' . base_url() . 'assets/images/logo1.png"></td>
				</tr>
				<tr></tr>
		    <tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>
			<tr><br></tr>
		<tr>
			<td colspan="4">
			</hr>
			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
			<tbody><tr>
			<td class="tablecontent2" width="51%">Membership No : </td>
			<td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
			<td class="tablecontent" rowspan="4" valign="top">
			<img src="' . base_url() . get_img_name($this->session->userdata('dbregnumber'), 'p') . '" height="100" width="100" >
			</td>
		</tr>
		<tr>
			<td class="tablecontent2">Full Name :</td>
			<td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
			</td>
		</tr>
		<tr>
			<td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
			<td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">' . $useradd . '</td>
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
			<td class="tablecontent2">Email :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['email'] . ' </td>
		</tr>
		<tr>
			<td class="tablecontent2">Mobile :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['mobile'] . ' </td>
		</tr>

		<tr>
			<td class="tablecontent2">Aadhar Card Number :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . ' </td>
		</tr>

		<tr>
			<td class="tablecontent2">ID Proof :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $idtype_master[0]['name'] . '</td>
		</tr>
		<tr>
			<td class="tablecontent2">ID No :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['idNo'] . '</td>
		</tr>
                <tr>
			<td class="tablecontent2">Exam Name :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $applied_exam_info[0]['description'] . '</td>
		</tr>
          <tr>
			<td class="tablecontent2">Amount :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $applied_exam_info[0]['exam_fee'] . '</td>
		</tr>
      <tr>
			<td class="tablecontent2">Exam Period :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $exam_period . '</td>
		</tr>
         <tr>
			<td class="tablecontent2">Mode :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $mode . '</td>
		</tr>
            <tr>
			<td class="tablecontent2">Medium :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $medium[0]['medium_description'] . '</td>
		</tr>
		<tr>
			<td class="tablecontent2">Centre Name :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $center[0]['center_name'] . '</td>
		</tr>
		<tr>
			<td class="tablecontent2">Date :</td>
			<td colspan="3" class="tablecontent2" nowrap="nowrap">
				' . date('d-m-Y h:i:s A') . '		</td>
		</tr>
		</tbody></table>
	</td>
</tr>
</tbody></table>';
        //this the the PDF filename that user will get to download
        $pdfFilePath = 'exam' . '.pdf';
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

    //##----Print Exam Details pdf (Prafull)
    public function printexamdetails()
    {
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Dbf/dashboard/');
        }
        $qualification = array();
        $this->db->select('member_registration.*,state_master.state_name');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->where('state_master.state_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('dbregid'), 'regnumber' => $this->session->userdata('dbregnumber'), 'isactive' => '1'));

        if (count($user_info) < 0) {
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

        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.created_on');
        $this->db->where('elg_mem_db', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'regnumber' => $this->session->userdata('dbregnumber')));

        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master');

        $data = array('middle_content' => 'dbf/print_dbf_applied_exam_details', 'user_info' => $user_info, 'qualification' => $qualification, 'idtype_master' => $idtype_master, 'applied_exam_info' => $applied_exam_info, 'center' => $center, 'medium' => $medium);
        $this->load->view('dbf/dbf_common_view', $data);

    }

    ##---------check pincode/zipcode alredy exist or not (prafull)-----------##
    public function checkpin()
    {
        $statecode = $_POST['statecode'];
        $pincode   = $_POST['pincode'];
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
            //echo $this->db->last_query();
            if ($prev_count == 0) {echo 'false';} else {echo 'true';}
        } else {
            echo 'false';
        }
    }

    //dbf admit card
    public function getadmitcard()
    {
        //To Do-- validate as per admin admit card setting(Need to Do)
        try {
            $img_path  = base_url() . "uploads/photograph/";
            $sig_path  = base_url() . "uploads/scansignature/";
            $member_id = $this->session->userdata('dbregnumber');
            $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
            $this->db->from('admitcard_info');
            $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
            $this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
            $record      = $this->db->get();
            $result      = $record->row();
            $exam_code   = $result->exm_cd;
            $medium_code = $result->m_1;

            $this->db->select('description');
            $exam        = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
            $exam_result = $exam->row();

            $this->db->select('subject_description,date,time');
            $this->db->from('subject_master');
            $this->db->join('admitcard_info', 'subject_master.subject_code = admitcard_info.sub_cd');
            $this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
            $subject        = $this->db->get();
            $subject_result = $subject->result();

            $exdate    = $subject_result[0]->date;
            $examdate  = explode("-", $exdate);
            $printdate = $examdate[1] . " 20" . $examdate[2];

            $this->db->select('medium_description');
            $medium        = $this->db->get_where('medium_master', array('medium_code' => $medium_code));
            $medium_result = $medium->row();

            //$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description);
            //$this->load->view('welcome_message', $data);

            //echo $result->center_code;
            $data = array("exam_name" => $exam_result->description, "subject" => $subject_result, "record" => $result, "medium" => $medium_result->medium_description, "img_path" => $img_path, "sig_path" => $sig_path, "subjectprint" => $subject_result, "examdate" => $printdate);
            //load the view and saved it into $html variable
            $this->load->view('dbf/admitcard', $data);

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getadmitcardpdf()
    {
        //To Do-- validate as per admin admit card setting(Need to Do)
        try {
            $img_path  = base_url() . "uploads/photograph/";
            $sig_path  = base_url() . "uploads/scansignature/";
            $member_id = $this->session->userdata('dbregnumber');
            $this->db->select('admitcard_info.*,member_registration.scannedphoto,member_registration.scannedsignaturephoto');
            $this->db->from('admitcard_info');
            $this->db->join('member_registration', 'admitcard_info.mem_mem_no = member_registration.regnumber');
            $this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
            $record      = $this->db->get();
            $result      = $record->row();
            $exam_code   = $result->exm_cd;
            $medium_code = $result->m_1;

            $this->db->select('description');
            $exam        = $this->db->get_where('exam_master', array('exam_code' => $exam_code));
            $exam_result = $exam->row();

            $this->db->select('subject_description,date,time');
            $this->db->from('subject_master');
            $this->db->join('admitcard_info', 'subject_master.subject_code = admitcard_info.sub_cd');
            $this->db->where(array('admitcard_info.mem_mem_no' => $member_id));
            $subject        = $this->db->get();
            $subject_result = $subject->result();

            $exdate    = $subject_result[0]->date;
            $examdate  = explode("-", $exdate);
            $printdate = $examdate[1] . " 20" . $examdate[2];

            $this->db->select('medium_description');
            $medium        = $this->db->get_where('medium_master', array('medium_code' => $medium_code));
            $medium_result = $medium->row();

            //$data = array("exam_name"=>$exam_result->description,"subject"=>$subject_result,"record"=>$result,"medium"=>$medium_result->medium_description);
            //$this->load->view('welcome_message', $data);

            //echo $result->center_code;
            $data = array("exam_name" => $exam_result->description, "subject" => $subject_result, "record" => $result, "medium" => $medium_result->medium_description, "img_path" => $img_path, "sig_path" => $sig_path, "examdate" => $printdate);
            //load the view and saved it into $html variable
            $html = $this->load->view('dbf/admitcardpdf', $data, true);
            //this the the PDF filename that user will get to download
            $this->load->library('m_pdf');
            $pdf         = $this->m_pdf->load();
            $pdfFilePath = "IIBF_ADMIT_CARD_" . $member_id . ".pdf";
            //generate the PDF from the given html
            $pdf->WriteHTML($html);
            //download it.
            $pdf->Output($pdfFilePath, "D");

        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    ##---------End user logout (Vrushali)-----------##//
    public function Logout()
    {
        $sessionData = $this->session->all_userdata();
        foreach ($sessionData as $key => $val) {
            $this->session->unset_userdata($key);
        }
        //redirect(base_url().'nonreg/memlogin/?Extype=MQ==&Mtype=Tk0=');
        redirect(base_url() . 'Dbfuser/login/');
    }

    ##------- check eligible user----------##
    public function checkusers($examcode = null)
    {
        $flag = 0;
        if ($examcode != null) {
            $exam_code = array(33, 47, 51, 52);
            if (in_array($examcode, $exam_code)) {
                $this->db->where_in('eligible_master.exam_code', $exam_code);
                $valid_member_list = $this->master_model->getRecords('eligible_master', array('eligible_period' => '802', 'member_type' => 'DB'), 'member_no');
                if (count($valid_member_list) > 0) {
                    foreach ($valid_member_list as $row) {
                        $memberlist_arr[] = $row['member_no'];
                    }
                    if (in_array($this->session->userdata('dbregnumber'), $memberlist_arr)) {
                        $flag = 1;
                    } else {
                        $flag = 0;
                    }
                } else {
                    $flag = 0;
                }
            } else {
                $flag = 1;
            }
        }
        return $flag;

    }

    // Remove an item from string
    public function removeFromString($str, $item)
    {
        $parts = explode(',', $str);
        while (($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }
        return implode(',', $parts);
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
                return false;} else {
                $this->form_validation->set_message('error', "");
            }

            {return true;}
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin', $str);
            return false;
        }
    }
    public function check_mobileduplication($mobile)
    {
        if ($mobile != "") {
            $where = "( registrationtype='DB')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $this->session->userdata('dbregid'), 'isactive' => '1'));

            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {
                $str = 'The entered Mobile no already exist';
                $this->form_validation->set_message('check_mobileduplication', $str);
                return false;
            }
        } else {
            return false;
        }
    }
    public function check_emailduplication($email)
    {
        if ($email != "") {
            $where = "( registrationtype='DB')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $this->session->userdata('dbregid'), 'isactive' => '1'));
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

    /* Get scribe drop down*/
    public function getsub_menue()
    {
        $deptid = $this->input->post('deptid');
        // Code for fetching department Dropdown
        $scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('code' => $deptid, 'is_delete' => '0'));

        // EOF Code fetching department Dropdown
        $department_dropdown = $search_department = '';
        if (!empty($scribe_sub_disability)) {
            $department_dropdown .= "<select class='form-control' id='Sub_menue' name='Sub_menue'>";

            $department_dropdown .= "<option value=''>--Select--</option>";

            foreach ($scribe_sub_disability as $dkey => $dValue) {
                $deptid    = $dValue['sub_code'];
                $dept_name = $dValue['sub_disability'];

                $department_dropdown .= "<option value=" . $dValue['sub_code'] . ">" . $dept_name . "</option>";
            }
            $department_dropdown .= "</select>";
            echo $department_dropdown;
        } else {
            echo $department_dropdown = "";
        }
    }

    public function set_dbf_elsub_cnt()
    {
        $subject_cnt_arr = array('subject_cnt' => $_POST['subject_cnt']);
        $this->session->set_userdata($subject_cnt_arr);
    }
    public function getsetAsFresherOrOld() // priyanka d- 27-feb-23 >> put candidate selection in session and keep it one till he ends session
    {
        if(isset($_GET['method']) && $_GET['method']=='get')
        {
            echo $this->session->userdata('selectedoptVal');
            return $this->session->userdata('selectedoptVal');;
        }
        if($this->session->userdata('selectedoptVal')!=0) {
            echo $this->session->userdata('selectedoptVal');
            return $this->session->userdata('selectedoptVal');;
        }
        $selectedoptVal = array('selectedoptVal' => $_GET['optVal']);
        $this->session->set_userdata($selectedoptVal);
        $this->session->set_userdata('selectedoptVal_examcode',$this->session->userdata('examcode'));
        echo $_GET['optVal'];

    }
}
