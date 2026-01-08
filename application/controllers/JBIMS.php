<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
class JBIMS extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('upload');
        $this->load->helper('upload_helper');
        $this->load->helper('master_helper');
        $this->load->helper('general_helper');
        //$this->load->helper('JBIMS_invocie_helper')s;
        $this->load->model('Master_model');
        $this->load->library('email');
        $this->load->helper('date');
        $this->load->library('email');
        $this->load->model('Emailsending');
        $this->load->model('log_model');
        $this->load->model('JBIMSmodel');
        $this->load->model('billdesk_pg_model');

    }

    public function JBIMS_capacity()
    {
        $data = array('middle_content' => 'JBIMS/JBIMS_capacity');
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    public function self()
    {
        //die;

        $this->db->join('JBIMS_candidates', 'JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
        $JBIMS_data_count = $this->master_model->getRecords('JBIMS_payment_transaction', array('status' => 1, 'date >' => '2021-09-15', 'JBIMS_payment_transaction.sponsor' => 'self'));
        //echo COUNT($JBIMS_data_count) ; die;
        if (COUNT($JBIMS_data_count) > 50) {
            redirect(base_url() . 'JBIMS/JBIMS_capacity');
        }

        $photo_error   = '';
        $sign_error    = '';
        $idproof_error = '';

        if ($this->input->post('form_type') == 'JBIMS_form') {
            $aPost = $this->input->post();
            //echo '<pre>';print_r($aPost);die;
            if ($this->is_valid('self')) {
                $aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
                $aPost['agree']        = isset($aPost['agree']) ? 1 : 0;
                $insert_info           = array(
                    'sponsor'               => 'self',
                    'name'                  => $aPost['sel_namesub'] . ' ' . strtoupper($aPost['name']),
                    'dob'                   => $aPost['dob'],
                    'iibf_membership_no'    => $aPost['iibf_membership_no'],
                    'bday'                  => $aPost['bday'],
                    'bmonth'                => $aPost['bmonth'],
                    'byear'                 => $aPost['byear'],
                    'address1'              => strtoupper($aPost['address1']),
                    'address2'              => strtoupper($aPost['address2']),
                    'address3'              => strtoupper($aPost['address3']),
                    'address4'              => strtoupper($aPost['address4']),
                    'state'                 => $aPost['state'],
                    'city'                  => strtoupper($aPost['city']),
                    'pincode_address'       => $aPost['pincode_address'],
                    'std_code'              => $aPost['std_code'],
                    'phone_no'              => $aPost['phone_no'],
                    'mobile_no'             => $aPost['mobile_no'],
                    'email_id'              => $aPost['email_id'],
                    'alt_email_id'          => $aPost['alt_email_id'],
                    'graduation'            => $aPost['graduation'],
                    'post_graduation'       => $aPost['post_graduation'],
                    'special_qualification' => $aPost['special_qualification'],
                    'name_employer'         => strtoupper($aPost['name_employer']),
                    'gender'                => strtoupper($aPost['gender']),
                    'current_role'          => strtoupper($aPost['current_role']),
                    'position'              => strtoupper($aPost['position']),

                    'till_present'          => $aPost['till_present'],
                    'work_experiance'       => $aPost['work_experiance'],
                    'payment'               => $aPost['payment'],
                    'agree'                 => $aPost['agree'],
                    'gst_bank_name'         => $aPost['gst_bank_name'],
                    'gst_no'                => $aPost['gst_no'],
                    'createdon'             => date('Y-m-d H:i:s'),
                );

                //$last_id = $this->master_model->insertRecord('JBIMS_candidates',$insert_info,true);

                if (isset($_FILES['photograph']['name']) && ($_FILES['photograph']['name'] != '')) {
                    $img          = "photograph";
                    $tmp_photonm  = strtotime('now') . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_photonm;
                    $config       = array('upload_path' => './uploads/JBIMS/photograph',
                        'allowed_types'                     => 'jpg|jpeg',
                        'file_name'                         => $new_filename,
                        'max_size'                          => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['photograph']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $insert_info['photograph'] = $dt['file_name'];
                        } else {
                            $photo_error = $this->upload->display_errors();
                        }
                    } else {
                        $photo_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }

                if (isset($_FILES['idproof']['name']) && ($_FILES['idproof']['name'] != '')) {
                    $img           = "idproof";
                    $tmp_idproofnm = strtotime('now') . rand(0, 100);
                    $new_filename  = 'idproof_' . $tmp_idproofnm;
                    $config        = array('upload_path' => './uploads/JBIMS/idproof',
                        'allowed_types'                      => 'jpg|jpeg',
                        'file_name'                          => $new_filename,
                        'max_size'                           => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproof']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                     = $this->upload->data();
                            $insert_info['idproof'] = $dt['file_name'];
                        } else {
                            $idproof_error = $this->upload->display_errors();
                        }
                    } else {
                        $idproof_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }

                if (isset($_FILES['signature']['name']) && ($_FILES['signature']['name'] != '')) {
                    $img          = "signature";
                    $tmp_photonm  = strtotime('now') . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_photonm;
                    $config       = array('upload_path' => './uploads/JBIMS/signature',
                        'allowed_types'                     => 'jpg|jpeg',
                        'file_name'                         => $new_filename,
                        'max_size'                          => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['signature']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                       = $this->upload->data();
                            $insert_info['signature'] = $dt['file_name'];
                        } else {
                            $sign_error = $this->upload->display_errors();
                        }
                    } else {
                        $sign_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }

                if ($sign_error == '' && $photo_error == '' && $idproof_error == '') {
                    $this->session->set_userdata('insertdata', $insert_info);
                    redirect(base_url() . 'JBIMS/payment');
                }
            }
        }

        $this->load->helper('captcha');
//         $this->session->unset_userdata("regJBIMScaptcha");
        //         $this->session->set_userdata("regJBIMScaptcha", rand(1, 100000));
        //         $vals = array(
        //                         'img_path' => './uploads/applications/',
        //                         'img_url' => base_url().'uploads/applications/',
        //                     );
        //         $cap = create_captcha($vals);
        $this->load->model('Captcha_model');
        $captcha_image = $this->Captcha_model->generate_captcha_img('regJBIMScaptcha');
        //state
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        //$this->session->set_userdata('regJBIMScaptcha', $captcha_image);
        $data = array('middle_content' => 'JBIMS/JBIMS_self', 'image' => $captcha_image, 'photo_error' => $photo_error, 'idproof_error' => $idproof_error, 'sign_error' => $sign_error, 'sponsor' => 'self', 'states' => $states);
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    public function bank()
    {
        //die;

        $JBIMS_data_count = $this->master_model->getRecords('JBIMS_candidates', array('regnumber !=' => 0, 'createdon >' => '2022-05-08', 'sponsor' => 'bank'));
        //echo COUNT($JBIMS_data_count) ; die;
        if (COUNT($JBIMS_data_count) > 75) {
            redirect(base_url() . 'JBIMS/JBIMS_capacity');
        }

        $photo_error   = '';
        $sign_error    = '';
        $idproof_error = '';

        if ($this->input->post('form_type') == 'JBIMS_form') {
            $aPost = $this->input->post();
            //    print_r($aPost);exit;
            if ($this->is_valid('bank')) {
                //echo '<pre>';print_r($aPost);die;
                $aPost['till_present'] = isset($aPost['till_present']) ? 1 : 0;
                $aPost['agree']        = isset($aPost['agree']) ? 1 : 0;
                $insert_info           = array(
                    'sponsor_bank_name'           => $aPost['sponsor_bank_name'],
                    'bank_address1'               => $aPost['bank_address1'],
                    'bank_address2'               => $aPost['bank_address2'],
                    'bank_address3'               => $aPost['bank_address3'],
                    //'bank_address4'=>$aPost['bank_address4'],
                    'bank_state'                  => $aPost['bank_state'],
                    'bank_city'                   => $aPost['bank_city'],
                    'bank_pincode'                => $aPost['bank_pincode'],
                    'sponsor_email'               => $aPost['sponsor_email'],
                    'sponsor_contact_person'      => $aPost['sponsor_contact_person'],
                    'sponsor_contact_designation' => $aPost['sponsor_contact_designation'],
                    'sponsor_contact_std'         => $aPost['sponsor_contact_std'],
                    'sponsor_contact_phone'       => $aPost['sponsor_contact_phone'],
                    'sponsor_contact_mobile'      => $aPost['sponsor_contact_mobile'],
                    'sponsor_contact_email'       => $aPost['sponsor_contact_email'],
                    'sponsor'                     => 'bank',
                    'name'                        => strtoupper($aPost['sel_namesub']) . ' ' . strtoupper($aPost['name']),
                    'dob'                         => $aPost['dob'],
                    'iibf_membership_no'          => $aPost['iibf_membership_no'],
                    //'regnumber'=>$aPost['regnumber'],
                    'bday'                        => $aPost['bday'],
                    'bmonth'                      => $aPost['bmonth'],
                    'byear'                       => $aPost['byear'],
                    'address1'                    => strtoupper($aPost['address1']),
                    'address2'                    => strtoupper($aPost['address2']),
                    'address3'                    => strtoupper($aPost['address3']),
                    'address4'                    => strtoupper($aPost['address4']),
                    'state'                       => $aPost['state'],
                    'city'                        => strtoupper($aPost['city']),
                    'pincode_address'             => $aPost['pincode_address'],
                    'std_code'                    => $aPost['std_code'],
                    'phone_no'                    => $aPost['phone_no'],
                    'mobile_no'                   => $aPost['mobile_no'],
                    'email_id'                    => $aPost['email_id'],
                    'alt_email_id'                => $aPost['alt_email_id'],
                    'graduation'                  => $aPost['graduation'],
                    'post_graduation'             => $aPost['post_graduation'],
                    'special_qualification'       => $aPost['special_qualification'],
                    'name_employer'               => strtoupper($aPost['name_employer']),
                    'gender'                      => strtoupper($aPost['gender']),
                    'current_role'                => strtoupper($aPost['current_role']),
                    'position'                    => strtoupper($aPost['position']),
                    // 'work_from_month'=>$aPost['work_from_month'],
                    // 'work_from_year'=>$aPost['work_from_year'],
                    // 'work_to_month'=>$aPost['work_to_month'],
                    // 'work_to_year'=>$aPost['work_to_year'],
                    'till_present'                => $aPost['till_present'],
                    'work_experiance'             => $aPost['work_experiance'],
                    //'payment'=>$aPost['payment'],
                    'agree'                       => $aPost['agree'],
                    //'gstin_no'=>$aPost['gstin_no'],
                    'createdon'                   => date('Y-m-d H:i:s'),
                );

                if (isset($_FILES['photograph']['name']) && ($_FILES['photograph']['name'] != '')) {
                    $img          = "photograph";
                    $tmp_photonm  = strtotime('now') . rand(0, 100);
                    $new_filename = 'photo_' . $tmp_photonm;
                    $config       = array('upload_path' => './uploads/JBIMS/photograph',
                        'allowed_types'                     => 'jpg|jpeg',
                        'file_name'                         => $new_filename,
                        'max_size'                          => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['photograph']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                        = $this->upload->data();
                            $update_info               = array('photograph' => $dt['file_name']);
                            $insert_info['photograph'] = $dt['file_name'];
                        } else {
                            $photo_error = $this->upload->display_errors();
                        }
                    } else {
                        $photo_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }

                if (isset($_FILES['idproof']['name']) && ($_FILES['idproof']['name'] != '')) {
                    $img           = "idproof";
                    $tmp_idproofnm = strtotime('now') . rand(0, 100);
                    $new_filename  = 'idproof_' . $tmp_idproofnm;
                    $config        = array('upload_path' => './uploads/JBIMS/idproof',
                        'allowed_types'                      => 'jpg|jpeg',
                        'file_name'                          => $new_filename,
                        'max_size'                           => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['idproof']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                     = $this->upload->data();
                            $insert_info['idproof'] = $dt['file_name'];
                        } else {
                            $idproof_error = $this->upload->display_errors();
                        }
                    } else {
                        $idproof_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }

                if (isset($_FILES['signature']['name']) && ($_FILES['signature']['name'] != '')) {
                    $img          = "signature";
                    $tmp_photonm  = strtotime('now') . rand(0, 100);
                    $new_filename = 'sign_' . $tmp_photonm;
                    $config       = array('upload_path' => './uploads/JBIMS/signature',
                        'allowed_types'                     => 'jpg|jpeg',
                        'file_name'                         => $new_filename,
                        'max_size'                          => 50);

                    $this->upload->initialize($config);
                    $size = @getimagesize($_FILES['signature']['tmp_name']);
                    if ($size) {
                        if ($this->upload->do_upload($img)) {
                            $dt                       = $this->upload->data();
                            $update_info              = array('signature' => $dt['file_name']);
                            $insert_info['signature'] = $dt['file_name'];
                        } else {
                            $sign_error = $this->upload->display_errors();
                        }
                    } else {
                        $sign_error = 'The filetype you are attempting to upload is not allowed';
                    }
                }
                if ($sign_error == '' && $photo_error == '' && $idproof_error == '') {
                    $this->session->set_userdata('insertdata', $insert_info);
                    $this->session->set_userdata('proforma', 'yes');

                    redirect(base_url() . 'JBIMS/bank_preview');
                }
            }
        }

        $this->load->helper('captcha');
//         $this->session->unset_userdata("regJBIMScaptcha");
        //         $this->session->set_userdata("regJBIMScaptcha", rand(1, 100000));
        //         $vals = array(
        //                         'img_path' => './uploads/applications/',
        //                         'img_url' => base_url().'uploads/applications/',
        //                     );
        //         $cap = create_captcha($vals);
        $this->load->model('Captcha_model');
        $captcha_image = $this->Captcha_model->generate_captcha_img('regJBIMScaptcha');
        //state
        $this->db->where('state_master.state_delete', '0');
        $states = $this->master_model->getRecords('state_master');

        //$this->session->set_userdata('regJBIMScaptcha', $captcha_image);
        $data = array('middle_content' => 'JBIMS/JBIMS_bank_sponsor', 'image' => $captcha_image, 'photo_error' => $photo_error, 'sign_error' => $sign_error, 'idproof_error' => $idproof_error, 'sponsor' => 'Bank', 'states' => $states);
        $this->load->view('JBIMS/common_view_fullwidth', $data);

    }

    //validation rules set
    public function is_valid($sponsor)
    {
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');

        $config = array(
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required|alpha_numeric_spaces',
            ), array(
                'field' => 'sel_namesub',
                'label' => 'sel_namesub',
                'rules' => 'required',
            ), array(
                'field' => 'gender',
                'label' => 'gender',
                'rules' => 'required',
            ), array(
                'field' => 'current_role',
                'label' => 'current_role',
                'rules' => 'required',
            ), array(
                'field' => 'iibf_membership_no',
                'label' => 'Membership No.',
                'rules' => 'max_length[11]|numeric',
            ), array(
                'field' => 'dob',
                'label' => 'Date of Birth',
                'rules' => 'required',
            ), array(
                'field' => 'bday',
                'label' => 'Birbday Day',
                'rules' => 'required',
            ), array(
                'field' => 'bmonth',
                'label' => 'Birbday Month',
                'rules' => 'required',
            ), array(
                'field' => 'byear',
                'label' => 'Birbday Year',
                'rules' => 'required',
            ), array(
                'field' => 'address1',
                'label' => 'Office/Residential Address',
                'rules' => 'required',
            ), array(
                'field' => 'city',
                'label' => 'city',
                'rules' => 'required',
            ), array(
                'field' => 'state',
                'label' => 'State',
                'rules' => 'required',
            ), array(
                'field' => 'pincode_address',
                'label' => 'Pincode',
                'rules' => 'required|exact_length[6]|numeric',
            ), array(
                'field' => 'std_code',
                'label' => 'STD code',
                'rules' => 'max_length[5]|numeric',
            ), array(
                'field' => 'phone_no',
                'label' => 'Phone No.',
                'rules' => 'max_length[8]|numeric',
            ), array(
                'field' => 'mobile_no',
                'label' => 'Mobile No.',
                'rules' => 'required|exact_length[10]|numeric|callback_check_unique_mobile',
            ), array(
                'field' => 'email_id',
                'label' => 'Candidates Email Id',
                'rules' => 'required|max_length[50]|valid_email|callback_check_unique_email',
            ), array(
                'field' => 'alt_email_id',
                'label' => 'Candidates Alternate Email Id',
                'rules' => 'max_length[50]|valid_email',
            ), array(
                'field' => 'name_employer',
                'label' => 'Name of the Employer',
                'rules' => 'required|max_length[30]|alpha_numeric_spaces',
            ), array(
                'field' => 'graduation',
                'label' => 'Graduation',
                'rules' => 'required',
            ), array(
                'field' => 'position',
                'label' => 'Position',
                'rules' => 'required|max_length[90]|alpha_numeric_spaces',
            ), array(
                'field' => 'work_experiance',
                'label' => 'Total Experience in month',
                'rules' => 'max_length[3]|min_length[3]|numeric',
            ), array(
                'field' => 'hiddenphoto',
                'label' => 'Photograph',
                'rules' => 'required',
            ), array(
                'field' => 'hiddenscanidproof',
                'label' => 'ID Proof',
                'rules' => 'required',
            ), array(
                'field' => 'hiddenscansignature',
                'label' => 'Signature',
                'rules' => 'required',
            ), array(
                'field' => 'agree',
                'label' => 'I Agree',
                'rules' => 'required',
            ), array(
                'field' => 'captcha',
                'label' => 'Captcha',
                'rules' => 'required|callback_check_captcha_userreg',
            ),
        );

        if ($sponsor == 'self') {
            $config[] = array(
                'field' => 'payment',
                'label' => 'Payment Option',
                'rules' => 'required',
            );
        }
        //validation rules set for bank sponser
        if ($sponsor == 'bank') {
            $config[] = array(
                'field' => 'sponsor_bank_name',
                'label' => 'Name Of Sponsored Bank',
                'rules' => 'required|max_length[30]|alpha_numeric_spaces',
            );
            $config[] = array(
                'field' => 'bank_address1',
                'label' => 'Address line1',
                'rules' => 'required|max_length[30]|alpha_numeric_spaces',
            );
            $config[] = array(
                'field' => 'bank_city',
                'label' => 'City ',
                'rules' => 'required',
            );
            $config[] = array(
                'field' => 'bank_state',
                'label' => 'State',
                'rules' => 'required',
            );
            $config[] = array(
                'field' => 'bank_pincode',
                'label' => 'Pincode',
                'rules' => 'required',
            );
            $config[] = array(
                'field' => 'sponsor_email',
                'label' => 'Name Of Department Email',
                'rules' => 'required|max_length[50]|valid_email',
            );
            $config[] = array(
                'field' => 'sponsor_contact_person',
                'label' => 'Contact person name',
                'rules' => 'required|max_length[40]|alpha_numeric_spaces',
            );
            $config[] = array(
                'field' => 'sponsor_contact_designation',
                'label' => 'Contact person Designation',
                'rules' => 'required|max_length[50]|alpha_numeric_spaces',
            );
            $config[] = array(
                'field' => 'sponsor_contact_std',
                'label' => 'Contact person STD code',
                'rules' => 'max_length[5]|numeric',
            );
            $config[] = array(
                'field' => 'sponsor_contact_phone',
                'label' => 'Contact person Phone No.',
                'rules' => 'max_length[8]|numeric',
            );
            $config[] = array(
                'field' => 'sponsor_contact_mobile',
                'label' => 'Contact person Mobile number',
                'rules' => 'max_length[10]|numeric',
            );
            $config[] = array(
                'field' => 'sponsor_contact_email',
                'label' => 'Contact person Email id',
                'rules' => 'required|max_length[50]|valid_email',
            );

        }

        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == false) {
            return false;
        }

        return true;
    }

    //work experience period check
    public function periodcheck()
    {

        if ($_POST['work_from_year'] != '' && $_POST['work_from_month'] != '' && $_POST['work_to_month'] != '' && $_POST['work_to_year'] != '') {
            $from = strtotime($_POST['work_from_year'] . '-' . $_POST['work_from_month'] . '-' . '1');
            $to   = strtotime($_POST['work_to_year'] . '-' . $_POST['work_to_month'] . '-' . '1');
            if ($from <= $to) {
                return true;
            } else {
                $this->form_validation->set_message('periodcheck', 'Work experience Period to must be greater than Period From.');
                return false;
            }
        } else {
            return true;
        }
    }

    //call back for check unique email for candidate
    public function check_unique_email($email)
    {

        //$sql = 'select * from JBIMS_candidates where email_id = '.$this->db->escape($email).' and isactive=\'1\'';
        $result = $this->master_model->getRecords('JBIMS_candidates', array('email_id' => $email, 'isactive' => '1'));
        //$result = $this->db->query($sql);
        if (count($result) > 0) {
            $this->form_validation->set_message('check_unique_email', 'Candidate Email ID already register.');
            return false;
        } else {
            return true;
        }
    }

    public function check_unique_mobile($mobile)
    {

        $result = $this->master_model->getRecords('JBIMS_candidates', array('mobile_no' => $mobile, 'isactive' => '1'));
        //$result = $this->db->query($sql);
        if (count($result) > 0) {
            $this->form_validation->set_message('check_unique_mobile', 'Candidate Mobile Number already register.');
            return false;
        } else {
            return true;
        }
    }
    public function mobileduplication()
    {
        $mobile = $_POST['mobile_no'];
        if ($mobile != "") {

            $prev_count = $this->master_model->getRecordCount('JBIMS_candidates', array('mobile_no' => $mobile, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok');
                echo json_encode($data_arr);
            } else {
                $str      = 'Candidate Mobile Number already register';
                $data_arr = array('ans' => 'exists', 'output' => $str);
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }
    public function emailduplication()
    {
        $email_id = $_POST['email_id'];
        if ($email_id != "") {

            $prev_count = $this->master_model->getRecordCount('JBIMS_candidates', array('email_id' => $email_id, 'isactive' => '1'));
            //echo $this->db->last_query();
            if ($prev_count == 0) {
                $data_arr = array('ans' => 'ok');
                echo json_encode($data_arr);
            } else {
                $str      = 'Candidate Email ID already register.';
                $data_arr = array('ans' => 'exists', 'output' => $str);
                echo json_encode($data_arr);
            }
        } else {
            echo 'error';
        }
    }
    //call back for check captcha server side
    public function check_captcha_userreg($code)
    {
        //return true;
        if (isset($_SESSION["regJBIMScaptcha"])) {
            //echo $code.'swa';die;
            if ($code == '' || $_SESSION["regJBIMScaptcha"] != $code) {
                $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
                return false;
            }
            if ($_SESSION["regJBIMScaptcha"] == $code) {
                //echo 'swati';die;
                return true;
            }

        } else {
            $this->form_validation->set_message('check_captcha_userreg', 'Invalid %s.');
            return false;
        }
    }

    ##---------check pincode/zipcode alredy exist or not -----------##
    public function checkpin()
    {
        $statecode = $_POST['statecode'];
        $pincode   = $_POST['pincode'];

        if ($statecode != "") {
            $this->db->where("$pincode BETWEEN start_pin AND end_pin");
            $prev_count = $this->master_model->getRecordCount('state_master', array('state_code' => $statecode));
            //echo $this->db->last_query();
            //exit;
            if ($prev_count == 0) {echo 'false';} else {echo 'true';}
        } else {
            echo 'false';
        }
    }

    // reload captcha functionality
    public function generatecaptchaajax()
    {
        //$this->load->helper('captcha');
        //$this->session->unset_userdata("regJBIMScaptcha");
        $session_name = 'regJBIMScaptcha';
        if (isset($_POST['session_name']) && $_POST['session_name'] != "") {
            $session_name = $this->security->xss_clean(trim($this->input->post('session_name')));
        }

        $this->load->model('Captcha_model');
        echo $captcha_img = $this->Captcha_model->generate_captcha_img($session_name);

    }

    //sending mails this function need membership number
    public function send_mail($membershipno)
    {
        $aCandidate = $this->master_model->getRecords('JBIMS_candidates', array('regnumber' => $membershipno));
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_reg'));

        $newstring1 = str_replace("#username#", "" . $aCandidate[0]['name'] . "", $emailerstr[0]['emailer_text']);
        $newstring2 = str_replace("#membershipno#", "" . $aCandidate[0]['regnumber'] . "", $newstring1);
        $newstring3 = str_replace("#name#", "" . $aCandidate[0]['name'] . "", $newstring2);
        $newstring4 = str_replace("#email#", "" . $aCandidate[0]['email_id'] . "", $newstring3);
        $newstring5 = str_replace("#transaction#", "" . "TRANSACTION ID" . "", $newstring4);
        $newstring6 = str_replace("#amount#", "" . $aCandidate[0]['payment'] . "", $newstring5);
        $newstring7 = str_replace("#Transaction_status#", "" . "Status" . "", $newstring6);
        $final_str  = str_replace("#date#", "" . date('Y-m-d h:s:i') . "", $newstring7);
        $to_email   = array($aCandidate[0]['email_id']);
        $info_arr   = array('to' => $to_email, 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);

        $this->Emailsending->mailsend($info_arr);
    }

    //sending SMS this function need membership number
    public function send_sms($membershipno)
    {
        $aCandidate = $this->master_model->getRecords('JBIMS_candidates', array('membershipno' => $membershipno));
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_reg'));

        //$sms_newstring = str_replace("#application_num#", "".$applicationNo."",  $emailerstr[0]['sms_text']);
        $sms_final_str = $emailerstr[0]['sms_text'];
        $this->master_model->send_sms($aCandidate[0]['mobile_no'], $sms_final_str);
        //$this->master_model->send_sms_trustsignal(intval($aCandidate[0]['mobile_no']),$sms_final_str,'template_id');
    }

    //candidate payment after form submit
    public function payment()
    {

        //print_r($_POST);exit;
        if (!isset($this->session->userdata['insertdata'])) {
            redirect(base_url() . 'JBIMS/self');
        }

        if ($this->input->post('form_type') == 'pay_form') {
            redirect(base_url() . 'JBIMS/insert_JBIMS_data');
        }

        $data = array('middle_content' => 'JBIMS/payment');
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    public function bank_preview()
    {
        $flag = 0;
        if (!isset($this->session->userdata['insertdata'])) {
            redirect(base_url() . 'JBIMS/bank');
        }

        if (!@$this->session->userdata['JBIMSmemberdata']['JBIMS_id']) {
            $flag = 0;
        }
//             elseif($this->session->userdata['JBIMSmemberdata']['amp_id'])
        //             {
        //                 //$result_data=$this->master_model->getRecords('JBIMS_candidates',array('id'=>$this->session->userdata['JBIMSmemberdata']['amp_id']));

//                 // if($result_data[0]['email_id']==$this->session->userdata['insertdata']['email_id'] && $result_data[0]['mobile_no']==$this->session->userdata['insertdata']['mobile_no'])
        //                 // {
        //                 //         $flag = 1 ;
        //                 // }
        //                     $data=array('middle_content'=>'JBIMS/bank_preview','row' => $this->session->userdata('insertdata'));
        //                     $this->load->view('JBIMS/common_view_fullwidth',$data);
        //             }
        if ($flag == 0) {
            $data = array('middle_content' => 'JBIMS/bank_preview', 'row' => $this->session->userdata('insertdata'));
            $this->load->view('JBIMS/common_view_fullwidth', $data);
        }

    }

    //insert candidate data call from payment function
    public function bank_addmember()
    {
        //print_r($_POST);exit;
        //print_r($_SESSION);exit;
        //print_r($this->session->userdata['JBIMSmemberdata']['amp_id']);

        $insertdata = $this->session->userdata('insertdata');
        $last_id    = $this->master_model->insertRecord('JBIMS_candidates', $insertdata, true);

        $userarr = array('JBIMS_id' => $last_id);
        $this->session->set_userdata('JBIMSmemberdata', $userarr);

        $applicationNo = generate_JBIMS_memreg($last_id);
        //update amp registration table
        $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
        $this->master_model->updateRecord('JBIMS_candidates', $update_mem_data, array('id' => $last_id));

        //create log activity
        $log_title   = "Candidate registration";
        $log_message = serialize($insertdata);
        $this->JBIMSmodel->create_log($log_title, $log_message);

        //get user information...
        $user_info = $this->master_model->getRecords('JBIMS_candidates', array('id' => $last_id));

        $upd_files    = array();
        $photo_file   = 'p_' . $applicationNo . '.jpg';
        $sign_file    = 's_' . $applicationNo . '.jpg';
        $idproof_file = 'pr_' . $applicationNo . '.jpg';

        /*    echo '</pre>$photo_file',$photo_file,'</pre>';
        echo '</pre>$sign_file',$sign_file,'</pre>';
        echo '</pre>$proof_file',$proof_file,'</pre>';
        echo '</pre>photograph',$user_info[0]['photograph'],'</pre>';
        echo '</pre>signature',$user_info[0]['signature'],'</pre>';*/

        if (@rename("./uploads/JBIMS/photograph/" . $user_info[0]['photograph'], "./uploads/JBIMS/photograph/" . $photo_file)) {
            $upd_files['photograph'] = $photo_file;}

        if (@rename("./uploads/JBIMS/idproof/" . $user_info[0]['idproof'], "./uploads/JBIMS/idproof/" . $idproof_file)) {
            $upd_files['idproof'] = $idproof_file;}

        if (@rename("./uploads/JBIMS/signature/" . $user_info[0]['signature'], "./uploads/JBIMS/signature/" . $sign_file)) {
            $upd_files['signature'] = $sign_file;}

        //print_r($upd_files);exit;
        if (count($upd_files) > 0) {
            $this->master_model->updateRecord('JBIMS_candidates', $upd_files, array('id' => $last_id));
        }

        //email to user
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank_nopayment'));
        if (count($emailerstr) > 0) {
            //echo 'in';
            $username         = $user_info[0]['name'];
            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
            $newstring1       = str_replace("#REG_NUM#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['emailer_text']);
            $newstring2       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
            $newstring3       = str_replace("#EMAIL#", "" . $user_info[0]['email_id'] . "", $newstring2);
            //    $newstring4 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $newstring3);
            $newstring5 = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $newstring3);
            $newstring6 = str_replace("#STATUS#", "Registration Successful", $newstring5);
            $final_str  = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($user_info[0]['createdon'])) . "", $newstring6);

            $info_arr = array('to' => '' . $user_info[0]['email_id'] . ',dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                'from'                 => $emailerstr[0]['from'],
                'subject'              => $emailerstr[0]['subject'],
                'message'              => $final_str,
                //'bcc'=>'swatiwatpade687@gmail.com'
            );

            $mempdf     = $this->memberpdf_bank($last_id);
            $attachment = array($mempdf);

            if ($attachment != '') {

                //    $sms_newstring = str_replace("#fee#", "".$payment_info[0]['amount']."",  $emailerstr[0]['sms_text']);
                //    $sms_newstring1 = str_replace("#transaction_no#", "".$payment_info[0]['transaction_no']."",  $sms_newstring);
                $sms_final_str = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['sms_text']);
                $this->master_model->send_sms($user_info[0]['mobile_no'], $sms_final_str);
                //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile_no']),$sms_final_str,'template_id');

                if ($this->Emailsending->mailsend_attch($info_arr, $attachment)) {
                    if ($user_info[0]['sponsor'] == 'bank') {
                        //get bank contact email id
                        $contact_mail_id = $user_info[0]['sponsor_contact_email'];

                        $emailerBankStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank'));
                        if (count($emailerBankStr) > 0) {
                            $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                            if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                            if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                            if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                            if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = 'NO';}

                            //    if(strtolower($user_info[0]['payment'])=='full'){ $payment = 'Full Paid'; }else{ $payment = ucwords($user_info[0]['payment']).' Installment'; }

                            if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                            if ($user_info[0]['sponsor_contact_phone'] != 0) {$sponsor_contact_phone = $user_info[0]['sponsor_contact_phone'];} else { $sponsor_contact_phone = '';}

                            $bankstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerBankStr[0]['emailer_text']);
                            $bankstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr1);
                            $bankstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr2);
                            $bankstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $bankstr3);
                            $bankstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $bankstr4);
                            $bankstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $bankstr5);
                            $bankstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $bankstr6);
                            $bankstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $bankstr7);
                            $bankstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $bankstr8);
                            $bankstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $bankstr9);
                            $bankstr11 = str_replace("#phone_no#", "" . $phone_no . "", $bankstr10);
                            $bankstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $bankstr11);
                            $bankstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $bankstr12);
                            $bankstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $bankstr13);
                            $bankstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $bankstr14);
                            $bankstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $bankstr15);
                            $bankstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $bankstr16);
                            $bankstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $bankstr17);
                            $bankstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $bankstr18);
                            //         $bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
                            //         $bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
                            //         $bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
                            //         $bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
                            $bankstr24 = str_replace("#till_present#", "" . $till_present . "", $bankstr19);
                            $bankstr25 = str_replace("#city#", "" . $user_info[0]['city'] . "", $bankstr24);
                            $bankstr26 = str_replace("#work_experiance#", "" . $work_experiance . "", $bankstr25);
                            //    $bankstr26 = str_replace("#payment#", "".$payment."",  $bankstr25);
                            //    $bankstr27 = str_replace("#TRANSACTION_NO#", "".$payment_info[0]['transaction_no']."",  $bankstr26);
                            //    $bankstr28 = str_replace("#AMOUNT#", "".$payment_info[0]['amount']."",  $bankstr27);
                            $bankstr29     = str_replace("#STATUS#", "Registration Successful", $bankstr26);
                            $bankstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($user_info[0]['createdon'])) . "", $bankstr29);
                            $bankstr31     = str_replace("#sponsor#", "" . $sponsor . "", $bankstr30);
                            $bankstr32     = str_replace("#sponsor_bank_name#", "" . $user_info[0]['sponsor_bank_name'] . "", $bankstr31);
                            $bankstr33     = str_replace("#sponsor_email#", "" . $user_info[0]['sponsor_email'] . "", $bankstr32);
                            $bankstr34     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr33);
                            $bankstr35     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr34);
                            $bankstr36     = str_replace("#sponsor_contact_designation#", "" . $user_info[0]['sponsor_contact_designation'] . "", $bankstr35);
                            $bankstr37     = str_replace("#sponsor_contact_std#", "" . $user_info[0]['sponsor_contact_std'] . "", $bankstr36);
                            $bankstr38     = str_replace("#sponsor_contact_phone#", "" . $sponsor_contact_phone . "", $bankstr37);
                            $bankstr39     = str_replace("#sponsor_contact_mobile#", "" . $user_info[0]['sponsor_contact_mobile'] . "", $bankstr38);
                            $bankstr40     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $bankstr39);
                            $bankstr41     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $bankstr40);
                            $final_bankstr = str_replace("#sponsor_contact_email#", "" . $user_info[0]['sponsor_contact_email'] . "", $bankstr41);

                            $bank_mail_arr = array(

                                //'to'=>'kyciibf@gmail.com',
                                'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                'from'    => $emailerBankStr[0]['from'],
                                'subject' => $emailerBankStr[0]['subject'],
                                'message' => $final_bankstr,
                            );
                            //print_r($bank_mail_arr);exit;
                            //$this->Emailsending->mailsend($bank_mail_arr);
                            $this->Emailsending->mailsend_attch($bank_mail_arr, $attachment);
                        }
                    }
                }

            }

        }

        redirect(base_url() . "JBIMS/thankyou_bank");

    }

    //Final page with session clear for bank only no payment
    public function thankyou_bank()
    {
        $this->session->unset_userdata("insertdata");
        $user_info_details = $this->master_model->getRecords('JBIMS_candidates', array('id' => $this->session->userdata['JBIMSmemberdata']['JBIMS_id']));
        $data              = array('middle_content' => 'JBIMS/thankyou_bank', 'user_info_details' => $user_info_details);
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    public function exampdf_bank($rid)
    {

        //$this->db->join('JBIMS_candidates','JBIMS_candidates.id=amp_payment_transaction.ref_id');
        $user_info_details = $this->master_model->getRecords('JBIMS_candidates', array('id' => base64_decode($rid)));

        if (empty($user_info_details)) {
            redirect(base_url() . 'JBIMS/bank');
        }

        //echo '<pre>';print_r($user_info_details);die;
        //if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
        $imagePath  = base_url() . 'uploads/JBIMS/photograph/' . $user_info_details[0]['photograph'];
        $imagePath1 = base_url() . 'uploads/JBIMS/signature/' . $user_info_details[0]['signature'];
        $imagePath2 = base_url() . 'uploads/JBIMS/idproof/' . $user_info_details[0]['idproof'];

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;">
			<td colspan="4" height="1" align="center" ><img src="' . base_url() . 'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Enrollment in JBIMS 2022-2023 ; February 2022</h3></strong></td></tr>
		<tr><td style="text-align:right"><img src="' . $imagePath . '" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['regnumber'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['name'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['gender'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['iibf_membership_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . date('d-M-Y', strtotime($user_info_details[0]['dob'])) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['address1'] . ' ' . $user_info_details[0]['address2'] . ' ' . $user_info_details[0]['address3'] . ' ' . $user_info_details[0]['address4'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['pincode_address'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['mobile_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['email_id'] . '</td>
				</tr>





				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['sponsor_bank_name'] . '</td>
				</tr>



				<tr>
					<td class="tablecontent2" width="51%">Registration Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> Successfully Registered </td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['createdon'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath2 . '" height="100" width="100" /></td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath1 . '" height="100" width="100" /></td>
				</tr>

				</tbody>
			</table>

			</td>
		</tr>
	</tbody>
</table>';
        //echo $html;die;
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
    //sent pdf on mail
    public function memberpdf_bank($rid)
    {

        //$this->db->join('JBIMS_candidates','JBIMS_candidates.id=amp_payment_transaction.ref_id');
        $user_info_details = $this->master_model->getRecords('JBIMS_candidates', array('id' => $rid));

        if (empty($user_info_details)) {
            redirect(base_url() . 'JBIMS/bank');
        }

        //echo '<pre>';print_r($user_info_details);die;
        //if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
        $imagePath  = base_url() . 'uploads/JBIMS/photograph/' . $user_info_details[0]['photograph'];
        $imagePath1 = base_url() . 'uploads/JBIMS/signature/' . $user_info_details[0]['signature'];
        $imagePath2 = base_url() . 'uploads/JBIMS/idproof/' . $user_info_details[0]['idproof'];

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;">
			<td colspan="4" height="1" align="center" ><img src="' . base_url() . 'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Enrollment in JBIMS 2022-2023 ; February 2022</h3></strong></td></tr>
		<tr><td style="text-align:right"><img src="' . $imagePath . '" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['regnumber'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['name'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['gender'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['iibf_membership_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . date('d-M-Y', strtotime($user_info_details[0]['dob'])) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['address1'] . ' ' . $user_info_details[0]['address2'] . ' ' . $user_info_details[0]['address3'] . ' ' . $user_info_details[0]['address4'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['pincode_address'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['mobile_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['email_id'] . '</td>
				</tr>





				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['sponsor_bank_name'] . '</td>
				</tr>



				<tr>
					<td class="tablecontent2" width="51%">Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> Successfully Registered</td>
				</tr>



				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath2 . '" height="100" width="100" /></td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath1 . '" height="100" width="100" /></td>
				</tr>

				</tbody>
			</table>

			</td>
		</tr>
	</tbody>
</table>';
        //echo $html;die;
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
        $file = $pdf->Output('exam_JBIMS.pdf', 'F');
        //file_put_contents($pdfFilePath, $file);
        return 'JBIMS_bank.pdf';

    }

    //daownload manually for bank
    public function memebrpdf_dwn_bank()
    {
        $rid               = $this->uri->segment('3');
        $user_info_details = $this->master_model->getRecords('JBIMS_candidates', array('regnumber' => $rid));

        if (empty($user_info_details)) {
            redirect(base_url() . 'JBIMS/bank');
        }

        //echo '<pre>';print_r($user_info_details);die;
        //if($user_info_details[0]['status']=='1'){ $status='Success';}else{ $status='Unsuccess';}
        $imagePath  = base_url() . 'uploads/JBIMS//photograph/' . $user_info_details[0]['photograph'];
        $imagePath1 = base_url() . 'uploads/JBIMS//signature/' . $user_info_details[0]['signature'];
        $imagePath2 = base_url() . 'uploads/JBIMS//idproof/' . $user_info_details[0]['idproof'];

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;">
			<td colspan="4" height="1" align="center" ><img src="' . base_url() . 'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Exam Enrolment Acknowledgement</h3></strong></td></tr>
		<tr><td style="text-align:right"><img src="' . $imagePath . '" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['regnumber'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['name'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['gender'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['iibf_membership_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . date('d-M-Y', strtotime($user_info_details[0]['dob'])) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['address1'] . ' ' . $user_info_details[0]['address2'] . ' ' . $user_info_details[0]['address3'] . ' ' . $user_info_details[0]['address4'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['pincode_address'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['mobile_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['email_id'] . '</td>
				</tr>





				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . ucfirst($user_info_details[0]['sponsor_bank_name']) . '</td>
				</tr>



				<tr>
					<td class="tablecontent2" width="51%">Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> Successfully Registered</td>
				</tr>



				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath2 . '" height="100" width="100" /></td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath1 . '" height="100" width="100" /></td>
				</tr>

				</tbody>
			</table>

			</td>
		</tr>
	</tbody>
</table>';
        //echo $html;die;
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
        $file = $pdf->Output('exam_JBIMS_' . $rid . '.pdf', 'D');

    }
    //insert candidate data call from payment function
    public function insert_JBIMS_data()
    {
        //print_r($_POST);exit;
        //print_r($_SESSION);exit;
        //print_r($this->session->userdata['JBIMSmemberdata']['JBIMS_id']);

        $insertdata = $this->session->userdata('insertdata');
        $last_id    = $this->master_model->insertRecord('JBIMS_candidates', $insertdata, true);

        $userarr = array('JBIMS_id' => $last_id);
        $this->session->set_userdata('JBIMSmemberdata', $userarr);

        //create log activity
        $log_title   = "Candidate registration";
        $log_message = serialize($insertdata);
        $this->JBIMSmodel->create_log($log_title, $log_message);

        redirect(base_url() . "JBIMS/make_payment");

    }

    //Final page with session clear
    public function thankyou()
    {
        $this->session->unset_userdata("insertdata");
        $data = array('middle_content' => 'JBIMS/thankyou');
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    //search candidate for installment payment
    public function login()
    {
        $aCandidate = array();
        if ($this->input->post('form_type') == 'search_form') {
            $this->form_validation->set_rules('searchStr', 'Enter Name or Membership no.', 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) {
                $searchStr      = $this->input->post('searchStr');
                $wherecondition = "(name LIKE '%$searchStr%' OR regnumber = '$searchStr')";
                $this->db->where($wherecondition);
                //$this->db->or_where('regnumber',$searchStr);
                //$this->db->like('name',$searchStr);

                $aCandidate = $this->master_model->getRecords('JBIMS_candidates', array('isactive' => '1'));
                //echo $this->db->last_query();exit;

            }
        }
        $data = array('middle_content' => 'JBIMS/login', 'aCandidate' => $aCandidate);
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    //candidate details and installment
    public function installment($membershipno = 0)
    {
        $aCandidate = $this->master_model->getRecords('JBIMS_candidates', array('regnumber' => $membershipno));
        if (empty($aCandidate)) {
            redirect(base_url() . 'JBIMS/self');
        }

        if ($this->input->post('form_type') == 'installment_form') {

            //$update_info = array('payment'=>$this->input->post('payment'));

            $userarr = array('JBIMS_id' => $aCandidate[0]['id']);
            $this->session->set_userdata('JBIMSmemberdata', $userarr);

            $userarr = array('payment' => $this->input->post('payment'));
            $this->session->set_userdata('insertdata', $userarr);

            redirect(base_url() . "JBIMS/make_payment");

            //$this->master_model->updateRecord('JBIMS_candidates',$update_info,array('regnumber'=>$membershipno));

            //log create for installment
            /*    $log_title ="Installment payment";
        $update_info['membershipno'] = $membershipno;
        $log_message = serialize($update_info);
        $this->JBIMSmodel->create_log($log_title, $log_message);

        redirect(base_url().'JBIMS/thankyou');*/
        }

        $data = array('middle_content' => 'JBIMS/installment', 'aCandidate' => $aCandidate);
        $this->load->view('JBIMS/common_view_fullwidth', $data);
    }

    //code done
    public function make_payment()
    {
        $cgst_rate = $sgst_rate = $igst_rate = $tax_type = '';
        $cgst_amt  = $sgst_amt  = $igst_amt  = '';
        $cs_total  = $igst_total  = '';
        $flag      = 1;

        // TO do:
        // Validate reg no in DB
        //$_REQUEST['regno'] = "ODExODU5OTE1";
        //$regno = base64_decode($_REQUEST['regno']);

        $regno = $this->session->userdata['JBIMSmemberdata']['JBIMS_id'];

        if (!empty($regno)) {
            $inst_state = $this->master_model->getRecords('JBIMS_candidates', array('id' => $regno), array('state', 'gstin_no', 'bank_state', 'sponsor')); //state ,'status'=>'0'/

            if ($inst_state[0]['sponsor'] == 'self') {
                $state = $inst_state[0]['state'];
            } else {
                $state = $inst_state[0]['bank_state'];
            }

        }
        //print_r($state); //exit;
        //echo '<pre>',print_r($this->session->userdata('proforma')),'</pre>';exit;
        if (isset($_POST['processPayment']) && $_POST['processPayment']) {

            $pg_name  = $this->input->post('pg_name');
            $gstin_no = $inst_state[0]['gstin_no'];
            //echo 'state',$state;exit;

            if (!empty($state)) {
                //get state code,state name,state number.
                $getstate = $this->master_model->getRecords('state_master', array('state_code' => $state, 'state_delete' => '0'));

            }

            if (isset($this->session->userdata['insertdata']['payment'])) {
                if ($state == 'MAH') {

                    if ($this->session->userdata['insertdata']['payment'] == 'full') {
                        $cgst_rate      = $this->config->item('JBIMS_cgst_rate');
                        $sgst_rate      = $this->config->item('JBIMS_sgst_rate');
                        $cgst_amt       = $this->config->item('JBIMS_full_sgst_amt');
                        $sgst_amt       = $this->config->item('JBIMS_full_cgst_amt');
                        $tax_type       = 'Intra';
                        $amount         = $this->config->item('JBIMS_full_cs_total');
                        $fee_amount     = $this->config->item('JBIMS_full_fee');
                        $cs_total       = $amount;
                        $payment_option = 4;
                    } elseif ($this->session->userdata['insertdata']['payment'] == 'first') {
                        $cgst_rate      = $this->config->item('JBIMS_cgst_rate');
                        $sgst_rate      = $this->config->item('JBIMS_sgst_rate');
                        $cgst_amt       = $this->config->item('JBIMS_first_sgst_amt');
                        $sgst_amt       = $this->config->item('JBIMS_first_cgst_amt');
                        $tax_type       = 'Intra';
                        $amount         = $this->config->item('JBIMS_first_cs_total');
                        $fee_amount     = $this->config->item('JBIMS_first_fee');
                        $cs_total       = $amount;
                        $payment_option = 1;
                    } elseif ($this->session->userdata['insertdata']['payment'] == 'second') {
                        $cgst_rate      = $this->config->item('JBIMS_cgst_rate');
                        $sgst_rate      = $this->config->item('JBIMS_sgst_rate');
                        $cgst_amt       = $this->config->item('JBIMS_second_sgst_amt');
                        $sgst_amt       = $this->config->item('JBIMS_second_cgst_amt');
                        $tax_type       = 'Intra';
                        $amount         = $this->config->item('JBIMS_second_cs_total');
                        $fee_amount     = $this->config->item('JBIMS_second_fee');
                        $cs_total       = $amount;
                        $payment_option = 2;
                    } elseif ($this->session->userdata['insertdata']['payment'] == 'third') {
                        $cgst_rate      = $this->config->item('JBIMS_cgst_rate');
                        $sgst_rate      = $this->config->item('JBIMS_sgst_rate');
                        $cgst_amt       = $this->config->item('JBIMS_third_sgst_amt');
                        $sgst_amt       = $this->config->item('JBIMS_third_cgst_amt');
                        $tax_type       = 'Intra';
                        $amount         = $this->config->item('JBIMS_third_cs_total');
                        $fee_amount     = $this->config->item('JBIMS_third_fee');
                        $cs_total       = $amount;
                        $payment_option = 3;
                    }

                } else {
                    if ($this->session->userdata['insertdata']['payment'] == 'full') {
                        $igst_rate      = $this->config->item('JBIMS_igst_rate');
                        $igst_amt       = $this->config->item('JBIMS_full_igst_amt');
                        $tax_type       = 'Inter';
                        $amount         = $this->config->item('JBIMS_full_igst_tot');
                        $fee_amount     = $this->config->item('JBIMS_full_fee');
                        $igst_total     = $amount;
                        $payment_option = 4;

                    } elseif ($this->session->userdata['insertdata']['payment'] == 'first') {
                        $igst_rate      = $this->config->item('JBIMS_igst_rate');
                        $igst_amt       = $this->config->item('JBIMS_first_igst_amt');
                        $tax_type       = 'Inter';
                        $amount         = $this->config->item('JBIMS_first_igst_tot');
                        $fee_amount     = $this->config->item('JBIMS_first_fee');
                        $igst_total     = $amount;
                        $payment_option = 1;
                    } elseif ($this->session->userdata['insertdata']['payment'] == 'second') {
                        $igst_rate      = $this->config->item('JBIMS_igst_rate');
                        $igst_amt       = $this->config->item('JBIMS_second_igst_amt');
                        $tax_type       = 'Inter';
                        $amount         = $this->config->item('JBIMS_second_igst_tot');
                        $fee_amount     = $this->config->item('JBIMS_second_fee');
                        $igst_total     = $amount;
                        $payment_option = 2;
                    } elseif ($this->session->userdata['insertdata']['payment'] == 'third') {
                        $igst_rate      = $this->config->item('JBIMS_igst_rate');
                        $igst_amt       = $this->config->item('JBIMS_third_igst_amt');
                        $tax_type       = 'Inter';
                        $amount         = $this->config->item('JBIMS_third_igst_tot');
                        $fee_amount     = $this->config->item('JBIMS_third_fee');
                        $igst_total     = $amount;
                        $payment_option = 3;
                    }
                }
            } else {
                redirect(base_url() . 'JBIMS/self');
            }

            //$MerchantOrderNo = generate_order_id("reg_sbi_order_id");

            // Create transaction
            $insert_data = array(
                'gateway'        => "sbiepay",
                'amount'         => $amount,
                'date'           => date('Y-m-d H:i:s'),
                'ref_id'         => $regno,
                'description'    => "JBIMS Membership Registration",
                'pay_type'       => 1,
                'status'         => 2,
                //'receipt_no'  => $MerchantOrderNo,
                'pg_flag'        => 'JBIMS',
                'payment_option' => $payment_option,
                //'pg_other_details'=>$custom_field
            );

            $pt_id = $this->master_model->insertRecord('JBIMS_payment_transaction', $insert_data, true);

            $MerchantOrderNo = amp_sbi_order_id($pt_id);

            //Member registration
            //Ref1 = orderid
            //Ref2 = iibfJBIMS
            //Ref3 = primary key of JBIMS member registation table
            //Ref4 = JBIMS+ registration year month ex (JBIMS201704)
            $yearmonth    = date('Ym');
            $custom_field = $MerchantOrderNo . "^iibfJBIMS^" . $regno . "^" . 'JBIMS' . $yearmonth;

            // update receipt no. in payment transaction -
            $update_data = array('receipt_no' => $MerchantOrderNo, 'pg_other_details' => $custom_field);
            $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('id' => $pt_id));

            $invoice_insert_array = array('pay_txn_id' => $pt_id,
                'receipt_no'                               => $MerchantOrderNo,
                'exam_code'                                => '',
                'state_of_center'                          => $state,
                //'gstin_no'=>$gstin_no,
                //'member_no'=>$member_no,
                'app_type'                                 => 'J', // J for JBIMS
                'service_code'                             => $this->config->item('JBIMS_service_code'),
                'qty'                                      => '1',
                'state_code'                               => $getstate[0]['state_no'],
                'state_name'                               => $getstate[0]['state_name'],
                'tax_type'                                 => $tax_type,
                'fee_amt'                                  => $fee_amount,
                'cgst_rate'                                => $cgst_rate,
                'cgst_amt'                                 => $cgst_amt,
                'sgst_rate'                                => $sgst_rate,
                'sgst_amt'                                 => $sgst_amt,
                'igst_rate'                                => $igst_rate,
                'igst_amt'                                 => $igst_amt,
                'cs_total'                                 => $cs_total,
                'igst_total'                               => $igst_total,
                'exempt'                                   => $getstate[0]['exempt'],
                'created_on'                               => date('Y-m-d H:i:s'),
            );

            $inser_id = $this->master_model->insertRecord('exam_invoice', $invoice_insert_array);

            $MerchantCustomerID = $regno;

            //paymentgaway
            if ($pg_name == 'sbi') {
                include_once APPPATH . 'third_party/SBI_ePay/CryptAES.php';
                $key          = $this->config->item('sbi_m_key');
                $merchIdVal   = $this->config->item('sbi_merchIdVal');
                $AggregatorId = $this->config->item('sbi_AggregatorId');

                $pg_success_url      = base_url() . "JBIMS/sbitranssuccess";
                $pg_fail_url         = base_url() . "JBIMS/sbitransfail";
                $data["pg_form_url"] = $this->config->item('sbi_pg_form_url'); // SBI ePay form URL
                $data["merchIdVal"]  = $merchIdVal;

                $EncryptTrans = $merchIdVal . "|DOM|IN|INR|" . $amount . "|" . $custom_field . "|" . $pg_success_url . "|" . $pg_fail_url . "|" . $AggregatorId . "|" . $MerchantOrderNo . "|" . $MerchantCustomerID . "|NB|ONLINE|ONLINE";
                $aes          = new CryptAES();
                $aes->set_key(base64_decode($key));
                $aes->require_pkcs5();

                $EncryptTrans = $aes->encrypt($EncryptTrans);

                $data["EncryptTrans"] = $EncryptTrans; // SBI encrypted form field value
                $this->load->view('pg_sbi_form', $data);

            } elseif ($pg_name == 'billdesk') {

                $update_payment_data = array('gateway' => 'billdesk');
                $this->master_model->updateRecord('payment_transaction', $update_payment_data, array('id' => $pt_id));

                $billdesk_res = $this->billdesk_pg_model->init_payment_request($MerchantOrderNo, $amount, $regno, $regno, '', 'JBIMS/handle_billdesk_response', '', '', '', $custom_field_billdesk);

                if (count($billdesk_res) > 0 && $billdesk_res['status'] == 'ACTIVE') {
                    $data['bdorderid']      = $billdesk_res['bdorderid'];
                    $data['token']          = $billdesk_res['token'];
                    $data['responseXHRUrl'] = $billdesk_res['responseXHRUrl'];
                    $data['returnUrl']      = $billdesk_res['returnUrl'];
                    $this->load->view('pg_billdesk/pg_billdesk_form', $data);
                } else {
                    $this->session->set_flashdata('flsh_msg', 'Transaction failed...!');
                    redirect(base_url() . 'JBIMS/self');
                }
            }
            //Proforma invoice generation
            /*  if($this->session->userdata('p_invoice')==1)
        {
        redirect(base_url()."JBIMS/download_P_invoice");
        }*/
        } else {
            //$data["regno"] = $_REQUEST['regno'];
            $data['show_billdesk_option_flag'] = 1;
            $this->load->view('pg_sbi/make_payment_page', $data);
        }

    }

    public function sbitranssuccess()
    {
        //delete_cookie('regid');
        //print_r($_REQUEST['encData']);
        //$_REQUEST['encData']='6N7QR1B/Kz1O3Q+GWcfdJcY7NhHGxCp8SbDgjXOc3kJkWolrLAg6NifwqMm9VBAzwCyNY2JWDt1v4HcN8yFAAw36jyZ0oopYmlVFX06tNlMHAWqLGS+S3EGynsHAPpxb7pQsObd6nFBvXEC2MVrsk3tn65zCjlxQ7+vg4Ryv3ZCGDC1Y+jicNwfvNBUOvAdvCyCe0lpM8y/uo+NzFQIybA==';
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
            //print_r($q_details);exit;
            if ($q_details) {
                if ($q_details[2] == "SUCCESS") {

                    $get_user_regnum_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'id,ref_id,status,payment_option');
                    //check user payment status is updated by s2s or not
                    //    print_r($get_user_regnum_info);exit;

                    if ($get_user_regnum_info[0]['status'] == 2) {
                        if ($get_user_regnum_info[0]['payment_option'] == 1 || $get_user_regnum_info[0]['payment_option'] == 4) {
                            $reg_id = $get_user_regnum_info[0]['ref_id'];
                            //$applicationNo = generate_mem_reg_num();
                            //Get membership number from 'JBIMS_membershipno' and update in 'JBIMS_candidates'
                            $applicationNo = generate_JBIMS_memreg($reg_id);
                            //update JBIMS registration table
                            $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
                            $this->master_model->updateRecord('JBIMS_candidates', $update_mem_data, array('id' => $reg_id));
                            //get user information...
                            //$user_info=$this->master_model->getRecords('JBIMS_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
                            $user_info = $this->master_model->getRecords('JBIMS_candidates', array('id' => $reg_id));

                            $update_data = array('member_regnumber' => $applicationNo, 'sponsor' => $user_info[0]['sponsor'], 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                            $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                            //get payment details

                            //Query to get Payment details
                            $payment_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $user_info[0]['regnumber']), 'transaction_no,date,amount');

                            $upd_files    = array();
                            $photo_file   = 'p_' . $applicationNo . '.jpg';
                            $sign_file    = 's_' . $applicationNo . '.jpg';
                            $idproof_file = 'pr_' . $applicationNo . '.jpg';

                            /*    echo '</pre>$photo_file',$photo_file,'</pre>';
                            echo '</pre>$sign_file',$sign_file,'</pre>';
                            echo '</pre>$proof_file',$proof_file,'</pre>';
                            echo '</pre>photograph',$user_info[0]['photograph'],'</pre>';
                            echo '</pre>signature',$user_info[0]['signature'],'</pre>';*/

                            if (@rename("./uploads/JBIMS/photograph/" . $user_info[0]['photograph'], "./uploads/JBIMS/photograph/" . $photo_file)) {
                                $upd_files['photograph'] = $photo_file;}

                            if (@rename("./uploads/JBIMS/idproof/" . $user_info[0]['idproof'], "./uploads/JBIMS/idproof/" . $idproof_file)) {
                                $upd_files['idproof'] = $idproof_file;}

                            if (@rename("./uploads/JBIMS/signature/" . $user_info[0]['signature'], "./uploads/JBIMS/signature/" . $sign_file)) {
                                $upd_files['signature'] = $sign_file;}

                            //print_r($upd_files);exit;
                            if (count($upd_files) > 0) {
                                $this->master_model->updateRecord('JBIMS_candidates', $upd_files, array('id' => $reg_id));
                            }

                            //Manage Log
                            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;

                            $this->log_model->logJBIMStransaction("sbiepay", $pg_response, $responsedata[2]);

                            //email to user
                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer'));
                            if (count($emailerstr) > 0) {
                                //echo 'in';
                                $username         = $user_info[0]['name'];
                                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                                $newstring1       = str_replace("#REG_NUM#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['emailer_text']);
                                $newstring2       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
                                $newstring3       = str_replace("#EMAIL#", "" . $user_info[0]['email_id'] . "", $newstring2);
                                $newstring4       = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring3);
                                $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                                $newstring6       = str_replace("#STATUS#", "Transaction Successful", $newstring5);
                                $newstring7       = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $newstring6);
                                $final_str        = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring7);

                                $info_arr = array('to' => '' . $user_info[0]['email_id'] . ',dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                    'from'                 => $emailerstr[0]['from'],
                                    'subject'              => $emailerstr[0]['subject'],
                                    'message'              => $final_str,
                                    //'bcc'=>'dd.trg1@iibf.org.in'
                                );
                                //    echo '<pre>',print_r($info_arr),'</pre>';
                                //$this->send_mail($applicationNo);
                                //$this->send_sms($applicationNo);

                                //Invoice generation
                                $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id'], 'exam_code' => 0, 'app_type' => 'J'));

                                if (count($getinvoice_number) > 0) {
                                    $invoiceNumber = generate_JBIMS_invoice_number($getinvoice_number[0]['invoice_id']);
                                    //    echo '<pre>',print_r($invoiceNumber),'</pre>';
                                    if ($invoiceNumber) {
                                        $invoiceNumber = $this->config->item('JBIMS_invoice_no_prefix') . $invoiceNumber;
                                    }
                                    $attachment  = '';
                                    $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                    $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                    $attachpath = genarate_JBIMS_invoice($getinvoice_number[0]['invoice_id']);
                                    $mempdf     = $this->memberpdf($MerchantOrderNo);
                                    $attachment = array($attachpath, $mempdf);
                                    //echo $this->db->last_query();
                                    //echo '<pre>update_data',print_r($update_data),'</pre>';
                                    //echo '<pre>',print_r($attachpath),'</pre>';

                                }
                                //echo '<pre>user_info',print_r($user_info),'</pre>';exit;
                                //exit;
                                if ($attachment != '') {

                                    $sms_newstring  = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $emailerstr[0]['sms_text']);
                                    $sms_newstring1 = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring);
                                    $sms_final_str  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $sms_newstring1);
                                    $this->master_model->send_sms($user_info[0]['mobile_no'], $sms_final_str);
                                    //$this->master_model->send_sms_trustsignal(intval(user_info[0]['mobile_no']),$sms_final_str,'template_id');

                                    //if($this->Emailsending->mailsend($info_arr))
                                    //if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
                                    //if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
                                    if ($this->Emailsending->mailsend_attch($info_arr, $attachment)) {

                                        //email send to sk datta and kavan for self sponsor
                                        if ($user_info[0]['sponsor'] == 'self') {
                                            $emailerJBIMSStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_self'));
                                            if (count($emailerJBIMSStr) > 0) {
                                                $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                                if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                                if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                                if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                                if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = '';}

                                                if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                                if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                                $JBIMSstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerJBIMSStr[0]['emailer_text']);
                                                $JBIMSstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr1);
                                                $JBIMSstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr2);
                                                $JBIMSstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $JBIMSstr3);
                                                $JBIMSstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $JBIMSstr4);
                                                $JBIMSstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $JBIMSstr5);
                                                $JBIMSstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $JBIMSstr6);
                                                $JBIMSstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $JBIMSstr7);
                                                $JBIMSstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $JBIMSstr8);
                                                $JBIMSstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $JBIMSstr9);
                                                $JBIMSstr11 = str_replace("#phone_no#", "" . $phone_no . "", $JBIMSstr10);
                                                $JBIMSstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $JBIMSstr11);
                                                $JBIMSstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $JBIMSstr12);
                                                $JBIMSstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $JBIMSstr13);
                                                $JBIMSstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $JBIMSstr14);
                                                $JBIMSstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $JBIMSstr15);
                                                $JBIMSstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $JBIMSstr16);
                                                $JBIMSstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $JBIMSstr17);
                                                $JBIMSstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $JBIMSstr18);
                                                //     $JBIMSstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $JBIMSstr19);
                                                //     $JBIMSstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $JBIMSstr20);
                                                //     $JBIMSstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $JBIMSstr21);
                                                //     $JBIMSstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $JBIMSstr22);
                                                $JBIMSstr24     = str_replace("#till_present#", "" . $till_present . "", $JBIMSstr19);
                                                $JBIMSstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $JBIMSstr24);
                                                $JBIMSstr26     = str_replace("#payment#", "" . $payment . "", $JBIMSstr25);
                                                $JBIMSstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $JBIMSstr26);
                                                $JBIMSstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $JBIMSstr27);
                                                $JBIMSstr29     = str_replace("#STATUS#", "Transaction Successful", $JBIMSstr28);
                                                $JBIMSstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $JBIMSstr29);
                                                $JBIMSstr31     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $JBIMSstr30);
                                                $JBIMSstr32     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $JBIMSstr31);
                                                $final_JBIMSstr = str_replace("#sponsor#", "" . $sponsor . "", $JBIMSstr32);

                                                $JBIMS_mail_arr = array(
                                                    'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                    //'to'=>'21bhavsartejasvi@gmail.com',
                                                    //'to'=>'dharmvirm@iibf.org.in,training@iibf.org.in',
                                                    //'to'=>'kyciibf@gmail.com',
                                                    'from'    => $emailerJBIMSStr[0]['from'],
                                                    'subject' => $emailerJBIMSStr[0]['subject'],
                                                    'message' => $final_JBIMSstr,
                                                );

                                                //$this->Emailsending->mailsend($JBIMS_mail_arr);
                                                $this->Emailsending->mailsend_attch($JBIMS_mail_arr, $attachpath);
                                            }

                                        }
                                        //email send to sk datta and kavan for bank sponsor
                                        if ($user_info[0]['sponsor'] == 'bank') {
                                            //get bank contact email id
                                            $contact_mail_id = $user_info[0]['sponsor_contact_email'];

                                            $emailerBankStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank'));
                                            if (count($emailerBankStr) > 0) {
                                                $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                                if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                                if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                                if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                                if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = 'NO';}

                                                if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                                if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                                if ($user_info[0]['sponsor_contact_phone'] != 0) {$sponsor_contact_phone = $user_info[0]['sponsor_contact_phone'];} else { $sponsor_contact_phone = '';}

                                                $bankstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerBankStr[0]['emailer_text']);
                                                $bankstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr1);
                                                $bankstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr2);
                                                $bankstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $bankstr3);
                                                $bankstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $bankstr4);
                                                $bankstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $bankstr5);
                                                $bankstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $bankstr6);
                                                $bankstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $bankstr7);
                                                $bankstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $bankstr8);
                                                $bankstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $bankstr9);
                                                $bankstr11 = str_replace("#phone_no#", "" . $phone_no . "", $bankstr10);
                                                $bankstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $bankstr11);
                                                $bankstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $bankstr12);
                                                $bankstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $bankstr13);
                                                $bankstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $bankstr14);
                                                $bankstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $bankstr15);
                                                $bankstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $bankstr16);
                                                $bankstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $bankstr17);
                                                $bankstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $bankstr18);
                                                //         $bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
                                                //         $bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
                                                //         $bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
                                                //         $bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
                                                $bankstr24     = str_replace("#till_present#", "" . $till_present . "", $bankstr19);
                                                $bankstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $bankstr24);
                                                $bankstr26     = str_replace("#payment#", "" . $payment . "", $bankstr25);
                                                $bankstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $bankstr26);
                                                $bankstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $bankstr27);
                                                $bankstr29     = str_replace("#STATUS#", "Transaction Successful", $bankstr28);
                                                $bankstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $bankstr29);
                                                $bankstr31     = str_replace("#sponsor#", "" . $sponsor . "", $bankstr30);
                                                $bankstr32     = str_replace("#sponsor_bank_name#", "" . $user_info[0]['sponsor_bank_name'] . "", $bankstr31);
                                                $bankstr33     = str_replace("#sponsor_email#", "" . $user_info[0]['sponsor_email'] . "", $bankstr32);
                                                $bankstr34     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr33);
                                                $bankstr35     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr34);
                                                $bankstr36     = str_replace("#sponsor_contact_designation#", "" . $user_info[0]['sponsor_contact_designation'] . "", $bankstr35);
                                                $bankstr37     = str_replace("#sponsor_contact_std#", "" . $user_info[0]['sponsor_contact_std'] . "", $bankstr36);
                                                $bankstr38     = str_replace("#sponsor_contact_phone#", "" . $sponsor_contact_phone . "", $bankstr37);
                                                $bankstr39     = str_replace("#sponsor_contact_mobile#", "" . $user_info[0]['sponsor_contact_mobile'] . "", $bankstr38);
                                                $bankstr40     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $bankstr39);
                                                $bankstr41     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $bankstr40);
                                                $final_bankstr = str_replace("#sponsor_contact_email#", "" . $user_info[0]['sponsor_contact_email'] . "", $bankstr41);

                                                $bank_mail_arr = array(
                                                    'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                    'from'    => $emailerBankStr[0]['from'],
                                                    'subject' => $emailerBankStr[0]['subject'],
                                                    'message' => $final_bankstr,
                                                );
                                                //print_r($bank_mail_arr);exit;
                                                //$this->Emailsending->mailsend($bank_mail_arr);
                                                $this->Emailsending->mailsend_attch($bank_mail_arr, $attachpath);
                                            }
                                        }

                                        $this->session->set_flashdata('success', 'JBIMS registration has been done successfully !!');
                                        redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                                    } else {
                                        redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                                    }

                                } else {
                                    redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                                }

                                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                            } else {
                                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                            }
                        } else if ($get_user_regnum_info[0]['payment_option'] == 2 || $get_user_regnum_info[0]['payment_option'] == 3) {

                            $payment_option = '';
                            if ($get_user_regnum_info[0]['payment_option'] == 2) {
                                $payment_option = 'second';
                            } else if ($get_user_regnum_info[0]['payment_option'] == 3) {
                                $payment_option = 'Full';
                            }

                            $reg_id = $get_user_regnum_info[0]['ref_id'];

                            //update JBIMS registration table with installment status
                            $update_mem_data = array('payment' => $payment_option);
                            $this->master_model->updateRecord('JBIMS_candidates', $update_mem_data, array('id' => $reg_id));
                            //$user_info=$this->master_model->getRecords('JBIMS_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
                            $user_info = $this->master_model->getRecords('JBIMS_candidates', array('id' => $reg_id));

                            //update payment transaction
                            $update_data = array('member_regnumber' => $user_info[0]['regnumber'], 'sponsor' => $user_info[0]['sponsor'], 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                            $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

                            //maintain log in for updated transaction
                            $log_title                   = "Installment payment";
                            $update_info['membershipno'] = $user_info[0]['regnumber'];
                            $log_message                 = serialize($update_mem_data);
                            $this->JBIMSmodel->create_log($log_title, $log_message);

                            //Query to get Payment details
                            $payment_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $user_info[0]['regnumber']), 'transaction_no,date,amount');

                            //email to user
                            $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer'));
                            if (count($emailerstr) > 0) {
                                $username         = $user_info[0]['name'];
                                $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                                $newstring1       = str_replace("#REG_NUM#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['emailer_text']);
                                $newstring2       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
                                $newstring3       = str_replace("#EMAIL#", "" . $user_info[0]['email_id'] . "", $newstring2);
                                $newstring4       = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring3);
                                $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                                $newstring6       = str_replace("#STATUS#", "Transaction Successful", $newstring5);
                                $newstring7       = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $newstring6);

                                $final_str = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring7);

                                $info_arr = array('to' => '' . $user_info[0]['email_id'] . ',dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                    'from'                 => $emailerstr[0]['from'],
                                    'subject'              => $emailerstr[0]['subject'],
                                    'message'              => $final_str,
                                    //'bcc'=>'dd.trg1@iibf.org.in'
                                );
                                //$this->send_mail($applicationNo);
                                //$this->send_sms($applicationNo);

                                //Invoice generation
                                $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));

                                if (count($getinvoice_number) > 0) {
                                    $invoiceNumber = generate_JBIMS_invoice_number($getinvoice_number[0]['invoice_id']);
                                    //    echo '<pre>',print_r($invoiceNumber),'</pre>';
                                    if ($invoiceNumber) {
                                        $invoiceNumber = $this->config->item('JBIMS_invoice_no_prefix') . $invoiceNumber;
                                    }
                                    $attachment  = '';
                                    $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $user_info[0]['regnumber'], 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                    $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                    $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                    $attachpath = genarate_JBIMS_invoice($getinvoice_number[0]['invoice_id']);
                                    $mempdf     = $this->memberpdf($MerchantOrderNo);
                                    $attachment = array($attachpath, $mempdf);
                                    //echo $this->db->last_query();
                                    //echo '<pre>update_data',print_r($update_data),'</pre>';
                                    //echo '<pre>',print_r($attachpath),'</pre>';

                                }

                                if ($attachment != '') {
                                    $sms_newstring  = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $emailerstr[0]['sms_text']);
                                    $sms_newstring1 = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring);
                                    $sms_final_str  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $sms_newstring1);
                                    $this->master_model->send_sms($user_info[0]['mobile_no'], $sms_final_str);
                                    //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile_no']),$sms_final_str,'template_id');

                                    //$this->Emailsending->mailsend($info_arr);
                                    if ($this->Emailsending->mailsend_attch($info_arr, $attachment)) {

                                        //email send to sk datta and kavan for JBIMS sponsor
                                        if ($user_info[0]['sponsor'] == 'self') {
                                            $emailerJBIMSStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_self'));
                                            if (count($emailerJBIMSStr) > 0) {
                                                $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                                if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                                if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                                if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                                if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = 'No';}

                                                if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}
                                                //echo $payment;exit;
                                                if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                                $JBIMSstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerJBIMSStr[0]['emailer_text']);
                                                $JBIMSstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr1);
                                                $JBIMSstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr2);
                                                $JBIMSstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $JBIMSstr3);
                                                $JBIMSstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $JBIMSstr4);
                                                $JBIMSstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $JBIMSstr5);
                                                $JBIMSstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $JBIMSstr6);
                                                $JBIMSstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $JBIMSstr7);
                                                $JBIMSstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $JBIMSstr8);
                                                $JBIMSstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $JBIMSstr9);
                                                $JBIMSstr11 = str_replace("#phone_no#", "" . $phone_no . "", $JBIMSstr10);
                                                $JBIMSstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $JBIMSstr11);
                                                $JBIMSstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $JBIMSstr12);
                                                $JBIMSstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $JBIMSstr13);
                                                $JBIMSstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $JBIMSstr14);
                                                $JBIMSstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $JBIMSstr15);
                                                $JBIMSstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $JBIMSstr16);
                                                $JBIMSstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $JBIMSstr17);
                                                $JBIMSstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $JBIMSstr18);
                                                //         $JBIMSstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $JBIMSstr19);
                                                //         $JBIMSstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $JBIMSstr20);
                                                //         $JBIMSstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $JBIMSstr21);
                                                //         $JBIMSstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $JBIMSstr22);
                                                $JBIMSstr24     = str_replace("#till_present#", "" . $till_present . "", $JBIMSstr19);
                                                $JBIMSstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $JBIMSstr24);
                                                $JBIMSstr26     = str_replace("#payment#", "" . $payment . "", $JBIMSstr25);
                                                $JBIMSstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $JBIMSstr26);
                                                $JBIMSstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $JBIMSstr27);
                                                $JBIMSstr29     = str_replace("#STATUS#", "Transaction Successful", $JBIMSstr28);
                                                $JBIMSstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $JBIMSstr29);
                                                $JBIMSstr31     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $JBIMSstr30);
                                                $JBIMSstr32     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $JBIMSstr31);
                                                $final_JBIMSstr = str_replace("#sponsor#", "" . $sponsor . "", $JBIMSstr32);

                                                $JBIMS_mail_arr = array(
                                                    //    'to'=>'kyciibf@gmail.com',
                                                    'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                    'from'    => $emailerJBIMSStr[0]['from'],
                                                    'subject' => $emailerJBIMSStr[0]['subject'],
                                                    'message' => $final_JBIMSstr,
                                                );

                                                //echo '<pre>',print_r($JBIMS_mail_arr),'</pre>';
                                                $this->Emailsending->mailsend_attch($JBIMS_mail_arr, $attachpath);
                                            }
                                        }

                                        //email send to sk datta and kavan for bank sponsor
                                        if ($user_info[0]['sponsor'] == 'bank') {
                                            $contact_mail_id = $user_info[0]['sponsor_contact_email'];
                                            $emailerBankStr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank'));
                                            if (count($emailerBankStr) > 0) {
                                                $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                                if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                                if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                                if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                                if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = '';}

                                                if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                                if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                                if ($user_info[0]['sponsor_contact_phone'] != 0) {$sponsor_contact_phone = $user_info[0]['sponsor_contact_phone'];} else { $sponsor_contact_phone = '';}

                                                $bankstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerBankStr[0]['emailer_text']);
                                                $bankstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr1);
                                                $bankstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr2);
                                                $bankstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $bankstr3);
                                                $bankstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $bankstr4);
                                                $bankstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $bankstr5);
                                                $bankstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $bankstr6);
                                                $bankstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $bankstr7);
                                                $bankstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $bankstr8);
                                                $bankstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $bankstr9);
                                                $bankstr11 = str_replace("#phone_no#", "" . $phone_no . "", $bankstr10);
                                                $bankstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $bankstr11);
                                                $bankstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $bankstr12);
                                                $bankstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $bankstr13);
                                                $bankstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $bankstr14);
                                                $bankstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $bankstr15);
                                                $bankstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $bankstr16);
                                                $bankstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $bankstr17);
                                                $bankstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $bankstr18);
                                                //         $bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
                                                //         $bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
                                                //         $bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
                                                //         $bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
                                                $bankstr24     = str_replace("#till_present#", "" . $till_present . "", $bankstr19);
                                                $bankstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $bankstr24);
                                                $bankstr26     = str_replace("#payment#", "" . $payment . "", $bankstr25);
                                                $bankstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $bankstr26);
                                                $bankstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $bankstr27);
                                                $bankstr29     = str_replace("#STATUS#", "Transaction Successful", $bankstr28);
                                                $bankstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $bankstr29);
                                                $bankstr31     = str_replace("#sponsor#", "" . $sponsor . "", $bankstr30);
                                                $bankstr32     = str_replace("#sponsor_bank_name#", "" . $user_info[0]['sponsor_bank_name'] . "", $bankstr31);
                                                $bankstr33     = str_replace("#sponsor_email#", "" . $user_info[0]['sponsor_email'] . "", $bankstr32);
                                                $bankstr34     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr33);
                                                $bankstr35     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr34);
                                                $bankstr36     = str_replace("#sponsor_contact_designation#", "" . $user_info[0]['sponsor_contact_designation'] . "", $bankstr35);
                                                $bankstr37     = str_replace("#sponsor_contact_std#", "" . $user_info[0]['sponsor_contact_std'] . "", $bankstr36);
                                                $bankstr38     = str_replace("#sponsor_contact_phone#", "" . $sponsor_contact_phone . "", $bankstr37);
                                                $bankstr39     = str_replace("#sponsor_contact_mobile#", "" . $user_info[0]['sponsor_contact_mobile'] . "", $bankstr38);
                                                $bankstr40     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $bankstr39);
                                                $bankstr41     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $bankstr40);
                                                $final_bankstr = str_replace("#sponsor_contact_email#", "" . $user_info[0]['sponsor_contact_email'] . "", $bankstr41);

                                                $bank_mail_arr = array(

                                                    'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                    'from'    => $emailerBankStr[0]['from'],
                                                    'subject' => $emailerBankStr[0]['subject'],
                                                    'message' => $final_bankstr,
                                                );

                                                $this->Emailsending->mailsend_attch($bank_mail_arr, $attachpath);
                                            }
                                        }

                                    }

                                }

                                //Manage Log
                                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;

                                $this->log_model->logJBIMStransaction("sbiepay", $pg_response, $responsedata[2]);

                                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                            } else {
                                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                            }
                        }
                    }

                }
                //

            }
            ///End of SBI B2B callback
            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
        } else {

            die("Please try again...");
        }
    }
    public function sbitransfail()
    {
        //delete_cookie('regid');
        //print_r($_REQUEST['encData']);exit;
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
            $get_user_regnum_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

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
                $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logJBIMStransaction("sbiepay", $pg_response, $responsedata[2]);
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

    //BIlldesk payment function
    public function handle_billdesk_response()
    {
        /* ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL); */

        if (isset($_REQUEST['transaction_response'])) {
            $response_encode = $_REQUEST['transaction_response'];
            $bd_response     = $this->billdesk_pg_model->verify_res($response_encode);
            $attachpath      = $invoiceNumber      = $admitcard_pdf      = '';

            $responsedata = $bd_response['payload'];

            $MerchantOrderNo = $responsedata['orderid']; // To DO: temp testing changes please remove it and use valid receipt id
            $transaction_no  = $responsedata['transactionid'];
            $merchIdVal      = $responsedata['mercid'];
            $Bank_Code       = $responsedata['bankid'];
            $encData         = $_REQUEST['transaction_response'];
            $auth_status     = $responsedata['auth_status'];

            $transaction_error_type = $responsedata['transaction_error_type'];
            $qry_api_response       = $this->billdesk_pg_model->billdeskqueryapi($MerchantOrderNo);
            if ($auth_status == "0300" && $qry_api_response['auth_status'] == '0300') {

                $get_user_regnum_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'id,ref_id,status,payment_option');
                //check user payment status is updated by s2s or not
                //    print_r($get_user_regnum_info);exit;

                if ($get_user_regnum_info[0]['status'] == 2) {
                    if ($get_user_regnum_info[0]['payment_option'] == 1 || $get_user_regnum_info[0]['payment_option'] == 4) {
                        $reg_id = $get_user_regnum_info[0]['ref_id'];
                        //$applicationNo = generate_mem_reg_num();
                        //Get membership number from 'JBIMS_membershipno' and update in 'JBIMS_candidates'
                        $applicationNo = generate_JBIMS_memreg($reg_id);
                        //update JBIMS registration table
                        $update_mem_data = array('isactive' => '1', 'regnumber' => $applicationNo);
                        $this->master_model->updateRecord('JBIMS_candidates', $update_mem_data, array('id' => $reg_id));
                        //get user information...
                        //$user_info=$this->master_model->getRecords('JBIMS_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
                        $user_info = $this->master_model->getRecords('JBIMS_candidates', array('id' => $reg_id));

                        $update_data = array('member_regnumber' => $applicationNo, 'sponsor' => $user_info[0]['sponsor'], 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                        $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                        //get payment details

                        //Query to get Payment details
                        $payment_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $user_info[0]['regnumber']), 'transaction_no,date,amount');

                        $upd_files    = array();
                        $photo_file   = 'p_' . $applicationNo . '.jpg';
                        $sign_file    = 's_' . $applicationNo . '.jpg';
                        $idproof_file = 'pr_' . $applicationNo . '.jpg';

                        /*    echo '</pre>$photo_file',$photo_file,'</pre>';
                        echo '</pre>$sign_file',$sign_file,'</pre>';
                        echo '</pre>$proof_file',$proof_file,'</pre>';
                        echo '</pre>photograph',$user_info[0]['photograph'],'</pre>';
                        echo '</pre>signature',$user_info[0]['signature'],'</pre>';*/

                        if (@rename("./uploads/JBIMS/photograph/" . $user_info[0]['photograph'], "./uploads/JBIMS/photograph/" . $photo_file)) {
                            $upd_files['photograph'] = $photo_file;}

                        if (@rename("./uploads/JBIMS/idproof/" . $user_info[0]['idproof'], "./uploads/JBIMS/idproof/" . $idproof_file)) {
                            $upd_files['idproof'] = $idproof_file;}

                        if (@rename("./uploads/JBIMS/signature/" . $user_info[0]['signature'], "./uploads/JBIMS/signature/" . $sign_file)) {
                            $upd_files['signature'] = $sign_file;}

                        //print_r($upd_files);exit;
                        if (count($upd_files) > 0) {
                            $this->master_model->updateRecord('JBIMS_candidates', $upd_files, array('id' => $reg_id));
                        }

                        //Manage Log
                        $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;

                        $this->log_model->logJBIMStransaction("sbiepay", $pg_response, $responsedata[2]);

                        //email to user
                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer'));
                        if (count($emailerstr) > 0) {
                            //echo 'in';
                            $username         = $user_info[0]['name'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                            $newstring1       = str_replace("#REG_NUM#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['emailer_text']);
                            $newstring2       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
                            $newstring3       = str_replace("#EMAIL#", "" . $user_info[0]['email_id'] . "", $newstring2);
                            $newstring4       = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring3);
                            $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                            $newstring6       = str_replace("#STATUS#", "Transaction Successful", $newstring5);
                            $final_str        = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring6);

                            $info_arr = array('to' => '' . $user_info[0]['email_id'] . ',dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                'from'                 => $emailerstr[0]['from'],
                                'subject'              => $emailerstr[0]['subject'],
                                'message'              => $final_str,
                                //'bcc'=>'dd.trg1@iibf.org.in'
                            );
                            //    echo '<pre>',print_r($info_arr),'</pre>';
                            //$this->send_mail($applicationNo);
                            //$this->send_sms($applicationNo);

                            //Invoice generation
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'pay_txn_id' => $get_user_regnum_info[0]['id'], 'exam_code' => 0, 'app_type' => 'J'));

                            if (count($getinvoice_number) > 0) {
                                $invoiceNumber = generate_JBIMS_invoice_number($getinvoice_number[0]['invoice_id']);
                                //    echo '<pre>',print_r($invoiceNumber),'</pre>';
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('JBIMS_invoice_no_prefix') . $invoiceNumber;
                                }
                                $attachment  = '';
                                $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $applicationNo, 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                $attachpath = genarate_JBIMS_invoice($getinvoice_number[0]['invoice_id']);
                                $mempdf     = $this->memberpdf($MerchantOrderNo);
                                $attachment = array($attachpath, $mempdf);
                                //echo $this->db->last_query();
                                //echo '<pre>update_data',print_r($update_data),'</pre>';
                                //echo '<pre>',print_r($attachpath),'</pre>';

                            }
                            //echo '<pre>user_info',print_r($user_info),'</pre>';exit;
                            //exit;
                            if ($attachment != '') {

                                $sms_newstring  = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $emailerstr[0]['sms_text']);
                                $sms_newstring1 = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring);
                                $sms_final_str  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $sms_newstring1);
                                $this->master_model->send_sms($user_info[0]['mobile_no'], $sms_final_str);
                                //$this->master_model->send_sms_trustsignal(intval(user_info[0]['mobile_no']),$sms_final_str,'template_id');

                                //if($this->Emailsending->mailsend($info_arr))
                                //if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
                                //if($this->Emailsending->mailsend_attch($info_arr,$attachpath))
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachment)) {

                                    //email send to sk datta and kavan for self sponsor
                                    if ($user_info[0]['sponsor'] == 'self') {
                                        $emailerJBIMSStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_self'));
                                        if (count($emailerJBIMSStr) > 0) {
                                            $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                            if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                            if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                            if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                            if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = '';}

                                            if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                            if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                            $JBIMSstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerJBIMSStr[0]['emailer_text']);
                                            $JBIMSstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr1);
                                            $JBIMSstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr2);
                                            $JBIMSstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $JBIMSstr3);
                                            $JBIMSstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $JBIMSstr4);
                                            $JBIMSstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $JBIMSstr5);
                                            $JBIMSstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $JBIMSstr6);
                                            $JBIMSstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $JBIMSstr7);
                                            $JBIMSstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $JBIMSstr8);
                                            $JBIMSstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $JBIMSstr9);
                                            $JBIMSstr11 = str_replace("#phone_no#", "" . $phone_no . "", $JBIMSstr10);
                                            $JBIMSstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $JBIMSstr11);
                                            $JBIMSstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $JBIMSstr12);
                                            $JBIMSstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $JBIMSstr13);
                                            $JBIMSstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $JBIMSstr14);
                                            $JBIMSstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $JBIMSstr15);
                                            $JBIMSstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $JBIMSstr16);
                                            $JBIMSstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $JBIMSstr17);
                                            $JBIMSstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $JBIMSstr18);
                                            //     $JBIMSstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $JBIMSstr19);
                                            //     $JBIMSstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $JBIMSstr20);
                                            //     $JBIMSstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $JBIMSstr21);
                                            //     $JBIMSstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $JBIMSstr22);
                                            $JBIMSstr24     = str_replace("#till_present#", "" . $till_present . "", $JBIMSstr19);
                                            $JBIMSstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $JBIMSstr24);
                                            $JBIMSstr26     = str_replace("#payment#", "" . $payment . "", $JBIMSstr25);
                                            $JBIMSstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $JBIMSstr26);
                                            $JBIMSstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $JBIMSstr27);
                                            $JBIMSstr29     = str_replace("#STATUS#", "Transaction Successful", $JBIMSstr28);
                                            $JBIMSstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $JBIMSstr29);
                                            $JBIMSstr31     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $JBIMSstr30);
                                            $JBIMSstr32     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $JBIMSstr31);
                                            $final_JBIMSstr = str_replace("#sponsor#", "" . $sponsor . "", $JBIMSstr32);

                                            $JBIMS_mail_arr = array(
                                                'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                //'to'=>'21bhavsartejasvi@gmail.com',
                                                //'to'=>'dharmvirm@iibf.org.in,training@iibf.org.in',
                                                //'to'=>'kyciibf@gmail.com',
                                                'from'    => $emailerJBIMSStr[0]['from'],
                                                'subject' => $emailerJBIMSStr[0]['subject'],
                                                'message' => $final_JBIMSstr,
                                            );

                                            //$this->Emailsending->mailsend($JBIMS_mail_arr);
                                            $this->Emailsending->mailsend_attch($JBIMS_mail_arr, $attachpath);
                                        }

                                    }
                                    //email send to sk datta and kavan for bank sponsor
                                    if ($user_info[0]['sponsor'] == 'bank') {
                                        //get bank contact email id
                                        $contact_mail_id = $user_info[0]['sponsor_contact_email'];

                                        $emailerBankStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank'));
                                        if (count($emailerBankStr) > 0) {
                                            $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                            if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                            if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                            if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                            if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = 'NO';}

                                            if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                            if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                            if ($user_info[0]['sponsor_contact_phone'] != 0) {$sponsor_contact_phone = $user_info[0]['sponsor_contact_phone'];} else { $sponsor_contact_phone = '';}

                                            $bankstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerBankStr[0]['emailer_text']);
                                            $bankstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr1);
                                            $bankstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr2);
                                            $bankstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $bankstr3);
                                            $bankstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $bankstr4);
                                            $bankstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $bankstr5);
                                            $bankstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $bankstr6);
                                            $bankstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $bankstr7);
                                            $bankstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $bankstr8);
                                            $bankstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $bankstr9);
                                            $bankstr11 = str_replace("#phone_no#", "" . $phone_no . "", $bankstr10);
                                            $bankstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $bankstr11);
                                            $bankstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $bankstr12);
                                            $bankstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $bankstr13);
                                            $bankstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $bankstr14);
                                            $bankstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $bankstr15);
                                            $bankstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $bankstr16);
                                            $bankstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $bankstr17);
                                            $bankstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $bankstr18);
                                            //         $bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
                                            //         $bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
                                            //         $bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
                                            //         $bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
                                            $bankstr24     = str_replace("#till_present#", "" . $till_present . "", $bankstr19);
                                            $bankstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $bankstr24);
                                            $bankstr26     = str_replace("#payment#", "" . $payment . "", $bankstr25);
                                            $bankstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $bankstr26);
                                            $bankstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $bankstr27);
                                            $bankstr29     = str_replace("#STATUS#", "Transaction Successful", $bankstr28);
                                            $bankstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $bankstr29);
                                            $bankstr31     = str_replace("#sponsor#", "" . $sponsor . "", $bankstr30);
                                            $bankstr32     = str_replace("#sponsor_bank_name#", "" . $user_info[0]['sponsor_bank_name'] . "", $bankstr31);
                                            $bankstr33     = str_replace("#sponsor_email#", "" . $user_info[0]['sponsor_email'] . "", $bankstr32);
                                            $bankstr34     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr33);
                                            $bankstr35     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr34);
                                            $bankstr36     = str_replace("#sponsor_contact_designation#", "" . $user_info[0]['sponsor_contact_designation'] . "", $bankstr35);
                                            $bankstr37     = str_replace("#sponsor_contact_std#", "" . $user_info[0]['sponsor_contact_std'] . "", $bankstr36);
                                            $bankstr38     = str_replace("#sponsor_contact_phone#", "" . $sponsor_contact_phone . "", $bankstr37);
                                            $bankstr39     = str_replace("#sponsor_contact_mobile#", "" . $user_info[0]['sponsor_contact_mobile'] . "", $bankstr38);
                                            $bankstr40     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $bankstr39);
                                            $bankstr41     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $bankstr40);
                                            $final_bankstr = str_replace("#sponsor_contact_email#", "" . $user_info[0]['sponsor_contact_email'] . "", $bankstr41);

                                            $bank_mail_arr = array(
                                                'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                'from'    => $emailerBankStr[0]['from'],
                                                'subject' => $emailerBankStr[0]['subject'],
                                                'message' => $final_bankstr,
                                            );
                                            //print_r($bank_mail_arr);exit;
                                            //$this->Emailsending->mailsend($bank_mail_arr);
                                            $this->Emailsending->mailsend_attch($bank_mail_arr, $attachpath);
                                        }
                                    }

                                    $this->session->set_flashdata('success', 'JBIMS registration has been done successfully !!');
                                    redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                                } else {
                                    redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                                }

                            } else {
                                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                            }

                            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                        } else {
                            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                        }
                    } else if ($get_user_regnum_info[0]['payment_option'] == 2 || $get_user_regnum_info[0]['payment_option'] == 3) {

                        $payment_option = '';
                        if ($get_user_regnum_info[0]['payment_option'] == 2) {
                            $payment_option = 'second';
                        } else if ($get_user_regnum_info[0]['payment_option'] == 3) {
                            $payment_option = 'Full';
                        }

                        $reg_id = $get_user_regnum_info[0]['ref_id'];

                        //update JBIMS registration table with installment status
                        $update_mem_data = array('payment' => $payment_option);
                        $this->master_model->updateRecord('JBIMS_candidates', $update_mem_data, array('id' => $reg_id));
                        //$user_info=$this->master_model->getRecords('JBIMS_candidates',array('id'=>$reg_id),'name,regnumber,email_id,photograph,signature,mobile_no,sponsor');
                        $user_info = $this->master_model->getRecords('JBIMS_candidates', array('id' => $reg_id));

                        //update payment transaction
                        $update_data = array('member_regnumber' => $user_info[0]['regnumber'], 'sponsor' => $user_info[0]['sponsor'], 'transaction_no' => $transaction_no, 'status' => 1, 'transaction_details' => $responsedata[2] . " - " . $responsedata[7], 'auth_code' => '0300', 'bankcode' => $responsedata[8], 'paymode' => $responsedata[5], 'callback' => 'B2B');
                        $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));

                        //maintain log in for updated transaction
                        $log_title                   = "Installment payment";
                        $update_info['membershipno'] = $user_info[0]['regnumber'];
                        $log_message                 = serialize($update_mem_data);
                        $this->JBIMSmodel->create_log($log_title, $log_message);

                        //Query to get Payment details
                        $payment_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo, 'member_regnumber' => $user_info[0]['regnumber']), 'transaction_no,date,amount');

                        //email to user
                        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer'));
                        if (count($emailerstr) > 0) {
                            $username         = $user_info[0]['name'];
                            $userfinalstrname = preg_replace('#[\s]+#', ' ', $username);
                            $newstring1       = str_replace("#REG_NUM#", "" . $user_info[0]['regnumber'] . "", $emailerstr[0]['emailer_text']);
                            $newstring2       = str_replace("#USERNAME#", "" . $userfinalstrname . "", $newstring1);
                            $newstring3       = str_replace("#EMAIL#", "" . $user_info[0]['email_id'] . "", $newstring2);
                            $newstring4       = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $newstring3);
                            $newstring5       = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $newstring4);
                            $newstring6       = str_replace("#STATUS#", "Transaction Successful", $newstring5);
                            $final_str        = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $newstring6);

                            $info_arr = array('to' => '' . $user_info[0]['email_id'] . ',dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                'from'                 => $emailerstr[0]['from'],
                                'subject'              => $emailerstr[0]['subject'],
                                'message'              => $final_str,
                                //'bcc'=>'dd.trg1@iibf.org.in'
                            );
                            //$this->send_mail($applicationNo);
                            //$this->send_sms($applicationNo);

                            //Invoice generation
                            $getinvoice_number = $this->master_model->getRecords('exam_invoice', array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));

                            if (count($getinvoice_number) > 0) {
                                $invoiceNumber = generate_JBIMS_invoice_number($getinvoice_number[0]['invoice_id']);
                                //    echo '<pre>',print_r($invoiceNumber),'</pre>';
                                if ($invoiceNumber) {
                                    $invoiceNumber = $this->config->item('JBIMS_invoice_no_prefix') . $invoiceNumber;
                                }
                                $attachment  = '';
                                $update_data = array('invoice_no' => $invoiceNumber, 'member_no' => $user_info[0]['regnumber'], 'transaction_no' => $transaction_no, 'date_of_invoice' => date('Y-m-d H:i:s'), 'modified_on' => date('Y-m-d H:i:s'));
                                $this->db->where('pay_txn_id', $get_user_regnum_info[0]['id']);
                                $this->master_model->updateRecord('exam_invoice', $update_data, array('receipt_no' => $MerchantOrderNo, 'exam_code' => 0, 'app_type' => 'J', 'pay_txn_id' => $get_user_regnum_info[0]['id']));
                                $attachpath = genarate_JBIMS_invoice($getinvoice_number[0]['invoice_id']);
                                $mempdf     = $this->memberpdf($MerchantOrderNo);
                                $attachment = array($attachpath, $mempdf);
                                //echo $this->db->last_query();
                                //echo '<pre>update_data',print_r($update_data),'</pre>';
                                //echo '<pre>',print_r($attachpath),'</pre>';

                            }

                            if ($attachment != '') {
                                $sms_newstring  = str_replace("#fee#", "" . $payment_info[0]['amount'] . "", $emailerstr[0]['sms_text']);
                                $sms_newstring1 = str_replace("#transaction_no#", "" . $payment_info[0]['transaction_no'] . "", $sms_newstring);
                                $sms_final_str  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $sms_newstring1);
                                $this->master_model->send_sms($user_info[0]['mobile_no'], $sms_final_str);
                                //$this->master_model->send_sms_trustsignal(intval($user_info[0]['mobile_no']),$sms_final_str,'template_id');

                                //$this->Emailsending->mailsend($info_arr);
                                if ($this->Emailsending->mailsend_attch($info_arr, $attachment)) {

                                    //email send to sk datta and kavan for JBIMS sponsor
                                    if ($user_info[0]['sponsor'] == 'self') {
                                        $emailerJBIMSStr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_self'));
                                        if (count($emailerJBIMSStr) > 0) {
                                            $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                            if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                            if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                            if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                            if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = 'No';}

                                            if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}
                                            //echo $payment;exit;
                                            if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                            $JBIMSstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerJBIMSStr[0]['emailer_text']);
                                            $JBIMSstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr1);
                                            $JBIMSstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $JBIMSstr2);
                                            $JBIMSstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $JBIMSstr3);
                                            $JBIMSstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $JBIMSstr4);
                                            $JBIMSstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $JBIMSstr5);
                                            $JBIMSstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $JBIMSstr6);
                                            $JBIMSstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $JBIMSstr7);
                                            $JBIMSstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $JBIMSstr8);
                                            $JBIMSstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $JBIMSstr9);
                                            $JBIMSstr11 = str_replace("#phone_no#", "" . $phone_no . "", $JBIMSstr10);
                                            $JBIMSstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $JBIMSstr11);
                                            $JBIMSstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $JBIMSstr12);
                                            $JBIMSstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $JBIMSstr13);
                                            $JBIMSstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $JBIMSstr14);
                                            $JBIMSstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $JBIMSstr15);
                                            $JBIMSstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $JBIMSstr16);
                                            $JBIMSstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $JBIMSstr17);
                                            $JBIMSstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $JBIMSstr18);
                                            //         $JBIMSstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $JBIMSstr19);
                                            //         $JBIMSstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $JBIMSstr20);
                                            //         $JBIMSstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $JBIMSstr21);
                                            //         $JBIMSstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $JBIMSstr22);
                                            $JBIMSstr24     = str_replace("#till_present#", "" . $till_present . "", $JBIMSstr19);
                                            $JBIMSstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $JBIMSstr24);
                                            $JBIMSstr26     = str_replace("#payment#", "" . $payment . "", $JBIMSstr25);
                                            $JBIMSstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $JBIMSstr26);
                                            $JBIMSstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $JBIMSstr27);
                                            $JBIMSstr29     = str_replace("#STATUS#", "Transaction Successful", $JBIMSstr28);
                                            $JBIMSstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $JBIMSstr29);
                                            $JBIMSstr31     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $JBIMSstr30);
                                            $JBIMSstr32     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $JBIMSstr31);
                                            $final_JBIMSstr = str_replace("#sponsor#", "" . $sponsor . "", $JBIMSstr32);

                                            $JBIMS_mail_arr = array(
                                                //    'to'=>'kyciibf@gmail.com',
                                                'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                'from'    => $emailerJBIMSStr[0]['from'],
                                                'subject' => $emailerJBIMSStr[0]['subject'],
                                                'message' => $final_JBIMSstr,
                                            );

                                            //echo '<pre>',print_r($JBIMS_mail_arr),'</pre>';
                                            $this->Emailsending->mailsend_attch($JBIMS_mail_arr, $attachpath);
                                        }
                                    }

                                    //email send to sk datta and kavan for bank sponsor
                                    if ($user_info[0]['sponsor'] == 'bank') {
                                        $contact_mail_id = $user_info[0]['sponsor_contact_email'];
                                        $emailerBankStr  = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer_bank'));
                                        if (count($emailerBankStr) > 0) {
                                            $sponsor = ucwords($user_info[0]['sponsor']) . ' Sponsor';

                                            if ($user_info[0]['phone_no'] != 0) {$phone_no = $user_info[0]['phone_no'];} else { $phone_no = '';}

                                            if ($user_info[0]['work_from_year'] != 0) {$work_from_year = $user_info[0]['work_from_year'];} else { $work_from_year = '';}

                                            if ($user_info[0]['work_to_year'] != 0) {$work_to_year = $user_info[0]['work_to_year'];} else { $work_to_year = '';}

                                            if ($user_info[0]['till_present'] == 1) {$till_present = 'Yes';} else { $till_present = '';}

                                            if (strtolower($user_info[0]['payment']) == 'full') {$payment = 'Full Paid';} else { $payment = ucwords($user_info[0]['payment']) . ' Installment';}

                                            if ($user_info[0]['work_experiance'] != 0) {$work_experiance = $user_info[0]['work_experiance'];} else { $work_experiance = '';}

                                            if ($user_info[0]['sponsor_contact_phone'] != 0) {$sponsor_contact_phone = $user_info[0]['sponsor_contact_phone'];} else { $sponsor_contact_phone = '';}

                                            $bankstr1  = str_replace("#name#", "" . $user_info[0]['name'] . "", $emailerBankStr[0]['emailer_text']);
                                            $bankstr2  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr1);
                                            $bankstr3  = str_replace("#regnumber#", "" . $user_info[0]['regnumber'] . "", $bankstr2);
                                            $bankstr4  = str_replace("#dob#", "" . $user_info[0]['dob'] . "", $bankstr3);
                                            $bankstr5  = str_replace("#address1#", "" . $user_info[0]['address1'] . "", $bankstr4);
                                            $bankstr6  = str_replace("#address2#", "" . $user_info[0]['address2'] . "", $bankstr5);
                                            $bankstr7  = str_replace("#address3#", "" . $user_info[0]['address3'] . "", $bankstr6);
                                            $bankstr8  = str_replace("#address4#", "" . $user_info[0]['address4'] . "", $bankstr7);
                                            $bankstr9  = str_replace("#pincode_address#", "" . $user_info[0]['pincode_address'] . "", $bankstr8);
                                            $bankstr10 = str_replace("#std_code#", "" . $user_info[0]['std_code'] . "", $bankstr9);
                                            $bankstr11 = str_replace("#phone_no#", "" . $phone_no . "", $bankstr10);
                                            $bankstr12 = str_replace("#mobile_no#", "" . $user_info[0]['mobile_no'] . "", $bankstr11);
                                            $bankstr13 = str_replace("#email_id#", "" . $user_info[0]['email_id'] . "", $bankstr12);
                                            $bankstr14 = str_replace("#alt_email_id#", "" . $user_info[0]['alt_email_id'] . "", $bankstr13);
                                            $bankstr15 = str_replace("#graduation#", "" . $user_info[0]['graduation'] . "", $bankstr14);
                                            $bankstr16 = str_replace("#post_graduation#", "" . $user_info[0]['post_graduation'] . "", $bankstr15);
                                            $bankstr17 = str_replace("#special_qualification#", "" . $user_info[0]['special_qualification'] . "", $bankstr16);
                                            $bankstr18 = str_replace("#name_employer#", "" . $user_info[0]['name_employer'] . "", $bankstr17);
                                            $bankstr19 = str_replace("#position#", "" . $user_info[0]['position'] . "", $bankstr18);
                                            //         $bankstr20 = str_replace("#work_from_month#", "".$user_info[0]['work_from_month']."",  $bankstr19);
                                            //         $bankstr21 = str_replace("#work_from_year#", "".$work_from_year."",  $bankstr20);
                                            //         $bankstr22 = str_replace("#work_to_month#", "".$user_info[0]['work_to_month']."",  $bankstr21);
                                            //         $bankstr23 = str_replace("#work_to_year#", "".$work_to_year."",  $bankstr22);
                                            $bankstr24     = str_replace("#till_present#", "" . $till_present . "", $bankstr19);
                                            $bankstr25     = str_replace("#work_experiance#", "" . $work_experiance . "", $bankstr24);
                                            $bankstr26     = str_replace("#payment#", "" . $payment . "", $bankstr25);
                                            $bankstr27     = str_replace("#TRANSACTION_NO#", "" . $payment_info[0]['transaction_no'] . "", $bankstr26);
                                            $bankstr28     = str_replace("#AMOUNT#", "" . $payment_info[0]['amount'] . "", $bankstr27);
                                            $bankstr29     = str_replace("#STATUS#", "Transaction Successful", $bankstr28);
                                            $bankstr30     = str_replace("#TRANSACTION_DATE#", "" . date('Y-m-d H:i:sA', strtotime($payment_info[0]['date'])) . "", $bankstr29);
                                            $bankstr31     = str_replace("#sponsor#", "" . $sponsor . "", $bankstr30);
                                            $bankstr32     = str_replace("#sponsor_bank_name#", "" . $user_info[0]['sponsor_bank_name'] . "", $bankstr31);
                                            $bankstr33     = str_replace("#sponsor_email#", "" . $user_info[0]['sponsor_email'] . "", $bankstr32);
                                            $bankstr34     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr33);
                                            $bankstr35     = str_replace("#sponsor_contact_person#", "" . $user_info[0]['sponsor_contact_person'] . "", $bankstr34);
                                            $bankstr36     = str_replace("#sponsor_contact_designation#", "" . $user_info[0]['sponsor_contact_designation'] . "", $bankstr35);
                                            $bankstr37     = str_replace("#sponsor_contact_std#", "" . $user_info[0]['sponsor_contact_std'] . "", $bankstr36);
                                            $bankstr38     = str_replace("#sponsor_contact_phone#", "" . $sponsor_contact_phone . "", $bankstr37);
                                            $bankstr39     = str_replace("#sponsor_contact_mobile#", "" . $user_info[0]['sponsor_contact_mobile'] . "", $bankstr38);
                                            $bankstr40     = str_replace("#gender#", "" . $user_info[0]['gender'] . "", $bankstr39);
                                            $bankstr41     = str_replace("#current_role#", "" . $user_info[0]['current_role'] . "", $bankstr40);
                                            $final_bankstr = str_replace("#sponsor_contact_email#", "" . $user_info[0]['sponsor_contact_email'] . "", $bankstr41);

                                            $bank_mail_arr = array(

                                                'to'      => 'dd.trg1@iibf.org.in ,iibfdevp@esds.co.in',
                                                'from'    => $emailerBankStr[0]['from'],
                                                'subject' => $emailerBankStr[0]['subject'],
                                                'message' => $final_bankstr,
                                            );

                                            $this->Emailsending->mailsend_attch($bank_mail_arr, $attachpath);
                                        }
                                    }

                                }

                            }

                            //Manage Log
                            $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;

                            $this->log_model->logJBIMStransaction("sbiepay", $pg_response, $responsedata[2]);

                            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                        } else {
                            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
                        }
                    }
                }

            } elseif ($auth_status == "0002") {
                $update_data = array('transaction_no' => $transaction_no, 'status' => 2, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0002', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');
                $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                //Manage Log
                $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                $this->log_model->logJBIMStransaction("Billdesk", $pg_response, $responsedata['transaction_error_type']);

                redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
            }
            //    billdesk fail
            else {

                $get_user_regnum_info = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $MerchantOrderNo), 'ref_id,status');

                if ($get_user_regnum_info[0]['status'] != 0 && $get_user_regnum_info[0]['status'] == 2) {

                    $update_data = array('transaction_no' => $transaction_no, 'status' => 0, 'transaction_details' => $responsedata['transaction_error_type'] . " - " . $responsedata['transaction_error_desc'], 'auth_code' => '0399', 'bankcode' => $responsedata['bankid'], 'paymode' => $responsedata['txn_process_type'], 'callback' => 'B2B');
                    $this->master_model->updateRecord('JBIMS_payment_transaction', $update_data, array('receipt_no' => $MerchantOrderNo));
                    //Manage Log
                    $pg_response = "encData=" . $encData . "&merchIdVal=" . $merchIdVal . "&Bank_Code=" . $Bank_Code;
                    $this->log_model->logJBIMStransaction("Billdesk", $pg_response, $responsedata['transaction_error_type']);
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

            }

            redirect(base_url() . 'JBIMS/details/' . base64_encode($MerchantOrderNo));
        } else {
            die("Please try again...");
        }
    }
    //Thank you message to end user
    public function details($order_no = null)
    {
        if ($order_no != null) {
            $data = array();
            $this->session->unset_userdata("insertdata");
            //get user details
            $this->db->join('JBIMS_candidates', 'JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
            $user_info_details = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => base64_decode($order_no)));
            //    echo '<pre>',print_r($user_info_details),'</pre>';
            if (empty($user_info_details)) {
                redirect(base_url() . 'JBIMS/self');
            }

            $data = array('middle_content' => 'JBIMS/thankyou', 'user_info_details' => $user_info_details);
            $this->load->view('JBIMS/common_view_fullwidth', $data);

        } else {
            redirect(base_url() . 'JBIMS/self');
        }
    }

    public function details_test($order_no = null)
    {
        if ($order_no != null) {
            $data = array();
            $this->session->unset_userdata("insertdata");
            //get user details
            $this->db->join('JBIMS_candidates', 'JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
            $user_info_details = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => base64_decode($order_no)));
            //echo $this->db->last_query();exit;
            //echo '<pre>',print_r($user_info_details),'</pre>';
            //exit;
            if (empty($user_info_details)) {
                redirect(base_url() . 'JBIMS/self');
            }

            $data = array('middle_content' => 'JBIMS/thankyou', 'user_info_details' => $user_info_details);
            $this->load->view('JBIMS/common_view_fullwidth', $data);

        } else {
            redirect(base_url() . 'JBIMS/self');
        }
    }

    public function exampdf($order_no)
    {

        $this->db->join('JBIMS_candidates', 'JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
        $user_info_details = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => base64_decode($order_no)));

        if (empty($user_info_details)) {
            redirect(base_url() . 'JBIMS/self');
        }

        //echo '<pre>';print_r($user_info_details);die;
        if ($user_info_details[0]['status'] == '1') {$status = 'Success';} else { $status = 'Unsuccess';}
        $imagePath  = base_url() . 'uploads/JBIMS/photograph/' . $user_info_details[0]['photograph'];
        $imagePath1 = base_url() . 'uploads/JBIMS/signature/' . $user_info_details[0]['signature'];
        $imagePath2 = base_url() . 'uploads/JBIMS/idproof/' . $user_info_details[0]['idproof'];
        if (strtolower($user_info_details[0]['payment']) == 'full') {
            $payment = 'Full Paid';
        } else {
            $payment = ucfirst($user_info_details[0]['payment']) . ' Installment';
        }

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;">
			<td colspan="4" height="1" align="center" ><img src="' . base_url() . 'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Enrollment in JBIMS 2022-2023 ; February 2022</h3></strong></td></tr>
		<tr><td style="text-align:right"><img src="' . $imagePath . '" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['regnumber'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['name'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['gender'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['iibf_membership_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . date('d-M-Y', strtotime($user_info_details[0]['dob'])) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['address1'] . ' ' . $user_info_details[0]['address2'] . ' ' . $user_info_details[0]['address3'] . ' ' . $user_info_details[0]['address4'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['pincode_address'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['mobile_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['email_id'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $payment . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['amount'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . ucfirst($user_info_details[0]['sponsor']) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['transaction_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $status . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['date'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath2 . '" height="100" width="100" /></td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath1 . '" height="100" width="100" /></td>
				</tr>

				</tbody>
			</table>

			</td>
		</tr>
	</tbody>
</table>';
        //echo $html;die;
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

    //sent pdf on mail
    public function memberpdf($order_no)
    {

        $this->db->join('JBIMS_candidates', 'JBIMS_candidates.id=JBIMS_payment_transaction.ref_id');
        $user_info_details = $this->master_model->getRecords('JBIMS_payment_transaction', array('receipt_no' => $order_no));

        if (empty($user_info_details)) {
            redirect(base_url() . 'JBIMS/self');
        }

        //echo '<pre>';print_r($user_info_details);die;
        if ($user_info_details[0]['status'] == '1') {$status = 'Success';} else { $status = 'Unsuccess';}
        $imagePath  = base_url() . 'uploads/JBIMS/photograph/' . $user_info_details[0]['photograph'];
        $imagePath1 = base_url() . 'uploads/JBIMS/signature/' . $user_info_details[0]['signature'];
        $imagePath2 = base_url() . 'uploads/JBIMS/idproof/' . $user_info_details[0]['idproof'];
        if (strtolower($user_info_details[0]['payment']) == 'full') {
            $payment = 'Full Paid';
        } else {
            $payment = ucfirst($user_info_details[0]['payment']) . ' Installment';
        }

        $html = '<table width="754" cellspacing="0" cellpadding="0" border="0" align="center" style=" background: #fff ; border: 1px solid #000; padding:25px;">
	<tbody>
		<tr><td colspan="4" align="left">&nbsp;</td> </tr>
		<tr>
			<td colspan="4" align="center" height="25">
			<span id="1001a1" class="alert"></span>
			</td>
		</tr>

		<tr style="border-bottom:solid 1px #000;">
			<td colspan="4" height="1" align="center" ><img src="' . base_url() . 'assets/images/logo1.png"></td>
		</tr>
		<tr></tr>
		<tr><td style="text-align:center"><strong><h3>Enrollment in JBIMS 2022-2023 ; February 2022</h3></strong></td></tr>
		<tr><td style="text-align:right"><img src="' . $imagePath . '" height="100" width="100" /></td>
		</tr>
		<tr>
			<td colspan="4">
			</hr>

			<table width="98%" cellspacing="2" cellpadding="2" border="0" align="center">
				<tbody>
				<tr>
					<td class="tablecontent2" width="51%">Membership No : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['regnumber'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Name : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['name'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">Gender : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['gender'] . '</td>
				</tr>
				<tr>
					<td class="tablecontent2" width="51%">IIBF Membership No: </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['iibf_membership_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Date of Birth : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . date('d-M-Y', strtotime($user_info_details[0]['dob'])) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Address : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['address1'] . ' ' . $user_info_details[0]['address2'] . ' ' . $user_info_details[0]['address3'] . ' ' . $user_info_details[0]['address4'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Pincode : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['pincode_address'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Mobile Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['mobile_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Email ID : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['email_id'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Payment : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $payment . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Amount : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['amount'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Sponsor : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . ucfirst($user_info_details[0]['sponsor']) . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Number : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['transaction_no'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Status : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $status . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Transaction Date : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"> ' . $user_info_details[0]['date'] . '</td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Id Proof : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath2 . '" height="100" width="100" /></td>
				</tr>

				<tr>
					<td class="tablecontent2" width="51%">Signature : </td>
					<td class="tablecontent2" width="49%" valign="middle" nowrap="nowrap" align="left"><img src="' . $imagePath1 . '" height="100" width="100" /></td>
				</tr>

				</tbody>
			</table>

			</td>
		</tr>
	</tbody>
</table>';
        //echo $html;die;
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
        $file = $pdf->Output('exam_JBIMS.pdf', 'F');
        //file_put_contents($pdfFilePath, $file);
        return 'exam_JBIMS.pdf';

    }

    public function custom_invoie_generation_new()
    {
        //$attachpath=genarate_JBIMS_invoice($getinvoice_number[0]['invoice_id']);
        $attachpath = custom_genarate_JBIMS_invoice_new('3239207');
        echo $attachpath;
        exit;
        $emailerstr = $this->master_model->getRecords('emailer', array('emailer_name' => 'JBIMS_emailer'));
        if (count($emailerstr) > 0) {
            $final_str = 'PFA';
            $info_arr  = array('to' => '21bhavsartejasvi@gmail.com', 'from' => $emailerstr[0]['from'], 'subject' => $emailerstr[0]['subject'], 'message' => $final_str);

        }
        //$attachpath = custome_genarate_duplicatecert_invoice('14185');
        $attachpath = custom_genarate_JBIMS_invoice('716886');
        if ($attachpath != '') {
            if ($this->Emailsending->mailsend_attch($info_arr, $attachpath)) {
                echo "mail send successfully";
            } else {
                echo "Error while mail sending";
            }
        }

    }
    public function convert_number_to_words($amt)
    {
        $number   = $amt;
        $no       = round($number);
        $point    = round($number - $no, 2) * 100;
        $hundred  = null;
        $digits_1 = strlen($no);
        $i        = 0;
        $str      = array();
        $words    = array('0' => '', '1'          => 'One', '2'       => 'Two',
            '3'                   => 'Three', '4'     => 'Four', '5'      => 'Five', '6' => 'Six',
            '7'                   => 'Seven', '8'     => 'Eight', '9'     => 'Nine',
            '10'                  => 'Ten', '11'      => 'Eleven', '12'   => 'Twelve',
            '13'                  => 'Thirteen', '14' => 'Fourteen',
            '15'                  => 'Fifteen', '16'  => 'Sixteen', '17'  => 'Seventeen',
            '18'                  => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30'                  => 'Thirty', '40'   => 'Forty', '50'    => 'Fifty',
            '60'                  => 'Sixty', '70'    => 'Seventy',
            '80'                  => 'Eighty', '90'   => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number  = floor($no % $divider);
            $no      = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural  = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? 'and ' : null;
                $str[]   = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else {
                $str[] = null;
            }

        }
        $str    = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        "." . $words[$point / 10] . " " .
        $words[$point = $point % 10] : '';
        //echo $result . "Rupees  " . $points . " Paise";
        return $result;
    }
    /*public function convert_number_to_words($number) {
$hyphen      = '-';
$conjunction = ' and ';
$separator   = ', ';
$negative    = 'negative ';
$decimal     = ' point ';
$dictionary  = array(
0                   => 'zero',
1                   => 'one',
2                   => 'two',
3                   => 'three',
4                   => 'four',
5                   => 'five',
6                   => 'six',
7                   => 'seven',
8                   => 'eight',
9                   => 'nine',
10                  => 'ten',
11                  => 'eleven',
12                  => 'twelve',
13                  => 'thirteen',
14                  => 'fourteen',
15                  => 'fifteen',
16                  => 'sixteen',
17                  => 'seventeen',
18                  => 'eighteen',
19                  => 'nineteen',
20                  => 'twenty',
30                  => 'thirty',
40                  => 'fourty',
50                  => 'fifty',
60                  => 'sixty',
70                  => 'seventy',
80                  => 'eighty',
90                  => 'ninety',
100                 => 'hundred',
1000                => 'thousand',
1000000             => 'million',
1000000000          => 'billion',
1000000000000       => 'trillion',
1000000000000000    => 'quadrillion',
1000000000000000000 => 'quintillion'
);
if (!is_numeric($number)) {
return false;
}
if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
// overflow
trigger_error(
'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
E_USER_WARNING
);
return false;
}
if ($number < 0) {
return $negative . convert_number_to_words(abs($number));
}
$string = $fraction = null;
if (strpos($number, '.') !== false) {
list($number, $fraction) = explode('.', $number);
}
switch (true) {
case $number < 21:
$string = $dictionary[$number];
break;
case $number < 100:
$tens   = ((int) ($number / 10)) * 10;
$units  = $number % 10;
$string = $dictionary[$tens];
if ($units) {
$string .= $hyphen . $dictionary[$units];
}
break;
case $number < 1000:
$hundreds  = $number / 100;
$remainder = $number % 100;
$string = $dictionary[$hundreds] . ' ' . $dictionary[100];
if ($remainder) {
$string .= $conjunction . $this->convert_number_to_words($remainder);
}
break;
default:
$baseUnit = pow(1000, floor(log($number, 1000)));
$numBaseUnits = (int) ($number / $baseUnit);
$remainder = $number % $baseUnit;
$string = $this->convert_number_to_words($remainder) . ' ' . $dictionary[$baseUnit];
if ($remainder) {
$string .= $remainder < 100 ? $conjunction : $separator;
$string .= $this->convert_number_to_words($remainder);
}
break;
}
if (null !== $fraction && is_numeric($fraction)) {
$string .= $decimal;
$words = array();
foreach (str_split((string) $fraction) as $number) {
$words[] = $dictionary[$number];
}
$string .= implode(' ', $words);
}
return $string;
}*/
}
