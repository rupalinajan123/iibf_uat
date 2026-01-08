<?php

/********************************************************************
 * Description: Controller for Scribe module
 * Created BY: Chaitali Jadhav
 * Created On: 2022-03-25

 * Description: Controller for Scribe module
 * Developed BY :POOJA MANE
 * Modified ON :2022-07-28

 *********************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Scribe_form extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        $this->load->model('master_model');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('chk_session');
        $this->load->model('Captcha_model');
        $this->load->helper('cookie');
        $this->load->model('log_model');
        $this->load->model('KYC_Log_model');
        $this->load->model('billdesk_pg_model');
        $this->load->helper('update_image_name_helper');
        $this->chk_session->Check_mult_session();
    }

    public function scribe()
    {
        /*GET ALL EXAMS*/
        $this->db->select('e.description,e.exam_code');
        $data['exams'] = $exams = $this->master_model->getRecords('exam_master e');

        /*GET ALL SUBJECTS*/
        $this->db->select('s.subject_code,s.exam_code,s.subject_description');
        $data['subjects'] = $subjects = $this->master_model->getRecords('subject_master s');

        /********** START : ACTIVE EXAM DATA TO DISPLAY IN DROPDOWN *****************/
        $select_exam = "a.id, a.exam_code, a.exam_period, a.exam_from_date, a.exam_from_time, a.exam_to_date, a.exam_to_time, a.exam_activation_delete, em.description";
        $whr_exam['a.exam_activation_delete'] = '0';
        $whr_exam['em.exam_delete']           = '0';

        $this->db->order_by('a.exam_code', 'ASC');
        $this->db->join("exam_master em", "em.exam_code = a.exam_code", "LEFT");
        $this->db->join("member_exam me", "me.exam_code = a.exam_code", "LEFT");
        $this->db->group_by('description');
        $data['active_exam_data'] = $active_exam_data = $this->master_model->getRecords('exam_activation_master a', $whr_exam, $select_exam, array(), '', '');

        /* echo $this->db->last_query();
        die; */
        $this->load->model('Captcha_model');
        $data['captcha_img'] = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');
        // echo'<pre>';print_r($data);die;

        $this->load->view('scribe_form/apply_scribe', $data);
    }

    public function special()
    {
        /*GET ALL EXAMS*/
        $this->db->select('e.description,e.exam_code');
        $data['exams'] = $exams = $this->master_model->getRecords('exam_master e');

        /*GET ALL SUBJECTS*/
        $this->db->select('s.subject_code,s.exam_code,s.subject_description');
        $data['subjects'] = $subjects = $this->master_model->getRecords('subject_master s');

        /********** START : ACTIVE EXAM DATA TO DISPLAY IN DROPDOWN *****************/
        $select_exam = "a.id, a.exam_code, a.exam_period, a.exam_from_date, a.exam_from_time, a.exam_to_date, a.exam_to_time, a.exam_activation_delete, em.description";
        $whr_exam['a.exam_activation_delete'] = '0';
        $whr_exam['em.exam_delete']           = '0';

        $this->db->order_by('a.exam_code', 'ASC');
        $this->db->join("exam_master em", "em.exam_code = a.exam_code", "LEFT");
        $this->db->join("member_exam me", "me.exam_code = a.exam_code", "LEFT");
        $this->db->group_by('description');
        $data['active_exam_data'] = $active_exam_data = $this->master_model->getRecords('exam_activation_master a', $whr_exam, $select_exam, array(), '', '');

        // echo $this->db->last_query();die;
        $this->load->model('Captcha_model');
        $data['captcha_img'] = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');

        $this->load->view('scribe_form/apply_special', $data);
    }

    public function faq()
    {
        $this->load->view('scribe_form/faq');
    }

    public function logout()
    {
        ## Clear all session values and redirect to default controller
        if (
            $this->session->flashdata('error') != ""
        ) {
            $this->session->set_flashdata('error', $this->session->flashdata('error'));
        }
        $this->session->unset_userdata('session_array');
        redirect(site_url('Scribe_form'));
    }

    public function demo()
    {
        /*GET ALL EXAMS*/
        $this->db->select('e.description,e.exam_code');
        $data['exams'] = $exams = $this->master_model->getRecords('exam_master e');

        /*GET ALL SUBJECTS*/
        $this->db->select('s.subject_code,s.exam_code,s.subject_description');
        $data['subjects'] = $subjects = $this->master_model->getRecords('subject_master s');
        $this->load->view('scribe_form/demo', $data);
    }

    public function getSubjects()
    {
        // POST data
        $subject   = array();
        $exam_code = $this->input->post('exam_code');

        // get data
        if ($exam_code) {
            $this->db->select('s.subject_code,s.exam_code,s.subject_description');
            $data['subjects'] = $subjects = $this->master_model->getRecords('subject_master s', array('exam_code' => $exam_code));
        }
        echo json_encode($subjects);
    }

    public function change_scribe()
    {
        $member_no = $exam_code = $sel_subject = '';
        $member_no = $this->input->post('member_no');
        $exam_code = $this->input->post('exam_code');
        $sel_subject = $this->input->post('sel_subject');

        $today = date('Y-m-d'); //Condition added to check future exam date Pooja mane : 23-01-23
        $this->db->Where('exam_date >=', $today); //Pooja mane : 23-01-23
        $data = $this->master_model->getRecords('scribe_registration', array('regnumber' => $member_no, 'exam_code' => $exam_code, 'subject_code' => $sel_subject));
        // echo $this->db->last_query();DIE;

        $prev_count = count($data);

        if ($prev_count == 0) {
            echo "false";
        } else {
            echo "true";
        }
    }

    public function index()
    {
        $this->load->view('scribe_form/scribe_form', $data);
    }

    /*Apply for scribe redirection*/
    public function getDetails_Scribe($member_no, $exam_code, $subject_code)
    {

        if ($this->session->userdata('userinfo')) {
            $this->session->unset_userdata('userinfo');
        }

        if (isset($_POST['btn_Submit'])) {

            if (!empty($_POST['member_no'])) {
                $config = array(
                    array(
                        'field' => 'member_no',
                        'label' => 'Registration/Membership No.',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'field' => 'exam_code',
                        'label' => 'Exam',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'field' => 'captcha_code',
                        'label' => 'Code',
                        'rules' => 'trim|required|callback_check_login_captcha',
                    ),
                );
                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == true) {
                } else {
                    $this->session->set_flashdata('error', 'Invalid Membership no. or Captcha.');
                    redirect(base_url() . 'Scribe_form/apply_scribe');
                }
            }
        }

        /*REGISTER FORM SUBMIT VALIDATIONS*/
        if (isset($_POST['btnSubmit'])) {
            // echo "<pre>";print_r($_POST);
            $idproof_file         = $declaration_file         = '';
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
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

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_name', 'Exam Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Center Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterCode', 'Center Code', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scribe_name', 'Scribe Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('mobile_scribe', 'Scribe Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            // $this->form_validation->set_rules('dob1', 'Date of Birth', 'trim|required|xss_clean');
            $this->form_validation->set_rules('dob1', 'Date of Birth', 'required|callback_validate_age');
            $this->form_validation->set_rules('scribe_email', 'Scribe Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('emp_details_scribe', 'Scribe details', 'trim|required|xss_clean');
            $this->form_validation->set_rules('photoid_no', 'Photo Id No.', 'trim|required|xss_clean');
            $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
            $this->form_validation->set_rules('declarationform', 'Declaration Form', 'file_required|file_allowed_type[jpg,pdf,doc,jpeg]|file_size_max[300]|callback_declarationform_upload');

            if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
                $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
                $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
                $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
            }

            /*echo'<pre>';
            print_r($_POST);die;*/
            if ($this->form_validation->run() == true) {
                //echo'in ';die;
                $idproof_file     = '';
                $declaration_file = '';

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                $idproof_file         = $outputphoto1         = $outputsign1         = $outputsign1         = $declaration_file         = '';
                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = '';
                $this->session->unset_userdata('enduserinfo');
                $eduqual1 = $eduqual2 = $eduqual3 = '';

                $dob1                 = $_POST["dob1"];
                $dob                  = str_replace('/', '-', $dob1);
                $scribe_dob          = date('Y-m-d', strtotime($dob));

                if ($_POST['optedu'] == 'U') {
                    $eduqual1 = $_POST["eduqual1"];
                } else if ($_POST['optedu'] == 'G') {
                    $eduqual2 = $_POST["eduqual2"];
                } else if ($_POST['optedu'] == 'P') {
                    $eduqual3 = $_POST["eduqual3"];
                }

                if ($_POST["optnletter"] == 'N') {

                    $_POST["optnletter"] = 'N';
                } elseif ($_POST["optnletter"] == 'Y') {
                    $_POST["optnletter"] = 'Y';
                } else {
                    $_POST["optnletter"] = 'Y';
                }
                $date = date('Y-m-d h:i:s');
                //echo "string2";
                // generate dynamic id proof
                $inputidproofphoto = $_POST["hiddenidproofphoto"];
                //print_r($_POST['subject']);//die;

                if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                    $img              = "idproofphoto";
                    $tmp_inputidproof = strtotime($date) . rand(0, 100);
                    $new_filename     = 'idproof_' . $tmp_inputidproof;
                    $config           = array(
                        'upload_path' => './uploads/scribe/idproof',
                        'allowed_types'                         => 'jpg|jpeg|',
                        'file_name'                             => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt             = $this->upload->data();
                            $idproof_file   = $dt['file_name'];
                            $outputidproof1 = base_url() . "uploads/scribe/idproof/" . $idproof_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate declaration form
                $inputdeclarationform = $_POST["hiddendeclarationform"];

                if (isset($_FILES['declarationform']['name']) && ($_FILES['declarationform']['name'] != '')) {
                    $img                  = "declarationform";
                    $tmp_declaration_form = strtotime($date) . rand(0, 100);
                    $new_filename         = 'declaration_' . $tmp_declaration_form;
                    $config               = array(
                        'upload_path' => './uploads/scribe/declaration',
                        'allowed_types'                             => 'jpg|jpeg|',
                        'file_name'                                 => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['declarationform']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt               = $this->upload->data();
                            $declaration_file = $dt['file_name'];
                            $declaration_form = base_url() . "uploads/scribe/declaration/" . $declaration_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string4";
                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_vis_imp_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string5";
                /* Orthopedically handicapped certificate */
                $input_orth_han_cert = $_POST["hidden_orth_han_cert"];
                if (isset($_FILES['scanned_orth_han_cert']['name']) && ($_FILES['scanned_orth_han_cert']['name'] != '')) {
                    $img          = "scanned_orth_han_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'orth_han_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_orth_han_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                //$this->check_scribe();

                //echo $declaration_file;//die;
                $benchmark_disability = $_POST['benchmark_disability'];
                $photoid_no           = $_POST['photoid_no'];
                //print_r($_POST['subject']);die;
                $subject            = $_POST['subject'];
                $emp_details_scribe = $_POST['emp_details_scribe'];
                // echo "<pre><br>";print_r($declaration_file);die;

                if ($idproof_file != '' || $declaration_file != '') {
                    $user_data = array(
                        'member_no' => $_POST["member_no"],
                        'exam_code'                    => $_POST["exam_code"],
                        'exam_date'                    => $_POST["exam_date"],
                        'firstname'                    => $_POST["firstname"],
                        'sel_namesub'                  => $_POST["sel_namesub"],
                        'eduqual'                      => $_POST["eduqual"],
                        'eduqual1'                     => $eduqual1,
                        'eduqual2'                     => $eduqual2,
                        'eduqual3'                     => $eduqual3,
                        'email'                        => $_POST["email"],
                        'lastname'                     => $_POST["lastname"],
                        'middlename'                   => $_POST["middlename"],
                        'mobile'                       => $_POST["mobile"],
                        'exam_name'                    => $_POST["exam_name"],
                        'subject_code'                 => $_POST["subject_code"],
                        'subject_name'                 => $_POST["subject"],
                        'selCenterName'                => $_POST["selCenterName"],
                        'selCenterCode'                => $_POST["selCenterCode"],
                        'scribe_name'                  => $_POST["scribe_name"],
                        'mobile_scribe'                => $_POST["mobile_scribe"],
                        'scribe_dob'                   => $scribe_dob,
                        'scribe_email'                 => $_POST["scribe_email"],
                        'optedu'                       => $_POST["optedu"],
                        'emp_details_scribe'           => $emp_details_scribe,
                        'optnletter'                   => $_POST["optnletter"],
                        'idproofphoto'                 => $outputidproof1,
                        'declarationform'              => $declaration_form,
                        'photoid_no'                   => $photoid_no,
                        'idname'                       => $idproof_file,
                        'declaration'                  => $declaration_file,
                        'benchmark_disability'         => $benchmark_disability,
                        'scanned_vis_imp_cert'         => $output_vis_imp_cert1,
                        'vis_imp_cert_name'            => $scanned_vis_imp_cert_file,
                        'scanned_orth_han_cert'        => $output_orth_han_cert1,
                        'orth_han_cert_name'           => $scanned_orth_han_cert_file,
                        'scanned_cer_palsy_cert'       => $output_cer_palsy_cert1,
                        'cer_palsy_cert_name'          => $scanned_cer_palsy_cert_file,
                        'visually_impaired'            => $_POST["visually_impaired"],
                        'orthopedically_handicapped'   => $_POST["orthopedically_handicapped"],
                        'cerebral_palsy'               => $_POST["cerebral_palsy"], //
                    );
                    // echo "<pre>";print_r($user_data); die;
                    $this->session->set_userdata('enduserinfo', $user_data);

                    //echo "string";
                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Scribe_form/preview');
                }
            }
            //echo validation_errors();die;
        }

        /*REGISTER FORM SUBMIT VALIDATIONS*/

        // Member deatils
        $today = date('Y-m-d');

        $this->db->Where('regnumber', $member_no);
        $this->db->Where('exm_cd', $exam_code);
        $this->db->Where('sub_cd', $subject_code);
        $this->db->order_by('exam_date', 'DESC');
        $this->db->join('admit_card_details b', 'a.regnumber = b.mem_mem_no', 'LEFT');
        $member_details = $this->master_model->getRecords('member_registration a', array('isactive' => '1', 'remark' => '1',), 'a.namesub,a.firstname,a.middlename,a.lastname,a.email,a.mobile,b.center_name,b.center_code,a.regnumber,b.exm_cd,b.exam_date,b.scribe_flag'); //'scribe_flag' => 'Y'
        // Step 1: Check if exam_date exists in admitcard_info
        // $this->db->select('date');
        // $this->db->from('admitcard_info');
        // $this->db->where('mem_mem_no', $member_no);
        // $this->db->where('exm_cd', $exam_code);
        // $this->db->where('sub_cd', $subject_code);
        // $this->db->order_by('date', 'DESC');
        // $this->db->limit(1);
        // $query = $this->db->get();
        // $exam_info = $query->row_array();

        // echo 'SQL3>>' . $this->db->last_query();
        // echo '<br>';
        // // die;
        // print_r($exam_info);
        // die;
        // if (!empty($exam_info['date'])) {

        //     $this->db->Where('regnumber', $member_no);
        //     $this->db->Where('exm_cd', $exam_code);
        //     $this->db->Where('sub_cd', $subject_code);
        //     $this->db->order_by('exam_date', 'DESC');
        //     $this->db->join('admit_card_details b', 'a.regnumber = b.mem_mem_no', 'LEFT');
        //     $member_details = $this->master_model->getRecords('member_registration a', array('isactive' => '1', 'remark' => '1',), 'a.namesub,a.firstname,a.middlename,a.lastname,a.email,a.mobile,b.center_name,b.center_code,a.regnumber,b.exm_cd,b.exam_date,b.scribe_flag'); //'scribe_flag' => 'Y'
        // }
        // echo 'SQL3>>' . $this->db->last_query();
        // echo '<br>';
        // print_r($member_details);
        // die;
        //SUBJECT DETAILS
        // if ($member_no == '510571323 ') {
        //     echo "Test";
        //     exit;
        // }
        $this->db->select('subject_code,subject_description,exam_date');
        $this->db->Where('subject_code', $subject_code);
        $subject_name = $this->master_model->getRecords('subject_master', array('subject_delete' => '0'), 'subject_description,subject_code');
        $SUB = $subject_name[0]['subject_description'];
        // print_r(count($member_details));
        // die;
        // echo 'SQL5>>' . $this->db->last_query();
        // echo '<br>';
        // die;

        //Future date validation for scribe : 23-01-23
        if (count($member_details) < '1') {
            $this->session->set_flashdata('error', "Incorrect exam selection..- " . $SUB);
            redirect(site_url('Scribe_form/scribe'));
        } elseif ($member_details[0]['scribe_flag'] != 'Y') {
            $this->session->set_flashdata('error', "You have not taken the Scribe option while applying for Selected Exam.");
            redirect(site_url('Scribe_form/scribe'));
        }
        // elseif (empty($member_details[0]['exam_date']) || $exam_info['date'] < $today) {
        //     $this->session->set_flashdata('error', "You have not yet successfully applied, or the exam date has passed.");
        //     redirect(site_url('Scribe_form/scribe'));
        // }
        elseif ($member_details[0]['exam_date'] < $today) {
            $this->session->set_flashdata('error', "You have not yet successfully applied, or the exam date has passed.");
            redirect(site_url('Scribe_form/scribe'));
        }
        //Future date validation end : 23-01-23

        //echo 'SQL3>>'.$this->db->last_query();echo'<br>';die;

        //Center Details
        $this->db->Where('exam_code', $exam_code);
        $exam_name = $this->master_model->getRecords('exam_master', array('exam_delete' => '0'), 'description,exam_code');

        //SUBJECT DETAILS
        $this->db->select('subject_code,subject_description,exam_date');
        $this->db->Where('subject_code', $subject_code);
        $subject_name = $this->master_model->getRecords('subject_master', array('subject_delete' => '0'), 'subject_description,subject_code');
        // print_r($subject_name);
        //echo 'SQL5>>'.$this->db->last_query();echo'<br>';//die;

        //Qualification Details
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        //echo 'SQL6>>'.$this->db->last_query();echo'<br>';die;

        //Captacha
        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');

        $data = array('member_details' => $member_details, 'exam_name' => $exam_name, 'subject_name' => $subject_name, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'captcha_img' => $captcha_img);

        // print_r($data);die;
        $this->load->view('scribe_form/register_scribe', $data);
    }

    /*Apply for special assistance/extra time redirection*/
    public function getDetails_Special()
    {
        //echo "string";die;
        //print_r($POST);die;
        if (empty($_POST)) {
            redirect(base_url() . 'Scribe_form/apply_special');
        }
        if ($this->session->userdata('userinfo')) {
            $this->session->unset_userdata('userinfo');
        }
        $member_no    = $_POST['member_no'];
        $exam_code    = $_POST['exam_code'];
        $subject_code = $_POST['subject_code'];
        $this->db->Where('regnumber', $member_no);

        $today = date('Y-m-d');
        $this->db->Where('regnumber', $member_no);
        $this->db->Where('exm_cd', $exam_code);
        $this->db->Where('sub_cd', $subject_code);
        $this->db->order_by('exam_date', 'DESC');
        $this->db->join('admit_card_details b', 'a.regnumber = b.mem_mem_no', 'LEFT');
        $member_details = $this->master_model->getRecords('member_registration a', array('isactive' => '1', 'remark' => '1',), 'a.namesub,a.firstname,a.middlename,a.lastname,a.email,a.mobile,b.center_name,b.center_code,a.regnumber,b.exm_cd,b.exam_date,b.scribe_flag');
        // echo 'SQL3>>'.$this->db->last_query();echo'<br>';die;

        //SUBJECT DETAILS
        $this->db->select('subject_code,subject_description,exam_date');
        $this->db->Where('subject_code', $subject_code);
        $subject_name = $this->master_model->getRecords('subject_master', array('subject_delete' => '0'), 'subject_description,subject_code');
        $SUB = $subject_name[0]['subject_description'];
        // print_r($SUB);
        // exit;
        //echo 'SQL5>>'.$this->db->last_query();echo'<br>';//die;

        //Future date validation for scribe : 23-01-23
        if (count($member_details) < '1') {
            $this->session->set_flashdata('error', "Incorrect exam selection - " . $SUB);
            redirect(site_url('Scribe_form/scribe'));
        } elseif ($member_details[0]['scribe_flag'] != 'Y') {
            $this->session->set_flashdata('error', "You have not taken the Scribe option while applying for Selected Exam.");
            redirect(site_url('Scribe_form/special'));
        } elseif ($member_details[0]['exam_date'] < $today) {
            $this->session->set_flashdata('error', "You have not yet successfully applied, or the exam date has passedd.");
            redirect(site_url('Scribe_form/special'));
        }

        $this->db->Where('regnumber', $member_no);
        $this->db->Where('exam_code', $exam_code);
        $this->db->Where('subject_code', $subject_code);
        $this->db->Where('exam_date >', $today);
        //echo $exam_code;//die;
        $scribe_member_chk = $this->master_model->getRecords('scribe_registration', array('remark' => '1'));
        // echo $this->db->last_query();
        // die;
        if (!empty($scribe_member_chk)) {
            $this->session->set_flashdata('error', "You have already taken the Scribe against the Selected Exam..");
            redirect(site_url('Scribe_form/special'));
        }

        if (isset($_POST['btn_Submit'])) {
            if (!empty($_POST['member_no'])) {
                $config = array(
                    array(
                        'field' => 'member_no',
                        'label' => 'Registration/Membership No.',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'field' => 'exam_code',
                        'label' => 'Exam',
                        'rules' => 'trim|required',
                    ),
                    // array(
                    //     'field' => 'captcha_code',
                    //     'label' => 'Code',
                    //     'rules' => 'trim|required|callback_check_login_captcha',
                    // ),
                );
                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == true) {
                } else {
                    $this->session->set_flashdata('error', 'Invalid Membership no. or Captcha.');
                    redirect(base_url() . 'Scribe_form/special');
                }
            }
        }

        /*submit for register special view*/
        if (isset($_POST['btnSubmit'])) {
            //echo "string";die;
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';

            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_name', 'Exam Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Center Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterCode', 'Center Code', 'trim|required|xss_clean');
            $this->form_validation->set_rules('description', 'Scribe Description', 'trim|required|max_length[500]|xss_clean');
            if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
                $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
                $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
                $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
            }

            if ($this->form_validation->run() == true) {

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = '';

                $this->session->unset_userdata('enduserinfo');

                if ($_POST["optnletter"] == 'N') {

                    $_POST["optnletter"] = 'N';
                } elseif ($_POST["optnletter"] == 'Y') {
                    $_POST["optnletter"] = 'Y';
                } else {
                    $_POST["optnletter"] = 'Y';
                }
                $date = date('Y-m-d h:i:s');

                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_vis_imp_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_orth_han_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                $benchmark_disability = $_POST['benchmark_disability'];
                $description          = $_POST['description'];
                $subject_name         = $_POST['subject_name'];
                $subject_name         = str_replace("amp;", "", $subject_name);
                $exam_name         = $_POST['exam_name'];
                $exam_name         = str_replace("amp;", "", $exam_name);

                $user_data = array(
                    'member_no' => $_POST["member_no"],
                    'exam_code'                    => $_POST["exam_code"],
                    'exam_date'                    => $_POST["exam_date"],
                    'firstname'                    => $_POST["firstname"],
                    'sel_namesub'                  => $_POST["sel_namesub"],
                    'email'                        => $_POST["email"],
                    'lastname'                     => $_POST["lastname"],
                    'middlename'                   => $_POST["middlename"],
                    'mobile'                       => $_POST["mobile"],
                    'exam_name'                    => $exam_name,
                    'subject_code'                 => $_POST["subject_code"],
                    'subject_name'                 => $subject_name,
                    'selCenterName'                => $_POST["selCenterName"],
                    'selCenterCode'                => $_POST["selCenterCode"],
                    'description'                  => $description,
                    'optnletter'                   => $_POST["optnletter"],
                    'special_assistance'           => $_POST["special_assistance"],
                    'extra_time'                   => $_POST["extra_time"],
                    'benchmark_disability'         => $benchmark_disability,
                    'scanned_vis_imp_cert'         => $output_vis_imp_cert1,
                    'vis_imp_cert_name'            => $scanned_vis_imp_cert_file,
                    'scanned_orth_han_cert'        => $output_orth_han_cert1,
                    'orth_han_cert_name'           => $scanned_orth_han_cert_file,
                    'scanned_cer_palsy_cert'       => $output_cer_palsy_cert1,
                    'cer_palsy_cert_name'          => $scanned_cer_palsy_cert_file,
                    'visually_impaired'            => $_POST["visually_impaired"],
                    'orthopedically_handicapped'   => $_POST["orthopedically_handicapped"],
                    'cerebral_palsy'               => $_POST["cerebral_palsy"],
                );

                $this->session->set_userdata('enduserinfo', $user_data);
                //print_r($user_data);die;
                $this->form_validation->set_message('error', "");
                redirect(base_url() . 'Scribe_form/preview_special');
            } else {
                redirect(base_url() . 'Scribe_form/getDetails_Special');
            }
        }
        /*submit for register special view*/

        // Member deatils
        $this->db->Where('regnumber', $member_no);
        $this->db->Where('exm_cd', $exam_code);
        $this->db->Where('sub_cd', $subject_code);
        $this->db->order_by('exam_date', 'DESC');
        $this->db->join('admit_card_details b', 'a.regnumber = b.mem_mem_no', 'LEFT');
        $member_details = $this->master_model->getRecords('member_registration a', array('isactive' => '1', 'remark' => '1', 'scribe_flag' => 'Y'), 'namesub,firstname,middlename,lastname,email,mobile,center_name,center_code,regnumber');
        //echo $this->db->last_query();die;
        //Center Details
        $this->db->Where('exam_code', $exam_code);
        $exam_name = $this->master_model->getRecords('exam_master', array('exam_delete' => '0'), 'description,exam_code');
        //echo $this->db->last_query();die;

        //SUBJECT DETAILS
        $this->db->select('subject_code,subject_description,exam_date');
        $this->db->Where('subject_code', $subject_code);
        $subject_name = $this->master_model->getRecords('subject_master', array('subject_delete' => '0'), 'subject_description,subject_code');
        //print_r($subject_name);die;

        //Qualification Details
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        //Captacha
        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');

        $data = array('member_details' => $member_details, 'exam_name' => $exam_name, 'subject_name' => $subject_name, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'captcha_img' => $captcha_img);
        //print_r($data);die;
        //echo $this->db->last_query();die;
        $this->load->view('scribe_form/register_special', $data);
    }

    public function memberold()
    {
        //echo "memberController";die;
        //print_r($_POST['emp_details_scribe']);
        if (isset($_POST['btnSubmit'])) {

            //print_r($_POST['emp_details_scribe']);die;
            $idproof_file         = $declaration_file         = '';
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
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
            //echo "BYCHMN";die;
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_name', 'Exam Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Center Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterCode', 'Center Code', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scribe_name', 'Scribe Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('mobile_scribe', 'Scribe Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('emp_details_scribe', 'Scribe details', 'trim|required|xss_clean');
            $this->form_validation->set_rules('photoid_no', 'Photo Id No.', 'trim|required|xss_clean');
            $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
            $this->form_validation->set_rules('declarationform', 'Declaration Form', 'file_required|file_allowed_type[jpg,pdf,doc,jpeg]|file_size_max[300]|callback_declarationform_upload');

            //echo "B111MN";die;
            if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
                $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
                $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
                $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
            }
            /* $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_login_captcha1');  */
            //echo "<pre>";print_r($_POST); //die;

            if ($this->form_validation->run() == true) {

                $idproof_file     = '';
                $declaration_file = '';

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                $idproof_file         = $outputphoto1         = $outputsign1         = $outputsign1         = $declaration_file         = '';
                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = '';
                $this->session->unset_userdata('enduserinfo');
                $eduqual1 = $eduqual2 = $eduqual3 = '';
                //echo "string1";
                if ($_POST['optedu'] == 'U') {
                    $eduqual1 = $_POST["eduqual1"];
                } else if ($_POST['optedu'] == 'G') {
                    $eduqual2 = $_POST["eduqual2"];
                } else if ($_POST['optedu'] == 'P') {
                    $eduqual3 = $_POST["eduqual3"];
                }

                if ($_POST["optnletter"] == 'N') {

                    $_POST["optnletter"] = 'N';
                } elseif ($_POST["optnletter"] == 'Y') {
                    $_POST["optnletter"] = 'Y';
                } else {
                    $_POST["optnletter"] = 'Y';
                }
                $date = date('Y-m-d h:i:s');
                //echo "string2";
                // generate dynamic id proof
                $inputidproofphoto = $_POST["hiddenidproofphoto"];
                //print_r($_POST['subject']);//die;

                if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                    $img              = "idproofphoto";
                    $tmp_inputidproof = strtotime($date) . rand(0, 100);
                    $new_filename     = 'idproof_' . $tmp_inputidproof;
                    $config           = array(
                        'upload_path' => './uploads/scribe/idproof',
                        'allowed_types'                         => 'jpg|jpeg|',
                        'file_name'                             => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt             = $this->upload->data();
                            $idproof_file   = $dt['file_name'];
                            $outputidproof1 = base_url() . "uploads/scribe/idproof/" . $idproof_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate declaration form
                $inputdeclarationform = $_POST["hiddendeclarationform"];

                if (isset($_FILES['declarationform']['name']) && ($_FILES['declarationform']['name'] != '')) {
                    $img                  = "declarationform";
                    $tmp_declaration_form = strtotime($date) . rand(0, 100);
                    $new_filename         = 'declaration_' . $tmp_declaration_form;
                    $config               = array(
                        'upload_path' => './uploads/scribe/declaration',
                        'allowed_types'                             => 'jpg|jpeg|',
                        'file_name'                                 => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['declarationform']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt               = $this->upload->data();
                            $declaration_file = $dt['file_name'];
                            $declaration_form = base_url() . "uploads/scribe/declaration/" . $declaration_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string4";
                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_vis_imp_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string5";
                /* Orthopedically handicapped certificate */
                $input_orth_han_cert = $_POST["hidden_orth_han_cert"];
                if (isset($_FILES['scanned_orth_han_cert']['name']) && ($_FILES['scanned_orth_han_cert']['name'] != '')) {
                    $img          = "scanned_orth_han_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'orth_han_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_orth_han_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                //echo "<pre>";print_r($_POST);die;
                //print_r($_POST['photoid_no']);

                //echo $declaration_file;//die;
                $benchmark_disability = $_POST['benchmark_disability'];
                $photoid_no           = $_POST['photoid_no'];
                //print_r($_POST['subject']);die;
                $subject            = $_POST['subject'];
                $emp_details_scribe = $_POST['emp_details_scribe'];
                //echo "<pre>";print_r($_POST);//die;
                if ($idproof_file != '' && $declaration_file != '') {
                    $user_data = array(
                        'member_no' => $_POST["member_no"],
                        'exam_code'                    => $_POST["exam_code"],
                        'exam_date'                    => $_POST["exam_date"],
                        'firstname'                    => $_POST["firstname"],
                        'sel_namesub'                  => $_POST["sel_namesub"],
                        'eduqual'                      => $_POST["eduqual"],
                        'eduqual1'                     => $eduqual1,
                        'eduqual2'                     => $eduqual2,
                        'eduqual3'                     => $eduqual3,
                        'email'                        => $_POST["email"],
                        'lastname'                     => $_POST["lastname"],
                        'middlename'                   => $_POST["middlename"],
                        'mobile'                       => $_POST["mobile"],
                        'exam_name'                    => $_POST["exam_name"],
                        'subject_code'                 => $_POST["subject_code"],
                        'subject_name'                 => $_POST["subject"],
                        'selCenterName'                => $_POST["selCenterName"],
                        'selCenterCode'                => $_POST["selCenterCode"],
                        'scribe_name'                  => $_POST["scribe_name"],
                        'mobile_scribe'                => $_POST["mobile_scribe"],
                        'optedu'                       => $_POST["optedu"],
                        'emp_details_scribe'           => $emp_details_scribe,
                        'optnletter'                   => $_POST["optnletter"],
                        'idproofphoto'                 => $outputidproof1,
                        'declarationform'              => $declaration_form,
                        'photoid_no'                   => $photoid_no,
                        'idname'                       => $idproof_file,
                        'declaration'                  => $declaration_file,
                        'benchmark_disability'         => $benchmark_disability,
                        'scanned_vis_imp_cert'         => $output_vis_imp_cert1,
                        'vis_imp_cert_name'            => $scanned_vis_imp_cert_file,
                        'scanned_orth_han_cert'        => $output_orth_han_cert1,
                        'orth_han_cert_name'           => $scanned_orth_han_cert_file,
                        'scanned_cer_palsy_cert'       => $output_cer_palsy_cert1,
                        'cer_palsy_cert_name'          => $scanned_cer_palsy_cert_file,
                        'visually_impaired'            => $_POST["visually_impaired"],
                        'orthopedically_handicapped'   => $_POST["orthopedically_handicapped"],
                        'cerebral_palsy'               => $_POST["cerebral_palsy"], //
                    );
                    //echo "<pre>";print_r($user_data); die;
                    $this->session->set_userdata('enduserinfo', $user_data);

                    //echo "string";
                    //$this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Scribe_form/preview');
                }
            } else {

                redirect(base_url() . 'Scribe_form/getDetails_Scribe');
            }
        } else {
            echo 'in last else';
            die;
        }
        //echo 'outOK';die;
    }
    /*MEMBER FUNCTION FOR SPECIAL MEMBER POOJA MANE : 27/07/2022*/
    public function memberSpecialold()
    {

        if (isset($_POST['btnSubmit'])) {
            //echo '<pre>'; print_r($_POST);exit;
            //$idproof_file=$declaration_file='';
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';

            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            //$this->form_validation->set_rules('optedu','Qualification','trim|required|xss_clean');

            if (isset($_POST['middlename'])) {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }
            if (isset($_POST['lastname'])) {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_name', 'Exam Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Center Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterCode', 'Center Code', 'trim|required|xss_clean');
            $this->form_validation->set_rules('description', 'Scribe Description', 'trim|required|max_length[500]|xss_clean');

            if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
                $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
                $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
                $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
            }
            /**/
            /* $this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_login_captcha1');  */
            //print_r($_FILES); die;
            if ($this->form_validation->run() == true) {
                //$idproof_file = '';
                //$declaration_file = '';
                //echo "success";exit;
                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                //$idproof_file=$outputphoto1=$outputsign1=$outputsign1=$declaration_file='';
                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = '';

                $this->session->unset_userdata('enduserinfo');

                if ($_POST["optnletter"] == 'N') {

                    $_POST["optnletter"] = 'N';
                } elseif ($_POST["optnletter"] == 'Y') {
                    $_POST["optnletter"] = 'Y';
                } else {
                    $_POST["optnletter"] = 'Y';
                }
                $date = date('Y-m-d h:i:s');

                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_vis_imp_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_orth_han_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                $benchmark_disability = $_POST['benchmark_disability'];
                $subject_name         = $_POST['subject_name'];
                $subject_name         = str_replace("amp;", "", $subject_name);
                $exam_name         = $_POST['exam_name'];
                $exam_name         = str_replace("amp;", "", $exam_name);
                $description          = $_POST['description'];
                $description = str_replace("\n", "", $description);
                $description = str_replace("&", "", $description);
                $description = str_replace(",", "", $description);
                $description = str_replace("\r", "", $description);
                //print_r($description);die;
                //print_r($subject_name);die;
                /*if($idproof_file!='' && $declaration_file !='' )
                {*/

                //echo'up';//exit;
                $user_data = array(
                    'member_no' => $_POST["member_no"],
                    'exam_code'                    => $_POST["exam_code"],
                    'exam_date'                    => $_POST["exam_date"],
                    'firstname'                    => $_POST["firstname"],
                    'sel_namesub'                  => $_POST["sel_namesub"],
                    'email'                        => $_POST["email"],
                    'lastname'                     => $_POST["lastname"],
                    'middlename'                   => $_POST["middlename"],
                    'mobile'                       => $_POST["mobile"],
                    'exam_name'                    => $exam_name,
                    'subject_code'                 => $_POST["subject_code"],
                    'subject_name'                 => $subject_name,
                    'selCenterName'                => $_POST["selCenterName"],
                    'selCenterCode'                => $_POST["selCenterCode"],
                    'description'                  => $description,
                    'optnletter'                   => $_POST["optnletter"],
                    'special_assistance'           => $_POST["special_assistance"],
                    'extra_time'                   => $_POST["extra_time"],
                    'benchmark_disability'         => $benchmark_disability,
                    'scanned_vis_imp_cert'         => $output_vis_imp_cert1,
                    'vis_imp_cert_name'            => $scanned_vis_imp_cert_file,
                    'scanned_orth_han_cert'        => $output_orth_han_cert1,
                    'orth_han_cert_name'           => $scanned_orth_han_cert_file,
                    'scanned_cer_palsy_cert'       => $output_cer_palsy_cert1,
                    'cer_palsy_cert_name'          => $scanned_cer_palsy_cert_file,
                    'visually_impaired'            => $_POST["visually_impaired"],
                    'orthopedically_handicapped'   => $_POST["orthopedically_handicapped"],
                    'cerebral_palsy'               => $_POST["cerebral_palsy"], //
                );
                //echo '<pre>'; print_r($user_data);die;
                $this->session->set_userdata('enduserinfo', $user_data);

                $this->form_validation->set_message('error', "");
                redirect(base_url() . 'Scribe_form/preview_special');
                //}
            } else {
                //echo validation_errors(); die;
                //echo"OUT OF FORM preview";die;
                redirect(base_url() . 'Scribe_form/getDetails_Special');
            }
        }
    }
    /*MEMBER FUNCTION SPECIAL END MEMBER POOJA MANE : 27/07/2022*/
    public function preview()
    {
        //echo "string";die;
        //Qualification Details
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $data = array('undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate);
        // echo '<pre>'; print_r($data);die;
        $this->load->view('scribe_form/preview_scribe', $data);
    }
    /*PREVIEW FUNCTION FOR SPECIAL MEMBER POOJA MANE : 27/07/2022*/
    public function preview_special()
    {
        $this->load->view('scribe_form/preview_special', $data);
    }
    /*PREVIEW FUNCTION FOR SPECIAL MEMBER POOJA MANE : 27/07/2022*/
    public function addscribe()
    {
        // echo '<pre>'; print_r($this->session->userdata['enduserinfo']); echo '</pre>'; die;
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        //echo "string";die;
        $idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];
        $photoid_no        = $this->session->userdata['enduserinfo']['photoid_no'];
        $img_response      = check_files_exist('./uploads/scribe/idproof/' . $idproofphoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {
            $image_error_flag = 1;
        }

        $declarationphoto_file = $this->session->userdata['enduserinfo']['declaration'];
        $img_response          = check_files_exist('./uploads/scribe/declaration/' . $declarationphoto_file); //update_image_name_helper.php
        if ($img_response['flag'] != 'success') {
            $image_error_flag = 1;
        }
        if ($image_error_flag == 1) {
            $this->session->set_flashdata('error', 'Please upload valid image(s)');
            redirect(base_url() . 'Scribe_form/scribe/');
        }
        $optedu = $this->session->userdata['enduserinfo']['optedu'];
        if ($optedu == 'U') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual1'];
        } elseif ($optedu == 'G') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual2'];
        } else if ($optedu == 'P') {
            $specify_qualification = $this->session->userdata['enduserinfo']['eduqual3'];
        }
        /* Benchmark */
        $benchmark_disability        = $this->session->userdata['enduserinfo']['benchmark_disability'];
        $scanned_vis_imp_cert_file   = $this->session->userdata['enduserinfo']['vis_imp_cert_name'];
        $scanned_orth_han_cert_file  = $this->session->userdata['enduserinfo']['orth_han_cert_name'];
        $scanned_cer_palsy_cert_file = $this->session->userdata['enduserinfo']['cer_palsy_cert_name'];

        $visually_impaired          = $this->session->userdata['enduserinfo']['visually_impaired'];
        $orthopedically_handicapped = $this->session->userdata['enduserinfo']['orthopedically_handicapped'];
        $cerebral_palsy             = $this->session->userdata['enduserinfo']['cerebral_palsy'];
        if ($benchmark_disability == 'N') {
            $scanned_vis_imp_cert_file   = '';
            $scanned_orth_han_cert_file  = '';
            $scanned_cer_palsy_cert_file = '';
            $visually_impaired           = '';
            $orthopedically_handicapped  = '';
            $cerebral_palsy              = '';
        }
        if ($visually_impaired == 'N') {
            $scanned_vis_imp_cert_file = '';
        }
        if ($orthopedically_handicapped == 'N') {
            $scanned_orth_han_cert_file = '';
        }
        if ($cerebral_palsy == 'N') {
            $scanned_cer_palsy_cert_file = '';
        }

        if ($benchmark_disability == 'Y') {
            if ($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N') {
                $benchmark_disability = 'N';
            }
        }

        $regnumber = $this->session->userdata['enduserinfo']['member_no'];
        $exam_code = $this->session->userdata['enduserinfo']['exam_code'];
        $subject_code = $this->session->userdata['enduserinfo']['subject_code'];
        $scribe_dob = $this->session->userdata['enduserinfo']['scribe_dob'];
        $scribe_email = $this->session->userdata['enduserinfo']['scribe_email'];

        $this->db->Where('regnumber', $regnumber);
        $this->db->Where('s.exam_code', $exam_code);
        $this->db->Where('s.subject_code', $subject_code);
        $this->db->select('scribe_uid');
        $old_scribe = $this->master_model->getRecords('scribe_registration s', array('remark' => '1'));
        //$old_scribe = $this->master_model->getRecords('scribe_registration s');

        if (!empty($old_scribe)) {
            $scribe_uid = $old_scribe[0]['scribe_uid'];
            $update_data = array(
                'scribe_approve' => '3',
                'remark' => '3'
            );
            $this->master_model->updateRecord('scribe_registration', $update_data, array('scribe_uid' => $scribe_uid));
        }
        //echo $this->db->last_query();die;

        $insert_info = array(
            'regnumber '                 => $this->session->userdata['enduserinfo']['member_no'],
            //'scribe_uid' => $scribe_uid,
            'exam_code '                 => $this->session->userdata['enduserinfo']['exam_code'],
            'exam_date '                 => $this->session->userdata['enduserinfo']['exam_date'],
            'exam_name '                 => $this->session->userdata['enduserinfo']['exam_name'],
            'subject_name '              => $this->session->userdata['enduserinfo']['subject_name'],
            'subject_code '              => $this->session->userdata['enduserinfo']['subject_code'],
            'namesub'                    => $this->session->userdata['enduserinfo']['sel_namesub'],
            'firstname'                  => $this->session->userdata['enduserinfo']['firstname'],
            'middlename'                 => $this->session->userdata['enduserinfo']['middlename'],
            'lastname'                   => $this->session->userdata['enduserinfo']['lastname'],
            'email'                      => $this->session->userdata['enduserinfo']['email'],
            'mobile'                     => $this->session->userdata['enduserinfo']['mobile'],
            'center_name'                => $this->session->userdata['enduserinfo']['selCenterName'],
            'center_code'                => $this->session->userdata['enduserinfo']['selCenterCode'],
            'name_of_scribe'             => $this->session->userdata['enduserinfo']['scribe_name'],
            'mobile_scribe'              => $this->session->userdata['enduserinfo']['mobile_scribe'],
            'scribe_dob'                 => $this->session->userdata['enduserinfo']['scribe_dob'],
            'scribe_email'               => $this->session->userdata['enduserinfo']['scribe_email'],
            'qualification'              => $optedu,
            'emp_details_scribe'         => $this->session->userdata['enduserinfo']['emp_details_scribe'],
            'specify_qualification'      => $specify_qualification,
            'benchmark_disability'       => $benchmark_disability,
            'vis_imp_cert_img'           => $scanned_vis_imp_cert_file,
            'orth_han_cert_img'          => $scanned_orth_han_cert_file,
            'cer_palsy_cert_img'         => $scanned_cer_palsy_cert_file,
            'visually_impaired'          => $visually_impaired,
            'orthopedically_handicapped' => $orthopedically_handicapped,
            'cerebral_palsy'             => $cerebral_palsy,
            'photoid_no'                 => $this->session->userdata['enduserinfo']['photoid_no'],
            'idproofphoto'               => $idproofphoto_file,
            'declaration_img'            => $declarationphoto_file,
            'created_on'                 => date('Y-m-d'),
            'remark'                     => '1',
        );

        $scribe_id = $this->master_model->insertRecord('scribe_registration', $insert_info, true);
        // echo $this->db->last_query();die;
        $scribe_uid = generate_scribe_uid($scribe_id);
        //print_r($scribe_uid);die();
        $update_data = array('scribe_uid' => $scribe_uid);

        $this->master_model->updateRecord('scribe_registration', $update_data, array('id' => $scribe_id));

        redirect(base_url() . "Scribe_form/acknowledge/" . base64_encode($scribe_id));
    }

    /*ADD FUNCTION FOR SPECIAL MEMBER POOJA MANE : 27/07/2022*/
    public function addspecial()
    {
        //echo '<pre>'; print_r($this->session->userdata['enduserinfo']); echo '</pre>'; die;
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }

        /* Benchmark */
        $benchmark_disability        = $this->session->userdata['enduserinfo']['benchmark_disability'];
        $scanned_vis_imp_cert_file   = $this->session->userdata['enduserinfo']['vis_imp_cert_name'];
        $scanned_orth_han_cert_file  = $this->session->userdata['enduserinfo']['orth_han_cert_name'];
        $scanned_cer_palsy_cert_file = $this->session->userdata['enduserinfo']['cer_palsy_cert_name'];

        //print_r($this->session->userdata['enduserinfo']['firstname']);die;

        $visually_impaired          = $this->session->userdata['enduserinfo']['visually_impaired'];
        $orthopedically_handicapped = $this->session->userdata['enduserinfo']['orthopedically_handicapped'];
        $cerebral_palsy             = $this->session->userdata['enduserinfo']['cerebral_palsy'];
        if ($benchmark_disability == 'N') {
            $scanned_vis_imp_cert_file   = '';
            $scanned_orth_han_cert_file  = '';
            $scanned_cer_palsy_cert_file = '';
            $visually_impaired           = '';
            $orthopedically_handicapped  = '';
            $cerebral_palsy              = '';
        }
        if ($visually_impaired == 'N') {
            $scanned_vis_imp_cert_file = '';
        }
        if ($orthopedically_handicapped == 'N') {
            $scanned_orth_han_cert_file = '';
        }
        if ($cerebral_palsy == 'N') {
            $scanned_cer_palsy_cert_file = '';
        }

        if ($benchmark_disability == 'Y') {
            if ($visually_impaired == 'N' && $orthopedically_handicapped == 'N' && $cerebral_palsy == 'N') {
                $benchmark_disability = 'N';
            }
        }

        //echo '<pre>'; print_r($this->session->userdata['enduserinfo']); echo 'above inserrty'; //die;
        $insert_info = array(
            'regnumber '                 => $this->session->userdata['enduserinfo']['member_no'],
            'exam_date '                 => $this->session->userdata['enduserinfo']['exam_date'],
            'exam_code '                 => $this->session->userdata['enduserinfo']['exam_code'],
            'exam_name '                 => $this->session->userdata['enduserinfo']['exam_name'],
            'subject_code '              => $this->session->userdata['enduserinfo']['subject_code'],
            'subject_name '              => $this->session->userdata['enduserinfo']['subject_name'],
            'special_assistance'         => $this->session->userdata['enduserinfo']['special_assistance'],
            'extra_time'                 => $this->session->userdata['enduserinfo']['extra_time'],
            'description'                => $this->session->userdata['enduserinfo']['description'],
            'namesub'                    => $this->session->userdata['enduserinfo']['sel_namesub'],
            'firstname'                  => $this->session->userdata['enduserinfo']['firstname'],
            'middlename'                 => $this->session->userdata['enduserinfo']['middlename'],
            'lastname'                   => $this->session->userdata['enduserinfo']['lastname'],
            'email'                      => $this->session->userdata['enduserinfo']['email'],
            'mobile'                     => $this->session->userdata['enduserinfo']['mobile'],
            'center_name'                => $this->session->userdata['enduserinfo']['selCenterName'],
            'center_code'                => $this->session->userdata['enduserinfo']['selCenterCode'],
            'benchmark_disability'       => $benchmark_disability,
            'vis_imp_cert_img'           => $scanned_vis_imp_cert_file,
            'orth_han_cert_img'          => $scanned_orth_han_cert_file,
            'cer_palsy_cert_img'         => $scanned_cer_palsy_cert_file,
            'visually_impaired'          => $visually_impaired,
            'orthopedically_handicapped' => $orthopedically_handicapped,
            'cerebral_palsy'             => $cerebral_palsy,
            'created_on'                 => date('Y-m-d'),
            'remark'                     => '1',
        );
        //
        $scribe_id  = $this->master_model->insertRecord('scribe_registration', $insert_info, true);
        $scribe_uid = generate_scribe_uid($scribe_id);

        $update_data = array('scribe_uid' => $scribe_uid);
        $this->master_model->updateRecord('scribe_registration', $update_data, array('id' => $scribe_id));
        //echo'succ3';die;
        redirect(base_url() . "Scribe_form/acknowledge_special/" . base64_encode($scribe_id));
    }

    /*ADD FUNCTION FOR SPECIAL MEMBER END POOJA MANE : 27/07/2022*/
    public function acknowledge($scribe_id)
    {
        //echo '<pre>';print_r($this->session->userdata['enduserinfo']);die;
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        $regnumber = $this->session->userdata['enduserinfo']['member_no'];
        $exam_code = $this->session->userdata['enduserinfo']['exam_code'];
        $scribe_id = base64_decode($scribe_id);
        $data      = array();
        $user_info = array();

        $user_info = $this->master_model->getRecords('scribe_registration', array('id' => $scribe_id, 'remark' => '1'));
        //echo $this->db->last_query();die;
        //print_r($user_info);die;
        /*$name           = $user_info[0]['firstname'];
        $name_of_scribe = $user_info[0]['name_of_scribe'];
        $mobile_scribe  = $user_info[0]['mobile_scribe'];
        $exam_name      = $user_info[0]['exam_name'];
        $center_name    = $user_info[0]['center_name'];
        $email          = $user_info[0]['email'];
        $scribe_uid     = $user_info[0]['scribe_uid'];
        $subject_name   = $user_info[0]['subject_name'];*/

        /*if (!empty($user_info)) {
            $final_str .= 'Dear Candidate ' . $name . ',';
            $final_str .= '<br/><br/>';
            $final_str .= 'Warm Greetings from IIBF!';
            $final_str .= '<br/><br/>';
            $final_str .= 'You have registered for the Scribe!';
            $final_str .= '<br/><br/>';
            $final_str .= 'Below are scribe Details.';
            $final_str .= '<br/><br/>';
            $final_str .= 'Application ID :- ' . $scribe_uid;
            $final_str .= '<br/>';
            $final_str .= 'Exam Name :- ' . $exam_name;
            $final_str .= '<br/>';
            $final_str .= 'Exam Name :- ' . $subject_name;
            $final_str .= '<br/>';
            $final_str .= 'Center Name :- ' . $center_name;
            $final_str .= '<br/>';
            $final_str .= 'Scribe Name :- ' . $name_of_scribe;
            $final_str .= '<br/>';
            $final_str .= 'Scribe Mobile no. :- ' . $mobile_scribe;
            $final_str .= '<br/><br/>';
            $final_str .= 'Thanks and Regards';
            $final_str .= '<br/>';
            $final_str .= 'IIBF TEAM';

            
            $info_arr = array(
                //'to'      => 'harshu.joy26@gmail.com',
                //'to'=>$email,
                'from'    => 'noreply@iibf.org.in',
                'subject' => 'IIBF: Scribe Registration',
                'message' => $final_str,
            );

            //print_r($final_str);die;
            if ($this->Emailsending->mailsend_attch($info_arr, '')) {
                $update_data = array(
                    'email_flag' => '1',
                );

                $this->master_model->updateRecord('scribe_registration',
                    $update_data,
                    array('regnumber' => $regnumber,
                        'exam_code'       => $exam_code,
                        'remark'          => '1'));
            }
        }*/

        $data['user_info'] = $user_info;
        $this->load->view('scribe_form/thankyou', $data);
        $this->session->unset_userdata('enduserinfo');
    }

    /*ACKNOWLEDGE FUNCTION FOR SPECIAL MEMBER POOJA MANE : 27/07/2022*/
    public function acknowledge_special($scribe_id)
    {
        //echo "string";die;
        if (!$this->session->userdata['enduserinfo']) {
            redirect(base_url());
        }
        $regnumber = $this->session->userdata['enduserinfo']['member_no'];
        $exam_code = $this->session->userdata['enduserinfo']['exam_code'];
        $scribe_id = base64_decode($scribe_id);
        $data      = array();
        $user_info = array();

        $user_info = $this->master_model->getRecords('scribe_registration', array('id' => $scribe_id, 'remark' => '1'));
        //echo $this->db->last_query();die;
        /*if (count($user_info)) {
            $name         = $user_info[0]['firstname'];
            $exam_name    = $user_info[0]['exam_name'];
            $center_name  = $user_info[0]['center_name'];
            $email        = $user_info[0]['email'];
            $scribe_uid   = $user_info[0]['scribe_uid'];
            $subject_name = $user_info[0]['subject_name'];

            if (!empty($user_info)) {
                $final_str .= 'Dear Candidate ' . $name . ',';
                $final_str .= '<br/><br/>';
                $final_str .= 'Warm Greetings from IIBF!';
                $final_str .= '<br/><br/>';
                $final_str .= 'You have registered for Special Assistance/Extra time scribe!';
                $final_str .= '<br/><br/>';
                $final_str .= 'Below are Details.';
                $final_str .= '<br/><br/>';
                $final_str .= 'Application ID :- ' . $scribe_uid;
                $final_str .= '<br/>';
                $final_str .= 'Exam Name :- ' . $exam_name;
                $final_str .= '<br/>';
                $final_str .= 'Subject Name :- ' . $subject_name;
                $final_str .= '<br/>';
                $final_str .= 'Center Name :- ' . $center_name;
                $final_str .= '<br/><br/>';
                $final_str .= 'Thanks and Regards';
                $final_str .= '<br/>';
                $final_str .= 'IIBF TEAM';

                $info_arr = array( //'to'=>$email,
                    'to'      => 'harshu.joy26@gmail.com',
                    'from'    => 'noreply@iibf.org.in',
                    'subject' => 'IIBF: Scribe Registration',
                    'message' => $final_str,
                );

                //print_r($final_str);
                //echo 'MAIL';die();
                if ($this->Emailsending->mailsend_attch($info_arr, '')) {
                    $update_data = array(
                        'email_flag' => '1',
                    );

                    $this->master_model->updateRecord('scribe_registration',
                        $update_data,
                        array('regnumber' => $regnumber,
                            'exam_code'       => $exam_code,
                            'remark'          => '1'));
                }
            }
        }*/

        $data['user_info'] = $user_info;
        $this->load->view('scribe_form/thankyou', $data);
        $this->session->unset_userdata('enduserinfo');
    }
    /*ACKNOWLEDGE FUNCTION FOR SPECIAL MEMBER END POOJA MANE : 27/07/2022*/

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
            $this->form_validation->set_message('idproofphoto_upload', "No declaration file selected");
            return false;
        }
    }
    public function check_member_no_ajax()
    {
        $result['flag']     = 'error';
        $result['response'] = 'Please enter valid Membership/Registration No';

        if (isset($_POST) && $_POST['member_no'] != "") {
            $member_no = $this->security->xss_clean(trim($this->input->post('member_no')));
            //$member_type = $this->security->xss_clean(trim($this->input->post('member_type')));

            $member_info = array();

            $member_info = $this->master_model->getRecords('member_registration', array('regnumber' => $member_no, 'isactive' => '1'), 'regid');

            if (!empty($member_info) && count($member_info) > 0) {
                $result['flag'] = 'success';
            }
        }

        echo json_encode($result);
    }
    public function check_captcha_code_ajax()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $session_name    = 'LOGIN_SCRIBE';
            $session_captcha = '';

            if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
                $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
            }

            if (isset($_SESSION[$session_name])) {
                $session_captcha = $_SESSION[$session_name];
            }

            $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

            if ($captcha_code == $session_captcha) {
                echo 'true';
            } else {
                echo "false";
            }
        } else {
            echo "false";
        }
    }

    public function generate_captcha_ajax()
    {
        $session_name = 'LOGIN_SCRIBE';
        if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
            $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
        }

        $this->load->model('Captcha_model');
        echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
        /*echo $captcha_imgs = $this->Captcha_model->generate_captcha_img($session_name);*/
    }
    public function check_captcha_code_ajax1()
    {
        if (isset($_POST) && count($_POST) > 0) {
            $session_name    = 'LOGIN_SCRIBE_FORM';
            $session_captcha = '';

            if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
                $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
            }

            if (isset($_SESSION[$session_name])) {
                $session_captcha = $_SESSION[$session_name];
            }

            $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

            if ($captcha_code == $session_captcha) {
                echo 'true';
            } else {
                echo "false";
            }
        } else {
            echo "false";
        }
    }
    public function generate_captcha_ajax1()
    {
        $session_name = 'LOGIN_SCRIBE_FORM';
        if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
            $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
        }

        $this->load->model('Captcha_model');
        echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);
    }
    public function check_login_captcha()
    {

        $session_name    = 'LOGIN_SCRIBE';
        $session_captcha = '';
        if (isset($_SESSION[$session_name])) {
            $session_captcha = $_SESSION[$session_name];
        }

        $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

        if ($captcha_code == $session_captcha) {
            return true;
        } else {
            $this->form_validation->set_message('check_login_captcha', 'Please enter correct code');
            return false;
        }
    }
    public function check_login_captcha1()
    {

        $session_name    = 'LOGIN_SCRIBE_FORM';
        $session_captcha = '';
        if (isset($_SESSION[$session_name])) {
            $session_captcha = $_SESSION[$session_name];
        }

        $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));

        if ($captcha_code == $session_captcha) {
            return true;
        } else {
            $this->form_validation->set_message('check_login_captcha', 'Please enter correct code');
            return false;
        }
    }

    public function check_mobile_exist_ajax()
    {
        if ($_POST['mobile_scribe'] != '' && $_POST["exam_date"] != '') {
            $mobile_scribe = $_POST['mobile_scribe'];
            $exam_date     = $_POST["exam_date"];

            //$prev_count = $this->master_model->getRecordCount('scribe_registration', array('mobile_scribe' => $mobile_scribe, 'exam_date' => $exam_date, 'remark'=>'1'), 'id');
            $prev_count = $this->master_model->getRecordCount('scribe_registration', array('mobile_scribe' => $mobile_scribe, 'exam_date' => $exam_date), 'id');


            if ($prev_count == 0) {
                echo "true";
            } else {

                echo "false";
            }
        } else {
            echo "false";
        }
    }

    //call back for [photoid] duplication
    public function check_photoid_exist_ajax()
    {
        if ($_POST['photoid_no'] != '' && $_POST["exam_date"] != '') {
            $photoid_no = $_POST['photoid_no'];
            $exam_date  = $_POST["exam_date"];

            $prev_count = $this->master_model->getRecordCount('scribe_registration', array('photoid_no' => $photoid_no, 'exam_date' => $exam_date), 'id');


            if ($prev_count == 0) {
                echo "true";
            } else {

                echo "false";
            }
        } else {
            echo "false";
        }
    }

    /*Apply for scribe redirection*/
    public function getDetails_Scribe_OLD()
    {

        if ($this->session->userdata('userinfo')) {
            $this->session->unset_userdata('userinfo');
        }

        $member_no    = $_POST['member_no'];
        $exam_code    = $_POST['exam_code'];
        $subject_code = $_POST['subject_code'];
        $this->db->Where('regnumber', $member_no);
        $this->db->Where('me.exam_code', $exam_code);
        $this->db->Where('subject_code', $subject_code);
        $this->db->join('subject_master sm', 'sm.exam_code = me.exam_code');
        $scribe_check = $this->master_model->getRecords('member_exam me', array('scribe_flag' => 'Y', 'pay_status' => '1', 'institute_id' => '0'));
        //print_r($scribe_check);die();
        if (empty($scribe_check)) {
            // echo "string";die;
            $this->session->set_flashdata('error', "You have not taken the Scribe while applying for Selected Exam.");
            redirect(site_url('Scribe_form'));
        }
        $this->db->Where('regnumber', $member_no);
        $this->db->Where('s.exam_code', $exam_code);
        $this->db->Where('s.subject_code', $subject_code);
        $this->db->Where('exam_date >', $today);
        //$this->db->join('subject_master sm', 'sm.exam_code = me.exam_code');
        $scribe_member_chk = $this->master_model->getRecords('scribe_registration s', array('remark' => '1'));

        if (!empty($scribe_member_chk)) {
            //echo "string";die;
            $this->session->set_flashdata('error', "You have already taken the Scribe against the Selected Exam.");
            redirect(site_url('Scribe_form'));
        }
        //echo "out";die;
        if (isset($_POST['btn_Submit'])) {

            if (!empty($_POST['member_no'])) {
                $config = array(
                    array(
                        'field' => 'member_no',
                        'label' => 'Registration/Membership No.',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'field' => 'exam_code',
                        'label' => 'Exam',
                        'rules' => 'trim|required',
                    ),
                    array(
                        'field' => 'captcha_code',
                        'label' => 'Code',
                        'rules' => 'trim|required|callback_check_login_captcha',
                    ),
                );
                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == true) {
                } else {
                    $this->session->set_flashdata('error', 'Invalid Membership no. or Captcha.');
                    redirect(base_url() . 'Scribe_form');
                }
            }
        }

        /*REGISTER FORM SUBMIT VALIDATIONS*/
        if (isset($_POST['btnSubmit'])) {
            //echo "string";die;
            $idproof_file         = $declaration_file         = '';
            $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_cer_palsy_cert_file = '';
            $this->form_validation->set_rules('sel_namesub', 'Name Prefix', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|xss_clean');
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

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('exam_name', 'Exam Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterName', 'Center Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('selCenterCode', 'Center Code', 'trim|required|xss_clean');
            $this->form_validation->set_rules('scribe_name', 'Scribe Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('mobile_scribe', 'Scribe Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('emp_details_scribe', 'Scribe details', 'trim|required|xss_clean');
            $this->form_validation->set_rules('photoid_no', 'Photo Id No.', 'trim|required|xss_clean');
            $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
            $this->form_validation->set_rules('declarationform', 'Declaration Form', 'file_required|file_allowed_type[jpg,pdf,doc,jpeg]|file_size_max[300]|callback_declarationform_upload');

            if (isset($_POST['visually_impaired']) && $_POST['visually_impaired'] == 'Y') {
                $this->form_validation->set_rules('scanned_vis_imp_cert', 'Visually impaired Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['orthopedically_handicapped']) && $_POST['orthopedically_handicapped'] == 'Y') {
                $this->form_validation->set_rules('scanned_orth_han_cert', 'Orthopedically handicapped Attach scan copy of PWD certificate', 'required');
            }
            if (isset($_POST['cerebral_palsy']) && $_POST['cerebral_palsy'] == 'Y') {
                $this->form_validation->set_rules('scanned_cer_palsy_cert', 'Cerebral palsy Attach scan copy of PWD certificate', 'required');
            }

            /*echo'<pre>';
             print_r($_POST);die;*/
            if ($this->form_validation->run() == true) {
                //echo'in ';die;
                $idproof_file     = '';
                $declaration_file = '';

                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_orth_han_cert_file = $scanned_vis_imp_cert_file = '';

                $idproof_file         = $outputphoto1         = $outputsign1         = $outputsign1         = $declaration_file         = '';
                $output_vis_imp_cert1 = $output_orth_han_cert1 = $output_cer_palsy_cert1 = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = $scanned_vis_imp_cert_file = '';
                $this->session->unset_userdata('enduserinfo');
                $eduqual1 = $eduqual2 = $eduqual3 = '';

                if ($_POST['optedu'] == 'U') {
                    $eduqual1 = $_POST["eduqual1"];
                } else if ($_POST['optedu'] == 'G') {
                    $eduqual2 = $_POST["eduqual2"];
                } else if ($_POST['optedu'] == 'P') {
                    $eduqual3 = $_POST["eduqual3"];
                }

                if ($_POST["optnletter"] == 'N') {

                    $_POST["optnletter"] = 'N';
                } elseif ($_POST["optnletter"] == 'Y') {
                    $_POST["optnletter"] = 'Y';
                } else {
                    $_POST["optnletter"] = 'Y';
                }
                $date = date('Y-m-d h:i:s');
                //echo "string2";
                // generate dynamic id proof
                $inputidproofphoto = $_POST["hiddenidproofphoto"];
                //print_r($_POST['subject']);//die;

                if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                    $img              = "idproofphoto";
                    $tmp_inputidproof = strtotime($date) . rand(0, 100);
                    $new_filename     = 'idproof_' . $tmp_inputidproof;
                    $config           = array(
                        'upload_path' => './uploads/scribe/idproof',
                        'allowed_types'                         => 'jpg|jpeg|',
                        'file_name'                             => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt             = $this->upload->data();
                            $idproof_file   = $dt['file_name'];
                            $outputidproof1 = base_url() . "uploads/scribe/idproof/" . $idproof_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate declaration form
                $inputdeclarationform = $_POST["hiddendeclarationform"];

                if (isset($_FILES['declarationform']['name']) && ($_FILES['declarationform']['name'] != '')) {
                    $img                  = "declarationform";
                    $tmp_declaration_form = strtotime($date) . rand(0, 100);
                    $new_filename         = 'declaration_' . $tmp_declaration_form;
                    $config               = array(
                        'upload_path' => './uploads/scribe/declaration',
                        'allowed_types'                             => 'jpg|jpeg|',
                        'file_name'                                 => $new_filename
                    );

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['declarationform']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt               = $this->upload->data();
                            $declaration_file = $dt['file_name'];
                            $declaration_form = base_url() . "uploads/scribe/declaration/" . $declaration_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string4";
                /* Visually impaired certificate */
                $input_vis_imp_cert = $_POST["hidden_vis_imp_cert"];
                if (isset($_FILES['scanned_vis_imp_cert']['name']) && ($_FILES['scanned_vis_imp_cert']['name'] != '')) {
                    $img          = "scanned_vis_imp_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'vis_imp_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_vis_imp_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $file                      = $dt['file_name'];
                            $scanned_vis_imp_cert_file = $dt['file_name'];
                            $output_vis_imp_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_vis_imp_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }
                //echo "string5";
                /* Orthopedically handicapped certificate */
                $input_orth_han_cert = $_POST["hidden_orth_han_cert"];
                if (isset($_FILES['scanned_orth_han_cert']['name']) && ($_FILES['scanned_orth_han_cert']['name'] != '')) {
                    $img          = "scanned_orth_han_cert";
                    $tmp_nm       = strtotime($date) . rand(0, 100);
                    $new_filename = 'orth_han_cert_' . $tmp_nm;
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_orth_han_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                         = $this->upload->data();
                            $file                       = $dt['file_name'];
                            $scanned_orth_han_cert_file = $dt['file_name'];
                            $output_orth_han_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_orth_han_cert_file;
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
                    $config       = array(
                        'upload_path' => './uploads/scribe/disability',
                        'allowed_types'                     => 'jpg|jpeg|',
                        'file_name'                         => $new_filename
                    );
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scanned_cer_palsy_cert']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                          = $this->upload->data();
                            $file                        = $dt['file_name'];
                            $scanned_cer_palsy_cert_file = $dt['file_name'];
                            $output_cer_palsy_cert1      = base_url() . "uploads/scribe/disability/" . $scanned_cer_palsy_cert_file;
                        } else {
                            $var_errors .= $this->upload->display_errors();
                        }
                    } else {
                        $var_errors .= 'The filetype you are attempting to upload is not allowed';
                    }
                }

                //$this->check_scribe();

                //echo $declaration_file;//die;
                $benchmark_disability = $_POST['benchmark_disability'];
                $photoid_no           = $_POST['photoid_no'];
                //print_r($_POST['subject']);die;
                $subject            = $_POST['subject'];
                $emp_details_scribe = $_POST['emp_details_scribe'];
                //echo "<pre>";print_r($_POST);//die;
                if ($idproof_file != '' && $declaration_file != '') {
                    $user_data = array(
                        'member_no' => $_POST["member_no"],
                        'exam_code'                    => $_POST["exam_code"],
                        'exam_date'                    => $_POST["exam_date"],
                        'firstname'                    => $_POST["firstname"],
                        'sel_namesub'                  => $_POST["sel_namesub"],
                        'eduqual'                      => $_POST["eduqual"],
                        'eduqual1'                     => $eduqual1,
                        'eduqual2'                     => $eduqual2,
                        'eduqual3'                     => $eduqual3,
                        'email'                        => $_POST["email"],
                        'lastname'                     => $_POST["lastname"],
                        'middlename'                   => $_POST["middlename"],
                        'mobile'                       => $_POST["mobile"],
                        'exam_name'                    => $_POST["exam_name"],
                        'subject_code'                 => $_POST["subject_code"],
                        'subject_name'                 => $_POST["subject"],
                        'selCenterName'                => $_POST["selCenterName"],
                        'selCenterCode'                => $_POST["selCenterCode"],
                        'scribe_name'                  => $_POST["scribe_name"],
                        'mobile_scribe'                => $_POST["mobile_scribe"],
                        'optedu'                       => $_POST["optedu"],
                        'emp_details_scribe'           => $emp_details_scribe,
                        'optnletter'                   => $_POST["optnletter"],
                        'idproofphoto'                 => $outputidproof1,
                        'declarationform'              => $declaration_form,
                        'photoid_no'                   => $photoid_no,
                        'idname'                       => $idproof_file,
                        'declaration'                  => $declaration_file,
                        'benchmark_disability'         => $benchmark_disability,
                        'scanned_vis_imp_cert'         => $output_vis_imp_cert1,
                        'vis_imp_cert_name'            => $scanned_vis_imp_cert_file,
                        'scanned_orth_han_cert'        => $output_orth_han_cert1,
                        'orth_han_cert_name'           => $scanned_orth_han_cert_file,
                        'scanned_cer_palsy_cert'       => $output_cer_palsy_cert1,
                        'cer_palsy_cert_name'          => $scanned_cer_palsy_cert_file,
                        'visually_impaired'            => $_POST["visually_impaired"],
                        'orthopedically_handicapped'   => $_POST["orthopedically_handicapped"],
                        'cerebral_palsy'               => $_POST["cerebral_palsy"], //
                    );
                    //echo "<pre>";print_r($user_data); die;
                    $this->session->set_userdata('enduserinfo', $user_data);

                    //echo "string";
                    $this->form_validation->set_message('error', "");
                    redirect(base_url() . 'Scribe_form/preview');
                }
            }
            //echo $_POST["firstname"]; echo validation_errors();die;
        }

        /*REGISTER FORM SUBMIT VALIDATIONS*/

        // Member deatils
        $this->db->Where('regnumber', $member_no);
        $this->db->Where('exm_cd', $exam_code);
        $this->db->Where('sub_cd', $subject_code);
        $this->db->order_by('exam_date', 'DESC');
        $this->db->join('admit_card_details b', 'a.regnumber = b.mem_mem_no', 'LEFT');
        $member_details = $this->master_model->getRecords('member_registration a', array('isactive' => '1', 'remark' => '1', 'scribe_flag' => 'Y'), 'namesub,firstname,middlename,lastname,email,mobile,center_name,center_code,regnumber');
        //print_r($subject_code);
        //echo $this->db->last_query();die;
        //Center Details
        $this->db->Where('exam_code', $exam_code);
        $exam_name = $this->master_model->getRecords('exam_master', array('exam_delete' => '0'), 'description,exam_code');
        //echo $this->db->last_query();die;
        //SUBJECT DETAILS
        $this->db->select('subject_code,subject_description,exam_date');
        $this->db->Where('subject_code', $subject_code);
        $subject_name = $this->master_model->getRecords('subject_master', array('subject_delete' => '0'), 'subject_description,subject_code');
        //print_r($subject_name);die;
        //Qualification Details
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate      = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate  = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        //Captacha
        $this->load->model('Captcha_model');
        $captcha_img = $this->Captcha_model->generate_captcha_img('LOGIN_SCRIBE_FORM');

        $data = array('member_details' => $member_details, 'exam_name' => $exam_name, 'subject_name' => $subject_name, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'captcha_img' => $captcha_img);
        //print_r($data);die;
        $this->load->view('scribe_form/register_scribe', $data);
    }
    // Custom validation function to check if age is more than 12 years
    public function validate_age($dob)
    {
        $from = new DateTime($dob);
        $to   = new DateTime('today');
        $age = $from->diff($to)->y;

        if ($age < 12) {
            $this->form_validation->set_message('validate_age', 'The age should be greater than 16 years.');
            // $this->form_validation->set_message('idproofphoto_upload', "No declaration file selected");
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
