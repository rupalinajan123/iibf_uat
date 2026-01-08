<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BulkApplyNM extends CI_Controller
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

        $this->load->helper('cookie');

        $this->load->model('log_model');

        $this->chk_session->chk_bank_login_session();

        if ($this->router->fetch_method() != 'comApplication' && $this->router->fetch_method() != 'preview' && $this->router->fetch_method() != 'Msuccess' && $this->router->fetch_method() != 'editmobile' && $this->router->fetch_method() != 'editemailduplication' && $this->router->fetch_method() != 'setExamSession' && $this->router->fetch_method() != 'saveexam' && $this->router->fetch_method() != 'savedetails' && $this->router->fetch_method() != 'exampdf' && $this->router->fetch_method() != 'printexamdetails' && $this->router->fetch_method() != 'details' && $this->router->fetch_method() != 'sbi_make_payment' && $this->router->fetch_method() != 'sbitranssuccess' && $this->router->fetch_method() != 'sbitransfail' && $this->router->fetch_method() != 'accessdenied' && $this->router->fetch_method() != 'getFee' && $this->router->fetch_method() != 'check_emailduplication' && $this->router->fetch_method() != 'check_mobileduplication' && $this->router->fetch_method() != 'check_checkpin' && $this->router->fetch_method() != 'refund' && $this->router->fetch_method() != 'checkpin' && $this->router->fetch_method() != 'exam_applicantlst' && $this->router->fetch_method() != 'add_member') {

            if ($this->session->userdata('examinfo')) {

                $this->session->unset_userdata('examinfo');

            }

            /* if($this->session->userdata('examcode'))

        {

        $this->session->unset_userdata('examcode');

        }*/

        }
     		
        $elearning_course_code =  [528,529,530];
        $_SESSION['is_elearning_course'] = 'n';
        $exam_code_construct = $this->session->userdata('examcode');
        if (isset($exam_code_construct) && $exam_code_construct!='' && in_array($exam_code_construct, $elearning_course_code) ) {
        	$_SESSION['is_elearning_course'] = 'y';
        }

        //exit;

    }


    public function GST()
    {

        $message = '<div style="color:#F00">Please pay GST amount of Exam/Mem registration in order to apply for the exam.<a href="' . base_url() . 'GstRecovery/" target="new">click here</a></div>';

        $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

        $this->load->view('bulk/bulk_common_view', $data);

    }

    //##-------Non Member Registration in Bank Dashboard---## //Tejasvi

    public function add_member()
    {

        $ex_prd = '';
        $dra_images = array();
        $scannedphoto = $scannedsignaturephoto = $idproofphoto = '';
        if (isset($this->session->userdata['exmCrdPrd']['exam_prd'])) {
            $ex_prd = $this->session->userdata['exmCrdPrd']['exam_prd'];
        }
        $exam_code = $this->session->userdata('examcode');
        if (empty($exam_code)) {
            redirect(base_url() . 'bulk/BulkApply/examlist');
        }

        $is_exam_valid_nm = $this->master_model->getRecords('exam_master', array('exam_code' => $exam_code, 'elg_mem_nm' => 'Y'));
        //echo $this->db->last_query();exit;
        if (empty($is_exam_valid_nm)) {
            $this->session->set_flashdata('error', 'Non member can not apply for this exam!');
            redirect(base_url() . 'bulk/BulkApply/exam_applicantlst/');
        }

        $flag = 1;
        //echo $exam_code;exit;
        if (isset($_POST['getdata'])) {
            //echo '<pre>',print_r($_POST),'</pre>';exit;
            $this->form_validation->set_rules('regnumber', 'Membership No.', 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {
                $mem_info = array();
                if (isset($_POST['regnumber'])) {
                    /* DRA-BULK Bhushan Start */
                    if ($exam_code == 1017 || $exam_code == 1018) {
                        $this->db->where('regnumber', $_POST['regnumber']);
                        $sql = $this->master_model->getRecords('dra_members');
                        $this->db->where('member_no', $_POST['regnumber']);
                        $this->db->where('exam_code', $this->session->userdata('examcode'));
                        $this->db->where('eligible_period', $this->session->userdata['exmCrdPrd']['exam_prd']);
                        $chk_eligible_member = $this->master_model->getRecords('eligible_master', '', 'discount_flag,institute_id,fee_paid_flag,reapeter_flag');
                        //echo "<br>".$this->db->last_query();
                        if (count($chk_eligible_member) > 0) {
                            if ($this->session->userdata['institute_id'] != $chk_eligible_member[0]['institute_id']) {
                                $this->session->set_flashdata('error', 'Entered Membership number not belong to this institute!!');
                                redirect(base_url('bulk/BulkApplyNM/add_member/'));
                            }

                        /*if($chk_eligible_member[0]['fee_paid_flag'] == 'F'){
                        $this->session->set_flashdata('error','Entered Membership number is wrong!!');
                        redirect(base_url('bulk/BulkApplyNM/add_member/'));
                        }*/
                        } else {
                            $this->session->set_flashdata('error', 'Wrong member number!!');
                            redirect(base_url('bulk/BulkApplyNM/add_member/'));

                        }
                        // accedd denied due to GST

                        $GST_val = check_GST($_POST['regnumber']);
                        if ($GST_val == 2) {
                            redirect(base_url() . 'bulk/BulkApplyNM/GST');
                        }

                        $mem_no = $_POST['regnumber'];
                        $mem_info = $this->master_model->getRecords('member_registration', array('regnumber' => $mem_no, 'isactive' => '1'));
                        if (empty($mem_info)) {
                            $mem_info = $this->master_model->getRecords('dra_members', array('regnumber' => $mem_no, 'isactive' => '1'));
                        }
                        //echo ">>".$this->db->last_query();
                        if (empty($mem_info)) {
                            $this->session->set_flashdata('error', 'Entered Membership number not present...Please do the registration!!');
                            redirect(base_url('bulk/BulkApplyNM/add_member/'));

                        } elseif ($mem_info[0]['registrationtype'] != 'NM') {
                            $this->session->set_flashdata('error', 'Registration number entered is invalid, Kindly enter correct Registration number.!!');
                            redirect(base_url('bulk/BulkApplyNM/add_member/'));

                        }

                        //validating for already registered Non_member

                        if (!empty($mem_info)) {
                          $user_data = array('mregid_applyexam' => $mem_info[0]['regid'],
                              'mregnumber_applyexam'                => $mem_info[0]['regnumber'],
                              'memtype'                             => $mem_info[0]['registrationtype'],
                              'free_paid_flag'                      => $chk_eligible_member[0]['fee_paid_flag'],

                              'reapeter_flag'                       => $chk_eligible_member[0]['reapeter_flag'],
                          );

                            $this->session->set_userdata($user_data);
                            $profile_flag = 1;
                            $message = '';
                            $exam_status = 1;
                            $applied_exam_info = array();
                            $flag             = 1;
                            $checkqualifyflag = 0;
                            $examcode = $this->session->userdata('examcode');
                            // DRA Images
                            $old_image_path = 'uploads' . $mem_info[0]['image_path'];

                            $new_image_path = 'uploads/iibfdra/';

                            if ($mem_info[0]['scannedphoto'] == '') {

                                if (file_exists($old_image_path . "photo/p_" . $mem_info[0]['registration_no'] . '.jpg')) {

                                    $scannedphoto = base_url() . $old_image_path . "photo/p_" . $mem_info[0]['registration_no'] . '.jpg';

                                }

                            } else {

                                if (file_exists($new_image_path . $mem_info[0]['scannedphoto'])) {

                                    $scannedphoto = base_url() . $new_image_path . $mem_info[0]['scannedphoto'];

                                }

                            }

                            if ($mem_info[0]['scannedsignaturephoto'] == '') {

                                if (file_exists($old_image_path . "signature/s_" . $mem_info[0]['registration_no'] . '.jpg')) {

                                    $scannedsignaturephoto = base_url() . $old_image_path . "signature/s_" . $mem_info[0]['registration_no'] . '.jpg';

                                }

                            } else {

                                if (file_exists($new_image_path . $mem_info[0]['scannedsignaturephoto'])) {

                                    $scannedsignaturephoto = base_url() . $new_image_path . $mem_info[0]['scannedsignaturephoto'];

                                }

                            }

                            if ($mem_info[0]['idproofphoto'] == '') {

                                if (file_exists($old_image_path . "idproof/pr_" . $mem_info[0]['registration_no'] . '.jpg')) {

                                    $idproofphoto = base_url() . $old_image_path . "idproof/pr_" . $mem_info[0]['registration_no'] . '.jpg';

                                }

                            } else {

                                if (file_exists($new_image_path . $mem_info[0]['idproofphoto'])) {

                                    $idproofphoto = base_url() . $new_image_path . $mem_info[0]['idproofphoto'];

                                }

                            }

                            $dra_images = array();

                            $dra_images = array('scannedphoto_v' => $scannedphoto,

                                'scannedsignaturephoto_v'            => $scannedsignaturephoto,

                                'idproofphoto_v'                     => $idproofphoto,

                            );


                            if ($scannedphoto == '' || $scannedsignaturephoto == '' || $idproofphoto == '') {

                                $profile_flag = 0;

                            }


                            if ($profile_flag == 1) {

                                $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));

                                if (count($check_qualify_exam) > 0) {

                                    if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part1']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    }

                                    //if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )

                                    if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part2']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    }

                                    //if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)

                                    if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part3']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    } else if ($flag == 1 && $checkqualifyflag == 0) {

                                        //echo 'in';exit;

                                        //check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)

                                        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

                                        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                        $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('mregnumber_applyexam')));

                                        if (count($check_eligibility_for_applied_exam) > 0) {

                                            foreach ($check_eligibility_for_applied_exam as $check_exam_status) {

                                                if ($check_exam_status['exam_status'] == 'F') {

                                                    $exam_status = 0;

                                                }

                                            }

                                            if ($exam_status == 1) {

                                                $flag = 0;

                                                $message = $check_eligibility_for_applied_exam[0]['remark'];

                                            } else if ($exam_status == 0) {

                                                // print_r($this->session->userdata('examcode'));exit;

                                                $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                //echo $this->db->last_query();

                                                //print_r($check);exit;

                                                //$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));

                                                if (!$check) {

                                                    $check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                    if (!$check_date) {

                                                        //CAIIB apply directly

                                                        $flag = 1;

                                                    } else {

                                                        $message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                        //$message='Exam fall in same date';

                                                        $flag = 0;

                                                    }

                                                } else {

                                                    $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                                    $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                                    $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata('examcode'), 'misc_master.misc_delete' => '0'), 'exam_month');

                                                    //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                                    $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                                    $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                                    $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                                    $flag = 0;

                                                }

                                            }

                                        } else {

                                            $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                            if (!$check) {

                                                $check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                if (!$check_date) {

                                                    //CAIIB apply directly

                                                    $flag = 1;

                                                } else {

                                                    $message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                    //$message='Exam fall in same date';

                                                    $flag = 0;

                                                }

                                            } else {

                                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata('examcode'), 'misc_master.misc_delete' => '0'), 'exam_month');

                                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                                //$message='You have already applied for the examination';

                                                $flag = 0;

                                            }

                                        }

                                    }

                                } else {

                                    $flag = 1;

                                }

                                $today_date = date('Y-m-d');

                                $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');

                                $this->db->where('exam_master.elg_mem_nm', 'Y');

                                //$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');

                                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                                $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

                                //$this->db->where('member_exam.pay_status','1');

                                $this->db->where('member_exam.bulk_isdelete', '0');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('mregnumber_applyexam')));

                            }

                        }

                        if ($profile_flag == 0) {

                            //  <a target="_blank" href='.base_url().'> Click here </a>to login

                            $message = '<div style="color:#F00" class="col-md-4">Member Profile incomplet.!!</div>';

                            $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

                            $this->load->view('bulk/bulk_common_view', $data);

                        } else if ($flag == 0) {

                            if ($profile_flag == 0) {

                                $message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a href=' . base_url() . '> Click here </a>to login</div>';

                            }

                            //echo 'message',print_r($message);

                            $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

                            $this->load->view('bulk/bulk_common_view', $data);

                        } else if (count($applied_exam_info) > 0) {

                            if ($check_qualify_exam[0]['exam_category'] == 1) {

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('nmregnumber')), 'examination_date');

                                $special_exam_dates = $this->master_model->getRecords('special_exam_dates', array('examination_date' => $applied_exam_info[0]['examination_date']), 'period');

                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0', 'exam_period' => $special_exam_dates[0]['period']), 'exam_month');

                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                $message = 'Application for this examination is already registered by you and is valid for<strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                $flag = 0;

                                $data = array('middle_content' => 'bulk/bulk-already-apply', 'check_eligibility' => $message);

                                $this->load->view('bulk/bulk_common_view', $data);

                            } else {

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0'), 'exam_month');

                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                $flag = 0;

                                $data = array('middle_content' => 'bulk/bulk-already-apply', 'check_eligibility' => $message);

                                $this->load->view('bulk/bulk_common_view', $data);

                            }

                        } else {

                            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=exam_master.exam_code');

                            $this->db->join("eligible_master", 'eligible_master.exam_code=bulk_exam_activation_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period', 'left');

                            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                            $this->db->where("misc_master.misc_delete", '0');

                            $this->db->where("eligible_master.member_no", $this->session->userdata('mregnumber_applyexam'));

                            if ($ex_prd != '') {

                                $this->db->where('eligible_master.eligible_period', $ex_prd);

                            }

                            $this->db->where("eligible_master.app_category !=", 'R');

                            $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));

                            $examinfo = $this->master_model->getRecords('exam_master');

                            //echo '<pre>',print_r($examinfo),'</pre>';exit;

                            ####### get subject mention in eligible master ##########

                            if (count($examinfo) > 0) {

                                //19_03_2018 for only accept fresh member or 'R' cat member

                                //if($examinfo[0]['app_category']!='R')

                                //{

                                //    $this->session->set_flashdata('error','You are not eligible...!');

                                //redirect(base_url('bulk/BulkApplyNM/add_member'));

                                //}

                                foreach ($examinfo as $rowdata) {

                                    if ($rowdata['exam_status'] != 'P') {

                                        $this->db->group_by('subject_code');

                                        $compulsory_subjects[] = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $rowdata['exam_period'], 'subject_code' => $rowdata['subject_code']));

                                    }

                                }

                                //$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));

                                $compulsory_subjects = array_map('current', $compulsory_subjects);

                                sort($compulsory_subjects);

                            }

                            //center for eligible member

                            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

                            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                            $this->db->where('center_master.exam_name', $this->session->userdata('examcode'));

                            $this->db->where("center_delete", '0');

                            if ($ex_prd != '') {

                                $this->db->where('center_master.exam_period', $ex_prd);

                            }

                            $center = $this->master_model->getRecords('center_master');

                            //echo '<pre>',print_r($examinfo),'</pre>';exit;

                        }

                        //exit;

                    } else {

                        // code to validate user

                        $this->db->where('member_no', $_POST['regnumber']);
                        $this->db->where('exam_code', $this->session->userdata('examcode'));
                        $this->db->where('eligible_period', $this->session->userdata['exmCrdPrd']['exam_prd']);
                        $chk_eligible_member = $this->master_model->getRecords('eligible_master', '', 'discount_flag,institute_id,fee_paid_flag,reapeter_flag,exam_status');
                     

                        if (count($chk_eligible_member) > 0) {
                            if ($this->session->userdata('is_elearning_course')=='n' && $this->session->userdata['institute_id'] != $chk_eligible_member[0]['institute_id']) {
                                $this->session->set_flashdata('error', 'Entered Membership number not belong to this institute!!');
                                redirect(base_url('bulk/BulkApplyNM/add_member/'));
                            }

                            if ($chk_eligible_member[0]['fee_paid_flag'] == 'F') {
                                $this->session->set_flashdata('error', 'Entered Membership number is wrong!!');
                                redirect(base_url('bulk/BulkApplyNM/add_member/'));

                            }

                           if ($chk_eligible_member[0]['exam_status'] == 'D') {
                                $this->session->set_flashdata('error', 'You Are Debarred For this Exam!!');
                                redirect(base_url('bulk/BulkApplyNM/add_member/'));

                            }

                        } else {
                        		if ($this->session->userdata('is_elearning_course')=='n' || $this->session->userdata('examcode')!="1026") {
                        				$this->session->set_flashdata('error', 'Wrong member number!!');
                            		redirect(base_url('bulk/BulkApplyNM/add_member/'));
                        		}
                            

                        }

                        // code to validate user end

                        // accedd denied due to GST

                        $GST_val = check_GST($_POST['regnumber']);
                        if ($GST_val == 2) {
                            redirect(base_url() . 'bulk/BulkApplyNM/GST');
                        }

                        $mem_no = $_POST['regnumber'];
                        $mem_info = $this->master_model->getRecords('member_registration', array('regnumber' => $mem_no, 'isactive' => '1'));
                        if (empty($mem_info)) {
                            $this->session->set_flashdata('error', 'Entered Membership number not present...Please do the registration!!');
                            redirect(base_url('bulk/BulkApplyNM/add_member/'));
                        } elseif ($mem_info[0]['registrationtype'] == 'O') {
                            $this->session->set_flashdata('error', 'Registration number entered is invalid, Kindly enter correct Registration number.!!');
                            redirect(base_url('bulk/BulkApplyNM/add_member/'));

                        }

                        //validating for already registered Non_member
                        if (!empty($mem_info)) {

                            $user_data = array('mregid_applyexam' => $mem_info[0]['regid'],
                                'mregnumber_applyexam'                => $mem_info[0]['regnumber'],
                                'memtype'                             => $mem_info[0]['registrationtype'],
                                'free_paid_flag'                      => $chk_eligible_member[0]['fee_paid_flag'],
                                'reapeter_flag'                       => $chk_eligible_member[0]['reapeter_flag']);

                            $this->session->set_userdata($user_data);
                            $profile_flag = 1;
                            $message = '';
                            $exam_status = 1;
                            $applied_exam_info = array();
                            $flag             = 1;
                            $checkqualifyflag = 0;
                            $examcode = $this->session->userdata('examcode');
                           
                            if (
                            	! is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 's')) 
                            	|| !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'p')) 
                            	|| !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'pr')) 
                            	|| validate_nonmemdata($this->session->userdata('mregnumber_applyexam'))
                            		) {

                                $profile_flag = 0;
                            		}

                            //echo $profile_flag;

                            //echo $this->db->last_query();exit;

                            if ($profile_flag == 1) {

                                $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));

                                if (count($check_qualify_exam) > 0) {

                                    if ($check_qualify_exam[0]['qualifying_exam1'] != '' && $check_qualify_exam[0]['qualifying_exam1'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam1'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part1']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    }

                                    //if($check_qualify_exam[0]['qualifying_exam2']!='' && $check_qualify_exam[0]['qualifying_exam2']!='0' && $checkqualifyflag==0 )

                                    if ($check_qualify_exam[0]['qualifying_exam2'] != '' && $check_qualify_exam[0]['qualifying_exam2'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam2'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part2']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    }

                                    //if($check_qualify_exam[0]['qualifying_exam3']!='' && $check_qualify_exam[0]['qualifying_exam3']!='0' && $checkqualifyflag==0)

                                    if ($check_qualify_exam[0]['qualifying_exam3'] != '' && $check_qualify_exam[0]['qualifying_exam3'] != '0') {

                                        $qaulifyarry = $this->checkqualify($check_qualify_exam[0]['qualifying_exam3'], $this->session->userdata('examcode'), $check_qualify_exam[0]['qualifying_part3']);

                                        $flag = $qaulifyarry['flag'];

                                        $message = $qaulifyarry['message'];

                                        if ($flag == 0) {

                                            $checkqualifyflag = 1;

                                        }

                                    } else if ($flag == 1 && $checkqualifyflag == 0) {

                                        //echo 'in';exit;

                                        //check eligibility for applied exam(These are the exam who don't have pre-qualifying exam)

                                        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

                                        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                        $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('mregnumber_applyexam')));

                                        if (count($check_eligibility_for_applied_exam) > 0) {

                                            foreach ($check_eligibility_for_applied_exam as $check_exam_status) {

                                                if ($check_exam_status['exam_status'] == 'F') {

                                                    $exam_status = 0;

                                                }

                                            }

                                            if ($exam_status == 1) {

                                                $flag = 0;

                                                $message = $check_eligibility_for_applied_exam[0]['remark'];

                                            } else if ($exam_status == 0) {

                                                // print_r($this->session->userdata('examcode'));exit;

                                                $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                //echo $this->db->last_query();

                                                //print_r($check);exit;

                                                //$check=$this->examapplied($this->session->userdata('mregnumber_applyexam'),$this->input->get('ExId'));

                                                if (!$check) {

                                                    $check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                    if (!$check_date) {

                                                        //CAIIB apply directly

                                                        $flag = 1;

                                                    } else {

                                                        $message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                        //$message='Exam fall in same date';

                                                        $flag = 0;

                                                    }

                                                } else {

                                                    $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                                    $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                                    $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata('examcode'), 'misc_master.misc_delete' => '0'), 'exam_month');

                                                    //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                                    $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                                    $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                                    $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                                    $flag = 0;

                                                }

                                            }

                                        } else {

                                            $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                            if (!$check) {

                                                $check_date = $this->examdate($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                if (!$check_date) {

                                                    //CAIIB apply directly

                                                    $flag = 1;

                                                } else {

                                                    $message = $this->get_alredy_applied_examname($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'));

                                                    //$message='Exam fall in same date';

                                                    $flag = 0;

                                                }

                                            } else {

                                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata('examcode'), 'misc_master.misc_delete' => '0'), 'exam_month');

                                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                                //$message='You have already applied for the examination';

                                                $flag = 0;

                                            }

                                        }

                                    }

                                } else {

                                    $flag = 1;

                                }

                                $today_date = date('Y-m-d');

                                $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description');

                                $this->db->where('exam_master.elg_mem_nm', 'Y');

                                //$this->db->join('payment_transaction','payment_transaction.exam_code=member_exam.exam_code');

                                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                                $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

                                //$this->db->where('member_exam.pay_status','1');

                                $this->db->where('member_exam.bulk_isdelete', '0');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('mregnumber_applyexam')));

                            }

                        }

                        if ($profile_flag == 0) {

                            $message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a target="_blank" href=' . base_url() . '> Click here </a>to login</div>';

                            $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

                            $this->load->view('bulk/bulk_common_view', $data);

                        } else if ($flag == 0) {

                            if ($profile_flag == 0) {

                                $message = '<div style="color:#F00" class="col-md-4">Please update your profile!!<a href=' . base_url() . '> Click here </a>to login</div>';

                            }

                            //echo 'message',print_r($message);

                            $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

                            $this->load->view('bulk/bulk_common_view', $data);

                        } else if (count($applied_exam_info) > 0) {

                            if ($check_qualify_exam[0]['exam_category'] == 1) {

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $examcode, 'regnumber' => $this->session->userdata('nmregnumber')), 'examination_date');

                                $special_exam_dates = $this->master_model->getRecords('special_exam_dates', array('examination_date' => $applied_exam_info[0]['examination_date']), 'period');

                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0', 'exam_period' => $special_exam_dates[0]['period']), 'exam_month');

                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                $message = 'Application for this examination is already registered by you and is valid for<strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                $flag = 0;

                                $data = array('middle_content' => 'bulk/bulk-already-apply', 'check_eligibility' => $message);

                                $this->load->view('bulk/bulk_common_view', $data);

                            } else {

                                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $examcode, 'misc_master.misc_delete' => '0'), 'exam_month');

                                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                                $flag = 0;

                                $data = array('middle_content' => 'bulk/bulk-already-apply', 'check_eligibility' => $message);

                                $this->load->view('bulk/bulk_common_view', $data);

                            }

                        } else {

                            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=exam_master.exam_code');

                            $this->db->join("eligible_master", 'eligible_master.exam_code=bulk_exam_activation_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period', 'left');

                            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                            $this->db->where("misc_master.misc_delete", '0');

                            $this->db->where("eligible_master.member_no", $this->session->userdata('mregnumber_applyexam'));

                            if ($ex_prd != '') {

                                $this->db->where('eligible_master.eligible_period', $ex_prd);

                            }

                            $this->db->where("eligible_master.app_category !=", 'R');

                            $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));

                            $examinfo = $this->master_model->getRecords('exam_master');

                            //echo '<pre>',print_r($examinfo),'</pre>';exit;

                            ####### get subject mention in eligible master ##########

                            if (count($examinfo) > 0) {

                                //19_03_2018 for only accept fresh member or 'R' cat member

                                //if($examinfo[0]['app_category']!='R')

                                //{

                                //    $this->session->set_flashdata('error','You are not eligible...!');

                                //redirect(base_url('bulk/BulkApplyNM/add_member'));

                                //}

                                foreach ($examinfo as $rowdata) {

                                    if ($rowdata['exam_status'] != 'P') {

                                        $this->db->group_by('subject_code');

                                        $compulsory_subjects[] = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $rowdata['exam_period'], 'subject_code' => $rowdata['subject_code']));

                                    }

                                }

                                //$compulsory_subjects = iterator_to_array(new RecursiveIteratorIterator(new RecursiveArrayIterator($compulsory_subjects)));

                                $compulsory_subjects = array_map('current', $compulsory_subjects);

                                sort($compulsory_subjects);

                            }

                            //center for eligible member

                            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

                            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                            $this->db->where('center_master.exam_name', $this->session->userdata('examcode'));

                            $this->db->where("center_delete", '0');

                            if ($ex_prd != '') {

                                $this->db->where('center_master.exam_period', $ex_prd);

                            }

                            $center = $this->master_model->getRecords('center_master');

                            //echo '<pre>',print_r($examinfo),'</pre>';exit;

                        }

                    }

                }

            }

        }

        //Below code, if member is new member

        if (empty($examinfo)) {

            $this->db->select('exam_master.*,misc_master.*');

            $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period'); //added on 5/6/2017

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->where("misc_master.misc_delete", '0');

            $this->db->where('exam_master.exam_code', $this->session->userdata('examcode'));

            $examinfo = $this->master_model->getRecords('exam_master');

            //get center

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->where("center_delete", '0');

            $this->db->where('exam_name', $this->session->userdata('examcode'));

            $this->db->group_by('center_master.center_name');

            if ($ex_prd != '') {

                $this->db->where('center_master.exam_period', $ex_prd);

            }

            $center = $this->master_model->getRecords('center_master');

            ####### get compulsory subject list##########

            $this->db->group_by('subject_code');

            $compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $examinfo[0]['exam_period']), '', array('subject_code' => 'ASC'));

        }

        $institute_id = $this->session->userdata('institute_id');

        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));

        $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));

        $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $states = $this->master_model->getRecords('state_master');

        $this->db->where("bulk_branch_master.institute_id", $institute_id);

        $bulk_branch_master = $this->master_model->getRecords('bulk_branch_master');

        $this->db->where("bulk_designation_master.institute_id", $institute_id);

        $bulk_designation_master = $this->master_model->getRecords('bulk_designation_master');

        $this->db->where("bulk_zone_master.institute_id", $institute_id);

        $bulk_zone_master = $this->master_model->getRecords('bulk_zone_master');

        $this->db->where("bulk_payment_scale_master.institute_id", $institute_id);

        $bulk_payment_scale_master = $this->master_model->getRecords('bulk_payment_scale_master');

        $this->db->not_like('name', 'Declaration Form');

        $this->db->not_like('name', 'college');

        $this->db->not_like('name', 'Aadhaar id');

        $this->db->not_like('name', 'Election Voters card');

        $idtype_master = $this->master_model->getRecords('idtype_master');

        /*$this->db->select('exam_master.*,misc_master.*');

        $this->db->join('misc_master','misc_master.exam_code=exam_master.exam_code');

        $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period');//added on 5/6/2017

        $this->db->where("misc_master.misc_delete",'0');

        $this->db->where('exam_master.exam_code',$exam_code);

        $examinfo = $this->master_model->getRecords('exam_master');*/

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

        $this->db->where('medium_master.exam_code', $exam_code);

        $this->db->where('medium_delete', '0');

        if ($ex_prd != '') {

            $this->db->where('medium_master.exam_period', $ex_prd);

        }

        $medium = $this->master_model->getRecords('medium_master');

        //subject information

        $caiib_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'E', 'exam_period' => $examinfo[0]['exam_period']));

        $special_exam_dates = array();

        $exam_category = $this->master_model->getRecords('exam_master', array('exam_code' => $this->session->userdata('examcode')));

        if ($exam_category[0]['exam_category'] == 1) {

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $special_exam_apply_date = $this->master_model->getRecords('member_exam', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'bulk_isdelete' => '0', 'examination_date !=' => '0000-00-00'), 'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'

            $specialdateapply = array();

            if (count($special_exam_apply_date) > 0) {

                foreach ($special_exam_apply_date as $row) {

                    $specialdateapply[] = $row['examination_date'];

                }

            }

            $today_date = date('Y-m-d');

            $this->db->where("'$today_date' BETWEEN from_date AND to_date");

            $special_exam_dates = $this->master_model->getRecords('special_exam_dates');

        }

        /*$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

        $this->db->where("center_delete",'0');

        $this->db->where('exam_name',$exam_code);

        $this->db->group_by('center_master.center_name');

        $center=$this->master_model->getRecords('center_master');*/

        $this->load->helper('captcha');

        $this->session->unset_userdata("nonmemregcaptcha_bulk");

        $this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));

        $vals = array(

            'img_path' => './uploads/applications/',

            'img_url'  => base_url() . 'uploads/applications/',

        );

        $cap = create_captcha($vals);

        $_SESSION["nonmemregcaptcha_bulk"] = $cap['word']; //nonmemlogincaptcha

        $n_flag = 1;

        if (!count($examinfo) > 0 || !count($medium) > 0 || !count($center) > 0) {

            $n_flag = 0;

        }

        if ($n_flag == 1) {

            /*$this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=subject_master.exam_code AND bulk_exam_activation_master.exam_period=subject_master.exam_period');

            $compulsory_subjects=$this->master_model->getRecords('subject_master',array('subject_master.exam_code'=>$exam_code,'subject_delete'=>'0','group_code'=>'C'));*/

            if ($flag == 1) {

                if (empty($mem_info)) {

                    $data = array('middle_content' => 'bulk/bulk_add_memberNM', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'image' => $cap['image'], 'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'idtype_master' => $idtype_master, 'caiib_subjects' => $caiib_subjects, 'compulsory_subjects' => $compulsory_subjects, 'special_exam_dates' => $special_exam_dates, 'bulk_branch_master' => $bulk_branch_master, 'bulk_designation_master' => $bulk_designation_master, 'bulk_zone_master' => $bulk_zone_master, 'bulk_payment_scale_master' => $bulk_payment_scale_master, 'dra_images' => $dra_images);

                    $this->load->view('bulk/bulk_common_view', $data);

                } else {

                    $data = array('middle_content' => 'bulk/bulk_add_regmemberNM', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'image' => $cap['image'], 'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'idtype_master' => $idtype_master, 'caiib_subjects' => $caiib_subjects, 'compulsory_subjects' => $compulsory_subjects, 'mem_info' => $mem_info, 'special_exam_dates' => $special_exam_dates, 'bulk_branch_master' => $bulk_branch_master, 'bulk_designation_master' => $bulk_designation_master, 'bulk_zone_master' => $bulk_zone_master, 'bulk_payment_scale_master' => $bulk_payment_scale_master, 'dra_images' => $dra_images);

                    $this->load->view('bulk/bulk_common_view', $data);

                }

            }

        } else {

            //$this->load->view('access_denied',$data);

            redirect(base_url() . 'bulk/BulkApply/accessdenied/');

        }

    }

    ##------------------ CMS Page for logged in user()---------------##

    public function comApplication()
    {

        if (isset($_POST['btnPreviewSubmit'])) {

            //echo '<pre>',print_r($_POST),'</pre>';exit;

            $scribe_flag = 'N';

            $caiib_subjects = array();

            $compulsory_subjects = array();

            $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $state = $password = $var_errors = '';

            $venue = $this->input->post('venue');

            $date = $this->input->post('date');

            $time = $this->input->post('time');

            if ($this->session->userdata('examinfo')) {

                $this->session->unset_userdata('examinfo');

            }

            //$this->form_validation->set_rules('email','Email','trim|required|valid_email|xss_clean|callback_check_emailduplication');

            //$this->form_validation->set_rules('mobile','Mobile','trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');

            $this->form_validation->set_rules('medium', 'Medium', 'required|xss_clean');

            $this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');

            $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');

            if (isset($_POST['bank_branch']) && $_POST['bank_branch'] != '') {

                $this->form_validation->set_rules('bank_branch', 'Bank Branch', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_designation']) && $_POST['bank_designation'] != '') {

                $this->form_validation->set_rules('bank_designation', 'Bank Designation', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_scale']) && $_POST['bank_scale'] != '') {

                $this->form_validation->set_rules('bank_scale', 'Pay Scale', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_zone']) && $_POST['bank_zone'] != '') {

                $this->form_validation->set_rules('bank_zone', 'Bank Zone', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_emp_id']) && $_POST['bank_emp_id'] != '') {

                $this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'trim|required|xss_clean');

            }

            if ($this->session->userdata('examcode') != 101
            		&& $this->session->userdata('is_elearning_course') == 'n'
                && $this->session->userdata('examcode') != 1010

                && $this->session->userdata('examcode') != 10100

                && $this->session->userdata('examcode') != 101000

                && $this->session->userdata('examcode') != 1010000

                && $this->session->userdata('examcode') != 10100000) /* && $this->session->userdata('examcode')!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {

                $this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');

                $this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');

                $this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');

            }

            if ($this->session->userdata('examcode') == $this->config->item('examCodeCaiib')) {

                $this->form_validation->set_rules('selSubcode', 'Elective Subject Name', 'required|xss_clean');

            }

            if ($this->session->userdata('examcode') == $this->config->item('examCodeJaiib')) {

                $this->form_validation->set_rules('placeofwork', 'Place of Work', 'trim|required|alpha_numeric_spaces|xss_clean');

                $this->form_validation->set_rules('state_place_of_work', 'State', 'trim|required|xss_clean');

                if ($this->input->post('state_place_of_work') != '') {

                    $state = $this->input->post('state_place_of_work');

                }

                $this->form_validation->set_rules('pincode_place_of_work', 'Pin Code', 'trim|required|numeric|xss_clean|callback_check_checkpin[' . $state . ']');

            }

            if ($this->form_validation->run() == true) {

                $subject_arr = array();

                $venue = $this->input->post('venue');

                $date = $this->input->post('date');

                $time = $this->input->post('time');

                $special_exam_date = '';

                $exam_category = $this->master_model->getRecords('exam_master', array('exam_code' => $this->session->userdata('examcode')));

                if ($exam_category[0]['exam_category'] == 1) {

                    $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                    $special_exam_apply_date = $this->master_model->getRecords('member_exam', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'bulk_isdelete' => '0', 'examination_date !=' => '0000-00-00'), 'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'

                    $specialdateapply = array();

                    if (count($special_exam_apply_date) > 0) {

                        foreach ($special_exam_apply_date as $row) {

                            $specialdateapply[] = $row['examination_date'];

                        }

                    }

                    $today_date = date('Y-m-d');

                    $this->db->where("'$today_date' BETWEEN from_date AND to_date");

                    $special_exam_dates = $this->master_model->getRecords('special_exam_dates');

                    $special_exam_date = $special_exam_dates[0]['examination_date'];

                }

                $splexamdate = '';

                if (count($venue) > 0 && count($date) > 0 && count($time) > 0) {

                    foreach ($venue as $k => $v) {

                        $splexamdate = $date[$k];

                        $compulsory_subjects_name = $this->master_model->getRecords('subject_master', array('exam_code' => base64_decode($_POST['excd']), 'subject_delete' => '0', 'exam_period' => $_POST['eprid'], 'subject_code' => $k), 'subject_description');

                        $subject_arr[$k] = array('venue' => $v, 'date' => $date[$k], 'session_time' => $time[$k], 'subject_name' => $compulsory_subjects_name[0]['subject_description']);

                    }

                    #### add elective subject in venue,time,date array#########

                    if (isset($_POST['venue_caiib']) && isset($_POST['date_caiib']) && isset($_POST['time_caiib'])) {

                        $subject_arr[$this->input->post('selSubcode')] = array('venue' => $this->input->post('venue_caiib'), 'date' => $this->input->post('date_caiib'), 'session_time' => $this->input->post('time_caiib'), 'subject_name' => $this->input->post('selSubName1'));

                    }

                    #########check duplication of venue,date,time##########

                    if (count($subject_arr) > 0) {

                        $msg = '';

                        $sub_flag = 1;

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

                            $capacity = check_capacity_bulk($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);

                            if ($capacity == 0) {

                                #########get message if capacity is full##########

                                $msg = getVenueDetails_bulk($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);

                            }

                            if ($msg != '') {

                                $this->session->set_flashdata('error', $msg);

                                redirect(base_url() . 'bulk/BulkApplyNM/add_member');

                            }

                        }

                    }

                    if ($sub_flag == 0) {

                        if (base64_decode($_POST['excd']) != 101
                        		 && $this->session->userdata('is_elearning_course')=='n'
                            && base64_decode($_POST['excd']) != 1010

                            && base64_decode($_POST['excd']) != 10100

                            && base64_decode($_POST['excd']) != 101000

                            && base64_decode($_POST['excd']) != 1010000

                            && base64_decode($_POST['excd']) != 10100000) /* && base64_decode($_POST['excd'])!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {

                            $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');

                            redirect(base_url() . 'bulk/BulkApplyNM/add_member');

                        }

                    }

                }

                $exam_category = $this->master_model->getRecords('exam_master', array('exam_code' => $this->session->userdata('examcode')));

                if ($exam_category[0]['exam_category'] == 1) {

                    ###############check wheather exam alredy applied on same date or not#########

                    $this->check_examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata('examcode'), $splexamdate);

                }

                if (isset($_POST['scribe_flag'])) {

                    $scribe_flag = 'Y';

                }

                /* DRA-BULK Bhushan Start */

                if (isset($_POST['bank_emp_id'])) {

                    $bank_emp_id = $_POST['bank_emp_id'];

                } else {

                    $bank_emp_id = '0';

                }

                /* End */

                $user_data = array('photo' => '',

                    'signname'                 => '',

                    'medium'                   => $_POST['medium'],

                    'selCenterName'            => $_POST['selCenterName'],

                    'optmode'                  => $_POST['optmode'],

                    'extype'                   => $_POST['extype'],

                    'exname'                   => $_POST['exname'],

                    'excd'                     => base64_decode($_POST["excd"]),

                    'eprid'                    => $_POST['eprid'],

                    'fee'                      => $_POST['fee'],

                    'txtCenterCode'            => $_POST['txtCenterCode'],

                    'insdet_id'                => '',

                    //'selected_elect_subcode'=>@$_POST['selSubcode'],

                    //'selected_elect_subname'=>@$_POST['selSubName1'],

                    'placeofwork'              => $_POST['placeofwork'],

                    'state_place_of_work'      => $_POST['state_place_of_work'],

                    'pincode_place_of_work'    => $_POST['pincode_place_of_work'],

                    'elected_exam_mode'        => $_POST['elected_exam_mode'],

                    'special_exam_date'        => $special_exam_date,

                    'grp_code'                 => $_POST['grp_code'],

                    'subject_arr'              => $subject_arr,

                    'scribe_flag'              => $scribe_flag,

                    'bank_branch'              => $_POST['bank_branch'],

                    'bank_designation'         => $_POST['bank_designation'],

                    'bank_scale'               => $_POST['bank_scale'],

                    'bank_zone'                => $_POST['bank_zone'],

                    'bank_emp_id'              => $bank_emp_id,

                    'elearning_flag'           => $_POST['elearning_flag'],

                    'discount_flag'            => $_POST['discount_flag'],

                    'free_paid_flag'           => $_POST['free_paid_flag'],

                    'reapeter_flag'            => $_POST['reapeter_flag'],

                );

                //echo '<pre>',print_r($user_data),'</pre>';exit;

                $this->session->set_userdata('examinfo', $user_data);

                //logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));

                //logactivity($log_title ="Member exam apply details", $log_message = serialize($user_data));

                /* User Log Activities : Bhushan */

                $log_title = "Non-Member bulk exam apply details";

                $log_message = serialize($user_data);

                $rId = $this->session->userdata('regid');

                $regNo = $this->session->userdata('mregnumber_applyexam');

                $inst_id = $this->session->userdata['institute_id'];

                //echo '<pre>',print_r($user_data),'</pre>';exit;

                bulk_storedUserActivity($log_title, $log_message, $inst_id, $rId, $regNo);

                /* Close User Log Actitives */

                //echo '<pre>',print_r($user_data),'</pre>';exit;

                redirect(base_url() . 'bulk/BulkApplyNM/preview');

            } else {

                $var_errors = str_replace("<p>", "<span>", $var_errors);

                $var_errors = str_replace("</p>", "</span><br>", $var_errors);

            }

        }

    }

    //##---------------exam application with new non-member registration----------(Tejasvi)-----##

    public function comApplication_reg()
    {

        if (!$this->session->userdata('examcode')) {
            redirect(base_url() . 'bulk/BulkApply/dashboard');
        }

        if (isset($_POST['btnSubmit'])) {
            //echo '<pre>',print_r($_POST),'</pre>';exit;
            $scribe_flag = 'N';
            $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';
            $this->form_validation->set_rules('sel_namesub', 'First Name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            $this->form_validation->set_rules('addressline1', 'Address line1', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
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
            if (isset($_POST['middlename']) && $_POST['middlename'] != '') {
                $this->form_validation->set_rules('middlename', 'Middle Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['lastname']) && $_POST['lastname'] != '') {
                $this->form_validation->set_rules('lastname', 'Last Name', 'trim|max_length[30]|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['optedu']) && $_POST['optedu'] == 'U') {
                $this->form_validation->set_rules('eduqual1', 'Please specify', 'trim|required|xss_clean|callback_check_exam_eligibility[' . $this->session->userdata('examcode') . ']');
            } else if (isset($_POST['optedu']) && $_POST['optedu'] == 'G') {
                $this->form_validation->set_rules('eduqual2', 'Please specify', 'trim|required|xss_clean|callback_check_exam_eligibility[' . $this->session->userdata('examcode') . ']');
            } else if (isset($_POST['optedu']) && $_POST['optedu'] == 'P') {
                $this->form_validation->set_rules('eduqual3', 'Please specify', 'trim|required|xss_clean|callback_check_exam_eligibility[' . $this->session->userdata('examcode') . ']');
            }

            if (isset($_POST['addressline2']) && $_POST['addressline2'] != '') {
                $this->form_validation->set_rules('addressline2', 'Address line2', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['addressline3']) && $_POST['addressline3'] != '') {
                $this->form_validation->set_rules('addressline3', 'Address line3', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['addressline4']) && $_POST['addressline4'] != '') {
                $this->form_validation->set_rules('addressline4', 'Address line4', 'trim|max_length[30]|required|alpha_numeric_spaces|xss_clean');
            }

            if (isset($_POST['stdcode']) && $_POST['stdcode'] != '') {
                $this->form_validation->set_rules('stdcode', 'STD Code', 'trim|max_length[4]|required|numeric|xss_clean');

            }

            if (isset($_POST['phone']) && $_POST['phone'] != '') {
                $this->form_validation->set_rules('phone', 'Phone No', 'trim|required|numeric|xss_clean');

            }

            //$this->form_validation->set_rules('institutionworking','Bank/Institution working','trim|required|alpha_numeric_spaces|xss_clean');

            //$this->form_validation->set_rules('office','Branch/Office','trim|required|xss_clean');

            //$this->form_validation->set_rules('designation','Designation','trim|required|xss_clean');

            //$this->form_validation->set_rules('doj1','Date of joining Bank/Institution','trim|required|xss_clean');

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean|callback_check_emailduplication');

            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric|min_length[10]|xss_clean|callback_check_mobileduplication');

            if ($this->session->userdata('examcode') != 101
            		&&  $this->session->userdata('is_elearning_course') == 'n'
                && $this->session->userdata('examcode') != 1010
                && $this->session->userdata('examcode') != 10100
                && $this->session->userdata('examcode') != 101000
                && $this->session->userdata('examcode') != 1010000
                && $this->session->userdata('examcode') != 10100000) /* && $this->session->userdata('examcode')!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {
                $this->form_validation->set_rules('venue[]', 'Venue', 'trim|required|xss_clean');
                $this->form_validation->set_rules('date[]', 'Date', 'trim|required|xss_clean');
                $this->form_validation->set_rules('time[]', 'Time', 'trim|required|xss_clean');

            }

            $this->form_validation->set_rules('scannedphoto', 'scanned Photograph', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedphoto_upload');

            $this->form_validation->set_rules('scannedsignaturephoto', 'Scanned Signature Specimen', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[20]|callback_scannedsignaturephoto_upload');

            $this->form_validation->set_rules('idproof', 'Id Proof', 'trim|required|xss_clean');

            $this->form_validation->set_rules('idNo', 'ID No', 'trim|required|max_length[25]|alpha_numeric_spaces|xss_clean');

            if ($this->session->userdata('examcode') != 101
            	||  $this->session->userdata('is_elearning_course') == 'n'
                || $this->input->post('aadhar_card') != ''
                || $this->session->userdata('examcode') != 1010
                || $this->session->userdata('examcode') != 10100
                || $this->session->userdata('examcode') != 101000
                || $this->session->userdata('examcode') != 1010000
                || $this->session->userdata('examcode') != 10100000) /* || $this->session->userdata('examcode')!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {
                if ($this->input->post('aadhar_card') != '') {
                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|max_length[12]|min_length[12]|numeric|xss_clean|callback_check_aadhar');
                }

            }

            if (isset($_POST['bank_branch']) && $_POST['bank_branch'] != '') {
                $this->form_validation->set_rules('bank_branch', 'Bank Branch', 'trim|required|numeric|xss_clean');
            }

            if (isset($_POST['bank_designation']) && $_POST['bank_designation'] != '') {
                $this->form_validation->set_rules('bank_designation', 'Bank Designation', 'trim|required|numeric|xss_clean');
            }

            if (isset($_POST['bank_scale']) && $_POST['bank_scale'] != '') {
                $this->form_validation->set_rules('bank_scale', 'Pay Scale', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_zone']) && $_POST['bank_zone'] != '') {
                $this->form_validation->set_rules('bank_zone', 'Bank Zone', 'trim|required|numeric|xss_clean');

            }

            if (isset($_POST['bank_emp_id']) && $_POST['bank_emp_id'] != '') {
                $this->form_validation->set_rules('bank_emp_id', 'Bank Employee Id', 'trim|required|xss_clean');
            }

            $this->form_validation->set_rules('idproofphoto', 'Id proof', 'file_required|file_allowed_type[jpg,jpeg]|file_size_max[300]|callback_idproofphoto_upload');
            if (  $this->session->userdata('is_elearning_course') == 'n') {
            	$this->form_validation->set_rules('medium', 'Medium', 'required|xss_clean');
            }

            $this->form_validation->set_rules('selCenterName', 'Centre Name', 'required|xss_clean');
            $this->form_validation->set_rules('txtCenterCode', 'Centre Code', 'required|xss_clean');

            //$this->form_validation->set_rules('code', 'Security Code ', 'trim|required|xss_clean|callback_check_captcha_userreg');
            $this->form_validation->set_rules('declaration1', 'I Accept', 'trim|required|xss_clean');

            if ($this->form_validation->run() == true) {

                $scannedphoto_file = $scannedsignaturephoto_file = $idproof_file = $outputphoto1 = $outputsign1 = $outputidproof1 = $state = '';

                $outputphoto1 = $outputsign1 = $outputsign1 = '';

                $scannedphoto_file = '';

                $scannedsignaturephoto_file = '';

                $idproof_file = '';

                $enduserinfo = $this->session->userdata('enduserinfo');

                if (count($enduserinfo)) {$this->session->unset_userdata('enduserinfo');}

                $subject_arr = array();
                $venue = $this->input->post('venue');
                $date = $this->input->post('date');
                $time = $this->input->post('time');
                $special_exam_date = '';
                $exam_category = $this->master_model->getRecords('exam_master', array('exam_code' => $this->session->userdata('examcode')));

                if ($exam_category[0]['exam_category'] == 1) {

                    $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                    $special_exam_apply_date = $this->master_model->getRecords('member_exam', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'bulk_isdelete' => '0', 'examination_date !=' => '0000-00-00'), 'examination_date'); /* <= Added By Bhushan *///,'pay_status'=>'1'

                    $specialdateapply = array();

                    if (count($special_exam_apply_date) > 0) {
                        foreach ($special_exam_apply_date as $row) {
                            $specialdateapply[] = $row['examination_date'];
                        }

                    }
                    $today_date = date('Y-m-d');
                    $this->db->where("'$today_date' BETWEEN from_date AND to_date");
                    $special_exam_dates = $this->master_model->getRecords('special_exam_dates');
                    $special_exam_date = $special_exam_dates[0]['examination_date'];
                }

                $splexamdate = '';

                ########### get POST data of subject ##############

                if (count($venue) > 0 && count($date) && count($time) > 0) {
                    foreach ($venue as $k => $v) {
                        $splexamdate = $date[$k];
                        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=subject_master.exam_code AND bulk_exam_activation_master.exam_period=subject_master.exam_period');

                        $compulsory_subjects_name = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'C', 'subject_code' => $k), 'subject_description');

                        $subject_arr[$k] = array('venue' => $v, 'date' => $date[$k], 'session_time' => $time[$k], 'subject_name' => $compulsory_subjects_name[0]['subject_description']);

                    }

                    #########check duplication of venue,date,time##########

                    if (count($subject_arr) > 0) {
                        $msg = '';
                        $sub_flag = 1;
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

                            $capacity = check_capacity_bulk($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);

                            if ($capacity == 0) {
                                #########get message if capacity is full##########
                                $msg = getVenueDetails_bulk($v['venue'], $v['date'], $v['session_time'], $_POST['selCenterName']);

                            }
                            if ($msg != '') {
                                $this->session->set_flashdata('error', $msg);
                                redirect(base_url() . 'bulk/BulkApplyNM/add_member');
                            }

                        }

                    }

                    if ($sub_flag == 0) {
                        $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');
                        redirect(base_url() . 'bulk/BulkApplyNM/add_member');
                        //redirect(base_url().'nonreg/member/?Mtype='.$this->input->get('Mtype').'=&ExId='.$this->input->get('ExId').'');
                    }

                }

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
                    $img = "scannedphoto";
                    $tmp_nm = strtotime($date) . rand(0, 100);
                    $new_filename = 'non_mem_photo_' . $tmp_nm;
                    $config = array('upload_path' => './uploads/photograph',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $file = $dt['file_name'];
                            $scannedphoto_file = $dt['file_name'];
                            $outputphoto1 = base_url() . "uploads/photograph/" . $scannedphoto_file;
                        } else {
                            $this->session->set_flashdata('error', 'Scanned Photograph :' . $this->upload->display_errors());
                            //$var_errors.=$this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {
                        $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                        //$var_errors.='The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic scan signature

                $inputsignature = $_POST["hiddenscansignature"];

                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img = "scannedsignaturephoto";
                    $tmp_signnm = strtotime($date) . rand(0, 100);
                    $sign_new_filename = 'non_mem_sign_' . $tmp_signnm;
                    $config = array('upload_path' => './uploads/scansignature',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $sign_new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $scannedsignaturephoto_file = $dt['file_name'];
                            $outputsign1 = base_url() . "uploads/scansignature/" . $scannedsignaturephoto_file;

                        } else {
                            //echo $this->upload->display_errors();;exit;
                            $this->session->set_flashdata('error', 'Scanned Signature :' . $this->upload->display_errors());
                            //    $var_errors.=$this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }
                    } else {

                        $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                        //$var_errors.='The filetype you are attempting to upload is not allowed';
                    }
                }

                // generate dynamic id proof
                $inputidproofphoto = $_POST["hiddenidproofphoto"];
                if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                    $img = "idproofphoto";
                    $tmp_inputidproof = strtotime($date) . rand(0, 100);
                    $new_filename = 'non_mem_idproof_' . $tmp_inputidproof;
                    $config = array('upload_path' => './uploads/idproof',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $new_filename);
                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproofphoto']['tmp_name']);

                    if ($size) {

                        if ($this->upload->do_upload($img)) {
                            $dt = $this->upload->data();
                            $idproof_file = $dt['file_name'];
                            $outputidproof1 = base_url() . "uploads/idproof/" . $idproof_file;
                        } else {
                            $this->session->set_flashdata('error', 'Id proof :' . $this->upload->display_errors());
                            //$var_errors.=$this->upload->display_errors();
                            //$data['error']=$this->upload->display_errors();
                        }

                    } else {
                        $this->session->set_flashdata('error', 'The filetype you are attempting to upload is not allowed');
                        //$var_errors.='The filetype you are attempting to upload is not allowed';
                    }
                }

               // Belwo code added by vishal to prevent mising images issue in bulk
                if (isset($_FILES['scannedphoto']['name']) && ($_FILES['scannedphoto']['name'] != '')) {
                    $img_x = "scannedphoto";
                    $tmp_nm_x = strtotime($date) . rand(0, 100);
                    $new_filename_x = $_POST["firstname"]."_".$_POST["lastname"].$tmp_nm_x;
                    $config_x = array('upload_path' => './uploads/bulk/photograph',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $new_filename_x);
                    $this->upload->initialize($config_x);
                    $size_x = @getimagesize($_FILES['scannedphoto']['tmp_name']);
                    if ($size_x) {
                        if ($this->upload->do_upload($img_x)) {
                           $this->upload->data();
                        } 
                    } 
                }


                if (isset($_FILES['scannedsignaturephoto']['name']) && ($_FILES['scannedsignaturephoto']['name'] != '')) {
                    $img_y = "scannedsignaturephoto";
                    $tmp_signnm_y = strtotime($date) . rand(0, 100);
                    $sign_new_filename_y = $_POST["firstname"]."_".$_POST["lastname"].$tmp_signnm_y;
                    $config_y = array('upload_path' => './uploads/bulk/scansignature',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $sign_new_filename_y);
                    $this->upload->initialize($config_y);
                    $size_y = @getimagesize($_FILES['scannedsignaturephoto']['tmp_name']);
                    if ($size_y) {
                        if ($this->upload->do_upload($img_y)) {
                             $this->upload->data();

                        } 
                    }
                }

                 if (isset($_FILES['idproofphoto']['name']) && ($_FILES['idproofphoto']['name'] != '')) {
                    $img_z = "idproofphoto";
                    $tmp_inputidproof_z = strtotime($date) . rand(0, 100);
                    $new_filename_z = $_POST["firstname"]."_".$_POST["lastname"]. $tmp_inputidproof_z;
                    $config_z = array('upload_path' => './uploads/bulk/idproof',
                        'allowed_types'               => 'jpg|jpeg',
                        'file_name'                   => $new_filename_z);
                    $this->upload->initialize($config_z);
                    $size_z = @getimagesize($_FILES['idproofphoto']['tmp_name']);

                    if ($size_z) {

                        if ($this->upload->do_upload($img_z)) {
                             $this->upload->data();
                        } 

                    }
                }
                // end code added by vishal to prevent mising images issue in bulk

                $dob1 = $_POST["dob1"];
                $dob = str_replace('/', '-', $dob1);
                $dateOfBirth = date('Y-m-d', strtotime($dob));
                /*added scribe_flag : pooja*/
                if (isset($_POST['scribe_flag'])) {
                    $scribe_flag = 'Y';
                }

              if ($scannedphoto_file != '' && $idproof_file != '' && $scannedsignaturephoto_file != '') {
              				$exam_medium = $_POST['medium'];
                		if ( $this->session->userdata('is_elearning_course') == 'y') {
              				$exam_medium = 'E';
                		}
                    $user_data = array(
                    		'firstname' => $_POST["firstname"],
                        'sel_namesub'                  => $_POST["sel_namesub"],
                        'addressline1'                 => $_POST["addressline1"],
                        'addressline2'                 => $_POST["addressline2"],
                        'addressline3'                 => $_POST["addressline3"],
                        'addressline4'                 => $_POST["addressline4"],
                        'city'                         => $_POST["city"],
                        //'code'                    =>trim($_POST["code"]),
                        'district'                     => $_POST["district"],
                        'dob'                          => $dateOfBirth,
                        'eduqual'                      => $_POST["eduqual"],
                        'eduqual1'                     => $eduqual1,
                        'eduqual2'                     => $eduqual2,
                        'eduqual3'                     => $eduqual3,
                        'email'                        => $_POST["email"],
                        'gender'                       => $_POST["gender"],
                        'idNo'                         => $_POST["idNo"],
                        'idproof'                      => $_POST["idproof"],
                        'lastname'                     => $_POST["lastname"],
                        'middlename'                   => $_POST["middlename"],
                        'mobile'                       => $_POST["mobile"],
                        'optedu'                       => $_POST["optedu"],
                        'optnletter'                   => $_POST["optnletter"],
                        'phone'                        => $_POST["phone"],
                        'pincode'                      => $_POST["pincode"],
                        'state'                        => $_POST["state"],
                        'stdcode'                      => $_POST["stdcode"],
                        'scannedphoto'                 => $outputphoto1,
                        'scannedsignaturephoto'        => $outputsign1,
                        'idproofphoto'                 => $outputidproof1,
                        'photoname'                    => $scannedphoto_file,
                        'signname'                     => $scannedsignaturephoto_file,
                        'idname'                       => $idproof_file,
                        'selCenterName'                => $_POST["selCenterName"],
                        'txtCenterCode'                => $_POST["txtCenterCode"],
                        'optmode'                      => $_POST["optmode"],
                        'exid'                         => $_POST["exid"],
                        'mtype'                        => $_POST["mtype"],
                        'memtype'                      => $_POST["memtype"],
                        'eprid'                        => $_POST["eprid"],
                        'exam_month'                   => $_POST["exmonth"],
                        'rrsub'                        => $_POST["rrsub"],
                        'excd'                         => base64_decode($_POST["excd"]),
                        'exname'                       => $_POST["exname"],
                        'fee'                          => $_POST["fee"],
                        'medium'                       => $exam_medium,
                        'aadhar_card'                  => $_POST['aadhar_card'],
                        'grp_code'                     => $_POST['grp_code'],
                        'bank_branch'                  => $_POST['bank_branch'],
                        'bank_designation'             => $_POST['bank_designation'],
                        'bank_scale'                   => $_POST['bank_scale'],
                        'bank_zone'                    => $_POST['bank_zone'],
                        'bank_emp_id'                  => $_POST['bank_emp_id'],
                        'special_exam_date'            => $special_exam_date,
                        'subject_arr'                  => $subject_arr,
                        'scribe_flag'                  => $scribe_flag,
                        'elearning_flag'               => $_POST['elearning_flag'],
                        'discount_flag'                => $_POST['discount_flag'],
                        'reapeter_flag'                => $_POST['reapeter_flag'],
                    );

                    $this->session->set_userdata('enduserinfo', $user_data);
                    //echo '<pre>_POST',print_r($_POST),'</pre>';
                    //echo '<pre>user_data',print_r($user_data),'</pre>';exit;
                    $log_title = "Non-Member bulk exam apply details";
                    $log_message = serialize($user_data);
                    $rId = $this->session->userdata('regid');
                    $regNo = $this->session->userdata('mregnumber_applyexam');
                    $inst_id = $this->session->userdata['institute_id'];
                    //echo '<pre>',print_r($user_data),'</pre>';exit;
                    bulk_storedUserActivity($log_title, $log_message, $inst_id, $rId, $regNo);
                    redirect(base_url() . 'bulk/BulkApplyNM/exam_preview');
                }
            }
        }

        $institute_id = $this->session->userdata('institute_id');
        $exam_code = $this->session->userdata('examcode');
        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));
        $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));
        $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));
        $states = $this->master_model->getRecords('state_master');
        $this->db->where("bulk_branch_master.institute_id", $institute_id);
        $bulk_branch_master = $this->master_model->getRecords('bulk_branch_master');
        $this->db->where("bulk_designation_master.institute_id", $institute_id);
        $bulk_designation_master = $this->master_model->getRecords('bulk_designation_master');
        $this->db->where("bulk_zone_master.institute_id", $institute_id);
        $bulk_zone_master = $this->master_model->getRecords('bulk_zone_master');
        $this->db->where("bulk_payment_scale_master.institute_id", $institute_id);
        $bulk_payment_scale_master = $this->master_model->getRecords('bulk_payment_scale_master');
        $this->db->not_like('name', 'Declaration Form');
        $this->db->not_like('name', 'college');
        $this->db->not_like('name', 'Aadhaar id');
        $this->db->not_like('name', 'Election Voters card');
        $idtype_master = $this->master_model->getRecords('idtype_master');
        $this->db->select('exam_master.*,misc_master.*');
        $this->db->join('misc_master', 'misc_master.exam_code=exam_master.exam_code');
        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND misc_master.exam_period=bulk_exam_activation_master.exam_period'); //added on 5/6/2017
        $this->db->where("misc_master.misc_delete", '0');
        $this->db->where('exam_master.exam_code', $exam_code);
        $examinfo = $this->master_model->getRecords('exam_master');
        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');
        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));
        $this->db->where('medium_master.exam_code', $exam_code);
        $this->db->where('medium_delete', '0');
        $medium = $this->master_model->getRecords('medium_master');

        //subject information

        $caiib_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata('examcode'), 'subject_delete' => '0', 'group_code' => 'E', 'exam_period' => $examinfo[0]['exam_period']));

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));
        $this->db->where("center_delete", '0');
        $this->db->where('exam_name', $exam_code);
        $this->db->group_by('center_master.center_name');
        $center = $this->master_model->getRecords('center_master');
        $this->load->helper('captcha');
        $this->session->unset_userdata("nonmemregcaptcha_bulk");
        $this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));
        $vals = array(
            'img_path' => './uploads/applications/',
            'img_url'  => base_url() . 'uploads/applications/',
        );

        $cap = create_captcha($vals);
        $_SESSION["nonmemregcaptcha_bulk"] = $cap['word']; //nonmemlogincaptcha
        $flag = 1;
        if (!count($examinfo) > 0 || !count($medium) > 0 || !count($center) > 0) {
            $flag = 0;
        }

        if ($flag == 1) {
            ############# get Compulsory Subject List ##############

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=subject_master.exam_code AND bulk_exam_activation_master.exam_period=subject_master.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $compulsory_subjects = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => $exam_code, 'subject_delete' => '0', 'group_code' => 'C'));

            $data = array('middle_content' => 'bulk/bulk_add_memberNM', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'image' => $cap['image'], 'examinfo' => $examinfo, 'medium' => $medium, 'center' => $center, 'idtype_master' => $idtype_master, 'caiib_subjects' => $caiib_subjects, 'compulsory_subjects' => $compulsory_subjects, 'bulk_branch_master' => $bulk_branch_master, 'bulk_designation_master' => $bulk_designation_master, 'bulk_zone_master' => $bulk_zone_master, 'bulk_payment_scale_master' => $bulk_payment_scale_master);

            $this->load->view('bulk/bulk_common_view', $data);

        } else {

            echo 'Access denied';

        }

    }

    //call back for check exam code 19 and specified qualification to be Company secretary(CS)

    public function check_exam_eligibility($specify_qualification, $examcode)
    {

        if ($specify_qualification != "" && $examcode != '') {

            if ($examcode == 19) {

                if ($specify_qualification == 91 && $examcode == 19) {

                    $this->form_validation->set_message('error', "");

                    return true;

                } else {

                    $str = 'You are not eligible to apply for exam';

                    $this->form_validation->set_message('check_exam_eligibility', $str);

                    return false;

                }

            } else {

                return true;

            }

        } else {

            $str = 'exam / qualification field is required.';

            $this->form_validation->set_message('check_exam_eligibility', $str);

            return false;

        }

    }

    ##------------------ Preview for applied exam,for logged in user()---------------##

    public function preview()
    {

        //$this->chk_session->checklogin();

        if (empty($this->session->userdata['institute_id'])) {

            redirect(base_url() . 'bulk/Banklogin/');

        }

        $sub_flag = 1;

        $sub_capacity = 1;

        //echo $this->session->userdata['examinfo']['selCenterName'];exit;

        $compulsory_subjects = array();

        if (!$this->session->userdata('examinfo')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //check exam acivation

        $check_exam_activation = bulk_check_exam_activate($this->session->userdata['examinfo']['excd']);

        if ($check_exam_activation['flag'] == 0) {

            redirect(base_url() . 'bulk/BulkApply/accessdenied/');

        }

        ############check capacity is full or not ##########

        $subject_arr = $this->session->userdata['examinfo']['subject_arr'];

        if (count($subject_arr) > 0) {

            $msg = '';

            $sub_flag = 1;

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

                $capacity = check_capacity_bulk($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);

                if ($capacity == 0) {

                    #########get message if capacity is full##########

                    $msg = getVenueDetails_bulk($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['examinfo']['selCenterName']);

                }

                if ($msg != '') {

                    $this->session->set_flashdata('error', $msg);

                    //redirect(base_url().'bulk/BulkApply/comApplication');

                    redirect(base_url() . 'bulk/BulkApplyNM/add_member');

                }

            }

        }

        if ($sub_flag == 0) {

            $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');

            //redirect(base_url().'bulk/BulkApply/comApplication');

            redirect(base_url() . 'bulk/BulkApplyNM/add_member');

        }

        /*$cookieflag=1;

        //$this->chk_session->checkphoto();

        //ask user to wait for 5 min, until the payment transaction process complete by ()

        $valcookie=$this->session->userdata('mregnumber_applyexam');

        if($valcookie)

        {

        $regnumber= $valcookie;

        $checkpayment=$this->master_model->getRecords('payment_transaction',array('member_regnumber'=>$regnumber,'status'=>'2','pay_type'=>'2'),'',array('id'=>'DESC'));

        if(count($checkpayment) > 0)

        {

        $endTime = date("Y-m-d H:i:s",strtotime("+5 minutes",strtotime($checkpayment[0]['date'])));

        $current_time= date("Y-m-d H:i:s");

        if(strtotime($current_time)<=strtotime($endTime))

        {

        $cookieflag=0;

        }

        else

        {

        delete_cookie('examid');

        }

        }

        else

        {

        delete_cookie('examid');

        }

        }

        else

        {

        delete_cookie('examid');

        } */

        //End Of ask user to wait for 5 min, until the payment transaction process complete by ()

        if (!$this->session->userdata('examinfo')) {

            $this->session->set_flashdata('error', 'Session expire!!');

            redirect(base_url() . 'bulk/BulkApply/add_member/');

        }

        //check for valid fee

        if ($this->session->userdata['examinfo']['excd'] != 1017 && $this->session->userdata['examinfo']['excd'] != 1018) {

            if ($this->session->userdata['examinfo']['fee'] == 0 || $this->session->userdata['examinfo']['fee'] == '') {

                //echo 'in';exit;

                if ($this->session->userdata['examinfo']['excd'] != 1015 && $this->session->userdata['examinfo']['free_paid_flag'] != 'N') {

                    $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');

                    //redirect(base_url().'bulk/BulkApply/comApplication');

                    redirect(base_url() . 'bulk/BulkApplyNM/add_member');

                }

            }

        }

        /*$examination_date = $this->session->userdata['examinfo']['special_exam_date'];

        $exam_category=$this->master_model->getRecords('exam_master',array('exam_code'=>$this->session->userdata('examcode')));

        if($exam_category[0]['exam_category']==1)

        {

        ###############check wheather exam alredy applied on same date or not#########

        $this->check_examapplied($this->session->userdata('mregnumber_applyexam'),$this->session->userdata('examcode'), $examination_date);

        }    */

        $check = $this->examapplied($this->session->userdata('mregnumber_applyexam'), $this->session->userdata['examinfo']['excd']);

        if (!$check) {

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->where('medium_master.exam_code', $this->session->userdata['examinfo']['excd']);

            $this->db->where('medium_delete', '0');

            $medium = $this->master_model->getRecords('medium_master');

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->where('exam_name', $this->session->userdata['examinfo']['excd']);

            $this->db->where('center_code', $this->session->userdata['examinfo']['selCenterName']);

            $center = $this->master_model->getRecords('center_master', '', 'center_name');

            if ($this->session->userdata['examinfo']['excd'] == '1017' || $this->session->userdata['examinfo']['excd'] == '1018') {

                $user_info = $this->master_model->getRecords('dra_members', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));

            } else {

                $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));

            }

            //echo $this->db->last_query();

            //print_r($user_info);

            if (count($user_info) <= 0) {

                redirect(base_url() . 'bulk');

            }

            $this->db->where('state_delete', '0');

            $states = $this->master_model->getRecords('state_master');

            $institute_id = $this->session->userdata('institute_id');

            $this->db->where("bulk_branch_master.institute_id", $institute_id);

            $bulk_branch_master = $this->master_model->getRecords('bulk_branch_master');

            $this->db->where("bulk_designation_master.institute_id", $institute_id);

            $bulk_designation_master = $this->master_model->getRecords('bulk_designation_master');

            $this->db->where("bulk_zone_master.institute_id", $institute_id);

            $bulk_zone_master = $this->master_model->getRecords('bulk_zone_master');

            $this->db->where("bulk_payment_scale_master.institute_id", $institute_id);

            $bulk_payment_scale_master = $this->master_model->getRecords('bulk_payment_scale_master');

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $misc = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata['examinfo']['excd'], 'misc_delete' => '0'));

            /*if($cookieflag==0)

            {

            $data=array('middle_content'=>'exam_apply_cms_msg');

            }

            else

            {}*/

            $data = array('middle_content' => 'bulk/exam_preview', 'user_info' => $user_info, 'medium' => $medium, 'center' => $center, 'misc' => $misc, 'states' => $states, 'compulsory_subjects' => $this->session->userdata['examinfo']['subject_arr'], 'bulk_branch_master' => $bulk_branch_master, 'bulk_designation_master' => $bulk_designation_master, 'bulk_zone_master' => $bulk_zone_master, 'bulk_payment_scale_master' => $bulk_payment_scale_master);

            $this->load->view('bulk/bulk_common_view', $data);

        } else {

            $exam_category = $this->master_model->getRecords('exam_master', array('exam_code' => $this->session->userdata('examcode')));

            if ($exam_category[0]['exam_category'] == 1) {

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $this->session->userdata['examinfo']['excd'], 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'examination_date');

                $special_exam_dates = $this->master_model->getRecords('special_exam_dates', array('examination_date' => $applied_exam_info[0]['examination_date']), 'period');

                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata['examinfo']['excd'], 'misc_master.misc_delete' => '0', 'exam_period' => $special_exam_dates[0]['period']), 'exam_month');

                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>.period. Hence you need not apply for the same.';

                $flag = 0;

                $data = array('middle_content' => 'bulk/already_apply', 'check_eligibility' => $message);

                $this->load->view('bulk/bulk_common_view', $data);

            } else {

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                $get_period_info = $this->master_model->getRecords('misc_master', array('misc_master.exam_code' => $this->session->userdata['examinfo']['excd'], 'misc_master.misc_delete' => '0'), 'exam_month');

                //$month = date('Y')."-".substr($get_period_info[0]['exam_month'],4)."-".date('d');

                $month = date('Y') . "-" . substr($get_period_info[0]['exam_month'], 4);

                $exam_period_date = date('F', strtotime($month)) . "-" . substr($get_period_info[0]['exam_month'], 0, -2);

                $message = 'Application for this examination is already registered by you and is valid for <strong>' . $exam_period_date . '</strong>. period. Hence you need not apply for the same.';

                $flag = 0;

                $data = array('middle_content' => 'bulk/already_apply', 'check_eligibility' => $message);

                $this->load->view('bulk/bulk_common_view', $data);

            }

        }

    }

    public function exam_preview()
    {

        $ex_prd = '';

        if (isset($this->session->userdata['exmCrdPrd']['exam_prd'])) {

            $ex_prd = $this->session->userdata['exmCrdPrd']['exam_prd'];

        }

        //$this->chk_session->checklogin();

        if (empty($this->session->userdata['institute_id'])) {

            redirect(base_url() . 'bulk/Banklogin/');

        }

        $sub_flag = 1;

        if (!$this->session->userdata('enduserinfo')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //check exam acivation

        $check_exam_activation = bulk_check_exam_activate($this->session->userdata['enduserinfo']['excd']);

        if ($check_exam_activation['flag'] == 0) {

            redirect(base_url() . 'bulk/BulkApply/accessdenied/');

        }

        ############check capacity is full or not ##########

        $subject_arr = $this->session->userdata['enduserinfo']['subject_arr'];

        if (count($subject_arr) > 0) {

            $msg = '';

            $sub_flag = 1;

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

                $capacity = check_capacity_bulk($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['enduserinfo']['selCenterName']);

                if ($capacity == 0) {

                    #########get message if capacity is full##########

                    $msg = getVenueDetails_bulk($v['venue'], $v['date'], $v['session_time'], $this->session->userdata['enduserinfo']['selCenterName']);

                }

                if ($msg != '') {

                    $this->session->set_flashdata('error', $msg);

                    //redirect(base_url().'bulk/BulkApply/comApplication');

                    redirect(base_url() . 'bulk/BulkApplyNM/add_member');

                }

            }

        }

        if ($sub_flag == 0) {

            $this->session->set_flashdata('error', 'Date and Time for Venue can not be same!');

            //redirect(base_url().'bulk/BulkApply/comApplication');

            redirect(base_url() . 'bulk/BulkApplyNM/add_member');

        }

        //check email,mobile duplication on the same time from different browser!!

        $endTime = date("H:i:s");

        $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));

        $this->db->where('Time(createdon) BETWEEN "' . $start_time . '" and "' . $endTime . '"');

        $this->db->where('email', $this->session->userdata['enduserinfo']['email']);

        $this->db->or_where('email', $this->session->userdata['enduserinfo']['mobile']);

        $check_duplication = $this->master_model->getRecords('member_registration', array('isactive' => 0));

        if (count($check_duplication) > 0) {

            //redirect(base_url().'bulk/BulkApply/accessdenied/');

        }

        //check for valid fee

        if ($this->session->userdata['enduserinfo']['fee'] == 0 || $this->session->userdata['enduserinfo']['fee'] == '') {

            $this->session->set_flashdata('error', 'Fee can not be zero(0) or Blank!!');

            redirect(base_url() . 'bulk/BulkApplyNM/add_member');

        }

        $institute_id = $this->session->userdata('institute_id');

        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));

        $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));

        $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $institution_master = $this->master_model->getRecords('institution_master');

        $states = $this->master_model->getRecords('state_master');

        $designation = $this->master_model->getRecords('designation_master');

        $idtype_master = $this->master_model->getRecords('idtype_master');

        $this->db->where("bulk_branch_master.institute_id", $institute_id);

        $bulk_branch_master = $this->master_model->getRecords('bulk_branch_master');

        $this->db->where("bulk_designation_master.institute_id", $institute_id);

        $bulk_designation_master = $this->master_model->getRecords('bulk_designation_master');

        $this->db->where("bulk_zone_master.institute_id", $institute_id);

        $bulk_zone_master = $this->master_model->getRecords('bulk_zone_master');

        $this->db->where("bulk_payment_scale_master.institute_id", $institute_id);

        $bulk_payment_scale_master = $this->master_model->getRecords('bulk_payment_scale_master');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=medium_master.exam_code AND bulk_exam_activation_master.exam_period=medium_master.exam_period');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

        $this->db->where('medium_delete', '0');

        if ($ex_prd != '') {

            $this->db->where('medium_master.exam_period', $ex_prd);

        }

        $medium = $this->master_model->getRecords('medium_master', array('medium_master.exam_code' => $this->session->userdata['enduserinfo']['excd']));

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=center_master.exam_name AND bulk_exam_activation_master.exam_period=center_master.exam_period');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

        if ($ex_prd != '') {

            $this->db->where('center_master.exam_period', $ex_prd);

        }

        $center = $this->master_model->getRecords('center_master', array('exam_name' => $this->session->userdata['enduserinfo']['excd']));

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=misc_master.exam_code AND bulk_exam_activation_master.exam_period=misc_master.exam_period');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

        $this->db->like('misc_master.exam_code', $this->session->userdata['enduserinfo']['excd']);

        $exam_period = $this->master_model->getRecords('misc_master', '', 'misc_master.exam_period');

        //echo $this->db->last_query();exit;

        $data = array('middle_content' => 'bulk/exam_preview_register_NM', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'medium' => $medium, 'center' => $center, 'exam_period' => $exam_period, 'idtype_master' => $idtype_master, 'compulsory_subjects' => $this->session->userdata['enduserinfo']['subject_arr'], 'bulk_branch_master' => $bulk_branch_master, 'bulk_designation_master' => $bulk_designation_master, 'bulk_zone_master' => $bulk_zone_master, 'bulk_payment_scale_master' => $bulk_payment_scale_master);

        $this->load->view('bulk/bulk_common_view', $data);

    }

    ##------------------Insert data in member_exam table for applied exam,for logged in user With Payment using Billdesk Gate-way()---------------##

    public function Msuccess()
    {

        //echo '<pre>',print_r($this->session->userdata['examinfo']); exit;

        //$this->chk_session->checklogin();

        $photoname = $singname = '';

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $elected_sub_code = '';

        if (!empty($this->session->userdata['examinfo']['selected_elect_subcode'])) {

            $elected_sub_code = $this->session->userdata['examinfo']['selected_elect_subcode'];

        }

        if (isset($_POST['btnPreview'])) {

            $update_array1 = array('bank_branch' => $this->session->userdata['examinfo']['bank_branch'],

                'bank_designation'                   => $this->session->userdata['examinfo']['bank_designation'],

                'bank_scale'                         => $this->session->userdata['examinfo']['bank_scale'],

                'bank_zone'                          => $this->session->userdata['examinfo']['bank_zone'],

                'bank_emp_id'                        => $this->session->userdata['examinfo']['bank_emp_id']);

            $this->master_model->updateRecord('member_registration', $update_array1, array('regnumber' => $this->session->userdata('mregnumber_applyexam')));

            $amount = bulk_getExamFee($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag'], $this->session->userdata['examinfo']['discount_flag']);

            //##------------get app_category and base_fee

            $fee_result = bulk_getFee_Appcat($this->session->userdata['examinfo']['txtCenterCode'], $this->session->userdata['examinfo']['eprid'], $this->session->userdata['examinfo']['excd'], $this->session->userdata['examinfo']['grp_code'], $this->session->userdata('memtype'), $this->session->userdata['examinfo']['elearning_flag'], $this->session->userdata['examinfo']['discount_flag']);

            //$free_paid_flag = 'P';

            $free_paid_flag = $this->session->userdata['examinfo']['free_paid_flag'];

            if ($free_paid_flag == 'F' && $this->session->userdata['examinfo']['elearning_flag'] == 'N' && ($this->session->userdata['examinfo']['excd'] == 1017 || $this->session->userdata['examinfo']['excd'] == 1018)) {

                $base_fee = 0;

                $original_base_fee = 0;

                $discount_percent = $fee_result['discount_percent'];

                $calculate_discount = 0;

                $taken_discount = 0;

                $amount = 0;

            } elseif ($free_paid_flag == 'F' && $this->session->userdata['examinfo']['elearning_flag'] == 'Y' && ($this->session->userdata['examinfo']['excd'] == 1017 || $this->session->userdata['examinfo']['excd'] == 1018)) {

                $base_fee = 250;

                $original_base_fee = 250;

                $discount_percent = $fee_result['discount_percent'];

                $calculate_discount = 0;

                $taken_discount = 0;

                $amount = 250;

            } else {

                if ($this->session->userdata['examinfo']['excd'] == 1015 && $this->session->userdata['examinfo']['free_paid_flag'] == 'N') {

                    $base_fee = 0;

                    $amount = 0;

                    $original_base_fee = 0;

                    $discount_percent = 0;

                    $calculate_discount = 0;

                    $taken_discount = 0;

                    $free_paid_flag = 'F';

                } else {

                    $base_fee = $fee_result['base_fee'];

                    $original_base_fee = $fee_result['original_base_fee'];

                    $discount_percent = $fee_result['discount_percent'];

                    $calculate_discount = $fee_result['calculate_discount'];

                    $taken_discount = $fee_result['taken_discount'];

                }

            }

            $inser_array = array('regnumber' => $this->session->userdata('mregnumber_applyexam'),

                'member_type'                    => $this->session->userdata('memtype'),

                'app_category'                   => $fee_result['grp_code'],

                'base_fee'                       => $base_fee,

                'original_base_fee'              => $original_base_fee,

                'bulk_discount_flg'              => $fee_result['bulk_discount_flg'],

                'discount_percent'               => $discount_percent,

                'discount_amount'                => $fee_result['discount_amount'],

                'calculate_discount'             => $calculate_discount,

                'taken_discount'                 => $taken_discount,

                'exam_code'                      => $this->session->userdata['examinfo']['excd'],

                'exam_mode'                      => $this->session->userdata['examinfo']['optmode'],

                'exam_medium'                    => $this->session->userdata['examinfo']['medium'],

                'exam_period'                    => $this->session->userdata['examinfo']['eprid'],

                'exam_center_code'               => $this->session->userdata['examinfo']['txtCenterCode'],

                'exam_fee'                       => $amount,

                'scribe_flag'                    => $this->session->userdata['examinfo']['scribe_flag'],

                'created_on'                     => date('y-m-d H:i:s'),

                'pay_status'                     => '2',

                'institute_id'                   => $this->session->userdata['institute_id'],

                'bulk_isdelete'                  => '0',

                'examination_date'               => $this->session->userdata['examinfo']['special_exam_date'],

                'elearning_flag'                 => $this->session->userdata['examinfo']['elearning_flag'],

                'free_paid_flg'                  => $free_paid_flag,

                'reapeter_flag'                  => $this->session->userdata['examinfo']['reapeter_flag'],

            );

            $inser_id = $this->master_model->insertRecord('member_exam', $inser_array, true);

            //echo $inser_id;

            //echo '54564';

            if ($inser_id) {

                //echo $this->session->userdata['examinfo']['fee'];

                $this->session->userdata['examinfo']['insdet_id'] = $inser_id;

                //$data['insdet_id'] =$inser_id;

                //$this->session->set_userdata('examinfo', $data);

                $update_array = array();

                // Re-set previous image update flags

                $prev_edited_on = '';

                $prev_photo_flg = "N";

                $prev_signature_flg = "N";

                $prev_id_flg = "N";

                $prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam')), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');

                if (count($prev_edited_on_qry)) {

                    $prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];

                    $prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];

                    $prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];

                    $prev_id_flg = $prev_edited_on_qry[0]['id_flg'];

                    if ($prev_edited_on != date('Y-m-d')) {

                        $this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N'), array('regid' => $this->session->userdata('mregid_applyexam')));

                    }

                }

                //update an array for images

                $photo_flg = '';

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$photo_flg = 'N';} else { $photo_flg = $prev_photo_flg;}

                $signature_flg = '';

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$signature_flg = 'N';} else { $signature_flg = $prev_signature_flg;}

                if ($this->session->userdata['examinfo']['photo'] != '') {

                    $update_array = array_merge($update_array, array("scannedphoto" => $this->session->userdata['examinfo']['photo']));

                    $photo_name = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'scannedphoto');

                    $photoname = $photo_name[0]['scannedphoto'];

                    $photo_flg = 'Y';

                }

                if ($this->session->userdata['examinfo']['signname'] != '') {

                    $update_array = array_merge($update_array, array("scannedsignaturephoto" => $this->session->userdata['examinfo']['signname']));

                    $sing_name = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')), 'scannedsignaturephoto');

                    $singname = $sing_name[0]['scannedsignaturephoto'];

                    $signature_flg = 'Y';

                }

                if ($prev_edited_on != date('Y-m-d') && ($photo_flg == 'Y' || $signature_flg == 'Y')) {

                    $update_array['photo_flg'] = $photo_flg;

                    $update_array['signature_flg'] = $signature_flg;

                    $update_array['images_editedon'] = date('Y-m-d H:i:s');

                    $update_array['images_editedby'] = 'Candidate';

                }

                $email_mbl_flg = 0;

                //get email and mobile

                if ($this->session->userdata['examinfo']['excd'] != '1017' && $this->session->userdata['examinfo']['excd'] != '1018') {

                    $user_data = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'), array('email', 'mobile'));

                } else {

                    $user_data = $this->master_model->getRecords('dra_members', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'), array('email', 'mobile'));

                }

                //check if email is unique

                if ($this->session->userdata['examinfo']['excd'] != '1017' && $this->session->userdata['examinfo']['excd'] != '1018') {

                    $check_email = $this->master_model->getRecordCount('member_registration', array('email' => $user_data[0]['email'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

                } else {

                    $check_email = $this->master_model->getRecordCount('dra_members', array('email' => $user_data[0]['email'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

                }

                if ($check_email == 0) {

                    $update_array = array_merge($update_array, array("email" => $user_data[0]['email']));

                    $email_mbl_flg = 1;

                }

                // check if mobile is unique

                if ($this->session->userdata['examinfo']['excd'] != '1017' && $this->session->userdata['examinfo']['excd'] != '1018') {

                    $check_mobile = $this->master_model->getRecordCount('member_registration', array('mobile' => $user_data[0]['mobile'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

                } else {

                    $check_mobile = $this->master_model->getRecordCount('dra_members', array('mobile' => $user_data[0]['mobile'], 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

                }

                if ($check_mobile == 0) {

                    $update_array = array_merge($update_array, array("mobile" => $user_data[0]['mobile']));

                    $email_mbl_flg = 1;

                }

                if (count($update_array) > 0) {

                    $edited = '';

                    foreach ($update_array as $key => $val) {

                        $edited .= strtoupper($key) . " = " . strtoupper($val) . " && ";

                    }

                    if ($email_mbl_flg == 1) {

                        $update_array['editedon'] = date('Y-m-d H:i:s');

                        $update_array['editedby'] = "Candidate";

                    }

                    $prevData = array();

                    /* $user_info=$this->master_model->getRecords('member_registration',array('regid'=>$this->session->userdata('mregid_applyexam'),'regnumber'=>$this->session->userdata('mregnumber_applyexam'),'isactive'=>'1'));

                     */

                    if (count($user_info)) {

                        $prevData = $user_info[0];

                    }

                    $desc['updated_data'] = $update_array;

                    $desc['old_data'] = $prevData;

                    $inst_id = $this->session->userdata['institute_id'];

                    $this->master_model->updateRecord('member_registration', $update_array, array('regid' => $this->session->userdata('mregid_applyexam'), 'regnumber' => $this->session->userdata('mregnumber_applyexam')));

                    //bulk_log_profile_user($log_title = "Profile updated successfully", $edited,'data',$this->session->userdata('mregid_applyexam'),$this->session->userdata('mregnumber_applyexam'));

                    bulk_logactivity($log_title = "Non Member update profile during exam apply", $log_message = serialize($desc), $inst_id);

                }

                /*if($this->config->item('exam_apply_gateway')=='sbi')

                {

                redirect(base_url().'Applyexam/sbi_make_payment/');

                }

                else

                {

                redirect(base_url().'Applyexam/make_payment/');

                }*/

                //Insert record in bulk admit_card_details##-------Start------## (Tejasvi)

                //################get userdata###########

                if ($this->session->userdata['examinfo']['excd'] != '1017' && $this->session->userdata['examinfo']['excd'] != '1018') {

                    $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'));

                } else {

                    $user_info = $this->master_model->getRecords('dra_members', array('regnumber' => $this->session->userdata('mregnumber_applyexam'), 'isactive' => '1'));

                }

                //############check Gender########

                if ($user_info[0]['gender'] == 'male') {$gender = 'M';} else { $gender = 'F';}

                //########prepare user name########

                $username = $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];

                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                //###########get State##########

                $states = $this->master_model->getRecords('state_master', array('state_code' => $user_info[0]['state'], 'state_delete' => '0'));

                $state_name = '';

                if (count($states) > 0) {

                    $state_name = $states[0]['state_name'];

                }

                //#######get Institute info########

                if (!empty($this->session->userdata['institute_id']) && ($this->session->userdata['institute_name'])) {

                    $institute_id = $this->session->userdata['institute_id'];

                    $institution_name = $this->session->userdata['institute_name'];

                }

                /* else

                {

                $this->session->set_flashdata('Error','Session Expire!!');

                redirect(base_url().'bulk/Banklogin/');

                } */

                //##############Examination Mode###########

                if ($this->session->userdata['examinfo']['optmode'] == 'ON') {

                    $mode = 'Online';

                } else {

                    $mode = 'Offline';

                }

                //set invoice details

                $getcenter = $this->master_model->getRecords('center_master', array('exam_name' => $this->session->userdata['examinfo']['excd'], 'center_code' => $this->session->userdata['examinfo']['txtCenterCode'], 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'center_delete' => '0'));

                $bulk_member_detail = array(

                    'regnumber'        => $this->session->userdata('mregnumber_applyexam'),

                    'member_name'      => $userfinalstrname,

                    'registrationtype' => 'NM',

                    'bank_emp_id'      => $user_info[0]['bank_emp_id'],

                );

                $this->master_model->insertRecord('bulk_member_detail', $bulk_member_detail, true);

                bulk_logactivity($log_title = "bulk_member_detail Non-Member user registration ", $log_message = serialize($bulk_member_detail), $this->session->userdata['institute_id']);

                $password = random_password();

                //print_r(($this->session->userdata['examinfo']['subject_arr']));exit;

                if (!empty($this->session->userdata['examinfo']['subject_arr'])) {

                    foreach ($this->session->userdata['examinfo']['subject_arr'] as $k => $v) {

                        $compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata['examinfo']['excd'], 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $this->session->userdata['examinfo']['eprid'], 'subject_code' => $k), 'subject_description');

                        $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v['venue'], 'exam_date' => $v['date'], 'session_time' => $v['session_time']));

                        $admitcard_insert_array = array(

                            'mem_exam_id'   => $this->session->userdata['examinfo']['insdet_id'],

                            'center_code'   => $getcenter[0]['center_code'],

                            'center_name'   => $getcenter[0]['center_name'],

                            'mem_type'      => $this->session->userdata('memtype'),

                            'mem_mem_no'    => $this->session->userdata('mregnumber_applyexam'),

                            'g_1'           => $gender,

                            'mam_nam_1'     => $userfinalstrname,

                            'mem_adr_1'     => $user_info[0]['address1'],

                            'mem_adr_2'     => $user_info[0]['address2'],

                            'mem_adr_3'     => $user_info[0]['address3'],

                            'mem_adr_4'     => $user_info[0]['address4'],

                            'mem_adr_5'     => $user_info[0]['district'],

                            'mem_adr_6'     => $user_info[0]['city'],

                            'mem_pin_cd'    => $user_info[0]['pincode'],

                            'state'         => $state_name,

                            'exm_cd'        => $this->session->userdata['examinfo']['excd'],

                            'exm_prd'       => $this->session->userdata['examinfo']['eprid'],

                            'sub_cd '       => $k,

                            'sub_dsc'       => $compulsory_subjects[0]['subject_description'],

                            'm_1'           => $this->session->userdata['examinfo']['medium'],

                            //'inscd'=>$institute_id,

                            //'insname'=>$institution_name,

                            'venueid'       => $get_subject_details[0]['venue_code'],

                            'venue_name'    => $get_subject_details[0]['venue_name'],

                            'venueadd1'     => $get_subject_details[0]['venue_addr1'],

                            'venueadd2'     => $get_subject_details[0]['venue_addr2'],

                            'venueadd3'     => $get_subject_details[0]['venue_addr3'],

                            'venueadd4'     => $get_subject_details[0]['venue_addr4'],

                            'venueadd5'     => $get_subject_details[0]['venue_addr5'],

                            'venpin'        => $get_subject_details[0]['venue_pincode'],

                            'pwd'           => $password,

                            'exam_date'     => $get_subject_details[0]['exam_date'],

                            'time'          => $get_subject_details[0]['session_time'],

                            'mode'          => $mode,

                            'scribe_flag'   => $this->session->userdata['examinfo']['scribe_flag'],

                            'vendor_code'   => $get_subject_details[0]['vendor_code'],

                            'remark'        => 2,

                            'record_source' => 'Bulk',

                            'free_paid_flg' => $free_paid_flag,

                            'created_on'    => date('Y-m-d H:i:s'),

                            'reapeter_flag' => $this->session->userdata['examinfo']['reapeter_flag']);

                        /*echo "<pre>";

                        print_r($admitcard_insert_array);

                        exit;*/

                        $inser_id = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);

                    }

                } else {

                    if ($this->session->userdata['examinfo']['excd'] != 101
                    	&&  $this->session->userdata('is_elearning_course') == 'n'
                        && $this->session->userdata['examinfo']['excd'] != 1010

                        && $this->session->userdata['examinfo']['excd'] != 10100

                        && $this->session->userdata['examinfo']['excd'] != 101000

                        && $this->session->userdata['examinfo']['excd'] != 1010000

                        && $this->session->userdata['examinfo']['excd'] != 10100000) /* && $this->session->userdata['examinfo']['excd']!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {

                        $this->session->set_flashdata('Error', 'Something went wrong!!');

                        //redirect(base_url().'bulk/BulkApply/comApplication');

                        redirect(base_url() . 'bulk/BulkApply/add_member');

                    }

                }

                //##--------End-----------

                //unset member info session info

                $this->session->unset_userdata('mregid_applyexam');

                $this->session->unset_userdata('mregnumber_applyexam');

                $this->session->unset_userdata('memtype');

                $this->session->set_flashdata('success', 'Application for examination has been done successfully..');

                redirect(base_url() . 'bulk/BulkApply/exam_applicantlst');

            }

        } else {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

    }

    public function Msuccess_reg()
    {

        $this->load->helper('update_image_name_helper');

        //echo '<pre>',print_r($this->session->userdata['enduserinfo']),'</pre>';

        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = $password = '';

        //check email,mobile duplication on the same time from different browser!!

        $endTime = date("H:i:s");

        $start_time = date("H:i:s", strtotime("-20 minutes", strtotime($endTime)));

        $this->db->where('Time(createdon) BETWEEN "' . $start_time . '" and "' . $endTime . '"');

        $this->db->where('email', $this->session->userdata['enduserinfo']['email']);

        $this->db->or_where('email', $this->session->userdata['enduserinfo']['mobile']);

        $check_duplication = $this->master_model->getRecords('member_registration', array('isactive' => 0));

        if (count($check_duplication) > 0) {

            //redirect(base_url().'bulk/BulkApply/accessdenied/');

        }

        if (($this->session->userdata('enduserinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //echo '<pre>',print_r($this->session->userdata['enduserinfo']),'</pre>';

        //echo '<pre>',print_r($_POST),'</pre>';exit;

        if (isset($_POST['btnSubmit'])) {

            /* $scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];

            $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];

            $idproofphoto_file = $this->session->userdata['enduserinfo']['idname']; */

            $image_error_flag = 0;

            $scannedphoto_file = $this->session->userdata['enduserinfo']['photoname'];

            $img_response = check_files_exist('./uploads/photograph/' . $scannedphoto_file); //update_image_name_helper.php

            if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

            $scannedsignaturephoto_file = $this->session->userdata['enduserinfo']['signname'];

            $img_response = check_files_exist('./uploads/scansignature/' . $scannedsignaturephoto_file); //update_image_name_helper.php

            if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

            $idproofphoto_file = $this->session->userdata['enduserinfo']['idname'];

            $img_response = check_files_exist('./uploads/idproof/' . $idproofphoto_file); //update_image_name_helper.php

            if ($img_response['flag'] != 'success') {$image_error_flag = 1;}

            if ($image_error_flag == 1) {

                $this->session->set_flashdata('error', 'Please upload valid image(s)');

                redirect(base_url() . 'bulk/BulkApply/dashboard/');

            }

            $sel_namesub = strtoupper($this->session->userdata['enduserinfo']['sel_namesub']);

            $firstname = strtoupper($this->session->userdata['enduserinfo']['firstname']);

            $middlename = strtoupper($this->session->userdata['enduserinfo']['middlename']);

            $lastname = strtoupper($this->session->userdata['enduserinfo']['lastname']);

            $addressline1 = strtoupper($this->session->userdata['enduserinfo']['addressline1']);

            $addressline2 = strtoupper($this->session->userdata['enduserinfo']['addressline2']);

            $addressline3 = strtoupper($this->session->userdata['enduserinfo']['addressline3']);

            $addressline4 = strtoupper($this->session->userdata['enduserinfo']['addressline4']);

            $district = strtoupper($this->session->userdata['enduserinfo']['district']);

            $nationality = strtoupper($this->session->userdata['enduserinfo']['city']);

            $state = $this->session->userdata['enduserinfo']['state'];

            $pincode = $this->session->userdata['enduserinfo']['pincode'];

            $dob = $this->session->userdata['enduserinfo']['dob'];

            $gender = $this->session->userdata['enduserinfo']['gender'];

            $optedu = $this->session->userdata['enduserinfo']['optedu'];

            if ($optedu == 'U') {

                $specify_qualification = $this->session->userdata['enduserinfo']['eduqual1'];

            } elseif ($optedu == 'G') {

                $specify_qualification = $this->session->userdata['enduserinfo']['eduqual2'];

            } else if ($optedu == 'P') {

                $specify_qualification = $this->session->userdata['enduserinfo']['eduqual3'];

            }

            $email = $this->session->userdata['enduserinfo']['email'];

            $stdcode = $this->session->userdata['enduserinfo']['stdcode'];

            $phone = $this->session->userdata['enduserinfo']['phone'];

            $mobile = $this->session->userdata['enduserinfo']['mobile'];

            $idproof = $this->session->userdata['enduserinfo']['idproof'];

            $idNo = $this->session->userdata['enduserinfo']['idNo'];

            $aadhar_card = $this->session->userdata['enduserinfo']['aadhar_card'];

            $optnletter = $this->session->userdata['enduserinfo']['optnletter'];

            $centerid = $this->session->userdata['enduserinfo']['selCenterName'];

            $centercode = $this->session->userdata['enduserinfo']['txtCenterCode'];

            $exmode = $this->session->userdata['enduserinfo']['optmode'];

            $bank_branch = $this->session->userdata['enduserinfo']['bank_branch'];

            $bank_designation = $this->session->userdata['enduserinfo']['bank_designation'];

            $bank_scale = $this->session->userdata['enduserinfo']['bank_scale'];

            $bank_zone = $this->session->userdata['enduserinfo']['bank_zone'];

            $bank_emp_id = $this->session->userdata['enduserinfo']['bank_emp_id'];

            $insert_info = array(

                'namesub'               => $sel_namesub,

                'firstname'             => $firstname,

                'middlename'            => $middlename,

                'lastname'              => $lastname,

                'address1'              => $addressline1,

                'address2'              => $addressline2,

                'address3'              => $addressline3,

                'address4'              => $addressline4,

                'district'              => $district,

                'city'                  => $nationality,

                'state'                 => $state,

                'pincode'               => $pincode,

                'dateofbirth'           => $dob,

                'gender'                => $gender,

                'qualification'         => $optedu,

                'specify_qualification' => $specify_qualification,

                'email'                 => $email,

                'registrationtype'      => 'NM',

                'stdcode'               => $stdcode,

                'office_phone'          => $phone,

                'mobile'                => $mobile,

                'scannedphoto'          => $scannedphoto_file,

                'scannedsignaturephoto' => $scannedsignaturephoto_file,

                'idproof'               => $idproof,

                'idNo'                  => $idNo,

                'optnletter'            => 'N',

                'declaration'           => '1',

                'idproofphoto'          => $idproofphoto_file,

                'excode'                => $this->session->userdata['enduserinfo']['excd'],

                'fee'                   => $this->session->userdata['enduserinfo']['fee'],

                'exam_period'           => $this->session->userdata['enduserinfo']['eprid'],

                'centerid'              => $centerid,

                'centercode'            => $centercode,

                'exmode'                => $exmode,

                'aadhar_card'           => $aadhar_card,

                'bank_branch'           => $bank_branch,

                'bank_designation'      => $bank_designation,

                'bank_scale'            => $bank_scale,

                'bank_zone'             => $bank_zone,

                'bank_emp_id'           => $bank_emp_id,

                'createdon'             => date('Y-m-d H:i:s'),

            );

            if ($last_id = $this->master_model->insertRecord('member_registration', $insert_info, true)) {

                $add_img_data['reg_id'] = $last_id;

                $add_img_data['photo'] = convert_img_into_base64(base_url() . 'uploads/photograph/' . $scannedphoto_file);

                $add_img_data['sign'] = convert_img_into_base64(base_url() . 'uploads/scansignature/' . $scannedsignaturephoto_file);

                $add_img_data['idproof'] = convert_img_into_base64(base_url() . 'uploads/idproof/' . $idproofphoto_file);

                $add_img_data['created_on'] = date('Y-m-d H:i:s');

                //$this->master_model->insertRecord('tbl_member_image_base64',$add_img_data,true);

                //insert member regid

                $this->master_model->updateRecord('member_registration', array('regnumber' => $last_id), array('regid' => $last_id));

                bulk_logactivity($log_title = "Non-Member user registration ", $log_message = serialize($insert_info), $this->session->userdata['institute_id']);

                $bulk_member_detail = array(

                    'regnumber'        => $last_id,

                    'member_name'      => $firstname . ' ' . $middlename . ' ' . $lastname,

                    'registrationtype' => 'NM',

                    'bank_emp_id'      => $bank_emp_id,

                );

                $this->master_model->insertRecord('bulk_member_detail', $bulk_member_detail, true);

                bulk_logactivity($log_title = "bulk_member_detail Non-Member user registration ", $log_message = serialize($bulk_member_detail), $this->session->userdata['institute_id']);

                $amount = bulk_getExamFee($this->session->userdata['enduserinfo']['txtCenterCode'], $this->session->userdata['enduserinfo']['eprid'], $this->session->userdata['enduserinfo']['excd'], $this->session->userdata['enduserinfo']['grp_code'], 'NM', $this->session->userdata['enduserinfo']['elearning_flag'], $this->session->userdata['enduserinfo']['discount_flag']);

                //##------------get app_category and base_fee

                $fee_result = bulk_getFee_Appcat($this->session->userdata['enduserinfo']['txtCenterCode'], $this->session->userdata['enduserinfo']['eprid'], $this->session->userdata['enduserinfo']['excd'], $this->session->userdata['enduserinfo']['grp_code'], 'NM', $this->session->userdata['enduserinfo']['elearning_flag'], $this->session->userdata['enduserinfo']['discount_flag']);

                $inser_exam_array = array('regnumber' => $last_id,

                    'member_type'                         => 'NM',

                    'app_category'                        => $fee_result['grp_code'],

                    'base_fee'                            => $fee_result['base_fee'],

                    'original_base_fee'                   => $fee_result['original_base_fee'],

                    'bulk_discount_flg'                   => $fee_result['bulk_discount_flg'],

                    'discount_percent'                    => $fee_result['discount_percent'],

                    'discount_amount'                     => $fee_result['discount_amount'],

                    'calculate_discount'                  => $fee_result['calculate_discount'],

                    'taken_discount'                      => $fee_result['taken_discount'],

                    'exam_code'                           => $this->session->userdata['enduserinfo']['excd'],

                    'exam_mode'                           => $this->session->userdata['enduserinfo']['optmode'],

                    'exam_medium'                         => $this->session->userdata['enduserinfo']['medium'],

                    'exam_period'                         => $this->session->userdata['enduserinfo']['eprid'],

                    'exam_center_code'                    => $this->session->userdata['enduserinfo']['txtCenterCode'],

                    'exam_fee'                            => $amount,

                    'scribe_flag'                         => $this->session->userdata['enduserinfo']['scribe_flag'],

                    'created_on'                          => date('y-m-d H:i:s'),

                    'pay_status'                          => '2',

                    'institute_id'                        => $this->session->userdata['institute_id'],

                    'bulk_isdelete'                       => '0',

                    'examination_date'                    => $this->session->userdata['enduserinfo']['special_exam_date'],

                    'elearning_flag'                      => $this->session->userdata['enduserinfo']['elearning_flag'],

                    'reapeter_flag'                       => $this->session->userdata['enduserinfo']['reapeter_flag'],

                );

                if ($exam_last_id = $this->master_model->insertRecord('member_exam', $inser_exam_array, true)) {

                    //##----------prepare user name

                    $username = $firstname . ' ' . $middlename . ' ' . $lastname;

                    $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);

                    //##----------set invoice details

                    $getcenter = $this->master_model->getRecords('center_master', array('exam_name' => $this->session->userdata['enduserinfo']['excd'], 'center_code' => $this->session->userdata['enduserinfo']['txtCenterCode'], 'exam_period' => $this->session->userdata['enduserinfo']['eprid'], 'center_delete' => '0'));

                    //##---------check Gender

                    if ($gender == 'male') {$gender = 'M';} else { $gender = 'F';}

                    //##---------Institute Information

                    if (!empty($this->session->userdata['institute_id']) && ($this->session->userdata['institute_name'])) {

                        $institute_id = $this->session->userdata['institute_id'];

                        $institution_name = $this->session->userdata['institute_name'];

                    }

                    //##----------get state name

                    $states = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));

                    $state_name = '';

                    if (count($states) > 0) {

                        $state_name = $states[0]['state_name'];

                    }

                    //##---------get mode

                    if ($this->session->userdata['enduserinfo']['optmode'] == 'ON') {$mode = 'Online';} else { $mode = 'Offline';}

                    $password = random_password();

                    //print_r(($this->session->userdata['enduserinfo']['subject_arr']));exit;

                    if (!empty($this->session->userdata['enduserinfo']['subject_arr'])) {

                        foreach ($this->session->userdata['enduserinfo']['subject_arr'] as $k => $v) {

                            $compulsory_subjects = $this->master_model->getRecords('subject_master', array('exam_code' => $this->session->userdata['enduserinfo']['excd'], 'subject_delete' => '0', 'group_code' => 'C', 'exam_period' => $this->session->userdata['enduserinfo']['eprid'], 'subject_code' => $k), 'subject_description');

                            $get_subject_details = $this->master_model->getRecords('venue_master', array('venue_code' => $v['venue'], 'exam_date' => $v['date'], 'session_time' => $v['session_time']));

                            $admitcard_insert_array = array('mem_exam_id' => $exam_last_id,

                                'center_code'                                 => $getcenter[0]['center_code'],

                                'center_name'                                 => $getcenter[0]['center_name'],

                                'mem_type'                                    => 'NM',

                                'mem_mem_no'                                  => $last_id,

                                'g_1'                                         => $gender,

                                'mam_nam_1'                                   => $userfinalstrname,

                                'mem_adr_1'                                   => $addressline1,

                                'mem_adr_2'                                   => $addressline2,

                                'mem_adr_3'                                   => $addressline3,

                                'mem_adr_4'                                   => $addressline4,

                                'mem_adr_5'                                   => $district,

                                'mem_adr_6'                                   => $nationality,

                                'mem_pin_cd'                                  => $pincode,

                                'state'                                       => $state_name,

                                'exm_cd'                                      => $this->session->userdata['enduserinfo']['excd'],

                                'exm_prd'                                     => $this->session->userdata['enduserinfo']['eprid'],

                                'sub_cd '                                     => $k,

                                'sub_dsc'                                     => $compulsory_subjects[0]['subject_description'],

                                'm_1'                                         => $this->session->userdata['enduserinfo']['medium'],

                                //'inscd'=>$institute_id,

                                //'insname'=>$institution_name,

                                'venueid'                                     => $get_subject_details[0]['venue_code'],

                                'venue_name'                                  => $get_subject_details[0]['venue_name'],

                                'venueadd1'                                   => $get_subject_details[0]['venue_addr1'],

                                'venueadd2'                                   => $get_subject_details[0]['venue_addr2'],

                                'venueadd3'                                   => $get_subject_details[0]['venue_addr3'],

                                'venueadd4'                                   => $get_subject_details[0]['venue_addr4'],

                                'venueadd5'                                   => $get_subject_details[0]['venue_addr5'],

                                'venpin'                                      => $get_subject_details[0]['venue_pincode'],

                                'pwd'                                         => $password,

                                'exam_date'                                   => $get_subject_details[0]['exam_date'],

                                'time'                                        => $get_subject_details[0]['session_time'],

                                'mode'                                        => $mode,

                                'scribe_flag'                                 => $this->session->userdata['enduserinfo']['scribe_flag'],

                                'vendor_code'                                 => $get_subject_details[0]['vendor_code'],

                                'remark'                                      => 2,

                                'record_source'                               => 'Bulk',

                                'created_on'                                  => date('Y-m-d H:i:s'),

                                'reapeter_flag'                               => $this->session->userdata['enduserinfo']['reapeter_flag']);

                            $inser_id = $this->master_model->insertRecord('admit_card_details', $admitcard_insert_array);

                        }

                    } else {

                        if ($this->session->userdata['enduserinfo']['excd'] != 101
                        		&&  $this->session->userdata('is_elearning_course') == 'n'
                            && $this->session->userdata['enduserinfo']['excd'] != 1010

                            && $this->session->userdata['enduserinfo']['excd'] != 10100

                            && $this->session->userdata['enduserinfo']['excd'] != 101000

                            && $this->session->userdata['enduserinfo']['excd'] != 1010000

                            && $this->session->userdata['enduserinfo']['excd'] != 10100000) /* && $this->session->userdata['enduserinfo']['excd']!=996 COMMENTED BY SAGAR ON 21-07-2021 */ {

                            $this->session->set_flashdata('Error', 'Something went wrong!!');

                            //redirect(base_url().'bulk/BulkApply/comApplication');

                            redirect(base_url() . 'bulk/BulkApply/add_member');

                        }

                    }

                }

            }

            $this->session->set_flashdata('success', 'Application for examination has been done successfully..');

            redirect(base_url() . 'bulk/BulkApply/exam_applicantlst');

        } else {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

    }

    // reload captcha functionality  -(Tejasvi)

    public function generatecaptchaajax()
    {

        $this->load->helper('captcha');

        $this->session->unset_userdata("nonmemregcaptcha_bulk");

        $this->session->set_userdata("nonmemregcaptcha_bulk", rand(1, 100000));

        $vals = array('img_path' => './uploads/applications/', 'img_url' => base_url() . 'uploads/applications/',

        );

        $cap = create_captcha($vals);

        $data = $cap['image'];

        $_SESSION["nonmemregcaptcha_bulk"] = $cap['word'];

        echo $data;

    }

    //validate captcha -(Tejasvi)

    public function ajax_check_captcha()
    {

        $code = $_POST['code'];

        // check if captcha is set -

        if ($code == '' || $_SESSION["nonmemregcaptcha_bulk"] != $code) {

            $this->form_validation->set_message('ajax_check_captcha', 'Invalid %s.');

            //$this->session->set_userdata("regcaptcha", rand(1, 100000));

            echo 'false';

        } else if ($_SESSION["nonmemregcaptcha_bulk"] == $code) {

            //$this->session->unset_userdata("nonmemlogincaptcha");

            // $this->session->set_userdata("mycaptcha", rand(1,100000));

            echo 'true';

        }

    }

    //call back for check captcha server side

    public function check_captcha_userreg($code)
    {

        if (isset($code)) {

            if ($code == '' || $_SESSION["nonmemregcaptcha_bulk"] != $code) {

                $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');

                //$this->session->set_userdata("regcaptcha", rand(1,100000));

                return false;

            }

            if ($_SESSION["nonmemregcaptcha_bulk"] == $code) {

                return true;

            }

        } else {

            $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');

            //$this->session->set_userdata("regcaptcha", rand(1,100000));

            return false;

        }

    }

    //check mail alredy exist or not

    public function emailduplication()
    {

        $email = $_POST['email'];

        if ($email != "") {

            $where = "(registrationtype='NM')";

            $this->db->where($where);

            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'isdeleted' => '0', 'isactive' => '1')); //,'isactive'=>'1'

            //echo $this->db->last_query();

            if ($this->session->userdata('examcode') == 1015 && $this->session->userdata['institute_id'] == 10004) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 994 && $this->session->userdata('institute_id') == 2851) {
                $prev_count = 0;
            }

            if ($this->session->userdata('examcode') == 101 && $this->session->userdata['institute_id'] == 9994) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10012) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10014) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10015) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10022) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10016) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10018) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 9998) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10039) {

                $prev_count = 0;

            }

            if ($prev_count == 0) {

                $data_arr = array('ans' => 'ok');

                echo json_encode($data_arr);} else {

                $str = 'You are already registered and the email ID is in use. If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';

                $data_arr = array('ans' => 'exists', 'output' => $str);

                echo json_encode($data_arr);

            }

        } else {

            echo 'error';

        }

    }

    ##---------check mobile number alredy exist or not for non member-----------##

    public function mobileduplication()
    {

        $mobile = $_POST['mobile'];

        if ($mobile != "") {

            $where = "(registrationtype='NM')";

            $this->db->where($where);

            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'isdeleted' => '0', 'isactive' => '1')); //,'isactive'=>'1'

            //echo $this->db->last_query();

            if ($this->session->userdata('examcode') == 1015 && $this->session->userdata['institute_id'] == 10004) {

                $prev_count = 0;

            }

            if ($prev_count == 0) {

                $data_arr = array('ans' => 'ok');

                echo json_encode($data_arr);

            } else {

                $str = 'You are already registered and the Mobile no is in use. If you have registered under non-member category for any other exam, please use the same registration number for applying for other examinations also.';

                $data_arr = array('ans' => 'exists', 'output' => $str);

                echo json_encode($data_arr);

            }

        } else {

            echo 'error';

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

    // ##---------Thank you page for user -----------##

    public function acknowledge()
    {

        $kycflag = 0;

        //$this->chk_session->checkphoto();

        $data = array('middle_content' => 'profile_thankyou', 'application_number' => $this->session->userdata('regnumber'), 'password' => base64_decode($this->session->userdata('password')));

        $this->load->view('common_view', $data);

    }

    //##---------accessdenied page----------##

    public function accessdenied()
    {

        $message = '<div style="color:#F00">You are not eligible to register for the selected examination. For details refer the link <strong>"Important Notice of Diploma and Certificate Examination syllabus" </strong> at the institutes website</div>';

        $data = array('middle_content' => 'bulk/bulk-not_eligible', 'check_eligibility' => $message);

        $this->load->view('bulk/bulk_common_view', $data);

    }

    ##-------------- check qualify exam pass/fail

    public function checkqualify($qualify_id = null, $examcode = null, $part_no = null)
    {

        //echo $examcode;exit;

        if ($examcode == null || $examcode == '') {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $flag        = 0;
        $exam_status = 1;

        $check_qualify = array();

        $check_qualify_exam_name = $this->master_model->getRecords('exam_master', array('exam_code' => $qualify_id), 'description');

        if (count($check_qualify_exam_name) > 0) {

            $message = 'you have not cleared qualifying examination - <strong>' . $check_qualify_exam_name[0]['description'] . '</strong>.';

        } else {

            $message = 'you have not cleared qualifying examination.';

        }

        $check_qualify_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $examcode));

        //Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

        $this->db->where('bulk_exam_activation_master.institute_code', $this->session->userdata('institute_id'));

        $check_qualify_exam_eligibility = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $qualify_id, 'part_no' => $part_no, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('mregnumber_applyexam')), 'exam_status,remark');

        if (count($check_qualify_exam_eligibility) > 0) {

            foreach ($check_qualify_exam_eligibility as $check_exam_status) {

                if ($check_exam_status['exam_status'] == 'F' || $check_exam_status['exam_status'] == 'V' || $check_exam_status['exam_status'] == 'D') {

                    $exam_status = 0;

                }

            }

            //if($check_qualify_exam_eligibility[0]['exam_status']=='P')

            if ($exam_status == 1) {

                //check eligibility for applied exam(This are the exam who  have pre qualifying exam)

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                $check_eligibility_for_applied_exam = $this->master_model->getRecords('eligible_master', array('eligible_master.exam_code' => $examcode, 'member_type' => $this->session->userdata('memtype'), 'member_no' => $this->session->userdata('mregnumber_applyexam')));

                if (count($check_eligibility_for_applied_exam) > 0) {

                    foreach ($check_eligibility_for_applied_exam as $check_exam_status) {

                        if ($check_exam_status['exam_status'] == 'F') {

                            $exam_status = 0;

                        }

                    }

                    /*if($check_eligibility_for_applied_exam[0]['exam_status']=='P' || $check_eligibility_for_applied_exam[0]['exam_status']=='p' ||$check_eligibility_for_applied_exam[0]['exam_status']=='D' || $check_eligibility_for_applied_exam[0]['exam_status']=='d' || $check_eligibility_for_applied_exam[0]['exam_status']=='V' || $check_eligibility_for_applied_exam[0]['exam_status']=='v' || $check_eligibility_for_applied_exam[0]['exam_status']=='B' || $check_eligibility_for_applied_exam[0]['exam_status']=='b')*/

                    if ($exam_status == 1) {

                        $flag = 0;

                        if (base64_decode($this->input->get('Extype')) == '3') {

                            $message = 'You have already cleared this subject as separate  Examination. Hence you cannot apply for the same.';

                            //$message='You have already cleared this subject under <strong>'.$check_qualify_exam_name[0]['description'].'</strong> Elective Examination. Hence you cannot apply for the same';

                        } else {

                            $message = $check_eligibility_for_applied_exam[0]['remark'];

                        }

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

                    //else if($check_eligibility_for_applied_exam[0]['exam_status']=='F'  || $check_eligibility_for_applied_exam[0]['exam_status']=='R')

                    else if ($exam_status == 0) {

                        $flag = 1;

                        $check_qualify = array('flag' => $flag, 'message' => $message);

                        return $check_qualify;

                    }

                } else {

                    //CAIIB apply directly

                    $flag = 1;

                    $check_qualify = array('flag' => $flag, 'message' => $message);

                    return $check_qualify;

                }

            } else {

                $flag = 0;

                $message = $check_qualify_exam_eligibility[0]['remark'];

                $check_qualify = array('flag' => $flag, 'message' => $message);

                return $check_qualify;

            }

        } else {

            //show message with pre-qualifying exam name if pre-qualify exam yet to not apply.

            $flag = 0;

            if ($qualify_id) {

                $get_exam = $this->master_model->getRecords('exam_master', array('exam_code' => $qualify_id), 'description');

                if (count($get_exam) > 0) {

                    $qualification = 0;

                    $user_info = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('mregnumber_applyexam')), 'specify_qualification');

                    if (count($user_info) > 0) {

                        $qualification = $user_info[0]['specify_qualification'];

                    }

                    if (base64_decode($this->input->get('Extype')) == '3') {

                        if ($qualification == 91 && $examcode == 19) {

                            $flag = 1;

                        } else {

                            //$message='you have not cleared qualifying examination - <strong>'.$get_exam[0]['description'].'</strong>.';

                            $message = 'You are not eligible to apply for this exam, you should have CS qualification.';

                        }

                    } else {

                        if ($qualification == 91 && $examcode == 19) {

                            $flag = 1;

                        } else {

                            $message = 'You are not eligible to apply for this exam, you should have CS qualification.';

                            //$message='You have not cleared  <strong>'.$get_exam[0]['description'].'</strong> examination, hence you cannot apply for <strong> '.$check_qualify_exam[0]['description'].'</strong>.';

                        }

                    }

                }

            }

            $check_qualify = array('flag' => $flag, 'message' => $message);

            return $check_qualify;

        }

    }

    //check user already exam apply or not

    public function examapplied($regnumber = null, $exam_code = null)
    {

        //check where exam alredy apply or not

        $today_date = date('Y-m-d');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

        //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');

        $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

        $this->db->where('exam_master.elg_mem_nm', 'Y');

        $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

        //$this->db->where('pay_status','0');

        $this->db->where('bulk_isdelete', '0');

        $this->db->where('institute_id!=', '');

        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $regnumber));

        //check for normal exam applied or not

        if (count($applied_exam_info) <= 0) {

            $today_date = date('Y-m-d');

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

            //$this->db->join('exam_activation_master','exam_activation_master.exam_code=member_exam.exam_code');

            $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

            $this->db->where('exam_master.elg_mem_nm', 'Y');

            $this->db->where('pay_status', '1');

            $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $regnumber));
            // print_r($applied_exam_info);die;

        }

        //echo $this->db->last_query();exit;

        return count($applied_exam_info);

    }

    //check user already exam apply or not - Special exam check

    public function check_examapplied($regnumber = null, $exam_code = null, $selected_date = null)
    {

        //check where exam alredy apply or not

        if ($regnumber != null && $exam_code != null && $selected_date != null) {

            $check_applied_flag = 0;

            $today_date = date('Y-m-d');

            $this->db->select('member_exam.examination_date,member_exam.exam_code');

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

            $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

            $this->db->where('exam_master.elg_mem_nm', 'Y');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            //$this->db->where('pay_status','1');

            $this->db->where('bulk_isdelete', '0');

            $this->db->where('institute_id!=', '');

            $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'examination_date !=' => ''));

            //check for normal apply

            if (count($applied_exam_info) <= 0) {

                $today_date = date('Y-m-d');

                $this->db->select('member_exam.examination_date,member_exam.exam_code');

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

                $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

                $this->db->where('exam_master.elg_mem_nm', 'Y');

                $this->db->where('pay_status', '1');

                $applied_exam_info = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'examination_date !=' => ''));

            }

            if (count($applied_exam_info) > 0) {

                foreach ($applied_exam_info as $row) {

                    if ($row['examination_date'] == $selected_date) {

                        $check_applied_flag = 1;

                        $message = $this->get_alredy_applied_examname_special($regnumber, $exam_code, $selected_date);

                        $this->session->set_flashdata('error', $message);

                        redirect(base_url() . 'SpecialExamNm/comApplication');

                    }

                }

            }

        }

    }

    //check whether applied exam date fall in same date of other exam date

    public function examdate($regnumber = null, $exam_code = null)
    {

        $flag = 0;

        $today_date = date('Y-m-d');

        $applied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exam_code, 'exam_date >=' => $today_date, 'subject_delete' => '0'));

        if (count($applied_exam_date) > 0) {

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->where('institute_id!=', '');

            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'bulk_isdelete' => '0'), 'member_exam.exam_code'); //'pay_status'=>'1'

            #---- checking normal applied applied ------#

            if (count($getapplied_exam_code) <= 0) {

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '1'), 'member_exam.exam_code');
                // print_r($getapplied_exam_code);die;

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

    ##--------- get applied exam name which is fall on same date

    public function get_alredy_applied_examname($regnumber = null, $exam_code = null)
    {

        $flag = 0;

        $msg = '';

        $today_date = date('Y-m-d');

        $this->db->select('subject_master.*,exam_master.description');

        $this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');

        $applied_exam_date = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => $exam_code, 'exam_date >=' => $today_date, 'subject_delete' => '0'));

        if (count($applied_exam_date) > 0) {

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'bulk_isdelete' => '0'), 'member_exam.exam_code,exam_master.description'); //'pay_status'=>'1'

            #--------checking normal applied-------#

            if (count($getapplied_exam_code) <= 0) {

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '1'), 'member_exam.exam_code,exam_master.description');

            }

            if (count($getapplied_exam_code) > 0) {

                foreach ($getapplied_exam_code as $exist_ex_code) {

                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));

                    if (count($getapplied_exam_date) > 0) {

                        foreach ($getapplied_exam_date as $exist_ex_date) {

                            foreach ($applied_exam_date as $sel_ex_date) {

                                if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {

                                    $msg = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";

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

    //get applied exam name which is fall on same date(Prafull)

    public function get_alredy_applied_examname_special($regnumber = null, $exam_code = null, $selected_date = null)
    {

        $flag = 0;

        $msg = '';

        $today_date = date('Y-m-d');

        $this->db->select('subject_master.*,exam_master.description');

        $this->db->join('exam_master', 'exam_master.exam_code=subject_master.exam_code');

        $applied_exam_date = $this->master_model->getRecords('subject_master', array('subject_master.exam_code' => $exam_code, 'exam_date >=' => $today_date, 'subject_delete' => '0'));

        if (count($applied_exam_date) > 0) {

            $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

            $this->db->where("bulk_exam_activation_master.institute_code", $this->session->userdata('institute_id'));

            $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

            $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'bulk_isdelete' => '0', 'examination_date' => $selected_date), 'member_exam.exam_code,exam_master.description');

            #--------checking normal applied-------#

            if (count($getapplied_exam_code) <= 0) {

                $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

                $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

                $getapplied_exam_code = $this->master_model->getRecords('member_exam', array('regnumber' => $regnumber, 'pay_status' => '1'), 'member_exam.exam_code,exam_master.description');

            }

            if (count($getapplied_exam_code) > 0) {

                foreach ($getapplied_exam_code as $exist_ex_code) {

                    $getapplied_exam_date = $this->master_model->getRecords('subject_master', array('exam_code' => $exist_ex_code['exam_code'], 'exam_date >=' => $today_date, 'subject_delete' => '0'));

                    if (count($getapplied_exam_date) > 0) {

                        foreach ($getapplied_exam_date as $exist_ex_date) {

                            foreach ($applied_exam_date as $sel_ex_date) {

                                if ($sel_ex_date['exam_date'] == $exist_ex_date['exam_date']) {

                                    $msg = "You have already applied for <strong>" . $exist_ex_code['description'] . "</strong> falling on same day, So you can not apply for <strong>" . $sel_ex_date['description'] . "</strong> examination.";

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

    // Remove an item from string

    public function removeFromString($str, $item)
    {

        $parts = explode(',', $str);

        while (($i = array_search($item, $parts)) !== false) {

            unset($parts[$i]);

        }

        return implode(',', $parts);

    }

    // ##---------Edit Images-----------##

    public function editimages()
    {

        $kyc_update_data = array();

        $kyc_edit_flag = 0;

        $flag = 1;

        $member_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'scannedphoto,scannedsignaturephoto,idproofphoto');

        $applicationNo = $this->session->userdata('regnumber');

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

                $prev_edited_on = '';

                $prev_photo_flg = "N";

                $prev_signature_flg = "N";

                $prev_id_flg = "N";

                $prev_edited_on_qry = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'DATE(images_editedon) images_editedon,photo_flg,signature_flg,id_flg');

                if (count($prev_edited_on_qry)) {

                    $prev_edited_on = $prev_edited_on_qry[0]['images_editedon'];

                    $prev_photo_flg = $prev_edited_on_qry[0]['photo_flg'];

                    $prev_signature_flg = $prev_edited_on_qry[0]['signature_flg'];

                    $prev_id_flg = $prev_edited_on_qry[0]['id_flg'];

                    if ($prev_edited_on != date('Y-m-d')) {

                        $this->master_model->updateRecord('member_registration', array('photo_flg' => 'N', 'signature_flg' => 'N', 'id_flg' => 'N'), array('regid' => $this->session->userdata('regid')));

                    }

                }

                $date = date('Y-m-d h:i:s');

                $scannedphoto_file = '';

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$photo_flg = 'N';} else { $photo_flg = $prev_photo_flg;}

                $edited = '';

                if (isset($_FILES['scannedphoto']['name']) && $_FILES['scannedphoto']['name'] != '') {

                    @unlink('uploads/photograph/' . $member_info[0]['scannedphoto']);

                    $path = "./uploads/photograph";

                    //$new_filename = 'photo_'.strtotime($date).rand(1,99999);

                    $new_filename = 'p_' . $applicationNo;

                    $uploadData = upload_file('scannedphoto', $path, $new_filename, '', '', true);

                    if ($uploadData) {

                        $kyc_edit_flag = 1;

                        $kyc_update_data['edited_mem_photo'] = 1;

                        //Overwrites file so no need to unlink

                        //@unlink('uploads/photograph/'.$member_info[0]['scannedphoto']);

                        $scannedphoto_file = $uploadData['file_name'];

                        $photo_flg = 'Y';

                        $edited .= 'PHOTO || ';

                    } else {

                        $flag = 0;

                        $scannedphoto_file = $this->input->post('scannedphoto1_hidd');

                    }

                } else {

                    $scannedphoto_file = $this->input->post('scannedphoto1_hidd');

                }

                // Upload DOB Proof

                $scannedsignaturephoto_file = '';

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$signature_flg = 'N';} else { $signature_flg = $prev_signature_flg;}

                if ($_FILES['scannedsignaturephoto']['name'] != '') {

                    @unlink('uploads/photograph/' . $member_info[0]['scannedsignaturephoto']);

                    $path = "./uploads/scansignature";

                    //$new_filename = 'sign_'.strtotime($date).rand(1,99999);

                    $new_filename = 's_' . $applicationNo;

                    $uploadData = upload_file('scannedsignaturephoto', $path, $new_filename, '', '', true);

                    if ($uploadData) {

                        $kyc_edit_flag = 1;

                        $kyc_update_data['edited_mem_sign'] = 1;

                        $scannedsignaturephoto_file = $uploadData['file_name'];

                        $signature_flg = 'Y';

                        $edited .= 'SIGNATURE || ';

                    } else {

                        $flag = 0;

                        $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');

                    }

                } else {

                    $scannedsignaturephoto_file = $this->input->post('scannedsignaturephoto1_hidd');

                }

                // Upload Education Certificate

                $idproofphoto_file = '';

                if ($prev_edited_on != '' && $prev_edited_on != date('Y-m-d')) {$id_flg = 'N';} else { $id_flg = $prev_id_flg;}

                if ($_FILES['idproofphoto']['name'] != '') {

                    @unlink('uploads/photograph/' . $member_info[0]['idproofphoto']);

                    $path = "./uploads/idproof";

                    //$new_filename = 'idproof_'.strtotime($date).rand(1,99999);

                    $new_filename = 'pr_' . $applicationNo;

                    $uploadData = upload_file('idproofphoto', $path, $new_filename, '', '', true);

                    if ($uploadData) {

                        $kyc_edit_flag = 1;

                        $kyc_update_data['edited_mem_proof'] = 1;

                        $idproofphoto_file = $uploadData['file_name'];

                        $id_flg = 'Y';

                        $edited .= 'PROOF || ';

                    } else {

                        $flag = 0;

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

                    $update_info = array(

                        'scannedphoto'          => $scannedphoto_file,

                        'scannedsignaturephoto' => $scannedsignaturephoto_file,

                        'idproofphoto'          => $idproofphoto_file,

                        'images_editedon'       => date('Y-m-d H:i:s'),

                        'images_editedby'       => 'Candidate',

                        'photo_flg'             => $photo_flg,

                        'signature_flg'         => $signature_flg,

                        'id_flg'                => $id_flg,

                        'kyc_edit'              => $kyc_edit_flag,

                        'kyc_status'            => '0',

                    );

                    //$personalInfo = filter($personal_info);

                    if ($this->master_model->updateRecord('member_registration', $update_info, array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')))) {

                        $desc['updated_data'] = $update_info;

                        $desc['old_data'] = $member_info[0];

                        //logactivity($log_title ="Member Edit Images", $log_message = serialize($desc));

                        /* User Log Activities : Bhushan */

                        $log_title = "Member Edit Images";

                        $log_message = serialize($desc);

                        $rId = $this->session->userdata('regid');

                        $regNo = $this->session->userdata('regnumber');

                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        /* Close User Log Actitives */

                        $finalStr = '';

                        if ($edited != '') {

                            $edit_data = trim($edited);

                            $finalStr = rtrim($edit_data, "||");

                        }

                        log_profile_user($log_title = "Profile updated successfully", $finalStr, 'image', $this->session->userdata('regid'), $this->session->userdata('regnumber'));

                        if ($kyc_edit_flag == 1) {

                            $kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('regnumber')), '', array('kyc_id' => 'DESC'), '0', '1');

                            if (count($kycmemdetails) > 0) {

                                $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');

                                $kyc_update_data['kyc_state'] = '2';

                                $kyc_update_data['kyc_status'] = '0';

                                $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));

                                $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));

                                $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array('list_type' => 'New'));

                                if (count($check_duplicate_entry) > 0) {

                                    foreach ($check_duplicate_entry as $row) {

                                        $allotted_member_id = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));

                                        $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));

                                        $admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);

                                        $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));

                                    }

                                }

                                //$kyc_update_data=array('user_edited_date'=>date('Y-m-d'),'kyc_state'=>2,'kyc_status'=>'0');

                                if ($kycmemdetails[0]['kyc_status'] == '0') {

                                    $this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));

                                    $this->KYC_Log_model->create_log('kyc member edited images', '', '', $this->session->userdata('regnumber'), serialize($desc));

                                }

                                //check membership count

                                $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));

                                if (count($check_membership_cnt) > 0) {

                                    //$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));

                                    /* update dowanload count 8-8-2017 */

                                    $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('regnumber')));

                                    /* Close update dowanload count */

                                    /* User Log Activities : Pooja */

                                    $uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'regid,regnumber');

                                    $user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));

                                    $log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];

                                    $log_message = serialize($user_info);

                                    $rId = $uerlog[0]['regid'];

                                    $regNo = $this->session->userdata('regnumber');

                                    storedUserActivity($log_title, $log_message, $rId, $regNo);

                                    /* Close User Log Actitives */

                                }

                            }

                            //echo $this->db->last_query();exit;

                            //change by pooja godse for  memebersgip id card  dowanload count reset

                            //check membership count

                            $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));

                            if (count($check_membership_cnt) > 0) {

                                //$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));

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

                            //logactivity($log_title = "kyc member edited images id : ".$this->session->userdata('regid'), $description = serialize($desc));

                            /* User Log Activities : Bhushan */

                            $log_title = "kyc member edited images id : " . $this->session->userdata('regid');

                            $log_message = serialize($desc);

                            $rId = $this->session->userdata('regid');

                            $regNo = $this->session->userdata('regnumber');

                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            /* Close User Log Actitives */

                        }

                        //if(!is_file(get_img_name($this->session->userdata('regnumber'),'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_userdata($this->session->userdata('regnumber')))

                        // if(!is_file(get_img_name($this->session->userdata('regnumber'),'s')) || !is_file(get_img_name($this->session->userdata('regnumber'),'p')) || validate_ordinary_userdata($this->session->userdata('regnumber')))

                        // ABove if commecnted by vishal and added below if on 29 dec 2022

                        if (validate_userdata($this->session->userdata('mregnumber_applyexam')) ||
                            !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 's')) ||
                            !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'p')) ||
                            !is_file(get_img_name($this->session->userdata('mregnumber_applyexam'), 'pr'))) {

                            $this->session->set_flashdata('error', 'Please update your profile!!');

                            redirect(base_url('bulk/BulkApply/profile/'));

                        }

                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));

                        if (count($emailerstr) > 0) {

                            $member_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid')), 'email');

                            $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);

                            $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);

                            $info_arr = array(

                                'to'      => $member_info[0]['email'],

                                'from'    => $emailerstr[0]['from'],

                                'subject' => $emailerstr[0]['subject'],

                                'message' => $final_str,

                            );

                            if ($this->Emailsending->mailsend($info_arr)) {

                                //$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');

                                redirect(base_url('bulk/BulkApply/acknowledge/'));

                            } else {

                                $this->session->set_flashdata('error', 'Error while sending email !!');

                                redirect(base_url('bulk/BulkApply/editimages/'));

                            }

                        } else {

                            $this->session->set_flashdata('error', 'Error while sending email !!');

                            redirect(base_url('bulk/BulkApply/editimages/'));

                        }

                    } else {

                        $desc['updated_data'] = $update_info;

                        $desc['old_data'] = $member_info[0];

                        //logactivity($log_title ="Error While Member Images Edit", $log_message = serialize($desc));

                        /* User Log Activities : Bhushan */

                        $log_title = "Error While Member Images Edit";

                        $log_message = serialize($desc);

                        $rId = $this->session->userdata('regid');

                        $regNo = $this->session->userdata('regnumber');

                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        /* Close User Log Actitives */

                        $this->session->set_flashdata('error', 'Error While Adding Your Information !!');

                        $last = $this->uri->total_segments();

                        $post = $this->uri->segment($last);

                        redirect(base_url() . $post);

                    }

                } else {

                    $this->session->set_flashdata('error', 'Please follow the instruction while uploading image(s)!!');

                    redirect(base_url('bulk/BulkApply/editimages/'));

                }

            } else {

                $data['validation_errors'] = validation_errors();

            }

        }

        $data = array('middle_content' => 'member_edit_images', 'member_info' => $member_info);

        $this->load->view('common_view', $data);

    }

    //##---------check mail alredy exist or not on edit page-----------##

    public function editemailduplication()
    {

        $email = $_POST['email'];

        $regid = $_POST['regid'];

        if ($email != "" && $regid != "") {

            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

            if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}

        } else {

            echo 'error';

        }

    }

    //##---------check mobile number alredy exist or not on edit page-----------##

    public function editmobile()
    {

        $mobile = $_POST['mobile'];

        $regid = $_POST['regid'];

        if ($mobile != "" && $regid != "") {

            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $regid, 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

            if ($prev_count == 0) {echo 'ok';} else {echo 'exists';}

        } else {

            echo 'error';

        }

    }

    ##------------------ chenge password ()---------------##

    public function changepass()
    {

        $this->chk_session->checkphoto();

        $data['error'] = '';

        if (isset($_POST['btn_password'])) {

            $this->form_validation->set_rules('current_pass', 'Current Password', 'required|xss_clean');

            $this->form_validation->set_rules('txtnpwd', 'New Password', 'required|xss_clean');

            $this->form_validation->set_rules('txtrpwd', 'Re-type new password', 'required|xss_clean|matches[txtnpwd]');

            if ($this->form_validation->run()) {

                $current_pass = $this->input->post('current_pass');

                $new_pass = $this->input->post('txtnpwd');

                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

                $key = $this->config->item('pass_key');

                $aes = new CryptAES();

                $aes->set_key(base64_decode($key));

                $aes->require_pkcs5();

                $encPass = $aes->encrypt($new_pass);

                $curr_encrypass = $aes->encrypt(trim($current_pass));

                $row = $this->master_model->getRecordCount('member_registration', array('usrpassword' => $curr_encrypass, 'regid' => $this->session->userdata('regid')));

                if ($row == 0) {

                    $this->session->set_flashdata('error', 'Current Password is Wrong.');

                    redirect(base_url() . 'bulk/BulkApply/changepass/');

                } else {

                    if ($current_pass != $new_pass) {

                        $input_array = array('usrpassword' => $encPass);

                        $this->master_model->updateRecord('member_registration', $input_array, array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')));

                        //logactivity($log_title ="Changed password Member ", $log_message = serialize($input_array));

                        /* User Log Activities : Bhushan */

                        $log_title = "Changed password Member";

                        $log_message = serialize($input_array);

                        $rId = $this->session->userdata('regid');

                        $regNo = $this->session->userdata('regnumber');

                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        /* Close User Log Actitives */

                        $this->session->unset_userdata('password');

                        $this->session->set_userdata("password", base64_encode($new_pass));

                        $this->session->set_flashdata('success', 'Password Changed successfully.');

                        redirect(base_url() . 'bulk/BulkApply/changepass/');

                    } else {

                        $this->session->set_flashdata('error', 'Current password and new password cannot be same..');

                        redirect(base_url() . 'bulk/BulkApply/changepass/');

                    }

                }

            }

        }

        $data = array('middle_content' => 'change_pass', $data);

        $this->load->view('common_view', $data);

    }

    ##---------check pincode/zipcode alredy exist or not -----------##

    public function checkpin()
    {

        $statecode = $_POST['statecode'];

        $pincode = $_POST['pincode'];

        if ($statecode != "") {

            $this->db->where("$pincode BETWEEN start_pin AND end_pin");

            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));

            //echo $this->db->last_query();

            if ($prev_count == 0) {echo 'false';} else {echo 'true';}

        } else {

            echo 'false';

        }

    }

    #Function is use to display all member exam list#

    public function all_exam_applicantlst()
    {

        #---Display the member lsit ---#

        //set seesion data

        $inst_id = $_SESSION['institute_id'];

        //$exam_code=$this->session->userdata('examcode');

        $member_list = array();

        $member_list = $this->master_model->getRecords('bulk_member_exam', array('inst_id' => $inst_id, 'virtual_del' => 0));

        if (!empty($member_list)) {

            $member_list = $member_list;

        }

        #----end---#

        //$check_exam_activation=check_exam_activate($this->session->userdata('examcode'));

        $result = $this->master_model->getRecords('bulk_member_exam');

        $data = array('middle_content' => 'bulk/all_exam_applicantlst', 'result' => $result, 'member_list' => $member_list);

        $this->load->view('bulk/bulk_common_view', $data);

        //    $this->load->view('bulk/exam_applicantlst');

    }

    ##-------------- check qualify exam pass/fail

    /*public function checkqualify($qualify_id=NULL,$examcode=NULL,$part_no=NULL)

    {

    $flag=0;

    $check_qualify=array();

    $message='Pre qualifying exam not found';

    $check_qualify_exam=$this->master_model->getRecords('exam_master',array('exam_code'=>$examcode));

    //Query to check the qualifying exam details of selected exam(Below code for those exam who have pre-qualifying exam)

    $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

    $check_qualify_exam_eligibility=$this->master_model->getRecords('eligible_master',array('eligible_master.exam_code'=>$qualify_id,'part_no'=>$part_no,'member_type'=>$this->session->userdata('memtype'),'member_no'=>$this->session->userdata('regnumber')),'exam_status,remark');

    if(count($check_qualify_exam_eligibility) > 0)

    {

    if($check_qualify_exam_eligibility[0]['exam_status']=='P')

    {

    //check eligibility for applied exam(This are the exam who  have pre qualifying exam)

    $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=eligible_master.exam_code AND bulk_exam_activation_master.exam_period=eligible_master.eligible_period');

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

    //CAIIB apply directly

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

    //show message with pre-qualifying exam name if pre-qualify exam yet to not apply.

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

    public function add()
    {

        $data = array();

        $data = array('middle_content' => 'bulk/bulk-add-exam');

        $this->load->view('bulk/bulk_common_view', $data);

        //$this->load->view('bulk/bulk-add-exam',$data);

    }

    //Show acknowlodgement to to user after transaction succeess

    public function details($order_no = null, $excd = null)
    {

        $this->chk_session->checkphoto();

        //payment detail

        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('regnumber')));

        $today_date = date('Y-m-d');

        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');

        $this->db->where('elg_mem_o', 'Y');

        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

        $this->db->where("misc_master.misc_delete", '0');

        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code');

        $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => base64_decode($excd), 'regnumber' => $this->session->userdata('regnumber'), 'pay_status' => '1'));

        if (count($applied_exam_info) <= 0) {

            redirect(base_url() . 'bulk/BulkApply/dashboard');

        }

        $this->db->where('medium_delete', '0');

        $this->db->where('exam_code', base64_decode($excd));

        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);

        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', base64_decode($excd));

        $this->db->where("center_delete", '0');

        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);

        $center = $this->master_model->getRecords('center_master', '', '', array('center_name' => 'ASC'));

        //echo $this->db->last_query();exit;

        //get state details

        $this->db->where('state_delete', '0');

        $states = $this->master_model->getRecords('state_master');

        if (count($applied_exam_info) <= 0) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

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

        $data = array('middle_content' => 'exam_applied_success', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info, 'payment_info' => $payment_info, 'states' => $states);

        $this->load->view('common_view', $data);

    }

    //Show acknowlodgement to to user after transaction succeess

    public function savedetails()
    {

        $this->chk_session->checkphoto();

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $exam_code = $this->session->userdata['examinfo']['excd'];

        $today_date = date('Y-m-d');

        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month');

        $this->db->where('elg_mem_o', 'Y');

        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

        $this->db->where("misc_master.misc_delete", '0');

        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code');

        $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $this->session->userdata('regnumber')));

        $this->db->where('medium_delete', '0');

        $this->db->where('exam_code', $exam_code);

        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);

        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', $exam_code);

        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);

        $this->db->where("center_delete", '0');

        $center = $this->master_model->getRecords('center_master', '', '', array('center_name' => 'ASC'));

        //get state details

        $this->db->where('state_delete', '0');

        $states = $this->master_model->getRecords('state_master');

        if (count($applied_exam_info) <= 0) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $data = array('middle_content' => 'exam_applied_success_withoutpay', 'medium' => $medium, 'center' => $center, 'applied_exam_info' => $applied_exam_info, 'states' => $states);

        $this->load->view('common_view', $data);

    }

    //Show acknowlodgement to to user after transaction Failure

    public function fail($order_no = null)
    {

        $this->chk_session->checkphoto();

        //payment detail

        $payment_info = $this->master_model->getRecords('payment_transaction', array('receipt_no' => base64_decode($order_no), 'member_regnumber' => $this->session->userdata('regnumber')));

        if (count($payment_info) <= 0) {

            redirect(base_url());

        }

        $data = array('middle_content' => 'exam_applied_fail', 'payment_info' => $payment_info);

        $this->load->view('common_view', $data);

    }

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

        $data = array('middle_content' => 'member_refund', 'payment_info' => $payment_info, 'exam_name' => $exam_name);

        $this->load->view('common_view', $data);

    }

    /*//get applied exam name which is fall on same date

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

    $this->db->join('bulk_exam_activation_master','bulk_exam_activation_master.exam_code=member_exam.exam_code AND bulk_exam_activation_master.exam_period=member_exam.exam_period');

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

    ##---------Forcefully Update profile mesage to user-----------##

    public function notification()
    {

        $msg = '';

        $flag = 1;

        $user_images = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'), 'scannedphoto,scannedsignaturephoto,idproofphoto,mobile,email');

        if ((!file_exists('./uploads/photograph/' . $user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/' . $user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/' . $user_images[0]['idproofphoto']) || $user_images[0]['scannedphoto'] == '' || $user_images[0]['scannedsignaturephoto'] == '' || $user_images[0]['idproofphoto'] == '') && (!is_file(get_img_name($this->session->userdata('regnumber'), 'pr')) || !is_file(get_img_name($this->session->userdata('regnumber'), 's')) || !is_file(get_img_name($this->session->userdata('regnumber'), 'p')))) {

            $flag = 0;

            $msg .= '<li>Your Photo/signature or ID proof are not available kindly go to Edit Profile and <a href="' . base_url() . 'BulkApply/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>';

        }

        if ($user_images[0]['mobile'] == '' || $user_images[0]['email'] == '') {

            $flag = 0;

            $msg .= '<li>

Your email id or mobile number are not available kindly go to Edit Profile and <a href="' . base_url() . 'BulkApply/profile/">click here</a> to update the, email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';

        }

        if (validate_userdata($this->session->userdata('regnumber'))) {

            $flag = 0;

            $msg .= '<li>

Please check all mandatory fields in profile <a href="' . base_url() . 'BulkApply/profile/">click here</a> to update the, profile. For any queries contact zonal office.</li>';

        }

        /*    if((!file_exists('./uploads/photograph/'.$user_images[0]['scannedphoto']) || !file_exists('./uploads/scansignature/'.$user_images[0]['scannedsignaturephoto']) || !file_exists('./uploads/idproof/'.$user_images[0]['idproofphoto']) || ($user_images[0]['scannedphoto']=='' || $user_images[0]['scannedsignaturephoto']=='' || $user_images[0]['idproofphoto']!='')) || ($user_images[0]['mobile']=='' ||$user_images[0]['email']==''))

        {

        $flag=0;

        $msg='<li>Your Photo/signature are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to upload the Photo/Signature and then apply for exam. For any queries contact zonal office.</li>

        <li>

        Your email id or mobile number are not available kindly go to Edit Profile and <a href="'.base_url().'BulkApply/profile/">click here</a> to update the, then email id or mobile number and then apply for exam. For any queries contact zonal office.</li>';

        }*/

        if ($flag) {

            redirect(base_url() . 'bulk/BulkApply/dashboard');

        }

        $data = array('middle_content' => 'member_notification', 'msg' => $msg);

        $this->load->view('common_view', $data);

    }

    ##---print user edit profile

    public function printUser()
    {

        $this->chk_session->checkphoto();

        $qualification = array();

        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');

        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');

        $this->db->join('state_master', 'state_master.state_code=member_registration.state');

        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');

        $this->db->where('institution_master.institution_delete', '0');

        $this->db->where('state_master.state_delete', '0');

        $this->db->where('designation_master.designation_delete', '0');

        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'));

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

    ##--print user edit profile

    public function printexamdetails()
    {

        $state_place_of_work = $elective_subject_name = '';

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //$this->chk_session->checkphoto();

        $qualification = array();

        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');

        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');

        $this->db->join('state_master', 'state_master.state_code=member_registration.state');

        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');

        $this->db->where('institution_master.institution_delete', '0');

        $this->db->where('state_master.state_delete', '0');

        $this->db->where('designation_master.designation_delete', '0');

        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'));

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

        include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';

        $key = $this->config->item('pass_key');

        $aes = new CryptAES();

        $aes->set_key(base64_decode($key));

        $aes->require_pkcs5();

        $decpass = $aes->decrypt(trim($user_info[0]['usrpassword']));

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $exam_code = $this->session->userdata['examinfo']['excd'];

        $today_date = date('Y-m-d');

        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');

        $this->db->where('elg_mem_o', 'Y');

        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

        $this->db->where("misc_master.misc_delete", '0');

        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code');

        $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $this->session->userdata('regnumber'), 'pay_status' => '1'));

        $this->db->where('medium_delete', '0');

        $this->db->where('exam_code', $exam_code);

        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);

        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', $exam_code);

        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);

        $this->db->where("center_delete", '0');

        $center = $this->master_model->getRecords('center_master', '', '', array('center_name' => 'ASC'));

        //get state details

        $this->db->where('state_delete', '0');

        $states = $this->master_model->getRecords('state_master');

        if (count($applied_exam_info) <= 0) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //get Elective Subeject name for CAIIB Exam

        $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array('subject_code' => $applied_exam_info['0']['elected_sub_code'], 'subject_delete' => 0), 'subject_description');

        if (count($elective_sub_name_arr) > 0) {

            $elective_subject_name = $elective_sub_name_arr[0]['subject_description'];

        }

        $data = array('middle_content' => 'print_member_applied_exam_details', 'user_info' => $user_info, 'qualification' => $qualification, 'idtype_master' => $idtype_master, 'applied_exam_info' => $applied_exam_info, 'medium' => $medium, 'center' => $center, 'qualification' => $qualification, 'states' => $states, 'elective_subject_name' => $elective_subject_name);

        $this->load->view('common_view', $data);

    }

    //download pdf

    public function downloadeditprofile()
    {

        $gender = $idtype = '';

        $this->chk_session->checkphoto();

        $qualification = array();

        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');

        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');

        $this->db->join('state_master', 'state_master.state_code=member_registration.state');

        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');

        $this->db->where('institution_master.institution_delete', '0');

        $this->db->where('state_master.state_delete', '0');

        $this->db->where('designation_master.designation_delete', '0');

        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'));

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

        if (isset($idtype_master[0]['name'])) {

            $idtype = $idtype_master[0]['name'];

        }

        $username = $user_info[0]['namesub'] . ' ' . $user_info[0]['firstname'] . ' ' . $user_info[0]['middlename'] . ' ' . $user_info[0]['lastname'];

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

			<td colspan="3" class="tablecontent2" nowrap="nowrap">' . $idtype . '</td>

		</tr>



				<tr>

			<td class="tablecontent2">I agree to receive the Annual report from the Institute in a softcopy, at my registered email ID, in place of physical copy :</td>

			<td colspan="3" class="tablecontent2" nowrap="nowrap"> ' . $optnletter . ' </td>

		</tr>

		<tr>

			<td class="tablecontent2">ID Proof :</td>

			<td colspan="3" class="tablecontent2" nowrap="nowrap">  <img src="' . base_url() . get_img_name($this->session->userdata('regnumber'), 'pr') . '"  height="180" width="100"></td>

		</tr>

		<tr>

			<td class="tablecontent2">Signature :</td>

			<td colspan="3" class="tablecontent2" nowrap="nowrap"><img src="' . base_url() . get_img_name($this->session->userdata('regnumber'), 's') . '" height="100" width="100"></td>

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

    // ##---------Download pdf-----------##

    public function pdf()
    {

        $this->chk_session->checkphoto();

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

						Your application saved successfully.<br><br><strong>Your Membership No is</strong> ' . $this->session->userdata('regnumber') . ' <strong>and Your password is </strong>' . base64_decode($this->session->userdata('password')) . '<br><br>Please note down your Membership No and Password for further reference.<br> <br>You may print or save membership registration page for further reference.<br><br>Please ensure proper Page Setup before printing.<br><br>Click on Continue to print registration page.<br><br>You can save system generated application form as PDF for future refence

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

    ##---------------------- Public function exam pdf ##########

    public function exampdf()
    {

        $state_place_of_work = $elective_subject_name = $ID_Proof = '';

        $this->chk_session->checkphoto();

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        $qualification = array();

        $this->db->select('member_registration.*,institution_master.name,state_master.state_name,designation_master.dname');

        $this->db->join('institution_master', 'institution_master.institude_id=member_registration.associatedinstitute');

        $this->db->join('state_master', 'state_master.state_code=member_registration.state');

        $this->db->join('designation_master', 'designation_master.dcode=member_registration.designation');

        $this->db->where('institution_master.institution_delete', '0');

        $this->db->where('state_master.state_delete', '0');

        $this->db->where('designation_master.designation_delete', '0');

        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'));

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

        $string1 = $user_info[0]['address1'] . $user_info[0]['address2'] . $user_info[0]['address3'] . $user_info[0]['address4'];

        $finalstr1 = str_replace("*", "<br>", $string1);

        $string2 = ',' . $user_info[0]['district'] . ',' . $user_info[0]['city'] . '*' . $user_info[0]['state_name'] . ',' . $user_info[0]['pincode'];

        $finalstr2 = str_replace("*", ",<br>", $string2);

        $useradd = preg_replace('#[\s]+#', ' ', $finalstr1 . $finalstr2);

        if (($this->session->userdata('examinfo') == '')) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //$this->session->userdata['examinfo']['excd']='NTgw';

        $exam_code = $this->session->userdata['examinfo']['excd'];

        $today_date = date('Y-m-d');

        $this->db->select('member_exam.id,member_exam.regnumber,member_exam.exam_code,member_exam.exam_mode,member_exam.exam_medium,member_exam.exam_period,member_exam.exam_center_code,member_exam.exam_fee,member_exam.note,member_exam.pay_status,exam_master.description,misc_master.exam_month,member_exam.place_of_work,member_exam.state_place_of_work,member_exam.pin_code_place_of_work,member_exam.elected_sub_code');

        $this->db->where('elg_mem_o', 'Y');

        $this->db->join('misc_master', 'misc_master.exam_code=member_exam.exam_code AND misc_master.exam_period=member_exam.exam_period');

        $this->db->where("misc_master.misc_delete", '0');

        $this->db->join('exam_master', 'exam_master.exam_code=member_exam.exam_code');

        $this->db->join('bulk_exam_activation_master', 'bulk_exam_activation_master.exam_code=member_exam.exam_code');

        $this->db->where("'$today_date' BETWEEN bulk_exam_activation_master.exam_from_date AND bulk_exam_activation_master.exam_to_date");

        $applied_exam_info = $this->master_model->getRecords('member_exam', array('member_exam.exam_code' => $exam_code, 'regnumber' => $this->session->userdata('regnumber'), 'pay_status' => '1'));

        $this->db->where('medium_delete', '0');

        $this->db->where('exam_code', $exam_code);

        $this->db->where('medium_code', $applied_exam_info[0]['exam_medium']);

        $medium = $this->master_model->getRecords('medium_master', '', 'medium_description');

        $this->db->where('exam_name', $exam_code);

        $this->db->where('center_code', $applied_exam_info[0]['exam_center_code']);

        $this->db->where("center_delete", '0');

        $center = $this->master_model->getRecords('center_master', '', '', array('center_name' => 'ASC'));

        //get state details

        $this->db->where('state_delete', '0');

        $states = $this->master_model->getRecords('state_master');

        if (count($applied_exam_info) <= 0) {

            redirect(base_url() . 'bulk/BulkApply/dashboard/');

        }

        //$month = date('Y')."-".substr($applied_exam_info['0']['exam_month'],4)."-".date('d');

        $month = date('Y') . "-" . substr($applied_exam_info['0']['exam_month'], 4);

        $exam_period = date('F', strtotime($month)) . "-" . substr($applied_exam_info['0']['exam_month'], 0, -2);

        if ($applied_exam_info[0]['exam_mode'] == 'ON') {

            $mode = 'Online';

        } else if ($applied_exam_info[0]['exam_mode'] == 'OF') {

            $mode = 'Offline';

        }

        //get sate name for CAIIB/JAIIB examination

        if (count($states) > 0 && $applied_exam_info[0]['state_place_of_work'] != '') {

            foreach ($states as $srow) {

                if ($applied_exam_info[0]['state_place_of_work'] == $srow['state_code']) {

                    $state_place_of_work = $srow['state_name'];

                }

            }

        }

        //get Elective Subeject name for CAIIB Exam

        $elective_sub_name_arr = $this->master_model->getRecords('subject_master', array('subject_code' => $applied_exam_info['0']['elected_sub_code'], 'subject_delete' => 0), 'subject_description');

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

			<tr><br></tr>

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

				' . wordwrap($useradd, 50, "<br>\n") . '			</td>

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

    ##------- check eligible user----------##

    public function checkusers($examcode = null)
    {

        $flag = 0;

        if ($examcode != null) {

            $exam_code = array(33, 47, 51, 52);

            if (in_array($examcode, $exam_code)) {

                $this->db->where_in('eligible_master.exam_code', $exam_code);

                $valid_member_list = $this->master_model->getRecords('eligible_master', array('eligible_period' => '417', 'member_type' => 'O'), 'member_no');

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

    //check aadhar card

    public function check_aadhar($aadhar_card)
    {

        if ($aadhar_card != "") {

            $where = "registrationtype='NM'";

            $this->db->where($where);

            $prev_count = $this->master_model->getRecordCount('member_registration', array('aadhar_card' => $aadhar_card, 'isactive' => '1')); //,'isactive'=>'1'

            //echo $this->db->last_query();

            //exit;

            if ($prev_count == 0) {

                return true;

            } else {

                $str = 'The entered Aadhar card number already exist';

                $this->form_validation->set_message('check_aadhar', $str);

                return false;

            }

        } else {

            return false;

        }

    }

    public function check_mobileduplication($mobile)
    {

        if ($mobile != "") {

            $prev_count = $this->master_model->getRecordCount('member_registration', array('mobile' => $mobile, 'regid !=' => $this->session->userdata('regid'), 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

            //echo $this->db->last_query();

            if ($this->session->userdata('examcode') == 1015 && $this->session->userdata['institute_id'] == 10004) {

                $prev_count = 0;

            }

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

            $prev_count = $this->master_model->getRecordCount('member_registration', array('email' => $email, 'regid !=' => $this->session->userdata('regid'), 'isactive' => '1', 'registrationtype' => $this->session->userdata('memtype')));

            if ($this->session->userdata('examcode') == 1015 && $this->session->userdata['institute_id'] == 10004) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 994 && $this->session->userdata('institute_id') == 2851) {
                $prev_count = 0;
            }

            if ($this->session->userdata('examcode') == 101 && $this->session->userdata['institute_id'] == 9994) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10012) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10014) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10015) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10022) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10016) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10018) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 10039) {

                $prev_count = 0;

            }

            if ($this->session->userdata('examcode') == 996 && $this->session->userdata['institute_id'] == 9998) {

                $prev_count = 0;

            }

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

    // ##---------End user profile -----------##

    public function profile()
    {

        $kycflag = 0;

        $prevData = array();

        $user_info = $this->master_model->getRecords('member_registration', array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber'), 'isactive' => '1'));

        if (count($user_info)) {

            $prevData = $user_info[0];

        } else {

            base_url();

        }

        $scannedphoto_file = $scannedsignaturephoto_file = $idproofphoto_file = '';

        if (isset($_POST['btnSubmit'])) {

            $this->form_validation->set_rules('firstname', 'First Name', 'trim|max_length[30]|required');

            $this->form_validation->set_rules('nameoncard', 'Name as to appear on Card', 'trim|max_length[35]|required');

            $this->form_validation->set_rules('addressline1', 'Addressline1', 'trim|max_length[30]|required');

            $this->form_validation->set_rules('district', 'District', 'trim|max_length[30]|required');

            $this->form_validation->set_rules('city', 'City', 'trim|max_length[30]|required');

            $this->form_validation->set_rules('state', 'State', 'trim|required');

            $this->form_validation->set_rules('pincode', 'Pincode/Zipcode', 'trim|required');

            //$this->form_validation->set_rules('dob','Date of Birth','trim|required');

            $this->form_validation->set_rules('gender', 'Gender', 'trim|required');

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

            $this->form_validation->set_rules('institutionworking', 'Bank/Institution working', 'trim|numeric|required');

            $this->form_validation->set_rules('office', 'Branch/Office', 'trim|required');

            $this->form_validation->set_rules('designation', 'Designation', 'trim|required');

            $this->form_validation->set_rules('doj1', 'Date of joining Bank/Institution', 'trim|required');

            //$this->form_validation->set_rules('aadhar_card','Aadhar Card Number','trim|required');

            //$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|numeric|is_unique[member_registration.aadhar_card.regid.'.$this->session->userdata('regid').']');

            if ($this->input->post('state') == 'ASS' || $this->input->post('state') == 'JAM' || $this->input->post('state') == 'MEG') {

                if ($this->input->post("aadhar_card") != '') {

                    //$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');

                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $this->session->userdata('regid') . '.registrationtype.' . $this->session->userdata('memtype') . ']');

                }

            } else {
                if ($this->input->post("aadhar_card") != '') {

                    //$this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'required|trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.'.$this->session->userdata('regid').'.registrationtype.'.$this->session->userdata('memtype').']');

                    $this->form_validation->set_rules('aadhar_card', 'Aadhar Card Number', 'trim|xss_clean|max_length[12]|min_length[12]|numeric|is_unique[member_registration.aadhar_card.isactive.1.regid.' . $this->session->userdata('regid') . '.registrationtype.' . $this->session->userdata('memtype') . ']');

                }

            }

            $this->form_validation->set_rules('idproof', 'Id Proof', 'trim|required|max_length[25]|xss_clean');

            $this->form_validation->set_rules('sel_namesub', 'Sub Name', 'trim|required');

            $this->form_validation->set_rules('scannedphoto1_hidd', 'Uploaded Photo', 'trim|required');

            $this->form_validation->set_rules('scannedsignaturephoto1_hidd', 'uploaded Signature', 'trim|required');

            $this->form_validation->set_rules('idproofphoto1_hidd', 'Uploaded ID Proof', 'trim|required');

            //$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_validunique[member_registration.email.regid.'.$this->session->userdata('regid').'.isactive.1]|xss_clean');

            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_validuniqueO[member_registration.email.regid.' . $this->session->userdata('regid') . '.isactive.1.registrationtype.' . $this->session->userdata('memtype') . ']|xss_clean');

            $this->form_validation->set_rules('mobile', 'Mobile', 'trim|required|numeric');

            if ($this->form_validation->run() == true) {

                $addressline1 = strtoupper($this->input->post('addressline1'));

                $addressline2 = strtoupper($this->input->post('addressline2'));

                $addressline3 = strtoupper($this->input->post('addressline3'));

                $addressline4 = strtoupper($this->input->post('addressline4'));

                $district = strtoupper($this->input->post('district'));

                $city = strtoupper($this->input->post('city'));

                $state = $this->input->post('state');

                $pincode = $this->input->post('pincode');

                //$dob= $this->input->post('dob');

                $gender = $this->input->post('gender');

                $optedu = $this->input->post('optedu');

                if ($optedu == 'U') {

                    $specify_qualification = $this->input->post('eduqual1');

                } elseif ($optedu == 'G') {

                    $specify_qualification = $this->input->post('eduqual2');

                } else if ($optedu == 'P') {

                    $specify_qualification = $this->input->post('eduqual3');

                }

                $institutionworking = $this->input->post('institutionworking');

                $office = strtoupper($this->input->post('office'));

                $designation = $this->input->post('designation');

                $doj = $this->input->post('doj1');

                $email = $this->input->post('email');

                $stdcode = $this->input->post('stdcode');

                $phone = $this->input->post('phone');

                $mobile = $this->input->post('mobile');

                $idproof = $this->input->post('idproof');

                $optnletter = $this->input->post('optnletter');

                $declaration1 = $this->input->post("declaration1");

                $aadhar_card = $this->input->post("aadhar_card");

                $idproof = $this->input->post("idproof");

                $sel_namesub = $this->input->post("sel_namesub");

                $firstname = $this->input->post("firstname");

                $nameoncard = $this->input->post("nameoncard");

                $dob = $this->input->post("dob1");

                $middlename = $this->input->post("middlename");

                $lastname = $this->input->post("lastname");

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

                    if (date('Y-m-d', strtotime($prevData['dateofbirth'])) != date('Y-m-d', strtotime($dob)) && $dob !== '') {
                        $update_data['dateofbirth'] = date('Y-m-d', strtotime($dob));

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_dob'] = 1;

                    }

                    if ($prevData['gender'] != $gender) {$update_data['gender'] = $gender;}

                    if ($prevData['qualification'] != $optedu) {$update_data['qualification'] = $optedu;}

                    if ($prevData['specify_qualification'] != $specify_qualification) {$update_data['specify_qualification'] = $specify_qualification;}

                    if ($prevData['associatedinstitute'] != $institutionworking) {
                        $update_data['associatedinstitute'] = $institutionworking;

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_associate_inst'] = 1;

                    }

                    if ($prevData['office'] != $office) {$update_data['office'] = $office;}

                    if ($prevData['designation'] != $designation) {$update_data['designation'] = $designation;}

                    if (date('Y-m-d', strtotime($prevData['dateofjoin'])) != date('Y-m-d', strtotime($doj))) {
                        $update_data['dateofjoin'] = date('Y-m-d', strtotime($doj));

                    }

                    if ($prevData['email'] != $email) {$update_data['email'] = $email;}

                    if ($prevData['stdcode'] != $stdcode) {$update_data['stdcode'] = $stdcode;}

                    if ($prevData['office_phone'] != $phone) {$update_data['office_phone'] = $phone;}

                    if ($prevData['mobile'] != $mobile) {$update_data['mobile'] = $mobile;}

                    if ($prevData['idproof'] != $idproof) {$update_data['idproof'] = $idproof;}

                    if ($prevData['aadhar_card'] != $aadhar_card) {$update_data['aadhar_card'] = $aadhar_card;}

                    if ($prevData['optnletter'] != $optnletter) {$update_data['optnletter'] = $optnletter;}

                    if ($prevData['namesub'] != $sel_namesub) {
                        $update_data['namesub'] = $sel_namesub;

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_name'] = 1;

                    }

                    if ($prevData['firstname'] != $firstname) {
                        $update_data['firstname'] = $firstname;

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_name'] = 1;

                    }

                    if ($prevData['middlename'] != $middlename) {
                        $update_data['middlename'] = $middlename;

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_name'] = 1;

                    }

                    if ($prevData['lastname'] != $lastname) {
                        $update_data['lastname'] = $lastname;

                        $update_data['kyc_edit'] = '1';

                        $update_data['kyc_status'] = '0';

                        $kycflag = 1;

                        $kyc_update_data['edited_mem_name'] = 1;

                    }

                    if ($prevData['displayname'] != $nameoncard) {$update_data['displayname'] = $nameoncard;}

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

                    //$update_data['editedbyadmin'] = $this->UserID;

                    //update member_kyc

                    //$personalInfo = filter($personal_info);

                    if ($this->master_model->updateRecord('member_registration', $update_data, array('regid' => $this->session->userdata('regid'), 'regnumber' => $this->session->userdata('regnumber')))) {

                        $desc['updated_data'] = $update_data;

                        $desc['old_data'] = $user_info[0];

                        //profile update logs

                        log_profile_user($log_title = "Profile updated successfully", $edited, 'data', $this->session->userdata('regid'), $this->session->userdata('regnumber'));

                        //logactivity($log_title = "Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));

                        /* User Log Activities : Bhushan */

                        $log_title = "Profile updated successfully id:" . $this->session->userdata('regid');

                        $log_message = serialize($desc);

                        $rId = $this->session->userdata('regid');

                        $regNo = $this->session->userdata('regnumber');

                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        /* Close User Log Actitives */

                        if ($kycflag == 1) {

                            $kyc_update_data['user_edited_date'] = date('Y-m-d H:i:s');

                            $kyc_update_data['kyc_state'] = '2';

                            $kyc_update_data['kyc_status'] = '0';

                            /*echo '<pre>';

                            print_r($kyc_update_data);

                            exit;*/

                            $this->db->like('allotted_member_id', $this->session->userdata('regnumber'));

                            $this->db->or_like('original_allotted_member_id', $this->session->userdata('regnumber'));

                            $check_duplicate_entry = $this->master_model->getRecords('admin_kyc_users', array('list_type' => 'New'));

                            if (count($check_duplicate_entry) > 0) {

                                foreach ($check_duplicate_entry as $row) {

                                    $allotted_member_id = $this->removeFromString($row['allotted_member_id'], $this->session->userdata('regnumber'));

                                    $original_allotted_member_id = $this->removeFromString($row['original_allotted_member_id'], $this->session->userdata('regnumber'));

                                    $admin_update_data = array('allotted_member_id' => $allotted_member_id, 'original_allotted_member_id' => $original_allotted_member_id);

                                    $this->master_model->updateRecord('admin_kyc_users', $admin_update_data, array('kyc_user_id' => $row['kyc_user_id']));

                                }

                            }

                            $kycmemdetails = $this->master_model->getRecords('member_kyc', array('regnumber' => $this->session->userdata('regnumber')), '', array('kyc_id' => 'DESC'), '0', '1');

                            if (count($kycmemdetails) > 0) {

                                if ($kycmemdetails[0]['kyc_status'] == '0') {

                                    $this->master_model->updateRecord('member_kyc', $kyc_update_data, array('kyc_id' => $kycmemdetails[0]['kyc_id']));

                                    $this->KYC_Log_model->create_log('kyc member profile edit', '', '', $this->session->userdata('regnumber'), serialize($desc));

                                }

                            }

                            //echo $this->db->last_query();exit;

                            //change by pooja godse for  memebersgip id card  dowanload count reset

                            //check membership count

                            $check_membership_cnt = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));

                            if (count($check_membership_cnt) > 0) {

                                //$this->master_model->deleteRecord('member_idcard_cnt','member_number',$this->session->userdata('regnumber'));

                                /* update dowanload count 8-8-2017 */

                                $this->master_model->updateRecord('member_idcard_cnt', array('card_cnt' => '0'), array('member_number' => $this->session->userdata('regnumber')));

                                /* Close update dowanload count */

                                /* User Log Activities : Pooja */

                                $uerlog = $this->master_model->getRecords('member_registration', array('regnumber' => $this->session->userdata('regnumber')), 'regid,regnumber');

                                $user_info = $this->master_model->getRecords('member_idcard_cnt', array('member_number' => $this->session->userdata('regnumber')));

                                $log_title = "Membership Id card Count Reset to 0 : " . $uerlog[0]['regid'];

                                $log_message = serialize($user_info);

                                $rId = $uerlog[0]['regid'];

                                $regNo = $this->session->userdata('regnumber');

                                storedUserActivity($log_title, $log_message, $rId, $regNo);

                                /* Close User Log Actitives */

                            }

                            //    logactivity($log_title = "KYC Profile updated successfully id:".$this->session->userdata('regid'), $description = serialize($desc));

                            /* User Log Activities : Bhushan */

                            $log_title = "KYC Profile updated successfully id:" . $this->session->userdata('regid');

                            $log_message = serialize($desc);

                            $rId = $this->session->userdata('regid');

                            $regNo = $this->session->userdata('regnumber');

                            storedUserActivity($log_title, $log_message, $rId, $regNo);

                            /* Close User Log Actitives */

                        }

                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'user_register_email'));

                        if (count($emailerstr) > 0) {

                            $newstring = str_replace("#application_num#", "" . $this->session->userdata('regnumber') . "", $emailerstr[0]['emailer_text']);

                            $final_str = str_replace("#password#", "" . base64_decode($this->session->userdata('password')) . "", $newstring);

                            $info_arr = array(

                                'to'      => $email,

                                'from'    => $emailerstr[0]['from'],

                                'subject' => $emailerstr[0]['subject'],

                                'message' => $final_str,

                            );

                            if ($this->Emailsending->mailsend($info_arr)) {

                                //$this->session->set_flashdata('success','Your Application has been Submitted Successfully !!');

                                redirect(base_url('bulk/BulkApply/acknowledge/'));

                            } else {

                                $this->session->set_flashdata('error', 'Error while sending email !!');

                                redirect(base_url('bulk/BulkApply/profile/'));

                            }

                        } else {

                            $this->session->set_flashdata('error', 'Error while sending email !!');

                            redirect(base_url('bulk/BulkApply/profile/'));

                        }

                    } else {

                        $desc['updated_data'] = $update_data;

                        $desc['old_data'] = $user_info[0];

                        //logactivity($log_title = "Profile update error id:".$this->session->userdata('regid'), $description = serialize($desc));

                        /* User Log Activities : Bhushan */

                        $log_title = "Profile update error id:" . $this->session->userdata('regid');

                        $log_message = serialize($desc);

                        $rId = $this->session->userdata('regid');

                        $regNo = $this->session->userdata('regnumber');

                        storedUserActivity($log_title, $log_message, $rId, $regNo);

                        /* Close User Log Actitives */

                        $this->session->set_flashdata('error', 'Error While Adding Your Information !!');

                        $last = $this->uri->total_segments();

                        $post = $this->uri->segment($last);

                        redirect(base_url() . $post);

                    }

                } else {

                    $this->session->set_flashdata('error', 'Change atleast one field');

                    redirect(base_url('bulk/BulkApply/profile/'));

                }

            } else {

                //echo validation_errors();exit;

                $data['validation_errors'] = validation_errors();

                //echo "222222";vdebug($_POST);exit;

            }

        }

        $undergraduate = $this->master_model->getRecords('qualification', array('type' => 'UG'));

        $graduate = $this->master_model->getRecords('qualification', array('type' => 'GR'));

        $postgraduate = $this->master_model->getRecords('qualification', array('type' => 'PG'));

        $this->db->where('institution_master.institution_delete', '0');

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

        $data = array('middle_content' => 'userprofile', 'states' => $states, 'undergraduate' => $undergraduate, 'graduate' => $graduate, 'postgraduate' => $postgraduate, 'institution_master' => $institution_master, 'designation' => $designation, 'user_info' => $user_info, 'idtype_master' => $idtype_master);

        $this->load->view('common_view', $data);

    }

}
