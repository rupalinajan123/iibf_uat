<?php

/********************************************************************
 * Description: Controller for Scribe module
 * Created BY: Rupali Najan
 * Created On: 2025-08-08
 *********************************************************************/
defined('BASEPATH') or exit('No direct script access allowed');

class Scribe_form extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Emailsending');
        $this->load->helper('ncvet/ncvet_helper');
        $this->load->model('master_model');
        $this->load->model('ncvet/Ncvet_model');
        $this->load->helper('ncvet/ncvet_helper');
        $this->load->helper('file');
        $this->aadhar_file_path = 'uploads/ncvet/scribe/aadhar_file';
        $this->login_candidate_id = $this->session->userdata('NCVET_CANDIDATE_LOGIN_ID');
        $this->ncvet_login_reg_no = $this->session->userdata('NCVET_CANDIDATE_REG_NO');
    }

    public function index()
    {
        $this->load->view('ncvet/scribe_form/apply_scribe');
    }

    public function details_page()
    {
        // echo $this->ncvet_login_reg_no;
        // exit;
        if ($this->ncvet_login_reg_no && $this->ncvet_login_reg_no != '') {
            $regnumber = $this->ncvet_login_reg_no;
        } else {
            $regnumber = $this->session->userdata('candidate_regnumber');
        }

        // INITIAL SETUP
        $today     = date('Y-m-d');

        $candidate_details = $this->db->get_where('ncvet_candidates', [
            'regnumber' => $regnumber
        ])->row();

        $candidate = $this->db
            ->where('mem_mem_no', $regnumber)
            ->where('exam_date >=', $today)
            ->get('ncvet_admit_card_details')
            ->row();
        // echo $this->db->last_query();
        // print_r($exam_payment);
        // exit;
        // BASIC VALIDATIONS

        if (!$regnumber) {
            $this->session->set_flashdata('error', 'Please login/apply first.');
            redirect('ncvet/scribe_form/index');
            return;
        }
        // echo $this->ncvet_login_reg_no;
        // die;
        $exam_payment = $this->db->get_where('ncvet_member_exam', [
            'regnumber'   => $regnumber,
            'pay_status'  => 1,
            // 'scribe_flag' => "Y"
        ])->row();
        // echo $this->db->last_query();
        // print_r($exam_payment);
        // exit;
        if (!$exam_payment) {
            $this->session->set_flashdata('error', 'You have not completed the payment for the exam.');
            redirect('ncvet/scribe_form/index');
            return;
        }

        if (!$candidate) {
            $this->session->set_flashdata('error', 'You have not yet successfully applied, or the exam date has passed.');
            redirect('ncvet/scribe_form/index');
            return;
        }

        if (strtoupper($candidate_details->benchmark_disability) !== 'Y') {
            $this->session->set_flashdata('error', 'You are not eligible to apply for a scribe.');
            redirect('ncvet/scribe_form/index');
            return;
        }

        $this->db->Where('regnumber', $regnumber);
        $this->db->Where('exam_code', $exam_code);
        $this->db->Where('subject_code', $subject_code);
        $this->db->Where('exam_date >', $today);
        //echo $exam_code;//die;
        $scribe_member_chk = $this->master_model->getRecords('ncvet_scribe_registration', array('remark' => '1'));
        // echo $this->db->last_query();
        // die;
        if (!empty($scribe_member_chk)) {
            $this->session->set_flashdata('error', "You have already taken the Scribe against the Selected Exam..");
            redirect(site_url('Scribe_form/special'));
        }

        // INITIAL PAGE DATA
        $data['aadhar_file_error'] = '';
        $data['aadhar_file_path']  = $aadhar_file_path = $this->aadhar_file_path;

        // FORM SUBMISSION LOGIC
        if ($this->input->post('submit')) {

            $error_flag      = 0;
            $file_name_str   = date("YmdHis") . '_' . rand(1000, 9999);
            $aadhar_file     = '';
            $new_aadhar_file = '';
            $upload_data1    = null;
            $aadhar_file_req_flg = 'required|';

            if (!empty($_POST['aadhar_file_cropper'])) {
                $aadhar_file_req_flg = '';
            }

            // FORM VALIDATION RULES
            $this->form_validation->set_rules('scribe_name', 'Scribe Name', 'trim|max_length[100]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('mobile_scribe', 'Scribe Mobile', 'trim|required|numeric|min_length[10]|xss_clean');
            $this->form_validation->set_rules('scribe_dob', 'Date of Birth', 'required|callback_validate_age');
            $this->form_validation->set_rules('scribe_email', 'Scribe Email', 'trim|required|valid_email|xss_clean');
            $this->form_validation->set_rules('aadhar_no', 'Aadhaar Number', 'required|numeric|exact_length[12]');
            $this->form_validation->set_rules('qualification', 'Qualification', 'required');
            $this->form_validation->set_rules(
                'aadhar_file',
                'Aadhar File',
                'callback_fun_validate_file_upload[aadhar_file|' . $aadhar_file_req_flg . '|jpg,jpeg,png|40|5120|Aadhar File|75]'
            );

            if (empty($_FILES['declaration_img']['name'])) {
                $this->form_validation->set_rules('declaration_img', 'Declaration Form', 'required');
            }

            // VALIDATION FAILED – RETURN WITH ERRORS

            if ($this->form_validation->run() == false) {

                $data['validation_errors'] = validation_errors();
                $data['candidate']         = $candidate_details;
                $data['admit_details']     = $candidate;
                $data['aadhar_file_error'] = '';
                $data['aadhar_file_path']  = $this->aadhar_file_path;
                $data['post_data']         = $this->input->post();

                $this->load->view('ncvet/scribe_form/details_page', $data);
                return;
            }

            // AADHAAR FILE HANDLING

            if (!empty($_FILES['aadhar_file']['name'])) {

                $new_file_name1 = "aadhar_" . $file_name_str;

                $upload_data1 = $this->Ncvet_model->upload_file(
                    "aadhar_file",
                    ['jpg', 'jpeg', 'png'],
                    $new_file_name1,
                    "./" . $aadhar_file_path,
                    "jpg|jpeg|png",
                    '',
                    '',
                    '',
                    '',
                    '5120',
                    '40',
                    '',
                    $new_file_name1
                );

                if ($upload_data1['response'] == 'error') {
                    $data['aadhar_file_error'] = $upload_data1['message'];
                    $error_flag = 1;
                } elseif ($upload_data1['response'] == 'success') {
                    $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = $upload_data1['message'];
                }
            } elseif (!empty($_POST['old_candidate_id']) && !empty($_POST['aadhar_file_old'])) {

                $aadhar_file_old = $this->security->xss_clean($this->input->post('aadhar_file_old'));

                if (!empty($_POST['parent_table']) && $_POST['parent_table'] == "ncvet_scribe_registration") {

                    $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($aadhar_file_old);
                } else {

                    $new_file_name1 = "aadhar_" . $file_name_str . '.' . strtolower(pathinfo($aadhar_file_old, PATHINFO_EXTENSION));

                    if (copy(str_replace(base_url(), '', $aadhar_file_old), $aadhar_file_path . '/' . $new_file_name1)) {
                        $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($new_file_name1);
                    } else {
                        $data['aadhar_file_error'] = 'Please upload valid Proof of Identity';
                        $error_flag = 1;
                    }
                }
            } elseif (!empty($_POST['aadhar_file_cropper'])) {

                $aadhar_file_cropper = $this->security->xss_clean($this->input->post('aadhar_file_cropper'));
                $new_file_name1      = "aadhar_" . $file_name_str . '.' . strtolower(pathinfo($aadhar_file_cropper, PATHINFO_EXTENSION));

                if (copy(str_replace(base_url(), '', $aadhar_file_cropper), $aadhar_file_path . '/' . $new_file_name1)) {
                    $add_data['aadhar_file'] = $aadhar_file = $new_aadhar_file = basename($new_file_name1);
                } else {
                    $data['aadhar_file_error'] = 'Please upload valid Proof of Identity';
                    $error_flag = 1;
                }
            }

            // CUSTOM UPLOADS FOR DISABILITY & DECLARATION DOCUMENTS
            $upload_path  = './uploads/ncvet/scribe/';
            $files        = [];
            $var_errors   = [];

            function do_upload(
                $field,
                $subfolder,
                $allowed_types,
                $min_size_kb,
                $max_size_kb,
                $resize_width,
                $resize_height,
                &$files,
                &$errors,
                $CI
            ) {
                if (!empty($_FILES[$field]['name'])) {

                    $file_name_str = date("YmdHis") . '_' . rand(1000, 9999);
                    $ext           = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                    $new_filename  = $field . '_' . $file_name_str . '.' . $ext;

                    if (!is_dir($subfolder)) {
                        mkdir($subfolder, 0777, true);
                    }

                    $allowed_exts = explode('|', $allowed_types);
                    if (!in_array(strtolower($ext), $allowed_exts)) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ': Invalid file type.';
                        return;
                    }

                    $file_size_kb = $_FILES[$field]['size'] / 1024;
                    if ($file_size_kb < $min_size_kb || $file_size_kb > $max_size_kb) {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ": File size must be between {$min_size_kb}KB and {$max_size_kb}KB.";
                        return;
                    }

                    $upload_config = [
                        'upload_path'   => $subfolder,
                        'allowed_types' => $allowed_types,
                        'file_name'     => $new_filename,
                        'overwrite'     => true,
                        'max_size'      => $max_size_kb,
                    ];

                    $CI->upload->initialize($upload_config);

                    if ($CI->upload->do_upload($field)) {
                        $dt = $CI->upload->data();
                        $files[$field] = $dt['file_name'];
                    } else {
                        $errors[] = ucfirst(str_replace('_', ' ', $field)) . ': ' . $CI->upload->display_errors('', '');
                    }
                }
            }

            // File uploads
            do_upload('declaration_img',      $upload_path . 'declaration/', 'pdf',               50, 2048, 1000, 1000, $files, $var_errors, $this);
            do_upload('vis_imp_cert_img',     $upload_path . 'disability/', 'jpg|jpeg|png|pdf',   50, 5120, 1000, 1000, $files, $var_errors, $this);
            do_upload('orth_han_cert_img',    $upload_path . 'disability/', 'jpg|jpeg|png|pdf',   50, 5120, 1000, 1000, $files, $var_errors, $this);
            do_upload('cer_palsy_cert_img',   $upload_path . 'disability/', 'jpg|jpeg|png|pdf',   50, 5120, 1000, 1000, $files, $var_errors, $this);

            // UPLOAD ERRORS → RETURN TO VIEW
            if (!empty($var_errors)) {

                $this->session->set_flashdata('error', implode(", ", $var_errors));

                $data['validation_errors'] = implode(", ", $var_errors);
                $data['candidate']         = $candidate_details;
                $data['admit_details']     = $candidate;
                $data['aadhar_file_error'] = '';
                $data['aadhar_file_path']  = $this->aadhar_file_path;
                $data['post_data']         = $this->input->post();

                $this->load->view('ncvet/scribe_form/details_page', $data);
                return;
            }

            // STORE IN SESSION AND REDIRECT TO PREVIEW
            $user_data = [
                'exam_code'                    => '123456',
                'exam_name'                    => 'Certificate Course on Fundamentals of Retail Banking',
                'exam_date'                    => $candidate->exam_date,
                'regnumber'                    => $regnumber,
                'namesub'                      => $candidate_details->salutation,
                'firstname'                    => $candidate_details->first_name,
                'middlename'                   => $candidate_details->middle_name,
                'lastname'                     => $candidate_details->last_name,
                'email'                        => $candidate_details->email_id,
                'mobile'                       => $candidate_details->mobile_no,
                'subject_name'                 => $candidate->sub_dsc,
                'center_name'                  => $candidate->center_name,
                'center_code'                  => $candidate->center_code,
                'benchmark_disability'         => $candidate_details->benchmark_disability,
                'qualification'                => $this->input->post('qualification'),
                'name_of_scribe'               => $this->input->post('scribe_name'),
                'mobile_scribe'                => $this->input->post('mobile_scribe'),
                'scribe_dob'                   => $this->input->post('scribe_dob'),
                'scribe_email'                 => $this->input->post('scribe_email'),
                'declaration_img'              => isset($files['declaration_img']) ? $files['declaration_img'] : '',
                'aadhar_no'                    => $this->input->post('aadhar_no'),
                'aadhar_file'                  => $aadhar_file,
                'visually_impaired'            => $candidate_details->visually_impaired,
                'orthopedically_handicapped'   => $candidate_details->orthopedically_handicapped,
                'cerebral_palsy'               => $candidate_details->cerebral_palsy,
                'vis_imp_cert_img'             => $candidate_details->vis_imp_cert_img,
                'orth_han_cert_img'            => $candidate_details->orth_han_cert_img,
                'cer_palsy_cert_img'           => $candidate_details->cer_palsy_cert_img,
                'created_on'                   => date('Y-m-d H:i:s'),
                'remark'                     => '1',

            ];

            $user_ses = $this->session->set_userdata('scribe_preview', $user_data);

            redirect('ncvet/scribe_form/preview');
            return;
        }

        $data = [
            'candidate'     => $candidate_details,
            'admit_details' => $candidate,
            'user_ses'      => isset($user_ses) ? $user_ses : null
        ];

        $this->load->view('ncvet/scribe_form/details_page', $data);
    }


    function fun_validate_file_upload($str, $parameter)
    {
        $result = $this->Ncvet_model->fun_validate_file_upload($parameter);
        if ($result['flag'] == 'success') {
            return true;
        } else {
            $this->form_validation->set_message('fun_validate_file_upload', $result['response']);
            return false;
        }
    }

    public function check_captcha_code_ajax()
    {
        $session_name = $this->input->post('session_name') ?: 'LOGIN_SCRIBE';
        $captcha_code = $this->security->xss_clean(trim($this->input->post('captcha_code')));
        $session_captcha = isset($_SESSION[$session_name]) ? $_SESSION[$session_name] : '';

        echo ($captcha_code && $captcha_code == $session_captcha) ? 'true' : 'false';
    }

    public function generate_captcha_ajax()
    {
        $session_name = $this->input->post('session_name') ?: 'LOGIN_SCRIBE';
        $this->load->model('Captcha_model');
        echo $this->Captcha_model->generate_captcha_img($session_name);
    }

    public function send_otp()
    {
        $enrollNo = $this->input->post('regnumber');
        $user = $this->db->get_where('ncvet_candidates', ['regnumber' => $enrollNo])->row();

        if (!$user) {
            echo json_encode(['status' => 'error', 'message' => 'Enrollment number not found.']);
            return;
        }

        $this->session->set_userdata('candidate_regnumber', $enrollNo);

        // Generate OTP
        $otp = rand(100000, 999999);
        $this->session->set_userdata('ncvet_otp', $otp);

        $otp_sent_on = date('Y-m-d H:i:s');
        $otp_expired_on = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($otp_sent_on)));

        // Mask mobile 
        $masked_mobile = str_repeat('*', strlen($user->mobile_no) - 2) . substr($user->mobile_no, -2);

        // Mask email 
        $email_parts = explode("@", $user->email_id);
        $name_part = substr($email_parts[0], 0, 2) . str_repeat('*', max(strlen($email_parts[0]) - 2, 0));
        $domain_parts = explode(".", $email_parts[1]);
        $domain_name = substr($domain_parts[0], 0, 1) . str_repeat('*', max(strlen($domain_parts[0]) - 1, 0));
        $domain_ext = isset($domain_parts[1]) ? "." . $domain_parts[1] : "";
        $masked_email = $name_part . "@" . $domain_name . $domain_ext;

        // Send OTP to both
        $this->send_otp_sms_email($user->mobile_no, $user->email_id, $otp, $otp_sent_on, $otp_expired_on, $enrollNo);

        echo json_encode([
            'status' => 'success',
            'message' => "OTP sent successfully.",
            'masked_mobile' => $masked_mobile,
            'masked_email' => $masked_email
        ]);
    }

    private function send_otp_sms_email($mobile, $email, $otp, $otp_sent_on, $otp_expired_on, $enrollNo)
    {
        $emailerstr = $this->master_model->getRecords('emailer', ['emailer_name' => 'email_mobile_verification']);

        // Email
        $email_text = str_replace('#OTP#', $otp, $emailerstr[0]['emailer_text']);
        $otp_mail_arr = [
            'to'      => $email,
            'subject' => $emailerstr[0]['subject'],
            'message' => $email_text
        ];
        $email_status = $this->Emailsending->mailsend($otp_mail_arr);

        // SMS
        $sms_text = str_replace('#OTP#', $otp, $emailerstr[0]['sms_text']);
        $sms_status = $this->master_model->send_sms_common_all(
            $mobile,
            $sms_text,
            $emailerstr[0]['sms_template_id'],
            $emailerstr[0]['sms_sender']
        );

        if ($email_status || $sms_status) {
            $add_data = [
                'email_id'       => $email,
                'mobile_no'      => $mobile,
                'regnumber'      => $enrollNo,
                'otp_type'       => '5',
                'otp'            => $otp,
                'is_validate'    => '0',
                'otp_expired_on' => $otp_expired_on,
                'created_on'     => $otp_sent_on
            ];

            $this->db->insert('ncvet_candidate_login_otp', $add_data);
            return true;
        }

        return false;
    }



    public function verify_otp()
    {
        $entered_otp = $this->input->post('otp');
        $current_time = date('Y-m-d H:i:s');

        // Get the latest OTP record
        $this->db->order_by('created_on', 'DESC');
        $otp_record = $this->db->get_where('ncvet_candidate_login_otp', [
            'otp'         => $entered_otp,
            'otp_type'    => '5',
            'is_validate' => '0'
        ])->row_array();

        if (!empty($otp_record)) {
            // Check if OTP is expired
            if ($current_time <= $otp_record['otp_expired_on']) {
                $this->db->where('otp', $entered_otp);
                $this->db->update('ncvet_candidate_login_otp', ['is_validate' => '1']);

                $response = [
                    'status'  => 'success',
                    'message' => 'OTP verified successfully.'
                ];
            } else {
                $response = [
                    'status'  => 'error',
                    'message' => 'OTP has expired. Please request a new one.'
                ];
            }
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Invalid OTP entered.'
            ];
        }

        echo json_encode($response);
    }


    public function validate_age($dob)
    {
        $from = new DateTime($dob);
        $to   = new DateTime('today');
        $age = $from->diff($to)->y;

        if ($age < 18 || $age > 24) {
            $this->form_validation->set_message('validate_age', 'Age must be between 18 to 24 years.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function preview()
    {
        $regnumber = $this->session->userdata('candidate_regnumber');
        $today = date('Y-m-d');
        $candidate_details = $this->db->get_where('ncvet_candidates', ['regnumber' => $regnumber])->row();
        $candidate = $this->db->where('mem_mem_no', $regnumber)
            ->where('exam_date >=', $today)
            ->get('ncvet_admit_card_details')
            ->row();

        $this->session->set_userdata([
            'candidate_details' => $candidate_details,
            'candidate' => $candidate
        ]);
        $data['candidate_details'] = $this->session->userdata('candidate_details');
        $data['candidate'] = $this->session->userdata('candidate');
        $data['scribe'] = $this->session->userdata('scribe_preview');

        if (!$data['scribe']) {
            $this->session->set_flashdata('error', 'No data found for preview. Please fill the form again.');
            redirect('ncvet/scribe_form/details_page');
            return;
        }

        if ($this->input->post("confirm")) {

            $insert = $this->db->insert('ncvet_scribe_registration', $data['scribe']);
            if ($insert) {

                $insert_id = $this->db->insert_id();
                $scribe_uid = 'SB' . str_pad($insert_id, 4, '0', STR_PAD_LEFT);

                // Update row with scribe_uid
                $this->db->where('id', $insert_id)
                    ->update('ncvet_scribe_registration', ['scribe_uid' => $scribe_uid]);

                $this->session->unset_userdata('scribe_preview');
                $this->session->set_flashdata('success', 'Scribe details submitted successfully.');
            }

            redirect('ncvet/scribe_form/acknowledge');
            return;
        }
        $this->load->view('ncvet/scribe_form/preview', $data);
    }

    public function getSubjects()
    {
        // POST data
        $subject   = array();
        $exam_code = $this->input->post('exam_code');

        // get data
        if ($exam_code) {
            $this->db->select('s.subject_code,s.exam_code,s.subject_description');
            $data['subjects'] = $subjects = $this->master_model->getRecords('ncvet_subject_master s', array('exam_code' => $exam_code));
        }
        echo json_encode($subjects);
    }

    public function acknowledge()
    {
        // $regnumber = $this->session->userdata('candidate_regnumber');
        if ($this->ncvet_login_reg_no && $this->ncvet_login_reg_no != '') {
            $regnumber = $this->ncvet_login_reg_no;
        } else {
            $regnumber = $this->session->userdata('candidate_regnumber');
        }

        if (!$regnumber) {
            $this->session->set_flashdata('error', 'Please login/apply first.');
            redirect('ncvet/scribe_form/index');
            return;
        }

        $user_info = $this->db
            ->where('regnumber', $regnumber)
            ->order_by('id', 'DESC')
            ->limit(1)
            ->get('ncvet_scribe_registration')
            ->result_array();


        if (empty($user_info)) {
            $this->session->set_flashdata('error', 'No scribe registration found for your enrollment number.');
            redirect('ncvet/scribe_form/index');
            return;
        }

        $data['user_info'] = $user_info;
        $this->session->unset_userdata('candidate_regnumber');
        $this->session->unset_userdata('scribe_preview');
        $this->session->sess_destroy();


        $this->load->view('ncvet/scribe_form/acknowledge', $data);
    }
}
