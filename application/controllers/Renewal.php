<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class Renewal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->helper('renewal_invoice_helper');
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
        $flag       = 1;
        $var_errors = '';
        $valcookie  = register_get_cookie();
        if ($valcookie) {
            $regid     = $valcookie;
            $checkuser = $this->master_model->getRecords('member_registration', array(
                'regid'        => $regid,
                'regnumber !=' => '',
                'isactive !='  => '0',
            ));
            if (count($checkuser) > 0) {
                delete_cookie('regid');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                    'ref_id' => $regid,
                    'status' => '2',
                ));
                if (count($checkpayment) > 0) {
                    $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
                    $current_time = date("Y-m-d H:i:s");
                    if (strtotime($current_time) <= strtotime($endTime)) {
                        $flag = 0;
                    } else {
                        delete_cookie('regid');
                    }
                } else {
                    $flag = 1;
                    delete_cookie('regid');
                }
            }
        }
        if ($this->session->userdata('enduserinfo')) {
            $this->session->unset_userdata('enduserinfo');
        }
        $selectedRecord = array();
        if (isset($_POST['btnGetDetails'])) {
            $selectedMemberId = $_POST['regnumber'];
            if ($selectedMemberId != '') {
                $memRegQry     = $this->db->query("SELECT regnumber FROM member_registration WHERE regnumber = '" . $selectedMemberId . "' AND isactive = '1'");
                $checkMemberNo = $memRegQry->row();
                //echo $this->db->last_query(); die;
                if (empty($checkMemberNo)) {
                    $renewalQry     = $this->db->query("SELECT * FROM renewal_eligible_master WHERE regnumber = '" . $selectedMemberId . "'  LIMIT 1 ");
                    $selectedRecord = $renewalQry->row_array();

                    if (empty($selectedRecord)) {
                        $selectedRecord = array(
                            // "msg" => "Please Enter Valid Membership No."
                        );
                    }
                } else {
                    $selectedRecord = array(
                        "msg" => "( " . $selectedMemberId . " ) This Membership No. is already exists. Please Enter Valid Number",
                    );
                }
            } else {
                $selectedRecord = array(
                    "msg" => "The Membership No. field is required.",
                );
            }
        } else {
            $scannedphoto_file         = $scannedsignaturephoto_file         = $idproofphoto_file         = $password         = $var_errors         = '';
            $data['validation_errors'] = '';
            if (isset($_POST['btnSubmit'])) {
                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = $declaration_file = '';
                $this->form_validation->set_rules('regnumber', 'Membership No', 'trim|required|xss_clean');
                $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
                $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[75]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|max_length[35]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1[Addressline1]');
                $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
                if ($this->input->post('state') != '') {
                    $state = $this->input->post('state');
                }
                $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');
                $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
                $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
                $this->form_validation->set_rules('optedu', 'Qualification', 'trim|required|xss_clean');
                if (isset($_POST['middlename'])) {
                    $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
                }
                if (isset($_POST['lastname'])) {
                    $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
                }
                if (isset($_POST['optedu']) && $_POST['optedu'] == 'U') {
                    $this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required|xss_clean');
                } else if (isset($_POST['optedu']) && $_POST['optedu'] == 'G') {
                    $this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required|xss_clean');
                } else if (isset($_POST['optedu']) && $_POST['optedu'] == 'P') {
                    $this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required|xss_clean');
                }
                if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                    $this->form_validation->set_rules('addressline2', 'Addressline2', 'trim|max_length[30]|required|callback_address1[Addressline2]|xss_clean');
                }
                if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                    $this->form_validation->set_rules('addressline3', 'Addressline3', 'trim|max_length[30]|required|callback_address1[Addressline3]|xss_clean');
                }
                if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                    $this->form_validation->set_rules('addressline4', 'Addressline4', 'trim|max_length[30]|required|callback_address1[Addressline4]|xss_clean');
                }
                if ($this->input->post('state_pr') != '') {
                    $state_pr = $this->input->post('state_pr');
                }
                $this->form_validation->set_rules('addressline1_pr', 'Permanent Addressline1', 'trim|max_length[30]|required|xss_clean|callback_address1Permanent[Addressline1]');
                $this->form_validation->set_rules('district_pr', 'District', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('city_pr', 'City', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('state_pr', 'State', 'trim|required|xss_clean');
                $this->form_validation->set_rules('pincode_pr', 'Pincode/Zipcode', 'trim|required|numeric|xss_clean|callback_check_checkpin_permanant[' . $state_pr . ']');
                if (isset($_POST['addressline2_pr']) && $_POST['addressline2_pr'] != '') {
                    $this->form_validation->set_rules('addressline2_pr', 'Permanent Addressline2', 'trim|max_length[30]|required|callback_address1Permanent[Addressline2]|xss_clean');
                }
                if (isset($_POST['addressline3_pr']) && $_POST['addressline3_pr'] != '') {
                    $this->form_validation->set_rules('addressline3_pr', 'Permanent Addressline3', 'trim|max_length[30]|required|callback_address1Permanent[Addressline3]|xss_clean');
                }
                if (isset($_POST['addressline4_pr']) && $_POST['addressline4_pr'] != '') {
                    $this->form_validation->set_rules('addressline4_pr', 'Permanent Addressline4', 'trim|max_length[30]|required|callback_address1Permanent[Addressline4]|xss_clean');
                }
                if (isset($_POST['stdcode']) && $_POST['stdcode'] != '') {
                    $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');
                }
                if (isset($_POST['phone']) && $_POST['phone'] != '') {
                    $this->form_validation->set_rules('phone', ' Phone No', 'trim|required|numeric|xss_clean');
                }
                $this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('office', 'Branch/Office', 'trim|required|alpha_numeric_spaces|xss_clean');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required|xss_clean');
                $this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');
                $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');
                $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');
                $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');
                // $this->form_validation->set_rules('idproof', 'Id Proof', 'trim|required|xss_clean');
                /*if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {
                if ($this->input->post('aadhar_card')) {
                $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');
                }
                } else {
                $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|required|max_length[12]|numeric|xss_clean|callback_check_aadhar');
                }*/
                $this->form_validation->set_rules('declarationform', 'Declaration Form', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_declarationform_upload');
                if ($this->input->post('aadhar_card') != '') {
                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|max_length[12]|numeric|xss_clean|callback_check_aadhar');
                }
                $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
                $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
                $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

                $this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'required|xss_clean');

                if ($this->form_validation->run() == true) {
                    $outputphoto1               = $outputsign1               = $outputsign1               = '';
                    $scannedphoto_file          = '';
                    $scannedsignaturephoto_file = '';
                    $idproof_file               = '';
                    $declaration_file           = '';
                    // ajax response -
                    $resp = array(
                        'success' => 0,
                        'error'   => 0,
                        'msg'     => '',
                    );
                    $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputsign1 = '';
                    $this->session->unset_userdata('enduserinfo');
                    $eduqual1 = $eduqual2 = $eduqual3 = '';
                    if ($_POST['optedu'] == 'U') {
                        $eduqual1 = $_POST["eduqual1"];
                    } else if ($_POST['optedu'] == 'G') {
                        $eduqual2 = $_POST["eduqual2"];
                    } else if ($_POST['optedu'] == 'P') {
                        $eduqual3 = $_POST["eduqual3"];
                    }
                    $date = date('Y-m-d h:i:s');
                    //Generate dynamic photo
                    $input = $_POST["hiddenphoto"];
                    if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                        $img          = "scannedphoto";
                        $tmp_nm       = strtotime($date) . rand(0, 100);
                        $new_filename = 'photo_' . $tmp_nm;
                        $config       = array(
                            'upload_path'   => './uploads/photograph',
                            'allowed_types' => 'jpg|jpeg|',
                            'file_name'     => $new_filename,
                        );
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                        if ($size) {
                            if ($this->upload->do_upload($img)) {
                                $dt                = $this->upload->data();
                                $file              = $dt['file_name'];
                                $scannedphoto_file = $dt['file_name'];
                                $outputphoto1      = base_url() . "uploads/photograph/" . $scannedphoto_file;
                            } else {
                                $var_errors .= $this->upload->display_errors();
                                //$data['error']=$this->upload->display_errors();
                            }
                        } else {
                            $var_errors .= 'The filetype you are attempting to upload is not allowed';
                        }
                    }
                    $inputsignature = $_POST["hiddenscansignature"];
                    if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                        $img          = "scannedsignaturephoto";
                        $tmp_signnm   = strtotime($date) . rand(0, 100);
                        $new_filename = 'sign_' . $tmp_signnm;
                        $config       = array(
                            'upload_path'   => './uploads/scansignature',
                            'allowed_types' => 'jpg|jpeg|',
                            'file_name'     => $new_filename,
                        );
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                        if ($size) {
                            if ($this->upload->do_upload($img)) {
                                $dt                         = $this->upload->data();
                                $scannedsignaturephoto_file = $dt['file_name'];
                                $outputsign1                = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;
                            } else {
                                $var_errors .= $this->upload->display_errors();
                                //$data['error']=$this->upload->display_errors();
                            }
                        } else {
                            $var_errors .= 'The filetype you are attempting to upload is not allowed';
                        }
                    }
                    // generate dynamic id proof
                    $inputidproofphoto = $_POST["hiddenidproofphoto"];
                    if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                        $img              = "idproofphoto";
                        $tmp_inputidproof = strtotime($date) . rand(0, 100);
                        $new_filename     = 'idproof_' . $tmp_inputidproof;
                        $config           = array(
                            'upload_path'   => './uploads/idproof',
                            'allowed_types' => 'jpg|jpeg|',
                            'file_name'     => $new_filename,
                        );
                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
                        if ($size) {
                            if ($this->upload->do_upload($img)) {
                                $dt             = $this->upload->data();
                                $idproof_file   = $dt['file_name'];
                                $outputidproof1 = base_url() . "uploads/idproof/" . $idproof_file;
                            } else {
                                $var_errors .= $this->upload->display_errors();
                                //$data['error']=$this->upload->display_errors();
                            }
                        } else {
                            $var_errors .= 'The filetype you are attempting to upload is not allowed';
                        }
                    }

                    // generate declaration form by pratibha borse
                    $inputdeclarationform = $_POST["hiddendeclarationform"];

                    if (isset($_FILES['declarationform']['name']) && ($_FILES['declarationform']['name'] != '')) {
                        $img                  = "declarationform";
                        $tmp_declaration_form = strtotime($date) . rand(0, 100);
                        $new_filename         = 'declaration_' . $tmp_declaration_form;
                        $config               = array('upload_path' => './uploads/declaration',
                            'allowed_types'                             => 'jpg|jpeg|',
                            'file_name'                                 => $new_filename);

                        $this->upload->initialize($config);
                        $size = @getimagesize($_FILES['declarationform']['tmp_name']);
                        if ($size) {
                            if ($this->upload->do_upload($img)) {
                                $dt               = $this->upload->data();
                                $declaration_file = $dt['file_name'];
                                $declaration_form = base_url() . "uploads/declaration/" . $declaration_file;
                            } else {
                                $var_errors .= $this->upload->display_errors();
                                //$data['error']=$this->upload->display_errors();
                            }
                        } else {
                            $var_errors .= 'The filetype you are attempting to upload is not allowed';
                        }

                    }
                    $dob1        = $_POST["dob1"];
                    $dob         = str_replace('/', '-', $dob1);
                    $dateOfBirth = date('Y-m-d', strtotime($dob));
                    $doj1        = $_POST["doj1"];
                    $doj         = str_replace('/', '-', $doj1);
                    $dateOfJoin  = date('Y-m-d', strtotime($doj));
                    if ($scannedphoto_file != '' && $idproof_file != '' && $scannedsignaturephoto_file != '' && $declaration_file != '') {
                        $user_data = array(
                            'firstname'             => $_POST["firstname"],
                            'sel_namesub'           => $_POST["sel_namesub"],
                            'addressline1'          => $_POST["addressline1"],
                            'addressline2'          => $_POST["addressline2"],
                            'addressline3'          => $_POST["addressline3"],
                            'addressline4'          => $_POST["addressline4"],
                            'city'                  => $_POST["city"],
                            'code'                  => trim($_POST["code"]),
                            'designation'           => $_POST["designation"],
                            'district'              => substr($_POST["district"], 0, 30),
                            'dob'                   => $dateOfBirth,
                            'doj'                   => $dateOfJoin,
                            'eduqual'               => $_POST["eduqual"],
                            'eduqual1'              => $eduqual1,
                            'eduqual2'              => $eduqual2,
                            'eduqual3'              => $eduqual3,
                            'email'                 => $_POST["email"],
                            'gender'                => $_POST["gender"],
                            'idNo'                  => '',
                            'idproof'               => $_POST["idproof"],
                            'institution'           => trim($_POST["institutionworking"]),
                            'lastname'              => $_POST["lastname"],
                            'middlename'            => $_POST["middlename"],
                            'mobile'                => $_POST["mobile"],
                            'nameoncard'            => $_POST["nameoncard"],
                            'office'                => $_POST["office"],
                            'optedu'                => $_POST["optedu"],
                            'optnletter'            => $_POST["optnletter"],
                            'phone'                 => $_POST["phone"],
                            'pincode'               => $_POST["pincode"],
                            'state'                 => $_POST["state"],
                            'stdcode'               => $_POST["stdcode"],
                            'scannedphoto'          => $outputphoto1,
                            'scannedsignaturephoto' => $outputsign1,
                            'idproofphoto'          => $outputidproof1,
                            'declarationform'       => $declaration_form,
                            'photoname'             => $scannedphoto_file,
                            'signname'              => $scannedsignaturephoto_file,
                            'idname'                => $idproof_file,
                            'declaration'           => $declaration_file,
                            'aadhar_card'           => $_POST['aadhar_card'],
                            'addressline1_pr'       => $_POST["addressline1_pr"],
                            'addressline2_pr'       => $_POST["addressline2_pr"],
                            'addressline3_pr'       => $_POST["addressline3_pr"],
                            'addressline4_pr'       => $_POST["addressline4_pr"],
                            'city_pr'               => $_POST["city_pr"],
                            'district_pr'           => substr($_POST["district_pr"], 0, 30),
                            'pincode_pr'            => $_POST["pincode_pr"],
                            'state_pr'              => $_POST["state_pr"],
                            'regnumber'             => $_POST["regnumber"], // User Entered Member No
                            'registrationtype'      => 'O', //$_POST['registrationtype']
                            'bank_emp_id'           => $_POST["bank_emp_id"],
                        );
                        $this->session->set_userdata('enduserinfo', $user_data);
                        $this->form_validation->set_message('error', "");
                        redirect(base_url() . 'Renewal/preview');
                    } else {
                        $var_errors = str_replace("<p>", "<span>", $var_errors);
                        $var_errors = str_replace("</p>", "</span><br>", $var_errors);
                    }
                }
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
        $this->db->where('institution_master.institution_delete', '0');
        $institution_master = $this->master_model->getRecords('institution_master', '', '', array(
            'name' => 'asc',
        ));
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('designation_master.designation_delete', '0');
        $designation = $this->master_model->getRecords('designation_master');
        $this->db->where('id', 4);
        $this->db->or_where('id', 8);
        $idtype_master = $this->master_model->getRecords('idtype_master');

        /*$this->load->helper('captcha');
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $_SESSION["regcaptcha"] = $cap['word'];*/
        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');

        if ($flag == 0) {
            $data = array(
                'middle_content' => 'cookie_msg',
            );
            $this->load->view('renewal_common_view', $data);
        } else {
            $data = array(
                'middle_content'     => 'renewal',
                'states'             => $states,
                'undergraduate'      => $undergraduate,
                'graduate'           => $graduate,
                'postgraduate'       => $postgraduate,
                'institution_master' => $institution_master,
                'designation'        => $designation,
                'image'              => $captcha_img,
                'idtype_master'      => $idtype_master,
                'var_errors'         => $var_errors,
                'selectedRecord'     => $selectedRecord,
            );
            $this->load->view('renewal_common_view', $data);
        }
    }
    /*public function getDetailsById()
    {
    $selectedMemberId = $_POST['selectedMemberId'];
    $selectedRecord = $this->master_model->getSelectedRecord('renewal_eligible_master',array('regnumber'=>$selectedMemberId),'','','','1');
    if($selectedRecord)
    {
    echo base64_encode(json_encode($selectedRecord));
    }
    else
    {
    $selectedRecord = array("msg"=>"Please Enter Valid Member Number..!!");
    echo base64_encode(json_encode($selectedRecord));
    }
    }
     */
    // callback function to validate addressline 1
    public function address1($addressline1)
    {
        if (!preg_match('/^[a-z0-9 .,-\/]+$/i', $addressline1)) {
            $this->form_validation->set_message('address1', "Please enter valid addressline1");
            return false;
        } else {
            return true;
        }
    }
    public function address1Permanent($addressline1, $id)
    {
        if (!preg_match('/^[a-z0-9 .,-\/]+$/i', $addressline1)) {
            $this->form_validation->set_message('address1Permanent', "Please enter valid Permanent " . $id);
            return false;
        } else {
            return true;
        }
    }
    //callback to validate photo
    public function scannedphoto_upload()
    {
        if ($_FILES['scannedphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedphoto_upload', "No Scanned Photograph file selected");
            return false;
        }
    }
    //callback to validate scannedsignaturephoto
    public function scannedsignaturephoto_upload()
    {
        if ($_FILES['scannedsignaturephoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('scannedsignaturephoto_upload', "No  Scanned Signature file selected");
            return false;
        }
    }
    //callback to validate idproofphoto
    public function idproofphoto_upload()
    {
        if ($_FILES['idproofphoto']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('idproofphoto_upload', "No Id proof file selected");
            return false;
        }
    }

    //callback to validate declarationform by pratibha borse
    public function declarationform_upload()
    {
        if ($_FILES['declarationform']['size'] != 0) {
            return true;
        } else {
            $this->form_validation->set_message('declaration_upload', "No declaration file selected");
            return false;
        }
    }
    //call back for e-mail duplication
    public function check_emailduplication($email)
    {
        if ($email != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'email'    => $email,
                'isactive' => '1',
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {
                $user_info = $this->master_model->getRecords('member_registration', array(
                    'email' => $email,
                ), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
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
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'mobile'   => $mobile,
                'isactive' => '1',
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                return true;
            } else {
                $user_info = $this->master_model->getRecords('member_registration', array(
                    'mobile' => $mobile,
                ), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered  mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
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
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode,
            ));
            //echo $this->db->last_query();
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
    public function check_checkpin_permanant($pincode, $statecode)
    {
        if ($statecode != "" && $pincode != '') {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode,
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $str = 'Please enter Valid Pincode';
                $this->form_validation->set_message('check_checkpin_permanant', $str);
                return false;
            } else {
                $this->form_validation->set_message('error', "");
            }
            {
                return true;
            }
        } else {
            $str = 'Pincode/State field is required.';
            $this->form_validation->set_message('check_checkpin_permanant', $str);
            return false;
        }
    }
    //check aadhar card
    public function check_aadhar($aadhar_card)
    {
        if ($aadhar_card != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            //$this->db->where('registrationtype !=','DB');
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'aadhar_card' => $aadhar_card,
                'isactive'    => '1',
            ));
            //echo $this->db->last_query();
            //exit;
            if ($prev_count == 0) {
                return true;
            } else {
                $str = 'The Entered Aadhar card number already exist';
                $this->form_validation->set_message('check_aadhar', $str);
                return false;
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
    public function date_view()
    {
        $undergraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'UG',
        ));
        $graduate = $this->master_model->getRecords('qualification', array(
            'type' => 'GR',
        ));
        $postgraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'PG',
        ));
        $this->db->where('institution_master.institution_delete', '0');
        $institution_master = $this->master_model->getRecords('institution_master');
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');
        $this->db->where('designation_master.designation_delete', '0');
        $designation = $this->master_model->getRecords('designation_master');
        $this->db->not_like('name', 'college');
        $idtype_master = $this->master_model->getRecords('idtype_master');

        /*$this->load->helper('captcha');
        $vals          = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url() . 'uploads/applications/'
        );
        $cap           = create_captcha($vals);
        $data['image'] = $cap['image'];
        $data['code']  = $cap['word'];
        $this->session->set_userdata('regcaptcha', $cap['word']);*/

        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');
        $calendar    = get_calendar_input();
        //print_r($calendar);
        //exit;
        $data = array(
            'middle_content'     => 'register1',
            'states'             => $states,
            'undergraduate'      => $undergraduate,
            'graduate'           => $graduate,
            'postgraduate'       => $postgraduate,
            'institution_master' => $institution_master,
            'designation'        => $designation,
            'image'              => $captcha_img,
            'idtype_master'      => $idtype_master,
            'calendar'           => $calendar,
        );
        $this->load->view('renewal_common_view', $data);
    }
    public function addmember()
    {
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        $password = $this->generate_random_password();
        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
        $key = $this->config->item('pass_key');
        $aes = new CryptAES();
        $aes->set_key(base64_decode($key));
        $aes->require_pkcs5();
        $encPass                    = $aes->encrypt($password);
        $scannedphoto_file          = $this->session->userdata['enduserinfo']['photoname'];
        $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];
        $idproofphoto_file          = $this->session->userdata['enduserinfo']['idname'];
        $declarationphoto_file      = $this->session->userdata['enduserinfo']['declaration'];
        $sel_namesub                = $this->session->userdata['enduserinfo']['sel_namesub'];
        $firstname                  = strtoupper($this->session->userdata['enduserinfo']['firstname']);
        $middlename                 = strtoupper($this->session->userdata['enduserinfo']['middlename']);
        $lastname                   = strtoupper($this->session->userdata['enduserinfo']['lastname']);
        $nameoncard                 = strtoupper($this->session->userdata['enduserinfo']['nameoncard']);
        $addressline1               = strtoupper($this->session->userdata['enduserinfo']['addressline1']);
        $addressline2               = strtoupper($this->session->userdata['enduserinfo']['addressline2']);
        $addressline3               = strtoupper($this->session->userdata['enduserinfo']['addressline3']);
        $addressline4               = strtoupper($this->session->userdata['enduserinfo']['addressline4']);
        $district                   = strtoupper($this->session->userdata['enduserinfo']['district']);
        $nationality                = strtoupper($this->session->userdata['enduserinfo']['city']);
        $state                      = $this->session->userdata['enduserinfo']['state'];
        $pincode                    = $this->session->userdata['enduserinfo']['pincode'];
        $dob                        = $this->session->userdata['enduserinfo']['dob'];
        $gender                     = $this->session->userdata['enduserinfo']['gender'];
        $optedu                     = $this->session->userdata['enduserinfo']['optedu'];
        $addressline1_pr            = strtoupper($this->session->userdata['enduserinfo']['addressline1_pr']);
        $addressline2_pr            = strtoupper($this->session->userdata['enduserinfo']['addressline2_pr']);
        $addressline3_pr            = strtoupper($this->session->userdata['enduserinfo']['addressline3_pr']);
        $addressline4_pr            = strtoupper($this->session->userdata['enduserinfo']['addressline4_pr']);
        $district_Pr                = strtoupper($this->session->userdata['enduserinfo']['district_pr']);
        $city_pr                    = strtoupper($this->session->userdata['enduserinfo']['city_pr']);
        $state_pr                   = $this->session->userdata['enduserinfo']['state_pr'];
        $pincode_pr                 = $this->session->userdata['enduserinfo']['pincode_pr'];
        if ($optedu == 'U') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual1'];
        } elseif ($optedu == 'G') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual2'];
        } else if ($optedu == 'P') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual3'];
        }
        $institutionworking = $this->session->userdata['enduserinfo']['institution'];
        $office             = strtoupper($this->session->userdata['enduserinfo']['office']);
        $designation        = $this->session->userdata['enduserinfo']['designation'];
        $doj                = $this->session->userdata['enduserinfo']['doj'];
        $email              = $this->session->userdata['enduserinfo']['email'];
        $stdcode            = $this->session->userdata['enduserinfo']['stdcode'];
        $phone              = $this->session->userdata['enduserinfo']['phone'];
        $mobile             = $this->session->userdata['enduserinfo']['mobile'];
        $idproof            = $this->session->userdata['enduserinfo']['idproof'];
        $idNo               = $this->session->userdata['enduserinfo']['idNo'];
        $aadhar_card        = $this->session->userdata['enduserinfo']['aadhar_card'];
        $optnletter         = $this->session->userdata['enduserinfo']['optnletter'];
        $regnumber          = $this->session->userdata['enduserinfo']['regnumber']; // User Entered Member No
        $registrationtype   = 'O'; //$this->session->userdata['enduserinfo']['registrationtype'];
        $bank_emp_id        = $this->session->userdata['enduserinfo']['bank_emp_id'];
        $insert_info        = array(
            'regnumber'             => $regnumber,
            'usrpassword'           => $encPass,
            'namesub'               => $sel_namesub,
            'firstname'             => $firstname,
            'middlename'            => $middlename,
            'lastname'              => $lastname,
            'displayname'           => $nameoncard,
            'address1'              => $addressline1,
            'address2'              => $addressline2,
            'address3'              => $addressline3,
            'address4'              => $addressline4,
            'district'              => $district,
            'city'                  => $nationality,
            'state'                 => $state,
            'pincode'               => $pincode,
            'dateofbirth'           => date('Y-m-d', strtotime($dob)),
            'gender'                => $gender,
            'qualification'         => $optedu,
            'specify_qualification' => $specify_qualification,
            'associatedinstitute'   => $institutionworking,
            'office'                => $office,
            'designation'           => $designation,
            'dateofjoin'            => date('Y-m-d', strtotime($doj)),
            'email'                 => $email,
            'registrationtype'      => 'O', //$registrationtype, // Default 'O'
            'stdcode'               => $stdcode,
            'office_phone'          => $phone,
            'mobile'                => $mobile,
            'scannedphoto'          => $scannedphoto_file,
            'scannedsignaturephoto' => $scannedsignaturephoto_file,
            //'idproof' => $idproof,
            'idNo'                  => $idNo,
            'optnletter'            => $optnletter,
            'declaration'           => $declarationphoto_file,
            'idproofphoto'          => $idproofphoto_file,
            'createdon'             => date('Y-m-d H:i:s'),
            'aadhar_card'           => $aadhar_card,
            //'id_proof_flag' => $idproof,
            'address1_pr'           => $addressline1_pr, //later changes
            'address2_pr'           => $addressline2_pr,
            'address3_pr'           => $addressline3_pr,
            'address4_pr'           => $addressline4_pr,
            'district_pr'           => $district_Pr,
            'city_pr'               => $city_pr,
            'state_pr'              => $state_pr,
            'pincode_pr'            => $pincode_pr,
            'is_renewal'            => 1,
            'bank_emp_id'           => $bank_emp_id,
        );
        if ($last_id = $this->master_model->insertRecord('member_registration', $insert_info, true)) {
            $upd_files = array();
            logactivity($log_title = "Renewal Of Ordinary Membership Registration ", $log_message = serialize($insert_info));
            $userarr = array(
                'regno'     => $last_id,
                'password'  => $password,
                'email'     => $email,
                'regnumber' => $regnumber,
            );
            $this->session->set_userdata('memberdata', $userarr);
            redirect(base_url() . "Renewal/make_payment");
        } else {
            $userarr = array(
                'regno'     => '',
                'password'  => '',
                'email'     => '',
                'regnumber' => '',
            );
            $this->session->set_userdata('memberdata', $userarr);
            $this->session->set_flashdata('error', 'Error while during registration.please try again!');
            redirect(base_url());
        }
    }
    //validate captcha
    ##---------check captcha userlogin (prafull)-----------##
    public function ajax_check_captcha()
    {
        //echo 'true';
        //exit;
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
    // reload captcha functionality
    public function generatecaptchaajax()
    {
        /*$this->load->helper('captcha');
        $this->session->unset_userdata("regcaptcha");
        $this->session->set_userdata("regcaptcha", rand(1, 100000));
        $vals                   = array(
        'img_path' => './uploads/applications/',
        'img_url' => base_url() . 'uploads/applications/'
        );
        $cap                    = create_captcha($vals);
        $data                   = $cap['image'];
        $_SESSION["regcaptcha"] = $cap['word'];
        echo $data;*/
        $this->load->model('Captcha_model');
        echo $captcha_img = $this->Captcha_model->generate_captcha_img('regcaptcha');
    }

    //Thank you message to end user
    public function acknowledge()
    {
        $data = array();
        if ($this->session->userdata('memberdata') == '') {
            redirect(base_url());
        }
        if ($this->session->userdata('enduserinfo')) {
            $this->session->unset_userdata('enduserinfo');
        }
        $user_info = $this->master_model->getRecords('member_registration', array(
            'regid' => $this->session->userdata['memberdata']['regno'],
        ), 'regnumber');
        $data = array(
            'middle_content'     => 'renewalThankyou',
            'application_number',
            'application_number' => $user_info[0]['regnumber'],
            'password'           => $this->session->userdata['memberdata']['password'],
        );
        $this->load->view('common_view', $data);
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
        if ($this->session->userdata('memberdata') != '') {
            $user_info = $this->master_model->getRecords('member_registration', array(
                'regid'    => $this->session->userdata['memberdata']['regno'],
                'isactive' => '1',
            ));
        } else {
            redirect(base_url());
        }
        if (count($user_info) <= 0) {
            redirect(base_url());
        }
        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
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
        if ($this->session->userdata('memberdata') != '') {
            $user_info = $this->master_model->getRecords('member_registration', array(
                'regid'    => $this->session->userdata['memberdata']['regno'],
                'isactive' => '1',
            ));
        } else {
            redirect(base_url());
        }
        if (count($user_info) <= 0) {
            redirect(base_url());
        }

        if ($user_info[0]['qualification'] == 'U') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'UG',
            ), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'G') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'GR',
            ), 'name as qname', '', '', 1);
        } else if ($user_info[0]['qualification'] == 'P') {
            $this->db->where('qid', $user_info[0]['specify_qualification']);
            $qualification = $this->master_model->getRecords('qualification', array(
                'type' => 'PG',
            ), 'name as qname', '', '', 1);
        }
        $this->db->where('id', $user_info[0]['idproof']);
        $idtype_master    = $this->master_model->getRecords('idtype_master', '', 'name');
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
            $user_info[0]['address2'] = $user_info[0]['address2'] . '<br>';
        }
        if ($user_info[0]['address3'] != '') {
            $user_info[0]['address3'] = $user_info[0]['address3'] . '<br>';
        }
        if ($user_info[0]['address4'] != '') {
            $user_info[0]['address4'] = $user_info[0]['address4'] . '<br>';
        }
        $useradd = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'] . ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . ',' . $user_info[0]['state_name'] . $user_info[0]['pincode'];
        $html    = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ;
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
                        <td colspan="2" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['displayname'] . '</td>
                    </tr>
                    <tr>
                        <td class="tablecontent2" width="51%">Office/Residential Address for communication :</td>
                        <td colspan="3" class="tablecontent2" width="49%" nowrap="nowrap">
                            ' . $useradd . '            </td>
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
                        <td class="tablecontent2">Bank Employee Id :</td>
                        <td colspan="3" class="tablecontent2" nowrap="nowrap">' . $user_info[0]['bank_emp_id'] . ' </td>
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
                        <td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="' . base_url() . 'uploads/idproof/' . $user_info[0]['idproofphoto'] . '"  height="180" width="100"></td>
                    </tr>
                    <tr>
                        <td class="tablecontent2">Signature :</td>
                        <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . 'uploads/scansignature/' . $user_info[0]['scannedsignaturephoto'] . '" height="100" width="100"></td>
                    </tr>
                    <tr>
                        <td class="tablecontent2">Declaration :</td>
                        <td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . 'uploads/declaration/' . $user_info[0]['declaration'] . '" height="100" width="100"></td>
                    </tr>
                    <tr>
                        <td class="tablecontent2">Date :</td>
                        <td colspan="3" class="tablecontent2" nowrap="nowrap">
                            ' . date('d-m-Y h:i:s A', strtotime($user_info[0]['createdon'])) . '        </td>
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
    public function preview()
    {
        if (!$this->session->userdata('enduserinfo')) {
            redirect(base_url());
        }
        $selectedMemberId = $this->session->userdata['enduserinfo']['regnumber'];
        $renewalQry       = $this->db->query("SELECT * FROM renewal_eligible_master WHERE regnumber = '" . $selectedMemberId . "'  LIMIT 1 ");
        $selectedRecord   = $renewalQry->row_array();
        $undergraduate    = $this->master_model->getRecords('qualification', array(
            'type' => 'UG',
        ));
        $graduate = $this->master_model->getRecords('qualification', array(
            'type' => 'GR',
        ));
        $postgraduate = $this->master_model->getRecords('qualification', array(
            'type' => 'PG',
        ));
        $institution_master = $this->master_model->getRecords('institution_master');
        $states             = $this->master_model->getRecords('state_master');
        $designation        = $this->master_model->getRecords('designation_master');
        $idtype_master      = $this->master_model->getRecords('idtype_master');
        $data               = array(
            'middle_content'     => 'renewal_preview',
            'states'             => $states,
            'undergraduate'      => $undergraduate,
            'graduate'           => $graduate,
            'postgraduate'       => $postgraduate,
            'institution_master' => $institution_master,
            'designation'        => $designation,
            'idtype_master'      => $idtype_master,
            'selectedRecord'     => $selectedRecord,
        );
        $this->load->view('renewal_common_view', $data);
    }
    //Genereate random password function
    public function generate_random_password($length = 8, $level = 2) // function to generate new password

    {
        list($usec, $sec) = explode(' ', microtime());
        srand((float) $sec + ((float) $usec * 100000));
        $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
        $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $validchars[3] = "0123456789_!@#*()-=+abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#*()-=+";
        $password      = "";
        $counter       = 0;
        while ($counter < $length) {
            $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level]) - 1), 1);
            if (!strstr($password, $actChar)) {
                $password .= $actChar;
                $counter++;
            }
        }
        return $password;
    }
    ##---------check mail alredy exist or not (prafull)-----------##
    public function emailduplication()
    {
        $email = $_POST['email'];
        if ($email != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'email'    => $email,
                'isactive' => '1',
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array(
                    'ans' => 'ok',
                );
                echo json_encode($data_arr);
            } else {
                $user_info = $this->master_model->getRecords('member_registration', array(
                    'email' => $email,
                ), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID and mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $data_arr         = array(
                    'ans'    => 'exists',
                    'output' => $str,
                );
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }
    ##---------check pincode/zipcode alredy exist or not (prafull)-----------##
    public function checkpin()
    {
        $statecode = $_POST['statecode'];
        $pincode   = $_POST['pincode'];
        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array(
                'state_code' => $statecode,
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
    ##---------check mobile nnnumber alredy exist or not (prafull)-----------##
    public function mobileduplication()
    {
        $mobile = $_POST['mobile'];
        if ($mobile != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'mobile'   => $mobile,
                'isactive' => '1',
            ));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array(
                    'ans' => 'ok',
                );
                echo json_encode($data_arr);
            } else {
                $user_info = $this->master_model->getRecords('member_registration', array(
                    'mobile' => $mobile,
                ), 'regnumber,firstname,middlename,lastname');
                $username         = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];
                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                $str              = 'The entered email ID and mobile no already exist for membership / registration number ' . $user_info[0]['regnumber'] . ' , ' . $userfinalstrname . '';
                $data_arr         = array(
                    'ans'    => 'exists',
                    'output' => $str,
                );
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }
    //check Aadhar Card Duplication
    public function adharNoDuplication()
    {
        $aadhar_card = $_POST['aadhar_card'];
        if ($aadhar_card != "") {
            $where = "( registrationtype='O' OR registrationtype='A' OR registrationtype='F')";
            $this->db->where($where);
            $prev_count = $this->master_model->getRecordCount('member_registration', array(
                'aadhar_card' => $aadhar_card,
                'isactive'    => '1',
            ));
            if ($prev_count == 0) {
                $data_arr = array(
                    'ans' => 'ok',
                );
                echo json_encode($data_arr);
            } else {
                $str      = 'The Entered Adhar Card Number already exist';
                $data_arr = array(
                    'ans'    => 'exists',
                    'output' => $str,
                );
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }
    public function make_payment()
    {
        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt  = $sgst_amt  = $igst_amt  = '';
        $cs_total  = $igst_total  = '';
        $getstate  = $getcenter  = $getfees  = array();
        $flag      = 1;
        $regno     = $this->session->userdata['memberdata']['regno'];
        $regnumber = $this->session->userdata['memberdata']['regnumber'];
        if (!empty($regno)) {
            $member_data = $this->Master_model->getRecords('member_registration', array(
                'regid'    => $regno,
                'isactive' => '0',
            ), array(
                'state_pr',
                'fee',
            ));
        }
        $valcookie = register_get_cookie();
        if ($valcookie) {
            $regid     = $valcookie;
            $checkuser = $this->master_model->getRecords('member_registration', array(
                'regid'        => $regno,
                'regnumber !=' => '',
                'isactive !='  => '0',
            ));
            if (count($checkuser) > 0) {
                delete_cookie('regid');
                redirect('http://iibf.org.in');
            } else {
                $checkpayment = $this->master_model->getRecords('payment_transaction', array(
                    'ref_id' => $regno,
                    'status' => '2',
                ));
                if (count($checkpayment) > 0) {
                    $endTime      = date("Y-m-d H:i:s", strtotime("+20 minutes", strtotime($checkpayment[0]['date'])));
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

            $pg_name = $this->input->post('pg_name');

            /*  include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
            $key            = $this->config->item('sbi_m_key');
            $merchIdVal     = $this->config->item('sbi_merchIdVal');
            $AggregatorId   = $this->config->item('sbi_AggregatorId');
            $pg_success_url = base_url() . "Renewal/sbitranssuccess";
            $pg_fail_url    = base_url() . "Renewal/sbitransfail"; */
            //$amount = $this->config->item('member_reg_fee');
            $state = $member_data[0]['state_pr'];
            $fee   = $member_data[0]['fee'];
            if (!empty($state)) {
                if ($state == 'MAH') {
                    $amount = $this->config->item('renewal_cs_total');
                }
                /*else if ($state == 'JAM') {
                $amount = $this->config->item('renewal_fee_amt');
                }*/
                else {
                    $amount = $this->config->item('renewal_igst_total');
                }
            }
            // Create transaction
            $insert_data = array(
                'member_regnumber' => $regnumber,
                'gateway'          => "sbiepay",
                'amount'           => $amount,
                'date'             => date('Y-m-d H:i:s'),
                'ref_id'           => $regno,
                'description'      => "Membership Renewal",
                'pay_type'         => 5,
                'status'           => 2,
                'pg_flag'          => 'iibfren',
            );
            $pt_id                 = $this->master_model->insertRecord('payment_transaction', $insert_data, true);
            $MerchantOrderNo       = reg_sbi_order_id($pt_id);
            $custom_field          = $MerchantOrderNo . "^iibfregn^iibfren^" . $regnumber;
            $custom_field_billdesk = $MerchantOrderNo . "-iibfregn-iibfren-" . $regnumber;
            // update receipt no. in payment transaction -
            $update_data = array(
                'receipt_no'       => $MerchantOrderNo,
                'pg_other_details' => $custom_field,
            );
            $this->master_model->updateRecord('payment_transaction', $update_data, array(
                'id' => $pt_id,
            ));
            //get value for invoice details
            if (!empty($state)) {
                $getstate = $this->master_model->getRecords('state_master', array(
                    'state_code'   => $state,
                    'state_delete' => '0',
                ));
            }
            if ($state == 'MAH') {
                //set a rate (e.g 9%,9% or 18%)
                $cgst_rate = $this->config->item('renewal_cgst_rate');
                $sgst_rate = $this->config->item('renewal_sgst_rate');
                //set an amount as per rate
                $cgst_amt = $this->config->item('renewal_cgst_amt');
                $sgst_amt = $this->config->item('renewal_sgst_amt');
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
            }*/
            else {
                $igst_rate  = $this->config->item('renewal_igst_rate');
                $igst_amt   = $this->config->item('renewal_igst_amt');
                $igst_total = $amount;
                $tax_type   = 'Inter';
            }
            /*if ($getstate[0]['exempt'] == 'E') {
            $cgst_rate = $sgst_rate = $igst_rate = '';
            $cgst_amt = $sgst_amt = $igst_amt = '';
            }*/
            $invoice_insert_array = array(
                'member_no'       => $regnumber,
                'pay_txn_id'      => $pt_id,
                'receipt_no'      => $MerchantOrderNo,
                'member_no'       => $regnumber,
                'state_of_center' => $state,
                'app_type'        => 'N',
                'service_code'    => $this->config->item('renewal_member_service_code'),
                'qty'             => '1',
                'state_code'      => $getstate[0]['state_no'],
                'state_name'      => $getstate[0]['state_name'],
                'tax_type'        => $tax_type,
                'fee_amt'         => $this->config->item('renewal_fee_amt'),
                'cgst_rate'       => $cgst_rate,
                'cgst_amt'        => $cgst_amt,
                'sgst_rate'       => $sgst_rate,
                'sgst_amt'        => $sgst_amt,
                'igst_rate'       => $igst_rate,
                'igst_amt'        => $igst_amt,
                'cs_total'        => $cs_total,
                'igst_total'      => $igst_total,
                'gstin_no'        => '',
                'exempt'          => $getstate[0]['exempt'],
                'created_on'      => date('Y-m-d H:i:s'),
            );
            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);
            /* $MerchantCustomerID   = $regno;
            $data["pg_form_url"]  = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
            $data["merchIdVal"]   = $merchIdVal;
            $EncryptTrans         = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
            $aes                  = new CryptAES();
            $aes->set_key(base64_decode($key));
            $aes->require_pkcs5();
            $EncryptTrans         = $aes->encrypt($EncryptTrans);
            $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
            $this->load->view('pg_sbi_form', $data); */
            if ($pg_name == 'sbi') {
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key            = $this->config->item('sbi_m_key');
                $merchIdVal     = $this->config->item('sbi_merchIdVal');
                $AggregatorId   = $this->config->item('sbi_AggregatorId');
                $pg_success_url = base_url() . "Renewal/sbitranssuccess";
                $pg_fail_url    = base_url() . "Renewal/sbitransfail";
                //exit;
                $MerchantCustomerID  = $regno;
                $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
                $data["merchIdVal"]  = $merchIdVal;
                $EncryptTrans        = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                $aes                 = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();
                $EncryptTrans         = $aes->encrypt($EncryptTrans);
                $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
                $this->load->view('pg_sbi_form', $data);
            } elseif ($pg_name == 'billdesk') {
                $update_payment_data = array('gateway' => 'billdesk');
                $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $new_invoice_id, $new_invoice_id, '', 'Renewal/handle_billdesk_response', '', '', '', $custom_field_billdesk);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'Renewal');
                }
            }
        } else {
            //$data["regno"] = $_REQUEST['regno'];
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

        if (isset($_REQUEST['transaction_response'])) {
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
            $get_user_regnum_info   = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,member_regnumber,status,id', '', '', '1');
            if (empty($get_user_regnum_info)) {
                redirect(base_url() . 'Renewal');
            }
            $new_invoice_id   = $get_user_regnum_info[0]['ref_id'];
            $member_regnumber = $get_user_regnum_info[0]['member_regnumber'];
            //Query to get Payment details
            $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $member_regnumber), 'transaction_no,date,amount,id,ref_id');

            //print_r($get_user_regnum_info[0]['status']);
            $qry_api_response = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300' && $get_user_regnum_info[0]['status'] == 2) {
                //payment transaction update
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
                $this->master_model->updateRecord('payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                /* Transaction Log */
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $reg_id        = $get_user_regnum_info[0]['ref_id'];
                $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
                if (count($get_user_regnum_info) > 0) {
                    //'regnumber' => $applicationNo,
                    $update_mem_data = array(
                        'isactive'   => '1',
                        'is_renewal' => 1,
                    );
                    $this->master_model->updateRecord('member_registration', $update_mem_data, array(
                        'regid' => $reg_id,
                    ));
                    $user_info = $this->master_model->getRecords('member_registration', array(
                        'regid' => $reg_id,
                    ), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,declaration,mobile');
                    $upd_files        = array();
                    $photo_file       = 'p_' . $applicationNo . '.jpg';
                    $sign_file        = 's_' . $applicationNo . '.jpg';
                    $proof_file       = 'pr_' . $applicationNo . '.jpg';
                    $declaration_file = 'declaration_' . $applicationNo . '.jpg';
                    if (@rename("./uploads/photograph/" . $user_info[0]['scannedphoto'], "./uploads/photograph/" . $photo_file)) {
                        $upd_files['scannedphoto'] = $photo_file;
                    }
                    if (@rename("./uploads/scansignature/" . $user_info[0]['scannedsignaturephoto'], "./uploads/scansignature/" . $sign_file)) {
                        $upd_files['scannedsignaturephoto'] = $sign_file;
                    }
                    if (@rename("./uploads/idproof/" . $user_info[0]['idproofphoto'], "./uploads/idproof/" . $proof_file)) {
                        $upd_files['idproofphoto'] = $proof_file;
                    }
                    if (@rename("./uploads/declaration/" . $user_info[0]['declaration'], "./uploads/declaration/" . $declaration_file)) {
                        $upd_files['declaration'] = $declaration_file;
                    }
                    if (count($upd_files) > 0) {
                        $this->master_model->updateRecord('member_registration', $upd_files, array(
                            'regid' => $reg_id,
                        ));
                    }
                }
                //Manage Log
                //$pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                // $this->log_model->logtransaction("billdesk", $pg_response, $responsedata[2]);
                //email to user
                $emailerstr = $this->master_model->getRecords('emailer', array(
                    'emailer_name' => 'user_renewal_email',
                ));
                if (count($emailerstr) > 0) {
                    include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                    $key = $this->config->item('pass_key');
                    $aes = new CryptAES();
                    $aes->set_key(base64_decode($key));
                    $aes->require_pkcs5();
                    //$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
                    $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
                    //$decpass = $aes->decrypt($user_info[0]['usrpassword']);
                    $newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
                    $final_str = str_replace("#password#", "" . $decpass . "", $newstring);
                    $info_arr  = array(
                        'to'      => $user_info[0]['email'],
                        //'to'=>'chaitali.jadhav@esds.co.in',
                        'from'    => $emailerstr[0]['from'],
                        'subject' => $emailerstr[0]['subject'],
                        'message' => $final_str,
                    );
                    // INVOICE CODE
                    $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                        'receipt_no' => $MerchantOrderNo,
                        'pay_txn_id' => $get_user_regnum_info[0]['id'],
                    ));
                    if (count($getinvoice_number) > 0) {
                        /*if ($getinvoice_number[0]['state_of_center'] == 'JAM') {
                        $invoiceNumber = generate_renewal_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                        $invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix_jammu') . $invoiceNumber;
                        }
                        } else {*/
                        $invoiceNumber = generate_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
                        if ($invoiceNumber) {
                            $invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix') . $invoiceNumber;
                            /*}*/
                        }
                        // 'member_no' => $applicationNo,
                        $update_data22 = array(
                            'invoice_no'      => $invoiceNumber,
                            'transaction_no'  => $transaction_no,
                            'date_of_invoice' => date('Y-m-d H:i:s'),
                            'modified_on'     => date('Y-m-d H:i:s'),
                        );
                        $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                        $this->master_model->updateRecord('exam_invoice', $update_data22, array(
                            'receipt_no' => $MerchantOrderNo,
                        ));
                        $attachpath = genarate_renewal_invoice($getinvoice_number[0]['invoice_id']);
                    }
                    if ($attachpath != '') {
                        $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['sms_text']);
                        $sms_final_str = str_replace("#password#", "" . $decpass . "", $sms_newstring);
                        $this->master_model->send_sms($user_info[0]['mobile'], $sms_final_str);
                        //if($this->Emailsending->mailsend($info_arr))
                        if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {

                            redirect(base_url() . 'Renewal/acknowledge/');
                        } else {
                            redirect(base_url() . 'Renewal/acknowledge/');
                        }
                    } else {
                        redirect(base_url() . 'Renewal/acknowledge/');
                    }
                }

            } 
            elseif ($auth_status=='0002') {
                $update_data33 = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 2,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code'           => '0002',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );
                $this->master_model->updateRecord('payment_transaction', $update_data33, array(
                    'receipt_no' => $MerchantOrderNo,
                ));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $this->session->set_flashdata('flsh_msg', 'Transaction in process...!');
                redirect(base_url() . 'Renewal');
            }
            else /* if ($transaction_error_type == 'payment_authorization_error') */
            {
                $update_data33 = array(
                    'transaction_no'      => $transaction_no,
                    'status'              => 0,
                    'transaction_details' => $transaction_error_type . " - " . $transaction_error_desc,
                    'auth_code'           => '0300',
                    'bankcode'            => $bankid,
                    'paymode'             => $txn_process_type,
                    'callback'            => 'B2B',
                );
                $this->master_model->updateRecord('payment_transaction', $update_data33, array(
                    'receipt_no' => $MerchantOrderNo,
                ));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("billdesk", $pg_response, $transaction_error_type);

                $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                redirect(base_url() . 'Renewal');
            }
        } else {
            die("Please try again...");
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
            $encData         = $aes->decrypt($_REQUEST['encData']);
            $responsedata    = explode("|", $encData);
            $MerchantOrderNo = $responsedata[0];
            $transaction_no  = $responsedata[1];
            $attachpath      = $invoiceNumber      = '';
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
                    $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                        'receipt_no' => $MerchantOrderNo,
                    ), 'ref_id,status,id,member_regnumber');
                    $applicationNo = $get_user_regnum_info[0]['member_regnumber'];
                    //check user payment status is updated by s2s or not
                    if ($get_user_regnum_info[0]['status'] == 2) {
                        $reg_id = $get_user_regnum_info[0]['ref_id'];
                        //$applicationNo = generate_mem_reg_num();
                        //$applicationNo =generate_O_memreg($reg_id);
                        //$applicationNo = $this->session->userdata['memberdata']['regnumber']; // User Entered Number
                        //$this->db->trans_start();
                        //'member_regnumber' => $applicationNo,
                        $update_data = array(
                            'transaction_no'      => $transaction_no,
                            'status'              => 1,
                            'transaction_details' => $responsedata[2] . " - " . $responsedata[7],
                            'auth_code'           => '0300',
                            'bankcode'            => $responsedata[8],
                            'paymode'             => $responsedata[5],
                            'callback'            => 'B2B',
                        );
                        $this->master_model->updateRecord('payment_transaction', $update_data, array(
                            'receipt_no' => $MerchantOrderNo,
                        ));
                        //$this->db->trans_complete();
                        if (count($get_user_regnum_info) > 0) {
                            //'regnumber' => $applicationNo,
                            $update_mem_data = array(
                                'isactive'   => '1',
                                'is_renewal' => 1,
                            );
                            $this->master_model->updateRecord('member_registration', $update_mem_data, array(
                                'regid' => $reg_id,
                            ));
                            $user_info = $this->master_model->getRecords('member_registration', array(
                                'regid' => $reg_id,
                            ), 'usrpassword,email,scannedphoto,scannedsignaturephoto,idproofphoto,mobile,declaration');
                            $upd_files        = array();
                            $photo_file       = 'p_' . $applicationNo . '.jpg';
                            $sign_file        = 's_' . $applicationNo . '.jpg';
                            $proof_file       = 'pr_' . $applicationNo . '.jpg';
                            $declaration_file = 'declaration_' . $applicationNo . '.jpg';

                            if (@rename("./uploads/photograph/" . $user_info[0]['scannedphoto'], "./uploads/photograph/" . $photo_file)) {
                                $upd_files['scannedphoto'] = $photo_file;
                            }
                            if (@rename("./uploads/scansignature/" . $user_info[0]['scannedsignaturephoto'], "./uploads/scansignature/" . $sign_file)) {
                                $upd_files['scannedsignaturephoto'] = $sign_file;
                            }
                            if (@rename("./uploads/idproof/" . $user_info[0]['idproofphoto'], "./uploads/idproof/" . $proof_file)) {
                                $upd_files['idproofphoto'] = $proof_file;
                            }
                            if (@rename("./uploads/declaration/" . $user_info[0]['declaration'], "./uploads/declaration/" . $declaration_file)) {
                                $upd_files['declaration'] = $declaration_file;
                            }
                            if (count($upd_files) > 0) {
                                $this->master_model->updateRecord('member_registration', $upd_files, array(
                                    'regid' => $reg_id,
                                ));
                            }
                        }
                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                        $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
                        //email to user
                        $emailerstr = $this->master_model->getRecords('emailer', array(
                            'emailer_name' => 'user_renewal_email',
                        ));
                        if (count($emailerstr) > 0) {
                            include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                            $key = $this->config->item('pass_key');
                            $aes = new CryptAES();
                            $aes->set_key(base64_decode($key));
                            $aes->require_pkcs5();
                            //$encPass = $aes->encrypt(trim($user_info[0]['usrpassword']));
                            $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));
                            //$decpass = $aes->decrypt($user_info[0]['usrpassword']);
                            $newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['emailer_text']);
                            $final_str = str_replace("#password#", "" . $decpass . "", $newstring);
                            $info_arr  = array(
                                'to'      => $user_info[0]['email'],
                                //'to'=>'kumartupe@gmail.com',
                                'from'    => $emailerstr[0]['from'],
                                'subject' => $emailerstr[0]['subject'],
                                'message' => $final_str,
                            );
                            // INVOICE CODE
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array(
                                'receipt_no' => $MerchantOrderNo,
                                'pay_txn_id' => $get_user_regnum_info[0]['id'],
                            ));
                            if (count($getinvoice_number) > 0) {
                                /*if ($getinvoice_number[0]['state_of_center'] == 'JAM') {
                                $invoiceNumber = generate_renewal_invoice_number_jammu($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                $invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix_jammu') . $invoiceNumber;
                                }
                                } else {*/
                                $invoiceNumber = generate_renewal_invoice_number($getinvoice_number[0]['invoice_id']);
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('renewal_mem_invoice_no_prefix') . $invoiceNumber;
                                    /*}*/
                                }
                                // 'member_no' => $applicationNo,
                                $update_data = array(
                                    'invoice_no'      => $invoiceNumber,
                                    'transaction_no'  => $transaction_no,
                                    'date_of_invoice' => date('Y-m-d H:i:s'),
                                    'modified_on'     => date('Y-m-d H:i:s'),
                                );
                                $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array(
                                    'receipt_no' => $MerchantOrderNo,
                                ));
                                $attachpath = genarate_renewal_invoice($getinvoice_number[0]['invoice_id']);
                            }
                            if ($attachpath != '') {
                                $sms_newstring = str_replace("#application_num#", "" . $applicationNo . "", $emailerstr[0]['sms_text']);
                                $sms_final_str = str_replace("#password#", "" . $decpass . "", $sms_newstring);
                                //$this->master_model->send_sms($user_info[0]['mobile'], $sms_final_str);
                                $this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile']), $sms_final_str, 'MQvtFIwMg');
                                //if($this->Emailsending->mailsend($info_arr))
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                                    redirect(base_url() . 'Renewal/acknowledge/');
                                } else {
                                    redirect(base_url() . 'Renewal/acknowledge/');
                                }
                            } else {
                                redirect(base_url() . 'Renewal/acknowledge/');
                            }
                        }
                    }
                }
            }
            ///End of SBI B2B callback
            redirect(base_url() . 'Renewal/acknowledge/');
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
            $encData              = $aes->decrypt($_REQUEST['encData']);
            $responsedata         = explode("|", $encData);
            $MerchantOrderNo      = $responsedata[0];
            $transaction_no       = $responsedata[1];
            $get_user_regnum_info = $this->master_model->getRecords('payment_transaction', array(
                'receipt_no' => $MerchantOrderNo,
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
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logtransaction("sbiepay", $pg_response, $responsedata[2]);
            }
            //Sbi fail code without callback
            //echo "Transaction failed";
            redirect(base_url() . 'Renewal/');
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
            /*    $this->load->model('log_model');
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
    /* Display list of exams for members */
    public function examlist()
    {
        $today_date = date('Y-m-d');
        $flag       = 1;
        $exam_list  = array();
        $Extype     = base64_decode($this->input->get('Extype'));
        $Mtype      = base64_decode($this->input->get('Mtype'));
        if ($Mtype != 'O' && $Mtype != 'A' && $Mtype != 'F' && $Mtype != 'DB' && $Mtype != 'NM') {
            $flag = 0;
        }
        if ($flag == 1) {
            if ($Mtype == 'O') {
                $this->db->where('elg_mem_o', 'Y');
            }
            if ($Mtype == 'A') {
                $this->db->where('elg_mem_a', 'Y');
            }
            if ($Mtype == 'F') {
                $this->db->where('elg_mem_f', 'Y');
            }
            if ($Mtype == 'DB') {
                $this->db->where('elg_mem_db', 'Y');
            }
            if ($Mtype == 'NM') {
                $this->db->where('elg_mem_nm', 'Y');
            }
            $this->db->join('subject_master', 'subject_master.exam_code=exam_master.exam_code');
            $this->db->join('center_master', 'center_master.exam_name=exam_master.exam_code');
            $this->db->join('exam_activation_master', 'exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master', 'medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND misc_master.exam_period=exam_activation_master.exam_period AND misc_master.exam_period=center_master.exam_period AND subject_master.exam_period=misc_master.exam_period');
            $this->db->where('medium_delete', '0');
            $this->db->where('exam_type', trim($Extype));
            $this->db->where("misc_master.misc_delete", '0');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where("exam_activation_master.exam_activation_delete", "0");
            $this->db->group_by('medium_master.exam_code');
            $this->db->order_by('exam_activation_master.id', 'DESC');
            $exam_list = $this->master_model->getRecords('exam_master');
            /*$this->db->join('exam_activation_master','exam_activation_master.exam_code=exam_master.exam_code');
            $this->db->join('medium_master','medium_master.exam_code=exam_activation_master.exam_code AND medium_master.exam_period=exam_activation_master.    exam_period');
            $this->db->where("'$today_date' BETWEEN exam_activation_master.exam_from_date AND exam_activation_master.exam_to_date");
            $this->db->where('medium_delete','0');
            $this->db->where('exam_type',trim($Extype));
            $this->db->where('exam_activation_master.exam_activation_delete','0');
            $this->db->group_by('medium_master.exam_code');
            $exam_list=$this->master_model->getRecords('exam_master');*/
            $exam_type_name = $this->master_model->getRecords('exam_type', array(
                'id' => trim($Extype),
            ));
            //echo $this->db->last_query();exit;
        }
        $data = array(
            'exam_list'      => $exam_list,
            'Extype'         => base64_encode($Extype),
            'Mtype'          => base64_encode($Mtype),
            'exam_type_name' => $exam_type_name,
        );
        $this->load->view('examlist', $data);
    }

    public function custom_invoice_renewal()
    {
        $getinvoice_number = 1227577;
        $demo              = custom_genarate_renewal_invoice($getinvoice_number);
    }

}
