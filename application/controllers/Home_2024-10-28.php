<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('url');
        $this->load->model('master_model');
        $this->load->library('email');
        $this->load->model('chk_session');
        $this->load->model('Emailsending');
        $this->load->helper('cookie');
        $this->load->model('log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->model('refund_after_capacity_full');
        
        $this->load->model('KYC_Log_model');
        $this->chk_session->chk_member_session();
        if ($this->router->fetch_method() != 'comApplication' && $this->router->fetch_method() != 'preview' && $this->router->fetch_method() != 'Msuccess' && $this->router->fetch_method() != 'editmobile' && $this->router->fetch_method() != 'editemailduplication' && $this->router->fetch_method() != 'setExamSession' && $this->router->fetch_method() != 'saveexam' && $this->router->fetch_method() != 'savedetails' && $this->router->fetch_method() != 'exampdf' && $this->router->fetch_method() != 'printexamdetails' && $this->router->fetch_method() != 'details' && $this->router->fetch_method() != 'sbi_make_payment' && $this->router->fetch_method() != 'sbitranssuccess' && $this->router->fetch_method() != 'sbitransfail' && $this->router->fetch_method() != 'accessdenied' && $this->router->fetch_method() != 'getFee' && $this->router->fetch_method() != 'check_emailduplication' && $this->router->fetch_method() != 'check_mobileduplication' && $this->router->fetch_method() != 'check_checkpin' && $this->router->fetch_method() != 'refund' && $this->router->fetch_method() != 'checkpin' && $this->router->fetch_method() != 'getsub_menue' && $this->router->fetch_method() != 'info' && $this->router->fetch_method() != 'set_jaiib_elsub_cnt' && $this->router->fetch_method() != 'handle_billdesk_response' && $this->router->fetch_method() != 'getsetAsFresherOrOld' ) { // priyanka d- 10-feb-23 >> added handle_billdesk_response ,getsetAsFresherOrOld
         //  echo $this->router->fetch_method();exit; 
            if ($this->session->userdata('examinfo')) {
                $this->session->unset_userdata('examinfo'); 
            }
            if ($this->session->userdata('examcode')) {
                $this->session->unset_userdata('examcode');
            }
        }
        // exit;

        //XXX : START : Step 1 : Allowing member to register for JAIIB / CAIIB after registration closed
        $this->jaiib_reschedule_arr = array(500083125,510081606);//,500213680,510257433,510330745,500083125,510081606
          //array(); //
        //$this->jaiib_reschedule_arr = array(510426448,510495669); //array(); //
        //$this->jaiib_reschedule_arr = array(510288264,510508977,510521163,510521099,510512062,510472087,510197697,510444276,510461695,510451083,510413635,510188989,510513020,510413954,510020465,510487712,510258585,510528420,510264396,510389081,510289957,510047002,510294385,510529505,510495151,510074554,510101717,510215362,510329146,510441939,510442257,510465956,510466608,510479640,510480313,510493962,510495037,510495274,510496372,510501764,510503827,510505111,510506751,510521316,510523897,510524427,200014306,510023664,510256290,510328144,510165330,510077051,510496472,510391243,510391638,510397622,510415785,510446312,510502110,510514925,510521635,510489811,510441256,510211497,510477635,510528488,500168741,500189038,510141261,510275840,510368704,510370098,510388472,510446068,510463993,510467487,510469232,510471897,510477378,510481810,510485654,510494500,510504147,510509258,510518420,510519132,510521617,510522816,510523177,510525341,510526742,510527152,510529791,510515931,510455219,510172330,510443566,510464687,510332186,510378326,510463343,510514103,510524790,510518450,510226624,510528614,510506383,510435624,510459828,510053429,510336647,510526615,510330503,510186449,510514083,510234890,510378019,510175412,510403491,510464678,510192752,510252767,510526675,510511760,510218606,510331497,510439508,510450888,510479605,510127799,510240441,510444174,510473471,510498935,510515027,510521852,510518414,500180395,510438305,510434000,510513587,510492287,510317210,510487695,510521321,500066107,510124700,510192764,510459102,510502822,510504040,700025527,700023854,700016574,510470193,510483891,510499754,510515960,510519513,510525184,510512312);
        //XXX : END : Step 1 : Allowing member to register for JAIIB / CAIIB after registration closed
      
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

    public function applicationForm()
    {
        // $this->chk_session->checkphoto();
        $this->load->view('application_block');
    }

    // #------------------ Member dashboard (PRAFULL)---------------##
    public function dashboard()
    {
        // Delete cookie if exist for duplicate I-card
        /*$valcookie= duplicateid_get_cookie();
        if($valcookie)
        {delete_cookie('did');}*/
        $data = array(
            'middle_content' => 'dashboard',
        );
        $this->load->view('common_view', $data);
    }

    // ##---------End user profile (prafull)-----------##
    public function profile()
    {
        $update_data = $kyc_update_data = $update_data = $update_data_image = array();
        $kycflag     = 0;
        $prevData    = array();
        $user_info   = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        #---images ---#
        $kyc_update_data = array();
        $kyc_edit_flag   = 0;
        $flag            = 1;
        $member_info     = $this->master_model->getRecords('member_registration', array(
            'regid' => $this->session->userdata('regid'),
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration');
        $applicationNo = $this->session->userdata('regnumber');

        #---images end ---#
        if (count($user_info)) {
            $prevData = $user_info[0];
        } else {
            base_url();
        }
        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $declaration_file = '';
        /* Benchmark Disability */
        $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';
        if (isset($_POST['btnSubmit'])) {
            
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|max_length[35]|required');
            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');
            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required');
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
            // $this->form_validation->set_rules('dob','Date of Birth','trim|required');
            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');
            $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required');
            // -----images-----#
            $image_info = array();
            $image_info = $this->master_model->getRecords('member_registration', array(
                'regid' => $this->session->userdata('regid'),
            ));
            //  print_r($image_info);exit;
            // get photo path
            $oldfilepath_photo = get_img_name($this->session->userdata('regnumber'), 'p');
            if ($oldfilepath_photo != '') {
                $p = '';
                $p = strpos($oldfilepath_photo, $this->session->userdata('regnumber'));
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
            $oldfilepath_s = get_img_name($this->session->userdata('regnumber'), 's');
            //echo $oldfilepath_s;exit;
            if ($oldfilepath_s != '') {

                $s = '';
                $s = strpos($oldfilepath_s, $this->session->userdata('regnumber'));
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
            $oldfilepath_pr = get_img_name($this->session->userdata('regnumber'), 'pr');

            if ($oldfilepath_pr != '') {
                $pr = '';
                $pr = strpos($oldfilepath_pr, $this->session->userdata('regnumber'));
                if ($pr == '') {
                    $oldfilepath_pr = '';

                }
                //        echo $oldfilepath_pr;exit;
                if ($oldfilepath_pr == '') {
                    if (!empty($image_info)) {
                        $oldfilepath_pr = 'uploads/' . $image_info[0]['image_path'] . 'idproof/pr_' . $image_info[0]['reg_no'] . '.jpg';
                        //echo $oldfilepath_photo;exit;
                        if (!file_exists($oldfilepath_pr)) {

                            $oldfilepath_pr = '';
                        }
                    }

                }
            }

            // get declaration path
            $oldfilepath_declaration = get_img_name($this->session->userdata('regnumber'), 'declaration');

            if ($oldfilepath_declaration != '') {
                $declaration = '';
                $declaration = strpos($oldfilepath_declaration, $this->session->userdata('regnumber'));
                if ($declaration == '') {
                    $oldfilepath_declaration = '';

                }
                //  echo $oldfilepath_pr;exit;
                if ($oldfilepath_declaration == '') {
                    if (!empty($image_info)) {
                        $oldfilepath_declaration = 'uploads/' . $image_info[0]['image_path'] . 'declaration/declaration_' . $image_info[0]['reg_no'] . '.jpg';
                        //echo $oldfilepath_photo;exit;
                        if (!file_exists($oldfilepath_declaration)) {
                            $oldfilepath_declaration = '';
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

            if (!file_exists($oldfilepath_declaration)) {
                if (isset($_FILES['declaration']['name'])) {
                    if ($_FILES['declaration']['name'] == '') {
                        $this->form_validation->set_rules('declaration', 'Declaration', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
                    }
                }
            }

            // -----image----#
            if ($_POST['optedu'] == 'U') {
                $this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required');
            } else
            if ($_POST['optedu'] == 'G') {
                $this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required');
            } else
            if ($_POST['optedu'] == 'P') {
                $this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required');
            }
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]');
            }
            $this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|numeric|required');
            $this->form_validation->set_rules('office', 'Branch/Office', 'trim|required');
            $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
            $this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required');
            // $this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required');
            // $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|numeric|is_unique[member_registration.aadhar_card.regid.'.$this->session->userdata('regid').']');
            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post("aadhar_card") != '') {
                    // $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $this->session->userdata('regid') . '.registrationtype.' . $this->session->userdata('memtype') . ']');
                }
            } else {
                if ($this->input->post("aadhar_card") != '') {
                    // $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');
                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $this->session->userdata('regid') . '.registrationtype.' . $this->session->userdata('memtype') . ']');
                }
            }
            // $this->form_validation->set_rules('idproof','Id Proof','trim|required|max_length[25]|xss_clean');
            $this->form_validation->set_rules('sel_namesub', 'Sub Name', 'trim|required');
            // $this->form_validation->set_rules('scannedphoto1_hidd','Uploaded Photo','trim|required');
            // $this->form_validation->set_rules('scannedsignaturephoto1_hidd','uploaded Signature','trim|required');
            // $this->form_validation->set_rules('idproofphoto1_hidd','Uploaded ID Proof','trim|required');
            // $this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validunique[member_registration.email.regid.'.$this->session->userdata('regid').'.isactive.1]|xss_clean');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_validuniqueO[member_registration.email.regid.' . $this->session->userdata('regid') . '.isactive.1.registrationtype.' . $this->session->userdata('memtype') . ']|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');
            // changes by tejasvi
            $this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'required|xss_clean');
            if ($this->form_validation->run() == true) {
                $addressline1 = strtoupper($this->input->post('addressline1'));
                $addressline2 = strtoupper($this->input->post('addressline2'));
                $addressline3 = strtoupper($this->input->post('addressline3'));
                $addressline4 = strtoupper($this->input->post('addressline4'));
                $district     = strtoupper($this->input->post('district'));
                $city         = strtoupper($this->input->post('city'));
                $state        = $this->input->post('state');
                $pincode      = $this->input->post('pincode');
                // $dob= $this->input->post('dob');
                $gender = $this->input->post('gender');
                $optedu = $this->input->post('optedu');
                if ($optedu == 'U') {
                    $specify_qualification = $this->input->post('eduqual1');
                } elseif ($optedu == 'G') {
                    $specify_qualification = $this->input->post('eduqual2');
                } else
                if ($optedu == 'P') {
                    $specify_qualification = $this->input->post('eduqual3');
                }
                $institutionworking = $this->input->post('institutionworking');
                $office             = strtoupper($this->input->post('office'));
                $designation        = $this->input->post('designation');
                $doj                = $this->input->post('doj1');
                $email              = $this->input->post('email');
                $stdcode            = $this->input->post('stdcode');
                $phone              = $this->input->post('phone');
                $mobile             = $this->input->post('mobile');
                $idproof            = $this->input->post('idproof');
                $optnletter         = $this->input->post('optnletter');
                $declaration        = $this->input->post("declaration");
                $aadhar_card        = $this->input->post("aadhar_card");
                // $idproof = $this->input->post("idproof");
                $sel_namesub = $this->input->post("sel_namesub");
                $firstname   = $this->input->post("firstname");
                $nameoncard  = $this->input->post("nameoncard");
                $dob         = $this->input->post("dob1");
                $middlename  = $this->input->post("middlename");
                $lastname    = $this->input->post("lastname");
                $bank_emp_id = $this->input->post("bank_emp_id");
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
                $prev_edited_on       = '';
                $prev_photo_flg       = "N";
                $prev_signature_flg   = "N";
                $prev_id_flg          = "N";
                $prev_declaration_flg = "N";
                $prev_edited_on_qry   = $this->master_model->getRecords('member_registration', array(
                    'regid' => $this->session->userdata('regid'),
                ), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg, declaration_flg');
                if (count($prev_edited_on_qry)) {
                    $prev_edited_on       = $prev_edited_on_qry[0]['images_editedon'];
                    $prev_photo_flg       = $prev_edited_on_qry[0]['photo_flg'];
                    $prev_signature_flg   = $prev_edited_on_qry[0]['signature_flg'];
                    $prev_id_flg          = $prev_edited_on_qry[0]['id_flg'];
                    $prev_declaration_flg = $prev_edited_on_qry[0]['declaration_flg'];
                    if ($prev_edited_on != date('Y-m-d')) {
                        $this->master_model->updateRecord('member_registration', array(
                            'photo_flg'       => 'N',
                            'signature_flg'   => 'N',
                            'id_flg'          => 'N',
                            'declaration_flg' => 'N',
                        ), array(
                            'regid' => $this->session->userdata('regid'),
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
                        @unlink('uploads/idproof/' . $member_info[0]['idproofphoto']);
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

                // Upload declaration Certificate
                $declaration_file = '';
                // Upload Education Certificate

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $declaration_flg = 'N';
                } else {
                    $declaration_flg = $prev_declaration_flg;
                }

                //echo $oldfilepath_declaration;exit;
                if (!file_exists($oldfilepath_declaration)) {
// echo '***';

                    if (isset($_FILES['declaration']['name']) && $_FILES['declaration']['name'] != '') {
                        @unlink('uploads/declaration/' . $member_info[0]['declaration']);
                        $path = "./uploads/declaration";

                        $new_filename = 'declaration_' . $applicationNo;
                        $uploadData   = upload_file('declaration', $path, $new_filename, '', '', true);
                        if ($uploadData) {
                            $kyc_edit_flag                             = 1;
                            $kyc_update_data['edited_mem_declaration'] = 1;
                            $declaration_file                          = $uploadData['file_name'];
                            $declaration_flg                           = 'Y';
                            $edited .= 'DECLARATION || ';

                        } else {
                            //$flag = 0;
                            $declaration_file = $this->input->post('declarationphoto_hidd');
                        }
                    } else {
                        $declaration_file = $this->input->post('declarationphoto_hidd');
                    }
                } else {
                    $declaration_file = $this->input->post('declarationphoto_hidd');
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
                    $update_data_image['declaration']           = $declaration_file;
                    $update_data_image['images_editedon']       = date('Y-m-d H:i:s');
                    $update_data_image['images_editedby']       = 'Candidate';
                    $update_data_image['photo_flg']             = $photo_flg;
                    $update_data_image['signature_flg']         = $signature_flg;
                    $update_data_image['id_flg']                = $id_flg;
                    $update_data_image['declaration_flg']       = $declaration_flg;
                    $update_data_image['kyc_edit']              = $kyc_edit_flag;
                    $update_data_image['kyc_status']            = '0';
                    if ($kyc_edit_flag == 1) {

                        if ($this->master_model->updateRecord('member_registration', $update_data_image, array(
                            'regid'     => $this->session->userdata('regid'),
                            'regnumber' => $this->session->userdata('regnumber'),
                        ))) {
                            $desc['updated_data'] = $update_data_image;
                            $desc['old_data']     = $member_info[0];
                            // logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));
                            /* User Log Activities : Bhushan */
                            $log_title   = "Member Edit Images";
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('regid');
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */
                            $finalStr = '';
                            if ($edited != '') {
                                $edit_data = trim($edited);
                                $finalStr  = rtrim($edit_data, "||");
                            }
                            log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('regid'), $this->session->userdata('regnumber'));
                            if ($kyc_edit_flag == 1) {

                                $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                    'regnumber' => $this->session->userdata('regnumber'),
                                ), '', array(
                                    'kyc_id' => 'DESC',
                                ), '0', '1');
                                if (count($kycmemdetails) > 0) {
                                    $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                    $kyc_update_data['kyc_state']        = '2';
                                    $kyc_update_data['kyc_status']       = '0';
                                    $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));
                                    $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));
                                    $this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
                                    $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
                                    // $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                    //     'list_type' => 'New'
                                    // ));
                                    if (count($check_duplicate_entry) > 0) {
                                        foreach ($check_duplicate_entry as $row) {
                                            $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));
                                            $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));
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
                                        $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('regnumber'), serialize($desc));
                                    }
                                    // check membership count
                                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    if (count($check_membership_cnt) > 0) {
                                        // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                        /* update dowanload count 8-8-2017 */
                                        $this->master_model->updateRecord('member_idcard_cnt', array(
                                            'card_cnt' => '0',
                                        ), array(
                                            'member_number' => $this->session->userdata('regnumber'),
                                        ));
                                        /* Close update dowanload count */
                                        /* User Log Activities : Pooja */
                                        $uerlog = $this->master_model->getRecords('member_registration', array(
                                            'regnumber' => $this->session->userdata('regnumber'),
                                        ), 'regid,regnumber');
                                        $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                            'member_number' => $this->session->userdata('regnumber'),
                                        ));
                                        $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                        $log_message = serialize($user_info);
                                        $rId         = $uerlog[0]['regid'];
                                        $regNo       = $this->session->userdata('regnumber');
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        /* Close User Log Actitives */
                                    }
                                }
                                // echo $this->db->last_query();exit;
                                // change by pooja godse for  memebersgip id card  dowanload count reset
                                // check membership count
                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                if (count($check_membership_cnt) > 0) {
                                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                    /* update dowanload count 8-8-2017 */
                                    $this->master_model->updateRecord('member_idcard_cnt', array(
                                        'card_cnt' => '0',
                                    ), array(
                                        'member_number' => $this->session->userdata('nmregnumber'),
                                    ));
                                    /* User Log Activities : Pooja */
                                    $uerlog = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $this->session->userdata('regnumber'),
                                    ), 'regid,regnumber');
                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                    $log_message = serialize($user_info);
                                    $rId         = $uerlog[0]['regid'];
                                    $regNo       = $this->session->userdata('regnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                    /* Close update dowanload count */
                                }
                                // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                                /* User Log Activities : Bhushan */
                                $log_title   = "kyc member edited images id : " . $this->session->userdata('regid');
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('regid');
                                $regNo       = $this->session->userdata('regnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */
                            }

                        }
                    }
                    // print_r($update_data);
                    // $personalInfo = filter($personal_info);
                    // ---end images ----#
                    // Check if value is edited
                    if (count($prevData)) {
                        if ($prevData['address1'] != $addressline1) {
                            $update_data['address1'] = $addressline1;
                        }
                        if ($prevData['address2'] != $addressline2) {
                            $update_data['address2'] = $addressline2;
                        }
                        if ($prevData['address3'] != $addressline3) {
                            $update_data['address3'] = $addressline3;
                        }
                        if ($prevData['address4'] != $addressline4) {
                            $update_data['address4'] = $addressline4;
                        }
                        if ($prevData['district'] != $district) {
                            $update_data['district'] = $district;
                        }
                        if ($prevData['city'] != $city) {
                            $update_data['city'] = $city;
                        }
                        if ($prevData['state'] != $state) {
                            $update_data['state'] = $state;
                        }
                        if ($prevData['pincode'] != $pincode) {
                            $update_data['pincode'] = $pincode;
                        }
                        if (date('Y-m-d', strtotime($prevData['dateofbirth'])) != date('Y-m-d', strtotime($dob)) && $dob !== '') {
                            $update_data['dateofbirth']        = date('Y-m-d', strtotime($dob));
                            $update_data['kyc_edit']           = '1';
                            $update_data['kyc_status']         = '0';
                            $kycflag                           = 1;
                            $kyc_update_data['edited_mem_dob'] = 1;
                        }
                        if ($prevData['gender'] != $gender) {
                            $update_data['gender'] = $gender;
                        }
                        if ($prevData['qualification'] != $optedu) {
                            $update_data['qualification'] = $optedu;
                        }
                        if ($prevData['specify_qualification'] != $specify_qualification) {
                            $update_data['specify_qualification'] = $specify_qualification;
                        }
                        if ($prevData['associatedinstitute'] != $institutionworking) {
                            $update_data['associatedinstitute']           = $institutionworking;
                            $update_data['kyc_edit']                      = '1';
                            $update_data['kyc_status']                    = '0';
                            $kycflag                                      = 1;
                            $kyc_update_data['edited_mem_associate_inst'] = 1;
                        }
                        if ($prevData['office'] != $office) {
                            $update_data['office'] = $office;
                        }
                        if ($prevData['designation'] != $designation) {
                            $update_data['designation'] = $designation;
                        }
                        // changes by tejasvi
                        if ($prevData['bank_emp_id'] != $bank_emp_id) {
                            $update_data['bank_emp_id'] = $bank_emp_id;
                        }
                        if (date('Y-m-d', strtotime($prevData['dateofjoin'])) != date('Y-m-d', strtotime($doj))) {
                            $update_data['dateofjoin'] = date('Y-m-d', strtotime($doj));
                        }
                        if ($prevData['email'] != $email) {
                            $update_data['email'] = $email;
                        }
                        if ($prevData['stdcode'] !== $stdcode) {
                            $update_data['stdcode'] = $stdcode;
                        }
                        if ($prevData['office_phone'] !== $phone) {
                            $update_data['office_phone'] = $phone;
                        }
                        if ($prevData['mobile'] !== $mobile) {
                            $update_data['mobile'] = $mobile;
                        }
                        if ($prevData['idproof'] != $idproof) {
                            $update_data['idproof'] = $idproof;
                        }
                        if ($prevData['aadhar_card'] !== $aadhar_card) {
                            $update_data['aadhar_card'] = $aadhar_card;
                        }
                        if ($prevData['optnletter'] != $optnletter) {
                            $update_data['optnletter'] = $optnletter;
                        }
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
                        if ($prevData['displayname'] != $nameoncard) {
                            $update_data['displayname'] = $nameoncard;
                        }
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
                    //echo $kyc_edit_flag ;exit;

                    if (empty($update_data) && $kyc_edit_flag == 0) {
//echo '**';exit;
                        $this->session->set_flashdata('error', 'Change atleast one field');
                        redirect(base_url('Home/profile/'));
                    }
                    $edited = array();
                    $edited = '';
                    if (count($update_data)) {
                        foreach ($update_data as $key => $val) {
                            $edited .= strtoupper($key) . " = " . strtoupper($val) . " && ";
                        }
                        // Set Image update flags to "N"
                        /*$prev_edited_on_qry = $this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid')),'DATE(editedon) editedon');
                        if(count($prev_edited_on_qry))
                        {
                        $prev_edited_on = $prev_edited_on_qry[0]['editedon'];
                        if($prev_edited_on != date('Y-m-d'))
                        {
                        $this->master_model->updateRecord('member_registration', array('photo_flg'=>'N', 'signature_flg'=>'N', 'id_flg'=>'N'), array('regid'=>$this->session->userdata('regid')));
                        }
                        }*/
                        $update_data['editedon'] = date('Y-m-d H:i:s');
                        $update_data['editedby'] = 'Candidate';
                        /* Benchmark Disability Code - Bhushan */
                        $applicationNo = $this->session->userdata('regnumber');

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

                        $check_benchmark = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'benchmark_disability');
                        if ($benchmark_disability != '') {
                            $update_data['benchmark_edit_flg']  = 'Y';
                            $update_data['benchmark_edit_date'] = date('Y-m-d H:i:s');
                        } elseif ($check_benchmark[0]['benchmark_disability'] != $benchmark_disability) {
                            $update_data['benchmark_edit_flg']  = 'Y';
                            $update_data['benchmark_edit_date'] = date('Y-m-d H:i:s');
                        }

                        /* Close Benchmark Disability Code - Bhushan */
                        // $update_data['editedbyadmin'] = $this->UserID;
                        // update member_kyc
                        //    echo '<pre>';
                        //    print_r($update_data);        exit;
                        // $personalInfo = filter($personal_info);
                        if ($this->master_model->updateRecord('member_registration', $update_data, array(
                            'regid'     => $this->session->userdata('regid'),
                            'regnumber' => $this->session->userdata('regnumber'),
                        ))) {
                            // -------image------#
                            $desc['updated_data'] = $update_data;
                            $desc['old_data']     = $member_info[0];
                            // logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));

                            $finalStr = '';
                            if ($edited != '') {
                                $edit_data = trim($edited);
                                $finalStr  = rtrim($edit_data, "||");
                            }
                            //  log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('regid') , $this->session->userdata('regnumber'));
                            if ($kyc_edit_flag == 1) {
                                $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                    'regnumber' => $this->session->userdata('regnumber'),
                                ), '', array(
                                    'kyc_id' => 'DESC',
                                ), '0', '1');
                                if (count($kycmemdetails) > 0) {
                                    $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                    $kyc_update_data['kyc_state']        = '2';
                                    $kyc_update_data['kyc_status']       = '0';
                                    $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));
                                    $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));
                                    $this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
                                    $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
                                    // $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                    //     'list_type' => 'New'
                                    // ));
                                    if (count($check_duplicate_entry) > 0) {
                                        foreach ($check_duplicate_entry as $row) {
                                            $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));
                                            $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));
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
                                        $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('regnumber'), serialize($desc));
                                    }
                                    // check membership count
                                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    if (count($check_membership_cnt) > 0) {
                                        // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                        /* update dowanload count 8-8-2017 */
                                        $this->master_model->updateRecord('member_idcard_cnt', array(
                                            'card_cnt' => '0',
                                        ), array(
                                            'member_number' => $this->session->userdata('regnumber'),
                                        ));
                                        /* Close update dowanload count */
                                        /* User Log Activities : Pooja */
                                        $uerlog = $this->master_model->getRecords('member_registration', array(
                                            'regnumber' => $this->session->userdata('regnumber'),
                                        ), 'regid,regnumber');
                                        $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                            'member_number' => $this->session->userdata('regnumber'),
                                        ));
                                        $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                        $log_message = serialize($user_info);
                                        $rId         = $uerlog[0]['regid'];
                                        $regNo       = $this->session->userdata('regnumber');
                                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        /* Close User Log Actitives */
                                    }
                                }
                                // echo $this->db->last_query();exit;
                                // change by pooja godse for  memebersgip id card  dowanload count reset
                                // check membership count
                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                if (count($check_membership_cnt) > 0) {
                                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                    /* update dowanload count 8-8-2017 */
                                    $this->master_model->updateRecord('member_idcard_cnt', array(
                                        'card_cnt' => '0',
                                    ), array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    /* User Log Activities : Pooja */
                                    $uerlog = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $this->session->userdata('regnumber'),
                                    ), 'regid,regnumber');
                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                    $log_message = serialize($user_info);
                                    $rId         = $uerlog[0]['regid'];
                                    $regNo       = $this->session->userdata('regnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                    /* Close update dowanload count */
                                }
                                // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                                /* User Log Activities : Bhushan */
                                $log_title   = "kyc member edited images id : " . $this->session->userdata('regid');
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('regid');
                                $regNo       = $this->session->userdata('regnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */
                            }
                            // if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))
                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'user_register_email',
                            ));
                            if (count($emailerstr) > 0) {
                                $member_info = $this->master_model->getRecords('member_registration', array(
                                    'regid' => $this->session->userdata('regid'),
                                ), 'email');
                                $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);
                                $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                                $info_arr  = array(
                                    'to'      => $member_info[0]['email'],
                                    'from'    => $emailerstr[0]['from'],
                                    'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('regnumber'),
                                    'message' => $final_str,
                                );
                                if ($this->Emailsending->mailsend($info_arr)) {
                                    // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                    redirect(base_url('home/acknowledge/'));
                                } else {
                                    $this->session->set_flashdata('error', 'Error while sending email !!');
                                    redirect(base_url('home/profile/'));
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                redirect(base_url('home/profile/'));
                            }
                            // ----------end image---------#
                            $desc['updated_data'] = $update_data;
                            $desc['old_data']     = $user_info[0];
                            // profile update logs
                            log_profile_user($log_title = "Profile updated successfully", $edited, 'data', $this->session->userdata('regid'), $this->session->userdata('regnumber'));
                            // logactivity($log_title = "Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));
                            /* User Log Activities : Bhushan */
                            $log_title   = "Profile updated successfully id:" . $this->session->userdata('regid');
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('regid');
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */
                            if ($kycflag == 1) {
                                $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                $kyc_update_data['kyc_state']        = '2';
                                $kyc_update_data['kyc_status']       = '0';
                                /*echo '<pre>';
                                print_r($kyc_update_data);
                                exit;*/
                                $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));
                                $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));
                                $this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
                                $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
                                // $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                //     'list_type' => 'New'
                                // ));
                                if (count($check_duplicate_entry) > 0) {
                                    foreach ($check_duplicate_entry as $row) {
                                        $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));
                                        $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));
                                        $admin_update_data           = array(
                                            'allotted_member_id'          => $allotted_member_id,
                                            'original_allotted_member_id' => $original_allotted_member_id,
                                        );
                                        $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array(
                                            'kyc_user_id' => $row['kyc_user_id'],
                                        ));
                                    }
                                }
                                $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                    'regnumber' => $this->session->userdata('regnumber'),
                                ), '', array(
                                    'kyc_id' => 'DESC',
                                ), '0', '1');
                                if (count($kycmemdetails) > 0) {
                                    if ($kycmemdetails[0]['kyc_status'] == '0') {
                                        $this->master_model->updateRecord('member_kyc', $kyc_update_data, array(
                                            'kyc_id' => $kycmemdetails[0]['kyc_id'],
                                        ));
                                        $this->KYC_Log_model->create_log('kyc member profile edit', '', '', $this->session->userdata('regnumber'), serialize($desc));
                                    }
                                }
                                // echo $this->db->last_query();exit;
                                // change by pooja godse for  memebersgip id card  dowanload count reset
                                // check membership count
                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                if (count($check_membership_cnt) > 0) {
                                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                    /* update dowanload count 8-8-2017 */
                                    $this->master_model->updateRecord('member_idcard_cnt', array(
                                        'card_cnt' => '0',
                                    ), array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    /* Close update dowanload count */
                                    /* User Log Activities : Pooja */
                                    $uerlog = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $this->session->userdata('regnumber'),
                                    ), 'regid,regnumber');
                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                    $log_message = serialize($user_info);
                                    $rId         = $uerlog[0]['regid'];
                                    $regNo       = $this->session->userdata('regnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                }
                                //    logactivity($log_title = "KYC Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));
                                /* User Log Activities : Bhushan */
                                $log_title   = "KYC Profile updated successfully id:" . $this->session->userdata('regid');
                                $log_message = serialize($desc);
                                $rId         = $this->session->userdata('regid');
                                $regNo       = $this->session->userdata('regnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */
                            }
                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'user_register_email',
                            ));
                            if (count($emailerstr) > 0) {
                                $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);
                                $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                                $info_arr  = array(
                                    'to'      => $email,
                                    'from'    => $emailerstr[0]['from'],
                                    'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('regnumber'),
                                    'message' => $final_str,
                                );
                                if ($this->Emailsending->mailsend($info_arr)) {
                                    // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                    redirect(base_url('home/acknowledge/'));
                                } else {
                                    $this->session->set_flashdata('error', 'Error while sending email !!');
                                    redirect(base_url('home/profile/'));
                                }
                            } else {
                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                redirect(base_url('home/profile/'));
                            }
                        } else {
                            $desc['updated_data'] = $update_info;
                            $desc['old_data']     = $member_info[0];
                            // logactivity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
                            /* User Log Activities : Bhushan */
                            $log_title   = "Error While Member Images Edit";
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('regid');
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */
                            $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                            $last = $this->uri->total_segments();
                            $post = $this->uri->segment($last);
                            redirect(base_url() . $post);
                            $desc['updated_data'] = $update_data;
                            $desc['old_data']     = $user_info[0];
                            // logactivity($log_title = "Profile update error id:".$this->session->userdata('regid'), $description = serialize($desc));
                            /* User Log Activities : Bhushan */
                            $log_title   = "Profile update error id:" . $this->session->userdata('regid');
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('regid');
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */
                            $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                            $last = $this->uri->total_segments();
                            $post = $this->uri->segment($last);
                            redirect(base_url() . $post);
                        }
                    } else {
                        $this->session->set_flashdata('error', 'Change atleast one field');
                        redirect(base_url('home/profile/'));
                    }
                } else {
                    // echo validation_errors();exit;
                    $data['validation_errors'] = validation_errors();
                    // echo "222222";vdebug($_POST);exit;
                }
            } else {
                $data['validation_errors'] = validation_errors();
            }
        }
        $undergraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'UG',
        ));
        $graduate = $this->master_model->getRecords('qualification', array(
            'type' => 'GR',
        ));
        $postgraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'PG',
        ));
        $instiarray = array(764, 793, 939, 946, 1238, 1444, 1449, 1456, 1458, 1459, 1460, 1464, 1465, 1469, 1470, 1471, 1472, 1473, 1474, 1476, 1487, 1490, 1491, 1497, 1499, 1506, 1507, 1511, 1522, 1525, 1526, 1527, 1528, 1530, 1531, 1533, 1538, 1539, 1540, 1541, 1549, 1559, 1567, 1570, 1571, 1573, 1574, 1575, 1576, 1581, 1584, 1587, 1589, 1591, 1592, 1593, 1594, 1598, 1602, 1603, 1607, 1608, 1609, 1612, 1615, 1616, 1617, 1620, 1625, 1626, 1627, 1628, 1629, 1630, 1635, 1643, 1644, 1646, 1648, 1652, 1654, 1655, 1656, 1657, 1658, 1660, 1661, 1663, 1669, 1678, 1679, 1680, 1687, 1688, 1690, 1691, 1692, 1699, 1707, 1708, 1709, 1714, 1720, 1721, 1723, 1724, 1725, 1727, 1730, 1731, 1740, 1742, 1743, 1755, 1758, 1760, 1767, 1769, 1773, 1774, 1775, 1776, 1777, 1780, 1781, 1782, 1783, 1785, 1786, 1788, 1790, 1795, 1802, 1803, 1804, 1806, 1813, 1814, 1815, 1817, 1818, 1820, 1824, 1825, 1828, 1844, 1845, 1846, 1848, 1850, 1851, 1852, 1853, 1855, 1861, 1862, 1863, 1864, 1868, 1869, 1870, 1876, 1884, 1885, 1886, 1890, 1894, 1896, 1897, 1898, 1905, 1908, 1909, 1910, 1911, 1912, 1913, 1914, 1915, 1921, 1925, 1926, 1930, 1931, 1936, 1947, 1951, 1952, 1961, 1971, 1976, 1982, 1986, 1988, 1991, 1992, 1994, 1995, 1996, 1997, 2000, 2002, 2012, 2025, 2028, 2029, 2034, 2041, 2043, 2044, 2046, 2050, 2052, 2053, 2054, 2056, 2058, 2059, 2060, 2061, 2062, 2063, 2064, 2065, 2066, 2067, 2068, 2069, 2070, 2071, 2072, 2073, 2076, 2077, 2078, 2079, 2080, 2081, 2082, 2083, 2084, 2086, 2087, 2090, 2093, 2096, 2097, 2098, 2100, 2101, 2102, 2105, 2106, 2107, 2108, 2109, 2110, 2111, 2112, 2113, 2115, 2116, 2117, 2118, 2119, 2120, 2122, 2123, 2125, 2126, 2129, 2130, 2131, 2132, 2133, 2134, 2135, 2136, 2137, 2138, 2139, 2140, 2141, 2145, 2146, 2147, 2152, 2153, 2154, 2155, 2156, 2180, 2183, 2184, 2185, 2186, 2187, 2683, 2698);
        $this->db->where_not_in('institude_id', $instiarray);
        $this->db->where('institution_master.institution_delete', 0);
        $institution_master = $this->master_model->getRecords('institution_master');
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('designation_master.designation_delete', '0');
        $designation = $this->master_model->getRecords('designation_master');
        /*$this->db->like('name','Employer\'s card');
        $this->db->or_like('name','Declaration Form');*/
        $this->db->where('id', 4);
        $this->db->or_where('id', 8);
        $idtype_master = $this->master_model->getRecords('idtype_master');
        $data          = array(
            'middle_content'     => 'userprofile',
            'states'             => $states,
            'undergraduate'      => $undergraduate,
            'graduate'           => $graduate,
            'postgraduate'       => $postgraduate,
            'institution_master' => $institution_master,
            'designation'        => $designation,
            'user_info'          => $user_info,
            'idtype_master'      => $idtype_master,
        );
        $this->load->view('common_view', $data);
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

    // ##---------Thank you page for user (prafull)-----------##
    public function acknowledge()
    {
        $kycflag = 0;
        // $this->chk_session->checkphoto();
        $data = array(
            'middle_content'     => 'profile_thankyou',
            'application_number' => $this->session->userdata('regnumber'),
            'password'           => base64_decode($this->session->userdata('password')),
        );
        $this->load->view('common_view', $data);
    }

    // ##---------Edit Images(Prafull)-----------##
    //RENAMED BY SAGAR M ON 2024-10-23, AS WE NEED TO REPLACE BROWSE IMAGE FUNCTIONALITY WITH CROPPER IMAGE (WEBCAM + CROPPER JS) FUNCTIONALITY
    public function editimages_bk_without_cropper()
    {
        $kyc_update_data = array();
        $kyc_edit_flag   = 0;
        $flag            = 1;
        $member_info     = $this->master_model->getRecords('member_registration', array(
            'regid' => $this->session->userdata('regid'),
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration');
        $applicationNo = $this->session->userdata('regnumber');
        if (isset($_POST['btnSubmit'])) {
            if ($_FILES['scannedphoto']['name'] == '' && $_FILES['scannedsignaturephoto']['name'] == '' && $_FILES['idproofphoto']['name'] == '' && $_FILES['declaration']['name'] == '') {
                $this->form_validation->set_rules('scannedphoto', 'Please Change atleast One Value', 'file_required');
            }
            if ($_FILES['scannedphoto']['name'] != '') {
                $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]');
            }
            if ($_FILES['scannedsignaturephoto']['name'] != '') {
                $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]');
            }
            if ($_FILES['idproofphoto']['name'] != '') {
                $this->form_validation->set_rules('idproofphoto', 'id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
            }
            if ($_FILES['declaration']['name'] != '') {
                $this->form_validation->set_rules('declaration', 'declaration', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]');
            }
            if ($this->form_validation->run() == true) {
                $prev_edited_on       = '';
                $prev_photo_flg       = "N";
                $prev_signature_flg   = "N";
                $prev_id_flg          = "N";
                $prev_declaration_flg = "N";
                $prev_edited_on_qry   = $this->master_model->getRecords('member_registration', array(
                    'regid' => $this->session->userdata('regid'),
                ), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg,declaration_flg');
                if (count($prev_edited_on_qry)) {
                    $prev_edited_on       = $prev_edited_on_qry[0]['images_editedon'];
                    $prev_photo_flg       = $prev_edited_on_qry[0]['photo_flg'];
                    $prev_signature_flg   = $prev_edited_on_qry[0]['signature_flg'];
                    $prev_id_flg          = $prev_edited_on_qry[0]['id_flg'];
                    $prev_declaration_flg = $prev_edited_on_qry[0]['declaration_flg'];
                    if ($prev_edited_on != date('Y-m-d')) {
                        $this->master_model->updateRecord('member_registration', array(
                            'photo_flg'       => 'N',
                            'signature_flg'   => 'N',
                            'id_flg'          => 'N',
                            'declaration_flg' => 'N',
                        ), array(
                            'regid' => $this->session->userdata('regid'),
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
                // Upload DOB Proof
                $scannedsignaturephoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $signature_flg = 'N';
                } else {
                    $signature_flg = $prev_signature_flg;
                }
                if ($_FILES['scannedsignaturephoto']['name'] != '') {
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
                // Upload Education Certificate
                $idproofphoto_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $id_flg = 'N';
                } else {
                    $id_flg = $prev_id_flg;
                }
                if ($_FILES['idproofphoto']['name'] != '') {
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

                // Upload declaration Certificate
                $declaration_file = '';
                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {
                    $declaration_flg = 'N';
                } else {
                    $declaration_flg = $prev_declaration_flg;
                }
                if ($_FILES['declaration']['name'] != '') {
                    @unlink('uploads/declaration/' . $member_info[0]['declaration']);
                    $path         = "./uploads/declaration";
                    $new_filename = 'declaration_' . $applicationNo;
                    $uploadData   = upload_file('declaration', $path, $new_filename, '', '', true);
                    if ($uploadData) {
                        $kyc_edit_flag                             = 1;
                        $kyc_update_data['edited_mem_declaration'] = 1;
                        $declaration_file                          = $uploadData['file_name'];
                        $declaration_flg                           = 'Y';
                        $edited .= 'DECLARATION || ';
                    } else {
                        $flag             = 0;
                        $declaration_file = $this->input->post('declaration_hidd');
                    }
                } else {
                    $declaration_file = $this->input->post('declaration_hidd');
                }

                // print_r($declaration_file); exit;//declaration_500114717.JPEG
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
                    $update_info = array(
                        'scannedphoto'          => $scannedphoto_file,
                        'scannedsignaturephoto' => $scannedsignaturephoto_file,
                        'idproofphoto'          => $idproofphoto_file,
                        'declaration'           => $declaration_file,
                        'editedon'              => date('Y-m-d H:i:s'),//Update editedon Added by Pooja (22-02-24)
                        'images_editedon'       => date('Y-m-d H:i:s'),
                        'images_editedby'       => 'Candidate',
                        'photo_flg'             => $photo_flg,
                        'signature_flg'         => $signature_flg,
                        'id_flg'                => $id_flg,
                        'declaration_flg'       => $declaration_flg,
                        'kyc_edit'              => $kyc_edit_flag,
                        'kyc_status'            => '0',
                    );
                    // $personalInfo = filter($personal_info);
                    if ($this->master_model->updateRecord('member_registration', $update_info, array(
                        'regid'     => $this->session->userdata('regid'),
                        'regnumber' => $this->session->userdata('regnumber'),
                    ))) {
                        $desc['updated_data'] = $update_info;
                        $desc['old_data']     = $member_info[0];
                        // logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));
                        /* User Log Activities : Bhushan */
                        $log_title   = "Member Edit Images";
                        $log_message = serialize($desc);
                        $rId         = $this->session->userdata('regid');
                        $regNo       = $this->session->userdata('regnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        /* Close User Log Actitives */
                        $finalStr = '';
                        if ($edited != '') {
                            $edit_data = trim($edited);
                            $finalStr  = rtrim($edit_data, "||");
                        }
                        log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('regid'), $this->session->userdata('regnumber'));
                        if ($kyc_edit_flag == 1) {
                            $kycmemdetails = $this->master_model->getRecords('member_kyc', array(
                                'regnumber' => $this->session->userdata('regnumber'),
                            ), '', array(
                                'kyc_id' => 'DESC',
                            ), '0', '1');
                            if (count($kycmemdetails) > 0) {
                                $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                                $kyc_update_data['kyc_state']        = '2';
                                $kyc_update_data['kyc_status']       = '0';
                                $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));
                                $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));
                                $this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
                                $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
                                // $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                                //     'list_type' => 'New'
                                // ));
                                if (count($check_duplicate_entry) > 0) {
                                    foreach ($check_duplicate_entry as $row) {
                                        $allotted_member_id          = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));
                                        $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));
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
                                    $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('regnumber'), serialize($desc));
                                }
                                // check membership count
                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                if (count($check_membership_cnt) > 0) {
                                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                    /* update dowanload count 8-8-2017 */
                                    $this->master_model->updateRecord('member_idcard_cnt', array(
                                        'card_cnt' => '0',
                                    ), array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    /* Close update dowanload count */
                                    /* User Log Activities : Pooja */
                                    $uerlog = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $this->session->userdata('regnumber'),
                                    ), 'regid,regnumber');
                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                        'member_number' => $this->session->userdata('regnumber'),
                                    ));
                                    $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                    $log_message = serialize($user_info);
                                    $rId         = $uerlog[0]['regid'];
                                    $regNo       = $this->session->userdata('regnumber');
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    /* Close User Log Actitives */
                                }
                            }
                            // echo $this->db->last_query();exit;
                            // change by pooja godse for  memebersgip id card  dowanload count reset
                            // check membership count
                            $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array(
                                'member_number' => $this->session->userdata('regnumber'),
                            ));
                            if (count($check_membership_cnt) > 0) {
                                // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                                /* update dowanload count 8-8-2017 */
                                $this->master_model->updateRecord('member_idcard_cnt', array(
                                    'card_cnt' => '0',
                                ), array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                /* User Log Activities : Pooja */
                                $uerlog = $this->master_model->getRecords('member_registration', array(
                                    'regnumber' => $this->session->userdata('regnumber'),
                                ), 'regid,regnumber');
                                $user_info = $this->master_model->getRecords('member_idcard_cnt', array(
                                    'member_number' => $this->session->userdata('regnumber'),
                                ));
                                $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                                $log_message = serialize($user_info);
                                $rId         = $uerlog[0]['regid'];
                                $regNo       = $this->session->userdata('regnumber');
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                                /* Close User Log Actitives */
                                /* Close update dowanload count */
                            }
                            // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                            /* User Log Activities : Bhushan */
                            $log_title   = "kyc member edited images id : " . $this->session->userdata('regid');
                            $log_message = serialize($desc);
                            $rId         = $this->session->userdata('regid');
                            $regNo       = $this->session->userdata('regnumber');
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /* Close User Log Actitives */
                        }
                        // if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))
                        if (validate_ordinary_userdata($this->session->userdata('regnumber'))) //!is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) ||
                        {
                            $this->session->set_flashdata('error', 'Please update your profile!!');
                            redirect(base_url('home/profile/'));
                        }
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'user_register_email',
                        ));
                        if (count($emailerstr) > 0) {
                            $member_info = $this->master_model->getRecords('member_registration', array(
                                'regid' => $this->session->userdata('regid'),
                            ), 'email');
                            $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);
                            $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                            $info_arr  = array(
                                'to'      => $member_info[0]['email'],
                                'from'    => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('regnumber'),
                                'message' => $final_str,
                            );
                            if ($this->Emailsending->mailsend($info_arr)) {
                                // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                                redirect(base_url('home/acknowledge/'));
                            } else {
                                $this->session->set_flashdata('error', 'Error while sending email !!');
                                redirect(base_url('home/editimages/'));
                            }
                        } else {
                            $this->session->set_flashdata('error', 'Error while sending email !!');
                            redirect(base_url('home/editimages/'));
                        }
                    } else {
                        $desc['updated_data'] = $update_info;
                        $desc['old_data']     = $member_info[0];
                        // logactivity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
                        /* User Log Activities : Bhushan */
                        $log_title   = "Error While Member Images Edit";
                        $log_message = serialize($desc);
                        $rId         = $this->session->userdata('regid');
                        $regNo       = $this->session->userdata('regnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        /* Close User Log Actitives */
                        $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                        $last = $this->uri->total_segments();
                        $post = $this->uri->segment($last);
                        redirect(base_url() . $post);
                    }
                } else {
                    $this->session->set_flashdata('error', 'Please follow the instruction while uploading image(s)!!');
                    redirect(base_url('home/editimages/'));
                }
            } else {
                $data['validation_errors'] = validation_errors();
            }
        }
        $data = array(
            'middle_content' => 'member_edit_images',
            'member_info'    => $member_info,
        );
        $this->load->view('common_view', $data);
    }

    // ADDED BY SAGAR M ON 2024-10-23, AS WE NEED TO REPLACE BROWSE IMAGE FUNCTIONALITY WITH CROPPER IMAGE (WEBCAM + CROPPER JS) FUNCTIONALITY
    public function editimages()
    {
      $this->load->helper('iibfbcbf/iibf_bcbf_helper');
      $scannedphoto_path = 'uploads/photograph';
      $scannedsignaturephoto_path = 'uploads/scansignature';
      $idproofphoto_path = 'uploads/idproof';
      $declaration_path = 'uploads/declaration';

      $kyc_update_data = array();
      $kyc_edit_flag = 0;
      $flag = 1;
      $member_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration, namesub, firstname, middlename, lastname');

      $applicationNo = $this->session->userdata('regnumber');
      $error_flag = 0;
      $custom_error = '';
      if (isset($_POST) && count($_POST) > 0)
      {
        $scannedphoto_req_flg = $scannedsignaturephoto_req_flg = $idproofphoto_req_flg = $declaration_req_flg = 'required|';

        if (isset($_POST['scannedphoto_cropper']) && $_POST['scannedphoto_cropper'] != "") { $scannedphoto_req_flg = ''; }
        else if ($member_info[0]['scannedphoto'] != "") { $scannedphoto_req_flg = ''; }

        if (isset($_POST['scannedsignaturephoto_cropper']) && $_POST['scannedsignaturephoto_cropper'] != "") { $scannedsignaturephoto_req_flg = ''; }
        else if ($member_info[0]['scannedsignaturephoto'] != "") { $scannedsignaturephoto_req_flg = ''; }
        
        if (isset($_POST['idproofphoto_cropper']) && $_POST['idproofphoto_cropper'] != "") { $idproofphoto_req_flg = ''; }
        else if ($member_info[0]['idproofphoto'] != "") { $idproofphoto_req_flg = ''; }
        
        if (isset($_POST['declaration_cropper']) && $_POST['declaration_cropper'] != "") { $declaration_req_flg = ''; }
        else if ($member_info[0]['declaration'] != "") { $declaration_req_flg = 'y'; }

        $this->form_validation->set_rules('form_value', 'form_value', 'trim|xss_clean');
        $this->form_validation->set_rules('scannedphoto', 'Scanned Photograph', 'trim|'.$scannedphoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature', 'trim|'.$scannedsignaturephoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('idproofphoto', 'Id Proof', 'trim|'.$idproofphoto_req_flg.'xss_clean');
        $this->form_validation->set_rules('declaration', 'Declaration', 'trim|'.$declaration_req_flg.'xss_clean');
                
        //$this->form_validation->set_rules('xxx', 'xxx', 'required');
        if ($this->form_validation->run() == true)
        {
          //$custom_error = 'Please Change atleast One Value';
          /* echo '<pre>'; 
          print_r($_POST);
          echo '</pre>';  */

          $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);
          
          $prev_edited_on = '';
          $prev_photo_flg = $prev_signature_flg = $prev_id_flg = $prev_declaration_flg = "N";
          $prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'DATE(images_editedon) AS images_editedon, photo_flg,signature_flg,id_flg,declaration_flg');

          if (count($prev_edited_on_qry))
          {
            $prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];
            $prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];
            $prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];
            $prev_id_flg = $prev_edited_on_qry[0]['id_flg'];
            $prev_declaration_flg = $prev_edited_on_qry[0]['declaration_flg'];
            
            if ($prev_edited_on != date('Y-m-d'))
            {
              $this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N', 'declaration_flg' => 'N'), array('regid' => $this->session->userdata('regid')));
            }
          }

          $date = date('Y-m-d h:i:s');
          $edited = '';
          
          //PHOTO
          $scannedphoto_file = '';
          if($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) { $photo_flg = 'N'; }
          else { $photo_flg = $prev_photo_flg; }
          if (isset($_POST['scannedphoto_cropper']) && $_POST['scannedphoto_cropper'] != "")
          {
            $scannedphoto_cropper = $this->security->xss_clean($this->input->post('scannedphoto_cropper'));
            $new_file_name1 = "p_" . $file_name_str . '.' . strtolower(pathinfo($scannedphoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scannedphoto_cropper), $scannedphoto_path . '/' . $new_file_name1))
            {
              $kyc_edit_flag = 1;
              $kyc_update_data['edited_mem_photo'] = 1;
              $scannedphoto_file = $new_file_name1;
              $photo_flg = 'Y';
              $edited .= 'PHOTO || ';
            }
            else { $flag = 0; }
          }
          
          //SIGNATURE
          $scannedsignaturephoto_file = '';
          if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) { $signature_flg = 'N'; }
          else { $signature_flg = $prev_signature_flg; }
          if (isset($_POST['scannedsignaturephoto_cropper']) && $_POST['scannedsignaturephoto_cropper'] != "")
          {
            $scannedsignaturephoto_cropper = $this->security->xss_clean($this->input->post('scannedsignaturephoto_cropper'));
            $new_file_name2 = "s_" . $file_name_str . '.' . strtolower(pathinfo($scannedsignaturephoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $scannedsignaturephoto_cropper), $scannedsignaturephoto_path . '/' . $new_file_name2))
            {
              $kyc_edit_flag = 1;
              $kyc_update_data['edited_mem_sign'] = 1;
              $scannedsignaturephoto_file = $new_file_name2;
              $signature_flg = 'Y';
              $edited .= 'SIGNATURE || ';
            }
            else { $flag = 0; }
          }
          
          //ID PROOF
          $idproofphoto_file = '';
          if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) { $id_flg = 'N'; }
          else { $id_flg = $prev_id_flg; }
          if (isset($_POST['idproofphoto_cropper']) && $_POST['idproofphoto_cropper'] != "")
          {
            $idproofphoto_cropper = $this->security->xss_clean($this->input->post('idproofphoto_cropper'));
            $new_file_name3 = "pr_" . $file_name_str . '.' . strtolower(pathinfo($idproofphoto_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $idproofphoto_cropper), $idproofphoto_path . '/' . $new_file_name3))
            {
              $kyc_edit_flag = 1;
              $kyc_update_data['edited_mem_proof'] = 1;
              $idproofphoto_file = $new_file_name3;
              $id_flg = 'Y';
              $edited .= 'PROOF || ';
            }
            else { $flag = 0; }
          }

          //DECLARATION
          $declaration_file = '';
          if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) { $declaration_flg = 'N'; }
          else { $declaration_flg = $prev_declaration_flg; }          
          if (isset($_POST['declaration_cropper']) && $_POST['declaration_cropper'] != "")
          {
            $declaration_cropper = $this->security->xss_clean($this->input->post('declaration_cropper'));
            $new_file_name4 = "declaration_" . $file_name_str . '.' . strtolower(pathinfo($declaration_cropper, PATHINFO_EXTENSION));
            if (copy(str_replace(base_url(), '', $declaration_cropper), $declaration_path . '/' . $new_file_name4))
            {
              $kyc_edit_flag = 1;
              $kyc_update_data['edited_mem_declaration'] = 1;
              $declaration_file = $new_file_name4;
              $declaration_flg = 'Y';
              $edited .= 'DECLARATION || ';
            }
            else { $flag = 0; }
          }

          //echo '<br>flag : '.$flag; exit;
          // print_r($declaration_file); exit;//declaration_500114717.JPEG
          if ($flag == 1)
          {
            $update_info = array();
            if($scannedphoto_file != '') 
            { 
              $update_info['scannedphoto'] = $scannedphoto_file; 
              $update_info['photo_flg'] = $photo_flg; 
            }

            if($scannedsignaturephoto_file != '') 
            { 
              $update_info['scannedsignaturephoto'] = $scannedsignaturephoto_file; 
              $update_info['signature_flg'] = $signature_flg; 
            }

            if($idproofphoto_file != '') 
            { 
              $update_info['idproofphoto'] = $idproofphoto_file; 
              $update_info['id_flg'] = $id_flg; 
            }

            if($declaration_file != '') 
            { 
              $update_info['declaration'] = $declaration_file; 
              $update_info['declaration_flg'] = $declaration_flg; 
            }

            if(count($update_info) > 0)
            {
              $update_info['images_editedon'] = date('Y-m-d H:i:s');
              $update_info['images_editedby'] = 'Candidate';
              $update_info['kyc_edit'] = $kyc_edit_flag;
              $update_info['kyc_status'] = '0';

              //echo '<pre>'; print_r($update_info); echo '</pre>'; exit;              
              // $personalInfo = filter($personal_info);
              if ($this->master_model->updateRecord('member_registration', $update_info, array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'))))
              {
                $desc['updated_data'] = $update_info;
                $desc['old_data'] = $member_info[0];
                // logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));
                /* User Log Activities : Bhushan */
                $log_title   = "Member Edit Images";
                $log_message = serialize($desc);
                $rId         = $this->session->userdata('regid');
                $regNo       = $this->session->userdata('regnumber');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */

                //START : UPDATE THE TEMP IMAGE WITH ACTUAL REGNUMBER 
                $up_img_data = array();
                if($scannedphoto_file != '')
                {
                  $scannedphoto_file_new = 'p_' . $this->session->userdata('regnumber') . '.' . pathinfo($scannedphoto_file, PATHINFO_EXTENSION);

                  if ($scannedphoto_file != $scannedphoto_file_new)
                  {
                    if (rename($scannedphoto_path . "/" . $scannedphoto_file, $scannedphoto_path . "/" . $scannedphoto_file_new))
                    {
                      $up_img_data['scannedphoto'] = $scannedphoto_file_new;
                    }
                  }
                }

                if($scannedsignaturephoto_file != '')
                {
                  $scannedsignaturephoto_file_new = 's_' . $this->session->userdata('regnumber') . '.' . pathinfo($scannedsignaturephoto_file, PATHINFO_EXTENSION);

                  if ($scannedsignaturephoto_file != $scannedsignaturephoto_file_new)
                  {
                    if (rename($scannedsignaturephoto_path . "/" . $scannedsignaturephoto_file, $scannedsignaturephoto_path . "/" . $scannedsignaturephoto_file_new))
                    {
                      $up_img_data['scannedsignaturephoto'] = $scannedsignaturephoto_file_new;
                    }
                  }
                }

                if($idproofphoto_file != '')
                {
                  $idproofphoto_file_new = 'pr_' . $this->session->userdata('regnumber') . '.' . pathinfo($idproofphoto_file, PATHINFO_EXTENSION);

                  if ($idproofphoto_file != $idproofphoto_file_new)
                  {
                    if (rename($idproofphoto_path . "/" . $idproofphoto_file, $idproofphoto_path . "/" . $idproofphoto_file_new))
                    {
                      $up_img_data['idproofphoto'] = $idproofphoto_file_new;
                    }
                  }
                }
                
                if($declaration_file != '')
                {
                  $declaration_file_new = 'declaration_' . $this->session->userdata('regnumber') . '.' . pathinfo($declaration_file, PATHINFO_EXTENSION);

                  if ($declaration_file != $declaration_file_new)
                  {
                    if (rename($declaration_path . "/" . $declaration_file, $declaration_path . "/" . $declaration_file_new))
                    {
                      $up_img_data['declaration'] = $declaration_file_new;
                    }
                  }
                }

                if (count($up_img_data) > 0)
                {
                  $this->master_model->updateRecord('member_registration', $up_img_data, array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')));
                }
                //END : UPDATE THE TEMP IMAGE WITH ACTUAL REGNUMBER 

                $finalStr = '';
                if ($edited != '')
                {
                  $edit_data = trim($edited);
                  $finalStr = rtrim($edit_data, "||");
                }
                log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('regid'), $this->session->userdata('regnumber'));
                
                if ($kyc_edit_flag == 1)
                {
                  $kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('regnumber')), '', array('kyc_id' => 'DESC'), '0', '1');
                  if (count($kycmemdetails) > 0)
                  {
                    $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');
                    $kyc_update_data['kyc_state'] = '2';
                    $kyc_update_data['kyc_status'] = '0';
                    $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));
                    $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));
                    $this->db->where_in('list_type', 'New,Edit'); // by sagar walzade : condition added for both new and edit
                    $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users'); // by sagar walzade : line updated and below is older line in comment
                    // $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array(
                    //     'list_type' => 'New'
                    // ));
                    if (count($check_duplicate_entry) > 0)
                    {
                      foreach ($check_duplicate_entry as $row)
                      {
                        $allotted_member_id = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));
                        $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));
                        $admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);
                        $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));
                      }
                    }

                    // $kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');
                    if ($kycmemdetails[0]['kyc_status'] == '0')
                    {
                      $this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));
                      $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('regnumber'), serialize($desc));
                    }

                    // check membership count
                    $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                    if (count($check_membership_cnt) > 0)
                    {
                      // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                      /* update dowanload count 8-8-2017 */
                      $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('regnumber')));
                      /* Close update dowanload count */
                      /* User Log Activities : Pooja */
                      $uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'regid,regnumber');
                      $user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                      $log_title   = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                      $log_message = serialize($user_info);
                      $rId = $uerlog[0]['regid'];
                      $regNo = $this->session->userdata('regnumber');
                      storedUserActivity($log_title, $log_message, $rId, $regNo);
                      /* Close User Log Actitives */
                    }
                  }

                  // echo $this->db->last_query();exit;
                  // change by pooja godse for memebership id card  dowanload count reset
                  // check membership count
                  $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                  if (count($check_membership_cnt) > 0)
                  {
                    // $this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));
                    /* update dowanload count 8-8-2017 */
                    $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('regnumber')));
                    /* User Log Activities : Pooja */
                    $uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'regid,regnumber');
                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));
                    $log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];
                    $log_message = serialize($user_info);
                    $rId = $uerlog[0]['regid'];
                    $regNo = $this->session->userdata('regnumber');
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    /* Close User Log Actitives */
                    /* Close update dowanload count */
                  }

                  // logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));
                  /* User Log Activities : Bhushan */
                  $log_title   = "kyc member edited images id : " . $this->session->userdata('regid');
                  $log_message = serialize($desc);
                  $rId         = $this->session->userdata('regid');
                  $regNo       = $this->session->userdata('regnumber');
                  storedUserActivity($log_title, $log_message, $rId, $regNo);
                  /* Close User Log Actitives */
                }
                
                // if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))
                if (validate_ordinary_userdata($this->session->userdata('regnumber'))) //!is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) ||
                {
                  $this->session->set_flashdata('error', 'Please update your profile!!');
                  redirect(base_url('home/profile/'));
                }

                $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));
                if (count($emailerstr) > 0)
                {
                  $member_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'email');
                  $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);
                  $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);
                  $info_arr  = array(
                    'to'      => $member_info[0]['email'],
                    'from'    => $emailerstr[0]['from'],
                    'subject' => $emailerstr[0]['subject'] . ' ' . $this->session->userdata('regnumber'),
                    'message' => $final_str,
                  );

                  if ($this->Emailsending->mailsend($info_arr))
                  {
                    // $this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');
                    redirect(base_url('home/acknowledge/'));
                  }
                  else
                  {
                    $this->session->set_flashdata('error', 'Error while sending email !!');
                    redirect(base_url('home/editimages/'));
                  }
                }
                else
                {
                  $this->session->set_flashdata('error', 'Error while sending email !!');
                  redirect(base_url('home/editimages/'));
                }
              }
              else
              {
                $desc['updated_data'] = $update_info;
                $desc['old_data']     = $member_info[0];
                // logactivity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));
                /* User Log Activities : Bhushan */
                $log_title   = "Error While Member Images Edit";
                $log_message = serialize($desc);
                $rId         = $this->session->userdata('regid');
                $regNo       = $this->session->userdata('regnumber');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */
                $this->session->set_flashdata('error', 'Error While Adding Your Information !!');
                $last = $this->uri->total_segments();
                $post = $this->uri->segment($last);
                redirect(base_url() . $post);
              }
            }
            else
            {
              $this->session->set_flashdata('error', 'Error occurred While updating the images !!');
              redirect(base_url('home/editimages/'));
            }            
          }
          else
          {
            $this->session->set_flashdata('error', 'Please follow the instruction while uploading image(s)!!');
            redirect(base_url('home/editimages/'));
          }
        }        
      }

      $data = array(
        'middle_content' => 'member_edit_images',
        'member_info'    => $member_info,
        'scannedphoto_path'    => $scannedphoto_path,
        'scannedsignaturephoto_path' => $scannedsignaturephoto_path,
        'idproofphoto_path' => $idproofphoto_path,
        'declaration_path' => $declaration_path,
        'custom_error' => $custom_error,
      );
      $this->load->view('common_view', $data);
    }

    // ##---------check mail alredy exist or not on edit page(Prafull)-----------##
    public function editemailduplication()
    {
        $email = $_POST['email'];
        $regid = $_POST['regid'];
        if ($email != "" && $regid != "") {
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'email'            => $email,
                'regid !='         => $regid,
                'isactive'         => '1',
                'registrationtype' => $this->session->userdata('memtype'),
            ));
            if ($prev_count == 0) {
                echo 'ok';
            } else {
                echo 'exists';
            }
        } else {
            echo 'error';
        }
    }

    // ##---------check mobile number alredy exist or not on edit page(Prafull)-----------##
    public function editmobile()
    {
        $mobile = $_POST['mobile'];
        $regid  = $_POST['regid'];
        if ($mobile != "" && $regid != "") {
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'mobile'           => $mobile,
                'regid !='         => $regid,
                'isactive'         => '1',
                'registrationtype' => $this->session->userdata('memtype'),
            ));
            if ($prev_count == 0) {
                echo 'ok';
            } else {
                echo 'exists';
            }
        } else {
            echo 'error';
        }
    }

    // #------------------ chenge password (PRAFULL)---------------##
    public function changepass()
    {
        // $this->chk_session->checkphoto();
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
                $row            = $this->master_model->getRecordCount('member_registration', array(
                    'usrpassword' => $curr_encrypass,
                    'regid'       => $this->session->userdata('regid'),
                ));
                if ($row == 0) {
                    $this->session->set_flashdata('error', 'Current Password is Wrong.');
                    redirect(base_url() . 'home/changepass/');
                } else {
                    if ($current_pass != $new_pass) {
                        $input_array = array(
                            'usrpassword' => $encPass,
                        );
                        $this->master_model->updateRecord('member_registration', $input_array, array(
                            'regid'     => $this->session->userdata('regid'),
                            'regnumber' => $this->session->userdata('regnumber'),
                        ));
                        // logactivity($log_title ="Changed password Member ", $log_message = serialize($input_array));
                        /* User Log Activities : Bhushan */
                        $log_title   = "Changed password Member";
                        $log_message = serialize($input_array);
                        $rId         = $this->session->userdata('regid');
                        $regNo       = $this->session->userdata('regnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        /* Close User Log Actitives */
                        $this->session->unset_userdata('password');
                        $this->session->set_userdata("password", base64_encode($new_pass));
                        $this->session->set_flashdata('success', 'Password Changed successfully.');
                        redirect(base_url() . 'home/changepass/');
                    } else {
                        $this->session->set_flashdata('error', 'Current password and new password cannot be same..');
                        redirect(base_url() . 'Home/changepass/');
                    }
                }
            }
        }
        $data = array(
            'middle_content' => 'change_pass',
            $data,
        );
        $this->load->view('common_view', $data);
    }

    // #------------------ Exam list for logged in user(PRAFULL)---------------##
    public function benchmark_disability_check()
    {
        $msg         = '';
        $flag        = 1;
        $user_images = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration,mobile,email,benchmark_disability');

        if ($user_images[0]['benchmark_disability'] == '') {
            $flag = 0;
            $msg .= '<li>
                Kindly go to Edit Profile and <a href="' . base_url() . 'Home/profile/">click here</a> to update the, "Benchmark Disability" and then apply for exam. For any queries contact zonal office.</li>';
        }

        if ($flag) {
            redirect(base_url() . 'Home/dashboard');
        }
        $data = array(
            'middle_content' => 'member_notification',
            'msg'            => $msg,
        );
        $this->load->view('common_view', $data);
    }

    public function examlist1() //NOT IN USE

    {
        // accedd denied due to GST
        $GST_val = check_GST($this->session->userdata('regnumber'));
        if ($GST_val == 2) {
            redirect(base_url() . 'Home/GST');
        }
        // accedd denied due to GST
        // $this->master_model->warning();
        $user_images = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration,mobile,email,benchmark_disability');
        /* if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto'])
        ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']==''
        ||$user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
        {*/
        if (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')) || validate_userdata($this->session->userdata('regnumber')))
        // if(validate_userdata($this->session->userdata('regnumber')))
        // !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) ||
        {
            redirect(base_url() . 'Home/notification');
        }
        // Benchmark Disability Check
        if ($user_images[0]['benchmark_disability'] == '') {
            redirect(base_url() . 'Home/benchmark_disability_check');
        }
        // $this->chk_session->checkphoto();

        //XXX : START : Allowing member to register for JAIIB / CAIIB after registration closed
        if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {
            $today_date = '2022-10-15'; //date('Y-m-d'); //'2021-11-29';//
            $flag       = 1;
            $exam_list  = array();
            $examcodes  = array($this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),'65',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));//'21', '42'
            $this->db->where('elg_mem_o', 'Y');
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            $exam_list = $this->master_model->getRecords('exam_master');
        } //XXX : END : Allowing member to register for JAIIB / CAIIB after registration closed
        else {
            $today_date = date('Y-m-d');
            $flag       = 1;
            $exam_list  = array();

            /*XXX Added By Sagar On 30-11-2021 For allowing member to register for JAIIB after registration closed */
            //$this->db->where_not_in('exam_master.exam_code', '60');
            $this->db->where_not_in('exam_master.exam_code', $this->config->item('examCodeJaiib'));

            $examcodes = array('528', '529', '530', '531', '534', '991', '997', '1052', '1054', '1053');
            $this->db->where('elg_mem_o', 'Y');
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_not_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            $exam_list = $this->master_model->getRecords('exam_master');
        }

        //echo $this->db->last_query();
        $data = array(
            'middle_content' => 'member_exam_list',
            'exam_list'      => $exam_list,
        );
        $this->load->view('common_view', $data);
    }

    public function examlist()
    {
        if($this->get_client_ip1()!='115.124.115.69'  && $this->get_client_ip1()!='182.73.101.70') {
			//echo 'Under maintenance';exit;
		}
        
        // accedd denied due to GST
        $GST_val = check_GST($this->session->userdata('regnumber'));
        if ($GST_val == 2) {
            redirect(base_url() . 'Home/GST');
        }
        // accedd denied due to GST
        // $this->master_model->warning();
        $user_images = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration,mobile,email,benchmark_disability,associatedinstitute');
        /* if(!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto'])
        ||$user_images[0]['scannedphoto']=='' ||$user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']==''
        ||$user_images[0]['mobile']=='' ||$user_images[0]['email']=='')
        {*/
        if (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')) || validate_userdata($this->session->userdata('regnumber')))
        // if(validate_userdata($this->session->userdata('regnumber')))
        // !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) ||
        {
            redirect(base_url() . 'Home/notification');
        }
        // Benchmark Disability Check
        if ($user_images[0]['benchmark_disability'] == '') {
            redirect(base_url() . 'Home/benchmark_disability_check');
        }
        // $this->chk_session->checkphoto();

        //XXX : START : Step 2 : Allowing member to register for JAIIB / CAIIB after registration closed
        if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {
            $today_date = '2022-11-09'; //date('Y-m-d'); //'2021-11-29';//
            $flag       = 1;
            $exam_list  = array();
            //$examcodes  = array('60','63','65','68','69','70','71');
            $examcodes=array('19');//,'11','19'
            $this->db->where('elg_mem_o', 'Y');
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            $exam_list = $this->master_model->getRecords('exam_master');

            if($this->session->userdata('regnumber') == 500083268){
                $exam_list1 = 8;
                //break;
            }
            else if($this->session->userdata('regnumber') == 500213680 || $this->session->userdata('regnumber') == 510257433 || $this->session->userdata('regnumber') == 510330745){
                $exam_list1 = 11;
                //echo 'xxx';
                //break;
            }
            else if($this->session->userdata('regnumber') == 500083125 || $this->session->userdata('regnumber') == 510081606) {
                $exam_list1 = 19;
                //break;
            }
            else{
                $exam_list1 = 0;
            }
        } //XXX : START : Step 2 : Allowing member to register for JAIIB / CAIIB after registration closed
        else {
            $today_date = date('Y-m-d');
            $flag       = 1;
            $exam_list  = array();

            /*XXX Added By Sagar On 30-11-2021 For allowing member to register for JAIIB after registration closed */
            //$this->db->where_not_in('exam_master.exam_code', '60');
            // $this->db->where_not_in('exam_master.exam_code', '21');

            $examcodes = array('528', '529', '530', '531', '534', '991', '997', '1052', '1054', '1053');
            $this->db->where('elg_mem_o', 'Y');
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->where_not_in('exam_master.exam_code', $examcodes);
            $this->db->group_by('medium_master.exam_code');
            $exam_list = $this->master_model->getRecords('exam_master');
          //  echo $this->db->last_query();exit;
            
        }

         
        //echo $this->db->last_query();
         
        $associatedinstitute = ($user_images && $user_images[0]['associatedinstitute'] ? $user_images[0]['associatedinstitute'] : '');
        
       // echo $this->db->last_query();exit;

        $data = array(
                'middle_content' => 'member_exam_list',
                'exam_list'      => $exam_list,
                'associatedinstitute' => $associatedinstitute,
                'exam_list1' => $exam_list1
            );
        
        //echo $this->get_client_ip1();
        /*$my_ip = $this->get_client_ip1();
        //echo $my_ip;
        if($my_ip == "115.124.115.69" || $my_ip == "182.73.101.70"){
            $data = array(
                'middle_content' => 'member_exam_list',
                'exam_list'      => $exam_list,
                'associatedinstitute' => $associatedinstitute,
                'exam_list1' => $exam_list1
            );
        }else{
            $data = array(
                'middle_content' => 'member_exam_list',
                'exam_list'      => array(),//$exam_list,
                'associatedinstitute' => $associatedinstitute,
                'exam_list1' => $exam_list1
            );
        }*/

        
        $this->load->view('common_view', $data);
    }

    public function accessdenied()
    {
        // echo 'accessdenied';exit;
        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';
        $data    = array(
            'middle_content'    => 'not_eligible',
            'check_eligibility' => $message,
        );
        $this->load->view('common_view', $data);
    }

    //Added by Priyanka W on 31/10/202 for Dipcert
    public function accessdenied_dipcert()
    {
        $message = '<div style="color:#F00">This exam has been discontinued. No fresh registrations are allowed.</div>';
        $data    = array('middle_content' => 'nonmember/not_eligible', 'check_eligibility' => $message);
        $this->load->view('nonmember/nm_common_view', $data);
    }

    // #------------------ Specific Exam Details for logged in user(PRAFULL)---------------##
    public function examdetails()
    {

        #check GST amount PAID or PENDING
        $GST_val = check_GST($this->session->userdata('regnumber'));
        if ($GST_val == 2) {
            redirect(base_url() . 'Home/GST');
        }

        //Added By Priyanka W for Wrong fees Dipcert link reopen
            /*$exam_code = base64_decode($this->input->get('excode2'));
            if($exam_code == 18 || $exam_code == 78 || $exam_code == 79 || $exam_code == 149 || $exam_code == 151 || $exam_code == 153 || $exam_code == 158 || $exam_code == 163 || $exam_code == 165){
              
                $mem_no = $this->session->userdata('regnumber');

                $check = $this->check_eligibility_dipcert($exam_code, $mem_no);
                if($check == 0){
                    die('You are not Eligible for the exam...');
                }
            }*/
        //closed

        if($exam_code==210 || $exam_code==420)
           {

            if($this->get_client_ip1()!='115.124.115.75'  && $this->get_client_ip1()!='182.73.101.70' && $this->get_client_ip1()!='115.124.115.69'  && $this->get_client_ip1()!='192.168.11.34') {
              //  echo'JAIIB/DBF will be start soon';exit;
            }
           }
        // Benchmark Disability Check
        $user_benchmark = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'benchmark_disability,associatedinstitute');
        if ($user_benchmark[0]['benchmark_disability'] == '') {
            redirect(base_url() . 'Home/benchmark_disability_check');
        }

        /*For OLD BCBF Exam check in New BCBF Module*/
        $decode_exam_code = base64_decode($this->input->get('excode2'));
        if($decode_exam_code == "101" || $decode_exam_code == "1046" || $decode_exam_code == "1047"){
            $user_details_iibfbcbf = $this->master_model->getRecords('iibfbcbf_batch_candidates', array(
                'regnumber' => $this->session->userdata('regnumber'),
                'parent_table_name'  => 'member_registration',
                'is_deleted'  => '0',
            ), 'regnumber');
            if(isset($user_details_iibfbcbf) && count($user_details_iibfbcbf) > 0){
                $this->session->set_flashdata('error_invalide_exam_selection', "You are not eligible to register for the selected examination. <strong>For any queries contact zonal office</strong>.");
                //echo $this->db->last_query();die;
                redirect(base_url() . 'Home/accessdenied_not_old_bcbf_mem/');
            }
        }
        /*For OLD BCBF Exam check in New BCBF Module*/ 

        $get_ip_address = '';
        /*$set_client_ip_address = array('182.73.101.70','115.124.115.72','115.124.115.75');
        $exam_codes_chk = array(1002,1003,1004,1005,1009,1013,1014,101,1046,1047);
        $get_ip_address = get_ip_address();
        if(in_array(base64_decode($this->input->get('excode2')),$exam_codes_chk) && in_array($get_ip_address,$set_client_ip_address) )
        {
            echo "<br>".$get_ip_address;
        }else if(!in_array(base64_decode($this->input->get('excode2')),$exam_codes_chk) && !in_array($get_ip_address,$set_client_ip_address) ){
            echo "Site Under Maintenance. Please try again later.".$get_ip_address;exit;
        }*/


        //START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
        $decode_exam_code = base64_decode($this->input->get('excode2'));
        $check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
        if($check_valid_exam_flag == 1){
            $this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile.");
            redirect(base_url() . 'Home/examlist');
        } 
        //END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023    


        // check exam activate or not
        $check_exam_activation = check_exam_activate(base64_decode($this->input->get('excode2')));
        if ($check_exam_activation == 0) {
            //XXX : START : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed
            if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {} else {redirect(base_url() . 'Home/accessdenied/');}
            //XXX : END : Step 3 : Allowing member to register for JAIIB / CAIIB after registration closed
        } else //NOT IN USE
        {
            //XXX : START : Allowing member to register for JAIIB / CAIIB after registration closed
            /*if(base64_decode($this->input->get('excode2')) == 21)
            {
            //if(in_array($this->session->userdata('regnumber'),$this->jaiib_reschedule_arr)) { }
            //else  { redirect(base_url() . 'Home/accessdenied/'); }
            }
            else if(base64_decode($this->input->get('excode2')) == 60)
            {
            if(in_array($this->session->userdata('regnumber'),$this->jaiib_reschedule_arr)) { }
            else  { redirect(base_url() . 'Home/accessdenied/'); }
            }*/
            //XXX : END : Allowing member to register for JAIIB / CAIIB after registration closed
        }

        $flag = $this->checkusers(base64_decode($this->input->get('excode2')));
        if ($flag == 0) {
            //XXX : START : Step 4 : Allowing member to register for JAIIB / CAIIB after registration closed
            if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {} else {redirect(base_url() . 'Home/accessdenied/');}
            //XXX : END : Step 4 : Allowing member to register for JAIIB / CAIIB after registration closed
        }

        //Added bby Priyanka W on 26/10/202 for Dipcert
            $examCd = base64_decode($this->input->get('excode2'));
               // echo $examCd; die;
            if(in_array($examCd,$this->config->item('examCodeDIPCERT'))){ 
                $memNo =  $this->session->userdata('regnumber');
                //$this->db->where_in('exam_status',array('F','A'));
                //$this->db->where_in('app_category',array('R','B1_1'));
                $res = $this->master_model->getRecords('eligible_master', array('member_no' => $memNo, 'exam_code' => $examCd));
               // echo $this->db->last_query(); die;
                if(count($res)>0){
                    //foreach ($$res as $key => $value) {
                       //if(($value['exam_status'] == 'F' || $value['exam_status'] == 'A') && $value['app_category'] == 'R' || $value['app_category'] == 'B1_1'){
                        if(($res[0]['exam_status'] == 'F' || $res[0]['exam_status'] == 'A') && $res[0]['app_category'] == 'R' || $res[0]['app_category'] == 'B1_1'){
                            
                            if ($examCd == 8 || $examCd == 158) 
                            {
                                if(($res[0]['exam_status'] == 'F' || $res[0]['exam_status'] == 'A') && $res[0]['app_category'] == 'R' || $res[0]['app_category'] == 'S1' || $res[0]['app_category'] == 'B1_1' || $res[0]['app_category'] == 'B1_2' || $res[0]['app_category'] == '') 
                                {
                                    redirect(base_url() . 'Home/accessdenied_dipcert/');
                                }
                            }
                            else
                            {
                               redirect(base_url() . 'Home/accessdenied_dipcert/');     
                            }
                        }
                    //}
                }
                else{
                    redirect(base_url() . 'Home/accessdenied_dipcert/');
                }
            }
            //End 

        $cookieflag = $exam_status = 1;
        // $this->chk_session->checkphoto();
        $message            = '';
        $applied_exam_info  = array();
        $flag               = 1;
        $checkqualifyflag   = 0;
        $examcode           = base64_decode($this->input->get('excode2'));
        $check_qualify_exam = $this->master_model->getRecords('exam_master', array(
            'exam_code' => $examcode,
        ));
        if ($check_qualify_exam[0]['exam_category'] == 1) {
            redirect(base_url() . 'SplexamM/examdetails/?excode2=' . $this->input->get('excode2') . '&' . 'Extype=' . $this->input->get('Extype'));
        }
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $this->session->userdata('regnumber');
        // $valcookie= applyexam_get_cookie();
        if ($valcookie) {
            $regnumber    = $valcookie;
            $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                'member_regnumber' => $regnumber,
                'status'           => '2',
                'pay_type'         => '2',
                'exam_code'        => $examcode
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
        // END Of ask user to wait for 5 min, until the payment transaction process complete

        //START : ADDED BY SAGAR M ON 2024-09-12 
        $exam_date_arr = $subject_arr_for_examdate = array();
        $subject_arr_for_examdate = $this->session->userdata['examinfo']['subject_arr'];
        if(count($subject_arr_for_examdate) > 0){
            foreach ($subject_arr_for_examdate as $k => $v) 
            {
              $exam_date_arr[] = $v['date']; 
            }
        }
        //END : ADDED BY SAGAR M 2024-09-12

        // $check=$this->examapplied($this->session->userdata('regnumber'),$this->input->get('excode2'));
        // if(!$check)
        // {
        // Query to check selected exam details
        if (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')) || validate_userdata($this->session->userdata('regnumber'))) {
            redirect(base_url() . 'Home/notification');
        }

        if (count($check_qualify_exam) > 0) {
            // Condition to check the qualifying id exist
            // if($check_qualify_exam[0]['qualifying_exam1']!='' && $check_qualify_exam[0]['qualifying_exam1']!='0' && $checkqualifyflag==0)
            if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0') {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $examcode, $check_qualify_exam[0]['qualifying_part1']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            // if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )
            if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0') {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $examcode, $check_qualify_exam[0]['qualifying_part2']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            }
            // if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)
            if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0') {
                $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $examcode, $check_qualify_exam[0]['qualifying_part3']);
                $flag        = $qaulifyarry['flag'];
                $message     = $qaulifyarry['message'];
                if ($flag == 0) {
                    $checkqualifyflag = 1;
                }
            } else
            if ($flag == 1 && $checkqualifyflag == 0) {
                // check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                    'eligible_master.exam_code' => $examcode,
                    'member_no'                 => $this->session->userdata('regnumber'),
                ));

                /*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                'eligible_master.exam_code' => $examcode,
                'member_type' => $this->session->userdata('memtype') ,
                'member_no' => $this->session->userdata('regnumber')
                ));*/
                if (count($check_eligibility_for_applied_exam) > 0) {
                    foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
                        if ($check_exam_status['exam_status'] == 'F') {
                            $exam_status = 0;
                        }
                    }
                    // if($exam_status==1 ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
                    if ($exam_status == 1) {
                        $flag    = 0;
                        $message = $check_eligibility_for_applied_exam[0]['remark'];
                    }
                    /*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
                    {
                    $flag=0;
                    $message=$check_eligibility_for_applied_exam[0]['remark'];
                    }*/
                    else
                    if ($exam_status == 0) {
                        $check = $this->examapplied($this->session->userdata('regnumber'), $this->input->get('excode2'), $exam_date_arr);
                        if (!$check) {
                            $check_date = $this->examdate($this->session->userdata('regnumber'), $this->input->get('excode2'));
                            if (!$check_date) {
                                // CAIIB apply directly
                                $flag = 1;
                            } else {
                                $message = $this->get_alredy_applied_examname($this->session->userdata('regnumber'), $this->input->get('excode2'));
                                // $message='Exam fall in same date';
                                $flag = 0;
                            }
                        } else {
                            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
                            $get_period_info = $this->master_model->getRecords('misc_master', array(
                                'misc_master.exam_code'   => base64_decode($this->input->get('excode2')),
                                'misc_master.misc_delete' => '0',
                            ), 'exam_month');
                            // $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
                            $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
                            $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
                            $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.... period. Hence you need not apply for the same.';
                            $flag             = 0;
                        }
                    }
                } else {
                    $check = $this->examapplied($this->session->userdata('regnumber'), $this->input->get('excode2'), $exam_date_arr);
                    if (!$check) {
                        $check_date = $this->examdate($this->session->userdata('regnumber'), $this->input->get('excode2'));
                        if (!$check_date) {
                            // CAIIB apply directly
                            $flag = 1;
                        } else {
                            $message = $this->get_alredy_applied_examname($this->session->userdata('regnumber'), $this->input->get('excode2'));
                            // $message='Exam fall in same date';
                            $flag = 0;
                        }
                    } else {
                        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
                        $get_period_info = $this->master_model->getRecords('misc_master', array(
                            'misc_master.exam_code'   => base64_decode($this->input->get('excode2')),
                            'misc_master.misc_delete' => '0',
                        ), 'exam_month');
                        // $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
                        $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
                        $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
                        $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.... period. Hence you need not apply for the same.';
                        // $message='You have already applied for the examination11';
                        $flag = 0;
                    }
                }
            }
        } else {
            $flag = 1;
        }
        // Query to check where exam applied successfully or not with transaction
        $is_transaction_doone = $this->master_model->getRecordCount('payment_transaction', array(
            'exam_code'        => $examcode,
            'member_regnumber' => $this->session->userdata('regnumber'),
            'status'           => '1',
        ));

        if ($is_transaction_doone > 0) {

            $today_date = date('Y-m-d');
            $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,member_exam.created_on');
            $this->db->where('exam_master.elg_mem_o', 'Y');
            // $this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            // $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('member_exam.pay_status', '1');
            $applied_exam_info = $this->master_model->getRecords('member_exam', array(
                'member_exam.exam_code' => $examcode,
                'regnumber'             => $this->session->userdata('regnumber'),
            ));
            //print_r($applied_exam_info);exit;
        }
        ########get Eligible createon date######
        $this->db->limit('1');
        $get_eligible_date = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode), 'eligible_master.created_on');
        $eligiblecnt       = 0;
        if (count($applied_exam_info) > 0) {

            if (strtotime($applied_exam_info[0]['created_on']) > strtotime($get_eligible_date[0]['created_on'])) {
                $eligiblecnt = $eligiblecnt + 1;
            }
        }
        if ($cookieflag == 0) {
            $data = array(
                'middle_content' => 'exam_apply_cms_msg',
            );
            $this->load->view('common_view', $data);
        } else
        if ($flag == 0 && $cookieflag == 1) {
            $data = array(
                'middle_content'    => 'not_eligible',
                'check_eligibility' => $message,
            );
            $this->load->view('common_view', $data);
        } else if ($eligiblecnt) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
            $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0'), 'exam_month');
            //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
            $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
            $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
            $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';
            $data             = array(
                'middle_content'    => 'already_apply',
                'check_eligibility' => $message,
            );
            $this->load->view('common_view', $data);
        }
        /* else
        if (count($applied_exam_info) > 0)
        {
        $get_period_info = $this->master_model->getRecords('misc_master', array(
        'misc_master.exam_code' => base64_decode($this->input->get('excode2')) ,
        'misc_master.misc_delete' => '0'
        ) , 'exam_month');
        // $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
        $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
        $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
        $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.... period. Hence you need not apply for the same.';
        $data = array(
        'middle_content' => 'already_apply',
        'check_eligibility' => $message
        );
        $this->load->view('common_view', $data);
        }*/
        else
        if ($cookieflag == 1) {
            $exam_info = $this->master_model->getRecords('exam_master', array(
                'exam_code' => $examcode,
            ));

            $this->db->where('exam_code', base64_decode($this->input->get('excode2')));
            $get_period = $this->master_model->getRecords('exam_activation_master', '', 'exam_period');
            /*Payment Check Code - Bhushan */
            $check_payment_val = check_payment_status($this->session->userdata('regnumber'));
            if ($check_payment_val == 1) {
                //redirect(base_url() . 'Home/Payment_process');
                $msg  = '<h4> Your transaction is in process. Please wait for some time.</h4>';
                $data = array('middle_content' => 'member_notification', 'msg' => $msg);
                $this->load->view('common_view', $data);
            } else {
                $this->db->where('fee_paid_flag', 'F');
                //$this->db->or_where('fee_paid_flag','f');
                $this->db->where('eligible_period', $get_period[0]['exam_period']);
                $this->db->where('member_no', $this->session->userdata('regnumber'));
                $this->db->where('exam_code', base64_decode($this->input->get('excode2')));
                $this->db->order_by("id", "desc");
                $eligible_info = $this->master_model->getRecords('eligible_master', '', 'exam_status,eligible_period');

                if (count($eligible_info) > 0) {
                    redirect(base_url() . 'Remote_exam_home/examdetails/?ExId=' . $this->input->get('excode2') . '&Extype=' . $this->input->get('Extype') . '&Exprd=' . base64_encode($eligible_info[0]['eligible_period']));
                }

                $data = array(
                    'middle_content' => 'cms_page',
                    'exam_info'      => $exam_info,
                );
                $this->load->view('common_view', $data);
            }
        }
        // }
        /*else
    {
    $data=array('middle_content'=>'already_apply','check_eligibility'=>'You have already applied for the examination.');
    $this->load->view('common_view',$data);
    }*/
    }

    // #-------------- check qualify exam pass/fail
    /*public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)
    {
    $flag=0;
    $check_qualify=array();
    $message='Pre qualifying exam not found';
    $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));
    // Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
    $check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('regnumber')),'exam_status,remark');
    if(count($check_qualify_exam_eligibility) > 0)
    {
    if($check_qualify_exam_eligibility[0]['exam_status']=='P')
    {
    // check eligibility for applied exam(This are the exam who  have pre qualifying exam)
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
    $check_eligibility_for_applied_exam=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$examcode,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('regnumber')));
    if(count($check_eligibility_for_applied_exam) > 0)
    {
    if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v')
    {
    $flag=0;
    $message=$check_eligibility_for_applied_exam[0]['remark'];
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
    {
    $flag=0;
    $message=$check_eligibility_for_applied_exam[0]['remark'];
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
    {
    $flag=1;
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    }
    else
    {
    // CAIIB apply directly
    $flag=1;
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    }
    else
    {
    $flag=0;
    $message=$check_qualify_exam_eligibility[0]['remark'];
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    }
    else
    {
    // show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
    $flag=0;
    if($qualify_id)
    {
    $get_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$qualify_id),'description');
    if(count($get_exam) > 0)
    {
    $message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';
    }
    }
    $check_qualify=array('flag'=>$flag,'message'=>$message);
    return $check_qualify;
    }
    }*/

    // #-------------- check qualify exam pass/fail
    public function checkqualify($qualify_id = null, $examcode = null, $part_no = null)
    {
        $flag                    = 0;
        $exam_status             = 1;
        $check_qualify           = array();
        $check_qualify_exam_name = $this->master_model->getRecords('exam_master', array(
            'exam_code' => $qualify_id,
        ), 'description');
        if (count($check_qualify_exam_name) > 0) {
            if ($examcode == 19) {$message = 'You are not eligible to apply for this exam, you should either be <strong>CAIIB</strong> passed or should have <strong>CS qualification</strong>.';} else { $message = 'you have not cleared qualifying examination - <strong>' . $check_qualify_exam_name[0]['description'] . '</strong>.';}
        } else {
            $message = 'you have not cleared qualifying examination.';
        }
        $check_qualify_exam = $this->master_model->getRecords('exam_master', array(
            'exam_code' => $examcode,
        ));
        // Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
        $check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array(
            'eligible_master.exam_code' => $qualify_id,
            'part_no'                   => $part_no,
            'member_no'                 => $this->session->userdata('regnumber'),
        ), 'exam_status,remark');
        /*$check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array(
        'eligible_master.exam_code' => $qualify_id,
        'part_no' => $part_no,
        'member_type' => $this->session->userdata('memtype') ,
        'member_no' => $this->session->userdata('regnumber')
        ) , 'exam_status,remark');*/
        if (count($check_qualify_exam_eligibility) > 0) {
            foreach ($check_qualify_exam_eligibility as $check_exam_status) {
                if ($check_exam_status['exam_status'] == 'F' || $check_exam_status['exam_status'] == 'V' || $check_exam_status['exam_status'] == 'D') {
                    $exam_status = 0;
                }
            }
            // if($check_qualify_exam_eligibility[0]['exam_status']=='P')

            if ($exam_status == 1) {
                // check eligibility for applied exam(This are the exam who  have pre qualifying exam)
                if (base64_decode($this->input->get('Extype')) == '3') {
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
                    $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                        'eligible_master.subject' => '1' . $examcode,
                        'member_no'               => $this->session->userdata('regnumber'),
                    ));
                    /*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                'eligible_master.subject'=>'1'. $examcode,
                'member_type' => $this->session->userdata('memtype') ,
                'member_no' => $this->session->userdata('regnumber')
                ));*/
                } else {
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=eligible_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period');
                    $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                        'eligible_master.exam_code' => $examcode,
                        'member_no'                 => $this->session->userdata('regnumber'),
                    ));
                    /*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array(
                'eligible_master.exam_code' => $examcode,
                'member_type' => $this->session->userdata('memtype') ,
                'member_no' => $this->session->userdata('regnumber')
                ));*/
                }
                if (count($check_eligibility_for_applied_exam) > 0) {
                    foreach ($check_eligibility_for_applied_exam as $check_exam_status) {
                        if ($check_exam_status['exam_status'] == 'F') {
                            $exam_status = 0;
                        }
                    }
                    // if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')
                    if ($exam_status == 1) {
                        $flag = 0;
                        if (base64_decode($this->input->get('Extype')) == '3') {
                            //    $message='You have already cleared this subject as separate  Examination. Hence you cannot apply for the same.';
                            $message = 'You have already cleared this subject under <strong>' . $check_qualify_exam_name[0]['description'] . '</strong> Elective Examination. Hence you cannot apply for the same';
                        } else {
                            $message = $check_eligibility_for_applied_exam[0]['remark'];
                        }
                        $check_qualify = array(
                            'flag'    => $flag,
                            'message' => $message,
                        );
                        return $check_qualify;
                    }
                    // else if($check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' )
                    else
                    if ($exam_status == 1) {
                        $flag = 0;
                        if ($check_qualify_exam_eligibility[0]['remark'] != '') {
                            $message = $check_qualify_exam_eligibility[0]['remark'];
                        }
                        $check_qualify = array(
                            'flag'    => $flag,
                            'message' => $message,
                        );
                        return $check_qualify;
                    }
                    /*else if(($check_eligibility_for_applied_exam[0]['exam_status']=='F' || $check_eligibility_for_applied_exam[0]['exam_status']=='f') && $check_eligibility_for_applied_exam[0]['fees']=='' && ($check_eligibility_for_applied_exam[0]['app_category']=='B1' || $check_eligibility_for_applied_exam[0]['app_category']=='B2'))
                    {
                    $flag=0;
                    $message=$check_eligibility_for_applied_exam[0]['remark'];
                    $check_qualify=array('flag'=>$flag,'message'=>$message);
                    return $check_qualify;
                    }*/
                    // else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')
                    else
                    if ($exam_status == 0) {
                        $flag          = 1;
                        $check_qualify = array(
                            'flag'    => $flag,
                            'message' => $message,
                        );
                        return $check_qualify;
                    }
                } else {
                    // CAIIB apply directly
                    $flag          = 1;
                    $check_qualify = array(
                        'flag'    => $flag,
                        'message' => $message,
                    );
                    return $check_qualify;
                }
            } else {
                $flag          = 0;
                $qualification = 0;
                $user_info     = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'specify_qualification');
                if (count($user_info) > 0) {
                    $qualification = $user_info[0]['specify_qualification'];
                }
                if ($qualification == 91 && $examcode == 19) {
                    $flag = 1;
                }
                if ($check_qualify_exam_eligibility[0]['remark'] != '') {
                    $message = $check_qualify_exam_eligibility[0]['remark'];
                }
                $check_qualify = array(
                    'flag'    => $flag,
                    'message' => $message,
                );
                return $check_qualify;
            }
        } else {
            // show message with pre-qualifying exam name if pre-qualify exam yet to not apply.
            $flag = 0;
            if ($qualify_id) {
                $get_exam = $this->master_model->getRecords('exam_master', array(
                    'exam_code' => $qualify_id,
                ), 'description');
                if (count($get_exam) > 0) {
                    $qualification = 0;
                    $user_info     = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'specify_qualification');
                    if (count($user_info) > 0) {
                        $qualification = $user_info[0]['specify_qualification'];
                    }
                    if (base64_decode($this->input->get('Extype')) == '3') {
                        if ($qualification == 91 && $examcode == 19) {
                            $flag = 1;
                        } else {
                            if ($examcode == 19) {$message = 'You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';} else { $message = 'you have not cleared qualifying examination - <strong>' . $get_exam[0]['description'] . '</strong>.';}
                        }
                    } else {
                        if ($qualification == 91 && $examcode == 19) {
                            $flag = 1;
                        } else {
                            if ($examcode == 19) {$message = 'You are not eligible to apply for this exam, you should either be CAIIB passed or should have CS qualification.';} else {
                                $message = 'You have not cleared  <strong>' . $get_exam[0]['description'] . '</strong> examination, hence you cannot apply for <strong> ' . $check_qualify_exam[0]['description'] . '</strong>.';
                            }
                        }
                    }
                }
            }
            $check_qualify = array(
                'flag'    => $flag,
                'message' => $message,
            );
            return $check_qualify;
        }
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
    // #------------------ CMS Page for logged in user(PRAFULL)---------------##
    public function comApplication()
    {
        if($this->get_client_ip1()!='115.124.115.77'    && $this->get_client_ip1()!='115.124.115.75'    && $this->get_client_ip1()!='182.73.101.70'&& $this->get_client_ip1()!='192.168.11.253') 
        {
        
                $excodes = array(210,420);
            
            if(in_array($this->session->userdata('examcode'),$excodes)) {
              //  echo'JAIIB/DBF will be start soon';exit;
		}
		    }
        
     //   echo 'Under maintenance.We will get back in 30 minutes';exit;
      //  genarate_admitcard(100097057, 210, 123);exit;
        ## show/hide elective subject dropdown

        $restrictNumbers=array(510359464,510388870,510534334,510273173,510395453,510501650,510562264,500214094,510440166,510234966,510288798,510176064,500017391,510423292,510322653,510486576,510115322,510563160,510288429,510488116,510426908,510201584,510508419,510360640,510410560,510457301,510462292,510195816);
            if(in_array($this->session->userdata('regnumber'),$restrictNumbers) && $this->session->userdata('examcode') == $this->config->item('examCodeCaiib') ) {
                echo'Already Applied';
                exit;
            }
        $elective = 'hide';

        
        if (!is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')) || validate_userdata($this->session->userdata('regnumber'))) {
            redirect(base_url() . 'Home/notification');
        }
        // accedd denied due to GST
        // $this->master_model->warning();
        $caiib_subjects      = array();
        $compulsory_subjects = array();
        if (isset($_POST['btnPreviewSubmit'])) {
          //  echo'<pre>';print_r($_POST);exit;  
            //exit;
            $scribe_flag       = 'N';
            $venue             = $this->input->post('venue');
            $date              = $this->input->post('date');
            $time              = $this->input->post('time');
            $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $declaration_file = $state = $password = $var_errors = '';
            if ($this->session->userdata('examinfo')) {
                $this->session->unset_userdata('examinfo');
            }
            $this->form_validation->set_rules('scribe_flag', 'Scribe Services', 'required');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
            if (($this->session->userdata('examcode') != 101 && $this->session->userdata('examcode') != 1046 && $this->session->userdata('examcode') != 1047) && $this->input->post('venue') != '' && $this->input->post('date') && $this->input->post('time') != '') {
                $this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');
                $this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');
                $this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');
            }
            $this->form_validation->set_rules('medium', 'Medium', 'required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');
            $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');
            if ($this->session->userdata('examcode') == $this->config->item('examCodeCaiib')) {
                if ($this->input->post('check_elective_validation') == 'Y') {
                    $this->form_validation->set_rules('selSubcode', 'Elective Subject Name', 'required|xss_clean');
                }
            }

            if ($this->session->userdata('examcode') == $this->config->item('examCodeSOB')) {
                if ($this->input->post('elearning_flag') == 'Y') {
                    $this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
                }
            }
            if ($this->session->userdata('examcode') == $this->config->item('examCodeJaiib') || $this->session->userdata('examcode') == $this->config->item('examCodeCaiib') || $this->session->userdata('examcode') == 65) {

                if ($this->input->post('elearning_flag') == 'Y') {
                    $this->form_validation->set_rules('el_subject[]', 'Elearning subject', 'trim|required|xss_clean');
                }

                $this->form_validation->set_rules('placeofwork', 'Place of Work', 'trim|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('state_place_of_work', 'State', 'trim|required|xss_clean');
                if ($this->input->post('state_place_of_work') != '') {
                    $state = $this->input->post('state_place_of_work');
                }
                $this->form_validation->set_rules('pincode_place_of_work', 'Pin Code', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
            }

            if($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047){

                $this->form_validation->set_rules('doj1', 'Date of commencement of operations/joining as BC', 'required|xss_clean');
                $this->form_validation->set_rules('ippb_emp_id', 'Bank BC ID No', 'required|alpha_numeric_spaces|max_length[20]|xss_clean');
                $this->form_validation->set_rules('empidproofphoto', 'Upload Bank BC ID Card', 'file_required|file_allowed_type[jpg,jpeg]|file_size_min[100]|file_size_max[300]|callback_empidproofphoto_upload');

                if ($this->input->post('name_of_bank_bc') != '') {
                    $name_of_bank_bc = $this->input->post('name_of_bank_bc');
                    $this->form_validation->set_rules('ippb_emp_id', 'Bank BC ID No', 'trim|required|alpha_numeric_spaces|xss_clean|callback_check_bank_bc_id_no_duplication[' . $name_of_bank_bc . ']');
                } 
                //if($this->input->post('doj1') != '' && $this->input->post('exam_date_exist') != ''){
                //echo "Inn".$this->input->post('doj1')."==".$this->input->post('exam_date_exist');die;
                    $exam_date_exist = $this->input->post('exam_date_exist');
                    $this->form_validation->set_rules('doj1', 'Date of Joining', 'trim|required|xss_clean|callback_check_date_of_joining_bc_validation[' . $exam_date_exist . ']');
                //}
            }

            if ($this->form_validation->run() == true) {
                $subject_arr        = array();
                $venue              = $this->input->post('venue');
                $date               = $this->input->post('date');
                $time               = $this->input->post('time');
                $selinstitute       = $this->input->post('institutionworking');
                $selinstitutionname = $this->input->post('institutionname');

                if (count($venue) > 0 && count($date) > 0 && count($time) > 0) {
                    foreach ($venue as $k => $v) {
                        $this->db->group_by('subject_code');
                        $compulsory_subjects_name = $this->master_model->getRecords('subject_master', array(
                            'exam_code'      => base64_decode($_POST['excd']),
                            'subject_delete' => '0',
                            'exam_period'    => $_POST['eprid'],
                            'subject_code'   => $k,
                        ), 'subject_description');
                        $subject_arr[$k] = array(
                            'venue'        => $v,
                            'date'         => $date[$k],
                            'session_time' => $time[$k],
                            'subject_name' => $compulsory_subjects_name[0]['subject_description'],
                        );
                    }
                    // ### add elective subject in venue,time,date array#########
                    if (isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib'])) {
                        $subject_arr[$this->input->post('selSubcode')] = array(
                            'venue'        => $this->input->post('venue_caiib'),
                            'date'         => $this->input->post('date_caiib'),
                            'session_time' => $this->input->post('time_caiib'),
                            'subject_name' => $this->input->post('selSubName1'),
                        );
                    }
                    // ########check duplication of venue,date,time##########
                    if (count($subject_arr) > 0) {
                        $msg          = '';
                        $sub_flag     = 1;
                        $sub_capacity = 1;
                        foreach ($subject_arr as $k => $v) {
                            foreach ($subject_arr as $j => $val) {
                                if ($k != $j) {
                                    // if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                                    if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                                        $sub_flag = 0;
                                    }
                                }
                            }
                            $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
                            if ($capacity <= 1) {
                                //get latest/ in between 2hr record from admit-card.
                                $total_admit_count = getLastseat(base64_decode($_POST['excd']), $_POST['selCenterName'], $v['venue'], $v['date'], $v['session_time']);
                                if ($total_admit_count > 0) {
                                    $msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);
                                    $msg = $msg . ' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
                                }
                            }
                            if ($msg != '') {
                                $this->session->set_flashdata('error', $msg);
                                redirect(base_url() . 'Home/comApplication');
                            }
                        }
                    }
                    if ($sub_flag == 0) {
                        $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                        redirect(base_url() . 'Home/comApplication');
                    }
                } else {
                    if (isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib'])) {
                        $subject_arr[$this->input->post('selSubcode')] = array(
                            'venue'        => $this->input->post('venue_caiib'),
                            'date'         => $this->input->post('date_caiib'),
                            'session_time' => $this->input->post('time_caiib'),
                            'subject_name' => $this->input->post('selSubName1'),
                        );
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

                $bank_bc_id_card_file_path = $outputempidproof1 = $bank_bc_id_card_filename = $empidproof_file = $date_of_commenc_bc = '';
                if ( ($this->session->userdata('examcode') == 101 || $this->session->userdata('examcode') == 1046 || $this->session->userdata('examcode') == 1047) ) 
                {

                    if(isset($_POST["doj1"]) && $_POST["doj1"] != ""){
                        $doj1       = $_POST["doj1"];
                        $doj        = str_replace('/', '-', $doj1);
                        $date_of_commenc_bc = date('Y-m-d', strtotime($doj));  
                    }
                    // generate dynamic employee id card added by gaurav
                    $inputidproofphoto = $_POST["hiddenempidproofphoto"];
                    if (isset($_FILES['empidproofphoto']['name']) && ($_FILES['empidproofphoto']['name'] != '')) 
                    {
                        $img = "empidproofphoto";
                        $tmp_inputempidproof = strtotime($date) . rand(0, 100);
                        //$new_filename = 'non_mem_empidproof_' . $tmp_inputempidproof;

                        if($this->session->userdata('regnumber') != ""){
                            $new_filename = 'bank_bc_id_card_'.$this->session->userdata('regnumber');
                        }else{
                            $new_filename = 'bank_bc_id_card_'.$tmp_inputempidproof;
                        }

                        $config = array(
                            'upload_path' => './uploads/empidproof',
                            'allowed_types' => 'jpg|jpeg',
                            'file_name' => $new_filename,
                        );
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['empidproofphoto']['tmp_name']);
                        if ($size) 
                        {
                            if ($this->upload->do_upload($img)) 
                            {
                                $dt = $this->upload->data();
                                $bank_bc_id_card_filename = $dt['file_name'];
                                $bank_bc_id_card_file_path = base_url() . "uploads/empidproof/" . $bank_bc_id_card_filename;                                
                            } 
                            else 
                            {
                                $this->session->set_flashdata('error', 'Employee Id proof :' . $this->upload->display_errors());
                                redirect(base_url() . 'NonMember/comApplication');
                            }
                        } 
                        else 
                        {
                            $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                            redirect(base_url() . 'NonMember/comApplication');
                        }
                    }

                }

                #----Scrib Flag end - changes by POOJA GODSE---#

                //priyanka d
					$optFlg='N';
				/*	if(isset($_POST['optval']) && $_POST['optval']==1)
					$optFlg='F';
				else if(isset($_POST['optval']) && $_POST['optval']==2)
				$optFlg='R';
                    */
                    $selectedoptval=$this->session->userdata('selectedoptVal');
                    if($selectedoptval==1)
                    {
                        $_POST['grp_code']='B1_1';
                        $optFlg='F';
                    }
                    else if($selectedoptval==2)
                        $optFlg='R';
                $user_data = array(
                    'email'                  => $_POST["email"],
                    'mobile'                 => $_POST["mobile"],
                    'photo'                  => '',
                    'signname'               => '',
                    'medium'                 => $_POST['medium'],
                    'selCenterName'          => $_POST['selCenterName'],
                    'optmode'                => $_POST['optmode'],
                    'extype'                 => $_POST['extype'],
                    'exname'                 => $_POST['exname'],
                    'excd'                   => $_POST['excd'],
                    'eprid'                  => $_POST['eprid'],
                    'fee'                    => $_POST['fee'],
                    'txtCenterCode'          => $_POST['txtCenterCode'],
                    'insdet_id'              => '',
                    'selected_elect_subcode' => $_POST['selSubcode'],
                    'selected_elect_subname' => $_POST['selSubName1'],
                    'placeofwork'            => $_POST['placeofwork'],
                    'state_place_of_work'    => $_POST['state_place_of_work'],
                    'pincode_place_of_work'  => $_POST['pincode_place_of_work'],
                    'elected_exam_mode'      => $_POST['elected_exam_mode'],
                    'grp_code'               => $_POST['grp_code'],
                    'subject_arr'            => $subject_arr,
                    'el_subject'             => $el_subject,
                    'scribe_flag'            => $scribe_flag,
                    'scribe_flag_d'          => $scribe_flag_d,
                    'disability_value'       => $disability_value,
                    'Sub_menue_disability'   => $Sub_menue_disability,
                    'elearning_flag'         => $elearning_flag_new,
                    /* 'elearning_flag'=>$_POST['elearning_flag'] */
                    'optval'                 => $selectedoptval, // priyanka d- 24-03-23
                    'optFlg'                 =>  $optFlg,
                    'selinstitute'           => $selinstitute,
                    'selinstitutionname'     => $selinstitutionname,
                    'date_of_commenc_bc'  => $date_of_commenc_bc,
                    'ippb_emp_id' => (isset($_POST['ippb_emp_id']) ? $_POST['ippb_emp_id']:''),
                    'name_of_bank_bc' => (isset($_POST['name_of_bank_bc']) ? $_POST['name_of_bank_bc']:''),
                    'bank_bc_id_card_file_path' => $bank_bc_id_card_file_path,
                    'bank_bc_id_card_filename'       => $bank_bc_id_card_filename,
                    'exemption'              => 0 //priyanka d >> 03-july-24
                );
                $this->session->set_userdata('examinfo', $user_data);
                // logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
                /* User Log Activities : Bhushan */
                $log_title   = "Member exam apply details";
                $log_message = serialize($user_data);
                $rId         = $this->session->userdata('regid');
                $regNo       = $this->session->userdata('regnumber');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */
                redirect(base_url() . 'Home/preview');
            } else {
                $var_errors = str_replace("<p>", "<span>", $var_errors);
                $var_errors = str_replace("</p>", "</span><br />", $var_errors);
            }
        }
        $check_exam_activation = check_exam_activate($this->session->userdata('examcode'));
        if ($check_exam_activation == 0) {
            //XXX : START : Step 5 : Allowing member to register for JAIIB / CAIIB after registration closed
            if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {} else {redirect(base_url() . 'Home/accessdenied/');}
            //XXX : END : Step 5 : Allowing member to register for JAIIB / CAIIB after registration closed
        }

        //START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
        $decode_exam_code = $this->session->userdata('examcode');
        $check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
        if($check_valid_exam_flag == 1){
            $this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile.");
            redirect(base_url() . 'Home/examlist');
        } 

        //END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023

        $cookieflag = 1;
        // $this->chk_session->checkphoto();
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $this->session->userdata('regnumber');
        if ($valcookie) {
            $regnumber    = $valcookie;
            $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                'member_regnumber' => $regnumber,
                'status'           => '2',
                'pay_type'         => '2',
                'exam_code'        => $examcode
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
        // Considering B1 as group code in query (By Prafull)
        if ($this->session->userdata('examcode') == '') {
            redirect(base_url() . 'Home/examlist/');
        }
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
            $keepElectiveSelected=0;
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
        $this->db->join("eligible_master", 'eligible_master.exam_code=exam_activation_master.exam_code AND exam_activation_master.exam_period=eligible_master.eligible_period', 'left');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->where("eligible_master.member_no", $this->session->userdata('regnumber'));
        $this->db->where("eligible_master.app_category !=", 'R');
        $this->db->where("eligible_master.institute_id", 0); // added by gaurav for bulk rpe condition
        $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
        $this->db->order_by("eligible_master.subject_code", "asc");
        $examinfo = $this->master_model->getRecords('exam_master');
        // ###### get subject mention in eligible master ##########
        //    echo $this->db->last_query();exit;
        if (count($examinfo) > 0 && $continueAsOld==1) // priyanka d
        {
            $presentElectiveSub=array();
            if($this->session->userdata('examcode')==$this->config->item('examCodeCaiib')) { // priyanka d - 30-feb-23 >> keep provision to sect other elective sub
                $getElectiveSub=$this->master_model->getRecords('subject_master', array(
                    'exam_code'      => $this->session->userdata('examcode'),
                    'subject_delete' => '0',
                    'group_code'    => 'E', // priyanka d- 20-3023 >> change caiib exam selection 
                ));
                
                foreach($getElectiveSub as $e) {
                    $presentElectiveSub[]=$e['subject_code'];
                }
              //  echo'<pre>';print_r($getElectiveSub);exit;
            }
            
            foreach ($examinfo as $rowdata) {
                if ($rowdata['exam_status'] != 'P') {
                    ## Caiib changes on 23-Mar-2021
                    if ($rowdata['subject_code'] == 999 || in_array($rowdata['subject_code'],$presentElectiveSub)) {
                        ## Elective subjects
                        $elective = 'show';
                        $keepElectiveSelected=$rowdata['subject_code']; // priyanka d - 30-feb-23 >> keep provision to sect other elective sub
                    } else {
                        $this->db->group_by('subject_code');
                        $compulsory_subjects[] = $this->master_model->getRecords('subject_master', array(
                            'exam_code'      => $this->session->userdata('examcode'),
                            'subject_delete' => '0',
                            'group_code'    => 'C', // priyanka d- 20-3023 >> change caiib exam selection 
                            'exam_period'    => $rowdata['exam_period'],
                            'subject_code'   => $rowdata['subject_code'],
                        ));
                    }
                }
            }
            $compulsory_subjects=array_filter($compulsory_subjects);// priyanka d- 20-3023
         //   echo'<pre>';print_r($getElectiveSub);exit;
            $compulsory_subjects = array_map('current', $compulsory_subjects);
            //sort($compulsory_subjects);
        }
        // echo $this->db->last_query();exit;
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
        $this->db->where('center_master.exam_name', $this->session->userdata('examcode'));
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        // Below code, if member is new member
        if (count($examinfo) <= 0 || $continueAsOld==0) // priyanka d- 24-01-23
        {
            $this->db->select('exam_master.*,misc_master.*');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period'); //added on 5/6/2017
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));
            $examinfo = $this->master_model->getRecords('exam_master');
            // echo $this->db->last_query();exit;
            // get center
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
            $this->db->where("center_delete", '0');
            $this->db->where('exam_name', $this->session->userdata('examcode'));
            $this->db->group_by('center_master.center_name');
            $center = $this->master_model->getRecords('center_master', '', '', array(
                'center_name' => 'ASC',
            ));
            // echo '****'.$this->db->last_query();exit;
            // ###### get compulsory subject list##########
            $this->db->group_by('subject_code');
            $compulsory_subjects = $this->master_model->getRecords('subject_master', array(
                'exam_code'      => $this->session->userdata('examcode'),
                'subject_delete' => '0',
                'group_code'     => 'C',
                'exam_period'    => $examinfo[0]['exam_period'],
            ), '', array(
                'subject_code' => 'ASC',
            ));
        }
        if (count($examinfo) <= 0) {
            redirect(base_url() . 'Home/examlist');
        }
        $undergraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'UG',
        ));
        $graduate = $this->master_model->getRecords('qualification', array(
            'type' => 'GR',
        ));
        $postgraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'PG',
        ));
        $this->db->where('institution_delete', '0');
        $institution_master = $this->master_model->getRecords('institution_master');
        $this->db->where('designation_delete', '0');
        $designation   = $this->master_model->getRecords('designation_master');
        $idtype_master = $this->master_model->getRecords('idtype_master');
        // To-do use exam-code wirh medium master
        $this->db->where('state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
        $this->db->where('medium_master.exam_code', $this->session->userdata('examcode'));
        $this->db->where('medium_delete', '0');
        $medium = $this->master_model->getRecords('medium_master');
        // get center as per exam
        // user information
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
        ));
        if (count($user_info) <= 0) {
            redirect(base_url() . 'Home/dashboard');
        }
        // subject information
        $this->db->group_by('subject_code');
        $caiib_subjects = $this->master_model->getRecords('subject_master', array(
            'exam_code'      => $this->session->userdata('examcode'),
            'subject_delete' => '0',
            'group_code'     => 'E',
            'exam_period'    => $examinfo[0]['exam_period'],
        ));
        if ($cookieflag == 0 && $this->session->flashdata('error') == '') {
            $data = array(
                'middle_content' => 'exam_apply_cms_msg',
            );
        } else {
            $scribe_disability = $this->master_model->getRecords('scribe_disability', array('is_delete' => '0'));
            /* benchmark disability */
            $benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');
            // priyanka d - 23-01-23 for jaiib flag
			$order_by=array('id'=>'desc');
			$checkJaiibFlag = $this->master_model->getRecords('eligible_master', array('exam_code' => $this->session->userdata('examcode'),  'eligible_period' => $examinfo[0]['exam_period'],'member_no' => $this->session->userdata('regnumber')),'',$order_by,0,1);
			$showOptForJaiib=0;
           // echo $this->db->last_query();
			//echo'<pre>';print_r($checkJaiibFlag);exit;
			if(count($checkJaiibFlag)>0) {
				foreach($checkJaiibFlag as $j) {
					if($j['optForCandidate']=='Y')
						$showOptForJaiib=1;
				}
			}
          //  echo $showOptForJaiib;exit;
			if(isset($_GET['optval']) && $_GET['optval']!='' && $showOptForJaiib==0)  {
				redirect(base_url().'/Home/comApplication/');
			}
            if(!isset($_GET['optval']) && $this->session->userdata('selectedoptVal')!=0) {
                redirect(base_url().'/Home/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
            }
            if($_GET['optval']!= $this->session->userdata('selectedoptVal')) {
                redirect(base_url().'/Home/comApplication/?optval='.$this->session->userdata('selectedoptVal'));
            }
            
            //exemption 1007 code start
            $showExemptionOption = 0;
            $exemptionres = $this->master_model->exemption_api_call_func($this->session->userdata('examcode'),$examinfo[0]['exam_period'],$this->session->userdata('regnumber'));
            if($exemptionres=='true' && $this->session->userdata('examcode') == '1007') {
                    $showExemptionOption =1;
            }
            $chk_exemption_application_exist = $this->master_model->chk_exemption_application($this->session->userdata('examcode'),$this->session->userdata('regnumber'));
            if($chk_exemption_application_exist['applicationexist']==1 || $chk_exemption_application_exist['inprogress']==1) {
                $msg  = $chk_exemption_application_exist['msg'];
                $data = array('middle_content' => 'member_notification', 'msg' => $msg);
                return $this->load->view('nonmember/nm_common_view', $data);
            }
            //exemption 1007 code end

            // Start and add the below field institute name by gaurav
            $ExamCode    = $this->session->userdata('examcode');
            $regnumber   = $this->session->userdata('regnumber');
            $exam_period = isset($examinfo[0]['exam_period']) ? $examinfo[0]['exam_period'] : '';
            
            
            $ButtonDisable        = true;
            $arr_fedai_eligible   = [];
            $AssociateInstituteId = '';    
            if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '') {
                $arr_fedai_eligible = $this->master_model->check_fedai_eligible($ExamCode,$exam_period,$regnumber);
                $arr_response = [];
                if ($arr_fedai_eligible['api_res_flag'] == 'success') 
                {
                    $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
                     
                    if (count($arr_response)>0) 
                    {
                        $AssociateInstituteId = $arr_response[0][7];
                        $examstatus = $arr_response[0][4];
                        if($examstatus != 'F' && $examstatus != '') 
                        {
                            $msg = 'You are not eligible for Fedai Exam ';
                            if($examstatus=='P') {
                                $msg = 'You have already passed selected exam';
                            } 
                            $ButtonDisable = false;
                            $this->session->set_flashdata('error', $msg);          
                        }
                    }
                    else
                    {
                        $this->db->where("isactive",'1');
                        $this->db->where("regnumber",$regnumber);
                        $memberinfo = $this->master_model->getRecords('member_registration');
                        $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                    }
                }
                else
                {
                    $this->db->where("isactive",'1');
                    $this->db->where("regnumber",$regnumber);
                    $memberinfo = $this->master_model->getRecords('member_registration');
                    $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                }

                $sel_institute_data  = [];
                if ( $AssociateInstituteId != '' || $AssociateInstituteId != null ) 
                {
                    $this->db->where('fedai_institution_master.institution_delete', '0');
                    $this->db->where('fedai_institution_master.institude_id', $AssociateInstituteId);
                    $sel_institute_data = $this->master_model->getRecords('fedai_institution_master', '', '', array('name' => 'asc'));
                    
                    if ( count($sel_institute_data) < 1 ) {

                        $this->db->where('institution_master.institution_delete', '0');
                        $this->db->where('institution_master.institude_id', $AssociateInstituteId);
                        $sel_institute_data = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));

                        $ButtonDisable = false;
                        $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
                    }
                }
                else
                {
                    $ButtonDisable = false;
                    // $this->session->set_flashdata('error', 'Member institute is not found');
                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
                }   
            }  

            /* START: Check Member for Fedai Institute API */
            /*if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
            {
                $ButtonDisable = $this->check_fedai_member($ExamCode,$regnumber,$exam_period); 
            }*/
            /* END: Check Member for Fedai Institute API */
            // End

            /*OLD BCBF Inst Mater Dropdown*/
            $old_bcbf_institute_data = array();
            $ExamCode = $this->session->userdata('examcode');
            if( $ExamCode == 101 || $ExamCode == 991 || $ExamCode == 997 || $ExamCode == 1046 || $ExamCode == 1047 ) 
            { 
                $this->db->where('is_deleted', '0');
                $old_bcbf_institute_data = $this->master_model->getRecords('bcbf_old_exam_institute_master', '', '', array('institute_name' => 'asc')); 
            }
            /*OLD BCBF Inst Mater Dropdown*/

            $data                      = array(
                'middle_content'            => 'comApplication',
                'scribe_disability'         => $scribe_disability,
                'states'                    => $states,
                'undergraduate'             => $undergraduate,
                'graduate'                  => $graduate,
                'postgraduate'              => $postgraduate,
                'institution_master'        => $institution_master,
                'sel_institute_data'        => $sel_institute_data,
                'ButtonDisable'             => $ButtonDisable,
                'designation'               => $designation,
                'user_info'                 => $user_info,
                'idtype_master'             => $idtype_master,
                'examinfo'                  => $examinfo,
                'medium'                    => $medium,
                'center'                    => $center,
                'caiib_subjects'            => $caiib_subjects,
                'compulsory_subjects'       => $compulsory_subjects,
                'benchmark_disability_info' => $benchmark_disability_info,
                'keepElectiveSelected'      => $keepElectiveSelected,// priyanka d- 20-03-23
                'elective'                  => $elective,
                'showOptForJaiib'=>$showOptForJaiib ,// priyanka d- 24-01-23
                'selectedoptVal'=>$this->session->userdata('selectedoptVal'),
                'showExemptionOption'=>$showExemptionOption,
                'old_bcbf_institute_data' => $old_bcbf_institute_data
            );
        }
        $this->load->view('common_view', $data);
    }
    

    public function check_fedai_member($ExamCode = '', $exam_period = '', $regnumber = '')
    {
        $arr_fedai_eligible   = [];
        $AssociateInstituteId = '';
        $ButtonDisable        = true;
        
        if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '') 
        {
            $arr_fedai_eligible = $this->master_model->check_fedai_eligible($ExamCode,$exam_period,$regnumber);
            $arr_response = [];
            if ($arr_fedai_eligible['api_res_flag'] == 'success') 
            {
                $arr_response = json_decode($arr_fedai_eligible['api_res_response'],true); 
                 
                if (count($arr_response)>0) {
                    $AssociateInstituteId = $arr_response[0][7];
                    $examstatus = $arr_response[0][4];
                    if($examstatus !='F' && $examstatus != '') {
                        $msg = 'You are not eligible for Fedai Exam ';
                        if($examstatus=='P') 
                        {
                            $msg = 'You have already passed selected exam';
                        } 
                        $ButtonDisable = false;
                        $this->session->set_flashdata('error', $msg);        
                    }
                }
                else
                {
                    $this->db->where("isactive",'1');
                    $this->db->where("regnumber",$regnumber);
                    $memberinfo = $this->master_model->getRecords('member_registration');
                    $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];
                }
            }
            else
            {
                $this->db->where("isactive",'1');
                $this->db->where("regnumber",$regnumber);
                $memberinfo = $this->master_model->getRecords('member_registration');
                $AssociateInstituteId = $memberinfo[0]['associatedinstitute'];     
            }
            
            $sel_institute_data  = [];
            if ( $AssociateInstituteId != '' || $AssociateInstituteId != null ) 
            {
                $this->db->where('fedai_institution_master.institution_delete', '0');
                $this->db->where('fedai_institution_master.institude_id', $AssociateInstituteId);
                $sel_institute_data = $this->master_model->getRecords('fedai_institution_master', '', '', array('name' => 'asc'));
                
                if ( count($sel_institute_data) < 1 ) {

                    $this->db->where('institution_master.institution_delete', '0');
                    $this->db->where('institution_master.institude_id', $AssociateInstituteId);
                    $sel_institute_data = $this->master_model->getRecords('institution_master', '', '', array('name' => 'asc'));

                    $ButtonDisable = false;
                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
                }
            }
            else
            {
                $ButtonDisable = false;
                // $this->session->set_flashdata('error', 'Member institute is not found');
                $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.');
            }   
        }

        return $ButtonDisable;
    }
    

    public function iibf_fedai_eligible_api($exam_code=0, $exam_period=0,$member_no=0)
        {
            $api_res_flag = 'error';
            $api_res_msg = '';
            
            // $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/500066883";
            
            // $api_url= "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/1009/811/".$member_no;
            
            $api_url =  "http://10.10.233.66:8093/fedaieligibleapi/getFedaiEligible/".$exam_code."/".$exam_period."/".$member_no;  //NEW API ADDED BY GAURAV ON 2024-05-27          

            $string = preg_replace('/\s+/', '+', $api_url);
            $x = curl_init($string);
            curl_setopt($x, CURLOPT_HEADER, 0);    
            curl_setopt($x, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($x, CURLOPT_RETURNTRANSFER, 1);    
            curl_setopt($x, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($x, CURLOPT_SSL_VERIFYPEER, FALSE);

            $result = curl_exec($x);      
            if(curl_errno($x)) //CURL ERROR
            {
                $api_res_msg = curl_error($x);
            }
            else
            {
                $api_res_flag = 'success';
                $api_res_msg = $result;
            }
            curl_close($x);
     
            $api_result_arr = array();
            $api_result_arr['api_res_flag']     = $api_res_flag;
            $api_result_arr['api_res_response'] = $api_res_msg;
            // print_r($api_result_arr); exit;    
            return $api_result_arr;
        }
    
    // #------------------ Set applied exam value in session for logged in user(PRAFULL)---------------##
    // public function setExamSession()
    // {
    /*$outputphoto1=$outputsign1=$photo_name=$sign_name='';
    if($this->session->userdata('examinfo'))
    {
    $this->session->unset_userdata('examinfo');
    }
    // Generate dynamic photo
    if(isset($_POST["hiddenphoto"]) && $_POST["hiddenphoto"]!='')
    {
    $input = $_POST["hiddenphoto"];
    // $tmp_nm = rand(0,100);
    $tmp_nm = 'p_'.$this->session->userdata('regnumber').'.jpg';
    $outputphoto = getcwd()."/uploads/photograph/".$tmp_nm;
    $outputphoto1 = base_url()."uploads/photograph/".$tmp_nm;
    file_put_contents($outputphoto, file_get_contents($input));
    $photo_name = $tmp_nm;
    }*/
    // generate dynamic id proof
    /*if(isset($_POST["hiddenscansignature"]) && $_POST["hiddenscansignature"]!='')
    {
    $inputsignature = $_POST["hiddenscansignature"];
    // $tmp_signnm = rand(0,100);
    $tmp_signnm = 's_'.$this->session->userdata('regnumber').'.jpg';
    $outputsign = getcwd()."/uploads/scansignature/".$tmp_signnm;
    $outputsign1 = base_url()."uploads/scansignature/".$tmp_signnm;
    file_put_contents($outputsign, file_get_contents($inputsignature));
    $sign_name = $tmp_signnm;
    }
    $user_data=array('email'=>$_POST["email"],
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
    'insdet_id'=>'',
    'selected_elect_subcode'=>$_POST['selSubcode'],
    'selected_elect_subname'=>$_POST['selSubName1'],
    // 'selSubName'=>$_POST['selSubName'],
    'placeofwork'=>$_POST['placeofwork'],
    'state_place_of_work'=>$_POST['state_place_of_work'],
    'pincode_place_of_work'=>$_POST['pincode_place_of_work'],
    'elected_exam_mode'=>$_POST['elected_exam_mode']
    );
    $this->session->set_userdata('examinfo',$user_data);
    logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));
    // redirect(base_url().'Home/preview');
    return 'true';*/
    // }

    // #------------------ Preview for applied exam,for logged in user(PRAFULL)---------------##
    public function preview()
    { 
        //Allowed member for different data
        $subject_arr1 = $this->session->userdata['examinfo']['subject_arr'];
        if (count($subject_arr1) > 0) {
            foreach ($subject_arr1 as $k => $v) {
                $flag = allowed_examdate($this->session->userdata('regnumber'), base64_decode($this->session->userdata['examinfo']['excd']), $v['date']);
                if ($flag == 1) {
                    redirect(base_url() . 'home/info');
                }
            }
        }
        $sub_flag     = 1;
        $sub_capacity = 1;
        // echo $this->session->userdata['examinfo']['selCenterName'];exit;
        if (!$this->session->userdata('examinfo')) {
            redirect(base_url() . 'home/dashboard/');
        }
        $compulsory_subjects   = array();
        $check_exam_activation = check_exam_activate(base64_decode($this->session->userdata['examinfo']['excd']));
        if ($check_exam_activation == 0) {
            //XXX : START : Step 6 : Allowing member to register for JAIIB / CAIIB after registration closed
            if (in_array($this->session->userdata('regnumber'), $this->jaiib_reschedule_arr)) {} else {redirect(base_url() . 'Home/accessdenied/');}
            //XXX : END : Step 6 : Allowing member to register for JAIIB / CAIIB after registration closed
        }

        //START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
        $decode_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
        $check_valid_exam_flag = $this->check_valid_exam_for_member($decode_exam_code);
        if($check_valid_exam_flag == 1){
            $this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile.");
            redirect(base_url() . 'Home/examlist');
        } 
        //END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023

        /* START: Check Member for Fedai Institute API */
        $ExamCode = base64_decode($this->session->userdata['examinfo']['excd']);
        
        $regnumber = $this->session->userdata('regnumber');
        $exam_period = $this->session->userdata['examinfo']['eprid'];
        if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
        {
            $ButtonDisable = $this->check_fedai_member($ExamCode,$exam_period,$regnumber);
            if (!$ButtonDisable) {
                $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.'); 
                redirect(base_url() . 'Home/comApplication/');    
            }
        }
        /* END: Check Member for Fedai Institute API */

        /*if($decode_exam_code == '1031'){  
            $examinfo = $this->session->userdata('examinfo');
            //print_r($examinfo['eprid']);
            $set_client_ip_address = $this->config->item('set_client_ip_address');
            $get_ip_address = '';
            $get_ip_address = get_ip_address();
            //print_r($this->session->userdata);
            if($examinfo['eprid'] == '323')
            {  
                if(!in_array($get_ip_address,$set_client_ip_address)){
                    $this->session->set_flashdata('error_invalide_exam_selection', "Your are not allowed to apply this exam.");
                    redirect(base_url() . 'Home/examlist');
                }
            } 
        }*/

        // ###########check capacity is full or not ##########
        $subject_arr = $this->session->userdata['examinfo']['subject_arr'];
        if (count($subject_arr) > 0) {
            $msg          = '';
            $sub_flag     = 1;
            $sub_capacity = 1;
            foreach ($subject_arr as $k => $v) {
                foreach ($subject_arr as $j => $val) {
                    if ($k != $j) {
                        // if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                        if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                            $sub_flag = 0;
                        }
                    }
                }
                $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
                if ($capacity <= 1) {
                    $total_admit_count = getLastseat(base64_decode($this->session->userdata['examinfo']['excd']), $this->session->userdata['examinfo']['selCenterName'], $v['venue'], $v['date'], $v['session_time']);
                    if ($total_admit_count > 0) {
                        $msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
                        $msg = $msg . ' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
                    }
                }
                if ($msg != '') {
                    $this->session->set_flashdata('error', $msg);
                    redirect(base_url() . 'Home/comApplication');
                }
            }
        }
        if ($sub_flag == 0) {
            $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
            redirect(base_url() . 'Home/comApplication');
        }
        $cookieflag = 1;
        // $this->chk_session->checkphoto();
        // ask user to wait for 5 min, until the payment transaction process complete by (PRAFULL)
        $valcookie = $this->session->userdata('regnumber');
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
        // check for valid fee
        if ($this->session->userdata['examinfo']['fee'] == 0 || $this->session->userdata['examinfo']['fee'] == '') {
            $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
            redirect(base_url() . 'Home/comApplication/');
        }

        //START : ADDED BY SAGAR M ON 2024-09-12        
        $exam_date_arr = $subject_arr_for_examdate = array();
        $subject_arr_for_examdate = $this->session->userdata['examinfo']['subject_arr'];
        if(count($subject_arr_for_examdate) > 0){
            foreach ($subject_arr_for_examdate as $k => $v) 
            {
              $exam_date_arr[] = $v['date']; 
            }
        }
        //END : ADDED BY SAGAR M 2024-09-12

        $check = $this->examapplied($this->session->userdata('regnumber'), $this->session->userdata['examinfo']['excd'], $exam_date_arr);
        if (!$check) {
            // get medium
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=medium_master.exam_code AND exam_activation_master.exam_period=medium_master.exam_period');
            $this->db->where('medium_master.exam_code', base64_decode($this->session->userdata['examinfo']['excd']));
            $this->db->where('medium_delete', '0');
            $medium = $this->master_model->getRecords('medium_master');
            // get center
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
            $this->db->where('exam_name', base64_decode($this->session->userdata['examinfo']['excd']));
            $this->db->where('center_code', $this->session->userdata['examinfo']['selCenterName']);
            $center = $this->master_model->getRecords('center_master', '', 'center_name', array(
                'center_name' => 'ASC',
            ));
            // echo $this->db->last_query();exit;
            $user_info = $this->master_model->getRecords('member_registration', array(
                'regid'     => $this->session->userdata('regid'),
                'regnumber' => $this->session->userdata('regnumber'),
            ));
            if (count($user_info) <= 0) {
                redirect(base_url());
            }
            $this->db->where('state_delete', '0');
            $states = $this->master_model->getRecords('state_master');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
            $misc = $this->master_model->getRecords('misc_master', array(
                'misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']),
                'misc_delete'           => '0',
            ));
            if ($cookieflag == 0) {
                $data = array(
                    'middle_content' => 'exam_apply_cms_msg',
                );
            } else {
                $disability_value      = $this->master_model->getRecords('scribe_disability', array('is_delete' => 0));
                $scribe_sub_disability = $this->master_model->getRecords('scribe_sub_disability', array('is_delete' => 0));

                // benchmark disability
                $benchmark_disability_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'benchmark_disability,visually_impaired, vis_imp_cert_img,orthopedically_handicapped,orth_han_cert_img,cerebral_palsy,cer_palsy_cert_img');

                $data = array(
                    'middle_content'            => 'exam_preview',
                    'user_info'                 => $user_info,
                    'medium'                    => $medium,
                    'center'                    => $center,
                    'misc'                      => $misc,
                    'states'                    => $states,
                    'disability_value'          => $disability_value,
                    'scribe_sub_disability'     => $scribe_sub_disability,
                    'compulsory_subjects'       => $this->session->userdata['examinfo']['subject_arr'],
                    'benchmark_disability_info' => $benchmark_disability_info,
                );
            }
            $this->load->view('common_view', $data);
        } else {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=misc_master.exam_code AND exam_activation_master.exam_period=misc_master.exam_period');
            $get_period_info = $this->master_model->getRecords('misc_master', array(
                'misc_master.exam_code'   => base64_decode($this->session->userdata['examinfo']['excd']),
                'misc_master.misc_delete' => '0',
            ), 'exam_month');
            // $month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
            $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4); 
            $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
            $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.... period. Hence you need not apply for the same.';
            $data             = array(
                'middle_content'    => 'already_apply',
                'check_eligibility' => $message,
            );
            $this->load->view('common_view', $data);
        }
    }

    public function info()
    {
        $message = $this->get_alredy_applied_examname($this->session->userdata('regnumber'), $this->session->userdata['examinfo']['excd']);
        $data    = array(
            'middle_content'    => 'not_eligible',
            'check_eligibility' => $message,
        );
        $this->load->view('common_view', $data);
    }

    // #------------------Insert data in member_exam table for applied exam,for logged in user With Payment using Billdesk Gate-way(PRAFULL)---------------##
    public function Msuccess()
    {
        // $this->chk_session->checkphoto();
        $photoname = $singname = '';
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }

        // CHECK DUPLICATE EXAM DATE APPLICATION CODE ADDED BY ANIL 27 SEP 2024
        if($this->session->userdata('examinfo')){
            $last_query = $current_url_with_query = '';
            $exam_date_arr = $subject_arr_for_examdate = array();
            $subject_arr_for_examdate = isset($this->session->userdata['examinfo']['subject_arr']) ? $this->session->userdata['examinfo']['subject_arr'] : array();
            if(count($subject_arr_for_examdate) > 0){
                foreach ($subject_arr_for_examdate as $k => $v) 
                {
                  $exam_date_arr[] = $v['date']; 
                }
            }  
            if(count($exam_date_arr) > 0){

                $this->db->where(" ( ((member_exam.institute_id IS NULL OR member_exam.institute_id = '' OR member_exam.institute_id = '0') AND admit_card_details.remark = '1') OR (member_exam.institute_id IS NOT NULL AND member_exam.institute_id != '' AND member_exam.institute_id != '0') ) "); 
                $this->db->where('member_exam.bulk_isdelete', '0');
                $this->db->where_in('admit_card_details.exam_date', $exam_date_arr);

                $this->db->join('member_exam', 'member_exam.id = admit_card_details.mem_exam_id', 'inner');

                $applied_exam_info = $this->master_model->getRecords('admit_card_details', array('admit_card_details.mem_mem_no' => $this->session->userdata('regnumber')), 'admit_card_details.exm_cd,admit_card_details.exm_prd,admit_card_details.remark,admit_card_details.mem_mem_no,admit_card_details.exam_date,admit_card_details.time,admit_card_details.created_on,admit_card_details.modified_on,admit_card_details.record_source,member_exam.institute_id,member_exam.bulk_isdelete');
                //echo $this->db->last_query();die;
                    
                $admit_card_details_data = isset($applied_exam_info) && count($applied_exam_info) > 0 ? $applied_exam_info : array();
                $session_data['user_all_data'] = isset($this->session->userdata) ? $this->session->userdata : array();

                $last_query = $this->db->last_query(); 
                $current_url_with_query = current_url(true); //echo $current_url_with_query;

                $insert_dup_application_data['page_title'] = "Controller: ".$this->router->fetch_class()." & Function: ".$this->router->fetch_method();
                $insert_dup_application_data['url'] = $current_url_with_query;
                $insert_dup_application_data['description'] = $last_query;
                $insert_dup_application_data['member_no'] = $this->session->userdata('regnumber');
                $insert_dup_application_data['exam_code'] = base64_decode($this->session->userdata['examinfo']['excd']);
                $insert_dup_application_data['exam_period'] = $this->session->userdata['examinfo']['eprid'];
                $insert_dup_application_data['session_data'] = serialize($session_data);
                $insert_dup_application_data['admit_card_details_data'] = serialize($admit_card_details_data);
               
                $inser_id = $this->master_model->insertRecord('check_dup_application_data', $insert_dup_application_data, true);

                if(isset($applied_exam_info) && count($applied_exam_info) > 0){
                    if(isset($inser_id) && $inser_id > 0){
                        $this->master_model->updateRecord('check_dup_application_data', array('duplicate_application' => 1), array('id' => $inser_id));
                    }  
                    redirect(base_url() . 'Home/accessdenied_already_apply');
                }
            } 
        }
        // CHECK DUPLICATE EXAM DATE APPLICATION CODE ADDED BY ANIL 27 SEP 2024

        if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
            if (isset($this->session->userdata['examinfo']['el_subject'][0]) && $this->session->userdata['examinfo']['el_subject'][0] == 'N' && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeJaiib') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeDBF') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeSOB') && base64_decode($this->session->userdata['examinfo']['excd']) != $this->config->item('examCodeCaiib') && base64_decode($this->session->userdata['examinfo']['excd']) != 65) {
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

            if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {

                $el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                $total_elearning_amt = $el_amount * $el_subject_cnt;
                $amount              = $amount + $total_elearning_amt;

            }

            /* START: Check Member for Fedai Institute API */
            $ExamCode    = base64_decode($this->session->userdata['examinfo']['excd']);
            $regnumber   = $this->session->userdata('regnumber');
            $exam_period = $this->session->userdata['examinfo']['eprid'];
            if ($ExamCode != '' && $ExamCode == 1009 && $regnumber != '' && $exam_period != '')
            {
                $ButtonDisable = $this->check_fedai_member($ExamCode,$exam_period,$regnumber);
                if (!$ButtonDisable) {
                    $this->session->set_flashdata('error', 'Your employer organization is Not a Member of FEDAI, hence you are not eligible to apply for this exam.'); 
                    redirect(base_url() . 'Home/comApplication/');    
                }
            }
            /* END: Check Member for Fedai Institute API */

            	//priyanka d =22-feb-23
				$optFlg	=	'N';
				if(isset($this->session->userdata['examinfo']['optFlg']))
					$optFlg	=	$this->session->userdata['examinfo']['optFlg'];

            $inser_array = array(
                'regnumber'              => $this->session->userdata('regnumber'),
                'exam_code'              => base64_decode($this->session->userdata['examinfo']['excd']),
                'exam_mode'              => $this->session->userdata['examinfo']['optmode'],
                'exam_medium'            => $this->session->userdata['examinfo']['medium'],
                'exam_period'            => $this->session->userdata['examinfo']['eprid'],
                'exam_center_code'       => $this->session->userdata['examinfo']['txtCenterCode'],
                'exam_fee'               => $amount,
                'elected_sub_code'       => $this->session->userdata['examinfo']['selected_elect_subcode'],
                'place_of_work'          => $this->session->userdata['examinfo']['placeofwork'],
                'state_place_of_work'    => $this->session->userdata['examinfo']['state_place_of_work'],
                'pin_code_place_of_work' => $this->session->userdata['examinfo']['pincode_place_of_work'],
                'scribe_flag'            => $this->session->userdata['examinfo']['scribe_flag'],
                'scribe_flag_PwBD'       => $this->session->userdata['examinfo']['scribe_flag_d'],
                'disability'             => $this->session->userdata['examinfo']['disability_value'],
                'sub_disability'         => $this->session->userdata['examinfo']['Sub_menue_disability'],
                'created_on'             => date('y-m-d H:i:s'),
                'elearning_flag'         => $this->session->userdata['examinfo']['elearning_flag'],
                // 'institute_id'             => $this->session->userdata['examinfo']['selinstitute'],
                'sub_el_count'           => $el_subject_cnt,
                'optFlg'                 => $optFlg // priyanka d - 22-feb-23
            );
            if ($inser_id = $this->master_model->insertRecord('member_exam', $inser_array, true)) {
                
                $regnumber = $this->session->userdata('regnumber');
                $AssociateInstituteId = $this->session->userdata['examinfo']['selinstitute'];
            
                $this->master_model->updateRecord('member_registration', array('associatedinstitute' => $AssociateInstituteId), array('regnumber' => $regnumber));

                /* User Log Activities : Bhushan */
                $log_title   = "Member exam apply details - Insert Home.php";
                $log_message = serialize($inser_array);
                $rId         = $this->session->userdata('regid');
                $regNo       = $this->session->userdata('mregnumber_applyexam');
                storedUserActivity($log_title, $log_message, $rId, $regNo);
                /* Close User Log Actitives */

                // echo $this->session->userdata['examinfo']['fee'];
                $this->session->userdata['examinfo']['insdet_id'] = $inser_id;
                $update_array                                     = array();
                // update an array for images
                if ($this->session->userdata['examinfo']['photo'] != '') {
                    $update_array = array_merge($update_array, array(
                        "scannedphoto" => $this->session->userdata['examinfo']['photo'],
                    ));
                    $photo_name = $this->master_model->getRecords('member_registration', array(
                        'regid'     => $this->session->userdata('regid'),
                        'regnumber' => $this->session->userdata('regnumber'),
                    ), 'scannedphoto');
                    $photoname = $photo_name[0]['scannedphoto'];
                }
                if ($this->session->userdata['examinfo']['signname'] != '') {
                    $update_array = array_merge($update_array, array(
                        "scannedsignaturephoto" => $this->session->userdata['examinfo']['signname'],
                    ));
                    $sing_name = $this->master_model->getRecords('member_registration', array(
                        'regid'     => $this->session->userdata('regid'),
                        'regnumber' => $this->session->userdata('regnumber'),
                    ), 'scannedsignaturephoto');
                    $singname = $sing_name[0]['scannedsignaturephoto'];
                }
                // check if email is unique
                $check_email = $this->master_model->getRecordCount('member_registration', array(
                    'email'            => $this->session->userdata['examinfo']['email'],
                    'isactive'         => '1',
                    'registrationtype' => $this->session->userdata('memtype'),
                ));
                if ($check_email == 0) {
                    $update_array = array_merge($update_array, array(
                        "email" => $this->session->userdata['examinfo']['email'],
                    ));
                }
                // check if mobile is unique
                $check_mobile = $this->master_model->getRecordCount('member_registration', array(
                    'mobile'           => $this->session->userdata['examinfo']['mobile'],
                    'isactive'         => '1',
                    'registrationtype' => $this->session->userdata('memtype'),
                ));
                if ($check_mobile == 0) {
                    $update_array = array_merge($update_array, array(
                        "mobile" => $this->session->userdata['examinfo']['mobile'],
                    ));
                }

                $sess_exam_code = base64_decode($this->session->userdata['examinfo']['excd']);
                if($sess_exam_code == 101 || $sess_exam_code == 1046 || $sess_exam_code == 1047){
                    $update_array = array_merge($update_array, array("ippb_emp_id" => $this->session->userdata['examinfo']['ippb_emp_id']));
                    $update_array = array_merge($update_array, array("name_of_bank_bc" => $this->session->userdata['examinfo']['name_of_bank_bc']));
                    $update_array = array_merge($update_array, array("date_of_commenc_bc" => $this->session->userdata['examinfo']['date_of_commenc_bc'])); 
                    if ($this->session->userdata['examinfo']['bank_bc_id_card_filename'] != '') {
                        $update_array = array_merge($update_array, array("bank_bc_id_card" => $this->session->userdata['examinfo']['bank_bc_id_card_filename']));
                        $photo_name   = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('nmregid'), 'regnumber' => $this->session->userdata('nmregnumber')), 'bank_bc_id_card');
                        $bank_bc_id_card_filename = $photo_name[0]['bank_bc_id_card'];
                    }   
                }
                    
                if (count($update_array) > 0) {
                    $update_array['editedon'] = date('Y-m-d H:i:s');
                    $update_array['editedby'] = "Candidate";
                    $this->master_model->updateRecord('member_registration', $update_array, array(
                        'regid'     => $this->session->userdata('regid'),
                        'regnumber' => $this->session->userdata('regnumber'),
                    ));
                    // @unlink('uploads/photograph/'.$photoname);
                    // @unlink('uploads/scansignature/'.$singname);
                    // logactivity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
                    /* User Log Activities : Bhushan */
                    $log_title   = "Member update profile during exam apply";
                    $log_message = serialize($update_array);
                    $rId         = $this->session->userdata('regid');
                    $regNo       = $this->session->userdata('regnumber');
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    /* Close User Log Actitives */
                }
                if ($this->config->item('exam_apply_gateway') == 'sbi') {
                    redirect(base_url() . 'Home/sbi_make_payment/');
                } else {
                    redirect(base_url() . 'Payment/make_payment/');
                }
            }
        } else {
            redirect(base_url() . 'Home/dashboard/');
        }
    }


    // #------------------Exam appky with SBI Payment Gate-way(PRAFULL)---------------##
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
        $valcookie            = applyexam_get_cookie();
        if ($valcookie) {
            redirect(base_url(), 'Home/dashboard/');
        }

        if (isset($_POST['processPayment']) && $_POST['processPayment']) {
            $pg_name = 'billdesk';
            //checked for application in payment process and prevent user to apply exam on the same time(Prafull)
            $checkpayment = $this->master_model->getRecords('payment_transaction', array('member_regnumber' => $this->session->userdata('regnumber'), 'status' => '2', 'pay_type' => '2'), '', array('id' => 'DESC'));
            if (count($checkpayment) > 0) {
                $endTime      = date("Y-m-d H:i:s", strtotime("+120 minutes", strtotime($checkpayment[0]['date'])));
                $current_time = date("Y-m-d H:i:s");
                if (strtotime($current_time) <= strtotime($endTime)) {
                    $this->session->set_flashdata('error', 'Your transactions is in process, please try after 2 hrs after your initial transaction.');
                    redirect(base_url() . 'Home/comApplication');
                }
            }

            $sub_flag = 1;
            // ###########check capacity is full or not
            $subject_arr = $this->session->userdata['examinfo']['subject_arr'];
            if (count($subject_arr) > 0) {
                $msg          = '';
                $sub_capacity = 1;
                foreach ($subject_arr as $k => $v) {
                    foreach ($subject_arr as $j => $val) {
                        if ($k != $j) {
                            // if($v['venue']==$val['venue'] && $v['date']==$val['date'] && $v['session_time']==$val['session_time'])
                            if ($v['date'] == $val['date'] && $v['session_time'] == $val['session_time']) {
                                $sub_flag = 0;
                            }
                        }
                    }
                    $capacity = get_capacity($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
                    if ($capacity <= 1) {
                        $total_admit_count = getLastseat(base64_decode($this->session->userdata['examinfo']['excd']), $this->session->userdata['examinfo']['selCenterName'], $v['venue'], $v['date'], $v['session_time']);
                        if ($total_admit_count > 0) {
                            $msg = getVenueDetails($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);
                            $msg = $msg . ' or there is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
                        }
                    }
                    if ($msg != '') {
                        $this->session->set_flashdata('error', $msg);
                        redirect(base_url() . 'Home/comApplication');
                    }
                }
            }

            if ($sub_flag == 0) {
                $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                redirect(base_url() . 'Home/comApplication');
            }

            $regno = $this->session->userdata('regnumber');

            if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
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
                $amount = getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
                // $amount=$this->session->userdata['examinfo']['fee'];
                
                if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
                    $el_amount = get_el_ExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);

                    $total_elearning_amt = $el_amount * $el_subject_cnt;
                    $amount              = $amount + $total_elearning_amt;
                    ## New elarning columns code
                    $total_el_base_amount = $el_subject_cnt;
                    $total_el_cgst_amount = $el_subject_cnt;
                    $total_el_sgst_amount = $el_subject_cnt;
                    $total_el_igst_amount = $el_subject_cnt;
                }
            }

            if ($amount == 0 || $amount == '') {
                $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');
              //  echo'here';exit;
                redirect(base_url() . 'Home/comApplication/');
            }

            // $MerchantOrderNo    = generate_order_id("sbi_exam_order_id");
            // Ordinary member Apply exam
            //    Ref1 = orderid
            //    Ref2 = iibfexam
            //    Ref3 = member reg num
            //    Ref4 = exam_code + exam year + exam month ex (101201602)
            $yearmonth = $this->master_model->getRecords('misc_master', array(
                'exam_code'   => base64_decode($this->session->userdata['examinfo']['excd']),
                'exam_period' => $this->session->userdata['examinfo']['eprid'],
            ), 'exam_month');
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
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 590) {
                $exam_code = 59;
            } elseif (base64_decode($this->session->userdata['examinfo']['excd']) == 810) {
                $exam_code = 81;
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
                'ref_id'           => $this->session->userdata['examinfo']['insdet_id'],
                'description'      => $this->session->userdata['examinfo']['exname'],
                'status'           => '2',
                'exam_code'        => base64_decode($this->session->userdata['examinfo']['excd']),
                // 'receipt_no'       => $MerchantOrderNo,
                'pg_flag'          => "IIBF_EXAM_O",
                // 'pg_other_details'=>$custom_field
            );
            $pt_id           = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            $MerchantOrderNo = sbi_exam_order_id($pt_id);
            // payment gateway custom fields -
            $custom_field = $MerchantOrderNo . "^iibfexam^" . $this->session->userdata('regnumber') . "^" . $ref4;

            $custom_field_billdesk = $MerchantOrderNo . "-iibfexam-" . $this->session->userdata('regnumber') . "-" . $ref4;
          //  echo $custom_field_billdesk;exit;
            // update receipt no. in payment transaction -
            $update_data = array(
                'receipt_no'       => $MerchantOrderNo,
                'pg_other_details' => $custom_field,
            );
            
            $this->master_model->updateRecord('payment_transaction', $update_data, array(
                'id' => $pt_id,
            ));
            // set invoice details(Prafull)
            $getcenter = $this->master_model->getRecords('center_master', array(
                'exam_name'     => base64_decode($this->session->userdata['examinfo']['excd']),
                'center_code'   => $this->session->userdata['examinfo']['txtCenterCode'],
                'exam_period'   => $this->session->userdata['examinfo']['eprid'],
                'center_delete' => '0',
            ));
            if (count($getcenter) > 0) {
                // get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array(
                    'state_code'   => $getcenter[0]['state_code'],
                    'state_delete' => '0',
                ));
                // call to helper (fee_helper)
                $getfees = getExamFeedetails($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag']);
            }
            if ($getcenter[0]['state_code'] == 'MAH') {
                // set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('cgst_rate');
                $sgst_rate = $this->config->item('sgst_rate');
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {
                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
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
            } else {
                if ($this->session->userdata['examinfo']['elearning_flag'] == 'Y') {

                    $igst_rate = $this->config->item('igst_rate');

                    if (isset($this->session->userdata['examinfo']['el_subject']) && count($this->session->userdata['examinfo']['el_subject']) > 0 && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {
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
                    $igst_rate   = $this->config->item('igst_rate');
                    $igst_amt    = $getfees[0]['igst_amt'];
                    $igst_total  = $getfees[0]['igst_tot'];
                    $amount_base = $getfees[0]['fee_amount'];
                    ## Code added on 22 Oct 2021 - chaitali
                    $cgst_rate            = $cgst_amt            = $sgst_rate            = $sgst_amt            = $cs_total            = '';
                    $total_el_base_amount = 0;
                    $total_el_gst_amount  = 0;
                }
                $tax_type = 'Inter';
            }
            if ($getstate[0]['exempt'] == 'E') {
                $cgst_rate = $sgst_rate = $igst_rate = '';
                $cgst_amt  = $sgst_amt  = $igst_amt  = '';
            }
            $gst_no = '0';
            /*if($this->session->userdata['examinfo']['gstin_no']!='')
            {
            $gst_no=$this->session->userdata['examinfo']['gstin_no'];
            }*/
            ## Code added on 22 Oct 2021    - chaitali
            $fee_details = array('state' => $getcenter[0]['state_code'], 'fee_amt' => $amount_base,
                'total_el_amount'            => $total_el_amount,
                'cgst_rate'                  => $cgst_rate,
                'cgst_amt'                   => $cgst_amt,
                'sgst_rate'                  => $sgst_rate,
                'sgst_amt'                   => $sgst_amt,
                'igst_rate'                  => $igst_rate,
                'igst_amt'                   => $igst_amt,
                'cs_total'                   => $cs_total,
                'igst_total'                 => $igst_total);
            $log_title   = "Exam invoice data from home cntrlr before insert array";
            $log_message = serialize($fee_details);
            $rId         = $this->session->userdata('regnumber');
            $regNo       = $this->session->userdata('regnumber');
            storedUserActivity($log_title, $log_message, $rId, $regNo);

            $invoice_insert_array = array(
                'pay_txn_id'           => $pt_id,
                'receipt_no'           => $MerchantOrderNo,
                'exam_code'            => base64_decode($this->session->userdata['examinfo']['excd']),
                'center_code'          => $getcenter[0]['center_code'],
                'center_name'          => $getcenter[0]['center_name'],
                'state_of_center'      => $getcenter[0]['state_code'],
                'member_no'            => $this->session->userdata('regnumber'),
                'app_type'             => 'O',
                'exam_period'          => $this->session->userdata['examinfo']['eprid'],
                'service_code'         => $this->config->item('exam_service_code'),
                'qty'                  => '1',
                'state_code'           => $getstate[0]['state_no'],
                'state_name'           => $getstate[0]['state_name'],
                'tax_type'             => $tax_type,
                'fee_amt'              => $amount_base,
                'total_el_amount'      => $total_el_amount,
                'total_el_base_amount' => $total_el_base_amount,
                'total_el_gst_amount'  => $total_el_gst_amount,
                'cgst_rate'            => $cgst_rate,
                'cgst_amt'             => $cgst_amt,
                'sgst_rate'            => $sgst_rate,
                'sgst_amt'             => $sgst_amt,
                'igst_rate'            => $igst_rate,
                'igst_amt'             => $igst_amt,
                'cs_total'             => $cs_total,
                'igst_total'           => $igst_total,
                'exempt'               => $getstate[0]['exempt'],
                'created_on'           => date('Y-m-d H:i:s'),
            );
            $inser_id    = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array, true);
            $log_title   = "Exam invoice data from home cntrlr last id inser_id = '" . $inser_id . "'";
            $log_message = serialize($invoice_insert_array);
            $rId         = $this->session->userdata('regnumber');
            $regNo       = $this->session->userdata('regnumber');
            storedUserActivity($log_title, $log_message, $rId, $regNo);
            // insert into admit card table
            // ################get userdata###########
            $user_info = $this->master_model->getRecords('member_registration', array(
                'regnumber' => $this->session->userdata('regnumber'),
                'isactive'  => '1',
            ));
            // get associate institute details
            $institute_id     = '';
            $institution_name = '';
            if ($user_info[0]['associatedinstitute'] != '') {
                $institution_master = $this->master_model->getRecords('institution_master', array(
                    'institude_id' => $user_info[0]['associatedinstitute'],
                ));
                if (count($institution_master) > 0) {
                    $institute_id     = $institution_master[0]['institude_id'];
                    $institution_name = $institution_master[0]['name'];
                }
            }
            // ############check Gender########
            if ($user_info[0]['gender'] == 'male') {
                $gender = 'M';
            } else {
                $gender = 'F';
            }
            // ########prepare user name########
           // $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
            // priyanka -d -08-feb-23 >> commented this
           $username        =   $user_info[0]['displayname']; // priyanka -d -08-feb-23 >> on admitcard it was not showing full name so changed it as asked by tester
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            // ###########get State##########
            $states = $this->master_model->getRecords('state_master', array(
                'state_code'   => $user_info[0]['state'],
                'state_delete' => '0',
            ));
            $state_name = '';
            if (count($states) > 0) {
                $state_name = $states[0]['state_name'];
            }
            // ##############Examination Mode###########
            if ($this->session->userdata['examinfo']['optmode'] == 'ON') {
                $mode = 'Online';
            } else {
                $mode = 'Offline';
            }

            $sub_el_flg = 'N';
         //   echo'<pre>';print_r($this->session->userdata['examinfo']['subject_arr']);exit;
            if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                    /*    $seat_count=$CI->master_model->getRecords('venue_master',array('exam_date'=>$sel_date,'venue_code'=>$sel_venue,'session_time'=>$sel_time,'center_code'=>$sel_center),'session_capacity');*/
                    $this->db->group_by('subject_code');
                    $compulsory_subjects = $this->master_model->getRecords('subject_master', array(
                        'exam_code'      => base64_decode($this->session->userdata['examinfo']['excd']),
                        'subject_delete' => '0',
                        'exam_period'    => $this->session->userdata['examinfo']['eprid'],
                        'subject_code'   => $k,
                    ), 'subject_description');
                    $get_subject_details = $this->master_model->getRecords('venue_master', array(
                        'venue_code'   => $v['venue'],
                        'exam_date'    => $v['date'],
                        'session_time' => $v['session_time'],
                        'center_code'  => $this->session->userdata['examinfo']['selCenterName']));

                    if (isset($this->session->userdata['examinfo']['el_subject']) && (base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeJaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeDBF') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeSOB') || base64_decode($this->session->userdata['examinfo']['excd']) == $this->config->item('examCodeCaiib') || base64_decode($this->session->userdata['examinfo']['excd']) == 65)) {

                        if ($this->session->userdata['examinfo']['el_subject'] != 'N') {
                            if (array_key_exists($k, $this->session->userdata['examinfo']['el_subject'])) {
                                $sub_el_flg = 'Y';
                            } else {
                                $sub_el_flg = 'N';
                            }
                        }

                    }
                    $check_last_seat_available = preventUser(base64_decode($this->session->userdata['examinfo']['excd']), $this->session->userdata['examinfo']['selCenterName'], $v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['eprid']);
                    if ($check_last_seat_available <= 0) {
                        $msg = 'There is only one seat left for the selected venue which is under the process of allocation to a candidate, You can reapply only if this seat becomes available, Please try after sometime.';
                        $this->session->set_flashdata('error', $msg);
                        redirect(base_url() . 'Home/comApplication');
                    }

                    $admitcard_insert_array = array(
                        'mem_exam_id'      => $this->session->userdata['examinfo']['insdet_id'],
                        'center_code'      => $getcenter[0]['center_code'],
                        'center_name'      => $getcenter[0]['center_name'],
                        'mem_type'         => $this->session->userdata('memtype'),
                        'mem_mem_no'       => $this->session->userdata('regnumber'),
                        'g_1'              => $gender,
                        'mam_nam_1'        => $userfinalstrname,
                        'mem_adr_1'        => $user_info[0]['address1'],
                        'mem_adr_2'        => $user_info[0]['address2'],
                        'mem_adr_3'        => $user_info[0]['address3'],
                        'mem_adr_4'        => $user_info[0]['address4'],
                        'mem_adr_5'        => $user_info[0]['district'],
                        'mem_adr_6'        => $user_info[0]['city'],
                        'mem_pin_cd'       => $user_info[0]['pincode'],
                        'state'            => $state_name,
                        'exm_cd'           => base64_decode($this->session->userdata['examinfo']['excd']),
                        'exm_prd'          => $this->session->userdata['examinfo']['eprid'],
                        'sub_cd '          => $k,
                        'sub_dsc'          => $compulsory_subjects[0]['subject_description'],
                        'sub_el_flg'       => $sub_el_flg,
                        'm_1'              => $this->session->userdata['examinfo']['medium'],
                        'inscd'            => $institute_id,
                        'insname'          => $institution_name,
                        'venueid'          => $get_subject_details[0]['venue_code'],
                        'venue_name'       => $get_subject_details[0]['venue_name'],
                        'venueadd1'        => $get_subject_details[0]['venue_addr1'],
                        'venueadd2'        => $get_subject_details[0]['venue_addr2'],
                        'venueadd3'        => $get_subject_details[0]['venue_addr3'],
                        'venueadd4'        => $get_subject_details[0]['venue_addr4'],
                        'venueadd5'        => $get_subject_details[0]['venue_addr5'],
                        'venpin'           => $get_subject_details[0]['venue_pincode'],
                        'exam_date'        => $get_subject_details[0]['exam_date'],
                        'time'             => $get_subject_details[0]['session_time'],
                        'mode'             => $mode,
                        'scribe_flag'      => $this->session->userdata['examinfo']['scribe_flag'],
                        'scribe_flag_PwBD' => $this->session->userdata['examinfo']['scribe_flag_d'],
                        'disability'       => $this->session->userdata['examinfo']['disability_value'],
                        'sub_disability'   => $this->session->userdata['examinfo']['Sub_menue_disability'],
                        'vendor_code'      => $get_subject_details[0]['vendor_code'],
                        'remark'           => 2,
                        'created_on'       => date('Y-m-d H:i:s'),
                    );
                    $inser_id    = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);
                    $log_title   = "Admit card data from Home cntrlr";
                    $log_message = serialize($admitcard_insert_array);
                    $rId         = $this->session->userdata('regnumber');
                    $regNo       = $this->session->userdata('regnumber');
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                }

                ##code added to verify if master tables has the raw entries - 2021-10-22 - by chaitali
                $marchant_id = $MerchantOrderNo;
                $exam_code   = base64_decode($this->session->userdata['examinfo']['excd']);
                $member_no   = $this->session->userdata('regnumber');
                $ref_id      = $this->session->userdata['examinfo']['insdet_id'];

                $payment_raw = $this->master_model->getRecordCount('payment_transaction', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_regnumber' => $member_no));

                $exam_invoice_raw = $this->master_model->getRecordCount('exam_invoice', array('receipt_no' => $marchant_id, 'exam_code' => $exam_code, 'member_no' => $member_no));

                $admit_card_raw = $this->master_model->getRecordCount('admit_card_details', array('mem_exam_id' => $ref_id, 'exm_cd' => $exam_code, 'mem_mem_no' => $member_no));

                if ($payment_raw == 0 || $exam_invoice_raw == 0 || $admit_card_raw == 0) {
                    $this->session->set_flashdata('error', 'Something went wrong!!');
                    redirect(base_url() . 'Home/comApplication');
                }

                ############check for missing subject############
                $this->db->where('app_category !=', 'R');
                $this->db->where('app_category !=', '');
                $this->db->where('exam_status !=', 'V');
                $this->db->where('exam_status !=', 'P');
                $this->db->where('exam_status !=', 'D');
                /*$check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'member_no' => $this->session->userdata('regnumber'), 'eligible_period' => $this->session->userdata['examinfo']['eprid'], 'institute_id' => '0'));*/
                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'member_no' => $this->session->userdata('regnumber'), 'eligible_period' => $this->session->userdata['examinfo']['eprid']));

                $treatAsFresher=0; //priyanka d- 24-01-23
                if (count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category'] == 'R')
						$treatAsFresher=1;
					else if(isset($this->session->userdata['examinfo']['optval']) && $this->session->userdata['examinfo']['optval']==1)
						$treatAsFresher=1;

                if ($treatAsFresher==1) {//priyanka d- 24-01-23

               // if (count($check_eligibility_for_applied_exam) <= 0 || $check_eligibility_for_applied_exam[0]['app_category'] == 'R') {
                    if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                        $count = 0;
                        foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                            $check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata('regnumber'), 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
                            if (count($check_admit_card_details) > 0) {
                                $count++;
                            }
                        }
                    }
                    if (count($this->session->userdata['examinfo']['subject_arr']) != $count) {
                        $log_title   = "Fresh Member subject missing Home cntrlr";
                        $log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
                        $rId         = $this->session->userdata('regnumber');
                        $regNo       = $this->session->userdata('regnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        delete_cookie('examid');
                        $this->session->set_flashdata('error', 'Something went wrong!!');
                        redirect(base_url() . 'Home/comApplication');
                    }
                } else {
                    $count = 0;
                    if (count($check_eligibility_for_applied_exam) == count($this->session->userdata['examinfo']['subject_arr'])) {
                        if (!empty($this->session->userdata['examinfo']['subject_arr'])) {
                            foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {
                                $check_admit_card_details = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata('regnumber'), 'exm_cd' => base64_decode($this->session->userdata['examinfo']['excd']), 'sub_cd' => $k, 'venueid' => $v['venue'], 'exam_date' => $v['date'], 'time' => $v['session_time'], 'center_code' => $this->session->userdata['examinfo']['selCenterName']));
                                if (count($check_admit_card_details) > 0) {
                                    $count++;
                                }
                            }
                        }
                    }
                    if (count($check_eligibility_for_applied_exam) != $count) {
                        $log_title   = "Existing Member subject missing  Home cntrlr";
                        $log_message = serialize($this->session->userdata['examinfo']['subject_arr']);
                        $rId         = $this->session->userdata('regnumber');
                        $regNo       = $this->session->userdata('regnumber');
                        storedUserActivity($log_title, $log_message, $rId, $regNo);
                        delete_cookie('examid');
                        $this->session->set_flashdata('error', 'Something went wrong!!');
                        redirect(base_url() . 'Home/comApplication');
                    }
                }
                ############END check for missing subject############
            } else  { // exemption
                if (base64_decode($this->session->userdata['examinfo']['excd']) != 101 && base64_decode($this->session->userdata['examinfo']['excd']) != 1046 && base64_decode($this->session->userdata['examinfo']['excd']) != 1047) {
                    $this->session->set_flashdata('Error', 'Something went wrong!!');
                    redirect(base_url() . 'Home/comApplication');
                }
            }
            // set cookie for Apply Exam
            applyexam_set_cookie($this->session->userdata['examinfo']['insdet_id']);
            $MerchantCustomerID  = $regno;
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
            if ($pg_name == 'sbi') {
                exit();
                include APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "Home/sbitranssuccess";
                $pg_fail_url    = base_url() . "Home/sbitransfail";
                $EncryptTrans   = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                $aes            = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $EncryptTrans         = $aes->encrypt($EncryptTrans);
                $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
                $this->load->view('pg_sbi_form', $data);
            } elseif ($pg_name == 'billdesk') {

                $callback_link = 'home/handle_billdesk_response';
               

                $update_payment_data = array('gateway' => 'billdesk');
                $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));
                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', $callback_link, '', '', '', $custom_field_billdesk);
              //  echo'<pre>';print_r($billdesk_res);exit;
                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('error', 'Transaction failed...!');
                    redirect(base_url() . 'Home/fail/' . base64_encode($MerchantOrderNo));
                }
            }

        } else {
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }
    }

    public function handle_billdesk_response()
    {

        $elective_subject_name = '';

        if (isset($_REQUEST['transaction_response'])) {
            $response_encode        = $_REQUEST['transaction_response'];
            $bd_response            = $this->billdesk_pg_model->verify_res($response_encode);
            $date_for_log = date('Y-m-d H:i:s');
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

            $this->db->limit(1);
            $this->db->order_by('id', 'DESC');
            $get_user_regnum = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'member_regnumber,ref_id,status,date');

            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);

            if ($auth_status == '0300' && $qry_api_response['auth_status'] == '0300') {

                if ($this->session->userdata('examinfo') == '') { //priyanka d- added this if because examinfo session was getting unset after come back from billdesk and exampdf function was not working >> 23-feb-23
                  //  echo'here';
                    $this->db->order_by('id','desc');
                    $this->db->limit(1,0);
                    $userlogsDetails = $this->master_model->getRecords('userlogs',array('title'=>'Member exam apply details','regnumber'=> $get_user_regnum[0]['member_regnumber']),'description');
                  //  echo $this->db->last_query();
                  //  echo'<pre>';print_r($userlogsDetails);
                    $this->session->set_userdata('examinfo', unserialize($userlogsDetails[0]['description']));
                  //  echo'<pre>examinfo=';print_r($this->session->userdata('examinfo')); 
                 }
                if ($get_user_regnum[0]['status'] == 2) {

                    // Query to get user details
                    $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                    $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
                    $result = $this->master_model->getRecords('member_registration', array(
                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
                    // Query to get exam details
                    $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                    $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
                    if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
                        // ######### Generate Admit card and allocate Seat #############
                        $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array(
                            'mem_exam_id' => $get_user_regnum[0]['ref_id'],
                        ));
                        // ###########check capacity is full or not ##########
                        // $subject_arr=$this->session->userdata['examinfo']['subject_arr'];
                        if (count($exam_admicard_details) > 0) {
                            $msg          = '';
                            $sub_flag     = 1;
                            $sub_capacity = 1;
                            foreach ($exam_admicard_details as $row) {
                                $capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
                                if ($capacity == 0) {
                                    // ########get message if capacity is full##########
                                    $log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
                                    $log_message = serialize($exam_admicard_details);
                                    $rId         = $get_user_regnum[0]['ref_id'];
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                    $refund_insert_array = array('receipt_no' => $MerchantOrderNo, 'response' => $encData);
                                    $inser_id            = $this->master_model->insertRecord('S2S_direcrt_refund', $refund_insert_array);

                                    $this->refund_after_capacity_full->make_refund($MerchantOrderNo);

                                    redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                                }
                            }
                        }
                        if (count($exam_admicard_details) > 0 && $capacity > 0) {

                            // ######## payment Transaction ############

                            $update_data  = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0300', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                            $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array(
                                'receipt_no' => $MerchantOrderNo,
                                'status'     => 2,
                            ));

                            $query_update_payment_tra = $this->db->last_query();
                            $log_title                = "home ctrl query_update_payment_tra :" . $query_update_payment_tra;
                            $log_message              = serialize($update_data);
                            $rId                      = $get_user_regnum[0]['member_regnumber'];
                            $regNo                    = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /*  if ($this->db->affected_rows())
                            { */
                            $get_payment_status = $this->master_model->getRecords('payment_transaction', array(
                                'receipt_no' => $MerchantOrderNo,
                            ), 'member_regnumber,ref_id,status,date');

                            if ($get_payment_status[0]['status'] == 1) {

                                if (count($get_user_regnum) > 0) {
                                    $user_info = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                                    ), 'regnumber,usrpassword,email');
                                }

                                // admit card gen
                                $password = random_password();
                                foreach ($exam_admicard_details as $row) {
                                    $get_subject_details = $this->master_model->getRecords('venue_master', array(
                                        'venue_code'   => $row['venueid'],
                                        'exam_date'    => $row['exam_date'],
                                        'session_time' => $row['time'],
                                        'center_code'  => $row['center_code'],
                                    ));
                                    $admit_card_details = $this->master_model->getRecords('admit_card_details', array(
                                        'venueid'     => $row['venueid'],
                                        'exam_date'   => $row['exam_date'],
                                        'time'        => $row['time'],
                                        'mem_exam_id' => $get_user_regnum[0]['ref_id'],
                                        'sub_cd'      => $row['sub_cd'],
                                    ));
                                    // echo $this->db->last_query().'<br />';
                                    $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
                                    if ($seat_number != '') {
                                        $final_seat_number = $seat_number;
                                        $update_data       = array(
                                            'pwd'                 => $password,
                                            'seat_identification' => $final_seat_number,
                                            'remark'              => 1,
                                            'modified_on'         => date('Y-m-d H:i:s'),
                                        );
                                        $this->master_model->updateRecord('admit_card_details', $update_data, array(
                                            'admitcard_id' => $admit_card_details[0]['admitcard_id'],
                                        ));
                                    } else {
                                        $admit_card_details = $this->master_model->getRecords('admit_card_details', array(
                                            'admitcard_id' => $admit_card_details[0]['admitcard_id'],
                                            'remark'       => 1,
                                        ));
                                        if (count($admit_card_details) > 0) {
                                            $log_title   = "Home Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $admit_card_details[0]['admitcard_id'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        } else {
                                            $log_title   = "Home Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $get_user_regnum[0]['member_regnumber'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                            redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                                        }
                                    }
                                }

                            } else {
                                redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                            }
                        }
                        // #####update member_exam######
                        $update_data = array(
                            'pay_status' => '1',
                        );
                        if ($get_payment_status[0]['status'] == 1) {

                            $this->master_model->updateRecord('member_exam', $update_data, array(
                                'id' => $get_user_regnum[0]['ref_id'],
                            ));
                            $query_update_member_exam = $this->db->last_query();
                            $log_title                = "home ctrl query_update_member_exam :" . $query_update_member_exam;
                            $log_message              = serialize($update_data);
                            $rId                      = $get_user_regnum[0]['member_regnumber'];
                            $regNo                    = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        } else {

                            $log_title   = "home ctrl query_update_member_exam fail :";
                            $log_message = $get_user_regnum[0]['ref_id'];
                            $rId         = $get_user_regnum[0]['member_regnumber'];
                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        }
                        if ($exam_info[0]['exam_mode'] == 'ON') {
                            $mode = 'Online';
                        } elseif ($exam_info[0]['exam_mode'] == 'OF') {
                            $mode = 'Offline';
                        } else {
                            $mode = '';
                        }
                        // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                        $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                        $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                        // Query to get Medium
                        $this->db->where('exam_code', $exam_info[0]['exam_code']);
                        $this->db->where('exam_period', $exam_info[0]['exam_period']);
                        $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                        $this->db->where('medium_delete', '0');
                        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
                        $this->db->where('state_delete', '0');
                        $states = $this->master_model->getRecords('state_master', array(
                            'state_code' => $exam_info[0]['state_place_of_work'],
                        ), 'state_name');
                        // Query to get Payment details
                        $payment_info = $this->master_model->getRecords('payment_transaction', array(
                            'receipt_no'       => $MerchantOrderNo,
                            'member_regnumber' => $get_user_regnum[0]['member_regnumber'],
                        ), 'transaction_no,date,amount,id');
                        $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                        // if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
                        if ($exam_info[0]['place_of_work'] != '' && $exam_info[0]['state_place_of_work'] != '' && $exam_info[0]['pin_code_place_of_work'] != '') {
                            // get Elective Subeject name for CAIIB Exam
                            if ($exam_info[0]['elected_sub_code'] != 0 && $exam_info[0]['elected_sub_code'] != '') {
                                $this->db->group_by('subject_code');
                                $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array(
                                    'subject_code'   => $exam_info[0]['elected_sub_code'],
                                    'subject_delete' => 0,
                                ), 'subject_description');
                                if (count($elective_sub_name_arr) > 0) {
                                    $elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
                                }
                            }
                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'member_exam_enrollment_nofee_elective',
                            ));
                            if($exam_info[0]['exam_code']==$this->config->item('examCodeJaiib') || $exam_info[0]['exam_code']==$this->config->item('examCodeCaiib')  ) {
                                $emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_caiib_member_exam_enrollment_nofee_elective'));
                            }
                            $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2  = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                            $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring5  = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring4);
                            $newstring6  = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring5);
                            $newstring7  = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring7);
                            $newstring9  = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring8);
                            $newstring10 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring9);
                            $newstring11 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring10);
                            $newstring12 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring11);
                            $newstring13 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring12);
                            $newstring14 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring13);
                            $newstring15 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring14);
                            $newstring16 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring15);
                            $newstring17 = str_replace("#ELECTIVE_SUB#", "" . $elective_subject_name . "", $newstring16);
                            $newstring18 = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring17);
                            $newstring19 = str_replace("#PLACE_OF_WORK#", "" . strtoupper($exam_info[0]['place_of_work']) . "", $newstring18);
                            $newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "" . $states[0]['state_name'] . "", $newstring19);
                            $newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "" . $exam_info[0]['pin_code_place_of_work'] . "", $newstring20);
                            // $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
                            $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                            if (count($elern_msg_string) > 0) {
                                foreach ($elern_msg_string as $row) {
                                    $arr_elern_msg_string[] = $row['exam_code'];
                                }
                                if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                    $newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
                                } else {
                                    $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                                }
                            } else {
                                $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                            }
                            $final_str = str_replace("#MODE#", "" . $mode . "", $newstring22);
                        } else {

                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'apply_exam_transaction_success',
                            ));
                            if($exam_info[0]['exam_code']==$this->config->item('examCodeJaiib') || $exam_info[0]['exam_code']==$this->config->item('examCodeCaiib')  ) {
                                $emailerstr       = $this->master_model->getRecords('emailer', array('emailer_name' => 'jaiib_caiib_apply_exam_transaction_success'));
                            }
                            //echo $this->db->last_query();exit;
                            $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2  = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                            $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring5  = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                            $newstring6  = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
                            $newstring7  = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
                            $newstring9  = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
                            $newstring10 = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
                            $newstring11 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
                            $newstring12 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
                            $newstring13 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
                            $newstring14 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
                            $newstring15 = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
                            $newstring16 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
                            $newstring17 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
                            $newstring18 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
                            $newstring19 = str_replace("#MODE#", "" . $mode . "", $newstring18);
                            $newstring20 = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
                            $newstring21 = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
                            // $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
                            $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                            if (count($elern_msg_string) > 0) {
                                foreach ($elern_msg_string as $row) {
                                    $arr_elern_msg_string[] = $row['exam_code'];
                                }
                                if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                    $newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
                                } else {
                                    $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                                }
                            } else {
                                $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                            }
                            $final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring22);
                        }
                        $info_arr = array(
                            'to'      => $result[0]['email'],
                            // 'to'=>'kumartupe@gmail.com',
                            'from'    => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                            'message' => $final_str,
                        );
                        // get invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $payment_info[0]['id'],
                        ));
                        // echo $this->db->last_query();exit;
                        if (count($getinvoice_number) > 0) {
                            $invoiceNumber = '';
                            if ($get_payment_status[0]['status'] == 1 && $capacity > 0) {
                                $invoiceNumber       = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                                $query_invoiceNumber = $this->db->last_query();
                                $log_title           = "home ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
                                $log_message         = $query_invoiceNumber;
                                $rId                 = $get_user_regnum[0]['member_regnumber'];
                                $regNo               = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            } else {
                                $log_title   = "home ctrl exam_invoice number generate fail :";
                                $log_message = $getinvoice_number[0]['invoice_id'];
                                $rId         = $get_user_regnum[0]['member_regnumber'];
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }

                            if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                            }
                            // }
                            $update_data_invoice = array(
                                'invoice_no'      => $invoiceNumber,
                                'transaction_no'  => $transaction_no,
                                'date_of_invoice' => date('Y-m-d H:i:s'),
                                'modified_on'     => date('Y-m-d H:i:s'),
                            );

                            if ($get_payment_status[0]['status'] == 1) {

                                $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array(
                                    'receipt_no' => $MerchantOrderNo,
                                ));

                                $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

                                $log_title   = "home ctrl exam invoice update :";
                                $log_message = '';
                                $rId         = $MerchantOrderNo;
                                $regNo       = $MerchantOrderNo;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {

                                $log_title   = "home ctrl exam invoice update fail :";
                                $log_message = $getinvoice_number[0]['invoice_id'];
                                $rId         = $MerchantOrderNo;
                                $regNo       = $MerchantOrderNo;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }

                            $update_data_me = array(
                                'pay_status' => '1',
                            );
                            $this->master_model->updateRecord('member_exam', $update_data_me, array(
                                'id' => $get_user_regnum[0]['ref_id'],
                            ));

                            $query_exam_invoice_generate = $this->db->last_query();
                            $log_title                   = "home ctrl exam_invoice :" . $query_exam_invoice_generate;
                            $log_message                 = serialize($attachpath);
                            $rId                         = $get_user_regnum[0]['member_regnumber'];
                            $regNo                       = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
                                // #############Get Admit card#############
                                $admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                                $log_title     = "home ctrl admitcard_pdf:" . $get_user_regnum[0]['member_regnumber'];
                                $log_message   = serialize($admitcard_pdf);
                                $rId           = $get_user_regnum[0]['member_regnumber'];
                                $regNo         = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
                        }
                        if ($attachpath != '') {
                            $files = array(
                                $attachpath,
                                $admitcard_pdf,
                            );
                            $exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
                            $sms_newstring  = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
                            $sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                            $sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
                            $sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
                            //$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);

                            //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'LSy_cIwGg', $exam_info[0]['exam_code']);
                            $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 20 Sep 2023

                            $this->Emailsending->mailsend_attch($info_arr, $files);
                        }
                    } 
                    else if ($exam_info[0]['exam_code'] == 101 || $exam_info[0]['exam_code'] == 1046 || $exam_info[0]['exam_code'] == 1047) {

                        $update_data = array('transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0300', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');

                        $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo, 'status' => 2));
                        if ($this->db->affected_rows()) {
                            ######update member_exam######
                            $update_data = array('pay_status' => '1');
                            $this->master_model->updateRecord('member_exam', $update_data, array('id' => $get_user_regnum[0]['ref_id']));
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
                            $payment_info     = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $get_user_regnum[0]['member_regnumber']), 'transaction_no,date,amount,id');
                            $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            $decpass          = $aes->decrypt(trim($result[0]['usrpassword']));
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
                            $info_arr  = array('to' => $result[0]['email'],
                                'from'                  => $emailerstr[0]['from'],
                                'subject'               => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                                'message'               => $final_str,
                            );
                            //get invoice
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $payment_info[0]['id']));
                            //echo $this->db->last_query();exit;
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
                                $invoiceNumber = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                                }
                                //}
                                $update_data = array('invoice_no' => $invoiceNumber, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo));
                                $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);
                                ##############Get Admit card#############
                                if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
                                    $admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                                }
                            }
                            if ($attachpath != '') {
                                $files          = array($attachpath);
                                $exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
                                $sms_newstring  = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
                                $sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                                $sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
                                $sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
                                // $this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
                                //$this->Emailsending->mailsend($info_arr);
                                //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'C-48OSQMg', $exam_info[0]['exam_code']);
                                $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 20 Sep 2023

                                $this->Emailsending->mailsend_attch($info_arr, $files);
                            }
                        } else {
                            $log_title   = "B2B Update fail:" . $get_user_regnum[0]['member_regnumber'];
                            $log_message = serialize($update_data);
                            $rId         = $MerchantOrderNo;
                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                        }
                    }
                    // Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk",$pg_response,$transaction_error_type . " - " . $transaction_error_desc.'-B2B-Homectrl-success-log-'.$date_for_log.'-'.$MerchantOrderNo);

                    $get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no' => $MerchantOrderNo,
                    ), 'member_regnumber,ref_id');
                    // Query to get exam details
                    $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                    $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
                    redirect(base_url() . 'Home/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));

                }

            } elseif ($auth_status == '0002') {

                if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                    $update_data = array('transaction_no' => $transaction_no, 'status' => '2', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0002', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                    $this->master_model->updateRecord('payment_transaction', $update_data, array(
                        'receipt_no' => $MerchantOrderNo,
                    ));
                    // Query to get Payment details
                    $payment_info = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no'       => $MerchantOrderNo,
                        'member_regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'transaction_no,date,amount');
                    // Query to get user details
                    $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                    $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
                    $result = $this->master_model->getRecords('member_registration', array(
                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,    pincode,state_master.state_name,institution_master.name');
                    // Query to get exam details
                    $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                    $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
                    // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                    $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                    $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                    $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                    $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                    $emailerstr       = $this->master_model->getRecords('emailer', array(
                        'emailer_name' => 'transaction_fail',
                    ));
                    $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
                    $newstring2 = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
                    $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
                    $final_str  = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
                    $info_arr   = array(
                        'to'      => $result[0]['email'],
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                        'message' => $final_str,
                    );
                    // send sms to Ordinary Member
                    $sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
                    $sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                    //$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
                    // $this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
                    // $this->Emailsending->mailsend($info_arr);
                    // Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-B2B-Homectrl-pending-log-'.$MerchantOrderNo);
                }
                redirect(base_url() . 'Home/pending/' . base64_encode($MerchantOrderNo));

            } else {
                //Fail Code 2022-07-08

                if ($get_user_regnum[0]['status'] != 0 && $get_user_regnum[0]['status'] == 2) {

                    $update_data = array('transaction_no' => $transaction_no, 'status' => '0', 'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc, 'auth_code' => '0399', 'bankcode' => $bankid, 'paymode' => $txn_process_type, 'callback' => 'B2B');
                    $this->master_model->updateRecord('payment_transaction', $update_data, array(
                        'receipt_no' => $MerchantOrderNo,
                    ));
                    // Query to get Payment details
                    $payment_info = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no'       => $MerchantOrderNo,
                        'member_regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'transaction_no,date,amount');
                    // Query to get user details
                    $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                    $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
                    $result = $this->master_model->getRecords('member_registration', array(
                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,    pincode,state_master.state_name,institution_master.name');
                    // Query to get exam details
                    $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                    $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
                    // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                    $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                    $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                    $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                    $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                    $emailerstr       = $this->master_model->getRecords('emailer', array(
                        'emailer_name' => 'transaction_fail',
                    ));
                    $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
                    $newstring2 = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
                    $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
                    $final_str  = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
                    $info_arr   = array(
                        'to'      => $result[0]['email'],
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                        'message' => $final_str,
                    );
                    // send sms to Ordinary Member
                    $exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
                    $sms_newstring = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
                    $sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                    //$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
                    //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
                    $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 20 Sep 2023

                    $this->Emailsending->mailsend($info_arr);
                    // Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type . " - " . $transaction_error_desc.'-B2B-Homectrl-fail-log-'.$MerchantOrderNo);
                }
                redirect(base_url() . 'Home/fail/' . base64_encode($MerchantOrderNo));

            }
        } else {
            die("Please try again...");
        }

        //End of check sbi payment status with MerchantOrderNo
        // /End of SBICALL Back
        // Old Code
        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no' => $MerchantOrderNo,
        ), 'member_regnumber,ref_id');
        // Query to get exam details
        $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $exam_info = $this->master_model->getRecords('member_exam', array(
            'regnumber'      => $get_user_regnum[0]['member_regnumber'],
            'member_exam.id' => $get_user_regnum[0]['ref_id'],
        ), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
        redirect(base_url() . 'Home/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));
    }

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
        $MerchantOrderNo = $responsedata[0];
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
        $elective_subject_name = '';
        // Sbi B2B callback
        // check sbi payment status with MerchantOrderNo
        $q_details = sbiqueryapi($MerchantOrderNo);
        if ($q_details) {
            if ($q_details[2] == "SUCCESS") {
                $get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
                    'receipt_no' => $MerchantOrderNo,
                ), 'member_regnumber,ref_id,status,date');

                if ($get_user_regnum[0]['status'] == 2) {

                    // Query to get user details
                    $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                    $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
                    $result = $this->master_model->getRecords('member_registration', array(
                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                    ), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,institution_master.name');
                    // Query to get exam details
                    $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                    $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                    $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                    $exam_info = $this->master_model->getRecords('member_exam', array(
                        'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                        'member_exam.id' => $get_user_regnum[0]['ref_id'],
                    ), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month,member_exam.state_place_of_work,member_exam.place_of_work,member_exam.pin_code_place_of_work,member_exam.examination_date,member_exam.elected_sub_code');
                    if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
                        // ######### Generate Admit card and allocate Seat #############
                        $exam_admicard_details = $this->master_model->getRecords('admit_card_details', array(
                            'mem_exam_id' => $get_user_regnum[0]['ref_id'],
                        ));
                        // ###########check capacity is full or not ##########
                        // $subject_arr=$this->session->userdata['examinfo']['subject_arr'];
                        if (count($exam_admicard_details) > 0) {
                            $msg          = '';
                            $sub_flag     = 1;
                            $sub_capacity = 1;
                            foreach ($exam_admicard_details as $row) {
                                $capacity = check_capacity($row['venueid'], $row['exam_date'], $row['time'], $row['center_code']);
                                if ($capacity == 0) {
                                    // ########get message if capacity is full##########
                                    $log_title   = "Capacity full id:" . $get_user_regnum[0]['member_regnumber'];
                                    $log_message = serialize($exam_admicard_details);
                                    $rId         = $get_user_regnum[0]['ref_id'];
                                    $regNo       = $get_user_regnum[0]['member_regnumber'];
                                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                                    $refund_insert_array = array('receipt_no' => $MerchantOrderNo, 'response' => $encData);
                                    $inser_id            = $this->master_model->insertRecord('S2S_direcrt_refund', $refund_insert_array);
                                    redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                                }
                            }
                        }
                        if (count($exam_admicard_details) > 0 && $capacity > 0) {

                            // ######## payment Transaction ############
                            $update_data = array(
                                'transaction_no'      => $transaction_no,
                                'status'              => 1,
                                'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                                'auth_code'           => '0300',
                                'bankcode'            => $responsedata[8],
                                'paymode'             => $responsedata[5],
                                'callback'            => 'B2B',
                            );
                            $update_query = $this->master_model->updateRecord('payment_transaction', $update_data, array(
                                'receipt_no' => $MerchantOrderNo,
                                'status'     => 2,
                            ));

                            $query_update_payment_tra = $this->db->last_query();
                            $log_title                = "home ctrl query_update_payment_tra :" . $query_update_payment_tra;
                            $log_message              = serialize($update_data);
                            $rId                      = $get_user_regnum[0]['member_regnumber'];
                            $regNo                    = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                            /*  if ($this->db->affected_rows())
                            { */
                            $get_payment_status = $this->master_model->getRecords('payment_transaction', array(
                                'receipt_no' => $MerchantOrderNo,
                            ), 'member_regnumber,ref_id,status,date');

                            if ($get_payment_status[0]['status'] == 1) {

                                if (count($get_user_regnum) > 0) {
                                    $user_info = $this->master_model->getRecords('member_registration', array(
                                        'regnumber' => $get_user_regnum[0]['member_regnumber'],
                                    ), 'regnumber,usrpassword,email');
                                }

                                // admit card gen
                                $password = random_password();
                                foreach ($exam_admicard_details as $row) {
                                    $get_subject_details = $this->master_model->getRecords('venue_master', array(
                                        'venue_code'   => $row['venueid'],
                                        'exam_date'    => $row['exam_date'],
                                        'session_time' => $row['time'],
                                        'center_code'  => $row['center_code'],
                                    ));
                                    $admit_card_details = $this->master_model->getRecords('admit_card_details', array(
                                        'venueid'     => $row['venueid'],
                                        'exam_date'   => $row['exam_date'],
                                        'time'        => $row['time'],
                                        'mem_exam_id' => $get_user_regnum[0]['ref_id'],
                                        'sub_cd'      => $row['sub_cd'],
                                    ));
                                    // echo $this->db->last_query().'<br />';
                                    $seat_number = getseat($exam_info[0]['exam_code'], $exam_info[0]['exam_center_code'], $get_subject_details[0]['venue_code'], $get_subject_details[0]['exam_date'], $get_subject_details[0]['session_time'], $exam_info[0]['exam_period'], $row['sub_cd'], $get_subject_details[0]['session_capacity'], $admit_card_details[0]['admitcard_id']);
                                    if ($seat_number != '') {
                                        $final_seat_number = $seat_number;
                                        $update_data       = array(
                                            'pwd'                 => $password,
                                            'seat_identification' => $final_seat_number,
                                            'remark'              => 1,
                                            'modified_on'         => date('Y-m-d H:i:s'),
                                        );
                                        $this->master_model->updateRecord('admit_card_details', $update_data, array(
                                            'admitcard_id' => $admit_card_details[0]['admitcard_id'],
                                        ));
                                    } else {
                                        $admit_card_details = $this->master_model->getRecords('admit_card_details', array(
                                            'admitcard_id' => $admit_card_details[0]['admitcard_id'],
                                            'remark'       => 1,
                                        ));
                                        if (count($admit_card_details) > 0) {
                                            $log_title   = "Home Seat number already allocated id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $admit_card_details[0]['admitcard_id'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                        } else {
                                            $log_title   = "Home Fail user seat allocation id:" . $get_user_regnum[0]['member_regnumber'];
                                            $log_message = serialize($exam_admicard_details);
                                            $rId         = $get_user_regnum[0]['member_regnumber'];
                                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                                            storedUserActivity($log_title, $log_message, $rId, $regNo);
                                            redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                                        }
                                    }
                                }

                            } else {
                                redirect(base_url() . 'Home/refund/' . base64_encode($MerchantOrderNo));
                            }
                        }
                        // #####update member_exam######
                        $update_data = array(
                            'pay_status' => '1',
                        );
                        if ($get_payment_status[0]['status'] == 1) {

                            $this->master_model->updateRecord('member_exam', $update_data, array(
                                'id' => $get_user_regnum[0]['ref_id'],
                            ));
                            $query_update_member_exam = $this->db->last_query();
                            $log_title                = "home ctrl query_update_member_exam :" . $query_update_member_exam;
                            $log_message              = serialize($update_data);
                            $rId                      = $get_user_regnum[0]['member_regnumber'];
                            $regNo                    = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        } else {

                            $log_title   = "home ctrl query_update_member_exam fail :";
                            $log_message = $get_user_regnum[0]['ref_id'];
                            $rId         = $get_user_regnum[0]['member_regnumber'];
                            $regNo       = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                        }
                        if ($exam_info[0]['exam_mode'] == 'ON') {
                            $mode = 'Online';
                        } elseif ($exam_info[0]['exam_mode'] == 'OF') {
                            $mode = 'Offline';
                        } else {
                            $mode = '';
                        }
                        // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                        $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                        $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                        // Query to get Medium
                        $this->db->where('exam_code', $exam_info[0]['exam_code']);
                        $this->db->where('exam_period', $exam_info[0]['exam_period']);
                        $this->db->where('medium_code', $exam_info[0]['exam_medium']);
                        $this->db->where('medium_delete', '0');
                        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
                        $this->db->where('state_delete', '0');
                        $states = $this->master_model->getRecords('state_master', array(
                            'state_code' => $exam_info[0]['state_place_of_work'],
                        ), 'state_name');
                        // Query to get Payment details
                        $payment_info = $this->master_model->getRecords('payment_transaction', array(
                            'receipt_no'       => $MerchantOrderNo,
                            'member_regnumber' => $get_user_regnum[0]['member_regnumber'],
                        ), 'transaction_no,date,amount,id');
                        $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                        // if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
                        if ($exam_info[0]['place_of_work'] != '' && $exam_info[0]['state_place_of_work'] != '' && $exam_info[0]['pin_code_place_of_work'] != '') {
                            // get Elective Subeject name for CAIIB Exam
                            if ($exam_info[0]['elected_sub_code'] != 0 && $exam_info[0]['elected_sub_code'] != '') {
                                $this->db->group_by('subject_code');
                                $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array(
                                    'subject_code'   => $exam_info[0]['elected_sub_code'],
                                    'subject_delete' => 0,
                                ), 'subject_description');
                                if (count($elective_sub_name_arr) > 0) {
                                    $elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
                                }
                            }
                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'member_exam_enrollment_nofee_elective',
                            ));
                            $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2  = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                            $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring5  = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring4);
                            $newstring6  = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring5);
                            $newstring7  = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring7);
                            $newstring9  = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring8);
                            $newstring10 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring9);
                            $newstring11 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring10);
                            $newstring12 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring11);
                            $newstring13 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring12);
                            $newstring14 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring13);
                            $newstring15 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring14);
                            $newstring16 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring15);
                            $newstring17 = str_replace("#ELECTIVE_SUB#", "" . $elective_subject_name . "", $newstring16);
                            $newstring18 = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring17);
                            $newstring19 = str_replace("#PLACE_OF_WORK#", "" . strtoupper($exam_info[0]['place_of_work']) . "", $newstring18);
                            $newstring20 = str_replace("#STATE_PLACE_OF_WORK#", "" . $states[0]['state_name'] . "", $newstring19);
                            $newstring21 = str_replace("#PINCODE_PLACE_OF_WORK#", "" . $exam_info[0]['pin_code_place_of_work'] . "", $newstring20);
                            // $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
                            $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                            if (count($elern_msg_string) > 0) {
                                foreach ($elern_msg_string as $row) {
                                    $arr_elern_msg_string[] = $row['exam_code'];
                                }
                                if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                    $newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
                                } else {
                                    $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                                }
                            } else {
                                $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                            }
                            $final_str = str_replace("#MODE#", "" . $mode . "", $newstring22);
                        } else {
                            $emailerstr = $this->master_model->getRecords('emailer', array(
                                'emailer_name' => 'apply_exam_transaction_success',
                            ));
                            $newstring1  = str_replace("#USERNAME#", "" . $userfinalstrname . "", $emailerstr[0]['emailer_text']);
                            $newstring2  = str_replace("#REG_NUM#", "" . $get_user_regnum[0]['member_regnumber'] . "", $newstring1);
                            $newstring3  = str_replace("#EXAM_NAME#", "" . $exam_info[0]['description'] . "", $newstring2);
                            $newstring4  = str_replace("#EXAM_DATE#", "" . $exam_period_date . "", $newstring3);
                            $newstring5  = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                            $newstring6  = str_replace("#ADD1#", "" . $result[0]['address1'] . "", $newstring5);
                            $newstring7  = str_replace("#ADD2#", "" . $result[0]['address2'] . "", $newstring6);
                            $newstring8  = str_replace("#ADD3#", "" . $result[0]['address3'] . "", $newstring7);
                            $newstring9  = str_replace("#ADD4#", "" . $result[0]['address4'] . "", $newstring8);
                            $newstring10 = str_replace("#DISTRICT#", "" . $result[0]['district'] . "", $newstring9);
                            $newstring11 = str_replace("#CITY#", "" . $result[0]['city'] . "", $newstring10);
                            $newstring12 = str_replace("#STATE#", "" . $result[0]['state_name'] . "", $newstring11);
                            $newstring13 = str_replace("#PINCODE#", "" . $result[0]['pincode'] . "", $newstring12);
                            $newstring14 = str_replace("#EMAIL#", "" . $result[0]['email'] . "", $newstring13);
                            $newstring15 = str_replace("#INSTITUDE#", "" . $result[0]['name'] . "", $newstring14);
                            $newstring16 = str_replace("#MEDIUM#", "" . $medium[0]['medium_description'] . "", $newstring15);
                            $newstring17 = str_replace("#CENTER#", "" . $exam_info[0]['center_name'] . "", $newstring16);
                            $newstring18 = str_replace("#CENTER_CODE#", "" . $exam_info[0]['exam_center_code'] . "", $newstring17);
                            $newstring19 = str_replace("#MODE#", "" . $mode . "", $newstring18);
                            $newstring20 = str_replace("#PLACE_OF_WORK#", "" . $result[0]['office'] . "", $newstring19);
                            $newstring21 = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring20);
                            // $elern_msg_string=array(21,60,62,63,64,65,66,67,68,69,70,71,72,42,58,580,81,5800,34,340,3400,151);
                            $elern_msg_string = $this->master_model->getRecords('elearning_examcode');
                            if (count($elern_msg_string) > 0) {
                                foreach ($elern_msg_string as $row) {
                                    $arr_elern_msg_string[] = $row['exam_code'];
                                }
                                if (in_array($exam_info[0]['exam_code'], $arr_elern_msg_string)) {
                                    $newstring22 = str_replace("#E-MSG#", $this->config->item('e-learn-msg'), $newstring21);
                                } else {
                                    $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                                }
                            } else {
                                $newstring22 = str_replace("#E-MSG#", '', $newstring21);
                            }
                            $final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring22);
                        }
                        $info_arr = array(
                            'to'      => $result[0]['email'],
                            // 'to'=>'kumartupe@gmail.com',
                            'from'    => $emailerstr[0]['from'],
                            'subject' => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                            'message' => $final_str,
                        );
                        // get invoice
                        $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                            'receipt_no' => $MerchantOrderNo,
                            'pay_txn_id' => $payment_info[0]['id'],
                        ));
                        // echo $this->db->last_query();exit;
                        if (count($getinvoice_number) > 0) {
                            $invoiceNumber = '';
                            if ($get_payment_status[0]['status'] == 1 && $capacity > 0) {
                                $invoiceNumber       = generate_exam_invoice_number($getinvoice_number[0]['invoice_id']);
                                $query_invoiceNumber = $this->db->last_query();
                                $log_title           = "home ctrl exam_invoice number generate :" . $getinvoice_number[0]['invoice_id'];
                                $log_message         = $query_invoiceNumber;
                                $rId                 = $get_user_regnum[0]['member_regnumber'];
                                $regNo               = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            } else {
                                $log_title   = "home ctrl exam_invoice number generate fail :";
                                $log_message = $getinvoice_number[0]['invoice_id'];
                                $rId         = $get_user_regnum[0]['member_regnumber'];
                                $regNo       = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }

                            if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('exam_invoice_no_prefix') . $invoiceNumber;
                            }
                            // }
                            $update_data_invoice = array(
                                'invoice_no'      => $invoiceNumber,
                                'transaction_no'  => $transaction_no,
                                'date_of_invoice' => date('Y-m-d H:i:s'),
                                'modified_on'     => date('Y-m-d H:i:s'),
                            );

                            if ($get_payment_status[0]['status'] == 1) {

                                $this->db->where('pay_txn_id', $payment_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data_invoice, array(
                                    'receipt_no' => $MerchantOrderNo,
                                ));

                                $attachpath = genarate_exam_invoice($getinvoice_number[0]['invoice_id']);

                                $log_title   = "home ctrl exam invoice update :";
                                $log_message = '';
                                $rId         = $MerchantOrderNo;
                                $regNo       = $MerchantOrderNo;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                            } else {

                                $log_title   = "home ctrl exam invoice update fail :";
                                $log_message = $getinvoice_number[0]['invoice_id'];
                                $rId         = $MerchantOrderNo;
                                $regNo       = $MerchantOrderNo;
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }

                            $update_data_me = array(
                                'pay_status' => '1',
                            );
                            $this->master_model->updateRecord('member_exam', $update_data_me, array(
                                'id' => $get_user_regnum[0]['ref_id'],
                            ));

                            $query_exam_invoice_generate = $this->db->last_query();
                            $log_title                   = "home ctrl exam_invoice :" . $query_exam_invoice_generate;
                            $log_message                 = serialize($attachpath);
                            $rId                         = $get_user_regnum[0]['member_regnumber'];
                            $regNo                       = $get_user_regnum[0]['member_regnumber'];
                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            if ($exam_info[0]['exam_code'] != 101 && $exam_info[0]['exam_code'] != 1046 && $exam_info[0]['exam_code'] != 1047) {
                                // #############Get Admit card#############
                                $admitcard_pdf = genarate_admitcard($get_user_regnum[0]['member_regnumber'], $exam_info[0]['exam_code'], $exam_info[0]['exam_period']);
                                $log_title     = "home ctrl admitcard_pdf:" . $get_user_regnum[0]['member_regnumber'];
                                $log_message   = serialize($admitcard_pdf);
                                $rId           = $get_user_regnum[0]['member_regnumber'];
                                $regNo         = $get_user_regnum[0]['member_regnumber'];
                                storedUserActivity($log_title, $log_message, $rId, $regNo);
                            }
                        }
                        if ($attachpath != '') {
                            $files = array(
                                $attachpath,
                                $admitcard_pdf,
                            );
                            $exm_name_sms = substr(str_replace('/','',$exam_info[0]['description']),0,30);
                            $sms_newstring  = str_replace("#exam_name#", "" . $exm_name_sms . "", $emailerstr[0]['sms_text']);
                            $sms_newstring1 = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                            $sms_newstring2 = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $sms_newstring1);
                            $sms_final_str  = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring2);
                            //$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
                            //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'C-48OSQMg', $exam_info[0]['exam_code']);
                            $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 20 Sep 2023

                            $this->Emailsending->mailsend_attch($info_arr, $files);
                        }
                    }
                    /* }
                    else
                    {
                    $log_title = "B2B Update fail:" . $get_user_regnum[0]['member_regnumber'];
                    $log_message = serialize($update_data_invoice);
                    $rId = $MerchantOrderNo;
                    $regNo = $get_user_regnum[0]['member_regnumber'];
                    storedUserActivity($log_title, $log_message, $rId, $regNo);
                    } */
                    // Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                }

            }
        } //End of check sbi payment status with MerchantOrderNo
        // /End of SBICALL Back
        // Old Code
        $get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no' => $MerchantOrderNo,
        ), 'member_regnumber,ref_id');
        // Query to get exam details
        $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $exam_info = $this->master_model->getRecords('member_exam', array(
            'regnumber'      => $get_user_regnum[0]['member_regnumber'],
            'member_exam.id' => $get_user_regnum[0]['ref_id'],
        ), 'member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
        redirect(base_url() . 'Home/details/' . base64_encode($MerchantOrderNo) . '/' . base64_encode($exam_info[0]['exam_code']));
    }

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
            // SBICALL Back B2B
            $get_user_regnum = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => $MerchantOrderNo,
            ), 'member_regnumber,ref_id,status');
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
                $update_data = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 0,
                    'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                    'auth_code'           => '0399',
                    'bankcode'            => $responsedata[8],
                    'paymode'             => $responsedata[5],
                    'callback'            => 'B2B',
                );
                $this->master_model->updateRecord('payment_transaction', $update_data, array(
                    'receipt_no' => $MerchantOrderNo,
                ));
                // Query to get Payment details
                $payment_info = $this->master_model->getRecords('payment_transaction', array(
                    'receipt_no'       => $MerchantOrderNo,
                    'member_regnumber' => $get_user_regnum[0]['member_regnumber'],
                ), 'transaction_no,date,amount');
                // Query to get user details
                $this->db->join('state_master', 'state_master.state_code=member_registration.state');
                $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
                $result = $this->master_model->getRecords('member_registration', array(
                    'regnumber' => $get_user_regnum[0]['member_regnumber'],
                ), 'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,    pincode,state_master.state_name,institution_master.name');
                // Query to get exam details
                $this->db->join('center_master', 'center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code AND center_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $exam_info = $this->master_model->getRecords('member_exam', array(
                    'regnumber'      => $get_user_regnum[0]['member_regnumber'],
                    'member_exam.id' => $get_user_regnum[0]['ref_id'],
                ), 'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month, member_exam.exam_code');
                // $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
                $month            = date('Y') . "-" . substr($exam_info[0]['exam_month'], 4);
                $exam_period_date = date('F', strtotime($month)) . "-" . substr($exam_info[0]['exam_month'], 0, -2);
                $username         = $result[0]['firstname'] . ' ' . $result[0]['middlename'] . ' ' . $result[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $emailerstr       = $this->master_model->getRecords('emailer', array(
                    'emailer_name' => 'transaction_fail',
                ));
                $newstring1 = str_replace("#application_num#", "" . $get_user_regnum[0]['member_regnumber'] . "", $emailerstr[0]['emailer_text']);
                $newstring2 = str_replace("#username#", "" . $userfinalstrname . "", $newstring1);
                $newstring3 = str_replace("#transaction_id#", "" . $payment_info[0]['transaction_no'] . "", $newstring2);
                $final_str  = str_replace("#transaction_date#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring3);
                $info_arr   = array(
                    'to'      => $result[0]['email'],
                    'from'    => $emailerstr[0]['from'],
                    'subject' => $emailerstr[0]['subject'] . ' ' . $get_user_regnum[0]['member_regnumber'],
                    'message' => $final_str,
                );
                // send sms to Ordinary Member
                $sms_newstring = str_replace("#exam_name#", "" . $exam_info[0]['description'] . "", $emailerstr[0]['sms_text']);
                $sms_final_str = str_replace("#period#", "" . $exam_period_date . "", $sms_newstring);
                //$this->master_model->send_sms($result[0]['mobile'], $sms_final_str);
                //$this->master_model->send_sms_trustsignal(intval($result[0]['mobile']), $sms_final_str, 'Jw6bOIQGg', $exam_info[0]['exam_code']);
                $this->master_model->send_sms_common_all($result[0]['mobile'], $sms_final_str, $emailerstr[0]['sms_template_id'], $emailerstr[0]['sms_sender'], $exam_info[0]['exam_code']); // Added on 20 Sep 2023

                $this->Emailsending->mailsend($info_arr);
                // Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            // End Of SBICALL Back
            // Old Code
            redirect(base_url() . 'Home/fail/' . base64_encode($MerchantOrderNo));
        } else {
            die("Please try again...");
        }
    }

    // #------------------Insert data in member_exam table for applied exam,for logged in user Without Payment(PRAFULL)---------------##
    /*public function saveexam()
    {
    $final_str='';
    $this->chk_session->checkphoto();
    $photoname=$singname='';
    if(($this->session->userdata('examinfo')==''))
    {
    redirect(base_url().'Home/dashboard/');
    }
    if(isset($_POST['btnPreview']))
    {
    $inser_array=array(    'regnumber'=>$this->session->userdata('regnumber'),
    'exam_code'=>base64_decode($this->session->userdata['examinfo']['excd']),
    'exam_mode'=>$this->session->userdata['examinfo']['optmode'],
    'exam_medium'=>$this->session->userdata['examinfo']['medium'],
    'exam_period'=>$this->session->userdata['examinfo']['eprid'],
    'exam_center_code'=>$this->session->userdata['examinfo']['txtCenterCode'],
    'exam_fee'=>$this->session->userdata['examinfo']['fee'],
    'pay_status'=>'1',
    'elected_sub_code'=>$this->session->userdata['examinfo']['selected_elect_subcode'],
    'place_of_work'=>$this->session->userdata['examinfo']['placeofwork'],
    'state_place_of_work'=>$this->session->userdata['examinfo']['state_place_of_work'],
    'pin_code_place_of_work'=>$this->session->userdata['examinfo']['pincode_place_of_work'],
    'created_on'=>date('y-m-d H:i:s')
    );
    if($inser_id=$this->master_model->insertRecord('member_exam',$inser_array,true))
    {
    // echo $this->session->userdata['examinfo']['fee'];
    $this->session->userdata['examinfo']['insdet_id']=$inser_id;
    $update_array=array();
    // update an array for images
    if($this->session->userdata['examinfo']['photo']!='')
    {
    $update_array=array_merge($update_array, array("scannedphoto"=>$this->session->userdata['examinfo']['photo']));
    $photo_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'scannedphoto');
    $photoname=$photo_name[0]['scannedphoto'];
    }
    if($this->session->userdata['examinfo']['signname']!='')
    {
    $update_array=array_merge($update_array, array("scannedsignaturephoto"=>$this->session->userdata['examinfo']['signname']));
    $sing_name=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'scannedsignaturephoto');
    $singname=$sing_name[0]['scannedsignaturephoto'];
    }
    // check if email is unique
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
    $this->master_model->updateRecord('member_registration',$update_array,array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')));
    // @unlink('uploads/photograph/'.$photoname);
    // @unlink('uploads/scansignature/'.$singname);
    logactivity($log_title ="Member update profile during exam apply", $log_message = serialize($update_array));
    }
    $this->db->join('state_master','state_master.state_code=member_registration.state');
    $result=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('regid'),'regnumber'=>$this->session->userdata('regnumber')),'firstname,middlename,lastname,address1,address2,address3,address4,district,city,email,mobile,office,pincode,state_master.state_name,usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto');
    $this->db->join('center_master','center_master.center_code=member_exam.exam_center_code AND center_master.exam_name=member_exam.exam_code');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $this->db->join('misc_master','misc_master.exam_code=member_exam.exam_code');
    $exam_info=$this->master_model->getRecords('member_exam',array('member_exam.id'=>$inser_id,'member_exam.regnumber'=>$this->session->userdata('regnumber')),'member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,center_master.center_name,member_exam.exam_center_code,exam_master.description,misc_master.exam_month');
    if($exam_info[0]['exam_mode']=='ON')
    {$mode='Online';}elseif($exam_info[0]['exam_mode']=='OF')
    {$mode='Offline';}
    else{$mode='';}
    $month = date('Y')."-".substr($exam_info[0]['exam_month'],4)."-".date('d');
    $exam_period_date=date('F',strtotime($month))."-".substr($exam_info[0]['exam_month'],0,-2);
    // Get Medium
    $this->db->where('exam_code',base64_decode($this->session->userdata['examinfo']['excd']));
    $this->db->where('exam_period',$exam_info[0]['exam_period']);
    $this->db->where('medium_code',$exam_info[0]['exam_medium']);
    $this->db->where('medium_delete','0');
    $medium=$this->master_model->getRecords('medium_master','','medium_description');
    $this->db->where('state_delete','0');
    $states=$this->master_model->getRecords('state_master',array('state_code'=>$this->session->userdata['examinfo']['state_place_of_work']),'state_name');
    $username=$result[0]['firstname'].' '.$result[0]['middlename'].' '.$result[0]['lastname'];
    $userfinalstrname= preg_replace('#[\s]+#', ' ', $username);
    if($this->session->userdata['examinfo']['elected_exam_mode']=='E')
    {
    $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee_elective'));
    $newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
    $newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
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
    $newstring18 = str_replace("#PLACE_OF_WORK#", "".strtoupper($this->session->userdata['examinfo']['placeofwork'])."",$newstring17);
    $newstring19 = str_replace("#STATE_PLACE_OF_WORK#", "".$states[0]['state_name']."",$newstring18);
    $newstring20 = str_replace("#PINCODE_PLACE_OF_WORK#", "".$this->session->userdata['examinfo']['pincode_place_of_work']."",$newstring19);
    $final_str = str_replace("#MODE#", "".$mode."",$newstring20);
    }
    else
    {
    $emailerstr=$this->master_model->getRecords('emailer',array('emailer_name'=>'member_exam_enrollment_nofee'));
    $newstring1= str_replace("#USERNAME#", "".$userfinalstrname."",$emailerstr[0]['emailer_text']);
    $newstring2 = str_replace("#REG_NUM#", "".$this->session->userdata('regnumber')."",$newstring1);
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
    }
    $info_arr=array(    'to'=>$result[0]['email'],
    'from'=>$emailerstr[0]['from'],
    'subject'=>$emailerstr[0]['subject'],
    'message'=>$final_str
    );
    // To Do---Transaction email to user    currently we using failure emailer
    $this->Emailsending->mailsend($info_arr);
    redirect(base_url().'Home/savedetails/');
    }
    }
    else
    {
    redirect(base_url().'Home/dashboard/');
    }
    }*/

    // Show acknowlodgement to to user after transaction succeess
    public function details($order_no = null, $excd = null)
    {
        // $this->chk_session->checkphoto();
        // payment detail
       
        $payment_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no'       => base64_decode($order_no),
            'member_regnumber' => $this->session->userdata('regnumber'),
        ));
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code,exam_master.ebook_flag');
        $this->db->where('elg_mem_o', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array(
            'member_exam.exam_code' => base64_decode($excd),
            'regnumber'             => $this->session->userdata('regnumber'),
            'pay_status'            => '1',
        ));
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Home/dashboard');
        }
        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', base64_decode($excd));
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
        $this->db->where('exam_name', base64_decode($excd));
        $this->db->where("center_delete", '0');
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $center = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        // echo $this->db->last_query();exit;
        // get state details
        $this->db->where('state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'home/dashboard/');
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
        'pincode_place_of_work'=>''
        );
        $this->session->unset_userdata('examinfo',$user_data);
         */
        $data = array(
            'middle_content'    => 'exam_applied_success',
            'medium'            => $medium,
            'center'            => $center,
            'applied_exam_info' => $applied_exam_info,
            'payment_info'      => $payment_info,
            'states'            => $states,
        );
        $this->load->view('common_view', $data);
    }

    // Show acknowlodgement to to user after transaction succeess
    public function savedetails()
    {
        // $this->chk_session->checkphoto();
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }
        $exam_code  = base64_decode($this->session->userdata['examinfo']['excd']);
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');
        $this->db->where('elg_mem_o', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array(
            'member_exam.exam_code' => $exam_code,
            'regnumber'             => $this->session->userdata('regnumber'),
        ));
        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', $exam_code);
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
        $this->db->where('exam_name', $exam_code);
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        // get state details
        $this->db->where('state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Home/dashboard/');
        }
        $data = array(
            'middle_content'    => 'exam_applied_success_withoutpay',
            'medium'            => $medium,
            'center'            => $center,
            'applied_exam_info' => $applied_exam_info,
            'states'            => $states,
        );
        $this->load->view('common_view', $data);
    }

    // Show acknowlodgement to to user after transaction Failure
    public function fail($order_no = null)
    {
        // $this->chk_session->checkphoto();
        // payment detail
        $payment_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no'       => base64_decode($order_no),
            'member_regnumber' => $this->session->userdata('regnumber'),
        ));
        if (count($payment_info) <= 0) {
            redirect(base_url());
        }
        $data = array(
            'middle_content' => 'exam_applied_fail',
            'payment_info'   => $payment_info,
        );
        $this->load->view('common_view', $data);
    }


     public function pending($order_no = null)
    {
        // $this->chk_session->checkphoto();
        // payment detail
        $payment_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no'       => base64_decode($order_no),
            'member_regnumber' => $this->session->userdata('regnumber'),
        ));
        if (count($payment_info) <= 0) {
            redirect(base_url());
        }
        $data = array(
            'middle_content' => 'exam_applied_pending',
            'payment_info'   => $payment_info,
        );
        $this->load->view('common_view', $data);
    }

    

    // ######## if seat allocation full show message#######
    public function refund($order_no = null)
    {
        // payment detail
        // $this->db->join('member_exam','member_exam.id=payment_transaction.ref_id AND member_exam.exam_code=payment_transaction.exam_code');
        // $this->db->where('member_exam.regnumber',$this->session->userdata('regnumber'));
        $payment_info = $this->master_model->getRecords('payment_transaction', array(
            'receipt_no' => base64_decode($order_no),
        ));
        if (count($payment_info) <= 0) {
            redirect(base_url());
        }
        $this->db->where('remark', '2');
        $admit_card_refund = $this->master_model->getRecords('admit_card_details', array(
            'mem_exam_id' => $payment_info[0]['ref_id'],
        ));
        if (count($admit_card_refund) > 0) {
            $update_data = array(
                'remark' => 3,
            );
            $this->master_model->updateRecord('admit_card_details', $update_data, array(
                'mem_exam_id' => $payment_info[0]['ref_id'],
            ));
        }
        $exam_name = $this->master_model->getRecords('exam_master', array(
            'exam_code' => $payment_info[0]['exam_code'],
        ));

        ##adding below code for processing the refund process - added by chaitali on 2021-09-17
        $insert_data = array('receipt_no' => base64_decode($order_no), 'transaction_no' => $payment_info[0]['transaction_no'], 'refund' => '0', 'created_on' => date('Y-m-d'), 'email_flag' => '0', 'sms_flag' => '0');
        $this->master_model->insertRecord('exam_payment_refund', $insert_data);
        //echo $this->db->last_query(); die;
        ## ended insert code

        $data = array(
            'middle_content' => 'member_refund',
            'payment_info'   => $payment_info,
            'exam_name'      => $exam_name,
        );
        $this->load->view('common_view', $data);
    }

    // check user already exam apply or not(Prafull)
    public function examapplied($regnumber = null, $exam_code = null, $exam_date_arr=array())
    {
        // check where exam alredy apply or not
        $cnt        = 0;
        $today_date = date('Y-m-d');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        // $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $this->db->where('exam_master.elg_mem_o', 'Y');
        $this->db->where('pay_status', '1');
        $applied_exam_info = $this->master_model->getRecords('member_exam', array(
            'member_exam.exam_code' => base64_decode($exam_code),
            'regnumber'             => $regnumber,
        ));
        // echo $this->db->last_query();exit;
        // ###check if number applied through the bulk registration (Prafull)###
        if (count($applied_exam_info) <= 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            // $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('exam_master.elg_mem_o', 'Y');
            $this->db->where('bulk_isdelete', '0');
            $this->db->where('institute_id!=', '');
            $applied_exam_info = $this->master_model->getRecords('member_exam', array(
                'member_exam.exam_code' => base64_decode($exam_code),
                'regnumber'             => $regnumber,
            ));
        }
        // ##### End of check  number applied through the bulk registration###

        //START : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE
        if (count($applied_exam_info) <= 0 && count($exam_date_arr) > 0)
        {
          $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
          $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
          $this->db->join('admit_card_details', 'admit_card_details.mem_exam_id = member_exam.id', 'inner');
          //$this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
          //$this->db->where('exam_master.elg_mem_o', 'Y');
          $this->db->where('bulk_isdelete', '0');
          //$this->db->where('institute_id!=', '');

          $this->db->where_in('admit_card_details.exam_date', $exam_date_arr);

          //if instutute id is null/empty then check remark is 1 else no need to check remark
          $this->db->where(" (((institute_id IS NULL OR institute_id = '' OR institute_id = '0') AND remark = '1') OR (institute_id IS NOT NULL AND institute_id != '' AND institute_id != '0')) ");           

          $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber));
          //echo '<br><br>4 ' . $this->db->last_query(); //die;
          if(count($applied_exam_info) > 0)//START : LOG ADDED BY SAGAR M ON 2024-10-02
          {
            $add_duplicate_log = array();
            if(isset($_POST) && count($_POST) > 0) { $add_duplicate_log['posted_data'] = json_encode($_POST); }
            if(isset($_SESSION)) { $add_duplicate_log['session_data'] = json_encode($_SESSION); }
            $add_duplicate_log['last_query'] = $this->db->last_query();
            $add_duplicate_log['regnumber'] = $regnumber;
            $this->master_model->insertRecord('duplicate_redirected_records', $add_duplicate_log);

            $email_send_arr = array();
            $email_send_arr['to'] = 'sagar.matale@esds.co.in, anil.s@esds.co.in';
            $email_send_arr['from'] = 'logs@iibf.esdsconnect.com';
            $email_send_arr['subject'] = 'Duplicate exam application prevented : '.$regnumber;
            $email_send_arr['message'] = 'The member '.$regnumber.' tried to apply for the duplicate exam date but redirected due to the check added by Anil & Sagar.';
            $this->Emailsending->mailsend($email_send_arr);
          }//END : LOG ADDED BY SAGAR M ON 2024-10-02
        } //END : ADDED BY SAGAR ON 2024-09-12 TO PREVENT THE DUPLICATION APPLICATION FOR SAME DATE


        // START: OLD BCBF CHECK ADDED BY ANIL
        $exCode = base64_decode($exam_code);
        $examCd_Arr = array(1046,1047); 
        if(in_array($exCode, $examCd_Arr)){
            $get_exam_period = $this->master_model->getRecords('exam_activation_master', array('exam_code' => $exCode));
            $this->db->select('member_exam.*');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            //$this->db->where('exam_master.elg_mem_o', 'Y');
            $this->db->where('pay_status', '1');
            $this->db->where_in('member_exam.exam_code', $examCd_Arr);
            $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_period' => $get_exam_period[0]['exam_period'], 'regnumber' => $regnumber));
            //echo $this->db->last_query(); die;
        }    
        // END: OLD BCBF CHECK ADDED BY ANIL

        ######get eligible created on data##########
        $this->db->limit('1');
        $get_eligible_date = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => base64_decode($exam_code), 'member_no' => $regnumber), 'eligible_master.created_on');
        if (count($applied_exam_info) > 0) {

            if (base64_decode($exam_code) != $this->config->item('examCodeJaiib') && base64_decode($exam_code) != $this->config->item('examCodeDBF') && base64_decode($exam_code) != $this->config->item('examCodeSOB') && base64_decode($exam_code) != $this->config->item('examCodeCaiib') && base64_decode($exam_code) != 62 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective63') && base64_decode($exam_code) != 64 && base64_decode($exam_code) != 65 && base64_decode($exam_code) != 66 && base64_decode($exam_code) != 67 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective68') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective69') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective70') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective71') && base64_decode($exam_code) != 72) {

                if (count($get_eligible_date) > 0) {
                    if (strtotime($applied_exam_info[0]['created_on']) > strtotime($get_eligible_date[0]['created_on'])) {
                        $cnt = $cnt + 1;
                    }
                } else {
                    $cnt = count($applied_exam_info);
                }

            } else {
                $cnt = count($applied_exam_info);
            }
        }
        return $cnt;
        //return count($applied_exam_info);
    }

    // check whether applied exam date fall in same date of other exam date(Prafull)
    public function examdate($regnumber = null, $exam_code = null)
    {
        $flag       = 0;
        $today_date = date('Y-m-d');

        $applied_exam_date = $this->master_model->getRecords('subject_master', array(
            'exam_code'      => base64_decode($exam_code),
            'exam_date >='   => $today_date,
            'subject_delete' => '0',
        ));
        if (count($applied_exam_date) > 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                'regnumber'  => $regnumber,
                'pay_status' => '1',
            ), 'member_exam.exam_code');
            if (count($getapplied_exam_code) <= 0) {
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->where('bulk_isdelete', '0');
                $this->db->where('institute_id!=', '');
                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                    'regnumber'  => $regnumber,
                    'pay_status' => '2',
                ), 'member_exam.exam_code');
            }
            if (count($getapplied_exam_code) > 0) {
                foreach ($getapplied_exam_code as $exist_ex_code) {
                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array(
                        'exam_code'      => $exist_ex_code['exam_code'],
                        'exam_date >='   => $today_date,
                        'subject_delete' => '0',
                    ));
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
        //Remove below flag once RPE exam get over
        if (base64_decode($exam_code) != $this->config->item('examCodeJaiib') && base64_decode($exam_code) != $this->config->item('examCodeDBF') && base64_decode($exam_code) != $this->config->item('examCodeSOB') && base64_decode($exam_code) != $this->config->item('examCodeCaiib') && base64_decode($exam_code) != 62 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective63') && base64_decode($exam_code) != 64 && base64_decode($exam_code) != 65 && base64_decode($exam_code) != 66 && base64_decode($exam_code) != 67 && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective68') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective69') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective70') && base64_decode($exam_code) != $this->config->item('examCodeCaiibElective71') && base64_decode($exam_code) != 72) {
            $flag = 0;
        }
        return $flag;
    }

    /*//get applied exam name which is fall on same date(Prafull)
    public function get_alredy_applied_examname($regnumber=NULL,$exam_code=NULL)
    {
    $flag=0;
    $msg='';
    $today_date=date('Y-m-d');
    $this->db->select('subject_master.*,exam_master.description');
    $this->db->join('exam_master','exam_master.exam_code=subject_master.exam_code');
    $applied_exam_date=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>base64_decode($exam_code),'exam_date >='=>$today_date,'subject_delete'=>'0'));
    if(count($applied_exam_date) > 0)
    {
    $this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
    $this->db->join('exam_master','exam_master.exam_code=member_exam.exam_code');
    $getapplied_exam_code=$this->master_model->getRecords('member_exam',array('regnumber'=>$regnumber,'pay_status'=>'1'),'member_exam.exam_code,exam_master.description');
    if(count($getapplied_exam_code) >0)
    {
    foreach($getapplied_exam_code as $exist_ex_code)
    {
    $getapplied_exam_date=$this->master_model->getRecords('subject_master',array('exam_code'=>$exist_ex_code['exam_code'],'exam_date >='=>$today_date,'subject_delete'=>'0'));
    if(count($getapplied_exam_date) > 0)
    {
    foreach($getapplied_exam_date as $exist_ex_date)
    {
    foreach($applied_exam_date as $sel_ex_date)
    {
    if($sel_ex_date['exam_date']==$exist_ex_date['exam_date'])
    {
    $msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
    $flag=1;
    break;
    }
    }
    if($flag==1)
    {
    $msg="You have already applied for <strong>".$exist_ex_code['description']."</strong> falling on same day, So you can not apply for <strong>".$sel_ex_date['description']."</strong> examination.";
    break;
    }
    }
    }
    }
    }
    }
    return $msg;
    }*/

    // #--------- get applied exam name which is fall on same date(Prafull)
    public function get_alredy_applied_examname($regnumber = null, $exam_code = null)
    {
        $flag       = 0;
        $msg        = '';
        $today_date = date('Y-m-d');
        $this->db->select('subject_master.*,exam_master.description');
        $this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');
        $applied_exam_date = $this->master_model->getRecords('subject_master', array(
            'subject_master.exam_code' => base64_decode($exam_code),
            'exam_date >='             => $today_date,
            'subject_delete'           => '0',
        ));
        if (count($applied_exam_date) > 0) {
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                'regnumber'  => $regnumber,
                'pay_status' => '1',
            ), 'member_exam.exam_code,exam_master.description');
            // ## checking bulk applied ######
            if (count($getapplied_exam_code) <= 0) {
                $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code AND exam_activation_master.exam_period=member_exam.exam_period');
                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
                $this->db->where('bulk_isdelete', '0');
                $this->db->where('institute_id!=', '');
                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array(
                    'regnumber'  => $regnumber,
                    'pay_status' => '2',
                ), 'member_exam.exam_code,exam_master.description');
            }
            if (count($getapplied_exam_code) > 0) {
                foreach ($getapplied_exam_code as $exist_ex_code) {
                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array(
                        'exam_code'      => $exist_ex_code['exam_code'],
                        'exam_date >='   => $today_date,
                        'subject_delete' => '0',
                    ));
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
                    if ($flag == 1) {
                        break;
                    }
                }
            }
        }
        return $msg;
    }

    // #---------check pincode/zipcode alredy exist or not (prafull)-----------##
    public function checkpin()
    {
        $statecode = $_POST['statecode'];
        $pincode   = $_POST['pincode'];
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode,
            ));
            // echo $this->db->last_query();
            if ($prev_count == 0) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            echo 'false';
        }
    }

    // #---------Forcefully Update profile mesage to user(prafull)-----------##
    public function notification()
    {
        $msg         = '';
        $flag        = 1;
        $user_images = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'scannedphoto,scannedsignaturephoto,idproofphoto,declaration,mobile,email');
        if ((!file_exists('./uploads/photograph/' . $user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/' . $user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/' . $user_images[0]['idproofphoto']) || $user_images[0]['scannedphoto'] == '' || $user_images[0]['scannedsignaturephoto'] == '' || $user_images[0]['idproofphoto'] == '') && (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')))) {
            $flag = 0;
            $msg .= '<li>Your Photo/signature or ID proof are not available kindly go to Edit Profile and <a href="' . base_url() . 'Home/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';
        }
        if ($user_images[0]['mobile'] == '' || $user_images[0]['email'] == '') {
            $flag = 0;
            $msg .= '<li>
                Your email id or mobile number are not available kindly go to Edit Profile and <a href="' . base_url() . 'Home/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
        }
        if (validate_userdata($this->session->userdata('regnumber'))) {
            $flag = 0;
            $msg .= '<li>
                Please check all mandatory fields in profile <a href="' . base_url() . 'Home/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';
        }
        /*    if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) || ($user_images[0]['scannedphoto']=='' || $user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']!='')) || ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))
        {
        $flag=0;
        $msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'Home/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>
        <li>
        Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'Home/profile/">click here</a> to update the, then email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';
        }*/
        if ($flag) {
            redirect(base_url() . 'Home/dashboard');
        }
        $data = array(
            'middle_content' => 'member_notification',
            'msg'            => $msg,
        );
        $this->load->view('common_view', $data);
    }

    // #---print user edit profile (Prafull)
    ###GST Message
    public function GST()
    {
        $msg  = '<li>Please pay GST amount of Exam/Mem registration in order to apply for the exam.<a href="' . base_url() . 'GstRecovery/" target="new">click here</a> </li>';
        $data = array('middle_content' => 'member_notification', 'msg' => $msg);
        $this->load->view('common_view', $data);
    }

    public function printUser()
    {
        // $this->chk_session->checkphoto();
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'PG',
            ), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');
        $data          = array(
            'middle_content' => 'print_member_profile',
            'user_info'      => $user_info,
            'qualification'  => $qualification,
            'idtype_master'  => $idtype_master,
        );
        $this->load->view('common_view', $data);
    }

    // #--print user edit profile (Prafull)
    public function printexamdetails()
    {
        $state_place_of_work = $elective_subject_name = '';
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }
        // $this->chk_session->checkphoto();
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'PG',
            ), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }
        $exam_code  = base64_decode($this->session->userdata['examinfo']['excd']);
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');
        $this->db->where('elg_mem_o', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array(
            'member_exam.exam_code' => $exam_code,
            'regnumber'             => $this->session->userdata('regnumber'),
            'pay_status'            => '1',
        ));
        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', $exam_code);
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
        $this->db->where('exam_name', $exam_code);
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        // get state details
        $this->db->where('state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Home/dashboard/');
        }
        // get Elective Subeject name for CAIIB Exam
        $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array(
            'subject_code'   => $applied_exam_info['0']['elected_sub_code'],
            'subject_delete' => 0,
        ), 'subject_description');
        if (count($elective_sub_name_arr) > 0) {
            $elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
        }
        $data = array(
            'middle_content'        => 'print_member_applied_exam_details',
            'user_info'             => $user_info,
            'qualification'         => $qualification,
            'idtype_master'         => $idtype_master,
            'applied_exam_info'     => $applied_exam_info,
            'medium'                => $medium,
            'center'                => $center,
            'qualification'         => $qualification,
            'states'                => $states,
            'elective_subject_name' => $elective_subject_name,
        );
        $this->load->view('common_view', $data);
    }

    // download pdf (Prafull)
    public function downloadeditprofile()
    {
        $gender = $idtype = '';
        // $this->chk_session->checkphoto();
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'PG',
            ), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master = $this->master_model->getRecords('idtype_master', '', 'name');
        if (isset($idtype_master[0]['name'])) {
            $idtype = $idtype_master[0]['name'];
        }
        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        $userfinalstrname;
        if ($user_info[0]['gender'] == 'female') {
            $gender = 'Female';
        }
        if ($user_info[0]['gender'] == 'male') {
            $gender = 'Male';
        }
        if ($user_info[0]['qualification'] == 'U') {
            $memqualification = 'Under Graduate';
        }
        if ($user_info[0]['qualification'] == 'G') {
            $memqualification = 'Graduate';
        }
        if ($user_info[0]['qualification'] == 'P') {
            $memqualification = 'Post Graduate';
        }
        if ($user_info[0]['optnletter'] == 'Y') {
            $optnletter = 'Yes';
        }
        if ($user_info[0]['optnletter'] == 'N') {
            $optnletter = 'No';
        }
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
        $useradd = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'] . ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . ',' . $user_info[0]['state_name'] . $user_info[0]['pincode'];
        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
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
            <tr></tr>
            <tr><td style="text-align:center"><strong><h3></h3></strong></td></tr>
            <tr><br /></tr>
            <tr>
            <td colspan="4">
            </hr>
            <table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
            <tbody><tr>
            <td class="tablecontent2" width="51%">Membership No : </td>
            <td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
            <td class="tablecontent" rowspan="4" valign="top">
            <img src="' . base_url() . get_img_name($this->session->userdata('regnumber'), 'p') . '" height="100" width="100" >
            </td>
            </tr>
            <tr>
            <td class="tablecontent2">Password :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $decpass . '
            </td>
            </tr>
            <tr>
            <td class="tablecontent2">Full Name :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
            </td>
            </tr>
            <tr>
            <td class="tablecontent2">Name as to appear on Card :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['displayname'] . '</td>
            </tr>
            <tr>
            <td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
            <td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
            ' . wordwrap($useradd, 50, "<br />\n") . '          </td>
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
            <td class="tablecontent2">Aadhar Card Number :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . '</td>
            </tr>
            <tr>
            <td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy  :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $optnletter . '</td>
            </tr>



            <tr>
            <td class="tablecontent2">ID Proof  :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"> <img src="' .base_url() . get_img_name($this->session->userdata('regnumber'), 'pr'). '" height="100" width="100" >' . '</td>
            </tr>

            <tr>
            <td class="tablecontent2">Signature :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' .base_url() . get_img_name($this->session->userdata('regnumber'), 's'). '" height="100" width="100" >' . '</td>
            </tr>


            <tr>
            <td class="tablecontent2">Declaration :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap"> <img src="' .base_url() . get_img_name($this->session->userdata('regnumber'), 'declaration'). '" height="100" width="100" >' . '</td>
            </tr>

        
            ';
            $html .= '<tr>
            <td class="tablecontent2">Date :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">
            ' . date('d-m-Y h:i:s A') . '       </td>
            </tr>
            </tbody></table>
            </td>
            </tr>

            </tbody></table>';
        // this the the PDF filename that user will get to download
        $pdfFilePath = 'iibf' . '.pdf';
        // load mPDF library
        $this->load->library('m_pdf');
        // actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        // $pdf->SetHTMLHeader($header);
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

    // ##---------Download pdf(Prafull)-----------##
    public function pdf()
    {
        // $this->chk_session->checkphoto();
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
            Your application saved successfully.<br /><br /><strong>Your Membership No is</strong> ' . $this->session->userdata('regnumber') . ' <strong>and Your password is </strong>' . base64_decode($this->session->userdata('password')) . '<br /><br />Please note down your Membership No and Password for further reference.<br /> <br />You may print or save membership registration page for further reference.<br /><br />Please ensure proper Page Setup before printing.<br /><br />Click on Continue to print registration page.<br /><br />You can save system generated application form as PDF for future refence
            </div>
            </center>
            </div>
            </div>
            </div>
            </div>
          </div>
            </section>
            </div>';
        // this the the PDF filename that user will get to download
        $pdfFilePath = 'iibf' . '.pdf';
        // load mPDF library
        $this->load->library('m_pdf');
        // actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        // $pdf->SetHTMLHeader($header);
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

    // #---------------------- Public function exam pdf ##########
    public function exampdf()
    {
        $state_place_of_work = $elective_subject_name = $ID_Proof = '';
        // $this->chk_session->checkphoto();
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }
        $qualification = array();
        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');
        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');
        $this->db->join('state_master', 'state_master.state_code=member_registration.state');
        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');
        $this->db->where('institution_master.institution_delete', '0');
        $this->db->where('state_master.state_delete', '0');
        $this->db->where('designation_master.designation_delete', '0');
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else
        if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'PG',
            ), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master    = $this->master_model->getRecords('idtype_master', '', 'name');
        $username         = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
        $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
        if ($user_info[0]['gender'] == 'female') {
            $gender = 'Female';
        }
        if ($user_info[0]['gender'] == 'male') {
            $gender = 'Male';
        }
        if ($user_info[0]['qualification'] == 'U') {
            $memqualification = 'Under Graduate';
        }
        if ($user_info[0]['qualification'] == 'G') {
            $memqualification = 'Graduate';
        }
        if ($user_info[0]['qualification'] == 'P') {
            $memqualification = 'Post Graduate';
        }
        if ($user_info[0]['optnletter'] == 'Y') {
            $optnletter = 'Yes';
        }
        if ($user_info[0]['optnletter'] == 'N') {
            $optnletter = 'No';
        }
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
        $finalstr1 = str_replace("*", "<br />", $string1);
        $string2   = ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . '*' . $user_info[0]['state_name'] . ',' . $user_info[0]['pincode'];
        $finalstr2 = str_replace("*", ",<br />", $string2);
        $useradd   = preg_replace('#[\s]+#', ' ', $finalstr1 . $finalstr2);
        if (($this->session->userdata('examinfo') == '')) {
            redirect(base_url() . 'Home/dashboard/');
        }
        // $this->session->userdata['examinfo']['excd']='NTgw';
        $exam_code  = base64_decode($this->session->userdata['examinfo']['excd']);
        $today_date = date('Y-m-d');
        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');
        $this->db->where('elg_mem_o', 'Y');
        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=member_exam.exam_code');
        $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
        $applied_exam_info = $this->master_model->getRecords('member_exam', array(
            'member_exam.exam_code' => $exam_code,
            'regnumber'             => $this->session->userdata('regnumber'),
            'pay_status'            => '1',
        ));
        $this->db->where('medium_delete', '0');
        $this->db->where('exam_code', $exam_code);
        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);
        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');
        $this->db->where('exam_name', $exam_code);
        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);
        $this->db->where("center_delete", '0');
        $center = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        // get state details
        $this->db->where('state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        if (count($applied_exam_info) <= 0) {
            redirect(base_url() . 'Home/dashboard/');
        }
        // $month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');
        $month       = date('Y') . "-" . substr($applied_exam_info['0']['exam_month'], 4);
        $exam_period = date('F', strtotime($month)) . "-" . substr($applied_exam_info['0']['exam_month'], 0, -2);
        if ($applied_exam_info[0]['exam_mode'] == 'ON') {
            $mode = 'Online';
        } else
        if ($applied_exam_info[0]['exam_mode'] == 'OF') {
            $mode = 'Offline';
        }
        // get sate name for CAIIB/JAIIB examination
        if (count($states) > 0 && $applied_exam_info[0]['state_place_of_work'] != '') {
            foreach ($states as $srow) {
                if ($applied_exam_info[0]['state_place_of_work'] == $srow['state_code']) {
                    $state_place_of_work = $srow['state_name'];
                }
            }
        }
        // get Elective Subeject name for CAIIB Exam
        $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array(
            'subject_code'   => $applied_exam_info['0']['elected_sub_code'],
            'subject_delete' => 0,
        ), 'subject_description');
        if (count($elective_sub_name_arr) > 0) {
            $elective_subject_name = $elective_sub_name_arr[0]['subject_description'];
        }
        if (isset($idtype_master[0]['name'])) {
            $ID_Proof = $idtype_master[0]['name'];
        }
        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
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
            <tr></tr>
            <tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>
            <tr><br /></tr>
            <tr>
            <td colspan="4">
            </hr>
            <table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
            <tbody><tr>
            <td class="tablecontent2" width="51%">Membership No : </td>
            <td colspan="2" class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info[0]['regnumber'] . '</td>
            <td class="tablecontent" rowspan="4" valign="top">
            <img src="' . base_url() . get_img_name($this->session->userdata('regnumber'), 'p') . '" height="100" width="100" >
            </td>
            </tr>

            <tr>
            <td class="tablecontent2">Full Name :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $userfinalstrname . '
            </td>
            </tr>
            <tr>
            <td class="tablecontent2">Name as to appear on Card :</td>
            <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['displayname'] . '</td>
            </tr>
            <tr>
            <td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
            <td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
            ' . wordwrap($useradd, 50, "<br />\n") . '          </td>
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
            <td class="tablecontent2">Aadhar Card Number :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['aadhar_card'] . '</td>
            </tr>
            <tr>
            <td class="tablecontent2">ID Proof :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $ID_Proof . '</td>
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
            </tr>';
        if ($applied_exam_info[0]['elected_sub_code'] != 0) {
            $html .= '<tr>
                <td class="tablecontent2">Elective Subject Name :</td>
                <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $elective_subject_name . '</td>
                </tr>';
        }
        if ($applied_exam_info[0]['place_of_work'] != '' && $applied_exam_info[0]['state_place_of_work'] != '' && $applied_exam_info[0]['pin_code_place_of_work'] != '') {
            $html .= '
                <tr>
                <td class="tablecontent2">Place of Work :</td>
                <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $applied_exam_info[0]['place_of_work'] . '</td>
                </tr>
                <tr>
                <td class="tablecontent2">State (Place of Work) :</td>
                <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $state_place_of_work . '</td>
                </tr>
                <tr>
                <td class="tablecontent2">Pin Code (Place of Work) :</td>
                <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $applied_exam_info[0]['pin_code_place_of_work'] . '</td>
                </tr>';
        }
        $html .= '<tr>
            <td class="tablecontent2">Date :</td>
            <td colspan="3" class="tablecontent2" nowrap="nowrap">
            ' . date('d-m-Y h:i:s A') . '       </td>
            </tr>
            </tbody></table>
            </td>
            </tr>

            </tbody></table>';
        // this the the PDF filename that user will get to download
        $pdfFilePath = 'exam' . '.pdf';
        // load mPDF library
        $this->load->library('m_pdf');
        // actually, you can pass mPDF parameter on this load() function
        $pdf = $this->m_pdf->load();
        // $pdf->SetHTMLHeader($header);
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

    // #------- check eligible user----------##
    public function checkusers($examcode = null)
    {
        $flag = 0;
        if ($examcode != null) {
            $exam_code = array(
                33,
                47,
                51,
                52,
            );
            if (in_array($examcode, $exam_code)) {
                $this->db->where_in('eligible_master.exam_code', $exam_code);
                $valid_member_list = $this->master_model->getRecords('eligible_master', array(
                    'eligible_period' => '417',
                    'member_type'     => 'O',
                ), 'member_no');
                if (count($valid_member_list) > 0) {
                    foreach ($valid_member_list as $row) {
                        $memberlist_arr[] = $row['member_no'];
                    }
                    if (in_array($this->session->userdata('regnumber'), $memberlist_arr)) {
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

    // call back for checkpin
    public function check_checkpin($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode,
            ));
            // echo $this->db->last_query();
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin', $str);
                return false;
            } else {
                $this->form_validation->set_message('error', "");
            }

            {
                return true;
            }
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin', $str);
            return false;
        }
    }

    public function check_mobileduplication($mobile)
    {
        if ($mobile != "") {
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'mobile'           => $mobile,
                'regid !='         => $this->session->userdata('regid'),
                'isactive'         => '1',
                'registrationtype' => $this->session->userdata('memtype'),
            ));
            // echo $this->db->last_query();
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
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'email'            => $email,
                'regid !='         => $this->session->userdata('regid'),
                'isactive'         => '1',
                'registrationtype' => $this->session->userdata('memtype'),
            ));
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

        public function check_bank_bc_id_no_duplication($ippb_emp_id, $name_of_bank_bc)
        {
            if ($ippb_emp_id != "" && $ippb_emp_id != "0" && $name_of_bank_bc != '') {
                $this->db->where("name_of_bank_bc",$name_of_bank_bc);
                $this->db->where("ippb_emp_id",$ippb_emp_id);
                $this->db->where("regnumber !=",$this->session->userdata('regnumber'));
                $prev_count = $this->master_model->getRecordCount('member_registration', array('isactive' => '1'));
                //echo $this->db->last_query();
                if ($prev_count > 0) {
                    $str = 'Bank BC ID No Already Exists for selected Name of Bank.';
                    $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
                    return false;} else {
                    $this->form_validation->set_message('error', "");
                }

                {return true;}
            } else if ($ippb_emp_id == "0") {
                $str = 'Bank BC ID No field is required.';
                $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
                return false;
            } else if ($name_of_bank_bc == "") {
                $str = 'Name of Bank field is required.';
                $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
                return false;
            } else {
                $str = 'Bank BC ID No & Name of Bank field is required.';
                $this->form_validation->set_message('check_bank_bc_id_no_duplication', $str);
                return false;
            }
        }
        public function check_date_of_joining_bc_validation($date_of_commenc_bc, $exam_date_exist)
        {
            if ($date_of_commenc_bc != "" && $exam_date_exist != '') {
                  
                  $jdate = date("Y-m-d",strtotime($date_of_commenc_bc));  
                  $ninemonthDate = date("Y-m-d",strtotime("+9 month", strtotime($jdate)));  
                  $beforeninemonthDate = date("Y-m-d",strtotime("-9 month", strtotime($exam_date_exist)));  
                  $chk_date = "2024-03-31";  
                  $check_start_date = "2023-07-01";
                  $check_end_date = "2024-03-31";  
                  //echo $jdate." > ".$chk_date;
                  //echo "<br>".$ninemonthDate." > ".$exam_date_exist;die;
                  /*if($jdate > $chk_date)
                  {
                    $str = 'Date of joining should not be greater than 31-March-2024';
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false;
                  }
                  else if( $jdate < $beforeninemonthDate )
                  {
                    $str = 'Commencement of operations / joining as BC to be within 9 months from the date of examination.';
                    //$str = 'Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '.date("d-M-Y",strtotime($exam_date_exist)).', your Date of Joining should be on or before '.date("d-M-Y",strtotime($beforeninemonthDate)).'.';
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false; 
                  }*/
                  if ($jdate < $check_start_date)
                  {
                    $str = 'Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.'; 
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false;
                  }
                  else if ($jdate > $check_end_date)
                  {
                    $str = 'Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.'; 
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false;
                  }
                  else if( $jdate > $exam_date_exist )
                  {
                    $str = 'Only Agents who have joined Bank as BC between 01 July 2023 to 31 March 2024 are eligible.';
                    /*$str = 'Please select your Date of Joining within 9 months (270 days) from the date of examination.<br> Your Examination Date is '.date("d-M-Y",strtotime($exam_date_exist)).', your Date of Joining should be on or before '.date("d-M-Y",strtotime($beforeninemonthDate)).'.';*/
                    $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                    return false; 
                  }
                  else {
                    $this->form_validation->set_message('error', ""); 
                  } 
                  
                  {return true;}
            } else {
                $str = 'Date of joining field is required.';
                $this->form_validation->set_message('check_date_of_joining_bc_validation', $str);
                return false;
            }
        }

        function empidproofphoto_upload()
        {
            if ($_FILES['empidproofphoto']['size'] != 0) {
                return true;
            } else {
                $this->form_validation->set_message('empidproofphoto_upload', "No Employee Id proof file selected");
                return false;
            }
        }

    /* Get scribe drop down code added by pooja*/
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

    public function set_jaiib_elsub_cnt()
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
    // get fee as per the cenrer selection (Prafull)
    //    public function getFee()
    //    {
    //    $centerCode= $_POST['centerCode'];
    //    $eprid=$_POST['eprid'];
    //    $excd=$_POST['excd'];
    //    $grp_code=$_POST['grp_code'];
    //    $memcategory=$this->session->userdata('memtype');
    // Prameter should be in following format
    // 1) Center Code 2)Exam period 3)exam code 4)Group ccode 5) member type (eg, '495','117','8','B1','O')
    // echo getExamFee($centerCode,$eprid,$excd,$grp_code,$memcategory);
    /*if($centerCode!="" && $eprid!="" && $excd!="" && $grp_code!="")
    {
    $getstate=$this->master_model->getRecords('center_master',array('exam_name'=>base64_decode($excd),'center_code'=>$centerCode,'exam_period'=>$eprid,'center_delete'=>'0'));
    if(count($getstate) > 0)
    {
    if($grp_code=='')
    {
    $grp_code='B1';
    }
    $today_date=date('Y-m-d');
    // $today_date='2017-08-15';
    // $this->db->where('('."'".$today_date."'". ' BETWEEN DATE_FORMAT(`fr_date`,"%y-%m-%d") AND DATE_FORMAT(`to_date`, "%y-%m-%d"))');
    $this->db->where("('$today_date' BETWEEN fr_date AND `to_date`)");
    $getfees=$this->master_model->getRecords('fee_master',array('exam_code'=>base64_decode($excd),'member_category'=>$this->session->userdata('memtype'),'exam_period'=>$eprid,'group_code'=>$grp_code));
    // echo $this->db->last_query();exit;
    if(count($getfees) > 0)
    {
    if($getstate[0]['state_code']=='MAH')
    {
    echo $getfees[0]['cs_tot'];
    }
    else
    {
    echo $getfees[0]['igst_tot'];
    }
    }
    }
    }*/
    //    exit;
    //    }
    /*public function checkseat()
    {
    if(isset($_POST['btnSubmit']))
    {
    $sel_center=$this->input->post('sel_center');
    $sel_exam=$this->input->post('sel_exam');
    $sel_venue=$this->input->post('sel_venue');
    $sel_date=$this->input->post('sel_date');
    $sel_time=$this->input->post('sel_time');
    $ex_prd=$this->input->post('ex_prd');
    $sel_subject=$this->input->post('sel_subject');
    // $last_id = $CI->master_model->insertRecord('seat_allocation', $insert_data, true);
    echo getseat($sel_exam,$sel_center,$sel_venue,$sel_date,$sel_time,$ex_prd,$sel_subject,'3');
    exit;
    }
    $data=array('middle_content'=>'checkseat');
    $this->load->view('common_view',$data);
    }*/

    public function getpdf()
    {

        $file = 'http://iibf.teamgrowth.net/uploads/put_file/sample.pdf';

        $file_cont = file_get_contents($file);

        header('Content-Type: application/pdf');
        header('Content-Length:' . strlen($file_cont));

        header('Content-disposition: inline; filename=cache.pdf');
        //file_put_contents('../public_html/uploads/put_file/testppp.pdf',$file);
        //header('Content-Type: application/pdf');
        header('Content-Transfer-Encoding: Binary');
        //    header('Content-disposition: inline; filename=testppp.pdf');
        //echo base64_decode($file);
        echo $file_cont;

    }

    //Function to check valid exam for member
    public function check_valid_exam_for_member($decode_exam_code){
        // Benchmark Disability Check
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ), 'associatedinstitute');
         

        //START CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
        $valid_ExamCode_InstituteCode_arr = $this->config->item('VALID_EXAMCODE_INSTITUTECODE_ARR');  
        $associatedinstitute = (isset($user_info) && $user_info[0]['associatedinstitute'] != "" ? $user_info[0]['associatedinstitute'] : '');
        if(count($valid_ExamCode_InstituteCode_arr) > 0){
          foreach($valid_ExamCode_InstituteCode_arr as $res_code){
            $inst_code_arr = $res_code["inst_code_arr"];
            $exam_code_arr = $res_code["exam_code_arr"]; 
            //$decode_exam_code = base64_decode($this->input->get('excode2')); 
            if(in_array($decode_exam_code,$exam_code_arr)){ 
              if(!in_array($associatedinstitute,$inst_code_arr)){
                 //$this->session->set_flashdata('error_invalide_exam_selection', "This certificate course is applicable for SBI staff only. In case you have changed your organisation to SBI, kindly update the bank name in your membership profile");
                 //redirect(base_url() . 'Home/examlist');
                 return 1;
                 //redirect(base_url() . 'Home/accessdenied/');
              }
            }  
          }
        }
        //END CODE TO CHECK VALIDE EXAM CODE ACCORDING TO INSTITUTE CODE ADDED BY ANIL ON 11 Sep 2023
    }
    
    //callback to validate photo
    function transfer_letter_upload()
    {
        if ($_FILES['transfer_letter']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('transfer_letter_upload', "No Transfer letter file selected");
            return false;
        }
    }

    //priyanka d - 08-aug-24 >> change center of upcoming exam 
    public function change_center()
    {
        
        $user_info   = $this->master_model->getRecords('member_registration', array(
            'regid'     => $this->session->userdata('regid'),
            'regnumber' => $this->session->userdata('regnumber'),
            'isactive'  => '1',
        ));
        $today_date = date('Y-m-d');
        $exam_list  = array();
        $examcodes  = array($this->config->item('examCodeJaiib'),$this->config->item('examCodeCaiib'),$this->config->item('examCodeCaiibElective63'),'65',$this->config->item('examCodeCaiibElective68'),$this->config->item('examCodeCaiibElective69'),$this->config->item('examCodeCaiibElective70'),$this->config->item('examCodeCaiibElective71'));//'21', '42'
        $this->db->where('elg_mem_o', 'Y');
        $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
        $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
        $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
        $this->db->where('medium_delete', '0');
        $this->db->where("misc_master.misc_delete", '0');            
        $this->db->where("exam_activation_master.exam_activation_delete", "0");
        $this->db->where_in('exam_master.exam_code', $examcodes);
        $this->db->where('subject_master.exam_date > ',$today_date);
        $this->db->group_by('medium_master.exam_code');
        $exam_list = $this->master_model->getRecords('exam_master');
        
    
        $registrations_till_date = strtotime($exam_list[0]['exam_to_date']);
        $centerchange_last_date = strtotime("+7 day", $registrations_till_date);
        $centerchange_last_date = date('Y-m-d', $centerchange_last_date);
        //if($today_date > $centerchange_last_date || $today_date < $registrations_till_date) 
        {
            $message = '<div style="color:#F00">Center change Feature is not available</div>';
            $data    = array(
                'middle_content'    => 'not_eligible',
                'check_eligibility' => $message,
            );
            //return $this->load->view('common_view', $data);
        }
        
            //return $this->load->view('common_view', $data);
        

        $this->db->where('regnumber', $this->session->userdata('regnumber'));
        $this->db->join('exam_master', 'exam_center_changes.exam_code=exam_master.exam_code');
        $previous_records = $this->master_model->getRecords('exam_center_changes');
        $message = '';
        if(isset($_POST) && !empty($_POST)) {
         //  echo'<pre>';print_r($_POST);exit;
            $this->form_validation->set_rules('exam_code', 'Exam', 'trim|required|xss_clean');
            $this->form_validation->set_rules('exam_period', 'Exam', 'trim|required|xss_clean');
            $this->form_validation->set_rules('center_code', 'Centre Code', 'required|xss_clean');
            $this->form_validation->set_rules('transfer_letter', 'Transfer order letter', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[50]|callback_transfer_letter_upload');
            if ($this->form_validation->run() == TRUE) {
                
                $exam_code = $this->input->post('exam_code');
                $exam_period = $this->input->post('exam_period');
                $center_code = $this->input->post('center_code');
                $center_name = $this->input->post('center_name');

                $this->db->where('status',1);
                $check_request_exist = $this->master_model->getRecords('exam_center_changes', array('regnumber' => $this->session->userdata('regnumber'), 'exam_code' => $exam_code, 'exam_period' => $exam_period,'status'=>1));
                // echo $this->db->last_query();exit;
                if(count($check_request_exist)) {
                    $data    = array(
                        'middle_content'    => 'not_eligible',
                        'check_eligibility' => 'You have already requested to center change for Selected Exam',
                    );
                    return $this->load->view('common_view', $data);
                }

                $this->db->where('exam_date > ',$today_date);
                $this->db->where('admitcard_image != ','');
                $check_admitcard_exist = $this->master_model->getRecords('admit_card_details', array('mem_mem_no' => $this->session->userdata('regnumber'), 'exm_cd' => $exam_code, 'remark' => 1));
            // echo $this->db->last_query();exit;
                if(count($check_admitcard_exist)) {
        
                }
                else {
                    $data    = array(
                        'middle_content'    => 'not_eligible',
                        'check_eligibility' => 'You are not applied for Selected Exam',
                    );
                    return $this->load->view('common_view', $data);
                }

                $this->db->where('regnumber', $this->session->userdata('regnumber'));
                $this->db->where("exam_code", $exam_code);     
                $this->db->where("exam_period", $exam_period);     
                $record_exist = $this->master_model->getRecords('exam_center_changes');

                $transfer_letter_file='';
                if (isset($_FILES['transfer_letter']['name']) && ($_FILES['transfer_letter']['name'] != '')) {
                    $img = "transfer_letter";
                    $tmp_nm = strtotime($today_date) . rand(0, 100);
                    $new_filename = 'transfer_letter_' . $tmp_nm;
                    $config = array(
                        'upload_path' => './uploads/transfer_letters',
                        'allowed_types' => 'jpg|jpeg',
                        'file_name' => $new_filename,
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['transfer_letter']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $transfer_letter_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/transfer_letter/" . $transfer_letter_file;
                        } else {
                            $this->session->set_flashdata('error', 'transfer_letter :' . $this->upload->display_errors());
                            redirect(base_url('Home/change_center/'));
                        }
                    } else {
                        $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                        redirect(base_url('Home/change_center/'));
                    }
                }
                
                $user_data = array(
                            'regnumber'			=> $this->session->userdata('regnumber'),
                            'exam_code'		=> $exam_code,
                            'exam_period'		=> $exam_period,
                            'transfer_letter'		=> $transfer_letter_file,
                            'center_code'		=> $center_code,
                            'center_name'		=> $center_name,
                            'status'=>2,
                            'ip_address'=>get_ip_address()
                );
                if(count($record_exist)>0) {
                    if($record_exist['status']!=1) {
                        $user_data['updated_on'] = date('Y-m-d H:i:s');

                        $this->master_model->updateRecord('exam_center_changes', $user_data, array('id' => $record_exist[0]['id']));
                        $this->session->set_flashdata('success', 'Your request is submitted successfully');
                        redirect(base_url('Home/change_center/'));
                     //   echo $this->db->last_query();exit;
                    }
                    else {
                        $this->session->set_flashdata('error', 'Your request is already Approved');
                        redirect(base_url('Home/change_center/'));
                    }
                    
                }
                else {
                   // echo'<pre>';print_r($_FILES);exit;
                    $pt_id = $this->master_model->insertRecord('exam_center_changes', $user_data, true);
                    $this->session->set_flashdata('success', 'Your request is submitted successfully');
                    redirect(base_url('Home/change_center/'));
                }
            }
            else {

            }

        }
        $data    = array(
            'middle_content'    => 'change_center',
            'exam_list' => $exam_list,
            'user_info'=>$user_info,
            'previous_records'=>$previous_records
        );
        $this->load->view('common_view', $data);
    }

    public function getCentersByExamCode() {
        $exam_code = $_POST['exam_code'];

        $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=center_master.exam_name AND exam_activation_master.exam_period=center_master.exam_period');
        $this->db->where('center_master.exam_name', $exam_code);
        $this->db->where("center_delete", '0');
        $centers = $this->master_model->getRecords('center_master', '', '', array(
            'center_name' => 'ASC',
        ));
        $html ='<option value="">Select</option>';
        foreach($centers as $center) {
            $html .='<option center_name="'.$center['center_name'].'" value="'.$center['center_code'].'">'.$center['center_name'].'</option>';
        }
        echo $html;
    }

    public function checkPreviousEntries() {
        $exam_code = $_POST['exam_code'];

       
        $this->db->where('exam_activation_master.exam_code', $exam_code);
       
        $exam_details = $this->master_model->getRecords('exam_activation_master');
        

        $this->db->where('regnumber', $this->session->userdata('regnumber'));
        $this->db->where('exam_code', $exam_code);
        $this->db->where('exam_period', $exam_details[0]['exam_period']);
        $this->db->where('status', 1);        
        
        $previous_records = $this->master_model->getRecords('exam_center_changes');
       // echo $this->db->last_query();exit;
        if(count($previous_records)>0) {
            echo'true';
        }
        else echo 'false';
    }

    public function accessdenied_not_old_bcbf_mem()
    {
        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. <strong>For any queries contact zonal office</strong>.</div>';
        $data    = array('middle_content' => 'nonmember/not_eligible', 'check_eligibility' => $message);
        $this->load->view('nonmember/nm_common_view', $data);
    }

    public function accessdenied_already_apply()
    {
        $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => base64_decode($this->session->userdata['examinfo']['excd']), 'misc_master.misc_delete' => '0'), 'exam_month');
        //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');
        $month            = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);
        $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);
        $message          = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.period. Hence you need not apply for the same.';   
         
        $data    = array('middle_content' => 'not_eligible', 'check_eligibility' => $message);
        $this->load->view('common_view', $data);
    }


}
